<?php

class Schedule extends Eloquent
{
	public $timestamps = false;
	protected $table = 'schedule';
	protected $primaryKey = 'appt_id';
}
