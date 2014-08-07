$(document).ready(function() {
	function updateCoords(c) {
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	function practice_logo() {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/get-practice-logo",
			dataType: 'json',
			success: function(data){
				$("#practice_logo_upload_preview").html(data.link);
				$("#practice_logo_message").html(data.message);
				if (data.button != "") {
					$('#image_target').Jcrop({
						maxSize: [350, 100],
						onSelect: updateCoords
					});
					$("#practice_logo_message").append(data.button);
					$('#image_crop').button().click(function(){
						var a = $('#x').val();
						if (a != '') {
							var str = "x=" + $('#x').val() + "&y=" + $('#y').val() + "&w=" + $('#w').val() + "&h=" + $('#h').val();
							$.ajax({
								type: "POST",
								url: "ajaxsetup/cropimage",
								data: str,
								dataType: 'json',
								success: function(data){
									$.jGrowl(data.growl);
									$("#practice_logo_upload_preview").html(data.link);
									$("#practice_logo_message").html(data.message);
								}
							});
						} else {
							$.jGrowl('Select cropping area!  Hint: Move your mouse over the preview image.');
						}
					});
				}
			}
		});
	}
	$("#setup_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#setup_accordion").accordion({
				heightStyle: "content",
				active: false,
				collapsible: true,
				beforeActivate: function (event, ui) {
					if(ui.newPanel[0]) {
						var id = ui.newPanel[0].id;
						$("#" + id + " .text").first().focus();
						if(ui.oldPanel[0]) {
							var old_id = ui.oldPanel[0].id;
							var form_id = $("#" + old_id + " form").attr('id');
							var bValid = true;
							$("#" + form_id).find("[required]").each(function() {
								var input_id = $(this).attr('id');
								var id1 = $("#" + input_id); 
								var text = $("label[for='" + input_id + "']").html();
								bValid = bValid && checkEmpty(id1, text);
							});
							var bValid1 = false;
							$("#" + form_id).find(".text").each(function() {
								if (bValid1 == false) {
									var input_id = $(this).attr('id');
									var a = $("#" + input_id).val();
									var b = $("#" + input_id + "_old").val();
									if (a != b) {
										bValid1 = true;
									}
								}
							});
							if (bValid) {
								if (bValid1) {
									var str = $("#" + form_id).serialize();
									if(str){
										$.ajax({
											type: "POST",
											url: "ajaxsetup/" + form_id,
											data: str,
											success: function(data){
												$.jGrowl(data);
												$("#" + form_id).find(".text").each(function() {
													if (bValid1 == false) {
														var input_id = $(this).attr('id');
														var a = $("#" + input_id).val();
														$("#" + input_id + "_old").val(a);
													}
												});
												return true;
											}
										});
									} else {
										$.jGrowl("Please complete the form");
										return false;
									}
								} else {
									return true;
								}
							} else {
								return false;
							}
						} else {
							return true;
						}
					} else {
						return true;
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxsetup/get-practice",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#setup_accordion :input[name='" + key + "']").val(value);
						$("#" + key + "_old").val(value);
						if (key == 'fax_type') {
							if (value == '') {
								$("#fax_show1").hide();
								$("#fax_show2").hide();
							}
							if (value == 'efaxsend.com' || value == 'rcfax.com' || value == 'metrofax.com') {
								$("#fax_show1").show();
								$("#fax_show2").hide();
							}
							if (value == 'phaxio') {
								$("#fax_show1").hide();
								$("#fax_show2").show();
							}
						}
					});
				}
			});
			$("#default_pos_id").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/pos",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							}
						}
					});
				},
				minLength: 1
			});
			practice_logo();
		},
		beforeClose: function(event, ui) {
			var active = $("#setup_accordion").accordion("option", "active");
			if (active != 2) {
				var num = active + 1;
				var form_id = "setup" + num;
				var bValid = true;
				$("#" + form_id).find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				var bValid1 = false;
				$("#" + form_id).find(".text").each(function() {
					if (bValid1 == false) {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						var b = $("#" + input_id + "_old").val();
						if (a != b) {
							bValid1 = true;
						}
					}
				});
				if (bValid) {
					if (bValid1) {
						var str = $("#" + form_id).serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "ajaxsetup/" + form_id,
								data: str,
								success: function(data){
									$.jGrowl(data);
									$("#" + form_id).find(".text").each(function() {
										if (bValid1 == false) {
											var input_id = $(this).attr('id');
											var a = $("#" + input_id).val();
											$("#" + input_id + "_old").val(a);
										}
									});
									return true;
								}
							});
						} else {
							$.jGrowl("Please complete the form");
							return false;
						}
					} else {
						return true;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#dashboard_setup").click(function(){
		$("#setup_dialog").dialog('open');
	});
	$("#state").addOption(states, false);
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	$('#documents_dir').focusout(function() {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/check-dir",
			data: 'documents_dir=' + $('#documents_dir').val(),
			success: function(data){
				if (data != 'OK') {
					$.jGrowl(data);
					$("#documents_dir").addClass("ui-state-error");
				} else {
					$("#documents_dir").removeClass("ui-state-error");
				}
			}
		});
	});
	$("#npi").mask("9999999999");
	$("#tax_id").mask("99-9999999");
	var myUpload1 = $("#practice_logo_upload_submit").upload({
		action: 'practicelogoupload',
		onComplete: function(data){
			$.jGrowl(data);
			practice_logo();
		}
	});
	$('#practice_logo_none').button().click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/no-practice-logo",
			success: function(data){
				$("#practice_logo_upload_preview").html('');
				$("#practice_logo_message").html('');
			}
		});
	});
	$("#transfer_address").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var a = $("#street_address1").val();
		var b = $("#street_address2").val();
		var c = $("#city").val();
		var d = $("#state").val();
		var e = $("#zip").val();
		$("#billing_street_address1").val(a);
		$("#billing_street_address2").val(b);
		$("#billing_city").val(c);
		$("#billing_state").val(d);
		$("#billing_zip").val(e);
	});
	$("#fax_type").addOption({"":"No fax service.","efaxsend.com":"eFax","rcfax.com":"ExtremeFax","metrofax.com":"MetroFax","phaxio":"Phaxio","rcfax.com":"RingCentral"});
	$("#fax_type").change(function () {
		var a = $("#fax_type").val();
		if (a == '') {
			$("#fax_show1").hide();
			$("#fax_show2").hide();
		}
		if (a == 'efaxsend.com' || a == 'rcfax.com' || a == 'metrofax.com') {
			$("#fax_show1").show();
			$("#fax_show2").hide();
		}
		if (a == 'phaxio') {
			$("#fax_show1").hide();
			$("#fax_show2").show();
		}
	});
	$("#fax_email").change(function (){
		var a = $("#fax_email").val();
		var b = a.substr(a.indexOf("@")+1);
		var c = b.substring(0, b.indexOf("."));
		if (c == "yahoo") {
			var imap = "imap.mail." + b + ":993";
			var smtp = "smtp.mail.yahoo.com"
		} else {
			var imap = "imap." + b + ":993";
			var smtp = "smtp." + b;
		}
		$("#fax_email_hostname").val(imap);
		$("#fax_email_smtp").val(smtp);
	});
	$("#billing_state").addOption(states, false);
	$("#encounter_template").addOption({'standardmedical':'Standard Medical Visit','standardpsych':'Annual Psychiatric Evaluation','standardpsych1':'Psychiatric Encounter','clinicalsupport':'Clinical Support Visit','standardmtm':'MTM Encounter'}, false);
	$("#opennotes_tip").tooltip({ content: "Selecting Yes will allow users of the patient portal to view their encounter notes.  Click on the hyperlink for more details." });
});
