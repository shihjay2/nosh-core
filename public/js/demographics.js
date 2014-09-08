$(document).ready(function() {
	$("#demographics_accordion").accordion({
		heightStyle: "content",
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			$("#" + id + " .text").first().focus();
		}
	});
	$("#demographics_accordion .ui-accordion-content").each(function(){
		$(this).find(".text").last().on('keydown', function(e) {
			if (e.which == 9) {
				if (!e.shiftKey) {
					var active = $("#demographics_accordion").accordion("option", "active");
					if (active < 3) {
						$("#demographics_accordion").accordion("option", "active", active + 1);
					}
				}
			}
		});
	});
	$("#guardian_import").button().click(function(){
		$('#menu_guardian_address').val($('#menu_address').val());
		$('#menu_guardian_city').val($('#menu_city').val());
		$('#menu_guardian_zip').val($('#menu_zip').val());
		$('#menu_guardian_phone_home').val($('#menu_phone_home').val());
		$('#menu_guardian_phone_cell').val($('#menu_phone_cell').val());
		$('#menu_guardian_phone_work').val($('#menu_phone_work').val());
	});
	$("#demographics_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#menu_autocomplete_patient").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/demographics-copy",
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
				select: function( event, ui ) {
					$.each(ui.item, function(key, value){
						if (key != 'label' || key != 'value') {
							$("#edit_demographics_form :input[name='" + key + "']").val(value);
						}
						
					});
				}
			});
			$(".address_autocomplete").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/address",
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
			$(".city_autocomplete").autocomplete({
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
			$("#menu_guardian_relationship").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/guardian-relationship",
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
				select: function( event, ui ) {
					$("#menu_guardian_code").val(ui.item.code);
				}
			});
			$("#menu_preferred_provider").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/provider",
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
			$("#menu_preferred_pharmacy").autocomplete({
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
				minLength: 3
			});
			$("#menu_race").autocomplete({
				source: race_options,
				minLength: 0,
				delay: 0,
				select: function( event, ui ) {
					$("#menu_race_code").val(ui.item.code);
				}
			}).click(function () {
				$(this).autocomplete("search", "");
			});
			$("#menu_ethnicity").autocomplete({
				source: ethnicity_options,
				minLength: 0,
				delay: 0,
				select: function( event, ui ) {
					$("#menu_ethnicity_code").val(ui.item.code);
				}
			}).click(function () {
				$(this).autocomplete("search", "");
			});
			$("#menu_language").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/language",
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
				select: function( event, ui ) {
					$("#menu_lang_code").val(ui.item.code);
				}
			});
		},
		close: function(event, ui) {
			$('#edit_demographics_form').clearForm();
			if(noshdata.group_id != '100') {
				menu_update('demographics');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#demographics_insurance_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#demographics_insurance_details").html("");
			$("#demographics_insurance").jqGrid('GridUnload');
			$("#demographics_insurance").jqGrid({
				url: "ajaxdashboard/insurance",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Phone','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
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
					{name:'insurance_insu_phone',index:'insurance_insu_phone',width:1,hidden:true},
					{name:'insurance_copay',index:'insurance_copay',width:1,hidden:true},
					{name:'insurance_deductible',index:'insurance_deductible',width:1,hidden:true},
					{name:'insurance_comments', index:'insurance_comments',width:1,hidden:true},
					{name:'address_id',index:'address_id',width:1,hidden:true},
					{name:'insurance_relationship',index:'insurance_relationship',width:1,hidden:true}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: $('#demographics_insurance_pager'),
				sortname: 'insurance_order',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Insurance Payors",
			 	height: "100%",
			 	onSelectRow: function(id){
			 		var copay = $("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = $("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = $("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '<strong>Additional insurance information for ' + $("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_pager',{search:false,edit:false,add:false,del:false});
			$("#demographics_insurance_inactive").jqGrid('GridUnload')
			$("#demographics_insurance_inactive").jqGrid({
				url: "ajaxdashboard/insurance-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Insurance','ID Number','Group Number','Priority','Lastname','Firstname','DOB','Gender','Address','City','State','Zip','Copay','Deductible','Comments','Address ID','Relationship'],
				colModel:[
					{name:'insurance_id',index:'insurance_id',width:1,hidden:true},
					{name:'insurance_plan_name',index:'insurance_plan_name',width:450},
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
				pager: $('#demographics_insurance_inactive_pager'),
				sortname: 'insurance_plan_name',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Inactive Insurance Payors",
			 	height: "100%",
			 	hiddengrid: true,
			 	onSelectRow: function(id){
			 		var copay = $("#demographics_insurance").getCell(id,'insurance_copay');
					var deductible = $("#demographics_insurance").getCell(id,'insurance_deductible');
					var comments = $("#demographics_insurance").getCell(id,'insurance_comments');
					var text = '<strong>Additional insurance information for ' + $("#demographics_insurance").getCell(id,'insurance_plan_name') + ":</strong><br>";
					if(copay != ''){
						text += "Copay: " + copay + "<br>";
					}
					if(deductible != ''){
						text += "Deductible: " + deductible + "<br>";
					}
					if (comments != ''){
						text += "Comments: " + comments;
					}
					$("#demographics_insurance_details").html(text);
				},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
			$.ajax({
				url: "ajaxsearch/insurance3",
				dataType: "json",
				type: "POST",
				success: function(data){
					if(data.response =='true'){
						$("#menu_insurance_plan_select").addOption({"":"Select or add insurance provider."}, false);
						$("#menu_insurance_plan_select").addOption(data.message, false);
					} else {
						$("#menu_insurance_plan_select").addOption({"":"No insurance providers.  Click Add."}, false);
					}
				}
			});
		},
		close: function(event, ui) {
			$("#edit_menu_insurance_main_form").clearForm();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	
	$("#menu_gender").addOption(gender, false);
	$("#menu_marital_status").addOption(marital, false);
	$("#menu_state").addOption(states, false);
	$("#menu_guardian_state").addOption(states, false);
	$("#menu_DOB").mask("99/99/9999");
	$("#menu_DOB").datepicker();
	$("#menu_ss").mask("999-99-9999");
	var race_options = [
		{
			value: "American Indian or Alaska Native",
			label: "American Indian or Alaska Native",
			code: "1002-5"
		},
		{
			value: "Asian",
			label: "Asian",
			code: "2028-9"
		},
		{
			value: "Black or African American",
			label: "Black or African American",
			code: "2054-5"
		},
		{
			value: "Native Hawaiian or Other Pacific Islander",
			label: "Native Hawaiian or Other Pacific Islander",
			code: "2076-8"
		},
		{
			value: "White",
			label: "White",
			code: "2106-3"
		}
	];
	var ethnicity_options = [
		{
			value: "Hispanic or Latino",
			label: "Hispanic or Latino",
			code: "2135-2"
		},
		{
			value: "Not Hispanic or Latino",
			label: "Not Hispanic or Latino",
			code: "2186-5"
		}
	];
	$("#menu_reminder_method").addOption({"":"","Email":"Email","Cellular Phone":"Cellular Phone"}, false);
	$("#menu_cell_carrier").addOption({"":"","txt.att.net":"AT&T","sms.mycricket.com":"Cricket","messaging.nextel.com":"Nextel","qwestmp.com":"Qwest","messaging.sprintpcs.com":"Sprint(PCS)","number@page.nextel.com":"Sprint(Nextel)","tmomail.net":"T-Mobile","email.uscc.net":"US Cellular","vtext.com":"Verizon","vmobl.com":"Virgin Mobile"}, false);
	$("#menu_active").addOption({"1":"Active","0":"Inactive"}, false);
	$(".demographics_list").click(function() {
		open_demographics();
	});
	$("#patient_demographics").click(function() {
		$('#demographics_list_dialog').dialog('option', {
			height: $("#maincontent").height(),
			width: $("#maincontent").width(),
			position: { my: 'left top', at: 'left top', of: '#maincontent' }
		});
		open_demographics();
	});
	function save_demographics(type) {
		var a = $("#menu_reminder_method").val();
		var b = $("#menu_cell_carrier").val();
		var c = $("#menu_email").val();
		var d = false;
		var regexp = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
		if ( c != '') {
			if ( !( regexp.test( c ) ) ) {
				$.jGrowl("Email format is incorrect!");
				d = true;
			}
		}
		if (a == "Cellular Phone" && b == "") {
			$.jGrowl("Cellular carrier needs to be completed for cellular phone appointment reminders!");
			d = true;
		}
		if (d == false) {
			var str = $("#edit_demographics_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/edit-demographics",
					data: str,
					success: function(data){
						$.jGrowl('Your information is updated!');
						if (noshdata.group_id != '100') {
							$.ajax({
								type: "POST",
								url: "ajaxchart/demographics-load",
								dataType: "json",
								success: function(data){
									$('#menu_ptname').html(data.ptname);
									$('#menu_nickname').html(data.nickname);
									$('#menu_dob').html(data.dob);
									$('#menu_age').html(data.age);
									$('#menu_gender1').html(data.gender);
									if (type == 'close') {
										$("#demographics_list_dialog").dialog('close');
									}
								}
							});
						}
						if (type == 'close') {
							$("#demographics_list_dialog").dialog('close');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form.");
			}
		}
	}
	$("#save_menu_demographics").click(function() {
		save_demographics('open');
	});
	$("#save_menu_demographics1").click(function() {
		save_demographics('close');
	});
	$("#cancel_menu_demographics").click(function() {
		$("#edit_demographics_form").clearForm();
		$("#demographics_list_dialog").dialog('close');
	});
	$("#insurance_menu_demographics").button({icons: {primary: "ui-icon-suitcase"}}).click(function() {
		$("#demographics_insurance_dialog").dialog('open');
	});
	$("#demographics_add_insurance").button().click(function(){
		$('#edit_menu_insurance_main_form').clearForm();
		$('#menu_insurance_plan_select').val('');
		$("#add_insurance_plan span").text("Add Insurance Provider");
		$('#menu_insurance_main_dialog').dialog('open');
	});
	$("#demographics_edit_insurance").button().click(function(){
		var item = $("#demographics_insurance").getGridParam('selrow');
		if(item){
			$("#demographics_insurance").GridToForm(item,"#edit_menu_insurance_main_form");
			var dob1 = $("#menu_insurance_insu_dob").val();
			var dob = editDate1(dob1);
			$("#menu_insurance_insu_dob").val(dob);
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
			$('#menu_insurance_main_dialog').dialog('open');
		} else {
			$.jGrowl("Please select insurance to edit!")
		}
	});
	function updateinsurance() {
		reload_grid("demographics_insurance");
		reload_grid("demographics_insurance_inactive");
		reload_grid("messages_lab_insurance_grid");
		reload_grid("messages_rad_insurance_grid");
		reload_grid("messages_cp_insurance_grid");
		reload_grid("messages_ref_insurance_grid");
	}
	$("#demographics_inactivate_insurance").button().click(function(){
		var item = $("#demographics_insurance").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/inactivate-insurance",
				data: "insurance_id=" + item,
				success: function(data){
					$.jGrowl(data);
					updateinsurance();
				}
			});
			$("#demographics_insurance").delRowData(item);
		} else {
			$.jGrowl("Please select insurance to inactivate!")
		}
	});
	$("#demographics_delete_insurance").button().click(function(){
		var item = $("#demographics_insurance").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this insurance?  This is not recommended unless entering the insurance was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "ajaxdashboard/delete-insurance",
					data: "insurance_id=" + item,
					success: function(data){
						$.jGrowl(data);
						updateinsurance();
					}
				});
			}
		} else {
			$.jGrowl("Please select insurance to delete!")
		}
	});
	$("#demographics_reactivate_insurance").button().click(function(){
		var item = $("#demographics_insurance_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/reactivate-insurance",
				data: "insurance_id=" + item,
				success: function(data){
					$.jGrowl(data);
					updateinsurance();
				}
			});
		} else {
			$.jGrowl("Please select medication to reactivate!")
		}
	});
	$("#menu_insurance_main_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$(".address_autocomplete").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/address",
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
			$(".city_autocomplete").autocomplete({
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
			$("#menu_insurance_plan_select").focus();
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_menu_insurance_main_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_menu_insurance_main_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxdashboard/edit-insurance",
							data: str,
							success: function(data){
								$.jGrowl(data);
								$("#edit_menu_insurance_main_form").clearForm();
								$("#menu_insurance_main_dialog").dialog('close');
								updateinsurance();
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_menu_insurance_main_form').clearForm();
				$("#menu_insurance_main_dialog").dialog('close');
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_main_form').clearForm();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#demographics_insurance_plan_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$(".address_autocomplete").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/address",
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
			$(".city_autocomplete").autocomplete({
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
			$("#menu_insurance_plan_facility").focus();
			var id = $("#menu_insurance_plan_select").val();
			if (id != "") {
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "Edit Insurance Provider");
				$.ajax({
					type: "POST",
					url: "ajaxsearch/insurance1",
					data: "address_id=" + id,
					dataType: "json",
					success: function(data){
						$.each(data, function(key, value){
							$("#edit_menu_insurance_plan_form :input[name='" + key + "']").val(value);
						});
					}
				});
			} else {
				$("#demographics_insurance_plan_dialog").attr("option", "title", "Add Insurance Provider");
				$("#menu_insurance_box_31").val('n');
				$("#menu_insurance_box_32a").val('n');
			}
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_menu_insurance_plan_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_menu_insurance_plan_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxdashboard/edit-insurance-provider",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								$("#menu_insurance_plan_select").removeOption(/./);
								$.ajax({
									url: "ajaxsearch/insurance3",
									dataType: "json",
									type: "POST",
									success: function(data1){
										if(data1.response =='true'){
											$("#menu_insurance_plan_select").addOption(data1.message);
											$("#menu_insurance_plan_select").val(data.id);
											$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
											$("#demographics_insurance_plan_dialog").dialog('close');
											$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
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
				$('#edit_menu_insurance_plan_form').clearForm();
				$("#demographics_insurance_plan_dialog").dialog('close');
				$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
			}
		},
		close: function(event, ui) {
			$('#edit_menu_insurance_plan_form').clearForm();
			$("#demographics_insurance_plan_dialog").dialog("option", "title", "");
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#add_insurance_plan").button().click(function(){
		$("#demographics_insurance_plan_dialog").dialog('open');
	});
	$('#menu_insurance_plan_select').change(function() {
		if ($(this).val() != ""){
			$("#menu_insurance_plan_name").val($("#menu_insurance_plan_select option:selected").text());
			$("#add_insurance_plan span").text("Edit Insurance Provider");
		} else {
			$("#add_insurance_plan span").text("Add Insurance Provider");
		}
	});
	$("#menu_insurance_insu_gender").addOption(gender, false);
	$("#menu_insurance_order").addOption({"":"","Primary":"Primary","Secondary":"Secondary","Unassigned":"Unassigned"}, false);
	$("#menu_insurance_relationship").addOption({"":"","Self":"Self","Spouse":"Spouse","Child":"Child","Other":"Other"}, false);
	$("#menu_insurance_plan_type").addOption({"":"","Group Health Plan":"Group Health Plan","Other":"Other","Medicare":"Medicare","Medicaid":"Medicaid","Tricare":"Tricare","ChampVA":"ChampVA","FECA":"FECA"}, false);
	$("#menu_insurance_plan_assignment").addOption({"":"","No":"No","Yes":"Yes"}, false);
	$("#menu_insurance_insu_dob").mask("99/99/9999");
	$("#menu_insurance_insu_dob").datepicker();
	$("#menu_insurance_plan_state").addOption(states, false);
	$("#menu_insurance_insu_state").addOption(states, false);
	$("#menu_insurance_relationship").change(function(){
		if($("#menu_insurance_relationship").val() == "Self") {
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/copy-address",
				dataType: "json",
				success: function(data){
					$("#menu_insurance_insu_lastname").val(data.lastname);
					$("#menu_insurance_insu_firstname").val(data.firstname);
					var dob = editDate1(data.DOB);
					$("#menu_insurance_insu_dob").val(dob);
					$("#menu_insurance_insu_gender").val(data.sex);
					$("#menu_insurance_insu_address").val(data.address);
					$("#menu_insurance_insu_city").val(data.city);
					$("#menu_insurance_insu_state").val(data.state);
					$("#menu_insurance_insu_zip").val(data.zip);
					if (data.phone_home != '') {
						$("#menu_insurance_insu_phone").val(data.phone_home);
					} else {
						$("#menu_insurance_insu_phone").val(data.phone_cell);
					}
				}
			});
		}
	});	
	$("#insurance_copy").button().click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/copy_address",
			dataType: "json",
			success: function(data){
				$("#menu_insurance_insu_address").val(data.address);
				$("#menu_insurance_insu_city").val(data.city);
				$("#menu_insurance_insu_state").val(data.state);
				$("#menu_insurance_insu_zip").val(data.zip);
				if (data.phone_home != '') {
					$("#menu_insurance_insu_phone").val(data.phone_home);
				} else {
					$("#menu_insurance_insu_phone").val(data.phone_cell);
				}
			}
		});
	});
	$("#menu_insurance_box_31").addOption({"n":"First Last, Title (Default)","y":"Last, First" }, false);
	$("#menu_insurance_box_32a").addOption({"n":"Company NPI (Default)","y":"Personal NPI" }, false);
	$("#register_menu_demographics").button().click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxdashboard/register-patient",
			success: function(data){
				$("#register_menu_demographics").hide();
				$("#menu_registration_code").html(data);
			}
		});
	});
	$("#pregnancy_lmp").datepicker().mask("99/99/9999");
	$("#pregnancy_us").datepicker().mask("99/99/9999");
	$('#edc_lmp').click(function(){
		var a = $("#pregnancy_lmp");
		var b = $("#pregnancy_cycle");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Last menstrural period");
		bValid = bValid && checkEmpty(a,"Number of days in cycle");
		if (bValid) {
			var codate = new Date();
			var daymsecs = 86400000;
			var c = $('#pregnancy_cycle').val();
			var string = $('#pregnancy_lmp').val();
			var result = string.split("/");
			var starto = new Date();
			starto.setFullYear(result[2]);
			starto.setMonth(result[0] - 1);
			starto.setDate(result[1]);
			starto.setTime(starto.getTime() + ((c * daymsecs) - daymsecs*14));
			codate.setTime(starto.getTime());
			var month = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			var a = "" + month[starto.getMonth()] + "/" + starto.getDate() + "/" + starto.getFullYear() + ";LMP " + string + " " + c;	
			var a1 = month[starto.getMonth()] + "/" + starto.getDate() + "/" + starto.getFullYear();
			$('#pregnancy_edc').val(a);
			$('#edc_text').html(a1);
		}
	});
	$('#edc_us').click(function(){
		var a = $("#pregnancy_us");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Ultrasound EDC");
		if (bValid) {
			var a = $('#pregnancy_us').val() + ";Ultrasound";
			$('#pregnancy_edc').val(a);
			$('#edc_text').html($('#pregnancy_us').val());
		}
	});
	function save_prenatal_dialog() {
		var edc = $("#pregnancy_edc");
		var bValid = true;
		bValid = bValid && checkEmpty(edc,"Consensus EDC");
		if (bValid) {
			var edc1 = $("#pregnancy_edc").val();
			$.ajax({
				type: "POST",
				url: "ajaxchart/edit-pregnancy",
				data: "pregnant=" + edc1,
				success: function(data){
					$.jGrowl(data);
					var origin = $("#prenatal_dialog_origin").val();
					var a = $("#pregnancy_edc").val();
					if (a != 'no') {
						var result1 = a.split(";");
						var string = result1[0];
						var result = string.split("/");
						var starto = new Date();
						starto.setFullYear(result[2]);
						starto.setMonth(result[0] - 1);
						starto.setDate(result[1]);
						var daymsecs = 86400000;
						var timenow = new Date();
						var elapsed = Math.round((timenow.getTime()-starto.getTime())/daymsecs);
						var b = "" + (Math.floor(elapsed/7)+2) + " weeks, " + Math.floor(elapsed%7) + " days";
						var duedate = new Date(); 
						duedate.setTime(starto.getTime() + daymsecs*266);
						var month = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
						var c = "" + month[duedate.getMonth()] + "/" + duedate.getDate() + "/" + duedate.getFullYear();
						var intro = "Pregnancy status: Pregnant.\nEstimated date of conception: " + string + "\nEstimated gestational age: " + b + "\nEstimated due date: " + c;
					} else {
						var intro = "Pregnancy status: Not pregnant.";
					}
					if (origin == "1") {
						var old = $("#hpi").val();
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
						$("#hpi").val(old1+intro);
					} else {
						if (a != 'no') {
							$("#prenatal_ega").val(b);
							$("#prenatal_duedate").val(c);
						}
					}
					$("#prenatal_dialog_form").clearForm();
					$("#prenatal_dialog").dialog('close');
				}
			});
		}
	} 
	$("#prenatal_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 500, 
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'OK': function() {
				save_prenatal_dialog();
			},
			'Not Pregnant': function() {
				$('#pregnancy_edc').val('no');
				save_prenatal_dialog();
			},
			Cancel: function() {
				$("#prenatal_dialog_form").clearForm();
				$("#prenatal_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
});
