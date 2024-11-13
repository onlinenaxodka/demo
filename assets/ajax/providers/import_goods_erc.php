<?php

//manually run
$show_ids = true;

$start = microtime(true);

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

//$url_xml_file = 'https://api.erc.ua/api/erc/wares.xml?token=40a164c0684853212f4ed7f308539a440c64e2df7a245a07';

//ru
//$url_xml_file = 'https://api.erc.ua/api/erc/wares.xml?token=2ed57764f570537a84728e3e0ad8a89035de3bc247fac943';
//uk
$url_xml_file = 'https://api.erc.ua/api/erc/wares.xml?token=797e90f8ad4b3951fe593f2d502f484a10a835d340a26f25';

$xml_file = __DIR__ . '/../../../data/files/import/erc_wares.xml';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url_xml_file);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

file_put_contents($xml_file, $response);


if (file_exists($xml_file)) {

	$xml = simplexml_load_file($xml_file);

	include_once __DIR__ . '/include_erc/categories.php';

	$sql = "SELECT `vendor_id`, `photo` FROM `goods` WHERE `user_id` = 5856";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$goods_vendors = array_column(mysqli_fetch_all($query, MYSQLI_ASSOC), 'photo', 'vendor_id');

	$n = 0;

	$up_goods_vendors = [];

	foreach ($xml->wares->ware as $ware) {

		$vendor_id = test_request($ware['id']);
		$vendor_id = intval($vendor_id);

		$vendor_code = test_request($ware->sku->code);
		$vendor_code = str_replace("'", "\'", $vendor_code);

		$name = test_request($ware->title);
		$name_arr['uk'] = $name;
		$name_arr['ru'] = $name;
		$name = json_encode($name_arr, JSON_UNESCAPED_UNICODE);
		$name = str_replace("'", "\'", $name);

		include __DIR__ . '/include_erc/parameters.php';

		$description_uk = test_request($ware->description);
		$description_uk = str_replace("'", "\'", $description_uk);
		$description_ru = test_request($ware->description);
		$description_ru = str_replace("'", "\'", $description_ru);

		$category_id = test_request($ware->categoryId);
		$category_id = intval($category_id);

		/*$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$vendor_id}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods = mysqli_num_rows($query);*/

		//if ($count_goods > 0) $goods = mysqli_fetch_assoc($query);

		include __DIR__ . '/include_erc/photos.php';

		/*if (!mysqli_ping($db)) {
			$db = mysqli_connect($host, $user, $password, $dbname);
		}*/

		if (array_key_exists($vendor_id, $goods_vendors)) {

			$up = 0;

			//$goods_id = $goods['id'];

			/*$goods['parameters'] = json_decode($goods['parameters'], true);
			$goods['parameters'] = json_encode($goods['parameters'], JSON_UNESCAPED_UNICODE);
			$goods['parameters'] = str_replace("'", "\'", $goods['parameters']);

			if ($goods['parameters'] != $template) {

				$sql = "UPDATE `goods` SET `parameters`='{$template}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if ($show_ids) {
					echo $n." - ".$vendor_id." (update template)\n";
					if (empty($template)) echo "empty template\n";
				}

				$up++;

			}*/

			if ((!empty($photo) && count($photo) > 1) || (count($photo) === 1 && $photo['img0'] != 'no_image.png')) {

				$up_goods_vendors[$vendor_id] = $photo_str;

				/*$sql = "UPDATE `goods` SET `photo`='{$photo_str}' WHERE `vendor_id`='{$vendor_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));*/

				if ($show_ids) echo $n." - ".$vendor_id." (update photo)\n";

				$up++;

			}

			if ($show_ids && $up > 0) echo "\n";

		} else {

			$sql = "SELECT `linkname` FROM `catalog` WHERE `user_id`=5856 AND `group_id`='{$category_id}' LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$catalog_category = mysqli_fetch_assoc($query);
			$catalog_category_linkname = $catalog_category['linkname'];

			$sql = "INSERT INTO `goods` SET `user_id`=5856,
											`vendor_id`='{$vendor_id}',
											`vendor_code`='{$vendor_code}',
											`category`='{$catalog_category_linkname}',
											`name`='{$name}',
											`parameters`='{$template}',
											`photo`='{$photo_str}',
											`video`='',
											`export`='',
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

			$goods_vendors[$vendor_id] = $photo_str;

			if ($show_ids) echo $n." - ".$vendor_id." (create)\n";

		}

		$n++;

		if (count($up_goods_vendors) == 100) {
			if (!mysqli_ping($db)) {
				$db = mysqli_connect($host, $user, $password, $dbname);
			}

			foreach ($up_goods_vendors as $key_vendor_id => $value_photo_str) {
				$sql = "UPDATE `goods` SET `photo`='{$value_photo_str}' WHERE `vendor_id`='{$key_vendor_id}' AND `user_id`=5856";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			}

			$up_goods_vendors = [];
		}

		//if ($n == 5000) break;

		echo "\n".$n." items [Used memory: ".convert(memory_get_usage())."]\n";

	}

}

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

function convert($size) {
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

?>