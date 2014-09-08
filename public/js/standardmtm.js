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
		activate: function(event, ui) {
			var id = $(ui.newTab).attr('id');
			var old_id = $(ui.oldTab).attr('id');
			var isValid = true;
			if (old_id == "encounter_tabs_hpi") {
				hpi_autosave('hpi');
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
			if (old_id == "encounter_tabs_assessment") {
				assessment_autosave();
			}
			if (old_id == "encounter_tabs_orders") {
				orders_autosave();
			}
			if (id == "encounter_tabs_orders") {
				checkorders();
			}
			if (id == "encounter_tabs_medication") {
				reload_grid('mtm_encounters');
				reload_grid('mtm_medications');
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
			$('#dialog_load').dialog('close');
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
	hpi_template_renew();
	$('#hpi_template').change(function(){
		var a = $(this).val();
		$('#hpi_template_form').html('');
		if (a != '') {
			var text = $('#hpi_template option:selected').text();
			var old = $("#hpi").val();
			if (text != 'Generic') {
				if (old != '') {
					var b = old + '\n\n' + text;
				} else {
					var b = text;
				}
				$("#hpi").val(b);
			}
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-hpi-template/" + a,
				dataType: "json",
				success: function(data){
					var old_text = "";
					var stringConstructor = "test".constructor;
					var objectConstructor = {}.constructor;
					if (data.constructor === stringConstructor) {
						var json_object = JSON.parse(data);
						$('#hpi_template_form').dform(json_object);
						$(".hpi_form_div").css("padding","5px");
						$('.hpi_template_div select').addOption({'':'Select option'},true);
					} else {
						$('#hpi_template_form').dform(data);
					}
					$('#hpi_template_form').find('input').first().focus();
					$('#hpi_template_form').find('.hpi_buttonset').buttonset();
					$('#hpi_template_form').find('.hpi_form_buttonset').buttonset();
					$('#hpi_template_form').find('.hpi_detail_text').hide();
					$('#hpi_template_form').find('input[type="checkbox"]').change(function() {
						var parent_id = $(this).attr("id");
						var old = $("#hpi").val();
						var a = $(this).val();
						if ($(this).is(':checked')) {
							if (old != '') {
								var b = old + '\n' + a;
							} else {
								var b = a;
							}
							$("#hpi").val(b); 
						} else {
							var a1 = '\n' + a;
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#hpi").val(c); 
						}
					});
					$('#hpi_template_form').find('input[type="radio"]').change(function() {
						var parent_id = $(this).attr("id");
						var old = $("#hpi").val();
						var a = $(this).val();
						if ($(this).is(':checked')) {
							if (old != '') {
								$(this).siblings('input:radio').each(function() {
									var d = $(this).val();
									var d1 = '  ' + d;
									old = old.replace(d1,'');
									old = old.replace(d, '');
								});
								if (old != '') {
									var b = old + '\n' + a;
								} else {
									var b = a;
								}
							} else {
								var b = a;
							}
							$("#hpi").val(b); 
						} else {
							var a1 = '\n' + a;
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#hpi").val(c); 
						}
					});
					$('#hpi_template_form').find('select').change(function() {
						var parent_id = $(this).attr("id");
						var old = $("#hpi").val();
						var a = $(this).val();
						if (old != '') {
							var b = old + '\n' + a;
						} else {
							var b = a;
						}
						$("#hpi").val(b); 
					});
					$('#hpi_template_form').find('input[type="text"]').focusin(function() {
						old_text = $(this).val();
					});
					$('#hpi_template_form').find('input[type="text"]').focusout(function() {
						var a = $(this).val();
						if (a != '') {
							var parent_id = $(this).attr("id");
							var x = parent_id.length - 1;
							var parent_div = parent_id.slice(0,x);
							if ($("#" + parent_div + "_div").length) {
								var start1 = $("#" + parent_div + "_div").find('span:first').text();
							} else {
								var parent_div_parts = parent_id.split("_");
								var parent_div = parent_div_parts[0] + "_" + parent_div_parts[1] + "_" + parent_div_parts[2];
								var start1 = $("#" + parent_div).find('span:first').text() + ":";
							}
							var start2 = $("label[for='" + parent_id + "']").text();
							var start3_n = start1.lastIndexOf('degrees');
							if (start3_n != -1) {
								var end_text = ' degrees.';
							} else {
								var end_text = '';
							}
							if (!!start2) {
								var start_text = start2 + ' ' + start1;
							} else {
								var start_text = start1;
							}
							var old = $("#hpi").val();
							var a_pointer = a.length - 1;
							var a_pointer2 = a.lastIndexOf('.');
							if (!!old) {
								if (!!start_text) {
									var c = start_text + ' ' + a + end_text;
									if (old_text != '') {
										var c_old = start_text + ' ' + old_text + end_text;
									}
								} else {
									if (a_pointer != a_pointer2) {
										var c = a + '.';
									} else {
										var c = a;
									}
								}
								if (old_text != '') {
									var old_text_pointer = old_text.length - 1;
									var old_text_pointer2 = old_text.lastIndexOf('.');
									if (old_text_pointer != old_text_pointer2) {
										var old_text1 = old_text + '.';
									} else {
										var old_text1 = old_text;
									}
									if (!!start_text) {
										var b = old.replace(c_old, c);
									} else {
										var b = old.replace(old_text1, c);
									}
									old_text = '';
								} else {
									var item_class = $(this).attr("class");
									if (item_class == "hpi_other hpi_detail_text ui-dform-text ui-widget-content ui-corner-all") {
										var b = old + " " +c;
									} else {
										var b = old + "\n" + c;
									}
								}
							} else {
								if (!!start_text) {
									var b = start_text + ' ' + a + end_text;
								} else {
									if (a_pointer != a_pointer2) {
										var b = a + '.';
									} else {
										var b = a;
									}
								}
							}
							$("#hpi").val(b);
						}
					});
					$('#hpi_template_form').find('.hpi_detail').click(function() {
						var detail_id = $(this).attr("id") + '_detail';
						if ($(this).is(':checked')) {
							$('#' + detail_id).show('fast');
							$('#' + detail_id).focus();
						} else {
							var parent_id = $(this).attr("id");
							var old = $("#hpi").val();
							var a = ' ' + $('#' + detail_id).val();
							var a1 = a + '  ';
							var c = old.replace(a1,'');
							c = c.replace(a, '');
							$("#hpi").val(c);
							$('#' + detail_id).hide('fast');
						}
					});
					$('#hpi_template_form').find('.hpi_normal').click(function() {
						if ($(this).is(':checked')) {
							$(this).siblings('input:checkbox').each(function(){
								var parent_id = $(this).attr("id");
								$(this).prop('checked',false);
								var old = $("#hpi").val();
								var a = $(this).val();
								var a1 = '\n' + a;
								var c = old.replace(a1,'');
								c = c.replace(a, '');
								$("#hpi").val(c);
								$("#hpi_template_form input").button('refresh');
							});
							$("#hpi_template_form").find('.hpi_detail_text').each(function(){
								if ($(this).val() != '') {
									var parent_id = $(this).attr("id");
									var old = $("#hpi").val();
									var a = ' ' + $(this).val();
									var a1 = '\n' + a;
									var c = old.replace(a1,'');
									c = c.replace(a, '');
									$("#hpi").val(c);
								}
								$(this).hide();
							});
						}
					});
				}
			});
		}
	});
	$('#hpi_reset').click(function(){
		$("#hpi").val('');
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
});
