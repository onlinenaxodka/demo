<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$sql = "SELECT * FROM `marketing` WHERE `dropshipper`='70'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$marketing_default = mysqli_fetch_assoc($query);

?>

<div class="row justify-content-center">
	<div class="col-sm-4 mb-3">
		<div class="card">
			<h5 class="card-header">Розподілення <button class="btn btn-success btn-sm float-right" onclick="calcMarginDistribution()">Розрахувати</button></h5>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-sm-3 pr-1">
						<label for="period">Період</label>
						<select id="period" class="form-control form-control-sm">
							<option value="1">День</option>
							<option value="7">Тиждень</option>
							<option value="30">Місяць</option>
							<option value="90">Квартал</option>
							<option value="182">Пів року</option>
							<option value="365">Рік</option>
						</select>
					</div>
					<div class="col-sm-3 pr-1 pl-1">
						<label for="numberMargin">Маржа</label>
						<input type="number" step="0.01" id="numberMargin" class="form-control form-control-sm" placeholder="Маржа">
					</div>
					<div class="col-sm-3 pl-1 pr-1">
						<label for="numberQuantity">Кількість</label>
						<input type="number" step="1" id="numberQuantity" class="form-control form-control-sm" placeholder="Кількість">
					</div>
					<div class="col-sm-3 pl-1">
						<label for="numberQuantity">% Дропа</label>
						<select id="bidDropshipper" class="form-control form-control-sm">
<?

							$sql = "SELECT `dropshipper` FROM `marketing` ORDER BY `id` ASC";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));

							while ($marketing = mysqli_fetch_assoc($query)) {
								
								if ($marketing['dropshipper'] == 70)
									echo '<option value="'.$marketing['dropshipper'].'" selected>'.$marketing['dropshipper'].'%</option>';
								else
									echo '<option value="'.$marketing['dropshipper'].'">'.$marketing['dropshipper'].'%</option>';

							}

?>
						</select>
					</div>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item p-1">
						<span>
							Дропшипер: 
							<b class="float-right">
								<span id="disDropshipper"><?=$marketing_default['dropshipper']?></span>% - 
								<span id="resultDropshipper">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Наставник: 
							<b class="float-right text-secondary">
								<span id="disManager"><?=$marketing_default['manager']?></span>% - 
								<span id="resultManager">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Супервайзер: 
							<b class="float-right" style="color:#c711ca;">
								<span id="disSupervisor"><?=$marketing_default['supervisor']?></span>% - 
								<span id="resultSupervisor">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Директор: 
							<b class="float-right text-warning">
								<span id="disDirector"><?=$marketing_default['director']?></span>% - 
								<span id="resultDirector">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Зарплатний фонд: 
							<b class="float-right text-info">
								<span id="disFond"><?=$marketing_default['fond']?></span>% - 
								<span id="resultFond">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Призовий фонд: 
							<b class="float-right" style="color:#51c2c3;">
								<span id="disDima"><?=$marketing_default['dima']?></span>% - 
								<span id="resultDima">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Адмін ON: 
							<b class="float-right">
								<span id="disAdminON"><?=$marketing_default['adminon']?></span>% - 
								<span id="resultAdminON">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Адмін R: 
							<b class="float-right text-success">
								<span id="disRoma"><?=$marketing_default['roma']?></span>% - 
								<span id="resultRoma">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Адмін E: 
							<b class="float-right text-primary">
								<span id="disZgenia"><?=$marketing_default['zgenia']?></span>% - 
								<span id="resultZgenia">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Адмін A: 
							<b class="float-right text-danger">
								<span id="disTema"><?=$marketing_default['tema']?></span>% - 
								<span id="resultTema">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Адмін O: 
							<b class="float-right" style="color:#4E6225;">
								<span id="disSasha"><?=$marketing_default['sasha']?></span>% - 
								<span id="resultSasha">0 грн</span>
							</b>
						</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function calcMarginDistribution () {

<?

$sql = "SELECT * FROM `marketing` ORDER BY `id` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($marketing = mysqli_fetch_assoc($query)) {

	$marketing_map['dropshipper'][] = $marketing['dropshipper'];
	$marketing_map['manager'][] = $marketing['manager'];
	$marketing_map['supervisor'][] = $marketing['supervisor'];
	$marketing_map['director'][] = $marketing['director'];
	$marketing_map['roma'][] = $marketing['roma'];
	$marketing_map['zgenia'][] = $marketing['zgenia'];
	$marketing_map['tema'][] = $marketing['tema'];
	$marketing_map['sasha'][] = $marketing['sasha'];
	$marketing_map['dima'][] = $marketing['dima'];
	$marketing_map['adminon'][] = $marketing['adminon'];
	$marketing_map['fond'][] = $marketing['fond'];

}

