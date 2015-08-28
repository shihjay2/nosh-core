$(document).ready(function() {
	if (noshdata.login_shake == 'y') {
		 $("#box").effect('shake');
	}
	$("#username").focus();
	$("#confirm").hide('fast');
	$("#login_button").button();
	$.ajax({
		type: "POST",
		url: "ajaxlogin/practices",
		dataType: "json",
		success: function(data){
			$("#practice_id").addOption(data.message);
			$("#practice_id").val(noshdata.practice_id);
			$.ajax({
				type: "POST",
				url: "ajaxlogin/practice-logo/" + noshdata.practice_id,
				success: function(data){
					$("#login_practice_logo").html(data);
				}
			});
		}
	});
	$("#practice_id").change(function(){
		var a = $("#practice_id").val();
		$.ajax({
			type: "POST",
			url: "ajaxlogin/practice-logo/" + a,
			success: function(data){
				$("#login_practice_logo").html(data);
			}
		});
	});
	$("#register_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 570, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function (event, ui) {
			$("#new_password_count").val("0");
			$('#numberReal').realperson({includeNumbers: true});
			$("#username1").removeClass("ui-state-error");
			$("#register_practice_id").val($("#practice_id").val());
		},
		close: function(event, ui) {
			$("#register_form").clearForm();
			$('#numberReal').realperson('destroy'); 
		}
	});
	$("#register").click(function(){
		$("#register_dialog").dialog('open');
	});
	$("#dob").mask("99/99/9999");
	$("#submit1").button().click(function(){
		var bValid = true;
		$("#register_form").find("[required]").each(function() {
			var input_id = $(this).attr('id');
			var id1 = $("#" + input_id); 
			var text = $("label[for='" + input_id + "']").html();
			bValid = bValid && checkEmpty(id1, text);
			if (input_id == 'username1') {
				bValid = bValid && checkRegexp(id1, /^\w+$/, "No whitespace or special characters!" );
			}
			if (input_id == 'email') {
				bValid = bValid && checkRegexp(id1, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. user@nosh.com" );
			}
		});
		if (bValid) {
			var str = $("#register_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxlogin/register-user",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.response == "1") {
							$("#new_password_id").val(data.id);
							$("#register_dialog").dialog('close');
							$("#new_password_dialog").dialog('open');
						} else if (data.response == "2") {
							$.jGrowl('Incorrect registration code, CAPTCHA, or patient information!');
							$("#new_password_count").val(data.count);
						} else if (data.response == "3") {
							$.jGrowl("Too many tries.  Contact the practice administrator to manually reset your password.");
							$("#register_dialog").dialog('close');
						} else if (data.response == "5") {
							$.jGrowl("You already have a registered account.  An e-mail has been sent to you with the username registered in your account.");
						} else {
							$.jGrowl('Your registration information has been sent to the administrator and you will receive your registration code within 48-72 hours by e-mail after confirmation of your idenity.<br>Thank you!');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#forgot_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 250, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$("#secret_question").html("");
			$("#forgot_password_form").clearForm();
			$("#forgot_password_form1").clearForm();
		}
	});
	$("#forgot_password").click(function(){
		var a = $("#username").val();
		if (a) {
			$.ajax({
				type: "POST",
				url: "ajaxlogin/forgot-password/" + a,
				dataType: "json",
				success: function(data){
					if (data.response == "You are not a registered user." || data.response == "You need to setup a secret question and answer.  Contact the practice administrator to manually reset your password." || data.response == "Use HIEofOne to reset your password.") {
						$.jGrowl(data.response);
					} else {
						$("#secret_question").html(data.response);
						$("#id").val(data.id);
						$("#count").val("0");
						$("#forgot_password_form1").hide();
						$("#forgot_password_dialog").dialog('open');
					}
				}
			});
		} else {
			$.jGrowl("Username field is required.");
		}
	});
	$("#submit2").button().click(function(){
		var a = $("#secret_answer");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Secret Answer");
		if (bValid) {
			var str = $("#forgot_password_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxlogin/forgot-password1",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.response == "OK") {
							$("#forgot_password_form").hide('fast');
							$("#forgot_password_form1").show('fast');
						} else if (data.response == "Close") {
							$.jGrowl("Too many tries.  Contact the practice administrator to manually reset your password.");
							$("#forgot_password_dialog").dialog('close');
						} else {
							$.jGrowl(data.response);
							$("#secret_answer").val('');
							$("#count").val(data.count);
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#submit3").button().click(function(){
		var a = $("#new_password");
		var b = $("#new_password_confirm");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"New Password");
		bValid = bValid && checkEmpty(b,"Confirm New Password");
		if (bValid) {
			var c = $("#new_password").val();
			var d = $("#new_password_confirm").val();
			if (c != d) {
				$.jGrowl("New passwords do not match!");
				$("#forgot_password_form1").clearForm();
			} else {
				var str = $("#forgot_password_form1").serialize();
				if(str){
					var id = $("#id").val();
					$.ajax({
						type: "POST",
						url: "ajaxlogin/change-password/" + id,
						data: str,
						success: function(data){
							$.jGrowl("Password changed.  Please login again.");
							$("#forgot_password_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		}
	});
	$("#new_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$("#new_password_form").clearForm();
		}
	});
	$("#submit4").button().click(function(){
		var a = $("#new_password1");
		var b = $("#new_password_confirm1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"New Password");
		bValid = bValid && checkEmpty(b,"Confirm New Password");
		if (bValid) {
			var c = $("#new_password1").val();
			var d = $("#new_password_confirm1").val();
			if (c != d) {
				$.jGrowl("New passwords do not match!");
				$("#new_password_form").clearForm();
			} else {
				var str = $("#new_password_form").serialize();
				if(str){
					var id = $("#new_password_id").val();
					$.ajax({
						type: "POST",
						url: "ajaxlogin/change-password/" + id,
						data: str,
						success: function(data){
							$.jGrowl("Password created.  Please login again.");
							$("#new_password_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		}
	});
	$('#username1').focusout(function() {
		$.ajax({
			type: "POST",
			url: "ajaxlogin/check-username",
			data: 'username=' + $('#username1').val(),
			dataType: "json",
			success: function(data){
				if (data.response == true) {
					$.jGrowl(data.message);
					$("#username1").addClass("ui-state-error");
				} else {
					$("#username1").removeClass("ui-state-error");
				}
			}
		});
	});
	$('#open_regular_box').click(function() {
		$('#regular_box').show();
		$('#openid_box').hide();
	});
	$('#open_openid_box').click(function() {
		console.log('Boom');
		$('#openid_box').show();
		$('#regular_box').hide();
	});
});
