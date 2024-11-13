<?php

$catalog_ids = [];

$sql = "SELECT `group_id` FROM `catalog` WHERE `user_id`=5856";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$category_ids = array_column(mysqli_fetch_all($query, MYSQLI_ASSOC), 'group_id');

foreach ($xml->categories->category as $category) {

	$category_name = test_request($category->title);
	$category_name = str_replace("'", "\'", $category_name);

	$category_id = test_request($category['id']);
	$category_id = intval($category_id);

	$category_parent_id = test_request($category->parentId);
	$category_parent_id = intval($category_parent_id);

	if (!in_array($category_id, $category_ids)) {

		$linkname = GenerateLinkname();

		$sql = "INSERT INTO `catalog` SET `level_id`=4269,
	    									`user_id`=5856,
	    									`group_id`='{$category_id}',
	    									`group_parent_id`='{$category_parent_id}',
											`linkname`='{$linkname}',
											`name_uk`='{$category_name}',
											`name_ru`='{$category_name}',
											`img`='no_image.png',
											`rate`='0.5',
											`buffer`=1,
											`updated`='{$current_date}',
											`created`='{$current_date}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$catalog_id = mysqli_insert_id($db);
		$catalog_ids[] = $catalog_id;

		$category_ids[] = $category_id;

		if ($show_ids) {
			echo 'new category: ' . $catalog_id . PHP_EOL;
		}

	} 
		
}

if (count($catalog_ids)) {
	$sql = "SELECT `id`, `group_id`, `group_parent_id` FROM `catalog` WHERE `id` IN (" . implode(',', $catalog_ids) . ")";
	$query_catalog = mysqli_query($db, $sql) or die(mysqli_error($db));

	while ($catalog = mysqli_fetch_assoc($query_catalog)) {
					
		$sql = "SELECT `id` FROM `catalog` WHERE `user_id`=5856 AND `group_id`='{$catalog['group_parent_id']}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$catalog_child = mysqli_fetch_assoc($query);

		if (mysqli_num_rows($query) > 0) {

			$sql = "UPDATE `catalog` SET `level_id`='{$catalog_child['id']}' WHERE `id`='{$catalog['id']}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		}

	}
}

function GenerateLinkname($n=24) {
	$key = '';
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz_';
	$counter = strlen($pattern)-1;
	for($i=0; $i<$n; $i++) {
		$key .= $pattern{rand(0,$counter)};
	}
	return $key;
}

?>