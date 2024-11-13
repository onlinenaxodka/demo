<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$start = microtime(true);

file_put_contents(__DIR__."/logs/yml_prom.log", date('Y-m-d H:i:s')." - Start create XML docs.\n", FILE_APPEND | LOCK_EX);

//Main config

$domen_name = 'https://onlinenaxodka.com';

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

//Main config

$sql = "SELECT COUNT(1) AS count_up FROM `catalog` WHERE `id` > 1 AND `yml_prom`=0 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$catalog_count_up = mysqli_fetch_assoc($query);

if ($catalog_count_up['count_up'] == 0) {

	$sql = "UPDATE `catalog` SET `yml_prom`=0, `updated`='{$current_date}' WHERE `yml_prom`=1";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

//$sql = "SELECT `id`, `linkname`, `name_ru` FROM `catalog` WHERE `id` > 1 AND `yml_prom`=0 AND `count_goods`<=100 LIMIT 100";
//$sql = "SELECT `id`, `linkname`, `name_ru` FROM `catalog` WHERE `id` > 1 AND `yml_prom`=0 AND `buffer`=0";
//Указати ID категорії
$sql = "SELECT `id`, `linkname`, `name_ru` FROM `catalog` WHERE `id` = 3209 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$n=0;
$m=0;

while ($catalog = mysqli_fetch_assoc($query)) {

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
$currency->setAttribute('id', 'UAH'); //атрибуты
$currency->setAttribute('rate', '1'); //атрибуты

$categories = $shop->appendChild($xml->createElement('categories'));

$selected_category_id = intval($catalog['id']);
$linkname = strval($catalog['linkname']);

$category = $categories->appendChild($xml->createElement('category'));
$category->setAttribute('id', $selected_category_id); //атрибуты
$category->appendChild($xml->createTextNode($catalog['name_ru']));

$category_linkname = categoriesLinkname($db, $selected_category_id, array());

for ($i=0; $i < count($category_linkname['category']['id']); $i++) {

	$category = $categories->appendChild($xml->createElement('category'));
	$category->setAttribute('id', $category_linkname['category']['id'][$i]); //атрибуты
	$category->setAttribute('parentId', $category_linkname['category']['parentId'][$i]); //атрибуты
	$category->appendChild($xml->createTextNode($category_linkname['category']['name'][$i]));

}


$offers = $shop->appendChild($xml->createElement('offers'));

//if (count($category_linkname['category_linkname']) == 0) $category_linkname['category_linkname'][] = '\''.$linkname.'\'';
if (count($category_linkname['category_linkname']) == 0) $category_linkname['category_linkname'][] = $linkname;

if (count($category_linkname['category_linkname']) > 0) {

//$category_linkname_separated = implode(",", $category_linkname['category_linkname']);
//echo $category_linkname_separated.'<br><br><br>';

//$sql_goods = "SELECT * FROM `goods` WHERE `category` IN ({$category_linkname_separated}) AND `status`=1";
//$sql_goods = "SELECT *, (SELECT `id` FROM `catalog` WHERE `linkname`=`goods`.`category`) AS catalog_id, (SELECT `description` FROM `goods_description` WHERE `goods_id`=`goods`.`id` AND `lang`='ru') AS description FROM `goods` WHERE `category` IN ({$category_linkname_separated}) AND `status`=1 AND `availability` > 0";
/*$sql_goods = "SELECT *, (SELECT `id` FROM `catalog` WHERE `linkname`=`goods`.`category`) AS catalog_id, (SELECT `description` FROM `goods_description` WHERE `goods_id`=`goods`.`id` AND `lang`='ru') AS description FROM `goods` WHERE `status`=1 AND `availability` > 0";*/

$sql_goods = "SELECT * FROM `goods` WHERE `status`=1 AND `availability` > 0";
$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

if (mysqli_num_rows($query_goods) > 0) {

	while ($goods = mysqli_fetch_assoc($query_goods)) {

		if (in_array($goods['category'], $category_linkname['category_linkname'])) {

		$sql_catalog = "SELECT `id` FROM `catalog` WHERE `linkname`='{$goods['category']}'";
		$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
		$goods_catalog = mysqli_fetch_assoc($query_catalog);

		$goods['name'] = json_decode($goods['name'], true);

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

		$currency_id = 'UAH';

		$price_sale = ceil($goods['price_sale'] * $kurs_currency);
		$price_sale = number_format($price_sale, 2, '.', '');

		$goods['photo'] = json_decode($goods['photo'], true);

		$goods['parameters'] = json_decode($goods['parameters'], true);

		$sql_goods_description = "SELECT * FROM `goods_description` WHERE `goods_id`='{$goods['id']}' AND `lang`='ru'";
		$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
		$goods_description = mysqli_fetch_assoc($query_goods_description);

		$str = $goods_description['description'];
		//$str = $goods['description'];

		$str_new = '';

		$arr = str_split($str);

		$start = false;

		for ($i=0; $i < count($arr); $i++) { 
			
			if ($arr[$i] == '<' and $arr[$i+1] == 'a') $start = true;
			if ($arr[$i-2] == 'a' and $arr[$i-1] == '>') $start = false;
			if ($arr[$i] == '<' and $arr[$i+1] == 'i' and $arr[$i+2] == 'f' and $arr[$i+3] == 'r') $start = true;
			if ($arr[$i-5] == 'r' and $arr[$i-4] == 'a' and $arr[$i-3] == 'm' and $arr[$i-2] == 'e' and $arr[$i-1] == '>') $start = false;

			if (!$start) {

				$str_new .= $arr[$i];

			}

		}

		$goods_description['description'] = $str_new;
		//$goods['description'] = $str_new;


		$offer = $offers->appendChild($xml->createElement('offer'));
		$offer->setAttribute('id', $goods['id']); //атрибуты
		$offer->setAttribute('available', 'true'); //атрибуты
		
			$name = $offer->appendChild($xml->createElement('name'));
			$name->appendChild($xml->createTextNode($goods['name']['ru']));

			$categoryId = $offer->appendChild($xml->createElement('categoryId'));
			$categoryId->appendChild($xml->createTextNode($goods_catalog['id']));

			$price = $offer->appendChild($xml->createElement('price'));
			$price->appendChild($xml->createTextNode($price_sale));

			$currencyId = $offer->appendChild($xml->createElement('currencyId'));
			$currencyId->appendChild($xml->createTextNode($currency_id));

			$photo_count = 0;

			$goods_photo_count = count($goods['photo']);
			if ($goods_photo_count > 10) $goods_photo_count = 10;

			for ($i=0; $i < $goods_photo_count; $i++) {

				if (file_exists(__DIR__.'/../../images/goods/'.$goods['photo']['img'.$i])) {

					$picture = $offer->appendChild($xml->createElement('picture'));
					$picture->appendChild($xml->createTextNode($domen_name.'/assets/images/goods/'.$goods['photo']['img'.$i]));

					$photo_count++;

				}

			}

			if ($photo_count == 0) {

				$picture = $offer->appendChild($xml->createElement('picture'));
				$picture->appendChild($xml->createTextNode($domen_name.'/assets/images/goods/no_image.png'));

			}

			if (!empty($goods['vendor_code']) and $goods['vendor_code'] != '-') {

				$vendorCode = $offer->appendChild($xml->createElement('vendorCode'));
				$vendorCode->appendChild($xml->createTextNode($goods['vendor_code']));

			} else {

				$vendorCode = $offer->appendChild($xml->createElement('vendorCode'));

			}

			$description = $offer->appendChild($xml->createElement('description'));
			//$description->appendChild($xml->createTextNode('<![CDATA['.$goods_description_view['ru'].']]>'));
			$description->appendChild($xml->createTextNode($goods_description['description']));

			$goods['keys'] = json_decode($goods['keys'], true);

			$keywords = $offer->appendChild($xml->createElement('keywords'));
			$keywords->appendChild($xml->createTextNode($goods['keys']['ru']));

			$vendor_value = '';

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if ($parameters_key == 'Производитель') {

					$vendor_value = $parameters_value;

				}

			}

			$vendor = $offer->appendChild($xml->createElement('vendor'));
			$vendor->appendChild($xml->createTextNode($vendor_value));

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if ($parameters_key != 'Производитель') {

					$param = $offer->appendChild($xml->createElement('param'));
					$param->appendChild($xml->createTextNode($parameters_value));
					$param->setAttribute('name', $parameters_key);

				}

			}

			$stock_quantity = $offer->appendChild($xml->createElement('stock_quantity'));
			$stock_quantity->appendChild($xml->createTextNode($goods['availability']));

		} else {

			continue;

		}

	}

}

} else {
	$m++;
	var_dump($category_linkname);
	echo $selected_category_id.'<br>';
}

