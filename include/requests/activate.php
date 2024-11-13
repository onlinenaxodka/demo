<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (isset($_SESSION['user'])) {

			if ($user['activated'] != 1 ) {

				if (!empty($_POST['email']) and !empty($_POST['g-recaptcha-response'])) {

					$recaptcha_code = $_POST['g-recaptcha-response'];
					$recaptcha_url_data = $recaptcha_url.'?secret='.$recaptcha_secret.'&response='.$recaptcha_code.'&remoteip='.$ip;

					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $recaptcha_url_data);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($curl);
					curl_close($curl);
					$result = json_decode($response, true);

					if ($result['success'] == 1) {

						$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
						$email = test_request($email);

						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

							$sql = "SELECT `id` FROM `users` WHERE `mail`='{$email}' AND `activated`=1 LIMIT 1";
							$query = mysqli_query($db, $sql) or die(mysqli_error());
							$user = mysqli_fetch_assoc($query);

							if (mysqli_num_rows($query) != 1) {

								$sql = "UPDATE `users` SET `mail`='{$email}', `updated`='{$current_date}' WHERE `id`={$user_id}";
								$query = mysqli_query($db, $sql) or die(mysqli_error());

								$name_for_mail = (!empty($name)) ? $name : $word_member;
								$subject = $activate_page_send_message_title." ".$name_company;
								$message = "<h1 style='text-align:center'>".$word_hello.", ".$name_for_mail."!</h1>
													<p style='text-align:center'>".$activate_page_send_message_body_1."</p>
													<p style='text-align:center'><a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/activate/?sum=".$user_id."&hash=".$key."' style='display:block;max-width:300px;padding:10px 30px;margin:30px auto;color:#fff;background:#84ad00;font:20px Arial;text-decoration:none;text-align:center;border-radius:7px'>".$activate_page_send_message_body_2."</a></p>
													<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>".$activate_page_send_message_body_3." ".date('H:i d.m.Y')."</p>";
								$from['name_company'] = $name_company;
								$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

								if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

									$alert_message = '<div class="alert alert-success" role="alert"><strong>'.$activate_page_alert_message_success_1.'</strong> '.$activate_page_alert_message_success_2.' <b>'.$email.'</b>. '.$activate_page_alert_message_success_3.' <a href="//'.substr($email, strrpos($email, '@')+1).'" target="_blank">'.$activate_page_alert_message_success_4.'</a></div>';

								} else {

									$alert_message = '<div class="alert alert-danger" role="alert"><strong>' . $word_error . '</strong> ' . $activate_page_alert_message_error_1 . '</div>';

								}

							} else {

								$alert_message = '<div class="alert alert-danger" role="alert"><strong>' . $word_error . '</strong> ' . $activate_page_alert_message_error_2_1 . ' ('. $email .') ' . $activate_page_alert_message_error_2_2 . '</div>';

							}

						} else {

							$alert_message = '<div class="alert alert-danger" role="alert"><strong>' . $word_error . '</strong> ' . $activate_page_alert_message_error_3 . '</div>';

						}

					} else {

						$alert_message = '<div class="alert alert-danger" role="alert"><strong>' . $word_error . '</strong> ' . $activate_page_alert_message_error_4 . '</div>';

					}

				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>' . $word_error . '</strong> ' . $activate_page_alert_message_error_5 . '</div>';

				}

			} else {

				header('Location: /' . $main_page);
				exit;

			}

		} else {

			header('Location: /');
			exit;

		}

	}

} else {

	if (!empty($_GET['sum']) and !empty($_GET['hash'])) {

		$user_id = (isset($_GET['sum'])) ? mysqli_real_escape_string($db, $_GET['sum']) : '';
        $key = (isset($_GET['hash'])) ? mysqli_real_escape_string($db, $_GET['hash']) : '';

		$user_id = test_request($user_id);
		$key = test_request($key);

		if ((int)$user_id > 0 and preg_match("/^[a-zA-Z0-9]{64}$/",$key)) {

			$sql = "SELECT `id`, `key`, `activated` FROM `users` WHERE `id`='{$user_id}' AND `key`='{$key}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) == 1) {

				if ($user['activated'] == 0) {

					$user_id = $user['id'];

					$sql = "UPDATE `users` SET `activated`=1, `updated`='{$current_date}' WHERE `id`={$user_id}";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$_SESSION['user'] = array('id' => $user['id'], 'hash' => $user['key']);

					header('Location: ' . $main_page);
					exit;

				} else {

					header('Location: /');
					exit;

				}

			} else {

				header('Location: /');
				exit;

			}

		} else {

			header('Location: /');
			exit;

		}

	}

}

?>