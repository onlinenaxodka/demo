<?php

$orders_post_goods = json_decode($orders_post['goods'], true);

$agent_margin = 0;
$agent_data = array();

$fond = 0;
$admin_r = 0;
$admin_z = 0;
$admin_t = 0;
$admin_d = 0;
$director = 0;
$supervisor = 0;
$manager = 0;
$admin_on_rate_dif = 0;
$admin_on_amount_left = 0;
$goods_price_purchase_sum = 0;

$admin_on_rates = array();

for ($i=0; $i < count($orders_post_goods); $i++) {

	$goods_id = $orders_post_goods[$i]['id'];
	$goods_availability = $orders_post_goods[$i]['availability'];
	$goods_price_agent = $orders_post_goods[$i]['goods_price_agent'];
	$goods_price_purchase = $orders_post_goods[$i]['goods_price_purchase'];
	$goods_price_recom = $orders_post_goods[$i]['goods_price_recom'];
	$goods_price_drop = $orders_post_goods[$i]['goods_price'];
	$catalog_rate = $orders_post_goods[$i]['catalog_rate'] * 100;

	$goods_prices_margin = ($goods_price_recom - $goods_price_purchase) * $goods_availability;

	$sql = "SELECT * FROM `marketing` WHERE `dropshipper`='{$catalog_rate}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$marketing = mysqli_fetch_assoc($query);

	$fond +=       $goods_prices_margin * $marketing['fond'] * 0.01;
	$admin_r +=    $goods_prices_margin * $marketing['roma'] * 0.01;
	$admin_z +=    $goods_prices_margin * $marketing['zgenia'] * 0.01;
	$admin_t +=    $goods_prices_margin * $marketing['tema'] * 0.01;
	$admin_d +=    $goods_prices_margin * $marketing['dima'] * 0.01;
	$director +=   $goods_prices_margin * $marketing['director'] * 0.01;
	$supervisor += $goods_prices_margin * $marketing['supervisor'] * 0.01;
	$manager +=    $goods_prices_margin * $marketing['manager'] * 0.01;

	$sql = "SELECT `user_id` FROM `goods` WHERE `id`='{$goods_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$goods = mysqli_fetch_assoc($query);

	$goods_user_id = $goods['user_id'];

	$sql = "SELECT * FROM `users` WHERE `id`='{$goods_user_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user_provider = mysqli_fetch_assoc($query);

	$user_partner_id = $user_provider['partner_id'];

	$sql_mentor = "SELECT * FROM `users` WHERE `id`='{$user_partner_id}'";
	$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
	$user_mentor = mysqli_fetch_assoc($query_mentor);

	$agent_margin = ($goods_price_purchase - $goods_price_agent) * $goods_availability;

	if ($user_mentor['agent'] == 1 and $goods_price_agent > 0 and $goods_price_agent < $goods_price_purchase) {

		$agent_data_key = 'user_'.$user_mentor['id'];

		if (empty($agent_data)) {

			$agent_data[$agent_data_key] = array($user_mentor['id'], $agent_margin);
			
		} else {

			if (!in_array($agent_data_key, array_keys($agent_data))) {

				$agent_data[$agent_data_key] = array($user_mentor['id'], $agent_margin);

			} else {

				$agent_data[$agent_data_key][1] += $agent_margin;

			}

		}

	}

	if ($marketing['adminon'] > 0) {
		$admin_on_rates[] = $marketing['adminon'].'%';
		$admin_on_rate_dif += $goods_prices_margin * $marketing['adminon'] * 0.01;
	}

	$goods_price_purchase_sum += $goods_price_purchase * $goods_availability;

}

$fond = intval($fond * 100) / 100;
$admin_r = intval($admin_r * 100) / 100;
$admin_z = intval($admin_z * 100) / 100;
$admin_t = intval($admin_t * 100) / 100;
$admin_d = intval($admin_d * 100) / 100;
$director = intval($director * 100) / 100;
$supervisor = intval($supervisor * 100) / 100;
$manager = intval($manager * 100) / 100;
$admin_on_rate_dif = intval($admin_on_rate_dif * 100) / 100;

$sum_system_profit = $fond + $admin_r + $admin_z + $admin_t + $admin_d + $director + $supervisor + $manager + $admin_on_rate_dif;

//modifikation
$marketer = 0;
$our_shop = 0;

$sql = "SELECT `id` FROM `users` WHERE `id`='{$orders_post_user_id}' AND `gtm` IN ('google')";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_gtm = mysqli_num_rows($query);

if ($count_users_gtm > 0) {

	$marketer = $admin_on_rate_dif * 0.1;
	$admin_on_rate_dif = $admin_on_rate_dif * 0.9;

	$admin_on_rate_dif = intval($admin_on_rate_dif * 100) / 100;
	$marketer = intval($marketer * 100) / 100;

	$sum_system_profit = $fond + $admin_r + $admin_z + $admin_t + $admin_d + $director + $supervisor + $manager + $marketer + $admin_on_rate_dif;

} else {

	if ($orders_post_user_id == 7037) {

		$total_amount_for_marketer = $admin_on_rate_dif + $manager + $supervisor + $director;

		$admin_on_rate_dif = 0;
		$manager = 0;
		$supervisor = 0;
		$director = 0;

		$user_agent_sum = 0;
		foreach ($agent_data as $agent_user_data) {
			$agent_user_margin = $agent_user_data[1];
			$user_agent_sum += $agent_user_margin;
		}

		//$our_shop = $total_amount_for_marketer * 0.85;
		//$marketer = $total_amount_for_marketer * 0.15;
		$our_shop = $total_amount_for_marketer + $fond + $admin_r + $admin_z + $admin_t + $admin_d + $user_agent_sum;

		$our_shop = intval($our_shop * 100) / 100;
		//$marketer = intval($marketer * 100) / 100;

		//$sum_system_profit = $fond + $admin_r + $admin_z + $admin_t + $admin_d + $marketer + $our_shop;
		$sum_system_profit = $our_shop;

	}

}
//modifikation

$admin_on_amount_left = $orders_post['amount'] - $orders_post['income'] - $sum_system_profit - $goods_price_purchase_sum;
$admin_on_amount_left = intval($admin_on_amount_left * 100) / 100;

//echo 'Сумма рассприделения: '.($orders_post['amount'] - $goods_price_purchase_sum).'<br>';
//echo 'Рассприделилось: '.($orders_post['income'] + $sum_system_profit + $admin_on_amount_left).'<br>';

?>