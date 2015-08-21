<?php

class UMAAPIController extends BaseController
{

	/**
	* NOSH ChartingSystem UMA API Functions
	*/
	
	public function getClients()
	{
		$url = URL::current();
		$result = $this->uma_api_build('clients', $url);
		return $result;
	}
}
