$(document).ready(function() {
	loadbuttons();
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-orders",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				$("#orders_form :input[name='" + key + "']").val(value);
				$("#orders_"+key+"_old").val(value);
			});
		}
	});
	$("#print_orders").click(function(){
		window.open("print_plan");
	});
	$("#instructions_dialog_load1").dialog({
		height: 100,
		width: 350,
		autoOpen: false,
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#orders_plan_instructions_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 350,
		width: 800,
		modal: true,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#instructions_dialog_load1").dialog('open');
			$.ajax({
				url: "ajaxsearch/vivacare-data",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#instructions_dialog_load1").dialog("close");
					if(data.response =='true'){
						var cache = data.message;
						$("#instructions_chosen").catcomplete({
							source: function (req, add) {
								add($.ui.autocomplete.filter(cache, extractLast( req.term )));
							},
							search: function() {
								var term = extractLast( this.value );
								if ( term.length < 2 ) {
									return false;
								}
							},
							focus: function() {
								return false;
							},
							select: function(event, ui){
								var terms = split( this.value );
								terms.pop();
								terms.push( ui.item.value );
								terms.push( "" );
								this.value = terms.join( "\n" );
								$("#instructions_dialog_load1").dialog('open');
								$.ajax({
									type: "POST",
									url: "ajaxchart/print-vivacare/" + ui.item.link,
									dataType: "json",
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
													$("#instructions_dialog_load1").dialog('close');
													$("#documents_view_dialog").dialog('open');
												}
											});
										} else {
											$.jGrowl(data.message);
										}
									}
								});
								return false;
							}
						}).attr('placeholder','Type in terms for Vivacare Patient Education Handouts');
					}
				}
			});
		},
		buttons: {
			'Save': function() {
				var b = $("#instructions_chosen").val();
				if (b !== '') {
					var old = $("#orders_plan").val();
					var old1 = '';
					if(old){
						var pos = old.lastIndexOf('\n');
						if (pos == -1) {
							old1 = old + '\n';
						} else {
							var a = old.slice(pos);
							if (a === '') {
								old1 = old;
							} else {
								old1 = old + '\n';
							}
						}
					}
					var intro = 'Patient Instructions Given: ';
					$("#orders_plan").val(old1+intro+b);
				}
				$("#instructions_chosen").val('');
				$("#orders_plan_instructions_dialog").dialog('close');
			},
			Cancel: function() {
				$("#instructions_chosen").val('');
				$("#orders_plan_instructions_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#orders_plan").focus();
	$('#orders_plan_reset').click(function(){
		$("#orders_plan").val('');
	});
	$('#orders_plan_instructions').click(function(){
		$("#orders_plan_instructions_dialog").dialog('open');
	});
	$("#encounter_letter").click(function() {
		$("#letter_dialog").dialog('open');
		$("#letter_eid").val('1');
	});
	$('#orders_duration_reset').click(function(){
		$("#orders_duration").val('');
	});
	$('#orders_followup_reset').click(function(){
		$("#orders_followup").val('');
	});
	$("#button_orders_labs").click(function() {
		$("#edit_message_lab_form").show();
		$("#save_lab_helper_label").html('Close Helper');
		$("#messages_lab_t_messages_id").val('');
		$("#messages_lab_origin").val('encounter');
		$("#messages_lab_header").show();
		reload_grid("messages_lab_list");
		$("#messages_lab_dialog").dialog('open');
	});
	$('.orders_tooltip').tooltip({
		items: ".orders_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		position: { my: "right+15 bottom", at: "left top", collision: "flipfit" },
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[2];
			$.ajax({
				type: "POST",
				url: "ajaxencounter/tip-orders/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "right+15 bottom", at: "left top", collision: "flipfit" });
				},
			});
		}
	}).click(function(){
		var id = $(this).attr("id");
		var parts = id.split('_');
		var id1 = parts[2];
		$.ajax({
			type: "POST",
			url: "ajaxencounter/tip-edit-orders/" + id1,
			dataType: 'json',
			success: function(data) {
				if (data.type != "N") {
					$("#edit_orders_dialog_text").val(data.text);
					$("#edit_orders_dialog_text").attr("name",data.type);
					$("#edit_orders_dialog").dialog('open');
				}
			},
		});
	});
	$("#edit_orders_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 200,
		width: 500,
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var a = $("#edit_orders_dialog_text");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Text Value");
				if (bValid) {
					var str = $("#edit_orders_form").serialize();
					if(str){
						var b = $("#edit_orders_dialog_text").attr("name");
						$.ajax({
							type: "POST",
							url: "ajaxencounter/edit-tip-orders/" + b,
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#edit_orders_dialog_text").val('').attr("name","");
								$("#edit_orders_dialog").dialog('close');
								checkorders();
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#edit_orders_dialog_text").val('').attr("name","");
				$("#edit_orders_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_orders_rad").click(function() {
		$("#edit_message_rad_form").show();
		$("#save_rad_helper_label").html('Close Helper');
		$("#messages_rad_t_messages_id").val('');
		$("#messages_rad_origin").val('encounter');
		$("#messages_rad_header").show();
		reload_grid("messages_rad_list");
		$("#messages_rad_dialog").dialog('open');
	});
	$("#button_orders_cp").click(function() {
		$("#edit_message_cp_form").show();
		$("#save_cp_helper_label").html('Close Helper');
		$("#messages_cp_t_messages_id").val('');
		$("#messages_cp_origin").val('encounter');
		$("#messages_cp_header").show();
		reload_grid("messages_cp_list");
		$("#messages_cp_dialog").dialog('open');
	});
	$("#button_orders_ref").click(function() {
		$("#edit_message_ref_form").show();
		$("#save_ref_helper_label").html('Close Helper');
		$("#messages_ref_t_messages_id").val('');
		$("#messages_ref_origin").val('encounter');
		$("#messages_ref_header").show();
		reload_grid("messages_ref_list");
		$("#messages_ref_dialog").dialog('open');
	});
	$("#button_orders_rx").click(function() {
		$("#messages_rx_dialog").dialog('open');
		$("#orders_rx_header").show();
		$("#messages_rx_header").hide();
	});
	$("#button_orders_supplements").click(function() {
		$("#supplement_origin_orders").val("Y");
		$("#supplement_origin_orders1").val("Y");
		$("#supplements_list_dialog").dialog('open');
		$("#orders_supplements_header").show();
		$("#orders_supplements").focus();
	});
	$("#button_orders_imm").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$("#orders_imm_header").show();
		$('#edit_immunization_form').hide();
		$('#imm_order').show();
		$('#imm_menu').hide();
	});
	$("#encounter_mtm").click(function() {
		$("#mtm_dialog").dialog('open');
		$("#mtm_origin").val('encounter');
	});
	$("#orders_schedule").click(function() {
		open_schedule();
	});
	setInterval(orders_autosave, 10000);
	swipe();
});
