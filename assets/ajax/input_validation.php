<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';
include_once __DIR__ . '/../../include/lang_files.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);
	$user_id = $user['id'];

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$data['error'] = 'false';

	if (isset($_POST['name'])) {

		$name = test_request($_POST['name']);
		$data['name'] = 'name';

		if (empty($name)) {

			$data['error'] = 'true';
			$data['message'] = $register_page_error_message_1;

		} else {
			
			if (strlen(utf8_decode($name)) < 2) {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_2;

			}


		}

	} elseif (isset($_POST['surname'])) {
			
		$surname = test_request($_POST['surname']);
		$data['name'] = 'surname';

		if (!empty($surname)) {

			if (strlen(utf8_decode($surname)) < 2) {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_3;

			}

		}


	} elseif (isset($_POST['email'])) {
			
		$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
		$email = test_request($email);
		$data['name'] = 'email';

		if (empty($email)) {

			$data['error'] = 'true';
			$data['message'] = $register_page_error_message_4;

		} else {

			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

				$sql = "SELECT `id` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				if (mysqli_num_rows($query) == 1) {

					$data['error'] = 'true';
					$data['message'] = $register_page_error_message_5;

				}

			} else {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_6;

			}

		}

			
	} elseif (isset($_POST['password'])) {
			
		$password = test_request($_POST['password']);
		$data['name'] = 'password';

		if (empty($password)) {

			$data['error'] = 'true';
			$data['message'] = $register_page_error_message_7;

		} else {

			if (strlen(utf8_decode($password)) < 6) {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_8;

			}

		}

	} elseif (isset($_POST['nickname'])) {
			
		$nickname = test_request($_POST['nickname']);
		$data['name'] = 'nickname';

		if (!empty($nickname)) {

			if (preg_match("/^[a-z0-9_-]{2,20}$/", $nickname)) {

				$sql = "SELECT `id` FROM `users` WHERE `nickname`='{$nickname}' AND `id`!='{$user_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				if (mysqli_num_rows($query) == 1) {

					$data['error'] = 'true';
					$data['message'] = 'Person with this nickname already exists, enter another.';

				} else {

					$forbidden_nicknames = array('admin', 'assets', 'finance', 'include', 'info', 'landing', 'merchant', 'network', 'statistic', 'user', 'activate', 'blocked', 'config', 'favicon', 'index', 'login', 'logout', 'register', 'remind', 'technical_work', 'edit', 'css', 'fonts', 'images', 'js');

					for ($i = 0; $i < count($forbidden_nicknames); $i++) { 
							
						if ($nickname == $forbidden_nicknames[$i]) {

							$data['error'] = 'true';						
							$data['message'] = 'This nickname is forbidden, enter another.';

						}

					}

				}

			} else {

				$data['error'] = 'true';
				$data['message'] = 'Enter nickname with small Latin letters from 2 till 20 symbols. Valid numbers and symbols "_-".';

			}

		} else {

			$data['error'] = 'true';
			$data['message'] = 'Поле никнейм обязательное.';

		}

	} elseif (isset($_POST['birthday'])) {
			
		$birthday = test_request($_POST['birthday']);
		$data['name'] = 'birthday';

		if (!empty($birthday)) {

			if (preg_match($preg_match_rule_date, $birthday)) {

				if (strtotime($birthday.' +14 years') > strtotime('now')) {

					$data['error'] = 'true';
					$data['message'] = 'Allowed age 14 years.';

				}

			} else {

				$data['error'] = 'true';
				$data['message'] = 'Enter correct date format '.$parametrs_datepicker[2].'.';

			}

		}

	} elseif (isset($_POST['phone'])) {
			
		$phone = test_request($_POST['phone']);
		$data['name'] = 'phone';

		if (empty($phone)) {

			$data['error'] = 'true';
			$data['message'] = $register_page_error_message_18;

		} else {

			if (!preg_match("/^[+][0-9]{12,12}$/", $phone)) {

				$data['error'] = 'true';
				$data['message'] = 'Неверный формат телефона, введите в формате +38098*******.'.$phone;

			}

		}

	} elseif (isset($_POST['telegram'])) {
			
		$telegram = test_request($_POST['telegram']);
		$data['name'] = 'telegram';

		if (!empty($telegram)) {

			if (substr($telegram, 0, 1) == '@') {
			
				$data['error'] = 'true';
				$data['message'] = 'Укажите никнейм Telegram без собачки @ в начале строки.';

			}

		}

	} elseif (isset($_POST['skype'])) {
			
		$skype = test_request($_POST['skype']);
		$data['name'] = 'skype';

		if (!empty($skype)) {

			if (!preg_match("/^[a-z0-9_.-]{6,32}$/", $skype)) {
			
				$data['error'] = 'true';
				$data['message'] = 'Укажите skype маленькими латинскими буквами от 6 до 32 символов. Допускаются цифры и символы "._-".';

			}

		}

	} elseif (isset($_POST['payeer'])) {
			
		$payeer = test_request($_POST['payeer']);
		$data['name'] = 'payeer';

		if (!empty($payeer)) {

			if (!preg_match("/^[P][0-9]{2,30}$/", $payeer)) {

				$data['error'] = 'true';
				$data['message'] = 'Укажите номер своего кошелька Payeer в формате P00000000.';

			}

		}

	} elseif (isset($_POST['perfectmoney'])) {
			
		$perfectmoney = test_request($_POST['perfectmoney']);
		$data['name'] = 'perfectmoney';

		if (!empty($perfectmoney)) {

			if (!preg_match("/^[U][0-9]{2,30}$/", $perfectmoney)) {

				$data['error'] = 'true';
				$data['message'] = 'Укажите номер своего кошелька PerfectMoney в формате U0000000.';

			}

		}

	} elseif (isset($_POST['card'])) {
			
		$card = test_request($_POST['card']);
		$data['name'] = 'card';

		if (!empty($card)) {

			if (!preg_match("/^[0-9]{16,16}$/", $card)) {

				$data['error'] = 'true';
				$data['message'] = 'Номер карты должен состоять из 16 цифир.';

			}

		}

	} elseif (isset($_POST['old_password'])) {
			
		$old_password = test_request($_POST['old_password']);
		$data['name'] = 'old_password';

		$salt = $user['salt'];
		$old_password = md5(md5($old_password) . $salt);

		if (!empty($user['password'])) {

			if (!empty($old_password)) {

				if (strlen(utf8_decode($old_password)) >= 6) {

					if ($old_password != $user['password']) {

						$data['error'] = 'true';
						$data['message'] = 'Неправильный старый пароль.';

					}

				} else {

					$data['error'] = 'true';
					$data['message'] = $register_page_error_message_8;

				}

			} else {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_7;

			}

		} else {

			$data['error'] = 'true';
			$data['message'] = 'Вы зарегистрированы с помощь социальной сети и еще не указывали пароль. Чтобы указать пароль к акаунту, произведите возобновление акаунта - <a href="/logout/?remind">Возобновить</a>.';

		}

	} elseif (isset($_POST['again_new_password']) and isset($_POST['new_password'])) {
			
		$again_new_password = test_request($_POST['again_new_password']);
		$new_password = test_request($_POST['new_password']);
		$data['name'] = 'again_new_password';

		if (empty($again_new_password)) {

			$data['error'] = 'true';
			$data['message'] = $register_page_error_message_7;

		} else {

			if (strlen(utf8_decode($again_new_password)) < 6) {

				$data['error'] = 'true';
				$data['message'] = $register_page_error_message_8;

			} else {

				if ($again_new_password != $new_password) {

					$data['error'] = 'true';
					$data['message'] = 'Пароль не совпадает с новым паролем.';

				}

			}

		}

	}

	echo json_encode($data);

}

?>