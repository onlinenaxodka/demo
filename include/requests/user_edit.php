<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (!empty($_POST['name'])) {

			$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
			$name = test_request($name);

			$surname = (isset($_POST['surname'])) ? mysqli_real_escape_string($db, $_POST['surname']) : '';
			$surname = test_request($surname);

			$nickname = (isset($_POST['nickname'])) ? mysqli_real_escape_string($db, $_POST['nickname']) : '';
			$nickname = test_request($nickname);

			$birthday = (isset($_POST['birthday'])) ? mysqli_real_escape_string($db, $_POST['birthday']) : '';
			$birthday = test_request($birthday);

			$country = (isset($_POST['country'])) ? mysqli_real_escape_string($db, $_POST['country']) : '';
			$country = test_request($country);
			$country = intval($country);

			$region = (isset($_POST['region'])) ? mysqli_real_escape_string($db, $_POST['region']) : '';
			$region = test_request($region);
			$region = intval($region);

			$city = (isset($_POST['city'])) ? mysqli_real_escape_string($db, $_POST['city']) : '';
			$city = test_request($city);
			$city = intval($city);
			
			$sex = (isset($_POST['sex'])) ? mysqli_real_escape_string($db, $_POST['sex']) : '';
			$sex = test_request($sex);
			$sex = intval($sex);

			$lang_post = (isset($_POST['lang'])) ? mysqli_real_escape_string($db, $_POST['lang']) : '';
			$lang_post = test_request($lang_post);

			$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
			$phone = test_request($phone);

			$telegram = (isset($_POST['telegram'])) ? mysqli_real_escape_string($db, $_POST['telegram']) : '';
			$telegram = test_request($telegram);

			$skype = (isset($_POST['skype'])) ? mysqli_real_escape_string($db, $_POST['skype']) : '';
			$skype = test_request($skype);

			$card = (isset($_POST['card'])) ? mysqli_real_escape_string($db, $_POST['card']) : '';
			$card = test_request($card);

			/*$site = (isset($_POST['site'])) ? mysqli_real_escape_string($db, $_POST['site']) : '';
			$site = test_request($site);*/

			$error = false;
			$errort = '';

			if (!empty($name)) {

				if (strlen(utf8_decode($name)) < 2) {

					$error = true;
					$errort .= 'Введите не менее 2 символов в поле Имя. ';

				}

			} else {

				$error = true;
				$errort .= 'Поле Имя является обязательным, оно не может быть пустым. ';

			}

			if (!empty($surname)){

				if (strlen(utf8_decode($surname)) < 2) {

					$error = true;
					$errort .= 'Введите не менее 2 символов для Фамилии. ';

				}

			}

			if (!empty($nickname)) {

				if (preg_match("/^[a-z0-9_-]{2,20}$/", $nickname)) {

					$sql = "SELECT `id` FROM `users` WHERE `nickname`='{$nickname}' AND `id`!='{$user_id}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					if (mysqli_num_rows($query) == 1) {

						$error = true;
						$errort .= 'Человек с этим никнеймом уже существует, введите другой. ';

					} else {

						$forbidden_nicknames = array('account', 'admin', 'assets', 'finance', 'include', 'info', 'landing', 'merchant', 'network', 'statistic', 'user', 'activate', 'blocked', 'config', 'favicon', 'index', 'login', 'logout', 'register', 'remind', 'technical_work', 'edit', 'css', 'fonts', 'images', 'js', 'success', 'error');

						for ($i = 0; $i < count($forbidden_nicknames); $i++) { 
							
							if ($nickname == $forbidden_nicknames[$i]) {

								$error = true;
								$errort .= 'Этот никнейм запрещен, введите другой. ';

							}

						}

					}

				} else {

					$error = true;
					$errort .= 'Введите никнейм небольшими латинскими буквами от 2 до 20 символов. Допустимые числа и символы «_-». ';

				}

			} else {

				$error = true;
				$errort .= 'Поле никнейм обязательное. ';

			}

			if (!empty($birthday)) {

				if (preg_match($preg_match_rule_date, $birthday)) {

					if (strtotime($birthday.' +14 years') > strtotime('now')) {

						$error = true;
						$errort .= 'Допустимый возраст 14 лет. ';

					} else {

						$birthday = date('Y-m-d', strtotime($birthday));

					}

				} else {

					$error = true;
					$errort .= 'Введите правильный формат даты '.$parametrs_datepicker[2].'. ';

				}

			}

			if (!empty($country)) {

				if ($country < 0) {

					$error = true;
					$errort .= 'Фатальная ошибка. ';

				}

			}

			if (!empty($region)) {

				if ($region < 0) {

					$error = true;
					$errort .= 'Фатальная ошибка. ';

				}

			}

			if (!empty($city)) {

				if ($city < 0) {

					$error = true;
					$errort .= 'Фатальная ошибка. ';

				}

			}

			if (!empty($sex)) {

				if ($sex < 0) {

					$error = true;
					$errort .= 'Фатальная ошибка. ';

				}

			}

			if (empty($phone)) {

				$error = true;
				$errort .= 'Поле Телефон обязательное. ';

			} else {

				if (!preg_match("/^[+][0-9]{12,12}$/", $phone)) {

					$error = true;
					$errort .= 'Неверный формат телефона, введите в формате +38098*******.'.$phone.' ';

				}

			}

			if (!empty($telegram)) {

				if (substr($telegram, 0, 1) == '@') {
				
					$error = true;
					$errort .= 'Укажите никнейм Telegram без собачки @ в начале строки. ';

				}

			}

			if (!empty($skype)) {

				if (!preg_match("/^[a-z0-9_.-]{6,32}$/", $skype)) {
				
					$error = true;
					$errort .= 'Укажите skype маленькими латинскими буквами от 6 до 32 символов. Допускаются цифры и символы "._-". ';

				}

			}

			if (!empty($card)) {

				if (!preg_match("/^[0-9]{16,16}$/", $card)) {
				
					$error = true;
					$errort .= 'Номер карты должен состоять из 16 цифир. ';

				}

			}

			if (!empty($lang_post)) {

				if (!preg_match("/^[a-z]{2,3}$/", $lang_post)) {
				
					$error = true;
					$errort .= 'Фатальная ошибка. ';

				} else {

					if (!in_array($lang_post, array_keys($lang_files))) {

						$error = true;
						$errort .= 'Неправельный язык. ';

					}

				}

			} else {

				$error = true;
				$errort .= 'Поле Язык обязательное. ';

			}

			/*if (!empty($site)) {

				if (filter_var($site, FILTER_VALIDATE_URL) == false) {
				
					$error = true;
					$errort .= 'Адрес моего интернет магазина некорректный, исправьте ошибку. ';

				}

			} else {

				$error = true;
				$errort .= 'Поле Адрес моего интернет магазина обязательное. ';

			}*/

			if (!$error) {

				$sql = "UPDATE `users` SET `nickname`='{$nickname}',
													`name`=\"{$name}\",
													`surname`=\"{$surname}\",
													`birthday`='{$birthday}',
													`sex`='{$sex}',
													`phone`='{$phone}',
													`telegram`=\"{$telegram}\",
													`skype`='{$skype}',
													`country`='{$country}',
													`region`='{$region}',
													`city`='{$city}',
													`card`='{$card}',
													`lang`='{$lang_post}',
													`updated`='{$current_date}' WHERE `id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: ?success');
    			exit;

			} else {

				$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $errort . '</div>';

			}

		} elseif (!empty($_POST['old_password'])) {
			
			$old_password = (isset($_POST['old_password'])) ? mysqli_real_escape_string($db, $_POST['old_password']) : '';
			$old_password = test_request($old_password);

			$new_password = (isset($_POST['new_password'])) ? mysqli_real_escape_string($db, $_POST['new_password']) : '';
			$new_password = test_request($new_password);

			$again_new_password = (isset($_POST['again_new_password'])) ? mysqli_real_escape_string($db, $_POST['again_new_password']) : '';
			$again_new_password = test_request($again_new_password);
			
			$error = false;
			$errort = '';

			if (empty($user['password'])) {

				$error = true;
				$errort .= 'Вы зарегистрированы с помощь социальной сети и еще не указывали пароль. Чтобы указать пароль к акаунту, произведите возобновление акаунта - <a href="/logout/?remind" class="alert-link">Возобновить</a>. ';

			}

			if (!empty($old_password) or !empty($new_password) or !empty($again_new_password)) {

				if (strlen(utf8_decode($old_password)) < 6 or strlen(utf8_decode($new_password)) < 6 or strlen(utf8_decode($again_new_password)) < 6) {
					$error = true;
					$errort .= 'Введите не менее 6 символов для пароля. ';
				}

			} else {

				$error = true;
				$errort .= 'Поле Пароль обязательное, оно не должно быть пустым. ';

			}

			$hashed_old_password = md5(md5($old_password) . $user['salt']);
			
			if ($hashed_old_password != $user['password']) {

				$error = true;
				$errort .= 'Неверный введенный старый пароль. ';

			}

			if ($again_new_password != $new_password) {

				$error = true;
				$errort .= 'Пароли не совпадают. ';

			}

			if (!$error) {

				$hashed_new_password = md5(md5($new_password) . $user['salt']);

				$sql = "UPDATE `users` SET `password`='{$hashed_new_password}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Пароль успешно изменен.</div>';

			} else {

				$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $errort . '</div>';

			}

		} elseif (!empty($_POST['token'])) {
			
			$response = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST'] . '/user/edit/');
	        $user_social = json_decode($response, true);

	        if ($user_social['uid']) {

	            $network = $user_social['network'];
	            $social_id = trim($user_social['uid']);
	            $nickname = $user_social['nickname'];
	            $name = $user_social['first_name'];
	            $surname = $user_social['last_name'];
	            $email = $user_social['email'];
	            $phone = $user_social['phone'];
	            $birthday = $user_social['bdate'];
	            $sex = $user_social['sex'];
	            $city = $user_social['city'];
	            $country = $user_social['country'];
	            $avatar = $user_social['photo'];
	            $avatar_big = $user_social['photo_big'];
	            $profile = $user_social['profile'];
	            
	            if ($user_social['network']) {

	            	switch ($network) {
	    				case "facebook":
	        				$table_tag = 'fb';
	        				break;
	    				case "google":
	        				$table_tag = 'gl';
	        				break;
					}


	                $sql = "SELECT `user_id` FROM `users_{$table_tag}` WHERE `social_id`='{$social_id}' LIMIT 1";
	                $query = mysqli_query($db, $sql) or die(mysqli_error($db));

	                if (mysqli_num_rows($query) != 1) {
	                     
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

	                    $alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Социальная сеть успешно подключена к вашей учетной записи.</div>';

	                } else {

	                	$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Эта социальная сеть уже связана с другой учетной записью.</div>';

	                }

	            } else {

	            	$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Эта социальная сеть не найдена.</div>';
	                
	            }

	        } else {

	        	$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Пользователь этой социальной сети не может быть найден.</div>';
	            
	        }

		} elseif (!empty($_POST['shop_act'])) {

			$shop_act = (isset($_POST['shop_act'])) ? mysqli_real_escape_string($db, $_POST['shop_act']) : '';
			$shop_act = test_request($shop_act);

			$shop_id = (isset($_POST['shop_id'])) ? mysqli_real_escape_string($db, $_POST['shop_id']) : '';
			$shop_id = test_request($shop_id);
			$shop_id = intval($shop_id);

			$shop_type = (isset($_POST['shop_type'])) ? mysqli_real_escape_string($db, $_POST['shop_type']) : '';
			$shop_type = test_request($shop_type);

			$shop_url = (isset($_POST['shop_url'])) ? mysqli_real_escape_string($db, $_POST['shop_url']) : '';
			$shop_url = test_request($shop_url);

			$error = false;
			$error_message = '';

			if ($shop_act != 'delete') {

				if (empty($shop_type) or $shop_type == 'none') {

					$error = true;
					$error_message .= 'Вы не указали место продаж. ';

				}

				if (!empty($shop_url)) {

					if (filter_var($shop_url, FILTER_VALIDATE_URL) == true) {
						
						$sql = "SELECT `id` FROM `users_shops` WHERE `user_id`='{$user_id}' and `url`='{$shop_url}'";
		                $query = mysqli_query($db, $sql) or die(mysqli_error($db));

		                if (mysqli_num_rows($query) > 0) {

		                	$error = true;
							$error_message .= 'Вы уже добавили этот URL адрес '.$shop_url.'. ';

						}

					} else {

						$error = true;
						$error_message .= 'URL адрес '.$shop_url.' некорректный. ';

					}

				} else {

					$error = true;
					$error_message .= 'Вы не указали URL адрес. ';

				}

			}

			if ($shop_act == 'add') {

				if (!$error) {

					$sql = "INSERT INTO `users_shops` SET `user_id`='{$user_id}',
															`type`=\"{$shop_type}\",
															`url`='{$shop_url}',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно добавлены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			} elseif ($shop_act == 'edit') {

				if (!$error) {

					$sql = "UPDATE `users_shops` SET `type`=\"{$shop_type}\",
														`url`='{$shop_url}',
														`updated`='{$current_date}' WHERE `id`='{$shop_id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно изменены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			} elseif ($shop_act == 'delete') {

				if (!$error) {

					$sql = "DELETE FROM `users_shops` WHERE `id`='{$shop_id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно удалены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			}

		} elseif (!empty($_POST['project_act'])) {

			$project_act = (isset($_POST['project_act'])) ? mysqli_real_escape_string($db, $_POST['project_act']) : '';
			$project_act = test_request($project_act);

			$project_id = (isset($_POST['project_id'])) ? mysqli_real_escape_string($db, $_POST['project_id']) : '';
			$project_id = test_request($project_id);
			$project_id = intval($project_id);

			$project_type = (isset($_POST['project_type'])) ? mysqli_real_escape_string($db, $_POST['project_type']) : '';
			$project_type = test_request($project_type);

			$project_url = (isset($_POST['project_url'])) ? mysqli_real_escape_string($db, $_POST['project_url']) : '';
			$project_url = test_request($project_url);

			$error = false;
			$error_message = '';

			if ($project_act != 'delete') {

				if (empty($project_type) or $project_type == 'none') {

					$error = true;
					$error_message .= 'Вы не выбрали название свого проекта. ';

				}

				if (!empty($project_url)) {

					if (filter_var($project_url, FILTER_VALIDATE_URL) == true) {
						
						$sql = "SELECT `id` FROM `users_projects` WHERE `user_id`='{$user_id}' and `url`='{$project_url}'";
		                $query = mysqli_query($db, $sql) or die(mysqli_error($db));

		                if (mysqli_num_rows($query) > 0) {

		                	$error = true;
							$error_message .= 'Вы уже добавили этот URL адрес '.$project_url.'. ';

						}

					} else {

						$error = true;
						$error_message .= 'URL адрес '.$project_url.' некорректный. ';

					}

				} else {

					$error = true;
					$error_message .= 'Вы не указали URL адрес. ';

				}

			}

			if ($project_act == 'add') {

				if (!$error) {

					$sql = "INSERT INTO `users_projects` SET `user_id`='{$user_id}',
															`type`=\"{$project_type}\",
															`url`='{$project_url}',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно добавлены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			} elseif ($project_act == 'edit') {

				if (!$error) {

					$sql = "UPDATE `users_projects` SET `type`=\"{$project_type}\",
														`url`='{$project_url}',
														`updated`='{$current_date}' WHERE `id`='{$project_id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно изменены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			} elseif ($project_act == 'delete') {

				if (!$error) {

					$sql = "DELETE FROM `users_projects` WHERE `id`='{$project_id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно удалены.</div>';
					
				} else {

					$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> ' . $error_message . '</div>';

				}

			}

		}



	}

}

?>