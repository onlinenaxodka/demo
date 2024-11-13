<?php

//Supervisor

//Own earnings

$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$orders_post_user_id}' AND `status`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$partners_managers_count = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$orders_post_user_id}' AND `status`=3";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$partners_supervisor_count = mysqli_num_rows($query);

if ($orders_completed_count >= 99999 and $partners_managers_count == 15 and $partners_supervisor_count == 1) {

	$sql = "UPDATE `users` SET `status`=4, `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

} else {

	$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_cash, $orders_post_income, $users_post_cash_sum, 2, $current_date);

//Structural accruals

$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$users_post_my = mysqli_fetch_assoc($query);

$users_post_my_cash = $users_post_my['cash'];
$users_post_my_cash_sum = $users_post_my_cash + $manager + $supervisor;

$sql = "UPDATE `users` SET `cash`='{$users_post_my_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

for ($i=0; $i < 2; $i++) {

	if ($i == 0) {
		$action = $action_structural_accruals . ' за достижение статуса Наставник';
		$users_post_my_cash_was = $users_post_my_cash;
		$users_post_my_cash_change = $manager;
		$users_post_my_cash_became = $users_post_my_cash_was + $manager;
	} elseif ($i == 1) {
		$action = $action_structural_accruals . ' за достижение статуса Супервайзер';
		$users_post_my_cash_was = $users_post_my_cash + $manager;
		$users_post_my_cash_change = $supervisor;
		$users_post_my_cash_became = $users_post_my_cash_was + $supervisor;
	}

	transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_my_cash_was, $users_post_my_cash_change, $users_post_my_cash_became, 2, $current_date);

}

$balance_director = searchPartnerStatus($db, $users_post_my['partner_id'], 3);

$user_director_id = $balance_director['id'];
$user_director_cash = $balance_director['cash'];
$user_director_cash_sum = $user_director_cash + $director;

$sql = "UPDATE `users` SET `cash`='{$user_director_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_director_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$action = $action_partner . ' за достижение статуса Директор';

transactionOrder($db, $user_director_id, $order_id, 3, $action, $user_director_cash, $director, $user_director_cash_sum, 2, $current_date);

?>