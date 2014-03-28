<div id="growth_chart_dialog" title="Choose parameter to plot:">
	<?php if (Session::get('agealldays') <6574.5) { if (Session::get('agealldays') > 730.5) {?>
		<button type="button" class="nosh_button weight_chart menu">Weight</button> <button type="button" class="nosh_button height_chart menu">Height</button> <button type="button" class="nosh_button hc_chart menu">Head Circumference</button> <button type="button" class="nosh_button bmi_chart menu">BMI</button> <button type="button" class="nosh_button weight_height_chart menu">Weight-Height</button><br><br>
	<?php } else {?>
		<button type="button" class="nosh_button weight_chart menu">Weight</button> <button type="button" class="nosh_button height_chart menu">Height</button> <button type="button" class="nosh_button hc_chart menu">Head Circumference</button> <button type="button" class="nosh_button weight_height_chart menu">Weight-Height</button><br><br>
	<?php }}?>
</div>
<div id="graph_dialog" title="Growth Chart">
	<div id="container" style="width: 750px; height: 550px; margin: 0 auto"></div>
</div>
<div id="graph_load" title="Loading...">
	<?php echo HTML::image('images/indicator.gif', 'Loading'); ?> Loading graph...
</div>
