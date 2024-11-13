<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$orders_status_cs_arr = array(
	array(0, 'Нет данных'),
	array(1, 'Нова пошта очікує надходження від відправника'),
	array(2, 'Видалено'),
	array(3, 'Номер не знайдено'),
	array(4, 'Відправлення у місті.'),
	array(41, 'Відправлення у місті.'),
	array(5, 'Відправлення прямує до міста.'),
	array(6, 'Відправлення у місті. Очікуйте додаткове повідомлення про прибуття.'),
	array(7, 'Прибув на відділення'),
	array(8, 'Прибув на відділення'),
	array(9, 'Відправлення отримано без наложки'),
	array(10, 'Відправлення отримано. Можна отримати грошовий переказ.'),
	array(11, 'Відправлення отримано. Грошовий переказ видано одержувачу.'),
	array(14, 'Відправлення передано до огляду отримувачу'),
	array(101, 'На шляху до одержувача'),
	array(102, 'Відмова одержувача 1'),
	array(103, 'Відмова одержувача 2'),
	array(108, 'Відмова одержувача 3'),
	array(104, 'Змінено адресу'),
	array(105, 'Припинено зберігання'),
	array(106, 'Одержано і створено ЄН зворотньої доставки')
);

$orders_status_arr = array(
	array(0, 'Новый'),
	array(1, 'Подтвержден'),
	array(2, 'Ожидаем оплату'),
	array(3, 'В обработке'),
	array(4, 'Отправлен'),
	array(5, 'Прибыл'),
	array(6, 'Оплачен'),
	array(7, 'Завершен'),
	array(8, 'Отменен'),
	array(9, 'Отказ'),
	array(10, 'Не дозвон'),
	array(11, 'Дубль')
);

