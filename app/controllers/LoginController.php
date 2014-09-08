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
			$validator = Validator::make(Input::all(), array(
				"username" => "required",
				"password" => "required",
				"practice_id" => "required"
			));
			if ($validator->passes()) {
				$username = Input::get('username');
				$password = Input::get('password');
				$practice_id = Input::get('practice_id');
				$credentials = array(
					"username" => $username,
					"password" => $password,
					"active" => '1',
					"practice_id" => $practice_id
				);
				if (Auth::attempt($credentials)) {
					$user = User::where('username', '=', $username)->where('active', '=', '1')->where('practice_id', '=', $practice_id)->first();
					$practice = Practiceinfo::find($practice_id);
					Session::put('user_id', $user->id);
					Session::put('group_id', $user->group_id);
					Session::put('practice_id', $practice_id);
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
}
