<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?/* if ($user['admin'] == 1):?>
<div class="row justify-content-between text-center">
	<div class="col-sm-6 mb-3">
		<button type="button" class="btn btn-primary btn-sm text-uppercase" data-toggle="modal" data-target="#modalTopCategory"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> ТОП 5 категорий <img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"></button>
	</div>
	<div class="col-sm-4 mb-3">
		<button type="button" class="btn btn-danger btn-sm text-uppercase" data-toggle="modal" data-target="#modalBestsellers"><i class="fa fa-trophy"></i> Хиты продаж <i class="fa fa-trophy"></i></button>
	</div>
	<div class="col-sm-6 mb-3">
		<button type="button" class="btn btn-warning btn-sm text-uppercase" data-toggle="modal" data-target="#modalToolsForBusiness"><i class="fa fa-wrench"></i> Инструменты OLX <i class="fa fa-wrench"></i></button>
	</div>
</div>
<?endif;*/?>

<div class="goods" id="goods" style="min-height: 400px;">

	<div class="row">
		<!-- <div class="col-sm-2">
			<button class="btn btn-warning btn-block" disabled>Каталог <i class="fa fa-angle-down"></i></button>
		</div> -->
		<div class="col-sm-12">
			<form method="POST" onsubmit="searchGoodsInCatalog(event, this)">
				<div class="row">
					<div class="col-lg-3 col-md-4 mb-3">
						<select name="type_search_goods" class="form-control form-control-lg" required>
							<option value="3">Название</option>
							<option value="1">Код товара</option>
							<option value="2">Артикул</option>
						</select>
					</div>
					<div class="col-lg-7 col-md-6 mb-3">
						<div class="input-group border-success">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fa fa-search"></i>
								</span>
							</div>
							<input type="search" name="search_goods" class="form-control form-control-lg" placeholder="Поиск по товара..." required>
						</div>
					</div>
					<div class="col-md-2 text-center">
						<button type="submit" class="btn btn-success btn-lg w-100" style="max-width: 155px;">Найти</button>
					</div>
				</div>
			</form>
			<!-- <p class="text-muted font-italic"><small>Поиск по названию может длится долго, так как он осуществляется по всех товарах платформы. В результате поиска по названию покажется только 100 товаров, введите значение поиска более точнее. Если вы ищете по названию товара, то введите полное название товара или часть названия, но в той последовательности, как оно отображаеться в товаре.</small></p> -->
		</div>
		<!-- <div class="col-sm-2">
			<select class="form-control" disabled>
				<option value="none">Фильтры</option>
			</select>
		</div>
		<div class="col-sm-1">
			<button class="btn btn-dark btn-block pt-1 pb-1" disabled><i class="fa fa-th" style="font-size: 27.5px;position: relative;top: 2px;"></i></button>
		</div>
		<div class="col-sm-1">
			<button class="btn btn-light btn-block pt-1 pb-1" disabled><i class="fa fa-list" style="font-size: 27.5px;position: relative;top: 2px;"></i></button>
		</div> -->
	</div>
 
	<?/*if ($_GET['linkname'] != 'tracksuits6576879' and $user['admin'] == 1):?>
	<h3 class="text-center text-white mb-0" style="background: url(/assets/images/krasnayalenta-lenta.png) no-repeat;background-size: contain;background-position-x: center;min-height: 80px;padding-top: 11px;">Сезон в разгаре: <a href="/account/goods/tracksuits6576879" class="btn btn-dark btn-sm font-weight-bold" target="_blank">Перейти</a></h3>
	<?endif;*/?>

	<?if (!empty($_SESSION['main_page']['url'])):?>
	<p class="text-center mt-3">
		<a href="<?=$_SESSION['main_page']['url']?>" class="btn btn-danger"><?=$_SESSION['main_page']['name']?></a>
	</p>
	<?endif;?>

	<div class="goods-result">

<?

		$linkname = 'catalog';
		if (!empty($_GET['linkname'])) $linkname = $_GET['linkname'];

		$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
		$query = mysqli_query($db, $sql) or die(mysqli_error());
		$catalog = mysqli_fetch_assoc($query);
		$catalog_id = $catalog['id'];

		$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_id}' AND `locked`=0 ORDER BY `sort` ASC";
		$query = mysqli_query($db, $sql) or die(mysqli_error());

		if (mysqli_num_rows($query) > 0) {

?>

	<?if ($linkname != 'catalog'): ?>

	<div class="card mt-3">
		<div class="card-header p-1 text-center">YML выгрузка всей категории <span class="text-primary"><?=$breadcrumb['names'][(count($breadcrumb['names'])-1)]?></span> и всех ее подкатегорий</div>
		<div class="card-body pt-2 pb-2">
			<div class="row">
				<div class="col-sm-3">
					<b>YML выгрузка: </b>
				</div>
				<div class="col-sm-9">
					<!-- <div class="row mb-1">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>" id="copyPromLink" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?></a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPromLink" onclick="copyLink(this)">Копировать</button>
						</div>
					</div> -->
					<?//if (file_exists('../assets/files/export/yml_prom/'.$linkname.'.xml')):?>
					<?

					if ($user['gtm'] == 'google') {
						$google_gtag_only = ' onclick="gtag(\'event\', \'Клик\', {\'event_category\' : \'Вызгрузка на Prom.ua\'})"';
						$google_gtag_clipboard = ' onclick="function(){copyLink(this);gtag(\'event\', \'Клик\', {\'event_category\' : \'Вызгрузка на Prom.ua\'});}"';
					} else {
						$google_gtag_only = '';
						$google_gtag_clipboard = ' onclick="copyLink(this)"';
					}

					?>
					<div class="row">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>.xml" id="copyPromLinkXml" target="_blank"<?=$google_gtag_only?>>https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>.xml</a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPromLinkXml"<?=$google_gtag_clipboard?>>Копировать</button>
							<a href="/assets/files/export/yml_prom/<?=$linkname?>.xml" class="btn btn-success btn-sm"<?=$google_gtag_only?> download>Скачать</a>
						</div>
					</div>
					<?//endif;?>
					<?if ($user['admin'] == 1):?>
					<p class="mt-3 mb-0">Только для админов товары с маржой больше 100 грн:</p>
					<p class="mb-0">
						<a href="https://onlinenaxodka.com/assets/files/export/yml_prom_margine_limit/<?=$linkname?>" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_prom_margine_limit/<?=$linkname?></a>
					</p>
					<?endif;?>
				</div>
			</div>
			<!-- <div class="row mt-2">
				<div class="col-sm-3">
					<b>На Rozetka.com.ua: </b>
				</div>
				<div class="col-sm-9">
					<div class="row mb-1">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>" id="copyRozetkaLink" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?></a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyRozetkaLink" onclick="copyLink(this)">Копировать</button>
						</div>
					</div>
					<?if (file_exists('../assets/files/export/yml_rozetka/'.$linkname.'.xml')):?>
					<div class="row">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>.xml" id="copyRozetkaLinkXml" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>.xml</a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyRozetkaLinkXml" onclick="copyLink(this)">Копировать</button>
							<a href="/assets/files/export/yml_rozetka/<?=$linkname?>.xml" class="btn btn-success btn-sm" download>Скачать</a>
						</div>
					</div>
					<?endif;?>
				</div>
			</div> -->
		</div>
	</div>

	<?endif;?>

	<?if ($user['status'] > 0 and $user['admin']==1):?>
	<p class="text-center pt-3">
		Ваша специализированная партнерская ссылка по которой люди смогут присоединиться в Вашу команду: <a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$linkname?>/<?=$user['nickname']?>/google" id="copyPartnerLink" target="_blank"><?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$linkname?>/<?=$user['nickname']?>/google</a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
	</p>
	<?endif;?>

	<?if ($linkname == 'catalog'): ?>
	<p class="text-center">

<?

//$sql_count_goods = "SELECT COUNT(1) AS count_goods FROM `goods` WHERE `status`=1 AND `availability` > 0 AND `category` IN (SELECT `linkname` FROM `catalog` WHERE `locked`=0 AND `buffer`=0)";
$sql_count_goods = "SELECT COUNT(1) AS count_goods FROM goods g LEFT JOIN catalog c ON g.category = c.linkname 
					WHERE g.status = 1 
						AND g.availability > 0 
						AND c.locked = 0
						AND c.buffer = 0";
$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));
$goods_count_all = mysqli_fetch_assoc($query_count_goods);

