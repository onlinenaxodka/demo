<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?if (!empty($_GET['question'])):?>

<p>
	<a href="/admin/information_faq/" class="btn btn-dark"><< Назад</a>
</p>

<?

$faq_id = (isset($_GET['question'])) ? mysqli_real_escape_string($db, $_GET['question']) : '';
$faq_id = test_request($faq_id);
$faq_id = intval($faq_id);

$sql = "SELECT * FROM `faq` WHERE `id`='{$faq_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

	$faq = mysqli_fetch_assoc($query);

?>

<div class="container">
	<form method="POST">
		<input type="hidden" name="act" value="edit">
		<input type="hidden" name="id" value="<?=$faq['id']?>">
		<div class="form-group">
			<label for="inputQuestion" class="font-weight-bold">Вопрос</label>
			<input type="text" name="question" class="form-control" id="inputQuestion" placeholder="Введите вопрос" value="<?=$faq['question']?>" required>
		</div>
		<div class="form-group">
			<label for="inputAnswer" class="font-weight-bold">Ответ</label>
			<textarea name="answer" rows="7" id="inputAnswer" class="form-control faq-description summernote" placeholder="Введите ответ..."><?=$faq['answer']?></textarea>
		</div>
		<div class="form-group">
			<label for="inputWhom" class="font-weight-bold">Категория</label>
			<select name="whom" id="inputWhom" class="form-control" required>
				<option value="1" <?=(($faq['whom']==1)?' selected':'')?>>Общии</option>
				<option value="2" <?=(($faq['whom']==2)?' selected':'')?>>Я поставщик</option>
				<option value="3" <?=(($faq['whom']==3)?' selected':'')?>>Я интернет магазин/продавец</option>
				<option value="4" <?=(($faq['whom']==4)?' selected':'')?>>Я инвестор</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputSort" class="font-weight-bold">Порядок сортировки</label>
			<input type="number" name="sort" class="form-control" id="inputSort" placeholder="Введите порядок сортировки" value="<?=$faq['sort']?>">
		</div>
		<div class="form-group text-center">
			<button type="submit" class="btn btn-success btn-lg">Редактировать</button>
		</div>
	</form>
</div>

<?

} else {

?>

<p class="text-center mt-3 mb-3">Такого вопроса нет</p>

<?

}

?>

<?else:?>

<? 

// Переменная хранит число сообщений выводимых на станице
		$num = 30;
		// Определяем общее число сообщений в базе данных
		$sql = "SELECT COUNT(1) as count FROM `faq`";
		if (!empty($_SESSION['faq_filter']))
			$sql = "SELECT COUNT(1) as count FROM `faq` WHERE `whom`='{$_SESSION['faq_filter']}'";
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

?>

<div class="row">
	<div class="col-sm-6 mb-3">
		<div class="row">
			<div class="col-sm-4">
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addFAQ">Создать</button>
			</div>
			<div class="col-sm-8">
				<form method="POST">
					<input type="hidden" name="act" value="filter">
					<div class="row">
						<div class="col-sm-8">
							<select name="whom" id="inputWhom" class="form-control">
								<option value="0" selected>Все</option>
								<option value="1" <?=(($_SESSION['faq_filter']==1)?' selected':'')?>>Общии</option>
								<option value="2" <?=(($_SESSION['faq_filter']==2)?' selected':'')?>>Я поставщик</option>
								<option value="3" <?=(($_SESSION['faq_filter']==3)?' selected':'')?>>Я интернет магазин/продавец</option>
								<option value="4" <?=(($_SESSION['faq_filter']==4)?' selected':'')?>>Я инвестор</option>
							</select>
						</div>
						<div class="col-sm-4">
							<button type="submit" class="btn btn-primary">Фильтровать</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-6 mb-3">
		<p class="text-right mt-2 mb-0">Найдено вопросов: <b><?=$posts['count']?></b></p>
	</div>
</div>

<div class="table-responsive mb-5" style="overflow:auto">
	<table class="table table-sm table-hover" style="font-size:14px">
		<thead class="thead-light">
			<tr>
				<th style="max-width: 100px;">Дата/Время</th>
				<th>Вопрос</th>
				<th>Категория</th>
				<th>Порядок сортировки</th>
				<th>Действие</th>
			</tr>
		</thead>
		<tbody>
			
<?

		$sql = "SELECT * FROM `faq` WHERE `lang`='ru' ORDER BY `sort` ASC, `created` DESC LIMIT $start, $num";
		if (!empty($_SESSION['faq_filter']))
			$sql = "SELECT * FROM `faq` WHERE `whom`='{$_SESSION['faq_filter']}' AND `lang`='ru' ORDER BY `sort` ASC, `created` DESC LIMIT $start, $num";

		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) > 0) {

				while ($faq = mysqli_fetch_assoc($query)) {

					switch ($faq['whom']) {
						case 1:
							$faq_whom_word = 'Общии';
							break;
						case 2:
							$faq_whom_word = 'Я поставщик';
							break;
						case 3:
							$faq_whom_word = 'Я интернет магазин/продавец';
							break;
						case 4:
							$faq_whom_word = 'Я инвестор';
							break;
					}

					$faq['created'] = date('d.m.Y H:i', strtotime($faq['created']));
					
?>

						<tr>
							<th class="font-italic"><?=$faq['created']?></th>
							<td>
								<a href="/admin/information_faq/?question=<?=$faq['id']?>"><?=$faq['question']?></a>
							</td>
							<td><?=$faq_whom_word?></td>
							<td><?=$faq['sort']?></td>
							<td>
								<form method="POST" class="float-left">
									<input type="hidden" name="act" value="delete">
									<input type="hidden" name="id" value="<?=$faq['id']?>">
									<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="return confirm('Вы действительно хотите удалить этот вопрос/ответ?')">
										<i class="material-icons float-left">delete_forever</i>
									</button>
								</form>
							</td>
						</tr>

<?

				}

			} else {

				echo '<tr><td colspan="5" class="text-center pt-3 pb-3">Еще нет вопросов и ответов</td></tr>';

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

<div class="modal fade bd-example-modal-lg" id="addFAQ">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Создать вопрос/ответ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<input type="hidden" name="act" value="add">
					<div class="form-group">
						<label for="inputQuestion" class="font-weight-bold">Вопрос</label>
						<input type="text" name="question" class="form-control" id="inputQuestion" placeholder="Введите вопрос" required>
					</div>
					<div class="form-group">
						<label for="inputAnswer" class="font-weight-bold">Ответ</label>
						<textarea name="answer" rows="7" id="inputAnswer" class="form-control faq-description summernote" placeholder="Введите ответ..."></textarea>
					</div>
					<div class="form-group">
						<label for="inputWhom" class="font-weight-bold">Категория</label>
						<select name="whom" id="inputWhom" class="form-control" required>
							<option value="1" selected>Общии</option>
							<option value="2">Я поставщик</option>
							<option value="3">Я интернет магазин/продавец</option>
							<option value="4">Я инвестор</option>
						</select>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success btn-lg">Создать</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?endif;?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>