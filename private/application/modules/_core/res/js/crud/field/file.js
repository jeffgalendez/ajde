;$(document).ready(function() {
	$('a.deleteFileCrud').click(function(e) {
		e.preventDefault();
		$filelist = $(this).parents('div.filelist');
		$fileupload = $filelist.next();
		$filelist.remove();
		$fileupload.removeClass('visuallyhidden');
		return false;
	});
});