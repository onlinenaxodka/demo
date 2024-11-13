		</div>

		<!-- <div class="alert alert-primary mt-3" role="alert">
			Для корректного функционирования системы  Вам необходимо почистить кеш вашего браузера<br>
			* Инструкция НАЖМИТЕ одновременно комбинацию клавиш CTRL + SHIFT + DELETE<br>
			<span class="text-danger"><b>Внимание!</b> Состоялось массовое обновление системы, просьба перезагрузить все ссылки XML на своих магазинах.</span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute;top: 5px;right: 10px;">
				<span aria-hidden="true">&times;</span>
			</button>
		</div> -->
		
	</section>
	
	<? include_once __DIR__ . '/footer.php'; ?>

	<?/*?>
	<div id="welcome" class="modal fade">
	  <div class="modal-dialog modal-lg" role="document" style="width: 100%;max-width: 1176px;">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <img src="/assets/images/tmp_orders/screen4.png" alt="screen4" class="w-100">
			<img src="/assets/images/tmp_orders/screen5.png" alt="screen5" class="w-100">
			<a href="/account/goods/" style="
			    float: right;
			    position: relative;
			    margin: -160px 132px 0 0;
			">
				<img src="/assets/images/tmp_orders/screen7.png" alt="screen6">
			</a>
			<a href="https://t.me/joinchat/GNSpuA0G1iDfahKS0csoDg" style="
			    float: right;
			    position: relative;
			    margin: -245px 230px 0 0;
			" target="_blank">
				<img src="/assets/images/tmp_orders/screen6.png" alt="screen6" width="70">
			</a>
			<a href="https://t.me/joinchat/GNSpuA0G1iDfahKS0csoDg" style="
				font-weight: bold;
			    float: right;
			    position: relative;
			        margin: -285px 95px 0 0;
			" target="_blank">Чат Поддержки пользователей системы 24/7</a>
			<div style="width: 536px;height:300px;position: absolute;
    top: 441px;
    left: 52px;">
				<div class="embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/lawAOdDYG6w?rel=0" allowfullscreen></iframe>
				</div>
			</div>
	      </div>
	    </div>
	  </div>
	</div>
	<?*/?>

	<?if(empty($user['phone'])):?>
	<div class="modal fade" id="editUserPhone">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Ваш номер телефона</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h5>В вашем профиле не указан номер телефона.</h5>
					<p>Укажите номер телефона, чтобы продолжить работу с платформой Online Naxodka.</p>
					<form method="POST" onsubmit="userPhoneAdd(event, this)">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-phone"></i>
									</span>
								</div>
								<input type="tel" name="phone" class="form-control form-control-lg" placeholder="Ваш телефон" data-inputmask="'mask': '+389999999999'" required>
								<div class="input-group-append">
									<span class="input-group-text bg-white"></span>
								</div>
							</div>
							<div class="invalid-feedback"></div>
						</div>
						<div class="form-group text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase">Сохранить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?endif;?>

	<?if($user['sub_social'] == 0):?>
	<div class="modal fade" id="subSocialShow">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header pb-0">
					<h5 class="modal-title text-center"><img src="/assets/images/core/logo.png" width="50" height="50" alt="logo" style="position: relative; top: -2px;"> ONLINE NAXODKA</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h2 class="text-center font-weight-bold">Поздравляем, Ваш e-mail подтвержден!</h2>
					<p class="text-center" style="font-size: 20px;">Рекомендуем прямо сейчас подписаться на наши соц. сети, чтобы получать еще больше полезных материалов и быть всегда на связи.</p>
					<div class="text-center mt-3 mb-3">
						<!-- <div class="d-inline-block text-center m-2">
							<a href="https://t.me/joinchat/DY75iUrI7q3paCjUdbyYhg" target="_blank">
								<img src="/assets/images/social/telegram.png" width="38">
								<br>
								<span>Чат</span>
							</a>
						</div> -->
						<div class="d-inline-block text-center m-2">
							<a href="https://t.me/joinchat/AAAAAEww568intibZHzkCg" target="_blank">
								<img src="/assets/images/social/telegram.png" width="38">
								<br>
								<span>Telegram</span>
							</a>
						</div>
						<!-- <div class="d-inline-block text-center m-2">
							<a href="https://invite.viber.com/?g2=AQAWaUOrt0AYOEmeSQv2OHs%2FAWdTMRk%2BnePb13OydKr2OgLLYs6ydCKvOV%2BLy9zQ&lang=ru" target="_blank">
								<img src="/assets/images/social/viber.png" width="38">
								<br>
								<span>Чат</span>
							</a>
						</div> -->
						<div class="d-inline-block text-center m-2">
							<a href="https://invite.viber.com/?g2=AQBMjWqbKnlExElhpyz1dkudEBOpTUIJQcNcCgUpGzHO5bHeGC3iZG5Z%2F7QtVrPq&lang=ru" target="_blank">
								<img src="/assets/images/social/viber.png" width="38">
								<br>
								<span>Viber</span>
							</a>
						</div>
						<!-- <div class="d-inline-block text-center m-2">
							<a href="https://www.facebook.com/groups/onlinenaxodka/" target="_blank">
								<img src="/assets/images/social/fb.png" width="38">
								<br>
								<span>Facebook</span>
							</a>
						</div>
						<div class="d-inline-block text-center m-2">
							<a href="https://www.instagram.com/online.naxodka/" target="_blank">
								<img src="/assets/images/social/instagram.png" width="38">
								<br>
								<span>Instagram</span>
							</a>
						</div>
						<div class="d-inline-block text-center m-2">
							<a href="https://vk.com/onlinenaxodka" target="_blank">
								<img src="/assets/images/social/vk.png" width="38">
								<br>
								<span>Вконтакте</span>
							</a>
						</div>
						<div class="d-inline-block text-center m-2">
							<a href="https://www.youtube.com/channel/UC1xfOAQyN33IS3nWTlfBW6w" target="_blank">
								<img src="/assets/images/social/yt.png" width="38">
								<br>
								<span>YouTube</span>
							</a>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?endif;?>

	<script src="/assets/js/jquery-2.2.4.min.js"></script>
	<!-- <script src="/assets/js/tether.min.js"></script> -->
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<?=$jquerylib?>
	<?/*if(empty($_SESSION['welcome'])):?>
	<script type="text/javascript">$(document).ready(function(){$('#welcome').modal();});</script>
	<?$_SESSION['welcome']=1;?>
	<?endif;*/?>
	<?if (empty($user['phone'])):?>
	<script src="/assets/js/jquery.inputmask.bundle.min.js"></script>
	<script type="text/javascript">$('input').inputmask();</script>
	<script type="text/javascript">$(document).ready(function(){$('#editUserPhone').modal();});</script>
	<?endif;?>
	<?if($user['sub_social'] == 0):?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#subSocialShow').modal();
			$('#subSocialShow').on('hidden.bs.modal', function (e) {
				$.ajax({
					url: '/assets/ajax/sub_social_show.php',
					type: 'POST',
					data: {ss: 1}
				})
				.done(function(data) {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			});
		});
	</script>
	<?endif;?>
	<?if ($add_funds_start_modal):?>
	<script type="text/javascript">$(document).ready(function(){$('#alertReminderAddFunds').modal();});</script>
	<?endif;?>
	<script src="/assets/js/main.js?v=<?=strtotime('2024-05-08 23:10:00')?>"></script>
</body>
</html>