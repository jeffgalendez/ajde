;
if (typeof AC ==="undefined") {AC = function(){}};
if (typeof AC.Crud ==="undefined") {AC.Crud = function(){}};

AC.Crud.Edit = function() {
	
	var infoHandler		= alert;
	var warningHandler	= alert;
	var errorHandler	= alert;
	
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
//			$('form.ACCrudEdit dl dt').each(function() {
//				$(this).css({height: $(this).next('dd').height()});
//			});
		},
		
		cancelHandler: function() {			
			window.location.href = window.location.pathname;
		},
		
		saveHandler: function() {
			var form = $(this).parents('form');
			if (!form.length) {
				form = $('form:eq(0)');
			}
			
			// TODO: HTML5 validation, should be deprecated?
			if (form[0].checkValidity) {
				if (form[0].checkValidity() === false) {
					alert(i18n.formError);
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
			form.find(':input').parent().removeClass('validation_error');
			form.find('div.validation_message').fadeOut(function() {
				$(this).remove();
			});
			AC.Crud.Edit.equalizeForm();
			
			// Set loading state and disable submit button
			$('body').addClass('loading');
			form.find('button.save').attr('disabled', 'disabled');
			
			if (typeof $(form[0]).data('onBeforeSubmit') === 'function') {
				var fn = $(form[0]).data('onBeforeSubmit');
				fn();
			}
			$.post(url, data, function(data) {		
								
				if (data.success === false) {
					
					$('body').removeClass('loading');
					form.find('button.save').attr('disabled', null);
				
					if (data.errors) {
						if (typeof $(form[0]).data('onError') === 'function') {
							var fn = $(form[0]).data('onError');
							fn();
						}
						for(var i in data.errors) {
							$parent = $(':input[name=' + i + ']').parent();
							$parent.addClass('validation_error');
							firstError = data.errors[i][0];
							$parent.attr('data-message', firstError);							
							$message = $('<div></div>').html(firstError).addClass('validation_message').hide();
							$parent.prepend($message.fadeIn());
							AC.Crud.Edit.equalizeForm();
						}
					} else {
						errorHandler(i18n.applicationError);
					}
				} else {
					if (typeof $(form[0]).data('onSave') === 'function') {
						var fn = $(form[0]).data('onSave');
						if (fn(data) === false) {
							
							$('body').removeClass('loading');
							form.find('button.save').attr('disabled', null);
							
							return;
						}
					}
					if (data.operation === 'save') {
						//$('dl.crudEdit > *', form[0]).css({backgroundColor:'orange'});
						window.location.href = window.location.pathname + '?list';
					} else {
						//$('dl.crudEdit > *', form[0]).css({backgroundColor:'green'});
						window.location.href = window.location.pathname + '?list';						
					}
				}
			}, 'json').error(function(jqXHR, message, exception) {
				
				$('body').removeClass('loading');
				form.find('button.save').attr('disabled', null);
				
				if (typeof $(form[0]).data('onError') === 'function') {
					var fn = $(form[0]).data('onError');
					fn();
				}
				if (exception == 'Unauthorized' || exception == 'Forbidden') {
					warningHandler(i18n.forbiddenWarning);
				} else {
					errorHandler(i18n.requestError + ' (' + exception + ')');
				}
			});
			
			return false;
		}
	};
}();

$(document).ready(function() {
	AC.Crud.Edit.init();
});