?>
		Товары в наличии: <b><?=$goods_count_all['count_goods']?></b>
	</p>
	<?endif;?>

	<ul class="list-catalog mt-4 text-center">

<?

			while ($catalog = mysqli_fetch_assoc($query)) {

				if (!file_exists('../data/images/catalog/'.$catalog['img'])) $catalog['img'] = 'no_image.png';

				list($catalog_width_img, $catalog_height_img) = getimagesize('../data/images/catalog/'.$catalog['img']);

				if ($catalog_width_img > $catalog_height_img) {
					$catalog_width_img = '100%';
					$catalog_height_img = 'auto';
				} else {
					$catalog_width_img = 'auto';
					$catalog_height_img = '100%';
				}

				$sql_count_subcategories = "SELECT COUNT(1) AS count_catalogs FROM `catalog` WHERE `level_id`='{$catalog['id']}'";
				$query_count_subcategories = mysqli_query($db, $sql_count_subcategories) or die(mysqli_error($db));
				$count_subcategories = mysqli_fetch_assoc($query_count_subcategories);

				//$count_goods_in_catalog = countGoodsInCategory($db, $catalog['id'], 0);

				if ($count_subcategories['count_catalogs'] > 0) {

					$count_goods_in_catalog_num = $catalog['count_goods'];
					$count_goods_in_catalog['count'] = 1;

				} else {

					$sql_count_goods = "SELECT COUNT(1) AS count FROM `goods` WHERE `category`='{$catalog['linkname']}' AND `status`=1 AND `availability` > 0";
					$query_count_goods = mysqli_query($db, $sql_count_goods) or die(mysqli_error($db));
					$count_goods_in_catalog = mysqli_fetch_assoc($query_count_goods);

				}


				if ($count_goods_in_catalog['count'] > 0) {

?>

				<li style="position: relative;">
					<?if ($count_subcategories['count_catalogs'] > 0):?>
						<span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #cccccc;color: #000;border-radius: 15px;padding: 0 10px;"><?=$count_goods_in_catalog_num?></span>
					<?else:?>
						<span style="display: inline-block;position: absolute;top: 15px; right: 15px;min-width: 30px;height: 30px;line-height: 32px; background-color: #ffc107;color: #000;border-radius: 15px;padding: 0 10px;"><?=$count_goods_in_catalog['count']?></span>
					<?endif;?>
					<a href="/account/goods/<?=$catalog['linkname']?>">
						<div>
							<img src="/data/images/catalog/<?=$catalog['img']?>?v=<?=strtotime($catalog['updated'])?>" style="width: <?=$catalog_width_img?>; height: <?=$catalog_height_img?>;">
						</div>
						<p class="text-uppercase"><span><?=$catalog['name_'.$lang]?></span></p>
					</a>
				</li>

<?

				}

			}

?>

	</ul>

