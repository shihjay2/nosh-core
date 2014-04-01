<div id="admin_schedule_dialog" title="Scheduling Setup">
	<div id="admin_schedule_accordion">
		<h3>Entire Clinic Schedule</h3>
		<div>
			<form id="schedule-setup1" class="pure-form pure-form-stacked">
				<label for="weekends">Include Weekends in the schedule:</label>
				<select name="weekends" id="weekends" class="text"></select><input type="hidden" id="weekends_old"/>
				<label for="minTime">First hour/time that will be displayed on the schedule:</label>
				<input type="text" name="minTime" id="minTime" size="20" class="schedule_time text" required/><input type="hidden" id="minTime_old"/>
				<label for="maxTime">Last hour/time that will be displayed on the schedule:</label>
				<input type="text" name="maxTime" id="maxTime" size="20" class="schedule_time text" required/><input type="hidden" id="maxTime_old"/>
				<label for="timezone">Timezone:</label>
				<select name="timezone" id="timezone" class="text" required></select><input type="hidden" id="timezone_old"/>
				<br>
				<strong>Clinic-wide operation hours</strong>
				<table id="global_schedule" class="pure-table pure-table-horizontal">
					<thead>
						<tr>
							<th>Day</th>
							<th>Open</th>
							<th>Close</th>
							<th>Closed All Day</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Sunday</td>
							<td><input type="text" name="sun_o" id="sun_o" size="20" class="schedule_time text"/><input type="hidden" id="sun_o_old"/></td>
							<td><input type="text" name="sun_c" id="sun_c" size="20" class="schedule_time text"/><input type="hidden" id="sun_c_old"/></td>
							<td><input type="checkbox" id="sun_all" name="sun_all"/></td>
						</tr>
						<tr>
							<td>Monday</td>
							<td><input type="text" name="mon_o" id="mon_o" size="20" class="schedule_time text"/><input type="hidden" id="mon_o_old"/></td>
							<td><input type="text" name="mon_c" id="mon_c" size="20" class="schedule_time text"/><input type="hidden" id="mon_c_old"/></td>
							<td><input type="checkbox" id="mon_all" name="mon_all"/></td>
						</tr>
						<tr>
							<td>Tuesday</td>
							<td><input type="text" name="tue_o" id="tue_o" size="20" class="schedule_time text"/><input type="hidden" id="tue_o_old"/></td>
							<td><input type="text" name="tue_c" id="tue_c" size="20" class="schedule_time text"/><input type="hidden" id="tue_c_old"/></td>
							<td><input type="checkbox" id="tue_all" name="tue_all"/></td>
						</tr>
						<tr>
							<td>Wednesday</td>
							<td><input type="text" name="wed_o" id="wed_o" size="20" class="schedule_time text"/><input type="hidden" id="wed_o_old"/></td>
							<td><input type="text" name="wed_c" id="wed_c" size="20" class="schedule_time text"/><input type="hidden" id="wed_c_old"/></td>
							<td><input type="checkbox" id="wed_all" name="wed_all"/></td>
						</tr>
						<tr>
							<td>Thursday</td>
							<td><input type="text" name="thu_o" id="thu_o" size="20" class="schedule_time text"/><input type="hidden" id="thu_o_old"/></td>
							<td><input type="text" name="thu_c" id="thu_c" size="20" class="schedule_time text"/><input type="hidden" id="thu_c_old"/></td>
							<td><input type="checkbox" id="thu_all" name="thu_all"/></td>
						</tr>
						<tr>
							<td>Friday</td>
							<td><input type="text" name="fri_o" id="fri_o" size="20" class="schedule_time text"/><input type="hidden" id="fri_o_old"/></td>
							<td><input type="text" name="fri_c" id="fri_c" size="20" class="schedule_time text"/><input type="hidden" id="fri_c_old"/></td>
							<td><input type="checkbox" id="fri_all" name="fri_all"/></td>
						</tr>
						<tr>
							<td>Saturday</td>
							<td><input type="text" name="sat_o" id="sat_o" size="20" class="schedule_time text"/><input type="hidden" id="sat_o_old"/></td>
							<td><input type="text" name="sat_c" id="sat_c" size="20" class="schedule_time text"/><input type="hidden" id="sat_c_old"/></td>
							<td><input type="checkbox" id="sat_all" name="sat_all"/></td>
						</tr>
					</tbody>
				</table>
				</form>
		</div>
		<h3>Visit Types</h3>
		<div>
			<table id="visit_type_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="visit_type_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_visit_type" class="nosh_button_add">Add</button>
			<button type="button" id="edit_visit_type" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_visit_type" class="nosh_button_delete">Delete</button>
		</div>
		<h3>Provider Schedule Exceptions</h3>
		<div>
			<form class="pure-form pure-form-stacked">
				<label for="provider_list1">Provider:</label><select id ="provider_list1" name="provider_list1" class="text"></select>
			</form>
			<br>
			<div id="provider_grid">
				<table id="exception_list" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="exception_list_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="add_exception1" class="nosh_button_add">Add</button>
				<button type="button" id="edit_exception1" class="nosh_button_edit">Edit</button>
				<button type="button" id="delete_exception1" class="nosh_button_delete">Delete</button>
			</div>
			<br>
			<button type="button" id="admin_schedule_preview" class="nosh_button">Preview Calendar</button>
		</div>
	</div>
</div>
