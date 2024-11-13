<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
//include_once __DIR__ . '/../../../include/libs/ImageResize.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

$xml_file = 'https://distributions.com.ua/user_downloads/2abc734fa90b957671671989267c4b67/content_yml/content_yml.xml';

if (filter_var($xml_file, FILTER_VALIDATE_URL) !== false) {
	$headers = get_headers($xml_file, 1);
	if (stripos($headers[0], "200 OK")) {
		if (strpos($headers["Content-Type"], 'xml') !== false) {
			$exists_xml_file = true;
		} else {
			$exists_xml_file = false;
		}
	} else {
		$exists_xml_file = false;
	}
} else {
	$exists_xml_file = false;
}

if ($exists_xml_file == true) {

	$finish_import_images_file_name = 'finish_import_images.txt';
    
    $xml = simplexml_load_file($xml_file);

    $n = 0;

	$uploaddir = __DIR__ . '/../../files/xml_providers/himoto/import_images/';

	foreach ($xml->shop->offers->offer as $offer) {

		$goods_vendor_id = test_request($offer['id']);

		$sql = "SELECT `id` FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods = mysqli_num_rows($query);

		//if (count($offer->picture) > 0 and $count_goods == 0) {
		if (count($offer->picture) > 0) {

			foreach ($offer->picture as $picture) {

				$filename_picture_http = basename($picture);

				$exists_images = file_get_contents(__DIR__ . "/../../files/xml_providers/himoto/exists_filename.log");
				$exists_images = strrpos($exists_images, $filename_picture_http);

				if ($exists_images === false) {

				$filename = basename($picture);

				$uploaddir_filename = $uploaddir.$filename;

				if (!file_exists($uploaddir_filename)) {

					if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
						
						$headers = get_headers($picture, 1);
						
						if (stripos($headers[0], "200 OK")) {
							
							if (strpos($headers["Content-Type"], 'image') !== false) {

								file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/".$finish_import_images_file_name, "processing");

								if (copy($picture, $uploaddir_filename)) {

									if (file_exists($uploaddir_filename)) {

										if (getimagesize($uploaddir_filename)) {

											/*$image = new \Gumlet\ImageResize($uploaddir_filename);

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
											}*/

											$image = new SimpleImage();
											$image->load($uploaddir_filename);

											if ($image->getWidth() >= $image->getHeight()) {

												if ($image->getWidth() > 1920) {

													$image->resizeToWidth(1920);
													$image->save($uploaddir_filename);

												}

											} else {

												if ($image->getHeight() > 1920) {

													$image->resizeToHeight(1920);
													$image->save($uploaddir_filename);

												}

											}

										} else {
											unlink($uploaddir_filename);
										}

										//file_put_contents("../../files/xml_providers/himoto/exists_filename.log", $filename_picture_http."\n", FILE_APPEND | LOCK_EX);

									}

								}

								$n++;

								echo $n." - ".$filename."\n";

							}

						}

					}

				}

				file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/exists_filename.log", $filename_picture_http."\n", FILE_APPEND | LOCK_EX);

				}

			}
		
		}

	}

	$uploaddir_thumb = __DIR__ . '/../../files/xml_providers/himoto/import_images_thumb/';

	if ($n == 0) {

		file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/".$finish_import_images_file_name, "processing");

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

								if ($image_thumb->getWidth() > 300) {

									$image_thumb->resizeToWidth(300);
									$image_thumb->save($uploaddir_thumb.$images_uploaddir);

								}

							} else {

								if ($image_thumb->getHeight() > 300) {

									$image_thumb->resizeToHeight(300);
									$image_thumb->save($uploaddir_thumb.$images_uploaddir);

								}

							}

							echo $images_uploaddir."\n";

						} else {
							unlink($uploaddir.$images_uploaddir);
						}

					}

				}

			}

		} else {

			file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/".$finish_import_images_file_name, "finish");

		}

	}

} else {

	file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/exists_xml_file.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

}

?>