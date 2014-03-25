<?php echo HTML::script('js/standardmedical.js'); ?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_hpi"><a href="#encounter_tabs_1">HPI</a></li>
			<?php }?>
			<li id="encounter_tabs_ros"><a href="ajaxencounter/ros">ROS</a></li>
			<li id="encounter_tabs_oh"><a href="ajaxencounter/oh">History</a></li>
			<li id="encounter_tabs_vitals"><a href="ajaxencounter/vitals">VS</a></li>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_pe"><a href="ajaxencounter/pe">PE</a></li>
			<?php }?>
			<li id="encounter_tabs_labs"><a href="ajaxencounter/labs">Labs</a></a></li>
			<li id="encounter_tabs_proc"><a href="ajaxencounter/proc">Procedure</a></li>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_assessment"><a href="ajaxencounter/assessment">DX</a></li>
			<?php }?>
			<li id="encounter_tabs_orders"><a href="ajaxencounter/orders">Orders</a></li>
		</ul>
		<div id="encounter_tabs_1" style="overflow:auto">
			<div id="hpi_form" class="pure-g">
				<div class="pure-u-13-24">
					<form class="pure-form pure-form-stacked">
						<label for="hpi">Preview:</label><textarea style="width:95%" rows="16" name="hpi" id="hpi" class="text"></textarea><input type="hidden" name="hpi_old" id="hpi_old"/>
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
	</div>
</div>

