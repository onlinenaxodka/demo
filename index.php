<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

$catalog_data_count = 0;

include_once __DIR__ . '/config.php';

if (isset($_GET)) {

    if (!empty($_GET['partner'])) {

        $nickname = (isset($_GET['partner'])) ? mysqli_real_escape_string($db, $_GET['partner']) : '';
        $nickname = test_request($nickname);

        if (preg_match("/^[a-z0-9_-]{2,30}$/", $nickname)) {

            $sql = "SELECT `id` FROM `users` WHERE `nickname` = '{$nickname}' LIMIT 1";
            $query = mysqli_query($db, $sql) or die(mysqli_error($db));

            if (mysqli_num_rows($query) > 0) {

                $partner_data = mysqli_fetch_assoc($query);
                $partner_data_id = $partner_data['id'];

                $_SESSION['partner_id'] = $partner_data_id;

                $sql = "INSERT INTO `landdrop_statistic` SET `user_id`='{$partner_data_id}', `ip`='{$ip}', `created`='{$current_date}'";
                $query = mysqli_query($db, $sql) or die(mysqli_error($db));

            } else {

                $_SESSION['partner_id'] = 1;
                
            }
                    
        } else {

            $_SESSION['partner_id'] = 1;

        }

    }

    if (!empty($_GET['category'])) {

        $linkname = (isset($_GET['category'])) ? mysqli_real_escape_string($db, $_GET['category']) : '';
        $linkname = test_request($linkname);

        if (preg_match("/^[a-z0-9_]{2,255}$/", $linkname)) {

            $sql = "SELECT * FROM `catalog` WHERE `linkname` = '{$linkname}' LIMIT 1";
            $query = mysqli_query($db, $sql) or die(mysqli_error($db));
            $catalog_data_count = mysqli_num_rows($query);
            $catalog_data = mysqli_fetch_assoc($query);
            $catalog_data_id = $catalog_data['id'];

            if ($catalog_data_count > 0) {

                $_SESSION['main_page']['url'] = '/account/goods/'.$linkname;
                $_SESSION['main_page']['name'] = $catalog_data['name_ru'];

                $finish_category = finishCategory($db, $catalog_data_id, '');
                $finish_category = mb_substr($finish_category, 1);

            }
                    
        }

    }

    if (!empty($_GET['gtm'])) {

        $gtm = (isset($_GET['gtm'])) ? mysqli_real_escape_string($db, $_GET['gtm']) : '';
        $gtm = test_request($gtm);

        if (preg_match("/^[a-z]{2,255}$/", $gtm)) {

            $_SESSION['gtm'] = $gtm;
                    
        }

    }

}

function finishCategory($db, $category_id, $finish_category) {

    $sql = "SELECT `id` FROM `catalog` WHERE `level_id`='{$category_id}'";
    $query = mysqli_query($db, $sql) or die(mysqli_error($db));
    $count_subcategories = mysqli_num_rows($query);

    if ($count_subcategories > 0) {

        while ($subcategories = mysqli_fetch_assoc($query)) {

            $finish_category = finishCategory($db, $subcategories['id'], $finish_category);

        }

    } else {

        $sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}'";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));
        $subcategories = mysqli_fetch_assoc($query);

        $finish_category .= ',\''.$subcategories['linkname'].'\'';

    }

    return $finish_category;

}

//var_dump($_GET);

if (isset($_SESSION['user'])) {

    header('Location: ' . $main_page);
    exit;

}

include_once __DIR__ . '/include/lang_files.php';

?>

<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <title>ONLINE NAXODKA – Платформа эксклюзивного дропшиппинга!</title>
    <meta name="description" content="Регистрируйся! Построй свой бизнес по дропшиппингу в Украине с многоуровневой партнерской программой на огромном ассортименте товаров и услуг. Высокая доходность и быстрые выплаты на карту. Первый дропшиппинг поставщик с большим потенциалом!">
    <meta name="robots" content="INDEX, FOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:title" content="ONLINE NAXODKA – Платформа эксклюзивного дропшиппинга!">
    <meta property="og:description" content="Регистрируйся! Построй свой бизнес по дропшиппингу в Украине с многоуровневой партнерской программой на огромном ассортименте товаров и услуг. Высокая доходность и быстрые выплаты на карту. Первый дропшиппинг поставщик с большим потенциалом!">
    <meta property="og:url" content="https://<?=$_SERVER['SERVER_NAME']?>/<?=$user['nickname']?>">
    <meta property="og:image" content="https://<?=$_SERVER['SERVER_NAME']?>/assets/images/landdrop/img_share_ru.png">
    <link rel="image_src" href="https://<?=$_SERVER['SERVER_NAME']?>/assets/images/landdrop/img_share_ru.png">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/styles/sweetalert2.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/assets/styles/landdrop.css">
