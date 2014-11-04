<div id="practice_choose">
	<div align="center" >
		<div id="box" align="left" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
			<div align="center">Your HIEofOne Identity has more than one associated practice NPI's.<br>Choose a practice NPI you want to associate with this patient's NOSH service.<br><br></div>
			<form method="POST" action="practice_choose" class="pure-form pure-form-stacked">
				<?php echo $practice_npi_select; ?>
				<div align="center">
					<input type="submit" id="practice_submit_button" value="Select Practice" name="select practice" class="ui-button ui-state-default ui-corner-all"/>
				</div>
			</form>
		</div>
	</div>
</div>
