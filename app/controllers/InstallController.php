<?php

class InstallController extends BaseController {

	/**
	* NOSH ChartingSystem Installation
	*/
	
	protected $layout = 'layouts.layout1';
	
	public function view()
	{
		$this->layout->title = "NOSH ChartingSystem Installation";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/install.js');
		$this->layout->content = View::make('install');
	}
	
	public function install_fix()
	{
		$this->layout->title = "NOSH ChartingSystem Database Connection Fixer";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/installfix.js');
		$this->layout->content = View::make('install_fix_db_conn');
	}
	
	public function install_oidc()
	{
		$this->layout->title = "NOSH ChartingSystem - Patient Version Installation";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/install_oidc.js');
		$this->layout->content = View::make('install_oidc');
	}
	
	public function practiceregister($api)
	{
		$this->layout->title = "NOSH ChartingSystem Practice Registration";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/practiceregister.js');
		$this->layout->content = '';
		$practice = DB::table('practiceinfo')->where('practice_registration_key', '=', $api)->first();
		$base = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		if ($practice) {
			$data['practice_id'] = $practice->practice_id;
			$data['patient_portal'] = rtrim($base->patient_portal, '/');
			$this->layout->content .= View::make('practiceregister', $data)->render();
		} else {
			$this->layout->content .= '<strong>Registration link timed out or does not exist!</strong><br>';
			$this->layout->content .= '<p>' . HTML::linkRoute('login', 'Click here to re-register to NOSH ChartingSystem') . '</p>';
		}
	}
	
	public function reset_database()
	{
		$this->layout->title = "NOSH ChartingSystem Reset Database";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/reset.js');
		$this->layout->content = View::make('reset_database');
	}
	
	public function google_start()
	{
		$this->layout->title = "NOSH ChartingSystem Pre Installation Check";
		$this->layout->style = '';
		$this->layout->script =  HTML::script('/js/google_start.js');
		$config_file = __DIR__."/../../.google";
		$data['file'] = "<strong>You're' here because you have not installed a Google OAuth2 Client ID file.  You'll need to set this up first before configuring NOSH Charting System.'</strong>";
		if (file_exists($config_file)) {
			$data['file'] = '<strong>A Google OAuth2 Client ID file is already installed.  Uploading a new file will overwrite the existing file!</strong>';
		}
		$this->layout->content = View::make('google_start', $data);
	}
}
