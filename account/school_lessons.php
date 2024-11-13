<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

	/*$video = array(
		array('dhDAyBNimRw','Знакомство', 'Знакомство с платформой ONLINE NAXODKA'),
		array('q2J-WBPJ_rg','Профиль','Раздел Профиль'),
		array('TNXKo8qX3bI','Кошелек','Раздел Кошелек'),
		array('kbhBc_QTdr4','Команда','Раздел Команда'),
		array('Ufkwj0ALIJc','Инструменты','Инструменты арбитража'),
		array('peVMXRdK2a8','Наставник','Страница наставника'),
		array('W41XgKarHwE','Инвестиции','Раздел Инвестиции'),
		array('Ct9q86K8fOg','Магазины','Раздел Магазины'),
		array('PIjdSK19s-c','Поддержка','Раздел Поддержка'),
		array('q41XBRbFM0M','Каталог','Раздел Каталог'),
		array('WD8KNaQ1Nbg', 'Заказ', 'Как оформить заказ наложенным платежем')
	);*/

if (empty($_GET['nav']) or $_GET['nav'] == 1) {

	$video = array(
		array('dhDAyBNimRw','Знакомство', 'Знакомство с платформой ONLINE NAXODKA'),
		
	);

} elseif ($_GET['nav'] == 2) {

	$video = array(
		array('WD8KNaQ1Nbg', 'Наложка', 'Как оформить заказ наложенным платежем'),
		array('wk7J8J3smEA', 'Внутр. баланс', 'Как оформить заказ с внутреннего баланс')
	);

} elseif ($_GET['nav'] == 3) {

	$video = array(
		array('q41XBRbFM0M','Каталог','Раздел Каталог'),
		array('W41XgKarHwE','Инвестиции','Раздел Инвестиции'),
		array('Ct9q86K8fOg','Магазины','Раздел Магазины')
	);

} elseif ($_GET['nav'] == 4) {

	$video = array(
		array('q2J-WBPJ_rg','Профиль','Раздел Профиль'),
		array('TNXKo8qX3bI','Кошелек','Раздел Кошелек'),
		array('kbhBc_QTdr4','Команда','Раздел Команда'),
		array('Ufkwj0ALIJc','Инструменты','Инструменты арбитража'),
		array('peVMXRdK2a8','Наставник','Страница наставника'),
		array('PIjdSK19s-c','Поддержка','Раздел Поддержка')
	);

}

?>

<div class="row">
	<div class="col-sm-3 mb-3">
		<a href="/account/school_lessons/?nav=1" class="btn btn-<?=((empty($_GET['nav']) or $_GET['nav']==1)?'primary':'secondary')?> btn-block">Знакомство</a>
	</div>
	<div class="col-sm-3 mb-3">
		<a href="/account/school_lessons/?nav=2" class="btn btn-<?=(($_GET['nav']==2)?'primary':'secondary')?> btn-block">Как оформить заказ</a>
	</div>
	<div class="col-sm-3 mb-3">
		<a href="/account/school_lessons/?nav=3" class="btn btn-<?=(($_GET['nav']==3)?'primary':'secondary')?> btn-block">Где зарабатывать</a>
	</div>
	<div class="col-sm-3 mb-3">
		<a href="/account/school_lessons/?nav=4" class="btn btn-<?=(($_GET['nav']==4)?'primary':'secondary')?> btn-block">Профиль</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-8">
		<div class="tab-content" id="pills-tabContent" data-count-video="<?=count($video)?>">

<?

			for ($i=0; $i < count($video); $i++) {
				
				$active_item = '';

				if ($i == 0) $active_item = ' show active';
				
?>

			<div class="tab-pane fade<?=$active_item?>" id="pills-<?=$i?>" role="tabpanel" aria-labelledby="pills-<?=$i?>-tab">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe id="videoYT-<?=$i?>" class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$video[$i][0]?>?rel=0&enablejsapi=1" allowfullscreen></iframe>
				</div>
				<h3 class="text-center font-weight-bold mt-3 mb-3"><?=$video[$i][2]?></h3>
			</div>

<?				

			}

?>

		</div>
	</div>
	<div class="col-sm-4">
		<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

<?

			for ($i=0; $i < count($video); $i++) {

				$active_item = '';
				$active_item_status = 'false';

				if ($i == 0) {
					$active_item = ' active';
					$active_item_status = 'true';
				}

?>

			<li class="nav-item">
				<a class="nav-link p-1<?=$active_item?>" id="pills-<?=$i?>-tab" data-toggle="pill" href="#pills-<?=$i?>" role="tab" aria-controls="pills-<?=$i?>" aria-selected="<?=$active_item_status?>">
					<div class="row">
						<div class="col-6">
							<img src="https://img.youtube.com/vi/<?=$video[$i][0]?>/hqdefault.jpg" class="img-fluid" alt="Responsive image">
						</div>
						<div class="col-6">
							<h5 class="mt-0 mb-0"><?=$video[$i][1]?></h5>
							<p class="mt-0 mb-0">№<?=($i+1)?> - <span id="video-<?=$i?>">0:00</span></p>
						</div>
					</div>
				</a>
				<hr>
			</li>

<?

			}

?>

		</ul>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>