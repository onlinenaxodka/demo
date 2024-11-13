<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		$project_id = (isset($_POST['project_id'])) ? mysqli_real_escape_string($db, $_POST['project_id']) : '';
		$project_id = test_request($project_id);
		$project_id = intval($project_id);

		$amount = (isset($_POST['amount'])) ? mysqli_real_escape_string($db, $_POST['amount']) : '';
		$amount = test_request($amount);
		$amount = floatval($amount);
		$amount = number_format($amount, 2, '.', '');

		if ($project_id > 0) {

			$sql = "SELECT * FROM `invest_project_config` WHERE `id`='{$project_id}' AND `status`=1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$invest_project_config_count = mysqli_num_rows($query);
			$invest_project_config = mysqli_fetch_assoc($query);

			if ($invest_project_config_count > 0) {

				if ($amount > 0) {

					if ($amount > $user['cash']) {

						$error = true;
						$error_message .= 'Сумма инвестиции не может быть больше доступной суммы на балансе. ';

					} else {

						if ($amount > $invest_project_config['amount']) {

							$error = true;
							$error_message .= 'Сумма инвестиции не может быть больше суммы фонда. ';

						}

					}

				} else {

					$error = true;
					$error_message .= 'Сумма инвестиции в проект указана неправильно. ';

				}

			} else {

				$error = true;
				$error_message .= 'Такого проекта не существует. ';

			}

		} else {

			$error = true;
			$error_message .= 'Такого проекта не существует. ';

		}

		if (!$error) {

			$sql = "SELECT * FROM `invest_project` WHERE `user_id`='{$user_id}' AND `project_id`='{$project_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$invest_project_assoc = mysqli_fetch_assoc($query);
			$invest_project_count = mysqli_num_rows($query);

			$user_cash_was = $user['cash'];
			$user_cash_change = $amount;
			$user_cash_became = $user_cash_was - $amount;

			$sql = "UPDATE `users` SET `cash`='{$user_cash_became}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			if ($invest_project_count > 0) {

				$amount = $amount + $invest_project_assoc['amount'];

				$sql = "UPDATE `invest_project` SET `amount`='{$amount}', `updated`='{$current_date}' WHERE `user_id`='{$user_id}' AND `project_id`='{$project_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				$sql = "INSERT INTO `invest_project` SET `user_id`='{$user_id}',
															`project_id`='{$project_id}',
															`amount`='{$amount}',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			$action = 'Инвестиция в проект '.$invest_project_config['name'];

			transactionOrder($db, $user_id, 8, $action, $user_cash_was, $user_cash_change, $user_cash_became, 2, $current_date);

			header('Location: /account/wallet/');
			exit;

		} else {

			$alert_message = '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' . $word_error . '</strong> ' . $error_message . '</div>';

		}

	}

}

function transactionOrder($db, $user_id, $type, $action, $was, $change, $became, $status, $created) {

	if ($type == 3) $sign_change = "+";
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