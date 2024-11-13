<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error($db));
	$user = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) > 0) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if (!empty($_POST)) {

				$lang = (isset($_POST['lang'])) ? mysqli_real_escape_string($db, $_POST['lang']) : '';
				$lang = test_request($lang);

				if (empty($lang) or $lang != 'uk' or $lang != 'ru') $lang = 'ru';

				$category = (isset($_POST['category'])) ? mysqli_real_escape_string($db, $_POST['category']) : '';
				$category = test_request($category);
				
				$linkname = $category;
				if (empty($linkname)) $linkname = 'catalog';

				$sql = "SELECT * FROM `catalog` WHERE `linkname`='{$linkname}'";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));
				$catalog = mysqli_fetch_assoc($query);
				$catalog_id = $catalog['id'];

				if ($linkname != 'catalog') {

					$catalog_level_id = $catalog['level_id'];

					$sql_parent_catalog = "SELECT * FROM `catalog` WHERE `id`='{$catalog_level_id}'";
					$query_parent_catalog = mysqli_query($db, $sql_parent_catalog) or die(mysqli_error($db));
					$catalog_parent = mysqli_fetch_assoc($query_parent_catalog);

					echo '<a href="#" onclick="selectGoodsInCatalog(event, \''.$catalog_parent['linkname'].'\')"><i class="fa fa-chevron-circle-left"></i> Назад</a><br>';

				}

				$sql = "SELECT * FROM `catalog` WHERE `level_id`='{$catalog_id}' AND `locked`=0 ORDER BY `sort` ASC";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

				if (mysqli_num_rows($query) > 0) {

				echo '<ul class="list-catalog mt-4 text-center">';

					while ($catalog = mysqli_fetch_assoc($query)) {

                        if (!file_exists('../../data/images/catalog/'.$catalog['img'])) {
                            $catalog['img'] = 'no_image.png';
                        }

						list($catalog_width_img, $catalog_height_img) = getimagesize('../../data/images/catalog/'.$catalog['img']);

						if ($catalog_width_img > $catalog_height_img) {
							$catalog_width_img = '100%';
							$catalog_height_img = 'auto';
						} else {
							$catalog_width_img = 'auto';
							$catalog_height_img = '100%';
						}

						echo '
						<li>
							<a href="#" onclick="selectGoodsInCatalog(event, \''.$catalog['linkname'].'\')">
								<div>
									<img src="/data/images/catalog/'.$catalog['img'].'" style="width: '.$catalog_width_img.'; height: '.$catalog_height_img.';">
								</div>
								<p class="text-uppercase"><span>'.$catalog['name_'.$lang].'</span></p>
							</a>
						</li>
						';

					}


				echo '</ul>';

				} else {

					echo '<ul class="list-goods mt-4 text-center">';

						$sql = "SELECT * FROM `goods` WHERE `category`='{$linkname}' AND `availability`>0 AND `status`=1 ORDER BY SUBSTRING_INDEX(SUBSTRING_INDEX(name, '\"', -2), '\"', 1) ASC";
						$query = mysqli_query($db, $sql) or die(mysqli_error($db));

						if (mysqli_num_rows($query) > 0) {

							while ($goods = mysqli_fetch_assoc($query)) {

								$goods['photo'] = json_decode($goods['photo'], true);
								$goods['name'] = json_decode($goods['name'], true);

                                if (!file_exists('../../data/images/goods_thumb/'.$goods['photo']['img0'])) {
                                    $goods['photo']['img0'] = 'no_image.png';
                                }

								list($goods_photo_w, $goods_photo_h) = getimagesize('../../data/images/goods_thumb/'.$goods['photo']['img0']);

								if ($goods_photo_w > $goods_photo_h) $goods_photo_size = 'max-width';
								else $goods_photo_size = 'max-height';

								$list_goods_name = $goods['name'][$lang];
								$list_goods_name = str_replace("'", "\'", $list_goods_name);
								if (strlen(utf8_decode($list_goods_name)) > 56) $list_goods_name = mb_substr($list_goods_name, 0, 56) . '...';

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

								$price_min = ceil(($goods['price_sale'] - (($goods['price_sale'] - $goods['price_purchase']) * $catalog['rate'])) * $kurs_currency);
								$price_sale = ceil($goods['price_sale'] * $kurs_currency);

								echo '
								<li>
									<a href="#" onclick="schoolSelectGoods(event, '.$goods['id'].',\''.$list_goods_name.'\',\''.$goods['photo']['img0'].'\', \''.$goods_photo_size.'\', \'/account/goods/'.$linkname.'/'.$goods['id'].'\')">
										<div class="'.$goods_photo_size.'">
											<img src="/data/images/goods_thumb/'.$goods['photo']['img0'].'">
										</div>
										<p class="goods-title" style="height: 65px;line-height: 65px;">
											<span>'.$list_goods_name.'</span>
										</p>
									</a>

									<div class="row">
										<div class="col-6">
											<p class="text-secondary mb-1">Дроп цена</p>
										</div>
										<div class="col-6">
											<p class="text-secondary mb-1">Реком. цена</p>
										</div>
									</div>
									<div class="row">
										<div class="col-6">
											<h4 style="color: red;"><b>'.$price_min.' грн</b></h4>
										</div>
										<div class="col-6">
											<h4 style="color: #28a745;"><b>'.$price_sale.' грн</b></h4>
										</div>
									</div>

									<div class="row pt-3 pb-3 ml-0 mr-0" style="background: #f3f3f3">
										<div class="col-12">
											<button type="button" class="btn btn-success btn-block" onclick="schoolSelectGoods(event, '.$goods['id'].', \''.$list_goods_name.'\', \''.$goods['photo']['img0'].'\', \''.$goods_photo_size.'\', \'/account/goods/'.$linkname.'/'.$goods['id'].'\')">Выбрать товар</button>
										</div>
									</div>
								</li>
								';

							}

						} else {

							echo '<br><br><br><br>На данный момент в этом каталоге нет товаров';

						}

					echo '</ul>';

				}

			}

		}

	}

}