if (!isset($_GET['number']) or empty($_GET['number'])) {

if (!empty($_GET['order_search_type']) and !empty($_GET['order_search'])) {

	$order_search_type = (isset($_GET['order_search_type'])) ? mysqli_real_escape_string($db, $_GET['order_search_type']) : '';
	$order_search_type = test_request($order_search_type);
	$order_search_type = intval($order_search_type);

	$order_search = (isset($_GET['order_search'])) ? mysqli_real_escape_string($db, $_GET['order_search']) : '';
	$order_search = test_request($order_search);

	switch ($order_search_type) {
		case 1:
			$order_search = intval($order_search);
			$sql_part_order_search = " WHERE `id`='{$order_search}'";
			break;
		case 2:
			$sql_part_order_search = " WHERE `invoice_number`='{$order_search}'";
			break;
		case 3:
			$sql_part_order_search = " WHERE `client` LIKE '%{$order_search}%'";
			break;
		default:
			$sql_part_order_search = "";
			break;
	}

	$sql_part_order = $sql_part_order_search;

}
	
	$filter_value_view = array();
	$filter_value_view['date_from']='2020-12-01';
	$filter_value_view['status_cs']=1000;
	$filter_value_view['status']=7;
	$filter_value_view['status_provider']=1000;

	if (!empty($_SESSION['order_filter'])) {

		$sql_part_order_filter_arr = array();

		foreach ($_SESSION['order_filter'] as $key_order_filter => $value_order_filter) {

			$filter_value_view[$key_order_filter] = $value_order_filter;

			switch ($key_order_filter) {
				case 'date_from':
					$sql_part_order_filter_arr[] = "DATE(`created`)>='{$value_order_filter}'";
					break;
				case 'date_to':
					$sql_part_order_filter_arr[] = "DATE(`created`)<='{$value_order_filter}'";
					break;
				case 'user':
					$sql_part_order_filter_arr[] = "`user_id`='{$value_order_filter}'";
					break;
				case 'gtm':
					$sql_part_order_filter_arr[] = "`user_id` IN (SELECT `id` FROM `users` WHERE `gtm`='{$value_order_filter}')";
					break;
				case 'name_goods':
					$sql_part_order_filter_arr[] = "`goods` LIKE '%{$value_order_filter}%'";
					break;
				case 'payment':
					$sql_part_order_filter_arr[] = "`payment`='{$value_order_filter}'";
					break;
				case 'status_cs':
					$sql_part_order_filter_arr[] = "`status_cs`='{$value_order_filter}'";
					break;
				case 'status':
					$sql_part_order_filter_arr[] = "`status`='{$value_order_filter}'";
					break;
				case 'status_provider':
					$sql_part_order_filter_arr[] = "`status_provider`='{$value_order_filter}'";
					break;
				case 'provider':
					$sql_part_order_filter_arr[] = "`goods` LIKE '%\"user_id\":\"{$value_order_filter}\"%'";
					break;
				default:
					$sql_part_order_filter_arr[] = "";
					break;
			}

		}

		if (!empty($sql_part_order_filter_arr)) $sql_part_order_filter = " WHERE ".implode(" AND ", $sql_part_order_filter_arr);
		else $sql_part_order_filter = "";

		$sql_part_order = $sql_part_order_filter;

	}

$sql = "SELECT COUNT(1) as count FROM `orders`".$sql_part_order;
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_orders = mysqli_fetch_assoc($query);
//$count_orders['count'] = $count_orders['count'] + 50000;
$count_orders['count'] = $count_orders['count'];

?>

<div class="row">
	<div class="col-xl-4 mb-xl-0 mb-3">
		<form method="GET">
			<div class="row">
				<div class="col-sm-5">
					<select name="order_search_type" class="form-control">
						<option value="1">Номер заказа</option>
						<option value="2">Номер накладной</option>
						<option value="3">Клиент (фио, телефон)</option>
					</select>
				</div>
				<div class="col-sm-5">
					<input type="search" name="order_search" class="form-control" placeholder="Введите значение...">
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-success">Поиск</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-xl-4 mb-xl-0 mb-3 text-xl-left text-center">
		<button type="button" class="btn btn-primary" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter">Фильтр &#8595;</button>
		<?if (!empty($_SESSION['order_filter'])):?>
		<form method="POST" class="d-inline-block ml-3">
			<div class="form-group text-center">
				<input type="hidden" name="clear_order_filter" value="1">
				<button type="submit" class="btn btn-warning">Очистить фильтр</button>
			</div>
		</form>
		<?endif;?>
		<div class="collapse mt-3 text-left" id="collapseFilter">
			<form method="POST">
				<div class="form-group">
					<label for="order_filter_date_from" class="font-weight-bold">Дата заказа (дд.мм.рррр)</label>
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">С</span>
								</div>
								<input type="date" name="order_filter_date_from" id="order_filter_date_from" class="form-control" placeholder="Выберите дату" value="<?=$filter_value_view['date_from']?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">До</span>
								</div>
								<input type="date" name="order_filter_date_to" class="form-control" placeholder="Выберите дату" value="<?=$filter_value_view['date_to']?>">
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="order_filter_user" class="font-weight-bold">Пользователь</label>
					<select name="order_filter_user" id="order_filter_user" class="form-control">
						<option value="none" selected>Все пользователи</option>
<?

						$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id` IN (SELECT `user_id` FROM `orders` GROUP BY `user_id`) ORDER BY FIELD(`id`,1,7037) DESC, `name` ASC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						while ($filter_orders = mysqli_fetch_assoc($query)) {
							
							/*$sql_filter_users = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$filter_orders['user_id']}'";
							$query_filter_users = mysqli_query($db, $sql_filter_users) or die(mysqli_error($db));
							$filter_users = mysqli_fetch_assoc($query_filter_users);*/
							if ($filter_orders['id'] == $filter_value_view['user'])
								echo '<option value="'.$filter_orders['id'].'" selected>['.$filter_orders['id'].'] '.$filter_orders['name'].' '.$filter_orders['surname'].'</option>';
							else
								echo '<option value="'.$filter_orders['id'].'">['.$filter_orders['id'].'] '.$filter_orders['name'].' '.$filter_orders['surname'].'</option>';

						}

?>
					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_gtm" class="font-weight-bold">Пользователи с рекламы</label>
					<select name="order_filter_gtm" id="order_filter_gtm" class="form-control">
						<option value="none" selected>Выберите рекламу</option>

<?

						$gtm_option = array(
							'google' => 'Google дропшиппинг'
						);

						foreach ($gtm_option as $gtm_option_key => $gtm_option_value) {
							
							if ($gtm_option_key == $filter_value_view['gtm'])
								echo '<option value="'.$gtm_option_key.'" selected>'.$gtm_option_value.'</option>';
							else
								echo '<option value="'.$gtm_option_key.'">'.$gtm_option_value.'</option>';

						}

?>

					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_name_goods" class="font-weight-bold">Название товара</label>
					<input type="search" name="order_filter_name_goods" id="order_filter_name_goods" class="form-control" placeholder="Введите название товара..." value="<?=$filter_value_view['name_goods']?>">
				</div>
				<div class="form-group">
					<label for="order_filter_payment" class="font-weight-bold">Способ оплаты</label>
					<select name="order_filter_payment" id="order_filter_payment" class="form-control">
						<option value="none" selected>Все способы оплаты</option>

<?

						$payment_option = array(
							'1' => 'Наложенный платеж',
							'2' => 'Перевод на карту 100%',
							'3' => 'Внутренний баланс 100%'
						);

						foreach ($payment_option as $payment_option_key => $payment_option_value) {
							
							if ($payment_option_key == $filter_value_view['payment'])
								echo '<option value="'.$payment_option_key.'" selected>'.$payment_option_value.'</option>';
							else
								echo '<option value="'.$payment_option_key.'">'.$payment_option_value.'</option>';

						}

?>

					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_status_cs" class="font-weight-bold">Статус посылки у курьера</label>
					<select name="order_filter_status_cs" id="order_filter_status_cs" class="form-control">
						<option value="none" selected>Все статусы посылки</option>

<?

						foreach ($orders_status_cs_arr as $orders_status_cs_filter) {
							
							if ($orders_status_cs_filter[0] == $filter_value_view['status_cs'])
								echo '<option value="'.$orders_status_cs_filter[0].'" selected>'.$orders_status_cs_filter[1].'</option>';
							else
								echo '<option value="'.$orders_status_cs_filter[0].'">'.$orders_status_cs_filter[1].'</option>';

						}

?>

					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_status" class="font-weight-bold">Статус заказа</label>
					<select name="order_filter_status" id="order_filter_status" class="form-control">
						<option value="none" selected>Все статусы заказа</option>

<?

						foreach ($orders_status_arr as $orders_status_filter) {
							
							if ($orders_status_filter[0] == $filter_value_view['status'])
								echo '<option value="'.$orders_status_filter[0].'" selected>'.$orders_status_filter[1].'</option>';
							else
								echo '<option value="'.$orders_status_filter[0].'">'.$orders_status_filter[1].'</option>';

						}

?>

					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_status_provider" class="font-weight-bold">Статус расчета с поставщиком</label>
					<select name="order_filter_status_provider" id="order_filter_status_provider" class="form-control">
						<option value="none" selected>Все статусы расчета</option>

<?

						$status_provider_option = array(
							'0' => 'Не задано',
							'1' => 'От поставщика получили деньги',
							'2' => 'Поставщику отдали деньги'
						);

						foreach ($status_provider_option as $status_provider_option_key => $status_provider_option_value) {
							
							if ($status_provider_option_key == $filter_value_view['status_provider'])
								echo '<option value="'.$status_provider_option_key.'" selected>'.$status_provider_option_value.'</option>';
							else
								echo '<option value="'.$status_provider_option_key.'">'.$status_provider_option_value.'</option>';

						}

?>

					</select>
				</div>
				<div class="form-group">
					<label for="order_filter_provider" class="font-weight-bold">Поставщики</label>
					<select name="order_filter_provider" id="order_filter_provider" class="form-control">
						<option value="none" selected>Все поставщики</option>
<?

						$sql = "SELECT `id`, `name`, `surname` FROM `users` WHERE `admin`=2 ORDER BY `name` ASC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						while ($filter_orders = mysqli_fetch_assoc($query)) {
							
							if ($filter_orders['id'] == $filter_value_view['provider'])
								echo '<option value="'.$filter_orders['id'].'" selected>['.$filter_orders['id'].'] '.$filter_orders['name'].' '.$filter_orders['surname'].'</option>';
							else
								echo '<option value="'.$filter_orders['id'].'">['.$filter_orders['id'].'] '.$filter_orders['name'].' '.$filter_orders['surname'].'</option>';

						}

?>
					</select>
				</div>
				<div class="form-group text-right">
					<button type="submit" class="btn btn-success">Фильтровать</button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-xl-4">
		<div class="row">
			<?if($user['admin'] == 1 or ($user['admin'] == 2 and $user_id == 5672)):?>
			<div class="col-sm-6 text-xl-center text-left">
				<?if (($post_user['id'] != 1 and $post_user['id'] != 2) or $user_id == 2):?>
				<form action="/admin/users/" method="POST" class="mb-2">
					<input type="hidden" name="login_in_acc" value="7037">
					<button type="submit" class="btn btn-success">Интернет магазин ONLINE NAXODKA</button>
				</form>
				<?endif;?>
				<!-- <button class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#googleLinks">Список поставщиков</button> -->
			</div>
			<div class="col-sm-6">
				<p class="text-right mt-2 mb-0">Найдено заказов: <b><?=$count_orders['count']?></b></p>

<?

				$sql = "SELECT `id` FROM `orders` WHERE `status`=3 AND `status_cs` IN (9,10,11)";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$orders_for_complete = mysqli_num_rows($query);
				//$orders_for_complete = $orders_for_complete + 103;
				$orders_for_complete = $orders_for_complete;

				$sql = "SELECT `id` FROM `orders` WHERE `status`=3 AND `status_cs` IN (102,103,108)";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$orders_for_refusal = mysqli_num_rows($query);
				//$orders_for_refusal = $orders_for_refusal + 7;
				$orders_for_refusal = $orders_for_refusal;

				$income_total = 0;
				$sql = "SELECT * FROM `orders`".$sql_part_order;
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				while ($orders = mysqli_fetch_assoc($query)) {
					$orders['goods'] = json_decode($orders['goods'], true) ?: [];
					$income_subtotal = 0;

					for ($i=0; $i < count($orders['goods']); $i++) {
						$goods_price_agent_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_agent'];
						$goods_price_purchase_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase'];
						$goods_goods_price_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];

						if ($goods_price_agent_count > 0) $income_subtotal += $goods_goods_price_count - $goods_price_agent_count;
						else $income_subtotal += $goods_goods_price_count - $goods_price_purchase_count;
					}
					$income_total += $income_subtotal;
				}

?>

				<p class="text-right mb-0">Нужно завершить: <b class="text-success"><?=$orders_for_complete?></b></p>
				<p class="text-right mb-0">Нужно отозвать: <b class="text-danger"><?=$orders_for_refusal?></b></p>
				<p class="text-right mb-0">Грязний дохід: <b><?=$income_total?> грн.</b></p>
			</div>
			<?else:?>
			<div class="col-sm-12">
				<p class="text-right mt-2 mb-0">Найдено заказов: <b><?=$count_orders['count']?></b></p>
			</div>
			<?endif;?>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-sm table-striped table-hover mt-3" style="font-size: 14px;">
		<thead class="thead-light">
			<tr>
				<th>№</th>
				<th>Пользователь</th>
				<th style="min-width: 400px;">Товары</th>
				<th>Клиент</th>
				<th>Комментарии</th>
				<th>Информация</th>
				<th>№ накладной</th>
				<th title="Грязний дохід">Дохід</th>
				<th>Статус</th>
			</tr>
		</thead>
		<tbody>

			<?

			$num = 30;
			
			$sql = "SELECT COUNT(1) as count FROM `orders`".$sql_part_order;
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$posts = mysqli_fetch_assoc($query);
			
			$total = intval(($posts['count'] - 1) / $num) + 1;
			$page = intval($_GET['page']);

			if(empty($page) or $page < 0) $page = 1;  
			if($page > $total) $page = $total;  

			$start = $page * $num - $num;

			$sql = "SELECT * FROM `orders`".$sql_part_order." ORDER BY FIELD(`status`,10,0,6,2,1,3,4,5,7,8,9) ASC, `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			if (mysqli_num_rows($query) > 0) {

				while ($orders = mysqli_fetch_assoc($query)) {

					$orders_user_id = $orders['user_id'];

					$sql_users = "SELECT `partner_id`, `name`, `surname`, `phone` FROM `users` WHERE `id`='{$orders_user_id}'";
					$query_users = mysqli_query($db, $sql_users) or die(mysqli_error($db));
					$users = mysqli_fetch_assoc($query_users);

					$users_partner_id = $users['partner_id'];

					$sql_mentor = "SELECT `name`, `surname`, `phone` FROM `users` WHERE `id`='{$users_partner_id}'";
					$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
					$user_mentor = mysqli_fetch_assoc($query_mentor);

					$user_mentor_data = '[' . $users_partner_id . ']<br>' . $user_mentor['name'] . ' ' . $user_mentor['surname'] . '<br>'.$user_mentor['phone'];

					$orders_id = $orders['id'];

					$orders['client'] = json_decode($orders['client'], true);
					$orders['client'] = $orders['client']['fio'] . ',<br> ' . $orders['client']['phone'];

					$orders['delivery_address'] = json_decode($orders['delivery_address'], true) ?: [];

					switch ($orders['delivery']) {
						case 1:
							$orders['delivery_address'] = 'Нова пошта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 2:
							$orders['delivery_address'] = 'Укрпошта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 3:
							$orders['delivery_address'] = 'Самовывоз: ' . implode(', ', $orders['delivery_address']);
							break;
						default:
							$orders['delivery_address'] = '-';
							break;
					}

					switch ($orders['payment']) {
						case 1:
							$orders_payment_status = 1;
							$orders['payment'] = '<b class="text-info">Наложенный платеж</b>';
							break;
						case 2:
							$orders_payment_status = 2;
							$orders['payment'] = '<b class="text-danger">Перевод на карту 100%</b>';
							break;
						case 3:
							$orders_payment_status = 3;
							$orders['payment'] = '<b class="text-success">Внутренний баланс 100%</b>';
							break;
						default:
							$orders_payment_status = 0;
							$orders['payment'] = '-';
							break;
					}

					$orders['goods'] = json_decode($orders['goods'], true) ?: [];

					switch ($orders['status']) {
						case 0:
							$orders_status = '<b class="text-primary">Новый</b>';
							break;
						case 1:
							$orders_status = '<b class="text-info">Подтвержден</b>';
							break;
						case 2:
							$orders_status = '<b class="text-dark">Ожидаем оплату</b>';
							break;
						case 3:
							$orders_status = '<b class="text-warning">В обработке</b>';
							break;
						case 4:
							$orders_status = '<b class="text-success">Отправлен</b>';
							break;
						case 5:
							$orders_status = '<b class="text-success">Прибыл</b>';
							break;
						case 6:
							$orders_status = '<b class="text-success">Оплачен</b>';
							break;
						case 7:
							$orders_status = '<b class="text-success">Завершен</b>';
							break;
						case 8:
							$orders_status = '<b class="text-danger">Отменен</b>';
							break;
						case 9:
							$orders_status = '<b class="text-danger">Отказ</b>';
							break;
						case 10:
							$orders_status = '<b class="text-primary">Не дозвон</b>';
							break;
						case 11:
							$orders_status = '<b class="text-danger">Дубль</b>';
							break;
						default:
							$orders_status = '-';
							break;
					}

					$orders['created'] = date('d.m.Y H:i', strtotime($orders['created']));

				?>
				<style type="text/css">
					.table-sm td, .table-sm th {
						padding: 10px 15px;
					}
				</style>
				<tr id="<?=$orders['id']?>">
					<td style="min-width: 70px;width: 70px;">
						<p>
							<a href="?number=<?=$orders['id']?>" class="btn btn-info btn-sm btn-block"><?=$orders['id']?></a>
						</p>
						<?if ($user_id != 4):?>
						<p class="font-italic mb-0"><?=$orders['created']?></p>
						<?endif;?>
					</td>
					<td style="min-width: 200px;width: 200px;">
						<form method="POST" action="/admin/users/">
							<input type="hidden" name="search" value="<?=$orders_user_id?>">
							<!-- <button type="submit" class="btn btn-dark btn-sm" data-toggle="tooltip" data-html="true" data-placement="top" title="Наставник:<br><?=$user_mentor_data?>">[<?=$orders_user_id?>] <?=$users['name']?> <?=$users['surname']?></button> -->
							<button type="submit" class="btn btn-dark btn-sm" data-toggle="tooltip" data-html="true" data-placement="top" title="Наставник:<br><?=$user_mentor_data?>"><?=$users['name']?> <?=$users['surname']?></button>
						</form>
						<button class="btn btn-link btn-clipboard p-0 border-0 mt-2" data-clipboard-text="<?=$users['phone']?>" onclick="copyLink(this)"><?=substr($users['phone'], 0, -6)?>******</button>
					</td>
					<td class="goods-catalog" style="min-width: 400px;width: 400px;">
						<div class="list-group">

<?
						
						$income_subtotal = 0;

						for ($i=0; $i < count($orders['goods']); $i++) {

							$orders_goods_id = $orders['goods'][$i]['id'];

							$sql_goods = "SELECT * FROM `goods` WHERE `id`='{$orders_goods_id}'";
                            $query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
                            $goods_count = mysqli_num_rows($query_goods);

                            $goods_price_agent_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_agent'];
							$goods_price_purchase_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase'];
							$goods_goods_price_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];

							if ($goods_price_agent_count > 0) $income_subtotal += $goods_goods_price_count - $goods_price_agent_count;
							else $income_subtotal += $goods_goods_price_count - $goods_price_purchase_count;

                            if ($goods_count > 0) {

                            	$goods = mysqli_fetch_assoc($query_goods);
                            
                            	$goods['photo'] = json_decode($goods['photo'], true);

                            	if (!file_exists('../data/images/goods/'.$goods['photo']['img0'])) {
                                    $goods['photo']['img0'] = 'no_image.png';
                                }

                            	list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img0']);

                            } else {

                            	list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/no_image.png');
                            	$goods['photo']['img0'] = 'no_image.png';

                            }

							if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
							else $goods_photo_size = 'max-height';

?>

							
								<?if($i == 1):?>
								<a data-toggle="collapse" href="#collapseGoods<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseGoods<?=$orders_id?>">В заказе еще <?=(count($orders['goods'])-1)?> товаров</a>
								<div class="collapse" id="collapseGoods<?=$orders_id?>">
								<?endif;?>

								<a href="/admin/goods/?goods_code=<?=$goods['id']?>" target="_blank" class="list-group-item list-group-item-action p-1 pr-5" style="min-height: 60px;position: relative;">
									<div class="goods-list-img float-left <?=$goods_photo_size?>">
										<img src="/data/images/goods/<?=$goods['photo']['img0']?>">
									</div>
									<p class="mt-1 mb-0" style="margin-left: 60px;"><?=$orders['goods'][$i]['name']?></p>
									<div style="position: absolute;right: 8px;top: 8px;">
										<b><?=$orders['goods'][$i]['availability']?> шт.</b>
									</div>
								</a>

								<?if($i == count($orders['goods'])-1):?>
								</div>
								<?endif;?>
							

<?

						}

?>
						</div>
					</td>
					<td>
						<p><?=substr($orders['client'], 0, -4)?>****</p>
						<p class="mb-0"><?=$orders['delivery_address']?></p>
					</td>
					<td>

<?

						$sql_messages = "SELECT `id` FROM `orders_messages` WHERE `order_id`='{$orders_id}'";
						$query_messages = mysqli_query($db, $sql_messages) or die(mysqli_error($db));
						$count_messages_all_in_order = mysqli_num_rows($query_messages);

						$sql_messages = "SELECT `id` FROM `orders_messages` WHERE `order_id`='{$orders_id}' AND `type_user`=1 AND `status`=0";
						$query_messages = mysqli_query($db, $sql_messages) or die(mysqli_error($db));
						$count_messages_new_in_order = mysqli_num_rows($query_messages);

?>						
						<div class="card">
							<div class="card-body p-2">
								<b><?=$count_messages_all_in_order?></b>
								<span class="text-primary">(<b class="text-danger"><?=$count_messages_new_in_order?></b> новых)</span>
							</div>
						</div>
					</td>
					<td style="min-width: 200px;width: 200px;">
						<p><?=$orders['payment']?></p>
						<p class="mb-0">
<?

							if ($orders['prepayment'] > 0) $orders['amount'] = $orders['amount'] - $orders['prepayment'];

?>
							Общая сумма: <b><?=$orders['amount']?> грн.</b>
						</p>
					</td>
					<td style="min-width: 240px;width: 260px;">
						<form method="POST">
							<input type="hidden" name="order" value="<?=$orders_id?>">
							<div class="input-group mb-2">
								<input type="number" name="invoice_number" class="form-control" placeholder="Введите ТТН" value="<?=$orders['invoice_number']?>">
								<div class="input-group-append">
									<span class="input-group-text">
										<a href="https://novaposhta.ua/tracking/?cargo_number=<?=$orders['invoice_number']?>" target="_blank">
											<img src="/assets/images/novaposhta_icon.png" alt="NP" width="24">
										</a>
									</span>
								</div>
							</div>
						</form>

<?

						for ($i=0; $i < count($orders_status_cs_arr); $i++) { 
							
							if ($orders['status_cs'] == $orders_status_cs_arr[$i][0]) $orders_status_cs = $orders_status_cs_arr[$i][1];

						}

?>

						<span class="font-weight-bold"><?=$orders_status_cs?></span>
					</td>
					<td><?=$income_subtotal?> грн.</td>
					<td style="min-width: 140px;width: 140px;">
						<span data-toggle="collapse" href="#collapseStatus<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseStatus<?=$orders_id?>" class="d-block" style="cursor:pointer;"><?=$orders_status?></span>
						<br>
<?

						 switch ($orders['status']) {
						 	case 0:
						 		$selected_status[0] = 'selected';
						 		break;
						 	case 1:
						 		$selected_status[1] = 'selected';
						 		break;
						 	case 2:
						 		$selected_status[2] = 'selected';
						 		break;
						 	case 3:
						 		$selected_status[3] = 'selected';
						 		break;
						 	case 4:
						 		$selected_status[4] = 'selected';
						 		break;
						 	case 5:
						 		$selected_status[5] = 'selected';
						 		break;
						 	case 6:
						 		$selected_status[6] = 'selected';
						 		break;
						 	case 7:
						 		$selected_status[7] = 'selected';
						 		break;
						 	case 8:
						 		$selected_status[8] = 'selected';
						 		break;
						 	case 9:
						 		$selected_status[9] = 'selected';
						 		break;
						 	case 10:
						 		$selected_status[10] = 'selected';
						 		break;
						 	default:
						 		$selected_status[0] = 'selected';
						 		break;
						 }

?>
						<?if ($orders['status'] != 4 and $orders['status'] != 7 and $orders['status'] != 8 and $orders['status'] != 9):?>
						
						<div class="collapse text-center" id="collapseStatus<?=$orders_id?>">
							<form method="POST">
								<input type="hidden" name="order" value="<?=$orders_id?>">
								<select class="form-control mb-1" name="status">
									<option value="0" <?=$selected_status[0]?>>Новый</option>
									<option value="1" <?=$selected_status[1]?>>Подтвержден</option>
									<option value="2" <?=$selected_status[2]?>>Ожидаем оплату</option>
									<option value="3" <?=$selected_status[3]?>>В обработке</option>
									<?if ($user_id == 2):?>
									<option value="4" <?=$selected_status[4]?>>Отправлен</option>
									<?endif;?>
									<option value="5" <?=$selected_status[5]?>>Прибыл</option>
									<option value="6" <?=$selected_status[6]?>>Оплачен</option>
									<?if ($user_id == 2):?>
									<option value="7" <?=$selected_status[7]?>>Завершен</option>
									<?endif;?>
									<option value="8" <?=$selected_status[8]?>>Отменен</option>
									<?if ($user_id == 2):?>
									<option value="9" <?=$selected_status[9]?>>Отказ</option>
									<?endif;?>
									<option value="10" <?=$selected_status[10]?>>Не дозвон</option>
									<option value="11" <?=$selected_status[11]?>>Дубль</option>
								</select>
								<?if ($user_id == 2):?>
								<input type="number" step="0.01" name="failure_commission" class="form-control" placeholder="Комиссия отказа">
								<?endif;?>
								<button type="submit" class="btn btn-success btn-sm mt-1">Изменить</button>
							</form>
						</div>
						<?endif;?>
					</td>
				</tr>

				<?

				}

			} else {

				echo '<tr><td colspan="9" class="text-center">Список заказов пуст</td></tr>';

			}

			?>

		</tbody>
	</table>
