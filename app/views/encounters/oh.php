<?php echo HTML::script('/js/oh.js');?>
<input type="hidden" name="oh_pmh_old" id="oh_pmh_old" value=""/>
<input type="hidden" name="oh_psh_old" id="oh_psh_old" value=""/>
<input type="hidden" name="oh_fh_old" id="oh_fh_old" value=""/>
<form id="oh_form" class="pure-form pure-form-stacked">
	<button type="button" id="copy_oh" class="nosh_button_copy">Copy From Most Recent Encounter</button>
	<hr class="ui-state-default" style="width:99%"/>
	<div class="pure-g">
		<div class="pure-u-17-24">
			<div class="pure-g">
				<div class="pure-u-1-5">
					<br><br><button type="button" id="oh_pmh_reset" class="nosh_button_cancel" style="width:75px">Clear</button><br>
					<button type="button" id="oh_pmh_issues" class="nosh_button" style="width:75px">Issues</button>
				</div>
				<div class="pure-u-4-5">
					<label for="oh_pmh">Past Medical History: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="4" name="oh_pmh" id="oh_pmh" class="text textdump" placeholder="Type a few letters to search and select from ICD9 database."></textarea>
					<label for="oh_pmh_pf_template">Patient Forms for Past Medical History:</label><select id="oh_pmh_pf_template"></select>
				</div>
				<div class="pure-u-1-5">
					<br><br><button type="button" id="oh_psh_reset" class="nosh_button_cancel" style="width:75px">Clear</button><br>
					<button type="button" id="oh_psh_issues" class="nosh_button" style="width:75px">Issues</button>
				</div>
				<div class="pure-u-4-5">
					<label for="oh_psh">Past Surgical History: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="4" name="oh_psh" id="oh_psh" class="text textdump" placeholder="Type a few letters to search and select from ICD9 database."></textarea>
					<label for="oh_psh_pf_template">Patient Forms for Past Surgical History:</label><select id="oh_psh_pf_template"></select>
				</div>
				<div class="pure-u-1-5">
					<br><br><button type="button" id="oh_fh_reset" class="nosh_button_cancel" style="width:75px">Clear</button><br>
					<button type="button" id="oh_fh_icd" class="nosh_button" style="width:75px">ICD</button>
				</div>
				<div class="pure-u-4-5">
					<label for="oh_fh">Family History: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="4" name="oh_fh" id="oh_fh" class="text textdump"></textarea>
					<label for="oh_fh_pf_template">Patient Forms for Family History:</label><select id="oh_fh_pf_template"></select>
				</div>
			</div>
		</div>
		<div class="pure-u-7-24">
			<span id="button_oh_sh_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_sh" class="nosh_button" style="width:125px">Social History</button><br><br>
			<span id="button_oh_meds_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_meds" class="nosh_button" style="width:125px">Medications</button><br><br>
			<?php if (Session::get('encounter_template') == 'standardmedical' || Session::get('encounter_template') == 'clinicalsupport'|| Session::get('encounter_template') == 'standardmtm') {?>
				<span id="button_oh_supplements_status" class="oh_tooltip"></span>
				<button type="button" id="button_oh_supplements" class="nosh_button" style="width:125px">Supplements</button><br><br>
			<?php }?>
			<span id="button_oh_allergies_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_allergies" class="nosh_button" style="width:125px">Allergies</button><br><br>
			<span id="button_oh_etoh_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_etoh" class="nosh_button" style="width:125px">Alcohol Use</button><br><br>
			<span id="button_oh_tobacco_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_tobacco" class="nosh_button" style="width:125px">Tobacco Use</button><br><br>
			<span id="button_oh_drugs_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_drugs" class="nosh_button" style="width:125px">Illicit Drug Use</button><br><br>
			<span id="button_oh_employment_status" class="oh_tooltip"></span>
			<button type="button" id="button_oh_employment" class="nosh_button" style="width:125px">Employment</button><br><br>
			<?php if (Session::get('encounter_template') == 'standardpsych' || Session::get('encounter_template') == 'standardpsych1') {?>
				<span id="button_oh_psychosocial_status" class="oh_tooltip"></span>
				<button type="button" id="button_oh_psychosocial" class="nosh_button" style="width:125px">Psychosocial</button><br><br>
				<span id="button_oh_developmental_status" class="oh_tooltip"></span>
				<button type="button" id="button_oh_developmental" class="nosh_button" style="width:125px">Developmental</button><br><br>
				<span id="button_oh_medtrials_status" class="oh_tooltip"></span>
				<button type="button" id="button_oh_medtrials" class="nosh_button" style="width:125px">Medication Trials</button><br><br>
			<?php }?>
		</div>
	</div>
</form>
<div id="oh_fh_helper_dialog" title="Family History Helper">
	<form class="pure-form pure-form-stacked">
		<label="fh_fm">Family member:</label><input type="text" id="fh_fm" style="width:400px" class="text"/>
		<label="fh_icd">Medical condition:</label><input type="text" id="fh_icd" style="width:400px" class="text"/>
	</form>
