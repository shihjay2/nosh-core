<div id="messaging_dialog" title="Messages">
	<div id="messaging_accordion">
		<h3>Practice Messaging</h3>
		<div>
			<button type="button" id="new_internal_message" class="nosh_button_add">New Message</button><br><br>
			<table id="internal_inbox" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="internal_inbox_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="internal_draft" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="internal_draft_pager" class="scroll" style="text-align:center;"></div><br>
			<table id="internal_outbox" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="internal_outbox_pager" class="scroll" style="text-align:center;"></div>
		</div>
		<?php if(Session::get('group_id') != '100') {?>
			<?php if(Session::get('patient_centric') == 'n') {?>
				<?php if($fax == true) {?>
					<h3>Faxes</h3>
					<div>
						<button type="button" id="new_fax" class="nosh_button_add">New Fax</button><br><br>
						<table id="received_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="received_faxes_pager" class="scroll" style="text-align:center;"></div><br>
						<button type="button" id="delete_fax" class="nosh_button_delete">Delete Selected</button><br><br>
						<table id="draft_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="draft_faxes_pager" class="scroll" style="text-align:center;"></div><br>
						<table id="sent_faxes" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="sent_faxes_pager" class="scroll" style="text-align:center;"></div>
					</div>
				<?php }?>
				<h3>Scans</h3>
				<div>
					<table id="received_scans" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="received_scans_pager" class="scroll" style="text-align:center;"></div><br>
					<button type="button" id="savescan" class="nosh_button_save">Import Selected Scan</button>
					<button type="button" id="delete_scan" class="nosh_button_delete">Delete Selected Scan</button>
				</div>
			<?php }?>
			<h3>Address Book</h3>
			<div>
				<div class="pure-g">
					<div class="pure-form pure-u-4-5">
						<label for="search_all_contact">Search:</label><input type="text" size="50" id="search_all_contact" class="text" onkeydown="doSearch1(arguments[0]||event)"/><br><br> 
					</div>
					<div class="pure-u-1-5">
						<button type="button" id="export_address_csv" class="nosh_button" style="width:125px">Export CSV File</button><br><button type="button" id="import_csv" class="nosh_button" style="width:125px">Import CSV File</button> 
					</div>
				</div>
				<br>
				<table id="all_contacts_list" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="all_contacts_list_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="messaging_add_contact" class="nosh_button_add">Add Contact</button>
				<button type="button" id="messaging_edit_contact" class="nosh_button_edit">Edit Contact</button>
				<button type="button" id="messaging_delete_contact" class="nosh_button_delete">Delete Contact</button>
			</div>
		<?php }?>
	</div>
</div>
<div id="internal_messages_dialog" title="Internal Message">
	<form name="internal_messages_form" id="internal_messages_form_id" class="pure-form pure-form-aligned">
		<input type="hidden" name="message_id" id="messages_message_id"/>
		<input type="hidden" name="pid" id="messages_pid">
		<input type="hidden" name="t_messages_id" id="messages_t_messages_id">
		<div class="pure-control-group">
			<label for="messages_subject">Subject:</label>
			<input type="text" name="subject" id="messages_subject" style="width:400px" class="text"/>
		</div>
		<?php if(Session::get('group_id') != '100') {?>
			<div class="pure-control-group">
				<label for="messages_patient">Concerning this patient (optional):</label>
				<input type="text" name="patient_name" id="messages_patient" style="width:400px" class="text"/>
			</div>
		<?php }?>
		<div class="pure-control-group">
			<label for="messages_to">To:</label>
			<select id="messages_to" name="message_to[]" style="width:400px" multiple="multiple" class="text multiselect"></select>
		</div>
		<div class="pure-control-group">
			<label for="messages_cc">CC:</label>
			<select id="messages_cc" name="cc[]" style="width:400px" multiple="multiple" class="text multiselect"></select>
		</div>
		<div class="pure-control-group">
			<label for="messages_body">Message:</label>
			<textarea name="body" id="messages_body" rows="12" style="width:400px" class="text"></textarea>
		</div>
	</form>
