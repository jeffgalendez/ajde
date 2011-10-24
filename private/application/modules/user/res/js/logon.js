;$(document).ready(function() {
	$('#loginform').bind('result', function(events, data) {
		if (data.success === false) {
			$("#loginformStatus").text(data.message);
		} else {
			window.location.reload(true);
		}
	});
	$('#loginform').bind('submit', function(events) {
		$("#loginformStatus").text("");
		return true;
	});
});
