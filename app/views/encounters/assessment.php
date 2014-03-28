<?php echo HTML::script('js/assessment.js'); ?>
<input type="hidden" name="assessment_icd1_old" id="assessment_icd1_old"/>
<input type="hidden" name="assessment_icd2_old" id="assessment_icd2_old"/>
<input type="hidden" name="assessment_icd3_old" id="assessment_icd3_old"/>
<input type="hidden" name="assessment_icd4_old" id="assessment_icd4_old"/>
<input type="hidden" name="assessment_icd5_old" id="assessment_icd5_old"/>
<input type="hidden" name="assessment_icd6_old" id="assessment_icd6_old"/>
<input type="hidden" name="assessment_icd7_old" id="assessment_icd7_old"/>
<input type="hidden" name="assessment_icd8_old" id="assessment_icd8_old"/>
<input type="hidden" name="assessment_icd9_old" id="assessment_icd9_old"/>
<input type="hidden" name="assessment_icd10_old" id="assessment_icd10_old"/>
<input type="hidden" name="assessment_icd11_old" id="assessment_icd11_old"/>
<input type="hidden" name="assessment_icd12_old" id="assessment_icd12_old"/>
<input type="hidden" name="assessment_1_old" id="assessment_1_old"/>
<input type="hidden" name="assessment_2_old" id="assessment_2_old"/>
<input type="hidden" name="assessment_3_old" id="assessment_3_old"/>
<input type="hidden" name="assessment_4_old" id="assessment_4_old"/>
<input type="hidden" name="assessment_5_old" id="assessment_5_old"/>
<input type="hidden" name="assessment_6_old" id="assessment_6_old"/>
<input type="hidden" name="assessment_7_old" id="assessment_7_old"/>
<input type="hidden" name="assessment_8_old" id="assessment_8_old"/>
<input type="hidden" name="assessment_9_old" id="assessment_9_old"/>
<input type="hidden" name="assessment_10_old" id="assessment_10_old"/>
<input type="hidden" name="assessment_11_old" id="assessment_11_old"/>
<input type="hidden" name="assessment_12_old" id="assessment_12_old"/>
<input type="hidden" name="assessment_other_old" id="assessment_other_old"/>
<input type="hidden" name="assessment_ddx_old" id="assessment_ddx_old"/>
<input type="hidden" name="assessment_notes_old" id="assessment_notes_old"/>
<form class="pure-form">
	Choose diagnoses from <button type="button" id="assessment_issues"  class="nosh_button">Issues Helper</button> or <input type="text" name="assessment_icd_search" id="assessment_icd_search" size="50" class="text" placeholder="Search from ICD9 database."/>
</form>
<div id="assessment_buttons">
	<button type="button" id="assessment_select_icd_1" class="nosh_button assessment_select">#1</button>
	<button type="button" id="assessment_select_icd_2" class="nosh_button assessment_select">#2</button>
	<button type="button" id="assessment_select_icd_3" class="nosh_button assessment_select">#3</button>
	<button type="button" id="assessment_select_icd_4" class="nosh_button assessment_select">#4</button>
	<button type="button" id="assessment_select_icd_5" class="nosh_button assessment_select">#5</button>
	<button type="button" id="assessment_select_icd_6" class="nosh_button assessment_select">#6</button>
	<button type="button" id="assessment_select_icd_7" class="nosh_button assessment_select">#7</button>
	<button type="button" id="assessment_select_icd_8" class="nosh_button assessment_select">#8</button>
	<button type="button" id="assessment_select_icd_9" class="nosh_button assessment_select">#9</button>
	<button type="button" id="assessment_select_icd_10" class="nosh_button assessment_select">#10</button>
	<button type="button" id="assessment_select_icd_11" class="nosh_button assessment_select">#11</button>
	<button type="button" id="assessment_select_icd_12" class="nosh_button assessment_select">#12</button>
	<button type="button" id="assessment_select_icd_13" class="nosh_button">Additional DX</button>
	<button type="button" id="assessment_select_icd_14" class="nosh_button">Differential DX</button>
