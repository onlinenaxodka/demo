<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$transaction_type_arr = array(
	array(0, 'Пополнение баланса', 'attach_money', '#9e9e9e', '#84ad00'),
	array(1, 'Покупка товаров', 'shopping_cart', '#9e9e9e', '#d9534f'),
	array(2, 'Выплата денег', 'attach_money', '#9e9e9e', '#d9534f'),
	array(3, 'Зачисление прибыли с заказа', 'attach_money', '#9e9e9e', '#84ad00'),
	array(4, 'Вычитание прибыли за возврат заказа', 'attach_money', '#9e9e9e', '#d9534f'),
	array(5, 'Исправление технических неисправностей', 'attach_money', '#9e9e9e', '#d9534f'),
	array(6, 'Списание комиссии за отказ заказа', 'attach_money', '#9e9e9e', '#d9534f'),
	array(7, 'Месячная абонплата', 'attach_money', '#9e9e9e', '#d9534f'),
	array(8, 'Внесение инвестиции', 'attach_money', '#9e9e9e', '#d9534f'),
	array(9, 'Дивиденды по инвестициях', 'attach_money', '#9e9e9e', '#84ad00')
);

$filter_value_view = array();
$filter_value_view['type']=1000;
$filter_value_view['status']=1000;

if (!empty($_SESSION['transaction_filter'])) {

	$sql_part_transaction_filter_arr = array();

	foreach ($_SESSION['transaction_filter'] as $key_transaction_filter => $value_transaction_filter) {

			$filter_value_view[$key_transaction_filter] = $value_transaction_filter;

			switch ($key_transaction_filter) {
				case 'number':
					$sql_part_transaction_filter_arr[] = "`id`='{$value_transaction_filter}'";
					break;
				case 'date_from':
					$sql_part_transaction_filter_arr[] = "DATE(`created`)>='{$value_transaction_filter}'";
					break;
				case 'date_to':
					$sql_part_transaction_filter_arr[] = "DATE(`created`)<='{$value_transaction_filter}'";
					break;
				case 'user':
					$sql_part_transaction_filter_arr[] = "`user_id`='{$value_transaction_filter}'";
					break;
				case 'task':
					$sql_part_transaction_filter_arr[] = "`task_id`='{$value_transaction_filter}'";
					break;
				case 'action':
					$sql_part_transaction_filter_arr[] = "`action` LIKE '%{$value_transaction_filter}%'";
					break;
				case 'type':
					$sql_part_transaction_filter_arr[] = "`type`='{$value_transaction_filter}'";
					break;
				case 'status':
					$sql_part_transaction_filter_arr[] = "`status`='{$value_transaction_filter}'";
					break;
				default:
					$sql_part_transaction_filter_arr[] = "";
					break;
			}

	}

	if (!empty($sql_part_transaction_filter_arr)) $sql_part_transaction_filter = " WHERE ".implode(" AND ", $sql_part_transaction_filter_arr);
	else $sql_part_transaction_filter = "";

	$sql_part_transaction = $sql_part_transaction_filter;

}

$sql = "SELECT COUNT(1) as count FROM `transactions`".$sql_part_transaction;
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_transactions = mysqli_fetch_assoc($query);

/*// Переменная хранит число сообщений выводимых на станице
		$num = 100;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `transactions`";

		if (!empty($search)) $sql = "SELECT COUNT(1) as count FROM `transactions` WHERE `action` LIKE '%{$search}%'";
		if (!empty($date)) $sql = "SELECT COUNT(1) as count FROM `transactions` WHERE DATE(`created`) = '{$date}'";

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
		$start = $page * $num - $num;*/

?>

<!-- <div class="row">
	<div class="col-sm-4">
		<form method="GET">
			<div class="row">
				<div class="col-sm-9 mb-3">
					<input type="text" name="search" placeholder="Поиск по описанию операции..." class="form-control">
				</div>
				<div class="col-sm-3 mb-3">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-2">
		<form method="GET">
			<div class="row">
				<div class="col-sm-9 mb-3">
					<input type="date" name="date" placeholder="Поиск по дате операции..." class="form-control">
				</div>
				<div class="col-sm-3 mb-3">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-6">
		<p class="text-right">Всего операций: <b><?=$posts['count']?></b></p>
	</div>
</div> -->
<div class="row">
	<div class="col-sm-9">
