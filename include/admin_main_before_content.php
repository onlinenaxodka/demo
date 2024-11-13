<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

$alert_message = '';

$name_company = 'Online Naxodka';

$link = $_SERVER['PHP_SELF'];

switch ($link) {
	case '/admin/index.php':
		$title_browser = 'Панель приборов';
		$title_content = 'Панель приборов';
		$request_file_name = '';
		$main_menu_active[0] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/users.php':
		$title_browser = 'Пользователи';
		$title_content = 'Пользователи';
		$request_file_name = 'admin_users';
		$main_menu_active[1] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/subscribers.php':
		$title_browser = 'Лиды';
		$title_content = 'Лиды';
		$request_file_name = '';
		$main_menu_active[2] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/goods.php':
		$title_browser = 'Каталог товаров';
		$title_content = 'Каталог товаров';
		$request_file_name = 'admin_goods';
		$main_menu_active[3] = 'active';
		$linkstyle = '<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script><script>$(document).ready(function(){$(".summernote").summernote({height: 200});});</script>';
		break;
	case '/admin/goods_catalog.php':
		$title_browser = 'Категории товаров';
		$title_content = 'Категории товаров';
		$request_file_name = 'admin_goods_catalog';
		$main_menu_active[3] = 'active';
		$linkstyle = '<link rel="stylesheet" href="/assets/styles/jquery.treegrid.css">';
		$jquerylib = '<script type="text/javascript" src="/assets/js/jquery.treegrid.min.js"></script><script type="text/javascript" src="/assets/js/jquery.treegrid.materialicons.js"></script>';
		break;
	case '/admin/goods_upload.php':
		$title_browser = 'Выгрузка товаров';
		$title_content = 'Выгрузка товаров';
		$request_file_name = 'admin_goods_upload';
		$main_menu_active[3] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	/*case '/admin/goods_parser.php':
		$title_browser = 'Спарсенные товары';
		$title_content = 'Спарсенные товары';
		$request_file_name = 'admin_goods_parser';
		$main_menu_active[3] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;*/
	case '/admin/market_tools.php':
		$title_browser = 'Инструменты арбитража';
		$title_content = 'Инструменты арбитража';
		$request_file_name = 'admin_market_tools';
		$main_menu_active[3] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/orders.php':
		$title_browser = 'Заказы';
		$title_content = 'Заказы';
		$request_file_name = 'admin_orders';
		$main_menu_active[4] = 'active';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script>';
		break;
	case '/admin/orders_payment.php':
		$title_browser = 'Расчет с поставщиком';
		$title_content = 'Расчет с поставщиком';
		$request_file_name = 'admin_orders_payment';
		$main_menu_active[4] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/add_funds.php':
		$title_browser = 'Пополнение баланса';
		$title_content = 'Пополнение баланса';
		$request_file_name = 'admin_add_funds';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/withdrawal.php':
		$title_browser = 'Вывод средств';
		$title_content = 'Вывод средств';
		$request_file_name = 'admin_withdrawal';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/transactions.php':
		$title_browser = 'История операций';
		$title_content = 'История операций';
		$request_file_name = 'admin_transactions';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/bookkeeping.php':
		$title_browser = 'Бухгалтерия';
		$title_content = 'Бухгалтерия';
		$request_file_name = 'admin_bookkeeping';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/invest_project.php':
		$title_browser = 'Инвестиционные проекты';
		$title_content = 'Инвестиционные проекты';
		$request_file_name = 'admin_invest_project';
		$main_menu_active[5] = 'active';
		$linkstyle = '<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">';		
		$jquerylib = '<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script><script>$(document).ready(function(){$(".summernote").summernote({height: 200});});</script>';
		break;
	case '/admin/marketing.php':
		$title_browser = 'Маркетинг';
		$title_content = 'Маркетинг';
		$request_file_name = 'admin_marketing';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	/*case '/admin/marketing_mlm.php':
		$title_browser = 'Маркетинг МЛМ';
		$title_content = 'Маркетинг МЛМ';
		$request_file_name = 'admin_marketing_mlm';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;*/
	case '/admin/for_marketer.php':
		$title_browser = 'Для маркетолога';
		$title_content = 'Для маркетолога';
		$request_file_name = '';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/support.php':
		$title_browser = 'Поддержка';
		$title_content = 'Поддержка';
		$request_file_name = 'admin_support';
		$main_menu_active[6] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/school.php':
		$title_browser = 'Школа';
		$title_content = 'Школа';
		$request_file_name = '';
		$main_menu_active[7] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/logs.php':
		$title_browser = 'История логов';
		$title_content = 'История логов';
		$request_file_name = '';
		$main_menu_active[8] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		break;
	case '/admin/information_faq.php':
		$title_browser = 'Вопросы и ответы';
		$title_content = 'Вопросы и ответы';
		$request_file_name = 'admin_information_faq';
		$main_menu_active[9] = 'active';
		$linkstyle = '<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">';
		$jquerylib = '<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script><script>$(document).ready(function(){$(".summernote").summernote({height: 200});});</script>';
		break;
	case '/admin/information_db_providers.php':
		$title_browser = 'База поставщиков';
		$title_content = 'База поставщиков';
		$request_file_name = 'admin_information_db_providers';
		$main_menu_active[9] = 'active';
		$linkstyle = '<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">';
		$jquerylib = '<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script><script>$(document).ready(function(){$(".summernote").summernote({height: 200});});</script>';
		break;
}

