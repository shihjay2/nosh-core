function checkEmpty(o,n) {
	if (o.val() === '' || o.val() === null) {
		var text = n.replace(":","");
		$.jGrowl(text + " Required");
		o.addClass("ui-state-error");
		return false;
	} else {
		o.removeClass("ui-state-error");
		return true;
	}
}
function checkRegexp( o, regexp, n ) {
	if ( !( regexp.test( o.val() ) ) ) {
		var text = n.replace(":","");
		$.jGrowl("Incorrect format: " + text);
		o.addClass("ui-state-error");
		return false;
	} else {
		o.removeClass("ui-state-error");
		return true;
	}
}
function split( val ) {
	return val.split( /\n\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}
function reload_grid(id) {
	if ($("#"+id)[0].grid) {
		jQuery("#"+id).trigger("reloadGrid");
	}
}
function openencounter() {
	$('#dialog_load').dialog('option', 'title', "Loading encounter...").dialog('open');
	$("#encounter_body").html('');
	$("#encounter_body").load('ajaxencounter/loadtemplate');
	$("#encounter_link_span").html('<a href="#" id="encounter_panel">[Active Encounter #: ' + noshdata.eid + ']</a>');
	$("#encounter_panel").click(function() {
		noshdata.encounter_active = 'y';
		openencounter();
		$("#nosh_chart_div").hide();
		$("#nosh_encounter_div").show();
	});
	$.ajax({
		type: "POST",
		url: "ajaxsearch/get-tags/eid/" + noshdata.eid,
		dataType: "json",
		success: function(data){
			$("#encounter_tags").tagit("fill",data);
		}
	});
}
function closeencounter() {
	noshdata.encounter_active = 'n';
	$("#nosh_encounter_div").hide();
	$("#nosh_chart_div").show();
	$("#encounter_link_span").html('');
}
function loadbuttons() {
	$(".nosh_button").button();
	$(".nosh_button_save").button({icons: {primary: "ui-icon-disk"}});
	$(".nosh_button_cancel").button({icons: {primary: "ui-icon-close"}});
	$(".nosh_button_delete").button({icons: {primary: "ui-icon-trash"}});
	$(".nosh_button_calculator").button({icons: {primary: "ui-icon-calculator"}});
	$(".nosh_button_check").button({icons: {primary: "ui-icon-check"}});
	$(".nosh_button_preview").button({icons: {primary: "ui-icon-comment"}});
	$(".nosh_button_edit").button({icons: {primary: "ui-icon-pencil"}});
	$(".nosh_button_add").button({icons: {primary: "ui-icon-plus"}});
	$(".nosh_button_print").button({icons: {primary: "ui-icon-print"}});
	$(".nosh_button_alert").button({icons: {primary: "ui-icon-alert"}});
	$(".nosh_button_copy").button({icons: {primary: "ui-icon-copy"}});
	$(".nosh_button_extlink").button({icons: {primary: "ui-icon-extlink"}});
	$(".nosh_button_reactivate").button({icons: {primary: "ui-icon-arrowreturnthick-1-w"}});
	$(".nosh_button_reply").button({icons: {primary: "ui-icon-arrowreturn-1-w"}});
	$(".nosh_button_forward").button({icons: {primary: "ui-icon-arrow-1-e"}});
	$(".nosh_button_open").button({icons: {primary: "ui-icon-folder-open"}});
	$(".nosh_button_calendar").button({icons: {primary: "ui-icon-calendar"}});
	$(".nosh_button_cart").button({icons: {primary: "ui-icon-cart"}});
}
function menu_update(type) {
	$.ajax({
		type: "POST",
		url: "ajaxchart/" + type + "-list",
		success: function(data){
			$("#menu_accordion_" + type + "-list_content").html(data);
			$("#menu_accordion_" + type + "-list_load").hide();
		}
	});
}
function refresh_documents() {
	$.ajax({
		type: "POST",
		url: "ajaxsearch/documents-count",
		dataType: "json",
		success: function(data){
			jQuery("#labs").jqGrid('setCaption', 'Labs: ' + data.labs_count);
			jQuery("#radiology").jqGrid('setCaption', 'Imaging: ' + data.radiology_count);
			jQuery("#cardiopulm").jqGrid('setCaption', 'Cardiopulmonary: ' + data.cardiopulm_count);
			jQuery("#endoscopy").jqGrid('setCaption', 'Endoscopy: ' + data.endoscopy_count);
			jQuery("#referrals").jqGrid('setCaption', 'Referrals: ' + data.referrals_count);
			jQuery("#past_records").jqGrid('setCaption', 'Past Records: ' + data.past_records_count);
			jQuery("#outside_forms").jqGrid('setCaption', 'Outside Forms: ' + data.outside_forms_count);
			jQuery("#letters").jqGrid('setCaption', 'Letters: ' + data.letters_count);
		}
	});
}
function checkorders() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/check-orders",
		dataType: "json",
		success: function(data){
			$('#button_orders_labs_status').html(data.labs_status);
			$('#button_orders_rad_status').html(data.rad_status);
			$('#button_orders_cp_status').html(data.cp_status);
			$('#button_orders_ref_status').html(data.ref_status);
			$('#button_orders_rx_status').html(data.rx_status);
			$('#button_orders_imm_status').html(data.imm_status);
			$('#button_orders_sup_status').html(data.sup_status);
		}
	});
}
function check_oh_status() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/check-oh",
		dataType: "json",
		success: function(data){
			$('#button_oh_sh_status').html(data.sh_status);
			$('#button_oh_etoh_status').html(data.etoh_status);
			$('#button_oh_tobacco_status').html(data.tobacco_status);
			$('#button_oh_drugs_status').html(data.drugs_status);
			$('#button_oh_employment_status').html(data.employment_status);
			$('#button_oh_meds_status').html(data.meds_status);
			$('#button_oh_supplements_status').html(data.supplements_status);
			$('#button_oh_allergies_status').html(data.allergies_status);
		}
	});
}
function check_ros_status() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/check-ros",
		dataType: "json",
		success: function(data){
			$('#button_ros_gen_status').html(data.gen);
			$('#button_ros_eye_status').html(data.eye);
			$('#button_ros_ent_status').html(data.ent);
			$('#button_ros_resp_status').html(data.resp);
			$('#button_ros_cv_status').html(data.cv);
			$('#button_ros_gi_status').html(data.gi);
			$('#button_ros_gu_status').html(data.gu);
			$('#button_ros_mus_status').html(data.mus);
			$('#button_ros_neuro_status').html(data.neuro);
			$('#button_ros_psych_status').html(data.psych);
			$('#button_ros_heme_status').html(data.heme);
			$('#button_ros_endocrine_status').html(data.endocrine);
			$('#button_ros_skin_status').html(data.skin);
			$('#button_ros_wcc_status').html(data.wcc);
		}
	});
}
function check_pe_status() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/check-pe",
		dataType: "json",
		success: function(data){
			$('#button_pe_gen_status').html(data.gen);
			$('#button_pe_eye_status').html(data.eye);
			$('#button_pe_ent_status').html(data.ent);
			$('#button_pe_neck_status').html(data.neck);
			$('#button_pe_resp_status').html(data.resp);
			$('#button_pe_cv_status').html(data.cv);
			$('#button_pe_ch_status').html(data.ch);
			$('#button_pe_gi_status').html(data.gi);
			$('#button_pe_gu_status').html(data.gu);
			$('#button_pe_lymph_status').html(data.lymph);
			$('#button_pe_ms_status').html(data.ms);
			$('#button_pe_neuro_status').html(data.neuro);
			$('#button_pe_psych_status').html(data.psych);
			$('#button_pe_skin_status').html(data.skin);
		}
	});
}
function check_labs1() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/check-labs",
		dataType: "json",
		success: function(data){
			$('#button_labs_ua_status').html(data.ua);
			$('#button_labs_rapid_status').html(data.rapid);
			$('#button_labs_micro_status').html(data.micro);
			$('#button_labs_other_status').html(data.other);
		}
	});
}
function total_balance() {
	$.ajax({
		type: "POST",
		url: "ajaxchart/total-balance",
		success: function(data){
			$('#total_balance').html(data);
		}
	});
}
function hpi_autosave(type) {
	var old0 = $("#"+type+"_old").val();
	var new0 = $("#"+type).val();
	if (old0 != new0) {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/hpi-save/" + type,
			data: type+'=' + $("#"+type).val(),
			success: function(data){
				$.jGrowl(data);
				var a = $("#"+type).val();
				$("#"+type+"_old").val(a);
			}
		});
	}
}
function oh_autosave() {
	var bValid = false;
	$("#oh_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var oh_str = $("#oh_form").serialize();
		if(oh_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/oh-save",
				data: oh_str,
				success: function(data){
					$.jGrowl(data);
					$("#oh_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function vitals_autosave() {
	var bValid = false;
	$("#vitals_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				console.log(input_id+","+a+","+b);
				bValid = true;
			}
		}
	});
	if (bValid) {
		var vitals_str = $("#vitals_form").serialize();
		if(vitals_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/vitals-save",
				data: vitals_str,
				success: function(data){
					$.jGrowl(data);
					$("#vitals_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function proc_autosave() {
	var bValid = false;
	$("#procedure_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var proc_str = $("#procedure_form").serialize();
		if(proc_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/proc-save",
				data: proc_str,
				success: function(data){
					$.jGrowl(data);
					$("#procedure_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function assessment_autosave() {
	var bValid = false;
	$("#assessment_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var assessment_str = $("#assessment_form").serialize();
		if(assessment_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/assessment-save",
				data: assessment_str,
				success: function(data){
					$.jGrowl(data);
					$("#assessment_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
					$.ajax({
						type: "POST",
						url: "ajaxencounter/get-billing",
						dataType: "json",
						success: function(data){
							$("#billing_icd").removeOption(/./);
							$("#billing_icd").addOption(data, false);
						}
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function orders_autosave() {
	var bValid = false;
	$("#orders_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var orders_str = $("#orders_form").serialize();
		if(orders_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/orders-save",
				data: orders_str,
				success: function(data){
					$.jGrowl(data);
					$("#orders_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function billing_autosave() {
	var bValid = false;
	$("#encounter_billing_form").find(".text").each(function() {
		if (bValid == false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var billing_str = $("#encounter_billing_form").serialize();
		if(billing_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/billing-save1",
				data: billing_str,
				success: function(data){
					$.jGrowl(data);
					$("#encounter_billing_form").find(".text").each(function() {
						var input_id = $(this).attr('id');
						var a = $("#" + input_id).val();
						$("#" + input_id + "_old").val(a);
					});
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
}
function pending_order_load(item) {
	$.ajax({
		url: "ajaxchart/order-type/" + item,
		dataType: "json",
		type: "POST",
		success: function(data){
			var label = data.label;
			var status = "";
			var type = "";
			if (label == 'messages_lab') {
				status = 'Details for Lab Order #' + item;
				type = 'lab';
			}
			if (label == 'messages_rad') {
				status = 'Details for Radiology Order #' + item;
				type = 'rad';
			}
			if (label == 'messages_cp') {
				status = 'Details for Cardiopulmonary Order #' + item;
				type = 'cp';
			}
			load_outside_providers(type,'edit');
			$.each(data, function(key, value){
				if (key != 'label') {
					if (key == 'orders_pending_date') {
						var value = getCurrentDate();
					}
					$("#edit_"+label+"_form :input[name='" + key + "']").val(value);
				}
			});
			$("#"+label+"_status").html(status);
			if ($("#"+label+"_provider_list").val() == '' && noshdata.group_id == '2') {
				$("#"+label+"_provider_list").val(noshdata.user_id);
			}
			$("#"+label+"_edit_fields").dialog("option", "title", "Edit Lab Order");
			$("#"+label+"_edit_fields").dialog('open');
		}
	});
}
function load_outside_providers(type,action) {
	$("#messages_"+type+"_location").removeOption(/./);
	var type1 = '';
	var type2 = '';
	if (type == 'lab') {
		type1 = 'Laboratory';
		type2 = 'lab';
	}
	if (type == 'rad') {
		type1 = 'Radiology';
		type2 = 'imaging';
	}
	if (type == 'cp') {
		type1 = 'Cardiopulmonary';
		type2 = 'cardiopulmonary';
	}
	$.ajax({
		url: "ajaxsearch/orders-provider/" + type1,
		dataType: "json",
		type: "POST",
		async: false,
		success: function(data){
			if(data.response == 'true'){
				$("#messages_"+type+"_location").addOption({"":"Add "+type2+" provider."}, false);
				$("#messages_"+type+"_location").addOption(data.message, false);
			} else {
				$("#messages_"+type+"_location").addOption({"":"No "+type2+" provider.  Click Add."}, false);
			}
		}
	});
	$("#messages_"+type+"_provider_list").removeOption(/./);
	$.ajax({
		url: "ajaxsearch/provider-select",
		dataType: "json",
		type: "POST",
		async: false,
		success: function(data){
			$("#messages_"+type+"_provider_list").addOption({"":"Select a provider for the order."}, false);
			$("#messages_"+type+"_provider_list").addOption(data, false);
			if(action == 'add') {
				if (noshdata.group_id == '2') {
					$("#messages_"+type+"_provider_list").val(noshdata.user_id);
				} else {
					$("#messages_"+type+"_provider_list").val('');
				}
			}
		}
	});
}
function ros_form_load() {
	$('.ros_buttonset').buttonset();
	$('.ros_detail_text').hide();
	$("#ros_gu_menarche").datepicker();
	$("#ros_gu_lmp").datepicker();
}
function get_ros_templates(group, id, type) {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-ros-templates/" + group + "/" + id + "/" + type,
		dataType: "json",
		success: function(data){
			$('#'+group+'_form').html('');
			$('#'+group+'_form').dform(data);
			ros_form_load();
		}
	});
}
function ros_dialog_open() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/ros-template-select-list",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_template').addOption({"":"*Select a template"}, false);
				$('#'+key+'_template').addOption(value, false);
				$('#'+key+'_template').sortOptions();
				$('#'+key+'_template').val("");
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-default-ros-templates",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_form').html('');
				$('#'+key+'_form').dform(value);
				ros_form_load();
			});
			
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-ros",
		dataType: "json",
		success: function(data){
			if (data != '') {
				$.each(data, function(key, value){
					if (key != 'eid' || key != 'pid' || key != 'ros_date' || key != 'encounter_provider') {
						$('#'+key).val(value);
						$('#'+key+'_old').val(value);
					}
				});
			}
		}
	});
}
function pe_form_load() {
	$('.pe_buttonset').buttonset();
	$('.pe_detail_text').hide();
}
function get_pe_templates(group, id, type) {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-pe-templates/" + group + "/" + id + "/" + type,
		dataType: "json",
		success: function(data){
			$('#'+group+'_form').html('');
			$('#'+group+'_form').dform(data);
			pe_form_load();
		}
	});
}
function pe_accordion_action(id, dialog_id) {
	$("#" + id + " .text").first().focus();
	$("#"+dialog_id).find('.pe_entry').each(function(){
		var parent_id1 = $(this).attr("id");
		if (!!$(this).val()) {
			$('#' + parent_id1 + '_h').html(noshdata.item_present);
		} else {
			$('#' + parent_id1 + '_h').html(noshdata.item_empty);
		}
	});
}
function pe_dialog_open() {
	$('.pe_dialog').each(function() {
		var dialog_id = $(this).attr('id');
		var accordion_id = dialog_id.replace('_dialog', '_accordion');
		if (!$("#"+accordion_id).hasClass('ui-accordion')) {
			$("#"+accordion_id).accordion({
				create: function(event, ui) {
					var id = ui.panel[0].id;
					pe_accordion_action(id, dialog_id);
				},
				activate: function(event, ui) {
					var id = ui.newPanel[0].id;
					pe_accordion_action(id, dialog_id);
				},
				heightStyle: "content"
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/pe-template-select-list",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_template').addOption({"":"*Select a template"}, false);
				$('#'+key+'_template').addOption(value, false);
				$('#'+key+'_template').sortOptions();
				$('#'+key+'_template').val("");
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-default-pe-templates",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_form').html('');
				$('#'+key+'_form').dform(value);
				pe_form_load();
			});
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-pe",
		dataType: "json",
		success: function(data){
			if (data != '') {
				$.each(data, function(key, value){
					if (key != 'eid' || key != 'pid' || key != 'pe_date' || key != 'encounter_provider') {
						$('#'+key).val(value);
						$('#'+key+'_old').val(value);
						if (!!value) {
							$('#' + key + '_h').html(noshdata.item_present);
						} else {
							$('#' + key + '_h').html(noshdata.item_empty);
						}
						
						
					}
				});
			}
		}
	});
}
function parse_date(string) {
	var date = new Date();
	var parts = String(string).split(/[- :]/);
	date.setFullYear(parts[0]);
	date.setMonth(parts[1] - 1);
	date.setDate(parts[2]);
	date.setHours(parts[3]);
	date.setMinutes(parts[4]);
	date.setSeconds(parts[5]);
	date.setMilliseconds(0);
	return date;
}
function parse_date1(string) {
	var date = new Date();
	var parts = String(string).split("/");
	date.setFullYear(parts[2]);
	date.setMonth(parts[0] - 1);
	date.setDate(parts[1]);
	date.setHours(0);
	date.setMinutes(0);
	date.setSeconds(0);
	date.setMilliseconds(0);
	return date;
}
function editDate(string) {
	var result = string.split("-");
	var edit_date = result[1] + '/' + result[2] + '/' + result[0];
	return edit_date;
}
function editDate1(string) {
	var result1 = string.split(" ");
	var result = result1[0].split("-");
	var edit_date = result[1] + '/' + result[2] + '/' + result[0];
	if (edit_date == '00/00/0000') {
		var edit_date1 = '';
	} else {
		var edit_date1 = edit_date;
	}
	return edit_date1;
}
function editDate2(string) {
	var result1 = string.split(" ");
	var result = result1[1].split(":");
	var hour1 = result[0];
	var hour2 = parseInt(hour1);
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		var pm = 'PM';
		if (hour4.length == 1) {
			var hour = "0" + hour4;
		} else {
			var hour = hour4;
		}
	} else {
		if (hour2 == 0) {
			var hour = '12';
			var pm = 'AM';
		}
		if (hour2 == 12) {
			var hour = hour2;
			var pm = 'PM';
		}
		if (hour2 < 12) {
			var pm = 'AM';
			if (hour2.length == 1) {
				var hour = "0" + hour2;
			} else {
				var hour = hour2;
			}
		}
	}
	var minute1 = result[1];
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		var minute = "0" + minute2;
	} else {
		var minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}
function getCurrentDate() {
	var d = new Date();
	var day1 = d.getDate();
	var day2 = day1 + '';
	if (day2.length == 1) {
		var day = "0" + day2;
	} else {
		var day = day2;
	}
	var month1 = d.getMonth();
	var month2 = parseInt(month1);
	var month3 = month2 + 1;
	var month4 = month3 + '';
	if (month4.length == 1) {
		var month = "0" + month4;
	} else {
		var month = month4;
	}
	var date = month + "/" + day + "/" + d.getFullYear();
	return date;
}
function getCurrentTime() {
	var d = new Date();
	var hour1 = d.getHours();
	var hour2 = parseInt(hour1);
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		var pm = 'PM';
		if (hour4.length == 1) {
			var hour = "0" + hour4;
		} else {
			var hour = hour4;
		}
	} else {
		if (hour2 == 0) {
			var hour = '12';
			var pm = 'AM';
		}
		if (hour2 == 12) {
			var hour = hour2;
			var pm = 'PM';
		}
		if (hour2 < 12) {
			var pm = 'AM';
			if (hour2.length == 1) {
				var hour = "0" + hour2;
			} else {
				var hour = hour2;
			}
		}
	}
	var minute1 = d.getMinutes();
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		var minute = "0" + minute2;
	} else {
		var minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}
$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = 0;
	});
};
$.fn.clearDiv = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'div')
			return $(':input',this).clearForm();
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea')
			this.value = '';
		else if (type == 'checkbox' || type == 'radio')
			this.checked = false;
		else if (tag == 'select')
			this.selectedIndex = 0;
	});
};
$.fn.serializeJSON = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};
$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var that = this,
		currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			that._renderItemData( ul, item );
		});
	}
});
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
		if(xhr.responseText){
			var response1 = $.parseJSON(xhr.responseText);
			var error = "Error:\nType: " + response1.error.type + "\nMessage: " + response1.error.message + "\nFile: " + response1.error.file;
			alert(error);
		}
	}
});
$.extend($.jgrid.defaults, {
	ajaxGridOptions : {
		beforeSend: function(xhr) {
			return xhr.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
		}
	}
});
$(document).idleTimeout({
	inactivity: 3600000,
	noconfirm: 10000,
	alive_url: noshdata.error,
	redirect_url: noshdata.logout_url,
	logout_url: noshdata.logout_url,
	sessionAlive: false
});
var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
$(document).ready(function() {
	loadbuttons();
	$(".nosh_tooltip").tooltip();
	$(".phonemask").mask("(999) 999-9999");
	$("#switcher").themeswitcher({
		imgpath: noshdata.images,
		loadtheme: "redmond"
	});
	$("#dialog_load").dialog({
		height: 75,
		autoOpen: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		modal: true
	});
	var tz = jstz.determine();
	$.cookie('nosh_tz', tz.name(), { path: '/' });
});
$(document).on("click", ".ui-jqgrid-titlebar", function() {
	$(".ui-jqgrid-titlebar-close", this).click();
});
$(document).ajaxStop(function() {
  $('#dialog_load').dialog('close');
});
function updateTextArea(parent_id_entry) {
	var newtext = '';
	$('#' + parent_id_entry + '_form :checked').each(function() {
		newtext += $(this).val() + '  ';
	});
	$('#' + parent_id_entry).val(newtext);
}
function ros_normal(parent_id) {
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + parent_id + "_div").find('.ros_other:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$("#" + parent_id_entry + "_form input").button('refresh');
		if (parts[1] == 'wccage') {
			$("#ros_wcc_age_form input").button('refresh');
		}
	});
	$("#" + parent_id + "_div").find('.ros_detail_text').each(function(){
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = ' ' + $(this).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$(this).hide();
	});
}
function ros_other(parent_id) {
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + parent_id + "_div").find('.ros_normal:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$("#" + parent_id_entry + "_form input").button('refresh');
		if (parts[1] == 'wccage') {
			$("#ros_wcc_age_form input").button('refresh');
		}
	});
}
$(document).on("click", '.ros_template_div input[type="checkbox"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if ($(this).prop('checked')) {
		if (old != '') {
			var b = old + a + '  ';
		} else {
			var b = a + '  ';
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.ros_normal')) {
			ros_normal(parent_id);
		}
		if ($(this).is('.ros_other')) {
			ros_other(parent_id);
		}
	} else {
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c); 
	}
});
$(document).on("click", '.ros_template_div input[type="radio"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if ($(this).prop('checked')) {
		if (old != '') {
			var b = old + a + '  ';
		} else {
			var b = a + '  ';
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.ros_normal')) {
			ros_normal(parent_id);
		}
		if ($(this).is('.ros_other')) {
			ros_other(parent_id);
		}
	} else {
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c); 
	}
});
$(document).on("change", '.ros_template_div select', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if (old != '') {
		var b = old + a + '  ';
	} else {
		var b = a + '  ';
	}
	$("#" + parent_id_entry).val(b); 
});
$(document).on('focusin', '.ros_template_div input[type="text"]', function() {
	old_text = $(this).val();
});
$(document).on('focusout', '.ros_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != '') {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var x = parent_id.length - 1;
		var parent_div = parent_id.slice(0,x);
		var start1 = $("#" + parent_div + "_div").find('span:first').text();
		if (start1 == '') {
			start1 = $("#" + parts[0] + '_' + parts[1] + '_' + parts[2] + '_label').text();
		}
		var start1_n = start1.lastIndexOf(' (');
		if (start1_n != -1) {
			var start1_n1 = start1.substring(0,start1_n);
			var start1_n2 = start1_n1.toLowerCase();
		} else {
			var start1_n1 = start1;
			var start1_n2 = start1;
		}
		var start2 = $("label[for='" + parent_id + "']").text();
		var start3_n = start1.lastIndexOf('degrees');
		if (start3_n != -1) {
			var end_text = ' degrees.';
		} else {
			var end_text = '';
		}
		var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
		if (start4 != '') {
			var start4_n = start4.lastIndexOf('-');
			if (start4_n != -1) {
				var parts2 = start4.split(' - ');
				var mid_text = ', ' + parts2[1].toLowerCase();
			} else {
				var mid_text = ', ' + start4.toLowerCase();
			}
		} else {
			var mid_text = '';
		}
		if (!!start2) {
			var start_text = start2 + ' ' + start1_n2;
		} else {
			var start_text = start1_n1;
		}
		var old = $("#" + parent_id_entry).val();
		var a_pointer = a.length - 1;
		var a_pointer2 = a.lastIndexOf('.');
		if (!!old) {
			if (!!start_text) {
				var c = start_text + mid_text + ': ' + a + end_text;
				if (old_text != '') {
					var c_old = start_text + mid_text + ': ' + old_text + end_text;
				}
			} else {
				if (a_pointer != a_pointer2) {
					var c = a + '.';
				} else {
					var c = a;
				}
			}
			if (old_text != '') {
				var old_text_pointer = old_text.length - 1;
				var old_text_pointer2 = old_text.lastIndexOf('.');
				if (old_text_pointer != old_text_pointer2) {
					var old_text1 = old_text + '.';
				} else {
					var old_text1 = old_text;
				}
				if (!!start_text) {
					var b = old.replace(c_old, c);
				} else {
					var b = old.replace(old_text1, c);
				}
				old_text = '';
			} else {
				var b = old + ' ' + c;
			}
		} else {
			if (!!start_text) {
				var b = start_text + mid_text + ': ' + a + end_text;
			} else {
				if (a_pointer != a_pointer2) {
					var b = a + '.';
				} else {
					var b = a;
				}
			}
		}
		$("#" + parent_id_entry).val(b);
	}
});
$(document).on('click', '.ros_template_div .ros_detail', function() {
	var detail_id = $(this).attr("id") + '_detail';
	if ($(this).prop('checked')) {
		$('#' + detail_id).show('fast');
		$('#' + detail_id).focus();
	} else {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = ' ' + $('#' + detail_id).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$('#' + detail_id).hide('fast');
	}
});
$(document).on("click", '.all_normal', function(){
	var a = $(this).prop('checked');
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	if (parts[1] == 'wcc') {
		if(a){
			$("#ros_wcc_form").find("input.ros_normal:checkbox").each(function(){
				$(this).prop("checked",true);
			});
			$("#ros_wcc_age_form").find("input.ros_normal:checkbox").each(function(){
				$(this).prop("checked",true);
			});
			var newtext = '';
			$('#ros_wcc_form :checked').each(function() {
				newtext += $(this).val() + '  ';
			});
			$('#ros_wcc_age_form :checked').each(function() {
				newtext += $(this).val() + '  ';
			});
			$('#ros_wcc').val(newtext);
		} else {
			$("#ros_wcc").val('');
			$("#ros_wcc_form").find('input.ros_normal:checkbox').each(function(){
				$(this).prop("checked",false);
			});
			$("#ros_wcc_age_form").find('input.ros_normal:checkbox').each(function(){
				$(this).prop("checked",false);
			});
		}
		$('#ros_wcc_form input[type="checkbox"]').button('refresh');
		$('#ros_wcc_age_form input[type="checkbox"]').button('refresh');
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
		if(a){
			$("#" + parent_id_entry + "_form").find("input.ros_normal:checkbox").each(function(){
				$(this).prop("checked",true);
			});
			updateTextArea(parent_id_entry);
		} else {
			$("#" + parent_id_entry).val('');
			$("#" + parent_id_entry + "_form").find('input.ros_normal:checkbox').each(function(){
				$(this).prop("checked",false);
			});
		}
		$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
	}
});

