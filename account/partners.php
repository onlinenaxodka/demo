<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

function displayNetwork($db, $user_id, $level, $data, $startnum = array()) {

	if (!empty($startnum)) {
		$start = $startnum[0];
		$num = $startnum[1];
	}

	if ($level < 4) {

	$sql = "SELECT * FROM `users` WHERE `partner_id`='{$user_id}' ORDER BY FIELD(DATE(`notify`),CURDATE()) DESC, `created` DESC";
	if (!empty($startnum)) 
		$sql = "SELECT * FROM `users` WHERE `partner_id`='{$user_id}' ORDER BY FIELD(DATE(`notify`),CURDATE()) DESC, `created` DESC LIMIT $start, $num";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$countusers = mysqli_num_rows($query);
	$data['countusers'] += $countusers;

	$arr_roma_numbers = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII');
	$partner_status = array('Новичок', 'Дропшиппер', 'Наставник', 'Супервайзер', 'Директор');
	$partner_status_img = array('career_status_1.png', 'career_status_2.png', 'career_status_3.png', 'career_status_4.png', 'career_status_5.png');

	if ($countusers > 0) {

		while ($user = mysqli_fetch_assoc($query)) {

			$countusers_child = countNetwork($db, $user['id'], $level, 0);

			if ($user['notify'] == date('Y-m-d', strtotime('now'))) $notify_current_day = ' table-primary';
			else $notify_current_day = '';

			if ($level == 0) $class = 'class="treegrid-'.$user['id'].$notify_current_day.'"';
			else $class = 'class="treegrid-'.$user['id'].' treegrid-parent-'.$user['partner_id'].$notify_current_day.'"';

			if ($user['status'] >= 0 and $user['status'] < 5) $user['status'] = '<img src="/assets/images/'.$partner_status_img[$user['status']].'" alt="'.$partner_status[$user['status']].'" class="border border-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="'.$partner_status[$user['status']].'" style="width:30px;">';
			else $user['status'] = '<img src="/assets/images/logo636x636.png" alt="не определен" class="border border-primary rounded-circle" data-toggle="tooltip" data-placement="top" title="не определен" style="width:30px;">';
			
			if (empty($user['phone'])) $user['phone'] = 'не указан';
			if (empty($user['telegram'])) $user['telegram'] = 'не указан';
			if ($user['birthday'] == '0000-00-00') $user['birthday'] = 'не указана';
			else $user['birthday'] = date('d.m.Y', strtotime($user['birthday']));

			switch ($user['lang']) {
				case 'uk':
					$user['lang'] = 'Українська';
					break;
				case 'ru':
					$user['lang'] = 'Русский';
					break;
				default:
					$user['lang'] = 'не определен';
					break;
			}

			if ($user['country'] == 0) {

				$user['country'] = '-';

			} else {

				$user_country = $user['country'];

				$sql_country = "SELECT `name` FROM `countries` WHERE `id`='$user_country' LIMIT 1";
				$query_country = mysqli_query($db, $sql_country);
				$country = mysqli_fetch_assoc($query_country);

				$user['country'] = $country['name'];

			}

			if ($user['region'] == 0) {

				$user['region'] = '-';

			} else {

				$user_region = $user['region'];

				$sql_region = "SELECT `name` FROM `regions` WHERE `id`='$user_region' LIMIT 1";
				$query_region = mysqli_query($db, $sql_region);
				$region = mysqli_fetch_assoc($query_region);

				$user['region'] = $region['name'];

			}

			if ($user['city'] == 0) {

				$user['city'] = '-';

			} else {

				$user_city = $user['city'];

				$sql_city = "SELECT `name` FROM `cities` WHERE `id`='$user_city' LIMIT 1";
				$query_city = mysqli_query($db, $sql_city);
				$city = mysqli_fetch_assoc($query_city);

				$user['city'] = $city['name'];

			}

			$user['created'] = date('d.m.Y H:i', strtotime($user['created']));

			$child_id = $user['id'];

			$sql_users_comments = "SELECT * FROM `users_comments` WHERE `child_id`='{$child_id}'";
			$query_users_comments = mysqli_query($db, $sql_users_comments);

			$users_comments_count = mysqli_num_rows($query_users_comments);

			$users_comments_data = '';

			if ($users_comments_count > 0) {

				while ($users_comments = mysqli_fetch_assoc($query_users_comments)) {

					$sql_u_w_c = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$users_comments['user_id']}'";
					$query_u_w_c = mysqli_query($db, $sql_u_w_c);
					$user_writed_comment = mysqli_fetch_assoc($query_u_w_c);

					$users_comments['comment'] = str_replace("\r\n", "<br>", str_replace("'", "\'", $users_comments['comment']));

					
					$users_comments_audio_file = '';

					if (!empty($users_comments['audio_name'])) {

						$users_comments_audio_file = '<audio class=\'d-block mx-auto\' controls><source src=\'/assets/files/mp3/users_comments/'.$users_comments['audio_name'].'\' type=\'audio/mpeg\'>Тег audio не поддерживается вашим браузером. <a href=\'/assets/files/mp3/users_comments/'.$users_comments['audio_name'].'\'>Скачайте запись</a>.</audio>';

					}
				
					$users_comments_data .= '<div class=\'card bg-light mt-3\'>
												<div class=\'card-header pt-1 pb-1\'>
													<div class=\'row\'>
														<div class=\'col-7\'>
															'.$user_writed_comment['name'].'
														</div>
														<div class=\'col-5 text-right\'>
															<span class=\'font-weight-normal font-italic\'>'.date('d.m.Y H:i', strtotime($users_comments['created'])).'</span>
														</div>
													</div>
												</div>
												<div class=\'card-body\'>
													<p class=\'card-text\'>'.$users_comments['comment'].'</p>
													'.$users_comments_audio_file.'
												</div>
											</div>';

				}

			} else {

				$users_comments_data = '<p class=\'text-center\'>Еще нет комментариев</p>';

			}

			$action_d_none = '';
			if ($level > 0) $action_d_none = ' d-none';

			$user_mail_status = '<b class=\'text-danger\'>не активирован</b>';
			if ($user['activated'] == 1) $user_mail_status = '<b class=\'text-success\'>активирован</b>';

$sql_homework = "SELECT * FROM `school_homework` WHERE `user_id`='{$child_id}' ORDER BY FIELD(`status`,0,2,1) ASC, `updated` ASC";
$query_homework = mysqli_query($db, $sql_homework) or die(mysqli_error($db));

if (mysqli_num_rows($query_homework) > 0) {

	$user_homework_data = '';

	while ($school_homework = mysqli_fetch_assoc($query_homework)) {

		$school_homework_goods_id = $school_homework['goods_id'];

		$sql_goods = "SELECT `id`, `category`, `name` FROM `goods` WHERE `id`='{$school_homework_goods_id}'";
		$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query_goods);

		$goods['name'] = json_decode($goods['name'], true);

		$school_homework['link_ad'] = json_decode($school_homework['link_ad'], true);

		$homework_d_none = '';
		if ($school_homework['status'] == 0) {
			$school_homework['status'] = '<b class=\'text-warning\'>На проверке</b>';
			if ($level > 0) $homework_d_none = 'd-none';
		} elseif ($school_homework['status'] == 1) {
			$school_homework['status'] = '<b class=\'text-success\'>Проверено</b>';
			$homework_d_none = 'd-none';
		} elseif ($school_homework['status'] == 2) {
			$school_homework['status'] = '<b class=\'text-danger\'>Отменено</b>';
			$homework_d_none = 'd-none';
		}

$user_homework_data .= '
<div class=\'card mb-2\'>
	<div class=\'card-header\'>
		<a href=\'/account/goods/'.$goods['category'].'/'.$goods['id'].'\' target=\'_blank\'>'.$goods['name']['ru'].'</a>
	</div>
	<div class=\'card-body\'>
		<div class=\'row\'>
			<div class=\'col-md-8\'>';

			foreach ($school_homework['link_ad'] as $school_homework_link_ad)
				$user_homework_data .= '
				<p><a href=\''.$school_homework_link_ad.'\' target=\'_blank\'>'.$school_homework_link_ad.'</a></p>';

$user_homework_data .= '
			</div>
			<div class=\'col-md-4\'>
				<p class=\'text-center\'>'.$school_homework['status'].'</p>
				<form method=\'POST\' class=\''.$homework_d_none.'\'>
					<input type=\'hidden\' name=\'homework_act\' value=\'1\'>
					<input type=\'hidden\' name=\'homework_id\' value=\''.$school_homework['id'].'\'>
					<input type=\'hidden\' name=\'child\' value=\''.$child_id.'\'>
					<button type=\'submit\' class=\'btn btn-success btn-block btn-sm mb-3\' onclick=\'return partnersConfirmHomework()\'>Подтвердить</button>
				</form>
				<form method=\'POST\' class=\''.$homework_d_none.'\'>
					<input type=\'hidden\' name=\'homework_act\' value=\'2\'>
					<input type=\'hidden\' name=\'homework_id\' value=\''.$school_homework['id'].'\'>
					<input type=\'hidden\' name=\'child\' value=\''.$child_id.'\'>
					<button type=\'submit\' class=\'btn btn-danger btn-block btn-sm\' onclick=\'return partnersCancelHomework()\'>Отменить</button>
				</form>
			</div>
		</div>
	</div>
</div>';

	}

} else {

	$user_homework_data = '<p class=\'text-center mt-3\'>У партнера еще нет выполненных домашних заданий</p>';

}

$sql_homework_count = "SELECT `id` FROM `school_homework` WHERE `user_id`='{$child_id}' AND `status`=0";
$query_homework_count = mysqli_query($db, $sql_homework_count) or die(mysqli_error($db));
$user_homework_count = mysqli_num_rows($query_homework_count);

$badge_color = 'badge-light';
if ($user_homework_count > 0) $badge_color = 'badge-danger';

			$tr = '<tr '.$class.'>
					<td class="text-left">'.$user['name'].' '.$user['surname'].'</td>
					<td class="line">'.$arr_roma_numbers[$level].'</td>
					<td>'.$user['status'].'</td>
					<td class="center_col">'.$countusers_child.'</td>
					<td>
						<button type="button" class="btn btn-success pt-0 pb-0 pl-2 pr-2" data-toggle="popover" data-placement="bottom" title="Домашнее задание '.$user['name'].' '.$user['surname'].' <button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'closePopoverWindow(this)\'><span aria-hidden=\'true\'>&times;</span></button>" data-content="
							<div style=\'overflow-y:auto;height:400px;\'>'.$user_homework_data.'<div>
						" style="font-size:20px;position:relative;">
							<i class="fa fa-graduation-cap"></i>
							<span class="badge '.$badge_color.' rounded-circle" style="position:absolute;padding:0;width:17px;height:17px;line-height:18px;top:-8px;right:-5px;">'.$user_homework_count.'</span>
						</button>
					</td>
					<td>
						<button type="button" class="btn btn-info pt-0 pb-0 pl-2 pr-2" data-toggle="popover" data-placement="bottom" title="Детальная информация <button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'closePopoverWindow(this)\'><span aria-hidden=\'true\'>&times;</span></button>" data-content="
							<table class=\'table\'>
								'.(($user['admin']==2)?'<tr><th colspan=\'2\' style=\'text-align:center;color:#007bff;\'>Поставщик</th></tr>':'').'
								<tr>
									<th>Язык:</th>
									<td>'.$user['lang'].'</td>
								</tr>
								<tr>
									<th>Телефон:</th>
									<td>'.$user['phone'].'</td>
								</tr>
								<tr>
									<th>E-mail:</th>
									<td>'.$user['mail'].'<br>'.$user_mail_status.'</td>
								</tr>
								<tr>
									<th>Telegram:</th>
									<td>@'.$user['telegram'].'</td>
								</tr>
								<tr>
									<th>Дата рождения:</th>
									<td>'.$user['birthday'].'</td>
								</tr>
								<tr>
									<th>Страна:</th>
									<td>'.$user['country'].'</td>
								</tr>
								<tr>
									<th>Регион:</th>
									<td>'.$user['region'].'</td>
								</tr>
								<tr>
									<th>Город:</th>
									<td>'.$user['city'].'</td>
								</tr>
							</table>
						" style="font-size:20px;">
							<i class="fa fa-address-card"></i>
						</button>
					</td>
					<td>'.$user['created'].'</td>
					<td>
						<button type="button" class="btn btn-primary pt-0 pb-0 pl-2 pr-2'.$action_d_none.'" data-toggle="popover" data-placement="bottom" title="Комментарии - - - - - - - - - - - - - - - - - - - - - - - <button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'closePopoverWindow(this)\'><span aria-hidden=\'true\'>&times;</span></button>" data-content="
							<form method=\'POST\' class=\'mb-3\'>
								<input type=\'hidden\' name=\'child\' value=\''.$child_id.'\'>
								<div class=\'form-group\'>
									<textarea class=\'form-control\' name=\'comment\' placeholder=\'Введите комментарий...\' rows=\'2\'></textarea>
								</div>
								<div class=\'form-group text-right\'>
									<button type=\'submit\' class=\'btn btn-success btn-sm\'>Добавить</button>
								</div>
							</form>
							<div style=\'overflow-y:auto;height:200px;\'>'.$users_comments_data.'<div>
						" style="font-size:20px;position:relative;">
							<i class="fa fa-comment"></i>
							<span class="badge badge-light rounded-circle" style="position:absolute;padding:0;width:17px;height:17px;line-height:18px;top:-8px;right:-5px;">'.$users_comments_count.'</span>
						</button>
						<button type="button" class="btn btn-danger pt-0 pb-0 pl-2 pr-2'.$action_d_none.'" data-toggle="popover" data-placement="bottom" title="Запланировать напоминание <button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'closePopoverWindow(this)\'><span aria-hidden=\'true\'>&times;</span></button>" data-content="
							<p>Если вы запланируете дату, то в тот <br>день строка вашего пользователя <br>загорится синим цветом.</p>
							<form method=\'POST\' class=\'mb-5\'>
								<input type=\'hidden\' name=\'child\' value=\''.$child_id.'\'>
								<div class=\'form-group\'>
									<input type=\'date\' name=\'date\' class=\'form-control\' placeholder=\'Введите дату\'>
								</div>
								<div class=\'form-group text-right\'>
									<button type=\'submit\' class=\'btn btn-success btn-sm\'>Запланировать</button>
								</div>
							</form>
						" style="font-size:20px;position:relative;">
							<i class="fa fa-calendar"></i>
						</button>
					</td>
				<tr>';

			$data['tr_all'][] = $tr;

			$data = displayNetwork($db, $user['id'], $level+1, $data);

		}

	} else {

		if ($level == 0) {

			$tr = '<tr><td colspan="8" align="center" valign="middle" height="100">У вас еще нет партнёров</td><tr>';

			$data['tr_all'][] = $tr;

		}

	}

	}

	return $data;

}

