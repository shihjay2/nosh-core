<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	protected $layout = 'layouts.layout2';

	public function dashboard()
	{
		$user_id = Session::get('user_id');
		$practice_id = Session::get('practice_id');
		$data['practiceinfo'] = Practiceinfo::find($practice_id);
		$result = User::find($user_id);
		$data['displayname'] = $result->displayname;
		$displayname = $result->displayname;
		$fax_query = Received::where('practice_id', '=', $practice_id)->count();
		$from = $displayname . ' (' . $user_id . ')';
		if(Session::get('group_id') == '2') {
			$data['number_messages'] = Messaging::where('mailbox', '=', $user_id)->count();
			$data['number_documents'] = Scans::where('practice_id', '=', $practice_id)->count() + $fax_query;
			$data['number_appts'] = $this->getNumberAppts($user_id);
			$query1 = DB::table('t_messages')
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
				->count();
			$query2 = DB::table('encounters')
				->join('demographics', 'encounters.pid', '=', 'demographics.pid')
				->where('encounters.encounter_provider', '=', $displayname)
				->where('encounters.encounter_signed', '=', 'No')
				->count();
			$data['number_drafts'] = $query1 + $query2;
			$data['number_reminders'] = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $user_id)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array) {
					$query_array->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
				})
				->count();
			$data['number_bills'] = Encounters::where('bill_submitted', '=', 'No')->where('user_id', '=', $user_id)->count();
			$data['number_tests'] = Tests::whereNull('pid')->where('practice_id', '=', $practice_id)->count();
			if($data['practiceinfo']->mtm_extension == 'y') {
				$mtm_users_array = explode(",", $data['practiceinfo']->mtm_alert_users);
				if (in_array($user_id, $mtm_users_array)) {
					$data['mtm_alerts'] = Alerts::where('alert_date_complete', '=', '0000-00-00 00:00:00')
						->where('alert_reason_not_complete', '=', '')
						->where('alert', '=', 'Medication Therapy Management')
						->where('practice_id', '=', $practice_id)
						->count();
					$data['mtm_alerts_status'] = "y";
				} else {
					$data['mtm_alerts_status'] = "n";
				}
			} else {
				$data['mtm_alerts_status'] = "n";
			}
			$data['vaccine_supplement_alert'] = $this->vaccine_supplement_alert($practice_id);
		}
		if(Session::get('group_id') == '3') {
			$data['number_messages'] = Messaging::where('mailbox', '=', $user_id)->count();
			$data['number_documents'] = Scans::where('practice_id', '=', $practice_id)->count() + $fax_query;
			$data['number_drafts'] = DB::table('t_messages')
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
				->count();
			$data['number_reminders'] = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $user_id)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array) {
					$query_array->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
				})
				->count();
			$data['number_bills'] = Encounters::where('bill_submitted', '=', 'No')->where('practice_id', '=', $practice_id)->count();
			$data['number_tests'] = Tests::whereNull('pid')->where('practice_id', '=', $practice_id)->count();
			$data['vaccine_supplement_alert'] = $this->vaccine_supplement_alert($practice_id);
		}
		if(Session::get('group_id') == '4') {
			$data['number_messages'] = Messaging::where('mailbox', '=', $user_id)->count();
			$data['number_bills'] = Encounters::where('bill_submitted', '=', 'No')->where('practice_id', '=', $practice_id)->count();
			$data['number_documents'] = Scans::where('practice_id', '=', $practice_id)->count() + $fax_query;
		}
		if(Session::get('group_id') == '100') {
			$row = Demographics_relate::where('id', '=', $user_id)->first();
			Session::put('pid', $row->pid);
		}
		if(Session::get('group_id') == '1') {
			if ($practice_id == '1') {
				$data['saas_admin'] = 'y';
			} else {
				$data['saas_admin'] = 'n';
			}
			if (Session::get('patient_centric') != 'y') {
				$users = DB::table('users')->where('group_id', '=', '2')->where('practice_id', '=', Session::get('practice_id'))->first();
				if ($users) {
					$data['users_needed'] = 'n';
				} else {
					$data['users_needed'] = 'y';
				}
			} else {
				$data['users_needed'] = 'n';
			}
			if (Session::get('patient_centric') != 'y') {
				$schedule = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->whereNull('minTime')->first();
				if ($schedule) {
					$data['schedule_needed'] = 'y';
				} else {
					$data['schedule_needed'] = 'n';
				}
			} else {
				$data['schedule_needed'] = 'n';
			}
		}
		if ($data['practiceinfo']->weekends == '1') {
			$data['weekends'] = 'true';
		} else {
			$data['weekends'] = 'false';
		}
		$data['minTime'] = ltrim($data['practiceinfo']->minTime,"0");
		$data['maxTime'] = ltrim($data['practiceinfo']->maxTime,"0");
		if (Session::get('group_id') == '2') {
			$provider = Providers::find(Session::get('user_id'));
			$data['schedule_increment'] = $provider->schedule_increment;
		} else {
			$data['schedule_increment'] = '15';
		}
		if (!Session::get('encounter_active')) {
			Session::put('encounter_active', 'n');
		}
		if ($data['practiceinfo']->fax_type != "") {
			$data1['fax'] = true;
		} else {
			$data1['fax'] = false;
		}
		$this->layout->style = $this->css_assets();
		$this->layout->script = $this->js_assets('home');
		$this->layout->content = '';
		if(Session::get('group_id') == '1') {
			$this->layout->content .= View::make('search', $this->getSearchData())->render();
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('setup')->render();
			$this->layout->content .= View::make('users')->render();
			$this->layout->content .= View::make('extensions', $data)->render();
			$this->layout->content .= View::make('schedule_admin')->render();
			$this->layout->content .= View::make('update')->render();
			$this->layout->content .= View::make('logs')->render();
			$this->layout->content .= View::make('schedule')->render();
		}
		if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') {
			$this->layout->content .= View::make('search', $this->getSearchData())->render();
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('demographics')->render();
			$this->layout->content .= View::make('options')->render();
			$this->layout->content .= View::make('messaging', $data1)->render();
			$this->layout->content .= View::make('schedule')->render();
			$this->layout->content .= View::make('billing')->render();
			$this->layout->content .= View::make('financial')->render();
			$this->layout->content .= View::make('office')->render();
			if (Session::get('patient_centric') == 'yp' && Session::get('group_id') == '2') {
				$this->layout->content .= View::make('setup')->render();
			}
		}
		if(Session::get('group_id') == '100') {
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('demographics')->render();
			$this->layout->content .= View::make('messaging', $data1)->render();
			$this->layout->content .= View::make('schedule')->render();
			$this->layout->content .= View::make('issues')->render();
			$this->layout->content .= View::make('encounters')->render();
			$this->layout->content .= View::make('t_messages')->render();
			$this->layout->content .= View::make('medications')->render();
			$this->layout->content .= View::make('supplements')->render();
			$this->layout->content .= View::make('allergies')->render();
			$this->layout->content .= View::make('immunizations')->render();
			$this->layout->content .= View::make('documents')->render();
			$this->layout->content .= View::make('forms')->render();
			$this->layout->content .= View::make('graph')->render();
		}
	}

	public function schedule()
	{
		$practice_id = Session::get('practice_id');
		$data['practiceinfo'] = Practiceinfo::find($practice_id);
		if ($data['practiceinfo']->weekends == '1') {
			$data['weekends'] = 'true';
		} else {
			$data['weekends'] = 'false';
		}
		$data['minTime'] = ltrim($data['practiceinfo']->minTime,"0");
		$data['maxTime'] = ltrim($data['practiceinfo']->maxTime,"0");
		$data['schedule_increment'] = '15';
		$this->layout->style = $this->css_assets();
		$this->layout->script = $this->js_assets('base');
		$this->layout->content .= View::make('schedule_widget', $data)->render();
	}

	public function view_fax($id)
	{
		$result = Received::find($id);
		$file_path = $result->filePath;
		return Response::download($file_path);
	}

	public function view_scan($id)
	{
		$result = Scans::find($id);
		$file_path = $result->filePath;
		return Response::download($file_path);
	}

	public function export_address_csv()
	{
		$query = DB::table('addressbook')->get();
		$i = 0;
		$csv = '';
		foreach ($query as $row) {
			$array_row = (array) $row;
			$array_values = array_values($array_row);
			if ($i == 0) {
				$array_key = array_keys($array_row);
				$csv .= implode(';', $array_key);
				$csv .= "\n" . implode(';', $array_values);
			} else {
				$csv .= "\n" . implode(';', $array_values);
			}
		}
		$file_path = __DIR__."/../../public/temp/" . time() . "_addressbook.txt";
		File::put($file_path, $csv);
		return Response::download($file_path);
	}

	public function print_individual_chart($pid)
	{
		$file = $this->print_chart($pid, 'file', '', 'all');
		return Response::download($file);
	}

	public function pnosh_provider_redirect()
	{
		$this->setpatient('1');
		$query = DB::table('demographics_relate')->where('practice_id', '=', Session::get('practice_id'))->where('pid', '=', '1')->first();
		if (!$query) {
			$query1 = DB::table('demographics_relate')->where('practice_id', '=', '1')->where('pid', '=', '1')->first();
			$data = array(
				'pid' => '1',
				'practice_id' => Session::get('practice_id'),
				'id' => $query1->id
			);
			DB::table('demographics_relate')->insert($data);
			$this->audit('Add');
		}
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$practice1 = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		if ($practice->google_refresh_token == '') {
			$data1 = array(
				'google_refresh_token' => $practice1->google_refresh_token
			);
			DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data1);
			$this->audit('Update');
		}
		if ($practice->email == '') {
			$data1 = array(
				'email' => $practice1->email
			);
			DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data1);
			$this->audit('Update');
		}
		return Redirect::to('chart');
	}

	public function uma_register()
	{
		if (Input::server("REQUEST_METHOD") == "POST") {
			$rules = [
				'email' => 'required|email'
			];
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				 return Redirect::to('uma_register')->withErrors($validator);
			}
			Session::forget('type');
			Session::forget('client_id');
			Session::forget('url');
			$domain = explode('@', Input::get('email'));
			// webfinger
			$url = 'https://' . $domain[1] . '/.well-known/webfinger';
			$query_string = 'resource=acct:' . Input::get('email') . '&rel=http://openid.net/specs/connect/1.0/issuer';
			$url .= '?' . $query_string ;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_FAILONERROR,1);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_TIMEOUT, 60);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,0);
			$result = curl_exec($ch);
			$result_array = json_decode($result, true);
			curl_close($ch);
			if (isset($result_array['subject'])) {
				$as_uri = $result_array['links']['href'];
				$client_name = 'mdNOSH Gateway';
				$url1 = route('uma_auth');
				$oidc = new OpenIDConnectClient($as_uri);
				$oidc->setClientName($client_name);
				$oidc->setRedirectURL($url1);
				$oidc->addScope('openid');
				$oidc->addScope('email');
				$oidc->addScope('profile');
				$oidc->addScope('address');
				$oidc->addScope('phone');
				$oidc->addScope('offline_access');
				$oidc->addScope('uma_authorization');
				$oidc->addScope('uma_protection');
				$oidc->register(true);
				$client_id = $oidc->getClientID();
				$client_secret = $oidc->getClientSecret();
				$data1 = [
					'hieofone_as_picture' => $client_id,
					'hieofone_as_client_secret' => $client_secret,
					'hieofone_as_url' => $as_uri
				];
				DB::table('demographics')->where('pid', '=', Session::get('pid'))->update($data1);
				Session::put('pnosh_client_id', $client_id);
				Session::put('pnosh_client_secret', $client_secret);
				Session::put('pnosh_url', $as_uri);
				Session::put('pnosh_id', $id);
				Session::save();
				return Redirect::to('uma_auth');
			} else {
				return Redirect::to('uma_register')->withErrors(['email' => 'Try again']);
			}
		} else {
			return View::make('uma_register');
		}
	}

	public function uma_auth(Request $request)
	{
		$url = route('uma_auth');
		$open_id_url = Session::get('pnosh_url');
		$client_id = Session::get('pnosh_client_id');
		$client_secret = Session::get('pnosh_client_secret');
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->addScope('openid');
		$oidc->addScope('email');
		$oidc->addScope('profile');
		$oidc->addScope('offline_access');
		$oidc->addScope('uma_authorization');
		$oidc->addScope('uma_protection');
		$oidc->authenticate(true);
		$refresh_data['refresh_token'] = $oidc->getRefreshToken();
		$name = $oidc->requestUserInfo('name');
		$birthday = $oidc->requestUserInfo('birthday');
		$refresh_data['hieofone_as_name'] = $name . '(DOB: ' . $birthday . ')';
		$refresh_data['hieofone_as_picture'] = $oidc->requestUserInfo('picture');
		DB::table('demographics')->where('pid', '=', Session::get('pid'))->update($refresh_data);
		$access_token = $oidc->getAccessToken();
		$data1['content'] = '<h3>Registration successful!</h3><ul>';
		$data1['content'] .= '<li>You can now invite physicians in the Directory to your pNOSH patient-centered EHR.</li>';
		$data1['content'] .= '<li>You have registered your FHIR resources in the mdNOSH EHR for sharing via your HIE of One Authorizaion Server</li>';
		$data1['content'] .= '<h3>You may now close this window or <a href="' . $request->session()->get('pnosh_url') .'/nosh">click here</a> to go to your pNOSH health record.</h3>';
		$data1['title'] = 'Patient Registration for mdNOSH Gateway Response';
		return view('home', $data1);
	}
}