function updateTextArea_pe(parent_id_entry) {
	var newtext = '';
	$('#' + parent_id_entry + '_form :checked').each(function() {
		newtext += $(this).val() + '  ';
	});
	$('#' + parent_id_entry).val(newtext);
}
function pe_normal(parent_id) {
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + parent_id + "_div").find('.pe_other:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$(this).button('refresh');
	});
	$("#" + parent_id + "_div").find('.pe_detail_text').each(function(){
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		if ($(this).val() != '') {
			var text_pointer = $(this).val().length - 1;
			var text_pointer2 = $(this).val().lastIndexOf('.');
			if (text_pointer != text_pointer2) {
				var text1 = $(this).val() + '.';
			} else {
				var text1 = $(this).val();
			}
			var a = ' ' + text1;
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c);
		}
		$(this).val('');
		$(this).hide();
	});
}
function pe_other(parent_id) {
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + parent_id + "_div").find('.pe_normal:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		var a1 = a + '  ';
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
		$(this).button('refresh');
	});
}
$(document).on("click", '.pe_template_div input[type="checkbox"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if ($(this).is(':checked')) {
		if (old != '') {
			var b = old + '  ' + a;
		} else {
			var b = a;
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.pe_normal')) {
			pe_normal(parent_id);
		}
		if ($(this).is('.pe_other')) {
			pe_other(parent_id);
		}
	} else {
		var a1 = '  ' + a;
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c); 
	}
});
$(document).on("change", '.pe_template_div input[type="radio"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if ($(this).is(':checked')) {
		if (old != '') {
			var b = old + '  ' + a;
		} else {
			var b = a;
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.pe_normal')) {
			pe_normal(parent_id);
		}
		if ($(this).is('.pe_other')) {
			pe_other(parent_id);
		}
	} else {
		var a1 = '  ' + a;
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c); 
	}
});
$(document).on("change", '.pe_template_div select', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if (old != '') {
		var b = old + '  ' + a;
	} else {
		var b = a;
	}
	$("#" + parent_id_entry).val(b); 
});
$(document).on("focusin", '.pe_template_div input[type="text"]', function() {
	old_text = $(this).val();
});
$(document).on("focusout", '.pe_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != '') {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var x = parent_id.length - 1;
		var parent_div = parent_id.slice(0,x);
		var start1 = $("#" + parent_div + "_div").find('span:first').text();
		if (start1 == '') {
			start1 = $("#" + parts[0] + '_' + parts[1] + '_' + parts[2] + '_label').text();
		}
		var start1_n = start1.lastIndexOf(' (');
		if (start1_n != -1) {
			var start1_n1 = start1.substring(0,start1_n);
			var start1_n2 = start1_n1.toLowerCase();
		} else {
			var start1_n1 = start1;
			var start1_n2 = start1;
		}
		var start2 = $("label[for='" + parent_id + "']").text();
		var start3_n = start1.lastIndexOf('degrees');
		if (start3_n != -1) {
			var end_text = ' degrees.';
		} else {
			var end_text = '';
		}
		var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
		if (start4 != '') {
			var start4_n = start4.lastIndexOf('-');
			if (start4_n != -1) {
				var parts2 = start4.split(' - ');
				var mid_text = ', ' + parts2[1].toLowerCase();
			} else {
				var mid_text = ', ' + start4.toLowerCase();
			}
		} else {
			var mid_text = '';
		}
		if (!!start2) {
			var start_text = start2 + ' ' + start1_n2;
		} else {
			var start_text = start1_n1;
		}
		var old = $("#" + parent_id_entry).val();
		var a_pointer = a.length - 1;
		var a_pointer2 = a.lastIndexOf('.');
		if (!!old) {
			if (!!start_text) {
				var c = start_text + mid_text + ': ' + a + end_text;
				if (old_text != '') {
					var c_old = start_text + mid_text + ': ' + old_text + end_text;
				}
			} else {
				if (a_pointer != a_pointer2) {
					var c = a + '.';
				} else {
					var c = a;
				}
			}
			if (old_text != '') {
				var old_text_pointer = old_text.length - 1;
				var old_text_pointer2 = old_text.lastIndexOf('.');
				if (old_text_pointer != old_text_pointer2) {
					var old_text1 = old_text + '.';
				} else {
					var old_text1 = old_text;
				}
				if (!!start_text) {
					var b = old.replace(c_old, c);
				} else {
					var b = old.replace(old_text1, c);
				}
				old_text = '';
			} else {
				var b = old + ' ' + c;
			}
		} else {
			if (!!start_text) {
				var b = start_text + mid_text + ': ' + a + end_text;
			} else {
				if (a_pointer != a_pointer2) {
					var b = a + '.';
				} else {
					var b = a;
				}
			}
		}
		$("#" + parent_id_entry).val(b);
	}
});
$(document).on("click", '.pe_template_div .pe_detail', function() {
	var detail_id = $(this).attr("id") + '_detail';
	if ($(this).is(':checked')) {
		$('#' + detail_id).show('fast');
		$('#' + detail_id).focus();
	} else {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		if ($('#' + detail_id).val() != '') {
			var text_pointer = $('#' + detail_id).val().length - 1;
			var text_pointer2 = $('#' + detail_id).val().lastIndexOf('.');
			if (text_pointer != text_pointer2) {
				var text1 = $('#' + detail_id).val() + '.';
			} else {
				var text1 = $('#' + detail_id).val();
			}
			var a = ' ' + text1;
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c);
		}
		$('#' + detail_id).val('');
		$('#' + detail_id).hide('fast');
	}
});
$(document).on("click", '.all_normal_pe', function(){
	var a = $(this).is(':checked');
	var parent_id = $(this).attr("id");
	var n = parent_id.lastIndexOf('_');
	var parent_id_entry = parent_id.substring(0,n);
	if(a){
		$("#" + parent_id_entry + "_form").find("input.pe_normal:checkbox").each(function(){
			$(this).prop("checked",true);
		});
		updateTextArea_pe(parent_id_entry);
	} else {
		$("#" + parent_id_entry).val('');
		$("#" + parent_id_entry + "_form").find('input.pe_normal:checkbox').each(function(){
			$(this).prop("checked",false);
		});
	}
	$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
});
$(document).on("click", '.all_normal1_pe', function(){
	var a = $(this).is(':checked');
	var parent_id = $(this).attr("id");
	var parent_id_entry = parent_id.replace('normal','dialog');
	if(a){
		$("#" + parent_id_entry).find(".all_normal").each(function(){
			$(this).prop("checked",true);
			var parent_id1 = $(this).attr("id");
			var n1 = parent_id1.lastIndexOf('_');
			var parent_id_entry1 = parent_id1.substring(0,n1);
			$("#" + parent_id_entry1 + "_form").find("input.pe_normal:checkbox").each(function(){
				$(this).prop("checked",true);
			});
			updateTextArea_pe(parent_id_entry1);
			$("#" + parent_id_entry1 + '_form input[type="checkbox"]').button('refresh');
		}).button('refresh');
		$("#" + parent_id_entry).find(".all_normal2").each(function(){
			$(this).prop("checked",true);
			var parent_id2 = $(this).attr("id");
			var parent_id_entry2 = parent_id2.replace('_normal1','');
			var old2 = $("#" + parent_id_entry2).val();
			var a2 = $(this).val();
			if (old2 != '') {
				var b2 = old2 + '  ' + a2;
			} else {
				var b2 = a2;
			}
			$("#" + parent_id_entry2).val(b2); 
		}).button('refresh');
	} else {
		$("#" + parent_id_entry).find(".all_normal").each(function(){
			$(this).prop("checked",false);
			var parent_id2 = $(this).attr("id");
			var n2 = parent_id2.lastIndexOf('_');
			var parent_id_entry2 = parent_id2.substring(0,n2);
			$("#" + parent_id_entry2).val('');
			$("#" + parent_id_entry2 + "_form").find('input.pe_normal:checkbox').each(function(){
				$(this).prop("checked",false);
			});
			$("#" + parent_id_entry2 + '_form input[type="checkbox"]').button('refresh');
		}).button('refresh');
		$("#" + parent_id_entry).find(".all_normal2").each(function(){
			$(this).prop("checked",true);
			var parent_id2 = $(this).attr("id");
			var parent_id_entry2 = parent_id2.replace('_normal1','');
			var old2 = $("#" + parent_id_entry2).val();
			var a2 = $(this).val();
			var a3 = '  ' + a2;
			var c2 = old2.replace(a3,'');
			c2 = c2.replace(a2, '');
			$("#" + parent_id_entry2).val(c2); 
		}).button('refresh');
	}
	$("#"+parent_id_entry).find('.pe_entry').each(function(){
		var parent_id1 = $(this).attr("id");
		if (!!$(this).val()) {
			$('#' + parent_id1 + '_h').html(noshdata.item_present);
		} else {
			$('#' + parent_id1 + '_h').html(noshdata.item_empty);
		}
	});
});
$(document).on("click", ".all_normal2_pe", function(){
	var parent_id = $(this).attr("id");
	var parent_id_entry = parent_id.replace('_normal1','');
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	if ($(this).is(':checked')) {
		if (old != '') {
			var b = old + '  ' + a;
		} else {
			var b = a;
		}
		$("#" + parent_id_entry).val(b); 
	} else {
		var a1 = '  ' + a;
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c); 
	}
});
