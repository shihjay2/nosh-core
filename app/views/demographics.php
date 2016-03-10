<div id="demographics_list_dialog" title="Demographics">
	<form name="edit_demographics_form" id="edit_demographics_form" class="pure-form pure-form-stacked">
		<button type="button" id="save_menu_demographics" class="nosh_button_save">Save</button>
		<button type="button" id="save_menu_demographics1" class="nosh_button_save">Save and Close</button>
		<button type="button" id="cancel_menu_demographics" class="nosh_button_cancel">Cancel</button>
		<button type="button" id="insurance_menu_demographics">Insurance</button>
		<?php if(Session::get('group_id') != '100') {?>
			<button type="button" id="register_menu_demographics">Register for Patient Portal</button>
		<?php }?>
		<span id="menu_registration_code"></span>
		<br><br>
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="race_code" id="menu_race_code">
		<input type="hidden" name="ethnicity_code" id="menu_ethnicity_code">
		<input type="hidden" name="guardian_code" id="menu_guardian_code">
		<input type="hidden" name="lang_code" id="menu_lang_code">
		<div id="demographics_accordion">
			<h3>Name and Identity</h3>
			<div>
				<div class="pure-g">
					<div class="pure-u-1-3"><label for="menu_lastname">Last Name:</label><input type="text" name="lastname" id="menu_lastname" class="text pure-input-1" required /></div>
					<div class="pure-u-1-3"><label for="menu_firstname">First Name:</label><input type="text" name="firstname" id="menu_firstname" class="text pure-input-1" required /></div>
					<div class="pure-u-1-3"><label for="menu_nickname">Nickname:</label><input type="text" name="nickname" id="menu_nickname" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_middle">Middle Name:</label><input type="text" name="middle" id="menu_middle" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_title">Title:</label><input type="text" name="title" id="menu_title" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_DOB">Date of Birth:</label><input type="text" name="DOB" id="menu_DOB" class="text pure-input-1" required /></div>
					<div class="pure-u-1-3"><label for="menu_gender">Gender:</label><select name="sex" id="menu_gender" class="text pure-input-1" required></select></div>
					<div class="pure-u-1-3"><label for="menu_ss">SSN:</label><input type="text" name="ss" id="menu_ss" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_race">Race:</label><input type="text" name="race" id="menu_race" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_marital_status">Marital Status:</label><select name="marital_status" id="menu_marital_status" class="text pure-input-1"></select></div>
					<div class="pure-u-1-3"><label for="menu_partner_name">Spouse/Partner Name:</label><input type="text" name="partner_name" id="menu_partner_name" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_employer">Employer:</label><input type="text" name="employer" id="menu_employer" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_ethnicity">Ethnicity:</label><input type="text" name="ethnicity" id="menu_ethnicity" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_caregiver">Caregiver(s):</label><input type="text" name="caregiver" id="menu_caregiver" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_active">Status:</label><select name="active" id="menu_active" class="text pure-input-1" required></select></div>
					<div class="pure-u-1-3"><label for="menu_referred_by">Referred By:</label><input type="text" name="referred_by" id="menu_referred_by" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_language">Preferred Language:</label><input type="text" name="language" id="menu_language" class="text pure-input-1" /></div>
				</div>
			</div>
			<h3>Contact</h3>
			<div>
				<div class="pure-g">
					<?php if(Session::get('group_id') != '100') {?>
						<div class="pure-u-1"><label for="menu_autocomplete_patient">Select existing patient to copy contact information:</label><input type="text" id="menu_autocomplete_patient" class="text pure-input-1"/></div>
					<?php }?>
					<div class="pure-u-1"><label for="menu_address">Address:</label><input type="text" name="address" id="menu_address" class="text pure-input-1 address_autocomplete" /></div>
					<div class="pure-u-1-3"><label for="menu_city">City:</label><input type="text" name="city" id="menu_city" class="text pure-input-1 city_autocomplete" /></div>
					<div class="pure-u-1-3"><label for="menu_state">State:</label><select name="state" id="menu_state" class="text pure-input-1"></select></div>
					<div class="pure-u-1-3"><label for="menu_zip">Zip:</label><input type="text" name="zip" id="menu_zip" class="text pure-input-1" /></div>
					<div class="pure-u-1"><label for="menu_email">Email:</label><input type="text" name="email" id="menu_email" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_phone_home">Home Phone:</label><input type="text" name="phone_home" id="menu_phone_home" class="text pure-input-1 phonemask" /></div>
					<div class="pure-u-1-3"><label for="menu_phone_work">Work Phone:</label><input type="text" name="phone_work" id="menu_phone_work" class="text pure-input-1 phonemask" /></div>
					<div class="pure-u-1-3"><label for="menu_phone_cell">Cellular Phone:</label><input type="text" name="phone_cell" id="menu_phone_cell" class="text pure-input-1 phonemask" /></div>
					<div class="pure-u-1-3"><label for="menu_emergency_contact">Emergency Contact:</label><input type="text" name="emergency_contact" id="menu_emergency_contact" class="text pure-input-1" /></div>
					<div class="pure-u-1-3"><label for="menu_emergency_phone">Emergency Phone:</label><input type="text" name="emergency_phone" id="menu_emergency_phone" class="text pure-input-1 phonemask" /></div>
					<div class="pure-u-1-3"></div>
					<div class="pure-u-1-3"><label for="menu_reminder_method">Appointment Reminder Method:</label><select name="reminder_method" id="menu_reminder_method" class="text pure-input-1"></select></div>
					<div class="pure-u-1-3"><label for="menu_cell_carrier">Cellular Phone Carrier:</label><select name="cell_carrier" id="menu_cell_carrier" class="text pure-input-1"></select></div>
					<div class="pure-u-1-3"><br><button type="button" id="test_reminder_email" class="nosh_button pure-input-1">Test Reminder Notification</button></div>
				</div>
			</div>
			<h3>Guardian</h3>
			<div>
				<button type="button" id="guardian_import">Same contact information as patient</button>
				<div class="pure-g">
					<div class="pure-u-1-3"><label for="menu_guardian_lastname">Last Name:</label><input type="text" name="guardian_lastname" id="menu_guardian_lastname" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_firstname">First Name:</label><input type="text" name="guardian_firstname" id="menu_guardian_firstname" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_relationship">Relationship:</label><input type="text" name="guardian_relationship" id="menu_guardian_relationship" class="text pure-input-1"/></div>
					<div class="pure-u-1"><label for="menu_guardian_address">Address:</label><input type="text" name="guardian_address" id="menu_guardian_address" class="text pure-input-1 address_autocomplete"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_city">City:</label><input type="text" name="guardian_city" id="menu_guardian_city" class="text pure-input-1 city_autocomplete"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_state">State:</label><select name="guardian_state" id="menu_guardian_state" class="text pure-input-1"></select></div>
					<div class="pure-u-1-3"><label for="menu_guardian_zip">Zip:</label><input type="text" name="guardian_zip" id="menu_guardian_zip" class="text pure-input-1"/></div>
					<div class="pure-u-1"><label for="menu_guardian_email">Email:</label><input type="text" name="guardian_email" id="menu_guardian_email" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_phone_home">Home Phone:</label><input type="text" name="guardian_phone_home" id="menu_guardian_phone_home" class="text pure-input-1 phonemask"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_phone_work">Work Phone:</label><input type="text" name="guardian_phone_work" id="menu_guardian_phone_work" class="text pure-input-1 phonemask"/></div>
					<div class="pure-u-1-3"><label for="menu_guardian_phone_cell">Cellular Phone:</label><input type="text" name="guardian_phone_cell" id="menu_guardian_phone_cell" class="text pure-input-1 phonemask"/></div>
				</div>
			</div>
			<h3>Other</h3>
			<div>
				<div class="pure-g">
					<div class="pure-u-1-3"><label for="menu_preferred_provider">Preferred Provider:</label><input type="text" name="preferred_provider" id="menu_preferred_provider" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_preferred_pharmacy">Preferred Pharmacy:</label><input type="text" name="preferred_pharmacy" id="menu_preferred_pharmacy" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_other1">Other Field 1:</label><input type="text" name="other1" id="menu_other1" class="text pure-input-1"/></div>
					<div class="pure-u-1-3"><label for="menu_other2">Other Field 2:</label><input type="text" name="other2" id="menu_other2" class="text pure-input-1"/></div>
					<div class="pure-u-1"><label for="menu_comments">Comments:</label><textarea name="comments" id="menu_comments" rows="1" class="text pure-input-1"></textarea></div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="demographics_insurance_plan_dialog" title="">
	<form name="edit_menu_insurance_plan_form" id="edit_menu_insurance_plan_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="address_id" id="menu_insurance_plan_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1"><label for="menu_insurance_plan_facility">Insurance Plan Name:</label><input type="text" name="facility" id="menu_insurance_plan_facility" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_payor_id">Payor ID:</label><input type="text" name="insurance_plan_payor_id" id="menu_insurance_plan_payor_id" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_type">Insurance Type:</label><select name="insurance_plan_type" id="menu_insurance_plan_type" class="text pure-input-1" required></select></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_assignment">Accept Assignment:</label><select name="insurance_plan_assignment" id="menu_insurance_plan_assignment" class="text pure-input-1" required/></select></div>
			<div class="pure-u-1"><label for="menu_insurance_plan_address">Address:</label><input type="text" name="street_address1" id="menu_insurance_plan_address" class="text pure-input-1" required/></div>
			<div class="pure-u-1"><label for="menu_insurance_plan_address2">Address2:</label><input type="text" name="street_address2" id="menu_insurance_plan_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_city">City:</label><input type="text" name="city" id="menu_insurance_plan_city" class="text pure-input-1 city_autocomplete" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_state">State:</label><select name="state" id="menu_insurance_plan_state" class="text pure-input-1" required></select></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_zip">Zip:</label><input type="text" name="zip" id="menu_insurance_plan_zip" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_phone">Phone:</label><input type="text" name="phone" id="menu_insurance_plan_phone" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_ppa_phone">Procedure PA Phone:</label><input type="text" name="insurance_plan_ppa_phone" id="menu_insurance_plan_ppa_phone" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_ppa_fax">Procedure PA Fax:</label><input type="text" name="insurance_plan_ppa_fax" id="menu_insurance_plan_ppa_fax" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1"><label for="menu_insurance_plan_ppa_url">Procedure PA Website:</label><input type="text" name="insurance_plan_ppa_url" id="menu_insurance_plan_ppa_url" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="menu_insurance_plan_mpa_url">Medication PA Website:</label><input type="text" name="insurance_plan_mpa_url" id="menu_insurance_plan_mpa_url" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_mpa_phone">Medication PA Phone:</label><input type="text" name="insurance_plan_mpa_phone" id="menu_insurance_plan_mpa_phone" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_plan_mpa_fax">Medication PA Fax:</label><input type="text" name="insurance_plan_mpa_fax" id="menu_insurance_plan_mpa_fax" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1-3"></div>
			<?php if(Session::get('group_id') != '100') {?>
				<div class="pure-u-1-3"><label for="menu_insurance_box_31">HCFA-1500 Box 31 format:</label><select name="insurance_box_31" id="menu_insurance_box_31" class="text pure-input-1"></select></div>
				<div class="pure-u-1-3"><label for="menu_insurance_box_32a">HCFA-1500 Box 32a/33a format:</label><select name="insurance_box_32a" id="menu_insurance_box_32a" class="text pure-input-1"></select></div>
				<div class="pure-u-1-3"></div>
			<?php }?>
		</div>
	</form>
