<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$act = (isset($_POST['act'])) ? mysqli_real_escape_string($db, $_POST['act']) : '';
		$act = test_request($act);

		$order_id = (isset($_POST['order'])) ? mysqli_real_escape_string($db, $_POST['order']) : '';
		$order_id = test_request($order_id);
		$order_id = intval($order_id);

		$goods_id = (isset($_POST['goods_id'])) ? mysqli_real_escape_string($db, $_POST['goods_id']) : '';
		$goods_id = test_request($goods_id);
		$goods_id = intval($goods_id);

		$goods_availability = (isset($_POST['goods_availability'])) ? mysqli_real_escape_string($db, $_POST['goods_availability']) : '';
		$goods_availability = test_request($goods_availability);
		$goods_availability = intval($goods_availability);

		$goods_price = (isset($_POST['goods_price'])) ? mysqli_real_escape_string($db, $_POST['goods_price']) : '';
		$goods_price = test_request($goods_price);
		$goods_price = intval($goods_price);

		$prepayment = (isset($_POST['prepayment'])) ? mysqli_real_escape_string($db, $_POST['prepayment']) : '';
		$prepayment = test_request($prepayment);
		$prepayment = intval($prepayment);

		$comment = (isset($_POST['comment'])) ? mysqli_real_escape_string($db, $_POST['comment']) : '';
		$comment = str_replace('\r\n', '\\\r\\\n', $comment);
		$comment = test_request($comment);

		$status = (isset($_POST['status'])) ? mysqli_real_escape_string($db, $_POST['status']) : '';
		$status = test_request($status);
		$status = intval($status);

		$payment = (isset($_POST['payment'])) ? mysqli_real_escape_string($db, $_POST['payment']) : '';
		$payment = test_request($payment);
		$payment = intval($payment);

		$invoice_number = (isset($_POST['invoice_number'])) ? mysqli_real_escape_string($db, $_POST['invoice_number']) : '';
		$invoice_number = test_request($invoice_number);

		$failure_commission = (isset($_POST['failure_commission'])) ? mysqli_real_escape_string($db, $_POST['failure_commission']) : '';
		$failure_commission = test_request($failure_commission);
		$failure_commission = floatval($failure_commission);

		$status_provider = (isset($_POST['status_provider'])) ? mysqli_real_escape_string($db, $_POST['status_provider']) : '';
		$status_provider = test_request($status_provider);
		$status_provider = intval($status_provider);

		$note = (isset($_POST['note'])) ? mysqli_real_escape_string($db, $_POST['note']) : '';
		$note = str_replace('\r\n', '\\\r\\\n', $note);
		$note = test_request($note);

		$order_filter_date_from = (isset($_POST['order_filter_date_from'])) ? mysqli_real_escape_string($db, $_POST['order_filter_date_from']) : '';
		$order_filter_date_from = test_request($order_filter_date_from);

		$order_filter_date_to = (isset($_POST['order_filter_date_to'])) ? mysqli_real_escape_string($db, $_POST['order_filter_date_to']) : '';
		$order_filter_date_to = test_request($order_filter_date_to);

		$order_filter_user = (isset($_POST['order_filter_user'])) ? mysqli_real_escape_string($db, $_POST['order_filter_user']) : '';
		$order_filter_user = test_request($order_filter_user);

		$order_filter_gtm = (isset($_POST['order_filter_gtm'])) ? mysqli_real_escape_string($db, $_POST['order_filter_gtm']) : '';
		$order_filter_gtm = test_request($order_filter_gtm);

		$order_filter_name_goods = (isset($_POST['order_filter_name_goods'])) ? mysqli_real_escape_string($db, $_POST['order_filter_name_goods']) : '';
		$order_filter_name_goods = test_request($order_filter_name_goods);

		$order_filter_payment = (isset($_POST['order_filter_payment'])) ? mysqli_real_escape_string($db, $_POST['order_filter_payment']) : '';
		$order_filter_payment = test_request($order_filter_payment);

		$order_filter_status_cs = (isset($_POST['order_filter_status_cs'])) ? mysqli_real_escape_string($db, $_POST['order_filter_status_cs']) : '';
		$order_filter_status_cs = test_request($order_filter_status_cs);

		$order_filter_status = (isset($_POST['order_filter_status'])) ? mysqli_real_escape_string($db, $_POST['order_filter_status']) : '';
		$order_filter_status = test_request($order_filter_status);

		$order_filter_status_provider = (isset($_POST['order_filter_status_provider'])) ? mysqli_real_escape_string($db, $_POST['order_filter_status_provider']) : '';
		$order_filter_status_provider = test_request($order_filter_status_provider);

		$order_filter_provider = (isset($_POST['order_filter_provider'])) ? mysqli_real_escape_string($db, $_POST['order_filter_provider']) : '';
		$order_filter_provider = test_request($order_filter_provider);

		$clear_order_filter = (isset($_POST['clear_order_filter'])) ? mysqli_real_escape_string($db, $_POST['clear_order_filter']) : '';
		$clear_order_filter = test_request($clear_order_filter);
		$clear_order_filter = intval($clear_order_filter);

		if ($clear_order_filter == 1) {
			
			if (!empty($_SESSION['order_filter'])) {

				unset($_SESSION['order_filter']);

				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			}

		}

		if (!empty($order_filter_date_from) or !empty($order_filter_date_to) or !empty($order_filter_user) or !empty($order_filter_name_goods) or !empty($order_filter_payment) or !empty($order_filter_status_cs) or !empty($order_filter_status) or !empty($order_filter_status_provider) or !empty($order_filter_provider)) {

			if (!empty($_SESSION['order_filter'])) unset($_SESSION['order_filter']);

		}

		if (!empty($order_filter_date_from)) {
			
			$order_filter_date_from = date('Y-m-d', strtotime($order_filter_date_from));
			$_SESSION['order_filter']['date_from'] = $order_filter_date_from;

		}

		if (!empty($order_filter_date_to)) {
			
			$order_filter_date_to = date('Y-m-d', strtotime($order_filter_date_to));
			$_SESSION['order_filter']['date_to'] = $order_filter_date_to;

		}

		if (!empty($order_filter_user) and $order_filter_user != 'none') {

			$order_filter_user = intval($order_filter_user);
			$_SESSION['order_filter']['user'] = $order_filter_user;
			
		}

		if (!empty($order_filter_gtm) and $order_filter_gtm != 'none') {

			if ($order_filter_gtm == 'google') {

				$_SESSION['order_filter']['gtm'] = $order_filter_gtm;
				
			}
			
		}

		if (!empty($order_filter_name_goods)) {
			
			$_SESSION['order_filter']['name_goods'] = $order_filter_name_goods;

		}

		if (!empty($order_filter_payment) and $order_filter_payment != 'none') {
			
			$order_filter_payment = intval($order_filter_payment);
			$_SESSION['order_filter']['payment'] = $order_filter_payment;
			
		}

		if ($order_filter_status_cs != '' and $order_filter_status_cs != 'none') {
			
			$order_filter_status_cs = intval($order_filter_status_cs);
			$_SESSION['order_filter']['status_cs'] = $order_filter_status_cs;
			
		}

		if ($order_filter_status != '' and $order_filter_status != 'none') {
			
			$order_filter_status = intval($order_filter_status);
			$_SESSION['order_filter']['status'] = $order_filter_status;
			
		}

		if ($order_filter_status_provider != '' and $order_filter_status_provider != 'none') {
			
			$order_filter_status_provider = intval($order_filter_status_provider);
			$_SESSION['order_filter']['status_provider'] = $order_filter_status_provider;
			
		}

		if (!empty($order_filter_provider) and $order_filter_provider != 'none') {

			$order_filter_provider = intval($order_filter_provider);
			$_SESSION['order_filter']['provider'] = $order_filter_provider;
			
		}

		if (!empty($order_filter_date_from) or !empty($order_filter_date_to) or !empty($order_filter_user) or !empty($order_filter_payment) or !empty($order_filter_status_cs) or !empty($order_filter_status) or !empty($order_filter_status_provider) or !empty($order_filter_provider)) {

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $payment > 0) {

			if ($payment == 1 or $payment == 2) {

				$sql = "UPDATE `orders` SET `payment`='{$payment}', `updated`='{$current_date}' WHERE `id`='{$order_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $goods_id > 0 and $goods_availability > 0  and $goods_price > 0) {

			$sql_valid = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
			$query_valid = mysqli_query($db, $sql_valid) or die(mysqli_error($db));
			$goods_valid = mysqli_fetch_assoc($query_valid);

			$goods_valid['name'] = json_decode($goods_valid['name'], true);

			$goods_linkname = $goods_valid['category'];

			$sql_rate = "SELECT * FROM `catalog` WHERE `linkname`='{$goods_linkname}'";
			$query_rate = mysqli_query($db, $sql_rate) or die(mysqli_error($db));
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

				$goods_new['id'] = $goods_id;
				$goods_new['user_id'] = $goods_valid['user_id'];
				$goods_new['name'] = $goods_valid['name']['ru'];
				$goods_new['availability'] = $goods_availability;
				$goods_new['goods_price_agent'] = $price_agent;
				$goods_new['goods_price_agent_native'] = $goods_valid['price_agent'];
				$goods_new['goods_price_purchase'] = $price_purchase;
				$goods_new['goods_price_purchase_native'] = $goods_valid['price_purchase'];
				$goods_new['goods_price_recom'] = $price_sale;
				$goods_new['goods_price_recom_native'] = $goods_valid['price_sale'];
				$goods_new['goods_price'] = $goods_price;
				$goods_new['catalog_rate'] = $catalog_rate['rate'];
				$goods_new['currency_kurs'] = $kurs_currency;

			} else {

				$error = true;
				$error_message_cart_goods2 = 'Ваша цена на некоторых товарах ниже минимальной цены товара. ';

			}

			$sql_goods = "SELECT * FROM `orders` WHERE `id`='{$order_id}'";
			$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
			$order_goods = mysqli_fetch_assoc($query_goods);

			$goods_in_order = json_decode($order_goods['goods'], true);

			$is_goods = false;

			for ($i=0; $i < count($goods_in_order); $i++) { 
				
				if ($goods_in_order[$i]['id'] == $goods_id) {
					$is_goods = true;
					$goods_in_order[$i]['availability'] = $goods_availability;
				}

			}

			if (!$is_goods) $goods_in_order[] = $goods_new;

			$sum_goods_price = 0;
			$sum_income = 0;

			for ($i=0; $i < count($goods_in_order); $i++) {

				$kurs_currency = $goods_in_order[$i]['currency_kurs'];

				$goods_price = $goods_in_order[$i]['goods_price'];

				$price_purchase = ceil($goods_in_order[$i]['goods_price_purchase_native'] * $kurs_currency);
				$price_sale = ceil($goods_in_order[$i]['goods_price_recom_native'] * $kurs_currency);

				$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $goods_in_order[$i]['catalog_rate']));

				$sum_goods_price += $goods_price * $goods_in_order[$i]['availability'];
				$sum_income += ($goods_price - $price_min) * $goods_in_order[$i]['availability'];

			}
			/*echo '<pre>';
			print_r($goods_in_order);
			echo '</pre>';*/

			/*if ($order_goods['prepayment'] > 0) {
				$sum_goods_price = $sum_goods_price - $order_goods['prepayment'];
			}*/

			$goods = json_encode($goods_in_order, JSON_UNESCAPED_UNICODE);
			$goods = str_replace("'", "\'", $goods);

			$sql = "UPDATE `orders` SET /*`payment`='{$payment}',*/
										`goods`='{$goods}',
										`amount`='{$sum_goods_price}',
										`income`='{$sum_income}',
										`updated`='{$current_date}' WHERE `id`={$order_id}";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $prepayment >= 0 and $act == 'prepayment') {

			$sql_goods = "SELECT * FROM `orders` WHERE `id`='{$order_id}'";
			$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
			$order_goods = mysqli_fetch_assoc($query_goods);

			if ($prepayment <= $order_goods['amount']) {

				$payment = $order_goods['payment'];

				if ($prepayment == $order_goods['amount']) {
					$payment = 2;
				}
				
				/*`amount`='{$sum_goods_price}',`income`='{$sum_income}',*/
				$sql = "UPDATE `orders` SET `payment`='{$payment}',
											`prepayment`='{$prepayment}',
											`updated`='{$current_date}' WHERE `id`={$order_id}";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $goods_id > 0 and $act == 'goods_delete') {

			$sql_goods = "SELECT * FROM `orders` WHERE `id`='{$order_id}'";
			$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
			$order_goods = mysqli_fetch_assoc($query_goods);

			$goods_in_order = json_decode($order_goods['goods'], true);

			$sum_goods_price = 0;
			$sum_income = 0;

			$goods_in_order_up = [];

			for ($i=0; $i < count($goods_in_order); $i++) {

				if ($goods_in_order[$i]['id'] != $goods_id) {

					$goods_in_order_up[] = $goods_in_order[$i];

					$kurs_currency = $goods_in_order[$i]['currency_kurs'];

					$goods_price = $goods_in_order[$i]['goods_price'];

					$price_purchase = ceil($goods_in_order[$i]['goods_price_purchase_native'] * $kurs_currency);
					$price_sale = ceil($goods_in_order[$i]['goods_price_recom_native'] * $kurs_currency);

					$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $goods_in_order[$i]['catalog_rate']));

					$sum_goods_price += $goods_price * $goods_in_order[$i]['availability'];
					$sum_income += ($goods_price - $price_min) * $goods_in_order[$i]['availability'];

				}

			}

			$goods = json_encode($goods_in_order_up, JSON_UNESCAPED_UNICODE);
			$goods = str_replace("'", "\'", $goods);

			$sql = "UPDATE `orders` SET `goods`='{$goods}',
										`amount`='{$sum_goods_price}',
										`income`='{$sum_income}',
										`updated`='{$current_date}' WHERE `id`={$order_id}";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and !empty($note)) {

			$sql = "INSERT INTO `orders_notes` SET `user_id`='{$user_id}',
													`order_id`='{$order_id}',
													`note`=\"{$note}\",
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$email = $email_for_notify;

			if ($user_id == 4) $email = $email_for_notify;

			$subject = "Новое примечание по заказу №".$order_id." от поддержки ".$name_company;
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$users_post['name']."!</h1>
							<p style='text-align:center'>У вас есть одно новое примечание по заказу №".$order_id." от поддержки ".$name_company."</p>
							<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить примечание</a>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Прислано в: ".date('H:i d.m.Y')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $status_provider > 0) {

			$sql = "UPDATE `orders` SET `status_provider`='{$status_provider}', `updated`='{$current_date}' WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and !empty($invoice_number)) {

			$sql = "SELECT `user_id` FROM `orders` WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$orders_post = mysqli_fetch_assoc($query);
			$orders_post_user_id = $orders_post['user_id'];

			$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$users_post = mysqli_fetch_assoc($query);

			$sql = "UPDATE `orders` SET `invoice_number`='{$invoice_number}', `updated`='{$current_date}' WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$email = $users_post['mail'];
			$subject = "Внесен номер накладной по заказу №".$order_id;
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$users_post['name']."!</h1>
							<p style='text-align:center'>К вашему заказу <b>№".$order_id."</b> добавлен номер накладной <b>".$invoice_number."</b> службы доставки.</p>
							<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/orders/#".$order_id."' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить заказ</a>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Прислано в: ".date('H:i d.m.Y')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			header('Location: '.$_SERVER['REQUEST_URI'].'#'.$order_id);
			exit;

		}

		if ($order_id > 0 and !empty($comment)) {

			$sql = "SELECT `user_id` FROM `orders` WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$orders_post = mysqli_fetch_assoc($query);
			$orders_post_user_id = $orders_post['user_id'];

			$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$users_post = mysqli_fetch_assoc($query);

			$sql = "INSERT INTO `orders_messages` SET `user_id`='{$user_id}',
														`order_id`='{$order_id}',
														`type_user`=0,
														`message`=\"{$comment}\",
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$email = $users_post['mail'];
			$subject = "Новый комментарий по заказу №".$order_id." от поддержки ".$name_company;
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$users_post['name']."!</h1>
							<p style='text-align:center'>У вас есть один новый комментарий по заказу №".$order_id." от поддержки ".$name_company."</p>
							<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить комментарий</a>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Прислано в: ".date('H:i d.m.Y')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		}

		if ($order_id > 0 and $status >= 0 and isset($_POST['status'])) {

			$sql = "SELECT * FROM `orders` WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$orders_post = mysqli_fetch_assoc($query);
			$orders_post_user_id = $orders_post['user_id'];

			if ($status == 7 and $orders_post['status'] != 7) {

				if ($orders_post['reserve_balance'] > 0) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_sum = $users_post_cash + $orders_post['reserve_balance'];

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Возвращение страховой суммы заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 0, $action, $users_post_cash, $orders_post['reserve_balance'], $users_post_cash_sum, 2, $current_date);

				}

				/*if ($orders_post['payment'] == 3) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_sum = $users_post_cash - $orders_post['amount'];

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Оплата полной стоимости заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 1, $action, $users_post_cash, $orders_post['amount'], $users_post_cash_sum, 2, $current_date);

				}*/

				include_once __DIR__ . '/admin_user_status/profit_sharing.php';

				include_once __DIR__ . '/admin_user_status/system_accruals.php';

				$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$users_post = mysqli_fetch_assoc($query);
				$users_post_cash = $users_post['cash'];
				$orders_post_income = $orders_post['income'];
				$users_post_cash_sum = $users_post_cash + $orders_post_income;

				$sql = "SELECT `id` FROM `orders` WHERE `status`=7 AND `user_id`='{$orders_post_user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$orders_completed_count = mysqli_num_rows($query);

				$action_partner = 'Прибыль с заказа №'.$order_id.' партнера '.$users_post['name'].' '.$users_post['surname'];
				$action_structural_accruals = 'Прибыль с собственного заказа №'.$order_id;
				$action = 'Прибыль с собственного заказа №'.$order_id;

				if ($orders_post_user_id != 7037) {

				if ($users_post['status'] == 0) {

					include_once __DIR__ . '/admin_user_status/status_0.php';

				} elseif ($users_post['status'] == 1) {

					include_once __DIR__ . '/admin_user_status/status_1.php';

				} elseif ($users_post['status'] == 2) {

					include_once __DIR__ . '/admin_user_status/status_2.php';

				} elseif ($users_post['status'] == 3) {

					include_once __DIR__ . '/admin_user_status/status_3.php';

				} elseif ($users_post['status'] == 4) {

					include_once __DIR__ . '/admin_user_status/status_4.php';

				}

				}

			} elseif ($status == 8 and $orders_post['status'] != 8) {

				if ($orders_post['reserve_balance'] > 0) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_sum = $users_post_cash + $orders_post['reserve_balance'];

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Возвращение страховой суммы заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 0, $action, $users_post_cash, $orders_post['reserve_balance'], $users_post_cash_sum, 2, $current_date);

				}

				if ($orders_post_user_id != 7037) {

				if ($orders_post['prepayment'] > 0) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_sum = $users_post_cash + $orders_post['prepayment'];

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Возвращение предоплаты заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 0, $action, $users_post_cash, $orders_post['prepayment'], $users_post_cash_sum, 2, $current_date);

				}

				}

				if ($orders_post['payment'] == 3) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_sum = $users_post_cash + $orders_post['amount'];

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Возвращение оплаты заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 0, $action, $users_post_cash, $orders_post['amount'], $users_post_cash_sum, 2, $current_date);

				}

			} elseif ($status == 9 and $orders_post['status'] != 9) {

				if ($orders_post['reserve_balance'] > $failure_commission) {

					$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$users_post = mysqli_fetch_assoc($query);
					$users_post_cash = $users_post['cash'];
					$users_post_cash_dif_reserve_balance = $orders_post['reserve_balance'] - $failure_commission;
					$users_post_cash_sum = $users_post_cash + $users_post_cash_dif_reserve_balance;

					$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Возвращение остатка страховой суммы заказа №'.$order_id;
					if ($failure_commission == 0)
						$action = 'Возвращение страховой суммы заказа №'.$order_id;

					transactionOrder($db, $orders_post_user_id, $order_id, 0, $action, $users_post_cash, $users_post_cash_dif_reserve_balance, $users_post_cash_sum, 2, $current_date);

				} else {

					if ($failure_commission > 0) {

						if ($orders_post['reserve_balance'] < $failure_commission)
							$failure_commission = $failure_commission - $orders_post['reserve_balance'];

						$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$users_post = mysqli_fetch_assoc($query);
						$users_post_cash = $users_post['cash'];
						$users_post_cash_sum = $users_post_cash - $failure_commission;

						$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						$action = 'Списание комиссии за доставку посылки в обе стороны. Причина - отказ от заказа №'.$order_id.' на отделении службы доставки.';
						if ($orders_post['reserve_balance'] > 0)
							$action = 'Списание недостающей суммы комиссии за доставку посылки в обе стороны. Причина - отказ от заказа №'.$order_id.' на отделении службы доставки.';

						transactionOrder($db, $orders_post_user_id, $order_id, 6, $action, $users_post_cash, $failure_commission, $users_post_cash_sum, 2, $current_date);

					}

				}

				//доробити повернення предоплати і повної оплати

			}

			$sql = "UPDATE `orders` SET `status`='{$status}', `updated`='{$current_date}' WHERE `id`='{$order_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: '.$_SERVER['REQUEST_URI'].'#'.$order_id);
			exit;

		}

	}

}

