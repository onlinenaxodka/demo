<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../include/lang_files.php';

?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=$title_browser_404?> - <?=$name_company?></title>

    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <link rel="shortcut icon" type="image/x-icon" href="//<?=$_SERVER['SERVER_NAME']?>/favicon.ico">
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
    background: #20174C url('//<?=$_SERVER['SERVER_NAME']?>/assets/images/core/bg.jpg') no-repeat;
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

.message h1.title404 {

    font-size: 11rem;

}

.message p {
    font-size: 1.12rem;
}

.message a {
    text-decoration: none;
    color: #6cb2e3;
}

.message a:hover {
    text-decoration: underline;
}

@media (max-width: 320px) {

    section {
        padding: 10px;
    }

    .message {
        width: 100%;
        padding: 32px 10px;
    }

    .message h1.title404 {
        font-size: 6rem;
    }

}

@media (min-width: 320px) and (max-width: 579px) {

    .message {
        width: 100%;
        padding: 32px 20px;
    }

    .message h1.title404 {
        font-size: 8rem;
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
    <? include_once __DIR__ . '/../../include/google_analytics.php'; ?>
    <section>
        <div class="message">
            <img src="//<?=$_SERVER['SERVER_NAME']?>/assets/images/core/logo.png" width="100">
            <p><?=$name_company?></p><br>
            <h1 class="title404">404</h1>
    		<h1><?=$page_no_found?></h1>
            <br>
            <p>
                <a href="/"><?=$back_to_home?></a> 
            </p>
    	</div>
    </section>
</body>
</html>