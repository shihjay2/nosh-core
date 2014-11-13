<div id="menucontainer">
	<div id="menu_accordion">
		<h3><i class="fa fa-home fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Chart for <span id="menu_ptname"></span> (ID: <?php echo $id;?>)</h3>
		<div id="menu_accordion_chart">
			<strong>Nickname:</strong> <span id="menu_nickname"></span><br>
			<strong>Date of Birth:</strong> <span id="menu_dob"></span><br>
			<strong>Age:</strong> <span id="menu_age"></span><br>
			<strong>Gender:</strong> <span id="menu_gender1"></span><br>
			<strong>Last Encounter:</strong> <?php echo $lastvisit;?><br>
			<strong>Next Appointment:</strong> <?php echo $nextvisit;?><br>
			<?php echo $psych;?>
			<br>
			<div class="pure-g" style="font-size:1.1em">
				<div class="pure-u-1-2"><i class="fa fa-umbrella fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> <a href="#" id="prevention_list" title="View prevention recommendations from U.S. Preventatitve Services Task Force and the CDC" class="nosh_tooltip">Prevention</a></div>
				<div class="pure-u-1-2"><i class="fa fa-pencil fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> <a href="#" id="order_list" title="View pending order and historical order history for the patient" class="nosh_tooltip">Orders</a></div>
				<?php if($mtm == 'y') {?>
					<div class="pure-u-1-2"><i class="fa fa-medkit fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> <a href="#" id="mtm_list" title="Medicare Medication Therapy Management" class="nosh_tooltip">MTM</a></div>
				<?php }?>
				<?php if (Session::get('agealldays') <6574.5) {?>
					<div class="pure-u-1-2"><i class="fa fa-line-chart fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> <a href="#" id="gc_list" title="View growth charts" class="nosh_tooltip">Growth Charts</a></div>
				<?php }?>
				<div class="pure-u-1-2"><i class="fa fa-check fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> <a href="#" id="hedis_chart" title="View a HEDIS audit for this patient" class="nosh_tooltip">HEDIS Audit</a></div>
			</div>
		</div>
		<h3><button class="demographics_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-user fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Demographics</a></h3>
		<div id="menu_accordion_demographics-list">
			<a href="#" class="demographics_list">Edit and View Details</a><br>
			<div id="menu_accordion_demographics-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
			<div id="menu_accordion_demographics-list_content" class="menu_accordion_content"></div>
		</div>
		<h3><button class="issues_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-bars fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Issues</a></h3>
		<div id="menu_accordion_issues-list">
			<a href="#" class="issues_list">Edit and View Details</a><br>
			<div id="menu_accordion_issues-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
			<div id="menu_accordion_issues-list_content" class="menu_accordion_content"></div>
		</div>
		<h3><button class="medications_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-eyedropper fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Medications</a></h3>
		<div id="menu_accordion_medications-list">
			<a href="#" class="medications_list">Edit and View Details</a><br>
			<div id="menu_accordion_medications-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
			<div id="menu_accordion_medications-list_content" class="menu_accordion_content"></div>
		</div>
		<?php if($supplements == 'y') {?>
			<h3><button class="supplements_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-tree fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Supplements</a></h3>
			<div id="menu_accordion_supplements-list">
				<a href="#" class="supplements_list">Edit and View Details</a><br>
				<div id="menu_accordion_supplements-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
				<div id="menu_accordion_supplements-list_content" class="menu_accordion_content"></div>
			</div>
		<?php }?>
		<?php if($immunizations == 'y') {?>
			<h3><button class="immunizations_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-magic fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Immunizations</a></h3>
			<div id="menu_accordion_immunizations-list">
				<a href="#" class="immunizations_list">Edit and View Details</a><br>
				<div id="menu_accordion_immunizations-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
				<div id="menu_accordion_immunizations-list_content" class="menu_accordion_content"></div>
			</div>
		<?php }?>
		<h3><button class="allergies_list nosh_button_edit">Edit</button><i class="fa fa-exclamation-triangle fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Allergies</a></h3>
		<div id="menu_accordion_allergies-list">
			<a href="#" class="allergies_list">Edit and View Details</a><br>
			<div id="menu_accordion_allergies-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
			<div id="menu_accordion_allergies-list_content" class="menu_accordion_content"></div>
		</div>
		<h3><button class="alerts_list nosh_button_edit">Edit</button><a href="#"><i class="fa fa-bell fa-fw fa-lg" style="vertical-align:middle;padding:2px"></i> Alerts</a></h3>
		<div id="menu_accordion_alerts-list">
			<a href="#" class="alerts_list">Edit and View Details</a><br>
			<div id="menu_accordion_alerts-list_load"><?php echo HTML::image('images/indicator.gif', 'Loading', array('border' => '0', 'style' => 'vertical-align:middle;')); ?> Loading...</div>
			<div id="menu_accordion_alerts-list_content" class="menu_accordion_content"></div>
		</div>
	</div>
</div>
