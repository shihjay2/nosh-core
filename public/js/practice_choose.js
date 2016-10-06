$(document).ready(function() {
	$("#practice_npi_select").focus();
	$("#practice_submit_button").button();
	$("#npi").mask("9999999999");
	var type = $('#practice_npi_select').attr('type');
	if (type !== 'select') {
		$("#practice_npi_select").mask("9999999999");
	}
});
