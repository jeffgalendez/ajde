;
$(document).ready(function() {
	$('#registerform').bind('result', function(event, data) {
		if (data.success === false) {
			$("#registerformStatus").text(data.message);
		} else {
			window.location.href = 'user';
		}
	});
	$('#registerform').bind('error', function(event) {
		$("#registerformStatus").text('Something went wrong');
	});
	$('#registerform').bind('submit', function(event) {
		$("#registerformStatus").text("");
		return true;
	});
});
