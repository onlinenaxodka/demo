<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate = mysqli_fetch_assoc($query);

$sql = "SELECT * FROM `transactions` WHERE `user_id`='{$user_id}' AND `type`=0 AND `status`=1 LIMIT 1";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$transactions = mysqli_fetch_assoc($query);

if (mysqli_num_rows($query) == 0) {

?>

<?=$alert_message?>

<form action="/account/add_funds/" method="POST">
	<div class="row justify-content-center mt-5">
		<div class="col-sm-4">
			<div class="form-group">
				<label for="inputAddFunds">Сумма пополнения</label>
				<div class="input-group mb-3">
					<input class="form-control form-control-lg" type="number" step="0.01" value="<?=((!empty($_GET['sum'])) ? $_GET['sum'] : 100)?>" placeholder="Введите сумму пополнения" id="inputAddFunds" name="sum" required>
					<div class="input-group-append">
						<span class="input-group-text">грн.</span>
					</div>
				</div>
			</div>
			<div class="form-group text-center">
				<button type="submit" class="btn btn-primary btn-block btn-lg">Пополнить</button>
			</div>
			<?if (!empty($_SESSION['cart'])):?>
			<a href="/account/cart/" class="btn btn-danger btn-block">Вернуться в корзину</a>
			<?endif;?>
		</div>
	</div>
</form>
<p class="text-muted text-center mt-5">Комисию за обработку платежа оплачивает сам клиент</p>

<?

}

