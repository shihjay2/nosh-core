$(document).ready(function() {
	function signedlabel (cellvalue, options, rowObject){
		if (cellvalue == 'No') {
			return 'Draft';
		}
		if (cellvalue == 'Yes') {
			return 'Signed';
		}
	}
	function typelabel (cellvalue, options, rowObject){
		if (cellvalue == 'standardmedical') {
			return 'Standard Medical Visit';
		}
		if (cellvalue == 'clinicalsupport') {
			return 'Clinical Support Visit';
		}
		if (cellvalue == 'standardpsych') {
			return 'Annual Psychiatric Evaluation';
		}
		if (cellvalue == 'standardpsych1') {
			return 'Psychiatric Encounter';
		}
		if (cellvalue == 'standardmtm') {
			return 'MTM Encounter';
		}
	}
	$("#encounter_view_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#encounter_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function (event, ui) {
			jQuery("#encounters").jqGrid({
				url:"ajaxcommon/encounters",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date','Chief Complaint','Type','Status'],
				colModel:[
					{name:'eid',index:'eid',width:1,hidden:true},
					{name:'encounter_DOS',index:'encounter_DOS',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'encounter_cc',index:'encounter_cc',width:350},
					{name:'encounter_template',index:'encounter_template',width:150,formatter:typelabel},
					{name:'encounter_signed',index:'encounter_signed',width:100,formatter:signedlabel}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#encounters_pager'),
				sortname: 'encounter_DOS',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Encounters",
			 	height: "100%",
			 	onSelectRow: function(id) {
			 		if (noshdata.group_id != '100') {
						var status = jQuery("#encounters").getCell(id,'encounter_signed');
						var acl = false;
						if (noshdata.group_id == '2' || noshdata.group_id == '3') {
							acl = true;
						}
						if (status == "Draft") {
							if (acl) {
								$.ajax({
									type: "POST",
									url: "ajaxchart/encounter-id-set",
									data: "eid=" + id,
									success: function(data) {
										noshdata.encounter_active = 'y';
										noshdata.eid = id
										openencounter();
										$("#nosh_chart_div").hide();
										$("#nosh_encounter_div").show();
										$("#encounter_list_dialog").dialog('close');
									}
								});
							} else {
								$.jGrowl('You do not have permissions to view draft encounters.');
							}
						}
						if (status == "Signed") {
							if (acl) {
								$("#encounter_view").load('ajaxchart/modal-view/' + id);
							} else {
								$("#encounter_view").load('ajaxchart/modal-view2/' + id);
							}
							$("#encounter_view_dialog").dialog('open');
						}
					} else {
						$.ajax({
							type: "POST",
							url: "ajaxcommon/patient-instructions/" + id,
							dataType: "json",
							success: function(data){
								$("#embedURL").html(data.html);
								$("#document_filepath").val(data.filepath);
								$("#documents_view_dialog").dialog('open');
							}
						});
					}
			 	},
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#encounters_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#encounter_list").click(function() {
		$("#encounter_list_dialog").dialog('open');
	});
	$("#dashboard_encounters").click(function() {
		$("#encounter_list_dialog").dialog('open');
	});
});
