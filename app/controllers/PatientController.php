<?php

class PatientController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = Input::all();
		if ($data) {
			$resource = 'Patient';
			$table = 'demographics';
			$table_primary_key = 'pid';
			$table_key = [
				'name' => ['lastname','firstname'],
				'identifier' => 'pid',
				'telcom' => ['phone_home','phone_work','phone_cell'],
				'gender' => 'sex',
				'birthDate' => 'dob',
				'address' => ['address','city','state','zip'],
				'contact.relationship' => 'guardian_relationship',
				'contact.name' => ['guardian_lastname','guardian_firstname'],
				'contact.telcom' => 'guardian_phone_home',
				'active' => 'active'
			];
			
			$result = $this->resource_translation($data, $table, $table_primary_key, $table_key);
			$queries = DB::getQueryLog();
			$sql = end($queries);
			if (!empty($sql['bindings'])) {
				$pdo = DB::getPdo();
				foreach($sql['bindings'] as $binding) {
					$sql['query'] = preg_replace('/\?/', $pdo->quote($binding), $sql['query'], 1);
				}
			}
			if ($result['response'] == true) {
				$statusCode = 200;
				$time = date('c', time());
				$reference_uuid = $this->gen_uuid();
				$response['resourceType'] = 'Bundle';
				$response['title'] = 'Search result';
				$response['id'] = 'urn:uuid:' . $this->gen_uuid();
				$response['updated'] = $time;
				$response['category'][] = [
					'scheme' => 'http://hl7.org/fhir/tag',
					'term' => 'http://hl7.org/fhir/tag/message',
					'label' => 'http://ht7.org/fhir/tag/label'
				];
				$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
				$response['author'][] = [
					'name' => $practice->practice_name,
					'uri' => route('home') . '/fhir'
				];
				$response['totalResults'] = $result['total'];
				foreach ($result['data'] as $row_id) {
					$row = DB::table($table)->where($table_primary_key, '=', $row_id)->first();
					$resource_content = $this->resource_detail($row, $resource);
					$response['entry'][] = [
						'title' => 'Resource of type ' . $resource . ' with id = ' . $row_id . ' and version = 1',
						'link' => [
							'rel' => 'self',
							'href' => Request::url() . '/' . $row_id
						],
						'id' => Request::url() . '/' . $row_id,
						'updated' => $time,
						'published' => $time,
						'author' => [
							'name' => $practice->practice_name,
							'uri' => route('home') . '/fhir'
						],
						'category' => [
							'scheme' => 'http://hl7.org/fhir/tag',
							'term' => 'http://hl7.org/fhir/tag/message',
							'label' => 'http://ht7.org/fhir/tag/label'
						],
						'content' => $resource_content,
						// the summary is variable
						'summary' => '<div><h5>' . $row->lastname . ', ' . $row->firstname . '. MRN: ' . $row->pid . '</h5></div>'
					];
				}
			} else {
				$response = [
					'error' => "Query returned 0 records.",
				];
				$statusCode = 404;
			}
		} else {
			$response = [
				'error' => "Invalid query."
			];
			$statusCode = 404;
		}
		
		$response['code'] = $sql['query'];
		return Response::json($response, $statusCode);
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
		$resource = 'Patient';
		$table = 'demographics';
		$table_primary_key = 'pid';
		
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$row = DB::table($table)->where($table_primary_key, '=', $id)->first();
		if ($row) {
			$statusCode = 200;
			$response = $this->resource_detail($row, $resource);
		} else {
			$response = [
				'error' => "Patient doesn't exist."
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
