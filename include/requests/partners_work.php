<?php

switch ($user_id) {
	case 2:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	case 4:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	case 5:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	case 19:
		$user_selected_allowed = array(19);
		break;
	case 28:
		$user_selected_allowed = array(28);
		break;
	case 30:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	case 340:
		$user_selected_allowed = array(340);
		break;
	case 342:
		$user_selected_allowed = array(342);
		break;
	case 348:
		$user_selected_allowed = array(348);
		break;
	case 397:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	case 712:
		$user_selected_allowed = array(712);
		break;
	case 1000:
		$user_selected_allowed = array(1000);
		break;
	case 1144:
		$user_selected_allowed = array(1144);
		break;
	case 2041:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
	default:
		$user_selected_allowed = array(2, 4, 5, 19, 28, 30, 340, 342, 348, 397);
		break;
}



$user_selected_team = 397;
if ($user_id == 19) $user_selected_team = 19;
if ($user_id == 28) $user_selected_team = 28;
if ($user_id == 340) $user_selected_team = 340;
if ($user_id == 342) $user_selected_team = 342;
if ($user_id == 348) $user_selected_team = 348;
if ($user_id == 712) $user_selected_team = 712;
if ($user_id == 1000) $user_selected_team = 1000;
if ($user_id == 1144) $user_selected_team = 1144;

if (!empty($_SESSION['user_selected'])) {

	if (in_array($_SESSION['user_selected'], $user_selected_allowed)) {
	
		$user_selected_team = $_SESSION['user_selected'];

	}

}

if ($user['employee'] == 0) {
	header('Location: /account/partners/');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST) and $user['employee'] == 1) {

		$user_selected_cur = (isset($_POST['user_selected'])) ? mysqli_real_escape_string($db, $_POST['user_selected']) : '';
		$user_selected_cur = test_request($user_selected_cur);
		$user_selected_cur = intval($user_selected_cur);

		$child_id = (isset($_POST['child'])) ? mysqli_real_escape_string($db, $_POST['child']) : '';
		$child_id = test_request($child_id);
		$child_id = intval($child_id);

		$comment = (isset($_POST['comment'])) ? mysqli_real_escape_string($db, $_POST['comment']) : '';
		$comment = str_replace('\r\n', '\\\r\\\n', $comment);
		$comment = test_request($comment);

		$date = (isset($_POST['date'])) ? mysqli_real_escape_string($db, $_POST['date']) : '';
		$date = test_request($date);

		$notcall = (isset($_POST['notcall'])) ? mysqli_real_escape_string($db, $_POST['notcall']) : '';
		$notcall = test_request($notcall);
		$notcall = intval($notcall);

		$homework_id = (isset($_POST['homework_id'])) ? mysqli_real_escape_string($db, $_POST['homework_id']) : '';
		$homework_id = test_request($homework_id);
		$homework_id = intval($homework_id);

		$homework_act = (isset($_POST['homework_act'])) ? mysqli_real_escape_string($db, $_POST['homework_act']) : '';
		$homework_act = test_request($homework_act);
		$homework_act = intval($homework_act);

		if (in_array($user_selected_cur, $user_selected_allowed)) {

			$_SESSION['user_selected'] = $user_selected_cur;

		}

		if ($child_id > 0) {

			$sql_child = "SELECT `partner_id` FROM `users` WHERE `id`='{$child_id}'";
			$query_child = mysqli_query($db, $sql_child) or die(mysqli_error($db));
			$child = mysqli_fetch_assoc($query_child);

			if ($child['partner_id'] == $user_selected_team) {

				if (!empty($comment)) {

					$audio_name = '';
					$audio_name_old = '';

					if (!empty($_FILES['audio']['name'])) {

						if ($_FILES['audio']['type'] == 'audio/mp3') {

							$uploaddir = __DIR__ . '/../../assets/files/mp3/users_comments/';
							$filename = time() . '.' . substr(strrchr($_FILES['audio']['name'], '.'), 1);
										
							$uploadfile = $uploaddir.$filename;

							move_uploaded_file($_FILES['audio']['tmp_name'], $uploadfile);

							$audio_name = $filename;
							$audio_name_old = $_FILES['audio']['name'];

						}

					}

					$sql = "INSERT INTO `users_comments` SET `user_id`='{$user_id}',
																`child_id`='{$child_id}',
																`comment`=\"{$comment}\",
																`audio_name`='{$audio_name}',
																`audio_name_old`=\"{$audio_name_old}\",
																`updated`='{$current_date}',
																`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				if (!empty($date)) {

					$date = date('Y-m-d', strtotime($date));

					$sql = "UPDATE `users` SET `notify`='{$date}', `notcall`='{$notcall}', `updated`='{$current_date}' WHERE `id`='{$child_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				if ($homework_id > 0 and ($homework_act == 1 or $homework_act == 2)) {

					$sql = "SELECT `id` FROM `school_homework` WHERE `id`='{$homework_id}' AND `user_id`='{$child_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					
					if (mysqli_num_rows($query) > 0) {

						$sql = "UPDATE `school_homework` SET `status`='{$homework_act}', `updated`='{$current_date}' WHERE `id`='{$homework_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

				}

			}

		}

		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;

	}

}

if (!empty($_GET['user_selected'])) {

	$user_selected = intval($_GET['user_selected']);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_selected}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_selected_breadcrumb = mysqli_fetch_assoc($query);

	$breadcrumb['names'][] = $user_selected_breadcrumb['name'].' '.$user_selected_breadcrumb['surname'];
	$breadcrumb['links'][] = '';

}

?>