<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

$xml_file = 'https://online-naxodka.prom.ua/yandex_market.xml?hash_tag=e3b1d71ae6449be0f11e40dacd01f142&sales_notes=&product_ids=&group_ids=46056208%2C46056272%2C63182580%2C63182632&label_ids=&exclude_fields=&html_description=1&yandex_cpa=&process_presence_sure=';

if (filter_var($xml_file, FILTER_VALIDATE_URL) !== false) {
	$headers = get_headers($xml_file, 1);
	if (stripos($headers[0], "200 OK")) {
		if (strpos($headers["Content-Type"], 'xml') !== false) {
			$exists_xml_file = true;
		} else {
			$exists_xml_file = false;
		}
	} else {
		$exists_xml_file = false;
	}
} else {
	$exists_xml_file = false;
}

if ($exists_xml_file == true) {
    
    $xml = simplexml_load_file($xml_file);

	$cnt_imgs = 0;

	foreach ($xml->shop->offers->offer as $offer) {

		$goods_vendor_id = test_request($offer['id']);

		$sql = "SELECT `id` FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods = mysqli_num_rows($query);

		if (count($offer->picture) > 0 and $count_goods == 0) {

			$cnt_imgs += count($offer->picture);

			foreach ($offer->picture as $picture) {

				if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
					
					$headers = get_headers($picture, 1);
				}
			}

		}

	}

	echo $cnt_imgs;

	

} else {

    file_put_contents("../../files/xml_providers/our_turbines/exists_xml_file.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file_1." или ".$xml_file_2." или ".$xml_file_3.".\n", FILE_APPEND | LOCK_EX);

}

?>