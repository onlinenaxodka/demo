<?php

//$start = microtime(true);

$domen_name = 'https://onlinenaxodka.com';

//XML create

$xml = new DomDocument('1.0', 'utf-8');

$yml_catalog = $xml->appendChild($xml->createElement('yml_catalog'));

$yml_catalog->setAttribute('date', date("Y-m-d H:i"));

$shop = $yml_catalog->appendChild($xml->createElement('shop'));

$name = $shop->appendChild($xml->createElement('name'));
$name->appendChild($xml->createTextNode('ONLINE NAXODKA'));

$company = $shop->appendChild($xml->createElement('company'));
$company->appendChild($xml->createTextNode('ONLINE NAXODKA'));

$url = $shop->appendChild($xml->createElement('url'));
$url->appendChild($xml->createTextNode($domen_name));

$currencies = $shop->appendChild($xml->createElement('currencies'));

$currency = $currencies->appendChild($xml->createElement('currency'));
$currency->setAttribute('id', 'UAH');
$currency->setAttribute('rate', '1');

$categories = $shop->appendChild($xml->createElement('categories'));


include_once __DIR__ . '/../../../../config.php';

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');


$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_eur = mysqli_fetch_assoc($query);
$api_exchange_rate_eur['buy'] = number_format($api_exchange_rate_eur['buy'], 2, '.', '');
$api_exchange_rate_eur['sale'] = number_format($api_exchange_rate_eur['sale'], 2, '.', '');

//$user_ids = '407,496,1799,1973,3171,4479,5856,6766';
$user_ids = '6766';
//$user_ids = '25,89';

$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `moderation`=1 AND `status`=1 GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
//$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$catalog_arr = array();
$category_linkname_children = array();

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru'], $catalog['img']), $catalog_arr);

	$category_linkname_children[] = strval('\''.$catalog['linkname'].'\'');

}

foreach ($catalog_arr as $catalog_arr_value) {

	$catalog_id = $catalog_arr_value['id'];
	$catalog_parent_id = $catalog_arr_value['parentId'];
	
	$catalog_linkname = $catalog_arr_value['data'][0];
	$catalog_name = str_replace("'", "\'", $catalog_arr_value['data'][1]);
	//$catalog_name = str_replace("сигареты", "pod-системы", $catalog_name);
	$catalog_img = $catalog_arr_value['data'][2];

	$top = 0;

	if ($catalog_parent_id == 1) {

		$category = $categories->appendChild($xml->createElement('category'));
		$category->setAttribute('id', $catalog_id);
		$category->setAttribute('img', 'https://onlinenaxodka.com/assets/images/catalog/'.$catalog_img);
		$category->appendChild($xml->createTextNode($catalog_name));

	} else {

		$category = $categories->appendChild($xml->createElement('category'));
		$category->setAttribute('id', $catalog_id);
		$category->setAttribute('parentId', $catalog_parent_id);
		$category->setAttribute('img', 'https://onlinenaxodka.com/assets/images/catalog/'.$catalog_img);
		$category->appendChild($xml->createTextNode($catalog_name));

	}
		
}

$offers = $shop->appendChild($xml->createElement('offers'));

$n = 0;