include_once __DIR__ . '/../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user = mysqli_fetch_assoc($query);
	$user_id = $user['id'];

	if ($request_file_name != '') include_once __DIR__ . '/../include/requests/'.$request_file_name.'.php';

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) != 0) {

		if ($user['blocked'] == 1) {

			header('Location: /blocked/');
    		exit;

		}
		
		if ($user['admin'] == 0) {

			header('Location: /account/');
    		exit;

		}

		if ($user['admin'] == 1 && in_array($user_id, [375, 12936, 12981]) && !in_array($link, ['/admin/goods.php', '/admin/goods_catalog.php'])) {
			header('Location: /admin/goods/');
    		exit;
		}

		if ($user_id != 5672) {

			if ($user['admin'] == 2 and $link != '/admin/goods.php') {

				header('Location: /admin/goods/');
	    		exit;

			}

		} else {

			if ($user['admin'] == 2 and $link != '/admin/goods.php' and $link != '/admin/orders.php' and $link != '/admin/support.php' and $link != '/admin/school.php') {

				header('Location: /admin/goods/');
    			exit;

			}

		}

	} else {

		header('Location: /logout/');
    	exit;

	}

} else {

	header('Location: /');
    exit;

}


$sql_count_message = "SELECT `id` FROM `support_subjects` WHERE `status`=0 AND `answer`=0";
$query_count_message = mysqli_query($db, $sql_count_message) or die(mysqli_error());
$count_new_message = mysqli_num_rows($query_count_message);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title_browser?>: Админ панель - Online Naxodka</title>
	<meta name="description" content="Админ панель сервиса Online Naxodka">
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap.min.css">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<?=$linkstyle?>
	<link rel="stylesheet" type="text/css" href="/assets/styles/admin.css">
