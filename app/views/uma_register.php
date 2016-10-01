<html>
	<head>
		<title>Connect to your HIE Of One Authorizaion Server</title>
		<meta name="token" content="{{ Session::token() }}">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="cache-control" content="no-store" />
		<meta http-equiv="cache-control" content="must-revalidate" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.3.0/pure-min.css">
		<link type="text/css" href="https://code.jquery.com/ui/1.11.0-beta.2/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<?php echo HTML::style('css/main.css'); ?>
		<?php echo HTML::style('css/jquery.jgrowl.css'); ?>
		<?php echo HTML::style('css/ui.jqgrid.css'); ?>
		<script type="text/javascript">
			var noshdata = {
				'url': '<?php echo route("home"); ?>',
				'images': '<?php echo url("images"); ?>/',
				'error': '<?php echo route("home"); ?>',
				'logout_url': '<?php echo route("logout"); ?>',
				'url_patient_centric': '<?php echo route("uma_patient_centric"); ?>',
			};
			var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
		</script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
		<?php echo HTML::script('js/jquery.maskedinput.min.js'); ?>
		<?php echo HTML::script('js/jquery.jgrowl.js'); ?>
		<?php echo HTML::script('js/jquery.selectboxes.js'); ?>
		<?php echo HTML::script('js/jquery-migrate-1.2.1.js'); ?>
		<?php echo HTML::script('js/i18n/grid.locale-en.js'); ?>
		<?php echo HTML::script('js/jquery.jqGrid.min.js'); ?>
		<?php echo HTML::script('js/jquery-idleTimeout.js'); ?>
		<?php echo HTML::script('js/jquery.themeswitcher.js'); ?>
		<?php echo HTML::script('js/jstz-1.0.4.min.js'); ?>
		<?php echo HTML::script('js/jquery.cookie.js'); ?>
		<?php echo HTML::script('js/main.js'); ?>
		<?php echo HTML::script('/js/jquery.ocupload.js'); ?>
		<?php echo HTML::script('js/jquery.touchswipe.min.js'); ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('.js').show();
				$("#email").focus();
				$('[data-toggle="tooltip"]').tooltip();
				$('#more_info_div').hide();
				$('#more_info').on('click', function(){
					$('#more_info_div').toggle();
				});
			});
		</script>
	</head>
	<body>
		<div id="dialog_load" title="">
			<?php echo HTML::image('images/indicator.gif', 'Loading image', array('border' => '0')); ?>
			<div id="dialog_progressbar"></div>
		</div>
		<div id="wrapper">
			<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
				<div>
					<div class="pure-g">
						<div class="pure-u-1-5"></div>
						<div class="pure-u-3-5">
							<div>
								<h4>By entering your <abbr data-toggle="tooltip" title="Find this email address by logging into your HIE of One authorization server, click on the username on the right upper corner, click on My Information">email address linked to your HIE of One authorization service below:</abbr></h4>
								<ul>
									<li>You will be allowing physicians using mdNOSH the potential to access your health information</li>
									<li>You will be able to make your authorization server identifiable in a patient directory for future physicians using mdNOSH to access your health information</li>
									<li>For more information about how your email address identifies you, <abbr data-toggle="tooltip" id="more_info" title="Click here">click here</abbr>
								</ul>
							</div>
							<div id="more_info_div">
								<h4 style="color:red;">How mdNOSH will contact your authorization server</h4>
								<ol>
									<li>mdNOSH will be contacting the server to validate if a user tied to this e-mail address exists.</li>
									<li>mdNOSH will then determine if an authorization service (like HIE of One) exists on the domain.</li>
									<li>mdNOSH will then make a call to register itself as a client to the authorization service so that physicians who have an account with mdNOSH Gateway that you invite can access your health-related resources.</li>
									<li>You will be prompted to accept or deny the registration of mdNOSH to your HIE of One authorization service.</li>
								</ol>
							</div>
							<form id="change_secret_answer_form" class="pure-form pure-form-aligned" method="POST" action="<?php echo route('uma_register');?>">
								<div class="pure-control-group">
									<label for="email">Email Address</label>
									<input type="text" name="email" id="email" class="text" style="width:60%;"/><button type="submit" class="nosh_button">Register</button>
								</div>
								<?php if($errors->has('email')) { ?>
									<span style="color:red">
										<strong><?php echo $errors->first('email');?></strong>
									</span>
								<?php }?>
							</form>
						</div>
						<div class="pure-u-1-5"></div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
