<?php

class Sup_list extends Eloquent
{
	public $timestamps = false;
	protected $table = 'sup_list';
	protected $primaryKey = 'sup_id';
	public function scopeActive($query, $pid)
	{
		return $query->where('pid', $pid)->where('sup_date_inactive', '=', '0000-00-00 00:00:00');
	}
	public function scopeInactive($query, $pid)
	{
		return $query->where('pid', $pid)->where('sup_date_inactive', '!=', '0000-00-00 00:00:00');
	}
}
