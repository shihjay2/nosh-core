<script type="text/javascript">
	noshdata.default_pos = '<?php echo $default_pos;?>';
	noshdata.encounter_active = '<?php echo Session::get('encounter_active'); ?>';
	noshdata.pid = '<?php echo Session::get('pid'); ?>';
	noshdata.eid = '<?php if (Session::get('eid')) {echo Session::get('eid');}?>';
	noshdata.age = '<?php echo Session::get('age'); ?>';
	noshdata.agealldays = '<?php echo Session::get('agealldays'); ?>';
	noshdata.gender = '<?php echo Session::get('gender'); ?>';
	noshdata.t_messages_id = '<?php if(Session::get('t_messages_id')) { echo Session::get('t_messages_id');} ?>';
	noshdata.alert_id = '<?php if(Session::get('alert_id')) { echo Session::get('alert_id');} ?>';
	noshdata.weekends = <?php echo $weekends;?>;
	noshdata.minTime = '<?php echo $minTime;?>';
	noshdata.maxTime = '<?php echo $maxTime;?>';
	noshdata.schedule_increment = '<?php echo $schedule_increment;?>';
	noshdata.financial = '<?php if(Session::get('financial')) { echo Session::get('financial');} ?>';
	noshdata.mtm = '<?php if(Session::get('mtm')) { echo Session::get('mtm');} ?>';
	noshdata.default_template = '<?php echo $encounter_template;?>';
	noshdata.mtm_extension = '<?php if(Session::get('mtm_extension')) { echo Session::get('mtm_extension');} ?>';
	noshdata.hedis = '<?php if(Session::get('hedis')) { echo Session::get('hedis');} ?>';
</script>
<div id="nosh_chart_common_div" style="width:100%">
	<fieldset class="ui-corner-all">
		<div class="pure-g">
			<div class="pure-u-1-5"><i class="fa fa-stethoscope fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="encounter_list" title="Past encounters" class="nosh_tooltip">Encounters</a></div>
			<div class="pure-u-1-5"><i class="fa fa-phone fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="messages_list" title="Telephone messages and e-mail encounters" class="nosh_tooltip">Messages</a></div>
			<div class="pure-u-1-5"><i class="fa fa-file-text-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="documents_list" title="Lab, imaging, cardiopulmonary, referral, and other associated documents for this patient." class="nosh_tooltip">Documents</a></div>
			<div class="pure-u-1-5"><i class="fa fa-money fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="billing_list" title="Past claims, payments, and balances for this patient" class="nosh_tooltip">Billing</a></div>
			<div class="pure-u-1-5"><i class="fa fa-print fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="print_list" title="Print, fax, and create C-CDA documents of this patient's records." class="nosh_tooltip">Print</a></div>
		</div>
	</fieldset>
</div>
<div id="nosh_chart_div" style="width:100%">
	<br>
	<div style="height:500px;float:left;">
		<fieldset class="ui-corner-all">
			<legend>Create</legend>
			<div class="pure-g">
				<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
					<div class="pure-u-1-2"><i class="fa fa-stethoscope fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="new_encounter">New Encounter</a></div>
					<div class="pure-u-1-2"><i class="fa fa-phone fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" class="new_telephone_message">New Telephone Message</a></div>
					<div class="pure-u-1-2"><i class="fa fa-pencil-square-o fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="new_letter">New Letter</a></div>
					<div class="pure-u-1-2"><i class="fa fa-flask fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="new_test_result" class="add_result_class">New Test Result</a></div>
				<?php }?>
				<div class="pure-u-1-2"><i class="fa fa-exclamation-triangle fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="new_alert" class="add_alert">New Alert</a></div>
				<div class="pure-u-1-2"><i class="fa fa-envelope fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="create_patient_message">New Message to Patient</a></div>
				<div class="pure-u-1-2"><i class="fa fa-print fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="print_chart">New Coordination of Care Transaction</a></div>
				<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
					<div class="pure-u-1-2"><i class="fa fa-cc fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="print_ccr">New Continuity of Care Record</a></div>
				<?php }?>
			</div>
		</fieldset><br>
		<fieldset class="ui-corner-all">
			<legend>Actions</legend>
			<div class="pure-g">
				<div class="pure-u-1-2"><i class="fa fa-credit-card fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" class="add_creditcard">Add/Edit Credit Card Information</a></div>
				<?php if(Session::get('patient_centric') == 'n') {?>
					<div class="pure-u-1-2"><i class="fa fa-exchange fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="connect_patient_nosh">Connect to Patient Centric NOSH (Status: <span="connect_patient_nosh_status"><?php echo $url;?></span>)</a></div>
				<?php }?>
			</div>
		</fieldset><br>
		<fieldset class="ui-corner-all">
			<legend>Import</legend>
			<div class="pure-g">
				<div class="pure-u-1-2"><i class="fa fa-file-text-o fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="new_import">Import New Documents</a></div>
				<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
					<div class="pure-u-1-2"><i class="fa fa-cc fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="import_ccr">Import Continuity of Care Record (XML)</a></div>
					<div class="pure-u-1-2"><i class="fa fa-medkit fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="import_ccda">Import C-CDA (Consolidated Clinical Document Architecture)</a></div>
				<?php }?>
				<div class="pure-u-1-2"><i class="fa fa-user fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="chart_import_csv">Import CSV File</a></div>
			</div>
		</fieldset>
	</div>
