;
if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Crud ==="undefined") { 	AC.Crud = function() {}; }

AC.Crud.List = function() {
	
	var infoHandler		= alert;
	var warningHandler	= alert;
	var errorHandler	= alert;
	
	return {
		
		init: function() {
			$('form.ACCrudList a.new').click(AC.Crud.List.newHandler);
			$('form.ACCrudList a.edit').click(AC.Crud.List.editHandler);
			$('form.ACCrudList a.delete').click(AC.Crud.List.deleteHandler);
			
			$('form.ACCrudList').bind('result', function(events, data) {
				console.log(data);
			});			
		},
		
		newHandler: function() {		
			window.location.href = window.location.pathname + '?new';
		},
		
		editHandler: function() {
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			window.location.href = window.location.pathname + '?edit=' + id;
		},
		
		// TODO: this should be a post request, with csrf protection!
		deleteHandler: function() {
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			if (confirm(i18n.confirmDelete + ' (id:' + id + ')')) {
				var options = {
					operation	: 'delete',
					crudId		: form.attr('id')
				};
				var url = form.attr('action') + "?" + $.param(options);
				var data = {					
					_token	: form.find('input[name=_token]').val(),
					id		: id
				};
				
				$.post(url, data, function(data) {
					if (data.operation === 'delete' && data.success === true) {
						row.css({backgroundColor:'red'}).fadeOut();
					}
				}, 'json').error(function(jqXHR, message, exception) {
					$('body').removeClass('loading');
					errorHandler(i18n.requestError + ' (' + exception + ')');
				});
			}
		}
	};
}();

$(document).ready(function() {
	AC.Crud.List.init();
});