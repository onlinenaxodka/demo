<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$recaptcha_code = $_POST['g-recaptcha-response'];
		$recaptcha_code = test_request($recaptcha_code);

		$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
		$email = test_request($email);

		$new_password = (isset($_POST['new_password'])) ? mysqli_real_escape_string($db, $_POST['new_password']) : '';
		$new_password = test_request($new_password);

		$again_new_password = (isset($_POST['again_new_password'])) ? mysqli_real_escape_string($db, $_POST['again_new_password']) : '';
		$again_new_password = test_request($again_new_password);

		$user_id = (isset($_GET['sum'])) ? mysqli_real_escape_string($db, $_GET['sum']) : '';
		$user_id = test_request($user_id);

        $key = (isset($_GET['hash'])) ? mysqli_real_escape_string($db, $_GET['hash']) : '';  
        $key = test_request($key);

        $error = false;
		$errort = '';

		if (!empty($recaptcha_code)) {

			$recaptcha_url_data = $recaptcha_url.'?secret='.$recaptcha_secret.'&response='.$recaptcha_code.'&remoteip='.$ip;

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $recaptcha_url_data);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($curl);
			curl_close($curl);
			$result = json_decode($response, true);

			if ($result['success'] != 1) {

				$error = true;
				$errort .= $remind_page_error_message_1 . '<br>';

			}

		} else {

			$error = true;
			$errort .= $remind_page_error_message_2 . '<br>';

		}

		if (!empty($user_id) and !empty($key)) {

			if ((int)$user_id > 0 and preg_match("/^[a-zA-Z0-9]{64}$/",$key)) {

				if (!empty($new_password) or !empty($again_new_password)) {

					if (strlen($new_password) < 6 or strlen($again_new_password) < 6) {

						$error = true;
						$errort .= $remind_page_error_message_3 . '<br>';

					} else {

						if ($again_new_password != $new_password) {

							$error = true;
							$errort .= $remind_page_error_message_4 . '<br>';

						}

					}

				} else {

					$error = true;
					$errort .= $remind_page_error_message_5 . '<br>';

				}

			} else {

				header('Location: /');
				exit;

			}

		} else {

			if (!empty($email)) {

				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

					$error = true;
					$errort .= $remind_page_error_message_6 . '<br>';

				} else {

					$sql = "SELECT `id` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					if (mysqli_num_rows($query) != 1) {

						$error = true;
						$errort .= $remind_page_error_message_7 . '<br>';

					}

				}

			} else {

				$error = true;
				$errort .= $remind_page_error_message_8 . '<br>';

			}

		}

		if (!$error) {

			if (!empty($user_id) and !empty($key)) {

				if ((int)$user_id > 0 and preg_match("/^[a-zA-Z0-9]{64}$/",$key)) {

					$sql = "SELECT `id`, `salt`, `key` FROM `users` WHERE `id`='{$user_id}' AND `key`='{$key}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
					$user = mysqli_fetch_assoc($query);

					if (mysqli_num_rows($query) == 1) {

						$hashed_new_password = md5(md5($new_password) . $user['salt']);

						$sql = "UPDATE `users` SET `password`='{$hashed_new_password}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error());

						$alert_message = '<div class="alert alert-success" role="alert"><strong>'.$word_saved.'</strong> '.$remind_page_success_message_2.' <a href="/login/" class="alert-link">'.$remind_page_success_message_3.'</a></div>';
					
					}
				
				}

			} else {

				if (!empty($email)) {

					$sql = "SELECT `id`, `name`, `key` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
					$user = mysqli_fetch_assoc($query);

					if (mysqli_num_rows($query) == 1) {

						$name_for_mail = (!empty($user['name'])) ? $user['name'] : $word_member;
						$subject = $remind_page_send_message_title." ".$name_company;
						$message = "<h1 style='text-align:center'>".$word_hello.", ".$name_for_mail."!</h1>
											<p style='text-align:center'>".$remind_page_send_message_body_1."</p>
											<p style='text-align:center'><a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/remind/?sum=".$user['id']."&hash=".$user['key']."' style='display:block;max-width:300px;padding:10px 30px;margin:30px auto;color:#fff;background:#84ad00;font:20px Arial;text-decoration:none;text-align:center;border-radius:7px'>".$remind_page_send_message_body_2."</a></p>
											<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>".$remind_page_send_message_body_3." ".date('H:i d.m.Y')."</p>";
						$from['name_company'] = $name_company;
						$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

						if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

							//$_SESSION['domen_mail'] = $email;

							$alert_message = '<div class="alert alert-success" role="alert"><strong>'.$remind_page_success_message_4.'</strong> '.$remind_page_success_message_5.' <a href="//'.substr($email, strrpos($email, '@')+1).'" class="alert-link" target="_blank">'.$remind_page_success_message_6.'</a></div>';

						} else {

							$error = true;
							$error_message .= $activate_page_alert_message_error_1 . '<br>';

						}

					}

				}

			}

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>'.$word_error.'</strong> ' . $errort . '</div>';

		}

	}

}

?>