</div>
<div id="internal_messages_view_dialog" title="Internal Message">
	<button type="button" id="reply_message" class="nosh_button_reply">Reply</button>
	<button type="button" id="reply_all_message" class="nosh_button_reactivate">Reply All</button>
	<button type="button" id="forward_message" class="nosh_button_forward">Forward</button>
	<?php if(Session::get('group_id') != '100') {?>
		<button type="button" id="internal_open_chart" class="nosh_button">Open Chart</button>
		<button type="button" id="export_message" class="nosh_button_copy">Export to Patient Chart</button>
	<?php }?>
	<br>
	<input type="hidden" id="message_view_message_id">
	<input type="hidden" id="message_view_to">
	<input type="hidden" id="message_view_from">
	<input type="hidden" id="message_view_cc">
	<input type="hidden" id="message_view_patient_name">
	<input type="hidden" id="message_view_rawtext">
	<input type="hidden" id="message_view_t_messages_id">
	<input type="hidden" id="message_view_documents_id">
	<form id="internal_messages_view_form">
		<input type="hidden" name="t_messages_subject" id="message_view_subject">
		<input type="hidden" name="t_messages_message" id="message_view_body">
		<input type="hidden" name="t_messages_date" id="message_view_date">
		<input type="hidden" name="pid" id="message_view_pid">
	</form>
	<div id="message_view1"></div>
</div>
<div id="internal_messages_view2_dialog" title="Internal Message">
	<?php if(Session::get('group_id') != '100') {?>
		<button type="button" id="export_message1" class="nosh_button_copy">Export to Patient Chart</button><br>
	<?php }?>
	<form id="internal_messages_view2_form">
		<input type="hidden" name="t_messages_subject" id="message_view_subject1">
		<input type="hidden" name="t_messages_message" id="message_view_body1">
		<input type="hidden" name="t_messages_date" id="message_view_date1">
		<input type="hidden" name="pid" id="message_view_pid1">
	</form>
	<div id="message_view2"></div>
</div>
<div id="messaging_fax_dialog" title="Send Fax">
	<div id="messaging_fax_accordion">
		<h3>Recipients</h3>
		<div>
			<div class="pure-form"><label for="quick_search_contact">Search:</label><input type="text" style="width:200px" id="quick_search_contact" class="text"/></div><br><br>
			<table id="send_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="send_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="addrecipient" class="nosh_button_add">Add</button>
			<button type="button" id="editrecipient" class="nosh_button_edit">Edit</button>
			<button type="button" id="removerecipient" class="nosh_button_delete">Remove</button>
		</div>
		<h3>Pages</h3>
		<div>
			<div class="pure-g">
				<div class="pure-u-2-3">
					<table id="pages_list" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="pages_list_pager" class="scroll" style="text-align:center;"></div>
				</div>
				<div class="pure-u-1-3">
					<button type="button" id="addfile" class="nosh_button_add">Add</button><br>
					<button type="button" id="viewpage" class="nosh_button_preview">View Page</button><br>
					<button type="button" id="delfile" class="nosh_button_delete">Remove Page</button>
				</div>
			</div>
		</div>
		<h3>Details</h3>
		<div>
			<form id="sendfinal" class="pure-form pure-form-aligned">
				<div class="pure-control-group"><label for="faxsubject">Subject:</label><input type="text" name="faxsubject" id="faxsubject" style="width:200px" class="text"/></div> 
				<div class="pure-control-group"><label for="faxcoverpage">Coverpage:</label><input type="checkbox" name="faxcoverpage" id="faxcoverpage" class="text" value="yes"/></div>
				<div class="pure-control-group formmessagecoverpage"><label for="faxmessage">Message for Coverpage:</label><textarea style="width:200px" rows="2" name="faxmessage" id="faxmessage" class="text ui-widget-content ui-corner-all"></textarea></div>
			</form>
		</div>
	</div>
</div>
<div id="fax_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_received_id"/>
	<input type="hidden" id="fax_filepath"/>
	<div class="pure-g">
		<div class="pure-u-1-2">
			<button type="button" id="save_fax" class="nosh_button_extlink">Download</button>
			<button type="button" id="import_fax" class="nosh_button_save">Import</button>
		</div>
		<div class="pure-u-1-2 pure-form">
			<label for="import_fax_pages">Select Pages (leave blank for all):</label><input type="text" style="width:100px" id="import_fax_pages"/>
		</div>
	</div>
	<br>
	<div id="embedURL1"></div>
</div>
<div id="pages_view_dialog" title="Pages Viewer">
	<input type="hidden" id="pages_view_filepath"/>
	<div id="embedURL2"></div>
