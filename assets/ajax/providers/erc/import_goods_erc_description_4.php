<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_description.xlsx';

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

	for ($iRow = 15001; $iRow <= 20000; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $oCell->getValue();
		$vendor_code = test_request($oCell->getValue());

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $description = $oCell->getValue();
		$description = test_request($description);
		$description = str_replace("'", "\'", $description);

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql_goods_description = "SELECT `id` FROM `goods_description` WHERE `goods_id`='{$goods_id}' LIMIT 1";
			$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
			$count_goods_description = mysqli_num_rows($query_goods_description);
			$goods_description = mysqli_fetch_assoc($query_goods_description);
			$goods_description_id = $goods_description['id'];

			if ($count_goods_description > 0) {

				if (!empty($description)) {

					$sql = "UPDATE `goods_description` SET `description`='{$description}',
															`updated`='{$current_date}' WHERE `id`='{$goods_description_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

			} else {

				if (!empty($description)) {

					$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
																`description`='{$description}',
																`lang`='uk',
																`updated`='{$current_date}',
																`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
																`description`='{$description}',
																`lang`='ru',
																`updated`='{$current_date}',
																`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

			}

			//$n++;

		}

	}

}

//echo $n.'<br>';

?>