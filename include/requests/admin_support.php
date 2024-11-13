<?php

$current_date_minus3days = date('Y-m-d H:i:s', strtotime('-3 days'));

$selected_active1 = 'selected';
$selected_active2 = '';
$selected_active3 = '';
$selected_active4 = '';

$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=0 AND `answer`=0 ORDER BY `updated` ASC";
$query_philter = mysqli_query($db, $sql_philter) or die(mysqli_error());

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (empty($_POST['subject_id'])) {

			if (!empty($_POST['philter'])) {

				switch ($_POST['philter']) {
				 	case 'open_and_no':
				 		$selected_active1 = 'selected';
				 		$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=0 AND `answer`=0 ORDER BY `updated` ASC";
				 		break;
				 	case 'open_and_yes':
				 		$selected_active2 = 'selected';
				 		$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=0 AND `answer`=1 ORDER BY `updated` ASC";
				 		break;
				 	case 'for_close':
				 		$selected_active3 = 'selected';
				 		$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=0 AND `answer`=1 AND `updated`<'{$current_date_minus3days}' ORDER BY `updated` ASC";
				 		break;
				 	case 'close':
				 		$selected_active4 = 'selected';
				 		$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=1 AND `answer`=1 ORDER BY `updated` ASC";
				 		break;
				 }
				 $query_philter = mysqli_query($db, $sql_philter) or die(mysqli_error());

			}

			if (!empty($_POST['status'])) {

				$status_post = $_POST['status'];

				if ($status_post > 0) {

					for ($i = 0; $i < count($status_post); $i++) {

						$support_subjects_id = intval($status_post[$i]);

						if ($support_subjects_id > 0) {

							$sql = "UPDATE `support_subjects` SET `status`=1, `updated`='{$current_date}' WHERE `id`='{$support_subjects_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

							$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_subjects',
														`id_row`='{$support_subjects_id}',
														`action`='Поставил статус закрыто',
														`created`='{$current_date}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

						}

					}

				}

			}

			if (!empty($_POST['answer'])) {

				$answer_post = $_POST['answer'];

				if ($answer_post > 0) {

					for ($i = 0; $i < count($answer_post); $i++) {

						$support_subjects_id = intval($answer_post[$i]);

						if ($support_subjects_id > 0) {

							$sql = "UPDATE `support_subjects` SET `answer`=1, `updated`='{$current_date}' WHERE `id`='{$support_subjects_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

							$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_subjects',
														`id_row`='{$support_subjects_id}',
														`action`='Дал ответ на сообщение в теме',
														`created`='{$current_date}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

						}

					}

				}
				
			}

			if (!empty($_POST['user'])) {

				$mess_user_id = (isset($_POST['user'])) ? mysqli_real_escape_string($db, $_POST['user']) : '';
				$mess_user_id = test_request($mess_user_id);

				$subject = (isset($_POST['subject'])) ? mysqli_real_escape_string($db, $_POST['subject']) : '';
				$subject = test_request($subject);

				$message = (isset($_POST['message'])) ? mysqli_real_escape_string($db, $_POST['message']) : '';
				$message = str_replace('\r\n', '\\\r\\\n', $message);
				$message = test_request($message);

				$sql = "INSERT INTO `support_subjects` SET `user_id`='{$mess_user_id}',
															`subject`='{$subject}',
															`answer`=1,
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$sql = "SELECT `id` FROM `support_subjects` WHERE `user_id`='{$mess_user_id}' ORDER BY `created` DESC LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$support_subject = mysqli_fetch_assoc($query);
				$support_subject_id = $support_subject['id'];

				$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_subjects',
														`id_row`='{$support_subject_id}',
														`action`='Создал новую тему',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$sql = "INSERT INTO `support_messages` SET `subject_id`='{$support_subject_id}',
															`type_user`=0,
															`message`=\"{$message}\",
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$support_messages_id = mysqli_insert_id($db);

				$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_messages',
														`id_row`='{$support_messages_id}',
														`action`='Создал новое сообщение в новой теме',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$mess_user_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$support_user = mysqli_fetch_assoc($query);

				$email = $support_user['mail'];
				$subject = "Новое сообщение от поддержки ".$name_company;
				$message = "<h1 style='text-align:center'>Здравствуйте, ".$support_user['name']."!</h1>
							<p style='text-align:center'>У вас есть одно новое сообщение от поддержки ".$name_company."</p>
							<p style='text-align:center'>Войдите, пожалуйста, в свой <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/'>аккаунт</a> и дайте нам ответ.</p>
							<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Войти</a>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Прислано в: ".date('H:i d.m.Y')."</p>";
				$from['name_company'] = $name_company;
				$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

				if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Сообщение успешно отправлено.</div>';

				} else {

					$error = true;
					$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

				}

			}
			
		} else {

			$message = (isset($_POST['message'])) ? mysqli_real_escape_string($db, $_POST['message']) : '';
			$message = str_replace('\r\n', '\\\r\\\n', $message);
			$message = test_request($message);

			$subject_id = test_request($_POST['subject_id']);
			$subject_id = intval($subject_id);

			$sql = "INSERT INTO `support_messages` SET `subject_id`='{$subject_id}',
														`message`=\"{$message}\",
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$support_messages_id = mysqli_insert_id($db);

			$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_messages',
														`id_row`='{$support_messages_id}',
														`action`='Создал новое сообщение в теме',
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$sql = "UPDATE `support_subjects` SET `status`=0, `answer`=1, `updated`='{$current_date}' WHERE `id`='{$subject_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='support_subjects',
														`id_row`='{$subject_id}',
														`action`='Создал новое сообщение в теме',
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$sql_philter = "SELECT * FROM `support_subjects` WHERE `status`=0 AND `answer`=0 ORDER BY `updated` ASC";
			$query_philter = mysqli_query($db, $sql_philter) or die(mysqli_error());

			$sql_user = "SELECT `user_id` FROM `support_subjects` WHERE `id`='{$subject_id}' LIMIT 1";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query_user);

			$sql_user = "SELECT `name`,`mail` FROM `users` WHERE `id`=".$user['user_id']." LIMIT 1";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query_user);

			$email = $user['mail'];
			$subject = "Новый ответ от поддержки ".$name_company." на ваш тикет №".$subject_id;
			$message = "<h1 style='text-align:center'>Здравствуйте, ".$user['name']."!</h1>
						<p style='text-align:center'>Новый ответ на ваш тикет <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/support/?ticket=".$subject_id."' >№".$subject_id."</a>.</p>
						<p style='text-align:center'>Перейдите по ссылке, чтобы прочитать ответ.</p>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Перейти</a>
						<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
						<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Прислано в: ".date('H:i d.m.Y')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

				$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Сообщение успешно отправлено.</div>';

			} else {

				$error = true;
				$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

			}

		}

	}

}

?>