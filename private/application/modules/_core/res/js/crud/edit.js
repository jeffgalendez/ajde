;
if (typeof AC ==="undefined") {AC = function(){}};
if (typeof AC.Crud ==="undefined") {AC.Crud = function(){}};

AC.Crud.Edit = function() {
	return {
		
		init: function() {
			var self = this;
			$('form.ACCrudEdit a.cancel').click(AC.Crud.Edit.cancelHandler);
			$('form.ACCrudEdit button.save').click(AC.Crud.Edit.saveHandler);
			
			AC.Shortcut.add('Ctrl+S', AC.Crud.Edit.saveHandler);
			$(window).load(function() {
				self.equalizeForm();
			});
		},
		
		equalizeForm: function() {
			$('form.ACCrudEdit dl dt').each(function() {
				$(this).css({height: $(this).next('dd').height()});
			});
		},
		
		cancelHandler: function() {			
			window.location.href = window.location.pathname;
		},
		
		saveHandler: function() {
			var form = $(this).parents('form');
			if (!form.length) {
				form = $('form:eq(0)');
			}
			
			if (form[0].checkValidity) {
				if (form[0].checkValidity() === false) {
					alert('Error in form');
					return false;
				};
			}
			
			var options = {
				operation	: 'save',
				crudId		: form.attr('id')					
			};
			
			var url = $(form).attr('action') + "?" + $.param(options);
			var data = $(form).serialize();
			
			// clean up errors
			form.find('input').parent().removeClass('validation_error');
			form.find('div.validation_message').remove();
			AC.Crud.Edit.equalizeForm();
			
			$('body').addClass('loading');			
			$.post(url, data, function(data) {				
				$('body').removeClass('loading');
				if (data.success === false) {
					if (data.errors) {
						for(var i in data.errors) {
							$parent = $('input[name=' + i + ']').parent();
							$parent.addClass('validation_error');
							firstError = data.errors[i][0];
							$parent.attr('data-message', firstError);							
							$message = $('<div></div>').html(firstError).addClass('validation_message');
							$parent.prepend($message);
							AC.Crud.Edit.equalizeForm();
						}
					} else {
						alert('Something went wrong. (Application error)');
					}
				} else {
					if (data.operation === 'save') {
						$('dl.crudEdit > *', form[0]).css({backgroundColor:'orange'});
						window.location.href = window.location.pathname + '?list';
					} else {
						$('dl.crudEdit > *', form[0]).css({backgroundColor:'green'});
						window.location.href = window.location.pathname + '?list';						
					}
				}
			}, 'json').error(function(jqXHR, message, exception) {
				$('body').removeClass('loading');
				if (exception == 'Unauthorized' || exception == 'Forbidden') {
					alert("Timed out or not logged in.\nPlease refresh and try again. (All your changes will be lost)");
				} else {
					alert('Something went wrong, please refresh and try again. (' + exception + ')');
				}
			});
			
			return false;
		}
	};
}();

$(document).ready(function() {
	AC.Crud.Edit.init();
});