</div>
<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
	<div id="nosh_encounter_div" style="width:100%">
		<ul id="encounter_tags"></ul>
		<button type="button" id="template_encounter" class="nosh_button_script">Template</button>
		<button type="button" id="preview_encounter" class="nosh_button_preview">Preview</button>
		<button type="button" id="detail_encounter" class="nosh_button_edit">Details</button>
		<button type="button" id="billing_encounter" class="nosh_button_cart">Billing</button>
		<button type="button" id="image_encounter" class="nosh_button_image">Images</button>
		<button type="button" id="copy_encounter" class="nosh_button_copy">Copy</button>
		<button type="button" id="save_draft" class="nosh_button_save">Save Draft</button>
		<button type="button" id="sign_encounter" class="nosh_button_check">Sign</button>
		<button type="button" id="delete_encounter" class="nosh_button_delete">Delete</button>
		<br><br>
		<div id="encounter_body"></div>
	</div>
<?php }?>
<div id="new_encounter_dialog" title="New Encounter">
	<form name="new_encounter_form" id="new_encounter_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="new_encounter_dialog_eid" name="eid">
		<div id="new_encounter_accordion">
			<h3>Define Encounter <span id="detail_encounter_number"></span></h3>
			<div>
				<div class="pure-control-group new_encounter_dialog_encounter_provider_div"><label for="encounter_provider">Encounter Provider:</label><select name="encounter_provider" id="encounter_provider" class="text" required/></select></div>
				<div class="pure-control-group detail_encounter_noshow"><label for="encounter_template">Encounter Template:</label><select name="encounter_template" id="encounter_template" class="text" required title="Select an encounter template.  This cannot be changed once chosen and saved.  If you need to change your template, delete the encounter and create a new one with a different template."/></select></div>
				<div class="pure-control-group"><label for="encounter_cc">Chief Complaint:</label><input type="text" name="encounter_cc" id="encounter_cc" class="text" style="width:350px" required/></div>
				<div class="pure-control-group"><label for="encounter_date">Date of Service:</label><input type="text" name="encounter_date" id="encounter_date" class="text" required/></div>
				<div class="pure-control-group"><label for="encounter_time">Time of Service:</label><input type="text" name="encounter_time" id="encounter_time" class="text" required/></div>
				<div class="pure-control-group"><label for="encounter_location">Encounter Location:</label><input type="text" name="encounter_location" id="encounter_location" class="text" required/></div>
				<div class="pure-control-group detail_encounter_noshow"><label for="encounter_type">Associated Appointment:</label><select name="encounter_type" id="encounter_type" class="text"></select></div>
				<div class="pure-control-group"><label for="encounter_role">Provider Role:</label><select name="encounter_role" id="encounter_role" class="text" required></select></div>
				<div class="pure-control-group referring_provider_div"><label for="referring_provider">Referring Provider:</label><input type="text" name="referring_provider" id="referring_provider" class="text"/></div>
				<div class="pure-control-group referring_provider_div"><label for="referring_provider_npi">Referring Provider NPI:</label><input type="text" name="referring_provider_npi" id="referring_provider_npi" class="text"/></div>
				<div class="pure-control-group"><label for="billing_bill_complex">Complexity of encounter:</label><select name="bill_complex" id="billing_bill_complex" class="text"></select></div>
			</div>
			<h3>Condtion Related To</h3>
			<div>
				<div class="pure-control-group"><label for="encounter_condition_work">Work:</label><select name="encounter_condition_work" id="encounter_condition_work" class="text" required></select></div>
				<div class="pure-control-group"><label for="encounter_condition_auto">Auto Accident:</label><select name="encounter_condition_auto" id="encounter_condition_auto" class="text" required></select></div>
				<div class="pure-control-group"><label for="encounter_condition_auto_state">State Accident Occurred:</label><select name="encounter_condition_auto_state" id="encounter_condition_auto_state" class="text"></select></div>
				<div class="pure-control-group"><label for="encounter_condition_other">Other Accident:</label><select name="encounter_condition_other" id="encounter_condition_other" class="text" required></select></div>
				<div class="pure-control-group"><label for="encounter_condition">Other: </label><input type="text" name="encounter_condition" id="encounter_condition" class="text" /></div>
			</div>
			<h3>Insurance Information</h3>
			<div>
				<div id="encounter_copay"></div>
			</div>
		</div>
	</form>