function countNetwork($db, $user_id, $level, $countusers_child) {

	if ($level < 7) {

		$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$user_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$countusers = mysqli_num_rows($query);
		$countusers_child += $countusers;

		while ($user = mysqli_fetch_assoc($query)) {

			$countusers_child = countNetwork($db, $user['id'], $level+1, $countusers_child);

		}

	}

	return $countusers_child;

}

$level = 0;
$data = array('countusers' => 0, 'tr_all' => array());

//page views
$num = 20;
		
$sql = "SELECT COUNT(1) as count FROM `users` WHERE `partner_id`='{$user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$posts = mysqli_fetch_assoc($query);
		
$total = intval(($posts['count'] - 1) / $num) + 1;
		
$page = intval($_GET['page']);
		
if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  
		
$start = $page * $num - $num;

$startnum = array($start, $num);
if ($posts['count'] == 0) $startnum = array();
//page views

$data_network = displayNetwork($db, $user_id, $level, $data, $startnum);

$bg_color = array('#e9ecef', '#e9ecef', '#e9ecef', '#e9ecef', '#e9ecef');

switch ($user['status']) {
	case 0:
		$bg_color[0] = '#007bff';
		$progress_career = 0;
		$user_status = 'Новичок';
		break;
	case 1:
		$bg_color[0] = '#007bff';
		$bg_color[1] = '#007bff';
		$progress_career = 25;
		$user_status = 'Дропшиппер';
		break;
	case 2:
		$bg_color[0] = '#007bff';
		$bg_color[1] = '#007bff';
		$bg_color[2] = '#007bff';
		$progress_career = 50;
		$user_status = 'Наставник';
		break;
	case 3:
		$bg_color[0] = '#007bff';
		$bg_color[1] = '#007bff';
		$bg_color[2] = '#007bff';
		$bg_color[3] = '#007bff';
		$progress_career = 75;
		$user_status = 'Супервайзер';
		break;
	case 4:
		$bg_color[0] = '#007bff';
		$bg_color[1] = '#007bff';
		$bg_color[2] = '#007bff';
		$bg_color[3] = '#007bff';
		$bg_color[4] = '#007bff';
		$progress_career = 100;
		$user_status = 'Директор';
		break;
	default:
		$bg_color[0] = '#007bff';
		$progress_career = 0;
		$user_status = 'Новичок';
		break;
}

