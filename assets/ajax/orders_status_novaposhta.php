<?php

include_once __DIR__ . '/../../config.php';

$sql = "SELECT `id`, `invoice_number` FROM `orders` WHERE `status` NOT IN (7,8,9)";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

	while ($orders = mysqli_fetch_assoc($query)) {

		$order_id = $orders['id'];
		
		$data = array(
			"apiKey" => "API_KEY_SHOULD_BE",
			"modelName" => "TrackingDocument",
			"calledMethod" => "getStatusDocuments",
			"methodProperties" => array(
				"Documents" => array(
					array(
						"DocumentNumber" => $orders['invoice_number'],
						"Phone" => ""
					)
				)
			)
		);
		$data_string = json_encode($data);

		$ch = curl_init('https://api.novaposhta.ua/v2.0/json/');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string))
		);

		$result = curl_exec($ch);
		$result = json_decode($result, true);

		$result_status_code = $result['data'][0]['StatusCode'];

		$sql_up = "UPDATE `orders` SET `status_cs`='{$result_status_code}', `updated`='{$current_date}' WHERE `id`='{$order_id}'";
		$query_up = mysqli_query($db, $sql_up) or die(mysqli_error($db));

	}

}

?>