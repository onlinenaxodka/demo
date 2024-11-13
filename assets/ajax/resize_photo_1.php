<?php

include_once __DIR__ . '/../../include/libs/ImageResize.php';

$uploaddir = __DIR__ . '/../images/catalog/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' or $image_dir != '..') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			try {

				$image_catalog = new \Gumlet\ImageResize($uploaddir_filename);

				if ($image_catalog->getSourceWidth() >= $image_catalog->getSourceHeight()) {
					if ($image_catalog->getSourceWidth() > 256) {
						$image_catalog->resizeToWidth(256);
						$image_catalog->save($uploaddir_filename);
						$n++;
					}
				} else {
					if ($image_catalog->getSourceHeight() > 256) {
						$image_catalog->resizeToHeight(256);
						$image_catalog->save($uploaddir_filename);
						$n++;
					}
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

echo "Finish resize catalog images: work - ".$n.", del - ".$m."\n";
//---------------------------------------------------------------------------------------------------------------------------------



$uploaddir = __DIR__ . '/../images/goods/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' or $image_dir != '..') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			try {

				$image_transparency = new Imagick();
				$image_transparency->readImage($uploaddir_filename);
				$hasTransparency = $image_transparency->getImageAlphaChannel();

				if ($hasTransparency == 1) {

					$image = new \Gumlet\ImageResize($uploaddir_filename);

					if ($image->getSourceWidth() >= $image->getSourceHeight()) {
						if ($image->getSourceWidth() > 1024) {
							$image->resizeToWidth(1024);
							$image->save($uploaddir_filename);
							$n++;
						}
					} else {
						if ($image->getSourceHeight() > 1024) {
							$image->resizeToHeight(1024);
							$image->save($uploaddir_filename);
							$n++;
						}
					}

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

	if ($image_dir != '.' or $image_dir != '..') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			try {

				$image_transparency_thumb = new Imagick();
				$image_transparency_thumb->readImage($uploaddir_filename);
				$hasTransparency = $image_transparency_thumb->getImageAlphaChannel();

				if ($hasTransparency == 1) {

					$image_thumb = new \Gumlet\ImageResize($uploaddir_filename);

					if ($image_thumb->getSourceWidth() >= $image_thumb->getSourceHeight()) {
						if ($image_thumb->getSourceWidth() > 256) {
							$image_thumb->resizeToWidth(256);
							$image_thumb->save($uploaddir_filename);
							$n++;
						}
					} else {
						if ($image_thumb->getSourceHeight() > 256) {
							$image_thumb->resizeToHeight(256);
							$image_thumb->save($uploaddir_filename);
							$n++;
						}
					}

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