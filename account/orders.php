<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$num = 20;

$sql = "SELECT COUNT(1) as count FROM `orders` WHERE `user_id`='{$user_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$results = mysqli_fetch_assoc($query);

$total = intval(($results['count'] - 1) / $num) + 1;

$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  

$start = $page * $num - $num;

?>

<!-- <img src="/assets/images/tmp_orders/orders_filters.jpg" alt="Orders Filters" class="w-100"> -->

<div class="table-responsive">
	<table class="table table-striped table-hover mt-3" style="font-size: 14px;">
		<thead class="thead-light">
			<tr>
				<th style="width: 75px">№</th>
				<th>Детали</th>
				<th style="min-width: 250px;">Комментарии</th>
				<!-- <th>Способ оплаты</th>
				<th>Сумма оплаты</th>
				<th>Прибыль</th>
				<th>Зарезервированная сумма баланса <i class="material-icons help_outline" data-toggle="tooltip" title="После завершения заказа или отмены, зарезервированная сумма баланса будет возвращена на ваш баланс">help_outline</i></th>
				<th>Предоплата</th> -->
				<th>Финансовая информация</th>
				<th>ТТН</th>
				<th>Статус</th>
				<!-- <th style="width: 75px">Создан</th> -->
			</tr>
		</thead>
		<tbody>

			<?

			$sql = "SELECT * FROM `orders` WHERE `user_id`='{$user_id}' ORDER BY `status` ASC, `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) > 0) {

				while ($orders = mysqli_fetch_assoc($query)) {

					$orders_id = $orders['id'];

					$orders['client'] = json_decode($orders['client'], true);
					$orders['client'] = $orders['client']['fio'] . ', ' . $orders['client']['phone'];
					$orders['client'] = str_replace("'", "\'", $orders['client']);

					$orders['delivery_address'] = json_decode($orders['delivery_address'], true) ?: [];

					switch ($orders['delivery']) {
						case 1:
							$orders['delivery_address'] = 'Новая почта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 2:
							$orders['delivery_address'] = 'Укрпочта: ' . implode(', ', $orders['delivery_address']);
							break;
						case 3:
							$orders['delivery_address'] = 'Самовывоз: ' . implode(', ', $orders['delivery_address']);
							break;
						default:
							$orders['delivery_address'] = '-';
							break;
					}

					$orders['delivery_address'] = str_replace("'", "\'", $orders['delivery_address']);

					switch ($orders['payment']) {
						case 1:
							$orders['payment'] = 'Наложенный платеж';
							break;
						case 2:
							$orders['payment'] = 'Перевод на карту 100%';
							break;
						case 3:
							$orders['payment'] = 'Внутренний баланс 100%';
							break;
						default:
							$orders['payment'] = '-';
							break;
					}

					$orders['goods'] = json_decode($orders['goods'], true);

					switch ($orders['status']) {
						case 0:
							$orders_status = '<b class="text-primary">Новый</b>';
							break;
						case 1:
							$orders_status = '<b class="text-warning">Подтвержден</b>';
							break;
						case 2:
							$orders_status = '<b class="text-warning">Ждет оплаты</b>';
							break;
						case 3:
							$orders_status = '<b class="text-warning">В обработке</b>';
							break;
						case 4:
							$orders_status = '<b class="text-success">Отправлен</b>';
							break;
						case 5:
							$orders_status = '<b class="text-success">Прибыл</b>';
							break;
						case 6:
							$orders_status = '<b class="text-success">Оплачен</b>';
							break;
						case 7:
							$orders_status = '<b class="text-success">Завершен</b>';
							break;
						case 8:
							$orders_status = '<b class="text-danger">Отменен</b>';
							break;
						case 9:
							$orders_status = '<b class="text-danger">Отказ</b>';
							break;
						default:
							$orders_status = '-';
							break;
					}

					$orders['updated'] = date('d.m.Y H:i', strtotime($orders['updated']));
					$orders['created'] = date('d.m.Y H:i', strtotime($orders['created']));

				?>

				<tr id="<?=$orders['id']?>" <?if($orders['status'] == 0) echo 'class="table-primary"';?>>
					<td>
						<b><?=$orders['id']?></b>
						<br>		
						<i><?=$orders['created']?></i>
					</td>
					<td>
						<div class="hidden-data-goods d-none">

<?

						$orders_amount = 0;

						for ($i=0; $i < count($orders['goods']); $i++) {

							$orders_goods_id = $orders['goods'][$i]['id'];

							$sql_goods = "SELECT `id`, `name`, `category` FROM `goods` WHERE `id`='{$orders_goods_id}'";
                            $query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error());
                            $goods = mysqli_fetch_assoc($query_goods);

                            $goods['name'] = json_decode($goods['name'], true);

                            $orders_amount += $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];

