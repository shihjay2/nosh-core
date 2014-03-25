<div id="print_list_dialog" title="Records Release">
	<table id="records_release" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="records_release_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="new_records_release" class="nosh_button_add">New Records Release</button>
	<button type="button" id="edit_records_release" class="nosh_button_reactivate">Records Re-Release</button>
</div>
<div id="print_chart_dialog" title="Print/Send Records">
	<form id="print_chart_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="hippa_id" id="print_hippa_id1"/>
		<div class="pure-control-group"><label for="hippa_date_release">Date of Records Release</label><input type="text" name="hippa_date_release" id="hippa_date_release1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="hippa_reason">Reason</label><input type="text" name="hippa_reason" id="hippa_reason1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="hippa_provider">Records Release To:</label><input type="text" name="hippa_provider" id="hippa_provider1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="hippa_role">Provider Role</label><select name="hippa_role" id="hippa_role1" class="text"></select></div>
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
		<button type="button" id="print_fax_add_fax_contact">Add Contact to Address Book</button>
		<button type="button" id="print_fax_send_fax">Send Fax</button>
		<button type="button" id="print_fax_cancel_fax">Cancel</button>
	</form>
</div>
<div id="print_message_view_dialog" title="Telephone Message"></div>
<div id="print_encounter_view_dialog" title="Encounter">
	<div id="print_encounter_view"></div>
</div>
