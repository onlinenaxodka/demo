<?php

$email = 'email';
$pass = 'pass';
$infotype = 6;
$onlyfree = 1;
 
$data = array("Email" => $email, "Pass" => $pass, "InfoType" => $infotype, "OnlyFree" => $onlyfree);
$data_string = json_encode($data);
 
$ch = curl_init('https://connect.erc.ua/connectservice/api/specprice/DoExport');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);

$result = curl_exec($ch);

curl_close($ch);


$data = array("Email" => $email, "Pass" => $pass, "InfoType" => 7, "IsJson" => 1, "CurrencyRateDate" => date('d.m.Y'));
$data_string = json_encode($data);
 
$ch = curl_init('https://connect.erc.ua/connectservice/api/specprice/DoExport');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);

$resultJSON = curl_exec($ch);

curl_close($ch);

$currencyKurs = json_decode($resultJSON, true);


$xml_file = __DIR__ . '/../../../data/files/import/erc_erc6.xml';

file_put_contents($xml_file, $result);

include_once __DIR__ . '/../../../config.php';

/*$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');*/

/*$sql = "UPDATE `goods` SET `status_import`=0 WHERE `user_id`=5856";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

$goods_erc_arr = array();

$sql = "SELECT `vendor_code`, `availability`, `price_agent`, `price_sale` FROM `goods` WHERE `user_id`=5856";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($goods_erc = mysqli_fetch_assoc($query)) {

	$goods_erc_arr[$goods_erc['vendor_code']] = [
		$goods_erc['availability'], 
		$goods_erc['price_agent'], 
		$goods_erc['price_sale']
	];

}

/*$time_start = microtime(true);
$time_end = microtime(true);
echo number_format(($time_end-$time_start), 0, '.', '')." sec.\n";*/

$k = 0;
$n = 0;
$m = 0;
$sql_multi_arr = array();
$vendors_arr = array();
$p = 0;

