$(document).ready(function() {
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
		for(i in array){
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
					if (noshdata.group_id != '1') {
						if (noshdata.group_id != '100') {
							$("#event_dialog").dialog('open');
							$("#title").focus();
							$("#start_date").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
							$("#start_time").val($.fullCalendar.formatDate(date, 'hh:mmTT'));
							$("#end").val('');
							$("#visit_type").val('');
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
								$("#visit_type").focus();
								$("#start").text($.fullCalendar.formatDate(date, 'dddd, MM/dd/yyyy, hh:mmTT'));
								$("#visit_type").val('');
								$("#reason").val('');
								$("#delete_form").hide("fast");
								$("#patient_appt").show();
								$("#start_form").show();
								$("#reason_form").show();
								$("#other_event").hide();
								$("#event_choose").hide();
								$("#event_dialog").dialog('open');
							}
						}
					}
				}
			},
			eventClick: function(calEvent, jsEvent, view) {
				if (noshdata.group_id != '1') {
					if (calEvent.editable != false) {
						$("#event_dialog").dialog('open');
						$("#title").focus();
					}
					$("#event_id").val(calEvent.id);
					$("#event_id_span").text(calEvent.id);
					$("#schedule_pid").val(calEvent.pid);
					$("#pid_span").text(calEvent.pid);
					$("#timestamp_span").text(calEvent.timestamp);
					$("#start_date").val($.fullCalendar.formatDate(calEvent.start, 'MM/dd/yyyy'));
					$("#start_time").val($.fullCalendar.formatDate(calEvent.start, 'hh:mmTT'));
					$("#end").val($.fullCalendar.formatDate(calEvent.end, 'hh:mmTT'));
					$("#schedule_title").val(calEvent.title);
					$("#visit_type").val(calEvent.visit_type);
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
					$("#delete_form").show();
					$(".nosh_schedule_exist_event").show();
					$("#event_choose").hide();
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
				var display = 'Reason: ' + event.reason + '<br>Status: ' + event.status;
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
	$("#schedule_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 925, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#provider_list2").removeOption(/./);
			$.ajax({
				url: "ajaxsearch/provider-select1",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#provider_list2").addOption({"":"Select a provider."});
					$("#provider_list2").addOption(data, false);
					if (noshdata.group_id == '2' || noshdata.group_id == '3') {
						$.ajax({
							type: "POST",
							url: "ajaxschedule/set-default-provider",
							success: function(data){
								$('#provider_list2').val(noshdata.user_id);
								if( $.cookie('nosh-schedule') === null){
									var d = new Date();
									var y = d.getFullYear();
									var m = d.getMonth();
									var d = d.getDate();
									loadcalendar(y,m,d,'agendaWeek');
								} else {
									var n =  $.cookie('nosh-schedule').split(",");
									loadcalendar(n[0],n[1],n[2],n[3]);
								}
								$("#visit_type").removeOption(/./);
								$.ajax({
									url: "ajaxsearch/visit-types/" + noshdata.user_id,
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
								setInterval(schedule_autosave, 10000);
							}
						});
					}
				}
			});
			$("#provider_list2").focus();
		},
		close: function(event, ui) {
			if (noshdata.group_id == '100') {
				$("#schedule_patient_step").hide();
			}
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#nosh_schedule").click(function() {
		$("#schedule_dialog").dialog('open');
	});
	$("#dashboard_schedule").click(function() {
		$("#schedule_dialog").dialog('open');
	});
	$("#admin_schedule_preview").click(function() {
		$("#schedule_dialog").dialog('open');
	});
	$('#provider_list2').change(function() {
		var id = $('#provider_list2').val();
		if(id){
			$.ajax({
				type: "POST",
				url: "ajaxschedule/set-provider",
				data: "id=" + id,
				success: function(data){
					if( $.cookie('nosh-schedule') === null){
						var d = new Date();
						var y = d.getFullYear();
						var m = d.getMonth();
						var d = d.getDate();
						loadcalendar(y,m,d,'agendaWeek');
					} else {
						var n =  $.cookie('nosh-schedule').split(",");
						loadcalendar(n[0],n[1],n[2],n[3]);
					}
					if (noshdata.group_id == '100') {
						$("#schedule_patient_step").show();
					}
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
	$("#event_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 450, 
		width: 600, 
		modal: true, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#patient_search").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/search",
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
				minLength: 1,
				select: function(event, ui){
					$("#schedule_pid").val(ui.item.id);
					$("#schedule_title").val(ui.item.value);
				}
			});
			if (noshdata.group_id == '100') {
				$("#schedule_dialog_open_chart").hide();
			}
		},
		buttons: [{
			text: 'Save',
			id: 'schedule_dialog_save',
			class: 'nosh_button_save',
			click: function() {
				var end= $("#end").val();
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
				var str = $("#event_form").serialize();
				if(str){
					if (visit_type == '' || visit_type == null && end == '') {
						$.jGrowl("No visit type or end time selected!");
					} else {
						$('#dialog_load').dialog('option', 'title', "Saving...").dialog('open');
						$.ajax({
							type: "POST",
							url: "ajaxschedule/edit-event",
							data: str,
							success: function(data){
								$("#providers_calendar").fullCalendar('removeEvents');
								$("#event_dialog").dialog('close');
								$("#event_form").clearForm();
								$("#providers_calendar").fullCalendar('refetchEvents');
								$('#dialog_load').dialog('close');
							}
						});
					}
				} else {
					$.jGrowl("Error saving appointment!");
				}
			}
		},{
			text: 'Open Chart',
			id: 'schedule_dialog_open_chart',
			class: 'nosh_button_open nosh_schedule_exist_event',
			click: function() {
				var pid = $("#schedule_pid").val();
				if(pid){
					var oldpt = noshdata.pid;
					if(!oldpt){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/openchart",
							dataType: "json",
							data: "pid=" + ui.item.id,
							success: function(data){
								window.location = data.url;
							}
						});
					} else {
						if(pid != oldpt){
							$("#search_dialog").dialog('open');
						} else {
							$.jGrowl("Patient chart already open!");
						}
					}
				} else {
					$.jGrowl("Please enter patient to open chart!");
				}
			}
		},{
			text: 'Delete',
			id: 'schedule_dialog_delete_event',
			class: 'nosh_button_delete nosh_schedule_exist_event',
			click: function() {
				if(confirm('Are you sure you want to delete this appointment?')){ 
					var appt_id = $("#event_id").val();
					$.ajax({
						type: "POST",
						url: "ajaxschedule/delete-event",
						data: "appt_id=" + appt_id,
						success: function(data){
							$("#providers_calendar").fullCalendar('removeEvents');
							$("#event_dialog").dialog('close');
							$("#event_form").clearForm();
							$("#providers_calendar").fullCalendar('refetchEvents');
						}
					});
				} 
			}
		},{
			text: 'Cancel',
			id: 'schedule_dialog_cancel',
			class: 'nosh_button_cancel',
			click: function() {
				$("#event_dialog").dialog('close');
				$("#event_form").clearForm();
			}
		}],
		close: function(event, ui) {
			$("#other_event").hide();
			$("#patient_appt").hide();
			$("#until_row").hide();
			$("#delete_form").hide();
			$(".nosh_schedule_exist_event").hide();
			$("#start_form").hide();
			$("#reason_form").hide();
			$("#event_choose").hide();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#status").addOption({"":"None.","Pending":"Pending","Reminder Sent":"Reminder Sent","Attended":"Attended","LMC":"Last Minute Cancellation","DNKA":"Did Not Keep Appointment"}, false);
	$("#repeat").addOption({"":"None.","86400":"Every Day","604800":"Every Week","1209600":"Every Other Week"}, false);
	$('#patient_appt_button').click(function() {
		loadappt();
	});
	$('#event_appt_button').click(function() {
		loadevent();
	});
	if (noshdata.group_id != '100') {
		$("#start_date").datepicker();
	}
	$("#until").datepicker();
	$('#start_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': noshdata.schedule_increment
	});
	$('#end').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': noshdata.schedule_increment
	});
	$('#visit_type').change(function() {
		var visit_type_select = $("#visit_type").val();
		if (visit_type_select != ''){
			$("#end_row").hide();
			$("#end").val('');
		} else {
			$("#end_row").show();
		}
	});
	$('#repeat').change(function() {
		var repeat_select = $("#repeat").val();
		if (repeat_select != ''){
			$("#until_row").show();
		} else {
			$("#until_row").hide();
			$("#until").val('');
		}
	});
});
