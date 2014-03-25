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
				$result = Demographics::where('registration_code', '=', $registration_code)
					->where('firstname', '=', Input::get('firstname'))
					->where('lastname', '=', Input::get('lastname'))
					->where('DOB', '=', $dob)
					->first();
				if ($result) {
					$arr['response'] = "1";
					$displayname = Input::get('firstname') . " " . Input::get('lastname');
					$demographics_relate = Demographics_relate::where('pid', '=', $result->pid)->get();
					foreach ($demographics_relate as $demographics_relate_row) {
						$row1 = Practiceinfo::where('practice_id', '=', $demographics_relate_row->practice_id)->first();
						if ($demographics_relate_row->id != "") {
							$arr['response'] = "5";
							$row2 = User::where('id', '=', $demographics_relate_row->id)->first();
							$data_message['practicename'] = $row1->practice_name;
							$data_message['username'] = $row2->username;
							$data_message['url'] = route('/');
							$this->send_mail('emails.loginregistration', $data_message, 'Patient Portal Registration Message', Input::get('email'), $demographics_relate_row->practice_id);
						} else {
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
							$data_message1['url'] = route('/');
							$this->send_mail('emails.loginregistrationconfirm', $data_message1, 'Patient Portal Registration Confirmation', Input::get('email'), $demographics_relate_row->practice_id);
						}
					}
				} else {
					$arr['response'] = "2";
					$count++;
					$arr['count'] = strval($count);
				}
			} else {
				$row3 = Practiceinfo::find(Input::get('practice_id'));
				$displayname = $this->session->userdata('displayname');
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
		$result = Users::find($id);
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
		$user->password = Hash::make(Input::get('new_password'));
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
			$new_password = Hash::make(Input::get('new_password'));
			$data = array (
				'password' => $new_password,
				'secret_question' => Input::get('secret_question'),
				'secret_answer' => Input::get('secret_answer')
			);
			DB::table('users')->where('id', '=', $id)->update($data);
			$this->audit('Update');
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
		$result = User::find(Session::get('user_id'));
		if ($result->secret_question == '') {
			echo "Need secret question and answer!";
		} else {
			echo "OK";
		}
	}
}
