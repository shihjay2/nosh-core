<?php echo HTML::script('/js/mtm_medications.js');?>
<button type="button" id="mtm_medications_reviewed" class="nosh_button_save">Reviewed Medications</button>
<button type="button" id="mtm_print_medication_list" class="nosh_button_print">Print Medication List</button>
<hr class="ui-state-default" style="width:99%"/>
<div id="mtm_medications_form">
	<table id="mtm_medications" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="mtm_medications_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="add_mtm_rx" class="nosh_button_add">Add</button>
	<button type="button" id="edit_mtm_rx" class="nosh_button_edit">Edit</button>
	<button type="button" id="inactivate_mtm_rx" class="nosh_button_cancel">Inactivate</button>
	<button type="button" id="delete_mtm_rx" class="nosh_button_delete">Delete</button><br><br>
	<table id="mtm_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="mtm_encounters_pager" class="scroll" style="text-align:center;"></div>
</div>
