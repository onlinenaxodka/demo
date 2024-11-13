<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_stock.xlsx';

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
		$vendor_code = test_request($oCell->getValue());

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $stock_quantity = $oCell->getValue();
		$stock_quantity_pre = intval($stock_quantity);

		if ($stock_quantity_pre == 0) {
			if ($stock_quantity != '0') $stock_quantity = substr($stock_quantity, 1);
		}

		$stock_quantity = test_request($stock_quantity);
		$stock_quantity = intval($stock_quantity);

		if ($stock_quantity > 0) {
			$status = 1;
		} else {
			$status = 0;
		}

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql = "UPDATE `goods` SET `availability`='{$stock_quantity}', `status`='{$status}', `status_import`=1, `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//$n++;

		}

	}

}

//echo $n.'<br>';

?>