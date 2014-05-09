$(document).ready(function() {
	$("#messages_rad_accordion").accordion({ 
		heightStyle: "content" ,
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#messages_rad_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#messages_rad_accordion").accordion("option", "active");
					if (active < 4) {
						$("#messages_rad_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#messages_rad_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_rad_list").jqGrid('GridUnload');
			jQuery("#messages_rad_list").jqGrid({
				url: "ajaxchart/orders-list/radiology",
				postData: {t_messages_id: function(){return $("#messages_rad_t_messages_id_origin").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Tests','Diagnosis','Location1','Location','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_radiology',index:'orders_radiology',width:300},
					{name:'orders_radiology_icd',index:'orders_radiology_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true},
					{name:'orders_pending_date',index:'orders_pending_date',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_rad_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Imaging Orders",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_rad_list_pager',{search:false,edit:false,add:false,del:false});
		},
		buttons: {
			'Save': function() {
				var origin = $("#messages_rad_origin").val();
				if (origin == 'message') {
					var id = $("#t_messages_id").val();
					$.ajax({
						type: "POST",
						url: "ajaxchart/import-orders/radiology",
						data: "t_messages_id=" + id,
						success: function(data){
							var old = $("#t_messages_message").val();
							var old1 = old.trim();
							if(data != ''){
								if (old1 != '') {
									var a = old1+'\n\n'+data;
								} else {
									var a = data;
								}
								$("#t_messages_message").val(a);
							}
						}
					});
				} else {
					checkorders();
				}
				$("#messages_rad_origin").val('');
				$("#messages_rad_t_messages_id_origin").val('');
				$("#messages_rad_dialog").dialog('close');
			},
			Cancel: function() {
				$("#messages_rad_origin").val('');
				$("#messages_rad_t_messages_id_origin").val('');
				$("#messages_rad_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	
	$("#messages_add_rad").click(function(){
		load_outside_providers('rad','add');
		var a = $("#messages_rad_t_messages_id_origin").val();
		if (a == '') {
			$("#messages_rad_eid").val(noshdata.eid);
		} else {
			$("#messages_rad_t_messages_id").val(a);
		}
		$("#messages_rad_status").html('');
		if ($("#messages_rad_provider_list").val() == '' && noshdata.group_id == '2') {
			$("#messages_rad_provider_list").val(noshdata.user_id);
		}
		$("#messages_rad_location").val('');
		var currentDate = getCurrentDate();
		$('#messages_rad_orders_pending_date').val(currentDate);
		$("#messages_rad_edit_fields").dialog("option", "title", "Add Imaging Order");
		$("#messages_rad_edit_fields").dialog('open');
	});
	$("#messages_edit_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			load_outside_providers('rad','edit');
			jQuery("#messages_rad_list").GridToForm(item,"#edit_messages_rad_form");
			var status = 'Details for Radiology Order #' + item;
			$("#messages_rad_status").html(status);
			if ($("#messages_rad_provider_list").val() == '' && noshdata.group_id == '2') {
				$("#messages_rad_provider_list").val(noshdata.user_id);
			}
			var date = $('#messages_rad_orders_pending_date').val();
			var edit_date = editDate1(date);
			$('#messages_rad_orders_pending_date').val(edit_date);
			var a = $("#messages_rad_t_messages_id_origin").val();
			if (a == '') {
				$("#messages_rad_eid").val(noshdata.eid);
			} else {
				$("#messages_rad_t_messages_id").val(a);
			}
			$("#messages_rad_edit_fields").dialog("option", "title", "Edit Imaging Order");
			$("#messages_rad_edit_fields").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			$("#messages_rad_orders_id").val(item);
			var a = $("#messages_rad_t_messages_id_origin").val();
			$("#messages_rad_t_messages_id").val(a);
			$('#messages_rad_choice').html("Choose an action for the radiology order, reference number " + item);
			$("#messages_rad_action_dialog").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_delete_rad").click(function(){
		var item = jQuery("#messages_rad_list").getGridParam('selrow');
		if(item){
			$.ajax({
				url: "ajaxchart/delete-orders/Imaging",
				type: "POST",
				data: "orders_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid("messages_rad_list");
				}
			});
		} else {
			$.jGrowl("Please select order to delete!");
		}
	});
	$("#messages_rad_edit_fields").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_rad_insurance_grid").jqGrid('GridUnload');
			jQuery("#messages_rad_insurance_grid").jqGrid({
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
				pager: jQuery('#messages_rad_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Click to select insurance for imaging order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_rad_insurance_grid").getCell(id,'insurance_insu_firstname');
					var address_id = jQuery("#messages_rad_insurance_grid").getCell(id,'address_id');
					$.ajax({
						url: "ajaxsearch/payor-id/" + address_id,
						type: "POST",
						success: function(data){
							var text = insurance_plan_name + '; Payor ID: ' + data + '; ID: ' + insurance_id_num;
							if(insurance_group != ''){
								text += "; Group: " + insurance_group;
							}
							text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
							var old = $("#messages_rad_insurance").val();
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
							$("#messages_rad_insurance").val(old1+text);
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_rad_insurance_pager',{search:false,edit:false,add:false,del:false});
			if (noshdata.group_id == '2') {
				$(".nosh_provider_exclude").hide();
			} else {
				$(".nosh_provider_exclude").show();
			}
			$("#messages_rad_codes").autocomplete({
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
			$("#messages_rad_orders").catcomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/rad",
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
						$("#messages_rad").val(ui.item.value1);
						$("#messages_rad_orders_text").val(this.value);
						$("#add_test_cpt1").dialog('open');
					} else {
						var terms = split( this.value );
						terms.pop();
						terms.push( ui.item.value );
						terms.push( "" );
						this.value = terms.join( "\n" );
						return false;
					}
				}
			});
			$("#messages_rad_accordion").accordion("option", "active", 0);
			$("#messages_rad_orders").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_messages_rad_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_messages_rad_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxchart/add-orders/radiology",
						data: str,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$('#messages_rad_choice').html(data.choice);
							$("#messages_rad_action_dialog").dialog('open');
							$("#edit_messages_rad_form").clearForm();
							$("#messages_rad_orders_id").val(data.id);
							$("#messages_rad_edit_fields").dialog('close');
							reload_grid("alerts");
							reload_grid("messages_rad_list");
							if(noshdata.pending_orders_id != '') {
								var old = $("#situation").val();
								if (old != '') {
									var b = old + '\n\n' + data.pending;
								} else {
									var b = data.pending;
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
				$("#edit_messages_rad_form").clearForm();
				$("#messages_rad_edit_fields").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_rad_action_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".messages_rad_button_clear").click(function(){
		var id = $(this).attr('id');
		var parent_id = id.replace('_clear', '');
		$("#" + parent_id).val('');
	});
	$("#messages_rad_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_rad_header').show();
		$('#issues_lab_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_ref_header').hide();
		$('#issues_assessment_header').hide();
	});
	$("#messages_select_rad_location2").click(function (){
		$("#messages_edit_rad_location").dialog('open');
	});
	$("#messages_rad_location_state").addOption(states, false);
	$("#messages_rad_location_phone").mask("(999) 999-9999");
	$("#messages_rad_location_fax").mask("(999) 999-9999");
	$("#messages_rad_insurance_client").click(function(){
		var text = "Bill Client";
		var old = $("#messages_rad_insurance").val();
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
		$("#messages_rad_insurance").val(old1+text);
	});
	$("#messages_rad_orders_pending_date").datepicker();
	$("#messages_print_rad").click(function(){
		var rad = $("#messages_rad_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(rad,"Radiology Order");
		if (bValid) {
			var order_id = $("#messages_rad_orders_id").val();
			window.open("print_orders/" + order_id);
		}
	});
	$("#messages_electronic_rad").click(function(){
		$.jGrowl('Future feature!');
	});
	$("#messages_fax_rad").click(function(){
		var rad = $("#messages_rad_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(rad,"Radiology Order");
		if (bValid) {
			var order_id = $("#messages_rad_orders_id").val();
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
	$("#messages_done_rad").click(function(){
		$("#messages_rad_action_dialog").dialog('close');
		$("#messages_rad_orders_id").val('');
		reload_grid("messages_rad_list");
	});
	$("#messages_edit_rad_location").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#messages_rad_location_city").autocomplete({
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
			$("#messages_rad_location_facility").focus();
			var id = $("#messages_rad_location").val();
			if(id){
				$("#messages_edit_rad_location").dialog("option", "title", "Edit Imaging Provider");
				$.ajax({
					type: "POST",
					url: "ajaxsearch/orders-provider1",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$.each(data, function(key, value){
							$("#messages_edit_rad_location_form :input[name='" + key + "']").val(value);
						});
					}
				});
			} else {
				$("#messages_edit_rad_location").dialog("option", "title", "Add Imaging Provider");
			}
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#messages_edit_rad_location_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messages_edit_rad_location_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-orders-provider/Radiology",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#messages_edit_rad_location_form").clearForm();
								$("#messages_edit_rad_location").dialog('close');
								$("#messages_rad_location").removeOption(/./);
								$.ajax({
									url: "ajaxsearch/orders-provider/Radiology",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#messages_rad_location").addOption({"":"Add imaging provider."}, false);
											$("#messages_rad_location").addOption(data1.message, false);
											$("#messages_rad_location").val(data.id);
										} else {
											$("#messages_rad_location").addOption({"":"No imaging provider.  Click Add."}, false);
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
				$("#messages_edit_rad_location_form").clearForm();
				$("#messages_edit_rad_location").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	var user_id = noshdata.user_id;
	$("#messages_rad_orders_type").addOption({"0":'Global',user_id:'Personal'}, false);
	$("#add_test_cpt1").dialog({ 
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
				var a = encodeURIComponent($("#messages_rad").val());
				var b = encodeURIComponent($("#messages_rad_cpt").val());
				var c = encodeURIComponent($("#messages_rad_orders_type").val());
				var d = encodeURIComponent($("#messages_rad_snomed").val());
				$.ajax({
					type: "POST",
					url: "ajaxchart/add-orderslist",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Radiology&user_id=" + c + "&snomed=" + d,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_rad_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_rad_orders").focus();
				$("#messages_rad_orders").val(terms.join( "\n" ));
				$("#add_test_cpt1_form").clearForm();
				$("#add_test_cpt1").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_rad_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_rad_orders").focus();
				$("#messages_rad_orders").val(terms.join( "\n" ));
				$("#add_test_cpt1_form").clearForm();
				$("#add_test_cpt1").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "ajaxdashboard/check-snomed-extension",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div1").show();
						$("#snomed_tree1").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "ajaxsearch/snomed-parent/imaging";
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
							$("#messages_rad_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_rad_snomed").autocomplete({
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
						$("#add_test_snomed_div1").hide();
					}
				}
			});
			$("#messages_rad_cpt").autocomplete({
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
						$('#configuration_cpt_origin').val("messages_rad_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_rad_orders_type").val('0');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_rad_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 4);
	});
});
