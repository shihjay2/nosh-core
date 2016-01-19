<div data-role="page" class="ui-responsive-panel" id="panel-responsive-page1" data-title="NOSH Mobile Chart Page">
	<div data-role="header" id="chart_header" data-position="fixed" role="banner" class="wow fadeIn"><?php echo $header;?></div>
	<div data-role="header" id="navigation_header" data-position="fixed" style="display:none" class="wow fadeIn"><?php echo $navigation_header;?></div>
	<div role="main" class="ui-content" id="content">
		<ul id="searchpt" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search patient..." data-filter-theme="a"></ul>
		<div id="content_inner_main">
			<div id="content_inner_timeline" data-enhance="false">
				<?php echo $content;?>
			</div>
			<div id="fab_container" class="ui-btn-fab-bottom">
				<a href="#" id="nosh_fab_issue" class="ui-btn ui-btn-fab ui-btn-raised clr-primary nosh_fab nosh_fab_child" style="display:none; background: #c03b44;"><i class="zmdi zmdi-format-list-bulleted zmdi-hc-2x nosh_fab_i"></i></a>
				<a href="#" id="nosh_fab_rx" class="ui-btn ui-btn-fab ui-btn-raised clr-primary nosh_fab nosh_fab_child" style="display:none; background: #f0ca45;"><i class="zmdi zmdi-eyedropper zmdi-hc-2x nosh_fab_i"></i></a>
				<a href="#" id="nosh_fab_encounter" class="ui-btn ui-btn-fab ui-btn-raised clr-primary nosh_fab nosh_fab_child" style="display:none; background: #75ce66;"><i class="zmdi zmdi-comment zmdi-hc-2x nosh_fab_i"></i></a>
				<a href="#" id="nosh_fab" class="ui-btn ui-btn-fab ui-btn-raised clr-primary nosh_fab"><i class="zmdi zmdi-plus zmdi-hc-2x nosh_fab_i"> </i></a>
			</div>
		</div>
		<div id="content_inner"></div>
		
	</div>
	<div role="main" class="ui-content" id="edit_content" style="display:none">
		<div id="searchicd_div" class="search_class" style="display:none">
			<ul id="searchicd" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search ICD Code..." data-filter-theme="a"></ul>
		</div>
		<div id="searchallergies_div" class="search_class" style="display:none">
			<ul id="searchmed1" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Medication..." data-filter-theme="a"></ul>
			<ul id="searchsupplement1" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Supplement..." data-filter-theme="a"></ul>
		</div>
		<div id="searchmed_div" class="search_class" style="display:none">
			<ul id="searchmed" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Medication..." data-filter-theme="a"></ul>
		</div>
		<div id="searchsupplement_div" class="search_class" style="display:none">
			<ul id="searchsupplement" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Supplement..." data-filter-theme="a"></ul>
		</div>
		<div id="searchimm_div" class="search_class" style="display:none">
			<ul id="searchimm" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Immunization..." data-filter-theme="a"></ul>
		</div>
		<div id="edit_content_inner"></div>
	</div>
	<div data-role="panel" data-display="overlay" data-theme="b" id="left_panel" data-position-fixed="true"><?php echo $left_panel;?></div>
	<div data-role="panel" data-position="right" data-display="overlay" data-theme="a" id="right_panel">
		<input type="hidden" id="form_item" value=""/>
		<div id="right_panel_selections">
			<ul id="search_results" data-role="listview" data-inset="true"></ul>
		</div>
		<?php echo $right_panel;?>
	</div>
