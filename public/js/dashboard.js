$(document).ready(function() {
	function colorlabel1 (cellvalue, options, rowObject) {
		if(cellvalue=="colorred"){
			return "Red";
		}
		if(cellvalue=="colororange"){
			return "Orange";
		}
		if(cellvalue=="coloryellow"){
			return "Yellow"; mi
		}
		if(cellvalue=="colorgreen"){
			return "Green";
		}
		if(cellvalue=="colorblue"){
			return "Blue";
		}
		if(cellvalue=="colorpurple"){
			return "Purple";
		}
		if(cellvalue=="colorbrown"){
			return "Brown"
		}
		if(cellvalue=="colorblack"){
			return "Black"
		}
	}
	function practicestatus (cellvalue, options, rowObject){
		if (cellvalue == '') {
			return 'Connected';
		} else {
			return 'Pending';
		}
	}
	$(".dashboard_draft").click(function(){
		$("#draft_messages").jqGrid('GridUnload');
		$("#draft_messages").jqGrid({
			url:"ajaxdashboard/draft-messages",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Date of Service','Last Name','First Name','Subject'],
			colModel:[
				{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'t_messages_subject',index:'t_messages_subject',width:300}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: $('#draft_messages_pager'),
			sortname: 't_messages_dos',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Telephone Message Drafts",
		 	emptyrecords:"No messages.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = $("#draft_messages").getCell(id,'pid');
		 		var t_messages_id = $("#draft_messages").getCell(id,'t_messages_id');
		 		$.ajax({
					type: "POST",
					url: "ajaxsearch/openchart",
					data: "pid=" + pid,
					success: function(data){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/tmessagesidset",
							data: "t_messages_id=" + t_messages_id,
							dataType: "json",
							success: function(data){
								window.location = data.url;
							}
						});
					}
				});
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#draft_messages_pager',{search:false,edit:false,add:false,del:false});
		$("#draft_encounters").jqGrid('GridUnload');
		$("#draft_encounters").jqGrid({
			url:"ajaxdashboard/draft-encounters",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Date of Service','Last Name','First Name','Chief Complaint'],
			colModel:[
				{name:'eid',index:'eid',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'encounter_cc',index:'encounter_cc',width:300}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: $('#draft_encounters_pager'),
			sortname: 'encounter_DOS',
		 	viewrecords: true,
		 	sortorder: "desc",
		 	caption:"Encounter Drafts - Click to open encounter",
		 	emptyrecords:"No encounters.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = $("#draft_encounters").getCell(id,'pid');
		 		var eid = $("#draft_encounters").getCell(id,'eid');
		 		$.ajax({
					type: "POST",
					url: "ajaxsearch/openchart",
					data: "pid=" + pid,
					dataType: "json",
					success: function(data){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/eidset",
							data: "eid=" + eid,
							dataType: "json",
							success: function(data) {
								window.location = data.url;
							}
						});
					}
				});
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#draft_encounters_pager',{search:false,edit:false,add:false,del:false});
		$("#draft_div").show();
		$("#alert_div").hide();
		$("#mtm_alert_div").hide();
	});
	$(".dashboard_alerts").click(function(){
		$("#dashboard_alert").jqGrid('GridUnload');
		$("#dashboard_alert").jqGrid({
			url:"ajaxdashboard/alerts",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Due Date','Last Name','First Name','Alert','Description'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'lastname',index:'lastname',width:100},
				{name:'firstname',index:'firstname',width:100},
				{name:'alert',index:'alert',width:100},
				{name:'alert_description',index:'alert',width:200}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: $('#dashboard_alert_pager'),
			sortname: 'alert_date_active',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Reminders - Click to open chart",
		 	emptyrecords:"No reminders.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = $("#dashboard_alert").getCell(id,'pid');
		 		var alert_id = $("#dashboard_alert").getCell(id,'alert_id');
		 		$.ajax({
					type: "POST",
					url: "ajaxsearch/openchart",
					data: "pid=" + pid,
					success: function(data){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/alertidset",
							data: "alert_id=" + alert_id,
							dataType: "json",
							success: function(data){
								window.location = data.url;
							}
						});
					}
				});
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#dashboard_alert_pager',{search:false,edit:false,add:false,del:false});
		$("#alert_div").show();
		$("#draft_div").hide();
		$("#mtm_alert_div").hide();
	});
	$("#provider_mtm_alerts").click(function(){
		$("#dashboard_mtm_alert").jqGrid('GridUnload');
		$("#dashboard_mtm_alert").jqGrid({
			url:'ajaxdashboard/mtm-alerts',
			datatype: "json",
			mtype: "POST",
			colNames:['ID','PID','Last Name','First Name'],
			colModel:[
				{name:'alert_id',index:'alert_id',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:250},
				{name:'firstname',index:'firstname',width:250}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: $('#dashboard_mtm_alert_pager'),
			sortname: 'lastname',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Medication Therapy Managment Patient Roster - Click to open chart",
		 	emptyrecords:"No patients.",
		 	height: "100%",
		 	onSelectRow: function(id) {
		 		var pid = $("#dashboard_mtm_alert").getCell(id,'pid');
		 		var alert_id = $("#dashboard_mtm_alert").getCell(id,'alert_id');
		 		$.ajax({
					type: "POST",
					url: "ajaxsearch/openchart",
					data: "pid=" + pid,
					success: function(data){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/mtmset",
							dataType: "json",
							success: function(data){
								window.location = data.url;
							}
						});
					}
				});
		 	},
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#dashboard_mtm_alert_pager',{search:false,edit:false,add:false,del:false});
		$("#mtm_alert_div").show();
		$("#alert_div").hide();
		$("#draft_div").hide();
	});
	$("#change_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		open: function () {
			$.ajax({
				type: "POST",
				url: "ajaxlogin/get-secret",
				dataType: "json",
				success: function(data){
					$("#secret_question").val(data.secret_question);
					$("#secret_answer").val(data.secret_answer);
				}
			});
		},
		buttons: {
			'OK': function() {
				var a = $("#old_password");
				var b = $("#new_password");
				var c = $("#new_password2");
				var d = $("#secret_question");
				var e = $("#secret_answer");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Old Password");
				bValid = bValid && checkEmpty(b,"New Password");
				bValid = bValid && checkEmpty(c,"Confirm New Password");
				bValid = bValid && checkEmpty(d,"Secret Question");
				bValid = bValid && checkEmpty(e,"Secret Answer");
				if (bValid) {
					var f = $("#new_password").val();
					var g = $("#new_password2").val();
					if (f != g) {
						$.jGrowl("New passwords do not match!");
						$("#change_password_form").clearForm();
					} else {
						var str = $("#change_password_form").serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "ajaxlogin/change-password1",
								data: str,
								success: function(data){
									if (data == "Your old password is incorrect!") {
										$.jGrowl(data);
										$("#change_password_form").clearForm();
									} else {
										$.jGrowl(data);
										$("#change_password_form").clearForm();
										$("#change_password_dialog").dialog('close');
									}
								}
							});
						} else {
							$.jGrowl("Please complete the form");
						}
					}
				}
			},
			Cancel: function() {
				$("#change_password_form").clearForm();
				$("#change_password_dialog").dialog('close');
			}
		}
	});
	$("#change_password").click(function(){
		$("#change_password_dialog").dialog('open');
	});
	var secret_question = {"What was your childhood nickname?":"What was your childhood nickname?","In what city did you meet your spouse/significant other?":"In what city did you meet your spouse/significant other?","What is the name of your favorite childhood friend?":"What is the name of your favorite childhood friend?","What street did you live on in third grade?":"What street did you live on in third grade?","What is your oldest sibling’s birthday month and year? (e.g., January 1900)":"What is your oldest sibling’s birthday month and year? (e.g., January 1900)","What is the middle name of your oldest child?":"What is the middle name of your oldest child?","What is your oldest sibling's middle name?":"What is your oldest sibling's middle name?","What school did you attend for sixth grade?":"What is your oldest sibling's middle name?","What was your childhood phone number including area code? (e.g., 000-000-0000)":"What was your childhood phone number including area code? (e.g., 000-000-0000)","What is your oldest cousin's first and last name?":"What is your oldest cousin's first and last name?","What was the name of your first stuffed animal?":"What was the name of your first stuffed animal?","In what city or town did your mother and father meet?":"In what city or town did your mother and father meet?","Where were you when you had your first kiss?":"Where were you when you had your first kiss?","What is the first name of the boy or girl that you first kissed?":"What is the first name of the boy or girl that you first kissed?","What was the last name of your third grade teacher?":"What was the last name of your third grade teacher?","In what city does your nearest sibling live?":"In what city does your nearest sibling live?","What is your oldest brother’s birthday month and year? (e.g., January 1900)":"What is your oldest brother’s birthday month and year? (e.g., January 1900)","What is your maternal grandmother's maiden name?":"What is your maternal grandmother's maiden name?","In what city or town was your first job?":"In what city or town was your first job?","What is the name of the place your wedding reception was held?":"What is the name of the place your wedding reception was held?","What is the name of a college you applied to but didn't attend?":"What is the name of a college you applied to but didn't attend?"};
	$("#secret_question").addOption(secret_question, false);
	$("#secret_question1").addOption(secret_question, false);
	$.ajax({
		type: "POST",
		url: "ajaxlogin/check-secret",
		dataType: "json",
		success: function(data){
			if (data.secret == "Need secret question and answer!") {
				$.jGrowl(data.secret);
				$("#change_secret_answer_dialog").dialog('open');
			}
			if (data.setup == 'y') {
				$("#setup_dialog").dialog('open');
			}
			if (data.template == 'y') {
				$('#dialog_load').dialog('option', 'title', "Installing default templates...").dialog('open');
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/reset-default-templates",
					success: function(data){
						$('#dialog_load').dialog('close');
						$.jGrowl(data);
					}
				});
			}
		}
	});
	$("#change_secret_answer_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'OK': function() {
				var a = $("#secret_question1");
				var b = $("#secret_answer1");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Secret Question");
				bValid = bValid && checkEmpty(b,"Secret Answer");
				if (bValid) {
					var str = $("#change_secret_answer_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxlogin/set-secret",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#change_secret_answer_form").clearForm();
								$("#change_secret_answer_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#change_secret_answer_form").clearForm();
				$("#change_secret_answer_dialog").dialog('close');
			}
		}
	});
	$('.sigPad').signaturePad({drawOnly:true});
	$("#provider_info_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#provider_info_specialty").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/specialty",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							}
						}
					});
				},
				minLength: 3
			});
			$("#provider_info_accordion").accordion({ heightStyle: "content" });
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/provider-info",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#provider_info_form :input[name='" + key + "']").val(value);
					});
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/check-rcopia",
				success: function(data){
					if (data == 'y') {
						$('#rcopia_username_div').show();
					} else {
						$('#rcopia_username_div').hide();
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/preview-signature",
				success: function(data){
					$("#preview_signature").html(data);
				}
			});
			jQuery("#provider_visit_type_list").jqGrid('GridUnload');
			jQuery("#provider_visit_type_list").jqGrid({
				url:"ajaxdashboard/visit-type-list",
				editurl:"ajaxdashboard/edit-visit-type-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Visit Type','Duration','Color'],
				colModel:[
					{name:'calendar_id',index:'calendar_id',width:1,hidden:true},
					{name:'visit_type',index:'visit_type',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
					{name:'duration',index:'duration',width:1,hidden:true,editable:true,editrules:{edithidden:true, required:true},edittype:'select',editoptions:{value:"900:15 minutes;1200:20 minutes;1800:30 minutes;2400:40 minutes;2700:45 minutes;3600:60 minutes;4500:75 minutes;4800:80 minutes;5400:90 minutes;6000:100 minutes;6300:105 minutes;7200:120 minutes"},formoptions:{elmsuffix:"(*)"}},
					{name:'classname',index:'classname',width:300,editable:true,edittype:'select',formatter:colorlabel1,editoptions:{value:"colorred:Red;colororange:Orange;coloryellow:Yellow;colorgreen:Green;colorblue:Blue;colorpurple:Purple;colorbrown:Brown"},formoptions:{elmsuffix:"(*)"}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#provider_visit_type_list_pager'),
				sortname: 'visit_type',
				viewrecords: true,
				sortorder: "asc",
				caption:"Visit Types",
				emptyrecords:"No visits",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#provider_visit_type_list_pager',{edit:false,add:false,del:false});
		},
		buttons: {
			'Save': function() {
				var str = $("#provider_info_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/provider-info1",
					data: str,
					success: function(data){
						$.jGrowl(data);
							$("#provider_info_form").clearForm();
							$("#provider_info_dialog").dialog('close');
					}
				});
			},
			Cancel: function() {
				$("#provider_info_form").clearForm();
				$("#provider_info_dialog").dialog('close');
			}
		}
	});
	$("#provider_info").click(function(){
		$("#provider_info_dialog").dialog('open');
	});
	$("#provider_info_license_state").addOption(states, false);
	$("#provider_info_upin").mask("aa9999999");
	$("#provider_info_tax_id").mask("99-9999999");
	$("#change_signature").button().click(function(){
		var str = $("#signature_form").serialize();
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/change-signature",
			data: str,
			success: function(data){
				$.jGrowl(data);
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/preview-signature",
					success: function(data){
						$("#preview_signature").html(data);
					}
				});
			}
		});
	});
	$("#add_provider_visit_type").click(function(){
		jQuery("#provider_visit_type_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	$("#edit_provider_visit_type").click(function(){
		var item = jQuery("#provider_visit_type_list").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_visit_type_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select visit type to edit!");
		}
	});
	$("#delete_provider_visit_type").click(function(){
		var item = jQuery("#provider_visit_type_list").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_visit_type_list").delGridRow(item);
			jQuery("#provider_visit_type_list").delRowData(item);
		} else {
			$.jGrowl("Please select visit type to delete!");
		}
	});
	
	$("#restore_database_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 320, 
		width: 500, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		}
	});
	$("#restore_database_link").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/findbackups",
			dataType: 'json',
			success: function(data){
				$("#backup_select").addOption(data.options);
			}
		});
		$("#restore_database_dialog").dialog('open');
	});
	$("#restore_backup_button").button().click(function(){
		var a = $("#backup_select").val();
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/backuprestore",
			data: "file=" + a,
			success: function(data){
				$.jGrowl(data);
				$("#restore_database_dialog").dialog('close');
			}
		});
	});
	$(".dashboard_test_reconcile").click(function(){
		$("#tests_reconcile_dialog").dialog('open');
	});
	$("#tests_reconcile_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		open: function(event, ui) {
			$("#reconcile_test_patient_search1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/search",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							}
						}
					});
				},
				minLength: 1,
				select: function(event, ui){
					$("#reconcile_tests_pid").val(ui.item.id);
				}
			});
			$("#tests_reconcile_list").jqGrid('GridUnload');
			$("#tests_reconcile_list").jqGrid({
				url:"ajaxdashboard/tests",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Patient','Test','Result','Unit','Normal','Flags','Type'],
				colModel:[
					{name:'tests_id',index:'tests_id',width:1,hidden:true},
					{name:'test_datetime',index:'test_datetime',width:75,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'test_unassigned',index:'test_unassigned',width:110},
					{name:'test_name',index:'test_name',width:200},
					{name:'test_result',index:'test_result',width:120},
					{name:'test_units',index:'test_units',width:50},
					{name:'test_reference',index:'test_reference',width:100},
					{name:'test_flags',index:'test_flags',width:50,
						cellattr: function (rowId, val, rawObject, cm, rdata) {
							if (rawObject.test_flags == "L") {
								var response = "Below low normal";
							}
							if (rawObject.test_flags == "H") {
								var response = "Above high normal";
							}
							if (rawObject.test_flags == "LL") {
								var response = "Below low panic limits";
							}
							if (rawObject.test_flags == "HH") {
								var response = "Above high panic limits";
							}
							if (rawObject.test_flags == "<") {
								var response = "Below absolute low-off instrument scale";
							}
							if (rawObject.test_flags == ">") {
								var response = "Above absolute high-off instrument scale";
							}
							if (rawObject.test_flags == "N") {
								var response = "Normal";
							}
							if (rawObject.test_flags == "A") {
								var response = "Abnormal";
							}
							if (rawObject.test_flags == "AA") {
								var response = "Very abnormal";
							}
							if (rawObject.test_flags == "U") {
								var response = "Significant change up";
							}
							if (rawObject.test_flags == "D") {
								var response = "Significant change down";
							}
							if (rawObject.test_flags == "B") {
								var response = "Better";
							}
							if (rawObject.test_flags == "W") {
								var response = "Worse";
							}
							if (rawObject.test_flags == "S") {
								var response = "Susceptible";
							}
							if (rawObject.test_flags == "R") {
								var response = "Resistant";
							}
							if (rawObject.test_flags == "I") {
								var response = "Intermediate";
							}
							if (rawObject.test_flags == "MS") {
								var response = "Moderately susceptible";
							}
							if (rawObject.test_flags == "VS") {
								var response = "Very susceptible";
							}
							if (rawObject.test_flags == "") {
								var response = "";
							}
							return 'title="' + response + '"';
						}
					},
					{name:'test_type',index:'test_type',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: $('#tests_reconcile_list_pager'),
				sortname: 'test_datetime',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Test Results",
			 	height: "100%",
			 	gridview: true,
			 	multiselect: true,
				multiboxonly: true,
			 	rowattr: function (rd) {
					if (rd.test_flags == "HH" || rd.test_flags == "LL" || rd.test_flags == "H" || rd.test_flags == "L") {
						return {"class": "myAltRowClass"};
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#tests_reconcile_list_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#reconcile_tests").button({icons: {primary: "ui-icon-disk"}}).click(function(){
		var click_id = $("#tests_reconcile_list").getGridParam('selarrrow');
		if(click_id.length > 0){
			$("#reconcile_tests_pid").val('');
			$("#scan_patient_search1").val('');
			$("#reconcile_tests_div").show();
			$("#reconcile_test_patient_search1").focus();
		} else {
			$.jGrowl("Choose test to reconcile!");
		}
	});
	$("#reconcile_tests_send").click(function(){
		var click_id = $("#tests_reconcile_list").getGridParam('selarrrow');
		var pid = $("#reconcile_tests_pid").val();
		if(click_id){
			var json_flat = JSON.stringify(click_id);
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/tests-import",
				data: "tests_id_array=" + json_flat + "&pid=" + pid,
				success: function(data){
					$.jGrowl('Imported ' + data + ' tests!');
					$("#reconcile_tests_pid").val('');
					$("#reconcile_test_patient_search1").val('');
					$("#reconcile_tests_div").hide();
					reload_grid("tests_reconcile_list");
				}
			});
		}
	});
	$("#reconcile_tests_cancel").click(function(){
		$("#reconcile_tests_pid").val('');
			$("#reconcile_test_patient_search1").val('');
			$("#reconcile_tests_div").hide();
	});
	$("#delete_tests").click(function(){
		var click_id = $("#tests_reconcile_list").getGridParam('selarrrow');
		if(click_id.length > 0){
			if(confirm('Are you sure you want to delete the selected tests?')){ 
				var count = click_id.length;
				for (var i = 0; i < count; i++) {
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/delete-tests",
						data: "tests_id=" + click_id[i],
						success: function(data){
						}
					});
				}
				$.jGrowl('Deleted ' + i + ' tests!');
				reload_grid("tests_reconcile_list");
			}
		} else {
			$.jGrowl("Please select test to delete!");
		}
	});
	$("#print_entire_charts").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/check-print-entire-chart",
			dataType: "json",
			success: function(data){
				if (data.response == true) {
					$('#dialog_load').dialog('option', 'title', "Creating file...").dialog('open');
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/print-entire-chart",
						success: function(data1){
							$("#print_entire_charts_return").html(data1).find('a').css({"font-weight":"bold","color":"red"});
						}
					});
					progressbartrack();
				} else {
					$.jGrowl(data.message);
				}
			}
		});
	}).tooltip({ content: "Clicking on this will create a ZIP file with individual PDF files of complete medical records for every patient in your practice." });
	$("#print_entire_ccda").click(function(){
		$('#dialog_load').dialog('option', 'title', "Creating file...").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/print-entire-ccda",
			success: function(data){
				$("#print_entire_ccda_return").html(data).find('a').css({"font-weight":"bold","color":"red"});
			}
		});
		progressbartrack();
	}).tooltip({ content: "Clicking on this will create a ZIP file with individual C-CDA files for every patient in your practice." });
	$("#export_entire").click(function(){
		$('#dialog_load').dialog('option', 'title', "Creating file...").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/noshexport",
			success: function(data){
				$("#export_entire_return").html(data).find('a').css({"font-weight":"bold","color":"red"});
			}
		});
		progressbartrack();
	}).tooltip({ content: "Clicking on this will create a ZIP file that you can use to transport your entire NOSH to another NOSH installation." });
	$("#generate_csv_patient_demographics").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/check-csv-patient-demographics",
			dataType: "json",
			success: function(data){
				if (data.response == true) {
					$('#dialog_load').dialog('option', 'title', "Creating file...").dialog('open');
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/generate-csv-patient-demographics",
						success: function(data1){
							$("#generate_csv_patient_demographics_return").html(data1).find('a').css({"font-weight":"bold","color":"red"});
						}
					});
					progressbartrack();
				} else {
					$.jGrowl(data);
				}
			}
		});
	}).tooltip({ content: "Clicking on this will create a CSV file of demographic information for every patient in your practice." });
	function updateCoords1(c) {
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	function signature() {
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/get-signature",
			dataType: 'json',
			success: function(data){
				$("#preview_signature").html(data.link);
				$("#signature_message").html(data.message);
				if (data.button != "") {
					$('#image_target').Jcrop({
						maxSize: [198, 55],
						onSelect: updateCoords1
					});
					$("#signature_message").append(data.button);
					$('#image_crop').button().click(function(){
						var a = $('#x').val();
						if (a != '') {
							var str = "x=" + $('#x').val() + "&y=" + $('#y').val() + "&w=" + $('#w').val() + "&h=" + $('#h').val();
							$.ajax({
								type: "POST",
								url: "ajaxdashboard/crop-signature",
								data: str,
								dataType: 'json',
								success: function(data){
									$.jGrowl(data.growl);
									$("#preview_signature").html(data.link);
									$("#signature_message").html(data.message);
								}
							});
						} else {
							$.jGrowl('Select cropping area!  Hint: Move your mouse over the preview signature image.');
						}
					});
				}
			}
		});
	}
	var mySigUpload1 = $("#signature_upload_submit").upload({
		action: 'signatureupload',
		onComplete: function(data){
			$("#signature_upload_submit").parent().find('input').val('');
			$.jGrowl(data);
			signature();
		}
	});
	var myImportUpload1 = $("#import_entire").upload({
		action: 'importupload',
		onComplete: function(data){
			$.jGrowl(data);
			$("#import_entire").parent().find('input').val('');
		}
	});
	$("#manage_practice_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		open: function(event, ui) {
			$("#manage_practice_list").jqGrid('GridUnload');
			$("#manage_practice_list").jqGrid({
				url:"ajaxcommon/connected-practices",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Practice','API Key','Status'],
				colModel:[
					{name:'practice_id',index:'practice_id',width:1,hidden:true},
					{name:'practice_name',index:'practice_name',width:200},
					{name:'api_key',index:'api_key',width:100},
					{name:'practice_registration_timeout',index:'practice_registration_timeout',width:100,formatter:practicestatus}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: $('#manage_practice_list_pager'),
				sortname: 'practice_id',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Connected Practices",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#manage_practice_list_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#dashboard_manage_practice").click(function() {
		$('#manage_practice_dialog').dialog('option', {
			height: $("#maincontent").height(),
			width: $("#maincontent").width(),
			position: { my: 'left top', at: 'left top', of: '#maincontent' }
		});
		$("#manage_practice_dialog").dialog('open');
	});
	$("#add_practice_state").addOption(states, false);
	$("#add_practice_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 350, 
		width: 400, 
		modal: true,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#add_practice_npi").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/npi-lookup-practice",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							}
						}
					});
				},
				minLength: 3,
				open: function() { 
					$('.ui-menu').width(300);
				}
			}).focus(function() {
				var a = $("#add_practice_practice_name").val();
				var b = $("#add_practice_state").val();
				if (a != "" && b != "") {
					var q = a + ";" + b;
					$("#add_practice_npi").autocomplete("search", q);
				}
			}).mask("9999999999");
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#add_practice_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
					if (input_id == 'add_practice_practice_url') {
						bValid = bValid && checkRegexp(id1, /\(?(?:(http|https):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/, "eg. https://www.nosh.com/nosh" );
					}
					if (input_id == 'add_practice_email') {
						bValid = bValid && checkRegexp(id1, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. user@nosh.com" );
					}
				});
				if (bValid) {
					var str = $("#add_practice_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxcommon/practice-api",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#add_practice_form").clearForm();
								$("#add_practice_dialog").dialog('close');
								reload_grid("manage_practice_list");
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#add_practice_form").clearForm();
				$("#add_practice_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#dashboard_add_practice").click(function() {
		$("#add_practice_dialog").dialog('open');
	});
	$("#manual_cancel_practice_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		modal: true,
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#manual_cancel_practice").click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/findpractices",
			dataType: 'json',
			success: function(data){
				$("#manual_cancel_practice_list").addOption(data.options);
			}
		});
		$("#manual_cancel_practice_dialog").dialog('open');
	});
	$("#manual_cancel_practice_button").button().click(function(){
		var a = $("#manual_cancel_practice_list").val();
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/cancelpractice",
			data: "practice_id=" + a,
			success: function(data){
				$.jGrowl(data);
				$("#manual_cancel_practice_dialog").dialog('close');
			}
		});
	});
	$("#hieofone_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 350, 
		width: 550, 
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'OK': function() {
				var a = $("#hieofone_username").val();
				$.ajax({
					type: "POST",
					url: "ajaxlogin/hieofone",
					data: "username=" + a,
					dataType: "json",
					success: function(data){
						if (data.response == 'y') {
							$("#hieofone_dialog").dialog('close');
							$.jGrowl(data.message);
						} else {
							$("#hieofone_error").html(data.message + '<br>');
							if (data.message == 'There is a duplicate username in the HIEofOne system.') {
								$("#hieofone_username_div").show();
							}
						}
					}
				});
			},
			Cancel: function() {
				$("#hieofone_dialog").dialog('close');
				$("#hieofone_username_div").hide();
				$("#hieofone_username").val('');
				$("#hieofone_error").html('');
			}
		}
	});
	$("#hieofone_sso").click(function(){
		$("#hieofone_username_div").hide();
		$("#hieofone_username").val('');
		$("#hieofone_error").html('');
		$("#hieofone_dialog").dialog('open');
	});
});
