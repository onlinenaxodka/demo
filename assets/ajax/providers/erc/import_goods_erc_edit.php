<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_name.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($excel_file)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($excel_file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
	$highestRow = intval($highestRow);

	//$n = 0;

	$iteration_count = $highestRow / 10000;
	if (is_int($iteration_count) === false) $iteration_count = intval($iteration_count) + 1;

	$iteration_name = file_get_contents("../../files/import_providers/erc/iteration_name.txt");
	$iteration_name = intval($iteration_name);

	$row_start = $iteration_name * 10000 + 2;
	$row_finish = $row_start + 10000;

	if ($iteration_name <= $iteration_count) {

	for ($iRow = ; $iRow < $highestRow; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $oCell->getValue();
		$name = test_request($oCell->getValue());
		$name_arr['uk'] = $name;
		$name_arr['ru'] = $name;
		$name = json_encode($name_arr, JSON_UNESCAPED_UNICODE);
		$name = str_replace("'", "\'", $name);

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $oCell->getValue();
		$vendor_code = test_request($oCell->getValue());
		$vendor_code = floatval($vendor_code);
		$vendor_code = number_format($vendor_code, 2, '.', '');

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql = "UPDATE `goods` SET `name`='{$name}', `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//$n++;

		} else {

			$sql = "INSERT INTO `goods` SET `user_id`=5856, `name`='{$name}', `updated`='{$current_date}', `created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

	}

	$iteration_name++;

	file_put_contents("../../files/import_providers/erc/iteration_name.txt", $iteration_name);

	}

}

//echo $n.'<br>';

?>