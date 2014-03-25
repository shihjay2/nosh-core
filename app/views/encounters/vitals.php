<?php echo HTML::script('js/vitals.js'); ?>
<div id="vitals_fieldset">
	<input type="hidden" name="vitals_weight_old" id="vitals_weight_old"/>
	<input type="hidden" name="vitals_height_old" id="vitals_height_old"/>
	<input type="hidden" name="vitals_BMI_old" id="vitals_BMI_old"/>
	<input type="hidden" name="vitals_temp_old" id="vitals_temp_old"/>
	<input type="hidden" name="vitals_temp_method_old" id="vitals_temp_method_old"/>
	<input type="hidden" name="vitals_bp_systolic_old" id="vitals_bp_systolic_old"/>
	<input type="hidden" name="vitals_bp_diastolic_old" id="vitals_bp_diastolic_old"/>
	<input type="hidden" name="vitals_bp_position_old" id="vitals_bp_position_old"/>
	<input type="hidden" name="vitals_pulse_old" id="vitals_pulse_old"/>
	<input type="hidden" name="vitals_respirations_old" id="vitals_respirations_old"/>
	<input type="hidden" name="vitals_o2_sat_old" id="vitals_o2_sat_old"/>
	<input type="hidden" name="vitals_vitals_other_old" id="vitals_vitals_other_old"/>
	<?php if (Session::get('agealldays') <6574.5) { if (Session::get('agealldays') > 730.5) {?>
		Growth Charts: <button type="button" class="nosh_button weight_chart">Weight</button> <button type="button" class="nosh_button height_chart">Height</button> <button type="button" class="nosh_button hc_chart">Head Circumference</button> <button type="button" class="nosh_button bmi_chart">BMI</button> <button type="button" class="nosh_button weight_height_chart">Weight-Height</button><br><br>
	<?php } else {?>
		Growth Charts: <button type="button" class="nosh_button weight_chart">Weight</button> <button type="button" class="nosh_button height_chart">Height</button> <button type="button" class="nosh_button hc_chart">Head Circumference</button> <button type="button" class="nosh_button weight_height_chart">Weight-Height</button><br><br>
	<?php }}?>
	<form id="vitals_form" class="pure-form pure-form-stacked">
		<div class="pure-g">
			<div class="pure-u-1-4"><label for="vitals_weight">Weight (<?php echo $practiceInfo->weight_unit;?>):</label><input type="text" name="weight" id="vitals_weight" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_height">Height (<?php echo $practiceInfo->height_unit;?>):</label><input type="text" name="height" id="vitals_height" style="width:60px" class="text"></div>
			<div class="pure-u-1-4">BMI:<br><span id="vitals_bmi_display"></span><input type="hidden" name="BMI" id="vitals_BMI"></div>
			<?php if (Session::get('agealldays') <6574.5) {?>
				<div class="pure-u-1-4"><label for="vitals_headcircumference">Head Circumference (<?php echo $practiceInfo->hc_unit;?>):</label><input type="hidden" name="vitals_headcircumference_old" id="vitals_headcircumference_old"/><input type="text" name="headcircumference" id="vitals_headcircumference" style="width:60px" class="text"></div>
			<?php } else {?>
				<div class="pure-u-1-4"><input type="hidden" name="vitals_headcircumference_old" id="vitals_headcircumference_old"/><input type="hidden" name="headcircumference" id="vitals_headcircumference"></div>
			<?php }?>
			<div class="pure-u-1-4"><label for="vitals_temp">Temperature (<?php echo $practiceInfo->temp_unit;?>):</label><input type="text" name="temp" id="vitals_temp" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_temp_method">Temperature Method</label><select name="temp_method" id="vitals_temp_method" class="text"></select></div>
			<div class="pure-u-1-2"></div>
			<div class="pure-u-1-4"><label for="vitals_bp_systolic">Systolic Blood Pressure:</label><input type="text" name="bp_systolic" id="vitals_bp_systolic" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_bp_diastolic">Diastolic Blood Pressure:</label><input type="text" name="bp_diastolic" id="vitals_bp_diastolic" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_bp_position">Blood Pressure Position:</label><select name="bp_position" id="vitals_bp_position" class="text"></select></div>
			<div class="pure-u-1-4"></div>
			<div class="pure-u-1-4"><label for="vitals_pulse">Pulse:</label><input type="text" name="pulse" id="vitals_pulse" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_respirations">Respirations:</label><input type="text" name="respirations" id="vitals_respirations" style="width:60px" class="text"></div>
			<div class="pure-u-1-4"><label for="vitals_o2_sat">Oxygen saturation:</label><input type="text" name="o2_sat" id="vitals_o2_sat" style="width:60px" class="text"></div>
			<div class="pure-u-1"><label for="vitals_vitals_other">Notes:</label><input type="text" name="vitals_other" id="vitals_vitals_other" style="width:500px" class="text"></div>
		</div>
	</form>
	<br><br>
	<table id="vitals_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="vitals_list_pager" class="scroll" style="text-align:center;"></div>
</div>
