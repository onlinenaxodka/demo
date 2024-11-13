<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

include_once __DIR__ . '/../../../config.php';

if (isset($_SESSION['user'])) {

	$user_id = test_request($_SESSION['user']['id']);
	$user_id = intval($user_id);

	$user_key = test_request($_SESSION['user']['hash']);
	$user_key = strval($user_key);

	$sql = "SELECT * FROM `users` WHERE `id`='{$user_id}' AND `key`='{$user_key}' LIMIT 1";
	$query = mysqli_query($db, $sql) or die(mysqli_error());
	$user = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) > 0) {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if (!empty($_POST)) {

				$lang = (isset($_POST['lang'])) ? mysqli_real_escape_string($db, $_POST['lang']) : '';
				$lang = test_request($lang);

				if (empty($lang) or $lang != 'uk' or $lang != 'ru') $lang = 'ru';

				$id = (isset($_POST['id'])) ? mysqli_real_escape_string($db, $_POST['id']) : '';
				$id = test_request($id);
				$id = intval($id);

				if ($id > 0) {

					$sql = "SELECT * FROM `school_homework` WHERE `id`='{$id}' AND `user_id`='{$user_id}'";
					$query = mysqli_query($db, $sql) or die(mysqli_error($db));

					if (mysqli_num_rows($query) > 0) {

						$school_homework = mysqli_fetch_assoc($query);

						$school_homework_goods_id = $school_homework['goods_id'];

						$sql_goods = "SELECT * FROM `goods` WHERE `id`='{$school_homework_goods_id}'";
						$query_goods = mysqli_query($db, $sql_goods) or die(mysqli_error($db));
						$goods = mysqli_fetch_assoc($query_goods);

						$goods['photo'] = json_decode($goods['photo'], true);
						$goods['name'] = json_decode($goods['name'], true);

						list($goods_photo_w, $goods_photo_h) = getimagesize('../../images/goods_thumb/'.$goods['photo']['img0']);

						if ($goods_photo_w > $goods_photo_h) {

							$goods_photo_width = '100%';
							$goods_photo_height = 'auto';

						} else {
							
							$goods_photo_width = 'auto';
							$goods_photo_height = '100%';

						}

						$school_homework['link_ad'] = json_decode($school_homework['link_ad'], true);



?>

					<input type="hidden" name="type_operation" value="edit">
					<input type="hidden" name="homework_id" value="<?=$school_homework['id']?>">
					<h3 class="text-center text-success mb-3"><span class="badge badge-success">Шаг 1</span> <br><small>Выберите товар</small></h3>
					<div class="form-group row justify-content-center">
						<div class="col-sm-5">
							<div class="card mb-2">
								<div class="card-body" id="goodsSelected">
									<input type="hidden" name="goods_id" value="<?=$goods['id']?>">
									<a href="/account/goods/<?=$goods['category']?>/<?=$goods['id']?>" target="_blank">
										<div class="img-in-block mb-2 mx-auto" style="width: 100px;height: 100px;">
											<img src="/data/images/goods/<?=$goods['photo']['img0']?>" alt="Goods" style="width: <?=$goods_photo_width?>; height: <?=$goods_photo_height?>;">
										</div>
										<p class="text-center mb-2"><?=$goods['name'][$lang]?></p>
									</a>
								</div>
							</div>
							<button type="button" class="btn btn-warning btn-block" onclick="selectGoodsInCatalog(event, 'catalog')">
								Выбрать товар <i class="fa fa-angle-down"></i>
							</button>
						</div>
					</div>
					<h3 class="text-center text-primary mt-5 mb-3"><span class="badge badge-primary">Шаг 2</span> <br><small>Добавьте ссылки на Ваши объявления</small></h3>
					<div class="form-group">
						<div class="inputs-for-links">

<?

							foreach ($school_homework['link_ad'] as $school_homework_link_ad) {

?>

							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-link"></i>
									</span>
								</div>
								<input type="url" name="url_ad[]" class="form-control" placeholder="Ссылка на объявление в интернете" value="<?=$school_homework_link_ad?>" required>
								<div class="input-group-append">
									<span class="input-group-text bg-danger text-white border-danger" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="schoolDeleteLinkAdGoods(this)">
										<i class="fa fa-trash"></i>
									</span>
								</div>
							</div>

<?

							}

?>

						</div>
						<button type="button" class="btn btn-dark mr-3 float-left" onclick="schoolAddLinkAdGoods(this)">
							<i class="fa fa-plus-circle"></i>
						</button>
						<small class="text-muted">максимум 20 ссылок</small>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success btn-lg" style="white-space: normal;"><i class="fa fa-paper-plane"></i> Отправить НАСТАВНИКУ на проверку <i class="fa fa-paper-plane"></i></button>
					</div>

<?						

					}

				}

			}

		}

	}

}