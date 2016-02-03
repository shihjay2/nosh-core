<div data-role="page" class="ui-responsive-panel" id="panel-responsive-page1" data-title="NOSH Mobile Home Page">
	<div data-role="header" data-position="fixed" class="wow fadeIn"><?php echo $header;?></div>
	<div role="main" class="ui-content" id="content">
		<?php echo $content;?>
	</div>
	<div role="main" class="ui-content" id="view_content" style="display:none">
		<?php echo $view;?>
	</div>
	<div role="main" class="ui-content" id="edit_content" style="display:none">
		<?php echo $form;?>
	</div>
	<div data-role="panel" data-display="overlay" data-theme="b" id="left_panel"><?php echo $left_panel;?></div>
	<div data-role="panel" data-position="right" data-display="overlay" data-theme="a" id="right_panel">
		<input type="hidden" id="form_item" value=""/>
		<div id="right_panel_selections">
			<ul id="search_results" data-role="listview" data-inset="true"></ul>
		</div>
		<?php echo $right_panel;?>
	</div>
</div>
<script type="text/javascript">
	$(document).on("pageinit", "#panel-responsive-page1", function() {
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
					url: "ajaxsearch/search",
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
	});
</script>
