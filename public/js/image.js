$(document).ready(function() {
	$('#wPaint').wPaint({
		path: 'js/'
	});
	$("#image_select").change(function() {
		var a = $("#image_select").val();
		if (a != '') {
			$('#wPaint').wPaint('image', a);
		}
	});
	$("#image_dialog").dialog({ 
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
		},
		buttons: {
			'Save': function() {
				$('#wPaint').wPaint('clear');
			},
			Cancel: function() {
				$('#wPaint').wPaint('clear');
			}
		},
		position: { my: 'center', at: 'center', of: '#maincontent' }
	});
});
