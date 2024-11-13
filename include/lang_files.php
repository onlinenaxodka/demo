<?php

$name_company = 'Online Naxodka';

$lang_files = array(
	"uk" => "ukrainian",
	"ru" => "russian"
);

$lang_files_name = array(
	"uk" => "Українська",
	"ru" => "Русский"
);




if (!isset($_SESSION['user'])) {

	if (empty($_SESSION['lang'])) {

	    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

	    $_SESSION['lang'] = $lang;

	    if (!in_array($lang, array_keys($lang_files))) {

		    $_SESSION['lang'] = 'uk';
		    $lang = 'uk';

		}

	} else {

	    $lang = $_SESSION['lang'];

	}

} else {

	if (empty($_SESSION['lang'])) {

		if (empty($user['lang'])) {

			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

		    $_SESSION['lang'] = $lang;

			if (!in_array($lang, array_keys($lang_files))) {

				$_SESSION['lang'] = 'uk';
				$lang = 'uk';

			}

		} else {

			$_SESSION['lang'] = $user['lang'];
	    	$lang = $user['lang'];

		}

	} else {

	    $lang = $_SESSION['lang'];

	}

}



include_once __DIR__ . '/lang_files/' . $lang_files[$lang] . '.php';

switch ($lang) {
	/*case 'en':
		$date_format = 'm/d/Y';
		$datetime_format = 'm/d/Y H:i';
		$parametrs_datepicker = array('7', 'mm/dd/yy', 'mm/dd/yyyy');
		$preg_match_rule_date = '/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/';
		break;*/
	case 'uk':
	case 'ru':
		$date_format = 'd.m.Y';
		$datetime_format = 'd.m.Y H:i';
		$parametrs_datepicker = array('1', 'dd.mm.yy', 'dd.mm.yyyy');
		$preg_match_rule_date = '/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/';
		break;
	default:
		$date_format = 'Y-m-d';
		$datetime_format = 'Y-m-d H:i';
		$parametrs_datepicker = array('1', 'yy-mm-dd', 'yyyy-mm-dd');
		$preg_match_rule_date = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
		break;
}

?>