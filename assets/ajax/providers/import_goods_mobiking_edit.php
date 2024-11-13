<?php

include_once __DIR__ . '/../../../config.php';

$file_url = 'https://httpclient.mobiking.com.ua:9443/bb629b72bae94fa8bb2fbb55160cfef6_8a34d45fe7d64be78066d98e1ccd5599.xml';
$file_local = __DIR__ . '/../../files/xml_providers/mobiking/import_xml/mobiking.xml';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $file_url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

file_put_contents($file_local, $response);

if (file_exists($file_local)) {
	    
	$xml = simplexml_load_file($file_local);

	foreach ($xml->items->item as $item) {
		
		$goods_vendor_id = test_request($item['id']);
		$goods_vendor_code = test_request($item->vendorCode);

		$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_code}' AND `user_id`=5184 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		
		$count_goods = mysqli_num_rows($query);

		if ($count_goods > 0) {

			$goods = mysqli_fetch_assoc($query);

			$goods_id = $goods['id'];

			$sql = "UPDATE `goods` SET `vendor_id`='{$goods_vendor_id}', `vendor_code`='{$goods_vendor_code}' WHERE `id`='{$goods_id}' AND `user_id`=5184";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

	}

}

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>