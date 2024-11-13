<?php

$start = microtime(true);

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/ImageResize.php';

$url_xml_file = 'https://api.erc.ua/api/erc/wares.xml?token=40a164c0684853212f4ed7f308539a440c64e2df7a245a07';

$xml_file = __DIR__ . '/../../files/import_providers/erc/import_files/wares.xml';

//file_put_contents($xml_file, file_get_contents($url_xml_file));

if (file_exists($xml_file)) {

	$xml = simplexml_load_file($xml_file);

	/*foreach ($xml->categories->category as $category) {

		$category_name = test_request($category->title);

		$category_id = test_request($category['id']);
		$category_id = intval($category_id);

		$category_parent_id = test_request($category->parentId);
		$category_parent_id = intval($category_parent_id);

		$sql = "SELECT `id` FROM `catalog` WHERE `user_id`=5856 AND `group_id`='{$category_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_catalog = mysqli_num_rows($query);

	    if ($count_catalog == 0) {

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

	    } 
		
	}

	$sql_ce = "SELECT `id`, `group_id`, `group_parent_id` FROM `catalog` WHERE `user_id`=5856";
	$query_ce = mysqli_query($db, $sql_ce) or die(mysqli_error($db));

	if (mysqli_num_rows($query_ce) > 0) {
			
		while ($catalog_ce = mysqli_fetch_assoc($query_ce)) {
				
			if ($catalog_ce['group_parent_id'] > 0) {

				$catalog_ce_id = $catalog_ce['id'];

				$catalog_group_parent_id = $catalog_ce['group_parent_id'];

				$sql_ce_up = "SELECT `id` FROM `catalog` WHERE `user_id`=5856 AND `group_id`='{$catalog_group_parent_id}' LIMIT 1";
				$query_ce_up = mysqli_query($db, $sql_ce_up) or die(mysqli_error($db));
				$catalog_ce_up = mysqli_fetch_assoc($query_ce_up);
				$catalog_ce_up_level_id = $catalog_ce_up['id'];

				if (mysqli_num_rows($query_ce_up) > 0) {

					$sql_catalog_update = "UPDATE `catalog` SET `level_id`='{$catalog_ce_up_level_id}' WHERE `id`='{$catalog_ce_id}'";
					$query_catalog_update = mysqli_query($db, $sql_catalog_update) or die(mysqli_error($db));

				}

			}

		}

	}*/

	$n = 0;

	foreach ($xml->wares->ware as $ware) {

		$vendor_id = test_request($ware['id']);
		$vendor_id = intval($vendor_id);

		/*$vendor_code = test_request($ware->sku->code);
		$vendor_code = str_replace("'", "\'", $vendor_code);

		$name = test_request($ware->title);
		$name_arr['uk'] = $name;
		$name_arr['ru'] = $name;
		$name = json_encode($name_arr, JSON_UNESCAPED_UNICODE);
		$name = str_replace("'", "\'", $name);

		//$country_of_origin = '-';

		$vendor = test_request($ware->vendor);
		if (empty($vendor)) $vendor = '-';

		$template_uk = array();
		$template_ru = array();
		$template = array();

		$template_uk['Производитель'] = $vendor;
		$template_ru['Производитель'] = $vendor;
				
		//$template_uk['Страна производитель'] = $country_of_origin;
		//$template_ru['Страна производитель'] = $country_of_origin;

		$template_uk['Ширина (см)'] = test_request($ware->ercWidth);
		$template_ru['Ширина (см)'] = test_request($ware->ercWidth);

		$template_uk['Высота (см)'] = test_request($ware->ercHeight);
		$template_ru['Высота (см)'] = test_request($ware->ercHeight);

		$template_uk['Глубина (см)'] = test_request($ware->ercLength);
		$template_ru['Глубина (см)'] = test_request($ware->ercLength);

		$template_uk['Вес (кг)'] = test_request($ware->ercWeight);
		$template_ru['Вес (кг)'] = test_request($ware->ercWeight);

		$template_uk['Объем (m3)'] = test_request($ware->ercVolume);
		$template_ru['Объем (m3)'] = test_request($ware->ercVolume);

		$template['uk'] = $template_uk;
		$template['ru'] = $template_ru;

		$template = json_encode($template, JSON_UNESCAPED_UNICODE);
		$template = str_replace("'", "\'", $template);

		$description_uk = test_request($ware->description);
		$description_uk = str_replace("'", "\'", $description_uk);
		$description_ru = test_request($ware->description);
		$description_ru = str_replace("'", "\'", $description_ru);

		$video = '';
		$export = '';

		$currency = 1;
		$currency_top_kurs = 1;

		$category_id = test_request($ware->categoryId);
		$category_id = intval($category_id);

		$sql = "SELECT `linkname` FROM `catalog` WHERE `user_id`=5856 AND `group_id`='{$category_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$catalog_category = mysqli_fetch_assoc($query);
		$catalog_category_linkname = $catalog_category['linkname'];

		$availability = 0;

		$price_agent = 0;
		$price_purchase = 0;
		$price_sale = 0;

		$status = 0;*/

		$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$vendor_id}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods = mysqli_num_rows($query);

		if ($count_goods > 0) {

			if (count($ware->images->src) > 0) {

				$i = 0;

				foreach ($ware->images->src as $picture) {

					echo $picture . "\n";

				}

			}

			echo "\n\n";

			/*$goods = mysqli_fetch_assoc($query);

			$goods_id = $goods['id'];

			if ($goods['parameters'] != $template) {

				$sql = "UPDATE `goods` SET `parameters`='{$template}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				echo $n." - ".$vendor_id." (update)\n";
				if (empty($template)) echo "empty template\n\n";

			}*/

		} else {

			/*if (count($ware->images->src) > 0) {

				$i = 0;

				foreach ($ware->images->src as $picture) {

					$filename = basename($picture);

					$filename_time = time() .'_' . $n . '_' . $i;
					$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);

					$uploaddir = __DIR__ . '/../../images/goods/';

					$uploaddir_filename = $uploaddir.$filename_new;

					if (!file_exists($uploaddir_filename)) {

						if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
							
							$headers = get_headers($picture, 1);
							
							if (stripos($headers[0], "200 OK")) {
								
								if (strpos($headers["Content-Type"], 'image') !== false) {

									if (copy($picture, $uploaddir_filename)) {

										if (file_exists($uploaddir_filename)) {

											$image = new \Gumlet\ImageResize($uploaddir_filename);

											if ($image->getSourceWidth() >= $image->getSourceHeight()) {

												if ($image->getSourceWidth() > 1920) {

													$image->resizeToWidth(1920);
													$image->save($uploaddir_filename);

												}

											} else {

												if ($image->getSourceHeight() > 1920) {

													$image->resizeToHeight(1920);
													$image->save($uploaddir_filename);

												}

											}

											$uploaddir_filename_thumb = __DIR__ . '/../../images/goods_thumb/'.$filename_new;

											$image_thumb = new \Gumlet\ImageResize($uploaddir_filename);

											if ($image_thumb->getSourceWidth() >= $image_thumb->getSourceHeight()) {

												if ($image_thumb->getSourceWidth() > 300) {

													$image_thumb->resizeToWidth(300);
													$image_thumb->save($uploaddir_filename_thumb);

												}

											} else {

												if ($image_thumb->getSourceHeight() > 300) {

													$image_thumb->resizeToHeight(300);
													$image->save($uploaddir_filename_thumb);

												}

											}

											$photo['img'.$i] = $filename_new;

										}

									}

								}

							}

						}

					}

					$i++;

				}
				
			} else {

				$photo['img0'] = 'no_image.png';

			}

			$photo_str = json_encode($photo);

			$sql = "INSERT INTO `goods` SET `user_id`=5856,
											`vendor_id`='{$vendor_id}',
											`vendor_code`='{$vendor_code}',
											`category`='{$catalog_category_linkname}',
											`name`='{$name}',
											`parameters`='{$template}',
											`photo`='{$photo_str}',
											`video`='{$video}',
											`export`='{$export}',
											`groups`='{\"top\":0,\"new\":0}',
											`availability`='{$availability}',
											`currency`='{$currency}',
											`currency_top_kurs`='{$currency_top_kurs}',
											`price_agent`='{$price_agent}',
											`price_purchase`='{$price_purchase}',
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}',
											`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$goods_id = mysqli_insert_id($db);

			if (!empty($description_uk)) {

				$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
															`description`='{$description_uk}',
															`lang`='uk',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			if (!empty($description_ru)) {

				$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
															`description`='{$description_ru}',
															`lang`='ru',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

			echo $n." - ".$vendor_id." (create)\n";*/

		}

		$n++;

		if ($n > 100) break;

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

$time = microtime(true) - $start;
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>