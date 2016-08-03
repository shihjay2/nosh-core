<?php

class InstallController extends BaseController {

	/**
	* NOSH ChartingSystem Installation
	*/

	protected $layout = 'layouts.layout1';

	public function view()
	{
		$this->layout->title = "NOSH ChartingSystem Installation";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/install.js');
		$this->layout->content = View::make('install');
	}

	public function install_fix()
	{
		$this->layout->title = "NOSH ChartingSystem Database Connection Fixer";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/installfix.js');
		$this->layout->content = View::make('install_fix_db_conn');
	}

	public function install_oidc()
	{
		$this->layout->title = "NOSH ChartingSystem - Patient Version Installation";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/install_oidc.js');
		$this->layout->content = View::make('install_oidc');
	}

	public function practiceregister($api)
	{
		$this->layout->title = "NOSH ChartingSystem Practice Registration";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/practiceregister.js');
		$this->layout->content = '';
		$practice = DB::table('practiceinfo')->where('practice_registration_key', '=', $api)->first();
		$base = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		if ($practice) {
			$data['practice_id'] = $practice->practice_id;
			$data['patient_portal'] = rtrim($base->patient_portal, '/');
			$this->layout->content .= View::make('practiceregister', $data)->render();
		} else {
			$this->layout->content .= '<strong>Registration link timed out or does not exist!</strong><br>';
			$this->layout->content .= '<p>' . HTML::linkRoute('login', 'Click here to re-register to NOSH ChartingSystem') . '</p>';
		}
	}

	public function reset_database()
	{
		$this->layout->title = "NOSH ChartingSystem Reset Database";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/reset.js');
		$this->layout->content = View::make('reset_database');
	}

	public function google_start()
	{
		$this->layout->title = "NOSH ChartingSystem Pre Installation Check";
		$this->layout->style = '';
		$this->layout->script =  HTML::script('/js/google_start.js');
		$config_file = __DIR__."/../../.google";
		$data['file'] = "<strong>You're' here because you have not installed a Google OAuth2 Client ID file.  You'll need to set this up first before configuring NOSH Charting System.'</strong>";
		if (file_exists($config_file)) {
			$data['file'] = '<strong>A Google OAuth2 Client ID file is already installed.  Uploading a new file will overwrite the existing file!</strong>';
		}
		$this->layout->content = View::make('google_start', $data);
	}

	public function uma_patient_centric()
	{
		$query = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$open_id_url = str_replace('/nosh', '/', URL::to('/'));
		if ($query->patient_centric == 'y') {
			if ($query->uma_client_id == '') {
				// Register as resource server
				$patient = DB::table('demographics')->where('pid', '=', '1')->first();
				$client_name = 'Patient NOSH for ' .  $patient->firstname . ' ' . $patient->lastname . ', DOB: ' . date('Y-m-d', strtotime($patient->DOB));
				$url = route('uma_auth');
				$oidc = new OpenIDConnectClient($open_id_url);
				$oidc->setClientName($client_name);
				$oidc->setRedirectURL($url);
				$oidc->addScope('openid');
				$oidc->addScope('email');
				$oidc->addScope('profile');
				$oidc->addScope('address');
				$oidc->addScope('phone');
				$oidc->addScope('offline_access');
				$oidc->addScope('uma_protection');
				$oidc->addScope('uma_authorization');
				$oidc->addGrantType('authorization_code');
				$oidc->addGrantType('password');
				$oidc->addGrantType('client_credentials');
				$oidc->addGrantType('implicit');
				$oidc->addGrantType('jwt-bearer');
				$oidc->addGrantType('refresh_token');
				$oidc->register(true,true);
				$data['uma_client_id']  = $oidc->getClientID();
				$data['uma_client_secret'] = $oidc->getClientSecret();
				DB::table('practiceinfo')->where('practice_id', '=', '1')->update($data);
				$this->audit('Update');
				return Redirect::to('uma_patient_centric');
			} else {
				if ($query->uma_refresh_token == '') {
					// Get refresh token and link patient with user
					$client_id = $query->uma_client_id;
					$client_secret = $query->uma_client_secret;
					$url = route('uma_patient_centric');
					$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
					$oidc->setRedirectURL($url);
					$oidc->addScope('openid');
					$oidc->addScope('email');
					$oidc->addScope('profile');
					$oidc->addScope('offline_access');
					$oidc->addScope('uma_protection');
					$oidc->authenticate(true);
					$user_data['uid']  = $oidc->requestUserInfo('sub');
					DB::table('users')->where('id', '=', '2')->update($user_data);
					$access_token = $oidc->getAccessToken();
					if ($oidc->getRefreshToken() != '') {
						$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
						DB::table('practiceinfo')->where('practice_id', '=', '1')->update($refresh_data);
						$this->audit('Update');
					}
				} else {
					// Register resource sets
					$uma = DB::table('uma')->first();
					if (!$uma) {
						$resource_set_array[] = array(
							'name' => 'Patient',
							'icon' => 'https://noshchartingsystem.com/i-patient.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Patient',
								URL::to('/') . '/fhir/Medication',
								URL::to('/') . '/fhir/Practitioner',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'My Conditions',
							'icon' => 'https://noshchartingsystem.com/i-condition.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Condition',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'Medication List',
							'icon' => 'https://noshchartingsystem.com/i-pharmacy.png',
							'scopes' => array(
								URL::to('/') . '/fhir/MedicationStatement',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'Allergy List',
							'icon' => 'https://noshchartingsystem.com/i-allergy.png',
							'scopes' => array(
								URL::to('/') . '/fhir/AllergyIntolerance',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'Immunization List',
							'icon' => 'https://noshchartingsystem.com/i-immunizations.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Immunization',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'My Encounters',
							'icon' => 'https://noshchartingsystem.com/i-medical-records.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Encounter',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'Family History',
							'icon' => 'https://noshchartingsystem.com/i-family-practice.png',
							'scopes' => array(
								URL::to('/') . '/fhir/FamilyHistory',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'My Documents',
							'icon' => 'https://noshchartingsystem.com/i-file.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Binary',
								'view',
								'edit'
							)
						);
						$resource_set_array[] = array(
							'name' => 'Observations',
							'icon' => 'https://noshchartingsystem.com/i-cardiology.png',
							'scopes' => array(
								URL::to('/') . '/fhir/Observation',
								'view',
								'edit'
							)
						);
						$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
						$oidc->refresh($query->uma_refresh_token,true);
						foreach ($resource_set_array as $resource_set_item) {
							$response = $oidc1->resource_set($resource_set_item['name'], $resource_set_item['icon'], $resource_set_item['scopes']);
							if (isset($response['resource_set_id'])) {
								foreach ($resource_set_item['scopes'] as $scope_item) {
									$response_data1 = array(
										'resource_set_id' => $response['resource_set_id'],
										'scope' => $scope_item,
										'user_access_policy_uri' => $response['user_access_policy_uri']
									);
									DB::table('uma')->insert($response_data1);
									$this->audit('Add');
								}
							}
						}
					}
				}
			}
		}
		return Redirect::to('home');
	}
}
