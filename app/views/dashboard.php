<script type="text/javascript">
	noshdata.pid = '<?php if (Session::get('pid')) {echo Session::get('pid');}?>';
	noshdata.weekends = <?php echo $weekends;?>;
	noshdata.minTime = '<?php echo $minTime;?>';
	noshdata.maxTime = '<?php echo $maxTime;?>';
	noshdata.schedule_increment = '<?php echo $schedule_increment;?>';
</script>
<div id="wrapper">
	<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
		<?php if(Session::get('group_id') != '100') { ?>
			<div id="maincontent">
		<?php } else {?>
			<div>
		<?php }?>
			<?php if(Session::get('group_id') != '100') { ?>
				<h4>Welcome <?php echo $displayname;?>.</h4>
			<?php }?>
			<div class="pure-g">
				<?php if(Session::get('group_id') != '100') { ?>
					<div class="pure-u-1-2">
						<i class="fa fa-key fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="change_password">Change your password.</a>
						<?php if(Session::get('group_id') == '1') { ?>
							<br><i class="fa fa-home fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" class="dashboard_setup">Setup</a>
							<br><i class="fa fa-plug fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_extensions">Extensions</a>
							<?php if(Session::get('patient_centric') == 'n') {?>
								<br><i class="fa fa-exclamation-circle fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_update">Updates</a>
							<?php }?>
							<br><i class="fa fa-users fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_users">Users<?php if($users_needed == 'y') {?><span id="users_needed" style="font-weight:bold;color:red;"> This needs to be setup before using!</span><?php }?></a>
							<?php if(Session::get('patient_centric') == 'n' || Session::get('patient_centric') == 'yp') {?>
								<br><i class="fa fa-clock-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_admin_schedule">Schedule<?php if($schedule_needed == 'y') {?><span id="schedule_needed" style="font-weight:bold;color:red;"> This needs to be setup before using!</span><?php }?></a>
							<?php }?>
							<br><i class="fa fa-list fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_logs">View system logs</a>
							<?php if(Session::get('practice_active') == 'Y') { if(route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh') {?>
								<br><i class="fa fa-times fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="https://noshchartingsystem.com/registerpractice/cancel_subscription/<?php echo Session::get('practice_id'); ?>">Cancel Subscriptions</a>
							<?php }}?>
							<?php if(Session::get('practice_active') == 'N') { if(route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh') {?>
								<br><i class="fa fa-refresh fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="https://noshchartingsystem.com/registerpractice/restart_subscription/<?php echo Session::get('practice_id'); ?>">Restart Subscriptions</a>
							<?php }}?>
						<?php }?>
						<?php if(Session::get('group_id') == '2') {?>
							<br><i class="fa fa-envelope fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
							<br><i class="fa fa-file-text-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
							<br><i class="fa fa-clock-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_appts;?> </span><a href="#" id="dashboard_schedule">pending appointments today.</a>
							<br><i class="fa fa-pencil-square-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_drafts;?> </span><a href="#" class="dashboard_draft">unsigned messages and encounters.</a>
							<br><i class="fa fa-exclamation-triangle fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_reminders;?> </span><a href="#" class="dashboard_alerts">reminders.</a>
							<br><i class="fa fa-flask fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_tests;?> </span><a href="#" class="dashboard_test_reconcile">test results to reconcile.</a>
							<?php if($mtm_alerts_status == "y") {?>
								<br><i class="fa fa-medkit fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $mtm_alerts;?> </span><a href="#" id="provider_mtm_alerts">patients on Medical Therapy Management.</a>
							<?php }?>
							<br><i class="fa fa-money fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to process and send.</a>
							<br><i class="fa fa-user-md fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="provider_info">Update your user information</a>
							<?php if(Session::get('patient_centric') == 'yp') {?>
								<br><i class="fa fa-cogs fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" class="dashboard_setup">Practice Setup</a>
							<?php }?>
							<?php if(route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh') {?>
								<br><i class="fa fa-exchange fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="hieofone_sso">Transfer your credentials to mdNOSH - a Single-Sign-On Solution for Medical Providers</a>
							<?php }?>
						<?php }?>
						<?php if(Session::get('group_id') == '3') {?>
							<br><i class="fa fa-envelope fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
							<br><i class="fa fa-file-text-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
							<br><i class="fa fa-pencil-square-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_drafts;?> </span><a href="#" class="dashboard_draft">unsigned messages.</a>
							<br><i class="fa fa-bell fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_reminders;?> </span><a href="#" class="dashboard_alerts">reminders.</a>
							<br><i class="fa fa-flask fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_tests;?> </span><a href="#" class="dashboard_test_reconcile">test results to reconcile.</a>
							<br><i class="fa fa-money fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to review.</a>
						<?php }?>
						<?php if(Session::get('group_id') == '4') {?>
							<br><i class="fa fa-envelope fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span style="font-weight:bold;color:red;"> You have <?php echo $number_messages;?> </span><a href="#" id="dashboard_messaging" style="font-weight:bold;color:red;">messages to view.</a>
							<br><i class="fa fa-file-text-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_documents;?> </span><a href="#" id="dashboard_documents">new documents from the fax or scanner to view.</a>
							<br><i class="fa fa-clock-o fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> <a href="#" id="dashboard_schedule">Clinic schedule</a>
							<br><i class="fa fa-money fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i><span> You have <?php echo $number_bills;?> </span><a href="#" id="dashboard_billing">new bills to process and send.</a>
						<?php }?>
					</div>
					<div class="pure-u-1-2">
						<?php if(Session::get('group_id') == '1') { ?>
							<i class="fa fa-download fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="print_entire_charts" title="">Export all charts</a> <span id="print_entire_charts_return"></span>
							<br><i class="fa fa-sign-out fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="print_entire_ccda" title="">Export all charts in C-CDA format</a> <span id="print_entire_ccda_return"></span>
							<br><i class="fa fa-list-alt fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="generate_csv_patient_demographics" title="">Export all patient demographics</a> <span id="generate_csv_patient_demographics_return"></span>
							<br><i class="fa fa-cloud-download fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="export_entire" title="">Export entire database and files</a> <span id="export_entire_return"></span>
							<?php if($saas_admin == "y") { ?>
								<br><i class="fa fa-cloud-upload fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="import_entire">Import from NOSH in the Cloud</a>
								<br><i class="fa fa-database fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="restore_database_link">Restore the database</a>
								<?php if(route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh') {?>
									<br><i class="fa fa-wrench fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="manual_cancel_practice">Manually Cancel Practice</a>
								<?php }?>
							<?php }?>
						<?php }?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') { echo $vaccine_supplement_alert; }?>
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
				<?php } else {?>
					<div class="pure-u-1-4">
						<h4>Welcome <?php echo $displayname;?>.</h4>
						<i class="fa fa-envelope fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Send a message to your provider here too!"> <a href="#" id="dashboard_messaging">View your messages.</a></span>
						<?php if(Session::get('patient_centric') == 'n') {?>
							<br><i class="fa fa-calendar fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="dashboard_schedule">Schedule an appointment</a>
						<?php }?>
						<br><i class="fa fa-user fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="patient_demographics">Edit your demographics and insurance.</a>
						<?php if(Session::get('patient_centric') == 'n') {?>
							<br><i class="fa fa-check-square-o fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Your provider may want you to fill out forms for the practice.  Do this here!"> <a href="#" id="dashboard_forms">Fill out forms.</a></span>
						<?php }?>
						<br><i class="fa fa-clock-o fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="View your health history in a timeline fashion."> <a href="#" id="timeline_chart">Your health timeline.</a></span>
						<br><i class="fa fa-user-md fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Your patient instructions for your previous encounters with your provider."> <a href="#" id="dashboard_encounters">Your past office visits.</a></span>
						<br><i class="fa fa-bars fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="These are your active medical issues documented by your provider(s)."> <a href="#" id="dashboard_issues">Your active medical issues.</a></span>
						<br><i class="fa fa-eyedropper fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="These are your active medications documented by your provider(s)."> <a href="#" id="dashboard_rx">Your active medication list.</a></span>
						<br><i class="fa fa-tree fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="These are your active supplements documented by your provider(s)."> <a href="#" id="dashboard_supplements">Your active supplement list.</a></span>
						<br><i class="fa fa-magic fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Your immunization records."> <a href="#" id="dashboard_immunizations">Your immunization records.</a></span>
						<br><i class="fa fa-exclamation-triangle fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Your medication and substance allergy list."> <a href="#" id="dashboard_allergies">Your allergy list.</a></span>
						<br><i class="fa fa-file-text-o fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Your test results, outside records, and other documents."> <a href="#" id="dashboard_health_record">Your personal health record.</a></span>
						<?php if (Session::get('agealldays') <6574.5) {?>
							<br><i class="fa fa-line-chart fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="View growth charts."> <a href="#" id="dashboard_growth_chart">View growth charts.</a></span>
						<?php }?>
						<?php if(Session::get('patient_centric') == 'y') {?>
							<br><i class="fa fa-plus-square fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i><span class="nosh_tooltip" title="Manage practices so that a provider can document their encounters with you.  Mangage apps that are connected to your patient NOSH."> <a href="#" class="dashboard_manage_practice">Your connected practices and apps.</a></span>
						<?php }?>
						<br><i class="fa fa-key fa-fw fa-2x" style="vertical-align:middle;padding:2px"></i> <a href="#" id="change_password">Change your password.</a>
					</div>
					<div class="pure-u-3-4" id="maincontent" style="padding:0px;height:90vh"></div>
				<?php }?>
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
		<h3 class="provider_info_class1"><a href="#">Provider Information</a></h3>
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
<div id="manage_practice_dialog" title="Connected Practices and Apps">
	<div style="margin:15px;"><i class="fa fa-plus fa-fw fa-2x send_uma_invite" style="vertical-align:middle;padding:2px"></i> <a href="#" class="send_uma_invite">Send an invitation to a medical provider to use your chart</a><br></div>
	<table id="manage_practice_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="manage_practice_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="dashboard_delete_practice" class="nosh_button_delete">Remove Practice</button>
