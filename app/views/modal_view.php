<?php if ($addendum == true) {?>
	<button type="button" id="add_addendum">Make Addendum</button><br>
	<hr class="ui-state-default"/><h4>Previous versions:</h4>
	<div id="previous_versions"></div>
	<hr class="ui-state-default"/>
<?php }?>
<div id="encounter_text">
	<table style="width:700" cellspacing="10">
		<tr>
			<th style="width:350">PATIENT INFORMATION</th>
			<th style="width:350">ENCOUNTER INFORMATION</th>
		</tr>
		<tr>
			<td valign="top">
				<h4><?php echo $patientInfo->lastname. ', ' . $patientInfo->firstname;?></h4>
				Date of Birth: <?php echo $dob;?><br>
				Current Age: <?php echo $age;?><br>
				Age at Date of Service: <?php echo $age1;?><br>
				Gender: <?php echo $gender;?><br>
			</td>
			<td valign="top">
				<h4>Encounter <?php echo $eid;?></h4>
				Date of Service: <?php echo $encounter_DOS;?><br>
				Provider: <?php echo $encounter_provider;?><br>
				Status: <?php echo $status;?><br>
			</td>
		</tr>
	</table><br>
	<hr class="ui-state-default"/>
	<h4>Chief Complaint:</h4>
	<p class="view"><?php echo $encounter_cc;?></p>
	<?php echo $hpi;?>
	<?php echo $ros;?>
	<?php echo $oh;?>
	<?php echo $vitals;?>
	<?php echo $pe;?>
	<?php echo $labs;?>
	<?php echo $procedure;?>
	<?php echo $assessment;?>
	<?php echo $orders;?>
	<?php echo $rx;?>
	<?php echo $plan;?>
	<br />
	<hr class="ui-state-default"/>
	<?php echo $billing;?>
</div>
<?php if ($addendum == true) {?>
	<script type="text/javascript">
		$('#add_addendum').button().click(function(){
			$.ajax({
				type: "POST",
				url: "ajaxencounter/new-addendum/<?php echo $eid;?>",
				success: function(data) {
					noshdata.encounter_active = 'y';
					noshdata.eid = data
					openencounter();
					$("#nosh_chart_div").hide();
					$("#nosh_encounter_div").show();
					$("#encounter_list_dialog").dialog('close');
					$("#encounter_view_dialog").dialog('close');
				}
			});
		});
		$.ajax({
			type: "POST",
			url: "ajaxencounter/previous-versions/<?php echo $eid;?>",
			success: function(data) {
				$('#previous_versions').html(data);
				$('.addendum_class').click(function(){
					var eid = $(this).attr("id");
					$.ajax({
						type: "POST",
						url: "ajaxencounter/get-previous-versions/" + eid,
						success: function(data) {
							$('#encounter_text').html(data);
						}
					});
				});
			}
		});
	</script>
<?php }?>
