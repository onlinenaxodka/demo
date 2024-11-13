$(document).ready(function(){

	$('#form').modal({backdrop: false});
	$('[data-toggle="tooltip"]').tooltip();
	$('input').inputmask();

	$('#inputName, #inputSurname, #inputPhone, #inputEmail, #inputPassword').change(function() {
		
		var input = $(this);
		var input_data = input.serialize();
		
		if (input.hasClass('is-valid')) input.removeClass('is-valid');
		if (input.hasClass('is-invalid')) input.removeClass('is-invalid');
		if (input.parent().find('.invalid-feedback').is('.invalid-feedback')) input.parent().find('.invalid-feedback').remove();

		if ($('form').find('.invalid-feedback').is('.invalid-feedback')) $('button[type="submit"]').attr('disabled', 'disabled');
		else $('button[type="submit"]').removeAttr('disabled');

		$.ajax({
			url: '/assets/ajax/input_validation.php',
			type: 'POST',
			data: input_data
		})
		.done(function(data) {

			var json_data = $.parseJSON(data);

			if (json_data.error == 'true') {
				input.addClass('is-invalid').parent().append('<div class="invalid-feedback">'+json_data.message+'</div>');
				$('button[type="submit"]').attr('disabled', 'disabled');
			} else {
				input.addClass('is-valid');
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

});