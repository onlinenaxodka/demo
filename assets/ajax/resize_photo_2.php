<?php

include_once __DIR__ . '/../../include/libs/classSimpleImage.php';

$uploaddir = __DIR__ . '/../images/goods/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' and $image_dir != '..' and !empty($image_dir)) {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			try {

				$image_transparency = new Imagick();
				$image_transparency->readImage($uploaddir_filename);
				$hasTransparency = $image_transparency->getImageAlphaChannel();

				if ($hasTransparency == 0) {

					$image = new SimpleImage();
					$image->load($uploaddir_filename);
					if ($image->getWidth() >= $image->getHeight()) {
						if ($image->getWidth() > 1024) {
							$image->resizeToWidth(1024);
							$image->save($uploaddir_filename);
							$n++;
						}
					} else {
						if ($image->getHeight() > 1024) {
							$image->resizeToHeight(1024);
							$image->save($uploaddir_filename);
							$n++;
						}
					}

					//echo $image_dir."\n";

				}

			} catch (ImagickException $e) {

				echo $e->getCode()."\n";
				echo $e->getMessage()."\n";

				unlink($uploaddir_filename);

				$m++;

			}

		} else {

			unlink($uploaddir_filename);

			$m++;

		}

	}

}

echo "Finish resize goods images: work - ".$n.", del - ".$m."\n";
//---------------------------------------------------------------------------------------------------------------------------------



$uploaddir = __DIR__ . '/../images/goods_thumb/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' and $image_dir != '..' and !empty($image_dir)) {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			try {

				$image_transparency_thumb = new Imagick();
				$image_transparency_thumb->readImage($uploaddir_filename);
				$hasTransparency = $image_transparency_thumb->getImageAlphaChannel();

				if ($hasTransparency == 0) {

					$image_thumb = new SimpleImage();
					$image_thumb->load($uploaddir_filename);
					if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {
						if ($image_thumb->getWidth() > 256) {
							$image_thumb->resizeToWidth(256);
							$image_thumb->save($uploaddir_filename);
							$n++;
						}
					} else {
						if ($image_thumb->getHeight() > 256) {
							$image_thumb->resizeToHeight(256);
							$image_thumb->save($uploaddir_filename);
							$n++;
						}
					}

					//echo $image_dir."\n";

				}

			} catch (ImagickException $e) {

				echo $e->getCode()."\n";
				echo $e->getMessage()."\n";

				unlink($uploaddir_filename);

				$m++;

			}

		} else {

			unlink($uploaddir_filename);

			$m++;

		}

	}

}

echo "Finish resize goods_thumb images: work - ".$n.", del - ".$m."\n";
//---------------------------------------------------------------------------------------------------------------------------------

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";