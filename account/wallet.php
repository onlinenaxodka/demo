<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate = mysqli_fetch_assoc($query);

?>

<?=$alert_message?>

<?

$sql = "SELECT `id`,`cash` FROM `users` WHERE `id` = 22";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$balance_admin = mysqli_fetch_assoc($query);

?>

<div class="row justify-content-center">
	<div class="col-xl-3 mb-3">
		<div class="card">
			<h5 class="card-header text-center">Призовой фонд:</h5>
			<div class="card-body p-2">
				<h3 class="text-center text-danger font-weight-bold mb-0"><?=($balance_admin['cash'] ?: 0)?> грн.</h3>
			</div>
		</div>
	</div>
</div>

<!-- <a href="/account/" target="_blank">
	<h2 class="text-center text-uppercase font-weight-bold mt-4 mb-4 text-success">
		<img src="https://apprecs.org/gp/images/app-icons/300/59/ua.com.rozetka.shop.jpg" alt="Rozetka" width="40">
		<span>Курс в складчину по ROZETKA</span>
		<img src="https://apprecs.org/gp/images/app-icons/300/59/ua.com.rozetka.shop.jpg" alt="Rozetka" width="40">
	</h2>
</a>
<h4 class="text-center">Реквизиты для оплаты:</h4>
<h4 class="text-center font-weight-bold mt-3 mb-3">4731 2191 1251 1302</h4> -->

<?if (!empty($_SESSION['cart'])):?>
<div class="row justify-content-center mt-3">
	<div class="col-sm-6">
		<a href="/account/cart/" class="btn btn-warning btn-block btn-lg">Завершить заказ в корзине</a>
	</div>
</div>
<?endif;?>

<h3 class="text-center mt-4 mb-3">Мой баланс на кошельке</h3>
<div class="row justify-content-center">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body bg-light">
				<h1 class="text-center"><?=$user['cash']?> грн.</h1>
				<a href="/account/wallet/" style="position: absolute;right: 10px;top: 10px;">
					<i class="material-icons">refresh</i>
				</a>
			</div>
		</div>
		<p class="font-italic text-center mt-1 mb-3">Курс ПриватБанк 1$ - <?=$api_exchange_rate['buy']?> / <?=$api_exchange_rate['sale']?> грн. (Обновлен: <?=date($datetime_format, strtotime($api_exchange_rate['updated']))?>)</p>
		<!-- <p class="text-center mt-4">
			<a href="/account/goods/avtomatizirovannie_voronki_online_naxodka" class="btn btn-dark btn-lg">
				<i class="fa fa-cog"></i> 
				<span>Ремонт турбин</span> 
				<i class="fa fa-cog"></i>
			</a>
		</p> -->
		<p class="text-center">
			<button class="btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseInvestProjects" aria-expanded="false" aria-controls="collapseInvestProjects">
				<i class="fa fa-line-chart"></i> 
				<span>Финансируем вместе</span>
				<i class="fa fa-line-chart"></i>
			</button>
		</p>
	</div>
	<div class="col-sm-12">
		<div class="collapse" id="collapseInvestProjects">
			<div class="card">
				<div class="card-body">
					<div class="row">

