<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$errort = '';

		$sum = (isset($_POST['sum'])) ? mysqli_real_escape_string($db, $_POST['sum']) : '';
		$sum = test_request($sum);
		$sum = floatval($sum);
		$sum = number_format($sum, 2, '.', '');

		$cancel = (isset($_POST['cancel'])) ? mysqli_real_escape_string($db, $_POST['cancel']) : '';
		$cancel = test_request($cancel);
		$cancel = intval($cancel);

		$confirm = (isset($_POST['confirm'])) ? mysqli_real_escape_string($db, $_POST['confirm']) : '';
		$confirm = test_request($confirm);
		$confirm = intval($confirm);

		if (isset($_POST['sum'])) {

			if ($sum <= 0) {

				$error = true;
				$errort .= 'Вы не ввели сумму пополнения <a href="/account/wallet/" class="alert-link">баланса</a>. ';

			} else {

				if ($sum < 0.01) {

					$error = true;
					$errort .= 'Минимальная сумма пополнения - 0.01 грн. ';

				}

			}

			$sql = "SELECT * FROM `transactions` WHERE `user_id`='{$user_id}' AND `type`=0 AND `status`=1 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$transactions = mysqli_fetch_assoc($query);
			
			if (mysqli_num_rows($query) == 1) {

				if ($transactions['updated'] == $transactions['created']) {

					$error = true;
					$errort .= 'Сначала оплатите и дождитесь подтверждения оплаты вашей заявки оператором. Потом можете создавать новые заявки. ';

				}

			}

			$action = 'Пополнение баланса';

			if (!$error) {

				$user_cash = $user['cash'];

				$sql = "INSERT INTO `transactions` SET `user_id`='{$user_id}',
														`type`=0,
														`action`='{$action}',
														`add_funds`='{$sum}',
														`was`='{$user_cash}',
														`change`='+0.00',
														`became`='{$user_cash}',
														`status`=1,
														`updated`='{$current_date}',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$orderid = mysqli_insert_id($db);

				header('Location: /account/add_funds/');
				exit;

			}

		}

		if (isset($_POST['cancel'])) {

			if ($cancel == 0) {

				$error = true;
				$errort .= 'Техническая ошибка. ';

			}

			$sql = "SELECT * FROM `transactions` WHERE `user_id`='{$user_id}' AND `type`=0 AND `status`=1 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$transactions = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) == 1) {

				if ($transactions['updated'] != $transactions['created']) {

					$error = true;
					$errort .= 'Статус заявки - подтверждение оплаты уже принято. Дождитесь подтверждения оплаты вашей заявки оператором. Потом можете создавать новые заявки. ';

				}

			}

			if (!$error) {

				$sql = "UPDATE `transactions` SET `updated`='{$current_date}', `status`=0 WHERE `id`='{$cancel}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				header('Location: /account/add_funds/');
				exit;

			}

		}

		if (isset($_POST['confirm'])) {

			if ($confirm == 0) {

				$error = true;
				$errort .= 'Техническая ошибка. ';

			}

			$sql = "SELECT * FROM `transactions` WHERE `user_id`='{$user_id}' AND `type`=0 AND `status`=1 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$transactions = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) == 1) {

				if ($transactions['updated'] != $transactions['created']) {

					$error = true;
					$errort .= 'Статус заявки - подтверждение оплаты уже принято. Дождитесь подтверждения оплаты вашей заявки оператором. Потом можете создавать новые заявки. ';

				}

			}

			if (!$error) {

				$sql = "UPDATE `transactions` SET `updated`='{$current_date}' WHERE `id`='{$confirm}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$email = $email_for_notify;
				$subject = "Новое подтверждение пополнение баланса - заявка №".$confirm." от пользователя в ".$name_company;
				$message = "<p style='text-align:center;'>Пользователь ".$user['name']." ".$user['surname']." подтвердил, что оплатил заявку на пополнение баланса. Номер завки - №".$confirm.".</p>
							<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/add_funds/' style='display:block;max-width:300px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить пополнение баланса</a>
							<p style='text-align:center;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
				$from['name_company'] = $name_company;
				$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

				sendMail($email, $subject, $message, $from, $server_protocole);

				header('Location: /account/add_funds/');
				exit;

			}

		}

		if ($error) {

			$alert_message = '<div class="alert alert-danger" role="alert">
								<strong>Ошибка!</strong> ' . $errort . '
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

		}

	}

}

?>