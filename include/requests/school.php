<?php

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$kurs = (isset($_POST['kurs'])) ? mysqli_real_escape_string($db, $_POST['kurs']) : '';
		$kurs = test_request($kurs);

		$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
		$name = str_replace("'", '\'', $name);
		$name = test_request($name);

		$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
		$phone = test_request($phone);

		if (!empty($kurs) and !empty($name) and !empty($phone)) {

			$sql = "INSERT INTO `orders_kurs` SET `user_id`='{$user_id}',
													`kurs`='{$kurs}',
													`name`='{$name}',
													`phone`='{$phone}',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /account/school/?success');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Техническая неполадка. Перезагрузите страницу.</div>';

		}

	}

}

if (isset($_GET['success'])) {

	$alert_message = '<div class="alert alert-success" role="alert"><strong>Поздравляем!</strong> Вы успешно оформили заявку на курс. В скором времени с вами свяжется наш менеджер.</div>';

}

?>