?>	

	var marketing_dropshipper = [<?=implode(",", $marketing_map['dropshipper'])?>];
	var marketing_manager = [<?=implode(",", $marketing_map['manager'])?>];
	var marketing_supervisor = [<?=implode(",", $marketing_map['supervisor'])?>];
	var marketing_director = [<?=implode(",", $marketing_map['director'])?>];
	var marketing_roma = [<?=implode(",", $marketing_map['roma'])?>];
	var marketing_zgenia = [<?=implode(",", $marketing_map['zgenia'])?>];
	var marketing_tema = [<?=implode(",", $marketing_map['tema'])?>];
	var marketing_sasha = [<?=implode(",", $marketing_map['sasha'])?>];
	var marketing_dima = [<?=implode(",", $marketing_map['dima'])?>];
	var marketing_adminon = [<?=implode(",", $marketing_map['adminon'])?>];
	var marketing_fond = [<?=implode(",", $marketing_map['fond'])?>];

	var period = $('#period').val();
	var number_margin = $('#numberMargin').val();
	var number_quantity = $('#numberQuantity').val();
	var bid_dropshipper = $('#bidDropshipper').val();

	for (var i = 0; i < marketing_dropshipper.length; i++) {
		
		if (marketing_dropshipper[i] == bid_dropshipper) {

			var result_dropshipper = period * number_margin * number_quantity * marketing_dropshipper[i] * 0.01;
			$('#disDropshipper').text(marketing_dropshipper[i]);
			$('#resultDropshipper').text(result_dropshipper.toFixed(2)+' грн');

			var result_manager = period * number_margin * number_quantity * marketing_manager[i] * 0.01;
			$('#disManager').text(marketing_manager[i]);
			$('#resultManager').text(result_manager.toFixed(2)+' грн');

			var result_supervisor = period * number_margin * number_quantity * marketing_supervisor[i] * 0.01;
			$('#disSupervisor').text(marketing_supervisor[i]);
			$('#resultSupervisor').text(result_supervisor.toFixed(2)+' грн');

			var result_director = period * number_margin * number_quantity * marketing_director[i] * 0.01;
			$('#disDirector').text(marketing_director[i]);
			$('#resultDirector').text(result_director.toFixed(2)+' грн');

			var result_roma = period * number_margin * number_quantity * marketing_roma[i] * 0.01;
			$('#disRoma').text(marketing_roma[i]);
			$('#resultRoma').text(result_roma.toFixed(2)+' грн');

			var result_zgenia = period * number_margin * number_quantity * marketing_zgenia[i] * 0.01;
			$('#disZgenia').text(marketing_zgenia[i]);
			$('#resultZgenia').text(result_zgenia.toFixed(2)+' грн');

			var result_tema = period * number_margin * number_quantity * marketing_tema[i] * 0.01;
			$('#disTema').text(marketing_tema[i]);
			$('#resultTema').text(result_tema.toFixed(2)+' грн');

			var result_sasha = period * number_margin * number_quantity * marketing_sasha[i] * 0.01;
			$('#disSasha').text(marketing_sasha[i]);
			$('#resultSasha').text(result_sasha.toFixed(2)+' грн');

			var result_dima = period * number_margin * number_quantity * marketing_dima[i] * 0.01;
			$('#disDima').text(marketing_dima[i]);
			$('#resultDima').text(result_dima.toFixed(2)+' грн');

			var result_adminon = period * number_margin * number_quantity * marketing_adminon[i] * 0.01;
			$('#disAdminON').text(marketing_adminon[i]);
			$('#resultAdminON').text(result_adminon.toFixed(2)+' грн');

			var result_fond = period * number_margin * number_quantity * marketing_fond[i] * 0.01;
			$('#disFond').text(marketing_fond[i]);
			$('#resultFond').text(result_fond.toFixed(2)+' грн');

		}

	}

}
</script>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>