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
	$(".nosh_button_image").button({icons: {primary: "ui-icon-image"}});
	$(".nosh_button_next").button({text: false, icons: {primary: "ui-icon-seek-next"}});
	$(".nosh_button_prev").button({text: false, icons: {primary: "ui-icon-seek-prev"}});
}
function swipe(){
	if(supportsTouch === true){
		$('.textdump').swipe({
			excludedElements:'button, input, select, a, .noSwipe',
			tap: function(){
				$(this).swipe('disable');
				$(this).focus();
				$(this).on('focusout', function() {
					$(this).swipe('enable');
				});
			},
			swipeRight: function(){
				var elem = $(this);
				textdump(elem);
			}
		});
		$('.textdump_text').text('Swipe right');
	} else {
		$('.textdump_text').text('Click right arrow key');
	}
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
	if ($('#ros_skin_form').html() == '') {
		$('#dialog_load').dialog('option', 'title', "Loading templates...").dialog('open');
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
	var bValid = false;
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
			bValid = true;
		}
	});
	if (bValid == true) {
		$('#dialog_load').dialog('option', 'title', "Loading templates...").dialog('open');
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
	$('.textdump').swipe({
		swipeRight: function(){
			var elem = $(this);
			textdump(elem);
		}
	});
});
$(document).on("click", ".ui-jqgrid-titlebar", function() {
	$(".ui-jqgrid-titlebar-close", this).click();
});
$(document).ajaxStop(function() {
  $('#dialog_load').dialog('close');
});
$(document).on('click', '#save_oh_sh_form', function(){
	var old = $("#oh_sh").val();
	var old1 = old.trim();
	var a = $("#sh1").val();
	var b = $("#sh2").val();
	var c = $("#sh3").val();
	var d = $("#oh_sh_marital_status").val();
	var d0 = $("#oh_sh_marital_status_old").val();
	var e = $("#oh_sh_partner_name").val();
	var e0 = $("#oh_sh_partner_name").val();
	var f = $("#sh4").val();
	var g = $("#sh5").val();
	var h = $("#sh6").val();
	var i = $("#sh7").val();
	var j = $("#sh8").val();
	var k = $("input[name='sh9']:checked").val();
	var l = $("input[name='sh10']:checked").val();
	var m = $("input[name='sh11']:checked").val();
	if(a){
		var a1 = 'Family members in the household: ' + a + '\n';
	} else {
		var a1 = '';
	}
	if(b){
		var b1 = 'Children: ' + b + '\n';
	} else {
		var b1 = '';
	}
	if(c){
		var c1 = 'Pets: ' + c + '\n';
	} else {
		var c1 = '';
	}
	if(d){
		var d1 = 'Marital status: ' + d + '\n';
	} else {
		var d1 = '';
	}
	if(e){
		var e1 = 'Partner name: ' + e + '\n';
	} else {
		var e1 = '';
	}
	if(f){
		var f1 = 'Diet: ' + f + '\n';
	} else {
		var f1 = '';
	}
	if(g){
		var g1 = 'Exercise: ' + g + '\n';
	} else {
		var g1 = '';
	}
	if(h){
		var h1 = 'Sleep: ' + h + '\n';
	} else {
		var h1 = '';
	}
	if(i){
		var i1 = 'Hobbies: ' + i + '\n';
	} else {
		var i1 = '';
	}
	if(j){
		var j1 = 'Child care arrangements: ' + j + '\n';
	} else {
		var j1 = '';
	}
	if(k){
		var k1 = k + '\n';
	} else {
		var k1 = '';
	}
	if(l){
		var l1 = l + '\n';
	} else {
		var l1 = '';
	}
	if(m){
		var m1 = m + '\n';
	} else {
		var m1 = '';
	}
	var full = d1+e1+a1+b1+c1+f1+g1+h1+i1+j1+k1+l1+m1;
	var full1 = full.trim();
	if (old1 != '') {
		var n = old1+'\n'+full1+'\n';
	} else {
		var n = full1+'\n';
	}
	var o = n.length;
	$("#oh_sh").val(n).caret(o);
	if(d != d0 || e != e0) {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/sh",
			data: "marital_status=" + d + "&partner_name=" + e,
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
	var sh9_y = $('#sh9_y').attr('checked');
	var sh9_n = $('#sh9_n').attr('checked');
	if(sh9_y){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/sex",
			data: "status=yes",
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
	if(sh9_n){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/sex",
			data: "status=no",
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
});
$(document).on("click", '#save_oh_etoh_form', function(){
	var old = $("#oh_etoh").val();
	var old1 = old.trim();
	var a = $("input[name='oh_etoh_select']:checked").val();
	var a0 = $("#oh_etoh_text").val();
	if(a){
		var a1 = a + a0;
	} else {
		var a1 = '';
	}
	if (old1 != '') {
		var b = old1+'\n'+a1+'\n';
	} else {
		var b = a1+'\n';
	}
	var c = b.length;
	$("#oh_etoh").val(b).caret(c);
});
$(document).on('click', '#save_oh_tobacco_form', function(){
	var old = $("#oh_tobacco").val();
	var old1 = old.trim();
	var a = $("input[name='oh_tobacco_select']:checked").val();
	var a0 = $("#oh_tobacco_text").val();
	if(a){
		var a1 = a + a0;
	} else {
		var a1 = '';
	}
	if (old1 != '') {
		var b = old1+'\n'+a1+'\n';
	} else {
		var b = a1+'\n';
	}
	var c = b.length;
	$("#oh_tobacco").val(b).caret(c);
	var tobacco_y = $('#oh_tobacco_y').prop('checked');
	var tobacco_n = $('#oh_tobacco_n').prop('checked');
	if(tobacco_y){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/tobacco",
			data: "status=yes",
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
	if(tobacco_n){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/tobacco",
			data: "status=no",
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
});
$(document).on('click', '#save_oh_drugs_form', function(){
	var old = $("#oh_drugs").val();
	var old1 = old.trim();
	var a = $("input[name='oh_drugs_select']:checked").val();
	if(a){
		if (a == 'No illicit drug use.') {
			var a1 = a;
		} else {
			var a0 = $("#oh_drugs_text").val();
			var a2 = $("#oh_drugs_text1").val();
			var a1 = a + a0 + '\nFrequency of drug use: ' + a2;
			$('#oh_drugs_input').hide();
			$('#oh_drugs_text').val('');
			$("#oh_drugs_text1").val('');
			$("input[name='oh_drugs_select']").each(function(){
				$(this).prop('checked', false);
			});
			$('#oh_drugs_form input[type="radio"]').button('refresh');
		}
	} else {
		var a1 = '';
		$('#oh_drugs_input').hide();
	}
	if (old1 != '') {
		var b = old1+'\n'+a1+'\n';
	} else {
		var b = a1+'\n';
	}
	var c = b.length;
	$("#oh_drugs").val(b).caret(c);
});
$(document).on('click', '#save_oh_employment_form', function(){
	var old = $("#oh_employment").val();
	var old1 = old.trim();
	var a = $("input[name='oh_employment_select']:checked").val();
	var b = $("#oh_employment_text").val();
	var c = $("#oh_employment_employer").val();
	var c0 = $("#oh_employment_employer_old").val();
	if(a){
		var a1 = a + '\n';
	} else {
		var a1 = '';
	}
	if(b){
		var b1 = 'Employment field: ' + b + '\n';
	} else {
		var b1 = '';
	}
	if(c){
		var c1 = 'Employer: ' + c + '\n';
	} else {
		var c1 = '';
	}
	var full = a1+b1+c1;
	var full1 = full.trim();
	if (old1 != '') {
		var d = old1+'\n'+full1+'\n';
	} else {
		var d = full1+'\n';
	}
	var e = d.length;
	$("#oh_employment").val(d).caret(e);
	if(c != c0){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/edit-demographics/employer",
			data: "employer=" + c,
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
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
		$("#" + parent_id_entry).find(".all_normal_pe").each(function(){
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
		$("#" + parent_id_entry).find(".all_normal2_pe").each(function(){
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
		$("#" + parent_id_entry).find(".all_normal_pe").each(function(){
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
		$("#" + parent_id_entry).find(".all_normal2_pe").each(function(){
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
function loadimagepreview(){
	$('#image_placeholder').html('');
	$('#image_placeholder').empty();
	var image_total = '';
	$.ajax({
		url: "ajaxchart/image-load",
		type: "POST",
		success: function(data){
			$('#image_placeholder').html(data);
			image_total = $("#image_placeholder img").length;
			var $image = $("#image_placeholder img");
			$image.tooltip();
			$image.first().show();
			var i = 1;
			$("#image_status").html('Image ' + i + ' of ' + image_total);
			$('#next_image').click(function () {
				var $next = $image.filter(':visible').hide().next('img');
				i++;
				if($next.length === 0) {
					$next = $image.first();
					i = 1;
				}
				$next.show();
				$("#image_status").html('Image ' + i + ' of ' + image_total);
			});
			$('#prev_image').click(function () {
				var $prev = $image.filter(':visible').hide().prev('img');
				i--;
				if($prev.length === 0) {
					$next = $image.last();
					i = image_total;
				}
				$prev.show();
				$("#image_status").html('Image ' + i + ' of ' + image_total);
			});
		}
	});
}
$(document).on('click', '#edit_image', function () {
	var image = $("#image_placeholder img").filter(':visible').attr('src');
	var image_id1 = $("#image_placeholder img").filter(':visible').attr('id');
	var image_id = image_id1.replace('_image', '');
	$('#wPaint').css({
		width: document.getElementById(image_id1).naturalWidth,
		height: document.getElementById(image_id1).naturalHeight
	}).wPaint('resize');
	$('.wPaint-menu-name-main').css({width:579});
	$('.wPaint-menu-name-text').css({width:182,left:0,top:42});
	$('.wPaint-menu-select').css({"overflow-y":"scroll"});
	//$('.wPaint-menu-name-main').parent().css({
		//width: 579,
		//left: 0,
		//top: -68
	//});
	//$('.wPaint-menu-name-text').parent().css({
		//width: 579,
		//left: 0,
		//top: -26
	//});
	$('#wPaint').wPaint('image', image);
	$.ajax({
		url: "ajaxchart/image-get/" + image_id,
		dataType: "json",
		type: "POST",
		success: function(data){
			$.each(data, function(key, value){
				$("#image_form :input[name='" + key + "']").val(value);
			});
			$("#image_dialog").dialog('open');
		}
	});
});
$(document).on('click', "#del_image", function() {
	var image_id1 = $("#image_placeholder img").filter(':visible').attr('id');
	var image_id = image_id1.replace('_image', '');
	if(confirm('Are you sure you want to delete this image?')){ 
		$.ajax({
			type: "POST",
			url: "ajaxchart/delete-image",
			data: "image_id=" + image_id,
			success: function(data){
				$.jGrowl(data);
				loadimagepreview();
			}
		});
	}
});
$(document).on('keydown', ':text', function(e){
	if(e.keyCode==13) {
		e.preventDefault();
	}
});
$(document).on('keydown', '.textdump', function(e){
	if(e.keyCode==39) {
		e.preventDefault();
		var id = $(this).attr('id');
		$.ajax({
			type: "POST",
			url: "ajaxsearch/textdump-group/" + id,
			success: function(data){
				$("#textdump_group_html").html('');
				$("#textdump_group_html").append(data);
				$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
				$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
				$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
				$('.textdump_group_item_text').editable('destroy');
				$('.textdump_group_item_text').editable({
					toggle:'manual',
					ajaxOptions: {
						headers: {"cache-control":"no-cache"},
						beforeSend: function(request) {
							return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
						},
						error: function(xhr) {
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
						}
					}
				});
				$("#textdump_group_target").val(id);
				$("#textdump_group").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
				$("#textdump_group").dialog('open');
			}
		});
	}
});
$(document).on('click', '.textdump_group_item', function(){
	var id = $("#textdump_group_target").val();
	var group = $(this).text();
	$("#textdump_group_item").val(group);
	$.ajax({
		type: "POST",
		url: "ajaxsearch/textdump/" + id,
		data: 'group='+group,
		success: function(data){
			$("#textdump_html").html('');
			$("#textdump_html").append(data);
			$(".edittexttemplate").button({text: false, icons: {primary: "ui-icon-pencil"}});
			$(".deletetexttemplate").button({text: false, icons: {primary: "ui-icon-trash"}});
			$(".normaltexttemplate").button({text: false, icons: {primary: "ui-icon-check"}});
			$('.textdump_item_text').editable('destroy');
			$('.textdump_item_text').editable({
				toggle:'manual',
				ajaxOptions: {
					headers: {"cache-control":"no-cache"},
					beforeSend: function(request) {
						return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
					},
					error: function(xhr) {
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
					}
				}
			});
			$("#textdump_target").val(id);
			$("#textdump").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
			$("#textdump").dialog('open');
		}
	});
});
$(document).on('click', '.textdump_item', function() {
	if ($(this).find(':first-child').hasClass("ui-state-error") == false) {
		var a = '';
		var id = $("#textdump_target").val();
		var old = $("#"+id).val();
		if ($("#textdump_input").val() == '') {
			if (old != '') {
				a += '\n' + $("#textdump_group_item").val() + ": ";
			} else {
				a += $("#textdump_group_item").val() + ": ";
			}
		}
		a += $(this).text();
		if (old != '') {
			var b = old + '\n' + a;
		} else {
			var b = a;
		}
		$("#"+id).val(b);
		var old1 = $("#textdump_input").val();
		if (old1 != '') {
			var c = old1 + '\n' + a;
		} else {
			var c = a;
		}
		$("#textdump_input").val(c);
		$(this).find(':first-child').addClass("ui-state-error ui-corner-all");
	}
});
$(document).on('click', '.edittextgroup', function(e) {
	var id = $(this).attr('id');
	e.stopPropagation();
	$("#"+id+"_b").editable('show', true);
});
$(document).on('click', '.edittexttemplate', function(e) {
	var id = $(this).attr('id');
	e.stopPropagation();
	$("#"+id+"_span").editable('show', true);
});
$(document).on('click', '.deletetextgroup', function() {
	var id = $(this).attr('id');
	var template_id = id.replace('deletetextgroup_','');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/deletetextdumpgroup/" + template_id,
		success: function(data){
			$("#textgroupdiv_"+template_id).remove();
		}
	});
});
$(document).on('click', '.deletetexttemplate', function() {
	var id = $(this).attr('id');
	var template_id = id.replace('deletetexttemplate_','');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/deletetextdump/" + template_id,
		success: function(data){
			$("#texttemplatediv_"+template_id).remove();
		}
	});
});
$(document).on('click', '.normaltextgroup', function() {
	var id = $("#textdump_group_target").val();
	var a = $(this).val();
	var old = $("#"+id).val();
	if (a != 'No normal values set.') {
		if ($(this).prop('checked')) {
			if (old != '') {
				var b = old + '\n' + a;
			} else {
				var b = a;
			}
			$("#"+id).val(b);
		} else {
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" +id).val(c); 
		}
	} else {
		$.jGrowl(a);
	}
});
$(document).on('click', '.normaltexttemplate', function() {
	var id = $(this).attr('id');
	var template_id = id.replace('normaltexttemplate_','');
	if ($(this).prop('checked')) {
		$.ajax({
			type: "POST",
			url: "ajaxsearch/defaulttextdump/" + template_id,
			success: function(data){
				$.jGrowl('Template marked as normal default!');
				$("#textdump_group_html").html('');
				$("#textdump_group_html").append(data);
				$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
				$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
				$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
				$('.textdump_group_item_text').editable('destroy');
				$('.textdump_group_item_text').editable({
					toggle:'manual',
					ajaxOptions: {
						headers: {"cache-control":"no-cache"},
						beforeSend: function(request) {
							return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
						},
						error: function(xhr) {
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
						}
					}
				});
			}
		});
	} else {
		$.ajax({
			type: "POST",
			url: "ajaxsearch/undefaulttextdump/" + template_id,
			success: function(data){
				$.jGrowl('Template unmarked as normal default!');
				$("#textdump_group_html").html('');
				$("#textdump_group_html").append(data);
				$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
				$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
				$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
				$('.textdump_group_item_text').editable('destroy');
				$('.textdump_group_item_text').editable({
					toggle:'manual',
					ajaxOptions: {
						headers: {"cache-control":"no-cache"},
						beforeSend: function(request) {
							return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
						},
						error: function(xhr) {
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
						}
					}
				});
			}
		});
	}
});
$(document).on('keydown', '#textdump_group_add', function(e){
	if(e.keyCode==13) {
		e.preventDefault();
		var a = $("#textdump_group_add").val();
		if (a != '') {
			var str = $("#textdump_group_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/add-text-template-group",
					data: str,
					dataType: 'json',
					success: function(data){
						$.jGrowl(data.message);
						var app = '<div id="textgroupdiv_' + data.id + '" style="width:99%" class="pure-g"><div class="pure-u-3-4"><input type="checkbox" id="normaltextgroup_' + data.id + '" class="normaltextgroup" value="No normal values set."><label for="normaltextgroup_' + data.id + '">Normal</label> <b id="edittextgroup_' + data.id + '_b" class="textdump_group_item textdump_group_item_text" data-type="text" data-pk="' + data.id + '" data-name="group" data-url="ajaxsearch/edit-text-template-group" data-title="Group">' + a + '</b></div><div class="pure-u-1-4" style="overflow:hidden"><div style="width:200px;"><button type="button" id="edittextgroup_' + data.id + '" class="edittextgroup">Edit</button><button type="button" id="deletetextgroup_' + data.id + '" class="deletetextgroup">Remove</button></div></div><hr class="ui-state-default"/></div>';
						$("#textdump_group_html").append(app);
						$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
						$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
						$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
						$('.textdump_group_item_text').editable('destroy');
						$('.textdump_group_item_text').editable({
							toggle:'manual',
							ajaxOptions: {
								headers: {"cache-control":"no-cache"},
								beforeSend: function(request) {
									return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
								},
								error: function(xhr) {
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
								}
							}
						});
						$("#textdump_group_add").val('');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		} else {
			$.jGrowl("No text to add!");
		}
	}
});
$(document).on('keydown', '#textdump_add', function(e){
	if(e.keyCode==13) {
		e.preventDefault();
		var a = $("#textdump_add").val();
		if (a != '') {
			var str = $("#textdump_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/add-text-template",
					data: str,
					dataType: 'json',
					success: function(data){
						$.jGrowl(data.message);
						var app = '<div id="texttemplatediv_' + data.id + '" style="width:99%" class="pure-g"><div class="textdump_item pure-u-2-3"><span id="edittexttemplate_' + data.id + '_span" class="textdump_item_text ui-state-error" data-type="text" data-pk="' + data.id + '" data-name="array" data-url="ajaxsearch/edit-text-template" data-title="Item">' + a + '</span></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:400px;"><input type="checkbox" id="normaltexttemplate_' + data.id + '" class="normaltexttemplate" value="normal"><label for="normaltexttemplate_' + data.id + '">Mark as Default Normal</label><button type="button" id="edittexttemplate_' + data.id + '" class="edittexttemplate">Edit</button><button type="button" id="deletetexttemplate_' + data.id + '" class="deletetexttemplate">Remove</button></div></div><hr class="ui-state-default"/></div>';
						$("#textdump_html").append(app);
						$(".edittexttemplate").button({text: false, icons: {primary: "ui-icon-pencil"}});
						$(".deletetexttemplate").button({text: false, icons: {primary: "ui-icon-trash"}});
						$(".normaltexttemplate").button({text: false, icons: {primary: "ui-icon-check"}});
						$('.textdump_item_text').editable('destroy');
						$('.textdump_item_text').editable({
							toggle:'manual',
							ajaxOptions: {
								headers: {"cache-control":"no-cache"},
								beforeSend: function(request) {
									return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
								},
								error: function(xhr) {
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
								}
							}
						});
						var a1 = '';
						if ($("#textdump_input").val() == '') {
							a1 += '\n' + $("#textdump_group_item").val() + ": ";
						}
						a1 += a;
						var id = $("#textdump_target").val();
						var old = $("#"+id).val();
						if (old != '') {
							var b = old + '\n' + a1;
						} else {
							var b = a1;
						}
						$("#"+id).val(b);
						var old1 = $("#textdump_input").val();
						if (old1 != '') {
							var c = old1 + '\n' + a1;
						} else {
							var c = a1;
						}
						$("#textdump_input").val(c);
						$("#textdump_add").val('');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		} else {
			$.jGrowl("No text to add!");
		}
	}
});
function textdump(elem) {
	var id = $(elem).attr('id');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/textdump-group/" + id,
		success: function(data){
			$("#textdump_group_html").html('');
			$("#textdump_group_html").append(data);
			$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
			$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
			$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
			$('.textdump_group_item_text').editable('destroy');
			$('.textdump_group_item_text').editable({
				toggle:'manual',
				ajaxOptions: {
					headers: {"cache-control":"no-cache"},
					beforeSend: function(request) {
						return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
					},
					error: function(xhr) {
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
					}
				}
			});
			$("#textdump_group_target").val(id);
			$("#textdump_group").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
			$("#textdump_group").dialog('open');
		}
	});
}
