<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$goods_id = (isset($_POST['goods'])) ? mysqli_real_escape_string($db, $_POST['goods']) : '';
		$goods_id = test_request($goods_id);
		$goods_id = intval($goods_id);

		$goods_price = (isset($_POST['price'])) ? mysqli_real_escape_string($db, $_POST['price']) : '';
		$goods_price = test_request($goods_price);
		$goods_price = ceil($goods_price);

		if ($goods_id > 0 and $goods_price > 0) {

			$sql = "SELECT * FROM `goods` WHERE `id`='{$goods_id}' AND `status`=1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods_post = mysqli_fetch_assoc($query);

			if (mysqli_num_rows($query) > 0) {

				$linkname = $goods_post['category'];
				$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$catalog = mysqli_fetch_assoc($query);

				if ($goods_post['currency'] == 1) {

					$kurs_currency = 1;

				} else if ($goods_post['currency'] == 2) {

					$kurs_currency = $api_exchange_rate_usd['sale'];

					if ($goods_post['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

						$kurs_currency = $goods_post['currency_top_kurs'];

					}

				} else if ($goods_post['currency'] == 3) {

					$kurs_currency = $api_exchange_rate_eur['sale'];

					if ($goods_post['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

						$kurs_currency = $goods_post['currency_top_kurs'];

					}

				}

				$price_min = ceil(($goods_post['price_sale'] - (($goods_post['price_sale'] - $goods_post['price_purchase']) * $catalog['rate'])) * $kurs_currency);

				if ($user['p_rate'] > 0) {

					if ($goods_post['price_agent'] > 0 and $goods_post['price_agent'] < $goods_post['price_purchase']) {

						$price_min = ceil(($goods_post['price_sale'] - (($goods_post['price_sale'] - $goods_post['price_agent']) * $user['p_rate'])) * $kurs_currency);

					} else {

						$price_min = ceil(($goods_post['price_sale'] - (($goods_post['price_sale'] - $goods_post['price_purchase']) * $user['p_rate'])) * $kurs_currency);

					}

				}

				if ($goods_price < $price_min) $goods_price = $price_min;

				if (empty($_SESSION['cart'])) {

					$_SESSION['cart']['goods'][] = $goods_id;
					$_SESSION['cart']['price'][] = $goods_price;

				} else {

					if (!in_array($goods_id, $_SESSION['cart']['goods'])) {

						$_SESSION['cart']['goods'][] = $goods_id;
						$_SESSION['cart']['price'][] = $goods_price;

					}

				}

			}

		}

		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;

	}

}

if (!empty($_GET['linkname'])) {

	$linkname = test_request($_GET['linkname']);

	$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$catalog = mysqli_fetch_assoc($query);

	function displayBreadcrumbGoods($db, $lang, $level_id) {
	
		$sql = "SELECT * FROM `catalog` WHERE `id`='{$level_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		while ($catalog = mysqli_fetch_assoc($query)) {
			
			displayBreadcrumbGoods($db, $lang, $catalog['level_id']);

			if ($catalog['level_id'] != 0) $_SESSION['breadcrumb'][$catalog['linkname']] = $catalog['name_'.$lang];

		}

	}
 
	displayBreadcrumbGoods($db, $lang, $catalog['id']);

	if (!empty($_SESSION['breadcrumb'])) {
		foreach ($_SESSION['breadcrumb'] as $session_breadcrumb_link => $session_breadcrumb_name) {
			$breadcrumb['names'][] = $session_breadcrumb_name;
			$breadcrumb['links'][] = '/account/goods/'.$session_breadcrumb_link;
		}
	}

	unset($_SESSION['breadcrumb']);

}

if (!empty($_GET['goods'])) {

	$goods_id = intval($_GET['goods']);

	$sql = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$goods = mysqli_fetch_assoc($query);

	$goods['name'] = json_decode($goods['name'], true);

	$breadcrumb['names'][] = $goods['name'][$lang];
	$breadcrumb['links'][] = '';

}

function countGoodsInCategory($db, $category_id, $count_goods) {

	$sql = "SELECT `id` FROM `catalog` WHERE `level_id`='{$category_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$count_subcategories = mysqli_num_rows($query);

	if ($count_subcategories > 0) {

		while ($subcategories = mysqli_fetch_assoc($query)) {

			$count_goods_in_category_pre = countGoodsInCategory($db, $subcategories['id'], $count_goods);

			$count_goods_in_category += $count_goods_in_category_pre;

		}

	} elseif ($count_subcategories == 0) {

		$sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$category = mysqli_fetch_assoc($query);

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}' AND `status`=1";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_in_category = mysqli_num_rows($query);

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}

?>