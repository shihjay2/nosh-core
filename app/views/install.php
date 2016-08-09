<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<input type="hidden" id="progress_input"/>
		<h3>Welcome to the NOSH ChartingSystem Installation</h3>
		<form id="install" class="pure-form pure-form-aligned" style="display:none">
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
					<?php echo Form::label('documents_dir', 'System Directory for Patient Documents:');?>
					<?php echo Form::text('documents_dir','',array('id'=>'documents_dir','style'=>'width:500px','class'=>'text documents_dir', 'title'=>'Format: /directory/subdirectory/subdirectory/', 'readonly'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('smtp_user', 'Gmail Username for Sending E-mail:');?>
					<?php echo Form::text('smtp_user','',array('id'=>'smtp_user','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<br>
				<button type='button' id='install_submit'>Install NOSH ChartingSystem</button>
			</div>
		</form>
		<form id="install_patient_form" class="pure-form pure-form-aligned" style="display:none">
			<p style="font-size:13px;">Please fill out the entries to complete the installation of NOSH ChartingSystem.</p>
			<p style="font-size:13px;">You will need to establish a Google Gmail account to be able to send e-mail from the system for patient appointment reminders, non-Protected Health Information messages, and faxes.</p>
			<br><br><div style="width: 50%;margin: 0 auto;">
				<div class="pure-control-group">
					<?php echo Form::label('username1','Administrator Username:');?>
					<?php echo Form::text('username','admin', array('id'=>'username1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('password1','Administrator Password:');?>
					<?php echo Form::password('password', array('id'=>'password1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('conf_password1', 'Password Confirm:');?>
					<?php echo Form::password('conf_password', array('id'=>'conf_password1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('pt_username','Portal Username:');?>
					<?php echo Form::text('pt_username','', array('id'=>'pt_username','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('lastname','Last Name:');?>
					<?php echo Form::text('lastname','', array('id'=>'lastname','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('firstname','First Name:');?>
					<?php echo Form::text('firstname','', array('id'=>'firstname','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('DOB','Date of Birth:');?>
					<?php echo Form::text('DOB','', array('id'=>'DOB','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('gender','Gender:');?>
					<?php echo Form::select('gender', array('m'=>'Male','f'=>'Female'), null, array('id'=>'gender','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('email1', 'Email:');?>
					<?php echo Form::text('email','',array('id'=>'email1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('address', 'Street Address:');?>
					<?php echo Form::text('address','',array('id'=>'address','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('city1', 'City:');?>
					<?php echo Form::text('city','',array('id'=>'city1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('state1', 'State:');?>
					<select name="state" id="state1" class="text state"></select>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('zip1', 'Zip:');?>
					<?php echo Form::text('zip','',array('id'=>'zip1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('documents_dir1', 'System Directory for Patient Documents:');?>
					<?php echo Form::text('documents_dir','',array('id'=>'documents_dir1','style'=>'width:500px','class'=>'text documents_dir', 'title'=>'Format: /directory/subdirectory/subdirectory/', 'readonly'));?>
				</div>
				<div class="pure-control-group">
					<?php echo Form::label('smtp_user1', 'Gmail Username for Sending E-mail:');?>
					<?php echo Form::text('smtp_user','',array('id'=>'smtp_user1','required','style'=>'width:500px','class'=>'text'));?>
				</div>
				<br>
				<button type='button' id='install_submit1'>Install NOSH ChartingSystem</button>
			</div>
		</form>
	</div>
	<div id="install_choose_dialog" title="Choose Installation Type">
		<button type='button' id='install_practice' title="Choose this method if you are a medical practice" style="width:310px">Install Practice Centric NOSH ChartingSystem</button><br><br>
		<button type='button' id='install_patient' title="Choose this method if you are a patient" style="width:310px">Install Patient Centric NOSH ChartingSystem</button>
	</div>
</div>
