<div id="allergies_list_dialog" title="Allergies">
	<button type="button" id="save_oh_allergies" style="display:none" class="nosh_button_save">Save Allergies List</button> 
	<?php if(Session::get('rcopia') == 'y') {?>
		<button type="button" id="rcopia_update_allergies" class="nosh_button">Update from RCopia</button>
	<?php }?>
	<table id="allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="add_allergy" class="nosh_button_add">Add Allergy</button>
		<button type="button" id="edit_allergy" class="nosh_button_edit">Edit Allergy</button>
		<button type="button" id="inactivate_allergy" class="nosh_button_cancel">Inactivate Allergy</button>
		<button type="button" id="delete_allergy" class="nosh_button_delete">Delete Allergy</button><br><br>
	<?php }?>
	<table id="allergies_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="allergies_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="reactivate_allergy" class="nosh_button">Reactivate Allergy</button><br><br>
	<?php }?>
</div>
<div id="edit_allergy_dialog" title="">
	<form name="edit_allergy_form" id="edit_allergy_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="allergies_id" id="allergies_id"/>
		<div class="pure-control-group"><label for='allergies_med'>Medication:</label><input type="text" name="allergies_med" id="allergies_med" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for='allergies_reaction'>Reaction:</label><input type="text" name="allergies_reaction" id="allergies_reaction" style="width:500px" class="text"/></div>
		<div class="pure-control-group"><label for='allergies_date_active'>Date Active:</label><input type="text" name="allergies_date_active" id="allergies_date_active" class="text"/></div>
	</form>
</div>
