<?php

class Recipients extends Eloquent
{
	public $timestamps = false;
	protected $table = 'recipients';
	protected $primaryKey = 'sendlist_id';
}
