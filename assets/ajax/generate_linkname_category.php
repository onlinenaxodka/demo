<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			$name_ru = (isset($_POST['name_category'])) ? mysqli_real_escape_string($db, $_POST['name_category']) : '';
			$name_ru = test_request($name_ru);
				
			$linkname = generate_linkname_category($db, '', $name_ru);

			echo $linkname;

		}

	}

}

function generate_linkname_category($db, $linkname, $namecategory) {

	$char=array(
		'а'=>'a',
		'б'=>'b',
		'в'=>'v',
		'г'=>'g',
		'ґ'=>'g',
		'д'=>'d',
		'е'=>'e',
		'є'=>'ie',
		'ё'=>'e',
		'ж'=>'zh',
		'з'=>'z',
		'и'=>'i',
		'і'=>'i',
		'ї'=>'i',
		'й'=>'y',
		'к'=>'k',
		'л'=>'l',
		'м'=>'m',
		'н'=>'n',
		'о'=>'o',
		'п'=>'p',
		'р'=>'r',
		'с'=>'s',
		'т'=>'t',
		'у'=>'u',
		'ф'=>'f',
		'х'=>'h',
		'ы'=>'i',
		'э'=>'e',
		'ц'=>'ts',
		'ч'=>'ch',
		'ш'=>'sh',
		'щ'=>'sch',
		'ь'=>'',
		'ю'=>'yu',
		'я'=>'ya',
		'a'=>'a',
		'b'=>'b',
		'c'=>'c',
		'd'=>'d',
		'e'=>'e',
		'f'=>'f',
		'g'=>'g',
		'h'=>'h',
		'i'=>'i',
		'j'=>'j',
		'k'=>'k',
		'l'=>'l',
		'm'=>'m',
		'n'=>'n',
		'o'=>'o',
		'p'=>'p',
		'q'=>'q',
		'r'=>'r',
		's'=>'s',
		't'=>'t',
		'u'=>'u',
		'v'=>'v',
		'w'=>'w',
		'x'=>'x',
		'y'=>'y',
		'z'=>'z',
		' '=>'_',
		'_'=>'_',
		'1'=>'1',
		'2'=>'2',
		'3'=>'3',
		'4'=>'4',
		'5'=>'5',
		'6'=>'6',
		'7'=>'7',
		'8'=>'8',
		'9'=>'9',
		'0'=>'0'
	);

	$namecategory = mb_strtolower($namecategory);
	$namecategory_arr = preg_split('//u', $namecategory, null, PREG_SPLIT_NO_EMPTY);

	if (empty($linkname)) {

		for ($i=0; $i < count($namecategory_arr); $i++) {
			
			if (!in_array($namecategory_arr[$i], array_keys($char))) {
				
				$namecategory_arr[$i] = '';

			} else {
				
				$namecategory_arr[$i] = $char[$namecategory_arr[$i]];

			}

			$linkname .= $namecategory_arr[$i];

		}

	}

	$sql = "SELECT `id` FROM `catalog` WHERE `linkname`='{$linkname}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	if (mysqli_num_rows($query) > 0) {

		$linkname = $linkname . '_' . rand(1000, 10000);

		$linkname = generate_linkname_category($db, $linkname, $namecategory);

	}

	return $linkname;

}