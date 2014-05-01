<div id="setup_dialog" title="Setup">
	<div id="setup_accordion">
		<h3>Practice Location</h3>
		<div>
			<form id="setup1" class="pure-form pure-form-stacked">
				<label for="practice_name">Practice Name:</label><input type="text" name="practice_name" id="practice_name" class="text" style="width:400px" required/><input type="hidden" id="practice_name_old"/>
				<label for="street_address1">Street Address:</label><input type="text" name="street_address1" id="street_address1" class="text" style="width:400px" required/><input type="hidden" id="street_address1_old"/>
				<label for="street_address2">Street Address Line 2:</label><input type="text" name="street_address2" id="street_address2" class="text" style="width:400px"/><input type="hidden" id="street_address2_old"/>
				<label for="city">City:</label><input type="text" name="city" id="city" class="text" style="width:400px" required/><input type="hidden" id="city_old"/>
				<label for="state">State:</label><select name="state" id="state" class="text" style="width:400px" required></select><input type="hidden" id="state_old"/>
				<label for="zip">Zip:</label><input type="text" name="zip" id="zip" class="text" style="width:400px" required/><input type="hidden" id="zip_old"/>
				<label for="phone">Phone:</label><input type="text" name="phone" id="phone" class="text" style="width:400px" required/><input type="hidden" id="phone_old"/>
				<label for="fax">Fax:</label><input type="text" name="fax" id="fax" class="text" style="width:400px" required/><input type="hidden" id="fax_old"/>
				<label for="email">E-mail:</label><input type="text" name="email" id="email" class="text" style="width:400px" required/><input type="hidden" id="email_old"/>
				<label for="website">Website:</label><input type="text" name="website" id="website" class="text" style="width:400px"/><input type="hidden" id="website_old"/>
				<label for="additional_message">Additional message for e-mailed<br>appointment reminders:</label><textarea name="additional_message" id="additional_message" class="text" rows="4" style="width:400px"></textarea><input type="hidden" id="additional_message_old"/>
				<?php if (Session::get('practice_id') == '1') {?>
					<label for="smtp_user">Gmail username for sending e-mail:</label><input type="text" name="smtp_user" id="smtp_user" class="text" style="width:400px"/><input type="hidden" id="smtp_user_old"/>
					<label for="smtp_pass">Gmail password for sending e-mail:</label><input type="password" name="smtp_pass" id="smtp_pass" class="text" style="width:400px"/><input type="hidden" id="smtp_pass_old"/>
					<label for="patient_portal">Patient Portal web address</label><input type="text" name="patient_portal" id="patient_portal" class="text" style="width:400px"/><input type="hidden" id="patient_portal_old"/>
				<?php }?>
			</form>
		</div>
		<h3>Practice Information</h3>
		<div>
			<form id="setup2" class="pure-form pure-form-stacked">
				<label for="primary_contact">Practice Primary Contact:</label>
				<input type="text" name="primary_contact" id="primary_contact" class="text" style="width:400px"/><input type="hidden" id="primary_contact_old"/>
				<label for="npi">Practice NPI:</label>
				<input type="text" name="npi" id="npi" class="text" style="width:400px"/><input type="hidden" id="npi_old"/>
				<label for="medicare">Practice Medicare Number:</label>
				<input type="text" name="medicare" id="medicare" class="text" style="width:400px"/><input type="hidden" id="medicare_old"/>
				<label for="tax_id">Practice Tax ID:</label>
				<input type="text" name="tax_id" id="tax_id" class="text" style="width:400px"/><input type="hidden" id="tax_id_old"/>
				<label for="default_pos_id">Default Practice Location:</label>
				<input type="text" name="default_pos_id" id="default_pos_id" class="text" style="width:400px" required/><input type="hidden" id="default_pos_id_old"/>
				<?php if (Session::get('practice_id') == '1') {?>
					<label for="documents_dir">Documents Directory:</label>
					<input type="text" name="documents_dir" id="documents_dir" class="text" style="width:400px" required/><input type="hidden" id="documents_dir_old"/>
				<?php }?>
				<label for="weight_unit">Weight Unit:</label>
				<select name="weight_unit" id="weight_unit" class="text" required>
					<option value="lbs">Pounds</option>
					<option value="kg">Kilograms</option>
				</select><input type="hidden" id="weight_unit_old"/>
				<label for="height_unit">Height Unit:</label>
				<select name="height_unit" id="height_unit" class="text" required>
					<option value="in">Inches</option>
					<option value="cm">Centimeters</option>
				</select><input type="hidden" id="height_unit_old"/>
				<label for="temp_unit">Temperature Unit:</label>
				<select name="temp_unit" id="temp_unit" class="text" required>
					<option value="F">Fahrenheit</option>
					<option value="C">Celsius</option>
				</select><input type="hidden" id="temp_unit_old"/>
				<label for="hc_unit">Head Circumerence Unit:</label>
				<select name="hc_unit" id="hc_unit" class="text" required>
					<option value="in">Inches</option>
					<option value="cm">Centimeters</option>
				</select><input type="hidden" id="hc_unit_old"/>
				<label for="hc_unit">ICD database:</label>
				<select name="icd" id="icd" class="text" required>
					<option value="9">ICD-9</option>
					<option value="10">ICD-10</option>
				</select><input type="hidden" id="icd_old"/>
			</form>
		</div>
		<h3>Practice Logo</h3>
		<div>
			<div id="practice_logo_upload_preview"></div>
			<div id="practice_logo_message"></div>
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<button type="button" id="practice_logo_upload_submit" class="nosh_button_add">Upload Logo</button><br><br>
			<button type="button" id="practice_logo_none" class="nosh_button_cancel">No Logo</button>
		</div>
		<h3>Fax Service</h3>
		<div>
			<form id="setup3" class="pure-form pure-form-stacked">
				<label for="fax_type">Fax Program:</label>
				<select name="fax_type" id="fax_type" class="text"></select><input type="hidden" id="fax_type_old"/>
				<label for="fax_email">E-mail address where faxes are sent:</label>
				<input type="text" name="fax_email" id="fax_email" class="text" style="width:400px"/><input type="hidden" id="fax_email_old"/>
				<label for="fax_email_password">Password :</label>
				<input type="password" name="fax_email_password" id="fax_email_password" class="text" style="width:400px"/><input type="hidden" id="fax_email_password_old"/>
				<label for="fax_email_hostname">IMAP Hostname (hostname:port):</label>
				<input type="text" name="fax_email_hostname" id="fax_email_hostname" class="text" style="width:400px" placeholder="hostname:port"/><input type="hidden" id="fax_email_hostname_old"/>
			</form>
		</div>
		<h3>Practice Billing Setup</h3>
		<div>
			<button type="button" id="transfer_address" class="nosh_button_copy">Copy Practice Address to Billing Address</button><br>
			<form id="setup4" class="pure-form pure-form-stacked">
				<label for="billing_street_address1">Billing Street Address:</label>
				<input type="text" name="billing_street_address1" id="billing_street_address1" class="text" style="width:400px" required/><input type="hidden" id="billing_street_address1_old"/>
				<label for="billing_street_address2">Billing Street Address Line 2:</label>
				<input type="text" name="billing_street_address2" id="billing_street_address2" class="text" style="width:400px"/><input type="hidden" id="billing_street_address2_old"/>
				<label for="billing_city">Billing City:</label>
				<input type="text" name="billing_city" id="billing_city" class="text" style="width:400px" required/><input type="hidden" id="billing_city_old"/>
				<label for="billing_state">Billing State:</label>
				<select name="billing_state" id="billing_state" class="text" required></select><input type="hidden" id="billing_state_old"/>
				<label for="billing_zip">Billing Zip:</label>
				<input type="text" name="billing_zip" id="billing_zip" class="text" style="width:400px" required/><input type="hidden" id="billing_zip_old"/>
			</form>
		</div>
	</div>
</div>
