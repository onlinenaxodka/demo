<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?=$alert_message?>

<?

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');


$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_eur = mysqli_fetch_assoc($query);
$api_exchange_rate_eur['buy'] = number_format($api_exchange_rate_eur['buy'], 2, '.', '');
$api_exchange_rate_eur['sale'] = number_format($api_exchange_rate_eur['sale'], 2, '.', '');

?>

<div class="row mb-3">
	<div class="col-sm-2">
		<button class="btn btn-warning btn-block" data-toggle="modal" data-target="#filterGoods">Фильтр</button>
	</div>
	<div class="col-sm-3">
		<form method="POST">
			<div class="row">
				<div class="col-sm-7">
					<input type="number" name="goods_code" class="form-control" placeholder="Код товара" required>
				</div>
				<div class="col-sm-5">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
		<form method="GET">
			<div class="row mt-2">
				<div class="col-sm-7">
					<input type="text" name="goods_search" class="form-control" placeholder="Артикул товара" required>
				</div>
				<div class="col-sm-5">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
		<form method="GET">
			<div class="row mt-2">
				<div class="col-sm-7">
					<input type="text" name="goods_search_name" class="form-control" placeholder="Название товара" required>
				</div>
				<div class="col-sm-5">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-2">
		<?if($user['admin'] == 1):?>
		<button class="btn btn-danger btn-block" data-toggle="modal" data-target="#googleLinks">Прайсы поставщиков</button>
		<?endif;?>
		<button class="btn btn-danger btn-block mt-2" data-toggle="modal" data-target="#infoProviders">Расписание/Информация</button>
	</div>
	<div class="col-sm-3">

