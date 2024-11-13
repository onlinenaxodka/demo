<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<div class="row justify-content-center">
	<div class="col-sm-8 col-md-6 col-xl-4">
		<form method="GET">
			<div class="row">
				<div class="col-sm-8">
					<div class="form-group">
						<select name="provider_id" class="form-control">
							<option value="none" selected disabled>Выберите поставщика</option>
<?

						$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `admin` IN (1,2) ORDER BY `name` ASC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						while ($provider = mysqli_fetch_assoc($query)) {
							
							if ($provider['id'] == (int)$_GET['provider_id'])
								echo '<option value="'.$provider['id'].'" selected>['.$provider['id'].'] '.$provider['name'].' '.$provider['surname'].'</option>';
							else
								echo '<option value="'.$provider['id'].'">['.$provider['id'].'] '.$provider['name'].' '.$provider['surname'].'</option>';

						}

?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-block">Выбрать</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<?if (isset($_GET['provider_id']) and (int)$_GET['provider_id'] > 0):?>
	
<?

$provider_id = (isset($_GET['provider_id'])) ? mysqli_real_escape_string($db, $_GET['provider_id']) : '';
$provider_id = test_request($provider_id);
$provider_id = intval($provider_id);

$sql = "SELECT * FROM `orders` WHERE `goods` LIKE '%\"user_id\":\"{$provider_id}\"%' AND `status` = 7 AND `status_provider` = 0 AND `created` > '2022-04-01 00:00:01' ORDER BY `id` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {

?>

<div class="table-responsive">
	<table class="table table-sm table-hover mt-3" style="font-size: 14px;">
		<thead class="thead-light">
			<tr>
				<th>Создан</th>
				<th>№ заказа</th>
				<th>ТТН</th>
				<th style="min-width: 400px;">Название товара</th>
				<th>Количество</th>
				<th>Цена за 1 ед.</th>
				<th>Сумма</th>
			</tr>
		</thead>
		<tbody>

<?

	$goods_price_amount_sum = 0;
	$orders_paid = [];

	while ($order = mysqli_fetch_assoc($query)) {

		$orders_paid[] = (int)$order['id'];

		$order['goods'] = json_decode($order['goods'], true);

		$order['goods'] = array_values(array_filter(array_map(function ($item) use ($provider_id) {
			return $item['user_id'] == $provider_id ? $item : null;
		}, $order['goods'])));

		for ($i=0; $i < count($order['goods']); $i++) {

			$goods_id = $order['goods'][$i]['id'];
			$goods_name = $order['goods'][$i]['name'];
			$goods_availability = (int)$order['goods'][$i]['availability'];
			$goods_price = (int)$order['goods'][$i]['goods_price_agent'] > 0 ? (int)$order['goods'][$i]['goods_price_agent'] : (int)$order['goods'][$i]['goods_price_purchase'];
			$goods_price_amount = $goods_availability * $goods_price;

			$goods_price_amount_sum += $goods_price_amount;

?>
			
			<tr>
				<td><?=($i == 0 ? date('d.m.Y H:i:s', strtotime($order['created'])) : '')?></td>
				<td>
					<?if ($i == 0):?>
					<a href="/admin/orders/?number=<?=$order['id']?>" target="_blank"><?=$order['id']?></a>
					<?endif;?>
				</td>
				<td>
					<?if ($i == 0):?>
					<a href="https://novaposhta.ua/tracking/?cargo_number=<?=$order['invoice_number']?>" target="_blank"><?=$order['invoice_number']?></a>
					<?endif;?>
				</td>
				<td><a href="/admin/goods/?goods_code=<?=$goods_id?>" target="_blank"><?=$goods_name?></a></td>
				<td><?=$goods_availability?> шт.</td>
				<td><?=$goods_price?> грн.</td>
				<th><?=$goods_price_amount?> грн.</th>
			</tr>

<?

		}

?>
	
			<tr>
				<td colspan="7"></td>
			</tr>
			<tr>
				<td colspan="7"></td>
			</tr>
			<tr>
				<td colspan="7"></td>
			</tr>

<?

	}

?>

			<tr>
				<td colspan="5"></td>
				<td>Итого:</td>
				<th>
					<?=$goods_price_amount_sum?> грн.
					<?if ($user_id == 2):?>
					<form method="POST">
						<input type="hidden" name="act" value="orders_paid">
						<input type="hidden" name="orderids" value="<?=implode(',', $orders_paid)?>">
						<button type="submit" class="btn btn-success btn-sm">Оплачено</button>
					</form>
					<?endif;?>
				</th>
			</tr>
		</tbody>
	</table>
</div>

<?

} else {

?>

<p class="text-center mt-5">Заказов с этим поставщиком нет</p>

<?

}

?>

<?else:?>

<p class="text-center mt-5">Поставщик не выбран</p>

<?endif;?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>