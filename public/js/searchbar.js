$(document).ready(function() {
	$("#searchpt").focus();
	$("#searchpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/search",
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
		select: function(event, ui){
			$("#hidden_pid").val(ui.item.id);
			var oldpt = noshdata.pid;
			if(!oldpt){
				if (noshdata.group_id != '1') {
					$.ajax({
						type: "POST",
						url: "ajaxsearch/openchart",
						dataType: "json",
						data: "pid=" + ui.item.id,
						success: function(data){
							closeencounter();
							window.location = data.url;
						}
					});
				} else {
					window.open("print_individual_chart/" + ui.item.id);
				}
			} else {
				if(ui.item.id != oldpt){
					$("#search_dialog").dialog('open');
				} else {
					$.jGrowl("Patient chart already open!");
				}
			}
		}
	});
	var gender = {"m":"Male","f":"Female"};
	$("#gender").addOption(gender, false);
	$("#DOB").mask("99/99/9999").datepicker();
	$("#search_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		resizable: true,
		height:200,
		width:350,
		modal: true,
		buttons: {
			'OK': function() {
				$("#search_dialog").dialog('close');
				var pid = $("#hidden_pid").val();
				var eid = $("#hidden_eid").val();
				if(pid){
					if(eid){
						$.ajax({
							type: "POST",
							url: "ajaxsearch/openchart",
							data: "pid=" + pid,
							dataType: "json",
							success: function(data){
								var eid = $("#hidden_eid").val();
								$.ajax({
									type: "POST",
									url: "ajaxsearch/eidset",
									data: "eid=" + eid,
									dataType: "json",
									success: function(data) {
										$("#hidden_eid").val('');
										window.location = data.url;
									}
								});
							}
						});
					} else {
						$.ajax({
							type: "POST",
							url: "ajaxsearch/openchart",
							data: "pid=" + pid,
							dataType: "json",
							success: function(data){
								$("#hidden_pid").val('');
								window.location = data.url;
							}
						});
					}
				} else {
					$.jGrowl("Please enter patient to open chart!");
				}
			},
			Cancel: function() {
				$("#search_dialog").dialog('close');
			}
		}
	});
	$("#openNewPatient").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		$("#new_patient").dialog('open');
		$("#lastname").focus();
	});
	$("#new_patient").dialog({
		bgiframe: true,
		autoOpen: false,
		resizable: true,
		height:400,
		width:550,
		modal: true,
		buttons: {
			'Add Only': function() {
				var str = $("#new_patient_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxsearch/newpatient",
						data: str,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$("#new_patient").dialog('close');
							$("#new_patient_form").clearForm();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			'Add and Open Chart': function() {
				var str = $("#new_patient_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxsearch/newpatient",
						data: str,
						dataType: "json",
						success: function(data){
							$.jGrowl(data.message);
							$("#new_patient").dialog('close');
							$("#new_patient_form").clearForm();
							$.ajax({
								type: "POST",
								url: "ajaxsearch/openchart",
								data: "pid=" + data.pid,
								dataType: "json",
								success: function(data){
									$("#hidden_pid").val('');
									window.location = data.url;
								}
							});
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#new_patient").dialog('close');
			}
		}
	});
	$("#encounter_panel").click(function() {
		noshdata.encounter_active = 'y';
		openencounter();
	});
	$("#chart_panel").click(function() {
		noshdata.encounter_active = 'n';
		$("#nosh_chart_div").show();
		$("#nosh_encounter_div").hide();
	});
});
