<?php

class Messaging extends Eloquent
{
	public $timestamps = false;
	protected $table = 'messaging';
	protected $primaryKey = 'message_id';
}
