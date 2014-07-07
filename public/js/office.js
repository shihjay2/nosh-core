$(document).ready(function() {
	function super_query() {
		$("#super_query_div").html('Search patients with the following filters:<br><button type="button" id="search_add" class="nosh_button_add">Add </button> <input type="hidden" name="search_join[]" id="search_join_first" value="start"></input><select name="search_field[]" id="search_field_1" class="text search_field_class"></select> <select name="search_op[]" id="search_op_1" class="text search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_1"  class="text search_desc_class"></input>');
		loadbuttons();
		$("#search_field_1").addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list","sup_supplement":"Patient's active supplement list","zip":"Zip code where patient resides","city":"City where patient resides"},false);
		$("#search_op_1").addOption({"":"Select Operator"},false);
		$("#search_field_1").change(function(){
			var a = $("#search_field_1").val();
			if (a == "age") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than","contains":"contains","not equal":"is not equal to"},false);
				$("#search_desc_1").val("");
			}
			if (a == "issue" || a == "rxl_medication" || a == "imm_immunization" || a == "insurance" || a == "sup_supplement" || a == "zip" || a == "city") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
				$("#search_desc_1").val("");
			}
			if (a == "billing") {
				$("#search_op_1").removeOption(/./);
				$("#search_op_1").addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
				$("#search_desc_1").val("");
				$("#search_desc_1").autocomplete({
					source: function (req, add){
						$.ajax({
							url: "ajaxsearch/cpt",
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
			}
		});
		$("#search_op_1").change(function(){
			var a = $("#search_op_1").val();
			if (a == "between") {
				$("#search_desc_1").val(" AND ");
			}
		});
		$("#search_gender_both").prop('checked',true);
		$("#search_add").click(function() {
			var a = $("#super_query_div > :last-child").attr("id");
			var a1 = a.split("_");
			var count = parseInt(a1[2]) + 1;
			$("#super_query_div").append('<br><select name="search_join[]" id="search_join_'+count+'" class="text search_join_class"></select> <select name="search_field[]" id="search_field_'+count+'" class="text search_field_class"></select> <select name="search_op[]" id="search_op_'+count+'" class="text search_op_class"></select> <input type="text" name="search_desc[]" id="search_desc_'+count+'"  class="text search_desc_class"></input>');
			$("#search_field_"+count).addOption({"":"Select Field","age":"Patient's age","insurance":"Patient's primary insurance","issue":"Patient's active medical issue list","billing":"Patient's billing code","rxl_medication":"Patient's active medication list","imm_immunization":"Patient's immunization list","sup_supplement":"Patient's active supplement list","sup_supplement":"Patient's active supplement list","zip":"Zip code where patient resides","city":"City where patient resides"},false);
			$("#search_op_"+count).addOption({"":"Select Operator"},false);
			$("#search_join_"+count).addOption({"AND":"And (&)","OR":"Or (||)"},false);
			$("#search_field_"+count).change(function(){
				var a = $("#search_field_"+count).val();
				if (a == "age") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","less than":"is less than","equal":"is equal to","greater than":"is greater than","between":"is between"},false);
					$("#search_desc_"+count).val("");
				}
				if (a == "issue" || a == "rxl_medication" || a == "imm_immunization" || a == "insurance" || a == "sup_supplement" || a == "zip" || a == "city") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","contains":"contains","not equal":"is not equal to"},false);
					$("#search_desc_"+count).val("");
				}
				if (a == "billing") {
					$("#search_op_"+count).removeOption(/./);
					$("#search_op_"+count).addOption({"":"Select Operator","equal":"is equal to","not equal":"is not equal to"},false);
					$("#search_desc_"+count).val("");
					$("#search_desc_"+count).autocomplete({
						source: function (req, add){
							$.ajax({
								url: "ajaxsearch/cpt');?>",
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
				}
			});
			$("#search_op_"+count).change(function(){
				var a = $("#search_op_"+count).val();
				if (a == "between") {
					$("#search_desc_"+count).val(" AND ");
				}
			});
		});
	}
	function tag_grid_reload() {
		var a = $("#tags_search").val();
		if (a !== null) {
			var json_result = $("#tag_query_form").serializeObject();
			var b = $("#tag_patient").val();
			if (b != '') {
				var pid = $("#tag_pid").val();
			} else {
				var pid = '0';
			}
			jQuery("#tag_query_results").jqGrid('GridUnload');
			jQuery("#tag_query_results").jqGrid({
				url:"ajaxoffice/tag-query/" + pid,
				datatype: "json",
				postData: json_result,
				mtype: "POST",
				colNames:['Index','PID','Last Name','First Name','Date','Document Type','Document Type Index','ID'],
				colModel:[
					{name:'index',index:'index',width:1,hidden:true},
					{name:'pid',index:'pid',width:50},
					{name:'lastname',index:'lastname',width:150,sortable:false},
					{name:'firstname',index:'firstname',width:150,sortable:false},
					{name:'doc_date',index:'doc_date',width:150,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"},sortable:false},
					{name:'doctype',index:'doctype',width:150,sortable:false},
					{name:'doctype_index',index:'doctype_index',width:1,hidden:true},
					{name:'doc_id',index:'doc_id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#tag_query_results_pager'),
				sortname: 'pid',
				viewrecords: true,
				sortorder: "asc",
				caption:"Search Results",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" },
				onSelectRow: function(row_id) {
					var row = $(this).getRowData(row_id);
					if (row['doctype_index'] == 'eid') {
						$("#tag_modal_view").load('ajaxoffice/modal-view/' + row['doc_id'] + '/' + row['pid']);
						$("#tag_modal_view_dialog").dialog("option", "title", "Encounter");
					}
					if (row['doctype_index'] == 't_messages_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/telephone-messages-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Patient Message");
							}
						});
					}
					if (row['doctype_index'] == 'message_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/messages-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Message");
							}
						});
					}
					if (row['doctype_index'] == 'documents_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/documents-view/" + row['doc_id'] + '/' + row['pid'],
							dataType: "json",
							success: function(data){
								$("#tag_modal_view").html(data.html);
								$("#tag_document_filepath").val(data.filepath);
								$("#tag_modal_view_dialog").dialog("option", "title", "Document");
							}
						});
					}
					if (row['doctype_index'] == 'mtm_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/mtm-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Medication Therapy Management");
							}
						});
					}
					if (row['doctype_index'] == 'appt_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/appt-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Appointment Details");
							}
						});
					}
					if (row['doctype_index'] == 'hippa_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/hippa-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Records Release Details");
							}
						});
					}
					if (row['doctype_index'] == 'tests_id') {
						$.ajax({
							type: "POST",
							url: "ajaxoffice/tests-view/" + row['doc_id'] + '/' + row['pid'],
							success: function(data){
								$("#tag_modal_view").html(data);
								$("#tag_modal_view_dialog").dialog("option", "title", "Test Details");
							}
						});
					}
					$("#tag_modal_view_dialog").dialog('open');
				}
			}).navGrid('#tag_query_results_pager',{search:false,edit:false,add:false,del:false});
		} else {
			jQuery("#tag_query_results").jqGrid('GridUnload');
		}
	}
	super_query();
	$("#nosh_office").click(function() {
		$("#office_dialog").dialog('open');
	});
	$("#office_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 925, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#office_accordion").accordion({ heightStyle: "content" });
			$('#hedis_office_load').hide();
			jQuery("#vaccine_inventory").jqGrid('GridUnload');
			jQuery("#vaccine_inventory").jqGrid({
				url:"ajaxoffice/vaccine-inventory",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Purchased','Expiration Date','Vaccine','Quantity','Lot','Manufacturer','Brand','CVX','CPT'],
				colModel:[
					{name:'vaccine_id',index:'vaccine_id',width:1,hidden:true},
					{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_expiration',index:'imm_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:500},
					{name:'quantity',index:'quantity',width:100},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_brand',index:'imm_brand',width:1,hidden:true},
					{name:'imm_cvxcode',index:'imm_cvxcode',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#vaccine_inventory_pager'),
				sortname: 'imm_immunization',
				viewrecords: true,
				sortorder: "desc",
				caption:"Vaccine Inventory",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#vaccine_inventory_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#vaccine_inventory_inactive").jqGrid('GridUnload');
			jQuery("#vaccine_inventory_inactive").jqGrid({
				url:"ajaxoffice/vaccine-inventory-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Purchased','Expiration Date','Vaccine','Quantity','Lot','Manufacturer','Brand','CVX','CPT'],
				colModel:[
					{name:'vaccine_id',index:'vaccine_id',width:1,hidden:true},
					{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_expiration',index:'imm_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:600},
					{name:'quantity',index:'quantity',width:1,hidden:true},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_brand',index:'imm_brand',width:1,hidden:true},
					{name:'imm_cvxcode',index:'imm_cvxcode',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#vaccine_inventory_inactive_pager'),
				sortname: 'imm_immunization',
				viewrecords: true,
				sortorder: "desc",
				caption:"Inactive Vaccine Inventory",
				height: "100%",
				hiddengrid: true,
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#vaccine_inventory_inactive_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#vaccine_temp").jqGrid('GridUnload');
			jQuery("#vaccine_temp").jqGrid({
				url:"ajaxoffice/vaccine-temp",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Temperature','Action Taken If Out of Range'],
				colModel:[
					{name:'temp_id',index:'temp_id',width:1,hidden:true},
					{name:'date',index:'date',width:200},
					{name:'temp',index:'temp',width:100},
					{name:'action',index:'temp',width:500},
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#vaccine_temp_pager'),
				sortname: 'date',
				viewrecords: true,
				sortorder: "desc",
				caption:"Vaccine Temperatures",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#vaccine_temp_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#supplements_inventory").jqGrid('GridUnload');
			jQuery("#supplements_inventory").jqGrid({
				url:"ajaxoffice/supplement-inventory",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Purchased','Expiration Date','Supplement','Strength','Quantity','Manufacturer','Lot','CPT','Charge'],
				colModel:[
					{name:'supplement_id',index:'supplement_id',width:1,hidden:true},
					{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_expiration',index:'sup_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_description',index:'sup_description',width:500},
					{name:'sup_strength',index:'sup_strength',width:1,hidden:true},
					{name:'quantity',index:'quantity',width:100},
					{name:'sup_manufacturer',index:'sup_manufacturer',width:1,hidden:true},
					{name:'sup_lot',index:'sup_lot',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:1,hidden:true},
					{name:'charge',index:'charge',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#supplements_inventory_pager'),
				sortname: 'sup_description',
				viewrecords: true,
				sortorder: "asc",
				caption:"Supplement Inventory",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#supplements_inventory_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#supplements_inventory_inactive").jqGrid('GridUnload');
			jQuery("#supplements_inventory_inactive").jqGrid({
				url:"ajaxoffice/supplement-inventory-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Purchased','Expiration Date','Supplement','Strength','Quantity','Manufacturer','Lot','CPT','Charge'],
				colModel:[
					{name:'supplement_id',index:'supplement_id',width:1,hidden:true},
					{name:'date_purchase',index:'date_purchase',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_expiration',index:'sup_expiration',width:100,hidden:true,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_description',index:'sup_description',width:600},
					{name:'sup_strength',index:'sup_strength',width:1,hidden:true},
					{name:'quantity',index:'quantity',width:1,hidden:true},
					{name:'sup_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'sup_lot',index:'sup_lot',width:1,hidden:true},
					{name:'cpt',index:'cpt',width:1,hidden:true},
					{name:'charge',index:'charge',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#supplements_inventory_inactive_pager'),
				sortname: 'sup_description',
				viewrecords: true,
				sortorder: "asc",
				caption:"Inactive Supplements Inventory",
				height: "100%",
				hiddengrid: true,
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#supplements_inventory_inactive_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				type: "POST",
				url: "ajaxoffice/age-percentage",
				dataType: "json",
				success: function(data){
					$("#age_group1").html(data.group1);
					$("#age_group2").html(data.group2);
					$("#age_group3").html(data.group3);
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxoffice/get-sales-tax",
				success: function(data){
					$("#sales_tax").val(data);
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxsearch/search-tags1",
				dataType: "json",
				success: function(data){
					if (data.message == "OK") {
						$("#tags_search").addOption(data, false).removeOption("message").trigger("liszt:updated");
					} else {
						$.jGrowl(data.message);
					}
				}
			});
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#edit_vaccine_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#edit_vaccine_imm_immunization").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/imm",
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
					$("#imm_cvxcode").val(ui.item.cvx);
				}
			});
			$("#edit_vaccine_cpt").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/cpt",
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
				$("#edit_vaccine_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_vaccine_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxoffice/edit-vaccine",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("vaccine_inventory");
								$('#edit_vaccine_form').clearForm();
								$('#edit_vaccine_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_vaccine_form').clearForm();
				$('#edit_vaccine_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#edit_vaccine_imm_expiration").mask("99/99/9999").datepicker();
	$("#edit_vaccine_date_purchase").mask("99/99/9999").datepicker();
	$("#reactivate_vaccine_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Reactivate': function() {
				var bValid = true;
				$("#reactivate_vaccine_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#reactivate_vaccine_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxoffice/reactivate-vaccine",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("vaccine_inventory_inactive");
							reload_grid("vaccine_inventory");
							$("#reactivate_vaccine_form").clearForm();
							$("#reactivate_vaccine_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#reactivate_vaccine_form").clearForm();
				$("#reactivate_vaccine_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#add_vaccine").click(function(){
		$('#edit_vaccine_dialog').dialog('option', 'title', "Add Vaccine");
		$('#edit_vaccine_dialog').dialog('open');
		$("#edit_vaccine_imm_immunization").focus();
	});
	$("#edit_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			jQuery("#vaccine_inventory").GridToForm(item,"#edit_vaccine_form");
			var date = $('#edit_vaccine_imm_expiration').val();
			var edit_date = editDate(date);
			$('#edit_vaccine_imm_expiration').val(edit_date);
			$('#edit_vaccine_dialog').dialog('option', 'title', "Edit Vaccine");
			$('#edit_vaccine_dialog').dialog('open');
			$("#edit_vaccine_imm_immunization").focus();
		} else {
			$.jGrowl("Please select vaccine to edit!")
		}
	});
	$("#inactivate_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxoffice/inactivate-vaccine",
				data: "vaccine_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("vaccine_inventory");
					reload_grid("vaccine_inventory_inactive");
				}
			});
		} else {
			$.jGrowl("Please select vaccine to inactivate!")
		}
	});
	$("#delete_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this vaccination entry?  This is not recommended unless entering the vaccine was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxoffice/delete-vaccine",
					data: "vaccine_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("vaccine_inventory");
						reload_grid("vaccine_inventory_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select vaccine to delete!")
		}
	});
	$("#reactivate_vaccine").click(function(){
		var item = jQuery("#vaccine_inventory_inactive").getGridParam('selrow');
		if(item){
			$("#reactivate_vaccine_id").val(item);
			$("#reactivate_vaccine_dialog").dialog('open');
			$("#reactivate_quantity").focus();
		} else {
			$.jGrowl("Please select vaccine to reactivate!")
		}
	});
	$("#edit_vaccine_temp_dialog").dialog({ 
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
				$("#edit_vaccine_temp_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_vaccine_temp_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxoffice/edit-temp",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("vaccine_temp");
								$('#edit_vaccine_temp_form').clearForm();
								$('#edit_vaccine_temp_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_vaccine_temp_form').clearForm();
				$('#edit_vaccine_temp_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#temp_date").mask("99/99/9999").datepicker();
	$('#temp_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#add_temp").click(function(){
		var date = getCurrentDate();
		var time = getCurrentTime();
		$('#edit_vaccine_temp_date').val(date);
		$('#edit_vaccine_temp_time').val(time);
		$('#edit_vaccine_temp_dialog').dialog('option', 'title', "Add Vaccine Temperature");
		$('#edit_vaccine_temp_dialog').dialog('open');
		$("#edit_vaccine_temp").focus();
	});
	$("#edit_temp").click(function(){
		var item = jQuery("#vaccine_temp").getGridParam('selrow');
		if(item){
			jQuery("#vaccine_temp").GridToForm(item,"#edit_vaccine_temp_form");
			var date = $('#edit_vaccine_temp_date').val();
			var edit_date = editDate1(date);
			$('#edit_vaccine_temp_date').val(edit_date);
			var edit_time = editDate2(date);
			$('#edit_vaccinetemp_time').val(edit_time);
			$('#edit_vaccine_temp_dialog').dialog('option', 'title', "Edit Vaccine Temperature");
			$('#edit_vaccine_temp_dialog').dialog('open');
			$("#edit_vaccine_temp").focus();
		} else {
			$.jGrowl("Please select vaccine to edit!")
		}
	});
	$("#delete_temp").click(function(){
		var item = jQuery("#vaccine_temp").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this vaccine temperature entry?  This is not recommended unless entering the temperature was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxoffice/delete-temp",
					data: "temp_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("vaccine_temp");
						reload_grid("vaccine_temp_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select vaccine temperature to delete!")
		}
	});
	$("#edit_supplement_sup_expiration").mask("99/99/9999").datepicker();
	$("#edit_supplement_sup_date_purchase").mask("99/99/9999").datepicker();
	$("#sales_tax").focusout(function(){
		$.ajax({
			type: "POST",
			url: "ajaxoffice/update-sales-tax",
			data: "sales_tax=" + $(this).val(),
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#supplements_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#edit_supplement_sup_description").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-cpt",
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
					$("#edit_supplement_sup_cpt").val(ui.item.cpt);
					$("#edit_supplement_sup_quantity").val(ui.item.quantity);
					$("#edit_supplement_sup_charge").val(ui.item.charge);
					$("#edit_supplement_sup_manufacturer").val(ui.item.charge.manufacturer);
				}
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_supplement_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_supplement_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxoffice/edit-supplement",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("supplements_inventory");
								$('#edit_supplement_form').clearForm();
								$('#supplements_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_supplement_form').clearForm();
				$('#supplements_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#add_supplement").button().click(function(){
		$('#supplements_dialog').dialog('option', 'title', "Add Supplement");
		$("#supplements_dialog").dialog('open');
		$("#edit_supplement_sup_description").focus();
	});
	$("#edit_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			jQuery("#supplements_inventory").GridToForm(item,"#edit_supplement_form");
			var date = $('#edit_supplement_sup_expiration').val();
			var edit_date = editDate(date);
			$('#edit_supplement_sup_expiration').val(edit_date);
			$('#supplements_dialog').dialog('option', 'title', "Edit Supplement");
			$("#supplements_dialog").dialog('open');
			$("#edit_supplement_sup_description").focus();
		} else {
			$.jGrowl("Please select supplement to edit!")
		}
	});
	$("#inactivate_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxoffice/inactivate-supplement",
				data: "supplement_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("supplements_inventory");
					reload_grid("supplements_inventory_inactive");
				}
			});
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#delete_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this supplement entry?  This is not recommended unless entering the supplement was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxoffice/delete-supplement",
					data: "supplement_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("supplements_inventory");
						reload_grid("supplements_inventory_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select supplement to delete!")
		}
	});
	$("#reactivate_supplement").button().click(function(){
		var item = jQuery("#supplements_inventory_inactive").getGridParam('selrow');
		if(item){
			$("#reactivate_supplement_inventory_id").val(item);
			$("#reactivate_supplement_dialog").dialog('open');
			$("#reactivate_sup_quantity").focus();
		} else {
			$.jGrowl("Please select supplement to reactivate!")
		}
	});
	$("#reactivate_supplement_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Reactivate': function() {
				var bValid = true;
				$("#reactivate_supplement_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#reactivate_supplement_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxoffice/reactivate-supplement",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("supplements_inventory_inactive");
							reload_grid("supplements_inventory");
							$("#reactivate_supplement_form").clearForm();
							$("#reactivate_supplement_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#reactivate_supplement_form").clearForm();
				$("#reactivate_supplement_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#super_query_submit").click(function(){
		var json_result = $("#super_query_form").serializeObject();
		jQuery("#super_query_results").jqGrid('GridUnload');
		jQuery("#super_query_results").jqGrid({
			url:"ajaxoffice/super-query",
			datatype: "json",
			postData: json_result,
			mtype: "POST",
			colNames:['PID','Last Name','First Name','DOB'],
			colModel:[
				{name:'pid',index:'pid',width:50},
				{name:'lastname',index:'lastname',width:150},
				{name:'firstname',index:'firstname',width:150},
				{name:'DOB',index:'DOB',width:150,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#super_query_results_pager'),
			sortname: 'lastname',
		 	viewrecords: true,
		 	sortorder: "asc",
		 	caption:"Search Results",
		 	height: "100%",
		 	jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#super_query_results_pager',{search:false,edit:false,add:false,del:false});
	});
	$("#super_query_reset").click(function(){
		super_query();
		$("#super_query_form").clearForm();
		$("#search_gender_both").prop('checked',true);
		$("#search_join_first").val('start');
		jQuery("#super_query_results").jqGrid('GridUnload');
	});
	$("#tags_search").chosen().change(function() {
		tag_grid_reload();
	});
	$("#tag_patient").autocomplete({
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
			$("#tag_pid").val(ui.item.id);
		}
	}).change(function() {
		tag_grid_reload();
	});
	$("#tag_modal_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			var a = $("#tag_document_filepath").val();
			if (a != '') {
				$.ajax({
					type: "POST",
					url: "ajaxoffice/close-document",
					data: "document_filepath=" + a,
					success: function(data){
						$("#tag_document_filepath").val('');
						$("#tag_view_document_id").val('');
					}
				});	
			}
			$("#tag_modal_view").html('');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#hedis_office_time").mask("99/99/9999").datepicker();
	$("#hedis_office_spec").click(function() {
		var a = $("#hedis_office_time").val();
		if (a != '') {
			$('#hedis_office_load').show();
			$.ajax({
				type: "POST",
				url: "ajaxoffice/hedis-audit/spec",
				data: "time=" + a,
				success: function(data){
					$('#hedis_office_items').html(data);
					$('#hedis_office_load').hide();
				}
			});
		} else {
			$.jGrowl('Enter a time value!');
		}
	});
	$("#hedis_office_all").click(function() {
		$('#hedis_office_load').show();
		$.ajax({
			type: "POST",
			url: "ajaxoffice/hedis-audit/all",
			success: function(data){
				$('#hedis_office_items').html(data);
				$('#hedis_office_load').hide();
			}
		});
	});
	$("#hedis_office_year").click(function() {
		$('#hedis_office_load').show();
		$.ajax({
			type: "POST",
			url: "ajaxoffice/hedis-audit/year",
			success: function(data){
				$('#hedis_office_items').html(data);
				$('#hedis_office_load').hide();
			}
		});
	});
	$("#export_demographics").button().click(function(){
		window.open("export_demographics/all");
	});
	$("#export_demographics1").button().click(function(){
		window.open("export_demographics/active");
	});
});
