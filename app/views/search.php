<?php if(Session::get('group_id') != '100') {?>
<script type="text/javascript">
	noshdata.pid = '<?php if (Session::get('pid')) {echo Session::get('pid');}?>';
	noshdata.group_id = '<?php echo Session::get('group_id');?>';
</script>
<div id ="header2">
	<div id ="searchbar">
		<div id="logo" class="innersearchbar" style="line-height:67px;width:100px;">Nosh</div>
		<div class="innersearchbar" style="line-height:67px;width:445px;">
			<?php if ($patient_centric == 'n') {?>
				<form class="pure-form">
					<input type="text" name="searchpt" id="searchpt" style="width:295px; font-size:1em;" class="text" placeholder="Search patient and then select to open the chart."/>
					<input type="hidden" name="hidden_pid" id="hidden_pid">
					<input type="hidden" name="hidden_eid" id="hidden_eid">
					<button type="button" id="openNewPatient">New Patient</button>&nbsp
				</form>
			<?php }?>
		</div>
		<div class="innersearchbar" style="width:300px;font-size:1.1em;">
			<?php if ($pt != '') { if (Route::getCurrentRoute()->getPath() != 'chart') {echo HTML::linkRoute('chart', '[Active Patient Chart: ' . $pt . ']');} else {echo '<br><a href="#" id="chart_panel">[Active Patient Chart: ' . $pt . ']</a>';}}?>
			<br>
			<span id="encounter_link_span">
				<?php if ($encounter != '' && Session::get('group_id') != '4') { if (Route::getCurrentRoute()->getPath() != 'chart') {echo HTML::linkRoute('encounter', '[Active Encounter #: ' . $encounter . ']');} else {echo '<a href="#" id="encounter_panel">[Active Encounter #: ' . $encounter . ']</a>';}}?>
			</span>
		</div>
		<div class="innersearchbar" style="float:right;line-height:67px;width:110px;font-size:1.1em;">
			<?php if ($pt != '' && $patient_centric == 'n') {echo HTML::image('images/cancel.png', 'Cancel', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle'));}?>
			<?php if ($pt != '' && $patient_centric == 'n') {echo HTML::linkRoute('closechart', 'Close Chart');}?>
		</div>
	</div>
</div>
<div id="search_dialog" title="Confirmation">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0"></span>Opening a new chart requires closing the chart for <?php echo Session::get('ptname');?>. Are you sure?</p>
</div>
<div id="new_patient" title="Add New Patient">
	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>All fields are required.<br>
	Add and Open Chart command will close any existing open charts!<br>
	<br>
	<form name="new_patient_form" id="new_patient_form" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
			<label for="lastname">Last Name</label>
			<input type="text" name="lastname" id="lastname" size="42" class="text" required/>
		</div>
		<div class="pure-control-group">
			<label for="firstname">First Name</label>
			<input type="text" name="firstname" id="firstname" size="42" class="text" required/>
		</div>
		<div class="pure-control-group">
			<label for="DOB">Date of Birth</label>
			<input type="text" name="DOB" id="DOB" class="text" required/>
		</div>
		<div class="pure-control-group">
			<label for="gender">Gender</label>
			<select name="gender" id="gender" class="text" required></select>
		</div>
	</form>
</div>
<?php } else {?>
<div id = "header2">
	<div id ="searchbar">
		<table>
		<tr><td><div id="logo1">Nosh</div></td>
		</tr></table>
	</div>
</div>
<?php }?>
