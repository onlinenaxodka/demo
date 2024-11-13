<?php

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		/*$amount_invest = (isset($_POST['amount_invest'])) ? mysqli_real_escape_string($db, $_POST['amount_invest']) : '';
		$amount_invest = test_request($amount_invest);
		$amount_invest = intval($amount_invest);

		if ($amount_invest > 0) {

			$sql = "INSERT INTO `orders_kurs` SET `user_id`='{$user_id}',
													`kurs`='suma{$amount_invest}',
													`name`='{$user['name']}',
													`phone`='{$user['phone']}',
													`email`='{$user['mail']}',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /account/investor_club/?success');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Техническая неполадка. Перезагрузите страницу.</div>';

		}*/

	}

}

if (isset($_GET['success'])) {

	/*$alert_message = '<div class="alert alert-success" role="alert"><h2><strong class="text-danger">Поздравляем!</strong> Вы успешно оформили заявку на Инвестиционный проект №1. В скором времени с Вами свяжется наш менеджер и уточнит детали.</h2></div>';*/

}

?>