<?php

class Demographics extends Eloquent
{
	public $timestamps = false;
	protected $table = 'demographics';
	protected $primaryKey = 'pid';
	public function allergies()
	{
		return $this->hasMany('Allergies', 'pid', 'pid');
	}
	public function encounters()
	{
		return $this->hasMany('Encounters', 'pid', 'pid');
	}
	public function immunizations()
	{
		return $this->hasMany('Immunizations', 'pid', 'pid');
	}
	public function insurance()
	{
		return $this->hasMany('Insurance', 'pid', 'pid');
	}
	public function issues()
	{
		return $this->hasMany('Issues', 'pid', 'pid');
	}
	public function mtm()
	{
		return $this->hasMany('Mtm', 'pid', 'pid');
	}
	public function rx_list()
	{
		return $this->hasMany('Rx_list', 'pid', 'pid');
	}
	public function sup_list()
	{
		return $this->hasMany('Sup_list', 'pid', 'pid');
	}
	public function tests()
	{
		return $this->hasMany('Tests', 'pid', 'pid');
	}
}
