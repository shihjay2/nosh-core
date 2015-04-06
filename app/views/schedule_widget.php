<script type="text/javascript">
	noshdata.weekends = <?php echo $weekends;?>;
	noshdata.minTime = '<?php echo $minTime;?>';
	noshdata.maxTime = '<?php echo $maxTime;?>';
	noshdata.schedule_increment = '<?php echo $schedule_increment;?>';
	$(document).ready(function() {
		$("#schedule_dialog").dialog({ 
			bgiframe: true, 
			autoOpen: false, 
			height: 0.99 * $(window).height(), 
			width: 0.99 * $(window).width(), 
			draggable: false,
			resizable: false,
			closeOnEscape: false,
			dialogClass: "noclose",
			position: { my: 'center', at: 'center', of: window }
		});
		$("#provider_list2").removeOption(/./);
		$.ajax({
			url: "ajaxsearch/provider-select",
			dataType: "json",
			type: "POST",
			success: function(data){
				$("#provider_list2").addOption({"":"Select a provider."});
				$("#provider_list2").addOption(data, false);
				$("#schedule_dialog").dialog('open');
			}
		});
		$("#provider_list2").focus();
		$('#provider_list2').change(function() {
			var id = $('#provider_list2').val();
			if(id){
				$.ajax({
					type: "POST",
					url: "ajaxschedule/set-provider",
					data: "id=" + id,
					success: function(data){
						if( $.cookie('nosh-schedule') === undefined){
							var d = new Date();
							var y = d.getFullYear();
							var m = d.getMonth();
							var d = d.getDate();
							loadcalendar(y,m,d,'agendaWeek');
						} else {
							var n =  $.cookie('nosh-schedule').split(",");
							loadcalendar(n[0],n[1],n[2],n[3]);
						}
						$("#schedule_patient_step").show();
					}
				});
			} 
		});
	});
</script>
<div id="schedule_dialog" title="Schedule">
	<div id="provider_schedule1" style="overflow:auto;">
		<div style="width:235px;float:left;">
			<div id="providers_datepicker"></div><br><br>
			<div>Step 1: Choose a provider to schedule an appointment:<br></div>
			<div class="pure-form pure-form-stacked"><label for="provider_list2">Provider:</label><select id ="provider_list2" name="provider_list2" class="text"></select></div>
			<div id="schedule_patient_step" style="display:none">
				Step 2: Click on an open time slot on the schedule.<br><br>
				You will then be directed to the login screen.<br><br>
				You will need to be a patient and have portal access to be able to schedule an appointment.<br><br>
			</div>
		</div>
		<div style="width:620px;float:left;"><div id="providers_calendar" ></div></div>
	</div>
</div>
