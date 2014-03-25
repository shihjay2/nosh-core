<div id="alerts_list_dialog" title="Alerts and Tasks">
	<table id="alerts" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_pager1" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="add_alert" class="nosh_button_add">Add</button> 
		<button type="button" id="edit_alert" class="nosh_button_edit">Edit</button>
		<button type="button" id="complete_alert" class="nosh_button_check">Mark as Completed</button> 
		<button type="button" id="incomplete_alert" class="nosh_button_cancel">Mark as Incomplete</button>
		<button type="button" id="delete_alert" class="nosh_button_delete">Delete</button><br><br>
	<?php }?>
	<table id="alerts_complete" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_complete_pager" class="scroll" style="text-align:center;"></div><br>
	<table id="alerts_not_complete" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="alerts_not_complete_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="edit_alert_dialog" title="Alert or Task">
	<form name="edit_alert_form" id="edit_alert_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="alert_id" id="alert_id"/>
		<input type="hidden" name="id" id="alert_provider_id"/>
		<div class="pure-control-group"><label for="alert">Alert:</label><input type="text" name="alert" id="alert" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for="alert_provider">User or Provider to Alert:</label><input type="text" name="alert_provider" id="alert_provider" style="width:500px" class="text"/></div>
		<div class="pure-control-group"><label for="alert_description">Description:</label><textarea name="alert_description" id="alert_description" rows="2" style="width:500px" class="text"></textarea></div>
		<div class="pure-control-group"><label for="alert_date_active">Due Date:</label><input type="text" name="alert_date_active" id="alert_date_active" class="text"/></div>
	</form>
</div>
<div id="edit_alert_dialog1" title="Alert or Task Not Completed">
	<form name="edit_alert_form1" id="edit_alert_form1" class="pure-form pure-form-aligned">
		<input type="hidden" name="alert_id1" id="alert_id1"/>
		<div class="pure-control-group"><label for="alert_reason_not_complete">Reason:</label><input type="text" name="alert_reason_not_complete" id="alert_reason_not_complete" style="width:500px" class="text" required/></div>
	</form>
</div>