</head>
<body>
    <? include_once __DIR__ . '/include/stopwar.php'; ?>
    <!--[if lt IE 7]>
        <p>
            <?=$version_browser_no?>
        </p>
    <![endif]-->
    <? include_once __DIR__ . '/include/google_analytics.php'; ?>
    <button id="top"><img src="/assets/images/landdrop/btn_top.png" alt="Top"></button>
    <header>
        <div class="container">
            <button class="btn-menu">
                <img src="/assets/images/landdrop/btn_menu_white.png" alt="Menu">
            </button>
            <ul class="navigation">
                <li>
                    <a href="#benefits">Преимущества</a>
                </li>
                <li>
                    <a href="#how_it_works">Как мы работаем</a>
                </li>
                <li>
                    <a href="#earnings">Зароботок</a>
                </li>
                <li>
                    <a href="#reviews">Отзывы</a>
                </li>
                <li>
                    <a href="#about_us">О нас</a>
                </li>
                <li>
                    <a href="#faq">FAQ</a>
                </li>
            </ul>
            <?/*?provider*/?>
            <?/*if (empty($_GET['category'])):?>
            <a href="/register/" class="btn btn-warning btn-register float-right" data-toggle="tooltip" data-placement="bottom" title="Хотите предложить свой товар или услугу? Добавляйтесь в телеграм @Evgeniy_Tkachuk, наш сотрудник вам поможет!">Регистрация поставщик</a>
            <a href="/register/" class="btn btn-success btn-register btn-dropshipper float-right mr-1">Регистрация дропшиппинг</a>
            <a href="/login/" class="text-white float-right mr-4">Вход</a>
            <?else:*/?>
            <a href="/register/" class="btn btn-warning btn-register float-right" style="padding: 9px 0;">Регистрация</a>
            <a href="/login/" class="text-white float-right mr-4" style="background: none;margin-top: 0;padding: 7px 25px;line-height: 2;">Вход</a>
            <?//endif;?>
        </div>
    </header>
    <section class="layout layout1">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 left-side text-center">
                    <img src="/assets/images/landdrop/land_logo.png" class="w-100" alt="Logo">
                    <?if (!empty($_GET['category'])):?>
                    <p class="logo-title mt-4 mb-3" style="line-height: 1;"><b style="color: red;text-shadow: 0 0 10px #fff;"><?=$catalog_data['name_ru']?></b> <br>для дропшиппинга</p>
                    <?else:?>
                    <p class="logo-title mb-3">Платформа <b>эксклюзивного</b> дропшиппинга!</p>
                    <?endif;?>
                    <div style="position: relative;">
                    <img src="/assets/images/landdrop/laptop.png" class="laptop" alt="Laptop">
                    <img src="/assets/images/landdrop/ipad.png" class="ipad" alt="iPad">
                    <img src="/assets/images/landdrop/iphone.png" class="iphone" alt="iPhone">
                    <!-- <figure class="laptop"></figure> -->
                    <?if($catalog_data_count > 0):?>
                    <style type="text/css">
                        .layout.layout1 .list-goods {
                            position: absolute;
                            top: 31px;
                            left: 0;
                            right: 0;
                            max-width: 474px;
                            width: 100%;
                            height: 100%;
                            max-height: 294px;
                            background-color: #fff;
                            margin: auto;
                            overflow: hidden;
                        }

                        .layout.layout1 .list-goods li {
                            display: inline-block;
                            width: 157px;
                            padding: 5px;
                            border: 1px solid #eee;
                            list-style: none;
                            float: left;
                            text-align: center;
                        }

                        .layout.layout1 .list-goods li:hover {
                            box-shadow: 0 0 13px 0 rgba(0,0,0,.4);
                        }

                        .layout.layout1 .list-goods li a {
                            display: block;
                        }

                        .layout.layout1 .list-goods li a div {
                            width: 100%;
                            height: 134px;
                            background-color: #F6F8FD;
                            /*background-color: #fff;*/
                            overflow: hidden;
                            text-align: center;
                        }

                        .layout.layout1 .list-goods li a div.max-width {
                            display: -webkit-box;
                            display: -moz-box;
                            display: -ms-flexbox;
                            display: -webkit-flex;
                            display: flex;
                            /*justify-content: center;*/
                            align-items: center;
                        }

                        .layout.layout1 .list-goods li a div.max-width img {
                            display: block;
                            width: 100%;
                            height: auto;
                            margin: auto;
                        }

                        .layout.layout1 .list-goods li a div.max-height img {
                            width: auto;
                            height: 100%;
                        }

                        @media (max-width: 1199px) and (min-width: 992px) {

                            .layout.layout1 .list-goods {
                                top: 26px;
                                max-width: 412px;
                                max-height: 258px;
                            }

                            .layout.layout1 .list-goods li {
                                width: 137px;
                            }

                            .layout.layout1 .list-goods li a div {
                                height: 116px;
                            }

                        }

                        @media (max-width: 991px) {
                            .layout.layout1 .list-goods {
                                top: 31px;
                            }
                        }

                        @media (max-width: 767px) {
                            .layout.layout1 .list-goods {
                                top: 78px;
                                max-width: 314px;
                                max-height: 440px;
                            }
                        }

                        @media (max-width: 576px) {
                            .layout.layout1 .list-goods {
                                top: 50px;
                                max-width: 202px;
                                max-height: 544px;
                                padding: 14px;
                            }

                            .layout.layout1 .list-goods li {
                                width: 174px;
                            }

                            .layout.layout1 .list-goods li a div {
                                height: 160px;
                            }
                        }

                        @media (max-width: 359px) {
                            .layout.layout1 .list-goods {
                                max-width: 186px;
                                max-height: 468px;
                            }
                            .layout.layout1 .list-goods li {
                                width: 157px;
                            }

                            .layout.layout1 .list-goods li a div {
                                height: 134px;
                            }
                        }

                        @media (max-width: 320px) {
                            .layout.layout1 .list-goods {
                                top: 46px;
                            }
                        }

                    </style>
                    <ul class="list-goods">
                    <?

                    $sql = "SELECT * FROM `goods` WHERE `category` IN ($finish_category) AND `status`=1 AND `photo` NOT LIKE '%no_image.png%' ORDER BY RAND() LIMIT 6";
                    $query = mysqli_query($db, $sql) or die(mysqli_error($db));

                    if (mysqli_num_rows($query) > 0) {

                        while ($goods = mysqli_fetch_assoc($query)) {

                            $goods['photo'] = json_decode($goods['photo'], true);

                            list($goods_photo_w, $goods_photo_h) = getimagesize('data/images/goods/'.$goods['photo']['img0']);

                            if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
                            else $goods_photo_size = 'max-height';

                    ?>
                        <li>
                            <a href="/register/?category=<?=$goods['category']?>&goods=<?=$goods['id']?>">
                                <div class="<?=$goods_photo_size?>">
                                    <img src="/data/images/goods/<?=$goods['photo']['img0']?>">
                                </div>
                            </a>
                        </li>
                    <?
                        }

                    }

                    ?>
                    </ul>
                    <?endif;?>
                    </div>
                </div>
                <div class="col-lg-5 right-side">
                    <div class="form">

<?

