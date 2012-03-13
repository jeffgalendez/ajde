/**
 * Sample JS module
 */

;
(function($) {
	
	var init = function() {		
		$('form.ACCrudList td.buttons a.view').live('click', viewHandler);
		
	};
	
	var viewHandler = function(e) {
		e.stopPropagation();
		if ($(this)[0].nodeName == 'TR' || $(this)[0].nodeName == 'tr') {
			var row = $(this);
		} else {
			var row = $(this).parents('tr');
		}
		var id = row.find('input[type=checkbox]').attr('value');			
		var form = $(this).parents('form');

		window.location.href = window.location.pathname + '?view';		
	};

	$(document).ready(init);

})(jQuery);