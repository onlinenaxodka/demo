<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/ImageResize.php';

$xml_file = __DIR__ . '/../../files/xml_providers/mobiking/import_xml/mobiking.xml';

if (file_exists($xml_file)) {

	$finish_import_images_file_name = 'finish_import_images.txt';
    
    $xml = simplexml_load_file($xml_file);

    $n = 0;

	$uploaddir = __DIR__ . '/../../files/xml_providers/mobiking/import_images/';

	$sql = "SELECT `vendor_id` FROM `goods` WHERE `user_id`=5184";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	
	if (mysqli_num_rows($query) > 0) {

		$goods_arr = array();

		while ($goods = mysqli_fetch_assoc($query)) {
			
			$goods_arr[] = $goods['vendor_id'];

		}

	}

	foreach ($xml->Item as $item) {

		$goods_vendor_id = test_request($item->Код);

		$count_goods = 0;

		if (in_array($goods_vendor_id, $goods_arr)) {
			$count_goods = 1;
		}

		if (!empty($item->Картинки->picture[0]) and $count_goods == 0) {

			$picture = $item->Картинки->picture[0];

			/*$filename_picture_http = basename($picture);

			$exists_images = file_get_contents("../../files/xml_providers/mobiking/exists_filename.log");
			$exists_images = strrpos($exists_images, $filename_picture_http);

			if ($exists_images === false) {*/

				$filename = basename($picture);

				$uploaddir_filename = $uploaddir.$filename;

				if (!file_exists($uploaddir_filename)) {

					if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
						
						$headers = get_headers($picture, 1);
						
						if (stripos($headers[0], "200 OK")) {
							
							if (strpos($headers["Content-Type"], 'image') !== false) {

								file_put_contents("../../files/xml_providers/mobiking/".$finish_import_images_file_name, "processing");

								if (copy($picture, $uploaddir_filename)) {

									if (file_exists($uploaddir_filename)) {

										if (getimagesize($uploaddir_filename)) {

											$image = new \Gumlet\ImageResize($uploaddir_filename);

											if ($image->getSourceWidth() >= $image->getSourceHeight()) {
												if ($image->getSourceWidth() > 1024) {
													$image->resizeToWidth(1024);
													$image->save($uploaddir_filename);
												}
											} else {
												if ($image->getSourceHeight() > 1024) {
													$image->resizeToHeight(1024);
													$image->save($uploaddir_filename);
												}
											}

										} else {
											unlink($uploaddir_filename);
										}

										//file_put_contents("../../files/xml_providers/mobiking/exists_filename.log", $filename_picture_http."\n", FILE_APPEND | LOCK_EX);

									}

								}

								$n++;

							}

						}

					}

				}

				//file_put_contents("../../files/xml_providers/mobiking/exists_filename.log", $filename_picture_http."\n", FILE_APPEND | LOCK_EX);

			//}
		
		}

	}

	$uploaddir_thumb = __DIR__ . '/../../files/xml_providers/mobiking/import_images_thumb/';

	if ($n == 0) {

		file_put_contents("../../files/xml_providers/mobiking/".$finish_import_images_file_name, "processing");

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
							if ($image->getSourceWidth() > 256) {
								$image->resizeToWidth(256);
							}
						} else {
							if ($image->getSourceHeight() > 256) {
								$image->resizeToHeight(256);
							}
						}

						$image->save($uploaddir_thumb.$images_uploaddir);*/

						} else {
							unlink($uploaddir.$images_uploaddir);
						}

					}

				}

			}

		} else {

			file_put_contents("../../files/xml_providers/mobiking/".$finish_import_images_file_name, "finish");

			header('Location: /admin/goods_upload/?mobiking_images=success');
			exit;

		}

	}

} else {

    file_put_contents("../../files/xml_providers/mobiking/exists_xml_file.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

}

?>