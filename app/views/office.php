<div id="office_dialog" title="Office Tools">
	<div id="office_accordion">
		<h3>Vaccine Inventory</h3>
		<div>
			<table id="vaccine_inventory" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="vaccine_inventory_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_vaccine" class="nosh_button_add">Add</button>
			<button type="button" id="edit_vaccine" class="nosh_button_edit">Edit</button>
			<button type="button" id="inactivate_vaccine" class="nosh_button_cancel">Inactivate</button>
			<button type="button" id="delete_vaccine" class="nosh_button_delete">Delete</button><br><br>
			<table id="vaccine_inventory_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="vaccine_inventory_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="reactivate_vaccine" class="nosh_button_reactivate">Reactivate</button>
		</div>
		<h3>Vaccine Temperatures</h3>
		<div>
			<table id="vaccine_temp" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="vaccine_temp_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_temp" class="nosh_button_add">Add</button>
			<button type="button" id="edit_temp" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_temp" class="nosh_button_delete">Delete</button>
		</div>
		<h3>Supplements Inventory</h3>
		<div>
			<form class="pure-form">
				<label for="sales_tax">Sales Tax %:</label><input type="text" name="sales_tax" id="sales_tax" class="text" placeholder="Leave blank if none"/><br><br>
			</form>
			<table id="supplements_inventory" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="supplements_inventory_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_supplement" class="nosh_button_add">Add</button>
			<button type="button" id="edit_supplement" class="nosh_button_edit">Edit</button>
			<button type="button" id="inactivate_supplement" class="nosh_button_cancel">Inactivate</button>
			<button type="button" id="delete_supplement" class="nosh_button_delete">Delete</button><br><br>
			<table id="supplements_inventory_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="supplements_inventory_inactive_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="reactivate_supplement" class="nosh_button_reactivate">Reactivate</button>
		</div>
		<h3>Queries and Reports</h3>
		<div>
			<fieldset class="ui-corner-all">
				<legend>Age Distribution of Patients in the Practice:</legend>
				<div class="pure-g">
					<div class="pure-u-1-2">0-18 years of age:</div>
					<div class="pure-u-1-2"><div id="age_group1"></div></div>
					<div class="pure-u-1-2">19-64 years of age:</div>
					<div class="pure-u-1-2"><div id="age_group2"></div></div>
					<div class="pure-u-1-2">65+ years of age:</div>
					<div class="pure-u-1-2"><div id="age_group3"></div></div>
				</div>
			</fieldset><br><br>
			<fieldset class="ui-corner-all">
				<legend>Super Query</legend>
				<form name="super_query_form" id="super_query_form" class="pure-form">
					<div id="super_query_div"></div><br>
					<div style="width:450px">
						<div class="pure-g">
							<div class="pure-u-1-2">Active Patients Only:</div>
							<div class="pure-u-1-6"><input type="checkbox" name="search_active_only" id="search_active_only" value="Yes" class="text"/></div>
							<div class="pure-u-1-6"></div>
							<div class="pure-u-1-6"></div>
							<div class="pure-u-1-2">Patients without insurance:</div>
							<div class="pure-u-1-6"><input type="checkbox" name="search_no_insurance_only" id="search_no_insurance_only" value="Yes" class="text"/></div>
							<div class="pure-u-1-6"></div>
							<div class="pure-u-1-6"></div>
							<div class="pure-u-1-2">Gender:</div>
							<div class="pure-u-1-6"><input type="radio" name="search_gender" id="search_gender_both" value="both" /> Both</div>
							<div class="pure-u-1-6"><input type="radio" name="search_gender" value="m" /> Male</div>
							<div class="pure-u-1-6"><input type="radio" name="search_gender" value="f" /> Female</div>
						</div>
					</div>
					<br>
					<button type="button" id="super_query_submit" class="nosh_button">Submit Query</button>
					<button type="button" id="super_query_reset" class="nosh_button_cancel">Reset Query</button>
				</form>
				<br><br>
				<table id="super_query_results" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="super_query_results_pager" class="scroll" style="text-align:center;"></div><br>
			</fieldset><br><br>
			<fieldset class="ui-corner-all">
				<legend>Tag Search</legend>
				<form name="tag_query_form" id="tag_query_form" class="pure-form pure-form-aligned">
					<div id="tag_query_div">
						<div class="pure-control-group"><label for="tag_parent">Patient (optional):</label><input type="text" id="tag_patient" style="width:400px" class="text"/></div>
						<div class="pure-control-group"><label for="tags_search">Search items with the following tag(s):</label><select name="tags_array[]" id="tags_search" multiple="multiple" style="width:400px" class="multiselect"></select></div>
						<input type="hidden" id="tag_pid"/>
					</div>
				</form>
				<br><br>
				<table id="tag_query_results" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="tag_query_results_pager" class="scroll" style="text-align:center;"></div><br>
			</fieldset>
		</div>
		<h3>Export Data</h3>
		<div>
			Export demographic information to CSV file: <button type="button" id="export_demographics" class="nosh_button">All Patients</button> <button type="button" id="export_demographics1" class="nosh_button">Active Patients Only</button>
		</div>
	</div>
