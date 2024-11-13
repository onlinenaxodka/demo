$(document).ready(function(){

	if ($('#payment1').is(':checked')) {
		cartChangePayment($('#payment1'));
	} else if ($('#payment2').is(':checked')) {
		cartChangePayment($('#payment2'));
	} else if ($('#payment3').is(':checked')) {
		cartChangePayment($('#payment3'));
	}

	var window_min_height = $(window).height() - 274;
	$('section .content').css('min-height', window_min_height + 'px');

	if ($(window).width() < 992) {

		$('#userMenuDesctop .dropdown-menu a').each(function() {

			var href_link = $(this).attr('href');
			var name_link = $(this).html();

			$('#userMenuMobile').append('<li class="nav-item"><a class="nav-link" href="'+href_link+'">'+name_link+'</a></li>').show();

		});

		$('#cartMenuDesctop').hide();
		$('#userMenuDesctop').hide();

	} else {

		$('#userMenuDesctop').show();
		$('#userMenuMobile').html('');

	}

	$('#addTicket').click(function(){
		$(this).parent().parent().hide();
		$('#formTicket').fadeIn(500);
	});

	$('#btnCancel').click(function(){
		$('#formTicket').hide();
		$('#addTicket').parent().parent().fadeIn(300);
	});

	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({html:true});

	

	$('#inputName').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var name = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {name: name}
		})
		.done(function(data) {
			var input = $('#inputName');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'name') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
				} else {
					input.addClass('is-valid');
					invalid_feedback.html('');
					progressBar();
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputSurname').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var surname = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {surname: surname}
		})
		.done(function(data) {
			var input = $('#inputSurname');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'surname') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputNickname').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var nickname = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {nickname: nickname}
		})
		.done(function(data) {
			var input = $('#inputNickname');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'nickname') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
				} else {
					input.addClass('is-valid');
					invalid_feedback.html('');
					progressBar();
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputBirthday').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var birthday = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {birthday: birthday}
		})
		.done(function(data) {
			var input = $('#inputBirthday');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'birthday') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputCountry, #inputRegion, #inputCity').change(function(){
		var select = $(this);
		if (select.val() > 0) {

			select.addClass('is-valid');
			select.parent().find('.input-group-append').find('.input-group-text').addClass('is-valid');

			progressBar();

		}
	});

	$('.input-sex').change(function(){

		var radio = $(this);
		
		if (radio.find('input[name="sex"]').val() > 0) {

			radio.parent().addClass('is-valid');

			progressBar();

		}

	});

	$('#inputPhone').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var phone = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {phone: phone}
		})
		.done(function(data) {
			var input = $('#inputPhone');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'phone') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputTelegram').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var telegram = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {telegram: telegram}
		})
		.done(function(data) {
			var input = $('#inputTelegram');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'telegram') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputSkype').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var skype = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {skype: skype}
		})
		.done(function(data) {
			var input = $('#inputSkype');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'skype') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputCard').change(function(){
		var prev_class = $(this).parent();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		var card = $(this).val();
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {card: card}
		})
		.done(function(data) {
			var input = $('#inputCard');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'card') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
					progressBar();
	
				} else {
					if (input.val() != '') {
						input.addClass('is-valid');
						invalid_feedback.html('');
					} else {
						invalid_feedback.html('');
					}
					progressBar();
	
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputOldPpassword').change(function(){
		var prev_class = $(this).parent();
		var old_password = $(this).val();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {old_password: old_password}
		})
		.done(function(data) {
			var input = $('#inputOldPpassword');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'old_password') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
				} else {
					input.addClass('is-valid');
					invalid_feedback.html('');
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputNewPpassword').change(function(){
		var prev_class = $(this).parent();
		var password = $(this).val();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {password: password}
		})
		.done(function(data) {
			var input = $('#inputNewPpassword');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'password') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
				} else {
					input.addClass('is-valid');
					invalid_feedback.html('');
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#inputAgainNewPpassword').change(function(){
		var prev_class = $(this).parent();
		var again_new_password = $(this).val();
		var new_password = $('#inputNewPpassword').val();
		if ($(this).hasClass('is-valid')) $(this).removeClass('is-valid');
		if ($(this).hasClass('is-invalid')) $(this).removeClass('is-invalid');
		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: {again_new_password: again_new_password, new_password: new_password}
		})
		.done(function(data) {
			var input = $('#inputAgainNewPpassword');
			var json_data = $.parseJSON(data);
			var invalid_feedback = input.parent().find('.invalid-feedback');
			if (json_data.name == 'again_new_password') {
				if (json_data.error == 'true') {
					input.addClass('is-invalid');
					invalid_feedback.html(json_data.message);
				} else {
					input.addClass('is-valid');
					invalid_feedback.html('');
				}
			}
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$('#generalInfo button[type="submit"]').click(function(){
		var count_errors = 0;

		$('#generalInfo .is-invalid').each(function(){
			count_errors++;
		});

		if (count_errors > 0) {
			return false;
		} else {
			return true;
		}

	});

	$('#changePassword button[type="submit"]').click(function(){
		var count_errors = 0;

		$('#changePassword .has-danger').each(function(){
			count_errors++;
		});

		if (count_errors > 0 || $('#inputOldPpassword').val() == '') {
			return false;
		} else {
			return true;
		}

	});

});

