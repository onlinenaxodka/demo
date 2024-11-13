<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

$alert_message = '';

include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/lang_files.php';
include_once __DIR__ . '/links.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);
	$user_id = $user['id'];

	if ($request_file_name != '') include_once __DIR__ . '/requests/' . $request_file_name . '.php';

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) != 0) {

		if ($_SESSION['lang'] != $user['lang']) {

			$_SESSION['lang'] = $user['lang'];

		}

		if ($user['blocked'] == 1) {

			header('Location: /blocked/');
    		exit;

		}

		if ($user['activated'] == 0) {

			header('Location: /login/');
    		exit;

		}/* else {

			if ($user['terms'] == 0) {

				if ($link != '/info/policy.php') {

					header('Location: /info/policy/');
    				exit;

				}

			}

		}*/

		if ($link == '/account/index.php') {

			header('Location: /account/goods/');
    		exit;

		}

	} else {

		header('Location: /logout/');
    	exit;

	}

} else {

	header('Location: /');
    exit;

}

$sql_count_message = "SELECT `id` FROM `support_subjects` WHERE `status`=0 AND `answer`=1 AND `user_id`='{$user_id}'";
$query_count_message = mysqli_query($db, $sql_count_message) or die(mysqli_error());
$count_new_message = mysqli_num_rows($query_count_message);

$add_funds_start_modal = false;

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
	<meta charset="utf-8">

	<title><?=$title_browser?> - <?=$name_company?></title>

	<meta name="description" content="<?=$home_page_description?>">
    <meta name="robots" content="INDEX, FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap.min.css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <?=$linkstyle?>
    <link rel="stylesheet" type="text/css" href="/assets/styles/main.css?v=11042024">
    <?/*<script src="//code.jivosite.com/widget.js" data-jv-id="33QA54494E" async></script>*/?>
</head>
<body>
	<? include_once __DIR__ . '/stopwar.php'; ?>
	<!--[if lt IE 7]>
        <p>
            <?=$version_browser_no?>
        </p>
    <![endif]-->
    <? include_once __DIR__ . '/google_analytics.php'; ?>
	<button id="top"><i class="material-icons">expand_less</i></button>
	<? include_once __DIR__ . '/header.php'; ?>
	<section class="container">
		<!-- <div class="row mt-3">
			<div class="col-sm-4 mb-1">
				<a href="/account/faq/?nav=2" class="btn btn-success btn-block" style="white-space: normal;">Я поставщик</a>
			</div>
			<div class="col-sm-4 mb-1">
				<a href="/account/faq/?nav=3" class="btn btn-success btn-block" style="white-space: normal;">Я интернет магазин/продавец</a>
			</div>
			<div class="col-sm-4 mb-1">
				<a href="/account/faq/?nav=4" class="btn btn-success btn-block" style="white-space: normal;">Я инвестор</a>
			</div>
		</div> -->
		<!-- <div class="alert alert-primary mt-3" role="alert">
			Для корректного функционирования системы  Вам необходимо почистить кеш вашего браузера<br>
			* Инструкция НАЖМИТЕ одновременно комбинацию клавиш CTRL + SHIFT + DELETE<br>
			<span class="text-danger"><b>Внимание!</b> Состоялось массовое обновление системы, просьба перезагрузить все ссылки XML на своих магазинах.</span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute;top: 5px;right: 10px;">
				<span aria-hidden="true">&times;</span>
			</button>
		</div> -->
		<div class="card content">
			<h2 class="title-page text-center"><?=$title_content?></h2>
			<?if (in_array($link, [
				'/account/goods.php',
				'/account/support.php',
				'/account/wallet.php',
				'/account/partners_work.php',
			])):?>
			<nav class="breadcrumb">
			<?

				for ($i = 0; $i < count($breadcrumb['names']); $i++) {
					//if ($breadcrumb['links'][$i] == '/account/') $breadcrumb['links'][$i] = $breadcrumb['links'][$i] . $user['nickname'];
					if ($i != count($breadcrumb['names'])-1) echo '<a class="breadcrumb-item" href="' . $breadcrumb['links'][$i] . '">' . $breadcrumb['names'][$i] . '</a>';
					else echo '<span class="breadcrumb-item active">' . $breadcrumb['names'][$i] . '</span>';
				}

			?>
			</nav>
			<?endif;?>