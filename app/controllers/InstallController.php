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
	
	public function test2()
	{
		$this->layout->title = "NOSH ChartingSystem Database Connection Fixer";
		$this->layout->style = '';
		$this->layout->script = '';
		$this->layout->content = View::make('encounters.pe');
	}
}
