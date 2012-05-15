;
$(document).ready(function() {
	
	var infoHandler		= AC.Core.Alert.show;
	var warningHandler	= AC.Core.Alert.warning;
	var errorHandler	= AC.Core.Alert.error;
	
	$('form.transactionPayment').bind('result', function(event, data) {
		if (data.success === false) {
			$('dd.status').addClass('error');			
			$('dd.status').text(data.message);
			AC.Core.Alert.hide();
		} else {
			if (data.postproxy) {
				$("dd.status").text('Redirecting you to the payment provider...');		
				AC.Core.Alert.show('Stand by, redirecting you to the payment provider...');
				$('#postproxy').html(data.postproxy);
				$('#postproxy form:eq(0)').submit();
			} else {
				alert('nothing to do...');
			}
		}
	});
	$('form.transactionPayment').bind('error', function(event, jqXHR, message, exception) {
		errorHandler(i18n.requestError);
	});
	$('form.transactionPayment').bind('submit', function(event) {
		$('dd.status').removeClass('error');
		$("dd.status").text('Getting ready for next step...');
		return true;
	});
	
});
