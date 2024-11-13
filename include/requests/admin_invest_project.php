<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		$act = (isset($_POST['act'])) ? mysqli_real_escape_string($db, $_POST['act']) : '';
		$act = test_request($act);

		$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
		$name = test_request($name);
		$name = str_replace("'", "\'", $name);

		$description = (isset($_POST['description'])) ? mysqli_real_escape_string($db, $_POST['description']) : '';

		$amount = (isset($_POST['amount'])) ? mysqli_real_escape_string($db, $_POST['amount']) : '';
		$amount = test_request($amount);
		$amount = floatval($amount);
		$amount = number_format($amount, 2, '.', '');

		$amount_on = (isset($_POST['amount_on'])) ? mysqli_real_escape_string($db, $_POST['amount_on']) : '';
		$amount_on = test_request($amount_on);
		$amount_on = floatval($amount_on);
		$amount_on = number_format($amount_on, 2, '.', '');

		$project_id = (isset($_POST['project_id'])) ? mysqli_real_escape_string($db, $_POST['project_id']) : '';
		$project_id = test_request($project_id);
		$project_id = intval($project_id);

		if ($project_id > 0) {

			$sql = "SELECT `id` FROM `invest_project_config` WHERE `id`='{$project_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$invest_project_config_count = mysqli_num_rows($query);

			if ($invest_project_config_count == 0) {

				$error = true;
				$error_message .= 'Такого проекта не существует. ';

			}

		}

		if ($act == 'add_project') {

			if (!empty($_FILES['imаgе']['name'])) {

				if ($_FILES['imаgе']['type'] != 'image/png') {

					$error = true;
					$error_message .= 'Формат изображения только png.<br>';
									
				}

			} else {

				$error = true;
				$error_message .= 'Поле изображение обязательное.<br>';

			}

			if (empty($name)) {

				$error = true;
				$error_message .= 'Название проекта обязательное. ';

			}

			if ($amount <= 0) {

				$error = true;
				$error_message .= 'Пороговая сумма инвестиций в проект обязательна. ';

			}

		} elseif ($act == 'edit_project') {

			 if ($project_id <= 0) {

				$error = true;
				$error_message .= 'Такого проекта не существует. ';

			}

			if (!empty($_FILES['imаgе']['name'])) {

				if ($_FILES['imаgе']['type'] != 'image/png') {

					$error = true;
					$error_message .= 'Формат изображения только png.<br>';
									
				}

			}

			if (empty($name)) {

				$error = true;
				$error_message .= 'Название проекта обязательное. ';

			}

			if ($amount <= 0) {

				$error = true;
				$error_message .= 'Пороговая сумма инвестиций в проект обязательна. ';

			}

		} elseif ($act == 'delete_project') {

			if ($project_id <= 0) {

				$error = true;
				$error_message .= 'Такого проекта не существует. ';

			}

		} elseif ($act == 'accrual_investments') {

			if ($project_id <= 0) {

				$error = true;
				$error_message .= 'Такого проекта не существует. ';

			}

			if ($amount < 0) {

				$error = true;
				$error_message .= 'Дивиденды для Online Naxodka не могут быть меньше 0. ';

			}

			if ($amount_on < 0) {

				$error = true;
				$error_message .= 'Дивиденды для инвесторов не могут быть меньше 0. ';

			}

		} else {

			$error = true;
			$error_message = 'Нет такого действия. ';

		}

		if (!$error) {

			if ($project_id > 0) {

				$sql = "SELECT * FROM `invest_project_config` WHERE `id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$invest_project_config = mysqli_fetch_assoc($query);

				$filename = $invest_project_config['image'];

			}

			if (!empty($_FILES['imаgе']['name'])) {

				if ($_FILES['imаgе']['type'] == 'image/png') {

					include_once __DIR__ . '/../libs/ImageResize.php';

					$uploaddir = __DIR__ . '/../../data/images/invest_project/';
					$filename = time() . '.' . substr(strrchr($_FILES['imаgе']['name'], '.'), 1);

					if ($project_id > 0) {

						$filename = $invest_project_config['image'];

					}

									
					$uploadfile = $uploaddir.$filename;

					move_uploaded_file($_FILES['imаgе']['tmp_name'], $uploadfile);

					if (file_exists($uploadfile)) {

						$image = new \Gumlet\ImageResize($uploadfile);


						if ($image->getSourceWidth() >= $image->getSourceHeight()) {

							if ($image->getSourceWidth() > 256) {

								$image->resizeToWidth(256);

							}

						} else {

							if ($image->getSourceHeight() > 256) {

								$image->resizeToHeight(256);

							}

						}

						$image->save($uploadfile);

					}
									
				}

			}

			if ($act == 'add_project') {

				$sql = "INSERT INTO `invest_project_config` SET `name`='{$name}',
																`description`='{$description}',
																`image`='{$filename}',
																`amount`='{$amount}',
																`updated`='{$current_date}',
																`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} elseif ($act == 'edit_project') {

				$sql = "UPDATE `invest_project_config` SET `name`='{$name}',
															`description`='{$description}',
															`image`='{$filename}',
															`amount`='{$amount}',
															`updated`='{$current_date}' WHERE `id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} elseif ($act == 'delete_project') {

				$filename = __DIR__ . '/../../data/images/invest_project/' . $invest_project_config['image'];

				if ($invest_project_config['image'] != 'no_image.png') {

					if (file_exists($filename)) {

						unlink($filename);

					}

				}

				$sql = "DELETE FROM `invest_project_config` WHERE `id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} elseif ($act == 'accrual_investments') {

				$sql = "SELECT * FROM `invest_project_config` WHERE `id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$invest_project_config = mysqli_fetch_assoc($query);

				$sql = "SELECT SUM(`amount`) AS sum_amount FROM `invest_project` WHERE `project_id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$invest_project = mysqli_fetch_assoc($query);
				$invest_project_sum_amount = $invest_project['sum_amount'];

				$sql = "SELECT * FROM `invest_project` WHERE `project_id`='{$project_id}' AND `amount`>0";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$invest_project_count = mysqli_num_rows($query);
				
				if ($invest_project_count > 0 and $amount > 0) {

					while ($invest_project = mysqli_fetch_assoc($query)) {

						$rate_of_user = $invest_project['amount'] * 100 / $invest_project_sum_amount * 0.01;
						$rate_of_user = number_format($rate_of_user, 4, '.', '');

						$amount_of_user = $amount * $rate_of_user;
						$amount_of_user = number_format($amount_of_user, 2, '.', '');
						//echo $rate_of_user . '% - ' . $amount_of_user.' грн<br>';

						$invest_project_user_id = $invest_project['user_id'];
						
						$sql_user = "SELECT * FROM `users` WHERE `id`='{$invest_project_user_id}'";
						$query_user = mysqli_query($db, $sql_user) or die(mysqli_error($db));
						$user_invest = mysqli_fetch_assoc($query_user);

						$user_cash_was = $user_invest['cash'];
						$user_cash_change = $amount_of_user;
						$user_cash_became = $user_cash_was + $amount_of_user;

						$sql_up = "UPDATE `users` SET `cash`='{$user_cash_became}', `updated`='{$current_date}' WHERE `id`='{$invest_project_user_id}'";
						$query_up = mysqli_query($db, $sql_up) or die(mysqli_error($db));

						$action = 'Начисление дивидендов по проекту инвестиций '.$invest_project_config['name'];

						transactionOrder($db, $invest_project_user_id, 9, $action, $user_cash_was, $user_cash_change, $user_cash_became, 2, $current_date);

					}

				}

				if ($amount_on > 0) {

					$sql = "SELECT * FROM `users` WHERE `id`=1";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$user_on = mysqli_fetch_assoc($query);

					$user_cash_was = $user_on['cash'];
					$user_cash_change = $amount_on;
					$user_cash_became = $user_cash_was + $amount_on;

					$sql = "UPDATE `users` SET `cash`='{$user_cash_became}', `updated`='{$current_date}' WHERE `id`=1";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$action = 'Начисление дивидендов по проекту инвестиций '.$invest_project_config['name'];

					transactionOrder($db, 1, 9, $action, $user_cash_was, $user_cash_change, $user_cash_became, 2, $current_date);

				}

			}

			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' . $word_error . '</strong> ' . $error_message . '</div>';

		}

	}

}

function transactionOrder($db, $user_id, $type, $action, $was, $change, $became, $status, $created) {

	if ($type == 9) $sign_change = "+";
	elseif ($type == 1 or $type == 6 or $type == 8) $sign_change = "-";

	$sql = "INSERT INTO `transactions` SET `user_id`='{$user_id}',
											`type`='{$type}',
											`action`=\"{$action}\",
											`add_funds`='{$change}',
											`was`='{$was}',
											`change`='$sign_change{$change}',
											`became`='{$became}',
											`status`='{$status}',
											`updated`='{$created}',
											`created`='{$created}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

?>