</head>
<body>
	<button id="top"><i class="material-icons">expand_less</i></button>
	<header class="navbar navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="/admin/">
			<img src="/assets/images/core/logo.png" width="30" height="30" class="d-inline-block align-top" alt="Logo">
			Админ панель
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarMenu">
			<ul class="navbar-nav mr-auto">
				<?if ($user['admin'] == 1):?>
				<li class="nav-item <?=$main_menu_active[0]?>">
					<a class="nav-link" href="/admin/">Панель приборов <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item <?=$main_menu_active[1]?>">
					<a class="nav-link" href="/admin/users/">Пользователи</a>
				</li>
				<!-- <li class="nav-item <?=$main_menu_active[2]?>">
					<a class="nav-link" href="/admin/subscribers/">Лиды</a>
				</li> -->
				<li class="nav-item dropdown <?=$main_menu_active[3]?>">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkGoods" data-toggle="dropdown">Товары</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkGoods">
						<a class="dropdown-item" href="/admin/goods_catalog/">Категории товаров</a>
						<a class="dropdown-item" href="/admin/goods/">Каталог товаров</a>
						<a class="dropdown-item" href="/admin/goods_upload/">Выгрузка товаров</a>
						<a class="dropdown-item" href="/admin/market_tools/">Инструменты арбитража</a>
						<!--<a class="dropdown-item" href="/admin/goods_parser/">Спарсенные товары</a>-->
					</div>
				</li>
				<li class="nav-item dropdown <?=$main_menu_active[4]?>">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkOrders" data-toggle="dropdown">Заказы</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkOrders">
						<a class="dropdown-item" href="/admin/orders/">Заказы</a>
						<a class="dropdown-item" href="/admin/orders_payment/">Расчет с поставщиком</a>
					</div>
				</li>
				<li class="nav-item dropdown <?=$main_menu_active[5]?>">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkGoods" data-toggle="dropdown">Финансы</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkGoods">
						<a class="dropdown-item" href="/admin/add_funds/">Пополнение баланса</a>
						<a class="dropdown-item" href="/admin/withdrawal/">Вывод средств</a>
						<a class="dropdown-item" href="/admin/transactions/">История операций</a>
						<a class="dropdown-item" href="/admin/bookkeeping/">Бухгалтерия</a>
						<a class="dropdown-item" href="/admin/invest_project/">Инвестиционные проекты</a>
						<a class="dropdown-item" href="/admin/marketing/">Маркетинг</a>
						<!--<a class="dropdown-item" href="/admin/marketing_mlm/">Маркетинг МЛМ</a>-->
						<a class="dropdown-item" href="/admin/for_marketer/">Для маркетолога</a>
					</div>
				</li>
				<li class="nav-item <?=$main_menu_active[6]?>">
					<a class="nav-link" href="/admin/support/">Поддержка<span class="support-count-new-message"><?=$count_new_message?></span></a>
				</li>
				<!-- <li class="nav-item <?=$main_menu_active[7]?>">
					<a class="nav-link" href="/admin/school/">Школа</a>
				</li> -->
				<li class="nav-item dropdown <?=$main_menu_active[9]?>">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkInformation" data-toggle="dropdown">Информация</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkInformation">
						<a class="dropdown-item" href="/admin/information_db_providers/">База поставщиков</a>
						<a class="dropdown-item" href="/admin/information_faq/">Вопросы и ответы</a>
						<a class="dropdown-item" href="#/admin/information_privacy_policy/"><s>Политика конфиденциальности</s></a>
						<a class="dropdown-item" href="#/admin/information_terms/"><s>Правила использования</s></a>
						<a class="dropdown-item" href="#/admin/information_provider_terms/"><s>Правила для поставщика</s></a>
					</div>
				</li>
				<li class="nav-item <?=$main_menu_active[8]?>">
					<a class="nav-link" href="/admin/logs/">История логов</a>
				</li>
				<?endif;?>
				<?if ($user['admin'] == 2):?>
					<li class="nav-item <?=$main_menu_active[3]?>">
						<a class="nav-link" href="/admin/goods/">Список товаров</a>
					</li>
					<?if($user_id == 0):?>
					<li class="nav-item <?=$main_menu_active[4]?>">
						<a class="nav-link" href="/admin/orders/">Заказы</a>
					</li>
					<li class="nav-item <?=$main_menu_active[6]?>">
						<a class="nav-link" href="/admin/support/">Поддержка<span class="support-count-new-message"><?=$count_new_message?></span></a>
					</li>
					<li class="nav-item <?=$main_menu_active[7]?>">
						<a class="nav-link" href="/admin/school/">Школа</a>
					</li>
					<?endif;?>
				<?endif;?>
			</ul>
			<a href="/account/goods/" target="_blank" class="btn btn-danger">Открыть каталог товаров</a>
			<a href="/account/goods/" class="btn user-block" title="Выйти">
					<?	
						$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo" height="40">';
						$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
						for ($i = 0; $i < count($type_img); $i++) { 
							$img_name = __DIR__ . '/../data/images/users/user'.$user_id.'.'.$type_img[$i];
							if (file_exists($img_name)) {
								$img_photo = '<img src="/data/images/users/user'.$user_id.'.'.$type_img[$i].'" alt="User Photo" height="40">';
							}
						}
						echo $img_photo . $user['name'];
					?>
			</a>
		</div>
	</header>
	<section class="container-fluid">
		<div class="card content">
			<h2 class="title-page text-sm-left">
				<div class="row">
					<div class="col-sm-4">
						<?=$title_content?>
					</div>
					<div class="col-sm-8 text-right">
<?/*if ($link == '/admin/index.php'):?>
<span style="height: 48px; line-height: 48px;float: left;margin-right: 30px;">Собрание</span>
<div class="card d-inline-block pt-1 pb-1 pl-2 pr-2 mr-3 float-left">
	<b>Пятница: <span class="text-danger">20:00</span></b>
</div>
<style type="text/css">

#deadline-messadge{
  display: none;
  font-size: 24px;
  font-style: italic;
}

#deadline-messadge.visible{
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
  font-size: 30px;
}
 
#clockdiv > div{
  min-width: 84px;
  padding: 5px;
  border-radius: 3px;
  background: #00a154;
  display: inline-block;
}
 
#clockdiv div > span{
  min-width: 64px;
  margin-right: 5px;
  padding: 0 15px;
  border-radius: 3px;
  background: #044214;
  display: inline-block;
  float: left;
}
 
.smalltext{
  display: inline-block;
  height: 36px;
  line-height: 36px;
  font-size: 16px;
  float: left;
}

</style>
<div class="d-inline-block text-center float-left">
	<div id="deadline-messadge">
		Собрание началось, заходи в скайп!
	</div>
	<div id="clockdiv">
		<div>
			<span class="days"></span>
			<div class="smalltext">Дней</div>
		</div>
		<div>
			<span class="hours"></span>
			<div class="smalltext">Часов</div>
		</div>
		<div>
			<span class="minutes"></span>
			<div class="smalltext">Минут</div>
		</div>
		<div>
			<span class="seconds"></span>
			<div class="smalltext">Секунд</div>
		</div>
	</div>
</div>
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
      //document.getElementById("clockdiv").className = "hidden";
      //document.getElementById("deadline-messadge").className = "visible";
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
var deadline="May 24 2019 20:00:00 GMT+0300";

initializeClock('clockdiv', deadline);
</script>
<?endif;*/?>						
					</div>
				</div>
			</h2>