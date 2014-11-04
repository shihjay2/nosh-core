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

Route::any('/', array('as' => 'home', 'before' => 'force.ssl|version_check|installfix|needinstall|update|openid|auth', 'uses' => 'HomeController@dashboard'));
Route::any('login', array('as' => 'login', 'before' => 'force.ssl', 'uses' => 'LoginController@action'));
Route::get('start/{practicehandle}', function($practicehandle = null)
{
	if ($practicehandle != null) {
		$practice = Practiceinfo::where('practicehandle', '=', $practicehandle)->first();
		if ($practice) {
			Session::put('practice_id', $practice->practice_id);
		}
	}
	return Redirect::to('/');
});
Route::get('install', array('as' => 'install', 'before' => 'force.ssl|installfix|noinstall', 'uses' => 'InstallController@view'));
Route::get('install_fix', array('as' => 'install_fix', 'uses' => 'InstallController@install_fix'));
Route::get('update', array('as' => 'update', 'uses' => 'AjaxInstallController@update'));
Route::get('update_system', array('as' => 'update_system', 'uses' => 'BackupController@update_system'));
Route::get('set_version', array('as' => 'set_version', 'uses' => 'AjaxInstallController@set_version'));
Route::post('phaxio/{id}', array('as' => 'phaxio', 'uses' => 'FaxController@phaxio'));
Route::get('bluebutton/{id}', array('as' => 'bluebutton', 'before' => 'force.ssl|auth', 'uses' => 'AjaxCommonController@bluebutton'));
Route::group(array('before' => 'force.ssl|csrf_header'), function() {
	Route::controller('ajaxinstall', 'AjaxInstallController');
	Route::controller('ajaxlogin', 'AjaxLoginController');
	Route::controller('ajaxdashboard', 'AjaxDashboardController');
	Route::controller('ajaxsearch', 'AjaxSearchController');
	Route::controller('ajaxmessaging', 'AjaxMessagingController');
	Route::controller('ajaxcommon', 'AjaxCommonController');
	Route::controller('ajaxschedule', 'AjaxScheduleController');
});
Route::group(array('before' => 'force.ssl|csrf_header|session_check|acl1'), function() {
	Route::controller('ajaxchart', 'AjaxChartController');
	Route::controller('ajaxfinancial', 'AjaxFinancialController');
	Route::controller('ajaxoffice', 'AjaxOfficeController');
});
Route::group(array('before' => 'force.ssl|csrf_header|session_check|acl2'), function() {
	Route::controller('ajaxencounter', 'AjaxEncounterController');
});
Route::group(array('before' => 'force.ssl|csrf_header|session_check|acl5'), function() {
	Route::controller('ajaxsetup', 'AjaxSetupController');
});
Route::group(array('before' => 'force.ssl|acl1'), function() {
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
		Session::forget('mtm');
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
	Route::get('hippa_request_print/{id}', array('as' => 'hippa_request_print', 'uses' => 'ChartController@hippa_request_print'));
	Route::get('export_demographics/{type}', array('as' => 'export_demographics', 'uses' => 'ChartController@export_demographics'));
	Route::get('export_address_csv', array('as' => 'export_address_csv', 'uses' => 'HomeController@export_address_csv'));
	Route::post('csvupload', array('as' => 'csvupload', 'uses' => 'AjaxChartController@import_csv'));
	Route::post('eraupload', array('as' => 'eraupload', 'uses' => 'AjaxFinancialController@eraupload'));
});
Route::group(array('before' => 'force.ssl|acl2'), function() {
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
	Route::post('photoupload', array('as' => 'photoupload', 'uses' => 'AjaxChartController@photoupload'));
	Route::get('templatedownload/{template_id}', array('as' => 'templatedownload', 'uses' => 'AjaxDashboardController@templatedownload'));
	Route::get('texttemplatedownload/{template_id}', array('as' => 'texttemplatedownload', 'uses' => 'AjaxDashboardController@texttemplatedownload'));
	Route::post('templateupload', array('as' => 'templateupload', 'uses' => 'AjaxDashboardController@templateupload'));
});
Route::group(array('before' => 'force.ssl|acl3'), function() {
	Route::post('signatureupload', array('as' => 'signatureupload', 'uses' => 'AjaxDashboardController@signatureupload'));
});
Route::group(array('before' => 'force.ssl|acl4'), function() {
});
Route::group(array('before' => 'force.ssl|acl5'), function() {
	Route::post('practicelogoupload', array('as' => 'practicelogoupload', 'uses' => 'AjaxSetupController@practicelogoupload'));
	Route::post('cpt_update', array('as' => 'cpt_update', 'uses' => 'AjaxSetupController@cpt_update'));
	Route::post('importupload', array('as' => 'importupload', 'uses' => 'AjaxDashboardController@importupload'));
});
Route::get('logout', array('as' => 'logout', 'before' => 'force.ssl|needinstall', 'uses' => 'LoginController@logout'));
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
Route::get('oidc', array('as' => 'oidc', 'uses' => 'LoginController@oidc'));
Route::get('oidc_register_client', array('as' => 'oidc_register_client', 'uses' => 'LoginController@oidc_register_client'));
Route::get('oidc_check_patient_centric', array('as' => 'oidc_check_patient_centric', 'uses' => 'LoginController@oidc_check_patient_centric'));
Route::any('practice_choose', array('as' => 'practice_choose', 'before' => 'force.ssl', 'uses' => 'LoginController@practice_choose'));
// API routes
Route::get('authtest', array('before' => 'auth.basic', function()
{
	return View::make('hello');
}));
Route::get('checkapi/{practicehandle}', array('as' => 'checkapi', 'before' => 'force.ssl', 'uses' => 'AjaxCommonController@checkapi'));
Route::get('practiceregister/{api}', array('as' => 'practiceregister', 'before' => 'force.ssl', 'uses' => 'InstallController@practiceregister'));
Route::post('practiceregisternosh/{api}', array('as' => 'practiceregisternosh', 'uses' => 'AjaxInstallController@practiceregisternosh'));
Route::get('providerregister/{api}', array('as' => 'providerregister', 'before' => 'force.ssl', 'uses' => 'InstallController@providerregister'));
Route::post('apilogin', array('as' => 'apilogin', 'uses' => 'AjaxLoginController@apilogin'));
Route::post('apilogout', array('as' => 'apilogout', 'uses' => 'AjaxLoginController@apilogout'));
Route::group(array('prefix' => 'api/v1', 'before' => 'force.ssl|auth.basic'), function()
{
	Route::controller('sync', 'APIv1Controller');
});
// Filters
Route::filter('needinstall', function()
{
	$query = Practiceinfo::find('1');
	if ($query == false) {
		return Redirect::to('install');
	}
});

