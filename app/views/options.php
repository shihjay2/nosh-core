<div id="configuration_dialog" title="Configuration">
	<button type="button" id="import_template" class="nosh_button_forward">Import Form or Text Templates</button>
	<br><br>
	<div id="configuration_accordion">
		<?php if(Session::get('group_id') == '2') {?>
			<h3 class="configuration_hpi"><a href="#">HPI Forms</a></h3>
			<div class="configuration_hpi">
				<table id="hpi_forms_list" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="hpi_forms_list_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="add_hpi_forms" class="nosh_button_add">Add</button>
				<button type="button" id="edit_hpi_forms" class="nosh_button_edit">Edit</button>
				<button type="button" id="delete_hpi_forms" class="nosh_button_delete">Delete</button>
				<button type="button" id="export_hpi_forms" class="nosh_button_extlink export_template">Export</button>
			</div>
		<?php }?>
		<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
			<h3 class="configuration_ros"><a href="#">ROS Forms</a></h3>
			<div class="configuration_ros">
				<table id="ros_forms_list" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="ros_forms_list_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="add_ros_forms" class="nosh_button_add">Add</button>
				<button type="button" id="edit_ros_forms" class="nosh_button_edit">Edit</button>
				<button type="button" id="delete_ros_forms" class="nosh_button_delete">Delete</button>
				<button type="button" id="default_ros_forms" class="nosh_button_check">Default</button>
				<button type="button" id="export_ros_forms" class="nosh_button_extlink">Export</button>
			</div>
		<?php }?>
		<?php if(Session::get('group_id') == '2') {?>
			<h3 class="configuration_pe"><a href="#">PE Forms</a></h3>
			<div class="configuration_pe">
				<table id="pe_forms_list" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="pe_forms_list_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="add_pe_forms" class="nosh_button_add">Add</button>
				<button type="button" id="edit_pe_forms" class="nosh_button_edit">Edit</button>
				<button type="button" id="delete_pe_forms" class="nosh_button_delete">Delete</button>
				<button type="button" id="default_pe_forms" class="nosh_button_check">Default</button>
				<button type="button" id="export_pe_forms" class="nosh_button_extlink">Export</button>
			</div>
		<?php }?>
		<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
			<h3 class="configuration_orders"><a href="#">Orders - Labs</a></h3>
			<div class="configuration_orders">
				<table id="configuration_orders_labs" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_labs_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_labs_add" class="configuration_orders_button nosh_button_add">Add Global</button>
				<button type="button" id="configuration_orders_labs_edit" class="configuration_orders_button nosh_button_edit">Edit Global</button>
				<button type="button" id="configuration_orders_labs_delete" class="configuration_orders_button nosh_button_delete">Delete Global</button><br><br>
				<table id="configuration_orders_labs1" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_labs1_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_labs1_add" class="configuration_orders_button nosh_button_add">Add Personal</button>
				<button type="button" id="configuration_orders_labs1_edit" class="configuration_orders_button nosh_button_edit">Edit Personal</button>
				<button type="button" id="configuration_orders_labs1_delete" class="configuration_orders_button nosh_button_delete">Delete Personal</button><br><br>
			</div>
			<h3 class="configuration_orders"><a href="#">Orders - Imaging</a></h3>
			<div class="configuration_orders">
				<table id="configuration_orders_rad" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_rad_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_rad_add" class="configuration_orders_button nosh_button_add">Add Global</button>
				<button type="button" id="configuration_orders_rad_edit" class="configuration_orders_button nosh_button_edit">Edit Global</button>
				<button type="button" id="configuration_orders_rad_delete" class="configuration_orders_button nosh_button_delete">Delete Global</button><br><br>
				<table id="configuration_orders_rad1" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_rad1_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_rad1_add" class="configuration_orders_button nosh_button_add">Add Personal</button>
				<button type="button" id="configuration_orders_rad1_edit" class="configuration_orders_button nosh_button_edit">Edit Personal</button>
				<button type="button" id="configuration_orders_rad1_delete" class="configuration_orders_button nosh_button_delete">Delete Personal</button><br><br>
			</div>
			<h3 class="configuration_orders"><a href="#">Orders - Cardiopulmonary</a></h3>
			<div class="configuration_orders">
				<table id="configuration_orders_cp" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_cp_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_cp_add" class="configuration_orders_button nosh_button_add">Add Global</button>
				<button type="button" id="configuration_orders_cp_edit" class="configuration_orders_button nosh_button_edit">Edit Global</button>
				<button type="button" id="configuration_orders_cp_delete" class="configuration_orders_button nosh_button_delete">Delete Global</button><br><br>
				<table id="configuration_orders_cp1" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_cp1_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_cp1_add" class="configuration_orders_button nosh_button_add">Add Personal</button>
				<button type="button" id="configuration_orders_cp1_edit" class="configuration_orders_button nosh_button_edit">Edit Personal</button>
				<button type="button" id="configuration_orders_cp1_delete" class="configuration_orders_button nosh_button_delete">Delete Personal</button><br><br>
			</div>
			<h3 class="configuration_orders"><a href="#">Orders - Referrals</a></h3>
			<div class="configuration_orders">
				<table id="configuration_orders_ref" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_ref_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_ref_add" class="configuration_orders_button nosh_button_add">Add Global</button>
				<button type="button" id="configuration_orders_ref_edit" class="configuration_orders_button nosh_button_edit">Edit Global</button>
				<button type="button" id="configuration_orders_ref_delete" class="configuration_orders_button nosh_button_delete">Delete Global</button><br><br>
				<table id="configuration_orders_ref1" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="configuration_orders_ref1_pager" class="scroll" style="text-align:center;"></div><br>
				<button type="button" id="configuration_orders_ref1_add" class="configuration_orders_button nosh_button_add">Add Personal</button>
				<button type="button" id="configuration_orders_ref1_edit" class="configuration_orders_button nosh_button_edit">Edit Personal</button>
				<button type="button" id="configuration_orders_ref1_delete" class="configuration_orders_button nosh_button_delete">Delete Personal</button><br><br>
			</div>
		<?php }?>
		<h3><a href="#">Text Templates</a></h3>
		<div>
			<table id="textdump_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="textdump_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_textdump_group" class="nosh_button_add">Add</button>
			<button type="button" id="edit_textdump_group" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_textdump_group" class="nosh_button_delete">Delete</button>
			<button type="button" id="export_textdump" class="nosh_button_extlink">Export</button>
		</div>
		<h3><a href="#">CPT</a></h3>
		<div>
			Search: <input type="text" size="50" id="search_all_cpt" class="text ui-widget-content ui-corner-all" onkeydown="doSearch(arguments[0]||event)"/><br><br> 
			<table id="cpt_list_config" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="cpt_list_config_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_cpt" class="nosh_button_add">Add</button>
			<button type="button" id="edit_cpt" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_cpt" class="nosh_button_delete">Delete</button>
		</div>
		<h3><a href="#">Patient Forms</a></h3>
		<div>
			<table id="patient_forms_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="patient_forms_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_patient_forms" class="nosh_button_add">Add</button>
			<button type="button" id="edit_patient_forms" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_patient_forms" class="nosh_button_delete">Delete</button>
			<button type="button" id="export_patient_forms" class="nosh_button_extlink export_template">Export</button>
		</div>
	</div>
