<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'demonaxodka';

$db = mysqli_connect($host, $user, $password, $dbname);

if (!$db) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

mysqli_query($db,"set character_set_client   ='utf8'");
mysqli_query($db,"set character_set_results  ='utf8'");
mysqli_query($db,"set collation_connection   ='utf8_general_ci'");

function slashes(&$el) {
	if (is_array($el)) foreach($el as $k=>$v) slashes($el[$k]);
	else $el = stripslashes($el); 
}

if (ini_get('magic_quotes_gpc')) {
	slashes($_GET);
	slashes($_POST);    
	slashes($_COOKIE);
}

function test_request($data) {
	$data = strip_tags($data);
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$email_username = 'noreply@demonaxodka.pp.ua';
$email_password = 'password';

function sendMail($email, $subject, $message, $from, $server_protocole) {

    global $email_username, $email_password;

	require_once __DIR__ . '/include/PHPMailer/sendMail.php';

	return $status_mail;

}

function adopt($text) {
	return '=?UTF-8?B?'.Base64_encode($text).'?=';
}

if (isset($_SERVER['HTTPS'])) $server_protocole = 'https';
else $server_protocole = 'http';

date_default_timezone_set('Europe/Kiev');

$main_page = '/account/goods/';
if (!empty($_SESSION['main_page']['url'])) $main_page = $_SESSION['main_page']['url'];
$partner_id = 1;
$ip = $_SERVER['REMOTE_ADDR'];
$current_date = date('Y-m-d H:i:s');

//reCaptcha / demonaxodka.pp.ua.local - is available for local
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_secret = 'KEY';
$recaptcha_public = 'KEY';

$email_for_notify = 'online.naxodka@gmail.com';

?>