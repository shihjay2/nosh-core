<?php

use Illuminate\Support\MessageBag;
class LoginController extends BaseController {

	/**
	* Authentication of users
	*/
	
	protected $layout = 'layouts.layout2';
	
	public function action()
	{
		$errors = new MessageBag();
		if ($old = Input::old("errors")) {
			$errors = $old;
		}
		$data = array(
			"errors" => $errors
		);
		if (Input::server("REQUEST_METHOD") == "POST") {
			$default_practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
			if ($default_practice->patient_centric == 'y') {
				$validator_array = array(
					"username" => "required",
					"password" => "required"
				);
			} else {
				$validator_array = array(
					"username" => "required",
					"password" => "required",
					"practice_id" => "required"
				);
			}
			$validator = Validator::make(Input::all(), $validator_array);
			if ($validator->passes()) {
				$username = Input::get('username');
				$password = Input::get('password');
				if ($default_practice->patient_centric == 'y') {
					$credentials = array(
						"username" => $username,
						"password" => $password,
						"active" => '1'
					);
					$user = User::where('username', '=', $username)->where('active', '=', '1')->first();
				} else {
					$practice_id = Input::get('practice_id');
					$credentials = array(
						"username" => $username,
						"password" => $password,
						"active" => '1',
						"practice_id" => $practice_id
					);
					$user = User::where('username', '=', $username)->where('active', '=', '1')->where('practice_id', '=', $practice_id)->first();
				}
				if (Auth::attempt($credentials)) {
					$practice = Practiceinfo::find($user->practice_id);
					Session::put('user_id', $user->id);
					Session::put('group_id', $user->group_id);
					Session::put('practice_id', $user->practice_id);
					Session::put('version', $practice->version);
					Session::put('practice_active', $practice->active);
					Session::put('displayname', $user->displayname);
					Session::put('documents_dir', $practice->documents_dir);
					Session::put('rcopia', $practice->rcopia_extension);
					Session::put('mtm_extension', $practice->mtm_extension);
					Session::put('patient_centric', $practice->patient_centric);
					setcookie("login_attempts", 0, time()+900, '/');
					if ($practice->patient_centric == 'n') {
						return Redirect::intended('/');
					} else {
						if ($user->group_id != '100' && $user->group_id != '1') {
							$pid = DB::table('demographics')->first();
							$this->setpatient($pid->pid);
							return Redirect::intended('chart');
						} else {
							return Redirect::intended('/');
						}
					}
				}
			}
			$attempts = $_COOKIE['login_attempts'] + 1;
			setcookie("login_attempts", $attempts, time()+900, '/');
			$data["errors"] = new MessageBag(array(
				"password" => "Username and/or password invalid."
			));
			$data["username"] = Input::get("username");
			return Redirect::to("login")->withInput($data);
		} else {
			$practice1 = Practiceinfo::find(1);
			Session::put('version', $practice1->version);
			$practice_id = Session::get('practice_id');
			if ($practice_id == FALSE) {
				$data['practice_id'] = '1';
			} else  {
				$data['practice_id'] = $practice_id;
			}
			$data['patient_centric'] = $practice1->patient_centric;
			if((array_key_exists('login_attempts', $_COOKIE)) && ($_COOKIE['login_attempts'] >= 5)){
				$data['attempts'] = "You have reached the number of limits to login.  Wait 15 minutes then try again.";
				$this->layout->style = $this->css_assets();
				$this->layout->script = $this->js_assets('base');
				$this->layout->script .= HTML::script('/js/login.js');
				$this->layout->content = View::make('login', $data);
			} else {
				if(!array_key_exists('login_attempts', $_COOKIE)) {
					setcookie("login_attempts", 0, time()+900, '/');
				}
				$this->layout->style = $this->css_assets();
				$this->layout->script = $this->js_assets('base');
				$this->layout->script .= HTML::script('/js/login.js');
				$this->layout->content = View::make('login', $data);
			}
		}
	}
	
	public function logout()
	{
		Auth::logout();
		Session::flush();
		$practice1 = Practiceinfo::find(1);
		Session::put('version', $practice1->version);
		$this->layout->style = $this->css_assets();
		$this->layout->script = $this->js_assets('base');
		$this->layout->content = View::make('logout');
	}
	
