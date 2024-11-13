$(document).ready(function(){

	checkCategoriesAddGoods();

	$('#addGoods').on('hidden.bs.modal', function (e) {

		var count_gi = 0;
		
		$('#addGoods .goods-images').each(function(){

			if ($(this).hasClass('max-width') || $(this).hasClass('max-height')) {

				count_gi++;

				if (count_gi == 1) {

					alert('Вы начали процедуру добавления товара. Если вы случайно закрыли карточку, просто нажмите ОК для востановления. Если вы хотите отменить процедуру добавления товара, нажмите ОК, и УДАЛИТЕ сначало фото!');
					$('#addGoods').modal('show');

				}

			}

		});
		
	});

	$('#editGoods').on('hidden.bs.modal', function (e) {

		if ($('#editGoods form').attr('data-edit') == 'true') {

			alert('Вы редактировали товар. Сохраните изминения!');
			$('#editGoods').modal('show');

		}
		
	});

	//Support

	$('input[name="all_status"]').click(function(){
		if ($(this).is(':checked')) {
			$('input[type="checkbox"].status').prop("checked", true);
		} else {
			$('input[type="checkbox"].status').prop('checked', false);
		}
		
	});

	$('input[name="all_answer"]').click(function(){
		if ($(this).is(':checked')) {
			$('input[type="checkbox"].answer').prop("checked", true);
		} else {
			$('input[type="checkbox"].answer').prop('checked', false);
		}
		
	});

	$('input[name="all_status"], input[name="all_answer"], input[type="checkbox"].status, input[type="checkbox"].answer').click(function(){
		$('#btnSaveChangesMessagesSupport').show();
	});

	$('#btnSaveChangesMessagesSupport').click(function(){
		$('#saveChangesMessagesSupport').submit();
	});

	//Support

	var window_min_height = $(window).height() - 74;

	$('section.container-fluid').css('min-height', window_min_height+'px');

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

	//Goods catalog

	$('input[name="all_goods_check"]').click(function(){
		if ($(this).is(':checked')) {
			$('input[type="checkbox"].goods_check').prop("checked", true);
		} else {
			$('input[type="checkbox"].goods_check').prop('checked', false);
		}
		
	});

	$('input[name="all_goods_check"], input[type="checkbox"].goods_check').click(function(){

		$('tr').removeClass('table-danger');
		
		//$('#btnSaveChangesGoods').show();
		$('.hiddenSelectGoods').html('');

		$('input[type="checkbox"].goods_check:checked').each(function() {
			
			var id_goods = $(this).val();

			$('.hiddenSelectGoods').append('<input type="hidden" name="goods_check[]" value="'+id_goods+'">');

			$('#code'+id_goods).addClass('table-danger');
			
		});

		var count_checke_goods = $('input[type="checkbox"].goods_check:checked').length;
		$('.countCheckedGoods').text(count_checke_goods+' шт.');

	});

	/*$('#btnSaveChangesGoods').click(function(){
		$('#saveChangesGoods').submit();
	});*/

	/*$('.tree').treegrid({
		expanderExpandedClass: 'fa fa-minus-square',
		expanderCollapsedClass: 'fa fa-plus-square',
		initialState: 'collapsed'
	});*/

	/*var location_hash = location.hash;
	location_hash = location_hash.substr(1);
	$("'"+location_hash+"'").addClass('table-primary');*/

	//Goods catalog

	//marketing
	$('#marketingSetting input[type="number"]').on('keyup change', function (e) {

		var inputs_row = $(this).parents('tr').find('td');
		var input_val;
		var input_amount = 0;
		var text_amount = $(this).parents('tr').find('.text-amount');

		inputs_row.each(function(){
			
			input_val = $(this).find('input[type="number"]').val();

			if (input_val) {

				input_amount += parseInt(input_val);

			}

		});

		$(this).parents('tr').removeClass('table-default table-danger');
		text_amount.removeClass('text-success text-danger');

		if (input_amount == 100) {
			
			$(this).parents('tr').addClass('table-default');
			text_amount.addClass('text-success');

		} else {
			
			$(this).parents('tr').addClass('table-danger');
			text_amount.addClass('text-danger');

		}

		text_amount.text(input_amount+'%');

	});
	//marketing

});