<form method="POST">
	<div class="row">
		<div class="col-lg-3">
				<div class="form-group">
					<label for="filter_number" class="font-weight-bold">Номер операции</label>
					<input type="number" name="filter_number" id="filter_number" class="form-control" placeholder="Введите номер операции..." value="<?=$filter_value_view['number']?>">
				</div>
				<div class="form-group">
					<label for="filter_status" class="font-weight-bold">Статус операции</label>
					<select name="filter_status" id="filter_status" class="form-control">
						<option value="none" selected>Все статусы операций</option>
						<option value="0"<?=(($filter_value_view['status']==0)?' selected':'')?>>Отменена</option>
						<option value="1"<?=(($filter_value_view['status']==1)?' selected':'')?>>Ожидает подтверждения</option>
						<option value="2"<?=(($filter_value_view['status']==2)?' selected':'')?>>Выполнена</option>
					</select>
				</div>
		</div>
		<div class="col-lg-4">
				<div class="form-group">
					<label for="filter_action" class="font-weight-bold">Описание операции</label>
					<input type="search" name="filter_action" id="filter_action" class="form-control" placeholder="Введите часть или целое описание..." value="<?=$filter_value_view['action']?>">
				</div>
				<div class="form-group">
					<label for="filter_date_from" class="font-weight-bold">Дата заказа (дд.мм.рррр)</label>
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">С</span>
								</div>
								<input type="date" name="filter_date_from" id="filter_date_from" class="form-control" placeholder="Выберите дату" value="<?=$filter_value_view['date_from']?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">До</span>
								</div>
								<input type="date" name="filter_date_to" class="form-control" placeholder="Выберите дату" value="<?=$filter_value_view['date_to']?>">
							</div>
						</div>
					</div>
				</div>
		</div>
		<div class="col-lg-3">
				<div class="form-group">
					<label for="filter_user" class="font-weight-bold">Пользователь</label>
					<select name="filter_user" id="filter_user" class="form-control">
						<option value="none" selected>Все пользователи</option>
