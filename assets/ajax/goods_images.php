<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';
include_once __DIR__ . '/../../include/libs/classSimpleImage.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_FILES)) {

			if ($user['admin'] == 1 or $user['admin'] == 2) {

				if (!empty($_FILES['photo']['name'])) {

					if ($_FILES['photo']['type'] == 'image/jpg' or $_FILES['photo']['type'] == 'image/jpeg' or $_FILES['photo']['type'] == 'image/png' or $_FILES['photo']['type'] == 'image/gif' or $_FILES['photo']['type'] == 'image/bmp') {

						$image = new SimpleImage();

						$filename = time() . '.' . substr(strrchr($_FILES['photo']['name'], '.'), 1);
							
							$image->load($_FILES['photo']['tmp_name']);

							if ($image->getWidth() >= $image->getHeight()) {

								if ($image->getWidth() > 1024) {

									$image->resizeToWidth(1024);

								}

							} else {

								if ($image->getHeight() > 1024) {

									$image->resizeToHeight(1024);

								}

							}

							$uploaddir = __DIR__ . '/../../data/images/goods/';

							$image->save($uploaddir.$filename);



							$image_thumb = new SimpleImage();

							$image_thumb->load($_FILES['photo']['tmp_name']);
							
							if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {

								if ($image_thumb->getWidth() > 256) {

									$image_thumb->resizeToWidth(256);

								}

							} else {

								if ($image_thumb->getHeight() > 256) {

									$image_thumb->resizeToHeight(256);

								}

							}

							$uploaddir = __DIR__ . '/../../data/images/goods_thumb/';

							$image_thumb->save($uploaddir.$filename);

							
							echo $filename;
							
							
						}

				}

			}

		} elseif (!empty($_POST)) {
			
			$name_img = (isset($_POST['name_img'])) ? mysqli_real_escape_string($db, $_POST['name_img']) : '';
			$name_img = test_request($name_img);

			if ($name_img != 'no_image.png') {

				$filename = __DIR__ . '/../images/goods/' . $name_img;

				if (file_exists($filename)) unlink($filename);

				$filename = __DIR__ . '/../images/goods_thumb/' . $name_img;

				if (file_exists($filename)) unlink($filename);
				
			}

			echo 'success';

		}

	}

}

?>