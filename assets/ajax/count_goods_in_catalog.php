<?php

$start = microtime(true);

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../config.php';

$sql = "SELECT `id`, `level_id` FROM `catalog` WHERE `id` > 1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$n=0;

$time_tmp = $start;

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_id = $catalog['id'];

	if ($catalog['id'] != $catalog['level_id']) {

		$count_goods_in_catalog = countGoodsInCategory($db, $catalog_id, 0);

		$sql_count_goods = "UPDATE `catalog` SET `count_goods`='{$count_goods_in_catalog}' WHERE `id`='{$catalog_id}'";
		$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));

	} else {

		file_put_contents(__DIR__ . "/../logs/count_goods_in_catalog-errors.log", date('Y-m-d H:i:s')." - No update catalog id: ".$catalog_id."\n", FILE_APPEND | LOCK_EX);

	}

	$n++;

	$time_tmp = microtime(true) - $time_tmp;
	$time_tmp = number_format($time_tmp, 4, '.', '');

	//echo "#: ".$n.", id: ".$catalog_id.", cnt: ".$count_goods_in_catalog.", time: ".$time_tmp." sec.\n";

}

function countGoodsInCategory($db, $category_id, $count_goods) {

	$count_goods_in_category = 0;

	$sql = "SELECT `id` FROM `catalog` WHERE `level_id`='{$category_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$count_subcategories = mysqli_num_rows($query);

	if ($count_subcategories > 0) {

		while ($subcategories = mysqli_fetch_assoc($query)) {

			$count_goods_in_category_pre = countGoodsInCategory($db, $subcategories['id'], $count_goods);

			$count_goods_in_category += $count_goods_in_category_pre;

		}

	} elseif ($count_subcategories == 0) {

		$sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$category = mysqli_fetch_assoc($query);

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}' AND `status`=1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_in_category = mysqli_num_rows($query);

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}

$time = microtime(true) - $start;
$time = number_format($time, 4, '.', '');

$dir_log_file = __DIR__ . "/../logs/assets-ajax-count_goods_in_catalog.log";

file_put_contents($dir_log_file, date('Y-m-d H:i:s')." - Товары успешно обновлены. Время обновления: ".$time." sec.\n", FILE_APPEND | LOCK_EX);

echo $time."\n";

?>