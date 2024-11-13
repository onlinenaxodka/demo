<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if ($user['admin'] == 1) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if (!empty($_POST)) {

				$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
				$id = test_request($id);
				$id = intval($id);
				
				$sql = "SELECT * FROM `users` WHERE `id`='{$id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$post_user = mysqli_fetch_assoc($query);

				$sql_partner = "SELECT `id` FROM `users` WHERE `partner_id`='{$id}'";
				$query_partner = mysqli_query($db, $sql_partner) or die(mysqli_error());
				$count_partners = mysqli_num_rows($query_partner);

				$post_user_partner_id = $post_user['partner_id'];

				$sql_sponsor = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$post_user_partner_id}'";
				$query_sponsor = mysqli_query($db, $sql_sponsor) or die(mysqli_error());
				$user_sponsor = mysqli_fetch_assoc($query_sponsor);
				$user_sponsor_data = '[' . $user_sponsor['id'] . '] ' . $user_sponsor['name'] . ' ' . $user_sponsor['surname'];

				if (empty($post_user['nickname'])) $post_user['nickname'] = '-';

				if (empty($post_user['name'])) $post_user['name'] = '-';

				if (empty($post_user['surname'])) $post_user['surname'] = '-';

				if (empty($post_user['phone'])) $post_user['phone'] = '-';
				
				if (empty($post_user['telegram'])) $post_user['telegram'] = '-';
				else $post_user['telegram'] = '<a href="https://t.me/'.$post_user['telegram'].'" target="_blank">@'.$post_user['telegram'].'</a>';

				if (empty($post_user['skype'])) $post_user['skype'] = '-';

				if ($post_user['birthday'] == '0000-00-00') $post_user['birthday'] = '-';
				else $post_user['birthday'] = date('d.m.Y', strtotime($post_user['birthday']));

				switch ($post_user['sex']) {
				 	case 1:
				 		$post_user['sex'] = 'Мужской';
				 		break;
				 	case 2:
				 		$post_user['sex'] = 'Женский';
				 		break;
				 	default:
				 		$post_user['sex'] = 'еще не указан';
				 		break;
				 } 

				if (empty($post_user['card'])) $post_user['card'] = '-';

				switch ($post_user['lang']) {
					case 'uk':
						$post_user['lang'] = 'Українська';
						break;
					case 'ru':
						$post_user['lang'] = 'Русский';
						break;
					default:
						$post_user['lang'] = 'не определен';
						break;
				}

				switch ($post_user['gtm']) {
					case 'google':
						$post_user['gtm'] = 'Google';
						break;
					default:
						$post_user['gtm'] = 'не указано';
						break;
				}

				if ($post_user['subscription'] == 0) $post_user['subscription'] = 'нет';
				else $post_user['subscription'] = 'да';

				if ($post_user['terms'] == 0) $post_user['terms'] = 'нет';
				else $post_user['terms'] = 'да';

				if ($post_user['activated'] == 0) $post_user['activated'] = 'нет';
				else $post_user['activated'] = 'да';

				if ($post_user['blocked'] == 1) {

					$post_user['blocked'] = 'да';
					$btn_blocked = '<form method="POST" style="display:inline-block;margin-left:10px"><input type="hidden" name="unblock" value="'.$post_user['id'].'"><button type="submit" class="btn btn-success btn-sm">Разблокировать</button></form>';

				} else { 

					$post_user['blocked'] = 'нет';
					$btn_blocked = '<form method="POST" style="display:inline-block;margin-left:10px"><input type="hidden" name="block" value="'.$post_user['id'].'"><button type="submit" class="btn btn-danger btn-sm">Заблокировать</button></form>';

				}

				$post_user_provider_yes = '';
				$post_user_provider_no = '';

				if ($post_user['admin'] == 1) {
					$post_user_admin = 'да';
					$post_user_provider_no = 'checked';
				} elseif ($post_user['admin'] == 2) {
					$post_user_admin = 'нет';
					$post_user_provider_yes = 'checked';
				} else {
					$post_user_admin = 'нет';
					$post_user_provider_no = 'checked';
				}

				if ($post_user['agent'] == 1) $post_user_agent = 'да';
				else $post_user_agent = 'нет';

				if ($post_user['country'] == 0) {

					$post_user['country'] = '-';

				} else {

					$post_user_country = $post_user['country'];

					$sql = "SELECT `name` FROM `countries` WHERE `id`='$post_user_country' LIMIT 1";
					$query = mysqli_query($db, $sql);
					$country = mysqli_fetch_assoc($query);

					$post_user['country'] = $country['name'];

				}

				if ($post_user['region'] == 0) {

					$post_user['region'] = '-';

				} else {

					$post_user_region = $post_user['region'];

					$sql = "SELECT `name` FROM `regions` WHERE `id`='$post_user_region' LIMIT 1";
					$query = mysqli_query($db, $sql);
					$region = mysqli_fetch_assoc($query);

					$post_user['region'] = $region['name'];

				}

				if ($post_user['city'] == 0) {

					$post_user['city'] = '-';

				} else {

					$post_user_city = $post_user['city'];

					$sql = "SELECT `name` FROM `cities` WHERE `id`='$post_user_city' LIMIT 1";
					$query = mysqli_query($db, $sql);
					$city = mysqli_fetch_assoc($query);

					$post_user['city'] = $city['name'];

				}

				$sql_post_user_comments = "SELECT * FROM `users_comments` WHERE `child_id`='{$id}'";
				$query_post_user_comments = mysqli_query($db, $sql_post_user_comments);

				$post_user_comments_count = mysqli_num_rows($query_post_user_comments);

				$post_user_comments_data = '';

				if ($post_user_comments_count > 0) {

					while ($post_user_comments = mysqli_fetch_assoc($query_post_user_comments)) {

						$sql_u_w_c = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$post_user_comments['user_id']}'";
						$query_u_w_c = mysqli_query($db, $sql_u_w_c);
						$post_user_writed_comment = mysqli_fetch_assoc($query_u_w_c);

						$post_user_comments['comment'] = str_replace("\r\n", "<br>", str_replace("'", "\'", $post_user_comments['comment']));
					
						$post_user_comments_data .= '<div class="card bg-light mt-1" style="font-size:90%">
														<div class="card-header pt-1 pb-1 pl-2 pr-2">
															<div class="row">
																<div class="col-7">
																	'.$post_user_writed_comment['name'].' '.$post_user_writed_comment['surname'].'
																</div>
																<div class="col-5 text-right">
																	<span class="font-weight-normal font-italic">'.date('d.m.Y, H:i', strtotime($post_user_comments['created'])).'</span>
																</div>
															</div>
														</div>
														<div class="card-body pt-1 pb-1 pl-2 pr-2">
															<p class="card-text">'.$post_user_comments['comment'].'</p>
															'.((!empty($post_user_comments['audio_name']))?'<audio class="d-block mx-auto" controls><source src="/assets/files/mp3/users_comments/'.$post_user_comments['audio_name'].'" type="audio/mpeg">Тег audio не поддерживается вашим браузером. <a href="/assets/files/mp3/users_comments/'.$post_user_comments['audio_name'].'">Скачайте запись</a>.</audio>':'').'
														</div>
													</div>';

					}

				} else {

					$post_user_comments_data = 'Еще нет комментариев';

				}


