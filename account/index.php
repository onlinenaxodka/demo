<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?=$alert_message?>

<div class="card mb-2">
	<div class="card-body">
		<?

		$user_partner_id = $user['partner_id'];

		$sql_nastavnyk = "SELECT * FROM `users` WHERE `id`='{$user_partner_id}'";
		$query_nastavnyk = mysqli_query($db, $sql_nastavnyk) or die(mysqli_error());
		$user_nastavnyk = mysqli_fetch_assoc($query_nastavnyk);
		
		$user_nastavnyk_data = $user_nastavnyk['name'] . ' ' . $user_nastavnyk['surname'];
		if ($user_nastavnyk['id'] == 1) 
			$user_nastavnyk_data = 'Online Naxodka';
		
		$user_telegram = (!empty($user_nastavnyk['telegram'])) ? '<a href="https://t.me/'.$user_nastavnyk['telegram'].'" target="_blank">@'.$user_nastavnyk['telegram'].'</a>' : 'еще не указан';

		?>
		<style type="text/css">
    				.nastavnyk-telegram {
						padding: 10px 20px 10px 0;
						border: 2px solid #2da5dc;
						border-radius: 10px;
        			}
        			.nastavnyk-telegram img {
						position: relative;
						top: -2px;
						width: 50px;
						background-color: #2da5dc;
						margin-right: 10px;
						border-radius: 8px 0 0 8px;
    				}
    	</style>
		<h4 class="text-center mt-0 mb-0">
			<b>Мой наставник:</b>
			<?=$user_nastavnyk_data?>
			<span class="nastavnyk-telegram d-lg-inline d-block ml-lg-3 mt-2 mt-lg-0">
				<img src="/assets/images/social/telegram.png" alt="Telegram"><?=$user_telegram?>
    		</span>
    	<!-- <div class="row">
    		<div class="col-sm-4">
    			<b>Мой наставник:</b>
    		</div>
    		<div class="col-sm-4">
    			<?=$user_nastavnyk_data?>
    		</div>
    		<div class="col-sm-4">
    			<style type="text/css">
    				.nastavnyk-telegram {
						padding: 10px 20px 10px 0;
						border: 2px solid #2da5dc;
						border-radius: 10px;
						margin-left: 30px;
        			}
        			.nastavnyk-telegram img {
						position: relative;
						top: -2px;
						width: 50px;
						background-color: #2da5dc;
						margin-right: 10px;
						border-radius: 8px 0 0 8px;
    				}
    			</style>
    			<span class="nastavnyk-telegram">
					<img src="/assets/images/social/telegram.png" alt="Telegram"><?=$user_telegram?>
    			</span>
    		</div>
    	</div> -->
    	</h4>
    	<?if($user['partner_id'] == 2):?>
    	<p class="text-center mb-0 font-italic">За помощью обращайтесь в телеграм к <a href="https://t.me/<?=$user_nastavnyk['telegram']?>" target="_blank">Евгению</a></p>
    	<?endif;?>
	</div>	
</div>

<?if ($user['admin'] != 2):?>
<div class="card mb-2">
	<div class="card-body pt-2 pb-2">
		<h5 class='mt-0 mb-0 text-center'>Будьте в курсе последних новостей, добавляйтесь в наши группы:
			<!-- <a href="https://t.me/joinchat/DY75iUrI7q3paCjUdbyYhg" target="_blank">
				<img src="/assets/images/social/telegram.png" class="mr-3 ml-3" width="38">
			</a>
			<a href="https://invite.viber.com/?g2=AQAWaUOrt0AYOEmeSQv2OHs%2FAWdTMRk%2BnePb13OydKr2OgLLYs6ydCKvOV%2BLy9zQ" target="_blank">
				<img src="/assets/images/social/viber.png" class="mr-3" width="38">
			</a> -->
			<a href="https://t.me/joinchat/AAAAAEww568intibZHzkCg" target="_blank">
				<img src="/assets/images/social/telegram.png" class="mr-3 ml-3" width="38">
			</a>
			<a href="https://invite.viber.com/?g2=AQBMjWqbKnlExElhpyz1dkudEBOpTUIJQcNcCgUpGzHO5bHeGC3iZG5Z%2F7QtVrPq&lang=ru" target="_blank">
				<img src="/assets/images/social/viber.png" class="mr-3" width="38">
			</a>
			<a href="https://www.facebook.com/groups/onlinenaxodka/" target="_blank">
				<img src="/assets/images/social/fb.png" width="38">
			</a>
		</h5>
	</div>
