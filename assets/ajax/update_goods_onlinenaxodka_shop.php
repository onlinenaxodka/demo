<?php

//$start = microtime(true);

include_once __DIR__ . '/../../config.php';

date_default_timezone_set('UTC');

$db_shop = mysqli_connect("localhost", "user", "pass", "db");

if (!$db_shop) {
    printf("Connect failed shop: %s\n", mysqli_connect_error());
    exit();
}

mysqli_query($db_shop,"set character_set_client   ='utf8'");
mysqli_query($db_shop,"set character_set_results  ='utf8'");
mysqli_query($db_shop,"set collation_connection   ='utf8_general_ci'");


$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');


$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_eur = mysqli_fetch_assoc($query);
$api_exchange_rate_eur['buy'] = number_format($api_exchange_rate_eur['buy'], 2, '.', '');
$api_exchange_rate_eur['sale'] = number_format($api_exchange_rate_eur['sale'], 2, '.', '');

//$user_ids = '1799,5856,6766,7158,7625';
//$user_ids = '1799';
//$user_ids = '25';

/*$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1 GROUP BY `category`) AND `locked`=0 AND `buffer`=0";*/
$sql = "SELECT * FROM `catalog` WHERE `linkname` IN (SELECT `category` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' GROUP BY `category`) AND `locked`=0 AND `buffer`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

$catalog_arr = array();
$category_linkname_children = array();

while ($catalog = mysqli_fetch_assoc($query)) {

	$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru'], $catalog['img']), $catalog_arr);

	$category_linkname_children[] = strval('\''.$catalog['linkname'].'\'');

}

/*$sql = "DELETE FROM `oc_category`";
$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

$sql = "DELETE FROM `oc_category_description`";
$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

$sql = "DELETE FROM `oc_category_path`";
$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

$sql = "DELETE FROM `oc_category_to_layout`";
$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

$sql = "DELETE FROM `oc_category_to_store`";
$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));*/

foreach ($catalog_arr as $catalog_arr_value) {

	$catalog_id = $catalog_arr_value['id'];
	$catalog_parent_id = $catalog_arr_value['parentId'];
	
	$catalog_linkname = $catalog_arr_value['data'][0];
	$catalog_name = str_replace("'", "\'", $catalog_arr_value['data'][1]);
	$catalog_img = $catalog_arr_value['data'][2];

	$top = 0;

	if ($catalog_parent_id == 1) {

		$catalog_parent_id = 0;
		$top = 1;

	}

	$sql = "SELECT `category_id` FROM `oc_category` WHERE `category_id`='{$catalog_id}'";
	$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));
	$count_oc_category = mysqli_num_rows($query);

	if ($count_oc_category == 0) {

		$sql = "INSERT INTO `oc_category` SET `category_id`='{$catalog_id}',
												`image`='assets/images/catalog/{$catalog_img}',
												`parent_id`='{$catalog_parent_id}',
												`top`='{$top}',
												`column`=1,
												`sort_order`=0,
												`status`=1,
												`date_added`='{$current_date}',
												`date_modified`='{$current_date}'";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "INSERT INTO `oc_category_description` SET `category_id`='{$catalog_id}',
												`language_id`=1,
												`name`='{$catalog_name}',
												`meta_title`='{$catalog_name}',
												`meta_h1`='{$catalog_name}'";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "INSERT INTO `oc_category_to_layout` SET `category_id`='{$catalog_id}',
														`store_id`=0,
														`layout_id`=0";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "INSERT INTO `oc_category_to_store` SET `category_id`='{$catalog_id}',
														`store_id`=0";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));



		$catalog_path = categories_path($catalog_id, $catalog_parent_id, $catalog_arr, array());

		if (count($catalog_path) > 0) {

			$level=0;

			for ($i=count($catalog_path)-1; $i >= 0; $i--) {

				$sql = "INSERT INTO `oc_category_path` SET `category_id`='{$catalog_id}',
															`path_id`='{$catalog_path[$i]}',
															`level`='{$level}'";
				$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));
				
				$level++;

			}

		}



	}
		
}

