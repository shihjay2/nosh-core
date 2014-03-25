$(document).ready(function() {
	function setup_progress(type) {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/setup-progress/" + type,
			dataType: "json",
			success: function(data){
				var a = $("#add_"+type+"_note").val();
				if (a != data.note) {
					$("#add_"+type+"_note").val(data.note);
					$("#add_"+type+"_progress").append(data.note);
				}
				$("#add_"+type+"_progress_num").html("<br>Number of records added: "+data.progress);
				if (data.complete == false) {
					setTimeout(setup_progress(type), 1000);
				}
			}
		});
	}
	$("#update_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 640, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#update_accordion").accordion({heightStyle: "content"});
			$("#add_icd_progress").hide();
			$("#add_med_progress").hide();
			$("#add_supplement_progress").hide();
			$("#add_cpt_progress").hide();
			$("#add_cvx_progress").hide();
			$("#add_npi_progress").hide();
		},
		position: { my: 'center', at: 'top', of: '#maincontent' }
	});
	$("#dashboard_update").click(function(){
		$("#update_dialog").dialog('open');
	});
	$("#add_icd9_file").click(function(){
		$("#add_icd_progress").show();
		setTimeout(setup_progress('icd'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/icd-update/9",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_icd10_file").click(function(){
		$("#add_icd_progress").show();
		setTimeout(setup_progress('icd'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/icd-update/10",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_med_file").click(function(){
		$("#add_med_progress").show();
		setTimeout(setup_progress('med'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/med-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_supplement_file").click(function(){
		$("#add_supplement_progress").show();
		setTimeout(setup_progress('supplement'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/supplements-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_cvx_file").click(function(){
		$("#add_cvx_progress").show();
		setTimeout(setup_progress('cvx'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/cvx-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_cpt_upload").click(function(){
		$("#add_cpt_progress").show();
		setTimeout(setup_progress('cpt'), 1000);
	});
	var myUpload2 = $("#add_cpt_upload").upload({
		action: 'cpt_update',
		onComplete: function(data){
			$.jGrowl(data);
		}
	});
	$("#add_npi_upload").click(function(){
		$("#add_npi_progress").show();
		setTimeout(setup_progress('npi'), 1000);
		$.ajax({
			type: "POST",
			url: "ajaxsetup/npi-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
});
