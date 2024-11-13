<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?=$alert_message?>

<?

$only_payment3 = false;

if (isset($_SESSION['cart'])) {

    for ($i = 0; $i < count($_SESSION['cart']['goods']); $i++) {

        $cart_goods_id = intval($_SESSION['cart']['goods'][$i]);

        $sql = "SELECT * FROM `goods` WHERE `id`='{$cart_goods_id}'";
        $query = mysqli_query($db, $sql) or die(mysqli_error());
        $goods = mysqli_fetch_assoc($query);

        if ($goods['user_id'] == 5184) {

            $only_payment3 = true;

        }

    }

}

if (!isset($_SESSION['cart_post']['surname'])) $_SESSION['cart_post']['surname'] = '';
if (!isset($_SESSION['cart_post']['name'])) $_SESSION['cart_post']['name'] = '';
if (!isset($_SESSION['cart_post']['middlename'])) $_SESSION['cart_post']['middlename'] = '';
if (!isset($_SESSION['cart_post']['phone'])) $_SESSION['cart_post']['phone'] = '';
if (!isset($_SESSION['cart_post']['locality'])) $_SESSION['cart_post']['locality'] = '';
if (!isset($_SESSION['cart_post']['branch'])) $_SESSION['cart_post']['branch'] = '';
if (!isset($_SESSION['cart_post']['street'])) $_SESSION['cart_post']['street'] = '';
if (!isset($_SESSION['cart_post']['house'])) $_SESSION['cart_post']['house'] = '';
if (!isset($_SESSION['cart_post']['flat'])) $_SESSION['cart_post']['flat'] = '';
if (!isset($_SESSION['cart_post']['comment'])) $_SESSION['cart_post']['comment'] = '';

?>