if (mysqli_num_rows($query) == 1) {

?>

<div class="alert alert-info pt-1 pb-1" role="alert" style="font-size: 12px;line-height: 24px;">
	<i class="material-icons float-left mr-2">info</i><strong>Внимание!</strong> Данная операция производится оператором в ручном режиме и занимает от 5 минут до 24 часов в рабочее время. Праздничные и выходные дни — свободный график.
</div>

<?

	if ($transactions['updated'] == $transactions['created']) {

		//$add_funds_start_modal = true;

$eps = !empty($_GET['eps']) ? intval($_GET['eps']) : 2;
$eps = ($eps >= 1 or $eps <= 5) ? $eps : 2;

$ps_total = $transactions['add_funds'];
$currency_code = 'грн';

$ps_total = number_format($ps_total, 2, '.', '');
$add_funds_total = $ps_total . ' ' . $currency_code;

?>

<h2 class="text-center mb-3">Заявка <b>№<?=$transactions['id']?></b></h2>

<div class="row">
	<div class="col-sm-6 mb-3">
		<h4 class="mb-3">Как оплатить:</h4>
		<style type="text/css">
			#accordion .card-header .btn {
				white-space: normal;
			}
		</style>
		<div id="accordion">
			<div class="card">
				<div class="card-header pl-1 pt-0 pb-0" id="heading1">
					<h5 class="mb-0">
						<button class="btn btn-link btn-block text-left" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">Шаг 1 - Выберите вариант перевода средств</button>
					</h5>
				</div>
				<div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion">
					<div class="card-body pl-2 pr-2">
						<nav class="nav nav-pills nav-fill">
							<a href="/account/add_funds/?eps=2" class="nav-item nav-link<?=($eps == 2 ? ' active' : '')?>">
								<img src="https://privatbank.ua/sites/pb/img/favicon/favicon-32x32.png" width="24" alt="PrivatBank"> ПриватБанк
							</a>
							<a href="/account/add_funds/?eps=3" class="nav-item nav-link<?=($eps == 3 ? ' active' : '')?>">
								<img src="https://www.monobank.ua/resources/1.0.29.1-1713536407715/img/favicon/favicon-32x32.png" width="24" alt="Monobank"> Monobank
							</a>
						</nav>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header pl-1 pt-0 pb-0" id="heading2">
					<h5 class="mb-0">
						<button class="btn btn-link btn-block text-left" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">Шаг 2 - Переведите сумму платежа на наш счет</button>
					</h5>
				</div>
				<div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
					<div class="card-body text-center pl-2 pr-2">
						<?if ($eps == 1):?>

						<h5 class="card-title">Приват Банк Рахунок</h5>

						<?elseif ($eps == 2):?>

						<h5 class="card-title">ПриватБанк</h5>
						<p><b id="copyNumberCard">0000 0000 0000 0000</b></p>
						<button type="button" class="btn btn-dark btn-sm btn-clipboard" data-clipboard-target="#copyNumberCard" onclick="copyLink(this)">Скопировать <i class="fa fa-clone" aria-hidden="true"></i></button>

						<?elseif ($eps == 3):?>

						<h5 class="card-title">Monobank</h5>
						<p><b id="copyNumberCardMono">0000 0000 0000 0000</b></p>
						<button type="button" class="btn btn-dark btn-sm btn-clipboard" data-clipboard-target="#copyNumberCardMono" onclick="copyLink(this)">Скопировать <i class="fa fa-clone" aria-hidden="true"></i></button>

						<?endif;?>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header pl-1 pt-0 pb-0" id="heading3">
					<h5 class="mb-0">
						<button class="btn btn-link btn-block text-left" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">Шаг 3 - Назначение платежа</button>
					</h5>
				</div>
				<div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
					<div class="card-body text-center pl-2 pr-2">
						<p class="mb-2">Скопируйте и впишите в назначении платежа ваши Имя, Фамилию и Номер заявки:</p>
						<h5 class="mb-3">
							<b id="copyDescOperation"><?=$user['name']?> <?=$user['surname']?>, <?=$transactions['id']?></b>
						</h5>
						<button type="button" class="btn btn-dark btn-sm btn-clipboard" data-clipboard-target="#copyDescOperation" onclick="copyLink(this)">Скопировать <i class="fa fa-clone" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header pl-1 pt-0 pb-0" id="heading4">
					<h5 class="mb-0">
						<button class="btn btn-link btn-block text-left" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapse4">Шаг 4 - Подтверждение платежа</button>
					</h5>
				</div>
				<div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
					<div class="card-body text-center pl-2 pr-2">
						Нажмите на кнопку «Я оплатил заявку»
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 mb-3">
		<h4 class="mb-3">Сумма платежа: <b class="text-success"><?=$add_funds_total?></b></h4>
		<p style="font-size:12px">
			<span style="color:#FF5402">Пожалуйста, будьте внимательны!</span>
			Все поля должны быть заполнены в точном соответствии с инструкцией, в противном случае, платеж может не пройти.
		</p>
		<p style="margin-bottom: 0">Время оформления: <?=$transactions['updated']?></p>
		<p>Статус заявки: принята, ожидает оплаты клиентом</p>
	</div>
</div>

<div class="row mt-4 mb-5 text-center">
	<div class="col-sm-6 mb-4">
		<form action="/account/add_funds/" method="POST">
			<input type="hidden" name="ps" value="<?=$eps?>">
			<input type="hidden" name="pstotal" value="<?=$ps_total?>">
			<input type="hidden" name="confirm" value="<?=$transactions['id']?>">
			<button type="submit" class="btn btn-success btn-lg">Я ОПЛАТИЛ ЗАЯВКУ</button>
		</form>
	</div>
	<div class="col-sm-6 mb-4">
		<form action="/account/add_funds/" method="POST">
			<input type="hidden" name="cancel" value="<?=$transactions['id']?>">
			<button type="submit" class="btn btn-outline-danger btn-lg">ОТМЕНИТЬ ЗАЯВКУ</button>
		</form>
	</div>
</div>

<div class="modal fade" id="alertReminderAddFunds">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<h4 class="font-weight-bold">Уважаемый, <?=$user['name']?> <?=$user['surname']?>!</h4>
				<p>После перевода средств на наш счет, подтвердите пополнение баланса:</p>
				<div class="row mt-4 mb-3">
					<div class="col-sm-6">
						<p>Если <b>перевели</b> средства, нажмите <br><b class="text-success">"Я ОПЛАТИЛ ЗАЯВКУ"</b>.</p>
					</div>
					<div class="col-sm-6">
						<p>Если <b>не перевели</b> средства, нажмите <br><b class="text-danger">"ОТМЕНИТЬ ЗАЯВКУ"</b>.</p>
					</div>
				</div>
				<p>Спасибо за понимание, с уважением Online Naxodka!</p>
			</div>
		</div>
	</div>
</div>

<?

	} else {

?>

<div class="alert alert-info" role="alert" style="font-size: 12px">
	<div class="row">
		<div class="col-sm-1" style="padding:0 5px;width: 2%;"><i class="material-icons">info</i></div>
		<div class="col-sm-11" style="padding:0 5px;width: 98%;"><strong>Внимание!</strong> Данная операция производится оператором в ручном режиме и занимает от 5 минут до 24 часов в рабочее время.<br>Праздничные и выходные дни — свободный график.</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 text-center">
		<br>
		<h5>Подтверждение оплаты принято</h5>
		<br>
		<p>Ваша заявка №<?=$transactions['id']?> обрабатывается оператором.</p>
		<p>Время оформления: <?=$transactions['updated']?></p>
		<p class="text-center mt-5">
			<a href="/account/wallet/" class="btn btn-info">Проверить баланс</a>
		</p>
	</div>
</div>

<?

	}

}	

?>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>