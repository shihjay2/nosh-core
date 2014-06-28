<?php echo HTML::script('/js/standardmtm.js');?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<li id="encounter_tabs_hpi"><a href="#encounter_tabs_1">MTM Tools</a></li>
			<li id="encounter_tabs_orders"><a href="#encounter_tabs_2">MTM Claims</a></li>
			<li id="encounter_tabs_oh"><a href="#encounter_tabs_3">History</a></li>
			<li id="encounter_tabs_vitals"><a href="#encounter_tabs_4">Vitals</a></li>
			<li id="encounter_tabs_results"><a href="#encounter_tabs_5">Results/Outcomes</a></li>
			<li id="encounter_tabs_assessment"><a href="#encounter_tabs_6">Assessment</a></li>
			<li id="encounter_tabs_medications"><a href="#encounter_tabs_7">Medications</a></li>
		</ul>
		<div id="encounter_tabs_1" style="overflow:auto">
			<div id="hpi_form" class="pure-g">
				<div class="pure-u-13-24">
					<button type="button" id="hpi_reset" class="reset nosh_button">Clear</button><?php echo $mtm;?>
					<form class="pure-form pure-form-stacked">
						<label for="hpi">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="16" name="hpi" id="hpi" class="text textdump"></textarea><input type="hidden" name="hpi_old" id="hpi_old"/>
					</form>
				</div>
				<div class="pure-u-11-24">
					<form class="pure-form pure-form-stacked">
						<label for="hpi_pf_template">Patient Forms:</label><select id="hpi_pf_template" class="text"></select>
						<label for="hpi_template">Choose Template</label><select id="hpi_template" class="hpi_template_choose text"></select>
					</form>
					<div class="hpi_template_div">
						<br><form id="hpi_template_form" class="hpi_template_form ui-widget pure-form"></form>
					</div>
				</div>
			</div>
		</div>
		<div id="encounter_tabs_2" style="overflow:auto">
			<?php echo $orders;?>
		</div>
		<div id="encounter_tabs_3" style="overflow:auto">
			<?php echo $oh;?>
		</div>
		<div id="encounter_tabs_4" style="overflow:auto">
			<?php echo $vitals;?>
		</div>
		<div id="encounter_tabs_5" style="overflow:auto">
			<?php echo $results;?>
		</div>
		<div id="encounter_tabs_6" style="overflow:auto">
			<?php echo $assessment;?>
		</div>
		<div id="encounter_tabs_7" style="overflow:auto">
			<?php echo $medications;?>
		</div>
	</div>
</div>
