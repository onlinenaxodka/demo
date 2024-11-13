<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?=$alert_message?>

<?
        
$linkname = 'bufernaya_kategoriya';

$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$catalog = mysqli_fetch_assoc($query);
$catalog_id = $catalog['id'];

$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_id}' ORDER BY `sort` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$count_subcategories = mysqli_num_rows($query);

?>

<div class="row mb-3">
    <div class="col-sm-3">
        <a href="/assets/ajax/count_goods_in_catalog2/" class="btn btn-primary btn-block" target="_blank">Быстрое отображение товара на платформе</a>
    </div>
    <div class="col-sm-3">
        <a href="/assets/ajax/count_goods_in_catalog_admin2/" class="btn btn-primary btn-block" target="_blank">Быстрое отображение товара в админке</a>
    </div>
    <!--<div class="col-sm-2">
        <a href="/assets/ajax/providers/import_goods_mobiking_list_categories/" class="btn btn-primary btn-block" target="_blank">Обновленные категории Mobiking</a>
    </div>-->
</div>

<?

if ($count_subcategories > 0) {

?>

    <ul class="category-goods mt-4 ml-0 mr-0">

<?

        while ($catalog = mysqli_fetch_assoc($query)) {

            if (!file_exists('../data/images/catalog/'.$catalog['img'])) {
                $catalog['img'] = 'no_image.png';
            }

            list($catalog_width_img, $catalog_height_img) = getimagesize('../data/images/catalog/'.$catalog['img']);

            if ($catalog_width_img > $catalog_height_img) {
                $catalog_width_img = '100%';
                $catalog_height_img = 'auto';
                $catalog_margin_img = 'unset';
            } else {
                $catalog_width_img = 'auto';
                $catalog_height_img = '100%';
                $catalog_margin_img = 'auto';
            }

            if ($catalog['linkname'] == 'zinchenko_posuda') {

                //$file = __DIR__ . '/../assets/files/import_providers/zinchenko_posuda/import/Price.xml';
                $file = __DIR__ . '/../data/files/import/zinchenko_posuda.xls';
                $file_1 = '';

            } elseif ($catalog['linkname'] == 'himoto') {

                $file = __DIR__ . '/../data/files/import/himoto.xlsx';
                $file_1 = __DIR__ . '/../data/files/import/himoto.xml';

            } elseif ($catalog['linkname'] == 'mobi') {

                $file = __DIR__ . '/../data/files/import/mobiking.xml';
                $file_1 = '';

            } elseif ($catalog['linkname'] == 'erc') {

                $file = __DIR__ . '/../data/files/import/erc_wares.xml';
                $file_1 = __DIR__ . '/../data/files/import/erc_erc6.xml';

            } else {

                $file = __DIR__ . '/../data/files/import/'.$catalog['linkname'].'.xml';
                $file_1 = '';
                
            }

            $filectime = file_exists($file) ? filectime($file) : strtotime('2016-10-23 00:00:00');
            $filectime1 = file_exists($file_1) ? filectime($file_1) : strtotime('2016-10-23 00:00:00');

?>

            <li style="position: relative;min-height: 370px;">
                <div>
                    <div style="display: flex;align-items: center;width: 100%;height: 150px;background: #F6F8FD;padding: 15px;">
                        <img src="/data/images/catalog/<?=$catalog['img']?>" style="width: <?=$catalog_width_img?>; height: <?=$catalog_height_img?>;margin: <?=$catalog_margin_img?>;">
                    </div>
                    <p class="text-uppercase mt-3 text-primary font-weight-bold"><span><?=$catalog['name_ru']?></span></p>
                </div>
                <div class="card mb-2">
                    <div class="card-body p-1">
                        <p class="mb-1"><small class="font-weight-bold">Последняя загрузка файла:</small></p>
                        <p class="font-italic mb-0"><?=date("d.m.Y, H:i", $filectime)?></p>
                        <?if(!empty($file_1)):?>
                        <p class="font-italic mb-0"><?=date("d.m.Y, H:i", $filectime1)?></p>
                        <?endif;?>
                    </div>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="provider" value="<?=$catalog['linkname']?>">

                    <?if ($catalog['linkname'] == 'zinchenko_posuda'):?>
                    <input type="file" name="excel_file" class="form-control form-control-sm mb-2" accept="application/vnd.ms-excel">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Загрузить xls файл</button>

                    <?elseif ($catalog['linkname'] == 'mobi'):?>
                    <!-- <input type="file" name="xml_file" class="form-control form-control-sm mb-2" accept="text/xml">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Загрузить XML файл</button>
                    <div class="form-group mt-3">
                        <a href="/assets/ajax/providers/import_goods_images_mobiking/" class="btn btn-success btn-block btn-sm mb-2">Обновить картинки</a>
                        <a href="/assets/ajax/providers/import_goods_mobiking_1/" class="btn btn-warning btn-block btn-sm mb-2">Обновить часть файла 1</a>
                        <a href="/assets/ajax/providers/import_goods_mobiking_2/" class="btn btn-warning btn-block btn-sm mb-2">Обновить часть файла 2</a>
                        <a href="/assets/ajax/providers/import_goods_mobiking_3/" class="btn btn-warning btn-block btn-sm">Обновить часть файла 3</a>
                    </div> -->
                    <?endif;?>
                </form>
            </li>

<?

        }

?>

    </ul>

<?

}

?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>