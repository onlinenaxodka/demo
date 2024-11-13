<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `logs_admin`";
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

$sql = "SELECT * FROM `logs_admin` ORDER BY `created` DESC LIMIT $start, $num";
$query = mysqli_query($db, $sql) or die(mysqli_error());

?>

<div class="table-responsive">
	<table class="table table-sm table-hover">
		<thead class="thead-default">
			<tr>
				<th>#</th>
				<th>[ID] Имя / Фамилия</th>
				<th>Таблица</th>
				<th>ID строка</th>
				<th>Действие</th>
				<th style="min-width:160px">Лог создан</th>
			</tr>
		</thead>
		<tbody>
			
		<?

		$n = 0;

		while ($logs_admin = mysqli_fetch_assoc($query)) {
			
			$n++;

			$user_id = $logs_admin['user_id'];
			$sql_user = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$user_id}'";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query_user);

			switch ($logs_admin['table_name']) {
				case 'users':
					$logs_admin['table_name'] = 'Пользователи';
					break;
				case 'support_subjects':
					$logs_admin['table_name'] = 'Тема сообщения';
					break;
				case 'support_messages':
					$logs_admin['table_name'] = 'Сообщение';
					break;
				case 'transactions':
					$logs_admin['table_name'] = 'Операции';
					break;
				case 'marketing':
					$logs_admin['table_name'] = 'Маркетинг';
					break;
				case 'notable':
					$logs_admin['table_name'] = 'Без таблицы';
					break;
			}

			echo '
			<tr>
				<th>' . $n . '</th>
				<td>[' . $logs_admin['user_id'] . '] ' . $user['name'] . ' ' . $user['surname'] . '</td>
				<td>' . $logs_admin['table_name'] . '</td>
				<td>' . $logs_admin['id_row'] . '</td>
				<td>' . $logs_admin['action'] . '</td>
				<td>' . $logs_admin['created'] . '</td>
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