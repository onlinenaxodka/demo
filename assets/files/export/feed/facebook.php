<?php

$start_time = microtime(true);

$domen_name = 'https://onlinenaxodka.com';

//$user_ids = '407,1799,4475,5856,6766,7158,7625';
$user_ids = '1799';

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

//XML create
$xml = new DomDocument('1.0', 'utf-8');

$rss = $xml->appendChild($xml->createElement('rss'));
$rss->setAttribute('version', '2.0');
$rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

	$items = $rss->appendChild($xml->createElement('channel'));

		$main_title = $items->appendChild($xml->createElement('title'));
		$main_title->appendChild($xml->createTextNode('ONLINE NAXODKA'));

		$main_link = $items->appendChild($xml->createElement('link'));
		$main_link->appendChild($xml->createTextNode($domen_name));

		$main_desc = $items->appendChild($xml->createElement('description'));
		$main_desc->appendChild($xml->createTextNode('Продажа товаров в Украине'));

echo "\nStart script - ".number_format((microtime(true) - $start_time), 2, '.', '')." sec.\n";
$start_time = microtime(true);

$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1 GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

//$catalog_arr = array();
$category_linkname_children = array();

while ($catalog = mysqli_fetch_assoc($query)) {

	//$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru']), $catalog_arr);

	$category_linkname_children[] = strval('\''.$catalog['linkname'].'\'');

}

/*foreach ($catalog_arr as $catalog_arr_value) {

	$catalog_id = $catalog_arr_value['id'];
	$catalog_parent_id = $catalog_arr_value['parentId'];
	
	$catalog_linkname = $catalog_arr_value['data'][0];
	$catalog_name = str_replace("'", "\'", $catalog_arr_value['data'][1]);

	$top = 0;

	if ($catalog_parent_id == 1) {

		$category = $categories->appendChild($xml->createElement('category'));
		$category->setAttribute('id', $catalog_id);
		$category->appendChild($xml->createTextNode($catalog_name));

	} else {

		$category = $categories->appendChild($xml->createElement('category'));
		$category->setAttribute('id', $catalog_id);
		$category->setAttribute('parentId', $catalog_parent_id);
		$category->appendChild($xml->createTextNode($catalog_name));

	}
		
}*/

echo "Create Categories - ".number_format((microtime(true) - $start_time), 2, '.', '')." sec.\n";
$start_time = microtime(true);

$n = 0;

