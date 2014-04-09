$(document).ready(function() {
	$('#wPaint').wPaint({
		path: noshdata.path,
		menuOrientation: 'horizontal',
		menuOffsetLeft: 0,
		menuOffsetTop: -78
	});
	$("#image_select").change(function() {
		var a = $("#image_select").val();
		if (a != '') {
			$('#wPaint').css({
				width: 140,
				height: 140
			}).wPaint('resize');
			$('.wPaint-menu-name-main').css({width:579});
			$('.wPaint-menu-name-text').css({width:182,left:0,top:42});
			$('.wPaint-menu-select').css({"overflow-y":"scroll"});
			//$('.wPaint-menu-name-main').parent().css({
				//width: 579,
				//left: 0,
				//top: -68
			//});
			//$('.wPaint-menu-name-text').parent().css({
				//width: 579,
				//left: 0,
				//top: -26
			//});
			$('#wPaint').wPaint('image', a);
			$("#image_eid").val(noshdata.eid);
			$("#image_pid").val(noshdata.pid);
			$("#image_dialog").dialog('open');
		}
	});
	$("#image_dialog_preview").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#image_select").removeOption(/./);
			$.ajax({
				url: "ajaxsearch/image-select",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#image_select").addOption({"":"Select an image to annotate."}, false);
					$("#image_select").addOption(data, false);
				}
			});
			loadimagepreview();
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	$("#image_encounter").click(function(){
		$("#image_dialog_preview").dialog('open');
	});
	$("#image_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 580, 
		width: 800, 
		draggable: false,
		resizable: false,
		closeOnEscape: false,
		dialogClass: "noclose",
		buttons: {
			'Save': function() {
				var imageData = $("#wPaint").wPaint("image");
				$("#image_data").val(imageData);
				var str = $("#image_form").serialize();
				$.ajax({
					url: "ajaxchart/image-save",
					type: "POST",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#wPaint').wPaint('clear');
						$('#image_form').clearForm();
						$("#image_dialog").dialog('close');
						loadimagepreview();
					}
				});
			},
			'Save As New Image': function() {
				var imageData = $("#wPaint").wPaint("image");
				$("#image_id").val('');
				$("#image_data").val(imageData);
				var str = $("#image_form").serialize();
				$.ajax({
					url: "ajaxchart/image-save",
					type: "POST",
					data: str,
					success: function(data){
						$.jGrowl(data);
						$('#wPaint').wPaint('clear');
						$('#image_form').clearForm();
						$("#image_dialog").dialog('close');
						loadimagepreview();
					}
				});
			},
			Cancel: function() {
				$('#wPaint').wPaint('clear');
				$('#image_form').clearForm();
				$("#image_dialog").dialog('close');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
	var myUpload5 = $("#add_photo").upload({
		action: 'photoupload',
		onComplete: function(data){
			loadimagepreview();
		}
	});
});
