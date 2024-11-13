<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<!-- <div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-4">
		<a href="/account/school/" class="btn btn-warning btn-block mb-3 text-uppercase"><i class="fa fa-graduation-cap"></i> Уроки</a>
	</div>
	<div class="col-lg-2"></div>
	<div class="col-lg-4">
		<a href="/account/school_homework/" class="btn btn-primary btn-block mb-3 text-uppercase font-weight-bold"><i class="fa fa-file-text"></i> Домашнее задание</a>
	</div>
	<div class="col-lg-1"></div>
</div> -->

<?=$alert_message?>

<p class="text-center pt-3">
	<?if(in_array($user_id, array(2,4,5,340,348,368,496,560,1144,4108,5715,6264,6679))):?>
	Ваша партнерская ссылка на лендинг Полессье: <a href="https://polissia.km.ua/u_<?=$user['nickname']?>" id="copyPartnerLink" target="_blank">https://polissia.km.ua/u_<?=$user['nickname']?></a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
	<?else:?>
	<a href="/account/goods/lending_polese/195893" class="btn btn-warning btn-lg" style="white-space: normal;">Получить Лендинг ТМ Полесье</a>
	<?endif;?>
</p>

<div class="row mt-4 mb-5 justify-content-center">
	<div class="col-sm-10">
		
<?

		$user_partner_id = $user['partner_id'];

		$sql_nastavnyk = "SELECT * FROM `users` WHERE `id`='{$user_partner_id}'";
		$query_nastavnyk = mysqli_query($db, $sql_nastavnyk) or die(mysqli_error());
		$user_nastavnyk = mysqli_fetch_assoc($query_nastavnyk);
		
		$user_telegram = (!empty($user_nastavnyk['telegram'])) ? '<a href="https://t.me/'.$user_nastavnyk['telegram'].'" class="btn btn-link btn-lg font-weight-bold" target="_blank"><img src="/assets/images/social/telegram.png" alt="Telegram" width="20"> @'.$user_nastavnyk['telegram'].'</a>' : 'еще не указан';

?>
		
		<div class="card">
			<div class="card-header text-center">Связаться с наставником в Telegram</div>
			<div class="card-body p-2">
				<h4 class="text-center font-weight-bold mb-0"><?=$user_telegram?></h4>
			</div>
		</div>
	</div>
</div>

<div class="row mt-3">
	<div class="col-lg-4">
		<h5 class="text-center mb-3 mx-auto" style="max-width: 340px;">
			<span class="badge badge-dark pl-2 pr-2">1</span>
			<br>
			<span class="text-dark"><i class="fa fa-search"></i> Выберите товар</span>
		</h5>
	</div>
	<div class="col-lg-4">
		<h5 class="text-center mb-3 mx-auto" style="max-width: 340px;">
			<span class="badge badge-primary pl-2 pr-2">2</span>
			<br>
			<span class="text-primary"><i class="fa fa-link"></i> Добавьте ссылку на публикацию</span>
		</h5>
	</div>
	<div class="col-lg-4">
		<h5 class="text-center mb-3 mx-auto" style="max-width: 340px;">
			<span class="badge badge-success pl-2 pr-2">3</span>
			<br>
			<span class="text-success"><i class="fa fa-paper-plane"></i> Отправьте на проверку</span> 
		</h5>
	</div>
</div>

<p class="text-center mt-4 mb-5">
	<button class="btn btn-success btn-lg" onclick="schoolAddHomework()">
		<i class="fa fa-plus-circle"></i> Добавить задание
	</button>
</p>

<?

$num = 50;
		
$sql = "SELECT COUNT(1) as count FROM `school_homework` WHERE `user_id`='{$user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$posts = mysqli_fetch_assoc($query);
		
$total = intval(($posts['count'] - 1) / $num) + 1;
		
$page = intval($_GET['page']);
		
if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  
		
$start = $page * $num - $num;