<?

		} else {

			if (!isset($_GET['goods']) and empty($_GET['goods'])) {

?>

	<div class="card mt-3">
		<div class="card-header p-1 text-center">YML выгрузка всей категории <span class="text-primary"><?=$breadcrumb['names'][(count($breadcrumb['names'])-1)]?></span> и всех ее подкатегорий</div>
		<div class="card-body pt-2 pb-2">
			<div class="row">
				<div class="col-sm-3">
					<b>YML выгрузка: </b>
				</div>
				<div class="col-sm-9">
					<!-- <div class="row mb-1">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>" id="copyPromLink" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?></a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPromLink" onclick="copyLink(this)">Копировать</button>
						</div>
					</div> -->
					<?if (file_exists('../assets/files/export/yml_prom/'.$linkname.'.xml')):?>
					<?

					if ($user['gtm'] == 'google') {
						$google_gtag_only = ' onclick="gtag(\'event\', \'Клик\', {\'event_category\' : \'Вызгрузка на Prom.ua\'})"';
						$google_gtag_clipboard = ' onclick="function(){copyLink(this);gtag(\'event\', \'Клик\', {\'event_category\' : \'Вызгрузка на Prom.ua\'});}"';
					} else {
						$google_gtag_only = '';
						$google_gtag_clipboard = ' onclick="copyLink(this)"';
					}

					?>
					<div class="row">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>.xml" id="copyPromLinkXml" target="_blank"<?=$google_gtag_only?>>https://onlinenaxodka.com/assets/files/export/yml_prom/<?=$linkname?>.xml</a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPromLinkXml"<?=$google_gtag_clipboard?>>Копировать</button>
							<a href="/assets/files/export/yml_prom/<?=$linkname?>.xml" class="btn btn-success btn-sm"<?=$google_gtag_only?> download>Скачать</a>
						</div>
					</div>
					<?endif;?>
					<?if ($user['admin'] == 1):?>
					<p class="mt-3 mb-0">Только для админов товары с маржой больше 100 грн:</p>
					<p class="mb-0">
						<a href="https://onlinenaxodka.com/assets/files/export/yml_prom_margine_limit/<?=$linkname?>" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_prom_margine_limit/<?=$linkname?></a>
					</p>
					<?endif;?>
				</div>
			</div>
			<!-- <div class="row mt-2">
				<div class="col-sm-3">
					<b>На Rozetka.com.ua: </b>
				</div>
				<div class="col-sm-9">
					<div class="row mb-1">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>" id="copyRozetkaLink" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?></a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyRozetkaLink" onclick="copyLink(this)">Копировать</button>
						</div>
					</div>
					<?if (file_exists('../assets/files/export/yml_rozetka/'.$linkname.'.xml')):?>
					<div class="row">
						<div class="col-sm-8">
							<a href="https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>.xml" id="copyRozetkaLinkXml" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_rozetka/<?=$linkname?>.xml</a>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyRozetkaLinkXml" onclick="copyLink(this)">Копировать</button>
							<a href="/assets/files/export/yml_rozetka/<?=$linkname?>.xml" class="btn btn-success btn-sm" download>Скачать</a>
						</div>
					</div>
					<?endif;?>
				</div>
			</div> -->
		</div>
	</div>

	<?if ($user['status'] > 0 and $user['admin']==1):?>
	<p class="text-center pt-3">
		Ваша специализированная партнерская ссылка по которой люди смогут присоединиться в Вашу команду: <a href="<?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$linkname?>/<?=$user['nickname']?>/google" id="copyPartnerLink" target="_blank"><?=$server_protocole?>://<?=$_SERVER['SERVER_NAME']?>/<?=$linkname?>/<?=$user['nickname']?>/google</a> <a href="#" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPartnerLink" onclick="copyLink(this)">Копировать</a>
	</p>
	<?endif;?>

	<ul class="list-goods mt-4 text-center">

<?

$num = 20;

$sql = "SELECT COUNT(1) as count FROM `goods` WHERE `category`='{$linkname}' AND `status`=1";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$results = mysqli_fetch_assoc($query);

$total = intval(($results['count'] - 1) / $num) + 1;

$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

if(empty($page) or $page < 0) $page = 1;  
if($page > $total) $page = $total;  

$start = $page * $num - $num;

			$sql = "SELECT * FROM `goods` WHERE `category`='{$linkname}' AND `status`=1 ORDER BY `availability` DESC, SUBSTRING_INDEX(SUBSTRING_INDEX(name, '\"', -2), '\"', 1) ASC LIMIT $start, $num";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			if (mysqli_num_rows($query) > 0) {

				while ($goods = mysqli_fetch_assoc($query)) {

					$goods['photo'] = json_decode($goods['photo'], true);
					$goods['name'] = json_decode($goods['name'], true);

					$goods_photo_route = '/data/images/goods_thumb/';

					if (!file_exists('..' . $goods_photo_route . $goods['photo']['img0'])) {
						$goods_photo_route = '/data/images/goods/';
					}

					if (!file_exists('..' . $goods_photo_route . $goods['photo']['img0'])) {
						$goods['photo']['img0'] = 'no_image.png';
					}

					list($goods_photo_w, $goods_photo_h) = getimagesize('..' . $goods_photo_route . $goods['photo']['img0']);

					if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
					else $goods_photo_size = 'max-height';

					if ($goods['currency'] == 1) {

						$kurs_currency = 1;

					} else if ($goods['currency'] == 2) {

						$kurs_currency = $api_exchange_rate_usd['sale'];

						if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

							$kurs_currency = $goods['currency_top_kurs'];

						}

					} else if ($goods['currency'] == 3) {

						$kurs_currency = $api_exchange_rate_eur['sale'];

						if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

							$kurs_currency = $goods['currency_top_kurs'];

						}

					}

					/*$price_min = ceil(($goods['price_sale'] - (($goods['price_sale'] - $goods['price_purchase']) * $catalog['rate'])) * $kurs_currency);
					$price_sale = ceil($goods['price_sale'] * $kurs_currency);*/

					$price_purchase = ceil($goods['price_purchase'] * $kurs_currency);
					$price_sale = ceil($goods['price_sale'] * $kurs_currency);
					$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $catalog['rate']));

					if ($user['p_rate'] > 0) {

						if ($goods['price_agent'] > 0 and $goods['price_agent'] < $goods['price_purchase']) {

							$price_purchase = ceil($goods['price_agent'] * $kurs_currency);

						}

						$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $user['p_rate']));

					}

?>

				<li>
					<a href="/account/goods/<?=$linkname?>/<?=$goods['id']?>">
						<div class="<?=$goods_photo_size?>">
							<img src="<?=($goods_photo_route . $goods['photo']['img0'])?>">
						</div>
						<p class="goods-title">

							<?

							$list_goods_name = $goods['name'][$lang];

							if (strlen(utf8_decode($list_goods_name)) > 56) $list_goods_name = mb_substr($list_goods_name, 0, 56) . '...';

							?>

							<span><?=$list_goods_name?></span>
						</p>
					</a>

					<?

						if ($goods['availability'] == 0) {
							$goods_availability_word = '<span class="badge alert-primary">Под заказ</span>';
						} elseif ($goods['availability'] > 0 and $goods['availability'] < 5) {
							$goods_availability_word = '<span class="badge alert-warning">Заканчивается</span>';
						} elseif ($goods['availability'] >= 5) {
							$goods_availability_word = '<span class="badge alert-success">В наличии</span>';
						} else {
							$goods_availability_word = '<span class="badge alert-danger">Нет в наличии</span>';
						}

					?>

					<p class="text-dark mb-2"><b><?=$goods_availability_word?></b></p>
					<div class="row">
						<div class="col-6">
							<p class="text-secondary mb-1">Клубная цена</p>
						</div>
						<div class="col-6">
							<p class="text-secondary mb-1">Реком. цена</p>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<h4 style="color: red;font-size: 21px;"><b><?=$price_min?> грн</b></h4>
						</div>
						<div class="col-6">
							<h4 style="color: #28a745;font-size: 21px;"><b><?=$price_sale?> грн</b></h4>
						</div>
					</div>

					<div class="row pt-3 pb-3 ml-0 mr-0" style="background: #f3f3f3">
						<div class="col-8 pr-0">
							<a href="/account/goods/<?=$linkname?>/<?=$goods['id']?>" class="btn btn-success btn-block">Открыть товар</a>
						</div>
						<div class="col-4 pl-2">
							<form method="POST">
								<input type="hidden" name="goods" value="<?=$goods['id']?>">
								<input type="hidden" name="price" value="<?=$price_sale?>">
								<button type="submit" class="btn btn-warning btn-block" style="padding: .2rem .75rem;"><i class="fa fa-cart-plus" style="font-size: 30px;"></i></button>
							</form>
						</div>
					</div>
				</li>

<?

				}

			} else {

				echo '<br><br><br><br>На данный момент в этом каталоге нет товаров<br><br><br><h3>Здесь может быть ваш товар или вашего поставщика</h3><br><a href="/account/provider_terms/" class="btn btn-lg btn-success mr-5" target="_blank">Разместить свой товар</a><a href="#" class="btn btn-lg btn-primary">Стать агентом поставщика</a><br><br><p>Для уточнения деталей обращайтесь в наш ЧАТ справа внизу есть кнопка</p>';

			}

