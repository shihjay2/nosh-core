<?php echo HTML::script('/js/clinicalsupport.js');?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<li id="encounter_tabs_situation"><a href="#encounter_tabs_1">Situation</a></li>
			<li id="encounter_tabs_background"><a href="#encounter_tabs_2">Background</a></li>
			<li id="encounter_tabs_labs"><a href="#encounter_tabs_3">Labs</a></a></li>
			<li id="encounter_tabs_proc"><a href="#encounter_tabs_4">Procedure</a></li>
			<li id="encounter_tabs_assessment"><a href="#encounter_tabs_5">Assessment</a></li>
			<li id="encounter_tabs_orders"><a href="#encounter_tabs_6">Orders</a></li>
		</ul>
		<div id="encounter_tabs_1" style="overflow:auto">
			<div id="situation_form" class="pure-g">
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="situation">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="16" name="situation" id="situation" class="text textdump"></textarea><input type="hidden" name="situation_old" id="situation_old"/>
					</form>
					<br><button type="button" id="situation_reset" class="reset nosh_button">Clear</button>
				</div>
				<div class="pure-u-11-24">
					<button type="button" id="situation_orders_pending" class="nosh_button">Orders Pending</button><br><br>
					<form class="pure-form pure-form-stacked">
						<label for="hpi_pf_template">Patient Forms:</label><select id="hpi_pf_template" class="text"></select>
						<label for="situation_template">Choose Template</label><select id="situation_template" class="situation_template_choose text"></select>
					</form>
					<div class="situation_template_div">
						<br><form id="situation_template_form" class="situation_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
		</div>
		<div id="encounter_tabs_2" style="overflow:auto">
			<?php echo $oh;?>
		</div>
		<div id="encounter_tabs_3" style="overflow:auto">
			<?php echo $labs;?>
		</div>
		<div id="encounter_tabs_4" style="overflow:auto">
			<?php echo $proc;?>
		</div>
		<div id="encounter_tabs_5" style="overflow:auto">
			<?php echo $assessment;?>
		</div>
		<div id="encounter_tabs_6" style="overflow:auto">
			<?php echo $orders;?>
		</div>
	</div>
</div>
