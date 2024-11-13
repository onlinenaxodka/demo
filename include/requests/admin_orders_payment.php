<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$act = (isset($_POST['act'])) ? mysqli_real_escape_string($db, $_POST['act']) : '';
		$act = test_request($act);

		$orderids = (isset($_POST['orderids'])) ? mysqli_real_escape_string($db, $_POST['orderids']) : '';
		$orderids = test_request($orderids);
		$orderids = strval($orderids);
		
		if ($act == 'orders_paid' && !empty($orderids)) {

			$sql = "UPDATE `orders` SET `status_provider`=2, `updated`='{$current_date}' WHERE `id` IN ({$orderids})";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;

	}

}

?>