$(document).ready(function() {
	$("#immunizations_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#immunizations").jqGrid('GridUnload');
			jQuery("#immunizations").jqGrid({
				url:"ajaxchart/immunizations",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Given','Immunization','Sequence','Given Elsewhere','Body Site','Dosage','Unit','Route','Lot Number','Manufacturer','Expiration Date','VIS'],
				colModel:[
					{name:'imm_id',index:'imm_id',width:1,hidden:true},
					{name:'imm_date',index:'imm_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'imm_immunization',index:'imm_immunization',width:410},
					{name:'imm_sequence',index:'imm_sequence',width:65},
					{name:'imm_elsewhere',index:'imm_elsewhere',width:150},
					{name:'imm_body_site',index:'imm_body_site',width:1,hidden:true},
					{name:'imm_dosage',index:'imm_dosage',width:1,hidden:true},
					{name:'imm_dosage_unit',index:'imm_dosage_unit',width:1,hidden:true},
					{name:'imm_route',index:'imm_route',width:1,hidden:true},
					{name:'imm_lot',index:'imm_lot',width:1,hidden:true},
					{name:'imm_manufacturer',index:'imm_manufacturer',width:1,hidden:true},
					{name:'imm_expiration',index:'imm_expiration',width:1,hidden:true},
					{name:'imm_vis',index:'imm_vis',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#immunizations_pager'),
				sortname: 'imm_immunization',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Immunizations",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#immunizations_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-imm-notes",
				success: function(data){
					$('#imm_notes_div').html(data);
				}
			});
		},
		close: function(event, ui) {
			$('#edit_immunization_form').clearForm();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#immunizations_vis_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 600, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".immunizations_list").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$('#orders_imm_header').hide();
		$('#imm_order').hide();
		$('#imm_menu').show();
	});
	$(".class_imm_date").mask("99/99/9999").datepicker();
	$(".class_imm_expiration").mask("99/99/9999").datepicker();
	$(".class_imm_route").addOption({"":"","intramuscularly":"IM","subcutaneously":"SC","by mouth":"PO","intravenously":"IV"}, false).selectOptions();
	$(".class_imm_sequence").addOption({"":"","1":"First","2":"Second","3":"Third","4":"Fourth","5":"Fifth"}, false).selectOptions();
	$(".class_imm_body_site").addOption({"Right Deltoid":"Right Deltoid","Left Deltoid":"Left Deltoid","Right Thigh":"Right Thigh","Left Thigh":"Left Thigh"}, false).selectOptions();
	$("#imm_immunization1").click(function(){
		$("#imm_immunization1").autocomplete("search", " ");
	});
	$("#add_immunization").click(function(){
		$('#edit_immunization_form').clearForm();
		var currentDate = getCurrentDate();
		$('#imm_date').val(currentDate);
		$('#edit_immunization_dialog').dialog('option', 'title', "Add Immunization");
		$('#edit_immunization_dialog').dialog('open');
		$("#imm_immunization").focus();
	});
	$("#edit_immunization").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			jQuery("#immunizations").GridToForm(item,"#edit_immunization_form");
			var date = $('#imm_date').val();
			var edit_date = editDate(date);
			$('#imm_date').val(edit_date);
			var expiration = $('#imm_expiration').val();
			var edit_expiration = editDate1(expiration);
			$('#imm_expiration').val(edit_expiration);
			$('#edit_immunization_dialog').dialog('option', 'title', "Edit Immunization");
			$('#edit_immunization_dialog').dialog('open');
			$("#imm_immunization").focus();
		} else {
			$.jGrowl("Please select immunization to edit!")
		}
	});
	$("#delete_immunization").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this immunization?')){
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-immunization",
					data: "imm_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("immunizations");
					}
				});
			}
		} else {
			$.jGrowl("Please select immunization to delete!")
		}
	});
	$("#add_immunization1").click(function(){
		$('#edit_immunization_form1').clearForm();
		var currentDate = getCurrentDate();
		$('#imm_date1').val(currentDate);
		$('#edit_immunization_dialog1').dialog('option', 'title', "Add Immunization");
		$('#edit_immunization_dialog1').dialog('open');
		$("#imm_immunization1").focus();
	});
	$("#edit_immunization1").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			jQuery("#immunizations").GridToForm(item,"#edit_immunization_form1");
			var date = $('#imm_date1').val();
			var edit_date = editDate(date);
			$('#imm_date1').val(edit_date);
			$('#edit_immunization_dialog1').dialog('option', 'title', "Edit Immunization");
			$('#edit_immunization_dialog1').dialog('open');
			$("#imm_immunization1").focus();
		} else {
			$.jGrowl("Please select immunization to edit!")
		}
	});
	$("#delete_immunization1").click(function(){
		var item = jQuery("#immunizations").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this immunization?')){
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-immunization",
					data: "imm_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid("immunizations");
					}
				});
			}
		} else {
			$.jGrowl("Please select immunization to delete!")
		}
	});
	$("#vis_immunization1").click(function(){
		$("#immunizations_vis_dialog").dialog('open');
	});
	$("#consent_immunization1").click(function(){
		var item = $("#consent_vaccine_list").val();
		if(item) {
			$.ajax({
				type: "POST",
				url: "ajaxchart/consent-immunizations",
				data: "vaccine_list=" + item,
				async: false,
				success: function(data){
					if (data == "OK") {
						noshdata.success_doc = true;
					} else {
						$.jGrowl(data);
					}
				}
			});
			if (noshdata.success_doc == true) {
				window.open("print_consent");
				noshdata.success_doc = false;
			}
		} else {
			$.jGrowl("Please click at least one vaccine information sheet!")
		}
	});
	$("#vis_dtap").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'DTaP';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hep_a").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hepatitis A';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hep_b").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hepatitis B';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_hib").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Hib';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$(".vis_hpv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'HPV';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_flulive").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Flu (intranasal, live)';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_flu").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Flu (inactivated)';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_mmr").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'MMR';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_mening").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Meningococcal';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_pcv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'PCV13';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_ppv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Pneumococcal';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_ipv").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Polio';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_rotavirus").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Rotavirus';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_shingles").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Shingles';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_tdap").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Tdap';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_td").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Td';
		$("#consent_vaccine_list").val(old+a+b);
	});
	$("#vis_varicella").click(function(){
		var old = $("#consent_vaccine_list").val();
		if (old != '') {
			var a = ', ';
		} else {
			var a = '';
		}
		var b = 'Varicella';
		$("#consent_vaccine_list").val(old+a+b);
	});
	
	$("#edit_immunization_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#imm_immunization").autocomplete({
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
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_immunization_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_immunization_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-immunization",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								reload_grid("immunizations");
								reload_grid("nosh_imm");
								$('#edit_immunization_form').clearForm();
								$('#edit_immunization_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_immunization_form').clearForm();
				$('#edit_immunization_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#edit_immunization_dialog1").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#imm_immunization1").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/imm1",
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
					if (ui.item.value != '') {
						$("#imm_cpt").val(ui.item.cpt);
						var edit_date = editDate1(ui.item.expiration);
						$("#imm_expiration1").val(edit_date);
						$("#imm_manufacturer1").val(ui.item.manufacturer);
						$("#imm_lot1").val(ui.item.lot);
						$("#imm_cvxcode1").val(ui.item.cvx);
						$("#imm_vaccine_id").val(ui.item.vaccine_id);
					}
				}
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_immunization_form1").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_immunization_form1").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-immunization1",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								reload_grid("immunizations");
								var old = $('#imm_text').val();
								$('#imm_text').val(old + '\n' + data.medtext);
								$('#edit_immunization_form1').clearForm();
								$('#edit_immunization_dialog1').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_immunization_form1').clearForm();
				$('#edit_immunization_dialog1').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('#save_orders_imm').click(function(){
		var a = $("#imm_text").val();
		if(a){
			var a1 = a + '\n\n';
		} else {
			var a1 = '';
		}
		$.ajax({
			type: "POST",
			url: "ajaxencounter/orders-imm-save",
			data: "rx_immunizations=" + a1,
			success: function(data){
				$.jGrowl(data);
				$("#orders_imm_header").hide();
				$("#imm_text").val('');
				$('#imm_order').hide();
				$('#imm_menu').hide();
				$("#immunizations_list_dialog").dialog('close');
				checkorders();
			}
		});
	});
	$("#cancel_orders_imm_helper").click(function() {
		$("#orders_imm_header").hide('fast');
		$('#edit_immunization_form1').clearForm();
		$("#imm_text").val('');
		$('#imm_order').hide('fast');
		$('#imm_menu').hide('fast');
		$("#immunizations_list_dialog").dialog('close');
	});
	$("#print_immunizations").click(function() {
		window.open("print_immunization_list");
	});
	$("#print_immunizations1").click(function() {
		window.open("print_immunization_list");
	});
	$("#csv_immunizations").click(function() {
		window.open("csv_immunization");
	});
	$("#csv_immunizations1").click(function() {
		window.open("csv_immunization");
	});
	$("#imm_notes_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 600, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$('#imm_notes_form').clearForm();
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-imm-notes1",
				success: function(data){
					$('#imm_notes').val(data.trim());
					$('#imm_notes_old').val(data.trim())
				}
			});
		},
		buttons: {
			'Save': function() {
				var str = $("#imm_notes_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxchart/edit-imm-notes",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$.ajax({
								type: "POST",
								url: "ajaxchart/get-imm-notes",
								success: function(data){
									$('#imm_notes_div').html(data);
								}
							});
							$('#imm_notes_form').clearForm();
							$('#imm_notes_dialog').dialog('close');
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$('#imm_notes_form').clearForm();
				$('#imm_notes_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#imm_notes_button").click(function(){
		$('#imm_notes_dialog').dialog('open');
	});
});
