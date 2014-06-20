$(document).ready(function() {
	$("#supplements_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			var a = $("#supplement_origin_orders").val();
			if (a == "N") {
				$("#edit_sup").attr("value", "Edit Supplement");
				$("#orders_supplements_header").hide();
				$("#messages_supplements_header").hide();
			}
			jQuery("#supplements").jqGrid('GridUnload');
			jQuery("#supplements").jqGrid({
				url:"ajaxcommon/supplements",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Supplement','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','Supplement ID','Provider ID'],
				colModel:[
					{name:'sup_id',index:'sup_id',width:1,hidden:true},
					{name:'sup_date_active',index:'sup_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_supplement',index:'sup_supplement',width:260},
					{name:'sup_dosage',index:'sup_dosage',width:50},
					{name:'sup_dosage_unit',index:'sup_dosage_unit',width:50},
					{name:'sup_sig',index:'sup_sig',width:50},
					{name:'sup_route',index:'sup_route',width:1,hidden:true},
					{name:'sup_frequency',index:'sup_frequency',width:205},
					{name:'sup_instructions',index:'sup_instructions',width:1,hidden:true},
					{name:'sup_reason',index:'sup_reason',width:1,hidden:true},
					{name:'supplement_id',index:'supplement_id',width:1,hidden:true},
					{name:'id',index:'id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#supplements_pager'),
				sortname: 'sup_date_active',
				viewrecords: true,
				sortorder: "desc",
				caption:"Supplements",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#supplements_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#supplements_inactive").jqGrid('GridUnload');
			jQuery("#supplements_inactive").jqGrid({
				url:"ajaxcommon/supplements-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Supplement','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason',"Supplement ID"],
				colModel:[
					{name:'sup_id',index:'sup_id',width:1,hidden:true},
					{name:'sup_date_active',index:'sup_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'sup_supplement',index:'sup_supplement',width:260},
					{name:'sup_dosage',index:'sup_dosage',width:50},
					{name:'sup_dosage_unit',index:'sup_dosage_unit',width:50},
					{name:'sup_sig',index:'sup_sig',width:50},
					{name:'sup_route',index:'sup_route',width:1,hidden:true},
					{name:'sup_frequency',index:'sup_frequency',width:205},
					{name:'sup_instructions',index:'sup_instructions',width:1,hidden:true},
					{name:'sup_reason',index:'sup_reason',width:1,hidden:true},
					{name:'supplement_id',index:'supplement_id',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#supplements_inactive_pager'),
				sortname: 'sup_date_active',
				viewrecords: true,
				sortorder: "desc",
				caption:"Inactive Medications",
				height: "100%",
				hiddengrid: true,
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#supplements_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_sup_form').clearForm();
			$("#edit_sup").attr("value", "Reorder Supplement");
			$("#orders_supplements_header").hide();
			$("#messages_supplements_header").hide();
			$("#oh_supplements_header").hide();
			$("#supplement_origin_orders").val("N");
			$("#supplement_origin_orders1").val("N");
			if (noshdata.group_id != '100') {
				menu_update('supplements');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".supplements_list").click(function() {
		$("#supplement_origin_orders").val("N");
		$("#supplement_origin_orders1").val("N");
		$("#supplements_list_dialog").dialog('open');
		$("#edit_sup").attr("value", "Edit Supplement");
		$("#oh_supplements_header").hide();
		$("#orders_supplements_header").hide();
	});
	$("#dashboard_supplements").click(function() {
		$("#supplement_origin_orders").val("N");
		$("#supplement_origin_orders1").val("N");
		$("#supplements_list_dialog").dialog('open');
		$("#edit_sup").attr("value", "Edit Supplement");
		$("#oh_supplements_header").hide();
		$("#orders_supplements_header").hide();
	});
	$("#edit_sup_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 650, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#sup_supplement").catcomplete({
				source: function (req, add){
					var a = $("#supplement_origin_orders").val();
					$.ajax({
						url: "ajaxsearch/supplements/" + a,
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
				select: function(event, ui) {
					$("#sup_dosage").val(ui.item.dosage);
					$("#sup_dosage_unit").val(ui.item.dosage_unit);
					$("#supplement_id").val(ui.item.supplement_id);
				}
			});
			$("#sup_dosage").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-dosage",
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
					$("#sup_dosage_unit").val(ui.item.unit);
				}
			});
			$("#sup_sig").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-sig",
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
			$("#sup_frequency").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-frequency",
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
			$("#sup_instructions").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-instructions",
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
			$("#sup_reason").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/sup-reason",
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
			$("#messages_sup_provider").removeOption(/./);
			$("#messages_sup_provider").addOption({'':'Choose Provider'});
			$.ajax({
				type: "POST",
				url: "ajaxsearch/provider-select",
				dataType: "json",
				success: function(data){
					$("#messages_sup_provider").addOption(data,false);
					if (noshdata.group_id == '2') {
						$("#messages_sup_provider").val(noshdata.user_id);
					}
				}
			});
			var a = $("#supplement_origin_orders").val();
			if (a == "N") {
				$(this).siblings('.ui-dialog-buttonpane').find('button').eq(0).hide();
			} else {
				$(this).siblings('.ui-dialog-buttonpane').find('button').eq(0).show();
			}
		},
		buttons: {
			'Purchase': function() {
				var bValid = true;
				$("#edit_sup_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var a = $("#supplement_id").val();
					if(a) {
						$.ajax({
							type: "POST",
							url: "ajaxchart/check-supplement-inventory",
							data: "supplement_id=" + a,
							success: function(data){
								if (data == "OK") {
									$("#supplement_inventory_dialog").dialog('open');
								} else {
									$.jGrowl(data);
								}
							}
						});
						
					} else {
						$.jGrowl("Ensure that the supplement chosen is in the inventory!");
					}
				}
			},
			'Save': function() {
				var bValid = true;
				$("#edit_sup_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_sup_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-supplement/N",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								var old = $('#supplement_text').val();
								$('#supplement_text').val(old + '\n' + data.medtext);
								$('#review_orders_supplements').html($('#supplement_text').val() + "\n" + $('#supplement_text1').val());
								reload_grid("supplements");
								$('#edit_sup_form').clearForm();
								$('#edit_sup_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_sup_form').clearForm();
				$('#edit_sup_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#sup_date_active").mask("99/99/9999");
	$("#sup_date_active").datepicker();
	$("#sup_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#sup_route").selectOptions();
	$("#sup_dosage").click(function(){
		var sup_name = $("#sup_supplement").val();
		if (sup_name == '') {
			$.jGrowl('Supplement field empty!');
		} else {
			$("#sup_dosage").autocomplete("search", sup_name);
		}
	});
	$("#add_sup").click(function(){
		$('#edit_sup_form').clearForm();
		var currentDate = getCurrentDate();
		$('#sup_date_active').val(currentDate);
		$('#edit_sup_dialog').dialog('option', 'title', "Add Supplement");
		$('#edit_sup_dialog').dialog('open');
		$("#sup_supplement").focus();
	});
	$("#edit_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			jQuery("#supplements").GridToForm(item,"#edit_sup_form");
			var date = $('#sup_date_active').val();
			var edit_date = editDate(date);
			$('#sup_date_active').val(edit_date);
			$('#edit_sup_dialog').dialog('option', 'title', "Edit Supplement");
			$('#edit_sup_dialog').dialog('open');
			$("#sup_supplement").focus();
		} else {
			$.jGrowl("Please select supplement to edit!")
		}
	});
	$("#inactivate_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-supplement",
				data: "sup_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#supplement_inactivate_text').val();
					$('#supplement_inactivate_text').val(old + '\n' + data.medtext);
					reload_grid("supplements");
					reload_grid("supplements_inactive");
				}
			});
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#delete_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this supplement?  This is not recommended unless entering the supplement was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-supplement",
					data: "sup_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("supplements");
						reload_grid("supplements_inactive");
					}
				});
			}
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#reactivate_sup").click(function(){
		var item = jQuery("#supplements_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/reactivate-supplement",
				data: "sup_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#supplement_reactivate_text').val();
					$('#supplement_reactivate_text').val(old + '\n' + data.medtext);
					reload_grid("supplements_inactive");
					reload_grid("supplements");
				}
			});
		} else {
			$.jGrowl("Please select supplement to reactivate!")
		}
	});
	$("#search_db_supplement").click(function(){
		window.open("http://www.dsld.nlm.nih.gov/dsld/");
	});
	$("#supplement_inventory_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var a = $("#supplement_inventory_dialog_amount");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Quantity");
				if (bValid) {
					var b = $("#supplement_inventory_dialog_amount").val();
					$("#sup_amount").val(b);
					$("#supplement_inventory_dialog_amount").val('');
					$("#supplement_inventory_dialog").dialog('close');
					var str = $("#edit_sup_form").serialize();
					if(str){
						var c = $("#supplement_origin_orders1").val();
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-supplement/" + c,
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								var old = $('#supplement_text1').val();
								$('#supplement_text1').val(old + '\n' + data.medtext);
								reload_grid("supplements");
								$('#edit_sup_form').clearForm();
								$('#edit_sup_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#supplement_inventory_dialog_amount").val('');
				$("#supplement_inventory_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('#save_orders_supplements').click(function(){
		var str = $("#messages_supplements_main_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "ajaxchart/orders-sup-save",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$("#messages_supplements_main_form").clearForm();
					$("#supplement_origin_orders").val('');
					$("#orders_supplements_header").hide();
					$("#supplements_list_dialog").dialog('close');
					checkorders();
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_orders_supplements_helper").click(function() {
		$("#messages_supplements_main_form").clearForm();
		$("#supplement_origin_orders").val('');
		$("#orders_supplements_header").hide();
		$("#supplements_list_dialog").dialog('close');
	});
	$('#save_orders_supplements1').click(function(){
		var old = $("#t_messages_message").val();
		var old1 = old.trim();
		var a = $("#supplement_text").val();
		var b = $("#supplement_text1").val();
		var c = $("#supplement_inactivate_text").val();
		var d = $("#supplement_reactivate_text").val();
		if(a){
			var a1 = 'SUPPLEMENTS ADVISED:  ' + a + '\n\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'SUPPLEMENTS PURCHASED BY PATIENT:  ' + b + '\n\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'DISCONTINUED SUPPLEMENTS:  ' + c + '\n\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'REINSTATED SUPPLEMENTS:  ' + d + '\n\n';
		} else {
			var d1 = '';
		}
		if (old1 != '') {
			var e = old1+'\n\n'+a1+b1+c1+d1;
		} else {
			var e = a1+b1+c1+d1;
		}
		$("#t_messages_message").val(e);
		$("#messages_supplements_main_form").clearForm();
		$("#supplement_origin_orders").val('');
		$("#messages_supplements_header").hide();
		$("#supplements_list_dialog").dialog('close');
	});
	$("#cancel_orders_supplements_helper1").click(function() {
		$("#messages_supplements_main_form").clearForm();
		$("#supplement_origin_orders").val('');
		$("#messages_supplements_header").hide();
		$("#supplements_list_dialog").dialog('close');
	});
	$('#save_oh_supplements').click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/oh-save1/supplements",
			success: function(data){
				$.jGrowl(data);
				$("#supplement_origin_orders").val('');
				$("#oh_supplements_header").hide('fast');
				$("#supplements_list_dialog").dialog('close');
				check_oh_status();
			}
		});
	});
});
