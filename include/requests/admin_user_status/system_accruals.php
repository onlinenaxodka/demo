<?php

//System accruals

//Fond

/*$action_fond = 'Зачисление прибыли в фонд с заказа №'.$order_id;

$sql = "INSERT INTO `transactions_fond` SET `user_id`='{$orders_post_user_id}',
											`type`=0,
											`action`='{$action_fond}',
											`amount`='{$fond}',
											`created`='{$current_date}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

if ($fond != 0) {

	$sql = "SELECT * FROM `users` WHERE `id`=27";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_fund = mysqli_fetch_assoc($query);

	$user_fund_cash = $user_fund['cash'];
	$user_fund_cash_sum = $user_fund_cash + $fond;

	$sql = "UPDATE `users` SET `cash`='{$user_fund_cash_sum}', `updated`='{$current_date}' WHERE `id`=27";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$action = 'Зачисление прибыли в фонд с заказа №'.$order_id;

	transactionOrder($db, 27, $order_id, 3, $action, $user_fund_cash, $fond, $user_fund_cash_sum, 2, $current_date);

}

//Admins

$users_admins = [
	[2, $admin_r, 'Админ R'], 
	[4, $admin_z, 'Админ E'], 
	[9764, $admin_t, 'Админ A'], 
	[22, $admin_d, 'Призовой фонд'],
	[12997, $admin_o, 'Админ O']
];

for ($i=0; $i < count($users_admins); $i++) {

	$user_admin_id = $users_admins[$i][0];
	$user_admin_income = $users_admins[$i][1];
	$user_admin_name = $users_admins[$i][2];

	if ($user_admin_income != 0) {

		$sql = "SELECT * FROM `users` WHERE `id`='{$user_admin_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$user_admin = mysqli_fetch_assoc($query);

		$user_admin_cash = $user_admin['cash'];
		$user_admin_cash_sum = $user_admin_cash + $user_admin_income;

		$sql = "UPDATE `users` SET `cash`='{$user_admin_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_admin_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$action = 'Роялти прибыли '.$user_admin_name.' с заказа №'.$order_id;

		transactionOrder($db, $user_admin_id, $order_id, 3, $action, $user_admin_cash, $user_admin_income, $user_admin_cash_sum, 2, $current_date);

	}

}

//Admin Online Naxodka or Our shop

$order_admin_user_id = 1;
if ($orders_post_user_id == 7037) $order_admin_user_id = 7037;

if ($admin_on_amount_left != 0) {

	$sql = "SELECT * FROM `users` WHERE `id`='{$order_admin_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_admin = mysqli_fetch_assoc($query);

	$user_admin_cash = $user_admin['cash'];
	$user_admin_cash_sum = $user_admin_cash + $admin_on_amount_left;

	$sql = "UPDATE `users` SET `cash`='{$user_admin_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$order_admin_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$action = 'Остаточная сумма с заказа №'.$order_id;

	transactionOrder($db, $order_admin_user_id, $order_id, 3, $action, $user_admin_cash, $admin_on_amount_left, $user_admin_cash_sum, 2, $current_date);

}

if ($admin_on_rate_dif != 0) {

	$sql = "SELECT * FROM `users` WHERE `id`='{$order_admin_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_admin = mysqli_fetch_assoc($query);

	$user_admin_cash = $user_admin['cash'];
	$user_admin_cash_sum = $user_admin_cash + $admin_on_rate_dif;

	$sql = "UPDATE `users` SET `cash`='{$user_admin_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$order_admin_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$action = 'Разница прибыли процентной ставки '.implode(', ', $admin_on_rates).' с заказа №'.$order_id;

	transactionOrder($db, $order_admin_user_id, $order_id, 3, $action, $user_admin_cash, $admin_on_rate_dif, $user_admin_cash_sum, 2, $current_date);

}

//Agent

if (count($agent_data) > 0) {

	foreach ($agent_data as $agent_user_data) {

		$agent_user_id = $agent_user_data[0];
		$agent_user_margin = $agent_user_data[1];

		if ($agent_user_margin != 0) {

			$sql = "SELECT * FROM `users` WHERE `id`='{$agent_user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$user_agent = mysqli_fetch_assoc($query);

			$user_agent_cash = $user_agent['cash'];
			$user_agent_cash_sum = $user_agent_cash + $agent_user_margin;

			$sql = "UPDATE `users` SET `cash`='{$user_agent_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$agent_user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$action = 'Прибыль с продажи товара за привлечение поставщика';

			transactionOrder($db, $agent_user_id, $order_id, 3, $action, $user_agent_cash, $agent_user_margin, $user_agent_cash_sum, 2, $current_date);

		}

	}

}

//Marketer

if ($marketer != 0) {

	$sql = "SELECT * FROM `users` WHERE `id`=6264";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_marketer = mysqli_fetch_assoc($query);

	$user_marketer_cash = $user_marketer['cash'];
	$user_marketer_cash_sum = $user_marketer_cash + $marketer;

	$sql = "UPDATE `users` SET `cash`='{$user_marketer_cash_sum}', `updated`='{$current_date}' WHERE `id`=6264";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$action = 'Прибыль с заказа №'.$order_id.' магазина';

	if ($count_users_gtm > 0) $action = 'Прибыль с заказа №'.$order_id.' дропшиппера';

	transactionOrder($db, 6264, $order_id, 3, $action, $user_marketer_cash, $marketer, $user_marketer_cash_sum, 2, $current_date);

}

//Our_shop

if ($our_shop != 0) {

	$sql = "SELECT * FROM `users` WHERE `id`=7037";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_our_shop = mysqli_fetch_assoc($query);

	$user_our_shop_cash = $user_our_shop['cash'];
	$user_our_shop_cash_sum = $user_our_shop_cash + $our_shop;

	$sql = "UPDATE `users` SET `cash`='{$user_our_shop_cash_sum}', `updated`='{$current_date}' WHERE `id`=7037";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$action = 'Прибыль с собственного заказа №'.$order_id.' - магазин';

	transactionOrder($db, 7037, $order_id, 3, $action, $user_our_shop_cash, $our_shop, $user_our_shop_cash_sum, 2, $current_date);

}

?>