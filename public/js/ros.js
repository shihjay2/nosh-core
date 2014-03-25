$(document).ready(function() {
	function updateTextArea(parent_id_entry) {
		var newtext = '';
		$('#' + parent_id_entry + '_form :checked').each(function() {
			newtext += $(this).val() + '  ';
		});
		$('#' + parent_id_entry).val(newtext);
	}
	function ros_form_load() {
		$('.ros_buttonset').buttonset();
		$('.ros_detail_text').hide();
		$("#ros_gu_menarche").datepicker();
		$("#ros_gu_lmp").datepicker();
	}
	function get_ros_templates(group, id, type) {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-ros-templates/" + group + "/" + id + "/" + type,
			dataType: "json",
			success: function(data){
				$('#'+group+'_form').html('');
				$('#'+group+'_form').dform(data);
				ros_form_load();
			}
		});
	}
	function ros_dialog_open(type) {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/ros-template-select-list/" + type,
			dataType: "json",
			success: function(data){
				$('#'+type+'_template').addOption({"":"*Select a template"});
				$('#'+type+'_template').addOption(data.options);
				$('#'+type+'_template').sortOptions();
				$('#'+type+'_template').val("");
				if ($('#'+type+'_form').html() == '') {
					get_ros_templates(type, '0', 'y');
				}
			}
		});
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-ros/" + type,
			success: function(data){
				$('#'+type).val(data);
				$('#'+type+'_old').val(data);
			}
		});
		$("#"+type).focus();
	}
	function ros_dialog_save(type) {
		var str = $("#ros_"+type+"_dialog_form").serialize();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/ros-save/" + type,
			data: str,
			success: function(data){
				$.jGrowl(data);
				$("#ros_"+type+"_dialog_form").clearForm();
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
	$('.ros_template_div').on("change", 'input[type="checkbox"]', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if ($(this).prop('checked')) {
			if (old != '') {
				var b = old + a + '  ';
			} else {
				var b = a + '  ';
			}
			$("#" + parent_id_entry).val(b); 
		} else {
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c); 
		}
	});
	$('.ros_template_div').on("change", 'input[type="radio"]', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if ($(this).prop('checked')) {
			if (old != '') {
				var b = old + a + '  ';
			} else {
				var b = a + '  ';
			}
			$("#" + parent_id_entry).val(b); 
		} else {
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c); 
		}
	});
	$('.ros_template_div').on("change", 'select', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
		}
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if (old != '') {
			var b = old + a + '  ';
		} else {
			var b = a + '  ';
		}
		$("#" + parent_id_entry).val(b); 
	});
	$('.ros_template_div').on('focusin', 'input[type="text"]', function() {
		old_text = $(this).val();
	});
	$('.ros_template_div').on('focusout', 'input[type="text"]', function() {
		var a = $(this).val();
		if (a != '') {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			if (parts[1] == 'wccage') {
				var parent_id_entry = 'ros_wcc';
			} else {
				var parent_id_entry = parts[0] + '_' + parts[1];
			}
			var x = parent_id.length - 1;
			var parent_div = parent_id.slice(0,x);
			var start1 = $("#" + parent_div + "_div").find('span:first').text();
			if (start1 == '') {
				start1 = $("#" + parts[0] + '_' + parts[1] + '_' + parts[2] + '_label').text();
			}
			var start1_n = start1.lastIndexOf(' (');
			if (start1_n != -1) {
				var start1_n1 = start1.substring(0,start1_n);
				var start1_n2 = start1_n1.toLowerCase();
			} else {
				var start1_n1 = start1;
				var start1_n2 = start1;
			}
			var start2 = $("label[for='" + parent_id + "']").text();
			var start3_n = start1.lastIndexOf('degrees');
			if (start3_n != -1) {
				var end_text = ' degrees.';
			} else {
				var end_text = '';
			}
			var start4 = $(this).closest('div.ui-accordion').find('h3.ui-state-active').text();
			if (start4 != '') {
				var start4_n = start4.lastIndexOf('-');
				if (start4_n != -1) {
					var parts2 = start4.split(' - ');
					var mid_text = ', ' + parts2[1].toLowerCase();
				} else {
					var mid_text = ', ' + start4.toLowerCase();
				}
			} else {
				var mid_text = '';
			}
			if (!!start2) {
				var start_text = start2 + ' ' + start1_n2;
			} else {
				var start_text = start1_n1;
			}
			var old = $("#" + parent_id_entry).val();
			var a_pointer = a.length - 1;
			var a_pointer2 = a.lastIndexOf('.');
			if (!!old) {
				if (!!start_text) {
					var c = start_text + mid_text + ': ' + a + end_text;
					if (old_text != '') {
						var c_old = start_text + mid_text + ': ' + old_text + end_text;
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
					var b = old + ' ' + c;
				}
			} else {
				if (!!start_text) {
					var b = start_text + mid_text + ': ' + a + end_text;
				} else {
					if (a_pointer != a_pointer2) {
						var b = a + '.';
					} else {
						var b = a;
					}
				}
			}
			$("#" + parent_id_entry).val(b);
		}
	});
	$('.ros_template_div').on('click', '.ros_detail', function() {
		var detail_id = $(this).attr("id") + '_detail';
		if ($(this).prop('checked')) {
			$('#' + detail_id).show('fast');
			$('#' + detail_id).focus();
		} else {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			if (parts[1] == 'wccage') {
			var parent_id_entry = 'ros_wcc';
			} else {
				var parent_id_entry = parts[0] + '_' + parts[1];
			}
			var old = $("#" + parent_id_entry).val();
			var a = ' ' + $('#' + detail_id).val();
			var a1 = a + '  ';
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c);
			$('#' + detail_id).hide('fast');
		}
	});
	$('.ros_template_div').on('click', '.ros_normal', function() {
		if ($(this).prop('checked')) {
			var parent_id = $(this).attr("id");
			var x = parent_id.length - 1;
			parent_id = parent_id.slice(0,x);
			$("#" + parent_id + "_div").find('.ros_other:checkbox').each(function(){
				var parent_id = $(this).attr("id");
				$(this).prop('checked',false);
				var parts = parent_id.split('_');
				if (parts[1] == 'wccage') {
					var parent_id_entry = 'ros_wcc';
				} else {
					var parent_id_entry = parts[0] + '_' + parts[1];
				}
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
				$("#" + parent_id_entry + "_form input").button('refresh');
				if (parts[1] == 'wccage') {
					$("#ros_wcc_age_form input").button('refresh');
				}
			});
			$("#" + parent_id + "_div").find('.ros_detail_text').each(function(){
				var parent_id = $(this).attr("id");
				var parts = parent_id.split('_');
				if (parts[1] == 'wccage') {
					var parent_id_entry = 'ros_wcc';
				} else {
					var parent_id_entry = parts[0] + '_' + parts[1];
				}
				var old = $("#" + parent_id_entry).val();
				var a = ' ' + $(this).val();
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
				$(this).hide();
			});
		}
	});
	$('.ros_template_div').click('click', '.ros_other', function() {
		if ($(this).prop('checked')) {
			var parent_id = $(this).attr("id");
			var x = parent_id.length - 1;
			parent_id = parent_id.slice(0,x);
			$("#" + parent_id + "_div").find('.ros_normal:checkbox').each(function(){
				var parent_id = $(this).attr("id");
				$(this).prop('checked',false);
				var parts = parent_id.split('_');
				if (parts[1] == 'wccage') {
					var parent_id_entry = 'ros_wcc';
				} else {
					var parent_id_entry = parts[0] + '_' + parts[1];
				}
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
				$("#" + parent_id_entry + "_form input").button('refresh');
				if (parts[1] == 'wccage') {
					$("#ros_wcc_age_form input").button('refresh');
				}
			});
		}
	});
	$('.all_normal').click(function(){
		var a = $(this).prop('checked');
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		if (parts[1] == 'wcc') {
			if(a){
				$("#ros_wcc_form").find("input.ros_normal:checkbox").each(function(){
					$(this).prop("checked",true);
				});
				$("#ros_wcc_age_form").find("input.ros_normal:checkbox").each(function(){
					$(this).prop("checked",true);
				});
				var newtext = '';
				$('#ros_wcc_form :checked').each(function() {
					newtext += $(this).val() + '  ';
				});
				$('#ros_wcc_age_form :checked').each(function() {
					newtext += $(this).val() + '  ';
				});
				$('#ros_wcc').val(newtext);
			} else {
				$("#ros_wcc").val('');
				$("#ros_wcc_form").find('input.ros_normal:checkbox').each(function(){
					$(this).prop("checked",false);
				});
				$("#ros_wcc_age_form").find('input.ros_normal:checkbox').each(function(){
					$(this).prop("checked",false);
				});
			}
			$('#ros_wcc_form input[type="checkbox"]').button('refresh');
			$('#ros_wcc_age_form input[type="checkbox"]').button('refresh');
		} else {
			var parent_id_entry = parts[0] + '_' + parts[1];
			if(a){
				$("#" + parent_id_entry + "_form").find("input.ros_normal:checkbox").each(function(){
					$(this).prop("checked",true);
				});
				updateTextArea(parent_id_entry);
			} else {
				$("#" + parent_id_entry).val('');
				$("#" + parent_id_entry + "_form").find('input.ros_normal:checkbox').each(function(){
					$(this).prop("checked",false);
				});
			}
			$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
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
		open: function(event, ui) {
			ros_dialog_open('ros_gen');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_eye');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_ent');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_resp');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_cv');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_gi');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_gu');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_mus');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_neuro');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_psych');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_heme');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_endocrine');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_skin');
		},
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
		open: function(event, ui) {
			ros_dialog_open('ros_wcc');
		},
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
