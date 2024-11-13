<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?=$alert_message?>

<?if (!isset($_GET['project_id']) or empty($_GET['project_id'])):?>

<p class="text-center">
	<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#addInvestProject">Создать проект</button>
</p>

<div class="d-block">

<?

$sql = "SELECT * FROM `invest_project_config` ORDER BY `created` DESC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$invest_project_config_count = mysqli_num_rows($query);

if ($invest_project_config_count > 0) {

	while ($invest_project_config = mysqli_fetch_assoc($query)) {

		$invest_project_config_id = $invest_project_config['id'];

        if (!file_exists('../data/images/invest_project/' . $invest_project_config['image'])) {
            $invest_project_config['image'] = 'no_image.png';
        }
		
?>

	<div class="card d-inline-block" style="max-width: 20rem;">
		<img class="card-img-top" src="/data/images/invest_project/<?=$invest_project_config['image']?>" alt="Project image" style="max-height: 18rem;">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-6">
					<a href="?project_id=<?=$invest_project_config['id']?>" class="btn btn-warning btn-block btn-sm text-center">
						<i class="material-icons" style="display: inherit;">edit</i>
					</a>
				</div>
				<div class="col-sm-6">
					<form method="POST">
						<input type="hidden" name="act" value="delete_project">
						<input type="hidden" name="project_id" value="<?=$invest_project_config_id?>">
						<button type="submit" class="btn btn-danger btn-block btn-sm text-center" onclick="return confirm('Вы действительно хотите удалить этот проект?')">
							<i class="material-icons" style="display: inherit;">delete_forever</i>
						</button>
					</form>
				</div>
			</div>
			<h5 class="card-title mt-4 font-weight-bold text-primary text-center"><?=$invest_project_config['name']?></h5>
			<p class="text-center font-weight-bold mb-0">Пороговая сумма инвестиций:</p>
			<h2 class="text-center text-danger card"><?=intval($invest_project_config['amount'])?> грн.</h2>
			<p class="text-center font-weight-bold mb-0">Собрано уже инвестиций:</p>

<?

		$sql_ip = "SELECT SUM(`amount`) AS sum_amount FROM `invest_project` WHERE `project_id`={$invest_project_config_id}";
		$query_ip = mysqli_query($db, $sql_ip) or die(mysqli_error($db));
		$invest_project = mysqli_fetch_assoc($query_ip);

?>

			<h2 class="text-center text-success card"><?=intval($invest_project['sum_amount'])?> грн.</h2>
			<form method="POST">
				<input type="hidden" name="act" value="accrual_investments">
				<input type="hidden" name="project_id" value="<?=$invest_project_config_id?>">
				<?if ($invest_project_config_id == 2):?>
				<div class="form-group">
					<label for="inputImаgе">Дивиденды для Online Naxodka<?=(($invest_project_config_id==2)?' <b>0.5%</b>':'')?></label>
					<div class="input-group mb-1">
						<div class="input-group-prepend">
							<span class="input-group-text">Сумма</span>
						</div>
						<input type="number" name="amount_on" class="form-control" step="0.01" min="0" placeholder="Введите сумму" value="0" <?=(($invest_project_config_id==1 or $invest_project_config_id==4)?'readonly ':'')?>required>
						<div class="input-group-append">
							<span class="input-group-text">грн.</span>
						</div>
					</div>
				</div>
				<?endif;?>
				<div class="form-group">
					<label for="inputImаgе">Дивиденды для инвесторов<?=(($invest_project_config_id==2)?' <b>1.5%</b>':'')?><?=(($invest_project_config_id==4)?' <b>4%</b>':'')?><?=(($invest_project_config_id==1)?' <b>10%</b>':'')?></label>
					<div class="input-group mb-1">
						<div class="input-group-prepend">
							<span class="input-group-text">Сумма</span>
						</div>
						<input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="Введите сумму" value="0" required>
						<div class="input-group-append">
							<span class="input-group-text">грн.</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success btn-block">Начислить</button>
				</div>
			</form>
		</div>
	</div>

<?

	}

}

?>
	
</div>

<div class="modal fade" id="addInvestProject">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Создать проект</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="act" value="add_project">
					<div class="form-group">
						<label for="inputImаgе" class="font-weight-bold">Изображение</label>
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">crop_original</i></span>
							</div>
							<input type="file" name="imаgе" class="form-control" id="inputImаgе" accept="image/png" required>
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="font-weight-bold">Название проекта</label>
						<input type="text" name="name" class="form-control" id="inputName" placeholder="Название проекта" required>
					</div>
					<div class="form-group">
						<label for="inputDescription" class="font-weight-bold">Описание проекта</label>
						<textarea name="description" rows="7" id="inputDescription" class="form-control project-description summernote" placeholder="Описание..."></textarea>
					</div>
					<div class="form-group">
						<label for="inputImаgе" class="font-weight-bold">Пороговая сумма инвестиций в проект</label>
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text">Сумма</span>
							</div>
							<input type="number" name="amount" class="form-control" step="1" min="0" placeholder="Введите сумму" value="0" required>
							<div class="input-group-append">
								<span class="input-group-text">грн.</span>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success">Создать</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- <div class="modal fade" id="editInvestProject">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Редактировать проект</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div> -->