</div>
<script type="text/javascript">
	$(document).on("pagecreate", "#panel-responsive-page1", function() {
		$("#searchpt").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/search",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						$.each(response.message, function ( i, val ) {
							html += '<li><a href="<?php echo URL::to('chart_mobile', array()); ?>/'  + val.id + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
		$("#searchicd").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/icd",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						$.each(response.message, function ( i, val ) {
							html += '<li><a href="#" data-nosh-value="' + val.label + '" class="mobile_paste" data-nosh-paste-to="' + nosh_paste_to + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
		$("#searchmed1").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/rx-name",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						$.each(response.message, function ( i, val ) {
							html += '<li><a href="#" data-nosh-value="' + val.label + '" class="mobile_paste" data-nosh-paste-to="' + nosh_paste_to + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
		$("#searchmed").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 4) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/rx-name",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						$.each(response.message, function ( i, val ) {
							html += '<li data-role="collapsible" data-inset="false" data-shadow="false" data-iconpos="right"><h2 data-nosh-name="' + val.name + '" data-nosh-form="' + val.form + '" data-nosh-paste-to="' + nosh_paste_to + '" data-nosh-med="' + val.label + '">' + val.label + '</h2><ul data-role="listview" data-inset="true" data-filter="false" data-shadow="false"></ul></li>';
						});
						$ul.html(html);
						$ul.find("[data-role='collapsible']").collapsible();
						$ul.listview("refresh");
						$ul.trigger("create");
					}
					$.mobile.loading("hide");
				});
				
			}
		}).on( "collapsibleexpand", function(event, ui) {
			$('#searchmed').children().each( function (i){
				if (!$(this).hasClass('ui-collapsible-collapsed')) {
					var $ul = $(this).find('ul');
					if ($ul.html() == '') {
						$.mobile.loading("show");
						var name = $(this).find('h2').attr('data-nosh-name');
						var form = $(this).find('h2').attr('data-nosh-form');
						var med = $(this).find('h2').attr('data-nosh-med');
						var req = 'term=' + name + ";" + form;
						var html = '';
						var nosh_paste_to = '';
						$.ajax({
							url: "../ajaxsearch/rx-dosage",
							dataType: "json",
							type: "POST",
							data: req
						}).then(function(response) {
							$.each(response.message, function ( i, val ) {
								html += '<li><a href="#" class="mobile_paste1" data-nosh-med="' + med + '"data-nosh-value="' + val.value + '" data-nosh-unit="' + val.unit + '" data-nosh-ndc="' + val.ndc + '"data-nosh-value-paste-to="'+ nosh_paste_to +'">' + val.label + '</a></li>';
							});
							$ul.html(html);
							$ul.listview().listview("refresh");
							$ul.trigger("updatelayout");
							$.mobile.loading("hide");
						});
					}
					
				}
			});
		});
		$("#searchsupplement1").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/supplements/N",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						var currentCategory = "";
						$.each(response.message, function ( i, val ) {
							if (val.category != currentCategory ) {
								if (val.category != '') {
									html += '<li data-role="list-divider">' + val.category + '</li>';
								} else {
									html += '<li data-role="list-divider">Supplements Database</li>';
								}
								currentCategory = val.category;
							}
							html += '<li><a href="#" data-nosh-value="' + val.label + '" class="mobile_paste" data-nosh-paste-to="' + nosh_paste_to + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
		$("#searchsupplement").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/supplements/N",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						var currentCategory = "";
						$.each(response.message, function ( i, val ) {
							if (val.category != currentCategory ) {
								if (val.category != '') {
									html += '<li data-role="list-divider">' + val.category + '</li>';
								} else {
									html += '<li data-role="list-divider">Supplements Database</li>';
								}
								currentCategory = val.category;
							}
							html += '<li><a href="#" class="mobile_paste3" data-nosh-value="' + val.value + '" class="mobile_paste" data-nosh-paste-to="' + nosh_paste_to + '" data-nosh-quantity="' + val.quantity + '" data-nosh-dosage="' + val.dosage +'" data-nosh-dosage-unit="' + val.dosage_unit + '" data-nosh-supplement-id="' + val.supplement_id + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
		$("#searchimm").on("filterablebeforefilter", function (e,data) {
			var $ul = $(this),
				$input = $(data.input),
				value = $input.val(),
				html = "";
			$ul.html("");
			var nosh_paste_to = $(this).attr('data-nosh-paste-to');
			if (value && value.length > 2) {
				$ul.html("<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>");
				$ul.listview("refresh");
				$.mobile.loading("show");
				$.ajax({
					url: "../ajaxsearch/imm",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					if (response.response == 'true') {
						$.each(response.message, function ( i, val ) {
							html += '<li><a href="#" data-nosh-value="' + val.label + '" class="mobile_paste4" data-nosh-paste-to="' + nosh_paste_to + '" data-nosh-cvx="' + val.cvx + '">' + val.label + '</a></li>';
						});
						$ul.html(html);
						$ul.listview("refresh");
						$ul.trigger("updatelayout");
					}
					$.mobile.loading("hide");
				});
			}
		});
	});
</script>