if (count($category_linkname_children) > 0) {

	$category_linkname_separated = implode(",", $category_linkname_children);

	/*$sql_goods = "SELECT `parameters` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1";*/
	$sql_goods = "SELECT `parameters` FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%'";
	$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

	if (mysqli_num_rows($query_goods) > 0) {

		while ($goods = mysqli_fetch_assoc($query_goods)) {

			$goods['parameters'] = json_decode($goods['parameters'], true);

			$goods_vendor = $goods['parameters']['ru']['Производитель'];
			$goods_vendor = str_replace("'", "\'", $goods_vendor);

			if (!empty($goods_vendor)) {

				$sql = "SELECT `name` FROM `oc_manufacturer` WHERE `name`='{$goods_vendor}'";
				$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

				if (mysqli_num_rows($query) == 0) {

					$sql = "INSERT INTO `oc_manufacturer` SET `name`='{$goods_vendor}'";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

					$manufacturer_id = mysqli_insert_id($db_shop);

					$sql = "INSERT INTO `oc_manufacturer_description` SET `manufacturer_id`='{$manufacturer_id}',
																			`language_id`=1,
																			`name`='{$goods_vendor}',
																			`meta_title`='{$goods_vendor}',
																			`meta_h1`='{$goods_vendor}'";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

					$sql = "INSERT INTO `oc_manufacturer_to_store` SET `manufacturer_id`='{$manufacturer_id}', `store_id`=0";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

				}

			}

		}

	}

	/*$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%' AND `availability`>0 AND `status`=1";*/
	$sql_goods = "SELECT * FROM `goods` WHERE `user_id` IN ({$user_ids}) AND `category` IN ({$category_linkname_separated}) AND `photo` NOT LIKE '%{\"img0\":\"no_image.png\"}%'";
	$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));

	if (mysqli_num_rows($query_goods) > 0) {

		/*$sql = "DELETE FROM `oc_product`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_description`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_image`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_attribute`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_to_category`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_to_layout`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

		$sql = "DELETE FROM `oc_product_to_store`";
		$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));*/

		while ($goods = mysqli_fetch_assoc($query_goods)) {

			$goods_id = $goods['id'];

			$sql_product = "SELECT `product_id` FROM `oc_product` WHERE `product_id`='{$goods_id}'";
			$query_product = mysqli_query($db_shop, $sql_product) or die(mysqli_error($db_shop));
			
			if (mysqli_num_rows($query_product) == 0) {

			$goods_vendor_code = $goods['vendor_code'];
			if ($goods_vendor_code == '-') $goods_vendor_code = ''; 

			$goods['name'] = json_decode($goods['name'], true);
			$goods_name = str_replace("'", "\'", $goods['name']['ru']);

			if ($goods['currency'] == 1) {

				$kurs_currency = 1;

			} elseif ($goods['currency'] == 2) {

				$kurs_currency = $api_exchange_rate_usd['sale'];

				if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

					$kurs_currency = $goods['currency_top_kurs'];

				}

			} elseif ($goods['currency'] == 3) {

				$kurs_currency = $api_exchange_rate_eur['sale'];

				if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

					$kurs_currency = $goods['currency_top_kurs'];

				}

			}

			$price_sale = ceil($goods['price_sale'] * $kurs_currency);
			$price_sale = number_format($price_sale, 2, '.', '');

			$sql_goods_description = "SELECT `description` FROM `goods_description` WHERE `goods_id`='{$goods['id']}' AND `lang`='ru'";
			$query_goods_description = mysqli_query($db, $sql_goods_description) or die(mysqli_error($db));
			$goods_description = mysqli_fetch_assoc($query_goods_description);

			$str = $goods_description['description'];

			$str_new = '';

			$arr = str_split($str);

			$start = false;

			for ($i=0; $i < count($arr); $i++) { 
				
				if ($arr[$i] == '<' and $arr[$i+1] == 'a') $start = true;
				if ($arr[$i-2] == 'a' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == '<' and $arr[$i+1] == 'i' and $arr[$i+2] == 'f' and $arr[$i+3] == 'r') $start = true;
				if ($arr[$i-5] == 'r' and $arr[$i-4] == 'a' and $arr[$i-3] == 'm' and $arr[$i-2] == 'e' and $arr[$i-1] == '>') $start = false;
				if ($arr[$i] == 'h' and $arr[$i+1] == 't' and $arr[$i+2] == 't' and $arr[$i+3] == 'p') $start = true;
				if ($arr[$i] == ' ') $start = false;

				if (!$start) {

					$str_new .= $arr[$i];

				}

			}

			$goods['description'] = $str_new;
			$goods_description = str_replace("'", "\'", $goods['description']);

			$stock_quantity = $goods['availability'];

			if ($stock_quantity > 0) $stock_status_id = 7;
			else $stock_status_id = 5;

			$goods['photo'] = json_decode($goods['photo'], true);

			$image_main = $goods['photo']['img0'];

			$goods['parameters'] = json_decode($goods['parameters'], true);

			$goods_vendor = $goods['parameters']['ru']['Производитель'];
			$goods_vendor = str_replace("'", "\'", $goods_vendor);

			$sql = "SELECT `manufacturer_id` FROM `oc_manufacturer` WHERE `name`='{$goods_vendor}'";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));
			if (mysqli_num_rows($query) > 0) {
				$oc_manufacturer = mysqli_fetch_assoc($query);
				$manufacturer_id = $oc_manufacturer['manufacturer_id'];
			} else {
				$manufacturer_id = 0;
			}

			$date_available = date('Y-m-d');

			$goods_weight = $goods['parameters']['ru']['Вес (кг)'];
			$goods_length = $goods['parameters']['ru']['Глубина (см)'];
			$goods_width = $goods['parameters']['ru']['Ширина (см)'];
			$goods_height = $goods['parameters']['ru']['Высота (см)'];

			$sql = "INSERT INTO `oc_product` SET `product_id`='{$goods_id}',
													`model`='{$goods_vendor_code}',
													`sku`='{$goods_vendor_code}',
													`quantity`='{$stock_quantity}',
													`stock_status_id`='{$stock_status_id}',
													`image`='assets/images/goods/{$image_main}',
													`manufacturer_id`='{$manufacturer_id}',
													`shipping`=1,
													`price`='{$price_sale}',
													`date_available`='{$date_available}',
													`weight`='{$goods_weight}',
													`weight_class_id`=1,
													`length`='{$goods_length}',
													`width`='{$goods_width}',
													`height`='{$goods_height}',
													`length_class_id`=1,
													`minimum`=1,
													`status`=1,
													`date_added`='{$current_date}',
													`date_modified`='{$current_date}'";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			for ($i=1; $i < count($goods['photo']); $i++) {

				if (file_exists(__DIR__.'/../images/goods/'.$goods['photo']['img'.$i])) {

					$image = $goods['photo']['img'.$i];

					$sql = "INSERT INTO `oc_product_image` SET `product_id`='{$goods_id}', `image`='assets/images/goods/{$image}'";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

				}

			}

			$sql = "INSERT INTO `oc_product_description` SET `product_id`='{$goods_id}',
													`language_id`=1,
													`name`='{$goods_name}',
													`description`='{$goods_description}',
													`meta_title`='{$goods_name}',
													`meta_h1`='{$goods_name}'";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			$parameters_names_no = array('Производитель', 'Вес (кг)', 'Глубина (см)', 'Ширина (см)', 'Высота (см)', 'Артикул', 'артикул', 'Бренд', '-', ' ', '');
			$parameters_values_no = array('-', ' ', '');

			foreach ($goods['parameters']['ru'] as $parameters_key => $parameters_value) {

				if (!in_array($parameters_key, $parameters_names_no) and !in_array($parameters_value, $parameters_values_no)) {

					$parameters_key = str_replace("'", "\'", $parameters_key);
					$parameters_value = str_replace("'", "\'", $parameters_value);

					$sql = "SELECT `attribute_id` FROM `oc_attribute_description` WHERE `name`='{$parameters_key}'";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));
					if (mysqli_num_rows($query) == 0) {
						
						$sql = "INSERT INTO `oc_attribute` SET `attribute_group_id`=4";
						$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

						$attribute_id = mysqli_insert_id($db_shop);

						$sql = "INSERT INTO `oc_attribute_description` SET `attribute_id`='{$attribute_id}',
																			`language_id`=1,
																			`name`='{$parameters_key}'";
						$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

					}

					$sql = "SELECT `attribute_id` FROM `oc_attribute_description` WHERE `name`='{$parameters_key}'";
					$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));
					if (mysqli_num_rows($query) > 0) {
						
						$oc_attribute_description = mysqli_fetch_assoc($query);
						$attribute_id = $oc_attribute_description['attribute_id'];

						$sql = "INSERT INTO `oc_product_attribute` SET `product_id`='{$goods_id}',
																		`attribute_id`='{$attribute_id}',
																		`language_id`=1,
																		`text`='{$parameters_value}'";
						$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

					}

				}

			}

			$sql_catalog = "SELECT `id` FROM `catalog` WHERE `linkname`='{$goods['category']}'";
			$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
			$goods_catalog = mysqli_fetch_assoc($query_catalog);

			$goods_catalog_id = $goods_catalog['id'];

			$sql = "INSERT INTO `oc_product_to_category` SET `product_id`='{$goods_id}', `category_id`='{$goods_catalog_id}', `main_category`=1";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			$sql = "INSERT INTO `oc_product_to_layout` SET `product_id`='{$goods_id}', `store_id`=0, `layout_id`=0";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			$sql = "INSERT INTO `oc_product_to_store` SET `product_id`='{$goods_id}', `store_id`=0";
			$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			} else {

				$goods_vendor_code = $goods['vendor_code'];
				if ($goods_vendor_code == '-') $goods_vendor_code = ''; 

				if ($goods['currency'] == 1) {

					$kurs_currency = 1;

				} elseif ($goods['currency'] == 2) {

					$kurs_currency = $api_exchange_rate_usd['sale'];

					if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

						$kurs_currency = $goods['currency_top_kurs'];

					}

				} elseif ($goods['currency'] == 3) {

					$kurs_currency = $api_exchange_rate_eur['sale'];

					if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

						$kurs_currency = $goods['currency_top_kurs'];

					}

				}

				$price_sale = ceil($goods['price_sale'] * $kurs_currency);
				$price_sale = number_format($price_sale, 2, '.', '');

				$stock_quantity = $goods['availability'];

				if ($stock_quantity > 0) $stock_status_id = 7;
				else $stock_status_id = 5;

				$status = 1;

				if ($goods['status'] == 0) {

					$stock_status_id = 5;
					$status = 0;
				}

				$date_available = date('Y-m-d');

				$sql = "UPDATE `oc_product` SET `quantity`='{$stock_quantity}',
												`stock_status_id`='{$stock_status_id}',
												`price`='{$price_sale}',
												`date_available`='{$date_available}',
												`status`='{$status}',
												`date_modified`='{$current_date}' WHERE `product_id`='{$goods_id}'";
				$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			}

			$goods_arr_tmp[] = $goods_id;

			echo $goods_id."\n";

		}

		$sql_product = "SELECT `product_id` FROM `oc_product`";
		$query_product = mysqli_query($db_shop, $sql_product) or die(mysqli_error($db_shop));

		$date_available = date('Y-m-d');

		while ($piv = mysqli_fetch_assoc($query_product)) {
				
			if (!in_array($piv['product_id'], $goods_arr_tmp)) {

				$piv_product_id = $piv['product_id'];

				$sql = "UPDATE `oc_product` SET `quantity`='0',
												`stock_status_id`='5',
												`date_available`='{$date_available}',
												`date_modified`='{$current_date}' WHERE `product_id`='{$piv_product_id}'";
				$query = mysqli_query($db_shop, $sql) or die(mysqli_error($db_shop));

			}

		}

	}

}