</div>
<div id="add_practice_dialog" title="Add a Practice">
	<form id="add_practice_form" class="pure-form pure-form-stacked">
		<label for="add_practice_practice_name">Provider/Practice Name (for search):</label>
		<input type="text" id="add_practice_practice_name" name="practice_name" style="width:95%" class="text"/>
		<label for="add_practice_state">Practice State (for search):</label>
		<select id="add_practice_state" name="practice_state" style="width:95%" class="text"></select>
		<label for="add_practice_npi">NPI of Practice:</label>
		<input type="text" id="add_practice_npi" name="npi" style="width:95%" class="text" required/>
		<label for="add_practice_email">Email address:</label>
		<input type="text" id="add_practice_email" name="email" style="width:95%" class="text" required/>
		<label for="add_practice_practice_url">Practice Portal URL (optional):</label>
		<input type="text" id="add_practice_practice_url" name="practice_url" style="width:95%" class="text"/>
	</form>
</div>
<div id="manual_cancel_practice_dialog" title="Manually Cancel Practice">
	<form id="manual_cancel_practice_form"  class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="manual_cancel_practice_list">Pick practice to cancel:</label>
			<select id="manual_cancel_practice_list" class="text"></select>
		</div>
		<button type="button" id="manual_cancel_practice_button">Select</button>
	</form>
