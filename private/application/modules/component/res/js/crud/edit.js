if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Crud ==="undefined") { 	AC.Crud = function() {}; }

AC.Crud.Edit = function() {
	return {
		
		init: function() {
			$('form.ACCrudEdit a.cancel').click(AC.Crud.Edit.cancelHandler);
			$('form.ACCrudEdit button.save').click(AC.Crud.Edit.saveHandler);
		},
		
		cancelHandler: function() {			
			window.location.href = window.location.pathname;
		},
		
		saveHandler: function() {
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			var options = {
				operation	: 'save',
				crudId		: form.attr('id'), 
				id			: id					
			};
			$.getJSON(form.attr('action'), options, function(data) {
				if (data.operation === 'save' && data.success === true) {
					row.css({backgroundColor:'red'}).fadeOut();
				}
			});
		}
		
	};
}();

$(document).ready(function() {
	AC.Crud.Edit.init();
})
