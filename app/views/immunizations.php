<div id="immunizations_list_dialog" title="Immunizations">
	<div id="orders_imm_header" style="display:none">
		<button type="button" id="save_orders_imm" class="nosh_button_save">Save Immunizations</button> 
		<button type="button" id="cancel_orders_imm_helper" class="nosh_button_cancel">Cancel</button> 
		<br><br>
	</div>
	<fieldset class="ui-corner-all">
		<div id="imm_notes_div"></div>
		<button type="button" id="imm_notes_button" class="nosh_button_edit">Edit Immunization Notes</button>
	</fieldset>
	<br><br>
	<table id="immunizations" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="immunizations_pager" class="scroll" style="text-align:center;"></div><br>
	<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
		<div id="imm_menu" style="display:none">
			<button type="button" id="add_immunization" class="nosh_button_add">Add</button>
			<button type="button" id="edit_immunization" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_immunization" class="nosh_button_delete">Delete</button>
			<button type="button" id="print_immunizations" class="nosh_button_print">Print List</button>
			<button type="button" id="csv_immunizations"  class="nosh_button_extlink">Export CSV</button><br><br>
		</div>
		<div id="imm_order" style="display:none">
			<button type="button" id="add_immunization1" class="nosh_button_add">Add</button>
			<button type="button" id="edit_immunization1" class="nosh_button_edit">Edit</button>
			<button type="button" id="delete_immunization1" class="nosh_button_delete">Delete</button>
			<button type="button" id="vis_immunization1" class="nosh_button_check">Vacccine Information Sheets and Consent</button>
			<button type="button" id="print_immunizations1" class="nosh_button_print">Print List</button>
			<button type="button" id="csv_immunizations1" class="nosh_button_extlink">Export CSV</button><br><br>
		</div>
	<?php }?>
	<input type="hidden" name="imm_text" id="imm_text"/>
</div>
<div id="edit_immunization_dialog" title="">
	<form name="edit_immunization_form" id="edit_immunization_form" class="pure-form pure-form-aligned">
		<input type="hidden" name="imm_id" id="imm_id"/>
		<input type="hidden" name="imm_cvxcode" id="imm_cvxcode"/>
		<div class="pure-control-group"><label for="imm_immunization">Immunization:</label><input type="text" name="imm_immunization" id="imm_immunization" style="width:400px" class="text" required/></div>
		<div class="pure-control-group"><label for="imm_sequence">Sequence:</label><select id ="imm_sequence" name="imm_sequence" class="text class_imm_sequence"></select></div>
		<div class="pure-control-group"><label for="imm_elsewhere">Given Elsewhere:</label><input type="checkbox" name="imm_elsewhere" id="imm_elsewhere" value="Yes" class="text"/></div>
		<div class="pure-control-group"><label for="imm_route">Route:</label><select id ="imm_route" name="imm_route" class="text class_imm_route"></select></div>
		<div class="pure-control-group"><label for="imm_body_site">Body Site:</label><select id ="imm_body_site" name="imm_body_site" class="text class_imm_body_site"></select></div>
		<div class="pure-control-group"><label for="imm_dosage">Dosage:</label><input type="text" name="imm_dosage" id="imm_dosage" class="text"/></div>
		<div class="pure-control-group"><label for="imm_dosage_unit">Unit:</label><input type="text" name="imm_dosage_unit" id="imm_dosage_unit" class="text"/></div>
		<div class="pure-control-group"><label for="imm_lot">Lot Number:</label><input type="text" name="imm_lot" id="imm_lot" class="text"/></div>
		<div class="pure-control-group"><label for="imm_manufacturer">Manufacturer:</label><input type="text" name="imm_manufacturer" id="imm_manufacturer" class="text"/></div>
		<div class="pure-control-group"><label for="imm_expiration">Expiration Date:</label><input type="text" name="imm_expiration" id="imm_expiration" class="text class_imm_expiration"/></div>
		<div class="pure-control-group"><label for="imm_date">Date Active:</label><input type="text" name="imm_date" id="imm_date" class="text"/></div>
	</form>
