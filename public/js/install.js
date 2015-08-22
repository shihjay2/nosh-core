$.ajaxSetup({
	headers: {"cache-control":"no-cache"},
	beforeSend: function(request) {
		return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
	}
});
//$(document).ajaxError(function() {
	//window.location.replace(noshdata.error);
//});
$(document).idleTimeout({
	inactivity: 28800000,
	noconfirm: 10000,
	alive_url: noshdata.error,
	redirect_url: noshdata.logout_url,
	logout_url: noshdata.logout_url,
	sessionAlive: false
});
$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	$(".documents_dir").val(noshdata.documents).focusout(function(){
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
	$(".state").addOption(states, false);
	$(".documents_dir").tooltip();
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
				$('#dialog_load').dialog('option', 'title', "Customizing NOSH ChartingSystem...").dialog('open');
				$.ajax({
					type: "POST",
					url: "ajaxinstall/install-process/practice",
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
	$("#install_submit1").button().click(function(){
		var bValid = true;
		$("#install_patient_form").find("[required]").each(function() {
			var input_id = $(this).attr('id');
			var id1 = $("#" + input_id); 
			var text = $("label[for='" + input_id + "']").html();
			bValid = bValid && checkEmpty(id1, text);
			if (input_id == 'email1') {
				bValid = bValid && checkRegexp(id1, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. user@nosh.com" );
			}
		});
		var a = $("#password1").val();
		var b = $("#conf_password1").val();
		if (a != b) {
			$.jGrowl('Passwords do not match!');
			$("#password1").addClass("ui-state-error");
			$("#conf_password1").addClass("ui-state-error");
			bValid = false;
		}
		var c = $("#pt_password").val();
		var d = $("#pt_conf_password").val();
		if (c != d) {
			$.jGrowl('Passwords do not match!');
			$("#pt_password").addClass("ui-state-error");
			$("#pt_conf_password").addClass("ui-state-error");
			bValid = false;
		}
		var e = $("#username1").val();
		var f = $("#pt_username").val();
		if (e == f) {
			$.jGrowl('Usernames cannot be the same!');
			$("#username1").addClass("ui-state-error");
			$("#pt_username").addClass("ui-state-error");
			bValid = false;
		}
		if (bValid) {
			var str = $("#install_patient_form").serialize();
			if(str){
				$('#dialog_load').dialog('option', 'title', "Customizing NOSH ChartingSystem...").dialog('open');
				$.ajax({
					type: "POST",
					url: "ajaxinstall/install-process/patient",
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
	$("#install_choose_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 450, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		modal: true
	});
	if (noshdata.patient_centric == 'y') {
		$("#install_patient_form").show();
		$("#password1").focus();
	} else {
		$("#install_choose_dialog").dialog('open');
	}
	$("#DOB").mask("99/99/9999").datepicker();
	$("#install_patient").button().click(function() {
		$("#install_patient_form").show();
		$("#password1").focus();
		$("#install_choose_dialog").dialog('close');
	}).tooltip();
	$("#install_practice").button().click(function() {
		$("#install").show();
		$("#password").focus();
		$("#install_choose_dialog").dialog('close');
	}).tooltip();
});
