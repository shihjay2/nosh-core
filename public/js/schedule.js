$(document).ready(function() {
	$("#schedule_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 925, 
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			if (noshdata.group_id == '100') {
				$("#schedule_patient_step").hide();
			}
			$(this).dialog('option', {
				height: 640,
				width: 925,
				position: { my: 'center', at: 'center', of: '#maincontent' }
			});
		},
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
		}],
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#nosh_schedule").click(function() {
		open_schedule();
	});
	$("#dashboard_schedule").click(function() {
		open_schedule();
	});
	$("#admin_schedule_preview").click(function() {
		open_schedule();
	});
	$('#provider_list2').change(function() {
		var id = $('#provider_list2').val();
		if(id){
			$.ajax({
				type: "POST",
				url: "ajaxschedule/set-provider",
				data: "id=" + id,
				success: function(data){
					if( $.cookie('nosh-schedule') === undefined){
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
					$("#schedule_visit_type").removeOption(/./);
					$.ajax({
						url: "ajaxsearch/visit-types/" + id,
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
				}
			});
		} 
	});
	$("#event_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
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
				var visit_type = $("#schedule_visit_type").val();
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
							data: "pid=" + pid,
							success: function(data){
								window.location = data.url;
							}
						});
					} else {
						if(pid == oldpt){
							$.jGrowl("Patient chart already open!");
						} else {
							$.ajax({
								type: "POST",
								url: "ajaxsearch/openchart",
								dataType: "json",
								data: "pid=" + pid,
								success: function(data){
									window.location = data.url;
								}
							});
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
	$('#schedule_visit_type').change(function() {
		var visit_type_select = $("#schedule_visit_type").val();
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
	swipe();
});
