<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/config.php';

if (!isset($_SESSION['user'])) {

	header('Location: /');
	exit;

} else {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

}

$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$user = mysqli_fetch_assoc($query);

if ($user['blocked'] == 0) {

	header('Location: ' . $main_page);
	exit;

}

include_once __DIR__ . '/include/lang_files.php';

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=$title_browser_blocked?> - <?=$name_company?></title>

    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<style type="text/css">

@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=cyrillic);

* {
	margin: 0;
	padding: 0;
	-webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

body {
	font-family: "Open Sans", Tahoma, sans-serif, Arial;
    background: #20174C url('/assets/images/core/bg.jpg') no-repeat;
    background-size: cover;
    background-attachment: fixed;
}

section {
    width: 100%;
    height: 100%;
    padding: 15px;
    overflow: auto;
}

.message {
	width: 40%;
    margin: 0 auto;
    border-radius: 5px;
    background: rgba(0,0,0,.55);
    color: #fff;
    padding: 32px 30px;
    text-align: center;
}

.message h1, .message p {
	margin-bottom: 20px;
}

.message h1 {
	font-size: 1.8rem;
	font-weight: normal;
}

.message p {
	font-size: 1.12rem;
	text-align: justify;
}

@media (max-width: 320px) {

	section {
		padding: 10px;
	}

	.message {
		width: 100%;
		padding: 32px 10px;
	}
}

@media (min-width: 320px) and (max-width: 579px) {
	.message {
		width: 100%;
		padding: 32px 20px;
	}
}

@media (min-width: 580px) and (max-width: 767px) {
	.message {
		width: 80%;
		padding: 32px 20px;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.message {
		width: 64%;
		padding: 32px 30px;
	}
}

</style>
</head>
<body>
	<? include_once __DIR__ . '/include/google_analytics.php'; ?>
    <section>
        <div class="message">
            <img src="/assets/images/core/logo.png" width="100">
            <p style="text-align: center;"><?=$name_company?></p><br>
    		<h1><?=$blocked_page_content_title?></h1>
    		<p style="text-align: center;"><?=$user['mail']?></p>
            <br>
            <p><?=$blocked_page_content_text_1?></p>
            <p><?=$blocked_page_content_text_2?></p>
            <p><?=$blocked_page_content_text_3?></p>
            <p><?=$blocked_page_content_text_4?> support@<?=$_SERVER['SERVER_NAME']?></p>
    	</div>
    </section>
</body>
</html>