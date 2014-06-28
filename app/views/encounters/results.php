<?php echo HTML::script('/js/results.js');?>
<button type="button" id="oh_results_open" class="nosh_button_copy">Test Results</button>
<hr class="ui-state-default" style="width:99%"/>
<input type="hidden" name="oh_results_old" id="oh_results_old"/>
<form id="oh_results_form" class="pure-form pure-form-stacked">
	<label for="oh_results">Reviewed Test Results: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="4" name="oh_results" id="oh_results" class="text textdump"></textarea>
</form><br><br>
<table id="oh_results_encounters" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="oh_results_encounters_pager" class="scroll" style="text-align:center;"></div>
