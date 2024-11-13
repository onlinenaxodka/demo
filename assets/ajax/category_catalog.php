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

			$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
			$id = test_request($id);
			$id = intval($id);

			$current_catalog_id = (isset($_POST['current_catalog_id'])) ? mysqli_real_escape_string($db, $_POST['current_catalog_id']) : '';
			$current_catalog_id = test_request($current_catalog_id);
			$current_catalog_id = intval($current_catalog_id);
				
			if ($id > 0) {

				$sql = "SELECT * FROM `catalog` WHERE `id`='{$id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error());
				$catalog = mysqli_fetch_assoc($query);

				$sql_subcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}'";
				$query_subcategories = mysqli_query($db, $sql_subcategories) or die(mysqli_error($db));
				$count_subcategories = mysqli_num_rows($query_subcategories);

				if ($count_subcategories > 0) {

					$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$id}' AND `id` != 2274 ORDER BY `name_ru` ASC";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

?>

					<select onchange="changeParentCategory(this)" class="form-control mt-2" required>
						<option value="none" selected disabled>Выбирете категорию</option>

<?
				
					while ($catalog = mysqli_fetch_assoc($query)) {

						if ($catalog['id'] != $current_catalog_id) {

							$sql_subsubcategories = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog['id']}' ORDER BY `name_ru` ASC";
							$query_subsubcategories = mysqli_query($db, $sql_subsubcategories) or die(mysqli_error($db));
							$count_subsubcategories = mysqli_num_rows($query_subsubcategories);

							if ($count_subsubcategories > 0) {
														
								echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';

							} else {

								$sql_goods_category = "SELECT `id` FROM `goods` WHERE `category`='{$catalog['linkname']}'";
								$query_goods_category = mysqli_query($db, $sql_goods_category) or die(mysqli_error($db));
								$count_goods_category = mysqli_num_rows($query_goods_category);

								if ($count_goods_category > 0) {

									echo '<option value="'.$catalog['id'].'" class="bg-light" disabled>'.$catalog['name_ru'].'</option>';

								} else {

									echo '<option value="'.$catalog['id'].'">'.$catalog['name_ru'].'</option>';

								}

							}

						} else {

							echo '<option value="'.$catalog['id'].'" class="bg-light" disabled>'.$catalog['name_ru'].'</option>';
													
						}

					}

?>

					</select>

<?					

				}

			}

		}

	}

}