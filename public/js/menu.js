$(document).ready(function() {
	$.ajax({
		type: "POST",
		url: "ajaxchart/demographics-load",
		dataType: "json",
		success: function(data){
			$('#menu_ptname').html(data.ptname);
			$('#menu_nickname').html(data.nickname);
			$('#menu_dob').html(data.dob);
			$('#menu_age').html(data.age);
			$('#menu_gender1').html(data.gender);
			if (data.new == 'Y') {
				open_demographics();
			}
		}
	});
	$("#menu_accordion").accordion({
		event: "click hoverintent",
		activate: function (event, ui) {
			var id = ui.newPanel[0].id;
			if (id != "menu_accordion_chart") {
				$("#" + id + "_load").show();
				var id1 = id.replace("menu_accordion_", "");
				$.ajax({
					type: "POST",
					url: "ajaxchart/" + id1,
					success: function(data){
						$("#" + id + "_content").html(data);
						$("#" + id + "_load").hide();
					}
				});
			}
		},
		active: false,
		collpasible: true
	});
	$("#prevention_list_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$('#prevention_items').html('');
			$('#prevention_load').show();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#prevention_list").click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxchart/prevention",
			success: function(data){
				$('#prevention_items').html(data);
				$('#prevention_load').hide();
			}
		});
		$("#prevention_list_dialog").dialog('open');
	});
	$("#hedis_chart_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 500,
		width: 800,
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$('#hedis_chart_load').hide();
		},
		close: function(event, ui) {
			$('#hedis_chart_items').html('');
			$('#hedis_chart_load').hide();
			$('#hedis_chart_question').show();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#hedis_chart").click(function() {
		$("#hedis_chart_dialog").dialog('open');
	});
	$("#hedis_chart_time").mask("99/99/9999").datepicker();
	$("#hedis_chart_spec").click(function() {
		var a = $("#hedis_chart_time").val();
		if (a !== '') {
			$('#hedis_chart_load').show();
			$.ajax({
				type: "POST",
				url: "ajaxchart/hedis-chart-audit/spec",
				data: "time=" + a,
				success: function(data){
					$('#hedis_chart_items').html(data);
					$('#hedis_chart_load').hide();
					$('#hedis_chart_question').hide();
				}
			});
		} else {
			$.jGrowl('Enter a time value!');
		}
	});
	$("#hedis_chart_all").click(function() {
		$('#hedis_chart_load').show();
		$.ajax({
			type: "POST",
			url: "ajaxchart/hedis-chart-audit/all",
			success: function(data){
				$('#hedis_chart_items').html(data);
				$('#hedis_chart_load').hide();
				$('#hedis_chart_question').hide();
			}
		});
	});
	$("#hedis_chart_year").click(function() {
		$('#hedis_chart_load').show();
		$.ajax({
			type: "POST",
			url: "ajaxchart/hedis-chart-audit/year",
			success: function(data){
				$('#hedis_chart_items').html(data);
				$('#hedis_chart_load').hide();
				$('#hedis_chart_question').hide();
			}
		});
	});
	if (noshdata.hedis !== '') {
		$("#hedis_chart_dialog").dialog('open');
		$.ajax({
			type: "POST",
			url: "ajaxsearch/hedis-unset",
			success: function(data){
				noshdata.hedis = '';
			}
		});
	}
});
