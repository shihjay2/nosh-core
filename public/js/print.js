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
			$.ajax({
				url: "ajaxsearch/ref-provider1/all",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#print_chart_form_provider").html(data.html);
					$("#hippa_request_provider").html(data.html1);
					loadbuttons();
				}
			});
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
		jQuery("#records_request").jqGrid('GridUnload');
		jQuery("#records_request").jqGrid({
			url: "ajaxchart/records-request",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Date','Reason','From','Received','Type','History','Lab Type','Lab Date','Operation','Accident From','Accident To','Other','Address'],
			colModel:[
				{name:'hippa_request_id',index:'hippa_request_id',width:1,hidden:true},
				{name:'hippa_date_request',index:'hippa_date_request',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
				{name:'request_reason',index:'request_reason',width:345},
				{name:'request_to',index:'request_to',width:200},
				{name:'received',index:'recieved',width:50},
				{name:'request_type',index:'request_type',width:1,hidden:true},
				{name:'history_physical',index:'history_physical',width:1,hidden:true},
				{name:'lab_type',index:'lab_type',width:1,hidden:true},
				{name:'lab_date',index:'lab_date',width:1,hidden:true},
				{name:'op',index:'op',width:1,hidden:true},
				{name:'accident_f',index:'accident_f',width:1,hidden:true},
				{name:'accident_t',index:'accident_t',width:1,hidden:true},
				{name:'other',index:'other',width:1,hidden:true},
				{name:'address_id',index:'address_id',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#records_request_pager'),
			sortname: 'hippa_date_request',
			viewrecords: true,
			sortorder: "desc",
			caption:"Records Requests",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#records_request_pager',{search:false,edit:false,add:false,del:false});
		$("#print_list_dialog").dialog('open');
	});
	$("#print_chart").click(function() {
		var currentDate = getCurrentDate();
		$('#hippa_date_release').val(currentDate);
		$("#print_chart_dialog").dialog('open');
		$('#hippa_reason').focus();
	});
	$("#new_records_release").click(function() {
		var currentDate = getCurrentDate();
		$('#hippa_date_release').val(currentDate);
		$("#print_chart_dialog").dialog('open');
		$('#hippa_reason').focus();
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
	$("#new_records_request").click(function() {
		var currentDate = getCurrentDate();
		$('#hippa_date_request').val(currentDate);
		$("#hippa_request_dialog").dialog('open');
	});
	$("#edit_records_request").click(function() {
		var item = jQuery("#records_request").getGridParam('selrow');
		if(item){
			jQuery("#records_request").GridToForm(item,"#hippa_request_form");
			$("#hippa_request_dialog").dialog('open');
		} else {
			$.jGrowl("Please select item!");
		}
	});
	$("#hippa_request_received").click(function() {
		var item = jQuery("#records_request").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "ajaxchart/request-received/" + item,
				type: "POST",
				success: function(data){
					$.jGrowl(data);
					reload_grid("records_request");
				}
			});
		} else {
			$.jGrowl("Please select item!");
		}
	});
	$('#hippa_date_request').mask("99/99/9999").datepicker();
	$('#hippa_request_history_physical').mask("99/99/9999").datepicker();
	$('#hippa_request_lab_date').mask("99/99/9999").datepicker();
	$('#hippa_request_accident_f').mask("99/99/9999").datepicker();
	$('#hippa_request_accident_t').mask("99/99/9999").datepicker();
	$('#request_type').change(function(){
		var a = $('#request_type').val();
		if (a == 'History and Physical') {
			$('#hippa_request_history_physical_div').show();
			$('.hippa_request_lab_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_op_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_accident_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_other_div').hide().find("input").each(function() {
				$(this).val('');
			});
		}
		if (a == 'Lab, Imaging, Cardiopulmonary Reports') {
			$('.hippa_request_lab_div').show();
			$('#hippa_request_history_physical_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_op_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_accident_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_other_div').hide().find("input").each(function() {
				$(this).val('');
			});
		}
		if (a == 'Operative Reports') {
			$('#hippa_request_op_div').show();
			$('#hippa_request_history_physical_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_lab_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_accident_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_other_div').hide().find("input").each(function() {
				$(this).val('');
			});
		}
		if (a == 'Accident or Injury') {
			$('.hippa_request_accident_div').show();
			$('#hippa_request_history_physical_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_lab_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_op_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_other_div').hide().find("input").each(function() {
				$(this).val('');
			});
		}
		if (a == 'Other') {
			$('#hippa_request_other_div').show();
			$('#hippa_request_history_physical_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_lab_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('#hippa_request_op_div').hide().find("input").each(function() {
				$(this).val('');
			});
			$('.hippa_request_accident_div').hide().find("input").each(function() {
				$(this).val('');
			});
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
			$("#hippa_reason").focus();
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
				url: "ajaxcommon/documents/Laboratory",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_labs").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Imaging",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_radiology").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Cardiopulmonary",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_cardiopulm").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Endoscopy",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_endoscopy").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Referrals",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_referrals").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Past_Records",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_past_records").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Other_Forms",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_outside_forms").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
				url: "ajaxcommon/documents/Letters",
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
				onSelectRow: function(row,iCol){
					var id = $("#print_letters").getCell(row,'documents_id');
					if (iCol > 0) {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
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
						url: "ajaxsearch/all-contacts",
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
				$('#hippa_reason').focus();
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
	$("#print_to_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#print_to_specialty").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/specialty1",
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
			$("#print_to_city").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/city",
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
			$("#print_to_lastname").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#print_to_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#print_to_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-orders-provider/Referral",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#print_to_form").clearForm();
								$("#print_to_dialog").dialog('close');
								$.ajax({
									url: "ajaxsearch/ref-provider1/all",
									dataType: "json",
									type: "POST",
									success: function(data1){
										$("#print_chart_form_provider").html(data1.html);
										$("#hippa_request_provider").html(data1.html1);
										loadbuttons();
										var b = $("#print_to_origin").val();
										if (b == 'hippa') {
											$("#hippa_address_id").val(data.id);
											var a = $("#hippa_address_id").find("option:selected").first().text();
											$("#hippa_provider1").val(a);
										} else {
											$("#hippa_request_address_id").val(data.id);
											var a = $("#hippa_request_address_id").find("option:selected").first().text();
											$("#hippa_request_to").val(a);
										}
										$("#print_to_origin").val('');
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#print_to_form").clearForm();
				$("#print_to_origin").val('');
				$("#print_to_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#print_to_state").addOption(states, false);
	$("#print_to_phone").mask("(999) 999-9999");
	$("#print_to_fax").mask("(999) 999-9999");
	$("#hippa_request_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event,ui) {
			$("#request_reason").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/request-reason",
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
			'Save': function() {
				var bValid = true;
				$("#hippa_request_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				var a = $('#request_type').val();
				if (a == 'History and Physical') {
					$("#hippa_request_history_physical_div").find("input").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
				}
				if (a == 'Lab, Imaging, Cardiopulmonary Reports') {
					$(".hippa_request_lab_div").find("input").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
				}
				if (a == 'Operative Reports') {
					$("#hippa_request_op_div").find("input").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
				}
				if (a == 'Accident or Injury') {
					$('.hippa_request_accident_div').find("input").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
				}
				if (a == 'Other') {
					$('#hippa_request_other_div').find("input").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
				}
				if (bValid) {
					var str = $("#hippa_request_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/records-request-save",
							data: str,
							async: false,
							success: function(data){
								$("#hippa_request_form").clearForm();
								$("#hippa_request_dialog").dialog('close');
								reload_grid("records_request");
								noshdata.success_doc = true;
								noshdata.id_doc = data;
							}
						});
						if (noshdata.success_doc == true) {
							window.open("hippa_request_print/" + noshdata.id_doc);
							noshdata.success_doc = '';
							noshdata.id_doc = '';
						}
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#hippa_request_form").clearForm();
				$("#hippa_request_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
});
