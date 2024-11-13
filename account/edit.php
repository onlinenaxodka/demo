<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$progress_bar = 0;

if (!empty($user['name'])) {

	$name_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['surname'])) {
	
	$surname_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['nickname'])) {

	$nickname_success = 'is-valid';
	$progress_bar++;

	if ($user['nickname'] == 'id'.$user['id']) {

		$title_nickname = 'nickname';

	} else {

		$title_nickname = $user['nickname'];
		
	}

}

if ($user['birthday'] != '0000-00-00') {

	$user['birthday'] = date($date_format, strtotime($user['birthday']));
	$birthday_success = 'is-valid';
	$progress_bar++;

} else {

	$user['birthday'] = '';

}

if ($user['sex'] != 0) {

	$sex_success = 'is-valid';
	$progress_bar++;

	if ($user['sex'] == 1) {

		$user_sex_man = 'checked';
		$user_sex_woman = '';

	} elseif ($user['sex'] == 2) {

		$user_sex_man = '';
		$user_sex_woman = 'checked';

	}

} else {

	$user_sex_man = 'checked';
	$user_sex_woman = '';
}

if ($user['country'] != 0) {
	
	$country_success = 'is-valid';
	$progress_bar++;

}

if ($user['region'] != 0) {
	
	$region_success = 'is-valid';
	$progress_bar++;

}

if ($user['city'] != 0) {

	$city_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['lang'])) {

	$lang_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['mail'])) {
	
	$email_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['phone'])) {
	
	$phone_success = 'is-valid';
	$progress_bar++;

}

if (!empty($user['telegram'])) {
	
	$telegram_success = 'is-valid';
	$progress_bar++;

}

/*if (!empty($user['skype'])) {
	
	$skype_success = 'is-valid';
	$progress_bar++;

}*/

if (!empty($user['card'])) {
	
	$card_success = 'is-valid';
	$progress_bar++;

} else {

	$user['card'] = '';
	
}

/*if (!empty($user['site'])) {
	
	$site_success = 'is-valid';
	$progress_bar++;

}*/

$progress_bar_rate = round($progress_bar*100/13);

/*if (isset($_GET['tosuccess'])) {
	echo '<script type="text/javascript">window.location = "?success";</script>';
}*/

if (isset($_GET['success'])) {
	
	$alert_message = '<div class="alert alert-success" role="alert"><strong>Сохранено!</strong> Данные успешно сохранены.</div>';

}

?>

<div class="row">
	<div class="col-sm-12 content-profile">
		<?=$alert_message?>
		<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">Профиль</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pills-places-tab" data-toggle="pill" href="#pills-places" role="tab" aria-controls="pills-places" aria-selected="false">Где я продаю</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pills-projects-tab" data-toggle="pill" href="#pills-projects" role="tab" aria-controls="pills-projects" aria-selected="false">Мои проекты</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab" aria-controls="pills-password" aria-selected="false">Пароль</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" id="pills-social-tab" data-toggle="pill" href="#pills-social" role="tab" aria-controls="pills-social" aria-selected="false">Соц. сети</a>
			</li> -->
		</ul>
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
				<p id="progressProfileTitle" class="progress-title mb-0"><?=$profile_page_title_progress_bar?> <span><?=$progress_bar_rate?></span>%</p>
				<div id="progressProfile" class="progress">
					<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="<?=$progress_bar_rate?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress_bar_rate?>%"></div>
				</div>
				<form method="POST" class="pt-3" id="generalInfo">
					<h3 class="mt-3 mb-3"><?=$profile_page_subtitle[0]?></h3>
					<div class="form-group row">
						<label for="inputPhoto" class="col-sm-3 col-form-label"><?=$profile_page_form_label[16]?>*</label>
						<div class="col-sm-9">
							<div class="row">
								<div class="col-sm-3">
									<div class="slim" data-label="<?=$profile_page_img[0]?>" data-service="/assets/ajax/avatar.php" data-ratio="1:1" data-size="240,240" data-push="true" data-button-edit-label="<?=$profile_page_img[1]?>" data-button-remove-label="<?=$profile_page_img[2]?>" data-button-cancel-label="<?=$profile_page_img[3]?>" data-button-confirm-label="<?=$profile_page_img[4]?>">
