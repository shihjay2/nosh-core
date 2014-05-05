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
		$this->layout->style = '';
		$this->layout->script = '';
		$this->layout->content = '';
		if(Session::get('group_id') == '1') {
			$this->layout->script .= HTML::script('/js/dashboard.js');
			$this->layout->script .= HTML::script('/js/setup.js');
			$this->layout->script .= HTML::script('/js/users.js');
			$this->layout->script .= HTML::script('/js/extensions.js');
			$this->layout->script .= HTML::script('/js/schedule_admin.js');
			$this->layout->script .= HTML::script('/js/update.js');
			$this->layout->script .= HTML::script('/js/logs.js');
			$this->layout->script .= HTML::script('/js/schedule.js');
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('setup')->render();
			$this->layout->content .= View::make('users')->render();
			$this->layout->content .= View::make('extensions')->render();
			$this->layout->content .= View::make('schedule_admin')->render();
			$this->layout->content .= View::make('update')->render();
			$this->layout->content .= View::make('logs')->render();
			$this->layout->content .= View::make('schedule')->render();
		}
		if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') {
			$this->layout->script .= HTML::script('/js/searchbar.js');
			$this->layout->script .= HTML::script('/js/dashboard.js');
			$this->layout->script .= HTML::script('/js/demographics.js');
			$this->layout->script .= HTML::script('/js/options.js');
			$this->layout->script .= HTML::script('/js/messaging.js');
			$this->layout->script .= HTML::script('/js/schedule.js');
			$this->layout->script .= HTML::script('/js/billing.js');
			$this->layout->script .= HTML::script('/js/financial.js');
			$this->layout->script .= HTML::script('/js/office.js');
			$this->layout->content .= View::make('search', $this->getSearchData())->render();
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('demographics')->render();
			$this->layout->content .= View::make('options')->render();
			$this->layout->content .= View::make('messaging', $data1)->render();
			$this->layout->content .= View::make('schedule')->render();
			$this->layout->content .= View::make('billing')->render();
			$this->layout->content .= View::make('financial')->render();
			$this->layout->content .= View::make('office')->render();
		}
		if(Session::get('group_id') == '100') {
			$this->layout->script .= HTML::script('/js/dashboard.js');
			$this->layout->script .= HTML::script('/js/demographics.js');
			$this->layout->script .= HTML::script('/js/messaging.js');
			$this->layout->script .= HTML::script('/js/schedule.js');
			$this->layout->script .= HTML::script('/js/issues.js');
			$this->layout->script .= HTML::script('/js/encounters.js');
			$this->layout->script .= HTML::script('/js/medications.js');
			$this->layout->script .= HTML::script('/js/supplements.js');
			$this->layout->script .= HTML::script('/js/allergies.js');
			$this->layout->script .= HTML::script('/js/immunizations.js');
			$this->layout->script .= HTML::script('/js/documents.js');
			$this->layout->script .= HTML::script('/js/forms.js');
			$this->layout->script .= HTML::script('/js/graph.js');
			$this->layout->content .= View::make('dashboard', $data)->render();
			$this->layout->content .= View::make('demographics')->render();
			$this->layout->content .= View::make('messaging', $data1)->render();
			$this->layout->content .= View::make('schedule')->render();
			$this->layout->content .= View::make('issues')->render();
			$this->layout->content .= View::make('encounters')->render();
			$this->layout->content .= View::make('medications')->render();
			$this->layout->content .= View::make('supplements')->render();
			$this->layout->content .= View::make('allergies')->render();
			$this->layout->content .= View::make('immunizations')->render();
			$this->layout->content .= View::make('documents')->render();
			$this->layout->content .= View::make('forms')->render();
			$this->layout->content .= View::make('graph')->render();
		}
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
}
