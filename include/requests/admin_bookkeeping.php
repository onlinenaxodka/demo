<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$order_id = (isset($_POST['order'])) ? mysqli_real_escape_string($db, $_POST['order']) : '';
		$order_id = test_request($order_id);
		$order_id = intval($order_id);

		

	}

}

?>