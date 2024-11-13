<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$sql = "SELECT SUM(`cash`) AS sum_cash FROM `users`";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$users_sum_cash = mysqli_fetch_assoc($query);

?>
<div class="row justify-content-center">
	<div class="col-sm-12 mb-3">
		<div class="card">
			<h5 class="card-header">Общая сумма балансов пользователей <span style="float: right;"><?=date('d.m.Y H:i:s')?></span></h5>
			<div class="card-body">
				<h3 class="text-center"><?=$users_sum_cash['sum_cash']?> грн.</h3>
			</div>
		</div>
	</div>
</div>
<div class="row justify-content-center">
	<div class="col-sm-8">
<?

$admins_name_for_balance = array(
	'1' => 'Админ ON',
	'2' => 'Админ R',
	'4' => 'Админ Z',
	'5' => 'Админ T',
	'27' => 'Зарплат. фонд',
	'22' => 'Призовой фонд',
	'7037' => 'Наш магазин'
);

?>
		<div class="row">

<?

			$sql = "SELECT `id`,`cash` FROM `users` WHERE `id` IN (1,22,27,7037)";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			while ($balance_admin = mysqli_fetch_assoc($query)) {

				$balance_admin_id = $balance_admin['id'];

				$sql_add_funds = "SELECT SUM(`add_funds`) AS add_funds_admin FROM `transactions` WHERE `user_id`={$balance_admin_id} AND `type`=3 AND `status`=2";
				$query_add_funds = mysqli_query($db, $sql_add_funds) or die(mysqli_error());
				$transactions_admin = mysqli_fetch_assoc($query_add_funds);

?>

			<div class="col mb-3">
				<div class="card<?=($balance_admin_id==7037?' border-danger':'')?>">
					<h5 class="card-header text-center<?=($balance_admin_id==7037?' font-weight-bold':'')?>"><?=$admins_name_for_balance[$balance_admin['id']]?></h5>
					<div class="card-body">
						<h3 class="text-center"><?=$balance_admin['cash']?> грн.</h3>
						<p class="text-center mb-0">Прибыль с заказов:</p>
						<p class="text-center mb-0">
							<b class="text-muted"><?=$transactions_admin['add_funds_admin']?> грн.</b>
						</p>
					</div>
				</div>
			</div>

<?					

			}

?>

		</div>
		<div class="row">

<?

			$sql = "SELECT `id`,`cash` FROM `users` WHERE `id` IN (2,4,5)";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			
			while ($balance_admin = mysqli_fetch_assoc($query)) {

				$balance_admin_id = $balance_admin['id'];

				$sql_add_funds = "SELECT SUM(`add_funds`) AS add_funds_admin FROM `transactions` WHERE `user_id`={$balance_admin_id} AND `type`=3 AND `status`=2";
				$query_add_funds = mysqli_query($db, $sql_add_funds) or die(mysqli_error());
				$transactions_admin = mysqli_fetch_assoc($query_add_funds);

?>

			<div class="col mb-3">
				<div class="card">
					<h5 class="card-header text-center"><?=$admins_name_for_balance[$balance_admin['id']]?></h5>
					<div class="card-body">
						<h3 class="text-center"><?=$balance_admin['cash']?> грн.</h3>
						<p class="text-center mb-0">Прибыль с заказов:</p>
						<p class="text-center mb-0">
							<b class="text-muted"><?=$transactions_admin['add_funds_admin']?> грн.</b>
						</p>
					</div>
				</div>
			</div>

<?					

			}

?>

		</div>
	</div>
	<div class="col-sm-4">
		<div class="card mb-3">
			<h5 class="card-header">Сумма балансов дропов с позитивным балансом</h5>
			<div class="card-body">
<?

$sql = "SELECT SUM(`cash`) AS sum_cash FROM `users` WHERE `id` NOT IN (1,2,4,5,22,27,7037) AND `cash` > 0";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$users_sum_cash_drops = mysqli_fetch_assoc($query);

?>
				<h3 class="text-center"><?=($users_sum_cash_drops['sum_cash'] ?: 0)?> грн.</h3>
			</div>
		</div>
		<div class="card mb-3">
			<h5 class="card-header" style="font-size: 19px;">Сумма балансов дропов с отрицательным балансом</h5>
			<div class="card-body">
<?

$sql = "SELECT SUM(`cash`) AS sum_cash FROM `users` WHERE `id` NOT IN (1,2,4,5,22,27,7037) AND `cash` < 0";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$users_minussum_cash_drops = mysqli_fetch_assoc($query);

?>
				<h3 class="text-center"><?=($users_minussum_cash_drops['sum_cash'] ?: 0)?> грн.</h3>
			</div>
		</div>
		<div class="card">
			<p class="text-center mt-3">
				<button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Смотреть балансы дропов ≠ 0</button>
			</p>
			<div class="collapse" id="collapseExample">
				<ul class="list-group list-group-flush">
<?

			$sql = "SELECT `id`, `name`, `surname`, `cash` FROM `users` WHERE `id` NOT IN (1,2,4,5,22,27,7037) AND `cash` != 0 ORDER BY `cash` ASC";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			
			while ($balance_user = mysqli_fetch_assoc($query)) {

?>

					<li class="list-group-item d-flex justify-content-between align-items-center">
						<form method="POST" action="/admin/users/" class="d-inline-block">
							<input type="hidden" name="search" value="<?=$balance_user['id']?>">
							<button type="submit" class="btn btn-link">[<?=$balance_user['id']?>] <?=$balance_user['name']?> <?=$balance_user['surname']?></button>
						</form>
						<span class="badge badge-secondary badge-pill"><?=$balance_user['cash']?> грн.</span>
					</li>

<?

			}

?>

				</ul>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>