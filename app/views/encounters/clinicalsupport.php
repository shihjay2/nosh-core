<?php echo HTML::script('js/clinicalsupport.js'); ?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<li id="encounter_tabs_situation"><a href="#encounter_tabs_1">Situation</a></li>
			<li id="encounter_tabs_background"><a href="ajaxencounter/oh">Background</a></li>
			<li id="encounter_tabs_labs"><a href="ajaxencounter/labs">Labs</a></a></li>
			<li id="encounter_tabs_proc"><a href="ajaxencounter/proc">Procedure</a></li>
			<li id="encounter_tabs_assessment"><a href="ajaxencounter/assessment">Assessment</a></li>
			<li id="encounter_tabs_orders"><a href="ajaxencounter/orders">Orders</a></li>
		</ul>
		<div id="encounter_tabs_1" style="overflow:auto">
			<div id="situation_form" class="pure-g">
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="situation">Preview:</label><textarea style="width:95%" rows="16" name="situation" id="situation" class="text"></textarea><input type="hidden" name="situation_old" id="situation_old"/>
					</form>
					<br><button type="button" id="situation_reset" class="reset nosh_button">Clear</button>
				</div>
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="hpi_pf_template">Patient Forms:</label><select id="hpi_pf_template" class="text"></select>
					</form>
					<br><button type="button" id="situation_orders_pending" class="nosh_button">Orders Pending</button>
				</div>
			</div>
		</div>
	</div>
</div>
