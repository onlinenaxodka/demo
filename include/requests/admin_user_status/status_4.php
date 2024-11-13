<?php

//Director

//Own earnings

$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_cash, $orders_post_income, $users_post_cash_sum, 2, $current_date);

//Structural accruals

$sql = "SELECT * FROM `users` WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$users_post_my = mysqli_fetch_assoc($query);

$users_post_my_cash = $users_post_my['cash'];
$users_post_my_cash_sum = $users_post_my_cash + $manager + $supervisor + $director;

$sql = "UPDATE `users` SET `cash`='{$users_post_my_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

for ($i=0; $i < 3; $i++) {

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
	} elseif ($i == 2) {
		$action = $action_structural_accruals . ' за достижение статуса Директор';
		$users_post_my_cash_was = $users_post_my_cash + $manager + $supervisor;
		$users_post_my_cash_change = $director;
		$users_post_my_cash_became = $users_post_my_cash_was + $director;
	}

	transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_my_cash_was, $users_post_my_cash_change, $users_post_my_cash_became, 2, $current_date);

}

?>