<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$sql = "SELECT `id` FROM `users`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_fb`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_fb = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_tw`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_tw = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_gl`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_gl = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_vk`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_vk = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_ok`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_ok = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users_ml`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_ml = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `admin`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_admin = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `activated`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_activated = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `blocked`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_blocked = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `admin`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_provider = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `employee`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_employee = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `lang`='uk'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_uk_lang = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `lang`='ru'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_ru_lang = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `cash`>0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_positive_cash = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `id` IN (SELECT `partner_id` FROM `users`)";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_own_team = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `status`=0";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_status_beginner = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_status_dropshipper = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `status`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_status_manager = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `status`=3";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_status_supervisor = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE `status`=4";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_status_director = mysqli_num_rows($query);

$sql = "SELECT `id` FROM `users` WHERE DATE(`was`)=CURDATE()";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_users_were = mysqli_num_rows($query);

$sql = "SELECT * FROM `orders` WHERE `status`=7";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$count_orders_completed = mysqli_num_rows($query);

$orders_margine_sum = 0;
$orders_margine_sum_clean = 0;

while ($orders = mysqli_fetch_assoc($query)) {
	
	$orders['goods'] = json_decode($orders['goods'], true);

	$orders_margine = 0;
	$orders_margine_clean = 0;

	for ($i=0; $i < count($orders['goods']); $i++) {

		$goods_price_purchase_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_purchase'];
		$goods_goods_price_recom_count = $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price_recom'];

		$orders_margine += $goods_goods_price_recom_count - $goods_price_purchase_count;

		$goods_rate = 0.22 + (0.7 - $orders['goods'][$i]['catalog_rate']);
		$orders_margine_clean += ($goods_goods_price_recom_count - $goods_price_purchase_count) * $goods_rate;

	}

	$orders_margine_sum += $orders_margine;
	$orders_margine_sum_clean += $orders_margine_clean;

}

$orders_margine_sum = $count_orders_completed ? $orders_margine_sum / $count_orders_completed : 0;
$orders_margine_sum = number_format($orders_margine_sum, 2, '.', '');

$orders_margine_sum_clean = $count_orders_completed ? $orders_margine_sum_clean / $count_orders_completed : 0;
$orders_margine_sum_clean = number_format($orders_margine_sum_clean, 2, '.', '');

?>