<form accept="/account/orders/" method="POST" class="cart">
    <div class="row">
        <div class="col-sm-4">
            <div class="card mb-3 border-info" style="border-width: 5px;border-radius: 10px;">
                <div class="card-header text-white bg-info">Получатель</div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" name="surname" class="form-control" placeholder="Фамилия*" onkeyup="cartCheckError()" value="<?=$_SESSION['cart_post']['surname']?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Имя*" onkeyup="cartCheckError()" value="<?=$_SESSION['cart_post']['name']?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="middlename" class="form-control" placeholder="Отчество" value="<?=$_SESSION['cart_post']['middlename']?>">
                    </div>
                    <div class="form-group mb-0">
                        <input type="text" name="phone" class="form-control" placeholder="Телефон*" data-inputmask="'mask': '+38 (999) 999 9999'" onkeyup="cartCheckError()" value="<?=$_SESSION['cart_post']['phone']?>" required>
                    </div>
                </div>
            </div>
            <!-- <input type="hidden" name="delivery" value="1"> -->
            <div class="card mb-3">
                <div class="card-header">Способ доставки</div>
                <div class="card-body">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="delivery1" name="delivery" class="custom-control-input" value="1" onchange="cartChangeDelivery(this, 'Адрес доставки &laquo;Нова пошта&raquo;', 'text|text', 'locality|branch', 'Населенный пункт|Отделение')" checked>
                        <label class="custom-control-label" for="delivery1">Нова пошта</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="delivery2" name="delivery" class="custom-control-input" value="2" onchange="cartChangeDelivery(this, 'Адрес доставки &laquo;Укрпошта&raquo;', 'text|text|text|text|text', 'region|district|city|address|index', 'Область|Район|Город/Поселек|Адрес (улица, дом, кв.)|Индекс')">
                        <label class="custom-control-label" for="delivery2">Укрпошта</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="delivery3" name="delivery" class="custom-control-input" value="3" onchange="cartChangeDelivery(this, 'Адрес самовывоза')">
                        <label class="custom-control-label" for="delivery3">Самовывоз</label>
                    </div>
                </div>
            </div>
            <div class="card mb-3 address border-warning" style="border-width: 5px;border-radius: 10px;">
                <div class="card-header bg-warning">Адрес доставки &laquo;Нова пошта&raquo;</div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <style type="text/css">
                        .nav-link {
                            padding: .2rem 1rem;
                        }
                        .nav-pills .nav-link.active {
                            color: #000!important;
                            background-color: #f8f9fa;
                            padding: .2rem 1rem;
                        }
                        </style>
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-tobranch-tab" data-toggle="pill" href="#pills-tobranch" role="tab" aria-controls="pills-tobranch" aria-selected="true">В отделение/пошт.</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-toaddress-tab" data-toggle="pill" href="#pills-toaddress" role="tab" aria-controls="pills-toaddress" aria-selected="false">По адресу</a>
                        </li>
                    </ul>
                    <div class="form-group">
                        <input type="text" name="locality" class="form-control" placeholder="Населенный пункт*" onkeyup="apiNovaPoshtaLocality(this)" value="<?=$_SESSION['cart_post']['locality']?>" required>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-tobranch" role="tabpanel" aria-labelledby="pills-tobranch-tab">
                            <div class="form-group mb-0">
                                <input type="text" name="branch" class="form-control" placeholder="Отделение*" value="<?=$_SESSION['cart_post']['branch']?>">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-toaddress" role="tabpanel" aria-labelledby="pills-toaddress-tab">
                            <div class="form-group">
                                <input type="text" name="street" class="form-control" placeholder="Улица*" value="<?=$_SESSION['cart_post']['street']?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="house" class="form-control" placeholder="Дом*" value="<?=$_SESSION['cart_post']['house']?>">
                            </div>
                            <div class="form-group mb-0">
                                <input type="text" name="flat" class="form-control" placeholder="Квартира" value="<?=$_SESSION['cart_post']['flat']?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 payment border-success" data-balance="<?=$user['cash']?>" data-admin="<?if($user['admin']==1)echo'1';else echo'0';?>" style="border-width: 5px;border-radius: 10px;">
                <div class="card-header text-white bg-success">Способ оплаты</div>
                <div class="card-body">

                    <input type="hidden" name="add_funds" value="0">

                    <div id="formPrePayment" class="mb-3">
                        <!-- <label for="inputPrePayment" class="font-italic">Укажите сумму <b>предоплаты</b>, если вы взяли предоплату у клиента или вам нужно просто внести предоплату.</label> -->
                        <label for="inputPrePayment" class="text-center">Укажите <b>сумму предоплаты</b>, полученную от клиента</label>
                        <div class="input-group">
                            <input type="number" name="prepayment" id="inputPrePayment" class="form-control" placeholder="Сумма" step="1" onkeyup="cartPrePayment(this)" value="<?=$_SESSION['cart_post']['prepayment']?>">
                            <div class="input-group-append">
                                <span class="input-group-text p-1">грн</span>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-primary">
                        <small class="font-weight-bold">Предоплата взимается с внутреннего баланса.</small>
                    </p> -->

                    <?//Payment Method 1?>

                    <?if(!$only_payment3):?>

                    <div class="custom-control custom-radio">
                        <input type="radio" id="payment1" name="payment" class="custom-control-input" value="1" onchange="cartChangePayment(this)"<?=((empty($_SESSION['cart_post']['payment']) or $_SESSION['cart_post']['payment']==1) ? ' checked' : '')?>>
                        <label class="custom-control-label font-weight-bold" for="payment1">Наложенный платеж <b class="text-danger"><span id="amountWithoutPrePayment">0</span> грн.</b></label>
                    </div>

                    <div id="messSuccessPayment1" class="mess-success-payment mt-2 mb-3">
                        <small class="text-success">На вашем <a href="/account/wallet/" class="text-success font-weight-bold">внутреннем балансе</a> будет зарезирвировано сумму <b><span class="reserve-balance-payment"></span> грн.</b><!--  в качестве страховой суммы от возврата заказа --></small>
                    </div>

                    <div id="messWarningPayment1" class="mess-warning-payment mt-2 mb-3">
                        <small class="text-secondary">На вашем <a href="/account/wallet/" class="text-secondary font-weight-bold">внутреннем балансе</a> должно быть минимум <b><span class="reserve-balance-payment"></span> грн.</b><!--  в качестве страховой суммы от возврата заказа --></small>
                        <br>
                        <small class="text-danger">Вам нехватает <span class="dif-reserve-balance-payment"></span> грн. на <a href="/account/wallet/" class="text-danger font-weight-bold">внутреннем балансе</a> чтобы оформить заказ.</small>
                        <br>
                        <div class="text-center mt-1 mb-0">
                            <input type="submit" class="btn btn-success btn-sm" value="Пополнить баланс">
                        </div>
                    </div>

                    <?endif;?>

                    <?//Payment Method 3?>

                    <div class="custom-control custom-radio">
                        <input type="radio" id="payment3" name="payment" class="custom-control-input" value="3" onchange="cartChangePayment(this)"<?=(($only_payment3 or (isset($_SESSION['cart_post']['payment']) and $_SESSION['cart_post']['payment']==3)) ? ' checked' : '')?>>
                        <label class="custom-control-label font-weight-bold" for="payment3">Внутренний баланс 100%</label>
                    </div>

                    <div id="messSuccessPayment3" class="mess-success-payment d-none mt-2 mb-3">
                        <small class="text-success">Вы выбрали способ оплаты "Внутренний баланс 100%". При оформлении заказа на вашем <a href="/account/wallet/" class="text-success font-weight-bold">внутреннем балансе</a> будет зарезирвировано сумму <span class="off-balance-payment"></span> грн. для полной оплаты заказа.</small>
                    </div>

                    <div id="messWarningPayment3" class="mess-warning-payment d-none mt-2 mb-3">
                        <small class="text-secondary">На вашем <a href="/account/wallet/" class="text-secondary font-weight-bold">внутреннем балансе</a> должно быть минимум <span class="off-balance-payment"></span> грн. для полной оплаты заказа.</small>
                        <br>
                        <small class="text-danger">Вам нехватает <span class="dif-off-balance-payment"></span> грн. на <a href="/account/wallet/" class="text-danger font-weight-bold">внутреннем балансе</a> чтобы оформить заказ.</small>
                        <br>
                        <div class="text-center mt-1 mb-0">
                            <input type="submit" class="btn btn-success btn-sm" value="Пополнить баланс">
                        </div>
                    </div>

                    <?//Payment Method 2?>

                    <?if (!$only_payment3):?>

                    <div class="custom-control custom-radio">
                        <input type="radio" id="payment2" name="payment" class="custom-control-input" value="2" onchange="cartChangePayment(this)"<?=((isset($_SESSION['cart_post']['payment']) and $_SESSION['cart_post']['payment']==2) ? ' checked' : '')?>>
                        <label class="custom-control-label font-weight-bold" for="payment2">Оформление услуги</label>
                    </div>

                    <div id="messSuccessPayment2" class="mess-success-payment d-none mt-2 mb-3">
                        <small class="text-success">Данный способ предусмотрен для оформления услуги.</small>
                    </div>

                    <!-- <div class="custom-control custom-radio">
                        <input type="radio" id="payment2" name="payment" class="custom-control-input" value="2" onchange="cartChangePayment(this)">
                        <label class="custom-control-label" for="payment2">Перевод на карту 100% (только для услуг)</label>
                    </div>

                    <div id="messSuccessPayment2" class="mess-success-payment d-none mt-2 mb-3">
                        <small class="text-success">Вы выбрали способ оплаты "Перевод на карту 100%". После подтверждения заказа, с Вами свяжется наш менеджер, уточнит заказ и подскажет как произвести перевод средств (полной суммы оплаты заказа) на наш номер карты <b><big>4731 2191 0710 1861</big></b>. Этот способ оплаты удобен, если нужно чтобы клиент напрямую оплатил заказ и не тратиться дважды на комиссию.</small>
                        <?/*?>
                        <small class="text-success"><b class="text-danger">Выбирайте этот метод только для оформления заказа на услугу!</b><br>Вы выбрали способ оплаты "Перевод на карту 100%". После подтверждения заказа, с Вами свяжется наш менеджер, уточнит заказ и подскажет как произвести перевод средств (полной суммы оплаты заказа) на наш номер карты <b><big>5168 7427 1961 8781</big></b>. Этот способ оплаты удобен, если нужно чтобы клиент напрямую оплатил заказ и не тратиться дважды на комиссию.</small><?*/?>
                        <br>
                        <small class="text-primary font-weight-bold">Обратите внимание: Ваш заказ не будет отправлен до получения нами оплаты.</small>
                    </div> -->

                    <?endif;?>

                    <p id="additional_info_about_marketplaces" class="mt-3 mb-0 d-none">
                        Заказ оформлен через:<br>
                        - <b style="color: #ff8300;">OLX доставку</b><br>
                        - <b style="color: #51499d;">Prom оплату</b><br>
                        оформляются через внутренний баланс
                    </p>

                </div>
            </div>
            <p>Отгрузка товара осуществляется в течение 1-3х дней</p>
        </div>
        <div class="col-sm-8">
            <div class="card mb-3">
                <div class="card-header">Корзина товаров</div>
                <div class="card-body table-responsive">
                    <table id="listGoods" class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Фото</th>
                                <th scope="col">Наименование товара</th>
                                <th scope="col">Количество</th>
                                <th scope="col" style="min-width: 100px;">Цена / ед.</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
