<?php

header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../../../include/libs/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

$dir = __DIR__ . '/../../../../data/files/import/';

if (file_exists($dir . 'zinchenko_posuda.xls')) {

$oReader = new Xls();

$oSpreadsheet = $oReader->load($dir . 'zinchenko_posuda.xls');
$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
	 
include_once __DIR__ . '/../../../../config.php';

$step = 500;
$total_products = $oCells->getHighestRow();
$current_page = $_GET['page'] ? intval($_GET['page']) : 1;
$total_pages = ceil($total_products / $step);

$start_each = 7;
if ($current_page > 1) $start_each = $current_page * $step - $step + 1;
$end_each = $current_page * $step;
if ($current_page == $total_pages) $end_each = $total_products;

for ($i=$start_each; $i < $end_each; $i++) {

    $vendor_code = test_request($oCells->get('A'.$i)->getValue());
    if ($vendor_code == null or $vendor_code == '') continue;

    $name = test_request($oCells->get('B'.$i)->getValue());
    $name = str_replace("\r\n", '', $name);
    $name = str_replace("\r", '', $name);
    $name = str_replace("\n", '', $name);
    $name = str_replace("\t", '', $name);
    $name_arr = array();
    $name_arr['uk'] = $name;
    $name_arr['ru'] = $name;
    $name = json_encode($name_arr, JSON_UNESCAPED_UNICODE);
    $name = str_replace("'", "\'", $name);

    $stock_quantity = test_request($oCells->get('D'.$i)->getValue());
    $stock_quantity = intval($stock_quantity);

    $price_wholesale = test_request($oCells->get('E'.$i)->getValue());
    $price_wholesale = floatval($price_wholesale);
    $price_wholesale = number_format($price_wholesale, 2, '.', '');
    
    $sql = "SELECT * FROM `goods` WHERE `user_id` = 1799 AND `vendor_code` = '{$vendor_code}' LIMIT 1";
    $query = mysqli_query($db, $sql) or die(mysqli_error($db));
    $count_goods = mysqli_num_rows($query);

    $goods = mysqli_fetch_assoc($query);

    $goods_id = $goods['id'];

    if ($stock_quantity > 0) {
        
        $status = 1;

    } else {
        
        $status = 0;

    }

    $price_purchase = $price_wholesale;
    //$price_sale = $goods['price_sale'];
    
    /*if ($count_goods == 0) {
        $price_sale = $price_wholesale * 1.3;
        $price_sale = number_format($price_sale, 2, '.', '');
    }*/

    if ($price_wholesale < 50) $price_sale = $price_wholesale * 2;
    elseif ($price_wholesale >= 50 and $price_wholesale < 70) $price_sale = $price_wholesale * 1.7;
    elseif ($price_wholesale >= 70 and $price_wholesale < 100) $price_sale = $price_wholesale * 1.5;
    elseif ($price_wholesale >= 100 and $price_wholesale < 200) $price_sale = $price_wholesale * 1.25;
    elseif ($price_wholesale >= 200 and $price_wholesale < 500) $price_sale = $price_wholesale * 1.2;
    else $price_sale = $price_wholesale * 1.18;

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

    if ($price_agent == 0  or $price_purchase == 0 or $price_sale == 0) {
        $status = 0;
    }

    if ($count_goods > 0) {

        $sql = "UPDATE `goods` SET `availability`='{$stock_quantity}',
                                    `price_agent`='{$price_agent}',
                                    `price_purchase`='{$price_purchase}',
                                    `price_sale`='{$price_sale}',
                                    `status`='{$status}',
                                    `status_import`=1,
                                    `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=1799";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

    } else {

        $template = "{\"uk\":{\"Страна производитель\":\"-\",\"Бренд\":\"-\"},\"ru\":{\"Страна производитель\":\"-\",\"Бренд\":\"-\"}}";

        $photo = array();
        $photo['img0'] = 'no_image.png';
        $photo = json_encode($photo);

        $sql = "INSERT INTO `goods` SET `user_id`=1799,
                                        `vendor_id`='-',
                                        `vendor_code`='{$vendor_code}',
                                        `category`='tovari_dlya_doma_bez_foto',
                                        `name`='{$name}',
                                        `parameters`='{$template}',
                                        `photo`='{$photo}',
                                        `video`='',
                                        `keys`='',
                                        `export`='',
                                        `groups`='{\"top\":0,\"new\":0}',
                                        `availability`='{$stock_quantity}',
                                        `currency`=1,
                                        `currency_top_kurs`='1.00',
                                        `price_agent`='{$price_agent}',
                                        `price_purchase`='{$price_purchase}',
                                        `price_sale`='{$price_sale}',
                                        `status`=1,
                                        `status_import`=1,
                                        `updated`='{$current_date}',
                                        `created`='{$current_date}'";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

    }

}

if ($current_page > 0 and $current_page < $total_pages) {

    $nextpage = $current_page + 1;

    header('Location: /assets/files/import_providers/zinchenko_posuda/parser_excel/?page=' . $nextpage);
    exit;

} else {

    if ($current_page == $total_pages) {

        $sql = "UPDATE `goods` SET `availability`=0, `status`=0, `updated`='{$current_date}' WHERE `status_import`=0 AND `user_id`=1799";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

        $sql = "UPDATE `goods` SET `status_import`=0, `updated`='{$current_date}' WHERE `user_id`=1799";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

    }

    header('Location: /admin/goods_upload/');
    exit;

}

mysqli_close($db);

}

?>