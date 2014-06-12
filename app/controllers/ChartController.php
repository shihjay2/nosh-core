<?php

class ChartController extends BaseController {

	protected $layout = 'layouts.layout3';
	
	public function main()
	{
		$row = Practiceinfo::find(Session::get('practice_id'));
		if (isset($row->default_pos_id)) {
			$data['default_pos'] = $row->default_pos_id;
		} else {
			$data['default_pos'] = '';
		}
		if ($row->weekends == '1') {
			$data['weekends'] = 'true';
		} else {
			$data['weekends'] = 'false';
		}
		$data['minTime'] = ltrim($row->minTime,"0");
		$data['maxTime'] = ltrim($row->maxTime,"0");
		if (!Session::get('encounter_active')) {
			Session::put('encounter_active', 'n');
		}
		if (Session::get('group_id') == '2') {
			$provider = Providers::find(Session::get('user_id'));
			$data['schedule_increment'] = $provider->schedule_increment;
		} else {
			$data['schedule_increment'] = '15';
		}
		if ($row->fax_type != "") {
			$data1['fax'] = true;
		} else {
			$data1['fax'] = false;
		}
		$this->layout->style = $this->css_assets();
		$this->layout->script = $this->js_assets('chart');
		//$this->layout->style = '';
		//$this->layout->script = HTML::script('/js/chart.js');
		//$this->layout->script .= HTML::script('/js/demographics.js');
		//$this->layout->script .= HTML::script('/js/searchbar.js');
		//$this->layout->script .= HTML::script('/js/options.js');
		//$this->layout->script .= HTML::script('/js/menu.js');
		//$this->layout->script .= HTML::script('/js/issues.js');
		//$this->layout->script .= HTML::script('/js/encounters.js');
		//$this->layout->script .= HTML::script('/js/medications.js');
		//$this->layout->script .= HTML::script('/js/supplements.js');
		//$this->layout->script .= HTML::script('/js/allergies.js');
		//$this->layout->script .= HTML::script('/js/alerts.js');
		//$this->layout->script .= HTML::script('/js/immunizations.js');
		//$this->layout->script .= HTML::script('/js/print.js');
		//$this->layout->script .= HTML::script('/js/billing.js');
		//$this->layout->script .= HTML::script('/js/documents.js');
		//$this->layout->script .= HTML::script('/js/t_messages.js');
		//$this->layout->script .= HTML::script('/js/lab.js');
		//$this->layout->script .= HTML::script('/js/rad.js');
		//$this->layout->script .= HTML::script('/js/cp.js');
		//$this->layout->script .= HTML::script('/js/ref.js');
		//$this->layout->script .= HTML::script('/js/messaging.js');
		//$this->layout->script .= HTML::script('/js/schedule.js');
		//$this->layout->script .= HTML::script('/js/financial.js');
		//$this->layout->script .= HTML::script('/js/office.js');
		//$this->layout->script .= HTML::script('/js/graph.js');
		//$this->layout->script .= HTML::script('/js/image.js');
		$this->layout->search = View::make('search', $this->getSearchData())->render();
		$this->layout->menu = View::make('menu', $this->getMenuData())->render();
		$this->layout->content = View::make('chart', $data)->render();
		$this->layout->modules = View::make('demographics')->render();
		$this->layout->modules .= View::make('options')->render();
		$this->layout->modules .= View::make('issues')->render();
		$this->layout->modules .= View::make('medications')->render();
		$this->layout->modules .= View::make('allergies')->render();
		$this->layout->modules .= View::make('supplements')->render();
		$this->layout->modules .= View::make('immunizations')->render();
		$this->layout->modules .= View::make('print')->render();
		$this->layout->modules .= View::make('billing')->render();
		$this->layout->modules .= View::make('documents')->render();
		$this->layout->modules .= View::make('t_messages')->render();
		$this->layout->modules .= View::make('encounters')->render();
		$this->layout->modules .= View::make('alerts')->render();
		$this->layout->modules .= View::make('lab')->render();
		$this->layout->modules .= View::make('rad')->render();
		$this->layout->modules .= View::make('cp')->render();
		$this->layout->modules .= View::make('ref')->render();
		$this->layout->modules .= View::make('messaging', $data1)->render();
		$this->layout->modules .= View::make('schedule')->render();
		$this->layout->modules .= View::make('financial')->render();
		$this->layout->modules .= View::make('office')->render();
		$this->layout->modules .= View::make('graph')->render();
		$this->layout->modules .= View::make('image')->render();
		if($row->mtm_extension == 'y') {
			$this->layout->content .= View::make('mtm')->render();
			$this->layout->script .= HTML::script('/js/mtm.js');
		}
	}
	
