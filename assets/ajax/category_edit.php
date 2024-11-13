<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

/*$breadcrumb_category = array(
	'names' => array('Категории товаров'),
	'id' => array(1)
);

function displayBreadcrumbCategory($db, $lang, $level_id) {
	
	$sql = "SELECT * FROM `catalog` WHERE `id`='{$level_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());

	while ($catalog = mysqli_fetch_assoc($query)) {
			
		displayBreadcrumbCategory($db, $lang, $catalog['level_id']);

		if ($catalog['level_id'] != 0) $_SESSION['breadcrumb'][$catalog['linkname']] = $catalog['name_'.$lang];

	}

}

displayBreadcrumbCategory($db, 'ru', $catalog['id']);

if (!empty($_SESSION['breadcrumb'])) {
	foreach ($_SESSION['breadcrumb'] as $session_breadcrumb_link => $session_breadcrumb_name) {
		$breadcrumb['names'][] = $session_breadcrumb_name;
		$breadcrumb['links'][] = '/admin/goods_catalog/?linkname='.$session_breadcrumb_link;
	}
}*/

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			if ($user['admin'] == 1 or $user['admin'] == 2) {

				$edit_category_id = (isset($_POST['edit_category_id'])) ? mysqli_real_escape_string($db, $_POST['edit_category_id']) : '';
				$edit_category_id = test_request($edit_category_id);
				$edit_category_id = intval($edit_category_id);

				$sql = "SELECT * FROM `catalog` WHERE `id`='{$edit_category_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$catalog = mysqli_fetch_assoc($query);

				if ($catalog['locked'] == 1) {

					$catalog_locked_show = '';
					$catalog_locked_hide = 'checked';

				} else {

					$catalog_locked_show = 'checked';
					$catalog_locked_hide = '';

				}

				if (!file_exists('../images/catalog/'.$catalog['img'])) $catalog['img'] = 'no_image.png';

				list($catalog_width_img, $catalog_height_img) = getimagesize('../images/catalog/'.$catalog['img']);

				if ($catalog_width_img > $catalog_height_img) {
					$catalog_width_img = '100%';
					$catalog_height_img = 'auto';
				} else {
					$catalog_width_img = 'auto';
					$catalog_height_img = '100%';
				}



?>

						<form method="POST" enctype="multipart/form-data">
							<input type="hidden" name="catalog_id" value="<?=$catalog['id']?>">

							<div class="card card-list-categories mb-1">
								<div class="card-body">
									<p class="font-weight-bold"><span class="text-danger">*</span> Изменить надкатегории:</p>
									
									<div class="form-group">
										<select onchange="changeParentCategory(this)" class="form-control" required>
											<option value="none" selected disabled>Выбирете категорию</option>
		<?

											$sql_change_category = "SELECT * FROM `catalog` WHERE `level_id`=0 AND `id` != 2274 ORDER BY `name_ru` ASC";
											$query_change_category = mysqli_query($db, $sql_change_category) or die(mysqli_error());

											while ($catalog_cc = mysqli_fetch_assoc($query_change_category)) {

												if ($catalog_cc['id'] != $catalog['id']) {

													$sql_subsubcategories = "SELECT `id` FROM `catalog` WHERE `level_id`='{$catalog_cc['id']}'";
													$query_subsubcategories = mysqli_query($db, $sql_subsubcategories) or die(mysqli_error($db));
													$count_subsubcategories = mysqli_num_rows($query_subsubcategories);
													
													if ($count_subsubcategories > 0) {
														
														echo '<option value="'.$catalog_cc['id'].'">'.$catalog_cc['name_ru'].'</option>';

													} else {

														$sql_goods_category = "SELECT `id` FROM `goods` WHERE `category`='{$catalog_cc['linkname']}'";
														$query_goods_category = mysqli_query($db, $sql_goods_category) or die(mysqli_error($db));
														$count_goods_category = mysqli_num_rows($query_goods_category);

														if ($count_goods_category > 0) {

															echo '<option value="'.$catalog_cc['id'].'" class="bg-light" disabled>'.$catalog_cc['name_ru'].'</option>';

														} else {

															echo '<option value="'.$catalog_cc['id'].'">'.$catalog_cc['name_ru'].'</option>';

														}

													}

												} else {

													echo '<option value="'.$catalog_cc['id'].'" class="bg-light" disabled>'.$catalog_cc['name_ru'].'</option>';
													
												}

											}

		?>
										</select>
									</div>

									<!-- <small class="text-danger">*Выбирайте категорию до тех пор, пока она не перестанет выбираться.</small> -->
								</div>
							</div>

							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text">UA</span>
								</div>
								<!-- onkeyup="generate_linkname_category(this)" -->
								<input type="text" name="name_uk" class="form-control" placeholder="Имя каталога" value="<?=$catalog['name_uk']?>" required>
							</div>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text">RU</span>
								</div>
								<!-- onkeyup="generate_linkname_category(this)" -->
								<input type="text" name="name_ru" class="form-control" placeholder="Имя каталога" value="<?=$catalog['name_ru']?>" required>
							</div>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="material-icons">link</i></span>
								</div>
								<input type="text" name="linkname" class="form-control" placeholder="linkname" value="<?=$catalog['linkname']?>" readonly required>
							</div>
							<div class="card mb-1">
								<div class="card-body">
									<p class="text-center">
										<img src="/data/images/catalog/<?=$catalog['img']?>?v=<?=strtotime($catalog['updated'])?>" class="card-img-top" style="max-width: 300px;">
									</p>
									<input type="file" name="img" class="form-control" accept="image/png">
								</div>
							</div>
							<div class="list-group mb-1">
								<div class="list-group-item">
									<p class="font-weight-bold mb-0">Шаблон параметров для товаров:</p>
								</div>
<?

								$template_with_db = json_decode($catalog['template'], true);

								for ($i=0; $i < count($template_with_db['uk']); $i++) {

									$template_with_db_key_uk = array_keys($template_with_db['uk']);
									$template_with_db_key_ru = array_keys($template_with_db['ru']);
									
?>

								<div class="list-group-item inputs">
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_name_uk[]" class="form-control" placeholder="Имя параметра" value="<?=$template_with_db_key_uk[$i]?>" required>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">UA</span>
												</div>
												<input type="text" name="param_value_uk[]" class="form-control" placeholder="Значение параметра" value="<?=$template_with_db['uk'][$template_with_db_key_uk[$i]]?>" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_name_ru[]" class="form-control" placeholder="Имя параметра" value="<?=$template_with_db_key_ru[$i]?>" required>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">RU</span>
												</div>
												<input type="text" name="param_value_ru[]" class="form-control" placeholder="Значение параметра" value="<?=$template_with_db['ru'][$template_with_db_key_ru[$i]]?>" required>
											</div>
										</div>
									</div>
									<?if($i > 0):?>
									<p class="text-center mb-0">
										<button type="button" class="btn btn-link btn-sm text-dark" onclick="deleteInputs(this)">
											<i class="material-icons float-left">delete_forever</i>
										</button>
									</p>
									<?endif;?>
								</div>

<?

								}

?>								
								<div class="list-group-item text-center">
									<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputs(this)">
										<i class="material-icons float-left">add</i>
									</button>
								</div>
							</div>
							<div class="list-group-item mb-1">
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text">Prom</span>
											</div>
											<input type="number" name="rate_prom" class="form-control" step="1" min="-100" max="100" placeholder="+/- % от Рек. цены" value="<?=$catalog['rate_prom']?>">
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
											<input type="number" name="rate_rozetka" class="form-control" step="1" min="-100" max="100" placeholder="+/- % от Рек. цены" value="<?=$catalog['rate_rozetka']?>">
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
										<input type="number" name="rate" class="form-control" step="1" min="0" max="92" placeholder="Процент заработка дропшиппера" value="<?=$catalog['rate']*100?>" required>
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
										<input type="number" name="sort" class="form-control" step="1" placeholder="Позиция категории" value="<?=$catalog['sort']?>"  required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" id="checkBlockedEditShow<?=$catalog['id']?>" name="locked" value="0" <?=$catalog_locked_show?>>
										<label class="custom-control-label" for="checkBlockedEditShow<?=$catalog['id']?>">Показать</label>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="custom-control custom-radio">
										<input type="radio" class="custom-control-input" id="checkBlockedEditHide<?=$catalog['id']?>" name="locked" value="1" <?=$catalog_locked_hide?>>
										<label class="custom-control-label" for="checkBlockedEditHide<?=$catalog['id']?>">Спрятать</label>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-success pl-4 pr-4 float-right">Редактировать каталог</button>
						</form>

<?

			}

		}

	}

}

?>