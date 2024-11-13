<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_category.xlsx';

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

	for ($iRow = 21001; $iRow <= 28000; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $oCell->getValue();
		$category_parent_name = test_request($oCell->getValue());

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $oCell->getValue();
		$category_name = test_request($oCell->getValue());

		$oCell = $oCells->get('C'.$iRow);
		if ($oCell) $oCell->getValue();
		$vendor_code = test_request($oCell->getValue());

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql_goods_asociate_category = "SELECT * FROM `goods_asociate_category` WHERE `parent_name`=\"{$category_parent_name}\" AND `name`=\"{$category_name}\" AND `user_id`=5856 LIMIT 1";
			$query_goods_asociate_category = mysqli_query($db, $sql_goods_asociate_category) or die(mysqli_error($db));
			$goods_asociate_category = mysqli_fetch_assoc($query_goods_asociate_category);
			$count_goods_asociate_category = mysqli_num_rows($query_goods_asociate_category);

			if ($count_goods_asociate_category > 0) {

				$category = $goods_asociate_category['linkname'];

				$sql = "UPDATE `goods` SET `category`='{$category}', `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			//$n++;

		}

	}

}

//echo $n.'<br>';

?>