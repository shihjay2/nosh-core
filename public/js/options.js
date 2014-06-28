$(document).ready(function() {
	function targetname (cellvalue, options, rowObject){
		var ret = '';
		$.each(fields, function(key, value){
			if (key == cellvalue) {
				ret = value;
			}
		});
		return ret;
	}
	function untargetname (cellvalue, options, cell){
		var ret = '';
		$.each(fields, function(key, value){
			if (value == cellvalue) {
				ret = key;
			}
		});
		return ret;
	}
	$("#configuration_accordion").accordion({
		heightStyle: "content",
		active: false,
		collapsible: true
	});
	$("#configuration_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#nosh_configuration").click(function() {
		$('#dialog_load').dialog('option', 'title', "Loading configuration...").dialog('open');
		jQuery("#configuration_orders_labs").jqGrid('GridUnload');
		jQuery("#configuration_orders_labs").jqGrid({
			url:"ajaxdashboard/orders-list/Laboratory/Global",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_labs_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Global Laboratory Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_labs_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_labs1").jqGrid('GridUnload');
		jQuery("#configuration_orders_labs1").jqGrid({
			url:"ajaxdashboard/orders-list/Laboratory/User",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_labs1_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Personal Laboratory Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_labs1_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_rad").jqGrid('GridUnload');
		jQuery("#configuration_orders_rad").jqGrid({
			url:"ajaxdashboard/orders-list/Radiology/Global",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_rad_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Global Imaging Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_rad_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_rad1").jqGrid('GridUnload');
		jQuery("#configuration_orders_rad1").jqGrid({
			url:"ajaxdashboard/orders-list/Radiology/User",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_rad1_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Personal Imaging Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_rad1_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_cp").jqGrid('GridUnload');
		jQuery("#configuration_orders_cp").jqGrid({
			url:"ajaxdashboard/orders-list/Cardiopulmonary/Global",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_cp_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Global Cardiopulmonary Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_cp_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_cp1").jqGrid('GridUnload');
		jQuery("#configuration_orders_cp1").jqGrid({
			url:"ajaxdashboard/orders-list/Cardiopulmonary/User",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_cp1_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Personal Cardiopulmonary Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_cp1_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_ref").jqGrid('GridUnload');
		jQuery("#configuration_orders_ref").jqGrid({
			url:"ajaxdashboard/orders-list/Referral/Global",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_ref_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Global Referral Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_ref_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#configuration_orders_ref1").jqGrid('GridUnload');
		jQuery("#configuration_orders_ref1").jqGrid({
			url:"ajaxdashboard/orders-list/Referral/User",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Group','Category','Description','CPT','SNOMED'],
			colModel:[
				{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
				{name:'user_id',index:'user_id',width:1,hidden:true},
				{name:'orders_category',index:'orders_category',width:1,hidden:true},
				{name:'orders_description',index:'orders_description',width:355},
				{name:"cpt",index:"cpt",width:100},
				{name:"snomed",index:"snomed",width:100}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#configuration_orders_ref1_pager'),
			sortname: 'orders_description',
			viewrecords: true,
			sortorder: "asc",
			caption:"Personal Referral Orders List",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#configuration_orders_ref1_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#cpt_list_config").jqGrid({
			url:"ajaxdashboard/cpt-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Relate ID','CPT Code','Description','Charge','Favorite','Unit'],
			colModel:[
				{name:'cpt_id',index:'cpt_id',width:1,hidden:true},
				{name:'cpt_relate_id',index:'cpt_relate_id',width:1,hidden:true, editrules : {edithidden:true}},
				{name:'cpt',index:'cpt',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'cpt_description',index:'cpt_description',width:350,editable:true,editrules:{required:true},edittype:"textarea",editoptions:{rows:"4",cols:"50"},formoptions:{elmsuffix:"(*)"}},
				{name:'cpt_charge',index:'cpt_charge',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'favorite',index:'favorite',width:1,hidden:true},
				{name:'unit',index:'unit',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#cpt_list_config_pager'),
			sortname: 'cpt',
			viewrecords: true,
			sortorder: "asc",
			caption:"CPT Codes",
			emptyrecords:"No CPT codes",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#cpt_list_config_pager',{edit:false,add:false,del:false});
		jQuery("#patient_forms_list").jqGrid({
			url:"ajaxdashboard/patient-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age','Scoring'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:300},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true},
				{name:'scoring',index:'scoring',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#patient_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"Patient Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#patient_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#hpi_forms_list").jqGrid({
			url:"ajaxdashboard/hpi-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:300},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#hpi_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"HPI Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#hpi_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#ros_forms_list").jqGrid({
			url:"ajaxdashboard/ros-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age','Default'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:250},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true},
				{name:'default',index:'default',width:50}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#ros_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"ROS Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#ros_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#pe_forms_list").jqGrid({
			url:"ajaxdashboard/pe-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age','Default'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:250},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true},
				{name:'default',index:'default',width:50}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#pe_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"PE Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#pe_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#situation_forms_list").jqGrid({
			url:"ajaxdashboard/situation-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:300},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#situation_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"Situation Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#situation_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#referral_forms_list").jqGrid({
			url:"ajaxdashboard/referral-forms-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Form','Gender','Group','Age'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:300},
				{name:'sex',index:'sex',width:100},
				{name:'group',index:'group',width:100},
				{name:'age',index:'age',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#referral_forms_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"Referral Forms",
			emptyrecords:"No forms",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#referral_forms_list_pager',{edit:false,add:false,del:false});
		jQuery("#textdump_list").jqGrid({
			url:"ajaxdashboard/textdump-list",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Target Field','Group'],
			colModel:[
				{name:'template_id',index:'template_id',width:1,hidden:true},
				{name:'template_name',index:'template_name',width:200,formatter:targetname,unformat:untargetname},
				{name:'group',index:'group',width:400}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#textdump_list_pager'),
			sortname: 'template_id',
			viewrecords: true,
			sortorder: "asc",
			caption:"Text Template Groups",
			emptyrecords:"No text templates",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" },
			subGrid: true,
			subGridRowExpanded: function(subgrid_id, row_id) {
				var group_id = row_id;
				var subgrid_table_id, pager_id;
				subgrid_table_id = subgrid_id+"_t";
				pager_id = "p_"+subgrid_table_id;
				$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
				jQuery("#"+subgrid_table_id).jqGrid({
					url: "ajaxdashboard/textdump-list1/"+row_id,
					datatype: "json",
					mtype: "POST",
					colNames:['ID','Target Field','Template Text','Group','Default'],
					colModel:[
						{name:'template_id',index:'template_id',width:1,hidden:true},
						{name:'template_name',index:'template_name',width:200,formatter:targetname,unformat:untargetname},
						{name:'array',index:'array',width:400},
						{name:'group',index:'group',width:1,hidden:true},
						{name:'default',index:'default',width:1,hidden:true}
					],
					rowNum:10,
					pager: pager_id,
					sortname: 'template_id', 
					sortorder: "asc", 
					height: '100%',
					jsonReader: { repeatitems : false, id: "0" }
				});
				jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
					search:false,
					edit:false,
					add:false,
					del:false
				}).jqGrid('navButtonAdd',"#"+pager_id,{
					caption:"Add", 
					buttonicon:"ui-icon-plus", 
					onClickButton: function(){ 
						jQuery("#textdump_list").GridToForm(group_id,"#configuration_textdump_form");
						$("#configuration_textdump_template_id").val('');
						$("#configuration_textdump_subgrid_table_id").val(subgrid_table_id);
						$('#configuration_textdump_dialog').dialog('open');
						$('#configuration_textdump_dialog').dialog('option', 'title', "Add Template Text");
					}, 
					position:"last"
				}).jqGrid('navButtonAdd',"#"+pager_id,{
					caption:"Edit", 
					buttonicon:"ui-icon-pencil", 
					onClickButton: function(){ 
						var id = jQuery(this).getGridParam('selrow');
						if(id){
							jQuery(this).GridToForm(id,"#configuration_textdump_form");
							$("#configuration_textdump_subgrid_table_id").val(subgrid_table_id);
							$('#configuration_textdump_dialog').dialog('open');
							$('#configuration_textdump_dialog').dialog('option', 'title', "Edit Template Text");
						} else {
							$.jGrowl('Choose item to edit!');
						}
					}, 
					position:"last"
				}).jqGrid('navButtonAdd',"#"+pager_id,{
					caption:"Delete", 
					buttonicon:"ui-icon-trash", 
					onClickButton: function(){ 
						var id = jQuery(this).getGridParam('selrow');
						if(id){
							if(confirm('Are you sure you want to delete this text?')){
								$.ajax({
									type: "POST",
									url: "ajaxsearch/deletetextdump/" + id,
									success: function(data){
										$.jGrowl(data);
										jQuery(this).trigger("reloadGrid");
									}
								});
							}
						} else {
							$.jGrowl('Choose item to delete!');
						}
					}, 
					position:"last"
				});
			}
		}).navGrid('#textdump_list_pager',{edit:false,add:false,del:false});
		$('#dialog_load').dialog('close');
		$("#configuration_dialog").dialog('open');
	});
	$("#configuration_order").dialog({ 
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
				var a = $("#configuration_orders_description");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Orders Description");
				if (bValid) {
					var str = $("#configuration_order_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/add-orderslist",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var b = $("#configuration_orderslist_table").val();
							jQuery("#" + b).trigger("reloadGrid");
							$("#configuration_order_form").clearForm();
							$("#configuration_order").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_order_form").clearForm();
				$("#configuration_order").dialog('close');
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "ajaxdashboard/check-snomed-extension",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#configuration_snomed_div").show();
						$("#configuration_snomed_tree").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										var type1 = $("#configuration_orders_categrory").val();
										if (type1 == "Laboratory") {
											var type = "lab";
										}
										if (type1 == "Radiology") {
											var type = "imaging";
										}
										if (type1 == "Cardiopulmonary") {
											var type = "cp";
										}
										if (type1 == "Referral") {
											var type = "ref";
										}
										if (node == -1) {
											url = "ajaxsearch/snomed-parent/" + type;
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
							$("#configuration_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#configuration_snomed").autocomplete({
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
						$("#configuration_snomed_div").hide();
					}
				}
			});
			$("#configuration_cpt").autocomplete({
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
			$("#configuration_orders_description").focus();
		},
		close: function(event, ui) {
			$("#configuration_order_form").clearForm();
			$('#configuration_order').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".configuration_orders_button").click(function(){
		var id = $(this).attr("id");
		var parts = id.split('_');
		var parent_id_table = parts[0] + '_' + parts[1] + '_' + parts[2];
		if (parts[2] == 'labs' || parts[2] == 'labs1') {
			var type = "Laboratory";
		}
		if (parts[2] == 'rad' || parts[2] == 'rad1') {
			var type = "Radiology";
		}
		if (parts[2] == 'cp' || parts[2] == 'cp1') {
			var type = "Cardiopulmonary";
		}
		if (parts[2] == 'labs' || parts[2] == 'rad' || parts[2] == 'cp') {
			var group = '0';
		} else {
			var group = noshdata.user_id;
		}
		if (parts[3] == 'add') {
			$("#configuration_order_form").clearForm();
			$("#configuration_orders_categrory").val(type);
			$("#configuration_orderslist_table").val(parent_id_table);
			$("#configuration_user_id").val(group);
			$('#configuration_order').dialog('open');
			$('#configuration_order').dialog('option', 'title', "Add Order");
		}
		if (parts[3] == 'edit') {
			var item = jQuery("#" + parent_id_table).getGridParam('selrow');
			if(item){
				jQuery("#" + parent_id_table).GridToForm(item,"#configuration_order_form");
				$("#configuration_orderslist_table").val(parent_id_table);
				$('#configuration_order').dialog('open');
				$('#configuration_order').dialog('option', 'title', "Edit Order");
			} else {
				$.jGrowl("Please select order to edit!");
			}
		}
		if (parts[3] == 'delete') {
			var item = jQuery("#" + parent_id_table).getGridParam('selrow');
			if(item){
				if(confirm('Are you sure you want to delete this order?')){
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/delete-orders-list",
						data: "orderslist_id=" + item,
						success: function(data){
							$.jGrowl(data);
							jQuery("#" + parent_id_table).trigger("reloadGrid");
						}
					});
				}
			} else {
				$.jGrowl("Please select order to delete!");
			}
		}
	});
	$("#add_cpt").click(function(){
		$("#configuration_cpt_form").clearForm();
		$("#configuration_unit").val('1');
		$("#configuration_favorite").val('0');
		$('#configuration_cpt_dialog').dialog('open');
		$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
	});
	$("#edit_cpt").click(function(){
		var item = jQuery("#cpt_list_config").getGridParam('selrow');
		if(item){ 
			jQuery("#cpt_list_config").GridToForm(item,"#configuration_cpt_form");
			$('#configuration_cpt_dialog').dialog('open');
			$('#configuration_cpt_dialog').dialog('option', 'title', "Edit CPT Code");
		} else {
			$.jGrowl("Please select CPT code to edit!");
		}
	});
	$("#delete_cpt").click(function(){
		var item = jQuery("#cpt_list_config").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-cpt",
				data: "cpt_id=" + item,
				success: function(data){
					reload_grid("cpt_list_config");
				}
			});
		} else {
			$.jGrowl("Please select CPT code to delete!");
		}
	});
	$("#configuration_cpt_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 400, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_cpt_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#configuration_cpt_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/edit-cpt-list",
						data: str,
						dataType: 'json',
						success: function(data){
							$.jGrowl(data.message);
							reload_grid("cpt_list_config");
							var origin = $("#configuration_cpt_origin").val();
							var cpt = $("#configuration_cpt_code").val();
							if (origin != "") {
								var parts = origin.split('_');
								if (parts[0] == 'billing') {
									if (parts[1] == 'cpt') {
										$('#' + origin + "_charge").val(data.charge);
									}
									if (parts[1] == 'cpt1') {
										$('#' + origin + "_charge1").val(data.charge);
									}
								}
								$('#' + origin).val(cpt);
							}
							$("#configuration_cpt_form").clearForm();
							$("#configuration_cpt_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				var origin = $("#configuration_cpt_origin").val();
				var cpt = $("#configuration_cpt_code").val();
				if (origin != "") {
					$('#' + origin).val("");
				}
				$("#configuration_cpt_form").clearForm();
				$("#configuration_cpt_dialog").dialog('close');
			}
		},
		open: function (event, ui) {
			$("#configuration_cpt").focus();
		},
		close: function (event, ui) {
			$("#configuration_cpt_form").clearForm();
			$('#configuration_cpt_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#configuration_favorite").addOption({"0":"No","1":"Yes"});
	$("#add_patient_forms").click(function(){
		$("#configuration_patient_forms_form").clearForm();
		$("#configuration_patient_forms_gender").val('b');
		$("#configuration_patient_forms_age_group").val('');
		$("#configuration_patient_forms_destination").val('');
		$('#configuration_patient_forms_dialog').dialog('open');
		$('#configuration_patient_forms_dialog').dialog('option', 'title', "Add Patient Form");
	});
	$("#edit_patient_forms").click(function(){
		var item = jQuery("#patient_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_forms_list").GridToForm(item,"#configuration_patient_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_patient_forms_json").val(data);
					$('#configuration_patient_forms_dialog').dialog('open');
					$('#configuration_patient_forms_dialog').dialog('option', 'title', "Edit Patient Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_patient_forms").click(function(){
		var item = jQuery("#patient_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("patient_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#export_patient_forms").click(function(){
		var item = jQuery("#patient_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$("#add_hpi_forms").click(function(){
		$("#configuration_hpi_forms_form").clearForm();
		$("#configuration_hpi_forms_gender").val('b');
		$("#configuration_hpi_forms_age_group").val('');
		$('#configuration_hpi_forms_dialog').dialog('open');
		$('#configuration_hpi_forms_dialog').dialog('option', 'title', "Add HPI Form");
	});
	$("#edit_hpi_forms").click(function(){
		var item = jQuery("#hpi_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#hpi_forms_list").GridToForm(item,"#configuration_hpi_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_hpi_forms_json").val(data);
					$('#configuration_hpi_forms_dialog').dialog('open');
					$('#configuration_hpi_forms_dialog').dialog('option', 'title', "Edit HPI Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_hpi_forms").click(function(){
		var item = jQuery("#hpi_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("hpi_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#export_hpi_forms").click(function(){
		var item = jQuery("#hpi_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$("#add_ros_forms").click(function(){
		$("#configuration_ros_forms_form").clearForm();
		$("#configuration_ros_forms_gender").val('b');
		$("#configuration_ros_forms_age_group").val('');
		$('#configuration_ros_forms_dialog').dialog('open');
		$('#configuration_ros_forms_dialog').dialog('option', 'title', "Add ROS Form");
	});
	$("#edit_ros_forms").click(function(){
		var item = jQuery("#ros_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#ros_forms_list").GridToForm(item,"#configuration_ros_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_ros_forms_json").val(data);
					$('#configuration_ros_forms_dialog').dialog('open');
					$('#configuration_ros_forms_dialog').dialog('option', 'title', "Edit ROS Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_ros_forms").click(function(){
		var item = jQuery("#ros_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("ros_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#default_ros_forms").click(function(){
		var item = jQuery("#ros_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/default-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("ros_forms_list");
					$.jGrowl(data);
					ros_template_renew();
				}
			});
		} else {
			$.jGrowl("Please select form to make default!");
		}
	});
	$("#export_ros_forms").click(function(){
		var item = jQuery("#ros_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$("#add_pe_forms").click(function(){
		$("#configuration_pe_forms_form").clearForm();
		$("#configuration_pe_forms_gender").val('b');
		$("#configuration_pe_forms_age_group").val('');
		$('#configuration_pe_forms_dialog').dialog('open');
		$('#configuration_pe_forms_dialog').dialog('option', 'title', "Add PE Form");
	});
	$("#edit_pe_forms").click(function(){
		var item = jQuery("#pe_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#pe_forms_list").GridToForm(item,"#configuration_pe_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_pe_forms_json").val(data);
					$('#configuration_pe_forms_dialog').dialog('open');
					$('#configuration_pe_forms_dialog').dialog('option', 'title', "Edit PE Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_pe_forms").click(function(){
		var item = jQuery("#pe_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("pe_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#default_pe_forms").click(function(){
		var item = jQuery("#pe_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/default-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("pe_forms_list");
					$.jGrowl(data);
					pe_template_renew();
				}
			});
		} else {
			$.jGrowl("Please select form to make default!");
		}
	});
	$("#export_pe_forms").click(function(){
		var item = jQuery("#pe_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$("#add_situation_forms").click(function(){
		$("#configuration_situation_forms_form").clearForm();
		$("#configuration_situation_forms_gender").val('b');
		$("#configuration_situation_forms_age_group").val('');
		$('#configuration_situation_forms_dialog').dialog('open');
		$('#configuration_situation_forms_dialog').dialog('option', 'title', "Add Situation Form");
	});
	$("#edit_situation_forms").click(function(){
		var item = jQuery("#situation_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#situation_forms_list").GridToForm(item,"#configuration_situation_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_situation_forms_json").val(data);
					$('#configuration_situation_forms_dialog').dialog('open');
					$('#configuration_situation_forms_dialog').dialog('option', 'title', "Edit Situation Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_situation_forms").click(function(){
		var item = jQuery("#situation_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("situation_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#export_situation_forms").click(function(){
		var item = jQuery("#situation_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$("#add_referral_forms").click(function(){
		$("#configuration_referral_forms_form").clearForm();
		$("#configuration_referral_forms_gender").val('b');
		$("#configuration_referral_forms_age_group").val('');
		$('#configuration_referral_forms_dialog').dialog('open');
		$('#configuration_referral_forms_dialog').dialog('option', 'title', "Add Referral Form");
	});
	$("#edit_referral_forms").click(function(){
		var item = jQuery("#referral_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#referral_forms_list").GridToForm(item,"#configuration_referral_forms_form");
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/get-template",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_referral_forms_json").val(data);
					$('#configuration_referral_forms_dialog').dialog('open');
					$('#configuration_referral_forms_dialog').dialog('option', 'title', "Edit Referral Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_referral_forms").click(function(){
		var item = jQuery("#referral_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/delete-template",
				data: "template_id=" + item,
				success: function(data){
					reload_grid("referral_forms_list");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#export_referral_forms").click(function(){
		var item = jQuery("#referral_forms_list").getGridParam('selrow');
		if(item){
			window.open("templatedownload/"+item);
		} else {
			$.jGrowl("Please select form to export!");
		}
	});
	$(".configuration_gender").addOption({"b":"Both","m":"Male","f":"Female"});
	$(".configuration_age_group").addOption({"":"All","adult":"Adult","child":"Child"});
	$("#configuration_patient_forms_destination").addOption({"":"Select Encounter/Chart Destination.","HPI":"History of Present Illness","PMH":"Past Medical History","PSH":"Past Surgical History","FH":"Family History","SH":"Social History"});
	$(".configuration_fieldtype").addOption({"":"Select Field Type.","text":"Text","radio":"Radio Buttons - User can select only one option.","checkbox":"Checkbox - User can select multiple options.","select":"Drop down list"}).on("change", function(){
		var a = $(this).val();
		var id = $(this).attr("id");
		var id_parts = id.split("_");
		var id_main1 = id_parts[1] + "_" + id_parts[2];
		var id2 = id_parts[2].replace("s", "");
		var id_main2 = id_parts[1] + "_" + id2;
		if (a == 'radio' || a == 'checkbox' || a == 'select') {
			$("#" + id_main1 + "_template_div_options").html('<div class="pure-control-group"><label for="configuration_' + id_main1 + '_option_1">Option: <a href="#" id="configuration_' + id_main1 + '_add_option">[Add]</a></label><input type="text" id="configuration_' + id_main1 + '_option_1" style="width:290px" class="text ' + id_main1 + '_option"/></div>');
			$("#configuration_" + id_main1 + "_add_option").on("click",function() {
				var a = $("." + id_main1 + "_option:last").attr("id");
				var a1 = a.split("_");
				var count = parseInt(a1[4]) + 1;
				$("#" + id_main1 + "_template_div_options").append('<div class="pure-control-group"><label for="configuration_' + id_main1 + '_option_' + count + '">Option: <a href="#" id="configuration_' + id_main1 + '_option_' + count +'_remove"class="' + id_main1 + '_remove_option">[Remove]</a></label><input type="text" id="configuration_' + id_main1 + '_option_' + count + '" style="width:290px" class="text ' + id_main1 + '_option"/></div>');
				$('#configuration_' + id_main1 + '_option_' + count).focus();
				$("." + id_main1 + "_remove_option").on("click",function() {
					$(this).parents(".pure-control-group").remove();
				});
			});
		} else {
			$("#" + id_main1 + "_template_div_options").html('');
		}
	});
	$(".element_save").click(function(){
		var id = $(this).attr("id");
		var id_parts = id.split("_");
		var id_main1 = id_parts[0] + "_" + id_parts[1];
		var id2 = id_parts[1].replace("s", "");
		var id_main2 = id_parts[0] + "_" + id2;
		var json_flat = $("#configuration_" + id_main1 + "_json").val();
		var json_object = JSON.parse(json_flat);
		var json_array = [json_object];
		var div_id = $("#" + id_main1 + "_div_id").val();
		if (div_id != '') {
			for (var i = 0; i < json_array[0]['html'].length; i++) {
				var a = json_array[0]['html'][i].id;
				if (a == div_id) {
					json_array[0]['html'][i]['html'][0]['html'] = $("#configuration_" + id_main1 + "_label").val();
					var f = json_array[0]['html'][i]['html'].length-2;
					json_array[0]['html'][i]['html'].splice(2,f);
					if ($("#configuration_" + id_main1 + "_fieldtype").val() == "radio" || $("#configuration_" + id_main1 + "_fieldtype").val() == "checkbox") {
						var h = 2;
						var g = h-2;
						$("." + id_main1 + "_option").each(function(){
							json_array[0]['html'][i]['html'][h] = {};
							json_array[0]['html'][i]['html'][h]['type']= $("#configuration_" + id_main1 + "_fieldtype").val();
							json_array[0]['html'][i]['html'][h]['id']= $("#" + id_main1 + "_div_id").val() + "_" + $("#configuration_" + id_main1 + "_fieldtype").val() + "_" + g;
							json_array[0]['html'][i]['html'][h]['name'] = $("#" + id_main1 + "_div_id").val();
							json_array[0]['html'][i]['html'][h]['value']= $("#configuration_" + id_main1 + "_label").val() + ": " + $(this).val();
							json_array[0]['html'][i]['html'][h]['caption'] = $(this).val();
							if (id_parts[0] != "patient" && h==2) {
								json_array[0]['html'][i]['html'][h]['class'] = id_parts[0] + "_normal";
							}
							h++;
							g++;
						});
						json_array[0]['html'][i]['class'] = id_main2 + "_div " + id_main2 + "_buttonset";
					} else {
						json_array[0]['html'][i]['html'][2] = {};
						json_array[0]['html'][i]['html'][2]['type'] = $("#configuration_" + id_main1 + "_fieldtype").val();
						json_array[0]['html'][i]['html'][2]['id'] = $("#" + id_main1 + "_div_id").val() + "_" + $("#configuration_" + id_main1 + "_fieldtype").val();
						json_array[0]['html'][i]['html'][2]['name'] = $("#" + id_main1 + "_div_id").val();
						if ($("#configuration_" + id_main1 + "_fieldtype").val() == "select") {
							if ($("#configuration_" + id_main1 + "_option_1").val() != "") {
								json_array[0]['html'][i]['html'][2]['options'] = {};
								$("." + id_main1 + "_option").each(function(){
									var value = $(this).val();
									var key = $("#configuration_" + id_main1 + "_label").val() + ": " + $(this).val();
									json_array[0]['html'][i]['html'][2]['options'][key] = value;
								});
								json_array[0]['html'][i]['class'] = id_main2 + "_div";
							}
						} else {
							json_array[0]['html'][i]['class'] = id_main2 + "_div " + id_main2 + "_text";
						}
					}
				}
			}
		} else {
			var j = json_array[0]['html'].length;
			if (id_parts[0] == 'patient') {
				var l = j-3;
			} else {
				var l = j+1;
			}
			if ($("#configuration_" + id_main1 + "_fieldtype").val() == 'text') {
				var k = id_main2 + "_div " + id_main2 + "_text";
				json_array[0]['html'][j] = {"type":"div","class":id_main2 + "_div " + id_main2 + "_text","id":id_main2 + "_div"+l,"html":[{"type":"span","id":id_main2 + "_div"+l+"_label","html":$("#configuration_" + id_main1 + "_label").val()},{"type":"br"},{"type":$("#configuration_" + id_main1 + "_fieldtype").val(),"id":id_main2 + "_div"+l+"_"+$("#configuration_" + id_main1 + "_fieldtype").val(),"name":id_main2 + "_div"+l,"value":""}]};
			}
			if ($("#configuration_" + id_main1 + "_fieldtype").val() == 'radio' || $("#configuration_" + id_main1 + "_fieldtype").val() == 'checkbox') {
				var m = 2;
				var n = m-2;
				json_array[0]['html'][j] = {"type":"div","class":id_main2 + "_div " + id_main2 + "_buttonset","id":id_main2 + "_div"+l,"html":[{"type":"span","id":id_main2 + "_div"+l+"_label","html":$("#configuration_" + id_main1 + "_label").val()},{"type":"br"}]};
				$("." + id_main1 + "_option").each(function(){
					json_array[0]['html'][j]['html'][m] = {"type":$("#configuration_" + id_main1 + "_fieldtype").val(),"id":id_main2 + "_div"+l+"_"+$("#configuration_" + id_main1 + "_fieldtype").val()+"_"+n,"name":id_main2 + "_div"+l,"value":$("#configuration_" + id_main1 + "_label").val()+": "+$(this).val(),"caption":$(this).val()};
					m++;
					n++;
				});
			}
			if ($("#configuration_" + id_main1 + "_fieldtype").val() == 'select') {
				json_array[0]['html'][j] = {"type":"div","class":id_main2 + "_div","id":id_main2 + "_div"+l,"html":[{"type":"span","id":id_main2 + "_div"+l+"_label","html":$("#configuration_" + id_main1 + "_label").val()},{"type":"br"},{"type":$("#configuration_" + id_main1 + "_fieldtype").val(),"id":id_main2 + "_div"+l+"_"+ $("#configuration_" + id_main1 + "_fieldtype").val(),"name":id_main2 + "_div"+l,"options":{}}]};
				$("." + id_main1 + "_option").each(function(){
					var value = $(this).val();
					var key = $("#configuration_" + id_main1 + "_label").val() + ": " + $(this).val();
					json_array[0]['html'][j]['html'][2]['options'][key] = value;
				});
			}
		}
		var json_flat1 = JSON.stringify(json_array[0]);
		$("#configuration_" + id_main1 + "_json").val(json_flat1);
		preview_form(id_main1);
		$("#" + id_main1 + "_template_div").clearDiv();
		$("#" + id_main1 + "_template_div_options").html('');
		$("#" + id_main1 + "_template_surround_div").hide();
	});
	$(".element_cancel").click(function(){
		var id = $(this).attr("id");
		var id_parts = id.split("_");
		var id_main1 = id_parts[0] + "_" + id_parts[1];
		var id2 = id_parts[1].replace("s", "");
		var id_main2 = id_parts[0] + "_" + id2;
		$("#" + id_main1 + "_template_div").clearDiv();
		$("#" + id_main1 + "_template_div_options").html('');
		$("#" + id_main1 + "_template_surround_div").hide();
		$("." + id_main2 + "_div").removeClass("ui-state-error");
	});
	$(".element_delete").click(function(){
		var id = $(this).attr("id");
		var id_parts = id.split("_");
		var id_main1 = id_parts[0] + "_" + id_parts[1];
		var id2 = id_parts[1].replace("s", "");
		var id_main2 = id_parts[0] + "_" + id2;
		var json_flat = $("#configuration_" + id_main1 + "_json").val();
		var json_object = JSON.parse(json_flat);
		var json_array = [json_object];
		var div_id = $("#" + id_main1 + "_div_id").val();
		for (var i = 0; i < json_array[0]['html'].length; i++) {
			var a = json_array[0]['html'][i].id;
			if (a == div_id) {
				json_array[0]['html'].splice(i,1);
			}
		}
		var json_flat1 = JSON.stringify(json_array[0]);
		$("#configuration_" + id_main1 + "_json").val(json_flat1);
		preview_form(id_main1);
		$("#" + id_main1 + "_template_div").clearDiv();
		$("#" + id_main1 + "_template_div_options").html('');
		$("#" + id_main1 + "_template_surround_div").hide();
	});
	$("#configuration_patient_forms_scoring").tooltip({ content: "Fill this field if you wish to create a scoring algorithm for this form.  The score value for a radio button or checkbox is determined automatically based on the order of the list that you create.  The first selection always starts with a value of 0, the second selection is 1, and so on." });
	$("#configuration_patient_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_patient_forms_title").focus();
			$("#patient_forms_template_surround_div").hide();
			preview_form('patient_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_patient_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_patient_forms_json").val();
					var json_object = JSON.parse(json_flat);
					json_object.html[2].value = $("#configuration_patient_forms_title").val();
					json_object.html[3].value = $("#configuration_patient_forms_destination").val();
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_patient_forms_json").val(json_flat1);
					var str = $("#configuration_patient_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-patient-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("patient_forms_list");
							$("#configuration_patient_forms_form").clearForm();
							$("#configuration_patient_forms_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_patient_forms_form").clearForm();
				$("#configuration_patient_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_patient_forms_form").clearForm();
			$('#configuration_patient_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#patient_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#patient_forms_template_surround_div").is(":hidden")) {
			$("#patient_forms_template_div").clearDiv();
			$("#patient_forms_template_div_options").html('');
			$("#configuration_patient_forms_fieldtype").val('');
			$("#patient_forms_template_surround_div").show();
			$("#configuration_patient_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	$("#configuration_hpi_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_hpi_forms_title").focus();
			$("#hpi_forms_template_surround_div").hide();
			preview_form('hpi_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_hpi_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_hpi_forms_json").val();
					var json_object = JSON.parse(json_flat);
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_hpi_forms_json").val(json_flat1);
					var str = $("#configuration_hpi_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-hpi-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("hpi_forms_list");
							$("#configuration_hpi_forms_form").clearForm();
							$("#configuration_hpi_forms_dialog").dialog('close');
							hpi_template_renew();
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_hpi_forms_form").clearForm();
				$("#configuration_hpi_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_hpi_forms_form").clearForm();
			$('#configuration_hpi_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#hpi_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#hpi_forms_template_surround_div").is(":hidden")) {
			$("#hpi_forms_template_div").clearDiv();
			$("#hpi_forms_template_div_options").html('');
			$("#configuration_hpi_forms_fieldtype").val('');
			$("#hpi_forms_template_surround_div").show();
			$("#configuration_hpi_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	$("#configuration_ros_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_ros_forms_title").focus();
			$("#ros_forms_template_surround_div").hide();
			preview_form('ros_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_ros_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_ros_forms_json").val();
					var json_object = JSON.parse(json_flat);
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_ros_forms_json").val(json_flat1);
					var str = $("#configuration_ros_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-ros-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("ros_forms_list");
							$("#configuration_ros_forms_form").clearForm();
							$("#configuration_ros_forms_dialog").dialog('close');
							ros_template_renew();
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_ros_forms_form").clearForm();
				$("#configuration_ros_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_ros_forms_form").clearForm();
			$('#configuration_ros_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#configuration_ros_forms_group").addOption({"ros_gen":"General","ros_eye":"Eye","ros_ent":"Ear, Nose, and Throat","ros_resp":"Respiratory","ros_cv":"Cardiovascular","ros_gi":"Gastrointestinal","ros_gu":"Genitourinary","ros_mus":"Musculoskeletal","ros_neuro":"Neurological","ros_psych":"Psychological","ros_heme":"Hematological/Lymphatic","ros_endocrine":"Endocrine","ros_skin":"Skin","ros_wcc":"Well Child Check","ros_psych1":"Depression","ros_psych2":"Anxiety","ros_psych3":"Bipolar","ros_psych4":"Mood Disorders","ros_psych5":"ADHD","ros_psych6":"PTSD","ros_psych7":"Substance Related Disorder","ros_psych8":"Obsessive Compulsive Disorder","ros_psych9":"Social Anxiety Disorder","ros_psych10":"Autistic Disorder","ros_psych11":"Asperger's Disorder"});
	$("#ros_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#ros_forms_template_surround_div").is(":hidden")) {
			$("#ros_forms_template_div").clearDiv();
			$("#ros_forms_template_div_options").html('');
			$("#configuration_ros_forms_fieldtype").val('');
			$("#ros_forms_template_surround_div").show();
			$("#configuration_ros_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	$("#configuration_pe_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_pe_forms_title").focus();
			$("#pe_forms_template_surround_div").hide();
			preview_form('pe_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_pe_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_pe_forms_json").val();
					var json_object = JSON.parse(json_flat);
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_pe_forms_json").val(json_flat1);
					var str = $("#configuration_pe_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-pe-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("pe_forms_list");
							$("#configuration_pe_forms_form").clearForm();
							$("#configuration_pe_forms_dialog").dialog('close');
							pe_template_renew();
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_pe_forms_form").clearForm();
				$("#configuration_pe_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_pe_forms_form").clearForm();
			$('#configuration_pe_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#configuration_pe_forms_group").addOption({"pe_gen1":"General","pe_eye1":"Eye: Conjunctiva and Lids","pe_eye2":"Eye: Pupil and Iris","pe_eye3":"Eye: Fundoscopic","pe_ent1":"Ears, Nose, Throat: External Ear and Nose","pe_ent2":"Ears, Nose, Throat: Canals and Tympanic Membrane","pe_ent3":"Ears, Nose, Throat: Hearing Assessment","pe_ent4":"Ears, Nose, Throat: Sinuses, Mucosa, Septum, and Turbinates","pe_ent5":"Ears, Nose, Throat: Lips, Teeth, and Gums","pe_ent6":"Ears, Nose, Throat: Oropharynx","pe_neck1":"Neck: General","pe_neck2":"Neck: Thyroid","pe_resp1":"Respiratory: Effort","pe_resp2":"Respiratory: Percussion","pe_resp3":"Respiratory: Palpation","pe_resp4":"Respiratory: Auscultation","pe_cv1":"Cardiovascular: Palpation","pe_cv2":"Cardiovascular: Auscultation","pe_cv3":"Cardiovascular: Carotid Arteries","pe_cv4":"Cardiovascular: Abdominal Aorta","pe_cv5":"Cardiovascular: Femoral Arteries","pe_cv6":"Cardiovascular: Extremities","pe_ch1":"Chest: Inspection","pe_ch2":"Chest: Palpation","pe_gi1":"Gastrointestinal: Masses and Tenderness","pe_gi2":"Gastrointestinal: Liver and Spleen","pe_gi3":"Gastrointestinal: Hernia","pe_gi4":"Gastrointestinal: Anus, Perineum, and Rectum","pe_gu1":"Genitourinary (female): Genitalia","pe_gu2":"Genitourinary (female): Urethra","pe_gu3":"Genitourinary (female): Bladder","pe_gu4":"Genitourinary (female): Cervix","pe_gu5":"Genitourinary (female): Uterus","pe_gu6":"Genitourinary (female): Adnexa","pe_gu7":"Genitourinary (male): Scrotum","pe_gu8":"Genitourinary (male): Penis","pe_gu9":"Genitourinary (male): Prostate","pe_lymph1":"Lymphatic: Neck","pe_lymph2":"Lymphatic: Axillae","pe_lymph3":"Lymphatic: Groin","pe_ms1":"Musculoskeletal: Gait and Station","pe_ms2":"Musculoskeletal: Digits and Nails","pe_ms3":"Musculoskeletal: Bones, Joints, and Muscles - Shoulder","pe_ms4":"Musculoskeletal: Bones, Joints, and Muscles - Elbow","pe_ms5":"Musculoskeletal: Bones, Joints, and Muscles - Wrist","pe_ms6":"Musculoskeletal: Bones, Joints, and Muscles - Hand","pe_ms7":"Musculoskeletal: Bones, Joints, and Muscles - Hip","pe_ms8":"Musculoskeletal: Bones, Joints, and Muscles - Knee","pe_ms9":"Musculoskeletal: Bones, Joints, and Muscles - Ankle","pe_ms10":"Musculoskeletal: Bones, Joints, and Muscles - Foot","pe_ms11":"Musculoskeletal: Bones, Joints, and Muscles - Cervical Spine","pe_ms12":"Musculoskeletal: Bones, Joints, and Muscles - Thoracic and Lumbar Spine","pe_neuro1":"Neurological: Cranial Nerves","pe_neuro2":"Neurological: Deep Tendon Reflexes","pe_neuro3":"Neurological: Sensation and Motor","pe_psych1":"Psychological: Judgement and Insight","pe_psych2":"Psychological: Orientation","pe_psych3":"Psychological: Memory","pe_psych4":"Psychological: Mood and Affect","pe_skin1":"Skin: Inspection","pe_skin2":"Skin: Palpation","pe_constitutional1":"Constitutional","pe_mental1":"Mental Status Examination"});
	$("#pe_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#pe_forms_template_surround_div").is(":hidden")) {
			$("#pe_forms_template_div").clearDiv();
			$("#pe_forms_template_div_options").html('');
			$("#configuration_pe_forms_fieldtype").val('');
			$("#pe_forms_template_surround_div").show();
			$("#configuration_pe_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	$("#configuration_situation_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_situation_forms_title").focus();
			$("#situation_forms_template_surround_div").hide();
			preview_form('situation_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_situation_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_situation_forms_json").val();
					var json_object = JSON.parse(json_flat);
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_situation_forms_json").val(json_flat1);
					var str = $("#configuration_situation_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-situation-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("situation_forms_list");
							$("#configuration_situation_forms_form").clearForm();
							$("#configuration_situation_forms_dialog").dialog('close');
							situation_template_renew();
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_situation_forms_form").clearForm();
				$("#configuration_situation_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_situation_forms_form").clearForm();
			$('#configuration_situation_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#situation_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#situation_forms_template_surround_div").is(":hidden")) {
			$("#situation_forms_template_div").clearDiv();
			$("#situation_forms_template_div_options").html('');
			$("#configuration_situation_forms_fieldtype").val('');
			$("#situation_forms_template_surround_div").show();
			$("#configuration_situation_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	$("#configuration_referral_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			$("#configuration_referral_forms_title").focus();
			$("#referral_forms_template_surround_div").hide();
			preview_form('referral_forms');
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_referral_forms_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var json_flat = $("#configuration_referral_forms_json").val();
					var json_object = JSON.parse(json_flat);
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_referral_forms_json").val(json_flat1);
					var str = $("#configuration_referral_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-referral-form/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("referral_forms_list");
							$("#configuration_referral_forms_form").clearForm();
							$("#configuration_referral_forms_dialog").dialog('close');
							referral_template_renew();
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_referral_forms_form").clearForm();
				$("#configuration_referral_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_referral_forms_form").clearForm();
			$('#configuration_referral_forms_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#referral_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#referral_forms_template_surround_div").is(":hidden")) {
			$("#referral_forms_template_div").clearDiv();
			$("#referral_forms_template_div_options").html('');
			$("#configuration_referral_forms_fieldtype").val('');
			$("#referral_forms_template_surround_div").show();
			$("#configuration_referral_forms_label").focus();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	function preview_form(type) {
		var type1 = type.replace("forms", "form");
		if ($("#configuration_" + type + "_json").val() == '') {
			if (type == 'patient_forms') {
				var default_json = '{"html":[{"type":"hidden","class":"' + type1 + '_hidden","value":"","id":"form_template_id","name":"template_id"},{"type":"hidden","class":"' + type1 + '_hidden","value":"","id":"form_forms_content","name":"forms_content"},{"type":"hidden","class":"' + type1 + '_hidden","value":"","id":"form_forms_title","name":"forms_title"},{"type":"hidden","class":"' + type1 + '_hidden","value":"","id":"form_forms_destination","name":"forms_destination"},{"type":"div","class":"' + type1 + '_div ' + type1 + '_buttonset","id":"' + type1 + '_div1","html":[{"type":"span","id":"' + type1 + '_div1_label","html":"Radio Button Question"},{"type":"br"},{"type":"radio","id":"' + type1 + '_div1_radio_0","name":"' + type1 + '_div1","value":"Radio Button Question: No","caption":"No"},{"type":"radio","id":"' + type1 + '_div1_radio_1","name":"' + type1 + '_div1","value":"Radio Button Question: Yes","caption":"Yes"}]},{"type":"div","class":"' + type1 + '_div ' + type1 + '_text","id":"' + type1 + '_div2","html":[{"type":"span","id":"' + type1 + '_div2_label","html":"Text Question"},{"type":"br"},{"type":"text","id":"' + type1 + '_div2_text","name":"' + type1 + '_div2","value":""}]},{"type":"div","class":"' + type1 + '_div","id":"' + type1 + '_div3","html":[{"type":"span","id":"' + type1 + '_div3_label","html":"Select List Question"},{"type":"br"},{"type":"select","id":"' + type1 + '_div3_select","name":"' + type1 + '_div3","options":{"Select List Question: No":"No","Select List Question: Yes":"Yes"}}]}]}';
			} else {
				var default_json = '{"html":[{"type":"div","class":"' + type1 + '_div ' + type1 + '_buttonset","id":"' + type1 + '_div1","html":[{"type":"span","id":"' + type1 + '_div1_label","html":"Radio Button Question"},{"type":"br"},{"type":"radio","id":"' + type1 + '_div1_radio_0","name":"' + type1 + '_div1","value":"Radio Button Question: No","caption":"No"},{"type":"radio","id":"' + type1 + '_div1_radio_1","name":"' + type1 + '_div1","value":"Radio Button Question: Yes","caption":"Yes"}]},{"type":"div","class":"' + type1 + '_div ' + type1 + '_text","id":"' + type1 + '_div2","html":[{"type":"span","id":"' + type1 + '_div2_label","html":"Text Question"},{"type":"br"},{"type":"text","id":"' + type1 + '_div2_text","name":"' + type1 + '_div2","value":""}]},{"type":"div","class":"' + type1 + '_div","id":"' + type1 + '_div3","html":[{"type":"span","id":"' + type1 + '_div3_label","html":"Select List Question"},{"type":"br"},{"type":"select","id":"' + type1 + '_div3_select","name":"' + type1 + '_div3","options":{"Select List Question: No":"No","Select List Question: Yes":"Yes"}}]}]}';
			}
			$("#configuration_" + type + "_json").val(default_json);
		} else {
			var default_json = $("#configuration_" + type + "_json").val();
		}
		var default_json_object = JSON.parse(default_json);
		$("#" + type + "_preview").html('');
		$("#" + type + "_preview").dform(default_json_object);
		$("." + type1 + "_buttonset").buttonset();
		$('.' + type1 + '_text input[type="text"]').css("width","280px");
		$('.' + type1 + '_div select').addClass("text ui-widget-content ui-corner-all");
		if (type == 'patient_forms') {
			if (default_json_object.html[2].value != "") {
				$("#configuration_" + type + "_title").val(default_json_object.html[2].value);
			}
			if (default_json_object.html[3].value != "") {
				$("#configuration_" + type + "_destination").val(default_json_object.html[3].value);
			}
		}
		$("." + type1 + "_div").css("padding","5px").on("click", function(){
			if($("#" + type + "_template_surround_div").is(":hidden")) {
				$(this).addClass("ui-state-error");
				$(this).siblings().removeClass("ui-state-error");
				var div_id = $(this).attr('id');
				$("#" + type + "_div_id").val(div_id);
				var json_flat = $("#configuration_" + type + "_json").val();
				var json_object = JSON.parse(json_flat);
				for (var i = 0; i < Object.size(json_object.html); i++) {
					var a = json_object.html[i].id;
					if (a == div_id) {
						$("#configuration_" + type + "_label").val(json_object.html[i].html[0].html);
						$("#configuration_" + type + "_fieldtype").val(json_object.html[i].html[2].type);
						if (json_object.html[i].html[2].type != "text") {
							$("#" + type + "_template_div_options").html('<div class="pure-control-group"><label for="configuration_' + type + '_option_1">Option: <a href="#" id="configuration_' + type + '_add_option">[Add]</a></label><input type="text" id="configuration_' + type + '_option_1" style="width:290px" class="text ' + type + '_option"/></div>');
							$("#configuration_" + type + "_add_option").on("click", function() {
								var a = $("." + type + "_option:last").attr("id");
								var a1 = a.split("_");
								var count = parseInt(a1[4]) + 1;
								$("#" + type + "_template_div_options").append('<div class="pure-control-group"><label for="configuration_' + type + '_option_' + count + '">Option: <a href="#" id="configuration_' + type + '_option_' + count +'_remove" class="' + type + '_remove_option">[Remove]</a></label><input type="text" id="configuration_' + type + '_option_' + count + '" style="width:290px" class="text ' + type + '_option"/></div>');
								$("#configuration_" + type + "_option_" + count).focus();
								$("." + type + "_remove_option").on("click",function() {
									$(this).parents(".pure-control-group").remove();
								});
							});
							if (json_object.html[i].html[2].type == "select") {
								var j = 1;
								$.each(json_object.html[i].html[2].options, function(k, v) {
									if (j > 1) {
										$("#" + type + "_template_div_options").append('<div class="pure-control-group"><label for="configuration_' + type + '_option_' + j + '">Option: <a href="#" id="configuration_' + type + '_option_' + j +'_remove" class="' + type + '_remove_option">[Remove]</a></label><input type="text" id="configuration_' + type + '_option_' + j + '" style="width:290px" class="text ' + type + '_option"/></div>');
										$("." + type + "_remove_option").on("click",function() {
											$(this).parents(".pure-control-group").remove();
										});
									}
									$("#configuration_" + type + "_option_" + j).val(v);
									j++;
								});
							} else {
								var l = 1;
								for (var k = 2; k < Object.size(json_object.html[i].html); k++) {
									if (l > 1) {
										$("#" + type + "_template_div_options").append('<div class="pure-control-group"><label for="configuration_' + type + '_option_' + l + '">Option: <a href="#" id="configuration_' + type + '_option_' + l +'_remove" class="' + type + '_remove_option">[Remove]</a></label><input type="text" id="configuration_' + type + '_option_' + l + '" style="width:290px" class="text ' + type + '_option"/></div>');
										$("." + type + "_remove_option").on("click",function() {
											$(this).parents(".pure-control-group").remove();
										});
									}
										$("#configuration_" + type + "_option_" + l).val(json_object.html[i].html[k].caption);
									l++;
								}
							}
						} else {
							$("#" + type + "_template_div_options").html('');
						}
					}
				}
				$("#" + type + "_template_surround_div").show();
			} else {
				$.jGrowl("Finish editing current form element!");
			}
		});
	}
	Object.size = function(obj) {
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
	};
	var myTemplateUpload = $("#import_template").upload({
		action: 'templateupload',
		onComplete: function(data){
			$.jGrowl(data);
			reload_grid("hpi_forms_list");
			reload_grid("ros_forms_list");
			reload_grid("pe_forms_list");
		}
	});
	$("#configuration_textdump_group_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_textdump_group_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#configuration_textdump_group_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-textdumpgroup",
						data: str,
						success: function(data){
							$.jGrowl(data);
							reload_grid("textdump_list");
							$("#configuration_textdump_group_form").clearForm();
							$("#configuration_textdump_group_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_textdump_group_form").clearForm();
				$("#configuration_textdump_group_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_textdump_group_form").clearForm();
			$('#configuration_textdump_group_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#configuration_textdump_group_template_name").addOption(fields,false);
	$("#configuration_textdump_default").addOption({"":"No","normal":"Yes"},false);
	$("#configuration_textdump_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 400, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#configuration_textdump_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#configuration_textdump_form").serialize();
					$.ajax({
						type: "POST",
						url: "ajaxdashboard/save-textdump",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var subgrid_table_id = $("#configuration_textdump_subgrid_table_id").val();
							reload_grid(subgrid_table_id);
							$("#configuration_textdump_form").clearForm();
							$("#configuration_textdump_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_textdump_form").clearForm();
				$("#configuration_textdump_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_textdump_form").clearForm();
			$('#configuration_textdump_dialog').dialog('option', 'title', "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#add_textdump_group").click(function(){
		$("#configuration_textdump_group_form").clearForm();
		$('#configuration_textdump_group_dialog').dialog('open');
		$('#configuration_textdump_group_dialog').dialog('option', 'title', "Add Template Group");
	});
	$("#edit_textdump_group").click(function(){
		var item = jQuery("#textdump_list").getGridParam('selrow');
		if(item){ 
			jQuery("#textdump_list").GridToForm(item,"#configuration_textdump_group_form");
			$('#configuration_textdump_group_dialog').dialog('open');
			$('#configuration_textdump_group_dialog').dialog('option', 'title', "Edit Template Group");
		} else {
			$.jGrowl("Please select group to edit!");
		}
	});
	$("#delete_textdump_group").click(function(){
		var item = jQuery("#textdump_list").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this text?')){
				$.ajax({
					type: "POST",
					url: "ajaxsearch/deletetextdump/" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#textdump_list").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select group to delete!");
		}
	});
	$("#export_textdump").click(function(){
		var item = jQuery("#textdump_list").getGridParam('selrow');
		if(item){
			window.open("texttemplatedownload/"+item);
		} else {
			$.jGrowl("Please select group to export!");
		}
	});
});
var timeoutHnd;
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd);
		timeoutHnd = setTimeout(gridReload,500);
}
function gridReload(){ 
	var mask = jQuery("#search_all_cpt").val();
	if (mask != '') {
		jQuery("#cpt_list_config").setGridParam({url:"ajaxdashboard/cpt-list/"+mask,page:1}).trigger("reloadGrid");
	} else {
		jQuery("#cpt_list_config").trigger("reloadGrid");
	}
}
