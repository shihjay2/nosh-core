<?php

class Issues extends Eloquent
{
	public $timestamps = false;
	protected $table = 'issues';
	protected $primaryKey = 'issue_id';
	public function scopeActive($query, $pid)
	{
		return $query->where('pid', $pid)->where('issue_date_inactive', '=', '0000-00-00 00:00:00');
	}
	public function scopeInactive($query, $pid)
	{
		return $query->where('pid', $pid)->where('issue_date_inactive', '!=', '0000-00-00 00:00:00');
	}
}
