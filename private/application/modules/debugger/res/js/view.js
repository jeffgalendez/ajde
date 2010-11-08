$(document).ready(function() {
	elements = $('body *:not(#ajdeDebugger)');
	var lowest = 0;
	for (var i = 0; i < elements.length; i++) {
		bottom = $(elements[i]).offset().top + $(elements[i]).outerHeight();
		console.log(bottom);
		if (bottom > lowest) {
			lowest = bottom;
		}
	};
	$('#ajdeDebugger').animate({top: lowest + 'px'});
})
