<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">
		<!-- <a class="nav-item nav-link active" id="nav-olx-tab" data-toggle="tab" href="#nav-olx" role="tab" aria-controls="nav-olx" aria-selected="true">OLX</a>
		<a class="nav-item nav-link" id="nav-landings-tab" data-toggle="tab" href="#nav-landings" role="tab" aria-controls="nav-landings" aria-selected="false">Лендинги</a> -->
		<a class="nav-item nav-link active" id="nav-shops-tab" data-toggle="tab" href="#nav-shops" role="tab" aria-controls="nav-shops" aria-selected="true">Интернет магазин</a>
		<a class="nav-item nav-link" id="nav-calc-tab" data-toggle="tab" href="#nav-calc" role="tab" aria-controls="nav-calc" aria-selected="false">Калькулятор</a>
	</div>
</nav>

<div class="tab-content">

	<div class="tab-pane fade pt-3" id="nav-olx" role="tabpanel" aria-labelledby="nav-olx-tab">

		<ul class="nav nav-pills mb-3" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="pills-olx1-tab" data-toggle="pill" href="#pills-olx1" role="tab" aria-controls="pills-olx1" aria-selected="true">ПРОГРАММЫ ОЛХ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pills-olx2-tab" data-toggle="pill" href="#pills-olx2" role="tab" aria-controls="pills-olx2" aria-selected="false">СХЕМЫ ЗАРАБОТКА ОЛХ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pills-olx3-tab" data-toggle="pill" href="#pills-olx3" role="tab" aria-controls="pills-olx3" aria-selected="false">ОБУЧЕНИЕ ОЛХ</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade show active" id="pills-olx1" role="tabpanel" aria-labelledby="pills-olx1-tab">
				
				<h3 class="text-center text-uppercase mt-5 mb-4">Автоматизированный софт OLX 2000+</h3>
				<div class="row justify-content-center">
					<div class="col-sm-6">
						<a href="/account/goods/softi_avtomatizatsii/10375">
							<img src="/assets/images/screen_soft_olx1.jpg" class="w-100" alt="screen">
						</a>
					</div>
					<div class="col-sm-10">
						<a href="/account/goods/softi_avtomatizatsii/10375">
							<img src="/assets/images/screen_soft_olx2.jpg" class="w-100" alt="screen">
						</a>
					</div>
				</div>
				<div class="row justify-content-center mt-5">
					<div class="col-sm-3">
					<a href="/account/goods/softi_avtomatizatsii/10375" class="btn btn-warning btn-lg btn-block" style="white-space: normal;">Купить</a>
					</div>
				</div>

			</div>
			<div class="tab-pane fade" id="pills-olx2" role="tabpanel" aria-labelledby="pills-olx2-tab">
				<h3 class="text-center text-uppercase mt-5 mb-4">СХЕМЫ ЗАРАБОТКА ОЛХ</h3>


			</div>
			<div class="tab-pane fade" id="pills-olx3" role="tabpanel" aria-labelledby="pills-olx3-tab">
				<h3 class="text-center text-uppercase mt-5 mb-4">ОБУЧЕНИЕ ОЛХ</h3>


			</div>
		</div>

	</div>

	<div class="tab-pane fade pt-3" id="nav-landings" role="tabpanel" aria-labelledby="nav-landings-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">
			<a href="https://polissia.km.ua/" target="_blank">Лендинг Полесье</a>
		</h3>
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<img src="/assets/images/screen_polissia.png" class="w-100" alt="screen">
			</div>
		</div>
		<p class="text-center pt-3">
			<?if(in_array($user_id, array(2,4,5,340,348,368,496,560,1144,4108,5715,6264,6679))):?>
			<a href="https://polissia.km.ua/u_<?=$user['nickname']?>" id="copyPartnerLink" target="_blank">https://polissia.km.ua/u_<?=$user['nickname']?></a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
			<?else:?>
			<a href="/account/goods/lending_polese/195893" class="btn btn-warning btn-lg" style="white-space: normal;">Получить Лендинг ТМ Полесье</a>
			<?endif;?>
		</p>
	</div>

	<div class="tab-pane fade show active pt-3" id="nav-shops" role="tabpanel" aria-labelledby="nav-shops-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Интернет магазин</h3>
		<div class="row justify-content-center mt-5">
			<div class="col-sm-3">
				<a href="/account/goods/internet_magazin_on/183923" class="btn btn-warning btn-lg btn-block" style="white-space: normal;">Купить</a>
			</div>
		</div>
	</div>

	<div class="tab-pane fade pt-3" id="nav-calc" role="tabpanel" aria-labelledby="nav-calc-tab">
		<h3 class="text-center text-uppercase mt-5 mb-4">Калькулятор</h3>
		