<?

		// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `goods`";
		if ($user['admin'] == 2 and $user_id != 5672) {
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `user_id`='{$user_id}'";
		}
		if (!empty($_SESSION['filter_goods_admin'])) {

			$category_filter = $_SESSION['filter_goods_admin'];
			if ($category_filter=='without') $category_filter = '';
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `category`='{$category_filter}'";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `user_id`='{$user_id}' AND `category`='{$category_filter}'";
			}

		} elseif (isset($_SESSION['filter_goods_visits']) and $_SESSION['filter_goods_visits'] == 1) {
			
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id` GROUP BY `goods_visits`.`goods_id`)";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id` GROUP BY `goods_visits`.`goods_id`) AND `user_id`='{$user_id}'";
			}

		} elseif (isset($_SESSION['filter_goods_homework']) and $_SESSION['filter_goods_homework'] == 1) {
			
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1 GROUP BY `school_homework`.`goods_id`)";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1 GROUP BY `school_homework`.`goods_id`) AND `user_id`='{$user_id}'";
			}

		} elseif (!empty($_GET['goods_code'])) {

			$goods_code = test_request($_GET['goods_code']);
			$goods_code = intval($goods_code);
			if ($goods_code > 0) $sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id`='{$goods_code}'";
			if ($user['admin'] == 2 and $user_id != 5672) {
				if ($goods_code > 0) $sql = "SELECT COUNT(1) as count FROM `goods` WHERE `id`='{$goods_code}' AND `user_id`='{$user_id}'";
			}

		} elseif (!empty($_GET['goods_search'])) {

			$goods_search = test_request($_GET['goods_search']);
			
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `vendor_code`='{$goods_search}'";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `vendor_code`='{$goods_search}' AND `user_id`='{$user_id}'";
			}

		} elseif (!empty($_GET['goods_search_name'])) {

			$goods_search_name = test_request($_GET['goods_search_name']);
			$goods_search_name = str_replace("'", "\'", $goods_search_name);
			
			$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `name` LIKE '%{$goods_search_name}%'";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `name` LIKE '%{$goods_search_name}%' AND `user_id`='{$user_id}'";
			}

		} elseif (!empty($_SESSION['sql'])) {
			$sql = preg_replace('/SELECT.+FROM/i', 'SELECT COUNT(1) as count FROM', $_SESSION['sql']);
		}
		$query = mysqli_query($db, $sql) or die(mysqli_error());
		$posts = mysqli_fetch_assoc($query);
		// Находим общее число страниц
		$total = intval(($posts['count'] - 1) / $num) + 1;
		// Определяем начало сообщений для текущей страницы
		$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
		// Если значение $page меньше единицы или отрицательно  
		// переходим на первую страницу  
		// А если слишком большое, то переходим на последнюю  
		if(empty($page) or $page < 0) $page = 1;  
		if($page > $total) $page = $total;  
		// Вычисляем начиная к какого номера  
		// следует выводить сообщения  
		$start = $page * $num - $num;

$pervpage = '';
$page2left = '';
$page1left = '';
$currentpage = '';
$page1right = '';
$page2right = '';
$nextpage = '';

if ($page == 1) $PHP_SELF = '/admin/goods/';
else $PHP_SELF = '';

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

?>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage; 

?>
		
	</ul>
</nav>

		<?if($user['admin'] == 1 && $user['id'] == 2):?>
		<form method="POST" class="mt-2">
			<textarea name="sql" class="form-control mb-1" placeholder="Input SQL"><?=($_SESSION['sql'] ? $_SESSION['sql'] : '')?></textarea>
			<button class="btn btn-success" type="submit">Request</button>
		</form>
		<form method="POST" style="position: absolute;right: 15px;bottom: 0;">
			<input type="hidden" name="cancel_sql" value="1">
			<button class="btn btn-danger float-right" type="submit">Cancel</button>
		</form>
		<?endif;?>

	</div>
	<div class="col-sm-2">
		<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#addGoods">Добавить товар</button>
	</div>
</div>

<div class="row">
	<div class="col-sm-2 mb-3">
		<form method="POST">
		<?if(empty($_SESSION['filter_goods_visits'])):?>
			<input type="hidden" name="filter_goods_visits" value="1">
			<button type="submit" class="btn btn-dark btn-sm">Сортировать по просмотрам</button>
		<?else:?>
			<input type="hidden" name="filter_goods_visits" value="2">
			<button type="submit" class="btn btn-danger btn-sm">Очистить сортировку просмотров</button>
		<?endif;?>
		</form>
	</div>
	<div class="col-sm-2 mb-3">
		<form method="POST">
		<?if(empty($_SESSION['filter_goods_homework'])):?>
			<input type="hidden" name="filter_goods_homework" value="1">
			<button type="submit" class="btn btn-info btn-sm">Сортировать по ДЗ</button>
		<?else:?>
			<input type="hidden" name="filter_goods_homework" value="2">
			<button type="submit" class="btn btn-danger btn-sm">Очистить сортировку ДЗ</button>
		<?endif;?>
		</form>
	</div>
	<div class="col-sm-8 mb-3">
		<p class="text-right mb-0">Найдено товаров: <b><?=$posts['count']?></b></p>
	</div>
</div>

<?if($user['admin'] == 2):?>
<div class="row justify-content-center">
	<div class="col-sm-7">
		<form method="POST">
			<div class="form-group text-center mb-0">
				<label for="providerUrlYML" class="font-weight-bold">
					<h3>Формат документа выгрузки - YML/XML</h3>
				</label>
			</div>
			<div class="row">
				<div class="col-sm-8 mb-3">
					<input type="url" name="provider_url_yml" maxlength="255" placeholder="Например, https://onlinenaxodka.com/document.xml" class="form-control form-control-lg" id="providerUrlYML" required>
				</div>
				<div class="col-sm-4 mb-3">
					<button type="submit" class="btn btn-success btn-lg">Отправить на модерацию</button>
				</div>
			</div>
		</form>
<?

						$sql_users_puy = "SELECT * FROM `provider_url_yml` WHERE `user_id`='{$user_id}'";
						$query_users_puy = mysqli_query($db, $sql_users_puy) or die(mysqli_error($db));

						if (mysqli_num_rows($query_users_puy) > 0) echo '<p>Мои ссылки на выгрузку:</p>';

						while ($users_puy = mysqli_fetch_assoc($query_users_puy)) {

							echo '<p>'.date('d.m.Y H:i', strtotime($users_puy['created'])).': <a href="'.$users_puy['url'].'" target="_blank">'.$users_puy['url'].'</a></p>';

						}

?>
	</div>
</div>
<?endif;?>

<div class="table-responsive">
<table class="table table-sm table-hover tree goods-catalog">
	<thead class="thead-light">
		<tr>
			<th>
				<input type="checkbox" name="all_goods_check" value="all_goods" data-toggle="tooltip" title="Отметить все" style="width: 25px; height: 25px;">
			</th>
			<th>Код</th>
			<th style="max-width: 50px;">Фото</th>
			<th>Артикул</th>
			<th>Название</th>
			<th style="max-width: 200px;">Категория</th>
			<th title="Количество" style="text-decoration: underline;">Кол.</th>
			<th title="Закупівельна ціна" style="text-decoration: underline;">З цена</th>
			<?if ($user['admin'] == 1):?>
			<th title="Агентська ціна" style="text-decoration: underline;">А цена</th>
			<?endif;?>
			<th title="Роздрібна ціна" style="text-decoration: underline;">Р цена</th>
			<th title="Ціна конкурента" style="text-decoration: underline; width: 100px;">К цена</th>
			<th title="Грязна маржа" style="text-decoration: underline;">Сума</th>
			<th title="Статус" class="text-center" style="text-decoration: underline;"><i class="fa fa-eye" aria-hidden="true"></i></th>
			<th class="text-center" title="Просмотров" style="text-decoration: underline;">ПР</th>
			<th class="text-center" title="Домашних заданий" style="text-decoration: underline;">ДЗ</th>
			<th class="text-center" title="Действия" style="max-width: 77px;text-decoration: underline;"><i class="fa fa-cog" aria-hidden="true"></i></th>
		</tr>
	</thead>
	<tbody>

<?

		$sql = "SELECT * FROM `goods` ORDER BY `status` DESC, `created` DESC LIMIT $start, $num";

		if ($user['admin'] == 2 and $user_id != 5672) {
			$sql = "SELECT * FROM `goods` WHERE `user_id`='{$user_id}' ORDER BY `status` DESC, `created` DESC LIMIT $start, $num";
		}

		if (!empty($_SESSION['filter_goods_admin'])) {
			$category_filter = $_SESSION['filter_goods_admin'];
			if ($category_filter=='without') $category_filter = '';
			$sql = "SELECT * FROM `goods` WHERE `category`='{$category_filter}' ORDER BY `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT * FROM `goods` WHERE `user_id`='{$user_id}' AND `category`='{$category_filter}' ORDER BY `created` DESC LIMIT $start, $num";
			}
		} elseif (isset($_SESSION['filter_goods_visits']) and $_SESSION['filter_goods_visits'] == 1) {
			
			$sql = "SELECT *, (SELECT COUNT(1) FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id`) AS count_goods_visits FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id` GROUP BY `goods_visits`.`goods_id`) ORDER BY count_goods_visits DESC, `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT *, (SELECT COUNT(1) FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id`) AS count_goods_visits FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `goods_visits` WHERE `goods`.`id`=`goods_visits`.`goods_id` GROUP BY `goods_visits`.`goods_id`) AND `user_id`='{$user_id}' ORDER BY count_goods_visits DESC, `created` DESC LIMIT $start, $num";
			}

		} elseif (isset($_SESSION['filter_goods_homework']) and $_SESSION['filter_goods_homework'] == 1) {
			
			$sql = "SELECT *, (SELECT COUNT(1) FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1) AS count_homework FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1 GROUP BY `school_homework`.`goods_id`) ORDER BY count_homework DESC, `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT *, (SELECT COUNT(1) FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1) AS count_homework FROM `goods` WHERE `id` IN (SELECT `goods_id` FROM `school_homework` WHERE `goods`.`id`=`school_homework`.`goods_id` AND `school_homework`.`status`=1 GROUP BY `school_homework`.`goods_id`) AND `user_id`='{$user_id}' ORDER BY count_homework DESC, `created` DESC LIMIT $start, $num";
			}

		} elseif (!empty($_GET['goods_code'])) {
			$goods_code = test_request($_GET['goods_code']);
			$goods_code = intval($goods_code);
			if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
			if ($goods_code > 0) $sql = "SELECT * FROM `goods` WHERE `id`='{$goods_code}' ORDER BY `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				if ($goods_code > 0) $sql = "SELECT * FROM `goods` WHERE `id`='{$goods_code}' AND `user_id`='{$user_id}' ORDER BY `created` DESC LIMIT $start, $num";
			}
		} elseif (!empty($_GET['goods_search'])) {

			$goods_search = test_request($_GET['goods_search']);
			
			$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$goods_search}' ORDER BY `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$goods_search}' AND `user_id`='{$user_id}' ORDER BY `created` DESC LIMIT $start, $num";
			}

		} elseif (!empty($_GET['goods_search_name'])) {

			$goods_search_name = test_request($_GET['goods_search_name']);
			$goods_search_name = str_replace("'", "\'", $goods_search_name);
			
			$sql = "SELECT * FROM `goods` WHERE `name` LIKE '%{$goods_search_name}%' ORDER BY `created` DESC LIMIT $start, $num";
			if ($user['admin'] == 2 and $user_id != 5672) {
				$sql = "SELECT * FROM `goods` WHERE `name` LIKE '%{$goods_search_name}%' AND `user_id`='{$user_id}' ORDER BY `created` DESC LIMIT $start, $num";
			}

		} elseif (!empty($_SESSION['sql'])) {
			$sql = $_SESSION['sql'] . " LIMIT $start, $num";
			echo $sql;
		}

		$query = mysqli_query($db, $sql) or die(mysqli_error());

		if (mysqli_num_rows($query) > 0) {

			while ($goods = mysqli_fetch_assoc($query)) {

				$goods['name'] = str_replace("\n", "\\n", $goods['name']);

				$goods['photo'] = json_decode($goods['photo'], true);
				$goods['name'] = json_decode($goods['name'], true);
				$goods['parameters'] = json_decode($goods['parameters'], true);
				if (isset($goods['description']))
					$goods['description'] = json_decode($goods['description'], true);
				$goods['video'] = json_decode($goods['video'], true);
				$goods['export'] = json_decode($goods['export'], true);

				switch ($goods['currency']) {
					case 1:
						$goods['currency'] = ' грн';
						break;
					case 2:
						$goods['currency'] = ' $';
						break;
					case 3:
						$goods['currency'] = ' €';
						break;
				}

				//$goods['price_compare'] - 1 грн
				$goods_margin = $goods['price_agent'] > 0 ? $goods['price_agent'] : $goods['price_purchase'];
				$goods_margin = number_format(($goods['price_compare'] - $goods_margin), 2, '.', '') . ' грн';

				$goods['price_agent'] = $goods['price_agent'].$goods['currency'];
				$goods['price_purchase'] = $goods['price_purchase'].$goods['currency'];
				$goods['price_sale'] = $goods['price_sale'].$goods['currency'];
				$goods['price_compare'] = intval($goods['price_compare']);

				$goods_user_id = $goods['user_id'];

				$sql_provider = "SELECT * FROM `users` WHERE `id`='{$goods_user_id}'";
				$query_provider = mysqli_query($db, $sql_provider) or die(mysqli_error($db));
				$user_provider = mysqli_fetch_assoc($query_provider);

				$user_provider_partner_id = $user_provider['partner_id'];

				$sql_mentor = "SELECT * FROM `users` WHERE `id`='{$user_provider_partner_id}'";
				$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
				$user_mentor = mysqli_fetch_assoc($query_mentor);

				if ($user_mentor['agent'] == 1 and $goods['price_agent'] > 0) {
					if ($user['admin'] == 2 and $user_id != 5672)
						$goods['price_purchase'] = $goods['price_agent'];
				}

				$sql_goods_visits = "SELECT `id` FROM `goods_visits` WHERE `goods_id`='{$goods['id']}'";
				$query_goods_visits = mysqli_query($db, $sql_goods_visits) or die(mysqli_error($db));
				$count_goods_visits = mysqli_num_rows($query_goods_visits);

				$sql_goods_homework = "SELECT `id` FROM `school_homework` WHERE `goods_id`='{$goods['id']}' AND `status`=1";
				$query_goods_homework = mysqli_query($db, $sql_goods_homework) or die(mysqli_error($db));
				$count_goods_homework = mysqli_num_rows($query_goods_homework);

				$goods_category_name = $goods['category'];
				$sql_catalog_category = "SELECT `name_ru` FROM `catalog` WHERE `linkname`='{$goods_category_name}'";
				$query_catalog_category = mysqli_query($db, $sql_catalog_category) or die(mysqli_error($db));
				$catalog_category = mysqli_fetch_assoc($query_catalog_category);

?>

			<tr id="code<?=$goods['id']?>" <?if(isset($_GET['code_edit']) and $_GET['code_edit']==$goods['id']) echo 'class="table-success"';?>>
				<td>
					<input type="checkbox" class="goods_check" value="<?=$goods['id']?>" data-toggle="tooltip" title="Отметить" style="width: 25px; height: 25px;">
				</td>
				<th><?=$goods['id']?></th>
				<td style="max-width: 50px;">
					
<?

					$count_goods_photo = count($goods['photo']);

					for ($i=0; $i < ($count_goods_photo <= 2 ? $count_goods_photo : 2); $i++) {

						if (!file_exists('../data/images/goods/'.$goods['photo']['img'.$i])) {
							$goods['photo']['img'.$i] = 'no_image.png';
						}

						list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img'.$i]);

						if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
						else $goods_photo_size = 'max-height';

						echo '<div class="goods-list-img float-left '.$goods_photo_size.'"><img src="/data/images/goods/'.$goods['photo']['img'.$i].'" onclick="bigImg(\'/data/images/goods/'.$goods['photo']['img'.$i].'\')"></div>';
					}

					if ($count_goods_photo > 2) echo '<a data-toggle="collapse" href="#collapseGoods'.$goods['id'].'" role="button" aria-expanded="false" aria-controls="collapseGoods'.$goods['id'].'" class="collapsed btn btn-outline-dark mt-1">+ '.($count_goods_photo-2).'</a><div class="collapse" id="collapseGoods'.$goods['id'].'">';

					for ($i=2; $i < $count_goods_photo; $i++) {

						list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img'.$i]);

						if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
						else $goods_photo_size = 'max-height';

						echo '<div class="goods-list-img float-left '.$goods_photo_size.'"><img src="/data/images/goods/'.$goods['photo']['img'.$i].'" onclick="bigImg(\'/data/images/goods/'.$goods['photo']['img'.$i].'\')"></div>';
					}

					if ($count_goods_photo > 2) echo '</div>';

?>
					
				</td>
				<td class="align-middle">
					<button class="btn btn-link btn-clipboard p-0 border-0 mt-2" data-clipboard-text="<?=$goods['vendor_code']?>" onclick="copyLink(this)"><?=$goods['vendor_code']?></button>
				</td>
				<td class="align-middle" style="min-width: 400px;">
					<form method="POST">
						<input type="hidden" name="edit_name_goods_id" value="<?=$goods['id']?>">
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text pl-1 pr-1">ua</span>
							</div>
							<textarea name="name_uk" class="form-control" rows="2" placeholder="Название товара" required><?=$goods['name']['uk']?></textarea>
						</div>
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text pr-1" style="width: 27.58px;padding-left: 6px;">ru</span>
							</div>
							<textarea name="name_ru" class="form-control" rows="2" placeholder="Название товара" required><?=$goods['name']['ru']?></textarea>
						</div>
						<button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Вы действительно хотите изменить название этого товара?')"><i class="fa fa-save" aria-hidden="true"></i> Сохранить</button>
						<?if ($goods['user_id'] == 6766):?>
						<span><?=(isset($goods['parameters']['uk']['Назва латинською']) ? $goods['parameters']['uk']['Назва латинською'] : '')?></span>
						<?endif;?>
						<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" target="_blank" class="float-right"><i class="material-icons">open_in_new</i></a>
					</form>
				</td>
				<td class="align-middle" style="max-width: 200px;">
					<?=$catalog_category['name_ru']?>
				</td>
				<td class="text-center align-middle">
					<form method="POST" class="float-left">
						<input type="hidden" name="edit_availability_goods_id" value="<?=$goods['id']?>">
						<input type="number" name="availability" min="0" class="form-control mb-1" placeholder="<?=$goods['availability']?>" value="<?=$goods['availability']?>" style="width: 70px;" required>
						<button type="submit" class="btn btn-success btn-sm btn-block" onclick="return confirm('Вы действительно хотите изменить количество этого товара?')">
							<i class="fa fa-save" aria-hidden="true"></i>
						</button>
					</form>
				</td>
				<?if ($user['admin'] == 1):?>
				<td class="align-middle"><?=$goods['price_agent']?><br><s><?=$goods['price_agent_old']?> грн</s></td>
				<?endif;?>
				<td class="align-middle"><?=$goods['price_purchase']?></td>
				<td class="align-middle"><?=$goods['price_sale']?><br><s><?=$goods['price_sale_old']?> грн</s></td>
				<td class="align-middle <?=($goods['price_compare'] > 0 ? 'bg-dark text-warning' : 'text-danger')?> text-center font-weight-bold">
					<?if ($goods['price_compare'] == 0):?>
					<form method="POST">
						<input type="hidden" name="edit_price_compare_goods_id" value="<?=$goods['id']?>">
						<input type="number" name="price_compare" min="0" class="form-control mb-1" placeholder="<?=$goods['price_compare']?>" value="<?=$goods['price_compare']?>" style="width: 100px;" required>
						<button type="submit" class="btn btn-success btn-sm btn-block" onclick="return confirm('Вы действительно хотите изменить Конкурентную цену этого товара?')">
							<i class="fa fa-save" aria-hidden="true"></i>
						</button>
					</form>
					<?else:?>
					<?=$goods['price_compare']?> грн
					<?endif;?>
				</td>
				<td class="align-middle text-center font-weight-bold <?=(strpos($goods_margin, '-') !== false ? 'text-danger' : 'text-success')?>"><?=$goods_margin?></td>
				<td class="text-center align-middle<?=(($goods['moderation'] == 0)?' bg-warning':'')?>"><?if ($goods['status'] == 1) echo '<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Включен">visibility</i>'; else echo '<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Выключен">visibility_off</i>';?></td>
				<td class="text-center align-middle"><?=$count_goods_visits?></td>
				<td class="text-center align-middle"><?=$count_goods_homework?></td>
				<td class="align-middle text-center" style="max-width: 77px;">
					<form method="POST" class="d-inline-block">
						<input type="hidden" name="copy_goods_id" value="<?=$goods['id']?>">
						<button type="submit" class="btn btn-dark mb-2" data-toggle="tooltip" data-placement="top" title="Копировать" onclick="return confirm('Вы действительно хотите копировать этот товар?')">
							<i class="material-icons float-left">file_copy</i>
						</button>
					</form>
					<button class="btn btn-warning<?//if($user_id == $goods['user_id']) echo 'warning'; else echo 'secondary';?> mb-2 btn-edit" data-toggle="modal" data-target="#editGoods" data-placement="top" title="Редактировать" onclick="editGoods(<?=$goods['id']?>)">
						<i class="material-icons float-left">edit</i>
					</button>
					<form method="POST" class="d-inline-block">
						<input type="hidden" name="delete_goods_id" value="<?=$goods['id']?>">
						<button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="return confirm('Вы действительно хотите удалить этот товар?')">
							<i class="material-icons float-left">delete_forever</i>
						</button>
					</form>
				</td>
			</tr>

<?

			}

		} else {

?>

			<tr>
				<td class="text-center" colspan="12">Список товаров пуст</td>
			</tr>

<?

		}
		
