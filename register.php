<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

$alert_message = '';

include_once __DIR__ . '/config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT `activated` FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) != 0) {

		if ($user['activated'] == 1) {

			header('Location: ' . $main_page);
    		exit;

		} else {

			header('Location: /login/');
    		exit;

		}

	} else {

		header('Location: /');
    	exit;

	}

}

include_once __DIR__ . '/include/lang_files.php';
include_once __DIR__ . '/include/requests/register.php';

if (isset($_GET['success'])) {

	if (empty($_SESSION['domen_mail'])) {

		header('Location: /register/');
		exit;

	}

}

if (isset($_GET) and !empty($_GET['partner'])) {

    $nickname = (isset($_GET['partner'])) ? mysqli_real_escape_string($db, $_GET['partner']) : '';
    $nickname = test_request($nickname);

    if (preg_match("/^[a-z0-9_-]{2,30}$/", $nickname)) {

        $sql = "SELECT `id` FROM `users` WHERE `nickname` = '{$nickname}' LIMIT 1";
        $query = mysqli_query($db, $sql) or die(mysqli_error());
        $partner_data = mysqli_fetch_assoc($query);
        $partner_data_id = $partner_data['id'];

        if (mysqli_num_rows($query) > 0) {

            $_SESSION['partner_id'] = $partner_data_id;

            $sql = "INSERT INTO `landdrop_statistic` SET `user_id`='{$partner_data_id}', `ip`='{$ip}', `created`='{$current_date}'";
            $query = mysqli_query($db, $sql) or die(mysqli_error());

        } else {

            $_SESSION['partner_id'] = 1;
            
        }
                
    } else {

        $_SESSION['partner_id'] = 1;

    }

}

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
	<meta charset="utf-8">

	<title><?=$title_browser_register?> - <?=$name_company?></title>

	<meta name="description" content="<?=$home_page_description?>">
    <meta name="robots" content="INDEX, FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap.min.css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/styles/outside.css">
</head>
<body>
	<? include_once __DIR__ . '/include/stopwar.php'; ?>
	<!--[if lt IE 7]>
        <p>
            <?=$version_browser_no?>
        </p>
    <![endif]-->
    <? include_once __DIR__ . '/include/google_analytics.php'; ?>

	<?if (!isset($_GET['success'])):?>

	<div id="form" class="modal fade">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<?if (isset($_GET['provider'])):?>
					<div class="row">
						<div class="col-sm-3">
							<p class="text-center">
								<a href="/">
									<img class="logo" src="/assets/images/core/logo.png" title="<?=$back_to_home?>">
								</a>
							</p>
						</div>
						<div class="col-sm-9">
							<p>Хотите предложить свой товар или услугу? <br>Даже без регистрации предварительно добавляйтесь в телеграм <a href="https://t.me/Evgeniy_Tkachuk" target="_blank">@Evgeniy_Tkachuk</a>, наш сотрудник вам поможет!</p>
						</div>
					</div>
					<?else:?>
						<p class="text-center">
							<a href="/">
								<img class="logo" src="/assets/images/core/logo.png" title="<?=$back_to_home?>">
							</a>
						</p>
					<?endif;?>
					<h1 class="text-center pb-2 mb-3"><?=$title_browser_register?></h1>
					<?=$alert_message?>
					<?/*
					<div class="ulogin" id="uLogin760eb6a0" data-ulogin="display=panel;fields=first_name,last_name,email,phone;providers=facebook,google;redirect_uri=<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/register/"></div>
					<p class="text-center"><?=$word_or?></p>
					*/?>
					<form method="POST" <?if(!empty($_SESSION['gtm']) and $_SESSION['gtm']=='google'):?>onsubmit="gtag('event', 'Подтверждено', {'event_category' : 'Регистрация'})"<?endif;?>>
						<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="text" class="form-control" id="inputName" name="name" placeholder="<?=$word_name?>*" autocomplete="off" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="text" class="form-control" id="inputSurname" name="surname" placeholder="<?=$word_surname?>" autocomplete="off">
	      					</div>
	      				</div>
						<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="tel" class="form-control" id="inputPhone" name="phone" placeholder="<?=$word_phone?>*" autocomplete="off" data-inputmask="'mask': '+389999999999'" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="email" class="form-control" id="inputEmail" name="email" placeholder="E-mail*" autocomplete="off" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="password" class="form-control" id="inputPassword" name="password" placeholder="<?=$word_password?>*" autocomplete="off" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8">
	      						<div class="g-recaptcha" data-sitekey="<?=$recaptcha_public?>"></div>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center mb-0">
	      					<div class="col-sm-10">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="inputSubscription" name="subscription" value="1" checked>
									<label class="custom-control-label text-justify" for="inputSubscription" style="line-height:1em"><?=$register_page_subscriber?> <?=$name_company?>.</label>
								</div>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="inputTerms" name="terms" value="1" required>
									<label class="custom-control-label text-justify" for="inputTerms" style="line-height:1em"><?=$register_page_input_police_1?> <a href="/info/policy/" target="_blank"><?=$register_page_input_police_2?></a> <?=$register_page_input_police_3?></label>
								</div>
	      					</div>
	      				</div>
	      				<div class="form-group row">
	      					<div class="col-sm-1"></div>
	      					<div class="col-sm-5 pb-3 mt-2">
	      						<a href="/login/"><?=$word_auth?></a>
	      					</div>
	      					<div class="col-sm-5">
	      						<button type="submit" class="btn btn-primary btn-block"><?=$word_register?></button>
	      					</div>
	      					<div class="col-sm-1"></div>
	      				</div>
	      			</form>
				</div>
			</div>
		</div>
	</div>

	<?else:?>
	<section>
		<div class="message">
			<br>
			<h2><?=$register_page_alert_message_success_1?></h2>
			<br>
			<h5><?=$register_page_alert_message_success_2?></h5>
			<p style="color:#dfb81c"><?=$activate_page_alert_message_success_2?> <b><?=htmlspecialchars($_SESSION['domen_mail'])?></b>. <?=$activate_page_alert_message_success_3?></p>
			<a class="btn btn_link" href="//<?=htmlspecialchars(substr($_SESSION['domen_mail'], strrpos($_SESSION['domen_mail'], '@')+1))?>" target="_blank"><?=$activate_page_alert_message_success_4?></a>
			<p><a href="/" style="color:#209e91"><?=$back_to_home?></a></p>
		</div>
	</section>
	<?endif;?>

	<script src="/assets/js/jquery-2.2.4.min.js"></script>
	<!-- <script src="/assets/js/tether.min.js"></script> -->
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/jquery.inputmask.bundle.min.js"></script>
	<script src="/assets/js/outside.js"></script>
	<script src='//www.google.com/recaptcha/api.js'></script>
	<?/*<script src="//ulogin.ru/js/ulogin.js"></script>*/?>
</body>
</html>