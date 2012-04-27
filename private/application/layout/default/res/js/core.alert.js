;
if (typeof AC ==="undefined") {AC = function(){}};
if (typeof AC.Core ==="undefined") {AC.Core = function(){}};

AC.Core.Alert = function() {
	
	var elements = $('#alert-wrapper');
	var textContainer = $('#alert-text');
	
	var callback;
	
	return {
		
		init: function() {
			if (!$('alert-wrapper').length) {				
				var a = $("<div id='alert-wrapper'><div id='alert-content'><p id='alert-text'></p></div></div>");
				$('body').append(a);
				elements = $('#alert-wrapper');
				textContainer = $('#alert-text');
			}			
			elements.hide();
			elements.click(AC.Core.Alert.hide);
		},
		
		show: function(text, c) {
			callback = c;
			elements.removeClass();
			textContainer.text(text);
			elements.fadeIn('fast');			
		},
		
		warning: function(text, c) {
			AC.Core.Alert.show(text, c);
			elements.addClass('warning');
		},
		
		error: function(text, c) {
			AC.Core.Alert.show(text, c);
			elements.addClass('error');
		},
		
		hide: function() {
			elements.fadeOut('fast');
			if (callback) {
				callback();
			}			
		}
	};
}();

$(document).ready(function() {
	AC.Core.Alert.init();
});