</div>
<div id="configuration_order" title="">
	<form id="configuration_order_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="configuration_orderslist_table"/>
		<input type="hidden" name="orderslist_id" id="configuration_orderslist_id"/>
		<input type="hidden" name="user_id" id="configuration_user_id"/>
		<input type="hidden" name="orders_category" id="configuration_orders_categrory"/>
		<div class="pure-control-group"><label for="configuration_orders_description">Order:</label><input type="text" name="orders_description" id="configuration_orders_description" style="width:450px" class="text" required/></div>
		<div class="pure-control-group"><label for="configuration_cpt">CPT Code (optional):</label><input type="text" name="cpt" id="configuration_cpt" style="width:290px" class="text"/></div>
		<div id="configuration_snomed_div">
			<div class="pure-control-group"><label for="configuration_snomed">SNOMED Code (optional):</label><input type="text" name="snomed" id="configuration_snomed" style="width:290px" class="text" placeholder="Type a few letters to search or select from hierarchy."/></div>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="configuration_snomed_tree" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="configuration_cpt_dialog" title="">
	<form id="configuration_cpt_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="configuration_cpt_origin"/>
		<input type="hidden" name="cpt_id" id="configuration_cpt_id"/>
		<input type="hidden" name="cpt_relate_id" id="configuration_cpt_relate_id"/>
		<div class="pure-control-group"><label for="configuration_cpt_code">CPT Code:</label><input type="text" name="cpt" id="configuration_cpt_code" style="width:290px" class="text" required/></div>
		<div class="pure-control-group"><label for="configuration_cpt_description">Description:</label><textarea name="cpt_description" id="configuration_cpt_description" style="width:400px" rows="5" class="text" required/></textarea></div>
		<div class="pure-control-group"><label for="configuration_charge">Charge:</label>$<input type="text" name="cpt_charge" id="configuration_charge" style="width:290px" class="text"/></div>
		<div class="pure-control-group"><label for="configuration_unit">Default Unit(s):</label><input type="text" name="unit" id="configuration_unit" style="width:290px" class="text"/></div>
		<div class="pure-control-group"><label for="configuration_favorite">Favorite:</label><select name="favorite" id="configuration_favorite" class="text forms_main"></select></div>
	</form>