$sql = "SELECT COUNT(`id`) AS sum FROM `goods` WHERE `availability` > 0 and `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$goods = mysqli_fetch_assoc($query);

?>

<link rel="stylesheet" type="text/css" href="/assets/lichylnyk/counter.css" />
<script type="text/javascript" src="/assets/lichylnyk/counter_mini.js"></script>

                        <input type="hidden" name="screen" value="1">
                        <input type="hidden" name="calc" value="0">
                        <?if (!empty($_GET['category'])):?>
                        <p class="title-1">Зарегистрируйтесь прямо сейчас<br>и получите доступ</p>
                        <p class="title-2">к крупнейшему каталогу</p>
                        <p id="results" data-counter-finish="<?=$goods['sum']?>" style="display: none;">0</p>
                        <div id="customCounter" class="custom-counter" style="margin-top: 10px;"></div>
                        <p class="title-3">товаров с высокой доходностью и реальным наличием</p>
                        <p class="text-center mb-3">
                            <img src="/assets/images/grafik.png" class="w-100">
                        </p>
                        <a href="/register/" class="btn btn-success btn-block">Получить доступ</a>
                        <?else:?>
                        <!-- <p class="title-1">Зарегистрируйтесь прямо сейчас<br>и получите чек лист</p>
                        <p class="title-2">топ 5 эксклюзивных</p>
                        <p class="title-3">товаров от своего наставника с высокой доходностью</p> -->
                        <p class="title-1">Зарегистрируйтесь прямо сейчас<br>и получите доступ</p>
                        <p class="title-2">к крупнейшему каталогу</p>
                        <p id="results" data-counter-finish="<?=$goods['sum']?>" style="display: none;">0</p>
                        <div id="customCounter" class="custom-counter" style="margin-top: 10px;"></div>
                        <p class="title-3">товаров с высокой доходностью и реальным наличием</p>
                        <p class="text-center mb-3">
                            <img src="/assets/images/grafik.png" class="w-100">
                        </p>
                        <a href="/register/" class="btn btn-success btn-block">Зарегистрироваться</a>
                        <?endif;?>

<script type="text/javascript">

function randomInt(min, max) {
    return min + Math.floor((max - min) * Math.random());
}

function startCounter() {
  
    var start = parseInt(document.getElementById('results').innerHTML);
    var finish = parseInt(document.getElementById('results').getAttribute('data-counter-finish'));
    var dif_num = finish - start;
    var random_num = randomInt(70, 100);
            
    if (start < finish) {

        if (dif_num > random_num) start += random_num;
        else start++;
        document.getElementById('results').innerHTML = start;
        setTimeout(startCounter, 1);

    }

}

startCounter();

var abb = parseInt(document.getElementById('results').innerHTML);

var customCounter = null;
var customTimerId = null;
function loadCounter(){  
    
    customCounter = new Counter("customCounter", {
        digitsNumber : 5,
        direction : Counter.ScrollDirection.Mixed,
        scrollAnimation : Counter.ScrollAnimation.FixedSpeed,
        characterSet : Counter.DefaultCharacterSets.numericUp,
        charsImageUrl : "/assets/lichylnyk/numeric_up_blackbg5.png",
        markerImageUrl : "/assets/lichylnyk/marker.png",
        value : parseInt(document.getElementById('results').innerHTML)
    });
    
    customTimerId = window.setInterval(function(){
        if (parseFloat(customCounter.value) >= 0){
            sum = parseInt(document.getElementById('results').innerHTML) - abb;
            customCounter.add(sum, 1000);
            abb = parseInt(document.getElementById('results').innerHTML);
        } else {
            clearInterval(customTimerId);
            document.getElementById("customCounter").parentNode.innerHTML = '<h2>Counting finished!</h2>'; 
        }
    }, 1000); 
}

loadCounter();

</script>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?if (!empty($_GET['category'])):?>
    <section class="layout" style="background: #f7f7f7;">
        <style type="text/css">
.goods {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    align-items: center;
}

.goods .list-catalog {
    max-width: 1078px;
    margin-left: auto;
    margin-right: auto;
}

.goods .list-catalog li {
    display: inline-block;
    width: 215.6px;
    padding: 5px;
    border: 1px solid #cacaca;
    list-style: none;
    float: left;
    text-align: center;
}

.goods .list-catalog li:hover {
    box-shadow: 0 0 13px 0 rgba(0,0,0,.4);
}

.goods .list-catalog li a {
    display: block;
}

.goods .list-catalog li a div {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    align-items: center;
    width: 100%;
    height: 203.6px;
    background: #F6F8FD;
    padding: 20px;
}

.goods .list-catalog li a div img {
    display: block;
    margin: auto;
}

.goods .list-catalog li a p {
    height: 48px;
    line-height: 48px;
    margin-bottom: 0;
}

.goods .list-catalog li a p span {
    display: inline-block;
    vertical-align: middle;
    line-height: normal;
}

.goods .list-catalog li a:hover p span {
    text-decoration: underline;
}

.goods .list-goods {
    max-width: 1078px;
    margin-left: auto;
    margin-right: auto;
}

.goods .list-goods li {
    display: inline-block;
    width: 269.5px;
    padding: 5px;
    border: 1px solid #cacaca;
    list-style: none;
    float: left;
    text-align: center;
}

.goods .list-goods li:hover {
    box-shadow: 0 0 13px 0 rgba(0,0,0,.4);
}

.goods .list-goods li a {
    display: block;
}

.goods .list-goods li a div {
    width: 100%;
    height: 257.5px;
    background-color: #F6F8FD;
    overflow: hidden;
    text-align: center;
}

.goods .list-goods li a div.max-width {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    /*justify-content: center;*/
    align-items: center;
}

.goods .list-goods li a div.max-width img {
    display: block;
    width: 100%;
    height: auto;
    margin: auto;
}

.goods .list-goods li a div.max-height img {
    width: auto;
    height: 100%;
}

.goods .list-goods li a p.goods-title {
    height: 48px;
    line-height: 48px;
    margin: 1rem 0 .5rem 0;
}

