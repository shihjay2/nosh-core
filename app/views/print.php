<div id="print_list_dialog" title="Coordination of Care">
	<table id="records_release" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="records_release_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="new_records_release" class="nosh_button_add">New Records Release</button>
	<button type="button" id="edit_records_release" class="nosh_button_reactivate">Records Re-Release</button><br><br>
	<table id="records_request" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="records_request_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="new_records_request" class="nosh_button_add">New Records Request</button>
	<button type="button" id="edit_records_request" class="nosh_button_reactivate">Edit Records Request</button>
	<button type="button" id="hippa_request_received" class="nosh_button_check">Mark as Received</button>
</div>
<div id="print_chart_dialog" title="Print/Send Records">
	<form id="print_chart_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="hippa_id" id="print_hippa_id1"/>
		<div class="pure-control-group"><label for="hippa_reason1">Reason:</label><input type="text" name="hippa_reason" id="hippa_reason1" style="width:200px" class="text" required/></div>
		<div id="print_chart_form_provider" class="pure-control-group"></div>
		<div class="pure-control-group"><label for="hippa_provider1">Records Release To:</label><input type="text" name="hippa_provider" id="hippa_provider1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="hippa_role1">Provider Role:</label><select name="hippa_role" id="hippa_role1" class="text"></select></div>
		<div class="pure-control-group"><label for="hippa_date_release1">Date of Records Release:</label><input type="text" name="hippa_date_release" id="hippa_date_release1" style="width:200px" class="text" required/></div>
	</form>
</div>
<div id="print_chart2_dialog" title="Print/Send Records">
	<input type="hidden" id="print_hippa_id"/>
	<div id="print_accordion">
		<h3>Type</h3>
		<div>
			<button id="print_all_records" class="nosh_button">All records</button>
			<button id="print_1year_records" class="nosh_button">All records from the past year</button>
			<button id="print_queue_records" class="nosh_button">Selected records from queue</button>
			<button id="print_ccda" class="nosh_button">Generate C-CDA</button>
		</div>
		<h3>Queue</h3>
		<div>
			Printing or faxing an empty queue will result in Continuity of Care Record only!<br>
			<table id="print_items_queue" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_items_queue_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="remove_item" class="nosh_button_cancel">Remove Item from Queue</button>
			<button type="button" id="clear_queue" class="nosh_button_delete">Clear Queue</button><br><br>
			<hr class="ui-state-default"/>
			<strong>Select from the following items:</strong><br>
			<table id="print_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_encounters_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="print_messages" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_messages_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="print_labs" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager8" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_radiology" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager9" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_cardiopulm" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager10" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_endoscopy" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager11" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_referrals" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager12" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_past_records" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager13" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_outside_forms" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager14" class="scroll" style="text-align:center;"></div> 
			<br>
			<table id="print_letters" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="print_pager15" class="scroll" style="text-align:center;"></div> 
		</div>
		<h3>Action</h3>
		<div>
			<div id="print_accordion_action">You need to select a Type first.</div>
			<div id="nosh_print1" style="display:none"><button type="button" id="print_all" class="nosh_button_print">Print</button><button type="button" id="fax_all" class="nosh_button fax_button">Fax</button></div>
			<div id="nosh_print2" style="display:none"><button type="button" id="print_1year" class="nosh_button_print">Print</button><button type="button" id="fax_1year" class="nosh_button fax_button">Fax</button></div>
			<div id="nosh_print3" style="display:none"><button type="button" id="print_queue" class="nosh_button_print">Print</button><button type="button" id="fax_queue" class="nosh_button fax_button">Fax</button></div>
		</div>
		<h3>Details</h3>
		<div>
			<div id="print_release_stats"></div>
			<button type="button" id="edit_hippa" class="nosh_button_edit">Edit Records Release Info</button>
		</div>
	</div>
</div>
<div id="print_fax_dialog" title="Fax Records">
	<form name="print_fax_form" id="print_fax_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="print_fax_type"/>
		<div class="pure-control-group"><label for="print_fax_recipient">Recipient:</label><input type="text" name="faxrecipient" id="print_fax_recipient" style="width:200px;" class="text"/></div>
		<div class="pure-control-group"><label for="print_fax_faxnumber">Fax Number:</label><input type="text" name="faxnumber" id="print_fax_faxnumber" class="text"/></div>
	</form>
</div>
<div id="print_message_view_dialog" title="Telephone Message"></div>
<div id="print_encounter_view_dialog" title="Encounter">
	<div id="print_encounter_view"></div>
