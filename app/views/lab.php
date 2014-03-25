<div id="messages_lab_dialog" title="Lab Helper">
	<input type="hidden" name="messages_lab_origin" id="messages_lab_origin"/>
	<input type="hidden" name="messages_lab_t_messages_id_origin" id="messages_lab_t_messages_id_origin"/>
	<table id="messages_lab_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_lab_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="messages_add_lab" class="nosh_button_add">Add</button> 
	<button type="button" id="messages_edit_lab" class="nosh_button_edit">Edit</button>
	<button type="button" id="messages_resend_lab" class="nosh_button_reactivate">Resend</button> 
	<button type="button" id="messages_delete_lab" class="nosh_button_delete">Delete</button>
</div>
<div id="messages_lab_edit_fields" title="">
	<form id="edit_message_lab_form">
		<div class="pure-form nosh_provider_exclude"><label for="messages_lab_provider_list">Provider:</label><select id ="messages_lab_provider_list" name="id" class="text"></select></div>
		<div style="float:right;" id="messages_lab_status"></div>
		<input type="hidden" name="orders_id" id="messages_lab_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_lab_t_messages_id"/>
		<div id="messages_lab_accordion">
			<h3><a href="#">Lab Tests</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_lab_orders">Preview:</label><textarea name="orders_labs" id="messages_lab_orders" rows="10" style="width:95%" class="text" placeholder="Type a few letters of order to search." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_lab_orders_clear" class="nosh_button_cancel messages_lab_button_clear">Clear</button>
					<button type="button" id="messages_lab_orderslist_link" class="nosh_button_edit">Edit Lab Orders Templates</button>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_lab_codes">Preview:</label><textarea name="orders_labs_icd" id="messages_lab_codes" rows="10" style="width:95%" class="text" placeholder="Use a comma to separate distinct search terms."></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_lab_codes_clear" class="nosh_button_cancel messages_lab_button_clear">Clear</button>
					<button type="button" id="messages_lab_issues" class="nosh_button_extlink">Issues</button>
				</div>
			</div>
			<h3><a href="#">Location and Date</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_lab_location">Location:</label><select name="address_id" id="messages_lab_location" class="text" required></select></div><br>
					<div class="pure-form pure-form-stacked"><label for="messages_lab_orders_pending_date">Order Date:</label><input type="text" name="orders_pending_date" id="messages_lab_orders_pending_date" class="text" required/></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_select_lab_location2" class="nosh_button_edit">Add/Edit</button>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_lab_insurance">Preview:</label><textarea name="orders_insurance"  id="messages_lab_insurance" rows="3" style="width:95%" class="text" required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_lab_insurance_clear" value="Clear" class="nosh_button_cancel messages_lab_button_clear">Clear</button>
					<button type="button" id="messages_lab_insurance_client" value="Bill Client" class="nosh_button">Bill Client</button>
				</div>
				<div class="pure-u-1">
					<br><br>
					<table id="messages_lab_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="messages_lab_insurance_pager" class="scroll" style="text-align:center;"></div>
				</div>
			</div>
			<h3><a href="#">Obtained Specimens</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_lab_obtained">Preview:</label><textarea name="orders_labs_obtained" id="messages_lab_obtained" rows="12" style="width:95%" class="text"></textarea></div>
				</div>
				<div class="pure-form pure-form-aligned pure-u-11-24">
					<div class="pure-control-group"><label for="messages_lab_fasting">Fasting:</label><select name="messages_lab_fasting" id="messages_lab_fasting" class="text"><option value="">Choose an option</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
					<div class="pure-control-group"><label for="messages_lab_date_obtained">Date Obtained:</label><input type="text" id="messages_lab_date_obtained" name="messages_lab_date_obtained" style="width:164px" class="text"/></div>
					<div class="pure-control-group"><label for="messages_lab_time_obtained">Time Obtained:</label><input type="text" id="messages_lab_time_obtained" name="messages_lab_time_obtained" style="width:164px" class="text"/></div>
					<div class="pure-control-group"><label for="messages_lab_location_obtained">Body Location Obtained:</label><input type="text" id="messages_lab_location_obtained" name="messages_lab_location_obtained" style="width:164px" class="text"/></div>
					<div class="pure-control-group"><label for="messages_lab_medication_obtained">Time of Last Medication Dosage:</label><input type="text" id="messages_lab_medication_obtained" name="messages_lab_medication_obtained" style="width:164px" class="text"/></div>
					<button type="button" id="messages_lab_obtained_import" class="nosh_button_copy">Enter</button>
					<button type="button" id="messages_lab_obtained_clear" class="nosh_button_cancel messages_lab_button_clear">Clear</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="messages_lab_action_dialog" title="Action">
	<div id="messages_lab_choice"></div><br>
	<button type="button" id="messages_print_lab" class="nosh_button_print">Print</button>
	<button type="button" id="messages_electronic_lab" class="nosh_button">Electronic</button>
	<button type="button" id="messages_fax_lab" class="nosh_button fax_button">Fax</button>
	<button type="button" id="messages_done_lab" class="nosh_button_check">Done</button>
