<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$xml_file = __DIR__ . '/../../files/xml_providers/mobiking/import_xml/mobiking.xml';

$finish_import_images = __DIR__ . '/../../files/xml_providers/mobiking/finish_import_images.txt';
$finish_import_images_status = file_get_contents($finish_import_images);

if ($finish_import_images_status == 'finish') {

	if (file_exists($xml_file)) {
	    
	    $xml = simplexml_load_file($xml_file);

	    $uploaddir_import_images = __DIR__ . '/../../files/xml_providers/mobiking/import_images/';
	    $uploaddir_import_images_thumb = __DIR__ . '/../../files/xml_providers/mobiking/import_images_thumb/';

	    $top_kurs = test_request($xml->Курс);
	    $top_kurs = floatval($top_kurs);

	    $n = 0;
	    $k = 0;

		foreach ($xml->Item as $item) {

			$k++;

			if ($k > 3500 and $k <= 7000) {

			$goods_vendor_id = test_request($item->Код);

			$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' AND `user_id`=5184 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);

			$goods_vendor_code = test_request($item->Артикул);
			
			$goods_name = test_request($item->Наименование);
			$goods_name_arr['uk'] = $goods_name;
			$goods_name_arr['ru'] = $goods_name;
			$goods_name = json_encode($goods_name_arr, JSON_UNESCAPED_UNICODE);
			$goods_name = str_replace("'", "\'", $goods_name);

			$goods_country = '-';

			$goods_vendor = test_request($item->Производитель);
			if (empty($goods_vendor)) $goods_vendor = '-';

			$goods_availability = test_request($item->Остаток);
			$goods_availability = intval($goods_availability);

			if ($goods_availability > 0) {
				$status = 1;
			} else {
				$status = 0;
			}

			$price_purchase = test_request($item->ЦенаЗакупки);
			$price_purchase = floatval($price_purchase);
			$price_purchase = $price_purchase * $top_kurs;
			$price_purchase = number_format($price_purchase, 2, '.', '');

			$price_sale = test_request($item->ЦенаРРЦ);
			$price_sale = floatval($price_sale);
			$price_sale = number_format($price_sale, 2, '.', '');

			//$template_opt = '';

			if ($price_purchase <= 0 or $price_sale <= 0) {

				$status = 0;

			} else {

				$price_purchase_40 = $price_purchase;
				$price_purchase_40_d = $price_purchase_40 + ($price_sale - $price_purchase_40) * 0.8;

				if ($price_purchase_40_d < $price_sale) {

					$price_purchase = $price_purchase_40;

				} else {

					if ($price_purchase > $price_sale) $status = 0;

					//$template_opt = 'Этот товар можно добавлять только в заказ из категории Гаджеты на сумму от 100$ или без всяких условий просто оплачивать дополнительно 40 грн за доставку.';

				}

			}

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

			if (count($item->Свойства->ItemSv) > 0) {

				$template_uk = array();
				$template_ru = array();
				$template = array();

				/*if (!empty($template_opt)) {

					$template_uk['Важно!'] = $template_opt;
					$template_ru['Важно!'] = $template_opt;

				}*/

				foreach ($item->Свойства->ItemSv as $param) {

					$param_name_uk = test_request($param['Value']);
					$param_value_uk = test_request($param['Name']);

					$param_name_ru = $param_name_uk;
					$param_value_ru = $param_value_uk;

					if ($param_name_uk != 'Категория товара' and $param_name_uk != 'Подкатегория товаров') {

						$template_uk[$param_name_uk] = $param_value_uk;
						$template_ru[$param_name_ru] = $param_value_ru;

					}

					//if ($count_goods == 0) {

						if ($param_name_uk == 'Категория товара') {

							$sql_catalog = "SELECT * FROM `catalog` WHERE `name_ru`='{$param_value_uk}' AND `level_id`=478 LIMIT 1";
							$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
							$count_catalog = mysqli_num_rows($query_catalog);
							$catalog = mysqli_fetch_assoc($query_catalog);

							if ($count_catalog > 0) {

								$level_id = $catalog['id'];

							}

						}

						if ($param_name_uk == 'Подкатегория товаров') {

							$sql_catalog = "SELECT * FROM `catalog` WHERE `name_ru`='{$param_value_uk}' AND `level_id`='{$level_id}' LIMIT 1";
							$query_catalog = mysqli_query($db, $sql_catalog) or die(mysqli_error($db));
							$count_catalog = mysqli_num_rows($query_catalog);
							$catalog = mysqli_fetch_assoc($query_catalog);

							if ($count_catalog > 0) {

								$category_linkname = $catalog['linkname'];

							}

						}

					//}

				}

				$template_uk['Производитель'] = $goods_vendor;
				$template_ru['Производитель'] = $goods_vendor;
				
				$template_uk['Страна производитель'] = $goods_country;
				$template_ru['Страна производитель'] = $goods_country;

				$template_uk['Гарантия'] = '3 мес.';
				$template_ru['Гарантия'] = '3 мес.';

				/*$template_uk['Предоплата 100%'] = 'оплата полной стоимости товара при оформлении заказа';
				$template_ru['Предоплата 100%'] = 'оплата полной стоимости товара при оформлении заказа';*/

				$template['uk'] = $template_uk;
				$template['ru'] = $template_ru;

				$template = json_encode($template, JSON_UNESCAPED_UNICODE);
				$goods_template = str_replace("'", "\'", $template);

			} else {

				$goods_template = "{\"uk\":{\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\",\"Гарантия\":\"3 мес.\"},\"ru\":{\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\",\"Гарантия\":\"3 мес.\"}}";

				if (!empty($template_opt)) {

					$goods_template = "{\"uk\":{\"Важно!\":\"".$template_opt."\",\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\",\"Гарантия\":\"3 мес.\"},\"ru\":{\"Важно!\":\"".$template_opt."\",\"Производитель\":\"".$goods_vendor."\",\"Страна производитель\":\"".$goods_country."\",\"Гарантия\":\"3 мес.\"}}";

				}

			}

			$description_uk = test_request($item->ДопОписание);
			$description_ru = test_request($item->ДопОписание);

			if (!empty($item->ОсновноеИзображение)) {

				$picture = $item->ОсновноеИзображение;

				$filename = basename($picture);

				if (file_exists($uploaddir_import_images.$filename) and file_exists($uploaddir_import_images_thumb.$filename)) {

					$filename_time = time() .'_' . $n . '_' . $i;
					$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);
							
					$uploaddir = __DIR__ . '/../../images/goods/';
					rename($uploaddir_import_images.$filename, $uploaddir.$filename_new);

					/*$uploaddir = __DIR__ . '/../../images/goods_thumb/';
					rename($uploaddir_import_images_thumb.$filename, $uploaddir.$filename_new);*/

					$photo['img0'] = $filename_new;

				} else {

					$photo['img0'] = 'no_image.png';

				}

			} else {

				$photo['img0'] = 'no_image.png';

			}

			$goods_photo = json_encode($photo);
			
			$goods_video = '';
			$goods_export = '';

			$goods_keys = '';

			$goods_currency = 1;
			$goods_currency_top_kurs = 1;

			$goods_id = $goods['id'];

			if ($count_goods > 0) {

				$sql = "UPDATE `goods` SET `availability`='{$goods_availability}',
											`price_agent`='{$price_agent}',
											`price_purchase`='{$price_purchase}',
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5184";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				$sql = "INSERT INTO `goods` SET `user_id`=5184,
													`vendor_id`='{$goods_vendor_id}',
													`vendor_code`='{$goods_vendor_code}',
													`category`='{$category_linkname}',
													`name`='{$goods_name}',
													`parameters`='{$goods_template}',
													`photo`='{$goods_photo}',
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

			$n++;

			}

		}

		header('Location: /admin/goods_upload/?mobiking_part_file_2=success');
		exit;

		/*$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=5184";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=5184";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

	} else {

	    //exit('Не удалось открыть файл "'.$xml_file.'".');
	    file_put_contents("../../files/xml_providers/mobiking/mobiking.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

	}

}

?>