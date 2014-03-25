$(document).ready(function() {
	function update_release_stats(hippa_id) {
		$.ajax({
			type: "POST",
			url: "ajaxchart/get-release-stats",
			data: "hippa_id=" + hippa_id,
			success: function(data){
				$("#print_release_stats").html(data);
			}
		});
	}
	$("#print_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#records_release").jqGrid('GridUnload');
			jQuery("#records_release").jqGrid({
				url: "ajaxchart/records-release",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Reason','Released To','Role'],
				colModel:[
					{name:'hippa_id',index:'hippa_id',width:1,hidden:true},
					{name:'hippa_date_release',index:'hippa_date_release',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'hippa_reason',index:'hippa_reason',width:400},
					{name:'hippa_provider',index:'hippa_provider',width:200},
					{name:'hippa_role',index:'hippa_role',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#records_release_pager'),
				sortname: 'hippa_date_release',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"View Past Records Releases",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#records_release_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_all_records").click(function(){
		$("#nosh_print1").show();
		$("#nosh_print2").hide();
		$("#nosh_print3").hide();
		$("#print_accordion_action").hide();
		$("#print_accordion").accordion("option", "active", 2);
	});
	$("#print_1year_records").click(function(){
		$("#nosh_print1").hide();
		$("#nosh_print2").show();
		$("#nosh_print3").hide();
		$("#print_accordion_action").hide();
		$("#print_accordion").accordion("option", "active", 2);
	});
	$("#print_queue_records").click(function(){
		$("#nosh_print1").hide();
		$("#nosh_print2").hide();
		$("#nosh_print3").show();
		$("#print_accordion_action").hide();
		$("#print_accordion").accordion("option", "active", 1);
	});
	$("#print_ccda").click(function(){
		var hippa_id = $("#print_hippa_id").val();
		window.open("ccda/" + hippa_id);
	});
	$("#print_list").click(function() {
		$("#print_list_dialog").dialog('open');
	});
	$("#print_chart").click(function() {
		var currentDate = getCurrentDate();
		$('#hippa_date_release1').val(currentDate);
		$("#print_chart_dialog").dialog('open');
		$('#hippa_reason1').focus();
	});
	$("#new_records_release").click(function() {
		var currentDate = getCurrentDate();
		$('#hippa_date_release1').val(currentDate);
		$("#print_chart_dialog").dialog('open');
		$('#hippa_reason1').focus();
	});
	$("#edit_records_release").click(function() {
		var item = jQuery("#records_release").getGridParam('selrow');
		if(item){
			$("#print_hippa_id").val(item);
			$("#print_accordion").accordion("option", "active", 0);
			$("#print_chart2_dialog").dialog('open');
		} else {
			$.jGrowl("Please select item!");
		}
	});
	$("#print_accordion").accordion({
		heightStyle: "content",
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#print_chart_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#hippa_reason1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/hippa-reason",
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
			'Continue': function() {
				var bValid = true;
				$("#print_chart_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#print_chart_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/print-chart-save",
							data: str,
							success: function(data){
								$("#print_hippa_id").val(data.trim());
								$("#print_chart_form").clearForm();
								$("#print_chart_dialog").dialog('close');
								reload_grid("records_release");
								if ($("#print_chart2_dialog").dialog("isOpen")===true) {
									update_release_stats(data);
								} else {
									$("#print_accordion").accordion("option", "active", 0);
									$("#print_chart2_dialog").dialog('open');
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#print_chart_form").clearForm();
				$("#print_chart_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_chart2_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function (event, ui) {
			$("#print_accordion_action").show();
			var hippa_id = $("#print_hippa_id").val()
			jQuery("#print_items_queue").jqGrid('GridUnload');
			jQuery("#print_items_queue").jqGrid({
				url:"ajaxchart/print-queue/" + hippa_id,
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Type','Description'],
				colModel:[
					{name:'hippa_id',index:'hippa_id',width:1,hidden:true},
					{name:'date',index:'date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'type',index:'type',width:200},
					{name:'description',index:'description',width:400}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_items_queue_pager'),
				sortname: 'hippa_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Print Items Queue",
			 	height: "100%"
			}).navGrid('#print_items_queue_pager',{search:false,edit:false,add:false,del:false});
			update_release_stats(hippa_id);
			jQuery("#print_encounters").jqGrid('GridUnload');
			jQuery("#print_encounters").jqGrid({
				url:"ajaxchart/print-encounters",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Chief Complaint','Provider'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_cc',index:'encounter_cc',width:475},
					{name:'encounter_provider',index:'encounter_provider', width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_encounters_pager'),
				sortname: 'encounter_DOS',
				viewrecords: true,
				sortorder: "desc",
				caption:"Encounters",
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				hiddengrid: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
						$("#print_encounter_view").load('ajaxchart/modal-view2/' + id);
						$("#print_encounter_view_dialog").dialog('open');
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_encounters_pager',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_encounters_pager',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_encounters").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue1",
								data: "eid=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
									$.jGrowl(data);
								}
							});
						}
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_messages").jqGrid('GridUnload');
			jQuery("#print_messages").jqGrid({
				url:"ajaxchart/print-messages",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date of Service','Subject','Message','Provider','To'],
				colModel:[
					{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
					{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'t_messages_subject',index:'t_messages_subject',width:475},
					{name:'t_messages_message',index:'t_messages_message',width:1,hidden:true},
					{name:'t_messages_provider',index:'t_messages_provider',width:100},
					{name:'t_messages_to',index:'t_messages_to',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_messages_pager'),
				sortname: 't_messages_dos',
				viewrecords: true,
				sortorder: "desc",
				caption:"Telephone Messages",
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				hiddengrid: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
						var row = jQuery("#print_messages").getRowData(id);
						var text = '<br><strong>Date:</strong>  ' + row['t_messages_dos'] + '<br><br><strong>Subject:</strong>  ' + row['t_messages_subject'] + '<br><br><strong>Message:</strong> ' + row['t_messages_message']; 
						$("#print_message_view_dialog").html(text);
						$("#print_message_view_dialog").dialog('open');
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_messages_pager',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_messages_pager',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_messages").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue2",
								data: "t_messages_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
									$.jGrowl(data);
								}
							});
						}
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_labs").jqGrid('GridUnload');
			jQuery("#print_labs").jqGrid({
				url: "ajaxchart/documents/Laboratory",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager8'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Labs",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager8',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager8',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_labs").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_radiology").jqGrid('GridUnload');
			jQuery("#print_radiology").jqGrid({
				url: "ajaxchart/documents/Imaging",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager9'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Imaging",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager9',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager9',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_radiology").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_cardiopulm").jqGrid('GridUnload');
			jQuery("#print_cardiopulm").jqGrid({
				url: "ajaxchart/documents/Cardiopulmonary",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager10'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Cardiopulmonary",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager10',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager10',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_cardiopulm").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_endoscopy").jqGrid('GridUnload');
			jQuery("#print_endoscopy").jqGrid({
				url: "ajaxchart/documents/Endoscopy",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager11'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Endoscopy",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager11',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager11',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_endoscopy").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_referrals").jqGrid('GridUnload');
			jQuery("#print_referrals").jqGrid({
				url: "ajaxchart/documents/Referrals",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager12'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Referrals",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager12',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager12',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_referrals").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_past_records").jqGrid('GridUnload');
			jQuery("#print_past_records").jqGrid({
				url: "ajaxchart/documents/Past_Records",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager13'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Past Records",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager13',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager13',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_past_records").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_outside_forms").jqGrid('GridUnload');
			jQuery("#print_outside_forms").jqGrid({
				url: "ajaxchart/documents/Other_Forms",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager14'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Other Forms",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager14',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager14',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_outside_forms").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
			jQuery("#print_letters").jqGrid('GridUnload');
			jQuery("#print_letters").jqGrid({
				url: "ajaxchart/documents/Letters",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:275},
					{name:'documents_desc',index:'documents_desc',width:300},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#print_pager15'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Letters",
				hiddengrid: true,
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onSelectRow: function(id,iCol){
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/view_documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#print_pager15',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#print_pager15',{
				caption:"Add Selected to Print Queue", 
				buttonicon:"ui-icon-plus", 
				onClickButton: function(){ 
					var id = jQuery("#print_letters").getGridParam('selarrrow');
					var hippa_id = $("#print_hippa_id").val();
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxchart/add-print-queue",
								data: "documents_id=" + id[i] + "&hippa_id=" + hippa_id,
								success: function(data){
								}
							});
						}
						$.jGrowl('Added ' + i + ' documents to the queue!');
						reload_grid("print_items_queue");
					} else {
						$.jGrowl('Choose document(s) to print!');
					}
				}, 
				position:"last"
			});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_fax_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#print_fax_recipient").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "<?php echo site_url('search/all_contacts');?>",
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
					$("#print_fax_faxnumber").val(ui.item.fax);
				}
			});
		},
		buttons: {
			'Add Contact to Address Book and Send Fax': function() {
				var bValid = true;
				$("#print_fax_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#print_fax_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/add-all-contact",
							data: str,
							success: function(data){
								$.jGrowl(data);
								var hippa_id = $("#print_hippa_id").val();
								var type = $("#print_fax_type").val();
								$.ajax({
									type: "POST",
									url: "ajaxchart/fax-chart/" + hippa_id + "/" + type,
									data: str,
									dataType: "json",
									success: function(data){
										$.jGrowl(data.message);
										$('#print_fax_form').clearForm();
										$('#print_fax_dialog').dialog('close');
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			'Send Fax': function() {
				var bValid = true;
				$("#print_fax_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#print_fax_form").serialize();
					var hippa_id = $("#print_hippa_id").val();
					var type = $("#print_fax_type").val();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/fax-chart/" + hippa_id + "/" + type,
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$('#print_fax_form').clearForm();
								$('#print_fax_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#print_fax_form').clearForm();
				$('#print_fax_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#hippa_date_release1").mask("99/99/9999");
	$("#hippa_date_release1").datepicker();
	$("#print_fax_faxnumber").mask("(999) 999-9999");
	$("#hippa_role1").addOption({"":"","Primary Care Provider":"Primary Care Provider","Consulting Provider":"Consulting Provider","Referring Provider":"Referring Provider"},false);
	$("#edit_hippa").click(function(){
		var hippa_id = $("#print_hippa_id").val();
		$.ajax({
			type: "POST",
			url: "ajaxchart/get-release/" + hippa_id,
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#print_chart_form :input[name='" + key + "']").val(value);
				});
				var date = $('#hippa_date_release1').val();
				var edit_date = editDate1(date);
				$('#hippa_date_release1').val(edit_date);
				$("#print_chart_dialog").dialog('open');
				$('#hippa_reason1').focus();
			}
		});
	});
	$("#print_all").click(function(){
		var hippa_id = $("#print_hippa_id").val();
		window.open("print_chart/" + hippa_id + "/all");
	});
	$("#fax_all").click(function(){
		$("#print_fax_type").val('all');
		$("#print_fax_dialog").dialog('open');
		$("#print_fax_recipient").focus();
	});
	$("#print_1year").click(function(){
		var hippa_id = $("#print_hippa_id").val();
		window.open("print_chart/" + hippa_id + "/1year");
	});
	$("#fax_1year").click(function(){
		$("#print_fax_type").val('1year');
		$("#print_fax_dialog").dialog('open');
		$("#print_fax_recipient").focus();
	});
	$("#print_queue").click(function(){
		var hippa_id = $("#print_hippa_id").val();
		window.open("print_chart/" + hippa_id + "/queue");
	});
	$("#fax_queue").click(function(){
		$("#print_fax_type").val('queue');
		$("#print_fax_dialog").dialog('open');
		$("#print_fax_recipient").focus();
	});
	$("#remove_item").click(function(){
		var item = jQuery("#print_items_queue").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/delete-chart-item",
				data: "hippa_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("print_items_queue"); 
				}
			});
		} else {
			$.jGrowl("Please select item to remove from the queue!");
		}
	});
	$("#clear_queue").click(function(){
		var item = $("#print_hippa_id").val();
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/clear-queue",
				data: "other_hippa_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("print_items_queue"); 
				}
			});
		} else {
			$.jGrowl("Please select item to remove from the queue!");
		}
	});
	$("#print_encounter_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_message_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
});
