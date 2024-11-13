<?php include_once __DIR__ . '/../include/main_before_content.php'; ?>

<?

$user_partner_id = $user['partner_id'];

$sql = "SELECT * FROM `users` WHERE `id`='{$user_partner_id}'";
$query = mysqli_query($db, $sql) or die(mysqli_error());
$mentor = mysqli_fetch_assoc($query);

$mentor_photo = '/data/images/users/user.jpg';
$type_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
for ($i = 0; $i < count($type_img); $i++) { 
	$img_name = __DIR__ . '/../data/images/users/user'.$mentor['id'].'.'.$type_img[$i];
	if (file_exists($img_name)) {
		$mentor_photo = '/data/images/users/user'.$mentor['id'].'.'.$type_img[$i];
	}
}

switch ($mentor['status']) {
	case 0:
		$mentor_status = 'Новичок';
		break;
	case 1:
		$mentor_status = 'Дропшиппер';
		break;
	case 2:
		$mentor_status = 'Наставник';
		break;
	case 3:
		$mentor_status = 'Супервайзер';
		break;
	case 4:
		$mentor_status = 'Директор';
		break;
	default:
		$mentor_status = 'Новичок';
		break;
}

?>



<div class="row">
	<div class="col-sm-4">
		<img src="<?=$mentor_photo?>" class="img-thumbnail w-100 mb-3" alt="Mentor">
		<h4 class="text-center mb-3"><?=$mentor['name']?> <?=$mentor['surname']?></h4>
		<h3 class="text-center mb-3"><small>Cтатус наставника</small> <b>"<?=$mentor_status?>"</b></h3>
	</div>
	<div class="col-sm-4">
		<ul class="list-group">
			<li class="list-group-item">
				<b class="float-left">Дата рождения:</b>
				<span class="float-right"><?=date('d.m.Y', strtotime($mentor['birthday']))?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left">Страна:</b>
				<?

				$mentor_country_id = $mentor['country'];

				$country_query = mysqli_query($db, "SELECT `name` FROM `countries` WHERE `id`='$mentor_country_id'");
				$country = mysqli_fetch_assoc($country_query);

				?>
				<span class="float-right"><?=$country['name']?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left">Регион:</b>
				<?

				$mentor_region_id = $mentor['region'];

				$region_query = mysqli_query($db, "SELECT `name` FROM `regions` WHERE `id`='$mentor_region_id'");
				$region = mysqli_fetch_assoc($region_query);

				?>
				<span class="float-right"><?=$region['name']?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left">Город:</b>
				<?

				$mentor_city_id = $mentor['city'];

				$city_query = mysqli_query($db, "SELECT `name` FROM `cities` WHERE `id`='$mentor_city_id'");
				$city = mysqli_fetch_assoc($city_query);

				?>
				<span class="float-right"><?=$city['name']?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left">Пол:</b>
				<span class="float-right"><?=(($city['sex']=1)?'Мужской':'Женский')?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left">Телефон:</b>
				<span class="float-right"><?=$mentor['phone']?></span>
			</li>
			<li class="list-group-item">
				<b class="float-left"><img src="/assets/images/social/telegram.png" alt="Telegram" width="20"> Telegram:</b>
				<?=(($mentor['telegram'])?'<a href="https://t.me/'.$mentor['telegram'].'" class="float-right" target="_blank">@'.$mentor['telegram'].'</a>':'еще не указан')?>
			</li>
		</ul>
	</div>
	<div class="col-sm-4">
		<h4>Крипто ресурсы</h4>
		<?

		$sql = "SELECT * FROM `users_projects` WHERE `user_id`={$mentor['id']}";
		$query = mysqli_query($db, $sql) or die(mysqli_error($db));

		$n=0;

		while ($users_projects = mysqli_fetch_assoc($query)) {

			$n++;

?>

		<p>
			<span><?=$n?>.</span>
			<a href="<?=$users_projects['url']?>" target="_blank" class="btn btn-info float-right">Регистрация в <?=$users_projects['type']?></a>
		</p>

<?

		}

		?>
	</div>
</div>

<!-- <div class="card">
	<div class="card-body">

	</div>	
</div> -->

<? include_once __DIR__ . '/../include/main_after_content.php'; ?>