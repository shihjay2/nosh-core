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
	
	public function postClients()
	{
		$url = URL::current();
		$send_object = array(
			'redirectUris' => [
				Input::get('redirectUris')
			],
			'clientName' => Input::get('clientName'),
			'clientUri' => Input::get('clientUri'),
			'logoUri' => Input::get('logoUri'),
			'tokenEndpointAuthMethod' => 'SECRET_BASIC',
			'generateSecret' => true,
			'accessTokenValiditySeconds' => 3600,
			'refreshTokenValiditySeconds' => null,
			'allowIntrospection' => true
		);
		$result = $this->uma_api_build('clients', $url, $send_object);
		return $result;
	}
	
	public function postClientsEdit()
	{
		$url = URL::current();
		$send_object = array(
			'redirectUris' => [
				Input::get('redirectUris')
			],
			'clientName' => Input::get('clientName'),
			'clientUri' => Input::get('clientUri'),
			'logoUri' => Input::get('logoUri'),
			'tokenEndpointAuthMethod' => 'SECRET_BASIC',
			'generateSecret' => true,
			'accessTokenValiditySeconds' => 3600,
			'refreshTokenValiditySeconds' => null,
			'allowIntrospection' => true
		);
		$result = $this->uma_api_build('clients/' . Input::get('id'), $url, $send_object, 'PUT');
		return $result;
	}
	
	public function postClientsDelete()
	{
		$url = URL::current();
		$result = $this->uma_api_build('clients/' . Input::get('id'), $url, null, 'DELETE');
		return $result;
	}
	
}
