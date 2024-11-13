<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<div class="row">
	<div class="col-sm-6">
		<a href="javascript:void(0);" class="btn btn-primary btn-block">Замовлення</a>
	</div>
	<div class="col-sm-6">
		<a href="/account/storage_goods/" class="btn btn-light btn-block">Товари</a>
	</div>
</div>

<?

$order_delivery = ['-', 'Новая почта', 'Укрпочта', 'Самовывоз'];
$orders_payment = ['-', 'Наложенный платеж', 'Услуга', 'Оплачено'];

$num = 5;

$sql = "SELECT COUNT(1) as count FROM `orders` WHERE `goods` LIKE '%\"user_id\":\"{$user['storage_id']}\"%'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$results = mysqli_fetch_assoc($query);

$total = intval(($results['count'] - 1) / $num) + 1;

$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  

$start = $page * $num - $num;

?>

<style type="text/css">
.goods-list-img {
	width: 50px;
    height: 50px;
    overflow: hidden;
    cursor: pointer;
    border: 1px solid #eee;
}
.goods-list-img.max-width {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    justify-content: center;
}
.goods-list-img.max-height img {
    width: 100%;
    height: auto;
}
.goods-list-img.max-width img {
    width: auto;
    height: 100%;
}
</style>

<div class="table-responsive">
	<table class="table table-striped table-hover mt-3" style="font-size: 14px;">
		<thead class="thead-light">
			<tr>
				<th style="width: 75px">№</th>
				<th>Детали и Статус</th>
				<th>Товары</th>
				<th>Клиент</th>
				<th>Доставка и Оплата</th>
			</tr>
		</thead>
		<tbody>

			<?

			$sql = "SELECT * FROM `orders` WHERE `goods` LIKE '%\"user_id\":\"{$user['storage_id']}\"%' ORDER BY `status` ASC, `created` DESC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error());

			if (mysqli_num_rows($query) > 0) {

				while ($orders = mysqli_fetch_assoc($query)) {

					$orders_id = $orders['id'];

					$orders['client'] = json_decode($orders['client'], true);
					$orders['delivery_address'] = json_decode($orders['delivery_address'], true) ?: [];
					$orders['goods'] = json_decode($orders['goods'], true);

					switch ($orders['status']) {
						case 0:
							$orders_status = '<b class="text-primary">Новый</b>';
							break;
						case 1:
							$orders_status = '<b class="text-info">Подтвержден</b>';
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

					$orders['created'] = date('H:i, d.m.Y', strtotime($orders['created']));

				?>

				<tr id="<?=$orders['id']?>" <?if($orders['status'] == 0) echo 'class="table-primary"';?>>
					<td>
						<h4 class="mb-0"><span class="badge badge-dark"><?=$orders['id']?></span></h4>
						<br>
						<i><?=$orders['created']?></i>
					</td>
					<td class="text-center">
						<a href="?number=<?=$orders['id']?>" class="btn btn-success">Открыть <i class="fa fa-external-link" aria-hidden="true"></i></a>
						<br>
						<br>
						<!-- <form method="POST">
							<input type="hidden" name="">
						</form> -->
						<?=$orders_status?>
					</td>
					<td style="min-width: 400px;width: 400px;">
						<div class="list-group">
<?

					for ($i=0; $i < count($orders['goods']); $i++) {

						$orders_goods_id = $orders['goods'][$i]['id'];

						$sql_goods = "SELECT `name`, `photo` FROM `goods` WHERE `id`='{$orders_goods_id}'";
                        $query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
                        $goods = mysqli_fetch_assoc($query_goods);

                        $goods['name'] = json_decode($goods['name'], true);

                        $orders_amount += $orders['goods'][$i]['availability'] * $orders['goods'][$i]['goods_price'];

                        $goods['photo'] = json_decode($goods['photo'], true);

                        if (!file_exists('../data/images/goods/'.$goods['photo']['img0'])) {
                            $goods['photo']['img0'] = 'no_image.png';
                        }

                    	list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img0']);

                    	$goods_photo_size = $goods_photo_w > $goods_photo_h ? 'max-width' : 'max-height';

?>
							
							<?if($i === 1):?>
							<a data-toggle="collapse" href="#collapseGoods<?=$orders['id']?>" role="button" aria-expanded="false" aria-controls="collapseGoods<?=$orders['id']?>">В заказе еще <?=(count($orders['goods'])-1)?> товаров</a>
							<div class="collapse" id="collapseGoods<?=$orders['id']?>">
							<?endif;?>

							<a href="javascript:void(0);" class="list-group-item list-group-item-action p-1 pr-5" style="min-height: 60px;position: relative;">
								<div class="goods-list-img float-left <?=$goods_photo_size?>">
									<img src="/data/images/goods/<?=$goods['photo']['img0']?>">
								</div>
								<p class="mt-1 mb-0" style="margin-left: 60px;"><?=$goods['name'][$lang]?></p>
								<div style="position: absolute;right: 8px;top: 8px;">
									<b><?=$orders['goods'][$i]['availability']?> шт.</b>
								</div>
							</a>

							<?if($i == count($orders['goods'])-1):?>
							</div>
							<?endif;?>

<?

					}

?>
						</div>
					</td>
					<td>
						<p><?=$orders['client']['fio']?></p>
						<p><?=$orders['client']['phone']?></p>
					</td>
					<td style="width: 350px;">
						<p>
							<?if ($orders['delivery'] == 1):?>
							<img src="/assets/images/novaposhta_icon.png" alt="NP" width="24">
							<?elseif ($orders['delivery'] == 2):?>
							<img src="/assets/images/ukrposhta_icon.png" alt="NP" width="24">
							<?endif;?>
							<span><?=$order_delivery[$orders['delivery']]?>: <?=implode(', ', $orders['delivery_address'])?></span>
						</p>

						<?if (in_array($orders['delivery'], [1, 2])):?>
							<?if (!empty($orders['invoice_number'])):?>
								<span class="text-secondary">ТТН: </span>
								<a href="<?=($orders['delivery'] == 1 ? 'https://novaposhta.ua/tracking/?cargo_number=' : 'https://track.ukrposhta.ua/tracking_UA.html?barcode=')?><?=$orders['invoice_number']?>" target="_blank"><?=$orders['invoice_number']?></a>
							<?else:?>
								<form method="POST">
									<div class="input-group mb-3">
										<input type="text" class="form-control" placeholder="Введите ТТН" value="<?=$orders['invoice_number']?>">
										<div class="input-group-append">
											<button class="btn btn-success" type="submit">
												<i class="fa fa-floppy-o" aria-hidden="true"></i>
											</button>
										</div>
									</div>
								</form>
							<?endif;?>
						<?endif;?>
						
						<p><b><?=$orders_payment[$orders['payment']]?>:</b> <?=$orders_amount?> грн.</p>
					</td>
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

if ($page == 1) $PHP_SELF = '/account/storage_orders/';
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

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>