$(window).scroll(function(){

	if  ($(window).scrollTop() > 0) {

		$('#top').show().animate({opacity: 1}, 800);
		$('#top').click(function(){
			$('body,html').animate({scrollTop: 0}, 800);
		});

	} else {

		$('#top').stop(true).animate({opacity: 0}, 400);
		setTimeout(function(){$('#top').hide();},400);
		$('body,html').stop(true).animate({scrollTop: 0}, 10);

	} 

});

function copyLink(btn) {
	var nameBtn = btn.innerHTML;
	/*btn.innerHTML = "Скопировано!";*/
	setTimeout(function(){btn.innerHTML = "Скопировано!";},100);
	setTimeout(function(){btn.innerHTML = nameBtn},1100);
}

function editSubscriber(el) {
	
	var subscriber = $(el).parents('tr');
	var id = subscriber.attr('data-id');
	var name = subscriber.attr('data-name');
	var phone = subscriber.attr('data-phone');
	var email = subscriber.attr('data-email');
	var site = subscriber.attr('data-site');
	var description = subscriber.attr('data-description');
	var status = subscriber.attr('data-status');
	var ip = subscriber.attr('data-ip');
	var geo = subscriber.attr('data-geo');
	var screen = subscriber.attr('data-screen');
	var calc = subscriber.attr('data-calc');
	var updated = subscriber.attr('data-updated');
	var created = subscriber.attr('data-created');

	$('#editSubscriber #editId').val(id);
	$('#editSubscriber #editName td').text(name);
	$('#editSubscriber #editPhone td').text(phone);
	$('#editSubscriber #editEmail td').text(email);
	$('#editSubscriber #editSite td input').val(site);
	$('#editSubscriber #editDescription td textarea').text(description);
	$('#editSubscriber #editStatus td select').val(status);
	$('#editSubscriber #editIp td').text(ip);
	$('#editSubscriber #editGeo td').text(geo);
	$('#editSubscriber #editScreen td').text(screen);
	$('#editSubscriber #editCalc td').text(calc);
	$('#editSubscriber #editUpdated td').text(updated);
	$('#editSubscriber #editCreated td').text(created);

}