.goods .list-goods li a p.goods-title span {
    display: inline-block;
    vertical-align: middle;
    line-height: normal;
}

.goods .list-goods li a:hover p.goods-title span {
    text-decoration: underline;
}

.goods .badge {
    font-size: 100%;
}

@media (max-width: 1199px) {

    .goods .list-catalog {
        max-width: 864px;
    }

    .goods .list-goods {
        max-width: 810px;
    }
    
}

@media (max-width: 991px) {

    .goods .list-catalog {
        max-width: 647px;
    }

    .goods .list-goods {
        max-width: 540px;
    }

}

@media (max-width: 768px) {

    .goods .list-catalog {
        max-width: 432px;
    }

    .goods .list-goods {
        max-width: 100%;
    }

    .goods .list-goods li {
        float: none;
    }

}

@media (max-width: 494px) {

    .goods .list-catalog {
        max-width: 100%;
    }

    .goods .list-catalog li {
        float: none;
    }

}
        </style>
        <div class="container mb-5">
            <h1 id="catalog" class="title">Каталог</h1>
        </div>
        <div class="container goods mb-5">

<?

        $linkname = 'catalog';
        if (!empty($_GET['category'])) $linkname = $_GET['category'];

        $sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));
        $catalog = mysqli_fetch_assoc($query);
        $catalog_id = $catalog['id'];

        $sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_id}' AND `locked`=0 ORDER BY `sort` ASC";
        $query = mysqli_query($db, $sql) or die(mysqli_error($db));

        if (mysqli_num_rows($query) > 0) {

?>
        
        <ul class="list-catalog text-center">

<?

            while ($catalog = mysqli_fetch_assoc($query)) {

                if (!file_exists('data/images/catalog/'.$catalog['img'])) $catalog['img'] = 'no_image.png';

                list($catalog_width_img, $catalog_height_img) = getimagesize('data/images/catalog/'.$catalog['img']);

                if ($catalog_width_img > $catalog_height_img) {
                    $catalog_width_img = '100%';
                    $catalog_height_img = 'auto';
                } else {
                    $catalog_width_img = 'auto';
                    $catalog_height_img = '100%';
                }

                $sql_count_subcategories = "SELECT `id` FROM `catalog` WHERE `level_id`='{$catalog['id']}'";
                $query_count_subcategories = mysqli_query($db, $sql_count_subcategories) or die(mysqli_error($db));
                $count_subcategories = mysqli_num_rows($query_count_subcategories);

                //$count_goods_in_catalog = countGoodsInCategory($db, $catalog['id'], 0);
                $count_goods_in_catalog = $catalog['count_goods'];

                if ($count_goods_in_catalog > 0) {

?>

                <li style="position: relative;">
                    <?if ($count_subcategories > 0):?>
                        <span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #cccccc;color: #000;border-radius: 15px;padding: 0 10px;"><?=$count_goods_in_catalog?></span>
                    <?else:?>
                        <span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #ffc107;color: #000;border-radius: 15px;padding: 0 10px;"><?=$count_goods_in_catalog?></span>
                    <?endif;?>
                    <a href="/<?=$catalog['linkname']?>/<?=$nickname?>#catalog">
                        <div>
                            <img src="/data/images/catalog/<?=$catalog['img']?>" style="width: <?=$catalog_width_img?>; height: <?=$catalog_height_img?>;">
                        </div>
                        <p class="text-uppercase"><span><?=$catalog['name_'.$lang]?></span></p>
                    </a>
                </li>

<?

                }

            }

?>

    </ul>

<?

        } else {

?>

    <ul class="list-goods text-center">

<?

            $sql = "SELECT * FROM `goods` WHERE `category`='{$linkname}' AND `status`=1 AND `photo` NOT LIKE '%no_image.png%' ORDER BY `availability` DESC, SUBSTRING_INDEX(SUBSTRING_INDEX(name, '\"', -2), '\"', 1) ASC LIMIT 20";
            $query = mysqli_query($db, $sql) or die(mysqli_error($db));

            if (mysqli_num_rows($query) > 0) {

                while ($goods = mysqli_fetch_assoc($query)) {

                    $goods['photo'] = json_decode($goods['photo'], true);
                    $goods['name'] = json_decode($goods['name'], true);

                    list($goods_photo_w, $goods_photo_h) = getimagesize('data/images/goods/'.$goods['photo']['img0']);

                    if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
                    else $goods_photo_size = 'max-height';

                    if ($goods['currency'] == 1) {

                        $kurs_currency = 1;

                    } else if ($goods['currency'] == 2) {

                        $kurs_currency = $api_exchange_rate_usd['sale'];

                        if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

                            $kurs_currency = $goods['currency_top_kurs'];

                        }

                    } else if ($goods['currency'] == 3) {

                        $kurs_currency = $api_exchange_rate_eur['sale'];

                        if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

                            $kurs_currency = $goods['currency_top_kurs'];

                        }

                    }

                    $price_purchase = ceil($goods['price_purchase'] * $kurs_currency);
                    $price_sale = ceil($goods['price_sale'] * $kurs_currency);
                    $price_min = ceil($price_sale - (($price_sale - $price_purchase) * $catalog['rate']));

?>

                <li>
                    <a href="/register/?category=<?=$goods['category']?>&goods=<?=$goods['id']?>">
                        <div class="<?=$goods_photo_size?>">
                            <img src="/data/images/goods/<?=$goods['photo']['img0']?>">
                        </div>
                        <p class="goods-title">

                            <?

                            $list_goods_name = $goods['name'][$lang];

                            if (strlen(utf8_decode($list_goods_name)) > 56) $list_goods_name = mb_substr($list_goods_name, 0, 56) . '...';

                            ?>

                            <span><?=$list_goods_name?></span>
                        </p>
                    </a>

                    <div class="row pt-3 pb-3 ml-0 mr-0" style="background: #f3f3f3">
                        <div class="col-12">
                            <a href="/register/?category=<?=$goods['category']?>&goods=<?=$goods['id']?>" class="btn btn-success btn-block">Узнать &nbsp;цену</a>
                        </div>
                    </div>
                </li>

<?

                }

            } else {

                echo '<br>На данный момент в этом каталоге нет товаров';

            }

        }

