$(document).ready(function() {
	function extensions_save() {
		var str = $("#extensions_form").serialize();
		$.ajax({
			type: "POST",
			url: "ajaxsetup/edit-extensions",
			data: str,
			success: function(data){
				$.jGrowl(data);
			}
		});
	}
	$("#extensions_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#extensions_accordion").accordion({heightStyle: "fill"});
			$.ajax({
				type: "POST",
				url: "ajaxsetup/get-providers",
				dataType: "json",
				success: function(data){
					if (data.message == "OK") {
						$("#mtm_alert_users").addOption(data, false).removeOption("message").trigger("liszt:updated");
					} else {
						$.jGrowl(data.message);
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxsetup/get-practice",
				dataType: "json",
				success: function(data){
					$.each(data, function(key, value){
						$("#extensions_form :input[name='" + key + "']").val(value);
					});
				}
			});
		},
		buttons: {
			'Save': function() {
				extensions_save();
				$("#extensions_form").clearForm();
				$("#extensions_dialog").dialog('close');
			},
			Cancel: function() {
				$("#extensions_form").clearForm();
				$("#extensions_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#dashboard_extensions").click(function(){
		$("#extensions_dialog").dialog('open');
	});
	$("#updox_extension").addOption({"n":"No","y":"Yes"});
	$("#rcopia_extension").addOption({"n":"No","y":"Yes"});
	$("#snomed_extension").addOption({"n":"No","y":"Yes"});
	$("#mtm_extension").addOption({"n":"No","y":"Yes"});
	$("#extensions_form select").val("n");
	$("#mtm_alert_users").chosen();
	$("#rcopia_extension").change(function(){
		if ($(this).val() == 'y') {
			extensions_save();
			$.ajax({
				type: "POST",
				url: "ajaxsetup/check-extension/rcopia",
				success: function(data){
					if (data == "Extension status: OK!") {
						$.jGrowl(data);
					} else {
						$.jGrowl(data, { sticky: true });
						$("#rcopia_extension").val("n");
						extensions_save();
					}
				}
			});
		}
	});
	$("#snomed_extension").change(function(){
		if ($(this).val() == 'y') {
			$.ajax({
				type: "POST",
				url: "ajaxsetup/check-extension/snomed",
				success: function(data){
					if (data == "Extension status: OK!") {
						$.jGrowl(data);
					} else {
						$.jGrowl(data, { sticky: true });
						$("#snomed_extension").val("n");
					}
				}
			});
		}
	});
});
