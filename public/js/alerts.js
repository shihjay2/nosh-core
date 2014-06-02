$(document).ready(function() {
	$("#alerts_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#alerts").jqGrid('GridUnload');
			jQuery("#alerts").jqGrid({
				url:"ajaxchart/alerts",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert_description',width:430}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_pager1'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Pending Alerts and Tasks",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_pager1',{search:false,edit:false,add:false,del:false});
			jQuery("#alerts_complete").jqGrid('GridUnload');
			jQuery("#alerts_complete").jqGrid({
				url:"ajaxchart/alerts-complete",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert_description',width:430}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_complete_pager'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Completed Alerts and Tasks",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_complete_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#alerts_not_complete").jqGrid('GridUnload');
			jQuery("#alerts_not_complete").jqGrid({
				url:"ajaxchart/alerts-not-complete",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description','Reason'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:150},
					{name:'alert_description',index:'alert_description',width:280},
					{name:'alert_reason_not_complete',index:'alert_reason_not_complete',width:195}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_not_complete_pager'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Alerts and Tasks Not Completed",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_not_complete_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			menu_update('alerts');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#edit_alert_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_alert_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_alert_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-alert",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid('alerts');
								$('#edit_alert_form').clearForm();
								$('#edit_alert_dialog').dialog('close');
								noshdata.alert_id = '';
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_alert_form').clearForm();
				$('#edit_alert_dialog').dialog('close');
				noshdata.alert_id = '';
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#edit_alert_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#alert").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/alert",
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
			$("#alert_provider").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/users1",
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
					$('#alert_provider_id').val(ui.item.id);
				}
			});
			$("#alert_description").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/alert-description",
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
			$("#alert_reason_not_complete").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/alert-reason-not-complete",
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
				$("#edit_alert_form1").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_alert_form1").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/incomplete-alert",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid('alerts');
								reload_grid('alerts_not_complete');
								var item = $("#alert_id1").val();
								$('#edit_alert_form1').clearForm();
								$('#edit_alert_dialog1').dialog('close');
								noshdata.alert_id = '';
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_alert_form1').clearForm();
				$('#edit_alert_dialog1').dialog('close');
				noshdata.alert_id = '';
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".alerts_list").click(function() {
		$("#alerts_list_dialog").dialog('open');
	});
	$("#alert_date_active").mask("99/99/9999");
	$("#alert_date_active").datepicker();
	$(".add_alert").click(function(){
		$('#edit_alert_form').clearForm();
		var currentDate = getCurrentDate();
		$('#alert_date_active').val(currentDate);
		$('#edit_alert_dialog').dialog('open');
		$("#alert").focus();
	});
	$("#edit_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			jQuery("#alerts").GridToForm(item,"#edit_alert_form");
			var date = $('#alert_date_active').val();
			var edit_date = editDate(date);
			$('#alert_date_active').val(edit_date);
			$('#edit_alert_dialog').dialog('open');
			$("#alert").focus();
		} else {
			$.jGrowl("Please select alert to edit!")
		}
	});
	$("#complete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/complete-alert",
				data: "alert_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid('alerts');
					reload_grid('alerts_complete');
				}
			});
		} else {
			$.jGrowl("Please select alert to mark as complete!")
		}
	});
	$("#incomplete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			$("#alert_id1").val(item);
			$('#edit_alert_dialog1').dialog('open');
		} else {
			$.jGrowl("Please select alert to mark as incomplete!")
		}
	});
	$("#delete_alert").click(function(){
		var item = jQuery("#alerts").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this alert?')){
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-alert",
					data: "alert_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid('alerts');
					}
				});
			}
		} else {
			$.jGrowl("Please select alert to delete!")
		}
	});
	if (noshdata.alert_id != '') {
		$("#alerts_list_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/get-alert/" + noshdata.alert_id,
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#edit_alert_form :input[name='" + key + "']").val(value);
				});
				var date = $('#alert_date_active').val();
				var edit_date = editDate1(date);
				$('#alert_date_active').val(edit_date);
				$('#edit_alert_dialog').dialog('open');
				$("#alert").focus();
				noshdata.alert_id = '';
			}
		});
	}
	$("#alerts_pending_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#alerts_pending").jqGrid('GridUnload');
			jQuery("#alerts_pending").jqGrid({
				url:"ajaxchart/alerts-pending",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description','Orders ID'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:1,hidden:true},
					{name:'alert_description',index:'alert_description',width:630},
					{name:'orders_id',index:'orders_id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#alerts_pending_pager'),
				sortname: 'alert_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Pending Orders",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#alerts_pending_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#past_orders_lab").jqGrid('GridUnload');
			jQuery("#past_orders_lab").jqGrid({
				url: "ajaxchart/orders-list/labs",
				postData: {t_messages_id:'all'},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Obtained','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_labs',index:'orders_labs',width:430},
					{name:'orders_labs_icd',index:'orders_labs_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_labs_obtained',index:'orders_labs_obtained',width:1,hidden:true},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true},
					{name:'orders_pending_date',index:'orders_pending_date',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#past_orders_lab_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Past Lab Orders - Click on Tests column to resend the order.",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" },
			 	onCellSelect: function(rowid,iCol,cellcontent,e){
			 		if (iCol == 1) {
						$("#messages_lab_orders_id").val(rowid);
						$('#messages_lab_choice').html("Choose an action for the lab order, reference number " + rowid);
						$("#messages_lab_action_dialog").dialog('open');
			 		}
				}
			}).navGrid('#past_orders_lab_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#past_orders_rad").jqGrid('GridUnload');
			jQuery("#past_orders_rad").jqGrid({
				url: "ajaxchart/orders-list/radiology",
				postData: {t_messages_id:'all'},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Obtained','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_radiology',index:'orders_radiology',width:430},
					{name:'orders_radiology_icd',index:'orders_radiology_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_labs_obtained',index:'orders_labs_obtained',width:1,hidden:true},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true},
					{name:'orders_pending_date',index:'orders_pending_date',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#past_orders_rad_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Past Imaging Orders - Click on Tests column to resend the order.",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" },
			 	onCellSelect: function(rowid,iCol,cellcontent,e){
			 		if (iCol == 1) {
						$("#messages_rad_orders_id").val(rowid);
						$('#messages_rad_choice').html("Choose an action for the radiology order, reference number " + rowid);
						$("#messages_rad_action_dialog").dialog('open');
			 		}
				}
			}).navGrid('#past_orders_rad_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#past_orders_cp").jqGrid('GridUnload');
			jQuery("#past_orders_cp").jqGrid({
				url: "ajaxchart/orders-list/cp",
				postData: {t_messages_id:'all'},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Obtained','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_cp',index:'orders_cp',width:430},
					{name:'orders_cp_icd',index:'orders_cp_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_labs_obtained',index:'orders_labs_obtained',width:1,hidden:true},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true},
					{name:'orders_pending_date',index:'orders_pending_date',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#past_orders_cp_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Past Cardiopulmonary Orders - Click on Tests column to resend the order.",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" },
			 	onCellSelect: function(rowid,iCol,cellcontent,e){
			 		if (iCol == 1) {
						$("#messages_cp_orders_id").val(rowid);
						$('#messages_cp_choice').html("Choose an action for the cardiopulmonary order, reference number " + rowid);
						$("#messages_cp_action_dialog").dialog('open');
			 		}
				}
			}).navGrid('#past_orders_cp_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			menu_update('alerts');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#order_list").click(function(){
		$("#process_pending_order").hide();
		$("#pending_create_encounter").show();
		$("#alerts_pending_dialog").dialog('open');
	});
	$("#process_pending_order").click(function(){
		var item = jQuery("#alerts_pending").getGridParam('selrow');
		noshdata.pending_orders_id = jQuery("#alerts_pending").getCell(item, 4);
		pending_order_load(noshdata.pending_orders_id);
	});
	$("#pending_create_encounter").click(function(){
		var item = jQuery("#alerts_pending").getGridParam('selrow');
		if (item) {
			noshdata.pending_orders_id = jQuery("#alerts_pending").getCell(item, 4);
			$("#detail_encounter_number").html("");
			$("#encounter_template").val('clinicalsupport');
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
			$("#alerts_pending_dialog").dialog('close');
			$("#new_encounter_dialog").dialog('open');
		} else {
			$.jGrowl('Select an order!');
		}
	});
});
