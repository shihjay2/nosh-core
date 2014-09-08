$(document).ready(function() {
	loadbuttons();
	$("#oh_results_open").click(function() {
		$("#encounter_copy_result").show();
		$("#t_message_copy_result").hide();
		$("#tests_dialog").dialog('open');
	});
	jQuery("#oh_results_encounters").jqGrid('GridUnload');
	jQuery("#oh_results_encounters").jqGrid({
		url: "ajaxencounter/results-encounters",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Chief Complaint','Status'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'encounter_cc',index:'encounter_cc',width:505},
			{name:'encounter_signed',index:'encounter_signed',width:100,formatter:signedlabel}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#oh_results_encounters_pager'),
		sortname: 'encounter_DOS',
		viewrecords: true,
		sortorder: "desc",
		caption:"Past Encounters - Expand Row for Test Result History",
		height: "100%",
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id, pager_id;
			subgrid_table_id = subgrid_id+"_t";
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({
				url: "ajaxencounter/results-encounters-history/"+row_id,
				datatype: "json",
				mtype: "POST",
				colNames:['Result'],
				colModel:[
					{name:"oh_results",index:"oh_results",width:600}
				], 
				rowNum:10,
				pager: pager_id,
				sortname: 'oh_result', 
				sortorder: "asc", 
				height: '100%',
				jsonReader: { repeatitems : false, id: "0" }
			});
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{
				search:false,
				edit:false,
				add:false,
				del:false
			});
		},
		jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#oh_results_encounters_pager',{search:false,edit:false,add:false,del:false});
	setInterval(results_autosave, 10000);
});
