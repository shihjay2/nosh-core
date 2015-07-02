<?php

use Illuminate\Support\MessageBag;
class MobileController extends BaseController {

	/**
	* Authentication of users
	*/
	
	protected $layout = 'layouts.mobile';
	
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
						return Redirect::intended('mobile');
					} else {
						if ($user->group_id != '100' && $user->group_id != '1') {
							$pid = DB::table('demographics')->first();
							$this->setpatient($pid->pid);
							return Redirect::intended('chart');
						} else {
							return Redirect::intended('mobile');
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
			return Redirect::to("login_mobile")->withInput($data);
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
			$practices = Practiceinfo::all();
			$practices_array = array();
			if ($practices) {
				foreach ($practices as $practice_row) {
					$practices_array[$practice_row->practice_id] = $practice_row->practice_name;
				}
			}
			$data['practices'] = Form::select('practice_id', $practices_array, null, array('id'=>'practice_id'));
			if((array_key_exists('login_attempts', $_COOKIE)) && ($_COOKIE['login_attempts'] >= 5)){
				$data['attempts'] = "You have reached the number of limits to login.  Wait 15 minutes then try again.";
				$this->layout->style = HTML::style('css/mobile.css');
				$this->layout->script = $this->js_assets('base',true);
				//$this->layout->script .= HTML::script('/js/login.js');
				$this->layout->content = View::make('mobile.login', $data);
			} else {
				if(!array_key_exists('login_attempts', $_COOKIE)) {
					setcookie("login_attempts", 0, time()+900, '/');
				}
				$this->layout->style = HTML::style('css/mobile.css');
				$this->layout->script = $this->js_assets('base',true);
				//$this->layout->script .= HTML::script('/js/login.js');
				$this->layout->content = View::make('mobile.login', $data);
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
		$this->layout->script = $this->js_assets('base',true);
		$this->layout->content = View::make('mobile.logout');
	}
	
	public function dashboard()
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$user_id = Session::get('user_id');
		$result = User::find($user_id);
		$practice_id = Session::get('practice_id');
		$fax_query = Received::where('practice_id', '=', $practice_id)->count();
		$displayname = $result->displayname;
		$from = $displayname . ' (' . $user_id . ')';
		$data['header'] = $this->mobile_header_build(Session::get('displayname'));
		if(Session::get('group_id') == '2') {
			$data1['number_messages'] = Messaging::where('mailbox', '=', $user_id)->count();
			$data1['number_documents'] = Scans::where('practice_id', '=', $practice_id)->count() + $fax_query;
			$data1['number_appts'] = $this->getNumberAppts($user_id);
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
			$data1['number_drafts'] = $query1 + $query2;
			$data1['number_reminders'] = DB::table('alerts')
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
			$data1['number_bills'] = Encounters::where('bill_submitted', '=', 'No')->where('user_id', '=', $user_id)->count();
			$data1['number_tests'] = Tests::whereNull('pid')->where('practice_id', '=', $practice_id)->count();
		}
		$data['content'] = View::make('mobile.home_content', $data1)->render();
		$left_panel_array = array(
			array('Schedule', 'mobile_schedule'),
			array('Inbox', 'mobile_inbox')
		);
		if(Session::get('group_id') != '100') {
			$left_panel_array[] = array('Drafts', 'mobile_drafts');
			$left_panel_array[] = array('Alerts', 'mobile_alerts');
			if(Session::get('patient_centric') == 'n') {
				$left_panel_array[] = array('Scans', 'mobile_scan');
				if ($practice->fax_type != "") {
					$left_panel_array[] = array('Faxes', 'mobile_fax');
				}
			}
		}
		$data['left_panel'] = $this->mobile_menu_build($left_panel_array, "left_panel_list", 'mobile_click_home');
		$data['right_panel'] = '';
		$this->layout->style = HTML::style('css/mobile.css');
		$this->layout->script = $this->js_assets('base',true);
		$this->layout->content = View::make('mobile.home', $data);
	}
	
	public function chart_mobile($pid)
	{
		$oldpid = Session::get('pid');
		if ($oldpid != '') {
			Session::forget('age');
			Session::forget('ptname');
			Session::forget('agealldays');
			Session::forget('gender');
			Session::forget('pid');
			Session::forget('eid');
		}	
		$this->setpatient($pid);
		
		$data['header'] = $this->mobile_header_build(Session::get('ptname'));
		$data['content'] = '';
		$left_panel_array = array(
			array('Demographics', 'mobile_demographics-list'),
			array('Issues', 'mobile_issues-list'),
			array('Medications', 'mobile_medications-list'),
			array('Supplements', 'mobile_supplements-list'),
			array('Immunizations', 'mobile_immunizations-list'),
			array('Allergies', 'mobile_allergies-list'),
			array('Alerts', 'mobile_alerts-list')
		);
		$data['left_panel'] = $this->mobile_menu_build($left_panel_array, "left_panel_list", 'mobile_click_chart');
		$data['right_panel'] = '';
		$this->layout->style = HTML::style('css/mobile.css');
		$this->layout->script = $this->js_assets('base',true);
		$this->layout->content = View::make('mobile.chart', $data);
	}
	
	public function submitdata($type)
	{
	
	}
	
	public function editpage($type, $index, $id)
	{
		$data['content'] = '';
		// Search Bar
		if ($type == 'issues') {
			$data['content'] .= '<ul id="searchicd" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search ICD Code..." data-filter-theme="a"></ul>';
		}
		if ($type == 'medications' || $type == 'allergies') {
			$data['content'] .= '<ul id="searchmed" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Medication..." data-filter-theme="a"></ul>';
			$data['content'] .= Form::select('searchdose', array(), null, array('id'=>'searchdose'));
		}
		if ($type == 'supplements' || $type == 'allergies') {
			$data['content'] .= '<ul id="searchsupplement" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Supplement..." data-filter-theme="a"></ul>';
		}
		if ($type == 'immunizations') {
			$data['content'] .= '<ul id="searchimm" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search Immunization..." data-filter-theme="a"></ul>';
		}
		$data['content'] .= '<form action="' . action('MobileController@submitdata', array($type)) . '" method="POST">';
		if ($id == '') {
			$data['content'] .= Form::hidden($index, null, array('required'));
		} else {
			$result = DB::table($type)->where($index, '=', $id)->first();
			$data['content'] .= Form::hidden($index, $id, array('required'));
		}
		if ($type == 'issues') {
			if ($id == '') {
				$issue = [
					'issue' => null,
					'type' => null,
					'issue_date_active' => null
				];
			} else {
				$issue = [
					'issue' => $result->issue,
					'type' => $result->type,
					'issue_date_active' => date('Y-m-d', $this->human_to_unix($result->issue_date_active))
				];
			}
			$data['content'] .= Form::label('issue', 'Issue');
			$data['content'] .= Form::text('issue', $issue['issue'], array('required'));
			$data['content'] .= Form::label('type', 'Type');
			$data['content'] .= Form::select('type', array('Problem List'=>'Problem List', 'Medical History'=>'Medical History', 'Surgical History'=>'Surgical History'), $issue['type'], array('required'));
			$data['content'] .= Form::label('issue_date_active', 'Date Active');
			$data['content'] .= Form::input('date', 'issue_date_active', $issue['issue_date_active'], array('required'));
			// Buttons
			$data['content'] .= Form::submit('Save', array('class'=>'ui-btn'));
			$data['content'] .= Form::button('Cancel', array('class'=>'cancel_edit ui-btn'));
			if ($id != '') {
				$data['content'] .= Form::button('Inactivate', array('class'=>'inactivate_edit ui-btn', 'data-nosh-table'=>'issues', 'data-nosh-index'=>$index, 'data-nosh-id'=>$id));
				$data['content'] .= Form::button('Delete', array('class'=>'delete_edit ui-btn', 'data-nosh-table'=>'issues', 'data-nosh-index'=>$index, 'data-nosh-id'=>$id));
			}
		}
		$data['content'] .= '</form>';
		$data['header'] = $this->mobile_header_build(Session::get('ptname'));
		$this->layout->style = HTML::style('css/mobile.css');
		$this->layout->script = $this->js_assets('base',true);
		$this->layout->content = View::make('mobile.editpage', $data);
	}
}
