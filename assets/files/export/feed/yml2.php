<?php

//$start = microtime(true);

$domen_name = 'https://onlinenaxodka.com';

//XML create

$xml = new DomDocument('1.0', 'utf-8'); //создаем новый экземпляр

$yml_catalog = $xml->appendChild($xml->createElement('yml_catalog')); // добавляем тег

$yml_catalog->setAttribute('date', date("Y-m-d H:i"));//атрибуты

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
$user_ids = '1799';
//$user_ids = '25';

$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1 GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
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
echo $category_linkname_separated."\n\n";
	//$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1";
	//$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%'";
	$sql_goods = "SELECT g.id, g.availability, g.vendor_code, g.name, g.category, c.id AS catalog_id, c.rate AS catalog_rate, g.currency, g.price_purchase, g.price_sale, g.photo, g.parameters, gd.description, g.keys FROM goods g LEFT JOIN catalog c ON c.linkname=g.category LEFT JOIN goods_description gd ON gd.goods_id=g.id WHERE g.user_id IN ({$user_ids}) AND g.category IN ({$category_linkname_separated}) AND g.photo NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND g.availability>0 AND g.status=1 AND gd.lang='ru'";
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

			$name = $offer->appendChild($xml->createElement('name'));
			$name->appendChild($xml->createTextNode($goods_name));

			/*$sql_catalog = "SELECT `id` FROM `catalog` WHERE `linkname`='{$goods['category']}'";
			$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
			$goods_catalog = mysqli_fetch_assoc($query_catalog);*/

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
			$price_sale = number_format($price_sale, 2, '.', '');

			$price = $offer->appendChild($xml->createElement('price'));
			$price->appendChild($xml->createTextNode($price_sale));

			$currencyId = $offer->appendChild($xml->createElement('currencyId'));
			$currencyId->appendChild($xml->createTextNode('UAH'));

			$goods['photo'] = json_decode($goods['photo'], true);

			for ($i=0; $i < count($goods['photo']); $i++) {

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
			$goods_description = mysqli_fetch_assoc($query_goods_description);*/

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
			$goods_description = str_replace("'", "\'", $goods['description']);

			$description = $offer->appendChild($xml->createElement('description'));
			//$description->appendChild($xml->createTextNode('<![CDATA['.$goods_description_view['ru'].']]>'));
			$description->appendChild($xml->createTextNode($goods_description));

			$goods['keys'] = json_decode($goods['keys'], true);

			$keywords = $offer->appendChild($xml->createElement('keywords'));
			$keywords->appendChild($xml->createTextNode($goods['keys']['ru']));

			$goods_weight = $goods['parameters']['ru']['Вес (кг)'];
			$weight = $offer->appendChild($xml->createElement('weight'));
			$weight->appendChild($xml->createTextNode($goods_weight));

			$goods_length = $goods['parameters']['ru']['Глубина (см)'];
			$length = $offer->appendChild($xml->createElement('length'));
			$length->appendChild($xml->createTextNode($goods_length));

			$goods_width = $goods['parameters']['ru']['Ширина (см)'];
			$width = $offer->appendChild($xml->createElement('width'));
			$width->appendChild($xml->createTextNode($goods_width));

			$goods_height = $goods['parameters']['ru']['Высота (см)'];
			$height = $offer->appendChild($xml->createElement('height'));
			$height->appendChild($xml->createTextNode($goods_height));
			

			$parameters_names_no = array('Производитель', 'Вес (кг)', 'Глубина (см)', 'Ширина (см)', 'Высота (см)', 'Артикул', 'артикул', 'Бренд', '-', ' ', '');
			$parameters_values_no = array('-', ' ', '');

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if (!in_array($parameters_key, $parameters_names_no) and !in_array($parameters_value, $parameters_values_no)) {

					$param = $offer->appendChild($xml->createElement('param'));
					$param->appendChild($xml->createTextNode($parameters_value));
					$param->setAttribute('name', $parameters_key);

				}

			}

			$stock_quantity = $offer->appendChild($xml->createElement('stock_quantity'));
			$stock_quantity->appendChild($xml->createTextNode($goods['availability']));

			$n++;

			echo $n." - ".$goods_id."\n";

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

if ($xml->save(__DIR__.'/yml2.xml')) {

	echo "Обновление фида yml.xml завершилось успешно!\n";

} else {

	echo "Не удалось сохранить файл yml.xml фида данных. Возможно у файла не достаточно прав доступа.\n";

}

//$time = microtime(true) - $start;
$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";