?>

	</ul>

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

if ($page == 1) $PHP_SELF = '/account/goods/'.$linkname.'/';
else $PHP_SELF = '';

// Проверяем нужны ли стрелки назад  
if ($page != 1) $pervpage = '<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'p-1" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only"><<</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="'.$PHP_SELF.'p-'. ($page - 1) .'" aria-label="Previous">
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
									<a class="page-link" href="'.$PHP_SELF.'p-'. ($page + 1) .'" aria-label="Next">
										<span aria-hidden="true">&#8250;</span>
										<span class="sr-only">></span>
									</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="'.$PHP_SELF.'p-' .$total. '" aria-label="Next">
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
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'p-'. ($page - 2) .'>'. ($page - 2) .'</a></li>';  
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'p-'. ($page - 1) .'>'. ($page - 1) .'</a></li>';  
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'p-'. ($page + 2) .'>'. ($page + 2) .'</a></li>';  
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href='.$PHP_SELF.'p-'. ($page + 1) .'>'. ($page + 1) .'</a></li>'; 

//Текущая страница
$currentpage = '<li class="page-item active"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';

// Вывод меню  
echo $pervpage.$page2left.$page1left.$currentpage.$page1right.$page2right.$nextpage;

?>

	</ul>
</nav>

<?

			} else {

				$goods_id = intval($_GET['goods']);

				$sql = "SELECT * FROM `goods` WHERE `id`='{$goods_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$goods = mysqli_fetch_assoc($query);

				$sql = "SELECT * FROM `goods_description` WHERE `goods_id`='{$goods_id}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				while ($goods_description = mysqli_fetch_assoc($query))
					$goods_description_view[$goods_description['lang']] = $goods_description['description'];

				$sql = "SELECT `id` FROM `goods_visits` WHERE `user_id`='{$user_id}' AND `goods_id`='{$goods_id}' AND DATE(`created`)=CURDATE()";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$count_goods_visits = mysqli_num_rows($query);

				if ($count_goods_visits == 0 and $goods['user_id'] != $user_id) {

					$sql = "INSERT INTO `goods_visits` SET `user_id`='{$user_id}', `goods_id`='{$goods_id}', `created`='{$current_date}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				}

				$goods['photo'] = json_decode($goods['photo'], true);
				$goods['name'] = json_decode($goods['name'], true);
				$goods['parameters'] = json_decode($goods['parameters'], true);
				//$goods['description'] = json_decode($goods['description'], true);
				//$goods['description'] = str_replace("\r\n", "<br>", $goods['description']);
				$goods['video'] = json_decode($goods['video'], true);
				$goods['export'] = json_decode($goods['export'], true);

				if (empty($goods_description_view[$lang])) $goods_description_view[$lang] = 'В ближайшее время описание этого товара будет добавлено, а пока поищите его на просторах интернета.';
				

?>

<!-- TEST				 -->
<br><br>
<div class="row goods">
	<div class="col-sm-12">
		<h3 class="text-center mb-3"><?=$goods['name'][$lang]?></h3>
	</div>
</div>
<div class="row goods">
	<div class="col-sm-3">

<?

if (!file_exists('../data/images/goods/'.$goods['photo']['img0'])) {
	$goods['photo']['img0'] = 'no_image.png';
}

list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img0']);

if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
else $goods_photo_size = 'max-height';

?>

		<div class="main-img <?=$goods_photo_size?>">
			<img src="/data/images/goods/<?=$goods['photo']['img0']?>" data-toggle="modal" data-target="#bigImg" onclick="showBigPhotoGoods(this)">
		</div>
		<ul class="other-imgs mt-3">
<?

			for ($i=0; $i < count($goods['photo']); $i++) {

				if (!file_exists('../data/images/goods/'.$goods['photo']['img'.$i])) {
					$goods['photo']['img'.$i] = 'no_image.png';
				}

				list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img'.$i]);

				if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
				else $goods_photo_size = 'max-height';

?>

				<li class="<?if($i==0) echo 'active';?>" onclick="selectPhotoGoods(this)">
					<div class="<?=$goods_photo_size?>">
						<img src="/data/images/goods/<?=$goods['photo']['img'.$i]?>">
					</div>
				</li>

<?

			}

?>
			<?if ($goods['user_id'] == 45):?>
			<li onclick="selectPhotoGoods(this)">
				<div class="max-width">
					<img src="/assets/images/sizes_<?=$lang?>.png">
				</div>
			</li>
			<?endif;?>
		</ul>
		<p class="mt-4">
			<!-- <button class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#videoViewsGoods"><i class="fa fa-eye"></i> Видео-обзор товара</button> -->
			<button class="btn btn-success btn-block btn-sm" data-toggle="modal" data-target="#downloadGoods"><i class="fa fa-picture-o"></i> Выгрузка этого товара</button>
		</p>
		<div class="mt-4 mb-3">
			<h5 class="font-weight-bold text-center text-uppercase mb-2">Расписание<br>Прочая информация</h5>
<?

			$sql = "SELECT * FROM `info_providers` WHERE `user_id`='{$goods['user_id']}'";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$info_providers = mysqli_fetch_assoc($query);
			$count_info_providers = mysqli_num_rows($query);

			if ($count_info_providers == 0) {

				echo '<p class="text-center">Еще нет расписания/прочей информации</p>';

			} elseif ($count_info_providers == 1) {

				echo $info_providers['description'];

			}

?>
		</div>
	</div>
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-8">
				<p class="text-right"><i>Товар обновлен: <b><?=date('d.m.Y H:i', strtotime($goods['updated']))?></b></i><i class="material-icons help_outline" data-toggle="tooltip" title="Обновляеться по товару наличие, цена и прочая другая информация.">help_outline</i></p>
				<ul class="list-group">
					<li class="list-group-item justify-content-between text-right">

						<?

						if ($goods['availability'] == 0) {
							$goods_availability_word = '<span class="badge alert-primary">Под заказ</span>';
						} elseif ($goods['availability'] > 0 and $goods['availability'] < 5) {
							$goods_availability_word = '<span class="badge alert-warning">Заканчивается</span>';
						} elseif ($goods['availability'] >= 5) {
							$goods_availability_word = '<span class="badge alert-success">В наличии</span>';
						} else {
							$goods_availability_word = '<span class="badge alert-danger">Нет в наличии</span>';
						}

						?>

						<span class="float-left">В наличии: <b><?=$goods['availability']?> шт.</b></span> <?=$goods_availability_word?>
						
					</li>
					<li class="list-group-item justify-content-between">
						<span>Код товара:</span>
						<span class="badge text-muted float-right"><?=$goods['id']?></span>
					</li>
					<li class="list-group-item justify-content-between">
						<span>Артикул:</span>
						<span class="badge text-muted float-right"><?=$goods['vendor_code']?></span>
					</li>
					<!-- <li class="list-group-item justify-content-between">
						<h4 class="font-weight-bold text-center text-uppercase mb-2">Расписание/Прочая информация</h4>
<?

						$sql = "SELECT * FROM `info_providers` WHERE `user_id`='{$goods['user_id']}'";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));
						$info_providers = mysqli_fetch_assoc($query);
						$count_info_providers = mysqli_num_rows($query);

						if ($count_info_providers == 0) {

							echo '<p class="text-center">Еще нет расписания/прочей информации</p>';

						} elseif ($count_info_providers == 1) {

							echo $info_providers['description'];

						}

