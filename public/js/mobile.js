toastr.options = {
	"closeButton": true,
	"debug": false,
	"newestOnTop": true,
	"progressBar": true,
	"positionClass": "toast-bottom-full-width",
	"preventDuplicates": false,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
};
function checkEmpty(o,n) {
	if (o.val() === '' || o.val() === null) {
		var text = n.replace(":","");
		toastr.error(text + " Required");
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
		toastr.error(text + " is not a number!");
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
		toastr.error("Incorrect format: " + text);
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
				if (repeat_select != ''){
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
				if (calEvent.editable != false) {
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
function open_schedule_list(dateText, inst, load) {
	var starttime = Math.round(+new Date(dateText)/1000);
	var endtime = starttime+86400;
	var startday = moment(starttime*1000).format('YYYY-MM-DD');
	var startday1 = 'Appointments for ' + moment(starttime*1000).format('MMMM Do YYYY') + ":";
	$ul = $("#events");
	$.mobile.loading("show");
	$("#providers_date").html(startday1);
	var html = '<li><a href="#" id="patient_appt_button" class="nosh_schedule_event" data-nosh-start="' + startday + '">Add Appointment</a></li>';
	html += '<li><a href="#" id="event_appt_button" class="nosh_schedule_event" data-nosh-start="' + startday + '">Add Event</a></li>';
	$.ajax({
		type: "POST",
		url: "ajaxschedule/provider-schedule",
		dataType: 'json',
		data: "start=" + starttime + "&end=" + endtime
	})
	.then(function(response) {
		$.each(response, function ( i, val ) {
			var label = '<h3>' + val.title + '</h3>';
			if (val.reason != val.title) {
				label += '<p>Reason: ' + val.reason + '</p>';
			}
			if (val.visit_type != undefined) {
				label += '<p>Visit Type: ' + val.visit_type + '</p>';
			}
			var date = $.datepicker.formatDate('M dd, yy, ', new Date(val.start));
			var start_time = moment(new Date(val.start)).format('HH:mmA');
			var end_time = moment(new Date(val.end)).format('HH:mmA');
			var start_date = moment(new Date(val.start)).format('MM/DD/YYYY');
			var color1 = 'clr-black';
			if(val.className==" colorred"){
				color1 = 'clr-red';
			}
			if(val.className==" colororange"){
				color1 = "clr-orange";
			}
			if(val.className==" coloryellow"){
				color1 = "clr-yellow";
			}
			if(val.className==" colorgreen"){
				color1 = "clr-green";
			}
			if(val.className==" colorblue"){
				color1 = "clr-blue";
			}
			if(val.className==" colorpurple"){
				color1 = "clr-purple";
			}
			if(val.className==" colorbrown"){
				color1 = "clr-brown"
			}
			label += '<span class="'+ color1 + '">' + date + start_time + '-' + end_time + '</span>';
			html += '<li><a href="#" class="nosh_schedule_event" data-nosh-event-id="' + val.id + '" data-nosh-pid="' + val.pid + '" data-nosh-title="' + val.title + '" data-nosh-start-date="' + start_date + '" data-nosh-start-time="' + start_time + '" data-nosh-end-time="' + end_time + '" data-nosh-visit-type="' + val.visit_type + '" data-nosh-timestamp="' + val.timestamp + '" data-nosh-repeat="' + val.repeat + '" data-nosh-reason="' + val.reason + '" data-nosh-until="' + val.until + '" data-nosh-notes="' + val.notes + '" data-nosh-status="' + val.status + '" data-nosh-editable="' + val.editable + '">' + label + '</a></li>';
		});
		$ul.html(html);
		$ul.listview("refresh");
		$ul.trigger("updatelayout");
		$.mobile.loading("hide");
		if (load !== undefined) {
			$('html, body').animate({
				scrollTop: $("#patient_appt_button").offset().top
			});
		}
	});
}

function open_schedule(startdate) {
	$('#providers_datepicker').datepicker({
		inline: true,
		onSelect: function (dateText, inst) {
			open_schedule_list(dateText, inst, true);
		}
	});
	if (startdate == null) {
		$("#providers_datepicker").datepicker("setDate", new Date());
		var date = moment().valueOf();
	} else {
		var date = moment(startdate).valueOf();
		$("#providers_datepicker").datepicker("setDate", new Date(date));
	}
	open_schedule_list(date);
	$("#provider_list2").focus();
}

function open_messaging(type) {
	$.mobile.loading("show");
	$ul = $("#"+type);
	var command = type.replace('_', '-');
	$.ajax({
		type: "POST",
		url: "ajaxmessaging/" + command,
		data: "sidx=date&sord=desc&rows=1000000&page=1",
		dataType: 'json'
	}).then(function(response) {
		if (type == 'internal_inbox') {
			var col = ['message-id','message-to','read','date','message-from','message-from-label','subject','body','cc','pid','patient_name','bodytext','t-messages-id','documents-id'];
		}
		if (type == 'internal_draft') {
			var col = ['message-id','date','message-to','cc','subject','body','pid','patient_name'];
		}
		if (type == 'internal_outbox') {
			var col = ['message-id','date','message-to','cc','subject','pid','body'];
		}
		var html = '';
		if (response.rows != '') {
			$.each(response.rows, function ( i, item ) {
				var obj = {};
				$.each(item.cell, function ( j, val ) {
					obj[col[j]] = val;
				});
				if (type == 'internal_inbox') {
					var label = '<h3>' + obj['message-from-label'] + '</h3><p>' + obj['subject'] + '</p>';
				} else {
					var label = '<h3>' + obj['message-to'] + '</h3><p>' + obj['subject'] + '</p>';
				}
				var datastring = '';
				$.each(obj, function ( key, value ) {
					datastring += 'data-nosh-' + key + '="' + value + '" ';
				});
				html += '<li><a href="#" class="nosh_messaging_item" ' + datastring + ' data-origin="' + type + '">' + label + '</a></li>';
			});
		}
		$ul.html(html);
		$ul.listview("refresh");
		$ul.trigger("updatelayout");
		$.mobile.loading("hide");
	});
}

function chart_notification() {
	if (noshdata.group_id == '2') {
		$.ajax({
			type: "POST",
			url: "ajaxchart/notification",
			dataType: "json",
			success: function(data){
				if (data.appt != noshdata.notification_appt && data.appt != '') {
					$.jGrowl(data.appt, {sticky:true, header:data.appt_header});
					noshdata.notification_appt = data.appt;
				}
				if (data.alert != noshdata.notification_alert && data.alert != '') {
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
	if (label_text != '') {
		var new_arr = search_array(old_arr, label_text);
	} else {
		var new_arr = [];
	}
	if (new_arr.length > 0) {
		var arr_index = old_arr.indexOf(new_arr[0]);
		a = a.replace(label_text, '');
		old_arr[arr_index] = old_arr[arr_index].replace(label_text, '');
		var old_arr1 = old_arr[arr_index].split('; ')
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
	if (ret == true) {
		return b;
	} else {
		$("#" + parent_id_entry).val(b);
	}
}
function repeat_text(parent_id_entry, a, label_text) {
	var ret = false;
	var old = $("#" + parent_id_entry).val();
	var old_arr = old.split('  ');
	if (label_text != '') {
		var new_arr = search_array(old_arr, label_text);
	} else {
		var new_arr = [];
	}
	if (new_arr.length > 0) {
		var arr_index = old_arr.indexOf(new_arr[0]);
		a = a.replace(label_text, '');
		old_arr[arr_index] = old_arr[arr_index].replace(label_text, '');
		var old_arr1 = old_arr[arr_index].split('; ')
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
	if (noshdata.pid != '') {
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
			if (data && data != '') {
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
			if (data && data != '') {
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
	if (bValid == true) {
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
function refresh_timeline() {
	var $timeline_block = $('.cd-timeline-block');
	//hide timeline blocks which are outside the viewport
	$timeline_block.each(function(){
		if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
			$(this).find('.cd-timeline-img, .cd-timeline-content').hide();
		}
	});
	//on scolling, show/animate timeline blocks when enter the viewport
	$(window).on('scroll', function(){
		$timeline_block.each(function(){
			if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.cd-timeline-img').is(":hidden")) {
				$(this).find('.cd-timeline-img, .cd-timeline-content').show("slide");
			}
		});
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
			$(this).removeClass("ui-state-error");
		} else if (type == 'checkbox' || type == 'radio') {
			this.checked = false;
			$(this).removeClass("ui-state-error");
			$(this).checkboxradio('refresh');
		} else if (tag == 'select') {
			this.selectedIndex = 0;
			$(this).removeClass("ui-state-error");
			$(this).selectmenu('refresh');
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
			$(this).removeClass("ui-state-error");
		} else if (type == 'checkbox' || type == 'radio') {
			this.checked = false;
			$(this).removeClass("ui-state-error");
			$(this).checkboxradio('refresh');
		} else if (tag == 'select') {
			this.selectedIndex = 0;
			$(this).removeClass("ui-state-error");
			$(this).selectmenu('refresh');
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

$(document).idleTimeout({
	inactivity: 3600000,
	noconfirm: 10000,
	alive_url: noshdata.error,
	redirect_url: noshdata.logout_url,
	logout_url: noshdata.logout_url,
	sessionAlive: false
});
$(document).ready(function() {
	if ($("#provider_schedule1").length) {
		open_schedule();
	}
	$('.cd-read-more').css('color', '#000000');
	if ($('#internal_inbox').length) {
		open_messaging('internal_inbox');
	}
	$(".headericon").offset({top: 23});
	$(".headericon1").offset({top: 7});
	if ($('.template_class').length) {
		var width = $('.template_class').width();
		$('.template_class').wrap('<div class="template_class_wrap" style="position:relative;width:100%"></div>');
		$('.template_class_wrap').append('<i class="template_click zmdi zmdi-favorite zmdi-hc-lg" style="position:absolute;right:5px;top:5px;width:30px;color:red;"></i>');
	}
	//refresh_timeline();
	//$('.js').show();
	//loadbuttons();
	//$(".nosh_tooltip").tooltip();
	//$(".phonemask").mask("(999) 999-9999");
	//$("#dialog_load").dialog({
		//height: 100,
		//autoOpen: false,
		//closeOnEscape: false,
		//dialogClass: "noclose",
		//modal: true
	//});
	//var tz = jstz.determine();
	//$.cookie('nosh_tz', tz.name(), { path: '/' });
	//$('.textdump').swipe({
		//swipeRight: function(){
			//var elem = $(this);
			//textdump(elem);
		//}
	//});
	//$("#textdump_group").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 300,
		//width: 400,
		//draggable: false,
		//resizable: false,
		//focus: function (event, ui) {
			//var id = $("#textdump_group_id").val();
			//if (id != '') {
				//$("#"+id).focus();
			//}
		//},
		//close: function (event, ui) {
			//$("#textdump_group_target").val('');
			//$("#textdump_group_add").val('');
			//$("#textdump_group_html").html('');
		//}
	//});
	//$("#restricttextgroup_dialog").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 200,
		//width: 400,
		//draggable: false,
		//resizable: false,
		//closeOnEscape: false,
		//dialogClass: "noclose",
		//close: function (event, ui) {
			//$("#restricttextgroup_form").clearForm();
		//},
		//buttons: {
			//'Save': function() {
				//var str = $("#restricttextgroup_form").serialize();
				//$.ajax({
					//type: "POST",
					//url: "ajaxsearch/restricttextgroup-save",
					//data: str,
					//success: function(data){
						//$.jGrowl(data);
						//$("#restricttextgroup_dialog").dialog('close');
					//}
				//});
			//},
			//Cancel: function() {
				//$("#restricttextgroup_dialog").dialog('close');
			//}
		//}
	//});
	//$("#textdump").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 300,
		//width: 400,
		//draggable: false,
		//resizable: false,
		//closeOnEscape: false,
		//dialogClass: "noclose",
		//close: function (event, ui) {
			//$("#textdump_target").val('');
			//$("#textdump_input").val('');
			//$("#textdump_add").val('');
			//$("#textdump_group_item").val('');
			//$("#textdump_html").html('');
		//},
		//buttons: [{
			//text: 'Save',
			//id: 'textdump_dialog_save',
			//class: 'nosh_button_save',
			//click: function() {
				//var id = $("#textdump_target").val();
				//var old = $("#"+id).val();
				//var delimiter = $("#textdump_delimiter1").val();
				//var input = '';
				//var text = [];
				//$("#textdump_html").find('.textdump_item').each(function() {
					//if ($(this).find(':first-child').hasClass("ui-state-error") == true) {
						//var a = $(this).text();
						//text.push(a);
					//}
				//});
				//if (old != '') {
					//input += old + '\n' + $("#textdump_group_item").val() + ": ";
				//} else {
					//input += $("#textdump_group_item").val() + ": ";
				//}
				//input += text.join(delimiter);
				//$("#"+id).val(input);
				//$("#textdump").dialog('close');
			//}
		//},{
			//text: 'Cancel',
			//id: 'textdump_dialog_cancel',
			//class: 'nosh_button_cancel',
			//click: function() {
				//$("#textdump").dialog('close');
			//}
		//}]
	//});
	//$("#textdump_specific").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 300,
		//width: 400,
		//draggable: false,
		//resizable: false,
		//closeOnEscape: false,
		//dialogClass: "noclose",
		//close: function (event, ui) {
			//$("#textdump_specific_target").val('');
			//$("#textdump_specific_start").val('');
			//$("#textdump_specific_length").val('');
			//$("#textdump_specific_origin").val('');
			//$("#textdump_specific_add").val('');
			//$("#textdump_specific_html").html('');
			//$("#textdump_specific_save").show();
			//$("#textdump_specific_cancel").show();
			//$("#textdump_specific_done").show();
			//$("#textdump_delimiter_div").show();
		//},
		//buttons: [{
			//text: 'Save',
			//id: 'textdump_specific_save',
			//class: 'nosh_button_save',
			//click: function() {
				//var origin = $("#textdump_specific_origin").val();
				//if (origin != 'configure') {
					//var id = $("#textdump_specific_target").val();
					//var start = $("#textdump_specific_start").val();
					//var length = $("#textdump_specific_length").val();
					//var delimiter = $("#textdump_delimiter").val();
					//var text = [];
					//$("#textdump_specific_html").find('.textdump_item_specific').each(function() {
						//if ($(this).find(':first-child').hasClass("ui-state-error") == true) {
							//var a = $(this).text();
							//text.push(a);
						//}
					//});
					//var input = text.join(delimiter);
					//$("#"+id).textrange('set', start, length);
					//$("#"+id).textrange('replace', input);
				//}
				//$("#textdump_specific").dialog('close');
			//}
		//},{
			//text: 'Cancel',
			//id: 'textdump_specific_cancel',
			//class: 'nosh_button_cancel',
			//click: function() {
				//$("#textdump_specific").dialog('close');
			//}
		//},{
			//text: 'Done',
			//id: 'textdump_specific_done',
			//class: 'nosh_button_check',
			//click: function() {
				//$("#textdump_specific").dialog('close');
			//}
		//}]
	//});
	//$("#textdump_group_html").tooltip();
	//$("#textdump_html").tooltip();
	//$("#textdump_hint").tooltip({
		//content: function(callback) {
			//var ret = '';
			//$.ajax({
				//type: "POST",
				//url: "ajaxdashboard/listmacros",
				//success: function(data){
					//callback(data);
				//}
			//});
		//},
		//position: { my: "left bottom+15", at: "left top", collision: "flipfit" },
		//open: function (event, ui) {
			//setTimeout(function() {
				//$(ui.tooltip).hide('explode');
			//}, 6000);
		//},
		//track: true
	//});
	//$("#template_encounter_edit_dialog").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 400,
		//width: 600,
		//closeOnEscape: false,
		//dialogClass: "noclose",
		//close: function(event, ui) {
			//$('#template_encounter_edit_form').clearForm();
			//$('#template_encounter_edit_div').empty();
			//reload_grid("encounter_templates_list");
			//if ($("#template_encounter_dialog").dialog("isOpen")) {
				//$.ajax({
					//type: "POST",
					//url: "ajaxencounter/get-encounter-templates",
					//dataType: "json",
					//success: function(data){
						//$("#template_encounter_choose").removeOption(/./);
						//if(data.response == true){
							//$("#template_encounter_choose").addOption(data.message, false);
						//} else {
							//$("#template_encounter_choose").addOption({"":"No encounter templates"}, false);
						//}
					//}
				//});
			//}
		//},
		//buttons: {
			//'Add Field': function() {
				//var a = $("#template_encounter_edit_div > :last-child").attr("id");
				//if (a == 'encounter_template_grid_label') {
					//var count = 0;
				//} else {
					//var a1 = a.split("_");
					//var count = parseInt(a1[4]) + 1;
				//}
				//$("#template_encounter_edit_div").append('<div id="group_encounter_template_div_'+count+'" class="pure-u-1-3"><select name="group[]" id="encounter_template_group_id_'+count+'" class="text encounter_template_group_group" style="width:95%"></select></div><div id="array_encounter_template_div_'+count+'" class="pure-u-1-3"><select name="array[]" id="encounter_template_array_id_'+count+'" class="text" style="width:95%"></select></div><div id="remove_encounter_template_div_'+count+'" class="pure-u-1-3"><button type="button" id="remove_encounter_template_field_'+count+'" class="remove_encounter_template_field nosh_button_cancel">Remove Field</button></div>');
				//if (a == 'encounter_template_grid_label') {
					//var b = $("#template_encounter_edit_dialog_encounter_template").val();
					//$.ajax({
						//type: "POST",
						//url: "ajaxsearch/get-template-fields/" + b,
						//dataType: "json",
						//success: function(data){
							//$("#encounter_template_group_id_"+count).addOption({'':'Choose Field'}, false);
							//$("#encounter_template_group_id_"+count).addOption(data, false);
							//$("#encounter_template_group_id_"+count).focus();
							//loadbuttons();
						//}
					//});
				//} else {
					//$("#encounter_template_group_id_0").copyOptions("#encounter_template_group_id_"+count, "all");
					//$("#encounter_template_group_id_"+count).val($("#encounter_template_group_id_"+count+" option:first").val())
					//$("#encounter_template_group_id_"+count).focus();
					//loadbuttons();
				//}
			//},
			//'Save': function() {
				//var bValid = true;
				//$("#template_encounter_edit_form").find("[required]").each(function() {
					//var input_id = $(this).attr('id');
					//var id1 = $("#" + input_id);
					//var text = $("label[for='" + input_id + "']").html();
					//bValid = bValid && checkEmpty(id1, text);
				//});
				//if (bValid) {
					//var str = $("#template_encounter_edit_form").serialize();
					//if(str){
						//$('#dialog_load').dialog('option', 'title', "Saving template...").dialog('open');
						//$.ajax({
							//type: "POST",
							//url: "ajaxsearch/save-encounter-templates",
							//data: str,
							//success: function(data){
								//$('#dialog_load').dialog('close');
								//if (data == 'There is already a template with the same name!') {
									//$.jGrowl(data);
									//$("#encounter_template_name_text").addClass("ui-state-error");
								//} else {
									//$.jGrowl(data);
									//$('#template_encounter_edit_dialog').dialog('close');
								//}
							//}
						//});
					//} else {
						//$.jGrowl("Please complete the form");
					//}
				//}
			//},
			//Cancel: function() {
				//$('#template_encounter_edit_dialog').dialog('close');
			//}
		//}
	//});
	//$("#timeline_dialog").dialog({
		//bgiframe: true,
		//autoOpen: false,
		//height: 500,
		//width: 650,
		//draggable: false,
		//resizable: false,
		//open: function(event, ui) {
		//},
		//close: function(event, ui) {
			//$("#timeline").html('');
		//},
		//position: { my: 'center', at: 'center', of: '#maincontent' }
	//});

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
	var id = parent_id;
	var x = parent_id.length - 1;
	parent_id = parent_id.slice(0,x);
	$("#" + id).siblings('input:checkbox').each(function(){
		var parent_id = $(this).attr("id");
		$(this).prop('checked',false);
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
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
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
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
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
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
	if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
	}
	var label = parts[0] + '_' + parts[1] + '_' + parts[2] + '_label';
	var label_text = $("#" + label).text() + ': ';
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,label_text);
	if ($(this).prop('checked') && repeat !== true) {
		if (old != '') {
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
			var b = old_arr.join("  ");
		} else {
			var b = a;
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
	if (parts[1] == 'wccage') {
		var parent_id_entry = 'ros_wcc';
	} else {
		var parent_id_entry = parts[0] + '_' + parts[1];
	}
	var old = $("#" + parent_id_entry).val();
	var a = $(this).val();
	var repeat = repeat_text(parent_id_entry,a,'');
	console.log(repeat);
	if ($(this).prop('checked') && repeat !== true) {
		if (old != '') {
			$(this).siblings('input:radio').each(function() {
				var d = $(this).val();
				var d1 = '  ' + d;
				old = old.replace(d1,'');
				old = old.replace(d, '');
			});
			if (old != '') {
				var b = old + '  ' + a;
			} else {
				var b = a;
			}
		} else {
			var b = a;
		}
		$("#" + parent_id_entry).val(b);
	} else {
		remove_text(parent_id_entry,a,'',false);
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
		$(this).siblings('option').each(function() {
			var d = $(this).val();
			var d1 = '  ' + d;
			old = old.replace(d1,'');
			old = old.replace(d, '');
		});
		var b = old + '  ' + a;
	} else {
		var b = a;
	}
	$("#" + parent_id_entry).val(b);
});
$(document).on('focus', '.ros_template_div input[type="text"]', function() {
	noshdata.old_text = $(this).val();
});
$(document).on('focusout', '.ros_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != noshdata.old_text) {
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
					if (noshdata.old_text != '') {
						var c_old = start_text + mid_text + ': ' + noshdata.old_text + end_text;
					}
				} else {
					if (a_pointer != a_pointer2) {
						var c = a + '.';
					} else {
						var c = a;
					}
				}
				if (noshdata.old_text != '') {
					var old_text_pointer = noshdata.old_text.length - 1;
					var old_text_pointer2 = noshdata.old_text.lastIndexOf('.');
					if (old_text_pointer != old_text_pointer2) {
						var old_text1 = noshdata.old_text + '.';
					} else {
						var old_text1 = noshdata.old_text;
					}
					if (!!start_text) {
						var b = old.replace(c_old, c);
					} else {
						var b = old.replace(old_text1, c);
					}
					noshdata.old_text = '';
				} else {
					var b = old + '  ' + c;
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
		if ($(this).val() != '') {
			var text_pointer = $(this).val().length - 1;
			var text_pointer2 = $(this).val().lastIndexOf('.');
			if (text_pointer != text_pointer2) {
				var text1 = $(this).val() + '.';
			} else {
				var text1 = $(this).val();
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
	if ($(this).is(':checked') && repeat !== true) {
		if (old != '') {
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
			var b = old_arr.join("  ");
		} else {
			var b = a;
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
	if ($(this).is(':checked') && repeat !== true) {
		if (old != '') {
			$(this).siblings('input:radio').each(function() {
				var d = $(this).val();
				var d1 = '  ' + d;
				old = old.replace(d1,'');
				old = old.replace(d, '');
			});
			if (old != '') {
				var b = old + '  ' + a;
			} else {
				var b = a;
			}
		} else {
			var b = a;
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
	if (old != '') {
		$(this).siblings('option').each(function() {
			var d = $(this).val();
			var d1 = '  ' + d;
			old = old.replace(d1,'');
			old = old.replace(d, '');
		});
		var b = old + '  ' + a;
	} else {
		var b = a;
	}
	$("#" + parent_id_entry).val(b);
});
$(document).on("focus", '.pe_template_div input[type="text"]', function() {
	noshdata.old_text = $(this).val();
});
$(document).on("focusout", '.pe_template_div input[type="text"]', function() {
	var a = $(this).val();
	if (a != noshdata.old_text) {
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
					if (noshdata.old_text != '') {
						var c_old = start_text + mid_text + ': ' + noshdata.old_text + end_text;
					}
				} else {
					if (a_pointer != a_pointer2) {
						var c = a + '.';
					} else {
						var c = a;
					}
				}
				if (noshdata.old_text != '') {
					var old_text_pointer = noshdata.old_text.length - 1;
					var old_text_pointer2 = noshdata.old_text.lastIndexOf('.');
					if (old_text_pointer != old_text_pointer2) {
						var old_text1 = noshdata.old_text + '.';
					} else {
						var old_text1 = noshdata.old_text;
					}
					if (!!start_text) {
						var b = old.replace(c_old, c);
					} else {
						var b = old.replace(old_text1, c);
					}
					noshdata.old_text = '';
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
		if(e.shiftKey==true) {
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

$(document).on('click', '.textdump_item', function() {
	if ($(this).find(':first-child').hasClass("ui-state-error") == false) {
		$(this).find(':first-child').addClass("ui-state-error ui-corner-all");
	} else {
		$(this).find(':first-child').removeClass("ui-state-error ui-corner-all");
	}
});
$(document).on('click', '.textdump_item_specific', function() {
	if ($(this).find(':first-child').hasClass("ui-state-error") == false) {
		$(this).find(':first-child').addClass("ui-state-error ui-corner-all");
	} else {
		$(this).find(':first-child').removeClass("ui-state-error ui-corner-all");
	}
});
$(document).on('click', '.edittextgroup', function(e) {
	var id = $(this).attr('id');
	var isEditable= $("#"+id+"_b").is('.editable');
	$("#"+id+"_b").prop('contenteditable',!isEditable).toggleClass('editable');
	if (isEditable) {
		var url = $("#"+id+"_b").attr('data-url');
		var pk = $("#"+id+"_b").attr('data-pk');
		var name = $("#"+id+"_b").attr('data-name');
		var title = $("#"+id+"_b").attr('data-title');
		var type = $("#"+id+"_b").attr('data-type');
		var value = encodeURIComponent($("#"+id+"_b").html());
		$.ajax({
			type: "POST",
			url: url,
			data: 'value=' + value + "&pk=" + pk + "&name=" + name,
			success: function(data){
				toastr.success(data);
			}
		});
		$(this).html('<i class="zmdi zmdi-edit"></i>');
		$(this).siblings('.deletetextgroup').show();
		$(this).siblings('.restricttextgroup').show();
	} else {
		$(this).html('<i class="zmdi zmdi-check"></i>');
		$(this).siblings('.deletetextgroup').hide();
		$(this).siblings('.restricttextgroup').hide();
	}
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
		if ($(this).prop('checked')) {
			if (old != '') {
				var b = old + '\n' + d;
			} else {
				var b = d;
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
		if (a != '') {
			var specific_name = $("#textdump_specific_name").val();
			if (specific_name == '') {
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
	};
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
		if (currentWord != '') {
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
				if (data.name != '') {
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
					if (json[key]['endDate'] != null) {
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

// Mobile
$(document).on('click', '.ui-title', function(e) {
	$("#form_item").val('');
	$("#search_results").html('');
	var url = $(location).attr('href');
	var parts = url.split("/");
	if (parts[4] == 'chart_mobile') {
		$.mobile.loading("show");
		$.ajax({
			type: "POST",
			url: "../ajaxchart/refresh-timeline",
			success: function(data){
				$("#content_inner_timeline").html(data);
				$("#content_inner_main").show();
				$("#content_inner").hide();
				//refresh_timeline();
				$.mobile.loading("hide");
			}
		});
	}
});
$(document).on('click', '.mobile_click_home', function(e) {
	var classes = $(this).attr('class').split(' ');
	for (var i=0; i<classes.length; i++) {
		if (classes[i].indexOf("ui-") == -1) {
			if (classes[i] != 'mobile_click_home') {
				//console.log(classes[i]);
				//var link = classes[i].replace("mobile_","");
				//$.mobile.loading("show");
				//$.ajax({
					//type: "POST",
					//url: "ajaxdashboard/" + link,
					//success: function(data){
						//$("#content_inner").html(data).trigger('create').show();
						//$("#content_inner_main").hide();
						//$.mobile.loading("hide");
					//}
				//});
				window.location = classes[i];
				break;
			}
		}
	}
});
$(document).on('click', '.mobile_click_chart', function(e) {
	var classes = $(this).attr('class').split(' ');
	for (var i=0; i<classes.length; i++) {
		if (classes[i].indexOf("ui-") == -1) {
			if (classes[i] != 'mobile_click_chart') {
				console.log(classes[i]);
				var link = classes[i].replace("mobile_","");
				$.ajax({
					type: "POST",
					url: "../ajaxchart/" + link + "/true",
					success: function(data){
						$("#content_inner").html(data).trigger('create').show();
						$.mobile.loading("hide");
						$("#content_inner_main").hide();
						$("#left_panel").panel('close');
					}
				});
				break;
			}
		}
	}
});
$(document).on('click', '.mobile_link', function(e) {
	$.mobile.loading("show");
	$("#content").hide();
	$("#chart_header").hide();
	var url = $(this).attr('data-nosh-url');
	var origin = $(this).attr('data-nosh-origin');
	$.ajax({
		type: "POST",
		url: url,
		data: 'origin=' + origin,
		dataType: 'json',
		success: function(data){
			$("#navigation_header_back").attr('data-nosh-origin', origin);
			$("#navigation_header_save").attr('data-nosh-form', data.form);
			$("#navigation_header_save").attr('data-nosh-origin', origin);
			if (data.search != '') {
				$(".search_class").hide();
				$("#"+data.search+"_div").show();
				$("#"+data.search+"_div").find('ul').attr('data-nosh-paste-to',data.search_to);
			}
			$("#edit_content_inner").html(data.content).trigger('create');
			$("#navigation_header").show();
			$("#edit_content").show();
			$.mobile.loading("hide");
		}
	});
});
$(document).on('click', '#navigation_header_back', function(e) {
	$.mobile.loading("show");
	var origin = $(this).attr('data-nosh-origin');
	if (origin == 'Chart') {
		$("#navigation_header").hide();
		$("#content_inner").hide();
		$("#chart_header").show();
		$("#content_inner_main").show();
		$.mobile.loading("hide");
		var scroll = parseInt($(this).attr('data-nosh-scroll'));
		$.mobile.silentScroll(scroll-70);
	} else {
		$.ajax({
			type: "POST",
			url: origin,
			success: function(data){
				$("#content_inner").html(data).trigger('create');
				$("#edit_content").hide();
				$("#navigation_header").hide();
				$("#content").show();
				$("#chart_header").show();
				$.mobile.loading("hide");
			}
		});
	}
});
$(document).on('click', '.cancel_edit', function(e) {
	$.mobile.loading("show");
	var origin = $(this).attr('data-nosh-origin');
	$.ajax({
		type: "POST",
		url: origin,
		success: function(data){
			$("#content_inner").html(data).trigger('create');
			$("#edit_content").hide();
			$("#navigation_header").hide();
			$("#content").show();
			$("#chart_header").show();
			$.mobile.loading("hide");
		}
	});
});
$(document).on('click', '.cancel_edit2', function(e) {
	var form = $(this).attr('data-nosh-form');
	$("#"+form).clearForm();
	$("#edit_content").hide();
	$("#content").show();
});
$(document).on('click', '.nosh_schedule_event', function(e) {
	var editable = $(this).attr('data-nosh-editable');
	if (editable != "false") {
		var id = $(this).attr('id');
		if (id == 'patient_appt_button') {
			loadappt();
			var startday = $(this).attr('data-nosh-start');
			$('#start_date').val(startday);
			$("#edit_content").show();
			$("#content").hide();
			$("#title").focus();
			$.mobile.silentScroll(0);
			return false;
		}
		if (id == 'event_appt_button') {
			loadevent();
			var startday = $(this).attr('data-nosh-start');
			$('#start_date').val(startday);
			$("#edit_content").show();
			$("#content").hide();
			$("#title").focus();
			$.mobile.silentScroll(0);
			return false;
		}
		var form = {};
		$.each($(this).get(0).attributes, function(i, attr) {
			if (attr.name.indexOf("data-nosh-") == '0') {
				var field = attr.name.replace('data-nosh-','');
				field = field.replace('-', '_');
				if (field == 'visit_type') {
					form.visit_type = attr.value;
				}
				if (field == 'title') {
					form.title = attr.value;
				}
				if (attr.value != 'undefined') {
					if (field != 'timestamp') {
						var value = attr.value;
						if (field.indexOf('_date') > 0) {
							value = moment(new Date(value)).format('YYYY-MM-DD');
						}
						if (field == 'pid') {
							field = 'schedule_pid';
						}
						if (field == 'title') {
							field = 'schedule_title';
						}
						$('#' + field).val(value);
					}
				}
			}
		});
		var timestamp = $(this).attr('data-nosh-timestamp');
		$("#event_id_span").text(form.event_id);
		$("#pid_span").text(form.pid);
		$("#timestamp_span").text(timestamp);
		if (form.visit_type){
			loadappt();
			$("#patient_search").val(form.title);
			$("#end").val('');
		} else {
			loadevent();
		}
		var repeat_select = $("#repeat").val();
		if (repeat_select != ''){
			$("#until_row").show();
		} else {
			$("#until_row").hide();
			$("#until").val('');
		}
		$("#delete_form").show();
		$("#schedule_form select").selectmenu('refresh');
		$("#edit_content").show();
		$("#content").hide();
		$("#title").focus();
		$.mobile.silentScroll(0);
		return false;
	} else {
		toastr.error('You cannot edit this entry!');
		return false;
	}
});
$(document).on('click', '.nosh_messaging_item', function(e) {
	var form = {};
	var datastring = '';
	var label = $(this).html();
	label = label.replace('<h3>','<h3 class="card-primary-title">');
	label = label.replace('<p>','<h5 class="card-subtitle">');
	label = label.replace('</p>','</h5>');
	var origin = $(this).attr('data-origin');
	var id = $(this).attr('data-nosh-message-id');
	$.each($(this).get(0).attributes, function(i, attr) {
		if (attr.name.indexOf("data-nosh-") == '0') {
			datastring += attr.name + '="' + attr.value + '" ';
			var field = attr.name.replace('data-nosh-','');
			if (field == 'message-from-label') {
				form.displayname = attr.value;
			}
			if (field == 'date') {
				form.date = attr.value;
			}
			if (field == 'subject') {
				form.subject = attr.value;
			}
			if (field == 'body') {
				form.body = attr.value;
			}
			if (field == 'bodytext') {
				form.bodytext = attr.value;
			}
		}
	});
	var text = '<br><strong>From:</strong> ' + form.displayname + '<br><br><strong>Date:</strong> ' + form.date + '<br><br><strong>Subject:</strong> ' + form.subject + '<br><br><strong>Message:</strong> ' + form.bodytext;
	var action = '<div class="card-action">';
		action += '<div class="row between-xs">';
			action += '<div class="col-xs-4">';
				action += '<div class="box">';
					action += '<a href="#" class="ui-btn ui-btn-inline ui-btn-fab back_message" data-origin="' + origin + '" data-origin-id="' + id + '"><i class="zmdi zmdi-arrow-left"></i></a>';
				action += '</div>'
			action += '</div>'
			if (origin == 'internal_inbox') {
				action += '<div class="col-xs-8 align-right">';
					action += '<div class="box">';
						action += '<a href="#" class="ui-btn ui-btn-inline ui-btn-fab reply_message"' + datastring + '><i class="zmdi zmdi-mail-reply"></i></a>';
						action += '<a href="#" class="ui-btn ui-btn-inline ui-btn-fab reply_all_message"' + datastring + '><i class="zmdi zmdi-mail-reply-all"></i></a>';
						action += '<a href="#" class="ui-btn ui-btn-inline ui-btn-fab forward_message"' + datastring + '><i class="zmdi zmdi-forward"></i></a>';
						action += '<a href="#" class="ui-btn ui-btn-inline ui-btn-fab export_message"' + datastring + '><i class="zmdi zmdi-sign-in"></i></a>';
					action += '</div>';
				action += '</div>';
			}
		action += '</div>';
	action += '</div>';
	var html = '<div class="nd2-card">';
		html += '<div class="card-title">' + label + '</div>' + action;
		html += '<div class="card-supporting-text">' + text + '</div>' + action;
	html += '</div>';
	$("#message_view1").html(html);
	//$("#message_view_rawtext").val(rawtext);
	//$("#message_view_message_id").val(id);
	//$("#message_view_from").val(row['message_from']);
	//$("#message_view_to").val(row['message_to']);
	//$("#message_view_cc").val(row['cc']);
	//$("#message_view_subject").val(row['subject']);
	//$("#message_view_body").val(row['body']);
	//$("#message_view_date").val(row['date']);
	//$("#message_view_pid").val(row['pid']);
	//$("#message_view_patient_name").val(row['patient_name']);
	//$("#message_view_t_messages_id").val(row['t_messages_id']);
	//$("#message_view_documents_id").val(row['documents_id']);
	//messages_tags();
	//if (row['pid'] == '' || row['pid'] == "0") {
		//$("#export_message").hide();
	//} else {
		//$("#export_message").show();
	//}
	//$("#internal_messages_view_dialog").dialog('open');
	//setTimeout(function() {
		//var a = $("#internal_messages_view_dialog" ).dialog("isOpen");
		//if (a) {
			//var id = $("#message_view_message_id").val();
			//var documents_id = $("#message_view_documents_id").val();
			//if (documents_id == '') {
				//documents_id = '0';
			//}
			//$.ajax({
				//type: "POST",
				//url: "ajaxmessaging/read-message/" + id + "/" + documents_id,
				//success: function(data){
					//$.jGrowl(data);
					//reload_grid("internal_inbox");
				//}
			//});
		//}
	//}, 3000);
	//form.event_id = $(this).attr('data-nosh-event-id');
	//form.pid = $(this).attr('data-nosh-pid');
	//form.start_date = moment(new Date($(this).attr('data-nosh-start-date'))).format('YYYY-MM-DD');
	//form.start_time = $(this).attr('data-nosh-start-time');
	//form.end = $(this).attr('data-nosh-end-time');
	//form.visit_type = $(this).attr('data-nosh-visit-type');
	//form.title = $(this).attr('data-nosh-title');
	//form.repeat = $(this).attr('data-nosh-repeat');
	//form.reason = $(this).attr('data-nosh-reason');
	//form.until = $(this).attr('data-nosh-until');
	//form.notes = $(this).attr('data-nosh-notes');
	//form.status = $(this).attr('data-nosh-status');
	//$.each(form, function(key, value){
		//if (value != 'undefined') {
			//$('#'+key).val(value);
		//}
	//});
	//var timestamp = $(this).attr('data-nosh-timestamp');
	//$("#event_id_span").text(form.event_id);
	//$("#pid_span").text(form.pid);
	//$("#timestamp_span").text(timestamp);
	//if (form.visit_type){
		//loadappt();
		//$("#patient_search").val(form.title);
		//$("#end").val('');
	//} else {
		//loadevent();
	//}
	//var repeat_select = $("#repeat").val();
	//if (repeat_select != ''){
		//$("#until_row").show();
	//} else {
		//$("#until_row").hide();
		//$("#until").val('');
	//}
	//$("#delete_form").show();
	//$("#schedule_form select").selectmenu('refresh');
	$("#view_content").show();
	$("#content").hide();
	$("#edit_content").hide();
	$('html, body').animate({
		scrollTop: $("#view_content").offset().top
	});
	return false;

});
$(document).on('click', '.mobile_form_action', function(e) {
	var form_id = $(this).attr('data-nosh-form');
	var table = $(this).attr('data-nosh-table');
	var row_id = $(this).attr('data-nosh-id');
	var action = $(this).attr('data-nosh-action');
	var refresh_url = $(this).attr('data-nosh-origin');
	var row_index = $(this).attr('data-nosh-index');
	var bValid = true;
	$("#"+form_id).find("[required]").each(function() {
		var input_id = $(this).attr('id');
		var id1 = $("#" + input_id);
		var text = $("label[for='" + input_id + "']").html();
		bValid = bValid && checkEmpty(id1, text);
	});
	if (bValid) {
		var str = $("#"+form_id).serialize();
		$.ajax({
			type: "POST",
			url: "../ajaxcommon/mobile-form-action/" + table + '/' + action + '/' + row_id + '/' + row_index,
			data: str,
			dataType: 'json',
			success: function(data){
				if (data.response == 'OK') {
					$('#'+form_id).clearForm();
					$.mobile.loading("show");
					toastr.success(data.message);
					$.ajax({
						type: "POST",
						url: refresh_url,
						success: function(data1){
							$("#content_inner").html(data1).trigger('create');
							$("#edit_content").hide();
							$("#navigation_header").hide();
							$("#content").show();
							$("#chart_header").show();
							$.mobile.loading("hide");
						}
					});
				} else {
					// error handling
				}
			}
		});
	}
});
$(document).on('click', '.mobile_form_action2', function(e) {
	var form_id = $(this).attr('data-nosh-form');
	var action = $(this).attr('data-nosh-action');
	var refresh_url = $(this).attr('data-nosh-origin');
	if (refresh_url == 'mobile_schedule') {
		var start_date = $("#start_date").val();
		var end = $("#end").val();
		var visit_type = $("#visit_type").val();
		var pid = $("#pid").val();
		if (pid == '') {
			var reason = $("#reason").val();
			$("#title").val(reason);
		}
		if ($("#repeat").val() != '' && $("#event_id").val() != '' && $("#event_id").val().indexOf("R") === -1) {
			var event_id = $("#event_id").val();
			$("#event_id").val("N" + event_id);
		}
		if ($("#repeat").val() == '' && $("#event_id").val() != '' && $("#event_id").val().indexOf("R") !== -1) {
			var event_id1 = $("#event_id").val();
			$("#event_id").val("N" + event_id1);
		}
		var str = $("#"+form_id).serialize();
		if (visit_type == '' || visit_type == null && end == '') {
			toastr.error("No visit type or end time selected!");
		} else {
			$.mobile.loading("show");
			$.ajax({
				type: "POST",
				url: "ajaxschedule/edit-event",
				data: str,
				success: function(data){
					open_schedule(start_date);
					$("#"+form_id).clearForm();
					$("#edit_content").hide();
					$("#content").show();
					$.mobile.loading("hide");
				}
			});
		}
	}
	if (refresh_url == 'mobile_inbox') {
		if (action == 'save') {
			var bValid = true;
			$("#"+form_id).find("[required]").each(function() {
				var input_id = $(this).attr('id');
				var id1 = $("#" + input_id);
				var text = $("label[for='" + input_id + "']").html();
				bValid = bValid && checkEmpty(id1, text);
			});
			if (bValid) {
				$.mobile.loading("show");
				var str = $("#"+form_id).serialize();
				$.ajax({
					type: "POST",
					url: "ajaxmessaging/send-message",
					data: str,
					success: function(data){
						toastr.success(data);
						$("#"+form_id).clearForm();
						$("#edit_content").hide();
						$("#content").show();
						$.mobile.loading("hide");
					}
				});
			}
		}
		if (action == 'draft') {
			var str = $("#"+form_id).serialize();
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/draft-message",
				data: str,
				success: function(data){
					toastr.success(data);
					$("#"+form_id).clearForm();
					$("#edit_content").hide();
					$("#content").show();
					$.mobile.loading("hide");
				}
			});
		}
	}
	// more stuff
	$("#edit_content").hide();
	$("#content").show();
});
$(document).on("click", ".mobile_paste", function(e) {
	var value = $(this).attr('data-nosh-value');
	var to = $(this).attr('data-nosh-paste-to');
	$('#'+to).val(value);
	$('input[data-type="search"]').val("");
	$('input[data-type="search"]').trigger("keyup");
});
$(document).on("click", ".mobile_paste1", function(e) {
	var form = {};
	form.rxl_medication = $(this).attr('data-nosh-med');
	form.rxl_dosage = $(this).attr('data-nosh-value');
	form.rxl_dosage_unit = $(this).attr('data-nosh-unit');
	form.rxl_ndcid = $(this).attr('data-nosh-ndc');
	$.each(form, function(key, value){
		if (value != 'undefined') {
			$('#'+key).val(value);
		}
	});
	$('input[data-type="search"]').val("");
	$('input[data-type="search"]').trigger("keyup");
});
$(document).on("click", ".mobile_paste2", function(e) {
	var value = $(this).attr('data-nosh-value');
	var to = $("#form_item").val();
	$('#'+to).val(value);
	if (to == 'patient_search') {
		var id = $(this).attr('data-nosh-id');
		$("#schedule_pid").val(id);
		$("#schedule_title").val(value);
	}
	$("#right_panel").panel('close');
	$("#"+to).focus();
});
$(document).on("click", ".mobile_paste3", function(e) {
	var form = {};
	form.sup_supplement = $(this).attr('data-nosh-value');
	form.sup_dosage = $(this).attr('data-nosh-dosage');
	form.sup_dosage_unit = $(this).attr('data-nosh-dosage-unit');
	form.supplement_id = $(this).attr('data-nosh-supplement-id');
	$.each(form, function(key, value){
		if (value != 'undefined') {
			$('#'+key).val(value);
		}
	});
	$('input[data-type="search"]').val("");
	$('input[data-type="search"]').trigger("keyup");
});
$(document).on("click", ".mobile_paste4", function(e) {
	var value = $(this).attr('data-nosh-value');
	var to = $(this).attr('data-nosh-paste-to');
	var cvx = $(this).attr('data-nosh-cvx');
	$('#'+to).val(value);
	$('#imm_cvxcode').val(cvx);
	$('input[data-type="search"]').val("");
	$('input[data-type="search"]').trigger("keyup");
});
$(document).on("click", ".return_button", function(e) {
	$("#right_panel").panel('close');
});
$(document).on("click", "input", function(e) {
	if ($(this).hasClass('texthelper')) {
		var id = $(this).attr('id');
		$("#form_item").val(id);
		$("#navigation_header_fav").show();
	} else {
		$("#navigation_header_fav").hide();
	}
});
$(document).on('keydown', '.texthelper', function(e){
	var value = $(this).val();
	var input = $(this).attr('id');
	if (value && value.length > 1) {
		$("#form_item").val(input);
		var $ul = $("#search_results");
		var html = "";
		var parts = input.split('_');
		if (parts[0] == 'rxl') {
			var url = "../ajaxsearch/rx-search/" + input + "/true";
		}
		if (parts[0] == 'sup') {
			var url = "../ajaxsearch/sup-"+ parts[1];
		}
		if (parts[0] == 'allergies') {
			var url = "../ajaxsearch/reaction/true";
		}
		$.mobile.loading("show");
		$.ajax({
			url: url,
			dataType: "json",
			type: "POST",
			data: "term=" + value
		})
		.then(function(response) {
			if (response.response == 'true') {
				$.each(response.message, function ( i, val ) {
					if (val.value != null) {
						html += '<li><a href=# class="ui-btn ui-btn-icon-left ui-icon-carat-l mobile_paste2" data-nosh-value="' + val.value +'">' + val.label + '</a></li>';
					}
				});
				$ul.html(html);
				$ul.listview("refresh");
				$ul.trigger("updatelayout");
				$.mobile.loading("hide");
				$("#right_panel").panel('open');
			} else {
				$.mobile.loading("hide");
			}
		});
	}
});
$(document).on('keydown', '.texthelper1', function(e){
	var value = $(this).val();
	var input = $(this).attr('id');
	if (value && value.length > 2) {
		$.mobile.loading("show");
		$("#form_item").val(input);
		var $ul = $("#search_results");
		var html = "";
		$.ajax({
			url: "ajaxsearch/search",
			dataType: "json",
			type: "POST",
			data: "term=" + value
		})
		.then(function(response) {
			if (response.response == 'true') {
				$.each(response.message, function ( i, val ) {
					if (val.value != null) {
						html += '<li><a href=# class="ui-btn ui-btn-icon-left ui-icon-carat-l mobile_paste2" data-nosh-value="' + val.value +'" data-nosh-id="' + val.id + '">' + val.label + '</a></li>';
					}
				});
				$ul.html(html);
				$("#right_panel").width("500px");
				$ul.listview("refresh");
				$ul.trigger("updatelayout");
				$.mobile.loading("hide");
				$("#right_panel").panel('open');
			} else {
				$.mobile.loading("hide");
			}
		});
	}
});
$(document).on("click", "#nosh_fab", function(e) {
	$(".nosh_fab_child").toggle('fade');
	return false;
});
$(document).on("click", "#nosh_fab1", function(e) {
	$("#view_content").hide();
	$("#content").hide();
	$("#edit_content").show();
	return false;
});
$(document).on("change", "#provider_list2", function(e) {
	var id = $('#provider_list2').val();
	if(id){
		$.ajax({
			type: "POST",
			url: "ajaxschedule/set-provider",
			data: "id=" + id,
			success: function(data){
				$("#visit_type").removeOption(/./);
				$.ajax({
					url: "ajaxsearch/visit-types/" + id,
					dataType: "json",
					type: "POST",
					async: false,
					success: function(data){
						if (data.response == 'true') {
							$("#visit_type").addOption(data.message, false);
						} else {
							$("#visit_type").addOption({"":"No visit types available."},false);
						}
					}
				});
			}
		});
	}
});
$(document).on("click", ".cd-read-more", function(e) {
	$.mobile.loading("show");
	var type = $(this).attr('data-nosh-type');
	var value = $(this).attr('data-nosh-value');
	var status = $(this).attr('data-nosh-status');
	var scroll = $(this).closest('.cd-timeline-block').offset().top;
	var acl = false;
	if (noshdata.group_id == '2' || noshdata.group_id == '3') {
		acl = true;
	}
	if (type == 'eid') {
		if (status == 'Yes') {
			if (acl) {
				$("#content_inner_main").hide();
				$.ajax({
					type: "GET",
					url: "../ajaxchart/modal-view-mobile/" + value,
					success: function(data){
						$("#content_inner").html(data).trigger('create').show();
						$('#content_inner').find('h4').css('color','blue');
						$("#navigation_header_back").attr('data-nosh-origin', 'Chart');
						$("#navigation_header_back").attr('data-nosh-scroll', scroll);
						$("#chart_header").hide();
						$("#navigation_header").show();
						$("#left_panel").panel('close');
						$.mobile.loading("hide");
					}
				});
			} else {
				$("#content_inner_main").hide();
				$.ajax({
					type: "POST",
					url: "../ajaxcommon/opennotes",
					success: function(data){
						if (data == 'y') {
							$.ajax({
								type: "GET",
								url: "../ajaxcommon/modal-view2-mobile/" + value,
								success: function(data){
									$("#content_inner").html(data).trigger('create').show();
									$('#content_inner').find('h4').css('color','blue');
									$("#navigation_header_back").attr('data-nosh-origin', 'Chart');
									$("#navigation_header_back").attr('data-nosh-scroll', scroll);
									$("#chart_header").hide();
									$("#navigation_header").show();
									$("#left_panel").panel('close');
									$.mobile.loading("hide");
								}
							});
						} else {
							$toastr.error('You cannot view the encounter as your provider has not activated OpenNotes.');
							$.mobile.loading("hide");
							return false;
						}
					}
				});
			}
		} else {
			toastr.error('Encounter is not signed.  You cannot view it at this time.');
			$.mobile.loading("hide");
			return false;
		}
	} else if (type == 't_messages_id') {
		if (status == 'Yes') {
			if (acl) {
				$("#content_inner_main").hide();
				$.ajax({
					type: "GET",
					url: "../ajaxcommon/tmessages-view/" + value,
					success: function(data){
						$("#content_inner").html(data).trigger('create').show();
						$('#content_inner').find('strong').css('color','blue');
						$("#navigation_header_back").attr('data-nosh-origin', 'Chart');
						$("#navigation_header_back").attr('data-nosh-scroll', scroll);
						$("#chart_header").hide();
						$("#navigation_header").show();
						$("#left_panel").panel('close');
						$.mobile.loading("hide");
					}
				});
				//$("#message_view").load('ajaxcommon/tmessages-view/' + value);
				//$("#t_messages_id").val(value);
				//t_messages_tags();
				//$("#messages_view_dialog").dialog('open');
			} else {
				$("#content_inner_main").hide();
				$.ajax({
					type: "POST",
					url: "../ajaxcommon/opennotes",
					success: function(data){
						if (data == 'y') {
							$.ajax({
								type: "GET",
								url: "../ajaxcommon/tmessages-view/" + value,
								success: function(data){
									$("#content_inner").html(data).trigger('create').show();
									$('#content_inner').find('strong').css('color','blue');
									$("#navigation_header_back").attr('data-nosh-origin', 'Chart');
									$("#navigation_header_back").attr('data-nosh-scroll', scroll);
									$("#chart_header").hide();
									$("#navigation_header").show();
									$("#left_panel").panel('close');
									$.mobile.loading("hide");
								}
							});
							//$("#t_messages_id").val(value);
							//$("#messages_view_dialog").dialog('open');
						} else {
							toastr.error('You cannot view the message as your provider has not activated OpenNotes.');
							$.mobile.loading("hide");
							return false;
						}
					}
				});
			}
		} else {
			toastr.error('Message is not signed.  You cannot view it at this time.');
			$.mobile.loading("hide");
			return false;
		}
	}
});
$(document).on("click", ".messaging_tab", function(e) {
	var tab = $(this).attr('data-tab');
	open_messaging(tab);
	$("#edit_content").hide();
	$("#content").show();
});
$(document).on("click", ".back_message", function(e) {
	var tab = $(this).attr('data-origin');
	var id = $(this).attr('data-origin-id');
	open_messaging(tab);
	$("#view_content").hide();
	$("#edit_content").hide();
	$("#content").show();
	var scroll = parseInt($('.nosh_messaging_item[data-nosh-message-id="' + id + '"]').offset().top);
	$.mobile.silentScroll(scroll-70);
});
$(document).on("click", ".reply_message", function(e) {
	var form = {};
	$.each($(this).get(0).attributes, function(i, attr) {
		if (attr.name.indexOf("data-nosh-") == '0') {
			var field = attr.name.replace('data-nosh-','');
			field = field.replace(/-/g, '_');
			form[field] = attr.value;
			if (attr.value != 'undefined') {
				if (attr.value != 'null') {
					if (field != 'timestamp') {
						var value = attr.value;
						if (field == 'date') {
							value = moment(new Date(value)).format('YYYY-MM-DD');
						}
						$('input[name="' + field + '"]').val(value);
					}
				}
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxmessaging/get-displayname",
		data: "id=" + form['message_from'],
		success: function(data){
			$('select[name="messages_to[]"]').val(data);
			$('select[name="messages_to[]"]').selectmenu('refresh');
			var subject = 'Re: ' + form['subject'];
			$('input[name="subject"]').val(subject);
			var newbody = '\n\n' + 'On ' + form['date'] + ', ' + data + ' wrote:\n---------------------------------\n' + form['body'];
			$('textarea[name="body"]').val(newbody).caret(0);
			$('textarea[name="body"]').focus();
			$("#view_content").hide();
			$("#content").hide();
			$("#edit_content").show();
		}
	});
});
$(document).on("click", ".reply_all_message", function(e) {
	var form = {};
	$.each($(this).get(0).attributes, function(i, attr) {
		if (attr.name.indexOf("data-nosh-") == '0') {
			var field = attr.name.replace('data-nosh-','');
			field = field.replace(/-/g, '_');
			form[field] = attr.value;
			if (attr.value != 'undefined') {
				if (attr.value != 'null') {
					if (field != 'timestamp') {
						var value = attr.value;
						if (field == 'date') {
							value = moment(new Date(value)).format('YYYY-MM-DD');
						}
						$('input[name="' + field + '"]').val(value);
					}
				}
			}
		}
	});
	if (form['cc'] == ''){
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/get-displayname",
			data: "id=" + form['message_from'],
			success: function(data){
				$('select[name="messages_to[]"]').val(data);
				$('select[name="messages_to[]"]').selectmenu('refresh');
				var subject = 'Re: ' + form['subject'];
				$('input[name="subject"]').val(subject);
				var newbody = '\n\n' + 'On ' + form['date'] + ', ' + data + ' wrote:\n---------------------------------\n' + form['body'];
				$('textarea[name="body"]').val(newbody).caret(0);
				$('textarea[name="body"]').focus();
				$("#view_content").hide();
				$("#content").hide();
				$("#edit_content").show();
			}
		});
	} else {
		var to1 = to + ';' + cc;
		$.ajax({
			type: "POST",
			url: ".ajaxmessaging/get-displayname1",
			data: "id=" + form['message_from'] + ';' + form['cc'],
			success: function(data){
				var a_array = String(data).split(";");
				$('select[name="messages_to[]"]').val(a_array);
				$('select[name="messages_to[]"]').selectmenu('refresh');
				//var a_length = a_array.length;
				//for (var i = 0; i < a_length; i++) {
					//$('select[name="messages_to[]"]').selectOptions(a_array[i]);
				//}
				var subject = 'Re: ' + form['subject'];
				$('input[name="subject"]').val(subject);
				var newbody = '\n\n' + 'On ' + form['date'] + ', ' + data + ' wrote:\n---------------------------------\n' + form['body'];
				$('textarea[name="body"]').val(newbody).caret(0);
				$('textarea[name="body"]').focus();
				$("#view_content").hide();
				$("#content").hide();
				$("#edit_content").show();
			}
		});
	}
});
$(document).on("click", ".forward_message", function(e) {
	var form = {};
	$.each($(this).get(0).attributes, function(i, attr) {
		if (attr.name.indexOf("data-nosh-") == '0') {
			var field = attr.name.replace('data-nosh-','');
			field = field.replace(/-/g, '_');
			form[field] = attr.value;
			if (attr.value != 'undefined') {
				if (attr.value != 'null') {
					if (field != 'timestamp') {
						var value = attr.value;
						if (field == 'date') {
							value = moment(new Date(value)).format('YYYY-MM-DD');
						}
						$('input[name="' + field + '"]').val(value);
					}
				}
			}
		}
	});
	var rawtext = 'From:  ' + form['message_from_label'] + '\nDate: ' + form['date'] + '\nSubject: ' + form['subject'] + '\n\nMessage: ' + form['body'];
	var subject = 'Fwd: ' + form['subject'];
	$('input[name="subject"]').val(subject);
	var newbody = '\n\n' + 'On ' + form['date'] + ', ' + data + ' wrote:\n---------------------------------\n' + form['body'];
	$('input[name="body"]').val(newbody).caret(0);
	$('input[name="messages_to"]').focus();
	$("#view_content").hide();
	$("#content").hide();
	$("#edit_content").show();
});

$(document).on("click", ".template_click", function(e) {
	$.mobile.loading("show");
	//var id = $(this).prev().attr('id');
	//console.log(id);
	var id = 'hpi';
	$.mobile.loading("show");
	$.ajax({
		url: "ajaxsearch/textdump-group/" + id,
		type: "POST"
	})
	.then(function(response) {
		$("#textdump_group_html").html('');
		$("#textdump_group_html").append(response);
		$("#textdump_group_html").children().css({"padding":"6px"});
		$("#textdump_group_html").children().not(':last-child').css({"border-width":"2px","border-bottom":"2px black solid"});
		$(".edittextgroup").html('<i class="zmdi zmdi-edit"></i>').addClass('ui-btn ui-btn-inline');
		$(".deletetextgroup").html('<i class="zmdi zmdi-delete"></i>').addClass('ui-btn ui-btn-inline');
		$(".normaltextgroup").each(function(){
			$item = $(this);
			$nextdiv = $(this).parent().next();
			$($item).next('label').html('ALL NORMAL').css('color','blue').andSelf().wrapAll('<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true"></fieldset>').parent().prependTo($nextdiv);
		});
		$(".restricttextgroup").html('<i class="zmdi zmdi-close"></i>').addClass('ui-btn ui-btn-inline');
		$("#textdump_group_target").val(id);
		$('#textdump_group_html_div').css('overflow-y', 'scroll');
		$.mobile.loading("hide");
		$("#textdump_group_html").trigger('create');
		$('#textdump_group_html_div').popup('open');
	});
});
$('#textdump_group_html_div').on({
  popupbeforeposition: function() {
    var maxHeight = $(window).height() - 30;
    $('#textdump_group_html_div').css('max-height', maxHeight + 'px');
  }
});
$(document).on('click', '.textdump_group_item', function(){
	$.mobile.loading("show");
	var id = $("#textdump_group_target").val();
	var group = $(this).text();
	$("#textdump_group_item").val(group);
	var id1 = $(this).attr('id');
	$("#textdump_group_id").val(id1);
	$.ajax({
		type: "POST",
		url: "ajaxsearch/textdump/" + id,
		data: 'group='+group
	})
	.then(function(response) {
		$("#textdump_html").html('');
		$("#textdump_html").append(response);
		$("#textdump_html").children().css({"padding":"6px"});
		$("#textdump_html").children().not(':last-child').css({"border-width":"2px","border-bottom":"2px black solid"});
		$(".edittexttemplate").html('<i class="zmdi zmdi-edit"></i>').addClass('ui-btn ui-btn-inline');
		$(".deletetexttemplate").html('<i class="zmdi zmdi-delete"></i>').addClass('ui-btn ui-btn-inline');
		$(".normaltexttemplate").each(function(){
			$item = $(this);
			$nextdiv = $(this).parent();
			$($item).next('label').html('DEFAULT').css('color','blue').andSelf().wrapAll('<fieldset data-role="controlgroup"  data-type="horizontal"  data-mini="true"></fieldset>').parent().prependTo($nextdiv);
		});
		// $(".normaltexttemplate").button({text: false, icons: {primary: "ui-icon-check"}});
		// $('.textdump_item_text').editable('destroy');
		// $('.textdump_item_text').editable({
		// 	toggle:'manual',
		// 	ajaxOptions: {
		// 		headers: {"cache-control":"no-cache"},
		// 		beforeSend: function(request) {
		// 			return request.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
		// 		},
		// 		error: function(xhr) {
		// 			if (xhr.status == "404" ) {
		// 				alert("Route not found!");
		// 				//window.location.replace(noshdata.error);
		// 			} else {
		// 				if(xhr.responseText){
		// 					var response1 = $.parseJSON(xhr.responseText);
		// 					var error = "Error:\nType: " + response1.error.type + "\nMessage: " + response1.error.message + "\nFile: " + response1.error.file;
		// 					alert(error);
		// 				}
		// 			}
		// 		}
		// 	}
		// });
		$("#textdump_target").val(id);
		$('#textdump_html_div').css('overflow-y', 'scroll');
		$.mobile.loading("hide");
		$("#textdump_html").trigger('create');
		$('#textdump_group_html_div').popup('close');
		$('#textdump_html_div').popup('open');
	});
});
$('#textdump_html_div').on({
  popupbeforeposition: function() {
    var maxHeight = $(window).height() - 30;
    $('#textdump_html_div').css('max-height', maxHeight + 'px');
  }
});


/*! jQuery UI - v1.11.1 - 2014-09-10
* http://jqueryui.com
* Includes: core.js, datepicker.js
* Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([ "jquery" ], factory );
	} else {

		// Browser globals
		factory( jQuery );
	}
}(function( $ ) {
/*!
 * jQuery UI Core 1.11.1
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/category/ui-core/
 */


// $.ui might exist from components with no dependencies, e.g., $.ui.position
$.ui = $.ui || {};

$.extend( $.ui, {
	version: "1.11.1",

	keyCode: {
		BACKSPACE: 8,
		COMMA: 188,
		DELETE: 46,
		DOWN: 40,
		END: 35,
		ENTER: 13,
		ESCAPE: 27,
		HOME: 36,
		LEFT: 37,
		PAGE_DOWN: 34,
		PAGE_UP: 33,
		PERIOD: 190,
		RIGHT: 39,
		SPACE: 32,
		TAB: 9,
		UP: 38
	}
});

// plugins
$.fn.extend({
	scrollParent: function( includeHidden ) {
		var position = this.css( "position" ),
			excludeStaticParent = position === "absolute",
			overflowRegex = includeHidden ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
			scrollParent = this.parents().filter( function() {
				var parent = $( this );
				if ( excludeStaticParent && parent.css( "position" ) === "static" ) {
					return false;
				}
				return overflowRegex.test( parent.css( "overflow" ) + parent.css( "overflow-y" ) + parent.css( "overflow-x" ) );
			}).eq( 0 );

		return position === "fixed" || !scrollParent.length ? $( this[ 0 ].ownerDocument || document ) : scrollParent;
	},

	uniqueId: (function() {
		var uuid = 0;

		return function() {
			return this.each(function() {
				if ( !this.id ) {
					this.id = "ui-id-" + ( ++uuid );
				}
			});
		};
	})(),

	removeUniqueId: function() {
		return this.each(function() {
			if ( /^ui-id-\d+$/.test( this.id ) ) {
				$( this ).removeAttr( "id" );
			}
		});
	}
});

// selectors
function focusable( element, isTabIndexNotNaN ) {
	var map, mapName, img,
		nodeName = element.nodeName.toLowerCase();
	if ( "area" === nodeName ) {
		map = element.parentNode;
		mapName = map.name;
		if ( !element.href || !mapName || map.nodeName.toLowerCase() !== "map" ) {
			return false;
		}
		img = $( "img[usemap='#" + mapName + "']" )[ 0 ];
		return !!img && visible( img );
	}
	return ( /input|select|textarea|button|object/.test( nodeName ) ?
		!element.disabled :
		"a" === nodeName ?
			element.href || isTabIndexNotNaN :
			isTabIndexNotNaN) &&
		// the element and all of its ancestors must be visible
		visible( element );
}

function visible( element ) {
	return $.expr.filters.visible( element ) &&
		!$( element ).parents().addBack().filter(function() {
			return $.css( this, "visibility" ) === "hidden";
		}).length;
}

$.extend( $.expr[ ":" ], {
	data: $.expr.createPseudo ?
		$.expr.createPseudo(function( dataName ) {
			return function( elem ) {
				return !!$.data( elem, dataName );
			};
		}) :
		// support: jQuery <1.8
		function( elem, i, match ) {
			return !!$.data( elem, match[ 3 ] );
		},

	focusable: function( element ) {
		return focusable( element, !isNaN( $.attr( element, "tabindex" ) ) );
	},

	tabbable: function( element ) {
		var tabIndex = $.attr( element, "tabindex" ),
			isTabIndexNaN = isNaN( tabIndex );
		return ( isTabIndexNaN || tabIndex >= 0 ) && focusable( element, !isTabIndexNaN );
	}
});

// support: jQuery <1.8
if ( !$( "<a>" ).outerWidth( 1 ).jquery ) {
	$.each( [ "Width", "Height" ], function( i, name ) {
		var side = name === "Width" ? [ "Left", "Right" ] : [ "Top", "Bottom" ],
			type = name.toLowerCase(),
			orig = {
				innerWidth: $.fn.innerWidth,
				innerHeight: $.fn.innerHeight,
				outerWidth: $.fn.outerWidth,
				outerHeight: $.fn.outerHeight
			};

		function reduce( elem, size, border, margin ) {
			$.each( side, function() {
				size -= parseFloat( $.css( elem, "padding" + this ) ) || 0;
				if ( border ) {
					size -= parseFloat( $.css( elem, "border" + this + "Width" ) ) || 0;
				}
				if ( margin ) {
					size -= parseFloat( $.css( elem, "margin" + this ) ) || 0;
				}
			});
			return size;
		}

		$.fn[ "inner" + name ] = function( size ) {
			if ( size === undefined ) {
				return orig[ "inner" + name ].call( this );
			}

			return this.each(function() {
				$( this ).css( type, reduce( this, size ) + "px" );
			});
		};

		$.fn[ "outer" + name] = function( size, margin ) {
			if ( typeof size !== "number" ) {
				return orig[ "outer" + name ].call( this, size );
			}

			return this.each(function() {
				$( this).css( type, reduce( this, size, true, margin ) + "px" );
			});
		};
	});
}

// support: jQuery <1.8
if ( !$.fn.addBack ) {
	$.fn.addBack = function( selector ) {
		return this.add( selector == null ?
			this.prevObject : this.prevObject.filter( selector )
		);
	};
}

// support: jQuery 1.6.1, 1.6.2 (http://bugs.jquery.com/ticket/9413)
if ( $( "<a>" ).data( "a-b", "a" ).removeData( "a-b" ).data( "a-b" ) ) {
	$.fn.removeData = (function( removeData ) {
		return function( key ) {
			if ( arguments.length ) {
				return removeData.call( this, $.camelCase( key ) );
			} else {
				return removeData.call( this );
			}
		};
	})( $.fn.removeData );
}

// deprecated
$.ui.ie = !!/msie [\w.]+/.exec( navigator.userAgent.toLowerCase() );

$.fn.extend({
	focus: (function( orig ) {
		return function( delay, fn ) {
			return typeof delay === "number" ?
				this.each(function() {
					var elem = this;
					setTimeout(function() {
						$( elem ).focus();
						if ( fn ) {
							fn.call( elem );
						}
					}, delay );
				}) :
				orig.apply( this, arguments );
		};
	})( $.fn.focus ),

	disableSelection: (function() {
		var eventType = "onselectstart" in document.createElement( "div" ) ?
			"selectstart" :
			"mousedown";

		return function() {
			return this.bind( eventType + ".ui-disableSelection", function( event ) {
				event.preventDefault();
			});
		};
	})(),

	enableSelection: function() {
		return this.unbind( ".ui-disableSelection" );
	},

	zIndex: function( zIndex ) {
		if ( zIndex !== undefined ) {
			return this.css( "zIndex", zIndex );
		}

		if ( this.length ) {
			var elem = $( this[ 0 ] ), position, value;
			while ( elem.length && elem[ 0 ] !== document ) {
				// Ignore z-index if position is set to a value where z-index is ignored by the browser
				// This makes behavior of this function consistent across browsers
				// WebKit always returns auto if the element is positioned
				position = elem.css( "position" );
				if ( position === "absolute" || position === "relative" || position === "fixed" ) {
					// IE returns 0 when zIndex is not specified
					// other browsers return a string
					// we ignore the case of nested elements with an explicit value of 0
					// <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
					value = parseInt( elem.css( "zIndex" ), 10 );
					if ( !isNaN( value ) && value !== 0 ) {
						return value;
					}
				}
				elem = elem.parent();
			}
		}

		return 0;
	}
});

// $.ui.plugin is deprecated. Use $.widget() extensions instead.
$.ui.plugin = {
	add: function( module, option, set ) {
		var i,
			proto = $.ui[ module ].prototype;
		for ( i in set ) {
			proto.plugins[ i ] = proto.plugins[ i ] || [];
			proto.plugins[ i ].push( [ option, set[ i ] ] );
		}
	},
	call: function( instance, name, args, allowDisconnected ) {
		var i,
			set = instance.plugins[ name ];

		if ( !set ) {
			return;
		}

		if ( !allowDisconnected && ( !instance.element[ 0 ].parentNode || instance.element[ 0 ].parentNode.nodeType === 11 ) ) {
			return;
		}

		for ( i = 0; i < set.length; i++ ) {
			if ( instance.options[ set[ i ][ 0 ] ] ) {
				set[ i ][ 1 ].apply( instance.element, args );
			}
		}
	}
};


/*!
 * jQuery UI Datepicker 1.11.1
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/datepicker/
 */


$.extend($.ui, { datepicker: { version: "1.11.1" } });

var datepicker_instActive;

function datepicker_getZindex( elem ) {
	var position, value;
	while ( elem.length && elem[ 0 ] !== document ) {
		// Ignore z-index if position is set to a value where z-index is ignored by the browser
		// This makes behavior of this function consistent across browsers
		// WebKit always returns auto if the element is positioned
		position = elem.css( "position" );
		if ( position === "absolute" || position === "relative" || position === "fixed" ) {
			// IE returns 0 when zIndex is not specified
			// other browsers return a string
			// we ignore the case of nested elements with an explicit value of 0
			// <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
			value = parseInt( elem.css( "zIndex" ), 10 );
			if ( !isNaN( value ) && value !== 0 ) {
				return value;
			}
		}
		elem = elem.parent();
	}

	return 0;
}
/* Date picker manager.
   Use the singleton instance of this class, $.datepicker, to interact with the date picker.
   Settings for (groups of) date pickers are maintained in an instance object,
   allowing multiple different settings on the same page. */

function Datepicker() {
	this._curInst = null; // The current instance in use
	this._keyEvent = false; // If the last event was a key event
	this._disabledInputs = []; // List of date picker inputs that have been disabled
	this._datepickerShowing = false; // True if the popup picker is showing , false if not
	this._inDialog = false; // True if showing within a "dialog", false if not
	this._mainDivId = "ui-datepicker-div"; // The ID of the main datepicker division
	this._inlineClass = "ui-datepicker-inline"; // The name of the inline marker class
	this._appendClass = "ui-datepicker-append"; // The name of the append marker class
	this._triggerClass = "ui-datepicker-trigger"; // The name of the trigger marker class
	this._dialogClass = "ui-datepicker-dialog"; // The name of the dialog marker class
	this._disableClass = "ui-datepicker-disabled"; // The name of the disabled covering marker class
	this._unselectableClass = "ui-datepicker-unselectable"; // The name of the unselectable cell marker class
	this._currentClass = "ui-datepicker-current-day"; // The name of the current day marker class
	this._dayOverClass = "ui-datepicker-days-cell-over"; // The name of the day hover marker class
	this.regional = []; // Available regional settings, indexed by language code
	this.regional[""] = { // Default regional settings
		closeText: "Done", // Display text for close link
		prevText: "Prev", // Display text for previous month link
		nextText: "Next", // Display text for next month link
		currentText: "Today", // Display text for current month link
		monthNames: ["January","February","March","April","May","June",
			"July","August","September","October","November","December"], // Names of months for drop-down and formatting
		monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], // For formatting
		dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"], // For formatting
		dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], // For formatting
		dayNamesMin: ["Su","Mo","Tu","We","Th","Fr","Sa"], // Column headings for days starting at Sunday
		weekHeader: "Wk", // Column header for week of the year
		dateFormat: "mm/dd/yy", // See format options on parseDate
		firstDay: 0, // The first day of the week, Sun = 0, Mon = 1, ...
		isRTL: false, // True if right-to-left language, false if left-to-right
		showMonthAfterYear: false, // True if the year select precedes month, false for month then year
		yearSuffix: "" // Additional text to append to the year in the month headers
	};
	this._defaults = { // Global defaults for all the date picker instances
		showOn: "focus", // "focus" for popup on focus,
			// "button" for trigger button, or "both" for either
		showAnim: "fadeIn", // Name of jQuery animation for popup
		showOptions: {}, // Options for enhanced animations
		defaultDate: null, // Used when field is blank: actual date,
			// +/-number for offset from today, null for today
		appendText: "", // Display text following the input box, e.g. showing the format
		buttonText: "...", // Text for trigger button
		buttonImage: "", // URL for trigger button image
		buttonImageOnly: false, // True if the image appears alone, false if it appears on a button
		hideIfNoPrevNext: false, // True to hide next/previous month links
			// if not applicable, false to just disable them
		navigationAsDateFormat: false, // True if date formatting applied to prev/today/next links
		gotoCurrent: false, // True if today link goes back to current selection instead
		changeMonth: false, // True if month can be selected directly, false if only prev/next
		changeYear: false, // True if year can be selected directly, false if only prev/next
		yearRange: "c-10:c+10", // Range of years to display in drop-down,
			// either relative to today's year (-nn:+nn), relative to currently displayed year
			// (c-nn:c+nn), absolute (nnnn:nnnn), or a combination of the above (nnnn:-n)
		showOtherMonths: false, // True to show dates in other months, false to leave blank
		selectOtherMonths: false, // True to allow selection of dates in other months, false for unselectable
		showWeek: false, // True to show week of the year, false to not show it
		calculateWeek: this.iso8601Week, // How to calculate the week of the year,
			// takes a Date and returns the number of the week for it
		shortYearCutoff: "+10", // Short year values < this are in the current century,
			// > this are in the previous century,
			// string value starting with "+" for current year + value
		minDate: null, // The earliest selectable date, or null for no limit
		maxDate: null, // The latest selectable date, or null for no limit
		duration: "fast", // Duration of display/closure
		beforeShowDay: null, // Function that takes a date and returns an array with
			// [0] = true if selectable, false if not, [1] = custom CSS class name(s) or "",
			// [2] = cell title (optional), e.g. $.datepicker.noWeekends
		beforeShow: null, // Function that takes an input field and
			// returns a set of custom settings for the date picker
		onSelect: null, // Define a callback function when a date is selected
		onChangeMonthYear: null, // Define a callback function when the month or year is changed
		onClose: null, // Define a callback function when the datepicker is closed
		numberOfMonths: 1, // Number of months to show at a time
		showCurrentAtPos: 0, // The position in multipe months at which to show the current month (starting at 0)
		stepMonths: 1, // Number of months to step back/forward
		stepBigMonths: 12, // Number of months to step back/forward for the big links
		altField: "", // Selector for an alternate field to store selected dates into
		altFormat: "", // The date format to use for the alternate field
		constrainInput: true, // The input is constrained by the current date format
		showButtonPanel: false, // True to show button panel, false to not show it
		autoSize: false, // True to size the input for the date format, false to leave as is
		disabled: false // The initial disabled state
	};
	$.extend(this._defaults, this.regional[""]);
	this.regional.en = $.extend( true, {}, this.regional[ "" ]);
	this.regional[ "en-US" ] = $.extend( true, {}, this.regional.en );
	this.dpDiv = datepicker_bindHover($("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"));
}

$.extend(Datepicker.prototype, {
	/* Class name added to elements to indicate already configured with a date picker. */
	markerClassName: "hasDatepicker",

	//Keep track of the maximum number of rows displayed (see #7043)
	maxRows: 4,

	// TODO rename to "widget" when switching to widget factory
	_widgetDatepicker: function() {
		return this.dpDiv;
	},

	/* Override the default settings for all instances of the date picker.
	 * @param  settings  object - the new settings to use as defaults (anonymous object)
	 * @return the manager object
	 */
	setDefaults: function(settings) {
		datepicker_extendRemove(this._defaults, settings || {});
		return this;
	},

	/* Attach the date picker to a jQuery selection.
	 * @param  target	element - the target input field or division or span
	 * @param  settings  object - the new settings to use for this date picker instance (anonymous)
	 */
	_attachDatepicker: function(target, settings) {
		var nodeName, inline, inst;
		nodeName = target.nodeName.toLowerCase();
		inline = (nodeName === "div" || nodeName === "span");
		if (!target.id) {
			this.uuid += 1;
			target.id = "dp" + this.uuid;
		}
		inst = this._newInst($(target), inline);
		inst.settings = $.extend({}, settings || {});
		if (nodeName === "input") {
			this._connectDatepicker(target, inst);
		} else if (inline) {
			this._inlineDatepicker(target, inst);
		}
	},

	/* Create a new instance object. */
	_newInst: function(target, inline) {
		var id = target[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1"); // escape jQuery meta chars
		return {id: id, input: target, // associated target
			selectedDay: 0, selectedMonth: 0, selectedYear: 0, // current selection
			drawMonth: 0, drawYear: 0, // month being drawn
			inline: inline, // is datepicker inline or not
			dpDiv: (!inline ? this.dpDiv : // presentation div
			datepicker_bindHover($("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")))};
	},

	/* Attach the date picker to an input field. */
	_connectDatepicker: function(target, inst) {
		var input = $(target);
		inst.append = $([]);
		inst.trigger = $([]);
		if (input.hasClass(this.markerClassName)) {
			return;
		}
		this._attachments(input, inst);
		input.addClass(this.markerClassName).keydown(this._doKeyDown).
			keypress(this._doKeyPress).keyup(this._doKeyUp);
		this._autoSize(inst);
		$.data(target, "datepicker", inst);
		//If disabled option is true, disable the datepicker once it has been attached to the input (see ticket #5665)
		if( inst.settings.disabled ) {
			this._disableDatepicker( target );
		}
	},

	/* Make attachments based on settings. */
	_attachments: function(input, inst) {
		var showOn, buttonText, buttonImage,
			appendText = this._get(inst, "appendText"),
			isRTL = this._get(inst, "isRTL");

		if (inst.append) {
			inst.append.remove();
		}
		if (appendText) {
			inst.append = $("<span class='" + this._appendClass + "'>" + appendText + "</span>");
			input[isRTL ? "before" : "after"](inst.append);
		}

		input.unbind("focus", this._showDatepicker);

		if (inst.trigger) {
			inst.trigger.remove();
		}

		showOn = this._get(inst, "showOn");
		if (showOn === "focus" || showOn === "both") { // pop-up date picker when in the marked field
			input.focus(this._showDatepicker);
		}
		if (showOn === "button" || showOn === "both") { // pop-up date picker when button clicked
			buttonText = this._get(inst, "buttonText");
			buttonImage = this._get(inst, "buttonImage");
			inst.trigger = $(this._get(inst, "buttonImageOnly") ?
				$("<img/>").addClass(this._triggerClass).
					attr({ src: buttonImage, alt: buttonText, title: buttonText }) :
				$("<button type='button'></button>").addClass(this._triggerClass).
					html(!buttonImage ? buttonText : $("<img/>").attr(
					{ src:buttonImage, alt:buttonText, title:buttonText })));
			input[isRTL ? "before" : "after"](inst.trigger);
			inst.trigger.click(function() {
				if ($.datepicker._datepickerShowing && $.datepicker._lastInput === input[0]) {
					$.datepicker._hideDatepicker();
				} else if ($.datepicker._datepickerShowing && $.datepicker._lastInput !== input[0]) {
					$.datepicker._hideDatepicker();
					$.datepicker._showDatepicker(input[0]);
				} else {
					$.datepicker._showDatepicker(input[0]);
				}
				return false;
			});
		}
	},

	/* Apply the maximum length for the date format. */
	_autoSize: function(inst) {
		if (this._get(inst, "autoSize") && !inst.inline) {
			var findMax, max, maxI, i,
				date = new Date(2009, 12 - 1, 20), // Ensure double digits
				dateFormat = this._get(inst, "dateFormat");

			if (dateFormat.match(/[DM]/)) {
				findMax = function(names) {
					max = 0;
					maxI = 0;
					for (i = 0; i < names.length; i++) {
						if (names[i].length > max) {
							max = names[i].length;
							maxI = i;
						}
					}
					return maxI;
				};
				date.setMonth(findMax(this._get(inst, (dateFormat.match(/MM/) ?
					"monthNames" : "monthNamesShort"))));
				date.setDate(findMax(this._get(inst, (dateFormat.match(/DD/) ?
					"dayNames" : "dayNamesShort"))) + 20 - date.getDay());
			}
			inst.input.attr("size", this._formatDate(inst, date).length);
		}
	},

	/* Attach an inline date picker to a div. */
	_inlineDatepicker: function(target, inst) {
		var divSpan = $(target);
		if (divSpan.hasClass(this.markerClassName)) {
			return;
		}
		divSpan.addClass(this.markerClassName).append(inst.dpDiv);
		$.data(target, "datepicker", inst);
		this._setDate(inst, this._getDefaultDate(inst), true);
		this._updateDatepicker(inst);
		this._updateAlternate(inst);
		//If disabled option is true, disable the datepicker before showing it (see ticket #5665)
		if( inst.settings.disabled ) {
			this._disableDatepicker( target );
		}
		// Set display:block in place of inst.dpDiv.show() which won't work on disconnected elements
		// http://bugs.jqueryui.com/ticket/7552 - A Datepicker created on a detached div has zero height
		inst.dpDiv.css( "display", "block" );
	},

	/* Pop-up the date picker in a "dialog" box.
	 * @param  input element - ignored
	 * @param  date	string or Date - the initial date to display
	 * @param  onSelect  function - the function to call when a date is selected
	 * @param  settings  object - update the dialog date picker instance's settings (anonymous object)
	 * @param  pos int[2] - coordinates for the dialog's position within the screen or
	 *					event - with x/y coordinates or
	 *					leave empty for default (screen centre)
	 * @return the manager object
	 */
	_dialogDatepicker: function(input, date, onSelect, settings, pos) {
		var id, browserWidth, browserHeight, scrollX, scrollY,
			inst = this._dialogInst; // internal instance

		if (!inst) {
			this.uuid += 1;
			id = "dp" + this.uuid;
			this._dialogInput = $("<input type='text' id='" + id +
				"' style='position: absolute; top: -100px; width: 0px;'/>");
			this._dialogInput.keydown(this._doKeyDown);
			$("body").append(this._dialogInput);
			inst = this._dialogInst = this._newInst(this._dialogInput, false);
			inst.settings = {};
			$.data(this._dialogInput[0], "datepicker", inst);
		}
		datepicker_extendRemove(inst.settings, settings || {});
		date = (date && date.constructor === Date ? this._formatDate(inst, date) : date);
		this._dialogInput.val(date);

		this._pos = (pos ? (pos.length ? pos : [pos.pageX, pos.pageY]) : null);
		if (!this._pos) {
			browserWidth = document.documentElement.clientWidth;
			browserHeight = document.documentElement.clientHeight;
			scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
			scrollY = document.documentElement.scrollTop || document.body.scrollTop;
			this._pos = // should use actual width/height below
				[(browserWidth / 2) - 100 + scrollX, (browserHeight / 2) - 150 + scrollY];
		}

		// move input on screen for focus, but hidden behind dialog
		this._dialogInput.css("left", (this._pos[0] + 20) + "px").css("top", this._pos[1] + "px");
		inst.settings.onSelect = onSelect;
		this._inDialog = true;
		this.dpDiv.addClass(this._dialogClass);
		this._showDatepicker(this._dialogInput[0]);
		if ($.blockUI) {
			$.blockUI(this.dpDiv);
		}
		$.data(this._dialogInput[0], "datepicker", inst);
		return this;
	},

	/* Detach a datepicker from its control.
	 * @param  target	element - the target input field or division or span
	 */
	_destroyDatepicker: function(target) {
		var nodeName,
			$target = $(target),
			inst = $.data(target, "datepicker");

		if (!$target.hasClass(this.markerClassName)) {
			return;
		}

		nodeName = target.nodeName.toLowerCase();
		$.removeData(target, "datepicker");
		if (nodeName === "input") {
			inst.append.remove();
			inst.trigger.remove();
			$target.removeClass(this.markerClassName).
				unbind("focus", this._showDatepicker).
				unbind("keydown", this._doKeyDown).
				unbind("keypress", this._doKeyPress).
				unbind("keyup", this._doKeyUp);
		} else if (nodeName === "div" || nodeName === "span") {
			$target.removeClass(this.markerClassName).empty();
		}
	},

	/* Enable the date picker to a jQuery selection.
	 * @param  target	element - the target input field or division or span
	 */
	_enableDatepicker: function(target) {
		var nodeName, inline,
			$target = $(target),
			inst = $.data(target, "datepicker");

		if (!$target.hasClass(this.markerClassName)) {
			return;
		}

		nodeName = target.nodeName.toLowerCase();
		if (nodeName === "input") {
			target.disabled = false;
			inst.trigger.filter("button").
				each(function() { this.disabled = false; }).end().
				filter("img").css({opacity: "1.0", cursor: ""});
		} else if (nodeName === "div" || nodeName === "span") {
			inline = $target.children("." + this._inlineClass);
			inline.children().removeClass("ui-state-disabled");
			inline.find("select.ui-datepicker-month, select.ui-datepicker-year").
				prop("disabled", false);
		}
		this._disabledInputs = $.map(this._disabledInputs,
			function(value) { return (value === target ? null : value); }); // delete entry
	},

	/* Disable the date picker to a jQuery selection.
	 * @param  target	element - the target input field or division or span
	 */
	_disableDatepicker: function(target) {
		var nodeName, inline,
			$target = $(target),
			inst = $.data(target, "datepicker");

		if (!$target.hasClass(this.markerClassName)) {
			return;
		}

		nodeName = target.nodeName.toLowerCase();
		if (nodeName === "input") {
			target.disabled = true;
			inst.trigger.filter("button").
				each(function() { this.disabled = true; }).end().
				filter("img").css({opacity: "0.5", cursor: "default"});
		} else if (nodeName === "div" || nodeName === "span") {
			inline = $target.children("." + this._inlineClass);
			inline.children().addClass("ui-state-disabled");
			inline.find("select.ui-datepicker-month, select.ui-datepicker-year").
				prop("disabled", true);
		}
		this._disabledInputs = $.map(this._disabledInputs,
			function(value) { return (value === target ? null : value); }); // delete entry
		this._disabledInputs[this._disabledInputs.length] = target;
	},

	/* Is the first field in a jQuery collection disabled as a datepicker?
	 * @param  target	element - the target input field or division or span
	 * @return boolean - true if disabled, false if enabled
	 */
	_isDisabledDatepicker: function(target) {
		if (!target) {
			return false;
		}
		for (var i = 0; i < this._disabledInputs.length; i++) {
			if (this._disabledInputs[i] === target) {
				return true;
			}
		}
		return false;
	},

	/* Retrieve the instance data for the target control.
	 * @param  target  element - the target input field or division or span
	 * @return  object - the associated instance data
	 * @throws  error if a jQuery problem getting data
	 */
	_getInst: function(target) {
		try {
			return $.data(target, "datepicker");
		}
		catch (err) {
			throw "Missing instance data for this datepicker";
		}
	},

	/* Update or retrieve the settings for a date picker attached to an input field or division.
	 * @param  target  element - the target input field or division or span
	 * @param  name	object - the new settings to update or
	 *				string - the name of the setting to change or retrieve,
	 *				when retrieving also "all" for all instance settings or
	 *				"defaults" for all global defaults
	 * @param  value   any - the new value for the setting
	 *				(omit if above is an object or to retrieve a value)
	 */
	_optionDatepicker: function(target, name, value) {
		var settings, date, minDate, maxDate,
			inst = this._getInst(target);

		if (arguments.length === 2 && typeof name === "string") {
			return (name === "defaults" ? $.extend({}, $.datepicker._defaults) :
				(inst ? (name === "all" ? $.extend({}, inst.settings) :
				this._get(inst, name)) : null));
		}

		settings = name || {};
		if (typeof name === "string") {
			settings = {};
			settings[name] = value;
		}

		if (inst) {
			if (this._curInst === inst) {
				this._hideDatepicker();
			}

			date = this._getDateDatepicker(target, true);
			minDate = this._getMinMaxDate(inst, "min");
			maxDate = this._getMinMaxDate(inst, "max");
			datepicker_extendRemove(inst.settings, settings);
			// reformat the old minDate/maxDate values if dateFormat changes and a new minDate/maxDate isn't provided
			if (minDate !== null && settings.dateFormat !== undefined && settings.minDate === undefined) {
				inst.settings.minDate = this._formatDate(inst, minDate);
			}
			if (maxDate !== null && settings.dateFormat !== undefined && settings.maxDate === undefined) {
				inst.settings.maxDate = this._formatDate(inst, maxDate);
			}
			if ( "disabled" in settings ) {
				if ( settings.disabled ) {
					this._disableDatepicker(target);
				} else {
					this._enableDatepicker(target);
				}
			}
			this._attachments($(target), inst);
			this._autoSize(inst);
			this._setDate(inst, date);
			this._updateAlternate(inst);
			this._updateDatepicker(inst);
		}
	},

	// change method deprecated
	_changeDatepicker: function(target, name, value) {
		this._optionDatepicker(target, name, value);
	},

	/* Redraw the date picker attached to an input field or division.
	 * @param  target  element - the target input field or division or span
	 */
	_refreshDatepicker: function(target) {
		var inst = this._getInst(target);
		if (inst) {
			this._updateDatepicker(inst);
		}
	},

	/* Set the dates for a jQuery selection.
	 * @param  target element - the target input field or division or span
	 * @param  date	Date - the new date
	 */
	_setDateDatepicker: function(target, date) {
		var inst = this._getInst(target);
		if (inst) {
			this._setDate(inst, date);
			this._updateDatepicker(inst);
			this._updateAlternate(inst);
		}
	},

	/* Get the date(s) for the first entry in a jQuery selection.
	 * @param  target element - the target input field or division or span
	 * @param  noDefault boolean - true if no default date is to be used
	 * @return Date - the current date
	 */
	_getDateDatepicker: function(target, noDefault) {
		var inst = this._getInst(target);
		if (inst && !inst.inline) {
			this._setDateFromField(inst, noDefault);
		}
		return (inst ? this._getDate(inst) : null);
	},

	/* Handle keystrokes. */
	_doKeyDown: function(event) {
		var onSelect, dateStr, sel,
			inst = $.datepicker._getInst(event.target),
			handled = true,
			isRTL = inst.dpDiv.is(".ui-datepicker-rtl");

		inst._keyEvent = true;
		if ($.datepicker._datepickerShowing) {
			switch (event.keyCode) {
				case 9: $.datepicker._hideDatepicker();
						handled = false;
						break; // hide on tab out
				case 13: sel = $("td." + $.datepicker._dayOverClass + ":not(." +
									$.datepicker._currentClass + ")", inst.dpDiv);
						if (sel[0]) {
							$.datepicker._selectDay(event.target, inst.selectedMonth, inst.selectedYear, sel[0]);
						}

						onSelect = $.datepicker._get(inst, "onSelect");
						if (onSelect) {
							dateStr = $.datepicker._formatDate(inst);

							// trigger custom callback
							onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);
						} else {
							$.datepicker._hideDatepicker();
						}

						return false; // don't submit the form
				case 27: $.datepicker._hideDatepicker();
						break; // hide on escape
				case 33: $.datepicker._adjustDate(event.target, (event.ctrlKey ?
							-$.datepicker._get(inst, "stepBigMonths") :
							-$.datepicker._get(inst, "stepMonths")), "M");
						break; // previous month/year on page up/+ ctrl
				case 34: $.datepicker._adjustDate(event.target, (event.ctrlKey ?
							+$.datepicker._get(inst, "stepBigMonths") :
							+$.datepicker._get(inst, "stepMonths")), "M");
						break; // next month/year on page down/+ ctrl
				case 35: if (event.ctrlKey || event.metaKey) {
							$.datepicker._clearDate(event.target);
						}
						handled = event.ctrlKey || event.metaKey;
						break; // clear on ctrl or command +end
				case 36: if (event.ctrlKey || event.metaKey) {
							$.datepicker._gotoToday(event.target);
						}
						handled = event.ctrlKey || event.metaKey;
						break; // current on ctrl or command +home
				case 37: if (event.ctrlKey || event.metaKey) {
							$.datepicker._adjustDate(event.target, (isRTL ? +1 : -1), "D");
						}
						handled = event.ctrlKey || event.metaKey;
						// -1 day on ctrl or command +left
						if (event.originalEvent.altKey) {
							$.datepicker._adjustDate(event.target, (event.ctrlKey ?
								-$.datepicker._get(inst, "stepBigMonths") :
								-$.datepicker._get(inst, "stepMonths")), "M");
						}
						// next month/year on alt +left on Mac
						break;
				case 38: if (event.ctrlKey || event.metaKey) {
							$.datepicker._adjustDate(event.target, -7, "D");
						}
						handled = event.ctrlKey || event.metaKey;
						break; // -1 week on ctrl or command +up
				case 39: if (event.ctrlKey || event.metaKey) {
							$.datepicker._adjustDate(event.target, (isRTL ? -1 : +1), "D");
						}
						handled = event.ctrlKey || event.metaKey;
						// +1 day on ctrl or command +right
						if (event.originalEvent.altKey) {
							$.datepicker._adjustDate(event.target, (event.ctrlKey ?
								+$.datepicker._get(inst, "stepBigMonths") :
								+$.datepicker._get(inst, "stepMonths")), "M");
						}
						// next month/year on alt +right
						break;
				case 40: if (event.ctrlKey || event.metaKey) {
							$.datepicker._adjustDate(event.target, +7, "D");
						}
						handled = event.ctrlKey || event.metaKey;
						break; // +1 week on ctrl or command +down
				default: handled = false;
			}
		} else if (event.keyCode === 36 && event.ctrlKey) { // display the date picker on ctrl+home
			$.datepicker._showDatepicker(this);
		} else {
			handled = false;
		}

		if (handled) {
			event.preventDefault();
			event.stopPropagation();
		}
	},

	/* Filter entered characters - based on date format. */
	_doKeyPress: function(event) {
		var chars, chr,
			inst = $.datepicker._getInst(event.target);

		if ($.datepicker._get(inst, "constrainInput")) {
			chars = $.datepicker._possibleChars($.datepicker._get(inst, "dateFormat"));
			chr = String.fromCharCode(event.charCode == null ? event.keyCode : event.charCode);
			return event.ctrlKey || event.metaKey || (chr < " " || !chars || chars.indexOf(chr) > -1);
		}
	},

	/* Synchronise manual entry and field/alternate field. */
	_doKeyUp: function(event) {
		var date,
			inst = $.datepicker._getInst(event.target);

		if (inst.input.val() !== inst.lastVal) {
			try {
				date = $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"),
					(inst.input ? inst.input.val() : null),
					$.datepicker._getFormatConfig(inst));

				if (date) { // only if valid
					$.datepicker._setDateFromField(inst);
					$.datepicker._updateAlternate(inst);
					$.datepicker._updateDatepicker(inst);
				}
			}
			catch (err) {
			}
		}
		return true;
	},

	/* Pop-up the date picker for a given input field.
	 * If false returned from beforeShow event handler do not show.
	 * @param  input  element - the input field attached to the date picker or
	 *					event - if triggered by focus
	 */
	_showDatepicker: function(input) {
		input = input.target || input;
		if (input.nodeName.toLowerCase() !== "input") { // find from button/image trigger
			input = $("input", input.parentNode)[0];
		}

		if ($.datepicker._isDisabledDatepicker(input) || $.datepicker._lastInput === input) { // already here
			return;
		}

		var inst, beforeShow, beforeShowSettings, isFixed,
			offset, showAnim, duration;

		inst = $.datepicker._getInst(input);
		if ($.datepicker._curInst && $.datepicker._curInst !== inst) {
			$.datepicker._curInst.dpDiv.stop(true, true);
			if ( inst && $.datepicker._datepickerShowing ) {
				$.datepicker._hideDatepicker( $.datepicker._curInst.input[0] );
			}
		}

		beforeShow = $.datepicker._get(inst, "beforeShow");
		beforeShowSettings = beforeShow ? beforeShow.apply(input, [input, inst]) : {};
		if(beforeShowSettings === false){
			return;
		}
		datepicker_extendRemove(inst.settings, beforeShowSettings);

		inst.lastVal = null;
		$.datepicker._lastInput = input;
		$.datepicker._setDateFromField(inst);

		if ($.datepicker._inDialog) { // hide cursor
			input.value = "";
		}
		if (!$.datepicker._pos) { // position below input
			$.datepicker._pos = $.datepicker._findPos(input);
			$.datepicker._pos[1] += input.offsetHeight; // add the height
		}

		isFixed = false;
		$(input).parents().each(function() {
			isFixed |= $(this).css("position") === "fixed";
			return !isFixed;
		});

		offset = {left: $.datepicker._pos[0], top: $.datepicker._pos[1]};
		$.datepicker._pos = null;
		//to avoid flashes on Firefox
		inst.dpDiv.empty();
		// determine sizing offscreen
		inst.dpDiv.css({position: "absolute", display: "block", top: "-1000px"});
		$.datepicker._updateDatepicker(inst);
		// fix width for dynamic number of date pickers
		// and adjust position before showing
		offset = $.datepicker._checkOffset(inst, offset, isFixed);
		inst.dpDiv.css({position: ($.datepicker._inDialog && $.blockUI ?
			"static" : (isFixed ? "fixed" : "absolute")), display: "none",
			left: offset.left + "px", top: offset.top + "px"});

		if (!inst.inline) {
			showAnim = $.datepicker._get(inst, "showAnim");
			duration = $.datepicker._get(inst, "duration");
			inst.dpDiv.css( "z-index", datepicker_getZindex( $( input ) ) + 1 );
			$.datepicker._datepickerShowing = true;

			if ( $.effects && $.effects.effect[ showAnim ] ) {
				inst.dpDiv.show(showAnim, $.datepicker._get(inst, "showOptions"), duration);
			} else {
				inst.dpDiv[showAnim || "show"](showAnim ? duration : null);
			}

			if ( $.datepicker._shouldFocusInput( inst ) ) {
				inst.input.focus();
			}

			$.datepicker._curInst = inst;
		}
	},

	/* Generate the date picker content. */
	_updateDatepicker: function(inst) {
		this.maxRows = 4; //Reset the max number of rows being displayed (see #7043)
		datepicker_instActive = inst; // for delegate hover events
		inst.dpDiv.empty().append(this._generateHTML(inst));
		this._attachHandlers(inst);

		var origyearshtml,
			numMonths = this._getNumberOfMonths(inst),
			cols = numMonths[1],
			width = 17,
			activeCell = inst.dpDiv.find( "." + this._dayOverClass + " a" );

		if ( activeCell.length > 0 ) {
			datepicker_handleMouseover.apply( activeCell.get( 0 ) );
		}

		inst.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");
		if (cols > 1) {
			inst.dpDiv.addClass("ui-datepicker-multi-" + cols).css("width", (width * cols) + "em");
		}
		inst.dpDiv[(numMonths[0] !== 1 || numMonths[1] !== 1 ? "add" : "remove") +
			"Class"]("ui-datepicker-multi");
		inst.dpDiv[(this._get(inst, "isRTL") ? "add" : "remove") +
			"Class"]("ui-datepicker-rtl");

		if (inst === $.datepicker._curInst && $.datepicker._datepickerShowing && $.datepicker._shouldFocusInput( inst ) ) {
			inst.input.focus();
		}

		// deffered render of the years select (to avoid flashes on Firefox)
		if( inst.yearshtml ){
			origyearshtml = inst.yearshtml;
			setTimeout(function(){
				//assure that inst.yearshtml didn't change.
				if( origyearshtml === inst.yearshtml && inst.yearshtml ){
					inst.dpDiv.find("select.ui-datepicker-year:first").replaceWith(inst.yearshtml);
				}
				origyearshtml = inst.yearshtml = null;
			}, 0);
		}
	},

	// #6694 - don't focus the input if it's already focused
	// this breaks the change event in IE
	// Support: IE and jQuery <1.9
	_shouldFocusInput: function( inst ) {
		return inst.input && inst.input.is( ":visible" ) && !inst.input.is( ":disabled" ) && !inst.input.is( ":focus" );
	},

	/* Check positioning to remain on screen. */
	_checkOffset: function(inst, offset, isFixed) {
		var dpWidth = inst.dpDiv.outerWidth(),
			dpHeight = inst.dpDiv.outerHeight(),
			inputWidth = inst.input ? inst.input.outerWidth() : 0,
			inputHeight = inst.input ? inst.input.outerHeight() : 0,
			viewWidth = document.documentElement.clientWidth + (isFixed ? 0 : $(document).scrollLeft()),
			viewHeight = document.documentElement.clientHeight + (isFixed ? 0 : $(document).scrollTop());

		offset.left -= (this._get(inst, "isRTL") ? (dpWidth - inputWidth) : 0);
		offset.left -= (isFixed && offset.left === inst.input.offset().left) ? $(document).scrollLeft() : 0;
		offset.top -= (isFixed && offset.top === (inst.input.offset().top + inputHeight)) ? $(document).scrollTop() : 0;

		// now check if datepicker is showing outside window viewport - move to a better place if so.
		offset.left -= Math.min(offset.left, (offset.left + dpWidth > viewWidth && viewWidth > dpWidth) ?
			Math.abs(offset.left + dpWidth - viewWidth) : 0);
		offset.top -= Math.min(offset.top, (offset.top + dpHeight > viewHeight && viewHeight > dpHeight) ?
			Math.abs(dpHeight + inputHeight) : 0);

		return offset;
	},

	/* Find an object's position on the screen. */
	_findPos: function(obj) {
		var position,
			inst = this._getInst(obj),
			isRTL = this._get(inst, "isRTL");

		while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.filters.hidden(obj))) {
			obj = obj[isRTL ? "previousSibling" : "nextSibling"];
		}

		position = $(obj).offset();
		return [position.left, position.top];
	},

	/* Hide the date picker from view.
	 * @param  input  element - the input field attached to the date picker
	 */
	_hideDatepicker: function(input) {
		var showAnim, duration, postProcess, onClose,
			inst = this._curInst;

		if (!inst || (input && inst !== $.data(input, "datepicker"))) {
			return;
		}

		if (this._datepickerShowing) {
			showAnim = this._get(inst, "showAnim");
			duration = this._get(inst, "duration");
			postProcess = function() {
				$.datepicker._tidyDialog(inst);
			};

			// DEPRECATED: after BC for 1.8.x $.effects[ showAnim ] is not needed
			if ( $.effects && ( $.effects.effect[ showAnim ] || $.effects[ showAnim ] ) ) {
				inst.dpDiv.hide(showAnim, $.datepicker._get(inst, "showOptions"), duration, postProcess);
			} else {
				inst.dpDiv[(showAnim === "slideDown" ? "slideUp" :
					(showAnim === "fadeIn" ? "fadeOut" : "hide"))]((showAnim ? duration : null), postProcess);
			}

			if (!showAnim) {
				postProcess();
			}
			this._datepickerShowing = false;

			onClose = this._get(inst, "onClose");
			if (onClose) {
				onClose.apply((inst.input ? inst.input[0] : null), [(inst.input ? inst.input.val() : ""), inst]);
			}

			this._lastInput = null;
			if (this._inDialog) {
				this._dialogInput.css({ position: "absolute", left: "0", top: "-100px" });
				if ($.blockUI) {
					$.unblockUI();
					$("body").append(this.dpDiv);
				}
			}
			this._inDialog = false;
		}
	},

	/* Tidy up after a dialog display. */
	_tidyDialog: function(inst) {
		inst.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar");
	},

	/* Close date picker if clicked elsewhere. */
	_checkExternalClick: function(event) {
		if (!$.datepicker._curInst) {
			return;
		}

		var $target = $(event.target),
			inst = $.datepicker._getInst($target[0]);

		if ( ( ( $target[0].id !== $.datepicker._mainDivId &&
				$target.parents("#" + $.datepicker._mainDivId).length === 0 &&
				!$target.hasClass($.datepicker.markerClassName) &&
				!$target.closest("." + $.datepicker._triggerClass).length &&
				$.datepicker._datepickerShowing && !($.datepicker._inDialog && $.blockUI) ) ) ||
			( $target.hasClass($.datepicker.markerClassName) && $.datepicker._curInst !== inst ) ) {
				$.datepicker._hideDatepicker();
		}
	},

	/* Adjust one of the date sub-fields. */
	_adjustDate: function(id, offset, period) {
		var target = $(id),
			inst = this._getInst(target[0]);

		if (this._isDisabledDatepicker(target[0])) {
			return;
		}
		this._adjustInstDate(inst, offset +
			(period === "M" ? this._get(inst, "showCurrentAtPos") : 0), // undo positioning
			period);
		this._updateDatepicker(inst);
	},

	/* Action for current link. */
	_gotoToday: function(id) {
		var date,
			target = $(id),
			inst = this._getInst(target[0]);

		if (this._get(inst, "gotoCurrent") && inst.currentDay) {
			inst.selectedDay = inst.currentDay;
			inst.drawMonth = inst.selectedMonth = inst.currentMonth;
			inst.drawYear = inst.selectedYear = inst.currentYear;
		} else {
			date = new Date();
			inst.selectedDay = date.getDate();
			inst.drawMonth = inst.selectedMonth = date.getMonth();
			inst.drawYear = inst.selectedYear = date.getFullYear();
		}
		this._notifyChange(inst);
		this._adjustDate(target);
	},

	/* Action for selecting a new month/year. */
	_selectMonthYear: function(id, select, period) {
		var target = $(id),
			inst = this._getInst(target[0]);

		inst["selected" + (period === "M" ? "Month" : "Year")] =
		inst["draw" + (period === "M" ? "Month" : "Year")] =
			parseInt(select.options[select.selectedIndex].value,10);

		this._notifyChange(inst);
		this._adjustDate(target);
	},

	/* Action for selecting a day. */
	_selectDay: function(id, month, year, td) {
		var inst,
			target = $(id);

		if ($(td).hasClass(this._unselectableClass) || this._isDisabledDatepicker(target[0])) {
			return;
		}

		inst = this._getInst(target[0]);
		inst.selectedDay = inst.currentDay = $("a", td).html();
		inst.selectedMonth = inst.currentMonth = month;
		inst.selectedYear = inst.currentYear = year;
		this._selectDate(id, this._formatDate(inst,
			inst.currentDay, inst.currentMonth, inst.currentYear));
	},

	/* Erase the input field and hide the date picker. */
	_clearDate: function(id) {
		var target = $(id);
		this._selectDate(target, "");
	},

	/* Update the input field with the selected date. */
	_selectDate: function(id, dateStr) {
		var onSelect,
			target = $(id),
			inst = this._getInst(target[0]);

		dateStr = (dateStr != null ? dateStr : this._formatDate(inst));
		if (inst.input) {
			inst.input.val(dateStr);
		}
		this._updateAlternate(inst);

		onSelect = this._get(inst, "onSelect");
		if (onSelect) {
			onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);  // trigger custom callback
		} else if (inst.input) {
			inst.input.trigger("change"); // fire the change event
		}

		if (inst.inline){
			this._updateDatepicker(inst);
		} else {
			this._hideDatepicker();
			this._lastInput = inst.input[0];
			if (typeof(inst.input[0]) !== "object") {
				inst.input.focus(); // restore focus
			}
			this._lastInput = null;
		}
	},

	/* Update any alternate field to synchronise with the main field. */
	_updateAlternate: function(inst) {
		var altFormat, date, dateStr,
			altField = this._get(inst, "altField");

		if (altField) { // update alternate field too
			altFormat = this._get(inst, "altFormat") || this._get(inst, "dateFormat");
			date = this._getDate(inst);
			dateStr = this.formatDate(altFormat, date, this._getFormatConfig(inst));
			$(altField).each(function() { $(this).val(dateStr); });
		}
	},

	/* Set as beforeShowDay function to prevent selection of weekends.
	 * @param  date  Date - the date to customise
	 * @return [boolean, string] - is this date selectable?, what is its CSS class?
	 */
	noWeekends: function(date) {
		var day = date.getDay();
		return [(day > 0 && day < 6), ""];
	},

	/* Set as calculateWeek to determine the week of the year based on the ISO 8601 definition.
	 * @param  date  Date - the date to get the week for
	 * @return  number - the number of the week within the year that contains this date
	 */
	iso8601Week: function(date) {
		var time,
			checkDate = new Date(date.getTime());

		// Find Thursday of this week starting on Monday
		checkDate.setDate(checkDate.getDate() + 4 - (checkDate.getDay() || 7));

		time = checkDate.getTime();
		checkDate.setMonth(0); // Compare with Jan 1
		checkDate.setDate(1);
		return Math.floor(Math.round((time - checkDate) / 86400000) / 7) + 1;
	},

	/* Parse a string value into a date object.
	 * See formatDate below for the possible formats.
	 *
	 * @param  format string - the expected format of the date
	 * @param  value string - the date in the above format
	 * @param  settings Object - attributes include:
	 *					shortYearCutoff  number - the cutoff year for determining the century (optional)
	 *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
	 *					dayNames		string[7] - names of the days from Sunday (optional)
	 *					monthNamesShort string[12] - abbreviated names of the months (optional)
	 *					monthNames		string[12] - names of the months (optional)
	 * @return  Date - the extracted date value or null if value is blank
	 */
	parseDate: function (format, value, settings) {
		if (format == null || value == null) {
			throw "Invalid arguments";
		}

		value = (typeof value === "object" ? value.toString() : value + "");
		if (value === "") {
			return null;
		}

		var iFormat, dim, extra,
			iValue = 0,
			shortYearCutoffTemp = (settings ? settings.shortYearCutoff : null) || this._defaults.shortYearCutoff,
			shortYearCutoff = (typeof shortYearCutoffTemp !== "string" ? shortYearCutoffTemp :
				new Date().getFullYear() % 100 + parseInt(shortYearCutoffTemp, 10)),
			dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
			dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
			monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
			monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
			year = -1,
			month = -1,
			day = -1,
			doy = -1,
			literal = false,
			date,
			// Check whether a format character is doubled
			lookAhead = function(match) {
				var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
				if (matches) {
					iFormat++;
				}
				return matches;
			},
			// Extract a number from the string value
			getNumber = function(match) {
				var isDoubled = lookAhead(match),
					size = (match === "@" ? 14 : (match === "!" ? 20 :
					(match === "y" && isDoubled ? 4 : (match === "o" ? 3 : 2)))),
					minSize = (match === "y" ? size : 1),
					digits = new RegExp("^\\d{" + minSize + "," + size + "}"),
					num = value.substring(iValue).match(digits);
				if (!num) {
					throw "Missing number at position " + iValue;
				}
				iValue += num[0].length;
				return parseInt(num[0], 10);
			},
			// Extract a name from the string value and convert to an index
			getName = function(match, shortNames, longNames) {
				var index = -1,
					names = $.map(lookAhead(match) ? longNames : shortNames, function (v, k) {
						return [ [k, v] ];
					}).sort(function (a, b) {
						return -(a[1].length - b[1].length);
					});

				$.each(names, function (i, pair) {
					var name = pair[1];
					if (value.substr(iValue, name.length).toLowerCase() === name.toLowerCase()) {
						index = pair[0];
						iValue += name.length;
						return false;
					}
				});
				if (index !== -1) {
					return index + 1;
				} else {
					throw "Unknown name at position " + iValue;
				}
			},
			// Confirm that a literal character matches the string value
			checkLiteral = function() {
				if (value.charAt(iValue) !== format.charAt(iFormat)) {
					throw "Unexpected literal at position " + iValue;
				}
				iValue++;
			};

		for (iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
					literal = false;
				} else {
					checkLiteral();
				}
			} else {
				switch (format.charAt(iFormat)) {
					case "d":
						day = getNumber("d");
						break;
					case "D":
						getName("D", dayNamesShort, dayNames);
						break;
					case "o":
						doy = getNumber("o");
						break;
					case "m":
						month = getNumber("m");
						break;
					case "M":
						month = getName("M", monthNamesShort, monthNames);
						break;
					case "y":
						year = getNumber("y");
						break;
					case "@":
						date = new Date(getNumber("@"));
						year = date.getFullYear();
						month = date.getMonth() + 1;
						day = date.getDate();
						break;
					case "!":
						date = new Date((getNumber("!") - this._ticksTo1970) / 10000);
						year = date.getFullYear();
						month = date.getMonth() + 1;
						day = date.getDate();
						break;
					case "'":
						if (lookAhead("'")){
							checkLiteral();
						} else {
							literal = true;
						}
						break;
					default:
						checkLiteral();
				}
			}
		}

		if (iValue < value.length){
			extra = value.substr(iValue);
			if (!/^\s+/.test(extra)) {
				throw "Extra/unparsed characters found in date: " + extra;
			}
		}

		if (year === -1) {
			year = new Date().getFullYear();
		} else if (year < 100) {
			year += new Date().getFullYear() - new Date().getFullYear() % 100 +
				(year <= shortYearCutoff ? 0 : -100);
		}

		if (doy > -1) {
			month = 1;
			day = doy;
			do {
				dim = this._getDaysInMonth(year, month - 1);
				if (day <= dim) {
					break;
				}
				month++;
				day -= dim;
			} while (true);
		}

		date = this._daylightSavingAdjust(new Date(year, month - 1, day));
		if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
			throw "Invalid date"; // E.g. 31/02/00
		}
		return date;
	},

	/* Standard date formats. */
	ATOM: "yy-mm-dd", // RFC 3339 (ISO 8601)
	COOKIE: "D, dd M yy",
	ISO_8601: "yy-mm-dd",
	RFC_822: "D, d M y",
	RFC_850: "DD, dd-M-y",
	RFC_1036: "D, d M y",
	RFC_1123: "D, d M yy",
	RFC_2822: "D, d M yy",
	RSS: "D, d M y", // RFC 822
	TICKS: "!",
	TIMESTAMP: "@",
	W3C: "yy-mm-dd", // ISO 8601

	_ticksTo1970: (((1970 - 1) * 365 + Math.floor(1970 / 4) - Math.floor(1970 / 100) +
		Math.floor(1970 / 400)) * 24 * 60 * 60 * 10000000),

	/* Format a date object into a string value.
	 * The format can be combinations of the following:
	 * d  - day of month (no leading zero)
	 * dd - day of month (two digit)
	 * o  - day of year (no leading zeros)
	 * oo - day of year (three digit)
	 * D  - day name short
	 * DD - day name long
	 * m  - month of year (no leading zero)
	 * mm - month of year (two digit)
	 * M  - month name short
	 * MM - month name long
	 * y  - year (two digit)
	 * yy - year (four digit)
	 * @ - Unix timestamp (ms since 01/01/1970)
	 * ! - Windows ticks (100ns since 01/01/0001)
	 * "..." - literal text
	 * '' - single quote
	 *
	 * @param  format string - the desired format of the date
	 * @param  date Date - the date value to format
	 * @param  settings Object - attributes include:
	 *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
	 *					dayNames		string[7] - names of the days from Sunday (optional)
	 *					monthNamesShort string[12] - abbreviated names of the months (optional)
	 *					monthNames		string[12] - names of the months (optional)
	 * @return  string - the date in the above format
	 */
	formatDate: function (format, date, settings) {
		if (!date) {
			return "";
		}

		var iFormat,
			dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
			dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
			monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
			monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
			// Check whether a format character is doubled
			lookAhead = function(match) {
				var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
				if (matches) {
					iFormat++;
				}
				return matches;
			},
			// Format a number, with leading zero if necessary
			formatNumber = function(match, value, len) {
				var num = "" + value;
				if (lookAhead(match)) {
					while (num.length < len) {
						num = "0" + num;
					}
				}
				return num;
			},
			// Format a name, short or long as requested
			formatName = function(match, value, shortNames, longNames) {
				return (lookAhead(match) ? longNames[value] : shortNames[value]);
			},
			output = "",
			literal = false;

		if (date) {
			for (iFormat = 0; iFormat < format.length; iFormat++) {
				if (literal) {
					if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
						literal = false;
					} else {
						output += format.charAt(iFormat);
					}
				} else {
					switch (format.charAt(iFormat)) {
						case "d":
							output += formatNumber("d", date.getDate(), 2);
							break;
						case "D":
							output += formatName("D", date.getDay(), dayNamesShort, dayNames);
							break;
						case "o":
							output += formatNumber("o",
								Math.round((new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime() - new Date(date.getFullYear(), 0, 0).getTime()) / 86400000), 3);
							break;
						case "m":
							output += formatNumber("m", date.getMonth() + 1, 2);
							break;
						case "M":
							output += formatName("M", date.getMonth(), monthNamesShort, monthNames);
							break;
						case "y":
							output += (lookAhead("y") ? date.getFullYear() :
								(date.getYear() % 100 < 10 ? "0" : "") + date.getYear() % 100);
							break;
						case "@":
							output += date.getTime();
							break;
						case "!":
							output += date.getTime() * 10000 + this._ticksTo1970;
							break;
						case "'":
							if (lookAhead("'")) {
								output += "'";
							} else {
								literal = true;
							}
							break;
						default:
							output += format.charAt(iFormat);
					}
				}
			}
		}
		return output;
	},

	/* Extract all possible characters from the date format. */
	_possibleChars: function (format) {
		var iFormat,
			chars = "",
			literal = false,
			// Check whether a format character is doubled
			lookAhead = function(match) {
				var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) === match);
				if (matches) {
					iFormat++;
				}
				return matches;
			};

		for (iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
					literal = false;
				} else {
					chars += format.charAt(iFormat);
				}
			} else {
				switch (format.charAt(iFormat)) {
					case "d": case "m": case "y": case "@":
						chars += "0123456789";
						break;
					case "D": case "M":
						return null; // Accept anything
					case "'":
						if (lookAhead("'")) {
							chars += "'";
						} else {
							literal = true;
						}
						break;
					default:
						chars += format.charAt(iFormat);
				}
			}
		}
		return chars;
	},

	/* Get a setting value, defaulting if necessary. */
	_get: function(inst, name) {
		return inst.settings[name] !== undefined ?
			inst.settings[name] : this._defaults[name];
	},

	/* Parse existing date and initialise date picker. */
	_setDateFromField: function(inst, noDefault) {
		if (inst.input.val() === inst.lastVal) {
			return;
		}

		var dateFormat = this._get(inst, "dateFormat"),
			dates = inst.lastVal = inst.input ? inst.input.val() : null,
			defaultDate = this._getDefaultDate(inst),
			date = defaultDate,
			settings = this._getFormatConfig(inst);

		try {
			date = this.parseDate(dateFormat, dates, settings) || defaultDate;
		} catch (event) {
			dates = (noDefault ? "" : dates);
		}
		inst.selectedDay = date.getDate();
		inst.drawMonth = inst.selectedMonth = date.getMonth();
		inst.drawYear = inst.selectedYear = date.getFullYear();
		inst.currentDay = (dates ? date.getDate() : 0);
		inst.currentMonth = (dates ? date.getMonth() : 0);
		inst.currentYear = (dates ? date.getFullYear() : 0);
		this._adjustInstDate(inst);
	},

	/* Retrieve the default date shown on opening. */
	_getDefaultDate: function(inst) {
		return this._restrictMinMax(inst,
			this._determineDate(inst, this._get(inst, "defaultDate"), new Date()));
	},

	/* A date may be specified as an exact value or a relative one. */
	_determineDate: function(inst, date, defaultDate) {
		var offsetNumeric = function(offset) {
				var date = new Date();
				date.setDate(date.getDate() + offset);
				return date;
			},
			offsetString = function(offset) {
				try {
					return $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"),
						offset, $.datepicker._getFormatConfig(inst));
				}
				catch (e) {
					// Ignore
				}

				var date = (offset.toLowerCase().match(/^c/) ?
					$.datepicker._getDate(inst) : null) || new Date(),
					year = date.getFullYear(),
					month = date.getMonth(),
					day = date.getDate(),
					pattern = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,
					matches = pattern.exec(offset);

				while (matches) {
					switch (matches[2] || "d") {
						case "d" : case "D" :
							day += parseInt(matches[1],10); break;
						case "w" : case "W" :
							day += parseInt(matches[1],10) * 7; break;
						case "m" : case "M" :
							month += parseInt(matches[1],10);
							day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
							break;
						case "y": case "Y" :
							year += parseInt(matches[1],10);
							day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
							break;
					}
					matches = pattern.exec(offset);
				}
				return new Date(year, month, day);
			},
			newDate = (date == null || date === "" ? defaultDate : (typeof date === "string" ? offsetString(date) :
				(typeof date === "number" ? (isNaN(date) ? defaultDate : offsetNumeric(date)) : new Date(date.getTime()))));

		newDate = (newDate && newDate.toString() === "Invalid Date" ? defaultDate : newDate);
		if (newDate) {
			newDate.setHours(0);
			newDate.setMinutes(0);
			newDate.setSeconds(0);
			newDate.setMilliseconds(0);
		}
		return this._daylightSavingAdjust(newDate);
	},

	/* Handle switch to/from daylight saving.
	 * Hours may be non-zero on daylight saving cut-over:
	 * > 12 when midnight changeover, but then cannot generate
	 * midnight datetime, so jump to 1AM, otherwise reset.
	 * @param  date  (Date) the date to check
	 * @return  (Date) the corrected date
	 */
	_daylightSavingAdjust: function(date) {
		if (!date) {
			return null;
		}
		date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
		return date;
	},

	/* Set the date(s) directly. */
	_setDate: function(inst, date, noChange) {
		var clear = !date,
			origMonth = inst.selectedMonth,
			origYear = inst.selectedYear,
			newDate = this._restrictMinMax(inst, this._determineDate(inst, date, new Date()));

		inst.selectedDay = inst.currentDay = newDate.getDate();
		inst.drawMonth = inst.selectedMonth = inst.currentMonth = newDate.getMonth();
		inst.drawYear = inst.selectedYear = inst.currentYear = newDate.getFullYear();
		if ((origMonth !== inst.selectedMonth || origYear !== inst.selectedYear) && !noChange) {
			this._notifyChange(inst);
		}
		this._adjustInstDate(inst);
		if (inst.input) {
			inst.input.val(clear ? "" : this._formatDate(inst));
		}
	},

	/* Retrieve the date(s) directly. */
	_getDate: function(inst) {
		var startDate = (!inst.currentYear || (inst.input && inst.input.val() === "") ? null :
			this._daylightSavingAdjust(new Date(
			inst.currentYear, inst.currentMonth, inst.currentDay)));
			return startDate;
	},

	/* Attach the onxxx handlers.  These are declared statically so
	 * they work with static code transformers like Caja.
	 */
	_attachHandlers: function(inst) {
		var stepMonths = this._get(inst, "stepMonths"),
			id = "#" + inst.id.replace( /\\\\/g, "\\" );
		inst.dpDiv.find("[data-handler]").map(function () {
			var handler = {
				prev: function () {
					$.datepicker._adjustDate(id, -stepMonths, "M");
				},
				next: function () {
					$.datepicker._adjustDate(id, +stepMonths, "M");
				},
				hide: function () {
					$.datepicker._hideDatepicker();
				},
				today: function () {
					$.datepicker._gotoToday(id);
				},
				selectDay: function () {
					$.datepicker._selectDay(id, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this);
					return false;
				},
				selectMonth: function () {
					$.datepicker._selectMonthYear(id, this, "M");
					return false;
				},
				selectYear: function () {
					$.datepicker._selectMonthYear(id, this, "Y");
					return false;
				}
			};
			$(this).bind(this.getAttribute("data-event"), handler[this.getAttribute("data-handler")]);
		});
	},

	/* Generate the HTML for the current state of the date picker. */
	_generateHTML: function(inst) {
		var maxDraw, prevText, prev, nextText, next, currentText, gotoDate,
			controls, buttonPanel, firstDay, showWeek, dayNames, dayNamesMin,
			monthNames, monthNamesShort, beforeShowDay, showOtherMonths,
			selectOtherMonths, defaultDate, html, dow, row, group, col, selectedDate,
			cornerClass, calender, thead, day, daysInMonth, leadDays, curRows, numRows,
			printDate, dRow, tbody, daySettings, otherMonth, unselectable,
			tempDate = new Date(),
			today = this._daylightSavingAdjust(
				new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate())), // clear time
			isRTL = this._get(inst, "isRTL"),
			showButtonPanel = this._get(inst, "showButtonPanel"),
			hideIfNoPrevNext = this._get(inst, "hideIfNoPrevNext"),
			navigationAsDateFormat = this._get(inst, "navigationAsDateFormat"),
			numMonths = this._getNumberOfMonths(inst),
			showCurrentAtPos = this._get(inst, "showCurrentAtPos"),
			stepMonths = this._get(inst, "stepMonths"),
			isMultiMonth = (numMonths[0] !== 1 || numMonths[1] !== 1),
			currentDate = this._daylightSavingAdjust((!inst.currentDay ? new Date(9999, 9, 9) :
				new Date(inst.currentYear, inst.currentMonth, inst.currentDay))),
			minDate = this._getMinMaxDate(inst, "min"),
			maxDate = this._getMinMaxDate(inst, "max"),
			drawMonth = inst.drawMonth - showCurrentAtPos,
			drawYear = inst.drawYear;

		if (drawMonth < 0) {
			drawMonth += 12;
			drawYear--;
		}
		if (maxDate) {
			maxDraw = this._daylightSavingAdjust(new Date(maxDate.getFullYear(),
				maxDate.getMonth() - (numMonths[0] * numMonths[1]) + 1, maxDate.getDate()));
			maxDraw = (minDate && maxDraw < minDate ? minDate : maxDraw);
			while (this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1)) > maxDraw) {
				drawMonth--;
				if (drawMonth < 0) {
					drawMonth = 11;
					drawYear--;
				}
			}
		}
		inst.drawMonth = drawMonth;
		inst.drawYear = drawYear;

		prevText = this._get(inst, "prevText");
		prevText = (!navigationAsDateFormat ? prevText : this.formatDate(prevText,
			this._daylightSavingAdjust(new Date(drawYear, drawMonth - stepMonths, 1)),
			this._getFormatConfig(inst)));

		prev = (this._canAdjustMonth(inst, -1, drawYear, drawMonth) ?
			"<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click'" +
			" title='" + prevText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "e" : "w") + "'>" + prevText + "</span></a>" :
			(hideIfNoPrevNext ? "" : "<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='"+ prevText +"'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "e" : "w") + "'>" + prevText + "</span></a>"));

		nextText = this._get(inst, "nextText");
		nextText = (!navigationAsDateFormat ? nextText : this.formatDate(nextText,
			this._daylightSavingAdjust(new Date(drawYear, drawMonth + stepMonths, 1)),
			this._getFormatConfig(inst)));

		next = (this._canAdjustMonth(inst, +1, drawYear, drawMonth) ?
			"<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click'" +
			" title='" + nextText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "w" : "e") + "'>" + nextText + "</span></a>" :
			(hideIfNoPrevNext ? "" : "<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='"+ nextText + "'><span class='ui-icon ui-icon-circle-triangle-" + ( isRTL ? "w" : "e") + "'>" + nextText + "</span></a>"));

		currentText = this._get(inst, "currentText");
		gotoDate = (this._get(inst, "gotoCurrent") && inst.currentDay ? currentDate : today);
		currentText = (!navigationAsDateFormat ? currentText :
			this.formatDate(currentText, gotoDate, this._getFormatConfig(inst)));

		controls = (!inst.inline ? "<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>" +
			this._get(inst, "closeText") + "</button>" : "");

		buttonPanel = (showButtonPanel) ? "<div class='ui-datepicker-buttonpane ui-widget-content'>" + (isRTL ? controls : "") +
			(this._isInRange(inst, gotoDate) ? "<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'" +
			">" + currentText + "</button>" : "") + (isRTL ? "" : controls) + "</div>" : "";

		firstDay = parseInt(this._get(inst, "firstDay"),10);
		firstDay = (isNaN(firstDay) ? 0 : firstDay);

		showWeek = this._get(inst, "showWeek");
		dayNames = this._get(inst, "dayNames");
		dayNamesMin = this._get(inst, "dayNamesMin");
		monthNames = this._get(inst, "monthNames");
		monthNamesShort = this._get(inst, "monthNamesShort");
		beforeShowDay = this._get(inst, "beforeShowDay");
		showOtherMonths = this._get(inst, "showOtherMonths");
		selectOtherMonths = this._get(inst, "selectOtherMonths");
		defaultDate = this._getDefaultDate(inst);
		html = "";
		dow;
		for (row = 0; row < numMonths[0]; row++) {
			group = "";
			this.maxRows = 4;
			for (col = 0; col < numMonths[1]; col++) {
				selectedDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, inst.selectedDay));
				cornerClass = " ui-corner-all";
				calender = "";
				if (isMultiMonth) {
					calender += "<div class='ui-datepicker-group";
					if (numMonths[1] > 1) {
						switch (col) {
							case 0: calender += " ui-datepicker-group-first";
								cornerClass = " ui-corner-" + (isRTL ? "right" : "left"); break;
							case numMonths[1]-1: calender += " ui-datepicker-group-last";
								cornerClass = " ui-corner-" + (isRTL ? "left" : "right"); break;
							default: calender += " ui-datepicker-group-middle"; cornerClass = ""; break;
						}
					}
					calender += "'>";
				}
				calender += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + cornerClass + "'>" +
					(/all|left/.test(cornerClass) && row === 0 ? (isRTL ? next : prev) : "") +
					(/all|right/.test(cornerClass) && row === 0 ? (isRTL ? prev : next) : "") +
					this._generateMonthYearHeader(inst, drawMonth, drawYear, minDate, maxDate,
					row > 0 || col > 0, monthNames, monthNamesShort) + // draw month headers
					"</div><table class='ui-datepicker-calendar'><thead>" +
					"<tr>";
				thead = (showWeek ? "<th class='ui-datepicker-week-col'>" + this._get(inst, "weekHeader") + "</th>" : "");
				for (dow = 0; dow < 7; dow++) { // days of the week
					day = (dow + firstDay) % 7;
					thead += "<th scope='col'" + ((dow + firstDay + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'" : "") + ">" +
						"<span title='" + dayNames[day] + "'>" + dayNamesMin[day] + "</span></th>";
				}
				calender += thead + "</tr></thead><tbody>";
				daysInMonth = this._getDaysInMonth(drawYear, drawMonth);
				if (drawYear === inst.selectedYear && drawMonth === inst.selectedMonth) {
					inst.selectedDay = Math.min(inst.selectedDay, daysInMonth);
				}
				leadDays = (this._getFirstDayOfMonth(drawYear, drawMonth) - firstDay + 7) % 7;
				curRows = Math.ceil((leadDays + daysInMonth) / 7); // calculate the number of rows to generate
				numRows = (isMultiMonth ? this.maxRows > curRows ? this.maxRows : curRows : curRows); //If multiple months, use the higher number of rows (see #7043)
				this.maxRows = numRows;
				printDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1 - leadDays));
				for (dRow = 0; dRow < numRows; dRow++) { // create date picker rows
					calender += "<tr>";
					tbody = (!showWeek ? "" : "<td class='ui-datepicker-week-col'>" +
						this._get(inst, "calculateWeek")(printDate) + "</td>");
					for (dow = 0; dow < 7; dow++) { // create date picker days
						daySettings = (beforeShowDay ?
							beforeShowDay.apply((inst.input ? inst.input[0] : null), [printDate]) : [true, ""]);
						otherMonth = (printDate.getMonth() !== drawMonth);
						unselectable = (otherMonth && !selectOtherMonths) || !daySettings[0] ||
							(minDate && printDate < minDate) || (maxDate && printDate > maxDate);
						tbody += "<td class='" +
							((dow + firstDay + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + // highlight weekends
							(otherMonth ? " ui-datepicker-other-month" : "") + // highlight days from other months
							((printDate.getTime() === selectedDate.getTime() && drawMonth === inst.selectedMonth && inst._keyEvent) || // user pressed key
							(defaultDate.getTime() === printDate.getTime() && defaultDate.getTime() === selectedDate.getTime()) ?
							// or defaultDate is current printedDate and defaultDate is selectedDate
							" " + this._dayOverClass : "") + // highlight selected day
							(unselectable ? " " + this._unselectableClass + " ui-state-disabled": "") +  // highlight unselectable days
							(otherMonth && !showOtherMonths ? "" : " " + daySettings[1] + // highlight custom dates
							(printDate.getTime() === currentDate.getTime() ? " " + this._currentClass : "") + // highlight selected day
							(printDate.getTime() === today.getTime() ? " ui-datepicker-today" : "")) + "'" + // highlight today (if different)
							((!otherMonth || showOtherMonths) && daySettings[2] ? " title='" + daySettings[2].replace(/'/g, "&#39;") + "'" : "") + // cell title
							(unselectable ? "" : " data-handler='selectDay' data-event='click' data-month='" + printDate.getMonth() + "' data-year='" + printDate.getFullYear() + "'") + ">" + // actions
							(otherMonth && !showOtherMonths ? "&#xa0;" : // display for other months
							(unselectable ? "<span class='ui-state-default'>" + printDate.getDate() + "</span>" : "<a class='ui-state-default" +
							(printDate.getTime() === today.getTime() ? " ui-state-highlight" : "") +
							(printDate.getTime() === currentDate.getTime() ? " ui-state-active" : "") + // highlight selected day
							(otherMonth ? " ui-priority-secondary" : "") + // distinguish dates from other months
							"' href='#'>" + printDate.getDate() + "</a>")) + "</td>"; // display selectable date
						printDate.setDate(printDate.getDate() + 1);
						printDate = this._daylightSavingAdjust(printDate);
					}
					calender += tbody + "</tr>";
				}
				drawMonth++;
				if (drawMonth > 11) {
					drawMonth = 0;
					drawYear++;
				}
				calender += "</tbody></table>" + (isMultiMonth ? "</div>" +
							((numMonths[0] > 0 && col === numMonths[1]-1) ? "<div class='ui-datepicker-row-break'></div>" : "") : "");
				group += calender;
			}
			html += group;
		}
		html += buttonPanel;
		inst._keyEvent = false;
		return html;
	},

	/* Generate the month and year header. */
	_generateMonthYearHeader: function(inst, drawMonth, drawYear, minDate, maxDate,
			secondary, monthNames, monthNamesShort) {

		var inMinYear, inMaxYear, month, years, thisYear, determineYear, year, endYear,
			changeMonth = this._get(inst, "changeMonth"),
			changeYear = this._get(inst, "changeYear"),
			showMonthAfterYear = this._get(inst, "showMonthAfterYear"),
			html = "<div class='ui-datepicker-title'>",
			monthHtml = "";

		// month selection
		if (secondary || !changeMonth) {
			monthHtml += "<span class='ui-datepicker-month'>" + monthNames[drawMonth] + "</span>";
		} else {
			inMinYear = (minDate && minDate.getFullYear() === drawYear);
			inMaxYear = (maxDate && maxDate.getFullYear() === drawYear);
			monthHtml += "<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>";
			for ( month = 0; month < 12; month++) {
				if ((!inMinYear || month >= minDate.getMonth()) && (!inMaxYear || month <= maxDate.getMonth())) {
					monthHtml += "<option value='" + month + "'" +
						(month === drawMonth ? " selected='selected'" : "") +
						">" + monthNamesShort[month] + "</option>";
				}
			}
			monthHtml += "</select>";
		}

		if (!showMonthAfterYear) {
			html += monthHtml + (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "");
		}

		// year selection
		if ( !inst.yearshtml ) {
			inst.yearshtml = "";
			if (secondary || !changeYear) {
				html += "<span class='ui-datepicker-year'>" + drawYear + "</span>";
			} else {
				// determine range of years to display
				years = this._get(inst, "yearRange").split(":");
				thisYear = new Date().getFullYear();
				determineYear = function(value) {
					var year = (value.match(/c[+\-].*/) ? drawYear + parseInt(value.substring(1), 10) :
						(value.match(/[+\-].*/) ? thisYear + parseInt(value, 10) :
						parseInt(value, 10)));
					return (isNaN(year) ? thisYear : year);
				};
				year = determineYear(years[0]);
				endYear = Math.max(year, determineYear(years[1] || ""));
				year = (minDate ? Math.max(year, minDate.getFullYear()) : year);
				endYear = (maxDate ? Math.min(endYear, maxDate.getFullYear()) : endYear);
				inst.yearshtml += "<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";
				for (; year <= endYear; year++) {
					inst.yearshtml += "<option value='" + year + "'" +
						(year === drawYear ? " selected='selected'" : "") +
						">" + year + "</option>";
				}
				inst.yearshtml += "</select>";

				html += inst.yearshtml;
				inst.yearshtml = null;
			}
		}

		html += this._get(inst, "yearSuffix");
		if (showMonthAfterYear) {
			html += (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "") + monthHtml;
		}
		html += "</div>"; // Close datepicker_header
		return html;
	},

	/* Adjust one of the date sub-fields. */
	_adjustInstDate: function(inst, offset, period) {
		var year = inst.drawYear + (period === "Y" ? offset : 0),
			month = inst.drawMonth + (period === "M" ? offset : 0),
			day = Math.min(inst.selectedDay, this._getDaysInMonth(year, month)) + (period === "D" ? offset : 0),
			date = this._restrictMinMax(inst, this._daylightSavingAdjust(new Date(year, month, day)));

		inst.selectedDay = date.getDate();
		inst.drawMonth = inst.selectedMonth = date.getMonth();
		inst.drawYear = inst.selectedYear = date.getFullYear();
		if (period === "M" || period === "Y") {
			this._notifyChange(inst);
		}
	},

	/* Ensure a date is within any min/max bounds. */
	_restrictMinMax: function(inst, date) {
		var minDate = this._getMinMaxDate(inst, "min"),
			maxDate = this._getMinMaxDate(inst, "max"),
			newDate = (minDate && date < minDate ? minDate : date);
		return (maxDate && newDate > maxDate ? maxDate : newDate);
	},

	/* Notify change of month/year. */
	_notifyChange: function(inst) {
		var onChange = this._get(inst, "onChangeMonthYear");
		if (onChange) {
			onChange.apply((inst.input ? inst.input[0] : null),
				[inst.selectedYear, inst.selectedMonth + 1, inst]);
		}
	},

	/* Determine the number of months to show. */
	_getNumberOfMonths: function(inst) {
		var numMonths = this._get(inst, "numberOfMonths");
		return (numMonths == null ? [1, 1] : (typeof numMonths === "number" ? [1, numMonths] : numMonths));
	},

	/* Determine the current maximum date - ensure no time components are set. */
	_getMinMaxDate: function(inst, minMax) {
		return this._determineDate(inst, this._get(inst, minMax + "Date"), null);
	},

	/* Find the number of days in a given month. */
	_getDaysInMonth: function(year, month) {
		return 32 - this._daylightSavingAdjust(new Date(year, month, 32)).getDate();
	},

	/* Find the day of the week of the first of a month. */
	_getFirstDayOfMonth: function(year, month) {
		return new Date(year, month, 1).getDay();
	},

	/* Determines if we should allow a "next/prev" month display change. */
	_canAdjustMonth: function(inst, offset, curYear, curMonth) {
		var numMonths = this._getNumberOfMonths(inst),
			date = this._daylightSavingAdjust(new Date(curYear,
			curMonth + (offset < 0 ? offset : numMonths[0] * numMonths[1]), 1));

		if (offset < 0) {
			date.setDate(this._getDaysInMonth(date.getFullYear(), date.getMonth()));
		}
		return this._isInRange(inst, date);
	},

	/* Is the given date in the accepted range? */
	_isInRange: function(inst, date) {
		var yearSplit, currentYear,
			minDate = this._getMinMaxDate(inst, "min"),
			maxDate = this._getMinMaxDate(inst, "max"),
			minYear = null,
			maxYear = null,
			years = this._get(inst, "yearRange");
			if (years){
				yearSplit = years.split(":");
				currentYear = new Date().getFullYear();
				minYear = parseInt(yearSplit[0], 10);
				maxYear = parseInt(yearSplit[1], 10);
				if ( yearSplit[0].match(/[+\-].*/) ) {
					minYear += currentYear;
				}
				if ( yearSplit[1].match(/[+\-].*/) ) {
					maxYear += currentYear;
				}
			}

		return ((!minDate || date.getTime() >= minDate.getTime()) &&
			(!maxDate || date.getTime() <= maxDate.getTime()) &&
			(!minYear || date.getFullYear() >= minYear) &&
			(!maxYear || date.getFullYear() <= maxYear));
	},

	/* Provide the configuration settings for formatting/parsing. */
	_getFormatConfig: function(inst) {
		var shortYearCutoff = this._get(inst, "shortYearCutoff");
		shortYearCutoff = (typeof shortYearCutoff !== "string" ? shortYearCutoff :
			new Date().getFullYear() % 100 + parseInt(shortYearCutoff, 10));
		return {shortYearCutoff: shortYearCutoff,
			dayNamesShort: this._get(inst, "dayNamesShort"), dayNames: this._get(inst, "dayNames"),
			monthNamesShort: this._get(inst, "monthNamesShort"), monthNames: this._get(inst, "monthNames")};
	},

	/* Format the given date for display. */
	_formatDate: function(inst, day, month, year) {
		if (!day) {
			inst.currentDay = inst.selectedDay;
			inst.currentMonth = inst.selectedMonth;
			inst.currentYear = inst.selectedYear;
		}
		var date = (day ? (typeof day === "object" ? day :
			this._daylightSavingAdjust(new Date(year, month, day))) :
			this._daylightSavingAdjust(new Date(inst.currentYear, inst.currentMonth, inst.currentDay)));
		return this.formatDate(this._get(inst, "dateFormat"), date, this._getFormatConfig(inst));
	}
});

/*
 * Bind hover events for datepicker elements.
 * Done via delegate so the binding only occurs once in the lifetime of the parent div.
 * Global datepicker_instActive, set by _updateDatepicker allows the handlers to find their way back to the active picker.
 */
function datepicker_bindHover(dpDiv) {
	var selector = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
	return dpDiv.delegate(selector, "mouseout", function() {
			$(this).removeClass("ui-state-hover");
			if (this.className.indexOf("ui-datepicker-prev") !== -1) {
				$(this).removeClass("ui-datepicker-prev-hover");
			}
			if (this.className.indexOf("ui-datepicker-next") !== -1) {
				$(this).removeClass("ui-datepicker-next-hover");
			}
		})
		.delegate( selector, "mouseover", datepicker_handleMouseover );
}

function datepicker_handleMouseover() {
	if (!$.datepicker._isDisabledDatepicker( datepicker_instActive.inline? datepicker_instActive.dpDiv.parent()[0] : datepicker_instActive.input[0])) {
		$(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover");
		$(this).addClass("ui-state-hover");
		if (this.className.indexOf("ui-datepicker-prev") !== -1) {
			$(this).addClass("ui-datepicker-prev-hover");
		}
		if (this.className.indexOf("ui-datepicker-next") !== -1) {
			$(this).addClass("ui-datepicker-next-hover");
		}
	}
}

/* jQuery extend now ignores nulls! */
function datepicker_extendRemove(target, props) {
	$.extend(target, props);
	for (var name in props) {
		if (props[name] == null) {
			target[name] = props[name];
		}
	}
	return target;
}

/* Invoke the datepicker functionality.
   @param  options  string - a command, optionally followed by additional parameters or
					Object - settings for attaching new datepicker functionality
   @return  jQuery object */
$.fn.datepicker = function(options){

	/* Verify an empty collection wasn't passed - Fixes #6976 */
	if ( !this.length ) {
		return this;
	}

	/* Initialise the date picker. */
	if (!$.datepicker.initialized) {
		$(document).mousedown($.datepicker._checkExternalClick);
		$.datepicker.initialized = true;
	}

	/* Append datepicker main container to body if not exist. */
	if ($("#"+$.datepicker._mainDivId).length === 0) {
		$("body").append($.datepicker.dpDiv);
	}

	var otherArgs = Array.prototype.slice.call(arguments, 1);
	if (typeof options === "string" && (options === "isDisabled" || options === "getDate" || options === "widget")) {
		return $.datepicker["_" + options + "Datepicker"].
			apply($.datepicker, [this[0]].concat(otherArgs));
	}
	if (options === "option" && arguments.length === 2 && typeof arguments[1] === "string") {
		return $.datepicker["_" + options + "Datepicker"].
			apply($.datepicker, [this[0]].concat(otherArgs));
	}
	return this.each(function() {
		typeof options === "string" ?
			$.datepicker["_" + options + "Datepicker"].
				apply($.datepicker, [this].concat(otherArgs)) :
			$.datepicker._attachDatepicker(this, options);
	});
};

$.datepicker = new Datepicker(); // singleton instance
$.datepicker.initialized = false;
$.datepicker.uuid = new Date().getTime();
$.datepicker.version = "1.11.1";

var datepicker = $.datepicker;



}));

/*
 * jQuery Mobile: jQuery UI Datepicker Monkey Patch
 * http://salman-w.blogspot.com/2014/03/jquery-ui-datepicker-for-jquery-mobile.html
 */
(function() {
	// use a jQuery Mobile icon on trigger button
	$.datepicker._triggerClass += " ui-btn ui-btn-right ui-icon-carat-d ui-btn-icon-notext ui-corner-all";
	// replace jQuery UI CSS classes with jQuery Mobile CSS classes in the generated HTML
	$.datepicker._generateHTML_old = $.datepicker._generateHTML;
	$.datepicker._generateHTML = function(inst) {
		return $("<div></div>").html(this._generateHTML_old(inst))
			.find(".ui-datepicker-header").removeClass("ui-widget-header ui-helper-clearfix").addClass("ui-bar-inherit").end()
			.find(".ui-datepicker-prev").addClass("ui-btn ui-btn-left ui-icon-carat-l ui-btn-icon-notext").end()
			.find(".ui-datepicker-next").addClass("ui-btn ui-btn-right ui-icon-carat-r ui-btn-icon-notext").end()
			.find(".ui-icon.ui-icon-circle-triangle-e, .ui-icon.ui-icon-circle-triangle-w").replaceWith(function() { return this.childNodes; }).end()
			.find("span.ui-state-default").removeClass("ui-state-default").addClass("ui-btn").end()
			.find("a.ui-state-default.ui-state-active").removeClass("ui-state-default ui-state-highlight ui-priority-secondary ui-state-active").addClass("ui-btn ui-btn-active").end()
			.find("a.ui-state-default").removeClass("ui-state-default ui-state-highlight ui-priority-secondary").addClass("ui-btn").end()
			.find(".ui-datepicker-buttonpane").removeClass("ui-widget-content").end()
			.find(".ui-datepicker-current").removeClass("ui-state-default ui-priority-secondary").addClass("ui-btn ui-btn-inline ui-mini").end()
			.find(".ui-datepicker-close").removeClass("ui-state-default ui-priority-primary").addClass("ui-btn ui-btn-inline ui-mini").end()
			.html();
	};
	// replace jQuery UI CSS classes with jQuery Mobile CSS classes on the datepicker div, unbind mouseover and mouseout events on the datepicker div
	$.datepicker._newInst_old = $.datepicker._newInst;
	$.datepicker._newInst = function(target, inline) {
		var inst = this._newInst_old(target, inline);
		if (inst.dpDiv.hasClass("ui-widget")) {
			inst.dpDiv.removeClass("ui-widget ui-widget-content ui-helper-clearfix").addClass(inline ? "ui-content" : "ui-content ui-overlay-shadow ui-body-a").unbind("mouseover mouseout");
		}
		return inst;
	};
})();
