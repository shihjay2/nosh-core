<html>
	<head>
		<title>{{ $title }}</title>
		<meta name="token" content="{{ Session::token() }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.3.0/pure-min.css">
		<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
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
		</script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<?php echo HTML::script('js/jquery.maskedinput.min.js'); ?>
		<?php echo HTML::script('js/jquery.jgrowl.js'); ?>
		<?php echo HTML::script('js/jquery.selectboxes.js'); ?>
		<?php echo HTML::script('js/jquery-migrate-1.1.0.js'); ?>
		<?php echo HTML::script('js/i18n/grid.locale-en.js'); ?>
		<?php echo HTML::script('js/jquery.jqGrid.min.js'); ?>
		<?php echo HTML::script('js/jquery-idleTimeout.js'); ?>
		<?php echo HTML::script('js/jquery.themeswitcher.js'); ?>
		<?php echo HTML::script('js/main.js'); ?>
		{{ $script }}
	</head>
	<body>
		{{ $content }}
	</body>
</html>