</div>

<nav>
	<ul class="pagination justify-content-center">

<?

// Проверяем нужны ли стрелки назад  
if ($page != 1) $pervpage = '<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only"><<</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page='. ($page - 1) .'" aria-label="Previous">
									<span aria-hidden="true">&#8249;</span>
									<span class="sr-only"><</span>
								</a>
							</li>';
else $pervpage = '<li class="page-item disabled">
					<span class="page-link">&laquo;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&#8249;</span>
				</li>';

// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = '<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page='. ($page + 1) .'" aria-label="Next">
										<span aria-hidden="true">&#8250;</span>
										<span class="sr-only">></span>
									</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page=' .$total. '" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">>></span>
									</a>
								</li>';
else $nextpage = '<li class="page-item disabled">
					<span class="page-link">&#8250;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&raquo;</span>
				</li>';

// Находим две ближайшие станицы с обоих краев, если они есть  
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 2) .'>'. ($page - 2) .'</a></li>';  
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 1) .'>'. ($page - 1) .'</a></li>';  
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 2) .'>'. ($page + 2) .'</a></li>';  
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 1) .'>'. ($page + 1) .'</a></li>'; 

//Текущая страница
$currentpage = '<li class="page-item active"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<?

} else {

	$order_id = (isset($_GET['number'])) ? mysqli_real_escape_string($db, $_GET['number']) : '';
	$order_id = test_request($order_id);
	$order_id = intval($order_id);

	$sql = "SELECT * FROM `orders` WHERE `id`='{$order_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));

	if (mysqli_num_rows($query) > 0) {

		$orders = mysqli_fetch_assoc($query);

		$orders_user_id = $orders['user_id'];

					$sql_users = "SELECT `partner_id`, `name`, `surname`, `phone` FROM `users` WHERE `id`='{$orders_user_id}'";
					$query_users = mysqli_query($db, $sql_users) or die(mysqli_error($db));
					$users = mysqli_fetch_assoc($query_users);

					$users_data = '[' . $orders_user_id . '] ' . $users['name'] . ' ' . $users['surname'] . ' '.$users['phone'];

					$users_partner_id = $users['partner_id'];

					$sql_mentor = "SELECT `name`, `surname`, `phone` FROM `users` WHERE `id`='{$users_partner_id}'";
					$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
					$user_mentor = mysqli_fetch_assoc($query_mentor);

					$user_mentor_data = '[' . $users_partner_id . ']<br>' . $user_mentor['name'] . ' ' . $user_mentor['surname'] . '<br>'.$user_mentor['phone'];

					$orders_id = $orders['id'];

					$orders['client'] = json_decode($orders['client'], true);
					$orders['client'] = $orders['client']['fio'] . ', ' . $orders['client']['phone'];

					$orders['delivery_address'] = json_decode($orders['delivery_address'], true) ?: [];

					switch ($orders['delivery']) {
						case 1:
							$orders['delivery_address'] = 'Нова пошта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 2:
							$orders['delivery_address'] = 'Укрпошта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 3:
							$orders['delivery_address'] = 'Самовывоз: ' . implode(', ', $orders['delivery_address']);
							break;
						default:
							$orders['delivery_address'] = '-';
							break;
					}

					switch ($orders['payment']) {
						case 1:
							$orders_payment_status = 1;
							$orders['payment'] = '<b class="text-info">Наложенный платеж</b>';
							break;
						case 2:
							$orders_payment_status = 2;
							$orders['payment'] = '<b class="text-danger">Перевод на карту 100%</b>';
							break;
						case 3:
							$orders_payment_status = 3;
							$orders['payment'] = '<b class="text-success">Внутренний баланс 100%</b>';
							break;
						default:
							$orders_payment_status = 0;
							$orders['payment'] = '-';
							break;
					}

					$orders['goods'] = json_decode($orders['goods'], true) ?: [];

					switch ($orders['status']) {
						case 0:
							$orders_status = '<b class="text-primary">Новый</b>';
							break;
						case 1:
							$orders_status = '<b class="text-info">Подтвержден</b>';
							break;
						case 2:
							$orders_status = '<b class="text-dark">Ожидаем оплату</b>';
							break;
						case 3:
							$orders_status = '<b class="text-warning">В обработке</b>';
							break;
						case 4:
							$orders_status = '<b class="text-success">Отправлен</b>';
							break;
						case 5:
							$orders_status = '<b class="text-success">Прибыл</b>';
							break;
						case 6:
							$orders_status = '<b class="text-success">Оплачен</b>';
							break;
						case 7:
							$orders_status = '<b class="text-success">Завершен</b>';
							break;
						case 8:
							$orders_status = '<b class="text-danger">Отменен</b>';
							break;
						case 9:
							$orders_status = '<b class="text-danger">Отказ</b>';
							break;
						case 10:
							$orders_status = '<b class="text-primary">Не дозвон</b>';
							break;
						case 11:
							$orders_status = '<b class="text-danger">Дубль</b>';
							break;
						default:
							$orders_status = '-';
							break;
					}

					$orders['updated'] = date('d.m.Y H:i', strtotime($orders['updated']));
					$orders['created'] = date('d.m.Y H:i', strtotime($orders['created']));

?>

<div class="row">
	<div class="col-sm-4 mb-3">
		<p>Номер заказа: <b><?=$orders['id']?></b></p>
		<?if ($user_id != 4):?>
		<p class="mb-0">Дата создания: <span class="font-italic"><?=$orders['created']?></span></p>
		<?endif;?>
	</div>
	<div class="col-sm-4 mb-3">
		<b>Пользователь: </b>
		<?if($user_id == 5672):?>
						<p data-toggle="tooltip" data-html="true" data-placement="bottom" title="Наставник:<br><?=$user_mentor_data?>"><?=$users_data?></p>
						<?else:?>
						<form method="POST" action="/admin/users/">
							<input type="hidden" name="search" value="<?=$orders_user_id?>">
							<button type="submit" class="btn btn-link btn-sm" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Наставник:<br><?=$user_mentor_data?>"><?=$users_data?></button>
						</form>
						<?endif;?>
	</div>
	<div class="col-sm-4 mb-3">
		<div class="row">
			<div class="col-sm-6">
				<button type="button" class="btn btn-dark btn-lg btn-block btn-clipboard" data-clipboard-action="cut" data-clipboard-target="#infoOrderForClientCopy" onclick="copyLink(this)">Копировать для клиента</button>
			</div>
			<div class="col-sm-6">
				<button type="button" class="btn btn-dark btn-lg btn-block btn-clipboard" data-clipboard-action="cut" data-clipboard-target="#infoOrderCopy" onclick="copyLink(this)">Копировать заказ</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Товары</h4>
			</div>
			<div class="card-body">
				<div>
							

<?

						$orders_margine = 0;
						$orders_amount_for_provider = 0;
						$orders_amount_for_provider_commission = 0;
						$orders_amount = 0;
						$order_info_goods = "";
						$order_info_goods_client = "";

						$admin_on_rate_dif_show = 0;
						$fond_show = 0;
						$orders_margine_show = 0;

						for ($i=0; $i < count($orders['goods']); $i++) {

							$orders_goods_id = $orders['goods'][$i]['id'];

							$sql_goods = "SELECT * FROM `goods` WHERE `id`='{$orders_goods_id}'";
                            $query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
                            $goods = mysqli_fetch_assoc($query_goods);

                            if ($goods['currency'] == 1) $goods['currency'] = 'грн';
                            elseif ($goods['currency'] == 2) $goods['currency'] = '$';
                            elseif ($goods['currency'] == 3) $goods['currency'] = '€';

                            $sql_user_provider = "SELECT * FROM `users` WHERE `id`='{$goods['user_id']}'";
							$query_user_provider = mysqli_query($db, $sql_user_provider) or die(mysqli_error($db));
							$user_provider = mysqli_fetch_assoc($query_user_provider);

                            $goods['name'] = json_decode($goods['name'], true);
                            $goods['photo'] = json_decode($goods['photo'], true);

                            $order_info_goods .= 
                            	$orders['goods'][$i]['name'] . " - " . $orders['goods'][$i]['availability'] . " шт." . 
                            	((!empty($goods['vendor_id']) and $goods['vendor_id'] != '-') ? "\nИдентификатор товара: " . $goods['vendor_id'] : "") . 
                            	((!empty($goods['vendor_code']) and $goods['vendor_code'] != '-') ? "\nАртикул: ".$goods['vendor_code'] : "") . 
                            	((!empty($goods['photo']['img0']) and $goods['photo']['img0'] != 'no_image.png') ? "\n".$server_protocole."://".$_SERVER['SERVER_NAME']."/data/images/goods/".$goods['photo']['img0']."\n\n" : "");

                            $order_info_goods_client .= 
                            	$orders['goods'][$i]['name'] . " - " . $orders['goods'][$i]['availability'] . " шт." . 
                            	((!empty($goods['vendor_id']) and $goods['vendor_id'] != '-') ? "\nИдентификатор товара: " . $goods['vendor_id'] : "") . 
                            	((!empty($goods['vendor_code']) and $goods['vendor_code'] != '-') ? "\nАртикул: ".$goods['vendor_code'] : "") . 
                            	((!empty($goods['photo']['img0']) and $goods['photo']['img0'] != 'no_image.png') ? "\nhttps://onlinenaxodka.com.ua/image/catalog/data/images/goods/".$goods['photo']['img0']."\n\n" : "");

                            $goods_price_agent_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_agent'];
                            if ($orders['goods'][$i]['currency_kurs'] > 1) {
                            	$goods_price_agent_native_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_agent_native'] * $orders['goods'][$i]['currency_kurs'];
                            	$goods_price_agent_native_count = number_format($goods_price_agent_native_count, 2, '.', '');
                            } else {
                            	$goods_price_agent_native_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_agent_native'];
                            }
                            $goods_price_purchase_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase'];
                            if ($orders['goods'][$i]['currency_kurs'] > 1) {
                            	$goods_price_purchase_native_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase_native'] * $orders['goods'][$i]['currency_kurs'];
                            	$goods_price_purchase_native_count = number_format($goods_price_purchase_native_count, 2, '.', '');
                            } else {
                            	$goods_price_purchase_native_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase_native'];
                            }
                            $goods_goods_price_recom_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_recom'];
                            $goods_goods_price_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];
                            $goods_goods_catalog_rate = $orders['goods'][$i]['catalog_rate'] * 100;

							if ($orders['goods'][$i]['goods_price'] > $orders['goods'][$i]['goods_price_recom']) 
								$orders_margine += $goods_goods_price_recom_count - $goods_price_purchase_count;
							else $orders_margine += $goods_goods_price_count - $goods_price_purchase_count;

							//marketolog
							$orders_margine_show = $goods_goods_price_recom_count - $goods_price_purchase_count;

							$sql_marketing = "SELECT * FROM `marketing` WHERE `dropshipper`='{$goods_goods_catalog_rate}'";
							$query_marketing = mysqli_query($db, $sql_marketing) or die(mysqli_error($db));
							$marketing = mysqli_fetch_assoc($query_marketing);

							if ($marketing['adminon'] > 0) {
								$admin_on_rate_dif_show += $orders_margine_show * $marketing['adminon'] * 0.01;
								//$admin_on_rate_dif_show = intval($admin_on_rate_dif_show * 100) / 100;
							}

							$fond_show += $orders_margine_show * $marketing['fond'] * 0.01;

							//marketolog

							//Amount for provider
							if ($orders_payment_status == 1) {
								/*if ($goods_price_agent_count > 0) $orders_amount_for_provider += $goods_goods_price_count - $goods_price_agent_count;
								else
									$orders_amount_for_provider += $goods_goods_price_count - $goods_price_purchase_count;*/
								if ($goods_price_agent_count > 0)
									$orders_amount_for_provider += $goods_goods_price_count - $goods_price_agent_native_count;
								else
									$orders_amount_for_provider += $goods_goods_price_count - $goods_price_purchase_native_count;
							} elseif ($orders_payment_status == 2 or $orders_payment_status == 3) {
								//if ($goods_price_agent_count > 0) $orders_amount_for_provider += $goods_price_agent_count;
								//else $orders_amount_for_provider += $goods_price_purchase_count;
								if ($goods_price_agent_count > 0)
									$orders_amount_for_provider += $goods_price_agent_native_count;
								else
									$orders_amount_for_provider += $goods_price_purchase_native_count;
							}
							
							$orders_amount += $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];

?>
						
							
							<div class="row">
								<div class="col-xl-2">
									<b>Поставщик</b>
									<br>
									<?=$user_provider['name']?> <?=$user_provider['surname']?>
								</div>
								<div class="col-xl-3">
									<b>Наименование товара</b><br>
									<?if($user_id == 5672):?>
									<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" target="_blank"><?=$goods['name']['ru']?></a>
									<?else:?>
									<a href="/admin/goods/?goods_code=<?=$goods['id']?>" target="_blank"><?=$orders['goods'][$i]['name']?></a>
									<?endif;?>
								</div>
								<div class="col-xl-1">
									<b>Количество</b>
									<?if($orders_user_id == 7037):?>
									<form method="POST">
										<input type="hidden" name="order" value="<?=$orders_id?>">
										<input type="hidden" name="goods_id" value="<?=$goods['id']?>">
										<input type="hidden" name="goods_price" value="<?=$goods_goods_price_count?>">
										<div class="input-group mb-1">
											<input type="number" name="goods_availability" step="1" min="1" class="form-control form-control-sm" value="<?=$orders['goods'][$i]['availability']?>">
											<div class="input-group-append">
												<span class="input-group-text">шт.</span>
											</div>
										</div>
										<div class="form-group mb-0">
											<button type="submit" class="btn btn-success btn-sm btn-block">Сохранить</button>
										</div>
									</form>
									<?else:?>
									<br><span><?=$orders['goods'][$i]['availability']?> шт.</span>
									<?endif;?>
								</div>
								<div class="col-xl-1">
									<b>Цена закуп.</b>
									<br>
									<i class="text-info"><?=$goods_price_agent_native_count?> грн.</i>
									<br>
									<?=$goods_price_agent_count?> грн. 
									<?if($goods['currency']!='грн'):?>
										<i>(<?=$goods['price_agent']?> <?=$goods['currency']?>)</i>
									<?endif;?>
								</div>
								<div class="col-xl-1">
									<b>Цена агента</b>
									<br>
									<i class="text-info"><?=$goods_price_purchase_native_count?> грн.</i>
									<br>
									<?=$goods_price_purchase_count?> грн. 
									<?if($goods['currency']!='грн'):?>
										<i>(<?=$goods['price_purchase']?> <?=$goods['currency']?>)</i>
									<?endif;?>
								</div>
								<div class="col-xl-1">
									<b>Цена реком.</b>
									<br>
									<?=$goods_goods_price_recom_count?> грн. 
									<?if($goods['currency']!='грн'):?>
										<i>(<?=$goods['price_sale']?> <?=$goods['currency']?>)</i>
									<?endif;?>
								</div>
								<div class="col-xl-1">
									<b>Цена дропа</b>
									<br>
									<?=$goods_goods_price_count?> грн.
								</div>
								<div class="col-xl-1">
									<b>% дропа</b>
									<br>
									<?=$goods_goods_catalog_rate?> %
								</div>
								<div class="col-xl-1">
									<div class="row">
										<div class="col-xl-6">
											<b>Курс</b><br><?=$orders['goods'][$i]['currency_kurs']?>
										</div>
										<div class="col-xl-6">
											<?if($orders_user_id == 7037):?>
											<form method="POST">
												<input type="hidden" name="act" value="goods_delete">
												<input type="hidden" name="order" value="<?=$orders_id?>">
												<input type="hidden" name="goods_id" value="<?=$goods['id']?>">
												<button type="submit" class="btn btn-danger btn-sm">
													<i class="material-icons float-left">delete_forever</i>
												</button>
											</form>
											<?endif;?>
										</div>
									</div>
								</div>
							</div>
							<hr class="mt-3 mb-3">

<?

						}

