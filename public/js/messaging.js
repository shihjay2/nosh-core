$(document).ready(function() {
	function mail_status(cellvalue, options, rowObject){
		if (cellvalue == "y") {
			return "<span class='ui-icon ui-icon-mail-open'></span>";
		} else {
			return "<span class='ui-icon ui-icon-mail-closed'></span>";
		}
	}
	function statusfn (cellvalue, options, rowObject){
		if (cellvalue == '1') {
			return 'Sent';
		} else {
			return 'Not Sent';
		}
	}
	function loadfaxjob() {
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/sendfinal",
			dataType: "json",
			success: function(data){
				if (data.task == "Draft") {
					$("#faxsubject").val(data.faxsubject);
					if (data.faxcoverpage == 'yes') {
						$("#faxcoverpage").prop('checked', true);
						$(".formmessagecoverpage").show();
						$("#faxmessage").val(data.faxmessage);
					} else {
						$("#faxcoverpage").prop('checked', false);
						$(".formmessagecoverpage").hide();
						$("#faxmessage").val('');
					}
				} else {
					$("#sendfinal").clearForm();
				}
				$.jGrowl(data.message);
			}
		});
	}
	$("#nosh_messaging").click(function() {
		$("#messaging_dialog").dialog('open');
	});
	$("#dashboard_messaging").click(function() {
		$("#messaging_dialog").dialog('open');
	});
	$("#dashboard_documents").click(function() {
		$("#messaging_dialog").dialog('open');
		$("#messaging_accordion").accordion({active: 1});
	});
	$("#messaging_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#messaging_accordion").accordion({ heightStyle: "content" });
			jQuery("#internal_inbox").jqGrid('GridUnload');
			jQuery("#internal_inbox").jqGrid({
				url:"ajaxmessaging/internal-inbox",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','To','','Date','FromID','From','Subject','Message','CC','PID','Patient Name','Body Text','Telephone Messages ID','Document ID'],
				colModel:[
					{name:'message_id',index:'message_id',width:1,hidden:true},
					{name:'message_to',index:'message_to',width:1,hidden:true},
					{name:'read',index:'read',width:15,formatter:mail_status},
					{name:'date',index:'date',width:120},
					{name:'message_from',index:'message_from',width:1,hidden:true},
					{name:'displayname',index:'displayname',width:175},
					{name:'subject',index:'subject',width:240},
					{name:'body',index:'body',width:1,hidden:true},
					{name:'cc',index:'cc',width:1,hidden:true},
					{name:'pid',index:'pid',width:1,hidden:true},
					{name:'patient_name',index:'patient_name',width:1,hidden:true},
					{name:'bodytext',index:'bodytext',width:1,hidden:true},
					{name:'t_messages_id',index:'t_messages_id',width:1,hidden:true},
					{name:'documents_id',index:'documents_id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#internal_inbox_pager'),
				sortname: 'date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Inbox",
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
						var row = jQuery("#internal_inbox").getRowData(id);
						var text = '<br><strong>From:</strong> ' + row['displayname'] + '<br><br><strong>Date:</strong> ' + row['date'] + '<br><br><strong>Subject:</strong> ' + row['subject'] + '<br><br><strong>Message:</strong> ' + row['bodytext']; 
						var rawtext = 'From:  ' + row['displayname'] + '\nDate: ' + row['date'] + '\nSubject: ' + row['subject'] + '\n\nMessage: ' + row['body']; 
						$("#message_view1").html(text);
						$("#message_view_rawtext").val(rawtext);
						$("#message_view_message_id").val(id);
						$("#message_view_from").val(row['message_from']);
						$("#message_view_to").val(row['message_to']);
						$("#message_view_cc").val(row['cc']);
						$("#message_view_subject").val(row['subject']);
						$("#message_view_body").val(row['body']);
						$("#message_view_date").val(row['date']);
						$("#message_view_pid").val(row['pid']);
						$("#message_view_patient_name").val(row['patient_name']);
						$("#message_view_t_messages_id").val(row['t_messages_id']);
						$("#message_view_documents_id").val(row['documents_id']);
						messages_tags();
						if (row['pid'] == '' || row['pid'] == "0") {
							$("#export_message").hide();
						} else {
							$("#export_message").show();
						}
						$("#internal_messages_view_dialog").dialog('open');
						setTimeout(function() {
							var a = $("#internal_messages_view_dialog" ).dialog("isOpen");
							if (a) {
								var id = $("#message_view_message_id").val();
								var documents_id = $("#message_view_documents_id").val();
								if (documents_id == '') {
									documents_id = '0';
								}
								$.ajax({
									type: "POST",
									url: "ajaxmessaging/read-message/" + id + "/" + documents_id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("internal_inbox");
									}
								});
							}
						}, 3000);
					}
				}
			}).navGrid('#internal_inbox_pager',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#internal_inbox_pager',{
				caption:"Delete Message", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#internal_inbox").getGridParam('selarrrow');
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxmessaging/delete-message",
								data: "message_id=" + id[i],
								success: function(data){
									$.jGrowl(data);
								}
							});
						}
						reload_grid("internal_inbox");
					} else {
						$.jGrowl('Choose message(s) to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#internal_draft").jqGrid('GridUnload');
			jQuery("#internal_draft").jqGrid({
				url:"ajaxmessaging/internal-draft",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','To','CC','Subject','Message','PID','Patient Name'],
				colModel:[
					{name:'message_id',index:'message_id',width:1,hidden:true},
					{name:'date',index:'date',width:120},
					{name:'message_to',index:'message_to',width:90},
					{name:'cc',index:'cc',width:90},
					{name:'subject',index:'subject',width:250},
					{name:'body',index:'body',width:1,hidden:true},
					{name:'pid',index:'pid',width:1,hidden:true},
					{name:'patient_name',index:'patient_name',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#internal_draft_pager'),
				sortname: 'date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Drafts",
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				hiddengrid: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
						jQuery("#internal_draft").GridToForm(id,"#internal_messages_form_id");
						$("#internal_messages_dialog").dialog('open');
						messages_tags();
						$("#messages_subject").focus();
					}
				}
			}).navGrid('#internal_draft_pager',{search:false,edit:false,add:false,del:false
			}).navButtonAdd('#internal_draft_pager',{
				caption:"Delete Message", 
				buttonicon:"ui-icon-trash", 
				onClickButton: function(){ 
					var id = jQuery("#internal_draft").getGridParam('selarrrow');
					if(id){
						var count = id.length;
						for (var i = 0; i < count; i++) {
							$.ajax({
								type: "POST",
								url: "ajaxmessaging/delete-message",
								data: "message_id=" + id[i],
								success: function(data){
									$.jGrowl(data);
								}
							});
						}
						reload_grid("internal_draft");
					} else {
						$.jGrowl('Choose message(s) to delete!');
					}
				}, 
				position:"last"
			});
			jQuery("#internal_outbox").jqGrid('GridUnload');
			jQuery("#internal_outbox").jqGrid({
				url:"ajaxmessaging/internal-outbox",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','To','CC','Subject','PID','Message'],
				colModel:[
					{name:'message_id',index:'message_id',width:1,hidden:true},
					{name:'date',index:'date',width:120},
					{name:'message_to',index:'message_to',width:90},
					{name:'cc',index:'cc',width:90},
					{name:'subject',index:'subject',width:250},
					{name:'pid',index:'pid',width:1,hidden:true},
					{name:'body',index:'body',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#internal_outbox_pager'),
				sortname: 'date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Sent Messages",
				height: "100%",
				multiselect: true,
				multiboxonly: true,
				hiddengrid: true,
				onCellSelect: function(id,iCol) {
					if (iCol > 0) {
						var row = jQuery("#internal_outbox").getRowData(id);
						var text = '<br><strong>To:</strong>  ' + row['message_to'] + '<br><strong>CC:</strong> ' + row['cc'] + '<br<br><strong>Date:</strong>  ' + row['date'] + '<br><br><strong>Subject:</strong>  ' + row['subject'] + '<br><br><strong>Message:</strong> ' + row['body']; 
						$("#message_view2").html(text);
						$("#message_view_message_id").val(id);
						$("#message_view_subject1").val(row['subject']);
						$("#message_view_body1").val(row['body']);
						$("#message_view_date1").val(row['date']);
						$("#message_view_pid1").val(row['pid']);
						messages_tags();
						if (row['pid'] == '' || row['pid'] == "0") {
							$("#export_message1").hide();
						} else {
							$("#export_message1").show();
						}
						$("#internal_messages_view2_dialog").dialog('open');
					}
				}
			}).navGrid('#internal_outbox_pager',{search:false,edit:false,add:false,del:false});
			if (noshdata.group_id != '100') {
				jQuery("#received_faxes").jqGrid('GridUnload');
				jQuery("#received_faxes").jqGrid({
					url:"ajaxmessaging/receive-fax",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Date','Pages','From','FileName','FaxPath'],
					colModel:[
						{name:'received_id',index:'received_id',width:1,hidden:true},
						{name:'fileDateTime',index:'fileDate',width:150},
						{name:'filePages',index:'filePages',width:50},
						{name:'fileFrom',index:'fileFrom',width:350},
						{name:'fileName',index:'fileName',width:1,hidden:true},
						{name:'filePath',index:'filePath',width:1,hidden:true}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#received_faxes_pager'),
					sortname: 'fileDateTime',
					viewrecords: true,
					sortorder: "desc",
					height: "100%",
					caption:"Received Faxes",
					onSelectRow: function(id){
						$("#view_received_id").val(id);
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/view-fax/" + id,
							dataType: "json",
							success: function(data){
								$("#embedURL1").html(data.html);
								$("#fax_filepath").val(data.filepath);
								$("#fax_view_dialog").dialog('open');
							}
						});
					},
					emptyrecords:"No faxes received.",
					jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#received_faxes_pager',{search:false,edit:false,add:false,del:false});
				jQuery("#draft_faxes").jqGrid('GridUnload');
				jQuery("#draft_faxes").jqGrid({
					url:"ajaxmessaging/drafts-list",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Fax Subject'],
					colModel:[
						{name:'job_id',index:'job_id',width:100},
						{name:'faxsubject',index:'faxsubject',width:455}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#draft_faxes_pager'),
					sortname: 'job_id',
					viewrecords: true,
					sortorder: "desc",
					caption:"Draft Faxes",
					height:"100%",
					emptyrecords:"No drafts",
					hiddengrid: true,
					onSelectRow: function(id){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/set-id",
							data: "job_id=" + id,
							success: function(data){
								loadfaxjob();
								$("#messaging_fax_dialog").dialog('open');
							}
						});
					},
					jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#draft_faxes_pager',{search:false,edit:false,add:false,del:false});
				jQuery("#sent_faxes").jqGrid('GridUnload');
				jQuery("#sent_faxes").jqGrid({
					url:"ajaxmessaging/sent-list",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Sent Date','Fax Subject','Status'],
					colModel:[
						{name:'job_id',index:'job_id',width:50},
						{name:'sentdate',index:'sentdate',width:100},
						{name:'faxsubject',index:'faxsubject',width:295},
						{name:'success',index:'success',width:100,formatter:statusfn}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#sent_faxes_pager'),
					sortname: 'job_id',
					viewrecords: true,
					sortorder: "desc",
					height: "100%",
					caption:"Sent Faxes",
					hiddengrid: true,
					onSelectRow: function(id){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/set-id",
							data: "job_id=" + id,
							success: function(data){
								loadfaxjob();
								$("#messaging_fax_dialog").dialog('open');
							}
						});
					},
					emptyrecords:"No sent faxes.",
					jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#sent_faxes_pager',{search:false,edit:false,add:false,del:false});
				jQuery("#received_scans").jqGrid('GridUnload');
				jQuery("#received_scans").jqGrid({
					url:"ajaxmessaging/scans",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Date','Pages','File Name','FaxPath'],
					colModel:[
						{name:'scans_id',index:'scans_id',width:1,hidden:true},
						{name:'fileDateTime',index:'fileDate',width:150},
						{name:'filePages',index:'filePages',width:50},
						{name:'fileName',index:'fileName',width:350},
						{name:'filePath',index:'filePath',width:1,hidden:true}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#received_scans_pager'),
					sortname: 'fileDateTime',
					viewrecords: true,
					sortorder: "desc",
					height: "100%",
					caption:"Scanned Documents",
					onCellSelect: function(id,iCol){
						if (iCol > 0) {
							$("#view_scans_id").val(id);
							$.ajax({
								type: "POST",
								url: "ajaxmessaging/view-scan/" + id,
								dataType: "json",
								success: function(data){
									$("#embedURL3").html(data.html);
									$("#scan_filepath").val(data.filepath);
									$("#scan_view_dialog").dialog('open');
								}
							});
						}
					},
					emptyrecords:"No scanned documents.",
					multiselect: true,
					multiboxonly: true,
					jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#received_scans_pager',{search:false,edit:false,add:false,del:false});
				jQuery("#all_contacts_list").jqGrid('GridUnload');
				jQuery("#all_contacts_list").jqGrid({
					url:"ajaxmessaging/all-contacts",
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Name','Specialty','Last Name','First Name','Prefix','Suffix','Facility','Street Address 1','Street Address 2','City','State','Zip','Phone','Fax','Email','Comments','NPI'],
					colModel:[
						{name:'address_id',index:'address_id',width:1,hidden:true},
						{name:'displayname',index:'displayname',width:150},
						{name:'specialty',index:'specialty',width:125},
						{name:'lastname',index:'lastname',width:1,hidden:true},
						{name:'firstname',index:'firstname',width:1,hidden:true},
						{name:'prefix',index:'prefix',width:1,hidden:true},
						{name:'suffix',index:'suffix',width:1,hidden:true},
						{name:'facility',index:'facility',width:1,hidden:true},
						{name:'street_address1',index:'street_address1',width:125},
						{name:'street_address2',index:'street_address2',width:1,hidden:true},
						{name:'city',index:'city',width: 75},
						{name:'state',index:'state',width:25},
						{name:'zip',index:'zip',width:1,hidden:true},
						{name:'phone',index:'phone',width:75},
						{name:'fax',index:'fax',width:75},
						{name:'email',index:'email',width:1,hidden:true},
						{name:'comments',index:'comments',width:1,hidden:true},
						{name:'npi',index:'npi',width:1,hidden:true}
					],
					rowNum:10,
					rowList:[10,20,30],
					pager: jQuery('#all_contacts_list_pager'),
					sortname: 'displayname',
					viewrecords: true,
					sortorder: "asc",
					height: "100%",
					caption:"Address Book",
					emptyrecords:"No contacts.",
					jsonReader: { repeatitems : false, id: "0" }
				}).navGrid('#all_contacts_list_pager',{search:false,edit:false,add:false,del:false});
			}
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$.ajax({
		type: "POST",
		url: "ajaxsearch/all-users2",
		dataType: "json",
		success: function(data){
			$("#messages_to").addOption(data, false).trigger("liszt:updated");
			$("#messages_cc").addOption(data, false).trigger("liszt:updated");
		}
	});
	$("#new_internal_message").click(function(){
		$("#internal_messages_form").clearForm();
		$("#internal_messages_form_id").show();
		$("#message_view_wrapper").hide();
 		$("#message_view_wrapper2").hide();
 		$("#messages_tags").hide();
 		$("#internal_messages_dialog").dialog('open');
		$("#messages_subject").focus();
	});
	$("#internal_messages_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Send': function() {
				var bValid = true;
				$("#internal_messages_form_id").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#internal_messages_form_id").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/send-message",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#internal_messages_form_id").clearForm();
								$("#messages_to").trigger("liszt:updated");
								$("#messages_cc").trigger("liszt:updated");
								$("#internal_messages_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			'Draft': function() {
				var str = $("#internal_messages_form_id").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/draft-message",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#internal_messages_form_id").clearForm();
							$("#messages_to").trigger("liszt:updated");
							$("#messages_cc").trigger("liszt:updated");
							$("#internal_messages_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				var message_id = $("#messages_message_id").val();
				if (message_id == '') {
					$("#internal_messages_form_id").clearForm();
					$("#internal_messages_dialog").dialog('close');
				} else {
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/delete-message",
						data: "message_id=" + message_id,
						success: function(data){
							$.jGrowl(data);
							$("#internal_messages_form_id").clearForm();
							$("#internal_messages_dialog").dialog('close');
						}
					});
				}
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#internal_messages_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#internal_messages_view2_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_to").chosen();
	$("#messages_cc").chosen();
	$("#messages_patient").autocomplete({
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
			$("#messages_pid").val(ui.item.id);
			$("messages_to").focus();
		}
	});
	$("#create_patient_message").click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxsearch/patient-is-user",
			dataType: 'json',
			success: function(data){
				if (data.message == 'yes') {
					$("#internal_messages_form").clearForm();
					$("#messages_to").val(data.messages_to);
					$("#messages_patient").val(data.messages_patient);
					$("#messages_pid").val(data.pid);
					$("#internal_messages_dialog").dialog('open');
					$("#messages_subject").focus();
				} else {
					$.jGrowl("Patient is not a portal user.  Register the patient so that you can send a secure direct message to the patient.");
				}
			}
		});
	});
	$("#reply_message").click(function(){
		var to = $("#message_view_from").val();
		$("#messages_to_hidden").val(to);
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/get-displayname",
			data: "id=" + to,
			success: function(data){
				$("#messages_to").val(data);
				$("#messages_to").trigger("liszt:updated");
			}
		});
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/get-displayname",
			data: "id=" + from,
			success: function(data){
				var date = $("#message_view_date").val();
				var body = $("#message_view_body").val();
				var newbody = '\n\n' + 'On ' + date + ', ' + data + ' wrote:\n---------------------------------\n' + body;
				$("#messages_body").val(newbody).caret(0);
			}
		});
		var subject = 'Re: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		var pid = $("#message_view_pid").val();
		var patient_name = $("#message_view_patient_name").val();
		var t_messages_id = $("#message_view_t_messages_id").val();
		$("#messages_pid").val(pid);
		$("#messages_patient").val(patient_name);
		$("#messages_t_messages_id").val(t_messages_id);
		$("#internal_messages_view_dialog").dialog('close');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_body").focus();
	});
	$("#reply_all_message").click(function(){
		var to = $("#message_view_from").val();
		var cc = $("#message_view_cc").val();
		$("#messages_to_hidden").val(to);
		$("#messages_cc_hidden").val(cc);
		if (cc == ''){
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/get-displayname",
				data: "id=" + to,
				success: function(data){
					$("#messages_to").val(data);
					$("#messages_to").trigger("liszt:updated");
				}
			});
		} else {
			var to1 = to + ';' + cc;
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/get-displayname1",
				data: "id=" + to1,
				success: function(data){
					var a_array = String(data).split(";");
					var a_length = a_array.length;
					for (var i = 0; i < a_length; i++) {
						$("#messages_to").selectOptions(a_array[i]);
					}
					$("#messages_to").trigger("liszt:updated");
				}
			});
		}
		var from = $("#message_view_from").val();
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/get-displayname",
			data: "id=" + from,
			success: function(data){
				var date = $("#message_view_date").val();
				var body = $("#message_view_body").val();
				var newbody = '\n\n' + 'On ' + date + ', ' + data + ' wrote:\n---------------------------------\n' + body;
				$("#messages_body").val(newbody).caret(0);
			}
		});
		var subject = 'Re: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		var pid = $("#message_view_pid").val();
		var patient_name = $("#message_view_patient_name").val();
		$("#messages_pid").val(pid);
		$("#messages_patient").val(patient_name);
		$("#internal_messages_view_dialog").dialog('close');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_body").focus();
	});
	$("#forward_message").click(function(){
		var rawtext = $("#message_view_rawtext").val();
		var newbody = '\n\n--------Forwarded Message--------\n' + rawtext;
		$("#messages_body").val(newbody);
		var subject = 'Fwd: ' + $("#message_view_subject").val();
		$("#messages_subject").val(subject);
		$("#internal_messages_view_dialog").dialog('open');
		$("#internal_messages_dialog").dialog('open');
		$("#messages_to").focus();
	});
	$("#internal_open_chart").click(function(){
		var pid = $("#message_view_pid").val();
		console.log(pid);
		if(pid){
			var oldpt = noshdata.pid;
			if(!oldpt){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/openchart",
					dataType: "json",
					data: "pid=" + pid,
					success: function(data){
						window.location = data.url;
					}
				});
			} else {
				if(pid == oldpt){
					$.jGrowl("Patient chart already open!");
				} else {
					$.ajax({
						type: "POST",
						url: "ajaxsearch/openchart",
						dataType: "json",
						data: "pid=" + pid,
						success: function(data){
							window.location = data.url;
						}
					});
				}
			}
		} else {
			$.jGrowl("Please enter patient to open chart!");
		}
	});
	$("#export_message").click(function(){
		var pid = $("#message_view_pid").val();
		var str = $("#internal_messages_view_form").serialize();
		if(pid){
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/export-message",
				data: str,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("No patient is associated with this message!");
		}
	});
	$("#export_message1").click(function(){
		var pid = $("#message_view_pid1").val();
		var str = $("#internal_messages_view2_form").serialize();
		if(pid){
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/export-message",
				data: str,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("No patient is associated with this message!");
		}
	});
	$("#messaging_fax_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#messaging_fax_accordion").accordion({ heightStyle: "content" });
			jQuery("#send_list").jqGrid('GridUnload');
			jQuery("#send_list").jqGrid({
				url: "ajaxmessaging/send-list",
				editurl: "ajaxmessaging/edit-send-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Recipient','Fax Number'],
				colModel:[
					{name:'sendlist_id',index:'sendlist_id',width:1,hidden:true},
					{name:'faxrecipient',index:'faxrecipient',width:300,editable:true},
					{name:'faxnumber',index:'faxnumber',width:100,editable:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#send_list_pager'),
				sortname: 'faxrecipient',
				viewrecords: true,
				sortorder: "desc",
				caption:"Fax Recipients",
				emptyrecords:"No recipients.",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#send_list_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#pages_list").jqGrid('GridUnload');
			jQuery("#pages_list").jqGrid({
				url:"ajaxmessaging/pages-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','File','Pages','Full Path'],
				colModel:[
					{name:'pages_id',index:'pages_id',width:1,hidden:true},
					{name:'file_original',index:'file_original',width:300},
					{name:'pagecount',index:'pagecount',width:100},
					{name:'file',index:'file',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pages_list_pager'),
				sortname: 'pages_id',
				viewrecords: true,
				sortorder: "asc",
				caption:"Fax Pages",
				emptyrecords:"No pages.",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pages_list_pager',{search:false,edit:false,add:false,del:false});
			$("#quick_search_contact").autocomplete({
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
				minLength: 2,
				select: function(event, ui){
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/add-fax-recipient",
						data: "displayname=" + ui.item.value + "&fax=" + ui.item.fax,
						success: function(data){
							$.jGrowl(data);
							reload_grid("send_list");
						}
					});
				}
			});
			$("#quick_search_contact").focus();
		},
		buttons: {
			'Send': function() {
				var str = $("#sendfinal").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/send-fax",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#sendfinal").clearForm();
							$("#messaging_fax_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			'Draft': function() {
				var str = $("#sendfinal").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/send-fax/yes",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#sendfinal").clearForm();
							$("#messaging_fax_dialog").dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$.ajax({
					type: "POST",
					url: "ajaxmessaging/cancel-fax",
					success: function(data){
						$.jGrowl(data);
						$("#sendfinal").clearForm();
						$("#messaging_fax_dialog").dialog('close');
						reload_grid("draft_faxes");
					}
				});
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#fax_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#fax_filepath").val();
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/close-fax",
				data: "fax_filepath=" + a,
				success: function(data){
					$("#embedURL1").html('');
					$("#fax_filepath").val('');
					$("#view_received_id").val('');
					$("#import_fax_pages").val('');
				}
			});	
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#new_fax").click(function(){
		$("#sendfinal").clearForm();
		$.ajax({
			type: "POST",
			url: "ajaxmessaging/new-fax",
			success: function(data){
				$.jGrowl(data);
				$("#faxcoverpage").prop('checked', false);
				$(".formmessagecoverpage").hide();
				$("#faxmessage").val('');
				$("#messaging_fax_dialog").dialog('open');
			}
		});	
	});
	$("#delete_fax").click(function(){
		var click_id = jQuery("#received_faxes").getGridParam('selrow');
		if(click_id){
			if(confirm('Are you sure you want to delete this fax?')){ 
				var click_filePath = jQuery("#received_faxes").getCell(click_id,'filePath');
				var click_fileName = jQuery("#received_faxes").getCell(click_id,'fileName');
				$.ajax({
					type: "POST",
					url: "ajaxmessaging/deletefax",
					data: "filePath=" + click_filePath + "&fileName=" + click_fileName,
					success: function(data){
						$.jGrowl(data);
						reload_grid("received_faxes");
					}
				});
			}
		} else {
			$.jGrowl("Please select fax to delete!");
		}
	});
	$("#fax_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#fax_import_documents_date").mask("99/99/9999");
	$("#fax_import_documents_date").datepicker();
	$("#fax_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#fax_import_patient_search").autocomplete({
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
					$("#fax_pid").val(ui.item.id);
				}
			});
			$("#fax_import_documents_from").autocomplete({
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
			$("#fax_import_documents_desc").autocomplete({
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
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#fax_import_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#fax_import_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/fax-import",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$('#fax_import_form').clearForm();
								$("#fax_import_message").html('');
								$('#fax_import_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#fax_import_form').clearForm();
				$("#fax_import_message").html('');
				$('#fax_import_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#save_fax").click(function() {
		var id = $("#view_received_id").val();
		window.open("view_fax/" + id);
	});
	$("#import_fax").click(function() {
		var id = $("#view_received_id").val();
		var pages = $("#import_fax_pages").val();
		var row = jQuery("#received_faxes").getRowData(id);
		if (pages != '') {
			var text = "Enter details for importing fax from " + row['fileFrom'] + ", pages " + pages + ":";
		} else {
			var text = "Enter details for importing fax from " + row['fileFrom'] + ":";
		}
		$("#fax_received_id").val(id);
		$("#fax_import_pages").val(pages);
		$("#fax_import_message").html(text);
		$("#fax_import_dialog").dialog('open');
		$("#fax_patient_search").focus();
	});
	$("#addrecipient").click(function(){
		jQuery("#send_list").editGridRow("new",{closeAfterAdd:true});
	});
	$("#editrecipient").click(function(){
		var clickedit = jQuery("#send_list").getGridParam('selrow');
		if(clickedit){ 
			jQuery("#send_list").editGridRow(clickedit,{closeAfterEdit:true});
		} else {
			$.jGrowl("Please select recipient to edit!");
		}
	});
	$("#removerecipient").click(function(){
		var clickremove = jQuery("#send_list").getGridParam('selrow');
			if(clickremove){
				jQuery("#send_list").delGridRow(clickremove);
			} else {
				$.jGrowl("Please select recipient to remove!");
			}
	});
	var myUpload2 = $("#addfile").upload({
		action: 'pages_upload',
		onComplete: function(data){
			$.jGrowl(data);
			reload_grid("pages_list");
		}
	});
	$("#pages_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#pages_view_filepath").val();
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/close-fax",
				data: "fax_filepath=" + a,
				success: function(data){
					$("#embedURL2").html('');
				}
			});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#viewpage").click(function(){
		var id = jQuery("#pages_list").getGridParam('selrow');
		if(id){
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/view-page/" + id,
				dataType: "json",
				success: function(data){
					$("#embedURL2").html(data.html);
					$("#pages_view_filepath").val(data.filepath);
					$("#pages_view_dialog").dialog('open');
				}
			});
		}
	});
	$("#delfile").click(function(){
		var clickremove = jQuery("#pages_list").getGridParam('selrow');
		if(clickremove){ 
			var click_file = jQuery("#pages_list").getCell(clickremove,'file');
			var click_pages_id = jQuery("#pages_list").getCell(clickremove,'pages_id');
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/deletepage",
				data: "file=" + click_file + "&pages_id=" + click_pages_id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("pages_list");
				}
			});
		} else {
			$.jGrowl("Please select files to remove!");
		}
	});
	$("#faxcoverpage").click(function(){
		if ($("#faxcoverpage").is(":checked")) {
			$(".formmessagecoverpage").show();
		} else {
			$(".formmessagecoverpage").hide();
			$("#faxmessage").val('');
		}
	});
	
	$("#scan_import_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms"}, false);
	$("#scan_import_documents_date").mask("99/99/9999");
	$("#scan_import_documents_date").datepicker();
	$("#scan_import_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#scan_patient_search").autocomplete({
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
					$("#scan_pid").val(ui.item.id);
				}
			});
			$("#scan_import_documents_from").autocomplete({
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
			$("#scan_import_documents_desc").autocomplete({
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
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#scan_import_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#scan_import_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/scan-import",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$('#scan_import_form').clearForm();
								$("#scan_import_message").html('');
								$('#scan_import_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#scan_import_form').clearForm();
				$("#scan_import_message").html('');
				$('#scan_import_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#savescan").click(function(){
		var click_id = jQuery("#received_scans").getGridParam('selrow');
		if(click_id){
			$("#scan_scans_id").val(click_id);
			var row = jQuery("#received_scans").getRowData(click_id);
			var text = "Enter details for importing document named " + row['fileName'] + ":";
			$("#scan_import_message").html(text);
			$("#scan_import_dialog").dialog('open');
			$("#scan_patient_search").focus();
		}
	});
	$("#delete_scan").click(function(){
		var click_id = jQuery("#received_scans").getGridParam('selarrrow');
		if(click_id){
			if(confirm('Are you sure you want to delete the seletected documents?')){ 
				var count = click_id.length;
				for (var i = 0; i < count; i++) {
					$.ajax({
						type: "POST",
						url: "ajaxmessaging/deletescan",
						data: "scans_id=" + click_id[i],
						success: function(data){
						}
					});
				}
				$.jGrowl('Deleted ' + i + ' documents!');
				reload_grid("received_scans");
			}
		} else {
			$.jGrowl("Please select document to delete!");
		}
	});
	$("#save_scan").click(function() {
		var id = $("#view_scans_id").val();
		window.open("view_scan/" + id);
	});
	$("#import_scan").click(function(){
		var id = $("#view_scans_id").val();
		var pages = $("#import_scan_pages").val();
		var row = jQuery("#received_scans").getRowData(id);
		if (pages != '') {
			var text = "Enter details for importing document named " + row['fileFrom'] + ", pages " + pages + ":";
		} else {
			var text = "Enter details for importing document named " + row['fileName'] + ":";
		}
		$("#scan_scans_id").val(id);
		$("#scan_import_pages").val(pages);
		$("#scan_import_message").html(text);
		$("#scan_import_dialog").dialog('open');
		$("#scan_patient_search").focus();
	});
	$("#scan_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		close: function(event, ui) {
			var a = $("#scan_filepath").val();
			$.ajax({
				type: "POST",
				url: "ajaxmessaging/close-scan",
				data: "scan_filepath=" + a,
				success: function(data){
					$("#embedURL3").html('');
					$("#scan_filepath").val('');
					$("#view_scans_id").val('');
					$("#import_scan_pages").val('');
				}
			});	
		}
	});
	
	var myUpload3 = $("#import_csv").upload({
		action: 'import_contact',
		onComplete: function(data){
			$.jGrowl(data);
			reload_grid("all_contacts_list");
		}
	});
	$("#export_address_csv").click(function(){
		window.open("export_address_csv");
	});
	$("#messaging_add_contact").click(function(){
		$('#messaging_contact_form').clearForm();
		$('#contacts_dialog').dialog('open');
		$("#messaging_lastname").focus();
	});

	$("#messaging_edit_contact").click(function(){
		var item = jQuery("#all_contacts_list").getGridParam('selrow');
		if(item){
			jQuery("#all_contacts_list").GridToForm(item,"#messaging_contact_form");
			$('#contacts_dialog').dialog('open');
			$("#messaging_lastname").focus();
		} else {
			$.jGrowl("Please select contact to edit!")
		}
	});
	$("#messaging_delete_contact").click(function(){
		var item = jQuery("#all_contacts_list").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this contact?')){
				$.ajax({
					type: "POST",
					url: "ajaxmessaging/delete-contact",
					data: "address_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("all_contacts_list");
					}
				});
			}
		} else {
			$.jGrowl("Please select contact to delete!")
		}
	});
	$("#contacts_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#messaging_contact_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messaging_contact_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxmessaging/edit-contact",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#messaging_contact_form").clearForm();
								$("#contacts_dialog").dialog('close');
								reload_grid("all_contacts_list");
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#messaging_contact_form").clearForm();
				$("#contacts_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messaging_specialty").autocomplete({
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
	$("#messaging_city").autocomplete({
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
	$("#messaging_state").addOption(states, false);
	$("#messaging_phone").mask("(999) 999-9999");
	$("#messaging_fax").mask("(999) 999-9999");
	$("#messaging_npi").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/npi-lookup",
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
		var a = $("#messaging_lastname").val();
		var b = $("#messaging_firstname").val();
		var c = $("#messaging_state").val();
		if (a != "" && b != "" && c != "") {
			var q = a + "," + b + "," + c
			$("#messaging_npi").autocomplete("search", q);
		}
	}).mask("9999999999");
	$("#messages_tags").tagit({
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
			if($("#internal_messages_form_id").is(":hidden")) {
				var c = $("#message_view_message_id").val();
			} else {
				var c = $("#messages_message_id").val(); 
			}
			if (b == "added") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/save-tag/message_id/" + c,
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/remove-tag/message_id/" + c,
					data: 'tag=' + a
				});
			}
		}
	});
	function messages_tags() {
		$("#messages_tags").show();
		if($("#internal_messages_form_id").is(":hidden")) {
			var id = $("#message_view_message_id").val();
		} else {
			var id = $("#messages_message_id").val();
		}
		$.ajax({
			type: "POST",
			url: "ajaxsearch/get-tags/message_id/" + id,
			dataType: "json",
			success: function(data){
				$("#messages_tags").tagit("fill",data);
			}
		});
	}
});
var timeoutHnd1;
function doSearch1(ev){ 
	if(timeoutHnd1) 
		clearTimeout(timeoutHnd1);
		timeoutHnd1 = setTimeout(gridReload1,500);
}
function gridReload1(){ 
	var mask = jQuery("#search_all_contact").val();
	jQuery("#all_contacts_list").setGridParam({url:"ajaxmessaging/all-contacts/"+mask,page:1}).trigger("reloadGrid");
}
