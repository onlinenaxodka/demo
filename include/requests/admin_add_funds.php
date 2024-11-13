<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$status_confirm = (isset($_POST['status_confirm'])) ? mysqli_real_escape_string($db, $_POST['status_confirm']) : '';
		$status_confirm = test_request($status_confirm);
		$status_confirm = intval($status_confirm);

		$status_cancel = (isset($_POST['status_cancel'])) ? mysqli_real_escape_string($db, $_POST['status_cancel']) : '';
		$status_cancel = test_request($status_cancel);
		$status_cancel = intval($status_cancel);

		$cash = (isset($_POST['cash'])) ? mysqli_real_escape_string($db, $_POST['cash']) : '';
		$cash = test_request($cash);
		$cash = floatval($cash);
		$cash = number_format($cash, 2, '.', '');

		if ($status_confirm > 0) {

			if ($cash > 0) {

				$sql = "SELECT `user_id` FROM `transactions` WHERE `id`='{$status_confirm}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$add_funds = mysqli_fetch_assoc($query);
				$add_funds_user_id = $add_funds['user_id'];

				$sql = "SELECT `name`, `mail`, `cash` FROM `users` WHERE `id`='{$add_funds_user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$user_add_funds = mysqli_fetch_assoc($query);

				$was = $user_add_funds['cash'];
				$became = $user_add_funds['cash'] + $cash;

				$sql = "UPDATE `transactions` SET `was`='{$was}', `change`='+{$cash}', `became`='{$became}', `status`=2, `updated`='{$current_date}' WHERE `id`='{$status_confirm}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$sql = "UPDATE `users` SET `cash`='{$became}', `updated`='{$current_date}' WHERE `id`='{$add_funds_user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$id_row = $status_confirm;
				$for_log_action = 'Подтвердил заявку на пополнение баланса';

				$email = $user_add_funds['mail'];
				$subject = "Ваше пополнение баланса в ".$name_company." было произведено";
				$message = "<h1 style='text-align:center'>Здравствуйте, ".$user_add_funds['name']."!</h1>
							<p style='text-align:center'>Ваша заявка №".$status_confirm." на пополнение баланса была успешно обработана и вам начислены средства на ваш баланс в ".$name_company.".</p>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата операции: ".date('d.m.Y H:i')."</p>";
				$from['name_company'] = $name_company;
				$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

				sendMail($email, $subject, $message, $from, $server_protocole);

			}

		}

		if ($status_cancel > 0) {

			$sql = "UPDATE `transactions` SET `status`=0, `updated`='{$current_date}' WHERE `id`='{$status_cancel}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$id_row = $status_cancel;
			$for_log_action = 'Отменил заявку на пополнение баланса';

			$sql = "SELECT `user_id` FROM `transactions` WHERE `id`='{$status_cancel}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$transactions_user = mysqli_fetch_assoc($query);

			$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$transactions_user['user_id']}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$for_user = mysqli_fetch_assoc($query);

			$email = $for_user['mail'];
			$subject = "Ваше пополнение баланса в ".$name_company." было отменено";
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
							<p style='text-align:center'>Ваша заявка №".$status_cancel." на пополнение баланса была отменена по причине не найденого перевода средств на наши банковские реквизиты и вам не начислены средства на ваш баланс в ".$name_company.".</p>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата операции: ".date('d.m.Y H:i')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

		}

		$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
											`table_name`='transactions',
											`id_row`='{$id_row}',
											`action`='{$for_log_action}',
											`created`='{$current_date}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		header('Location: /admin/add_funds/');
		exit;

	}

}

?>