<?

						$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id` IN (SELECT `user_id` FROM `transactions` GROUP BY `user_id`) ORDER BY FIELD(`id`,1,22,27,7037) DESC, `name` ASC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						while ($filter_user = mysqli_fetch_assoc($query)) {
							
							if ($filter_user['id'] == $filter_value_view['user'])
								echo '<option value="'.$filter_user['id'].'" selected>['.$filter_user['id'].'] '.$filter_user['name'].' '.$filter_user['surname'].'</option>';
							else
								echo '<option value="'.$filter_user['id'].'">['.$filter_user['id'].'] '.$filter_user['name'].' '.$filter_user['surname'].'</option>';

						}

?>
					</select>
				</div>
				<div class="form-group">
					<label for="filter_type" class="font-weight-bold">Тип операции</label>
					<select name="filter_type" id="filter_type" class="form-control">
						<option value="none" selected>Все типы операций</option>

<?

						foreach ($transaction_type_arr as $transaction_type_filter) {
							
							if ($transaction_type_filter[0] == $filter_value_view['type'])
								echo '<option value="'.$transaction_type_filter[0].'" selected>'.$transaction_type_filter[1].'</option>';
							else
								echo '<option value="'.$transaction_type_filter[0].'">'.$transaction_type_filter[1].'</option>';

						}

?>

					</select>
				</div>
		</div>
		<div class="col-lg-2">
			<div class="form-group">
				<label for="filter_task" class="font-weight-bold">ID задачи</label>
				<input type="number" name="filter_task" id="filter_task" class="form-control" placeholder="Введите ID задачи..." value="<?=$filter_value_view['task']?>">
			</div>
			<div class="form-group">
				<label class="text-white">Нажмите</label>
				<button type="submit" class="btn btn-success btn-block">Фильтровать</button>
			</div>
		</div>
	</div>
</form>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<div class="col-lg-6">
				<?if (!empty($_SESSION['transaction_filter'])):?>
				<form method="POST" class="d-inline-block">
					<div class="form-group text-left">
						<input type="hidden" name="clear_filter" value="1">
						<button type="submit" class="btn btn-warning">Очистить фильтр</button>
					</div>
				</form>
				<?endif;?>
			</div>
			<div class="col-lg-6">
				<p class="text-right mt-2 mb-3">Найдено операций: <b><?=$count_transactions['count']?></b></p>
			</div>
		</div>
	</div>
</div>

<?

$sql = "SELECT SUM(`add_funds`) as amount FROM `transactions`".$sql_part_transaction;
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$sum_transactions = mysqli_fetch_assoc($query);

?>

<div class="table-responsive mb-5" style="overflow:auto">
	<table class="table table-sm table-hover" style="font-size:14px">
		<thead class="thead-light">
			<tr>
				<th>№</th>
				<th>Тип</th>
				<th>ID задачи</th>
				<th>[ID] Имя/Фамилия</th>
				<th>Описание</th>
				<th data-toggle="tooltip" title="<?=$sum_transactions['amount']?> грн.">
					<span class="btn btn-link btn-sm text-danger font-weight-bold p-0">Сумма</span>
				</th>
				<th>ЭПС</th>
				<th>Статус</th>
				<th>Было</th>
				<th>Изменение</th>
				<th>Стало</th>
				<th>Дата/Время</th>
			</tr>
		</thead>
		<tbody>
			
		<?

		$num = 100;
			
		$total = intval(($count_transactions['count'] - 1) / $num) + 1;
		$page = intval($_GET['page']);

		if(empty($page) or $page < 0) $page = 1;  
		if($page > $total) $page = $total;  

		$start = $page * $num - $num;

		/*$sql = "SELECT * FROM `transactions` ORDER BY `created` DESC, `id` DESC LIMIT $start, $num";

		if (!empty($search)) $sql = "SELECT * FROM `transactions` WHERE `action` LIKE '%{$search}%' ORDER BY `created` DESC, `id` DESC LIMIT $start, $num";
		if (!empty($date)) $sql = "SELECT * FROM `transactions` WHERE DATE(`created`) = '{$date}' ORDER BY `created` DESC, `id` DESC LIMIT $start, $num";*/

		$sql = "SELECT * FROM `transactions`".$sql_part_transaction." ORDER BY `created` DESC, `id` DESC LIMIT $start, $num";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) == 0) {

			echo '<tr><td colspan="11" class="text-sm-center">История операций пустая</td></tr>';

		}

		$n = 0;

		while ($transactions = mysqli_fetch_assoc($query)) {
			
			$n++;

			$user_id = $transactions['user_id'];
			$sql_user = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$user_id}'";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error($db));
			$user = mysqli_fetch_assoc($query_user);

			switch ($transactions['ps']) {
				default:
					$transactions['ps'] = 'Не указана';
					break;
			}

			foreach ($transaction_type_arr as $transaction_type_value) {
				
				if ($transactions['type'] == $transaction_type_value[0]) {

					$transaction_type = '<i class="material-icons" style="color:'.$transaction_type_value[3].'" data-toggle="tooltip" title="'.$transaction_type_value[1].'">'.$transaction_type_value[2].'</i>';
					$transaction_change = '<span style="color:'.$transaction_type_value[4].'">' . $transactions['change'] . ' грн.</span>';

				}

			}

			switch ($transactions['status']) {
				case 0:
					$transaction_status = '<i class="material-icons" style="color:#d9534f" data-toggle="tooltip" title="Отменена">cancel</i>';
					break;
				case 1:
					$transaction_status = '<i class="material-icons" style="color:#dfb81c" data-toggle="tooltip" title="Ожидает подтверждения">watch_later</i>';
					break;
				case 2:
					$transaction_status = '<i class="material-icons" style="color:#84ad00" data-toggle="tooltip" title="Выполнена">check_circle</i>';
					break;
			}

			if ($transactions['updated'] == $transactions['created'] and $transactions['type'] == 0 and $transactions['status'] == 1) {

				$transaction_status = '<i class="material-icons" style="color:#757575" data-toggle="tooltip" title="Создана заявка на пополнение баланса">watch_later</i>';

			}

			echo '
			<tr>
				<td>' . $transactions['id'] . '</td>
				<td>' . $transaction_type . '</td>
				<td>' . $transactions['task_id'] . '</td>
				<td>
					<form method="POST" action="/admin/users/">
						<input type="hidden" name="search" value="' . $transactions['user_id'] . '">
						<button type="submit" class="btn btn-link">[' . $transactions['user_id'] . '] ' . $user['name'] . ' ' . $user['surname'] . '</button>
					</form>
				</td>
				<td>' . $transactions['action'] . '</td>
				<td>' . $transactions['add_funds'] . '</td>
				<td>' . $transactions['ps'] . '</td>
				<td>' . $transaction_status . '</td>
				<td>' . $transactions['was'] . ' грн.</td>
				<td>' . $transaction_change . '</td>
				<td>' . $transactions['became'] . ' грн.</td>
				<td>' . $transactions['created'] . '</td>
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

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>