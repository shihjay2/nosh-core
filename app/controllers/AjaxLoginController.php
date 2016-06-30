<?php

class AjaxLoginController extends BaseController {

	/**
	* NOSH ChartingSystem Login Ajax Functions
	*/

	public function postPractices()
	{
		$practices = Practiceinfo::all();
		$data['message'] = array();
		if ($practices) {
			foreach ($practices as $practice) {
				$data['message'][$practice->practice_id] = $practice->practice_name;
			}
		}
		echo json_encode($data);
	}

	public function postPracticeLogo($practice_id)
	{
		$practice = Practiceinfo::find($practice_id);
		$html = "";
		if ($practice->practice_logo != '') {
			$html = HTML::image($practice->practice_logo, 'Practice Logo', array('border' => '0'));
		}
		echo $html;
	}

	public function postRegisterUser()
	{
		ini_set('memory_limit','196M');
		if ($this->rpHash(Input::get('numberReal')) == Input::get('numberRealHash')) {
			$registration_code = Input::get('registration_code');
			if ($registration_code != '') {
				$count = intval(Input::get('count'));
				if ($count > 2) {
					$arr['response'] = "3";
					echo json_encode($arr);
					exit (0);
				}
				$dob = date('Y-m-d', strtotime(Input::get('dob')));
				$result = DB::table('demographics')->where('registration_code', '=', $registration_code)
					->where('firstname', '=', Input::get('firstname'))
					->where('lastname', '=', Input::get('lastname'))
					->where('DOB', '=', $dob)
					->first();
				if ($result) {
					$displayname = Input::get('firstname') . " " . Input::get('lastname');
					$demographics_relate = DB::table('demographics_relate')
						->where('pid', '=', $result->pid)
						->where(function($query_array1) {
							$query_array1->whereNotNull('id')
							->orWhere('id', '!=', '')
							->orWhere('id', '!=', '0');
						})
						->get();
					if ($demographics_relate) {
						$arr['response'] = "1";
						foreach ($demographics_relate as $demographics_relate_row) {
							$row1 = DB::table('practiceinfo')->where('practice_id', '=', $demographics_relate_row->practice_id)->first();
							$data1 = array(
								'username' => Input::get('username'),
								'firstname' => Input::get('firstname'),
								'lastname' => Input::get('lastname'),
								'email' => Input::get('email'),
								'group_id' => '100',
								'active' => '1',
								'displayname' => $displayname,
								'practice_id' => $demographics_relate_row->practice_id
							);
							$arr['id'] = DB::table('users')->insertGetId($data1);
							$this->audit('Add');
							$data2 = array(
								'id' => $arr['id']
							);
							DB::table('demographics_relate')->where('demographics_relate_id', '=', $demographics_relate_row->demographics_relate_id)->update($data2);
							$this->audit('Update');
							$data_message1['practicename'] = $row1->practice_name;
							$data_message1['username'] = Input::get('username');
							$data_message1['url'] = route('home');
							$this->send_mail('emails.loginregistrationconfirm', $data_message1, 'Patient Portal Registration Confirmation', Input::get('email'), $demographics_relate_row->practice_id);
						}
					} else {
						$arr['response'] = "5";
						$row2 = User::where('id', '=', $demographics_relate_row->id)->first();
						$data_message['practicename'] = $row1->practice_name;
						$data_message['username'] = $row2->username;
						$data_message['url'] = route('home');
						$this->send_mail('emails.loginregistration', $data_message, 'Patient Portal Registration Message', Input::get('email'), $demographics_relate_row->practice_id);
					}
				} else {
					$arr['response'] = "2";
					$count++;
					$arr['count'] = strval($count);
				}
			} else {
				$row3 = Practiceinfo::find(Input::get('practice_id'));
				$displayname = Session::get('displayname');
				$data_message2 = array(
					'firstname' => Input::get('firstname'),
					'lastname' => Input::get('lastname'),
					'dob' => Input::get('dob'),
					'username' => Input::get('username'),
					'email' => Input::get('email')
				);
				$this->send_mail('emails.loginregistrationrequest', $data_message2, 'New User Request', $row3->email, Input::get('practice_id'));
				$arr['response'] = "4";
			}
		} else {
			$count = intval(Input::get('count'));
			$arr['response'] = "2";
			$count++;
			$arr['count'] = strval($count);
		}
		echo json_encode($arr);
	}

	public function postForgotPassword($username)
	{
		$result = User::where('username', '=', $username)->first();
		if ($result) {
			if ($result->secret_question == '') {
				$arr['response'] = "You need to setup a secret question and answer.  Contact the practice administrator to manually reset your password.";
			} else {
				$arr['response'] = $result->secret_question;
			}
			$arr['id'] = $result->id;
		} else {
			$arr['response'] = "You are not a registered user.";
		}
		echo json_encode($arr);
	}

	public function postForgotPassword1()
	{
		$id = Input::get('id');
		$count = intval(Input::get('count'));
		if ($count > 2) {
			$arr['response'] = "Close";
			echo json_encode($arr);
			exit (0);
		}
		$result = User::find($id);
		if ($result->secret_answer == Input::get('secret_answer')) {
			$arr['response'] = "OK";
		} else {
			$arr['response'] = "Secret answer is incorrect!";
			$count++;
			$arr['count'] = strval($count);
		}
		echo json_encode($arr);
	}

	public function postChangePassword($id)
	{
		$user = User::find($id);
		$user->password = substr_replace(Hash::make(Input::get('new_password')),"$2a",0,3);
		$user->save();
		echo 'OK';
	}

