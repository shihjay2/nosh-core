<div id="users_dialog" title="Administer Users">
	<div id="users_accordion">
		<h3>Active Users</h3>
		<div>
			<?php if (route('home') == 'https://noshchartingsystem.com/nosh') {?>
				<div id="practice_upgrade1" style="display:none;"><a href="https://noshchartingsystem.com/registerpractice/upgrade/<?php echo Session::get('practice_id');?>">Upgrade your practice for more providers.</a></div>
			<?php }?>
			<table id="provider_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="provider_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_provider" class="nosh_button_add">Add Provider</button>
			<button type="button" id="edit_provider" class="nosh_button_edit">Edit Provider</button>
			<button type="button" id="disable_provider" class="nosh_button_cancel">Inactivate Provider</button>
			<button type="button" id="reset_password_provider" class="nosh_button">Reset Password</button><br><br>
			<table id="assistant_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="assistant_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_assistant" class="nosh_button_add">Add Assistant</button>
			<button type="button" id="edit_assistant" class="nosh_button_edit">Edit Assistant</button>
			<button type="button" id="disable_assistant" class="nosh_button_cancel">Inactivate Assistant</button>
			<button type="button" id="reset_password_assistant" class="nosh_button">Reset Password</button><br><br>
			<table id="billing_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="billing_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_billing" class="nosh_button_add">Add Biller</button>
			<button type="button" id="edit_billing" class="nosh_button_edit">Edit Biller</button>
			<button type="button" id="disable_billing" class="nosh_button_cancel">Inactivate Biller</button>
			<button type="button" id="reset_password_billing" class="nosh_button">Reset Password</button><br><br>
			<table id="patient_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="patient_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_patient" class="nosh_button_add">Add Patient</button>
			<button type="button" id="edit_patient" class="nosh_button_edit">Edit Patient</button>
			<button type="button" id="disable_patient" class="nosh_button_cancel">Inactivate Patient</button>
			<button type="button" id="reset_password_patient" class="nosh_button">Reset Password</button><br><br>
		</div>
		<h3>Inactive Users</h3>
		<div>
			<?php if (route('home') == 'https://noshchartingsystem.com/nosh') {?>
				<div id="practice_upgrade1" style="display:none;"><a href="https://noshchartingsystem.com/registerpractice/upgrade/<?php echo Session::get('practice_id');?>">Upgrade your practice for more providers.</a></div>
			<?php }?>
			<table id="provider_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="provider_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="view_provider" class="nosh_button">View Details</button>
			<button type="button" id="enable_provider" class="nosh_button_reactivate">Reactivate Provider</button><br><br>
			<table id="assistant_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="assistant_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="view_assistant" class="nosh_button">View Details</button>
			<button type="button" id="enable_assistant" class="nosh_button_reactivate">Reactivate Assistant</button><br><br>
			<table id="billing_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="billing_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="view_billing" class="nosh_button">View Details</button>
			<button type="button" id="enable_billing" class="nosh_button_reactivate">Reactivate Biller</button><br><br>
			<table id="patient_list_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="patient_list_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="view_patient" class="nosh_button">View Details</button>
			<button type="button" id="enable_patient" class="nosh_button_reactivate">Reactivate Patient</button><br><br>
		</div>
	</div>
</div>
<div id="reset_password_dialog" title="Reset Password">
	<form id="reset_password_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="id" id="user_id"/>
		<label for="reset_password_password">New Password:</label><input type="password" name="password" id="reset_password_password" style="width:164px" class="text"/>
	</form>
</div>
