<?php

class APIv1Controller extends BaseController
{

	/**
	* NOSH ChartingSystem API Functions
	*/
	
	public function add()
	{
		$data = Input::all();
		$practice = DB::table('practiceinfo')->where('practice_api_key', '=', $data['api_key'])->first();
		$patient = DB::table('demographics')->first();
		if ($practice) {
			$data1 = $data['data'];
			$data1['pid'] = $patient->pid;
			$id = DB::table($data['table'])->insertGetId($data1);
			$this->audit('Add');
			return Response::json(array(
				'error' => false,
				'message' => 'Adding data successful',
				'remote_id' => $id
			),200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'Adding data unsuccessful; no practice identified.'
			),200);
		}
	}
	
	public function update()
	{
		$data = Input::all();
		$practice = DB::table('practiceinfo')->where('practice_api_key', '=', $data['api_key'])->first();
		$patient = DB::table('demographics')->first();
		if ($practice) {
			$data1 = $data['data'];
			$data1['pid'] = $patient->pid;
			DB::table($data['table'])->where($data['primary'], '=', $data['remote_id'])->update($data1);
			$this->audit('Update');
			return Response::json(array(
				'error' => false,
				'message' => 'Updating data successful',
				'remote_id' => $data['remote_id']
			),200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'Updating data unsuccessful; no practice identified.'
			),200);
		}
	}
	
	public function delete()
	{
		$data = Input::all();
		$practice = DB::table('practiceinfo')->where('practice_api_key', '=', $data['api_key'])->first();
		$patient = DB::table('demographics')->first();
		if ($practice) {
			DB::table($data['table'])->where($data['primary'], '=', $data['remote_id'])->delete();
			$this->audit('Delete');
			return Response::json(array(
				'error' => false,
				'message' => 'Deleting data successful',
				'remote_id' => $data['remote_id']
			),200);
		} else {
			return Response::json(array(
				'error' => true,
				'message' => 'Deleting data unsuccessful; no practice identified.'
			),200);
		}
	}
}