?>

	</tbody>
</table>
</div>

<form method="POST" id="saveChangesGoods">
	<input type="hidden" name="goods_checked_act" value="change_category">
	<div class="hiddenSelectGoods d-none"></div>
	<div class="row modal-goods">
		<div class="col-sm-4">
			<div class="list-group mb-3">
				<div class="list-group-item list-group-item-category">
					<p class="font-weight-bold"><span class="text-danger">*</span>Отмеченые товары (<span class="countCheckedGoods text-danger">0 шт.</span>) попадут в эту категорию:</p>
					<div class="form-group">
						<select onchange="categorySelectAddGoods(this)" class="form-control" required>
							<option value="none" selected disabled>Выбирете категорию</option>
							<option value="all">Все категории</option>
<?

							$sql = "SELECT * FROM `catalog` WHERE `level_id`=1 ORDER BY `name_ru` ASC";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

							while ($catalog = mysqli_fetch_assoc($query)) {

								if ($catalog['id'] != 2274 and $catalog['id'] != 573 and $user['admin'] == 2) {

									$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
									$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
									$count_subcategories = mysqli_num_rows($query_subcategories);
											
									if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';
									else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].'</option>';

								} elseif ($user['admin'] == 1) {

									$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
									$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
									$count_subcategories = mysqli_num_rows($query_subcategories);
											
									if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';
									else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].'</option>';

								}

							}

