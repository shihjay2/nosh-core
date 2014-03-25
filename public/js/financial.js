$(document).ready(function() {
	function signedlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'Draft';
		}
		if (cellvalue == 'Yes') {
			return 'Signed';
		}
	}
	function batchlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'None';
		}
		if (cellvalue == 'Pend') {
			return 'Print Image';
		}
		if (cellvalue == 'HCFA') {
			return 'HCFA-1500';
		}
	}
	$("#nosh_financial").click(function() {
		$("#financial_dialog").dialog('open');
	});
	$("#dashboard_billing").click(function() {
		$("#financial_dialog").dialog('open');
	});
	$("#financial_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 925, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#financial_accordion").accordion({ heightStyle: "content" });
			jQuery("#submit_list").jqGrid('GridUnload');
			jQuery("#submit_list").jqGrid({
				url:"ajaxfinancial/submit-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Status','Batch Type','Last Name','First Name','Chief Complaint'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_signed',index:'encounter_signed',width:110,formatter:signedlabel},
					{name:'bill_submitted',index:'bill_submitted',width:110,formatter:batchlabel},
					{name:'lastname',index:'lastname',width:125},
					{name:'firstname',index:'firstname',width:125},
					{name:'encounter_cc',index:'encounter_cc',width:225}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#submit_list_pager'),
				sortname: 'encounter_DOS',
				viewrecords: true,
				sortorder: "desc",
				caption:"Billed Encounters Waiting to be Submitted",
				height: "100%",
				onSelectRow: function(id) {
					$("#billing_eid").val(id);
					$("#submit_bill_dialog").dialog('open');
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#submit_list_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#bills_done").jqGrid('GridUnload');
			jQuery("#bills_done").jqGrid({
				url:"ajaxfinancial/bills-done",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Last Name','First Name','Chief Complaint','Charges','Total Balance'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'lastname',index:'lastname',width:125},
					{name:'firstname',index:'firstname',width:125},
					{name:'encounter_cc',index:'encounter_cc',width:220},
					{name:'charges',index:'charges',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'balance',index:'balance',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#bills_done_pager'),
				sortname: 'encounter_DOS',
				viewrecords: true,
				sortorder: "desc",
				caption:"Billed Encounters that have been Processed",
				height: "100%",
				loadComplete: function(data) {
					var id1 = $('#billing_list_eid').val();
					if (id1 != '') {
						jQuery("#bills_done").expandSubGridRow(id1);
						$('#billing_list_eid').val('');
					}
				},
				hiddengrid: true,
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
							{name:"payment_type",index:"payment_type",width:300,align:"right"}, 
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
			}).navGrid('#bills_done_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#outstanding_balance").jqGrid('GridUnload');
			jQuery("#outstanding_balance").jqGrid({
				url:"ajaxfinancial/outstanding-balance",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Last Name','First Name','Balance','Notes'],
				colModel:[
					{name:'pid',index:'pid',width:100},
					{name:'lastname',index:'lastname',width:150},
					{name:'firstname',index:'firstname',width:150},
					{name:'balance',index:'balance',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'billing_notes',index:'billing_notes',width:300}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#outstanding_balance_pager'),
				sortname: 'lastname',
				viewrecords: true,
				sortorder: "desc",
				caption:"Patients with Outstanding Balances - Clicking on a row will open the patient's chart.",
				height: "100%",
				onSelectRow: function(id) {
					$.ajax({
						type: "POST",
						url: "ajaxsearch/openchart",
						data: "pid=" + id,
						success: function(data){
							$.ajax({
								type: "POST",
								url: "ajaxfinancial/billing-set",
								dataType: "json",
								success: function(data){
									window.location = data.url;
								}
							});
						}
					});
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#outstanding_balance_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#monthly_stats").jqGrid('GridUnload');
			jQuery("#monthly_stats").jqGrid({
				url:"ajaxfinancial/monthly-stats",
				datatype: "json",
				mtype: "POST",
				colNames:['Month','Patients Seen','Total Billed','Total Payments','DNKA','LMC'],
				colModel:[
					{name:'month',index:'month',width:100},
					{name:'patients_seen',index:'patients_seen',width:100},
					{name:'total_billed',index:'total_billed',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'total_payments',index:'total_payments',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'dnka',index:'dnka',width:100},
					{name:'lmc',index:'lmc',width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#monthly_stats_pager'),
				sortname: 'month',
				viewrecords: true,
				sortorder: "desc",
				caption:"Monthly Statistics - Expand Row for Insurance Statistics",
				hiddengrid: true,
				height: "100%",
				subGrid: true,
				subGridRowExpanded: function(subgrid_id, row_id) {
					var subgrid_table_id, pager_id;
					subgrid_table_id = subgrid_id+"_t2";
					pager_id = "p2_"+subgrid_table_id;
					$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
					jQuery("#"+subgrid_table_id).jqGrid({
						url:"ajaxfinancial/monthly-stats-insurance/"+row_id,
						datatype: "json",
						mtype: "POST",
						colNames:['Insurance','Patients Seen'],
						colModel:[
							{name:"insuranceplan",index:"insuranceplan",width:300},
							{name:"ins_patients_seen",index:"ins_patients_seen",width:100}
						], 
						rowNum:10,
						pager: pager_id,
						sortname: 'insuranceplan', 
						sortorder: "desc", 
						height: '100%'
					});
					jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
						search:false,
						edit:false,
						add:false,
						del:false
					});
				}
			}).navGrid('#monthly_stats_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#yearly_stats").jqGrid('GridUnload');
			jQuery("#yearly_stats").jqGrid({
				url:"ajaxfinancial/yearly-stats",
				datatype: "json",
				mtype: "POST",
				colNames:['Year','Patients Seen','Total Billed','Total Payments','DNKA','LMC'],
				colModel:[
					{name:'year',index:'year',width:100},
					{name:'patients_seen',index:'patients_seen',width:100},
					{name:'total_billed',index:'total_billed',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'total_payments',index:'total_payments',width:100,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "$ "}},
					{name:'dnka',index:'dnka',width:100},
					{name:'lmc',index:'lmc',width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#yearly_stats_pager'),
				sortname: 'year',
				viewrecords: true,
				sortorder: "desc",
				caption:"Yearly Statistics - Expand Row for Insurance Statistics",
				hiddengrid: true,
				height: "100%",
				subGrid: true,
				subGridRowExpanded: function(subgrid_id, row_id) {
					var subgrid_table_id, pager_id;
					subgrid_table_id = subgrid_id+"_t3";
					pager_id = "p3_"+subgrid_table_id;
					$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
					jQuery("#"+subgrid_table_id).jqGrid({
						url:"ajaxfinancial/yearly-stats-insurance/"+row_id,
						datatype: "json",
						mtype: "POST",
						colNames:['Insurance','Patients Seen'],
						colModel:[
							{name:"insuranceplan",index:"insuranceplan",width:300},
							{name:"ins_patients_seen",index:"ins_patients_seen",width:100}
						], 
						rowNum:10,
						pager: pager_id,
						sortname: 'insuranceplan', 
						sortorder: "desc", 
						height: '100%'
					});
					jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
						search:false,
						edit:false,
						add:false,
						del:false
					});
				}
			}).navGrid('#yearly_stats_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#submit_bill_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 500, 
		modal: true,
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#submit_batch_printimage").click(function(){
		var eid = $("#billing_eid").val();
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/add-queue/Pend",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				$("#billing_eid").val('');
				reload_grid("submit_list");
				$("#submit_bill_dialog").dialog('close');
			}
		});	
	});
	$("#submit_batch_hcfa").click(function(){
		var eid = $("#billing_eid").val();
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/add-queue/HCFA",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				$("#billing_eid").val('');
				reload_grid("submit_list");
				$("#submit_bill_dialog").dialog('close');
			}
		});	
	});
	$("#submit_single_printimage").click(function(){
		var eid = $("#billing_eid").val();
		window.open("printimage_single/" + eid);
		$("#billing_eid").val('');
		reload_grid("submit_list");
		reload_grid("bills_done");
		$("#submit_bill_dialog").dialog('close');
	});
	$("#submit_hcfa").click(function(){
		var eid = $("#billing_eid").val();
		window.open("generate_hcfa/n/" + eid);
		$("#billing_eid").val('');
		reload_grid("submit_list");
		reload_grid("bills_done");
		$("#submit_bill_dialog").dialog('close');
	});
	$("#submit_hcfa2").click(function(){
		var eid = $("#billing_eid").val();
		window.open("generate_hcfa/y/" + eid);
		$("#billing_eid").val('');
		reload_grid("submit_list");
		reload_grid("bills_done");
		$("#submit_bill_dialog").dialog('close');
	});
	$("#submit_batch").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/check-batch/Pend/n",
			dataType: "json",
			async: false,
			success: function(data){
				if (data.response == 'OK') {
					noshdata.success_doc = true;
					noshdata.type = data.type;
					noshdata.filename = data.filename;
					reload_grid("submit_list");
					reload_grid("bills_done");
				} else {
					$.jGrowl(data.response);
				}
			}
		});
		if (noshdata.success_doc == true) {
			window.open("print_batch/" + noshdata.type + "/" + noshdata.filename);
			noshdata.success_doc = false;
			noshdata.type = '';
			noshdata.filename= '';
		}
	});
	$("#submit_batch1").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/check-batch/HCFA/n",
			dataType: "json",
			async: false,
			success: function(data){
				if (data.response == 'OK') {
					noshdata.success_doc = true;
					noshdata.type = data.type;
					noshdata.filename = data.filename;
					reload_grid("submit_list");
					reload_grid("bills_done");
				} else {
					$.jGrowl(data.response);
				}
			}
		});
		if (noshdata.success_doc == true) {
			window.open("print_batch/" + noshdata.type + "/" + noshdata.filename);
			noshdata.success_doc = false;
			noshdata.type = '';
			noshdata.filename= '';
		}
	});
	$("#submit_batch2").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/check-batch/HCFA/y",
			dataType: "json",
			async: false,
			success: function(data){
				if (data.response == 'OK') {
					noshdata.success_doc = true;
					noshdata.type = data.type;
					noshdata.filename = data.filename;
					reload_grid("submit_list");
					reload_grid("bills_done");
				} else {
					$.jGrowl(data.response);
				}
			}
		});
		if (noshdata.success_doc == true) {
			window.open("print_batch/" + noshdata.type + "/" + noshdata.filename);
			noshdata.success_doc = false;
			noshdata.type = '';
			noshdata.filename= '';
		}
	});
	$("#bill_resubmit").click(function(){
		var eid = jQuery("#bills_done").getGridParam('selrow');
		$.ajax({
			type: "POST",
			url: "ajaxfinancial/bill-resubmit",
			data: "eid=" + eid,
			success: function(data){
				$.jGrowl(data);
				reload_grid("bills_done");
				reload_grid("submit_list");
			}
		});
	});
	$("#payment_encounter_charge1").click(function(){
		var item = jQuery("#bills_done").getGridParam('selrow');
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
	$("#financial_query_type").addOption({"":"Choose query type","payment_type":"Payment type","cpt":"CPT Codes"}).change(function(){
		var a = $("#financial_query_type").val();
		if (a == "payment_type") {
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/query-payment-type-list",
				dataType: "json",
				success: function(data){
					$("#financial_query_variables").removeOption(/./);
					$("#financial_query_variables").addOption(data, false).trigger("liszt:updated");
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/query-year-list",
				dataType: "json",
				success: function(data){
					$("#financial_query_year").removeOption(/./);
					$("#financial_query_year").addOption(data, false).trigger("liszt:updated");
				}
			});
		}
		if (a == "cpt") {
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/query-cpt-list",
				dataType: "json",
				success: function(data){
					$("#financial_query_variables").removeOption(/./);
					$("#financial_query_variables").addOption(data, false).trigger("liszt:updated");
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/query-year-list",
				dataType: "json",
				success: function(data){
					$("#financial_query_year").removeOption(/./);
					$("#financial_query_year").addOption(data, false).trigger("liszt:updated");
				}
			});
		}
		if (a == "") {
			$("#financial_query_variables").removeOption(/./).trigger("liszt:updated");
			$("#financial_query_year").removeOption(/./).trigger("liszt:updated");

		}
	});
	$("#financial_query_type").val('');
	$("#financial_query_variables").chosen();
	$("#financial_query_year").chosen();
	$("#financial_query_submit").button().click(function(){
		var a = $("#financial_query_type");
		var b = $("#financial_query_variables");
		var c = $("#financial_query_year");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Search");
		bValid = bValid && checkEmpty(b,"Variables");
		bValid = bValid && checkEmpty(c,"Year");
		if (bValid) {
			var json_result = $("#financial_query_form").serializeObject();
			jQuery("#financial_query_results").jqGrid('GridUnload');
			jQuery("#financial_query_results").jqGrid({
				url:"ajaxfinancial/financial-query",
				datatype: "json",
				postData: json_result,
				mtype: "POST",
				colNames:['ID','Date','Last Name','First Name','Amount','Type'],
				colModel:[
					{name:'billing_core_id',index:'billing_core_id',width:1,hidden:true},
					{name:'dos_f',index:'dos_f',width:150,formatter:'date',formatoptions:{srcformat:"m/d/y", newformat: "ISO8601Short"}},
					{name:'lastname',index:'lastname',width:150,sortable:false},
					{name:'firstname',index:'firstname',width:150,sortable:false},
					{name:'amount',index:'amount',width:150,sortable:false},
					{name:'type',index:'type',width:150,sortable:false}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#financial_query_results_pager'),
				sortname: 'dos_f',
				viewrecords: true,
				sortorder: "desc",
				caption:"Search Results",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#financial_query_results_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#financial_query_reset").button().click(function(){
		$("#financial_query_form").clearForm();
		$("#financial_query_type").val('');
		jQuery("#financial_query_results").jqGrid('GridUnload');
		$("#financial_query_year").removeOption(/./).trigger("liszt:updated");
		$("#financial_query_variables").removeOption(/./).trigger("liszt:updated");
	});
	$("#financial_query_print").button().click(function(){
		var bValid = true;
		$("#financial_query_form").find("[required]").each(function() {
			var input_id = $(this).attr('id');
			var id1 = $("#" + input_id); 
			var text = $("label[for='" + input_id + "']").html();
			bValid = bValid && checkEmpty(id1, text);
		});
		if (bValid) {
			var json_result = $("#financial_query_form").serializeObject();
			$.ajax({
				type: "POST",
				url: "ajaxfinancial/financial-query-print",
				data: json_result,
				dataType: "json",
				async: false,
				success: function(data){
					if (data.message == 'OK') {
						noshdata.success_doc = true;
						noshdata.id_doc = data.id_doc;
					} else {
						$.jGrowl(data.message);
					}
				}
			});
			if (noshdata.success_doc == true) {
				window.open("financial_query_print/" + noshdata.id_doc);
				noshdata.success_doc = '';
				noshdata.id_doc = '';
			}
		}
	});
});