?>
							
							<?if($orders_user_id == 7037):?>
							<p>
								<button type="button" class="btn btn-light" data-toggle="modal" data-target="#addGoodsToOrderModal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить товар</button>
							</p>
							<?endif;?>

<?

						//marketolog
						$marketer_show = 0;

						$sql_users_gtm = "SELECT `id` FROM `users` WHERE `id`='{$orders_user_id}' AND `gtm` IN ('google')";
						$query_users_gtm = mysqli_query($db, $sql_users_gtm) or die(mysqli_error($db));
						$count_users_gtm = mysqli_num_rows($query_users_gtm);

						if ($count_users_gtm > 0) {

							$marketer_show = $admin_on_rate_dif_show * 0.5;
							//$admin_on_rate_dif_show = $admin_on_rate_dif_show * 0.9;
							if ($marketer_show == 0) {
								$marketer_show = $fond_show;
							}
							//$admin_on_rate_dif_show = intval($admin_on_rate_dif_show * 100) / 100;
							$marketer_show = intval($marketer_show * 100) / 100;

						}
						//marketolog

						//Amount for provider payment commission
						$orders_amount_for_provider_commission = $orders_amount_for_provider * 0.005;
						$orders_amount_for_provider_commission = number_format($orders_amount_for_provider_commission, 2, '.', '');

						if ($orders_payment_status == 1) {

							if ($orders['prepayment'] <= $orders_amount_for_provider) {

								$orders_amount_for_provider = $orders_amount_for_provider - $orders['prepayment'];
								$orders_amount_for_provider_show = '<b class="text-danger">От поставщика забрать:</b> <b>'.$orders_amount_for_provider.' грн.</b>';

							} else {

								$orders_amount_for_provider = $orders['prepayment'] - $orders_amount_for_provider;
								$orders_amount_for_provider_show = '<b class="text-danger">Поставщику отдать:</b> <b>'.$orders_amount_for_provider.' грн.</b>';

							}

							$orders_amount_for_provider_commission = '<b class="text-success">'.($orders_amount_for_provider_commission).' грн.</b>';
						} elseif ($orders_payment_status == 2 or $orders_payment_status == 3) {
							$orders_amount_for_provider_show = '<b class="text-danger">Поставщику отдать:</b> <b>'.$orders_amount_for_provider.' грн.</b>';
							$orders_amount_for_provider_commission = '<b class="text-danger">'.($orders_amount_for_provider_commission).' грн.</b>';
						}

						switch ($orders['status_provider']) {
							case 1:
								$selected_status_provider[1] = 'selected';
								break;
							case 2:
								$selected_status_provider[2] = 'selected';
								break;
							default:
								$selected_status_provider[0] = 'selected';
								break;
						}

