<?php

$dir = __DIR__ . '/import/Images/';

$files = scandir($dir);

$size = 0;

foreach ($files as $file) {

	$size += filesize($dir.$file);

	//echo $file." - ".filesize($dir.$file)."\n";

}

$size_kb = $size / 1000;
$size_mb = $size_kb / 1000;
$size_gb = $size_mb / 1000;

echo "Directory: " . $dir . "\n";
echo "Size: " . $size . " b\n";
echo "Size: " . $size_kb . " Kb\n";
echo "Size: " . $size_mb . " Mb\n";
echo "Size: " . $size_gb . " Gb\n\n";

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n\n";

?>