function categories($db, $id, $parent_id, $data, $catalog_arr) {

	$ca_status = 0;

	foreach ($catalog_arr as $catalog_arr_value) {
		
		if ($catalog_arr_value['id'] == $id) {

			$ca_status++;

		}
		
	}

	if ($ca_status == 0) {

		if ($parent_id > 1) {

			$catalog_arr[] = array(
				'id' => $id,
				'parentId' => $parent_id,
				'data' => $data
			);

			$sql = "SELECT * FROM `catalog` WHERE `id`='{$parent_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$catalog = mysqli_fetch_assoc($query);

			$catalog_arr = categories($db, $catalog['id'], $catalog['level_id'], array($catalog['linkname'], $catalog['name_ru'], $catalog['img']), $catalog_arr);

		} else {

			if ($parent_id > 0) {

				$catalog_arr[] = array(
					'id' => $id,
					'parentId' => $parent_id,
					'data' => $data
				);

			}

		}

	}

	return $catalog_arr;

}

function categories_path($id, $parent_id, $catalog_arr, $catalog_path) {

	if ($parent_id == 0) {

		$catalog_path[] = $id;

	} else {

		$catalog_path[] = $id;

		foreach ($catalog_arr as $catalog_arr_value) {
			
			if ($parent_id == $catalog_arr_value['id']) {

				$catalog_path = categories_path($catalog_arr_value['id'], $catalog_arr_value['parentId'], $catalog_arr, $catalog_path);

			}

		}

	}

	return $catalog_path;

}

mysqli_close($db);
mysqli_close($db_shop);

//$time = microtime(true) - $start;
$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');

echo $time."\n";