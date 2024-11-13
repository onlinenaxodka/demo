<?php

header('Content-Type: text/html; charset=utf-8');

$file_url = 'https://distributions.com.ua/user_downloads/2abc734fa90b957671671989267c4b67/content_yml/content_yml.xml';
$file_local = __DIR__ . '/../../../data/files/import/himoto.xml';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $file_url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

file_put_contents($file_local, $response);

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

$xml_file = $file_local;

if (filter_var($xml_file, FILTER_VALIDATE_URL) !== false) {
	$headers = get_headers($xml_file, 1);
	if (stripos($headers[0], "200 OK")) {
		if (strpos($headers["Content-Type"], 'xml') !== false) {
			$exists_xml_file = true;
		} else {
			$exists_xml_file = false;
		}
	} else {
		$exists_xml_file = false;
	}
} else {
	$exists_xml_file = false;
}

	if ($exists_xml_file == true) {
	    
	    $xml = simplexml_load_file($xml_file);

	foreach ($xml->shop->categories->category as $category) {

		$category_name = test_request($category);

		$category_id = test_request($category['id']);
		$category_id = intval($category_id);

		$category_parent_id = test_request($category['parentId']);
		$category_parent_id = intval($category_parent_id);

		$sql = "SELECT `id` FROM `catalog` WHERE `user_id`=407 AND `group_id`='{$category_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_catalog = mysqli_num_rows($query);

	    if ($count_catalog == 0) {

	    	$linkname = GenerateLinkname();

	    	$sql = "INSERT INTO `catalog` SET `level_id`=2720,
	    										`user_id`=407,
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

	$sql_ce = "SELECT `id`, `group_id`, `group_parent_id` FROM `catalog` WHERE `user_id`=407";
	$query_ce = mysqli_query($db, $sql_ce) or die(mysqli_error($db));

	if (mysqli_num_rows($query_ce) > 0) {
			
		while ($catalog_ce = mysqli_fetch_assoc($query_ce)) {
				
			if ($catalog_ce['group_parent_id'] > 0) {

				$catalog_ce_id = $catalog_ce['id'];

				$catalog_group_parent_id = $catalog_ce['group_parent_id'];

				$sql_ce_up = "SELECT `id` FROM `catalog` WHERE `user_id`=407 AND `group_id`='{$catalog_group_parent_id}' LIMIT 1";
				$query_ce_up = mysqli_query($db, $sql_ce_up) or die(mysqli_error($db));
				$catalog_ce_up = mysqli_fetch_assoc($query_ce_up);
				$catalog_ce_up_level_id = $catalog_ce_up['id'];

				if (mysqli_num_rows($query_ce_up) > 0) {

					$sql_catalog_update = "UPDATE `catalog` SET `level_id`='{$catalog_ce_up_level_id}' WHERE `id`='{$catalog_ce_id}'";
					$query_catalog_update = mysqli_query($db, $sql_catalog_update) or die(mysqli_error($db));

				}

			}

		}

	}

	    $n = 0;

		foreach ($xml->shop->offers->offer as $offer) {

			$goods_vendor_id = test_request($offer['id']);

			$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' AND `user_id`=407 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);

			$goods_vendor_code = '-';
			
			$goods_name = test_request($offer->name);
			$goods_name_arr['uk'] = $goods_name;
			$goods_name_arr['ru'] = $goods_name;
			$goods_name = json_encode($goods_name_arr, JSON_UNESCAPED_UNICODE);
			$goods_name = str_replace("'", "\'", $goods_name);

			$goods_country = test_request($offer->country);
			if (empty($goods_country)) $goods_country = '-';

			$goods_vendor = test_request($offer->vendor);
			if (empty($goods_vendor)) $goods_vendor = '-';

			if (count($offer->param) > 0) {

				$template_uk = array();
				$template_ru = array();
				$template = array();

				foreach ($offer->param as $param) {

					$param_name_uk = test_request($param['name']);
					$param_value_uk = test_request($param);

					$param_name_ru = $param_name_uk;
					$param_value_ru = $param_value_uk;

					$template_uk[$param_name_uk] = $param_value_uk;
					$template_ru[$param_name_ru] = $param_value_ru;

				}

				$template_uk['Производитель'] = $goods_vendor;
				$template_ru['Производитель'] = $goods_vendor;
				
				$template_uk['Страна производитель'] = $goods_country;
				$template_ru['Страна производитель'] = $goods_country;

				$template['uk'] = $template_uk;
				$template['ru'] = $template_ru;

				$template = json_encode($template, JSON_UNESCAPED_UNICODE);
				$goods_template = str_replace("'", "\'", $template);

			} else {

				$goods_template = "{\"uk\":{\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\"},\"ru\":{\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\"}}";

			}

			$description_uk = test_request($offer->description);
			$description_ru = test_request($offer->description);

			$goods_photo = json_decode($goods['photo'], true);

			$photo_count = 0;

			foreach ($goods_photo as $photo_value) {
				
				if ($photo_value != 'no_image.png') {
					$photo_count++;
				}

			}

			$photo = array();

			if (count($offer->picture) > 0) {

				if ($photo_count != count($offer->picture)) {

					$i = 0;

					foreach ($offer->picture as $picture) {

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

												$image = new SimpleImage();
												$image->load($uploaddir_filename);

												if ($image->getWidth() >= $image->getHeight()) {

													if ($image->getWidth() > 1024) {

														$image->resizeToWidth(1024);
														$image->save($uploaddir_filename);

													}

												} else {

													if ($image->getHeight() > 1024) {

														$image->resizeToHeight(1024);
														$image->save($uploaddir_filename);

													}

												}

												/*$uploaddir_filename_thumb = __DIR__ . '/../../images/goods_thumb/'.$filename_new;

												$image_thumb = new SimpleImage();
												$image_thumb->load($uploaddir_filename);

												if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {

													if ($image_thumb->getWidth() > 256) {

														$image_thumb->resizeToWidth(256);
														$image_thumb->save($uploaddir_filename_thumb);

													}

												} else {

													if ($image_thumb->getHeight() > 256) {

														$image_thumb->resizeToHeight(256);
														$image->save($uploaddir_filename_thumb);

													}

												}*/

											}

											$photo['img'.$i] = $filename_new;

										}

									}

								}

							}

						} else {

							if (!getimagesize($uploaddir_filename)) {

								if (file_exists($uploaddir_filename)) unlink($uploaddir_filename);

							}

						}

						$i++;

					}
					
				} else {

					//$photo = $goods_photo;

				}

			} else {

				$photo['img0'] = 'no_image.png';

			}

			$photo_str = json_encode($photo);
			
			$goods_video = '';
			$goods_export = '';

			$goods_keys = test_request($offer->keywords);
			$goods_keys_arr['uk'] = $goods_keys;
			$goods_keys_arr['ru'] = $goods_keys;
			$goods_keys = json_encode($goods_keys_arr, JSON_UNESCAPED_UNICODE);
			$goods_keys = str_replace("'", "\'", $goods_keys);

			$category_id = test_request($offer->categoryId);
			$category_id = intval($category_id);

			$goods_id = $goods['id'];

			if ($count_goods > 0) {

				if (!empty($photo)) {

					$sql = "UPDATE `goods` SET `photo`='{$photo_str}',
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=407";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

			} else {

				$sql = "SELECT `linkname` FROM `catalog` WHERE `user_id`=407 AND `group_id`='{$category_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$catalog_category = mysqli_fetch_assoc($query);
				$catalog_category_linkname = $catalog_category['linkname'];

				$sql = "INSERT INTO `goods` SET `user_id`=407,
													`vendor_id`='{$goods_vendor_id}',
													`vendor_code`='{$goods_vendor_code}',
													`category`='{$catalog_category_linkname}',
													`name`='{$goods_name}',
													`parameters`='{$goods_template}',
													`photo`='{$photo_str}',
													`video`='{$goods_video}',
													`keys`='{$goods_keys}',
													`export`='{$goods_export}',
													`groups`='{\"top\":0,\"new\":0}',
													`availability`=0,
													`currency`=1,
													`currency_top_kurs`=1,
													`price_agent`=0,
													`price_purchase`=0,
													`price_sale`=0,
													`status`=0,
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

			}

			$n++;

			//echo $n."\n";

		}

		$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=407";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=407";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	} else {

	    //exit('Не удалось открыть файл "'.$xml_file.'".');
	    file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/himoto.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

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

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>