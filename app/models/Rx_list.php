<?php

class Rx_list extends Eloquent
{
	public $timestamps = false;
	protected $table = 'rx_list';
	protected $primaryKey = 'rxl_id';
	public function scopeActive($query, $pid)
	{
		return $query->where('pid', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00');
	}
	public function scopeInactive($query, $pid)
	{
		return $query->where('pid', $pid)->where('rxl_date_inactive', '!=', '0000-00-00 00:00:00');
	}
}
