<?php

include_once __DIR__ . '/../../../config.php';

$file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_zist.xlsx';
$file_ru = __DIR__ . '/../../files/import_providers/erc/import_files/erc_zist_ru.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($file_ru)) {

	$oReader_ru = new Xlsx();

	$oSpreadsheet_ru = $oReader_ru->load($file_ru);
	$oCells_ru = $oSpreadsheet_ru->getActiveSheet()->getCellCollection();
	$highestRow_ru = $oSpreadsheet_ru->getActiveSheet()->getHighestRow();
	$highestRow_ru = intval($highestRow_ru);

	$arr_ru = array();

	for ($iRow_ru = 2; $iRow_ru <= $highestRow_ru; $iRow_ru++) {

			$oCell_ru = $oCells_ru->get('A'.$iRow_ru);
			if ($oCell_ru) {
				$parent_name_ru = test_request($oCell_ru->getValue());
				$parent_name_ru = strval($parent_name_ru);
			}

			$oCell_ru = $oCells_ru->get('B'.$iRow_ru);
			if ($oCell_ru) {
				$name_ru = test_request($oCell_ru->getValue());
				$name_ru = strval($name_ru);
			}

			$oCell_ru = $oCells_ru->get('C'.$iRow_ru);
			if ($oCell_ru) {
				$kod_ru = test_request($oCell_ru->getValue());
				$kod_ru = strval($kod_ru);
			}

			$arr_ru[$kod_ru] = array($parent_name_ru, $name_ru);

		}

}

if (file_exists($file)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
	$highestRow = intval($highestRow);

	for ($iRow = 2; $iRow <= $highestRow; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) {
			$parent_name_uk = test_request($oCell->getValue());
			$parent_name_uk = strval($parent_name_uk);
			//$category_main = str_replace("'", "\'", $category_main);
		}

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) {
			$name_uk = test_request($oCell->getValue());
			$name_uk = strval($name_uk);
			//$category_main_item = str_replace("'", "\'", $category_main_item);
		}

		$oCell = $oCells->get('C'.$iRow);
		if ($oCell) {
			$kod_uk = test_request($oCell->getValue());
			$kod_uk = strval($kod_uk);
			//$category_kod = str_replace("'", "\'", $category_kod);
		}

		if (in_array($kod_uk, array_keys($arr_ru))) {

			$parent_name_ru_up = $arr_ru[$kod_uk][0];
			$name_ru_up = $arr_ru[$kod_uk][1];

		}

		$sql = "INSERT INTO `zist` SET `kod`='{$kod_uk}',
										`parent_name_uk`=\"{$parent_name_uk}\",
										`name_uk`=\"{$name_uk}\",
										`parent_name_ru`=\"{$parent_name_ru_up}\",
										`name_ru`=\"{$name_ru_up}\"";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	}

}

?>