<?
										$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
										for ($i = 0; $i < 5; $i++) { 
											$img_name = __DIR__ . '/../data/images/users/user'.$user['id'].'.'.$type_img[$i];
											if (file_exists($img_name)) {
												echo '<img src="/data/images/users/user'.$user['id'].'.'.$type_img[$i].'" alt="User Photo">';
											}
										}
										echo '<input type="file" name="slim[]"/>';
?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputName" class="col-sm-3 col-form-label"><?=$profile_page_form_label[0]?>*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$name_success?>" id="inputName" name="name" value="<?=$user['name']?>" placeholder="<?=$profile_page_form_input_placeholder[0]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputSurname" class="col-sm-3 col-form-label"><?=$profile_page_form_label[1]?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$surname_success?>" id="inputSurname" name="surname" value="<?=$user['surname']?>" placeholder="<?=$profile_page_form_input_placeholder[1]?>">
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputNickname" class="col-sm-3 col-form-label"><?=$profile_page_form_label[2]?>* <i class="material-icons help_outline" data-toggle="tooltip" title="Никнейм используется в Вашей партнерской ссылке вида <?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$title_nickname?>">help_outline</i></label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$nickname_success?>" id="inputNickname" name="nickname" value="<?=$user['nickname']?>" placeholder="<?=$profile_page_form_input_placeholder[2]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputBirthday" class="col-sm-3 col-form-label"><?=$profile_page_form_label[3]?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$birthday_success?>" id="inputBirthday" name="birthday" value="<?=$user['birthday']?>" placeholder="<?=$profile_page_form_input_placeholder[3]?>">
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputCountry" class="col-sm-3 col-form-label"><?=$profile_page_form_label[4]?></label>
						<div class="col-sm-9">
							<div class="input-group">
								<select class="form-control <?=$country_success?>" id="inputCountry" name="country" onchange="getList('inputRegion', 'inputCountry')">
									<option value="none" <?if ($user['country'] == 0) echo 'selected';?> disabled><?=$profile_page_form_input_placeholder[4]?></option>
									<?

										$countries_query = mysqli_query($db, "SELECT * FROM `countries` ORDER BY `name`");

										while ($countries = mysqli_fetch_assoc($countries_query)) {

											if ($countries['id'] == $user['country']) {

												echo '<option value="'.$countries['id'].'" selected>'.$countries['name'].'</option>';

											} else {

												echo '<option value="'.$countries['id'].'">'.$countries['name'].'</option>';

											}

										}

									?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text form-control pr-4 bg-white <?=$country_success?>"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputRegion" class="col-sm-3 col-form-label"><?=$profile_page_form_label[5]?></label>
						<div class="col-sm-9">
							<div id="loader_inputRegion" class="body-loader">
								<img class="ajax-loader" src="/assets/images/ajax_loader_black.gif">
							</div>
							<div class="input-group">
								<select class="form-control <?=$region_success?>" id="inputRegion" name="region" onchange="getList('inputCity', 'inputRegion')">
									<option value="none" <?if ($user['region'] == 0) echo 'selected';?> disabled><?=$profile_page_form_input_placeholder[5]?></option>
									<?

										$user_country_id = $user['country'];

										$regions_query = mysqli_query($db, "SELECT * FROM `regions` WHERE `country_id`='$user_country_id' ORDER BY `name`");

										while ($regions = mysqli_fetch_assoc($regions_query)) {

											if ($regions['id'] == $user['region']) {

												echo '<option value="'.$regions['id'].'" selected>'.$regions['name'].'</option>';

											} else {

												echo '<option value="'.$regions['id'].'">'.$regions['name'].'</option>';

											}

										}

									?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text form-control pr-4 bg-white <?=$region_success?>"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputCity" class="col-sm-3 col-form-label"><?=$profile_page_form_label[6]?></label>
						<div class="col-sm-9">
							<div id="loader_inputCity" class="body-loader">
								<img class="ajax-loader" src="/assets/images/ajax_loader_black.gif">
							</div>
							<div class="input-group">
								<select class="form-control <?=$city_success?>" id="inputCity" name="city">
									<option value="none" <?if ($user['region'] == 0) echo 'selected';?> disabled><?=$profile_page_form_input_placeholder[6]?></option>
									<?

										$user_region_id = $user['region'];

										$cities_query = mysqli_query($db, "SELECT * FROM `cities` WHERE `region_id`='$user_region_id' ORDER BY `name`");

										while ($cities = mysqli_fetch_assoc($cities_query)) {

											if ($cities['id'] == $user['city']) {

												echo '<option value="'.$cities['id'].'" selected>'.$cities['name'].'</option>';

											} else {

												echo '<option value="'.$cities['id'].'">'.$cities['name'].'</option>';

											}

										}

									?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text form-control pr-4 bg-white <?=$city_success?>"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputSexMan" class="col-sm-3 col-form-label"><?=$profile_page_form_label[7]?></label>
						<div class="col-sm-9">
							<div class="form-control border-0 <?=$sex_success?>">
								<div class="custom-control custom-radio custom-control-inline input-sex">
									<input type="radio" id="inputSexMan" name="sex" class="custom-control-input" value="1" <?=$user_sex_man?>>
									<label class="custom-control-label" for="inputSexMan"><?=$profile_page_form_input_placeholder[7]?></label>
								</div>
								<div class="custom-control custom-radio custom-control-inline input-sex">
									<input type="radio" id="inputSexWoman" name="sex" class="custom-control-input" value="2" <?=$user_sex_woman?>>
									<label class="custom-control-label" for="inputSexWoman"><?=$profile_page_form_input_placeholder[8]?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputLang" class="col-sm-3 col-form-label"><?=$profile_page_form_label[8]?></label>
						<div class="col-sm-9">
							<div class="input-group">
								<select class="form-control <?=$lang_success?>" id="inputLang" name="lang">

								<?

									foreach ($lang_files_name as $key => $value) {
										
										if ($user['lang'] == $key) echo '<option value="' . $key . '" selected>' . $value . '</option>';
										else echo '<option value="' . $key . '">' . $value . '</option>';
									}

								?>
									
								</select>
								<div class="input-group-append">
									<span class="input-group-text form-control pr-4 bg-white <?=$lang_success?>"></span>
								</div>
							</div>
						</div>
					</div>
					<h3 class="pt-3 mb-3"><?=$profile_page_subtitle[1]?></h3>
					<div class="form-group row">
						<label for="inputEmail" class="col-sm-3 col-form-label"><?=$profile_page_form_label[9]?>*</label>
						<div class="col-sm-9">
							<input type="email" class="form-control <?=$email_success?>" id="inputEmail" name="email" value="<?=$user['mail']?>" placeholder="<?=$profile_page_form_input_placeholder[9]?>" disabled required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPhone" class="col-sm-3 col-form-label"><?=$profile_page_form_label[10]?>*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$phone_success?>" id="inputPhone" name="phone" value="<?=$user['phone']?>" placeholder="<?=$profile_page_form_input_placeholder[10]?>" data-inputmask="'mask': '+389999999999'" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputTelegram" class="col-sm-3 col-form-label"><?=$profile_page_form_label[17]?>*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$telegram_success?>" id="inputTelegram" name="telegram" value="<?=$user['telegram']?>" placeholder="<?=$profile_page_form_input_placeholder[16]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<!-- <div class="form-group row">
						<label for="inputSkype" class="col-sm-3 col-form-label"><?=$profile_page_form_label[11]?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$skype_success?>" id="inputSkype" name="skype" value="<?=$user['skype']?>" placeholder="<?=$profile_page_form_input_placeholder[11]?>">
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div> -->
					<h3 class="pt-3 mb-3"><?=$profile_page_subtitle[2]?></h3>
					<div class="form-group row">
						<label for="inputCard" class="col-sm-3 col-form-label"><?=$profile_page_form_label[12]?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control <?=$card_success?>" id="inputCard" name="card" value="<?=$user['card']?>" placeholder="<?=$profile_page_form_input_placeholder[12]?>">
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<!-- <h3 class="pt-3 mb-3">Дополнительная информация</h3>
					<div class="form-group row">
						<label for="inputSite" class="col-sm-3 col-form-label">Адрес моего интернет магазина*</label>
						<div class="col-sm-9">
							<input type="url" class="form-control <?=$site_success?>" id="inputSite" name="site" value="<?=$user['site']?>" placeholder="Введите адрес своего интернет магазина" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div> -->
					<div class="form-group row pt-3 mt-3">
						<div class="col-sm-12 text-right">
							<button type="submit" class="btn btn-success"><?=$profile_page_form_btn[0]?></button>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="pills-places" role="tabpanel" aria-labelledby="pills-places-tab">
				<h3 class="pt-3 mb-3">Маркетплейсы, площадки или собственный интернет магазин где я продаю</h3>

