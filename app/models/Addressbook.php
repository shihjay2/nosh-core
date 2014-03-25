<?php

class Addressbook extends Eloquent
{
	public $timestamps = false;
	protected $table = 'addressbook';
	protected $primaryKey = 'address_id';
}
