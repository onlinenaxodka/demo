<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

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

		$edit_availability_goods_id = (isset($_POST['edit_availability_goods_id'])) ? mysqli_real_escape_string($db, $_POST['edit_availability_goods_id']) : '';
		$edit_availability_goods_id = test_request($edit_availability_goods_id);
		$edit_availability_goods_id = intval($edit_availability_goods_id);

		$category = (isset($_POST['category'])) ? mysqli_real_escape_string($db, $_POST['category']) : '';
		$category = test_request($category);

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

		$name_uk = (isset($_POST['name_uk'])) ? mysqli_real_escape_string($db, $_POST['name_uk']) : '';
		$name_uk = test_request($name_uk);

		$name_ru = (isset($_POST['name_ru'])) ? mysqli_real_escape_string($db, $_POST['name_ru']) : '';
		$name_ru = test_request($name_ru);

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

		for ($i=0; $i < count($_POST['param_name_uk']); $i++) {

			$param_name_uk = (isset($_POST['param_name_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_uk'][$i]) : '';
			$param_name_uk = test_request($param_name_uk);

			$param_value_uk = (isset($_POST['param_value_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_uk'][$i]) : '';
			$param_value_uk = test_request($param_value_uk);

			$param_name_ru = (isset($_POST['param_name_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_ru'][$i]) : '';
			$param_name_ru = test_request($param_name_ru);

			$param_value_ru = (isset($_POST['param_value_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_ru'][$i]) : '';
			$param_value_ru = test_request($param_value_ru);

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

		$description_uk = (isset($_POST['description_uk'])) ? mysqli_real_escape_string($db, $_POST['description_uk']) : '';
		//if (substr_count($description_uk, '\r\n') > 0) $description_uk = addslashes($description_uk);
		$description_uk = str_replace('\r\n', '\\\r\\\n', $description_uk);
		$description_uk = str_replace('\t', '', $description_uk);
		$description_uk = test_request($description_uk);

		$description_ru = (isset($_POST['description_ru'])) ? mysqli_real_escape_string($db, $_POST['description_ru']) : '';
		$description_ru = str_replace('\r\n', '\\\r\\\n', $description_ru);
		$description_ru = str_replace('\t', '', $description_ru);
		$description_ru = test_request($description_ru);

		$description['uk'] = $description_uk;
		$description['ru'] = $description_ru;

		$description = json_encode($description, JSON_UNESCAPED_UNICODE);
		$description = str_replace("'", "\'", $description);

		for ($i=0; $i < count($_POST['video']); $i++) {

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

		$status = (isset($_POST['status'])) ? mysqli_real_escape_string($db, $_POST['status']) : '';
		$status = test_request($status);
		$status = intval($status);
		

		if ($delete_goods_id > 0) {

			$sql = "SELECT `id` FROM `school_homework` WHERE `goods_id`='{$delete_goods_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$school_homework_count_goods = mysqli_num_rows($query);

			$sql = "SELECT `goods` FROM `orders`";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$orders_count_goods = 0;
			while ($orders = mysqli_fetch_assoc($query)) {
				$orders['goods'] = json_decode($orders['goods'], true);
				for ($i=0; $i < count($orders['goods']); $i++) {
					if ($orders['goods'][$i]['id'] == $delete_goods_id) $orders_count_goods = 1;
				}
			}

			if ($school_homework_count_goods == 0 and $orders_count_goods == 0) {

				if ($user['admin'] == 1) $sql = "SELECT * FROM `goods` WHERE `id`='{$delete_goods_id}'";
				elseif ($user['admin'] == 2) $sql = "SELECT * FROM `goods` WHERE `id`='{$delete_goods_id}' AND `user_id`='{$user_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$goods_del = mysqli_fetch_assoc($query);

				$goods_del['photo'] = json_decode($goods_del['photo'], true);

				for ($i = 0; $i < count($goods_del['photo']); $i++) { 
					
					$filename = __DIR__ . '/../../data/images/goods/' . $goods_del['photo']['img'.$i];

					if (file_exists($filename)) unlink($filename);

					$filename = __DIR__ . '/../../data/images/goods_thumb/' . $goods_del['photo']['img'.$i];

					if (file_exists($filename)) unlink($filename);

				}

				/*$goods_del['export'] = json_decode($goods_del['export'], true);

				if ($goods_del['export']['prom_xlsx']) {

					$filename = __DIR__ . '/../../files/xlsx/' . $goods_del['export']['prom_xlsx'];

					if (file_exists($filename)) unlink($filename);

				}*/

				if ($user['admin'] == 1) $sql = "DELETE FROM `goods` WHERE `id`='{$delete_goods_id}'";
				elseif ($user['admin'] == 2) $sql = "DELETE FROM `goods` WHERE `id`='{$delete_goods_id}' AND `user_id`='{$user_id}'";

				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: /admin/goods/');
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> Вы не можете удалить этот товар, так как он используется в домашних заданиях или заказах.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

			}

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

				if ($user['admin'] == 1)  {
					$sql = "UPDATE `goods` SET `category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`description`='{$description}',
												`photo`='{$photo}',
												`video`='{$video}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`status`='{$status}',
												`updated`='{$current_date}' WHERE `id`='{$edit_goods_id}'";
				} elseif ($user['admin'] == 2) {
					$sql = "UPDATE `goods` SET `category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`description`='{$description}',
												`photo`='{$photo}',
												`video`='{$video}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`status`='{$status}',
												`updated`='{$current_date}' WHERE `id`='{$edit_goods_id}' AND `user_id`='{$user_id}'";
				}
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: /admin/goods/');
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

				if ($user['admin'] == 1) {
					$sql = "INSERT INTO `goods` SET `user_id`='{$user_id}',
												`category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`description`='{$description}',
												`photo`='{$photo}',
												`video`='{$video}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`status`='{$status}',
												`updated`='{$current_date}',
												`created`='{$current_date}'";
				} elseif ($user['admin'] == 2) {
					$sql = "INSERT INTO `goods` SET `user_id`='{$user_id}',
												`category`='{$category}',
												`name`='{$name}',
												`parameters`='{$template}',
												`description`='{$description}',
												`photo`='{$photo}',
												`video`='{$video}',
												`export`='{$export}',
												`groups`='{$group}',
												`availability`='{$availability}',
												`currency`='{$currency}',
												`currency_top_kurs`='{$currency_top_kurs}',
												`price_agent`='{$price_agent}',
												`price_purchase`='{$price_purchase}',
												`price_sale`='{$price_sale}',
												`status`='{$status}',
												`updated`='{$current_date}',
												`created`='{$current_date}'";
				}
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: /admin/goods/');
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

				$name = array();
				$photo = array();

				$category = $goods_copy['category'];

				$goods_copy['name'] = json_decode($goods_copy['name'], true);
				$name['uk'] = $goods_copy['name']['uk'] . ' Копія';
				$name['ru'] = $goods_copy['name']['ru'] . ' Копия';
				$name = json_encode($name, JSON_UNESCAPED_UNICODE);
				$name = str_replace("'", "\'", $name);

				$parameters = $goods_copy['parameters'];
				$parameters = str_replace("'", "\'", $parameters);

				$description = $goods_copy['description'];
				$description = str_replace("'", "\'", $description);
				$description = str_replace('\r\n', '\\\r\\\n', $description);
				
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
				$status = 0;

				if (mysqli_num_rows($query) > 0) {

					$sql = "INSERT INTO `goods` SET `user_id`='{$user_id}',
													`category`='{$category}',
													`name`='{$name}',
													`parameters`='{$parameters}',
													`description`='{$description}',
													`photo`='{$photo}',
													`video`='{$video}',
													`export`='{$export}',
													`groups`='{$groups}',
													`availability`='{$availability}',
													`currency`='{$currency}',
													`currency_top_kurs`='{$currency_top_kurs}',
													`price_agent`='{$price_agent}',
													`price_purchase`='{$price_purchase}',
													`price_sale`='{$price_sale}',
													`status`='{$status}',
													`updated`='{$current_date}',
													`created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				header('Location: /admin/goods/');
				exit;

			} else {

				$alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Ошибка!</strong> '.$error_message.'
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';

			}

		} elseif ($edit_availability_goods_id > 0) {

			if ($user['admin'] == 1)
				$sql = "UPDATE `goods` SET `availability`='{$availability}', `updated`='{$current_date}' WHERE `id`='{$edit_availability_goods_id}'";
			elseif ($user['admin'] == 2)
				$sql = "UPDATE `goods` SET `availability`='{$availability}', `updated`='{$current_date}' WHERE `id`='{$edit_availability_goods_id}' AND `user_id`='{$user_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			header('Location: /admin/goods/');
			exit;

		} elseif ($filter_goods_id > 0) {

			if ($category != 'all') {
				
				$_SESSION['filter_goods_admin'] = $category;

				if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
				if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);

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

		}

	}

}

if (!empty($_GET['goods_code'])) {

	if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
	if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
	if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);
	
}

?>