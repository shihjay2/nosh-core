<?php

class Immunizations extends Eloquent
{
	public $timestamps = false;
	protected $table = 'immunizations';
	protected $primaryKey = 'imm_id';
}
