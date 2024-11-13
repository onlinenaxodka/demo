<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$xml_file = 'https://distributions.com.ua/price_list/products.xml?t=ff19539378168b72cb52fef3c2d57e27';

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

$finish_import_images = __DIR__ . '/../../files/xml_providers/himoto/finish_import_images.txt';
$finish_import_images_status = file_get_contents($finish_import_images);

if ($finish_import_images_status == 'finish') {

	if ($exists_xml_file == true) {
	    
	    $xml = simplexml_load_file($xml_file);

	    $n = 0;

		foreach ($xml->products->offers->offer as $offer) {

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

			if (count($offer->picture) > 0 and $count_goods == 0) {
				
				$i = 0;
				
				foreach ($offer->picture as $picture) {

					$filename = basename($picture);

					if (file_exists($uploaddir_import_images.$filename) and file_exists($uploaddir_import_images_thumb.$filename)) {

						$filename_time = time() .'_' . $n . '_' . $i;
						$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);
							
						$uploaddir = __DIR__ . '/../../images/goods/';
						rename($uploaddir_import_images.$filename, $uploaddir.$filename_new);
						//if (copy($uploaddir_import_images.$filename, $uploaddir.$filename_new)) unlink($uploaddir_import_images.$filename);

						$uploaddir = __DIR__ . '/../../images/goods_thumb/';
						rename($uploaddir_import_images_thumb.$filename, $uploaddir.$filename_new);
						//if (copy($uploaddir_import_images_thumb.$filename, $uploaddir.$filename_new)) unlink($uploaddir_import_images_thumb.$filename);

						$photo['img'.$i] = $filename_new;

					} else {

						$photo['img'.$i] = 'no_image.png';

					}

					$i++;

				}

			} else {

				$photo['img0'] = 'no_image.png';

			}

			$goods_photo = json_encode($photo);
			
			$goods_video = '';
			$goods_export = '';

			$goods_keys = test_request($offer->keywords);

			$goods_available = test_request($offer['available']);

			if ($goods_available == 'true') {
				$status = 1;
			} else {
				$status = 0;
			}

			if ($offer->currencyId == 'UAH') {
				
				$goods_currency = 1;
				$goods_currency_top_kurs = number_format(test_request($currency_uah), 2, '.', '');

			} elseif ($offer->currencyId == 'USD') {
				
				$goods_currency = 2;
				$goods_currency_top_kurs = number_format(test_request($currency_usd), 2, '.', '');

			} elseif ($offer->currencyId == 'EUR') {
				
				$goods_currency = 3;
				$goods_currency_top_kurs = number_format(test_request($currency_eur), 2, '.', '');

			} else {
				
				$goods_currency = 1;
				$goods_currency_top_kurs = number_format(test_request($currency_uah), 2, '.', '');

			}

			$price_sale = test_request($offer->price);
			$price_sale = floatval($price_sale);
			$price_sale = number_format($price_sale, 2, '.', '');

			$goods_id = $goods['id'];

			if ($count_goods > 0) {

				$sql = "UPDATE `goods` SET `availability`=0,
											`currency`='{$goods_currency}',
											`currency_top_kurs`='{$goods_currency_top_kurs}',
											`price_agent`=0,
											`price_purchase`=0,
											`price_sale`='{$price_sale}',
											`status`='{$status}',
											`status_import`=1,
											`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=407";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} else {

				$sql = "INSERT INTO `goods` SET `user_id`=407,
													`vendor_id`='{$goods_vendor_id}',
													`vendor_code`='{$goods_vendor_code}',
													`category`='himoto',
													`name`='{$goods_name}',
													`parameters`='{$goods_template}',
													`photo`='{$goods_photo}',
													`video`='{$goods_video}',
													`keys`='{$goods_keys}',
													`export`='{$goods_export}',
													`groups`='{\"top\":0,\"new\":0}',
													`availability`=0,
													`currency`='{$goods_currency}',
													`currency_top_kurs`='{$goods_currency_top_kurs}',
													`price_agent`=0,
													`price_purchase`=0,
													`price_sale`='{$price_sale}',
													`status`=1,
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

		$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=407";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=407";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	} else {

	    //exit('Не удалось открыть файл "'.$xml_file.'".');
	    file_put_contents("../../files/xml_providers/himoto/himoto.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

	}

}

?>