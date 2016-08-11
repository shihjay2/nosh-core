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

Route::any('/', array('as' => 'home', 'before' => 'force.ssl|version_check|installfix|googlecheck|needinstall|update|openid|auth|google|pnosh', 'uses' => 'HomeController@dashboard'));
Route::any('login', array('as' => 'login', 'before' => 'force.ssl', 'uses' => 'LoginController@action'));
Route::any('mobile', array('as' => 'mobile', 'before' => 'force.ssl|version_check|installfix|needinstall|update|openid|auth.mobile', 'uses' => 'MobileController@dashboard'));
Route::any('login_mobile', array('as' => 'login_mobile', 'before' => 'force.ssl', 'uses' => 'MobileController@action'));
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
Route::any('schedule_widget', array('as' => 'schedule_widget', 'before' => 'force.ssl|schedule_check', 'uses' => 'HomeController@schedule'));
Route::get('schedule_widget_start/{practicehandle}', function($practicehandle = null)
{
	if ($practicehandle != null) {
		$practice = Practiceinfo::where('practicehandle', '=', $practicehandle)->first();
		if ($practice) {
			Session::put('practice_id', $practice->practice_id);
			Session::put('group_id', 'schedule');
		}
	}
	return Redirect::to('schedule_widget');
});
Route::get('install', array('as' => 'install', 'before' => 'force.ssl|installfix|noinstall', 'uses' => 'InstallController@view'));
Route::get('install_fix', array('as' => 'install_fix', 'uses' => 'InstallController@install_fix'));
Route::get('install_oidc', array('as' => 'install_oidc', 'before' => 'force.ssl|installfix|noinstall', 'uses' => 'InstallController@view'));
Route::get('reset_database', array('as' => 'reset_database', 'uses' => 'InstallController@reset_database'));
Route::get('google_start', array('as' => 'google_start', 'uses' => 'InstallController@google_start'));
Route::post('google_upload', array('as' => 'google_upload', 'uses' => 'AjaxInstallController@google_upload'));
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
Route::group(array('before' => 'force.ssl|csrf_header|session_check|acl6'), function() {
	Route::controller('ajaxsetup', 'AjaxSetupController');
});
Route::group(array('before' => 'auth|force.ssl|acl1|pid_check|uma_check'), function() {
	Route::get('chart', array('as' => 'chart', 'uses' => 'ChartController@main'));
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
	Route::get('print_chart/{hippa_id}/{type}', array('as' => 'print_chart', 'uses' => 'ChartController@print_charts'));
	Route::get('ccda/{hippa_id}', array('as' => 'ccda', 'uses' => 'ChartController@ccda'));
	Route::post('csvupload', array('as' => 'csvupload', 'uses' => 'AjaxChartController@import_csv'));
	Route::post('eraupload', array('as' => 'eraupload', 'uses' => 'AjaxFinancialController@eraupload'));
});
Route::group(array('before' => 'auth|force.ssl|acl1'), function() {
	Route::post('import_contact', array('as' => 'import_contact', 'uses' => 'AjaxMessagingController@import_contact'));
	Route::post('pages_upload', array('as' => 'pages_upload', 'uses' => 'AjaxMessagingController@pages_upload'));
	Route::get('view_faxpage/{pages_id}', array('as' => 'view_faxpage', 'uses' => 'ChartController@view_faxpage'));
	Route::get('view_documents/{id}', array('as' => 'view_documents', 'uses' => 'ChartController@view_documents'));
	Route::get('view_fax/{id}', array('as' => 'view_fax', 'uses' => 'HomeController@view_fax'));
	Route::get('view_scan/{id}', array('as' => 'view_scan', 'uses' => 'HomeController@view_scan'));
	Route::get('print_invoice1/{eid}/{insurance_id_1?}/{insurance_id_2?}', array('as' => 'print_invoice1', 'uses' => 'ChartController@print_invoice1'));
	Route::get('print_invoice2/{id}', array('as' => 'print_invoice2', 'uses' => 'ChartController@print_invoice2'));
	Route::get('generate_hcfa/{flatten}/{eid}', array('as' => 'generate_hcfa', 'uses' => 'ChartController@generate_hcfa'));
	Route::get('generate_hcfa1/{flatten}/{eid}/{insurance_id_1}/{insurance_id_2?}', array('as' => 'generate_hcfa1', 'uses' => 'ChartController@generate_hcfa1'));
	Route::get('printimage_single/{eid}', array('as' => 'printimage_single', 'uses' => 'ChartController@printimage_single'));
	Route::get('print_batch/{type}/{filename}', array('as' => 'print_batch', 'uses' => 'ChartController@print_batch'));
	Route::get('financial_query_print/{id}', array('as' => 'financial_query_print', 'uses' => 'ChartController@financial_query_print'));
	Route::get('hippa_request_print/{id}', array('as' => 'hippa_request_print', 'uses' => 'ChartController@hippa_request_print'));
	Route::get('export_demographics/{type}', array('as' => 'export_demographics', 'uses' => 'ChartController@export_demographics'));
	Route::get('export_address_csv', array('as' => 'export_address_csv', 'uses' => 'HomeController@export_address_csv'));
	Route::get('chart_mobile/{pid}', array('as' => 'chart_mobile', 'uses' => 'MobileController@chart_mobile'));
	Route::get('editpage/{type}/{index}/{id}', array('as' => 'editpage', 'uses' => 'MobileController@editpage'));
	Route::get('submitdata/{type}', array('as' => 'submitdata', 'uses' => 'MobileController@submitdata'));
	Route::get('mobile_schedule', array('as' => 'mobile_schedule', 'uses' => 'MobileController@schedule'));
	Route::get('mobile_inbox', array('as' => 'mobile_inbox', 'uses' => 'MobileController@inbox'));
	Route::get('pnosh_provider_redirect', array('as' => 'pnosh_provider_redirect', 'uses' => 'HomeController@pnosh_provider_redirect'));
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
	Route::get('textmacrodownload/{template_id}', array('as' => 'textmacrodownload', 'uses' => 'AjaxDashboardController@textmacrodownload'));
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
Route::group(array('before' => 'force.ssl|acl6'), function() {
	Route::get('print_individual_chart/{pid}', array('as' => 'print_individual_chart', 'uses' => 'HomeController@print_individual_chart'));
});

Route::get('logout', array('as' => 'logout', 'before' => 'force.ssl|needinstall|logout_type', 'uses' => 'LoginController@logout'));
Route::get('uma_logout', array('as' => 'uma_logout', 'before' => 'force.ssl|needinstall', 'uses' => 'LoginController@uma_logout'));
Route::get('oidc_logout', array('as' => 'oidc_logout', 'before' => 'force.ssl|needinstall', 'uses' => 'LoginController@oidc_logout'));
Route::get('logout_mobile', array('as' => 'logout_mobile', 'before' => 'force.ssl', 'uses' => 'MobileController@logout'));
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
Route::get('googleoauth', array('as' => 'googleoauth', 'uses' => 'LoginController@googleoauth'));
Route::get('oidc', array('as' => 'oidc', 'uses' => 'LoginController@oidc'));
Route::get('oidc_register_client', array('as' => 'oidc_register_client', 'uses' => 'LoginController@oidc_register_client'));
Route::get('oidc_check_patient_centric', array('as' => 'oidc_check_patient_centric', 'uses' => 'LoginController@oidc_check_patient_centric'));
Route::any('practice_choose', array('as' => 'practice_choose', 'before' => 'force.ssl', 'uses' => 'LoginController@practice_choose'));
Route::any('uma_invitation_request', array('as' => 'uma_invitation_request', 'before' => 'force.ssl', 'uses' => 'LoginController@uma_invitation_request'));
Route::get('uma_auth', array('as' => 'uma_auth', 'uses' => 'LoginController@uma_auth'));
Route::get('uma_register_client', array('as' => 'uma_register_client', 'uses' => 'LoginController@uma_register_client'));
Route::get('uma_get_refresh_token', array('as' => 'uma_get_refresh_token', 'uses' => 'LoginController@uma_get_refresh_token'));
Route::any('uma_patient_centric', array('as' => 'uma_patient_centric', 'uses' => 'InstallController@uma_patient_centric'));
Route::get('reset_demo', array('as' => 'reset_demo', 'uses' => 'LoginController@reset_demo'));
Route::group(array('prefix' => 'uma_api'), function()
{
	Route::controller('', 'UMAAPIController');
});
// API routes
Route::get('authtest', array('before' => 'auth.basic', function()
{
	return View::make('hello');
}));
Route::get('checkapi/{practicehandle}', array('as' => 'checkapi', 'before' => 'force.ssl', 'uses' => 'AjaxCommonController@checkapi'));
Route::post('registerapi', array('as' => 'registerapi', 'before' => 'force.ssl', 'uses' => 'AjaxCommonController@registerapi'));
Route::get('practiceregister/{api}', array('as' => 'practiceregister', 'before' => 'force.ssl', 'uses' => 'InstallController@practiceregister'));
Route::post('practiceregisternosh/{api}', array('as' => 'practiceregisternosh', 'uses' => 'AjaxInstallController@practiceregisternosh'));
Route::get('providerregister/{api}', array('as' => 'providerregister', 'before' => 'force.ssl', 'uses' => 'InstallController@providerregister'));
Route::post('apilogin', array('as' => 'apilogin', 'uses' => 'AjaxLoginController@apilogin'));
Route::post('apilogout', array('as' => 'apilogout', 'uses' => 'AjaxLoginController@apilogout'));
Route::group(array('prefix' => 'api/v1', 'before' => 'force.ssl|auth.token'), function()
{
	Route::controller('sync', 'APIv1Controller');
});
// FHIR routes
Route::get('fhir/oidc', array('as' => 'oidc_api', 'uses' => 'LoginController@oidc_api'));
//Route::group(array('prefix' => 'fhir'), function()
Route::group(array('prefix' => 'fhir', 'before' => 'auth.token'), function()
{
	Route::resource('AdverseReaction', 'AdverseReactionController');
	Route::resource('Alert', 'AlertController');
	Route::resource('AllergyIntolerance', 'AllergyIntoleranceController'); // in use - allergies
	Route::resource('Binary', 'BinaryController'); // in use - documents
	Route::resource('CarePlan', 'CarePlanController');
	Route::resource('Composition', 'CompositionController');
	Route::resource('ConceptMap', 'ConceptMapController');
	Route::resource('Condition', 'ConditionController'); //in use - issues, assessments
	Route::resource('Conformance', 'ConformanceController');
	Route::resource('Device', 'DeviceController');
	Route::resource('DeviceObservationReport', 'DeviceObservationReportController');
	Route::resource('DiagnosticOrder', 'DiagnosticOrderController');
	Route::resource('DiagnosticReport', 'DiagnosticReportController');
	Route::resource('DocumentReference', 'DocumentReferenceController');
	Route::resource('DocumentManifest', 'DocumentManifestController');
	Route::resource('Encounter', 'EncounterController'); //in use - encounters
	Route::resource('FamilyHistory', 'FamilyHistoryController'); //in use
	Route::resource('Group', 'GroupController');
	Route::resource('ImagingStudy', 'ImagingStudyController');
	Route::resource('Immunization', 'ImmunizationController'); // in use - immunizations
	Route::resource('ImmunizationRecommendation', 'ImmunizationRecommendationController');
	Route::resource('List', 'ListController');
	Route::resource('Location', 'LocationController');
	Route::resource('Media', 'MediaController');
	Route::resource('Medication', 'MedicationController'); //in use - rxnorm
	Route::resource('MedicationAdministration', 'MedicationAdministrationController');
	Route::resource('MedicationDispense', 'MedicationDispenseController');
	Route::resource('MedicationPrescription', 'MedicationPrescriptionController');
	Route::resource('MedicationStatement', 'MedicationStatementController'); //in use - medication list
	Route::resource('MessageHeader', 'MessageHeaderController');
	Route::resource('Observation', 'ObservationController');
	Route::resource('OperationOutcome', 'OperationOutcomeController');
	Route::resource('Order', 'OrderController');
	Route::resource('OrderResponse', 'OrderResponseController');
	Route::resource('Organization', 'OrganizationController');
	Route::resource('Other', 'OtherController');
	Route::resource('Patient', 'PatientController'); //in use
	Route::resource('Practitioner', 'PractitionerController'); //in use
	Route::resource('Procedure', 'ProcedureController');
	Route::resource('Profile', 'ProfileController');
	Route::resource('Provenance', 'ProvenanceController');
	Route::resource('Query', 'QueryController');
	Route::resource('Questionnaire', 'QuestionnaireController');
	Route::resource('RelatedPerson', 'RelatedPersonController');
	Route::resource('SecurityEvent', 'SecurityEventController');
	Route::resource('Specimen', 'SpecimenController');
	Route::resource('Substance', 'SubstanceController');
	Route::resource('Supply', 'SupplyController');
	Route::resource('ValueSet', 'ValueSetController');
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
	$current_version = "1.8.4";
	$row = Practiceinfo::find(1);
	// Check version number
	if ($row->version < $current_version) {
		return Redirect::to('update');
	}
});

Route::filter('google', function()
{
	$row = Practiceinfo::find(1);
	if ($row->google_refresh_token == '' && Session::get('group_id') == '1') {
		if (route('home') != 'http://localhost/nosh') {
			return Redirect::to('googleoauth');
		}
	}
});

Route::filter('googlecheck', function()
{
	$config_file = __DIR__."/../.google";
	if (! file_exists($config_file)) {
		return Redirect::to('google_start');
	}
});

Route::filter('openid', function()
{
	if(route('home') == 'https://hieofone.com/nosh' || route('home') == 'https://noshchartingsystem.com/nosh' || route('home') == 'https://www.noshchartingsystem.com/nosh' || route('home') == 'https://shihjay.xyz/nosh' || route('home') == 'https://agropper.xyz/nosh') {
		$row = Practiceinfo::find(1);
		if ($row->openidconnect_client_id == '') {
			return Redirect::to('oidc_register_client');
		}
	}
});

Route::filter('pnosh', function()
{
	if (Session::get('group_id') != '100' && Session::get('patient_centric') == 'yp') {
		return Redirect::to('pnosh_provider_redirect');
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

Route::filter('pid_check', function()
{
	if (!Session::get('pid')) {
		return Redirect::to('/');
	}
});

Route::filter('uma_check', function()
{
	if (Session::get('patient_centric') != 'yp') {
		$query = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		if ($query->api_key != '') {
			if ($query->uma_client_id == '') {
				return Redirect::to('uma_register_client');
			} else {
				if ($query->uma_refresh_token) {
					return Redirect::to('uma_get_refresh_token');
				}
			}
		}
	}
});

Route::filter('schedule_check', function()
{
	if (!Session::get('practice_id')) {
		Session::put('practice_id', '1');
		Session::put('group_id', 'schedule');
		return Redirect::to('schedule_widget');
	}
});

Route::filter('logout_type', function()
{
	if (Session::has('uma_auth_access_token')) {
		return Redirect::to('uma_logout');
	}
	if (Session::has('oidc_auth_access_token')) {
		return Redirect::to('oidc_logout');
	}
});

// ACL filters
// Group 1 = Providers, Assistants, Billers
// Group 2 = Providers, Assistants
// Group 3 = Providers
// Group 4 = Patients
// Group 5 = Admin
// Group 6 = Admin + Patient Centric Providers

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

Route::filter('acl6', function()
{
	if (Session::get('group_id') != '1') {
		if (Session::get('group_id') != '2' || Session::get('patient_centric') != 'yp') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		}
	}
});

Route::filter('force.ssl', function()
{
	if (route('home') != 'http://demo.noshchartingsystem.com:444/nosh' && route('home') != 'http://localhost/nosh') {
		if (!Request::secure()) {
			return Redirect::secure(Request::path());
		}
	}
});

Route::filter('auth.basic', function()
{
	return Auth::onceBasic('username');
});

Route::filter('auth.token', function()
{
	$payload = Request::header('Authorization');
	$open_id_url = str_replace('/nosh', '', URL::to('/'));
	$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
	$client_id = $practice->uma_client_id;
	$client_secret = $practice->uma_client_secret;
	if ($payload) {
		// RPT, Perform Token Introspection
		$rpt = str_replace('Bearer ', '', $payload);
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->refresh($practice->uma_refresh_token,true);
		if ($oidc->getRefreshToken() != '') {
			$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
			DB::table('practiceinfo')->where('practice_id', '=', '1')->update($refresh_data);
			$this->audit('Update');
		}
		$result_rpt = $oidc->introspect($rpt);
		if ($result_rpt['active'] == false) {
			// Inactive RPT, Request Permission Ticket
			$url = Request::url();
			// Remove parameters if it exists
			$url_array = explode('?', $url);
			$url = $url_array[0];
			// Trim any trailing Slashes
			$url = rtrim($url, '/');
			// Check if end fragment of URL is an integer and strip it out
			$path = parse_url($url, PHP_URL_PATH);
			$pathFragments = explode('/', $path);
			$end = end($pathFragments);
			if (is_numeric($end)) {
				$pathFragments1 = explode('/', $url);
				$sliced = array_slice($pathFragments1, 0, -1);
				$url = implode('/', $sliced);
			}
			$query = DB::table('uma')->where('scope', '=', $url)->first();
			$as_uri = str_replace('/nosh', '/', URL::to('/'));
			$header = [
				'WWW-Authenticate' => 'UMA realm="pNOSH_UMA", as_uri="' . $as_uri . '"'
			];
			$statusCode = 403;
			if ($query) {
				// Look for additional scopes for resource_set_id
				$query1 = DB::table('uma')->where('resource_set_id', '=', $query->resource_set_id)->get();
				$scopes = array();
				foreach ($query1 as $row1) {
					$scopes[] = $row1->scope;
				}
				$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
				$oidc->refresh($practice->uma_refresh_token,true);
				if ($oidc->getRefreshToken() != '') {
					$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
					DB::table('practiceinfo')->where('practice_id', '=', '1')->update($refresh_data);
					$this->audit('Update');
				}
				$permission_ticket = $oidc->permission_request($query->resource_set_id, $scopes);
				if (isset($permission_ticket['error'])) {
					$response = [
						'error' => $permission_ticket['error'],
						'error_description' => $permission_ticket['error_description']
					];
				} else {
					$response = [
						'ticket' => $permission_ticket['ticket']
					];
				}
			} else {
				$response = [
					'error' => 'invalid_scope',
					'error_description' => 'At least one of the scopes included in the request was not registered previously by this resource server.'
				];
			}
			return Response::json($response, $statusCode, $header);
		}
	} else {
		// No RPT, Request Permission Ticket
		$url = Request::url();
		// Remove parameters if it exists
		$url_array = explode('?', $url);
		$url = $url_array[0];
		// Trim any trailing Slashes
		$url = rtrim($url, '/');
		// Check if end fragment of URL is an integer and strip it out
		$path = parse_url($url, PHP_URL_PATH);
		$pathFragments = explode('/', $path);
		$end = end($pathFragments);
		if (is_numeric($end)) {
			$pathFragments1 = explode('/', $url);
			$sliced = array_slice($pathFragments1, 0, -1);
			$url = implode('/', $sliced);
		}
		$query = DB::table('uma')->where('scope', '=', $url)->first();
		$as_uri = str_replace('/nosh', '', URL::to('/'));
		$header = [
			'WWW-Authenticate' => 'UMA realm = "pNOSH_UMA", as_uri = "' . $as_uri . '"'
		];
		$statusCode = 403;
		if ($query) {
			// Look for additional scopes for resource_set_id
			$query1 = DB::table('uma')->where('resource_set_id', '=', $query->resource_set_id)->get();
			$scopes = array();
			foreach ($query1 as $row1) {
				$scopes[] = $row1->scope;
			}
			$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
			$oidc->refresh($practice->uma_refresh_token,true);
			if ($oidc->getRefreshToken() != '') {
				$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
				DB::table('practiceinfo')->where('practice_id', '=', '1')->update($refresh_data);
				$this->audit('Update');
			}
			$permission_ticket = $oidc->permission_request($query->resource_set_id, $scopes);
			if (isset($permission_ticket['error'])) {
				$response = [
					'error' => $permission_ticket['error'],
					'error_description' => $permission_ticket['error_description']
				];
			} else {
				$response = [
					'ticket' => $permission_ticket['ticket']
				];
			}
		} else {
			$response = [
				'error' => 'invalid_scope',
				'error_description' => 'At least one of the scopes included in the request was not registered previously by this resource server.'
			];
		}
		return Response::json($response, $statusCode, $header);
	}
	//$payload = Request::header('X-Auth-Token');
	//$user =  DB::table('users')->where('oauth_token', '=', $payload)->where('oauth_token_secret', '>', time())->first();
	//if(!$payload || !$user) {
		//$statusCode = 401;
		//$response['error'] = true;
		//$response['message'] = 'Not authenticated';
		//$response['code'] = 401;
		//return Response::json($response, $statusCode);
	//}
});
Route::filter('auth.mobile', function()
{
	if (Auth::guest()) return Redirect::guest('login_mobile');
});

//Route::any('test1', array('as' => 'test1', 'uses' => 'ReminderController@test'));
