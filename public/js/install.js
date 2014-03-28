$.ajaxSetup({
	headers: {"cache-control":"no-cache"},
	beforeSend: function(request) {
		return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
	}
});
//$(document).ajaxError(function() {
	//window.location.replace(noshdata.error);
//});
$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	$("#documents_dir").val(noshdata.documents).focusout(function(){
		var a = encodeURIComponent($("#documents_dir").val());
		$.ajax({
			type: "POST",
			url: "ajaxinstall/directory-check",
			data: "documents_dir=" + a,
			success: function(data){
				if (data != 'OK') {
					$.jGrowl(data);
				}
			}
		});
	});
	var states = {"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
	$("#state").addOption(states, false);
	$("#password").focus();
	$("#documents_dir").tooltip();
	$("#install_submit").button().click(function(){
		var bValid = true;
		$("#install").find("[required]").each(function() {
			var input_id = $(this).attr('id');
			var id1 = $("#" + input_id); 
			var text = $("label[for='" + input_id + "']").html();
			bValid = bValid && checkEmpty(id1, text);
			if (input_id == 'email') {
				bValid = bValid && checkRegexp(id1, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. user@nosh.com" );
			}
		});
		if (bValid) {
			var str = $("#install").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxinstall/install-process",
					data: str,
					success: function(data){
						if (data != 'OK') {
							$.jGrowl(data);
						} else {
							window.location = noshdata.url;
						}
					}
				});
				$("#install_progress_div").show();
				$("#install_progressbar").progressbar("option", "value", 0);
				setTimeout(install_progress, 1000);
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#install_progressbar").progressbar({
		value: false,
		change: function() {
			var value = $("#install_progressbar").progressbar("option", "value");
			$("#install_progressbar_label").text(value + "%" );
		},
		complete: function() {
			$("#install_progressbar_label").text( "Complete!" );
		}
	});
	function install_progress() {
		$.ajax({
			type: "POST",
			url: "ajaxinstall/install-progress",
			dataType: "json",
			success: function(data){
				$("#install_progressbar").progressbar("option","value", parseInt(data.install_progress));
				$("#install_progress_div").append("<br>" + data.install_note);
				if (data.install_progress < 99) {
					setTimeout(install_progress, 1000);
				}
			}
		});
	}
	
});
