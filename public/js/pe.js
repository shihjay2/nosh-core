$(document).ready(function() {
	function pe_clear_dialog_form(dialog_id) {
		$("#"+dialog_id).find('.pe_entry').each(function(){
			var a = $(this).val();
			var id = $(this).attr('id');
			var b = $("#"+id+"_old").val();
			if (a != b) {
				$(this).val(b);
			}
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
				if (i !== 0) {
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
		if (a !== '') {
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
						$("." + id + "_div").css("padding","5px");
						$('.pe_template_div select').addOption({'':'Select option'},true);
					} else {
						$('#'+id+'_form').dform(data);
					}
					pe_form_load();
				}
			});
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
	$("#pe_constitutional_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 575,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_constitutional_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_constitutional_dialog');
				$("#pe_constitutional_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#pe_mental_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 575,
		width: 800,
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				pe_dialog_save('pe_mental_dialog');
			},
			Cancel: function() {
				pe_clear_dialog_form('pe_mental_dialog');
				$("#pe_mental_dialog").dialog('close');
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
	$("#button_pe_constitutional").button().click(function() {
		$("#pe_constitutional_dialog").dialog('open');
		$("#pe_constitutional1").focus();
	});
	$("#button_pe_mental").button().click(function() {
		$("#pe_mental_dialog").dialog('open');
		$("#pe_mental1").focus();
	});
	$("#pe_entire_normal").click(function() {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/all-normal/pe/all/0",
			success: function(data){
				$.jGrowl('All normal values set!');
				check_pe_status();
				pe_get_data();
			}
		});
	});
	swipe();
});