switch ($user['status']) {
	case 0:
		$user_next_status = 'Дропшиппер';
		$requirement_orders_completed = 3;
		$requirement_managers_completed = 0;
		$requirement_supervisors_completed = 0;
		break;
	case 1:
		$user_next_status = 'Наставник';
		$requirement_orders_completed = 50;
		$requirement_managers_completed = 0;
		$requirement_supervisors_completed = 0;
		break;
	case 2:
		$user_next_status = 'Супервайзер';
		$requirement_orders_completed = 1000;
		$requirement_managers_completed = 3;
		$requirement_supervisors_completed = 0;
		break;
	case 3:
		$user_next_status = 'Директор';
		$requirement_orders_completed = 10000;
		$requirement_managers_completed = 15;
		$requirement_supervisors_completed = 1;
		break;
	default:
		$user_next_status = 'Дропшиппер';
		$requirement_orders_completed = 3;
		$requirement_managers_completed = 0;
		$requirement_supervisors_completed = 0;
		break;
}

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_id}' AND `status`=7";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_completed = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `orders` WHERE `user_id` IN (SELECT `id` FROM `users` WHERE `partner_id`='{$user_id}') AND `status`=7";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_team_orders_completed = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$user_id}' AND `status`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_partner_managers_completed = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `partner_id`='{$user_id}' AND `status`=3";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_partner_supervisors_completed = mysqli_num_rows($query);

