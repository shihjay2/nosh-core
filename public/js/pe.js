$(document).ready(function() {
	function updateTextArea(parent_id_entry) {
		var newtext = '';
		$('#' + parent_id_entry + '_form :checked').each(function() {
			newtext += $(this).val() + '  ';
		});
		$('#' + parent_id_entry).val(newtext);
	}
	function pe_form_load() {
		$('.pe_buttonset').buttonset();
		$('.pe_detail_text').hide();
	}
	function get_pe_templates(group, id, type) {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-pe-templates/" + group + "/" + id + "/" + type,
			dataType: "json",
			success: function(data){
				$('#'+group+'_form').html('');
				$('#'+group+'_form').dform(data);
				pe_form_load();
			}
		});
	}
	function pe_accordion_action(id, dialog_id) {
		$("#" + id + " .text").first().focus();
		$("#"+dialog_id).find('.pe_entry').each(function(){
			var parent_id1 = $(this).attr("id");
			if (!!$(this).val()) {
				$('#' + parent_id1 + '_h').html(noshdata.item_present);
			} else {
				$('#' + parent_id1 + '_h').html(noshdata.item_empty);
			}
		});
	}
	function pe_dialog_open(dialog_id) {
		var accordion_id = dialog_id.replace('_dialog', '_accordion');
		if (!$("#"+accordion_id).hasClass('ui-accordion')) {
			$("#"+accordion_id).accordion({
				create: function(event, ui) {
					var id = ui.panel[0].id;
					pe_accordion_action(id, dialog_id);
				},
				activate: function(event, ui) {
					var id = ui.newPanel[0].id;
					pe_accordion_action(id, dialog_id);
				},
				heightStyle: "content"
			});
		}
		$("#"+dialog_id).find('.pe_entry').each(function(){
			var parent_id = $(this).attr("id");
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-pe/" + parent_id,
				success: function(data){
					$('#' + parent_id).val(data);
					$('#' + parent_id + "_old").val(data);
					if (!!data) {
						$('#' + parent_id + '_h').html(noshdata.item_present);
					} else {
						$('#' + parent_id + '_h').html(noshdata.item_empty);
					}
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxencounter/pe-template-select-list/" + parent_id,
				dataType: "json",
				success: function(data){
					$('#'+parent_id+'_template').addOption({"":"*Select a template"});
					$('#'+parent_id+'_template').addOption(data.options);
					$('#'+parent_id+'_template').sortOptions();
					$('#'+parent_id+'_template').val("");
					if ($('#'+parent_id+'_form').html() == '') {
						get_pe_templates(parent_id, '0', 'y');
					}
				}
			});
		});
	}
	function pe_clear_dialog_form(dialog_id) {
		$("#"+dialog_id).find('.pe_entry').each(function(){
			$(this).val('');
			var id = $(this).attr('id');
			$("#"+id+"_old").val('');
		});
	}
	function pe_dialog_save(dialog_id) {
		var str = '';
		var i = 0;
		if (dialog_id == 'pe_gu_dialog' && noshdata.gender == 'male') {
			i = 6;
		}
		var type = dialog_id.replace('_dialog', '');
		$("#"+dialog_id).find('.pe_entry').each(function(){
			var key = $(this).attr('id');
			var value = encodeURIComponent($(this).val());
			if (dialog_id == 'pe_gu_dialog' && noshdata.gender == 'male') {
				if (i != 6) {
					str += "&";
				}
			} else {
				if (i != 0) {
					str += "&";
				}
			}
			str += key + "=" + value;
			i++;
		});
		$.ajax({
			type: "POST",
			url: "ajaxencounter/pe-save/" + type + "/" + i,
			data: str,
			success: function(data){
				$.jGrowl(data);
				pe_clear_dialog_form(dialog_id);
				$("#"+dialog_id).dialog('close');
				check_pe_status();
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
	$('.pe_template_choose').change(function(){
		var id = $(this).attr('id');
		id = id.replace('_template', '');
		var a = $(this).val();
		if (a != '') {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-pe-templates/" +id + "/" + a + "/n",
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
					pe_form_load();
				}
			});
		}
	});
	$('.pe_template_div').on("change", 'input[type="checkbox"]', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if ($(this).is(':checked')) {
			if (old != '') {
				var b = old + '  ' + a;
			} else {
				var b = a;
			}
			$("#" + parent_id_entry).val(b); 
		} else {
			var a1 = '  ' + a;
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c); 
		}
	});
	$('.pe_template_div').on("change", 'input[type="radio"]', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if ($(this).is(':checked')) {
			if (old != '') {
				var b = old + '  ' + a;
			} else {
				var b = a;
			}
			$("#" + parent_id_entry).val(b); 
		} else {
			var a1 = '  ' + a;
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c); 
		}
	});
	$('.pe_template_div').on("change", 'select', function() {
		var parent_id = $(this).attr("id");
		var parts = parent_id.split('_');
		var parent_id_entry = parts[0] + '_' + parts[1];
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if (old != '') {
			var b = old + '  ' + a;
		} else {
			var b = a;
		}
		$("#" + parent_id_entry).val(b); 
	});
	$('.pe_template_div').on("focusin", 'input[type="text"]', function() {
		old_text = $(this).val();
	});
	$('.pe_template_div').on("focusout", 'input[type="text"]', function() {
		var a = $(this).val();
		if (a != '') {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			var parent_id_entry = parts[0] + '_' + parts[1];
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
	$('.pe_template_div').on("click", '.pe_detail', function() {
		var detail_id = $(this).attr("id") + '_detail';
		if ($(this).is(':checked')) {
			$('#' + detail_id).show('fast');
			$('#' + detail_id).focus();
		} else {
			var parent_id = $(this).attr("id");
			var parts = parent_id.split('_');
			var parent_id_entry = parts[0] + '_' + parts[1];
			var old = $("#" + parent_id_entry).val();
			if ($('#' + detail_id).val() != '') {
				var text_pointer = $('#' + detail_id).val().length - 1;
				var text_pointer2 = $('#' + detail_id).val().lastIndexOf('.');
				if (text_pointer != text_pointer2) {
					var text1 = $('#' + detail_id).val() + '.';
				} else {
					var text1 = $('#' + detail_id).val();
				}
				var a = ' ' + text1;
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
			}
			$('#' + detail_id).val('');
			$('#' + detail_id).hide('fast');
		}
	});
	$('.pe_template_div').on("click", '.pe_normal', function() {
		if ($(this).is(':checked')) {
			var parent_id = $(this).attr("id");
			var x = parent_id.length - 1;
			parent_id = parent_id.slice(0,x);
			$("#" + parent_id + "_div").find('.pe_other:checkbox').each(function(){
				var parent_id = $(this).attr("id");
				$(this).prop('checked',false);
				var parts = parent_id.split('_');
				var parent_id_entry = parts[0] + '_' + parts[1];
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
				$(this).button('refresh');
			});
			$("#" + parent_id + "_div").find('.pe_detail_text').each(function(){
				var parent_id = $(this).attr("id");
				var parts = parent_id.split('_');
				var parent_id_entry = parts[0] + '_' + parts[1];
				var old = $("#" + parent_id_entry).val();
				if ($(this).val() != '') {
					var text_pointer = $(this).val().length - 1;
					var text_pointer2 = $(this).val().lastIndexOf('.');
					if (text_pointer != text_pointer2) {
						var text1 = $(this).val() + '.';
					} else {
						var text1 = $(this).val();
					}
					var a = ' ' + text1;
					var a1 = a + '  ';
					var c = old.replace(a1,'');
					c = c.replace(a, '');
					$("#" + parent_id_entry).val(c);
				}
				$(this).val('');
				$(this).hide();
			});
		}
	});
	$('.pe_template_div').on("click", '.pe_other', function() {
		if ($(this).is(':checked')) {
			var parent_id = $(this).attr("id");
			var x = parent_id.length - 1;
			parent_id = parent_id.slice(0,x);
			$("#" + parent_id + "_div").find('.pe_normal:checkbox').each(function(){
				var parent_id = $(this).attr("id");
				$(this).prop('checked',false);
				var parts = parent_id.split('_');
				var parent_id_entry = parts[0] + '_' + parts[1];
				var old = $("#" + parent_id_entry).val();
				var a = $(this).val();
				var a1 = a + '  ';
				var c = old.replace(a1,'');
				c = c.replace(a, '');
				$("#" + parent_id_entry).val(c);
				$(this).button('refresh');
			});
		}
	});
	$('.all_normal').on("click", function(){
		var a = $(this).is(':checked');
		var parent_id = $(this).attr("id");
		var n = parent_id.lastIndexOf('_');
		var parent_id_entry = parent_id.substring(0,n);
		if(a){
			$("#" + parent_id_entry + "_form").find("input.pe_normal:checkbox").each(function(){
				$(this).prop("checked",true);
			});
			updateTextArea(parent_id_entry);
		} else {
			$("#" + parent_id_entry).val('');
			$("#" + parent_id_entry + "_form").find('input.pe_normal:checkbox').each(function(){
				$(this).prop("checked",false);
			});
		}
		$("#" + parent_id_entry + '_form input[type="checkbox"]').button('refresh');
	});
	$('.all_normal1').on("click", function(){
		var a = $(this).is(':checked');
		var parent_id = $(this).attr("id");
		var parent_id_entry = parent_id.replace('normal','dialog');
		if(a){
			$("#" + parent_id_entry).find(".all_normal").each(function(){
				$(this).prop("checked",true);
				var parent_id1 = $(this).attr("id");
				var n1 = parent_id1.lastIndexOf('_');
				var parent_id_entry1 = parent_id1.substring(0,n1);
				$("#" + parent_id_entry1 + "_form").find("input.pe_normal:checkbox").each(function(){
					$(this).prop("checked",true);
				});
				updateTextArea(parent_id_entry1);
				$("#" + parent_id_entry1 + '_form input[type="checkbox"]').button('refresh');
			}).button('refresh');
			$("#" + parent_id_entry).find(".all_normal2").each(function(){
				$(this).prop("checked",true);
				var parent_id2 = $(this).attr("id");
				var parent_id_entry2 = parent_id2.replace('_normal1','');
				var old2 = $("#" + parent_id_entry2).val();
				var a2 = $(this).val();
				if (old2 != '') {
					var b2 = old2 + '  ' + a2;
				} else {
					var b2 = a2;
				}
				$("#" + parent_id_entry2).val(b2); 
			}).button('refresh');
		} else {
			$("#" + parent_id_entry).find(".all_normal").each(function(){
				$(this).prop("checked",false);
				var parent_id2 = $(this).attr("id");
				var n2 = parent_id2.lastIndexOf('_');
				var parent_id_entry2 = parent_id2.substring(0,n2);
				$("#" + parent_id_entry2).val('');
				$("#" + parent_id_entry2 + "_form").find('input.pe_normal:checkbox').each(function(){
					$(this).prop("checked",false);
				});
				$("#" + parent_id_entry2 + '_form input[type="checkbox"]').button('refresh');
			}).button('refresh');
			$("#" + parent_id_entry).find(".all_normal2").each(function(){
				$(this).prop("checked",true);
				var parent_id2 = $(this).attr("id");
				var parent_id_entry2 = parent_id2.replace('_normal1','');
				var old2 = $("#" + parent_id_entry2).val();
				var a2 = $(this).val();
				var a3 = '  ' + a2;
				var c2 = old2.replace(a3,'');
				c2 = c2.replace(a2, '');
				$("#" + parent_id_entry2).val(c2); 
			}).button('refresh');
		}
		$("#"+parent_id_entry).find('.pe_entry').each(function(){
			var parent_id1 = $(this).attr("id");
			if (!!$(this).val()) {
				$('#' + parent_id1 + '_h').html(noshdata.item_present);
			} else {
				$('#' + parent_id1 + '_h').html(noshdata.item_empty);
			}
		});
	});
	$(".all_normal2").on("click", function(){
		var parent_id = $(this).attr("id");
		var parent_id_entry = parent_id.replace('_normal1','');
		var old = $("#" + parent_id_entry).val();
		var a = $(this).val();
		if ($(this).is(':checked')) {
			if (old != '') {
				var b = old + '  ' + a;
			} else {
				var b = a;
			}
			$("#" + parent_id_entry).val(b); 
		} else {
			var a1 = '  ' + a;
			var c = old.replace(a1,'');
			c = c.replace(a, '');
			$("#" + parent_id_entry).val(c); 
		}
	});
	$("#pe_gen_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_gen_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_gen_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_gen_dialog');
				$("#pe_gen_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_eye_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_eye_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_eye_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_eye_dialog');
				$("#pe_eye_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_ent_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_ent_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_ent_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_ent_dialog');
				$("#pe_ent_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_neck_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_neck_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_neck_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_neck_dialog');
				$("#pe_neck_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_resp_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_resp_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_resp_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_resp_dialog');
				$("#pe_resp_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_cv_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_cv_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_cv_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_cv_dialog');
				$("#pe_cv_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_ch_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_ch_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_ch_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_ch_dialog');
				$("#pe_ch_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_gi_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_gi_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_gi_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_gi_dialog');
				$("#pe_gi_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_gu_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_gu_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_gu_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_gu_dialog');
				$("#pe_gu_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_lymph_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_lymph_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_lymph_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_lymph_dialog');
				$("#pe_lymph_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_ms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_ms_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_ms_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_ms_dialog');
				$("#pe_ms_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_neuro_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_neuro_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_neuro_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_neuro_dialog');
				$("#pe_neuro_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_psych_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_psych_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_psych_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_psych_dialog');
				$("#pe_psych_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_skin_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 575, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function() {
			pe_dialog_open('pe_skin_dialog');
		},
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_skin_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_skin_dialog');
				$("#pe_skin_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('.pe_tooltip').tooltip({
		items: ".pe_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			var idnum = $("#num_"+id1).val();
			var id2 = id1 + "/" + idnum;
			$.ajax({
				type: "POST",
				url: "ajaxencounter/tip-pe/" + id2,
				success: function(data) {
					elem.tooltip('option', 'content', data);
				},
			});
		}
	});
	$("#button_pe_gen").button().click(function() {
		$("#pe_gen_dialog").dialog('open');
		$("#pe_gen1").focus();
	});
	$("#button_pe_eye").button().click(function() {
		$("#pe_eye_dialog").dialog('open');
		$("#pe_eye1").focus();
	});
	$("#button_pe_ent").button().click(function() {
		$("#pe_ent_dialog").dialog('open');
		$("#pe_ent1").focus();
	});
	$("#button_pe_neck").button().click(function() {
		$("#pe_neck_dialog").dialog('open');
		$("#pe_neck1").focus();
	});
	$("#button_pe_resp").button().click(function() {
		$("#pe_resp_dialog").dialog('open');
		$("#pe_resp1").focus();
	});
	$("#button_pe_cv").button().click(function() {
		$("#pe_cv_dialog").dialog('open');
		$("#pe_cv1").focus();
	});
	$("#button_pe_ch").button().click(function() {
		$("#pe_ch_dialog").dialog('open');
		$("#pe_ch1").focus();
	});
	$("#button_pe_gi").button().click(function() {
		$("#pe_gi_dialog").dialog('open');
		$("#pe_gi1").focus();
	});
	$("#button_pe_gu").button().click(function() {
		$("#pe_gu_dialog").dialog('open');
		$("#pe_gu1").focus();
	});
	$("#button_pe_lymph").button().click(function() {
		$("#pe_lymph_dialog").dialog('open');
		$("#pe_lymph1").focus();
	});
	$("#button_pe_ms").button().click(function() {
		$("#pe_ms_dialog").dialog('open');
		$("#pe_ms1").focus();
	});
	$("#button_pe_neuro").button().click(function() {
		$("#pe_neuro_dialog").dialog('open');
		$("#pe_neuro1").focus();
	});
	$("#button_pe_psych").button().click(function() {
		$("#pe_psych_dialog").dialog('open');
		$("#pe_psych1").focus();
	});
	$("#button_pe_skin").button().click(function() {
		$("#pe_skin_dialog").dialog('open');
		$("#pe_skin1").focus();
	});
});
