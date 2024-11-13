<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_usd = mysqli_fetch_assoc($query);
$api_exchange_rate_usd['buy'] = number_format($api_exchange_rate_usd['buy'], 2, '.', '');
$api_exchange_rate_usd['sale'] = number_format($api_exchange_rate_usd['sale'], 2, '.', '');


$sql = "SELECT * FROM `api_exchange_rate` WHERE `id`=2";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$api_exchange_rate_eur = mysqli_fetch_assoc($query);
$api_exchange_rate_eur['buy'] = number_format($api_exchange_rate_eur['buy'], 2, '.', '');
$api_exchange_rate_eur['sale'] = number_format($api_exchange_rate_eur['sale'], 2, '.', '');

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

				$edit_goods_id = (isset($_POST['edit_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_goods_id']) : '';
				$edit_goods_id = test_request($edit_goods_id);
				$edit_goods_id = intval($edit_goods_id);

				if ($edit_goods_id > 0) {

					$sql = "SELECT * FROM `goods` WHERE `id`='{$edit_goods_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
					$goods = mysqli_fetch_assoc($query);

					if (mysqli_num_rows($query) > 0) {

						$category = $goods['category'];

						$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$category}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$catalog = mysqli_fetch_assoc($query);

						$catalog_rate = $catalog['rate'];

						$catalog_level_id = $catalog['level_id'];

						function displayCategoriesGoods($db, $level_id) {
						
							$sql = "SELECT * FROM `catalog` WHERE `id`='{$level_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));
							$catalog = mysqli_fetch_assoc($query);

							if (mysqli_num_rows($query) > 0) {

								$_SESSION['category_tmp'][] = $catalog['id'];

								displayCategoriesGoods($db, $catalog['level_id']);

							}

						}

						displayCategoriesGoods($db, $catalog_level_id);

						$category = $_SESSION['category_tmp'];
						if (isset($_SESSION['category_tmp'])) unset($_SESSION['category_tmp']);

						$goods_user_id = $goods['user_id'];

						$sql = "SELECT * FROM `users` WHERE `id`='{$goods_user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$user_provider = mysqli_fetch_assoc($query);

						$user_provider_partner_id = $user_provider['partner_id'];

						$sql_mentor = "SELECT * FROM `users` WHERE `id`='{$user_provider_partner_id}'";
						$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
						$user_mentor = mysqli_fetch_assoc($query_mentor);

						$sql = "SELECT * FROM `goods_description` WHERE `goods_id`='{$edit_goods_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						while ($goods_description = mysqli_fetch_assoc($query))
							$goods_description_view[$goods_description['lang']] = $goods_description['description'];

						$goods['photo'] = json_decode($goods['photo'], true);
						$goods['name'] = json_decode($goods['name'], true);
						$goods['parameters'] = json_decode($goods['parameters'], true);
						$goods['keys'] = json_decode($goods['keys'], true);
						$goods['video'] = json_decode($goods['video'], true);
						$goods['export'] = json_decode($goods['export'], true);
						$goods['groups'] = json_decode($goods['groups'], true);

?>

				<form method="POST" action="#code<?=$goods['id']?>" enctype="multipart/form-data" data-edit="false">
					<input type="hidden" name="edit_goods_id" value="<?=$goods['id']?>">
					<div class="card mb-2">
						<div class="card-body bg-light">
							<?if ($user['admin'] == 1):?>
							<b>Поставщик:</b> [<?=$goods_user_id?>] <?=$user_provider['name']?> <?=$user_provider['surname']?> | <?=$user_provider['phone']?>
							<?else:?>
							<b>Поставщик:</b> <?=$user_provider['name']?> <?=$user_provider['surname']?>
							<?endif;?>
						</div>
					</div>
					<div class="list-group mb-3">
						<div class="list-group-item">
							<div class="row">
								<div class="col-sm-6">
									<p class="font-weight-bold">Идентификатор товара продавца:</p>
									<div class="form-group">
										<input type="text" name="vendor_id" maxlength="255" class="form-control" placeholder="Введите значение" value="<?=$goods['vendor_id']?>">
									</div>
								</div>
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Артикул / Код товара продавца:</p>
									<div class="form-group">
										<input type="text" name="vendor_code" maxlength="255" class="form-control alert-warning" placeholder="Введите значение" value="<?=$goods['vendor_code']?>" required<?=(in_array($goods['user_id'], [407, 1799, 5184, 5856, 7625]) ? ' readonly' : '')?>>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item list-group-item-category">
							<p class="font-weight-bold"><span class="text-danger">*</span> Категория:</p>
							<div class="form-group">

<?

							for ($i = count($category) - 1; $i > 0; $i--) {
								
								$category_tmp = $category[$i];

?>

								<select onchange="categorySelectAddGoods(this)" class="form-control mt-2" required>
									<option value="none" selected disabled>Выбирете категорию</option>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$category_tmp}' ORDER BY `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									while ($catalog = mysqli_fetch_assoc($query)) {

										$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
										$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
										$count_subcategories = mysqli_num_rows($query_subcategories);
										
										if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';
										else $show_option = '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].'</option>';

										if ($catalog['id'] == $category[$i-1]) {

											if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'" selected>'.$catalog['name_ru'].'</option>';
											else $show_option = '<option value="'.$catalog['linkname'].'" selected>'.$catalog['name_ru'].'</option>';

										}

										echo $show_option;

									}

?>
								</select>

<?

							}

