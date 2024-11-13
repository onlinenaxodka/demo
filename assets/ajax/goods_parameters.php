<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

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

			$category = (isset($_POST['category'])) ? mysqli_real_escape_string($db, $_POST['category']) : '';
			$category = test_request($category);
				
			if (!empty($category)) {

				$sql = "SELECT `template` FROM `catalog` WHERE `linkname`='{$category}' LIMIT 1";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$catalog = mysqli_fetch_assoc($query);

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
									<input type="text" name="param_value_ru[]" class="form-control" placeholder="Значение параметра" value="<?=$template_with_db['ru'][$template_with_db_key_ru[$i]]?>" required>
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

<?

			}

		}

	}

}

?>