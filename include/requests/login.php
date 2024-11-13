<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$error_message = '';

		if (!empty($_POST['token'])) {

			$response = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
	        $user = json_decode($response, true);

	        if ($user['uid']) {

	            $network = $user['network'];
	            $social_id = trim($user['uid']);
	            
	            if ($user['network']) {

	                switch ($network) {
	                    case "facebook":
	                        $table_tag = 'fb';
	                        break;
	                    case "google":
	                        $table_tag = 'gl';
	                        break;
	                }
	                
	                $sql = "SELECT `user_id` FROM `users_{$table_tag}` WHERE `social_id`='{$social_id}' LIMIT 1";
	                $query = mysqli_query($db, $sql) or die(mysqli_error());
	                $user = mysqli_fetch_assoc($query);
	                $user_id = $user['user_id'];

	                if (mysqli_num_rows($query) == 1) {

	                    $sql = "SELECT `id`, `key`, `activated` FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
	                    $query = mysqli_query($db, $sql) or die(mysqli_error());
	                    $user = mysqli_fetch_assoc($query);
	                    
	                    if ($user['activated'] == 1) {

	                    	$user_new_key = GenerateKey();
							$sql = "UPDATE `users` SET `key`='{$user_new_key}', `was`='{$current_date}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
							$query = mysqli_query($db, $sql) or die(mysqli_error());

							$sql = "SELECT `key` FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
		                    $query = mysqli_query($db, $sql) or die(mysqli_error());
		                    $user = mysqli_fetch_assoc($query);

							$_SESSION['user'] = array('id' => $user_id, 'hash' => $user['key']);

	                    	header('Location: ' . $main_page);
	                    	exit;

	                    } else {

	                    	$_SESSION['user'] = array('id' => $user_id, 'hash' => $user['key']);

	                    	$error_message .= $login_page_error_message_1 . ' <a href="/activate/" class="alert-link">' . $login_page_error_message_2 . '</a><br>';

	                    }

	                } else {

	                	$error_message .= $login_page_error_message_3 . '<br>';

	                }

	            } else {
	                
					$error_message .= $login_page_error_message_4 . '<br>';

	            }

	        } else {
	            
				$error_message .= $login_page_error_message_5 . '<br>';

	        }

		} else {

			if (!empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['g-recaptcha-response'])) {

				$email = (isset($_POST['email'])) ? mysqli_real_escape_string($db, $_POST['email']) : '';
		        $password = (isset($_POST['password'])) ? mysqli_real_escape_string($db, $_POST['password']) : '';

				$email = test_request($email);
				$password = test_request($password);

				$recaptcha_code = $_POST['g-recaptcha-response'];
				$recaptcha_url_data = $recaptcha_url.'?secret='.$recaptcha_secret.'&response='.$recaptcha_code.'&remoteip='.$ip;

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $recaptcha_url_data);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($response, true);

				if ($result['success'] == 1) {

					$sql = "SELECT `salt` FROM `users` WHERE `mail`='{$email}' LIMIT 1";
					$query = mysqli_query($db, $sql) or die(mysqli_error());
					$user = mysqli_fetch_assoc($query);

					if (mysqli_num_rows($query) == 1) {
						
						$salt = $user['salt'];
						$password = md5(md5($password) . $salt);

						$sql = "SELECT `id`, `key`, `activated` FROM `users` WHERE `mail`='{$email}' AND `password`='{$password}' LIMIT 1";
						$query = mysqli_query($db, $sql) or die(mysqli_error());
						$user = mysqli_fetch_assoc($query);
						$user_id =  $user['id'];

						if (mysqli_num_rows($query) == 1) {
							
							if ($user['activated'] == 1) {

								$user_new_key = GenerateKey();
								$sql = "UPDATE `users` SET `key`='{$user_new_key}', `was`='{$current_date}', `updated`='{$current_date}' WHERE `id`='{$user_id}'";
		                    	$query = mysqli_query($db, $sql) or die(mysqli_error());

		                    	$sql = "SELECT `key` FROM `users` WHERE `id`='{$user_id}' LIMIT 1";
								$query = mysqli_query($db, $sql) or die(mysqli_error());
								$user = mysqli_fetch_assoc($query);

								$_SESSION['user'] = array('id' => $user_id, 'hash' => $user['key']);
								
								header('Location: ' . $main_page);
	    						exit;

							} else {

								$_SESSION['user'] = array('id' => $user_id, 'hash' => $user['key']);

								$error_message .= $login_page_error_message_1 . ' <a href="/activate/" class="alert-link">' . $login_page_error_message_2 . '</a><br>';

							}

						} else {

							$error_message .= $login_page_error_message_6 . '<br>';

						}

					} else {

						$error_message .= $login_page_error_message_7 . '<br>';

					}

				} else {

					$error_message .= $login_page_error_message_8 . '<br>';

				}

			} else {

				$error_message .= $login_page_error_message_9 . '<br>';

			}

		}

		$alert_message = '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' . $word_error . '</strong> ' . $error_message . '</div>';

	}

}

function GenerateKey($n=64) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pattern_arr = str_split($pattern);
    $counter = strlen($pattern)-1;
    for($i=0; $i<$n; $i++) {
        $key .= $pattern_arr[rand(0,$counter)];
    }
    return $key;
}

?>