?>

    </ul>

        </div>
    </section>
    <?endif;?>

    <section id="benefits" class="layout layout-menu layout2">
        <div class="container">
            <h1 class="title">Преимущества</h1>
            <p class="subtitle">работы с нашей <b>платформой</b></p>
            <div class="benefits-list text-center">
                <div class="benefits-list-item">
                    <div class="benefits-img img-1"></div>
                    <p class="benefits-title">Огромный</p>
                    <p class="benefits-subtitle">ассортимент</p>
                </div>
                <div class="benefits-list-item">
                    <div class="benefits-img img-2"></div>
                    <p class="benefits-title">Эксклюзивные</p>
                    <p class="benefits-subtitle">товары</p>
                </div>
                <div class="benefits-list-item">
                    <div class="benefits-img img-3"></div>
                    <p class="benefits-title">Высокая</p>
                    <p class="benefits-subtitle">доходность</p>
                </div>
                <div class="benefits-list-item">
                    <div class="benefits-img img-4"></div>
                    <p class="benefits-title">Полная</p>
                    <p class="benefits-subtitle">автоматизация</p>
                </div>
                <div class="benefits-list-item">
                    <div class="benefits-img img-5"></div>
                    <p class="benefits-title">Выгрузка</p>
                    <p class="benefits-subtitle">товара за секунду</p>
                </div>
                <div class="benefits-list-item">
                    <div class="benefits-img img-1"></div>
                    <p class="benefits-title">Реальное</p>
                    <p class="benefits-subtitle">наличие на складе</p>
                </div>
            </div>
        </div>
    </section>

    <section class="layout layout-forwhom">
        <div class="container">
            <h1 class="title">Кому подойдет</h1>
            <p class="subtitle mb-4">наша дропшиппинг-<b>платформа</b></p>
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="forwhom-img">
                        <img class="picture" src="/assets/images/landdrop/forwhom/1.jpg" alt="forwhom1">
                        <img class="ellipse" src="/assets/images/landdrop/forwhom/ellipse.png" alt="Ellipse">
                    </div>
                    <h3 class="forwhom-title">Дропшиппер</h3>
                    <ul class="forwhom-text-list">
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Домохазяйка</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Студент</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Безработные</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Предприниматель</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Арбитражник</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="forwhom-img">
                        <img class="picture" src="/assets/images/landdrop/forwhom/2.jpg" alt="forwhom2">
                        <img class="ellipse" src="/assets/images/landdrop/forwhom/ellipse.png" alt="Ellipse">
                    </div>
                    <h3 class="forwhom-title">Интернет-магазин</h3>
                    <ul class="forwhom-text-list">
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Prom</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Rozetka</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Zakupka</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Open Cart</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Wordpress</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="forwhom-img">
                        <img class="picture" src="/assets/images/landdrop/forwhom/3.jpg" alt="forwhom3">
                        <img class="ellipse" src="/assets/images/landdrop/forwhom/ellipse.png" alt="Ellipse">
                    </div>
                    <h3 class="forwhom-title">Опт</h3>
                    <ul class="forwhom-text-list">
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Груповые покупки</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Мелкий опт</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Средний опт</span>
                        </li>
                        <li>
                            <img src="/assets/images/landdrop/icon_ok.png" alt="ok">
                            <span>Крупный опт</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="layout layout3">
        <div class="container">
            <h1 class="title">Что мы предлагаем</h1>
            <p class="subtitle mb-4">нашим <b>клиентам</b></p>
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/1.png" alt="Get1">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Эксклюзивность</p>
                        <p class="get-text-p">Мы одна из немногих компаний, которая взяла особую ответственность объединить три основных критерия для успешного ведения дропшиппинга: <b>качество, цена и эксклюзивность</b>.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/2.png" alt="Get2">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Ассортимент</p>
                        <p class="get-text-p">Благодаря нашему <b>изобилию товаров</b>, на платформе <b>Online Naxodka</b> абсолютно каждый человек найдёт для себя лучший вариант сотрудничества.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/3.png" alt="Get3">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Низкие цены на товар</p>
                        <p class="get-text-p">Мы работаем <b>без посредников</b>, поставляя товар на прямую из Китая, за счет чего имеем цену продукции ниже рыночной. Именно поэтому мы можем предложить нашим клиентам <b>максимально низкие цены</b>, и всегда большой ассортимент популярных товаров.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/4.png" alt="Get4">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Высокая доходность</p>
                        <p class="get-text-p">Мы работаем <b>без посредников</b>, поставляя товар на прямую из Китая, за счет чего имеем цену продукции ниже рыночной. Именно поэтому мы можем предложить нашим клиентам <b>максимально низкие цены</b>, и всегда большой ассортимент популярных товаров <b>высокой доходности</b>.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/5.png" alt="Get5">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Без предоплат</p>
                        <p class="get-text-p">Мы не требуем <b>предварительной оплаты</b> за товар от дропшиперов, для того, чтобы вы привлекали и   удерживали доверие ваших клиентов.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/6.png" alt="Get6">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Выгрузка в 1 клик</p>
                        <p class="get-text-p"><b>Автоматизированная система Online Naxodka</b> дает возможность разместить товары для ваших торговых страниц молниеносно.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/7.png" alt="Get7">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Гарантии</p>
                        <p class="get-text-p">Более <b>3500 партнеров</b> выбрали нас как надежную компанию по дропшиппингу. Мы дорожим своим именем поэтому гарантируем оперативную отправку и максимальное <b>качество продукции</b>.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="get-img">
                        <img class="picture" src="/assets/images/landdrop/get/8.png" alt="Get8">
                        <img class="ellipse" src="/assets/images/landdrop/get/ellipse.png" alt="Ellipse">
                    </div>
                    <div class="get-text">
                        <p class="get-text-title">Быстрая отправка</p>
                        <p class="get-text-p">Так как мы <b>сами доставляем</b> товар из Китая к себе на склад в Хмельницкий  - у нас есть большой опыт работы в логистике. Заказывая товар у сервиса Online Naxodka , Вы получаете отправку товаров в тот же день заказа и <b>максимально быструю доставку</b> в любой уголок  Украины.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="how_it_works" class="layout layout-menu layout4">
        <div class="container">
            <p class="subtitle"><b>Как</b> мы</p>
            <h1 class="title">работаем</h1>
            <div class="work-list">
                <div class="work-list-item">
                    <div class="work-list-img img-1"></div>
                    <p>К Вам поступает заказ от клиента</p>
                </div>
                <div class="work-list-item arrow">
                    <img src="/assets/images/landdrop/work_arrow.png" alt="Work arrow">
                </div>
                <div class="work-list-item">
                    <div class="work-list-img img-2"></div>
                    <p>Вы передаете заказ к нам в систему</p>
                </div>
                <div class="work-list-item arrow">
                    <img src="/assets/images/landdrop/work_arrow.png" alt="Work arrow">
                </div>
                <div class="work-list-item">
                    <div class="work-list-img img-3"></div>
                    <p>Мы отправляем заказ клиенту и уведомляем Вас</p>
                </div>
                <div class="work-list-item arrow">
                    <img src="/assets/images/landdrop/work_arrow.png" alt="Work arrow">
                </div>
                <div class="work-list-item">
                    <div class="work-list-img img-4"></div>
                    <p>По прибытию заказа в пункт назначения мы уведомляем клиента</p>
                </div>
                <div class="work-list-item arrow">
                    <img src="/assets/images/landdrop/work_arrow.png" alt="Work arrow">
                </div>
                <div class="work-list-item">
                    <div class="work-list-img img-5"></div>
                    <p>Клиент оплачивает заказ, мы забираем себе стоимость товара, а разницу переводим Вам</p>
                </div>
            </div>
        </div>
    </section>
    <section id="earnings" class="layout layout-menu layout5">
        <div class="container">
            <p class="subtitle"><b>Сколько</b> можно</p>
            <h1 class="title">заработать</h1>
            <div class="earnings-slider">
              <div class="earnings-slider-items">
                <div class="item-0"><p>0</p><span></span></div>
                <div class="item-1"><p>1</p><span></span></div>
                <div class="item-2"><p>2</p><span></span></div>
                <div class="item-3"><p>3</p><span></span></div>
                <div class="item-4"><p>4</p><span></span></div>
                <div class="item-5"><p>5</p><span></span></div>
                <div class="item-6"><p>6</p><span></span></div>
                <div class="item-7"><p>7</p><span></span></div>
                <div class="item-8"><p>8</p><span></span></div>
                <div class="item-9"><p>9</p><span></span></div>
                <div class="item-10"><p>10</p><span></span></div>
                <div class="item-11"><p>15</p><span></span></div>
                <div class="item-12"><p>20</p><span></span></div>
              </div>
              <div class="earnings-slider-progressbar" id="earningsSlider"></div>
            </div>
            <div class="earnings-select mt-5">
              <select class="form-control" id="earningsSelect">
