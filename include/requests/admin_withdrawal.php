<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$status_confirm = (isset($_POST['status_confirm'])) ? mysqli_real_escape_string($db, $_POST['status_confirm']) : '';
		$status_confirm = test_request($status_confirm);
		$status_confirm = intval($status_confirm);

		if ($status_confirm > 0) {

			$sql = "UPDATE `transactions` SET `status`=2, `updated`='{$current_date}' WHERE `id`='{$status_confirm}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
													`table_name`='transactions',
													`id_row`='{$status_confirm}',
													`action`='Подтвердил заявку на вывод средств',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$sql = "SELECT `user_id` FROM `transactions` WHERE `id`='{$status_confirm}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$transactions_user = mysqli_fetch_assoc($query);

			$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$transactions_user['user_id']}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$for_user = mysqli_fetch_assoc($query);

			$email = $for_user['mail'];
			$subject = "Ваш вывод средств в ".$name_company." был произведен";
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
						<p style='text-align:center'>Ваша заявка №".$status_confirm." на вывод средств была успешно обработана и вам переведены средства на вашу банковскую карту, указанную в профиле.</p>
						<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
						<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата операции: ".date('d.m.Y H:i')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			header('Location: /admin/withdrawal/');
			exit;

		}

	}

}

?>