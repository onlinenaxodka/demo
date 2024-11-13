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

	if (mysqli_num_rows($query) > 0) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if (!empty($_POST)) {

				$sub_social = (isset($_POST['ss'])) ? mysqli_real_escape_string($db, $_POST['ss']) : '';
				$sub_social = test_request($sub_social);
				$sub_social = intval($sub_social);				

				if ($sub_social == 1) {

					$sql = "UPDATE `users` SET `sub_social`=1, `updated`='{$current_date}' WHERE `id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

			}

		}

	}

}