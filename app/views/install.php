<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<input type="hidden" id="progress_input"/>
		<h3>Welcome to the NOSH ChartingSystem Installation</h3>
		<form id="install" class="pure-form pure-form-aligned">
			<p style="font-size:13px;">Please fill out the entries to complete the installation of NOSH ChartingSystem.</p>
			<p style="font-size:13px;">You will need to establish a Google Gmail account to be able to send e-mail from the system for patient appointment reminders, non-Protected Health Information messages, and faxes.</p>
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
					<?php echo Form::label('state:', 'State:');?>
					<select name="state" id="state" class="text"></select>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('zip:', 'Zip:');?>
					<?php echo Form::text('zip','',array('id'=>'zip','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('phone:', 'Phone:');?>
					<?php echo Form::text('phone','',array('id'=>'phone','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('fax:', 'Fax:');?>
					<?php echo Form::text('fax','',array('id'=>'fax','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('documents_dir', 'System Directory for Patient Documents:');?>
					<?php echo Form::text('documents_dir','',array('id'=>'documents_dir','style'=>'width:500px','class'=>'text', 'title'=>'Format: /directory/subdirectory/subdirectory/', 'readonly'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('smtp_user', 'Gmail Username for Sending E-mail:');?>
					<?php echo Form::text('smtp_user','',array('id'=>'smtp_user','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('smtp_pass', 'Gmail Password for Sending E-mail:');?>
					<?php echo Form::password('smtp_pass', array('id'=>'smtp_pass','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<br>
				<button type='button' id='install_submit'>Install NOSH ChartingSystem</button>
			</div>
		</form>
	</div>
</div>