$orders_completed_sum = $user_orders_completed + $user_team_orders_completed;

$left_to_do_orders_completed = $requirement_orders_completed-$user_orders_completed;
$left_to_do_orders_team = $requirement_orders_completed-$user_team_orders_completed;
$left_to_do_orders = $requirement_orders_completed-$orders_completed_sum;

$left_to_do_managers = $requirement_managers_completed-$user_partner_managers_completed;
$left_to_do_supervisors = $requirement_supervisors_completed-$user_partner_supervisors_completed;

if ($orders_completed_sum > $requirement_orders_completed) $left_to_do_orders = 0;
if ($user_partner_managers_completed > $requirement_managers_completed) $left_to_do_managers = 0;
if ($user_partner_supervisors_completed > $requirement_supervisors_completed) $left_to_do_supervisors = 0;

?>

<h3 class="text-center mt-3 mb-5">Мой статус "<?=$user_status?>"</h3>

<?if($user['status'] < 4):?>
<div class="row mb-4">
	<div class="col-sm-6 mb-2">
		<div class="card">
			<div class="card-header">
				Достигнуто:
			</div>
			<div class="card-body">
				<?if($user['status'] == 0):?>
				<p class="text-center mb-0">Продаж личных: <b><?=$orders_completed_sum?></b></p>
				<?else:?>
				<p class="text-center mb-0">Продаж личных: <b><?=$user_orders_completed?></b></p>
				<p class="text-center mb-0">Продаж командных: <b><?=$user_team_orders_completed?></b></p>
				<p class="text-center mb-0">Продаж личных вместе с командой: <b><?=$orders_completed_sum?></b></p>
				<?endif;?>
				<?if($user['status'] >= 2):?>
				<hr>
				<p class="text-center mb-0">Наставников: <b><?=$user_partner_managers_completed?></b></p>
				<?endif;?>
				<?if($user['status'] >= 3):?>
				<hr>
				<p class="text-center mb-0">Супервайзеров: <b><?=$user_partner_supervisors_completed?></b></p>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="col-sm-6 mb-2">
		<div class="card">
			<div class="card-header">
				До достижения статуса <span class="text-primary">"<?=$user_next_status?>"</span> осталось сделать:
			</div>
			<div class="card-body">
				<?if($user['status'] == 0):?>
				<p class="text-center mb-0">Продаж личных: <b><?=$left_to_do_orders?></b></p>
				<?else:?>
				<p class="text-center mb-0">Продаж личных вместе с командой: <b><?=$left_to_do_orders?></b></p>
				<?endif;?>
				<?if($user['status'] >= 2):?>
				<hr>
				<p class="text-center mb-0">Наставников: <b><?=$left_to_do_managers?></b></p>
				<?endif;?>
				<?if($user['status'] >= 3):?>
				<hr>
				<p class="text-center mb-0">Супервайзеров: <b><?=$left_to_do_supervisors?></b></p>
				<?endif;?>
			</div>
		</div>
	</div>
