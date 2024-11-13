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

		}

	} else {

		header('Location: /');
    	exit;

	}

}

include_once __DIR__ . '/include/lang_files.php';
include_once __DIR__ . '/include/requests/login.php';

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
	<meta charset="utf-8">

	<title><?=$title_browser_login?> - <?=$name_company?></title>

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
	<div id="form" class="modal fade">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<p class="text-center">
						<a href="/">
							<img class="logo" src="/assets/images/core/logo.png" title="<?=$back_to_home?>">
						</a>
					</p>
					<h1 class="text-center pb-2 mb-3"><?=$title_browser_login?></h1>
					<?=$alert_message?>
					<?/*
					<div class="ulogin" id="uLogin760eb6a0" data-ulogin="display=panel;fields=first_name,last_name,email,phone;providers=facebook,google;redirect_uri="></div>
					<p class="text-center"><?=$word_or?></p>
					*/?>
					<form method="POST">
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8">
	      						<!-- <label for="inputEmailLogin">E-mail</label> -->
	      						<input type="email" class="form-control" id="inputEmailLogin" name="email" placeholder="E-mail" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8">
	      						<!-- <label for="inputPasswordLogin"><?=$word_password?></label> -->
	      						<input type="password" class="form-control" id="inputPasswordLogin" name="password" placeholder="<?=$word_password?>" required>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8">
	      						<div class="g-recaptcha" data-sitekey="<?=$recaptcha_public?>"></div>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8 text-center">
	      						<button type="submit" class="btn btn-primary btn-block mb-3"><?=$word_login?></button>
	      						<a href="/remind/"><?=$login_page_btn_forgot_password?></a>
	      						<p class="mt-3"><?=$login_page_text_not_registered?> <a href="/register/"><?=$word_register?></a></p>
	      					</div>
	      					<!-- <div class="col-sm-1"></div>
	      					<div class="col-sm-5 pt-3">
	      						
	      					</div>
	      					<div class="col-sm-5" style="padding-top: 7px">
	      						
	      					</div>
	      					<div class="col-sm-1"></div> -->
	      				</div>
	      				
	      			</form>
				</div>
			</div>
		</div>
	</div>
	<script src="/assets/js/jquery-2.2.4.min.js"></script>
	<!-- <script src="/assets/js/tether.min.js"></script> -->
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/outside.js"></script>
	<script src='//www.google.com/recaptcha/api.js'></script>
	<?/*<script src="//ulogin.ru/js/ulogin.js"></script>*/?>
</body>
</html>