<?

$marketplaces = array(
	'Rozetka',
	'Алло',
	'F.ua',
	'Prom.ua',
	'Bigl.ua',
	'Shafa.ua',
	'Crafta.ua',
	'IZI.ua',
	'OLX',
	'ModnaKasta.ua',
	'Zakupka.com',
	'Amazon.com',
	'Aliexpress.com',
	'Lamoda',
	'Alibaba',
	'Ebay',
	'Hotline.ua',
	'Kidstaff.com.ua',
	'Leboutique',
	'klubok.com',
	'NewAuctionukraine',
	'Allbiz',
	'Ibud.ua',
	'Приват маркет',
	'Svitstyle',
	'Gold.ua',
	'Socol.ua',
	'Recci.ua',
	'Skidka.ua'
);

$social_networks = array(
	'Facebook',
	'Instagram',
	'Вконтакте',
	'Одноклассники',
	'Pinterest',
	'YouTube',
	'LinkedIn'
);

$messengers = array(
	'Viber',
	'Telegram',
	'WhatsApp',
	'Facebook Messenger',
	'Skype',
	'Imo'
);

				$sql = "SELECT * FROM `users_shops` WHERE `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				while ($users_shops = mysqli_fetch_assoc($query)) {

?>

				<form method="POST" action="#pills-places-tab">
					<input type="hidden" name="shop_act" value="edit">
					<input type="hidden" name="shop_id" value="<?=$users_shops['id']?>">
					<div class="form-group row">
						<div class="col-sm-3">
							<select class="form-control" name="shop_type" required>
								<optgroup label="Маркетплейсы">

