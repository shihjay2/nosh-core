$(document).ready(function() {
	$("#allergies_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#allergies").jqGrid('GridUnload');
			jQuery("#allergies").jqGrid({
				url: "ajaxcommon/allergies",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:320}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#allergies_pager'),
				sortname: 'allergies_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Allergies",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#allergies_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#allergies_inactive").jqGrid('GridUnload');
			jQuery("#allergies_inactive").jqGrid({
				url: "ajaxcommon/allergies-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Medication','Reason'],
				colModel:[
					{name:'allergies_id',index:'allergies_id',width:1,hidden:true},
					{name:'allergies_date_active',index:'allergies_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'allergies_med',index:'allergies_med',width:310},
					{name:'allergies_reaction',index:'allergies_reaction',width:320}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#allergies_inactive_pager'),
				sortname: 'allergies_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption: "Inactive Allergies",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#allergies_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_allergy_form').clearForm();
			menu_update('allergies');
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#edit_allergy_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#allergies_med").autocomplete({
				source: function (req, add){
					if (req.term in allergies_cache){
						add(allergies_cache[req.term]);
						return;
					}
					$.ajax({
						url: "ajaxsearch/rx-name",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								allergies_cache[req.term] = data.message;
								add(data.message);
							}
						}
					});
				},
				minLength: 3
			});
			$("#allergies_reaction").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "ajaxsearch/reaction",
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
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_allergy_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_allergy_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-allergy",
							data: str,
							dataType: "json",
							success: function(data){
								$.jGrowl(data.message);
								reload_grid('allergies');
								reload_grid('nosh_allergies');
								$('#edit_allergy_form').clearForm();
								$('#edit_allergy_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_allergy_form').clearForm();
				$('#edit_allergy_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#rcopia_update_allergies").button({icons: {primary: "ui-icon-link"}}).click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxchart/rcopia-update-allergy",
			success: function(data){
				$.jGrowl(data);
				reload_grid('allergies');
				reload_grid('allergies_inactive');
			}
		});
	});
	$(".allergies_list").click(function() {
		$("#allergies_list_dialog").dialog('open');
	});
	$("#allergies_date_active").mask("99/99/9999");
	$("#allergies_date_active").datepicker();
	$("#add_allergy").click(function(){
		$('#edit_allergy_form').clearForm();
		var currentDate = getCurrentDate();
		$('#allergies_date_active').val(currentDate);
		$('#edit_allergy_dialog').dialog('option', 'title', "Add Allergy");
		$('#edit_allergy_dialog').dialog('open');
		$("#allergies_med").focus();
	});
	$("#edit_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			jQuery("#allergies").GridToForm(item,"#edit_allergy_form");
			var date = $('#allergies_date_active').val();
			var edit_date = editDate(date);
			$('#allergies_date_active').val(edit_date);
			$('#edit_allergy_dialog').dialog('option', 'title', "Edit Allergy");
			$('#edit_allergy_dialog').dialog('open');
			$("#allergies_med").focus();
		} else {
			$.jGrowl("Please select allergy to edit!")
		}
	});
	$("#inactivate_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-allergy",
				data: "allergies_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid('allergies');
					reload_grid('allergies_inactive');
				}
			});
		} else {
			$.jGrowl("Please select allergy to inactivate!")
		}
	});
	$("#delete_allergy").click(function(){
		var item = jQuery("#allergies").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this allergy?')){
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-allergy",
					data: "allergies_id=" + item,
					success: function(data){
						$.jGrowl(data);
						reload_grid('allergies');
					}
				});
			}
		} else {
			$.jGrowl("Please select allergy to delete!")
		}
	});
	$("#reactivate_allergy").click(function(){
		var item = jQuery("#allergies_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "ajaxchart/reactivate-allergy",
				data: "allergies_id=" + item,
				success: function(data){
					$.jGrowl(data);
					reload_grid('allergies');
					reload_grid('allergies_inactive');
				}
			});
		} else {
			$.jGrowl("Please select allergy to inactivate!")
		}
	});
	$('#save_oh_allergies').click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/oh-save1/allergies",
			success: function(data){
				$.jGrowl(data);
				$("#save_oh_allergies").hide();
				$("#allergies_list_dialog").dialog('close');
				check_oh_status();
			}
		});
	});
});
