<script type="text/javascript">
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
	function schedule_autosave() {
		var d = $('#providers_calendar').fullCalendar('getDate');
		var n = d.getFullYear();
		n = n + "," + d.getMonth();
		n = n + "," + d.getDate();
		var view = $('#providers_calendar').fullCalendar('getView');
		n = n + "," + view.name;
		$.cookie('nosh-schedule', n, { path: '/' });
	}
	setInterval(schedule_autosave, 10000);
	
	$("#patient_appt_button").button();
	$('#patient_appt_button').click(function() {
		$("#patient_appt").show("fast");
		$("#start_form").show("fast");
		$("#reason_form").show("fast");
		$("#other_event").hide("fast");
		$("#event_choose").hide("fast");
		$("#patient_search").focus();
	});
	$("#event_appt_button").button();
	$('#event_appt_button').click(function() {
		$("#patient_appt").hide("fast");
		$("#other_event").show("fast");
		$("#start_form").show("fast");
		$("#reason_form").show("fast");
		$("#event_choose").hide("fast");
		$("#reason").focus();
	});
	$("#patient_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
			$("#pid").val(ui.item.id);
			$("#title").val(ui.item.value);
		}
	});
	$("#start_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#start_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': <?php echo $schedule_increment;?>
	});
	$('#end').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': <?php echo $schedule_increment;?>
	});
	$('#visit_type').change(function() {
		var visit_type_select = $("#visit_type").val();
		if (visit_type_select != ''){
			$("#end_row").hide("fast");
			$("#end").val('');
		} else {
			$("#end_row").show("fast");
		}
	});
	$('#repeat').change(function() {
		var repeat_select = $("#repeat").val();
		if (repeat_select != ''){
			$("#until_row").show("fast");
		} else {
			$("#until_row").hide("fast");
			$("#until").val('');
		}
	});
	$("#until").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#delete_event').click(function() {
		if(confirm('Are you sure you want to delete this appointment?')){ 
			var appt_id = $("#event_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/schedule/delete_event/');?>",
				data: "appt_id=" + appt_id,
				success: function(data){
					$("#providers_calendar").fullCalendar('removeEvents');
					$("#event_dialog").dialog('close');
					$("#providers_calendar").fullCalendar('refetchEvents');
				}
			});
		} 
	});
	$('#openChart1').click(function() {
		var pid = $("#pid").val();
		if(pid){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('search/openchart/');?>",
				data: "pid=" + pid,
				success: function(data){
					window.location = "<?php echo site_url('search/openchart1/');?>";
				}
			});
		} else {
			$.jGrowl("Please enter patient to open chart!");
		}
	});
	
</script>
<div style="width:250px;float:left;"><div id="providers_datepicker"></div></div>
<div style="width:650px;float:left;"><div id="providers_calendar" ></div></div>
<div id="event_dialog" title="Schedule an Appointment">
	<form id="event_form">
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="event_id" id="event_id"/>
		<input type="hidden" name="title" id="title"/>
		<div id="event_choose">
			Choose a type of event: 
			<button type="button" id="patient_appt_button">Patient Appointment</button> 
			<button type="button" id="event_appt_button">Other Event</button>
		</div>
		<div id="delete_form">
			<a href="#" id="delete_event" style="float:right;text-align:right">[Delete]</a>
			Event ID: <span id="event_id_span"></span> | Patient ID: <span id="pid_span"></span> <input type="button" value="Open Chart" id="openChart1" class="ui-button ui-state-default ui-corner-all"/> | Status: 
			<select name="status" id="status" class="text ui-widget-content ui-corner-all">
				<option value="">None</option>
				<option value="Pending">Pending</option>
				<option value="Reminder Sent">Reminder Sent</option>
				<option value="Attended">Attended</option>
				<option value="LMC">Last Minute Cancellation</option>
				<option value="DNKA">Did Not Keep Appointment</option>
			</select>
			<br>
			<span id="timestamp_span"></span><br>
			<hr class="ui-state-default"/>
		</div>
		<div id="start_form">
			<table>
				<tr>
					<td><label for="start_date">Start Date:</label></td>
					<td><input type="text" name="start_date" id="start_date" class="text ui-widget-content ui-corner-all"></td>
				</tr>
				<tr>
					<td><label for="start_time">Start Time:</label></td>
					<td><input type="text" name="start_time" id="start_time" class="text ui-widget-content ui-corner-all"></td>
				</tr>
			</table>
			<br>
		</div>
		<div id="patient_appt">
			<label for="patient_search">Patient:</label><br>
			<input type="text" name="patient_search" id="patient_search" style="width:400px" class="text ui-widget-content ui-corner-all" /><br>
			<label for="visit_type">Visit Type:</label><br>
			<select name="visit_type" id="visit_type" class="text ui-widget-content ui-corner-all"><?php if ($visit_type_select != '') {echo $visit_type_select;}?></select><br>
		</div>
		<div id="reason_form">
			<label for="reason">Reason:</label><br>
			<textarea name="reason" id="reason" style="width:400px" rows="3" class="text ui-widget-content ui-corner-all"></textarea><br>
		</div>
		<div id="other_event">
			<label for="end">End Time:</label><br>
			<input type="text" name="end" id="end" class="text ui-widget-content ui-corner-all"><br>
			<label for="repeat">Repeat:</label><br>
			<select name="repeat" id="repeat" class="text ui-widget-content ui-corner-all">
				<option value="">None</option>
				<option value="86400">Every Day</option>
				<option value="604800">Every Week</option>
				<option value="1209600">Every Other Week</option>
			</select><br>
			<div id="until_row">
				<label for="until">Until (Leave this field blank if repeat goes on forever):</label><br>
				<input type="text" name="until" id="until" class="text ui-widget-content ui-corner-all" /><br>
			</div>
		</div>
	</form>
</div>
