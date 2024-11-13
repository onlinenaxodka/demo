<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			$phone = (isset($_POST['phone'])) ? mysqli_real_escape_string($db, $_POST['phone']) : '';
			$phone = test_request($phone);

			$data['error'] = 'false';
			$data['message'] = '';

			if (empty($phone)) {

				$data['error'] = 'true';
				$data['message'] = 'Поле Телефон обязательное, оно не должно быть пустым.';

			} else {

				if (!preg_match("/^[+][0-9]{12,12}$/", $phone)) {

					$data['error'] = 'true';
					$data['message'] = 'Неверный формат телефона, введите в формате +38098*******.'.$phone;

				}

			}

			if ($data['error'] == 'false') {

				$sql = "UPDATE `users` SET `phone`='{$phone}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			echo json_encode($data);

		}

	}

}