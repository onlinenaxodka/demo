<?php include_once '../include/main_before_content.php'; ?>

<?

if (empty($_GET['ticket'])) {

?>

<div class="row justify-content-center">
	<div class="col-sm-3 mt-4 mb-4">
		<button id="addTicket" class="btn btn-primary btn-block"><?=$support_page_btn_create_ticket?></button>
	</div>
</div>

<div class="row form-group justify-content-center mt-4">
	<div class="col-sm-8">
		<form id="formTicket" action="/account/support/" method="POST" class="support-form-ticket">
			<div class="form-group">
				<label for="subject"><?=$support_page_label_create_ticket[0]?></label>
				<input id="subject" class="form-control" type="text" name="subject" maxlength="255" required>
			</div>
			<div class="form-group">
				<label for="message"><?=$support_page_label_create_ticket[1]?></label>
				<textarea rows="10" id="message" class="form-control" name="message" required></textarea>
			</div>
			<div class="form-group text-sm-right">
				<input id="btnCancel" type="button" class="btn btn-secondary" value="<?=$word_cancel?>">
				<input type="submit" class="btn btn-primary" value="<?=$word_send?>">
			</div>
		</form>
	</div>
</div>

<h3 class="text-sm-center mb-4"><?=$support_page_title_my_tickets?></h3>

<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-light">
			<tr>
				<th>#</th>
				<th><?=$support_page_table_thead_my_tickets[0]?></th>
				<th><?=$support_page_table_thead_my_tickets[1]?></th>
				<th style="min-width: 128px"><?=$support_page_table_thead_my_tickets[2]?></th>
			</tr>
		</thead>
		<tbody>

		<?

			$sql = "SELECT * FROM `support_subjects` WHERE `user_id`='{$user_id}' ORDER BY `status` ASC, `updated` DESC";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) != 0) {

				$num = 0;

				while ($support_subjects = mysqli_fetch_assoc($query)) {
					
					$num++;

					if ($support_subjects['status'] == 0) {

						if ($support_subjects['answer'] == 0) {

							$support_subjects_status = '<b style="color:#dfb81c">'.$word_opened.'</b>';

						} else {

							$support_subjects_status = '<b style="color:#84ad00">'.$word_opened.'</b>';

						}

					} else {
						$support_subjects_status = '<b>'.$word_closed.'</b>';
					}

					echo '<tr>
							<td><b>'.$num.'</b></td>
							<td><a href="?ticket='.$support_subjects['id'].'">'.$support_subjects['subject'].'</a></td>
							<td>'.$support_subjects_status.'</td>
							<td>'.date($datetime_format, strtotime($support_subjects['updated'])).'</td>
						</tr>';

				}

			} else {

				echo '<tr><td colspan="4" style="text-align:center">'.$support_page_have_not_my_tickets.'</td></tr>';

			}

		?>

		</tbody>
	</table>
</div>
<p class="text-muted"><i class="material-icons mr-3" style="color:#84ad00;float:left;">lens</i> <?=$support_page_words_info[0]?></p>
<p class="text-muted"><i class="material-icons mr-3" style="color:#dfb81c;float:left;">lens</i> <?=$support_page_words_info[1]?></p>

<?

} else {

	$sql = "SELECT * FROM `support_messages` WHERE `subject_id`='{$subject_id}'";
	$query = mysqli_query($db, $sql) or die(mysqli_error());

?>

<div class="support-body pt-4 pb-4 mb-4">

<?


	$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo">';
	$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
	for ($i = 0; $i < count($type_img); $i++) { 
		$img_name = __DIR__ . '/../data/images/users/user'.$user_id.'.'.$type_img[$i];
		if (file_exists($img_name)) {
			$img_photo = '<img src="/data/images/users/user'.$user_id.'.'.$type_img[$i].'" alt="User Photo">';
		}
	}					

	if (mysqli_num_rows($query) != 0) {

		while ($support_messages = mysqli_fetch_assoc($query)) {

			$support_messages['message'] = str_replace("\r\n", "<br>", $support_messages['message']);

			if ($support_messages['type_user'] == 1) {

?>
				
				<div class="row pb-2">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-1 pr-0">
								<?=$img_photo?>
							</div>
							<div class="col-sm-11">
								<div class="user-message"><?=$support_messages['message']?></div>
							</div>
						</div>
						<p class="text-right mr-3"><small><?=date($datetime_format, strtotime($support_messages['created']))?></small></p>
					</div>
				</div>

<?
				
			} else {

?>
				
				<div class="row pb-2">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-1 text-right pl-0 mobile">
								<img src="/data/images/users/support.png">
							</div>
							<div class="col-sm-11">
								<div class="support-message"><?=$support_messages['message']?></div>
							</div>
							<div class="col-sm-1 pl-0 desctop">
								<img src="/data/images/users/support.png">
							</div>
						</div>
						<p class="text-left ml-3"><small><?=date($datetime_format, strtotime($support_messages['created']))?></small></p>
					</div>
				</div>

<?

			}

		}

	} else {

		echo '<p class="text-center">' . $support_page_have_not_messaging . '</p>';

	}

?>

</div>
	
<div class="row form-group justify-content-center">
	<div class="col-sm-8">
		<form id="formTicket" action="/account/support/?ticket=<?=$_GET['ticket']?>" method="POST">
			<div class="form-group row">
				<div class="col-sm-12">
					<textarea rows="10" class="form-control" placeholder="<?=$support_page_placeholder_enter_message?>" name="message" required></textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-8"></div>
				<div class="col-sm-4">
					<input type="submit" class="btn btn-primary btn-block" value="<?=$word_send?>">
				</div>
			</div>
		</form>
	</div>
</div>

<?

}

?>


<? include_once '../include/main_after_content.php'; ?>