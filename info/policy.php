<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../include/lang_files.php';

?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
	<meta charset="utf-8">

	<title><?=$title_browser_privacy_policy?> - <?=$name_company?></title>

	<meta name="description" content="<?=$home_page_description?>">
    <meta name="robots" content="INDEX, FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap.min.css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style type="text/css">
    	body {
			background: #20174C url('/assets/images/core/bg.jpg') no-repeat;
		    background-size: cover;
		    background-attachment: fixed;
		    padding:10px;
		}
		.container {
			margin-top: 0;
			margin-bottom: 0;
			padding-top: 30px;
			padding-bottom: 30px;
		}
		@media (min-width: 576px) {
			.container {
				margin-top: 20px;
				margin-bottom: 20px;
			}
		}
    </style>
</head>
<body>
	<section class="container card">
		<div class="card-body">
			<h2 class="text-center mb-5"><?=$title_browser_privacy_policy?></h2>

			<?include_once __DIR__ . '/../assets/lang/policy_'.$lang.'.html';?>
			
			<p class="text-center mt-5">
				<a href="/" class="btn btn-primary"><?=$back_to_home;?></a>
			</p>
		</div>
	</section>
</body>
</html>