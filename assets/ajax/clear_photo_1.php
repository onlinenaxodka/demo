<?php

include_once __DIR__ . '/../../config.php';

$catalog_arr = array();

$sql = "SELECT `img` FROM `catalog`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_arr[] = $catalog['img'];

}

$uploaddir = __DIR__ . '/../images/catalog/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /catalog/\n";

$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	$file_type = substr(strrchr($image, '.'), 1);

	if (in_array($file_type, $images_extension_array)) {

		/*$sql = "SELECT `id` FROM `catalog` WHERE `img`='{$image}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) == 0) {

			if ($image != 'no_image.png') {

				if (file_exists($uploaddir.$image)) {

					unlink($uploaddir.$image);

					$n++;

				}

			}

		}*/

		if (!in_array($image, $catalog_arr)) {

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

echo "\nFinish clear catalog imgages: ".$n."\n";
//---------------------------------------------------------------------------------------------------------------------------------

$goods_arr = array();

$sql = "SELECT `photo` FROM `goods`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($goods = mysqli_fetch_assoc($query)) {

	$goods['photo'] = json_decode($goods['photo'], true);

	foreach ($goods['photo'] as $goods_photo) {
		
		$goods_arr[] = $goods_photo;

	}

}

$uploaddir = __DIR__ . '/../images/goods/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /goods/\n";

$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	$file_type = substr(strrchr($image, '.'), 1);

	if (in_array($file_type, $images_extension_array)) {

		/*$sql = "SELECT `id` FROM `goods` WHERE `photo` LIKE '%{$image}%'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		if (mysqli_num_rows($query) == 0) {

			if ($image != 'no_image.png') {

				if (file_exists($uploaddir.$image)) {

					unlink($uploaddir.$image);

					$n++;

				}

			}

		}*/

		if (!in_array($image, $goods_arr)) {

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

echo "\nFinish clear goods imgages: ".$n."\n";
//---------------------------------------------------------------------------------------------------------------------------------



/*$uploaddir = __DIR__ . '/../images/goods_thumb/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /goods_thumb/\n";

$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	$file_type = substr(strrchr($image, '.'), 1);

	if (in_array($file_type, $images_extension_array)) {

		//$sql = "SELECT `id` FROM `goods` WHERE `photo` LIKE '%{$image}%'";
		//$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		//if (mysqli_num_rows($query) == 0) {

		//	if ($image != 'no_image.png') {

		//		if (file_exists($uploaddir.$image)) {

		//			unlink($uploaddir.$image);

		//			$n++;

		//		}

		//	}

		//}

		if (!in_array($image, $goods_arr)) {

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

echo "\nFinish clear goods_thumb imgages: ".$n."\n";*/
//---------------------------------------------------------------------------------------------------------------------------------

mysqli_close($db);

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";