</div>
<?else:?>
<h5 class="text-center text-secondary mb-5 font-italic">Вы достигли наивысшего статуса и вам доступны самые большие возможности на платформе.</h5>
<?endif;?>

<p class="text-center font-italic">Наведите курсор мыши или нажмите на иконку статуса на полосе достижения и Вам откроются условия и возможности достижения статуса.</p>

<style type="text/css">
	.status-user-circle {
		display: inline-block;
		width: 50px;
		padding: .25rem;
		position: relative;
		top: -35px;
		border-radius: 50%;
		cursor: pointer;
		outline: 0;
	}
	.status-user-circle-1 {margin-left: -24px;}
	.status-user-circle-2 {margin-left: 20%;}
	.status-user-circle-3 {margin-left: 20%;}
	.status-user-circle-4 {margin-left: 20%;}
	.status-user-circle-5 {margin-right: -24px;float: right;}
	.status-user-circle img {
		background: #fff;
		width: 100%;
		border-radius: 50%;
	}
	@media (max-width: 1199px) {
		.status-user-circle-2 {margin-left: 19%;}
		.status-user-circle-3 {margin-left: 19%;}
		.status-user-circle-4 {margin-left: 19%;}
	}
	@media (max-width: 991px) {
		.status-user-circle-2 {margin-left: 16%;}
		.status-user-circle-3 {margin-left: 16%;}
		.status-user-circle-4 {margin-left: 16%;}
	}
	@media (max-width: 767px) {
		.status-user-circle-2 {margin-left: 13%;}
		.status-user-circle-3 {margin-left: 13%;}
		.status-user-circle-4 {margin-left: 13%;}
	}
	@media (max-width: 575px) {
		.status-user-circle-2 {margin-left: 12%;}
		.status-user-circle-3 {margin-left: 12%;}
		.status-user-circle-4 {margin-left: 12%;}
	}
	@media (max-width: 480px) {
		.status-user-circle-2 {margin-left: 9%;}
		.status-user-circle-3 {margin-left: 9%;}
		.status-user-circle-4 {margin-left: 9%;}
	}
	@media (max-width: 440px) {
		.status-user-circle-2 {margin-left: 7%;}
		.status-user-circle-3 {margin-left: 7%;}
		.status-user-circle-4 {margin-left: 7%;}
	}
	@media (max-width: 400px) {
		.status-user-circle-2 {margin-left: 5%;}
		.status-user-circle-3 {margin-left: 5%;}
		.status-user-circle-4 {margin-left: 5%;}
	}
	@media (max-width: 360px) {
		.status-user-circle-2 {margin-left: 1%;}
		.status-user-circle-3 {margin-left: 1%;}
		.status-user-circle-4 {margin-left: 1%;}
	}
	@media (max-width: 320px) {
		.status-user-circle-2 {margin-left: -1px;}
		.status-user-circle-3 {margin-left: -1px;}
		.status-user-circle-4 {margin-left: -1px;}
	}
