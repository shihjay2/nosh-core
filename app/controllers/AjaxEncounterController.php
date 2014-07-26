<?php

class AjaxEncounterController extends BaseController {

	/**
	* NOSH ChartingSystem Encounter Ajax Functions
	*/
	
	public function postNewEncounter()
	{
		$encounter_DOS = date('Y-m-d H:i:s', strtotime(Input::get('encounter_date') . " " . Input::get('encounter_time')));
		if (Input::get('encounter_type') != '') {
			$encounter_type = explode(",", Input::get('encounter_type'));
		} else {
			$encounter_type[0] = "";
			$encounter_type[1] = "";
		}
		if (Session::get('group_id') == '2') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = Input::get('encounter_provider');
		}
		$user_query = User::find($user_id);
		$encounter_provider = $user_query->displayname;
		$data = array(
			'pid' => Session::get('pid'),
			'appt_id' => $encounter_type[1],
			'encounter_provider' => $encounter_provider,
			'encounter_DOS' => $encounter_DOS,
			'encounter_age' => Session::get('age'),
			'encounter_type' => $encounter_type[0],
			'encounter_location' =>  Input::get('encounter_location'),
			'encounter_cc' => Input::get('encounter_cc'),
			'encounter_signed' => 'No',
			'encounter_condition' => Input::get('encounter_condition'),
			'encounter_condition_work' => Input::get('encounter_condition_work'),
			'encounter_condition_auto' => Input::get('encounter_condition_auto'),
			'encounter_condition_auto_state' => Input::get('encounter_condition_auto_state'),
			'encounter_condition_other' => Input::get('encounter_condition_other'),
			'addendum' => 'n',
			'user_id' => $user_id,
			'encounter_role' => Input::get('encounter_role'),
			'referring_provider' => Input::get('referring_provider'),
			'referring_provider_npi' => Input::get('referring_provider_npi'),
			'practice_id' => Session::get('practice_id'),
			'encounter_template' => Input::get('encounter_template'),
			'bill_complex' => Input::get('bill_complex')
		);
		$eid = DB::table('encounters')->insertGetId($data);
		$this->audit('Add');
		$data2 = array(
			'status' => 'Attended'
		);
		if ($encounter_type[1] != '') {
			DB::table('schedule')->where('appt_id', '=', $encounter_type[1])->update($data2);
			$this->audit('Update');
		}
		$data3 = array(
			'addendum_eid' => $eid
		);
		DB::table('encounters')->where('eid', '=', $eid)->update($data3);
		$this->audit('Update');
		Session::put('eid', $eid);
		Session::put('encounter_DOS', $encounter_DOS);
		Session::put('encounter_template', Input::get('encounter_template'));
		$arr['eid'] = $eid;
		$arr['message'] = "Encounter created!";
		echo json_encode($arr);
	}
	
	function postGetEncounter()
	{
		$eid = Session::get('eid');
		$result = DB::table('encounters')->where('eid', '=', $eid)->first();
		$date = strtotime($result->encounter_DOS);
		$data = (array) $result;
		$data['encounter_date'] = date('m/d/Y', $date);
		$data['encounter_time'] = date('h:i A', $date);
		$data['encounter_provider'] = $data['user_id'];
		echo json_encode($data);
	}

	public function postEditEncounter()
	{
		$eid = Session::get('eid');
		$encounter_DOS = date('Y-m-d H:i:s', strtotime(Input::get('encounter_date') . " " . Input::get('encounter_time')));
		$data = array(
			'encounter_DOS' => $encounter_DOS,
			'encounter_location' => Input::get('encounter_location'),
			'encounter_cc' => Input::get('encounter_cc'),
			'encounter_condition' => Input::get('encounter_condition'),
			'encounter_condition_work' => Input::get('encounter_condition_work'),
			'encounter_condition_auto' => Input::get('encounter_condition_auto'),
			'encounter_condition_auto_state' => Input::get('encounter_condition_auto_state'),
			'encounter_condition_other' => Input::get('encounter_condition_other'),
			'encounter_role' => Input::get('encounter_role'),
			'referring_provider' => Input::get('referring_provider'),
			'practice_id' => Session::get('practice_id'),
			'referring_provider_npi' => Input::get('referring_provider_npi'),
			'bill_complex' => Input::get('bill_complex')
		);
		DB::table('encounters')->where('eid', '=', $eid)->update($data);
		$this->audit('Update');
		echo "Update successful!";
	}
	
	public function postCheckEncounter()
	{
		$eid = Session::get('eid');
		$error = "";
		$hpi = Hpi::find($eid);
		$pe = Pe::find($eid);
		$assessment = Assessment::find($eid);
		$encounter = Encounters::find($eid);
		$billing = Billing::where('eid', '=', $eid)->first();
		if (!$hpi) {
			$error .= "Missing History of Present Illness<br>";
		}
		if ($encounter->encounter_template == 'standardmedical') {
			if (!$pe) {
				$error .= "Missing Physical Exam<br>";
			}
		}
		if (!$assessment) {
			$error .= "Missing Assessment<br>";
		}
		if (!$billing) {
			$error .= "Missing Billing<br>";
		}	
		echo $error;
	}
	
	function postSignEncounter()
	{
		$eid = Session::get('eid');
		$encounter = Encounters::find($eid);
		if ($encounter->encounter_template == 'standardmedical' && Session::get('group_id') == '3') {
			echo 'You are not allowed to sign this type of encounter!';
		} else {
			$data = array(
				'encounter_signed' => "Yes",
				'date_signed' => date('Y-m-d H:i:s', time())
			);
			DB::table('encounters')->where('eid', '=', Session::get('eid'))->update($data);
			$this->audit('Update');
			if ($encounter->encounter_template == 'standardpsych') {
				$psych_date = strtotime($encounter->encounter_DOS) + 31556926;
				$description = 'Schedule Annual Psychiatric Evaluation Appointment for ' . date('F jS, Y', $psych_date);
				$data1 = array(
					'alert' => 'Annual Psychiatric Evaluation Reminder',
					'alert_description' => $description,
					'alert_date_active' => date('Y-m-d H:i:s', time()),
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'alert_provider' => Session::get('user_id'),
					'orders_id' => '',
					'pid' => $pid,
					'practice_id' => Session::get('practice_id')
				);
				DB::table('alerts')->insert($data1);
				$this->audit('Add');
			}
			Session::forget('eid');
			Session::forget('encounter_active');
			echo 'Encounter Signed!';
		}
	}
	
	public function postDeleteEncounter()
	{
		$eid = Session::get('eid');
		$encounter = Encounters::find($eid);
		if ($encounter->encounter_template == 'standardmedical' && Session::get('group_id') == '3') {
			echo 'You are not allowed to delete this type of encounter!';
		} else {
			DB::table('encounters')->where('eid', '=', Session::get('eid'))->where('encounter_signed', '=', 'No')->delete();
			$this->audit('Delete');
			DB::table('hpi')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('ros')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('other_history')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('vitals')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('pe')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('labs')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('procedure')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('assessment')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('orders')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('plan')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('rx')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			DB::table('billing')->where('eid', '=', Session::get('eid'))->delete();
			$this->audit('Delete');
			Session::forget('eid');
			Session::forget('encounter_active');
			echo 'Encounter deleted!';
		}
	}
	
	public function getLoadtemplate()
	{
		$row = Encounters::find(Session::get('eid'));
		$data['encounter'] = $row;
		$gender = Session::get('gender');
		$result = Practiceinfo::find(Session::get('practice_id'));
		if ($result->mtm_extension == 'y') {
			$data['mtm'] = '<button type="button" id="hpi_mtm" class="nosh_button">MTM</button>';
		} else {
			$data['mtm'] = '';
		}
		$age = Session::get('agealldays');
		if ($age <= 365.25) {
			$data['birth'] = '<button type="button" id="hpi_birth_hx_template" class="nosh_button">Birth History</button>';
		} else {
			$data['birth'] = '';
		}
		if ($age <= 6574.5) {
			$data['wcc'] = '<button type="button" id="hpi_wcc" class="nosh_button">Well Child Check</button>';
			$data['cpe'] = '';
			$data['preg'] = '';
		} else {
			$data['wcc'] = '';
			$data['cpe'] = '<button type="button" id="hpi_cpe" class="nosh_button">Complete Physical</button>';
			if ($gender == 'male') {
				$data['preg'] = '';
			} else {
				$data['preg'] = '<button type="button" id="hpi_preg" class="nosh_button">Pregnancy Status</button>';
			}
		}
		if ($row->encounter_template == 'standardmedical') {
			$data['ros'] = View::make('encounters.ros')->render();
			$data['oh'] = View::make('encounters.oh')->render();
			$data1['practiceInfo'] = $result;
			$data['vitals'] = View::make('encounters.vitals', $data1)->render();
			$data['pe'] = View::make('encounters.pe')->render();
			$data['labs'] = View::make('encounters.labs')->render();
			$data['results'] = View::make('encounters.results')->render();
			$data['proc'] = View::make('encounters.proc')->render();
			$data['assessment'] = View::make('encounters.assessment')->render();
			$data2['mtm'] = $result->mtm_extension;
			$data['orders'] = View::make('encounters.orders', $data2)->render();
		}
		if ($row->encounter_template == 'clinicalsupport') {
			$data['oh'] = View::make('encounters.oh')->render();
			$data['labs'] = View::make('encounters.labs')->render();
			$data['proc'] = View::make('encounters.proc')->render();
			$data['assessment'] = View::make('encounters.assessment')->render();
			$data2['mtm'] = $result->mtm_extension;
			$data['orders'] = View::make('encounters.orders', $data2)->render();
		}
		if ($row->encounter_template == 'standardpsych' || $row->encounter_template == 'standardpsych1') {
			$data['ros'] = View::make('encounters.ros')->render();
			$data['oh'] = View::make('encounters.oh')->render();
			$data1['practiceInfo'] = $result;
			$data['vitals'] = View::make('encounters.vitals', $data1)->render();
			$data['pe'] = View::make('encounters.pe')->render();
			$data['assessment'] = View::make('encounters.assessment')->render();
			$data2['mtm'] = $result->mtm_extension;
			$data['orders'] = View::make('encounters.orders', $data2)->render();
		}
		if ($row->encounter_template == 'standardmtm') {
			$data2['mtm'] = $result->mtm_extension;
			$data['oh'] = View::make('encounters.oh')->render();
			$data1['practiceInfo'] = $result;
			$data['vitals'] = View::make('encounters.vitals', $data1)->render();
			$data['results'] = View::make('encounters.results')->render();
			$data['assessment'] = View::make('encounters.assessment')->render();
			$data['orders'] = View::make('encounters.orders', $data2)->render();
			$data['medications'] = View::make('encounters.mtm_medications')->render();
		}
		return View::make('encounters.' . $row->encounter_template, $data);
	}
	
	// HPI functions
	public function postGetHpi()
	{
		$row = DB::table('hpi')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			$data = (array) $row;
			$data['response'] = true;
		} else {
			$data['response'] = false;
		}
		echo json_encode($data);
	}
	
	public function postHpiSave($type)
	{
		$eid = Session::get('eid');
		$count = Hpi::find($eid);
		$data = array(
			'eid' => $eid,
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname'),
			$type => Input::get($type)
		);
		if ($type == 'hpi') {
			$result1 = 'History of Present Illness';
		}
		if ($type == 'situation') {
			$result1 = 'Situation';
		}
		if ($count) {
			DB::table('hpi')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			
			$result = $result1 . ' Updated!';
		} else {
			DB::table('hpi')->insert($data);
			$this->audit('Add');
			$result = $result1 . ' Added!';
		}
		echo $result;
	}
	
	public function postHpiTemplateSelectList()
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', Session::get('user_id'))
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'hpi');
		if (Session::get('agealldays') > 6574.5) {
			$query->where(function($query_array1) {
				$query_array1->where('age', '=', 'adult')
				->orWhere('age', '=', '');
			});
		}
		$result = $query->get();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			if ($row->template_name == 'Global Default') {
				if ($row->group == 'hpi_generic') {
					$name = 'Generic';
				}
				if ($row->group == 'hpi_asthma') {
					$name = 'Asthma';
				}
				if ($row->group == 'hpi_prenatal') {
					$name = 'Prenatal';
				}
				if ($row->group == 'hpi_injury') {
					$name = 'Injury';
				}
				if ($row->group == 'hpi_sports') {
					$name = 'Sports Physical';
				}
				if ($row->group == 'hpi_pain') {
					$name = 'Chronic Pain';
				}
				if ($row->group == 'hpi_wwe') {
					$name = 'Well Woman Exam';
				}
				if ($row->group == 'hpi_birthhx') {
					$name = 'Birth History';
				}
			} else {
				$name = $row->template_name;
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postSituationTemplateSelectList()
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', Session::get('user_id'))
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'situation');
		if (Session::get('agealldays') > 6574.5) {
			$query->where(function($query_array1) {
				$query_array1->where('age', 'adult')
				->orWhere('age', '');
			});
		}
		$result = $query->get();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			$name = $row->template_name;
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postGetHpiTemplate($id)
	{
		$row = Templates::find($id);
		$data = unserialize($row->array);
		echo json_encode($data);
	}
	
	// ROS functions
	public function postCheckRos()
	{
		$data['gen'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['eye'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['ent'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['resp'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['cv'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['gi'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['gu'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['mus'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['neuro'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['heme'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['endocrine'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['skin'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['wcc'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych1'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych2'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych3'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych4'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych5'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych6'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych7'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych8'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych9'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych10'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych11'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['message'] = 'Review of Systems Unchanged!';
		$row = DB::table('ros')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			if ($row->ros_gen) {
				$data['gen'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_eye) {
				$data['eye'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_ent) {
				$data['ent'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_resp) {
				$data['resp'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_cv) {
				$data['cv'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_gi) {
				$data['gi'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_gu) {
				$data['gu'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_mus) {
				$data['mus'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_neuro) {
				$data['neuro'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych) {
				$data['psych'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_heme) {
				$data['heme'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_endocrine) {
				$data['endocrine'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_skin) {
				$data['skin'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_wcc) {
				$data['wcc'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych1) {
				$data['psych1'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych2) {
				$data['psych2'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych3) {
				$data['psych3'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych4) {
				$data['psych4'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych5) {
				$data['psych5'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych6) {
				$data['psych6'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych7) {
				$data['psych7'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych8) {
				$data['psych8'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych9) {
				$data['psych9'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych10) {
				$data['psych10'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->ros_psych11) {
				$data['psych11'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			$data['message'] = 'Review of Systems Updated!';
		} else {
			
		}
		echo json_encode($data);
	}
	
	public function postRosTemplateSelectList()
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', Session::get('user_id'))
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'ros')
			->orderBy('group', 'asc');
		if (Session::get('agealldays') > 6574.5) {
			$query->where(function($query_array1) {
				$query_array1->where('age', '=', 'adult')
				->orWhere('age', '=', '');
			});
		}
		$result = $query->get();
		$data = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			$name = $row->template_name;
			$group = $row->group;
			$data[$group][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postGetRosTemplates($group, $id, $default)
	{
		if ($default == 'y' && $id == '0') {
			if (Session::get('gender') == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$row = DB::table('templates')
				->where('user_id', '=', '0')
				->where('sex', '=', $sex)
				->where('category', '=', 'ros')
				->where('group', '=', $group)
				->where('default', '=', "default")
				->first();
			$data = unserialize($row->array);
			$data1 = json_encode($data);
		} else {
			$row = Templates::find($id);
			$data = unserialize($row->array);
			$data1 = json_encode($data);
			$data1 = str_replace('ros_form_buttonset', 'ros_buttonset', $data1);
			$data1 = str_replace('ros_form', $group , $data1);
		}
		echo $data1;
	}
	
	public function postGetRosWccTemplate()
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$age = Session::get('agealldays');
		if ($age <= 60.88) {
			$id = 'ros_wccage0m';
		}
		if ($age > 60.88 && $age <= 121.76) {
			$id = 'ros_wccage2m';
		}
		if ($age > 121.76 && $age <= 182.64) {
			$id = 'ros_wccage4m';
		}
		if ($age > 182.64 && $age <= 273.96) {
			$id = 'ros_wccage6m';
		}
		if ($age > 273.96 && $age <= 365.24) {
			$id = 'ros_wccage9m';
		}
		if ($age > 365.24 && $age <= 456.6) {
			$id = 'ros_wccage12m';
		}
		if ($age > 456.6 && $age <= 547.92) {
			$id = 'ros_wccage15m';
		}
		if ($age > 547.92 && $age <= 730.48) {
			$id = 'ros_wccage18m';
		}
		if ($age > 730.48 && $age <= 1095.75) {
			$id = 'ros_wccage2';
		}
		if ($age > 1095.75 && $age <= 1461) {
			$id = 'ros_wccage3';
		}
		if ($age > 1461 && $age <= 1826.25) {
			$id = 'ros_wccage4';
		}
		if ($age > 1826.25 && $age <= 2191.44) {
			$id = 'ros_wccage5';
		}
		$row = DB::table('templates')
			->where('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'ros')
			->where('group', '=', $id)
			->where('default', '=', "default")
			->first();
		$data = unserialize($row->array);
		echo json_encode($data);
	}
	
	public function postGetDefaultRosTemplates()
	{
		$gender = Session::get('gender');
		$age = Session::get('agealldays');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'ros')
			->where('default', '=', "default")
			->orderBy('group', 'asc')
			->get();
		$data = array();
		foreach ($query as $row) {
			$group = $row->group;
			$data[$group] = unserialize($row->array);
		}
		$query1 = DB::table('templates')
			->where('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'ros')
			->where('default', '=', "y")
			->orderBy('group', 'asc')
			->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$group1 = $row1->group;
				$data1 = unserialize($row1->array);
				$data1 = str_replace('ros_form_buttonset', 'ros_buttonset', $data1);
				$data1 = str_replace('ros_form', $group1 , $data1);
				$data[$group1] = json_decode($data1);
			}
		}
		echo json_encode($data);
	}
	
	public function postGetRos()
	{
		$data = DB::table('ros')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			$data1 = (array) $data;
			echo json_encode($data1);
		} else {
			echo '';
		}
	}
	
	public function postTipRos($item)
	{
		$data = DB::table('ros')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			$data1 = (array) $data;
			if ($data1[$item] == '') {
				echo 'No entry for this item.';
			} else {
				echo nl2br($data1[$item]);
			}
		} else {
			echo 'No entry for this item.';
		}
	}
	
	public function postRosSave($item)
	{
		$count = DB::table('ros')->where('eid', '=', Session::get('eid'))->first();
		$key = "ros_" . $item;
		$data = array(
			'eid' => Session::get('eid'),
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname'),
			$key => Input::get($key)
		);
		if ($count) {
			DB::table('ros')->where('eid', '=', Session::get('eid'))->update($data); 
			$this->audit('Update');
			$result = 'Review of Systems Updated';
		} else {
			DB::table('ros')->insert($data);
			$this->audit('Add');
			$result = 'Review of Systems Added';
		}
		echo $result;
	}
	
	// Other history functions
	public function postGetOh()
	{
		$result = DB::table('other_history')->where('eid', '=', Session::get('eid'))->first();
		$data['message'] = 'n';
		if ($result) {
			$data['message'] = 'y';
			$data['response'] = $result;
		}
		echo json_encode($data);
	}
	
	public function postOhSave()
	{
		$eid = Session::get('eid');
		$count = DB::table('other_history')->where('eid', '=', $eid)->first();
		$data = array(
			'eid' => $eid,
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname'),
			'oh_pmh' => Input::get('oh_pmh'),
			'oh_psh' => Input::get('oh_psh'),
			'oh_fh' => Input::get('oh_fh')
		);
		if ($count) {
			DB::table('other_history')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Other History Updated';
		} else {
			DB::table('other_history')->insert($data);
			$this->audit('Add');
			$result = 'Other History Added';
		}
		echo $result;
	}
	
	public function postCopyIssues()
	{
		$query = Issues::where('pid', '=', Session::get('pid'))->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($query) {
			$result = '';
			foreach ($query as $row) {
				$result .= $row->issue . ',';
			}
			echo $result;
		} else {
			echo 'No';
		}
	}
	
	public function postCheckOh()
	{
		$row = DB::table('other_history')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			if ($row->oh_sh) {
				$data['sh_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['sh_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_meds) {
				$data['meds_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['meds_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_supplements) {
				$data['supplements_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['supplements_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_allergies) {
				$data['allergies_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['allergies_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_etoh) {
				$data['etoh_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['etoh_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_tobacco) {
				$data['tobacco_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['tobacco_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_drugs) {
				$data['drugs_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['drugs_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_employment) {
				$data['employment_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['employment_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_psychosocial) {
				$data['psychosocial_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['psychosocial_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_developmental) {
				$data['developmental_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['developmental_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->oh_medtrials) {
				$data['medtrials_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['medtrials_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			$data['message'] = 'Other History Updated!';
		} else {
			$data['sh_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['meds_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['supplements_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['allergies_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['etoh_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['tobacco_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['drugs_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['employment_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['psychosocial_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['developmental_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['medtrials_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['message'] = 'Other History Unchanged!';
		}
		echo json_encode($data);
	}
	
	public function postOhSave1($item)
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$encounter_provider = Session::get('displayname');
		if ($item == 'sh') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_sh' => Input::get('oh_sh')
			);
		}
		if ($item == 'etoh') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_etoh' => Input::get('oh_etoh')
			);
		}
		if ($item == 'tobacco') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_tobacco' => Input::get('oh_tobacco')
			);
		}
		if ($item == 'drugs') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_drugs' => Input::get('oh_drugs')
			);
		}
		if ($item == 'employment') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_employment' => Input::get('oh_employment')
			);
		}
		if ($item == 'psychosocial') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_psychosocial' => Input::get('oh_psychosocial')
			);
		}
		if ($item == 'developmental') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_developmental' => Input::get('oh_developmental')
			);
		}
		if ($item == 'medtrials') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_medtrials' => Input::get('oh_medtrials')
			);
		}
		if ($item == 'meds') {
			$query1 = DB::table('rx_list')
				->where('pid', '=', $pid)
				->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
				->where('rxl_date_old', '=', '0000-00-00 00:00:00')
				->get();
			$result1 = '';
			if ($query1) {
				foreach ($query1 as $row1) {
					if ($row1->rxl_sig != '') {
						$result1 .= $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_sig . ' ' . $row1->rxl_route . ' ' . $row1->rxl_frequency . ' for ' . $row1->rxl_reason . "\n";
					} else {
						$result1 .= $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_instructions . ' for ' . $row1->rxl_reason . "\n";
					}
				}
			} else {
				$result1 .= 'None.';
			}
			$result1 = trim($result1);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_meds' => $result1
			);
		}
		if ($item == 'supplements') {
			$query2 = DB::table('sup_list')
				->where('pid', '=', $pid)
				->where('sup_date_inactive', '=', '0000-00-00 00:00:00')
				->get();
			$result2 = '';
			if ($query2) {
				foreach ($query2 as $row2) {
					if ($row2->sup_sig != '') {
						$result2 .=  $row2->sup_supplement . ' ' . $row2->sup_dosage . ' ' . $row2->sup_dosage_unit . ', ' . $row2->sup_sig . ' ' . $row2->sup_route . ' ' . $row2->sup_frequency . ' for ' . $row2->sup_reason . "\n";
					} else {
						$result2 .=  $row2->sup_supplement . ' ' . $row2->sup_dosage . ' ' . $row2->sup_dosage_unit . ', ' . $row2->sup_instructions . ' for ' . $row2->sup_reason . "\n";
					}
				}
			} else {
				$result2 .= 'None.';
			}
			$result2 = trim($result2);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_supplements' => $result2
			);
		}
		if ($item == 'allergies') {
			$query3 = DB::table('allergies')
				->where('pid', '=', $pid)
				->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')
				->get();
			$result3 = '';
			if ($query3) {
				foreach ($query3 as $row3) {
					$result3 .=  $row3->allergies_med . ' - ' . $row3->allergies_reaction .  "\n";
				}
			} else {
				$result3 .= 'None.';
			}
			$result3 = trim($result3);
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_allergies' => $result3
			);
		}
		if ($item == 'results') {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'oh_results' => Input::get('oh_results')
			);
		}
		$count = DB::table('other_history')->where('eid', '=', $eid)->first();
		if ($count) {
			DB::table('other_history')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Other History Updated.';
		} else {
			DB::table('other_history')->insert($data);
			$this->audit('Add');
			$result = 'Other History Added.';
		}
		echo $result;
	}
	
	public function postTipOh($item)
	{
		$data = DB::table('other_history')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			if ($data->$item == '') {
				echo 'No entry for this item.';
			} else {
				echo nl2br($data->$item);
			}
		} else {
			echo 'No entry for this item.';
		}
	}
	
	public function postCopyOh($item)
	{
		$pid = Session::get('pid');
		$eid = Session::get('eid');
		$query = DB::table('other_history')
			->where('pid', '=', $pid)
			->where('eid', '!=', $eid)
			->orderBy('eid', 'desc');
		$data['callback'] = 'No previous encounter!';
		if ($item == 'oh') {
			$types = array('oh_pmh', 'oh_psh', 'oh_fh');
			foreach ($types as $type) {
				$query->where($type, '!=', '');
				$result = $query->first();
				if ($result) {
					$data[$type] = $result->$type;
					$data['callback'] = 'Items copied from last encounter!';
				}
			}
		} else {
			$type = 'oh_' . $item;
			$query->where($type, '!=', '');
			$result = $query->first();
			if ($result) {
				$data[$type] = $result->$type;
				$data['callback'] = 'Items copied from last encounter!';
			}
		}
		echo json_encode($data);
	}
	
	public function postEditDemographics($type)
	{
		if ($type == 'sh') {
			$data = array(
				'marital_status' => Input::get('marital_status'),
				'partner_name' => Input::get('partner_name')
			);
		}
		if ($type == 'tobacco') {
			$data = array(
				'tobacco' => Input::get('status')
			);
		}
		if ($type == 'employer') {
			$data = array(
				'employer' => Input::get('employer')
			);
		}
		if ($type == 'sex') {
			$data = array(
				'sexuallyactive' => Input::get('status')
			);
		}
		DB::table('demographics')->where('pid', '=', Session::get('pid'))->update($data);
		$this->audit('Update');
		echo "Patient information updated.";
	}
	
	// Vitals functions
	public function postVitalsSave()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$count = DB::table('vitals')->where('eid', '=', $eid)->get();
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => Session::get('displayname'),
			'weight' => Input::get('weight'),
			'height' => Input::get('height'),
			'headcircumference' => Input::get('headcircumference'),
			'BMI' => Input::get('BMI'),
			'temp' => Input::get('temp'),
			'temp_method' => Input::get('temp_method'),
			'bp_systolic' => Input::get('bp_systolic'),
			'bp_diastolic' => Input::get('bp_diastolic'),
			'bp_position' => Input::get('bp_position'),
			'pulse' => Input::get('pulse'),
			'respirations' => Input::get('respirations'),
			'o2_sat' => Input::get('o2_sat'),
			'vitals_other' => Input::get('vitals_other')
		);
		if ($count) {
			DB::table('vitals')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Vitals Updated';
		} else {
			$encounterInfo = Encounters::find($eid);
			$demographicsInfo = Demographics::find($pid);
			$a = $this->human_to_unix($encounterInfo->encounter_DOS);
			$b = $this->human_to_unix($demographicsInfo->DOB);
			$data['pedsage'] = ($a - $b)/2629743;
			$data['vitals_age'] = ($a - $b)/31556926;
			$data['vitals_date'] = $encounterInfo->encounter_DOS;
			DB::table('vitals')->insert($data);
			$this->audit('Add');
			$result = 'Vitals Added';
		}
		echo $result;
	}
	
	public function postGetVitals()
	{
		$row = DB::table('vitals')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			$row1 = (array) $row;
		} else {
			$row1 = '';
		}
		echo json_encode($row1);
	}

	public function postVitalsList()
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('vitals')
			->where('pid', '=', $pid)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('vitals')
			->where('pid', '=', $pid)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	// PE functions
	public function postGetPeTemplates($group, $id, $default)
	{
		if ($default == 'y' && $id == '0') {
			$gender = Session::get('gender');
			if ($gender == 'male') {
				$sex = 'm';
			} else {
				$sex = 'f';
			}
			$row = DB::table('templates')
				->where('user_id', '=', '0')
				->where('sex', '=', $sex)
				->where('category', '=', 'pe')
				->where('group', '=', $group)
				->where('default', '=', "default")
				->first();
			$data = unserialize($row->array);
			$data1 = json_encode($data);
		} else {
			$row = Templates::find($id);
			$data = unserialize($row->array);
			$data1 = json_encode($data);
			$data1 = str_replace('pe_form_buttonset', 'pe_buttonset', $data1);
			$data1 = str_replace('pe_form', $group , $data1);
		}
		echo $data1;
	}
	
	public function postGetDefaultPeTemplates()
	{
		$gender = Session::get('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'pe')
			->where('default', '=', "default")
			->orderBy('group', 'asc')
			->get();
		$data = array();
		foreach ($query as $row) {
			$group = $row->group;
			$data[$group] = unserialize($row->array);
		}
		$query1 = DB::table('templates')
			->where('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'pe')
			->where('default', '=', "y")
			->orderBy('group', 'asc')
			->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$group1 = $row1->group;
				$data1 = unserialize($row1->array);
				$data1 = str_replace('pe_form_buttonset', 'pe_buttonset', $data1);
				$data1 = str_replace('pe_form', $group1 , $data1);
				$data[$group1] = json_decode($data1);
			}
		}
		echo json_encode($data);
	}
	
	public function postPeTemplateSelectList()
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$query = DB::table('templates')
			->where('user_id', '=', Session::get('user_id'))
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'pe')
			->orderBy('group', 'asc');
		$result = $query->get();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			$name = $row->template_name;
			$group = $row->group;
			$data[$group][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postCheckPe()
	{
		$data['gen'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['eye'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['ent'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['neck'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['resp'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['cv'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['ch'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['gi'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['gu'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['lymph'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['ms'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['neuro'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['psych'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['skin'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['constitutional'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['mental'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['message'] = 'Physical Examination Unchanged!';
		$row = DB::table('pe')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			if ($row->pe_gen1) {
				$data['gen'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_eye1 || $row->pe_eye2 || $row->pe_eye3) {
				$data['eye'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_ent1 || $row->pe_ent2 || $row->pe_ent3 || $row->pe_ent4 || $row->pe_ent5 || $row->pe_ent6) {
				$data['ent'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_neck1 || $row->pe_neck2) {
				$data['neck'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_resp1 || $row->pe_resp2 || $row->pe_resp3 || $row->pe_resp4) {
				$data['resp'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_cv1 || $row->pe_cv2 || $row->pe_cv3 || $row->pe_cv4 || $row->pe_cv5 || $row->pe_cv6) {
				$data['cv'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_ch1 || $row->pe_ch2) {
				$data['ch'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_gi1 || $row->pe_gi2 || $row->pe_gi3 || $row->pe_gi4) {
				$data['gi'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_gu1 || $row->pe_gu2 || $row->pe_gu3 || $row->pe_gu4 || $row->pe_gu5 || $row->pe_gu6 || $row->pe_gu7 || $row->pe_gu8 || $row->pe_gu9) {
				$data['gu'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_lymph1 || $row->pe_lymph2 || $row->pe_lymph3) {
				$data['lymph'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_ms1 || $row->pe_ms2 || $row->pe_ms3 || $row->pe_ms4 || $row->pe_ms5 || $row->pe_ms6 || $row->pe_ms7 || $row->pe_ms8 || $row->pe_ms9 || $row->pe_ms10 || $row->pe_ms11 || $row->pe_ms12) {
				$data['ms'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_neuro1 || $row->pe_neuro2 || $row->pe_neuro3) {
				$data['neuro'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_psych1 || $row->pe_psych2 || $row->pe_psych3 || $row->pe_psych4) {
				$data['psych'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_skin1 || $row->pe_skin2) {
				$data['skin'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_constitutional1) {
				$data['constitutional'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->pe_mental1) {
				$data['mental'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			$data['message'] = 'Physical Examination Updated!';
		}
		echo json_encode($data);
	}
	
	public function postGetPe()
	{
		$data = DB::table('pe')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			$data1 = (array) $data;
			echo json_encode($data1);
		} else {
			echo '';
		}
	}
	
	public function postPeSave($item, $num)
	{
		$count = DB::table('pe')->where('eid', '=', Session::get('eid'))->first();
		$data = array(
			'eid' => Session::get('eid'),
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname')
		);
		if ($item == 'pe_gu' && Session::get('gender') == 'male') {
			for ($i = 7; $i <= $num; $i++) {
				$key = $item . $i;
				$data[$key] = Input::get($key);
			}
		} else {
			for ($i = 1; $i <= $num; $i++) {
				$key = $item . $i;
				$data[$key] = Input::get($key);
			}
		}
		if ($count) {
			DB::table('pe')->where('eid', '=', Session::get('eid'))->update($data);
			$this->audit('Update');
			$result = 'Physical Examination Updated';
		} else {
			DB::table('pe')->insert($data);
			$this->audit('Add');
			$result = 'Physical Examination Added';
		}
		echo $result;
	}
	
	public function postTipPe($item, $num)
	{
		$data = DB::table('pe')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			$data1 = (array) $data;
			$result = '';
			$i = 1;
			while ($i <= $num) {
				$a = $data1[$item . $i];
				if ($a != '') {
					$result .= nl2br($a);
					$result .= ' ';
				}
				$i = $i + 1;
			}
			if ($result != '') {
				echo $result;
			} else {
				echo 'No entry for this item.';
			}
		} else {
			echo 'No entry for this item.';
		}
	}
	
	// Lab functions
	public function postCheckLabs()
	{
		$data['ua'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['rapid'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['micro'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['other'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		$data['message'] = 'Lab Entry Unchanged!';
		$row = DB::table('labs')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			if ($row->labs_ua_urobili || $row->labs_ua_bilirubin || $row->labs_ua_ketones || $row->labs_ua_protein || $row->labs_ua_glucose || $row->labs_ua_nitrites || $row->labs_ua_leukocytes || $row->labs_ua_blood || $row->labs_ua_ph || $row->labs_ua_spgr || $row->labs_ua_color || $row->labs_ua_clarity) {
				$data['ua'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->labs_upt || $row->labs_strep || $row->labs_mono || $row->labs_flu || $row->labs_glucose) {
				$data['rapid'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->labs_microscope) {
				$data['micro'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row->labs_other) {
				$data['other'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			$data['message'] = 'Lab Entry Updated!';
		}
		echo json_encode($data);
	}
	
	public function postGetLabs()
	{
		$row = DB::table('labs')->where('eid', '=', Session::get('eid'))->first();
		if ($row) {
			$data = (array) $row;
		} else {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postTipLabs($lab)
	{
		$data = DB::table('labs')->where('eid', '=', Session::get('eid'))->first();
		if ($data) {
			$result = '';
			if ($lab == "ua") {
				if ($data->labs_ua_urobili != '') {
					$result .= 'Urobilinogen: ';
					$result .= nl2br($data->labs_ua_urobili);
					$result .= '<br>';
				}
				if ($data->labs_ua_bilirubin != '') {
					$result .= 'Bilirubin: ';
					$result .= nl2br($data->labs_ua_bilirubin);
					$result .= '<br>';
				}
				if ($data->labs_ua_ketones != '') {
					$result .= 'Ketones: ';
					$result .= nl2br($data->labs_ua_ketones);
					$result .= '<br>';
				}
				if ($data->labs_ua_protein != '') {
					$result .= 'Protein: ';
					$result .= nl2br($data->labs_ua_protein);
					$result .= '<br>';
				}
				if ($data->labs_ua_glucose != '') {
					$result .= 'Glucose: ';
					$result .= nl2br($data->labs_ua_glucose);
					$result .= '<br>';
				}
				if ($data->labs_ua_nitrites != '') {
					$result .= 'Nitrites: ';
					$result .= nl2br($data->labs_ua_nitrites);
					$result .= '<br>';
				}
				if ($data->labs_ua_leukocytes != '') {
					$result .= 'Leukocytes: ';
					$result .= nl2br($data->labs_ua_leukocytes);
					$result .= '<br>';
				}
				if ($data->labs_ua_blood != '') {
					$result .= 'Blood: ';
					$result .= nl2br($data->labs_ua_blood);
					$result .= '<br>';
				}
				if ($data->labs_ua_ph != '') {
					$result .= 'pH: ';
					$result .= nl2br($data->labs_ua_ph);
					$result .= '<br>';
				}
				if ($data->labs_ua_spgr != '') {
					$result .= 'Specific gravity: ';
					$result .= nl2br($data->labs_ua_spgr);
					$result .= '<br>';
				}
				if ($data->labs_ua_color != '') {
					$result .= 'Color: ';
					$result .= nl2br($data->labs_ua_color);
					$result .= '<br>';
				}
				if ($data->labs_ua_clarity != '') {
					$result .= 'Clarity: ';
					$result .= nl2br($data->labs_ua_clarity);
					$result .= '<br>';
				}
			}
			if ($lab == "rapid") {
				if ($data->labs_upt != '') {
					$result .= 'Urine HcG: ';
					$result .= nl2br($data->labs_upt);
					$result .= '<br>';
				}
				if ($data->labs_strep != '') {
					$result .= 'Rapid Strep: ';
					$result .= nl2br($data->labs_strep);
					$result .= '<br>';
				}
				if ($data->labs_mono != '') {
					$result .= 'Mono Spot: ';
					$result .= nl2br($data->labs_mono);
					$result .= '<br>';
				}
				if ($data->labs_flu != '') {
					$result .= 'Rapid Influenza: ';
					$result .= nl2br($data->labs_flu);
					$result .= '<br>';
				}
				if ($data->labs_glucose != '') {
					$result .= 'Fingerstick Glucose: ';
					$result .= nl2br($data->labs_glucose);
					$result .= '<br>';
				}
			}
			if ($lab == "micro") {
				if ($data->labs_microscope != '') {
					$result .= 'Microscopy: ';
					$result .= nl2br($data->labs_microscope);
					$result .= '<br>';
				}
			}
			if ($lab == "other") {
				if ($data->labs_other != '') {
					$result .= 'Other Laboratory: ';
					$result .= nl2br($data->labs_other);
					$result .= '<br>';
				}
			}
			if ($result != '') {
				echo $result;
			} else {
				echo 'No entry for this item.';
			}
		} else {
			echo 'No entry for this item.';
		}
	}
	
	public function postLabsSave($item)
	{
		$count = DB::table('labs')->where('eid', '=', Session::get('eid'))->first();
		if ($item == 'ua') {
			$data = array(
				'eid' => Session::get('eid'),
				'pid' => Session::get('pid'),
				'encounter_provider' => Session::get('displayname'),
				'labs_ua_urobili' => Input::get('labs_ua_urobili'),
				'labs_ua_bilirubin' => Input::get('labs_ua_bilirubin'),
				'labs_ua_ketones' => Input::get('labs_ua_ketones'),
				'labs_ua_protein' => Input::get('labs_ua_protein'),
				'labs_ua_glucose' => Input::get('labs_ua_glucose'),
				'labs_ua_nitrites' => Input::get('labs_ua_nitrites'),
				'labs_ua_leukocytes' => Input::get('labs_ua_leukocytes'),
				'labs_ua_blood' => Input::get('labs_ua_blood'),
				'labs_ua_ph' => Input::get('labs_ua_ph'),
				'labs_ua_spgr' => Input::get('labs_ua_spgr'),
				'labs_ua_color' => Input::get('labs_ua_color'),
				'labs_ua_clarity' => Input::get('labs_ua_clarity')
			);
		}
		if ($item == 'rapid') {
			if (Session::get('gender') == 'male') {
				$data = array(
					'eid' => Session::get('eid'),
					'pid' => Session::get('pid'),
					'encounter_provider' => Session::get('displayname'),
					'labs_strep' => Input::get('labs_strep'),
					'labs_mono' => Input::get('labs_mono'),
					'labs_flu' => Input::get('labs_flu'),
					'labs_glucose' => Input::get('labs_glucose')
				);
			} else {
				$data = array(
					'eid' => Session::get('eid'),
					'pid' => Session::get('pid'),
					'encounter_provider' => Session::get('displayname'),
					'labs_upt' => Input::get('labs_upt'),
					'labs_strep' => Input::get('labs_strep'),
					'labs_mono' => Input::get('labs_mono'),
					'labs_flu' => Input::get('labs_flu'),
					'labs_glucose' => Input::get('labs_glucose')
				);
			}
		}
		if ($item == 'micro') {
			$data = array(
				'eid' => Session::get('eid'),
				'pid' => Session::get('pid'),
				'encounter_provider' => Session::get('displayname'),
				'labs_microscope' => Input::get('labs_microscope')
			);
		}
		if ($item == 'other') {
			$data = array(
				'eid' => Session::get('eid'),
				'pid' => Session::get('pid'),
				'encounter_provider' => Session::get('displayname'),
				'labs_other' => Input::get('labs_other')
			);
		}
		if ($count) {
			DB::table('labs')->where('eid', '=', Session::get('eid'))->update($data);
			$this->audit('Update');
			$result = 'Lab Entry Updated';
		} else {
			DB::table('labs')->insert($data);
			$this->audit('Add');
			$result = 'Lab Entry Added';
		}
		echo $result;
	}
	
	// Procedure functions
	public function postGetProc()
	{
		$query = DB::table('procedure')->where('eid', '=', Session::get('eid'))->first();
		if ($query) {
			$data = (array) $query;
		} else {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postProcSave()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$count = Procedure::find($eid);
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => Session::get('displayname'),
			'proc_type' => Input::get('proc_type'),
			'proc_description' => Input::get('proc_description'),
			'proc_complications' => Input::get('proc_complications'),
			'proc_ebl' => Input::get('proc_ebl'),
			'proc_cpt' => Input::get('proc_cpt')
		);
		if ($count) {
			DB::table('procedure')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Procedure Updated';
		} else {
			DB::table('procedure')->insert($data);
			$this->audit('Add');
			$result = 'Procedure Added';
		}
		echo $result;
	}
	
	public function postProcTemplate()
	{
		$data = array(
			'procedure_type' => Input::get('proc_type'),
			'procedure_description' => Input::get('proc_description'),
			'procedure_complications' => Input::get('proc_complications'),
			'procedure_ebl' => Input::get('proc_ebl'),
			'cpt' => Input::get('proc_cpt'),
			'practice_id' => Session::get('practice_id')
		);
		if (Input::get('procedurelist_id') != '') {
			DB::table('procedurelist')->where('procedurelist_id', '=', Input::get('procedurelist_id'))->update($data);
			$this->audit('Update');
			$result = 'Procedure Template Updated';
		} else {
			DB::table('procedurelist')->insert($data);
			$this->audit('Add');
			$result = 'Procedure Template Added';
		}
		echo $result;
	}
	
	// Assessment functions
	public function postGetAssessment()
	{
		$query = DB::table('assessment')->where('eid', '=', Session::get('eid'))->first();
		if ($query) {
			$data = (array) $query;
		} else {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postAssessmentSave()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$count = Assessment::find($eid);
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => Session::get('displayname'),
			'assessment_icd1' => Input::get('assessment_icd1'),
			'assessment_icd2' => Input::get('assessment_icd2'),
			'assessment_icd3' => Input::get('assessment_icd3'),
			'assessment_icd4' => Input::get('assessment_icd4'),
			'assessment_icd5' => Input::get('assessment_icd5'),
			'assessment_icd6' => Input::get('assessment_icd6'),
			'assessment_icd7' => Input::get('assessment_icd7'),
			'assessment_icd8' => Input::get('assessment_icd8'),
			'assessment_icd9' => Input::get('assessment_icd9'),
			'assessment_icd10' => Input::get('assessment_icd10'),
			'assessment_icd11' => Input::get('assessment_icd11'),
			'assessment_icd12' => Input::get('assessment_icd12'),
			'assessment_1' => Input::get('assessment_1'),
			'assessment_2' => Input::get('assessment_2'),
			'assessment_3' => Input::get('assessment_3'),
			'assessment_4' => Input::get('assessment_4'),
			'assessment_5' => Input::get('assessment_5'),
			'assessment_6' => Input::get('assessment_6'),
			'assessment_7' => Input::get('assessment_7'),
			'assessment_8' => Input::get('assessment_8'),
			'assessment_9' => Input::get('assessment_9'),
			'assessment_10' => Input::get('assessment_10'),
			'assessment_11' => Input::get('assessment_11'),
			'assessment_12' => Input::get('assessment_12'),
			'assessment_other' => Input::get('assessment_other'),
			'assessment_ddx' => Input::get('assessment_ddx'),
			'assessment_notes' => Input::get('assessment_notes')
		);
		if ($count) {
			DB::table('assessment')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Assessment Updated';
		} else {
			DB::table('assessment')->insert($data);
			$this->audit('Add');
			$result = 'Assessment Added';
		}
		echo $result;
	}
	
	public function postCheckAssessment()
	{
		$result = "No diagnoses for this encounter!<br>Make sure these are established before billing is submitted!";
		$row = Assessment::find(Session::get('eid'));
		if ($row) {
			if ($row->assessment_icd1 != '') {
				$result = "OK!";
			}
		}
		echo $result;
	}
	
	// Orders functions
	public function postCheckOrders()
	{
		$eid = Session::get('eid');
		$query_labs = DB::table('orders')->where('eid', '=', $eid)->where('orders_labs', '!=', '')->get();
		$query_radiology = DB::table('orders')->where('eid', '=', $eid)->where('orders_radiology', '!=', '')->get();
		$query_cp = DB::table('orders')->where('eid', '=', $eid)->where('orders_cp', '!=', '')->get();
		$query_ref = DB::table('orders')->where('eid', '=', $eid)->where('orders_referrals', '!=', '')->get();
		if ($query_labs) {
			$data['labs_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		} else {
			$data['labs_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		}
		if ($query_radiology) {
			$data['rad_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		} else {
			$data['rad_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		}
		if ($query_cp) {
			$data['cp_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		} else {
			$data['cp_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		}
		if ($query_ref) {
			$data['ref_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		} else {
			$data['ref_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		}
		$data['message'] = 'Orders Updated!';
		$row1 = Rx::find($eid);
		if ($row1) {
			if ($row1->rx_rx) {
				$data['rx_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['rx_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row1->rx_supplements) {
				$data['sup_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['sup_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			if ($row1->rx_immunizations) {
				$data['imm_status'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			} else {
				$data['imm_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			}
			$data['message'] = 'Orders Updated!';
		} else {
			$data['rx_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['sup_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
			$data['imm_status'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '30', 'width' => '30', 'style' => 'vertical-align:middle;'));
		}
		echo json_encode($data);
	}
	
	public function postGetOrders()
	{
		$query = DB::table('plan')->where('eid', '=', Session::get('eid'))->first();
		if ($query) {
			$data = (array) $query;
		} else {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postOrdersSave()
	{
		$eid = Session::get('eid');
		$count = Plan::find($eid);
		$data = array(
			'eid' => $eid,
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname'),
			'plan' => Input::get('plan'),
			'duration' => Input::get('duration'),
			'followup' => Input::get('followup'),
			'goals' => Input::get('goals'),
			'tp' => Input::get('tp')
		);
		if ($count) {
			DB::table('plan')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Orders Updated';
		} else {
			DB::table('plan')->insert($data);
			$this->audit('Add');
			$result = 'Orders Added';
		}
		echo $result;
	}
	
	public function postOrdersRxSave()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$encounter_provider = Session::get('displayname');
		$rx_rx = "";
		$rx_orders_summary_text = "";
		$row = Rx::find($eid);
		if ($row) {
			$row_parts = explode("\n\n", $row->rx_rx);
			$rx_text = "";
			$rx_eie_text = "";
			$rx_inactivate_text = "";
			$rx_reactivate_text = "";
			foreach($row_parts as $row_part) {
				if (strpos($row_part, "PRESCRIBED MEDICATIONS:")!==FALSE) {
					$rx_text .= str_replace("PRESCRIBED MEDICATIONS:  ","",$row_part);
				}
				if (strpos($row_part, "ENTERED MEDICATIONS IN ERROR:")!==FALSE) {
					$rx_eie_text .= str_replace("ENTERED MEDICATIONS IN ERROR:  ","",$row_part);
				}
				if (strpos($row_part, "DISCONTINUED MEDICATIONS:")!==FALSE) {
					$rx_inactivate_text .= str_replace("DISCONTINUED MEDICATIONS:  ","",$row_part);
				}
				if (strpos($row_part, "REINSTATED MEDICATIONS:")!==FALSE) {
					$rx_reactivate_text .= str_replace("REINSTATED MEDICATIONS:  ","",$row_part);
				}
			}
			if($rx_text != "" || Input::get('rx')) {
				$rx_rx .= "PRESCRIBED MEDICATIONS:  ";
				$rx_orders_summary_text .= "PRESCRIBED MEDICATIONS:  ";
				if ($rx_text) {
					$rx_rx .= $rx_text;
					$rx_orders_summary_text .= $rx_text;
				}
				if (Input::get('rx')) {
					$rx_rx .= Input::get('rx');
					$rx_orders_summary_text .= Input::get('rx');
				}
			}
			if($rx_eie_text != "" || Input::get('eie')) {
				$rx_rx .= "ENTERED MEDICATIONS IN ERROR:  ";
				if ($rx_eie_text) {
					$rx_rx .= $rx_eie_text;
				}
				if (Input::get('eie')) {
					$rx_rx .= Input::get('eie');
				}
			}
			if($rx_inactivate_text != "" || Input::get('inactivate')) {
				$rx_rx .= "DISCONTINUED MEDICATIONS:  ";
				$rx_orders_summary_text .= "DISCONTINUED MEDICATIONS:  ";
				if ($rx_inactivate_text) {
					$rx_rx .= $rx_inactivate_text;
					$rx_orders_summary_text .= $rx_inactivate_text;
				}
				if (Input::get('inactivate')) {
					$rx_rx .= Input::get('inactivate');
					$rx_orders_summary_text .= Input::get('inactivate');
				}
			}
			if($rx_reactivate_text != "" || Input::get('reactivate')) {
				$rx_rx .= "REINSTATED MEDICATIONS:  ";
				$rx_orders_summary_text .= "REINSTATED MEDICATIONS:  ";
				if ($rx_reactivate_text) {
					$rx_rx .= $rx_inactivate_text;
					$rx_orders_summary_text .= $rx_inactivate_text;
				}
				if (Input::get('reactivate')) {
					$rx_rx .= Input::get('reactivate');
					$rx_orders_summary_text .= Input::get('reactivate');
				}
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_rx' => $rx_rx,
				'rx_orders_summary' => $rx_orders_summary_text
			);
			DB::table('rx')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Medication Orders Updated';
		} else {
			if (Input::get('rx')) {
				$rx_text = "PRESCRIBED MEDICATIONS:  " . Input::get('rx');
				$rx_rx .= $rx_text . "\n\n";
				$rx_orders_summary_text .= $rx_text . "\n\n";
			}
			if (Input::get('eie')) {
				$rx_rx .= "ENTERED MEDICATIONS IN ERROR:  " . Input::get('eie') . "\n\n";
			}
			if (Input::get('inactivate')) {
				$rx_inactivate_text = "DISCONTINUED MEDICATIONS:  " . Input::get('inactivate');
				$rx_rx .= $rx_inactivate_text . "\n\n";
				$rx_orders_summary_text .= $rx_inactivate_text . "\n\n";
			}
			if (Input::get('reactivate')) {
				$rx_reactivate_text = "REINSTATED MEDICATIONS:  " . Input::get('reactivate');
				$rx_rx .= $rx_reactivate_text . "\n\n";
				$rx_orders_summary_text .= $rx_reactivate_text . "\n\n";
			}
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_rx' => $rx_rx,
				'rx_orders_summary' => $rx_text
			);
			DB::table('rx')->insert($data);
			$this->audit('Add');
			$result = 'Medication Orders Added';
		}
		echo $result;
	}
	
	public function postOrdersImmSave()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$encounter_provider = Session::get('displayname');
		$row = Rx::find($eid);
		if ($row) {
			$rx_immunizations = $row->rx_immunizations . Input::get('rx_immunizations');
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_immunizations' => $rx_immunizations,
			);
			DB::table('rx')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Immunization Orders Updated';
		} else {
			$data = array(
				'eid' => $eid,
				'pid' => $pid,
				'encounter_provider' => $encounter_provider,
				'rx_immunizations' => Input::get('rx_immunizations'),
			);
			DB::table('rx')->insert($data);
			$this->audit('Add');
			$result = 'Immunization Orders Added';
		}
		echo $result;
	}
	
	public function print_plan()
	{
		ini_set('memory_limit','196M');
		$html = $this->page_plan(Session::get('eid'))->render();
		$file_path = __DIR__."/../../public/temp/plan_" . time() . "_" . Session::get('user_id') . ".pdf";
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function postTipOrders($item)
	{
		$eid = Session::get('eid');
		if ($item == 'rx'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_rx == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data->rx_rx) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'sup'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_supplements == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data->rx_supplements) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'imm'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_immunizations == '') {
					echo 'No entry for this item.';
				} else {
					$text = nl2br($data->rx_immunizations) . "<br><br>Click on check mark to edit text.";
					echo $text;
				}
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'labs'){
			$query = DB::table('orders')->where('eid', '=', $eid)->get();
			if ($query) {
				$description = '';
				foreach ($query as $data) {
					if ($data->orders_labs == '') {
						$description .= '';
					} else {
						$text = nl2br($data->orders_labs);
						$row1 = Addressbook::find($data->address_id);
						$description .= 'Orders sent to ' . $row1->displayname . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'rad'){
			$query = DB::table('orders')->where('eid', '=', $eid)->get();
			if ($query) {
				$description = '';
				foreach ($query as $data) {
					if ($data->orders_radiology == '') {
						$description .= '';
					} else {
						$text = nl2br($data->orders_radiology);
						$row1 = Addressbook::find($data->address_id);
						$description .= 'Orders sent to ' . $row1->displayname . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'cp'){
			$query = DB::table('orders')->where('eid', '=', $eid)->get();
			if ($query) {
				$description = '';
				foreach ($query as $data) {
					if ($data->orders_cp == '') {
						$description .= '';
					} else {
						$text = nl2br($data->orders_cp);
						$row1 = Addressbook::find($data->address_id);
						$description .= 'Orders sent to ' . $row1->displayname . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
		if ($item == 'ref'){
			$query = DB::table('orders')->where('eid', '=', $eid)->get();
			if ($query) {
				$description = '';
				foreach ($query as $data) {
					if ($data->orders_referrals == '') {
						$description .= '';
					} else {
						$text = nl2br($data->orders_referrals);
						$row1 = Addressbook::find($data->address_id);
						$description .= 'Orders sent to ' . $row1->displayname . ': '. $text . '<br>';
					}
				}
				echo $description;
			} else {
				echo 'No entry for this item.';
			}
		}
	}
	
	public function postTipEditOrders($item)
	{
		$eid = Session::get('eid');
		$arr['type'] = "N";
		if ($item == 'rx'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_rx != '') {
					$arr['type'] = 'rx_rx';
					$arr['text'] = $data->rx_rx;
				}
			}
		}
		if ($item == 'sup'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_supplements != '') {
					$arr['type'] = 'rx_supplements';
					$arr['text'] = $data->rx_supplements;
				}
			}
		}
		if ($item == 'imm'){
			$data = Rx::find($eid);
			if ($data) {
				if ($data->rx_immunizations != '') {
					$arr['type'] = 'rx_immunizations';
					$arr['text'] = $data->rx_immunizations;
				}
			}
		}
		echo json_encode($arr);
	}
	
	public function postEditTipOrders($type)
	{
		$data = array(
			'eid' => Session::get('eid'),
			'pid' => Session::get('pid'),
			'encounter_provider' => Session::get('displayname'),
			$type => Input::get($type),
		);
		DB::table('rx')->where('eid', '=', Session::get('eid'))->update($data);
		$this->audit('Update');
		if ($type == 'rx_rx') {
			$text = "Medication ";
		}
		if ($type == 'rx_supplements') {
			$text = "Supplement ";
		}
		if ($type == 'rx_immunizations') {
			$text = "Immunization ";
		}
		$result = $text . 'Orders Updated';
		echo $result;
	}
	
	// Billing functions
	public function postGetBilling()
	{
		$row = Assessment::find(Session::get('eid'));
		if ($row) {
			$data1['message'] = "OK";
			if ($row->assessment_1 != '') {
				$data1['1'] = "1 - " . $row->assessment_1;
			} else {
				$data1['message'] = "No diagnoses available.";
			}
			if ($row->assessment_2 != '') {
				$data1['2'] = "2 - " . $row->assessment_2;
			}
			if ($row->assessment_3 != '') {
				$data1['3'] = "3 - " . $row->assessment_3;
			}
			if ($row->assessment_4 != '') {
				$data1['4'] = "4 - " . $row->assessment_4;
			}
			if ($row->assessment_5 != '') {
				$data1['5'] = "5 - " . $row->assessment_5;
			}
			if ($row->assessment_6 != '') {
				$data1['6'] = "6 - " . $row->assessment_6;
			}
			if ($row->assessment_7 != '') {
				$data1['7'] = "7 - " . $row->assessment_7;
			}
			if ($row->assessment_8 != '') {
				$data1['8'] = "8 - " . $row->assessment_8;
			}
		} else {
			$data1['message'] = "No diagnoses available.";
		}
		echo json_encode($data1);
	}
	
	public function postBillingSave1()
	{
		$result = $this->billing_save_common(Input::get('insurance_id_1'), Input::get('insurance_id_2'), Session::get('eid'), Input::get('bill_complex'));
		echo $result;
	}
	
	public function postCompileBilling()
	{
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$row = Demographics::find($pid);
		$encounterInfo = Encounters::find($eid);
		$dos1 = $this->human_to_unix($encounterInfo->encounter_DOS);
		$dos = date('mdY', $dos1);
		$dos2 = date('m/d/Y', $dos1);
		$pos = $encounterInfo->encounter_location;
		$assessment_data = Assessment::find($eid);
		$icd_pointer = '';
		if ($assessment_data->assessment_1 != '') {
			$icd_pointer .= "A";
		}
		if ($assessment_data->assessment_2 != '') {
			$icd_pointer .= "B";
		}
		if ($assessment_data->assessment_3 != '') {
			$icd_pointer .= "C";
		}
		if ($assessment_data->assessment_4 != '') {
			$icd_pointer .= "D";
		}
		$labsInfo = Labs::find($eid);
		if ($labsInfo) {
			if ($labsInfo->labs_ua_urobili != '' || $labsInfo->labs_ua_bilirubin != '' || $labsInfo->labs_ua_ketones != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$this->compile_procedure_billing('81002', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			}
			if ($labsInfo->labs_upt != '') {
				$this->compile_procedure_billing('81025', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			} 
			if ($labsInfo->labs_strep != '') {
				$this->compile_procedure_billing('87880', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			} 
			if ($labsInfo->labs_mono != '') {
				$this->compile_procedure_billing('86308', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			}
			if ($labsInfo->labs_flu != '') {
				$this->compile_procedure_billing('87804', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			}
			if ($labsInfo->labs_glucose != '') {
				$this->compile_procedure_billing('82962', $eid, $pid, $dos2, $icd_pointer, $practice_id);
			}
		}
		$result9 = Procedure::find($eid);
		if ($result9) {
			$this->compile_procedure_billing($result9->proc_cpt, $eid, $pid, $dos2, $icd_pointer, $practice_id);
		}
		$result11 = Immunizations::where('eid', '=', $eid)->get();
		if ($result11) {
			foreach ($result11 as $row11) {
				$this->compile_procedure_billing($row11->cpt, $eid, $pid, $dos2, $icd_pointer, $practice_id);
			}
		}
		echo 'CPT codes complied from the encounter!';
	}
	
	// Patient forms functions
	public function postPfTemplateSelectList($destination)
	{
		$query = Forms::where('pid', '=', Session::get('pid'))->where('forms_destination', '=', $destination)->get();
		$data['options'] = array();
		if ($query) {
			$data['options'][''] = "*Select completed forms below.";
			foreach ($query as $row) {
				$data['options'][$row->forms_id] = $row->forms_title . ", completed on " . date('m/d/Y', $this->human_to_unix($row->forms_date));
			}
		} else {
			$data['options'][''] = "No forms completed by patient.";
		}
		echo json_encode($data);
	}
	
	public function postGetPfTemplate($id)
	{
		$row = Forms::find($id);
		echo $row->forms_content_text;
	}
	
	// Addendum functions
	public function postNewAddendum($eid)
	{
		$encounter = DB::table('encounters')->where('eid', '=', $eid)->first();
		$data = (array) $encounter;
		unset($data['eid']);
		unset($data['encounter_signed']);
		$data['encounter_signed'] = 'No';
		$new_eid = DB::table('encounters')->insertGetId($data);
		$this->audit('Add');
		$data1 = array(
			'addendum' => 'y'
		);
		DB::table('encounters')->where('eid', '=', $eid)->update($data1);
		$this->audit('Update');
		if ($encounter->encounter_template == 'standardmedical') {
			$table_array1 = array("hpi", "ros", "vitals", "pe", "labs", "procedure", "rx", "assessment", "plan");
			$table_array2 = array("other_history", "orders", "billing", "billing_core", "image");
		}
		if ($encounter->encounter_template == 'clinicalsupport') {
			$table_array1 = array("hpi", "labs", "procedure", "rx", "assessment", "plan");
			$table_array2 = array("other_history", "orders", "billing", "billing_core", "image");
		}
		if ($encounter->encounter_template == 'standardpsych' || $encounter->encounter_template == 'standardpsych1') {
			$table_array1 = array("hpi", "ros", "vitals", "pe", "rx", "assessment", "plan");
			$table_array2 = array("other_history", "orders", "billing", "billing_core", "image");
		}
		if ($encounter->encounter_template == 'standardmtm') {
			$table_array1 = array("hpi", "vitals", "assessment", "plan");
			$table_array2 = array("other_history", "orders", "billing", "billing_core", "image");
		}
		foreach($table_array1 as $table1) {
			$table_query1 = DB::table($table1)->where('eid', '=', $eid)->first();
			if ($table_query1) {
				$data2 = (array) $table_query1;
				unset($data2['eid']);
				$data2['eid'] = $new_eid;
				DB::table($table1)->insert($data2);
				$this->audit('Add');
			}
		}
		foreach($table_array2 as $table2) {
			$table_query2 = DB::table($table2)->where('eid', '=', $eid)->get();
			if ($table_query2) {
				if ($table2 == 'other_history') {
					$primary = 'oh_id';
				}
				if ($table2 == 'orders') {
					$primary = 'orders_id';
				}
				if ($table2 == 'billing') {
					$primary = 'bill_id';
				}
				if ($table2 == 'billing_core') {
					$primary = 'billing_core_id';
				}
				if ($table2 == 'image') {
					$primary = 'image_id';
				}
				foreach ($table_query2 as $table_row) {
					$data3 = (array) $table_row;
					unset($data3['eid']);
					unset($data3[$primary]);
					$data3['eid'] = $new_eid;
					DB::table($table2)->insert($data3);
					$this->audit('Add');
				}
			}
		}
		Session::put('eid', $new_eid);
		Session::put('encounter_active', 'y');
		Session::put('encounter_template', $encounter->encounter_template);
		Session::put('encounter_DOS', $encounter->encounter_DOS);
		echo $new_eid;
	}
	
	public function postPreviousVersions($eid)
	{
		$encounter = DB::table('encounters')->where('eid', '=', $eid)->first();
		$query = DB::table('encounters')->where('addendum_eid', '=', $encounter->addendum_eid)->get();
		if (count($query) > 1) {
			$result = "";
			foreach ($query as $row) {
				if ($row->addendum != "n" && $row->encounter_signed === 'Yes') {
					$result .= '<a href="#" id="' . $row->eid . '" class="addendum_class">Date signed: ' . $row->date_signed . '</a><br>';
				}
			}
			$result .= '<h4>Current version:</h4><a href="#" id="' . $eid . '" class="addendum_class">Date signed: ' . $encounter->date_signed . '</a><br>';
		} else {
			$result = "None.";
		}
		echo $result;
	}
	
	public function postGetPreviousVersions($eid)
	{
		return $this->encounters_view($eid, Session::get('pid'), Session::get('practice_id'), true, false);
	}
	
	public function postCopyEncounter()
	{
		$eid = Input::get('copy_encounter_from');
		$encounter = Encounters::find(Session::get('eid'));
		if ($encounter->encounter_template == 'standardmedical') {
			$table_array1 = array("hpi", "ros", "vitals", "pe", "labs", "procedure", "rx", "assessment", "plan");
			$table_array2 = array("other_history");
		}
		if ($encounter->encounter_template == 'clinicalsupport') {
			$table_array1 = array("hpi", "labs", "procedure", "rx", "assessment", "plan");
			$table_array2 = array("other_history");
		}
		if ($encounter->encounter_template == 'standardpsych' || $encounter->encounter_template == 'standardpsych1') {
			$table_array1 = array("hpi", "ros", "vitals", "pe", "rx", "assessment", "plan");
			$table_array2 = array("other_history");
		}
		foreach($table_array1 as $table1) {
			$table_query1 = DB::table($table1)->where('eid', '=', $eid)->first();
			if ($table_query1) {
				$data2 = (array) $table_query1;
				unset($data2['eid']);
				$data2['eid'] = Session::get('eid');
				$query1 = DB::table($table1)->where('eid', '=', Session::get('eid'))->first();
				if ($query1) {
					DB::table($table1)->where('eid', '=', Session::get('eid'))->update($data2);
					$this->audit('Update');
				} else {
					DB::table($table1)->insert($data2);
					$this->audit('Add');
				}
			}
		}
		$table_query2 = DB::table('other_history')->where('eid', '=', $eid)->get();
		if ($table_query2) {
			foreach ($table_query2 as $table_row) {
				$data3 = (array) $table_row;
				unset($data3['eid']);
				unset($data3['oh_id']);
				$data3['eid'] = Session::get('eid');
				$query2 = DB::table('other_history')->where('eid', '=', Session::get('eid'))->first();
				if ($query2) {
					$data3[$primary] = $query2->oh_id;
					DB::table('other_history')->where('oh_id', '=', $query2->oh_id)->update($data3);
					$this->audit('Update');
				} else {
					DB::table('other_history')->insert($data3);
					$this->audit('Add');
				}
			}
		}
		echo "Copied previous encounter elements to new encounter.";
	}
	
	public function postAllNormal($type, $group)
	{
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$age = Session::get('agealldays');
		$query = DB::table('templates')
			->where('user_id', '=', Session::get('user_id'))
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', $type);
		if ($group != 'all') {
			$query->where('group', 'LIKE', "%$group%");
		} else {
			$query->orderBy('group', 'asc');
		}
		$group1 = $group;
		if ($type == 'ros' && Session::get('agealldays') > 6574.5) {
			$query->where(function($query_array1) {
				$query_array1->where('age', '=', 'adult')
				->orWhere('age', '=', '');
			});
		}
		$query->where(function($query_array2) {
			$query_array2->where('practice_id', '=', Session::get('practice_id'))
			->orWhereNull('practice_id');
		});
		$result = $query->get();
		$data = array();
		foreach ($result as $row) {
			$group = $row->group;
			if (isset($data[$group]) && $data[$group] != '') {
				$data[$group] .= '  ';
			} else {
				$data[$group] = '';
			}
			if ($group == 'pe_ms3') {
				$data[$group] = 'Full range of motion of the shoulders bilaterally.';
			}
			if ($group == 'pe_ms4') {
				$data[$group] = 'Full range of motion of the elbows bilaterally.';
			}
			if ($group == 'pe_ms5') {
				$data[$group] = 'Full range of motion of the wrists bilaterally.';
			}
			if ($group == 'pe_ms6') {
				$data[$group] = 'Full range of motion of the fingers and hands bilaterally.';
			}
			if ($group == 'pe_ms7') {
				$data[$group] = 'Full range of motion of the hips bilaterally.';
			}
			if ($group == 'pe_ms8') {
				$data[$group] = 'Full range of motion of the knees bilaterally.';
			}
			if ($group == 'pe_ms9') {
				$data[$group] = 'Full range of motion of the ankles bilaterally.';
			}
			if ($group == 'pe_ms10') {
				$data[$group] = 'Full range of motion of the toes and feet bilaterally.';
			}
			if ($group == 'pe_ms11') {
				$data[$group] = 'Full range of motion of the cervical spine.';
			}
			if ($group == 'pe_ms12') {
				$data[$group] = 'Full range of motion of the thoracic and lumbar spine.';
			}
			if ($group == 'pe_neuro2') {
				$data[$group] = 'Biceps, Patellar, and Achillies deep tendon reflexes are equal bilaterally.';
			}
			if ($row->default == 'default') {
				$row_array = unserialize($row->array);
			} else {
				$row_array = json_decode(unserialize($row->array));
			}
			if (isset($row_array->html)) {
				foreach ($row_array->html as $row_elem) {
					if (isset($row_elem->html)) {
						foreach ($row_elem->html as $row_elem1) {
							if (isset($row_elem1->class)) {
								if ($row_elem1->class == $type . '_normal') {
									if (isset($row_elem1->value)) {
										if ($data[$group] != '') {
											$data[$group] .= '  ';
										}
										$data[$group] .= $row_elem1->value;
									}
								}
							}
						}
					}
				}
			}
		}
		if ($type == 'pe') {
			if ($sex == 'f') {
				$gender_array = array('pe_gu1','pe_gu2','pe_gu3','pe_gu4','pe_gu5','pe_gu6');
			} else {
				$gender_array = array('pe_gu7','pe_gu8','pe_gu9');
			}
			foreach ($gender_array as $gender_row) {
				unset($data[$gender_row]);
			}
		}
		if ($type == 'ros') {
			$wcc_array = array('ros_wccage0m','ros_wccage2m','ros_wccage4m','ros_wccage6m','ros_wccage9m','ros_wccage12m','ros_wccage15m','ros_wccage18m','ros_wccage2','ros_wccage3','ros_wccage4','ros_wccage5');
			if ($age <= 2191.44) {
				if ($age <= 60.88) {
					$wcc_id = 'ros_wccage0m';
				}
				if ($age > 60.88 && $age <= 121.76) {
					$wcc_id = 'ros_wccage2m';
				}
				if ($age > 121.76 && $age <= 182.64) {
					$wcc_id = 'ros_wccage4m';
				}
				if ($age > 182.64 && $age <= 273.96) {
					$wcc_id = 'ros_wccage6m';
				}
				if ($age > 273.96 && $age <= 365.24) {
					$wcc_id = 'ros_wccage9m';
				}
				if ($age > 365.24 && $age <= 456.6) {
					$wcc_id = 'ros_wccage12m';
				}
				if ($age > 456.6 && $age <= 547.92) {
					$wcc_id = 'ros_wccage15m';
				}
				if ($age > 547.92 && $age <= 730.48) {
					$wcc_id = 'ros_wccage18m';
				}
				if ($age > 730.48 && $age <= 1095.75) {
					$wcc_id = 'ros_wccage2';
				}
				if ($age > 1095.75 && $age <= 1461) {
					$wcc_id = 'ros_wccage3';
				}
				if ($age > 1461 && $age <= 1826.25) {
					$wcc_id = 'ros_wccage4';
				}
				if ($age > 1826.25 && $age <= 2191.44) {
					$wcc_id = 'ros_wccage5';
				}
				if (isset($data[$wcc_id])) {
					$data['ros_wcc'] = $data[$wcc_id];
					foreach ($wcc_array as $wcc_row) {
						unset($data[$wcc_row]);
					}
				}
			}
		}
		if ($group1 == 'all') {
			if ($type == 'pe') {
				if (Session::get('encounter_template') == 'standardpsych' || Session::get('encounter_template') == 'standardpsych') {
					if ($sex == 'f') {
						$psych_array('pe_ch1','pe_ch2','pe_cv1','pe_cv2','pe_cv3','pe_cv4','pe_cv5','pe_cv6','pe_ent1','pe_ent2','pe_ent3','pe_ent4','pe_ent5','pe_ent6','pe_eye1','pe_eye2','pe_eye3','pe_gen1','pe_gi1','pe_gi2','pe_gi3','pe_gi4','pe_gu1','pe_gu2','pe_gu3','pe_gu4','pe_gu5','pe_gu6','pe_lymph1','pe_lymph2','pe_lymph3','pe_neck1','pe_neck2','pe_resp1','pe_resp2','pe_resp3','pe_resp4','pe_skin1','pe_skin2');
					} else {
						$psych_array('pe_ch1','pe_ch2','pe_cv1','pe_cv2','pe_cv3','pe_cv4','pe_cv5','pe_cv6','pe_ent1','pe_ent2','pe_ent3','pe_ent4','pe_ent5','pe_ent6','pe_eye1','pe_eye2','pe_eye3','pe_gen1','pe_gi1','pe_gi2','pe_gi3','pe_gi4','pe_gu7','pe_gu8','pe_gu9','pe_lymph1','pe_lymph2','pe_lymph3','pe_neck1','pe_neck2','pe_resp1','pe_resp2','pe_resp3','pe_resp4','pe_skin1','pe_skin2');
					}
					foreach ($psych_array as $psych_row) {
						unset($data[$psych_row]);
					}
				}
				$pe_query = DB::table('pe')->where('eid', '=', Session::get('eid'))->first();
				if ($pe_query) {
					DB::table('pe')->where('eid', '=', Session::get('eid'))->update($data);
					$this->audit('Update');
				} else {
					$data['eid'] = Session::get('eid');
					$data['pid'] = Session::get('pid');
					$data['encounter_provider'] = Session::get('displayname');
					DB::table('pe')->insert($data);
					$this->audit('Add');
				}
			}
			if ($type == 'ros') {
				$ros_query = DB::table('ros')->where('eid', '=', Session::get('eid'))->first();
				if ($ros_query) {
					DB::table('ros')->where('eid', '=', Session::get('eid'))->update($data);
					$this->audit('Update');
				} else {
					$data['eid'] = Session::get('eid');
					$data['pid'] = Session::get('pid');
					$data['encounter_provider'] = Session::get('displayname');
					DB::table('ros')->insert($data);
					$this->audit('Add');
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postMtmMedicationList() {
		$query1 = DB::table('rx_list')
			->where('pid', '=', $pid)
			->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
			->where('rxl_date_old', '=', '0000-00-00 00:00:00')
			->get();
		$result1 = '';
		if ($query1) {
			foreach ($query1 as $row1) {
				if ($row1->rxl_sig != '') {
					$result1 .= $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_sig . ' ' . $row1->rxl_route . ' ' . $row1->rxl_frequency . ' for ' . $row1->rxl_reason . "\n";
				} else {
					$result1 .= $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_instructions . ' for ' . $row1->rxl_reason . "\n";
				}
			}
		} else {
			$result1 .= 'None.';
		}
		$result1 = trim($result1);
		echo $result1;
	}
	
	public function postMtmGetMedicationList() {
		$query = DB::table('other_history')->where('eid', '=', Session::get('eid'))->first();
		if ($query) {
			$data['oh_meds'] = $query->oh_meds;
		} else {
			$data['oh_meds'] = '';
		}
		echo json_encode($data);
	}
	
	public function postMtmSaveMedicationList() {
		$eid = Session::get('eid');
		$pid = Session::get('pid');
		$encounter_provider = Session::get('displayname');
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'encounter_provider' => $encounter_provider,
			'oh_meds' => Input::get('oh_meds')
		);
		$count = DB::table('other_history')->where('eid', '=', $eid)->first();
		if ($count) {
			DB::table('other_history')->where('eid', '=', $eid)->update($data);
			$this->audit('Update');
			$result = 'Medication List Updated.';
		} else {
			DB::table('other_history')->insert($data);
			$this->audit('Add');
			$result = 'Medication List Added.';
		}
		echo $result;
	}
	
	public function postMtmEncounters()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('encounter_template', '=', 'standardmtm')
			->where('practice_id', '=', $practice_id);
		$result = $query->get();
		if($result) { 
			$count = count($result);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query->orderBy($sidx, $sord)
			->skip($start)
			->take($limit);
		$query1 = $query->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postMtmMedicationHistory($eid)
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('other_history')->where('eid', '=', $eid)->first();
		if($query) {
			$meds = $query->oh_meds;
			if ($meds != '') {
				$meds_array = explode("\n", trim($meds));
				$count = count($meds_array);
				$total_pages = ceil($count/$limit);
				if ($page > $total_pages) $page=$total_pages;
				$start = $limit*$page - $limit;
				if($start < 0) $start = 0;
				if ($sord == 'asc') {
					sort($meds_array);
				} else {
					rsort($meds_array);
				}
				$meds_array1 = array_slice($meds_array, $start, $limit);
				foreach($meds_array1 as $row) {
					$response['rows'][]['mtm_medication'] = $row;
				}
			} else {
				$count = 0;
				$total_pages = 0;
				$response['rows'] = '';
			}
		} else {
			$count = 0;
			$total_pages = 0;
			$response['rows'] = '';
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		echo json_encode($response);
	}
	
	public function postResultsEncounters()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id);
		$result = $query->get();
		if($result) { 
			$count = count($result);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query->orderBy($sidx, $sord)
			->skip($start)
			->take($limit);
		$query1 = $query->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postResultsEncountersHistory($eid)
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('other_history')->where('eid', '=', $eid)->first();
		if($query) {
			$oh_results = $query->oh_results;
			if ($oh_results != '') {
				$oh_results_array = explode("\n", trim($oh_results));
				$count = count($oh_results_array);
				$total_pages = ceil($count/$limit);
				if ($page > $total_pages) $page=$total_pages;
				$start = $limit*$page - $limit;
				if($start < 0) $start = 0;
				if ($sord == 'asc') {
					sort($oh_results_array);
				} else {
					rsort($oh_results_array);
				}
				$oh_results_array1 = array_slice($oh_results_array, $start, $limit);
				foreach($oh_results_array1 as $row) {
					$response['rows'][]['oh_results'] = $row;
				}
			} else {
				$count = 0;
				$total_pages = 0;
				$response['rows'] = '';
			}
		} else {
			$count = 0;
			$total_pages = 0;
			$response['rows'] = '';
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		echo json_encode($response);
	}
}
