<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<input type="hidden" id="progress_input"/>
		<h3>Welcome to the NOSH ChartingSystem Installation for Patients</h3>
		<form id="install" class="pure-form pure-form-aligned" style="display:none">
			<p style="font-size:13px;">Please fill out the entries to complete the installation of NOSH ChartingSystem.</p>
			<p style="font-size:13px;">You will need to establish a Google Gmail account to be able to send e-mail from the system for patient appointment reminders, non-Protected Health Information messages, and faxes.</p>
			<br><br><div style="width: 50%;margin: 0 auto;">
				<div class="pure-control-group">
					<?php echo Form::label('url','URL of your Authorization Server:');?>
					<?php echo Form::text('url', array('id'=>'username','required','style'=>'width:500px','class'=>'text'));?>
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
	</div>
</div>
