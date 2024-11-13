<?php

header('Content-Type: text/html; charset=utf-8');

function test_request($data) {
	$data = strip_tags($data);
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$locality_value = test_request($_GET['term']);

if (empty($locality_value)) exit('Error data...');

$api_key = 'API_KEY_SHOULD_BE';
$model_name = 'Address';
$called_method = 'searchSettlements';
$method_properties = "\"CityName\": \"".$locality_value."\",\r\n \"Limit\": 5";

function curl_request_np($api_key, $model_name, $called_method, $method_properties) {

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\r\n\"apiKey\": \"".$api_key."\",\r\n\"modelName\": \"".$model_name."\",\r\n\"calledMethod\": \"".$called_method."\",\r\n\"methodProperties\": {\r\n".$method_properties."\r\n}\r\n}",
		CURLOPT_HTTPHEADER => array("content-type: application/json",)
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	$arr_result[] = $response;
	$arr_result[] = $err;

	return $arr_result;

}

$arr_result = curl_request_np($api_key, $model_name, $called_method, $method_properties);

$err = $arr_result[1];
$response = $arr_result[0];

if ($err) {
	echo "Error #:" . $err;
} else {

	$result = json_decode($response, true);
	
	for ($i = 0; $i < count($result['data'][0]['Addresses']); $i++) {

		$addresses = $result['data'][0]['Addresses'][$i];

		if ($addresses['Warehouses'] > 0) {

			$called_method = 'getWarehouses';
			$method_properties = "\"CityRef\": \"".$addresses['DeliveryCity']."\"";

			$arr_result2 = curl_request_np($api_key, $model_name, $called_method, $method_properties);

			$response2 = $arr_result2[0];

			$result2 = json_decode($response2, true);

			if (!empty($data2)) unset($data2);

			for ($j = 0; $j < count($result2['data']); $j++) { 
				
				$data2[] = $result2['data'][$j]['Description'];

			}

			$data[] = array(
				'value' => $addresses['SettlementTypeCode'].$addresses['MainDescription'].' ('.$addresses['Area'].' обл.)',
				'branch' => $data2
			);

		}

	}

	echo json_encode($data);
}

?>