<script type="text/javascript">
	noshdata.practice_id = '<?php echo $practice_id;?>';
	noshdata.login_shake = '<?php if ($error = $errors->first("password")) { echo 'y'; } elseif (isset($attempts)) { echo 'y'; } else { echo 'n';}?>';
</script>
<div id="login">
	<div id="logo" align="center" style="width:100%;">Nosh</div><br>
	<div style="width:100%"><div id="login_practice_logo" align="center" style="max-height:100px;"></div></div><br>
	<div align="center">
		<?php if ($patient_centric == 'y') {?>
			<div id="box" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
				<div id="openid_box" class="pure-g">
					<div class="pure-u-1">
						<div id="openid_connect_logo" align="center" style="max-height:100px;">
							<i class="fa fa-openid fa-fw fa-4x" style="vertical-align:middle;padding:2px"></i>
						</div>
					</div>
					<div class="pure-u-1">Login with OpenID Connect.</div><div class="pure-u-1"><br><br></div>
					<div class="pure-u-1-2"><?php echo link_to_route('uma_auth', "I'm the Patient", $parameters = array(), $attributes = array('class'=>'nosh_button'));?></div>
					<div class="pure-u-1-2"><?php echo link_to_route('oidc', "I'm a Provider", $parameters = array(), $attributes = array('class'=>'nosh_button'));?></div>
					<div class="pure-u-1"><br><br><a href="#" id="open_regular_box">Standard Login for Administrator</a></div>
					<?php if (route('home') == 'https://shihjay.xyz/nosh' || route('home') == 'https://agropper.xyz/nosh') { ?>
						<div class="pure-u-1"><br><br><?php echo link_to_route('reset_demo', "Reset Demo", $parameters = array(), $attributes = array('class'=>'nosh_button'));?></div>
					<?php }?>
				</div>
				<div id="regular_box" style="display:none">
					<form method="POST" action="login" class="pure-form pure-form-stacked">
						<div class="pure-control-group"><label for="username">Username:</label><input type="text" id="username" name="username" class="text" style="width:300px" /></div>
						<div class="pure-control-group"><label for="password">Password:</label><input type="password" id="password" name="password" class="text" style="width:300px" /></div>
						<?php if ($patient_centric == 'n') {?>
							<div class="pure-control-group"><label for="practice_id">Organization/Practice:</label><select name="practice_id" id="practice_id" class="text" /></select></div>
						<?php }?>
						<br><br>
						<div id="error_text">
							<?php if ($error = $errors->first("password")) { echo $error . "<br><br>"; } ?>
							<?php if (isset($attempts)) { echo $attempts . "<br><br>"; } ?>
						</div> 
						<div align="center">
							<input type="submit" id="login_button" value="Login" name="login" class="ui-button ui-state-default ui-corner-all"/>
							<br><br>
							<a href="#" id="open_openid_box">OpenID Connect Login</a>
						</div>
					</form>
				</div>
			</div>
			<br><br>
		<?php } else {?>
			<div id="box" align="left" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
				<form method="POST" action="login" class="pure-form pure-form-stacked">
					<div class="pure-control-group"><label for="username">Username:</label><input type="text" id="username" name="username" class="text" style="width:300px" /></div>
					<div class="pure-control-group"><label for="password">Password:</label><input type="password" id="password" name="password" class="text" style="width:300px" /></div>
					<?php if ($patient_centric == 'n') {?>
						<div class="pure-control-group"><label for="practice_id">Organization/Practice:</label><select name="practice_id" id="practice_id" class="text" /></select></div>
					<?php }?>
					<br><br>
					<div id="error_text">
						<?php if ($error = $errors->first("password")) { echo $error . "<br><br>"; } ?>
						<?php if (isset($attempts)) { echo $attempts . "<br><br>"; } ?>
					</div> 
					<div align="center">
						<input type="submit" id="login_button" value="Login" name="login" class="ui-button ui-state-default ui-corner-all"/>
					</div>
				</form>
			</div>
			<br>
			<?php if ($patient_centric == 'n') {?>
				<a href="#" id="register" style="font-size:14px;">Are you new to the Patient Portal?</a><br><br>
			<?php }?>
		<?php }?>
		<a href="#" id="forgot_password"style="font-size:14px;">Did you forget your password?</a><br><br>
		<i class="fa fa-exclamation-triangle fa-fw fa-3x" style="vertical-align:middle;padding:2px"></i> NOSH ChartingSystem is compatible with Mozilla Firefox, Google Chrome, Apple Safari, Internet Explorer 8 and up, and Opera web browsers only.
	</div>
	<div id="register_dialog" title="New User Registration">
		<form id="register_form" class="pure-form pure-form-aligned">
			<input type="hidden" name="count" id="new_password_count" value="" />
			<input type="hidden" name="practice_id" id="register_practice_id" value="" />
			Enter the following fields to register as a patient portal user.  It is important that your answers are exactly what is provided to your practice such as the spelling of your name and date of birth.<br><br>
			<div class="pure-control-group"><label for="lastname">Last name *:</label><input type="text" style="width:300px" id="lastname" name="lastname" class="text" required/></div>
			<div class="pure-control-group"><label for="firstname">First name *:</label><input type="text" style="width:300px" id="firstname" name="firstname" class="text" required/></div>
			<div class="pure-control-group"><label for="dob">Date of birth *:</label><input type="text" style="width:300px" id="dob" name="dob" class="text" required/></div>
			<div class="pure-control-group"><label for="email">E-mail address *:</label><input type="text" style="width:300px" id="email" name="email" class="text" required/></div>
			<div class="pure-control-group"><label for="username1">Desired username *:</label><input type="text" style="width:300px" id="username1" name="username" class="text" required/></div>
			<div class="pure-control-group"><label for="registration_code">Registration code:</label><input type="password" style="width:300px" id="registration_code" name="registration_code" class="text" placeholder="Optional"/></div>
			<br>
			If you don't have a registration code, a registration request will be sent to the practice administrator.<br>
			You will then receive a registration code sent to your e-mail address before you proceed further.<br>
			Keep in mind that this may take some time depending on the response time of the practice administrator.<br><br>
			CAPTCHA code *:<br><div style="width:201px"><input type="text" style="width:300px" id="numberReal" name="numberReal" class="text ui-widget-content ui-corner-all" placeholder="Enter CAPTCHA code here." /><br></div>
			<hr class="ui-state-default"/>
			* = required.
			<button type="button" id="submit1">Register</button>
		</form>
	</div>
	<div id="forgot_password_dialog" title="Password Recovery">
		<form id="forgot_password_form" class="pure-form pure-form-aligned">
			<input type="hidden" name="id" id="id" value="" />
			<input type="hidden" name="count" id="count" value="" />
			Secret Question:<br><div id="secret_question"></div><br>
			<div class="pure-control-group"><label for="secret_answer">Secret Answer:</label><input type="text" style="width:300px" id="secret_answer" name="secret_answer" class="text" /></div>
			<div style="text-align:center">
				<button type="button" id="submit2">OK</button>
			</div>
		</form>
		<form id="forgot_password_form1" class="pure-form pure-form-aligned">
			<div class="pure-control-group"><label for="new_password">New Password:</label><input type="password" style="width:300px" id="new_password" name="new_password" class="text ui-widget-content ui-corner-all" /></div>
			<div class="pure-control-group"><label for="new_password_confirm">Confirm New Password:</label><input type="password" style="width:300px" id="new_password_confirm" name="new_password_confirm" class="text ui-widget-content ui-corner-all" /></div>
			<div style="text-align:center">
				<button type="button" id="submit3">OK</button>
			</div>
		</form>
	</div>
	<div id="new_password_dialog" title="Create Password">
		<form id="new_password_form" class="pure-form pure-form-aligned">
			<input type="hidden" name="id" id="new_password_id"/>
			<div class="pure-control-group"><label for="new_password1">Password:</label><input type="password" style="width:300px" id="new_password1" name="new_password" class="text ui-widget-content ui-corner-all" /></div>
			<div class="pure-control-group"><label for="new_password_confirm1">Confirm Password:</label><input type="password" style="width:300px" id="new_password_confirm1" name="new_password_confirm1" class="text ui-widget-content ui-corner-all" /></div>
			<hr class="ui-state-default"/>
			<button type="button" id="submit4">OK</button>
		</form>
	</div>
</div>
