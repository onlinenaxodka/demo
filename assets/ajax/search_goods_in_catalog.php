<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_eur = mysqli_fetch_assoc($query);
$api_exchange_rate_eur['buy'] = number_format($api_exchange_rate_eur['buy'], 2, '.', '');
$api_exchange_rate_eur['sale'] = number_format($api_exchange_rate_eur['sale'], 2, '.', '');

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	sleep(1);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			$lang = (isset($_POST['lang'])) ? mysqli_real_escape_string($db, $_POST['lang']) : '';
			$lang = test_request($lang);

			if (empty($lang) or $lang != 'uk' or $lang != 'ru') $lang = 'ru';

			$type_search = (isset($_POST['type_search'])) ? mysqli_real_escape_string($db, $_POST['type_search']) : '';
			$type_search = test_request($type_search);
			$type_search = intval($type_search);

			$search = (isset($_POST['search'])) ? mysqli_real_escape_string($db, $_POST['search']) : '';
			$search = test_request($search);
			$search = str_replace("'", "\'", $search);
				
			if (!empty($search)) {

				$sql = "SELECT * FROM `goods` WHERE `category` IN (SELECT `linkname` FROM `catalog` WHERE `buffer`=0) AND `status`=1 ORDER BY `availability` DESC, SUBSTRING_INDEX(SUBSTRING_INDEX(name, '\"', -2), '\"', 1) ASC LIMIT 20";
				if ($type_search == 1)
					$sql = "SELECT * FROM `goods` WHERE `id`='{$search}' AND `category` IN (SELECT `linkname` FROM `catalog` WHERE `buffer`=0) AND `status`=1";
				elseif ($type_search == 2)
					$sql = "SELECT * FROM `goods` WHERE (`vendor_code`='{$search}' OR `vendor_id`='{$search}') AND `category` IN (SELECT `linkname` FROM `catalog` WHERE `buffer`=0) AND `status`=1";
				elseif ($type_search == 3)
					$sql = "SELECT * FROM `goods` WHERE `category` IN (SELECT `linkname` FROM `catalog` WHERE `buffer`=0) AND `name` LIKE '%{$search}%' AND `status`=1 ORDER BY `availability` DESC, SUBSTRING_INDEX(SUBSTRING_INDEX(name, '\"', -2), '\"', 1) ASC LIMIT 100";

				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if (mysqli_num_rows($query) > 0) {

?>
	
		<ul class="list-goods mt-4 text-center">

<?

					while ($goods = mysqli_fetch_assoc($query)) {

						$linkname = $goods['category'];

						$sql_rate = "SELECT `rate`, `buffer` FROM `catalog` WHERE `linkname`='{$linkname}'";
						$query_rate = mysqli_query($db, $sql_rate) or die(mysqli_error($db));
						$catalog = mysqli_fetch_assoc($query_rate);

						$goods['photo'] = json_decode($goods['photo'], true);
						$goods['name'] = json_decode($goods['name'], true);

                        if (!file_exists('../../data/images/goods/'.$goods['photo']['img0'])) {
                            $goods['photo']['img0'] = 'no_image.png';
                        }

						list($goods_photo_w, $goods_photo_h) = getimagesize('../../data/images/goods/'.$goods['photo']['img0']);

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

						$price_min = ceil(($goods['price_sale'] - (($goods['price_sale'] - $goods['price_purchase']) * $catalog['rate'])) * $kurs_currency);
						$price_sale = ceil($goods['price_sale'] * $kurs_currency);

?>

					<li>
						<a href="/account/goods/<?=$linkname?>/<?=$goods['id']?>">
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

<?

							if ($goods['availability'] == 0) {
								$goods_availability_word = '<span class="badge alert-primary">Под заказ</span>';
							} elseif ($goods['availability'] > 0 and $goods['availability'] < 5) {
								$goods_availability_word = '<span class="badge alert-warning">Заканчивается</span>';
							} elseif ($goods['availability'] >= 5) {
								$goods_availability_word = '<span class="badge alert-success">В наличие</span>';
							} else {
								$goods_availability_word = '<span class="badge alert-danger">Нет в наличие</span>';
							}

?>

						<p class="text-dark mb-2"><b><?=$goods_availability_word?></b></p>
						<div class="row">
							<div class="col-6">
								<p class="text-secondary mb-1">Дроп цена</p>
							</div>
							<div class="col-6">
								<p class="text-secondary mb-1">Реком. цена</p>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<h4 style="color: red;"><b><?=$price_min?> грн</b></h4>
							</div>
							<div class="col-6">
								<h4 style="color: #28a745;"><b><?=$price_sale?> грн</b></h4>
							</div>
						</div>

						<div class="row pt-3 pb-3 ml-0 mr-0" style="background: #f3f3f3">
							<div class="col-8 pr-0">
								<a href="/account/goods/<?=$linkname?>/<?=$goods['id']?>" class="btn btn-success btn-block">Открыть товар</a>
							</div>
							<div class="col-4 pl-2">
								<form method="POST">
									<input type="hidden" name="goods" value="<?=$goods['id']?>">
									<input type="hidden" name="price" value="<?=$price_sale?>">
									<button type="submit" class="btn btn-warning btn-block" style="padding: .2rem .75rem;"><i class="fa fa-cart-plus" style="font-size: 30px;"></i></button>
								</form>
							</div>
						</div>
					</li>

<?

					}

?>

		</ul>

<?

				} else {

					echo '<p class="mt-5 pt-5 text-center">По данному запросу поиска не найдено ни одного товара</p>';

				}

			} else {

				echo '<p class="mt-5 pt-5 text-center">Введите поисковый запрос товара</p>';

			}

		}

	}

}

?>