</div>
<div id="menu_insurance_main_dialog" title="Insurance Plan">
	<form name="edit_menu_insurance_main_form" id="edit_menu_insurance_main_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="insurance_id" id="menu_insurance_id"/>
		<input type="hidden" name="insurance_plan_name" id="menu_insurance_plan_name" required/>
		<div class="pure-g">
			<div class="pure-u-2-3"><label for="menu_insurance_plan_select">Insurance Provider:</label><select name="address_id" id="menu_insurance_plan_select" class="text pure-input-1"></select></div>
			<div class="pure-u-1-3" style="text-align:center; padding: 2% 0;"><button type="button" id="add_insurance_plan" class="nosh_button_add">Add Insurance Provider</button></div>
			<div class="pure-u-1-3"><label for="menu_insurance_order">Insurance Priority:</label><select name="insurance_order" id="menu_insurance_order" class="text pure-input-1"></select></div>
			<div class="pure-u-1-3"><label for="menu_insurance_id_num">ID Number:</label><input type="text" name="insurance_id_num" id="menu_insurance_id_num" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_group">Group Number:</label><input type="text" name="insurance_group" id="menu_insurance_group" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_relationship">Relationship:</label><select name="insurance_relationship" id="menu_insurance_relationship" class="text pure-input-1" required></select></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_lastname">Insured Last Name:</label><input type="text" name="insurance_insu_lastname" id="menu_insurance_insu_lastname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_firstname">Insured First Name:</label><input type="text" name="insurance_insu_firstname" id="menu_insurance_insu_firstname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_dob">Insured Date of Birth:</label><input type="text" name="insurance_insu_dob" id="menu_insurance_insu_dob" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_gender">Insured Gender:</label><select name="insurance_insu_gender" id="menu_insurance_insu_gender" class="text pure-input-1"></select></div>
			<div class="pure-u-1-3" style="text-align:center; padding: 2% 0;"><button type="button" id="insurance_copy" class="nosh_button_copy">Use Patient's Address</button></div>
			<div class="pure-u-1"><label for="menu_insurance_insu_address">Insured Address:</label><input type="text" name="insurance_insu_address" id="menu_insurance_insu_address" class="text pure-input-1 address_autocomplete" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_city">Insured City:</label><input type="text" name="insurance_insu_city" id="menu_insurance_insu_city" class="text pure-input-1 city_autocomplete" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_state">Insured State:</label><select name="insurance_insu_state" id="menu_insurance_insu_state" class="text pure-input-1" required></select></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_zip">Insured Zip:</label><input type="text" name="insurance_insu_zip" id="menu_insurance_insu_zip" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_insu_phone">Insured Phone:</label><input type="text" name="insurance_insu_phone" id="menu_insurance_insu_phone" class="text pure-input-1 phonemask"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_copay">Copay:</label><input type="text" name="insurance_copay" id="menu_insurance_copay" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="menu_insurance_deductible">Deductible:</label><input type="text" name="insurance_deductible" id="menu_insurance_deductible" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="menu_insurance_comments">Comments:</label><textarea name="insurance_comments" id="menu_insurance_comments" rows="3" class="text pure-input-1"></textarea></div>
		</div>
	</form>
