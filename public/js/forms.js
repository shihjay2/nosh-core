$(document).ready(function() {
	$("#form_button_div").hide();
	$("#forms_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			jQuery("#forms").jqGrid({
				url:"ajaxcommon/forms-grid",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Form','Form ID','Date Completed'],
				colModel:[
					{name:'template_id',index:'template_id',width:1,hidden:true},
					{name:'template_name',index:'template_name',width:400},
					{name:'forms_id',index:'forms_id',width:1,hidden:true},
					{name:'forms_date',index:'forms_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#forms_pager'),
				sortname: 'template_name',
				viewrecords: true,
				sortorder: "asc",
				caption:"Click on a form:",
				height: "100%",
				onSelectRow: function(id){
					var template_id = $("#forms").getCell(id,'template_id');
					$.ajax({
						type: "POST",
						url: "ajaxcommon/get-form/" + template_id,
						dataType: 'json',
						success: function(data){
							$("#form_array").val(data.array);
							$('#form_content').html('');
							var json_object = JSON.parse(data.array);
							$('#form_content').dform(json_object);
							$('#form_scoring').val(data.scoring);
							$("#form_template_id").val(template_id);
							$(".patient_form_div").css("padding","5px");
							$('.patient_form_buttonset').buttonset();
							$('input.form_other[type="checkbox"]').button();
							$('.patient_form_text input[type="text"]').css("width","280px");
							$('.patient_form_div select').addClass("text ui-widget-content ui-corner-all");
							$(".form_select").chosen();
							var row = jQuery("#forms").getGridParam('selrow');
							var forms_id = jQuery('#forms').jqGrid('getCell', row, 'forms_id');
							if (forms_id !== "") {
								$.ajax({
									type: "POST",
									url: "ajaxcommon/get-form-data/" + forms_id,
									success: function(data){
										var json_object = JSON.parse(data);
										$("#form_content").populate(json_object);
										$('#form_dialog').dialog('option', 'title', "Fill out the " + json_object.forms_title + " Form:");
										$(".patient_form_buttonset input").button('refresh');
										$("#form_dialog").dialog('open');
									}
								});
							} else {
								$('#form_dialog').dialog('option', 'title', "Fill out the " + json_object.html[2].value + " Form:");
								$("#form_dialog").dialog('open');
							}
						}
					});
				},
				jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#forms_pager',{search:false,edit:false,add:false,del:false});
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#dashboard_forms").click(function(){
		$('#forms_dialog').dialog('option', {
			height: $("#maincontent").height(),
			width: $("#maincontent").width(),
			position: { my: 'left top', at: 'left top', of: '#maincontent' }
		});
		$("#forms_dialog").dialog('open');
	});
	$("#form_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		buttons: {
			'Submit': function() {
				var id = $("#form_template_id").val();
				var content = JSON.stringify($("#form_content").serializeJSON());
				var title = $("#form_forms_title").val();
				var destination = $("#form_forms_destination").val();
				var text = "Form title: " + $("#form_forms_title").val() + "\n";
				var d = new Date();
				var date = d.toISOString();
				text += "Form completed by patient on " + date + "\n";
				text += "********************************************\n";
				var score = 0;
				$(".patient_form_div").each(function() {
					var a = $(this).attr('class');
					var b = $(this).attr('id');
					if (a == "ui-dform-div patient_form_div patient_form_buttonset ui-buttonset") {
						$("#" + b + " input:checked").each(function() {
							text += $(this).val() + "\n";
							var c = $(this).attr('id');
							var d = c.split('_');
							score += parseInt(d.slice(-1)[0]);
						});
					}
					if (a == "ui-dform-div patient_form_div patient_form_text") {
						text += $("#" + b + "_label").html() + ": " + $("#" + b + "_text").val() + "\n";
					}
					if (a == "ui-dform-div patient_form_div") {
						text += $("#" + b + "_select").val() + "\n";
					}
				});
				if ($("#form_scoring").val() !== '') {
					text += "Score: " + score + "\n";
					text += "Scoring Description: " + $("#form_scoring").val();
				}
				var array = $("#form_array").val();
				$.ajax({
					type: "POST",
					url: "ajaxcommon/save-form-data",
					data: "template_id=" + id + "&forms_content=" + content + "&forms_title=" + title + "&forms_destination=" + destination + "&forms_content_text=" + text + "&array=" + array,
					success: function(data){
						$("#form_template_id").val();
						$("#form_button_div").hide();
						$('#form_content').clearForm();
						$('#form_content').html('');
						$("#form_array").val();
						$.jGrowl(data);
						jQuery("#forms").trigger("reloadGrid");
						$("#form_dialog").dialog('close');
					}
				});
			},
			'Clear' : function() {
				$('#form_content').clearForm();
				$(".patient_form_buttonset input").button('refresh');
			},
			Cancel: function() {
				$("#form_template_id").val();
				$("#form_button_div").hide();
				$('#form_content').clearForm();
				$('#form_content').html('');
				$("#form_array").val();
				$("#form_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$('#form_content').clearForm();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
});
