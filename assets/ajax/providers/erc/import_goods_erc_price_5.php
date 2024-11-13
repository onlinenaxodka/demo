<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

$excel_file = __DIR__ . '/../../files/import_providers/erc/import_files/erc_easy_prices.xlsx';

require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');

if (file_exists($excel_file)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($excel_file);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	$highestRow = $oSpreadsheet->getActiveSheet()->getHighestRow();
	$highestRow = intval($highestRow);

	//$n = 0;

	for ($iRow = 28001; $iRow <= $highestRow; $iRow++) {

		$oCell = $oCells->get('A'.$iRow);
		if ($oCell) $oCell->getValue();
		$vendor_code = test_request($oCell->getValue());

		$oCell = $oCells->get('B'.$iRow);
		if ($oCell) $oCell->getValue();
		$price_sale = test_request($oCell->getValue());
		$price_sale = floatval($price_sale);
		$price_sale = number_format($price_sale, 2, '.', '');

		$oCell = $oCells->get('C'.$iRow);
		if ($oCell) $oCell->getValue();
		$price_purchase = test_request($oCell->getValue());
		$price_purchase = floatval($price_purchase);

		$oCell = $oCells->get('D'.$iRow);
		if ($oCell) $oCell->getValue();
		$price_purchase_ddp = test_request($oCell->getValue());
		$price_purchase_ddp = intval($price_purchase_ddp);

		if ($price_purchase_ddp == 0) {
			$price_purchase = $price_purchase * floatval($api_exchange_rate_usd['sale']);
		}

		if ($price_purchase > $price_sale) {
			$price_purchase = $price_sale - $price_sale * 0.05;
		}

		$price_purchase = number_format($price_purchase, 2, '.', '');

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

		$sql = "SELECT * FROM `goods` WHERE `vendor_code`='{$vendor_code}' AND `user_id`=5856 LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$goods = mysqli_fetch_assoc($query);
		$count_goods = mysqli_num_rows($query);

		$goods_id = $goods['id'];

		if ($count_goods > 0) {

			$sql = "UPDATE `goods` SET `currency`='1',
										`currency_top_kurs`='1',
										`price_agent`='{$price_agent}',
										`price_purchase`='{$price_purchase}',
										`price_sale`='{$price_sale}',
										`updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=5856";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//$n++;

		}

	}

}

//echo $n.'<br>';

?>