</style>

<div class="mt-4 pl-4 pr-4">
	<div class="progress">
		<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?=$progress_career?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress_career?>%"></div>
	</div>
	<div tabindex="0" class="status-user-circle status-user-circle-1" style="background: <?=$bg_color[0]?>;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Новичок" data-content="<b>Условия:</b> заполнение профиля на 100%.<br><b>Возможность:</b><br>- зарабатывать со своих продаж,<br>- привлекать новичков,<br>- быть поставщиком,<br>- быть инвестором,<br>- быть агентом.">
		<img src="/assets/images/career_status_1.png" alt="Newbie">
	</div>
	<div tabindex="1" class="status-user-circle status-user-circle-2" style="background: <?=$bg_color[1]?>;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Дропшиппер" data-content="<b>Условия:</b> сделать лично 3 продажи.<br><b>Возможность:</b><br>- XML выгрузка товаров,<br>- зарабатывать со своих продаж,<br>- привлекать новичков,<br>- быть поставщиком,<br>- быть инвестором,<br>- быть агентом.">
		<img src="/assets/images/career_status_2.png" alt="Seller">
	</div>
	<div tabindex="2" class="status-user-circle status-user-circle-3" style="background: <?=$bg_color[2]?>;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Наставник" data-content="<b>Условия:</b> сделать лично или вместе с командой 1000 продаж.<br><b>Возможность:</b><br>- зарабатывать со своих продаж,<br>- получать доход от оборота своей команды,<br>- быть поставщиком,<br>- быть инвестором,<br>- быть агентом.">
		<img src="/assets/images/career_status_3.png" alt="Manager">
	</div>
	<div tabindex="3" class="status-user-circle status-user-circle-4" style="background: <?=$bg_color[3]?>;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Супервайзер" data-content="<b>Условия:</b> сделать лично или вместе с командой 10 000 продаж, вырастить 3 собственных наставника.<br><b>Возможность:</b><br>- зарабатывать со своих продаж,<br>- получать доход от оборота своей команды,<br>- быть поставщиком,<br>- быть инвестором,<br>- быть агентом.">
		<img src="/assets/images/career_status_4.png" alt="Supervisor">
	</div>
	<div tabindex="4" class="status-user-circle status-user-circle-5" style="background: <?=$bg_color[4]?>;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Директор" data-content="<b>Условия:</b> сделать лично или вместе с командой 100 000 продаж, вырастить 15 собственных наставников и 1 собственного супервайзера.<br><b>Возможность:</b><br>- зарабатывать со своих продаж,<br>- получать доход от оборота своей команды,<br>- быть поставщиком,<br>- быть инвестором,<br>- быть агентом.">
		<img src="/assets/images/career_status_5.png" alt="Director">
	</div>
