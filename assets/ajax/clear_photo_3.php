<?php

$uploaddir = __DIR__ . '/../images/catalog/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /catalog/\n";

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	if ($image != '.' or $image != '..' or $image != 'index.html' or $image != 'no_image.png') {

		if (!getimagesize($uploaddir.$image)) {

			unlink($uploaddir.$image);

			$n++;

		}

	}

	$k++;

	$xrate = intval($k * 100 / $images_uploaddir_cnt);

	echo "\r";

	for ($j=0; $j < $xrate; $j++) echo '|';

	echo " ".$xrate."%";

}

echo "\nFinish clear catalog images: ".$n."\n";
//---------------------------------------------------------------------------------------------------------------------------------



$uploaddir = __DIR__ . '/../images/goods/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /goods/\n";

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	if ($image != '.' or $image != '..' or $image != 'index.html' or $image != 'no_image.png') {

		if (!getimagesize($uploaddir.$image)) {

			unlink($uploaddir.$image);

			$n++;

		}

	}

	$k++;

	$xrate = intval($k * 100 / $images_uploaddir_cnt);

	echo "\r";

	for ($j=0; $j < $xrate; $j++) echo '|';

	echo " ".$xrate."%";

}

echo "\nFinish clear goods images: ".$n."\n";
//---------------------------------------------------------------------------------------------------------------------------------



$uploaddir = __DIR__ . '/../images/goods_thumb/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /goods_thumb/\n";

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	if ($image != '.' or $image != '..' or $image != 'index.html' or $image != 'no_image.png') {

		if (!getimagesize($uploaddir.$image)) {

			unlink($uploaddir.$image);

			$n++;

		}

	}

	$k++;

	$xrate = intval($k * 100 / $images_uploaddir_cnt);

	echo "\r";

	for ($j=0; $j < $xrate; $j++) echo '|';

	echo " ".$xrate."%";

}

echo "\nFinish clear goods_thumb images: ".$n."\n";
//---------------------------------------------------------------------------------------------------------------------------------

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";