	public function oidc_register_client()
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		if ($practice->patient_centric == 'y') {
			$patient = DB::table('demographics')->first();
			$dob = date('m/d/Y', strtotime($patient->DOB));
			$client_name = 'PatientNOSH for ' . $patient->firstname . ' ' . $patient->lastname . ' (DOB: ' . $dob . ')'; 
		} else {
			$client_name = 'PracticeNOSH for ' . $practice->practice_name;
		}
		$open_id_url = 'https://noshchartingsystem.com/openid-connect-server-webapp/';
		$url = route('oidc');
		$oidc = new OpenIDConnectClient($open_id_url);
		$oidc->setClientName($client_name);
		$oidc->setRedirectURL($url);
		$oidc->register();
		$client_id = $oidc->getClientID();
		$client_secret = $oidc->getClientSecret();
		$data = array(
			'openidconnect_client_id' => $client_id,
			'openidconnect_client_secret' => $client_secret
		);
		DB::table('practiceinfo')->where('practice_id', '=', '1')->update($data);
		$this->audit('Update');
		return Redirect::intended('/');
	}
	
	public function oidc_check_patient_centric()
	{
		$query = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		echo $query->patient_centric;
	}
	
	public function oidc()
	{
		$open_id_url = 'https://noshchartingsystem.com/openid-connect-server-webapp/';
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->openidconnect_client_id;
		$client_secret = $practice->openidconnect_client_secret;
		$url = route('oidc');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->authenticate();
		$firstname = $oidc->requestUserInfo('given_name');
		$lastname = $oidc->requestUserInfo('family_name');
		$email = $oidc->requestUserInfo('email');
		$npi = $oidc->requestUserInfo('npi');
		$access_token = $oidc->getAccessToken();
		if ($npi != '') {
			$provider = DB::table('providers')->where('npi', '=', $npi)->first();
			if ($provider) {
				$user = User::where('id', '=', $provider->id)->first();
			} else {
				$user = false;
			}
		} else {
			$user = User::where('uid', '=', $oidc->requestUserInfo('sub'))->first();
			//$user = User::where('firstname', '=', $firstname)->where('email', '=', $email)->where('lastname', '=', $lastname)->where('active', '=', '1')->first();
		}
		if ($user) {
			Auth::login($user);
			$practice = Practiceinfo::find($user->practice_id);
			Session::put('user_id', $user->id);
			Session::put('group_id', $user->group_id);
			Session::put('practice_id', $user->practice_id);
			Session::put('version', $practice->version);
			Session::put('practice_active', $practice->active);
			Session::put('displayname', $user->displayname);
			Session::put('documents_dir', $practice->documents_dir);
			Session::put('rcopia', $practice->rcopia_extension);
			Session::put('mtm_extension', $practice->mtm_extension);
			Session::put('patient_centric', $practice->patient_centric);
			Session::put('oidc_auth_access_token', $access_token);
			setcookie("login_attempts", 0, time()+900, '/');
			return Redirect::intended('/');
		} else {
			$practice_npi = $oidc->requestUserInfo('practice_npi');
			$practice_id = false;
			if ($practice_npi != '') {
				$practice_npi_array = explode(',', $practice_npi);
				$practice_npi_array_null = array();
				foreach ($practice_npi_array as $practice_npi_item) {
					$practice_query = DB::table('practiceinfo')->where('npi', '=', $practice_npi_item)->first();
					if ($practice_query) {
						$practice_id = $practice_query->practice_id;
					} else {
						$practice_npi_array_null[] = $practice_npi_item;
					}
				}
			}
			if ($practice_id == false) {
				if (count($practice_npi_array_null) == 1) {
					$url = 'http://docnpi.com/api/index.php?ident=' . $practice_npi_array_null[0] . '&is_ident=true&format=aha';
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_FAILONERROR,1);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch,CURLOPT_TIMEOUT, 15);
					$data1 = curl_exec($ch);
					curl_close($ch);
					$html = new Htmldom($data1);
					$practicename = '';
					$address = '';
					$street_address1 = '';
					$city = '';
					$state = '';
					$zip = '';
					if (isset($html)) {
						$li = $html->find('li',0);
						if (isset($li)) {
							$nomatch = $li->innertext;
							if ($nomatch != ' no matching results ') {
								$name_item = $li->find('span[class=org]',0);
								$practicename = $name_item->innertext;
								$address_item = $li->find('span[class=address]',0);
								$address = $address_item->innertext;
							}
						}
					}
					if ($address != '') {
						$address_array = explode(',', $address);
						if (isset($address_array[0])) {
							$street_address1 = trim($address_array[0]);
						}
						if (isset($address_array[1])) {
							$zip = trim($address_array[1]);
						}
						if (isset($address_array[2])) {
							$city = trim($address_array[2]);
						}
						if (isset($address_array[3])) {
							$state = trim($address_array[3]);
						}
					}
					$practice_data = array(
						'npi' => $practice_npi_array_null[0],
						'practice_name' => $practicename,
						'street_address1' => $street_address1,
						'city' => $city,
						'state' => $state,
						'zip' => $zip,
						'documents_dir' => $practice->documents_dir,
						'version' => $practice->version,
						'active' => 'Y',
						'fax_type' => '',
						'vivacare' => '',
						'patient_centric' => 'yp',
						'smtp_user' => $practice->smtp_user,
						'smtp_pass' => $practice->smtp_pass
					);
					$practice_id = DB::table('practiceinfo')->insertGetId($practice_data);
					$this->audit('Add');
				} else {
					Session::put('practice_npi_array', implode(',', $practice_npi_array_null));
					Session::put('firstname', $firstname);
					Session::put('lastname', $lastname);
					Session::put('username', $oidc->requestUserInfo('sub'));
					Session::put('middle', $oidc->requestUserInfo('middle_name'));
					Session::put('displayname', $oidc->requestUserInfo('name'));
					Session::put('email', $email);
					Session::put('npi', $npi);
					Session::put('practice_choose', 'y');
					Session::put('uid', $oidc->requestUserInfo('sub'));
					Session::put('oidc_auth_access_token', $access_token);
					return Redirect::to('practice_choose');
				}
			}
			$data = array(
				'username' => $oidc->requestUserInfo('sub'),
				'firstname' => $firstname,
				'middle' => $oidc->requestUserInfo('middle_name'),
				'lastname' => $lastname,
				'displayname' => $oidc->requestUserInfo('name'),
				'email' => $email,
				'group_id' => '2',
				'active'=> '1',
				'practice_id' => $practice_id,
				'secret_question' => 'Use mdNOSH Gateway to reset your password!',
				'uid' => $oidc->requestUserInfo('sub')
			);
			$id = DB::table('users')->insertGetId($data);
			$this->audit('Add');
			$data1 = array(
				'id' => $id,
				'npi' => $npi,
				'practice_id' => $practice_id
			);
			DB::table('providers')->insert($data1);
			$this->audit('Add');
			$user1 = User::where('id', '=', $id)->first();
			Auth::login($user1);
			$practice1 = Practiceinfo::find($user1->practice_id);
			Session::put('user_id', $user1->id);
			Session::put('group_id', $user1->group_id);
			Session::put('practice_id', $user1->practice_id);
			Session::put('version', $practice1->version);
			Session::put('practice_active', $practice1->active);
			Session::put('displayname', $user1->displayname);
			Session::put('documents_dir', $practice1->documents_dir);
			Session::put('rcopia', $practice1->rcopia_extension);
			Session::put('mtm_extension', $practice1->mtm_extension);
			Session::put('patient_centric', $practice1->patient_centric);
			Session::put('oidc_auth_access_token', $access_token);
			setcookie("login_attempts", 0, time()+900, '/');
			return Redirect::intended('/');
		}
	}
	
	public function oidc_api()
	{
		//$client_id = '5b8e4e18-fbfa-4ef2-8e49-074e571be425';
		//$client_secret = 'Pt6PD6xHQFCiWzo0QnfSXJ2XLatVAafXlGlNSw4Td9gVBVjasg7JtcogqgLDdjr6axfFoBV6FhvEoDUA0q1_BQ';
		$open_id_url = 'http://noshchartingsystem.com:8888/openid-connect-server-webapp/';
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->openidconnect_client_id;
		$client_secret = $practice->openidconnect_client_secret;
		$url = route('oidc_api');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->authenticate();
		$firstname = $oidc->requestUserInfo('given_name');
		$lastname = $oidc->requestUserInfo('family_name');
		$email = $oidc->requestUserInfo('email');
		$npi = $oidc->requestUserInfo('npi');
		$access_token = substr($oidc->getAccessToken(),0,255);
		if ($npi != '') {
			$provider = DB::table('providers')->where('npi', '=', $npi)->first();
			if ($provider) {
				$user = User::where('id', '=', $provider->id)->first();
			} else {
				$user = false;
			}
		} else {
			$user = User::where('uid', '=', $oidc->requestUserInfo('sub'))->first();
			//$user = User::where('firstname', '=', $firstname)->where('email', '=', $email)->where('lastname', '=', $lastname)->where('active', '=', '1')->first();
		}
		if ($user) {
			Auth::login($user);
			$user_data = array(
				'oauth_token' => $access_token,
				'oauth_token_secret' => time() + 7200  //2 hour time limit
			);
			DB::table('users')->where('id', '=', $user->id)->update($user_data);
			$this->audit('Update');
			$response['token'] = $access_token;
			$response['user'] = array(
				'uid' => $oidc->requestUserInfo('sub'),
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email,
				'npi' => $npi,
				'api_token' => $access_token
			);
			$statusCode = 200;
		} else {
			$statusCode = 401;
			$response['error'] = true;
			$response['message'] = 'Not an approved user for this system';
			$response['code'] = 401;
		}
		return Response::json($response, $statusCode);
	}
	
	public function practice_choose()
	{
		if (Input::server("REQUEST_METHOD") == "POST") {
			$url = 'http://docnpi.com/api/index.php?ident=' . Input::get('practice_npi_select') . '&is_ident=true&format=aha';
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_FAILONERROR,1);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_TIMEOUT, 15);
			$data1 = curl_exec($ch);
			curl_close($ch);
			$html = new Htmldom($data1);
			$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
			$practicename = '';
			$address = '';
			$street_address1 = '';
			$city = '';
			$state = '';
			$zip = '';
			if (isset($html)) {
				$li = $html->find('li',0);
				if (isset($li)) {
					$nomatch = $li->innertext;
					if ($nomatch != ' no matching results ') {
						$name_item = $li->find('span[class=org]',0);
						$practicename = $name_item->innertext;
						$address_item = $li->find('span[class=address]',0);
						$address = $address_item->innertext;
					}
				}
			}
			if ($address != '') {
				$address_array = explode(',', $address);
				if (isset($address_array[0])) {
					$street_address1 = trim($address_array[0]);
				}
				if (isset($address_array[1])) {
					$zip = trim($address_array[1]);
				}
				if (isset($address_array[2])) {
					$city = trim($address_array[2]);
				}
				if (isset($address_array[3])) {
					$state = trim($address_array[3]);
				}
			}
			$practice_data = array(
				'npi' => Input::get('practice_npi_select'),
				'practice_name' => $practicename,
				'street_address1' => $street_address1,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'documents_dir' => $practice->documents_dir,
				'version' => $practice->version,
				'active' => 'Y',
				'fax_type' => '',
				'vivacare' => '',
				'patient_centric' => 'yp',
				'smtp_user' => $practice->smtp_user,
				'smtp_pass' => $practice->smtp_pass
			);
			$practice_id = DB::table('practiceinfo')->insertGetId($practice_data);
			$this->audit('Add');
			$data = array(
				'username' => Session::get('username'),
				'firstname' => Session::get('firstname'),
				'middle' => Session::get('middle'),
				'lastname' => Session::get('lastname'),
				'displayname' => Session::get('displayname'),
				'email' => Session::get('email'),
				'group_id' => '2',
				'active'=> '1',
				'practice_id' => $practice_id,
				'uid' => Session::get('uid'),
				'secret_question' => 'Use mdNOSH Gateway to reset your password!',
			);
			$id = DB::table('users')->insertGetId($data);
			$this->audit('Add');
			$data1 = array(
				'id' => $id,
				'npi' => Session::get('npi'),
				'practice_id' => $practice_id
			);
			DB::table('providers')->insert($data1);
			$this->audit('Add');
			$user1 = User::where('id', '=', $id)->first();
			Auth::login($user1);
			$practice1 = Practiceinfo::find($user1->practice_id);
			Session::put('user_id', $user1->id);
			Session::put('group_id', $user1->group_id);
			Session::put('practice_id', $user1->practice_id);
			Session::put('version', $practice1->version);
			Session::put('practice_active', $practice1->active);
			Session::put('displayname', $user1->displayname);
			Session::put('documents_dir', $practice1->documents_dir);
			Session::put('rcopia', $practice1->rcopia_extension);
			Session::put('mtm_extension', $practice1->mtm_extension);
			Session::put('patient_centric', $practice1->patient_centric);
			setcookie("login_attempts", 0, time()+900, '/');
			Session::forget('practice_npi_array');
			Session::forget('practice_choose');
			Session::forget('username');
			Session::forget('firstname');
			Session::forget('middle');
			Session::forget('lastname');
			Session::forget('email');
			Session::forget('npi');
			return Redirect::intended('/');
		} else {
			if (Session::has('practice_choose')) {
				if (Session::get('practice_choose') == 'y') {
					$practice_npi_array1 = explode(',', Session::get('practice_npi_array'));
					$form_select_array = array();
					foreach ($practice_npi_array1 as $practice_npi_item1) {
						$form_select_array[$practice_npi_item1] = $practice_npi_item1;
					}
					$arr['practice_npi_select'] = '<div class="pure-control-group">';
					$arr['practice_npi_select'] .= '<label for="practice_npi_select">Practice NPI:</label>';
					$arr['practice_npi_select'] .= Form::select('practice_npi_select',$form_select_array, null, array('id'=>'practice_npi_select','required','style'=>'width:300px','class'=>'text'));
					$this->layout->style = $this->css_assets();
					$this->layout->script = $this->js_assets('base');
					$this->layout->script .= HTML::script('/js/practice_choose.js');
					$this->layout->content = View::make('practice_choose', $arr);
				} else {
					return Redirect::intended('/');
				}
			} else {
				return Redirect::intended('/');
			}
		}
	}
	
	// Patient-centric, UMA login
	public function uma_auth()
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$url = route('uma_auth');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		if ($practice->uma_refresh_token == '') {
			$oidc->authenticate(true, 'user1');
		} else {
			$oidc->authenticate(true, 'user');
		}
		$firstname = $oidc->requestUserInfo('given_name');
		$lastname = $oidc->requestUserInfo('family_name');
		$email = $oidc->requestUserInfo('email');
		$npi = $oidc->requestUserInfo('npi');
		$access_token = $oidc->getAccessToken();
		if ($npi != '') {
			$provider = DB::table('providers')->where('npi', '=', $npi)->first();
			if ($provider) {
				$user = User::where('id', '=', $provider->id)->first();
			} else {
				$user = false;
			}
		} else {
			$user = User::where('uid', '=', $oidc->requestUserInfo('sub'))->first();
			//$user = User::where('firstname', '=', $firstname)->where('email', '=', $email)->where('lastname', '=', $lastname)->where('active', '=', '1')->first();
		}
		if ($user) {
			// Add refresh token, if there is one
			if ($oidc->getRefreshToken() != '') {
				$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
				DB::table('practiceinfo')->where('practice_id', '=', '1')->update($refresh_data);
				// Register scopes, if none are set yet
				$uma = DB::table('uma')->first();
				if (!$uma) {
					$resource_set_array[] = array(
						'name' => 'Patient',
						'icon' => 'https://noshchartingsystem.com/i-patient.png',
						'scopes' => array(
							URL::to('/') . '/fhir/Patient',
							URL::to('/') . '/fhir/Medication',
							URL::to('/') . '/fhir/Practitioner'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Condition',
						'icon' => 'https://noshchartingsystem.com/i-condition.png',
						'scopes' => array(
							URL::to('/') . '/fhir/Condition'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Medication List',
						'icon' => 'https://noshchartingsystem.com/i-pharmacy.png',
						'scopes' => array(
							URL::to('/') . '/fhir/MedicationStatement'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Allergy',
						'icon' => 'https://noshchartingsystem.com/i-allergy.png',
						'scopes' => array(
							URL::to('/') . '/fhir/AllergyIntolerance'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Immunization',
						'icon' => 'https://noshchartingsystem.com/i-immunization.png',
						'scopes' => array(
							URL::to('/') . '/fhir/Immunization'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Encounter',
						'icon' => 'https://noshchartingsystem.com/i-medical-records.png',
						'scopes' => array(
							URL::to('/') . '/fhir/Encounter'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Family History',
						'icon' => 'https://noshchartingsystem.com/i-family-practice.png',
						'scopes' => array(
							URL::to('/') . '/fhir/FamilyHistory'
						)
					);
					$resource_set_array[] = array(
						'name' => 'Binary Files',
						'icon' => 'https://noshchartingsystem.com/i-file.png',
						'scopes' => array(
							URL::to('/') . '/fhir/Binary'
						)
					);
					$oidc1 = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
					$oidc1->refresh($refresh_data['uma_refresh_token'],true);
					foreach ($resource_set_array as $resource_set_item) {
						$response = $oidc1->resource_set($resource_set_item['name'], $$resource_set_item['icon'], $resource_set_item['scopes']);
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
			Auth::login($user);
			$practice = Practiceinfo::find($user->practice_id);
			Session::put('user_id', $user->id);
			Session::put('group_id', $user->group_id);
			Session::put('practice_id', $user->practice_id);
			Session::put('version', $practice->version);
			Session::put('practice_active', $practice->active);
			Session::put('displayname', $user->displayname);
			Session::put('documents_dir', $practice->documents_dir);
			Session::put('rcopia', $practice->rcopia_extension);
			Session::put('mtm_extension', $practice->mtm_extension);
			Session::put('patient_centric', $practice->patient_centric);
			Session::put('uma_auth_access_token', $access_token);
			setcookie("login_attempts", 0, time()+900, '/');
			return Redirect::intended('/');
		} else {
			$practice_npi = $oidc->requestUserInfo('practice_npi');
			$practice_id = false;
			if ($practice_npi != '') {
				$practice_npi_array = explode(',', $practice_npi);
				$practice_npi_array_null = array();
				foreach ($practice_npi_array as $practice_npi_item) {
					$practice_query = DB::table('practiceinfo')->where('npi', '=', $practice_npi_item)->first();
					if ($practice_query) {
						$practice_id = $practice_query->practice_id;
					} else {
						$practice_npi_array_null[] = $practice_npi_item;
					}
				}
			}
			if ($practice_id == false) {
				if (count($practice_npi_array_null) == 1) {
					$url = 'http://docnpi.com/api/index.php?ident=' . $practice_npi_array_null[0] . '&is_ident=true&format=aha';
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_FAILONERROR,1);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch,CURLOPT_TIMEOUT, 15);
					$data1 = curl_exec($ch);
					curl_close($ch);
					$html = new Htmldom($data1);
					$practicename = '';
					$address = '';
					$street_address1 = '';
					$city = '';
					$state = '';
					$zip = '';
					if (isset($html)) {
						$li = $html->find('li',0);
						if (isset($li)) {
							$nomatch = $li->innertext;
							if ($nomatch != ' no matching results ') {
								$name_item = $li->find('span[class=org]',0);
								$practicename = $name_item->innertext;
								$address_item = $li->find('span[class=address]',0);
								$address = $address_item->innertext;
							}
						}
					}
					if ($address != '') {
						$address_array = explode(',', $address);
						if (isset($address_array[0])) {
							$street_address1 = trim($address_array[0]);
						}
						if (isset($address_array[1])) {
							$zip = trim($address_array[1]);
						}
						if (isset($address_array[2])) {
							$city = trim($address_array[2]);
						}
						if (isset($address_array[3])) {
							$state = trim($address_array[3]);
						}
					}
					$practice_data = array(
						'npi' => $practice_npi_array_null[0],
						'practice_name' => $practicename,
						'street_address1' => $street_address1,
						'city' => $city,
						'state' => $state,
						'zip' => $zip,
						'documents_dir' => $practice->documents_dir,
						'version' => $practice->version,
						'active' => 'Y',
						'fax_type' => '',
						'vivacare' => '',
						'patient_centric' => 'yp',
						'smtp_user' => $practice->smtp_user,
						'smtp_pass' => $practice->smtp_pass
					);
					$practice_id = DB::table('practiceinfo')->insertGetId($practice_data);
					$this->audit('Add');
				} else {
					Session::put('practice_npi_array', implode(',', $practice_npi_array_null));
					Session::put('firstname', $firstname);
					Session::put('lastname', $lastname);
					Session::put('username', $oidc->requestUserInfo('sub'));
					Session::put('middle', $oidc->requestUserInfo('middle_name'));
					Session::put('displayname', $oidc->requestUserInfo('name'));
					Session::put('email', $email);
					Session::put('npi', $npi);
					Session::put('practice_choose', 'y');
					Session::put('uid', $oidc->requestUserInfo('sub'));
					Session::put('uma_auth_access_token', $access_token);
					return Redirect::to('practice_choose');
				}
			}
			$data = array(
				'username' => $oidc->requestUserInfo('sub'),
				'firstname' => $firstname,
				'middle' => $oidc->requestUserInfo('middle_name'),
				'lastname' => $lastname,
				'displayname' => $oidc->requestUserInfo('name'),
				'email' => $email,
				'group_id' => '2',
				'active'=> '1',
				'practice_id' => $practice_id,
				'secret_question' => 'Use HIEofOne to reset your password!',
				'uid' => $oidc->requestUserInfo('sub')
			);
			$id = DB::table('users')->insertGetId($data);
			$this->audit('Add');
			$data1 = array(
				'id' => $id,
				'npi' => $npi,
				'practice_id' => $practice_id
			);
			DB::table('providers')->insert($data1);
			$this->audit('Add');
			$user1 = User::where('id', '=', $id)->first();
			Auth::login($user1);
			$practice1 = Practiceinfo::find($user1->practice_id);
			Session::put('user_id', $user1->id);
			Session::put('group_id', $user1->group_id);
			Session::put('practice_id', $user1->practice_id);
			Session::put('version', $practice1->version);
			Session::put('practice_active', $practice1->active);
			Session::put('displayname', $user1->displayname);
			Session::put('documents_dir', $practice1->documents_dir);
			Session::put('rcopia', $practice1->rcopia_extension);
			Session::put('mtm_extension', $practice1->mtm_extension);
			Session::put('patient_centric', $practice1->patient_centric);
			Session::put('uma_auth_access_token', $access_token);
			setcookie("login_attempts", 0, time()+900, '/');
			return Redirect::intended('/');
		}
	}
	
	public function uma_register_client()
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		if ($practice->patient_centric == 'y') {
			$patient = DB::table('demographics')->first();
			$dob = date('m/d/Y', strtotime($patient->DOB));
			$client_name = 'PatientNOSH for ' . $patient->firstname . ' ' . $patient->lastname . ' (DOB: ' . $dob . ')'; 
		} else {
			$client_name = 'PracticeNOSH for ' . $practice->practice_name;
		}
		$open_id_url = 'http://162.243.111.18/uma-server-webapp/';
		$url = route('uma_auth');
		$oidc = new OpenIDConnectClient($open_id_url);
		$oidc->setClientName($client_name);
		$oidc->setRedirectURL($url);
		$oidc->register(true);
		$client_id = $oidc->getClientID();
		$client_secret = $oidc->getClientSecret();
		$data = array(
			'uma_client_id' => $client_id,
			'uma_client_secret' => $client_secret
		);
		DB::table('practiceinfo')->where('practice_id', '=', '1')->update($data);
		$this->audit('Update');
		return Redirect::intended('/');
	}
	
	public function uma_logout()
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$url = route('uma_logout');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->setAccessToken(Session::get('uma_auth_access_token'));
		$oidc->revoke();
		Session::forget('uma_auth_access_token');
		return Redirect::intended('logout');
	}
	
	public function oidc_logout()
	{
		$open_id_url = 'https://noshchartingsystem.com/openid-connect-server-webapp/';
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$url = route('oidc_logout');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->setAccessToken(Session::get('oidc_auth_access_token'));
		$oidc->revoke();
		Session::forget('oidc_auth_access_token');
		return Redirect::intended('logout');
	}
}
