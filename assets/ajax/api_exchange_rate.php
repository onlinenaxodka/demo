<?php

include_once __DIR__ . '/../../config.php';

//branch - https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5
//privat24 - https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11");
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);
$api_exchange_rates = json_decode($response, true);

if (!empty($api_exchange_rates) && is_array($api_exchange_rates)) {
	$currencies = ['USD', 'EUR'];

	foreach ($api_exchange_rates as $api_exchange_rate) {
		if (is_array($api_exchange_rate)) {
			$ccy = $api_exchange_rate['ccy'];
			$base_ccy = $api_exchange_rate['base_ccy'];
			$buy = floatval($api_exchange_rate['buy']);
			$sale = floatval($api_exchange_rate['sale']);

			if (in_array($ccy, $currencies) && $base_ccy == 'UAH' && $buy > 0 && $sale > 0) {
				$sql = "UPDATE `api_exchange_rate` 
						SET `buy`='{$buy}', `sale`='{$sale}', `updated`='{$current_date}' 
						WHERE `ccy`='{$ccy}' AND `base_ccy`='UAH'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			}
		}
	}
}
