<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		$delete_catalog = (isset($_POST['delete_catalog'])) ? mysqli_real_escape_string($db, $_POST['delete_catalog']) : '';
		$delete_catalog = test_request($delete_catalog);
		$delete_catalog = intval($delete_catalog);

		if ($delete_catalog == 0) {

			$level_id = (isset($_POST['level_id'])) ? mysqli_real_escape_string($db, $_POST['level_id']) : '';
			$level_id = test_request($level_id);
			$level_id = intval($level_id);

			$edit_level_id = (isset($_POST['edit_level_id'])) ? mysqli_real_escape_string($db, $_POST['edit_level_id']) : '';
			$edit_level_id = test_request($edit_level_id);
			$edit_level_id = intval($edit_level_id);

			$catalog_id = (isset($_POST['catalog_id'])) ? mysqli_real_escape_string($db, $_POST['catalog_id']) : '';
			$catalog_id = test_request($catalog_id);
			$catalog_id = intval($catalog_id);

			$linkname = (isset($_POST['linkname'])) ? mysqli_real_escape_string($db, $_POST['linkname']) : '';
			$linkname = test_request($linkname);

			if (!empty($linkname)) {

				if (preg_match("/^[a-z0-9_]{1,120}$/", $linkname)) {

					if ($level_id > 0) {

						$sql = "SELECT `id` FROM `catalog` WHERE `linkname`='{$linkname}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error());

						if (mysqli_num_rows($query) > 0) {

							$error = true;
							$error_message .= 'В базе уже создан такой <b>linkname</b> измените его немного.<br>';

						}

					}

					if ($catalog_id > 0) {

						$sql = "SELECT `id` FROM `catalog` WHERE `linkname`='{$linkname}' AND `id`!='{$catalog_id}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error());

						if (mysqli_num_rows($query) > 0) {

							$error = true;
							$error_message .= 'В базе уже создан такой <b>linkname</b> измените его немного.<br>';

						}

					}

				} else {

					$error = true;
					$error_message .= 'Для <b>linkname</b> длинной до 120 символов допустимо только маленькие латинские буквы ( a-z ), цифры ( 0-9 ) и нижние подчеркивание ( _ ).<br>';

				}

			} else {

				$error = true;
				$error_message .= 'Поле linkname обязательное.<br>';

			}

			$name_uk = (isset($_POST['name_uk'])) ? mysqli_real_escape_string($db, $_POST['name_uk']) : '';
			$name_uk = test_request($name_uk);
			$name_uk = str_replace("'", "\'", $name_uk);

			$name_ru = (isset($_POST['name_ru'])) ? mysqli_real_escape_string($db, $_POST['name_ru']) : '';
			$name_ru = test_request($name_ru);
			$name_ru = str_replace("'", "\'", $name_ru);

			//$name_uk = $name_ru;

			if (empty($name_uk) or empty($name_ru)) {

				$error = true;
				$error_message .= 'Поле Имя каталога обязательное.<br>';

			}

			$locked = (isset($_POST['locked'])) ? mysqli_real_escape_string($db, $_POST['locked']) : '';
			$locked = test_request($locked);
			$locked = intval($locked);

			/*$prom = (isset($_POST['prom'])) ? mysqli_real_escape_string($db, $_POST['prom']) : '';
			$prom = test_request($prom);

			if (empty($prom)) {

				$error = true;
				$error_message .= 'Поле Cсылка для выгрузки на Prom.ua каталога обязательное.<br>';

			}*/

			for ($i=0; $i < count($_POST['param_name_ru']); $i++) {

				$param_name_uk = (isset($_POST['param_name_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_uk'][$i]) : '';
				$param_name_uk = test_request($param_name_uk);

				$param_value_uk = (isset($_POST['param_value_uk'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_uk'][$i]) : '';
				$param_value_uk = test_request($param_value_uk);

				$param_name_ru = (isset($_POST['param_name_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_name_ru'][$i]) : '';
				$param_name_ru = test_request($param_name_ru);

				$param_value_ru = (isset($_POST['param_value_ru'][$i])) ? mysqli_real_escape_string($db, $_POST['param_value_ru'][$i]) : '';
				$param_value_ru = test_request($param_value_ru);

				//$param_name_uk = $param_name_ru;
				//$param_value_uk = $param_value_ru;

				if (!empty($param_name_uk) and !empty($param_value_uk) and !empty($param_name_ru) and !empty($param_value_ru)) {

					$template_uk[$param_name_uk] = $param_value_uk;
					$template_ru[$param_name_ru] = $param_value_ru;

				} else {

					$error = true;
					$error_message .= 'Все поля параметров для товаров обязательны.<br>';

				}

			}

			$template['uk'] = $template_uk;
			$template['ru'] = $template_ru;

			$template = json_encode($template, JSON_UNESCAPED_UNICODE);
			$template = str_replace("'", "\'", $template);

			$rate_prom = (isset($_POST['rate_prom'])) ? mysqli_real_escape_string($db, $_POST['rate_prom']) : '';
			$rate_prom = test_request($rate_prom);
			$rate_prom = intval($rate_prom);

			$rate_rozetka = (isset($_POST['rate_rozetka'])) ? mysqli_real_escape_string($db, $_POST['rate_rozetka']) : '';
			$rate_rozetka = test_request($rate_rozetka);
			$rate_rozetka = intval($rate_rozetka);

			$rate = (isset($_POST['rate'])) ? mysqli_real_escape_string($db, $_POST['rate']) : '';
			$rate = test_request($rate);
			$rate = floatval($rate) * 0.01;

			if ($rate < 0) $rate = $rate * (-1);

			$sort = (isset($_POST['sort'])) ? mysqli_real_escape_string($db, $_POST['sort']) : '';
			$sort = test_request($sort);
			$sort = intval($sort);

			$catalog_buffer_status = 0;

			if ($level_id == 2274) {

				$catalog_buffer_status = 1;

			}

		}

		if ($delete_goods_id == 0) {

			include_once __DIR__ . '/../libs/ImageResize.php';

		}

		if ($delete_catalog > 0) {

			$sql = "SELECT * FROM `catalog` WHERE `id`='{$delete_catalog}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$catalog_del = mysqli_fetch_assoc($query);

			$filename = __DIR__ . '/../../data/images/catalog/' . $catalog_del['img'];

			if ($catalog_del['img'] != 'no_image.png') {

				if (file_exists($filename)) {

					unlink($filename);

				}

			}

			$sql = "DELETE FROM `catalog` WHERE `id`='{$delete_catalog}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			header('Location: ' . $_SERVER['REQUEST_URI']);
			exit;

		} elseif ($level_id > 0) {
			
			if (!empty($_FILES['img']['name'])) {

				if ($_FILES['img']['type'] == 'image/png') {

					$uploaddir = __DIR__ . '/../../data/images/catalog/';
					$filename = time() . '.' . substr(strrchr($_FILES['img']['name'], '.'), 1);
									
					$uploadfile = $uploaddir.$filename;

					move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile);

					if (file_exists($uploadfile)) {

						$image = new \Gumlet\ImageResize($uploadfile);


						if ($image->getSourceWidth() >= $image->getSourceHeight()) {

							if ($image->getSourceWidth() > 256) {

								$image->resizeToWidth(256);

							}

						} else {

							if ($image->getSourceHeight() > 256) {

								$image->resizeToHeight(256);

							}

						}

						$image->save($uploadfile);

					}
									
				} else {

					$error = true;
					$error_message .= 'Формат изображения только png.<br>';

				}

			} else {

				$error = true;
				$error_message .= 'Поле изображение обязательное.<br>';

			}

			if (!$error) {

				$sql = "INSERT INTO `catalog` SET `level_id`='{$level_id}',
													`linkname`='{$linkname}',
													`name_uk`='{$name_uk}',
													`name_ru`='{$name_ru}',
													`template`='{$template}',
													`img`='{$filename}',
													/*`export_prom`='{$prom}',*/
													`rate_prom`='{$rate_prom}',
													`rate_rozetka`='{$rate_rozetka}',
													`rate`='{$rate}',
													`locked`='{$locked}',
													`sort`='{$sort}',
													`buffer`='{$catalog_buffer_status}',
													`created`='{$current_date}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: ' . $_SERVER['REQUEST_URI']);
				exit;

			}

		} elseif ($catalog_id > 0) {
			
			$sql = "SELECT `img` FROM `catalog` WHERE `id`='{$catalog_id}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());
			$catalog_img = mysqli_fetch_assoc($query);

			if (!empty($_FILES['img']['name'])) {

				if ($_FILES['img']['type'] == 'image/png') {

					$uploaddir = __DIR__ . '/../../data/images/catalog/';
					$filename = $catalog_img['img'];
									
					$uploadfile = $uploaddir.$filename;

					move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile);

					if (file_exists($uploadfile)) {

						$image = new \Gumlet\ImageResize($uploadfile);

						if ($image->getSourceWidth() >= $image->getSourceHeight()) {

							if ($image->getSourceWidth() > 256) {

								$image->resizeToWidth(256);

							}

						} else {

							if ($image->getSourceHeight() > 256) {

								$image->resizeToHeight(256);

							}

						}

						$image->save($uploadfile);

					}
									
				} else {

					$error = true;
					$error_message .= 'Формат изображения только png.<br>';

				}

			}

			if (!$error) {
				if ($edit_level_id > 0) {
					$sql = "UPDATE `catalog` SET `level_id`='{$edit_level_id}',
											`linkname`='{$linkname}',
											`name_uk`='{$name_uk}',
											`name_ru`='{$name_ru}',
											`template`='{$template}',
											/*`export_prom`='{$prom}',*/
											`rate_prom`='{$rate_prom}',
											`rate_rozetka`='{$rate_rozetka}',
											`rate`='{$rate}',
											`locked`='{$locked}',
											`sort`='{$sort}',
											`buffer`='{$catalog_buffer_status}' WHERE `id`='{$catalog_id}'";
				} else {
					$sql = "UPDATE `catalog` SET `linkname`='{$linkname}',
											`name_uk`='{$name_uk}',
											`name_ru`='{$name_ru}',
											`template`='{$template}',
											/*`export_prom`='{$prom}',*/
											`rate_prom`='{$rate_prom}',
											`rate_rozetka`='{$rate_rozetka}',
											`rate`='{$rate}',
											`locked`='{$locked}',
											`sort`='{$sort}',
											`buffer`='{$catalog_buffer_status}' WHERE `id`='{$catalog_id}'";
				}
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				header('Location: ' . $_SERVER['REQUEST_URI']);
				exit;

			}

		}

		if ($error) $alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '.$error_message.'</div>';

	}

}

