<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `orders_kurs`";
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
				<th>Код заявки</th>
				<th>Пользователь</th>
				<th>Курс</th>
				<th>Имя</th>
				<th>Телефон</th>
				<th>E-mail</th>
				<th>Создана</th>
			</tr>
		</thead>
		<tbody>
			<?

			$sql = "SELECT * FROM `orders_kurs` ORDER BY `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) > 0) {

				while ($orders_kurs = mysqli_fetch_assoc($query)) {

					$orders_kurs_user_id = $orders_kurs['user_id'];

					$sql_users = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$orders_kurs_user_id}'";
					$query_users = mysqli_query($db, $sql_users) or die(mysqli_error());
					$user_data = mysqli_fetch_assoc($query_users);

					switch ($orders_kurs['kurs']) {
						case 'olx':
							$orders_kurs['kurs'] = 'OLX';
							break;
						case 'prom':
							$orders_kurs['kurs'] = 'Prom';
							break;
						case 'webinar':
							$orders_kurs['kurs'] = 'Вебинар';
							break;
						case 'anelkinkurs':
							$orders_kurs['kurs'] = 'Курс Анелькина';
							break;
						case 'rozetka':
							$orders_kurs['kurs'] = 'Курс Rozetka';
							break;
						case 'suma50':
							$orders_kurs['kurs'] = 'Инвестиция в Полесье 50$';
							break;
						case 'suma100':
							$orders_kurs['kurs'] = 'Инвестиция в Полесье 100$';
							break;
						case 'suma500':
							$orders_kurs['kurs'] = 'Инвестиция в Полесье 500$';
							break;
						case 'suma1000':
							$orders_kurs['kurs'] = 'Инвестиция в Полесье 1000$';
							break;
						default:
							$orders_kurs['kurs'] = 'OLX';
							break;
					}

					if (empty($orders_kurs['email'])) $orders_kurs['email'] = '-';

				?>

				<tr>
					<td><?=$orders_kurs['id']?></td>
					<td>[<?=$orders_kurs['user_id']?>] <?=$user_data['name']?> <?=$user_data['surname']?></td>
					<td><?=$orders_kurs['kurs']?></td>
					<td><?=$orders_kurs['name']?></td>
					<td><?=$orders_kurs['phone']?></td>
					<td><?=$orders_kurs['email']?></td>
					<td><?=date('d.m.Y H:i', strtotime($orders_kurs['created']))?></td>
				</tr>

				<?

				}

			} else {

				echo '<tr><td colspan="6" class="text-center">Список заявок на курсы пуст</td></tr>';

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