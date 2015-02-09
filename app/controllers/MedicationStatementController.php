<?php

class MedicationStatementController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		$query = DB::table('rx_list')->where('pid', '=', $id)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		if ($query) {
			$statusCode = 200;
			$response['resourceType'] = 'Patient';
			$response['text']['status'] = 'generated';
			$response['text']['div'] = '';
			$response['patient']['reference'] = 'Patient/example';
			$response['whenGiven']['start'] = date('c', $this->human_to_unix($med_row->rxl_date_active));
			$response['whenGiven']['end'] = date('c', $this->human_to_unix($med_row->rxl_date_active));
			foreach ($query as $row) {
			
			}
		} else {
			$response = [
				'error' => "No active medications for patient."
			];
			$statusCode = 404;
		}
		return Response::json($response, $statusCode);
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
