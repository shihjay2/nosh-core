<?php echo HTML::script('js/labs.js'); ?>
<form id="encounters_labs">
	<div class="pure-g">
		<div class="pure-u-1-2">
			<span id="button_labs_ua_status" class="labs_tooltip"></span>
			<button type="button" id="button_labs_ua" class="nosh_button">Dipstick UA</button><br><br>
			<span id="button_labs_rapid_status" class="labs_tooltip"></span>
			<button type="button" id="button_labs_rapid" class="nosh_button">Rapid Tests</button><br><br>
			<span id="button_labs_micro_status" class="labs_tooltip"></span>
			<button type="button" id="button_labs_micro" class="nosh_button">Microscopy</button><br><br>
			<span id="button_labs_other_status" class="labs_tooltip"></span>
			<button type="button" id="button_labs_other" class="nosh_button">Other</button><br><br>
		</div>
		<div class="pure-u-1-2"></div>
	</div>
</form>
<div id="labs_ua_dialog" title="Dipstick Urinalysis">
	<form id="labs_ua_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="labs_ua_urobili">Urobilinogen:</label>
			<select name="labs_ua_urobili" id="labs_ua_urobili" class="text">
				<option value="">Select One</option>
				<option value="Normal">Normal</option>
				<option value="2 mg/Dl">2 mg/Dl</option>
				<option value="4 mg/Dl">4 mg/Dl</option>
				<option value="8 mg/Dl">8 mg/Dl</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_bilirubin">Bilirubin:</label>
			<select name="labs_ua_bilirubin" id="labs_ua_bilirubin" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="+">+</option>
				<option value="++">++</option>
				<option value="+++">+++</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_ketones">Ketones:</label>
			<select name="labs_ua_ketones" id="labs_ua_ketones" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="5 mg/Dl">5 mg/Dl</option>
				<option value="15 mg/Dl">15 mg/Dl</option>
				<option value="40 mg/Dl">40 mg/Dl</option>
				<option value="80 mg/Dl">80 mg/Dl</option>
				<option value="160 mg/Dl">160 mg/Dl</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_glucose">Glucose:</label>
			<select name="labs_ua_glucose" id="labs_ua_glucose" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="50 mg/Dl">50 mg/Dl</option>
				<option value="100 mg/Dl">100 mg/Dl</option>
				<option value="250 mg/Dl">250 mg/Dl</option>
				<option value="500 mg/Dl">500 mg/Dl</option>
				<option value="1000 mg/Dl">1000 mg/Dl</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_protein">Protein:</label>
			<select name="labs_ua_protein" id="labs_ua_protein" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Trace">Trace</option>
				<option value="30 mg/Dl">30 mg/Dl</option>
				<option value="100 mg/Dl">100 mg/Dl</option>
				<option value="300 mg/Dl">300 mg/Dl</option>
				<option value="2000 mg/Dl">2000 mg/Dl</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_nitrites">Nitrites:</label>
			<select name="labs_ua_nitrites" id="labs_ua_nitrites" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Positive">Positive</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_leukocytes">Leukocytes:</label>
			<select name="labs_ua_leukocytes" id="labs_ua_leukocytes" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Trace">Trace</option>
				<option value="+">+</option>
				<option value="++">++</option>
				<option value="+++">+++</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_blood">Blood:</label>
			<select name="labs_ua_blood" id="labs_ua_blood" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Trace">Trace</option>
				<option value="+">+</option>
				<option value="++">++</option>
				<option value="+++">+++</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_ph">pH:</label>
			<select name="labs_ua_ph" id="labs_ua_ph" class="text">
				<option value="">Select One</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="6.5">6.5</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_spgr">Specific gravity:</label>
			<select name="labs_ua_spgr" id="labs_ua_spgr" class="text">
				<option value="">Select One</option>
				<option value="1.000">1.000</option>
				<option value="1.005">1.005</option>
				<option value="1.010">1.010</option>
				<option value="1.015">1.015</option>
				<option value="1.020">1.020</option>
				<option value="1.025">1.025</option>
				<option value="1.030">1.030</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_color">Color:</label>
			<select name="labs_ua_color" id="labs_ua_color" class="text">
				<option value="">Select One</option>
				<option value="Colorless">Colorless</option>
				<option value="Yellow">Yellow</option>
				<option value="Amber">Amber</option>
				<option value="Orange">Orange</option>
				<option value="Green">Green</option>
				<option value="Red">Red</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_ua_clarity">Clarity:</label>
			<select name="labs_ua_clarity" id="labs_ua_clarity" class="text">
				<option value="">Select One</option>
				<option value="Clear">Clear</option>
				<option value="Hazy">Hazy</option>
				<option value="Cloudy">Cloudy</option>
				<option value="Turbid">Turbid</option>
			</select>
		</div>
	</form>
</div>
<div id="labs_rapid_dialog" title="Rapid Tests">
	<form id="labs_rapid_form" class="pure-form pure-form-aligned">
		<?php if (Session::get('gender') == 'female') {?>
			<div class="pure-control-group">
				<label for="labs_upt">Urine HcG:</label>
				<select name="labs_upt" id="labs_upt" class="text">
					<option value="">Select One</option>
					<option value="Negative">Negative</option>
					<option value="Positive">Positive</option>
				</select>
			</div>
		<?php }?>
		<div class="pure-control-group">
			<label for="labs_strep">Rapid Strep:</label>
			<select name="labs_strep" id="labs_strep" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Positive">Positive</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_mono">Mono Spot:</label>
			<select name="labs_mono" id="labs_mono" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Positive">Positive</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_flu">Rapid Influenza:</label>
			<select name="labs_flu" id="labs_flu" class="text">
				<option value="">Select One</option>
				<option value="Negative">Negative</option>
				<option value="Positive">Positive</option>
			</select>
		</div>
		<div class="pure-control-group">
			<label for="labs_flu">Fingerstick Glucose:</label>
			<input type="text" name="labs_glucose" id="labs_glucose" style="width:60px" class="text"/>
		</div>
	</form>
</div>
<div id="labs_micro_dialog" title="Microscopy">
	<form id="labs_micro_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="labs_microscope">Microscopy:</label>
			<textarea style="width:500px" rows="3" name="labs_microscope" id="labs_microscope" class="text"></textarea>
		</div>
	</form>
</div>
<div id="labs_other_dialog" title="Other">
	<form id="labs_other_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="labs_other">Other tests:</label>
			<textarea style="width:500px" rows="3" name="labs_other" id="labs_other" class="text"></textarea>
		</div>
	</form>
</div>
