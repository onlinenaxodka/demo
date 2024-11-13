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
include_once __DIR__ . '/include/requests/remind.php';

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
	<meta charset="utf-8">

	<title><?=$title_browser_remind?> - <?=$name_company?></title>

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
							<img class="logo" src="/assets/images/core/logo.png" title="<?=$back_to_home?>" style="position:relative;display:inline-block;">
						</a>
					</p>
					<h1 class="text-center pb-2 mb-3"><?=$title_browser_remind?></h1>
					<?=$alert_message?>
					
<?

	if (!empty($_GET['sum']) and !empty($_GET['hash'])) {

		$user_id = (isset($_GET['sum'])) ? mysqli_real_escape_string($db, $_GET['sum']) : '';
        $key = (isset($_GET['hash'])) ? mysqli_real_escape_string($db, $_GET['hash']) : '';

		$user_id = test_request($user_id);
		$key = test_request($key);

		if ((int)$user_id > 0 and preg_match("/^[a-zA-Z0-9]{64}$/",$key)) {

			$sql = "SELECT `id`, `key`, `activated` FROM `users` WHERE `id`='{$user_id}' AND `key`='{$key}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) == 1) {

				if ($user['activated'] == 0) {

					$user_id = $user['id'];
					$sql = "UPDATE `users` SET `activated`=1, `updated`='{$current_date}' WHERE `id`={$user_id}";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
					

				}

?>

				<form action="/remind/?sum=<?=$_GET['sum']?>&hash=<?=$_GET['hash']?>" method="POST">
					<div class="row justify-content-center">
						<div class="col-sm-10">
							<div class="form-group">
	      						<label for="passwordNew"><?=$remind_page_text_label[0]?></label>
								<input type="password" class="form-control" id="passwordNew" name="new_password" placeholder="<?=$remind_page_text_input[0]?>" required>
	      					</div>
		      				<div class="form-group">
		      					<label for="passwordRepeat"><?=$remind_page_text_label[1]?></label>
		      					<input type="password" class="form-control" id="passwordRepeat" name="again_new_password" placeholder="<?=$remind_page_text_input[1]?>" required>
		      				</div>
		      			</div>
					</div>
		      		<div class="form-group row justify-content-center">
		      			<div class="col-sm-8">
		      				<div class="g-recaptcha" data-sitekey="<?=$recaptcha_public?>"></div>
		      			</div>
		      		</div>
					<div class="form-group row pt-3">
						<div class="col-sm-1"></div>
						<div class="col-sm-6 pt-2">
				      		<a href="/"><?=$back_to_home?></a>
				      	</div>
				      	<div class="col-sm-4 pt-1">
				      		<button type="submit" class="btn btn-primary btn-block"><?=$word_save?></button>
				      	</div>
				      	<div class="col-sm-1"></div>
					</div>
						
				</form>

<?				
			
			} else {

				echo '<div class="modal-body text-sm-center">'.$remind_page_text_no_result.'</div>';

			}

		} else {

			echo '<div class="modal-body text-sm-center">'.$remind_page_text_no_result.'</div>';

		}

	} else {
		
?>

				<form action="/remind/" method="POST">
					
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-10">
	      						<input type="email" class="form-control" id="email" name="email" placeholder="<?=$remind_page_text_input_email?>" required>
	      						<p class="form-text text-muted"><?=$remind_page_text_email?></p>
	      					</div>
	      				</div>
	      				<div class="form-group row justify-content-center">
	      					<div class="col-sm-8">
	      						<div class="g-recaptcha" data-sitekey="<?=$recaptcha_public?>"></div>
	      					</div>
	      				</div>
					
					<div class="form-group row pt-3">
						<div class="col-sm-1"></div>
						<div class="col-sm-6 pt-2">
		      				<a href="/"><?=$back_to_home?></a>
		      			</div>
		      			<div class="col-sm-4 pt-1">
		      				<button type="submit" class="btn btn-primary btn-block"><?=$word_send?></button>
		      			</div>
		      			<div class="col-sm-1"></div>
					</div>
				</form>

<?

	}

?>
	      			
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
</body>
</html>