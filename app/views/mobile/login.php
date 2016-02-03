<div id="outer_box">
	<div class="login_box" class="is-center">
		<div align="center">
			<h1 class="splash-head">Nosh</h1>
			<div id="login_practice_logo" align="center"></div>
			<div class="splash-subhead">Mobile Version <?php echo Session::get('version');?></div><br><br>
		</div>
		<form method="POST" action="login_mobile">
			<fieldset>
				<input type="text" id="username" name="username" placeholder="Username"/>
				<input type="password" id="password" name="password" placeholder="Password"/>
				<?php if ($patient_centric == 'n') { echo $practices; }?>
			</fieldset>
			<div class="error_text">
				<?php if ($error = $errors->first("password")) { echo $error . "<br><br>"; } ?>
				<?php if (isset($attempts)) { echo $attempts . "<br><br>"; } ?>
			</div> 
			<input type="submit" id="login_button" value="Login" name="login" class="ui-btn"/>
		</form>
	</div>
</div>
<script type="text/javascript">
	noshdata.practice_id = '<?php echo $practice_id;?>';
	noshdata.login_shake = '<?php if ($error = $errors->first("password")) { echo 'y'; } elseif (isset($attempts)) { echo 'y'; } else { echo 'n';}?>';
	$(document).ready(function() {
		if (noshdata.login_shake == 'y') {
			 $("#error_text").effect('shake');
		}
		$("#username").focus();
		$.ajax({
			type: "POST",
			url: "ajaxlogin/practice-logo/" + noshdata.practice_id,
			success: function(data){
				$("#login_practice_logo").html(data);
				if (data != '') {
					var img = document.getElementById('login_practice_logo').firstChild;
					img.onload = function() {
						if(img.height > img.width) {
							img.height = '100%';
							img.width = 'auto';
						}
					};
				}
			}
		});
		//$('body').css("background","#1f8dd6");
	});
</script>
