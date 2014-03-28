<div id="messages_rad_dialog" title="Imaging Helper">
	<input type="hidden" name="messages_rad_origin" id="messages_rad_origin"/>
	<input type="hidden" name="messages_rad_t_messages_id_origin" id="messages_rad_t_messages_id_origin"/>
	<table id="messages_rad_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_rad_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="messages_add_rad" class="nosh_button_add">Add</button> 
	<button type="button" id="messages_edit_rad" class="nosh_button_edit">Edit</button>
	<button type="button" id="messages_resend_rad" class="nosh_button_reactivate">Resend</button>
	<button type="button" id="messages_delete_rad" class="nosh_button_delete">Delete</button>
</div>
<div id="messages_rad_edit_fields" title="">
	<form id="edit_messages_rad_form">
		<div class="pure-form nosh_provider_exclude"><label for="messages_rad_provider_list">Provider:</label><select id ="messages_rad_provider_list" name="id" class="text" required></select></div>
		<div id="messages_rad_status"></div>
		<input type="hidden" name="orders_id" id="messages_rad_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_rad_t_messages_id"/>
		<input type="hidden" name="eid" id="messages_rad_eid"/>
		<div id="messages_rad_accordion">
			<h3><a href="#">Imaging Tests</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_rad_orders">Preview:</label><textarea name="orders_radiology" id="messages_rad_orders" rows="10" style="width:95%" class="text" placeholder="Type a few letters of order to search." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_rad_orders_clear" class="nosh_button_cancel messages_rad_button_clear">Clear</button>
					<button type="button" id="messages_rad_orderslist_link" class="nosh_button_edit">Edit Imaging Orders Templates</button>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_rad_codes">Preview:</label><textarea name="orders_radiology_icd" id="messages_rad_codes" rows="10" style="width:95%" class="text" placeholder="Use a comma to separate distinct search terms." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_rad_codes_clear" class="nosh_button_cancel messages_rad_button_clear">Clear</button>
					<button type="button" id="messages_rad_issues" class="nosh_button_extlink">Issues</button>
				</div>
			</div>
			<h3><a href="#">Location and Date</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_rad_location">Location:</label><select name="address_id" id="messages_rad_location" class="text" required></select></div><br>
					<div class="pure-form pure-form-stacked"><label for="messages_rad_orders_pending_date">Order Date:</label><input type="text" name="orders_pending_date" id="messages_rad_orders_pending_date" class="text" required/></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_select_rad_location2" class="nosh_button_add">Add/Edit</button>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form pure-form-stacked"><label for="messages_rad_insurance">Preview:</label><textarea name="orders_insurance"  id="messages_rad_insurance" rows="3" style="width:95%" class="text" required></textarea></td>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_rad_insurance_clear" class="nosh_button_cancel messages_rad_button_clear">Clear</button>
					<button type="button" id="messages_rad_insurance_client" class="nosh_button">Bill Client</button>
				</div>
				<div class="pure-u-1">
					<br><br>
					<table id="messages_rad_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="messages_rad_insurance_pager" class="scroll" style="text-align:center;"></div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="messages_rad_action_dialog" title="Action">
	<div id="messages_rad_choice"></div><br>
	<button type="button" id="messages_print_rad" class="nosh_button_print">Print</button>
	<!--<button type="button" id="messages_electronic_rad" class="nosh_button">Electronic</button> -->
	<button type="button" id="messages_fax_rad" class="nosh_button fax_button">Fax</button>
	<button type="button" id="messages_done_rad" class="nosh_button_check">Done</button>
</div>
<div id="add_test_cpt1" title="Add Order to Database">
	<form id="add_test_cpt1_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="messages_rad"/>
		<input type="hidden" id="messages_rad_orders_text"/>
		<div class="pure-control-group"><label for="messages_rad_orders_type">Order Type:</label><select id="messages_rad_orders_type"></select></div>
		<div class="pure-control-group"><label for="messages_rad_cpt">CPT Code (optional):</label><input type="text" name="messages_rad_cpt" id="messages_rad_cpt" style="width:400px" class="text"/></div>
		<div id="add_test_snomed_div1">
			<div class="pure-control-group"><label for="messages_rad_snomed">SNOMED Code (optional):<br><input type="text" name="messages_rad_snomed" id="messages_rad_snomed" style="width:400px" class="text" placeholder="Type a few letters to search or select from hierarchy."/></div><br><br>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="snomed_tree1" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="messages_edit_rad_location" title="">
	<form id="messages_edit_rad_location_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="messages_rad_location_address_id" id="messages_rad_location_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1"><label for="messages_rad_location_facility">Facility:</label><input type="text" name="facility" id="messages_rad_location_facility" class="text pure-input-1" required/></div>
			<div class="pure-u-1"><label for="messages_rad_location_address">Address:</label><input type="text" name="street_address1" id="messages_rad_location_address" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_rad_location_address2">Address2:</label><input type="text" name="street_address2" id="messages_rad_location_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_city">City:</label><input type="text" name="city" id="messages_rad_location_city" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_state">State:</label><select name="state" id="messages_rad_location_state" class="text pure-input-1"></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_zip">Zip:</label><input type="text" name="zip" id="messages_rad_location_zip" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_phone">Phone:</label><input type="text" name="phone" id="messages_rad_location_phone" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_fax">Fax:</label><input type="text" name="fax" id="messages_rad_location_fax" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_rad_location_ordering_id">Provider/Clinic Identity:</label><input type="text" name="ordering_id" id="messages_rad_location_ordering_id" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_rad_location_comments">Comments:</label><input type="text" name="comments" id="messages_rad_location_comments" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