?>
							<?if($user['admin'] == 1):?>
							<div class="row mt-3">
								<div class="col-lg-5 mb-lg-0 mb-5">
									<?=$orders_amount_for_provider_show?>
									<form method="POST">
										<input type="hidden" name="order" value="<?=$orders_id?>">
										<div class="row mt-2">
											<div class="col-lg-8 col-sm-12 mb-1">
												<select class="form-control mb-1" name="status_provider">
													<option value="0" <?=$selected_status_provider[0]?>>Не задано</option>
													<option value="1" <?=$selected_status_provider[1]?>>От поставщика получили деньги</option>
													<option value="2" <?=$selected_status_provider[2]?>>Поставщику отдали деньги</option>
												</select>
											</div>
											<div class="col-lg-4">
												<button type="submit" class="btn btn-success">Сохранить</button>
											</div>
										</div>
									</form>
								</div>
								<div class="col-lg-3 text-center mb-lg-0 mb-5">
									<b>Коммисия:</b> <?=$orders_amount_for_provider_commission?>
								</div>
								<div class="col-lg-4 text-right">
									<p><b>Маржа:</b> <?=$orders_margine?> грн.</p>
									<p class="border border-danger"><b>Заробіток маркетолога:</b> <?=$marketer_show?> грн.</p>
								</div>
							</div>
							<?endif;?>
						</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-8 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Клиент</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-7">

