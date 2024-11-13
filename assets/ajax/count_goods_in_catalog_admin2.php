<?php

$start = microtime(true);

include_once __DIR__ . '/../../config.php';

$catalog_table = array();
$sql = "SELECT `id`, `level_id`, `linkname` FROM `catalog` WHERE `id` > 1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($catalog = mysqli_fetch_assoc($query)) {
	$catalog_table[] = [
		'id' => $catalog['id'],
		'level_id' => $catalog['level_id'],
		'linkname' => $catalog['linkname']
	];
}

$goods_table = array();
$sql = "SELECT `category`, COUNT(`category`) AS cnt FROM `goods` GROUP BY `category`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($goods = mysqli_fetch_assoc($query)) {
	$goods_table[$goods['category']] = $goods['cnt'];
}

$sql = "SELECT `id`, `level_id` FROM `catalog` WHERE `id` > 1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$n=0;

while ($catalog = mysqli_fetch_assoc($query)) {

	$start_item = microtime(true);

	$catalog_id = $catalog['id'];

	if ($catalog['id'] != $catalog['level_id']) {

		//$count_goods_in_catalog = countGoodsInCategory($db, $catalog_id, 0);
		$count_goods_in_catalog = countGoodsInCategoryNew($catalog_table, $goods_table, $catalog_id, 0);

		$sql_count_goods = "UPDATE `catalog` SET `count_goods_admin`='{$count_goods_in_catalog}' WHERE `id`='{$catalog_id}'";
		$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));

	}

	$n++;

	$time_item = microtime(true) - $start_item;
	$time_item = number_format($time_item, 4, '.', '');

	//echo "#: ".$n.", id: ".$catalog_id.", cnt: ".$count_goods_in_catalog.", time: ".$time_item." sec.\n";

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

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_in_category = mysqli_num_rows($query);

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}

?>