?>

								<select onchange="categorySelectAddGoods(this)" class="form-control mt-2" name="category" required>
									<option value="none" selected disabled>Выбирете категорию</option>
<?

									$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_level_id}' ORDER BY `name_ru` ASC";
									$query = mysqli_query($db, $sql) or die(mysqli_error($db));

									while ($catalog = mysqli_fetch_assoc($query)) {

										$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
										$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
										$count_subcategories = mysqli_num_rows($query_subcategories);
										
										if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';
										else $show_option = '<option value="'.$catalog['linkname'].'">'.$catalog['name_ru'].'</option>';

										if ($catalog['linkname'] == $goods['category']) {

											if ($count_subcategories > 0) $show_option = '<option value="'.$catalog['id'].'" selected>'.$catalog['name_ru'].'</option>';
											else $show_option = '<option value="'.$catalog['linkname'].'" selected>'.$catalog['name_ru'].'</option>';

										}

										echo $show_option;

									}

?>
								</select>
							</div>
							<small class="text-danger">*Выбирайте категорию до тех пор, пока она не перестанет выбираться.</small>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Фото:</p>
							<div class="form-group row">

<?
								
								$count_uploaded_photo = count($goods['photo']);

								$max_card_photo = 20;
								for ($mcp=8; $mcp <= 20; $mcp+=4) {
									if ($count_uploaded_photo < $mcp) {
										$max_card_photo = $mcp;
										break;
									}
								}

								for ($i = 0; $i < $max_card_photo; $i++) {
									
									if ($i < $count_uploaded_photo) {

										if (!file_exists('../../data/images/goods/'.$goods['photo']['img'.$i])) $goods['photo']['img'.$i] = 'no_image.png';

										list($goods_photo_w, $goods_photo_h) = getimagesize('../../data/images/goods/'.$goods['photo']['img'.$i]);

										if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
										else $goods_photo_size = 'max-height';

?>

								<div class="col-sm-3 mb-2">
									<div class="card">
										<div class="card-body p-1">
											<input type="hidden" name="photo[]" value="<?=$goods['photo']['img'.$i]?>">
											<label class="goods-images mb-0 float-left <?=$goods_photo_size?>" data-cnt="<?=$i?>">
												<button type="button" class="btn btn-danger btn-close-img p-0" onclick="deletePhotoGoods(this)" data-name="<?=$goods['photo']['img'.$i]?>">
													<i class="material-icons">close</i>
												</button>
												<img src="/data/images/goods/<?=$goods['photo']['img'.$i]?>">
											</label>
										</div>
										<div class="card-footer text-center pt-0 pb-0">
											<small><?if ($i==0) echo 'Основное'; else echo 'Фото'.($i+1);?></small>
										</div>
									</div>
								</div>

<?										

									} else {

?>

								<div class="col-sm-3 mb-2">
									<div class="card">
										<div class="card-body p-1">
											<input type="hidden" name="photo[]" value="<?=(($i==0)?'no_image.png':'')?>">
											<label class="goods-images mb-0 float-left" data-cnt="<?=$i?>">
												<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)" <?//if ($i==0) echo 'required';?>>
												<p class="text-center text-muted d-flex justify-content-center mb-0 h-100">
													<i class="material-icons align-self-center">add_a_photo</i>
												</p>
											</label>
										</div>
										<div class="card-footer text-center pt-0 pb-0">
											<small><?if ($i==0) echo 'Основное'; else echo 'Фото'.($i+1);?></small>
										</div>
									</div>
								</div>

<?

									}

								}