</div>
<div id="print_to_dialog" title="">
	<input type="hidden" id="print_to_origin"/>
	<form id="print_to_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="address_id" id="print_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1-3"><label for="print_to_lastname">Last Name:</label><input type="text" name="lastname" id="print_to_lastname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="print_to_firstname">First Name:</label><input type="text" name="firstname" id="print_to_firstname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="print_to_facility">Facility:</label><input type="text" name="facility" id="print_to_facility" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_prefix">Prefix:</label><input type="text" name="prefix" id="print_to_prefix" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_suffix">Suffix:</label><input type="text" name="suffix" id="print_to_suffix" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_specialty">Specialty:</label><input type="text" name="specialty" id="print_to_specialty" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="print_to_email">E-mail:</label><input type="text" name="email" id="print_to_email" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="print_to_address">Address:</label><input type="text" name="street_address1" id="print_to_address" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="print_to_address2">Address2:</label><input type="text" name="street_address2" id="print_to_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_city">City:</label><input type="text" name="city" id="print_to_city" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_state">State:</label><select name="state" id="print_to_state" class="text pure-input-1"></select></div>
			<div class="pure-u-1-3"><label for="print_to_zip">Zip:</label><input type="text" name="zip" id="print_to_zip" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_phone">Phone:</label><input type="text" name="phone" id="print_to_phone" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="print_to_fax">Fax:</label><input type="text" name="fax" id="print_to_fax" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="print_to_comments">Comments:</label><input type="text" name="comments" id="print_to_comments" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
<div id="hippa_request_dialog" title="Request Records">
	<form id="hippa_request_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="hippa_request_id" id="hippa_request_id"/>
		<label for="request_reason">Reason:</label><input type="text" name="request_reason" id="request_reason" style="width:300px" class="text" required/>
		<label for="request_type">Type:</label>
		<select name="request_type" id="request_type" style="width:200px" class="text" required>
			<option value="">Select Option</option>
			<option value="General Medical Records">General Medical Records</option>
			<optgroup label="Specific">
				<option value="History and Physical">History and Physical</option>
				<option value="Medications and Therapy">Medications and Therapy</option>
				<option value="Lab, Imaging, or Cardiopulmonary Report">Lab, Imaging, or Cardiopulmonary Report</option>
				<option value="Operative Report">Operative Report</option>
				<option value="Accident or Injury">Accident or Injury</option>
				<option value="Immunizations">Immunizations</option>
				<option value="Other">Other</option>
			</optgroup>
		</select>
		<div id="hippa_request_provider"></div>
		<label for="hippa_request_to">Records Release To:</label><input type="text" name="request_to" id="hippa_request_to" style="width:200px" class="text" required/>
		<div id="hippa_request_history_physical_div" style="display:none"><label for="hippa_request_history_physical">Date of History and Physical:</label><input type="text" name="history_physical" id="hippa_request_history_physical" style="width:200px" class="text"/></div>
		<div class="hippa_request_lab_div" style="display:none"><label for="hippa_request_lab_type">Type of Test:</label><input type="text" name="lab_type" id="hippa_request_lab_type" style="width:200px" class="text"/></div>
		<div class="hippa_request_lab_div" style="display:none"><label for="hippa_request_lab_date">Date of Test:</label><input type="text" name="lab_date" id="hippa_request_lab_date" style="width:200px" class="text"/></div>
		<div id="hippa_request_op_div" style="display:none"><label for="hippa_request_op">Type of Operation:</label><input type="text" name="op" id="hippa_request_op" style="width:200px" class="text"/></div>
		<div class="hippa_request_accident_div" style="display:none"><label for="hippa_request_accident_f">Date of Injury/Accident (from):</label><input type="text" name="accident_f" id="hippa_request_accident_f" style="width:200px" class="text"/></div>
		<div class="hippa_request_accident_div" style="display:none"><label for="hippa_request_accident_t">Date of Injury/Accident (to):</label><input type="text" name="accident_t" id="hippa_request_accident_t" style="width:200px" class="text"/></div>
		<div id="hippa_request_other_div" style="display:none"><label for="hippa_request_other">Other:</label><input type="text" name="op" id="hippa_request_other" style="width:200px" class="text"/></div>
		<label for="hippa_date_request">Date of Request:</label><input type="text" name="hippa_date_request" id="hippa_date_request" style="width:200px" class="text" required/>
	</form>
</div>
