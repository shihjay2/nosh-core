$(document).ready(function() {
	function ros_dialog_save(type) {
		var str = $("#ros_"+type+"_dialog_form").serialize();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/ros-save/" + type,
			data: str,
			success: function(data){
				$.jGrowl(data);
				$("#ros_"+type+"_dialog").dialog('close');
				check_ros_status();
			}
		});
	}
	loadbuttons();
	$('.reset').click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		$("#" + parent_id).val('');
	});
	$('.per_hpi').click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		var old = $("#" + parent_id).val();
		var a = "Per History of Present Illness.  ";
		$("#" + parent_id).val(old + a);
	
	});
	$('.nc').click(function(){
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		parent_id = parent_id.substring(0,n);
		var old = $("#" + parent_id).val();
		var a = "Noncontributory.  ";
		$("#" + parent_id).val(old + a);
	});
	$('.ros_template_choose').change(function(){
		var id = $(this).attr('id');
		id = id.replace('_template', '');
		var a = $(this).val();
		if (a != '') {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-ros-templates/" +id + "/" + a + "/n",
				dataType: "json",
				success: function(data){
					$('#'+id+'_form').html('');
					var stringConstructor = "test".constructor;
					var objectConstructor = {}.constructor;
					if (data.constructor === stringConstructor) {
						var json_object = JSON.parse(data);
						$('#'+id+'_form').dform(json_object);
					} else {
						$('#'+id+'_form').dform(data);
					}
					ros_form_load();
				}
			});
		}
	});
	$("#ros_gen_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('gen');
			},
			Cancel: function() {
				$("#ros_gen_dialog_form").clearForm();
				$("#ros_gen_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_eye_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('eye');
			},
			Cancel: function() {
				$("#ros_eye_dialog_form").clearForm();
				$("#ros_eye_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_ent_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('ent');
			},
			Cancel: function() {
				$("#ros_ent_dialog_form").clearForm();
				$("#ros_ent_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_resp_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('resp');
			},
			Cancel: function() {
				$("#ros_resp_dialog_form").clearForm();
				$("#ros_resp_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_cv_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('cv');
			},
			Cancel: function() {
				$("#ros_cv_dialog_form").clearForm();
				$("#ros_cv_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_gi_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('gi');
			},
			Cancel: function() {
				$("#ros_gi_dialog_form").clearForm();
				$("#ros_gi_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_gu_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('gu');
			},
			Cancel: function() {
				$("#ros_gu_dialog_form").clearForm();
				$("#ros_gu_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_mus_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('mus');
			},
			Cancel: function() {
				$("#ros_mus_dialog_form").clearForm();
				$("#ros_mus_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_neuro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('neuro');
			},
			Cancel: function() {
				$("#ros_neuro_dialog_form").clearForm();
				$("#ros_neuro_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_psych_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('psych');
			},
			Cancel: function() {
				$("#ros_psych_dialog_form").clearForm();
				$("#ros_psych_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_heme_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('heme');
			},
			Cancel: function() {
				$("#ros_heme_dialog_form").clearForm();
				$("#ros_heme_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_endocrine_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('endocrine');
			},
			Cancel: function() {
				$("#ros_endocrine_dialog_form").clearForm();
				$("#ros_endocrine_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_skin_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('skin');
			},
			Cancel: function() {
				$("#ros_skin_dialog_form").clearForm();
				$("#ros_skin_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#ros_wcc_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 850, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				ros_dialog_save('wcc');
			},
			Cancel: function() {
				$("#ros_wcc_dialog_form").clearForm();
				$("#ros_wcc_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('.ros_tooltip').tooltip({
		items: ".ros_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			$.ajax({
				type: "POST",
				url: "ajaxencounter/tip-ros/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
				},
			});
		}
	});
	$(".ros_menu_button").click(function() {
		var id = $(this).attr('id');
		id = id.replace('button_', '');
		$("#"+id+"_dialog").dialog('open');
		if (id == "ros_wcc") {
			//var age = parseInt(noshdata.agealldays);
			//if (age <= 2191.44) {
				//$(".ros_wcc_0_5").show('fast');
			//} else {
				//$(".ros_wcc_0_5").hide('fast');
			//}
			//if (age > 730.48 && age <= 6574.32 ) {
				//$(".ros_wcc_2_20").show('fast');
			//} else {
				//$(".ros_wcc_2_20").hide('fast');
			//}
			//if (age <= 60.88) {
				//$(".ros_wcc_0m").show('fast');
			//} else {
				//$(".ros_wcc_0m").hide('fast');
			//}
			//if (age > 60.88 && age <= 121.76) {
				//$(".ros_wcc_2m").show('fast');
			//} else {
				//$(".ros_wcc_2m").hide('fast');
			//}
			//if (age > 121.76 && age <= 182.64) {
				//$(".ros_wcc_4m").show('fast');
			//} else {
				//$(".ros_wcc_4m").hide('fast');
			//}
			//if (age > 182.64 && age <= 273.96) {
				//$(".ros_wcc_6m").show('fast');
			//} else {
				//$(".ros_wcc_6m").hide('fast');
			//}
			//if (age > 273.96 && age <= 365.24) {
				//$(".ros_wcc_9m").show('fast');
			//} else {
				//$(".ros_wcc_9m").hide('fast');
			//}
			//if (age > 365.24 && age <= 456.6) {
				//$(".ros_wcc_12m").show('fast');
			//} else {
				//$(".ros_wcc_12m").hide('fast');
			//}
			//if (age > 456.6 && age <= 547.92) {
				//$(".ros_wcc_15m").show('fast');
			//} else {
				//$(".ros_wcc_15m").hide('fast');
			//}
			//if (age > 547.92 && age <= 730.48) {
				//$(".ros_wcc_18m").show('fast');
			//} else {
				//$(".ros_wcc_18m").hide('fast');
			//}
			//if (age > 730.48 && age <= 1095.75) {
				//$(".ros_wcc_2").show('fast');
			//} else {
				//$(".ros_wcc_2").hide('fast');
			//}
			//if (age > 1095.75 && age <= 1461) {
				//$(".ros_wcc_3").show('fast');
			//} else {
				//$(".ros_wcc_3").hide('fast');
			//}
			//if (age > 1461 && age <= 1826.25) {
				//$(".ros_wcc_4").show('fast');
			//} else {
				//$(".ros_wcc_4").hide('fast');
			//}
			//if (age > 1826.25 && age <= 2191.44) {
				//$(".ros_wcc_5").show('fast');
			//} else {
				//$(".ros_wcc_5").hide('fast');
			//}
		}
	});
});