?>
					</li> -->
<?

					foreach ($goods['parameters'][$lang] as $parameters_key => $parameters_value) {
						
?>

						<li class="list-group-item justify-content-between">
							<span><?=$parameters_key?></span>
							<span class="badge text-muted float-right" style="white-space: normal;"><?=$parameters_value?></span>
						</li>

<?

					}

?>
					<?if ($goods_id == 4995):?>
					<li class="list-group-item justify-content-between">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="//www.youtube.com/embed/XBAwGeQNUW8?rel=0" allowfullscreen></iframe>
						</div>
					</li>
					<?endif;?>
					<?if ($goods_id == 7299):?>
					<li class="list-group-item justify-content-between">
						<h4 class="font-weight-bold text-center text-uppercase">Как работает программа</h4>
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="//www.youtube.com/embed/jOrgcSpMwMg?rel=0" allowfullscreen></iframe>
						</div>
					</li>
					<li class="list-group-item">
						<p class="text-center mb-0">
							<a href="http://codestudio.net/products/?a=5847" class="btn btn-success btn-lg mr-2" target="_blank">Купить TOOLX</a>
							<b>+</b>
							<a href="/account/goods/modemolx" class="btn btn-warning btn-lg ml-2" target="_blank">Дозаказать USB модем</a>
						</p>
					</li>
					<?endif;?>
					<?if ($goods['category'] == 'modemolx'):?>
					<li class="list-group-item justify-content-between">
						<p class="font-weight-bold">Настройка модема под ключ: <a href="https://onlinenaxodka.com/account/goods/fghgffgfgjgf/9316" class="btn btn-primary btn-sm float-right" target="_blank">Заказать</a></p>
					</li>
					<?endif;?>
					<li class="list-group-item justify-content-between"><?=$goods_description_view[$lang]?></li>
<?

$category_arr_tracksuits = array('tracksuitsclasic56575676', 'izi6457457', 'izi2019', 'tracksuits_windbreakers20181909999', 'iziclasic5535', 'tracksuits_condivo201846758758', 'classic_5466464', 'trac567575', 'tracksuits_winter201824424252', 'classic_tracksuits467575', 'deficient_models67686', 'sports_suit_big_size5675775', 'summercostumes_summer');

$category_arr_greekgoods = array('olive_oil9899979', 'olives_and_olives5757', 'balsamic_vinegar858585', 'sauces_and_ketchups868686', 'conservation8879797', 'mastihi_products575757', 'olive_soap466464', 'church_utensils57575', 'spices_in_4654564jars', 'mountain_spices655', 'cheese_butter57757', 'coffee_and_cocoa6', 'jam_and_confiture67686', 'sweets67', 'wine', 'vodka');

$category_olx = array('evgeniytkachukolx', 'valentin_st', 'programdmitryinstructo');

?>					
					<?if (in_array($goods['category'], $category_olx)):?>
					<li class="list-group-item justify-content-between">
						<h4 class="font-weight-bold">Как оплатить обучение Наставника</h4>
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="//www.youtube.com/embed/hegdqgvQDls?rel=0" allowfullscreen></iframe>
						</div>
					</li>
					<?endif;?>
					<?if (in_array($goods['category'], $category_arr_greekgoods)):?>
					<li class="list-group-item justify-content-between">
						<p class="font-weight-bold">Актуальный ассортимент товаров:</p>
						<p>
							<a href="https://docs.google.com/spreadsheets/d/1l2tTBSLJ_daIhYGyp7Qjnl3CQY-UAKNgMvMMkePihmA/edit?usp=sharing" target="_blank">https://docs.google.com/spreadsheets/d/1l2tTBSLJ_daIhYGyp7Qjnl3CQY-UAKNgMvMMkePihmA/edit?usp=sharing</a>
						</p>
					</li>
					<?endif;?>
				</ul>
			</div>
			<div class="col-sm-4">
				<form method="POST" class="buy-goods text-center">
					<input type="hidden" name="goods" value="<?=$goods['id']?>">
					<div class="card mb-3">
						<div class="card-body p-1">

<?

								if ($goods['currency'] == 1) {

									$kurs_currency = 1;

								} else if ($goods['currency'] == 2) {

									$kurs_currency = $api_exchange_rate_usd['sale'];

									if ($goods['currency_top_kurs'] > $api_exchange_rate_usd['sale']) {

										$kurs_currency = $goods['currency_top_kurs'];

									}

								} else if ($goods['currency'] == 3) {

									$kurs_currency = $api_exchange_rate_eur['sale'];

									if ($goods['currency_top_kurs'] > $api_exchange_rate_eur['sale']) {

										$kurs_currency = $goods['currency_top_kurs'];

									}

								}

								/*$price_min = ceil(($goods['price_sale'] - (($goods['price_sale'] - $goods['price_purchase']) * $catalog['rate'])) * $kurs_currency);
								$price_sale = ceil($goods['price_sale'] * $kurs_currency);

								$goods_income = $price_sale - $price_min;*/

								$price_purchase = ceil($goods['price_purchase'] * $kurs_currency);
								$price_sale = ceil($goods['price_sale'] * $kurs_currency);
								$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $catalog['rate']));

								if ($user['p_rate'] > 0) {

									if ($goods['price_agent'] > 0 and $goods['price_agent'] < $goods['price_purchase']) {

										$price_purchase = ceil($goods['price_agent'] * $kurs_currency);

									}

									$price_min = ceil($price_sale - (($price_sale - $price_purchase) * $user['p_rate']));

								}

								//$goods_income = $price_sale - $price_min;
								$goods_income = 0;

