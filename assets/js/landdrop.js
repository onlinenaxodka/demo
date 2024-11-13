$(function() {

	$('#earningsSlider').slider({
		value: 1,
		min: 0,
		max: 14,
		step: 1,
		animate: "fast",
		slide: function( event, ui ) {
			if (ui.value > 9) $('#earningsSlider').slider('option', 'step', 2);
			else $('#earningsSlider').slider('option', 'step', 1);

			var sum = 150;
			var amount = ui.value * sum * 30;

			if (ui.value == 11 || ui.value == 12) amount = 15 * sum * 30;
			if (ui.value == 14) amount = 20 * sum * 30;

			$('.earnings-amount').text(space_num(amount));
			$('#earnings button[type="button"]').attr('onclick', 'screenCalcValue(2, '+amount+')');

			//$('.spincrement').spincrement({thousandSeparator: " "});

		}
	});

	$('#earningsSelect').change(function(){
		
		var value = $(this).val();
		var sum = 150;
		var amount = value * sum * 30;

		$('.earnings-amount').text(space_num(amount));
		$('#earnings button[type="button"]').attr('onclick', 'screenCalcValue(2, '+amount+')');

		//$('.spincrement').spincrement({thousandSeparator: " "});

	});

});

$(document).ready(function(){

	$('input').inputmask();

	var show = true;
	var countbox = "#about_us";
	$(window).on("scroll load resize", function(){

		if(!show) return false;

		var w_top = $(window).scrollTop();
		var e_top = $(countbox).offset().top;

		var w_height = $(window).height();
		var d_height = $(document).height();

		var e_height = $(countbox).outerHeight();

		if(w_top + 500 >= e_top || w_height + w_top == d_height || e_height + e_top < w_height){
			$(".spincrement_count").spincrement({
				thousandSeparator: " ",
				duration: 1200
			});

			show = false;
		}
	});

	$('header button.btn-menu').click(function(){
		$('header ul.navigation').slideToggle('slow');
	});

	$('ul.navigation a').bind('click', function(e) {
		
		e.preventDefault();

		var target = $(this).attr("href");

		if ($(window).width() < 991) {
			$('html, body').stop().animate({scrollTop: $(target).offset().top-50}, 600);
			$('header ul.navigation').slideToggle('slow');
		} else {
			$('html, body').stop().animate({scrollTop: $(target).offset().top-100}, 600);
		}

		return false;

	});

	$('.reviews-read-more').click(function(){
		$(this).parent().parent().find('.three-points').hide();
		$(this).parent().parent().find('.hide-text').slideToggle('slow', function(e){
			$(this).parent().parent().find('.hide-text').css('display','inline');
		});
		$(this).hide();
	});

	$('.reviews-show-all').click(function(){
		$('.reviews-hided').slideToggle('slow');
		$(this).hide();
	});

	$('.faq-list-item-title').click(function(){
		
		var faq_text = $(this).parent().find('.faq-list-item-text');
		
		faq_text.slideToggle('slow');
		
		if ($(this).find('.btn-faq').hasClass('btn-faq-plus')) {
			$(this).find('.btn-faq').removeClass('btn-faq-plus').addClass('btn-faq-minus');
		} else {
			$(this).find('.btn-faq').removeClass('btn-faq-minus').addClass('btn-faq-plus');
		}

	});

	$('form').submit(function(){

		var form = $(this);

		form.find('button[type="submit"]').attr('disabled', 'disabled').append('<img class="loader-img" src="/assets/images/ajax_loader.gif">');
		form.find('input').attr('disabled', 'disabled');

		var screen = form.find('input[name="screen"]').val();
		var calc = form.find('input[name="calc"]').val();
		var name = form.find('input[name="name"]').val();
		var email = form.find('input[name="email"]').val();
		var phone = form.find('input[name="phone"]').val();

		if (screen == 'undefined' || screen == '') screen = 0;
		if (calc == 'undefined' || calc == '') calc = 0;
		if (name == 'undefined' || name == '') name = '';
		if (email == 'undefined' || email == '') email = '';
		if (phone == 'undefined' || phone == '') phone = '';

		$.ajax({

			type: "POST",
			url: "/assets/ajax/landdrop.php",
			data: {
				screen: screen,
				calc: calc,
				name: name,
				email: email,
				phone: phone
			}

		}).done(function(data) {

			var json_data = $.parseJSON(data);

			if (json_data.error == 'true') {
				swal({
					title: json_data.title,
					text: json_data.message,
					type: 'error'
				});
				
			} else {
				swal({
					title: json_data.title,
					html: json_data.message,
					type: 'success'
				});
				form.trigger("reset");
				if (screen != 1) $('#formSubscribe').modal('hide');
			}

			form.find('button[type="submit"]').removeAttr('disabled').find('img').remove();
			form.find('input').removeAttr('disabled');

			console.log("success");

		}).fail(function() {
			console.log("error");
		}).always(function() {
			console.log("complete");
		});

		return false;
		
	});

	$('[data-toggle="tooltip"]').tooltip();

});

$(window).scroll(function(){

	if  ($(window).scrollTop() > 0) {

		$('#top').show().animate({opacity: 1}, 500);
		$('#top').click(function(){
			$('html,body').animate({scrollTop: 0}, 500);
		});

	} else {

		$('#top').stop(true).animate({opacity: 0}, 400);
		setTimeout(function(){$('#top').hide();},400);
		$('html,body').stop(true).animate({scrollTop: 0}, 10);

	}

	var scrollDistance = $(window).scrollTop();

	if (scrollDistance == 0) $('ul.navigation a.active').removeClass('active');

	if ($(window).width() < 1154) scrollDistance = scrollDistance + 60;
	else scrollDistance = scrollDistance + 110;

	$('.layout-menu').each(function(i) {

		if ($(this).position().top <= scrollDistance) {

			$('ul.navigation a.active').removeClass('active');
			$('ul.navigation a').eq(i).addClass('active');

		}

	});

});

function space_num(n) {
	n += "";
	n = new Array(4 - n.length % 3).join("U") + n;
	return n.replace(/([0-9U]{3})/g, "$1 ").replace(/U/g, "");
}

function screenCalcValue(screen, calc) {
	$('.modal input[name="screen"]').val(screen);
	$('.modal input[name="calc"]').val(calc);
}