</div>
<div id="demographics_insurance_dialog" title="Insurance">
	<table id="demographics_insurance" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_pager" class="scroll" style="text-align:center;"></div><br>
	<div id="demographics_insurance_details"></div><br>
	<button type="button" id="demographics_add_insurance">Add Insurance</button>
	<button type="button" id="demographics_edit_insurance">Edit Insurance</button>
	<button type="button" id="demographics_inactivate_insurance">Inactivate Insurance</button>
	<button type="button" id="demographics_delete_insurance">Delete Insurance</button><br><br>
	<table id="demographics_insurance_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="demographics_insurance_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="button" id="demographics_reactivate_insurance" value="Reactivate Insurance" class="nosh_button"/><br><br>
</div>
<div id="prenatal_dialog" title="Pregnancy Calculator">
	<form id="prenatal_dialog_form" class="pure-form pure-form-aligned">
	<input type="hidden" name="prenatal_dialog_origin" id="prenatal_dialog_origin"/>
	<input type="hidden" name="pregnancy_edc" id="pregnancy_edc"/>
		<fieldset class="ui-corner-all">
			<legend>EDC by dates</legend>
			<div class="pure-control-group"><label for="pregnancy_lmp">Last menstrural period:</label><input type="text" name="pregnancy_lmp" id="pregnancy_lmp" class="text" required/></div>
			<div class="pure-control-group"><label for="pregnancy_cycle">Number of days in cycle:</label>><input type="text" name="pregnancy_cycle" id="pregnancy_cycle" class="text" required/></div>
			<button type="button" id="edc_lmp" class="nosh_button_check">Use for EDC</button> 
		</fieldset>
		<fieldset class="ui-corner-all">
			<legend>EDC by ultrasound</legend>
			<div class="pure-control-group"><label for="pregnancy_us">Ultrasound EDC:</label><input type="text" name="pregnancy_us" id="pregnancy_us" class="text" required/></div>
			<button type="button" id="edc_us" class="nosh_button_check">Use for EDC</button>
		</fieldset>
		<br><br>
		<strong>Consensus EDC:</strong> <span id="edc_text"></span>
	</form>
</div>
