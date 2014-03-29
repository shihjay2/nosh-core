<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo 'NOS'.'H Ch'.'art'.'ing'.'Sys'.'tem';?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="author" content="root"/>
		<meta name="description" content="NOSH (New, Open Source, Health) Record System" />
		<meta name="keywords" content="NOSH, Electronic Medical Record" />
		<meta name="robots" content="noindex, nofollow" />
		<meta name="rating" content="general" />
		<meta name="language" content="english" />
		<meta name="copyright" content="Copyright (c) <?php echo date("Y");?> Michael Chen, MD" />
		<meta name="token" content="{{ Session::token() }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.3.0/pure-min.css">
		<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<?php echo HTML::style('css/main.css'); ?>
		<?php echo HTML::style('css/jquery.jgrowl.css'); ?>
		<?php echo HTML::style('css/ui.jqgrid.css'); ?>
		<?php echo HTML::style('css/fullcalendar.css'); ?>
		<?php echo HTML::style('css/fullcalendar.print.css', array('media' => 'print'));?>
		<?php echo HTML::style('css/styledButton.css'); ?>
		<?php echo HTML::style('css/main.css'); ?>
		<?php echo HTML::style('css/jquery.timepicker.css'); ?>
		<?php echo HTML::style('css/jquery.signaturepad.css'); ?>
		<?php echo HTML::style('css/searchFilter.css'); ?>
		<?php echo HTML::style('css/ui.multiselect.css'); ?>
		<?php echo HTML::style('css/chosen.css'); ?>
		<?php echo HTML::style('css/jquery.Jcrop.css'); ?>
		<?php echo HTML::style('css/jquery.realperson.css'); ?>
		<?php echo HTML::style('css/tagit.css'); ?>
		{{ $style }}
		<script type="text/javascript">
			var noshdata = {
				'user_id': '<?php echo Session::get('user_id'); ?>',
				'pid': '',
				'eid': '',
				'job_id': '',
				'practice_id': '<?php echo Session::get('practice_id'); ?>',
				't_messages_id': '',
				'alert_id': '',
				'group_id': '<?php echo Session::get('group_id'); ?>',
				'calendar': '<?php echo asset('images/calendar.gif','Calendar', array('border' => '0')); ?>',
				'error': '<?php echo route('home'); ?>',
				'logout_url': '<?php echo route('logout'); ?>',
				'images': '<?php echo url('images'); ?>/',
				'default_pos': '',
				'displayname': '<?php echo Session::get('displayname'); ?>',
				'encounter_active': '',
				'age': '',
				'agealldays': '',
				'gender': '',
				'success_doc': false,
				'id_doc': '',
				'weekends': '',
				'minTime': '',
				'maxTime': '',
				'schedule_increment': '',
				'financial': '',
				'type': '',
				'filename': '',
				'pending_orders_id': '',
				'pending_orders_id1': '',
				'item_present': '<?php echo HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '15', 'width' => '15', 'style' => 'vertical-align:middle;'));?>',
				'item_empty': '<?php echo HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '15', 'width' => '15', 'style' => 'vertical-align:middle;'));?>'
			};
			var medcache = {};
			var medcache1 = {};
			var allergies_cache = {};
			var issue_cache = {};
			var gender = {"m":"Male","f":"Female"};
			var marital = {"":"","Single":"Single","Married":"Married","Common law":"Common law","Domestic partner":"Domestic partner","Registered domestic partner":"Registered domestic partner","Interlocutory":"Interlocutory","Living together":"Living together","Legally Separated":"Legally Separated","Divorced":"Divorced","Separated":"Separated","Widowed":"Widowed","Other":"Other","Unknown":"Unknown","Unmarried":"Unmarried","Unreported":"Unreported"};
			var states = {"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
		</script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<?php echo HTML::script('js/jquery.maskedinput.min.js'); ?>
		<?php echo HTML::script('js/jquery.jgrowl.js'); ?>
		<?php echo HTML::script('js/jquery.selectboxes.js'); ?>
		<?php echo HTML::script('js/jquery-migrate-1.1.0.js'); ?>
		<?php echo HTML::script('js/jquery.ajaxQueue.js'); ?>
		<?php echo HTML::script('js/i18n/grid.locale-en.js'); ?>
		<?php echo HTML::script('js/jquery.jqGrid.min.js'); ?>
		<?php echo HTML::script('js/jquery.timepicker.min.js'); ?>
		<?php echo HTML::script('js/fullcalendar.js'); ?>
		<?php echo HTML::script('js/jquery-idleTimeout.js'); ?>
		<?php echo HTML::script('js/jquery.styledButton.js'); ?>
		<?php echo HTML::script('js/jquery.iframer.js'); ?>
		<?php echo HTML::script('js/jquery.serializeObject.js'); ?>
		<?php echo HTML::script('js/jquery.signaturepad.min.js'); ?>
		<?php echo HTML::script('js/json2.min.js'); ?>
		<?php echo HTML::script('js/highcharts.js'); ?>
		<?php echo HTML::script('js/exporting.js'); ?>
		<?php echo HTML::script('js/jquery.dform-1.0.0.min.js'); ?>
		<?php echo HTML::script('js/grid.addons.js'); ?>
		<?php echo HTML::script('js/grid.postext.js'); ?>
		<?php echo HTML::script('js/grid.setcolumns.js'); ?>
		<?php echo HTML::script('js/jquery.contextmenu.js'); ?>
		<?php echo HTML::script('js/jquery.searchFilter.js'); ?>
		<?php echo HTML::script('js/jquery.tablednd.js'); ?>
		<?php echo HTML::script('js/jquery.chosen.min.js'); ?>
		<?php echo HTML::script('js/ui.multiselect.js'); ?>
		<?php echo HTML::script('js/jquery.themeswitcher.js'); ?>
		<?php echo HTML::script('js/jquery.color.js'); ?>
		<?php echo HTML::script('js/jquery.Jcrop.min.js'); ?>
		<?php echo HTML::script('js/jquery.realperson.js'); ?>
		<?php echo HTML::script('js/tagit-themeroller.js'); ?>
		<?php echo HTML::script('js/jquery.jstree.js'); ?>
		<?php echo HTML::script('js/jquery.populate.js'); ?>
		<?php echo HTML::script('js/jquery.ocupload.js'); ?>
		<?php echo HTML::script('js/main.js'); ?>
		<?php echo HTML::script('js/bluebutton.js'); ?>
		{{ $script }}
	</head>
	<body>
		<div id="dialog_load" title="">
			<?php echo HTML::image('images/indicator.gif', 'Loading image', array('border' => '0')); ?>
		</div>
		<div id="options_load"></div>
		<div id="allpage" class="allpage">
			<div id="header" class="header ui-widget">
				<?php if(Auth::check()) {?>
					<div id="header_left">
						<strong><?php echo HTML::linkRoute('home', 'Tasks') . ' '; ?></strong>
						<?php if(Session::get('group_id') == '1') { echo HTML::linkRoute('adminsetup', 'Setup'); } ?>
						<?php if(Session::get('group_id') == '1' && Session::get('practice_active') == 'Y') { echo HTML::linkRoute('adminusers', 'Users'); } ?>
						<?php if(Session::get('group_id') == '1' && Session::get('practice_active') == 'Y') { echo HTML::linkRoute('adminschedule', 'Schedule'); } ?>
						<?php if(Session::get('group_id') == '1') { echo HTML::linkRoute('adminlogs', 'Logs'); } ?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') { echo '<a href="#" id="nosh_messaging">Messaging</a>'; } ?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') { echo '<a href="#" id="nosh_schedule">Schedule</a>'; } ?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') { echo '<a href="#" id="nosh_financial">Financial</a>'; } ?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') { echo '<a href="#" id="nosh_office">Office</a>'; } ?>
						<?php if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') { echo '<a href="#" id="nosh_configuration">Configure</a>'; } ?>
						<?php if(Session::get('group_id') == '100') { echo HTML::linkRoute('portalmessaging', 'Messages'); } ?>
						<?php if(Session::get('group_id') == '100') { echo HTML::linkRoute('portalschedule', 'Schedule'); } ?>
						<?php if(Session::get('group_id') == '100') { echo HTML::linkRoute('portalchart', 'Chart'); } ?>
						<br>
					</div>
					<div id="header_right">
						<div style="float:left;width:190px">
							<span id="switcher"></span>
						</div>
						<div style="float:left;">
							&nbspVersion <?php echo Session::get('version');?> &nbsp|&nbsp
							<?php echo Session::get('displayname') . ' ';?>&nbsp|&nbsp
							<?php echo date('M j, Y') . ' ';?>&nbsp
							<?php echo HTML::linkRoute('logout', 'Logout'); ?>
						</div>
					</div>
					<br />
					<hr class="ui-state-default"/>
				<?php } else {?>
					<strong>NOSH ChartingSystem</strong> Version <?php echo Session::get('version');?> <br>
					<hr class="ui-state-default"/>
				<?php }?>
			</div>
			{{ $search }}
			<div id="wrapper" class="pure-g">
				<div id="leftcol" class="pure-u-7-24">
					{{ $menu }}
				</div>
				<div id="rightcol" class="pure-u-17-24">
					<div id="mainborder" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
						<div id="maincontent">
							{{ $content }}
							{{ $modules }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