<?

                for ($i=0; $i < 21; $i++) { 
                  if ($i == 1) echo '<option value="'.$i.'" selected>'.$i.'</option>';
                  else echo '<option value="'.$i.'">'.$i.'</option>';
                }

?>                
              </select>
            </div>
            <p class="earnings-p">(Количество заявок в день)</p>
            <div class="earnings-amount spincrement">4 500</div>
            <p class="earnings-p">(Сумма заработка в месяц, грн.)</p>
            <p class="text-center pt-2">
                <a href="/register/" class="btn btn-success btn-block">Заработать</a>
            </p>
        </div>
    </section>
    <section id="reviews" class="layout layout-menu layout6">
        <div class="container">
            <h1 class="title">Отзывы</h1>
            <p class="subtitle mb-5">дропшипперов</p>
            <div class="row">
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/1.png" alt="User1">
                      <p>Андрей Александров</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Работа с супер дроп очень радует. Из плюсов могу выделить очень хорошую и быструю обратную связь. Заказы все отправляются в срок без задержек, я рад и мои клиенты тоже. Спасибо за сотрудничество.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/2.png" alt="User2">
                      <p>Сергей Тихонов</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Работаю по системе дропшиппинга не так давно. Долгое время присматривался к разным поставщикам. В итоге остановился на Online Naxodka. Единственный поставщик у которого - адекватные цены, выгодные условия.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/3.png" alt="User3">
                      <p>Оксана Назарук</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Недавно начала работать с этим сервисом. Очень понравилось. Работают оперативно, помогают советами, быстро отвечают. Сложилось очень положительное впечатление о команде. Отправила больше десяти товаров.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/4.png" alt="User4">
                      <p>Павел Скочко</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Здравствуйте. Я занимаюсь дропшиппингом уже более 3-х лет и это основной мой заработок. С товаром Online Naxodka познакомился еще год назад. Нашел их объявление на сайте ОЛХ со спортивным костюмом Реал Мадрид. Так как<span class="three-points">...</span><span class="hide-text"> я большой фанат королевского клуба, сразу же заказал себе, после того как получил, сам позвонил и спросил, есть ли у них дропшиппинг. Ответ был положительным, скинули мне сразу все прайсы и фотографии. Уже очень много клиентов постоянно хвалят свои покупки. Поэтому я очень рад, что наткнулся на их объявление тогда. Спасибо!</span></p>
                      <p class="text-right">
                        <button type="button" class="reviews-read-more">Читать отзыв полностью</button>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <p class="text-center">
              <button type="button" class="reviews-show-all">Показать все отзывы</button>
            </p>
            <div class="reviews-hided">
            <div class="row">
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/5.jpg" alt="User5">
                      <p>Михаил Копченко</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Для начинающего товарщика Online Naxodka – отличный выбор. Отправка от одной штуки – раз. Отправка в день заказа – два. Менеджер, который всегда на связи – три. Не робот, живой человек)</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/6.jpg" alt="User6">
                      <p>Марина Коржова</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Классный сервис, самый главный плюс это оперативность, в отличие, от других систем, где заказ может только обрабатываться 1-2 дня. По качеству пока товаров, пока ничего сказать не могу. Ещё один плюс.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/7.jpg" alt="User7">
                      <p>Павел Воля</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Хороший сервис. Обслуживают быстро и прибыль быстро переводят))</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/8.jpg" alt="User8">
                      <p>Артем Самсоненко</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Рекомендую сервис! Отправлял заказ по дропшиппингу, чтобы клиент получил товар на следующий день. Оперативно собрали заказ и доставили курьером. Клиент доволен! Благодарю за работу!</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/9.jpg" alt="User9">
                      <p>Мария Игнатьева</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Четкая слаженная работа. Все очень оперативно. Вопросы решаются быстро. Удобный сайт и личный кабинет. Все условия дают возможность сосредоточится на трафике, не думая о логистике. Крутой аутсерсинг.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mb-5">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="reviews-user">
                      <img src="/assets/images/landdrop/reviews/10.jpg" alt="User10">
                      <p>Александер Кошевой</p>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="reviews-text">
                      <p>Пытался работать со многими... но, то товар не качественный, то менеджер не адекватный... Посоветовали месяца четыре назад Команду Online Naxodka, с тех пор ни одного нарекания! Быстро, качественно, цены<span class="three-points">...</span><span class="hide-text"> я большой фанат королевского клуба, сразу же заказал себе, после того как получил, сам позвонил и спросил, есть ли у них дропшиппинг. Ответ был положительным, скинули мне сразу все прайсы и фотографии. Уже очень много клиентов постоянно хвалят свои покупки. Поэтому я очень рад, что наткнулся на их объявление тогда. Спасибо!</span></p>
                      <p class="text-right">
                        <button type="button" class="reviews-read-more">Читать отзыв полностью</button>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
        </div>
    </section>

