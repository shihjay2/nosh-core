$(document).ready(function() {
	loadbuttons();
	//setInterval(hpi_autosave('hpi'), 10000);
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
		create: function(event, ui) {
			check_ros_status();
			ros_get_data();
		},
		activate: function(event, ui) {
			var id = $(ui.newTab).attr('id');
			var old_id = $(ui.oldTab).attr('id');
			var isValid = true;
			if (old_id == "encounter_tabs_hpi") {
				hpi_autosave('hpi');
			}
			if (id == "encounter_tabs_ros") {
				check_ros_status();
				ros_get_data();
			}
			if (old_id == "encounter_tabs_oh") {
				oh_autosave();
			}
			if (id == "encounter_tabs_oh") {
				check_oh_status();
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
			if (old_id == "encounter_tabs_vitals") {
				vitals_autosave();
			}
			if (id == "encounter_tabs_pe") {
				check_pe_status();
				pe_dialog_open1();
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
	$("#hpi").focus();
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-hpi",
		dataType: "json",
		success: function(data){
			if (data.response == true) {
				$("#hpi").val(data.hpi);
				$("#hpi_old").val(data.hpi);
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
				$("#hpi").val(intro);
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-oh",
		dataType: "json",
		success: function(data){
			if (data.message != 'n') {
				$.each(data.response, function(key, value){
					if (key == 'oh_results') {
						$("#oh_results_form :input[name='" + key + "']").val(value);
						$("#"+key+"_old").val(value);
					} else {
						$("#oh_form :input[name='" + key + "']").val(value);
						$("#" + key + "_old").val(value);
					}
				});
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
			url: "ajaxencounter/get-pf-template/" + a,
			success: function(data){
				var old = $("#hpi").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#hpi").val(b);
			}
		});
	});
	$('#hpi_reset').click(function(){
		$("#hpi").val('');
	});
	$('#hpi_wcc').click(function(){
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
		var age1 = noshdata.age;
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = noshdata.gender;
		var intro = age + gender + " here for a well child check.";
		$("#hpi").val(old1+intro);
	});
	$('#hpi_cpe').click(function(){
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
		var age1 = noshdata.age;
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = noshdata.gender;
		var intro = age + gender + " here for a complete physical examination.";
		$("#hpi").val(old1+intro);
	});
	$('#hpi_preg').click(function(){
		$('#pregnancy_form').clearForm();
		$('#edc_text').html('');
		$('#prenatal_dialog_origin').val('1');
		$.ajax({
			type: "POST",
			url: "ajaxchart/get-prenatal",
			success: function(data){
				if (data != 'no') {
					var result1 = data.split(";");
					if (result1[1] == "Ultrasound"){
						$("#pregnancy_us").val(result1[0]);
					} else {
						var result2 = result1[1].split(" ");
						$("#pregnancy_lmp").val(result2[1]);
						$("#pregnancy_cycle").val(result2[2]);
					}
					$("#pregnancy_edc").val(data.trim());
					$("#edc_text").html(result1[0]);
				} else {
					$("#pregnancy_edc").val(data.trim());
					$("#edc_text").html('Not Pregnant');
				}
			}
		});
		$("#prenatal_dialog").dialog('open');
	});
	$('#hpi_mtm').click(function(){
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
		var age1 = noshdata.age;
		var age = age1.replace("Years Old", "year-old ");
		age = age.replace("Month Old", "month-old ");
		age = age.replace("Months Old", "month-old ");
		age = age.replace("Week Old", "week-old ");
		age = age.replace("Weeks Old", "weeks-old ");
		age = age.replace("Year", 'year');
		var gender = noshdata.gender;
		var intro = age + gender + " here for a Medication Therapy Management encounter.";
		$("#hpi").val(old1+intro);
	});
	swipe();
	$('#dialog_load').dialog('close');
});
