<?php

class AjaxDashboardController extends BaseController {

	/**
	* NOSH ChartingSystem Dashboard Ajax Functions
	*/
	
	public function postDraftMessages()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$from = Session::get('displayname') . ' (' . Session::get('user_id') . ')';
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('t_messages')
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
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
			$query1 = DB::table('t_messages')
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
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
	}
	
	public function postDraftEncounters()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$user_id = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('encounters')
				->join('demographics', 'encounters.pid', '=', 'demographics.pid')
				->where('encounters.user_id', '=', $user_id)
				->where('encounters.encounter_signed', '=', 'No')
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
			$query1 = DB::table('encounters')
				->join('demographics', 'encounters.pid', '=', 'demographics.pid')
				->where('encounters.user_id', '=', $user_id)
				->where('encounters.encounter_signed', '=', 'No')
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
	}
	
	public function postAlerts()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$provider = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $provider)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array) {
					$query_array->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Radiology results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
				})
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
			$query1 = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $provider)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array1) {
					$query_array1->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Radiology results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
				})
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
	}
	
	public function postMtmAlerts()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$practice_id = Session::get('practice_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where('alerts.alert', '=', 'Medication Therapy Management')
				->where('alerts.practice_id', '=', $practice_id)
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
			$query1 = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where('alerts.alert', '=', 'Medication Therapy Management')
				->where('alerts.practice_id', '=', $practice_id)
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
	}
	
	public function postGetDraftMessage($id)
	{
		$result = DB::table('t_messages')->where('t_messages_id', '=', $id)->first();
		if (Session::get('t_messages_id')) {
			Session::forget('t_messages_id');
		};
		echo json_encode($result);
	}
	
	public function postGetAlert($id)
	{
		$result = DB::table('alerts')->where('alert_id', '=', $id)->first();
		if (Session::get('alert_id')) {
			Session::forget('alert_id');
		};
		echo json_encode($result);
	}
	
	// Provider specific functions
	public function postProviderInfo()
	{
		$result = Providers::find(Session::get('user_id'))->toArray();
		echo json_encode($result);
	}
	
	public function postProviderInfo1()
	{
		$data = array(
			'specialty' => substr(Input::get('specialty'), 0, -13),
			'license' => Input::get('license'),
			'license_state' => Input::get('license_state'),
			'npi' => Input::get('npi'),
			'npi_taxonomy' => substr(Input::get('specialty'), -11, 10),
			'upin' => Input::get('upin'),
			'dea' => Input::get('dea'),
			'medicare' => Input::get('medicare'),
			'tax_id' => Input::get('tax_id'),
			'rcopia_username' => Input::get('rcopia_username'),
			'schedule_increment' => Input::get('schedule_increment'),
			'peacehealth_id' => Input::get('peacehealth_id')
		);
		DB::table('providers')->where('id', '=', Input::get('id'))->update($data);
		$this->audit('Update'); 
		echo "Provider information updated!";
	}
	
	public function postCheckRcopia()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Practiceinfo::find(Session::get('practice_id'));
			echo $result->rcopia_extension;
		}
	}
	
	public function postPreviewSignature()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$signature = Providers::find(Session::get('user_id'));
			if ($signature->signature != '') {
				$result = HTML::image($signature->signature, 'Provider Signature', array('border' => '0'));
			} else {
				$result = '';
			}
			echo $result;
		}
	}
	
	public function postChangeSignature()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$id = Session::get('user_id');
			$user = User::find($id);
			$name = $user->firstname . " " . $user->lastname;
			if ($name != Input::get('name')) {
				echo "Incorrect name!  Signature not saved.  Try again.";
			} else {
				$base_dir = __DIR__."/../../public/";
				$filename = "images/signature_" . $id . "_" . time() . ".png";
				$json = Input::get('output');
				$img = $this->sigJsonToImage($json);
				imagepng($img, $base_dir . $filename);
				imagedestroy($img);
				$data = array(
					'signature' => $filename
				);
				DB::table('providers')->where('id', '=', $id)->update($data);
				$this->audit('Update');
				echo "Signature created!";
			}
		}
	}
	
	// Demographic functions
	public function postDemographics()
	{
		$row = Demographics::find(Session::get('pid'))->toArray();
		echo json_encode($row);
	}
	
	public function postEditDemographics()
	{
		$pid = Session::get('pid');
		$date_active = date('Y-m-d H:i:s', strtotime(Input::get('issue_date_active')));
		$dob = date('Y-m-d', strtotime(Input::get('DOB')));
		if (Input::get('reminder_method')=="Cellular Phone") {
			$meta = array("(", ")", "-", " ");
			$number = str_replace($meta, "", Input::get('phone_cell'));
			$reminder_to = $number . '@' . Input::get('cell_carrier');
		} else {
			if (Input::get('reminder_method')=="Email") {
				$reminder_to = Input::get('email');
			} else {
				$reminder_to = "";
			}
		}
		$data = array(
			'lastname' => Input::get('lastname'),
			'firstname' => Input::get('firstname'),
			'middle' => Input::get('middle'),
			'nickname' => Input::get('nickname'),
			'title' => Input::get('title'),
			'sex' => Input::get('gender'),
			'DOB'=> $dob,
			'ss' => Input::get('ss'),
			'race' => Input::get('race'),
			'ethnicity' => Input::get('ethnicity'),
			'language' => Input::get('language'),
			'address' => Input::get('address'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone_home' => Input::get('phone_home'),
			'phone_work' => Input::get('phone_work'),
			'phone_cell' => Input::get('phone_cell'),
			'email' => Input::get('email'),
			'marital_status' => Input::get('marital_status'),
			'partner_name' => Input::get('partner_name'),
			'employer' => Input::get('employer'),
			'emergency_contact' => Input::get('emergency_contact'),
			'emergency_phone' => Input::get('emergency_phone'),
			'reminder_method' => Input::get('reminder_method'),
			'reminder_to' => $reminder_to,
			'cell_carrier' => Input::get('cell_carrier'),
			'preferred_provider' => Input::get('preferred_provider'),
			'preferred_pharmacy' => Input::get('preferred_pharmacy'),
			'active' => Input::get('active'),
			'other1' => Input::get('other1'),
			'other2' => Input::get('other2'),
			'caregiver' => Input::get('caregiver'),
			'referred_by' => Input::get('referred_by'),
			'comments' => Input::get('comments'),
			'rcopia_sync' => 'n',
			'race_code' => Input::get('race_code'),
			'ethnicity_code' => Input::get('ethnicity_code'),
			'guardian_lastname' => Input::get('guardian_lastname'),
			'guardian_firstname' => Input::get('guardian_firstname'),
			'guardian_relationship' => Input::get('guardian_relationship'),
			'guardian_code' => Input::get('guardian_code'),
			'guardian_address' => Input::get('guardian_address'),
			'guardian_city' => Input::get('guardian_city'),
			'guardian_state' => Input::get('guardian_state'),
			'guardian_zip' => Input::get('guardian_zip'),
			'guardian_phone_home' => Input::get('guardian_phone_home'),
			'guardian_phone_work' => Input::get('guardian_phone_work'),
			'guardian_phone_cell' => Input::get('guardian_phone_cell'),
			'guardian_email' => Input::get('guardian_email'),
			'lang_code' => Input::get('lang_code')
		);
		DB::table('demographics')->where('pid', '=', $pid)->update($data);
		$this->audit('Update');
		$this->setpatient($pid);
		echo "OK";
	}
	
	public function postInsurance()
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'Yes')
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
		$query1 = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'Yes')
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
	
	public function postInsuranceInactive()
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'No')
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
		$query1 = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'No')
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
	
	public function postEditInsurance()
	{
		$pid = Session::get('pid');
		$dob = date("Y-m-d", strtotime(Input::get('insurance_insu_dob')));
		$data = array(
			'insurance_plan_name' => Input::get('insurance_plan_name'),
			'address_id' => Input::get('address_id'),
			'insurance_id_num' => Input::get('insurance_id_num'),
			'insurance_group' => Input::get('insurance_group'),
			'insurance_order' => Input::get('insurance_order'),
			'insurance_relationship' => Input::get('insurance_relationship'),
			'insurance_copay' => Input::get('insurance_copay'),
			'insurance_deductible' => Input::get('insurance_deductible'),
			'insurance_comments' => Input::get('insurance_comments'),
			'insurance_insu_lastname' => Input::get('insurance_insu_lastname'),
			'insurance_insu_firstname' => Input::get('insurance_insu_firstname'),
			'insurance_insu_dob' => $dob,
			'insurance_insu_gender' => Input::get('insurance_insu_gender'),
			'insurance_insu_address' => Input::get('insurance_insu_address'),
			'insurance_insu_city' => Input::get('insurance_insu_city'),
			'insurance_insu_state' => Input::get('insurance_insu_state'),
			'insurance_insu_zip' => Input::get('insurance_insu_zip'),
			'insurance_insu_phone' => Input::get('insurance_insu_phone'),
			'insurance_plan_active' => 'Yes',
			'pid' => $pid
		);	
		if(Input::get('insurance_id') == '') {
			DB::table('insurance')->insert($data);
			echo "Insurance added!";
		} else {
			DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
			$this->audit('Update');
			echo "Insurance updated!";
		}
	}
	
	public function postInactivateInsurance()
	{
		$data = array(
			'insurance_plan_active' => 'No'
		);
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
		$this->audit('Update');
		echo "Insurance inactivated!";
	}
	
	public function postDeleteInsurance()
	{
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->delete();
		$this->audit('Delete');
		echo "Insurance deleted!";
	}

	public function postReactivateInsurance()
	{
		$data = array(
			'insurance_plan_active' => 'Yes'
		);
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
		$this->audit('Update');
		echo "Insurance reactivated!";
	}
	
	public function postEditInsuranceProvider()
	{
		$data = array(
			'displayname' => Input::get('facility'),
			'facility' => Input::get('facility'),
			'street_address1' => Input::get('street_address1'),
			'street_address2' => Input::get('street_address2'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone' => Input::get('phone'),
			'insurance_plan_payor_id' => Input::get('insurance_plan_payor_id'),
			'insurance_plan_type' => Input::get('insurance_plan_type'),
			'insurance_plan_assignment' => Input::get('insurance_plan_assignment'),
			'insurance_plan_ppa_phone' => Input::get('insurance_plan_ppa_phone'),
			'insurance_plan_ppa_fax' => Input::get('insurance_plan_ppa_fax'),
			'insurance_plan_ppa_url' => Input::get('insurance_plan_ppa_url'),
			'insurance_plan_mpa_phone' => Input::get('insurance_plan_mpa_phone'),
			'insurance_plan_mpa_fax' => Input::get('insurance_plan_mpa_fax'),
			'insurance_plan_mpa_url' => Input::get('insurance_plan_mpa_url'),
			'specialty' => 'Insurance',
			'insurance_box_31' => Input::get('insurance_box_31'),
			'insurance_box_32a' => Input::get('insurance_box_32a')
		);
		if(Input::get('address_id') == '') {
			$add = DB::table('addressbook')->insertGetId($data);
			$this->audit('Add');
			$result['message'] = "Insurance provider added!";
			$result['item'] = $data['displayname'];
			$result['id'] = $add;
			
		} else {
			DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->update($data);
			$this->audit('Update');
			$result['message'] = "Insurance provider updated!";
			$result['item'] = $data['displayname'];
			$result['id'] = Input::get('address_id');
		}
		echo json_encode($result);
	}
	
	public function postCopyAddress()
	{
		$row = Demographics::find(Session::get('pid'))->toArray();
		echo json_encode($row);
	}
	
	public function postCheckRegistrationCode()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Demographics::find(Session::get('pid'));
			if ($result->registration_code != '') {
				echo "Registration Code: " . $result->registration_code;
			} else {
				echo "n";
			}
		}
	}
	
	public function postRegisterPatient()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$length = 6;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$token = '';
			for ($i = 0; $i < $length; $i++) {
				$token .= $characters[mt_rand(0, strlen($characters)-1)];
			}
			$data = array(
				'registration_code' => $token
			);
			DB::table('demographics')->where('pid', '=', $pid)->update($data);
			$this->audit('Update');
			$result = Demographics::find($pid);
			if ($result->email != '') {
				$practice = Practiceinfo::find(Session::get('practice_id'));
				$data1 = array(
					'practicename' => $practice->practice_name,
					'url' => route('/'),
					'token' => $token
				);
				Mail::send('emails.loginregistrationcode', $data1, function($message){
					$message->to($result->email)
						->from($practice->email, $practice->practice_name)
						->subject('Patient Portal Registration Code');
				});
			}
			echo "Registration Code: " . $token;
		}
	}
	
	// Test result functions
	public function postTests()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('tests')
				->whereNull('pid')
				->where('practice_id', '=', Session::get('practice_id'))
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
			$query1 = DB::table('tests')
				->whereNull('pid')
				->where('practice_id', '=', Session::get('practice_id'))
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['rows'] = $query1;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			$response['page'] = $page;
			if ($query1) {
				$response['rows'] = $query1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postTestsImport()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Input::get('pid');
			$tests_id_array = json_decode(Input::get('tests_id_array'));
			$i = 0;
			$results = array();
			foreach ($tests_id_array as $tests_id) {
				$data = array(
					'pid' => $pid,
					'test_unassigned' => ''
				);
				DB::table('tests')->where('tests_id', '=', $tests_id)->update($data);
				$this->audit('Update');
				$results[$i] = Tests::find($tests_id)->toArray();
				$provider_id = $results[$i]['test_provider_id'];
				$from = $results[$i]['test_from'];
				$test_type = $results[$i]['test_type'];
				$i++;
			}
			$patient_row = Demographics::find($pid)->toArray();
			$dob_message = date("m/d/Y", strtotime($patient_row['DOB']));
			$patient_name =  $patient_row['lastname'] . ', ' . $patient_row['firstname'] . ' (DOB: ' . $dob_message . ') (ID: ' . $pid . ')';
			$practice_row = Practiceinfo::find(Session::get('practice_id'))->toArray();
			$directory = $practice_row['documents_dir'] . $pid;
			$file_path = $directory . '/tests_' . time() . '.pdf';
			$html = $this->page_intro('Test Results', $practice_id);
			$html .= $this->page_results($pid, $results, $patient_name);
			$this->generate_pdf($html, $file_path);
			$documents_date = date("Y-m-d H:i:s", time());
			$test_desc = 'Test results for ' . $patient_name;
			$pages_data = array(
				'documents_url' => $file_path,
				'pid' => $pid,
				'documents_type' => $test_type,
				'documents_desc' => $test_desc,
				'documents_from' => $from,
				'documents_date' => $documents_date
			);
			if(Session::get('group_id') == '2') {
				$pages_data['documents_viewed'] = Session::get('displayname');
			}
			$documents_id = DB::table('documents')->insertGetId($pages_data);
			$this->audit('Add');
			if(Session::get('group_id') == '3') {
				$provider_row = User::find($provider_id)->toArray();
				$provider_name = $provider_row['firstname'] . " " . $provider_row['lastname'] . ", " . $provider_row['title'] . " (" . $provider_id . ")";
				$subject = "Test results for " . $patient_name;
				$body = "Test results for " . $patient_name . "\n\n";
				foreach ($results as $results_row1) {
					$body .= $results_row1['test_name'] . ": " . $results_row1['test_result'] . ", Units: " . $results_row1['test_units'] . ", Normal reference range: " . $results_row1['test_reference'] . ", Date: " . $results_row1['test_datetime'] . "\n";
				}
				$body .= "\n" . $from;
				$data_message = array(
					'pid' => $pid,
					'message_to' => $provider_name,
					'message_from' => Session::get('user_id'),
					'subject' => $subject,
					'body' => $body,
					'patient_name' => $patient_name,
					'status' => 'Sent',
					'mailbox' => $provider_id,
					'practice_id' => $practice_id,
					'documents_id' => $documents_id
				);
				DB::table('messaging')->insert($data_message);
				$this->audit('Add');
			}
			echo $i;
		}
	}
	
	public function postDeleteTests()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$this->db->where('tests_id', $this->input->post('tests_id'));
			$this->db->delete('tests');
			echo "OK";
		}
	}
	
	// Admin specific functions
	public function postFindbackups()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row2 = Practiceinfo::find(1);
			$dir = $row2->documents_dir;
			$files = glob($dir . "*.sql");
			$data['options'] = array();
			arsort($files);
			foreach ($files as $file) {
				$explode = explode("_", $file);
				$time = intval(str_replace(".sql","",$explode[1]));
				$data['options'][$file] = date("Y-m-d H:i:s", $time);
			}
			echo json_encode($data);
		}
	}
	
	public function postBackuprestore()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$file = Input::get('file');
			$config_file = __DIR__."/../.env.php";
			$config = require($config_file);
			$command = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $config['mysql_database'] . " < " . $file;
			system($command);
			echo "OK";
		}
	}
	
	public function postCheckPrintEntireChart()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array();
			$query = Demographics_relate::where('practice_id', '=', Session::get('practice_id'))->first();
			if ($query) {
				$data['response'] = true;
				Session::put('print_chart_percent', '0');
			} else {
				$data['response'] = false;
				$data['message'] = "No patients in your practice!";
			}
			echo json_encode($data);
		}
	}
	
	public function postPrintEntireChart()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			$practice_id = Session::get('practice_id');
			$query = Demographics_relate::where('practice_id', '=', $practice_id)->get();
			$total = count($query);
			$i=0;
			$data = array();
			$zip_file_name = __DIR__.'/../../public/temp/charts_' . $practice_id . '.zip';
			if (file_exists($zip_file_name)) {
				unlink($zip_file_name);
			}
			Zipper::make($zip_file_name);
			foreach ($query as $row) {
				$file = $this->print_chart($row->pid, 'file', '', 'all');
				Zipper::add($file);
				$i++;
				$percent = round($i/$total*100);
				Session::put('print_chart_percent', $percent);
			}
			Zipper::close();
			$data['response'] = true;
			$data['html'] = HTML::secureLink('temp/charts_' . $practice_id . '.zip', 'Download ZIP File');
			echo json_encode($data);
		}
	}
	
	public function postPrintEntireChartProgress()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			echo Session::get('print_chart_percent');
		}
	}
	
	public function postCheckCsvPatientDemographics()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$query = Demographics_relate::where('practice_id', '=', Session::get('practice_id'))->first();
			if ($query) {
				$data['response'] = true;
				Session::put('csv_percent', '0');
			} else {
				$data['response'] = false;
				$data['message'] = "No patients in your practice!";
			}
			echo json_encode($data);
		}
	}
	
	function postGenerateCsvPatientDemographics()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			$practice_id = Session::get('practice_id');
			$query = Demographics_relate::where('practice_id', '=', $practice_id)->get();
			$total = count($query);
			$i=0;
			$csv = "Last Name;First Name;Gender;Date of Birth;Address;City;State;Zip;Home Phone;Work Phone;Cell Phone";
			foreach ($query as $row) {
				$row1 = DB::table('demographics')
					->select('lastname', 'firstname', 'sex', 'DOB', 'address', 'city', 'state', 'zip', 'phone_home', 'phone_work', 'phone_cell')
					->where('pid', '=', $row->pid)
					->first();
				$csv .= "\n";
				$array = (array) $row1;
				$csv .= implode(";", $array);
				$i++;
				$percent = round($i/$total*100);
				Session::put('csv_percent', $percent);
			}
			$csv_file_name = __DIR__.'/../../public/temp/csv_' . $practice_id . '.csv';
			if (file_exists($csv_file_name)) {
				unlink($csv_file_name);
			}
			File::put($csv_file_name, $csv);
			$data['message'] = "OK";
			$data['html'] = HTML::secureLink('temp/csv_' . $practice_id . '.csv', 'Download CSV File');
			echo json_encode($data);
		}
	}
	
	public function postCsvProgress()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			echo Session::get('csv_percent');
		}
	}
	
	// Configuration dialog functions
	public function postOrdersList($type, $user_type)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3' && Session::get('group_id') != '4') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if ($user_type == 'Global') {
				$user_id = '0';
			} else {
				$user_id = Session::get('user_id');
			}
			$user_id = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('orderslist')
				->where('orders_category', '=', $type)
				->where('user_id', '=', $user_id)
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
			$query1 = DB::table('orderslist')
				->where('orders_category', '=', $type)
				->where('user_id', '=', $user_id)
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
	}
	
	public function postAddOrderslist()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'orders_category' => Input::get('orders_category'),
				'orders_description' => Input::get('orders_description'),
				'cpt' => Input::get('cpt'),
				'snomed'=> Input::get('snomed'),
				'user_id' => Input::get('user_id')
			);
			if (Input::get('orderslist_id') == '') {
				DB::table('orderslist')->insert($data);
				$this->audit('Add');
				$message = "Entry added as a template!";
			} else {
				DB::table('orderslist')->where('orderslist_id', '=', Input::get('orderslist_id'))->update($data);
				$this->audit('Update');
				$message = "Entry updated as a template!";
			}
			echo $message;
		}
	}
	
	public function postCheckSnomedExtension()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		echo $result->snomed_extension;
	}
	
	public function postDeleteOrdersList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('orderslist')->where('orderslist_id', '=', Input::get('orderslist_id'))->delete();
			$this->audit('Delete');
			echo "Template entry deleted.";
		}
	}
	
	public function postCptList($mask='')
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			if($mask == ''){
				$query = DB::table('cpt')->get();
			} else {
				$query = DB::table('cpt')
					->where('cpt_description', 'LIKE', "%$mask%")
					->orWhere('cpt', 'LIKE', "%$mask%")
					->get();
			}
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
			if($mask == ''){
				$query1 = DB::table('cpt')
					->orderBy($sidx, $sord)
					->skip($start)
					->take($limit)
					->get();
			} else {
				$query1 = DB::table('cpt')
					->where('cpt_description', 'LIKE', "%$mask%")
					->orWhere('cpt', 'LIKE', "%$mask%")
					->orderBy($sidx, $sord)
					->skip($start)
					->take($limit)
					->get();
			}
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $records_row) {
					$query2_row = DB::table('cpt_relate')->where('cpt', '=', $records_row->cpt)
						->where('practice_id', '=', Session::get('practice_id'))
						->first();
					if ($query2_row) {
						$records1[$i] = array(
							'cpt_id' => $records_row->cpt_id,
							'cpt_relate_id' => $query2_row->cpt_relate_id,
							'cpt' => $query2_row->cpt,
							'cpt_description' => $query2_row->cpt_description,
							'cpt_charge' => $query2_row->cpt_charge,
							'favorite' => $query2_row->favorite,
							'unit' => $query2_row->unit
						);
					} else {
						$records1[$i] = array(
							'cpt_id' => $records_row->cpt_id,
							'cpt_relate_id' => '',
							'cpt' => $records_row->cpt,
							'cpt_description' => $records_row->cpt_description,
							'cpt_charge' => '',
							'favorite' => '0',
							'unit' => '1'
						);
					}
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function edit_cpt_list()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'cpt' => Input::get('cpt'),
				'cpt_description' => Input::get('cpt_description'),
			);
			if (Input::get('cpt_id') != '') {
				DB::table('cpt')->where('cpt_id', '=', Input::get('cpt_id'))->update($data);
				$this->audit('Update');
				$arr['message'] = "CPT code updated!";
			} else {
				DB::table('cpt')->insert($data);
				$this->audit('Add');
				$arr['message'] = "CPT code added!";
			}
			if (Input::get('cpt_charge') != '') {
				$charge = str_replace("$", "", Input::get('cpt_charge'));
				$pos = strpos($charge, ".");
				if ($pos === FALSE) {
					$charge .= ".00";
				}
				$data_relate = array(
					'cpt' => Input::get('cpt'),
					'cpt_description' => Input::get('cpt_description'),
					'cpt_charge' => $charge,
					'practice_id' => Session::get('practice_id'),
					'favorite' => Input::get('favorite'),
					'unit' => Input::get('unit')
				);
				if (Input::get('cpt_relate_id') != '') {
					DB::table('cpt_relate')->where('cpt_relate_id', '=', Input::get('cpt_relate_id'))->update($data_relate);
					$this->audit('Update');
					$arr['message'] .= "  CPT charge updated!";
				} else {
					DB::table('cpt_relate')->insert($data_relate);
					$this->audit('Add');
					$arr['message'] .= "  CPT charge added!";
				}
				$arr['charge'] = $charge;
			} else {
				$arr['charge'] = '';
			}
			echo json_encode($arr);
		}
	}
	
	public function postDeleteCpt()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('cpt')->where('cpt_id', '=', Input::get('id'))->delete();
			$this->audit('Delete');
			echo "CPT code deleted!";
		}
	}
	
	public function postGetTemplate()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row = Templates::find(Input::get('template_id'));
			$array = unserialize($row->array);
			echo $array;
		}
	}
	
	public function postDeleteTemplate()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('templates')->where('template_id', '=', Input::get('template_id'))->delete();
			$this->audit('Delete');
			echo "Form template deleted!";
		}
	}
	
	public function postPatientFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'forms')
				->where('practice_id', '=', $practice_id)
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
			$query1 = DB::table('templates')
				->where('category', '=', 'forms')
				->where('practice_id', '=', $practice_id)
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $row) {
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSavePatientForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postHpiFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'hpi')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'hpi')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $row) {
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveHpiForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postRosFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'ros')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'ros')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $row) {
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveRosForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => 'm',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => 'f',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => Input::get('sex'),
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postPeFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'pe')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'pe')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $row) {
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSavePeForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => 'm',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => 'f',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => Input::get('sex'),
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postCheckFax()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		if ($result->fax_type != "") {
			echo "Yes";
		} else {
			echo "No";
		}
	}
}