?>
						</select>
					</div>
					<small class="text-danger">*Выбирайте категорию до тех пор, пока она не перестанет выбираться.</small>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button id="btnSaveChangesGoods" class="btn btn-success mt-3" type="submit">Сохранить</button>
	</div>
</form>

<form method="POST">
	<input type="hidden" name="goods_checked_act" value="delete">
	<div class="hiddenSelectGoods d-none"></div>
	<div class="row modal-goods">
		<div class="col-sm-4">
			<div class="list-group mb-3">
				<div class="list-group-item">
					<p class="font-weight-bold"><span class="text-danger">*</span>Отмеченые товары (<span class="countCheckedGoods text-danger">0 шт.</span>) будут удалены:</p>
					<div class="form-group">
						<button class="btn btn-danger mt-3" type="submit" onclick="return confirm('Вы действительно хотите удалить эти товары?')">
							<i class="material-icons float-left">delete_forever</i> Удалить выбранные товары 
							<i class="material-icons float-right">delete_forever</i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage; 

?>
		
	</ul>
</nav>

<div class="modal fade" id="addGoods">
	<div class="modal-dialog modal-lg modal-goods" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить товар</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="add_goods_id" value="<?=rand(1,1000000)?>">
					<div class="list-group mb-3">
						<div class="list-group-item">
							<div class="row">
								<div class="col-sm-6">
									<p class="font-weight-bold">Идентификатор товара продавца:</p>
									<div class="form-group">
										<input type="text" name="vendor_id" maxlength="255" class="form-control" placeholder="Введите значение">
									</div>
								</div>
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Артикул / Код товара продавца:</p>
									<div class="form-group">
										<input type="text" name="vendor_code" maxlength="255" class="form-control alert-warning" placeholder="Введите значение" required>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item list-group-item-category">
							<p class="font-weight-bold"><span class="text-danger">*</span> Категория:</p>
							<div class="form-group">
								<select onchange="categorySelectAddGoods(this)" class="form-control" required>
									<option value="none" selected disabled>Выбирете категорию</option>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`=1 ORDER BY `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									while ($catalog = mysqli_fetch_assoc($query)) {

										$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
										$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
										$count_subcategories = mysqli_num_rows($query_subcategories);
										
										if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';
										else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].'</option>';

									}