if (file_exists($xml_file)) {

	$xml = simplexml_load_file($xml_file);

	$sql = '';

	$goods_all_count = 0;
	foreach ($xml->vendor as $vendor) {
		foreach ($vendor->goods as $goods) {
			$goods_all_count++;
		}
	}

	$goods_n = 0;
	$iter_n = 0;
	$goods_n_true = 0;

	$goods_available = [];

	foreach ($xml->vendor as $vendor) {

		foreach ($vendor->goods as $goods) {

			$goods_n++;

			$vendor_code = test_request($goods->code);
			$vendor_code = str_replace("'", "\'", $vendor_code);

			$price_purchase = test_request($goods->sprice);
			$price_purchase = str_replace(",", ".", $price_purchase);
			$price_purchase = floatval($price_purchase);
			$price_purchase = number_format($price_purchase, 2, '.', '');

			$price_purchase_ddp = test_request($goods->ddp);
			$price_purchase_ddp = intval($price_purchase_ddp);

			$price_sale = test_request($goods->RRP_UAH);
			$price_sale = str_replace(",", ".", $price_sale);
			$price_sale = floatval($price_sale);
			$price_sale = number_format($price_sale, 2, '.', '');

			$price_purchase_import = $price_purchase;
			$price_sale_import = $price_sale;

			if ($price_purchase_ddp === 0) {
				$price_purchase = $price_purchase * floatval($currencyKurs['cash']);
			}

			if ($price_purchase > $price_sale) {

				if ($price_purchase < 100) $price_sale = $price_purchase * 2;
				elseif ($price_purchase < 500) $price_sale = $price_purchase * 1.5;
				elseif ($price_purchase < 2000) $price_sale = $price_purchase * 1.3;
				elseif ($price_purchase < 3000) $price_sale = $price_purchase * 1.2;
				elseif ($price_purchase < 5000) $price_sale = $price_purchase * 1.15;
				elseif ($price_purchase < 10000) $price_sale = $price_purchase * 1.1;
				else $price_sale = $price_purchase * 1.05;

				$price_sale = number_format($price_sale, 2, '.', '');

				//echo $vendor_code.": ".$price_purchase." - ".$price_sale."\n";
				$p++;
			}

			$price_purchase = number_format($price_purchase, 2, '.', '');

			$price_agent = 0;

			if ($price_sale >= $price_purchase) {
					
				//if ($user_mentor['agent'] == 1) {

					$price_agent = $price_purchase;

					$price_margine_procent = ($price_sale - $price_purchase) * 0.04;

					if ($price_margine_procent > 0) {

						if ($price_purchase > 0 && $price_purchase <= 500)
							$price_purchase_procent = $price_purchase * 0.05;
						elseif ($price_purchase > 500 && $price_purchase <= 1000)
							$price_purchase_procent = $price_purchase * 0.04;
						elseif ($price_purchase > 1000 && $price_purchase <= 5000)
							$price_purchase_procent = $price_purchase * 0.03;
						elseif ($price_purchase > 5000 && $price_purchase <= 10000)
							$price_purchase_procent = $price_purchase * 0.02;
						elseif ($price_purchase > 10000)
							$price_purchase_procent = $price_purchase * 0.01;

						$price_purchase_preview = $price_purchase + $price_purchase_procent;

						if ($price_purchase_procent > $price_margine_procent)
							$price_purchase_preview = $price_purchase + $price_margine_procent;

						if ($price_purchase_preview > $price_agent and $price_purchase_preview < $price_sale)
							$price_purchase = number_format($price_purchase_preview, 2, '.', '');

					}

				//}

			}

			/*$stock_warehouses = [
				'warehouse1' => $goods->warehouse1 ?: 0,
				'warehouse44' => $goods->warehouse44 ?: 0,
				'warehouse55' => $goods->warehouse55 ?: 0,
				'warehouse5' => $goods->warehouse5 ?: 0,
				'Warehouse95' => $goods->Warehouse95 ?: 0,
				'Warehouse96' => $goods->Warehouse96 ?: 0
			];*/

			$stock_quantity_all_warehouses = 0;

			foreach ($goods->whs->wh as $stock_warehouse) {
				
				$stock_quantity = $stock_warehouse['q'];
				$stock_quantity_preview = intval($stock_quantity);

				if ($stock_quantity_preview == 0) {
					if ($stock_quantity != '0') $stock_quantity = substr($stock_quantity, 1);
				}

				$stock_quantity = test_request($stock_quantity);
				$stock_quantity = intval($stock_quantity);

				if ($stock_quantity > 0) $stock_quantity_all_warehouses += $stock_quantity;

			}

			$stock_quantity = $stock_quantity_all_warehouses;

			/*$stock_quantity = $goods->amount;
			$stock_quantity_preview = intval($stock_quantity);

			if ($stock_quantity_preview == 0) {
				if ($stock_quantity != '0') $stock_quantity = substr($stock_quantity, 1);
			}

			$stock_quantity = test_request($stock_quantity);
			$stock_quantity = intval($stock_quantity);*/

			/*$stock_warehouse1 = test_request($goods->warehouse1);
			$stock_warehouse1 = intval($stock_warehouse1);

			if ($stock_warehouse1 == 0) {

				$stock_quantity = 0;

			}*/
			
			if ($stock_quantity > 0) {
				$status = 1;
			} else {
				$status = 0;
			}

			if ($price_agent == $price_sale or $price_purchase == $price_sale) {
				$status = 0;
			}

			if ($price_purchase_import == $price_sale_import) {
				$status = 0;
			}

			if ($status == 1) {

				$m++;
				
			}

			if (in_array($vendor_code, array_keys($goods_erc_arr))) {

				$goods_erc_arr_once = $goods_erc_arr[$vendor_code];

				if ($stock_quantity != $goods_erc_arr_once[0] or $price_agent != $goods_erc_arr_once[1] or $price_sale != $goods_erc_arr_once[2]) {

					/*$goods_available[] = [
						'price_agent' => $price_agent,
						'price_purchase' => $price_purchase,
						'price_sale' => $price_sale,
						'availability' => $stock_quantity,
						'status' => $status,
					];*/

					/*echo "\n";
					echo "vendor_code - ".$vendor_code."\n";
					echo "stock_quantity - ".$stock_quantity."\n";
					echo "goods_erc_arr_once_0 - ".$goods_erc_arr_once[0]."\n";
					echo "price_agent - ".$price_agent."\n";
					echo "goods_erc_arr_once_1 - ".$goods_erc_arr_once[1]."\n";
					echo "price_sale - ".$price_sale."\n";
					echo "goods_erc_arr_once_2 - ".$goods_erc_arr_once[2]."\n";*/

					$sql = "UPDATE `goods` SET `price_agent`={$price_agent},
												`price_purchase`={$price_purchase},
												`price_sale`={$price_sale},
												`availability`={$stock_quantity},
												`status`={$status},
												`status_import`=1,
												`updated`='{$current_date}' WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856;";

					//$goods_available[] = $sql;
					$goods_available[] = $vendor_code;
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$goods_n_true++;
					
					/*if (mb_strlen($sql) <= 49000) {
						if ($goods_n == $goods_all_count) {
							$iter_n++;
							echo "- chunk_total: ".$goods_n_true."\n
								- add_total: ".$goods_n."\n
								- total: " .$goods_all_count."\n
								- chunk: ".$iter_n."\n";
							$query = mysqli_multi_query($db, $sql) or die(mysqli_error($db));
						}
					} elseif (mb_strlen($sql) > 49000 and mb_strlen($sql) < 50000) {
						$iter_n++;
						echo "- chunk_total: ".$goods_n_true."\n
							- add_total: ".$goods_n."\n
							- total: " .$goods_all_count."\n
							- chunk: ".$iter_n."\n";
						$query = mysqli_multi_query($db, $sql) or die(mysqli_error($db));
						$sql = '';
						$goods_n_true = 0;
					}

					do {
					    if ($result = mysqli_store_result($db)) {
					        while ($row = mysqli_fetch_row($result)) {
					            printf("%s\n", $row[0]);
					        }
					    }
					    if (mysqli_more_results($db)) {
					        //printf("-----------------\n");
					    }
					} while (mysqli_next_result($db));*/

					$k++;

					//echo $vendor_code.": ".$price_agent." - ".$price_sale."\n";
					//echo $vendor_code." - ".$stock_quantity."\n";

				}

			}

			$vendors_arr[] = $vendor_code;

			$n++;

			/*$sql = "SELECT `id` FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);

			$goods_id = $goods['id'];

			if ($count_goods > 0) {

				$sql = "UPDATE `goods` SET `currency`=1,
											`currency_top_kurs`=1,
											`price_agent`={$price_agent},
											`price_purchase`={$price_purchase},
											`price_sale`={$price_sale},
											`availability`={$stock_quantity},
											`status`={$status},
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				//echo $vendor_code.": ".$price_agent." - ".$price_sale."\n";

			}*/

		}

	}



	/*$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=5856";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	*/

}

$vendors_not_available = array();

if ($xml and count($xml) > 0) {

	foreach ($goods_erc_arr as $goods_erc_key => $goods_erc_value) {
		
		if (!in_array($goods_erc_key, $vendors_arr)) {

			$vendors_not_available[] = $goods_erc_key;

		}

	}

	$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `user_id`=5856 AND `vendor_code` IN ('".implode("','", $vendors_not_available)."') AND `status`=1";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

$sql = "UPDATE `goods` SET `updated`='{$current_date}' WHERE `user_id`=5856 AND `updated`<'{$current_date}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

mysqli_close($db);

echo "\nItems: " . count($goods_available);

echo "\n\n";

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds \n
- all: ".$n." \n
- price incorrect: ".$p." \n
- in_stock: ".$m." \n
- updated: ".$k."\n
- closed: ".count($vendors_not_available)."\n";

echo "\n\n";

?>