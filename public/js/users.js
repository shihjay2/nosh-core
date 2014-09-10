$(document).ready(function() {
	function checkpt(value, colname) {
		var result = null;
		$.ajax({
			url: "ajaxsetup/checkpt/" + value,
			type: "POST",
			async: false,
			success: function(data){
				if(data =='true'){
					result = [true,""];
				} else {
					result = [false,"Please enter valid patient ID"];
				}
			}
		});
		return result;
	}
	$("#users_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		close: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxsetup/check-providers",
				success: function(data){
					if (data == 'y') {
						$("#users_needed").hide();
					}
				}
			});
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#dashboard_users").click(function(){
		$("#users_accordion").accordion({heightStyle: "content"});
		jQuery("#provider_list").jqGrid({
			url:"ajaxsetup/users-list/2/1",
			editurl:"ajaxsetup/edit-users/2",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Specialty','License Number','State Licensed','NPI','NPI Taxonomy Code','UPIN','DEA Number','Medicare Number','Tax ID Number','RCopia Username'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'password',index:'password',width:1,editable:false,hidden:true},
				{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'specialty',index:'specialty',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},editoptions:{dataInit:function(elem){ 
					$(elem).autocomplete({
						source: function (req, add){
							$.ajax({
								url: "ajaxsearch/specialty",
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
					$('.ui-autocomplete').css('z-index',1000);
				}},formoptions:{elmsuffix:"(*)"}},
				{name:'license',index:'license',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'license_state',index:'license_state',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'select',editoptions:{value:"AL:Alabama;AK:Alaska;AS:America Samoa;AZ:Arizona;AR:Arkansas;CA:California;CO:Colorado;CT:Connecticut;DE:Delaware;DC:District of Columbia;FM:Federated States of Micronesia;FL:Florida;GA:Georgia;GU:Guam;HI:Hawaii;ID:Idaho;IL:Illinois;IN:Indiana;IA:Iowa;KS:Kansas;KY:Kentucky;LA:Louisiana;ME:Maine;MH:Marshall Islands;MD:Maryland;MA:Massachusetts;MI:Michigan;MN:Minnesota;MS:Mississippi;MO:Missouri;MT:Montana;NE:Nebraska;NV:Nevada;NH:New Hampshire;NJ:New Jersey;NM:New Mexico;NY:New York;NC:North Carolina;ND:North Dakota;OH:Ohio;OK:Oklahoma;OR:Oregon;PW:Palau;PA:Pennsylvania;PR:Puerto Rico;RI:Rhode Island;SC:South Carolina;SD:South Dakota;TN:Tennessee;TX:Texas;UT:Utah;VT:Vermont;VI:Virgin Island;VA:Virginia;WA:Washington;WV:West Virginia;WI:Wisconsin;WY:Wyoming"},formoptions:{elmsuffix:"(*)"}},
				{name:'npi',index:'npi',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},editoptions:{dataInit:function(elem){$(elem).mask("9999999999");}},formoptions:{elmsuffix:"(*)"}},
				{name:'npi_taxonomy',index:'npi_taxonomy',width:1,editable:true,hidden:true,editrules:{edithidden:true},edittype:'text',editoptions:{readonly:'readonly'}},
				{name:'upin',index:'upin',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'dea',index:'dea',width:1,editable:true,hidden:true,editrules:{edithidden:true},editoptions:{dataInit:function(elem){$(elem).mask("aa9999999");}}},
				{name:'medicare',index:'medicare',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'tax_id',index:'tax_id',width:1,editable:true,hidden:true,editrules:{edithidden:true},editoptions:{dataInit:function(elem){$(elem).mask("99-9999999");}}},
				{name:'rcopia_username',index:'rcopia_username',width:1,editable:true,hidden:true,editrules:{edithidden:true}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#provider_list_pager'),
			sortname: 'displayname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Medical Providers",
			emptyrecords:"No medical providers",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#provider_list_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#assistant_list").jqGrid({
			url:"ajaxsetup/users-list/3/1",
			editurl:"ajaxsetup/edit-users/3",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'password',index:'password',width:1,editable:false,hidden:true},
				{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#assistant_list_pager'),
			sortname: 'displayname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Medical Assistants",
			emptyrecords:"No medical assistants",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#assistant_list_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#billing_list").jqGrid({
			url:"ajaxsetup/users-list/4/1",
			editurl:"ajaxsetup/edit-users/4",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'password',index:'password',width:1,editable:false,hidden:true},
				{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#billing_list_pager'),
			sortname: 'displayname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Medical Billers",
			emptyrecords:"No medical billers",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#billing_list_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#patient_list").jqGrid({
			url:"ajaxsetup/users-list/100/1",
			editurl:"ajaxsetup/edit-users/100",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Associated Patient ID'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'password',index:'password',width:1,editable:false,hidden:true},
				{name:'firstname',index:'firstname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'middle',index:'middle',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'lastname',index:'lastname',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'title',index:'title',width:1,editable:true,hidden:true,editrules:{edithidden:true}},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,editable:true,hidden:true,editrules:{edithidden:true, email:true, required:true},formoptions:{elmsuffix:"(*)"}},
				{name:'pid',index:'pid',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true, custom:true, custom_func:checkpt},editoptions:{dataInit:function(elem){ 
					$(elem).autocomplete({
						source: function (req, add){
							$.ajax({
								url: "ajaxsearch/pid",
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
				}},formoptions:{elmsuffix:"(*)"}}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#patient_list_pager'),
			sortname: 'displayname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Patients with Health Record Access",
			emptyrecords:"No patients",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#patient_list_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#provider_list_inactive").jqGrid({
			url:"ajaxsetup/users-list/2/0",
			editurl:"ajaxsetup/enable",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Specialty','License Number','State Licensed','NPI','NPI Taxonomy Code','UPIN','DEA Number','Medicare Number','Tax ID Number'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300},
				{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
				{name:'firstname',index:'firstname',width:1,hidden:true},
				{name:'middle',index:'middle',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:1,hidden:true},
				{name:'title',index:'title',width:1,hidden:true},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,hidden:true},
				{name:'specialty',index:'specialty',width:1,hidden:true},
				{name:'license',index:'license',width:1,hidden:true},
				{name:'license_state',index:'license_state',width:1,hidden:true},
				{name:'npi',index:'npi',width:1,hidden:true},
				{name:'npi_taxonomy',index:'npi_taxonomy',width:1,hidden:true},
				{name:'upin',index:'upin',width:1,hidden:true},
				{name:'dea',index:'dea',width:1,hidden:true},
				{name:'medicare',index:'medicare',width:1,hidden:true},
				{name:'tax_id',index:'tax_id',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#provider_list_inactive_pager'),
			sortname: 'lastname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Inactive Medical Providers",
			emptyrecords:"No inactive medical providers",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#provider_list_inactive_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#assistant_list_inactive").jqGrid({
			url:"ajaxsetup/users-list/3/0",
			editurl:"ajaxsetup/enable",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300},
				{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
				{name:'firstname',index:'firstname',width:1,hidden:true},
				{name:'middle',index:'middle',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:1,hidden:true},
				{name:'title',index:'title',width:1,hidden:true},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,editable:true,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#assistant_list_inactive_pager'),
			sortname: 'lastname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Inactive Medical Assistants",
			emptyrecords:"No inactivemedical assistants",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#assistant_list_inactive_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#billing_list_inactive").jqGrid({
			url:"ajaxsetup/users-list/4/0",
			editurl:"ajaxsetup/enable",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300},
				{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
				{name:'firstname',index:'firstname',width:1,hidden:true},
				{name:'middle',index:'middle',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:1,hidden:true},
				{name:'title',index:'title',width:1,hidden:true},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#billing_list_inactive_pager'),
			sortname: 'lastname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Inactive Medical Billers",
			emptyrecords:"No medical inactive billers",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#billing_list_inactive_pager',{search:false,edit:false,add:false,del:false});
		jQuery("#patient_list_inactive").jqGrid({
			url:"ajaxsetup/users-list/100/0",
			editurl:"ajaxsetup/enable",
			datatype: "json",
			mtype: "POST",
			colNames:['ID','Username','Password','First Name','Middle Name','Last Name','Title','Display Name','E-mail','Associated Patient ID'],
			colModel:[
				{name:'id',index:'id',width:1,hidden:true},
				{name:'username',index:'username',width:300},
				{name:'password',index:'password',width:1,editable:true,hidden:true,editrules:{edithidden:true, required:true},edittype:'password',formoptions:{elmsuffix:"Required"}},
				{name:'firstname',index:'firstname',width:1,hidden:true},
				{name:'middle',index:'middle',width:1,hidden:true},
				{name:'lastname',index:'lastname',width:1,hidden:true},
				{name:'title',index:'title',width:1,hidden:true},
				{name:'displayname',index:'displayname',width:300},
				{name:'email',index:'email',width:1,hidden:true},
				{name:'pid',index:'pid',width:1,hidden:true}
			],
			rowNum:10,
			rowList:[10,20,30],
			pager: jQuery('#patient_list_inactive_pager'),
			sortname: 'lastname',
			viewrecords: true,
			sortorder: "asc",
			caption:"Inactive Patients with Health Record Access",
			emptyrecords:"No inactive patients",
			height: "100%",
			jsonReader: { repeatitems : false, id: "0" }
		}).navGrid('#patient_list_inactive_pager',{search:false,edit:false,add:false,del:false});
	
		$("#users_dialog").dialog('open');
	});
	$("#add_provider").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxsetup/check-admin",
			success: function(data){
				if (data == "OK") {
					jQuery("#provider_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
						var res = $.parseJSON(response.responseText);
						$("#user_id").val(res.id);
						$("#reset_password_dialog").dialog('open');
					},beforeSubmit: function(postData) {
						var regexp = /^\w+$/;
						if ( !( regexp.test( postData.username ) ) ) {
							return [false, 'Incorrect format for username: No whitespace or special characters!'];
						} else {
							return [true, ''];
						}
					}});
				} else {
					$.jGrowl(data);
					$("#practice_upgrade").show();
				}
			}
		});
	});
	$("#edit_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.',beforeSubmit: function(postData) {
				var regexp = /^\w+$/;
				if ( !( regexp.test( postData.username ) ) ) {
					return [false, 'Incorrect format for username: No whitespace or special characters!'];
				} else {
					return [true, ''];
				}
			}});
		} else {
			$.jGrowl("Please select provider to edit!");
		}
	});
	$("#disable_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){
			var id = $("#provider_list").getCell(item,'id');
			$.ajax({
				type: "POST",
				url: "ajaxsetup/disable",
				data: "id=" + id,
				success: function(data){
					jQuery("#provider_list").delRowData(item);
					reload_grid("provider_list");
				}
			});
		} else {
			$.jGrowl("Please select provider to inactivate!");
		}
	});
	$("#reset_password_provider").click(function(){
		var item = jQuery("#provider_list").getGridParam('selrow');
		if(item){
			var id = $("#provider_list").getCell(item,'id');
			$("#user_id").val(id);
			$("#reset_password_dialog").dialog('open');
		}
	});
	$("#add_assistant").click(function(){
		jQuery("#assistant_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		},beforeSubmit: function(postData) {
			var regexp = /^\w+$/;
			if ( !( regexp.test( postData.username ) ) ) {
				return [false, 'Incorrect format for username: No whitespace or special characters!'];
			} else {
				return [true, ''];
			}
		}});
	});
	$("#edit_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){ 
			jQuery("#assistant_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.',beforeSubmit: function(postData) {
				var regexp = /^\w+$/;
				if ( !( regexp.test( postData.username ) ) ) {
					return [false, 'Incorrect format for username: No whitespace or special characters!'];
				} else {
					return [true, ''];
				}
			}});
		} else {
			$.jGrowl("Please select assistant to edit!");
		}
	});
	$("#disable_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){
			var id = $("#assistant_list").getCell(item,'id');
			$.ajax({
				type: "POST",
				url: "ajaxsetup/disable",
				data: "id=" + id,
				success: function(data){
					jQuery("#assistant_list").delRowData(item);
					reload_grid("assistant_list");
				}
			});
		} else {
			$.jGrowl("Please select assistant to inactivate!");
		}
	});
	$("#reset_password_assistant").click(function(){
		var item = jQuery("#assistant_list").getGridParam('selrow');
		if(item){
			var id = $("#assistant_list").getCell(item,'id');
			$("#user_id").val(id);
			$("#reset_password_dialog").dialog('open');
		}
	});
	$("#add_billing").click(function(){
		jQuery("#billing_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		},beforeSubmit: function(postData) {
			var regexp = /^\w+$/;
			if ( !( regexp.test( postData.username ) ) ) {
				return [false, 'Incorrect format for username: No whitespace or special characters!'];
			} else {
				return [true, ''];
			}
		}});
	});
	$("#edit_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){ 
			jQuery("#billing_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.',beforeSubmit: function(postData) {
				var regexp = /^\w+$/;
				if ( !( regexp.test( postData.username ) ) ) {
					return [false, 'Incorrect format for username: No whitespace or special characters!'];
				} else {
					return [true, ''];
				}
			}});
		} else {
			$.jGrowl("Please select biller to edit!");
		}
	});
	$("#disable_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){
			var id = $("#billing_list").getCell(item,'id');
			$.ajax({
				type: "POST",
				url: "ajaxsetup/disable",
				data: "id=" + id,
				success: function(data){
					jQuery("#billing_list").delRowData(item);
					reload_grid("billing_list");
				}
			});
		} else {
			$.jGrowl("Please select biller to inactivate!");
		}
	});
	$("#reset_password_billing").click(function(){
		var item = jQuery("#billing_list").getGridParam('selrow');
		if(item){
			var id = $("#billing_list").getCell(item,'id');
			$("#user_id").val(id);
			$("#reset_password_dialog").dialog('open');
		}
	});
	$("#add_patient").click(function(){
		jQuery("#patient_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.',afterComplete: function(response, postdata){
			var res = $.parseJSON(response.responseText);
			$("#user_id").val(res.id);
			$("#reset_password_dialog").dialog('open');
		},beforeSubmit: function(postData) {
			var regexp = /^\w+$/;
			if ( !( regexp.test( postData.username ) ) ) {
				return [false, 'Incorrect format for username: No whitespace or special characters!'];
			} else {
				return [true, ''];
			}
		}});
	});
	$("#edit_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.',beforeSubmit: function(postData) {
				var regexp = /^\w+$/;
				if ( !( regexp.test( postData.username ) ) ) {
					return [false, 'Incorrect format for username: No whitespace or special characters!'];
				} else {
					return [true, ''];
				}
			}});
		} else {
			$.jGrowl("Please select patient to edit!");
		}
	});
	$("#disable_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){
			var id = $("#patient_list").getCell(item,'id');
			$.ajax({
				type: "POST",
				url: "ajaxsetup/disable",
				data: "id=" + id,
				success: function(data){
					jQuery("#patient_list").delRowData(item);
					reload_grid("patient_list");
				}
			});
		} else {
			$.jGrowl("Please select patient to inactivate!");
		}
	});
	$("#reset_password_patient").click(function(){
		var item = jQuery("#patient_list").getGridParam('selrow');
		if(item){
			var id = $("#patient_list").getCell(item,'id');
			$("#user_id").val(id);
			$("#reset_password_dialog").dialog('open');
		}
	});
	$("#reset_password_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 300, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#reset_password_password").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#reset_password_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#reset_password_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxsetup/reset-password",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#reset_password_form").clearForm();
								$("#reset_password_dialog").dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$("#reset_password_form").clearForm();
				$("#reset_password_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#view_provider").click(function(){
		var item = jQuery("#provider_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#provider_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive provider to view!");
		}
	});
	$("#enable_provider").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxsetup/check-admin",
			success: function(data){
				if (data == "OK") {
					var item = jQuery("#provider_list_inactive").getGridParam('selrow');
					if(item){
						jQuery("#provider_list_inactive").editGridRow(item,{closeAfterEdit:true});
						$("#password").val('');
						reload_grid("provider_list_inactive");
					} else {
						$.jgrowl("Please select provider to reactivate!");
					}
				} else {
					$.jGrowl(data);
					$("#practice_upgrade1").show();
				}
			}
		});
	});
	$("#view_assistant").click(function(){
		var item = jQuery("#assistant_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#assistant_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive assistant to view!");
		}
	});
	$("#enable_assistant").click(function(){
		var item = jQuery("#assistant_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#assistant_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			reload_grid("assistant_list_inactive");
		} else {
			$.jgrowl("Please select assistant to reactivate!");
		}
	});
	$("#view_billing").click(function(){
		var item = jQuery("#billing_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#billing_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive biller to view!");
		}
	});
	$("#enable_billing").click(function(){
		var item = jQuery("#billing_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#billing_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			reload_grid("billing_list_inactive");
		} else {
			$.jgrowl("Please select biller to reactivate!");
		}
	});
	$("#view_patient").click(function(){
		var item = jQuery("#patient_list_inactive").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_list_inactive").viewGridRow(item);
		} else {
			$.jgrowl("Please select inactive patient to view!");
		}
	});
	$("#enable_patient").click(function(){
		var item = jQuery("#patient_list_inactive").getGridParam('selrow');
		if(item){
			jQuery("#patient_list_inactive").editGridRow(item,{closeAfterEdit:true});
			$("#password").val('');
			reload_grid("patient_list_inactive");
		} else {
			$.jgrowl("Please select patient to reactivate!");
		}
	});
});
