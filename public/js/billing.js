$(document).ready(function() {
	function billing_detail_save() {
		var bValid = true;
		var a = $("#billing_eid_1");
		var b = $("#billing_insurance_id_1");
		bValid = bValid && checkEmpty(a, "Encounter");
		bValid = bValid && checkEmpty(b, "Primary Insurance"); 
		if (bValid) {
			var str = $("#billing_detail_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxchart/billing-save1",
					data: str,
					success: function(data){
						$.jGrowl(data);
						total_balance();
						reload_grid("billing_encounters");
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	}
	function get_insurance_info() {
		var a = $("#billing_insurance_id_1").val();
		var b = $("#billing_insurance_id_2").val();
		$.ajax({
			type: "POST",
			url: "ajaxchart/get-insurance-info",
			data: "insurance_id_1=" + a + "&insurance_id_2=" + b,
			dataType: "json",
			success: function(data){
				$("#billing_insuranceinfo1").html(data.result1);
				$("#billing_insuranceinfo2").html(data.result2);
			}
		});
	}
	$("#billing_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 825, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			total_balance();
			jQuery("#billing_encounters").jqGrid('GridUnload');
			jQuery("#billing_encounters").jqGrid({
				url: "ajaxchart/billing-encounters",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Chief Complaint','Charges','Balance'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_cc',index:'encounter_cc',width:355},
					{name:"charges",index:"charges",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_encounters_pager'),
				sortname: 'encounter_DOS',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Bills from Encounters - Expand Row for Payment History",
			 	height: "100%",
			 	loadComplete: function(data) {
			 		var id1 = $('#billing_list_eid').val();
			 		var id2 = $('#billing_list_other_billing_id').val();
			 		if (id1 != '') {
			 			jQuery("#billing_encounters").expandSubGridRow(id1);
			 			$('#billing_list_eid').val('');
			 		}
			 		if (id2 != '') {
			 			jQuery("#billing_other").expandSubGridRow(id2);
			 			$('#billing_list_other_billing_id').val('');
			 		}
			 	},
			 	subGrid: true,
			 	subGridRowExpanded: function(subgrid_id, row_id) {
			 		var subgrid_table_id, pager_id;
			 		subgrid_table_id = subgrid_id+"_t";
			 		pager_id = "p_"+subgrid_table_id;
			 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			 		jQuery("#"+subgrid_table_id).jqGrid({
			 			url: "ajaxchart/billing-payment-history1/"+row_id,
			 			datatype: "json",
			 			mtype: "POST",
			 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
			 			colModel:[
			 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
			 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
			 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			 				{name:"payment_type",index:"payment_type",width:300,align:"right"}
			 			], 
			 			rowNum:10,
			 			pager: pager_id,
			 			sortname: 'dos_f', 
			 			sortorder: "desc", 
			 			height: '100%',
			 			footerrow : true,
			 			userDataOnFooter : true,
			 			onSelectRow: function(id) {
			 				$('#billing_billing_core_id').val(id);
			 				$.ajax({
								type: "POST",
								url: "ajaxchart/get-payment",
								data: "id=" + id,
								dataType: "json",
								success: function(data){
									$.each(data, function(key, value){
										$("#billing_payment_form :input[name='" + key + "']").val(value);
										var input_id = $("#billing_payment_form :input[name='" + key + "']").attr('id');
										$("#" + input_id + "_old").val(value);
									});
									$('#billing_payment_dialog').dialog('open');
								}
							});
			 			},
			 			jsonReader: { repeatitems : false, id: "0" }
			 		});
			 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
			 			search:false,
			 			edit:false,
			 			add:false,
			 			del:false
			 		}).jqGrid('navButtonAdd',"#"+pager_id,{
			 			caption:"Delete Payment", 
						buttonicon:"ui-icon-trash", 
						onClickButton: function(){ 
							var id = jQuery(this).getGridParam('selrow');
							if(id){
				 				$('#billing_billing_core_id').val(id);
				 				$.ajax({
									type: "POST",
									url: "ajaxchart/get-payment",
									data: "id=" + id,
									dataType: "json",
									success: function(data){
										$('#billing_list_eid').val(data.eid);
									}
								});
				 				if(confirm('Are you sure you want to delete this payment?')){
									$.ajax({
										type: "POST",
										url: "ajaxchart/delete-payment1",
										data: "id=" + id,
										dataType: 'json',
										success: function(data){
											$.jGrowl(data.message);
											$("#billing_encounters").setCell(data.id,"balance",data.balance); 
										}
									});
									jQuery(this).trigger("reloadGrid");
									total_balance();
								}
							} else {
								$.jGrowl('Choose payment to delete!');
							}
						}, 
						position:"last"
					});
			 	}
			}).navGrid('#billing_encounters_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#billing_other").jqGrid('GridUnload');
			jQuery("#billing_other").jqGrid({
				url: "ajaxchart/billing-other",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Reason','Charge','Balance'],
				colModel:[
					{name:'other_billing_id',index:'other_billing_id',width:1,hidden:true},
					{name:'dos_f',index:'dos_f',width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"},unformat:editDate},
					{name:'reason',index:'reason',width:355},
					{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},		
					{name:"balance",index:"balance",width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_other_pager'),
				sortname: 'dos_f',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Miscellaneous Bills - Expand Row for Payment History",
			 	height: "100%",
			 	loadComplete: function(data) {
			 		var id1 = $('#billing_list_eid').val();
			 		var id2 = $('#billing_list_other_billing_id').val();
			 		if (id1 != '') {
			 			jQuery("#billing_encounters").expandSubGridRow(id1);
			 			$('#billing_list_eid').val('');
			 		}
			 		if (id2 != '') {
			 			jQuery("#billing_other").expandSubGridRow(id2);
			 			$('#billing_list_other_billing_id').val('');
			 		}
			 	},
			 	subGrid: true,
			 	subGridRowExpanded: function(subgrid_id, row_id) {
			 		var subgrid_table_id, pager_id;
			 		subgrid_table_id = subgrid_id+"_t1";
			 		pager_id = "p1_"+subgrid_table_id;
			 		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			 		jQuery("#"+subgrid_table_id).jqGrid({
			 			url:"ajaxchart/billing-payment-history2/"+row_id,
			 			datatype: "json",
			 			mtype: "POST",
			 			colNames:['ID','Date of Payment','Payment Amount','Payment Type'],
			 			colModel:[
			 				{name:"billing_core_id",index:"billing_core_id",width:1,hidden:true},
			 				{name:"dos_f",index:"dos_f",width:100,formatter:'date',formatoptions:{srcformat:"m/d/Y", newformat: "ISO8601Short"}},
			 				{name:"payment",index:"payment",width:200,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
			 				{name:"payment_type",index:"payment_type",width:300,align:"right"}
			 			], 
			 			rowNum:10,
			 			pager: pager_id,
			 			sortname: 'dos_f', 
			 			sortorder: "desc", 
			 			height: '100%',
			 			footerrow : true,
			 			userDataOnFooter : true,
			 			onSelectRow: function(id) {
			 				$('#billing_billing_core_id').val(id);
			 				$.ajax({
								type: "POST",
								url: "ajaxchart/get-payment",
								data: "id=" + id,
								dataType: "json",
								success: function(data){
									$.each(data, function(key, value){
										$("#billing_payment_form :input[name='" + key + "']").val(value);
										var input_id = $("#billing_payment_form :input[name='" + key + "']").attr('id');
										$("#" + input_id + "_old").val(value);
									});
									$('#billing_payment_dialog').dialog('open');
								}
							});
			 			},
			 			jsonReader: { repeatitems : false, id: "0" }
			 		});
			 		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
			 			search:false,
			 			edit:false,
			 			add:false,
			 			del:false
			 		}).jqGrid('navButtonAdd',"#"+pager_id,{
			 			caption:"Delete Payment", 
						buttonicon:"ui-icon-trash", 
						onClickButton: function(){ 
							var id = jQuery(this).getGridParam('selrow');
							if(id){
				 				$('#billing_billing_core_id').val(id);
				 				$.ajax({
									type: "POST",
									url: "ajaxchart/get-payment",
									data: "id=" + id,
									dataType: "json",
									success: function(data){
										$('#billing_list_eid').val(data.eid);
									}
								});
				 				if(confirm('Are you sure you want to delete this payment?')){
									$.ajax({
										type: "POST",
										url: "ajaxchart/delete-payment2",
										data: "id=" + id,
										dataType: 'json',
										success: function(data){
											$.jGrowl(data.message);
											$("#billing_other").setCell(data.id,"balance",data.balance); 
										}
									});
									jQuery(this).trigger("reloadGrid");
									total_balance();
								}
							} else {
								$.jGrowl('Choose payment to delete!');
							}
						}, 
						position:"last"
					});
			 	}
			}).navGrid('#billing_other_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#billing_list").click(function() {
		$("#billing_list_dialog").dialog('open');
	});
	$("#billing_detail_accordion").accordion({
		heightStyle: "content",
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
			var active = $("#billing_detail_accordion").accordion("option", "active");
			if (active != 0) {
				billing_detail_save();
			}
		}
	});
	$("#billing_detail_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 840, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			var id = $("#billing_eid_1").val();
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-billing/" + id,
				dataType: "json",
				success: function(data){
					if (data.message = "OK") {
						$("#billing_icd1").addOption(data, false).removeOption("message").trigger("liszt:updated");
					} else {
						$.jGrowl(data.message);
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-prevention/" + id,
				dataType: "json",
				success: function(data){
					$("#established_prevent1").attr("value", data.prevent_established1);
					$("#new_prevent1").attr("value", data.prevent_new1);
					$("#established_prevent1_text").html(data.prevent_established1);
					$("#new_prevent1_text").html(data.prevent_new1);
				}
			});	
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-insurance-id/" + id,
				dataType: "json",
				success: function(data){
					$("#billing_insurance_id_1").val(data.insurance_id_1);
					$("#billing_insurance_id_2").val(data.insurance_id_2);
					$("#billing_insurance_id_1_old").val(data.insurance_id_1);
					$("#billing_insurance_id_2_old").val(data.insurance_id_2);
					if (data.insurance_id_1 == '') {
						$("#billing_insuranceinfo1").html("No primary insurance chosen");
					}
					if (data.insurance_id_2 == '') {
						$("#billing_insuranceinfo2").html("No secondary insurance chosen");
					}
					get_insurance_info();
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-assessment/" + id,
				dataType: "json",
				success: function(data){
					if (data != '') {
						if(data.assessment_1.length!=0){
							var label1 = '<strong>Diagnosis A:</strong> ' + data.assessment_1;
						}
						if(data.assessment_2.length!=0){
							label1 += '<br><strong>Diagnosis B:</strong> ' + data.assessment_2;
						}
						if(data.assessment_3.length!=0){
							label1 += '<br><strong>Diagnosis C:</strong> ' + data.assessment_3;
						}
						if(data.assessment_4.length!=0){
							label1 += '<br><strong>Diagnosis D:</strong> ' + data.assessment_4;
						}
						if(data.assessment_5.length!=0){
							label1 += '<br><strong>Diagnosis E:</strong> ' + data.assessment_5;
						}
						if(data.assessment_6.length!=0){
							label1 += '<br><strong>Diagnosis F:</strong> ' + data.assessment_6;
						}
						if(data.assessment_7.length!=0){
							label1 += '<br><strong>Diagnosis G:</strong> ' + data.assessment_7;
						}
						if(data.assessment_8.length!=0){
							label1 += '<br><strong>Diagnosis H:</strong> ' + data.assessment_8;
						}
						if(data.assessment_9.length!=0){
							label1 += '<br><strong>Diagnosis I:</strong> ' + data.assessment_9;
						}
						if(data.assessment_10.length!=0){
							label1 += '<br><strong>Diagnosis J:</strong> ' + data.assessment_10;
						}
						if(data.assessment_11.length!=0){
							label1 += '<br><strong>Diagnosis K:</strong> ' + data.assessment_11;
						}
						if(data.assessment_12.length!=0){
							label1 += '<br><strong>Diagnosis L:</strong> ' + data.assessment_12;
						}
						$("#billing_icd9").html(label1);
					}
				}
			});
			jQuery("#billing_cpt_list").jqGrid('GridUnload');
			jQuery("#billing_cpt_list").jqGrid({
				url:"ajaxchart/procedure-codes/" + id,
				datatype: "json",
				mtype: "POST",
				colNames:['ID','CPT','CPT Description','Charge','Units','Modifier','ICD Pointer','DOS From','DOS To'],
				colModel:[
					{name:'billing_core_id',index:'billing_core_id',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:50},
					{name:'cpt_description',index:'cpt_description',width:200},
					{name:'cpt_charge',index:'cpt_charge',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'unit',index:'unit',width:50},
					{name:'modifier',index:'modifier',width:50},
					{name:'icd_pointer',index:'icd_pointer',width:50,edittype: 'select'},
					{name:'dos_f',index:'dos_f',width:75},
					{name:'dos_t',index:'dos_t',width:75}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_cpt_list_pager'),
				sortname: 'cpt_charge',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Procedure codes for this encounter - Click on ICD Pointer column to get diagnosis codes for each procedure.",
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol == 6) {
						var item = jQuery("#billing_cpt_list").getCell(id,'icd_pointer');
						$.ajax({
							type: "POST",
							url: "ajaxchart/define-icd/" + id,
							data: "icd=" + item,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.item, {sticky:true});	
							}
						});
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#billing_cpt_list_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#billing_insurance_list1").jqGrid('GridUnload');
			jQuery("#billing_insurance_list1").jqGrid({
				url: "ajaxdashboard/insurance",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:270},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:100},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#billing_insurance_pager1'),
				sortname: 'insurance_order',
				viewrecords: true,
				sortorder: "asc",
				caption:"Insurance Payors",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#billing_insurance_pager1',{search:false,edit:false,add:false,del:false});
		},
		beforeClose: function(event, ui) {
			var bValid = true;
			var a = $("#billing_eid_1");
			var b = $("#billing_insurance_id_1");
			bValid = bValid && checkEmpty(a, "Encounter");
			bValid = bValid && checkEmpty(b, "Primary Insurance"); 
			if (bValid) {
				billing_detail_save();
				$("#billing_detail_form").clearForm();
				$("#billing_icd9").html('');
				$("#billing_insuranceinfo1").html('');
				$("#billing_insuranceinfo2").html('');
				return true;
			} else {
				return false;
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#billing_other_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#billing_other_reason1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/billing-reason",
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
		},
		buttons: {
			"Save": function() {
				var bValid = true;
				$("#billing_other_form1").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#billing_other_form1").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/billing-other-save",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$('#billing_other_form1').clearForm();
								reload_grid("billing_other");
								$("#billing_other_dialog").dialog('close');
								total_balance();
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#billing_other_form1').clearForm();
				$("#billing_other_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#billing_notes_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-billing-notes",
				success: function(data){
					$('#billing_billing_notes').val(data);
					$('#billing_billing_notes_old').val(data)
				}
			});
		},
		buttons: {
			'Save': function() {
				var str = $("#billing_notes_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxchart/edit-billing-notes",
						data: str,
						success: function(data){
							$.jGrowl(data);
							total_balance();
							$('#billing_notes_form').clearForm();
							$('#billing_notes_dialog').dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$('#billing_notes_form').clearForm();
				$('#billing_notes_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#billing_notes").click(function(){
		$('#billing_notes_dialog').dialog('open');
	});
	$("#billing_other_dos_f1").mask("99/99/9999").datepicker();
	$("#billing_payment_dos_f").mask("99/99/9999").datepicker();
	$("#billing_payment_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#billing_payment_payment_type").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/payment-type",
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
				minLength: 0
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#billing_payment_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#billing_payment_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/payment-save",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								if (data.eid != ''){
									$('#billing_list_eid').val(data.eid);
									reload_grid("billing_encounters");
									total_balance();
								}
								if (data.other_billing_id != ''){
									$('#billing_list_other_billing_id').val(data.other_billing_id);
									reload_grid("billing_other");
									total_balance();
								}
								$("#billing_payment_form").clearForm();
								$("#billing_payment_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#billing_payment_form").clearForm();
				$("#billing_payment_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#billing_payment_payment_type").focus(function(){
		$("#billing_payment_payment_type").autocomplete("search", '1');
	});
	$("#edit_encounter_charge").click(function(){
		var id = jQuery("#billing_encounters").getGridParam('selrow');
		if(id){
			$("#billing_eid_1").val(id);
			$("#billing_detail_dialog").dialog('open');
		} else {
			$.jGrowl("Please select encounter to edit billing details!");
		}
	});
	$("#payment_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			$('#billing_payment_eid').val(item);
			var currentDate = getCurrentDate();
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select encounter to add payment!");
		}
	});
	$("#invoice_encounter_charge").click(function(){
		var item = jQuery("#billing_encounters").getGridParam('selrow');
		if(item){
			window.open("print_invoice1/" + item + "/0/0");
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	$("#add_charge").click(function(){
		var currentDate = getCurrentDate();
		$('#billing_other_dos_f1').val(currentDate);
		$('#billing_other_dialog').dialog('open');
		$("#billing_other_reason1").focus();
	});
	$("#edit_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			jQuery("#billing_other").GridToForm(item,"#billing_other_form1");
			$('#billing_other_dialog').dialog('open');
			$("#billing_other_reason1").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to edit!");
		}
	});
	$("#payment_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			$('#billing_payment_other_billing_id').val(item);
			var currentDate = getCurrentDate();
			$('#billing_payment_dos_f').val(currentDate);
			$('#billing_payment_dialog').dialog('open');
			$("#billing_payment_payment").focus();
		} else {
			$.jGrowl("Please select miscellaneous bill to add payment!");
		}
	});
	$("#invoice_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			window.open("print_invoice2/" + item);
		} else {
			$.jGrowl("Please select encounter to print invoice!");
		}
	});
	$("#delete_charge").click(function(){
		var item = jQuery("#billing_other").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this miscellaneous bill?')){
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-other-bill",
					data: "billing_core_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("billing_other");
					}
				});
			}
		} else {
			$.jGrowl("Please select miscellaneous bill to delete!");
		}
	});
	$("#billing_modifier1").addOption({"":"","25":"25 - Significant, Separately Identifiable E & M Service.","52":"52 - Reduced Service .","59":"59 - Distinct Procedural Service."}, false);
	$("#cpt_helper_items1").accordion({active: false, fillSpace: true});
	$("#cpt_helper_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			var b = $("input[name='billing_cpt_helper1']:checked").val();
			if (b != '') {
				$("#billing_cpt1").val(b);
				$.ajax({
					type: "POST",
					url: "ajaxchart/get-cpt-charge",
					data: "cpt=" + b,
					success: function(data){
						$("#billing_cpt_charge1").val(data);
					}
				});
			} else {
				$("#billing_cpt1").val('');
				$("#billing_cpt_charge1").val('');
			}
			$('#cpt_helper_items1').clearDiv();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_invoice1").click(function(){
		var ins1 = $("#billing_insurance_id_1").val();
		var ins2 = $("#billing_insurance_id_2").val();
		if (ins2 == '') {
			ins2 = '0';
		}
		var eid = $("#billing_eid_1").val();
		window.open("print_invoice1/" + eid + "/" + ins1 + "/" + ins2);
		total_balance();
		reload_grid("billing_encounters");
	});
	$("#print_hcfa1").click(function(){
		var a = $("#billing_insurance_id_1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Primary Insurance");
		if (bValid) {
			var ins1 = $("#billing_insurance_id_1").val();
			var ins2 = $("#billing_insurance_id_2").val();
			var eid = $("#billing_eid_1").val();
			if (ins1 == '' || ins1 == '0') {
				$.jGrowl("No HCFA-1500 printed due to no primary insurance!");
			} else {
				window.open("generate_hcfa1/n/" + eid + "/" + ins1 + "/" + ins2);
				total_balance();
				reload_grid("billing_encounters");
			}
		}
	});
	$("#print_hcfa2").click(function(){
		var a = $("#billing_insurance_id_1");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Primary Insurance");
		if (bValid) {
			var ins1 = $("#billing_insurance_id_1").val();
			var ins2 = $("#billing_insurance_id_2").val();
			var eid = $("#billing_eid_1").val();
			if (ins1 == '' || ins1 == '0') {
				$.jGrowl("No HCFA-1500 printed due to no primary insurance!");
			} else {
				window.open("generate_hcfa1/y/" + eid + "/" + ins1 + "/" + ins2);
				total_balance();
				reload_grid("billing_encounters");
			}
		}
	});
	$("#billing_icd1").chosen();
	$("#add_billing_cpt1").click(function(){
		$('#billing_form1').clearForm();
		$("#billing_unit1").val('1');
		$("#billing_modifier1").val('');
		var eid = $("#billing_eid_1").val();
		$.ajax({
			type: "POST",
			url: "ajaxchart/get-encounter-date/" + eid,
			success: function(data){
				var a = editDate1(data);
				$("#billing_dos_f1").val(a);
				$("#billing_dos_t1").val(a);
			}
		});
		$("#cpt_billing_dialog1").dialog('open');
		$("#billing_cpt1").focus();
	});
	$("#edit_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			jQuery("#billing_cpt_list").GridToForm(item,"#billing_form1");
			var dx = jQuery("#billing_cpt_list").getCell(item,"icd_pointer");
			var icd_array = String(dx).split("");
			var length = icd_array.length;
			for (var i = 0; i < length; i++) {
				$("#billing_icd1").selectOptions(icd_array[i]);
			}
			$("#billing_icd1").trigger("liszt:updated");
			$("#cpt_billing_dialog1").dialog('open');
			$("#billing_cpt_charge1").focus();
		} else {
			$.jGrowl("Please select row to edit!");
		}
	});
	$("#remove_billing_cpt1").click(function(){
		var item = jQuery("#billing_cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "ajaxchart/remove-cpt",
				type: "POST",
				data: "billing_core_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("billing_cpt_list");
				}
			});
		} else {
			$.jGrowl("Please select row to remove!");
		}
	});
	$("#cpt_billing_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#billing_cpt1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/cpt1",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							} else {
								var addterm = [{"label": req.term + ": Select to add CPT to database.", "value":"*/add/*", "value1": req.term}];
								add(addterm);
							}
						}
					});
				},
				minLength: 3,
				select: function(event, ui){
					if (ui.item.value == "*/add/*") {
						$("#configuration_cpt_form").clearForm();
						if (ui.item.value1.length > 5) {
							$("#configuration_cpt_description").val(ui.item.value1);
						} else {
							$("#configuration_cpt_code").val(ui.item.value1);
						}
						$('#configuration_cpt_origin').val("billing_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					} else {
						$("#billing_cpt_charge1").val(ui.item.charge);
					}
				},
				change: function (event, ui) {
					if(!ui.item){
						$.jGrowl("CPT code must be selected from the database!");
						$("#billing_cpt").addClass("ui-state-error");
					} else {
						$("#billing_cpt").removeClass("ui-state-error");
					}
				}
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#billing_form1").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#billing_form1").serialize();
					var eid = $("#billing_eid_1").val();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/billing-save/" + eid,
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#billing_form1").clearForm();
								$("#cpt_billing_dialog1").dialog('close');
								reload_grid("billing_cpt_list");
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#billing_form1").clearForm();
				$("#cpt_billing_dialog1").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('#cpt_helper1').click(function(){
		$("#cpt_helper_dialog1").dialog('open');
	});
	$('#update_cpt_charge1').click(function(){
		var item = $("#billing_cpt1").val();
		if(item != ''){
			var item2 = $("#billing_cpt_charge1").val();
			$.ajax({
				url: "ajaxchart/update-cpt-charge",
				type: "POST",
				data: "cpt=" + item + "&cpt_charge=" + item2,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please enter a CPT code to update!");
		}
	});
	$("#billing_dos_f1").mask("99/99/9999").datepicker();
	$("#billing_dos_t1").mask("99/99/9999").datepicker();
	$("#billing_select_insurance1").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_1").val(item);
			get_insurance_info();
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_insurance2").click(function(){
		var item = jQuery("#billing_insurance_list1").getGridParam('selrow');
		if(item){
			$("#billing_insurance_id_2").val(item);
			get_insurance_info();
		} else {
			$.jGrowl("Please select insurance!");
		}
	});
	$("#billing_select_self_pay").click(function(){
		$("#billing_insurance_id_1").val('0');
		$("#billing_insurance_id_2").val('');
		get_insurance_info();
	});
	$("#billing_clear_insurance1").click(function(){
		$("#billing_insurance_id_1").val('');
		get_insurance_info();
	});
	$("#billing_clear_insurance2").click(function(){
		$("#billing_insurance_id_2").val('');
		get_insurance_info();
	});
	$(".insurance_billing").click(function() {
		$("#demographics_insurance_dialog").dialog('open');
	});
	$("#cpt_link1").click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 7);
	});
	if (noshdata.financial != '') {
		$("#billing_list_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/reset-session",
			success: function(data){
				noshdata.financial = '';
			}
		});
	}
});
