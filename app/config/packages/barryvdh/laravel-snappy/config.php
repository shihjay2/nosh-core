<?php
$int = PHP_INT_SIZE;
if($int == 4) {
	$bin = base_path('/vendor/h4cc/wkhtmltopdf-i386/bin/wkhtmltopdf-i386');
} else {
	$bin = base_path('/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
}
return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => $bin,
        'options' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltoimage',
        'options' => array(),
    ),


);
