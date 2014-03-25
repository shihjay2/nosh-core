<?php

class Billing extends Eloquent
{
	public $timestamps = false;
	protected $table = 'billing';
	protected $primaryKey = 'bill_id';
}
