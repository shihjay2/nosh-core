<div id="messages_cp_dialog" title="Imaging Helper">
	<input type="hidden" name="messages_cp_origin" id="messages_cp_origin"/>
	<input type="hidden" name="messages_cp_t_messages_id_origin" id="messages_cp_t_messages_id_origin"/>
	<table id="messages_cp_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="messages_cp_list_pager" class="scroll" style="text-align:center;"></div><br>
	<button type="button" id="messages_add_cp" class="nosh_button_add">Add</button> 
	<button type="button" id="messages_edit_cp" class="nosh_button_edit">Edit</button>
	<button type="button" id="messages_resend_cp" class="nosh_button_reactivate">Resend</button>
	<button type="button" id="messages_delete_cp" class="nosh_button_delete">Delete</button>
</div>
<div id="messages_cp_edit_fields" title="">
	<form id="edit_messages_cp_form">
		<div class="pure-form nosh_provider_exclude"><label for="messages_cp_provider_list">Provider:</label><select id ="messages_cp_provider_list" name="id" class="text" required></select><br><br></div>
		<div id="messages_cp_status"></div>
		<input type="hidden" name="orders_id" id="messages_cp_orders_id"/>
		<input type="hidden" name="t_messages_id" id="messages_cp_t_messages_id"/>
		<input type="hidden" name="eid" id="messages_cp_eid"/>
		<div id="messages_cp_accordion">
			<h3><a href="#">Cardiopulmonary Tests</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form"><label for="messages_cp_orders">Preview:</label><textarea name="orders_cp" id="messages_cp_orders" rows="10" style="width:95%" class="text" placeholder="Type a few letters of order to search." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_cp_orders_clear" class="nosh_button_cancel messages_cp_button_clear">Clear</button>
					<button type="button" id="messages_cp_orderslist_link" class="nosh_button_edit">Edit Imaging Orders Templates</button>
				</div>
			</div>
			<h3><a href="#">Diagnosis Codes</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form"><label for="messages_cp_codes">Preview:</label><textarea name="orders_cp_icd" id="messages_cp_codes" rows="10" style="width:95%" class="text" placeholder="Use a comma to separate distinct search terms." required></textarea></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_cp_codes_clear" class="nosh_button_cancel messages_cp_button_clear">Clear</button>
					<button type="button" id="messages_cp_issues" class="nosh_button_extlink">Issues</button>
				</div>
			</div>
			<h3><a href="#">Location and Date</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form"><label for="messages_cp_location">Location</label><select name="address_id" id="messages_cp_location" class="text" required></select></div><br>
					<div class="pure-form"><label for="messages_cp_orders_pending_date"><input type="text" name="orders_pending_date" id="messages_cp_orders_pending_date" class="text" required/></div>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_select_cp_location2" class="nosh_button_add">Add/Edit</button>
				</div>
			</div>
			<h3><a href="#">Insurance</a></h3>
			<div class="pure-g">
				<div class="pure-u-13-24">
					<div class="pure-form"><label for="messages_cp_insurance">Preview:</label><textarea name="orders_insurance"  id="messages_cp_insurance" rows="3" style="width:95%" class="text" required></textarea></td>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="messages_cp_insurance_clear" class="nosh_button_cancel messages_cp_button_clear">Clear</button>
					<button type="button" id="messages_cp_insurance_client" class="nosh_button">Bill Client</button>
				</div>
				<div class="pure-u-1">
					<br><br>
					<table id="messages_cp_insurance_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
					<div id="messages_cp_insurance_pager" class="scroll" style="text-align:center;"></div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="messages_cp_action_dialog" title="Action">
	<div id="messages_cp_choice"></div><br>
	<button type="button" id="messages_print_cp" class="nosh_button_print">Print</button>
	<!--<button type="button" id="messages_electronic_cp" class="nosh_button">Electronic</button> -->
	<button type="button" id="messages_fax_cp" class="nosh_button fax_button">Fax</button>
	<button type="button" id="messages_done_cp" class="nosh_button_check">Done</button>
</div>
<div id="add_test_cpt2" title="Add Order to Database">
	<form id="add_test_cpt2_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="messages_cp"/>
		<input type="hidden" id="messages_cp_orders_text"/>
		<div class="pure-control-group"><label for="messages_cp_orders_type">Order Type:</label><select id="messages_cp_orders_type"></select></div>
		<div class="pure-control-group"><label for="messages_cp_cpt">CPT Code (optional):</label><input type="text" name="messages_cp_cpt" id="messages_cp_cpt" style="width:400px" class="text"/></div>
		<div id="add_test_snomed_div2">
			<div class="pure-control-group"><label for="messages_cp_snomed">SNOMED Code (optional):<br><input type="text" name="messages_cp_snomed" id="messages_cp_snomed" style="width:400px" class="text" placeholder="Type a few letters to search or select from hierarchy."/></div><br><br>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="snomed_tree2" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="messages_edit_cp_location" title="">
	<form id="messages_edit_cp_location_form" class="pure-form pure-form-stacked">
		<input type="hidden" name="messages_cp_location_address_id" id="messages_cp_location_address_id"/>
		<div class="pure-g">
			<div class="pure-u-1"><label for="messages_cp_location_facility">Facility:</label><input type="text" name="facility" id="messages_cp_location_facility" class="text pure-input-1" required/></div>
			<div class="pure-u-1"><label for="messages_cp_location_address">Address:</label><input type="text" name="street_address1" id="messages_cp_location_address" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_cp_location_address2">Address2:</label><input type="text" name="street_address2" id="messages_cp_location_address2" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_city">City:</label><input type="text" name="city" id="messages_cp_location_city" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_state">State:</label><select name="state" id="messages_cp_location_state" class="text pure-input-1"></select></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_zip">Zip:</label><input type="text" name="zip" id="messages_cp_location_zip" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_phone">Phone:</label><input type="text" name="phone" id="messages_cp_location_phone" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_fax">Fax:</label><input type="text" name="fax" id="messages_cp_location_fax" class="text pure-input-1"/></div>
			<div class="pure-u-1-3"><label for="messages_cp_location_ordering_id">Provider/Clinic Identity:</label><input type="text" name="ordering_id" id="messages_cp_location_ordering_id" class="text pure-input-1"/></div>
			<div class="pure-u-1"><label for="messages_cp_location_comments">Comments:</label><input type="text" name="comments" id="messages_cp_location_comments" class="text pure-input-1"/></div>
		</div>
	</form>
</div>
