<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST)) {

		$order_id = (isset($_POST['order'])) ? mysqli_real_escape_string($db, $_POST['order']) : '';
		$order_id = test_request($order_id);
		$order_id = intval($order_id);

		$comment = (isset($_POST['comment'])) ? mysqli_real_escape_string($db, $_POST['comment']) : '';
		$comment = str_replace('\r\n', '\\\r\\\n', $comment);
		$comment = test_request($comment);

		if ($order_id > 0) {

			$sql = "INSERT INTO `orders_messages` SET `user_id`='{$user_id}',
														`order_id`='{$order_id}',
														`type_user`=1,
														`message`=\"{$comment}\",
														`created`='{$current_date}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$email = $email_for_notify;
			$subject = "Новый комментарий по заказу №".$order_id." от пользователя в ".$name_company;
			$message = "<p style='text-align:center;'>Новый комментарий от пользователя ".$user['name']." ".$user['surname']." по заказу №".$order_id.", перейдите чтобы посмотреть комментарий.</p>
						<a href='".$server_protocole."://".$_SERVER['SERVER_NAME']."/admin/orders/' style='display:block;max-width:200px;padding:10px 30px;margin:10px auto;color:#fff;background:#007bff;font:18px Arial;text-decoration:none;text-align:center;border-radius:7px'>Ответить</a>
						<p style='text-align:center;'>Дата создания: ".date('d.m.Y H:i:s')."</p>";
			$from['name_company'] = $name_company;
			$from['address'] = "noreply@".$_SERVER['SERVER_NAME'];

			sendMail($email, $subject, $message, $from, $server_protocole);

			header('Location: /account/orders/');
			exit;

		}

	}

}

?>