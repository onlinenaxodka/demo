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

		$_SESSION['cart_post'] = $_POST;

		if (!empty($_POST['add_funds'])) {

			$sum_add_funds = (isset($_POST['add_funds'])) ? mysqli_real_escape_string($db, $_POST['add_funds']) : '';
			$sum_add_funds = test_request($sum_add_funds);
			$sum_add_funds = intval($sum_add_funds);

			header('Location: /account/add_funds/?sum='.$sum_add_funds);
			exit;

		}

		$error = false;
		$error_message = '';

		$cart_goods = (isset($_POST['cart_goods'])) ? mysqli_real_escape_string($db, $_POST['cart_goods']) : '';
		$cart_goods = test_request($cart_goods);
		$cart_goods = intval($cart_goods);

		$remove_all_goods = (isset($_POST['remove_all_goods'])) ? mysqli_real_escape_string($db, $_POST['remove_all_goods']) : '';
		$remove_all_goods = test_request($remove_all_goods);
		$remove_all_goods = intval($remove_all_goods);

		$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
		$name = test_request($name);

		$surname = (isset($_POST['surname'])) ? mysqli_real_escape_string($db, $_POST['surname']) : '';
		$surname = test_request($surname);

		$middlename = (isset($_POST['middlename'])) ? mysqli_real_escape_string($db, $_POST['middlename']) : '';
		$middlename = test_request($middlename);

		if (!empty($name)) {

			if (strlen(utf8_decode($name)) < 2) {

				$error = true;
				$error_message .= 'Введите не меньше 2-х симфолов в поле Имя. ';

			}

		} else {

			$error = true;
			$error_message .= 'Поле Имя обязательное. ';
			
		}

		if (!empty($surname)) {

			if (strlen(utf8_decode($surname)) < 2) {

				$error = true;
				$error_message .= 'Введите не меньше 2-х симфолов в поле Фамилия. ';

			}

		} else {

			$error = true;
			$error_message .= 'Поле Фамилия обязательное. ';
			
		}

		if (!empty($middlename)) {

			if (strlen(utf8_decode($middlename)) < 2) {

				$error = true;
				$error_message .= 'Введите не меньше 2-х симфолов в поле Отчество. ';

			}

		}/* else {

			$error = true;
			$error_message .= 'Поле Отчество обязательное. ';
			
		}*/
		
		$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
		$phone = test_request($phone);

		if (!empty($phone)) {

			if (!preg_match("/^[+]38\s\([0-9]{3}\)\s[0-9]{3}\s[0-9]{4}$/", $phone)) {

				$error = true;
				$error_message .= 'Формат телефона неверный. ';

			}

		} else {

			$error = true;
			$error_message .= 'Поле Телефон обязательное. ';
			
		}

		$client['fio'] = $surname.' '.$name.' '.$middlename;
		$client['phone'] = $phone;

		$client = json_encode($client, JSON_UNESCAPED_UNICODE);
		$client = str_replace("'", "\'", $client);

		$delivery = (isset($_POST['delivery'])) ? mysqli_real_escape_string($db, $_POST['delivery']) : '';
		$delivery = test_request($delivery);
		$delivery = intval($delivery);

		$locality = (isset($_POST['locality'])) ? mysqli_real_escape_string($db, $_POST['locality']) : '';
		$locality = test_request($locality);

		$branch = (isset($_POST['branch'])) ? mysqli_real_escape_string($db, $_POST['branch']) : '';
		$branch = test_request($branch);

		$region = (isset($_POST['region'])) ? mysqli_real_escape_string($db, $_POST['region']) : '';
		$region = test_request($region);

		$district = (isset($_POST['district'])) ? mysqli_real_escape_string($db, $_POST['district']) : '';
		$district = test_request($district);

		$city = (isset($_POST['city'])) ? mysqli_real_escape_string($db, $_POST['city']) : '';
		$city = test_request($city);

		$address = (isset($_POST['address'])) ? mysqli_real_escape_string($db, $_POST['address']) : '';
		$address = test_request($address);

		$index = (isset($_POST['index'])) ? mysqli_real_escape_string($db, $_POST['index']) : '';
		$index = test_request($index);

		$street = (isset($_POST['street'])) ? mysqli_real_escape_string($db, $_POST['street']) : '';
		$street = test_request($street);

		$house = (isset($_POST['house'])) ? mysqli_real_escape_string($db, $_POST['house']) : '';
		$house = test_request($house);

		$flat = (isset($_POST['flat'])) ? mysqli_real_escape_string($db, $_POST['flat']) : '';
		$flat = test_request($flat);

		$pickup = (isset($_POST['pickup'])) ? mysqli_real_escape_string($db, $_POST['pickup']) : '';
		$pickup = test_request($pickup);

		if ($delivery == 1) {

			if (empty($locality)) {

				$error = true;
				$error_message .= 'Все поля адреса доставки обязательные. ';

			}

			$delivery_address['locality'] = $locality;
			
			if (!empty($branch)) $delivery_address['branch'] = $branch;

			if (!empty($street)) $delivery_address['street'] = 'ул. ' . $street;
			if (!empty($house)) $delivery_address['house'] = 'дом ' . $house;
			if (!empty($flat)) $delivery_address['flat'] = 'кв. ' . $flat;

		} elseif ($delivery == 2) {

			if (empty($region) or empty($district) or empty($city) or empty($address) or empty($index)) {

				$error = true;
				$error_message .= 'Все поля адреса доставки обязательные. ';

			}

			$delivery_address['region'] = $region;
			$delivery_address['district'] = $district;
			$delivery_address['city'] = $city;
			$delivery_address['address'] = $address;
			$delivery_address['index'] = $index;

		} elseif ($delivery == 3) {
			if (empty($pickup)) {
				$error = true;
				$error_message .= 'Укажите адрес самовывоза. ';
			}

			$delivery_address['locality'] = $pickup;
		}

		$delivery_address = json_encode($delivery_address, JSON_UNESCAPED_UNICODE);
		$delivery_address = str_replace("'", "\'", $delivery_address);

		$sum_goods_price = 0;
		$check_turbines = false;

		for ($i=0; $i < count($_POST['goods']); $i++) {

			$goods_id = (isset($_POST['goods'][$i])) ? mysqli_real_escape_string($db, $_POST['goods'][$i]) : '';
			$goods_id = test_request($goods_id);
			$goods_id = intval($goods_id);

			$availability = (isset($_POST['availability'][$i])) ? mysqli_real_escape_string($db, $_POST['availability'][$i]) : '';
			$availability = test_request($availability);
			$availability = intval($availability);

			$goods_price = (isset($_POST['goods_price'][$i])) ? mysqli_real_escape_string($db, $_POST['goods_price'][$i]) : '';
			$goods_price = test_request($goods_price);
			$goods_price = intval($goods_price);

			if ($goods_id == 4660 or $goods_id == 3080) $check_turbines = true;

			if ($goods_id > 0 and $availability > 0 and $goods_price > 0) {

				$sql_valid = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
				$query_valid = mysqli_query($db, $sql_valid) or die(mysqli_error());
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

				if ($user['p_rate'] > 0) {

					if ($goods_valid['price_agent'] > 0 and $goods_valid['price_agent'] < $goods_valid['price_purchase']) {

						$price_purchase = ceil($goods_valid['price_agent'] * $kurs_currency);

					}

					$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $user['p_rate']));

				}

				if ($goods_price >= $price_min) {

					$goods[$i]['id'] = $goods_id;
					$goods[$i]['user_id'] = $goods_valid['user_id'];
					$goods[$i]['name'] = $goods_valid['name']['ru'];
					$goods[$i]['availability'] = $availability;
					$goods[$i]['goods_price_agent'] = $price_agent;
					$goods[$i]['goods_price_agent_native'] = $goods_valid['price_agent'];
					$goods[$i]['goods_price_purchase'] = $price_purchase;
					$goods[$i]['goods_price_purchase_native'] = $goods_valid['price_purchase'];
					if ($user['p_rate'] > 0 and $goods_valid['price_agent'] > 0 and $goods_valid['price_agent'] < $goods_valid['price_purchase']) $goods[$i]['goods_price_purchase_native'] = $goods_valid['price_agent'];
					$goods[$i]['goods_price_recom'] = $price_sale;
					$goods[$i]['goods_price_recom_native'] = $goods_valid['price_sale'];
					$goods[$i]['goods_price'] = $goods_price;
					$goods[$i]['catalog_rate'] = $catalog_rate['rate'];
					if ($user['p_rate'] > 0) 
						$goods[$i]['catalog_rate'] = $user['p_rate'];
					$goods[$i]['currency_kurs'] = $kurs_currency;

					$sum_goods_price += $goods_price * $availability;
					$sum_income += ($goods_price - $price_min) * $availability;

				} else {

					$error = true;
					$error_message_cart_goods2 = 'Ваша цена на некоторых товарах ниже минимальной цены товара. ';

				}

			} else {

				$error = true;
				$error_message_cart_goods1 = 'Не корректные данные в одном из товаров. ';

			}

		}

		$error_message .= $error_message_cart_goods1;
		$error_message .= $error_message_cart_goods2;

		$goods = json_encode($goods, JSON_UNESCAPED_UNICODE);
		$goods = str_replace("'", "\'", $goods);

		$payment = (isset($_POST['payment'])) ? mysqli_real_escape_string($db, $_POST['payment']) : '';
		$payment = test_request($payment);
		$payment = intval($payment);

		$prepayment = (isset($_POST['prepayment'])) ? mysqli_real_escape_string($db, $_POST['prepayment']) : '';
		$prepayment = test_request($prepayment);
		$prepayment = intval($prepayment);

		if ($prepayment >= $sum_goods_price) {
			
			$payment = 3;
			$prepayment = 0;

		}

		//комісія банку 0.5% при наложці
		if ($payment == 1) {
			$sum_income = $sum_income - $sum_goods_price * 0.005;
		}

		$reserve_balance = 0;

		if ($payment > 0 and $payment < 4) {

			if ($payment == 1) {

				if ($user['admin'] != 1) {

					if ($sum_goods_price < 1000)
						$reserve_balance = 100;
					else
						$reserve_balance = $sum_goods_price * 0.1;

					if ($prepayment < $reserve_balance) {

						if ($user['cash'] < $reserve_balance) {

							$error = true;
							$error_message .= 'Выбирая способ оплаты "Наложенный платеж", у вас должно быть минимум '.$reserve_balance.' грн. на внутреннем <a href="/account/wallet/" class="alert-link">балансе</a> в качестве страховой суммы от возврата заказа. ';

						}

						$reserve_balance = $reserve_balance - $prepayment;

					} else {

						if ($user['cash'] < $prepayment) {

							$error = true;
							$error_message .= 'Выбирая способ оплаты "Наложенный платеж", у вас должно быть минимум '.$prepayment.' грн. на внутреннем <a href="/account/wallet/" class="alert-link">балансе</a> в качестве суммы предоплаты. ';

						}

						$reserve_balance = 0;

					}

				}

			} elseif ($payment == 3) {

				if ($sum_goods_price > $user['cash']) {

					$error = true;
					$error_message .= 'Выбирая способ оплаты "Внутренний баланс", у вас должно быть минимум '.$sum_goods_price.' грн. на внутреннем <a href="/account/wallet/" class="alert-link">балансе</a> для полной оплаты заказа. ';

				}

			}

		} else {

			$error = true;
			$error_message .= 'Способ оплаты указан неверно. ';

		}

		// if (empty($user['site'])) {

		// 	$error = true;
		// 	$error_message .= 'У вас не заполнен адрес вашего интернет магазина. Зайдите в <a href="/account/edit/">профиль</a> и обязательно его заполните иначе вы не сможете оформить заказ. ';

		// }

		/*$sql = "SELECT `id` FROM `users_shops` WHERE `user_id`='{$user_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) == 0) {

			$error = true;
			$error_message .= 'Что бы оформить заказ вам обязательно нужно указать хотя бы одно место продаж. Зайдите, пожалуйста, в <a href="/account/edit/#pills-places-tab">профиль</a> и обязательно укажите где вы продаете. ';

		}*/

		$comment = (isset($_POST['comment'])) ? mysqli_real_escape_string($db, $_POST['comment']) : '';
		$comment = str_replace("\r\n", "\\r\\n", $comment);
		$comment = test_request($comment);

		if ($cart_goods > 0) {

			if (isset($_SESSION['cart'])) {

				$tmp_cart_goods = array_keys($_SESSION['cart']['goods'], $cart_goods);

				unset($_SESSION['cart']['goods'][$tmp_cart_goods[0]]);
				unset($_SESSION['cart']['price'][$tmp_cart_goods[0]]);

				if (!empty($_SESSION['cart']['goods']) and !empty($_SESSION['cart']['price'])) {

					$variable_goods = $_SESSION['cart']['goods'];
					$variable_price = $_SESSION['cart']['price'];

					unset($_SESSION['cart']['goods']);
					unset($_SESSION['cart']['price']);

					foreach ($variable_goods as $value) $_SESSION['cart']['goods'][] += $value;
					foreach ($variable_price as $value) $_SESSION['cart']['price'][] += $value;

				} else {
					
					unset($_SESSION['cart']);

				}

				header('Location: /account/cart/');
				exit;
				
			}

		}

		if ($remove_all_goods == 1) {
			
			if (isset($_SESSION['cart'])) unset($_SESSION['cart']);

			header('Location: /account/cart/');
			exit;

		}

		if (!$error) {

			$sql = "INSERT INTO `orders` SET `user_id`='{$user_id}',
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

				$sql = "INSERT INTO `orders_messages` SET `user_id`='{$user_id}',
															`order_id`='{$order_id}',
															`type_user`=1,
															`message`=\"{$comment}\",
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

			}

			if ($payment == 1 or $payment == 3) {

				if ($payment == 1) {

					if ($reserve_balance > 0) {

						$transaction_order_payment['sum_payment'][] = $reserve_balance;
						$transaction_order_payment['action'][] = 'Резервирование страховой суммы заказа №'.$order_id;

					}

					if ($prepayment > 0) {

						$transaction_order_payment['sum_payment'][] = $prepayment;
						$transaction_order_payment['action'][] = 'Сумма предоплаты заказа №'.$order_id;

					}					

				} elseif ($payment == 3) {
					
					$transaction_order_payment['sum_payment'][] = $sum_goods_price;
					$transaction_order_payment['action'][] = 'Оплата полной стоимости заказа №'.$order_id;

				}

				for ($i=0; $i < count($transaction_order_payment['sum_payment']); $i++) {

					$sum_payment = $transaction_order_payment['sum_payment'][$i];
					$action = $transaction_order_payment['action'][$i];
					
					if ($sum_payment > 0) {

						$sql = "SELECT `cash` FROM `users` WHERE `id`='{$user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_cur_data = mysqli_fetch_assoc($query);

						$users_post_cash = $user_cur_data['cash'];
						$users_post_cash_sum = $users_post_cash - $sum_payment;

						$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						transactionOrder($db, $user_id, $order_id, 1, $action, $users_post_cash, $sum_payment, $users_post_cash_sum, 2, $current_date);

					}

				}

			}

			if (isset($_SESSION['cart'])) unset($_SESSION['cart']);

			$email = $email_for_notify;
			if ($check_turbines) {
				$turbine_info_client = '<p style=\'text-align:center;\'>'.$surname.' '.$name.' '.$middlename.' '.$phone.'</p>';
			} else {
				$turbine_info_client = '';
			}

			$subject = "У вас новый заказ №".$order_id." в ".$name_company;
			$message = "<h3 style='text-align:center;'>Обработайте заказ №".$order_id."<h3>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/orders/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Обработать заказ</a>
						<br>
						".$turbine_info_client."
						<p style='text-align:center;font-weight:normal;font-style:italic;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			//sendMail($email, $subject, $message, $from, $server_protocole);

			if (isset($_SESSION['cart_post'])) unset($_SESSION['cart_post']);

			header('Location: /account/orders/');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>'.$word_error.'!</strong> '.$error_message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

		}

	}

}

function transactionOrder($db, $user_id, $task_id, $type, $action, $was, $change, $became, $status, $created) {

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

?>