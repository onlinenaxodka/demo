<?php

$file_url = 'https://httpclient.mobiking.com.ua:9443/bb629b72bae94fa8bb2fbb55160cfef6_8a34d45fe7d64be78066d98e1ccd5599.xml';
$file_local = __DIR__ . '/../../../data/files/import/mobiking.xml';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $file_url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

file_put_contents($file_local, $response);
die();

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

if (file_exists($file_local)) {
	    
	$xml = simplexml_load_file($file_local);

	$top_kurs = test_request($xml->currency);
	$top_kurs = floatval($top_kurs);

	/*$currency_uah = 0;
	$currency_usd = 0;
	$currency_eur = 0;

	switch ($xml->currency['code']) {
		case 'UAH':
			$currency_uah = $top_kurs;
			break;
		case 'USD':
			$currency_usd = $top_kurs;
			break;
		case 'EUR':
			$currency_eur = $top_kurs;
			break;
		default:
			$currency_uah = $top_kurs;
			break;
	}*/

	$categories = array();

	foreach ($xml->items->item as $item) {

		foreach ($item->param as $category) {

			if ($category['name'] == 'Категория товара') $category_main = strval($category);
			if ($category['name'] == 'Подкатегория товаров') $category_main_item = strval($category);

		}

		$category_main = str_replace("'", '\'', $category_main);

		if (!in_array($category_main, array_keys($categories))) {
			$categories[$category_main][] = $category_main_item;
		} else {
			if (!in_array($category_main_item, $categories[$category_main])) {
				$categories[$category_main][] = $category_main_item;
			}
		}

	}

	foreach ($categories as $key => $value) {

		$sql = "SELECT `id` FROM `catalog` WHERE `name_ru`='{$key}' AND `level_id`=478 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_catalog = mysqli_num_rows($query);
		
		if ($count_catalog > 0) {

			$catalog = mysqli_fetch_assoc($query);

			$level_id = $catalog['id'];

		} else {

			$linkname = GenerateLinkname();

	    	$sql = "INSERT INTO `catalog` SET `level_id`=478,
	    										`user_id`=5184,
												`linkname`='{$linkname}',
												`name_uk`=\"{$key}\",
												`name_ru`=\"{$key}\",
												`img`='no_image.png',
												`rate`='0.5',
												`buffer`=1,
												`updated`='{$current_date}',
												`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			
			$level_id = mysqli_insert_id($db);

		}

		foreach ($value as $value_item) {

			$sql = "SELECT * FROM `catalog` WHERE `name_ru`='{$value_item}' AND `level_id`='{$level_id}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$count_catalog_in = mysqli_num_rows($query);

			if ($count_catalog_in == 0) {

				$linkname = GenerateLinkname();

		    	$sql = "INSERT INTO `catalog` SET `level_id`='{$level_id}',
													`user_id`=5184,
													`linkname`='{$linkname}',
													`name_uk`=\"{$value_item}\",
													`name_ru`=\"{$value_item}\",
													`img`='no_image.png',
													`rate`='0.5',
													`buffer`=1,
													`updated`='{$current_date}',
													`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

		}

	}

	$n = 0;
	$photo_up_cnt = 0;
	$param_up_cnt = 0;
	$goods_in_cnt = 0;

	foreach ($xml->items->item as $item) {

		$goods_availability = 0;
		$status = 0;

		if ($item['available'] == 'true') {

			//$goods_availability = rand(1, 10);
			$goods_availability = test_request($item->quantity_in_stock);
			$status = 1;

		}

		if ($status == 1) {
		
		$goods_vendor_id = test_request($item['id']);

		$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' AND `user_id`=5184 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_vendor_code = test_request($item->vendorCode);
			
		$goods_name = test_request($item->name);
		$goods_name_arr['uk'] = $goods_name;
		$goods_name_arr['ru'] = $goods_name;
		$goods_name = json_encode($goods_name_arr, JSON_UNESCAPED_UNICODE);
		$goods_name = str_replace("'", "\'", $goods_name);

		$goods_country = '-';

		$goods_vendor = test_request($item->vendor);
		if (empty($goods_vendor)) $goods_vendor = '-';

		/*$goods_availability = test_request($item->Остаток);
		$goods_availability = intval($goods_availability);*/

		/*if ($goods_availability > 0) {
			$status = 1;
		} else {
			$status = 0;
		}*/

		$price_purchase = test_request($item->bnprice);
		$price_purchase = floatval($price_purchase);
		$price_purchase = $price_purchase * $top_kurs;
		$price_purchase = number_format($price_purchase, 2, '.', '');
		//$price_purchase = $price_purchase + 45;

		$price_sale = test_request($item->price);
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

		$category_linkname = 'assorti_mix';

		$template_uk = array();
		$template_ru = array();
		$template = array();

		foreach ($item->param as $param) {

			$param_name_uk = test_request($param['name']);
			$param_name_uk = str_replace("'", '\'', $param_name_uk);
			$param_value_uk = test_request($param);

			$param_name_ru = $param_name_uk;
			$param_value_ru = $param_value_uk;

			if ($param_name_uk != 'Категория товара' and $param_name_uk != 'Подкатегория товаров') {

				$template_uk[$param_name_uk] = $param_value_uk;
				$template_ru[$param_name_ru] = $param_value_ru;

			}

			if ($param_name_uk == 'Категория товара') {

				$sql = "SELECT `id` FROM `catalog` WHERE `name_ru`='{$param_value_uk}' AND `level_id`=478 LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$count_catalog = mysqli_num_rows($query);

				if ($count_catalog > 0) {

					$catalog = mysqli_fetch_assoc($query);

					$level_id = $catalog['id'];

				}

			}

			if ($param_name_uk == 'Подкатегория товаров') {

				$sql = "SELECT `linkname` FROM `catalog` WHERE `name_ru`='{$param_value_uk}' AND `level_id`='{$level_id}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$count_catalog = mysqli_num_rows($query);

				if ($count_catalog > 0) {

					$catalog = mysqli_fetch_assoc($query);

					$category_linkname = $catalog['linkname'];

				}

			}

		}

		$template_uk['Производитель'] = $goods_vendor;
		$template_ru['Производитель'] = $goods_vendor;
				
		$template_uk['Страна производитель'] = $goods_country;
		$template_ru['Страна производитель'] = $goods_country;

		$template['uk'] = $template_uk;
		$template['ru'] = $template_ru;

		$template = json_encode($template, JSON_UNESCAPED_UNICODE);
		$goods_template = str_replace("'", "\'", $template);

		$description_uk = test_request($item->description);
		$description_uk = str_replace("'", "\'", $description_uk);

		$description_ru = test_request($item->description);
		$description_ru = str_replace("'", "\'", $description_ru);

			$goods_photo = json_decode($goods['photo'], true);

			$photo_count = 0;

			foreach ($goods_photo as $photo_value) {
				
				if ($photo_value != 'no_image.png') {
					$photo_count++;
				}

			}

			$photo = array();

			if (count($item->image) > 0) {

				if ($photo_count != count($item->image)) {

					$i = 0;

					foreach ($item->image as $picture) {

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

		$goods_currency = 1;
		$goods_currency_top_kurs = 1;

		$goods_id = $goods['id'];

			if ($count_goods > 0) {

				if (!empty($photo)) {

					$sql = "UPDATE `goods` SET `photo`='{$photo_str}' WHERE `id`='{$goods_id}' AND `user_id`=5184";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					
					$photo_up_cnt++;

				}

				if ($template != $goods['parameters']) {

					$sql = "UPDATE `goods` SET `parameters`='{$goods_template}' WHERE `id`='{$goods_id}' AND `user_id`=5184";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					
					$param_up_cnt++;

				}

				$sql = "UPDATE `goods` SET `availability`='{$goods_availability}',
											`currency`='{$goods_currency}',
											`currency_top_kurs`='{$goods_currency_top_kurs}',
											`price_agent`='{$price_agent}',
											`price_purchase`='{$price_purchase}',
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5184";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				//if ($status == 1) {

				if ($goods_availability > 0) {

				$sql = "INSERT INTO `goods` SET `user_id`=5184,
													`vendor_id`='{$goods_vendor_id}',
													`vendor_code`='{$goods_vendor_code}',
													`category`='{$category_linkname}',
													`name`='{$goods_name}',
													`parameters`='{$goods_template}',
													`photo`='{$photo_str}',
													`video`='',
													`keys`='',
													`export`='',
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

				$goods_in_cnt++;

				}

				//}

			}

		$n++;

		echo $n." - ".$goods_vendor_id."\n";

		}

	}

	$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=5184";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = "UPDATE `goods` SET `status_import`=0 WHERE `user_id`=5184";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

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
echo $time." seconds - Items: ".$n.", Photo up: ".$photo_up_cnt.", Param up: ".$param_up_cnt.", Goods insert: ".$goods_in_cnt."\n";

?>