</div>
<div id="configuration_patient_forms_dialog" title="">
	<form id="configuration_patient_forms_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_patient_forms_template_id" value=''/>
		<input type="hidden" name="array" id="configuration_patient_forms_json" value='' required/>
		<div class="pure-g">
			<div class="pure-u-1-2"><label for="configuration_patient_forms_title">Form Title:</label><input type="text" name="template_name" id="configuration_patient_forms_title" style="width:290px" class="text forms_main" required/></div>
			<div class="pure-u-1-2"><label for="configuration_patient_forms_destination">Form Destination to Encounter Element:</label><select name="forms_destination" id="configuration_patient_forms_destination" style="width:290px" class="text forms_main" required></select></div>
			<div class="pure-u-1-2"><label for="configuration_patient_forms_gender">Gender</label><select name="sex" id="configuration_patient_forms_gender" style="width:290px" class="text forms_main configuration_gender"></select></div>
			<div class="pure-u-1-2"><label for="configuration_patient_forms_age_group">Age Group</label><select name="age" id="configuration_patient_forms_age_group" style="width:290px" class="text forms_main configuration_age_group"></select></div>
			<div class="pure-u-1"><label for="configuration_patient_forms_scoring">Scoring Description</label><textarea name="scoring" id="configuration_patient_forms_scoring" rows="3" style="width:95%" class="text forms_main" title=""></textarea></div>
		</div>
	</form>
	<hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		<strong>Here is what the form will look like!</strong><br>
		Click around the form element to edit.<br>
		<div id="patient_forms_preview_surround" class="ui-corner-all ui-tabs ui-widget ui-widget-content" style="width:290px"><form id="patient_forms_preview" class="ui-widget pure-form"></form></div>
		<br><button type="button" id="patient_forms_add_element" class="nosh_button_add">Add Element</button>
	</div>
	<div style="display:block;float:left">
		<div id="patient_forms_template_surround_div">
			<button type="button" id="patient_forms_element_save" class="nosh_button_save element_save">Save</button><button type="button" id="patient_forms_element_cancel" class="nosh_button_cancel element_cancel">Cancel</button><button type="button" id="patient_forms_element_delete" class="nosh_button_delete element_delete">Delete Form Element</button><br/>
			<div id="patient_forms_template_div">
				<form class="pure-form pure-form-stacked">
					<input type="hidden" id="patient_forms_div_id"/>
					<div class="pure-control-group"><label for="configuration_patient_forms_label">Label:</label><input type="text" id="configuration_patient_forms_label" style="width:290px" class="text"/></div>
					<div class="pure-control-group"><label for="configuration_patient_forms_fieldtype">Field Type:</label><select id="configuration_patient_forms_fieldtype" style="width:290px" class="text configuration_fieldtype"></select></div>
					<div id="patient_forms_template_div_options"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="configuration_hpi_forms_dialog" title="">
	<form id="configuration_hpi_forms_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_hpi_forms_template_id" value=''/>
		<input type="hidden" name="array" id="configuration_hpi_forms_json" value='' required/>
		<div class="pure-g">
			<div class="pure-u-1-2"><label for="configuration_hpi_forms_title">Form Title:</label><input type="text" name="template_name" id="configuration_hpi_forms_title" style="width:290px" class="text forms_main" required/></div>
			<div class="pure-u-1-2"><label for="configuration_hpi_forms_gender">Gender</label><select name="sex" id="configuration_hpi_forms_gender" style="width:290px" class="text forms_main configuration_gender"></select></div>
			<div class="pure-u-1-2"><label for="configuration_hpi_forms_age_group">Age Group</label><select name="age" id="configuration_hpi_forms_age_group" style="width:290px" class="text forms_main configuration_age_group"></select></div>
		</div>
	</form>
	<hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		<strong>Here is what the form will look like!</strong><br>
		Click around the form element to edit.<br>
		<div id="hpi_forms_preview_surround" class="ui-corner-all ui-tabs ui-widget ui-widget-content" style="width:290px"><form id="hpi_forms_preview" class="ui-widget pure-form"></form></div>
		<br><button type="button" id="hpi_forms_add_element" class="nosh_button_add">Add Element</button>
	</div>
	<div style="display:block;float:left">
		<div id="hpi_forms_template_surround_div">
			<button type="button" id="hpi_forms_element_save" class="nosh_button_save element_save">Save</button><button type="button" id="hpi_forms_element_cancel" class="nosh_button_cancel element_cancel">Cancel</button><button type="button" id="hpi_forms_element_delete" class="nosh_button_delete element_delete">Delete Form Element</button><br/>
			<div id="hpi_forms_template_div">
				<form class="pure-form pure-form-stacked">
					<input type="hidden" id="hpi_forms_div_id"/>
					The first option of any radio button or <br>checkbox group will be designated as "Normal"<br>
					<div class="pure-control-group"><label for="configuration_hpi_forms_label">Label:</label><input type="text" id="configuration_hpi_forms_label" style="width:290px" class="text"/></div>
					<div class="pure-control-group"><label for="configuration_hpi_forms_fieldtype">Field Type:</label><select id="configuration_hpi_forms_fieldtype" style="width:290px" class="text configuration_fieldtype"></select></div>
					<div id="hpi_forms_template_div_options"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="configuration_ros_forms_dialog" title="">
	<form id="configuration_ros_forms_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_ros_forms_template_id" value=''/>
		<input type="hidden" name="array" id="configuration_ros_forms_json" value='' required/>
		<div class="pure-g">
			<div class="pure-u-1-2"><label for="configuration_ros_forms_title">Form Title:</label><input type="text" name="template_name" id="configuration_ros_forms_title" style="width:290px" class="text forms_main" required/></div>
			<div class="pure-u-1-2"><label for="configuration_ros_forms_gender">Gender</label><select name="sex" id="configuration_ros_forms_gender" style="width:290px" class="text forms_main configuration_gender" required></select></div>
			<div class="pure-u-1-2"><label for="configuration_ros_forms_age_group">Age Group</label><select name="age" id="configuration_ros_forms_age_group" style="width:290px" class="text forms_main configuration_age_group"></select></div>
			<div class="pure-u-1-2"><label for="configuration_ros_forms_group">Body System</label><select name="group" id="configuration_ros_forms_group" style="width:290px" class="text forms_main configuration_group" required></select></div>
		</div>
	</form>
	<hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		<strong>Here is what the form will look like!</strong><br>
		Click around the form element to edit.<br>
		<div id="ros_forms_preview_surround" class="ui-corner-all ui-tabs ui-widget ui-widget-content" style="width:290px"><form id="ros_forms_preview" class="ui-widget pure-form"></form></div>
		<br><button type="button" id="ros_forms_add_element" class="nosh_button_add">Add Element</button>
	</div>
	<div style="display:block;float:left">
		<div id="ros_forms_template_surround_div">
			<button type="button" id="ros_forms_element_save" class="nosh_button_save element_save">Save</button><button type="button" id="ros_forms_element_cancel" class="nosh_button_cancel element_cancel">Cancel</button><button type="button" id="ros_forms_element_delete" class="nosh_button_delete element_delete">Delete Form Element</button><br/>
			<div id="ros_forms_template_div">
				<form class="pure-form pure-form-stacked">
					<input type="hidden" id="ros_forms_div_id"/>
					The first option of any radio button or <br>checkbox group will be designated as "Normal"<br>
					<div class="pure-control-group"><label for="configuration_ros_forms_label">Label:</label><input type="text" id="configuration_ros_forms_label" style="width:290px" class="text"/></div>
					<div class="pure-control-group"><label for="configuration_ros_forms_fieldtype">Field Type:</label><select id="configuration_ros_forms_fieldtype" style="width:290px" class="text configuration_fieldtype"></select></div>
					<div id="ros_forms_template_div_options"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="configuration_pe_forms_dialog" title="">
	<form id="configuration_pe_forms_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_pe_forms_template_id" value=''/>
		<input type="hidden" name="array" id="configuration_pe_forms_json" value='' required/>
		<div class="pure-g">
			<div class="pure-u-1-2"><label for="configuration_pe_forms_title">Form Title:</label><input type="text" name="template_name" id="configuration_pe_forms_title" style="width:290px" class="text forms_main" required/></div>
			<div class="pure-u-1-2"><label for="configuration_pe_forms_gender">Gender</label><select name="sex" id="configuration_pe_forms_gender" style="width:290px" class="text forms_main configuration_gender"></select></div>
			<div class="pure-u-1-2"><label for="configuration_pe_forms_age_group">Age Group</label><select name="age" id="configuration_pe_forms_age_group" style="width:290px" class="text forms_main configuration_age_group"></select></div>
			<div class="pure-u-1-2"><label for="configuration_pe_forms_group">Body System</label><select name="group" id="configuration_pe_forms_group" style="width:290px" class="text forms_main configuration_group" required></select></div>
		</div>
	</form>
	<hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		<strong>Here is what the form will look like!</strong><br>
		Click around the form element to edit.<br>
		<div id="pe_forms_preview_surround" class="ui-corner-all ui-tabs ui-widget ui-widget-content" style="width:290px"><form id="pe_forms_preview" class="ui-widget pure-form"></form></div>
		<br><button type="button" id="pe_forms_add_element" class="nosh_button_add">Add Element</button>
	</div>
	<div style="display:block;float:left">
		<div id="pe_forms_template_surround_div">
			<button type="button" id="pe_forms_element_save" class="nosh_button_save element_save">Save</button><button type="button" id="pe_forms_element_cancel" class="nosh_button_cancel element_cancel">Cancel</button><button type="button" id="pe_forms_element_delete" class="nosh_button_delete element_delete">Delete Form Element</button><br/>
			<div id="pe_forms_template_div">
				<form class="pure-form pure-form-stacked">
					<input type="hidden" id="pe_forms_div_id"/>
					The first option of any radio button or <br>checkbox group will be designated as "Normal"<br>
					<div class="pure-control-group"><label for="configuration_pe_forms_label">Label:</label><input type="text" id="configuration_pe_forms_label" style="width:290px" class="text"/></div>
					<div class="pure-control-group"><label for="configuration_pe_forms_fieldtype">Field Type:</label><select id="configuration_pe_forms_fieldtype" style="width:290px" class="text configuration_fieldtype"></select></div>
					<div id="pe_forms_template_div_options"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="configuration_textdump_group_dialog" title="">
	<form id="configuration_textdump_group_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_textdump_group_template_id"/>
		<div class="pure-control-group"><label for="configuration_textdump_group_template_name">Target Field</label><select id="configuration_textdump_group_template_name" name="template_name" style="width:290px" class="text" required></select></div>
		<div class="pure-control-group"><label for="configuration_textdump_array">Group</label><input type="text" id="configuration_textdump_group_group" name="group" style="width:290px" class="text" required/></div>
	</form>
</div>
<div id="configuration_textdump_dialog" title="">
	<form id="configuration_textdump_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="template_id" id="configuration_textdump_template_id"/>
		<input type="hidden" name="group" id="configuration_textdump_group"/>
		<input type="hidden" name="template_name" id="configuration_textdump_template_name"/>
		<input type="hidden" id="configuration_textdump_subgrid_table_id"/>
		<div class="pure-control-group"><label for="configuration_textdump_array">Template Text</label><input type="text" id="configuration_textdump_array" name="array" style="width:290px" class="text" required/></div>
		<div class="pure-control-group"><label for="configuration_textdump_default">Default Normal?</label><select id="configuration_textdump_default" name="default"></select></div>
	</form>
</div>
