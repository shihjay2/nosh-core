<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('/', array('as' => 'home', 'before' => 'needinstall|installfix|update|auth', 'uses' => 'HomeController@dashboard'));
Route::any('login', array('as' => 'login', 'uses' => 'LoginController@action'));
Route::get('start/{practicehandle}', function($practicehandle = null)
{
	if ($practicehandle != null) {
		$practice = Practiceinfo::where('practicehandle', '=', $practicehandle)->first();
		if ($practice) {
			return $practice->practice_id;
		}
	}
	return Redirect::to('/');
});
Route::get('install', array('as' => 'install', 'before' => 'noinstall|installfix', 'uses' => 'InstallController@view'));
Route::get('install_fix', array('as' => 'install_fix', 'uses' => 'InstallController@install_fix'));
Route::get('codeigniter_migrate', array('as' => 'codeigniter_migrate', 'uses' => 'AjaxInstallController@codeigniter_migrate'));
Route::get('update', array('as' => 'update', 'uses' => 'AjaxInstallController@update'));
Route::get('update_system', array('as' => 'update_system', 'uses' => 'BackupController@update_system'));
Route::get('set_version', array('as' => 'set_version', 'uses' => 'AjaxInstallController@set_version'));
Route::get('bluebutton/{id}', array('as' => 'bluebutton', 'before' => 'auth', 'uses' => 'AjaxCommonController@bluebutton'));
Route::group(['before' => 'csrf_header'], function() {
	Route::controller('ajaxinstall', 'AjaxInstallController');
	Route::controller('ajaxlogin', 'AjaxLoginController');
	Route::controller('ajaxdashboard', 'AjaxDashboardController');
	Route::controller('ajaxsearch', 'AjaxSearchController');
	Route::controller('ajaxmessaging', 'AjaxMessagingController');
	Route::controller('ajaxcommon', 'AjaxCommonController');
});
Route::group(['before' => 'csrf_header|session_check|acl1'], function() {
	Route::controller('ajaxchart', 'AjaxChartController');
	Route::controller('ajaxschedule', 'AjaxScheduleController');
	Route::controller('ajaxfinancial', 'AjaxFinancialController');
	Route::controller('ajaxoffice', 'AjaxOfficeController');
});
Route::group(['before' => 'csrf_header|session_check|acl2'], function() {
	Route::controller('ajaxencounter', 'AjaxEncounterController');
});
Route::group(['before' => 'csrf_header|session_check|acl5'], function() {
	Route::controller('ajaxsetup', 'AjaxSetupController');
});
Route::group(['before' => 'acl1'], function() {
	Route::get('chart', array('as' => 'chart', 'uses' => 'ChartController@main'));
	Route::get('messaging', array('as' => 'messaging', 'uses' => 'HomeController@showWelcome'));
	Route::get('schedule', array('as' => 'schedule', 'uses' => 'HomeController@showWelcome'));
	Route::get('billing', array('as' => 'billing', 'uses' => 'HomeController@showWelcome'));
	Route::get('office', array('as' => 'office', 'uses' => 'HomeController@showWelcome'));
	Route::get('closechart', array('as' => 'closechart', function()
	{
		Session::forget('age');
		Session::forget('agealldays');
		Session::forget('gender');
		Session::forget('pid');
		Session::forget('ptname');
		Session::forget('eid');
		Session::forget('encounter_active');
		Session::forget('t_messages_id');
		Session::forget('alert_id');
		Session::forget('financial');
		return Redirect::to('/');
	}));
	Route::post('documentsupload', array('as' => 'documentsupload', 'uses' => 'AjaxChartController@documentsupload'));
	Route::post('ccrupload', array('as' => 'ccrupload', 'uses' => 'AjaxChartController@ccrupload'));
	Route::post('ccdaupload', array('as' => 'ccdaupload', 'uses' => 'AjaxChartController@ccdaupload'));
	Route::get('print_ccr', array('as' => 'print_ccr', 'uses' => 'ChartController@print_ccr'));
	Route::get('view_faxpage/{pages_id}', array('as' => 'view_faxpage', 'uses' => 'ChartController@view_faxpage'));
	Route::get('view_documents/{id}', array('as' => 'view_documents', 'uses' => 'ChartController@view_documents'));
	Route::get('print_chart/{hippa_id}/{type}', array('as' => 'print_chart', 'uses' => 'ChartController@print_charts'));
	Route::get('ccda/{hippa_id}', array('as' => 'ccda', 'uses' => 'ChartController@ccda'));
	Route::get('print_invoice1/{eid}/{insurance_id_1?}/{insurance_id_2?}', array('as' => 'print_invoice1', 'uses' => 'ChartController@print_invoice1'));
	Route::get('print_invoice2/{id}', array('as' => 'print_invoice2', 'uses' => 'ChartController@print_invoice2'));
	Route::get('generate_hcfa/{flatten}/{eid}', array('as' => 'generate_hcfa', 'uses' => 'ChartController@generate_hcfa'));
	Route::get('generate_hcfa1/{flatten}/{eid}/{insurance_id_1}/{insurance_id_2?}', array('as' => 'generate_hcfa1', 'uses' => 'ChartController@generate_hcfa1'));
	Route::post('pages_upload', array('as' => 'pages_upload', 'uses' => 'AjaxMessagingController@pages_upload'));
	Route::get('view_fax/{id}', array('as' => 'view_fax', 'uses' => 'HomeController@view_fax'));
	Route::get('view_scan/{id}', array('as' => 'view_scan', 'uses' => 'HomeController@view_scan'));
	Route::post('import_contact', array('as' => 'import_contact', 'uses' => 'AjaxMessagingController@import_contact'));
	Route::get('printimage_single/{eid}', array('as' => 'printimage_single', 'uses' => 'ChartController@printimage_single'));
	Route::get('print_batch/{type}/{filename}', array('as' => 'print_batch', 'uses' => 'ChartController@print_batch'));
	Route::get('financial_query_print/{id}', array('as' => 'financial_query_print', 'uses' => 'ChartController@financial_query_print'));
	Route::get('export_demographics/{type}', array('as' => 'export_demographics', 'uses' => 'ChartController@export_demographics'));
});
Route::group(['before' => 'acl2'], function() {
	Route::get('encounter', array('as' => 'encounter', function()
	{
		Session::put('encounter_active', 'y');
		return Redirect::to('chart');
	}));
	Route::get('print_medication/{rxl_id}', array('as' => 'print_medication', 'uses' => 'ChartController@print_medication'));
	Route::get('print_medication_list', array('as' => 'print_medication_list', 'uses' => 'ChartController@print_medication_list'));
	Route::get('print_orders/{orders_id}', array('as' => 'print_orders', 'uses' => 'ChartController@print_orders'));
	Route::get('print_consent', array('as' => 'print_consent', 'uses' => 'ChartController@print_consent'));
	Route::get('print_immunization_list', array('as' => 'print_immunization_list', 'uses' => 'ChartController@print_immunization_list'));
	Route::get('csv_immunization', array('as' => 'csv_immunization', 'uses' => 'ChartController@csv_immunization'));
	Route::get('print_plan', array('as' => 'print_plan', 'uses' => 'AjaxEncounterController@print_plan'));
});
Route::group(['before' => 'acl4'], function() {
});
Route::group(['before' => 'acl5'], function() {
	Route::post('practicelogoupload', array('as' => 'practicelogoupload', 'uses' => 'AjaxSetupController@practicelogoupload'));
	Route::post('cpt_update', array('as' => 'cpt_update', 'uses' => 'AjaxSetupController@cpt_update'));
});
Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));
Route::get('reminder', array('as' => 'reminder', 'uses' => 'ReminderController@reminder'));
Route::get('fax', array('as' => 'fax', 'uses' => 'FaxController@fax'));
Route::get('footerpdf', array("as" => "footerpdf", function()
{
	$data['page'] = Input::get('page');
	$data['topage'] = Input::get('topage');
	return View::make('pdf.footer', $data);
}));
Route::get('mtmfooterpdf', array("as" => "mtmfooterpdf", function()
{
	$data['page'] = Input::get('page');
	$data['topage'] = Input::get('topage');
	return View::make('pdf.mtmfooter', $data);
}));
Route::get('mtmheaderpdf/{pid}', array("as" => "mtmheaderpdf", function($pid)
{
	$page = Input::get('page');
	if ($page > 1) {
		$data['show'] = true;
	} else {
		$data['show'] = false;
	}
	$row = Demographics::find($pid);
	$date = explode(" ", $row->DOB);
	$date1 = explode("-", $date[0]);
	$data['patientDOB'] = $date1[1] . "/" . $date1[2] . "/" . $date1[0];
	$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
	return View::make('pdf.mtmheader', $data);
}));
Route::get('backup', array('as' => 'backup', 'uses' => 'BackupController@backup'));
Route::post('backuprestore', array('as' => 'backuprestore', 'uses' => 'BackupController@restore'));
Route::filter('needinstall', function()
{
	$config_file = __DIR__."/../.env.php";
	$codeigniter = __DIR__."/../.codeigniter.php";
	if (!file_exists($config_file)) {
		if (file_exists($codeigniter)) {
			return Redirect::to('codeigniter_migrate');
		} else {
			return Redirect::to('install');
		}
	} else {
		$config = require($config_file);
		$connect = mysqli_connect('localhost', $config['mysql_username'], $config['mysql_password']);
		$db = mysqli_select_db($connect, $config['mysql_database']);
		if (!$db) {
			mysqli_close($connect);
			return Redirect::to('install');
		}
		mysqli_close($connect);
	}
});

