<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<div class="table-responsive">
	<table class="table table-striped table-hover" style="margin-top: 30px; font-size: 14px;">
		<thead class="thead-light">
			<tr>
				<th>№</th>
				<th>Имя</th>
				<th>Телефон</th>
				<th>E-mail</th>
				<th>Статус</th>
				<th>Дата/Время</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?

			$sql = "SELECT * FROM `subscribers` WHERE `user_id`='{$user_id}' ORDER BY `status` ASC, `created` DESC";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			$n = 0;

			if (mysqli_num_rows($query) > 0) {

				while ($subscribers = mysqli_fetch_assoc($query)) {

					$n++;

					switch ($subscribers['status']) {
						case 0:
							$subscribers_status = 'Новый';
							break;
						case 1:
							$subscribers_status = 'В обработке';
							break;
						case 2:
							$subscribers_status = 'Обработан';
							break;
					}

					switch ($subscribers['screen']) {
						case 1:
							$subscribers['screen'] = 'Первый экран';
							break;
						case 2:
							$subscribers['screen'] = 'Экран &quot;ЗАРАБОТАТЬ - Калькулятор&quot;';
							break;
						case 3:
							$subscribers['screen'] = 'Экран &quot;ПРЯМО СЕЙЧАС&quot;';
							break;
						case 4:
							$subscribers['screen'] = 'Экран &quot;ДВА ПУТИ&quot;';
							break;
						case 5:
							$subscribers['screen'] = 'Последный экран';
							break;
						default:
							$subscribers['screen'] = 'Экран не определен';
							break;
					}

					$subscribers['updated'] = date('d.m.Y H:i', strtotime($subscribers['updated']));
					$subscribers['created'] = date('d.m.Y H:i', strtotime($subscribers['created']));

				?>

				<tr data-id="<?=$subscribers['id']?>" data-name="<?=$subscribers['name']?>" data-phone="<?=$subscribers['phone']?>" data-email="<?=$subscribers['email']?>" data-site="<?=$subscribers['site']?>" data-description="<?=$subscribers['description']?>" data-status="<?=$subscribers['status']?>" data-ip="<?=$subscribers['ip']?>" data-geo="<?=$subscribers['geo']?>" data-screen="<?=$subscribers['screen']?>" data-calc="<?=$subscribers['calc']?> грн." data-updated="<?=$subscribers['updated']?>" data-created="<?=$subscribers['created']?>">
					<td><?=$n?></td>
					<td>
						<a id="copyName<?=$n?>" onclick="copyLink(this)" href="#" class="btn-clipboard" data-clipboard-target="#copyName<?=$n?>">
						<?=$subscribers['name']?>
					</a>
					</td>
					<td>
						<a id="copyTel<?=$n?>" onclick="copyLink(this)" href="#" class="btn-clipboard" data-clipboard-target="#copyTel<?=$n?>"><?=$subscribers['phone']?></a>
					</td>
					<td>
						<a id="copyMail<?=$n?>" onclick="copyLink(this)" href="#" class="btn-clipboard" data-clipboard-target="#copyMail<?=$n?>"><?=$subscribers['email']?></a>
					</td>
					<td><?=$subscribers_status?></td>
					<td><?=$subscribers['created']?></td>
					<td>
						<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSubscriber" onclick="editSubscriber(this)">
							<i class="material-icons" style="float:left">edit</i>
						</button>
					</td>
				</tr>

				<?

				}

			} else {

				echo '<tr><td colspan="7" class="text-center">Список лидов пуст</td></tr>';

			}

			?>
		</tbody>
	</table>
</div>

<div class="modal fade" id="editSubscriber">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Редактировать лида</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<form method="POST">
						<input type="hidden" name="edit_id" id="editId" value="">
						<table class="table table-hover">
							<tbody>
								<tr id="editName">
									<th>Имя</th>
									<td></td>
								</tr>
								<tr id="editPhone">
									<th>Телефон</th>
									<td></td>
								</tr>
								<tr id="editEmail">
									<th>E-mail</th>
									<td></td>
								</tr>
								<tr id="editSite">
									<th>Сайт</th>
									<td>
										<input type="text" name="site" class="form-control" placeholder="Введите адресс сайта">
									</td>
								</tr>
								<tr id="editDescription">
									<th>Описание</th>
									<td>
										<textarea name="description" class="form-control" rows="5" placeholder="Введите описание..."></textarea>
									</td>
								</tr>
								<tr id="editStatus">
									<th>Статус</th>
									<td>
										<select name="status" class="form-control" required>
											<option value="0">Новый</option>
											<option value="1">В обработке</option>
											<option value="2">Обработан</option>
										</select>
									</td>
								</tr>
								<tr id="editIp">
									<th>IP</th>
									<td></td>
								</tr>
								<tr id="editGeo">
									<th>ГЕО Локация</th>
									<td></td>
								</tr>
								<tr id="editScreen">
									<th>Экран</th>
									<td></td>
								</tr>
								<tr id="editCalc">
									<th>Калькулятор</th>
									<td></td>
								</tr>
								<tr id="editUpdated">
									<th>Редактирован</th>
									<td></td>
								</tr>
								<tr id="editCreated">
									<th>Зарегистрирован</th>
									<td></td>
								</tr>
							</tbody>
						</table>
						<p class="text-right">
							<button type="submit" class="btn btn-success">Редактировать</button>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>