</div>
<?endif;?>

<div class="card mb-2">
	<div class="card-body text-center">
		<a href="https://shop.safepal.io/products/safepal-hardware-wallet-s1-bitcoin-wallet?ref=evgeniytkachuk" target="_blank" rel="nofollow">
			<img src="https://static.tapfiliate.com/5d691081125a5.png?a=54948-abc73a&s=1486624-ff368a" border="0" class="w-100">
		</a>
	</div>
</div>

<div class="card mb-2">
	<div class="card-body">
		<div class="row justify-content-center">
			<div class="col-sm-8">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" src="//www.youtube.com/embed/JJonyFIidd8?rel=0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card mb-2">
	<div class="card-body">
		<h3 class="text-uppercase text-center font-weight-bold text-primary mt-3 mb-3"><span class="text-danger">Эксклюзивное интервью</span></h3>
		<p class="text-center">
			<a href="https://www.facebook.com/valentin.stanishevsky" target="_blank">Валентин Станишевкий</a> & <a href="https://www.facebook.com/EvgeniyTkachuk007" target="_blank">Евгений Ткачук</a>
		</p>
		<h4 class="text-uppercase text-center font-weight-bold text-primary mt-3 mb-3">Товарный бизнес 2020 или что продавать в карантин?</h4>
		<div class="row justify-content-center">
			<div class="col-sm-8">
				<div class="embed-responsive embed-responsive-16by9 mb-3">
					<iframe class="embed-responsive-item" src="//www.youtube.com/embed/CMnVqBW6zYw?rel=0" allowfullscreen></iframe>
				</div>
				<p class="text-center">
					<a href="/account/goods/internet_magazin_on/183923" class="btn btn-success">Заказать магазин</a>
					<a href="/account/goods/posuda_i_aksessuari" class="btn btn-warning">ТОП категория</a>
				</p>
			</div>
		</div>
	</div>
</div>


<style type="text/css">

#deadline-messadge,
.deadline-messadge{
  display: none;
  font-size: 24px;
  font-style: italic;
}

#deadline-messadge.visible,
.deadline-messadge.visible{
  display: block;
}

#clockdiv.hidden{
  display: none;
}
 
#clockdiv{
  font-family: sans-serif;
  color: #fff;
  display: inline-block;
  font-weight: 100;
  text-align: center;
  font-size: 28px;
}
 
#clockdiv > div{
  min-width: 64px;
  margin-bottom: 10px;
  padding: 5px;
  border-radius: 3px;
  background: #00a154;
  display: inline-block;
}
 
#clockdiv div > span{
  min-width: 54px;
  padding: 0 10px;
  border-radius: 3px;
  background: #044214;
  display: inline-block;
}
 
.smalltext{
  padding-top: 5px;
  font-size: 14px;
}

.cycle-webinar {
	margin: -44px 0 0 40px;
}

@media (max-width: 1199px) {
	.cycle-webinar {
		margin: -44px 0 0 5px;
	}
}

@media (max-width: 991px) {
	.cycle-webinar {
		margin: 0 0 20px 0;
	}
}

@media (max-width: 767px) {
	.cycle-webinar {
		margin: -44px 0 0 30px;
	}
}

@media (max-width: 576px) {
	.cycle-webinar {
		margin: 0 0 20px 0;
	}
}

</style>

