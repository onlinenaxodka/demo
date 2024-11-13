<?php include_once __DIR__ . '/../include/admin_main_before_content.php'; ?>

<?

if (empty($_GET['ticket'])) {

?>

<div class="row form-group">
	<div class="col-sm-6">
		<form method="POST">
			<div class="row">
				<div class="col-sm-8">
					<select class="form-control mb-3" name="philter">
						<option value="open_and_no" <?=$selected_active1?>>С открытым статусом и без ответа</option>
						<option value="open_and_yes" <?=$selected_active2?>>С открытым статусом и с ответом</option>
						<option value="for_close" <?=$selected_active3?>>С ответом и для закрытия статуса</option>
						<option value="close" <?=$selected_active4?>>С закрытым статусом</option>
					</select>
				</div>
				<div class="col-sm-4">
					<button type="submit" class="btn btn-primary btn-block mb-3">Фильтровать</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-3"></div>
	<div class="col-sm-3">
		<button id="btnSaveChangesMessagesSupport" class="btn btn-success btn-block mb-3" style="display: none;">Сохранить</button>
	</div>
</div>

<form method="POST" id="saveChangesMessagesSupport">
	
<div class="table-responsive">
	<table class="table table-sm table-hover">
		<thead class="thead-default">
			<tr>
				<th>#</th>
				<th>Фото</th>
				<th>[ID] Имя и фамилия</th>
				<th>Тема сообщения</th>
				<th>
					<input type="checkbox" name="all_status" value="all_status" data-toggle="tooltip" title="Отметить все закрытыми"> Статус
				</th>
				<th>
					<input type="checkbox" name="all_answer" value="all_answer" data-toggle="tooltip" title="Отметить все, как ответы есть"> Ответ
				</th>
				<th>Дата/Час</th>
			</tr>
		</thead>
		<?

		if (mysqli_num_rows($query_philter) != 0) {

			$num = 0;

			while ($support_subjects = mysqli_fetch_assoc($query_philter)) {

				$num++;

						$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo" height="40" style="border-radius:100%">';
						$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
						for ($i = 0; $i < count($type_img); $i++) { 
							$img_name = __DIR__ . '/../data/images/users/user'.$support_subjects['user_id'].'.'.$type_img[$i];
							if (file_exists($img_name)) {
								$img_photo = '<img src="/data/images/users/user'.$support_subjects['user_id'].'.'.$type_img[$i].'" alt="User Photo" height="40" style="border-radius:100%">';
							}
						}

				$sql_user = "SELECT `name`,`surname` FROM `users` WHERE `id`=".$support_subjects['user_id']." LIMIT 1";
				$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
				$user = mysqli_fetch_assoc($query_user);

				$status = 'Открыто';
				if ($support_subjects['status'] == 1) {
					$status = 'Закрыто';
				}

				$answer = 'Нет ответа';
				if ($support_subjects['answer'] == 1) {
					$answer = 'Есть';
				}

				$style_bg = '';
				$input_checkbox_answer = '';
				if ($support_subjects['updated'] < $current_date_minus3days) {
					$style_bg = 'style="background:#ebcccc"';
					$input_checkbox_answer = '<input type="checkbox" class="status" name="status[]" value="'.$support_subjects['id'].'" data-toggle="tooltip" title="Отметить закрытым"> (Закрыто)<br>';
				}
				
				echo '<tr>
						<th>'.$num.'</th>
						<td>'.$img_photo.'</td>
						<td>['.$support_subjects['user_id'].'] '.$user['name'].' '.$user['surname'].'</td>
						<td '.$style_bg.'><a href="?ticket='.$support_subjects['id'].'">'.$support_subjects['subject'].'</a></td>
						<td '.$style_bg.'>'.$input_checkbox_answer.'<b>'.$status.'</b></td>
						<td><input type="checkbox" class="answer" name="answer[]" value="'.$support_subjects['id'].'" data-toggle="tooltip" title="Отметить, что ответ есть"> (Есть)<br><b>'.$answer.'</b></td>
						<td>'.$support_subjects['updated'].'</td>
					</tr>';

			}

		} else {

			echo '<tr><td colspan="7" class="text-center">Тикетов нет</td></tr>';

		}

		?>
	</table>
</div>

</form>

<br><br>

<div class="row form-group justify-content-center">
	<div class="col-sm-6">
		<form id="formTicket" method="POST" class="form_ticket">
			<div class="form-group">
				<label for="user">Пользователь</label>
				<select id="user" class="form-control" name="user" required>
					<option value="none" selected disabled>[ID] Имя и фамилия</option>
					<?

						$sql_select = "SELECT `id`, `name`, `surname` FROM `users` ORDER BY `created` ASC";
						$query_select = mysqli_query($db, $sql_select) or die(mysqli_error());

						while ($user_select = mysqli_fetch_assoc($query_select)) {

							echo '<option value="' . $user_select['id'] . '">[' . $user_select['id'] . '] ' . $user_select['name'] . ' ' . $user_select['surname'] . '</option>';

						}

					?>
				</select>
			</div>
			<div class="form-group">
				<label for="subject">Тема</label>
				<input id="subject" class="form-control" type="text" name="subject" maxlength="255" required>
			</div>
			<div class="form-group">
				<label for="message">Сообщение</label>
				<textarea rows="10" id="message" class="form-control" name="message" required></textarea>
			</div>
			<div class="form-group row">
				<div class="col-sm-8"></div>
				<div class="col-sm-4">
					<input type="submit" class="btn btn-primary btn-block" value="Отправить">
				</div>
			</div>
		</form>
	</div>
</div>

<?

} else {

	$subject_id = test_request($_GET['ticket']);
	$subject_id = intval($subject_id);

	$sql = "SELECT * FROM `support_subjects` WHERE `id`='{$subject_id}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$support_subjects = mysqli_fetch_assoc($query);

	$sql = "SELECT * FROM `support_messages` WHERE `subject_id`='{$subject_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());

?>

<p><a href="/admin/support/">Назад</a></p>
<h3 class="mb-3 pb-3"><b>Тема:</b> <?=$support_subjects['subject']?></h3>
<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-default">
			<tr>
				<th>Собеседник</th>
				<th>Сообщение</th>
				<th width="200">Дата/Время</th>
			</tr>
		</thead>

		<?

			$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo" height="70" style="border-radius:100%">';
			$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
			for ($i = 0; $i < count($type_img); $i++) { 
				$img_name = __DIR__ . '/../data/images/users/user'.$support_subjects['user_id'].'.'.$type_img[$i];
				if (file_exists($img_name)) {
					$img_photo = '<img src="/data/images/users/user'.$support_subjects['user_id'].'.'.$type_img[$i].'" alt="User Photo" height="70" style="border-radius:100%">';
				}
			}

			$sql_user = "SELECT `name`,`surname` FROM `users` WHERE `id`=".$support_subjects['user_id']." LIMIT 1";
			$query_user = mysqli_query($db, $sql_user) or die(mysqli_error());
			$user = mysqli_fetch_assoc($query_user);

			if (mysqli_num_rows($query) != 0) {

				while ($support_messages = mysqli_fetch_assoc($query)) {

					$support_messages['message'] = str_replace("\r\n", "<br>", $support_messages['message']);

					if ($support_messages['type_user'] == 1) {
						
						echo '<tr>
								<td>'.$img_photo.'<br>'.$user['name'].' '.$user['surname'].'</td>
								<td>'.$support_messages['message'].'</td>
								<td>'.$support_messages['created'].'</td>
							</tr>';
					
					} else {

						echo '<tr>
								<td><img src="/data/images/users/support.png" width="70"><br>Поддержка</td>
								<td>'.$support_messages['message'].'</td>
								<td>'.$support_messages['created'].'</td>
							</tr>';

					}

				}

			} else {

				echo '<tr><td colspan="3" class="text-center">Переписки с партнером не найдено</td></tr>';

			}

		?>

	</table>
</div>

<br>

<form method="POST">
	<input type="hidden" name="subject_id" value="<?=$_GET['ticket']?>">
	<div class="row form-group justify-content-center">
		<div class="col-sm-6">
			<div class="form-group row">
				<div class="col-sm-12">
					<textarea rows="15" class="form-control" placeholder="Введите текст сообщения" name="message" required></textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-8"></div>
				<div class="col-sm-4">
					<input type="submit" class="btn btn-primary btn-block" value="Отправить">
				</div>
			</div>
		</div>
	</div>
</form>

<?

}

?>

<? include_once __DIR__ . '/../include/admin_main_after_content.php'; ?>