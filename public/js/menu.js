$(document).ready(function() {
	//var windowHeight = $(window).height() - 115;
	//$('#menucontainer').css('max-height', windowHeight);
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
				$("#demographics_list_dialog").dialog('open');
			}
		}
	});
	$("#menu_accordion").accordion({
		//heightStyle: "fill",
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
});
