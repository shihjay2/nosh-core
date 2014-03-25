$(document).ready(function() {
	loadbuttons();
	function load_proc() {
		$.ajax({
			type: "POST",
			url: "ajaxencounter/get-proc",
			dataType: "json",
			success: function(data){
				$.each(data, function(key, value){
					$("#procedure_form :input[name='" + key + "']").val(value);
					var a = key.replace('proc', 'procedure');
					$("#"+a+"_old").val(value);
				});
				$("#procedure_template_status").html('');
			}
		});
	}
	load_proc();
	$("#procedure_type").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/procedure-type",
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
			$("#procedure_description").val(ui.item.procedure_description);
			$("#procedure_complications").val(ui.item.procedure_complications);
			$("#procedure_ebl").val(ui.item.procedure_ebl);
			$("#procedurelist_id").val(ui.item.procedurelist_id);
			$("#procedure_cpt").val(ui.item.cpt);
			$("#procedure_template_status").html('Template # ' + ui.item.procedurelist_id + ' in use.');
		}
	});
	$("#procedure_cpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "ajaxsearch/cpt",
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
		minLength: 3
	});
	$("#template_procedure").click(function(){
		var a = $("#procedure_type");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Type");
		if (bValid) {
			var str = $("#procedure_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "ajaxencounter/proc-template",
					data: str,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$('#procedure_type_reset').click(function(){
		$("#procedure_type").val('');
		$("#procedurelist_id").val('');
		$("#procedure_template_status").html('');
	});
	$('#procedure_cpt_reset').click(function(){
		$("#procedure_cpt").val('');
	});
	$('#procedure_description_reset').click(function(){
		$("#procedure_description").val('');
	});
	$('#procedure_complications_reset').click(function(){
		$("#procedure_complications").val('');
	});
	$('#procedure_ebl_reset').click(function(){
		$("#procedure_ebl").val('');
	});
	setInterval(proc_autosave, 10000);
});
