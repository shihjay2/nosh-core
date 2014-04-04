<div id="messages_ref_dialog" title="Referral Helper">
	<input type="hidden" name="messages_ref_origin" id="messages_ref_origin"/>
	<input type="hidden" name="messages_ref_t_messages_id_origin" id="messages_ref_t_messages_id_origin"/>
	<table id="messages_ref_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_ref_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="messages_add_ref" class="nosh_button_add">Add</button>
	<button type="button" id="messages_edit_ref" class="nosh_button_edit">Edit</button>
	<button type="button" id="messages_resend_ref" class="nosh_button_reactivate">Resend</button>
	<button type="button" id="messages_delete_ref" class="nosh_button_delete">Delete</button>
</div>
<div id="messages_ref_edit_fields" title="">
	<form id="edit_messages_ref_form">
		<div class="pure-form nosh_provider_exclude"><label for="messages_ref_provider_list">Provider:</label><select id ="messages_ref_provider_list" name="id" class="text" required></select></div>
		<div id="messages_ref_status"></div>
		<input type="hidden" name="orders_id" id="messages_ref_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_ref_t_messages_id"/>
		<input type="hidden" name="eid" id="messages_ref_eid"/>
		<div id="messages_ref_accordion">
			<h3><a href="#">Referral Reason</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_ref_orders">Preview:</label><textarea name="orders_referrals" id="messages_ref_orders" rows="10" style="width:95%" class="text" placeholder="Type a few letters of order to search." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<div class="pure-form pure-form-stacked"><label for="messages_ref_template">Choose Template:</label><select id="messages_ref_template" class="text ui-widget-content ui-corner-all"></select></div>
					<button type="button" id="messages_ref_template_save" class="nosh_button_copy">Copy</button><button type="button" id="messages_ref_orderslist_link" class="nosh_button_edit">Edit Templates</button><button type="button" id="messages_ref_orders_clear" class="nosh_button_cancel messages_ref_button_clear">Clear</button>
					<div class="ref_template_div">
						<br><div id="messages_ref_form" class="ui-widget pure-form"></div>
					</div>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_ref_codes">Preview:</label><textarea name="orders_referrals_icd" id="messages_ref_codes" rows="3" style="width:95%" class="text" placeholder="Use a comma to separate distinct search terms." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_ref_codes_clear" class="nosh_button_cancel messages_ref_button_clear">Clear</button>
					<button type="button" id="messages_ref_issues" class="nosh_button_extlink">Issues</button>
				</div>
			</div>
			<h3><a href="#">Location</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_ref_location">Provider:</label><select name="address_id" id="messages_ref_location" class="text" required></select></div>
					<div class="pure-form pure-form-stacked"><label for="messages_specialty_select">Filter by Specialty:</label><select name="specialty_select" id="messages_specialty_select" class="text"></select></div> 
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_select_ref_location2" class="nosh_button_add">Add/Edit</button> 
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_ref_insurance">Preview:</label><textarea name="orders_insurance"  id="messages_ref_insurance" rows="3" style="width:95%" class="text" required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_ref_insurance_clear" class="nosh_button_cancel  messages_ref_button_clear">Clear</button>
					<button type="button" id="messages_ref_insurance_client" class="nosh_button">Bill Client</button>
				</div>
				<div class="pure-u-1">
					<br><br>
					<table id="messages_ref_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="messages_ref_insurance_pager" class="scroll" style="text-align:center;"></div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="messages_ref_action_dialog" title="Action">
	<div id="messages_ref_choice"></div><br>
	<button type="button" id="messages_print_ref" class="nosh_button_print">Print</button> 
	<!--<button type="button" id="messages_electronic_ref" class="nosh_button">Electronic</button> -->
	<button type="button" id="messages_fax_ref" class="nosh_button fax_button">Fax</button>
	<button type="button" id="messages_done_ref" class="nosh_button_check">Done</button> 
</div>
<div id="add_test_cpt3" title="Add Order to Database">
	<form id="add_test_cpt3_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="messages_ref"/>
		<input type="hidden" id="messages_ref_orders_text"/>
		<div class="pure-control-group"><label for="messages_ref_orders_type">Order Type:</label><select id="messages_ref_orders_type"></select></div>
		<div class="pure-control-group"><label for="messages_ref_cpt">CPT Code (optional):</label><input type="text" name="messages_ref_cpt" id="messages_ref_cpt" style="width:400px" class="text"/></div>
		<div id="add_test_snomed_div3">
			<div class="pure-control-group"><label for="messages_ref_snomed">SNOMED Code (optional):<br><input type="text" name="messages_ref_snomed" id="messages_ref_snomed" style="width:400px" class="text" placeholder="Type a few letters to search or select from hierarchy."/></div><br><br>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="snomed_tree3" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="messages_edit_ref_location" title="">
	<form id="messages_edit_ref_location_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="messages_ref_location_address_id" id="messages_ref_location_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1-3"><label for="messages_ref_location_lastname">Last Name:</label><input type="text" name="lastname" id="messages_ref_location_lastname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_firstname">First Name:</label><input type="text" name="firstname" id="messages_ref_location_firstname" class="text pure-input-1" required/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_facility">Facility:</label><input type="text" name="facility" id="messages_ref_location_facility" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_prefix">Prefix:</label><input type="text" name="prefix" id="messages_ref_location_prefix" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_suffix">Suffix:</label><input type="text" name="suffix" id="messages_ref_location_suffix" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_specialty">Specialty:</label><input type="text" name="specialty" id="messages_ref_location_specialty" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_ref_location_email">E-mail:</label><input type="text" name="email" id="messages_ref_location_email" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_ref_location_address">Address:</label><input type="text" name="street_address1" id="messages_ref_location_address" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_ref_location_address2">Address2:</label><input type="text" name="street_address2" id="messages_ref_location_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_city">City:</label><input type="text" name="city" id="messages_ref_location_city" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_state">State:</label><select name="state" id="messages_ref_location_state" class="text pure-input-1"></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_zip">Zip:</label><input type="text" name="zip" id="messages_ref_location_zip" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_phone">Phone:</label><input type="text" name="phone" id="messages_ref_location_phone" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_ref_location_fax">Fax:</label><input type="text" name="fax" id="messages_ref_location_fax" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_ref_location_comments">Comments:</label><input type="text" name="comments" id="messages_ref_location_comments" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
