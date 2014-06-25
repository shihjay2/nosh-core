$(document).ready(function() {
	function newencounter() {
		var str = $("#new_encounter_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/new-encounter",
				data: str,
				dataType: "json",
				success: function(data){
					noshdata.eid = data.eid;
					$.jGrowl(data.message);
					$("#new_encounter_form").clearForm();
					$("#new_encounter_dialog").dialog('close');
					noshdata.encounter_active = 'y';
					$("#nosh_chart_div").hide('blind');
					$("#nosh_encounter_div").show('blind');
					openencounter();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	}
	$.ajax({
		url: "ajaxsearch/provider-select",
		dataType: "json",
		type: "POST",
		success: function(data){
			$("#messages_lab_provider_list").addOption({"":"Select a provider for the order."}, false);
			$("#messages_lab_provider_list").addOption(data, false);
			$("#messages_rad_provider_list").addOption({"":"Select a provider for the order."}, false);
			$("#messages_rad_provider_list").addOption(data, false);
			$("#messages_cp_provider_list").addOption({"":"Select a provider for the order."}, false);
			$("#messages_cp_provider_list").addOption(data, false);
			$("#messages_ref_provider_list").addOption({"":"Select a provider for the order."}, false);
			$("#messages_ref_provider_list").addOption(data, false);
			$("#results_test_provider_id").addOption({"":"Select a provider for the order."}, false);
			$("#results_test_provider_id").addOption(data, false);
		}
	});
	if (noshdata.encounter_active == 'y') {
		$("#nosh_chart_div").hide('blind');
		$("#nosh_encounter_div").show('blind');
		openencounter();
		noshdata.encounter_active = 'n';
	} else {
		$("#nosh_encounter_div").hide('blind');
		$("#nosh_chart_div").show('blind');
	}
	$("#new_encounter_accordion").accordion({
		heightStyle: "content",
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#new_encounter_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#new_encounter_accordion").accordion("option", "active");
					if (active < 2) {
						$("#new_encounter_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#encounter_template").addOption({'standardmedical':'Standard Medical Visit','standardpsych':'Annual Psychiatric Evaluation','standardpsych1':'Psychiatric Encounter','clinicalsupport':'Clinical Support Visit','standardmtm':'MTM Encounter'}, false).tooltip();
	$("#encounter_location").val(noshdata.default_pos);
	$("#encounter_date").mask("99/99/9999").datepicker();
	$("#encounter_time").timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#encounter_provider").change(function() {
		var a = $(this).val();
		if (a != "") {
			$("#encounter_type").removeOption(/./);
			$("#encounter_type").addOption({'':'Choose appointment to associate encounter!'}, false);
			$.ajax({
				type: "POST",
				url: "ajaxsearch/get-appointments/" + a,
				dataType: "json",
				success: function(data){
					$("#encounter_type").addOption(data,false);
				}
			});
		}
	});
	$("#encounter_role").addOption({"":"Choose Provider Role","Primary Care Provider":"Primary Care Provider","Consulting Provider":"Consulting Provider","Referring Provider":"Referring Provider"},false).change(function(){
		if ($(this).val() == "Consulting Provider" || $(this).val() == "Referring Provider") {
			$(".referring_provider_div").show();
		} else {
			$(".referring_provider_div").hide().val('');
		}
	});
	$("#referring_provider_npi").mask("9999999999");
	$("#encounter_cc").val('');
	$("#billing_bill_complex").addOption({"":"","Low Complexity":"Low Complexity","Medium Complexity":"Medium Complexity","High Complexity":"High Complexity"}, false);
	$("#encounter_condition_work").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_other").addOption({"":"","No":"No","Yes":"Yes"},false);
	$("#encounter_condition_auto_state").addOption({"":"State where accident occured.","AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"}, false);
	$("#new_encounter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 650, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxsearch/get-copay",
				success: function(data){
					$("#encounter_copay").html(data);
				}
			});
			$("#encounter_provider").removeOption(/./);
			$("#encounter_provider").addOption({'':'Choose Provider'}, false);
			$.ajax({
				type: "POST",
				url: "ajaxsearch/provider-select1",
				dataType: "json",
				success: function(data){
					$("#encounter_provider").addOption(data,false);
					if (noshdata.group_id == '2' || noshdata.group_id == '3') {
						$("#encounter_provider").val(noshdata.user_id);
						$.ajax({
							type: "POST",
							url: "ajaxsearch/get-appointments/" + noshdata.user_id,
							dataType: "json",
							success: function(data){
								$("#encounter_type").addOption(data,false);
							}
						});
					}
				}
			});
			$("#encounter_location").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/pos",
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
				minLength: 1
			});
			$("#encounter_cc").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/cc",
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
			$("#referring_provider").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/all-contacts2",
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
				select: function(event, ui){
					$("#referring_provider_npi").val(ui.item.npi);
				}
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#new_encounter_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					if($("#new_encounter_dialog_eid").val() == '') {
						if($("#encounter_type").val() == '') {
							if(confirm('Are you sure you want to create a new encounter without an associated appointment?')){
								newencounter();
							}
						} else {
							newencounter();
						}
					} else {
						var str = $("#new_encounter_form").serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "ajaxencounter/edit-encounter",
								data: str,
								success: function(data){
									$.jGrowl(data);
									$("#new_encounter_form").clearForm();
									$("#new_encounter_dialog").dialog('close');
								}
							});
						} else {
							$.jGrowl("Please complete the form");
						}
					}
				}
			},
			Cancel: function() {
				$("#new_encounter_form").clearForm();
				$("#new_encounter_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#new_encounter").click(function() {
		$("#detail_encounter_number").html("");
		$("#encounter_template").val(noshdata.default_template);
		$("#encounter_location").val(noshdata.default_pos);
		var currentDate = getCurrentDate();
		var currentTime = getCurrentTime();
		$("#encounter_date").val(currentDate);
		$("#encounter_time").val(currentTime);
		$("#encounter_type").removeOption(/./);
		$("#encounter_type").addOption({'':'Choose appointment to associate encounter!'}, false);
		if (noshdata.group_id == '2') {
			$(".new_encounter_dialog_encounter_provider_div").hide();
		} else {
			$(".new_encounter_dialog_encounter_provider_div").show();
		}
		$("#encounter_condition_work").val('No');
		$("#encounter_condition_auto").val('No');
		$("#encounter_condition_other").val('No');
		$(".referring_provider_div").hide();
		$(".detail_encounter_noshow").show();
		$("#new_encounter_dialog").dialog('open');
	});
	$("#new_message").click(function() {
		$("#edit_message_form").clearForm();
		$.ajax({
			url: "ajaxchart/new-message",
			type: "POST",
			success: function(data){
				$("#t_messages_id").val(data);
			}
		});
		var currentDate = getCurrentDate();
		$("#t_messages_dos").val(currentDate);
		reload_grid("messages");
		$("#messages_list_dialog").dialog('open');
		$("#edit_message_fieldset").show();
		$("#t_messages_subject").focus();
		$("#message_view").html('');
		$("#messages_main_dialog").dialog('open');
	});
	$("#new_letter").click(function() {
		$("#letter_dialog").dialog('open');
	});
	$("#new_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#new_import_documents_date").mask("99/99/9999");
	$("#new_import_documents_date").datepicker();
	$("#new_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#new_import_documents_from").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/document-from",
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
				minLength: 2
			});
			$("#new_import_documents_desc").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/document-description",
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
				minLength: 2
			});
			$("#new_import_documents_from").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#new_import_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#new_import_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/documents-upload",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$('#new_import_form').clearForm();
								$("#new_import_message").html('');
								$("#new_import_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				var id = $("#new_import_documents_id").val();
				if(id){
					$.ajax({
						type: "POST",
						url: "ajaxchart/delete-upload",
						data: "documents_id=" + id,
						success: function(data){
							$.jGrowl(data);
							$('#new_import_form').clearForm();
							$("#new_import_message").html('');
							$("#new_import_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Error canceling upload!");
				}
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	var myUpload = $("#new_import").upload({
		action: 'documentsupload',
		onComplete: function(data){
			var data1 = JSON.parse(data);
			$.jGrowl(data1.result);
			$("#new_import_message").html(data1.result1);
			$("#new_import_documents_id").val(data1.id);
			$('#new_import1').val('');
			$("#new_import_dialog").dialog('open');
		}
	});
	var myUpload1 = $("#import_ccr").upload({
		action: 'ccrupload',
		onComplete: function(data){
			$.jGrowl(data);
		}
	});
	var myUpload2 = $("#import_ccda").upload({
		action: 'ccdaupload',
		onComplete: function(data){
			var data1 = JSON.parse(data);
			$.jGrowl(data1.message);
			if (data1.result == true) {
				$.ajax({
					type: "POST",
					url: "ajaxchart/get-ccda/" + data1.ccda,
					success: function(data){
						jQuery("#ccda_issues").jqGrid('GridUnload');
						jQuery("#ccda_issues").jqGrid({
							datatype: "local",
							colNames:['ID','Date Active','Issue','Code'],
							colModel:[
								{name:'id',index:'id',width:1,hidden:true},
								{name:'date_range.start',index:'date_range.start',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'name',index:'name',width:635},
								{name:'code',index:'code',width:1,hidden:true}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#ccda_issues_pager'),
							sortname: 'date_range.start',
							viewrecords: true,
							sortorder: "desc",
							caption:"Issues from C-CDA",
							height: "100%"
						}).navGrid('#ccda_issues_pager',{search:false,edit:false,add:false,del:false});
						jQuery("#ccda_medications").jqGrid('GridUnload');
						jQuery("#ccda_medications").jqGrid({
							datatype: "local",
							colNames:['ID','Date Active','Due Date','Medication','SIG Dosage','SIG Unit','Route','Reason','RXNorm','Administration'],
							colModel:[
								{name:'id',index:'id',width:1,hidden:true},
								{name:'date_range.start',index:'date_range.start',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'date_range.end',index:'date_range.end',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'product.name',index:'product.name',width:320},
								{name:'dose_quantity.value',index:'dose_quantity.value',width:100},
								{name:'dose_quantity.unit',index:'dose_quantity.unit',width:100},
								{name:'route.name',index:'route.name',width:1,hidden:true},
								{name:'reason.name',index:'reason.name',width:1,hidden:true},
								{name:'product.code',index:'product.code',width:1,hidden:true},
								{name:'administration.name',index:'administration.name',width:1,hidden:true}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#nosh_medications_pager'),
							sortname: 'date_range.start',
							viewrecords: true,
							sortorder: "desc",
							caption:"Medications from C-CDA",
							height: "100%"
						}).navGrid('#ccda_medications_pager',{search:false,edit:false,add:false,del:false});
						jQuery("#ccda_allergies").jqGrid('GridUnload');
						jQuery("#ccda_allergies").jqGrid({
							datatype: "local",
							colNames:['ID','Date Active','Medication','Reason'],
							colModel:[
								{name:'id',index:'id',width:1,hidden:true},
								{name:'date_range.start',index:'date_range.start',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'allergen.name',index:'allergen.name',width:310},
								{name:'reaction_type.name',index:'reaction_type.name',width:320}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#ccda_allergies_pager'),
							sortname: 'date_range.start',
							viewrecords: true,
							sortorder: "desc",
							caption:"Allergies",
							height: "100%",
							jsonReader: { repeatitems : false, id: "0" }
						}).navGrid('#ccda_allergies_pager',{search:false,edit:false,add:false,del:false});
						jQuery("#ccda_imm").jqGrid('GridUnload');
						jQuery("#ccda_imm").jqGrid({
							datatype: "local",
							colNames:['ID','Date Given','Immunization','Route'],
							colModel:[
								{name:'id',index:'id',width:1,hidden:true},
								{name:'date',index:'date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
								{name:'product.name',index:'product.name',width:635},
								{name:'route.name',index:'route.name',width:1,hidden:true}
							],
							rowNum:10,
							rowList:[10,20,30],
							pager: jQuery('#ccda_imm_pager'),
							sortname: 'product.name',
							viewrecords: true,
							sortorder: "asc",
							caption:"Immunizations",
							height: "100%",
							jsonReader: { repeatitems : false, id: "0" }
						}).navGrid('#ccda_imm_pager',{search:false,edit:false,add:false,del:false});
						var bb = BlueButton(data);
						var bb_issues = bb.problems();
						for (var i=0; i<bb_issues.length; i++) {
							bb_issues[i].id = i+1;
							jQuery("#ccda_issues").jqGrid('addRowData',i+1,bb_issues[i]);
						}
						var bb_medications = bb.medications();
						var medications_array = [];
						for (var j=0; j<bb_medications.length; j++) {
							bb_medications[j].id = j+1;
							jQuery("#ccda_medications").jqGrid('addRowData',j+1,bb_medications[j]);
						}
						var bb_allergies = bb.allergies();
						var allergies_array = [];
						for (var k=0; k<bb_allergies.length; k++) {
							bb_allergies[k].id = k+1;
							jQuery("#ccda_allergies").jqGrid('addRowData',k+1,bb_allergies[k]);
						}
						var bb_immunizations = bb.immunizations();
						var immunizations_array = [];
						for (var l=0; l<bb_immunizations.length; l++) {
							bb_immunizations[l].id = l+1;
							jQuery("#ccda_imm").jqGrid('addRowData',l+1,bb_immunizations[l]);
						}
						$("#ccda_dialog").dialog('open');
					}
				});
			}
		}
	});
	var myUpload3 = $("#chart_import_csv").upload({
		action: 'csvupload',
		onComplete: function(data){
			var data1 = JSON.parse(data);
			$.jGrowl(data1.message);
			if (data1.message != 'Error with CSV file!') {
				$("#csv_form").html('');
				$("#csv_form").html(data1.html);
				$("#csv_dialog").dialog('open');
			}
		}
	});
	$("#ccda_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 925, 
		draggable: false,
		resizable: false,
		open: function(event,ui) {
			$("#ccda_accordion").accordion({ heightStyle: "content" });
			jQuery("#nosh_issues").jqGrid('GridUnload');
			jQuery("#nosh_issues").jqGrid({
				url:"ajaxcommon/issues",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Issue'],
				colModel:[
					{name:'issue_id',index:'issue_id',width:1,hidden:true},
					{name:'issue_date_active',index:'issue_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'issue',index:'issue',width:635}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#nosh_issues_pager'),
				sortname: 'issue_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Issues",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#nosh_issues_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#nosh_medications").jqGrid('GridUnload');
			jQuery("#nosh_medications").jqGrid({
				url:"ajaxcommon/medications",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','NDC'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:255},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:105},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#nosh_medications_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medications - Click on the Date Active column to get past prescriptions for the medication.",
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol == 1) {
						var med = jQuery("#nosh_medications").getCell(id,'rxl_medication');
						$.ajax({
							type: "POST",
							url: "ajaxchart/past-medication",
							data: "rxl_medication=" + med,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.item, {sticky:true, header:data.header});
							}
						});
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#nosh_medications_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#nosh_allergies").jqGrid('GridUnload');
			jQuery("#nosh_allergies").jqGrid({
				url: "ajaxcommon/allergies",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:320}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#nosh_allergies_pager'),
				sortname: 'allergies_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Allergies",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#nosh_allergies_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#nosh_imm").jqGrid('GridUnload');
			jQuery("#nosh_imm").jqGrid({
				url:"ajaxchart/immunizations",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Given','Immunization','Sequence','Given Elsewhere','Body Site','Dosage','Unit','Route','Lot Number','Manufacturer','Expiration Date','VIS'],
				colModel:[
					{name:'imm_id',index:'imm_id',width:1,hidden:true},
					{name:'imm_date',index:'imm_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:410},
					{name:'imm_sequence',index:'imm_sequence',width:65},
					{name:'imm_elsewhere',index:'imm_elsewhere',width:150},
					{name:'imm_body_site',index:'imm_body_site',width:1,hidden:true},
					{name:'imm_dosage',index:'imm_dosage',width:1,hidden:true},
					{name:'imm_dosage_unit',index:'imm_dosage_unit',width:1,hidden:true},
					{name:'imm_route',index:'imm_route',width:1,hidden:true},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_expiration',index:'imm_expiration',width:1,hidden:true},
					{name:'imm_vis',index:'imm_vis',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#nosh_imm_pager'),
				sortname: 'imm_immunization',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Immunizations",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#nosh_imm_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#copy_ccda_issues_item").click(function(){
		var item = jQuery("#ccda_issues").getGridParam('selrow');
		if(item){
			$('#edit_issue_form').clearForm();
			var data = jQuery("#ccda_issues").getLocalRow(item);
			var a = data.name + " [" + data.code + "]";
			$('#issue').val(a);
			var data1 = jQuery("#ccda_issues").getCell(item,1);
			var edit_date = editDate(data1);
			$('#issue_date_active').val(edit_date);
			$('#edit_issue_dialog').dialog('option', 'title', "Copy Issue");
			$('#edit_issue_dialog').dialog('open');
		} else {
			$.jGrowl("Please select issue to copy!")
		}
	});
	$("#add_nosh_issue").click(function(){
		$('#edit_issue_form').clearForm();
		var currentDate = getCurrentDate();
		$('#issue_date_active').val(currentDate);
		$('#edit_issue_dialog').dialog('option', 'title', "Add Issue");
		$('#edit_issue_dialog').dialog('open');
		$("#issue").focus();
	});
	$("#edit_nosh_issue").click(function(){
		var item = jQuery("#nosh_issues").getGridParam('selrow');
		if(item){
			jQuery("#nosh_issues").GridToForm(item,"#edit_issue_form");
			var date = $('#issue_date_active').val();
			var edit_date = editDate(date);
			$('#issue_date_active').val(edit_date);
			$('#edit_issue_dialog').dialog('option', 'title', "Edit Issue");
			$('#edit_issue_dialog').dialog('open');
		} else {
			$.jGrowl("Please select issue to edit!")
		}
	});
	$("#inactivate_nosh_issue").click(function(){
		var item = jQuery("#nosh_issues").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-issue",
				data: "issue_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("nosh_issues");
				}
			});
		} else {
			$.jGrowl("Please select issue to inactivate!")
		}
	});
	$("#copy_ccda_medications_item").click(function(){
		var item = jQuery("#ccda_medications").getGridParam('selrow');
		if(item){
			$('#edit_rx_form').clearForm();
			var data = jQuery("#ccda_medications").getLocalRow(item);
			var medication = jQuery("#ccda_medications").getCell(item,3);
			var dosage = jQuery("#ccda_medications").getCell(item,4);
			var dosage_unit = jQuery("#ccda_medications").getCell(item,5);
			var route = jQuery("#ccda_medications").getCell(item,6);
			var reason = jQuery("#ccda_medications").getCell(item,7);
			var admin = jQuery("#ccda_medications").getCell(item,9);
			var a = '';
			if (dosage != '') {
				a += dosage;
			}
			if (dosage_unit != '') {
				a += ' ' + dosage_unit;
			}
			if (admin != '') {
				a += " " + admin;
			}
			$('#rxl_medication').val(medication);
			$('#rxl_sig').val(a);
			if (route != '') {
				var b = '';
				if (route == "Oropharyngeal Route of Administration") {
					b = "by mouth"; 
				}
				if (route == "Rectal Route of Administration") {
					b = "per rectum"; 
				}
				if (route == "Subcutaneous Route of Administration") {
					b = "subcutaneously"; 
				}
				if (route == "Intravascular Route of Administration") {
					b = "intravenously"; 
				}
				if (route == "Intramuscular Route of Administration") {
					b = "intramuscularly"; 
				}
				$('#rxl_route').val(b);
			}
			$('#rxl_reason').val(reason);
			var date_active = jQuery("#ccda_medications").getCell(item,1);
			var edit_date = editDate(date_active);
			$('#rxl_date_active').val(edit_date);
			$('#edit_medications_dialog').dialog('option', 'title', "Copy Medication");
			$('#edit_medications_dialog').dialog('open');
			$("#rxl_medication").focus();
		} else {
			$.jGrowl("Please select medication to copy!")
		}
	});
	$("#add_nosh_rx").click(function(){
		$('#edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#rxl_date_active').val(currentDate);
		$('#edit_medications_dialog').dialog('option', 'title', "Add Medication");
		$('#edit_medications_dialog').dialog('open');
		$("#rxl_search").focus();
	});
	$("#edit_nosh_rx").click(function(){
		var item = jQuery("#nosh_medications").getGridParam('selrow');
		if(item){
			jQuery("#nosh_medications").GridToForm(item,"#edit_rx_form");
			var date = $('#rxl_date_active').val();
			var edit_date = editDate(date);
			$('#rxl_date_active').val(edit_date);
			$('#edit_medications_dialog').dialog('option', 'title', "Edit Medication");
			$('#edit_medications_dialog').dialog('open');
			$("#rxl_medication").focus();
		} else {
			$.jGrowl("Please select medication to edit!")
		}
	});
	$("#inactivate_nosh_rx").click(function(){
		var item = jQuery("#nosh_medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-medication",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					reload_grid("nosh_medications");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#copy_ccda_allergies_item").click(function(){
		var item = jQuery("#ccda_allergies").getGridParam('selrow');
		if(item){
			$('#edit_allergy_form').clearForm();
			var allergen = jQuery("#ccda_allergies").getCell(item,2);
			var reaction = jQuery("#ccda_allergies").getCell(item,3);
			$('#allergies_med').val(allergen);
			$('#allergies_reaction').val(reaction);
			var date_active = jQuery("#ccda_allergies").getCell(item,1);
			var edit_date = editDate(date_active);
			$('#allergies_date_active').val(edit_date);
			$('#edit_allergy_dialog').dialog('option', 'title', "Edit Allergy");
			$('#edit_allergy_dialog').dialog('open');
			$("#allergies_med").focus();
		} else {
			$.jGrowl("Please select allergy to copy!")
		}
	});
	$("#add_nosh_allergy").click(function(){
		$('#edit_allergy_form').clearForm();
		var currentDate = getCurrentDate();
		$('#allergies_date_active').val(currentDate);
		$('#edit_allergy_dialog').dialog('option', 'title', "Add Allergy");
		$('#edit_allergy_dialog').dialog('open');
		$("#allergies_med").focus();
	});
	$("#edit_nosh_allergy").click(function(){
		var item = jQuery("#nosh_allergies").getGridParam('selrow');
		if(item){
			jQuery("#nosh_allergies").GridToForm(item,"#edit_allergy_form");
			var date = $('#allergies_date_active').val();
			var edit_date = editDate(date);
			$('#allergies_date_active').val(edit_date);
			$('#edit_allergy_dialog').dialog('option', 'title', "Edit Allergy");
			$('#edit_allergy_dialog').dialog('open');
			$("#allergies_med").focus();
		} else {
			$.jGrowl("Please select allergy to edit!")
		}
	});
	$("#inactivate_nosh_allergy").click(function(){
		var item = jQuery("#nosh_allergies").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-allergy",
				data: "allergies_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("nosh_allergies");
				}
			});
		} else {
			$.jGrowl("Please select allergy to inactivate!")
		}
	});
	$("#copy_ccda_imm_item").click(function(){
		var item = jQuery("#ccda_imm").getGridParam('selrow');
		if(item){
			$('#edit_immunization_form').clearForm();
			var imm = jQuery("#ccda_imm").getCell(item,2);
			var date_active = jQuery("#ccda_imm").getCell(item,1);
			var route = jQuery("#ccda_imm").getCell(item,3);
			$('#imm_immunization').val(imm);
			if (route != null) {
				var b = '';
				if (route == "Oropharyngeal Route of Administration") {
					b = "by mouth"; 
				}
				if (route == "Subcutaneous Route of Administration") {
					b = "subcutaneously"; 
				}
				if (route == "Intravascular Route of Administration") {
					b = "intravenously"; 
				}
				if (route == "Intramuscular Route of Administration") {
					b = "intramuscularly"; 
				}
				$('#imm_route').val(b);
			}
			var edit_date = editDate(date_active);
			$('#imm_date').val(edit_date);
			$$('#edit_immunization_dialog').dialog('option', 'title', "Copy Immunization");
			$('#edit_immunization_dialog').dialog('open');
			$("#imm_immunization").focus();
		} else {
			$.jGrowl("Please select immunization to copy!")
		}
	});
	$("#add_nosh_imm").click(function(){
		$('#edit_immunization_form').clearForm();
		var currentDate = getCurrentDate();
		$('#imm_date').val(currentDate);
		$('#edit_immunization_dialog').dialog('option', 'title', "Add Immunization");
		$('#edit_immunization_dialog').dialog('open');
		$("#imm_immunization").focus();
	});
	$("#edit_nosh_imm").click(function(){
		var item = jQuery("#nosh_imm").getGridParam('selrow');
		if(item){
			jQuery("#nosh_imm").GridToForm(item,"#edit_immunization_form");
			var date = $('#imm_date').val();
			var edit_date = editDate(date);
			$('#imm_date').val(edit_date);
			var expiration = $('#imm_expiration').val();
			var edit_expiration = editDate1(expiration);
			$('#imm_expiration').val(edit_expiration);
			$('#edit_immunization_dialog').dialog('option', 'title', "Edit Immunization");
			$('#edit_immunization_dialog').dialog('open');
			$("#imm_immunization").focus();
		} else {
			$.jGrowl("Please select immunization to edit!")
		}
	});
	$("#print_ccr").click(function() {
		window.open("print_ccr");
	});
	$("#csv_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 925, 
		draggable: false,
		resizable: false,
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#csv_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#csv_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/import-csv",
							data: str,
							success: function(data){
								$.jGrowl(data,{sticky:true});
								$('#csv_form').clearForm();
								$('#csv_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#csv_form').clearForm();
				$('#csv_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});

	//Encounters section
	$("#preview_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		buttons: [
		{
			text: "Toggle Fullscreen",
			click: function() {
				var w = $(this).dialog('option', 'width');
				if (w == 800) {
					$(this).dialog('option', {
						height: $(window).height(),
						width: $(window).width(),
						position: { my: 'center', at: 'center', of: window }
					});
				} else {
					$(this).dialog('option', {
						height: 500,
						width: 800,
						position: { my: 'center', at: 'center', of: '#maincontent' }
					});
				}
			}
		}],
		close: function(event, ui) {
			$(this).dialog('option', {
				height: 500,
				width: 800,
				position: { my: 'center', at: 'center', of: '#maincontent' }
			});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#preview_encounter").click(function() {
		$("#preview").load('ajaxchart/modal-view2/' + noshdata.eid);
		$("#preview_dialog").dialog('open');
	});
	$('#detail_encounter').click(function(){
		$("#new_encounter_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-encounter",
			dataType: "json",
			success: function(data){
				$('#detail_encounter_number').html(data.eid);
				$(".detail_encounter_noshow").hide();
				$(".new_encounter_dialog_encounter_provider_div").hide();
				if (data.encounter_role == "Consulting Provider" || data.encounter_role == "Referring Provider") {
					$(".referring_provider_div").show();
				} else {
					$(".referring_provider_div").hide();
				}
				$.each(data, function(key, value){
					if (key != "encounter_type") {
						$("#new_encounter_form :input[name='" + key + "']").val(value);
					}
				});
			}
		});
		
	});
	$("#billing_encounter").click(function(){
		$("#billing_eid_1").val(noshdata.eid);
		$("#billing_detail_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxencounter/check-assessment",
			success: function(data){
				if (data == "OK!") {
					$.ajax({
						type: "POST",
						url: "ajaxencounter/compile-billing",
						success: function(data){
							$.jGrowl(data);
							reload_grid("billing_cpt_list");
						}
					});
				} else {
					$.jGrowl(data);
				}
				reload_grid("billing_cpt_list");
			}
		});
	});
	$("#save_draft").click(function() {
		closeencounter();
	});
	$("#sign_encounter").click(function() {
		if(confirm('Are you sure you want to sign?')){ 
			var signed = "Yes";
			$.ajax({
				type: "POST",
				url: "ajaxencounter/check-encounter",
				success: function(data){
					if (data == "") {
						$.ajax({
							type: "POST",
							url: "ajaxencounter/sign-encounter",
							success: function(data){
								$.jGrowl(data);
								reload_grid("encounters");
								if (data == 'Encounter Signed!') {
									closeencounter();
								}
							}
						});
					} else {
						$.jGrowl(data);
					}
				}
			});
		}
	});
	$("#delete_encounter").click(function() {
		if(confirm('Are you sure you want to delete this encounter?')){ 
			$.ajax({
				type: "POST",
				url: "ajaxencounter/delete-encounter",
				data: "eid=" + noshdata.eid,
				success: function(data){
					$.jGrowl(data);
					if (data == 'Encounter deleted!') {
						closeencounter();
					}
				}
			});
		}
	});
	$("#encounter_tags").tagit({
		tagSource: function (req, add){
			$.ajax({
				url: "ajaxsearch/search-tags",
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
		tagsChanged: function(a, b) {
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/save-tag/eid/" + noshdata.eid,
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/remove-tag/eid/" + noshdata.eid,
					data: 'tag=' + a
				});
			}
		}
	});
	$("#textdump_group").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		draggable: false,
		resizable: false,
		focus: function (event, ui) {
			var id = $("#textdump_group_id").val();
			if (id != '') {
				$("#"+id).focus();
			}
		},
		close: function (event, ui) {
			$("#textdump_group_target").val('');
			$("#textdump_group_add").val('');
			$("#textdump_group_html").html('');
		}
	});
	$("#textdump").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		draggable: false,
		resizable: false,
		close: function (event, ui) {
			$("#textdump_target").val('');
			$("#textdump_input").val('');
			$("#textdump_add").val('');
			$("#textdump_group_item").val('');
			$("#textdump_html").html('');
		},
		buttons: {
			Cancel: function() {
				var id = $("#textdump_target").val();
				var a = $("#textdump_input").val();
				var b = $("#"+id).val();
				var c = b.replace(a, "");
				$("#"+id).val(c);
				$("#textdump").dialog('close');
			}
		}
	});
	$("#textdump_group_html").tooltip();
	$("#textdump_html").tooltip();
	$("#copy_encounter").click(function(){
		$("#copy_encounter_dialog").dialog('open');
	});
	$("#copy_encounter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 550, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxsearch/previous-encounters",
				dataType: "json",
				success: function(data){
					$("#copy_encounter_from").removeOption(/./);
					$("#copy_encounter_from").addOption(data,false);
				}
			});
		},
		buttons: {
			'Copy': function() {
				if(confirm('Are you sure you want to copy this encounter into the current?')){ 
					var bValid = true;
					$("#copy_encounter_form").find("[required]").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
					if (bValid) {
						var str = $("#copy_encounter_form").serialize();
						if(str){
							$.ajax({
								type: "POST",
								url: "ajaxencounter/copy-encounter",
								data: str,
								success: function(data){
									$.jGrowl(data);
									$("#copy_encounter_form").clearForm();
									$("#copy_encounter_dialog").dialog('close');
								}
							});
						} else {
							$.jGrowl("Please complete the form");
						}
					}
				}
			},
			Cancel: function() {
				$("#copy_encounter_form").clearForm();
				$("#copy_encounter_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#creditcard_type").addOption({"":"Select a credit card type","MasterCard":"MasterCard","Visa":"Visa","Discover":"Discover","Amex":"American Express"}, false);
	$("#creditcard_expiration").mask("99/9999");
	$("#creditcard_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-creditcard",
				dataType: "json",
				success: function(data){
					if (data.message == 'y') {
						$.each(data, function(key, value){
							if (key != 'message') {
								$("#creditcard_form :input[name='" + key + "']").val(value);
							}
						});
					}
				}
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#creditcard_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#creditcard_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/save-creditcard",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#creditcard_form").clearForm();
								$("#creditcard_dialog").dialog('close');
								total_balance();
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#creditcard_form").clearForm();
				$("#creditcard_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".add_creditcard").click(function() {
		$("#creditcard_dialog").dialog('open');
	});
});