<?

                            if (!empty($_SESSION['cart'])) {

                                $sum_cart_goods_price = 0;
                                $sum_earnings = 0;

                                for ($i = 0; $i < count($_SESSION['cart']['goods']); $i++) {

                                    $cart_goods_id = intval($_SESSION['cart']['goods'][$i]);
                                    $cart_goods_price = $_SESSION['cart']['price'][$i];

                                    $sql = "SELECT * FROM `goods` WHERE `id`='{$cart_goods_id}'";
                                    $query = mysqli_query($db, $sql) or die(mysqli_error());
                                    $goods = mysqli_fetch_assoc($query);

                                    $goods_linkname = $goods['category'];

                                    $sql = "SELECT * FROM `catalog` WHERE `linkname`='{$goods_linkname}'";
                                    $query = mysqli_query($db, $sql) or die(mysqli_error());
                                    $catalog = mysqli_fetch_assoc($query);

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

                                    if ($user['p_rate'] > 0) {

                                        if ($goods['price_agent'] > 0 and $goods['price_agent'] < $goods['price_purchase']) {

                                            $price_purchase = ceil($goods['price_agent'] * $kurs_currency);

                                        }

                                        $price_min = ceil($price_sale - (($price_sale - $price_purchase) * $user['p_rate']));

                                    }

                                    $cart_goods_price_one = $cart_goods_price;
                                    $tmp_earnings_one = $cart_goods_price-$price_min;

                                    if (!empty($_SESSION['cart_post']['availability'][$i])) {

                                        $cart_goods_price = $cart_goods_price * $_SESSION['cart_post']['availability'][$i];
                                        $price_min = $price_min * $_SESSION['cart_post']['availability'][$i];

                                    }

                                    $sum_cart_goods_price += $cart_goods_price;
                                    $tmp_earnings = $cart_goods_price-$price_min;
                                    $sum_earnings += $tmp_earnings;

                                    $goods['photo'] = json_decode($goods['photo'], true);
                                    $goods['name'] = json_decode($goods['name'], true);

                                    if (!file_exists('../data/images/goods/'.$goods['photo']['img0'])) {
                                        $goods['photo']['img0'] = 'no_image.png';
                                    }

                                    list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img0']);

                                    if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
                                    else $goods_photo_size = 'max-height';

                                    if ($goods['availability'] == 0) $goods['availability'] = 500;

?>

                            <tr class="cart-list-goods" data-goods-price="<?=$cart_goods_price?>" data-earnings="<?=$tmp_earnings?>">
                                <td>
                                    <input type="hidden" name="goods[]" value="<?=$goods['id']?>">
                                    <div class="<?=$goods_photo_size?>">
                                        <img src="/data/images/goods/<?=$goods['photo']['img0']?>">
                                    </div>
                                </td>
                                <td>
                                    <a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" target="_blank"><?=$goods['name'][$lang]?></a>
                                </td>
                                <td>
                                    <select name="availability[]" class="form-control" onchange="cartChangeAvailabilityGoods(this, <?=$cart_goods_price_one?>, <?=$tmp_earnings_one?>)" required>
                                        <option value="1"<?=((empty($_SESSION['cart_post']['availability'][$i]) or $_SESSION['cart_post']['availability'][$i]==1) ? ' selected' : '')?>>1</option>
<?

                                        for ($j=1; $j < $goods['availability']; $j++) {
                                            if ($_SESSION['cart_post']['availability'][$i] == ($j+1))
                                                echo '<option value="'.($j+1).'" selected>'.($j+1).'</option>';
                                            else
                                                echo '<option value="'.($j+1).'">'.($j+1).'</option>';
                                        }

?>                                        
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="goods_price[]" value="<?=$cart_goods_price_one?>">
                                    <span class="this-price-goods"><?=$cart_goods_price_one?></span> грн.
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-danger btn-sm p-0" onclick="cartDeleteGoods(<?=$goods['id']?>)"><i class="material-icons float-left">close</i></button>
                                </td>
                            </tr>

<?

                                }

                                if ($sum_earnings < 0) $sum_earnings = 0;

?>

                            <tr>
                                <td colspan="5" class="text-right ">
                                    <p class="mt-3 mb-0">Стоимость товаров - <span id="sumGoodsPrices"><?=$sum_cart_goods_price?></span> грн.</p>
                                    <p>Ваш потенциальный доход - <b><span id="sumEarnings"><?=$sum_earnings?></span> грн.</b><i class="material-icons help_outline" data-toggle="tooltip" title="Выбирая способ оплаты Наложенный платеж после оформления заказа с вашего потенциального дохода будет изьята сумма 0.5% от суммы заказа как комиссия банка.">help_outline</i></p>
                                </td>
                            </tr>

<?                                

                            } else {

                                echo '<tr><td colspan="5" class="text-center">Корзина пустая</td></tr>';

                            }