</div>
<div id="hieofone_dialog" title="Transfer credentials to mdNOSH Gateway">
	By clicking OK, your login credentials will be transferred automatically to mdNOSH Gateway.<br><br>
	By using mdNOSH Gateway as your login provider, you will be able to use your username and password to any PatientNOSH instances.<br><br>
	<a href="https://noshchartingsystem.com/nosh-sso" target="_blank">Click here for more information</a>.<br><br>
	<div id="hieofone_error"></div>
	<div id="hieofone_username_div">
		<form class="pure-form">
			<label for="hieofone_username">Select new username:</label>
			<input type="text" id="hieofone_username" name="hieofone_username" style="width:95%" class="text"/>
		</form>
	</div>
</div>
<div id="send_uma_invite_dialog" title="Invite a Provider to Access your Chart">
	<div id="send_uma_invite_div1">
		<form id="send_uma_invite_form1" class="pure-form">
			<label for="mdnosh_provider_search_input">Practice/Provider Name (for search):</label>
			<input type="text" id="mdnosh_provider_search_input" name="term" style="width:60%" class="text" required/>
			<button type="button" id="send_uma_invite_form1_submit" class="nosh_button_search">Search</button>
		</form><br>
		<div id="send_uma_invite_results"></div>
	</div>
	<div id="send_uma_invite_div2">
		<h3>Invite this provider:</h3>
		<div id="send_uma_invite_provider"></div>
		<h3>With access to these resources:</h3>
		<form id="send_uma_invite_form2" class="pure-form">
			<input type="hidden" id="mdnosh_email_final" name="email" required/>
			<div id="send_uma_invite_div3"></div>
		</form>
	</div>
</div>
