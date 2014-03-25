$(document).ready(function() {
	$("#documents_list_dialog").dialog({ 
		bgiframe: true,
		autoOpen: false,
		height: 580,
		width: 800,
		open: function() {
			jQuery("#labs").jqGrid('GridUnload');
			jQuery("#labs").jqGrid({
				url: "ajaxcommon/documents/Laboratory",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager8'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Labs",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager8',{search:false,edit:false,add:false,del:false});
			jQuery("#radiology").jqGrid('GridUnload');
			jQuery("#radiology").jqGrid({
				url: "ajaxcommon/documents/Imaging",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager9'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Imaging",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager9',{search:false,edit:false,add:false,del:false});
			jQuery("#cardiopulm").jqGrid('GridUnload');
			jQuery("#cardiopulm").jqGrid({
				url: "ajaxcommon/documents/Cardiopulmonary",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager10'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Cardiopulmonary",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager10',{search:false,edit:false,add:false,del:false});
			jQuery("#endoscopy").jqGrid('GridUnload');
			jQuery("#endoscopy").jqGrid({
				url: "ajaxcommon/documents/Endoscopy",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager11'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Endoscopy: " + $('#endoscopy_count').val(),
				hiddengrid: true,
				height: "100%",
				onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager11',{search:false,edit:false,add:false,del:false});
			jQuery("#referrals").jqGrid('GridUnload');
			jQuery("#referrals").jqGrid({
				url: "ajaxcommon/documents/Referrals",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager12'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Referrals",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager12',{search:false,edit:false,add:false,del:false});
			jQuery("#past_records").jqGrid('GridUnload');
			jQuery("#past_records").jqGrid({
				url: "ajaxcommon/documents/Past_Records",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager13'),
				sortname: 'documents_date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Past Records",
				hiddengrid: true,
				height: "100%",
				onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager13',{search:false,edit:false,add:false,del:false});
			jQuery("#outside_forms").jqGrid('GridUnload');
			jQuery("#outside_forms").jqGrid({
				url: "ajaxcommon/documents/Other_Forms",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager14'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Other Forms",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager14',{search:false,edit:false,add:false,del:false});
			jQuery("#letters").jqGrid('GridUnload');
			jQuery("#letters").jqGrid({
				url: "ajaxcommon/documents/Letters",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager15'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Letters",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		$("#view_document_id").val(id);
				 		$.ajax({
							type: "POST",
							url: "ajaxcommon/view-documents1/" + id,
							dataType: "json",
							success: function(data){
								//$('#embedURL').PDFDoc( { source : data.html } );
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								documents_view_tags();
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager15',{search:false,edit:false,add:false,del:false});
			jQuery("#ccdas").jqGrid('GridUnload');
			jQuery("#ccdas").jqGrid({
				url: "ajaxcommon/documents/ccda",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','From','Description','Type','URL'],
				colModel:[
					{name:'documents_id',index:'documents_id',width:1,hidden:true},
					{name:'documents_date',index:'documents_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'documents_from',index:'documents_from',width:300},
					{name:'documents_desc',index:'documents_desc',width:325},
					{name:'documents_type',index:'documents_type',width:1,hidden:true},
					{name:'documents_url',index:'documents_url',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#pager16'),
				sortname: 'documents_date',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Consolidated Clinical Documents (C-CDA's)",
			 	hiddengrid: true,
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol > 1) {
				 		window.open("bluebutton/" + id);
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#pager16',{search:false,edit:false,add:false,del:false});
			if (noshdata.group_id == '2' || noshdata.group_id == '3') {
				jQuery("#labs").navButtonAdd('#pager8',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#labs").getGridParam('selrow');
						if(id){
							jQuery("#labs").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager8',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#labs").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("labs");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#radiology").navButtonAdd('#pager9',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#radiology").getGridParam('selrow');
						if(id){
							jQuery("#radiology").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager9',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#radiology").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("radiology");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#cardiopulm").navButtonAdd('#pager10',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#cardiopulm").getGridParam('selrow');
						if(id){
							jQuery("#cardiopulm").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_view_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager10',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#cardiopulm").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("cardiopulm");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#endoscopy").navButtonAdd('#pager11',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#endoscopy").getGridParam('selrow');
						if(id){
							jQuery("#endoscopy").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager11',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#endoscopy").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("endosocpy");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#referrals").navButtonAdd('#pager12',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#referrals").getGridParam('selrow');
						if(id){
							jQuery("#referrals").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager12',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#referrals").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("referrals");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#past_records").navButtonAdd('#pager13',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#past_records").getGridParam('selrow');
						if(id){
							jQuery("#past_records").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager13',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#past_records").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("past_records");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#outside_forms").navButtonAdd('#pager14',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#outside_forms").getGridParam('selrow');
						if(id){
							jQuery("#outside_forms").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager14',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#outside_forms").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("outside_forms");
										refresh_documents();
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#letters").navButtonAdd('#pager15',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#letters").getGridParam('selrow');
						if(id){
							jQuery("#letters").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager15',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#letters").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("letters");
										refresh_documents()
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
				jQuery("#ccdas").navButtonAdd('#pager16',{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery("#ccdas").getGridParam('selrow');
						if(id){
							jQuery("#ccdas").GridToForm(id,"#documents_edit_form");
							var date = $('#menu_documents_date').val();
							var edit_date = editDate(date);
							$('#menu_documents_date').val(edit_date);
							documents_tags();
							$('#documents_edit_dialog').dialog('open');
							$("#menu_documents_from").focus();
						} else {
							$.jGrowl('Choose document to edit!');
						}
					}, 
					position:"last"
				}).navButtonAdd('#pager16',{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery("#ccdas").getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this document?')){ 
								$.ajax({
									type: "POST",
									url: "ajaxchart/delete-document",
									data: "documents_id=" + id,
									success: function(data){
										$.jGrowl(data);
										reload_grid("ccdas");
										refresh_documents()
									}
								});
							}
						} else {
							$.jGrowl('Choose document to delete!');
						}
					}, 
					position:"last"
				});
			}
			refresh_documents();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#documents_view_dialog").dialog({ 
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Download': function() {
				var id = $("#view_document_id").val();
				window.open("view_documents/" + id);
			},
			'Close' : function() {
				var a = $("#document_filepath").val();
				$.ajax({
					type: "POST",
					url: "ajaxcommon/close-document",
					data: "document_filepath=" + a,
					success: function(data){
						$("#embedURL").html('');
						$("#document_filepath").val('');
						$("#view_document_id").val('');
						$("#documents_view_dialog").dialog('close');
					}
				});
				
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#documents_list").click(function() {
		$("#documents_list_dialog").dialog('open');
	});
	$("#dashboard_health_record").click(function() {
		$("#documents_list_dialog").dialog('open');
	});
	$("#menu_documents_type").addOption({"Laboratory":"Laboratory","Imaging":"Imaging","Cardiopulmonary":"Cardiopulmonary","Endoscopy":"Endoscopy","Referrals":"Referrals","Past Records":"Past Records","Other Forms":"Other Forms","Letters":"Letters"}, false);
	$("#menu_documents_date").mask("99/99/9999");
	$("#menu_documents_date").datepicker();
	$("#documents_edit_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#menu_documents_from").autocomplete({
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
			$("#menu_documents_desc").autocomplete({
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
				$("#documents_edit_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#documents_edit_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-document",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$('#documents_edit_form').clearForm();
								$('#documents_edit_dialog').dialog('close');
								reload_grid("labs");
								reload_grid("radiology");
								reload_grid("cardiopulm");
								reload_grid("endoscopy");
								reload_grid("referrals");
								reload_grid("past_records");
								reload_grid("outside_forms");
								reload_grid("letters");
								refresh_documents();
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#documents_edit_form').clearForm();
				$('#documents_edit_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#menu_new_letter").click(function() {
		$("#letter_dialog").dialog('open');
	});
	$("#letter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#letter_to").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/all_contacts1",
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
					$('#letter_to_id').val(ui.item.id);
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxchart/letter-template-select-list",
				dataType: "json",
				success: function(data){
					$('#letter_template_choose_id').addOption({"":"*Select a template"});
					$('#letter_template_choose_id').addOption(data.options);
					$('#letter_template_choose_id').sortOptions();
					$('#letter_template_choose_id').val("");
				}
			});
		},
		buttons: {
			'Save': function() {
				var str = $("#letter_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxchart/print-letter",
					data: str,
					dataType: 'json',
					async: false,
					success: function(data){
						if (data.message == 'OK') {
							$.ajax({
								type: "POST",
								url: "ajaxcommon/view-documents1/" + data.id,
								dataType: "json",
								success: function(data){
									//$('#embedURL').PDFDoc( { source : data.html } );
									$("#embedURL").html(data.html);
									$("#document_filepath").val(data.filepath);
									$("#documents_view_dialog").dialog('open');
								}
							});
						} else {
							$.jGrowl(data.message);
						}
					}
				});
				var eid = $("#letter_eid").val();
				if (eid != '') {
					var to = $("#letter_to").val();
					var body = $("#letter_body").val();
					var send = "Letter Written:\nTo: " + to + "\n" + body;
					var old = $("#orders_plan").val();
						if(old){
							var pos = old.lastIndexOf('\n');
							if (pos == -1) {
								var old1 = old + '\n';
							} else {
								var a = old.slice(pos);
								if (a == '') {
									var old1 = old;
								} else {
									var old1 = old + '\n';
								}
							}
						} else {
							var old1 = '';
						}
						$("#orders_plan").val(old1+send);
				}
				$('#letter_form').clearForm();
				$('#letter_template_form').clearForm();
				$('#letter_template').hide();
				$('#letter_dialog').dialog('close');
			},
			Cancel: function() {
				$('#letter_form').clearForm();
				$('#letter_template_form').clearForm();
				$('#letter_template').hide();
				$('#letter_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#letter_to_whom").button();
	$("#letter_to_whom").click(function() {
		$('#letter_to').val('To whom it may concern');
	});
	$('#letter_template_choose_id').change(function(){
		var a = $(this).val();
		$('#letter_template_form').html('');
		if (a != '') {
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-letter-template/" + a,
				dataType: "json",
				success: function(data){
					$('#letter_template_form').dform(data);
					$(".letter_date").mask("99/99/9999").datepicker();
					$('#letter_template_form').find('.letter_buttonset').buttonset();
					$(".letter_select").chosen();
					$('#letter_template_form').find('input').first().focus();
				}
			});
		}
	});
	$("#letter_template_save").button({icons: {primary: "ui-icon-arrowthick-1-w"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxchart/letter-template-construct",
			dataType: "json",
			success: function(data){
				var b = $(".letter_hidden").val();
				var start_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_start_date').val()));
				var return_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_return_date').val()));
				var end_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_end_date').val()));
				b = b.replace('_firstname', data.firstname);
				b = b.replace('  _firstname', '  ' + data.firstname);
				b = b.replace('_start_date', start_date);
				b = b.replace('_return_date', return_date);
				b = b.replace('_end_date', end_date);
				var c_array = $('.letter_select').val();
				if (c_array) {
					var c = c_array.join("");
					b = b + "\n" + c;
				}
				var a = $("#letter_body").val();
				a = a.replace(data.start, '');
				if (a == '') {
					$('#letter_body').val(data.start + b);
				} else {
					$('#letter_body').val(a + "\n" + data.start + b);
				}
				$('#letter_template_form').clearForm();
			}
		});
		
	});
	$('#letter_reset').button({icons: {primary: "ui-icon-close"}}).click(function(){
		$("#letter_body").val('');
		$('#letter_template_form').clearForm();
	});
	
	$("#menu_tests").button({
		icons: {
			primary: "ui-icon-image"
		}
	});
	$("#menu_tests").click(function() {
		$("#tests_dialog").dialog('open');
	});
	$("#tests_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		open: function(event, ui) {
			$("#chart_loading").hide();
			jQuery("#tests_list").jqGrid('GridUnload');
			jQuery("#tests_list").jqGrid({
				url:"ajaxchart/tests",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Test','Result','Unit','Normal','Flags','Type'],
				colModel:[
					{name:'tests_id',index:'tests_id',width:1,hidden:true},
					{name:'test_datetime',index:'test_datetime',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'test_name',index:'test_name',width:310},
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
				pager: jQuery('#tests_list_pager'),
				sortname: 'test_datetime',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Test Results",
			 	height: "100%",
			 	gridview: true,
			 	rowattr: function (rd) {
					if (rd.test_flags == "HH" || rd.test_flags == "LL" || rd.test_flags == "H" || rd.test_flags == "L") {
						return {"class": "myAltRowClass"};
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#tests_list_pager',{search:false,edit:false,add:false,del:false});
			$("#chart_results").button().click(function() {
				var item = jQuery("#tests_list").getGridParam('selrow');
				if (item) {
					$("#chart_loading").show();
					var options = {
						chart: {
							renderTo: 'tests_container',
							defaultSeriesType: 'line',
							marginRight: 130,
							marginBottom: 50,
							width: 750
						},
						title: {
							text: '',
							x: -20
						},
						xAxis: {
							title: {
								text: ''
							},
							type: 'datetime'
						},
						yAxis: {
							title: {
								text: ''
							},
							plotLines: [{
								value: 0,
								width: 1,
								color: '#808080'
							}]
						},
						legend: {
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'top',
							x: -10,
							y: 100,
							borderWidth: 0
						},
						series: [
							{type: 'line', data: []}
						],
						credits: {
							href: 'http://noshemr.wordpress.com',
							text: 'NOSH ChartingSystem'
						}
					};
					$.ajax({
						type: "POST",
						url: "ajaxchart/chart-test/" + item,
						dataType: "json",
						success: function(data){
							options.title.text = data.title;
							options.xAxis.title.text = data.xaxis;
							options.yAxis.title.text = data.yaxis;
							options.series[0].name = data.name;
							newData = [];
							for (i in data.patient) {
								newData.push( [ new Date(data.patient[i][0]).getTime(), parseFloat(data.patient[i][1]) ] );
							}
							options.series[0].data = newData;
							var chart = new Highcharts.Chart(options);
							$("#chart_loading").hide();
						}
					});
				} else {
					$.jGrowl('Choose item to chart!');
				}
			});
		},
		close: function (event, ui) {
			$("#tests_container").html('');
		}
	});
	$("#documents_view_tags").tagit({
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
					url: "ajaxsearch/save_tag/documents_id/" + $("#view_document_id").val(),
					data: 'tag=' + a
				});
			}
			if (b == "popped") {
				$.ajax({
					type: "POST",
					url: "ajaxsearch/remove_tag/documents_id/" + $("#view_document_id").val(),
					data: 'tag=' + a
				});
			}
		}
	});
	function documents_view_tags() {
		var id = $("#view_document_id").val();
		$.ajax({
			type: "POST",
			url: "ajaxsearch/get-tags/documents_id/" + id,
			dataType: "json",
			success: function(data){
				$("#documents_view_tags").tagit("fill",data);
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
	var mask = jQuery("#search_all_tests").val();
	jQuery("#tests_list").setGridParam({url:"ajaxchart/tests/"+mask,page:1}).trigger("reloadGrid");
}