$('[data-toggle="tooltip"]').tooltip();
$('.btn-edit').tooltip();

new ClipboardJS('.btn-clipboard');

function copyLink(btn) {
	var nameBtn = btn.innerHTML;
	/*btn.innerHTML = "Скопировано!";*/
	setTimeout(function(){btn.innerHTML = "Скопировано!";},100);
	setTimeout(function(){btn.innerHTML = nameBtn;},1100);
}

function dataUser(id) {

	$("#tableRows").html('');

	$('#windowUserData').modal();

	$('#loaderUser').show();

	$.ajax({
		url: '/assets/ajax/users.php',
		type: 'POST',
		data: {id: id}
	})
	.done(function(data) {
		$("#tableRows").html(data);
		$('#loaderUser').hide();
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function categorySelectAddGoods(select) {
	
	var elements = $('.modal-goods .list-group .list-group-item .form-group select');

	elements.removeAttr('name');

	for (var i = elements.length - 1; i > elements.index(select); i--) {
		
		elements.eq(i).remove();

	}
	
	$.ajax({
		url: '/assets/ajax/catalog.php',
		type: 'POST',
		data: {id: select.value}
	})
	.done(function(data) {
		
		if (data != '') {

			$('.modal-goods .list-group .list-group-item-category .form-group').append(data);

		} else {

			if ($(select).parents('.modal').attr('id') == 'addGoods') parametersAddGoods(select.value);

			$('.modal-goods #parametersGoodsUpdate').attr('onclick', 'parametersAddGoods(\''+select.value+'\')');
			
			$('.modal-goods .list-group .list-group-item-category .form-group select:last-child').attr('name', 'category');

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

function parametersAddGoods(category) {
	
	$.ajax({
		url: '/assets/ajax/goods_parameters.php',
		type: 'POST',
		data: {category: category}
	})
	.done(function(data) {
		
		$('.modal-goods .parameters .list-group').html(data);

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function addInputs(e) {
	
	var first_input = $(e).parent().parent().find('.list-group-item.inputs').html();

	$(e).parent().before('<div class="list-group-item inputs">'+first_input+'</div>');

	$(e).parent().parent().find('.list-group-item.inputs').last().find('input').val('');
	$(e).parent().parent().find('.list-group-item.inputs').last().find('input[name="param_value_uk[]"],input[name="param_value_ru[]"]').val('-');

	$(e).parent().parent().find('.list-group-item.inputs').last().append('<p class="text-center mb-0"><button type="button" class="btn btn-link btn-sm text-dark" onclick="deleteInputs(this)"><i class="material-icons float-left">delete_forever</i></button></p>');

}

function deleteInputs(e) {
	
	$(e).parent().parent().html('').remove();

}

function addInputsPhotoAddGoods(e) {

	var count_photo_added = $(e).parent().parent().find('.form-group').children().length;

	if (count_photo_added < 20) {

		$(e).parent().parent().find('.form-group').append('<div class="col-sm-3 mb-2"><div class="card"><div class="card-body p-1"><input type="hidden" name="photo[]"><label class="goods-images mb-0 float-left"><input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)"><p class="text-center text-muted d-flex justify-content-center mb-0 h-100"><i class="material-icons align-self-center">add_a_photo</i></p></label></div><div class="card-footer text-center pt-0 pb-0"><small>Фото '+(count_photo_added+1)+'</small></div></div></div>');

	}

	if (count_photo_added == 19) $(e).parent().remove();

}

function addInputsVideoAddGoods(e) {
	
	//var first_input = $(e).parent().parent().find('.form-group').html();

	$(e).parent().before('<div class="form-group row"><div class="col-sm-11"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">https://www.youtube.com/watch?v=</span></div><input type="text" name="video[]" class="form-control" placeholder="MPxPh7UrNJ8"></div></div><div class="col-sm-1"><button type="button" class="btn btn-danger btn-sm btn-block pl-1 pr-0" onclick="deleteInputsVideoAddGoods(this)"><i class="material-icons float-sm-left">delete_forever</i></button></div></div>');

}

function deleteInputsVideoAddGoods(e) {
	
	$(e).parent().parent().html('').remove();

}

function checkCategoriesAddGoods() {

	$('.modal-goods form').submit(function(){

		var last_select = $('.modal-goods form select:last-child');

		if (last_select.attr('name') != 'category') {

			alert('Ошибка! Вы не выбрали категорию товара.');
			return false;

		}
		
	});

}

function maxCountPhotosInInput(e) {

	var files = $(e)[0].files;

	if (files.length > 7) {

		alert('Выбрать можно максимум 7 картинок');

		$(e).val('');
					
	}

}

function bigImg(src) {

	var style;
	var img = new Image();
	var window_height = window.innerHeight;

	$('#bigImg .modal-dialog').css('max-width', window_height);
	$('#bigImg .modal-content').height(window_height);

	img.src = src;

	img.onload = function() {
    	
		/*if (this.width >= this.height) {
    		style = 'width: 100%;';
    	} else {
    		if (this.width > 960) {
    			style = 'display: block; width: 100%; max-width: 960px; margin: auto;';
    		} else {
    			style = 'display: block; width: 100%; max-width: '+this.width+'px; margin: auto;';
    		}
    	}*/

    	if (this.width >= this.height) {
    		$('#bigImg .modal-body').removeClass('max-height').addClass('max-width');
    	} else {
    		$('#bigImg .modal-body').removeClass('max-width').addClass('max-height');
    	}

		//$('#bigImg .modal-body').html('<img src="'+src+'" style="'+style+'">');
		$('#bigImg .modal-body').html('<img src="'+src+'">');
		$('#bigImg').modal();

	}

}

function changePhotoGoods(e) {

	var files = e.files[0];
	var formData = new FormData();

	if (files.type == 'image/jpg' || files.type == 'image/jpeg' || files.type == 'image/png' || files.type == 'image/gif' || files.type == 'image/bmp') {

		if (files.size <= 10000000) {

			$(e).parent().find('p').hide();
			$(e).parent().parent().append('<div class="progress-uploading"><img src="/assets/images/ajax_loader_black.gif"></div>');

			formData.append('photo', e.files[0]);

			$.ajax({
				url: '/assets/ajax/goods_images.php',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(data) {

				data = $.trim(data);

				$(e).parent().parent().find('input[type=hidden]').val(data);
				
				var img = new Image();

				img.src = '/data/images/goods_thumb/' + data;

				img.onload = function() {
					
					if (img.width > img.height) $(e).parent().addClass('max-width').html('<button type="button" class="btn btn-danger btn-close-img p-0" onclick="deletePhotoGoods(this)" data-name="'+data+'"><i class="material-icons">close</i></button><img src="'+img.src+'">');
					else $(e).parent().addClass('max-height').html('<button type="button" class="btn btn-danger btn-close-img p-0" onclick="deletePhotoGoods(this)" data-name="'+data+'"><i class="material-icons">close</i></button><img src="'+img.src+'">');

				}

				if ($(e).parents('.modal').attr('id') == 'editGoods') {
					$(e).parents('form').attr('data-edit', 'true');
				}

				$(e).parent().parent().find('.progress-uploading').fadeOut(700);

				setTimeout(function(){

					$('.modal-goods .progress-uploading').each(function(){

						if ($(this).css('display') == 'none') {

							$(this).remove();

						}

					});

				}, 800);

				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		} else {

			alert('Слишком большое изображение. Максимальный размер 10Мб.');

		}

	} else {

		alert('Допускаются расширения файлов только jpg, jpeg, png, gif, bmp. Выбирите другое расширение.');

	}

}

function deletePhotoGoods(e) {

	data_name = $(e).attr('data-name');

	$(e).parent().parent().append('<div class="progress-uploading"><img src="/assets/images/ajax_loader_black.gif"></div>');

	$.ajax({
		url: '/assets/ajax/goods_images.php',
		type: 'POST',
		data: {name_img: data_name}
	})
	.done(function(data) {

		data = $.trim(data);
		
		if (data == 'success') {

			var index_img = $(e).parent().attr('data-cnt');

			if ($(e).parents('.modal').attr('id') == 'editGoods') {
				$(e).parents('form').attr('data-edit', 'true');
			}

			if (index_img == 0) {
				$(e).parent().parent().find('input[type="hidden"]').val('no_image.png');
			} else {
				$(e).parent().parent().find('input[type="hidden"]').val('');
			}
			
			$(e).parent().parent().find('.progress-uploading').fadeOut(700);

			setTimeout(function(){

				$('.modal-goods .progress-uploading').each(function(){

					if ($(this).css('display') == 'none') $(this).remove();
					
				});

			}, 800);

			if ($(e).parent().hasClass('max-width')) $(e).parent().removeClass('max-width');
			if ($(e).parent().hasClass('max-height')) $(e).parent().removeClass('max-height');

			if (index_img == 0) $(e).parent().html('<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)"><p class="text-center text-muted d-flex justify-content-center mb-0 h-100"><i class="material-icons align-self-center">add_a_photo</i></p>');
			else $(e).parent().html('<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp" onchange="changePhotoGoods(this)"><p class="text-center text-muted d-flex justify-content-center mb-0 h-100"><i class="material-icons align-self-center">add_a_photo</i></p>');

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

function editGoods(id) {

	$('#editGoods .modal-body').html('').append('<div class="progress-uploading-goods-body"><img src="/assets/images/ajax_loader_black.gif"></div>');

	$.ajax({
		url: '/assets/ajax/goods_edit.php',
		type: 'POST',
		data: {edit_goods_id: id}
	})
	.done(function(data) {

		if (data != '') {

			$('#editGoods .modal-body').html(data);

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

/*function showResultEarningsAgent(el) {
	
	var input = $(el);
	if (input.val() == 2)
		input.parents('.row').find('.result-earnings-agent').show();
	else
		input.parents('.row').find('.result-earnings-agent').hide();

}*/

function selectCurrencyPriceGoods(el) {

	var input = $(el);

	if (input.val() == 1) {

		input.parents('.list-group-item').find('.top-kurs-currency').hide();
		input.parents('.list-group-item').find('.top-kurs-currency').find('.input-group-text').text('UAH');
		input.parents('.list-group-item').find('.top-kurs-currency').find('input[name="currency_top_kurs"]').attr('placeholder', '1');
		input.parents('.list-group-item').find('.input-prices-goods').find('.input-group-text').text('грн');
		input.parents('.list-group-item').find('.kurses-pb-goods').hide();

	} else if (input.val() == 2) {

		input.parents('.list-group-item').find('.top-kurs-currency').show();
		input.parents('.list-group-item').find('.top-kurs-currency').find('.input-group-text').text('USD');
		input.parents('.list-group-item').find('.top-kurs-currency').find('input[name="currency_top_kurs"]').attr('placeholder', '28.5');
		input.parents('.list-group-item').find('.input-prices-goods').find('.input-group-text').text('$');
		input.parents('.list-group-item').find('.kurses-pb-goods').show();

	} else if (input.val() == 3) {

		input.parents('.list-group-item').find('.top-kurs-currency').show();
		input.parents('.list-group-item').find('.top-kurs-currency').find('.input-group-text').text('EUR');
		input.parents('.list-group-item').find('.top-kurs-currency').find('input[name="currency_top_kurs"]').attr('placeholder', '31.7');
		input.parents('.list-group-item').find('.input-prices-goods').find('.input-group-text').text('€');
		input.parents('.list-group-item').find('.kurses-pb-goods').show();

	}

}

function convertToUAH(el) {

	var input_current = $(el);
	var input_current_value = parseFloat(input_current.val());
	var currency = parseInt(input_current.parents('.list-group-item').find('input[name="currency"]:checked').val());
	var currency_top_kurs = parseFloat(input_current.parents('.list-group-item').find('input[name="currency_top_kurs"]').val());
	var kurs_usd_sale_pb = parseFloat(input_current.parents('.list-group-item').find('.kurs-usd-sale-pb-goods').text());
	var kurs_eur_sale_pb = parseFloat(input_current.parents('.list-group-item').find('.kurs-eur-sale-pb-goods').text());
	var input_prices_goods = input_current.parents('.input-prices-goods').find('h4');

	if (Number.isNaN(input_current_value)) input_current_value = 0;
	if (Number.isNaN(currency_top_kurs)) currency_top_kurs = 0;

	if (currency == 1) {

		input_prices_goods.text(input_current_value.toFixed(2)+' грн');

	} else if (currency == 2) {

		if (kurs_usd_sale_pb >= currency_top_kurs)
			input_prices_goods.text((input_current_value*kurs_usd_sale_pb).toFixed(2)+' грн');
		else
			input_prices_goods.text((input_current_value*currency_top_kurs).toFixed(2)+' грн');
		
	} else if (currency == 3) {

		if (kurs_eur_sale_pb >= currency_top_kurs)
			input_prices_goods.text((input_current_value*kurs_eur_sale_pb).toFixed(2)+' грн');
		else
			input_prices_goods.text((input_current_value*currency_top_kurs).toFixed(2)+' грн');

	}

}

function editCategory(id) {

	$('#editCategory .modal-body').html('').append('<div class="progress-uploading-goods-body"><img src="/assets/images/ajax_loader_black.gif"></div>');

	$.ajax({
		url: '/assets/ajax/category_edit.php',
		type: 'POST',
		data: {edit_category_id: id}
	})
	.done(function(data) {

		if (data != '') {

			$('#editCategory .modal-body').html(data);

			$('#editCategory').modal();

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

function changeParentCategory(select) {

	var elements = $('#editCategory .card-list-categories .form-group select');
	var catalog_id = $('#editCategory input[name="catalog_id"]').val();
	var el = $(select);

	elements.removeAttr('name');

	for (var i = elements.length - 1; i > elements.index(select); i--) {
		
		elements.eq(i).remove();

	}
	
	$.ajax({
		url: '/assets/ajax/category_catalog.php',
		type: 'POST',
		data: {id: select.value, current_catalog_id: catalog_id}
	})
	.done(function(data) {

		$('#editCategory .card-list-categories .form-group').append(data);
			
		el.attr('name', 'edit_level_id');

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function generate_linkname_category(el) {

	var name_ru = $(el).val();

	$.ajax({
		url: '/assets/ajax/generate_linkname_category.php',
		type: 'POST',
		data: {name_category: name_ru}
	})
	.done(function(linkname_value) {

		var linkname = $('.modal input[name="linkname"]');

		linkname.val(linkname_value);

		if (linkname_value.match(/^[a-z0-9_]{1,120}$/)) {
			linkname.removeClass('is-invalid');
			linkname.addClass('is-valid');
			linkname.parents('form').find('button[type="submit"]').removeAttr('disabled');
		} else {
			linkname.removeClass('is-valid');
			linkname.addClass('is-invalid');
			linkname.parents('form').find('button[type="submit"]').attr('disabled', 'disabled');
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

function searchGoodsInCatalog(e, search, order_id) {
	
	e.preventDefault();

	var lang = $('html').attr('lang');
	var search_val = $(search).val();

	$('#searchedGoods').html('').append('<p class="text-center mt-5 mb-5"><img src="/assets/images/ajax_loader_black.gif" width="50"></p>');

	$.ajax({
		url: '/assets/ajax/search_goods_in_order_admin.php',
		type: 'POST',
		data: {search: search_val, lang: lang, order_id: order_id}
	})
	.done(function(data) {
		
		$('#searchedGoods').html(data);

		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
