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

	//sleep(1);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			$lang = (isset($_POST['lang'])) ? mysqli_real_escape_string($db, $_POST['lang']) : '';
			$lang = test_request($lang);

			if (empty($lang) or $lang != 'uk' or $lang != 'ru') $lang = 'ru';

			$search = (isset($_POST['search'])) ? mysqli_real_escape_string($db, $_POST['search']) : '';
			$search = test_request($search);
			$search = str_replace("'", "\'", $search);

			$order_id = (isset($_POST['order_id'])) ? mysqli_real_escape_string($db, $_POST['order_id']) : '';
			$order_id = test_request($order_id);
			$order_id = intval($order_id);
				
			if (!empty($search)) {

				$sql = "SELECT g.* FROM goods g LEFT JOIN catalog c ON c.linkname = g.category WHERE (g.id = '{$search}' OR g.name LIKE '%{$search}%') AND g.status = 1 AND g.availability > 0 AND c.buffer = 0 ORDER BY g.id DESC LIMIT 10";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if (mysqli_num_rows($query) > 0) {

?>

			<div class="row">

<?

					while ($goods = mysqli_fetch_assoc($query)) {

						$goods['photo'] = json_decode($goods['photo'], true);
						$goods['name'] = json_decode($goods['name'], true);

                        if (!file_exists('../../data/images/goods/'.$goods['photo']['img0'])) {
                            $goods['photo']['img0'] = 'no_image.png';
                        }

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

						$price_sale = ceil($goods['price_sale'] * $kurs_currency);

?>

					<div class="col-sm-6 mb-3 text-center">
						<form method="POST">
							<input type="hidden" name="order" value="<?=$order_id?>">
							<input type="hidden" name="goods_id" value="<?=$goods['id']?>">
							<input type="hidden" name="goods_availability" value="1">
							<input type="hidden" name="goods_price" value="<?=$price_sale?>">
							<div class="form-group">
								<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" target="_blank">
									<p class="text-center">
										<img src="/data/images/goods/<?=$goods['photo']['img0']?>" class="img-fluid" alt="imgGoods" style="max-height: 150px;">
									</p>
									<h3><?=$goods['name'][$lang]?></h3>
								</a>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success">Добавить товар</button>
							</div>
						</form>
					</div>

<?

					}

?>

			</div>
		

<?

				} else {

					echo '<p class="mt-5 text-center">По данному запросу поиска не найдено ни одного товара</p>';

				}

			} else {

				echo '<p class="mt-5 text-center">Введите поисковый запрос товара</p>';

			}

		}

	}

}

?>