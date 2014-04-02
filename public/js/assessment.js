$(document).ready(function() {
	$("#assessment_buttons").hide();
	loadbuttons();
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-assessment",
		dataType: "json",
		success: function(data){
			if (data != '') {
				$.each(data, function(key, value){
					$("#assessment_form :input[name='" + key + "']").val(value);
					$("#"+key+"_old").val(value);
				});
				if(data.assessment_1 != '' && data.assessment_1 != null){
					var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
					$("#assessment_icd1_div").html(label1);
					$("#assessment_icd1_div_button").show();
				}
				if(data.assessment_2 != '' && data.assessment_2 != null){
					var label2 = '<strong>Diagnosis #2:</strong> ' + data.assessment_2;
					$("#assessment_icd2_div").html(label2);
					$("#assessment_icd2_div_button").show();
				}
				if(data.assessment_3 != '' && data.assessment_3 != null){
					var label3 = '<strong>Diagnosis #3:</strong> ' + data.assessment_3;
					$("#assessment_icd3_div").html(label3);
					$("#assessment_icd3_div_button").show();
				}
				if(data.assessment_4 != '' && data.assessment_4 != null){
					var label4 = '<strong>Diagnosis #4:</strong> ' + data.assessment_4;
					$("#assessment_icd4_div").html(label4);
					$("#assessment_icd4_div_button").show();
				}
				if(data.assessment_5 != '' && data.assessment_5 != null){
					var label5 = '<strong>Diagnosis #5:</strong> ' + data.assessment_5;
					$("#assessment_icd5_div").html(label5);
					$("#assessment_icd5_div_button").show();
				}
				if(data.assessment_6 != '' && data.assessment_6 != null){
					var label6 = '<strong>Diagnosis #6:</strong> ' + data.assessment_6;
					$("#assessment_icd6_div").html(label6);
					$("#assessment_icd6_div_button").show();
				}
				if(data.assessment_7 != '' && data.assessment_7 != null){
					var label7 = '<strong>Diagnosis #7:</strong> ' + data.assessment_7;
					$("#assessment_icd7_div").html(label7);
					$("#assessment_icd7_div_button").show();
				}
				if(data.assessment_8 != '' && data.assessment_8 != null){
					var label8 = '<strong>Diagnosis #8:</strong> ' + data.assessment_8;
					$("#assessment_icd8_div").html(label8);
					$("#assessment_icd8_div_button").show();
				}
				if(data.assessment_9 != '' && data.assessment_9 != null){
					var label9 = '<strong>Diagnosis #9:</strong> ' + data.assessment_9;
					$("#assessment_icd9_div").html(label9);
					$("#assessment_icd9_div_button").show();
				}
				if(data.assessment_10 != '' && data.assessment_10 != null){
					var label10 = '<strong>Diagnosis #10:</strong> ' + data.assessment_10;
					$("#assessment_icd10_div").html(label10);
					$("#assessment_icd10_div_button").show();
				}
				if(data.assessment_11 != '' && data.assessment_11 != null){
					var label11 = '<strong>Diagnosis #11:</strong> ' + data.assessment_11;
					$("#assessment_icd11_div").html(label11);
					$("#assessment_icd11_div_button").show();
				}
				if(data.assessment_12 != '' && data.assessment_12 != null){
					var label12 = '<strong>Diagnosis #12:</strong> ' + data.assessment_12;
					$("#assessment_icd12_div").html(label12);
					$("#assessment_icd12_div_button").show();
				}
			}
		}
	});
	$("#assessment_issues").button().click(function() {
		$("#issues_list_dialog").dialog('open');
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_lab_header').hide();
		$('#issues_rad_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_ref_header').hide();
		$('#issues_assessment_header').show();
	});
	$("#assessment_icd_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/icd9",
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
		minLength: 3,
		select: function(event, ui){
			$("#assessment_buttons").show();
		}
	});
	$('.assessment_select').click(function(){
		var id = $(this).attr('id');
		var parts = id.split('_');
		var issue = $("#assessment_icd_search").val();
		var pos = issue.indexOf('[');
		if (pos == -1) {
			$.jGrowl('Please select issue to copy!');
		} else {
			var icd1 = issue.slice(pos);
			var icd2 = icd1.replace("[", "");
			var icd = icd2.replace("]", "");
			$("#assessment_icd" + parts[3]).val(icd);
			$("#assessment_" + parts[3]).val(issue);
			var label = '<strong>Diagnosis #' + parts[3] + ':</strong> ' + issue;
			$("#assessment_icd" + parts[3] + "_div").html(label);
			$("#assessment_icd" + parts[3] + "_div_button").show();
			$.jGrowl('Issue copied to Diagnosis #' + parts[3] + '!');
			$("#assessment_icd_search").val('');
			$("#assessment_buttons").hide();
		}
	});
	$("#assessment_select_icd_13").click(function(){
		var issue = $("#assessment_icd_search").val();
		var old = $("#assessment_other").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old;
				} else {
					var old1 = old + '\n';
				}
			}
		} else {
			var old1 = '';
		}
		$("#assessment_other").val(old1+issue);
		$.jGrowl('Issue copied!');
		$("#assessment_icd_search").val('');
		$("#assessment_buttons").hide();
	});
	$("#assessment_select_icd_14").click(function(){
		var issue = $("#assessment_icd_search").val();
		var old = $("#assessment_ddx").val();
		if(old){
			var pos = old.lastIndexOf('\n');
			if (pos == -1) {
				var old1 = old + '\n';
			} else {
				var a = old.slice(pos);
				if (a == '') {
					var old1 = old;
				} else {
					var old1 = old + '\n';
				}
			}
		} else {
			var old1 = '';
		}
		$("#assessment_ddx").val(old1+issue);
		$.jGrowl('Issue copied!');
		$("#assessment_icd_search").val('');
		$("#assessment_buttons").hide();
	});
	$('.assessment_clear').click(function(){
		var id = $(this).attr('id');
		var parts = id.split('_');
		$("#assessment_" + parts[3]).val('');
		$("#assessment_icd" + parts[3]).val('');
		$("#assessment_icd" + parts[3] + "_div").html('');
		$("#assessment_icd" + parts[3] + "_div_button").hide();
	});
	$("#assessment_other_reset").button();
	$('#assessment_other_reset').click(function(){
		$("#assessment_other").val('');
	});
	$("#assessment_ddx_reset").button();
	$('#assessment_ddx_reset').click(function(){
		$("#assessment_ddx").val('');
	});
	$("#assessment_notes_reset").button();
	$('#assessment_notes_reset').click(function(){
		$("#assessment_notes").val('');
	});
	setInterval(assessment_autosave, 10000);
});
