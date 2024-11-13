<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?=$alert_message?>

<?

$search_user_submit = false;
$search_user_value = '';

if (isset($_SESSION['search_user']) and !empty($_SESSION['search_user'])) {
	
	$search_user_submit = true;

	$search_user_pages = $_SESSION['search_user']['pages'];
	$search_user_results = $_SESSION['search_user']['results'];
	$search_user_value = $_SESSION['search_user']['value'];

} else if (isset($_SESSION['filter_user']) and !empty($_SESSION['filter_user'])) {
	
	$search_user_submit = true;

	$search_user_pages = $_SESSION['filter_user']['pages'];
	$search_user_results = $_SESSION['filter_user']['results'];
	$filter_user_value = $_SESSION['filter_user']['value'];

}

// Переменная хранит число сообщений выводимых на станице
$num = 30;
// Определяем общее число сообщений в базе данных
$sql = "SELECT COUNT(1) as count FROM `users`";
if ($search_user_submit) $sql = $search_user_pages;
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$posts = mysqli_fetch_assoc($query);
// Находим общее число страниц
$total = intval(($posts['count'] - 1) / $num) + 1;
// Определяем начало сообщений для текущей страницы
$page = intval($_GET['page']);
// Если значение $page меньше единицы или отрицательно  
// переходим на первую страницу  
// А если слишком большое, то переходим на последнюю  
if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  
// Вычисляем начиная к какого номера  
// следует выводить сообщения  
$start = $page * $num - $num;

$sql = "SELECT * FROM `users` ORDER BY `created` ASC LIMIT $start, $num";
if ($search_user_submit) $sql = $search_user_results."$start, $num";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

?>

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-7 mb-3">
				<form method="POST">
					<div class="row">
						<div class="col-sm-8 mb-3">
							<?

							if (!isset($_SESSION['search_user']) or empty($_SESSION['search_user'])) $search_user_value = '';
							else $search_user_value = str_replace("\'", "'", $search_user_value);

							?>
							<input type="text" name="search" class="form-control form-control-lg" placeholder="Введите значиние для поиска..." value="<?=$search_user_value?>">
						</div>
						<div class="col-sm-4 mb-3">
							<button type="submit" class="btn btn-primary btn-lg">Поиск</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-5 mb-3">
				<form method="POST">
					<div class="row">
						<div class="col-sm-6 mb-3">
							<select name="filter" class="form-control form-control-lg">
								<?

								$filter_user_selected_none = ' selected';
								$filter_user_selected_admins = '';
								$filter_user_selected_providers = '';
								$filter_user_selected_agents = '';
								$filter_user_selected_newbie = '';
								$filter_user_selected_dropshipper = '';
								$filter_user_selected_manager = '';
								$filter_user_selected_supervisor = '';
								$filter_user_selected_director = '';
								$filter_user_selected_nonementor = '';
								$filter_user_selected_isorders = '';
								$filter_user_selected_isorders7 = '';
								$filter_user_selected_partnersnoadmin = '';
								$filter_user_selected_gtm = '';

								if (isset($_SESSION['filter_user']) and !empty($_SESSION['filter_user'])) {

									switch ($filter_user_value) {
										case 'admins':
											$filter_user_selected_admins = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'providers':
											$filter_user_selected_providers = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'agents':
											$filter_user_selected_agents = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'newbie':
											$filter_user_selected_newbie = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'dropshipper':
											$filter_user_selected_dropshipper = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'manager':
											$filter_user_selected_manager = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'supervisor':
											$filter_user_selected_supervisor = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'director':
											$filter_user_selected_director = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'nonementor':
											$filter_user_selected_nonementor = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'isorders':
											$filter_user_selected_isorders = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'isorders7':
											$filter_user_selected_isorders7 = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'partnersnoadmin':
											$filter_user_selected_partnersnoadmin = ' selected';
											$filter_user_selected_none = '';
											break;
										case 'gtm':
											$filter_user_selected_gtm = ' selected';
											$filter_user_selected_none = '';
											break;
										default:
											$filter_user_selected_none = ' selected';
											break;
									}

								}

								?>
								<option value="none"<?=$filter_user_selected_none?> disabled>Фильтр</option>
								<option value="admins"<?=$filter_user_selected_admins?>>Админы</option>
								<option value="providers"<?=$filter_user_selected_providers?>>Поставщики</option>
								<option value="agents"<?=$filter_user_selected_agents?>>Агенты</option>
								<option value="newbie"<?=$filter_user_selected_newbie?>>Новички</option>
								<option value="dropshipper"<?=$filter_user_selected_dropshipper?>>Дропшипперы</option>
								<option value="manager"<?=$filter_user_selected_manager?>>Наставники</option>
								<option value="supervisor"<?=$filter_user_selected_supervisor?>>Супервайзеры</option>
								<option value="director"<?=$filter_user_selected_director?>>Директоры</option>
								<option value="nonementor"<?=$filter_user_selected_nonementor?>>Без наставника</option>
								<option value="isorders"<?=$filter_user_selected_isorders?>>С заказами</option>
								<option value="isorders7"<?=$filter_user_selected_isorders7?>>С завершенными заказами</option>
								<option value="partnersnoadmin"<?=$filter_user_selected_partnersnoadmin?>>Партнеры под админом, но не админа</option>
								<option value="gtm"<?=$filter_user_selected_gtm?>>Партнеры с Google</option>
							</select>
						</div>
						<div class="col-sm-6 mb-3">
							<button type="submit" class="btn btn-primary btn-lg">Фильтровать</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?if ($search_user_submit):?>
		<div class="col-sm-4 mb-3 text-right">
			<form method="POST">
				<input type="hidden" name="clear_search" value="1">
				<button type="submit" class="btn btn-warning btn-lg">Очистить результат поиска/фильтра</button>
			</form>
		</div>
	<?endif;?>
