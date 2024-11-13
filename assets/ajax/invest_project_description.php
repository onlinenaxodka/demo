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

			$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
			$id = test_request($id);
			if(is_numeric($id)) $id = intval($id);
			else $id = 0;
				
			if ($id > 0) {

				$sql = "SELECT * FROM `invest_project_config` WHERE `id`='{$id}' AND `status`=1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$invest_project_config = mysqli_fetch_assoc($query);

				echo $invest_project_config['description'];

			}

		}

	}

}