<div id="medications_list_dialog" title="Medications" >
	<div id="oh_meds_header" style="display:none">
		<button type="button" id="save_oh_meds" class="nosh_button_save">Save Medication List</button>
		<br><br>
	</div>
	<table id="medications" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="add_rx" class="nosh_button_add">Add</button>
		<button type="button" id="edit_rx" class="nosh_button_edit">Edit</button>
		<button type="button" id="inactivate_rx" class="nosh_button_cancel">Inactivate</button>
		<button type="button" id="delete_rx" class="nosh_button_delete">Delete</button><br><br>
	<?php }?>
	<table id="medications_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="medications_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="reactivate_rx" class="nosh_button_reactivate">Reactivate</button>
	<?php }?>
</div>
<div id="edit_medications_dialog" title="">
	<img src="https://d4fuqqd5l3dbz.cloudfront.net/static/images/powered-by-goodrx-black-xs.png" style="height:30px;vertical-align:text-top;" title="Prescribing a new medicaion will send your patient a link to GoodRX regarding medication pricing and information." class="nosh_tooltip">
	<form name="edit_rx_form" id="edit_rx_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="rxl_id" id="rxl_id"/>
		<input type="hidden" name="rxl_ndcid" id="rxl_ndcid"/>
		<input type="hidden" id="rxl_name" value=""/>
		<input type="hidden" id="rxl_form" value=""/>
		<div class="pure-g">
			<div class="pure-u-1-2"><label for="rxl_medication">Medication:</label><input type="text" name="rxl_medication" id="rxl_medication" class="text pure-input-1" required/></div>
			<div class="pure-u-1-4"><label for="rxl_dosage">Dosage:</label><input type="text" name="rxl_dosage" id="rxl_dosage" class="text pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="rxl_dosage_unit">Unit:</label><input type="text" name="rxl_dosage_unit" id="rxl_dosage_unit" class="text pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="rxl_sig">Sig:</label><input type="text" name="rxl_sig" id="rxl_sig" class="text search_sig pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="rxl_route">Route:</label><select id ="rxl_route" name="rxl_route" class="text search_route pure-input-1"></select></div>
			<div class="pure-u-1-2"><label for="rxl_frequency">Frequency:</label><input type="text" name="rxl_frequency" id="rxl_frequency" class="text search_frequency pure-input-1"/></div>
			<div class="pure-u-1-2"><label for="rxl_instructions">Special Instructions:</label><input type="text" name="rxl_instructions" id="rxl_instructions" class="text search_instructions pure-input-1"/></div>
			<div class="pure-u-1-2"><label for="rxl_reason">Reason:</label><input type="text" name="rxl_reason" id="rxl_reason" class="text search_reason pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="rxl_date_active">Date Active:</label><input type="text" name="rxl_date_active" id="rxl_date_active" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
	<div id="messages_rx_dialog" title="Prescribing Medications Helper">
		<div id="messages_rx_main">
			<div id="orders_rx_header" style="display:none">
				<?php if(Session::get('rcopia') == 'y') {?>
					<button type="button" id="rcopia_orders_rx" class="nosh_button">Update from RCopia</button>
				<?php }?>
				<button type="button" id="save_orders_rx" class="nosh_button_save">Save Prescriptions</button>
				<button type="button" id="cancel_orders_rx_helper"  class="nosh_button_cancel">Cancel</button>
				<br><br>
			</div>
			<div id="messages_rx_header" style="display:none">
				<?php if(Session::get('rcopia') == 'y') {?>
					<button type="button" id="rcopia_rx_helper" class="nosh_button">Update from RCopia</button>
				<?php }?>
				<button type="button" id="save_rx_helper" class="nosh_button_save">Save Prescriptions to Message</button>
				<button type="button" id="cancel_rx_helper"  class="nosh_button_cancel">Cancel</button>
				<br><br>
			</div>
			<table id="messages_medications" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="messages_medications_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="messages_add_rx" class="nosh_button_add">Add</button>
			<button type="button" id="messages_edit_rx" class="nosh_button_edit">Refill</button>
			<button type="button" id="messages_eie_rx" class="nosh_button_alert">Entered in Error</button>
			<button type="button" id="messages_inactivate_rx" class="nosh_button_cancel">Inactivate</button>
			<button type="button" id="messages_delete_rx" class="nosh_button_delete">Delete</button>
			<button type="button" id="messages_print_rx" class="nosh_button_print">Print List</button><br><br>
			<form id="messages_rx_main_form">
				<input type="hidden" name="rx" id="messages_rx_text"/>
				<input type="hidden" name="eie" id="messages_rx_eie_text"/>
				<input type="hidden" name="inactivate" id="messages_rx_inactivate_text"/>
				<input type="hidden" name="reactivate" id="messages_rx_reactivate_text"/>
			</form>
			<table id="messages_medications_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="messages_medications_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<input type="button" id="messages_reactivate_rx" value="Reactivate Medication" class="ui-button ui-state-default ui-corner-all"/><br><br>
		</div>
	</div>
	<div id="messages_edit_rx_dialog" title="Prescription">
		<img src="https://d4fuqqd5l3dbz.cloudfront.net/static/images/powered-by-goodrx-black-xs.png" style="height:30px;vertical-align:text-top;" title="Prescribing a new medicaion will send your patient a link to GoodRX regarding medication pricing and information." class="nosh_tooltip">
		<form name="messages_edit_rx_form" id="messages_edit_rx_form" class="pure-form pure-form-stacked">
			<input type="hidden" name="rxl_id" id="messages_rxl_id"/>
			<input type="hidden" name="rxl_ndcid" id="messages_rxl_ndcid"/>
			<input type="hidden" id="messages_rxl_medication_list"/>
			<input type="hidden" id="messages_rxl_name" value=""/>
			<input type="hidden" id="messages_rxl_form" value=""/>
			<div class="pure-g">
				<div class="pure-u-1 messages_rx_provider_div"><label for="messages_rx_provider">Prescribing Provider:</label><select name="id" id="messages_rx_provider" class="text pure-input-1" required/></select></div>
				<div class="pure-u-1-2"><label for="messages_rxl_medication">Medication:</label><input type="text" name="rxl_medication" id="messages_rxl_medication" class="text pure-input-1" required/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_dosage">Dosage:</label><input type="text" name="rxl_dosage" id="messages_rxl_dosage" class="text pure-input-1"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_dosage_unit">Unit:</label><input type="text" name="rxl_dosage_unit" id="messages_rxl_dosage_unit" class="text pure-input-1"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_sig">Sig:</label><input type="text" name="rxl_sig" id="messages_rxl_sig" class="text search_sig"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_route">Route:</label><select id ="messages_rxl_route" name="rxl_route" class="text pure-input-1"></select></div>
				<div class="pure-u-1-2"><label for="messages_rxl_frequency">Frequency:</label><input type="text" name="rxl_frequency" id="messages_rxl_frequency" class="text pure-input-1 search_frequency"/></div>
				<div class="pure-u-1-2"><label for="messages_rxl_instructions">Special Instructions:</label><input type="text" name="rxl_instructions" id="messages_rxl_instructions" class="text pure-input-1 search_instructions"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_quantity">Quantity:</label><input type="text" name="rxl_quantity" id="messages_rxl_quantity" class="text pure-input-1"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_refill">Refills:</label><input type="text" name="rxl_refill" id="messages_rxl_refill" class="text pure-input-1"/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_days">Days:</label><input type="text" name="rxl_days" id="messages_rxl_days" class="text pure-input-1"/></div>
				<div class="pure-u-1-2"><label for="messages_rxl_reason">Reason:</label><input type="text" name="rxl_reason" id="messages_rxl_reason" class="text pure-input-1 search_reason" required/></div>
				<div class="pure-u-1-4"><label for="messages_rxl_date_prescribed">Date of Prescription:</label><input type="text" name="rxl_date_prescribed" id="messages_rxl_date_prescribed" class="text pure-input-1"/></div>
			</div>
			<div class="pure-control-group"><label for="messages_rxl_daw">Dispense as Written: <input type="checkbox" name="daw" id="messages_rxl_daw" value="Yes" class="text"/></div>
			<div class="pure-control-group"><label for="messages_rxl_dea">DEA Number on Prescription: <input type="checkbox" name="dea" id="messages_rxl_dea" value="Yes" class="text"/></div>
		</form>
	</div>
	<div id="rx_dialog_confirm" title="Confirmation">
		<div id="rx_dialog_confirm_text"></div>
	</div>
	<div id="rx_dialog_confirm1" title="Confirmation">
		<div id="rx_dialog_confirm_text1"></div>
	</div>
	<div id="interactions_load" title="Checking...">
		<?php echo HTML::image('images/indicator.gif', 'Loading');?> Checking for drug interactions.
	</div>
	<div id="messages_action_rx_dialog" title="What do you want to do?">
		<form name="messages_action_rx_form" id="messages_action_rx_form">
			<input type="hidden" name="prescribe_id" id="prescribe_id"/>
			<div id="prescribe_choice"></div><br><br>
			<button type="button" id="messages_print_medication" class="nosh_button_print">Print Prescription</button>
			<!--<button type="button" id="messages_eprescribe_medication" class="nosh_button">ePrescribe</button> -->
			<button type="button" id="messages_fax_medication" class="nosh_button fax_button">Fax</button>
			<button type="button" id="messages_done_medication" class="nosh_button">Done</button>
		</form>
	</div>
	<div id="messages_rx_fax_dialog" title="Fax Prescription">
		Fax job: <div id="messages_fax_id"></div><br>
		<table id="messages_rx_fax_list" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="messages_rx_fax_list_pager" class="scroll" style="text-align:center;"></div><br>
		<input type="button" id="messages_rx_fax_viewpage" value="View Page" class="ui-button ui-state-default ui-corner-all"/><br><br>
		<form name="messages_rx_fax_form" id="messages_rx_fax_form" class="pure-form pure-form-aligned">
			<input type="hidden" name="fax_prescribe_id" id="fax_prescribe_id"/>
			<div class="pure-control-group"><label for="messages_pharmacy_name">Pharmacy:</label><input type="text" name="messages_pharmacy_name" id="messages_pharmacy_name" size="50" class="text" required/></div>
			<div class="pure-control-group"><label for="messages_pharmacy_fax_number">Fax Number:</label><input type="text" name="messages_pharmacy_fax_number" id="messages_pharmacy_fax_number" class="text" required/></div>
		</form>
		<button type="button" id="messages_add_fax_contact" class="nosh_button_add">Add Pharmacy Contact to Address Book</button>
	</div>
<?php }?>
