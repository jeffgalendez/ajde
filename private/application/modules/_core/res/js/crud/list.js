;
if (typeof AC ==="undefined") {AC = function() {}};
if (typeof AC.Crud ==="undefined") {AC.Crud = function() {}};

AC.Crud.List = function() {
	
	var infoHandler		= alert;
	var warningHandler	= alert;
	var errorHandler	= alert;
	
	return {
		
		init: function() {
			$('form.ACCrudList tbody tr').live('click', AC.Crud.List.trHandler);
			
			$('form.ACCrudList input.id').live('click', AC.Crud.List.checkboxHandler);
			$('form.ACCrudList input.toggleSelect').live('click', AC.Crud.List.toggleSelectHandler);
			$('form.ACCrudList td.toolbar a.new').live('click', AC.Crud.List.newHandler);
			$('form.ACCrudList td.toolbar a.delete').live('click', AC.Crud.List.multipleDeleteHandler);
			$('form.ACCrudList td.buttons a.edit').live('click', AC.Crud.List.editHandler);
			$('form.ACCrudList td.buttons a.delete').live('click', AC.Crud.List.deleteHandler);
			
			$('form.ACCrudList td.buttons a.prev').live('click', AC.Crud.List.prevHandler);
			$('form.ACCrudList td.buttons a.next').live('click', AC.Crud.List.nextHandler);
			$('form.ACCrudList td.buttons select.pageSize').live('change', AC.Crud.List.pageSizeHandler);
			$('form.ACCrudList th a.order').live('click', AC.Crud.List.orderHandler);
			$('form.ACCrudList th input[name=\'view[search]\']').live('keypress', AC.Crud.List.searchBoxHandler);
			$('form.ACCrudList th a.search').live('click', AC.Crud.List.searchButtonHandler);
			
			$('form.ACCrudList').bind('result', function(events, data) {
				//console.log(data);
			});			
		},
		
		trHandler: function(e) {
			e.stopPropagation();
			e.preventDefault();
			var row = $(this);
			var checkbox = row.find('input[type=checkbox]');
			checkbox.attr('checked', !checkbox.attr('checked'));
			AC.Crud.List.checkboxHandler.call(checkbox, e);
			return false;
		},
		
		checkboxHandler: function(e) {
			e.stopPropagation();
			var form = $(this).parents('form');
			var data = form.serializeArray();
			form.find('td.toolbar .button.multiple').removeClass('show');
			form.find('input.toggleSelect').attr('checked', false); 
			var count = 0;
			for (elm in data) {
				if (data[elm].name == 'id[]') {
					form.find('input.toggleSelect').css('opacity', 0.45); 
					form.find('input.toggleSelect').attr('checked', true); 
					form.find('td.toolbar .button.multiple').addClass('show');
					count++;
				}
			}
			if (count == $('input.id').length) {
				form.find('input.toggleSelect').css('opacity', 1); 
			}
		},
		
		toggleSelectHandler: function() {
			var form = $(this).parents('form');
			var data = form.serializeArray();
			
			var count = 0;
			for (elm in data) {
				if (data[elm].name == 'id[]') {
					count++;
				}
			}
			
			form.find('input.toggleSelect').css('opacity', 1); 
			if (count > 0) {
				form.find('input.id').attr('checked', false); 
				form.find('td.toolbar .button.multiple').removeClass('show');
			} else {
				form.find('input.id').attr('checked', true);
				form.find('td.toolbar .button.multiple').addClass('show');
			}
		},
		
		newHandler: function() {		
			window.location.href = window.location.pathname + '?new';
		},
		
		editHandler: function(e) {
			e.stopPropagation();
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			window.location.href = window.location.pathname + '?edit=' + id;
		},
		
		multipleDeleteHandler: function(e, id) {
			id = id || false;			
			var form = $(this).parents('form');
			
			form.find('input.operation').val('delete');
			
			var options = {
				operation	: 'delete',
				crudId		: form.attr('id')
			};
			var url = form.attr('action') + "?" + $.param(options);
			var data = form.serializeArray();
						
			var count = 0;
			for (elm in data) {
				if (data[elm].name == 'id[]') {
					if (id !== false && data[elm].value != id) {
						delete data[elm];
					} else {
						count++;
					}
				}
			}
			
			if (id !== false && count == 0) {
				data.push({
					name: 'id[]',
					value: id
				});
				count = 1;
			}			
			
			if (count > 0 && confirm(i18n.confirmDelete + ' (' + count + ' item/items)')) {				
				$.post(url, data, function(response) {
					if (response.operation === 'delete' && response.success === true) {
						for (elm in data) {
							if (data[elm].name == 'id[]') {
								form.find('input.id[value=' + data[elm].value + ']').parents('tr').css({backgroundColor:'red'}).fadeOut(function() {
									$(this).remove();
								});
							}
						}
						form.find('td.toolbar .button.multiple').removeClass('show');
					}
				}, 'json').error(function(jqXHR, message, exception) {
					$('body').removeClass('loading');
					errorHandler(i18n.requestError + ' (' + exception + ')');
				});
			}
		},
		
		deleteHandler: function(e) {
			e.stopPropagation();
			var self = this;
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			AC.Crud.List.multipleDeleteHandler.call(self, e, id);
		},
		
		prevHandler: function(e) {
			var form = $(this).parents('form');
			var $page = form.find('input[name=\'view[page]\']');
			$page.val(parseInt($page.val()) - 1);
			AC.Crud.List.updateView(this);
		},
		
		nextHandler: function(e) {
			var form = $(this).parents('form');
			var $page = form.find('input[name=\'view[page]\']');
			$page.val(parseInt($page.val()) + 1);
			AC.Crud.List.updateView(this);
		},
		
		pageSizeHandler: function(e) {
			AC.Crud.List.resetPage(this);
			AC.Crud.List.updateView(this);
		},
		
		orderHandler: function(e) {
			var form = $(this).parents('form');
			var $orderBy = form.find('input[name=\'view[orderBy]\']');
			var $orderDir = form.find('input[name=\'view[orderDir]\']');
			$orderBy.val($(this).attr('data-orderBy'));			
			$orderDir.val($(this).attr('data-orderDir'));
			AC.Crud.List.resetPage(this);
			AC.Crud.List.updateView(this);
		},
		
		searchBoxHandler: function(e) {
			if (e.which == 13) {
				AC.Crud.List.resetPage(this);
				AC.Crud.List.updateView(this);
			}
		},
		
		searchButtonHandler: function(e) {	
			AC.Crud.List.resetPage(this);
			AC.Crud.List.updateView(this);
		},
		
		resetPage: function(node) {
			var form = $(node).parents('form');
			var $page = form.find('input[name=\'view[page]\']');
			$page.val(1);	
		},
		
		updateView: function(node) {
			var form = $(node).parents('form');
			var data = form.serializeArray();
			
			var count = 0;
			for (elm in data) {
				if (data[elm].name.substr(0, 5) !== 'view[') {
					delete data[elm];
				}
			}
			
			form.find('tbody').animate({
				opacity: 0
			}, 1000);
			
			data.push({
				name: 'output',
				value: 'table'
			});		
			var url = document.location.href;
			
			$.get(url, data, function(response) {
				form.html($(response).filter('form').html());
				form.find('tbody').css({opacity: 1});
			}, 'html').error(function(jqXHR, message, exception) {
				$('body').removeClass('loading');
				errorHandler(i18n.requestError + ' (' + exception + ')');
			});
		}
		
	};
}();

$(document).ready(function() {
	AC.Crud.List.init();
});