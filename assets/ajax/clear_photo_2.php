<?php

include_once __DIR__ . '/../../include/libs/classSimpleImage.php';
include_once __DIR__ . '/../../include/libs/ImageResize.php';

$uploaddir = __DIR__ . '/../images/catalog/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' or $image_dir != '..' or $image_dir != 'index.html') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			$image = new \Gumlet\ImageResize($uploaddir_filename);

			if ($image->getSourceWidth() >= $image->getSourceHeight()) {
				if ($image->getSourceWidth() > 256) {
					$image->resizeToWidth(256);
					$image->save($uploaddir_filename);
				}
			} else {
				if ($image->getSourceHeight() > 256) {
					$image->resizeToHeight(256);
					$image->save($uploaddir_filename);
				}
			}

			$n++;

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

	if ($image_dir != '.' or $image_dir != '..' or $image_dir != 'index.html') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			$image_transparency = new Imagick();
			$image_transparency->readImage($uploaddir_filename);
			$hasTransparency = $image_transparency->getImageAlphaChannel();

			if ($hasTransparency) {

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

				$image = new SimpleImage();
				$image->load($uploaddir_filename);
				if ($image->getWidth() >= $image_thumb->getHeight()) {
					if ($image->getWidth() > 1024) {
						$image->resizeToWidth(1024);
						$image->save($uploaddir_filename);
					}
				} else {
					if ($image->getHeight() > 1024) {
						$image->resizeToHeight(1024);
						$image->save($uploaddir_filename);
					}
				}

			}

			$n++;

		} else {

			unlink($uploaddir_filename);

			$m++;

		}

	}

}

echo "Finish resize goods images: work - ".$n.", del - ".$m."\n";
//---------------------------------------------------------------------------------------------------------------------------------



/*$uploaddir = __DIR__ . '/../images/goods_thumb/';

$images_uploaddir = scandir($uploaddir);

$n = 0;
$m = 0;

foreach ($images_uploaddir as $image_dir) {

	if ($image_dir != '.' or $image_dir != '..' or $image_dir != 'index.html') {

		$uploaddir_filename = $uploaddir.$image_dir;

		if (getimagesize($uploaddir_filename)) {

			$image_transparency = new Imagick();
			$image_transparency->readImage($uploaddir_filename);
			$hasTransparency = $image_transparency->getImageAlphaChannel();

			if ($hasTransparency) {

				$image = new \Gumlet\ImageResize($uploaddir_filename);

				if ($image->getSourceWidth() >= $image->getSourceHeight()) {
					if ($image->getSourceWidth() > 256) {
						$image->resizeToWidth(256);
						$image->save($uploaddir_filename);
					}
				} else {
					if ($image->getSourceHeight() > 256) {
						$image->resizeToHeight(256);
						$image->save($uploaddir_filename);
					}
				}

			} else {

				$image = new SimpleImage();
				$image->load($uploaddir_filename);
				if ($image->getWidth() >= $image_thumb->getHeight()) {
					if ($image->getWidth() > 256) {
						$image->resizeToWidth(256);
						$image->save($uploaddir_filename);
					}
				} else {
					if ($image->getHeight() > 256) {
						$image->resizeToHeight(256);
						$image->save($uploaddir_filename);
					}
				}

			}

			$n++;

		} else {

			unlink($uploaddir_filename);

			$m++;

		}

	}

}

echo "Finish resize goods_thumb images: work - ".$n.", del - ".$m."\n";*/
//---------------------------------------------------------------------------------------------------------------------------------

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";