<div id="accordion" class="mt-3">
	<div class="card mb-1">
		<div class="card-header" id="heading2">
			<h4 class="text-primary text-center text-uppercase font-weight-bold mt-3 mb-3 collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" style="cursor: pointer;"><span class="text-danger">Практический вебинар</span> Первые $2000 с 1000 грн на OLX за 21 день</h4>
		</div>
		<div id="collapse2" class="collapse show" aria-labelledby="heading2" data-parent="#accordion">
			<div class="card-body">
				<div class="row mb-5">
					<div class="col-sm-6">
						<img src="/assets/images/mac_notebook_olx.png" alt="Course OLX" class="img-fluid">
					</div>
					<div class="col-sm-6">
						<h3 class="text-center text-uppercase mb-0">Практический вебинар</h3>
						<h3 class="text-center text-uppercase font-weight-bold mb-4">Первые $2000 с 1000 грн на OLX за 21 день</h3>
						<h5 class="text-center mb-5">Постройте прибыльный товарный бизнес почти без вложений с пошаговой инструкцией</h5>
						<div class="text-center">
							<form method="POST" onsubmit="visitWebinarOLX(event, this)">
								<input type="hidden" name="visitwebinarolx" value="1">
								<button type="submit" class="btn btn-primary btn-lg text-uppercase">Посетить вебинар</button>
							</form>
						</div>
					</div>
				</div>
				<h3 class="text-center font-weight-bold">Этот <span class="text-primary">вебинар</span> в первую очередь <span class="text-primary">подходит</span> для тех, кто</h3>
				<img src="/assets/images/for_whom_course_olx.png" alt="For Whom Course OLX" class="img-fluid">
				<div class="text-center mt-5 mb-4">
					<form method="POST" onsubmit="visitWebinarOLX(event, this)">
						<input type="hidden" name="visitwebinarolx" value="1">
						<button type="submit" class="btn btn-primary btn-lg text-uppercase">Подключиться к вебинару</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>

<h3 class="text-uppercase text-center font-weight-bold text-primary mt-5">Как достичь результата <span class="text-danger">5000$</span> в месяц на OLX</h3>
<div class="row justify-content-center mt-3">
	<div class="col-sm-8">
		<div class="embed-responsive embed-responsive-16by9 mb-3">
			<iframe class="embed-responsive-item" src="//www.youtube.com/embed/GFLxHRsq7T4?rel=0" allowfullscreen></iframe>
		</div>
	</div>
</div>
<div class="row justify-content-center">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-6">
				<p class="text-center text-sm-left font-weight-bold">Хочешь узнать больше - ПОДПИШИСЬ</p>
			</div>
			<div class="col-sm-6 text-center text-sm-right">
				<script src="https://apis.google.com/js/platform.js"></script>
				<div class="g-ytsubscribe" data-channelid="UCaLJRk_SEfOgNaeh_oDi5WA" data-layout="default" data-count="hidden"></div>
			</div>
		</div>
	</div>
</div>
<h3 class="text-center mt-4 font-weight-bold text-info">Личная рекомендация от Евгения Ткачука</h3>
<div class="row justify-content-center mt-3">
	<div class="col-sm-8">
		<div class="card">
			<div class="card-header">
				<h4 class="text-center mb-0">Выполняй действия для достижения своей цели:</h4>
			</div>
			<div class="card-body">
				<div class="row justify-content-center">
					<div class="col-md-10 col-lg-8 col-xl-6">
						<ol class="ml-5">
							<li>Выбери товар из <a href="/account/goods/">каталога</a>.</li>
							<li>Размести товар на OLX.</li>
							<li>Получай результат.</li>
							<li>Работай со своей командой.</li>
							<li>Всегда учись!</li>
						</ol>
					</div>
				</div>
				<p class="text-center mb-0" style="font-size: 24px;">
					<i class="fa fa-comment"></i>
					<i class="fa fa-comment"></i>
					<i class="fa fa-comment"></i>
				</p>
				<p class="text-center">И помни только действия порождают Твой результат. С ув. Евгений Ткачук</p>
				<h4 class="text-center">Дерзай и будь <span class="text-uppercase">Лучшим!</span> Я в тебя верю!</h4>
			</div>
		</div>
	</div>
</div>
<p class="text-center mt-4">
	<a href="/account/goods/" class="btn btn-warning btn-lg">Перейти в каталог</a>
</p>

<!-- <div class="card mt-5 mb-5 pb-4">
	<div class="card-body">
		<div class="row">
			<div class="col-sm-6 mt-5">
				<img src="/assets/images/landdrop/land_logo.png" class="img-fluid" alt="Land logo">
				<h1 class="mt-4 font-weight-bold text-danger">Start Вебинар</h1>
				<img src="/assets/images/grafik.png" class="img-fluid" alt="Grafik">
			</div>
			<div class="col-sm-6 mt-5">
				<h3 class="text-center mb-4 font-weight-bold">Первый <span class="text-danger">Эксклюзивный Вебинар</span> компании ONLINE NAXODKA.</h3>
				<h4 class="text-center mb-3 font-weight-bold">На вебинаре вы узнаете:</h4>
				<p class="text-center">- как выйти на доход в первые месяц роботы с платформой и многое другое</p>
				<h5 class="text-center mb-4">Спикер: <b>Дмитрий Щедрин</b> - <br>сооснователь платформы Online Naxodka</h5>
				<div class="text-center mb-3">
					<div class="deadline-messadge visible">
						Запись вебинара!
					</div>
				</div>
				<p class="text-center">
					<a href="https://www.youtube.com/watch?v=xbdEQWiE2fU" class="btn btn-warning btn-lg pl-5 pr-5 font-weight-bold" target="_blank">Посетить</a>
				</p>
			</div>
		</div>
	</div>
