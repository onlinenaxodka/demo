<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
//include_once __DIR__ . '/../../../include/libs/ImageResize.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

$file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_images.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($file)) {

	$finish_import_images_file_name = 'finish_import_images.txt';

    $n = 0;

	$uploaddir = __DIR__ . '/../../files/import_providers/erc/import_images/';

	/*$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
	$highestRow = intval($highestRow);

	for ($iRow = 2; $iRow <= $highestRow; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) {
			$picture = test_request($oCell->getValue());
			$picture = strval($picture);
			//$picture = str_replace("'", "\'", $picture);
		}

		if (!empty($picture)) {

			$picture_parse_url = parse_url($picture);

			$picture = 'http://service-fw.erc.ua'.$picture_parse_url['path'];

			$filename_picture_http = basename($picture);

			$exists_images = file_get_contents("../../files/import_providers/erc/exists_filename.log");
			$exists_images = strrpos($exists_images, $filename_picture_http);

			if ($exists_images === false) {

				$filename = basename($picture);

				$uploaddir_filename = $uploaddir.$filename;

				if (!file_exists($uploaddir_filename)) {

					if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
						
						$headers = get_headers($picture, 1);

						if (stripos($headers[0], "200 OK")) {

							if (strpos($headers["Content-Type"], 'image') !== false) {

								file_put_contents("../../files/import_providers/erc/".$finish_import_images_file_name, "processing");

								if (copy($picture, $uploaddir_filename)) {

									if (file_exists($uploaddir_filename)) {

										$image = new \Gumlet\ImageResize($uploaddir_filename);

										if ($image->getSourceWidth() >= $image->getSourceHeight()) {
											if ($image->getSourceWidth() > 1920) {
												$image->resizeToWidth(1920);
												$image->save($uploaddir_filename);
											}
										} else {
											if ($image->getSourceHeight() > 1920) {
												$image->resizeToHeight(1920);
												$image->save($uploaddir_filename);
											}
										}

										file_put_contents("../../files/import_providers/erc/exists_filename.log", $filename_picture_http."\n", FILE_APPEND | LOCK_EX);

									}

								}

								$n++;

							}

						}

					}

				}

			} else {

				if (!getimagesize($uploaddir.$filename_picture_http)) {
					/*$exists_images = file_get_contents("../../files/import_providers/erc/exists_filename.log");
					$exists_images = str_replace($filename_picture_http, '', $exists_images);
					file_put_contents("../../files/import_providers/erc/exists_filename.log", $exists_images);
					$exists_images = file_get_contents("../../files/import_providers/erc/exists_filename.log");
					$exists_images = strrpos($exists_images, $filename_picture_http);
					if ($exists_images === false) {
						unlink($uploaddir.$filename_picture_http);
						echo $filename_picture_http.' - removed<br>';
					}
					echo $filename_picture_http.'<br>';
				}

			}

		}

	}*/

	$uploaddir_thumb = __DIR__ . '/../../files/import_providers/erc/import_images_thumb/';

	if ($n == 0) {

		file_put_contents("../../files/import_providers/erc/".$finish_import_images_file_name, "processing");

		$arr_images_uploaddir = scandir($uploaddir);
		$arr_images_uploaddir_thumb = scandir($uploaddir_thumb);

		if (count($arr_images_uploaddir) != count($arr_images_uploaddir_thumb)) {

			$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

			foreach ($arr_images_uploaddir as $images_uploaddir) {

				$file_type = substr(strrchr($images_uploaddir, '.'), 1);

				if (in_array($file_type, $images_extension_array)) {
				
					if (!file_exists($uploaddir_thumb.$images_uploaddir)) {

						if (getimagesize($uploaddir.$images_uploaddir)) {

						/*$image = new \Gumlet\ImageResize($uploaddir.$images_uploaddir);

						if ($image->getSourceWidth() >= $image->getSourceHeight()) {
							if ($image->getSourceWidth() > 300) {
								$image->resizeToWidth(300);
							}
						} else {
							if ($image->getSourceHeight() > 300) {
								$image->resizeToHeight(300);
							}
						}

						$image->save($uploaddir_thumb.$images_uploaddir);*/

						$image_thumb = new SimpleImage();
						$image_thumb->load($uploaddir.$images_uploaddir);
						if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {
							if ($image_thumb->getWidth() > 256) {
								$image_thumb->resizeToWidth(256);
							}
						} else {
							if ($image_thumb->getHeight() > 256) {
								$image_thumb->resizeToHeight(256);
							}
						}
							
						$image_thumb->save($uploaddir_thumb.$images_uploaddir);

						//echo $images_uploaddir.'<br>';

						} else {
							echo $images_uploaddir.'<br>';
						}
						/*if (!getimagesize($uploaddir_thumb.$images_uploaddir)) {
							echo $images_uploaddir.'<br>';
						}*/

					} else {

						if (!getimagesize($uploaddir_thumb.$images_uploaddir)) {

							unlink($uploaddir_thumb.$images_uploaddir);

						}

					}

				}

			}

		} else {

			file_put_contents("../../files/import_providers/erc/".$finish_import_images_file_name, "finish");

		}

	}

} else {

	file_put_contents("../../files/import_providers/erc/exists_xml_file.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$file.".\n", FILE_APPEND | LOCK_EX);

}
echo $n;

?>