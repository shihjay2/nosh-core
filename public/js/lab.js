$(document).ready(function() {
	$("#messages_lab_accordion").accordion({
		heightStyle: "content" ,
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#messages_lab_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#messages_lab_accordion").accordion("option", "active");
					if (active < 4) {
						$("#messages_lab_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#messages_lab_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_lab_list").jqGrid('GridUnload');
			jQuery("#messages_lab_list").jqGrid({
				url: "ajaxchart/orders-list/labs",
				postData: {t_messages_id: function(){return $("#messages_lab_t_messages_id_origin").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Obtained','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_labs',index:'orders_labs',width:300},
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
				pager: jQuery('#messages_lab_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Lab Orders",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_lab_list_pager',{search:false,edit:false,add:false,del:false});
		},
		buttons: {
			'Save': function() {
				var origin = $("#messages_lab_origin").val();
				if (origin == 'message') {
					var id = $("#t_messages_id").val();
					$.ajax({
						type: "POST",
						url: "ajaxchart/import-orders/labs",
						data: "t_messages_id=" + id,
						success: function(data){
							var old = $("#t_messages_message").val();
							var old1 = old.trim();
							var a = '';
							if(data !== ''){
								if (old1 !== '') {
									a = old1+'\n\n'+data;
								} else {
									a = data;
								}
								$("#t_messages_message").val(a);
							}
						}
					});
				} else {
					checkorders();
				}
				$("#messages_lab_origin").val('');
				$("#messages_lab_t_messages_id_origin").val('');
				$("#messages_lab_dialog").dialog('close');
			},
			Cancel: function() {
				$("#messages_lab_origin").val('');
				$("#messages_lab_t_messages_id_origin").val('');
				$("#messages_lab_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_add_lab").click(function(){
		load_outside_providers('lab','add');
		var a = $("#messages_lab_t_messages_id_origin").val();
		if (a === '') {
			$("#messages_lab_eid").val(noshdata.eid);
		} else {
			$("#messages_lab_t_messages_id").val(a);
		}
		$("#messages_lab_status").html('');
		$("#messages_lab_location").val('');
		if ($("#messages_lab_provider_list").val() === '' && noshdata.group_id === '2') {
			$("#messages_lab_provider_list").val(noshdata.user_id);
		}
		var currentDate = getCurrentDate();
		$('#messages_lab_orders_pending_date').val(currentDate);
		$("#messages_lab_edit_fields").dialog("option", "title", "Add Lab Order");
		$("#messages_lab_edit_fields").dialog('open');
	});
	$("#messages_edit_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			load_outside_providers('lab','edit');
			jQuery("#messages_lab_list").GridToForm(item,"#edit_messages_lab_form");
			var status = 'Details for Lab Order #' + item;
			$("#messages_lab_status").html(status);
			if ($("#messages_lab_provider_list").val() === '' && noshdata.group_id === '2') {
				$("#messages_lab_provider_list").val(noshdata.user_id);
			}
			var date = $('#messages_lab_orders_pending_date').val();
			var edit_date = editDate1(date);
			$('#messages_lab_orders_pending_date').val(edit_date);
			var a = $("#messages_lab_t_messages_id_origin").val();
			if (a === '') {
				$("#messages_lab_eid").val(noshdata.eid);
			} else {
				$("#messages_lab_t_messages_id").val(a);
			}
			$("#messages_lab_edit_fields").dialog("option", "title", "Edit Lab Order");
			$("#messages_lab_edit_fields").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			var id = $("#messages_lab_list").getCell(item,'orders_id');
			$("#messages_lab_orders_id").val(id);
			$('#messages_lab_choice').html("Choose an action for the lab order, reference number " + id);
			$("#messages_lab_action_dialog").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_delete_lab").click(function(){
		var item = jQuery("#messages_lab_list").getGridParam('selrow');
		if(item){
			var id = $("#messages_lab_list").getCell(item,'orders_id');
			$.ajax({
				url: "ajaxchart/delete-orders/Laboratory",
				type: "POST",
				data: "orders_id=" + id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("messages_lab_list");
				}
			});
		} else {
			$.jGrowl("Please select order to delete!");
		}
	});
	$("#messages_lab_edit_fields").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 580,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_lab_insurance_grid").jqGrid('GridUnload');
			jQuery("#messages_lab_insurance_grid").jqGrid({
				url: "ajaxdashboard/insurance",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:350},
					{name:'insurance_id_num',index:'insurance_id_num',width:100},
					{name:'insurance_group',index:'insurance_group',width:100},
					{name:'insurance_order',index:'insurance_order',width:105},
					{name:'insurance_insu_lastname',index:'insurance_insu_lastname',width:1,hidden:true},
					{name:'insurance_insu_firstname',index:'insurance_insu_firstname',width:1,hidden:true},
					{name:'insurance_insu_dob',index:'insurance_insu_dob',width:1,hidden:true},
					{name:'insurance_insu_gender',index:'insurance_insu_gender',width:1,hidden:true},
					{name:'insurance_insu_address',index:'insurance_insu_address',width:1,hidden:true},
					{name:'insurance_insu_city',index:'insurance_insu_city',width:1,hidden:true},
					{name:'insurance_insu_state',index:'insurance_insu_state',width:1,hidden:true},
					{name:'insurance_insu_zip',index:'insurance_insu_zip',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_lab_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Click to select insurance for lab order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_lab_insurance_grid").getCell(id,'insurance_insu_firstname');
					var address_id = jQuery("#messages_lab_insurance_grid").getCell(id,'address_id');
					$.ajax({
						url: "ajaxsearch/payor-id/" + address_id,
						type: "POST",
						success: function(data){
							var text = insurance_plan_name + '; Payor ID: ' + data + '; ID: ' + insurance_id_num;
							if(insurance_group !== ''){
								text += "; Group: " + insurance_group;
							}
							text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
							var old = $("#messages_lab_insurance").val();
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
							$("#messages_lab_insurance").val(old1+text);
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_lab_insurance_pager',{search:false,edit:false,add:false,del:false});
			var currentTime = getCurrentTime();
			var currentDate = getCurrentDate();
			$("#messages_lab_time_obtained").val(currentTime);
			$("#messages_lab_date_obtained").val(currentDate);
			if (noshdata.group_id == '2') {
				$(".nosh_provider_exclude").hide();
			} else {
				$(".nosh_provider_exclude").show();
			}
			$("#messages_lab_codes").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/icd",
						dataType: "json",
						type: "POST",
						data: "term=" + extractLast(req.term),
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							}
						}
					});
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
					return false;
				}
			});
			$("#messages_lab_orders").catcomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/lab",
						dataType: "json",
						type: "POST",
						data: "term=" + extractLast(req.term),
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							} else {
								var addterm = [{"label": extractLast(req.term) + ": Select to add order to database.", "value":"*/add/*", "value1": extractLast(req.term), "category":"New Item"}];
								add(addterm);
							}
						}
					});
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
					if (ui.item.value == "*/add/*") {
						$("#messages_lab").val(ui.item.value1);
						$("#messages_lab_orders_text").val(this.value);
						$("#add_test_cpt").dialog('open');
					} else {
						if (!ui.item.aoe_code) {
							var terms = split( this.value );
							terms.pop();
							terms.push( ui.item.value );
							terms.push( "" );
							this.value = terms.join( "\n" );
							return false;
						} else {
							var aoe_code = ui.item.aoe_code;
							var aoe_field = ui.item.aoe_field;
							if (aoe_code.indexOf(";") > -1) {
								var codes = aoe_code.split(";");
								var fields = aoe_field.split(";");
								for (var i=0;i<codes.length;i++) {
									$("#" + fields[i]).val(codes[i]);
									var parent_id = fields[i].replace("_code", "");
									$("#" + parent_id).show();
									$("#" + parent_id).addClass("aoe_required");
								}
							} else {
								$("#" + aoe_field).val(aoe_code);
								var parent_id1 = aoe_field.replace("_code", "");
								$("#" + parent_id1).show();
								$("#" + parent_id1).addClass("aoe_required");
							}
							$("#aoe_value").val(ui.item.value);
							$("#messages_lab_orders_text").val(this.value);
							$("#messages_lab_aoe_dialog").dialog('open');
						}
					}
				}
			});
			$("#messages_lab_accordion").accordion("option", "active", 0);
			$("#messages_lab_orders").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_messages_lab_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id);
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_messages_lab_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxchart/add-orders/labs",
						data: str,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$('#messages_lab_choice').html(data.choice);
							$("#messages_lab_action_dialog").dialog('open');
							$("#edit_messages_lab_form").clearForm();
							$("#messages_lab_orders_id").val(data.id);
							$("#messages_lab_edit_fields").dialog('close');
							reload_grid("alerts");
							reload_grid("messages_lab_list");
							if(noshdata.pending_orders_id !== '') {
								var old = $("#situation").val();
								var b = '';
								if (old !== '') {
									b = old + '\n\n' + data.pending;
								} else {
									b = data.pending;
								}
								$("#situation").val(b);
								$.ajax({
									type: "POST",
									url: "ajaxchart/complete-alert-order/" + noshdata.pending_orders_id,
									success: function(data){
										$.jGrowl(data);
										noshdata.pending_orders_id = '';
										reload_grid("alerts_pending");
									}
								});
							}
						}
					});
				}
			},
			Cancel: function() {
				$("#edit_messages_lab_form").clearForm();
				$("#messages_lab_edit_fields").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_lab_action_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 200,
		width: 500,
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".messages_lab_button_clear").click(function(){
		var id = $(this).attr('id');
		var parent_id = id.replace('_clear', '');
		$("#" + parent_id).val('');
	});
	$("#messages_lab_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_lab_header').show();
		$('#issues_rad_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_ref_header').hide();
		$('#issues_assessment_header').hide();
	});
	$("#messages_select_lab_location2").click(function (){
		$("#messages_edit_lab_location").dialog('open');
	});
	$("#messages_lab_location_state").addOption(states, false);
	$("#messages_lab_location_phone").mask("(999) 999-9999");
	$("#messages_lab_location_fax").mask("(999) 999-9999");
	$("#messages_lab_location_electronic_order").addOption({"":"Select Electronic Order Interface","PeaceHealth":"PeaceHealth Labs"}, false);
	$("#messages_lab_insurance_client").click(function(){
		var text = "Bill Client";
		var old = $("#messages_lab_insurance").val();
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
		$("#messages_lab_insurance").val(old1+text);
	});
	$("#messages_lab_orders_pending_date").datepicker();
	$("#messages_lab_date_obtained").datepicker();
	$('#messages_lab_time_obtained').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$('#messages_lab_medication_obtained').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#messages_lab_obtained_import").click(function(){
		var a1 = $("#messages_lab_date_obtained");
		var b1 = $("#messages_lab_time_obtained");
		var bValid = true;
		bValid = bValid && checkEmpty(a1,"Date Obtained");
		bValid = bValid && checkEmpty(b1,"Time Obtained");
		if (bValid) {
			var item = '';
			var a = $("#messages_lab_fasting").val();
			if(a){
				item += 'Fasting: ' + $("#messages_lab_fasting").val() + '\n';
			}
			item += 'Date/Time specimen obtained: ' + $("#messages_lab_date_obtained").val() + ', ' + $("#messages_lab_time_obtained").val() + '\n';
			var b = $("#messages_lab_location_obtained").val();
			if(b !== ''){
				item += 'Body location of specimen: ' + $("#messages_lab_location_obtained").val() + '\n';
			}
			var c = $("#messages_lab_medication_obtained").val();
			if(c !== ''){
				item += 'Time of last dosage of medication: ' + $("#messages_lab_medication_obtained").val() + '\n';
			}
			var old = $("#messages_lab_obtained").val();
			var old1 = '';
			if(old){
				var pos = old.lastIndexOf('\n');
				if (pos == -1) {
					old1 = old + '\n';
				} else {
					var d = old.slice(pos);
					if (d === '') {
						old1 = old;
					} else {
						old1 = old + '\n';
					}
				}
			}
			$("#messages_lab_obtained").val(old1+item);
			$("#messages_lab_date_obtained").val(currentDate);
			$("#messages_lab_time_obtained").val(currentTime);
			$("#messages_lab_location_obtained").val('');
			$("#messages_lab_medication_obtained").val('');
		}
	});
	$("#messages_print_lab").click(function(){
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			window.open("print_orders/" + order_id);
		}
	});
	$("#messages_electronic_lab").click(function(){
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			if(order_id){
				$.ajax({
					type: "POST",
					url: "ajaxchart/electronic-orders",
					data: "orders_id=" + order_id,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_fax_lab").click(function(){
		var lab = $("#messages_lab_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(lab,"Lab Order");
		if (bValid) {
			var order_id = $("#messages_lab_orders_id").val();
			if(order_id){
				$.ajax({
					type: "POST",
					url: "ajaxchart/fax-orders",
					data: "orders_id=" + order_id,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#messages_done_lab").click(function(){
		$("#messages_lab_action_dialog").dialog('close');
		$("#messages_lab_orders_id").val('');
		reload_grid("messages_lab_list");
	});
	$("#messages_edit_lab_location").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 580,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#messages_lab_location_city").autocomplete({
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
			$("#messages_lab_location_facility").focus();
			var id = $("#messages_lab_location").val();
			if(id){
				$("#messages_edit_lab_location").dialog("option", "title", "Edit Laboratory Provider");
				$.ajax({
					type: "POST",
					url: "ajaxsearch/orders-provider1",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$.each(data, function(key, value){
							$("#messages_edit_lab_location_form :input[name='" + key + "']").val(value);
						});
					}
				});
			} else {
				$("#messages_edit_lab_location").dialog("option", "title", "Add Laboratory Provider");
			}
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#messages_edit_lab_location_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id);
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messages_edit_lab_location_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-orders-provider/Laboratory",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#messages_edit_lab_location_form").clearForm();
								$("#messages_edit_lab_location").dialog('close');
								$("#messages_lab_location").removeOption(/./);
								$.ajax({
									url: "ajaxsearch/orders-provider/Laboratory",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#messages_lab_location").addOption({"":"Add lab provider."}, false);
											$("#messages_lab_location").addOption(data1.message, false);
											$("#messages_lab_location").val(data.id);
										} else {
											$("#messages_lab_location").addOption({"":"No lab provider.  Click Add."}, false);
										}
									}
								});
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#messages_edit_lab_location_form").clearForm();
				$("#messages_edit_lab_location").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	var user_id = noshdata.user_id;
	$("#messages_lab_orders_type").addOption({"0":'Global',user_id:'Personal'}, false);
	$("#add_test_cpt").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var a = encodeURIComponent($("#messages_lab").val());
				var b = encodeURIComponent($("#messages_lab_cpt").val());
				var c = encodeURIComponent($("#messages_lab_orders_type").val());
				var d = encodeURIComponent($("#messages_lab_snomed").val());
				$.ajax({
					type: "POST",
					url: "ajaxchart/add-orderslist",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Laboratory&user_id=" + c + "&snomed=" + d,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_lab_orders").focus();
				$("#messages_lab_orders").val(terms.join( "\n" ));
				$("#add_test_cpt_form").clearForm();
				$("#add_test_cpt").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_lab_orders").focus();
				$("#messages_lab_orders").val(terms.join( "\n" ));
				$("#add_test_cpt_form").clearForm();
				$("#add_test_cpt").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "ajaxdashboard/check-snomed-extension",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div").show();
						$("#snomed_tree").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "ajaxsearch/snomed-parent/lab";
										} else {
											nodeId = node.attr('id');
											url = "ajaxsearch/snomed-child/" + nodeId;
										}
										return url;
									},
									"success": function (new_data) {
										return new_data;
									}
								}
							},
							"themeroller" : {
								"item" : 'ui-widget-content'
							}
						}).bind("select_node.jstree", function (event, data) {
							$("#messages_lab_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_lab_snomed").autocomplete({
							source: function (req, add){
								$.ajax({
									url: "ajaxsearch/snomed/procedure",
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
					} else {
						$("#add_test_snomed_div").hide();
					}
				}
			});
			$("#messages_lab_cpt").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/cpt",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								add(data.message);
							} else {
								var addterm = [{"label": req.term + ": Select to add CPT to database.", "value":"*/add/*", "value1": req.term}];
								add(addterm);
							}
						}
					});
				},
				select: function(event, ui){
					if (ui.item.value == "*/add/*") {
						$("#configuration_cpt_form").clearForm();
						if (ui.item.value1.length > 5) {
							$("#configuration_cpt_description").val(ui.item.value1);
						} else {
							$("#configuration_cpt_code").val(ui.item.value1);
						}
						$('#configuration_cpt_origin').val("messages_lab_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_lab_orders_type").val('0');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_lab_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 4);
	});
	$("#messages_lab_aoe_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		width: 500,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var aoe_answer = '';
				var aoe_code = '';
				$(".aoe_required").each(function() {
					var required_id = $(this).attr('id');
					var input_id = required_id + "_input";
					var code_id = required_id + "_code";
					var id1 = $("#" + input_id);
					var text = $("label[for='" + input_id + "']").html();
					var bValid = true;
					bValid = bValid && checkEmpty(id1, text);
					if (bValid) {
						if (aoe_answer !== '') {
							aoe_answer = aoe_answer + "|" + $("#" + input_id).val();
						} else {
							aoe_answer = $("#" + input_id).val();
						}
						if (aoe_code !== '') {
							aoe_code = aoe_code + "|" + $("#" + code_id).val();
						} else {
							aoe_code = $("#" + code_id).val();
						}
					}
				});
				var a = $("#aoe_value").val() + "; AOEAnswer: " + aoe_answer + "; AOECode: " + aoe_code;
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				var b = terms.join( "\n" );
				var c = b.length;
				$("#messages_lab_orders").val(b).caret(c);
				$("#messages_lab_aoe_dialog_form").clearForm();
				$("#messages_lab_aoe_dialog_form").children().removeClass("aoe_required");
				$("#messages_lab_aoe_dialog_form").children().hide();
				$("#messages_lab_aoe_dialog").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_lab_orders_text").val());
				terms.pop();
				terms.push( "" );
				var b = terms.join( "\n" );
				var c = b.length;
				$("#messages_lab_orders").val(b).caret(c);
				$("#messages_lab_aoe_dialog_form").clearForm();
				$("#messages_lab_aoe_dialog_form").children().removeClass("aoe_required");
				$("#messages_lab_aoe_dialog_form").children().hide();
				$("#messages_lab_aoe_dialog").dialog('close');
				return false;
			}
		}
	});
});
