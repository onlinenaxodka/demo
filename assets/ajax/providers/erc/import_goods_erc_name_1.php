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

	for ($iRow = 2; $iRow <= 7000; $iRow++) {

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

			$sql = "INSERT INTO `goods` SET `user_id`=5856, `vendor_code`='{$vendor_code}', `name`='{$name}', `updated`='{$current_date}', `created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

	}

}

//echo $n.'<br>';

?>