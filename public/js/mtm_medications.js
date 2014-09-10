$(document).ready(function() {
	loadbuttons();
	jQuery("#mtm_medications").jqGrid('GridUnload');
	jQuery("#mtm_medications").jqGrid({
		url:"ajaxcommon/medications",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Due Date','Medication','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason','NDC'],
		colModel:[
			{name:'rxl_id',index:'rxl_id',width:1,hidden:true},
			{name:'rxl_date_active',index:'rxl_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'rxl_due_date',index:'rxl_due_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'rxl_medication',index:'rxl_medication',width:255},
			{name:'rxl_dosage',index:'rxl_dosage',width:50},
			{name:'rxl_dosage_unit',index:'rxl_dosage_unit',width:50},
			{name:'rxl_sig',index:'rxl_sig',width:50},
			{name:'rxl_route',index:'rxl_route',width:1,hidden:true},
			{name:'rxl_frequency',index:'rxl_frequency',width:105},
			{name:'rxl_instructions',index:'rxl_instructions',width:1,hidden:true},
			{name:'rxl_reason',index:'rxl_reason',width:1,hidden:true},
			{name:'rxl_ndcid',index:'rxl_ndcid',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#mtm_medications_pager'),
		sortname: 'rxl_date_active',
		viewrecords: true,
		sortorder: "desc",
		caption:"Medications - Click on the Date Active column to get past prescriptions for the medication.",
		height: "100%",
		onCellSelect: function(id,iCol) {
			if (iCol == 1) {
				var med = jQuery("#mtm_medications").getCell(id,'rxl_medication');
				$.ajax({
					type: "POST",
					url: "ajaxchart/past-medication",
					data: "rxl_medication=" + med,
					dataType: "json",
					success: function(data){
						$.jGrowl(data.item, {sticky:true, header:data.header});
					}
				});
			}
		},
		jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#mtm_medications_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#mtm_encounters").jqGrid('GridUnload');
	jQuery("#mtm_encounters").jqGrid({
		url: "ajaxencounter/mtm-encounters",
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
		pager: jQuery('#mtm_encounters_pager'),
		sortname: 'encounter_DOS',
		viewrecords: true,
		sortorder: "desc",
		caption:"Past MTM Encounters - Expand Row for Medication History",
		height: "100%",
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id, pager_id;
			subgrid_table_id = subgrid_id+"_t";
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({
				url: "ajaxencounter/mtm-medication-history/"+row_id,
				datatype: "json",
				mtype: "POST",
				colNames:['Medication'],
				colModel:[
					{name:"mtm_medication",index:"mtm_medication",width:600}
				], 
				rowNum:10,
				pager: pager_id,
				sortname: 'mtm_medication', 
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
	}).navGrid('#mtm_encounters_pager',{search:false,edit:false,add:false,del:false});
	$("#add_mtm_rx").click(function(){
		$('#edit_rx_form').clearForm();
		var currentDate = getCurrentDate();
		$('#rxl_date_active').val(currentDate);
		$('#edit_medications_dialog').dialog('option', 'title', "Add Medication");
		$('#edit_medications_dialog').dialog('open');
		$("#rxl_search").focus();
	});
	$("#edit_mtm_rx").click(function(){
		var item = jQuery("#mtm_medications").getGridParam('selrow');
		if(item){
			jQuery("#mtm_medications").GridToForm(item,"#edit_rx_form");
			var date = $('#rxl_date_active').val();
			var edit_date = editDate(date);
			$('#rxl_date_active').val(edit_date);
			$('#edit_medications_dialog').dialog('option', 'title', "Edit Medication");
			$('#edit_medications_dialog').dialog('open');
			$("#rxl_medication").focus();
		} else {
			$.jGrowl("Please select medication to edit!")
		}
	});
	$("#inactivate_mtm_rx").click(function(){
		var item = jQuery("#mtm_medications").getGridParam('selrow');
		if(item){
			var id = $("#mtm_medications").getCell(item,'rxl_id');
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-medication",
				data: "rxl_id=" + id,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					reload_grid("mtm_medications");
				}
			});
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#delete_mtm_rx").click(function(){
		var item = jQuery("#mtm_medications").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this medication?  This is not recommended unless entering the medication was a mistake!')){ 
				var id = $("#mtm_medications").getCell(item,'rxl_id');
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-medication",
					data: "rxl_id=" + id,
					success: function(data){
						$.jGrowl(data);
						reload_grid("mtm_medications");
					}
				});
			}
		} else {
			$.jGrowl("Please select medication to inactivate!")
		}
	});
	$("#mtm_medications_reviewed").click(function(){
		medications_autosave();
	});
	$("#mtm_print_medication_list").click(function() {
		window.open("print_medication_list");
	});
});
