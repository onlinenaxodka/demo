<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$filter_number = (isset($_POST['filter_number'])) ? mysqli_real_escape_string($db, $_POST['filter_number']) : '';
		$filter_number = test_request($filter_number);
		$filter_number = intval($filter_number);

		$filter_status = (isset($_POST['filter_status'])) ? mysqli_real_escape_string($db, $_POST['filter_status']) : '';
		$filter_status = test_request($filter_status);

		$filter_action = (isset($_POST['filter_action'])) ? mysqli_real_escape_string($db, $_POST['filter_action']) : '';
		$filter_action = test_request($filter_action);

		$filter_date_from = (isset($_POST['filter_date_from'])) ? mysqli_real_escape_string($db, $_POST['filter_date_from']) : '';
		$filter_date_from = test_request($filter_date_from);

		$filter_date_to = (isset($_POST['filter_date_to'])) ? mysqli_real_escape_string($db, $_POST['filter_date_to']) : '';
		$filter_date_to = test_request($filter_date_to);

		$filter_user = (isset($_POST['filter_user'])) ? mysqli_real_escape_string($db, $_POST['filter_user']) : '';
		$filter_user = test_request($filter_user);

		$filter_type = (isset($_POST['filter_type'])) ? mysqli_real_escape_string($db, $_POST['filter_type']) : '';
		$filter_type = test_request($filter_type);

		$filter_task = (isset($_POST['filter_task'])) ? mysqli_real_escape_string($db, $_POST['filter_task']) : '';
		$filter_task = test_request($filter_task);
		$filter_task = intval($filter_task);

		$clear_filter = (isset($_POST['clear_filter'])) ? mysqli_real_escape_string($db, $_POST['clear_filter']) : '';
		$clear_filter = test_request($clear_filter);
		$clear_filter = intval($clear_filter);

		if ($clear_filter == 1) {
			
			if (!empty($_SESSION['transaction_filter'])) {

				unset($_SESSION['transaction_filter']);

				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			}

		}

		if (!empty($_SESSION['transaction_filter'])) unset($_SESSION['transaction_filter']);

		if ($filter_number > 0) {
			
			$_SESSION['transaction_filter']['number'] = $filter_number;

		}

		if ($filter_status != '' and $filter_status != 'none') {
			
			$filter_status = intval($filter_status);
			$_SESSION['transaction_filter']['status'] = $filter_status;
			
		}

		if (!empty($filter_action)) {
			
			$_SESSION['transaction_filter']['action'] = $filter_action;

		}

		if (!empty($filter_date_from)) {
			
			$filter_date_from = date('Y-m-d', strtotime($filter_date_from));
			$_SESSION['transaction_filter']['date_from'] = $filter_date_from;

		}

		if (!empty($filter_date_to)) {
			
			$filter_date_to = date('Y-m-d', strtotime($filter_date_to));
			$_SESSION['transaction_filter']['date_to'] = $filter_date_to;

		}

		if (!empty($filter_user) and $filter_user != 'none') {

			$filter_user = intval($filter_user);
			$_SESSION['transaction_filter']['user'] = $filter_user;
			
		}

		if ($filter_type != '' and $filter_type != 'none') {
			
			$filter_type = intval($filter_type);
			$_SESSION['transaction_filter']['type'] = $filter_type;
			
		}

		if ($filter_task > 0) {
			
			$_SESSION['transaction_filter']['task'] = $filter_task;

		}

		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;

	}

}

/*if ($_SERVER["REQUEST_METHOD"] == "GET") {

	if (!empty($_GET)) {

		$search = (isset($_GET['search'])) ? mysqli_real_escape_string($db, $_GET['search']) : '';
		$search = test_request($search);
		
		$date = (isset($_GET['date'])) ? mysqli_real_escape_string($db, $_GET['date']) : '';
		$date = test_request($date);

	}

}*/

?>