?>
						
				<tr>
					<th>Фото</th>
					<td>
					<?	
						$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo" height="100">';
						$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
						for ($i = 0; $i < count($type_img); $i++) { 
							$img_name = __DIR__ . '/../images/users/user'.$post_user['id'].'.'.$type_img[$i];
							if (file_exists($img_name)) {
								$img_photo = '<a href="/data/images/users/user'.$post_user['id'].'.'.$type_img[$i].'" target="_blank"><img src="/data/images/users/user'.$post_user['id'].'.'.$type_img[$i].'" alt="User Photo" height="100"></a>';
							}
						}
						echo $img_photo;
					?>
					</td>
				</tr>
				<tr><th>Системный ID</th><td><?=$post_user['id']?></td></tr>

				<tr><th>Рабочий ID</th><td><?=$post_user['work_id']?></td></tr>

				<tr><th>Кол. партнеров</th><td><?=$count_partners?></td></tr>

				<tr>
					<th>Наставник</th>
					<td>

						<form method="POST">
							<?

$sql_select = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id` = {$post_user['partner_id']}";
$query_select = mysqli_query($db, $sql_select) or die(mysqli_error($db));
$user_select = mysqli_fetch_assoc($query_select);

							?>
							<label class="font-weight-bold font-italic"><?=($user_select['name'] . ' ' . $user_select['surname'])?></label>
							<div class="row">
								<div class="col-sm-8 mb-2">
									<input type="hidden" name="user_sponsor" value="<?=$post_user['id']?>">
									<input type="number" name="sponsor" class="form-control" placeholder="Введите Системный ID">
									<?/*?>
									<select class="form-control" name="sponsor" required>
										<option value="none" selected disabled>[ID] Имя/Фамилия</option>
										
					<?

						$sql_select = "SELECT `id`, `name`, `surname` FROM `users` ORDER BY `created` ASC";
						$query_select = mysqli_query($db, $sql_select) or die(mysqli_error());

						while ($user_select = mysqli_fetch_assoc($query_select)) {

							if ($user_select['id'] == $post_user['partner_id']) {

								echo '<option value="' . $user_select['id'] . '" selected>[' . $user_select['id'] . '] ' . $user_select['name'] . ' ' . $user_select['surname'] . '</option>';

							} else {

								echo '<option value="' . $user_select['id'] . '">[' . $user_select['id'] . '] ' . $user_select['name'] . ' ' . $user_select['surname'] . '</option>';

							}

						}

					?>

									</select>
									<?*/?>
								</div>
								<div class="col-sm-4">
									<button type="submit" class="btn btn-primary btn-block">Поменять</button>
								</div>
							</div>
						</form>
					</td>
				</tr>

				<tr><th>Никнейм</th><td><?=$post_user['nickname']?></td></tr>
				<tr><th>Имя</th><td><?=$post_user['name']?></td></tr>
				<tr><th>Фамилия</th><td><?=$post_user['surname']?></td></tr>
				<tr><th>E-mail</th><td><?=$post_user['mail']?></td></tr>
				<tr>
					<th>Телефон</th>
					<td><?=$post_user['phone']?></td>
				</tr>
				<tr>
					<th>Telegram</th>
					<td><?=$post_user['telegram']?></td>
				</tr>
				<tr>
					<th>Skype</th>
					<td><?=$post_user['skype']?></td>
				</tr>
				<tr><th>Дата рождения</th><td><?=$post_user['birthday']?></td></tr>

				<tr><th>Пол</th><td><?=$post_user['sex']?></td></tr>
						
				<tr><th>Страна</th><td><?=$post_user['country']?></td></tr>
				<tr><th>Регион</th><td><?=$post_user['region']?></td></tr>
				<tr><th>Город</th><td><?=$post_user['city']?></td></tr>

				<tr><th>Банковская карта</th><td><?=$post_user['card']?></td></tr>

				<tr>
					<th>Места продаж</th>
					<td>
						<!-- <a href="<?=$post_user['site']?>" target="_blank"><?=$post_user['site']?></a> -->

<?

						$sql_users_shops = "SELECT * FROM `users_shops` WHERE `user_id`='{$post_user['id']}'";
						$query_users_shops = mysqli_query($db, $sql_users_shops) or die(mysqli_error($db));

						while ($users_shops = mysqli_fetch_assoc($query_users_shops)) {

							echo '<p>'.$users_shops['type'].': <a href="'.$users_shops['url'].'" target="_blank">'.$users_shops['url'].'</p>';

						}

?>

					</td>
				</tr>

				<tr>
					<th>Ссылки на выгрузку</th>
					<td>

<?

						$sql_users_puy = "SELECT * FROM `provider_url_yml` WHERE `user_id`='{$post_user['id']}'";
						$query_users_puy = mysqli_query($db, $sql_users_puy) or die(mysqli_error($db));

						while ($users_puy = mysqli_fetch_assoc($query_users_puy)) {

							echo '<p>'.$users_puy['created'].': <a href="'.$users_puy['url'].'" target="_blank" style="word-break: break-word;">'.$users_puy['url'].'</a></p>';

						}

?>

					</td>
				</tr>

				<tr>
					<th>Баланс</th>
					<td>
						<p>
							<?=$post_user['cash']?> грн. 
							<?if ($user_id == 2):?>
							<button class="btn btn-dark btn-sm" type="button" data-toggle="collapse" data-target="#collapseUserCashChange" aria-expanded="false" aria-controls="collapseUserCashChange">Списать</button>
							<button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#collapseUserCashAdd" aria-expanded="false" aria-controls="collapseUserCashAdd">Пополнить</button><?endif;?>
						</p>
						<?if ($user_id == 2):?>
						<div class="collapse" id="collapseUserCashChange">
							<form method="POST">
								<input type="hidden" name="user_cash_change" value="<?=$post_user['id']?>">
								<div class="form-group">
									<input type="number" step="0.01" name="cash" class="form-control" placeholder="Сумма" required>
								</div>
								<div class="form-group">
									<textarea name="action" class="form-control" placeholder="Введите комментарий..." required></textarea>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-success">Списать</button>
								</div>
							</form>
						</div>
						<div class="collapse" id="collapseUserCashAdd">
							<form method="POST">
								<input type="hidden" name="user_cash_add" value="<?=$post_user['id']?>">
								<div class="form-group">
									<input type="number" step="0.01" name="cash" class="form-control" placeholder="Сумма" required>
								</div>
								<div class="form-group">
									<textarea name="action" class="form-control" placeholder="Введите комментарий..." required></textarea>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-success">Пополнить</button>
								</div>
							</form>
						</div>
						<?endif;?>
					</td>
				</tr>

				<tr><th>Персональная скидка</th><th class="text-danger"><?=($post_user['p_rate']*100)?>%</th></tr>

				<tr><th>Язык</th><td><?=$post_user['lang']?></td></tr>

				<tr><th>Пришел с рекламы:</th><td><?=$post_user['gtm']?></td></tr>

				<tr>
					<th>Статус</th>
					<td>
						<form method="POST">
							<div class="row">
								<div class="col-sm-8 mb-2">
									<input type="hidden" name="user_id_status" value="<?=$post_user['id']?>">
									<select class="form-control" name="status" required>

								<?

									$status_name = array('Новичок', 'Дропшиппер', 'Наставник', 'Супервайзер', 'Директор');

									for ($i=0; $i < 5; $i++) {

										if ($i == $post_user['status']) {

											echo '<option value="'.$i.'" selected>'.$status_name[$i].'</option>';

										} else {

											echo '<option value="'.$i.'">'.$status_name[$i].'</option>';

										}

									}

								?>

									</select>
								</div>
								<div class="col-sm-4">
									<button type="submit" class="btn btn-primary btn-block">Поменять</button>
								</div>
							</div>
						</form>
					</td>
				</tr>
						
				<tr><th>Подписан на новости</th><td><?=$post_user['subscription']?></td></tr>
				<tr><th>Принял правила</th><td><?=$post_user['terms']?></td></tr>
				<tr><th>Активирован</th><td><?=$post_user['activated']?></td></tr>
						
				<tr>
					<th>Заблокирован</th>
					<td>
						<?=$post_user['blocked']?>
						<?=$btn_blocked?>
					</td>
				</tr>
				<tr><th>Админ</th><td><?=$post_user_admin?></td></tr>
				<tr>
					<th>Поставщик</th>
					<td>
						<form method="POST">
							<input type="hidden" name="provider_id" value="<?=$post_user['id']?>">
							<div class="row">
								<div class="col-sm-6 mb-2">
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="customRadioProviderYes" name="provider" class="custom-control-input" value="1" <?=$post_user_provider_yes?>>
										<label class="custom-control-label" for="customRadioProviderYes">Да</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="customRadioProviderNo" name="provider" class="custom-control-input" value="2" <?=$post_user_provider_no?>>
										<label class="custom-control-label" for="customRadioProviderNo">Нет</label>
									</div>
								</div>
								<div class="col-sm-6 text-right">
									<button type="submit" class="btn btn-primary btn-sm">Подтвердить</button>
								</div>
							</div>
						</form>
					</td>
				</tr>
				<tr><th>Агент</th><td><?=$post_user_agent?></td></tr>
				<tr><th>IP</th><td><?=$post_user['ip']?></td></tr>
				<tr><th>Был на сайте</th><td><?=$post_user['was']?></td></tr>
				<tr><th>Редактирован</th><td><?=$post_user['updated']?></td></tr>
				<tr><th>Зарегистрирован</th><td><?=$post_user['created']?></td></tr>
				<?if (($post_user['id'] != 1 and $post_user['id'] != 2) or $user_id == 2):?>
				<tr>
					<th>Вход в аккаунт</th>
					<td>
						<form method="POST">
							<input type="hidden" name="login_in_acc" value="<?=$post_user['id']?>">
							<button type="submit" class="btn btn-success">Войти</button>
						</form>
					</td>
				</tr>
				<?endif;?>
				<tr>
					<th>Количество заказов:</th>
					<td>
						<?

						$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$post_user['id']}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_orders_all = mysqli_num_rows($query);

						$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$post_user['id']}' AND `status`=3";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_orders_status3 = mysqli_num_rows($query);

						$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$post_user['id']}' AND `status`=7";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_orders_status7 = mysqli_num_rows($query);

						$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$post_user['id']}' AND `status`=8";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_orders_status8 = mysqli_num_rows($query);

						$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$post_user['id']}' AND `status`=9";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_orders_status9 = mysqli_num_rows($query);

						?>
						<p>Всего: <b><?=$user_orders_all?></b></p>
						<p>В обработке: <b><?=$user_orders_status3?></b></p>
						<p>Завершенных: <b><?=$user_orders_status7?></b></p>
						<p>Отмененных: <b><?=$user_orders_status8?></b></p>
						<p>Отказов: <b><?=$user_orders_status9?></b></p>
					</td>
				</tr>
				<tr>
					<th>Последние визиты лендингов</th>
					<td>
						<?

						$sql = "SELECT `user_id`, MAX(`created`) AS last_created FROM `landdrop_statistic` WHERE `ip` = '{$post_user['ip']}' GROUP BY `user_id` ORDER BY last_created DESC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						if (mysqli_num_rows($query) > 0) {

							echo '<div class="row">';

							while ($landdrop_statistic = mysqli_fetch_assoc($query)) {
								
								$sql_user_landdrop_statistic = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$landdrop_statistic['user_id']}'";
								$query_user_landdrop_statistic = mysqli_query($db, $sql_user_landdrop_statistic) or die(mysqli_error($db));
								$user_landdrop_statistic = mysqli_fetch_assoc($query_user_landdrop_statistic);

								echo '
									<div class="col-sm-6">['.$user_landdrop_statistic['id'].'] '.$user_landdrop_statistic['name'].' '.$user_landdrop_statistic['surname'].'</div>
									<div class="col-sm-6"><b>'.$landdrop_statistic['last_created'].'</b></div>';

							}

							echo '</div>';

						} else {
							echo 'Совпадений нет';
						}

						?>
					</td>
				</tr>
				<tr><th>Комментарии к пользователю</th><td><?=$post_user_comments_data?></td></tr>

<?	

			}

		}

	}

}