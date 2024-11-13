<?php

$uploaddir = __DIR__ . '/import/Images/';

$arr_images_uploaddir = scandir($uploaddir);

$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

foreach ($arr_images_uploaddir as $images_uploaddir) {

	$file_type = substr(strrchr($images_uploaddir, '.'), 1);

	if (in_array($file_type, $images_extension_array)) {

		echo $images_uploaddir."\n";

		unlink($uploaddir.$images_uploaddir);

	}

}

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>