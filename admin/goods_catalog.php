<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

/*$table_catalog = array();
$table_goods = array();

$sql = "SELECT `id`, `level_id`, `linkname` FROM `catalog`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($catalog = mysqli_fetch_assoc($query)) {
	
	$table_catalog[] = array($catalog['id'], $catalog['level_id'], $catalog['linkname']);

}

$sql = "SELECT `category`, `status` FROM `goods`";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($goods = mysqli_fetch_assoc($query)) {
	
	$table_goods[] = array($goods['category'], $goods['status']);

}

function countGoodsInCategory($table_catalog, $table_goods, $category_id, $count_goods) {

	$count_subcategories = 0;

	foreach ($table_catalog as $catalog) {
		if ($catalog[1] == $category_id) {
			$count_subcategories++;
		}
	}

	if ($count_subcategories > 0) {

		foreach ($table_catalog as $subcategories) {

			if ($subcategories[1] == $category_id) {

				$count_goods_in_category_pre = countGoodsInCategory($table_catalog, $table_goods, $subcategories[0], $count_goods);

				$count_goods_in_category += $count_goods_in_category_pre;

			}

		}

	} elseif ($count_subcategories == 0) {

		$count_goods_in_category = 0;

		foreach ($table_catalog as $catalog) {
			
			if ($catalog[0] == $category_id) {
				
				foreach ($table_goods as $goods) {

					if ($goods[0] == $catalog[2]) {

						$count_goods_in_category++;

					}

				}

			}

		}

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

}*/

?>

<nav class="breadcrumb">
<?

	for ($i = 0; $i < count($breadcrumb['names']); $i++) {
		if ($i != count($breadcrumb['names'])-1) echo '<a class="breadcrumb-item" href="' . $breadcrumb['links'][$i] . '">' . $breadcrumb['names'][$i] . '</a>';
		else echo '<span class="breadcrumb-item active">' . $breadcrumb['names'][$i] . '</span>';
	}

?>
</nav>

<?=$alert_message?>

<div class="row">
	<div class="col-sm-12">

<?		
		
		$linkname = 'catalog';
		if (!empty($_GET['linkname'])) $linkname = $_GET['linkname'];

		$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());
		$catalog = mysqli_fetch_assoc($query);
		$catalog_id = $catalog['id'];
		$catalog_buffer = $catalog['buffer'];

		$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_id}' ORDER BY `sort` ASC";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_subcategories = mysqli_num_rows($query);

		if ($count_subcategories > 0) {

?>

		<ul class="category-goods mt-4 text-center">

<?

			while ($catalog = mysqli_fetch_assoc($query)) {

				if (!file_exists('../data/images/catalog/'.$catalog['img'])) $catalog['img'] = 'no_image.png';

				list($catalog_width_img, $catalog_height_img) = getimagesize('../data/images/catalog/'.$catalog['img']);

				if ($catalog_width_img > $catalog_height_img) {
					$catalog_width_img = '100%';
					$catalog_height_img = 'auto';
				} else {
					$catalog_width_img = 'auto';
					$catalog_height_img = '100%';
				}

				$sql_subcategories = "SELECT `id` FROM `catalog` WHERE `level_id`='{$catalog['id']}'";
				$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error());
				$count_subcategories_in = mysqli_num_rows($query_subcategories);

				//$count_goods_in_catalog = countGoodsInCategory($db, $catalog['id'], 0);

				if ($count_subcategories_in > 0) {

					$count_goods_in_catalog = $catalog['count_goods_admin'];
					//$count_goods_in_catalog = countGoodsInCategory($table_catalog, $table_goods, $catalog['id'], 0);

				} else {

					$sql_count_goods = "SELECT `id` FROM `goods` WHERE `category`='{$catalog['linkname']}'";
					$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));
					$count_goods_in_catalog = mysqli_num_rows($query_count_goods);

				}

