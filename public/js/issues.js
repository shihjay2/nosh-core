$(document).ready(function() {
	$("#issues_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event,ui) {
			jQuery("#issues").jqGrid('GridUnload');
			jQuery("#issues").jqGrid({
				url:"ajaxcommon/issues",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Issue'],
				colModel:[
					{name:'issue_id',index:'issue_id',width:1,hidden:true},
					{name:'issue_date_active',index:'issue_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'issue',index:'issue',width:635}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#issues_pager'),
				sortname: 'issue_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption:"Issues",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#issues_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#issues_inactive").jqGrid('GridUnload');
			jQuery("#issues_inactive").jqGrid({
				url:"ajaxcommon/issues-inactive",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Date Active','Issue'],
				colModel:[
					{name:'issue_id',index:'issue_id',width:1,hidden:true},
					{name:'issue_date_active',index:'issue_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
					{name:'issue',index:'issue',width:635}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#issues_inactive_pager'),
				sortname: 'issue_date_active',
			 	viewrecords: true,
			 	sortorder: "desc",
			 	caption: "Inactive Issues",
			 	hiddengrid: true,
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#issues_inactive_pager',{search:false,edit:false,add:false,del:false});
		},
		close: function(event, ui) {
			$('#edit_issue_form').clearForm();
			$('#issues_pmh_header').hide();
			$('#issues_psh_header').hide();
			if (noshdata.group_id != '100') {
				menu_update('issues');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$(".issues_list").click(function() {
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_assessment_header').hide();
		$("#issues_list_dialog").dialog('open');
	});
	$("#dashboard_issues").click(function() {
		$('#issues_list_dialog').dialog('option', {
			height: $("#maincontent").height(),
			width: $("#maincontent").width(),
			position: { my: 'left top', at: 'left top', of: '#maincontent' }
		});
		$('#issues_pmh_header').hide();
		$('#issues_psh_header').hide();
		$('#issues_assessment_header').hide();
		$("#issues_list_dialog").dialog('open');
	});
	$("#issue_date_active").mask("99/99/9999");
	$("#issue_date_active").datepicker();
	$("#add_issue").click(function(){
		$('#edit_issue_form').clearForm();
		var currentDate = getCurrentDate();
		$('#issue_date_active').val(currentDate);
		$('#edit_issue_dialog').dialog('option', 'title', "Add Issue");
		$('#edit_issue_dialog').dialog('open');
		$("#issue").focus();
	});
	$("#edit_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			jQuery("#issues").GridToForm(item,"#edit_issue_form");
			var date = $('#issue_date_active').val();
			var edit_date = editDate(date);
			$('#issue_date_active').val(edit_date);
			$('#edit_issue_dialog').dialog('option', 'title', "Edit Issue");
			$('#edit_issue_dialog').dialog('open');
		} else {
			$.jGrowl("Please select issue to edit!")
		}
	});
	$("#inactivate_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var id = $("#issues").getCell(item,'issue_id');
			$.ajax({
				type: "POST",
				url: "ajaxchart/inactivate-issue",
				data: "issue_id=" + id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("issues");
					reload_grid("issues_inactive");
				}
			});
		} else {
			$.jGrowl("Please select issue to inactivate!")
		}
	});
	$("#delete_issue").click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this issue?')){
				var id = $("#issues").getCell(item,'issue_id');
				$.ajax({
					type: "POST",
					url: "ajaxchart/delete-issue",
					data: "issue_id=" + id,
					success: function(data){
						$.jGrowl(data);
						reload_grid("issues");
					}
				});
			}
		} else {
			$.jGrowl("Please select issue to delete!")
		}
	});
	$("#reactivate_issue").click(function(){
		var item = jQuery("#issues_inactive").getGridParam('selrow');
		if(item){
			var id = $("#issues_inactive").getCell(item,'issue_id');
			$.ajax({
				type: "POST",
				url: "ajaxchart/reactivate-issue",
				data: "issue_id=" + id,
				success: function(data){
					$.jGrowl(data);
					reload_grid("issues_inactive");
					reload_grid("issues");
				}
			});
		} else {
			$.jGrowl("Please select issue to inactivate!")
		}
	});
	$("#edit_issue_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		open: function (event, ui) {
			$("#issue").autocomplete({
				source: function (req, add){
					if (req.term in issue_cache){
						add(issue_cache[req.term]);
						return;
					}
					$.ajax({
						url: "ajaxsearch/icd",
						dataType: "json",
						type: "POST",
						data: req,
						success: function(data){
							if(data.response =='true'){
								issue_cache[req.term] = data.message;
								add(data.message);
							}
						}
					});
				},
				minLength: 3
			});
		},
		buttons: {
			'Save': function() {
				var bValid = true;
				$("#edit_issue_form").find("[required]").each(function() {
					var input_id = $(this).attr('id');
					var id1 = $("#" + input_id); 
					var text = $("label[for='" + input_id + "']").html();
					bValid = bValid && checkEmpty(id1, text);
				});
				if (bValid) {
					var str = $("#edit_issue_form").serialize();
					if(str){
						$.ajax({
							type: "POST",
							url: "ajaxchart/edit-issue",
							data: str,
							success: function(data){
								$.jGrowl(data);
								reload_grid("issues");
								reload_grid("nosh_issues");
								$('#edit_issue_form').clearForm();
								$('#edit_issue_dialog').dialog('close');
							}
						});
					} else {
						$.jGrowl("Please complete the form");
					}
				}
			},
			Cancel: function() {
				$('#edit_issue_form').clearForm();
				$('#edit_issue_dialog').dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#copy_oh_pmh_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var old = $("#oh_pmh").val();
					if(old){
						var pos = old.lastIndexOf('\n');
						if (pos == -1) {
							var old1 = old + '\n';
						} else {
							var a = old.slice(pos);
							if (a == '') {
								var old1 = old;
							} else {
								var old1 = old + '\n';
							}
						}
					} else {
						var old1 = '';
					}
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issues = issues1.slice(0, len1);
					$("#oh_pmh").val(old1+issues);
					$.jGrowl('All active issues copied!');
					$('#issues_pmh_header').hide();
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_oh_pmh_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#oh_pmh").val();
			if(old){
				var pos = old.lastIndexOf('\n');
				if (pos == -1) {
					var old1 = old + '\n';
				} else {
					var a = old.slice(pos);
					if (a == '') {
						var old1 = old;
					} else {
						var old1 = old + '\n';
					}
				}
			} else {
				var old1 = '';
			}
			$("#oh_pmh").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_oh_psh_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var old = $("#oh_psh").val();
					if(old){
						var pos = old.lastIndexOf('\n');
						if (pos == -1) {
							var old1 = old + '\n';
						} else {
							var a = old.slice(pos);
							if (a == '') {
								var old1 = old;
							} else {
								var old1 = old + '\n';
							}
						}
					} else {
						var old1 = '';
					}
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issues = issues1.slice(0, len1);
					$("#oh_psh").val(old1+issues);
					$.jGrowl('All active issues copied!');
					$('#issues_psh_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_oh_psh_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var old = $("#oh_psh").val();
			if(old){
				var pos = old.lastIndexOf('\n');
				if (pos == -1) {
					var old1 = old + '\n';
				} else {
					var a = old.slice(pos);
					if (a == '') {
						var old1 = old;
					} else {
						var old1 = old + '\n';
					}
				}
			} else {
				var old1 = '';
			}
			$("#oh_psh").val(old1+issue);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_lab_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_lab_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_lab_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_lab_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_lab_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_lab_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_lab_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_rad_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_rad_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_rad_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_rad_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_rad_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_rad_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_rad_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_cp_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_cp_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_cp_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_cp_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_cp_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_cp_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_cp_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$("#copy_ref_all_issues").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "ajaxencounter/copy-issues",
			success: function(data){
				if (data == 'No') {
					$.jGrowl('No active issues!');
					$('#issues_header').html('');
					$("#issues_list_dialog").dialog('close');
				} else {
					var terms = split($("#messages_ref_codes").val());
					var issues1 = data.replace(/,/g,"\n");
					var len = issues1.length;
					var len1 = len - 1;
					var issue = issues1.slice(0, len1);
					terms.pop();
					terms.push( issue );
					terms.push( "" );
					var new_terms = terms.join( "\n" );
					$("#messages_ref_codes").val(new_terms);
					$.jGrowl('All active issues copied!');
					$('#issues_ref_header').hide('fast');
					$("#issues_list_dialog").dialog('close');
				}
			}
		});
	});
	$("#copy_ref_one_issue").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var item = jQuery("#issues").getGridParam('selrow');
		if(item){
			var issue = jQuery("#issues").getCell(item,'issue');
			var terms = split($("#messages_ref_codes").val());
			terms.pop();
			terms.push( issue );
			terms.push( "" );
			var new_terms = terms.join( "\n" );
			$("#messages_ref_codes").val(new_terms);
			$.jGrowl('Issue copied!');
		} else {
			$.jGrowl("Please select issue to copy!");
		}
	});
	$(".copy_assessment_issue_class").button({icons: {primary: "ui-icon-arrowthickstop-1-s"}}).click(function(){
		var id = $(this).attr('id');
		var id_num = id.replace("copy_assessment_issue_", '');
		if (id_num != '9' || id_num != '10') {
			var item = jQuery("#issues").getGridParam('selrow');
			if(item){
				var issue = jQuery("#issues").getCell(item,'issue');
				var pos = issue.indexOf('[');
				if (pos == -1) {
					$.jGrowl('Please select issue to copy!');
				} else {
					var icd1 = issue.slice(pos);
					var icd2 = icd1.replace("[", "");
					var icd = icd2.replace("]", "");
					$("#assessment_icd" + id_num).val(icd);
					$("#assessment_" + id_num).val(issue);
					var label = '<strong>Diagnosis #' + id_num + ':</strong> ' + issue;
					$("#assessment_icd" + id_num + "_div").html(label);
					$("#assessment_icd" + id_num + "_div_button").show();
					$.jGrowl('Issue copied to Diagnosis #' + id_num + '!');
				}
			} else {
				$.jGrowl("Please select issue to copy!");
			}
		} else {
			var item = jQuery("#issues").getGridParam('selrow');
			if(item){
				var issue = jQuery("#issues").getCell(item,'issue');
				if (id_num == '9') {
					var old = $("#assessment_other").val();
				} else {
					var old = $("#assessment_ddx").val();
				}
				if(old){
					var pos = old.lastIndexOf('\n');
					if (pos == -1) {
						var old1 = old + '\n';
					} else {
						var a = old.slice(pos);
						if (a == '') {
							var old1 = old;
						} else {
							var old1 = old + '\n';
						}
					}
				} else {
					var old1 = '';
				}
				if (id_num == '9') {
					$("#assessment_other").val(old1+issue);
				} else {
					$("#assessment_ddx").val(old1+issue);
				}
				$.jGrowl('Issue copied!');
			} else {
				$.jGrowl("Please select issue to copy!");
			}
		}
	});
});
