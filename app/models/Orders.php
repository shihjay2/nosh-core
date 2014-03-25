<?php

class Orders extends Eloquent
{
	public $timestamps = false;
	protected $table = 'orders';
	protected $primaryKey = 'orders_id';
}