</div>

<p class="text-right">Найдено всего результатов: <b><?=$posts['count']?></b></p>

<div class="table-responsive">
	<table class="table table-sm table-hover">
		<thead class="thead-light">
			<tr>
				<th>ID</th>
				<th>Наставник</th>
				<th>Имя</th>
				<th>Фамилия</th>
				<th>E-mail</th>
				<th>Телефон</th>
				<th>Статус</th>
				<th>Админ</th>
				<th>Поставщик</th>
				<th>Агент</th>
				<th style="min-width:160px">Зарегистрирован</th>
			</tr>
		</thead>
		<tbody>
			<?

			while ($user_list = mysqli_fetch_assoc($query)) {

				$is_class = '';

				$user_list_partner_id = $user_list['partner_id'];

				$sql_nastavnyk = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$user_list_partner_id}'";
				$query_nastavnyk = mysqli_query($db, $sql_nastavnyk) or die(mysqli_error());
				$user_nastavnyk = mysqli_fetch_assoc($query_nastavnyk);
				if (mysqli_num_rows($query_nastavnyk) > 0) $user_nastavnyk_data = $user_nastavnyk['name'] . ' ' . $user_nastavnyk['surname'];
				else $user_nastavnyk_data = '-';
				
				if (empty($user_list['name'])) $user_list['name'] = '-';
				
				if (empty($user_list['surname'])) $user_list['surname'] = '-';

				if (empty($user_list['phone'])) $user_list['phone'] = '-';

				if ($user_list['activated'] == 0) $user_list['mail'] = '<span class="text-danger">'.$user_list['mail'].'</span>';

				if ($user_list['admin'] == 1) {
					$user_list_admin = 'Админ';
					$user_list_provider = '-';
				} elseif ($user_list['admin'] == 2) {
					$user_list_admin = '-';
					$user_list_provider = 'Поставщик';
				} else {
					$user_list_admin = '-';
					$user_list_provider = '-';
				}

				if ($user_list['agent'] == 1) $user_list_agent = 'Агент';
				else $user_list_agent = '-';

				if ($user_list_partner_id == 1) $is_class = ' class="table-primary"';

				if ($user_list['blocked'] == 1) $is_class = ' class="table-danger"';

				switch ($user_list['status']) {
					case 0:
						$user_list['status'] = 'Новичок';
						break;
					case 1:
						$user_list['status'] = 'Дропшиппер';
						break;
					case 2:
						$user_list['status'] = 'Наставник';
						break;
					case 3:
						$user_list['status'] = 'Супервайзер';
						break;
					case 4:
						$user_list['status'] = 'Директор';
						break;
					default:
						$user_list['status'] = 'не определен';
						break;
				}

				echo '
				<tr' . $is_class . ' onclick="dataUser(' . $user_list['id'] . ')" style="cursor:pointer">
					<td>' . $user_list['id'] . '</td>
					<td><small>' . $user_nastavnyk_data . '</small></td>
					<td>' . $user_list['name'] . '</td>
					<td>' . $user_list['surname'] . '</td>
					<td>' . $user_list['mail'] . '</td>
					<td>' . $user_list['phone'] . '</td>
					<td>' . $user_list['status'] . '</td>
					<td>' . $user_list_admin . '</td>
					<td>' . $user_list_provider . '</td>
					<td>' . $user_list_agent . '</td>
					<td>' . $user_list['created'] . '</td>
				</tr>';

			}

			?>
		</tbody>
	</table>
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

<div id="windowUserData" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Данные пользователя</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-hover">
					<thead class="thead-default">
						<tr>
							<th>Заголовок</th>
							<th>Содержимое</th>
						</tr>
					</thead>
					<tbody id="tableRows">
						
					</tbody>
				</table>
				<div id="loaderUser">
					<img src="/assets/images/ajax_loader_black.gif" width="30">
				</div>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>