if (count($category_linkname_children) > 0) {

	$category_linkname_separated = implode(",", $category_linkname_children);

	$sql_goods = "SELECT g.id, g.availability, g.vendor_code, g.name, g.category, c.id AS catalog_id, c.rate AS catalog_rate, g.currency, g.price_purchase, g.price_sale, g.photo, g.parameters, gd.description, g.keys FROM goods g LEFT JOIN catalog c ON c.linkname=g.category LEFT JOIN goods_description gd ON gd.goods_id=g.id WHERE g.user_id IN ({$user_ids}) AND g.category IN ({$category_linkname_separated}) AND g.photo NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND g.availability>0 AND g.status=1 AND gd.lang='ru'";
	$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

	if (mysqli_num_rows($query_goods) > 0) {

		while ($goods = mysqli_fetch_assoc($query_goods)) {

			if ($goods['availability'] > 0) $product_availability = 'in stock';
			else $product_availability = 'out of stock';

			//if ($product_availability == 'in stock') {

			$product_id = $goods['id'];

			$goods['name'] = json_decode($goods['name'], true);
			$product_name = $goods['name']['ru'];

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

			$product_description = strip_tags($str_new);

			$parameters_names_no = array('Производитель', 'Вес (кг)', 'Глубина (см)', 'Ширина (см)', 'Высота (см)', 'Артикул', 'артикул', 'Бренд', '-', ' ', '');
			$parameters_values_no = array('-', ' ', '');

			$product_description .= ' Характеристики: ';

			$goods['parameters'] = json_decode($goods['parameters'], true);

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if (!in_array($parameters_key, $parameters_names_no) and !in_array($parameters_value, $parameters_values_no)) {

					$product_description .= $parameters_key.' - '.$parameters_value.';';

				}

			}

			$product_condition = 'new';

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

			$price_purchase = ceil($goods['price_purchase'] * $kurs_currency);
			$price_sale = ceil($goods['price_sale'] * $kurs_currency);
			$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $goods['catalog_rate']));
			//$price_min = number_format($price_min, 2, '.', '');

			$product_price = $price_min . ' UAH';

			$product_link = $domen_name.'/account/goods/'.$goods['category'].'/'.$product_id;

			$goods['photo'] = json_decode($goods['photo'], true);

			for ($i=0; $i < count($goods['photo']); $i++) {

				if (file_exists(__DIR__.'/../../../images/goods/'.$goods['photo']['img'.$i])) {

					$product_image_link = $domen_name.'/assets/images/goods/'.$goods['photo']['img'.$i];

					break;

				}

			}

			$goods_vendor = $goods['parameters']['ru']['Производитель'];
			if (!empty($goods_vendor)) $product_brand = $goods_vendor;
			else $product_brand = 'ONLINE NAXODKA';

			$fb_product_category_arr = fb_product_category($db, $goods['catalog_id']);
			//$product_type_arr[] = 'Главная';
			$fb_product_category_arr = array_reverse($fb_product_category_arr);
			$product_fb_product_category = implode(" > ", $fb_product_category_arr);

			$item = $items->appendChild($xml->createElement('item'));

			$id = $item->appendChild($xml->createElement('g:id'));
			$id->appendChild($xml->createTextNode($product_id));

			$title = $item->appendChild($xml->createElement('g:title'));
			$title->appendChild($xml->createTextNode($product_name));

			$description = $item->appendChild($xml->createElement('g:description'));
			$description->appendChild($xml->createTextNode($product_description));

			$availability = $item->appendChild($xml->createElement('g:availability'));
			$availability->appendChild($xml->createTextNode($product_availability));

			$condition = $item->appendChild($xml->createElement('g:condition'));
			$condition->appendChild($xml->createTextNode($product_condition));

			$price = $item->appendChild($xml->createElement('g:price'));
			$price->appendChild($xml->createTextNode($product_price));

			$link = $item->appendChild($xml->createElement('g:link'));
			$link->appendChild($xml->createTextNode($product_link));

			$image_link = $item->appendChild($xml->createElement('g:image_link'));
			$image_link->appendChild($xml->createTextNode($product_image_link));

			$brand = $item->appendChild($xml->createElement('g:brand'));
			$brand->appendChild($xml->createTextNode($product_brand));

			$fb_product_category = $item->appendChild($xml->createElement('g:fb_product_category'));
			$fb_product_category->appendChild($xml->createTextNode($product_fb_product_category));

			$n++;

			//echo $n." - ".$goods_id."\n";

			//if ($n == 10) break;

			//}

		}

	}

}

echo "Create items ".$n." - ".number_format((microtime(true) - $start_time), 2, '.', '')." sec.\n";

/*function categories($db, $id, $parent_id, $data, $catalog_arr) {

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

			$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru']), $catalog_arr);

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

}*/

function fb_product_category($db, $category_id, $product_type_arr = array()) {

	$sql = "SELECT * FROM `catalog` WHERE `id`={$category_id}";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$category = mysqli_fetch_assoc($query);

	$product_type_arr[] = $category['name_ru'];

	if ($category['level_id'] > 1) {

		$product_type_arr = fb_product_category($db, $category['level_id'], $product_type_arr);

	}

	return $product_type_arr;

}

mysqli_close($db);

$xml->formatOutput = true;

if ($xml->save(__DIR__.'/facebook.xml')) {

	echo "\nОбновление фида facebook.xml завершилось успешно!\n";

} else {

	echo "\nНе удалось сохранить файл facebook.xml фида данных. Возможно у файла не достаточно прав доступа.\n";

}

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 2, '.', '');

file_put_contents(__DIR__ . "/../logs/update_facebook.log", date('Y-m-d H:i:s')." - Finish update Facebook feed XML. Execution time: ".$time." seconds.\n", FILE_APPEND | LOCK_EX);

echo "\nAll time ".$time." sec. of job script\n\n";