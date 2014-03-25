<?php

class Calendar extends Eloquent
{
	public $timestamps = false;
	protected $table = 'calendar';
	protected $primaryKey = 'calendar_id';
}
