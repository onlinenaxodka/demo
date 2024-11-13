<?php

//Dropshipper

//Own earnings

if ($orders_completed_count == 999) {

	$sql = "UPDATE `users` SET `status`=2, `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

} else {

	$sql = "UPDATE `users` SET `cash`='{$users_post_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$orders_post_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

transactionOrder($db, $orders_post_user_id, $order_id, 3, $action, $users_post_cash, $orders_post_income, $users_post_cash_sum, 2, $current_date);

//Structural accruals

$balance_partners = searchPartnerStatus($db, $users_post['partner_id'], 1);

if ($balance_partners['status'] == 2) {

	$balance_partners_t = searchPartnerStatus($db, $balance_partners['partner_id'], 2);

	if ($balance_partners_t['status'] == 3) {

		$balance_director = searchPartnerStatus($db, $balance_partners_t['partner_id'], 3);

		for ($i=0; $i < 3; $i++) {

			if ($i == 0) {
				$action = $action_partner . ' за достижение статуса Наставник';
				$users_structure_id = $balance_partners['id'];
				$users_structure_cash_was = $balance_partners['cash'];
				$users_structure_cash_change = $manager;
				$users_structure_cash_became = $users_structure_cash_was + $manager;
			} elseif ($i == 1) {
				$action = $action_partner . ' за достижение статуса Супервайзер';
				$users_structure_id = $balance_partners_t['id'];
				$users_structure_cash_was = $balance_partners_t['cash'];
				$users_structure_cash_change = $supervisor;
				$users_structure_cash_became = $users_structure_cash_was + $supervisor;
			} elseif ($i == 2) {
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

	} elseif ($balance_partners_t['status'] == 4) {

		$user_manager_id = $balance_partners['id'];
		$user_manager_cash = $balance_partners['cash'];
		$user_manager_cash_sum = $user_manager_cash + $manager;

		$user_director_id = $balance_partners_t['id'];
		$user_director_cash = $balance_partners_t['cash'];
		$user_director_cash_sum = $user_director_cash + $supervisor + $director;

		$sql = "UPDATE `users` SET `cash`='{$user_manager_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_manager_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `users` SET `cash`='{$user_director_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_director_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		for ($i=0; $i < 3; $i++) { 
			
			if ($i == 0) {
				$action = $action_partner . ' за достижение статуса Наставник';
				$users_structure_id = $user_manager_id;
				$users_structure_cash_was = $user_manager_cash;
				$users_structure_cash_change = $manager;
				$users_structure_cash_became = $users_structure_cash_was + $manager;
			} elseif ($i == 1) {
				$action = $action_partner . ' за достижение статуса Супервайзер';
				$users_structure_id = $user_director_id;
				$users_structure_cash_was = $user_director_cash;
				$users_structure_cash_change = $supervisor;
				$users_structure_cash_became = $users_structure_cash_was + $supervisor;
			} elseif ($i == 2) {
				$action = $action_partner . ' за достижение статуса Директор';
				$users_structure_id = $user_director_id;
				$users_structure_cash_was = $user_director_cash + $supervisor;
				$users_structure_cash_change = $director;
				$users_structure_cash_became = $users_structure_cash_was + $director;
			}

			transactionOrder($db, $users_structure_id, $order_id, 3, $action, $users_structure_cash_was, $users_structure_cash_change, $users_structure_cash_became, 2, $current_date);

		}

	}

} elseif ($balance_partners['status'] == 3) {

	$balance_director = searchPartnerStatus($db, $balance_partners['partner_id'], 3);

	for ($i=0; $i < 3; $i++) {

		if ($i == 0) {
			$action = $action_partner . ' за достижение статуса Наставник';
			$users_structure_id = $balance_partners['id'];
			$users_structure_cash_was = $balance_partners['cash'];
			$users_structure_cash_change = $manager;
			$users_structure_cash_became = $users_structure_cash_was + $manager;
		} elseif ($i == 1) {
			$action = $action_partner . ' за достижение статуса Супервайзер';
			$users_structure_id = $balance_partners['id'];
			$users_structure_cash_was = $balance_partners['cash'] + $manager;
			$users_structure_cash_change = $supervisor;
			$users_structure_cash_became = $users_structure_cash_was + $supervisor;
		} elseif ($i == 2) {
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
	$user_director_cash_sum = $user_director_cash + $manager + $supervisor + $director;

	$sql = "UPDATE `users` SET `cash`='{$user_director_cash_sum}', `updated`='{$current_date}' WHERE `id`='{$user_director_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	for ($i=0; $i < 3; $i++) { 
		
		if ($i == 0) {
			$action = $action_partner . ' за достижение статуса Наставник';
			$users_director_cash_was = $user_director_cash;
			$users_director_cash_change = $manager;
			$users_director_cash_became = $users_director_cash_was + $manager;
		} elseif ($i == 1) {
			$action = $action_partner . ' за достижение статуса Супервайзер';
			$users_director_cash_was = $user_director_cash + $manager;
			$users_director_cash_change = $supervisor;
			$users_director_cash_became = $users_director_cash_was + $supervisor;
		} elseif ($i == 2) {
			$action = $action_partner . ' за достижение статуса Директор';
			$users_director_cash_was = $user_director_cash + $manager + $supervisor;
			$users_director_cash_change = $director;
			$users_director_cash_became = $users_director_cash_was + $director;
		}

		transactionOrder($db, $user_director_id, $order_id, 3, $action, $users_director_cash_was, $users_director_cash_change, $users_director_cash_became, 2, $current_date);

	}

}

?>