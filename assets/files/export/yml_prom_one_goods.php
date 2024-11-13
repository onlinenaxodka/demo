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



$goods_id = (isset($_GET['goods_id'])) ? mysqli_real_escape_string($db, $_GET['goods_id']) : '';
$goods_id = test_request($goods_id);
$goods_id = intval($goods_id);

$sql = "SELECT * FROM `goods` WHERE `id`='{$goods_id}' AND `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$goods = mysqli_fetch_assoc($query);

if (mysqli_num_rows($query) > 0) {

	$currencies = $shop->appendChild($xml->createElement('currencies'));

	$currency = $currencies->appendChild($xml->createElement('currency'));
	$currency->setAttribute('id', 'UAH'); //атрибуты
	$currency->setAttribute('rate', '1'); //атрибуты

	$categories = $shop->appendChild($xml->createElement('categories'));

	$sql = "SELECT `id`, `name_ru` FROM `catalog` WHERE `linkname`='{$goods['category']}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$catalog = mysqli_fetch_assoc($query);

	$category = $categories->appendChild($xml->createElement('category'));
	$category->setAttribute('id', $catalog['id']); //атрибуты
	$category->appendChild($xml->createTextNode($catalog['name_ru']));

	$offers = $shop->appendChild($xml->createElement('offers'));

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

		$sql_goods_description = "SELECT * FROM `goods_description` WHERE `goods_id`='{$goods['id']}'";
		$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
		while ($goods_description = mysqli_fetch_assoc($query_goods_description))
			$goods_description_view[$goods_description['lang']] = $goods_description['description'];

		$str = $goods_description_view['ru'];

		$str_new = '';

		$arr = str_split($str);

		$start = false;

		for ($i=0; $i < count($arr); $i++) { 
			
			if ($arr[$i] == '<' and $arr[$i+1] == 'a') $start = true;
			if ($i > 1) {
				if ($arr[$i-2] == 'a' and $arr[$i-1] == '>') $start = false;
			}
			if ($arr[$i] == '<' and $arr[$i+1] == 'i' and $arr[$i+2] == 'f' and $arr[$i+3] == 'r') $start = true;
			if ($i > 4) {
				if ($arr[$i-5] == 'r' and $arr[$i-4] == 'a' and $arr[$i-3] == 'm' and $arr[$i-2] == 'e' and $arr[$i-1] == '>') $start = false;
			}
			if (!$start) {

				$str_new .= $arr[$i];

			}

		}

		$goods_description_view['ru'] = $str_new;


		$offer = $offers->appendChild($xml->createElement('offer'));
		$offer->setAttribute('id', $goods['id']); //атрибуты
		$offer->setAttribute('available', 'true'); //атрибуты
		
			$name = $offer->appendChild($xml->createElement('name'));
			$name->appendChild($xml->createTextNode($goods['name']['ru']));

			if (!empty($goods['name']['uk'])) {
				$name_uk = $offer->appendChild($xml->createElement('name_ua'));
				$name_uk->appendChild($xml->createTextNode($goods['name']['uk']));
			}

			$categoryId = $offer->appendChild($xml->createElement('categoryId'));
			$categoryId->appendChild($xml->createTextNode($catalog['id']));

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

			if (!empty($goods['vendor_code']) and $goods['vendor_code'] != '-') {

				$vendorCode = $offer->appendChild($xml->createElement('vendorCode'));
				$vendorCode->appendChild($xml->createTextNode($goods['vendor_code']));

			}

			$description = $offer->appendChild($xml->createElement('description'));
			//$description->appendChild($xml->createTextNode($goods_description_view['ru']));
			$description->appendChild($xml->createCDATASection($goods_description_view['ru']));

			if (!empty($goods_description_view['uk'])) {
				$description_uk = $offer->appendChild($xml->createElement('description_ua'));
				//$description->appendChild($xml->createTextNode($goods_description_uk));
				$description_uk->appendChild($xml->createCDATASection($goods_description_view['uk']));
			}

			$goods['keys'] = json_decode($goods['keys'], true);

			$keywords = $offer->appendChild($xml->createElement('keywords'));
			$keywords->appendChild($xml->createTextNode($goods['keys']['ru']));

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				$param = $offer->appendChild($xml->createElement('param'));
				$param->appendChild($xml->createTextNode($parameters_value));
				$param->setAttribute('name', $parameters_key); //атрибуты

			}

			$stock_quantity = $offer->appendChild($xml->createElement('stock_quantity'));
			$stock_quantity->appendChild($xml->createTextNode($goods['availability']));

} else {

	$offers = $shop->appendChild($xml->createElement('offers'));

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