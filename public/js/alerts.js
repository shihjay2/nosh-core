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
					{name:'alert_description',index:'alert',width:430}
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
					{name:'alert_description',index:'alert',width:430}
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
					{name:'alert_description',index:'alert',width:280},
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
	$("#add_alert").click(function(){
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
});