function transactionOrder($db, $user_id, $task_id, $type, $action, $was, $change, $became, $status, $created) {

	if ((float)$change != 0) {

		if ($type == 0 or $type == 3) $sign_change = "+";
		elseif ($type == 1 or $type == 6) $sign_change = "-";

		$sql = "INSERT INTO `transactions` SET `user_id`='{$user_id}',
												`task_id`='{$task_id}',
												`type`='{$type}',
												`action`=\"{$action}\",
												`add_funds`='{$change}',
												`was`='{$was}',
												`change`='$sign_change{$change}',
												`became`='{$became}',
												`status`='{$status}',
												`updated`='{$created}',
												`created`='{$created}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	}

}

function searchPartnerStatus($db, $partner_id, $method) {
	
	$sql = "SELECT `id`, `partner_id`, `cash`, `status` FROM `users` WHERE `id`='{$partner_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user = mysqli_fetch_assoc($query);

	if ($method == 1) {

		if ($user['status'] == 2 or $user['status'] == 3 or $user['status'] == 4) {

			$data['id'] = $user['id'];
			$data['partner_id'] = $user['partner_id'];
			$data['cash'] = $user['cash'];
			$data['status'] = $user['status'];

		} else {

			$data = searchPartnerStatus($db, $user['partner_id'], 1);

		}

	} elseif ($method == 2) {

		if ($user['status'] == 3 or $user['status'] == 4) {

			$data['id'] = $user['id'];
			$data['partner_id'] = $user['partner_id'];
			$data['cash'] = $user['cash'];
			$data['status'] = $user['status'];

		} else {

			$data = searchPartnerStatus($db, $user['partner_id'], 2);

		}

	} elseif ($method == 3) {

		if ($user['status'] == 4) {

			$data['id'] = $user['id'];
			$data['partner_id'] = $user['partner_id'];
			$data['cash'] = $user['cash'];
			$data['status'] = $user['status'];

		} else {

			$data = searchPartnerStatus($db, $user['partner_id'], 3);

		}

	}

	return $data;

}

?>