	public function postChangePassword1()
	{
		$id = Session::get('user_id');
		$user = User::find($id);
		$credentials = array(
			"username" => $user->username,
			"password" => Input::get('old_password'),
			"active" => '1',
			"practice_id" => Session::get('practice_id')
		);
		if (Auth::attempt($credentials)) {
			$new_password = substr_replace(Hash::make(Input::get('new_password')),"$2a",0,3);
			$data = array (
				'password' => $new_password,
				'secret_question' => Input::get('secret_question'),
				'secret_answer' => Input::get('secret_answer')
			);
			DB::table('users')->where('id', '=', $id)->update($data);
			$this->audit('Update');
			// Check if patient-centric and associated with UMA Server
			if (Session::get('group_id') == '100' && Session::get('patient_centric') == 'y') {
				$res = DB::select("SHOW DATABASES LIKE 'oic_production'");
				if(count($res) > 0) {
					$oic = DB::connection('oic')->table('users')->where('username', '=', $user->username)->first();
					if ($oic) {
						$data_edit = array(
							'password' => $new_password
						);
						DB::connection('oic')->table('users')->where('username', '=', $user->username)->update($data_edit);
					}
				}
			}
			echo 'Password changed!';
		} else {
			echo 'Your old password is incorrect!';
		}
	}

	public function postCheckUsername()
	{
		$data['response'] = true;
		if (strlen(Input::get('username')) < 4) {
			$data['message'] = "Username needs to be more than 4 characters long!";
		} else {
			$user = User::where('username', '=', Input::get('username'))->first();
			if ($user) {
				$data['message'] = "Username already exists!";
			} else {
				$data['response'] = false;
			}
		}
		echo json_encode($data);
	}

	public function postGetSecret()
	{
		$result = User::find(Session::get('user_id'))->toArray();
		echo json_encode($result);
	}

	public function postSetSecret()
	{
		$id = Session::get('user_id');
		$data = array (
			'secret_question' => Input::get('secret_question'),
			'secret_answer' => Input::get('secret_answer')
		);
		DB::table('users')->where('id', '=', $id)->update($data);
		$this->audit('Update');
		echo 'Secret question and answer set!';
	}

	public function postCheckSecret()
	{
		$arr = array(
			'secret' => '',
			'setup' => '',
			'template' => ''
		);
		$result = User::find(Session::get('user_id'));
		if ($result->secret_question == '') {
			$arr['secret'] = "Need secret question and answer!";
		}
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		if ($practice->icd == '' && Session::get('patient_centric') == 'yp' && Session::get('group_id') == '2') {
			$arr['setup'] = 'y';
			$arr['template'] = 'y';
		}
		$template = DB::table('templates')->where('practice_id', '=', Session::get('practice_id'))->where('template_core_id', '=', '1')->first();
		if (!$template && Session::get('group_id') == '1') {
			$arr['template'] = 'y';
		}
		echo json_encode($arr);
	}

	public function apilogin()
	{
		$data = Input::all();
		$practice = DB::table('practiceinfo')->where('npi', '=', $data['npi'])->where('practice_api_key', '=', $data['api_key'])->first();
		if ($practice) {
			$password = Hash::make(time());
			$data1 = array(
				'username' => $practice->practice_api_key,
				'password' => $password,
				'group_id' => '99',
				'practice_id' => $practice->practice_id
			);
			DB::table('users')->insert($data1);
			$this->audit('Add');
			return Response::json(array(
				'error' => false,
				'message' => 'Login successful',
				'username' => $practice->practice_api_key,
				'password' => $password
			),200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'Login incorrect!'
			),200);
		}
	}

	public function apilogout()
	{
		$data = Input::all();
		$practice = DB::table('practiceinfo')->where('npi', '=', $data['npi'])->where('practice_api_key', '=', $data['api_key'])->first();
		if ($practice) {
			DB::table('users')->where('group_id', '=', '99')->where('practice_id', '=', $practice->practice_id)->delete();
			$this->audit('Delete');
			return Response::json(array(
				'error' => false,
				'message' => 'Logout successful'
			),200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'Login incorrect!'
			),200);
		}
	}

	public function postHieofone()
	{
		$arr['response'] = 'y';
		$arr['message'] = "Credentials transferred!";
		$url = 'https://noshchartingsystem.com/nosh-sso/noshadduser';
		$user = DB::table('users')->where('id', '=', Session::get('user_id'))->first();
		if (Input::get('username') != '') {
			$username = Input::get('username');
		} else {
			$username = $user->username;
		}
		$provider = DB::table('providers')->where('id', '=', Session::get('user_id'))->first();
		$data = array(
			'username' => $username,
			'password' => $user->password,
			'email' => $user->email,
			'npi' => $provider->npi,
			'name' => $user->displayname,
			'firstname' => $user->firstname,
			'lastname' => $user->lastname,
			'middle' => $user->middle
		);
		$result = $this->send_api_data($url, $data, '', '');
		if ($result['url_error'] != '') {
			$arr['response'] = 'n';
			$arr['message'] = $result['url_error'];
		} else {
			if ($result['error'] == true) {
				$arr['response'] = 'n';
				$arr['message'] = $result['message'];
			} else {
				$new_data = array(
					'uid' => $result['uid']
				);
				DB::table('users')->where('id', '=', Session::get('user_id'))->update($new_data);
				$this->audit('Update');
			}
		}
		echo json_encode($arr);
	}
}
