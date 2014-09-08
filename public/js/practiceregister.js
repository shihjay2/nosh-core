$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	var states = {"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
	$(".state").addOption(states, false);
	$("#practice_name").focusout(function() {
		var a = $(this).val();
		var b = a.replace(/\s/g, '');
		var c = b.toLowerCase();
		$("#practicehandle").val(c);
		$("#practicehandleval").html(c);
	});
	$("#practicehandle").tooltip({
		position: { my: "left+15 center", at: "right center", collision: "flipfit" }
	});
	$("#practicehandle").focusout(function() {
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "../ajaxinstall/check-practicehandle",
			data: "practicehandle=" + a,
			success: function(data){
				if (data != "OK") {
					$("#practicehandle").val('').focus();
					$.jGrowl(data);
				} else {
					$("#practicehandleval").html(a);
				}
			}
		});
	});
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
		var a = $("#password").val();
		var b = $("#conf_password").val();
		if (a != b) {
			$.jGrowl('Passwords do not match!');
			$("#password").addClass("ui-state-error");
			$("#conf_password").addClass("ui-state-error");
			bValid = false;
		}
		if (bValid) {
			var str = $("#install").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "../ajaxinstall/practice-register",
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
