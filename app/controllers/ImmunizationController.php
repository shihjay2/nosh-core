<?php

class ImmunizationController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$resource_set_array[] = array(
			'name' => 'Binary Files',
			'icon' => 'https://noshchartingsystem.com/i-file.png',
			'scopes' => array(
				URL::to('/') . '/fhir/Binary'
			)
		);
		$oidc1 = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc1->refresh($practice->uma_refresh_token,true);
		foreach ($resource_set_array as $resource_set_item) {
			$response = $oidc1->resource_set($resource_set_item['name'], $resource_set_item['icon'], $resource_set_item['scopes']);
		}
		return $response;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