?>

							<h4 class="mt-3 mb-3 font-weight-bold text-danger">Клубная цена</h4>
							<div class="w-75 mx-auto">
								<style type="text/css">
									input[name="price"]:focus {
										box-shadow: 0 0 0 0.2rem rgba(255,193,7,.5);
									}
								</style>
								<input type="text" class="form-control text-center font-weight-bold" name="price" placeholder="<?=$price_sale?>" value="<?=$price_min?>" data-price-min="<?=$price_min?>" data-price-recomend="<?=$price_sale?>" data-msg-error="Минимальная цена <?=$price_min?> грн." data-msg-warning="Розничная цена <?=$price_sale?> грн." onkeyup="calcPriceGoods(this)" autocomplete="off" style="font-size: 30px; background-color: #343a40; color: #fff;" autofocus required>
							</div>
							<small class="info-feedback text-muted mt-1" style="display: block;">Розничная цена <?=$price_sale?> грн.</small>
							<small class="invalid-feedback d-block"></small>
							<p class="mt-3 mb-2">Ваша прибыль: <span class="badge badge-pill badge-info" data-toggle="tooltip" data-placement="top" title="В поле выше укажите вашу цену, по которой вы продаете в Интернете, и калькулятор рассчитает ваш доход.">?</span></p>
							<h5 class="mb-3">~<span class="commission"><?=$goods_income?></span> грн.</h5>
						</div>
					</div>

<?

					$goods_add_to_cart = false;

					if (!empty($_SESSION['cart'])) {

						for ($i=0; $i < count($_SESSION['cart']['goods']); $i++) { 
							
							if ($_SESSION['cart']['goods'][$i] == $goods['id']) $goods_add_to_cart = true;

						}

					}

?>
					<?if ($goods_add_to_cart):?>
						<button type="submit" class="btn btn-warning btn-block btn-lg" style="font-size: 1.2rem" disabled><i class="fa fa-cart-plus"></i> Добавить в корзину</button>
						<a href="/account/cart/" class="btn btn-block btn-danger">Перейти в корзину</a>
					<?else:?>
						<button type="submit" class="btn btn-warning btn-block btn-lg" style="font-size: 1.2rem"><i class="fa fa-cart-plus"></i> Добавить в корзину</button>
					<?endif;?>
					<button type="button" class="btn btn-success btn-block btn-lg" data-toggle="modal" data-target="#calcGoods"><i class="fa fa-calculator"></i> Калькулятор</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="calcGoods" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content bg-light">
			<div class="modal-header">
				<h5 class="modal-title">Калькулятор</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

<?

$catalog_rate = $catalog['rate'] * 100;

$sql = "SELECT * FROM `marketing` WHERE `dropshipper`='{$catalog_rate}'";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));
$marketing_default = mysqli_fetch_assoc($query);

$access = ($user['admin'] == 1 or in_array($user_id, [9764]));

?>

<h3 class="text-center mb-3"><?=$goods['name'][$lang]?></h3>
<div class="row justify-content-center">
	<div class="col-sm-4 mb-3">
<?

list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img0']);

if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
else $goods_photo_size = 'max-height';

?>

		<div class="main-img <?=$goods_photo_size?>" style="cursor: default;">
			<img src="/data/images/goods/<?=$goods['photo']['img0']?>" alt="Goods image">
		</div>
	</div>
	<div class="col-sm-8 mb-3">
		<div class="card">
			<h5 class="card-header">Розподілення з РРЦ <?=$price_sale?> грн <button class="btn btn-success btn-sm float-right" onclick="calcMarginDistribution()">Розрахувати</button></h5>
			<div class="card-body">
				<div class="row mb-3">
					<input type="hidden" id="numberMargin" value="<?=($price_sale - $price_purchase)?>">
					<input type="hidden" id="bidDropshipper" value="<?=$catalog_rate?>">
					<div class="col-sm-6 pr-sm-1">
						<label for="period">Період</label>
						<select id="period" class="form-control form-control-sm">
							<option value="1">День</option>
							<option value="7">Тиждень</option>
							<option value="30">Місяць</option>
							<option value="90">Квартал</option>
							<option value="182">Пів року</option>
							<option value="365">Рік</option>
						</select>
					</div>
					<div class="col-sm-6 pl-sm-1 pr-sm-1">
						<label for="numberQuantity">Кількість за день</label>
						<input type="number" step="1" id="numberQuantity" class="form-control form-control-sm" placeholder="Кількість">
						<!-- <div class="input-group input-group-sm">
							<div class="input-group-append">
								<span class="input-group-text">в день</span>
							</div>
						</div> -->
					</div>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item p-1">
						<span>
							Знижка: 
							<b class="float-right" style="color: #F10000;">
								<?if ($access):?>
								<span id="disDropshipper"><?=$marketing_default['dropshipper']?></span>% - 
								<?endif;?>
								<span id="resultDropshipper">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Наставник: 
							<b class="float-right" style="color: #F19E00;">
								<?if ($access):?>
								<span id="disManager"><?=$marketing_default['manager']?></span>% - 
								<?endif;?>
								<span id="resultManager">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Супервайзер: 
							<b class="float-right" style="color: #F2F200;">
								<?if ($access):?>
								<span id="disSupervisor"><?=$marketing_default['supervisor']?></span>% - 
								<?endif;?>
								<span id="resultSupervisor">0 грн</span>
							</b>
						</span>
					</li>
					<li class="list-group-item p-1">
						<span>
							Директор: 
							<b class="float-right" style="color: #007A00;">
								<?if ($access):?>
								<span id="disDirector"><?=$marketing_default['director']?></span>% - 
								<?endif;?>
								<span id="resultDirector">0 грн</span>
							</b>
						</span>
					</li>
					<?if ($access):?>
					<li class="list-group-item p-1">
						<span>
							Операційні витрати:
							<b class="float-right" style="color: #0B0BD9;">
								<span id="disSoft"><?=(
									$marketing_default['roma'] 
									+ $marketing_default['zgenia'] 
									+ $marketing_default['tema']
									+ $marketing_default['sasha']
									+ $marketing_default['dima']
									+ $marketing_default['adminon']
									+ $marketing_default['fond']
									)?></span>% - 
								<span id="resultSoft">0 грн</span>
							</b>
						</span>
					</li>
					<?endif;?>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function calcMarginDistribution () {

<?

$sql = "SELECT * FROM `marketing` ORDER BY `id` ASC";
$query = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($marketing = mysqli_fetch_assoc($query)) {

	$marketing_map['dropshipper'][] = $marketing['dropshipper'];
	$marketing_map['manager'][] = $marketing['manager'];
	$marketing_map['supervisor'][] = $marketing['supervisor'];
	$marketing_map['director'][] = $marketing['director'];
	$marketing_map['roma'][] = $marketing['roma'];
	$marketing_map['zgenia'][] = $marketing['zgenia'];
	$marketing_map['tema'][] = $marketing['tema'];
	$marketing_map['sasha'][] = $marketing['sasha'];
	$marketing_map['dima'][] = $marketing['dima'];
	$marketing_map['adminon'][] = $marketing['adminon'];
	$marketing_map['fond'][] = $marketing['fond'];

}

?>	

	var marketing_dropshipper = [<?=implode(",", $marketing_map['dropshipper'])?>];
	var marketing_manager = [<?=implode(",", $marketing_map['manager'])?>];
	var marketing_supervisor = [<?=implode(",", $marketing_map['supervisor'])?>];
	var marketing_director = [<?=implode(",", $marketing_map['director'])?>];
	<?if ($access):?>
	var marketing_roma = [<?=implode(",", $marketing_map['roma'])?>];
	var marketing_zgenia = [<?=implode(",", $marketing_map['zgenia'])?>];
	var marketing_tema = [<?=implode(",", $marketing_map['tema'])?>];
	var marketing_sasha = [<?=implode(",", $marketing_map['sasha'])?>];
	var marketing_dima = [<?=implode(",", $marketing_map['dima'])?>];
	var marketing_adminon = [<?=implode(",", $marketing_map['adminon'])?>];
	var marketing_fond = [<?=implode(",", $marketing_map['fond'])?>];
	<?endif;?>

	var period = $('#period').val();
	var number_margin = $('#numberMargin').val();
	var number_quantity = $('#numberQuantity').val();
	var bid_dropshipper = $('#bidDropshipper').val();

	for (var i = 0; i < marketing_dropshipper.length; i++) {
		
		if (marketing_dropshipper[i] == bid_dropshipper) {

			var result_dropshipper = period * number_margin * number_quantity * marketing_dropshipper[i] * 0.01;
			<?if ($access):?>
			$('#disDropshipper').text(marketing_dropshipper[i]);
			<?endif;?>
			$('#resultDropshipper').text(result_dropshipper.toFixed(2)+' грн');

			var result_manager = period * number_margin * number_quantity * marketing_manager[i] * 0.01;
			<?if ($access):?>
			$('#disManager').text(marketing_manager[i]);
			<?endif;?>
			$('#resultManager').text(result_manager.toFixed(2)+' грн');

			var result_supervisor = period * number_margin * number_quantity * marketing_supervisor[i] * 0.01;
			<?if ($access):?>
			$('#disSupervisor').text(marketing_supervisor[i]);
			<?endif;?>
			$('#resultSupervisor').text(result_supervisor.toFixed(2)+' грн');

			var result_director = period * number_margin * number_quantity * marketing_director[i] * 0.01;
			<?if ($access):?>
			$('#disDirector').text(marketing_director[i]);
			<?endif;?>
			$('#resultDirector').text(result_director.toFixed(2)+' грн');

			<?if ($access):?>
			var result_roma = period * number_margin * number_quantity * marketing_roma[i] * 0.01;
			var result_zgenia = period * number_margin * number_quantity * marketing_zgenia[i] * 0.01;
			var result_tema = period * number_margin * number_quantity * marketing_tema[i] * 0.01;
			var result_sasha = period * number_margin * number_quantity * marketing_sasha[i] * 0.01;
			var result_dima = period * number_margin * number_quantity * marketing_dima[i] * 0.01;
			var result_adminon = period * number_margin * number_quantity * marketing_adminon[i] * 0.01;
			var result_fond = period * number_margin * number_quantity * marketing_fond[i] * 0.01;

			$('#disSoft').text(
				marketing_roma[i] 
				+ marketing_zgenia[i] 
				+ marketing_tema[i]
				+ marketing_sasha[i]
				+ marketing_dima[i]
				+ marketing_adminon[i]
				+ marketing_fond[i]
			);

			$('#resultSoft').text(
				(result_roma + result_zgenia + result_tema + result_sasha + result_dima + result_adminon + result_fond).toFixed(2)
			+ ' грн');

			<?endif;?>

		}

	}

}
</script>

			</div>
		</div>
	</div>
