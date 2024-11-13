<?php

session_start();

include_once __DIR__ . '/../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT `id` FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);
	$user_id = $user['id'];

	if (mysqli_num_rows($query) != 0) {

		require_once('slim.php');

		// get posted data, if something is wrong, exit
		try {
		    $images = Slim::getImages();
		}
		catch (Exception $e) {
		    Slim::outputJSON(SlimStatus::Failure);
		    return;
		}

		// if no images found
		if (count($images)===0) {
		    Slim::outputJSON(SlimStatus::Failure);
		    return;
		}

		// should always be one file (when posting async)
		$image = $images[0];
		$file = Slim::saveFile($image['output']['data'], $image['input']['name'], $user_id);

		// echo results
		Slim::outputJSON(SlimStatus::Success, $file['name'], $file['path']);

	}

}