</div>

<?

$allow_partner_url = false;

if (
	!empty($user['name']) 
	&& !empty($user['surname']) 
	&& !empty($user['nickname']) 
	&& $user['birthday'] != '0000-00-00' 
	&& !empty($user['sex']) 
	&& !empty($user['country']) 
	&& !empty($user['region']) 
	&& !empty($user['city']) 
	&& !empty($user['lang']) 
	&& !empty($user['mail']) 
	&& !empty($user['phone']) 
	&& !empty($user['telegram']) 
	&& !empty($user['card']) 
) {
	$allow_partner_url = true;
}

?>

<div class="row">
	<div class="col-sm-12">
			<div class="table-responsive" style="min-height: 300px;">
				<?if ($allow_partner_url):?>
				<p class="text-center pt-3">
					Ваша партнерская ссылка по которой люди смогут присоединиться в Вашу команду: <a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user['nickname']?>" id="copyPartnerLink" target="_blank"><?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user['nickname']?></a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
				</p>
				<p class="text-center pb-3">
					<?

					$sql = "SELECT COUNT(1) AS lsmcount FROM `landdrop_statistic` WHERE `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$landdrop_statistic_my_count = mysqli_fetch_assoc($query);

					?>
					Количество посещений моей партнерской ссылки: <b><?=$landdrop_statistic_my_count['lsmcount']?></b>
				</p>
				<div class="row">
					<div class="col-xl-4 col-md-6 mb-3 text-center text-md-left">
						<a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user['nickname']?>" target="_blank">
							<img src="/assets/images/banner.gif" alt="Banner" style="max-width: 300px;width: 100%;">
						</a>
					</div>
					<div class="col-xl-8 col-md-6 mb-3">
						<p class="font-italic text-info">Поставьте себе этот код на баннер на свой блог или сайт. В него уже встроеная Ваша партнерская ссылка.</p>
						<figure style="padding: .2rem .4rem; font-size: 120%; color: #bd4147; background-color: #f7f7f9; border-radius: .25rem;">
							<code id="copyPartnerLinkBanner">
								&lt;a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user['nickname']?>" target="_blank"&gt;&lt;img src="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/assets/images/banner.gif" alt="Banner"&gt;&lt;/a&gt;
							</code>
						</figure>
						<a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLinkBanner" onclick="copyLink(this)">Копировать</a>
					</div>
				</div>
				<?else:?>
				<p class="text-center pt-3 pb-3">Вам еще недоступна ссылка для регистрации своих партнеров. Чтобы получить ее, вам нужно получить следующий статус "Дропшиппер". Наведите курсор мыши или нажмите на него и вы узнаете детали условий.</p>
				<?endif;?>
				<!-- <p class="text-center pt-3 pb-3">
					Ваша партнерская ссылка для MailerLite: <a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/register/<?=$user['nickname']?>" target="_blank"><?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/register/<?=$user['nickname']?></a>
				</p> -->
				<table class="table table-hover table-partners text-center" style="font-size: 90%;">
					<thead class="thead-light">
						<tr>
							<th class="text-left">Имя/Фамилия</th>
							<th>Линия</th>
							<th>Статус</th>
							<th>Партнеров (<?=$data_network['countusers']?>)</th>
							<th>ДЗ</th>
							<th>Контакты</th>
							<th>Зарегистрирован</th>
							<th>Действия</th>
						</tr>
					</thead>
					<tbody>
					<?	

						foreach ($data_network['tr_all'] as $tr) echo $tr;

					?>
					</tbody>
				</table>
			</div>
	</div>