</div>
<hr class="ui-state-default"/>
<form id="assessment_form" class="pure-form pure-form-stacked">
	<input type="hidden" name="assessment_icd1" id="assessment_icd1" class="text"/>
	<input type="hidden" name="assessment_icd2" id="assessment_icd2" class="text"/>
	<input type="hidden" name="assessment_icd3" id="assessment_icd3" class="text"/>
	<input type="hidden" name="assessment_icd4" id="assessment_icd4" class="text"/>
	<input type="hidden" name="assessment_icd5" id="assessment_icd5" class="text"/>
	<input type="hidden" name="assessment_icd6" id="assessment_icd6" class="text"/>
	<input type="hidden" name="assessment_icd7" id="assessment_icd7" class="text"/>
	<input type="hidden" name="assessment_icd8" id="assessment_icd8" class="text"/>
	<input type="hidden" name="assessment_icd9" id="assessment_icd9" class="text"/>
	<input type="hidden" name="assessment_icd10" id="assessment_icd10" class="text"/>
	<input type="hidden" name="assessment_icd11" id="assessment_icd11" class="text"/>
	<input type="hidden" name="assessment_icd12" id="assessment_icd12" class="text"/>
	<input type="hidden" name="assessment_1" id="assessment_1" class="text"/>
	<input type="hidden" name="assessment_2" id="assessment_2" class="text"/>
	<input type="hidden" name="assessment_3" id="assessment_3" class="text"/>
	<input type="hidden" name="assessment_4" id="assessment_4" class="text"/>
	<input type="hidden" name="assessment_5" id="assessment_5" class="text"/>
	<input type="hidden" name="assessment_6" id="assessment_6" class="text"/>
	<input type="hidden" name="assessment_7" id="assessment_7" class="text"/>
	<input type="hidden" name="assessment_8" id="assessment_8" class="text"/>
	<input type="hidden" name="assessment_9" id="assessment_9" class="text"/>
	<input type="hidden" name="assessment_10" id="assessment_10" class="text"/>
	<input type="hidden" name="assessment_11" id="assessment_11" class="text"/>
	<input type="hidden" name="assessment_12" id="assessment_12" class="text"/>
	<div id="assessment_icd1_div_button" style="display:none"><span id="assessment_icd1_div"></span> <button type="button" id="clear_icd_1" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd2_div_button" style="display:none"><span id="assessment_icd2_div"></span> <button type="button" id="clear_icd_2" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd3_div_button" style="display:none"><span id="assessment_icd3_div"></span> <button type="button" id="clear_icd_3" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd4_div_button" style="display:none"><span id="assessment_icd4_div"></span> <button type="button" id="clear_icd_4" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd5_div_button" style="display:none"><span id="assessment_icd5_div"></span> <button type="button" id="clear_icd_5" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd6_div_button" style="display:none"><span id="assessment_icd6_div"></span> <button type="button" id="clear_icd_6" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd7_div_button" style="display:none"><span id="assessment_icd7_div"></span> <button type="button" id="clear_icd_7" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd8_div_button" style="display:none"><span id="assessment_icd8_div"></span> <button type="button" id="clear_icd_8" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd9_div_button" style="display:none"><span id="assessment_icd9_div"></span> <button type="button" id="clear_icd_9" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd10_div_button" style="display:none"><span id="assessment_icd10_div"></span> <button type="button" id="clear_icd_10" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd11_div_button" style="display:none"><span id="assessment_icd11_div"></span> <button type="button" id="clear_icd_11" class="nosh_button_cancel assessment_clear">Clear</button><br></div>
	<div id="assessment_icd12_div_button" style="display:none"><span id="assessment_icd12_div"></span> <button type="button" id="clear_icd_12" class="nosh_button_cancel assessment_clear">Clear</button><br></div><br>
	<div class="pure-g">
		<div class="pure-u-1-6">
			<br><button type="button" id="assessment_other_reset"  class="nosh_button_cancel">Clear</button>
		</div>
		<div class="pure-u-5-6">
			<label for="assessment_other">Additional diagnoses:</label>
			<textarea rows="4" style="width:500px" name="assessment_other" id="assessment_other" class="text"></textarea>
		</div>
		<div class="pure-u-1-6">
			<br><button type="button" id="assessment_ddx_reset"  class="nosh_button_cancel">Clear</button>
		</div>
		<div class="pure-u-5-6">
			<label for="assessment_ddx">Differential diagnoses:</label>
			<textarea rows="4" style="width:500px" name="assessment_ddx" id="assessment_ddx" class="text"></textarea>
		</div>
		<div class="pure-u-1-6">
			<br><button type="button" id="assessment_notes_reset"  class="nosh_button_cancel">Clear</button>
		</div>
		<div class="pure-u-5-6">
			<label for="assessment_notes">Assessment discussion:</label>
			<textarea rows="4" style="width:500px" name="assessment_notes" id="assessment_notes" class="text"></textarea>
		</div>
	</div>
</form>
