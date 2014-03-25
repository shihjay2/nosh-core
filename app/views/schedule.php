<div id="schedule_dialog" title="Schedule">
	<div id="provider_schedule1" style="overflow:auto;">
		<div style="width:235px;float:left;">
			<div id="providers_datepicker"></div><br><br>
			<?php if (Session::get('group_id') == 100) {?>
				<div>Step 1: Choose a provider to schedule an appointment:<br></div>
			<?php }?>
			<div class="pure-form pure-form-stacked"><label for="provider_list2">Provider:</label><select id ="provider_list2" name="provider_list2" class="text"></select></div>
			<?php if (Session::get('group_id') == 100) {?>
				<div id="schedule_patient_step" style="display:none">
					Step 2: Click on an open time slot on the schedule.<br>
					There can be no overlapping appointments with an existing appointment.<br>
					You will be notified if there is a problem with your appointment request.<br>
					To delete an existing appointment, double click on the appointment and on the dialog box on the upper right hand corner, click Delete.
				</div>
			<?php }?>
		</div>
		<div style="width:620px;float:left;"><div id="providers_calendar" ></div></div>
	</div>
</div>
<div id="event_dialog" title="Schedule an Appointment">
	<form id="event_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="pid" id="schedule_pid"/>
		<input type="hidden" name="event_id" id="event_id"/>
		<input type="hidden" name="title" id="schedule_title"/>
		<div id="event_choose">
			Choose a type of event: 
			<button type="button" id="patient_appt_button" class="nosh_button">Patient Appointment</button> 
			<button type="button" id="event_appt_button" class="nosh_button">Other Event</button>
		</div>
		<div id="delete_form">
			Event ID: <span id="event_id_span"></span> | Patient ID: <span id="pid_span"></span><br>
			<span id="timestamp_span"></span><br>
			<br>
			<div class="pure-control-group"><label for="status">Status:</label><select name="status" id="status" class="text"></select></div>
		</div>
		<div id="start_form">
			<?php if (Session::get('group_id') != '100') {?>
				<div class="pure-control-group"><label for="start_date">Start Date:</label><input type="text" name="start_date" id="start_date" class="text"/></div>
				<div class="pure-control-group"><label for="start_time">Start Time:</label><input type="text" name="start_time" id="start_time" class="text"/></div>
			<?php } else { ?>
				<div class="pure-control-group"><label for="start_date">Start Date:</label><input type="text" name="start_date" id="start_date" class="text" readonly/></div>
				<div class="pure-control-group"><label for="start_time">Start Time:</label><input type="text" name="start_time" id="start_time" class="text" readonly/></div>
			<?php }?>
			<br>
		</div>
		<div id="patient_appt">
			<?php if (Session::get('group_id') != '100') {?>
				<div class="pure-control-group"><label for="patient_search">Patient:</label><input type="text" name="patient_search" id="patient_search" style="width:400px" class="text" /></div>
			<?php }?>
			<div class="pure-control-group"><label for="visit_type">Visit Type:</label><select name="visit_type" id="visit_type" class="text"></select></div>
		</div>
		<div id="reason_form">
			<div class="pure-control-group"><label for="reason">Reason:</label><textarea name="reason" id="reason" style="width:400px" rows="3" class="text"></textarea></div>
		</div>
		<div id="other_event">
			<div class="pure-control-group"><label for="end">End Time:</label><input type="text" name="end" id="end" class="text"></div>
			<div class="pure-control-group"><label for="repeat">Repeat:</label><select name="repeat" id="repeat" class="text"></select></div>
			<div id="until_row">
				<div class="pure-control-group"><label for="until">Until (Leave this field blank if repeat goes on forever):</label><input type="text" name="until" id="until" class="text" /></div>
			</div>
		</div>
	</form>
</div>

