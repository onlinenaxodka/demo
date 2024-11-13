<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once ('../../config.php');

if (isset($_SESSION['user'])) {

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if (!empty($_POST)) {

			$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
			$id = test_request($id);
			$id = intval($id);
			$type = (isset($_POST['type'])) ? mysqli_real_escape_string($db, $_POST['type']) : '';
			$type = test_request($type);


			if ($type == 'inputCity') {

				$result = mysqli_query($db, "SELECT * FROM `cities` WHERE `region_id` = '{$id}' ORDER BY name");
				if (mysqli_num_rows($result) != 0) {
					echo "out.options[out.options.length] = new Option('Выберите город','none');\n";
					while ($city = mysqli_fetch_assoc($result)) {
						$city_name = str_replace("'", "`", $city['name']);
						echo "out.options[out.options.length] = new Option('".$city_name."','".$city['id']."');\n";
					}
				} else {
					echo "out.options[out.options.length] = new Option('нет городов','none');\n";
				}
			}

			if ($type == 'inputRegion') {

				$result = mysqli_query($db, "SELECT * FROM `regions` WHERE `country_id` = '{$id}' ORDER BY name");
				if (mysqli_num_rows($result) != 0) {
					echo "out.options[out.options.length] = new Option('Выберите регион','none');\n";
					while ($region = mysqli_fetch_assoc($result)) {
						$region_name = str_replace("'", "`", $region['name']);
						echo "out.options[out.options.length] = new Option('".$region_name."','".$region['id']."');\n";
					}
				} else {
					echo "out.options[out.options.length] = new Option('нет регионов','none');\n";
				}
			}

		}

	}

}