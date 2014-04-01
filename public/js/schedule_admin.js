$(document).ready(function() {
	function colorlabel (cellvalue, options, rowObject) {
		if(cellvalue=="colorred"){
			return "Red";
		}
		if(cellvalue=="colororange"){
			return "Orange";
		}
		if(cellvalue=="coloryellow"){
			return "Yellow"; mi
		}
		if(cellvalue=="colorgreen"){
			return "Green";
		}
		if(cellvalue=="colorblue"){
			return "Blue";
		}
		if(cellvalue=="colorpurple"){
			return "Purple";
		}
		if(cellvalue=="colorbrown"){
			return "Brown"
		}
		if(cellvalue=="colorblack"){
			return "Black"
		}
	}
	function capsfn (cellvalue, options, rowObject)
	{
		if (cellvalue == 'sunday') {
			return 'Sunday';
		}
		if (cellvalue == 'monday') {
			return 'Monday';
		}
		if (cellvalue == 'tuesday') {
			return 'Tuesday';
		}
		if (cellvalue == 'wednesday') {
			return 'Wednesday';
		}
		if (cellvalue == 'thursday') {
			return 'Thursday';
		}
		if (cellvalue == 'friday') {
			return 'Friday';
		}
		if (cellvalue == 'saturday') {
			return 'Saturday';
		}
	}
	function providerlabel(cellvalue, options, rowObject) {
		var item = "";
		if (cellvalue == '0') {
			item = "All Providers";
		} else {
			$.ajax({
				url: "ajaxsearch/provider-select1",
				dataType: "json",
				type: "POST",
				async: false,
				success: function(data){
					$.each(data, function(key, value){
						if (cellvalue == key) {
							item = value;
						}
					});
				}
			});
		}
		return item;
	}
	$("#admin_schedule_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#admin_schedule_accordion").accordion({
				heightStyle: "content",
				beforeActivate: function (event, ui) {
					var id = ui.newPanel[0].id;
					$("#" + id + " .text").first().focus();
					var old_id = ui.oldPanel[0].id;
					var form_id = $("#" + old_id + " form").attr('id');
					var bValid = true;
					$("#" + form_id).find("[required]").each(function() {
						var input_id = $(this).attr('id');
						var id1 = $("#" + input_id); 
						var text = $("label[for='" + input_id + "']").html();
						bValid = bValid && checkEmpty(id1, text);
					});
					var bValid1 = false;
					$("#" + form_id).find(".text").each(function() {
						if (bValid1 == false) {
							var input_id = $(this).attr('id');
							var a = $("#" + input_id).val();
							var b = $("#" + input_id + "_old").val();
							if (a != b) {
								bValid1 = true;
							}
						}
					});
					if (bValid) {
						if (bValid1) {
							var str = $("#" + form_id).serialize();
							if(str){
								$.ajax({
									type: "POST",
									url: "ajaxsetup/" + form_id,
									data: str,
									success: function(data){
										$.jGrowl(data);
										return true;
									}
								});
							} else {
								$.jGrowl("Please complete the form");
								return false;
							}
						} else {
							return true;
						}
					} else {
						return false;
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxsetup/get-practice",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#schedule-setup1 :input[name='" + key + "']").val(value);
						$("#" + key + "_old").val(value);
					});
					if ($("#timezone").val() == '') {
						var tz = jstz.determine();
						$("#timezone").val(tz.name());
						$.jGrowl("Timezone not set. Automatically set based on your browser location");
					}
				}
			});
			
			jQuery("#visit_type_list").jqGrid({
				url:"ajaxsetup/visit-type-list",
				editurl:"ajaxsetup/edit-visit-type-list",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Visit Type','Provider','Duration','Color'],
				colModel:[
					{name:'calendar_id',index:'calendar_id',width:1,hidden:true},
					{name:'visit_type',index:'visit_type',width:300,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
					{name:'provider_id',index:'provider_id',width:200,editable:true,editrules:{edithidden:true, required:true},formatter:providerlabel,edittype:'select',
						editoptions:{value: function(){
							var list = "0:All Providers";
							$.ajax({
								url: "ajaxsearch/provider-select1",
								dataType: "json",
								type: "POST",
								async: false,
								success: function(data){
									$.each(data, function(key, value){
										list += ";" + key + ":" + value
									});
								}
							});
							return list;
						}},
						formoptions:{elmsuffix:"(*)"}},
					{name:'duration',index:'duration',width:1,hidden:true,editable:true,editrules:{edithidden:true, required:true},edittype:'select',editoptions:{value:"900:15 minutes;1200:20 minutes;1800:30 minutes;2400:40 minutes;2700:45 minutes;3600:60 minutes;4500:75 minutes;4800:80 minutes;5400:90 minutes;6000:100 minutes;6300:105 minutes;7200:120 minutes"},formoptions:{elmsuffix:"(*)"}},
					{name:'classname',index:'classname',width:300,editable:true,edittype:'select',formatter:colorlabel,editoptions:{value:"colorred:Red;colororange:Orange;coloryellow:Yellow;colorgreen:Green;colorblue:Blue;colorpurple:Purple;colorbrown:Brown"},formoptions:{elmsuffix:"(*)"}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#visit_type_list_pager'),
				sortname: 'visit_type',
				viewrecords: true,
				sortorder: "asc",
				caption:"Visit Types",
				emptyrecords:"No visits",
				height: "100%",
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#visit_type_list_pager',{edit:false,add:false,del:false});
			$("#provider_list1").removeOption(/./);
			$("#provider_grid").hide();
			$.ajax({
				url: "ajaxsearch/provider-select1",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#provider_list1").addOption({"":"Select a provider."}, false);
					$("#provider_list1").addOption(data, false);
				}
			});
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#weekends").addOption({"0":"No","1":"Yes"},false);
	$.ajax({
		url: "ajaxsearch/timezone",
		dataType: "json",
		type: "POST",
		success: function(data){
			$("#timezone").addOption({"":"Select a timezone."}, false);
			$("#timezone").addOption(data, false);
		}
	});
	$("#dashboard_admin_schedule").click(function(){
		$("#admin_schedule_dialog").dialog('open');
	});
	$('.schedule_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	$("#sun_all").click(function() {
		if ($("#sun_all").is(":checked")) {
			$("#sun_o").val("");
			$("#sun_c").val("");
		}
	});
	$("#mon_all").click(function() {
		if ($("#mon_all").is(":checked")) {
			$("#mon_o").val("");
			$("#mon_c").val("");
		}
	});
	$("#tue_all").click(function() {
		if ($("#tue_all").is(":checked")) {
			$("#tue_o").val("");
			$("#tue_c").val("");
		}
	});
	$("#wed_all").click(function() {
		if ($("#wed_all").is(":checked")) {
			$("#wed_o").val("");
			$("#wed_c").val("");
		}
	});
	$("#thu_all").click(function() {
		if ($("#thu_all").is(":checked")) {
			$("#thu_o").val("");
			$("#thu_c").val("");
		}
	});
	$("#fri_all").click(function() {
		if ($("#fri_all").is(":checked")) {
			$("#fri_o").val("");
			$("#fri_c").val("");
		}
	});
	$("#sat_all").click(function() {
		if ($("#sat_all").is(":checked")) {
			$("#sat_o").val("");
			$("#sat_c").val("");
		}
	});
	$("#add_visit_type").click(function(){
		jQuery("#visit_type_list").editGridRow("new",{closeAfterAdd:true,width:'400',bottominfo:'Fields marked in (*) are required.'});	
	});
	$("#edit_visit_type").click(function(){
		var item = jQuery("#visit_type_list").getGridParam('selrow');
		if(item){ 
			jQuery("#visit_type_list").editGridRow(item,{closeAfterEdit:true,width:'400',bottominfo:'Fields marked in (*) are required.'});
		} else {
			$.jGrowl("Please select visit type to edit!");
		}
	});
	$("#delete_visit_type").click(function(){
		var item = jQuery("#visit_type_list").getGridParam('selrow');
		if(item){ 
			jQuery("#visit_type_list").delGridRow(item);
			jQuery("#visit_type_list").delRowData(item);
		} else {
			$.jGrowl("Please select visit type to delete!");
		}
	});
	$('#provider_list1').change(function() {
		var provider_id = $('#provider_list1').val();
		if(provider_id){
			$.ajax({
				type: "POST",
				url: "ajaxsetup/set-provider",
				data: "id=" + provider_id,
				success: function(data){
					$("#provider_grid").show();
					jQuery("#exception_list").jqGrid('GridUnload');
					jQuery("#exception_list").jqGrid({
						url:"ajaxsetup/exception-list",
						editurl:"ajaxsetup/edit-exception-list",
						datatype: "json",
						mtype: "POST",
						colNames:['ID','Day','Start Time','End Time','Title','Reason'],
						colModel:[
							{name:'repeat_id',index:'repeat_id',width:1,hidden:true},
							{name:'repeat_day',index:'repeat_day',width:100,formatter:capsfn,editable:true,editrules:{required:true},edittype:'select',editoptions:{value:"sunday:Sunday;monday:Monday;tuesday:Tuesday;wednesday:Wednesday;thursday:Thursday;friday:Friday;saturday:Saturday"},formoptions:{elmsuffix:"(*)"}},
							{name:'repeat_start_time',index:'repeat_start_time',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
							{name:'repeat_end_time',index:'repeat_end_time',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
							{name:'title',index:'title',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
							{name:'reason',index:'reason',width:200,editable:true,edittype:'textarea',editoptions:{rows:'4',cols:'20'}}
						],
						rowNum:10,
						rowList:[10,20,30],
						pager: jQuery('#exception_list_pager'),
						sortname: 'repeat_day',
						viewrecords: true,
						sortorder: "asc",
						caption:"Repeating Schedule Events",
						emptyrecords:"No repeating events",
						height: "100%",
						jsonReader: { repeatitems : false, id: "0" }
					}).navGrid('#exception_list_pager',{edit:false,add:false,del:false});
				}
			});
		}
	});
	$("#add_exception1").click(function(){
		jQuery("#exception_list").editGridRow("new",{
			closeAfterAdd:true,
			width:'650',
			bottominfo:'Fields marked in (*) are required.',
			onInitializeForm : function(formid){
				$("#repeat_start_time",formid).timepicker({
					'scrollDefaultNow': true,
					'timeFormat': 'h:i A',
					'step': 15
				});
				$("#repeat_end_time",formid).timepicker({
					'scrollDefaultNow': true,
					'timeFormat': 'h:i A',
					'step': 15
				});
			}
		});	
	});
	$("#edit_exception1").click(function(){
		var item = jQuery("#exception_list").getGridParam('selrow');
		if(item){ 
			jQuery("#exception_list").editGridRow(item,{
				closeAfterEdit:true,
				width:'650',
				bottominfo:'Fields marked in (*) are required.',
				onInitializeForm : function(formid){
					$("#repeat_start_time",formid).timepicker({
						'scrollDefaultNow': true,
						'timeFormat': 'h:i A',
						'step': 15
					});
					$("#repeat_end_time",formid).timepicker({
						'scrollDefaultNow': true,
						'timeFormat': 'h:i A',
						'step': 15
					});
				}
			});
		} else {
			$.jGrowl("Please select exception to edit!");
		}
	});
	$("#delete_exception1").click(function(){
		var item = jQuery("#exception_list").getGridParam('selrow');
		if(item){ 
			jQuery("#exception_list").delGridRow(item);
			jQuery("#exception_list").delRowData(item);
		} else {
			$.jGrowl("Please select exception to delete!");
		}
	});
});