?>

							</div>
							<?if ($count_uploaded_photo < 20):?>
							<p class="text-center mb-0">
								<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputsPhotoAddGoods(this)">
									<i class="material-icons material-icons-plus-input float-left">add</i>
								</button>
							</p>
							<?endif;?>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Название товара:</p>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">UA</span>
									</div>
									<input type="text" name="name_uk" class="form-control" placeholder="Название товара" value="<?=$goods['name']['uk']?>" required>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">RU</span>
									</div>
									<input type="text" name="name_ru" class="form-control" placeholder="Название товара" value="<?=$goods['name']['ru']?>" required>
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Параметры описания: <button type="button" id="parametersGoodsUpdate" class="btn btn-warning btn-sm float-right" onclick="parametersAddGoods('<?=$goods['category']?>')">Подтянуть параметры из шаблона категории</button></p>
							<div class="parameters">
								<div class="list-group">
									
<?

								for ($i=0; $i < count($goods['parameters']['uk']); $i++) {

									$template_with_db_key_uk = array_keys($goods['parameters']['uk']);
									$template_with_db_key_ru = array_keys($goods['parameters']['ru']);

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
													<input type="text" name="param_value_uk[]" class="form-control" placeholder="Значение параметра" value="<?=$goods['parameters']['uk'][$template_with_db_key_uk[$i]]?>" required>
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
													<input type="text" name="param_value_ru[]" class="form-control" placeholder="Значение параметра" value="<?=$goods['parameters']['ru'][$template_with_db_key_ru[$i]]?>" required>
												</div>
											</div>
										</div>
										<?if ($i != 0):?>
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
											<i class="material-icons material-icons-plus-input float-left">add</i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="list-group-item">
							<!-- <script>
								tinymce.init({
									selector:'textarea.goods-description',
									content_style: ".mce-content-body {font-size:16px;font-family:Arial,sans-serif!important;}",
									height: 300,
									language: 'ru',
									language_url : '/assets/js/tinymce_lang_ru.js',
									plugins: [
										"advlist autolink lists link image charmap print preview anchor",
										"searchreplace visualblocks code fullscreen",
										"insertdatetime media table contextmenu paste textcolor colorpicker emoticons imagetools"
									],
									toolbar: "insertfile undo redo | fontselect | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image emoticons charmap forecolor backcolor",
									imagetools_cors_hosts: ['www.tinymce.com', 'codepen.io'],
									content_css: [
										'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
										'//www.tinymce.com/css/codepen.min.css'
									]
								});
							</script> -->
							<p class="font-weight-bold">Описание UA:</p>
							<textarea name="description_uk" rows="7" class="form-control goods-description summernote" placeholder="Описание..."><?=$goods_description_view['uk']?></textarea>
							<br>
							<p class="font-weight-bold">Описание RU:</p>
							<textarea name="description_ru" rows="7" class="form-control goods-description summernote" placeholder="Описание..."><?=(isset($goods_description_view['ru'])?$goods_description_view['ru']:'')?></textarea>
							<script>$(document).ready(function(){$(".summernote").summernote({height: 200});});</script>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Ключи UA:</p>
							<textarea name="keys_uk" rows="7" class="form-control goods-keys" placeholder="Ключи вводить через запятую..."><?=$goods['keys']['uk']?></textarea>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Ключи RU:</p>
							<textarea name="keys_ru" rows="7" class="form-control goods-keys" placeholder="Ключи вводить через запятую..."><?=$goods['keys']['ru']?></textarea>
						</div>
						<div class="list-group-item">
							<p class="font-weight-bold">Видео YouTube, только id ( https://www.youtube.com/watch?v=MPxPh7UrNJ8 ):</p>
							
<?

							for ($i = 0; $i < count(isset($goods['video'])?$goods['video']:array()); $i++) { 
								



?>

							<div class="form-group row">
								<div class="col-sm-11">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">https://www.youtube.com/watch?v=</span>
										</div>
										<input type="text" name="video[]" class="form-control" placeholder="MPxPh7UrNJ8" value="<?=$goods['video']['v'.$i]?>">
									</div>
								</div>
								<div class="col-sm-1">
									<button type="button" class="btn btn-danger btn-sm btn-block pl-1 pr-0" onclick="deleteInputsVideoAddGoods(this)">
										<i class="material-icons float-sm-left">delete_forever</i>
									</button>
								</div>
							</div>

<?

							}

?>

							<p class="text-center mb-0">
								<button type="button" class="btn btn-link pt-0 pb-0" onclick="addInputsVideoAddGoods(this)">
									<i class="material-icons material-icons-plus-input float-left">add</i>
								</button>
							</p>
						</div>
						
						<div class="list-group-item">
							<p class="font-weight-bold">Группа:</p>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="goodsGroupTopEdit" name="group_top" value="1" <?if ($goods['groups']['top'] == 1) echo 'checked';?>>
									<label class="custom-control-label" for="goodsGroupTopEdit">Хит продаж</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="goodsGroupNewEdit" name="group_new" value="1" <?if ($goods['groups']['new'] == 1) echo 'checked';?>>
									<label class="custom-control-label" for="goodsGroupNewEdit">Новинка</label>
								</div>
							</div>
						</div>

						<div class="list-group-item">
							<p class="font-weight-bold"><span class="text-danger">*</span> Количество в наличии:</p>
							<div class="form-group">
								<input type="number" name="availability" min="0" step="1" class="form-control alert-primary" placeholder="0" value="<?=$goods['availability']?>" required>
							</div>
						</div>
						<div class="list-group-item">
							<p class="kurses-pb-goods font-italic text-center"<?if ($goods['currency'] == 1) echo ' style="display: none;"';?>>
								<span class="border border-dark p-1">Курсы ПриватБанк: (<b>USD</b>: <?=$api_exchange_rate_usd['buy']?> / <span class="kurs-usd-sale-pb-goods"><?=$api_exchange_rate_usd['sale']?></span> | <b>EUR</b>: <?=$api_exchange_rate_eur['buy']?> / <span class="kurs-eur-sale-pb-goods"><?=$api_exchange_rate_eur['sale']?></span>) на <b><?=date('d.m.Y H:i', strtotime($api_exchange_rate_usd['updated'].' +2 hours'))?></b></span>
							</p>
							<p class="font-weight-bold"><span class="text-danger">*</span> Цена: </p>
							<div class="form-group">
								<p>Выберите <b>валюту цены</b>:</p>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioEditGoodsUAH" name="currency" class="custom-control-input" value="1" <?if ($goods['currency'] == 1) echo 'checked';?> onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioEditGoodsUAH">UAH</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioEditGoodsUSD" name="currency" class="custom-control-input" value="2" <?if ($goods['currency'] == 2) echo 'checked';?> onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioEditGoodsUSD">USD</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="customRadioEditGoodsEUR" name="currency" class="custom-control-input" value="3" <?if ($goods['currency'] == 3) echo 'checked';?> onchange="selectCurrencyPriceGoods(this)">
									<label class="custom-control-label" for="customRadioEditGoodsEUR">EUR</label>
								</div>
							</div>
							<div class="form-group top-kurs-currency"<?if ($goods['currency'] == 1) echo ' style="display: none;"';?>>
								<p>Укажите пороговый курс выбранной валюты, если он нужен, ниже которого цена товара в гривнях не опустится при колебании курса валют в банках:</p>
								<div class="input-group mb-1">
									<div class="input-group-prepend">
										<?

										if ($goods['currency'] == 1) {
											$placeholder_currency_top_kurs = '1';
											$input_group_text_price = 'грн';
											echo '<span class="input-group-text">UAH</span>';
										} elseif ($goods['currency'] == 2) {
											$placeholder_currency_top_kurs = '28.5';
											$input_group_text_price = '$';
											echo '<span class="input-group-text">USD</span>';
										} elseif ($goods['currency'] == 3) {
											$placeholder_currency_top_kurs = '31.7';
											$input_group_text_price = '€';
											echo '<span class="input-group-text">EUR</span>';
										}

										?>
									</div>
									<input type="number" name="currency_top_kurs" step="0.01" class="form-control" placeholder="<?=$placeholder_currency_top_kurs?>" value="<?=$goods['currency_top_kurs']?>">
								</div>
							</div>
							<div class="form-group input-prices-goods">
								<p>Укажите <b>цену закупки</b> для платформы:</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text"><?=$input_group_text_price?></span>
											</div>

											<?

												$goods_price_purchase_buf = $goods['price_purchase'];

												if ($user_mentor['agent'] == 1 and $goods['price_agent'] > 0)
													$goods['price_purchase'] = $goods['price_agent'];

											?>

											<input type="number" name="price_purchase" step="0.01" class="form-control alert-success" placeholder="Введите цифру" value="<?=$goods['price_purchase']?>" required onkeyup="convertToUAH(this)">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card bg-light border border-success">
											<div class="card-body text-center p-1">
												<h4 class="mb-0">0.00 грн</h4>
											</div>
										</div>
										<small>Сконвертированная цена в гривни для наглядности</small>
									</div>
								</div>
							</div>
							<div class="form-group input-prices-goods">
								<p>Укажите <b>рекомендованную цену</b> для продажи:</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group mb-1">
											<div class="input-group-prepend">
												<span class="input-group-text"><?=$input_group_text_price?></span>
											</div>
											<input type="number" name="price_sale" step="0.01" class="form-control alert-danger" placeholder="Введите цифру" value="<?=$goods['price_sale']?>" onkeyup="convertToUAH(this)" required<?=($goods['price_compare'] > 0 ? ' readonly' : '')?>>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card bg-light border border-danger">
											<div class="card-body text-center p-1">
												<h4 class="mb-0">0.00 грн</h4>
											</div>
										</div>
										<small>Сконвертированная цена в гривни для наглядности</small>
									</div>
								</div>
							</div>
							<div class="form-group">
								<p class="font-weight-bold">Цена конкурента:</p>
								<div class="input-group mb-1">
									<div class="input-group-prepend">
										<span class="input-group-text">грн</span>
									</div>
									<input type="number" name="price_compare" min="0" step="0.01" class="form-control" placeholder="Введите цифру" value="<?=$goods['price_compare']?>">
								</div>
							</div>
							
							<?

							$catalog_rate = $catalog_rate * 100;

							$sql = "SELECT * FROM `marketing` WHERE `dropshipper`='{$catalog_rate}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));
							$marketing = mysqli_fetch_assoc($query);

							$system_rate = (100 - $marketing['dropshipper'] - $marketing['manager'] - $marketing['supervisor'] - $marketing['director']) * 0.01;

							$project_earn = ($goods['price_sale'] - $goods_price_purchase_buf) * $placeholder_currency_top_kurs * $system_rate;
							$project_earn = number_format($project_earn, 2, '.', '');

							//без роялті
							$system_rate_wroyalti = (100 - $marketing['dropshipper'] - $marketing['manager'] - $marketing['supervisor'] - $marketing['director'] - $marketing['roma'] - $marketing['zgenia'] - $marketing['tema'] - $marketing['dima']) * 0.01;

							$project_earn_wroyalti = ($goods['price_sale'] - $goods_price_purchase_buf) * $placeholder_currency_top_kurs * $system_rate_wroyalti;
							$project_earn_wroyalti = number_format($project_earn_wroyalti, 2, '.', '');

							?>

							<?if ($user['admin'] == 1):?>
							<p class="text-center mt-5">Заработок платформы с этого товара:</p>
							<h1 class="text-center mt-3">
								С роялти: <b class="text-danger"><?=$project_earn?> грн</b>
								<br>
								Без роялти: <b class="text-success"><?=$project_earn_wroyalti?> грн</b>
							</h1>
							<p class="text-center mt-3">Это число вышло с формулы:</p>
							<p class="text-center"><b>Маржа</b> - це (<?=$goods['price_sale']?> - <?=$goods_price_purchase_buf?>) * <?=$placeholder_currency_top_kurs?> = <?=(($goods['price_sale'] - $goods_price_purchase_buf) * $placeholder_currency_top_kurs)?> грн.</p>
							<div class="card mt-3 text-center">
								<div class="card-header">С роялти</div>
								<div class="card-body">
									(Маржа) <b>100%</b> - (Дропшипер) <b><?=$marketing['dropshipper']?>%</b> - (Наставник) <b><?=$marketing['manager']?>%</b> - (Супервайзер) <b><?=$marketing['supervisor']?>%</b> - (Директор) <b><?=$marketing['director']?>%</b> = <b><?=($system_rate*100)?>%</b>
								</div>
							</div>
							<div class="card mt-1 text-center">
								<div class="card-header">Без роялти</div>
								<div class="card-body">
									(Маржа) <b>100%</b> - (Дропшипер) <b><?=$marketing['dropshipper']?>%</b> - (Наставник) <b><?=$marketing['manager']?>%</b> - (Супервайзер) <b><?=$marketing['supervisor']?>%</b> - (Директор) <b><?=$marketing['director']?>%</b> - (Роман) <b><?=$marketing['roma']?>%</b> - (Євген) <b><?=$marketing['zgenia']?>%</b> - (Артем) <b><?=$marketing['tema']?>%</b> - (Дмитро) <b><?=$marketing['dima']?>%</b> = <b><?=($system_rate_wroyalti*100)?>%</b>
								</div>
							</div>
							<?endif;?>
						</div>
						<div class="list-group-item">
							<div class="row">
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Товар промодерировано:</p>
									<div class="form-group">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioModerationYes" name="moderation" class="custom-control-input" value="1" <?if ($goods['moderation'] == 1) echo 'checked';?>>
											<label class="custom-control-label" for="customRadioModerationYes">Да</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioModerationNo" name="moderation" class="custom-control-input" value="0" <?if ($goods['moderation'] == 0) echo 'checked';?>>
											<label class="custom-control-label" for="customRadioModerationNo">Нет</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<p class="font-weight-bold"><span class="text-danger">*</span> Статус:</p>
									<div class="form-group">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioEditGoodsStatusOpen" name="status" class="custom-control-input" value="1" <?if ($goods['status'] == 1) echo 'checked';?>>
											<label class="custom-control-label" for="customRadioEditGoodsStatusOpen">Включен</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="customRadioEditGoodsStatusClose" name="status" class="custom-control-input" value="0" <?if ($goods['status'] == 0) echo 'checked';?>>
											<label class="custom-control-label" for="customRadioEditGoodsStatusClose">Выключен</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?//if($user_id == $goods['user_id']):?>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success pl-5 pr-5">Редактировать</button>
					</div>
					<?//endif;?>
				</form>

<?

					}

				}

			}

		}

	}

}

?>