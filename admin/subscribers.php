<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `subscribers`";
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

<div class="table-responsive mb-5" style="overflow: auto;">
	<table class="table table-sm table-hover" style="font-size: 14px;">
		<thead class="thead-default">
			<tr>
				<th>ID</th>
				<th>Менеджер</th>
				<th>Имя</th>
				<th>Телефон</th>
				<th>E-mail</th>
				<th>Сайт</th>
				<th>Описание</th>
				<th>Статус</th>
				<th>IP</th>
				<th>ГЕО Локация</th>
				<th>Экран</th>
				<th>Калькулятор</th>
				<th>Редактирован</th>
				<th>Зарегистрирован</th>
			</tr>
		</thead>
		<tbody>
			<?

			$sql = "SELECT * FROM `subscribers` ORDER BY `status` ASC, `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) > 0) {

				while ($subscribers = mysqli_fetch_assoc($query)) {

					$subscribers_user_id = $subscribers['user_id'];

					$sql_users = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$subscribers_user_id}'";
					$query_users = mysqli_query($db, $sql_users) or die(mysqli_error());
					$partner_data = mysqli_fetch_assoc($query_users);

					switch ($subscribers['status']) {
						case 0:
							$subscribers_status = 'Новый';
							break;
						case 1:
							$subscribers_status = 'В обработке';
							break;
						case 2:
							$subscribers_status = 'Обработан';
							break;
					}

					switch ($subscribers['screen']) {
						case 1:
							$subscribers['screen'] = 'Первый экран';
							break;
						case 2:
							$subscribers['screen'] = 'Экран &quot;ЗАРАБОТАТЬ - Калькулятор&quot;';
							break;
						case 3:
							$subscribers['screen'] = 'Экран &quot;ПРЯМО СЕЙЧАС&quot;';
							break;
						case 4:
							$subscribers['screen'] = 'Экран &quot;ДВА ПУТИ&quot;';
							break;
						case 5:
							$subscribers['screen'] = 'Последный экран';
							break;
						default:
							$subscribers['screen'] = 'Экран не определен';
							break;
					}

				?>

				<tr>
					<td><?=$subscribers['id']?></td>
					<td>[<?=$subscribers['user_id']?>] <?=$partner_data['name']?> <?=$partner_data['surname']?></td>
					<td><?=$subscribers['name']?></td>
					<td><?=$subscribers['phone']?></td>
					<td><?=$subscribers['email']?></td>
					<td><?=$subscribers['site']?></td>
					<td><?=$subscribers['description']?></td>
					<td><?=$subscribers_status?></td>
					<td><?=$subscribers['ip']?></td>
					<td><?=$subscribers['geo']?></td>
					<td><?=$subscribers['screen']?></td>
					<td><?=$subscribers['calc']?> грн.</td>
					<td><?=$subscribers['updated']?></td>
					<td><?=$subscribers['created']?></td>
				</tr>

				<?

				}

			} else {

				echo '<tr><td colspan="7" class="text-center">Список лидов пуст</td></tr>';

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