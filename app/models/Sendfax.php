<?php

class Sendfax extends Eloquent
{
	public $timestamps = false;
	protected $table = 'sendfax';
	protected $primaryKey = 'job_id';
	public function pages()
	{
		return $this->hasMany('Pages', 'job_id', 'job_id');
	}
	public function recipients()
	{
		return $this->hasMany('Recipients', 'job_id', 'job_id');
	}
}
