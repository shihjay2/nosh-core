<?php

class Audit extends Eloquent
{
	public $timestamps = false;
	protected $table = 'audit';
	protected $primaryKey = 'audit_id';
}
