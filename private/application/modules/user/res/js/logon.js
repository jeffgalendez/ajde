;
$(document).ready(function() {
	$('#loginform').bind('result', function(event, data) {
		if (data.success === false) {
			$("#loginformStatus").text(data.message);
		} else {
			window.location.reload(true);
		}
	});
	$('#loginform').bind('error', function(event) {
		$("#loginformStatus").text('Something went wrong');
	});
	$('#loginform').bind('submit', function(event) {
		$("#loginformStatus").text("");
		return true;
	});
});