<?

$sql = "SELECT COUNT(1) AS count FROM `users`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$users = mysqli_fetch_assoc($query);

//$sql = "SELECT SUM(`availability`) AS sum FROM `goods`";
$sql = "SELECT COUNT(`id`) AS sum FROM `goods` WHERE `availability` > 0 and `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$goods = mysqli_fetch_assoc($query);

$sql = "SELECT COUNT(1) AS count FROM `orders`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$orders = mysqli_fetch_assoc($query);

$sql = "SELECT COUNT(1) AS count FROM `orders` WHERE MONTH(`created`) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH)) AND YEAR(`created`) = YEAR(NOW())";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$orders_current_month = mysqli_fetch_assoc($query);

?>

    <section id="about_us" class="layout layout-menu layout7">
        <div class="container">
            <h1 class="title">О нас</h1>
            <p class="subtitle mb-5">в цифрах</p>
            <div class="row">
              <div class="col-lg-3 mb-5">
                <div class="about-us-img">
                  <img class="picture" src="/assets/images/landdrop/about_us/1.png" alt="About_us1">
                  <img class="ellipse" src="/assets/images/landdrop/about_us/ellipse.png" alt="Ellipse">
                </div>
                <h3 class="about-us-title spincrement_count"><?=$goods['sum']?></h3>
                <p class="about-us-text">Количество товаров доступных для дропшипинга</p>
              </div>
              <div class="col-lg-3 mb-5">
                <div class="about-us-img">
                  <img class="picture" src="/assets/images/landdrop/about_us/2.png" alt="About_us2">
                  <img class="ellipse" src="/assets/images/landdrop/about_us/ellipse.png" alt="Ellipse">
                </div>
                <h3 class="about-us-title spincrement_count"><?=$users['count']?></h3>
                <p class="about-us-text">Дропшипперов зарегистрировано в системе и зарабатывают вместе с нами</p>
              </div>
              <div class="col-lg-3 mb-5">
                <div class="about-us-img">
                  <img class="picture" src="/assets/images/landdrop/about_us/3.png" alt="About_us3">
                  <img class="ellipse" src="/assets/images/landdrop/about_us/ellipse.png" alt="Ellipse">
                </div>
                <h3 class="about-us-title spincrement_count"><?=($orders_current_month['count']+2100)?></h3>
                <p class="about-us-text">Отправок осуществляется ежемесячно</p>
              </div>
              <div class="col-lg-3 mb-5">
                <div class="about-us-img">
                  <img class="picture" src="/assets/images/landdrop/about_us/4.png" alt="About_us4">
                  <img class="ellipse" src="/assets/images/landdrop/about_us/ellipse.png" alt="Ellipse">
                </div>
                <h3 class="about-us-title spincrement_count"><?=($orders['count']+14000)?></h3>
                <p class="about-us-text">Довольных клиентов, получивших свой товар</p>
              </div>
            </div>
        </div>
    </section>
    <section class="layout layout8">
        <div class="container">
            <p class="subtitle mb-3"><b>Начни прибыльный бизнес</b></p>
            <img src="/assets/images/landdrop/land_logo.png" class="register-logo" alt="Logo">
            <h3 class="register-p-title">Прямо сейчас</h3>
            <p class="register-p-text">Зарегистрируйся на сайте, добавляй товары для отправки, все остальные вопросы решит профессиональная команда Online Naxodka</p>
            <p class="text-center pt-2">
                <a href="/register/" class="btn btn-success btn-block">Зарегистрироваться</a>
            </p>
        </div>
    </section>
    <section class="layout layout9">
        <div class="container">
            <p class="subtitle">У вас <b>есть</b></p>
            <h1 class="title">два пути</h1>
            <div class="row mt-4">
              <div class="col-lg-6 mb-4">
                <img class="two-ways-img" src="/assets/images/landdrop/way1.png" alt="Way1">
                <div class="two-ways-number">1</div>
                <h3 class="two-ways-title">Продолжать искать</h3>
                <p class="two-ways-text">товары разных поставщиков, терять время и деньги, набивать все новые и новые шишки самостоятельно.</p>
              </div>
              <div class="col-lg-6 mb-4">
                <img class="two-ways-img" src="/assets/images/landdrop/way2.png" alt="Way2">
                <div class="two-ways-number">2</div>
                <h3 class="two-ways-title">Воспользоваться опытом</h3>
                <p class="two-ways-text">проверенной платформы Online Naxodka, где собраны лучшие предложения с высокой доходностью специально для вас.</p>
              </div>
            </div>
            <p class="two-ways-p">Сделайте ваш выбор:</p>
            <p class="text-center pt-2">
                <a href="/register/" class="btn btn-success btn-block">Выбрать второй Путь</a>
            </p>
        </div>
    </section>
    <section id="faq" class="layout layout-menu layout10">
        <div class="container">
            <h1 class="title">FAQ</h1>
            <div class="row mt-4 mb-5">
              <div class="col-lg-6">
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">1.</span>
                    <span class="title">Оповещаете ли вы клиентов о прибытие почтовой посылки?</span>
                    <div class="btn-faq btn-faq-minus"></div>
                  </div>
                  <div class="faq-list-item-text" style="display: block;">
                    <p>Да, при подтверждении заказа (статус Подтвержден) - клиенту уходит смс, при отправке посылки (статус Отправлен) клиенту высылаем смс с трек - номер посылки и уведомлением о вложении, о прибытие посылки на почту (статус Доставлен) также уведомляем по смс. И каждые 7 дней смс напоминания, чтоб клиент забрал свою посылку.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">2.</span>
                    <span class="title">Мой заказ выкупил клиент, когда я получу деньги?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Если товар был отправлен Новой почтой - по регламенту почты  на следующий день, после выкупа клиентом товара, деньги приходят в наше отделение. Забираем платежи 1-2 раза в неделю. Соответственно вы получите деньги в течение 2-3-х дней после того, как клиент забрал посылку.</p>
                    <p>Если Укрпочта – механизм оплаты похожий, но на 1-2 дня медленнее, чем Новой Почтой. Поэтому вы получите свои деньги в течение 3-4-х дней после выкупа посылки клиентом.</p>
                    <p>То есть, оплату мы проводим сразу, как получаем деньги, а не 2 раза в месяц как многие другие дропшиппинг поставщики.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">3.</span>
                    <span class="title">Оформил заказ, когда отправите?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Заказ подтвержденный до 19-00 уезжает на следующий день, после 19-00 через день. При наличии товара на складе. По воскресеньям отправок нет. Когда заказ будет отправлен, он перейдет в статус Заказ ушел на отправку. Трек номера проставляются через день после отправки.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">4.</span>
                    <span class="title">Сколько времени товар лежит бесплатно на почте, пока его не заберут?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Товар лежит бесплатно 5 рабочих дней, после чего начинается платное хранение по тарифам почты, поэтому если в течение 5 рабочих дней клиент не забирает посылку, мы отзываем её назад.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">5.</span>
                    <span class="title">Кто оплачивает доставку, если клиент не пришел за посылкой или отказался от товара на почте?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Если клиент не появился на почту в течение 5 рабочих дней после прибытия товара, мы отзываем посылку назад, в таком случае доставку покрываете вы. Если вы отказываетесь покрывать доставку – вы попадаете в черный список Online Naxodka  и наше сотрудничество прекращается.</p>
                    <p>Если клиент отказался от товара на почте по причине того, что ему пришел не тот товар, который он заказывал либо он оказался поврежденным (бракованным) – в таком случае доставку оплачиваем полостью мы.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">6.</span>
                    <span class="title">У вас есть доставка в другие страны - СНГ, Европу?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>На данный момент, мы работаем только по Украине. Но мы можем отправлять в другие страны СНГ, при условии, что товар будет оплачен предварительно полностью с учетом стоимости доставки.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">7.</span>
                    <span class="title">Как я могу отследить посылку?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Вам будет предоставлены номера ТТН, благодаря которым вы сможете отслеживать посылки на сайтах почты, которой был отправлен товар.</p>
                  </div>
                </div>
                <div class="faq-list-item">
                  <div class="faq-list-item-title">
                    <span class="num">8.</span>
                    <span class="title">Вы каждый день отправляете посылки?</span>
                    <div class="btn-faq btn-faq-plus"></div>
                  </div>
                  <div class="faq-list-item-text">
                    <p>Нет. Мы отправляем посылки с понедельника по субботу. Воскресенье выходной, а также праздничные, по которым не работают почтовые сервисы.</p>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </section>
    <section class="layout layout11">
        <div class="container">
          <div class="faq-register">
            <p class="faq-register-title"><span>Если вы не нашли вопрос,</span> который вас интересует</p>
            <p class="subtitle">вы можете задать его в разделе Поддержка, зарегистрировавшись в системе:</p>
            <p class="text-center pt-4">
                <a href="/register/" class="btn btn-success btn-block">Зарегистрироваться</a>
            </p>
          </div>
        </div>
    </section>
    <?/*?>
    <div class="modal fade" id="formSubscribe">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST" class="modal-form">
                <input type="hidden" name="screen" value="0">
                <input type="hidden" name="calc" value="0">
                <p class="title">Введите данные ниже для регистрации</p>
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Ваше имя" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Ваш email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Ваш телефон" data-inputmask="'mask': '+38(099) 999 9999'" required>
                </div>
                <button type="submit" class="btn btn-success btn-block">Зарегистрироваться</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?*/?>
    <script src="/assets/js/jquery-2.2.4.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.inputmask.bundle.min.js"></script>
    <script src="/assets/js/jquery.spincrement.js"></script>
    <script src="/assets/js/sweetalert2.min.js"></script>
    <script src="/assets/js/jquery-ui-1.12.1.js"></script>
    <script src="/assets/js/landdrop.js"></script>
</body>
</html>