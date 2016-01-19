$.ajaxSetup({
	headers: {"cache-control":"no-cache"},
	beforeSend: function(request) {
		return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
	}
});
$(document).ajaxError(function(event,xhr,options,exc) {
	if (xhr.status == "404" ) {
		alert("Route not found!");
		//window.location.replace(noshdata.error);
	} else {
		var response1 = $.parseJSON(xhr.responseText);
		var error = "Error:\nType: " + response1.error.type + "\nMessage: " + response1.error.message + "\nFile: " + response1.error.file;
		alert(error);
	}
});
$(document).ready(function() {
	$("#reset_submit").button().click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxinstall/reset-database",
			data: str,
			success: function(data){
				if (data != 'OK') {
					$.jGrowl(data);
				} else {
					window.location = noshdata.url;
				}
			}
		});
	});
});