<?

$sql = "SELECT * FROM `marketing` WHERE `dropshipper`='70'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$marketing_default = mysqli_fetch_assoc($query);

$access = ($user['admin'] == 1 or in_array($user_id, [9764, 12946]));

?>

<div class="row justify-content-center">
	<div class="col-sm-6 mb-3">
		<div class="card">
			<h5 class="card-header">Розподілення <button class="btn btn-success btn-sm float-right" onclick="calcMarginDistribution()">Розрахувати</button></h5>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-sm-3 pr-sm-1">
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
					<div class="col-sm-3 pr-sm-1 pl-sm-1">
						<label for="numberMargin">Сума</label>
						<input type="number" step="0.01" id="numberMargin" class="form-control form-control-sm" placeholder="Маржа">
					</div>
					<div class="col-sm-3 pl-sm-1 pr-sm-1">
						<label for="numberQuantity">Кількість</label>
						<input type="number" step="1" id="numberQuantity" class="form-control form-control-sm" placeholder="Кількість">
					</div>
					<div class="col-sm-3 pl-sm-1">
						<label for="bidDropshipper">Відсоток</label>
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
							Новичок/Дропшипер: 
							<b class="float-right" style="color: #F10000;">
								<span id="disDropshipper"><?=$marketing_default['dropshipper']?></span>% - 
								<span id="resultDropshipper">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Наставник: 
							<b class="float-right" style="color: #F19E00;">
								<span id="disManager"><?=$marketing_default['manager']?></span>% - 
								<span id="resultManager">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Супервайзер: 
							<b class="float-right" style="color: #F2F200;">
								<span id="disSupervisor"><?=$marketing_default['supervisor']?></span>% - 
								<span id="resultSupervisor">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Директор: 
							<b class="float-right" style="color: #007A00;">
								<span id="disDirector"><?=$marketing_default['director']?></span>% - 
								<span id="resultDirector">0 грн</span>
							</b>
						</span>
					</li>
					<?if ($access):?>
					<li class="list-group-item p-1">
						<span>
							Операційні витрати:
							<b class="float-right" style="color: #0B0BD9;">
								<span id="disSoft"><?=(
									$marketing_default['roma'] 
									+ $marketing_default['zgenia'] 
									+ $marketing_default['tema']
									+ $marketing_default['dima']
									+ $marketing_default['adminon']
									+ $marketing_default['fond']
									)?></span>% - 
								<span id="resultSoft">0 грн</span>
							</b>
						</span>
					</li>
					<?endif;?>
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
	$marketing_map['dima'][] = $marketing['dima'];
	$marketing_map['adminon'][] = $marketing['adminon'];
	$marketing_map['fond'][] = $marketing['fond'];

}

?>	

	var marketing_dropshipper = [<?=implode(",", $marketing_map['dropshipper'])?>];
	var marketing_manager = [<?=implode(",", $marketing_map['manager'])?>];
	var marketing_supervisor = [<?=implode(",", $marketing_map['supervisor'])?>];
	var marketing_director = [<?=implode(",", $marketing_map['director'])?>];
	<?if ($access):?>
	var marketing_roma = [<?=implode(",", $marketing_map['roma'])?>];
	var marketing_zgenia = [<?=implode(",", $marketing_map['zgenia'])?>];
	var marketing_tema = [<?=implode(",", $marketing_map['tema'])?>];
	var marketing_dima = [<?=implode(",", $marketing_map['dima'])?>];
	var marketing_adminon = [<?=implode(",", $marketing_map['adminon'])?>];
	var marketing_fond = [<?=implode(",", $marketing_map['fond'])?>];
	<?endif;?>

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

			<?if ($access):?>
			var result_roma = period * number_margin * number_quantity * marketing_roma[i] * 0.01;
			var result_zgenia = period * number_margin * number_quantity * marketing_zgenia[i] * 0.01;
			var result_tema = period * number_margin * number_quantity * marketing_tema[i] * 0.01;
			var result_dima = period * number_margin * number_quantity * marketing_dima[i] * 0.01;
			var result_adminon = period * number_margin * number_quantity * marketing_adminon[i] * 0.01;
			var result_fond = period * number_margin * number_quantity * marketing_fond[i] * 0.01;

			$('#disSoft').text(
				marketing_roma[i] 
				+ marketing_zgenia[i] 
				+ marketing_tema[i]
				+ marketing_dima[i]
				+ marketing_adminon[i]
				+ marketing_fond[i]
			);

			$('#resultSoft').text(
				(result_roma + result_zgenia + result_tema + result_dima + result_adminon + result_fond).toFixed(2)
			+ ' грн');

			<?endif;?>

		}

	}

}
</script>

	</div>

</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>