</div>
<div id="add_test_cpt" title="Add Order to Database">
	<form id="add_test_cpt_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="messages_lab"/>
		<input type="hidden" id="messages_lab_orders_text"/>
		<div class="pure-control-group"><label for="messages_lab_orders_type">Order Type:</label><select id="messages_lab_orders_type"></select></div>
		<div class="pure-control-group"><label for="messages_lab_cpt">CPT Code (optional):</label><input type="text" name="messages_lab_cpt" id="messages_lab_cpt" style="width:400px" class="text"/></div>
		<div id="add_test_snomed_div">
			<div class="pure-control-group"><label for="messages_lab_snomed">SNOMED Code (optional):<br><input type="text" name="messages_lab_snomed" id="messages_lab_snomed" style="width:400px" class="text" placeholder="Type a few letters to search or select from hierarchy."/></div><br><br>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="snomed_tree" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="messages_edit_lab_location" title="">
	<form id="messages_edit_lab_location_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="messages_lab_location_address_id" id="messages_lab_location_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1"><label for="messages_lab_location_facility">Facility:</label><input type="text" name="facility" id="messages_lab_location_facility" class="text pure-input-1" required/></div>
			<div class="pure-u-1"><label for="messages_lab_location_address">Address:</label><input type="text" name="street_address1" id="messages_lab_location_address" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_lab_location_address2">Address2:</label><input type="text" name="street_address2" id="messages_lab_location_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_city">City:</label><input type="text" name="city" id="messages_lab_location_city" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_state">State:</label><select name="state" id="messages_lab_location_state" class="text pure-input-1"></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_zip">Zip:</label><input type="text" name="zip" id="messages_lab_location_zip" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_phone">Phone:</label><input type="text" name="phone" id="messages_lab_location_phone" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_fax">Fax:</label><input type="text" name="fax" id="messages_lab_location_fax" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"></div>
			<div class="pure-u-1"><label for="messages_lab_location_comments">Comments:</label><input type="text" name="comments" id="messages_lab_location_comments" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_lab_location_ordering_id">Provider/Clinic Identity:</label><input type="text" name="ordering_id" id="messages_lab_location_ordering_id" class="text pure-input-1"/></div>
			<div class="pure-u-2-3"><label for="messages_lab_location_electronic_order">Electronic Order Interface (optional)</label><select name="electronic_order" id="messages_lab_location_electronic_order" class="text pure-input-1"></select></div>
		</div>
	</form>
</div>
<div id="messages_lab_aoe_dialog" title="Required Question">
	<form id="messages_lab_aoe_dialog_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="aoe_value" value=""/>
		<div class="pure_control_group aoe_fasting" style="display:none;"><label for="aoe_fasting_input">Fasting:</label><select id="aoe_fasting_input" class="text"><option value="">Choose an option</option><option value="Yes">Yes</option><option value="No">No</option></select><input type="hidden" id="aoe_fasting_code" value=""/></div>
		<div class="pure_control_group aoe_fasting_hours" style="display:none;"><label for="aoe_fasting_hours_input">Hours fasting:</label><input type="text" id="aoe_fasting_hours_input" style="width:164px" class="text"/><input type="hidden" id="aoe_fasting_hours_code" value=""/></div>
		<div class="pure_control_group aoe_dose_date" style="display:none;"><label for="aoe_dose_date_input">Date of Last Medication Dosage:</label><input type="text" id="aoe_dose_date_input" style="width:164px" class="text"/><input type="hidden" id="aoe_dose_date_code" value=""/></div>
		<div class="pure_control_group aoe_dose_time" style="display:none;"><label for="aoe_dose_time_input">Time of Last Medication Dosage:</label><input type="text" id="aoe_dose_time_input" style="width:164px" class="text"/><input type="hidden" id="aoe_dose_time_code" value=""/></div>
		<div class="pure_control_group aoe_source" style="display:none;"><label for="aoe_source_input">Source:</label><input type="text" id="aoe_source_input" style="width:290px" class="text"/><input type="hidden" id="aoe_source_code" value=""/></div>
		<div class="pure_control_group aoe_source1" style="display:none;"><label for="aoe_source1_input">Source:</label><input type="text" id="aoe_source1_input" style="width:290px" class="text"/><input type="hidden" id="aoe_source1_code" value=""/></div>
		<div class="pure_control_group aoe_additional" style="display:none;"><label for="aoe_additional_input">Additional information:</label><input type="text" id="aoe_additional_input" style="width:290px" class="text"/><input type="hidden" id="aoe_additional_code" value=""/></div>
	</form>
</div>
