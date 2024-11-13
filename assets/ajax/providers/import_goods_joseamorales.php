<?php

header('Content-Type: text/html; charset=utf-8');

$file_url = 'https://jose-amorales.com.ua/products/exportNahodkaxml';
$file_local = __DIR__ . '/../../../data/files/import/jose_amorales.xml';

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

	/*$currency_uah = 0;
	$currency_usd = 0;
	$currency_eur = 0;

	foreach ($xml->shop->currencies->currency as $currency) {

		if ($currency['id'] == 'UAH') {
			$currency_uah = $currency['rate'];
		} elseif ($currency['id'] == 'USD') {
			$currency_usd = $currency['rate'];
		} elseif ($currency['id'] == 'EUR') {
			$currency_eur = $currency['rate'];
		}

	}*/

	foreach ($xml->shop->categories->category as $category) {

		$category_name = test_request($category);
		$category_name = str_replace("'", "\'", $category_name);

		$category_id = test_request($category['id']);
		$category_id = intval($category_id);

		$category_parent_id = test_request($category['parentId']);
		$category_parent_id = intval($category_parent_id);

		$sql = "SELECT `id` FROM `catalog` WHERE `user_id`=7625 AND `group_id`='{$category_id}' LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_catalog = mysqli_num_rows($query);

	    if ($count_catalog == 0) {

	    	$linkname = GenerateLinkname();

	    	$sql = "INSERT INTO `catalog` SET `level_id`=8800,
	    										`user_id`=7625,
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

	$sql_ce = "SELECT `id`, `group_id`, `group_parent_id` FROM `catalog` WHERE `user_id`=7625";
	$query_ce = mysqli_query($db, $sql_ce) or die(mysqli_error($db));

	if (mysqli_num_rows($query_ce) > 0) {
			
		while ($catalog_ce = mysqli_fetch_assoc($query_ce)) {
				
			if ($catalog_ce['group_parent_id'] > 0) {

				$catalog_ce_id = $catalog_ce['id'];

				$catalog_group_parent_id = $catalog_ce['group_parent_id'];

				$sql_ce_up = "SELECT `id` FROM `catalog` WHERE `user_id`=7625 AND `group_id`='{$catalog_group_parent_id}' LIMIT 1";
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

			$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' AND `user_id`=7625 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);
			
			$goods_name = test_request($offer->name);
			$goods_name_arr['uk'] = $goods_name;
			$goods_name_arr['ru'] = $goods_name;
			$goods_name = json_encode($goods_name_arr, JSON_UNESCAPED_UNICODE);
			$goods_name = str_replace("'", "\'", $goods_name);

			$goods_vendor_code = test_request($offer->model);
			if (empty($goods_vendor_code)) $goods_vendor_code = '-';

			$goods_size = test_request($offer->size);
			if (empty($goods_size)) $goods_size = '-';

			$template_uk = array();
			$template_ru = array();
			$template = array();

			/*$template_uk['Модель'] = $goods_model;
			$template_ru['Модель'] = $goods_model;*/

			foreach ($offer->param as $param) {

				$param_name_uk = test_request($param['name']);
				$param_value_uk = test_request($param);

				$param_name_ru = $param_name_uk;
				$param_value_ru = $param_value_uk;

				if ($param_name_uk != 'Бренд') {

					$template_uk[$param_name_uk] = $param_value_uk;
					$template_ru[$param_name_ru] = $param_value_ru;

				}

			}

			$template_uk['Размер'] = $goods_size;
			$template_ru['Размер'] = $goods_size;

			$template_uk['Страна производитель'] = 'Украина';
			$template_ru['Страна производитель'] = 'Украина';

			$template['uk'] = $template_uk;
			$template['ru'] = $template_ru;

			$template = json_encode($template, JSON_UNESCAPED_UNICODE);
			$goods_template = str_replace("'", "\'", $template);

			$description_uk = test_request($offer->description);
			$description_uk = str_replace("'", "\'", $description_uk);
			$description_uk = str_replace("тм «Jose Amorales»", "", $description_uk);

			$description_ru = test_request($offer->description);
			$description_ru = str_replace("'", "\'", $description_ru);
			$description_ru = str_replace("тм «Jose Amorales»", "", $description_ru);

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

			//$goods_keys = test_request($offer->keywords);
			// $goods_keys_arr['uk'] = $goods_keys;
			// $goods_keys_arr['ru'] = $goods_keys;
			// $goods_keys = json_encode($goods_keys_arr, JSON_UNESCAPED_UNICODE);
			// $goods_keys = str_replace("'", "\'", $goods_keys);
			$goods_keys = '';

			$category_id = test_request($offer->categoryId);
			$category_id = intval($category_id);

			/*switch ($offer->currencyId) {
				case 'UAH':
					$goods_currency = 1;
					$goods_currency_top_kurs = number_format(test_request($currency_uah), 2, '.', '');
					//$goods_rate_kurs = 1;
					break;
				case 'USD':
					$goods_currency = 2;
					$goods_currency_top_kurs = number_format(test_request($currency_usd), 2, '.', '');
					//$goods_rate_kurs = 25;
					break;
				case 'EUR':
					$goods_currency = 3;
					$goods_currency_top_kurs = number_format(test_request($currency_eur), 2, '.', '');
					//$goods_rate_kurs = 27.6;
					break;
				default:
					$goods_currency = 1;
					$goods_currency_top_kurs = number_format(test_request($currency_uah), 2, '.', '');
					//$goods_rate_kurs = 1;
					break;
			}*/

			$goods_currency = 1;
			$goods_currency_top_kurs = 1;

			$goods_availability = intval(test_request($offer->stock));
			//$goods_availability = rand(3,5);

			//$status = 1;

			$goods_available = test_request($offer['available']);

			if ($goods_available == 'true') {

				if ($goods_availability > 0) {
					
					$status = 1;

				} else {

					$status = 0;

				}
				
			} else {
				
				$status = 0;

			}

			/*$price_purchase = test_request($offer->price);
			$price_purchase = floatval($price_purchase);
			$price_purchase = number_format($price_purchase, 2, '.', '');			

			$price_sale = $price_purchase + $price_purchase * 0.3;
			$price_sale = number_format($price_sale, 2, '.', '');*/

			$price_purchase = test_request($offer->drop_price);
			$price_purchase = floatval($price_purchase);
			$price_purchase = number_format($price_purchase, 2, '.', '');
			
			$price_sale = test_request($offer->price);
			$price_sale = floatval($price_sale);
			$price_sale = number_format($price_sale, 2, '.', '');

			$price_agent = 0;

			if ($price_sale >= $price_purchase) {
				
				//if ($user_mentor['agent'] == 1) {

					$price_agent = $price_purchase;

					$price_margine_procent = ($price_sale - $price_purchase) * 0.04;

					if ($price_margine_procent > 0) {

						if ($price_purchase > 0 && $price_purchase <= 500) 
							$price_purchase_procent = $price_purchase * 0.05;
						elseif ($price_purchase > 500 && $price_purchase <= 1000) 
							$price_purchase_procent = $price_purchase * 0.04;
						elseif ($price_purchase > 1000 && $price_purchase <= 5000) 
							$price_purchase_procent = $price_purchase * 0.03;
						elseif ($price_purchase > 5000 && $price_purchase <= 10000) 
							$price_purchase_procent = $price_purchase * 0.02;
						elseif ($price_purchase > 10000) 
							$price_purchase_procent = $price_purchase * 0.01;

						$price_purchase_preview = $price_purchase + $price_purchase_procent;

						if ($price_purchase_procent > $price_margine_procent) 
							$price_purchase_preview = $price_purchase + $price_margine_procent;

						if ($price_purchase_preview > $price_agent and $price_purchase_preview < $price_sale) 
							$price_purchase = number_format($price_purchase_preview, 2, '.', '');

					}

				//}

			}

			$goods_id = $goods['id'];

			if ($count_goods > 0) {

				if (!empty($photo)) {

					$sql = "UPDATE `goods` SET `photo`='{$photo_str}' WHERE `id`='{$goods_id}' AND `user_id`=7625";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				if ($template != $goods['parameters']) {

					$sql = "UPDATE `goods` SET `parameters`='{$goods_template}' WHERE `id`='{$goods_id}' AND `user_id`=7625";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				$sql = "UPDATE `goods` SET `availability`='{$goods_availability}',
											`currency`='{$goods_currency}',
											`currency_top_kurs`='{$goods_currency_top_kurs}',
											`price_agent`='{$price_agent}',
											`price_purchase`='{$price_purchase}',
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=7625";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				if ($goods_availability > 0) {

				$sql = "SELECT `linkname` FROM `catalog` WHERE `user_id`=7625 AND `group_id`='{$category_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$catalog_category = mysqli_fetch_assoc($query);
				$catalog_category_linkname = $catalog_category['linkname'];

				$sql = "INSERT INTO `goods` SET `user_id`=7625,
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
													`availability`='{$goods_availability}',
													`currency`='{$goods_currency}',
													`currency_top_kurs`='{$goods_currency_top_kurs}',
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

				}

			}

			$n++;

			//echo $n." - ".$goods_vendor_id."\n";

		}

		$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=7625";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=7625";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

} else {

	//exit('Не удалось открыть файл "'.$xml_file.'".');
	//file_put_contents(__DIR__ . "/../../files/xml_providers/himoto/himoto.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);
	echo "Не удалось открыть файл ".$xml_file.".\n";

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