?>

				<li style="position: relative;">
					<span style="position: absolute;left: 20px;top: 15px;">ID: <b><?=$catalog['id']?></b></span>
					<?if ($catalog['locked'] == 1):?>
						<div style="position: absolute;left: 0;right: 0;top: 0;bottom: 50px;margin: auto;background-color: rgba(255,133,144,.5);display: flex;align-items: center;z-index: 3;">
							<i class="fa fa-lock mx-auto d-block" style="font-size: 70px;"></i>
						</div>
					<?endif;?>
					<?if ($count_subcategories_in > 0):?>
						<span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #cccccc;color: #000;border-radius: 15px;padding: 0 10px;z-index: 1;"><?=$count_goods_in_catalog?></span>
					<?else:?>
						<span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #ffc107;color: #000;border-radius: 15px;padding: 0 10px;z-index: 1;"><?=$count_goods_in_catalog?></span>
					<?endif;?>
					<span style="display: inline-block;position: absolute; bottom: 58px; left:0; right: 0; width: 56px;height: 30px;line-height: 32px; background-color: #fff; color: #000; border: 1px solid #000; border-radius: 5px; margin:auto;padding: 0 10px; z-index: 1;"><?=($catalog['rate']*100)?>%</span>
					<a href="/admin/goods_catalog/?linkname=<?=$catalog['linkname']?>">
						<div>
							<img src="/data/images/catalog/<?=$catalog['img']?>?v=<?=strtotime($catalog['updated'])?>" style="width: <?=$catalog_width_img?>; height: <?=$catalog_height_img?>;">
						</div>
						<p class="text-uppercase"><span><?=$catalog['name_ru']?></span></p>
					</a>
					<button type="button" onclick="editCategory(<?=$catalog['id']?>)" class="btn btn-warning btn-sm" style="position: absolute;left: 20px;margin-top: -85px;z-index: 4;">
						<i class="material-icons float-left">edit</i>
					</button>
					<?if ($count_subcategories_in == 0):?>
					<form method="POST" class="float-right" style="position: absolute;right: 20px;margin-top: -85px;z-index: 5;">
						<input type="hidden" name="delete_catalog" value="<?=$catalog['id']?>">
						<?if ($count_goods_in_catalog == 0):?>
							<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы действительно хотите удалить этот каталог?')">
								<i class="material-icons float-left">delete_forever</i>
							</button>
						<?else:?>
							<span class="d-inline-block" tabindex="0" data-toggle="tooltip" data-placement="top" title="ЗАПРЕЩЕНО! Удалите или переместите сначала товары из категории.">
								<button type="button" class="btn btn-danger btn-sm" style="pointer-events: none;" disabled>
									<i class="material-icons float-left">delete_forever</i>
								</button>
							</span>
						<?endif;?>
					</form>
					<?endif;?>
				</li>

<?

			}

?>
			
			<?if($catalog_buffer == 0 or ($catalog_buffer == 1 and $catalog_id == 2274)):?>
			<li>
				<button type="button" class="btn btn-link d-block w-100" style="min-height: 251.59px;" data-toggle="modal" data-target="#addCategory">
					<i class="fa fa-plus-circle" style="font-size: 50px;"></i>
				</button>
			</li>
			<?endif;?>
		</ul>

<?

		} else {

?>

		<p class="text-center">В этой категории нет подкатегорий</p>

<?

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$linkname}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_category = mysqli_num_rows($query);

?>

		<?if ($count_goods_category == 0):?>
		<ul class="category-goods mt-4 text-center">
			<?if($catalog_buffer == 0):?>
			<li>
				<button type="button" class="btn btn-link d-block w-100" style="min-height: 251.59px;" data-toggle="modal" data-target="#addCategory">
					<i class="fa fa-plus-circle" style="font-size: 50px;"></i>
				</button>
			</li>
			<?endif;?>
		</ul>
		<?else:?>
		<p class="text-center text-danger">Но в этой категории есть товары <?=$count_goods_category?> шт., перенесите сначала товары в другую категорию чтобы создать здесь подкатегорию</p>
		<?endif;?>

<?

		}

?>

	</div>
</div>

