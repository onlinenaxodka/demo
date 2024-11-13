<?php

include_once __DIR__ . '/../../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$user_id = 5856;

		$goods_asociate_category_id = (isset($_POST['goods_asociate_category_id'])) ? mysqli_real_escape_string($db, $_POST['goods_asociate_category_id']) : '';
		$goods_asociate_category_id = test_request($goods_asociate_category_id);
		$goods_asociate_category_id = intval($goods_asociate_category_id);
echo $goods_asociate_category_id;
		$linkname = (isset($_POST['linkname'])) ? mysqli_real_escape_string($db, $_POST['linkname']) : '';
		$linkname = test_request($linkname);

		$parent_name = (isset($_POST['parent_name'])) ? mysqli_real_escape_string($db, $_POST['parent_name']) : '';
		$parent_name = test_request($parent_name);

		$name = (isset($_POST['name'])) ? mysqli_real_escape_string($db, $_POST['name']) : '';
		$name = test_request($name);

		$sql = "UPDATE `goods_asociate_category` SET `user_id`='{$user_id}',
														`parent_name`='{$parent_name}',
														`name`='{$name}',
														`linkname`='{$linkname}',
														`updated`='{$current_date}',
														`created`='{$current_date}' WHERE `id`='{$goods_asociate_category_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	}

}

$file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_ru.xlsx';
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
	$highestRow = intval($highestRow);

	$categories = array();
	$n = 0;

	for ($iRow = 2; $iRow <= $highestRow; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) {
			$category_main = test_request($oCell->getValue());
			$category_main = strval($category_main);
			//$category_main = str_replace("'", "\'", $category_main);
		}

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) {
			$category_main_item = test_request($oCell->getValue());
			$category_main_item = strval($category_main_item);
			//$category_main_item = str_replace("'", "\'", $category_main_item);
		}

		if (!in_array($category_main, array_keys($categories))) {
			$categories[$category_main][] = $category_main_item;
		} else {
			if (!in_array($category_main_item, $categories[$category_main])) {
				$categories[$category_main][] = $category_main_item;
			}
		}

		$n++;

	}

}

echo 'Обработано товаров: '.$n.'<br>';

echo '<ul>';

foreach ($categories as $key => $value) {

	//$key = str_replace("'", "\'", $key);

	$sql_catalog = "SELECT * FROM `catalog` WHERE `name_uk`=\"{$key}\" AND `level_id`=3066 LIMIT 1";
	$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
	$count_catalog = mysqli_num_rows($query_catalog);
	$catalog = mysqli_fetch_assoc($query_catalog);

	$sql_zist = "SELECT * FROM `zist` WHERE `parent_name_uk`=\"{$key}\" LIMIT 1";
	$query_zist = mysqli_query($db, $sql_zist) or die(mysqli_error($db));
	$count_zist = mysqli_num_rows($query_zist);
	$zist = mysqli_fetch_assoc($query_zist);
	$zist_parent_name_ru = $zist['parent_name_ru'];

	if (count($count_zist) > 0) {

		/*$sql_catalog_up = "UPDATE `catalog` SET `name_uk`='{$zist_parent_name_ru}',`name_ru`='{$zist_parent_name_ru}' WHERE `name_uk`=\"{$key}\" AND `level_id`=3066";
		$query_catalog_up = mysqli_query($db, $sql_catalog_up) or die(mysqli_error($db));*/
		//echo $zist_parent_name_ru.'<br>';

	}

	if ($count_catalog > 0) {

		$level_id = $catalog['id'];

		$style_no_category = '';

	} else {

		$level_id = 0;

		$style_no_category = ' style="color:red;"';

	}
	
	echo '<li>
			<h3'.$style_no_category.'>'.$key.'</h3>
			<ol>';

			foreach ($value as $value_item) {

				//$value_item = str_replace("'", "\'", $value_item);

				$sql_zist_child = "SELECT * FROM `zist` WHERE `parent_name_uk`=\"{$key}\" AND `name_uk`=\"$value_item\" LIMIT 1";
				$query_zist_child = mysqli_query($db, $sql_zist_child) or die(mysqli_error($db));
				$count_zist_child = mysqli_num_rows($query_zist_child);
				$zist_child = mysqli_fetch_assoc($query_zist_child);
				$zist_child_name_ru = $zist_child['name_ru'];

				if (count($count_zist_child) > 0) {

					/*$sql_catalog_up_child = "UPDATE `catalog` SET `name_uk`='{$zist_child_name_ru}',`name_ru`='{$zist_child_name_ru}' WHERE `name_uk`=\"{$value_item}\" AND `level_id`='{$level_id}'";
					$query_catalog_up_child = mysqli_query($db, $sql_catalog_up_child) or die(mysqli_error($db));*/
					//echo $zist_child_name_ru.'<br>';

				}

				$sql_catalog_in = "SELECT * FROM `catalog` WHERE `name_uk`=\"{$value_item}\" AND `level_id`='{$level_id}' LIMIT 1";
				$query_catalog_in = mysqli_query($db, $sql_catalog_in) or die(mysqli_error($db));
				$count_catalog_in = mysqli_num_rows($query_catalog_in);
				$catalog_in = mysqli_fetch_assoc($query_catalog_in);
				$catalog_in_linkname = $catalog_in['linkname'];

				if ($count_catalog_in > 0) {

					$style_no_category_in = '';

					/*$sql = "INSERT INTO `goods_asociate_category` SET `user_id`=5856,
																	`parent_name`=\"{$key}\",
																	`name`=\"{$value_item}\",
																	`linkname`='{$catalog_in_linkname}',
																	`updated`='{$current_date}',
																	`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

				} else {

					$style_no_category_in = ' style="color:red;"';

				}

				$sql_goods_asociate_category = "SELECT * FROM `goods_asociate_category` WHERE `parent_name`=\"{$key}\" AND `name`=\"{$value_item}\" LIMIT 1";
				$query_goods_asociate_category = mysqli_query($db, $sql_goods_asociate_category) or die(mysqli_error($db));
				$goods_asociate_category = mysqli_fetch_assoc($query_goods_asociate_category);

				echo '<li'.$style_no_category_in.'>'.$value_item.' 
				<form method="POST">
					<input type="hidden" name="goods_asociate_category_id" value="'.$goods_asociate_category['id'].'">
					<input type="hidden" name="parent_name" value="'.$goods_asociate_category['parent_name'].'">
					<input type="hidden" name="name" value="'.$goods_asociate_category['name'].'">
					<input type="text" name="linkname" value="'.$goods_asociate_category['linkname'].'">
					<button type="submit">Сохранить</button>
				</form></li><b>'.$goods_asociate_category['parent_name'].' / '.$goods_asociate_category['name'].'</b><br><br>';

			}

	echo '</ol>
		</li>';

}

echo '</ul>';

/*echo '<pre>';
print_r($categories);
echo '</pre>';*/

?>