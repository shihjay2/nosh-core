<?php echo HTML::script('/js/standardmtm.js');?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<li id="encounter_tabs_hpi"><a href="#encounter_tabs_1">HPI</a></li>
			<li id="encounter_tabs_assessment"><a href="#encounter_tabs_2">DX</a></li>
			<li id="encounter_tabs_orders"><a href="#encounter_tabs_3">Orders</a></li>
		</ul>
		<div id="encounter_tabs_1" style="overflow:auto">
			<div id="hpi_form" class="pure-g">
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="hpi">Preview: <span class="textdump_text"></span> for templates.</label><textarea style="width:95%" rows="16" name="hpi" id="hpi" class="text textdump"></textarea><input type="hidden" name="hpi_old" id="hpi_old"/>
					</form>
					<br><button type="button" id="hpi_reset" class="reset nosh_button">Clear</button><?php echo $cpe;?><?php echo $wcc;?><?php echo $preg;?><?php echo $mtm;?>
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
			<?php echo $assessment;?>
		</div>
		<div id="encounter_tabs_3" style="overflow:auto">
			<?php echo $orders;?>
		</div>
	</div>
</div>
