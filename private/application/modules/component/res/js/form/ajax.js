if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Form ==="undefined") { 	AC.Form = function() {}; }

AC.Form.Ajax = function() {
	return {
		
		init: function() {
			$('form.ACAjaxForm').submit(AC.Form.Ajax.submitHandler);
		},
		
		submitHandler: function() {
			var url = $(this).attr('action');
			var data = $(this).serialize();
			var form = this;
			var success = function(data) { $(form).trigger('result', [data]); };
			var dataType = 'json';
			$.post(url, data, success, dataType);
			return false;	
		}
	};
}();

$(document).ready(function() {
	AC.Form.Ajax.init();
})