?>
								</select>
							</div>
							<small class="text-danger">*Выбирайте категорию до тех пор, пока она не перестанет выбираться.</small>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Фото:</p>
							<div class="form-group row">
								<div class="col-sm-3">
									<div class="card">
										<div class="card-body p-1">
											<input type="hidden" name="photo[]">
											<label class="goods-images mb-0 float-left" data-cnt="0">
												<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)" required>
												<p class="text-center text-muted d-flex justify-content-center mb-0 h-100">
													<i class="material-icons align-self-center">add_a_photo</i>
												</p>
											</label>
										</div>
										<div class="card-footer text-center pt-0 pb-0">
											<small>Основное</small>
										</div>
									</div>
								</div>
								
						<?

							for ($i = 0; $i < 7; $i++) {

						?>

								<div class="col-sm-3 mb-2">
									<div class="card">
										<div class="card-body p-1">
											<input type="hidden" name="photo[]" value="<?=(($i==0)?'no_image.png':'')?>">
											<label class="goods-images mb-0 float-left" data-cnt="<?=$i?>">
												<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)">
												<p class="text-center text-muted d-flex justify-content-center mb-0 h-100">
													<i class="material-icons align-self-center">add_a_photo</i>
												</p>
											</label>
										</div>
										<div class="card-footer text-center pt-0 pb-0">
											<small>Фото <?=($i+2)?></small>
										</div>
									</div>
								</div>

						<?

							}

						?>

							</div>
							<p class="text-center mb-0">
								<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputsPhotoAddGoods(this)">
									<i class="material-icons material-icons-plus-input float-left">add</i>
								</button>
							</p>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Название товара:</p>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">UA</span>
									</div>
									<input type="text" name="name_uk" class="form-control" placeholder="Название товара" required>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">RU</span>
									</div>
									<input type="text" name="name_ru" class="form-control" placeholder="Название товара" required>
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Параметры описания:</p>
							<div class="parameters">
								<div class="list-group"></div>
							</div>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Описание UA:</p>
							<textarea name="description_uk" rows="7" class="form-control goods-description summernote" placeholder="Описание..."></textarea>
							<br>
							<p class="font-weight-bold">Описание RU:</p>
							<textarea name="description_ru" rows="7" class="form-control goods-description summernote" placeholder="Описание..."></textarea>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Ключи UA:</p>
							<textarea name="keys_uk" rows="7" class="form-control goods-keys" placeholder="Ключи вводить через запятую..."></textarea>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Ключи RU:</p>
							<textarea name="keys_ru" rows="7" class="form-control goods-keys" placeholder="Ключи вводить через запятую..."></textarea>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Видео YouTube, только id ( https://www.youtube.com/watch?v=MPxPh7UrNJ8 ):</p>
							<div class="form-group row">
								<div class="col-sm-11">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">https://www.youtube.com/watch?v=</span>
										</div>
										<input type="text" name="video[]" class="form-control" placeholder="MPxPh7UrNJ8">
									</div>
								</div>
								<div class="col-sm-1">
									<button type="button" class="btn btn-danger btn-sm btn-block pl-1 pr-0" onclick="deleteInputsVideoAddGoods(this)">
										<i class="material-icons float-sm-left">delete_forever</i>
									</button>
								</div>
							</div>
							<p class="text-center mb-0">
								<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputsVideoAddGoods(this)">
									<i class="material-icons material-icons-plus-input float-left">add</i>
								</button>
							</p>
						</div>
						
						<div class="list-group-item">
							<p class="font-weight-bold">Группа:</p>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="goodsGroupTopAdd" name="group_top" value="1">
									<label class="custom-control-label" for="goodsGroupTopAdd">Хит продаж</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="goodsGroupNewAdd" name="group_new" value="1">
									<label class="custom-control-label" for="goodsGroupNewAdd">Новинка</label>
								</div>
							</div>
						</div>
						
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Количество в наличии:</p>
							<div class="form-group">
								<input type="number" name="availability" min="0" max="10000" step="1" class="form-control alert-primary" placeholder="0" required>
							</div>
						</div>

						<div class="list-group-item">
							<p class="kurses-pb-goods font-italic text-center" style="display: none;">
								<span class="border border-dark p-1">Курсы ПриватБанк: (<b>USD</b>: <?=$api_exchange_rate_usd['buy']?> / <span class="kurs-usd-sale-pb-goods"><?=$api_exchange_rate_usd['sale']?></span> | <b>EUR</b>: <?=$api_exchange_rate_eur['buy']?> / <span class="kurs-eur-sale-pb-goods"><?=$api_exchange_rate_eur['sale']?></span>) на <b><?=date('d.m.Y H:i', strtotime($api_exchange_rate_usd['updated'].' +2 hours'))?></b></span>
							</p>
							<p class="font-weight-bold"><span class="text-danger">*</span> Цена: </p>
							<div class="form-group">
								<p>Выберите <b>валюту цены</b>:</p>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioUAH" name="currency" class="custom-control-input" value="1" checked  onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioUAH">UAH</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioUSD" name="currency" class="custom-control-input" value="2" onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioUSD">USD</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioEUR" name="currency" class="custom-control-input" value="3" onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioEUR">EUR</label>
								</div>
							</div>
							<div class="form-group top-kurs-currency" style="display: none;">
								<p>Укажите пороговый курс выбранной валюты, если он нужен, ниже которого цена товара в гривнях не опустится при колебании курса валют в банках:</p>
								<div class="input-group mb-1">
									<div class="input-group-prepend">
										<span class="input-group-text">USD</span>
									</div>
									<input type="number" name="currency_top_kurs" step="0.01" class="form-control" placeholder="28.5">
								</div>
							</div>
							<div class="form-group input-prices-goods">
								<p>Укажите <b>цену закупки</b> для платформы:</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text">грн</span>
											</div>
											<input type="number" name="price_purchase" step="0.01" class="form-control alert-success" placeholder="Введите цифру" required onkeyup="convertToUAH(this)">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card bg-light border border-success">
											<div class="card-body text-center p-1">
												<h4 class="mb-0">0.00 грн</h4>
											</div>
										</div>
										<small>Сконвертированная цена в гривни для наглядности</small>
									</div>
								</div>
							</div>
							<div class="form-group input-prices-goods">
								<p>Укажите <b>рекомендованную цену</b> для продажи:</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text">грн</span>
											</div>
											<input type="number" name="price_sale" step="0.01" class="form-control alert-danger" placeholder="Введите цифру" required onkeyup="convertToUAH(this)">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card bg-light border border-danger">
											<div class="card-body text-center p-1">
												<h4 class="mb-0">0.00 грн</h4>
											</div>
										</div>
										<small>Сконвертированная цена в гривни для наглядности</small>
									</div>
								</div>
							</div>
							<div class="form-group">
								<p class="font-weight-bold">Цена конкурента:</p>
								<div class="input-group mb-1">
									<div class="input-group-prepend">
										<span class="input-group-text">грн</span>
									</div>
									<input type="number" name="price_compare" min="0" step="0.01" class="form-control" placeholder="Введите цифру">
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<div class="row">
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Товар промодерировано:</p>
									<div class="form-group">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioModerationAddYes" name="moderation" class="custom-control-input" value="1" checked>
											<label class="custom-control-label" for="customRadioModerationAddYes">Да</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioModerationAddNo" name="moderation" class="custom-control-input" value="0">
											<label class="custom-control-label" for="customRadioModerationAddNo">Нет</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Статус:</p>
									<div class="form-group">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioStatusOpen" name="status" class="custom-control-input" value="1" checked>
											<label class="custom-control-label" for="customRadioStatusOpen">Включен</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioStatusClose" name="status" class="custom-control-input" value="0">
											<label class="custom-control-label" for="customRadioStatusClose">Выключен</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success pl-5 pr-5">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editGoods">
	<div class="modal-dialog modal-lg modal-goods" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Редактировать товар</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

<style type="text/css">
#bigImg {
	padding-right: 0!important;
	overflow: hidden!important;
}
#bigImg .modal-dialog {
	max-width: 100%;
	margin-top: 0;
}

