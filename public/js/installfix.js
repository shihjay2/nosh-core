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
	$("#db_username").focus();
	$("#db_submit").button().click(function(){
		var bValid = true;
		var a = $("#db_username");
		bValid = bValid && checkEmpty(a,"MySQL username");
		if (bValid) {
			var str = $("#db_fix").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "db_fix",
					data: str,
					success: function(data){
						if (data != 'OK') {
							$.jGrowl(data);
						} else {
							window.location = noshdata.url;
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
});
