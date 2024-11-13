<?php

/*$mentor_id = $user_id;
$level = 1;*/

/*if ($user['status'] == 0) {
	header('Location: /account/');
    exit;
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$child_id = (isset($_POST['child'])) ? mysqli_real_escape_string($db, $_POST['child']) : '';
		$child_id = test_request($child_id);
		$child_id = intval($child_id);

		$comment = (isset($_POST['comment'])) ? mysqli_real_escape_string($db, $_POST['comment']) : '';
		$comment = str_replace('\r\n', '\\\r\\\n', $comment);
		$comment = test_request($comment);

		$date = (isset($_POST['date'])) ? mysqli_real_escape_string($db, $_POST['date']) : '';
		$date = test_request($date);

		$homework_act = (isset($_POST['homework_act'])) ? mysqli_real_escape_string($db, $_POST['homework_act']) : '';
		$homework_act = test_request($homework_act);
		$homework_act = intval($homework_act);

		$homework_id = (isset($_POST['homework_id'])) ? mysqli_real_escape_string($db, $_POST['homework_id']) : '';
		$homework_id = test_request($homework_id);
		$homework_id = intval($homework_id);

		if ($child_id > 0 and !empty($comment)) {

			$sql_child = "SELECT * FROM `users` WHERE `id`='{$child_id}'";//tmp
			$query_child = mysqli_query($db, $sql_child) or die(mysqli_error($db));//tmp
			$child = mysqli_fetch_assoc($query_child);//tmp

			if ($user['employee'] == 1 and $child['partner_id'] != $user_id) $user_id = $user['partner_id'];//tmp

			$sql = "INSERT INTO `users_comments` SET `user_id`='{$user_id}',
														`child_id`='{$child_id}',
														`comment`=\"{$comment}\",
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			if ($user['employee'] == 1 and $child['partner_id'] != $user['id']) $user_id = $user['id'];//tmp

		}

		if ($child_id > 0 and !empty($date)) {

			$date = date('Y-m-d', strtotime($date));

			$sql = "UPDATE `users` SET `notify`='{$date}', `updated`='{$current_date}' WHERE `id`='{$child_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

		if ($child_id > 0 and ($homework_act == 1 or $homework_act == 2) and $homework_id > 0) {

			$sql = "SELECT `partner_id` FROM `users` WHERE `id`='{$child_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$child = mysqli_fetch_assoc($query);

			if ($user['employee'] == 1 and $child['partner_id'] != $user_id) $user_id = $user['partner_id'];//tmp

			if ($child['partner_id'] == $user_id) {

				$sql = "SELECT `id` FROM `school_homework` WHERE `id`='{$homework_id}' AND `user_id`='{$child_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				
				if (mysqli_num_rows($query) > 0) {

					$sql = "UPDATE `school_homework` SET `status`='{$homework_act}', `updated`='{$current_date}' WHERE `id`='{$homework_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

			}

			if ($user['employee'] == 1 and $child['partner_id'] != $user['id']) $user_id = $user['id'];//tmp

		}

		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;

	}

}

/*if ($_SERVER["REQUEST_METHOD"] == "GET") {

	if (!empty($_GET)) {

		if (!empty($_GET['c'])) {

			$mentor_id = test_request($_GET['c']);
			$breadcrumb_mentors = array();

			function displayBreadcrumbMentors($db, $level, $mentor_id, $breadcrumb_mentors) {

				$sql = "SELECT * FROM `users` WHERE `partner_id`='{$mentor_id}' ORDER BY `created` DESC";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			$breadcrumb_mentors = displayBreadcrumbMentors($db, $level, $mentor_id);

			if (!empty($_SESSION['breadcrumb'])) {
				foreach ($_SESSION['breadcrumb'] as $session_breadcrumb_link => $session_breadcrumb_name) {
					$breadcrumb['names'][] = $session_breadcrumb_name;
					$breadcrumb['links'][] = '/account/goods/'.$session_breadcrumb_link;
				}
			}

		}

	}

}*/

?>