</div>
<div id="new_import_dialog" title="Import New Documents">
	<div id="new_import_message"></div><br>
	<div id="new_import_fieldset">
		<form name="new_import_form" id="new_import_form" class="pure-form pure-form-aligned">
			<input type="hidden" name="documents_id" id="new_import_documents_id"/>
			<div class="pure-control-group">
				<label for="new_import_documents_from">From:</label>
				<input type="text" name="documents_from" id="new_import_documents_from" style="width:500px" class="text" required/>
			</div>
			<div class="pure-control-group">
				<label for="new_import_documents_type">Document Type:</label>
				<select name="documents_type" id="new_import_documents_type" class="text" required></select>
			</div>
			<div class="pure-control-group">
				<label for="new_import_documents_desc">Description:</label>
				<input type="text" name="documents_desc" id="new_import_documents_desc" style="width:500px" class="text" required/>
			</div>
			<div class="pure-control-group">
				<label for="new_import_document_date">Document Date:</label>
				<input type="text" name="documents_date" id="new_import_documents_date" class="text" required/>
			</div>
		</form>
	</div>
</div>
<div id="preview_dialog" title="Encounter Preview">
	<div id="preview"></div>
</div>
<div id="prevention_list_dialog" title="Prevention Recommendations">
	<div id="prevention_load"><?php echo HTML::image('images/indicator.gif', 'Loading'); ?> Loading...</div>
	<div id="prevention_items"></div>
</div>
<div id="ccda_dialog" title="Reconcile C-CDA">
	<div id="ccda_accordion">
		<h3>Issues</h3>
		<div id="ccda_issues_div">
			<table id="ccda_issues" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="ccda_issues_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="copy_ccda_issues_item" class="nosh_button_add">Copy</button><br><br>
			<table id="nosh_issues" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="nosh_issues_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_nosh_issue" class="nosh_button_add">Add</button>
			<button type="button" id="edit_nosh_issue" class="nosh_button_edit">Edit</button>
			<button type="button" id="inactivate_nosh_issue" class="nosh_button_cancel">Inactivate</button>
		</div>
		<h3>Medications</h3>
		<div id="ccda_medications_div">
			<table id="ccda_medications" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="ccda_medications_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="copy_ccda_medications_item" class="nosh_button_add">Copy</button><br><br>
			<table id="nosh_medications" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="nosh_medications_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_nosh_rx" class="nosh_button_add">Add</button>
			<button type="button" id="edit_nosh_rx" class="nosh_button_edit">Edit</button>
			<button type="button" id="inactivate_nosh_rx" class="nosh_button_cancel">Inactivate</button>
		</div>
		<h3>Allergies</h3>
		<div id="ccda_allergies_div">
			<table id="ccda_allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="ccda_allergies_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="copy_ccda_allergies_item" class="nosh_button_add">Copy</button><br><br>
			<table id="nosh_allergies" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="nosh_allergies_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_nosh_allergy" class="nosh_button_add">Add</button>
			<button type="button" id="edit_nosh_allergy" class="nosh_button_edit">Edit</button>
			<button type="button" id="inactivate_nosh_allergy" class="nosh_button_cancel">Inactivate</button>
		</div>
		<h3>Immunizations</h3>
		<div id="ccda_imm_div">
			<table id="ccda_imm" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="ccda_imm_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="copy_ccda_imm_item" class="nosh_button_add">Copy</button><br><br>
			<table id="nosh_imm" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="nosh_imm_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_nosh_imm" class="nosh_button_add">Add</button>
			<button type="button" id="edit_nosh_imm" class="nosh_button_edit">Edit</button>
		</div>
	</div>
