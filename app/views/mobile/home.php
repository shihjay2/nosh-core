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
	<div data-role="popup" id="textdump_group_html_div" data-display="overlay" data-theme="a">
		<div data-role="header" data-theme="a">
			<h1 class="nd-title">Template Groups</h1>
		</div>
		<div data-role="content">
			<form id="textdump_group_form">
				<input type="text" name="textdump_group_add" id="textdump_group_add" placeholder="Add template group here"/>
				<input type="hidden" name="target" id="textdump_group_target"/>
				<input type="hidden" id="textdump_group_id">
			</form><br>
			<form id="textdump_delimiter2_form">
				<div id="textdump_delimiter2_div">
					<label for="textdump_delimiter2">Delimiter for normal values</label>
					<select id="textdump_delimiter2">
						<option value=", ">,</option>
						<option value=" ">space</option>
						<option value="&#13;&#10;">new line</option>
						<option value="; ">;</option>
					</select>
				</div>
			</form>
		</div>
		<div data-role="content" id="textdump_group_html"></div>
	</div>
	<div data-role="popup" id="textdump_html_div" data-display="overlay" data-theme="a">
		<div data-role="header" data-theme="a">
			<h1 class="nd-title">Templates</h1>
		</div>
		<div data-role="content">
			<input type="hidden" id="textdump_input"/>
			<form id="textdump_form">
				<input type="text" name="textdump_add" id="textdump_add" placeholder="Add text to template here"/>
				<input type="hidden" name="target" id="textdump_target"/>
				<input type="hidden" name="group" id="textdump_group_item">
			</form><br>
			<form id="textdump_delimiter1_form">
				<div id="textdump_delimiter1_div">
					<label for="textdump_delimiter1">Delimiter</label>
					<select id="textdump_delimiter1">
						<option value=", ">,</option>
						<option value=" ">space</option>
						<option value="&#13;&#10;">new line</option>
						<option value="; ">;</option>
					</select>
					<span id="textdump_hint" title="" style="float:right">Text Macro List</span><br><br>
				</div>
			</form>
		</div>
		<div data-role="content" id="textdump_html"></div>
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