<?else:?>

<div class="row justify-content-center">
	<div class="col-lg-10 col-xl-8">

<?

			$project_id = (isset($_GET['project_id'])) ? mysqli_real_escape_string($db, $_GET['project_id']) : '';
			$project_id = test_request($project_id);
			$project_id = intval($project_id);
				
			$sql = "SELECT * FROM `invest_project_config` WHERE `id`={$project_id}";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			if (mysqli_num_rows($query) > 0) {

				$invest_project_config = mysqli_fetch_assoc($query);

?>

				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="act" value="edit_project">
					<input type="hidden" name="project_id" value="<?=$invest_project_config['id']?>">
					<div class="form-group">
						<p class="text-center">
							<img src="/data/images/invest_project/<?=$invest_project_config['image']?>" alt="Image" class="img-thumbnail">
						</p>
						<label for="inputImаgе" class="font-weight-bold">Загрузить новое изображение</label>
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">crop_original</i></span>
							</div>
							<input type="file" name="imаgе" class="form-control" id="inputImаgе" accept="image/png">
						</div>
					</div>
					<div class="form-group">
						<label for="inputName" class="font-weight-bold">Название проекта</label>
						<input type="text" name="name" class="form-control" id="inputName" placeholder="Название проекта" value="<?=$invest_project_config['name']?>" required>
					</div>
					<div class="form-group">
						<label for="inputDescription" class="font-weight-bold">Описание проекта</label>
						<textarea name="description" rows="7" id="inputDescription" class="form-control project-description summernote" placeholder="Описание..."><?=$invest_project_config['description']?></textarea>
					</div>
					<div class="form-group">
						<label for="inputImаgе" class="font-weight-bold">Пороговая сумма инвестиций в проект</label>
						<div class="input-group mb-1">
							<div class="input-group-prepend">
								<span class="input-group-text">Сумма</span>
							</div>
							<input type="number" name="amount" class="form-control" step="1" min="0" placeholder="Введите сумму" value="<?=$invest_project_config['amount']?>" required>
							<div class="input-group-append">
								<span class="input-group-text">грн.</span>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success">Сохранить</button>
					</div>
				</form>
				<div class="card">
					<div class="card-header">Инвесторы</div>
					<div class="card-body">
						<div class="table-responsive" style="overflow:auto">
							<table class="table table-sm table-hover">
								<thead class="thead-light">
									<tr>
										<th>Инвестор</th>
<?

										$sql = "SELECT SUM(`amount`) AS sum_amount FROM `invest_project` WHERE `project_id`='{$project_id}'";
										$query = mysqli_query($db, $sql) or die(mysqli_error($db));
										$invest_project = mysqli_fetch_assoc($query);
										$invest_project_sum_amount = $invest_project['sum_amount'];

?>


										<th>Сумма (<?=$invest_project_sum_amount?> грн.)</th>
										<th>Процент, %</th>
									</tr>
								</thead>
								<tbody>

<?

									$sql = "SELECT * FROM `invest_project` WHERE `project_id`={$project_id} ORDER BY `amount` DESC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									if (mysqli_num_rows($query) > 0) {

										while ($invest_project = mysqli_fetch_assoc($query)) {

											$invest_project_user_id = $invest_project['user_id'];

											$sql_ip = "SELECT * FROM `users` WHERE `id`={$invest_project_user_id}";
											$query_ip = mysqli_query($db, $sql_ip) or die(mysqli_error($db));
											$user_ip = mysqli_fetch_assoc($query_ip);

											$rate_of_user = $invest_project['amount'] * 100 / $invest_project_sum_amount;
											$rate_of_user = number_format($rate_of_user, 2, '.', '');
											
?>

									<tr>
										<td>
											<form method="POST" action="/admin/users/">
												<input type="hidden" name="search" value="<?=$user_ip['id']?>">
												<button type="submit" class="btn btn-link btn-sm" data-toggle="tooltip" data-html="true" data-placement="bottom" title="" data-original-title="[<?=$user_ip['id']?>] <?=$user_ip['name']?> <?=$user_ip['surname']?>">[<?=$user_ip['id']?>] <?=$user_ip['name']?> <?=$user_ip['surname']?></button>
											</form>
										</td>
										<td>
											<form method="POST" action="/admin/transactions/">
												<input type="hidden" name="filter_user" value="<?=$user_ip['id']?>">
												<button type="submit" class="btn btn-link btn-sm" data-toggle="tooltip" data-html="true" data-placement="bottom" title="" data-original-title="<?=$invest_project['amount']?> грн."><?=$invest_project['amount']?> грн.</button>
											</form>
										</td>
										<td><?=$rate_of_user?> %</td>
									</tr>

<?

										}

									}

?>

								</tbody>
							</table>
						</div>
					</div>
				</div>

<?

			}

?>

	</div>
</div>

<?endif;?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>