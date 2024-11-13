<?php

include_once __DIR__ . '/../../config.php';

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

//$post_data = test_request($_POST);
$post_data = $_POST;
$test = json_encode($post_data, JSON_UNESCAPED_UNICODE);

$key = $post_data['apiKey'];
$post_order_id = $post_data['id'];

$error = false;

if (!empty($post_data)) {

	if ($key == '123') {

		$name = $post_data['data']['firstname'];

		if (!empty($name)) {

			if (strlen(utf8_decode($name)) < 2) {

				$error = true;

			}

		} else {

			$error = true;
			
		}

		$surname = $post_data['data']['lastname'];

		if (!empty($surname)) {

			if (strlen(utf8_decode($surname)) < 2) {

				$error = true;

			}

		} else {

			$error = true;
			
		}

		$phone = $post_data['data']['telephone'];

		if (!empty($phone)) {

			if (!preg_match("/^[+]38\s\([0-9]{3}\)\s[0-9]{7}$/", $phone)) {

				$error = true;

			}

		} else {

			$error = true;
			
		}

		$client['fio'] = $surname.' '.$name;
		$client['phone'] = $phone;

		$client = json_encode($client, JSON_UNESCAPED_UNICODE);
		$client = str_replace("'", "\'", $client);

		$payment = $post_data['data']['payment_code'];

		switch ($payment) {
			case 'cod':
				$payment = 1;
				break;
			case 'revpay2':
			case 'shoputils_ik':
				$payment = 2;
				break;
			default:
				$payment = 1;
				break;
		}

		$prepayment = 0;
		$reserve_balance = 0;

		$delivery = $post_data['data']['shipping_code'];

		switch ($delivery) {
			case 'novaposhta.warehouse':
				$delivery = 1;
				break;
			default:
				$delivery = 1;
				break;
		}

		$delivery_address['city'] = $post_data['data']['shipping_city'];
		$delivery_address['address_1'] = $post_data['data']['shipping_address_1'];
		$delivery_address['address_2'] = $post_data['data']['shipping_address_2'];

		$delivery_address = json_encode($delivery_address, JSON_UNESCAPED_UNICODE);
		$delivery_address = str_replace("'", "\'", $delivery_address);

		if (empty($delivery_address['city']) or empty($delivery_address['city'])) {

			$error = true;

		}

		for ($i=0; $i < count($post_data['data']['products']); $i++) {

			$goods_id = $post_data['data']['products'][$i]['product_id'];
			$goods_id = intval($goods_id);

			$availability = $post_data['data']['products'][$i]['quantity'];
			$availability = intval($availability);

			$goods_price = $post_data['data']['products'][$i]['price'];
			$goods_price = intval($goods_price);

			if ($goods_id > 0 and $availability > 0 and $goods_price > 0) {

				$sql_valid = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
				$query_valid = mysqli_query($db, $sql_valid) or die(mysqli_error($db));
				$goods_valid = mysqli_fetch_assoc($query_valid);
				$goods_valid['name'] = json_decode($goods_valid['name'], true);

				$goods_linkname = $goods_valid['category'];

				$sql_rate = "SELECT * FROM `catalog` WHERE `linkname`='{$goods_linkname}'";
				$query_rate = mysqli_query($db, $sql_rate) or die(mysqli_error());
				$catalog_rate = mysqli_fetch_assoc($query_rate);

				if ($goods_valid['currency'] == 1) {

					$kurs_currency = 1;

				} else if ($goods_valid['currency'] == 2) {

					$kurs_currency = $api_exchange_rate_usd['sale'];

					if ($goods_valid['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

						$kurs_currency = $goods_valid['currency_top_kurs'];

					}

				} else if ($goods_valid['currency'] == 3) {

					$kurs_currency = $api_exchange_rate_eur['sale'];

					if ($goods_valid['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

						$kurs_currency = $goods_valid['currency_top_kurs'];

					}

				}

				$price_agent = ceil($goods_valid['price_agent'] * $kurs_currency);
				$price_purchase = ceil($goods_valid['price_purchase'] * $kurs_currency);
				$price_sale = ceil($goods_valid['price_sale'] * $kurs_currency);

				$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $catalog_rate['rate']));

				if ($goods_price >= $price_min) {

					$goods[$i]['id'] = $goods_id;
					$goods[$i]['user_id'] = $goods_valid['user_id'];
					$goods[$i]['name'] = $goods_valid['name']['ru'];
					$goods[$i]['availability'] = $availability;
					$goods[$i]['goods_price_agent'] = $price_agent;
					$goods[$i]['goods_price_agent_native'] = $goods_valid['price_agent'];
					$goods[$i]['goods_price_purchase'] = $price_purchase;
					$goods[$i]['goods_price_purchase_native'] = $goods_valid['price_purchase'];
					$goods[$i]['goods_price_recom'] = $price_sale;
					$goods[$i]['goods_price_recom_native'] = $goods_valid['price_sale'];
					$goods[$i]['goods_price'] = $goods_price;
					$goods[$i]['catalog_rate'] = $catalog_rate['rate'];
					$goods[$i]['currency_kurs'] = $kurs_currency;

					$sum_goods_price += $goods_price * $availability;
					$sum_income += ($goods_price - $price_min) * $availability;

				} else {

					$error = true;

				}

			} else {

				$error = true;

			}

		}

		$goods = json_encode($goods, JSON_UNESCAPED_UNICODE);
		$goods = str_replace("'", "\'", $goods);

		$comment = 'Детали заказа #'.$post_order_id.' '.$post_data['data']['comment'];

		//if (!$error) {

			$sql = "INSERT INTO `orders` SET `user_id`=7037,
												`client`='{$client}',
												`delivery`='{$delivery}',
												`delivery_address`='{$delivery_address}',
												`payment`='{$payment}',
												`goods`='{$goods}',
												`reserve_balance`='{$reserve_balance}',
												`prepayment`='{$prepayment}',
												`amount`='{$sum_goods_price}',
												`income`='{$sum_income}',
												`updated`='{$current_date}',
												`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$order_id = mysqli_insert_id($db);

			if (!empty($comment)) {

				$sql = "INSERT INTO `orders_messages` SET `user_id`=7037,
															`order_id`='{$order_id}',
															`type_user`=1,
															`message`=\"{$comment}\",
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			/*$email = "mail@gmail.com";
			$subject = "У вас новый заказ №".$order_id." в ".$name_company;
			$message = "<h3 style='text-align:center;'>Обработайте заказ №".$order_id."<h3>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/orders/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Обработать заказ</a>
						<br>
						".$turbine_info_client."
						<p style='text-align:center;font-weight:normal;font-style:italic;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);*/

		//}

	}

}

mysqli_close($db);

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";