<?

$client_phone_number = substr($orders['client'], strpos($orders['client'], '+') + 1);
$client_phone_number = str_replace('(', '', $client_phone_number);
$client_phone_number = str_replace(')', '', $client_phone_number);
$client_phone_number = str_replace(' ', '', $client_phone_number);
$client_phone_number = str_replace('-', '', $client_phone_number);

$client_phone_number_call = '<a href="tel:+'.$client_phone_number.'" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-phone" aria-hidden="true"></i> Звонить</a>';

$client_phone_number = '<a href="viber://chat?number='.$client_phone_number.'" target="_blank"><img src="/assets/images/social/viber.png" width="30"></a>';

?>

				<?=$orders['client']?> <?=$client_phone_number_call?> <?=$client_phone_number?><br>
				<?=$orders['delivery_address']?>

					</div>
					<div class="col-sm-5">
						<form method="POST">
							<input type="hidden" name="order" value="<?=$orders['id']?>">
							<div class="input-group mb-2">
								<input type="number" name="invoice_number" class="form-control" placeholder="Введите ТТН" value="<?=$orders['invoice_number']?>">
								<div class="input-group-append">
									<span class="input-group-text">
										<a href="https://novaposhta.ua/tracking/?cargo_number=<?=$orders['invoice_number']?>" target="_blank">
											<img src="/assets/images/novaposhta_icon.png" alt="NP" width="24">
										</a>
									</span>
								</div>
							</div>
						</form>

