<div id="extensions_dialog" title="Extensions">
	<form id="extensions_form" class="pure-form pure-form-stacked">
		<div id="extensions_accordion">
			<h3>DrFirst Rcopia Integration</h3>
			<div>
				<label for="rcopia_extension">Enable DrFirst Rcopia Extension:</label>
				<select name="rcopia_extension" id="rcopia_extension" class="text"></select>
				<label for="rcopia_apiVendor">DrFirst Rcopia Vendor Username for the Practice (apiVendor):</label>
				<input type="text" name="rcopia_apiVendor" id="rcopia_apiVendor" class="text" style="width:400px"/>
				<label for="rcopia_apiPass">DrFirst Rcopia Vendor Password for the Practice (apiPass):</label>
				<input type="text" name="rcopia_apiPass" id="rcopia_apiPass" class="text" style="width:400px"/>
				<label for="rcopia_apiPractice">DrFirst Rcopia Practice Username (apiPractice):</label>
				<input type="text" name="rcopia_apiPractice" id="rcopia_apiPractice" class="text" style="width:400px"/>
				<label for="rcopia_apiSystem">DrFirst Rcopia Vendor Name (apiSystem):</label>
				<input type="text" name="rcopia_apiSystem" id="rcopia_apiSystem" class="text" style="width:400px"/>
			</div>
			<h3>Updox Sync Integration</h3>
			<div>
				<label for="updox_extension">Enable Updox Sync Extension:</label>
				<select name="updox_extension" id="updox_extension" class="text"></select>
			</div>
			<h3>Vivacare Patient Education Materials</h3>
			<div>
				<label for="vivacare">Username for Vivacare (XXXXXX in http://www.XXXXXX.fromyourdoctor.com when you registered):</label>
				<input type="text" name="vivacare" id="vivacare" class="text" style="width:400px"/><br><br>
				<a href="https://vivacare.com/provider/register/register.jsp" target="_blank">Register at Vivacare for free.</a>
			</div>
			<?php if (Session::get('practice_id') == '1') {?>
				<h3>SNOMED-CT</h3>
				<div>
					<label for="snomed_extension">Enable SNOMED-CT Extension:</label>
					<select name="snomed_extension" id="snomed_extension" class="text"></select>
				</div>
			<?php }?>
			<h3>Medicare Medication Therapy Management (MTM) Integration</h3>
			<div>
				<label for="mtm_extension">Enable Medicare Medication Therapy Management (MTM) Extension:</label>
				<select name="mtm_extension" id="mtm_extension" class="text"></select>
				<label for="mtm_alert_users">Medication Therapy Management (MTM) Extension Alert Providers:</label>
				<select name="mtm_alert_users[]" id="mtm_alert_users" multiple="multiple" style="width:400px" class="text"></select>
			</div>
			<h3>PeaceHealth Laboratories</h3>
			<div>
				<label for="peacehealth_id">Practice ID number:</label>
				<input type="text" name="peacehealth_id" id="peacehealth_id" class="text" style="width:400px"/>
			</div>
		</div>
	</form>
</div>