#bigImg .modal-header {
	padding: 0 1rem;
}

#bigImg .modal-body {
	width: 100%;
	height: 100%;
	background-color: #F6F8FD;
	overflow: hidden;
	text-align: center;
	padding: 0;
}

#bigImg .modal-body.max-width {
	display: -webkit-box;
	display: -moz-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	/* justify-content: center; */
	align-items: center;
}

#bigImg .modal-body.max-width img {
	display: block;
	width: 100%;
	height: auto;
	margin: auto;
}

#bigImg .modal-body.max-height img {
	width: auto;
	height: 100%;
}
</style>
<div class="modal" id="bigImg">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="filterGoods">
	<div class="modal-dialog modal-lg modal-goods" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Фильтровать товары по категории</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<input type="hidden" name="filter_goods_id" value="<?=rand(1,1000000)?>">
					<div class="list-group mb-3">
						<div class="list-group-item list-group-item-category">
							<p class="font-weight-bold"><span class="text-danger">*</span> Категория:</p>
							<div class="form-group">

<?if (empty($_SESSION['filter_goods_admin'])):?>

								<select onchange="categorySelectAddGoods(this)" class="form-control" required>
									<option value="none" selected disabled>Выбирете категорию</option>
									<option value="without">Без категории</option>
									<option value="all">Все категории</option>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`=1 ORDER BY IF(`id` = 2274, 1, 2) ASC, `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error());

									while ($catalog = mysqli_fetch_assoc($query)) {

										if ($catalog['id'] != 2274 and $catalog['id'] != 573 and $user['admin'] == 2) {

											$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
											$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
											$count_subcategories = mysqli_num_rows($query_subcategories);
											
											if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										} elseif ($user['admin'] == 1) {

											$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
											$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
											$count_subcategories = mysqli_num_rows($query_subcategories);
											
											if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										}

									}

