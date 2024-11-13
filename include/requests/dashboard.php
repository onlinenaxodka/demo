<?php

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$visitwebinarolx = (isset($_POST['visitwebinarolx'])) ? mysqli_real_escape_string($db, $_POST['visitwebinarolx']) : '';
		$visitwebinarolx = test_request($visitwebinarolx);
		$visitwebinarolx = intval($visitwebinarolx);

		$visitanelkinkurs = (isset($_POST['visitanelkinkurs'])) ? mysqli_real_escape_string($db, $_POST['visitanelkinkurs']) : '';
		$visitanelkinkurs = test_request($visitanelkinkurs);
		$visitanelkinkurs = intval($visitanelkinkurs);

		$visitrozetkakurs = (isset($_POST['visitrozetkakurs'])) ? mysqli_real_escape_string($db, $_POST['visitrozetkakurs']) : '';
		$visitrozetkakurs = test_request($visitrozetkakurs);
		$visitrozetkakurs = intval($visitrozetkakurs);

		if ($visitwebinarolx > 0) {

			$sql = "INSERT INTO `orders_kurs` SET `user_id`='{$user_id}',
													`kurs`='webinar',
													`name`='{$user['name']}',
													`phone`='{$user['phone']}',
													`email`='{$user['mail']}',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /account/?success');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Техническая неполадка. Перезагрузите страницу.</div>';

		}

		if ($visitanelkinkurs > 0) {

			$sql = "INSERT INTO `orders_kurs` SET `user_id`='{$user_id}',
													`kurs`='anelkinkurs',
													`name`='{$user['name']}',
													`phone`='{$user['phone']}',
													`email`='{$user['mail']}',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /account/?success_a_kurs');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Техническая неполадка. Перезагрузите страницу.</div>';

		}

		if ($visitrozetkakurs > 0) {

			$sql = "INSERT INTO `orders_kurs` SET `user_id`='{$user_id}',
													`kurs`='rozetka',
													`name`='{$user['name']}',
													`phone`='{$user['phone']}',
													`email`='{$user['mail']}',
													`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /account/?success_rozetka_kurs');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Техническая неполадка. Перезагрузите страницу.</div>';

		}

	}

}

if (isset($_GET['success'])) {

	$alert_message = '<div class="alert alert-success" role="alert"><h2><strong>Поздравляем!</strong> Вы успешно оформили заявку на вебинар. В новой вкладке у вас должна была открыться страница для перехода на вебинар. Вы можете ее открыть еще по этой кнопке <a href="https://businessdoski.com.ua/olx_web/" class="btn btn-success btn-sm" target="_blank">Открыть</a></h2></div>';

} elseif (isset($_GET['success_a_kurs'])) {

	$alert_message = '<div class="alert alert-success" role="alert"><h2><strong class="text-danger">Поздравляем!</strong> Вы успешно оформили заявку на курс Дмитрия. В скором времени с Вами свяжется наш менеджер и уточнит детали.</h2></div>';

} elseif (isset($_GET['success_rozetka_kurs'])) {

	$alert_message = '<div class="alert alert-success" role="alert"><h2><strong class="text-danger">Поздравляем!</strong> Вы успешно оформили заявку на курс по ROZETKA. Для бронирование и оплаты места пишите нашему менеджеру прямо сейчас в наш ЧАТ справа внизу есть кнопка</h2></div>';

}

?>