<div class="row">
	<div class="col-sm-5 mb-3">
		<div class="card">
			<h5 class="card-header">Пользователи</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-6 mb-3">
						<div class="bg-dark" style="height:100%;width:100%;color:#fff;border-radius:5px;padding:10px">
							<h1 class="text-center pt-2 pb-2" style="font-size: 50px"><?=$count_users?></h1>
							<div class="row text-center mb-2">
								<div class="col-6">
									<img src="/assets/images/social/fb.png" width="30"> <?=$count_users_fb?>
								</div>
								<div class="col-6">
									<img src="/assets/images/social/tw.png" width="30"> <?=$count_users_tw?>
								</div>
							</div>
							<div class="row text-center mb-2">
								<div class="col-6">
									<img src="/assets/images/social/gl.png" width="30"> <?=$count_users_gl?>
								</div>
								<div class="col-6">
									<img src="/assets/images/social/vk.png" width="30"> <?=$count_users_vk?>
								</div>
							</div>
							<div class="row text-center mb-2">
								<div class="col-6">
									<img src="/assets/images/social/ok.png" width="30"> <?=$count_users_ok?>
								</div>
								<div class="col-6">
									<img src="/assets/images/social/ml.png" width="30"> <?=$count_users_ml?>
								</div>
							</div>
							<ul class="list-group pt-3">
								<li class="list-group-item text-dark pt-1 pb-1 pl-2 pr-2">
									<span style="font-size:16px">Директоры: <b class="float-right"><?=$count_users_status_director?></b></span>
								</li>
								<li class="list-group-item text-dark pt-1 pb-1 pl-2 pr-2">
									<span style="font-size:16px">Супервайзеры: <b class="float-right"><?=$count_users_status_supervisor?></b></span>
								</li>
								<li class="list-group-item text-dark pt-1 pb-1 pl-2 pr-2">
									<span style="font-size:16px">Наставники: <b class="float-right"><?=$count_users_status_manager?></b></span>
								</li>
								<li class="list-group-item text-dark pt-1 pb-1 pl-2 pr-2">
									<span style="font-size:16px">Дропшипперы: <b class="float-right"><?=$count_users_status_dropshipper?></b></span>
								</li>
								<li class="list-group-item text-dark pt-1 pb-1 pl-2 pr-2">
									<span style="font-size:16px">Новички: <b class="float-right"><?=$count_users_status_beginner?></b></span>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6 mb-3">
						<ul class="list-group list-group-flush">
							<li class="list-group-item p-2">
								<span style="font-size:18px">Админы: <b class="float-right"><?=$count_users_admin?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Активированные: <b class="float-right"><?=$count_users_activated?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Заблокированные: <b class="float-right"><?=$count_users_blocked?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Поставщики: <b class="float-right"><?=$count_users_provider?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Работники: <b class="float-right"><?=$count_users_employee?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Украиноязычные: <b class="float-right"><?=$count_users_uk_lang?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">Русскоязычные: <b class="float-right"><?=$count_users_ru_lang?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">С позитивным балансом: <b class="float-right"><?=$count_users_positive_cash?></b></span>
							</li>
							<li class="list-group-item p-2">
								<span style="font-size:18px">С собственной командой: <b class="float-right"><?=$count_users_own_team?></b></span>
							</li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<ul class="list-group list-group-flush">
							<li class="list-group-item p-2">
								<span style="font-size:18px">Были <b class="text-success">сегодня</b> онлайн: <b class="float-right"><?=$count_users_were?></b></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-7 mb-3">
		<div class="row">
			<div class="col-sm-7 mb-3">
				<div class="card mb-3">
					<div class="card-header">
						<h5 class="mb-0">Наши корпоративные почтовые аккаунты</h5>
					</div>
					<div class="card-body">
						<p class="text-center">
							<a href="https://webmail1.hostinger.com.ua/" class="btn btn-primary pl-5 pr-5" target="_blank">Вход</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-sm-5 mb-3">
				<div class="card mb-3">
					<div class="card-header">
						<h5 class="mb-0">Количество всего завершенных заказов</h5>
					</div>
					<div class="card-body">
						<h1 class="text-center">
							<b><?=$count_orders_completed?></b>
						</h1>
					</div>
				</div>
				<div class="card mb-3">
					<div class="card-header">
						<h5 class="mb-0">Средняя маржа на все завершенные заказы</h5>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6">
								<h3 class="text-center">Грязная</h3>
								<h3 class="text-center">
									<b><?=$orders_margine_sum?> грн.</b>
								</h3>
							</div>
							<div class="col-sm-6">
								<h3 class="text-center">Чистая</h3>
								<h3 class="text-center">
									<b><?=$orders_margine_sum_clean?> грн.</b>
								</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0">Формирование агентской цены</h5>
					</div>
					<div class="card-body">
						5% от цены закупки, если цена закупки от 0 до 500 грн <br>
						4% от цены закупки, если цена закупки от 500 до 1000 грн <br>
						3% от цены закупки, если цена закупки от 1000 до 5000 грн <br>
						2% от цены закупки, если цена закупки от 5000 до 10000 грн <br>
						1% от цены закупки, если цена закупки от 10000 грн <br>
						но не более 4% от маржи
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 
<?

$sql = "SELECT SUBSTRING_INDEX(`mail`, '@', -1) AS mail_domen, COUNT(`id`) AS count_domen FROM `users` GROUP BY mail_domen ORDER BY mail_domen ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

?>

<ul class="list-group d-block">
<?

while ($users_mail_domen = mysqli_fetch_assoc($query)) {

?>
	<li class="list-group-item d-inline-block w-100" style="max-width: 290px;">
		<div class="d-flex justify-content-between align-items-center">
			<a href="http://<?=$users_mail_domen['mail_domen']?>" target="_blank"><?=$users_mail_domen['mail_domen']?></a>
			<span class="badge badge-primary badge-pill"><?=$users_mail_domen['count_domen']?></span>
		</div>
	</li>
<?

}

?>
</ul>
 -->
<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>