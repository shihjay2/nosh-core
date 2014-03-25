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
				if(data.assessment_1.length!=0){
					var label1 = '<strong>Diagnosis #1:</strong> ' + data.assessment_1;
					$("#assessment_icd1_div").html(label1);
					$("#assessment_icd1_div_button").show('fast');
				}
				if(data.assessment_2.length!=0){
					var label2 = '<strong>Diagnosis #2:</strong> ' + data.assessment_2;
					$("#assessment_icd2_div").html(label2);
					$("#assessment_icd2_div_button").show('fast');
				}
				if(data.assessment_3.length!=0){
					var label3 = '<strong>Diagnosis #3:</strong> ' + data.assessment_3;
					$("#assessment_icd3_div").html(label3);
					$("#assessment_icd3_div_button").show('fast');
				}
				if(data.assessment_4.length!=0){
					var label4 = '<strong>Diagnosis #4:</strong> ' + data.assessment_4;
					$("#assessment_icd4_div").html(label4);
					$("#assessment_icd4_div_button").show('fast');
				}
				if(data.assessment_5.length!=0){
					var label5 = '<strong>Diagnosis #5:</strong> ' + data.assessment_5;
					$("#assessment_icd5_div").html(label5);
					$("#assessment_icd5_div_button").show('fast');
				}
				if(data.assessment_6.length!=0){
					var label6 = '<strong>Diagnosis #6:</strong> ' + data.assessment_6;
					$("#assessment_icd6_div").html(label6);
					$("#assessment_icd6_div_button").show('fast');
				}
				if(data.assessment_7.length!=0){
					var label7 = '<strong>Diagnosis #7:</strong> ' + data.assessment_7;
					$("#assessment_icd7_div").html(label7);
					$("#assessment_icd7_div_button").show('fast');
				}
				if(data.assessment_8.length!=0){
					var label8 = '<strong>Diagnosis #8:</strong> ' + data.assessment_8;
					$("#assessment_icd8_div").html(label8);
					$("#assessment_icd8_div_button").show('fast');
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
		$('#edit_issue_form').hide();
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
	$("#assessment_select_icd_9").click(function(){
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
	$("#assessment_select_icd_10").click(function(){
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
