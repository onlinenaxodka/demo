<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		if (!empty($_POST['block'])) {

			$block = (isset($_POST['block'])) ? mysqli_real_escape_string($db, $_POST['block']) : '';
			$block = test_request($block);
			$block = intval($block);

			if ($block > 0) {

				$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$block}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$for_user = mysqli_fetch_assoc($query);

				$sql = "UPDATE `users` SET `blocked`=1, `updated`='{$current_date}' WHERE `id`='{$block}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='users',
														`id_row`='{$block}',
														`action`='Заблокировал пользователя',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$email = $for_user['mail'];
				$subject = "Ваш аккаунт в ".$name_company." был заблокирован";
				$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
							<p style='text-align:center'>Ваш аккаунт был заблокирован в соответствии с нарушением Правил пользования сайтом.</p>
							<p style='text-align:center'>Если вы считаете, что это ошибка, пишите в службу поддержки: support@".$_SERVER['SERVER_NAME']."</p>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата блокировки: ".date('d.m.Y H:i')."</p>";
				$from['name_company'] = $name_company;
				$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

				if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

					$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Успех!</strong> Пользователь успешно заблокирован. Сообщение успешно отправлено.</div>';

				} else {

					$error = true;
					$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

				}

			}

		} elseif (!empty($_POST['unblock'])) {

			$unblock = (isset($_POST['unblock'])) ? mysqli_real_escape_string($db, $_POST['unblock']) : '';
			$unblock = test_request($unblock);
			$unblock = intval($unblock);

			if ($unblock > 0) {

				$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$unblock}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$for_user = mysqli_fetch_assoc($query);

				$sql = "UPDATE `users` SET `blocked`=0, `updated`='{$current_date}' WHERE `id`='{$unblock}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
														`table_name`='users',
														`id_row`='{$unblock}',
														`action`='Разблокировал пользователя',
														`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());

				$email = $for_user['mail'];
				$subject = "Ваш аккаунт в ".$name_company." был разблокирован";
				$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
							<p style='text-align:center'>Ваш аккаунт был разблокирован.</p>
							<p style='text-align:center'>Вы можете <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' target='_blank'>войти</a> в ваш аккаунт.</p>
							<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
							<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата разблокировки: ".date('d.m.Y H:i')."</p>";
				$from['name_company'] = $name_company;
				$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

				if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

					$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Успех!</strong> Пользователь успешно разблокирован. Сообщение успешно отправлено.</div>';

				} else {

					$error = true;
					$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

				}

			}

		} elseif (!empty($_POST['user_sponsor'])) {

			$user_sponsor = (isset($_POST['user_sponsor'])) ? mysqli_real_escape_string($db, $_POST['user_sponsor']) : '';
			$user_sponsor = test_request($user_sponsor);
			$user_sponsor = intval($user_sponsor);

			$sponsor = (isset($_POST['sponsor'])) ? mysqli_real_escape_string($db, $_POST['sponsor']) : '';
			$sponsor = test_request($sponsor);
			$sponsor = intval($sponsor);

			if ($user_sponsor > 0) {

				if ($sponsor > 0) {

					$sql = "SELECT `partner_id`, `name`, `mail` FROM `users` WHERE `id`='{$user_sponsor}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$for_user = mysqli_fetch_assoc($query);

					$sql = "UPDATE `users` SET `partner_id`='{$sponsor}', `updated`='{$current_date}' WHERE `id`='{$user_sponsor}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$for_user['partner_id']}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$old_mentor = mysqli_fetch_assoc($query);

					$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$sponsor}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$new_mentor = mysqli_fetch_assoc($query);

					$action = 'Назначен новый наставник ['.$new_mentor['id'].'] '.$new_mentor['name'].' '.$new_mentor['surname'].'. Предыдущий наставник был ['.$old_mentor['id'].'] '.$old_mentor['name'].' '.$old_mentor['surname'].'.';

					$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
															`table_name`='users',
															`id_row`='{$user_sponsor}',
															`action`=\"{$action}\",
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$email = $for_user['mail'];
					$subject = "У вас новый старший партнер в ".$name_company;
					$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
								<p style='text-align:center'>Вам назначен новый наставник.</p>
								<p style='text-align:center'>Вы можете <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' target='_blank'>войти</a> в свой аккаунт и просмотреть его.</p>
								<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
								<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата назначения наставника: ".date('d.m.Y H:i')."</p>";
					$from['name_company'] = $name_company;
					$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

					if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

						$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Успех!</strong> Наставник успешно изменен для пользователя ID '.$user_sponsor.'. Сообщение успешно отправлено.</div>';

					} else {

						$error = true;
						$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

					}
				
				}

			}

		} elseif (!empty($_POST['user_id_status'])) {

			$user_id_status = (isset($_POST['user_id_status'])) ? mysqli_real_escape_string($db, $_POST['user_id_status']) : '';
			$user_id_status = test_request($user_id_status);
			$user_id_status = intval($user_id_status);

			$status = (isset($_POST['status'])) ? mysqli_real_escape_string($db, $_POST['status']) : '';
			$status = test_request($status);
			$status = intval($status);

			$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$user_id_status}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$for_user = mysqli_fetch_assoc($query);

			if ($user_id_status > 0) {

				if ($status >= 0) {

					switch ($status) {
						case 0:
							$status_name = 'Новичок';
							break;
						case 1:
							$status_name = 'Дропшиппер';
							break;
						case 2:
							$status_name = 'Наставник';
							break;
						case 3:
							$status_name = 'Супервайзер';
							break;
						case 4:
							$status_name = 'Директор';
							break;
						default:
							$status_name = 'не определен';
							break;
					}

					$sql = "UPDATE `users` SET `status`='{$status}', `updated`='{$current_date}' WHERE `id`='{$user_id_status}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
															`table_name`='users',
															`id_row`='{$user_id_status}',
															`action`='Пользователю ID{$user_id_status} назначен новый статус {$status_name}',
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$email = $for_user['mail'];
					$subject = "Вам назначен новый статус в ".$name_company;
					$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
								<p style='text-align:center'>Вам назначен новый статус.</p>
								<p style='text-align:center'>Вы можете <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' target='_blank'>войти</a> в свой аккаунт и проверить его.</p>
								<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
								<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата назначения статуса: ".date('d.m.Y H:i')."</p>";
					$from['name_company'] = $name_company;
					$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

					if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

						$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Успех!</strong> Пользователю ID'.$user_id_status.' успешно назначен новый статус '.$status_name.'. Сообщение успешно отправлено.</div>';

					} else {

						$error = true;
						$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

					}

				}

			}

		} elseif (!empty($_POST['login_in_acc'])) {

			$login_in_acc = (isset($_POST['login_in_acc'])) ? mysqli_real_escape_string($db, $_POST['login_in_acc']) : '';
			$login_in_acc = test_request($login_in_acc);
			$login_in_acc = intval($login_in_acc);

			$sql = "SELECT * FROM `users` WHERE `id`='{$login_in_acc}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$for_user = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) > 0) {
				
				if ($user['admin'] == 1) {

					if (($for_user['id'] != 1 and $for_user['id'] != 2) or $user_id == 2) {

						$_SESSION['user'] = array('id' => $for_user['id'], 'hash' => $for_user['key']);

						$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
																`table_name`='users',
																`id_row`=\"{$login_in_acc}\",
																`action`=\"Выполнен вход в аккаунт пользователя {$for_user["name"]} {$for_user["surname"]}\",
																`created`='{$current_date}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error());

					}

				}

			}

			header('Location: /account/');
			exit;

		} elseif (!empty($_POST['provider_id'])) {

			$provider_id = (isset($_POST['provider_id'])) ? mysqli_real_escape_string($db, $_POST['provider_id']) : '';
			$provider_id = test_request($provider_id);
			$provider_id = intval($provider_id);

			$provider = (isset($_POST['provider'])) ? mysqli_real_escape_string($db, $_POST['provider']) : '';
			$provider = test_request($provider);
			$provider = intval($provider);

			$sql = "SELECT `name`, `mail` FROM `users` WHERE `id`='{$provider_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$for_user = mysqli_fetch_assoc($query);

			if ($provider_id > 0) {

				if ($provider > 0) {

					switch ($provider) {
						case 1:
							$provider_status = 2;
							$action_provider = 'Пользователю ID'.$provider_id.' назначен статус Поставщик';
							$msg_mail_subject = 'Вам назначен статус Поставщик';
							$msg_mail_body_part = 'Вам назначен статус Поставщик и открыт дополнительный раздел для добавления и управления товарами.';
							$msg_alert_message_part = 'Пользователю ID'.$provider_id.' успешно назначен статус Поставщик.';
							break;
						case 2:
							$provider_status = 0;
							$action_provider = 'У пользователя ID'.$provider_id.' статус Поставщик забран';
							$msg_mail_subject = 'У Вас забран статус Поставщик';
							$msg_mail_body_part = 'У Вас забран статус Поставщик и закрыт дополнительный раздел для добавления и управления товарами.';
							$msg_alert_message_part = 'У пользователя ID'.$provider_id.' успешно забран статус Поставщик.';
							break;
						default:
							$provider_status = 0;
							$action_provider = 'У пользователя ID'.$provider_id.' статус Поставщик забран';
							$msg_mail_subject = 'У Вас забран статус Поставщик';
							$msg_mail_body_part = 'У Вас забран статус Поставщик и закрыт дополнительный раздел для добавления и управления товарами.';
							$msg_alert_message_part = 'У пользователя ID'.$provider_id.' успешно забран статус Поставщик.';
							break;
					}

					$sql = "UPDATE `users` SET `admin`='{$provider_status}', `updated`='{$current_date}' WHERE `id`='{$provider_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$sql = "INSERT INTO `logs_admin` SET `user_id`='{$user_id}',
															`table_name`='users',
															`id_row`='{$provider_id}',
															`action`='{$action_provider}',
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());

					$email = $for_user['mail'];
					$subject = $msg_mail_subject." в ".$name_company;
					$message = "<h1 style='text-align:center'>Здравствуйте, ".$for_user['name']."!</h1>
								<p style='text-align:center'>".$msg_mail_body_part."</p>
								<p style='text-align:center'>Вы можете <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/login/' target='_blank'>войти</a> в свой аккаунт и проверить его.</p>
								<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
								<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата назначения статуса: ".date('d.m.Y H:i')."</p>";
					$from['name_company'] = $name_company;
					$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

					if (sendMail($email, $subject, $message, $from, $server_protocole) == 'success') {

						$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Успех!</strong> '.$msg_alert_message_part.' Сообщение успешно отправлено.</div>';

					} else {

						$error = true;
						$error_message .= 'Ошибка! Сообщение не отправлено.<br>';

					}

				}

			}

		} elseif (!empty($_POST['search'])) {

			$search = (isset($_POST['search'])) ? mysqli_real_escape_string($db, $_POST['search']) : '';
			$search = test_request($search);
			$search = str_replace("'", "\'", $search);

			$where_search_user = "`id`='{$search}' OR `work_id`='{$search}' OR `nickname` LIKE '%{$search}%' OR `name` LIKE '%{$search}%' OR `surname` LIKE '%{$search}%' OR `mail` LIKE '%{$search}%' OR `phone` LIKE '%{$search}%' OR `telegram` LIKE '%{$search}%' OR `skype` LIKE '%{$search}%'";

			if (isset($_SESSION['filter_user'])) unset($_SESSION['filter_user']);

			$_SESSION['search_user']['pages'] = "SELECT COUNT(1) as count FROM `users` WHERE ".$where_search_user;
			$_SESSION['search_user']['results'] = "SELECT * FROM `users` WHERE ".$where_search_user." ORDER BY `created` ASC LIMIT ";
			$_SESSION['search_user']['value'] = $search;

			header('Location: /admin/users/');
			exit;

		} elseif (!empty($_POST['filter'])) {

			$filter = (isset($_POST['filter'])) ? mysqli_real_escape_string($db, $_POST['filter']) : '';
			$filter = test_request($filter);

			switch ($filter) {
				case 'admins':
					$where_filter_user = ' WHERE `admin`=1';
					break;
				case 'providers':
					$where_filter_user = ' WHERE `admin`=2';
					break;
				case 'agents':
					$where_filter_user = ' WHERE `agent`=1';
					break;
				case 'newbie':
					$where_filter_user = ' WHERE `status`=0';
					break;
				case 'dropshipper':
					$where_filter_user = ' WHERE `status`=1';
					break;
				case 'manager':
					$where_filter_user = ' WHERE `status`=2';
					break;
				case 'supervisor':
					$where_filter_user = ' WHERE `status`=3';
					break;
				case 'director':
					$where_filter_user = ' WHERE `status`=4';
					break;
				case 'nonementor':
					$where_filter_user = ' WHERE `partner_id` NOT IN (SELECT `id` FROM `users`)';
					break;
				case 'isorders':
					$where_filter_user = ' WHERE `id` IN (SELECT `user_id` FROM `orders`)';
					break;
				case 'isorders7':
					$where_filter_user = ' WHERE `id` IN (SELECT `user_id` FROM `orders` WHERE `status`=7)';
					break;
				case 'partnersnoadmin':
					$where_filter_user = ' WHERE `ip` IN (SELECT `ip` FROM `landdrop_statistic`) AND `partner_id`=1 AND `admin`=0';
					break;
				case 'gtm':
					$where_filter_user = ' WHERE `gtm`=\'google\'';
					break;
				default:
					$where_filter_user = '';
					break;
			}

			if (isset($_SESSION['search_user'])) unset($_SESSION['search_user']);

			$_SESSION['filter_user']['pages'] = "SELECT COUNT(1) as count FROM `users`".$where_filter_user;
			$_SESSION['filter_user']['results'] = "SELECT * FROM `users`".$where_filter_user." ORDER BY `created` ASC LIMIT ";
			$_SESSION['filter_user']['value'] = $filter;

			header('Location: /admin/users/');
			exit;

		} elseif (!empty($_POST['clear_search'])) {

			$clear_search = (isset($_POST['clear_search'])) ? mysqli_real_escape_string($db, $_POST['clear_search']) : '';
			$clear_search = test_request($clear_search);
			$clear_search = intval($clear_search);

			if ($clear_search == 1) {

				if (isset($_SESSION['search_user'])) unset($_SESSION['search_user']);
				if (isset($_SESSION['filter_user'])) unset($_SESSION['filter_user']);

			}

			header('Location: /admin/users/');
			exit;
			
		} elseif (!empty($_POST['user_cash_change']) or !empty($_POST['user_cash_add'])) {

			$user_cash_change = (isset($_POST['user_cash_change'])) ? mysqli_real_escape_string($db, $_POST['user_cash_change']) : '';
			$user_cash_change = test_request($user_cash_change);
			$user_cash_change = intval($user_cash_change);

			$user_cash_add = (isset($_POST['user_cash_add'])) ? mysqli_real_escape_string($db, $_POST['user_cash_add']) : '';
			$user_cash_add = test_request($user_cash_add);
			$user_cash_add = intval($user_cash_add);

			$cash = (isset($_POST['cash'])) ? mysqli_real_escape_string($db, $_POST['cash']) : '';
			$cash = test_request($cash);
			$cash = floatval($cash);
			$cash = number_format($cash, 2, '.', '');

			$action = (isset($_POST['action'])) ? mysqli_real_escape_string($db, $_POST['action']) : '';
			$action = test_request($action);

			if ($cash > 0 and !empty($action)) {

				$user_cash_id = $user_cash_add ?: $user_cash_change;

				$sql = "SELECT `cash` FROM `users` WHERE `id`='{$user_cash_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$user_cash_change_select = mysqli_fetch_assoc($query);

				$was = $user_cash_change_select['cash'];
				$became = $user_cash_add ? $was + $cash : $was - $cash;

				$sql = "UPDATE `users` SET `cash`='{$became}', `updated`='{$current_date}' WHERE `id`='{$user_cash_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				
				if ($user_cash_add) {
					$sql = "INSERT INTO `transactions` SET `user_id`='{$user_cash_id}',
														`type`=0,
														`action`='Пополнение баланса ({$action})',
														`add_funds`='{$cash}',
														`was`='{$was}',
														`change`='+{$cash}',
														`became`='{$became}',
														`status`=2,
														`updated`='{$current_date}',
														`created`='{$current_date}'";
				} else {
					$sql = "INSERT INTO `transactions` SET `user_id`='{$user_cash_id}',
														`type`=2,
														`action`='Вывод средств ({$action})',
														`add_funds`='{$cash}',
														`was`='{$was}',
														`change`='-{$cash}',
														`became`='{$became}',
														`status`=2,
														`updated`='{$current_date}',
														`created`='{$current_date}'";
				}
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

		}

	}

}

?>