</div>
<div id="edit_vaccine_dialog" title="">
	<form name="edit_vaccine_form" id="edit_vaccine_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="vaccine_id" id="edit_vaccine_vaccine_id"/>
		<input type="hidden" name="imm_cvxcode" id="edit_vaccine_imm_cvxcode"/>
		<div class="pure-control-group"><label for="edit_vaccine_imm_immunization">Vaccine:</label><input type="text" name="imm_immunization" id="edit_vaccine_imm_immunization" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_imm_manufacturer">Manufacturer:</label><input type="text" name="imm_manufacturer" id="edit_vaccine_imm_manufacturer" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_imm_brand">Brand:</label><input type="text" name="imm_brand" id="edit_vaccine_imm_brand" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_imm_lot">Lot number:</label><input type="text" name="imm_lot" id="edit_vaccine_imm_lot" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_cpt">CPT:</label><input type="text" name="cpt" id="edit_vaccine_cpt" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_quantity">Quantity:</label><input type="text" name="quantity" id="edit_vaccine_quantity" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_date_purchase">Date Purchased:</label><input type="text" name="date_purchase" id="edit_vaccine_date_purchase" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_imm_expiration">Expiration Date:</label><input type="text" name="imm_expiration" id="edit_vaccine_imm_expiration" class="text" required/></div>
	</form>
</div>
<div id="reactivate_vaccine_dialog" title="Reactivate Vaccine">
	<form id="reactivate_vaccine_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="vaccine_id" id="reactivate_vaccine_id"/>
		<div class="pure-control-group"><label for="reactivate_quantity">Quantity:</label><input type="text" name="quantity" id="reactivate_quantity" class="text" required/></div>
	</form>
</div>
<div id="edit_vaccine_temp_dialog" title="">
	<form name="edit_vaccine_temp_form" id="edit_vaccine_temp_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="temp_id" id="temp_id"/>
		<input type="hidden" name="date" id="date">
		<div class="pure-control-group"><label for="edit_vaccine_temp_date">Date:</label><input type="text" name="temp_date" id="edit_vaccine_temp_date" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_temp_time">Time:</label><input type="text" name="temp_time" id="edit_vaccine_temp_time" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_temp">Temperature:</label><input type="text" name="temp" id="edit_vaccine_temp" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_vaccine_action">Action Taken If Out of Range:</label><input type="text" name="action" id="edit_vaccine_action" style="width:400px" class="text"/></div>
	</form>
</div>
<div id="supplements_dialog" title="">
	<form name="edit_supplement_form" id="edit_supplement_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="supplement_id" id="edit_supplement_supplement_id"/>
		<input type="hidden" name="cpt" id="edit_supplement_sup_cpt"/>
		<div class="pure-control-group"><label for="edit_supplement_sup_description">Supplement Description:</label><input type="text" name="sup_description" id="edit_supplement_sup_description" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_strength">Strength:</label><input type="text" name="sup_strength" id="edit_supplement_sup_strength" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_manufacturer">Manufacturer:</label><input type="text" name="sup_manufacturer" id="edit_supplement_sup_manufacturer" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_lot">Lot Number:</label><input type="text" name="sup_lot" id="edit_supplement_sup_lot" style="width:400px" class="text"/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_charge">Charge:</label>$<input type="text" name="charge" id="edit_supplement_sup_charge" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_quantity">Quantity (bottles or packages):</label><input type="text" name="quantity" id="edit_supplement_sup_quantity" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_date_purchase">Date Purchased:</label><input type="text" name="date_purchase" id="edit_supplement_sup_date_purchase" class="text" required/></div>
		<div class="pure-control-group"><label for="edit_supplement_sup_expiration">Expiration Date:</label><input type="text" name="sup_expiration" id="edit_supplement_sup_expiration" class="text"/></div>
	</form>
</div>
<div id="reactivate_supplement_dialog" title="Reactivate Supplement">
	<form id="reactivate_supplement_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="supplement_id" id="reactivate_supplement_inventory_id"/>
		<div class="pure-control-group"><label for="reactivate_sup_quantity">Quantity:</label><input type="text" name="quantity" id="reactivate_sup_quantity" class="text" required/></div>
	</form>
</div>
<div id="tag_modal_view_dialog" title="">
	<input type="hidden" id="tag_view_document_id"/>
	<input type="hidden" id="tag_document_filepath"/>
	<div id="tag_modal_view"></div>
</div>