</div>

<div id="videoViewsGoods" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content bg-light">
			<div class="modal-header">
				<h5 class="modal-title">Видео-обзор товара</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">

<?
				
				if (!empty($goods['video'])) {

					foreach ($goods['video'] as $video_key => $video_value) {
					
?>

						<div class="col-sm-12">
							<div class="embed-responsive embed-responsive-16by9 mb-3">
								<iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?=$video_value?>?rel=0" allowfullscreen></iframe>
							</div>
						</div>

<?

					}

				} else {

?>

						<div class="col-sm-12">
							<p class="text-muted text-center mt-3">К этому товару не добавлено видео-обзора</p>
						</div>

<?

				}

?>

				</div>
			</div>
		</div>
	</div>
</div>

<div id="downloadGoods" class="modal fade">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Скачать товар</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-header p-1 text-center">YML выгрузка этого товара в одни клик</div>
					<div class="card-body pt-2 pb-2">
						<div class="row">
							<div class="col-sm-3">
								<b>На Prom.ua: </b>
							</div>
							<div class="col-sm-9">
								<div class="row mb-1">
									<div class="col-sm-8">
										<a href="https://onlinenaxodka.com/assets/files/export/yml_prom_one_goods/<?=$goods['id']?>" id="copyPromLink" target="_blank">https://onlinenaxodka.com/assets/files/export/yml_prom_one_goods/<?=$goods['id']?></a>
									</div>
									<div class="col-sm-4">
										<button type="button" class="btn btn-warning btn-sm btn-clipboard" data-clipboard-target="#copyPromLink" onclick="copyLink(this)">Копировать</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?/*?>
				<div class="card mb-3">
					<div class="card-header">Выгрузить этот товар на Prom.ua в одни клик (YML):</div>
					<div class="card-body text-center">
<?

						if (!empty($goods['export']['prom_yml'])) {

?>

						<a class="btn btn-primary" href="<?=$goods['export']['prom_yml']?>" target="_blank">Выгрузить этот товар</a>

<?

						} else {

?>

						<p>Выгрузка на Prom.ua еще не добавлена</p>

<?

						}

?>

					</div>
				</div>
				<div class="card mb-3">
					<div class="card-header">Скачать XLS файл товара для выгрузки на Prom.ua:</div>
					<div class="card-body text-center">
<?

						if (!empty($goods['export']['prom_xlsx'])) {

?>

						<a class="btn btn-primary" href="/assets/files/xlsx/<?=$goods['export']['prom_xlsx']?>" target="_blank" download><i class="fa fa-file-excel-o"></i> Скачать этот товар</a>

<?

						} else {

?>

						<p>XLS файл этого товара для выгрузки на Prom.ua еще не добавлен</p>

<?

						}

?>

					</div>
				</div>
				<div class="card">
					<div class="card-header">Скачать все фотографии этого товара архивом:</div>
					<div class="card-body text-center">
						<button class="btn btn-success" disabled>Создать архив</button>
					</div>
				</div>
				<?*/?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="bigImg">
	<div class="modal-dialog modal-lg modal-goods" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner text-center h-100">
						