</div>
<div id="csv_dialog" title="Assign the CSV headers to the NOSH database fields...">
	<form class="pure-form pure-form-stacked" id="csv_form"></form>
</div>
<div id="copy_encounter_dialog" title="Copy Encounter">
	<form id="copy_encounter_form" class="pure-form">
		<label for="copy_encounter_from">Copy encounter from:</label>
		<select name="copy_encounter_from" id="copy_encounter_from" style="width:500px" class="text" required></select>
	</form><br>
	<?php echo HTML::image('images/important.png','Important Message', array('border' => '0', 'height' => '40', 'width' => '40'));?>Copying form a encounter will erase all previously entered items for this current encounter!
</div>
<div id="creditcard_dialog" title="Add Credit Card on File">
	<form id="creditcard_form" class="pure-form pure-form-stacked">
		<label for="creditcard_number">Credit Card Number</label>
		<input type="text" style="width:95%" name="creditcard_number" id="creditcard_number" placeholder="Add Card Number Number Here"/>
		<label for="creditcard_type">Credit Card Type</label>
		<select style="width:95%" name="creditcard_type" id="creditcard_type"></select>
		<label for="creditcard_expiration">Expiration Date</label>
		<input type="text" style="width:95%" name="creditcard_expiration" id="creditcard_expiration"/>
	</form>
</div>
<div id="hedis_chart_dialog" title="HEDIS Audit">
	<div id="hedis_chart_question" class="pure-form">
		<label for="hedis_chart_time">Choose Time Period for Audit to Begin:</label>
		<input type="text" style="width:150px" name="hedis_chart_time" id="hedis_chart_time"/>
		<button type="button" id="hedis_chart_spec" class="nosh_button">Specified Time</button>
		<button type="button" id="hedis_chart_all" class="nosh_button">All</button>
		<button type="button" id="hedis_chart_year" class="nosh_button">Past Year</button><br><br>
	</div>
	<div id="hedis_chart_load"><?php echo HTML::image('images/indicator.gif', 'Loading'); ?> Loading...</div>
	<div id="hedis_chart_items"></div>
</div>
<div id="connect_patient_centric_dialog" title="Connect to a Patient Centric NOSH">
	<form id="connect_patient_centric_form" class="pure-form pure-form-stacked">
		<label for="connect_patient_centric_url">Enter the URL in your email that you recevied:</label>
		<input type="text" style="width:95%" name="url" id="connect_patient_centric_url" class="text" required/>
	</form>
</div>
<div id="template_encounter_dialog" title="Encounter Templates">
	<form id="template_encounter_form" class="pure-form pure-form-stacked">
		<label for="template_encounter_choose">Choose template to use for encounter</label>
		<select id="template_encounter_choose" name="template_name" style="width:95%" required></select>
		<label for="textdump_delimiter3">Delimiter for normal values</label>
		<select id="textdump_delimiter3" name="delimiter" required>
			<option value=", ">,</option>
			<option value=" ">space</option>
			<option value="&#13;&#10;">new line</option>
			<option value="; ">;</option>
		</select>
	</form>
</div>
