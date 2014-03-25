<?php echo HTML::script('js/orders.js'); ?>
<input type="hidden" name="orders_plan_old" id="orders_plan_old"/>
<input type="hidden" name="orders_duration_old" id="orders_duration_old"/>
<input type="hidden" name="orders_followup_old" id="orders_followup_old"/>
<form id="orders_form" class="pure-form pure-form-stacked">
	<button type="button" id="print_orders" class="nosh_button_print">Print Orders Summary</button>
	<hr class="ui-state-default"/>
	<div class="pure-g">
		<div class="pure-u-17-24">
			<div class="pure-g">
				<div class="pure-u-1-4">
					<br><button type="button" id="orders_plan_reset" class="nosh_button_cancel" style="width:105px">Clear</button><br>
					<button type="button" id="orders_plan_instructions" class="nosh_button" style="width:105px">Instructions</button><br>
					<button type="button" id="encounter_letter" class="nosh_button_edit" style="width:105px">Letter</button>
					<?php if($mtm == 'y') {?>
						<br><button type="button" id="encounter_mtm" class="nosh_button" style="width:105px">MTM</button>
					<?php }?>
				</div>
				<div class="pure-u-3-4">
					<label for="orders_plan">Recommendations:</label>
					<textarea style="width:95%" rows="10" name="plan" id="orders_plan" class="text"></textarea>
				</div>
				<div class="pure-u-1-4">
					<br><button type="button" id="orders_schedule" class="nosh_button_calendar" style="width:105px">Schedule</button>
				</div>
				<div class="pure-u-3-4">
					<label for="orders_followup">Follow Up:</label>
					<textarea style="width:95%" rows="10" name="followup" id="orders_followup" class="text"></textarea>
				</div>
				<div class="pure-u-1-4">
					<br><button type="button" id="orders_duration_reset" class="nosh_button_cancel" style="width:105px">Clear</button>
				</div>
				<div class="pure-u-3-4">
					<label for="orders_plan">Face-to-face time (in minutes), if greater than 50% of the visit:</label>
					<input type="text" style="width:95%" name="duration" id="orders_duration" class="text"/>
				</div>
			</div>
		</div>
		<div class="pure-u-7-24">
			<span id="button_orders_labs_status" class="orders_tooltip"></span><button type="button" id="button_orders_labs" class="nosh_button" style="width:140px">Lab</button><br><br>
			<span id="button_orders_rad_status" class="orders_tooltip"></span><button type="button" id="button_orders_rad" class="nosh_button" style="width:140px">Imaging</button><br><br>
			<span id="button_orders_cp_status" class="orders_tooltip"></span><button type="button" id="button_orders_cp" class="nosh_button" style="width:140px">Cardiopulmonary</button><br><br>
			<span id="button_orders_ref_status" class="orders_tooltip"></span><button type="button" id="button_orders_ref" class="nosh_button" style="width:140px">Referrals</button><br><br>
			<span id="button_orders_rx_status" class="orders_tooltip"></span><button type="button" id="button_orders_rx" class="nosh_button" style="width:140px">RX</button><br><br>
			<span id="button_orders_sup_status" class="orders_tooltip"></span><button type="button" id="button_orders_supplements" class="nosh_button" style="width:140px">Supplements</button><br><br>
			<span id="button_orders_imm_status" class="orders_tooltip"></span><button type="button" id="button_orders_imm" class="nosh_button" style="width:140px">Immunizations</button><br><br>
		</div>
	</div>
</form>
<div id="orders_plan_instructions_dialog" title="Patient Instructions">
	<form class="pure-form">
		<textarea style="width:750px" rows="8" name="instructions_chosen" id="instructions_chosen" class="text" placeholder="Patient instructions."></textarea>
	</form>
</div>
<div id="edit_orders_dialog" title="Edit Orders Text">
	<form id="edit_orders_form" class="pure-form pure-form-stacked">
		<label for="edit_orders_dialog_text">Text:</label>
		<textarea style="width:450px" rows="4" name="test" id="edit_orders_dialog_text" class="text"></textarea>
	</form>
</div>
<div id="instructions_dialog_load1" title="Checking...">
	<?php echo HTML::image('images/indicator.gif', 'Loading'); ?> Loading Vivacare Patient Education Materials.
</div>
