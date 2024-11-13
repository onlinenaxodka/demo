<?php

//$start = microtime(true);

include_once __DIR__ . '/../../config.php';

$table_catalog = array();
$table_goods = array();

$sql = "SELECT `id`, `level_id`, `linkname` FROM `catalog`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($catalog = mysqli_fetch_assoc($query)) {
	
	$table_catalog[] = array($catalog['id'], $catalog['level_id'], $catalog['linkname']);

}

$sql = "SELECT `category`, `status` FROM `goods`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($goods = mysqli_fetch_assoc($query)) {
	
	$table_goods[] = array($goods['category'], $goods['status']);

}




$sql = "SELECT `id` FROM `catalog` WHERE `id` > 1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

//$sql_count_goods = "";

$query_count_goods_in_catalog = array();

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_id = $catalog['id'];

	$count_goods_in_catalog = countGoodsInCategory($table_catalog, $table_goods, $catalog_id, 0);

	$query_count_goods_in_catalog[] = array($catalog_id, $count_goods_in_catalog);

	//$sql_count_goods .= "UPDATE catalog SET count_goods={$count_goods_in_catalog} WHERE id={$catalog_id}; ";
	//echo $count_goods_in_catalog.'<br>';

	echo "preid: ".$catalog_id." - ".$count_goods_in_catalog."\n";

}


$start_update = microtime(true);

foreach ($query_count_goods_in_catalog as $catalog) {
	
	$sql_count_goods = "UPDATE `catalog` SET `count_goods`='{$catalog[1]}' WHERE `id`='{$catalog[0]}'";
	$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));

	echo "id: ".$catalog[0]." - ".$catalog[1]."\n";

}

$finish_update = microtime(true) - $start_update;
$finish_update = number_format($finish_update, 4, '.', '');
echo "\n\nFinish update: ".$finish_update." seconds\n\n";

//echo mysqli_num_rows($query).'<br>';
//echo $sql_count_goods;
//if(!mysqli_multi_query($db, $sql_count_goods)) die(mysqli_error($db));
/*if (mysqli_multi_query($db, $sql_count_goods)) {
	echo 'succes<br>';
} else {
	echo 'error<br>';
}*/

function countGoodsInCategory($table_catalog, $table_goods, $category_id, $count_goods) {

	/*$sql = "SELECT `id` FROM `catalog` WHERE `level_id`='{$category_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$count_subcategories = mysqli_num_rows($query);*/

	$count_subcategories = 0;

	foreach ($table_catalog as $catalog) {
		if ($catalog[1] == $category_id) {
			$count_subcategories++;
		}
	}

	if ($count_subcategories > 0) {

		//while ($subcategories = mysqli_fetch_assoc($query)) {
		foreach ($table_catalog as $subcategories) {

			if ($subcategories[1] == $category_id) {

				$count_goods_in_category_pre = countGoodsInCategory($table_catalog, $table_goods, $subcategories[0], $count_goods);

				$count_goods_in_category += $count_goods_in_category_pre;

			}

		}

	} elseif ($count_subcategories == 0) {

		/*$sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$category = mysqli_fetch_assoc($query);

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}' AND `status`=1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_in_category = mysqli_num_rows($query);*/

		$count_goods_in_category = 0;

		foreach ($table_catalog as $catalog) {
			
			if ($catalog[0] == $category_id) {
				
				foreach ($table_goods as $goods) {

					if ($goods[0] == $catalog[2] and $goods[1] == 1) {

						$count_goods_in_category++;

					}

				}

			}

		}

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}

mysqli_close($db);

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>