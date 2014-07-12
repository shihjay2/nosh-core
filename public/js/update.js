$(document).ready(function() {
	function setup_progress(type) {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/setup-progress",
			dataType: "json",
			success: function(data){
				var a = $("#add_"+type+"_note").val();
				if (a != data.note) {
					$("#add_"+type+"_note").val(data.note);
					$("#add_"+type+"_progress").append(data.note);
				}
				$("#add_"+type+"_progress_num").html("<br>Number of records added: "+data.progress);
				if (data.complete == 'false') {
					setTimeout(setup_progress(type), 1000);
				}
			}
		});
	}
	function setup_reset(type) {
		$.ajax({
			type: "POST",
			url: "ajaxsetup/setup-reset",
			success: function(data){
				setTimeout(setup_progress(type), 7000);
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
		setup_reset('icd');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/icd-update/9",
			success: function(data){
				$.jGrowl(data);
				$(document).idleTimeout({
					inactivity: 3600000,
					noconfirm: 10000,
					alive_url: noshdata.error,
					redirect_url: noshdata.logout_url,
					logout_url: noshdata.logout_url,
					sessionAlive: false
				});
			}
		});
		$(document).idleTimeout({
			inactivity: 28800000,
			noconfirm: 10000,
			alive_url: noshdata.error,
			redirect_url: noshdata.logout_url,
			logout_url: noshdata.logout_url,
			sessionAlive: false
		});
	});
	$("#add_icd10_file").click(function(){
		$("#add_icd_progress").html('').show();
		setup_reset('icd');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/icd-update/10",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_med_file").click(function(){
		$("#add_med_progress").html('').show();
		setup_reset('med');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/med-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_supplement_file").click(function(){
		$("#add_supplement_progress").html('').show();
		setup_reset('supplement');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/supplements-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_cvx_file").click(function(){
		$("#add_cvx_progress").html('').show();
		setup_reset('cvx');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/cvx-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_cpt_upload").click(function(){
		$("#add_cpt_progress").html('').show();
		setup_reset('cpt');
	});
	var myUpload2 = $("#add_cpt_upload").upload({
		action: 'cpt_update',
		onComplete: function(data){
			$("#add_cpt_upload").parent().find('input').val('');
			$.jGrowl(data);
		}
	});
	$("#add_npi_upload").click(function(){
		$("#add_npi_progress").html('').show();
		setup_reset('npi');
		$.ajax({
			type: "POST",
			url: "ajaxsetup/npi-update",
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
});
