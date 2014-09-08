<?php

class Hippa_request extends Eloquent
{
	public $timestamps = false;
	protected $table = 'hippa_request';
	protected $primaryKey = 'hippa_request_id';
}