Route::filter('noinstall', function()
{
	$config_file = __DIR__."/../.env.php";
	if (file_exists($config_file)) {
		$config = require($config_file);
		$connect = mysqli_connect('localhost', $config['mysql_username'], $config['mysql_password']);
		$db = mysqli_select_db($connect, $config['mysql_database']);
		if ($db) {
			mysqli_close($connect);
			return Redirect::to('/');
		}
		mysqli_close($connect);
	}
});

Route::filter('installfix', function()
{
	$config_file = __DIR__."/../.env.php";
	if (file_exists($config_file)) {
		$config = require($config_file);
		$connect = mysqli_connect('localhost', $config['mysql_username'], $config['mysql_password']);
		if (!$connect) {
			return Redirect::to('install_fix');
		}
		mysqli_close($connect);
	}
});

Route::filter('update', function()
{
	$current_version = "1.8.0";
	$row = Practiceinfo::find(1);
	// Check version number
	if ($row->version < $current_version) {
		return Redirect::to('update');
	}
});

Route::filter('csrf_header', function()
{
	if (Session::token() != Request::header('x-csrf-token')) {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::filter('session_check', function()
{
	if (!Session::get('user_id')) {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

// ACL filters
// Group 1 = Providers, Assistants, Billers
// Group 2 = Providers, Assistants
// Group 3 = Providers
// Group 4 = Patients
// Group 5 = Admin

Route::filter('acl1', function()
{
	if (Session::get('group_id') == '100') {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::filter('acl2', function()
{
	if (Session::get('group_id') == '100' || Session::get('group_id') == '4') {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::filter('acl3', function()
{
	if (Session::get('group_id') == '100' || Session::get('group_id') == '4' || Session::get('group_id') == '3') {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::filter('acl4', function()
{
	if (Session::get('group_id') != '100') {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::filter('acl5', function()
{
	if (Session::get('group_id') != '1') {
		Auth::logout();
		Session::flush();
		header("HTTP/1.1 404 Page Not Found", true, 404);
		exit("You cannot do this.");
	}
});

Route::get('test', array('as' => 'test', function()
{
	echo route('home');
}));

//Route::get('test1', array('as' => 'test1', 'uses' => 'ReminderController@test'));
