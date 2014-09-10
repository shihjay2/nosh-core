$(document).ready(function() {
	$("#messages_ref_accordion").accordion({ 
		heightStyle: "content" ,
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#messages_ref_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#messages_ref_accordion").accordion("option", "active");
					if (active < 4) {
						$("#messages_ref_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#messages_ref_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_ref_list").jqGrid('GridUnload');
			jQuery("#messages_ref_list").jqGrid({
				url: "ajaxchart/orders-list/referrals",
				postData: {t_messages_id: function(){return $("#messages_ref_t_messages_id_origin").val();}},
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Referral','Diagnosis','Location1','Location','Insurance','Provider','Order Date'],
				colModel:[
					{name:'orders_id',index:'orders_id',width:1,hidden:true},
					{name:'orders_referrals',index:'orders_referrals',width:300},
					{name:'orders_referrals_icd',index:'orders_referrals_icd',width:200},
					{name:'address_id',index:'address_id',hidden:true},
					{name:'displayname',index:'displayname',width:100},
					{name:'orders_insurance',index:'orders_insurance',hidden:true},
					{name:'encounter_provider',index:'encounter_provider',hidden:true},
					{name:'orders_pending_date',index:'orders_pending_date',hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#messages_ref_list_pager'),
				sortname: 'orders_id',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Referral Orders",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_ref_list_pager',{search:false,edit:false,add:false,del:false});
		},
		buttons: {
			'Save': function() {
				var origin = $("#messages_ref_origin").val();
				if (origin == 'message') {
					var id = $("#t_messages_id").val();
					$.ajax({
						type: "POST",
						url: "ajaxchart/import-orders/referrals",
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
				$("#messages_ref_origin").val('');
				$("#messages_ref_t_messages_id_origin").val('');
				$("#messages_ref_dialog").dialog('close');
			},
			Cancel: function() {
				$("#messages_ref_origin").val('');
				$("#messages_ref_t_messages_id_origin").val('');
				$("#messages_ref_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('#messages_specialty_select').change(function() {
		if ($(this).val() != ""){
			$("#messages_ref_location").removeOption(/./);
			$.ajax({
				url: "ajaxsearch/ref-provider/" + $(this).val(),
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#messages_ref_location").addOption({"":"Add referral provider."}, false);
						$("#messages_ref_location").addOption(data.message, false);
					} else {
						$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."}, false);
					}
				}
			});
		}
	});
	$("#messages_add_ref").click(function(){
		var a = $("#messages_ref_t_messages_id_origin").val();
		if (a == '') {
			$("#messages_ref_eid").val(noshdata.eid);
		} else {
			$("#messages_ref_t_messages_id").val(a);
		}
		$("#messages_ref_status").html('');
		$("#messages_ref_location").val('');
		$("#messages_specialty_select").val('');
		$("#messages_ref_template").val('');
		if (noshdata.group_id == '2') {
			$("#messages_ref_provider_list").val(noshdata.user_id);
		} else {
			$("#messages_ref_provider_list").val('');
		}
		var currentDate = getCurrentDate();
		$('#messages_ref_orders_pending_date').val(currentDate);
		$("#messages_specialty_select").removeOption(/./);
		$.ajax({
			url: "ajaxsearch/ref-provider-specialty",
			dataType: "json",
			type: "POST",
			success: function(data){
				if(data.response =='true'){
					$("#messages_specialty_select").addOption({"":"All specialties."}, false);
					$("#messages_specialty_select").addOption(data.message, false);
				} else {
					$("#messages_specialty_select").addOption({"":"No specialties.  Click Add."}, false);
				}
			}
		});
		$("#messages_ref_location").removeOption(/./);
		$.ajax({
			url: "ajaxsearch/ref-provider/all",
			dataType: "json",
			type: "POST",
			success: function(data){
				if(data.response =='true'){
					$("#messages_ref_location").addOption({"":"Add referral provider."}, false);
					$("#messages_ref_location").addOption(data.message, false);
				} else {
					$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."}, false);
				}
			}
		});
		$("#messages_ref_edit_fields").dialog("option", "title", "Add Referral");
		$("#messages_ref_edit_fields").dialog('open');
	});
	$("#messages_edit_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			$("#messages_specialty_select").removeOption(/./);
			$.ajax({
				url: "ajaxsearch/ref-provider-specialty",
				dataType: "json",
				type: "POST",
				async: false,
				success: function(data){
					if(data.response =='true'){
						$("#messages_specialty_select").addOption({"":"All specialties."}, false);
						$("#messages_specialty_select").addOption(data.message, false);
					} else {
						$("#messages_specialty_select").addOption({"":"No specialties.  Click Add."}, false);
					}
				}
			});
			$("#messages_ref_location").removeOption(/./);
			$.ajax({
				url: "ajaxsearch/ref-provider/all",
				dataType: "json",
				type: "POST",
				async: false,
				success: function(data){
					if(data.response =='true'){
						$("#messages_ref_location").addOption({"":"Add referral provider."}, false);
						$("#messages_ref_location").addOption(data.message, false);
					} else {
						$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."}, false);
					}
				}
			});
			jQuery("#messages_ref_list").GridToForm(item,"#edit_messages_ref_form");
			var status = 'Details for Referral Order #' + item;
			$("#messages_ref_status").html(status);
			$("#messages_ref_template").val('');
			if ($("#messages_ref_provider_list").val() == '' && noshdata.group_id == '2') {
				$("#messages_ref_provider_list").val(noshdata.user_id);
			}
			var a = $("#messages_ref_t_messages_id_origin").val();
			if (a == '') {
				$("#messages_ref_eid").val(noshdata.eid);
			} else {
				$("#messages_ref_t_messages_id").val(a);
			}
			$("#messages_ref_edit_fields").dialog("option", "title", "Edit Referral");
			$("#messages_ref_edit_fields").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_resend_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			var id = $("#messages_ref_list").getCell(item,'orders_id');
			$("#messages_ref_orders_id").val(id);
			$('#messages_ref_choice').html("Choose an action for the referral order, reference number " + id);
			$("#messages_ref_action_dialog").dialog('open');
		} else {
			$.jGrowl("Please select order to edit!");
		}
	});
	$("#messages_delete_ref").click(function(){
		var item = jQuery("#messages_ref_list").getGridParam('selrow');
		if(item){
			var id = $("#messages_ref_list").getCell(item,'orders_id');
			$.ajax({
				url: "ajaxchart/delete-orders/Referral",
				type: "POST",
				data: "orders_id=" + id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("messages_ref_list");
				}
			});
		} else {
			$.jGrowl("Please select order to delete!");
		}
	});
	$("#messages_ref_edit_fields").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(){
			jQuery("#messages_ref_insurance_grid").jqGrid('GridUnload');
			jQuery("#messages_ref_insurance_grid").jqGrid({
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
				pager: jQuery('#messages_ref_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors - Click to select insurance for imaging order",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var insurance_plan_name = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_plan_name');
					var insurance_id_num = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_id_num');
					var insurance_group = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_group');
					var insurance_insu_lastname = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_insu_lastname');
					var insurance_insu_firstname = jQuery("#messages_ref_insurance_grid").getCell(id,'insurance_insu_firstname');
					var address_id = jQuery("#messages_ref_insurance_grid").getCell(id,'address_id');
					$.ajax({
						url: "ajaxsearch/payor-id/" + address_id,
						type: "POST",
						success: function(data){
							var text = insurance_plan_name + '; Payor ID: ' + data + '; ID: ' + insurance_id_num;
							if(insurance_group != ''){
								text += "; Group: " + insurance_group;
							}
							text += "; " + insurance_insu_lastname + ", " + insurance_insu_firstname;
							var old = $("#messages_ref_insurance").val();
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
							$("#messages_ref_insurance").val(old1+text);
						}
					});
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#messages_ref_insurance_pager',{search:false,edit:false,add:false,del:false});
			referral_template_renew();
			if (noshdata.group_id == '2') {
				$(".nosh_provider_exclude").hide();
			} else {
				$(".nosh_provider_exclude").show();
			}
			$("#messages_ref_codes").autocomplete({
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
			$("#messages_ref_accordion").accordion("option", "active", 0);
			$("#messages_ref_orders").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_messages_ref_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_messages_ref_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxchart/add-orders/referrals",
						data: str,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$('#messages_ref_choice').html(data.choice);
							$("#messages_ref_action_dialog").dialog('open');
							$("#edit_messages_ref_form").clearForm();
							$("#messages_ref_orders_id").val(data.id);
							$("#messages_ref_edit_fields").dialog('close');
							$('#messages_ref_form').html('');
							reload_grid("alerts");
							reload_grid("messages_ref_list");
						}
					});
				}
			},
			Cancel: function() {
				$("#edit_messages_ref_form").clearForm();
				$("#messages_ref_edit_fields").dialog('close');
				$('#messages_ref_form').html('');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_ref_action_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 500, 
		modal: true,
		closeOnEscape: false,
		dialogClass: "noclose",
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".messages_ref_button_clear").click(function(){
		var id = $(this).attr('id');
		var parent_id = id.replace('_clear', '');
		$("#" + parent_id).val('');
	});
	$("#messages_ref_issues").click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_ref_header').show();
		$('#issues_lab_header').hide();
		$('#issues_rad_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_assessment_header').hide();
	});	
	$("#messages_select_ref_location2").click(function (){
		$("#messages_edit_ref_location").dialog('open');
	});
	$("#messages_ref_location_state").addOption(states, false);
	$("#messages_ref_location_phone").mask("(999) 999-9999");
	$("#messages_ref_location_fax").mask("(999) 999-9999");
	$("#messages_ref_insurance_client").click(function(){
		var text = "Bill Client";
		var old = $("#messages_ref_insurance").val();
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
		$("#messages_ref_insurance").val(old1+text);
	});
	
	$("#messages_print_ref").click(function(){
		var ref = $("#messages_ref_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(ref,"Referral Order");
		if (bValid) {
			var order_id = $("#messages_ref_orders_id").val();
			window.open("print_orders/" + order_id);
		}
	});
	$("#messages_electronic_ref").click(function(){
		$.jGrowl('Future feature!');
	});
	$("#messages_fax_ref").click(function(){
		var ref = $("#messages_ref_orders_id");
		var bValid = true;
		bValid = bValid && checkEmpty(ref,"Referral Order");
		if (bValid) {
			var order_id = $("#messages_ref_orders_id").val();
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
	$("#messages_done_ref").click(function(){
		$("#messages_ref_action_dialog").dialog('close');
		$("#messages_ref_orders_id").val('');
		reload_grid("messages_ref_list");
	});
	$("#messages_edit_ref_location").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#messages_ref_location_specialty").autocomplete({
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
			$("#messages_ref_location_city").autocomplete({
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
			$("#messages_ref_location_lastname").focus();
			var id = $("#messages_ref_location").val();
			if(id){
				$("#messages_edit_ref_location").dialog("option", "title", "Edit Referral Provider");
				$.ajax({
					type: "POST",
					url: "ajaxsearch/orders-provider1",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$.each(data, function(key, value){
							$("#messages_edit_ref_location_form :input[name='" + key + "']").val(value);
						});
					}
				});
			} else {
				$("#messages_edit_ref_location").dialog("option", "title", "Add Referral Provider");
			}
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#messages_edit_ref_location_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#messages_edit_ref_location_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-orders-provider/Referral",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#messages_edit_ref_location_form").clearForm();
								$("#messages_edit_ref_location").dialog('close');
								$("#messages_ref_location").removeOption(/./);
								$.ajax({
									url: "ajaxsearch/ref-provider/all",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#messages_ref_location").addOption({"":"Add referral provider."});
											$("#messages_ref_location").addOption(data1.message, false);
											$("#messages_ref_location").val(data.id);
										} else {
											$("#messages_ref_location").addOption({"":"No referral provider.  Click Add."}, false);
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
				$("#messages_edit_ref_location_form").clearForm();
				$("#messages_edit_ref_location").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	var user_id = noshdata.user_id;
	$("#messages_ref_orders_type").addOption({"0":'Global',user_id:'Personal'}, false);
	$("#add_test_cpt3").dialog({ 
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
				var a = encodeURIComponent($("#messages_ref").val());
				var b = encodeURIComponent($("#messages_ref_cpt").val());
				var c = encodeURIComponent($("#messages_ref_orders_type").val());
				var d = encodeURIComponent($("#messages_ref_snomed").val());
				$.ajax({
					type: "POST",
					url: "ajaxchart/add-orderslist",
					data: "orders_description=" + a + "&cpt=" + b + "&orders_category=Referral&user_id=" + c + "&snomed=" + d,
					success: function(data){
						$.jGrowl(data);
					}
				});
				if(b){
					a = a + ', CPT ' + b;
				}
				var terms = split($("#messages_ref_orders_text").val());
				terms.pop();
				terms.push(a);
				terms.push( "" );
				$("#messages_ref_orders").focus();
				$("#messages_ref_orders").val(terms.join( "\n" ));
				$("#add_test_cpt3_form").clearForm();
				$("#add_test_cpt3").dialog('close');
				return false;
			},
			Cancel: function() {
				var terms = split($("#messages_ref_orders_text").val());
				terms.pop();
				terms.push( "" );
				$("#messages_ref_orders").focus();
				$("#messages_ref_orders").val(terms.join( "\n" ));
				$("#add_test_cpt3_form").clearForm();
				$("#add_test_cpt3").dialog('close');
				return false;
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "ajaxdashboard/check-snomed-extension",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#add_test_snomed_div3").show();
						$("#snomed_tree3").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										if (node == -1) {
											url = "ajaxsearch/snomed-parent/ref";
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
							$("#messages_ref_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#messages_ref_snomed").autocomplete({
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
						$("#add_test_snomed_div3").hide();
					}
				}
			});
			$("#messages_ref_cpt").autocomplete({
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
						$('#configuration_cpt_origin').val("messages_ref_cpt");
						$('#configuration_cpt_dialog').dialog('open');
						$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
					}
				},
				minLength: 3
			});
			$("#messages_ref_orders_type").val('0');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#messages_ref_orderslist_link").button().click(function(){
		$("#configuration_dialog").dialog('open');
		$("#configuration_accordion").accordion("option", "active", 7);
	});
	$('#messages_ref_template').change(function (){
		var a = $(this).val();
		if (a != "") {
			$.ajax({
				type: "POST",
				url: "ajaxchart/get-ref-template/" + a,
				dataType: "json",
				success: function(data){
					$('#messages_ref_form').html('');
					var stringConstructor = "test".constructor;
					var objectConstructor = {}.constructor;
					if (data.constructor === stringConstructor) {
						var json_object = JSON.parse(data);
						$('#messages_ref_form').dform(json_object);
						$(".referral_form_div").css("padding","5px");
						$('.ref_template_div select').addOption({'':'Select option'},true);
					} else {
						$('#messages_ref_form').dform(data);
					}
					$('.ref_buttonset').buttonset();
					$('#messages_ref_form').find('input').first().focus();
					$('#messages_ref_form').find('.referral_buttonset').buttonset();
					$('#messages_ref_form').find('.referral_form_buttonset').buttonset();
					$('input.ref_other[type="checkbox"]').button();
					$(".ref_select").chosen();
				}
			});
		}
	});
	$("#messages_ref_template_save").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxchart/make-referral",
			dataType: "json",
			success: function(data){
				var preview = "Requested action:\n";
				var b = $(".ref_hidden").val();
				if (b !== '' && b !== undefined) {
					preview += b + '\n';
				} else {
					var c = $('#messages_ref_template option:selected').text();
					preview += c + '\n';
				}
				$('input.ref_intro[type="checkbox"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('select.ref_intro').each(function (){
					if ($(this).val() != "") {
						var select_label = $(this).parent().children('span').html();
						preview += select_label + " " + $(this).val() + '\n';
					}
				});
				$('input.ref_intro[type="text"]').each(function (){
					if ($(this).val() != "") {
						var label = $(this).attr("placeholder");
						preview += label + ": " + $(this).val() + '\n';
					}
				});
				$('.referral_form_div').find('input[type="checkbox"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('.referral_form_div').find('input[type="radio"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('.referral_form_div').find('select').each(function (){
					if ($(this).val() != "") {
						preview += $(this).val() + '\n';
					}
				});
				$('.referral_form_div').find('input[type="text"]').each(function (){
					if ($(this).val() != "") {
						var parent_id = $(this).attr("id");
						var x = parent_id.length - 1;
						var parent_div = parent_id.slice(0,x);
						if ($("#" + parent_div + "_div").length) {
							var start1 = $("#" + parent_div + "_div").find('span:first').text();
						} else {
							var parent_div_parts = parent_id.split("_");
							var parent_div = parent_div_parts[0] + "_" + parent_div_parts[1] + "_" + parent_div_parts[2];
							var start1 = $("#" + parent_div).find('span:first').text();
						}
						preview += start1 + ": " + $(this).val() + '\n';
					}
				});
				preview += '\n';
				var issues = data.issues;
				preview += "Active Issues:";
				var issues_len = issues.length;
				for(var i=0; i<issues_len; i++) {
					preview += '\n' + issues[i];
				}
				preview += '\n\n';
				var meds = data.meds;
				preview += "Active Medications:";
				var meds_len = meds.length;
				for(var j=0; j<meds_len; j++) {
					preview += '\n' + meds[j];
				}
				preview += '\n\n';
				var allergies = data.allergies;
				preview += "Allergies:";
				var allergies_len = allergies.length;
				for(var k=0; k<allergies_len; k++) {
					preview += '\n' + allergies[k];
				}
				preview += '\n\n';
				$('input.ref_after[type="checkbox"]').each(function (){
					if ($(this).is(':checked')) {
						preview += $(this).val() + '\n';
					}
				});
				$('select.ref_after').each(function (){
					if ($(this).val() != "") {
						var select_label = $(this).parent().children('span').html();
						preview += select_label + " " + $(this).val() + '\n';
					}
				});
				$('input.ref_after[type="text"]').each(function (){
					if ($(this).val() != "") {
						var label = $(this).attr("placeholder");
						preview += label + ": " + $(this).val() + '\n';
					}
				});
				preview += '\n' + 'Sincerely,' + '\n\n' + data.displayname;
				var terms = split($("#messages_ref_orders").val());
				terms.pop();
				terms.push( preview );
				terms.push( "" );
				var new_terms = terms.join( "\n" );
				$("#messages_ref_orders").val(new_terms);
				$('#messages_ref_form').clearDiv();
			}
		});
	});
	swipe();
});