function getList(type, obj) {
	var select = $('#'+obj);
	$('#loader_'+type).show();
	$.ajax({
		url: '/assets/ajax/cities.php',
		type: 'POST',
		data: {type: type, id: select.val()}
	})
	.done(function(data) {
		out = document.getElementById(type);
		for (var i = out.length - 1; i >= 0; i--) {
			out.options[i] = null;
		}
		eval(data);
		$('option[value="none"]').attr("disabled","disabled");
		$('#loader_'+type).hide();

		if (select.val() > 0) {

			var form_control_feedback = select.parent().parent().find('.form-control-feedback');
			select.parent().parent().addClass('has-success');
			select.parent().find('.input-group-addon').addClass('form-control-success');
			if (form_control_feedback.attr('credits-add') != 0) {
				form_control_feedback.html('+'+form_control_feedback.attr('credits-add')+' Credits');
			} else {
				form_control_feedback.html('');
			}
			progressBar();

		}

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function progressBar() {

	var data_progress = 0;
	var data_progress_rate = 0;

	$('#generalInfo .form-control-success').each(function(){

		data_progress++;

	});

	data_progress_rate = parseInt(data_progress*100/13);

	$('#progressProfile .progress-bar').attr('aria-valuenow', data_progress_rate).css('width', data_progress_rate+'%');
	$('#progressProfileTitle span').html(data_progress_rate);

}

function selectPhotoGoods(e) {

	var this_photo = $(e);

	this_photo.parent().find('li.active').removeClass('active');
	this_photo.addClass('active');

	var src_thumb_img = this_photo.find('img').attr('src');
	//var src_big_img = '/data/images/goods/' + src_thumb_img.split('/')[4];
	var src_big_img = src_thumb_img;

	var main_img = this_photo.parent().parent().find('.main-img');

	if (main_img.hasClass('max-width')) main_img.removeClass('max-width');
	if (main_img.hasClass('max-height')) main_img.removeClass('max-height');

	var class_img = this_photo.find('div').attr('class');

	main_img.addClass(class_img);

	main_img.find('img').attr('src', src_big_img);

}

function showBigPhotoGoods(e) {

	var this_photo = $(e);
	var src_big_img = this_photo.attr('src');

	$('#bigImg .modal-body .carousel-item').removeClass('active');

	$('#bigImg .modal-body .carousel-item img').each(function(){
		
		if ($(this).attr('src') == src_big_img) $(this).parent().addClass('active');

	});

}

function calcPriceGoods(e) {

	var price = parseFloat($(e).val());
	var price_min = parseFloat($(e).attr('data-price-min'));
	var price_recomend = parseFloat($(e).attr('data-price-recomend'));
	var msg_error = $(e).attr('data-msg-error');
	var msg_warning = $(e).attr('data-msg-warning');
	

	if (isNaN(price) == true) price = price_min;

	$(e).removeClass('is-invalid');
	$(e).parents('.card-body').find('.info-feedback').hide();
	$(e).parents('.card-body').find('.invalid-feedback').removeClass('text-muted').text('');
	$(e).parents('form').find('button[type="submit"]').removeAttr('disabled');

	if (price < price_min) {
		$(e).addClass('is-invalid');
		$(e).parents('.card-body').find('.invalid-feedback').text(msg_error);
		$(e).parents('form').find('button[type="submit"]').attr('disabled', 'disabled');
	} else if (price >= price_min && price < price_recomend) {
		$(e).parents('.card-body').find('.invalid-feedback').addClass('text-muted').text(msg_warning);
	}

	var commission = price - price_min;

	$(e).parents('.card-body').find('.commission').text(commission);

}

function cartRemoveGoods() {

	$('form#cartRemoveGoods').submit();

}

function cartDeleteGoods(goods) {
	
	goods = parseInt(goods);

	if (goods > 0) {

		if ($('form#cartDeleteGoods input').val(goods)) {

			$('form#cartDeleteGoods').submit();

		}

	}

}

function cartChangeDelivery(el, title, type = '', name = '', placeholder = '') {

	var delivery = $(el);

	type = type ? type.split('|') : [];
	name = name ? name.split('|') : [];
	placeholder = placeholder ? placeholder.split('|') : [];

	$('.cart .address .card-header').text(title);

	var html_data = '';

	if (delivery.val() != 3) {

		for (var i = 0; i < type.length; i++) {
			if (name[i] == 'locality') html_data += '<div class="form-group"><input type="'+type[i]+'" name="'+name[i]+'" class="form-control" placeholder="'+placeholder[i]+'*" onkeyup="apiNovaPoshtaLocality(this)" required></div>';
			else html_data += '<div class="form-group"><input type="'+type[i]+'" name="'+name[i]+'" class="form-control" placeholder="'+placeholder[i]+'*" required></div>';
		}

		
		$('.cart .address .card-body').html(html_data);

		$('input[name="index"]').inputmask({mask: "9 9 9 9 9"});

	} else {

		html_data += '<div class="custom-control custom-radio">' + 
						'<input type="radio" id="pickup1" name="pickup" class="custom-control-input" value="Київ 1">' +
                        '<label class="custom-control-label" for="pickup1">Київ 1</label>' + 
                    '</div>';
        html_data += '<div class="custom-control custom-radio">' + 
						'<input type="radio" id="pickup2" name="pickup" class="custom-control-input" value="Дніпро 1">' +
                        '<label class="custom-control-label" for="pickup2">Дніпро 1</label>' + 
                    '</div>';
        html_data += '<div class="custom-control custom-radio">' + 
						'<input type="radio" id="pickup3" name="pickup" class="custom-control-input" value="Одеса 1">' +
                        '<label class="custom-control-label" for="pickup3">Одеса 1</label>' + 
                    '</div>';

		$('.cart .address .card-body').html(html_data);

	}

	var payment1 = $('.cart .payment #payment1');
	var payment2 = $('.cart .payment #payment2');
	var payment3 = $('.cart .payment #payment3');

	if (name[0] == 'locality') {

		if (!payment1.is(':checked')) {
			payment1.prop('checked', true);
			payment1.parent().show();
			payment2.prop('checked', false);
		}

	} else {

		if (payment1.is(':checked')) {
			payment1.prop('checked', false);
			payment1.parent().hide();
			payment2.prop('checked', true);
		} else {
			payment1.prop('checked', false);
			payment1.parent().hide();
		}

	}

	cartChangePayment();

}

function apiNovaPoshtaLocality(e) {
	
	var input = $(e);
		
	input.autocomplete({

		source: "/assets/ajax/api_nova_poshta.php",
		select: function(event, ui) {
			
			$('.cart .address input[name="branch"]').autocomplete({
				source: ui.item.branch,
				minLength: 0
			});

		}

	});

	cartCheckError();

}

function cartChangePayment(el) {

	var payment_method = $(el);
	var payment_method_num = parseInt(payment_method.val());
	var user_balance = parseFloat($('.payment').attr('data-balance'));
	var admin = parseInt($('.payment').attr('data-admin'));
	var amount_of_order;
	if ($('#sumGoodsPrices').length > 0) amount_of_order = parseFloat($('#sumGoodsPrices').text());
	else amount_of_order = 0;

	var prepayment;
	if ($('#inputPrePayment').val() != '') 
		prepayment = parseInt($('#inputPrePayment').val());
	else
		prepayment = 0;

	$('.cart .payment .mess-success-payment').addClass('d-none');
	$('.cart .payment .mess-warning-payment').addClass('d-none');

	if (payment_method.is(':checked')) {

		if (admin != 1) {

			if (payment_method_num == 1) {

				var amount_without_prepayment = amount_of_order - prepayment;

				$('.cart #amountWithoutPrePayment').text(amount_without_prepayment);

				var reserve_balance_payment;

				if (amount_of_order < 1000)
					reserve_balance_payment = 100;
				else
					reserve_balance_payment = amount_of_order * 0.1;

				if (prepayment < reserve_balance_payment) {

					reserve_balance_payment = Math.ceil(reserve_balance_payment - prepayment);

					$('.cart .payment .reserve-balance-payment').text(reserve_balance_payment);

					if (user_balance < reserve_balance_payment) {
						$('.cart #messWarningPayment1').removeClass('d-none');
						$('.cart #messWarningPayment1 .dif-reserve-balance-payment').text(Math.ceil(prepayment+reserve_balance_payment-user_balance));
						$('.cart input[name="add_funds"]').val(Math.ceil(prepayment+reserve_balance_payment-user_balance));
					} else {
						$('.cart #messSuccessPayment1').removeClass('d-none');
					}

				} else {

					reserve_balance_payment = 0;

					$('.cart .payment .reserve-balance-payment').text(prepayment);

					if (user_balance < prepayment) {
						$('.cart #messWarningPayment1').removeClass('d-none');
						$('.cart #messWarningPayment1 .dif-reserve-balance-payment').text((prepayment-user_balance).toFixed(2));
						$('.cart input[name="add_funds"]').val(Math.ceil(prepayment-user_balance));
					} else {
						$('.cart #messSuccessPayment1').removeClass('d-none');
					}

				}

				$('#additional_info_about_marketplaces').addClass('d-none');

			} else if (payment_method_num == 2) {

				$('.cart #amountWithoutPrePayment').text('0');
				$('#inputPrePayment').val('');

				$('.cart #messSuccessPayment2').removeClass('d-none');

				$('#additional_info_about_marketplaces').addClass('d-none');

			} else if (payment_method_num == 3) {

				$('.cart #amountWithoutPrePayment').text('0');
				$('#inputPrePayment').val('');

				$('.cart .payment .off-balance-payment').text(amount_of_order);
				
				if (amount_of_order <= user_balance) {

					$('.cart #messSuccessPayment3').removeClass('d-none');

				} else {

					$('.cart #messWarningPayment3').removeClass('d-none');
					$('.cart #messWarningPayment3 .dif-off-balance-payment').text((amount_of_order-user_balance).toFixed(2));
					$('.cart input[name="add_funds"]').val(Math.ceil(amount_of_order-user_balance));

				}

				$('#additional_info_about_marketplaces').removeClass('d-none');

			}

		}

	}

	cartCheckError();

}

function cartPrePayment(el) {

	var amount_of_order;
	if ($('#sumGoodsPrices').length > 0) amount_of_order = parseFloat($('#sumGoodsPrices').text());
	else amount_of_order = 0;

	var prepayment_value = $(el).val();

	if (prepayment_value >= 0) {

		if (prepayment_value >= amount_of_order) {

			$(el).val(amount_of_order);

			$('#payment3').prop("checked", true);

			cartChangePayment($('#payment3'));

		} else {

			$('#payment1').prop("checked", true);

			cartChangePayment($('#payment1'));

		}

	} else {

		$(el).val(0);

	}

}

function cartChangeAvailabilityGoods(el, price, earnings) {

	var availability = parseInt($(el).val());
	var sum_prices = availability * price;
	var sum_earnings = availability * earnings;
	var tmp_price;
	var tmp_earnings;
	var global_sum_prices = 0;
	var global_sum_earnings = 0;

	$(el).parent().parent().attr('data-goods-price', sum_prices);
	$(el).parent().parent().attr('data-earnings', sum_earnings);

	$(el).parent().parent().find('.this-price-goods').text(sum_prices);

	$('.cart #listGoods tr.cart-list-goods').each(function(){
		tmp_price = parseFloat($(this).attr('data-goods-price'));
		tmp_earnings = parseFloat($(this).attr('data-earnings'));
		global_sum_prices += tmp_price;
		global_sum_earnings += tmp_earnings;
	});

	
	$('#sumGoodsPrices').text(global_sum_prices);
	$('#sumEarnings').text(global_sum_earnings);

	$('.cart .payment input[type="radio"]').each(function(){
		if ($(this).is(':checked')) {
			cartChangePayment(this);
		}
	});

}

function cartCheckError() {

	var name = $('.cart input[name="name"]');
	var surname = $('.cart input[name="surname"]');
	//var middlename = $('.cart input[name="middlename"]');
	var phone = $('.cart input[name="phone"]');
	var locality = $('.cart input[name="locality"]');
	//var branch = $('.cart input[name="branch"]');
	var payment1 = $('.cart #messWarningPayment1');
	var payment3 = $('.cart #messWarningPayment3');

	$('.cart button[type="submit"]').addClass('btn-success').removeAttr('disabled');

	if (name.val() == '' || surname.val() == '' || phone.val() == '' || locality.val() == '')
		$('.cart button[type="submit"]').removeClass('btn-success').addClass('btn-secondary').attr('disabled', 'disabled');
	/*if (!payment1.hasClass('d-none') || !payment3.hasClass('d-none'))
		$('.cart button[type="submit"]').removeClass('btn-success').addClass('btn-secondary').attr('disabled', 'disabled');*/
	if (!$('.mess-warning-payment').hasClass('d-none'))
		 $('.cart button[type="submit"]').removeClass('btn-success').addClass('btn-secondary').attr('disabled', 'disabled');
	if ($('#sumGoodsPrices').length == 0)
		$('.cart button[type="submit"]').removeClass('btn-success').addClass('btn-secondary').attr('disabled', 'disabled');

}

function dataOrders (goods, order_number, client, delivery_address) {

	var body_goods = $(goods).parent().find('.hidden-data-goods').html();

	$('#dataOrders .order_number').text(order_number);
	$('#dataOrders .goods .body-goods').html(body_goods);
	$('#dataOrders .client').text(client);
	$('#dataOrders .delivery_address').text(delivery_address);

}

function convertingAmountDolUah(input, curs) {

	var amount = $(input).val();
	var new_amount = amount * curs;

	//$(input).parent().find('.new-amount').text(new_amount.toFixed(2));

}

function closePopoverWindow(el) {
	var el_popover = $(el).parents('.popover');
	$(el_popover).popover('hide');
}

function selectGoodsInCatalog(e, category) {

	e.preventDefault();
	var lang = $('html').attr('lang');
	
	$.ajax({
		url: '/assets/ajax/select_goods_in_catalog.php',
		type: 'POST',
		data: {category: category, lang: lang}
	})
	.done(function(data) {
		
		$('#schoolSelectGoods .goods').html(data);

		$('#schoolSelectGoods').modal('show');

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function schoolAddLinkAdGoods(el) {
	
	var element = $(el);

	element.parent().find('.inputs-for-links').append('<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-link"></i></span></div><input type="url" name="url_ad[]" class="form-control" placeholder="Ссылка на объявление в интернете" required><div class="input-group-append"><span class="input-group-text bg-danger text-white border-danger" data-toggle="tooltip" data-placement="top" title="Удалить" onclick="schoolDeleteLinkAdGoods(this)"><i class="fa fa-trash"></i></span></div></div>');

	var count_links = element.parent().find('.inputs-for-links').children().length;

	if (count_links == 20) element.remove();

}

function schoolDeleteLinkAdGoods(el) {
	
	var element = $(el);
	var count_links = element.parents('.inputs-for-links').children().length;

	if (count_links == 20) element.parents('.form-group').append('<button type="button" class="btn btn-dark mr-3 float-left" onclick="schoolAddLinkAdGoods(this)"><i class="fa fa-plus-circle"></i></button>');

	element.parents('.input-group').remove();

}

function schoolSelectGoods(e, id, name, photo_name, photo_size, url) {

	e.preventDefault();

	var photo_width;
	var photo_height;

	if (photo_size == 'max-width') {
		photo_width = '100%';
		photo_height = 'auto';
	} else if (photo_size == 'max-height') {
		photo_width = 'auto';
		photo_height = '100%';
	}

	$('#schoolHomework #goodsSelected').html('<input type="hidden" name="goods_id" value="'+id+'"><a href="'+url+'" target="_blank"><div class="img-in-block mb-2 mx-auto" style="width: 100px;height: 100px;"><img src="/data/images/goods/'+photo_name+'" alt="Goods" style="width: '+photo_width+'; height: '+photo_height+';"></div><p class="text-center mb-2">'+name+'</p></a>');

	$('#schoolSelectGoods').modal('hide');

	$('#schoolSelectGoods').on('hidden.bs.modal', function (e) {
		$('body').addClass('modal-open');
	});

}

function schoolAddHomework() {

	$('#schoolHomework input[name="type_operation"]').val('add');
	$('#schoolHomework input[name="homework_id"]').val(0);
	$('#schoolHomework #goodsSelected').html('');
	$('#schoolHomework input[name="url_ad[]"]').val('');

	$('#schoolHomework').modal('show');

}

function schoolEditHomework(id) {

	var lang = $('html').attr('lang');

	$.ajax({
		url: '/assets/ajax/school/homework.php',
		type: 'POST',
		data: {id: id, lang: lang}
	})
	.done(function(data) {
		
		$('#schoolHomework form').html(data);

		$('#schoolHomework').modal('show');

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function partnersConfirmHomework() {
	return confirm('Вы действительно хотите подтвердить это задание?');
}

function partnersCancelHomework() {
	return confirm('Вы действительно хотите отменить это задание?');
}

function searchGoodsInCatalog(e, search_goods) {

	e.preventDefault();

	var lang = $('html').attr('lang');
	var type_search = $(search_goods).find('select[name="type_search_goods"]').val();
	var search = $(search_goods).find('input[name="search_goods"]').val();

	$('#goods .goods-result').html('').append('<p class="text-center mt-5 pt-5"><img src="/assets/images/ajax_loader_black.gif"></p>');

	$.ajax({
		url: '/assets/ajax/search_goods_in_catalog.php',
		type: 'POST',
		data: {type_search: type_search, search: search, lang: lang}
	})
	.done(function(data) {
		
		$('#goods .goods-result').html(data);

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function userPhoneAdd(e, form_phone) {

	e.preventDefault();

	var phone = $(form_phone).find('input[name="phone"]').val();

	$(form_phone).find('.input-group-append .input-group-text').html('<img src="/assets/images/ajax_loader_black.gif" width="30">');

	$.ajax({
		url: '/assets/ajax/user_phone_add.php',
		type: 'POST',
		data: {phone: phone}
	})
	.done(function(data) {
		
		var json_data = $.parseJSON(data);
		if (json_data.error == 'true') {
			$(form_phone).find('input[name="phone"]').removeClass('is-valid').addClass('is-invalid');
			$(form_phone).find('.invalid-feedback').addClass('d-block').html(json_data.message);
		} else {
			$(form_phone).find('input[name="phone"]').removeClass('is-invalid').addClass('is-valid');
			$(form_phone).find('.invalid-feedback').addClass('d-none').html('');
			setTimeout(function(){document.location.reload(true);},500);
		}
		$(form_phone).find('.input-group-append .input-group-text').html('');

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function visitWebinarOLX(e, form) {

	e.preventDefault();

	window.open('https://businessdoski.com.ua/olx_web/', '_blank');

	form.submit();

}

function investProjectDescription(id) {

	$.ajax({
		url: '/assets/ajax/invest_project_description.php',
		type: 'POST',
		data: {id: id}
	})
	.done(function(data) {
		
		$('#investProjectDescription .modal-body').html(data);

		$('#investProjectDescription').modal('show');

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

var player = [];
var count_video_duration = $('#pills-tabContent').attr('data-count-video');

function onYouTubePlayerAPIReady() {
	for (var i = 0; i < count_video_duration; i++) {
		player[i] = new YT.Player('videoYT-'+i, {
			events: {
				onReady: getDurationDisplay
			}
		});
	}
}

function getDurationDisplay() {
	for (var i = 0; i < count_video_duration; i++) {
		$('#video-'+i).text(formatTime(player[i].getDuration()));
	}
}

function formatTime(time) {
	time = Math.round(time);
	var minutes = Math.floor(time / 60),
	seconds = time - minutes * 60;
	seconds = seconds < 10 ? '0' + seconds : seconds;
	return minutes + ":" + seconds;
}

var tag = document.createElement('script');
tag.src = "//www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);