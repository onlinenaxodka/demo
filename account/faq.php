<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$sql = "SELECT * FROM `faq` GROUP BY `whom` ORDER BY `whom` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

?>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">

<?

while ($faq_whom = mysqli_fetch_assoc($query)) {

	$faq_whom_status = '';
	$faq_whom_status_bool = 'false';

	if (empty($_GET['nav'])) {

		if ($faq_whom['whom'] == 3) {
			$faq_whom_status = ' active';
			$faq_whom_status_bool = 'true';
		}

	} else {

		if ($faq_whom['whom'] == $_GET['nav']) {

			$faq_whom_status = ' active';
			$faq_whom_status_bool = 'true';

		}

	}

	switch ($faq_whom['whom']) {
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

?>		

		<a class="nav-item nav-link<?=$faq_whom_status?>" id="nav-<?=$faq_whom['whom']?>-tab" data-toggle="tab" href="#nav-<?=$faq_whom['whom']?>" role="tab" aria-controls="nav-<?=$faq_whom['whom']?>" aria-selected="<?=$faq_whom_status_bool?>"><?=$faq_whom_word?></a>

<?

}

?>

	</div>
</nav>

<div class="tab-content mt-2">

<?

$sql = "SELECT * FROM `faq` GROUP BY `whom` ORDER BY `whom` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($faq_whom = mysqli_fetch_assoc($query)) {

	$faq_whom_status = '';

	if (empty($_GET['nav'])) {

		if ($faq_whom['whom'] == 3) $faq_whom_status = ' show active';

	} else {

		if ($faq_whom['whom'] == $_GET['nav']) $faq_whom_status = ' show active';

	}

?>	

	<div class="tab-pane fade<?=$faq_whom_status?>" id="nav-<?=$faq_whom['whom']?>" role="tabpanel" aria-labelledby="nav-<?=$faq_whom['whom']?>-tab">

		<div id="accordion<?=$faq_whom['whom']?>">
	
<?

$sql_accordion = "SELECT * FROM `faq` WHERE `whom`='{$faq_whom['whom']}' AND `lang`='ru' AND `status`=1 ORDER BY `sort` ASC, `created` DESC";
$query_accordion = mysqli_query($db, $sql_accordion) or die(mysqli_error($db));

$n = 0;

if (mysqli_num_rows($query_accordion) > 0) {

	while ($faq = mysqli_fetch_assoc($query_accordion)) {

		$n++;

		$collapsed_text = ' collapsed';
		$collapse_class = '';
		$aria_expanded = 'false';

		if ($n == 1) {

			$collapsed_text = '';
			$collapse_class = ' show';
			$aria_expanded = 'true';

		}

?>

			<div class="card mb-1">
				<div class="card-header" id="heading<?=$faq['id']?>">
					<h5 class="mb-0">
						<button class="btn btn-link<?=$collapsed_text?>" data-toggle="collapse" data-target="#collapse<?=$faq['id']?>" aria-expanded="<?=$aria_expanded?>" aria-controls="collapse<?=$faq['id']?>" style="white-space: normal;">
							<?=$faq['question']?>
						</button>
					</h5>
				</div>
				<div id="collapse<?=$faq['id']?>" class="collapse<?=$collapse_class?>" aria-labelledby="heading<?=$faq['id']?>" data-parent="#accordion<?=$faq_whom['whom']?>">
					<div class="card-body">
						<?=$faq['answer']?>
					</div>
				</div>
			</div>

<?

	}

} else {

	echo '<p class="text-center mt-5">Список вопросов и ответов пуст</p>';

}

?>

		</div>

	</div>

<?

}

?>

</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>