<?

				for ($i=0; $i < count($orders_status_cs_arr); $i++) { 
							
					if ($orders['status_cs'] == $orders_status_cs_arr[$i][0]) $orders_status_cs = $orders_status_cs_arr[$i][1];

				}

?>

				<span class="font-weight-bold"><?=$orders_status_cs?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Информация</h4>
			</div>
			<div class="card-body">

				<?if(($orders_payment_status == 1 or $orders_payment_status == 2) and $orders_user_id == 7037):?>

				<form method="POST">
					<input type="hidden" name="order" value="<?=$orders_id?>">
					<div class="form-group">
						<label for="paymentStatusSelect">Способ оплаты:</label>
						<div class="input-group mb-3">
						<select class="form-control font-weight-bold <?=(($orders_payment_status==1)?'text-info':'text-danger')?>" id="paymentStatusSelect" name="payment">
				 			<option value="1"<?=(($orders_payment_status==1)?' selected':'')?>>Наложенный платеж</option>
				 			<option value="2"<?=(($orders_payment_status==2)?' selected':'')?>>Перевод на карту 100%</option>
				 		</select>
						<div class="input-group-append">
							<button type="submit" class="btn btn-outline-success" type="button">Изменить</button>
						</div>
					</div>
					</div>
				</form>

				<?else:?>

				Способ оплаты: <?=$orders['payment']?><br>

				<?endif;?>

<?

				if ($orders['prepayment'] > 0) $orders['amount'] = $orders['amount'] - $orders['prepayment'];

?>

				Сумма оплаты: <b><?=$orders['amount']?> грн.</b><br>
				Выплата дропшиперу: <?=$orders['income']?> грн.<br><br>
				Зарезервированная сумма баланса дропшипера <i class="material-icons help_outline" data-toggle="tooltip" title="После завершения заказа или отмены, зарезервированная сумма баланса пользователя будет возвращена на его баланс">help_outline</i>: <?=$orders['reserve_balance']?> грн.<br><br>

				<?if($orders_user_id == 7037):?>

				<form method="POST">
					<input type="hidden" name="act" value="prepayment">
					<input type="hidden" name="order" value="<?=$orders_id?>">
					<div class="form-group">
						<label for="prepaymentInput">Предоплата:</label>
						<div class="input-group mb-3">
						<input type="number" name="prepayment" id="prepaymentInput" class="form-control" value="<?=$orders['prepayment']?>">
						<div class="input-group-append">
							<button type="submit" class="btn btn-outline-success" type="button">Изменить</button>
						</div>
					</div>
					</div>
				</form>

				<?else:?>
				Предоплата: <?=$orders['prepayment']?> грн.<br><br>
				<?endif;?>

				Статус заказа:<br>
				<?=$orders_status?><br>
<?

						 switch ($orders['status']) {
						 	case 0:
						 		$selected_status[0] = 'selected';
						 		break;
						 	case 1:
						 		$selected_status[1] = 'selected';
						 		break;
						 	case 2:
						 		$selected_status[2] = 'selected';
						 		break;
						 	case 3:
						 		$selected_status[3] = 'selected';
						 		break;
						 	case 4:
						 		$selected_status[4] = 'selected';
						 		break;
						 	case 5:
						 		$selected_status[5] = 'selected';
						 		break;
						 	case 6:
						 		$selected_status[6] = 'selected';
						 		break;
						 	case 7:
						 		$selected_status[7] = 'selected';
						 		break;
						 	case 8:
						 		$selected_status[8] = 'selected';
						 		break;
						 	case 9:
						 		$selected_status[9] = 'selected';
						 		break;
						 	case 10:
						 		$selected_status[10] = 'selected';
						 		break;
						 	default:
						 		$selected_status[0] = 'selected';
						 		break;
						 }

?>
						<?if ($orders['status'] != 4 and $orders['status'] != 7 and $orders['status'] != 8 and $orders['status'] != 9):?>
						<a class="btn btn-primary btn-sm mb-2" data-toggle="collapse" href="#collapseStatus<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseStatus<?=$orders_id?>">Изменить статус</a>
						<div class="collapse text-center" id="collapseStatus<?=$orders_id?>">
							<form method="POST">
								<input type="hidden" name="order" value="<?=$orders_id?>">
								<select class="form-control mb-1" name="status">
									<option value="0" <?=$selected_status[0]?>>Новый</option>
									<option value="1" <?=$selected_status[1]?>>Подтвержден</option>
									<option value="2" <?=$selected_status[2]?>>Ожидаем оплату</option>
									<option value="3" <?=$selected_status[3]?>>В обработке</option>
									<?if ($user_id == 2):?>
									<option value="4" <?=$selected_status[4]?>>Отправлен</option>
									<?endif;?>
									<option value="5" <?=$selected_status[5]?>>Прибыл</option>
									<option value="6" <?=$selected_status[6]?>>Оплачен</option>
									<?if ($user_id == 2):?>
									<option value="7" <?=$selected_status[7]?>>Завершен</option>
									<?endif;?>
									<option value="8" <?=$selected_status[8]?>>Отменен</option>
									<?if ($user_id == 2):?>
									<option value="9" <?=$selected_status[9]?>>Отказ</option>
									<?endif;?>
									<option value="10" <?=$selected_status[10]?>>Не дозвон</option>
									<option value="11" <?=$selected_status[11]?>>Дубль</option>
								</select>
								<?if ($user_id == 2):?>
								<input type="number" step="0.01" name="failure_commission" class="form-control" placeholder="Комиссия отказа">
								<?endif;?>
								<button type="submit" class="btn btn-success btn-sm mt-1">Изменить</button>
							</form>
						</div>
						<?endif;?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-8 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Комментарии пользователям</h4>
			</div>
			<div class="card-body">
				<div style="height: 200px; overflow: auto;">
<?

						$sql_messages = "SELECT * FROM `orders_messages` WHERE `order_id`='{$orders_id}' ORDER BY `created` ASC";
						$query_messages = mysqli_query($db, $sql_messages) or die(mysqli_error($db));

						if (mysqli_num_rows($query_messages) > 0) {

						while ($orders_messages = mysqli_fetch_assoc($query_messages)) {

							$sql_messages_user = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$orders_messages['user_id']}'";
							$query_messages_user = mysqli_query($db, $sql_messages_user) or die(mysqli_error($db));
							$orders_messages_user = mysqli_fetch_assoc($query_messages_user);

							$orders_messages['message'] = str_replace("\r\n", "<br>", $orders_messages['message']);
							$orders_messages_url = stristr($orders_messages['message'], 'http');
							$orders_messages_url_tmp = substr(strrchr($orders_messages_url, ' '), 1);
							$orders_messages_url = str_replace($orders_messages_url_tmp, '', $orders_messages_url);
							$orders_messages['message'] = str_replace($orders_messages_url, '<a href="'.$orders_messages_url.'" target="_blank">'.$orders_messages_url.'</a>', $orders_messages['message']);

							if ($orders_messages['type_user'] == 1) {

?>

								<div>
									<div class="mb-0">
										<small class="font-italic"><?=date('d.m.Y, H:i', strtotime($orders_messages['created']))?></small> - 
										<?if($user['admin'] == 1):?>
										<form method="POST" action="/admin/users/" class="d-inline-block">
											<input type="hidden" name="search" value="<?=$orders_messages_user['id']?>">
											<button type="submit" class="btn btn-link btn-sm font-weight-bold text-dark"><?=$orders_messages_user['name']?> <?=$orders_messages_user['surname']?></button>
										</form>
										<?endif;?>
									</div>
									<div class="alert alert-primary d-inline-block mb-1 pt-1 pb-1" role="alert" style="max-width: 900px;width: 100%;"><?=$orders_messages['message']?></div>
								</div>

<?								

							} else {

?>

								<div class="text-right mr-2">
									<div class="mb-0">
										<?if($user['admin'] == 1):?>
										<form method="POST" action="/admin/users/" class="d-inline-block">
											<input type="hidden" name="search" value="<?=$orders_messages_user['id']?>">
											<button type="submit" class="btn btn-link btn-sm font-weight-bold text-dark"><?=$orders_messages_user['name']?> <?=$orders_messages_user['surname']?></button>
										</form>
										<?endif;?>
										 - <small class="font-italic"><?=date('d.m.Y, H:i', strtotime($orders_messages['created']))?></small>
									</div>
									<div class="alert alert-warning d-inline-block mb-1 pt-1 pb-1 text-left" role="alert" style="max-width: 900px;width: 100%;"><?=$orders_messages['message']?></div>
								</div>

<?

							}

						}

						} else {

							echo 'еще нет';

						}

