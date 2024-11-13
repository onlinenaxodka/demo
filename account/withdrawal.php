<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate = mysqli_fetch_assoc($query);

?>

<?=$alert_message?>

<div class="alert alert-primary" role="alert">
	<!-- <strong>Важно!</strong> Вывод средств на платформе производится каждый вторник и четверг до 22:00. Операция проводится в ручном режиме и занимает от 5 минут до 48 часов в рабочее время! -->
	<strong>Важно!</strong> Вывод средств на платформе производится в порядке очереди. Операция проводится в ручном режиме и занимает от 5 минут до 48 часов в рабочее время!
</div>

<form action="/account/withdrawal/" method="POST">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="inputWithdrawal">Сумма выплаты</label>
				<div class="input-group mb-3">
					<input class="form-control form-control-lg" type="number" step="0.01" placeholder="Введите сумму" id="inputWithdrawal" name="sum" required>
					<div class="input-group-append">
						<span class="input-group-text">грн.</span>
					</div>
				</div>
			</div>
			<div class="form-group text-center">
				<button type="submit" class="btn btn-primary btn-block btn-lg">Заказать выплату</button>
			</div>
		</div>
	</div>
</form>
<p class="text-muted text-center mt-5">Комисию за обработку платежа оплачивает сам клиент</p>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>