</div>
<div id="oh_sh_dialog" title="Social History" style="overflow:hidden;">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_sh_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_sh">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_sh" id="oh_sh" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form class="pure-form pure-form-stacked">
				<label for="oh_sh_pf_template">Patient Forms:</label><select id="oh_sh_pf_template" class="text"></select>
			</form>
			<br><button type="button" id="save_oh_sh_form" class="nosh_button_save">Save</button><button type="button" id="cancel_oh_sh_form" class="nosh_button_cancel">Cancel</button><button type="button" id="oh_sh_reset" class="nosh_button">Clear</button><br><br>
			<form id="oh_sh_form" class="pure-form">
				<div class="oh_buttonset"><label for="oh_sh_marital_status">Marital Status:</label><br><select name="marital_status" id="oh_sh_marital_status" style="width:164px" class="text"></select><input type="hidden" id="oh_sh_marital_status_old"></div><br>
				<div class="oh_buttonset"><label for="oh_sh_partner_name">Spouse/Partner Name:</label><br><input type="text" name="partner_name" id="oh_sh_partner_name" style="width:164px" class="text"/><input type="hidden" id="oh_sh_partner_name_old"></div><br>
				<div class="oh_buttonset"><label for="sh1">Family members in the household:</label><br><input type="text" name="sh1" id="sh1" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh2">Children:</label><br><input type="text" name="sh2" id="sh2" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh3">Pets:</label><br><input type="text" name="sh3" id="sh3" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh4">Diet:</label><br><input type="text" name="sh4" id="sh4" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh5">Exercise:</label><br><input type="text" name="sh5" id="sh5" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh6">Sleep:</label><br><input type="text" name="sh6" id="sh6" style="width:250px" class="text"/></div><br>
				<div class="oh_buttonset"><label for="sh7">Hobbies:</label><br><input type="text" name="sh7" id="sh7" style="width:250px" class="text"/></div><br>
				<?php if (Session::get('agealldays') > 730.48 && Session::get('agealldays') <= 6574.32) {?>
					<div class="oh_buttonset"><label for="sh8">Child Care:</label><br><input type="text" name="sh8" id="sh8" style="width:250px" class="text"/></div><br>
				<?php }?>
				<div class="oh_buttonset">
					<span>Sexually Active:</span><br>
					<input name="sh9" id="sh9_n" value="Not sexually active." type="radio"/><label for="sh9_n">No</label>
					<input name="sh9" id="sh9_y" value="Sexually active." type="radio"/><label for="sh9_y">Yes</label>
				</div><br>
				<div class="oh_buttonset">
					<span>Number of sexual partners:</span><br>
					<input name="sh10" id="sh10_n" value="One current sexual partner." type="radio"/><label for="sh10_n">One</label>
					<input name="sh10" id="sh10_y" value="Multiple current sexual partners." type="radio"/><label for="sh10_y">Multiple</label>
				</div><br>
				<div class="oh_buttonset">
					<span>Sex Partner Preference:</span><br>
					<input name="sh11" id="sh11_1" value="Heterosexual." type="radio"/><label for="sh11_1">Heterosexual</label>
					<input name="sh11" id="sh11_2" value="Homosexual." type="radio"/><label for="sh11_2">Homosexual</label>
					<input name="sh11" id="sh11_3" value="Bisexual." type="radio"/><label for="sh11_3">Bisexual</label>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="oh_etoh_dialog" title="Alcohol">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_etoh_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_etoh">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_etoh" id="oh_etoh" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<button type="button" id="save_oh_etoh_form" class="nosh_button_save">Save</button><button type="button" id="cancel_oh_etoh_form" class="nosh_button_cancel">Cancel</button><button type="button" id="oh_etoh_reset" class="nosh_button">Clear</button><br><br>
			<form id="oh_etoh_form" class="pure-form">
				<div class="oh_buttonset">
					<span>Alcohol Use</span><br>
					<input name="oh_etoh_select" id="oh_etoh_n" value="No alcohol use." type="radio"><label for="oh_etoh_n">No</label>
					<input name="oh_etoh_select" id="oh_etoh_y" value="Frequency of alcohol use: " type="radio"><label for="oh_etoh_y">Yes</label>
				</div>
				<div id="oh_etoh_input" style="display:none">
					<label for="oh_etoh_text">Frequency:</label><input type="text" name="oh_etoh_text" id="oh_etoh_text" style="width:300px" class="text" />
				</div>
			</form>
		</div>
	</div>
