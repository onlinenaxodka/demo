<?php

$start = microtime(true);

include_once __DIR__ . '/../../config.php';

$catalog_table = array();
$sql = "SELECT `id`, `level_id`, `linkname` FROM `catalog` WHERE `id` > 1 AND `locked` = 0 AND `buffer` = 0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($catalog = mysqli_fetch_assoc($query)) {
	$catalog_table[] = [
		'id' => $catalog['id'],
		'level_id' => $catalog['level_id'],
		'linkname' => $catalog['linkname']
	];
}

$goods_table = array();
$sql = "SELECT `category`, COUNT(`category`) AS cnt FROM `goods` WHERE `availability` > 0 AND `status` = 1 GROUP BY `category`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($goods = mysqli_fetch_assoc($query)) {
	$goods_table[$goods['category']] = $goods['cnt'];
}

$sql = "SELECT `id`, `level_id` FROM `catalog` WHERE `id` > 1 AND `locked` = 0 AND `buffer` = 0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$n=0;

$time_tmp = $start;

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_id = $catalog['id'];

	if ($catalog['id'] != $catalog['level_id']) {

		$count_goods_in_catalog = countGoodsInCategoryNew($catalog_table, $goods_table, $catalog_id, 0);

		$sql_count_goods = "UPDATE `catalog` SET `count_goods`='{$count_goods_in_catalog}' WHERE `id`='{$catalog_id}'";
		$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));

	}

	$n++;

	$time_tmp = microtime(true) - $time_tmp;
	$time_tmp = number_format($time_tmp, 4, '.', '');

	//echo "#: ".$n.", id: ".$catalog_id.", cnt: ".$count_goods_in_catalog.", time: ".$time_tmp." sec.\n";

}

$time = microtime(true) - $start;
$time = number_format($time, 4, '.', '');

echo $time."\n";

function countGoodsInCategoryNew($catalog, $goods, $category_id, $count_goods) {

	$count_goods_in_category = 0;

	$subcategories = [];
	foreach ($catalog as $catalog_item) {
		if ($catalog_item['level_id'] == $category_id) {
			$subcategories[] = $catalog_item['id'];
		} elseif ($catalog_item['id'] == $category_id) {
			$count_goods_in_category = $goods[$catalog_item['linkname']];
		}
	}

	if (count($subcategories) > 0) {

		foreach ($subcategories as $subcategory_id) {

			$count_goods_in_category_pre = countGoodsInCategoryNew($catalog, $goods, $subcategory_id, $count_goods);

			$count_goods_in_category += $count_goods_in_category_pre;

		}

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}

?>