<?

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';
include_once __DIR__ . '/../../include/lang_files.php';

if (isset($_SESSION['user'])) exit();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (!empty($_SESSION['partner_id'])) {

			$partner_id = (isset($_SESSION['partner_id'])) ? mysqli_real_escape_string($db, $_SESSION['partner_id']) : '';
			$partner_id = test_request($partner_id);
			$partner_id = intval($partner_id);

			if ($partner_id == 0) $partner_id = 1;
			
		}

		$screen = (isset($_POST['screen'])) ? mysqli_real_escape_string($db, $_POST['screen']) : '';
		$screen = test_request($screen);
		$screen = intval($screen);

		$calc = (isset($_POST['calc'])) ? mysqli_real_escape_string($db, $_POST['calc']) : '';
		$calc = test_request($calc);
		$calc = intval($calc);

		$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
		$name = test_request($name);

		$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
		$email = test_request($email);

		$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
		$phone = test_request($phone);

		$error = false;
		$error_message = '';

		if (!empty($name)) {

			if (strlen(utf8_decode($name)) < 2) {

				$error = true;
				$error_message .= 'Введите не менее 2-х символов в поле Имя. ';

			}

		} else {

			$error = true;
			$error_message .= 'Поле Имя обязательное, оно не должно быть пустым. ';

		}

		if (!empty($email)) {

			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

				$sql = "SELECT `id` FROM `subscribers` WHERE `email`='{$email}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				if (mysqli_num_rows($query) == 1) {

					$error = true;
					$error_message = 'Этот E-mail уже зарегистрирован, введите другой. ';

				}

			} else {

				$error = true;
				$error_message .= 'Введите корректный E-mail. ';

			}

		} else {

			$error = true;
			$error_message .= 'Поле E-mail обязательное, оно не должно быть пустым. ';

		}

		if (!empty($phone)) {

			if (!preg_match("/^[+]38\([0-9]{3}\)\s[0-9]{3}\s[0-9]{4}$/", $phone)) {

				$error = true;
				$error_message .= 'Укажите телефон в формате +38 (098) 765 4321 минимум 3 цифры.<br>';

			}

		} else {

			$error = true;
			$error_message .= 'Поле Телефон обязательное, оно не должно быть пустым. ';

		}

		if (!$error) {

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "http://freegeoip.net/json/".$ip);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($curl);
			curl_close($curl);
			$country = json_decode($response, true);

			if ($country) {

				if (!empty($country['country_code'])) {

					$geo = $country['country_code'].", ".$country['country_name'].", ".$country['region_name'].", ".$country['city'];

				}

			}

			require_once __DIR__ . '/../../include/vendor/autoload.php';

			/*$sql = "SELECT `count_partners` FROM `users` WHERE `id`=4";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user_evgen = mysqli_fetch_assoc($query);

			$sql = "SELECT `count_partners` FROM `users` WHERE `id`=5";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user_artem = mysqli_fetch_assoc($query);

			if ($user_evgen['count_partners'] <= $user_artem['count_partners']) {

				$user_count_partners = $user_evgen['count_partners']+1;
				$api_key = "45666b283d3bb57447e89046e95dcd34";
				$group_id = 9183968;
				$land_user_id = 4;

			} else {
				
				$user_count_partners = $user_artem['count_partners']+1;
				$api_key = "def048f2a589d4fe12dc9b5453139578";
				$group_id = 8350976;
				$land_user_id = 5;

			}*/

			switch ($partner_id) {
				case 2:
					$api_key = "0e77cc97f07b9bd8ade6c8ef050f8bc1";
					$group_id = 7958197;
					break;
				case 4:
					$api_key = "45666b283d3bb57447e89046e95dcd34";
					$group_id = 9183968;
					//$land_user_id = 1;
					//$admin_id = 0;
					break;
				case 5:
					$api_key = "def048f2a589d4fe12dc9b5453139578";
					$group_id = 8350976;
					//$land_user_id = 1;
					//$admin_id = 0;
					break;
				case 19:
					$api_key = "8a5ca9acb7acd7534c7ea6bef241235d";
					$group_id = 9553472;
					break;
				case 30:
					$api_key = "3dcf9eaa5d68e078c99d284e6476416f";
					$group_id = 9554200;
					break;
				default:
					$api_key = "96b416bcd17841a061642a24fbe0f21b";
					$group_id = 9554928;
					//$partner_id = $land_user_id;
					//$admin_id = 1;
				break;
			}

			$groupsApi = (new MailerLiteApi\MailerLite($api_key))->groups();

			$subscriber = [
			    'email' => $email,
			    'fields' => [
			        'name' => $name,
			        'company' => 'ONLINE NAXODKA'
			    ]
			];

			$response = $groupsApi->addSubscriber($group_id, $subscriber);

			if (!$response->error) {

				$sql = "INSERT INTO `subscribers` SET 	`user_id`='{$partner_id}',
														`name`=\"{$name}\",
														`phone`='{$phone}',
														`email`='{$email}',
														`ip`='{$ip}',
														`geo`=\"{$geo}\",
														`screen`='{$screen}',
														`calc`='{$calc}',
														`updated`='{$current_date}',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				/*if ($land_user_id == 4 or $land_user_id == 5) {
					$sql = "UPDATE `users` SET `count_partners`='{$user_count_partners}', `updated`='{$current_date}' WHERE `id`='{$land_user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
				}*/	

				$data['error'] = 'false';
				$data['title'] = 'Вы успешно подписались на платформу Online Naxodka!';
				$data['message'] = '<h4>Сейчас вам нужно подтвердить свой электронный адресс.</h4><br>Нажмите на кнопу подтверждения в письме, которое мы вам только что отправили на почту '.$email.'. Если не найдете письма в почте, проверьте Спам.<p><a href="//'.substr($email, strrpos($email, '@')+1).'" class="btn btn-success mt-3" target="_blank">Проверить почту</a></p>';
			} else {

				$data['error'] = 'true';
				$data['title'] = 'Ошибка!';
				$data['message'] = 'Данные не отправлены, попробуйте позже или через другой браузер.';

			}

		} else {

			$data['error'] = 'true';
			$data['title'] = 'Ошибка!';
			$data['message'] = $error_message;

		}

	}

}

echo json_encode($data);

?>