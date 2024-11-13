<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-4">
		<a href="/account/school/" class="btn btn-primary btn-block mb-3 text-uppercase font-weight-bold"><i class="fa fa-graduation-cap"></i> Уроки</a>
	</div>
	<div class="col-lg-2"></div>
	<div class="col-lg-4">
		<a href="/account/school_homework/" class="btn btn-warning btn-block mb-3 text-uppercase"><i class="fa fa-file-text-o"></i> Домашнее задание</a>
	</div>
	<div class="col-lg-1"></div>
</div>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">
		<!-- <a class="nav-item nav-link active" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-controls="nav-courses" aria-selected="true">Курсы OLX і Prom.ua</a> -->
		<a class="nav-item nav-link active" id="nav-fornewbie-tab" data-toggle="tab" href="#nav-fornewbie" role="tab" aria-controls="nav-fornewbie" aria-selected="false">PDF Инструкция</a>
		<a class="nav-item nav-link" id="nav-videoinstruction-tab" data-toggle="tab" href="#nav-videoinstruction" role="tab" aria-controls="nav-videoinstruction" aria-selected="false">Видеоинструкции</a>
		<a class="nav-item nav-link" id="nav-motivation-tab" data-toggle="tab" href="#nav-motivation" role="tab" aria-controls="nav-motivation" aria-selected="false">Мотивация</a>
		<a class="nav-item nav-link" id="nav-photobank-tab" data-toggle="tab" href="#nav-photobank" role="tab" aria-controls="nav-photobank" aria-selected="false">Фотобанк</a>
		<a class="nav-item nav-link text-danger font-weight-bold" id="nav-vipschool-tab" href="#" data-toggle="tooltip" data-placement="bottom" title="Только для VIP Участников: секреты продаж, реальные кейсы, высокая доходность">VIP Обучение <i class="fa fa-lock"></i></a>
		<a class="nav-item nav-link text-danger font-weight-bold" id="nav-vipschool-tab" href="#" data-toggle="tooltip" data-placement="bottom" title="Закрытый бизнес клуб: нетворкинг, бизнес разборы и поставка целей">Коучинг Клуб <i class="fa fa-lock"></i></a>
	</div>
</nav>

<?if ($user['admin'] == 2):?>

<h3 class="text-center mt-5 mb-5">Как работать с админ панелью</h3>

<div class="row">
	<div class="col-sm-6 mb-3">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/OVWdaoGO8cE?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
	<div class="col-sm-6 mb-3">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/l_Z5txx9Nq4?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
	<div class="col-sm-6 mb-3">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/w44MoaVc7JM?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
</div>

<?endif;?>

<div class="tab-content">
	<!-- <div class="tab-pane fade show active" id="nav-courses" role="tabpanel" aria-labelledby="nav-courses-tab">

<?if ($user['admin'] != 2):?>

<?=$alert_message?>

<h3 class="text-center mt-5 mb-4">Выберите СВОЙ курс обучения продаж в интернете</h3>
<h6 class="text-center text-secondary font-weight-bold mb-5" style="border-radius: 5px;">ВНИМАНИЕ! ДЛЯ ПОВЫШЕНИЯ РЕЗУЛЬТАТОВ ОБУЧЕНИЯ, КОЛИЧЕСТВО УЧАСТНИКОВ ОГРАНИЧЕНО</h6>
<p>
	<img src="/assets/images/tmp_orders/screen8.png" class="w-100">