</div>
<div id="oh_tobacco_dialog" title="Tobacco">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_tobacco_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_tobacco">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_tobacco" id="oh_tobacco" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<button type="button" id="save_oh_tobacco_form" class="nosh_button_save">Save</button><button type="button" id="cancel_oh_tobacco_form" class="nosh_button_cancel">Cancel</button><button type="button" id="oh_tobacco_reset" class="nosh_button">Clear</button><br><br>
			<form id="oh_tobacco_form" class="pure-form">
				<div class="oh_buttonset">
					<span>Tobacco Use</span><br>
					<input name="oh_tobacco_select" id="oh_tobacco_n" value="No tobacco use." type="radio"><label for="oh_tobacco_n">No</label>
					<input name="oh_tobacco_select" id="oh_tobacco_y" value="Frequency of tobacco use: " type="radio"><label for="oh_tobacco_y">Yes</label>
				</div>
				<div id="oh_tobacco_input" style="display:none">
					<label for="oh_tobacco_text">Frequency:</label><input type="text" name="oh_tobacco_text" id="oh_tobacco_text" style="width:300px" class="text" />
				</div>
			</form>
		</div>
	</div>
</div>
<div id="oh_drugs_dialog" title="Drugs">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_drugs_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_drugs">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_drugs" id="oh_drugs" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<button type="button" id="save_oh_drugs_form" class="nosh_button_save">Save</button><button type="button" id="cancel_oh_drugs_form" class="nosh_button_cancel">Cancel</button><button type="button" id="oh_drugs_reset" class="nosh_button">Clear</button><br><br>
			<form id="oh_drugs_form" class="pure-form">
				<div class="oh_buttonset">
					<span>Drug Use:</span><br>
					<input name="oh_drugs_select" id="oh_drugs_n" value="No illicit drug use." type="radio"><label for="oh_drugs_n">No</label>
					<input name="oh_drugs_select" id="oh_drugs_y"value="Type of drug use: " type="radio"><label for="oh_drugs_y">Yes</label>
				</div>
				<div id="oh_drugs_input" style="display:none">
					<label for="oh_drugs_text">Type:</label><input type="text" name="oh_drugs_text" id="oh_drugs_text" style="width:300px" class="text" /><br>
					<label for="oh_drugs_text1">Frequency:</label><input type="text" name="oh_drugs_text1" id="oh_drugs_text1" style="width:300px" class="text" />
				</div>
			</form>
		</div>
	</div>
</div>
<div id="oh_employment_dialog" title="Employment">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_employment_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_employment">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_employment" id="oh_employment" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<button type="button" id="save_oh_employment_form" class="nosh_button_save">Save</button><button type="button" id="cancel_oh_employment_form" class="nosh_button_cancel">Cancel</button><button type="button" id="oh_employment_reset" class="nosh_button">Clear</button><br><br>
			<form id="oh_employment_form" class="pure-form">
				<div class="oh_buttonset">
					<span>Employment Status:</span><br>
					<input name="oh_employment_select" id="oh_employment_1" value="Employed." type="radio"><label for="oh_employment_1">Employed</label>
					<input name="oh_employment_select" id="oh_employment_2" value="Unemployed." type="radio"><label for="oh_employment_2">Unemployed</label>
					<input name="oh_employment_select" id="oh_employment_3" value="Student." type="radio"><label for="oh_employment_3">Student</label>
					<input name="oh_employment_select" id="oh_employment_4" value="Disabled." type="radio"><label for="oh_employment_4">Disabled</label>
					<input name="oh_employment_select" id="oh_employment_5" value="Retired." type="radio"><label for="oh_employment_5">Retired</label>
					<input name="oh_employment_select" id="oh_employment_6" value="Homemaker." type="radio"><label for="oh_employment_6">Homemaker</label>
				</div>
				<div id="oh_employment_input" style="display:none">
					<label for="oh_employment_text">Employment Field:</label><input type="text" name="oh_employment_text" id="oh_employment_text" style="width:300px" class="text" /><br>
					<label for="oh_employment_employer">Employer:</label><input type="text" name="employer" id="oh_employment_employer" style="width:300px" class="text"/><input type="hidden" id="oh_employment_employer_old">
				</div>
			</form>
		</div>
	</div>
</div>
<div id="oh_psychosocial_dialog" title="Psychosocial">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_psychosocial_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_psychosocial">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_psychosocial" id="oh_psychosocial" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form id="oh_psychosocial_form" class="pure-form">
			</form>
		</div>
	</div>
</div>
<div id="oh_developmental_dialog" title="Developmental">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_developmental_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_developmental">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_developmental" id="oh_developmental" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form id="oh_developmental_form" class="pure-form">
			</form>
		</div>
	</div>
</div>
<div id="oh_medtrials_dialog" title="Past Medication Trials">
	<div class="pure-g">
		<div class="pure-u-13-24">
			<form id="oh_medtrials_dialog_form" class="pure-form pure-form-stacked">
				<label for="oh_medtrials">Preview: <span class="textdump_text"></span> for templates.</label><textarea name="oh_medtrials" id="oh_medtrials" rows="20" style="width:95%" class="text textdump"></textarea>
			</form>
		</div>
		<div class="pure-u-11-24">
			<form id="oh_medtrials_form" class="pure-form">
			</form>
		</div>
	</div>
</div>
