<?php

class AjaxSearchController extends BaseController {

	/**
	* NOSH ChartingSystem Search Ajax Functions
	*/
	
	public function postSearch()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->select('demographics.lastname', 'demographics.firstname', 'demographics.DOB', 'demographics.pid')
			->where('demographics_relate.practice_id', '=', Session::get('practice_id'))
			->where(function($query_array1) use ($q) {
				$query_array1->where('demographics.lastname', 'LIKE', "%$q%")
				->orWhere('demographics.firstname', 'LIKE', "%$q%")
				->orWhere('demographics.pid', 'LIKE', "%$q%");
			})
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$dob = date('m/d/Y', strtotime($row->DOB));
				$id = $row->pid;
				$name = $row->lastname . ', ' . $row->firstname . ' (DOB: ' . $dob . ') (ID: ' . $row->pid . ')';
				$data['message'][] = array(
					'id' => $id,
					'label' => $name,
					'value' => $name
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postOpenchart()
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
		$pid = Input::get('pid');
		$this->setpatient($pid);
		$data['message'] = 'OK';
		$data['url'] = route('chart');
		echo json_encode($data);
	}
	
	public function postEidset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Session::get('eid') != FALSE) {
				Session::forget('eid');
			}
			Session::put('eid', Input::get('eid'));
			Session::put('encounter_active', 'y');
			$encounter = DB::table('encounters')->where('eid', '=', Input::get('eid'))->first();
			Session::put('encounter_template', $encounter->encounter_template);
			Session::put('encounter_DOS', $encounter->encounter_DOS);
			$data['message'] = 'OK';
			$data['url'] = route('chart');
			echo json_encode($data);
		}
	}
	
	public function postTmessagesidset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Session::get('t_messages_id') != FALSE) {
				Session::forget('t_messages_id');
			}
			Session::put('t_messages_id', Input::get('t_messages_id'));
			$data['message'] = 'OK';
			$data['url'] = route('chart');
			echo json_encode($data);
		}
	}
	
	public function postAlertidset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Session::get('alert_id') != FALSE) {
				Session::forget('alert_id');
			}
			Session::put('alert_id', Input::get('alert_id'));
			$data['message'] = 'OK';
			$data['url'] = route('chart');
			echo json_encode($data);
		}
	}
	
	public function postMtmset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Session::get('mtm') != FALSE) {
				Session::forget('mtm');
			}
			Session::put('mtm', 'open');
			$data['message'] = 'OK';
			$data['url'] = route('chart');
			echo json_encode($data);
		}
	}
	
	public function postMtmunset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			Session::forget('mtm');
			echo 'OK';
		}
	}
	
	public function postHedisSet()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Session::get('hedis') != FALSE) {
				Session::forget('hedis');
			}
			Session::put('hedis', 'open');
			$data['message'] = 'OK';
			$data['url'] = route('chart');
			echo json_encode($data);
		}
	}
	
	public function postHedisUnset()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			Session::forget('hedis');
			echo 'OK';
		}
	}
	
	public function postNewpatient()
	{
		$dob = date('Y-m-d', strtotime(Input::get('DOB')));
		$practice_id = Session::get('practice_id');
		$data = array(
			'lastname' => Input::get('lastname'),
			'firstname' => Input::get('firstname'),
			'DOB' => $dob,
			'sex' => Input::get('gender'),
			'active' => '1',
			'sexuallyactive' => 'no',
			'tobacco' => 'no',
			'pregnant' => 'no'
		);
		$pid = DB::table('demographics')->insertGetId($data);
		$this->audit('Add');
		$data1 = array(
			'billing_notes' => '',
			'imm_notes' => '',
			'pid' => $pid,
			'practice_id' => $practice_id
		);
		DB::table('demographics_notes')->insert($data1);
		$this->audit('Add');
		$data2 = array(
			'pid' => $pid,
			'practice_id' => $practice_id
		);
		DB::table('demographics_relate')->insert($data2);
		$this->audit('Add');
		$result = Practiceinfo::find($practice_id);
		$directory = $result->documents_dir . $pid;
		mkdir($directory, 0775);
		$json = array(
			'pid' => $pid,
			'message' => Input::get('firstname') . ' ' . Input::get('lastname') . ' added!'
		);
		echo json_encode($json);
	}
	
	public function postAddress()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('demographics')
			->where('address', 'LIKE', "%$q%")
			->select('address')
			->distinct()
			->get();
		if ($query > 0) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->address,
					'value' => $row->address
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postCity()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('demographics')
			->where('city', 'LIKE', "%$q%")
			->select('city')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->city,
					'value' => $row->city
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postGuardianRelationship()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('guardian_roles')
			->where('description', 'LIKE', "%$q%")
			->select('description', 'code')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->description,
					'value' => $row->description,
					'code' => $row->code
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postLanguage()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('lang')
			->where('description', 'LIKE', "%$q%")
			->select('description', 'code')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->description,
					'value' => $row->description,
					'code' => $row->code
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postProvider()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('users')
			->where('group_id', '=', '2')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('displayname', 'LIKE', "%$q%")
			->select('displayname', 'id')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname,
					'id' => $row->id
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postProviderSelect()
	{
		$query = DB::table('users')
			->where('group_id', '=', '2')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('active', '=', '1')
			->select('displayname', 'id')
			->distinct()
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$data[$row->id] = $row->displayname;
			}
		}
		echo json_encode($data);
	}
	
	public function postProviderSelect1()
	{
		$query = DB::table('users')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('active', '=', '1')
			->select('displayname', 'id')
			->where(function($query_array1) {
				$query_array1->where('group_id', '=', '2')
				->orWhere('group_id', '=', '3');
			})
			->distinct()
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$data[$row->id] = $row->displayname;
			}
		}
		echo json_encode($data);
	}
	
	public function postSpecialty()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Npi::where('classification', 'LIKE', "%$q%")->orWhere('specialization', 'LIKE', "%$q%")->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				if ($row->specialization != '') {
					$records = $row->classification . ', ' . $row->specialization . ' (' . $row->code . ')';
				} else {
					$records = $row->classification . ' (' . $row->code . ')';
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSpecialty1()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('npi')
			->where('classification', 'LIKE', "%$q%")
			->orWhere('specialization', 'LIKE', "%$q%")
			->select('classification', 'specialization')
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				if ($row->specialization != '') {
					$records = $row->classification . ', ' . $row->specialization;
				} else {
					$records = $row->classification;
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postPos()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Pos::where('pos_id', 'LIKE', "%$q%")->orWhere('pos_description', 'LIKE', "%$q%")->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->pos_description . ' (' . $row->pos_id . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row->pos_id
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAllContacts()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('addressbook')
			->where('displayname', 'LIKE', "%$q%")
			->select('displayname', 'fax')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname,
					'fax' => $row->fax
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAllContacts1()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('addressbook')
			->where('displayname', 'LIKE', "%$q%")
			->select('displayname')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname,
					'id' => $row->address_id
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAllContacts2()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Addressbook::where('displayname', 'LIKE', "%$q%")
			->where('specialty', '!=', 'Pharmacy')
			->where('specialty', '!=', 'Laboratory')
			->where('specialty', '!=', 'Radiology')
			->where('specialty', '!=', 'Cardiopulmonary')
			->where('specialty', '!=', 'Insurance')
			->select('displayname', 'specialty')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname . ", Specialty: " . $row->specialty,
					'value' => $row->displayname,
					'id' => $row->address_id,
					'npi' => $row->npi
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postInsurance1()
	{
		$row = Addressbook::find(Input::get('address_id'))->toArray();
		echo json_encode($row);
	}
	
	public function postInsurance3()
	{
		$query = Addressbook::where('specialty', '=', 'Insurance')->get();
		$data['response'] = 'false';
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][$row->address_id] = $row->displayname;
			}
		}
		echo json_encode($data);
	}
	
	public function postPharmacy()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('addressbook')
			->where('specialty', '=', 'Pharmacy')
			->where('displayname', 'LIKE', "%$q%")
			->select('displayname', 'fax')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname,
					'fax' => $row->fax
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postDocumentFrom()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Addressbook::where('displayname', 'LIKE', "%$q%")
			->select('displayname')
			->distinct()
			->get();
		$query1 = Documents::where('documents_from', 'LIKE', "%$q%")
			->select('documents_from')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname
				);
			}
		}
		if ($query1) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query1 as $row1) {
				$data['message'][] = array(
					'label' => $row1->documents_from,
					'value' => $row1->documents_from
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postDocumentDescription()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Documents::where('documents_desc', 'LIKE', "%$q%")
			->select('documents_desc')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->documents_desc,
					'value' => $row->documents_desc
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postDemographicsCopy()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->where('demographics_relate.practice_id', '=', Session::get('practice_id'))
			->where(function($query_array1) use ($q) {
				$query_array1->where('demographics.lastname', 'LIKE', "%$q%")
				->orWhere('demographics.firstname', 'LIKE', "%$q%")
				->orWhere('demographics.pid', 'LIKE', "%$q%");
			})
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$dob = date('m/d/Y', strtotime($row->DOB));
				$name =  $row->lastname . ', ' . $row->firstname . ' (DOB: ' . $dob . ') (ID: ' . $row->pid . ')';
				$data['message'][] = array(
					'label' => $name,
					'value' => $name,
					'address' => $row->address,
					'city' => $row->city,
					'state' => $row->state,
					'zip' => $row->zip,
					'phone_home' => $row->phone_home,
					'phone_work' => $row->phone_work,
					'phone_cell' => $row->phone_cell,
					'email' => $row->email,
					'emergency_contact' => $row->emergency_contact,
					'emergency_phone' => $row->emergency_phone,
					'reminder_method' => $row->reminder_method,
					'cell_carrier' => $row->cell_carrier
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postCc()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = Encounters::where('encounter_cc', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->select('encounter_cc')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->encounter_cc,
					'value' => $row->encounter_cc
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postGetAppointments($user_id)
	{
		$start_time = time() - 604800;
		$end_time = time() + 604800;
		$query = Schedule::where('provider_id', '=', $user_id)
			->where('pid', '=', Session::get('pid'))
			->whereBetween('start', array($start_time, $end_time))
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$key = $row->visit_type . ',' . $row->appt_id;
				$value = date('Y-m-d H:i:s A', $row->start) . ' (Appt ID: ' . $row->appt_id . ')';
				$data[$key] = $value;
			}
		}
		echo json_encode($data);
	}
	
	public function postGetCopay()
	{
		$query = Insurance::where('pid', '=', Session::get('pid'))->where('insurance_plan_active', '=', 'Yes')->get();
		if ($query) {
			$i = 0;
			$result = "";
			foreach ($query as $row) {
				if ($i > 0) {
					$result .= "<br><br>";
				}
				$result .= 'Insurance: ' . $row->insurance_plan_name . '; ID: ' . $row->insurance_id_num . '; Group: ' . $row->insurance_group;
				if ($row->insurance_copay != '') {
					$result .= '<br>Copay: ' . $row->insurance_copay; 
				}
				if ($row->insurance_deductible != '') {
					$result .= '<br>Deductible: ' . $row->insurance_deductible; 
				}
				if ($row->insurance_comments != '') {
					$result .= '<br>Comments: ' . $row->insurance_comments; 
				}
				$i++;
			}
		} else {
			$result = "None.";
		}
		echo $result;
	}
	
	public function postGetTags ($type, $id)
	{
		$query = Tags_relate::where($type, '=', $id)->get();
		$result = "";
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$row1 = Tags::where('tags_id', '=', $row->tags_id)->first();
				$data[] = array(
					'label' => $row1->tag,
					'value' => $row1->tag
				);
			}
		}
		if ($type == 'eid') {
			Session::put('encounter_active', 'n');
		}
		echo json_encode($data);
	}
	
	public function postAllUsers()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('users')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('active', '=', '1')
			->where(function($query_array1) use ($q) {
				$query_array1->where('displayname', 'LIKE', "%$q%")
				->orWhere('id', 'LIKE', "%$q%");
			})
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->displayname . ' (' . $row->id . ')';
				$data['message'][] = array(
					'id' => $row->id,
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAllUsers2()
	{
		$data = array();
		if (Session::get('group_id') == '100') {
			$query = DB::table('users')->where('group_id', '!=', '100')->where('group_id', '!=', '1')->where('practice_id', '=', Session::get('practice_id'))->get();
		} else {
			$query = DB::table('users')->where('group_id', '!=', '1')->where('practice_id', '=', Session::get('practice_id'))->get();
		}
		if ($query) {
			foreach ($query as $row) {
				$records = $row->displayname . ' (' . $row->id . ')';
				$data[$records] = $records;
			}
		}
		echo json_encode($data);
	}
	
	public function postPatientIsUser()
	{
		$pid = Session::get('pid');
		$row = DB::table('demographics_relate')
			->where('pid', '=', $pid)
			->where('practice_id', '=', Session::get('practice_id'))
			->first();
		$data = array();
		$data['message'] = 'no';
		if ($row->id != '') {
			$data['message'] = "yes";
			$row1 = User::find($row->id);
			$data['messages_to'] = $row1->displayname . ' (' . $row1->id . ')';
			$data['messages_patient'] = $row1->lastname . ', ' . $row1->firstname . ' (DOB: ' . date('m/d/Y', strtotime($row1->DOB)) . ') (ID: ' . $pid . ')';
			$data['pid'] = $pid;
		}
		echo json_encode($data);
	}
	
	// Tag Functions
	public function postSearchTags()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('tags')
			->join('tags_relate', 'tags_relate.tags_id', '=', 'tags.tags_id')
			->select('tags.tag')
			->where('tags.tag', 'LIKE', "%$q%")
			->where('tags_relate.practice_id', '=', Session::get('practice_id'))
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->tag,
					'value' => $row->tag,
					'category' => 'Previous Tags'
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSearchTags1()
	{
		$query = DB::table('tags')
			->join('tags_relate', 'tags_relate.tags_id', '=', 'tags.tags_id')
			->select('tags.tags_id','tags.tag')
			->where('tags_relate.practice_id', '=', Session::get('practice_id'))
			->distinct()
			->get();
		if ($query) {
			$data['message'] = 'OK';
			foreach ($query as $row) {
				$i = $row->tags_id;
				$data[$i] = $row->tag;
			}
		} else {
			$data['message'] = "No tags available.";
		}
		echo json_encode($data);
	}
	
	public function postSaveTag($type, $id)
	{
		$row1 = Tags::where('tag', '=', Input::get('tag'))->first();
		if ($row1) {
			$tags_id = $row1->tags_id;
		} else {
			$data1 = array(
				'tag' => Input::get('tag')
			);
			$tags_id = DB::table('tags')->insertGetId($data1);
			$this->audit('Add');
		}
		$data2 = array(
			'tags_id' => $tags_id,
			$type => $id,
			'pid' => Session::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		DB::table('tags_relate')->insert($data2);
		$this->audit('Add');
	}
	
	public function postRemoveTag($type, $id)
	{
		$row = Tags::where('tag', '=', Input::get('tag'))->first();
		DB::table('tags_relate')->where('tags_id', '=', $row->tags_id)->where($type, '=', $id)->delete();
		$this->audit('Delete');
	}
	
	// SNOMED functions
	public function postSnomed($type)
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$p = "(" . $type . ")";
		$query = DB::table('curr_description_f')
			->select('term', 'conceptid')
			->where('term', 'LIKE', "%$q%")
			->where('term', 'LIKE', "%$p%")
			->where('active', '=', '1')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->term . ", Code: " . $row->conceptid,
					'value' => $row->conceptid
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSnomedParent($type)
	{
		$query = DB::table('curr_relationship_f')
			->select('sourceid')
			->distinct()
			->where('typeid', '=','116680003')
			->where('active', '=', '1');
		if ($type == "imaging") {
			$query->where('destinationid', '=', '371571005');
		}
		if ($type == "lab") {
			$query->where('destinationid', '=', '15220000');
		}
		if ($type == "cp") {
			$query->where('destinationid', '=', '276341003');
			$query->orWhere('destinationid', '=', '23426006');
		}
		if ($type == "ref") {
			$query>where('destinationid', '=', '281100006');
		}
		$result = $query->get();
		$arr = array();
		if ($result) {
			foreach ($result as $row) {
				$term_row = DB::table('curr_description_f')->where('conceptid', '=', $row->sourceid)->where('active', '=', '1')->first();
				$arr[] = array(
					'data' => $term_row->term,
					'attr' => array('id' => $row->sourceid),
					'state' => 'closed'
				);
			}
		}
		echo json_encode($arr);
	}
	
	public function postSnomedChild($id)
	{
		$query = DB::table('curr_relationship_f')
			->where('destinationid', '=', $id)
			->where('typeid', '=','116680003')
			->where('active', '=', '1')
			->select('sourceid')
			->distinct()
			->get();
		$arr = array();
		if ($query) {
			foreach ($query as $row) {
				$term_row = DB::table('curr_description_f')->where('conceptid', '=', $row->sourceid)->where('active', '=', '1')->first();
				$arr[] = array(
					'data' => $term_row->term,
					'attr' => array('id' => $row->sourceid),
					'state' => 'closed'
				);
			}
		}
		echo json_encode($arr);
	}
	
	public function postCpt()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('cpt')->select('cpt', 'cpt_description');
		$pos = explode(',', $q);
		if ($pos == FALSE) {
			$query->where('cpt_description', 'LIKE', "%$q%");
		} else {
			foreach ($pos as $p) {
				$query->where('cpt_description', 'LIKE', "%$p%");
			}
		}
		$query->orWhere('cpt', 'LIKE', "%$q%");
		$result = $query->get();
		if ($result) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($result as $row) {
				$records = $row->cpt_description . ' [' . $row->cpt . ']';
				$data['message'][] = array(
					'label' => $records,
					'value' => $row->cpt
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postCpt1()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		if ($q == "***") {
			$query0 = DB::table('cpt_relate')->where('favorite', '=', '1')->where('practice_id', '=', Session::get('practice_id'))->get();
			if ($query0) {
				$data['response'] = 'true';
				foreach ($query0 as $row0) {
					$records0 = $row0->cpt_description . ' [' . $row0->cpt . ']';
					$data['message'][] = array(
						'label' => $records0,
						'value' => $row0->cpt,
						'charge' => $row0->cpt_charge,
						'unit' => $row0->unit,
						'category' => 'Favorites'
					);
				}
			}
		} else {
			$pos2 = explode(',', $q);
			$query2 = DB::table('cpt_relate')->where('practice_id', '=', Session::get('practice_id'));
			if ($pos2 == FALSE) {
				$query2->where(function($query_array1) use ($q) {
					$q = strtolower(Input::get('term'));
					$query_array1->where('cpt_description', 'LIKE', "%$q%")
					->orWhere('cpt', 'LIKE', "%$q%");
				});
			} else {
				$query2->where(function($query_array1) use ($q, $pos2) {
					foreach ($pos2 as $r) {
						$query_array1->where('cpt_description', 'LIKE', "%$r%");
					}
					$query_array1->orWhere('cpt', 'LIKE', "%$q%");
				});
			}
			$result2 = $query2->get();
			if ($result2) {
				$data['response'] = 'true';
				foreach ($result2 as $row2) {
					$records2 = $row2->cpt_description . ' [' . $row2->cpt . ']';
					$data['message'][] = array(
						'label' => $records2,
						'value' => $row2->cpt,
						'charge' => $row2->cpt_charge,
						'unit' => $row2->unit,
						'category' => 'Practice CPT Database'
					);
				}
			}
			$pos1 = explode(',', $q);
			$query1 = DB::table('cpt');
			if ($pos1 == FALSE) {
				$query1->where('cpt_description', 'LIKE', "%$q%");
			} else {
				foreach ($pos1 as $p) {
					$query1->where('cpt_description', 'LIKE', "%$p%");
				}
			}	
			$query1->orWhere('cpt','LIKE', "%$q%");
			$result1 = $query1->get();
			if ($result1) {
				$data['response'] = 'true';
				foreach ($result1 as $row1) {
					$records1 = $row1->cpt_description . ' [' . $row1->cpt . ']';
					$data['message'][] = array(
						'label' => $records1,
						'value' => $row1->cpt,
						'charge' => $row1->cpt_charge,
						'unit' => '1',
						'category' => 'Universal CPT Database'
					);
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postIcd()
	{
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		if ($practice->icd == '9') {
			$query = DB::table('icd9')->select('icd9', 'icd9_description');
			$pos = explode(',', $q);
			if ($pos == FALSE) {
				$query->where('icd9_description', 'LIKE', "%$q%");
			} else {
				foreach ($pos as $p) {
					$query->where('icd9_description', 'LIKE', "%$p%");
				}
			}
			$query->orWhere('icd9', 'LIKE', "%$q%");
			$result = $query->get();
			if ($result) {
				$data['message'] = array();
				$data['response'] = 'true';
				foreach ($result as $row) {
					$records = $row->icd9_description . ' [' . $row->icd9 . ']';
					$data['message'][] = array(
						'label' => $records,
						'value' => $records
					);
				}
			}
		} else {
			$query = DB::table('icd10')->select('icd10', 'icd10_description');
			$pos = explode(',', $q);
			if ($pos == FALSE) {
				$query->where('icd10_description', 'LIKE', "%$q%");
			} else {
				foreach ($pos as $p) {
					$query->where('icd10_description', 'LIKE', "%$p%");
				}
			}
			$query->orWhere('icd10', 'LIKE', "%$q%");
			$result = $query->get();
			if ($result) {
				$data['message'] = array();
				$data['response'] = 'true';
				foreach ($result as $row) {
					$records = $row->icd10_description . ' [' . $row->icd10 . ']';
					$data['message'][] = array(
						'label' => $records,
						'value' => $records
					);
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postRxName($alt='0')
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		if ($alt == '1') {
			$query = DB::table('rx_list')
				->where('rxl_medication', 'LIKE', "%$q%")
				->select('rxl_medication', 'rxl_dosage', 'rxl_dosage_unit', 'rxl_ndcid')
				->distinct()
				->get();
			if ($query) {
				$data['response'] = 'true';
				foreach ($query as $row) {
					$label = $row->rxl_medication . ", Dosage: " . $row->rxl_dosage . " " . $row->rxl_dosage_unit;
					$data['message'][] = array(
						'label' => $label,
						'value' => $row->rxl_medication,
						'name' => '',
						'form' => '',
						'dosage' => $row->rxl_dosage,
						'dosage_unit' => $row->rxl_dosage_unit,
						'ndcid' => $row->rxl_ndcid,
						'category' => 'Previously Prescribed'
					);
				}
			}
		}
		$query1 = DB::table('meds_full')
			->select('PROPRIETARYNAME', 'DOSAGEFORMNAME', 'PRODUCTNDC')
			->where('PROPRIETARYNAME', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query1) {
			$data['response'] = 'true';
			foreach ($query1 as $row1) {
				$medication = trim($row1->PROPRIETARYNAME) . ", " . strtolower($row1->DOSAGEFORMNAME);
				$data['message'][] = array(
					'label' => $medication,
					'value' => $medication,
					'name' => $row1->PROPRIETARYNAME,
					'form' => $row1->DOSAGEFORMNAME,
					'dosage' => '',
					'dosage_unit' => '',
					'ndcid' => '',
					'category' => 'Medication Database'
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postRxDosage()
	{
		$q = Input::get('term');
		if (!$q) return;
		$data['response'] = 'false';
		$q_parts = explode(";", $q);
		$query = DB::table('meds_full')
			->where('PROPRIETARYNAME', '=', $q_parts[0])
			->where('DOSAGEFORMNAME', '=', $q_parts[1])
			->select('ACTIVE_NUMERATOR_STRENGTH', 'ACTIVE_INGRED_UNIT', 'PRODUCTNDC')
			->orderBy('ACTIVE_NUMERATOR_STRENGTH', 'asc')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$dosage = trim($row->ACTIVE_NUMERATOR_STRENGTH);
				$unit = trim($row->ACTIVE_INGRED_UNIT);
				$label = $dosage . ' ' . $unit;
				if ($this->recursive_array_search($label, $data) === FALSE) {
					$data['message'][] = array(
						'label' => $label,
						'value' => $dosage,
						'unit' => $unit,
						'ndc' => $row->PRODUCTNDC
					);
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postRxNdcConvert($ndc)
	{
		$result = DB::table('meds_full_package')->where('PRODUCTNDC', '=', $ndc)->take(1)->first();
		$ndcid = $this->ndc_convert($result->NDCPACKAGECODE);
		echo $ndcid;
	}
	
	public function postRxSearch($item)
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('rx_list')
			->where($item, 'LIKE', "%$q%")
			->select($item)
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->$item,
					'value' => $row->$item
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupplements($order)
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('supplement_inventory')
			->where('sup_description', 'LIKE', "%$q%")
			->where('quantity',  '>', '0')
			->where('practice_id', '=', Session::get('practice_id'))
			->select('sup_description', 'quantity', 'cpt', 'charge', 'sup_strength', 'supplement_id')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				if ($order == "Y") {
					if(strpos($row->sup_strength, " ") === FALSE) {
						$dosage_array[0] = $row->sup_strength;
						$dosage_array[1] = '';
					} else {
						$dosage_array = explode(' ', $row->sup_strength);
					}
					$label = $row->sup_description . ", Quantity left: " . $row->quantity;
					$data['message'][] = array(
						'label' => $label,
						'value' => $row->sup_description,
						'category' => 'Supplements Inventory',
						'quantity' => $row->quantity,
						'dosage' => $dosage_array[0],
						'dosage_unit' => $dosage_array[1],
						'supplement_id' => $row->supplement_id
					);
				} else {
					if(strpos($row->sup_strength, " ") === FALSE) {
						$dosage_array[0] = $row->sup_strength;
						$dosage_array[1] = '';
					} else {
						$dosage_array = explode(' ', $row->sup_strength);
					}
					$data['message'][] = array(
						'label' => $row->sup_description,
						'value' => $row->sup_description,
						'category' => 'Supplements Inventory',
						'dosage' => $dosage_array[0],
						'dosage_unit' => $dosage_array[1],
						'supplement_id' => $row->supplement_id
					);
				}
			}
		}
		$query0 = DB::table('sup_list')
			->where('sup_supplement', 'LIKE', "%$q%")
			->select('sup_supplement', 'sup_dosage', 'sup_dosage_unit')
			->distinct()
			->get();
		if ($query0) {
			if (!isset($data['message'])) {
				$data['message'] = array();
				$data['response'] = 'true';
			}
			foreach ($query0 as $row0) {
				if ($order == "Y") {
					$label0 = $row0->sup_supplement . ", Dosage: " . $row0->sup_dosage . " " . $row0->sup_dosage_unit;
					$data['message'][] = array(
						'label' => $label0,
						'value' => $row0->sup_supplement,
						'category' => 'Previously Prescribed',
						'quantity' => '',
						'dosage' => $row0->sup_dosage,
						'dosage_unit' => $row0->sup_dosage_unit,
						'supplement_id' => ''
					);
				} else {
					$data['message'][] = array(
						'label' => $row0->sup_supplement,
						'value' => $row0->sup_supplement,
						'category' => ''
					);
				}
			}
		}
		$query1 = DB::table('supplements_list')
			->where('supplement_name', 'LIKE', "%$q%")
			->select('supplement_name')
			->distinct()
			->get();
		if ($query1) {
			if (!isset($data['message'])) {
				$data['message'] = array();
				$data['response'] = 'true';
			}
			foreach ($query1 as $row1) {
				if ($order == "Y") {
					$data['message'][] = array(
						'label' => $row1->supplement_name,
						'value' => $row1->supplement_name,
						'category' => 'Supplement Database',
						'quantity' => '',
						'dosage' => '',
						'dosage_unit' => '',
						'supplement_id' => ''
					);
				} else {
					$data['message'][] = array(
						'label' => $row1->supplement_name,
						'value' => $row1->supplement_name,
						'category' => ''
					);
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postSupDosage()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('sup_list')
			->where('sup_supplement', 'LIKE', "%$q%")
			->select('sup_dosage', 'sup_dosage_unit')
			->orderBy('sup_dosage', 'asc')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$dosage = trim($row->sup_dosage);
				$unit = trim($row->sup_dosage_unit);
				$label = $dosage . ' ' . $unit;
				$data['message'][] = array(
					'label' => $label,
					'value' => $dosage,
					'unit' => $unit
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupSig()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('sup_list')
			->where('sup_sig', 'LIKE', "%$q%")
			->select('sup_sig')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->sup_sig,
					'value' => $row->sup_sig
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupFrequency()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('sup_list')
			->where('sup_frequency', 'LIKE', "%$q%")
			->select('sup_frequency')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->sup_frequency,
					'value' => $row->sup_frequency
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupInstructions()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('sup_list')
			->where('sup_instructions', 'LIKE', "%$q%")
			->select('sup_instructions')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->sup_instructions,
					'value' => $row->sup_instructions
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupReason()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('sup_list')
			->where('sup_reason', 'LIKE', "%$q%")
			->select('sup_reason')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->sup_reason,
					'value' => $row->sup_reason
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSupCpt()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('supplement_inventory')
			->where('sup_description', 'LIKE', "%$q%")
			->select('sup_description','cpt','charge','quantity','sup_manufacturer')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->sup_description,
					'value' => $row->sup_description,
					'cpt' => $row->cpt,
					'charge' => $row->charge,
					'quantity' => $row->quantity,
					'manufacturer' => $row->sup_manufacturer
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSubject()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('t_messages')
			->where('t_messages_subject', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->select('t_messages_subject')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->t_messages_subject,
					'value' => $row->t_messages_subject
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postUsers()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('users')
			->where('group_id', '!=', '100')
			->where('practice_id', '=', Session::get('practice_id'))
			->where(function($query_array1) use ($q) {
				$pos2 = explode(',', $q);
				$query_array1->where('displayname', 'LIKE', "%$q%")
				->orWhere('id', 'LIKE', "%$q%");
			})
			->select('id', 'displayname')
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->displayname . ' (' . $row->id . ')';
				$data['message'][] = array(
					'label' => $records,
					'value' => $records
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postUsers1()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('users')
			->where('displayname', 'LIKE', "%$q%")
			->orWhere('id', 'LIKE', "%$q%")
			->where('group_id', '!=', '100')
			->where('practice_id', '=', Session::get('practice_id'))
			->select('id', 'displayname')
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->displayname,
					'value' => $row->displayname,
					'id' => $row->id
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postReaction()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('allergies')
			->where('allergies_reaction', 'LIKE', "%$q%")
			->select('allergies_reaction')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->allergies_reaction,
					'value' => $row->allergies_reaction
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAlert()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('alerts')
			->where('alert', 'LIKE', "%$q%")
			->select('alert')
			->where('practice_id', '=', Session::get('practice_id'))
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->alert,
					'value' => $row->alert
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAlertDescription()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('alerts')
			->where('alert_description', 'LIKE', "%$q%")
			->select('alert_description')
			->where('practice_id', '=', Session::get('practice_id'))
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->alert_description,
					'value' => $row->alert_description
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postAlertReasonNotComplete()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('alerts')
			->where('alert_reason_not_complete', 'LIKE', "%$q%")
			->select('alert_reason_not_complete')
			->where('practice_id', '=', Session::get('practice_id'))
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->alert_reason_not_complete,
					'value' => $row->alert_reason_not_complete
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postDocumentsCount()
	{
		$pid = Session::get('pid');
		$array = array();
		$data = array();
		$array[] = array(
			'documents_type' => 'Laboratory',
			'label' => 'labs_count'
		);
		$array[] = array(
			'documents_type' => 'Imaging',
			'label' => 'radiology_count'
		);
		$array[] = array(
			'documents_type' => 'Cardiopulmonary',
			'label' => 'cardiopulm_count'
		);
		$array[] = array(
			'documents_type' => 'Endoscopy',
			'label' => 'endoscopy_count'
		);
		$array[] = array(
			'documents_type' => 'Referrals',
			'label' => 'referrals_count'
		);
		$array[] = array(
			'documents_type' => 'Past Records',
			'label' => 'past_records_count'
		);
		$array[] = array(
			'documents_type' => 'Other Forms',
			'label' => 'outside_forms_count'
		);
		$array[] = array(
			'documents_type' => 'Letters',
			'label' => 'letters_count'
		);
		foreach ($array as $array_row) {
			$query = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_type', $array_row['documents_type'])
				->get();
			$query_count = count($query);
			if ($query_count === 1) {
				$data[$array_row['label']] = $query_count . " document.";
			} else {
				$data[$array_row['label']] = $query_count . " documents.";
			}
		}
		echo json_encode($data);
	}
	
	public function postImm()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('cvx')
			->where('description', 'LIKE', "%$q%")
			->orWhere('vaccine_name', 'LIKE', "%$q%")
			->select('vaccine_name', 'cvx_code')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->vaccine_name,
					'value' => $row->vaccine_name,
					'cvx' => $row->cvx_code
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postImm1()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('vaccine_inventory')
			->where('imm_immunization', 'LIKE', "%$q%")
			->where('quantity', '>', 0)
			->where('practice_id', '=', Session::get('practice_id'))
			->select('imm_immunization', 'imm_cvxcode', 'cpt', 'imm_expiration', 'imm_manufacturer', 'imm_lot', 'vaccine_id')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->imm_immunization,
					'value' => $row->imm_immunization,
					'cvx' => $row->imm_cvxcode,
					'cpt' => $row->cpt,
					'expiration' => $row->imm_expiration,
					'manufacturer' => $row->imm_manufacturer,
					'lot' => $row->imm_lot,
					'vaccine_id' => $row->vaccine_id
				);
			}
		} else {
			$data['message'] = array();
			$data['response'] = 'true';
			$data['message'][] = array(
				'label' => 'No vaccines in the inventory!',
				'value' => ''
			);
		}
		echo json_encode($data);
	}
	
	public function postHippaReason()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('hippa')
			->where('hippa_reason', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->select('hippa_reason')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->hippa_reason,
					'value' => $row->hippa_reason
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postRequestReason()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('hippa_request')
			->where('request_reason', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->select('request_reason')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->request_reason,
					'value' => $row->request_reason
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postPaymentType()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'true';
		$query = DB::table('billing_core')
			->where('payment_type', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->select('payment_type')
			->distinct()
			->get();
		$data['message'] = array();
		if ($query) {
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->payment_type,
					'value' => $row->payment_type
				);
			}
		}
		$data['message'][] = array(
			'label' => '*********',
			'value' => ''
		);
		$data['message'][] = array(
			'label' => 'Billing Service Adjustment',
			'value' => 'Billing Service Adjustment'
		);
		$data['message'][] = array(
			'label' => 'Cash',
			'value' => 'Cash'
		);
		$data['message'][] = array(
			'label' => 'Check',
			'value' => 'Check, #'
		);
		$data['message'][] = array(
			'label' => 'Credit/Debit Card',
			'value' => 'Credit/Debit Card'
		);
		$data['message'][] = array(
			'label' => 'Insurance Copay',
			'value' => 'Insurance Copay'
		);
		$data['message'][] = array(
			'label' => 'Insurance Deductible',
			'value' => 'Insurance Deductible'
		);
		$data['message'][] = array(
			'label' => 'Insurance Payment',
			'value' => 'Insurance Payment'
		);
		$data['message'][] = array(
			'label' => 'Write-Off',
			'value' => 'Write-Off'
		);
		echo json_encode($data);
	}
	
	public function postBillingReason()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('billing_core')
			->where('reason', 'LIKE', "%$q%")
			->where('practice_id', Session::get('practice_id'))
			->select('reason')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->reason,
					'value' => $row->reason
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postSearchIssues()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$pid = Session::get('pid');
		$data['response'] = 'false';
		$query = DB::table('issues')
			->where('issue', 'LIKE', "%$q%")
			->where('pid', '=', $pid)
			->where('issue_date_inactive', '=', '0000-00-00 00:00:00')
			->select('issue')
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->issue,
					'value' => $row->issue
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postPayorId($address_id)
	{
		$row = Addressbook::find($address_id);
		if ($row->insurance_plan_payor_id == "") {
			$arr = "Unknown";
		} else {
			$arr = $row->insurance_plan_payor_id;
		}
		echo $arr;
	}
	
	public function postOrdersProvider($type)
	{
		$query = DB::table('addressbook')->where('specialty', '=', $type)->get();
		$data['response'] = 'false';
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][$row->address_id] = $row->displayname;
			}
		}
		echo json_encode($data);
	}
	
	public function postOrdersProvider1()
	{
		$row = DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->first();
		echo json_encode($row);
	}
	
	public function postRefProvider($specialty)
	{
		$query = DB::table('addressbook');
		if ($specialty != "all") {
			$query->where('specialty', '=', $specialty);
		} else {
			$query->where('specialty', '!=', 'Pharmacy')
				->where('specialty', '!=', 'Laboratory')
				->where('specialty', '!=', 'Radiology')
				->where('specialty', '!=', 'Cardiopulmonary')
				->where('specialty', '!=', 'Insurance');
		}
		$result = $query->get();
		$data['response'] = 'false';
		if ($result) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($result as $row) {
				$data['message'][$row->address_id] = $row->displayname . " - " . $row->specialty;
			}
		}
		echo json_encode($data);
	}
	
	public function postRefProvider1($specialty)
	{
		$arr['html'] = '<label for="hippa_address_id">Records Release To (Provider):</label>';
		$arr['html1'] = '<label for="hippa_request_address_id">Records Request To (Provider):</label>';
		$data = array(''=>'Select Provider');
		$data['Patient Favorites'] = array();
		$data['Address Book'] = array();
		$address = array();
		$query1 = DB::table('hippa')
			->where('pid', '=', Session::get('pid'))
			->where(function($query_array1) {
				$query_array1->whereNotNull('address_id')
				->orWhere('address_id', '!=', '');
			})
			->select('address_id')
			->distinct()
			->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$address[] = $row1->address_id;
			}
		}
		$query2 = DB::table('orders')->where('pid', '=', Session::get('pid'))->where('orders_referrals', '!=', '')->select('address_id')->distinct()->get();
		if ($query2) {
			foreach ($query2 as $row2) {
				$address[] = $row2->address_id;
			}
		}
		$query3 = DB::table('hippa_request')->where('pid', '=', Session::get('pid'))->select('address_id')->distinct()->get();
		if ($query3) {
			foreach ($query3 as $row3) {
				$address[] = $row3->address_id;
			}
		}
		if (!empty($address)) {
			$address1 = array_unique($address);
			foreach ($address1 as $row3) {
				$add1 = DB::table('addressbook')->where('address_id', '=', $row3)->first();
				$data['Patient Favorites'][$add1->address_id] = $add1->displayname . " - " . $add1->specialty;
			}
		}
		$query = DB::table('addressbook');
		if ($specialty != "all") {
			$query->where('specialty', '=', $specialty);
		} else {
			$query->where('specialty', '!=', 'Pharmacy')
				->where('specialty', '!=', 'Laboratory')
				->where('specialty', '!=', 'Radiology')
				->where('specialty', '!=', 'Cardiopulmonary')
				->where('specialty', '!=', 'Insurance');
		}
		$result = $query->get();
		if ($result) {
			foreach ($result as $row) {
				$data['Address Book'][$row->address_id] = $row->displayname . " - " . $row->specialty;
			}
		}
		$arr['html'] .= Form::select('address_id', $data, null, array('id'=>'hippa_address_id','style'=>'width:300px','class'=>'text'));
		$arr['html1'] .= Form::select('address_id', $data, null, array('id'=>'hippa_request_address_id','style'=>'width:300px','class'=>'text'));
		$arr['html'] .= '<button type="button" id="hippa_address_id2" class="nosh_button_add">Add/Edit</button>';
		$arr['html1'] .= '<button type="button" id="hippa_request_address_id2" class="nosh_button_add">Add/Edit</button>';
		return $arr;
	}
	
	public function postRefProviderSpecialty()
	{
		$query = DB::table('addressbook')
			->where('specialty', '!=', 'Pharmacy')
			->where('specialty', '!=', 'Laboratory')
			->where('specialty', '!=', 'Radiology')
			->where('specialty', '!=', 'Cardiopulmonary')
			->where('specialty', '!=', 'Insurance')
			->select('specialty')
			->distinct()
			->get();
		$data['response'] = 'false';
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][$row->specialty] = $row->specialty;
			}
		}
		echo json_encode($data);
	}
	
	public function postLab()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$query = DB::table('orderslist')
			->where('orders_category', '=', 'Laboratory')
			->where('user_id', '=', '0')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query) {
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->orders_description;
				if ($row->cpt != '') {
					$records .= '; CPT: ' . $row->cpt;
				}
				if ($row->snomed != '') {
					$records .= '; SNOMED: ' . $row->snomed;
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global',
					'aoe_code' => '',
					'aoe_field' => ''
				);
			}
		}
		$query1 = DB::table('orderslist')
			->where('orders_category', '=', 'Laboratory')
			->where('user_id', '=', Session::get('user_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query1) {
			$data['response'] = 'true';
			foreach ($query1 as $row1) {
				$records1 = $row1->orders_description;
				if ($row1->cpt != '') {
					$records1 .= '; CPT: ' . $row1->cpt;
				}
				if ($row1->snomed != '') {
					$records1 .= '; SNOMED: ' . $row1->snomed;
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal',
					'aoe_code' => '',
					'aoe_field' => ''
				);
			}
		}
		$query2 = DB::table('orderslist1')
			->where('orders_category', '=', 'Laboratory')
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query2) {
			$data['response'] = 'true';
			foreach ($query2 as $row2) {
				$records2 = $row2->orders_description . "; Code: " . $row2->orders_code;
				if ($row2->cpt != '') {
					$records2 .= '; CPT: ' . $row2->cpt;
				}
				if ($this->recursive_array_search($records2, $data) === FALSE) {
					$data['message'][] = array(
						'label' => $records2,
						'value' => $records2,
						'category' => 'Electronic Order Entry: ' . $row2->orders_vendor,
						'aoe_code' => $row2->aoe_code,
						'aoe_field' => $row2->aoe_field
					);
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postRad()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$query = DB::table('orderslist')
			->where('orders_category', '=', 'Radiology')
			->where('user_id', '=', '0')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query) {
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->orders_description;
				if ($row->cpt != '') {
					$records .= '; CPT: ' . $row->cpt;
				}
				if ($row->snomed != '') {
					$records .= '; SNOMED: ' . $row->snomed;
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$query1 = DB::table('orderslist')
			->where('orders_category', '=', 'Radiology')
			->where('user_id', '=', Session::get('user_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query1) {
			$data['response'] = 'true';
			foreach ($query1 as $row1) {
				$records1 = $row1->orders_description;
				if ($row1->cpt != '') {
					$records1 .= '; CPT: ' . $row1->cpt;
				}
				if ($row->snomed != '') {
					$records1 .= '; SNOMED: ' . $row1->snomed;
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postCp()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$data['message'] = array();
		$query = DB::table('orderslist')
			->where('orders_category', '=', 'Cardiopulmonary')
			->where('user_id', '=', '0')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query) {
			$data['response'] = 'true';
			foreach ($query as $row) {
				$records = $row->orders_description;
				if ($row->cpt != '') {
					$records .= '; CPT: ' . $row->cpt;
				}
				if ($row->snomed != '') {
					$records .= '; SNOMED: ' . $row->snomed;
				}
				$data['message'][] = array(
					'label' => $records,
					'value' => $records,
					'category' => 'Global'
				);
			}
		}
		$query1 = DB::table('orderslist')
			->where('orders_category', '=', 'Cardiopulmonary')
			->where('user_id', '=', Session::get('user_id'))
			->where('orders_description', 'LIKE', "%$q%")
			->distinct()
			->get();
		if ($query1) {
			$data['response'] = 'true';
			foreach ($query1 as $row1) {
				$records1 = $row1->orders_description;
				if ($row1->cpt != '') {
					$records1 .= '; CPT: ' . $row1->cpt;
				}
				if ($row->snomed != '') {
					$records1 .= '; SNOMED: ' . $row1->snomed;
				}
				$data['message'][] = array(
					'label' => $records1,
					'value' => $records1,
					'category' => 'Personal'
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postVisitTypes($id)
	{
		$query = DB::table('calendar')
			->where('active', '=', 'y')
			->where('practice_id', '=', Session::get('practice_id'))
			->where(function($query_array1) use ($id) {
				$query_array1->where('provider_id', '=', '0')
				->orWhere('provider_id', '=', $id);
			})
			->get();
		$data['response'] = 'false';
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][$row->visit_type] = $row->visit_type;
			}
		}
		echo json_encode($data);
	}
	
	public function postNpiLookup()
	{
		$q = Input::get('term');
		$q_items = explode(",", $q);
		$data['response'] = "false";
		$lastname = $q_items[0];
		$firstname = $q_items[1];
		$state = $q_items[2];
		$url = 'http://docnpi.com/api/index.php?first_name=' . $firstname . '&last_name=' . $lastname . '&org_name=&address=&state=' . $state . '&city_name=&zip=&taxonomy=&ident=&is_person=true&is_address=false&is_org=false&is_ident=true&format=aha_table';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$data1 = curl_exec($ch);
		curl_close($ch);
		$html = new Htmldom($data1);
		if (isset($html)) {
			$table = $html->find('table[id=npi_results_table]',0);
			if (isset($table)) {
				$data['response'] = "true";
				$data['message'] = array();
				foreach ($table->find('tbody',0)->find('tr') as $tr) {
					if ($tr->class == "tr_ind") {
						$npi = $tr->id;
						$name_item = $tr->find('span[class=name_ind]',0);
						$name = $name_item->innertext;
						$address_item = $tr->find('span[class=address]',0);
						$address = $address_item->innertext;
						$specialty_item = $tr->find('span[class=tax]',0);
						$specialty = $specialty_item->innertext;
						$specialty = str_replace("( ","",$specialty);
						$specialty = str_replace(") ","",$specialty);
						$text = $name . "; Specialty: " . $specialty . "; Address: " . $address;
						$data['message'][] = array(
							'label' => $text,
							'value' => $npi
						);
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postProcedureType()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('procedurelist')
			->where('procedure_type', 'LIKE', "%$q%")
			->where('practice_id', '=', Session::get('practice_id'))
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->procedure_type,
					'value' => $row->procedure_type,
					'procedurelist_id' => $row->procedurelist_id,
					'procedure_description' => $row->procedure_description,
					'procedure_complications' => $row->procedure_complications,
					'procedure_ebl' => $row->procedure_ebl,
					'cpt' => $row->cpt
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postVivacareData1()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['response'] = "false";
		if ($practice->vivacare != "") {
			$html = new Htmldom("http://fromyourdoctor.com/" . $practice->vivacare . "/health/library.do");
			if (isset($html)) {
				$div = $html->find('[id=usercontent]',0);
				$div1 = $html->find('[id=formselectA]',0);
				if (isset($div)) {
					$data['response'] = "true";
					foreach ($div->find('ul[!id]') as $ul) {
						foreach ($ul->find('li') as $li) {
							$a = $li->find('a',0);
							$text = $a->innertext;
							$text = str_replace("\n","",$text);
							$link = $a->href;
							$link = str_replace("\n","",$link);
							$link_array = explode("=", $link);
							$search = 'option[value=' . $link_array[2] . ']';
							$item = $div1->find($search, 0);
							if (isset($item)) {
								$cat = $item->parent()->first_child();
								$category = $cat->innertext;
								$category = str_replace("\n","",$category);
							} else {
								$category = "Other";
							}
							$data['message'][] = array(
								'label' => $text,
								'value' => $text,
								'link' => $link_array[2],
								'category' => $category
							);
						}
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postVivacareData()
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['response'] = "false";
		if ($practice->vivacare != "") {
			$html = new Htmldom("http://informationrx.com/" . $practice->vivacare);
			if (isset($html)) {
				$div = $html->find('[id=nav-topic-dropdown]',0);
				$div1 = $html->find('[id=formselectA]',0);
				if (isset($div)) {
					$data['response'] = "true";
					foreach ($div->find('select') as $select) {
						$category = $select->id;
						foreach ($select->find('option') as $option) {
							$text = $option->innertext;
							$link = $option->value;
							$data['message'][] = array(
								'label' => $text,
								'value' => $text,
								'link' => $link,
								'category' => $category
							);
						}
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postPid()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('demographics')
			->where('lastname', 'LIKE', "%$q%")
			->orWhere('firstname', 'LIKE', "%$q%")
			->orWhere('pid', 'LIKE', "%$q%")
			->select('lastname', 'firstname', 'DOB', 'pid')
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$name =  $row->lastname . ', ' . $row->firstname . ' (DOB: ' . date("m/d/Y", strtotime($row->DOB)) . ') (ID: ' . $row->pid . ')';
				$data['message'][] = array(
					'label' => $name,
					'value' => $row->pid
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postTimezone()
	{
		$arr = array();
		$zones = DateTimeZone::listIdentifiers();
		foreach ($zones as $zone) {
			$arr[$zone] = $zone;
		}
		echo json_encode($arr);
	}
	
	public function postImageSelect()
	{
		$gender = Session::get('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} else {
			$sex = 'f';
		}
		$dir = "images/illustrations/" . $sex;
		$full_dir = __DIR__."/../../public/" . $dir;
		$files = scandir($full_dir);
		$count = count($files);
		$full_count=0;
		$arr = array();
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$file = $dir . "/" . $line;
			$line1 = str_replace("_", " ", $line);
			$name = str_replace(".jpg", "", $line1);
			$arr[$file] = $name;
		}
		echo json_encode($arr);
	}
	
	public function postImageSelectDimensions()
	{
		$result = getimagesize(Input::get('file'));
		$data = array(
			'width' => $result[0],
			'height' => $result[1]
		);
		echo json_encode($data);
	}
	
	public function postTextdumpGroup($target)
	{
		$arr = '';
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $target)->where('practice_id', '=', Session::get('practice_id'))->where('array', '=', '')->get();
		foreach ($query as $row) {
			$norm = 'No normal values set.';
			$query1 = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $target)->where('group', '=', $row->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->where('default', '=', 'normal')->get();
			if ($query1) {
				$norm = $row->group . ': ';
				$i = 0;
				foreach ($query1 as $row1) {
					if ($i > 0) {
						$norm .= "\n";
					}
					$norm .= $row1->array;
					$i++;
				}
			}
			$arr .= '<div id="textgroupdiv_' . $row->template_id . '" style="width:99%" class="pure-g"><div class="pure-u-3-4"><input type="checkbox" id="normaltextgroup_' . $row->template_id . '" class="normaltextgroup" value="' . $norm . '"/><label for="normaltextgroup_' . $row->template_id . '">Normal</label> <b id="edittextgroup_' . $row->template_id . '_b" class="textdump_group_item textdump_group_item_text" data-type="text" data-pk="' . $row->template_id . '" data-name="group" data-url="ajaxsearch/edit-text-template-group" data-title="Group">' . $row->group . '</b></div><div class="pure-u-1-4" style="overflow:hidden"><div style="width:200px;"><button type="button" id="edittextgroup_' . $row->template_id . '" class="edittextgroup">Edit</button><button type="button" id="deletetextgroup_' . $row->template_id . '" class="deletetextgroup">Remove</button></div></div><hr class="ui-state-default"/></div>';
		}
		echo $arr;
	}
	
	public function postTextdump($target)
	{
		$arr = '';
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $target)->where('group', '=', Input::get('group'))->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->get();
		foreach ($query as $row) {
			if ($row->default == 'normal') {
				$arr .= '<div id="texttemplatediv_' . $row->template_id . '" style="width:99%" class="pure-g"><div class="textdump_item pure-u-2-3"><span id="edittexttemplate_' . $row->template_id . '_span" class="textdump_item_text" data-type="text" data-pk="' . $row->template_id . '" data-name="array" data-url="ajaxsearch/edit-text-template" data-title="Item">' . $row->array . '</span></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:400px;"><input type="checkbox" id="normaltexttemplate_' . $row->template_id . '" class="normaltexttemplate" value="normal" checked><label for="normaltexttemplate_' . $row->template_id . '">Mark as Default Normal</label><button type="button" id="edittexttemplate_' . $row->template_id . '" class="edittexttemplate">Edit</button><button type="button" id="deletetexttemplate_' . $row->template_id . '" class="deletetexttemplate">Remove</button></div></div><hr class="ui-state-default"/></div>';
			} else {
				$arr .= '<div id="texttemplatediv_' . $row->template_id . '" style="width:99%" class="pure-g"><div class="textdump_item pure-u-2-3"><span id="edittexttemplate_' . $row->template_id . '_span" class="textdump_item_text" data-type="text" data-pk="' . $row->template_id . '" data-name="array" data-url="ajaxsearch/edit-text-template" data-title="Item">' . $row->array . '</span></div><div class="pure-u-1-3" style="overflow:hidden"><div style="width:400px;"><input type="checkbox" id="normaltexttemplate_' . $row->template_id . '" class="normaltexttemplate" value="normal"><label for="normaltexttemplate_' . $row->template_id . '">Mark as Default Normal</label><button type="button" id="edittexttemplate_' . $row->template_id . '" class="edittexttemplate">Edit</button><button type="button" id="deletetexttemplate_' . $row->template_id . '" class="deletetexttemplate">Remove</button></div></div><hr class="ui-state-default"/></div>';
			}
		}
		echo $arr;
	}
	
	public function postAddTextTemplate()
	{
		$data = array(
			'array' => Input::get('textdump_add'),
			'category' => 'text',
			'template_name' => Input::get('target'),
			'practice_id' => Session::get('practice_id'),
			'group' => Input::get('group')
		);
		$arr['id'] = DB::table('templates')->insertGetId($data);
		$this->audit('Add');
		$arr['message'] = "Text added";
		echo json_encode($arr);
	}
	
	public function postAddTextTemplateGroup()
	{
		$data = array(
			'array' => '',
			'category' => 'text',
			'template_name' => Input::get('target'),
			'practice_id' => Session::get('practice_id'),
			'group' => Input::get('textdump_group_add')
		);
		$arr['id'] = DB::table('templates')->insertGetId($data);
		$this->audit('Add');
		$arr['message'] = "Group added";
		echo json_encode($arr);
	}
	
	public function postEditTextTemplateGroup()
	{
		$group = DB::table('templates')->where('template_id', '=', Input::get('pk'))->first();
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $group->template_name)->where('group', '=', $group->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->get();
		foreach ($query as $row) {
			$data1 = array(
				'group' => Input::get('value')
			);
			DB::table('templates')->where('template_id', '=', $row->template_id)->update($data1);
			$this->audit('Update');
		}
		$data = array(
			'group' => Input::get('value')
		);
		DB::table('templates')->where('template_id', '=', Input::get('pk'))->update($data);
		$this->audit('Update');
		echo "Group updated";
	}
	
	public function postEditTextTemplate()
	{
		$data = array(
			'array' => Input::get('value')
		);
		DB::table('templates')->where('template_id', '=', Input::get('pk'))->update($data);
		$this->audit('Update');
		echo "Text updated";
	}
	
	public function postDeletetextdump($id)
	{
		$item = DB::table('templates')->where('template_id', '=', $id)->first();
		DB::table('templates')->where('template_id', '=', $id)->delete();
		$this->audit('Delete');
		echo 'Text deleted';
	}
	
	public function postDeletetextdumpgroup($id)
	{
		$group = DB::table('templates')->where('template_id', '=', $id)->first();
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $group->template_name)->where('group', '=', $group->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->get();
		foreach ($query as $row) {
			DB::table('templates')->where('template_id', '=', $row->template_id)->delete();
			$this->audit('Delete');
		}
		DB::table('templates')->where('template_id', '=', $id)->delete();
		$this->audit('Delete');
		echo 'Group deleted';
	}
	
	public function postDefaulttextdump($id)
	{
		$data = array(
			'default' => 'normal'
		);
		DB::table('templates')->where('template_id', '=', $id)->update($data);
		$this->audit('Update');
		$query2 = DB::table('templates')->where('template_id', '=', $id)->first();
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $query2->template_name)->where('practice_id', '=', Session::get('practice_id'))->where('array', '=', '')->get();
		$arr = '';
		foreach ($query as $row) {
			$norm = 'No normal values set.';
			$query1 = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $query2->template_name)->where('group', '=', $row->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->where('default', '=', 'normal')->get();
			if ($query1) {
				$norm = $row->group . ': ';
				$i = 0;
				foreach ($query1 as $row1) {
					if ($i > 0) {
						$norm .= "\n";
					}
					$norm .= $row1->array;
					$i++;
				}
			}
			$arr .= '<div id="textgroupdiv_' . $row->template_id . '" style="width:99%" class="pure-g"><div class="pure-u-3-4"><input type="checkbox" id="normaltextgroup_' . $row->template_id . '" class="normaltextgroup" value="' . $norm . '"/><label for="normaltextgroup_' . $row->template_id . '">Normal</label> <b id="edittextgroup_' . $row->template_id . '_b" class="textdump_group_item textdump_group_item_text" data-type="text" data-pk="' . $row->template_id . '" data-name="group" data-url="ajaxsearch/edit-text-template-group" data-title="Group">' . $row->group . '</b></div><div class="pure-u-1-4" style="overflow:hidden"><div style="width:200px;"><button type="button" id="edittextgroup_' . $row->template_id . '" class="edittextgroup">Edit</button><button type="button" id="deletetextgroup_' . $row->template_id . '" class="deletetextgroup">Remove</button></div></div><hr class="ui-state-default"/></div>';
		}
		echo $arr;
	}
	
	public function postUndefaulttextdump($id)
	{
		$data = array(
			'default' => ''
		);
		DB::table('templates')->where('template_id', '=', $id)->update($data);
		$this->audit('Update');
		$query2 = DB::table('templates')->where('template_id', '=', $id)->first();
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $query2->template_name)->where('practice_id', '=', Session::get('practice_id'))->where('array', '=', '')->get();
		$arr = '';
		foreach ($query as $row) {
			$norm = 'No normal values set.';
			$query1 = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $query2->template_name)->where('group', '=', $row->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->where('default', '=', 'normal')->get();
			if ($query1) {
				$norm = $row->group . ': ';
				$i = 0;
				foreach ($query1 as $row1) {
					if ($i > 0) {
						$norm .= "\n";
					}
					$norm .= $row1->array;
					$i++;
				}
			}
			$arr .= '<div id="textgroupdiv_' . $row->template_id . '" style="width:99%" class="pure-g"><div class="pure-u-3-4"><input type="checkbox" id="normaltextgroup_' . $row->template_id . '" class="normaltextgroup" value="' . $norm . '"/><label for="normaltextgroup_' . $row->template_id . '">Normal</label> <b id="edittextgroup_' . $row->template_id . '_b" class="textdump_group_item textdump_group_item_text" data-type="text" data-pk="' . $row->template_id . '" data-name="group" data-url="ajaxsearch/edit-text-template-group" data-title="Group">' . $row->group . '</b></div><div class="pure-u-1-4" style="overflow:hidden"><div style="width:200px;"><button type="button" id="edittextgroup_' . $row->template_id . '" class="edittextgroup">Edit</button><button type="button" id="deletetextgroup_' . $row->template_id . '" class="deletetextgroup">Remove</button></div></div><hr class="ui-state-default"/></div>';
		}
		echo $arr;
	}
	
	public function postPreviousEncounters()
	{
		$encounter = Encounters::find(Session::get('eid'));
		$query = DB::table('encounters')->where('pid', '=', Session::get('pid'))
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->orderBy('encounter_DOS', 'asc')
			->where('eid', '!=', Session::get('eid'));
		if ($encounter->encounter_template == 'standardpsych' || $encounter->encounter_template == 'standardpsych1') {
			$query->where(function($query_array1) {
				$query_array1->where('encounter_template', '=', 'standardpsych')
				->orWhere('encounter_template', '=', 'standardpsych1');
			});
		} else {
			$query->where('encounter_template', '=', $encounter->encounter_template);
		}
		$result = $query->get();
		$data = array();
		if ($result) {
			foreach ($result as $row) {
				$key = $row->eid;
				$value = date('Y-m-d', $this->human_to_unix($row->encounter_DOS)) . ' (Chief complaint: ' . $row->encounter_cc . ')';
				$data[$key] = $value;
			}
		}
		echo json_encode($data);
	}
	
	public function postTestName()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('tests')
			->where('test_name', 'LIKE', "%$q%")
			->select('test_name')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->test_name,
					'value' => $row->test_name
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postTestUnits()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('tests')
			->where('test_units', 'LIKE', "%$q%")
			->select('test_units')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->test_units,
					'value' => $row->test_units
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postTestFrom()
	{
		$q = strtolower(Input::get('term'));
		if (!$q) return;
		$data['response'] = 'false';
		$query = DB::table('tests')
			->where('test_from', 'LIKE', "%$q%")
			->select('test_from')
			->distinct()
			->get();
		if ($query) {
			$data['message'] = array();
			$data['response'] = 'true';
			foreach ($query as $row) {
				$data['message'][] = array(
					'label' => $row->test_from,
					'value' => $row->test_from
				);
			}
		}
		echo json_encode($data);
	}
	
	public function postCheckTitle($type)
	{
		$data['response'] = true;
		if (Input::get('id') != '') {
			$check = DB::table('templates')->where('template_id', '=', Input::get('id'))->first();
			if ($check->template_name == Input::get('title')) {
				$data['response'] = false;
				echo json_encode($data);
				exit(0);
			}
		}
		$query = DB::table('templates')
			->where('category', '=', $type)
			->where('template_name', '=', Input::get('title'))
			->first();
		if ($query) {
			$data['message'] = "Title name already exists!";
		} else {
			$data['response'] = false;
		}
		echo json_encode($data);
	}
	
	public function postEncounterList($pid)
	{
		$result = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->orderBy('encounter_DOS', 'asc')
			->get();
		$data = array(
			'' => 'Choose an encounter...'
		);
		if ($result) {
			foreach ($result as $row) {
				$key = $row->eid;
				$value = date('Y-m-d', $this->human_to_unix($row->encounter_DOS)) . ' (Chief complaint: ' . $row->encounter_cc . ')';
				$data[$key] = $value;
			}
		}
		echo json_encode($data);
	}
}
