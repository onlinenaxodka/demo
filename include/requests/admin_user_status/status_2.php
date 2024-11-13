<?php

//Manager

//Own earnings

$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$orders_post_user_id}' AND `status`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$partners_managers_count = mysqli_num_rows($query);

if ($orders_completed_count >= 9999 and $partners_managers_count == 3) {

	$sql = "UPDATE `users` SET `status`=3, `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
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
$users_post_my_cash_sum = $users_post_my_cash + $manager;

$sql = "UPDATE `users` SET `cash`='{$users_post_my_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$action = $action_structural_accruals . ' за достижение статуса Наставник';

transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_my_cash, $manager, $users_post_my_cash_sum, 2, $current_date);

$balance_partners = searchPartnerStatus($db, $users_post_my['partner_id'], 2);

if ($balance_partners['status'] == 3) {

	$balance_director = searchPartnerStatus($db, $balance_partners['partner_id'], 3);

	for ($i=0; $i < 2; $i++) {

		if ($i == 0) {
			$action = $action_partner . ' за достижение статуса Супервайзер';
			$users_structure_id = $balance_partners['id'];
			$users_structure_cash_was = $balance_partners['cash'];
			$users_structure_cash_change = $supervisor;
			$users_structure_cash_became = $users_structure_cash_was + $supervisor;
		} elseif ($i == 1) {
			$action = $action_partner . ' за достижение статуса Директор';
			$users_structure_id = $balance_director['id'];
			$users_structure_cash_was = $balance_director['cash'];
			$users_structure_cash_change = $director;
			$users_structure_cash_became = $users_structure_cash_was + $director;
		}

		$sql = "UPDATE `users` SET `cash`='{$users_structure_cash_became}', `updated`='{$current_date}' WHERE `id`='{$users_structure_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		transactionOrder($db, $users_structure_id, $order_id, 3, $action, $users_structure_cash_was, $users_structure_cash_change, $users_structure_cash_became, 2, $current_date);

	}

} elseif ($balance_partners['status'] == 4) {

	$user_director_id = $balance_partners['id'];
	$user_director_cash = $balance_partners['cash'];
	$user_director_cash_sum = $user_director_cash + $supervisor + $director;

	$sql = "UPDATE `users` SET `cash`='{$user_director_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_director_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	for ($i=0; $i < 2; $i++) { 
		
		if ($i == 0) {
			$action = $action_partner . ' за достижение статуса Супервайзер';
			$users_director_cash_was = $user_director_cash;
			$users_director_cash_change = $supervisor;
			$users_director_cash_became = $users_director_cash_was + $supervisor;
		} elseif ($i == 1) {
			$action = $action_partner . ' за достижение статуса Директор';
			$users_director_cash_was = $user_director_cash + $supervisor;
			$users_director_cash_change = $director;
			$users_director_cash_became = $users_director_cash_was + $director;
		}

		transactionOrder($db, $user_director_id, $order_id, 3, $action, $users_director_cash_was, $users_director_cash_change, $users_director_cash_became, 2, $current_date);

	}

}

?>