$(document).ready(function() {
	function importreply() {
		var old = $("#t_messages_message").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old + '\n';
				} else {
					var old1 = old + '\n\n';
				}
			}
		} else {
			var old1 = '';
		}
		var a = $("#message_reply_tests_performed").val();
		var b = $("#message_reply_message").val();
		var c = $("#message_reply_followup").val();
		if(c){
			var c1 = 'Followup recommendations:  ' + c;
			if(b){
				var b1 = 'Conclusion:  ' + b + '\n\n';
				if(a != ""){
					var a1 = 'The following tests were performed: ' + a + '\n\n';
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;
				} else {
					var a1 = '';
				}
			}
		} else {
			var c1 = '';
			if(b){
				var b1 = 'Conclusion:  ' + b;
				if(a){
					var a1 = 'The following tests were performed: ' + a + '\n\n';
				} else {
					var a1 = '';
				}	
			} else {
				var b1 = '';
				if(a){
					var a1 = 'The following tests were performed: ' + a;
				} else {
					var a1 = '';
				}
			}
		}
		$("#t_messages_message").val(old1+a1+b1+c1);
		var response = a1+b1+c1;
		return response;
	}
	$("#messages_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#messages").jqGrid('GridUnload');
			jQuery("#messages").jqGrid({
				url:"ajaxchart/messages",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date of Service','Subject','Message','Provider','Signed','To'],
				colModel:[
					{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
					{name:'t_messages_dos',index:'t_messages_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'t_messages_subject',index:'t_messages_subject',width:425},
					{name:'t_messages_message',index:'t_messages_message',width:1,hidden:true},
					{name:'t_messages_provider',index:'t_messages_provider',width:100},
					{name:'t_messages_signed',index:'t_messages_signed',width:100},
					{name:'t_messages_to',index:'t_messages_to',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_pager'),
				sortname: 't_messages_dos',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Messages",
			 	height: "100%",
			 	onSelectRow: function(id) {
			 		var item = jQuery("#messages").getGridParam('selrow');
		 			var signed = jQuery("#messages").getCell(id,'t_messages_signed');
		 			if (signed == 'No') {
						jQuery("#messages").GridToForm(id,"#edit_message_form");
						var date = $('#t_messages_dos').val();
						var edit_date = editDate(date);
						$('#t_messages_dos').val(edit_date);
						t_messages_tags();
						$("#messages_main_dialog").dialog('open');
					}
					if (signed == 'Yes') {
						$("#edit_message_fieldset").hide('fast');
						var row = jQuery("#messages").getRowData(id);
						var text = '<br><strong>Date:</strong>  ' + row['t_messages_dos'] + '<br><br><strong>Subject:</strong>  ' + row['t_messages_subject'] + '<br><br><strong>Message:</strong> ' + row['t_messages_message']; 
						$("#message_view").html(text);
						$("#t_messages_id").val(row['t_messages_id']);
						t_messages_tags();
						$("#messages_view_dialog").dialog('open');
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_telephone_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Import': function() {
				var old = $("#t_messages_message").val();
				if(old){
					var pos = old.lastIndexOf('\n');
					if (pos == -1) {
						var old1 = old + '\n\n';
					} else {
						var a = old.slice(pos);
						if (a == '') {
							var old1 = old + '\n';
						} else {
							var old1 = old + '\n\n';
						}
					}
				} else {
					var old1 = '';
				}
				var a = $("#message_subjective").val();
				var b = $("#message_assessment").val();
				var c = $("#message_plan").val();
				if(c){
					var c1 = 'PLAN:  ' + c;
					if(b){
						var b1 = 'ASSESSMENT:  ' + b + '\n\n';
						if(a != ""){
							var a1 = 'SUBJECTIVE:  ' + a + '\n\n';
						} else {
							var a1 = '';
						}	
					} else {
						var b1 = '';
						if(a){
							var a1 = 'SUBJECTIVE:  ' + a;
						} else {
							var a1 = '';
						}
					}
				} else {
					var c1 = '';
					if(b){
						var b1 = 'ASSESSMENT:  ' + b;
						if(a){
							var a1 = 'SUBJECTIVE:  ' + a + '\n\n';
						} else {
							var a1 = '';
						}	
					} else {
						var b1 = '';
						if(a){
							var a1 = 'SUBJECTIVE:  ' + a;
						} else {
							var a1 = '';
						}
					}
				}
				$("#t_messages_message").val(old1+a1+b1+c1);
				$('#edit_message_telephone_form').clearForm();
				$("#messages_telephone_dialog").dialog('close');
			},
			Cancel: function() {
				$('#edit_message_telephone_form').clearForm();
				$("#messages_telephone_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_reply_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#message_reply_tests_performed").focus();
			jQuery("#messages_reply_alerts").jqGrid('GridUnload');
			jQuery("#messages_reply_alerts").jqGrid({
				url:"ajaxchart/alerts1",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Due Date','Alert','Description'],
				colModel:[
					{name:'alert_id',index:'alert_id',width:1,hidden:true},
					{name:'alert_date_active',index:'alert_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'alert',index:'alert',width:200},
					{name:'alert_description',index:'alert',width:400}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_reply_alerts_pager1'),
				sortname: 'alert_date_active',
				viewrecords: true,
				sortorder: "desc",
				caption:"Pending Orders",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_reply_alerts_pager1',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_message_reply_form').clearForm();
		},
		buttons: {
			'Import Only': function() {
				importreply();
				$('#edit_message_reply_form').clearForm();
				$("#messages_reply_dialog").dialog('close');
			},
			'E-mail/Patient Portal Message': function() {
				var response = importreply();
				var body = encodeURIComponent(response);
				$.ajax({
					type: "POST",
					url: "ajaxchart/internal-message-reply",
					data: "body=" + body,
					success: function(data){
						$.jGrowl(data);
						$('#edit_message_reply_form').clearForm();
						$("#messages_reply_dialog").dialog('close');
					}
				});
			},
			'Send Letter': function() {
				var response = importreply();
				var body = encodeURIComponent(response);
				$.ajax({
					type: "POST",
					url: "ajaxchart/letter-reply",
					data: "body=" + body,
					dataType: "json",
					async: false,
					success: function(data){
						if (data.message == 'OK') {
							noshdata.success_doc = true;
							noshdata.id_doc = data.id;
						} else {
							$.jGrowl(data.message);
						}
					}
				});
				if (noshdata.success_doc == true) {
					window.open("view_documents/" + noshdata.id_doc);
					noshdata.success_doc = false;
					noshdata.id_doc = '';
				}
				$('#edit_message_reply_form').clearForm();
				$("#messages_reply_dialog").dialog('close');
			},
			Cancel: function() {
				$('#edit_message_reply_form').clearForm();
				$("#messages_reply_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_list").click(function() {
		$("#messages_list_dialog").dialog('open');
	});
	$(".new_telephone_message").click(function() {
		$("#edit_message_form").clearForm();
		$.ajax({
			url: "ajaxchart/new-message",
			dataType: "json",
			type: "POST",
			success: function(data){
				$("#t_messages_id").val(data);
				var currentDate = getCurrentDate();
				$("#t_messages_dos").val(currentDate);
				reload_grid("messages");
				t_messages_tags();
				$("#messages_main_dialog").dialog('open');
			}
		});
	});
	$("#t_messages_dos").mask("99/99/9999");
	$("#t_messages_dos").datepicker();
	$("#message_telephone").click(function() {
		$("#messages_telephone_dialog").dialog('open');
		$("#message_subjective").focus();
	});
	$("#message_rx").click(function() {
		$("#orders_rx_header").hide();
		$("#messages_rx_header").show();
		$("#messages_rx_main").show();
		$("#messages_rx_dialog").dialog('open');
	});
	$("#message_sup").click(function() {
		$("#supplement_origin_orders").val("Y");
		$("#supplement_origin_orders1").val("N");
		$("#supplements_list_dialog").dialog('open');
		$("#messages_supplements_header").show();
		$("#orders_supplements").focus();
	});
	$("#message_lab").click(function() {
		$("#save_lab_helper_label").html('Import to Message');
		$("#messages_lab_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_lab_t_messages_id_origin").val(id);
		$("#messages_lab_header").show();
		$("#messages_lab_dialog").dialog('open');
	});
	$("#message_rad").click(function() {
		$("#save_rad_helper_label").html('Import to Message');
		$("#messages_rad_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_rad_t_messages_id_origin").val(id);
		$("#messages_rad_header").show();
		$("#messages_rad_dialog").dialog('open');
	});
	$("#message_cp").click(function() {
		$("#save_cp_helper_label").html('Import to Message');
		$("#messages_cp_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_cp_t_messages_id_origin").val(id);
		$("#messages_cp_header").show();
		$("#messages_cp_dialog").dialog('open');
	});
	$("#message_ref").click(function() {
		$("#save_ref_helper_label").html('Import to Message');
		$("#messages_ref_origin").val('message');
		var id = $("#t_messages_id").val();
		$("#messages_ref_t_messages_id_origin").val(id);
		$("#messages_ref_header").show();
		$("#messages_ref_dialog").dialog('open');
	});
	$("#message_reply").click(function() {
		$("#messages_reply_dialog").dialog('open');
	});
	$("#complete_message_reply_alert").click(function(){
		var item = jQuery("#messages_reply_alerts").getGridParam('selrow');
		var row = jQuery("#messages_reply_alerts").getRowData(item);
		var test = row['alert_description'];
		var test1 = test.split(":");
		var test2 = $.trim(test1[1]);
		var old = $("#message_reply_tests_performed").val();
		if (old == '') {
			$("#message_reply_tests_performed").val(test2);
		} else {
			$("#message_reply_tests_performed").val(old + '\n' + test2);
		}
		if(item){
			var id = $("#messages_reply_alerts").getCell(item,'alert_id');
			$.ajax({
				type: "POST",
				url: "ajaxchart/complete-alert",
				data: "alert_id=" + id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("messages_reply_alerts");
					reload_grid("alerts");
					reload_grid("alerts_complete");
				}
			});
		} else {
			$.jGrowl("Please select order to mark as complete!")
		}
	});
	$("#messages_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#t_messages_subject").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/subject",
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
			$("#t_messages_to").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/users",
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
			if (noshdata.group_id == "4") {
				$(this).siblings('.ui-dialog-buttonpane').find('button').eq(1).hide();
			} else {
				$(this).siblings('.ui-dialog-buttonpane').find('button').eq(1).show();
			}
		},
		buttons: {
			'Save Draft': function() {
				var bValid = true;
				$("#edit_message_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_message_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-message",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("messages");
								$("#edit_message_form").clearForm();
								$("#messages_main_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			'Sign': function() {
				var bValid = true;
				$("#edit_message_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_message_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/sign-message",
							data: str,
							success: function(data){
								if(data) {
									$.jGrowl(data);
									reload_grid("messages");
									$("#edit_message_form").clearForm();
									$("#messages_main_dialog").dialog('close');
								} else {
									$.jGrowl(data);
								}
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_message_form').clearForm();
				$("#messages_main_dialog").dialog('close');
			},
			'Delete': function() {
				var str = $("#t_messages_id").val();
				if(str != ''){
					$.ajax({
						type: "POST",
						url: "ajaxchart/delete-message",
						data: "t_messages_id=" + str,
						success: function(data){
							if(data) {
								$.jGrowl(data);
								reload_grid("messages");
								$("#edit_message_form").clearForm();
								$("#messages_main_dialog").dialog('close');
							} else {
								$.jGrowl(data);
							}
						}
					});
				} else {
					$.jGrowl("No message to delete!  Message has not been saved previously!");
				}
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".t_messages_tags").tagit({
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
					url: "ajaxsearch/save-tag/t_messages_id/" + $("#t_messages_id").val(),
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/remove-tag/t_messages_id/" + $("#t_messages_id").val(),
					data: 'tag=' + a
				});
			}
		}
	});
	if (noshdata.t_messages_id != '') {
		$("#messages_list_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/get-draft-message/" + noshdata.t_messages_id,
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#edit_message_form :input[name='" + key + "']").val(value);
				});
				var date = $('#t_messages_dos').val();
				var edit_date = editDate1(date);
				$('#t_messages_dos').val(edit_date);
				t_messages_tags();
				$("#messages_main_dialog").dialog('open');
				noshdata.t_messages_id = '';
			}
		});
	}
	$("#t_message_test_results").click(function() {
		$("#encounter_copy_result").hide();
		$("#t_message_copy_result").show();
		$("#tests_dialog").dialog('open');
	});
});
