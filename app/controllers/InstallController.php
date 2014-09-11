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
}