</div>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

// Проверяем нужны ли стрелки назад  
if ($page != 1) $pervpage = '<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only"><<</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page='. ($page - 1) .'" aria-label="Previous">
									<span aria-hidden="true">&#8249;</span>
									<span class="sr-only"><</span>
								</a>
							</li>';
else $pervpage = '<li class="page-item disabled">
					<span class="page-link">&laquo;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&#8249;</span>
				</li>';

// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = '<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page='. ($page + 1) .'" aria-label="Next">
										<span aria-hidden="true">&#8250;</span>
										<span class="sr-only">></span>
									</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page=' .$total. '" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">>></span>
									</a>
								</li>';
else $nextpage = '<li class="page-item disabled">
					<span class="page-link">&#8250;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&raquo;</span>
				</li>';

// Находим две ближайшие станицы с обоих краев, если они есть  
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 2) .'>'. ($page - 2) .'</a></li>';  
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 1) .'>'. ($page - 1) .'</a></li>';  
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 2) .'>'. ($page + 2) .'</a></li>';  
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 1) .'>'. ($page + 1) .'</a></li>'; 

//Текущая страница
$currentpage = '<li class="page-item active"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<?/*?>
<?if ($user['employee'] == 1):?>

<?

$level = 0;
$data = array('countusers' => 0, 'tr_all' => array());

//pagementor views
$num = 20;
		
$sql = "SELECT COUNT(1) as count FROM `users` WHERE `partner_id`='{$user['partner_id']}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$posts = mysqli_fetch_assoc($query);
		
$total = intval(($posts['count'] - 1) / $num) + 1;
		
$page = intval($_GET['pagementor']);
		
if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  
		
$start = $page * $num - $num;

$startnum = array($start, $num);
if ($posts['count'] == 0) $startnum = array();
//pagementor views

$data_network = displayNetwork($db, $user['partner_id'], $level, $data, $startnum);

?>

<div class="row">
	<div class="col-sm-12">
			<div class="table-responsive" style="min-height: 300px;">
				<h3 class="text-center pt-3 pb-3">Команда моего наставника</h3>
				<table class="table table-hover table-partners text-center" style="font-size: 90%;">
					<thead class="thead-light">
						<tr>
							<th class="text-left">Имя/Фамилия</th>
							<th>Линия</th>
							<th>Статус</th>
							<th>Партнеров (<?=$data_network['countusers']?>)</th>
							<th>ДЗ</th>
							<th>Контакты</th>
							<th>Зарегистрирован</th>
							<th>Действия</th>
						</tr>
					</thead>
					<tbody>
					<?	

						foreach ($data_network['tr_all'] as $tr) echo $tr;

					?>
					</tbody>
				</table>
			</div>
	</div>
</div>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

// Проверяем нужны ли стрелки назад  
if ($page != 1) $pervpage = '<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?pagementor=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only"><<</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?pagementor='. ($page - 1) .'" aria-label="Previous">
									<span aria-hidden="true">&#8249;</span>
									<span class="sr-only"><</span>
								</a>
							</li>';
else $pervpage = '<li class="page-item disabled">
					<span class="page-link">&laquo;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&#8249;</span>
				</li>';

// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = '<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?pagementor='. ($page + 1) .'" aria-label="Next">
										<span aria-hidden="true">&#8250;</span>
										<span class="sr-only">></span>
									</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?pagementor=' .$total. '" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">>></span>
									</a>
								</li>';
else $nextpage = '<li class="page-item disabled">
					<span class="page-link">&#8250;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&raquo;</span>
				</li>';

// Находим две ближайшие станицы с обоих краев, если они есть  
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?pagementor='. ($page - 2) .'>'. ($page - 2) .'</a></li>';  
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?pagementor='. ($page - 1) .'>'. ($page - 1) .'</a></li>';  
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?pagementor='. ($page + 2) .'>'. ($page + 2) .'</a></li>';  
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?pagementor='. ($page + 1) .'>'. ($page + 1) .'</a></li>'; 

//Текущая страница
$currentpage = '<li class="page-item active"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<?endif;?>
<?*/?>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>