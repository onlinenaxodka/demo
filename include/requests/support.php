<?

if (!empty($_POST)) {

	if (empty($_GET['ticket'])) {

		$subject = (isset($_POST['subject'])) ? mysqli_real_escape_string($db, $_POST['subject']) : '';
		$subject = test_request($subject);

		$message = (isset($_POST['message'])) ? mysqli_real_escape_string($db, $_POST['message']) : '';
		$message = str_replace('\r\n', '\\\r\\\n', $message);
		$message = test_request($message);

		$sql = "INSERT INTO `support_subjects` SET `user_id`='{$user_id}',
													`subject`='{$subject}',
													`updated`='{$current_date}',
													`created`='{$current_date}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$sql = "SELECT `id` FROM `support_subjects` WHERE `user_id`='{$user_id}' ORDER BY `created` DESC LIMIT 1";
		$query = mysqli_query($db, $sql) or die(mysqli_error());
		$support_subject = mysqli_fetch_assoc($query);
		$support_subject_id = $support_subject['id'];

		$sql = "INSERT INTO `support_messages` SET `subject_id`='{$support_subject_id}',
													`type_user`=1,
													`message`=\"{$message}\",
													`created`='{$current_date}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		/*$email = $user['mail'];
		$subject = "Вы создали новый тикет в ".$name_company;
		$message = "<p style='text-align:center'>Для предотвращения потери сообщения тикету присваивается номер <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/support/?ticket=".$support_subject_id."' >№".$support_subject_id."</a>.</p>
					<p style='text-align:center'>В ближайшее время служба поддержки ответит вам, пожалуйста, дождитесь уведомления на этот же адрес электронной почты или проверяйте раздел Поддержка на <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/account/support/' >сайте</a>.</p>
					<p style='text-align:center'>С уважением, команда поддержки ".$name_company."!!!</p>
					<p style='text-align:center;font-weight:normal;font-style:italic;font-size:12px;'>Дата создания: ".date('d.m.Y H:i')."</p>";
		$from['name_company'] = $name_company;
		$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

		sendMail($email, $subject, $message, $from, $server_protocole);*/

		$email = $email_for_notify;
		$subject = "Кому-то нужна помощь, был создан тикет №".$support_subject_id." в ".$name_company;
		$message = "<p style='text-align:center'>Кому-то нужна помощь, было создано сообщение <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/support/?ticket=".$support_subject_id."' >№".$support_subject_id."</a>, пожалуйста предоставьте ответ в течение 24 часов.</p>
					<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/support/?ticket=".$support_subject_id."' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Ответить</a>
					<p style='text-align:center;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
		$from['name_company'] = $name_company;
		$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

		sendMail($email, $subject, $message, $from, $server_protocole);

		header('Location: /account/support/');
		exit;

	} else {

		$message = (isset($_POST['message'])) ? mysqli_real_escape_string($db, $_POST['message']) : '';
		$message = test_request($message);

		$subject_id = test_request($_GET['ticket']);
		$subject_id = intval($subject_id);

		$sql = "INSERT INTO `support_messages` SET `subject_id`='{$subject_id}',
													`type_user`=1,
													`message`=\"{$message}\",
													`created`='{$current_date}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		$sql = "UPDATE `support_subjects` SET `status`=0, `answer`=0, `updated`='{$current_date}' WHERE `id`='{$subject_id}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		$email = $email_for_notify;
		$subject = "Новый вопрос по тикету №".$subject_id." от пользователя в ".$name_company;
		$message = "<p style='text-align:center;'>Новый вопрос от пользователя ".$user['name']." ".$user['surname']." по тикету <a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/support/?ticket=".$subject_id."' >№".$subject_id."</a>, перейдите чтобы посмотреть вопрос.</p>
					<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/support/?ticket=".$subject_id."' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Ответить</a>
					<p style='text-align:center;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
		$from['name_company'] = $name_company;
		$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

		sendMail($email, $subject, $message, $from, $server_protocole);

		header('Location: /account/support/?ticket='.$subject_id);
		exit;

	}

}

if (!empty($_GET['ticket'])) {

	$subject_id = test_request($_GET['ticket']);
	$subject_id = intval($subject_id);

	$sql = "SELECT * FROM `support_subjects` WHERE `id`='{$subject_id}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$support_subjects = mysqli_fetch_assoc($query);

	$breadcrumb['names'][] = $support_subjects['subject'];

}

?>