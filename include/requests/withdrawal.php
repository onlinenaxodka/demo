<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$errort = '';

		$sum = (isset($_POST['sum'])) ? mysqli_real_escape_string($db, $_POST['sum']) : '';
		$sum = test_request($sum);
		$sum = floatval($sum);
		$sum = number_format($sum, 2, '.', '');

		if ($user['admin'] != 1) {

			if ($sum <= 0) {

				$error = true;
				$errort .= 'Вы не ввели сумму для выплаты. ';

			} else {

				if ($sum < 0.01) {

					$error = true;
					$errort .= 'Минимальная сумма вывода - 0.01 грн. ';

				}

			}

			if ($sum > $user['cash']) {

				$error = true;
				$errort .= 'Сумма для выплаты превышает сумму у вас на <a href="/account/wallet/" class="alert-link">балансе</a>. Укажите меньшую сумму.';

			}

		}

		if (empty($user['card'])) {

			$error = true;
			$errort .= 'У вас в <a href="/account/edit/" class="alert-link">профиле</a> не указана ни одина платежная система.';

		}

		if (!$error) {

			$user_cash = $user['cash'];
			$minus_user_cash = $user_cash - $sum;

			$sql = "UPDATE `users` SET `cash`='{$minus_user_cash}' WHERE `id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$sql = "INSERT INTO `transactions` SET `user_id`='{$user_id}',
														`type`=2,
														`action`='Вывод средств',
														`add_funds`='{$sum}',
														`was`='{$user_cash}',
														`change`='-{$sum}',
														`became`='{$minus_user_cash}',
														`status`=1,
														`updated`='{$current_date}',
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$new_withdrawal_id = mysqli_insert_id($db);

			$email = $email_for_notify;
			$subject = "Новая заявка №".$new_withdrawal_id." на вывод средств от пользователя в ".$name_company;
			$message = "<p style='text-align:center;'>Пользователь ".$user['name']." ".$user['surname']." заказал выплату средств со своего баланса. Номер завки - №".$new_withdrawal_id.".</p>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/withdrawal/' style='display:block;max-width:300px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить вывод средств</a>
						<p style='text-align:center;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			$alert_message = '<div class="alert alert-success" role="alert">
								<strong>Поздравляем!</strong> Вы успешно заказали выплату, ваша заявка будет обработана в течении 48 часов, согласно регламенту. Просмотреть статус заявки вы можете в <a href="/account/transactions/" class="alert-link">истории операций</a>.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

		} else {

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