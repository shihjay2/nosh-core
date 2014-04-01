$(document).ready(function() {
	$(".oh_buttonset").buttonset();
	loadbuttons();
	$("#copy_oh").click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-oh/oh",
			dataType: "json",
			success: function(data){
				if(data.callback == 'Items copied from last encounter!'){
					$.jGrowl(data.callback);
					$("#oh_pmh").val(data.oh_pmh);
					$("#oh_psh").val(data.oh_psh);
					$("#oh_fh").val(data.oh_fh);
				} else {
					$.jGrowl(data.callback);
					$("#oh_pmh").val('');
					$("#oh_psh").val('');
					$("#oh_fh").val('');
				}
			}
		});
	});
	$('#oh_pmh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-pf-template/" + a,
			success: function(data){
				var old = $("#oh_pmh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_pmh").val(b);
			}
		});
	});
	$('#oh_psh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-pf-template/" + a,
			success: function(data){
				var old = $("#oh_psh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_psh").val(b);
			}
		});
	});
	$('#oh_fh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-pf-template/" + a,
			success: function(data){
				var old = $("#oh_fh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_fh").val(b);
			}
		});
	});
	$('#oh_sh_pf_template').change(function(){
		var a = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-pf-template/" + a,
			success: function(data){
				var old = $("#oh_sh").val();
				if (old != '') {
					var b = old + '\n\n' + data;
				} else {
					var b = data;
				}
				$("#oh_sh").val(b);
			}
		});
	});
	$("#oh_pmh").focus().autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/icd9",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}
				}
			});
		},
		search: function() {
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			var terms = split( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( "\n" );
			return false;
		}
	});
	$('#oh_pmh_reset').click(function(){
		$("#oh_pmh").val('');
	});
	$('#oh_pmh_issues').click(function() {
		$('#issues_pmh_header').show();
		$('#issues_psh_header').hide();
		$('#issues_lab_header').hide();
		$('#issues_rad_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_ref_header').hide();
		$('#issues_assessment_header').hide();
		$('#edit_issue_form').hide();
		$("#issues_list_dialog").dialog('open');
	});
	$('#oh_psh_reset').click(function(){
		$("#oh_psh").val('');
	});
	$('#oh_psh_issues').button().click(function() {
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').show();
		$('#issues_lab_header').hide();
		$('#issues_rad_header').hide();
		$('#issues_cp_header').hide();
		$('#issues_ref_header').hide();
		$('#issues_assessment_header').hide();
		$('#edit_issue_form').hide();
		$("#issues_list_dialog").dialog('open');
	});
	$("#oh_psh").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/icd9",
				dataType: "json",
				type: "POST",
				data: "term=" + extractLast(req.term),
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}
				}
			});
		},
		search: function() {
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			return false;
		},
		select: function(event, ui){
			var terms = split( this.value );
			terms.pop();
			terms.push( ui.item.value );
			terms.push( "" );
			this.value = terms.join( "\n" );
			return false;
		}
	});
	$('#oh_fh_reset').click(function(){
		$("#oh_fh").val('');
	});
	$('#oh_fh_helper_dialog').dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 200, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$("#fh_icd").autocomplete({
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
					$(this).end().val(ui.item.value);
				}
			});
		},
		buttons: {
			'Import': function() {
				var icd = $("#fh_icd").val();
				var fm = $("#fh_fm").val();
				if (icd != '' || fh != '') {
					var old = $("#oh_fh").val().trim();
					var full = icd + ' - ' + fm;
					$("#oh_fh").val(old+'\n'+full);
					$("#fh_icd").val('');
					$("#fh_fm").val('');
				} else {
					$.jGrowl("Empty field!  Try again.");
				}
			},
			'Done': function() {
				$("#fh_icd").val('');
				$("#fh_fm").val('');
				$('#oh_fh_helper_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$('#oh_fh_icd').click(function(){
		$('#oh_fh_helper_dialog').dialog('open');
	});
	$("#oh_sh_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-oh",
				dataType: "json",
				success: function(data){
					$('#oh_sh').val(data.response.oh_sh);
				}
			});
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/demographics",
				dataType: "json",
				success: function(data){
					$("#oh_sh_marital_status").val(data.marital_status);
					$("#oh_sh_partner_name").val(data.partner_name);
					$("#oh_sh_marital_status_old").val(data.marital_status);
					$("#oh_sh_partner_name_old").val(data.partner_name);
				}
			});
			$("#oh_sh").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#oh_sh_dialog_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/oh-save1/sh",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#oh_sh_dialog_form").clearForm();
						$('#oh_sh_form').clearForm();
						$("#oh_sh_dialog").dialog('close');
						check_oh_status();
					}
				});
			},
			'Copy From Most Recent Encounter': function() {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/copy-oh/sh",
					dataType: "json",
					success: function(data){
						if(data.callback == 'Items copied from last encounter!'){
							$.jGrowl(data.callback);
							$("#oh_sh").val(data.oh_sh);
						} else {
							$.jGrowl(data.callback);
							$("#oh_sh").val('');
						}
					}
				});
			}, 
			Cancel: function() {
				$("#oh_sh_dialog_form").clearForm();
				$("#oh_sh_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_oh_sh").click(function() {
		$("#oh_sh_dialog").dialog('open');
	});
	$('.oh_tooltip').tooltip({
		items: ".oh_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		position: { my: "right+15 bottom", at: "left top", collision: "flipfit" },
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[1] + "_" + parts[2];
			$.ajax({
				type: "POST",
				url: "ajaxencounter/tip-oh/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "right+15 bottom", at: "left top", collision: "flipfit" });
				},
			});
		}
	});
	$('#oh_sh_reset').click(function(){
		$("#oh_sh").val('');
	});
	$('#save_oh_sh_form').click(function(){
		var old = $("#oh_sh").val();
		var old1 = old.trim();
		var a = $("#sh1").val();
		var b = $("#sh2").val();
		var c = $("#sh3").val();
		var d = $("#oh_sh_marital_status").val();
		var d0 = $("#oh_sh_marital_status_old").val();
		var e = $("#oh_sh_partner_name").val();
		var e0 = $("#oh_sh_partner_name").val();
		var f = $("#sh4").val();
		var g = $("#sh5").val();
		var h = $("#sh6").val();
		var i = $("#sh7").val();
		var j = $("#sh8").val();
		var k = $("input[name='sh9']:checked").val();
		var l = $("input[name='sh10']:checked").val();
		var m = $("input[name='sh11']:checked").val();
		if(a){
			var a1 = 'Family members in the household: ' + a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Children: ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Pets: ' + c + '\n';
		} else {
			var c1 = '';
		}
		if(d){
			var d1 = 'Marital status: ' + d + '\n';
		} else {
			var d1 = '';
		}
		if(e){
			var e1 = 'Partner name: ' + e + '\n';
		} else {
			var e1 = '';
		}
		if(f){
			var f1 = 'Diet: ' + f + '\n';
		} else {
			var f1 = '';
		}
		if(g){
			var g1 = 'Exercise: ' + g + '\n';
		} else {
			var g1 = '';
		}
		if(h){
			var h1 = 'Sleep: ' + h + '\n';
		} else {
			var h1 = '';
		}
		if(i){
			var i1 = 'Hobbies: ' + i + '\n';
		} else {
			var i1 = '';
		}
		if(j){
			var j1 = 'Child care arrangements: ' + j + '\n';
		} else {
			var j1 = '';
		}
		if(k){
			var k1 = k + '\n';
		} else {
			var k1 = '';
		}
		if(l){
			var l1 = l + '\n';
		} else {
			var l1 = '';
		}
		if(m){
			var m1 = m + '\n';
		} else {
			var m1 = '';
		}
		var full = d1+e1+a1+b1+c1+f1+g1+h1+i1+j1+k1+l1+m1;
		var full1 = full.trim();
		if (old1 != '') {
			var n = old1+'\n'+full1+'\n';
		} else {
			var n = full1+'\n';
		}
		var o = n.length;
		$("#oh_sh").val(n).caret(o);
		if(d != d0 || e != e0) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/sh",
				data: "marital_status=" + d + "&partner_name=" + e,
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
		var sh9_y = $('#sh9_y').attr('checked');
		var sh9_n = $('#sh9_n').attr('checked');
		if(sh9_y){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/sex",
				data: "status=yes",
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
		if(sh9_n){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/sex",
				data: "status=no",
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
	});
	$('#cancel_oh_sh_form').click(function(){
		$('#oh_sh_form').clearForm();
	});
	$("#oh_sh_marital_status").addOption(marital, false);
	$("#button_oh_meds").click(function() {
		$("#oh_meds_header").show();
		$("#medications_list_dialog").dialog('open');
		$("#oh_meds").focus();
	});
	$("#button_oh_supplements").click(function() {
		$("#oh_supplements_header").show();
		$("#supplements_list_dialog").dialog('open');
		$("#supplement_origin_orders").val('N');
		$("#oh_supplements").focus();
	});
	$("#button_oh_allergies").click(function() {
		$("#save_oh_allergies").show();
		$("#allergies_header").show();
		$("#allergies_list_dialog").dialog('open');
		$("#oh_allergies").focus();
	});
	$("#oh_etoh_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-oh",
				dataType: "json",
				success: function(data){
					$('#oh_etoh').val(data.response.oh_etoh);
				}
			});
			$("#oh_etoh").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#oh_etoh_dialog_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/oh-save1/etoh",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#oh_etoh_dialog_form").clearForm();
						$('#oh_etoh_form').clearForm();
						$("#oh_etoh_dialog").dialog('close');
						check_oh_status();
					}
				});
			},
			'Copy From Most Recent Encounter': function() {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/copy-oh/etoh",
					dataType: "json",
					success: function(data){
						if(data.callback == 'Items copied from last encounter!'){
							$.jGrowl(data.callback);
							$("#oh_etoh").val(data.oh_sh);
						} else {
							$.jGrowl(data.callback);
							$("#oh_etoh").val('');
						}
					}
				});
			}, 
			Cancel: function() {
				$("#oh_etoh_dialog_form").clearForm();
				$("#oh_etoh_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_oh_etoh").click(function() {
		$("#oh_etoh_dialog").dialog('open');
	});
	$('#oh_etoh_reset').click(function(){
		$("#oh_etoh").val('');
	});
	$("input[name='oh_etoh_select']").click(function(){
		var a = $('#oh_etoh_y').prop('checked');
		if(a){
			$('#oh_etoh_input').show();
			$('#oh_etoh_text').focus();
		} else {
			$('#oh_etoh_input').hide();
			$('#oh_etoh_text').val('');
		}
	});
	$('#save_oh_etoh_form').click(function(){
		var old = $("#oh_etoh").val();
		var old1 = old.trim();
		var a = $("input[name='oh_etoh_select']:checked").val();
		var a0 = $("#oh_etoh_text").val();
		if(a){
			var a1 = a + a0;
		} else {
			var a1 = '';
		}
		if (old1 != '') {
			var b = old1+'\n'+a1+'\n';
		} else {
			var b = a1+'\n';
		}
		var c = b.length;
		$("#oh_etoh").val(b).caret(c);
	});
	$('#cancel_oh_etoh_form').click(function(){
		$('#oh_etoh_form').clearForm();
	});
	$("#oh_tobacco_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-oh",
				dataType: "json",
				success: function(data){
					$('#oh_tobacco').val(data.response.oh_tobacco);
				}
			});
			$("#oh_tobacco").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#oh_tobacco_dialog_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/oh-save1/tobacco",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#oh_tobacco_dialog_form").clearForm();
						$('#oh_tobacco_form').clearForm();
						$("#oh_tobacco_dialog").dialog('close');
						check_oh_status();
					}
				});
			},
			'Copy From Most Recent Encounter': function() {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/copy-oh/tobacco",
					dataType: "json",
					success: function(data){
						if(data.callback == 'Items copied from last encounter!'){
							$.jGrowl(data.callback);
							$("#oh_tobacco").val(data.oh_sh);
						} else {
							$.jGrowl(data.callback);
							$("#oh_tobacco").val('');
						}
					}
				});
			}, 
			Cancel: function() {
				$("#oh_tobacco_dialog_form").clearForm();
				$("#oh_tobacco_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_oh_tobacco").click(function() {
		$("#oh_tobacco_dialog").dialog('open');
	});
	$('#oh_tobacco_reset').click(function(){
		$("#oh_tobacco").val('');
	});
	$("input[name='oh_tobacco_select']").click(function(){
		var a = $('#oh_tobacco_y').prop('checked');
		if(a){
			$('#oh_tobacco_input').show();
			$('#oh_tobacco_text').focus();
		} else {
			$('#oh_tobacco_input').hide();
			$('#oh_tobacco_text').val('');
		}
	});
	$('#save_oh_tobacco_form').click(function(){
		var old = $("#oh_tobacco").val();
		var old1 = old.trim();
		var a = $("input[name='oh_tobacco_select']:checked").val();
		var a0 = $("#oh_tobacco_text").val();
		if(a){
			var a1 = a + a0;
		} else {
			var a1 = '';
		}
		if (old1 != '') {
			var b = old1+'\n'+a1+'\n';
		} else {
			var b = a1+'\n';
		}
		var c = b.length;
		$("#oh_tobacco").val(b).caret(c);
		var tobacco_y = $('#oh_tobacco_y').prop('checked');
		var tobacco_n = $('#oh_tobacco_n').prop('checked');
		if(tobacco_y){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/tobacco",
				data: "status=yes",
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
		if(tobacco_n){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/tobacco",
				data: "status=no",
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
	});
	$('#cancel_oh_tobacco_form').click(function(){
		$('#oh_tobacco_form').clearForm();
	});
	$("#oh_drugs_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-oh",
				dataType: "json",
				success: function(data){
					$('#oh_drugs').val(data.response.oh_drugs);
				}
			});
			$("#oh_drugs").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#oh_drugs_dialog_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/oh-save1/drugs",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#oh_drugs_dialog_form").clearForm();
						$('#oh_drugs_form').clearForm();
						$("#oh_drugs_dialog").dialog('close');
						check_oh_status();
					}
				});
			},
			'Copy From Most Recent Encounter': function() {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/copy-oh/drugs",
					dataType: "json",
					success: function(data){
						if(data.callback == 'Items copied from last encounter!'){
							$.jGrowl(data.callback);
							$("#oh_drugs").val(data.oh_sh);
						} else {
							$.jGrowl(data.callback);
							$("#oh_drugs").val('');
						}
					}
				});
			}, 
			Cancel: function() {
				$("#oh_drugs_dialog_form").clearForm();
				$("#oh_drugs_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_oh_drugs").click(function() {
		$("#oh_drugs_dialog").dialog('open');
	});
	$('#oh_drugs_reset').click(function(){
		$("#oh_drugs").val('');
	});
	$("input[name='oh_drugs_select']").click(function(){
		var a = $('#oh_drugs_y').prop('checked');
		if(a){
			$('#oh_drugs_input').show();
			$('#oh_drugs_text').focus();
		} else {
			$('#oh_drugs_input').hide();
			$('#oh_drugs_text').val('');
			$("#oh_drugs_text1").val('');
		}
	});
	$('#save_oh_drugs_form').click(function(){
		var old = $("#oh_drugs").val();
		var old1 = old.trim();
		var a = $("input[name='oh_drugs_select']:checked").val();
		if(a){
			if (a == 'No illicit drug use.') {
				var a1 = a;
			} else {
				var a0 = $("#oh_drugs_text").val();
				var a2 = $("#oh_drugs_text1").val();
				var a1 = a + a0 + '\nFrequency of drug use: ' + a2;
				$('#oh_drugs_input').hide();
				$('#oh_drugs_text').val('');
				$("#oh_drugs_text1").val('');
				$("input[name='oh_drugs_select']").each(function(){
					$(this).prop('checked', false);
				});
				$('#oh_drugs_form input[type="radio"]').button('refresh');
			}
		} else {
			var a1 = '';
			$('#oh_drugs_input').hide();
		}
		if (old1 != '') {
			var b = old1+'\n'+a1+'\n';
		} else {
			var b = a1+'\n';
		}
		var c = b.length;
		$("#oh_drugs").val(b).caret(c);
	});
	$('#cancel_oh_drugs_form').click(function(){
		$('#oh_drugs_form').clearForm();
	});
	$("#oh_employment_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function(event, ui) {
			$.ajax({
				type: "POST",
				url: "ajaxencounter/get-oh",
				dataType: "json",
				success: function(data){
					$('#oh_employment').val(data.response.oh_employment);
				}
			});
			$("#oh_employment").focus();
		},
		buttons: {
			'Save': function() {
				var str = $("#oh_employment_dialog_form").serialize();
				$.ajax({
					type: "POST",
					url: "ajaxencounter/oh-save1/employment",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$("#oh_employment_dialog_form").clearForm();
						$('#oh_employment_form').clearForm();
						$("#oh_employment_dialog").dialog('close');
						check_oh_status();
					}
				});
			},
			'Copy From Most Recent Encounter': function() {
				$.ajax({
					type: "POST",
					url: "ajaxencounter/copy-oh/employment",
					dataType: "json",
					success: function(data){
						if(data.callback == 'Items copied from last encounter!'){
							$.jGrowl(data.callback);
							$("#oh_employment").val(data.oh_sh);
						} else {
							$.jGrowl(data.callback);
							$("#oh_employment").val('');
						}
					}
				});
			}, 
			Cancel: function() {
				$("#oh_employment_dialog_form").clearForm();
				$("#oh_employment_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#button_oh_employment").click(function() {
		$("#oh_employment_dialog").dialog('open');
	});
	$('#oh_employment_reset').click(function(){
		$("#oh_employment").val('');
	});
	$("input[name='oh_employment_select']").click(function(){
		var a = $('#oh_employment_1').prop('checked');
		if(a){
			$('#oh_employment_input').show();
			$.ajax({
				type: "POST",
				url: "ajaxdashboard/demographics",
				dataType: "json",
				success: function(data){
					$("#oh_employment_employer").val(data.employer);
					$("#oh_employment_employer_old").val(data.employer);
				}
			});
			$("oh_employment_employer").focus();
		} else {
			$('#oh_employment_input').hide();
			$("#oh_employment_employer").val('');
			$("#oh_employment_text").val('');
		}
	});
	$('#save_oh_employment_form').click(function(){
		var old = $("#oh_employment").val();
		var old1 = old.trim();
		var a = $("input[name='oh_employment_select']:checked").val();
		var b = $("#oh_employment_text").val();
		var c = $("#oh_employment_employer").val();
		var c0 = $("#oh_employment_employer_old").val();
		if(a){
			var a1 = a + '\n';
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'Employment field: ' + b + '\n';
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'Employer: ' + c + '\n';
		} else {
			var c1 = '';
		}
		var full = a1+b1+c1;
		var full1 = full.trim();
		if (old1 != '') {
			var d = old1+'\n'+full1+'\n';
		} else {
			var d = full1+'\n';
		}
		var e = d.length;
		$("#oh_employment").val(d).caret(e);
		if(c != c0){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/edit-demographics/employer",
				data: "employer=" + c,
				success: function(data){
					$.jGrowl(data);
				}
			});
		}
	});
	$('#cancel_oh_employment_form').click(function(){
		$('#oh_employment_form').clearForm();
	});
	setInterval(oh_autosave, 10000);
});
