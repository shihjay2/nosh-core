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
		<?php echo HTML::style('css/fullcalendar.print.css', array('media' => 'print'));?>
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
				'path': "/<?php $path = explode('/', route('home')); echo $path[3];?>/js/",
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
				'item_empty': '<?php echo HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '15', 'width' => '15', 'style' => 'vertical-align:middle;'));?>',
				'mtm': '',
				'old_text': '',
				'label_text': '',
				'progress': 0
			};
			var medcache = {};
			var medcache1 = {};
			var allergies_cache = {};
			var issue_cache = {};
			var gender = {"m":"Male","f":"Female"};
			var marital = {"":"","Single":"Single","Married":"Married","Common law":"Common law","Domestic partner":"Domestic partner","Registered domestic partner":"Registered domestic partner","Interlocutory":"Interlocutory","Living together":"Living together","Legally Separated":"Legally Separated","Divorced":"Divorced","Separated":"Separated","Widowed":"Widowed","Other":"Other","Unknown":"Unknown","Unmarried":"Unmarried","Unreported":"Unreported"};
			var states = {"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
			var fields = {"hpi":"History of Present Illness","situation":"Situation","ros_gen":"ROS - General","ros_eye":"ROS - Eye","ros_ent":"ROS - Ears, Nose, Throat","ros_resp":"ROS - Respiratory","ros_cv":"ROS - Cardiovascular","ros_gi":"ROS - Gastrointestinal","ros_gu":"ROS - Genitourinary","ros_mus":"ROS - Musculoskeletal","ros_neuro":"ROS - Neurological","ros_psych":"ROS - Psychological","ros_heme":"ROS - Hematological","ros_endocrine":"ROS - Endocrine","ros_skin":"ROS - Skin","ros_wcc":"ROS - Well Child Check","ros_psych1":"ROS - Depression","ros_psych2":"ROS - Anxiety","ros_psych3":"ROS - Bipolar","ros_psych4":"ROS - Mood Disorders","ros_psych5":"ROS - ADHD","ros_psych6":"ROS - PTSD","ros_psych7":"ROS - Substance Related Disorder","ros_psych8":"ROS - Obsessive Compulsive Disorder","ros_psych9":"ROS - Social Anxiety Disorder","ros_psych10":"ROS - Autistic Disorder","ros_psych11":"ROS - Asperger's Disorder","oh_pmh":"Past Medical History","oh_psh":"Past Surgical History","oh_fh":"Family History","oh_sh":"Social History","oh_etoh":"Alcohol Use","oh_tobacco":"Tobacco Use","oh_drugs":"Illicit Drug Use","oh_employment":"History - Employment","oh_psychosocial":"History - Psychosocial","oh_developmental":"History - Developmental","oh_medtrials":"History - Medication Trials","pe_gen1":"PE - General","pe_eye1":"PE - Eye - Conjunctiva and Lids","pe_eye2":"PE - Eye - Pupil and Iris","pe_eye3":"PE - Eye - Fundoscopic","pe_ent1":"PE - ENT - External Ear and Nose","pe_ent1":"PE - ENT - Canals and Tympanic Membranes","pe_ent1":"PE - ENT - Hearing Assessment","pe_ent1":"PE - ENT - Sinuses, Mucosa, Septum, and Turbinates","pe_ent1":"PE - ENT - Lips, Teeth, and Gums","pe_ent1":"PE - ENT - Oropharynx","pe_neck1":"PE - Neck - General","pe_neck2":"PE - Neck - Thryoid","pe_resp1":"PE - Respiratory - Effort","pe_resp2":"PE - Respiratory - Percussion","pe_resp3":"PE - Respiratory - Palpation","pe_resp4":"PE - Respiratory - Auscultation","pe_cv1":"PE - Cardiovascular - Palpation","pe_cv2":"PE - Cardiovascular - Auscultation","pe_cv3":"PE - Cardiovascular - Carotid Arteries","pe_cv4":"PE - Cardiovascular - Abdominal Aorta","pe_cv5":"PE - Cardiovascular - Femoral Arteries","pe_cv6":"PE - Cardiovascular - Extremities","pe_ch1":"PE - Chest - Inspection","pe_ch2":"PE - Chest - Palpation","pe_gi1":"PE - Gastrointestinal - Masses and Tenderness","pe_gi2":"PE - Gastrointestinal - Liver and Spleen","pe_gi3":"PE - Gastrointestinal - Hernia","pe_gi4":"PE - Gastrointestinal - Anus, Perineum, and Rectum","pe_gu1":"PE - Genitourinary - Genitalia","pe_gu2":"PE - Genitourinary - Urethra","pe_gu3":"PE - Genitourinary - Bladder","pe_gu4":"PE - Genitourinary - Cervix","pe_gu5":"PE - Genitourinary - Uterus","pe_gu6":"PE - Genitourinary - Adnexa","pe_gu7":"PE - Genitourinary - Scrotum","pe_gu8":"PE - Genitourinary - Penis","pe_gu9":"PE - Genitourinary - Prostate","pe_lymph1":"PE - Lymphatic - Neck","pe_lymph2":"PE - Lymphatic - Axillae","pe_lymph3":"PE - Lymphatic - Groin","pe_ms1":"PE - Musculoskeletal - Gait and Station","pe_ms2":"PE - Musculoskeletal - Digit and Nails","pe_ms3":"PE - Musculoskeletal - Shoulder","pe_ms4":"PE - Musculoskeletal - Elbow","pe_ms5":"PE - Musculoskeletal - Wrist","pe_ms6":"PE - Musculoskeletal - Hand","pe_ms7":"PE - Musculoskeletal - Hip","pe_ms8":"PE - Musculoskeletal - Knee","pe_ms9":"PE - Musculoskeletal - Ankle","pe_ms10":"PE - Musculoskeletal - Foot","pe_ms11":"PE - Musculoskeletal - Cervical Spine","pe_ms12":"PE - Musculoskeletal - Thoracic and Lumbar Spine","pe_neuro1":"PE - Neurological - Cranial Nerves","pe_neuro2":"PE - Neurological - Deep Tendon Reflexes","pe_neuro3":"PE - Neurological - Sensationa and Motor","pe_psych1":"PE - Psychiatric - Judgement","pe_psych2":"PE - Psychiatric - Orientation","pe_psych3":"PE - Psychiatric - Memory","pe_psych4":"PE - Psychiatric - Mood and Affect","pe_skin1":"PE - Skin - Inspection","pe_skin2":"PE - Skin - Palpation","pe_constitutional1":"PE - Constitutional","pe_mental1":"PE - Mental Status Examination","proc_description":"Procedure - Description","assessment_notes":"Assessement Discussion","messages_ref_orders":"Referral Reason","orders_plan":"Orders - Recommendations","orders_goals":"Treatment Plan - Goals/Measure","orders_tp":"Treatment Plan - Treatment Plan Notes"};
			var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
		</script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0-beta.2/jquery-ui.min.js"></script>
		{{ $script }}
	</head>
	<body>
		<div id="dialog_load" title="">
			<?php echo HTML::image('images/indicator.gif', 'Loading image', array('border' => '0')); ?>
			<div id="dialog_progressbar"></div>
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