?>                            
                        </tbody>
                    </table>
                    <?/*if (!empty($_SESSION['cart'])):?>
                    <p class="text-right">
                        <button type="button" class="btn btn-danger btn-sm" onclick="cartRemoveGoods()">Очистить корзину</button>
                    </p>
                    <?endif;*/?>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light">Комментарий</div>
                <div class="card-body bg-light">
                    <textarea name="comment" class="form-control" rows="5" placeholder="Для товаров категории Одежда обязательно напишите точный рост и вес. Для товаров категории Обувь обязательно напишите размер стельки в сантиметрах. Также вы можете оставить любой свой комментарий при желании."><?=$_SESSION['cart_post']['comment']?></textarea>
                </div>
            </div>
        </div>
    </div>
    <p class="text-center mt-3">
        <button type="submit" class="btn btn-secondary btn-lg" onclick="return confirm('Вы подтверждаете что все правильно и вы проверили заказ?')" <?if(empty($_SESSION['cart'])) echo 'disabled';?>>Оформить заказ</button>
    </p>
</form>
<form method="POST" id="cartDeleteGoods">
    <input type="hidden" name="cart_goods" value="">
</form>
<form method="POST" id="cartRemoveGoods">
    <input type="hidden" name="remove_all_goods" value="1">
</form>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>