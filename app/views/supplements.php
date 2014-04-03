<div id="supplements_list_dialog" title="Supplements">
	<div id="orders_supplements_header" style="display:none">
		<button type="button" id="save_orders_supplements" class="nosh_button_save">Save Supplement Order</button> 
		<button type="button" id="cancel_orders_supplements_helper" class="nosh_button_cancel">Cancel</button> 
		<br><br>
	</div>
	<div id="messages_supplements_header" style="display:none">
		<button type="button" id="save_orders_supplements1" class="nosh_button_save">Import Supplement Order</button> 
		<button type="button" id="cancel_orders_supplements_helper1" class="nosh_button_cancel">Cancel</button> 
		<br><br>
	</div>
	<div id="oh_supplements_header" style="display:none">
		<button type="button" id="save_oh_supplements" class="nosh_button_save">Save Supplements List</button>
		<br><br>
	</div>
	<table id="supplements" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="supplements_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="add_sup" class="nosh_button_add">Add</button>
		<button type="button" id="edit_sup" class="nosh_button_edit">Reorder</button>
		<button type="button" id="inactivate_sup" class="nosh_button_cancel">Inactivate</button>
		<button type="button" id="delete_sup" class="nosh_button_delete">Delete</button><br><br>
	<?php }?>
	<form id="messages_supplements_main_form">
		<input type="hidden" name="advised" id="supplement_text"/>
		<input type="hidden" name="purchased" id="supplement_text1"/>
		<input type="hidden" name="inactivate" id="supplement_inactivate_text"/>
		<input type="hidden" name="reactivate" id="supplement_reactivate_text"/>
	</form>
	<input type="hidden" id="supplement_origin_orders"/>
	<input type="hidden" id="supplement_origin_orders1"/>
	<table id="supplements_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="supplements_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<input type="button" id="reactivate_sup" value="Reactivate Supplement" class="nosh_button_reactivate"/>
	<?php }?>
</div>
<div id="edit_sup_dialog" title="">
	<form name="edit_sup_form" id="edit_sup_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="sup_id" id="sup_id"/>
		<input type="hidden" name="amount" id="sup_amount"/>
		<input type="hidden" name="supplement_id" id="supplement_id"/>
		<div class="pure-g">
			<div class="pure-u-3-4 messages_sup_provider_div"><label for="messages_sup_provider">Prescribing Provider:</label><select name="id" id="messages_sup_provider" class="text pure-input-1"/></select></div>
			<div class="pure-u-1-4" style="text-align:center; padding: 2% 0;"><button type="button" id="search_db_supplement" class="nosh_button_extlink">Database</button></div>
			<div class="pure-u-1-2"><label for="sup_supplement">Supplement:</label><input type="text" name="sup_supplement" id="sup_supplement" class="text pure-input-1" required/></div>
			<div class="pure-u-1-4"><label for="sup_dosage">Dosage:</label><input type="text" name="sup_dosage" id="sup_dosage"class="text pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="sup_dosage_unit">Unit:</label><input type="text" name="sup_dosage_unit" id="sup_dosage_unit" class="text pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="sup_sig">Sig:</label><input type="text" name="sup_sig" id="sup_sig" class="text pure-input-1"/></div>
			<div class="pure-u-1-4"><label for="sup_route">Route</label><select id ="sup_route" name="sup_route" class="text pure-input-1"></select></div>
			<div class="pure-u-1-2"><label for="sup_frequency">Frequency:</label><input type="text" name="sup_frequency" id="sup_frequency"  class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="sup_instructions">Special Instructions:</label><input type="text" name="sup_instructions" id="sup_instructions" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="sup_reason">Reason:</label><input type="text" name="sup_reason" id="sup_reason" class="text pure-input-1" required/></div>
			<div class="pure-u-1"><label for="sup_date_active">Date Active:</label><input type="text" name="sup_date_active" id="sup_date_active" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
<div id="supplement_inventory_dialog" title="Supplement Inventory Confirmation">
	<form id="supplement_inventory_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="supplement_id" id="reactivate_supplement_id"/>
		This is confirmation that you will be dispensing <span id="supplement_inventory_description"></span>.<br>
		<label for="supplement_inventory_dialog_amount">Indicate below the quantity (bottles or packages) you will be dispensing:</label>
		<input type="text" id="supplement_inventory_dialog_amount" class="text" required/>
	</form>
</div>
