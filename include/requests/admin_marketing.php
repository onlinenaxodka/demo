<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$act = (isset($_POST['act'])) ? mysqli_real_escape_string($db, $_POST['act']) : '';
		$act = test_request($act);
		$act = strval($act);

		$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
		$id = test_request($id);
		$id = intval($id);

		$dropshipper = (isset($_POST['dropshipper'])) ? mysqli_real_escape_string($db, $_POST['dropshipper']) : '';
		$dropshipper = test_request($dropshipper);
		$dropshipper = intval($dropshipper);

		$manager = (isset($_POST['manager'])) ? mysqli_real_escape_string($db, $_POST['manager']) : '';
		$manager = test_request($manager);
		$manager = intval($manager);

		$supervisor = (isset($_POST['supervisor'])) ? mysqli_real_escape_string($db, $_POST['supervisor']) : '';
		$supervisor = test_request($supervisor);
		$supervisor = intval($supervisor);

		$director = (isset($_POST['director'])) ? mysqli_real_escape_string($db, $_POST['director']) : '';
		$director = test_request($director);
		$director = intval($director);

		$roma = (isset($_POST['roma'])) ? mysqli_real_escape_string($db, $_POST['roma']) : '';
		$roma = test_request($roma);
		$roma = intval($roma);

		$zgenia = (isset($_POST['zgenia'])) ? mysqli_real_escape_string($db, $_POST['zgenia']) : '';
		$zgenia = test_request($zgenia);
		$zgenia = intval($zgenia);

		$tema = (isset($_POST['tema'])) ? mysqli_real_escape_string($db, $_POST['tema']) : '';
		$tema = test_request($tema);
		$tema = intval($tema);

		$sasha = (isset($_POST['sasha'])) ? mysqli_real_escape_string($db, $_POST['sasha']) : '';
		$sasha = test_request($sasha);
		$sasha = intval($sasha);

		$dima = (isset($_POST['dima'])) ? mysqli_real_escape_string($db, $_POST['dima']) : '';
		$dima = test_request($dima);
		$dima = intval($dima);

		$adminon = (isset($_POST['adminon'])) ? mysqli_real_escape_string($db, $_POST['adminon']) : '';
		$adminon = test_request($adminon);
		$adminon = intval($adminon);

		$fond = (isset($_POST['fond'])) ? mysqli_real_escape_string($db, $_POST['fond']) : '';
		$fond = test_request($fond);
		$fond = intval($fond);

		$amount = $dropshipper + $manager + $supervisor + $director + $roma + $zgenia + $tema + $sasha + $dima + $adminon + $fond;

		$error = false;

		if ($amount != 100) {

			$error = true;

		}

		if ($act == 'add') {

			/*$sql = "INSERT INTO `table` SET `updated`='{$current_date}', `created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

		} elseif ($act == 'edit') {

			if (!$error) {

				$sql = "UPDATE `marketing` SET `dropshipper`='{$dropshipper}',
												`manager`='{$manager}',
												`supervisor`='{$supervisor}',
												`director`='{$director}',
												`roma`='{$roma}',
												`zgenia`='{$zgenia}',
												`tema`='{$tema}',
												`dima`='{$dima}',
												`adminon`='{$adminon}',
												`fond`='{$fond}',
												`sasha`='{$sasha}' WHERE `id`='{$id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='marketing',
														`id_row`='{$id}',
														`action`='Редактировал товарный маркетинг',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

		} elseif ($act == 'delete') {

			/*$sql = "DELETE FROM `table` WHERE `id`='{$id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

		}

		if (!$error) {

			header('Location: /admin/marketing/');
			exit;

		} else {

			header('Location: /admin/marketing/?error');
			exit;

		}		

	}

}

?>