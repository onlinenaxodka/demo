<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `transactions` WHERE `user_id`='{$user_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
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

?>

<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-light">
			<tr>
				<th>Дата</th>
				<th>№ операции</th>
				<th>Описание</th>
				<th>Статус</th>
				<th>Было</th>
				<th>Изминение</th>
				<th>Стало</th>
			</tr>
		</thead>
		<tbody>
<?

			$sql = "SELECT * FROM `transactions` WHERE `user_id`='{$user_id}' ORDER BY `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) == 0) {

				echo '<tr><td colspan="7" class="text-sm-center">История операций пустая</td></tr>';

			}

			while ($transaction = mysqli_fetch_assoc($query)) {

				$transaction['created'] = date($datetime_format, strtotime($transaction['created']));
									
				// #84ad00 - green
				// #d9534f - red
				// #dfb81c - yellow
				// #6c757d - gray

				switch ($transaction['type']) {
					case 0:
					case 3:
					case 9:
						$transaction_change = '<span style="color:#84ad00">' . $transaction['change'] . ' грн.</span>';
						break;
					case 1:
					case 2:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
						$transaction_change = '<span style="color:#d9534f">' . $transaction['change'] . ' грн.</span>';
						break;
					default:
						$transaction_change = '<span style="color:#6c757d">' . $transaction['change'] . ' грн.</span>';
						break;
				}

				switch ($transaction['status']) {
					case 0:
						$transaction_status = '<i class="material-icons" style="color:#d9534f" data-toggle="tooltip" title="Отменена">cancel</i>';
						break;
					case 1:
						$transaction_status = '<i class="material-icons" style="color:#dfb81c" data-toggle="tooltip" title="В процессе">watch_later</i>';
						break;
					case 2:
						$transaction_status = '<i class="material-icons" style="color:#84ad00" data-toggle="tooltip" title="Выполнено">check_circle</i>';
						break;
				}

				if ($transaction['updated'] == $transaction['created'] and $transaction['type'] == 0 and $transaction['status'] == 1) {

					$transaction_status = '<i class="material-icons" style="color:#757575" data-toggle="tooltip" title="Создана заявка на пополнение баланса">watch_later</i>';

				}
								
?>
				<tr>
					<td class="font-italic text-secondary"><?=$transaction['created']?></td>
					<td><?=$transaction['id']?></td>
					<td><?=$transaction['action']?></td>
					<td><?=$transaction_status?></td>
					<td><?=number_format($transaction['was'], 2, '.', '')?> грн.</td>
					<td><?=$transaction_change?></td>
					<td><?=number_format($transaction['became'], 2, '.', '')?> грн.</td>
				</tr>
<?

			}

?>
		</tbody>
	</table>
</div>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

$pervpage = '';
$page2left = '';
$page1left = '';
$currentpage = '';
$page1right = '';
$page2right = '';
$nextpage = '';

if ($page == 1) $PHP_SELF = '/account/transactions/';
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

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>