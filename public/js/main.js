$.event.special.hoverintent = {
	setup: function() {
		$( this ).bind( "mouseover", jQuery.event.special.hoverintent.handler );
	},
	teardown: function() {
		$( this ).unbind( "mouseover", jQuery.event.special.hoverintent.handler );
	},
	handler: function( event ) {
		var currentX, currentY, timeout,
			args = arguments,
			target = $( event.target ),
			previousX = event.pageX,
			previousY = event.pageY;

		function track( event ) {
			currentX = event.pageX;
			currentY = event.pageY;
		}

		function clear() {
			target
				.unbind( "mousemove", track )
				.unbind( "mouseout", clear );
			clearTimeout( timeout );
		}

		function handler() {
			var prop,
				orig = event;

			if ( ( Math.abs( previousX - currentX ) +
					Math.abs( previousY - currentY ) ) < 7 ) {
				clear();

				event = $.Event( "hoverintent" );
				for ( prop in orig ) {
					if ( !( prop in event ) ) {
						event[ prop ] = orig[ prop ];
					}
				}
				// Prevent accessing the original event since the new event
				// is fired asynchronously and the old event is no longer
				// usable (#6028)
				delete event.originalEvent;

				target.trigger( event );
			} else {
				previousX = currentX;
				previousY = currentY;
				timeout = setTimeout( handler, 100 );
			}
		}

		timeout = setTimeout( handler, 100 );
		target.bind({
			mousemove: track,
			mouseout: clear
		});
	}
};

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
function checkNumeric(o,n) {
	if (! $.isNumeric(o.val())) {
		var text = n.replace(":","");
		$.jGrowl(text + " is not a number!");
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
function search_array(a, query_value){
	var query_value1 = query_value.replace('?','\\?');
	var query_value2 = query_value1.replace('(','\\(');
	var query_value3 = query_value2.replace(')','\\)');
	var query_value4 = query_value3.replace('+','\\+');
	var query_value5 = query_value4.replace('/','\\/');
	var found = $.map(a, function (value) {
		var re = RegExp(query_value5, "g");
		if(value.match(re)) {
			return value;
		} else {
			return null;
		}
	});
	return found;
}
function progressbartrack() {
	if (parseInt(noshdata.progress) < 100) {
		if (noshdata.progress === 0) {
			$("#dialog_progressbar").progressbar({value:0});
		}
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/progressbar-track",
			success: function(data){
				$("#dialog_progressbar").progressbar("value", parseInt(data));
				if (parseInt(data) < 100) {
					setTimeout(progressbartrack(),1000);
					noshdata.progress = data;
				} else {
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/delete-progress",
						success: function(data){
							$("#dialog_progressbar").progressbar('destroy');
							$("#dialog_load").dialog('close');
							noshdata.progress = 0;
						}
					});
				}
			}
		});
	}
}
function reload_grid(id) {
	if ($("#"+id)[0].grid) {
		jQuery("#"+id).trigger("reloadGrid");
	}
}
function open_demographics() {
	$.ajax({
		type: "POST",
		url: "ajaxdashboard/demographics",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				if (key == 'DOB') {
					value = editDate1(data.DOB);
				}
				$("#edit_demographics_form :input[name='" + key + "']").val(value);
			});
			if (noshdata.group_id != '100') {
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/check-registration-code",
					success: function(data){
						if (data == 'n') {
							$("#register_menu_demographics").show();
						} else {
							$("#register_menu_demographics").hide();
							$("#menu_registration_code").html(data);
						}
					}
				});
			}
			$("#menu_lastname").focus();
			$("#demographics_list_dialog").dialog('open');
		}
	});
}
function schedule_autosave() {
	var d = $('#providers_calendar').fullCalendar('getDate');
	var n = d.getFullYear();
	n = n + "," + d.getMonth();
	n = n + "," + d.getDate();
	var view = $('#providers_calendar').fullCalendar('getView');
	n = n + "," + view.name;
	$.cookie('nosh-schedule', n, { path: '/' });
}
function addMinutes(date, minutes) {
	return new Date(date.getTime() + minutes*60000);
}
function isOverlapping(start){
	var array = $('#providers_calendar').fullCalendar('clientEvents');
	var end = addMinutes(start, 15);
	for(var i in array){
		if(!(array[i].start >= end || array[i].end <= start)){
			return true;
		}
	}
	return false;
}
function loadappt() {
	$("#patient_appt").show();
	$("#start_form").show();
	$("#reason_form").show();
	$("#other_event").hide();
	$("#event_choose").hide();
	$("#patient_search").focus();
}
function loadevent() {
	$("#patient_appt").hide();
	$("#other_event").show();
	$("#start_form").show();
	$("#reason_form").show();
	$("#event_choose").hide();
	$("#reason").focus();
}
function loadcalendar (y,m,d,view) {
	$('#providers_calendar').fullCalendar('destroy');
	$('#providers_calendar').fullCalendar({
		year: y,
		month: m,
		date: d,
		weekends: noshdata.weekends,
		minTime: noshdata.minTime,
		maxTime: noshdata.maxTime,
		theme: true,
		allDayDefault: false,
		slotMinutes: 15,
		defaultView: view,
		aspectRatio: 0.8,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'agendaWeek,agendaDay'
		},
		editable: true,
		events: function(start, end, callback) {
			var starttime = Math.round(start.getTime() / 1000);
			var endtime = Math.round(end.getTime() / 1000);
			$.ajax({
				type: "POST",
				url: "ajaxschedule/provider-schedule",
				dataType: 'json',
				data: "start=" + starttime + "&end=" + endtime,
				success: function(events) {
					callback(events);
				}
			});
		},
		dayClick: function(date, allDay, jsEvent, view) {
			if (allDay) {
				$.jGrowl('Clicked on the entire day: ' + date);
			} else {
				if (noshdata.group_id == 'schedule') {
					if(confirm('You will need to login to schedule an appointment.  Proceed?')){
						window.location = noshdata.login_url;
					}
				} else {
					if (noshdata.group_id != '1') {
						if (noshdata.group_id != '100') {
							$("#event_dialog").dialog("option", "title", "Schedule an Appointment");
							$("#event_dialog").dialog('open');
							$("#title").focus();
							$("#start_date").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
							$("#start_time").val($.fullCalendar.formatDate(date, 'hh:mmTT'));
							$("#end").val('');
							$("#schedule_visit_type").val('');
							$("#end_row").show();
							$("#title").val('');
							$("#reason").val('');
							$("#until").val('');
							$("#until_row").hide();
							$('#repeat').val('');
							$('#status').val('');
							$("#delete_form").hide();
							$(".nosh_schedule_exist_event").hide();
							$("#patient_appt").hide();
							$("#other_event").hide();
							$("#until_row").hide();
							$("#start_form").hide();
							$("#reason_form").hide();
							$("#event_choose").show();
						} else {
							if (isOverlapping(date)) {
								$.jGrowl('You cannot schedule an appointment in this time slot!');
							} else {
								$("#schedule_visit_type").focus();
								$("#start").text($.fullCalendar.formatDate(date, 'dddd, MM/dd/yyyy, hh:mmTT'));
								$("#start_date").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
								$("#start_time").val($.fullCalendar.formatDate(date, 'hh:mmTT'));
								$("#end").val('');
								$("#schedule_visit_type").val('');
								$("#reason").val('');
								$("#until").val('');
								$("#until_row").hide();
								$('#repeat').val('');
								$("#delete_form").hide("fast");
								$("#patient_appt").show();
								$("#start_form").show();
								$("#reason_form").show();
								$("#other_event").hide();
								$("#event_choose").hide();
								$("#event_dialog").dialog("option", "title", "Schedule an Appointment");
								$("#event_dialog").dialog('open');
							}
						}
					}
				}
			}
		},
		eventClick: function(calEvent, jsEvent, view) {
			if (noshdata.group_id != '1') {
				$("#event_id").val(calEvent.id);
				$("#event_id_span").text(calEvent.id);
				$("#schedule_pid").val(calEvent.pid);
				$("#pid_span").text(calEvent.pid);
				$("#timestamp_span").text(calEvent.timestamp);
				$("#start_date").val($.fullCalendar.formatDate(calEvent.start, 'MM/dd/yyyy'));
				$("#start_time").val($.fullCalendar.formatDate(calEvent.start, 'hh:mmTT'));
				$("#end").val($.fullCalendar.formatDate(calEvent.end, 'hh:mmTT'));
				$("#schedule_title").val(calEvent.title);
				$("#schedule_visit_type").val(calEvent.visit_type);
				if (calEvent.visit_type){
					loadappt();
					$("#patient_search").val(calEvent.title);
					$("#end").val('');
				} else {
					loadevent();
				}
				$("#reason").val(calEvent.reason);
				$("#repeat").val(calEvent.repeat);
				$("#until").val(calEvent.until);
				var repeat_select = $("#repeat").val();
				if (repeat_select !== ''){
					$("#until_row").show();
				} else {
					$("#until_row").hide();
					$("#until").val('');
				}
				$("#status").val(calEvent.status);
				$("#notes").val(calEvent.notes);
				$("#delete_form").show();
				$(".nosh_schedule_exist_event").show();
				$("#event_choose").hide();
				if (calEvent.editable !== false) {
					$("#event_dialog").dialog("option", "title", "Edit an Appointment");
					$("#event_dialog").dialog('open');
					$("#title").focus();
				}
			}
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			if (noshdata.group_id != '1') {
				var start = Math.round(event.start.getTime() / 1000);
				var end = Math.round(event.end.getTime() / 1000);
				if(start){
					$.ajax({
						type: "POST",
						url: "ajaxschedule/drag-event",
						data: "start=" + start + "&end=" + end + "&id=" + event.id,
						success: function(data){
							$.jGrowl("Event updated!");
						}
					});
				} else {
					revertFunc();
				}
				$('.fc-event').each(function(){
					$(this).tooltip('disable');
				});
			} else {
				revertFunc();
				$.jGrowl("You don't have permission to do this!");
			}
		},
		eventResize: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			if (noshdata.group_id != '1') {
				var start = Math.round(event.start.getTime() / 1000);
				var end = Math.round(event.end.getTime() / 1000);
				if(start){
					$.ajax({
						type: "POST",
						url: "ajaxschedule/drag-event",
						data: "start=" + start + "&end=" + end + "&id=" + event.id,
						success: function(data){
							$.jGrowl("Event updated!");
						}
					});
				} else {
					revertFunc();
				}
				$('.fc-event').each(function(){
					$(this).tooltip('disable');
				});
			} else {
				revertFunc();
				$.jGrowl("You don't have permission to do this!");
			}
		},
		eventRender: function(event, element) {
			var display = 'Reason: ' + event.reason + '<br>Status: ' + event.status + '<br>' + event.notes;
			element.tooltip({
				items: element,
				hide: false,
				show: false,
				content: display
			});
			element.tooltip('enable');
		}
	});
	$('#providers_datepicker').datepicker('destroy');
	$('#providers_datepicker').datepicker({
		inline: true,
		onSelect: function(dateText, inst) {
			var d = new Date(dateText);
			$('#providers_calendar').fullCalendar('gotoDate', d);
			var n = d.getFullYear();
			n = n + "," + d.getMonth();
			n = n + "," + d.getDate();
			var view = $('#providers_calendar').fullCalendar('getView');
			n = n + "," + view.name;
			$.cookie('nosh-schedule', n, { path: '/' });
		}
	});
}
function open_schedule() {
	$('#dialog_load').dialog('option', 'title', "Loading schedule...").dialog('open');
	if (noshdata.group_id == '100') {
		$('#schedule_dialog').dialog('option', {
			height: $("#maincontent").height(),
			width: $("#maincontent").width(),
			position: { my: 'left top', at: 'left top', of: '#maincontent' }
		});
	} else {
		$('#schedule_dialog').dialog('option', {
			buttons: [
			{
				text: "Toggle Fullscreen",
				click: function() {
					var w = $(this).dialog('option', 'width');
					if (w == 925) {
						$(this).dialog('option', {
							height: $(window).height(),
							width: $(window).width(),
							position: { my: 'center', at: 'center', of: window }
						});
					} else {
						$(this).dialog('option', {
							height: 640,
							width: 925,
							position: { my: 'center', at: 'center', of: '#maincontent' }
						});
					}
				}
			}]
		});
	}
	$("#provider_list2").removeOption(/./);
	$.ajax({
		url: "ajaxsearch/provider-select",
		dataType: "json",
		type: "POST",
		success: function(data){
			$("#provider_list2").addOption({"":"Select a provider."});
			$("#provider_list2").addOption(data, false);
			if (noshdata.group_id == '2') {
				$.ajax({
					type: "POST",
					url: "ajaxschedule/set-default-provider",
					success: function(data){
						$("#schedule_dialog").dialog('open');
						$('#provider_list2').val(noshdata.user_id);
						if( $.cookie('nosh-schedule') === undefined){
							var d1 = new Date();
							var y = d1.getFullYear();
							var m = d1.getMonth();
							var d = d1.getDate();
							loadcalendar(y,m,d,'agendaWeek');
							$('#dialog_load').dialog('close');
						} else {
							var n =  $.cookie('nosh-schedule').split(",");
							loadcalendar(n[0],n[1],n[2],n[3]);
							$('#dialog_load').dialog('close');
						}
						$("#schedule_visit_type").removeOption(/./);
						$.ajax({
							url: "ajaxsearch/visit-types/" + noshdata.user_id,
							dataType: "json",
							type: "POST",
							async: false,
							success: function(data){
								if (data.response == 'true') {
									$("#schedule_visit_type").addOption(data.message, false);
								} else {
									$("#schedule_visit_type").addOption({"":"No visit types available."},false);
								}
							}
						});
						setInterval(schedule_autosave, 10000);
					}
				});
			} else {
				$('#dialog_load').dialog('close');
				$("#schedule_dialog").dialog('open');
			}
		}
	});
	$("#provider_list2").focus();
}
function chart_notification() {
	if (noshdata.group_id == '2') {
		$.ajax({
			type: "POST",
			url: "ajaxchart/notification",
			dataType: "json",
			success: function(data){
				if (data.appt !== noshdata.notification_appt && data.appt !== '') {
					$.jGrowl(data.appt, {sticky:true, header:data.appt_header});
					noshdata.notification_appt = data.appt;
				}
				if (data.alert !== noshdata.notification_alert && data.alert !== '') {
					$.jGrowl(data.alert, {sticky:true, header:data.alert_header});
					noshdata.notification_alert = data.alert;
				}
			}
		});
	}
}
function openencounter() {
	$("#encounter_body").html('');
	$("#encounter_body").empty();
	if ($(".ros_dialog").hasClass('ui-dialog-content')) {
		$(".ros_dialog").dialog('destroy');
	}
	if ($(".pe_dialog").hasClass('ui-dialog-content')) {
		$(".pe_dialog").dialog('destroy');
	}
	$("#encounter_body").load('ajaxencounter/loadtemplate');
	$('#dialog_load').dialog('option', 'title', "Loading encounter...").dialog('open');
	$("#encounter_link_span").html('<a href="#" id="encounter_panel">[Active Encounter #: ' + noshdata.eid + ']</a>');
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
	var $hpi = $('#hpi_form');
	console.log($hpi.length);
	if($hpi.length) {
		hpi_autosave('hpi');
	}
	var $situation = $('#situation_form');
	if($situation.length) {
		hpi_autosave('situation');
	}
	var $oh = $('#oh_form');
	if($oh.length) {
		oh_autosave();
	}
	var $vitals = $('#vitals_form');
	if($vitals.length) {
		vitals_autosave();
	}
	var $proc = $('#procedure_form');
	if($proc.length) {
		proc_autosave();
	}
	var $assessment = $('#assessment_form');
	if($assessment.length) {
		assessment_autosave();
	}
	var $orders = $('#orders_form');
	if($orders.length) {
		orders_autosave();
	}
	var $medications = $('#mtm_medications_form');
	if($medications.length) {
		medications_autosave();
	}
	$.ajax({
		type: "POST",
		url: "ajaxchart/closeencounter",
		success: function(data){
			noshdata.encounter_active = 'n';
			$("#nosh_encounter_div").hide();
			$("#nosh_chart_div").show();
			$("#encounter_link_span").html('');
		}
	});
}
function signedlabel (cellvalue, options, rowObject){
	if (cellvalue == 'No') {
		return 'Draft';
	}
	if (cellvalue == 'Yes') {
		return 'Signed';
	}
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
	$(".nosh_button_star").button({icons: {primary: "ui-icon-star"}});
	$(".nosh_button_script").button({icons: {primary: "ui-icon-script"}});
	$(".nosh_button_search").button({icons: {primary: "ui-icon-search"}});
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
		$('#swipe').show();
	} else {
		$('.textdump_text').text('Click shift-right arrow key');
		$('#swipe').hide();
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
function remove_text(parent_id_entry, a, label_text, ret) {
	var old = $("#" + parent_id_entry).val();
	var old_arr = old.split('  ');
	var new_arr = [];
	if (label_text !== '') {
		new_arr = search_array(old_arr, label_text);
	}
	if (new_arr.length > 0) {
		var arr_index = old_arr.indexOf(new_arr[0]);
		a = a.replace(label_text, '');
		old_arr[arr_index] = old_arr[arr_index].replace(label_text, '');
		var old_arr1 = old_arr[arr_index].split('; ');
		var new_arr1 = search_array(old_arr1, a);
		if (new_arr1.length > 0) {
			var arr_index1 = old_arr1.indexOf(new_arr1[0]);
			old_arr1.splice(arr_index1,1);
			if (old_arr1.length > 0) {
				old_arr[arr_index] = label_text + old_arr1.join('; ');
			} else {
				old_arr.splice(arr_index,1);
			}
		}
	} else {
		var new_arr2 = search_array(old_arr, a);
		if (new_arr2.length > 0) {
			var arr_index2 = old_arr.indexOf(new_arr2[0]);
			old_arr.splice(arr_index2,1);
		}
	}
	var b = old_arr.join("  ");
	if (ret === true) {
		return b;
	} else {
		$("#" + parent_id_entry).val(b);
	}
}
function repeat_text(parent_id_entry, a, label_text) {
	var ret = false;
	var old = $("#" + parent_id_entry).val();
	var old_arr = old.split('  ');
	var new_arr = [];
	if (label_text !== '') {
		new_arr = search_array(old_arr, label_text);
	}
	if (new_arr.length > 0) {
		var arr_index = old_arr.indexOf(new_arr[0]);
		a = a.replace(label_text, '');
		old_arr[arr_index] = old_arr[arr_index].replace(label_text, '');
		var old_arr1 = old_arr[arr_index].split('; ');
		var new_arr1 = search_array(old_arr1, a);
		if (new_arr1.length > 0) {
			ret = true;
		}
	} else {
		var new_arr2 = search_array(old_arr, a);
		if (new_arr2.length > 0) {
			ret = true;
		}
	}
	return ret;
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
			$('#button_oh_psychosocial_status').html(data.psychosocial_status);
			$('#button_oh_developmental_status').html(data.developmental_status);
			$('#button_oh_medtrials_status').html(data.medtrials_status);
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
			$('#button_ros_psych1_status').html(data.psych1);
			$('#button_ros_psych2_status').html(data.psych2);
			$('#button_ros_psych3_status').html(data.psych3);
			$('#button_ros_psych4_status').html(data.psych4);
			$('#button_ros_psych5_status').html(data.psych5);
			$('#button_ros_psych6_status').html(data.psych6);
			$('#button_ros_psych7_status').html(data.psych7);
			$('#button_ros_psych8_status').html(data.psych8);
			$('#button_ros_psych9_status').html(data.psych9);
			$('#button_ros_psych10_status').html(data.psych10);
			$('#button_ros_psych11_status').html(data.psych11);
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
			$('#button_pe_constitutional_status').html(data.constitutional);
			$('#button_pe_mental_status').html(data.mental);
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
	if (noshdata.pid !== '') {
		$.ajax({
			type: "POST",
			url: "ajaxchart/total-balance",
			success: function(data){
				$('#total_balance').html(data);
			}
		});
	}
}
function hpi_autosave(type) {
	var old0 = $("#"+type+"_old").val();
	var new0 = $("#"+type).val();
	if (old0 != new0) {
		var str = encodeURIComponent(new0);
		$.ajax({
			type: "POST",
			url: "ajaxencounter/hpi-save/" + type,
			data: type+'=' + str,
			success: function(data){
				$.jGrowl(data);
				$("#"+type+"_old").val(new0);
			}
		});
	}
}
function oh_autosave() {
	var bValid = false;
	$("#oh_form").find(".text").each(function() {
		if (bValid === false) {
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
		if (bValid === false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
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
		if (bValid === false) {
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
		if (bValid === false) {
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
		if (bValid === false) {
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
function medications_autosave() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/oh-save1/meds",
		success: function(data){
			$.jGrowl(data);
		}
	});
}
function results_autosave() {
	var bValid = false;
	$("#oh_results_form").find(".text").each(function() {
		if (bValid === false) {
			var input_id = $(this).attr('id');
			var a = $("#" + input_id).val();
			var b = $("#" + input_id + "_old").val();
			if (a != b) {
				bValid = true;
			}
		}
	});
	if (bValid) {
		var oh_str = $("#oh_results_form").serialize();
		if(oh_str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/oh-save1/results",
				data: oh_str,
				success: function(data){
					$.jGrowl(data);
					$("#oh_results_form").find(".text").each(function() {
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
		if (bValid === false) {
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
						value = getCurrentDate();
					}
					$("#edit_"+label+"_form :input[name='" + key + "']").val(value);
				}
			});
			$("#"+label+"_status").html(status);
			if ($("#"+label+"_provider_list").val() === '' && noshdata.group_id === '2') {
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
function hpi_template_renew() {
	$("#hpi_template").removeOption(/./);
	$.ajax({
		type: "POST",
		url: "ajaxencounter/hpi-template-select-list",
		dataType: "json",
		success: function(data){
			$('#hpi_template').addOption({"":"*Select a template"}, false);
			$('#hpi_template').addOption(data.options, false);
			$('#hpi_template').sortOptions();
			$('#hpi_template').val("");
		}
	});
}
function situation_template_renew() {
	$("#situation_template").removeOption(/./);
	$.ajax({
		type: "POST",
		url: "ajaxencounter/situation-template-select-list",
		dataType: "json",
		success: function(data){
			$('#situation_template').addOption({"":"*Select a template"}, false);
			$('#situation_template').addOption(data.options, false);
			$('#situation_template').sortOptions();
			$('#situation_template').val("");
		}
	});
}
function referral_template_renew() {
	$("#messages_ref_template").removeOption(/./);
	$.ajax({
		type: "POST",
		url: "ajaxchart/get-ref-templates-list",
		dataType: "json",
		success: function(data){
			$('#messages_ref_template').addOption({"":"*Select a template"}, false);
			$('#messages_ref_template').addOption(data.options, false);
			$('#messages_ref_template').sortOptions();
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
function ros_template_renew() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/ros-template-select-list",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_template').removeOption(/./);
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
				$("." + key + "_div").css("padding","5px");
				$('.ros_template_div select').addOption({'':'Select option'},true);
				ros_form_load();
				if (key == 'ros_wcc' && noshdata.agealldays <= 2191.44) {
					$.ajax({
						type: "POST",
						url: "ajaxencounter/get-ros-wcc-template",
						dataType: "json",
						success: function(data){
							$('#ros_wcc_age_form').html('');
							$('#ros_wcc_age_form').dform(data);
							ros_form_load();
						}
					});
				}
			});
			$('#dialog_load').dialog('close');
		}
	});
}
function ros_get_data() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-ros",
		dataType: "json",
		success: function(data){
			if (data && data !== '') {
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
function ros_dialog_open() {
	if ($('#ros_skin_form').is(':empty')) {
		$('#dialog_load').dialog('option', 'title', "Loading templates...").dialog('open');
		ros_template_renew();
	}
	ros_get_data();
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
function pe_template_renew() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/pe-template-select-list",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$('#'+key+'_template').removeOption(/./);
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
				$("." + key + "_div").css("padding","5px");
				$('.pe_template_div select').addOption({'':'Select option'},true);
				pe_form_load();
			});
			$('#dialog_load').dialog('close');
		}
	});
}
function pe_get_data() {
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-pe",
		dataType: "json",
		success: function(data){
			if (data && data !== '') {
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
	if (bValid === true) {
		$('#dialog_load').dialog('option', 'title', "Loading templates...").dialog('open');
		pe_template_renew();
	}
	pe_get_data();
}
function pe_dialog_open1() {
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
	pe_get_data();
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
	var edit_date1 = '';
	if (edit_date !== '00/00/0000') {
		edit_date1 = edit_date;
	}
	return edit_date1;
}
function editDate2(string) {
	var result1 = string.split(" ");
	var result = result1[1].split(":");
	var hour1 = result[0];
	var hour2 = parseInt(hour1);
	var hour = '';
	var pm = '';
	var minute = '';
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		pm = 'PM';
		if (hour4.length == 1) {
			hour = "0" + hour4;
		} else {
			hour = hour4;
		}
	} else {
		if (hour2 === 0) {
			hour = '12';
			pm = 'AM';
		}
		if (hour2 === 12) {
			hour = hour2;
			pm = 'PM';
		}
		if (hour2 < 12) {
			pm = 'AM';
			if (hour2.length == 1) {
			hour = "0" + hour2;
			} else {
				hour = hour2;
			}
		}
	}
	var minute1 = result[1];
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		minute = "0" + minute2;
	} else {
		minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}
function getCurrentDate() {
	var d = new Date();
	var day1 = d.getDate();
	var day2 = day1 + '';
	var day = '';
	var month = '';
	if (day2.length == 1) {
		day = "0" + day2;
	} else {
		day = day2;
	}
	var month1 = d.getMonth();
	var month2 = parseInt(month1);
	var month3 = month2 + 1;
	var month4 = month3 + '';
	if (month4.length == 1) {
		month = "0" + month4;
	} else {
		month = month4;
	}
	var date = month + "/" + day + "/" + d.getFullYear();
	return date;
}
function getCurrentTime() {
	var d = new Date();
	var hour1 = d.getHours();
	var hour2 = parseInt(hour1);
	var hour = '';
	var pm = '';
	var minute = '';
	if (hour2 > 12) {
		var hour3 = hour2 - 12;
		var hour4 = hour3 + '';
		pm = 'PM';
		if (hour4.length == 1) {
			hour = "0" + hour4;
		} else {
			hour = hour4;
		}
	} else {
		if (hour2 === 0) {
			hour = '12';
			pm = 'AM';
		}
		if (hour2 == 12) {
			hour = hour2;
			pm = 'PM';
		}
		if (hour2 < 12) {
			pm = 'AM';
			if (hour2.length == 1) {
				hour = "0" + hour2;
			} else {
				hour = hour2;
			}
		}
	}
	var minute1 = d.getMinutes();
	var minute2 = minute1 + '';
	if (minute2.length == 1) {
		minute = "0" + minute2;
	} else {
		minute = minute2;
	}
	var time = hour + ":" + minute + ' ' + pm;
	return time;
}
function typelabel (cellvalue, options, rowObject){
	if (cellvalue == 'standardmedical') {
		return 'Standard Medical Visit V1';
	}
	if (cellvalue == 'standardmedical1') {
		return 'Standard Medical Visit V2';
	}
	if (cellvalue == 'clinicalsupport') {
		return 'Clinical Support Visit';
	}
	if (cellvalue == 'standardpsych') {
		return 'Annual Psychiatric Evaluation';
	}
	if (cellvalue == 'standardpsych1') {
		return 'Psychiatric Encounter';
	}
	if (cellvalue == 'standardmtm') {
		return 'MTM Encounter';
	}
}
function t_messages_tags() {
	var id = $("#t_messages_id").val();
	$.ajax({
		type: "POST",
		url: "ajaxsearch/get-tags/t_messages_id/" + id,
		dataType: "json",
		success: function(data){
			$(".t_messages_tags").tagit("fill",data);
		}
	});
}
$.fn.clearForm = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form') {
			return $(':input',this).clearForm();
		}
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea') {
			this.value = '';
			$('#'+this.id).removeClass("ui-state-error");
		} else if (type == 'checkbox' || type == 'radio') {
			this.checked = false;
			$('#'+this.id).removeClass("ui-state-error");
		} else if (tag == 'select') {
			this.selectedIndex = 0;
			$('#'+this.id).removeClass("ui-state-error");
		}
	});
};
$.fn.clearDiv = function() {
	return this.each(function() {
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'div') {
			return $(':input',this).clearForm();
		}
		if (type == 'text' || type == 'password' || type == 'hidden' || tag == 'textarea') {
			this.value = '';
			$('#'+this.id).removeClass("ui-state-error");
		} else if (type == 'checkbox' || type == 'radio') {
			this.checked = false;
			$('#'+this.id).removeClass("ui-state-error");
		} else if (tag == 'select') {
			this.selectedIndex = 0;
			$('#'+this.id).removeClass("ui-state-error");
		}
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
	//headers: {"cache-control":"no-cache"},
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
	$('.js').show();
	loadbuttons();
	$(".nosh_tooltip").tooltip();
	$(".phonemask").mask("(999) 999-9999");
	if (noshdata.patient_centric == 'y' || noshdata.patient_centric == 'yp') {
		$("#switcher").themeswitcher({
			imgpath: noshdata.images,
			loadtheme: "smoothness"
		});
	} else {
		$("#switcher").themeswitcher({
			imgpath: noshdata.images,
			loadtheme: "redmond"
		});
	}
	$("#dialog_load").dialog({
		height: 100,
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
	$("#textdump_group").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		width: 400,
		draggable: false,
		resizable: false,
		focus: function (event, ui) {
			var id = $("#textdump_group_id").val();
			if (id !== '') {
				$("#"+id).focus();
			}
		},
		close: function (event, ui) {
			$("#textdump_group_target").val('');
			$("#textdump_group_add").val('');
			$("#textdump_group_html").html('');
		}
	});
	$("#restricttextgroup_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 200,
		width: 400,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		close: function (event, ui) {
			$("#restricttextgroup_form").clearForm();
		},
		buttons: {
			'Save': function() {
				var str = $("#restricttextgroup_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxsearch/restricttextgroup-save",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#restricttextgroup_dialog").dialog('close');
					}
				});
			},
			Cancel: function() {
				$("#restricttextgroup_dialog").dialog('close');
			}
		}
	});
	$("#textdump").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		width: 400,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		close: function (event, ui) {
			$("#textdump_target").val('');
			$("#textdump_input").val('');
			$("#textdump_add").val('');
			$("#textdump_group_item").val('');
			$("#textdump_html").html('');
		},
		buttons: [{
			text: 'Save',
			id: 'textdump_dialog_save',
			class: 'nosh_button_save',
			click: function() {
				var id = $("#textdump_target").val();
				var old = $("#"+id).val();
				var delimiter = $("#textdump_delimiter1").val();
				var input = '';
				var text = [];
				$("#textdump_html").find('.textdump_item').each(function() {
					if ($(this).find(':first-child').hasClass("ui-state-error") === true) {
						var a = $(this).text();
						text.push(a);
					}
				});
				if (old !== '') {
					input += old + '\n' + $("#textdump_group_item").val() + ": ";
				} else {
					input += $("#textdump_group_item").val() + ": ";
				}
				input += text.join(delimiter);
				$("#"+id).val(input);
				$("#textdump").dialog('close');
			}
		},{
			text: 'Cancel',
			id: 'textdump_dialog_cancel',
			class: 'nosh_button_cancel',
			click: function() {
				$("#textdump").dialog('close');
			}
		}]
	});
	$("#textdump_specific").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		width: 400,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		close: function (event, ui) {
			$("#textdump_specific_target").val('');
			$("#textdump_specific_start").val('');
			$("#textdump_specific_length").val('');
			$("#textdump_specific_origin").val('');
			$("#textdump_specific_add").val('');
			$("#textdump_specific_html").html('');
			$("#textdump_specific_save").show();
			$("#textdump_specific_cancel").show();
			$("#textdump_specific_done").show();
			$("#textdump_delimiter_div").show();
		},
		buttons: [{
			text: 'Save',
			id: 'textdump_specific_save',
			class: 'nosh_button_save',
			click: function() {
				var origin = $("#textdump_specific_origin").val();
				if (origin != 'configure') {
					var id = $("#textdump_specific_target").val();
					var start = $("#textdump_specific_start").val();
					var length = $("#textdump_specific_length").val();
					var delimiter = $("#textdump_delimiter").val();
					var text = [];
					$("#textdump_specific_html").find('.textdump_item_specific').each(function() {
						if ($(this).find(':first-child').hasClass("ui-state-error") === true) {
							var a = $(this).text();
							text.push(a);
						}
					});
					var input = text.join(delimiter);
					$("#"+id).textrange('set', start, length);
					$("#"+id).textrange('replace', input);
				}
				$("#textdump_specific").dialog('close');
			}
		},{
			text: 'Cancel',
			id: 'textdump_specific_cancel',
			class: 'nosh_button_cancel',
			click: function() {
				$("#textdump_specific").dialog('close');
			}
		},{
			text: 'Done',
			id: 'textdump_specific_done',
			class: 'nosh_button_check',
			click: function() {
				$("#textdump_specific").dialog('close');
			}
		}]
	});
	$("#textdump_group_html").tooltip();
	$("#textdump_html").tooltip();
	$("#textdump_hint").tooltip({
		content: function(callback) {
			var ret = '';
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/listmacros",
				success: function(data){
					callback(data);
				}
			});
		},
		position: { my: "left bottom+15", at: "left top", collision: "flipfit" },
		open: function (event, ui) {
			setTimeout(function() {
				$(ui.tooltip).hide('explode');
			}, 6000);
		},
		track: true
	});
	$("#template_encounter_edit_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 400,
		width: 600,
		closeOnEscape: false,
		dialogClass: "noclose",
		close: function(event, ui) {
			$('#template_encounter_edit_form').clearForm();
			$('#template_encounter_edit_div').empty();
			reload_grid("encounter_templates_list");
			if ($("#template_encounter_dialog").dialog("isOpen")) {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/get-encounter-templates",
					dataType: "json",
					success: function(data){
						$("#template_encounter_choose").removeOption(/./);
						if(data.response === true){
							$("#template_encounter_choose").addOption(data.message, false);
						} else {
							$("#template_encounter_choose").addOption({"":"No encounter templates"}, false);
						}
					}
				});
			}
		},
		buttons: {
			'Add Field': function() {
				var a = $("#template_encounter_edit_div > :last-child").attr("id");
				var count = 0;
				if (a == 'encounter_template_grid_label') {
					count = 0;
				} else {
					var a1 = a.split("_");
					count = parseInt(a1[4]) + 1;
				}
				$("#template_encounter_edit_div").append('<div id="group_encounter_template_div_'+count+'" class="pure-u-1-3"><select name="group[]" id="encounter_template_group_id_'+count+'" class="text encounter_template_group_group" style="width:95%"></select></div><div id="array_encounter_template_div_'+count+'" class="pure-u-1-3"><select name="array[]" id="encounter_template_array_id_'+count+'" class="text" style="width:95%"></select></div><div id="remove_encounter_template_div_'+count+'" class="pure-u-1-3"><button type="button" id="remove_encounter_template_field_'+count+'" class="remove_encounter_template_field nosh_button_cancel">Remove Field</button></div>');
				if (a == 'encounter_template_grid_label') {
					var b = $("#template_encounter_edit_dialog_encounter_template").val();
					$.ajax({
						type: "POST",
						url: "ajaxsearch/get-template-fields/" + b,
						dataType: "json",
						success: function(data){
							$("#encounter_template_group_id_"+count).addOption({'':'Choose Field'}, false);
							$("#encounter_template_group_id_"+count).addOption(data, false);
							$("#encounter_template_group_id_"+count).focus();
							loadbuttons();
						}
					});
				} else {
					$("#encounter_template_group_id_0").copyOptions("#encounter_template_group_id_"+count, "all");
					$("#encounter_template_group_id_"+count).val($("#encounter_template_group_id_"+count+" option:first").val());
					$("#encounter_template_group_id_"+count).focus();
					loadbuttons();
				}
			},
			'Save': function() {
				var bValid = true;
				$("#template_encounter_edit_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id);
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#template_encounter_edit_form").serialize();
					if(str){
						$('#dialog_load').dialog('option', 'title', "Saving template...").dialog('open');
						$.ajax({
							type: "POST",
							url: "ajaxsearch/save-encounter-templates",
							data: str,
							success: function(data){
								$('#dialog_load').dialog('close');
								if (data == 'There is already a template with the same name!') {
									$.jGrowl(data);
									$("#encounter_template_name_text").addClass("ui-state-error");
								} else {
									$.jGrowl(data);
									$('#template_encounter_edit_dialog').dialog('close');
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#template_encounter_edit_dialog').dialog('close');
			}
		}
	});
	$("#timeline_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 650,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
		},
		close: function(event, ui) {
			$("#timeline").html('');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#uma_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#uma_dialog_frame").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$('#uma_iframe').attr('src', '');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#uma_provider_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 200,
		width: 400,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#uma_provider_dialog_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id);
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
					if (input_id == 'uma_provider_practice_url') {
						bValid = bValid && checkRegexp(id1, /\(?(?:(http|https):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/, "eg. https://www.nosh.com/nosh" );
					}
				});
				if (bValid) {
					var str = $("#uma_provider_dialog_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxcommon/practice-api",
							data: str,
							dataType: 'json',
							success: function(data){
								if (data.status == 'y') {
									$.jGrowl(data.message);
									$("#uma_provider_dialog_form").clearForm();
									$("#uma_provider_dialog").dialog('close');
									$("#share_command").css('color','green');
									$("#share_command").attr('title', 'Connected to: ' + data.url);
								} else {
									$.jGrowl(data.message);
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#uma_provider_dialog_form").clearForm();
				$("#uma_provider_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	if (noshdata.patient_centric == 'yp') {
		$.ajax({
			type: "POST",
			url: "ajaxcommon/get-provider-nosh",
			success: function(data){
				if (data !== '') {
					$("#share_command").css('color','green');
					$("#share_command").attr('title', 'Connected to: ' + data);
				} else {
					$("#share_command").attr('title', 'Connect to your mdNOSH instance here');
				}
			}
		});
	}
});
$(document).on("click", "#encounter_panel", function() {
	noshdata.encounter_active = 'y';
	openencounter();
	$("#nosh_chart_div").hide();
	$("#nosh_encounter_div").show();
});
$(document).on("click", ".ui-jqgrid-titlebar", function() {
	$(".ui-jqgrid-titlebar-close", this).click();
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
	var a1 = '';
	var b1 = '';
	var c1 = '';
	var d1 = '';
	var e1 = '';
	var f1 = '';
	var g1 = '';
	var h1 = '';
	var i1 = '';
	var j1 = '';
	var k1 = '';
	var l1 = '';
	var m1 = '';
	if(a){
		a1 = 'Family members in the household: ' + a + '\n';
	}
	if(b){
		b1 = 'Children: ' + b + '\n';
	}
	if(c){
		c1 = 'Pets: ' + c + '\n';
	}
	if(d){
		d1 = 'Marital status: ' + d + '\n';
	}
	if(e){
		e1 = 'Partner name: ' + e + '\n';
	}
	if(f){
		f1 = 'Diet: ' + f + '\n';
	}
	if(g){
		g1 = 'Exercise: ' + g + '\n';
	}
	if(h){
		h1 = 'Sleep: ' + h + '\n';
	}
	if(i){
		i1 = 'Hobbies: ' + i + '\n';
	}
	if(j){
		j1 = 'Child care arrangements: ' + j + '\n';
	}
	if(k){
		k1 = k + '\n';
	}
	if(l){
		l1 = l + '\n';
	}
	if(m){
		m1 = m + '\n';
	}
	var full = d1+e1+a1+b1+c1+f1+g1+h1+i1+j1+k1+l1+m1;
	var full1 = full.trim();
	var n = '';
	if (old1 !== '') {
		n = old1+'\n'+full1+'\n';
	} else {
		n = full1+'\n';
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
	var a1 = '';
	var b = '';
	if(a){
		a1 = a + a0;
	}
	if (old1 !== '') {
		b = old1+'\n'+a1+'\n';
	} else {
		b = a1+'\n';
	}
	var c = b.length;
	$("#oh_etoh").val(b).caret(c);
});
$(document).on('click', '#save_oh_tobacco_form', function(){
	var old = $("#oh_tobacco").val();
	var old1 = old.trim();
	var a = $("input[name='oh_tobacco_select']:checked").val();
	var a0 = $("#oh_tobacco_text").val();
	var a1 = '';
	var b = '';
	if(a){
		a1 = a + a0;
	}
	if (old1 !== '') {
		b = old1+'\n'+a1+'\n';
	} else {
		b = a1+'\n';
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
	var a1 = '';
	if(a){
		if (a == 'No illicit drug use.') {
			a1 = a;
		} else {
			var a0 = $("#oh_drugs_text").val();
			var a2 = $("#oh_drugs_text1").val();
			a1 = a + a0 + '\nFrequency of drug use: ' + a2;
			$('#oh_drugs_input').hide();
			$('#oh_drugs_text').val('');
			$("#oh_drugs_text1").val('');
			$("input[name='oh_drugs_select']").each(function(){
				$(this).prop('checked', false);
			});
			$('#oh_drugs_form input[type="radio"]').button('refresh');
		}
	} else {
		$('#oh_drugs_input').hide();
	}
	var b = '';
	if (old1 !== '') {
		b = old1+'\n'+a1+'\n';
	} else {
		b = a1+'\n';
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
	var a1 = '';
	var b1 = '';
	var c1 = '';
	if(a){
		a1 = a + '\n';
	}
	if(b){
		b1 = 'Employment field: ' + b + '\n';
	}
	if(c){
		c1 = 'Employer: ' + c + '\n';
	}
	var full = a1+b1+c1;
	var full1 = full.trim();
	var d = '';
	if (old1 !== '') {
		d = old1+'\n'+full1+'\n';
	} else {
		d = full1+'\n';
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
	var id = parent_id;
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + id).siblings('input:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		var parent_id_entry = '';
		if (parts[1] == 'wccage') {
			parent_id_entry = 'ros_wcc';
		} else {
			parent_id_entry = parts[0] + '_' + parts[1];
		}
		var a = $(this).val();
		remove_text(parent_id_entry,a,'',false);
		if (parts[1] == 'wccage') {
			$("#ros_wcc_age_form input:checkbox").button('refresh');
		} else {
			$("#" + parent_id_entry + "_form input:checkbox").button('refresh');
		}
	});
	$("#" + parent_id + "_div").find('.ros_detail_text').each(function(){
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = '';
		if (parts[1] == 'wccage') {
			parent_id_entry = 'ros_wcc';
		} else {
			parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = ' ' + $(this).val();
		remove_text(parent_id_entry,a,'',false);
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
		var parent_id_entry = '';
		if (parts[1] == 'wccage') {
			parent_id_entry = 'ros_wcc';
		} else {
			parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		remove_text(parent_id_entry,a,'',false);
		if (parts[1] == 'wccage') {
			$("#ros_wcc_age_form input:checkbox").button('refresh');
		} else {
			$("#" + parent_id_entry + "_form input:checkbox").button('refresh');
		}
	});
}
$(document).on("click", '.ros_template_div input[type="checkbox"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = '';
	if (parts[1] == 'wccage') {
		parent_id_entry = 'ros_wcc';
	} else {
		parent_id_entry = parts[0] + '_' + parts[1];
	}
	var label = parts[0] + '_' + parts[1] + '_' + parts[2] + '_label';
	var label_text = $("#" + label).text() + ': ';
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,label_text);
	var b = '';
	if ($(this).prop('checked') && repeat !== true) {
		if (old !== '') {
			var comma = a.charAt(0);
			var old_arr = old.split('  ');
			var new_arr = search_array(old_arr, label_text);
			if (new_arr.length > 0 && label_text != ': ') {
				var arr_index = old_arr.indexOf(new_arr[0]);
				a = a.replace(label_text, '; ');
				old_arr[arr_index] += a;
			} else {
				old_arr.push(a);
			}
			b = old_arr.join("  ");
		} else {
			b = a;
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.ros_normal')) {
			ros_normal(parent_id);
		} else {
			ros_other(parent_id);
		}
	} else {
		if (label_text == ': ') {
			label_text = '';
		}
		remove_text(parent_id_entry,a,label_text,false);
	}
});
$(document).on("click", '.ros_template_div input[type="radio"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = '';
	if (parts[1] == 'wccage') {
		parent_id_entry = 'ros_wcc';
	} else {
		parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,'');
	console.log(repeat);
	if ($(this).prop('checked') && repeat !== true) {
		var b = a;
		if (old !== '') {
			$(this).siblings('input:radio').each(function() {
				var d = $(this).val();
				var d1 = '  ' + d;
				old = old.replace(d1,'');
				old = old.replace(d, '');
			});
			if (old !== '') {
				b = old + '  ' + a;
			}
		}
		$("#" + parent_id_entry).val(b);
	} else {
		remove_text(parent_id_entry,a,'',false);
	}
});
$(document).on("change", '.ros_template_div select', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = '';
	if (parts[1] == 'wccage') {
		parent_id_entry = 'ros_wcc';
	} else {
		parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var b = a;
	if (old !== '') {
		$(this).siblings('option').each(function() {
			var d = $(this).val();
			var d1 = '  ' + d;
			old = old.replace(d1,'');
			old = old.replace(d, '');
		});
		b = old + '  ' + a;
	}
	$("#" + parent_id_entry).val(b);
});
$(document).on('focus', '.ros_template_div input[type="text"]', function() {
	noshdata.old_text = $(this).val();
});
$(document).on('focusout', '.ros_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != noshdata.old_text) {
		if (a !== '') {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			var parent_id_entry = '';
			if (parts[1] == 'wccage') {
				parent_id_entry = 'ros_wcc';
			} else {
				parent_id_entry = parts[0] + '_' + parts[1];
			}
			var x = parent_id.length - 1;
			var parent_div = parent_id.slice(0,x);
			var start1 = $("#" + parent_div + "_div").find('span:first').text();
			if (start1 === '') {
				start1 = $("#" + parts[0] + '_' + parts[1] + '_' + parts[2] + '_label').text();
			}
			var start1_n = start1.lastIndexOf(' (');
			var start1_n1 = start1;
			var start1_n2 = start1;
			if (start1_n != -1) {
				start1_n1 = start1.substring(0,start1_n);
				start1_n2 = start1_n1.toLowerCase();
			}
			var start2 = $("label[for='" + parent_id + "']").text();
			var start3_n = start1.lastIndexOf('degrees');
			var end_text = '';
			if (start3_n != -1) {
				end_text = ' degrees.';
			}
			var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
			var mid_text = '';
			if (start4 !== '') {
				var start4_n = start4.lastIndexOf('-');
				if (start4_n != -1) {
					var parts2 = start4.split(' - ');
					mid_text = ', ' + parts2[1].toLowerCase();
				} else {
					mid_text = ', ' + start4.toLowerCase();
				}
			}
			var start_text = start1_n1;
			if (!!start2) {
				start_text = start2 + ' ' + start1_n2;
			}
			var old = $("#" + parent_id_entry).val();
			var a_pointer = a.length - 1;
			var a_pointer2 = a.lastIndexOf('.');
			var b = '';
			if (!!old) {
				var c = '';
				var c_old = '';
				if (!!start_text) {
					c = start_text + mid_text + ': ' + a + end_text;
					if (noshdata.old_text !== '') {
						c_old = start_text + mid_text + ': ' + noshdata.old_text + end_text;
					}
				} else {
					if (a_pointer != a_pointer2) {
						c = a + '.';
					} else {
						c = a;
					}
				}
				if (noshdata.old_text !== '') {
					var old_text_pointer = noshdata.old_text.length - 1;
					var old_text_pointer2 = noshdata.old_text.lastIndexOf('.');
					var old_text1 = '';
					if (old_text_pointer != old_text_pointer2) {
						old_text1 = noshdata.old_text + '.';
					} else {
						old_text1 = noshdata.old_text;
					}

					if (!!start_text) {
						b = old.replace(c_old, c);
					} else {
						b = old.replace(old_text1, c);
					}
					noshdata.old_text = '';
				} else {
					b = old + '  ' + c;
				}
			} else {
				if (!!start_text) {
					b = start_text + mid_text + ': ' + a + end_text;
				} else {
					if (a_pointer != a_pointer2) {
						b = a + '.';
					} else {
						b = a;
					}
				}
			}
			$("#" + parent_id_entry).val(b);
		}
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
		var parent_id_entry = '';
		if (parts[1] == 'wccage') {
			parent_id_entry = 'ros_wcc';
		} else {
			parent_id_entry = parts[0] + '_' + parts[1];
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
$(document).on("click", '.all_normal1_ros', function(){
	var a = $(this).prop('checked');
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	$.ajax({
		type: "POST",
		url: "ajaxencounter/all-normal/ros/" + parent_id_entry,
		dataType: 'json',
		success: function(data){
			var message = '';
			$.each(data, function(key, value){
				if(a){
					$("#" + key).val(value);
					message = "All normal values set!";
				} else {
					$("#" + key).val('');
					message = "All normal values cleared!";
				}
			});
			$.jGrowl(message);
		}
	});
});

function updateTextArea_pe(parent_id_entry) {
	var newtext = '';
	$('#' + parent_id_entry + '_form :checked').each(function() {
		newtext += $(this).val() + '  ';
	});
	$('#' + parent_id_entry).val(newtext);
}
function pe_normal(parent_id) {
	var id = parent_id;
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + id).siblings('input:checkbox').each(function() {
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		remove_text(parent_id_entry,a,'',false);
		$(this).button('refresh');
	});
	$("#" + parent_id + "_div").find('.pe_detail_text').each(function(){
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		if ($(this).val() !== '') {
			var text_pointer = $(this).val().length - 1;
			var text_pointer2 = $(this).val().lastIndexOf('.');
			var text1 = '';
			if (text_pointer != text_pointer2) {
				text1 = $(this).val() + '.';
			} else {
				text1 = $(this).val();
			}
			var a = ' ' + text1;
			remove_text(parent_id_entry,a,'',false);
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
		remove_text(parent_id_entry,a,'',false);
		//var a1 = a + '  ';
		//var c = old.replace(a1,'');
		//c = c.replace(a, '');
		//$("#" + parent_id_entry).val(c);
		$(this).button('refresh');
	});
}
$(document).on("click", '.pe_template_div input[type="checkbox"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var label = parts[0] + '_' + parts[1] + '_' + parts[2] + '_label';
	var label_text = $("#" + label).text() + ': ';
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,label_text);
	var b = a;
	if ($(this).is(':checked') && repeat !== true) {
		if (old !== '') {
			var comma = a.charAt(0);
			var old_arr = old.split('  ');
			var new_arr = search_array(old_arr, label_text);
			if (new_arr.length > 0) {
				var arr_index = old_arr.indexOf(new_arr[0]);
				a = a.replace(label_text, '; ');
				old_arr[arr_index] += a;
			} else {
				old_arr.push(a);
			}
			b = old_arr.join("  ");
		}
		$("#" + parent_id_entry).val(b);
		if ($(this).is('.pe_normal')) {
			pe_normal(parent_id);
		} else {
			pe_other(parent_id);
		}
	} else {
		remove_text(parent_id_entry,a,label_text,false);
	}
});
$(document).on("change", '.pe_template_div input[type="radio"]', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,'');
	var b = a;
	if ($(this).is(':checked') && repeat !== true) {
		if (old !== '') {
			$(this).siblings('input:radio').each(function() {
				var d = $(this).val();
				var d1 = '  ' + d;
				old = old.replace(d1,'');
				old = old.replace(d, '');
			});
			if (old !== '') {
				b = old + '  ' + a;
			}
		}
		$("#" + parent_id_entry).val(b);
	} else {
		remove_text(parent_id_entry,a,'',false);
	}
});
$(document).on("change", '.pe_template_div select', function() {
	var parent_id = $(this).attr("id");
	var parts = parent_id.split('_');
	var parent_id_entry = parts[0] + '_' + parts[1];
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var b = a;
	if (old !== '') {
		$(this).siblings('option').each(function() {
			var d = $(this).val();
			var d1 = '  ' + d;
			old = old.replace(d1,'');
			old = old.replace(d, '');
		});
		b = old + '  ' + a;
	}
	$("#" + parent_id_entry).val(b);
});
$(document).on("focus", '.pe_template_div input[type="text"]', function() {
	noshdata.old_text = $(this).val();
});
$(document).on("focusout", '.pe_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != noshdata.old_text) {
		if (a !== '') {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			var parent_id_entry = parts[0] + '_' + parts[1];
			var x = parent_id.length - 1;
			var parent_div = parent_id.slice(0,x);
			var start1 = $("#" + parent_div + "_div").find('span:first').text();
			if (start1 === '') {
				start1 = $("#" + parts[0] + '_' + parts[1] + '_' + parts[2] + '_label').text();
			}
			var start1_n = start1.lastIndexOf(' (');
			var start1_n1 = start1;
			var start1_n2 = start1;
			if (start1_n != -1) {
				start1_n1 = start1.substring(0,start1_n);
				start1_n2 = start1_n1.toLowerCase();
			}
			var start2 = $("label[for='" + parent_id + "']").text();
			var start3_n = start1.lastIndexOf('degrees');
			var end_text = '';
			if (start3_n != -1) {
				end_text = ' degrees.';
			}
			var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
			var mid_text = '';
			if (start4 !== '') {
				var start4_n = start4.lastIndexOf('-');
				if (start4_n != -1) {
					var parts2 = start4.split(' - ');
					mid_text = ', ' + parts2[1].toLowerCase();
				} else {
					mid_text = ', ' + start4.toLowerCase();
				}
			}
			var start_text = start1_n1;
			if (!!start2) {
				start_text = start2 + ' ' + start1_n2;
			}
			var old = $("#" + parent_id_entry).val();
			var a_pointer = a.length - 1;
			var a_pointer2 = a.lastIndexOf('.');
			var b = '';
			var c = '';
			var c_old = '';
			var old_text1 = '';
			if (!!old) {
				if (!!start_text) {
					c = start_text + mid_text + ': ' + a + end_text;
					if (noshdata.old_text !== '') {
						c_old = start_text + mid_text + ': ' + noshdata.old_text + end_text;
					}
				} else {
					if (a_pointer != a_pointer2) {
						c = a + '.';
					} else {
						c = a;
					}
				}
				if (noshdata.old_text !== '') {
					var old_text_pointer = noshdata.old_text.length - 1;
					var old_text_pointer2 = noshdata.old_text.lastIndexOf('.');
					if (old_text_pointer != old_text_pointer2) {
						old_text1 = noshdata.old_text + '.';
					} else {
						old_text1 = noshdata.old_text;
					}
					if (!!start_text) {
						b = old.replace(c_old, c);
					} else {
						b = old.replace(old_text1, c);
					}
					noshdata.old_text = '';
				} else {
					b = old + ' ' + c;
				}
			} else {
				if (!!start_text) {
					b = start_text + mid_text + ': ' + a + end_text;
				} else {
					if (a_pointer != a_pointer2) {
						b = a + '.';
					} else {
						b = a;
					}
				}
			}
			$("#" + parent_id_entry).val(b);
		}
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
		if ($('#' + detail_id).val() !== '') {
			var text_pointer = $('#' + detail_id).val().length - 1;
			var text_pointer2 = $('#' + detail_id).val().lastIndexOf('.');
			var text1 = '';
			if (text_pointer != text_pointer2) {
				text1 = $('#' + detail_id).val() + '.';
			} else {
				text1 = $('#' + detail_id).val();
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
			var b2 = a2;
			if (old2 !== '') {
				b2 = old2 + '  ' + a2;
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
	var b = a;
	if ($(this).is(':checked')) {
		if (old !== '') {
			b = old + '  ' + a;
		}
		$("#" + parent_id_entry).val(b);
	} else {
		var a1 = '  ' + a;
		var c = old.replace(a1,'');
		c = c.replace(a, '');
		$("#" + parent_id_entry).val(c);
	}
});
$(document).on("click", ".all_normal3_pe", function(){
	var a = $(this).is(':checked');
	var parent_id = $(this).attr("id");
	var parent_id_entry = parent_id.replace('_normal1','');
	$.ajax({
		type: "POST",
		url: "ajaxencounter/all-normal/pe/" + parent_id_entry,
		dataType: 'json',
		success: function(data){
			var message = '';
			$.each(data, function(key, value){
				if(a){
					$("#" + key).val(value);
					message = "All normal values set!";
				} else {
					$("#" + key).val('');
					message = "All normal values cleared!";
				}
			});
			$.jGrowl(message);
			$("#"+parent_id_entry+"_dialog").find('.pe_entry').each(function(){
				var parent_id1 = $(this).attr("id");
				if (!!$(this).val()) {
					$('#' + parent_id1 + '_h').html(noshdata.item_present);
				} else {
					$('#' + parent_id1 + '_h').html(noshdata.item_empty);
				}
			});
		}
	});
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
$(document).on('keydown', ':password', function(e){
	var a = $(this).attr('id');
	if(a != 'password') {
		if(e.keyCode==13) {
			e.preventDefault();
		}
	}
});
$(document).on('keydown', '.textdump', function(e){
	if(e.keyCode==39) {
		if(e.shiftKey===true) {
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
					$(".restricttextgroup").button({text: false, icons: {primary: "ui-icon-close"}});
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
	}
});
$(document).on('click', '.textdump_group_item', function(){
	var id = $("#textdump_group_target").val();
	var group = $(this).text();
	$("#textdump_group_item").val(group);
	var id1 = $(this).attr('id');
	$("#textdump_group_id").val(id1);
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
			var dialogheight = $("#" + id).innerHeight();
			$("#textdump").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
			if (dialogheight > 300) {
				$("#textdump").dialog("option", "height", dialogheight);
			}
			$("#textdump").dialog('open');
		}
	});
});
$(document).on('click', '.textdump_item', function() {
	if ($(this).find(':first-child').hasClass("ui-state-error") === false) {
		$(this).find(':first-child').addClass("ui-state-error ui-corner-all");
	} else {
		$(this).find(':first-child').removeClass("ui-state-error ui-corner-all");
	}
});
$(document).on('click', '.textdump_item_specific', function() {
	if ($(this).find(':first-child').hasClass("ui-state-error") === false) {
		$(this).find(':first-child').addClass("ui-state-error ui-corner-all");
	} else {
		$(this).find(':first-child').removeClass("ui-state-error ui-corner-all");
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
$(document).on('click', '.edittexttemplatespecific', function(e) {
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
$(document).on('click', '.restricttextgroup', function() {
	var id = $(this).attr('id');
	var template_id = id.replace('restricttextgroup_','');
	$("#restricttextgroup_template_id").val(template_id);
	$.ajax({
		type: "POST",
		url: "ajaxsearch/restricttextgroup-get/" + template_id,
		dataType: 'json',
		success: function(data){
			$.each(data, function(key, value){
				$("#restricttextgroup_form :input[name='" + key + "']").val(value);
			});
		}
	});
	$("#restricttextgroup_dialog").dialog('open');
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
$(document).on('click', '.deletetexttemplatespecific', function() {
	var id = $(this).attr('id');
	var template_id = id.replace('deletetexttemplatespecific_','');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/deletetextdump/" + template_id,
		success: function(data){
			$("#texttemplatespecificdiv_"+template_id).remove();
		}
	});
});
$(document).on('click', '.normaltextgroup', function() {
	var id = $("#textdump_group_target").val();
	var a = $(this).val();
	var old = $("#"+id).val();
	var delimiter = $("#textdump_delimiter2").val();
	if (a != 'No normal values set.') {
		var a_arr = a.split("\n");
		var d = a_arr.join(delimiter);
		var b = d;
		if ($(this).prop('checked')) {
			if (old !== '') {
				b = old + '\n' + d;
			}
			$("#"+id).val(b);
		} else {
			var a1 = d + '  ';
			var c = old.replace(a1,'');
			c = c.replace(d, '');
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
				$(".restricttextgroup").button({text: false, icons: {primary: "ui-icon-close"}});
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
				$(".restricttextgroup").button({text: false, icons: {primary: "ui-icon-close"}});
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
		if (a !== '') {
			var str = $("#textdump_group_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/add-text-template-group",
					data: str,
					dataType: 'json',
					success: function(data){
						$.jGrowl(data.message);
						var app = '<div id="textgroupdiv_' + data.id + '" style="width:99%" class="pure-g"><div class="pure-u-2-3"><input type="checkbox" id="normaltextgroup_' + data.id + '" class="normaltextgroup" value="No normal values set."><label for="normaltextgroup_' + data.id + '">Normal</label> <b id="edittextgroup_' + data.id + '_b" class="textdump_group_item textdump_group_item_text" data-type="text" data-pk="' + data.id + '" data-name="group" data-url="ajaxsearch/edit-text-template-group" data-title="Group">' + a + '</b></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:200px;"><button type="button" id="edittextgroup_' + data.id + '" class="edittextgroup">Edit</button><button type="button" id="deletetextgroup_' + data.id + '" class="deletetextgroup">Remove</button><button type="button" id="restricttextgroup_' + data.id + '" class="restricttextgroup">Restrictions</button></div></div><hr class="ui-state-default"/></div>';
						$("#textdump_group_html").append(app);
						$(".edittextgroup").button({text: false, icons: {primary: "ui-icon-pencil"}});
						$(".deletetextgroup").button({text: false, icons: {primary: "ui-icon-trash"}});
						$(".normaltextgroup").button({text: false, icons: {primary: "ui-icon-check"}});
						$(".restricttextgroup").button({text: false, icons: {primary: "ui-icon-close"}});
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
		if (a !== '') {
			var str = $("#textdump_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/add-text-template",
					data: str,
					dataType: 'json',
					success: function(data){
						$.jGrowl(data.message);
						var app = '<div id="texttemplatediv_' + data.id + '" style="width:99%" class="pure-g"><div class="textdump_item pure-u-2-3"><span id="edittexttemplate_' + data.id + '_span" class="textdump_item_text ui-state-error ui-corner-all" data-type="text" data-pk="' + data.id + '" data-name="array" data-url="ajaxsearch/edit-text-template" data-title="Item">' + a + '</span></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:400px;"><input type="checkbox" id="normaltexttemplate_' + data.id + '" class="normaltexttemplate" value="normal"><label for="normaltexttemplate_' + data.id + '">Mark as Default Normal</label><button type="button" id="edittexttemplate_' + data.id + '" class="edittexttemplate">Edit</button><button type="button" id="deletetexttemplate_' + data.id + '" class="deletetexttemplate">Remove</button></div></div><hr class="ui-state-default"/></div>';
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
$(document).on('keydown', '#textdump_specific_add', function(e){
	if(e.keyCode==13) {
		e.preventDefault();
		var a = $("#textdump_specific_add").val();
		if (a !== '') {
			var specific_name = $("#textdump_specific_name").val();
			if (specific_name === '') {
				var id = $("#textdump_specific_target").val();
				var start = $("#textdump_specific_start").val();
				var length = $("#textdump_specific_length").val();
				$("#"+id).textrange('set', start, length);
				$("#"+id).textrange('replace', a);
				$("#textdump_specific").dialog('close');
			} else {
				var str = $("#textdump_specific_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxsearch/add-specific-template",
						data: str,
						dataType: 'json',
						success: function(data){
							$.jGrowl(data.message);
							var app = '<div id="texttemplatespecificdiv_' + data.id + '" style="width:99%" class="pure-g"><div class="textdump_item_specific pure-u-2-3"><span id="edittexttemplatespecific_' + data.id + '_span" class="textdump_item_specific_text ui-state-error ui-corner-all" data-type="text" data-pk="' + data.id + '" data-name="array" data-url="ajaxsearch/edit-text-template-specific" data-title="Item">' + a + '</span></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:400px;"><button type="button" id="edittexttemplatespecific_' + data.id + '" class="edittexttemplatespecific">Edit</button><button type="button" id="deletetexttemplatespecific_' + data.id + '" class="deletetexttemplatespecific">Remove</button></div></div><hr class="ui-state-default"/></div>';
							$("#textdump_specific_html").append(app);
							$(".edittexttemplatespecific").button({text: false, icons: {primary: "ui-icon-pencil"}});
							$(".deletetexttemplatespecific").button({text: false, icons: {primary: "ui-icon-trash"}});
							$(".defaulttexttemplatespecific").button();
							$('.textdump_item_specific_text').editable('destroy');
							$('.textdump_item_specific_text').editable({
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
							$("#textdump_specific_add").val('');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		} else {
			$.jGrowl("No text to add!");
		}
	}
});
$(document).on("change", "#hippa_address_id", function () {
	var a = $(this).find("option:selected").first().text();
	if (a != 'Select Provider') {
		$("#hippa_provider1").val(a);
	} else {
		$("#hippa_provider1").val('');
	}
});
$(document).on('click', "#hippa_address_id2", function (){
	var id = $("#hippa_address_id").val();
	if(id){
		$("#print_to_dialog").dialog("option", "title", "Edit Provider");
		$.ajax({
			type: "POST",
			url: "ajaxsearch/orders-provider1",
			data: "address_id=" + id,
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#print_to_form :input[name='" + key + "']").val(value);
				});
			}
		});
	} else {
		$("#print_to_dialog").dialog("option", "title", "Add Provider");
	}
	$("#print_to_origin").val('hippa');
	$("#print_to_dialog").dialog('open');
});
$(document).on("change", "#hippa_request_address_id", function () {
	var a = $(this).find("option:selected").first().text();
	if (a != 'Select Provider') {
		$("#hippa_request_to").val(a);
	} else {
		$("#hippa_request_to").val('');
	}
});
$(document).on('click', "#hippa_request_address_id2", function (){
	var id = $("#hippa_request_address_id").val();
	if(id){
		$("#print_to_dialog").dialog("option", "title", "Edit Provider");
		$.ajax({
			type: "POST",
			url: "ajaxsearch/orders-provider1",
			data: "address_id=" + id,
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#print_to_form :input[name='" + key + "']").val(value);
				});
			}
		});
	} else {
		$("#print_to_dialog").dialog("option", "title", "Add Provider");
	}
	$("#print_to_origin").val('request');
	$("#print_to_dialog").dialog('open');
});
$(document).on('click', '.assessment_clear', function(){
	var id = $(this).attr('id');
	var parts = id.split('_');
	console.log(parts[2]);
	$("#assessment_" + parts[2]).val('');
	$("#assessment_icd" + parts[2]).val('');
	$("#assessment_icd" + parts[2] + "_div").html('');
	$("#assessment_icd" + parts[2] + "_div_button").hide();
});
$(document).on('click', '.hedis_patient', function() {
	var id = $(this).attr('id');
	var pid = id.replace('hedis_', '');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/openchart",
		data: "pid=" + pid,
		success: function(data){
			$.ajax({
				type: "POST",
				url: "ajaxsearch/hedis-set",
				dataType: "json",
				success: function(data){
					window.location = data.url;
				}
			});
		}
	});
});
$(document).on('click', '.claim_associate', function() {
	var id = $(this).attr('id');
	var form_id = id.replace('era_button_', 'era_form_');
	var div_id = id.replace('era_button_', 'era_div_');
	var bValid = true;
	$("#" + form_id).find("[required]").each(function() {
		var input_id = $(this).attr('id');
		var id1 = $("#" + input_id);
		var text = $("label[for='" + input_id + "']").html();
		bValid = bValid && checkEmpty(id1, text);
	});
	if (bValid) {
		var str = $("#" + form_id).serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/associate-claim",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#" + form_id).clearForm();
					$('#' + div_id).remove();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
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
			$(".restricttextgroup").button({text: false, icons: {primary: "ui-icon-close"}});
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
$(document).on('click', 'textarea', function(e) {
	var stopCharacters = [' ', '\n', '\r', '\t', ','];
	var id = $(this).attr('id');
	var val = $(this).val();
	$(this).html(val.replace(/[&\/\-\.]/g, 'a'));
	var text = $(this).html();
	var start = $(this)[0].selectionStart;
	var end = $(this)[0].selectionEnd;
	while (start > 0) {
		if (stopCharacters.indexOf(text[start]) == -1) {
			--start;
		} else {
			break;
		}
	}
	++start;
	while (end < text.length) {
		if (stopCharacters.indexOf(text[end]) == -1) {
			++end;
		} else {
			break;
		}
	}
	if (start == 1) {
		start = 0;
	}
	var startW = text.substr(start,1);
	var endW = text.substr(end-1,1);
	if (startW == '*' && endW == '*') {
		$(this).textrange('set', start, end - start);
		var currentWord = text.substr(start + 1, end - start - 2);
		if (currentWord !== '') {
			if (currentWord == '~') {
				$("#textdump_specific_target").val(id);
				$("#textdump_specific_name").val('');
				$("#textdump_specific_start").val(start);
				$("#textdump_specific_length").val(end - start);
				$("#textdump_delimiter_div").hide();
				$("#textdump_specific_save").hide();
				$("#textdump_specific_done").hide();
				$("#textdump_specific").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
				$("#textdump_specific").dialog('open');
			} else {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/textdump-specific/" + currentWord,
					success: function(data){
						$("#textdump_specific_html").html('');
						$("#textdump_specific_html").append(data);
						$(".edittexttemplatespecific").button({text: false, icons: {primary: "ui-icon-pencil"}});
						$(".deletetexttemplatespecific").button({text: false, icons: {primary: "ui-icon-trash"}});
						$(".defaulttexttemplatespecific").button();
						$('.textdump_item_specific_text').editable('destroy');
						$('.textdump_item_specific_text').editable({
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
						$("#textdump_specific_target").val(id);
						$("#textdump_specific_name").val(currentWord);
						$("#textdump_specific_start").val(start);
						$("#textdump_specific_length").val(end - start);
						$("#textdump_specific_done").hide();
						$("#textdump_specific").dialog("option", "position", { my: 'left top', at: 'right top', of: '#'+id });
						$("#textdump_specific").dialog('open');
					}
				});
			}
		}
	}
});
$(document).on('change', '.encounter_template_group_group', function() {
	var id = $(this).attr('id');
	var a1 = id.split("_");
	var count = a1[4];
	var a = $("#"+id).val();
	$.ajax({
		type: "POST",
		url: "ajaxsearch/get-template-normal-options/" + a,
		dataType: "json",
		success: function(data){
			$("#encounter_template_array_id_"+count).removeOption(/./);
			$("#encounter_template_array_id_"+count).addOption({'':'Choose Group'}, false);
			$("#encounter_template_array_id_"+count).addOption(data, false);
		}
	});
});
$(document).on('click', '#autogenerate_encounter_template', function() {
	$('#dialog_load').dialog('option', 'title', "Autogenerating template...").dialog('open');
	var str = $("#template_encounter_edit_form").serialize();
	if(str){
		$.ajax({
			type: "POST",
			url: "ajaxsearch/autogenerate-encounter-template",
			data: str,
			dataType: "json",
			success: function(data){
				$.jGrowl(data.message);
				if (data.name !== '') {
					$("#template_encounter_edit_dialog").dialog('close');
					$('#dialog_load').dialog('close');
					$('#dialog_load').dialog('option', 'title', "Loading template...").dialog('open');
					$.ajax({
						type: "POST",
						url: "ajaxsearch/get-encounter-templates-details",
						data: 'template_name='+data.name,
						dataType: "json",
						success: function(data){
							$('#dialog_load').dialog('close');
							$("#template_encounter_edit_div").html(data.html);
							loadbuttons();
							$("#template_encounter_edit_dialog").dialog("option", "title", "Edit Encounter Template");
							$("#template_encounter_edit_dialog").dialog('open');
						}
					});
				}
			}
		});
	}
});
$(document).on('click', '.remove_encounter_template_field', function() {
	var id = $(this).attr('id');
	var a1 = id.split("_");
	var count = a1[4];
	$("#group_encounter_template_div_"+count).remove();
	$("#array_encounter_template_div_"+count).remove();
	$("#remove_encounter_template_div_"+count).remove();
});
$(document).on('click', "#timeline_chart", function() {
	$('#dialog_load').dialog('option', 'title', "Loading timeline...").dialog('open');
	$.ajax({
		type: "POST",
		url: "ajaxsearch/timeline",
		dataType: "json",
		success: function(data){
			var json = data.json;
			for (var key in json) {
				if (json.hasOwnProperty(key)) {
					json[key]['startDate'] = new Date(json[key]['startDate'] * 1000);
					if (json[key]['endDate'] !== null) {
						json[key]['endDate'] = new Date(json[key]['endDate'] * 1000);
					}
				}
			}
			$("#timeline").timeCube({
				data: json,
				granularity: data.granular,
				startDate: new Date(data.start * 1000),
				endDate: new Date(data.end * 1000),
				transitionAngle: 60,
				transitionSpacing: 100,
				nextButton: $("#next-link"),
				previousButton: $("#prev-link"),
				showDate: true
			});
			$('#dialog_load').dialog('close');
			$('#timeline_dialog').dialog('open');
		}
	});
});
$(document).on('click', '.timeline_event', function() {
	var type = $(this).attr('type');
	var value = $(this).attr('value');
	var status = $(this).attr('status');
	var acl = false;
	if (noshdata.group_id == '2' || noshdata.group_id == '3') {
		acl = true;
	}
	if (type == 'eid') {
		if (status == 'Yes') {
			if (acl) {
				$("#encounter_view").load('ajaxchart/modal-view/' + value);
			} else {
				$.ajax({
					type: "POST",
					url: "ajaxcommon/opennotes",
					success: function(data){
						if (data == 'y') {
							$("#encounter_view").load('ajaxcommon/modal-view2/' + value);
						} else {
							$.jGrowl('You cannot view the encounter as your provider has not activated OpenNotes.');
						}
					}
				});
			}
			$("#encounter_view_dialog").dialog('open');
		} else {
			$.jGrowl('Encounter is not signed.  You cannot view it at this time.');
		}
	} else if (type == 't_messages_id') {
		if (status == 'Yes') {
			if (acl) {
				$("#message_view").load('ajaxcommon/tmessages-view/' + value);
				$("#t_messages_id").val(value);
				t_messages_tags();
				$("#messages_view_dialog").dialog('open');
			} else {
				$.ajax({
					type: "POST",
					url: "ajaxcommon/opennotes",
					success: function(data){
						if (data == 'y') {
							$("#message_view").load('ajaxcommon/tmessages-view/' + value);
							$("#t_messages_id").val(value);
							$("#messages_view_dialog").dialog('open');
						} else {
							$.jGrowl('You cannot view the message as your provider has not activated OpenNotes.');
						}
					}
				});
			}
		} else {
			$.jGrowl('Message is not signed.  You cannot view it at this time.');
		}
	}
	console.log(value + "," + type);
});
$(document).on('click', '#share_command', function() {
	if (noshdata.group_id == '100') {
		$.ajax({
			type: "POST",
			url: "ajaxcommon/get-patient-resources",
			success: function(data){
				$("#uma_resources").html(data);
				$(".nosh_tooltip").tooltip();
				$('#uma_frame_action').hide();
				$('#uma_dialog').dialog('option', {
					height: $("#maincontent").height(),
					width: $("#maincontent").width(),
					title: 'Registered Resources',
					position: { my: 'left top', at: 'left top', of: '#maincontent' }
				});
				$("#uma_dialog").dialog('open');
			}
		});
	} else {
		$.ajax({
			type: "POST",
			url: "ajaxcommon/get-provider-nosh",
			success: function(data){
				$("#uma_provider_practice_url").val(data);
				$("#uma_provider_dialog").dialog('open');
			}
		});
	}
});
$(document).on('click', '#logout_command', function() {
	window.location = noshdata.logout_url;
});
$(document).on('click', '.view_uma_users', function() {
	var id = $(this).attr('nosh-id');
	$.ajax({
		type: "POST",
		url: "ajaxcommon/get-patient-resource-users/" + id,
		success: function(data){
			$("#uma_users").html(data);
			$(".nosh_tooltip").tooltip();
		}
	});
	$('.uma_table1').css('background-color','white');
	$(this).closest('tr').css('background-color','yellow');
});
$(document).on('click', '.edit_user_access', function() {
	var url = $(this).attr('nosh-url');
	$('#uma_iframe').attr('src', url);
	$('#uma_dialog_frame').dialog('option', {
		height: $("#maincontent").height(),
		width: $("#maincontent").width(),
		position: { my: 'left top', at: 'left top', of: '#maincontent' }
	});
	$("#uma_dialog_frame").dialog('open');
});
$(document).on('click', '.add_uma_policy_user', function() {
	var email = $(this).attr('nosh-email');
	var resource_set_id = $(this).attr('nosh-resource-set-id');
	var policy_id = $(this).attr('nosh-policy-id');
	var name = $(this).attr('nosh-name');
	var scopes = $(this).attr('nosh-scopes');
	var str = "email=" + email + "&resource_set_id=" + resource_set_id + "&policy_id=" + policy_id + "&action=edit&scopes=" + scopes;
	str = encodeURIComponent(str);
	$.ajax({
		type: "POST",
		url: "ajaxcommon/edit-policy",
		data: str,
		dataType: 'json',
		success: function(data){
			$.jGrowl(data.message);
			$("#uma_users").html(data.html);
			$(".nosh_tooltip").tooltip();
		}
	});
});
$(document).on('click', '.remove_uma_policy_user', function() {
	var email = $(this).attr('nosh-email');
	var resource_set_id = $(this).attr('nosh-resource-set-id');
	var policy_id = $(this).attr('nosh-policy-id');
	var name = $(this).attr('nosh-name');
	var scopes = $(this).attr('nosh-scopes');
	var str = "email=" + email + "&resource_set_id=" + resource_set_id + "&policy_id=" + policy_id + "&action=show&scopes=" + scopes;
	str = encodeURIComponent(str);
	$.ajax({
		type: "POST",
		url: "ajaxcommon/edit-policy",
		data: str,
		dataType: 'json',
		success: function(data){
			$.jGrowl(data.message);
			$("#uma_users").html(data.html);
			$(".nosh_tooltip").tooltip();
		}
	});
});
$(document).on('click', '.remove_uma_user', function() {
	var sub = $(this).attr('nosh-sub');
	var resource_set_id = $(this).attr('nosh-resource-set-id');
	var policy_id = $(this).attr('nosh-policy-id');
	var name = $(this).attr('nosh-name');
	if(confirm('You will remove ' + name + ' from having permission to access this resource.  Proceed?')){
		$.ajax({
			type: "POST",
			url: "ajaxcommon/remove-patient-resource-user",
			data: "sub=" + sub + "&resource_set_id=" + resource_set_id + "&policy_id=" + policy_id,
			success: function(data){
				$("#uma_users").html(data);
				$(".nosh_tooltip").tooltip();
			}
		});
	}
});
$(document).on('click', ".dashboard_manage_practice", function() {
	$('#manage_practice_dialog').dialog('option', {
		height: $("#maincontent").height(),
		width: $("#maincontent").width(),
		position: { my: 'left top', at: 'left top', of: '#maincontent' }
	});
	$("#manage_practice_dialog").dialog('open');
});
$(document).on('click', ".send_uma_invite", function() {
	$('#send_uma_invite_dialog').dialog('option', {
		height: $("#maincontent").height(),
		width: $("#maincontent").width(),
		position: { my: 'left top', at: 'left top', of: '#maincontent' }
	});
	$.ajax({
		url: "ajaxcommon/get-patient-resources1",
		type: "POST",
		success: function(data){
			$("#send_uma_invite_div3").html(data);
		}
	});
	$("#send_uma_invite_div1").show();
	$("#send_uma_invite_div2").hide();
	$("#send_uma_invite_dialog").dialog('open');
});
$(document).on('click', ".mdnosh_email_select", function() {
	var id = $(this).attr('id');
	var id1 = id.replace("mdnosh_email_","mdnosh_email_label_span_");
	var a = $("#"+id1).html();
	var b = $(this).val();
	var name = $(this).attr('mdnosh-name');
	$("#mdnosh_email_final").val(b);
	$("#mdnosh_name_final").val(name);
	$("#send_uma_invite_provider").html(a);
	$("#send_uma_invite_div1").hide();
	$("#send_uma_invite_div2").show();
});
