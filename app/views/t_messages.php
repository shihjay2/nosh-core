<div id="messages_list_dialog" title="Messages">
	<button type="button" class="nosh_button_add new_telephone_message">New Message</button> 
	<br><br>
	<table id="messages" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="messages_main_dialog" title="Message">
	<ul class="t_messages_tags"></ul>
	<form name="edit_message_form" id="edit_message_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="t_messages_id" id="t_messages_id"/>
		<div class="pure-g">
			<div class="pure-u-2-3">
				<label for="t_messages_subject">Subject:</label><input type="text" name="t_messages_subject" id="t_messages_subject" style="width:450px" class="text" required/>
				<label for="t_messages_dos">Date of Service:</label><input type="text" name="t_messages_dos" id="t_messages_dos" class="text" required/>
				<label for="t_messages_to">To:</label><input type="text" name="t_messages_to" id="t_messages_to" style="width:450px"class="text"/>
				<label for="t_messages_message">Message:</label><textarea name="t_messages_message" id="t_messages_message" rows="9" style="width:450px" class="text" required></textarea>
			</div>
			<div class="pure-u-1-3">
				<button type="button" id="message_telephone" style="width:140px" class="nosh_button">Phone</button><br>
				<button type="button" id="message_rx" style="width:140px" class="nosh_button">RX</button><br>
				<button type="button" id="message_sup" style="width:140px" class="nosh_button">Supplements</button><br>
				<button type="button" id="message_lab" style="width:140px" class="nosh_button">Lab</button><br>
				<button type="button" id="message_rad" style="width:140px" class="nosh_button">Imaging</button><br>
				<button type="button" id="message_cp" style="width:140px" class="nosh_button">Cardiopulmonary</button><br>
				<button type="button" id="message_ref" style="width:140px" class="nosh_button">Referral</button><br>
				<button type="button" id="message_reply" style="width:140px" class="nosh_button">Results</button>
			</div>
		</div>
	</form>
</div>
<div id="messages_view_dialog" title="View Message">
	<?php if(Session::get('group_id') != '100') { ?>
		<ul class="t_messages_tags"></ul>
	<?php }?>
	<div id="message_view"></div>
</div>
<div id="messages_telephone_dialog" title="Message Helper">
	<form name="edit_message_telephone_form" id="edit_message_telephone_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group"><label for="message_subjective">Subjective:</label><textarea name="message_subjective" id="message_subjective" rows="8" style="width:500px" class="text"></textarea></div>
		<div class="pure-control-group"><label for="message_assessment">Assessment:</label><input type="text" name="message_assessment" id="message_assessment" style="width:500px" class="text"/></div>
		<div class="pure-control-group"><label for="message_plan">Plan:</label><textarea name="message_plan" id="message_plan" rows="8" style="width:500px" class="text"></textarea></div>
	</form>
</div>
<div id="messages_reply_dialog" title="Results Correspondence">
	<table id="messages_reply_alerts" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_reply_alerts_pager1" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="complete_message_reply_alert" class="nosh_button_check">Mark as Completed</button>
	<button type="button" id="t_message_test_results" class="nosh_button_copy">Test Results</button><br><br>
	<form name="edit_message_reply_form" id="edit_message_reply_form" class="pure-form pure-form-aligned">
		<fieldset class="ui-corner-all">
			<legend>Results Message</legend>
			<div class="pure-control-group"><label for="message_reply_tests_performed">Tests Performed:</label><textarea name="message_reply_tests_performed" id="message_reply_tests_performed" rows="4" style="width:500px" class="text"></textarea></div>
			<div class="pure-control-group"><label for="message_reply_message">Message to Patient:</label><textarea name="message_reply_message" id="message_reply_message" rows="4" style="width:500px" class="text"></textarea></div>
			<div class="pure-control-group"><label for="message_reply_followup">Followup:</label><input type="text" name="message_reply_followup" id="message_reply_followup" style="width:500px" class="text"/></div>
		</fieldset>
	</form>
</div>
