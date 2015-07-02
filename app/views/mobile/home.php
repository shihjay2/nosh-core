<div data-role="page" class="ui-responsive-panel" id="panel-responsive-page1" data-title="NOSH Mobile Home Page">
	<div data-role="header"><?php echo $header;?></div>
	<div role="main" class="ui-content" id="content">
		<ul id="searchpt" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search patient..." data-filter-theme="a"></ul>
		<?php echo $content;?>
		</div>
	<div data-role="panel" data-display="push" data-theme="b" id="left_panel"><?php echo $left_panel;?></div>
	<div data-role="panel" data-position="right" data-display="reveal" data-theme="a" id="right_panel"><?php echo $right_panel;?></div>
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
				$.ajax({
					url: "ajaxsearch/search",
					dataType: "json",
					type: "POST",
					data: "term=" + value
				})
				.then(function(response) {
					$.each(response.message, function ( i, val ) {
						html += '<li><a href="<?php echo URL::to('chart_mobile', array()); ?>/'  + val.id + '">' + val.label + '</a></li>';
					});
					$ul.html(html);
					$ul.listview("refresh");
					$ul.trigger("updatelayout");
				});
			}
		});
	});
</script>
