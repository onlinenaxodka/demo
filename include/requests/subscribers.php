<?php

if ($user['status'] == 0) {
	header('Location: /account/');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$edit_id = (isset($_POST['edit_id'])) ? mysqli_real_escape_string($db, $_POST['edit_id']) : '';
		$edit_id = test_request($edit_id);
		$edit_id = intval($edit_id);

		$site = (isset($_POST['site'])) ? mysqli_real_escape_string($db, $_POST['site']) : '';
		$site = test_request($site);

		$description = (isset($_POST['description'])) ? mysqli_real_escape_string($db, $_POST['description']) : '';
		$description = str_replace('\r\n', '\\\r\\\n', $description);
		$description = test_request($description);

		$status = (isset($_POST['status'])) ? mysqli_real_escape_string($db, $_POST['status']) : '';
		$status = test_request($status);
		$status = intval($status);

		if ($edit_id > 0) {

			$sql = "UPDATE `subscribers` SET 
											`site`=\"{$site}\",
											`description`=\"{$description}\",
											`status`='{$status}' WHERE `id`='{$edit_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			header('Location: /account/subscribers/');
	    	exit;

		}

	}

}

?>