if (count($category_linkname_children) > 0) {

	$category_linkname_separated = implode(",", $category_linkname_children);

	//$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `moderation`=1 AND `status`=1";
	//$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%'";
	$sql_goods = "
	SELECT g.*, c.id AS catalog_id, gd.description, gduk.description AS description_uk FROM goods g 
	LEFT JOIN catalog c ON c.linkname=g.category 
	LEFT JOIN goods_description gd ON (gd.goods_id=g.id AND gd.lang='ru') 
	LEFT JOIN goods_description gduk ON (gduk.goods_id=g.id AND gduk.lang='uk') 
	WHERE g.user_id IN ({$user_ids}) 
		AND g.category IN ({$category_linkname_separated}) 
		AND g.photo NOT LIKE '%{\"img0\":\"no_image.png\"}%' 
		AND g.availability > 0 
		AND g.moderation = 1 
		AND g.status = 1";
//echo $sql_goods."\n";
	$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

	if (mysqli_num_rows($query_goods) > 0) {

		while ($goods = mysqli_fetch_assoc($query_goods)) {

			$goods_id = $goods['id'];

			if ($goods['availability'] > 0) {

				$available = 'true';

			} else {

				$available = 'false';

			}

			$offer = $offers->appendChild($xml->createElement('offer'));
			$offer->setAttribute('id', $goods_id);
			$offer->setAttribute('available', $available);

			$goods_vendor_code = $goods['vendor_code'];
			if ($goods_vendor_code == '-') $goods_vendor_code = ''; 

			$goods['name'] = json_decode($goods['name'], true);
			$goods_name = str_replace("'", "\'", $goods['name']['ru']);
			//$goods_name = str_replace("сигарета", "pod-система", $goods_name);
			//$goods_name = str_replace(" затяжек", "", $goods_name);

			$name = $offer->appendChild($xml->createElement('name'));
			$name->appendChild($xml->createTextNode($goods_name));

			$goods_name_uk = str_replace("'", "\'", $goods['name']['uk']);

			if (!empty($goods_name_uk)) {
				$name_uk = $offer->appendChild($xml->createElement('name_ua'));
				$name_uk->appendChild($xml->createTextNode($goods_name_uk));
			}

			/*$sql_catalog = "SELECT `id` FROM `catalog` WHERE `linkname`='{$goods['category']}'";
			$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
			$goods_catalog = mysqli_fetch_assoc($query_catalog);

			$goods_catalog_id = $goods_catalog['id'];*/
			$goods_catalog_id = $goods['catalog_id'];

			$categoryId = $offer->appendChild($xml->createElement('categoryId'));
			$categoryId->appendChild($xml->createTextNode($goods_catalog_id));

			if ($goods['currency'] == 1) {

				$kurs_currency = 1;

			} elseif ($goods['currency'] == 2) {

				$kurs_currency = $api_exchange_rate_usd['sale'];

				if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

					$kurs_currency = $goods['currency_top_kurs'];

				}

			} elseif ($goods['currency'] == 3) {

				$kurs_currency = $api_exchange_rate_eur['sale'];

				if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

					$kurs_currency = $goods['currency_top_kurs'];

				}

			}

			$price_sale = ceil($goods['price_sale'] * $kurs_currency);
			if ($goods_id != 356039) $price_sale = ceil($goods['price_sale'] * $kurs_currency) - 10;
			$price_sale = number_format($price_sale, 2, '.', '');

			$price = $offer->appendChild($xml->createElement('price'));
			$price->appendChild($xml->createTextNode($price_sale));

			$currencyId = $offer->appendChild($xml->createElement('currencyId'));
			$currencyId->appendChild($xml->createTextNode('UAH'));

			$goods['photo'] = json_decode($goods['photo'], true);

			$goods_photo_total = count($goods['photo']);
			if ($goods_photo_total > 10) $goods_photo_total = 10;

			for ($i=0; $i < $goods_photo_total; $i++) {

				if (file_exists(__DIR__.'/../../../images/goods/'.$goods['photo']['img'.$i])) {

					$picture = $offer->appendChild($xml->createElement('picture'));
					$picture->appendChild($xml->createTextNode($domen_name.'/assets/images/goods/'.$goods['photo']['img'.$i]));

				}

			}

			$goods['parameters'] = json_decode($goods['parameters'], true);

			$goods_vendor = $goods['parameters']['ru']['Производитель'];

			if (!empty($goods_vendor)) {

				$vendor = $offer->appendChild($xml->createElement('vendor'));
				$vendor->appendChild($xml->createTextNode($goods_vendor));

			}

			if (!empty($goods['vendor_code']) and $goods['vendor_code'] != '-') {

				$vendorCode = $offer->appendChild($xml->createElement('vendorCode'));
				$vendorCode->appendChild($xml->createTextNode($goods['vendor_code']));

			}

			/*$sql_goods_description = "SELECT `description` FROM `goods_description` WHERE `goods_id`='{$goods['id']}' AND `lang`='ru'";
			$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
			$goods_description = mysqli_fetch_assoc($query_goods_description);

			$str = $goods_description['description'];*/
			$str = $goods['description'];

			$str_new = '';

			$arr = str_split($str);

			$start = false;

			for ($i=0; $i < count($arr); $i++) { 
				
				if ($arr[$i] == '<' and $arr[$i+1] == 'a') $start = true;
				if ($arr[$i-2] == 'a' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == '<' and $arr[$i+1] == 'i' and $arr[$i+2] == 'f' and $arr[$i+3] == 'r') $start = true;
				if ($arr[$i-5] == 'r' and $arr[$i-4] == 'a' and $arr[$i-3] == 'm' and $arr[$i-2] == 'e' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == 'h' and $arr[$i+1] == 't' and $arr[$i+2] == 't' and $arr[$i+3] == 'p') $start = true;
				if ($arr[$i] == ' ') $start = false;

				if (!$start) {

					$str_new .= $arr[$i];

				}

			}

			$goods['description'] = $str_new;
			$goods_description = strReplaceCustom($goods['description']);
			/*$goods_description = str_replace("сигарета", "pod-система", $goods_description);
			$goods_description = str_replace("сигарету", "pod-систему", $goods_description);
			$goods_description = str_replace("сигареты", "pod-системы", $goods_description);
			$goods_description = str_replace("сигарети", "pod-системи", $goods_description);
			$goods_description = str_replace(", что можно приравнять к выкуриванию 4-5 пачек обычных сигарет", "", $goods_description);*/

			$description = $offer->appendChild($xml->createElement('description'));
			//$description->appendChild($xml->createTextNode('<![CDATA['.$goods_description_view['ru'].']]>'));
			//$description->appendChild($xml->createTextNode($goods_description));
			$description->appendChild($xml->createCDATASection($goods_description));

			$str = $goods['description_uk'];

			$str_new = '';

			$arr = str_split($str);

			$start = false;

			for ($i=0; $i < count($arr); $i++) { 
				
				if ($arr[$i] == '<' and $arr[$i+1] == 'a') $start = true;
				if ($arr[$i-2] == 'a' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == '<' and $arr[$i+1] == 'i' and $arr[$i+2] == 'f' and $arr[$i+3] == 'r') $start = true;
				if ($arr[$i-5] == 'r' and $arr[$i-4] == 'a' and $arr[$i-3] == 'm' and $arr[$i-2] == 'e' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == 'h' and $arr[$i+1] == 't' and $arr[$i+2] == 't' and $arr[$i+3] == 'p') $start = true;
				if ($arr[$i] == ' ') $start = false;

				if (!$start) {

					$str_new .= $arr[$i];

				}

			}

			$goods['description_uk'] = $str_new;
			$goods_description_uk = strReplaceCustom($goods['description_uk']);

			if (!empty($goods_description_uk)) {
				$description_uk = $offer->appendChild($xml->createElement('description_ua'));
				//$description->appendChild($xml->createTextNode($goods_description_uk));
				$description_uk->appendChild($xml->createCDATASection($goods_description_uk));
			}

			/*$goods['keys'] = str_replace("сигарета", "pod-система", $goods['keys']);
			$goods['keys'] = str_replace("сигареты", "pod-системы", $goods['keys']);
			$goods['keys'] = str_replace("сигарети", "pod-системи", $goods['keys']);*/

			$goods['keys'] = json_decode($goods['keys'], true);

			$keywords = $offer->appendChild($xml->createElement('keywords'));
			$keywords->appendChild($xml->createTextNode($goods['keys']['ru']));

			//$goods_weight = $goods['parameters']['ru']['Вес (кг)'];
			$goods_weight = $goods['parameters']['ru']['Тип'] == 'зерновой сухой маточный' ? '0.25' : '0.3';
			$weight = $offer->appendChild($xml->createElement('weight'));
			$weight->appendChild($xml->createTextNode($goods_weight));

			//$goods_length = $goods['parameters']['ru']['Глубина (см)'];
			$goods_length = $goods['parameters']['ru']['Тип'] == 'зерновой сухой маточный' ? '4' : '5';
			$length = $offer->appendChild($xml->createElement('length'));
			$length->appendChild($xml->createTextNode($goods_length));

			//$goods_width = $goods['parameters']['ru']['Ширина (см)'];
			$goods_width = $goods['parameters']['ru']['Тип'] == 'зерновой сухой маточный' ? '14' : '15';
			$width = $offer->appendChild($xml->createElement('width'));
			$width->appendChild($xml->createTextNode($goods_width));

			//$goods_height = $goods['parameters']['ru']['Высота (см)'];
			$goods_height = $goods['parameters']['ru']['Тип'] == 'зерновой сухой маточный' ? '18' : '20';
			$height = $offer->appendChild($xml->createElement('height'));
			$height->appendChild($xml->createTextNode($goods_height));
			

			$parameters_names_no = array('Производитель', 'Вес (кг)', 'Глубина (см)', 'Ширина (см)', 'Высота (см)', 'Артикул', 'артикул', 'Бренд', '-', ' ', '');
			$parameters_values_no = array('-', ' ', '');

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if (!in_array($parameters_key, $parameters_names_no) and !in_array($parameters_value, $parameters_values_no)) {

					$parameters_key = str_replace("Никотин", "Крепость", $parameters_key);

					$param = $offer->appendChild($xml->createElement('param'));
					$param->appendChild($xml->createTextNode($parameters_value));
					$param->setAttribute('name', $parameters_key);

				}

			}

			$stock_quantity = $offer->appendChild($xml->createElement('stock_quantity'));
			$stock_quantity->appendChild($xml->createTextNode($goods['availability']));

			$n++;

			//echo $n." - ".$goods_id."\n";

			//if ($n == 10) break;

		}

	}

}

