<?php

if (!empty($_POST['goods_code']) or !empty($_GET['goods_search']) or !empty($_GET['goods_search_name'])) {
	if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
	if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
	if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		if((isset($_POST['sql']) or isset($_POST['cancel_sql'])) && $user['admin'] == 1 && $user['id'] == 2) {
			$request_sql = isset($_POST['sql']) ? $_POST['sql'] : '';
			if ($request_sql) {
				$_SESSION['sql'] = $request_sql;
			}

			$cancel_sql = (isset($_POST['cancel_sql'])) ? mysqli_real_escape_string($db, $_POST['cancel_sql']) : '';
			$cancel_sql = test_request($cancel_sql);
			$cancel_sql = intval($cancel_sql);

			if ($cancel_sql) {
				if ($_SESSION['sql']) unset($_SESSION['sql']);
			}

			header('Location: /admin/goods/');
			exit;			
		}

		$goods_code = (isset($_POST['goods_code'])) ? mysqli_real_escape_string($db, $_POST['goods_code']) : '';
		$goods_code = test_request($goods_code);
		$goods_code = intval($goods_code);

		if ($goods_code > 0) {

			header('Location: /admin/goods/?goods_code='.$goods_code);
			exit;

		}

		$delete_goods_id = (isset($_POST['delete_goods_id'])) ? mysqli_real_escape_string($db, $_POST['delete_goods_id']) : '';
		$delete_goods_id = test_request($delete_goods_id);
		$delete_goods_id = intval($delete_goods_id);

		$edit_goods_id = (isset($_POST['edit_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_goods_id']) : '';
		$edit_goods_id = test_request($edit_goods_id);
		$edit_goods_id = intval($edit_goods_id);

		$add_goods_id = (isset($_POST['add_goods_id'])) ? mysqli_real_escape_string($db, $_POST['add_goods_id']) : '';
		$add_goods_id = test_request($add_goods_id);
		$add_goods_id = intval($add_goods_id);

		$copy_goods_id = (isset($_POST['copy_goods_id'])) ? mysqli_real_escape_string($db, $_POST['copy_goods_id']) : '';
		$copy_goods_id = test_request($copy_goods_id);
		$copy_goods_id = intval($copy_goods_id);

		$filter_goods_id = (isset($_POST['filter_goods_id'])) ? mysqli_real_escape_string($db, $_POST['filter_goods_id']) : '';
		$filter_goods_id = test_request($filter_goods_id);
		$filter_goods_id = intval($filter_goods_id);

		$filter_goods_visits = (isset($_POST['filter_goods_visits'])) ? mysqli_real_escape_string($db, $_POST['filter_goods_visits']) : '';
		$filter_goods_visits = test_request($filter_goods_visits);
		$filter_goods_visits = intval($filter_goods_visits);

		$filter_goods_homework = (isset($_POST['filter_goods_homework'])) ? mysqli_real_escape_string($db, $_POST['filter_goods_homework']) : '';
		$filter_goods_homework = test_request($filter_goods_homework);
		$filter_goods_homework = intval($filter_goods_homework);

		$edit_name_goods_id = (isset($_POST['edit_name_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_name_goods_id']) : '';
		$edit_name_goods_id = test_request($edit_name_goods_id);
		$edit_name_goods_id = intval($edit_name_goods_id);

		$edit_availability_goods_id = (isset($_POST['edit_availability_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_availability_goods_id']) : '';
		$edit_availability_goods_id = test_request($edit_availability_goods_id);
		$edit_availability_goods_id = intval($edit_availability_goods_id);

		$edit_price_compare_goods_id = (isset($_POST['edit_price_compare_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_price_compare_goods_id']) : '';
		$edit_price_compare_goods_id = test_request($edit_price_compare_goods_id);
		$edit_price_compare_goods_id = intval($edit_price_compare_goods_id);

		$category = (isset($_POST['category'])) ? mysqli_real_escape_string($db, $_POST['category']) : '';
		$category = test_request($category);

		$vendor_id = (isset($_POST['vendor_id'])) ? mysqli_real_escape_string($db, $_POST['vendor_id']) : '';
		$vendor_id = test_request($vendor_id);

		$vendor_code = (isset($_POST['vendor_code'])) ? mysqli_real_escape_string($db, $_POST['vendor_code']) : '';
		$vendor_code = test_request($vendor_code);

		$provider_url_yml = (isset($_POST['provider_url_yml'])) ? mysqli_real_escape_string($db, $_POST['provider_url_yml']) : '';
		$provider_url_yml = test_request($provider_url_yml);

		if ($copy_goods_id <= 0) {

			if (!empty($vendor_id)) {

				if (strlen(utf8_decode($vendor_id)) > 255) {

					$error = true;
					$error_message .= 'Идентификатор товара продавца слишком большой, максимальное количество символов 255.<br>';

				}

			}

		}

		if ($copy_goods_id <= 0) {

			if (!empty($vendor_code)) {

				if (strlen(utf8_decode($vendor_code)) > 255) {

					$error = true;
					$error_message .= 'Артикул или Код товара продавца слишком большое, максимальное количество символов 255.<br>';

				}

			} else {

				$error = true;
				$error_message .= 'Артикул или Код товара продавца нужно указать обязательно.<br>';

			}

		}

		if (!empty($category)) {

			if (!preg_match("/^[a-z0-9_]{1,120}$/", $category)) {

				$error = true;
				$error_message .= 'Ошибка категории.<br>';

			}

		} else {

			if ($copy_goods_id <= 0) {

				$error = true;
				$error_message .= 'Выбрать категорию нужно обязательно.<br>';

			}

		}

		$photo = array();

		if (!empty($_POST['photo'])) {

			for ($i = 0; $i < count($_POST['photo']); $i++) {

				$photo_tmp = (isset($_POST['photo'][$i])) ? mysqli_real_escape_string($db, $_POST['photo'][$i]) : '';
				$photo_tmp = test_request($photo_tmp);

				if (!empty($photo_tmp)) {

					$photo['img'.$i] = $photo_tmp;

				}

			}

		} else {

			if ($copy_goods_id <= 0) {

				$error = true;
				$error_message .= 'Основное фото обязательное.<br>';

			}

		}

		$photo = json_encode($photo);

		$name_ru = (isset($_POST['name_ru'])) ? mysqli_real_escape_string($db, $_POST['name_ru']) : '';
		$name_ru = test_request($name_ru);

		$name_uk = (isset($_POST['name_uk'])) ? mysqli_real_escape_string($db, $_POST['name_uk']) : '';
		$name_uk = test_request($name_uk);
		//$name_uk = $name_ru;

		if (empty($name_uk) or empty($name_ru)) {

			if ($copy_goods_id <= 0) {

				$error = true;
				$error_message .= 'Оба поля названия товара обязательны.<br>';

			}
			
		}

		$name['uk'] = $name_uk;
		$name['ru'] = $name_ru;

		$name = json_encode($name, JSON_UNESCAPED_UNICODE);
		$name = str_replace("'", "\'", $name);

		$error_message_param = '';
		$template_uk = array();
		$template_ru = array();

		for ($i=0; $i < count(isset($_POST['param_name_ru'])?$_POST['param_name_ru']:array()); $i++) {

			$param_name_ru = (isset($_POST['param_name_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_ru'][$i]) : '';
			$param_name_ru = test_request($param_name_ru);

			$param_value_ru = (isset($_POST['param_value_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_ru'][$i]) : '';
			$param_value_ru = test_request($param_value_ru);

			$param_name_uk = (isset($_POST['param_name_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_uk'][$i]) : '';
			$param_name_uk = test_request($param_name_uk);

			$param_value_uk = (isset($_POST['param_value_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_uk'][$i]) : '';
			$param_value_uk = test_request($param_value_uk);
			//$param_name_uk = $param_name_ru;
			//$param_value_uk = $param_value_ru;

			if (!empty($param_name_uk) and !empty($param_value_uk) and !empty($param_name_ru) and !empty($param_value_ru)) {

				$template_uk[$param_name_uk] = $param_value_uk;
				$template_ru[$param_name_ru] = $param_value_ru;

			} else {

				$error = true;
				$error_message_param = 'Все поля параметров для товара обязательны.<br>';

			}

		}

		$error_message .= $error_message_param;

		$template['uk'] = $template_uk;
		$template['ru'] = $template_ru;

		$template = json_encode($template, JSON_UNESCAPED_UNICODE);
		$template = str_replace("'", "\'", $template);

		/*$description_uk = (isset($_POST['description_uk'])) ? mysqli_real_escape_string($db, $_POST['description_uk']) : '';
		//if (substr_count($description_uk, '\r\n') > 0) $description_uk = addslashes($description_uk);
		$description_uk = str_replace('\r\n', '\\\r\\\n', $description_uk);
		$description_uk = str_replace('\t', '', $description_uk);
		$description_uk = test_request($description_uk);

		$description_ru = (isset($_POST['description_ru'])) ? mysqli_real_escape_string($db, $_POST['description_ru']) : '';
		$description_ru = str_replace('\r\n', '\\\r\\\n', $description_ru);
		$description_ru = str_replace('\t', '', $description_ru);
		$description_ru = test_request($description_ru);*/

		$description_ru = (isset($_POST['description_ru'])) ? mysqli_real_escape_string($db, $_POST['description_ru']) : '';
		$description_uk = (isset($_POST['description_uk'])) ? mysqli_real_escape_string($db, $_POST['description_uk']) : '';
		//$description_uk = $description_ru;

		/*$description['uk'] = $description_uk;
		$description['ru'] = $description_ru;*/

		//$description = json_encode($description, JSON_UNESCAPED_UNICODE);
		//$description = str_replace("'", "\'", $description);

		$keys_uk = (isset($_POST['keys_uk'])) ? mysqli_real_escape_string($db, $_POST['keys_uk']) : '';
		$keys_uk = test_request($keys_uk);

		$keys_ru = (isset($_POST['keys_ru'])) ? mysqli_real_escape_string($db, $_POST['keys_ru']) : '';
		$keys_ru = test_request($keys_ru);

		$keys['uk'] = $keys_uk;
		$keys['ru'] = $keys_ru;

		$keys = json_encode($keys, JSON_UNESCAPED_UNICODE);
		$keys = str_replace("'", "\'", $keys);

		$video_num = array();

		for ($i=0; $i < count(isset($_POST['video'])?$_POST['video']:array()); $i++) {

			$video = (isset($_POST['video'][$i])) ? mysqli_real_escape_string($db, $_POST['video'][$i]) : '';
			$video = test_request($video);

			if (!empty($video)) $video_num['v'.$i] = $video;

		}

		$video = json_encode($video_num);

		$export_links = (isset($_POST['export_links'])) ? mysqli_real_escape_string($db, $_POST['export_links']) : '';
		$export_links = test_request($export_links);
		/*$export['prom_yml'] = $export_links;

		if (!empty($_FILES['export_files']['name'])) {

			if ($_FILES['export_files']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

				$error = true;
				$error_message .= 'Формат файла только xlsx.<br>';

			}

		}*/

		$group_top = (isset($_POST['group_top'])) ? mysqli_real_escape_string($db, $_POST['group_top']) : '';
		$group_top = test_request($group_top);
		$group_top = intval($group_top);

		$group_new = (isset($_POST['group_new'])) ? mysqli_real_escape_string($db, $_POST['group_new']) : '';
		$group_new = test_request($group_new);
		$group_new = intval($group_new);

		$group['top'] = 0;
		$group['new'] = 0;

		if ($group_top > 0) $group['top'] = 1;
		if ($group_new > 0) $group['new'] = 1;

		$group = json_encode($group);

		$availability = (isset($_POST['availability'])) ? mysqli_real_escape_string($db, $_POST['availability']) : '';
		$availability = test_request($availability);
		$availability = intval($availability);

		$currency = (isset($_POST['currency'])) ? mysqli_real_escape_string($db, $_POST['currency']) : '';
		$currency = test_request($currency);
		$currency = intval($currency);

		$currency_top_kurs = (isset($_POST['currency_top_kurs'])) ? mysqli_real_escape_string($db, $_POST['currency_top_kurs']) : '';
		$currency_top_kurs = test_request($currency_top_kurs);
		$currency_top_kurs = floatval($currency_top_kurs);

		$price_purchase = (isset($_POST['price_purchase'])) ? mysqli_real_escape_string($db, $_POST['price_purchase']) : '';
		$price_purchase = test_request($price_purchase);
		$price_purchase = floatval($price_purchase);
		$price_purchase = number_format($price_purchase, 2, '.', '');

		if ($price_purchase < 0) {

			$error = true;
			$error_message .= 'Цена закупки не может быть меньше нуля.<br>';

		}

		$price_sale = (isset($_POST['price_sale'])) ? mysqli_real_escape_string($db, $_POST['price_sale']) : '';
		$price_sale = test_request($price_sale);
		$price_sale = floatval($price_sale);
		$price_sale = number_format($price_sale, 2, '.', '');

		if ($price_sale < 0) {

			$error = true;
			$error_message .= 'Рекомендованная цена не может быть меньше нуля.<br>';
			
		}

		$user_partner_id = $user['partner_id'];

		$sql_mentor = "SELECT * FROM `users` WHERE `id`='{$user_partner_id}'";
		$query_mentor = mysqli_query($db, $sql_mentor) or die(mysqli_error($db));
		$user_mentor = mysqli_fetch_assoc($query_mentor);

		$price_agent = 0;

		if ($price_sale >= $price_purchase) {
			
			if ($user_mentor['agent'] == 1) {

				$price_agent = $price_purchase;

				$price_margine_procent = ($price_sale - $price_purchase) * 0.04;

				if ($price_margine_procent > 0) {

					if ($price_purchase > 0 && $price_purchase <= 500) 
						$price_purchase_procent = $price_purchase * 0.05;
					elseif ($price_purchase > 500 && $price_purchase <= 1000) 
						$price_purchase_procent = $price_purchase * 0.04;
					elseif ($price_purchase > 1000 && $price_purchase <= 5000) 
						$price_purchase_procent = $price_purchase * 0.03;
					elseif ($price_purchase > 5000 && $price_purchase <= 10000) 
						$price_purchase_procent = $price_purchase * 0.02;
					elseif ($price_purchase > 10000) 
						$price_purchase_procent = $price_purchase * 0.01;

					$price_purchase_preview = $price_purchase + $price_purchase_procent;

					if ($price_purchase_procent > $price_margine_procent) 
						$price_purchase_preview = $price_purchase + $price_margine_procent;

					if ($price_purchase_preview > $price_agent and $price_purchase_preview < $price_sale) 
						$price_purchase = number_format($price_purchase_preview, 2, '.', '');

				}

			}

		} else {

			$error = true;
			$error_message .= 'Рекомендованная цена не может быть меньше цены закупки.<br>';

		}

		$price_compare = (isset($_POST['price_compare'])) ? mysqli_real_escape_string($db, $_POST['price_compare']) : '';
		$price_compare = test_request($price_compare);
		$price_compare = floatval($price_compare);
		$price_compare = number_format($price_compare, 2, '.', '');

		if ($price_compare > 0) {
			$price_sale = number_format($price_compare - 1, 2, '.', '');
		}

		$status = (isset($_POST['status'])) ? mysqli_real_escape_string($db, $_POST['status']) : '';
		$status = test_request($status);
		$status = intval($status);

		$moderation = (isset($_POST['moderation'])) ? mysqli_real_escape_string($db, $_POST['moderation']) : '';
		$moderation = test_request($moderation);
		$moderation = intval($moderation);

		$description_ip = (isset($_POST['description_ip'])) ? mysqli_real_escape_string($db, $_POST['description_ip']) : '';
		

		if ($delete_goods_id > 0) {

			/*$sql = "SELECT `id` FROM `school_homework` WHERE `goods_id`='{$delete_goods_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$school_homework_count_goods = mysqli_num_rows($query);*/

			/*$sql = "SELECT `goods` FROM `orders`";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$orders_count_goods = 0;
			while ($orders = mysqli_fetch_assoc($query)) {
				$orders['goods'] = json_decode($orders['goods'], true);
				for ($i=0; $i < count($orders['goods']); $i++) {
					if ($orders['goods'][$i]['id'] == $delete_goods_id) $orders_count_goods = 1;
				}
			}*/

			//if ($school_homework_count_goods == 0 and $orders_count_goods == 0) {
			//if ($school_homework_count_goods == 0) {

				if ($user['admin'] == 1) $sql = "SELECT * FROM `goods` WHERE `id`='{$delete_goods_id}'";
				elseif ($user['admin'] == 2) $sql = "SELECT * FROM `goods` WHERE `id`='{$delete_goods_id}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$goods_count = mysqli_num_rows($query);
				$goods_del = mysqli_fetch_assoc($query);

				if ($goods_count > 0) {

					$goods_del['photo'] = json_decode($goods_del['photo'], true);

					for ($i = 0; $i < count($goods_del['photo']); $i++) {

						if ($goods_del['photo']['img'.$i] != 'no_image.png') {
						
							$filename = __DIR__ . '/../../data/images/goods/' . $goods_del['photo']['img'.$i];

							if (file_exists($filename)) unlink($filename);

							$filename = __DIR__ . '/../../data/images/goods_thumb/' . $goods_del['photo']['img'.$i];

							if (file_exists($filename)) unlink($filename);

						}

					}

					/*$goods_del['export'] = json_decode($goods_del['export'], true);

					if ($goods_del['export']['prom_xlsx']) {

						$filename = __DIR__ . '/../../files/xlsx/' . $goods_del['export']['prom_xlsx'];

						if (file_exists($filename)) unlink($filename);

					}*/

					$sql = "SELECT `id` FROM `goods_description` WHERE `goods_id`='{$delete_goods_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$goods_description_count = mysqli_num_rows($query);

					if ($goods_description_count > 0) {

						$sql = "DELETE FROM `goods_description` WHERE `goods_id`='{$delete_goods_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

				}

				if ($user['admin'] == 1) $sql = "DELETE FROM `goods` WHERE `id`='{$delete_goods_id}'";
				elseif ($user['admin'] == 2) $sql = "DELETE FROM `goods` WHERE `id`='{$delete_goods_id}' AND `user_id`='{$user_id}'";

				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				//header('Location: /admin/goods/');
				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			/*} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> Вы не можете удалить этот товар, так как он используется в домашних заданиях или заказах.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

			}*/

		} elseif ($edit_goods_id > 0) {

			if (!$error) {

				/*if (!empty($_FILES['export_files']['name'])) {

					if ($user['admin'] == 1) $sql = "SELECT * FROM `goods` WHERE `id`='{$edit_goods_id}'";
					elseif ($user['admin'] == 2) $sql = "SELECT * FROM `goods` WHERE `id`='{$edit_goods_id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$goods_edit = mysqli_fetch_assoc($query);

					if ($_FILES['export_files']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

						$uploaddir = __DIR__ . '/../../assets/files/xlsx/';

						$filename = time() . '.' . substr(strrchr($_FILES['export_files']['name'], '.'), 1);

						if (!empty($goods_edit['export']['prom_xlsx'])) $filename = $goods_edit['export']['prom_xlsx'];
										
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['export_files']['tmp_name'], $uploadfile);

						$export['prom_xlsx'] = $filename;
										
					}

				}

				$export = json_encode($export, JSON_UNESCAPED_UNICODE);*/

				if ($user['admin'] == 1) {

					$sql = "UPDATE `goods` SET `vendor_id`='{$vendor_id}',
												`vendor_code`='{$vendor_code}',
												`category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`photo`='{$photo}',
												`video`='{$video}',
												`keys`='{$keys}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`price_compare`='{$price_compare}',
												`moderation`='{$moderation}',
												`status`='{$status}',
												`updated`='{$current_date}' WHERE `id`='{$edit_goods_id}'";

				} elseif ($user['admin'] == 2) {

					$sql = "UPDATE `goods` SET `vendor_id`='{$vendor_id}',
												`vendor_code`='{$vendor_code}',
												`category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`photo`='{$photo}',
												`video`='{$video}',
												`keys`='{$keys}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`price_compare`='{$price_compare}',
												`moderation`='{$moderation}',
												`status`='{$status}',
												`updated`='{$current_date}' WHERE `id`='{$edit_goods_id}' AND `user_id`='{$user_id}'";

				}

				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if ($user['admin'] == 1) {
					$sql = "SELECT `id` FROM `goods` WHERE `id`='{$edit_goods_id}'";
				} elseif ($user['admin'] == 2) {
					$sql = "SELECT `id` FROM `goods` WHERE `id`='{$edit_goods_id}' AND `user_id`='{$user_id}'";
				}
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$goods_count = mysqli_num_rows($query);

				if ($goods_count > 0) {

					$sql = "SELECT `id` FROM `goods_description` WHERE `goods_id`='{$edit_goods_id}' AND `lang`='uk'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$goods_description_uk_count = mysqli_num_rows($query);

					$sql = "SELECT `id` FROM `goods_description` WHERE `goods_id`='{$edit_goods_id}' AND `lang`='ru'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));
					$goods_description_ru_count = mysqli_num_rows($query);

					if (!empty($description_uk)) {

						if ($goods_description_uk_count > 0) {

							$sql = "UPDATE `goods_description` SET `description`='{$description_uk}', `updated`='{$current_date}' WHERE `goods_id`='{$edit_goods_id}' AND `lang`='uk'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						} else {

							$sql = "INSERT INTO `goods_description` SET `goods_id`='{$edit_goods_id}',
																		`description`='{$description_uk}',
																		`lang`='uk',
																		`updated`='{$current_date}',
																		`created`='{$current_date}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						}

					}

					if (!empty($description_ru)) {

						if ($goods_description_ru_count > 0) {

							$sql = "UPDATE `goods_description` SET `description`='{$description_ru}', `updated`='{$current_date}' WHERE `goods_id`='{$edit_goods_id}' AND `lang`='ru'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						} else {

							$sql = "INSERT INTO `goods_description` SET `goods_id`='{$edit_goods_id}',
																		`description`='{$description_ru}',
																		`lang`='ru',
																		`updated`='{$current_date}',
																		`created`='{$current_date}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						}

					}

				}

				//header('Location: /admin/goods/');
				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> '.$error_message.'
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
				
			}

		} elseif ($add_goods_id > 0) {

			if (!$error) {

				/*if (!empty($_FILES['export_files']['name'])) {

					if ($_FILES['export_files']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

						$uploaddir = __DIR__ . '/../../assets/files/xlsx/';
						$filename = time() . '.' . substr(strrchr($_FILES['export_files']['name'], '.'), 1);
										
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['export_files']['tmp_name'], $uploadfile);

						$export['prom_xlsx'] = $filename;
										
					}

				}

				$export = json_encode($export, JSON_UNESCAPED_UNICODE);*/

				$sql = "INSERT INTO `goods` SET `user_id`='{$user_id}',
												`vendor_id`='{$vendor_id}',
												`vendor_code`='{$vendor_code}',
												`category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`photo`='{$photo}',
												`video`='{$video}',
												`keys`='{$keys}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`price_compare`='{$price_compare}',
												`moderation`='{$moderation}',
												`status`='{$status}',
												`updated`='{$current_date}',
												`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				$goods_id = mysqli_insert_id($db);

				if (!empty($description_uk)) {

					$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
															`description`='{$description_uk}',
															`lang`='uk',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				if (!empty($description_ru)) {

					$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
																`description`='{$description_ru}',
																`lang`='ru',
																`updated`='{$current_date}',
																`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				//header('Location: /admin/goods/');
				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> '.$error_message.'
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

			}

		} elseif ($copy_goods_id > 0) {

			if ($user['admin'] == 1) $sql = "SELECT * FROM `goods` WHERE `id`='{$copy_goods_id}'";
			elseif ($user['admin'] == 2) $sql = "SELECT * FROM `goods` WHERE `id`='{$copy_goods_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$copy_goods_count = mysqli_num_rows($query);
			$goods_copy = mysqli_fetch_assoc($query);

			$goods_copy['photo'] = json_decode($goods_copy['photo'], true);

			for ($i = 0; $i < count($goods_copy['photo']); $i++) {

				$name_photo = time() + $i;
				$filename_new = $name_photo . '.' . substr(strrchr($goods_copy['photo']['img'.$i], '.'), 1);
					
				$filename = __DIR__ . '/../../data/images/goods/' . $goods_copy['photo']['img'.$i];
				$filename_copy = __DIR__ . '/../../data/images/goods/' . $filename_new;

				$filename_thumb = __DIR__ . '/../../data/images/goods_thumb/' . $goods_copy['photo']['img'.$i];
				$filename_copy_thumb = __DIR__ . '/../../data/images/goods_thumb/' . $filename_new;

				if (file_exists($filename) and file_exists($filename_thumb)) {
					
					$copy_filename_copy = copy($filename, $filename_copy);
					$copy_filename_copy_thumb = copy($filename_thumb, $filename_copy_thumb);
					
					if ($copy_filename_copy and $copy_filename_copy_thumb) {
						
						$photo_name_arr[] = $filename_new;

					} else {

						$error = true;
						$error_message .= 'Не удалось скопировать этот товар. Попробуйте еще раз.<br>';

					}

				}

			}

			if (!$error) {

				$goods_copy_user_id = $goods_copy['user_id'];

				$name = array();
				$photo = array();

				$vendor_id = $goods_copy['vendor_id'].'-'.rand(0,1000);
				
				$vendor_code = $goods_copy['vendor_code'].'-'.rand(0,1000);

				$category = $goods_copy['category'];

				$goods_copy['name'] = json_decode($goods_copy['name'], true);
				$name['uk'] = $goods_copy['name']['uk'] . ' Копія';
				$name['ru'] = $goods_copy['name']['ru'] . ' Копия';
				$name = json_encode($name, JSON_UNESCAPED_UNICODE);
				$name = str_replace("'", "\'", $name);

				$parameters = $goods_copy['parameters'];
				$parameters = str_replace("'", "\'", $parameters);

				$sql = "SELECT * FROM `goods_description` WHERE `goods_id`='{$copy_goods_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$goods_description_count = mysqli_num_rows($query);

				if ($goods_description_count > 0) {

					while ($goods_description = mysqli_fetch_assoc($query))
						$goods_description_view[$goods_description['lang']] = $goods_description['description'];

				}

				$description_uk = str_replace("'", "\'", $goods_description_view['uk']);
				$description_ru = str_replace("'", "\'", $goods_description_view['ru']);
				
				$keys = str_replace("'", "\'", $goods_copy['keys']);

				for ($i=0; $i < count($photo_name_arr); $i++) $photo['img'.$i] = $photo_name_arr[$i];
				$photo = json_encode($photo);
				
				$video = $goods_copy['video'];
				$export = $goods_copy['export'];
				$groups = $goods_copy['groups'];
				$availability = $goods_copy['availability'];
				$currency = $goods_copy['currency'];
				$currency_top_kurs = $goods_copy['currency_top_kurs'];
				$price_agent = $goods_copy['price_agent'];
				$price_purchase = $goods_copy['price_purchase'];
				$price_sale = $goods_copy['price_sale'];
				$price_compare = $goods_copy['price_compare'];
				$status = 0;

				if ($copy_goods_count > 0) {

					$sql = "INSERT INTO `goods` SET `user_id`='{$goods_copy_user_id}',	
													`vendor_id`='{$vendor_id}',
													`vendor_code`='{$vendor_code}',
													`category`='{$category}',
													`name`='{$name}',
													`parameters`='{$parameters}',
													`photo`='{$photo}',
													`video`='{$video}',
													`keys`='{$keys}',
													`export`='{$export}',
													`groups`='{$groups}',
													`availability`='{$availability}',
													`currency`='{$currency}',
													`currency_top_kurs`='{$currency_top_kurs}',
													`price_agent`='{$price_agent}',
													`price_purchase`='{$price_purchase}',
													`price_sale`='{$price_sale}',
													`price_compare`='{$price_compare}',
													`moderation`='{$moderation}',
													`status`='{$status}',
													`updated`='{$current_date}',
													`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					$goods_id = mysqli_insert_id($db);

					if (!empty($description_uk)) {

						$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
																`description`='{$description_uk}',
																`lang`='uk',
																`updated`='{$current_date}',
																`created`='{$current_date}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

					if (!empty($description_ru)) {

						$sql = "INSERT INTO `goods_description` SET `goods_id`='{$goods_id}',
																	`description`='{$description_ru}',
																	`lang`='ru',
																	`updated`='{$current_date}',
																	`created`='{$current_date}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

				}

				//header('Location: /admin/goods/');
				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> '.$error_message.'
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

			}

		} elseif ($edit_name_goods_id > 0) {

			if ($user['admin'] == 1)
				$sql = "UPDATE `goods` SET `name`='{$name}', `updated`='{$current_date}' WHERE `id`='{$edit_name_goods_id}'";
			elseif ($user['admin'] == 2)
				$sql = "UPDATE `goods` SET `name`='{$name}', `updated`='{$current_date}' WHERE `id`='{$edit_name_goods_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//header('Location: /admin/goods/');
			$http_get_params = [];
			if ($_GET and is_array($_GET)) {
				foreach ($_GET as $get_param_name => $get_param_value) {
					$http_get_params[] = $get_param_name . '=' . ($get_param_name == 'code_edit' ? $edit_name_goods_id : $get_param_value);
				}
			}
			if (!isset($_GET['code_edit'])) {
				$http_get_params[] = 'code_edit=' . $edit_name_goods_id;
			}
			header('Location: /admin/goods/?' . implode('&', $http_get_params) . '#code' . $edit_name_goods_id);
			exit;

		} elseif ($edit_availability_goods_id > 0) {

			if ($user['admin'] == 1)
				$sql = "UPDATE `goods` SET `availability`='{$availability}', `updated`='{$current_date}' WHERE `id`='{$edit_availability_goods_id}'";
			elseif ($user['admin'] == 2)
				$sql = "UPDATE `goods` SET `availability`='{$availability}', `updated`='{$current_date}' WHERE `id`='{$edit_availability_goods_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//header('Location: /admin/goods/');
			header('Location: '.$_SERVER['REQUEST_URI'].'#code'.$edit_availability_goods_id);
			exit;

		} elseif ($edit_price_compare_goods_id > 0 && $price_compare > 0) {
			if ($user['admin'] == 1)
				$sql = "UPDATE `goods` SET `price_sale`='{$price_sale}', `price_compare`='{$price_compare}', `updated`='{$current_date}' WHERE `id`='{$edit_price_compare_goods_id}'";
			elseif ($user['admin'] == 2)
				$sql = "UPDATE `goods` SET `price_sale`='{$price_sale}', `price_compare`='{$price_compare}', `updated`='{$current_date}' WHERE `id`='{$edit_price_compare_goods_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			//header('Location: /admin/goods/');
			header('Location: '.$_SERVER['REQUEST_URI'].'#code'.$edit_price_compare_goods_id);
			exit;

		} elseif (!empty($_POST['goods_check'])) {

			$goods_checked_act = (isset($_POST['goods_checked_act'])) ? mysqli_real_escape_string($db, $_POST['goods_checked_act']) : '';
			$goods_checked_act = test_request($goods_checked_act);

			$goods_checked = $_POST['goods_check'];

			if (count($goods_checked) > 0) {

				for ($i = 0; $i < count($goods_checked); $i++) {

					$goods_checked_id = (isset($goods_checked[$i])) ? mysqli_real_escape_string($db, $goods_checked[$i]) : '';
					$goods_checked_id = test_request($goods_checked_id);
					$goods_checked_id = intval($goods_checked_id);

					if ($goods_checked_id > 0 and !empty($category) and $goods_checked_act == 'change_category') {

						if ($user['admin'] == 1)
							$sql = "UPDATE `goods` SET `category`='{$category}' WHERE `id`='{$goods_checked_id}'";
						elseif ($user['admin'] == 2)
							$sql = "UPDATE `goods` SET `category`='{$category}' WHERE `id`='{$goods_checked_id}' AND `user_id`='{$user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

					if ($goods_checked_id > 0 and $goods_checked_act == 'delete') {

						if ($user['admin'] == 1) $sql = "SELECT * FROM `goods` WHERE `id`='{$goods_checked_id}'";
						elseif ($user['admin'] == 2) $sql = "SELECT * FROM `goods` WHERE `id`='{$goods_checked_id}' AND `user_id`='{$user_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$goods_count = mysqli_num_rows($query);
						$goods_del = mysqli_fetch_assoc($query);

						if ($goods_count > 0) {

							$goods_del['photo'] = json_decode($goods_del['photo'], true);

							for ($j = 0; $j < count($goods_del['photo']); $j++) {

								if ($goods_del['photo']['img'.$j] != 'no_image.png') {
								
									$filename = __DIR__ . '/../../data/images/goods/' . $goods_del['photo']['img'.$j];

									if (file_exists($filename)) unlink($filename);

									$filename = __DIR__ . '/../../data/images/goods_thumb/' . $goods_del['photo']['img'.$j];

									if (file_exists($filename)) unlink($filename);

								}

							}

							$sql = "SELECT `id` FROM `goods_description` WHERE `goods_id`='{$goods_checked_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error($db));
							$goods_description_count = mysqli_num_rows($query);

							if ($goods_description_count > 0) {

								$sql = "DELETE FROM `goods_description` WHERE `goods_id`='{$goods_checked_id}'";
								$query = mysqli_query($db, $sql) or die(mysqli_error($db));

							}

						}

						if ($user['admin'] == 1) $sql = "DELETE FROM `goods` WHERE `id`='{$goods_checked_id}'";
						elseif ($user['admin'] == 2) $sql = "DELETE FROM `goods` WHERE `id`='{$goods_checked_id}' AND `user_id`='{$user_id}'";

						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					}

				}

			}

			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;

		} elseif ($filter_goods_id > 0) {

			if ($category != 'all') {

				if ($category == 'without') {

					$_SESSION['filter_goods_admin'] = 'without';

					if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
					if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

				} else {

					$_SESSION['filter_goods_admin'] = $category;

					if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
					if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

				}

			} else {

				if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
				if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
				if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

			}

			header('Location: /admin/goods/');
			exit;

		} elseif ($filter_goods_visits > 0) {

			if ($filter_goods_visits == 1) {
				
				$_SESSION['filter_goods_visits'] = 1;

				if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);				
				if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

			} else {

				if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
				if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
				if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

			}

			header('Location: /admin/goods/');
			exit;

		} elseif ($filter_goods_homework > 0) {

			if ($filter_goods_homework == 1) {
				
				$_SESSION['filter_goods_homework'] = 1;

				if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
				if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);

			} else {

				if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
				if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
				if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

			}

			header('Location: /admin/goods/');
			exit;

		} elseif (!empty($description_ip)) {

			$sql = "SELECT * FROM `info_providers` WHERE `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$info_providers = mysqli_fetch_assoc($query);
			$count_info_providers = mysqli_num_rows($query);

			if ($count_info_providers == 0) {

				$sql = "INSERT INTO `info_providers` SET `user_id`='{$user_id}',
															`description`='{$description_ip}',
															`updated`='{$current_date}',
															`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			} elseif ($count_info_providers == 1) {

				$sql = "UPDATE `info_providers` SET `description`='{$description_ip}', `updated`='{$current_date}' WHERE `id`='{$info_providers['id']}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

		} elseif (!empty($provider_url_yml) and $user['admin'] == 2) {

			$sql = "INSERT INTO `provider_url_yml` SET `user_id`='{$user_id}',
														`url`='{$provider_url_yml}',
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			$email = $email_for_notify;
			$subject = "Новая ссылка на выгрузку в ".$name_company;
			$message = "<p style='text-align:center'>Партнер [".$user['id']."] ".$user['name']." ".$user['surname'].".</p>
						<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
						<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата: ".date('d.m.Y H:i')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			$alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong>Вы успешно отправили документ выгрузки на модерацию!</strong> В течение 24 часов ваш товар будет добавлен в каталог и доступен пользователям.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

		}

	}

}

if (!empty($_GET['goods_code'])) {

	if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
	if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
	if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);
	
}

?>