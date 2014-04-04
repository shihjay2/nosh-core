$(document).ready(function() {
	loadbuttons();
	//setInterval(hpi_autosave('situation'), 10000);
	if(noshdata.pending_orders_id != '') {
		pending_order_load(noshdata.pending_orders_id);
	}
	$("#encounter_tabs").tabs({
		beforeLoad: function(event, ui) {
			if ( ui.tab.data("loaded") ) {
				event.preventDefault();
				return;
			}
			ui.jqXHR.success(function() {
				ui.tab.data("loaded", true );
			});
			ui.jqXHR.setRequestHeader("X-CSRF-Token", $("meta[name='token']").attr('content'));
		},
		activate: function(event, ui) {
			var id = $(ui.newTab).attr('id');
			var old_id = $(ui.oldTab).attr('id');
			var isValid = true;
			if (old_id == "encounter_tabs_situation") {
				hpi_autosave('situation');
			}
			if (old_id == "encounter_tabs_background") {
				oh_autosave();
			}
			if (id == "encounter_tabs_background") {
				check_oh_status();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/get-oh",
					dataType: "json",
					success: function(data){
						if (data.message != 'n') {
							$.each(data.response, function(key, value){
								$("#oh_form :input[name='" + key + "']").val(value);
								$("#" + key + "_old").val(value);
							});
						}
					}
				});
				$.ajax({
					type: "POST",
					url: "ajaxencounter/pf-template-select-list/PMH",
					dataType: "json",
					success: function(data){
						$("#oh_pmh_pf_template").removeOption(/./);
						$('#oh_pmh_pf_template').addOption(data.options, false);
						$('#oh_pmh_pf_template').sortOptions();
						$('#oh_pmh_pf_template').val("");
					}
				});
				$.ajax({
					type: "POST",
					url: "ajaxencounter/pf-template-select-list/PSH",
					dataType: "json",
					success: function(data){
						$("#oh_psh_pf_template").removeOption(/./);
						$('#oh_psh_pf_template').addOption(data.options, false);
						$('#oh_psh_pf_template').sortOptions();
						$('#oh_psh_pf_template').val("");
					}
				});
				$.ajax({
					type: "POST",
					url: "ajaxencounter/pf-template-select-list/FH",
					dataType: "json",
					success: function(data){
						$("#oh_fh_pf_template").removeOption(/./);
						$('#oh_fh_pf_template').addOption(data.options, false);
						$('#oh_fh_pf_template').sortOptions();
						$('#oh_fh_pf_template').val("");
					}
				});
				$.ajax({
					type: "POST",
					url: "ajaxencounter/pf-template-select-list/SH",
					dataType: "json",
					success: function(data){
						$("#oh_sh_pf_template").removeOption(/./);
						$('#oh_sh_pf_template').addOption(data.options, false);
						$('#oh_sh_pf_template').sortOptions();
						$('#oh_sh_pf_template').val("");
					}
				});
			}
			if (id == "encounter_tabs_labs") {
				check_labs1();
			}
			if (old_id == "encounter_tabs_proc") {
				proc_autosave();
			}
			if (old_id == "encounter_tabs_assessment") {
				assessment_autosave();
			}
			if (old_id == "encounter_tabs_orders") {
				orders_autosave();
			}
			if (id == "encounter_tabs_orders") {
				checkorders();
			}
			return isValid;
		}
	});
	$("#situation").focus();
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-hpi",
		dataType: "json",
		success: function(data){
			if (data.response == true) {
				$("#situation").val(data.situation);
				$("#situation_old").val(data.situation);
			} else {
				var age1 = noshdata.age;
				var age = age1.replace("Years Old", "year-old ");
				age = age.replace("Month Old", "month-old ");
				age = age.replace("Months Old", "month-old ");
				age = age.replace("Week Old", "week-old ");
				age = age.replace("Weeks Old", "weeks-old ");
				age = age.replace("Year", 'year');
				var gender = noshdata.gender;
				var intro = age + gender + " here for the following concerns:";
				$("#situation").val(intro);
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/pf-template-select-list/HPI",
		dataType: "json",
		success: function(data){
			$('#hpi_pf_template').addOption(data.options, false);
			$('#hpi_pf_template').sortOptions();
			$('#hpi_pf_template').val("");
		}
	});
	$('#hpi_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajaxencounters/get-pf-template/" + a,
			success: function(data){
				var old = $("#situation").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#situation").val(b);
			}
		});
	});
	$("#situation_orders_pending").click(function(){
		$("#process_pending_order").show();
		$("#pending_create_encounter").hide();
		$("#alerts_pending_dialog").dialog('open');
	});
	$('#situation_reset').click(function(){
		$("#situation").val('');
	});
});
