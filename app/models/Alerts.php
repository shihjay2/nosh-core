<?php

class Alerts extends Eloquent
{
	public $timestamps = false;
	protected $table = 'alerts';
	protected $primaryKey = 'alert_id';
}