$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true

if ($xml->save(__DIR__.'/yml_prom/'.$linkname.'.xml')) {

	//echo 'Обновление фида '.$linkname.' завершилось успешно!';

	$sql_up = "UPDATE `catalog` SET `yml_prom`=1, `updated`='{$current_date}' WHERE `id`='{$selected_category_id}'";
	$query_up = mysqli_query($db, $sql_up) or die(mysqli_error($db));

} else {

	echo 'Не удалось сохранить файл '.$linkname.' фида данных. Возможно у файла не достаточно прав доступа.';

	file_put_contents(__DIR__."/errors/yml_prom.log", date('Y-m-d H:i:s')." - Не удалось сохранить файл ".$linkname.".xml фида данных. Возможно у файла не достаточно прав доступа.\n", FILE_APPEND | LOCK_EX);

}

//$xml->save('yml_prom/'.$linkname.'.xml'); #-> сохраняем файл
$n++;
//echo $catalog['name_ru'].'<br>';

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 0, '.', '');

echo $n." - ".$time." sec.\n";

}

file_put_contents(__DIR__."/logs/yml_prom.log", date('Y-m-d H:i:s')." - Finish create XML docs.\n", FILE_APPEND | LOCK_EX);

echo "Iters all: ".$n."\n";
echo "Iters from category: ".$m."\n";

function categoriesLinkname($db, $category_id, $catalog_arr) {

	$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$category_id}' AND `locked`=0";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$count_subcategories = mysqli_num_rows($query);

	if ($count_subcategories > 0) {

		while ($catalog = mysqli_fetch_assoc($query)) {
		 	
			$catalog_arr['category']['id'][] = $catalog['id'];
			$catalog_arr['category']['parentId'][] = $catalog['level_id'];
			$catalog_arr['category']['name'][] = $catalog['name_ru'];

			$catalog_arr = categoriesLinkname($db, $catalog['id'], $catalog_arr);

		}

	} else {

		$sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}' AND `locked`=0";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) > 0) {

			$category = mysqli_fetch_assoc($query);

			$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}' AND `status`=1 AND `availability` > 0";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$count_goods_in_category = mysqli_num_rows($query);

			if ($count_goods_in_category > 0) {

				//$catalog_arr['category_linkname'][] = '\''.$category['linkname'].'\'';
				$catalog_arr['category_linkname'][] = $category['linkname'];

			}

		}

	}

	return $catalog_arr;

}

mysqli_close($db);

?>