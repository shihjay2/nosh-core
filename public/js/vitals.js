$(document).ready(function() {
	loadbuttons();
	$.ajax({
		type: "POST",
		url: "ajaxencounter/get-vitals",
		dataType: "json",
		success: function(data){
			if (data == '') {
				$("#vitals_temp_method_old").val('Oral');
				$("#vitals_bp_position_old").val('Sitting');
			} else {
				$.each(data, function(key, value){
					$("#vitals_form :input[name='" + key + "']").val(value);
					$("#vitals_"+key+"_old").val(value);
				});
			}
		}
	});
	$("#vitals_temp_method").addOption({"Oral":"Oral","Axillary":"Axillary","Temporal":"Temporal","Rectal":"Rectal"}, false);
	$("#vitals_bp_position").addOption({"Sitting":"Sitting","Standing":"Standing","Supine":"Supinee"}, false);
	jQuery("#vitals_list").jqGrid({
		url:"ajaxencounter/vitals-list",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Weight','Height','HC','BMI','Temp','SBP','DBP','Pulse','Resp','O2 Sat','Notes'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'vitals_date',index:'vitals_date',width:100},
			{name:'weight',index:'weight',width:50},
			{name:'height',index:'height',width:50},
			{name:'headcircumference',index:'headcircumference',width:50},
			{name:'BMI',index:'BMI',width:50},
			{name:'temp',index:'temp',width:50},
			{name:'bp_systolic',index:'bp_systolic',width:50},
			{name:'bp_diastolic',index:'bp_diastolic',width:50},
			{name:'pulse',index:'pulse',width:50},
			{name:'respirations',index:'respirations',width:50},
			{name:'o2_sat',index:'o2_sat',width:50},
			{name:'vitals_other',index:'vitals_other',width:100}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#vitals_list_pager'),
		sortname: 'eid',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Past Vital Signs",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
	if (parseInt(noshdata.agealldays) > 6574.5) {
		jQuery("#vitals_list").jqGrid('hideCol','headcircumference');
	}
	$("#vitals_height").blur(function(){
		var w = $("#vitals_weight").val();
		var h = $("#vitals_height").val();
		if (w != '') {
			if ((w >= 500) || (h >= 120)) {
				alert("Invalid data.  Please check and re-enter!");
			} else {
				var bmi = (Math.round((w * 703) / (h * h)));
				$("#vitals_BMI").val(bmi);
				if (parseInt(noshdata.agealldays) <= 6574.5) {
					var text = bmi;
				} else {
					if (bmi < 19) {
						var a = " - Underweight";
					}
					if (bmi >=19 && bmi <=25) {
						var a = " - Desirable";
					}
					if (bmi >=26 && bmi <=29) {
						var a = " - Prone to health risks";
					}
					if (bmi >=30 && bmi <=40) {
						var a = " - Obese";
					}
					if (bmi >40){
						var a = " - Morbidly obese";
					}
					var text = bmi + a;
				}
				$("#vitals_bmi_display").html(text);
			}
		}
	});
	$("#vitals_temp").blur(function(){
		var a = $("#vitals_temp").val();
		if (a != '') {
			if (a > 100.4) {
				$("#vitals_temp").css("color","red");
			} else {
				$("#vitals_temp").css("color","black");
			}
			if (a > 106 || a < 93) {
				$.jGrowl('Invalid temperature value!');
				$("#vitals_temp").val('');
				$("#vitals_temp").css("color","black");
			}
		}
	});
	$("#vitals_bp_systolic").blur(function(){
		var a = $("#vitals_bp_systolic").val();
		if (a != '') {
			if (a > 140 || a < 80) {
				$("#vitals_bp_systolic").css("color","red");
			} else {
				$("#vitals_bp_systolic").css("color","black");
			}
			if (a > 250 || a < 50) {
				$.jGrowl('Invalid value!');
				$("#vitals_bp_systolic").val('');
				$("#vitals_bp_systolic").css("color","black");
			}
		}
	});
	$("#vitals_bp_diastolic").blur(function(){
		var a = $("#vitals_bp_diastolic").val();
		if (a != '') {
			if (a > 90 || a < 50) {
				$("#vitals_bp_diastolic").css("color","red");
			} else {
				$("#vitals_bp_diastolic").css("color","black");
			}
			if (a > 200 || a < 30) {
				$.jGrowl('Invalid value!');
				$("#vitals_bp_diastolic").val('');
				$("#vitals_bp_diastolic").css("color","black");
			}
		}
	});
	$("#vitals_pulse").blur(function(){
		var a = $("#vitals_pulse").val();
		if (a != '') {
			if (a > 140 || a < 50) {
				$("#vitals_pulse").css("color","red");
			} else {
				$("#vitals_pulse").css("color","black");
			}
			if (a > 250 || a < 30) {
				$.jGrowl('Invalid value!');
				$("#vitals_pulse").val('');
				$("#vitals_pulse").css("color","black");
			}
		}
	});
	$("#vitals_respirations").blur(function(){
		var a = $("#vitals_respirations").val();
		if (a != '') {
			if (a > 35 || a < 10) {
				$("#vitals_respirations").css("color","red");
			} else {
				$("#vitals_respirations").css("color","black");
			}
			if (a > 50 || a < 5) {
				$.jGrowl('Invalid value!');
				$("#vitals_respirations").val('');
				$("#vitals_respirations").css("color","black");
			}
		}
	});
	$("#vitals_o2_sat").blur(function(){
		var a = $("#vitals_o2_sat").val();
		if (a != '') {
			if (a < 90) {
				$("#vitals_o2_sat").css("color","red");
			} else {
				$("#vitals_o2_sat").css("color","black");
			}
			if (a > 100 || a < 50) {
				$.jGrowl('Invalid value!');
				$("#vitals_o2_sat").val('');
				$("#vitals_o2_sat").css("color","black");
			}
		}
	});
	//setInterval(vitals_autosave, 10000);
});
