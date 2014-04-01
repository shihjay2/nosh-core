$(document).ready(function() {
	$.ajax({
		url: "ajaxdashboard/check-fax",
		type: "POST",
		success: function(data){
			if (data == "Yes") {
				$(".fax_button").show();
			} else {
				$(".fax_button").hide();
			}
		}
	});
	$("#medications_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#medications").jqGrid('GridUnload');
			jQuery("#medications").jqGrid({
				url:"ajaxcommon/medications",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','NDC'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:255},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:105},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#medications_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medications - Click on the Date Active column to get past prescriptions for the medication.",
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol == 1) {
						var med = jQuery("#medications").getCell(id,'rxl_medication');
						$.ajax({
							type: "POST",
							url: "ajaxchart/past-medication",
							data: "rxl_medication=" + med,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.item, {sticky:true, header:data.header});
							}
						});
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#medications_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#medications_inactive").jqGrid('GridUnload');
			jQuery("#medications_inactive").jqGrid({
				url:"ajaxcommon/medications-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:255},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:105},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#medications_inactive_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Inactive Medications",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#medications_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#oh_meds_header').hide();
			menu_update('medications');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".medications_list").click(function() {
		$("#medications_list_dialog").dialog('open');
		$("#oh_meds_header").hide();
	});
	$("#dashboard_rx").click(function() {
		$("#medications_list_dialog").dialog('open');
		$("#oh_meds_header").hide();
	});
	$("#rxl_date_active").mask("99/99/9999");
	$("#rxl_date_active").datepicker();
	$("#rxl_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#rxl_route").selectOptions();
	$("#rxl_dosage").focus(function(){
		var rx_name = $("#rxl_name").val();
		if (rx_name == '') {
			$.jGrowl('Medication field empty!');
		} else {
			rx_name = rx_name + ";" + $("#rxl_form").val();
			$("#rxl_dosage").autocomplete("search", rx_name);
		}
	});
	$("#add_rx").click(function(){
		$('#edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#rxl_date_active').val(currentDate);
		$('#edit_medications_dialog').dialog('option', 'title', "Add Medication");
		$('#edit_medications_dialog').dialog('open');
		$("#rxl_search").focus();
	});
	$("#edit_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			jQuery("#medications").GridToForm(item,"#edit_rx_form");
			var date = $('#rxl_date_active').val();
			var edit_date = editDate(date);
			$('#rxl_date_active').val(edit_date);
			$('#edit_medications_dialog').dialog('option', 'title', "Edit Medication");
			$('#edit_medications_dialog').dialog('open');
			$("#rxl_medication").focus();
		} else {
			$.jGrowl("Please select medication to edit!")
		}
	});
	$("#inactivate_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-medication",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					reload_grid("medications");
					reload_grid("medications_inactive");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#delete_rx").click(function(){
		var item = jQuery("#medications").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this medication?  This is not recommended unless entering the medication was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-medication",
					data: "rxl_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("medications");
						reload_grid("medications_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#reactivate_rx").click(function(){
		var item = jQuery("#medications_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/reactivate-medication",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					reload_grid("medications_inactive");
					reload_grid("medications");
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$("#edit_medications_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#rxl_medication").autocomplete({
				source: function (req, add){
					var term = req.term;
					if (term in medcache1) {
						add(medcache1[term]);
						return;
					}
					$.ajax({
						url: "ajaxsearch/rx-name",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								medcache1[term] = data.message;
								add(data.message);
							}
						}
					});
				},
				minLength: 3,
				select: function(event, ui){
					$('#rxl_name').val(ui.item.name);
					$('#rxl_form').val(ui.item.form);
					$('#rxl_dosage').val('');
					$('#rxl_dosage_unit').val('');
				}
			});
			$("#rxl_dosage").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-dosage",
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
				minLength: 0,
				select: function(event, ui){
					$("#rxl_dosage_unit").val(ui.item.unit);
					$.ajax({
						url: "ajaxsearch/rx-ndc-convert/" + ui.item.ndc,
						type: "POST",
						success: function(data){
							$("#rxl_ndcid").val(data);
						}
					});
				}
			});
			$(".search_sig").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_sig",
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
				minLength: 1
			});
			$(".search_frequency").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_frequency",
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
				minLength: 1
			});
			$(".search_instructions").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_instructions",
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
			$(".search_reason").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_reason",
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
				$("#edit_rx_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_rx_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-medication",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("medications");
								reload_grid("nosh_medications");
								$('#edit_rx_form').clearForm();
								$('#edit_medications_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_rx_form').clearForm();
				$('#edit_medications_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#interactions_load").dialog({
		height: 100,
		autoOpen: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		closeOnEscape: false,
		dialogClass: "noclose",
		modal: true,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_action_rx_dialog").dialog({
		height: 200,
		autoOpen: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		modal: true,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_rx_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			jQuery("#messages_medications").jqGrid('GridUnload');
			jQuery("#messages_medications").jqGrid({
				url:"ajaxcommon/medications",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Date Prescribed','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Days','Quantity','Refills','NDC','Provider ID'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_date_prescribed',index:'rxl_date_prescribed',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:180},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:75},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_days',index:'rxl_days',width:1,hidden:true},
					{name:'rxl_quantity',index:'rxl_quantity',width:1,hidden:true},
					{name:'rxl_refill',index:'rxl_refill',width:1,hidden:true},
					{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true},
					{name:'id',index:'id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_medications_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Medications - Click on Date Active column to get previous prescription dates.",
			 	height: "100%",
			 	onCellSelect: function(id,iCol) {
					if (iCol == 1) {
						var med = jQuery("#messages_medications").getCell(id,'rxl_medication');
						$.ajax({
							type: "POST",
							url: "ajaxchart/past-medication",
							data: "rxl_medication=" + med,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.item, {sticky:true, header:data.header});
							}
						});
					}
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_medications_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#messages_medications_inactive").jqGrid('GridUnload');
			jQuery("#messages_medications_inactive").jqGrid({
				url:"ajaxcommon/medications-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Date Prescribed','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Days'],
				colModel:[
					{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
					{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_date_prescribed',index:'rxl_date_prescribed',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'rxl_medication',index:'rxl_medication',width:180},
					{name:'rxl_dosage',index:'rxl_dosage',width:50},
					{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
					{name:'rxl_sig',index:'rxl_sig',width:50},
					{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
					{name:'rxl_frequency',index:'rxl_frequency',width:75},
					{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
					{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
					{name:'rxl_days',index:'rxl_days',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_medications_inactive_pager'),
				sortname: 'rxl_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Inactive Medications",
			 	height: "100%",
			 	hiddengrid: true,
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_medications_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function (event,ui) {
			menu_update('medications');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	function prescribe_medication() {
		var str = $("#messages_edit_rx_form").serialize();
		$.ajax({
			type: "POST",
			url: "ajaxchart/prescribe-medication",
			data: str,
			dataType: "json",
			success: function(data){
				if(data.id) {
					$.jGrowl(data.message);
					reload_grid("messages_medications");
					reload_grid("medications");
					$('#prescribe_id').val(data.id);
					var old = $('#messages_rx_text').val();
					$('#messages_rx_text').val(old + '\n' + data.medtext);
					$('#prescribe_choice').html(data.med);
					$('#messages_edit_rx_form').clearForm();
					$("#messages_edit_rx_dialog").dialog('close');
					$("#rx_dialog_confirm").dialog("close");
					$('#messages_action_rx_dialog').dialog('open');
				} else {
					$.jGrowl(data.message);
				}
			}
		});
	}
	$("#messages_edit_rx_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 650, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#messages_rxl_medication").catcomplete({
				source: function (req, add){
					var term = req.term;
					if (term in medcache) {
						add(medcache[term]);
						return;
					}
					$.ajax({
						url: "ajaxsearch/rx-name/1",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								medcache[term] = data.message;
								add(data.message);
							}
						}
					});
				},
				minLength: 3,
				delay: 500,
				select: function(event, ui){
					if (ui.item.category == "Previously Prescribed") {
						$("#messages_rxl_dosage").val(ui.item.dosage);
						$("#messages_rxl_dosage_unit").val(ui.item.dosage_unit);
						$("#messages_rxl_ndcid").val(ui.item.rxl_ndcid);
						$("#messages_rxl_name").val('');
						$("#messages_rxl_form").val('');
					} else {
						$("#messages_rxl_dosage").val('');
						$("#messages_rxl_dosage_unit").val('');
						$("#messages_rxl_ndcid").val('');
						$("#messages_rxl_name").val(ui.item.name);
						$("#messages_rxl_form").val(ui.item.form);
					}
				}
			});
			$("#messages_rxl_dosage").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-dosage",
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
				minLength: 0,
				select: function(event, ui){
					$("#messages_rxl_dosage_unit").val(ui.item.unit);
					$.ajax({
						url: "ajaxsearch/rx-ndc-convert/" + ui.item.ndc,
						type: "POST",
						success: function(data){
							$("#messages_rxl_ndcid").val(data);
						}
					});
				}
			});
			$(".search_sig").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_sig",
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
				minLength: 1
			});
			$(".search_frequency").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_frequency",
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
				minLength: 1
			});
			$(".search_instructions").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_instructions",
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
			$(".search_reason").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rx-search/rxl_reason",
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
			$("#messages_rx_provider").removeOption(/./);
			$("#messages_rx_provider").addOption({'':'Choose Provider'});
			$.ajax({
				type: "POST",
				url: "ajaxsearch/provider-select",
				dataType: "json",
				success: function(data){
					$("#messages_rx_provider").addOption(data,false);
					if (noshdata.group_id == '2') {
						$("#messages_rx_provider").val(noshdata.user_id);
					}
				}
			});
		},
		buttons: {
			'Prescribe': function() {
				var bValid = true;
				$("#messages_edit_rx_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messages_edit_rx_form").serialize();
					if(str){
						$("#interactions_load").dialog('open');
						$.ajax({
							type: "POST",
							url: "ajaxchart/interactions-medication",
							data: str,
							dataType: "json",
							success: function(data){
								if (data.message == 'Allergies') {
									$("#rx_dialog_confirm_text").html(data.info);
									$("#rx_dialog_confirm").dialog("open");
								}
								if (data.message == 'Multiple') {
									$("#rx_dialog_confirm_text").html(data.info);
									$("#rx_dialog_confirm").dialog("open");
								}
								if (data.message == 'None') {
									prescribe_medication();
								}
							}
						});
						$("#interactions_load").ajaxStop(function(){
							$(this).dialog("close");
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#messages_edit_rx_form').clearForm();
				$("#messages_edit_rx_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#rx_dialog_confirm").dialog({
		resizable: false,
		height:400,
		width: 400,
		modal: true,
		bgiframe: true, 
		autoOpen: false, 
		draggable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			"Prescribe": function() {
				prescribe_medication();
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	function closerxorders() {
		$('#messages_edit_rx_form').clearForm();
		$("#messages_rx_text").val('');
		$("#messages_rx_eie_text").val('');
		$("#messages_rx_inactivate_text").val('');
		$("#messages_rx_reactivate_text").val('');
		$("#orders_rx_header").hide();
		$("#messages_rx_header").hide();
		$("#messages_rx_dialog").dialog('close');
	}
	$('#save_orders_rx').click(function(){
		var str = $("#messages_rx_main_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/orders-rx-save",
				data: str,
				success: function(data){
					$.jGrowl(data);
					closerxorders();
					checkorders();
				}
			});
		}
	});
	$("#cancel_orders_rx_helper").click(function() {
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a != '' || b != '' || c != '' || d != ''){
			if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
				closerxorders();
				return true;
			} else {
				return false;
			}
		} else {
			closerxorders();
		}	
	});
	$("#save_rx_helper").click(function() {
		var old = $("#t_messages_message").val();
		var old1 = old.trim();
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a){
			var a1 = 'PRESCRIBED MEDICATIONS:  ' + a + '\n\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'ENTERED MEDICATIONS IN ERROR:  ' + b + '\n\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'DISCONTINUED MEDICATIONS:  ' + c + '\n\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'REINSTATED MEDICATIONS:  ' + d + '\n\n';
		} else {
			var d1 = '';
		}
		if (old1 != '') {
			var e = old1+'\n\n'+a1+b1+c1+d1;
		} else {
			var e = +a1+b1+c1+d1;
		}
		$("#t_messages_message").val(e);
		closerxorders();
	});
	$("#cancel_rx_helper").click(function() {
		var a = $("#messages_rx_text").val();
		var b = $("#messages_rx_eie_text").val();
		var c = $("#messages_rx_inactivate_text").val();
		var d = $("#messages_rx_reactivate_text").val();
		if(a != '' || b != '' || c != '' || d != ''){
			if(confirm('Changes have not been saved.  Are you sure you want to close this window?  If not, press Cancel and press Import to save the form fields.')){ 
				closerxorders();
				return true;
			} else {
				return false;
			}
		} else {
			closerxorders();
		}
	});
	$('#save_oh_meds').click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/oh-save1/meds",
			success: function(data){
				$.jGrowl(data);
				$("#oh_meds_header").hide();
				$("#medications_list_dialog").dialog('close');
				check_oh_status();
			}
		});
	});
	$("#messages_rxl_date_prescribed").mask("99/99/9999");
	$("#messages_rxl_date_prescribed").datepicker();
	$("#messages_rxl_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#messages_rxl_route").selectOptions();
	$("#messages_rxl_dosage").focus(function(){
		var rx_name = $("#messages_rxl_name").val();
		if (rx_name != '') {
			rx_name = rx_name + ";" + $("#messages_rxl_form").val();
			$("#messages_rxl_dosage").autocomplete("search", rx_name);
		}
	});
	$("#messages_add_rx").click(function(){
		$('#messages_edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#messages_rxl_date_prescribed').val(currentDate);
		if (noshdata.group_id == '2') {
			$(".messages_rx_provider_div").hide();
		} else {
			$(".messages_rx_provider_div").show();
		}
		$('#messages_edit_rx_dialog').dialog('option', 'title', "Add Prescription");
		$('#messages_edit_rx_dialog').dialog('open');
		$('#messages_rxl_medication').focus();
	});
	$("#messages_edit_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			jQuery("#messages_medications").GridToForm(item,"#messages_edit_rx_form");
			var currentDate = getCurrentDate();
			$('#messages_rxl_date_prescribed').val(currentDate);
			if (noshdata.group_id == '2') {
				$(".messages_rx_provider_div").hide();
			} else {
				$(".messages_rx_provider_div").show();
			}
			$('#messages_edit_rx_dialog').dialog('option', 'title', "Refill Prescription");
			$('#messages_edit_rx_dialog').dialog('open');
			$('#messages_rxl_quantity').focus();
		} else {
			$.jGrowl("Please select medication to edit!");
		}
	});
	$("#messages_eie_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/eie-medication",
				data: "rxl_id=" + item,
				dataType: 'json',
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_eie_text').val();
					$('#messages_rx_eie_text').val(old + '\n' + data.medtext);
					$('#messages_action_rx_form').clearForm();
					$('#messages_action_rx_form').hide('fast');
					reload_grid("messages_medications");
					reload_grid("messages_medications_inactive");
					reload_grid("medications");
					reload_grid("medications_inactive");
				}
			});
		} else {
			$.jGrowl("Please select medication!");
		}
	});
	$("#messages_inactivate_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-medication",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_inactivate_text').val();
					$('#messages_rx_inactivate_text').val(old + '\n' + data.medtext);
					reload_grid("messages_medications");
					reload_grid("messages_medications_inactive");
					reload_grid("medications");
					reload_grid("medications_inactive");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!");
		}
	});
	$("#messages_delete_rx").click(function(){
		var item = jQuery("#messages_medications").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this medication?  This is not recommended unless entering the medication was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-medication",
					data: "rxl_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("messages_medications");
						reload_grid("messages_medications_inactive");
						reload_grid("medications");
						reload_grid("medications_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select medication to inactivate!");
		}
	});
	$("#messages_reactivate_rx").click(function(){
		var item = jQuery("#messages_medications_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/reactivate-medication",
				data: "rxl_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#messages_rx_reactivate_text').val();
					$('#messages_rx_reactivate_text').val(old + '\n' + data.medtext);
					reload_grid("messages_medications_inactive");
					reload_grid("messages_medications");
					reload_grid("medications");
					reload_grid("medications_inactive");
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$.extend({
		rx: {
			callback: function (result) {
				var id = result["references"][0]["id"];
				var a = $("#messages_rxl_medication_list").val();
				if (a == '') {
					$("#messages_rxl_medication_list").val(id);
				} else {
					$("#messages_rxl_medication_list").val(a + ',' + id);
				}
			}
		}
	});
	$("#messages_rx_fax_dialog").dialog({
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			jQuery("#messages_rx_fax_list").jqGrid('GridUnload');
			jQuery("#messages_rx_fax_list").jqGrid({
				url:"ajaxchart/rx-fax-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','File','Pages','Full Path'],
				colModel:[
					{name:'pages_id',index:'pages_id',width:1,hidden:true},
					{name:'file_original',index:'file_original',width:555},
					{name:'pagecount',index:'pagecount',width:100},
					{name:'file',index:'file',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_rx_fax_list_pager'),
				sortname: 'file_original',
				viewrecords: true,
				sortorder: "desc",
				caption:"Fax Queue",
				emptyrecords:"No pages",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_rx_fax_list_pager',{search:false,edit:false,add:false,del:false});
		},
		buttons: {
			'Send Fax Queue': function() {
				var medication = $("#prescribe_id");
				var id = $("#prescribe_id").val();
				$('#fax_prescribe_id').val(id);
				var bValid = true;
				bValid = bValid && checkEmpty(medication,"Prescription ID");
				$("#messages_rx_fax_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messages_rx_fax_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/send-fax-medication",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$('#messages_rx_fax_form').clearForm();
								$("#messages_rx_fax_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			'Save Fax Queue to Send Later': function() {
				$("#fax_prescribe_id").val('');
				$('#messages_rx_fax_dialog').dialog('close');
			},
			'Cancel Fax Queue': function() {
				$.ajax({
					type: "POST",
					url: "ajaxchart/cancel-fax-medication",
					success: function(data){
						$.jGrowl(data);
						$('#messages_rx_fax_form').clearForm();
						$('#messages_rx_fax_dialog').dialog('close');
					}
				});
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_print_medication").click(function(){
		var medication = $("#prescribe_id");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var prescribe_id = $("#prescribe_id").val();
			window.open("print_medication/" + prescribe_id);
		}
	});
	$("#messages_eprescribe_medication").click(function(){
	});
	$("#messages_fax_medication").click(function(){
		var medication = $("#prescribe_id");
		var bValid = true;
		bValid = bValid && checkEmpty(medication,"Prescription ID");
		if (bValid) {
			var id = $("#prescribe_id").val();
			$("#fax_prescribe_id").val(id);
			var str = $("#messages_rx_fax_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxchart/start-fax-medication",
					data: str,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.message);
						$('#messages_fax_id').html(data.id);
						$("#messages_rx_fax_dialog").dialog('open');
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_done_medication").click(function(){
		$('#messages_action_rx_form').clearForm();
		$('#messages_action_rx_dialog').dialog('close');
	});
	$("#messages_pharmacy_fax_number").mask("(999) 999-9999");
	$("#messages_pharmacy_name").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/pharmacy",
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
			$("#messages_pharmacy_fax_number").val(ui.item.fax);
		}
	});
	$("#messages_add_fax_contact").click(function(){
		var bValid = true;
		$("#messages_rx_fax_form").find("[required]").each(function() {
			var input_id = $(this).attr('id');
			var id1 = $("#" + input_id); 
			var text = $("label[for='" + input_id + "']").html();
			bValid = bValid && checkEmpty(id1, text);
		});
		if (bValid) {
			var str = $("#messages_rx_fax_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxchart/add-pharmacy",
					data: str,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_print_rx").click(function() {
		window.open("print_medication_list");
	});
	$("#messages_rx_fax_viewpage").click(function(){
		var click_id = jQuery("#messages_rx_fax_list").getGridParam('selrow');
		if(click_id){
			window.open("view_faxpage/" + click_id);
		}
	});
	$("#rcopia_orders_rx").button({icons: {primary: "ui-icon-link"}}).click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxchart/rcopia-update-medication/encounter",
			dataType: "json",
			success: function(data){
				if (data.response == 'Error connecting to DrFirst RCopia.  Try again later.' || data.response == 'No updated prescriptions.') {
					$.jGrowl(data.response);
				} else {
					$.jGrowl(data.response);
					var old = $('#messages_rx_text').val();
					$('#messages_rx_text').val(old + '\n' + data.medtext);
					reload_grid("messages_medications");
					reload_grid("medications");
				}
			}
		});
	});
	$("#rcopia_rx_helper").button({icons: {primary: "ui-icon-link"}}).click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxchart/rcopia-update-medication/message",
			dataType: "json",
			success: function(data){
				if (data.response == 'Error connecting to DrFirst RCopia.  Try again later.' || data.response == 'No updated prescriptions.') {
					$.jGrowl(data.response);
				} else {
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
					$("#t_messages_message").val(old1+data.medtext);
					$('#messages_edit_rx_form').hide('fast');
					$('#messages_edit_rx_form').clearForm();
					$("#messages_rx_text").val('');
					$("#messages_rx_eie_text").val('');
					$("#messages_rx_inactivate_text").val('');
					$("#messages_rx_reactivate_text").val('');
					$("#messages_rx_dialog").dialog('close');
				}
			}
		});
	});
});
