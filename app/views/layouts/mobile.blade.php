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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="cache-control" content="no-store" />
		<meta http-equiv="cache-control" content="must-revalidate" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
<!--
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/pure/0.6.0/pure-min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/pure/0.6.0/grids-responsive-min.css">
-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
		
<!--
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />
-->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />

		<?php echo HTML::style('css/nativedroid.css');?>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
		{{ $style }}
		<?php echo HTML::style('css/nosh-timeline.css');?>
		<?php echo HTML::style('css/flexboxgrid.min.css');?>
		<script type="text/javascript">
			document.documentElement.className = 'js';
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
				'logout_url': '<?php echo route('logout_mobile'); ?>',
				'login_url': '<?php echo route('home'); ?>',
				'login_shake': '',
				'images': '<?php echo url('images'); ?>/',
				'weekends': '',
				'minTime': '',
				'maxTime': '',
				'schedule_increment': '',
				'type': '',
				'filename': '',
				'financial': '',
				'old_text': '',
				'label_text': '',
				'progress': 0,
				'default_template' : '',
				'mtm_extension': '',
				'hedis': '',
				'messaging_dialog_load' : 0
			};
			var gender = {"m":"Male","f":"Female","u":"Undifferentiated"};
			var marital = {"":"","Single":"Single","Married":"Married","Common law":"Common law","Domestic partner":"Domestic partner","Registered domestic partner":"Registered domestic partner","Interlocutory":"Interlocutory","Living together":"Living together","Legally Separated":"Legally Separated","Divorced":"Divorced","Separated":"Separated","Widowed":"Widowed","Other":"Other","Unknown":"Unknown","Unmarried":"Unmarried","Unreported":"Unreported"};
			var states = {"":"","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
			var fields = {"hpi":"History of Present Illness","situation":"Situation","ros_gen":"ROS - General","ros_eye":"ROS - Eye","ros_ent":"ROS - Ears, Nose, Throat","ros_resp":"ROS - Respiratory","ros_cv":"ROS - Cardiovascular","ros_gi":"ROS - Gastrointestinal","ros_gu":"ROS - Genitourinary","ros_mus":"ROS - Musculoskeletal","ros_neuro":"ROS - Neurological","ros_psych":"ROS - Psychological","ros_heme":"ROS - Hematological","ros_endocrine":"ROS - Endocrine","ros_skin":"ROS - Skin","ros_wcc":"ROS - Well Child Check","ros_psych1":"ROS - Depression","ros_psych2":"ROS - Anxiety","ros_psych3":"ROS - Bipolar","ros_psych4":"ROS - Mood Disorders","ros_psych5":"ROS - ADHD","ros_psych6":"ROS - PTSD","ros_psych7":"ROS - Substance Related Disorder","ros_psych8":"ROS - Obsessive Compulsive Disorder","ros_psych9":"ROS - Social Anxiety Disorder","ros_psych10":"ROS - Autistic Disorder","ros_psych11":"ROS - Asperger's Disorder","oh_pmh":"Past Medical History","oh_psh":"Past Surgical History","oh_fh":"Family History","oh_sh":"Social History","oh_etoh":"Alcohol Use","oh_tobacco":"Tobacco Use","oh_drugs":"Illicit Drug Use","oh_employment":"History - Employment","oh_psychosocial":"History - Psychosocial","oh_developmental":"History - Developmental","oh_medtrials":"History - Medication Trials","oh_results":"Reviewed Results","pe_gen1":"PE - General","pe_eye1":"PE - Eye - Conjunctiva and Lids","pe_eye2":"PE - Eye - Pupil and Iris","pe_eye3":"PE - Eye - Fundoscopic","pe_ent1":"PE - ENT - External Ear and Nose","pe_ent2":"PE - ENT - Canals and Tympanic Membranes","pe_ent3":"PE - ENT - Hearing Assessment","pe_ent4":"PE - ENT - Sinuses, Mucosa, Septum, and Turbinates","pe_ent5":"PE - ENT - Lips, Teeth, and Gums","pe_ent6":"PE - ENT - Oropharynx","pe_neck1":"PE - Neck - General","pe_neck2":"PE - Neck - Thryoid","pe_resp1":"PE - Respiratory - Effort","pe_resp2":"PE - Respiratory - Percussion","pe_resp3":"PE - Respiratory - Palpation","pe_resp4":"PE - Respiratory - Auscultation","pe_cv1":"PE - Cardiovascular - Palpation","pe_cv2":"PE - Cardiovascular - Auscultation","pe_cv3":"PE - Cardiovascular - Carotid Arteries","pe_cv4":"PE - Cardiovascular - Abdominal Aorta","pe_cv5":"PE - Cardiovascular - Femoral Arteries","pe_cv6":"PE - Cardiovascular - Extremities","pe_ch1":"PE - Chest - Inspection","pe_ch2":"PE - Chest - Palpation","pe_gi1":"PE - Gastrointestinal - Masses and Tenderness","pe_gi2":"PE - Gastrointestinal - Liver and Spleen","pe_gi3":"PE - Gastrointestinal - Hernia","pe_gi4":"PE - Gastrointestinal - Anus, Perineum, and Rectum","pe_gu1":"PE - Genitourinary - Genitalia","pe_gu2":"PE - Genitourinary - Urethra","pe_gu3":"PE - Genitourinary - Bladder","pe_gu4":"PE - Genitourinary - Cervix","pe_gu5":"PE - Genitourinary - Uterus","pe_gu6":"PE - Genitourinary - Adnexa","pe_gu7":"PE - Genitourinary - Scrotum","pe_gu8":"PE - Genitourinary - Penis","pe_gu9":"PE - Genitourinary - Prostate","pe_lymph1":"PE - Lymphatic - Neck","pe_lymph2":"PE - Lymphatic - Axillae","pe_lymph3":"PE - Lymphatic - Groin","pe_ms1":"PE - Musculoskeletal - Gait and Station","pe_ms2":"PE - Musculoskeletal - Digit and Nails","pe_ms3":"PE - Musculoskeletal - Shoulder","pe_ms4":"PE - Musculoskeletal - Elbow","pe_ms5":"PE - Musculoskeletal - Wrist","pe_ms6":"PE - Musculoskeletal - Hand","pe_ms7":"PE - Musculoskeletal - Hip","pe_ms8":"PE - Musculoskeletal - Knee","pe_ms9":"PE - Musculoskeletal - Ankle","pe_ms10":"PE - Musculoskeletal - Foot","pe_ms11":"PE - Musculoskeletal - Cervical Spine","pe_ms12":"PE - Musculoskeletal - Thoracic and Lumbar Spine","pe_neuro1":"PE - Neurological - Cranial Nerves","pe_neuro2":"PE - Neurological - Deep Tendon Reflexes","pe_neuro3":"PE - Neurological - Sensation and Motor","pe_psych1":"PE - Psychiatric - Judgement","pe_psych2":"PE - Psychiatric - Orientation","pe_psych3":"PE - Psychiatric - Memory","pe_psych4":"PE - Psychiatric - Mood and Affect","pe_skin1":"PE - Skin - Inspection","pe_skin2":"PE - Skin - Palpation","pe_constitutional1":"PE - Constitutional","pe_mental1":"PE - Mental Status Examination","proc_description":"Procedure - Description","assessment_notes":"Assessement Discussion","messages_ref_orders":"Referral Reason","orders_plan":"Orders - Recommendations","followup":"Followup","orders_goals":"Treatment Plan - Goals/Measure","orders_tp":"Treatment Plan - Treatment Plan Notes","notes":"Appointment Notes/Tasks","t_messages_message":"Telephone Message"};
			var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
			var flags = {"":"Not Applicable","L":"Below low normal","H":"Above high normal","LL":"Below lower panic limits","HH":"Above upper panic limits","<":"Below absolute low-off instrument scale",">":"Above absolute high-off instrument scale","N":"Normal","A":"Abnormal","AA":"Very abnormal","U":"Significant change up","D":"Significant change down","B":"Better--use when direction not relevant","W":"Worse--use when direction not relevant","S":"Susceptible","R":"Resistant","I":"Intermediate","MS":"Moderately susceptible","VS":"Very susceptible"};
			var availableMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		</script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<!--
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
-->
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!--
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
-->
		<script type="text/javascript">
			$(document).on("mobileinit", function () {
				$.extend($.mobile, {
					linkBindingEnabled: false,
					ajaxEnabled: false
				});
				$.mobile.ignoreContentEnabled = true;
			});
		</script>
		<script type="text/javascript" src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment.min.js"></script>
		{{ $script }}
	</head>
	<body>
		{{ $content }}
	</body>
</html>
