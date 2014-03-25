<div id="documents_list_dialog" title="Documents">
	<?php if(Session::get('group_id') != '100') {?>
		<button type="button" id="menu_new_letter" class="nosh_button_add">New Letter</button>
		<button type="button" id="menu_tests">Test Results</button>
		<br><br>
	<?php }?>
	<table id="labs" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager8" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="radiology" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager9" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="cardiopulm" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager10" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="endoscopy" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager11" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="referrals" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager12" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="past_records" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager13" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="outside_forms" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager14" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="letters" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager15" class="scroll" style="text-align:center;"></div> 
	<br>
	<table id="ccdas" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager16" class="scroll" style="text-align:center;"></div> 
	<br>
</div>
<div id="documents_edit_dialog" title="Edit Documents">
	<ul id="documents_tags"></ul>
	<form id="documents_edit_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="documents_id" id="menu_documents_id"/>
		<div class="pure-control-group"><label for="menu_documents_from">From:</label><input type="text" name="documents_from" id="menu_documents_from" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for="menu_documents_type">Document Type:</label><select name="documents_type" id="menu_documents_type" class="text" required></select></div>
		<div class="pure-control-group"><label for="menu_documents_desc">Description:</label><input type="text" name="documents_desc" id="menu_documents_desc" style="width:500px" class="text" required/></div>
		<div class="pure-control-group"><label for="menu_document_date">Document Date:</label><input type="text" name="documents_date" id="menu_documents_date" class="text" required/></div>
		<button type="button" id="save_menu_documents">Save</button> 
		<button type="button" id="cancel_menu_documents">Cancel</button>
	</form>
</div>
<div id="documents_view_dialog" title="Documents Viewer">
	<ul id="documents_view_tags"></ul>
	<input type="hidden" id="view_document_id"/>
	<input type="hidden" id="document_filepath"/>
	<div id="embedURL"></div>
</div>
<div id="letter_dialog" title="Letter Writer" style="overflow:hidden">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="letter_form" class="pure-form pure-form-stacked">
				<input type="hidden" name="address_id" id="letter_to_id"/>
				<input type="hidden" id="letter_eid"/>
				<input type="hidden" id="letter_type"/>
					<label for="letter_to">To:</label><input type="text" name="letter_to" id="letter_to" style="width:95%" class="text" /><br>
					<label for="letter_body">Preview:</label><textarea style="width:95%" rows="16" name="letter_body" id="letter_body" class="text" required></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<br><button type="button" id="letter_to_whom">To Whom It May Concern</button><br><br>
			<form class="pure-form">
				<label for="letter_template_choose_id">Choose Template:</label><br><select id="letter_template_choose_id" class="letter_template_choose text"></select>
			</form><br>
			<button type="button" id="letter_template_save">Copy</button>
			<button type="button" id="letter_reset">Clear</button><br>
			<div class="letter_template_div" style="height:250px;overflow:auto;">
				<br><form id="letter_template_form" class="letter_template_form ui-widget pure-form pure-form-stacked"></form>
			</div>
		</div>
	</div>
</div>
<div id="tests_dialog" title="Test Results">
	<form class="pure-form pure-form-aligned">
		<div class="pure-control-group"><label for="search_all_tests">Search:</label><input type="text" size="50" id="search_all_tests" class="text" onkeydown="doSearch1(arguments[0]||event)"/></div><br><br> 
	</form>
	<table id="tests_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="tests_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="chart_results">Chart Selected Results</button>
	<div id="chart_loading" style="display: block;float: right;"><?php echo HTML::image('images/indicator.gif', 'Loading');?> Loading graph...</div><br><br>
	<div id="tests_container" style="width: 750px; height: 550px; margin: 0 auto"></div>
</div>
