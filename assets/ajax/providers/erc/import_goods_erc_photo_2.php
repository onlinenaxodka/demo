<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_photo.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$uploaddir_import_images = __DIR__ . '/../../files/import_providers/erc/import_images/';
$uploaddir_import_images_thumb = __DIR__ . '/../../files/import_providers/erc/import_images_thumb/';

if (file_exists($excel_file)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($excel_file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
	$highestRow = intval($highestRow);

	$n = 0;

	for ($iRow = 7001; $iRow <= 14000; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $oCell->getValue();
		$vendor_code = test_request($oCell->getValue());

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $picture = $oCell->getValue();
		$picture = test_request($picture);
		$picture = strval($picture);

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$filename = basename($picture);

		if (empty($goods['photo'])) {

		if (!empty($filename)) {

			if (file_exists($uploaddir_import_images.$filename) and file_exists($uploaddir_import_images_thumb.$filename)) {

				$filename_time = time() .'_' . $iRow;
				$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);
								
				$uploaddir = __DIR__ . '/../../images/goods/';
				//rename($uploaddir_import_images.$filename, $uploaddir.$filename_new);
				copy($uploaddir_import_images.$filename, $uploaddir.$filename_new);

				$uploaddir = __DIR__ . '/../../images/goods_thumb/';
				//rename($uploaddir_import_images_thumb.$filename, $uploaddir.$filename_new);
				copy($uploaddir_import_images_thumb.$filename, $uploaddir.$filename_new);

				$photo['img0'] = $filename_new;

			} else {

				$photo['img0'] = 'no_image.png';

			}

		} else {

			$photo['img0'] = 'no_image.png';
			
		}

		$photo_str = json_encode($photo);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql = "UPDATE `goods` SET `photo`='{$photo_str}', `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$n++;

		}

		}

	}

}

//echo $n.'<br>';

?>