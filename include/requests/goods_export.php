<?php

$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$kurs = (isset($_POST['kurs'])) ? mysqli_real_escape_string($db, $_POST['kurs']) : '';
		$kurs = test_request($kurs);

		

	}

}

?>