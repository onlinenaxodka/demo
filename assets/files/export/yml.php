<?php

header('Content-Type: text/xml; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

//Main config

$server_protocole = 'http';
if (isset($_SERVER['HTTPS'])) $server_protocole = 'https';
else $server_protocole = 'http';

$domen_name = $server_protocole."://".$_SERVER['SERVER_NAME'];

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

$selected_category_id = 2693;
$sql = "SELECT `name_ru` FROM `catalog` WHERE `id`='{$selected_category_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$catalog = mysqli_fetch_assoc($query);

$category = $categories->appendChild($xml->createElement('category'));
$category->setAttribute('id', $selected_category_id); //атрибуты
$category->appendChild($xml->createTextNode($catalog['name_ru']));

/*$category_linkname = array();

$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$selected_category_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($catalog = mysqli_fetch_assoc($query)) {
 	
	$category = $categories->appendChild($xml->createElement('category'));
	$category->setAttribute('id', $catalog['id']); //атрибуты
	$category->setAttribute('parentId', '1441'); //атрибуты
	$category->appendChild($xml->createTextNode($catalog['name_ru']));

	$sql_in = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}'";
	$query_in = mysqli_query($db, $sql_in) or die(mysqli_error($db));
	while ($catalog_in = mysqli_fetch_assoc($query_in)) {
	 	
		$category = $categories->appendChild($xml->createElement('category'));
		$category->setAttribute('id', $catalog_in['id']); //атрибуты
		$category->setAttribute('parentId', $catalog['id']); //атрибуты
		$category->appendChild($xml->createTextNode($catalog_in['name_ru']));

		$category_linkname[] = '\''.$catalog_in['linkname'].'\'';

	}

}

$category_linkname_separated = implode(",", $category_linkname);*/

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

			$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$count_goods_in_category = mysqli_num_rows($query);

			if ($count_goods_in_category > 0) {

				$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}' AND `status`=1";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if (mysqli_num_rows($query) > 0) {

					$catalog_arr['category_linkname'][] = '\''.$category['linkname'].'\'';

				}

			}

		}

	}

	return $catalog_arr;

}

$category_linkname = categoriesLinkname($db, $selected_category_id, array());

for ($i=0; $i < count($category_linkname['category']['id']); $i++) { 
	
	$category = $categories->appendChild($xml->createElement('category'));
	$category->setAttribute('id', $category_linkname['category']['id'][$i]); //атрибуты
	$category->setAttribute('parentId', $category_linkname['category']['parentId'][$i]); //атрибуты
	$category->appendChild($xml->createTextNode($category_linkname['category']['name'][$i]));

}

$category_linkname_separated = implode(",", $category_linkname['category_linkname']);

$offers = $shop->appendChild($xml->createElement('offers'));

$sql = "SELECT * FROM `goods` WHERE `category` IN ({$category_linkname_separated}) AND `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

	while ($goods = mysqli_fetch_assoc($query)) {

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

		$goods['photo'] = json_decode($goods['photo'], true);

		$goods['parameters'] = json_decode($goods['parameters'], true);

		$sql_goods_description = "SELECT * FROM `goods_description` WHERE `goods_id`='{$goods['id']}'";
		$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
		while ($goods_description = mysqli_fetch_assoc($query_goods_description))
			$goods_description_view[$goods_description['lang']] = $goods_description['description'];


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

				if (file_exists('../../images/goods/'.$goods['photo']['img'.$i])) {

					$picture = $offer->appendChild($xml->createElement('picture'));
					$picture->appendChild($xml->createTextNode($domen_name.'/assets/images/goods/'.$goods['photo']['img'.$i]));

					$photo_count++;

				}

			}

			if ($photo_count == 0) {

				$picture = $offer->appendChild($xml->createElement('picture'));
				$picture->appendChild($xml->createTextNode($domen_name.'/assets/images/goods/no_image.png'));

			}

			$vendorCode = $offer->appendChild($xml->createElement('vendorCode'));
			$vendorCode->appendChild($xml->createTextNode($goods['id']));

			$description = $offer->appendChild($xml->createElement('description'));
			$description->appendChild($xml->createTextNode('<![CDATA['.$goods_description_view['ru'].']]>'));

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				$param = $offer->appendChild($xml->createElement('param'));
				$param->appendChild($xml->createTextNode($parameters_value));
				$param->setAttribute('name', $parameters_key); //атрибуты

			}

	}

}

mysqli_close($db);

$xml->preserveWhiteSpace = false;

$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true

if ($xml->saveXML()) {

	$xml_output = $xml->saveXML();
	echo $xml_output;

} else {

	echo "<yml_catalog date=\"".date("Y-m-d H:i")."\">
			<shop>
				<name>ONLINE NAXODKA</name>
				<company>ONLINE NAXODKA</company>
				<url>".$domen_name."</url>
				<offers/>
			</shop>
		</yml_catalog>";

}

?>