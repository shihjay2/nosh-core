$(document).ready(function() {
	$("#growth_chart_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 100, 
		width: 700, 
		modal: true,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#gc_list").click(function(){
		$("#growth_chart_dialog").dialog('open');
	});
	$("#dashboard_growth_chart").click(function(){
		$("#growth_chart_dialog").dialog('open');
	});
	$("#graph_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#graph_load").dialog({
		height: 100,
		autoOpen: false,
		modal: true,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(document).on("click", '.weight_chart', function() {
		var origin = $(this).hasClass('menu');
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "ajaxcommon/growth-chart/weight-age",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				if (origin == false) {
					var note = $("#vitals_vitals_other").val();
					if (note == '') {
						var newnote = 'Weight-to-age percentile: ' + data.percentile + '.';
					} else {
						var newnote = note + '  Weight-to-age percentile: ' + data.percentile + '.';
					}
					$("#vitals_vitals_other").val(newnote);
					vitals_autosave();
				}
			}
		});
	});
	$(document).on("click", '.height_chart', function() {
		var origin = $(this).hasClass('menu');
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "ajaxcommon/growth-chart/height-age",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				if (origin == false) {
					var note = $("#vitals_vitals_other").val();
					if (note == '') {
						var newnote = 'Height-to-age percentile: ' + data.percentile + '.';
					} else {
						var newnote = note + '  Height-to-age percentile: ' + data.percentile + '.';
					}
					$("#vitals_vitals_other").val(newnote);
					vitals_autosave();
				}
			}
		});
	});
	$(document).on("click", '.hc_chart', function() {
		var origin = $(this).hasClass('menu');
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "ajaxcommon/growth-chart/head-age",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				if (origin == false) {
					var note = $("#vitals_vitals_other").val();
					if (note == '') {
						var newnote = 'Head circumference-to-age percentile: ' + data.percentile + '.';
					} else {
						var newnote = note + '  Head circumference-to-age percentile: ' + data.percentile + '.';
					}
					$("#vitals_vitals_other").val(newnote);
					vitals_autosave();
				}
			}
		});
	});
	$(document).on("click", '.bmi_chart', function() {
		var origin = $(this).hasClass('menu');
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'spline', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "ajaxcommon/growth-chart/bmi-age",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				if (origin == false) {
					var note = $("#vitals_vitals_other").val();
					if (note == '') {
						var newnote = 'BMI-to-age percentile: ' + data.percentile + '.';
					} else {
						var newnote = note + '  BMI-to-age percentile: ' + data.percentile + '.';
					}
					$("#vitals_vitals_other").val(newnote);
					vitals_autosave();
				}
			}
		});
	});
	$(document).on("click", '.weight_height_chart', function() {
		var origin = $(this).hasClass('menu');
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				}
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "ajaxcommon/growth-chart/weight-height",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				if (origin == false) {
					var note = $("#vitals_vitals_other").val();
					if (note == '') {
						var newnote = 'Weight-to-height percentile: ' + data.percentile + '.';
					} else {
						var newnote = note + '  Weight-to-height percentile: ' + data.percentile + '.';
					}
					$("#vitals_vitals_other").val(newnote);
					vitals_autosave();
				}
			}
		});
	});
});