</p>
<div class="row justify-content-center mt-5 mb-5">
	<div class="col-sm-8">
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/MLTXbXm2jpo?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header" style="background: #0098d0;">
				<h4 class="text-white">Курс OLX</h4>
			</div>
			<div class="card-body bg-light">
				<form method="POST">
					<input type="hidden" name="kurs" value="olx">
					<div class="form-group">
						<label class="font-weight-bold">Ваше имя</label>
						<input type="text" name="name" placeholder="Введите имя" class="form-control" required>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Ваш телефон</label>
						<input type="tel" name="phone" placeholder="Введите телефон" class="form-control" required>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-warning btn-lg text-white" style="background: #ff8300;">Выбрать курс</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header" style="background: linear-gradient(135deg,#4854a2,#772088);">
				<h4 class="text-white">Курс Prom.ua</h4>
			</div>
			<div class="card-body bg-light">
				<form method="POST">
					<input type="hidden" name="kurs" value="prom">
					<div class="form-group">
						<label class="font-weight-bold">Ваше имя</label>
						<input type="text" name="name" placeholder="Введите имя" class="form-control">
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Ваш телефон</label>
						<input type="tel" name="phone" placeholder="Введите телефон" class="form-control">
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary btn-lg" style="background: #8646aa;border-color: #8646aa;">Выбрать курс</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?endif;?>

	</div> -->
	<div class="tab-pane fade show active" id="nav-fornewbie" role="tabpanel" aria-labelledby="nav-fornewbie-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Пошаговая инструкция для новичка</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/instruction-for-a-beginner.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<!-- <h3 class="text-center text-uppercase mt-5 mb-4">Каталог Online Naxodka июль 2019</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/catalog-top-goods-jule-2019.pdf" class="btn btn-success btn-lg" target="_blank" onclick="gtag('event', 'Клик по кнопке каталог', {'event_category' : 'Каталог ONLINE NAXODKA'})">Скачать</a>
		</p> -->
		<h3 class="text-center text-uppercase mt-5 mb-4">Алгоритм быстрого заполнения акаунта Инстаграм</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/algorithm_to_quickly_fill_out_instagram_account.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<?if ($user['status'] > 1):?>
		<h3 class="text-center text-uppercase mt-5 mb-4">Речевой диалог Первого звонка Новичку</h3>
		<p class="text-center">
			<a href="https://docs.google.com/document/d/1MhABOR9kzyWmSrwdtnhTWb5gUIhaz2EmLGZZ8FgLy_w/edit?usp=sharing" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<?endif;?>
		<h3 class="text-center text-uppercase mt-5 mb-4">Речевой модуль по Ремонту турбин</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/turbine-repair-speech-module.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<h3 class="text-center text-uppercase mt-5 mb-4">Речевой модуль по Пассажирским перевозкам</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/dialogue-with-the-passenger-dropshipper.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<h3 class="text-center text-uppercase mt-5 mb-4">Речевой модуль по Землеустроительным услугам</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/dialogue-on-land-management-works.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
		<h3 class="text-center text-uppercase mt-5 mb-4">Вопросы и ответы по строительным лесам</h3>
		<p class="text-center">
			<a href="/assets/files/pdf/questions-and-answers-on-construction-forests.pdf" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
	</div>
	<div class="tab-pane fade" id="nav-videoinstruction" role="tabpanel" aria-labelledby="nav-videoinstruction-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Как зарегистрироваться на платформе Online Naxodka</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/dDsiYQQkSjY?rel=0" allowfullscreen></iframe>
		</div>
		<h3 class="text-center text-uppercase mt-5 mb-4">Как оформить заказ с помощью Наложенного Платежа на платформе Online Naxodka</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/H3BrsOOlTGs?rel=0" allowfullscreen></iframe>
		</div>
		<h3 class="text-center text-uppercase mt-5 mb-4">Как пополнить личный баланс своего кошелька</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/pDZmH2coG_g?rel=0" allowfullscreen></iframe>
		</div>
		<?if ($user['status'] > 1):?>
		<h3 class="text-center text-uppercase mt-5 mb-4">Как скачать инструкцию новичку pdf документ для наставников</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/LVT9GEnywAo?rel=0" allowfullscreen></iframe>
		</div>
		<?endif;?>
		<h3 class="text-center text-uppercase mt-5 mb-4">Как добавить ник нейм с телеграма</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/V8wAaVgKoQ4?rel=0" allowfullscreen></iframe>
		</div>
	</div>
	<div class="tab-pane fade" id="nav-motivation" role="tabpanel" aria-labelledby="nav-motivation-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Как заработать на олх СЕКРЕТ 100 000 сим карт</h3>
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/92LXzFn-3_0?rel=0" allowfullscreen></iframe>
		</div>
	</div>
	<div class="tab-pane fade" id="nav-photobank" role="tabpanel" aria-labelledby="nav-photobank-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Фотографии TM "Полісся"</h3>
		<p class="text-center">
			<a href="https://drive.google.com/drive/folders/1SEb6ludkWqutLdrWeDAX7xpYf5CMozc4?usp=sharing" class="btn btn-success btn-lg" target="_blank">Скачать</a>
		</p>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>