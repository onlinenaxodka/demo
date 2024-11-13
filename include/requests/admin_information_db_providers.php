<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$act = (isset($_POST['act'])) ? mysqli_real_escape_string($db, $_POST['act']) : '';
		$act = test_request($act);
		$act = strval($act);

		$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
		$id = test_request($id);
		$id = intval($id);

		$question = (isset($_POST['question'])) ? mysqli_real_escape_string($db, $_POST['question']) : '';
		$question = test_request($question);
		$question = str_replace("'", "\'", $question);

		$answer = (isset($_POST['answer'])) ? mysqli_real_escape_string($db, $_POST['answer']) : '';

		$whom = (isset($_POST['whom'])) ? mysqli_real_escape_string($db, $_POST['whom']) : '';
		$whom = test_request($whom);
		$whom = intval($whom);

		$sort = (isset($_POST['sort'])) ? mysqli_real_escape_string($db, $_POST['sort']) : '';
		$sort = test_request($sort);
		$sort = intval($sort);

		if ($sort == 0) {

			$sql = "SELECT `id` FROM `db_providers` WHERE `whom`='{$whom}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$faq_whom_count = mysqli_num_rows($query);

			$sort = $faq_whom_count + 1;

		}

		if ($act == 'add') {

			$sql = "INSERT INTO `db_providers` SET `question`='{$question}',
											`answer`='{$answer}',
											`whom`='{$whom}',
											`lang`='ru',
											`sort`='{$sort}',
											`status`=1,
											`updated`='{$current_date}',
											`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		} elseif ($act == 'edit') {

			$sql = "UPDATE `db_providers` SET `question`='{$question}',
										`answer`='{$answer}',
										`whom`='{$whom}',
										`lang`='ru',
										`sort`='{$sort}',
										`status`=1,
										`updated`='{$current_date}' WHERE `id`='{$id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		} elseif ($act == 'delete') {

			$sql = "DELETE FROM `db_providers` WHERE `id`='{$id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		} elseif ($act == 'filter') {

			if ($whom == 0) {

				if (!empty($_SESSION['faq_filter'])) {

					unset($_SESSION['faq_filter']);

				}

			} else {

				$_SESSION['faq_filter'] = $whom;

			}

		}

		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;

	}

}

?>