<?

					$sql = "SELECT * FROM `invest_project_config` WHERE `status`=1 ORDER BY `created` DESC";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$invest_project_config_count = mysqli_num_rows($query);

					if ($invest_project_config_count > 0) {

						while ($invest_project_config = mysqli_fetch_assoc($query)) {

							$invest_project_config_id = $invest_project_config['id'];

							$sql_ip = "SELECT SUM(`amount`) AS sum_amount FROM `invest_project` WHERE `project_id`='{$invest_project_config_id}'";
							$query_ip  = mysqli_query($db, $sql_ip) or die(mysqli_error($db));
							$invest_project = mysqli_fetch_assoc($query_ip);
							$invest_project_sum_amount = $invest_project['sum_amount'];

							$sql_ip_user = "SELECT `amount` FROM `invest_project` WHERE `project_id`='{$invest_project_config_id}' AND `user_id`='{$user_id}'";
							$query_ip_user = mysqli_query($db, $sql_ip_user) or die(mysqli_error($db));
							$invest_project_user = mysqli_fetch_assoc($query_ip_user);

                            if (!file_exists('../data/images/invest_project/' . $invest_project_config['image'])) {
                                $invest_project_config['image'] = 'no_image.png';
                            }

?>

						<div class="col-lg-4">
							<form method="POST">
								<input type="hidden" name="project_id" value="<?=$invest_project_config['id']?>">
								<div class="card text-center bg-light mx-auto" style="max-width: 325px;">
									<div class="card-body">
										<div class="img-thumbnail w-100 mb-3">
											<?if ($invest_project_config_id == 2 or $invest_project_config_id == 4):?>
											<a href="/account/investor_club/">
												<img src="/data/images/invest_project/<?=$invest_project_config['image']?>" alt="Project image" style="width: 100%; height: 180px;">
											</a>
											<?else:?>
											<img src="/data/images/invest_project/<?=$invest_project_config['image']?>" alt="Project image" style="width: 100%; height: 180px;">
											<?endif;?>
										</div>
										<?if ($invest_project_config_id == 2 or $invest_project_config_id == 4):?>
										<a href="/account/investor_club/">
											<h4 class="card-title font-weight-bold mb-1"><?=$invest_project_config['name']?></h4>
										</a>
										<?else:?>
										<h4 class="card-title font-weight-bold mb-1"><?=$invest_project_config['name']?></h4>
										<?endif;?>
										<p class="mb-2">
											<button type="button" class="btn btn-danger btn-sm" onclick="investProjectDescription(<?=$invest_project_config_id?>)">О проекте</button>
										</p>
										<?/*if ($invest_project_config_id == 1):?>
										<h5 class="text-success font-weight-bold text-uppercase mb-3">до 10%</h5>
										<?elseif($invest_project_config_id == 2):?>
										<h5 class="text-success font-weight-bold text-uppercase mb-3">до 1,5%</h5>
										<?elseif($invest_project_config_id == 3):?>
										<h5 class="text-success font-weight-bold text-uppercase mb-3">до 15%</h5>
										<?elseif($invest_project_config_id == 4):?>
										<h5 class="text-success font-weight-bold text-uppercase mb-3">до 4%</h5>
										<?else:?>
										<h5 class="text-success font-weight-bold text-uppercase mb-3">до 10%</h5>
										<?endif;*/?>
										<div class="card mb-2">
											<div class="card-header p-1">Моя инвестиция:</div>
											<div class="card-body p-1 pt-2 pb-2">
												<h5 class="card-title mb-0 font-weight-bold"><?=intval($invest_project_user['amount'])?> грн.</h5>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-6 pr-1">
												<div class="card">
													<div class="card-header text-white bg-dark p-1">Общий фонд:</div>
													<div class="card-body p-1 pt-2 pb-2">
														<h5 class="card-title text-danger mb-0 font-weight-bold"><?=intval($invest_project_config['amount'])?> грн.</h5>
													</div>
												</div>
											</div>
											<div class="col-sm-6 pl-1">
												<div class="card">
													<div class="card-header bg-warning p-1">Осталось собрать:</div>
													<div class="card-body p-1 pt-2 pb-2">
														<h5 class="card-title text-success mb-0 font-weight-bold"><?=($invest_project_config['amount']-$invest_project_sum_amount)?> грн.</h5>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<input type="number" name="amount" min="1" placeholder="Введите сумму от 1 грн" class="form-control" required>
										</div>
										<div class="form-group mb-0">
											<button type="submit" class="btn btn-success btn-block">Финансировать</button>
										</div>
									</div>
								</div>
							</form>
						</div>

<?

						}

					} else {

?>

						<div class="col-sm-12">
							<p class="text-center mb-0">Проектов для инвестиций еще нет</p>
						</div>

<?

					}

?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-sm-6">
		<div class="row mt-5">
			<div class="col-sm-6 mb-4">
				<a href="/account/add_funds/" class="btn btn-success btn-block">Пополнить</a>
			</div>
			<div class="col-sm-6 mb-4">
				<a href="/account/withdrawal/" class="btn btn-danger btn-block">Вывести</a>
			</div>
		</div>
		<p class="text-center">
			<a href="/account/transactions/" class="btn btn-info btn-block">История операций</a>
		</p>
	</div>
</div>
<p class="text-center text-muted mt-5">Платформа Online Naxodka работает с денежной единицей доллар. Все представления - расчеты цен производиться в $ и конвертируются в грн. по курсу Приват Банк (ПБ). При создании заявки на вывод средств на ваш банковский или расчетный счет, вам будет переведена сумма, указанная в заявке.</p>

<div class="modal fade" id="investProjectDescription">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mb-0">Описание проекта</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>