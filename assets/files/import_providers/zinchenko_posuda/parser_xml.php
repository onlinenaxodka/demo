<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../../config.php';
include_once __DIR__ . '/../../../../include/libs/classSimpleImage.php';

$xml_file = __DIR__ . '/import/Price.xml';


	if (file_exists($xml_file)) {
	    
	    $xml = simplexml_load_file($xml_file);

		$uploaddir_import_images = __DIR__ . '/import/Images/';

	    $n = 0;

		foreach ($xml->shop->offers->offer as $offer) {

			$goods_vendor_id = test_request($offer['id']);

			$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);

			$goods_vendor_code = test_request($offer->vendorCode);
			
			$goods_name = test_request($offer->name);
			$goods_name_arr['uk'] = $goods_name;
			$goods_name_arr['ru'] = $goods_name;
			$goods_name = json_encode($goods_name_arr, JSON_UNESCAPED_UNICODE);
			$goods_name = str_replace("'", "\'", $goods_name);

			$goods_template = "{\"uk\":{\"Страна производитель\":\"-\",\"Бренд\":\"-\"},\"ru\":{\"Страна производитель\":\"-\",\"Бренд\":\"-\"}}";

			$goods['photo'] = json_decode($goods['photo'], true);

			$category = 'tovari_dlya_doma_c_foto';

			if (count($offer->picture) > 0 and $count_goods == 0) {
//$goods['photo']['img0'] == 'no_image.png';				
				$i = 0;
				
				foreach ($offer->picture as $picture) {

					$filename = $picture;

					$uploaddir_import_images_filename = $uploaddir_import_images.$filename;

					if (file_exists($uploaddir_import_images_filename)) {

						$filename_time = time() .'_' . $n . '_' . $i;
						$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);
						
						$uploaddir = __DIR__ . '/../../../images/goods/';

								$image = new SimpleImage();
								$image->load($uploaddir_import_images_filename);
								if ($image->getWidth() >= $image->getHeight()) {
									if ($image->getWidth() > 1920) {
										$image->resizeToWidth(1920);
									}
								} else {
									if ($image->getHeight() > 1920) {
										$image->resizeToHeight(1920);
									}
								}
								
								$image->save($uploaddir.$filename_new);

						$uploaddir = __DIR__ . '/../../../images/goods_thumb/';

								$image_thumb = new SimpleImage();
								$image_thumb->load($uploaddir_import_images_filename);
								if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {
									if ($image_thumb->getWidth() > 300) {
										$image_thumb->resizeToWidth(300);
									}
								} else {
									if ($image_thumb->getHeight() > 300) {
										$image_thumb->resizeToHeight(300);
									}
								}
								
								$image_thumb->save($uploaddir.$filename_new);

						$photo['img'.$i] = $filename_new;

					} else {

						$photo['img'.$i] = 'no_image.png';

						$category = 'tovari_dlya_doma_bez_foto';

					}

					$i++;

				}

			} else {

				$photo['img0'] = 'no_image.png';

				$category = 'tovari_dlya_doma_bez_foto';

			}

			$goods_photo = json_encode($photo);
			
			$goods_video = '';
			$goods_export = '';

			$goods_availability = test_request($offer->quantity_in_stock);
			$goods_availability = intval($goods_availability);

			if ($goods_availability > 0) {
				$status = 1;
			} else {
				$status = 0;
			}

			$goods_currency = 1;
			$goods_currency_top_kurs = 1;

			$price_purchase = test_request($offer->price);
			$price_purchase = floatval($price_purchase);
			$price_purchase = number_format($price_purchase, 2, '.', '');

			if ($price_purchase == 0) $status = 0;

			$price_sale = $price_purchase * 0.41 + $price_purchase;
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

				$sql = "UPDATE `goods` SET `availability`={$goods_availability},
											`currency`='{$goods_currency}',
											`currency_top_kurs`='{$goods_currency_top_kurs}',
											`price_agent`='{$price_agent}',
											`price_purchase`='{$price_purchase}',
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=1799";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				$sql = "INSERT INTO `goods` SET `user_id`=1799,
													`vendor_id`='{$goods_vendor_id}',
													`vendor_code`='{$goods_vendor_code}',
													`category`='{$category}',
													`name`='{$goods_name}',
													`parameters`='{$goods_template}',
													`photo`='{$goods_photo}',
													`video`='{$goods_video}',
													`export`='{$goods_export}',
													`groups`='{\"top\":0,\"new\":0}',
													`availability`={$goods_availability},
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

			}

			$n++;

		}

		$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=1799";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=1799";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
		$time = number_format($time, 3, '.', '');
		echo $time." sec.\n";

	} else {

	    file_put_contents(__DIR__."/zinchenko_posuda.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

	}

?>