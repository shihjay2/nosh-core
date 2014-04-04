<?php echo HTML::script('/js/standardmedical.js');?>
<div id="noshtabs">
	<div id="encounter_tabs">
		<ul>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_hpi"><a href="#encounter_tabs_1">HPI</a></li>
			<?php }?>
			<li id="encounter_tabs_ros"><a href="#encounter_tabs_2">ROS</a></li>
			<li id="encounter_tabs_oh"><a href="#encounter_tabs_3">History</a></li>
			<li id="encounter_tabs_vitals"><a href="#encounter_tabs_4">VS</a></li>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_pe"><a href="#encounter_tabs_5">PE</a></li>
			<?php }?>
			<li id="encounter_tabs_labs"><a href="#encounter_tabs_6">Labs</a></a></li>
			<li id="encounter_tabs_proc"><a href="#encounter_tabs_7">Procedure</a></li>
			<?php if(Session::get('group_id') == '2') {?>
				<li id="encounter_tabs_assessment"><a href="#encounter_tabs_8">DX</a></li>
			<?php }?>
			<li id="encounter_tabs_orders"><a href="#encounter_tabs_9">Orders</a></li>
		</ul>
		<?php if(Session::get('group_id') == '2') {?>
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
		<?php }?>
		<div id="encounter_tabs_2" style="overflow:auto">
			<?php echo $ros;?>
		</div>
		<div id="encounter_tabs_3" style="overflow:auto">
			<?php echo $oh;?>
		</div>
		<div id="encounter_tabs_4" style="overflow:auto">
			<?php echo $vitals;?>
		</div>
		<?php if(Session::get('group_id') == '2') {?>
			<div id="encounter_tabs_5" style="overflow:auto">
				<?php echo $pe;?>
			</div>
		<?php }?>
		<div id="encounter_tabs_6" style="overflow:auto">
			<?php echo $labs;?>
		</div>
		<div id="encounter_tabs_7" style="overflow:auto">
			<?php echo $proc;?>
		</div>
		<?php if(Session::get('group_id') == '2') {?>
			<div id="encounter_tabs_8" style="overflow:auto">
				<?php echo $assessment;?>
			</div>
		<?php }?>
		<div id="encounter_tabs_9" style="overflow:auto">
			<?php echo $orders;?>
		</div>
	</div>
</div>

