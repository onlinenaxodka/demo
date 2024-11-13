<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?if (!isset($_GET['user_selected']) or empty($_GET['user_selected'])):?>

<?

$sql = "SELECT * FROM `users` WHERE `id`='{$user_selected_team}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_selected_info = mysqli_fetch_assoc($query);

$num = 30;

$sql = "SELECT COUNT(1) as count FROM `users` WHERE `partner_id`='{$user_selected_team}' AND `admin`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$results = mysqli_fetch_assoc($query);

$total = intval(($results['count'] - 1) / $num) + 1;

$page = intval($_GET['page']);

if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  

$start = $page * $num - $num;

?>

<div class="row">
	<div class="col-md-7 mb-3">
		<form method="POST">
			<div class="row">
				<div class="col-xl-5 pt-2 text-center text-sm-left">
					<label for="user_selected">
						<h5>Партнеры пользователя:</h5>
					</label>
				</div>
				<div class="col-xl-4 col-lg-6 col-sm-8 mb-2">
					<select class="form-control" id="user_selected" name="user_selected">
<?

					$user_selected_allowed_id = implode(',', $user_selected_allowed);

					$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id` IN ({$user_selected_allowed_id})";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$n_select = 0;

					while ($user_selected_option = mysqli_fetch_assoc($query)) {

						$n_select++;
						
						echo '<option value="'.$user_selected_option['id'].'" '.(($user_selected_team == $user_selected_option['id'])?'selected':'').'>'.$n_select.' - '.$user_selected_option['name'].' '.$user_selected_option['surname'].'</option>';

					}

?>
					</select>
				</div>
				<div class="col-xl-3 col-lg-6 col-sm-4 text-right text-sm-left">
					<button type="submit" class="btn btn-primary">Показать</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-5 text-center text-md-right">
		<p>Всего в команде пользователей: <b><?=$results['count']?></b></p>
	</div>
</div>

<?if($user_id == 2041):?>
<p class="text-center pt-3">
	Партнерская ссылка выбраного наставника: <a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user_selected_info['nickname']?>" id="copyPartnerLink" target="_blank"><?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$user_selected_info['nickname']?></a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
</p>
<?endif;?>

<div class="row">
	<div class="col-sm-12">
			<div class="table-responsive" style="min-height: 300px;">
				<table class="table table-hover text-center" style="font-size: 90%;">
					<thead class="thead-light">
						<tr>
							<th>ID</th>
							<th>Имя/Фамилия</th>
							<th>Телефон</th>
							<th>E-mail</th>
							<th>Зарегистрирован</th>
							<th>Недозвон</th>
							<th>Действие</th>
						</tr>
					</thead>
					<tbody>

<?

					$current_date_php = date('Y-m-d');

					$sql = "SELECT *, IF (`notify`='{$current_date_php}',1,0) AS notify_c_d, (SELECT COUNT(1) FROM `users_comments` WHERE `users_comments`.`child_id`=`users`.`id`) AS count_comments FROM `users` WHERE `partner_id`='{$user_selected_team}' AND `admin`=0 ORDER BY notify_c_d DESC, count_comments ASC, `created` DESC LIMIT $start, $num";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					
					if (mysqli_num_rows($query) > 0) {

						while ($user_selected = mysqli_fetch_assoc($query)) {

							$user_selected_id = $user_selected['id'];

							if ($user_selected['notify'] == date('Y-m-d', strtotime('now'))) {
								if ($user_selected['notcall'] > 0) $notify_class = 'table-danger ';
								else $notify_class = 'table-warning ';
								$notify_act = '<span class="text-secondary">Обработать сегодня</span><br>';
							} else {
								if ($user_selected['notcall'] > 0) $notify_class = 'table-danger ';
								else $notify_class = '';
								$notify_act = '';
							}

							if (empty($user_selected['phone'])) $user_selected['phone'] = 'не указан';

							$sql_users_comments = "SELECT `id` FROM `users_comments` WHERE `child_id`='{$user_selected_id}'";
							$query_users_comments = mysqli_query($db, $sql_users_comments);
							if (mysqli_num_rows($query_users_comments) > 0) $users_comments_class = 'table-success';
							else $users_comments_class = '';

							$tr_class = $notify_class.$users_comments_class;
							
?>

						<tr class="<?=$tr_class?>">
							<th><?=$user_selected['id']?></th>
							<td><?=$user_selected['name']?> <?=$user_selected['surname']?></td>
							<td><?=$user_selected['phone']?></td>
							<td>
								<button class="btn btn-link btn-clipboard p-0 border-0<?=($user_selected['activated']==0)?' text-danger':''?>" data-clipboard-text="<?=$user_selected['mail']?>" onclick="copyLink(this)"><?=$user_selected['mail']?></button>
							</td>
							<td><?=date('d.m.Y H:i', strtotime($user_selected['created']))?></td>
							<td>
								<?if($user_selected['notcall'] == 1):?>
								<img src="/assets/images/notcall.png" width="20">
								<?elseif($user_selected['notcall'] == 2):?>
								<img src="/assets/images/notcall.png" width="20">
								<img src="/assets/images/notcall.png" width="20">
								<?elseif($user_selected['notcall'] == 3):?>
								<img src="/assets/images/notcall.png" width="20">
								<img src="/assets/images/notcall.png" width="20">
								<img src="/assets/images/notcall.png" width="20">
								<?else:?>
								<span>-</span>
								<?endif;?>
							</td>
							<td>
								<?=$notify_act?>
								<a href="/account/partners_work/?user_selected=<?=$user_selected['id']?>" class="btn btn-success btn-sm">Открыть карточку</a>
							</td>
						</tr>

<?

						}

					} else {

?>

						<tr>
							<td colspan="6" align="center" valign="middle" height="100">У пользователя еще нет партнёров</td>
						<tr>

<?

					}

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

<?else:?>

<?

$user_selected = intval($_GET['user_selected']);

$sql = "SELECT * FROM `users` WHERE `id`='{$user_selected}' AND `partner_id`='{$user_selected_team}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

	$user_selected = mysqli_fetch_assoc($query);

	$user_selected_id = $user_selected['id'];

	if (empty($user_selected['phone'])) $user_selected['phone'] = 'телефон не указан';

	$user_mail_status = '<b class="text-danger">не активирован</b>';
	if ($user_selected['activated'] == 1) $user_mail_status = '<b class="text-success">активирован</b>';

?>

<div class="row mb-3">
	<div class="col-sm-3">
		<div class="card">
<?	
			$img_photo = '<img src="/data/images/users/user.jpg" class="card-img-top" alt="User Photo">';
			$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
			for ($i = 0; $i < count($type_img); $i++) { 
				$img_name = __DIR__ . '/../data/images/users/user'.$user_selected_id.'.'.$type_img[$i];
				if (file_exists($img_name)) {
					$img_photo = '<img src="/data/images/users/user'.$user_selected_id.'.'.$type_img[$i].'" class="card-img-top" alt="User Photo">';
				}
			}
			echo $img_photo;
?>
			<div class="card-body">
				<h5 class="card-title text-center mb-0"><?=$user_selected['name']?> <?=$user_selected['surname']?></h5>
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-6">
				<ul class="list-group">
					<li class="list-group-item">
						<b>ID:</b> <?=$user_selected['work_id']?>
					</li>
					<li class="list-group-item">
						<b>Телефон:</b><br>
						<?=$user_selected['phone']?>
					</li>
					<li class="list-group-item">
						<b>E-mail:</b><br>
						<button class="btn btn-link btn-clipboard p-0 border-0" data-clipboard-text="<?=$user_selected['mail']?>" onclick="copyLink(this)"><?=$user_selected['mail']?></button>
						<br>
						<?=$user_mail_status?>
					</li>
					<li class="list-group-item">
						<b>Telegram:</b><br>
						<?=((!empty($user_selected['telegram'])) ? '<a href="https://t.me/'.$user_selected['telegram'].'" target="_blank">@'.$user_selected['telegram'].'</a>' : 'еще не указан')?>
					</li>
				</ul>
			</div>
			<div class="col-sm-6">
				<form method="POST">
					<input type="hidden" name="child" value="<?=$user_selected_id?>">
					<div class="form-group">
						<label>Установить дату напоминания:</label>
						<div class="input-group mb-3">
							<input type="date" name="date" class="form-control" placeholder="Введите дату" value="<?=$user_selected['notify']?>" required>
							<div class="input-group-append">
								<button type="submit" class="btn btn-success">Напомнить</button>
							</div>
						</div>
						<label class="mr-3">Недозвонов:</label>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioNotcall0" name="notcall" class="custom-control-input" value="0" <?=(($user_selected['notcall'] == 0)?'checked':'')?>>
							<label class="custom-control-label" for="customRadioNotcall0">0</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioNotcall1" name="notcall" class="custom-control-input" value="1" <?=(($user_selected['notcall'] == 1)?'checked':'')?>>
							<label class="custom-control-label" for="customRadioNotcall1">1</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioNotcall2" name="notcall" class="custom-control-input" value="2" <?=(($user_selected['notcall'] == 2)?'checked':'')?>>
							<label class="custom-control-label" for="customRadioNotcall2">2</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="customRadioNotcall3" name="notcall" class="custom-control-input" value="3" <?=(($user_selected['notcall'] == 3)?'checked':'')?>>
							<label class="custom-control-label" for="customRadioNotcall3">3</label>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_selected_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_all = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_selected_id}' AND `status`=3";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_status3 = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_selected_id}' AND `status`=7";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_status7 = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_selected_id}' AND `status`=8";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_status8 = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `orders` WHERE `user_id`='{$user_selected_id}' AND `status`=9";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$user_orders_status9 = mysqli_num_rows($query);

?>

<div class="card mb-3">
	<div class="card-header">Количество заказов: <span class="float-right">(Всего - <b><?=$user_orders_all?></b>)</span></div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-3">
				<p>В обработке: <b><?=$user_orders_status3?></b></p>
			</div>
			<div class="col-sm-3">
				<p>Завершенных: <b><?=$user_orders_status7?></b></p>
			</div>
			<div class="col-sm-3">
				<p>Отмененных: <b><?=$user_orders_status8?></b></p>
			</div>
			<div class="col-sm-3">
				<p>Отказов: <b><?=$user_orders_status9?></b></p>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">История комментариев</div>
			<div class="card-body">
				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="child" value="<?=$user_selected_id?>">
					<div class="form-group">
						<textarea name="comment" class="form-control" placeholder="Введите комментарий..." rows="3" style="min-height: 86px;" required></textarea>
					</div>
					<div class="form-group">
						<input type="file" name="audio" class="form-control" accept="audio/mp3">
					</div>
					<p class="text-center mt-3 pb-4">
						<button type="submit" class="btn btn-primary pl-5 pr-5">Добавить</button>
					</p>
				</form>
<?

$sql = "SELECT * FROM `users_comments` WHERE `child_id`='{$user_selected_id}' ORDER BY `created` DESC";
$query = mysqli_query($db, $sql);

if (mysqli_num_rows($query) > 0) {

	while ($users_comments = mysqli_fetch_assoc($query)) {

		$sql_u_w_c = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$users_comments['user_id']}'";
		$query_u_w_c = mysqli_query($db, $sql_u_w_c);
		$user_writed_comment = mysqli_fetch_assoc($query_u_w_c);

		$users_comments['comment'] = str_replace("\r\n", "<br>", str_replace("'", "\'", $users_comments['comment']));

?>

				<div class="card bg-light mt-3">
					<div class="card-header pt-1 pb-1">
						<div class="row">
							<div class="col-7">
								<?=$user_writed_comment['name']?> <?=$user_writed_comment['surname']?>
							</div>
							<div class="col-5 text-right">
								<span class="font-weight-normal font-italic"><?=date('d.m.Y, H:i', strtotime($users_comments['created']))?></span>
							</div>
						</div>
					</div>
					<div class="card-body">
						<p class="card-text"><?=$users_comments['comment']?></p>
<?

		if (!empty($users_comments['audio_name'])) {

?>

						<audio class="d-block mx-auto" controls>
							<source src="/data/files/mp3/users_comments/<?=$users_comments['audio_name']?>" type="audio/mpeg">
							Тег audio не поддерживается вашим браузером. <a href="/data/files/mp3/users_comments/<?=$users_comments['audio_name']?>">Скачайте запись</a>.
						</audio>

<?

		}

?>
					</div>
				</div>

<?

	}

} else {

?>

				<p class="text-center">Еще нет комментариев</p>

<?

}

?>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">Домашние задания</div>
			<div class="card-body">
<?

$sql_homework = "SELECT * FROM `school_homework` WHERE `user_id`='{$user_selected_id}' ORDER BY FIELD(`status`,0,2,1) ASC, `updated` ASC";
$query_homework = mysqli_query($db, $sql_homework) or die(mysqli_error($db));

if (mysqli_num_rows($query_homework) > 0) {

	$user_homework_data = '';

	while ($school_homework = mysqli_fetch_assoc($query_homework)) {

		$school_homework_goods_id = $school_homework['goods_id'];

		$sql_goods = "SELECT `id`, `category`, `name`, `photo` FROM `goods` WHERE `id`='{$school_homework_goods_id}'";
		$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query_goods);

		$goods['name'] = json_decode($goods['name'], true);
		$goods['photo'] = json_decode($goods['photo'], true);

		list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods_thumb/'.$goods['photo']['img0']);

		if ($goods_photo_w > $goods_photo_h) {

			$goods_photo_width = '100%';
			$goods_photo_height = 'auto';

		} else {
			
			$goods_photo_width = 'auto';
			$goods_photo_height = '100%';

		}

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
		<div class="row">
			<div class="col-sm-3 pr-1">
				<div class="img-in-block img-thumbnail" style="width: 70px;height: 70px;">
					<img src="/data/images/goods_thumb/'.$goods['photo']['img0'].'" alt="Goods" style="width: '.$goods_photo_width.'; height: '.$goods_photo_height.';">
				</div>
			</div>
			<div class="col-sm-9 pl-1">
				<a href=\'/account/goods/'.$goods['category'].'/'.$goods['id'].'\' target=\'_blank\'>'.$goods['name']['ru'].'</a>
			</div>
		</div>
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
					<input type=\'hidden\' name=\'child\' value=\''.$user_selected_id.'\'>
					<button type=\'submit\' class=\'btn btn-success btn-block btn-sm mb-3\' onclick=\'return partnersConfirmHomework()\'>Подтвердить</button>
				</form>
				<form method=\'POST\' class=\''.$homework_d_none.'\'>
					<input type=\'hidden\' name=\'homework_act\' value=\'2\'>
					<input type=\'hidden\' name=\'homework_id\' value=\''.$school_homework['id'].'\'>
					<input type=\'hidden\' name=\'child\' value=\''.$user_selected_id.'\'>
					<button type=\'submit\' class=\'btn btn-danger btn-block btn-sm\' onclick=\'return partnersCancelHomework()\'>Отменить</button>
				</form>
			</div>
		</div>
	</div>
</div>';

	}

} else {

	$user_homework_data = '<p class=\'text-center mt-3\'>У пользователя еще нет выполненных домашних заданий</p>';

}

echo $user_homework_data;

?>
			</div>
		</div>
	</div>
</div>

<?

} else {

?>

<p class="text-center mt-5">Такой карточки не существует</p>

<?

}

?>

<?endif;?>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>