?>
								</select>

<?else:?>

	<?

						$category_filter = $_SESSION['filter_goods_admin'];

	?>

	<?if ($category_filter != 'without'):?>

								<?

						$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$category_filter}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$catalog = mysqli_fetch_assoc($query);

						$catalog_level_id = $catalog['level_id'];

						function displayCategoriesGoods($db, $level_id) {
						
							$sql = "SELECT * FROM `catalog` WHERE `id`='{$level_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));
							$catalog = mysqli_fetch_assoc($query);

							if (mysqli_num_rows($query) > 0) {

								$_SESSION['category_tmp'][] = $catalog['id'];

								displayCategoriesGoods($db, $catalog['level_id']);

							}

						}

						displayCategoriesGoods($db, $catalog_level_id);

						$category = $_SESSION['category_tmp'];
						if (isset($_SESSION['category_tmp'])) unset($_SESSION['category_tmp']);
							$n_ = 0;
							for ($i = count($category) - 1; $i > 0; $i--) {
								$n_++;
								$category_tmp = $category[$i];

?>

								<select onchange="categorySelectAddGoods(this)" class="form-control mt-2" required>
									<option value="none" selected disabled>Выбирете категорию</option>
									<?if($n_==1):?>
									<option value="without">Без категории</option>
									<option value="all">Все категории</option>
									<?endif;?>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$category_tmp}' ORDER BY `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									while ($catalog = mysqli_fetch_assoc($query)) {

										$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
										$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
										$count_subcategories = mysqli_num_rows($query_subcategories);
										
										if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
										else $show_option = '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										if ($catalog['id'] == $category[$i-1]) {

											if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'" selected>'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else $show_option = '<option value="'.$catalog['linkname'].'" selected>'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										}

										echo $show_option;

									}

