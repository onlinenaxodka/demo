<?php

include_once __DIR__ . '/../../config.php';

$uploaddir = __DIR__ . '/../images/catalog/';

$k = 0;
$n = 0;

$sql = "SELECT `id`, `linkname`, `img` FROM `catalog`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$catalog_images_cnt = mysqli_num_rows($query);

$broken_images = [];

while ($catalog = mysqli_fetch_assoc($query)) {

	if (!getimagesize($uploaddir . $catalog['img'])) {

		$broken_images[$catalog['linkname']] = $catalog['id'];

		$k++;

	}

	$n++;

	$xrate = intval($n * 100 / $catalog_images_cnt);

	echo "\r";

	for ($j=0; $j < $xrate; $j++) echo '|';

	echo " ".$xrate."%";

}

echo "\nAll DB catalog images: ".$n.", Broken: ".$k."\n";
print_r($broken_images);

//--------------------------------------------

/*$goods_arr = array();

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

//--------------------------------------------


$uploaddir = __DIR__ . '/../images/goods_thumb/';

$images_uploaddir = scandir($uploaddir);

$images_uploaddir_cnt = count($images_uploaddir);
echo $images_uploaddir_cnt." images in /goods_thumb/\n";

$images_extension_array = array("jpg","jpeg","gif","png","bmp","JPG","JPEG","GIF","PNG","BMP");

$n = 0;
$k = 0;

foreach ($images_uploaddir as $image) {

	$file_type = substr(strrchr($image, '.'), 1);

	if (in_array($file_type, $images_extension_array)) {

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

//--------------------------------------------

mysqli_close($db);

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";