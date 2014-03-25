<?php

class Ci_sessions extends Eloquent
{
	public $timestamps = false;
	protected $table = 'ci_sessions';
	protected $primaryKey = 'session_id';
}