Route::filter('noinstall', function()
{
	$query = Practiceinfo::find('1');
	if ($query) {
		return Redirect::to('/');
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
	if (!Schema::hasTable('migrations')) {
		Artisan::call('migrate:install');
	}
	Artisan::call('migrate');
	$current_version = "1.8.3";
	$row = Practiceinfo::find(1);
	// Check version number
	if ($row->version < $current_version) {
		return Redirect::to('update');
	}
});

Route::filter('openid', function()
{
	if(route('home') == 'https://hieofone.com/nosh' || route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh') {
		$row = Practiceinfo::find(1);
		if ($row->openidconnect_client_id == '') {
			return Redirect::to('oidc_register_client');
		}
	}
});

Route::filter('version_check', function()
{
	if (!File::exists(__DIR__."/../.version")) {
		return Redirect::to('set_version');
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

Route::filter('force.ssl', function()
{
	if (!Request::secure()) {
		return Redirect::secure(Request::path());
	}
});

Route::filter('auth.basic', function()
{
	return Auth::onceBasic('username');
});

Route::get('autoload', function()
{
	$command = "composer dump-autoload -d " . __DIR__."/../";
	exec($command);
	return "Autoload complete.";
});
//Route::get('test1', array('as' => 'test1', 'uses' => 'ReminderController@test'));