$sql = "SELECT * FROM `school_homework` WHERE `user_id`='{$user_id}' ORDER BY FIELD(`status`,2,0,1) ASC, `updated` DESC LIMIT $start, $num";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

	while ($school_homework = mysqli_fetch_assoc($query)) {

		$school_homework_goods_id = $school_homework['goods_id'];

		$sql_goods = "SELECT * FROM `goods` WHERE `id`='{$school_homework_goods_id}'";
		$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
		$goods_count = mysqli_num_rows($query_goods);
		$goods = mysqli_fetch_assoc($query_goods);

		if ($goods_count > 0) {

			$goods['photo'] = json_decode($goods['photo'], true);
			$goods['name'] = json_decode($goods['name'], true);

			list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods_thumb/'.$goods['photo']['img0']);

			if ($goods_photo_w > $goods_photo_h) {

				$goods_photo_width = '100%';
				$goods_photo_height = 'auto';

			} else {
				
				$goods_photo_width = 'auto';
				$goods_photo_height = '100%';

			}

		}

		$school_homework['link_ad'] = json_decode($school_homework['link_ad'], true);

		if ($school_homework['status'] == 0) $school_homework['status'] = '<b class="text-warning">На проверке</b>';
		elseif ($school_homework['status'] == 1) $school_homework['status'] = '<b class="text-success">Проверено</b>';
		elseif ($school_homework['status'] == 2) $school_homework['status'] = '<b class="text-danger">Отменено</b>';

?>

<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-lg-3 mb-3 text-lg-left text-center">
				Код: <b><?=$school_homework['id']?></b>
			</div>
			<div class="col-lg-4 mb-3 text-lg-right text-center">
				<span>Задание размещено: <b><i class="fa fa-flag-checkered"></i> <?=date('d.m.Y H:i', strtotime($school_homework['updated']))?></b></span>
			</div>
			<div class="col-lg-5 mb-3 text-lg-right text-center">
				<span>Объявление деактивируется: <b><i class="fa fa-trophy"></i> <?=date('d.m.Y', strtotime($school_homework['updated'].' +30 days'))?></b></span>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
				<div class="card mb-2">
					<div class="card-body">
						<?if ($goods_count > 0):?>
						<div class="img-in-block mb-2 mx-auto" style="width: 100px;height: 100px;">
							<img src="/data/images/goods_thumb/<?=$goods['photo']['img0']?>" alt="Goods" style="width: <?=$goods_photo_width?>; height: <?=$goods_photo_height?>;">
						</div>
						<p class="text-center mb-2"><?=$goods['name'][$lang]?></p>
						<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" class="btn btn-success btn-block btn-sm" target="_blank">Открыть товар</a>
						<?else:?>
						<p class="text-center mb-0">Товар удален</p>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="col-lg-6 pt-3">

<?

		foreach ($school_homework['link_ad'] as $school_homework_link_ad) {
	
?>

				<p>
					<a href="<?=$school_homework_link_ad?>" target="_blank"><?=$school_homework_link_ad?></a>
				</p>

<?	

		}

?>				

			</div>
			<div class="col-lg-2">
				<p class="text-center"><?=$school_homework['status']?></p>
			</div>
			<div class="col-lg-1">
				<button class="btn btn-warning float-left mr-2 mb-2" data-toggle="tooltip" data-placement="top" title="Редактировать" onclick="schoolEditHomework(<?=$school_homework['id']?>)">
					<i class="fa fa-pencil"></i>
				</button>
				<form method="POST" class="float-right float-lg-left">
					<input type="hidden" name="type_operation" value="delete">
					<input type="hidden" name="homework_id" value="<?=$school_homework['id']?>">
					<button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="return confirm('Вы действительно хотите удалить это задание?')">
						<i class="fa fa-trash"></i>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<?

	}

} else {

?>

<div class="card mb-3">
	<div class="card-body">
		<p class="text-center mb-0">У вас еще нет выполненных домашних заданий</p>
	</div>
</div>

<?

}

?>

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

<div class="modal fade" id="schoolHomework">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Выполнить домашние задание</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<input type="hidden" name="type_operation" value="add">
					<input type="hidden" name="homework_id" value="0">
					<h3 class="text-center text-success mb-3"><span class="badge badge-success">Шаг 1</span> <br><small>Выберите товар</small></h3>
					<div class="form-group row justify-content-center">
						<div class="col-sm-5">
							<div class="card mb-2">
								<div class="card-body" id="goodsSelected"></div>
							</div>
							<button type="button" class="btn btn-warning btn-block" onclick="selectGoodsInCatalog(event, 'catalog')">
								Выбрать товар <i class="fa fa-angle-down"></i>
							</button>
						</div>
					</div>
					<h3 class="text-center text-primary mt-5 mb-3"><span class="badge badge-primary">Шаг 2</span> <br><small>Добавьте ссылки на Ваши объявления</small></h3>
					<div class="form-group">
						<div class="inputs-for-links">
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-link"></i>
									</span>
								</div>
								<input type="url" name="url_ad[]" class="form-control" placeholder="Ссылка на объявление в интернете" required>
								<div class="input-group-append">
									<span class="input-group-text bg-danger text-white border-danger" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="schoolDeleteLinkAdGoods(this)">
										<i class="fa fa-trash"></i>
									</span>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-dark mr-3 float-left" onclick="schoolAddLinkAdGoods(this)">
							<i class="fa fa-plus-circle"></i>
						</button>
						<small class="text-muted">максимум 20 ссылок</small>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success btn-lg" style="white-space: normal;"><i class="fa fa-paper-plane"></i> Отправить НАСТАВНИКУ на проверку <i class="fa fa-paper-plane"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="schoolSelectGoods">
	<div class="modal-dialog modal-lg" style="max-width: 100%;margin-top: 0;">
		<div class="modal-content" style="min-height: 940px;">
			<div class="modal-header">
				<h5 class="modal-title">Выбрать товар</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body goods"></div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>