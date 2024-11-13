<?php

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (!empty($_SESSION['partner_id'])) {

			$partner_id = (isset($_SESSION['partner_id'])) ? mysqli_real_escape_string($db, $_SESSION['partner_id']) : '';
			$partner_id = test_request($partner_id);
			$partner_id = intval($partner_id);

			if ($partner_id == 0) $partner_id = 1;

			if ($partner_id > 1) {
				
				$sql = "SELECT * FROM `users` WHERE `id`='{$partner_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$user_partner = mysqli_fetch_assoc($query);

				//if ($user_partner['status'] == 0) $partner_id = 1;

			}
		
		}

		if (!empty($_SESSION['lang'])) {

			$lang = (isset($_SESSION['lang'])) ? mysqli_real_escape_string($db, $_SESSION['lang']) : '';
			$lang = test_request($lang);
		
		}

		if (!empty($_SESSION['gtm'])) {

			$gtm = (isset($_SESSION['gtm'])) ? mysqli_real_escape_string($db, $_SESSION['gtm']) : '';
			$gtm = test_request($gtm);

			$gtm_access = array('google');

			if (!in_array($gtm, $gtm_access)) $gtm = '';
		
		}

		$error = false;
		$error_message = '';

		if (!empty($_POST['token'])) {

			$response = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
	        $user = json_decode($response, true);

	        if ($user['uid']) {

	            $network = $user['network'];
	            $social_id = trim($user['uid']);
	            $nickname = $user['nickname'];
	            $name = $user['first_name'];
	            $surname = $user['last_name'];
	            $email = $user['email'];
	            $phone = $user['phone'];
	            $phone = str_replace(' ', '', $phone);
	            $phone = str_replace('-', '', $phone);
	            $phone = str_replace('(', '', $phone);
	            $phone = str_replace(')', '', $phone);
	            $birthday = $user['bdate'];
	            $sex = $user['sex'];
	            $city = $user['city'];
	            $country = $user['country'];
	            $avatar = $user['photo'];
	            $avatar_big = $user['photo_big'];
	            $profile = $user['profile'];
	            
	            if ($user['network']) {

					switch ($network) {
						case "facebook":
							$table_tag = 'fb';
							break;
						case "google":
							$table_tag = 'gl';
							break;
					}
	                
	                $sql = "SELECT `user_id` FROM `users_{$table_tag}` WHERE `social_id`='{$social_id}' LIMIT 1";
	                $query = mysqli_query($db, $sql) or die(mysqli_error());
	                $user = mysqli_fetch_assoc($query);
	                $user_id = $user['user_id'];

	                if (mysqli_num_rows($query) != 1) {

	                    $sql = "SELECT `id` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
	                    $query = mysqli_query($db, $sql) or die(mysqli_error());

	                    if (mysqli_num_rows($query) == 0) {

							$work_id = GenerateWorkId();

							$sql = "SELECT `work_id` FROM `users` WHERE `work_id`='{$work_id}' LIMIT 1";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

							while (mysqli_num_rows($query) == 1) {
								
								$work_id = GenerateWorkId();

								$sql = "SELECT `work_id` FROM `users` WHERE `work_id`='{$work_id}' LIMIT 1";
								$query = mysqli_query($db, $sql) or die(mysqli_error());

							}

	                        $salt = GenerateSalt();
	                        $key = GenerateKey();

							$groupsApi = (new MailerLiteApi\MailerLite('7285ed97b521f5b88ef5bc0ef50e6509'))->groups();

							$subscriber = [
								'email' => $email,
								'type' => 'active',
								'fields' => [
									'name' => $name,
									'company' => 'ONLINE NAXODKA'
								]
							];

							$response = $groupsApi->addSubscriber(9227860, $subscriber);

							if (!$response->error) {

		                        $sql = "INSERT INTO `users` SET
		                                                    `partner_id`='{$partner_id}',
		                                                    `work_id`='{$work_id}',
		                                                    `name`=\"{$name}\",
		                                                    `surname`=\"{$surname}\",
		                                                    `mail`='{$email}',
		                                                    `phone`='{$phone}',
		                                                    `salt`='{$salt}',
		                                                    `key`='{$key}',
															`lang`='{$lang}',
															`gtm`='{$gtm}',
															`subscription`=1,
															`terms`=1,
		                                                    `ip`='{$ip}',
		                                                    `was`='{$current_date}',
		                                                    `updated`='{$current_date}',
		                                                    `created`='{$current_date}'";
		                        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

			                    $sql = "SELECT `id`, `key` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
			                    $query = mysqli_query($db, $sql) or die(mysqli_error());
			                    $user = mysqli_fetch_assoc($query);
			                    $user_id = $user['id'];
			                    $nickname = 'id' . $user['id'];

			                    $sql = "UPDATE `users` SET `nickname`='{$nickname}', `updated`='{$current_date}' WHERE `id`={$user_id}";
								$query = mysqli_query($db, $sql) or die(mysqli_error());

			                    $sql = "INSERT INTO `users_{$table_tag}` SET
			                                                    `user_id`='{$user_id}',
			                                                    `social_id`='{$social_id}',
			                                                    `nickname`=\"{$nickname}\",
			                                                    `name`=\"{$name}\",
			                                                    `surname`=\"{$surname}\",
			                                                    `mail`='{$email}',
			                                                    `phone`='{$phone}',
			                                                    `birthday`='{$birthday}',
			                                                    `sex`='{$sex}',
			                                                    `city`=\"{$city}\",
			                                                    `country`=\"{$country}\",
			                                                    `avatar`='{$avatar}',
			                                                    `avatar_big`='{$avatar_big}',
			                                                    `profile`='{$profile}',
			                                                    `updated`='{$current_date}',
			                                                    `created`='{$current_date}'";

			                    $query = mysqli_query($db, $sql) or die(mysqli_error());

			                    //$_SESSION['user'] = array('id' => $user_id, 'hash' => $user['key']);

			                    $name_for_mail = (!empty($name)) ? $name : $word_member;
								$subject = $activate_page_send_message_title." ".$name_company;
								$message = "<h1 style='text-align:center'>".$word_hello.", ".$name_for_mail."!</h1>
											<p style='text-align:center'>".$activate_page_send_message_body_1."</p>
											<p style='text-align:center'><a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/activate/?sum=".$user_id."&hash=".$key."' style='display:block;max-width:300px;padding:10px 30px;margin:30px auto;color:#fff;background:#84ad00;font:20px Arial;text-decoration:none;text-align:center;border-radius:7px'>".$activate_page_send_message_body_2."</a></p>
											<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>".$activate_page_send_message_body_3." ".date('H:i d.m.Y')."</p>";
								$from['name_company'] = $name_company;
								$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

								if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

									$_SESSION['domen_mail'] = $email;
									$key_hash = md5($user_partner['mail'].$email);

									header('Location: /register/?success&key='.$key_hash);
									exit;

								} else {

			                    	$error = true;
									$error_message .= $activate_page_alert_message_error_1 . '<br>';

			                    }

		                	} else {

		                		$error = true;
								$error_message .= $register_page_error_message_17 . '<br>';

		                	}

						} else {

							$error = true;
	                		$error_message .= $register_page_error_message_9 . '<br>';

						}

	                } else {

	                	$error = true;
	                	$error_message .= $register_page_error_message_10 . '<br>';

	                }

	            } else {
	                
	                $error = true;
	            	$error_message .= $register_page_error_message_11 . '<br>';

	            }

	        } else {
	            
	            $error = true;
	        	$error_message .= $register_page_error_message_12 . '<br>';

	        }

		} else {

			if (!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['g-recaptcha-response'])) {

				$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
				$name = test_request($name);

				$surname = (isset($_POST['surname'])) ? mysqli_real_escape_string($db, $_POST['surname']) : '';
				$surname = test_request($surname);

				$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
				$phone = test_request($phone);

				$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
				$email = test_request($email);

		        $password = (isset($_POST['password'])) ? mysqli_real_escape_string($db, $_POST['password']) : '';
		        $password = test_request($password);

		        $subscription = (isset($_POST['subscription'])) ? mysqli_real_escape_string($db, $_POST['subscription']) : '';
				$subscription = test_request($subscription);
				$subscription = intval($subscription);

				$terms = (isset($_POST['terms'])) ? mysqli_real_escape_string($db, $_POST['terms']) : '';
				$terms = test_request($terms);

				$recaptcha_code = $_POST['g-recaptcha-response'];
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
					$error_message = $login_page_error_message_8 . '<br>';

				}

				$sql = "SELECT `salt` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				if (mysqli_num_rows($query) == 1) {

					$error = true;
					$error_message = $register_page_error_message_13 . '<br>';

				}

				if (strlen(utf8_decode($name)) < 2) {

					$error = true;
					$error_message = $register_page_error_message_2 . '<br>';

				}

				if (!empty($surname)) {

					if (strlen(utf8_decode($surname)) < 2) {

						$error = true;
						$error_message = $register_page_error_message_3 . '<br>';

					}

				}

				if (empty($phone)) {

					$error = true;
					$errort .= $register_page_error_message_18 . '<br>';

				} else {

					if (!preg_match("/^[+][0-9]{12,12}$/", $phone)) {

						$error = true;
						$errort .= $register_page_error_message_19 . '<br>';

					}

				}

				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

					$error = true;
					$error_message = $register_page_error_message_6 . '<br>';

				}

				if (strlen(utf8_decode($password)) < 6) {

					$error = true;
					$error_message = $register_page_error_message_8 . '<br>';

				}

				if ($subscription != 0 and $subscription != 1) {

					$error = true;
					$error_message = $register_page_error_message_16 . '<br>';

				}

				if (intval($terms) != 1) {

					$error = true;
					$error_message = $register_page_error_message_14 . '<br>';

				}

				if (!$error) {

					$work_id = GenerateWorkId();

					$sql = "SELECT `work_id` FROM `users` WHERE `work_id`='{$work_id}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					while (mysqli_num_rows($query) == 1) {
								
						$work_id = GenerateWorkId();

						$sql = "SELECT `work_id` FROM `users` WHERE `work_id`='{$work_id}' LIMIT 1";
						$query = mysqli_query($db, $sql) or die(mysqli_error());

					}

					$salt = GenerateSalt();
					$hashed_password = md5(md5($password) . $salt);
					$key = GenerateKey();

					if ($subscription == 1) {

						$groupsApi = (new MailerLiteApi\MailerLite('7285ed97b521f5b88ef5bc0ef50e6509'))->groups();

						$subscriber = [
							'email' => $email,
							'type' => 'active',
							'fields' => [
								'name' => $name,
								'company' => 'ONLINE NAXODKA'
							]
						];

						$response = $groupsApi->addSubscriber(9227860, $subscriber);

						if ($response->error) {

							$error = true;
							$error_message .= $register_page_error_message_17 . '<br>';

						}

					}

					if (!$error) {

						$sql = "INSERT INTO `users` SET
												`partner_id`='{$partner_id}',
												`work_id`='{$work_id}',
												`name`=\"{$name}\",
												`surname`=\"{$surname}\",
												`mail`='{$email}',
												`phone`='{$phone}',
												`password`='{$hashed_password}',
												`salt`='{$salt}',
												`key`='{$key}',
												`lang`='{$lang}',
												`gtm`='{$gtm}',
												`subscription`='{$subscription}',
												`terms`='{$terms}',
												`ip`='{$ip}',
												`was`='{$current_date}',
												`updated`='{$current_date}',
												`created`='{$current_date}'";
			        	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			        	$sql = "SELECT `id` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
						$query = mysqli_query($db, $sql) or die(mysqli_error());
						$user = mysqli_fetch_assoc($query);
						$user_id = $user['id'];
						$nickname = 'id' . $user['id'];

						$sql = "UPDATE `users` SET `nickname`='{$nickname}', `updated`='{$current_date}' WHERE `id`={$user_id}";
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

							$_SESSION['domen_mail'] = $email;
							$key_hash = md5($user_partner['mail'].$email);

							header('Location: /register/?success&key='.$key_hash);
							exit;

						} else {

							$error = true;
							$error_message .= $activate_page_alert_message_error_1 . '<br>';

						}

					}

				}

			} else {

				$error = true;
				$error_message .= $register_page_error_message_15 . '<br>';

			}

		}

		if ($error) {

			$alert_message = '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' . $word_error . '</strong> ' . $error_message . '</div>';

		}

	}

}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

	if (!empty($_SESSION['partner_id'])) {

		$partner_id = (isset($_SESSION['partner_id'])) ? mysqli_real_escape_string($db, $_SESSION['partner_id']) : '';
		$partner_id = test_request($partner_id);
		$partner_id = intval($partner_id);

		if ($partner_id == 0) $partner_id = 1;

		if ($partner_id > 1) {
				
			$sql = "SELECT * FROM `users` WHERE `id`='{$partner_id}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user_partner = mysqli_fetch_assoc($query);

		}
		
	}

	if (isset($_SESSION['domen_mail']) and isset($_GET['key'])) {

		$email_child = $_SESSION['domen_mail'];
		$key_hash = md5($user_partner['mail'].$email_child);

	}

	if (isset($_GET['success']) and isset($_SESSION['domen_mail']) and $_GET['key'] == $key_hash) {

		if ($partner_id > 1) {

			$sql = "SELECT * FROM `users` WHERE `mail`='{$email_child}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user_child = mysqli_fetch_assoc($query);

			$email = $user_partner['mail'];
			$subject = "Новый партнер ID:".$user_child['work_id']." в ".$name_company;
			$message = "<p style='text-align:center'>У вас появился новый партнер ".$user_child['name']." ".$user_child['surname']." в ".$name_company.".</p>
						<p style='text-align:center'>Войдите пожалуйста на <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/login/'>платформу</a> и проверьте более детальную информацию о своем партнере в разделе Команда.</p>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Войти</a>
						<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
						<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата регистрации партнера: ".date('d.m.Y H:i')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

		}

	}

	if (!empty($_GET['category']) and !empty($_GET['goods'])) {

		$linkname = (isset($_GET['category'])) ? mysqli_real_escape_string($db, $_GET['category']) : '';
        $linkname = test_request($linkname);

        $goods_id = (isset($_GET['goods'])) ? mysqli_real_escape_string($db, $_GET['goods']) : '';
        $goods_id = test_request($goods_id);
        $goods_id = intval($goods_id);

        $sql = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods_data_count = mysqli_num_rows($query);
		$goods_data = mysqli_fetch_assoc($query);
		$goods_data['name'] = json_decode($goods_data['name'], true);

		if ($goods_data_count > 0) {

			$_SESSION['main_page']['url'] = '/account/goods/'.$linkname.'/'.$goods_id;
			$_SESSION['main_page']['name'] = $goods_data['name']['ru'];

		}

		header('Location: /register/');
		exit;

	}

}

function GenerateWorkId($n=5) {
	$key = '';
	$pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$pattern_arr = str_split($pattern);
	$counter = strlen($pattern)-1;
	for($i=0; $i<$n; $i++) {
		$key .= $pattern_arr[rand(0,$counter)];
	}
	return $key;
}

function GenerateKey($n=64) {
	$key = '';
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$pattern_arr = str_split($pattern);
	$counter = strlen($pattern)-1;
	for($i=0; $i<$n; $i++) {
		$key .= $pattern_arr[rand(0,$counter)];
	}
	return $key;
}

function GenerateSalt($n=3) {
	$key = '';
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
	$pattern_arr = str_split($pattern);
	$counter = strlen($pattern)-1;
	for($i=0; $i<$n; $i++) {
		$key .= $pattern_arr[rand(0,$counter)];
	}
	return $key;
}

?>