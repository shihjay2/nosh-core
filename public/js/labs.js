$(document).ready(function() {
	loadbuttons();
	$("#labs_ua_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		open: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-labs",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#labs_ua_form :input[name='" + key + "']").val(value);
					});
				}
			});
			$("#labs_ua").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#labs_ua_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxencounter/labs-save/ua",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#labs_ua_form").clearForm();
							$("#labs_ua_dialog").dialog('close');
							check_labs1();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#labs_ua_form").clearForm();
				$("#labs_ua_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#labs_rapid_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		open: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-labs",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#labs_rapid_form :input[name='" + key + "']").val(value);
					});
				}
			});
			$("#labs_rapid").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#labs_rapid_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxencounter/labs-save/rapid",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#labs_rapid_form").clearForm();
							$("#labs_rapid_dialog").dialog('close');
							check_labs1();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#labs_rapid_form").clearForm();
				$("#labs_rapid_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#labs_micro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		open: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-labs",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#labs_micro_form :input[name='" + key + "']").val(value);
					});
				}
			});
			$("#labs_micro").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#labs_micro_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxencounter/labs-save/micro",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#labs_micro_form").clearForm();
							$("#labs_micro_dialog").dialog('close');
							check_labs1();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#labs_micro_form").clearForm();
				$("#labs_micro_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#labs_other_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		beforeclose: function (event, ui) { return false; },
		dialogClass: "noclose",
		open: function (event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-labs",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#labs_other_form :input[name='" + key + "']").val(value);
					});
				}
			});
			$("#labs_other").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#labs_ua_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "ajaxencounter/labs-save/other",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#labs_other_form").clearForm();
							$("#labs_other_dialog").dialog('close');
							check_labs1();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#labs_other_form").clearForm();
				$("#labs_other_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('.labs_tooltip').tooltip({
		items: ".labs_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui){
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[2];
			$.ajax({
				type: "POST",
				url: "ajaxencounter/tip-labs/" + id1,
				success: function(data){
					elem.tooltip('option', 'content', data);
				}
			});
		}
	});
	$("#button_labs_ua").click(function() {
		$("#labs_ua_dialog").dialog('open');
	});
	$("#button_labs_rapid").click(function() {
		$("#labs_rapid_dialog").dialog('open');
	});
	$("#button_labs_micro").click(function() {
		$("#labs_micro_dialog").dialog('open');
	});
	$("#button_labs_other").click(function() {
		$("#labs_other_dialog").dialog('open');
	});
});
