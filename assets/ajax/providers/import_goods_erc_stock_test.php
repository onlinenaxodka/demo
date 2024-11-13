<?php

include_once __DIR__ . '/../../../config.php';

$sql = "SELECT `vendor_code`, `availability`, `price_agent`, `price_sale` FROM `goods` WHERE `user_id`=5856";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$goods_erc = mysqli_fetch_assoc($query);

echo '<pre>';
var_dump($goods_erc);
echo '</pre>';

//while ($goods_erc = mysqli_fetch_assoc($query)) {

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>