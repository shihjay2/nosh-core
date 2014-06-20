<script type="text/javascript">
	noshdata.pid = '<?php if (Session::get('pid')) {echo Session::get('pid');}?>';
	noshdata.weekends = <?php echo $weekends;?>;
	noshdata.minTime = '<?php echo $minTime;?>';
	noshdata.maxTime = '<?php echo $maxTime;?>';
	noshdata.schedule_increment = '<?php echo $schedule_increment;?>';
</script>
<div id="wrapper">
	<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
		<div id="maincontent">
			<h4>Welcome <?php echo $displayname;?>.</h4>
			<div class="pure-g">
				<div class="pure-u-1-2">
					<?php echo HTML::image('images/usersadmin.png', 'Change password', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;')); ?><span>If this is your first time logging in, you should </span><a href="#" id="change_password">change your password.</a>
					<?php if(Session::get('group_id') == '1') { ?>
						<br><?php echo HTML::image('images/dashboard.png', 'Modify clinic settings', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_setup">Modify practice settings</a>
						<br><?php echo HTML::image('images/control.png', 'Administer NOSH extensions', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_extensions">Administer NOSH extensions</a>
						<br><?php echo HTML::image('images/important.png', 'System updates', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_update">System updates</a>
						<br><?php echo HTML::image('images/usersadmin.png', 'Administer users', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_users">Administer users</a>
						<br><?php echo HTML::image('images/schedule.png', 'Setup schedule', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_admin_schedule">Administer schedule</a>
						<br><?php echo HTML::image('images/newencounter.png', 'View system logs', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_logs">View system logs</a>
						<br><?php echo HTML::image('images/download.png', 'Export all charts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="print_entire_charts" title="">Export all charts</a> <span id="print_entire_charts_return"></span>
						<br><?php echo HTML::image('images/download.png', 'Export all charts in C-CDA format', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="print_entire_ccda" title="">Export all charts in C-CDA format</a> <span id="print_entire_ccda_return"></span>
						<br><?php echo HTML::image('images/download.png', 'Export all patient Demographics', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="generate_csv_patient_demographics" title="">Export all patient demographics</a> <span id="generate_csv_patient_demographics_return"></span>
						<br><?php echo HTML::image('images/download.png', 'Export entire NOSH database and files', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="export_entire" title="">Export entire database and files</a> <span id="export_entire_return"></span>
						<?php if($saas_admin == "y") { ?>
							<br><?php echo HTML::image('images/kdisknav.png', 'Import from NOSH in the Cloud', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="import_entire">Import from NOSH in the Cloud</a>
							<br><?php echo HTML::image('images/kdisknav.png', 'Restore the database', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="restore_database_link">Restore the database</a>
						<?php }?>
						<?php if(Session::get('practice_active') == 'Y' && route('home') == 'https://noshchartingsystem.com/nosh') { ?>
							<br><?php echo HTML::image('images/cancel.png', 'Cancel subscription', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="https://noshchartingsystem/registerpractice/cancel_subscription/' . Session::get('practice_id') . '">Cancel Subscriptions</a>
						<?php }?>
						<?php if(Session::get('practice_active') == 'N' && route('home') == 'https://noshchartingsystem.com/nosh') { ?>
							<br><?php echo HTML::image('images/button_accept.png', 'Restart subscription', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="https://noshchartingsystem/registerpractice/restart_subscription/' . Session::get('practice_id') . '">Restart Subscriptions</a>
						<?php }?>
					<?php }?>
					<?php if(Session::get('group_id') == '2') {?>
						<br><?php echo HTML::image('images/personal.png', 'Update your user information', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="provider_info">Update your user information</a>
						<br><?php echo HTML::image('images/email.png', 'Messages', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
						<br><?php echo HTML::image('images/scanner.png', 'Documents', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
						<br><?php echo HTML::image('images/schedule.png', 'Appointments', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_appts;?> </span><a href="#" id="dashboard_schedule">pending appointments today.</a>
						<br><?php echo HTML::image('images/chart1.png', 'Drafts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_drafts;?> </span><a href="#" class="dashboard_draft">unsigned messages and encounters.</a>
						<br><?php echo HTML::image('images/important.png', 'Alerts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_reminders;?> </span><a href="#" class="dashboard_alerts">reminders.</a>
						<br><?php echo HTML::image('images/science.png', 'Reconcile Tests', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_tests;?> </span><a href="#" class="dashboard_test_reconcile">test results to reconcile.</a>
						<?php if($mtm_alerts_status == "y") {?>
							<br><?php echo HTML::image('images/search.png', 'MTM', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $mtm_alerts;?> </span><a href="#" id="provider_mtm_alerts">patients on Medical Therapy Management.</a>
						<?php }?>
						<br><?php echo HTML::image('images/billing.png', 'Billing', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to process and send.</a>
					<?php }?>
					<?php if(Session::get('group_id') == '3') {?>
						<br><?php echo HTML::image('images/email.png', 'Messages', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
						<br><?php echo HTML::image('images/scanner.png', 'Documents', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
						<br><?php echo HTML::image('images/chart1.png', 'Drafts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_drafts;?> </span><a href="#" class="dashboard_draft">unsigned messages.</a>
						<br><?php echo HTML::image('images/important.png', 'Alerts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_reminders;?> </span><a href="#" class="dashboard_alerts">reminders.</a>
						<br><?php echo HTML::image('images/science.png', 'Reconcile Tests', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_tests;?> </span><a href="#" class="dashboard_test_reconcile">test results to reconcile.</a>
						<br><?php echo HTML::image('images/billing.png', 'Billing', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to review.</a>
					<?php }?>
					<?php if(Session::get('group_id') == '4') {?>
						<br><?php echo HTML::image('images/email.png', 'Messages', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
						<br><?php echo HTML::image('images/scanner.png', 'Documents', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
						<br><?php echo HTML::image('images/schedule.png', 'Appointments', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> <a href="#" id="dashboard_schedule">Clinic schedule</a>
						<br><?php echo HTML::image('images/billing.png', 'Billing', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to process and send.</a>
					<?php }?>
					<?php if(Session::get('group_id') == '100') {?>
						<br><?php echo HTML::image('images/personal.png', 'Update demographics', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="patient_demographics">Update your demographic and insurance information</a>
						<br><?php echo HTML::image('images/schedule.png', 'Schedule an appointment', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?> <a href="#" id="dashboard_schedule">Schedule an appointment</a>
						<br><?php echo HTML::image('images/email.png', 'Messages', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Send a message to your provider here too!"> <a href="#" id="dashboard_messaging">View your messages.</a></span>
						<br><?php echo HTML::image('images/sign.png', 'Forms', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Your provider may want you to fill out forms for the practice.  Do this here!"> <a href="#" id="dashboard_forms">Fill out forms.</a></span>
						<br><?php echo HTML::image('images/reminder.png', 'Documents', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Your patient instructions for your previous encounters with your provider."> <a href="#" id="dashboard_encounters">Your patient instructions.</a></span>
					<?php }?>
				</div>
				<div class="pure-u-1-2">
					<?php if(Session::get('group_id') == '100') {?>
						<?php echo HTML::image('images/chart.png', 'Issues', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="These are your active medical issues documented by your provider(s)."> <a href="#" id="dashboard_issues">Your active medical issues.</a></span>
						<br><?php echo HTML::image('images/rx.png', 'Medications', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="These are your active medications documented by your provider(s)."> <a href="#" id="dashboard_rx">Your active medication list.</a></span>
						<br><?php echo HTML::image('images/supplements.png', 'Supplements', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="These are your active supplements documented by your provider(s)."> <a href="#" id="dashboard_supplements">Your active supplement list.</a></span>
						<br><?php echo HTML::image('images/immunization.png', 'Immunizations', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Your immunization records."> <a href="#" id="dashboard_immunizations">Your immunization records.</a></span>
						<br><?php echo HTML::image('images/important.png', 'Allergies', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Your medication and substance allergy list."> <a href="#" id="dashboard_allergies">Your allergy list.</a></span>
						<br><?php echo HTML::image('images/chart1.png', 'Documents', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="Your test results, outside records, and other documents."> <a href="#" id="dashboard_health_record">Your personal health record.</a></span>
						<?php if (Session::get('agealldays') <6574.5) {?>
							<br><?php echo HTML::image('images/plot.png', 'Growth charts', array('border' => '0', 'height' => '40', 'width' => '40', 'style' => 'vertical-align:middle;'));?><span class="nosh_tooltip" title="View growth charts."> <a href="#" id="dashboard_growth_chart">View growth charts.</a></span>
						<?php }?>
					<?php }?>
					<div id="draft_div" style="display:none;">
						<table id="draft_messages" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="draft_messages_pager" class="scroll" style="text-align:center;"></div><br>
						<table id="draft_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="draft_encounters_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
					<div id="alert_div" style="display:none;">
						<table id="dashboard_alert" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="dashboard_alert_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
					<div id="mtm_alert_div" style="display:none;">
						<table id="dashboard_mtm_alert" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="dashboard_mtm_alert_pager" class="scroll" style="text-align:center;"></div><br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="change_password_dialog" title="Change Password">
	<form id="change_password_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="old_password">Old Password</label>
			<input type="password" name="old_password" id="old_password" class="text" />
		</div>
		<div class="pure-control-group">
			<label for="new_password">New Password</label>
			<input type="password" name="new_password" id="new_password" class="text" />
		</div>
		<div class="pure-control-group">
			<label for="new_password2">Confirm New Password</label>
			<input type="password" name="new_password2" id="new_password2" class="text" />
		</div>
		<div class="pure-control-group">
			<label for="secret_question">Secret Question</label>
			<select name="secret_question" id="secret_question" class="text"></select>
		</div>
		<div class="pure-control-group">
			<label for="secret_answer">Secret Answer</label>
			<input type="text" name="secret_answer" id="secret_answer" class="text">
		</div>
	</form>
</div>
<div id="change_secret_answer_dialog" title="First Time Secert Question/Answer Setup">
	<form id="change_secret_answer_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="secret_question1">Secret Question</label>
			<select name="secret_question" id="secret_question1" class="text"></select>
		</div>
		<div class="pure-control-group">
			<label for="secret_answer1">Secret Answer</label>
			<input type="text" name="secret_answer" id="secret_answer1" class="text">
		</div>
	</form>
</div>
<div id="restore_database_dialog" title="Restore Database">
	<form id="restore_database_form"  class="pure-form pure-form-aligned">
	<div class="pure-control-group">
		<label for="backup_select">Pick one to restore:</label>
		<select id="backup_select" class="text"></select>
	</div>
	<button type="button" id="restore_backup_button">Select</button>
	</form>
</div>
<div id="provider_info_dialog" title="Edit Provider Information">
	<div id="provider_info_accordion">
		<h3 class="provider_info_class1"><a href="#">Accounts</a></h3>
		<div class="provider_info_class1">
			<form name="provider_info_form" id="provider_info_form" class="pure-form pure-form-aligned">
				<input type="hidden" name="id" id="provider_info_id"/>
				<div class="pure-control-group">
					<label for="provider_info_specialty">Specialty:</label>
					<input type="text" name="specialty" id="provider_info_specialty" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_license">License Number:</label>
					<input type="text" name="license" id="provider_info_license" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_license_state">State Licensed:</label>
					<select name="license_state" id="provider_info_license_state" style="width:200px" class="text"></select>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_npi">NPI:</label>
					<input type="text" name="npi" id="provider_info_npi" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_upin">UPIN:</label>
					<input type="text" name="upin" id="provider_info_upin" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_dea">DEA Number:</label>
					<input type="text" name="dea" id="provider_info_dea" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_medicare">Medicare Number:</label>
					<input type="text" name="medicare" id="provider_info_medicare" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_tax_id">Tax ID Number:</label>
					<input type="text" name="tax_id" id="provider_info_tax_id" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group" id="rcopia_username_div">
					<label for="provider_info_rcopia_username">RCopia Username:</label>
					<input type="text" name="rcopia_username" id="provider_info_rcopia_username" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_peacehealth_id">PeaceHealth Labs ID:</label>
					<input type="text" name="peacehealth_id" id="provider_info_peacehealth_id" style="width:200px" class="text"/>
				</div>
				<div class="pure-control-group">
					<label for="provider_info_schedule_increment">Time increments for schedule (minutes):</label>
					<input type="text" name="schedule_increment" id="provider_info_schedule_increment" style="width:200px" class="text"/>
				</div>
			</form>
		</div>
		<h3 class="provider_info_class2"><a href="#">Signature</a></h3>
		<div class="provider_info_class2">
			Preview Signature:<br>
			<div id="preview_signature"></div><br>
			<div class="pure-g">
				<div class="pure-u-1-2">
					<strong>Draw It</strong>
					<form id="signature_form" class="pure-form pure-form-aligned sigPad">
						<label for="name">Print your name for verification.</label>
						<input type="text" name="name" id="name" class="name" placeholder="{First name} {Last name}">
						<p class="drawItDesc">Draw your signature</p>
						<ul class="sigNav">
							<li class="drawIt"><a href="#draw-it">Draw It</a></li>
							<li class="clearButton"><a href="#clear">Clear</a></li>
						</ul>
						<div class="sig sigWrapper">
							<div class="typed"></div>
							<canvas class="pad" width="198" height="55"></canvas>
							<input type="hidden" name="output" class="output">
						</div>
					</form>
					<br><button type="button" id="change_signature">Save signature</button>
				</div>
				<div class="pure-u-1-2">
					<strong>Upload It</strong>
					<div id="signature_message"></div>
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<button type="button" id="signature_upload_submit" class="nosh_button_add">Upload Signature</button><br><br>
				</div>
			</div>
		</div>
		<h3 class="provider_info_class3"><a href="#">Visit Types</a></h3>
		<div class="provider_info_class3">
			<table id="provider_visit_type_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="provider_visit_type_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_provider_visit_type" class="nosh_button_add">Add</button>
			<button type="button" id="edit_provider_visit_type" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_provider_visit_type" class="nosh_button_delete">Delete</button>
		</div>
	</div>
</div>
<div id="tests_reconcile_dialog" title="Test Reconciliation">
	<table id="tests_reconcile_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="tests_reconcile_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="reconcile_tests">Reconcile</button><button type="button" id="delete_tests" class="nosh_button_delete">Delete</button>
	<div id="reconcile_tests_div" style="display:none">
		<form class="pure-form">
			<br><br>
			<input type="hidden" id="reconcile_tests_pid"/>
			<label for="reconcile_test_patient_search1">Choose patient:</label><input type="text" id="reconcile_test_patient_search1" style="width:300px" class="text" />
			<button type="button" id="reconcile_tests_send" class="nosh_button_save">Import</button>
			<button type="button" id="reconcile_tests_cancel" class="nosh_button_cancel">Cancel</button>
		</form>
	</div>
</div>