</div>
<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3') {?>
	<div id="edit_immunization_dialog1" title="">
		<form name="edit_immunization_form1" id="edit_immunization_form1" class="pure-form pure-form-aligned">
			<input type="hidden" name="imm_id" id="imm_id1"/>
			<input type="hidden" name="cpt" id="imm_cpt" required/>
			<input type="hidden" name="imm_cvxcode" id="imm_cvxcode1" required/>
			<input type="hidden" name="vaccine_id" id="imm_vaccine_id"/>
			<div class="pure-control-group"><label for="imm_immunization1">Immunization:</label><input type="text" name="imm_immunization" id="imm_immunization1" style="width:400px" class="text" required/></div>
			<div class="pure-control-group"><label for="imm_sequence1">Sequence:</label><select id ="imm_sequence1" name="imm_sequence" class="text class_imm_sequence"></select></div>
			<div class="pure-control-group"><label for="imm_vis1">Vaccine Information Sheet Given: <input type="checkbox" name="imm_vis" id="imm_vis1" value="Yes" class="text"/></div>
			<div class="pure-control-group"><label for="imm_route1">Route:</label><select id ="imm_route1" name="imm_route" class="text class_imm_route"></select></div>
			<div class="pure-control-group"><label for="imm_body_site1">Body Site:</label><select id ="imm_body_site1" name="imm_body_site" class="text class_imm_body_site"></select></div>
			<div class="pure-control-group"><label for="imm_dosage1">Dosage:</label><input type="text" name="imm_dosage" id="imm_dosage1" class="text"/></div>
			<div class="pure-control-group"><label for="imm_dosage_unit1">Unit:</label><input type="text" name="imm_dosage_unit" id="imm_dosage_unit1" class="text"/></div>
			<div class="pure-control-group"><label for="imm_lot1">Lot Number:</label><input type="text" name="imm_lot" id="imm_lot1" class="text"/></div>
			<div class="pure-control-group"><label for="imm_manufacturer1">Manufacturer:</label><input type="text" name="imm_manufacturer" id="imm_manufacturer1" class="text"/></div>
			<div class="pure-control-group"><label for="imm_expiration1">Expiration Date:</label><input type="text" name="imm_expiration" id="imm_expiration1" class="text class_imm_expiration"/></div>
			<div class="pure-control-group"><label for="imm_date1">Date Active:</label><input type="text" name="imm_date" id="imm_date1" class="text"/></div>
		</form>
	</div>
	<div id="immunizations_vis_dialog" title="Vaccine Information Sheets">
		<ul>
			<li><a id="vis_dtap" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/dtap.pdf" target="_blank">DTaP</a></li>
			<li><a id="vis_hep_a" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/hep-a.pdf" target="_blank">Hepatitis A</a></li>
			<li><a id="vis_hep_b" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/hep-b.pdf" target="_blank">Hepatitis B</a></li>
			<li><a id="vis_hib" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/hib.pdf" target="_blank">Hib</a></li>
			<li><a class="vis_hpv" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/hpv-ceravix.pdf" target="_blank">HPV - Ceravix</a></li>
			<li><a class="vis_hpv" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/hpv-gardasil.pdf" target="_blank">HPV - Gardasil</a></li>
			<li><a id="vis_flulive" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/flulive.pdf" target="_blank">Flu (Live, Intranasal)</a></li>
			<li><a id="vis_flu" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/flu.pdf" target="_blank">Flu (Inactivated)</a></li>
			<li><a id="vis_mmr" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/mmr.pdf" target="_blank">MMR</a></li>
			<li><a id="vis_mening" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/mening.pdf" target="_blank">Meningococcal</a></li>
			<li><a id="vis_pcv" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/pcv13.pdf" target="_blank">Pneumococcal (PCV13)</a></li>
			<li><a id="vis_ppv" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/ppv.pdf" target="_blank">Pneumococcal (PPSV23)</a></li>
			<li><a id="vis_ipv" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/ipv.pdf" target="_blank">Polio</a></li>
			<li><a id="vis_rotavirus" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/rotavirus.pdf" target="_blank">Rotavirus</a></li>
			<li><a id="vis_shingles" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/shingles.pdf" target="_blank">Shingles</a></li>
			<li><a id="vis_tdap" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/tdap.pdf" target="_blank">Tdap</a></li>
			<li><a id="vis_td" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/td.pdf" target="_blank">Td</a></li>
			<li><a id="vis_varicella" href="http://www.cdc.gov/vaccines/hcp/vis/vis-statements/varicella.pdf" target="_blank">Varicella (Chickenpox)</a></li>     
		</ul>
		<form class="pure-form pure-form-stacked">
			<label for="consent_vaccine_list">Immunizations to be given:</label><input type="text" name="consent_vaccine_list" id="consent_vaccine_list" style="width:400px" class="text">
			<button type="button" id="consent_immunization1" class="nosh_button_check">Consent Form</button>
		</form>
	</div>
<?php }?>
<div id="imm_notes_dialog" title="Immunization Notes">
	<form id="imm_notes_form" class="pure-form pure-form-aligned">
		<input type="hidden" id="imm_notes_old"/>
		<div class="pure-control-group"><label for="imm_notes">Immunization Notes:</label><textarea name="imm_notes" id="imm_notes" rows="4" style="width:300px" class="text"></textarea></div>
	</form>
</div>