?>
							
							<hr class="mt-2 mb-2">
							<div class="row">
								<div class="col-5">
									<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>"><?=$goods['name'][$lang]?></a>
								</div>
								<div class="col-4"><?=$orders['goods'][$i]['availability']?> шт.</div>
								<div class="col-3"><?=$orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price']?> грн.</div>
							</div>

<?

						}

?>

						</div>
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataOrders" onclick="dataOrders(this, '<?=$orders['id']?>', '<?=$orders['client']?>', '<?=$orders['delivery_address']?>')">Детали</button>
					</td>
					<td>
						<div style="height: 120px; max-width: 300px; overflow: auto;">
<?

						$sql_messages = "SELECT * FROM `orders_messages` WHERE `order_id`='{$orders_id}' ORDER BY `created` ASC";
						$query_messages = mysqli_query($db, $sql_messages) or die(mysqli_error());

						if (mysqli_num_rows($query_messages) > 0) {

						while ($orders_messages = mysqli_fetch_assoc($query_messages)) {

							$sql_messages_user = "SELECT `name`, `surname` FROM `users` WHERE `id`='{$orders_messages['user_id']}'";
							$query_messages_user = mysqli_query($db, $sql_messages_user) or die(mysqli_error($db));
							$orders_messages_user = mysqli_fetch_assoc($query_messages_user);

							$orders_messages['message'] = str_replace("\r\n", "<br>", str_replace("'", "\'", $orders_messages['message']));
							$orders_messages_url = stristr($orders_messages['message'], 'http');
							$orders_messages_url_tmp = substr(strrchr($orders_messages_url, ' '), 1);
							$orders_messages_url = str_replace($orders_messages_url_tmp, '', $orders_messages_url);
							$orders_messages['message'] = str_replace($orders_messages_url, '<a href="'.$orders_messages_url.'" target="_blank">'.$orders_messages_url.'</a>', $orders_messages['message']);

							if ($orders_messages['type_user'] == 1) {

?>

								<div>
									<p class="mb-0">
										<small class="font-italic"><?=date('d.m.Y, H:i', strtotime($orders_messages['created']))?></small> - <span><?=$orders_messages_user['name']?> <?=$orders_messages_user['surname']?></span>
									</p>
									<div class="alert alert-primary d-inline-block mb-1 pt-1 pb-1" role="alert" style="min-width: 180px;"><?=$orders_messages['message']?></div>
								</div>

<?								

							} else {

?>

								<div class="text-right mr-2">
									<p class="mb-0">
										<span><?=$orders_messages_user['name']?></span> - <small class="font-italic"><?=date('d.m.Y, H:i', strtotime($orders_messages['created']))?></small>
									</p>
									<div class="alert alert-warning d-inline-block mb-1 pt-1 pb-1" role="alert" style="min-width: 180px;"><?=$orders_messages['message']?></div>
								</div>

<?

							}

						}

						} else {

							echo 'еще нет';

						}