</div> -->

<!-- <div class="card alert-warning">
	<div class="card-body">
		<h2 class="text-center mb-4">Начни действовать и зарабатывай от <span class="text-danger font-weight-bold">370$</span> в месяц</h2>
		<div class="row text-center">
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header">Действие 1</div>
					<div class="card-body">
						Выбери 3 категории товара из <a href="/account/goods/" target="_blank">каталога</a>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header">Действие 2</div>
					<div class="card-body">
						Размести по 100 обьявлений на <a href="/assets/files/pdf/instruction-for-a-beginner.pdf" target="_blank">OLX.ua</a> на каждую категорию товара
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header">Действие 3</div>
					<div class="card-body">
						Зафиксируй каждое объявление на <a href="/account/school_homework/" target="_blank">платформе</a>
					</div>
				</div>
			</div>
		</div>
		<h5 class="text-center font-weight-bold mt-3 text-info">Жми кнопку</h5>
		<p class="text-center mb-0">
			<a href="/account/school_homework/" class="btn btn-warning btn-lg text-uppercase font-weight-bold" target="_blank">Домашние задание</a>
		</p>
	</div>
</div> -->

<script type="text/javascript">
function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}
 
function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector(".days");
  var hoursSpan = clock.querySelector(".hours");
  var minutesSpan = clock.querySelector(".minutes");
  var secondsSpan = clock.querySelector(".seconds");
 
  function updateClock() {
    var t = getTimeRemaining(endtime);
 
    if (t.total <= 0) {
      /*document.getElementById("clockdiv").className = "hidden";
      document.getElementById("deadline-messadge").className = "visible";*/
      clearInterval(timeinterval);
      var deadline = new Date(Date.parse(new Date()) + 60 * 60 * 24 * 7 * 1000);
      initializeClock('clockdiv', deadline);
      return true;
    }
 
    daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ("0" + t.hours).slice(-2);
    minutesSpan.innerHTML = ("0" + t.minutes).slice(-2);
    secondsSpan.innerHTML = ("0" + t.seconds).slice(-2);
  }
 
  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}
 
//var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000); // for endless timer
var deadline="June 11 2019 20:00:00 GMT+0300";
initializeClock('clockdiv', deadline);
</script>

<!-- <h1>ТОП товары</h1>
<h1>Новинки</h1>
<h1>Рейтинг дропшиперов, менеджеров, супервайзеров</h1> -->

<!-- <img src="/assets/images/tmp_orders/screen1.png" alt="screen1" class="w-100">
<img src="/assets/images/tmp_orders/screen2.png" alt="screen1" class="w-100">
<img src="/assets/images/tmp_orders/screen3.png" alt="screen1" class="w-100"> -->

<!-- <div class="modal fade" id="visitWebinarOLX">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Вебинар - "Практический курс OLX за 21 день"</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-user-o"></i>
								</span>
							</div>
							<input type="text" name="name" class="form-control form-control-lg" placeholder="Ваше имя" required>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-phone"></i>
								</span>
							</div>
							<input type="tel" name="phone" class="form-control form-control-lg" placeholder="Ваш телефон" required>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-envelope-o"></i>
								</span>
							</div>
							<input type="email" name="email" class="form-control form-control-lg" placeholder="Ваш E-mail" required>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary btn-lg text-uppercase">Посетить вебинар</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> -->

<div class="modal fade" id="howPayAnelkinKurs">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Как оплатить практический курс по продажам на OLX</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="embed-responsive embed-responsive-16by9 mb-3">
					<iframe class="embed-responsive-item" src="//www.youtube.com/embed/hegdqgvQDls?rel=0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="howPayAnelkinKurs">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Как оплатить практический курс по продажам на OLX</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="embed-responsive embed-responsive-16by9 mb-3">
					<iframe class="embed-responsive-item" src="//www.youtube.com/embed/hegdqgvQDls?rel=0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>