<?

							foreach ($marketplaces as $marketplace) {
								
								echo '<option value="'.$marketplace.'"'.(($users_shops['type']==$marketplace)?' selected':'').'>'.$marketplace.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Социальные сети">

<?

							foreach ($social_networks as $social_network) {
								
								echo '<option value="'.$social_network.'"'.(($users_shops['type']==$social_network)?' selected':'').'>'.$social_network.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Мессенджеры">

<?

							foreach ($messengers as $messenger) {
								
								echo '<option value="'.$messenger.'"'.(($users_shops['type']==$messenger)?' selected':'').'>'.$messenger.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Другое">
									<option value="other"<?=(($users_shops['type']=='other')?' selected':'')?>>Другое</option>
								</optgroup>
							</select>
						</div>
						<div class="col-sm-7">
							<input type="url" class="form-control" name="shop_url" value="<?=$users_shops['url']?>" placeholder="Введите URL адрес" required>
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-warning btn-block">Редактировать</button>
						</div>
					</div>
				</form>
				<form method="POST" action="#pills-places-tab">
					<input type="hidden" name="shop_id" value="<?=$users_shops['id']?>">
					<input type="hidden" name="shop_act" value="delete">
					<div class="form-group row justify-content-end">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-danger btn-block">Удалить</button>
						</div>
					</div>
				</form>

<?

				}

?>

				<form method="POST" action="#pills-places-tab">
					<input type="hidden" name="shop_act" value="add">
					<div class="form-group row">
						<div class="col-sm-3">
							<select class="form-control" name="shop_type" required>
								<option value="none" selected disabled>Выберите место продаж</option>
								<optgroup label="Маркетплейсы">

<?

							foreach ($marketplaces as $marketplace) {
								
								echo '<option value="'.$marketplace.'">'.$marketplace.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Социальные сети">

<?

							foreach ($social_networks as $social_network) {
								
								echo '<option value="'.$social_network.'">'.$social_network.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Мессенджеры">

<?

							foreach ($messengers as $messenger) {
								
								echo '<option value="'.$messenger.'">'.$messenger.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Другое">
									<option value="other">Другое</option>
								</optgroup>
							</select>
						</div>
						<div class="col-sm-7">
							<input type="url" class="form-control" name="shop_url" placeholder="Введите URL адрес" required>
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-success btn-block">Добавить</button>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="pills-projects" role="tabpanel" aria-labelledby="pills-projects-tab">
				<h3 class="pt-3 mb-3">Крипто ресурсы</h3>

<?

$exchanges = array(
	'Binance',
	'Bitfinex',
	'Bybit',
	'CoinList',
	'Insider',
);

$network_projects = array(
	'APL',
	// 'Avon',
	// 'Oriflame',
);

				$sql = "SELECT * FROM `users_projects` WHERE `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				while ($users_projects = mysqli_fetch_assoc($query)) {

?>

				<form method="POST" action="#pills-projects-tab">
					<input type="hidden" name="project_act" value="edit">
					<input type="hidden" name="project_id" value="<?=$users_projects['id']?>">
					<div class="form-group row">
						<div class="col-sm-3">
							<select class="form-control" name="project_type" required>
								<optgroup label="Крипто ресурсы">

<?

							foreach ($exchanges as $exchange) {
								
								echo '<option value="'.$exchange.'"'.(($users_projects['type']==$exchange)?' selected':'').'>'.$exchange.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Сетевые проекты">

<?

							foreach ($network_projects as $network_project) {
								
								echo '<option value="'.$network_project.'"'.(($users_projects['type']==$network_project)?' selected':'').'>'.$network_project.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Другое">
									<option value="other"<?=(($users_projects['type']=='other')?' selected':'')?>>Другое</option>
								</optgroup>
							</select>
						</div>
						<div class="col-sm-7">
							<input type="url" class="form-control" name="project_url" value="<?=$users_projects['url']?>" placeholder="Введите свою реферальную ссылку" required>
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-warning btn-block">Редактировать</button>
						</div>
					</div>
				</form>
				<form method="POST" action="#pills-projects-tab" class="mb-5">
					<input type="hidden" name="project_id" value="<?=$users_projects['id']?>">
					<input type="hidden" name="project_act" value="delete">
					<div class="form-group row justify-content-end">
						<div class="col-sm-2">
							<button type="submit" class="btn btn-danger btn-block">Удалить</button>
						</div>
					</div>
				</form>

<?

				}

?>

				<form method="POST" action="#pills-projects-tab">
					<input type="hidden" name="project_act" value="add">
					<div class="form-group row mb-5">
						<div class="col-sm-3">
							<select class="form-control" name="project_type" required>
								<option value="none" selected disabled>Выберите название свого проекта</option>
								<optgroup label="Биржи">

<?

							foreach ($exchanges as $exchange) {
								
								echo '<option value="'.$exchange.'">'.$exchange.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Сетевые проекты">

<?

							foreach ($network_projects as $network_project) {
								
								echo '<option value="'.$network_project.'">'.$network_project.'</option>';

							}

?>
								</optgroup>
								<optgroup label="Другое">
									<option value="other">Другое</option>
								</optgroup>
							</select>
						</div>
						<div class="col-sm-7">
							<input type="url" class="form-control" name="project_url" placeholder="Введите свою реферальную ссылку" required>
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-success btn-block">Добавить</button>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
				<form method="POST" id="changePassword" action="#pills-password-tab">
					<h3 class="pt-3 mb-3"><?=$profile_page_subtitle[3]?></h3>
					<!-- <div class="collapse" id="collapsePassword"> -->
					<div class="form-group row">
						<label for="inputOldPpassword" class="col-sm-4 col-form-label"><?=$profile_page_form_label[13]?>*</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="inputOldPpassword" name="old_password" placeholder="<?=$profile_page_form_input_placeholder[13]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputNewPpassword" class="col-sm-4 col-form-label"><?=$profile_page_form_label[14]?>*</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="inputNewPpassword" name="new_password" placeholder="<?=$profile_page_form_input_placeholder[14]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputAgainNewPpassword" class="col-sm-4 col-form-label"><?=$profile_page_form_label[15]?>*</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="inputAgainNewPpassword" name="again_new_password" placeholder="<?=$profile_page_form_input_placeholder[15]?>" required>
							<div class="invalid-feedback mt-0"></div>
						</div>
					</div>
					<div class="form-group row pt-3 mt-3">
						<div class="col-sm-12 text-right">
							<button type="submit" class="btn btn-success"><?=$profile_page_form_btn[1]?></button>
						</div>
					</div>
					<!-- </div> -->
				</form>
			</div>
			<!-- <div class="tab-pane fade" id="pills-social" role="tabpanel" aria-labelledby="pills-social-tab">
				<?/*?><p class="text-center">
					<button class="btn btn-link" data-toggle="collapse" href="#collapsePassword" role="button" aria-expanded="false" aria-controls="collapsePassword">Изменить пароль</button>
				</p><?*/?>
				<h3 class="pt-3 mb-3"><?=$profile_page_subtitle[4]?></h3>
<?

$social_network_tag = array();
//$social_network_name = array('twitter', 'vkontakte', 'facebook', 'mailru', 'googleplus', 'odnoklassniki');
//$social_network_name = array('vkontakte', 'facebook', 'mailru', 'google', 'odnoklassniki');
$social_network_name = array('facebook', 'google');
//$social_network_fullname = array('Twitter', 'Вконтакте', 'Facebook', 'Mail.Ru', 'Google', 'Одноклассники');
//$social_network_fullname = array('Вконтакте', 'Facebook', 'Mail.Ru', 'Google', 'Одноклассники');
$social_network_fullname = array('Facebook', 'Google');
//$social_network_tag_name = array('tw', 'vk', 'fb', 'ml', 'gl', 'ok');
//$social_network_tag_name = array('vk', 'fb', 'ml', 'gl', 'ok');
$social_network_tag_name = array('fb', 'gl');
$social_network_img_yes = '';
$social_network_img_no = '';
$connected_social_network = 0;

for ($i=0; $i < count($social_network_name); $i++) { 
    
    $sql = "SELECT `id` FROM `users_{$social_network_tag_name[$i]}` WHERE `user_id`='{$user_id}' LIMIT 1";
    $query = mysqli_query($db, $sql) or die(mysqli_error());

    if (mysqli_num_rows($query) == 1) {

        $social_network_img_yes .= '<div class="item ' . $social_network_tag_name[$i] . ' active">
									<img src="/assets/images/social/' . $social_network_tag_name[$i] . '.png">
									<span>' . $social_network_fullname[$i] . '</span>
									<span class="close">&times;</span>
								</div>';
        $connected_social_network++;

    } else {

        array_push($social_network_tag, $social_network_name[$i]);
        $social_network_img_no .= '<div class="item ' . $social_network_tag_name[$i] . '" data-uloginbutton="' . $social_network_name[$i] . '">
									<img src="/assets/images/social/' . $social_network_tag_name[$i] . '.png">
									<span>' . $social_network_fullname[$i] . '</span>
									<span class="close">&times;</span>
								</div>';

    }

}

if ($connected_social_network == 0) $social_network_img_yes = '';

if ($connected_social_network == count($social_network_name)) $social_network_img_no = '';

?>
				<div class="social-networks text-center">
					<?=$social_network_img_yes?>
					<?if ($connected_social_network < count($social_network_name)):?>
					<?/*
					<div style="display:inline;margin-left:-4px" id="uLogin760eb6a0" data-ulogin="display=buttons;fields=first_name,last_name,email,nickname,sex,bdate,photo,photo_big;optional=phone,city,country;mobilebuttons=0;sort=default;lang=en;providers=<?=implode(',', $social_network_tag)?>;redirect_uri=">
						<?=$social_network_img_no?>
					</div>
					*/?>
					<?endif;?>
				</div>
			
			</div>-->
		</div>
	</div>
</div>

<script type="text/javascript">
	function datepickerCall() {
		$( "#inputBirthday" ).datepicker({
			changeMonth: true,
	      	changeYear: true,
	      	yearRange: "-100:-4",
	      	firstDay: <?=$parametrs_datepicker[0]?>,
	      	monthNamesShort: ['<?=$months_names[0]?>', '<?=$months_names[1]?>', '<?=$months_names[2]?>', '<?=$months_names[3]?>', '<?=$months_names[4]?>', '<?=$months_names[5]?>', '<?=$months_names[6]?>', '<?=$months_names[7]?>', '<?=$months_names[8]?>', '<?=$months_names[9]?>', '<?=$months_names[10]?>', '<?=$months_names[11]?>'],
			dayNamesMin: ['<?=$weeks_names_short[0]?>', '<?=$weeks_names_short[1]?>', '<?=$weeks_names_short[2]?>', '<?=$weeks_names_short[3]?>', '<?=$weeks_names_short[4]?>', '<?=$weeks_names_short[5]?>', '<?=$weeks_names_short[6]?>'],
			dateFormat: "<?=$parametrs_datepicker[1]?>",
			nextText: '<?=$word_next?>',
			prevText: '<?=$word_prew?>'
		}).inputmask("<?=$parametrs_datepicker[2]?>");
	}
</script>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>