<?php

include_once __DIR__ . '/../../../config.php';

$url_xml_file = 'https://httpclient.mobiking.com.ua:9443/bb629b72bae94fa8bb2fbb55160cfef6_8a34d45fe7d64be78066d98e1ccd5599.xml';
$xml_file = __DIR__ . '/../../files/xml_providers/mobiking/import_xml/mobiking.xml';

/*$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: ru\r\n" .
              "Cookie: foo=bar\r\n"
  )
);
$context = stream_context_create($opts);
$file = file_get_contents($url_xml_file, false, $context);
file_put_contents($xml_file, $file);*/

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url_xml_file);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);
//var_dump($response);

file_put_contents($xml_file, $response);

/*$headers = get_headers($xml_file, 1);
var_dump($headers);
echo '<br>';*/

/*if (filter_var($xml_file, FILTER_VALIDATE_URL) !== false) {
	$headers = get_headers($xml_file, 1);
	if (stripos($headers[0], "200 OK")) {
		if (strpos($headers["Content-Type"], 'xml') !== false) {
			$exists_xml_file = true;
		} else {
			$exists_xml_file = false;
			echo 'error Content-Type XML<br>';
		}
	} else {
		$exists_xml_file = false;
		echo 'error 200 OK XML<br>';
	}
} else {
	$exists_xml_file = false;
	echo 'error FILTER_VALIDATE_URL<br>';
}

if ($exists_xml_file == true) {*/

if (file_exists($xml_file)) {

	$xml = simplexml_load_file($xml_file);

	$categories = array();

	$n=0;
	$m=0;
	$k=0;
	$p=0;
	$s=0;

	$top_kurs = test_request($xml->Курс);
	$top_kurs = floatval($top_kurs);

	foreach ($xml->Item as $item) {

		$m++;

		$n += count($item->Картинки->picture);

		$goods_availability = test_request($item->Остаток);
		$goods_availability = intval($goods_availability);

		if ($goods_availability > 0) {
			$k++;
		}

		$price_purchase = test_request($item->ЦенаЗакупки);
		$price_purchase = floatval($price_purchase);
		$price_sale = test_request($item->ЦенаРРЦ);
		$price_sale = floatval($price_sale);

		if ($price_purchase == 0) $p++;
		if ($price_sale == 0) $s++;

		foreach ($item->Свойства->ItemSv as $category) {

			if ($category['Value'] == 'Категория товара') $category_main = strval($category['Name']);
			if ($category['Value'] == 'Подкатегория товаров') $category_main_item = strval($category['Name']);

		}

		$category_main = str_replace("'", '\'', $category_main);

		if (!in_array($category_main, array_keys($categories))) {
			$categories[$category_main][] = $category_main_item;
		} else {
			if (!in_array($category_main_item, $categories[$category_main])) {
				$categories[$category_main][] = $category_main_item;
			}
		}

	}

} else {
	echo 'error XML file<br>';
}

echo 'Зображень: '.$n.'<br>';
echo 'Товарів: '.$m.'<br>';
echo 'К-во > 0: '.$k.'<br>';
echo 'К-во ЦенаЗакупки = 0: '.$p.'<br>';
echo 'К-во ЦенаРРЦ = 0: '.$s.'<br>';
echo 'Курс: '.$top_kurs.'<br><br>';

echo '<ul>';

foreach ($categories as $key => $value) {

	$sql_catalog = "SELECT * FROM `catalog` WHERE `name_ru`='{$key}' AND `level_id`=478 LIMIT 1";
	$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
	$count_catalog = mysqli_num_rows($query_catalog);
	$catalog = mysqli_fetch_assoc($query_catalog);

	if ($count_catalog > 0) {

		$level_id = $catalog['id'];

		$style_no_category = '';

	} else {
		$style_no_category = ' style="color:red;"';
	}
	
	echo '<li>
			<h3'.$style_no_category.'>'.$key.'</h3>
			<ol>';

			foreach ($value as $value_item) {

				$sql_catalog = "SELECT * FROM `catalog` WHERE `name_ru`='{$value_item}' AND `level_id`='{$level_id}' LIMIT 1";
				$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
				$count_catalog_in = mysqli_num_rows($query_catalog);
				$catalog = mysqli_fetch_assoc($query_catalog);

				if ($count_catalog_in > 0) {

					$style_no_category_in = '';

				} else {

					$style_no_category_in = ' style="color:red;"';

				}

				echo '<li'.$style_no_category_in.'>'.$value_item.'</li>';
			}

	echo '</ol>
		</li>';

}

echo '</ul>';

/*echo '<pre>';
print_r($categories);
echo '</pre>';*/

?>