<?

$sort_category_last = $count_subcategories + 1;

?>

<div class="modal fade" id="addCategory">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить категорию</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
						<form method="POST" enctype="multipart/form-data">
							<input type="hidden" name="level_id" value="<?=$catalog_id?>">
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text">UA</span>
								</div>
								<input type="text" name="name_uk" class="form-control" placeholder="Имя каталога" onkeyup="generate_linkname_category(this)" required>
							</div>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text">RU</span>
								</div>
								<input type="text" name="name_ru" class="form-control" placeholder="Имя каталога" onkeyup="generate_linkname_category(this)" required>
							</div>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="material-icons">link</i></span>
								</div>
								<input type="text" name="linkname" class="form-control" placeholder="linkname" readonly required>
							</div>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="material-icons">crop_original</i></span>
								</div>
								<input type="file" name="img" class="form-control" accept="image/png" required>
							</div>
							<div class="list-group mb-1">
								<div class="list-group-item">
									<p class="font-weight-bold mb-0">Шаблон параметров для товаров:</p>
								</div>
								<div class="list-group-item inputs">
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_name_uk[]" class="form-control" placeholder="Имя параметра" value="Країна виробник" required>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_value_uk[]" class="form-control" placeholder="Значение параметра" value="Україна" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_name_ru[]" class="form-control" placeholder="Имя параметра" value="Страна производитель" required>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_value_ru[]" class="form-control" placeholder="Значение параметра" value="Украина" required>
											</div>
										</div>
									</div>
								</div>
								<div class="list-group-item inputs">
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_name_uk[]" class="form-control" placeholder="Имя параметра" value="Бренд" required="">
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_value_uk[]" class="form-control" placeholder="Значение параметра" value="-" required="">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_name_ru[]" class="form-control" placeholder="Имя параметра" value="Бренд" required="">
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_value_ru[]" class="form-control" placeholder="Значение параметра" value="-" required="">
											</div>
										</div>
									</div>
									<p class="text-center mb-0"><button type="button" class="btn btn-link btn-sm text-dark" onclick="deleteInputs(this)"><i class="material-icons float-left">delete_forever</i></button></p>
								</div>
								<div class="list-group-item text-center">
									<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputs(this)">
										<i class="material-icons float-left">add</i>
									</button>
								</div>
							</div>
							<!-- <div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="material-icons">backup</i></span>
								</div>
								<input type="text" name="prom" class="form-control" placeholder="Cсылка для выгрузки на Prom.ua" required>
							</div> -->
							<div class="list-group-item mb-1">
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text">Prom</span>
											</div>
											<input type="number" name="rate_prom" class="form-control" step="1" min="-100" max="100" placeholder="+/- % от Рек. цены" value="0">
											<div class="input-group-append">
												<span class="input-group-text">%</span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text">Rozetka</span>
											</div>
											<input type="number" name="rate_rozetka" class="form-control" step="1" min="-100" max="100" placeholder="+/- % от Рек. цены" value="0">
											<div class="input-group-append">
												<span class="input-group-text">%</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="input-group mb-1">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="material-icons">equalizer</i></span>
										</div>
										<input type="number" name="rate" class="form-control" step="1" min="0" max="92" placeholder="Процент заработка дропшиппера" value="50" required>
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="input-group mb-1">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="material-icons">sort</i></span>
										</div>
										<input type="number" name="sort" class="form-control" step="1" placeholder="Позиция категории" value="<?=$sort_category_last?>" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" id="checkBlockedAddShow<?=$catalog['id']?>" name="locked" value="0" checked>
										<label class="custom-control-label" for="checkBlockedAddShow<?=$catalog['id']?>">Показать</label>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" id="checkBlockedAddHide<?=$catalog['id']?>" name="locked" value="1">
										<label class="custom-control-label" for="checkBlockedAddHide<?=$catalog['id']?>">Спрятать</label>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-success pl-4 pr-4 float-right">Добавить подраздел</button>
						</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editCategory">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Редактировать категорию</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>