<?php

include_once __DIR__ . '/../../../config.php';

$file = __DIR__ . '/../../files/import_providers/erc/import_files/erc.xlsx';
//$file = __DIR__ . '/../../files/xml_providers/himoto/import_excel/excel_file.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($file)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
echo $highestRow;
	$categories = array();

	/*for ($iRow = 2; $iRow <= $highestRow; $iRow++) {

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $oCell->getValue();
		$category_main = test_request($oCell->getValue());
		$category_main = strval($category_main);
		$category_main = str_replace("'", '\'', $category_main);

		$oCell = $oCells->get('C'.$iRow);
		if ($oCell) $oCell->getValue();
		$category_main_item = test_request($oCell->getValue());
		$category_main_item = strval($category_main_item);

		if (!in_array($category_main, array_keys($categories))) {
			$categories[$category_main][] = $category_main_item;
		} else {
			if (!in_array($category_main_item, $categories[$category_main])) {
				$categories[$category_main][] = $category_main_item;
			}
		}

	}*/

}

echo '<ul>';

foreach ($categories as $key => $value) {

	$sql_catalog = "SELECT * FROM `catalog` WHERE `name_uk`='{$key}' LIMIT 1";
	$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
	$count_catalog = mysqli_num_rows($query_catalog);
	$catalog = mysqli_fetch_assoc($query_catalog);

	if ($count_catalog > 0) {

		$level_id = $catalog['id'];

		$style_no_category = '';

	} else {
		$style_no_category = ' style="color:red;"';
	}
	
	echo '<li>
			<h3'.$style_no_category.'>'.$key.'</h3>
			<ol>';

			foreach ($value as $value_item) {

				$sql_catalog = "SELECT * FROM `catalog` WHERE `name_uk`='{$value_item}' AND `level_id`='{$level_id}' LIMIT 1";
				$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
				$count_catalog_in = mysqli_num_rows($query_catalog);
				$catalog = mysqli_fetch_assoc($query_catalog);

				if ($count_catalog_in > 0) {

					$style_no_category_in = '';

				} else {

					$style_no_category_in = ' style="color:red;"';

				}

				echo '<li'.$style_no_category_in.'>'.$value_item.'</li>';
			}

	echo '</ol>
		</li>';

}

echo '</ul>';

/*echo '<pre>';
print_r($categories);
echo '</pre>';*/

?>