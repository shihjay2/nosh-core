<?php

use Illuminate\Support\MessageBag;
class LoginController extends BaseController {

	/**
	* Authentication of users
	*/

	protected $layout = 'layouts.layout2';

	public function action($type='all')
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
							$url_hieofoneas = str_replace('/nosh', '/resources/' . $practice->uma_client_id, URL::to('/'));
							Session::put('url_hieofoneas', $url_hieofoneas);
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
			if ($type == 'provider') {
				$data['pnosh_provider'] = 'y';
			} else {
				$data['pnosh_provider'] = 'n';
			}
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
		$open_id_url = 'http://noshchartingsystem.com/oidc';
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
		$open_id_url = 'http://noshchartingsystem.com/oidc';
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->openidconnect_client_id;
		$client_secret = $practice->openidconnect_client_secret;
		$url = route('oidc');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->addScope('openid');
		$oidc->addScope('email');
		$oidc->addScope('profile');
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
			// If patient-centric, confirm if user request is registered to pNOSH first
			if ($practice->patient_centric == 'y') {
				// Flush out all previous errored attempts.
				if (Session::has('uma_error')) {
					Session::forget('uma_error');
				}
				// Check if there is an invite first
				$invite_query = DB::table('uma_invitation')->where('email', '=', $email)->where('invitation_timeout', '>', time())->first();
				if (!$invite_query) {
					// No invitation, expired invitation, or access
					return Redirect::to('uma_invitation_request');
				}
				// Add resources associated with new provider user to pNOSH UMA Server
				$resource_set_id_arr = explode(',', $invite_query->resource_set_ids);
				foreach ($resource_set_id_arr as $resource_set_id) {
					$uma_query = DB::table('uma')->where('resource_set_id', '=', $resource_set_id)->get();
					$scopes = array();
					if ($uma_query) {
						// Register all scopes for resource sets for now
						foreach ($uma_query as $uma_row) {
							$scopes[] = $uma_row->scope;
						}
					}
					$this->uma_policy($resource_set_id, $email, $invite_query->name, $scopes);
				}
				// Remove invite
				DB::table('uma_invitation')->where('id', '=', $invite_query->id)->delete();
				$this->audit('Delete');
				// Get Practice NPI from Oauth credentials and check if practice already loaded
				$practice_npi = $oidc->requestUserInfo('practice_npi');
				$practice_id = false;
				$practice_npi_array_null = array();
				if ($practice_npi != '') {
					$practice_npi_array = explode(' ', $practice_npi);
					foreach ($practice_npi_array as $practice_npi_item) {
						$practice_query = DB::table('practiceinfo')->where('npi', '=', $practice_npi_item)->first();
						if ($practice_query) {
							$practice_id = $practice_query->practice_id;
						} else {
							$practice_npi_array_null[] = $practice_npi_item;
						}
					}
				} else {
					Session::put('uma_error', 'No Practice NPI registered.  Please have one registered on mdNOSH to continue.');
					return Redirect::to('uma_invitation_request');
				}
				if ($practice_id == false) {
					// No practice is registered to pNOSH yet so let's add it
					if (count($practice_npi_array_null) == 1) {
						// Only 1 NPI associated with provider, great!
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
						// Ask for provider to choose which practice to link with pNOSH
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

				// Finally, add user to pNOSH
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
			} else {
				// No registered mdNOSH user for this NOSH instance - punt back to login page.
				return Redirect::intended('/');
			}
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
			if (Session::has('npi')) {
				$npi = Session::get('npi');
			} else {
				$npi = Input::get('practice_npi_select');
			}
			$data1 = array(
				'id' => $id,
				'npi' => $npi,
				'practice_id' => $practice_id
			);
			DB::table('providers')->insert($data1);
			$this->audit('Add');
			//$this->syncuser(Session::get('oidc_auth_access_token'));
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
					if (Session::has('practice_npi_array')) {
						$practice_npi_array1 = explode(',', Session::get('practice_npi_array'));
						$form_select_array = array();
						foreach ($practice_npi_array1 as $practice_npi_item1) {
							$form_select_array[$practice_npi_item1] = $practice_npi_item1;
						}
						$arr['page_comment'] = "<div align='center'>Your identity has more than one associated practice NPI's.<br>Choose a practice NPI you want to associate with this patient's NOSH service.<br><br></div>";
						$arr['practice_npi_select'] = '<div class="pure-control-group">';
						$arr['practice_npi_select'] .= '<label for="practice_npi_select">Practice NPI:</label>';
						$arr['practice_npi_select'] .= Form::select('practice_npi_select', $form_select_array, null, array('id'=>'practice_npi_select','required','style'=>'width:90%','class'=>'text'));
						$arr['practice_npi_select'] .= '</div><br><br>';
						$arr['button'] = '<input type="submit" id="practice_submit_button" value="Select Practice" name="select practice" class="ui-button ui-state-default ui-corner-all"/>';
					} else {
						$arr['page_comment'] = "<div align='center'>Enter your NPI and a practice NPI you want to associate with this patient's NOSH service.<br>You can verify your NPI number <a href='http://npinumberlookup.org/' target='_blank'>here</a><br><br></div>";
						$arr['practice_npi_select'] = '<div class="pure-control-group" align="center">';
						$arr['practice_npi_select'] .= '<label for="practice_npi_select">NPI:</label>';
						$arr['practice_npi_select'] .= Form::text('practice_npi_select', null, array('id'=>'practice_npi_select','required','style'=>'width:90%','class'=>'text'));
						$arr['practice_npi_select'] .= '</div><br><br>';
						$arr['button'] = '<input type="submit" id="practice_submit_button" value="Add Practice" name="select practice" class="ui-button ui-state-default ui-corner-all"/>';
					}
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

	public function uma_invitation_request()
	{
		$this->layout->style = $this->css_assets();
		$this->layout->script = $this->js_assets('base');
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$arr['email'] = $practice->email . '?Subject=Invitation%20Request';
		Session::put('version', $practice->version);
		$this->layout->content = View::make('uma_invitation_request', $arr);
	}

	// Patient-centric, UMA login
	public function uma_auth()
	{
		$open_id_url = str_replace('/nosh', '', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$url = route('uma_auth');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		if ($practice->uma_refresh_token == '') {
			$oidc->addScope('openid');
			$oidc->addScope('email');
			$oidc->addScope('profile');
			$oidc->addScope('offline_access');
			$oidc->addScope('uma_protection');
		} else {
			$oidc->addScope('openid');
			$oidc->addScope('email');
			$oidc->addScope('profile');
		}
		$oidc->authenticate(true);
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
			Session::put('uma_auth_access_token', $access_token);
			$url_hieofoneas = str_replace('/nosh', '/resources/' . $practice->uma_client_id, URL::to('/'));
			Session::put('url_hieofoneas', $url_hieofoneas);
			setcookie("login_attempts", 0, time()+900, '/');
			return Redirect::intended('/');
		} else {
			$practice_npi = $npi;
			$practice_id = false;
			if ($practice_npi != '') {
				$practice_query = DB::table('practiceinfo')->where('npi', '=', $practice_npi)->first();
				if ($practice_query) {
					$practice_id = $practice_query->practice_id;
				}
				if ($practice_id == false) {
					$url = 'http://docnpi.com/api/index.php?ident=' . $practice_npi . '&is_ident=true&format=aha';
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
				}
			} else {
				return Redirect::to('uma_invitation_request');
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
			// $practice_npi = $oidc->requestUserInfo('practice_npi');
			// $practice_id = false;
			// if ($practice_npi != '') {
			// 	$practice_npi_array = explode(',', $practice_npi);
			// 	$practice_npi_array_null = array();
			// 	foreach ($practice_npi_array as $practice_npi_item) {
			// 		$practice_query = DB::table('practiceinfo')->where('npi', '=', $practice_npi_item)->first();
			// 		if ($practice_query) {
			// 			$practice_id = $practice_query->practice_id;
			// 		} else {
			// 			$practice_npi_array_null[] = $practice_npi_item;
			// 		}
			// 	}
			// }
			// if ($practice_id == false) {
			// 	if (count($practice_npi_array_null) == 1) {
			// 		$url = 'http://docnpi.com/api/index.php?ident=' . $practice_npi_array_null[0] . '&is_ident=true&format=aha';
			// 		$ch = curl_init();
			// 		curl_setopt($ch,CURLOPT_URL, $url);
			// 		curl_setopt($ch,CURLOPT_FAILONERROR,1);
			// 		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			// 		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			// 		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
			// 		$data1 = curl_exec($ch);
			// 		curl_close($ch);
			// 		$html = new Htmldom($data1);
			// 		$practicename = '';
			// 		$address = '';
			// 		$street_address1 = '';
			// 		$city = '';
			// 		$state = '';
			// 		$zip = '';
			// 		if (isset($html)) {
			// 			$li = $html->find('li',0);
			// 			if (isset($li)) {
			// 				$nomatch = $li->innertext;
			// 				if ($nomatch != ' no matching results ') {
			// 					$name_item = $li->find('span[class=org]',0);
			// 					$practicename = $name_item->innertext;
			// 					$address_item = $li->find('span[class=address]',0);
			// 					$address = $address_item->innertext;
			// 				}
			// 			}
			// 		}
			// 		if ($address != '') {
			// 			$address_array = explode(',', $address);
			// 			if (isset($address_array[0])) {
			// 				$street_address1 = trim($address_array[0]);
			// 			}
			// 			if (isset($address_array[1])) {
			// 				$zip = trim($address_array[1]);
			// 			}
			// 			if (isset($address_array[2])) {
			// 				$city = trim($address_array[2]);
			// 			}
			// 			if (isset($address_array[3])) {
			// 				$state = trim($address_array[3]);
			// 			}
			// 		}
			// 		$practice_data = array(
			// 			'npi' => $practice_npi_array_null[0],
			// 			'practice_name' => $practicename,
			// 			'street_address1' => $street_address1,
			// 			'city' => $city,
			// 			'state' => $state,
			// 			'zip' => $zip,
			// 			'documents_dir' => $practice->documents_dir,
			// 			'version' => $practice->version,
			// 			'active' => 'Y',
			// 			'fax_type' => '',
			// 			'vivacare' => '',
			// 			'patient_centric' => 'yp',
			// 			'smtp_user' => $practice->smtp_user,
			// 			'smtp_pass' => $practice->smtp_pass
			// 		);
			// 		$practice_id = DB::table('practiceinfo')->insertGetId($practice_data);
			// 		$this->audit('Add');
			// 	} else {
			// 		Session::put('practice_npi_array', implode(',', $practice_npi_array_null));
			// 		Session::put('firstname', $firstname);
			// 		Session::put('lastname', $lastname);
			// 		Session::put('username', $oidc->requestUserInfo('sub'));
			// 		Session::put('middle', $oidc->requestUserInfo('middle_name'));
			// 		Session::put('displayname', $oidc->requestUserInfo('name'));
			// 		Session::put('email', $email);
			// 		Session::put('npi', $npi);
			// 		Session::put('practice_choose', 'y');
			// 		Session::put('uid', $oidc->requestUserInfo('sub'));
			// 		Session::put('uma_auth_access_token', $access_token);
			// 		return Redirect::to('practice_choose');
			// 	}
			// }
			// $data = array(
			// 	'username' => $oidc->requestUserInfo('sub'),
			// 	'firstname' => $firstname,
			// 	'middle' => $oidc->requestUserInfo('middle_name'),
			// 	'lastname' => $lastname,
			// 	'displayname' => $oidc->requestUserInfo('name'),
			// 	'email' => $email,
			// 	'group_id' => '2',
			// 	'active'=> '1',
			// 	'practice_id' => $practice_id,
			// 	'secret_question' => 'Use HIEofOne to reset your password!',
			// 	'uid' => $oidc->requestUserInfo('sub')
			// );
			// $id = DB::table('users')->insertGetId($data);
			// $this->audit('Add');
			// $data1 = array(
			// 	'id' => $id,
			// 	'npi' => $npi,
			// 	'practice_id' => $practice_id
			// );
			// DB::table('providers')->insert($data1);
			// $this->audit('Add');
			// $user1 = User::where('id', '=', $id)->first();
			// Auth::login($user1);
			// $practice1 = Practiceinfo::find($user1->practice_id);
			// Session::put('user_id', $user1->id);
			// Session::put('group_id', $user1->group_id);
			// Session::put('practice_id', $user1->practice_id);
			// Session::put('version', $practice1->version);
			// Session::put('practice_active', $practice1->active);
			// Session::put('displayname', $user1->displayname);
			// Session::put('documents_dir', $practice1->documents_dir);
			// Session::put('rcopia', $practice1->rcopia_extension);
			// Session::put('mtm_extension', $practice1->mtm_extension);
			// Session::put('patient_centric', $practice1->patient_centric);
			// Session::put('uma_auth_access_token', $access_token);
			// setcookie("login_attempts", 0, time()+900, '/');
			// return Redirect::intended('/');
		}
	}

	public function uma_register_client()
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$client_name = 'Practice NOSH for ' . $practice->practice_name;
		$patient = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		$open_id_url = str_replace('/nosh', '', $patient->url);
		$url = route('uma_get_refresh_token');
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
		DB::table('demographics_relate')->where('demographics_relate_id', '=', $patient->demographics_relate_id)->update($data);
		$this->audit('Update');
		return Redirect::to('chart');
	}

	public function uma_get_refresh_token()
	{
		$patient = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		$open_id_url = str_replace('/nosh', '', $patient->url);
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $patient->uma_client_id;
		$client_secret = $patient->uma_client_secret;
		$url = route('uma_get_refresh_token');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->addScope('openid');
		$oidc->addScope('email');
		$oidc->addScope('profile');
		$oidc->addScope('offline_access');
		$oidc->addScope('uma_authorization');
		$oidc->authenticate(true);
		$firstname = $oidc->requestUserInfo('given_name');
		$lastname = $oidc->requestUserInfo('family_name');
		$email = $oidc->requestUserInfo('email');
		$npi = $oidc->requestUserInfo('npi');
		$access_token = $oidc->getAccessToken();
		if ($oidc->getRefreshToken() != '') {
			$refresh_data['uma_refresh_token'] = $oidc->getRefreshToken();
			DB::table('demographics_relate')->where('demographics_relate_id', '=', $patient->demographics_relate_id)->update($refresh_data);
			$this->audit('Update');
		}
		return Redirect::to('chart');
	}

	public function uma_logout()
	{
		$open_id_url = str_replace('/nosh', '', URL::to('/'));
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
		$open_id_url = 'http://noshchartingsystem.com/oidc';
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

	public function google_auth()
	{
		$file = File::get(__DIR__."/../../.google");
		$file_arr = json_decode($file, true);
		$client_id = $file_arr['web']['client_id'];
		$client_secret = $file_arr['web']['client_secret'];
		$open_id_url = 'https://accounts.google.com';
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$url = route('google_auth');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->addScope('openid');
		$oidc->addScope('email');
		$oidc->addScope('profile');
		$oidc->authenticate();
		$name = $oidc->requestUserInfo('name');
		$email = $oidc->requestUserInfo('email');
		//$npi = $oidc->requestUserInfo('npi');
		$access_token = $oidc->getAccessToken();
		$user = User::where('uid', '=', $oidc->requestUserInfo('sub'))->first();
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
			// If patient-centric, confirm if user request is registered to pNOSH first
			if ($practice->patient_centric == 'y') {
				// Flush out all previous errored attempts.
				if (Session::has('uma_error')) {
					Session::forget('uma_error');
				}
				// Check if there is an invite first
				$invite_query = DB::table('uma_invitation')->where('email', '=', $email)->where('invitation_timeout', '>', time())->first();
				if (!$invite_query) {
					// No invitation, expired invitation, or access
					return Redirect::to('uma_invitation_request');
				}
				$name_arr = explode(' ', $invite_query->name);
				// Add resources associated with new provider user to pNOSH UMA Server
				$resource_set_id_arr = explode(',', $invite_query->resource_set_ids);
				foreach ($resource_set_id_arr as $resource_set_id) {
					$uma_query = DB::table('uma')->where('resource_set_id', '=', $resource_set_id)->get();
					$scopes = array();
					if ($uma_query) {
						// Register all scopes for resource sets for now
						foreach ($uma_query as $uma_row) {
							$scopes[] = $uma_row->scope;
						}
					}
					$this->uma_policy($resource_set_id, $email, $invite_query->name, $scopes);
				}
				// Remove invite
				DB::table('uma_invitation')->where('id', '=', $invite_query->id)->delete();
				$this->audit('Delete');
				Session::put('firstname', $name_arr[0]);
				Session::put('lastname', $name_arr[1]);
				Session::put('username', $oidc->requestUserInfo('sub'));
				Session::put('middle', '');
				Session::put('displayname', $name);
				Session::put('email', $email);
				Session::put('practice_choose', 'y');
				Session::put('uid', $oidc->requestUserInfo('sub'));
				Session::put('oidc_auth_access_token', $access_token);
				return Redirect::to('practice_choose');
			} else {
				// No registered mdNOSH user for this NOSH instance - punt back to login page.
				return Redirect::intended('/');
			}
		}
	}

	public function googleoauth()
	{
		$file = File::get(__DIR__."/../../.google");
		$file_arr = json_decode($file, true);
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$client_id = $file_arr['web']['client_id'];
		$client_secret = $file_arr['web']['client_secret'];
		$url = Request::URL();
		$google = new Google_Client();
		$google->setRedirectUri($url);
		$google->setApplicationName('NOSH ChartingSystem');
		$google->setClientID($client_id);
		$google->setClientSecret($client_secret);
		$google->setAccessType('offline');
		$google->setApprovalPrompt('force');
		$google->setScopes(array('https://mail.google.com/'));
		if (isset($_REQUEST["code"])) {
			$credentials = $google->authenticate($_GET['code']);
			$result = json_decode($credentials, true);
			$data['google_refresh_token'] = $result['refresh_token'];
			DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
			return Redirect::intended('/');
		} else {
			$authUrl = $google->createAuthUrl();
			header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
			exit;
		}
	}

	public function reset_demo()
	{
		$practice = Practiceinfo::find('1');
		// $patients = DB::table('demographics')->get();
		// foreach ($patients as $patient) {
		// 	$directory = $practice->documents_dir . $patient->pid;
		// 	$this->deltree($directory, false);
		// }
		$config_file = __DIR__."/../../.env.php";
		$config = require($config_file);
		$file = '/noshdocuments/demo.sql';
		$file1 = '/noshdocuments/demo_oidc.sql';
		// $file1 = '/noshdocuments/demo_oic.sql';
		$command = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " nosh < " . $file;
		// $command1 = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " oic_production < " . $file1;
		$command1 = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " oidc < " . $file1;
		system($command);
		system($command1);
		Auth::logout();
		Session::flush();
		//$open_id_url = str_replace('/nosh', '', URL::to('/'));
		$mdnosh_url = 'http://noshchartingsystem.com/oidc/reset_demo';
		return Redirect::to($mdnosh_url);
	}
}
