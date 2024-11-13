<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = 'https://distributions.com.ua/price_list/products.xlsx?t=ff19539378168b72cb52fef3c2d57e27';

$path = __DIR__ . '/../../../data/files/import/himoto.xlsx';

file_put_contents($path, file_get_contents($excel_file));

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($path)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($path);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	
	$n = 0;

	for ($iRow = 2; $iRow <= $oCells->getHighestRow(); $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $vendor_id = test_request($oCell->getValue());

		$oCell = $oCells->get('G'.$iRow);
		if ($oCell) $price_purchase = test_request($oCell->getValue());
		$price_purchase = floatval($price_purchase);
		$price_purchase = number_format($price_purchase, 2, '.', '');

		$oCell = $oCells->get('E'.$iRow);
		if ($oCell) $price_sale = test_request($oCell->getValue());
		$price_sale = floatval($price_sale);
		$price_sale = number_format($price_sale, 2, '.', '');

		$oCell = $oCells->get('H'.$iRow);
		if ($oCell) $availability = test_request($oCell->getValue());
		$availability = intval($availability);

		$oCell = $oCells->get('P'.$iRow);
		if ($oCell) $group_str = test_request($oCell->getValue());
		$group = array();
		$group['top'] = 0;
		$group['new'] = 0;
		if ($group_str == 'Хит продаж!') $group['top'] = 1;
		if ($group_str == 'Новинка') $group['new'] = 1;
		$group = json_encode($group);

		if ($availability > 0) {
			$status = 1;
		} else {
			$status = 0;
		}

		$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$vendor_id}' AND `user_id`=407 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

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

		if ($count_goods > 0) {

			$sql = "UPDATE `goods` SET `groups`='{$group}',
										`availability`='{$availability}',
										`price_agent`='{$price_agent}',
										`price_purchase`='{$price_purchase}',
										`price_sale`='{$price_sale}',
										`status`='{$status}',
										`status_import`=1,
										`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=407";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$n++;

			//echo $n."\n";

		}

	}

	$sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=407";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	$sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=407";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

}

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>