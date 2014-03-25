<div id="billing_list_dialog" title="Patient Billing">
	<input type="hidden" id="billing_list_eid"/>
	<input type="hidden" id="billing_list_other_billing_id"/>
	<fieldset class="ui-corner-all">
		<div id="total_balance"></div>
		<button type="button" id="billing_notes" class="nosh_button_edit">Edit Billing Notes</button><button type="button" class="nosh_button insurance_billing">Insurance</button>
	</fieldset><br>
	<table id="billing_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_encounters_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="edit_encounter_charge" class="nosh_button_edit">View/Edit Billing Details</button>
	<button type="button" id="payment_encounter_charge" class="nosh_button_check">Make Payment for Encounter</button>
	<button type="button" id="invoice_encounter_charge" class="nosh_button_print">Print Invoice for Encounter</button><br><br>
	<table id="billing_other" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="billing_other_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_charge" class="nosh_button_add">Add Special Bill</button>
	<button type="button" id="edit_charge" class="nosh_button_edit">Edit Special Bill</button>
	<button type="button" id="payment_charge" class="nosh_button_check">Make Payment for Bill</button>
	<button type="button" id="invoice_charge" class="nosh_button_print">Print Invoice for Bill</button>
	<button type="button" id="delete_charge" class="nosh_button_delete">Delete Special Bill</button>
</div>
<div id="billing_notes_dialog" title="Billing Notes">
	<form id="billing_notes_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="billing_billing_notes_old"/>
		<div class="pure-control-group"><label for="billing_billing_notes">Billing Notes:</label><textarea name="billing_notes" id="billing_billing_notes" rows="6" style="width:300px" class="text"></textarea></div>
	</form>
</div>
<div id="billing_detail_dialog" title="Detailed Billing Information">
	<div id="billing_detail_accordion">
		<h3>Who to bill</h3>
		<div>
			<form id="billing_detail_form">
				<input type="hidden" name="eid" id="billing_eid_1"/>
				<input type="hidden" name="insurance_id_1" id="billing_insurance_id_1"/>
				<input type="hidden" name="insurance_id_2" id="billing_insurance_id_2"/>
				<input type="hidden" name="insurance_id_1_old" class="old" id="billing_insurance_id_1_old"/>
				<input type="hidden" name="insurance_id_2_old" class="old" id="billing_insurance_id_2_old"/>
				<input type="hidden" name="bill_complex_old" id="billing_bill_complex_old1"/>
			</form>
			<div class="pure-g">
				<div class="pure-u-1-3">Primary Insurance:</div>
				<div class="pure-u-1-3" id="billing_insuranceinfo1"></div>
				<div class="pure-u-1-3"><button type="button" id="billing_clear_insurance1" class="nosh_button_cancel">Clear</button></div>
				<div class="pure-u-1-3">Secondary Insurance:</div>
				<div class="pure-u-1-3" id="billing_insuranceinfo2"></div>
				<div class="pure-u-1-3"><button type="button" id="billing_clear_insurance2" class="nosh_button_cancel">Clear</button></div>
			</div><br>
			<table id="billing_insurance_list1" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="billing_insurance_pager1" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="billing_select_insurance1" class="nosh_button_check">Set as Primary</button>
			<button type="button" id="billing_select_insurance2" class="nosh_button_check">Set as Secondary</button>
			<button type="button" id="billing_select_self_pay" class="nosh_button_check">No Insurance</button>
			<button type="button" class="nosh_button insurance_billing">Edit Insurance</button>
		</div>
		<h3>What to bill</h3>
		<div>
			<div id="billing_icd9"></div>
			<br>
			<table id="billing_cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="billing_cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_billing_cpt1" class="nosh_button_add">Add Row</button>
			<button type="button" id="edit_billing_cpt1" class="nosh_button_edit">Edit Row</button>
			<button type="button" id="remove_billing_cpt1" class="nosh_button_delete">Remove Row</button>
		</div>
		<h3>Action</h3>
		<div>
			<button type="button" id="print_invoice1" class="nosh_button_print">Print Invoice</button>
			<button type="button" id="print_hcfa1" class="nosh_button_print">Print HCFA-1500 - Editable</button>
			<button type="button" id="print_hcfa2" class="nosh_button_print">Print HCFA-1500</button>
		</div>
	</div>