?>						
						</div>
						<a data-toggle="collapse" href="#collapseMessage<?=$orders_id?>" role="button" aria-expanded="false" aria-controls="collapseMessage<?=$orders_id?>">Добавить комментарий</a>
						<div class="collapse text-center" id="collapseMessage<?=$orders_id?>">
							<form method="POST">
								<input type="hidden" name="order" value="<?=$orders_id?>">
								<textarea class="form-control" name="comment"></textarea>
								<button type="submit" class="btn btn-success btn-sm mt-1">Добавить</button>
							</form>
						</div>
					</td>
					<!-- <td><?=$orders['payment']?></td>
					<td><?=$orders['amount']?> грн.</td>
					<td><?=$orders['income']?> грн.</td>
					<td><?=$orders['reserve_balance']?> грн.</td>
					<td><?=$orders['prepayment']?> грн.</td> -->
					<td>
						<b>Способ оплаты:</b> <?=$orders['payment']?>
						<br>
						<b>Сумма оплаты:</b> <?=$orders['amount']?> грн.
						<br>
						<b>Прибыль:</b> <?=$orders['income']?> грн.
						<br>
						<b>Зарезервированная сумма:</b> <?=$orders['reserve_balance']?> грн.<i class="material-icons help_outline" data-toggle="tooltip" title="После завершения заказа или отмены, зарезервированная сумма баланса будет возвращена на ваш баланс">help_outline</i>
						<br>
						<b>Предоплата:</b> <?=$orders['prepayment']?> грн.
					</td>
					<td>
						<?if (empty($orders['invoice_number'])):?>
						<span class="text-secondary">еще не указана</span>
						<?else:?>
						<a href="https://novaposhta.ua/tracking/?cargo_number=<?=$orders['invoice_number']?>" target="_blank"><?=$orders['invoice_number']?></a>
						<?endif;?>
					</td>
					<td><?=$orders_status?></td>
				</tr>

				<?

				}

			} else {

				echo '<tr><td colspan="8" class="text-center">Список заказов пуст</td></tr>';

			}

			?>

		</tbody>
	</table>
</div>

<nav aria-label="Page navigation example">
	<ul class="pagination justify-content-center">

<?

$pervpage = '';
$page2left = '';
$page1left = '';
$currentpage = '';
$page1right = '';
$page2right = '';
$nextpage = '';

if ($page == 1) $PHP_SELF = '/account/orders/';
else $PHP_SELF = '';

// Проверяем нужны ли стрелки назад  
if ($page != 1) $pervpage = '<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page=1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only"><<</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'?page='. ($page - 1) .'" aria-label="Previous">
									<span aria-hidden="true">&#8249;</span>
									<span class="sr-only"><</span>
								</a>
							</li>';
else $pervpage = '<li class="page-item disabled">
					<span class="page-link">&laquo;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&#8249;</span>
				</li>';

// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = '<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page='. ($page + 1) .'" aria-label="Next">
										<span aria-hidden="true">&#8250;</span>
										<span class="sr-only">></span>
									</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'?page=' .$total. '" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">>></span>
									</a>
								</li>';
else $nextpage = '<li class="page-item disabled">
					<span class="page-link">&#8250;</span>
				</li>
				<li class="page-item disabled">
					<span class="page-link">&raquo;</span>
				</li>';

// Находим две ближайшие станицы с обоих краев, если они есть  
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 2) .'>'. ($page - 2) .'</a></li>';  
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page - 1) .'>'. ($page - 1) .'</a></li>';  
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 2) .'>'. ($page + 2) .'</a></li>';  
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'?page='. ($page + 1) .'>'. ($page + 1) .'</a></li>'; 

//Текущая страница
$currentpage = '<li class="page-item active"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<div id="dataOrders" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Детали заказа</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<th>Номер заказа</th>
								<td class="order_number"></td>
							</tr>
							<tr>
								<th>Товары</th>
								<td class="goods">
									<div class="row">
										<div class="col-5"><b>Наименование товара</b></div>
										<div class="col-4"><b>Количество</b></div>
										<div class="col-3"><b>Цена</b></div>
									</div>
									<div class="body-goods"></div>
								</td>
							</tr>
							<tr>
								<th>Получатель</th>
								<td class="client"></td>
							</tr>
							<tr>
								<th>Адрес доставки</th>
								<td class="delivery_address"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
	
<? include_once __DIR__ . '/../include/main_after_content.php'; ?>