function categories($db, $id, $parent_id, $data, $catalog_arr) {

	$ca_status = 0;

	foreach ($catalog_arr as $catalog_arr_value) {
		
		if ($catalog_arr_value['id'] == $id) {

			$ca_status++;

		}
		
	}

	if ($ca_status == 0) {

		if ($parent_id > 1) {

			$catalog_arr[] = array(
				'id' => $id,
				'parentId' => $parent_id,
				'data' => $data
			);

			$sql = "SELECT * FROM `catalog` WHERE `id`='{$parent_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$catalog = mysqli_fetch_assoc($query);

			$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru'], $catalog['img']), $catalog_arr);

		} else {

			if ($parent_id > 0) {

				$catalog_arr[] = array(
					'id' => $id,
					'parentId' => $parent_id,
					'data' => $data
				);

			}

		}

	}

	return $catalog_arr;

}

mysqli_close($db);

$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true

if ($xml->save(__DIR__.'/yml_sambud.xml')) {

	echo "Оновлення фіду yml_sambud.xml успішно завершилося!\n";

} else {

	echo "Неможливо зберегти файл yml_sambud.xml фіда даних. Можливо у файлу недостатньо прав доступу.\n";

}

//$time = microtime(true) - $start;
$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";

function strReplaceCustom($str = '') {

	$arraySymbols = array(
		array('"', '&quot;'),
		array('&', '&amp;'),
		array('>', '&gt;'),
		array('<', '&lt;'),
		array("'", '&apos;')
	);

	foreach ($arraySymbols as $symbol) {
		$str = str_replace($symbol[0], $symbol[1], $str);
	}

	return $str;

}