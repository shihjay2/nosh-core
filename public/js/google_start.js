$(document).ready(function() {
	var myUpload1 = $("#google_upload").upload({
		action: 'google_upload',
		onComplete: function(data){
			$.jGrowl(data);
			$("#google_upload").parent().find('input').val('');
		}
	});
});
