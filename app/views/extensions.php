<div id="extensions_dialog" title="Extensions">
	<form id="extensions_form" class="pure-form pure-form-stacked">
		<div id="extensions_accordion">
			<h3>DrFirst Rcopia Integration</h3>
			<div>
				<label for="rcopia_extension">Enable DrFirst Rcopia Extension:</label>
				<select name="rcopia_extension" id="rcopia_extension" class="text"></select>
				<label for="rcopia_apiVendor">DrFirst Rcopia Vendor Username for the Practice (apiVendor):</label>
				<input type="text" name="rcopia_apiVendor" id="rcopia_apiVendor" class="text" style="width:400px"/>
				<label for="rcopia_apiPass">DrFirst Rcopia Vendor Password for the Practice (apiPass):</label>
				<input type="text" name="rcopia_apiPass" id="rcopia_apiPass" class="text" style="width:400px"/>
				<label for="rcopia_apiPractice">DrFirst Rcopia Practice Username (apiPractice):</label>
				<input type="text" name="rcopia_apiPractice" id="rcopia_apiPractice" class="text" style="width:400px"/>
				<label for="rcopia_apiSystem">DrFirst Rcopia Vendor Name (apiSystem):</label>
				<input type="text" name="rcopia_apiSystem" id="rcopia_apiSystem" class="text" style="width:400px"/>
			</div>
			<h3>Updox Sync Integration</h3>
			<div>
				<label for="updox_extension">Enable Updox Sync Extension:</label>
				<select name="updox_extension" id="updox_extension" class="text"></select>
			</div>
			<h3>Vivacare Patient Education Materials</h3>
			<div>
				<label for="vivacare">Username for Vivacare (XXXXXX in http://www.XXXXXX.fromyourdoctor.com when you registered):</label>
				<input type="text" name="vivacare" id="vivacare" class="text" style="width:400px"/><br><br>
				<a href="https://vivacare.com/provider/register/register.jsp" target="_blank">Register at Vivacare for free.</a>
			</div>
			<?php if (Session::get('practice_id') == '1') {?>
				<h3>SNOMED-CT</h3>
				<div>
					<label for="snomed_extension">Enable SNOMED-CT Extension:</label>
					<select name="snomed_extension" id="snomed_extension" class="text"></select>
				</div>
			<?php }?>
			<h3>Medicare Medication Therapy Management (MTM) Integration</h3>
			<div>
				<label for="mtm_extension">Enable Medicare Medication Therapy Management (MTM) Extension:</label>
				<select name="mtm_extension" id="mtm_extension" class="text"></select>
				<label for="mtm_alert_users">Medication Therapy Management (MTM) Extension Alert Providers:</label>
				<select name="mtm_alert_users[]" id="mtm_alert_users" multiple="multiple" style="width:400px" class="text"></select>
			</div>
			<h3>PeaceHealth Laboratories</h3>
			<div>
				<label for="peacehealth_id">Practice ID number:</label>
				<input type="text" name="peacehealth_id" id="peacehealth_id" class="text" style="width:400px"/>
			</div>
			<h3>Birthday Announcements</h3>
			<div>
				<label for="birthday_extension">Enable Birthday Announcement Extension:</label>
				<select name="birthday_extension" id="birthday_extension" class="text"></select>
				<label for="birthday_message">Birthday Message:</label>
				<textarea name="birthday_message" id="birthday_message" class="text" rows="4" style="width:400px"></textarea>
				<br><br>
				<strong>The birthday message sent out will appear like this:</strong><br><br>
				SMS message:<br>
				Happy Birthday, {patient's first name}, from <?php echo $practiceinfo->practice_name;?>!  Call <?php echo $practiceinfo->phone;?> if you would like to schedule an appointment with your provider.<br><br>
				E-mail message:<br>
				Happy Birthday, {patient's first name}, from <?php echo $practiceinfo->practice_name;?>!<br>
				<span id="birthday_message_preview"></span><br>
				If you would like to set up an appointment with your provider, please contact us at <?php echo $practiceinfo->phone;?> or reply to this e-mail at <?php echo $practiceinfo->email;?>.
			</div>
			<h3>Continuing Care Reminders</h3>
			<div>
				<label for="appointment_extension">Enable Continuing Care Reminder Extension:</label>
				<select name="appointment_extension" id="appointment_extension" class="text"></select>
				<label for="appointment_interval">Appointment interval (minimum time lapsed from last appointment):</label>
				<select name="appointment_interval" id="appointment_interval" class="text"></select>
				<label for="appointment_message">Continuing Care Reminder Message:</label>
				<textarea name="appointment_message" id="appointment_message" class="text" rows="4" style="width:400px"></textarea>
				<br><br>
				<strong>The continuing care reminder message sent out will appear like this:</strong><br><br>
				SMS message:<br>
				Time for continuing care appointment with <?php echo $practiceinfo->practice_name;?>.  Call <?php echo $practiceinfo->phone;?> to schedule an appointment or visit <?php echo $practiceinfo->patient_portal;?> to schedule online.<br><br>
				E-mail message:<br>
				Dear, {patient's first name},<br>
				It is time for your continuing care appointment with <?php echo $practiceinfo->practice_name;?>. Please call us at <?php echo $practiceinfo->phone;?> or visit <?php echo $practiceinfo->patient_portal;?> to schedule your next appointment at your earliest convenience.<br>
				<span id="appointment_message_preview"></span><br>
				Thank you<br>
				<?php echo $practiceinfo->practice_name;?><br>
				Phone: <?php echo $practiceinfo->phone;?><br>
				Email: <?php echo $practiceinfo->email;?>
			</div>
		</div>
	</form>
</div>
