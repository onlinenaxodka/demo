<header class="navbar navbar-expand-xl navbar-light bg-faded">
		<a class="navbar-brand" href="/">
			<img src="/assets/images/core/logo.png" width="30" height="30" class="d-inline-block align-top" alt="logo">
			<?=$name_company?>
			<small class="badge badge-danger" style="position: absolute;padding: 1px 5px;margin: 25px 0 0 -34px;font-weight: normal;font-size: 12px;">Beta</small>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarMenu">
			<ul class="navbar-nav mr-auto">
				<!-- <li class="nav-item <?=$main_menu_active[0]?>">
					<a class="nav-link" href="/account/"><i class="fa fa-home"></i> <?=$header_main_menu_item[0]?></a>
				</li> -->
				<li class="nav-item <?=$main_menu_active[1]?>">
					<a class="nav-link" href="/account/goods/"><i class="fa fa-briefcase"></i> <?=$header_main_menu_item[1]?></a>
				</li>
				<li class="nav-item <?=$main_menu_active[2]?>">
					<a class="nav-link" href="/account/orders/"><i class="fa fa-shopping-bag"></i> <?=$header_main_menu_item[2]?></a>
				</li>
				<li class="nav-item <?=$main_menu_active[8]?>">
					<a class="nav-link" href="/account/faq/"><i class="fa fa-handshake-o"></i> Сотрудничество</a>
				</li>
				<!-- <li class="nav-item <?=$main_menu_active[3]?>">
					<a class="nav-link" href="/account/cart/"><i class="fa fa-shopping-cart"></i> <?=$header_main_menu_item[3]?></a>
				</li> -->
				<li class="nav-item <?=$main_menu_active[4]?>">
					<!-- <a class="nav-link" href="/account/school/"><i class="fa fa-graduation-cap"></i> <?=$header_main_menu_item[4]?></a> -->
					<a class="nav-link" href="/account/school_lessons/"><i class="fa fa-graduation-cap"></i> Инструкция</a>
				</li>
				<li class="nav-item <?=$main_menu_active[6]?>">
					<a class="nav-link" href="/account/news/"><i class="fa fa-newspaper-o"></i> <?=$header_main_menu_item[12]?></a>
				</li>
				<!-- <li class="nav-item <?=$main_menu_active[7]?>">
					<a class="nav-link dropdown-toggle" href="/account/shops/" id="dropdownMenuShopsLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-star"></i> Магазины</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuShopsLink" style="right: auto;margin: 0;">
						<a class="dropdown-item text-info" href="https://zakupka.com/reg/d13587/" target="_blank">Zakupka <small>(В разработке)</small></a>
						<a class="dropdown-item" href="#">Rozetka <small>(В разработке)</small></a>
						<a class="dropdown-item" href="#">Allbiz <small>(В разработке)</small></a>
						<a class="dropdown-item text-info text-center" href="/account/goods/internet_magazin_on/183923">Продающий<br>магазин<br><small class="font-weight-bold">(доступно <span class="text-danger">8</span>/10)</small><br><span class="btn btn-warning btn-sm btn-block mt-1 font-weight-bold">Узнать<span></a>
						<a class="dropdown-item" href="#">Hotline <small>(В разработке)</small></a>
						<a class="dropdown-item" href="#">Prom <small>(В разработке)</small></a>
					</div>
				</li> -->
				<li class="nav-item <?=$main_menu_active[5]?>">
					<a class="nav-link" href="/account/support/"><i class="fa fa-envelope"></i> <?=$header_main_menu_item[5]?><span class="count-support-messages"><?=$count_new_message?></span></a>
				</li>
			</ul>
			<style type="text/css">
				.our_tel_header {max-width: 64px;width: 100%;}
				@media (max-width: 1391px) {
					.our_tel_header {display: none;}
				}
			</style>
			<div class="row our_tel_header">
				<div class="col-sm-12">
					<i class="fa fa-clock-o" data-toggle="tooltip" data-html="true" data-placement="bottom" title="
					<h5 class='text-info mt-2'>График работы</h5>
					<h6 class='text-warning'>- обработка заказов -</h6>
					<p class='mb-0'>Пн-Пт с 11:00 до 22:00<br>
					Сб-Вс с 12:00 до 21:00</p>
					<h3 class='mb-0 text-secondary'>~</h3>
					<h6 class='text-warning'>- работа поддержки -</h6>
					<p class='mb-2'>Пн-Пт с 11:00 до 22:00<br>
					Сб-Вс с 12:00 до 21:00</p>
					" style="position: absolute;font-size: 42px;top: -22px;right: 15px;color: #fff;cursor: pointer;"></i>
				</div>
				<!-- <div class="col-sm-6 pr-0">
					<a href="https://t.me/joinchat/DY75iUrI7q3paCjUdbyYhg" style="float: left;" target="_blank">
						<img src="/assets/images/tmp_orders/screen6.png" alt="screen6" style="width:30px;">
					</a>
				</div> -->
			</div>
            <?

            $count_added_cart_goods = isset($_SESSION['cart']['goods']) ? count($_SESSION['cart']['goods']) : 0;

            ?>
			<ul id="userMenuMobile" class="navbar-nav mr-auto" style="display:none">
				<li class="nav-item <?=$main_menu_active[3]?>">
					<a class="nav-link" href="/account/cart/">
                        <i class="fa fa-shopping-cart"></i> <?=$header_main_menu_item[3]?>
                        <span class="count-cart-goods"><?=$count_added_cart_goods?></span>
                    </a>
				</li>
			</ul>
			<div id="cartMenuDesctop" class="cart-menu-desctop <?=$main_menu_active[3]?>">
				<a href="/account/cart/" class="d-block" title="<?=$header_main_menu_item[3]?>">
					<i class="fa fa-shopping-cart"></i>
					<span class="count-cart-goods"><?=$count_added_cart_goods?></span>
				</a>
			</div>
			<div id="userMenuDesctop" class="dropdown">
				<button class="user-block dropdown-toggle" type="button" id="dropdownUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?	
						$img_photo = '<img src="/data/images/users/user.jpg" alt="User Photo" height="40">';
						$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
						for ($i = 0; $i < count($type_img); $i++) { 
							$img_name = __DIR__ . '/../data/images/users/user'.$user_id.'.'.$type_img[$i];
							if (file_exists($img_name)) {
								$img_photo = '<img src="/data/images/users/user'.$user_id.'.'.$type_img[$i].'" alt="User Photo" height="40">';
							}
						}
						echo $img_photo;
					?>
					<p>
						<!-- <span><?=$user['name']?></span><span style="font-size:13px">ID: <?=$user['work_id']?></span> -->
						<span>Кабинет</span><span style="font-size:13px"><?=$user['name']?> (<?=$user['work_id']?>)</span>
					</p>
				</button>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserMenu">
					<?if ($user['admin'] == 1 or $user['admin'] == 2):?>
						<a class="dropdown-item" href="/admin/"><i class="fa fa-lock"></i> <?=$header_main_menu_item[6]?></a>
					<?endif;?>
					<?if ($user['storage_id']):?>
						<a class="dropdown-item" href="/account/storage_orders/"><i class="fa fa-cubes"></i> Склад</a>
					<?endif;?>
					
					<a class="dropdown-item" href="/account/wallet/"><i class="fa fa-google-wallet"></i> <?=$header_main_menu_item[7]?></a>
					<a class="dropdown-item" href="/account/edit/"><i class="fa fa-id-card-o"></i> Настройки профиля</a>

					<?if (in_array($user_id, array(2,4,5,340,348,368,496,560,1144,4108,5715,6264,6679))):?>
						<a class="dropdown-item" href="/account/school_homework/"><i class="fa fa-graduation-cap"></i> Домашнее задание</a>
					<?endif;?>
					<!-- <a class="dropdown-item" href="/account/goods/marketingovoe_agentstvo_on"><i class="fa fa-maxcdn text-danger"></i> Маркетинговое агентство</a> -->
					<?if ($user['status'] > 0):?>
						<?/*?>
						<a class="dropdown-item" href="/account/subscribers/"><i class="fa fa-child"></i> <?=$header_main_menu_item[8]?></a>
						<?*/?>
					<?endif;?>
					<a class="dropdown-item" href="/account/partners/"><i class="fa fa-users"></i> <?=$header_main_menu_item[9]?></a>
					<?if ($user['employee'] == 1):?>
					<a class="dropdown-item" href="/account/partners_work/"><i class="fa fa-users"></i> <?=$header_main_menu_item[13]?></a>
					<?endif;?>
					<a class="dropdown-item" href="/account/market_tools/"><i class="fa fa-diamond"></i> <?=$header_main_menu_item[14]?></a>
					<a class="dropdown-item" href="/account/mentor/"><i class="fa fa-user-md"></i> Страница наставника</a>
					<!-- <a class="dropdown-item" href="/account/kripto_naxodka/"><span class="font-weight-bold text-warning"><i class="fa fa-btc"></i> Страница наставника</span></a> -->
					<a class="dropdown-item" href="/account/investor_club/"><i class="fa fa-percent"></i> Краудфандинг</a>
					<!-- <a class="dropdown-item" href="/account/goods_new/">Новый каталог</a> -->
					<!-- <a class="dropdown-item" href="/account/goods_export/"><i class="fa fa-upload"></i> <?=$header_main_menu_item[15]?></a> -->
					<!-- <a class="dropdown-item" href="/account/edit/"><i class="fa fa-id-card-o"></i> <?=$header_main_menu_item[10]?></a> -->
					<a class="dropdown-item" href="/logout/"><i class="fa fa-sign-out"></i> <?=$header_main_menu_item[11]?></a>
				</div>
			</div>
		</div>
	</header>