<?

					for ($i=0; $i < count($goods['photo']); $i++) {

						list($goods_photo_w, $goods_photo_h) = getimagesize('../data/images/goods/'.$goods['photo']['img'.$i]);

						if ($goods_photo_w > $goods_photo_h) {

							$goods_photo_size = 'w-100';

						} else {

							if ($goods_photo_h/$goods_photo_w > 2.2) {
								$goods_photo_size = 'w-25 mx-auto';
							} else {
								$goods_photo_size = 'w-50 mx-auto';
							}
							
						}

?>

						<div class="carousel-item">
							<p class="text-center"><a href="/data/images/goods/<?=$goods['photo']['img'.$i]?>" target="_blank" download>Скачать</a></p>
							<img class="d-block <?=$goods_photo_size?>" src="/data/images/goods/<?=$goods['photo']['img'.$i]?>" alt="<?=($i+1)?> slide">
						</div>

<?

					}

?>
						<?if ($goods['user_id'] == 45):?>
						<div class="carousel-item">
							<p class="text-center"><a href="/assets/images/sizes_<?=$lang?>.png" target="_blank" download>Скачать</a></p>
							<img class="d-block w-100" src="/assets/images/sizes_<?=$lang?>.png" alt="<?=($i+1)?> slide">
						</div>
						<?endif;?>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- TEST				 -->

<?

			}

		}

?>

	</div>
</div>

<div class="modal fade" id="modalTopCategory">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title font-weight-bold text-danger">ТОП 5 категории</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center text-uppercase">
				<p>
					<a href="/account/goods/posuda_i_aksessuari" class="btn btn-info btn-lg btn-block mb-3 text-left" style="white-space: normal;"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> Посуда и аксессуары</a>
				</p>
				<p>
					<a href="/account/goods/instrumenti_2475" class="btn btn-info btn-lg btn-block mb-3 text-left" style="white-space: normal;"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> Инструмент и оборудование</a>
				</p>
				<p>
					<a href="/account/goods/sportivnaya_strelba" class="btn btn-info btn-lg btn-block mb-3 text-left" style="white-space: normal;"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> Спортивная стрельба</a>
				</p>
				<p>
					<a href="/account/goods/smartfoni_tv_i_elektronika" class="btn btn-info btn-lg btn-block mb-3 text-left" style="white-space: normal;"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> Смартфоны, тв и электроника</a>
				</p>
				<p>
					<a href="/account/goods/igrushki_5664" class="btn btn-info btn-lg btn-block mb-3 text-left" style="white-space: normal;"><img src="/assets/images/icon-fire.png" alt="Icon Fire" width="20"> Игрушки</a>
				</p>
				<div class="alert alert-warning" role="alert">Здесь может быть ваша категория</div>
				<p style="text-transform: none;">Для уточнения деталей обращайтесь в телеграм <a href="https://t.me/Evgeniy_Tkachuk" target="_blank">@Evgeniy_Tkachuk</a></p>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalBestsellers">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Хиты продаж</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<div class="row">
					<div class="col-md-6">
						<p>
							<a href="/account/goods/grocery_franchise_polesie/2306" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> ТМ Полесье</a>
						</p>
						<p>
							<a href="/account/goods/turbine_repair/3080" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Ремонт турбин</a>
						</p>
						<p>
							<a href="/account/goods/izi6457457/3615" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> DK KIEV Капюшен</a>
						</p>
						<p>
							<a href="/account/goods/izi6457457/3614" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> FC Shakhtar Капюшен</a>
						</p>
						<p>
							<a href="/account/goods/clasicon2019/4969" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> DK KIEV Clasic</a>
						</p>
						<p>
							<a href="/account/goods/tracksuits_windbreakers20181909999/1181" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> DK KIEV 2019 </a>
						</p>
						<p>
							<a href="/account/goods/tracksuits_windbreakers20181909999/1186" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> FC Shakhtar 2019</a>
						</p>
					</div>
					<div class="col-md-6">
						<p>
							<a href="/account/goods/olive_oil9899979/3285" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Оливковое масло</a>
						</p>
						<p>
							<a href="/account/goods/mastihi_products575757/3320" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Мастиха капсулы</a>
						</p>
						<p>
							<a href="/account/goods/mastihi_products575757/3316" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Мастиха целая</a>
						</p>
						<p>
							<a href="/account/goods/mastihi_products575757/4156" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Мастиха крем</a>
						</p>
						<p>
							<a href="/account/goods/spices_in_4654564jars/4163" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Стевия Organic</a>
						</p>
						<p>
							<a href="/account/goods/mountain_spices655/3364" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Чай</a>
						</p>
						<p>
							<a href="/account/goods/bathsponges4565654757/6611" class="btn btn-danger btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-trophy"></i> Мочалка Organic </a>
						</p>
					</div>
				</div>
				<div class="alert alert-warning" role="alert">Здесь могут быть вашы товары</div>
				<p style="text-transform: none;">Для уточнения деталей обращайтесь в телеграм <a href="https://t.me/Evgeniy_Tkachuk" target="_blank">@Evgeniy_Tkachuk</a></p>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalToolsForBusiness">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Инструменты OLX</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<!-- <p>
					<a href="/account/goods/automationsofts464545456464/7299" class="btn btn-success btn-lg mb-3" style="white-space: normal;"><i class="fa fa-wrench"></i> Инструмент автоматизации продаж</a>
				</p>
				<p>
					<a href="/account/goods/modemolx" class="btn btn-warning btn-lg mb-3" style="white-space: normal;"><i class="fa fa-usb"></i> USB модем</a>
				</p> -->
				<!-- <p>
					<a href="/account/goods/modemolx/7300" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Huawei E372 3G GSM модем уценка</a>
				</p> -->
				<p>
					<a href="/account/goods/automationsofts464545456464/4995" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Профессиональный софт OLX</a>
				</p>
				<p>
					<a href="/account/goods/automationsofts464545456464/7299" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Программа TOOLX</a>
				</p>
				<p>
					<a href="/account/goods/starterpacks45655757/7213" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Стартовые пакеты для OLX</a>
				</p>
				<p>
					<a href="/account/goods/calcentr5676756/6006" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Обзвон вашей команды ON</a>
				</p>
				<p>
					<a href="/account/goods/programdmitryinstructo/7256" class="btn btn-warning btn-lg btn-block mb-3 text-left" style="white-space: normal;"><i class="fa fa-wrench"></i> Фишки продаж через OLX</a>
				</p>
				<div class="alert alert-warning" role="alert">Здесь могут быть вашы Инструменты</div>
				<p style="text-transform: none;">Для уточнения деталей обращайтесь в телеграм <a href="https://t.me/Evgeniy_Tkachuk" target="_blank">@Evgeniy_Tkachuk</a></p>
			</div>
		</div>
	</div>
</div>

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>