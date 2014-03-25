<?php

class Encounters extends Eloquent
{
	public $timestamps = false;
	protected $table = 'encounters';
	protected $primaryKey = 'eid';
	public function billing()
	{
		return $this->hasMany('Billing', 'eid', 'eid');
	}
	public function billing_core()
	{
		return $this->hasMany('Billing_core', 'eid', 'eid');
	}
	public function hpi()
	{
		return $this->hasOne('Hpi', 'eid', 'eid');
	}
	public function images()
	{
		return $this->hasMany('Images', 'eid', 'eid');
	}
	public function labs()
	{
		return $this->hasOne('Labs', 'eid', 'eid');
	}
	public function orders()
	{
		return $this->hasMany('Orders', 'eid', 'eid');
	}
	public function pe()
	{
		return $this->hasOne('Pe', 'eid', 'eid');
	}
	public function plan()
	{
		return $this->hasOne('Plan', 'eid', 'eid');
	}
	public function procedure()
	{
		return $this->hasOne('Procedure', 'eid', 'eid');
	}
	public function ros()
	{
		return $this->hasOne('Ros', 'eid', 'eid');
	}
	public function rx()
	{
		return $this->hasOne('Rx', 'eid', 'eid');
	}
	public function scopeList($query, $pid, $practice_id)
	{
		return $query->where('pid', $pid)->where('addendum', '=', 'n')->where('practice_id', '=', $practice_id);
	}
}