$breadcrumb = array(
	'names' => array('Панель приборов', 'Категории товаров'),
	'links' => array('/admin/', '/admin/goods_catalog/')
);

if (!empty($_GET['linkname'])) {

	$linkname = test_request($_GET['linkname']);

	$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$catalog = mysqli_fetch_assoc($query);

	function displayBreadcrumbGoods($db, $lang, $level_id) {
	
		$sql = "SELECT * FROM `catalog` WHERE `id`='{$level_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		while ($catalog = mysqli_fetch_assoc($query)) {
			
			displayBreadcrumbGoods($db, $lang, $catalog['level_id']);

			if ($catalog['level_id'] != 0) $_SESSION['breadcrumb'][$catalog['linkname']] = $catalog['name_'.$lang];

		}

	}

	displayBreadcrumbGoods($db, 'ru', $catalog['id']);

	if (!empty($_SESSION['breadcrumb'])) {
		foreach ($_SESSION['breadcrumb'] as $session_breadcrumb_link => $session_breadcrumb_name) {
			$breadcrumb['names'][] = $session_breadcrumb_name;
			$breadcrumb['links'][] = '/admin/goods_catalog/?linkname='.$session_breadcrumb_link;
		}
	}

	unset($_SESSION['breadcrumb']);

}

/*function countGoodsInCategory($db, $category_id, $count_goods) {

	$sql = "SELECT `id` FROM `catalog` WHERE `level_id`='{$category_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$count_subcategories = mysqli_num_rows($query);

	if ($count_subcategories > 0) {

		while ($subcategories = mysqli_fetch_assoc($query)) {

			$count_goods_in_category_pre = countGoodsInCategory($db, $subcategories['id'], $count_goods);

			$count_goods_in_category += $count_goods_in_category_pre;

		}

	} elseif ($count_subcategories == 0) {

		$sql = "SELECT `linkname` FROM `catalog` WHERE `id`='{$category_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$category = mysqli_fetch_assoc($query);

		$sql = "SELECT `id` FROM `goods` WHERE `category`='{$category['linkname']}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));
		$count_goods_in_category = mysqli_num_rows($query);

	}

	$count_goods += $count_goods_in_category;

	return $count_goods;

	//$count_goods += $count_goods + $count_goods_in_catalog;

	//return $count_goods;

}*/



?>