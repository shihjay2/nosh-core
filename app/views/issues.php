<div id="issues_list_dialog" title="Issues">
	<div id="issues_pmh_header" style="display:none">
		<button type="button" id="copy_oh_pmh_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_oh_pmh_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_psh_header" style="display:none">
		<button type="button" id="copy_oh_psh_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_oh_psh_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_lab_header" style="display:none">
		<button type="button" id="copy_lab_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_lab_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_rad_header" style="display:none">
		<button type="button" id="copy_rad_all_issues">Copy All Active Issues</button>
		<button type="button" id="copy_rad_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_cp_header" style="display:none">
		<button type="button" id="copy_cp_all_issues">Copy All Active Issues</button> 
		<button type="button" id="copy_cp_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_ref_header" style="display:none">
		<button type="button" id="copy_ref_all_issues">Copy All Active Issues</button> 
		<button type="button" id="copy_ref_one_issue">Copy Issue</button>
		<br><br>
	</div>
	<div id="issues_assessment_header" style="display:none">
		Select Issue as Diagnosis:<br>
		<button type="button" id="copy_assessment_issue_1" class="copy_assessment_issue_class">#1</button> 
		<button type="button" id="copy_assessment_issue_2" class="copy_assessment_issue_class">#2</button>
		<button type="button" id="copy_assessment_issue_3" class="copy_assessment_issue_class">#3</button>
		<button type="button" id="copy_assessment_issue_4" class="copy_assessment_issue_class">#4</button>
		<button type="button" id="copy_assessment_issue_5" class="copy_assessment_issue_class">#5</button> 
		<button type="button" id="copy_assessment_issue_6" class="copy_assessment_issue_class">#6</button>
		<button type="button" id="copy_assessment_issue_7" class="copy_assessment_issue_class">#7</button>
		<button type="button" id="copy_assessment_issue_8" class="copy_assessment_issue_class">#8</button>
		<button type="button" id="copy_assessment_issue_9" class="copy_assessment_issue_class">Additional Diagnosis</button>
		<button type="button" id="copy_assessment_issue_10" class="copy_assessment_issue_class">Differential Diagnosis</button>
		<br><br>
	</div>
	<table id="issues" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="add_issue" class="nosh_button_add">Add</button>
		<button type="button" id="edit_issue" class="nosh_button_edit">Edit</button>
		<button type="button" id="inactivate_issue" class="nosh_button_cancel">Inactivate</button>
		<button type="button" id="delete_issue" class="nosh_button_delete">Delete</button><br><br>
	<?php }?>
	<table id="issues_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="issues_inactive_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<button type="button" id="reactivate_issue" class="nosh_button_reactivate">Reactivate</button>
	<?php }?>
</div>
<div id="edit_issue_dialog" title="">
	<form name="edit_issue_form" id="edit_issue_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="issue_id" id="issue_id"/>
		<div class="pure-control-group"><label for="issue">Issue:</label><input type="text" name="issue" id="issue" style="width:500px" class="text" placeholder="Use a comma to separate distinct search terms." required/></div>
		<div class="pure-control-group"><label for="issue_type">Type:</label><select id="issue_type" name="type" class="text"></select></div>
		<div class="pure-control-group"><label for="issue_date_active">Date Active:</label><input type="text" name="issue_date_active" id="issue_date_active" class="text"/></div>
	</form>
</div>
