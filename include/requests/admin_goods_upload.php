<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error = false;
		$error_message = '';

		$provider = (isset($_POST['provider'])) ? mysqli_real_escape_string($db, $_POST['provider']) : '';
		$provider = test_request($provider);

		/*if ($provider != 'zinchenko_posuda') {
			$error = true;
			$error_message = 'Пока что файл можна загрузить zinchenko_posuda. ';
		}*/

		if (!$error) {

			if ($provider == 'zinchenko_posuda') {

				if (!empty($_FILES['excel_file']['name'])) {

					if ($_FILES['excel_file']['type'] == 'application/vnd.ms-excel') {

						$uploaddir = __DIR__ . '/../../data/files/import/';
						$filename = 'zinchenko_posuda.' . substr(strrchr($_FILES['excel_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadfile);

						header('Location: /assets/files/import_providers/zinchenko_posuda/parser_excel/');
						exit;
												
					}

				}

			} elseif ($provider == 'mobi') {

				if (!empty($_FILES['xml_file']['name'])) {

					if ($_FILES['xml_file']['type'] == 'text/xml') {

						$uploaddir = __DIR__ . '/../../assets/files/xml_providers/mobiking/import_xml/';
						$filename = 'mobiking.' . substr(strrchr($_FILES['xml_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['xml_file']['tmp_name'], $uploadfile);

						/*header('Location: /assets/files/import_providers/mobiking/parser_excel/');
						exit;*/
												
					}

				}

			} elseif ($provider == 'kontrabas') {

				if (!empty($_FILES['excel_file']['name'])) {

					if ($_FILES['excel_file']['type'] == 'application/vnd.ms-excel') {

						$uploaddir = __DIR__ . '/../../assets/files/import_providers/kontrabas/';
						$filename = 'kontrabas.' . substr(strrchr($_FILES['excel_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadfile);

						/*header('Location: /assets/files/import_providers/kontrabas/parser_excel/');
						exit;*/
						header('Location: /admin/goods_upload/');
						exit;
												
					}

				}

			} elseif ($provider == 'roman_melkaya_bitovaya_tehnika') {

				if (!empty($_FILES['excel_file']['name'])) {

					if ($_FILES['excel_file']['type'] == 'application/vnd.ms-excel') {

						$uploaddir = __DIR__ . '/../../assets/files/import_providers/small_household_appliances/';
						$filename = 'small_household_appliances.' . substr(strrchr($_FILES['excel_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadfile);

						header('Location: /assets/files/import_providers/small_household_appliances/parser_excel/');
						exit;
												
					}

				}

			} elseif ($provider == 'bat_hmelnitskiy') {

				if (!empty($_FILES['excel_file']['name'])) {

					if ($_FILES['excel_file']['type'] == 'application/vnd.ms-excel') {

						$uploaddir = __DIR__ . '/../../assets/files/import_providers/bat/';
						$filename = 'bat.' . substr(strrchr($_FILES['excel_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadfile);

						header('Location: /assets/files/import_providers/bat/parser_excel/');
						exit;
												
					}

				}

				if (!empty($_FILES['xml_file']['name'])) {

					if ($_FILES['xml_file']['type'] == 'text/xml') {

						$uploaddir = __DIR__ . '/../../assets/files/xml_providers/bat/';
						$filename = 'bat.' . substr(strrchr($_FILES['xml_file']['name'], '.'), 1);
												
						$uploadfile = $uploaddir.$filename;

						move_uploaded_file($_FILES['xml_file']['tmp_name'], $uploadfile);

						header('Location: /admin/goods_upload/');
						exit;
												
					}

				}

			}

		}

		if ($error) $alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '.$error_message.'</div>';

	}

}

//Mobiking

$finish_import_images = __DIR__ . '/../../assets/files/xml_providers/mobiking/finish_import_images.txt';
$finish_import_images_status = file_get_contents($finish_import_images);

if ($finish_import_images_status == 'finish' and $_GET['mobiking_images'] == 'success') {
	$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Картинки mobiking успешно обновлены до конца, обновляйте по очереди части дданые по товарам</div>';
} elseif ($finish_import_images_status == 'processing') {
	$alert_message = '<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Картинки mobiking еще не обновлены до конца, запустите обновление картинок еще раз</div>';
}

if ($_GET['mobiking_part_file_1'] == 'success') {
	$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Обновление mobiking <b class="text-danger">первой части</b> дданых по товарам прошло успешно</div>';
}

if ($_GET['mobiking_part_file_2'] == 'success') {
	$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Обновление mobiking <b class="text-danger">второй части</b> дданых по товарам прошло успешно</div>';
}

if ($_GET['mobiking_part_file_3'] == 'success') {
	$alert_message = '<div class="alert alert-success" role="alert"><strong>Успех!</strong> Обновление mobiking <b class="text-danger">третий части</b> дданых по товарам прошло успешно. Все данные обновлены.</div>';
}

//Mobiking

?>