	public function print_ccr()
	{
		$pid = Session::get('pid');
		ini_set('memory_limit','196M');
		$user_id = Session::get('user_id');
		$row = Demographics::find($pid);
		$header = strtoupper($row->lastname . ', ' . $row->firstname . '(DOB: ' . date('m/d/Y', $this->human_to_unix($row->DOB)) . ', Gender: ' . ucfirst(Session::get('gender')) . ', ID: ' . $pid . ')');
		$html = $this->page_intro('Continuity of Care Record', Session::get('practice_id'))->render();
		$html .= $this->page_ccr($pid)->render();
		$file_path = __DIR__."/../../public/temp/ccr_" . time() . "_" . $user_id . ".pdf";
		$this->generate_pdf($html, $file_path, 'footerpdf', $header, '2');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function print_medication($rxl_id)
	{
		ini_set('memory_limit','196M');
		$html = $this->page_medication($rxl_id)->render();
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/rx_" . time() . "_" . $user_id . ".pdf";
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function print_medication_list()
	{
		ini_set('memory_limit','196M');
		$html = $this->page_medication_list()->render();
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/rx_list_" . time() . "_" . $user_id . ".pdf";
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function view_faxpage($pages_id)
	{
		$result = DB::table('pages')->where('pages_id', '=', $pages_id)->first();
		$file_path = $result->file;
		return Response::download($file_path);
	}
	
	public function view_documents($id)
	{
		$result = Documents::find($id);
		$file_path = $result->documents_url;
		$data = array(
			'documents_viewed' => Session::get('displayname')
		);
		DB::table('documents')->where('documents_id', '=', $id)->update($data);
		$this->audit('Update');
		return Response::download($file_path);
	}
	
	public function print_consent()
	{
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/vaccine_consent_output_" . $user_id . ".pdf";
		return Response::download($file_path);
	}
	
	public function print_immunization_list()
	{
		ini_set('memory_limit','196M');
		$html = $this->page_immunization_list()->render();
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/imm_list_" . time() . "_" . $user_id . ".pdf";
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function csv_immunization()
	{
		$pid = Session::get('pid');
		$result = DB::table('immunizations')
			->join('demographics', 'demographics.pid', '=', 'immunizations.pid')
			->join('insurance', 'insurance.pid' , '=', 'immunizations.pid')
			->where('immunizations.pid', '=', $pid)
			->where('insurance.insurance_plan_active', '=', 'Yes')
			->where('insurance.insurance_order', '=', 'Primary')
			->select('immunizations.pid', 'demographics.lastname', 'demographics.firstname', 'demographics.DOB', 'demographics.sex', 'demographics.address', 'demographics.city', 'demographics.state', 'demographics.zip', 'demographics.phone_home', 'immunizations.imm_cvxcode', 'immunizations.imm_elsewhere', 'immunizations.imm_date', 'immunizations.imm_lot', 'immunizations.imm_manufacturer', 'insurance.insurance_plan_name')
			->get();
		$csv = '';
		if ($result) {
			$csv .= "PatientID,Last,First,BirthDate,Gender,PatientAddress,City,State,Zip,Phone,ImmunizationCVX,OtherClinic,DateGiven,LotNumber,Manufacturer,InsuredPlanName";
			foreach ($result as $row1) {
				$row = (array) $row1;
				$row['DOB'] = date('m/d/Y', $this->human_to_unix($row['DOB']));
				$row['imm_date'] = date('m/d/Y', $this->human_to_unix($row['imm_date']));
				$row['sex'] = strtoupper($row['sex']);
				if ($row['imm_elsewhere'] == 'Yes') {
					$row['imm_elsewhere'] = $row['imm_date'];
				} else {
					$row['imm_elsewhere'] = '';
				}
				$csv .= "\n";
				$csv .= implode(',', $row);
			}
		}
		$file_path = __DIR__."/../../public/temp/" . time() . '_immunization_csv.txt';
		File::put($file_path, $csv);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function print_charts($hippa_id, $type) {
		$pid = Session::get('pid');
		$file_path = $this->print_chart($pid, 'fax', $hippa_id, $type);
		return Response::download($file_path);
	}
	
	public function ccda($hippa_id)
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$file_path = __DIR__.'/../../public/temp/ccda_' . $pid . "_" . time() . ".xml";
		$ccda = $this->generate_ccda($hippa_id);
		File::put($file_path, $ccda);
		return Response::download($file_path);
	}
	
	public function print_invoice1($eid, $insurance_id_1, $insurance_id_2)
	{
		ini_set('memory_limit','196M');
		if ($insurance_id_1 != '0') {
			$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		}
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/invoice_" . time() . "_" . $user_id . ".pdf";
		$html = $this->page_invoice1($eid)->render();
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function print_invoice2($id)
	{
		ini_set('memory_limit','196M');
		$user_id = Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/invoice_" . time() . "_" . $user_id . ".pdf";
		$html = $this->page_invoice2($id);
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function generate_hcfa($flatten, $eid)
	{
		$file_path = $this->hcfa($eid, $flatten);
		if ($file_path) {
			return Response::download($file_path);
		} else {
			return "No HCFA to print.";
		}
	}
	
	public function generate_hcfa1($flatten, $eid, $insurance_id_1, $insurance_id_2='')
	{
		$result = $this->billing_save_common($insurance_id_1, $insurance_id_2, $eid);
		$file_path = $this->hcfa($eid, $flatten);
		if ($file_path) {
			return Response::download($file_path);
		} else {
			return "No HCFA to print.";
		}
	}
	
	public function print_orders($orders_id)
	{
		ini_set('memory_limit','196M');
		$html = $this->page_orders($orders_id)->render();
		$file_path = __DIR__."/../../public/temp/orders_" . time() . "_" . Session::get('user_id') . ".pdf";
		$this->generate_pdf($html, $file_path);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		return Response::download($file_path);
	}
	
	public function printimage_single($eid)
	{
		$printimage = $this->printimage($eid);
		return Response::make($printimage, '200', array(
			'Content-Type' => 'application/octet-stream',
			'Content-Disposition' => 'attachment; filename="' . date('Ymd', time()) . '_printimage.txt"'
		));
	}
	
	public function print_batch($type, $filename)
	{
		if ($type=="batchprintimage") {
			$file_path = __DIR__.'/../../public/temp/' . $filename . "_" . $type . ".txt";
		} else {
			$file_path = __DIR__.'/../../public/temp/' . $filename . "_" . $type . ".pdf";
		}
		return Response::download($file_path);
	}
	
	public function financial_query_print($id)
	{
		$file_path = __DIR__."/../../public/temp/financial_query_" . $id . ".pdf";
		return Response::download($file_path);
	}
	
	public function export_demographics($type)
	{
		$practice_id = Session::get('practice_id');
		if ($type == "all") {
			$query = DB::table('demographics')
				->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
				->where('demographics_relate.practice_id', '=', $practice_id)
				->get();
		} else {
			$query = DB::table('demographics')
				->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
				->where('demographics_relate.practice_id', '=', $practice_id)
				->where('demographics.active', '=', '1')
				->get();
		}
		$i = 0;
		$csv = '';
		foreach ($query as $row) {
			$array_row = (array) $row;
			$array_values = array_values($array_row);
			if ($i == 0) {
				$array_key = array_keys($array_row);
				$csv .= implode(',', $array_key);
				$csv .= "\n" . implode(',', $array_values);
			} else {
				$csv .= "\n" . implode(',', $array_values);
			}
		}
		$file_path = __DIR__."/../../public/temp/" . time() . "_demographics.txt";
		File::put($file_path, $csv);
		return Response::download($file_path);
	}
}
