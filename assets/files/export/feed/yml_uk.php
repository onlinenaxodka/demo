<?php

//$start = microtime(true);

//XML create

$xml = new DomDocument('1.0', 'utf-8');

$yml_catalog = $xml->appendChild($xml->createElement('yml_catalog'));
$yml_catalog->setAttribute('date', date("Y-m-d H:i"));

$shop = $yml_catalog->appendChild($xml->createElement('shop'));

include_once __DIR__ . '/../../../../config.php';

//$user_ids = '407,496,1799,1973,3171,4479,5856,6766';
$user_ids = '1799,6766';

//$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1 GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$category_linkname_children = array();

while ($catalog = mysqli_fetch_assoc($query)) {

	$category_linkname_children[] = strval('\''.$catalog['linkname'].'\'');

}

$offers = $shop->appendChild($xml->createElement('offers'));

$n = 0;

if (count($category_linkname_children) > 0) {

	$category_linkname_separated = implode(",", $category_linkname_children);

	/*$sql_goods = "
	SELECT g.id, g.name, g.category, g.parameters, gd.description FROM goods g 
	LEFT JOIN goods_description gd ON gd.goods_id=g.id 
	WHERE g.user_id IN ({$user_ids}) AND g.category IN ({$category_linkname_separated}) AND g.photo NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND g.availability>0 AND g.status=1 AND gd.lang='uk'";*/
	$sql_goods = "
	SELECT g.id, g.name, g.category, g.parameters, gd.description FROM goods g 
	LEFT JOIN goods_description gd ON gd.goods_id=g.id 
	WHERE g.user_id IN ({$user_ids}) AND g.category IN ({$category_linkname_separated}) AND g.photo NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND g.status=1 AND gd.lang='uk'";
	$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

	if (mysqli_num_rows($query_goods) > 0) {

		while ($goods = mysqli_fetch_assoc($query_goods)) {

			$goods_id = $goods['id'];

			$offer = $offers->appendChild($xml->createElement('offer'));
			$offer->setAttribute('id', $goods_id);

			$goods['name'] = json_decode($goods['name'], true);
			$goods_name = $goods['name']['uk'];

			$name = $offer->appendChild($xml->createElement('name'));
			$name->appendChild($xml->createTextNode($goods_name));

			$goods['parameters'] = json_decode($goods['parameters'], true);

			if (!empty($goods['parameters']['uk']['Виробник']))
				$goods_vendor = $goods['parameters']['uk']['Виробник'];
			if (!empty($goods['parameters']['uk']['Бренд']))
				$goods_vendor = $goods['parameters']['uk']['Бренд'];
			if (!empty($goods_vendor_uk)) {

				$vendor = $offer->appendChild($xml->createElement('vendor'));
				$vendor->appendChild($xml->createTextNode($goods_vendor));

			}

			$goods_description_uk = clearStr($goods['description']);

			$description = $offer->appendChild($xml->createElement('description'));
			$description->appendChild($xml->createCDATASection($goods_description_uk));

			$goods['keys'] = json_decode($goods['keys'], true);

			$keywords = $offer->appendChild($xml->createElement('keywords'));
			$keywords->appendChild($xml->createTextNode($goods['keys']['uk']));
			
			$parameters_names_no = array('Производитель', 'Виробник', 'Вес (кг)', 'Вага (кг)', 'Глубина (см)', 'Глибина (см)', 'Ширина (см)', 'Высота (см)', 'Висота (см)', 'Артикул', 'артикул', 'Бренд', '-', ' ', '');
			$parameters_values_no = array('-', ' ', '');

			foreach ($goods['parameters']['uk'] as $parameters_key => $parameters_value) {

				if (!in_array($parameters_key, $parameters_names_no) and !in_array($parameters_value, $parameters_values_no)) {

					$param = $offer->appendChild($xml->createElement('param'));
					$param->appendChild($xml->createTextNode($parameters_value));
					$param->setAttribute('name', $parameters_key);

				}

			}

			$n++;

			//echo $n." - ".$goods_id."\n";

			//if ($n == 10) break;

		}

	}

}

mysqli_close($db);

$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true

if ($xml->save(__DIR__.'/yml_uk.xml')) {

	echo "The yml_uk.xml feed update completed successfully!\n";

} else {

	echo "Failed to save data feed yml_uk.xml file. The file may not have sufficient permissions.\n";

}

//$time = microtime(true) - $start;
$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time." - count: ".$n."\n";

function clearStr($str) {
	
	$arr = str_split($str);
	$str_new = '';
	$tag = false;

	for ($i=0; $i < count($arr); $i++) {

		if ($arr[$i] == '<') $tag = true;
		if ($arr[$i-1] == '>') $tag = false;

		if (!$tag) {
			$str_new .= $arr[$i];
		}

	}

	$str_new = str_replace("&mdash;", "-", $str_new);
	$str_new = str_replace("&ndash;", "-", $str_new);
	$str_new = str_replace("&nbsp;", " ", $str_new);
	$str_new = str_replace("&amp;", "&", $str_new);

	$str_new = str_replace(" \n", "\n", $str_new);
	$str_new = str_replace("\n ", "\n", $str_new);
	$str_new = str_replace("\r\n", "\n", $str_new);
	$str_new = str_replace("\n\n", "\n", $str_new);
	$str_new = str_replace("\n \n", "\n", $str_new);

	$str_new = str_replace(",,", ",", $str_new);
	$str_new = str_replace("..", ".", $str_new);
	$str_new = str_replace("--", "-", $str_new);
	
	$str_new = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $str_new);

	$str_new = str_replace("     ", " ", $str_new);
	$str_new = str_replace("    ", " ", $str_new);
	$str_new = str_replace("   ", " ", $str_new);
	$str_new = str_replace("  ", " ", $str_new);

	$str_new = str_replace("<br>", "", $str_new);
	$str_new = str_replace("<br/>", "", $str_new);
	$str_new = str_replace("<br />", "", $str_new);

	$arr = str_split($str_new);
	$str_new = '';

	for ($i=0; $i < count($arr); $i++) {

		$str_new .= $arr[$i];
		
		if ($arr[$i] == '.' and $arr[$i+1] != ' ' and $arr[$i+2] != '.') {
			$str_new .= ' ';
		}

	}

	$str_new = trim($str_new);

	return $str_new;

}