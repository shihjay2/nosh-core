<?php echo HTML::script('/js/proc.js');?>
<input type="hidden" id="procedure_type_old"/>
<input type="hidden" id="procedure_cpt_old"/>
<input type="hidden" id="procedure_description_old"/>
<input type="hidden" id="procedure_complications_old"/>
<input type="hidden" id="procedure_ebl_old"/>
<form id="procedure_form" class="pure-form pure-form-stacked">
	<button type="button" id="procedure_type_reset" class="nosh_button_add">Create New Template</button>
	<button type="button" id="template_procedure" class="nosh_button_save">Save Template</button>
	<span id="procedure_template_status"></span>
	<hr class="ui-state-default"/>
	<input type="hidden" name="procedurelist_id" id="procedurelist_id">
	<div class="pure-g">
		<div class="pure-u-1-6"></div>
		<div class="pure-u-5-6">
			<label for="procedure_type">Type:</label><input type="text" style="width:500px" name="proc_type" id="procedure_type" class="text" required/>
		</div>
		<div class="pure-u-1-6"><br><button type="button" id="procedure_cpt_reset" class="nosh_button_cancel">Clear</button></div>
		<div class="pure-u-5-6">
			<label for="procedure_cpt">CPT:</label><input type="text" style="width:500px" name="proc_cpt" id="procedure_cpt" class="text" required/>
		</div>
		<div class="pure-u-1-6"><br><button type="button" id="procedure_description_reset" class="nosh_button_cancel">Clear</button></div>
		<div class="pure-u-5-6">
			<label for="">Description:</label><textarea style="width:500px" rows="10" name="proc_description" id="procedure_description" class="text" required></textarea>
		</div>
		<div class="pure-u-1-6"><br><button type="button" id="procedure_complications_reset" class="nosh_button_cancel">Clear</button></div>
		<div class="pure-u-5-6">
			<label for="procedure_complications">Complications:</label><textarea style="width:500px" rows="1" name="proc_complications" id="procedure_complications" class="text" required></textarea>
		</div>
		<div class="pure-u-1-6"><br><button type="button" id="procedure_ebl_reset" class="nosh_button_cancel">Clear</button></div>
		<div class="pure-u-5-6">
			<label for="procedure_ebl">Estimated Blood Loss:</label><textarea style="width:500px" rows="1" name="proc_ebl" id="procedure_ebl" class="text" required></textarea>
		</div>
	</div>
</form>