</div>
<div id="fax_import_dialog" title="Import Fax">
	<div id="fax_import_message"></div><br>
	<form name="fax_import_form" id="fax_import_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="received_id" id="fax_received_id"/>
		<input type="hidden" name="pid" id="fax_pid"/>
		<input type="hidden" name="fax_import_pages" id="fax_import_pages"/>
		<div class="pure-control-group"><label for="fax_import_patient_search">Choose Patient:</label><input type="text" name="patient_search" id="fax_import_patient_search" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for="fax_import_documents_from">From:</label><input type="text" name="documents_from" id="fax_import_documents_from" style="width:500px" class="text" /></div>
		<div class="pure-control-group"><label for="fax_import_documents_type">Document Type:</label><select name="documents_type" id="fax_import_documents_type" class="text" required></select></div>
		<div class="pure-control-group"><label for="fax_import_documents_desc">Description:</label><input type="text" name="documents_desc" id="fax_import_documents_desc" style="width:500px" class="text" /></div>
		<div class="pure-control-group"><label for="fax_import_document_date">Document Date:</label><input type="text" name="documents_date" id="fax_import_documents_date" class="text" required/></div>
	</form>
</div>
<div id="scan_import_dialog" title="Import Scanned Document">
	<div id="scan_import_message"></div><br>
	<form name="scan_import_form" id="scan_import_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="scans_id" id="scan_scans_id"/>
		<input type="hidden" name="pid" id="scan_pid"/>
		<input type="hidden" name="scan_import_pages" id="scan_import_pages"/>
		<div class="pure-control-group"><label for="scan_import_patient_search">Choose Patient:</label><input type="text" name="patient_search" id="scan_patient_search" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for="scan_import_documents_from">From:</label><input type="text" name="documents_from" id="scan_import_documents_from" style="width:500px" class="text" /></div>
		<div class="pure-control-group"><label for="scan_import_documents_type">Document Type:</label><select name="documents_type" id="scan_import_documents_type" class="text" required></select></div>
		<div class="pure-control-group"><label for="scan_import_documents_desc">Description:</label><input type="text" name="documents_desc" id="scan_import_documents_desc" style="width:500px" class="text" /></div>
		<div class="pure-control-group"><label for="scan_import_document_date">Document Date:</label><input type="text" name="documents_date" id="scan_import_documents_date" class="text" required/></div>
	</form>
</div>
<div id="scan_view_dialog" title="Documents Viewer">
	<input type="hidden" id="view_scans_id"/>
	<input type="hidden" id="scan_filepath"/>
	<button type="button" id="save_scan" class="nosh_button_extlink">Download</button>
	<button type="button" id="import_scan" class="nosh_button_save">Import</button><br>
	<div class="pure-form">
		<label for="import_scan_pages">Select Pages (leave blank for all):</label><input type="text" style="width:100px" id="import_scan_pages"/>
	</div>
	<div id="embedURL3"></div>
</div>
<div id="contacts_dialog" title="Add/Edit Entry for Address Book">
	<form id="messaging_contact_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="address_id" id="messaging_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1-3"><label for="messaging_lastname">Last Name:</label><input type="text" name="lastname" id="messaging_lastname" style="width:164px" class="text"/></div>
			<div class="pure-u-2-3"><label for="messaging_firstname">First Name:</label><input type="text" name="firstname" id="messaging_firstname" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_prefix">Prefix:</label><input type="text" name="prefix" id="messaging_prefix" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_suffix">Suffix:</label><input type="text" name="suffix" id="messaging_suffix" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_specialty">Specialty:</label><input type="text" name="specialty" id="messaging_specialty" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_facility">Facility:</label><input type="text" name="facility" id="messaging_facility" style="width:164px" class="text"/></div>
			<div class="pure-u-2-3"><label for="messaging_email">E-mail:</label><input type="text" name="email" id="messaging_email" style="width:446px" class="text"/></div>
			<div class="pure-u-1"><label for="messaging_address">Address:</label><input type="text" name="street_address1" id="messaging_address" style="width:500px" class="text"/></div>
			<div class="pure-u-1"><label for="messaging_address2">Address2:</label><input type="text" name="street_address2" id="messaging_address2" style="width:500px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_city">City:</label><input type="text" name="city" id="messaging_city" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_state">State:</label><select name="state" id="messaging_state" class="text"></select></div>
			<div class="pure-u-1-3"><label for="messaging_zip">Zip:</label><input type="text" name="zip" id="messaging_zip" style="width:100px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_phone">Phone:</label><input type="text" name="phone" id="messaging_phone" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_fax">Fax:</label><input type="text" name="fax" id="messaging_fax" style="width:164px" class="text"/></div>
			<div class="pure-u-1-3"><label for="messaging_npi">NPI:</label><input type="text" name="npi" id="messaging_npi" style="width:164px" class="text"/></div>
			<div class="pure-u-1"><label for="messaging_comments">Comments:</label><input type="text" name="comments" id="messaging_comments" style="width:500px" class="text"/></div>
		</div>
	</form>
</div>