?>						
						</div>
						<div class="row">
							<div class="col-sm-4 mb-3">
								<form method="POST">
									<input type="hidden" name="order" value="<?=$orders_id?>">
									<textarea class="form-control" name="comment" style="height:1px;visibility: hidden;">Сообщите клиенту что его посылка уже находится на отделении Новой почты и он может приходить и забирать ее!</textarea>
									<button type="submit" class="btn btn-success btn-sm mt-1">Посылка на отдиление</button>
								</form>
							</div>
							<div class="col-sm-4">
								<form method="POST">
									<input type="hidden" name="order" value="<?=$orders_id?>">
									<textarea class="form-control" name="comment" style="height:1px;visibility: hidden;">Реквизиты для оплаты вашего заказа: \r\nПриват Банк: 0000 0000 0000 0000 \r\nили \r\nMonobank: 0000 0000 0000 0000. \r\nВнимание просьба учитывать комиссию банка при переводе денег! Как оплатите отправьте скрин квитанции по номеру вайбера или телеграма</textarea>
									<button type="submit" class="btn btn-success btn-sm mt-1">Реквизиты для оплаты</button>
								</form>
							</div>
							<div class="col-sm-4">
								
							</div>
						</div>
						<a data-toggle="collapse" href="#collapseMessage<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseMessage<?=$orders_id?>">Добавить комментарий</a>
						<div class="collapse text-center" id="collapseMessage<?=$orders_id?>">
							<form method="POST">
								<input type="hidden" name="order" value="<?=$orders_id?>">
								<textarea class="form-control" name="comment"></textarea>
								<button type="submit" class="btn btn-success btn-sm mt-1">Добавить</button>
							</form>
						</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Примечания сотрудников <small class="font-italic">(Пользователи их не видят)</small></h4>
			</div>
			<div class="card-body">
				<div style="height: 200px; overflow: auto; padding: 0 15px;">
				<?

					$sql_notes = "SELECT * FROM `orders_notes` WHERE `order_id`='{$orders_id}' ORDER BY `created` ASC";
					$query_notes = mysqli_query($db, $sql_notes) or die(mysqli_error($db));

					if (mysqli_num_rows($query_notes) > 0) {

						while ($orders_notes = mysqli_fetch_assoc($query_notes)) {

							$sql_notes_user = "SELECT `id`, `name`, `surname` FROM `users` WHERE `id`='{$orders_notes['user_id']}'";
							$query_notes_user = mysqli_query($db, $sql_notes_user) or die(mysqli_error($db));
							$orders_notes_user = mysqli_fetch_assoc($query_notes_user);

							$orders_notes['note'] = str_replace("\r\n", "<br>", str_replace("'", "\'", $orders_notes['note']));

?>
							
							<div class="row">
								<div class="col-sm-6">
									<?if($user['admin'] == 1):?>
									<form method="POST" action="/admin/users/" class="d-inline-block float-left">
										<input type="hidden" name="search" value="<?=$orders_notes_user['id']?>">
										<button type="submit" class="btn btn-link btn-sm font-weight-bold text-dark"><?=$orders_notes_user['name']?> <?=$orders_notes_user['surname']?></button>
									</form>
									<?endif;?>
								</div>
								<div class="col-sm-6">
									<p class="mb-0 font-italic text-right">
										<small><?=date('d.m.Y, H:i', strtotime($orders_notes['created']))?></small>
									</p>
								</div>
							</div>
							<div class="alert alert-secondary mb-1 pt-1 pb-1" role="alert"><?=$orders_notes['note']?></div>

<?

						}

					} else {

							echo 'еще нет примечаний';

					}

?>						
				</div>
				<a data-toggle="collapse" href="#collapseNote<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseNote<?=$orders_id?>">Добавить примечание</a>
				<div class="collapse text-center" id="collapseNote<?=$orders_id?>">
					<form method="POST">
						<input type="hidden" name="order" value="<?=$orders_id?>">
						<textarea class="form-control" name="note"></textarea>
						<button type="submit" class="btn btn-success btn-sm mt-1">Добавить</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?

$order_info_goods = $order_info_goods."\n";
$order_info_goods_client = $order_info_goods_client."\n";

$orders['client'] = $orders['client']."\n";
$orders['delivery_address'] = $orders['delivery_address']."\n\n";

switch ($orders_payment_status) {
 	case 1:
 		$orders_info = "Накладений платіж - ".ceil($orders['amount'])." грн.";
 		break;
 	case 2:
 	case 3:
 		$orders_info = "Оплата на реквізити. До оплати: " . ceil($orders['amount']) . " грн.";
 		break;
 	default:
 		$orders_info = "Спосіб платежу не вказано";
 		break;
}

?>

<textarea id="infoOrderCopy" style="height:1px;border:0;">
Товари:
<?=$order_info_goods?>
Клієнт:
<?=$orders['client']?>
<?=$orders['delivery_address']?>
Інформація:
<?=$orders_info?>
</textarea>

<textarea id="infoOrderForClientCopy" style="height:1px;border:0;">
Замовлення №<?=$orders_id."\n\n"?>
Товари:
<?=$order_info_goods_client?>
Клієнт:
<?=$orders['client']?>
<?=$orders['delivery_address']?>
Інформація:
<?=($orders['prepayment'] > 0) ? "Предоплата: ".$orders['prepayment']." грн.\n" : ''?>
<?=$orders_info."\n\n"?>
<?if($orders_payment_status != 1):?>
Реквізити оплати: 4149 4993 7400 7019
Prokopiak H.D.
<?endif;?>
</textarea>
<!-- 
Мы в Viber: https://invite.viber.com/?g2=AQBp01MQHxN8QU8RrJ7v%2BNd8ev%2FYR5aJNzt64CI0AzomR4d5OG7Nn8ZZ3Y1VysJV
Мы в Telegram: https://t.me/+b-qkcUczBtU1MTYy
С уважением интернет магазин: https://onlinenaxodka.com.ua
 -->

<div class="modal fade" id="addGoodsToOrderModal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить товар в заказ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="form-group">
						<label class="search_goods">Поиск товара</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">
									<i class="fa fa-search" aria-hidden="true"></i>
								</span>
							</div>
							<input type="search" name="search_goods" class="form-control" id="search_goods" placeholder="Введите Название или Код товара..." onkeyup="searchGoodsInCatalog(event, this, <?=$orders_id?>)">
						</div>
					</div>
				</form>
				<div id="searchedGoods"></div>
			</div>
		</div>
	</div>
</div>

<?

	}

}

?>

<?if($user['admin'] == 1 or ($user['admin'] == 2 and $user_id == 5672)):?>
<div class="modal fade" id="googleLinks">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ссылки на заказы поставщиков</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Карта для оплаты товаров магазина onlinenaxodka.com.ua: <b>0000 0000 0000 0000</b></p>
				<p>Карта для дропшиппинга onlinenaxodka.com: <b class="text-primary">0000 0000 0000 0000</b></p>
				<!-- <p>Получения денег на складчину ROZETKA: <b>4731 2191 1251 1302</b></p> -->
				<ul class="list-group">

<?

					$privider_google_links = array();
					
					$nn=0;

					foreach ($privider_google_links as $google_links) {

						$nn++;
						
?>

					<li class="list-group-item">
						<h5 class="mb-1">
							<b><?=$nn?>. <?=$google_links[0]?></b> 
							<a href="<?=$google_links[2]?>" class="btn btn-primary ml-3 float-right" target="_blank">
								Перейти 
								<i class="material-icons float-left mr-3">open_in_new</i>
							</a>
							<a href="<?=$google_links[4]?>" class="btn ml-3 float-right" target="_blank"> 
								<?if ($google_links[3] == 'Viber'):?>
								<img src="/assets/images/social/viber.png" alt="Viber" width="30">
								<?elseif ($google_links[3] == 'Telegram'):?>
								<img src="/assets/images/social/telegram.png" alt="Telegram" width="30">
								<?elseif ($google_links[3] == 'Skype'):?>
								<img src="/assets/images/social/skype.png" alt="Skype" width="30">
								<?endif;?>
							</a>
						</h5>
						<h5 class="mb-1">(<i><?=$google_links[1]?></i>)</h5>
						<h6 class="mb-0"><span>Отправка с: <b><?=$google_links[5]?></b></span></h6>
					</li>

<?					
					}
?>

				</ul>
			</div>
		</div>
	</div>
</div>
<?endif;?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>