<?php

class Api_queue extends Eloquent
{
	public $timestamps = true;
	protected $table = 'api_queue';
	protected $primaryKey = 'id';
}
