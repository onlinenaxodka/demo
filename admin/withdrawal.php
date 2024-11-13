<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$admin_user_id = $user_id;

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `transactions` WHERE `type`=2";
		$query = mysqli_query($db, $sql) or die(mysqli_error());
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

?>

<div class="table-responsive mb-5" style="overflow:auto">
	<table class="table table-sm table-hover" style="font-size:14px">
		<thead class="thead-light">
			<tr>
				<th>#</th>
				<th>№ Операции</th>
				<th>ID Пользователя</th>
				<th>Имя/Фамилия</th>
				<th>Описание</th>
				<th>Сумма</th>
				<th>ЭПС</th>
				<th>Статус</th>
				<th>Создана</th>
			</tr>
		</thead>
		<tbody>
			
		<?

		$sql = "SELECT * FROM `transactions` WHERE `type`=2 ORDER BY FIELD(`status`,1,2,0) ASC, `updated` DESC, `created` ASC LIMIT $start, $num";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		if (mysqli_num_rows($query) == 0) {

			echo '<tr><td colspan="9" class="text-sm-center">Выводов средств еще не было</td></tr>';

		}

		$n = 0;

		while ($transactions = mysqli_fetch_assoc($query)) {
			
			$n++;

			$user_id = $transactions['user_id'];
			$sql_user = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$user_id}'";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query_user);

			switch ($transactions['ps']) {
				default:
					$transactions['ps'] = 'Не указана';
					break;
			}

			if ($transactions['status'] == 0) {
				$color_text = '#d9534f';
				$transactions['status'] = 'Отменена<br>' . $transactions['updated'];
			} elseif ($transactions['status'] == 1) {
				$color_text = '#dfb81c';
				$transactions['status'] = 'Ожидает подтверждения<form action="/admin/withdrawal/" method="POST"><input type="hidden" name="status_confirm" value="' . $transactions['id'] . '"><input type="submit" class="btn btn-success btn-sm btn-block" value="Подтвердить"></form>';
				if ($admin_user_id <> 2) $transactions['status'] = 'Ожидает подтверждения';
			} elseif ($transactions['status'] == 2) {
				$color_text = '#84ad00';
				$transactions['status'] = 'Выполнена<br>' . $transactions['updated'];
			}

			echo '
			<tr>
				<th>' . $n . '</th>
				<td>' . $transactions['id'] . '</td>
				<td>' . $transactions['user_id'] . '</td>
				<td>' . $user['name'] . ' ' . $user['surname'] . '</td>
				<td>' . $transactions['action'] . '</td>
				<td>' . $transactions['add_funds'] . '</td>
				<td>' . $transactions['ps'] . '</td>
				<th style="color:' . $color_text . '">' . $transactions['status'] . '</th>
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