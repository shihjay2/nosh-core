<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<input type="hidden" id="progress_input"/>
		<h3>Welcome to the NOSH ChartingSystem Practice Registration</h3>
		<form id="install" class="pure-form pure-form-aligned">
			<input type="hidden" name="practice_id" id="practice_id" value="<?php echo $practice_id;?>"/>
			<p style="font-size:13px;">Please fill out the entries to complete the installation of NOSH ChartingSystem.</p>
			<br><br><div style="width: 50%;margin: 0 auto;">
				<div class="pure-control-group">
					<?php echo Form::label('username','Administrator Username:');?>
					<?php echo Form::text('username','admin', array('id'=>'username','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('password','Administrator Password:');?>
					<?php echo Form::password('password', array('id'=>'password','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('conf_password', 'Password Confirm:');?>
					<?php echo Form::password('conf_password', array('id'=>'conf_password','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('email', 'Email:');?>
					<?php echo Form::text('email','',array('id'=>'email','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('practice_name', 'Practice Name:');?>
					<?php echo Form::text('practice_name','',array('id'=>'practice_name','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('street_address1', 'Street Address:');?>
					<?php echo Form::text('street_address1','',array('id'=>'street_address1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('street_address2', 'Street Address Line 2:');?>
					<?php echo Form::text('street_address2','',array('id'=>'street_address2','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('city', 'City:');?>
					<?php echo Form::text('city','',array('id'=>'city','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('state', 'State:');?>
					<select name="state" id="state" class="text state"></select>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('zip', 'Zip:');?>
					<?php echo Form::text('zip','',array('id'=>'zip','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('phone', 'Phone:');?>
					<?php echo Form::text('phone','',array('id'=>'phone','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('fax', 'Fax:');?>
					<?php echo Form::text('fax','',array('id'=>'fax','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('practicehandle', 'Practice Handle:');?>
					<?php echo Form::text('practicehandle','',array('id'=>'practicehandle','style'=>'width:500px','class'=>'text','title'=>'A practice handle must not contain any spaces'));?>
				</div>
				<br>
				A practice handle is how any user for your practice can access your NOSH ChartingSystem account directly.<br>For instance, the web address to access your account would be:<br><?php echo $patient_portal;?>/start/<span id="practicehandleval"></span><br>
				<br>
				<button type='button' id='install_submit'>Register</button>
			</div>
		</form>
	</div>
</div>
