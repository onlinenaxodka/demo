<?php

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		$type_operation = (isset($_POST['type_operation'])) ? mysqli_real_escape_string($db, $_POST['type_operation']) : '';
		$type_operation = test_request($type_operation);

		$homework_id = (isset($_POST['homework_id'])) ? mysqli_real_escape_string($db, $_POST['homework_id']) : '';
		$homework_id = test_request($homework_id);
		$homework_id = intval($homework_id);

		if ($type_operation == 'edit' or $type_operation == 'delete') {

			if ($homework_id > 0) {

				$sql = "SELECT `id` FROM `school_homework` WHERE `id`='{$homework_id}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if (mysqli_num_rows($query) == 0) {

					$error = true;
					$error_message .= 'Этого домашнего задания нет. Обратитесь в поддержку.<br>';

				}

			} else {

				$error = true;
				$error_message .= 'Техническая ошибка домашнего задания. Обратитесь в поддержку.<br>';

			}

		}

		$goods_id = (isset($_POST['goods_id'])) ? mysqli_real_escape_string($db, $_POST['goods_id']) : '';
		$goods_id = test_request($goods_id);
		$goods_id = intval($goods_id);

		if ($goods_id > 0) {

			if ($type_operation == 'add') {

				$sql = "SELECT `id` FROM `school_homework` WHERE `user_id`='{$user_id}' AND `goods_id`='{$goods_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$school_homework_err = mysqli_fetch_assoc($query);

				if (mysqli_num_rows($query) > 0) {

					$error = true;
					$error_message .= 'Вы уже добавили домашнее задание с этим товаром. Если у вас есть еще ссылки на обьявления этого товара, то просто добавьте их уже в созданное домашнее задание с кодом - <b>'.$school_homework_err['id'].'</b>.<br>';

				}

			}

		} else {

			if ($type_operation != 'delete') {

				$error = true;
				$error_message .= 'Товар обязательно нужно выбрать.<br>';

			}

		}

		$url_ad = array();

		if (!empty($_POST['url_ad'])) {

			for ($i = 0; $i < count($_POST['url_ad']); $i++) {

				$url_ad_tmp = (isset($_POST['url_ad'][$i])) ? mysqli_real_escape_string($db, $_POST['url_ad'][$i]) : '';
				$url_ad_tmp = test_request($url_ad_tmp);

				if (!empty($url_ad_tmp)) {

					$url_ad[] = $url_ad_tmp;

				} else {

					if ($type_operation != 'delete') {

						$error = true;
						$error_message .= 'Ссылку на объявление в интернете обязательно нужно добавить.<br>';

					}

				}

			}

		} else {

			if ($type_operation != 'delete') {

				$error = true;
				$error_message .= 'Хотя бы одну ссылку на объявление в интернете обязательно нужно добавить.<br>';

			}

		}
		
		$url_ad = json_encode($url_ad, JSON_UNESCAPED_UNICODE);

		if ($type_operation == 'add') {

			if (!$error) {

				$sql = "INSERT INTO `school_homework` SET `user_id`='{$user_id}',
															`goods_id`='{$goods_id}',
															`link_ad`='{$url_ad}',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$school_homework_id = mysqli_insert_id($db);
				$sql = "SELECT `id` FROM `school_homework` WHERE `id`='{$school_homework_id}' AND `user_id`='{$user_id}' AND DATE(`created`)=CURDATE()";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				
				if (mysqli_num_rows($query) == 0) {

					$sql = "SELECT `mail` FROM `users` WHERE `id`='{$user['partner_id']}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$user_mentor = mysqli_fetch_assoc($query);

					$email = $user_mentor['mail'];

					$subject = "У вас новое домашнее задание на проверку от Вашего партнера ".$user['name']." ".$user['surname']." в ".$name_company;
					$message = "<h3 style='text-align:center;'>1) Зайдите в раздел Команда.<h3>
								<h3 style='text-align:center;'>2) Найдите своего партнера ".$user['name']." ".$user['surname'].".<h3>
								<h3 style='text-align:center;'>3) Проверьте его домашнее задание.<h3>
								<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/partners/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить</a>
								<br>
								<p style='text-align:center;font-weight:normal;font-style:italic;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
					$from['name_company'] = $name_company;
					$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

					sendMail($email, $subject, $message, $from, $server_protocole);

				}

				header('Location: /account/school_homework/');
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '.$error_message.'</div>';

			}

		} elseif ($type_operation == 'edit') {

			if (!$error) {

				$sql = "UPDATE `school_homework` SET `user_id`='{$user_id}',
													`goods_id`='{$goods_id}',
													`link_ad`='{$url_ad}',
													`status`=0,
													`updated`='{$current_date}' WHERE `id`='{$homework_id}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$sql = "SELECT `id` FROM `school_homework` WHERE `id`='{$homework_id}' AND `user_id`='{$user_id}' AND DATE(`updated`)=CURDATE()";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				
				if (mysqli_num_rows($query) == 0) {

					$sql = "SELECT `mail` FROM `users` WHERE `id`='{$user['partner_id']}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$user_mentor = mysqli_fetch_assoc($query);

					$email = $user_mentor['mail'];

					$subject = "У вас новое домашнее задание на проверку от Вашего партнера ".$user['name']." ".$user['surname']." в ".$name_company;
					$message = "<h3 style='text-align:center;'>1) Зайдите в раздел Команда.<h3>
								<h3 style='text-align:center;'>2) Найдите своего партнера ".$user['name']." ".$user['surname'].".<h3>
								<h3 style='text-align:center;'>3) Проверьте его домашнее задание.<h3>
								<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/partners/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Проверить</a>
								<br>
								<p style='text-align:center;font-weight:normal;font-style:italic;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
					$from['name_company'] = $name_company;
					$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

					sendMail($email, $subject, $message, $from, $server_protocole);

				}

				header('Location: /account/school_homework/');
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '.$error_message.'</div>';

			}

		} elseif ($type_operation == 'delete') {
			
			if (!$error) {

				$sql = "DELETE FROM `school_homework` WHERE `id`='{$homework_id}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: /account/school_homework/');
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '.$error_message.'</div>';

			}

		}

	}

}

/*if (isset($_GET['success'])) {

	$alert_message = '<div class="alert alert-success" role="alert"><strong>Поздравляем!</strong> Вы успешно оформили заявку на курс. В скором времени с вами свяжется наш менеджер.</div>';

}*/

?>