?>
								</select>

<?

							}

?>

								<select onchange="categorySelectAddGoods(this)" class="form-control mt-2" name="category" required>
									<option value="none" selected disabled>Выбирете категорию</option>
									<?if (count($category) == 1):?>
									<option value="without">Без категории</option>
									<option value="all">Все категории</option>
									<?endif;?>

<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_level_id}' ORDER BY `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									while ($catalog = mysqli_fetch_assoc($query)) {

										$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
										$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
										$count_subcategories = mysqli_num_rows($query_subcategories);
										
										if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
										else $show_option = '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										if ($catalog['linkname'] == $goods['category']) {

											if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'" selected>'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else $show_option = '<option value="'.$catalog['linkname'].'" selected>'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										}

										echo $show_option;

									}

?>
								</select>

	<?else:?>

								<select onchange="categorySelectAddGoods(this)" class="form-control" required>
									<option value="none" selected disabled>Выбирете категорию</option>
									<option value="without" <?if($category_filter=='without') echo 'selected';?>>Без категории</option>
									<option value="all">Все категории</option>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`=1 ORDER BY IF(`id` = 2274, 1, 2) ASC, `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error());

									while ($catalog = mysqli_fetch_assoc($query)) {

										if ($catalog['id'] != 2274 and $catalog['id'] != 573 and $user['admin'] == 2) {

											$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
											$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
											$count_subcategories = mysqli_num_rows($query_subcategories);
											
											if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										} elseif ($user['admin'] == 1) {

											$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
											$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
											$count_subcategories = mysqli_num_rows($query_subcategories);
											
											if ($count_subcategories > 0) echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';
											else echo '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].' - ['.$catalog['count_goods_admin'].']</option>';

										}

									}

?>
								</select>

	<?endif;?>

<?endif;?>

							</div>
							<small class="text-danger">*Выбирайте категорию до тех пор, пока она не перестанет выбираться.</small>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary pl-5 pr-5">Фильтровать</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="googleLinks">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ссылки на прайсы поставщиков</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group">
					<li class="list-group-item">
						<h5 class="mb-0">Гречиские товары 
							<a href="https://docs.google.com/spreadsheets/d/1SO8JtAwuQcWWsIdc7zgYdhWlatuYhFFVJT9VCJU7LeY/edit#gid=220238637" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons float-left mr-3">open_in_new</i>
							</a>
						</h5>
					</li>
					<li class="list-group-item">
						<h5 class="mb-0">Кухонная утварь 
							<a href="https://drive.google.com/open?id=19fNLHlNRV3M3ymhMhdzb8ksKGxspJ2NU" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons float-left mr-3">open_in_new</i>
							</a>
						</h5>
					</li>
					<li class="list-group-item">
						<h5 class="mb-0">Спортивные костюмы 
							<a href="https://docs.google.com/spreadsheets/d/1SO8JtAwuQcWWsIdc7zgYdhWlatuYhFFVJT9VCJU7LeY/edit#gid=220238637" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons float-left mr-3">open_in_new</i>
							</a>
						</h5>
					</li>
					<li class="list-group-item">
						<h5 class="mb-0">Автоелектроника 
							<a href="https://drive.google.com/open?id=1ioyDDSd3oFO3k4fD6remWVi5INlopNOb" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons mr-3 float-left">open_in_new</i>
							</a>
						</h5>
					</li>
					<li class="list-group-item">
						<h5 class="mb-0">Ремонт турбин 
							<a href="https://docs.google.com/spreadsheets/d/1aHFmvhfkM-mNunswtjos0ZuFgpcP1sd3BBQQxilrYTA/edit#gid=2027080553" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons mr-3 float-left">open_in_new</i>
							</a>
						</h5>
					</li>
					<li class="list-group-item">
						<h5 class="mb-0">Инвест клуб спортывные костюмы 
							<a href="https://docs.google.com/spreadsheets/d/15xlvo_0Kxy-EZcZRh_8MxA24V4npr9s2_BkTXq-3rOU/edit#gid=1583966257" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons mr-3 float-left">open_in_new</i>
							</a>
						</h5>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?

$sql = "SELECT * FROM `info_providers` WHERE `user_id`='{$user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$info_providers = mysqli_fetch_assoc($query);

?>

<div class="modal fade" id="infoProviders">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить Расписание/Прочую информацию</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="form-group">
						<textarea name="description_ip" rows="10" class="form-control goods-description" placeholder="Описание..."><?=$info_providers['description']?></textarea>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-success">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>