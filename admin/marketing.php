<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> Вы допустили ошыбку, исправьте!
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

if (isset($_GET['error'])) echo $alert_message;

$group_rate = [];
$sql = "SELECT `rate` FROM `catalog` GROUP BY `rate`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
while ($catalog = mysqli_fetch_assoc($query)) {
	$group_rate[] = $catalog['rate'] * 100;
}

$sql = "SELECT * FROM `marketing` WHERE `dropshipper` IN (" . implode(',', $group_rate) . ") ORDER BY `id` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

?>

<style type="text/css">

/*table thead, table tbody {
	display: block;
}

table thead {
	padding-right: 134px;
}

table tbody {
	overflow: auto;
	height: 680px;
}

table thead th {
	width: 158px;
}*/

thead th {
  position: -webkit-sticky;
  position: sticky;
  top: -1px;
  z-index: 1;
}

</style>

<!-- <div class="table-responsive"> -->
<div>
	<table class="table table-sm table-hover table-bordered text-center" id="marketingSetting">
		<thead class="thead-light">
			<tr>
				<!-- <th>ID</th> -->
				<th>Дропшиппер</th>
				<th>Наставник</th>
				<th>Супервайзер</th>
				<th>Директор</th>
				<th>Админ R</th>
				<th>Админ E</th>
				<th>Админ A</th>
				<th>Админ O</th>
				<th>Призовой фонд</th>
				<th>Админ ON</th>
				<th>Зарплатный фонд</th>
				<th>Сумма</th>
			</tr>
		</thead>
		<tbody>
			
<?

		$n = 0;

		while ($marketing = mysqli_fetch_assoc($query)) {
			
			$n++;

			$amount = $marketing['dropshipper'] + $marketing['manager'] + $marketing['supervisor'] + $marketing['director'] + $marketing['roma'] + $marketing['zgenia'] + $marketing['tema'] + $marketing['sasha'] + $marketing['dima'] + $marketing['adminon'] + $marketing['fond'];

?>
			<form method="POST">
				<input type="hidden" name="act" value="edit">
				<input type="hidden" name="id" value="<?=$marketing['id']?>">
				<tr class="table-<?=(($amount==100)?'default':'danger')?>">
					<!-- <th><?=$marketing['id']?></th> -->
					<td>
						<div class="input-group">
							<input type="number" name="dropshipper" class="form-control" placeholder="Дропшиппер" value="<?=$marketing['dropshipper']?>" readonly>
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="manager" class="form-control" placeholder="Наставник" value="<?=$marketing['manager']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="supervisor" class="form-control" placeholder="Супервайзер" value="<?=$marketing['supervisor']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="director" class="form-control" placeholder="Директор" value="<?=$marketing['director']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="roma" class="form-control" placeholder="Админ R" value="<?=$marketing['roma']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="zgenia" class="form-control" placeholder="Админ E" value="<?=$marketing['zgenia']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="tema" class="form-control" placeholder="Админ A" value="<?=$marketing['tema']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="sasha" class="form-control" placeholder="Админ O" value="<?=$marketing['sasha']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="dima" class="form-control" placeholder="Призовой фонд" value="<?=$marketing['dima']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="adminon" class="form-control" placeholder="Админ ON" value="<?=$marketing['adminon']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="number" name="fond" class="form-control" placeholder="Фонд" value="<?=$marketing['fond']?>">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</td>
					<th class="text-<?=(($amount==100)?'success':'danger')?> text-amount align-middle" style="width: 120px"><?=$amount?>%</th>
					<td>
						<button type="submit" class="btn btn-success">Сохранить</button>
					</td>
				</tr>
			</form>

<?

		}

?>

		</tbody>
	</table>
</div>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>