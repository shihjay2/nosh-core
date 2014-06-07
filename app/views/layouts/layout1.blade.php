<html>
	<head>
		<title>{{ $title }}</title>
		<meta name="token" content="{{ Session::token() }}">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="cache-control" content="no-store" />
		<meta http-equiv="cache-control" content="must-revalidate" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.3.0/pure-min.css">
		<link type="text/css" href="https://code.jquery.com/ui/1.11.0-beta.2/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
		<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<?php echo HTML::style('css/main.css'); ?>
		<?php echo HTML::style('css/jquery.jgrowl.css'); ?>
		<?php echo HTML::style('css/ui.jqgrid.css'); ?>
		{{ $style }}
		<script type="text/javascript">
			var noshdata = {
				'url': '<?php echo route('home'); ?>',
				'images': '<?php echo url('images'); ?>/',
				'documents': '<?php echo trim(File::get(__DIR__ . '/../../../.noshdir')); ?>',
				'error': '<?php echo route('home'); ?>',
				'logout_url': '<?php echo route('logout'); ?>',
				'images': '<?php echo url('images'); ?>/'
			};
			var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
		</script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0-beta.2/jquery-ui.min.js"></script>
		<?php echo HTML::script('js/jquery.maskedinput.min.js'); ?>
		<?php echo HTML::script('js/jquery.jgrowl.js'); ?>
		<?php echo HTML::script('js/jquery.selectboxes.js'); ?>
		<?php echo HTML::script('js/jquery-migrate-1.2.1.js'); ?>
		<?php echo HTML::script('js/i18n/grid.locale-en.js'); ?>
		<?php echo HTML::script('js/jquery.jqGrid.min.js'); ?>
		<?php echo HTML::script('js/jquery-idleTimeout.js'); ?>
		<?php echo HTML::script('js/jquery.themeswitcher.js'); ?>
		<?php echo HTML::script('js/jstz-1.0.4.min.js'); ?>
		<?php echo HTML::script('js/jquery.cookie.js'); ?>
		<?php echo HTML::script('js/main.js'); ?>
		{{ $script }}
	</head>
	<body>
		{{ $content }}
	</body>
</html>
