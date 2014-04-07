<?php

class Image extends Eloquent
{
	public $timestamps = false;
	protected $table = 'image';
	protected $primaryKey = 'image_id';
}