</div>
<div id="cpt_billing_dialog1" title="CPT Editor">
	<form id="billing_form1" class="pure-form pure-form-aligned">
		<input type="hidden" name="billing_core_id" id="billing_core_id1"/>
		<div class="pure-control-group"><label for="billing_cpt1">CPT Code:</label><input type="text" name="cpt" id="billing_cpt1" style="width:200px" class="text" required/> <button type="button" id="cpt_helper1" class="nosh_button_calculator">CPT Helper</button><button type="button" id="cpt_link1" class="nosh_button_edit">CPT Editor</button></div>
		<div class="pure-control-group"><label for="billing_cpt_charge1">Charge:</label>$<input type="text" name="cpt_charge" id="billing_cpt_charge1" style="width:195px" class="text" required/> <button type="button" id="update_cpt_charge1" class="nosh_button_check">Update Charge</button></div>
		<div class="pure-control-group"><label for="billing_unit1">Unit:</label><input type="text" name="unit" id="billing_unit1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_modifier1">Modifier:</label><select name="modifier" id="billing_modifier1" style="width:200px" class="text"></select></div>
		<div class="pure-control-group"><label for="billing_dos_f1">Date of Service/From:</label><input type="text" name="dos_f" id="billing_dos_f1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_dos_t1">Date of Service/To:</label><input type="text" name="dos_t" id="billing_dos_t1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_icd1">Diagnosis Pointer</label><select name="icd_pointer[]" id="billing_icd1" multiple="multiple" style="width:400px" class="multiselect" required></select></div>
	</form>
</div>
<div id="cpt_helper_dialog1" title="CPT Helper">
	<div id="cpt_helper_items1">
		<h3><a href="#">Evaluation and Management Codes</a></h3>
		<div>
			<div class="pure-g">
				<div class="pure-u-1-3">New Patient:</div>
				<div class="pure-u-2-3">
					<input name="billing_cpt_helper1" id="99202" value="99202" type="radio" class="nosh_button"><label for="99202">99202</label>
					<input name="billing_cpt_helper1" id="99203" value="99203" type="radio" class="nosh_button"><label for="99203">99203</label>
					<input name="billing_cpt_helper1" id="99204" value="99204" type="radio" class="nosh_button"><label for="99204">99204</label>
					<input name="billing_cpt_helper1" id="99205" value="99205" type="radio" class="nosh_button"><label for="99205">99205</label>
				</div>
				<div class="pure-u-1-3">Established Patient:</div>
				<div class="pure-u-2-3">
					<input name="billing_cpt_helper1" id="99212" value="99212" type="radio" class="nosh_button"><label for="99212">99212</label>
					<input name="billing_cpt_helper1" id="99213" value="99213" type="radio" class="nosh_button"><label for="99213">99213</label>
					<input name="billing_cpt_helper1" id="99214" value="99214" type="radio" class="nosh_button"><label for="99214">99214</label>
					<input name="billing_cpt_helper1" id="99215" value="99215" type="radio" class="nosh_button"><label for="99215">99215</label>
				</div>
			</div>
		</div>
		<h3><a href="#">Preventative Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="new_prevent" value="" type="radio" class="cpt_buttons"><label for="new_prevent">New - <span id="new_prevent1_text"></span></label>
			<input name="billing_cpt_helper1" id="established_prevent" value="" type="radio" class="cpt_buttons"><label for="established_prevent">Established - <span id="established_prevent1_text"></span></label>
		</div>
		<h3><a href="#">Prolonged Visit Codes</a></h3>
		<div>
			<input name="billing_cpt_helper1" id="99354" value="99354" type="radio" class="cpt_buttons"><label for="99354">99354</label>
			<input name="billing_cpt_helper1" id="99355" value="99355" type="radio" class="cpt_buttons"><label for="99355">99355 - Additional 30 minutes</label>
		</div>
	</div>
</div>
<div id="billing_other_dialog" title="Detailed Billing Information">
	<form id="billing_other_form1" class="pure-form pure-form-aligned">
		<input type="hidden" name="other_billing_id" id="billing_other_billing_id"/>
		<div class="pure-control-group"><label for="billing_other_reason1">Reason:</label><input type="text" name="reason" id="billing_other_reason1" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_other_cpt_charge1">Charge:</label>$<input type="text" name="cpt_charge" id="billing_other_cpt_charge1" style="width:195px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_other_dos_f1">Date of Charge:</label><input type="text" name="dos_f" id="billing_other_dos_f1" style="width:200px" class="text" required/></div>
	</form>
</div>	
<div id="billing_payment_dialog" title="Payment">
	<form id="billing_payment_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="billing_core_id" id="billing_billing_core_id"/>
		<input type="hidden" name="other_billing_id" id="billing_payment_other_billing_id"/>
		<input type="hidden" name="eid" id="billing_payment_eid"/>
		<input type="hidden" name="subgrid_table_id" id="billing_subgrid_table_id"/>
		<div class="pure-control-group"><label for="billing_payment_payment">Payment:</label>$<input type="text" name="payment" id="billing_payment_payment" style="width:195px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_payment_payment_type">Payment Type:</label><input type="text" name="payment_type" id="billing_payment_payment_type" style="width:200px" class="text" required/></div>
		<div class="pure-control-group"><label for="billing_payment_dos_f">Date of Payment:</label><input type="text" name="dos_f" id="billing_payment_dos_f" style="width:200px" class="text" required/></div>
	</form>
</div>
