<?php

class AjaxOfficeController extends BaseController {

	/**
	* NOSH ChartingSystem Office Ajax Functions
	*/
	
	public function postVaccineInventory()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('vaccine_inventory')
			->where('quantity', '>', '0')
			->where('practice_id', '=', $practice_id)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('vaccine_inventory')
			->where('quantity', '>', '0')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postVaccineInventoryInactive()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('vaccine_inventory')
			->where('quantity', '<=', '0')
			->where('practice_id', '=', $practice_id)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('vaccine_inventory')
			->where('quantity', '<=', '0')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postEditVaccine()
	{
		$data = array(
			'imm_immunization' => Input::get('imm_immunization'),
			'imm_cvxcode' => Input::get('imm_cvxcode'),
			'imm_manufacturer' => Input::get('imm_manufacturer'),
			'imm_brand' => Input::get('imm_brand'),
			'imm_lot' => Input::get('imm_lot'),
			'quantity' => Input::get('quantity'),
			'cpt' => Input::get('cpt'),
			'imm_expiration' => date("Y-m-d H:i:s", strtotime(Input::get('imm_expiration'))),
			'date_purchase'=> date("Y-m-d H:i:s", strtotime(Input::get('date_purchase'))),
			'practice_id' => Session::get('practice_id')
		);
		if(Input::get('vaccine_id') == '') {
			DB::table('vaccine_inventory')->insert($data);
			$this->audit('Add');
			echo "Vaccine added!";
		} else {
			DB::table('vaccine_inventory')->where('vaccine_id', '=', Input::get('vaccine_id'))->update($data);
			$this->audit('Update');
			echo "Vaccine updated!";
		}
	}
	
	public function postInactivateVaccine()
	{
		$data = array(
			'quantity' => 0
		);
		DB::table('vaccine_inventory')->where('vaccine_id', '=', Input::get('vaccine_id'))->update($data);
		$this->audit('Update');
		echo "Vaccine inactivated!";
	}
	
	public function postDeleteVaccine()
	{
		DB::table('vaccine_inventory')->where('vaccine_id', '=', Input::get('vaccine_id'))->delete();
		$this->audit('Delete');
		echo "Vaccine deleted!";
	}

	public function postReactivateVaccine()
	{
		$data = array(
			'quantity' => Input::get('quantity')
		);
		DB::table('vaccine_inventory')->where('vaccine_id', '=', Input::get('vaccine_id'))->update($data);
		$this->audit('Update');
		echo "Vaccine reactivated!";
	}
	
	public function postVaccineTemp()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('vaccine_temp')
			->where('practice_id', '=', $practice_id)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('vaccine_temp')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postEditTemp()
	{
		$data = array(
			'temp' => Input::get('temp'),
			'date'=> date("Y-m-d H:i:s", strtotime(Input::get('temp_date') . ' ' . Input::get('temp_time'))),
			'action' => Input::get('action'),
			'practice_id' => Session::get('practice_id')
		);
		if(Input::get('temp_id') == '') {
			DB::table('vaccine_temp')->insert($data);
			$this->audit('Add');
			echo "Vaccine temperature added!";
		} else {
			DB::table('vaccine_temp')->where('temp_id', '=', Input::get('temp_id'))->update($data);
			$this->audit('Update');
			echo "Vaccine temperature updated!";
		}
	}
	
	public function postDeleteTemp()
	{
		DB::table('vaccine_temp')->where('temp_id', '=', Input::get('temp_id'))->delete();
		$this->audit('Delete');
		echo "Vaccine deleted!";
	}
	
	public function postGetSalesTax()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		echo $result->sales_tax;
	}
	
	public function postUpdateSalesTax()
	{
		$data['sales_tax'] = Input::get('sales_tax');
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		if ($data['sales_tax'] != "") {
			$query = DB::table('cpt')->where('cpt', '=', 'sptax')->first();
			if (!$query) {
				$data1 = array(
					'cpt' => 'sptax',
					'cpt_description' => 'Sales Tax',
					'cpt_charge' => '',
					'practice_id' => Session::get('practice_id')
				);
				DB::table('cpt_relate')->insert($data1);
				$this->audit('Add');
			}
		}
		echo "Sales tax percentage updated!";
	}
	
	public function postSupplementInventory()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('supplement_inventory')
			->where('quantity', '>', '0')
			->where('practice_id', '=', $practice_id)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('supplement_inventory')
			->where('quantity', '>', '0')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postSupplementInventoryInactive()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('supplement_inventory')
			->where('quantity', '<=', '0')
			->where('practice_id', '=', $practice_id)
			->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = DB::table('supplement_inventory')
			->where('quantity', '<=', '0')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		} 
		echo json_encode($response); 
	}
	
	public function postEditSupplement()
	{
		$data = array(
			'sup_description' => Input::get('sup_description'),
			'sup_strength' => Input::get('sup_strength'),
			'sup_manufacturer' => Input::get('sup_manufacturer'),
			'quantity' => Input::get('quantity'),
			'charge' => Input::get('charge'),
			'sup_expiration' => date("Y-m-d H:i:s", strtotime(Input::get('sup_expiration'))),
			'date_purchase'=> date("Y-m-d H:i:s", strtotime(Input::get('date_purchase'))),
			'sup_lot' => Input::get('sup_lot'),
			'practice_id' => Session::get('practice_id')
		);
		if(Input::get('cpt') != '') {
			$data['cpt'] = Input::get('cpt');
		} else {
			$cpt_query = DB::table('cpt_relate')->where('cpt', 'LIKE', '%sp%')->select('cpt')->get();
			if ($cpt_query) {
				$cpt_array = array();
				$i = 0;
				foreach ($cpt_query as $cpt_row) {
					$cpt_array[$i]['cpt'] = $cpt_row->cpt;
					$i++;
				}
				rsort($cpt_array);
				$cpt_num = str_replace("sp", "", $cpt_array[0]['cpt']);
				$cpt_num_new = $cpt_num + 1;
				$cpt_num_new = str_pad($cpt_num_new, 3, "0", STR_PAD_LEFT);
				$data['cpt'] = 'sp' . $cpt_num_new;
			} else {
				$data['cpt'] = 'sp001';
			}
		}
		$data1['cpt'] = $data['cpt'];
		$charge = str_replace("$", "", Input::get('charge'));
		$data1['cpt_charge'] = $charge;
		$pos = strpos($charge, ".");
		if ($pos === FALSE) {
			$charge .= ".00";
		}
		$data1['cpt_description'] = Input::get('sup_description');
		$cpt_query1 = DB::table('cpt_relate')->where('cpt', '=', $data1['cpt'])->first();
		if ($cpt_query1) {
			DB::table('cpt_relate')->where('cpt', '=', $data1['cpt'])->update($data1);
			$this->audit('Update');
		} else {
			DB::table('cpt_relate')->insert($data1);
			$this->audit('Add');
		}
		if(Input::get('supplement_id') == '') {
			DB::table('supplement_inventory')->insert($data);
			$this->audit('Add');
			echo "Supplement added!";
		} else {
			DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->update($data);
			$this->audit('Update');
			echo "Supplement updated!";
		}
	}
	
	public function postInactivateSupplement()
	{
		$data = array(
			'quantity' => 0
		);
		DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->update($data);
		$this->audit('Update');
		echo "Supplement inactivated!";
	}
	
	public function postDeleteSupplement()
	{
		DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->delete();
		$this->audit('Delete');
		echo "Supplement deleted!";
	}

	public function postReactivateSupplement()
	{
		$data = array(
			'quantity' => Input::get('quantity')
		);
		DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->update($data);
		$this->audit('Update');
		echo "Supplement reactivated!";
	}
	
	public function postSuperQuery()
	{
		$practice_id = Session::get('practice_id');
		$search_field = Input::get('search_field');
		$search_op = Input::get('search_op');
		$search_desc = Input::get('search_desc');
		$search_join = Input::get('search_join');
		$search_active_only = Input::get('search_active_only');
		$search_no_insurance_only = Input::get('search_no_insurance_only');
		$search_gender = Input::get('search_gender');
		$query_text1 = DB::table('demographics')
			->join('demographics_relate', 'demographics.pid', '=', 'demographics_relate.pid')
			->select('demographics.pid','demographics.lastname','demographics.firstname','demographics.DOB')
			->distinct()
			->where('demographics_relate.practice_id', '=', $practice_id);
		for($i = 0; $i<count($search_field); $i++) {
			if(isset($search_field[$i])) {
				if($search_field[$i] == 'age') {
					$query_text1->where(function($query_array0) use ($search_op, $search_desc, $search_join, $i) {
						$ago = strtotime($search_desc[$i] . " years ago");
						$unix_target1 = $ago - 15778463;
						$unix_target2 = $ago + 15778463;
						$target1 = date('Y-m-d 00:00:00', $unix_target1);
						$target2 = date('Y-m-d 00:00:00', $unix_target2);
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array0->whereBetween('demographics.DOB', array($target1, $target2));
								} else {
									$query_array0->orWhereBetween('demographics.DOB', array($target1, $target2));
								}
							} else {
								$query_array0->whereBetween('demographics.DOB', array($target1, $target2));
							}
						}
						if($search_op[$i] == 'greater than') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array0->where('demographics.DOB', '<', $target1);
								} else {
									$query_array0->orWhere('demographics.DOB', '<', $target1);
								}
							} else {
								$query_array0->where('demographics.DOB', '<', $target1);
							}
						}
						if($search_op[$i] == 'less than') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array0->where('demographics.DOB', '>', $target2);
								} else {
									$query_array0->orWhere('demographics.DOB', '>', $target2);
								}
							} else {
								$query_array0->where('demographics.DOB', '>', $target2);
							}
						}
					});
				}
				if($search_field[$i] == 'insurance') {
					$query_text1->join('insurance', 'insurance.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array1) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array1->where('insurance.insurance_order', '=', 'Primary')
										->where('insurance.insurance_plan_active', '=', 'Yes')
										->where('insurance.insurance_plan_name', '=',  $search_desc[$i]);
								} else {
									$query_array1->where('insurance.insurance_order', '=', 'Primary')
										->where('insurance.insurance_plan_active', '=', 'Yes')
										->orWhere('insurance.insurance_plan_name', '=',  $search_desc[$i]);
								}
							} else {
								$query_array1->where('insurance.insurance_order', '=', 'Primary')
									->where('insurance.insurance_plan_active', '=', 'Yes')
									->where('insurance.insurance_plan_name', '=',  $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array1->where('insurance.insurance_order', '=', 'Primary')
										->where('insurance.insurance_plan_active', '=', 'Yes')
										->where('insurance.insurance_plan_name', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array1->where('insurance.insurance_order', '=', 'Primary')
										->where('insurance.insurance_plan_active', '=', 'Yes')
										->orWhere('insurance.insurance_plan_name', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array1->where('insurance.insurance_order', '=', 'Primary')
									->where('insurance.insurance_plan_active', '=', 'Yes')
									->where('insurance.insurance_plan_name', 'LIKE', "%$search_desc[$i]%");
							}
						}
					});
				}
				if($search_field[$i] == 'issue') {
					$query_text1->join('issues', 'issues.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array2) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array2->where('issues.issue', '=', $search_desc[$i]);
								} else {
									$query_array2->orWhere('issues.issue', '=', $search_desc[$i]);
								}
							} else {
								$query_array2->where('issues.issue', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array2->where('issues.issue', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array2->orWhere('issues.issue', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array2->where('issues.issue', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array2->where('issues.issue', '!=', $search_desc[$i]);
								} else {
									$query_array2->orWhere('issues.issue', '!=', $search_desc[$i]);
								}		
							} else {
								$query_array2->where('issues.issue', '!=', $search_desc[$i]);
							}
						}
						$query_array2->where('issues.issue_date_inactive', '=', '0000-00-00 00:00:00');
					});
				}
				if($search_field[$i] == 'billing') {
					$query_text1->join('billing_core', 'billing_core.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array3) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array3->where('billing_core.cpt', '=', $search_desc[$i]);
								} else {
									$query_array3->orWhere('billing_core.cpt', '=', $search_desc[$i]);
								}
							} else {
								$query_array3->where('billing_core.cpt', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array3->where('billing_core.cpt', '!=', $search_desc[$i]);
								} else {
									$query_array3->orWhere('billing_core.cpt', '!=', $search_desc[$i]);
								}
							} else {
								$query_array3->where('billing_core.cpt', '!=', $search_desc[$i]);
							}
						}
					});
				}
				if($search_field[$i] == 'rxl_medication') {
					$query_text1->join('rx_list', 'rx_list.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array4) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array4->where('rx_list.rxl_medication', '=', $search_desc[$i]);
								} else {
									$query_array4->orWhere('rx_list.rxl_medication', '=', $search_desc[$i]);
								}
							} else {
								$query_array4->where('rx_list.rxl_medication', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array4->where('rx_list.rxl_medication', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array4->orWhere('rx_list.rxl_medication', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array4->where('rx_list.rxl_medication', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array4->where('rx_list.rxl_medication', '!=', $search_desc[$i]);
								} else {
									$query_array4->orWhere('rx_list.rxl_medication', '!=', $search_desc[$i]);
								}
							} else {
								$query_array4->where('rx_list.rxl_medication', '!=', $search_desc[$i]);
							}
						}
						$query_array4->where('rx_list.rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rx_list.rxl_date_old', '=', '0000-00-00 00:00:00');
					});
				}
				if($search_field[$i] == 'imm_immunization') {
					$query_text1->join('immunizations', 'immunizations.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array5) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array5->where('immunizations.imm_immunization', '=', $search_desc[$i]);
								} else {
									$query_array5->orWhere('immunizations.imm_immunization', '=', $search_desc[$i]);
								}		
							} else {
								$query_array5->where('immunizations.imm_immunization', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array5->where('immunizations.imm_immunization', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array5->orWhere('immunizations.imm_immunization', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array5->where('immunizations.imm_immunization', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array5->where('immunizations.imm_immunization', '!=', $search_desc[$i]);
								} else {
									$query_array5->orWhere('immunizations.imm_immunization', '!=', $search_desc[$i]);
								}
							} else {
								$query_array5->where('immunizations.imm_immunization', '!=', $search_desc[$i]);
							}
						}
					});
				}
				if($search_field[$i] == 'sup_supplement') {
					$query_text1->join('sup_list', 'sup_list.pid', '=', 'demographics.pid');
					$query_text1->where(function($query_array6) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array6->where('sup_list.sup_supplement', '=', $search_desc[$i]);
								} else {
									$query_array6->orWhere('sup_list.sup_supplement', '=', $search_desc[$i]);
								}
							} else {
								$query_array6->where('sup_list.sup_supplement', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array6->where('sup_list.sup_supplement', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array6->orWhere('sup_list.sup_supplement', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array6->where('sup_list.sup_supplement', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array6->where('sup_list.sup_supplement', '!=', $search_desc[$i]);
								} else {
									$query_array6->orWhere('sup_list.sup_supplement', '!=', $search_desc[$i]);
								}		
							} else {
								$query_array6->where('sup_list.sup_supplement', '!=', $search_desc[$i]);
							}
						}
						$query_array6->where('sup_list.sup_date_inactive', '=', '0000-00-00 00:00:00');
					});
				}
				if($search_field[$i] == 'zip') {
					$query_text1->where(function($query_array7) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array7->where('demographics.zip', '=', $search_desc[$i]);
								} else {
									$query_array7->orWhere('demographics.zip', '=', $search_desc[$i]);
								}
							} else {
								$query_array7->where('demographics.zip', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array7->where('demographics.zip', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array7->orWhere('demographics.zip', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array7->where('demographics.zip', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array7->where('demographics.zip', '!=', $search_desc[$i]);
								} else {
									$query_array7->orWhere('demographics.zip', '!=', $search_desc[$i]);
								}		
							} else {
								$query_array7->where('demographics.zip', '!=', $search_desc[$i]);
							}
						}
					});
				}
				if($search_field[$i] == 'city') {
					$query_text1->where(function($query_array8) use ($search_op, $search_desc, $search_join, $i) {
						if($search_op[$i] == 'equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array8->where('demographics.city', '=', $search_desc[$i]);
								} else {
									$query_array8->orWhere('demographics.city', '=', $search_desc[$i]);
								}
							} else {
								$query_array8->where('demographics.city', '=', $search_desc[$i]);
							}
						}
						if($search_op[$i] == 'contains') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array8->where('demographics.city', 'LIKE', "%$search_desc[$i]%");
								} else {
									$query_array8->orWhere('demographics.city', 'LIKE', "%$search_desc[$i]%");
								}
							} else {
								$query_array8->where('demographics.city', 'LIKE', "%$search_desc[$i]%");
							}
						}
						if($search_op[$i] == 'not equal') {
							if($search_join[$i] != "start") {
								if($search_join[$i] == 'AND') {
									$query_array8->where('demographics.city', '!=', $search_desc[$i]);
								} else {
									$query_array8->orWhere('demographics.city', '!=', $search_desc[$i]);
								}		
							} else {
								$query_array8->where('demographics.city', '!=', $search_desc[$i]);
							}
						}
					});
				}
			}
		}
		if($search_active_only == "Yes") {
			$query_text1->where('demographics.active', '=', '1');
		}
		if($search_no_insurance_only == "Yes") {
			$query_text1->leftJoin('insurance', 'insurance.pid', '=', 'demographics.pid')->whereNull('insurance.pid');
		}
		if($search_gender == "m" || $search_gender == "f") {
			$query_text1->where('demographics.sex', '=', $search_gender);
		}
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$sord = strtolower($sord);
		$query = $query_text1->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $query_text1
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postAgePercentage()
	{
		$practice_id = Session::get('practice_id');
		$current_date = time();
		$query1 = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->where('demographics.active', '=', '1')
			->where('demographics_relate.practice_id', '=', $practice_id)
			->get();
		$total = count($query1);
		$a = $current_date - 568024668;
		$a1 = date('Y-m-d H:i:s', $a);
		$query2 = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->where('demographics.active', '=', '1')
			->where('demographics_relate.practice_id', '=', $practice_id)
			->where('demographics.DOB', '>=', $a1)
			->get();
		$num1 = count($query2);
		$b = $current_date - 2051200190;
		$b1 = date('Y-m-d H:i:s', $b);
		$query3 = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->where('demographics.active', '=', '1')
			->where('demographics_relate.practice_id', '=', $practice_id)
			->where('demographics.DOB', '<', $a1)
			->where('demographics.DOB', '>=', $b1)
			->get();
		$num2 = count($query3);
		$query4 = DB::table('demographics')
			->join('demographics_relate', 'demographics_relate.pid', '=', 'demographics.pid')
			->where('demographics.active', '=', '1')
			->where('demographics_relate.practice_id', '=', $practice_id)
			->where('demographics.DOB', '<', $b1)
			->get();
		$num3 = count($query4);
		$result['group1'] = round($num1/$total*100) . "% of patients";
		$result['group2'] = round($num2/$total*100) . "% of patients";
		$result['group3'] = round($num3/$total*100) . "% of patients";
		echo json_encode($result);
	}
	
	public function postTagQuery($pid)
	{
		$practice_id = Session::get('practice_id');
		$query_text = DB::table('tags_relate');
		$tags = Input::get('tags_array');
		foreach ($tags[0] as $tag) {
			$query_text->where('tags_id', '=', $tag);
		}
		if ($pid != '0') {
			$query_text->where('pid', '=', $pid);
		}
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$sord = strtolower($sord);
		$query = $query_text->get();
		if($query) { 
			$count = count($query);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $query_text->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$records1 = array();
			$i = 0;
			foreach ($query1 as $row) {
				$records1[$i]['index'] = $i;
				$records1[$i]['pid'] = $row->pid;
				$row1 = Demographics::find($row->pid);
				$records1[$i]['lastname'] = $row1->lastname;
				$records1[$i]['firstname'] = $row1->firstname;
				if ($row->eid != '') {
					$row2 = Encounters::find($row->eid);
					$records1[$i]['doc_date'] = $row2->encounter_date;
					$records1[$i]['doctype'] = 'Encounter';
					$records1[$i]['doctype_index'] = 'eid';
					$records1[$i]['doc_id'] = $row->eid;
				}
				if ($row->t_messages_id != '') {
					$row3 = T_messages::find($row->t_messages_id);
					$records1[$i]['doc_date'] = $row3->t_messages_date;
					$records1[$i]['doctype'] = 'Telephone Message';
					$records1[$i]['doctype_index'] = 't_messages_id';
					$records1[$i]['doc_id'] = $row->t_messages_id;
				}
				if ($row->message_id != '') {
					$row4 = Messaging::find($row->message_id);
					$records1[$i]['doc_date'] = $row4->date;
					$records1[$i]['doctype'] = 'Message';
					$records1[$i]['doctype_index'] = 'message_id';
					$records1[$i]['doc_id'] = $row->message_id;
				}
				if ($row->documents_id != '') {
					$row5 = Documents::find($row->documents_id);
					$records1[$i]['doc_date'] = $row5->documents_date;
					$records1[$i]['doctype'] = 'Documents';
					$records1[$i]['doctype_index'] = 'documents_id';
					$records1[$i]['doc_id'] = $row->documents_id;
				}
				if ($row->hippa_id != '') {
					$row6 = Hippa::find($row->hippa_id);
					$records1[$i]['doc_date'] = $row6->hippa_date_release;
					$records1[$i]['doctype'] = 'Records Release';
					$records1[$i]['doctype_index'] = 'hippa_id';
					$records1[$i]['doc_id'] = $row->hippa_id;
				}
				if ($row->appt_id != '') {
					$row7 = Schedule::find($row->appt_id);
					$records1[$i]['doc_date'] = $row7->timestamp;
					$records1[$i]['doctype'] = 'Appointment';
					$records1[$i]['doctype_index'] = 'appt_id';
					$records1[$i]['doc_id'] = $row->appt_id;
				}
				if ($row->tests_id != '') {
					$row8 = Tests::find($row->tests_id);
					$records1[$i]['doc_date'] = $row8->test_datetime;
					$records1[$i]['doctype'] = 'Test Results';
					$records1[$i]['doctype_index'] = 'tests_id';
					$records1[$i]['doc_id'] = $row->tests_id;
				}
				if ($row->mtm_id != '') {
					$row9 = Mtm::find($row->mtm_id);
					$records1[$i]['doc_date'] = $row9->mtm_date_completed;
					$records1[$i]['doctype'] = 'Medication Therapy Management';
					$records1[$i]['doctype_index'] = 'mtm_id';
					$records1[$i]['doc_id'] = $row->mtm_id;
				}
				$i++;
			}
			$response['rows'] = $records1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function getModalView($eid, $pid)
	{
		return $this->encounters_view($eid, $pid, Session::get('practice_id'), true, false);
	}
	
	public function postTelephoneMessagesView($t_messages_id, $pid)
	{
		$row = T_messages::find($t_messages_id);
		$row1 = Demographics::find($pid);
		if ($row->t_messages_signed == 'Yes') {
			$status = "Signed";
		} else {
			$status = "Draft";
		}
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname . '<br><br><strong>Status:</strong>  ' . $status . '<br><br><strong>Date:</strong>  ' . date('m/d/Y', $this->human_to_unix($row->t_messages_dos)) . '<br><br><strong>Subject:</strong>  ' . $row->t_messages_subject . '<br><br><strong>Message:</strong> ' . nl2br($row->t_messages_message); 
		echo $text;
	}
	
	public function postMessagesView($message_id, $pid)
	{
		$row = Messaging::find($message_id);
		$row1 = Demographics::find($pid);
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname . '<br><br><strong>Date:</strong>  ' . date('m/d/Y', $this->human_to_unix($row->date)) . '<br><br><strong>Subject:</strong>  ' . $row->subject . '<br><br><strong>Message:</strong> ' . nl2br($row->body); 
		echo $text;
	}
	
	public function postApptView($appt_id, $pid)
	{
		$row = Schedule::find($appt_id);
		$row1 = Demographics::find($pid);
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname . '<br><br><strong>Start Date:</strong>  ' . date('m/d/Y h:i A', $row->start) . '<br><br><strong>End Date:</strong>  ' . date('m/d/Y h:i A', $row->end) . '<br><br><strong>Visit Type:</strong> ' . $row->visit_type . '<br><br><strong>Reason:</strong> ' . $row->reason . '<br><br><strong>Status:</strong> ' . $row->status; 
		echo $text;
	}
	
	public function postHippaView($hippa_id, $pid)
	{
		$row = Hippa::find($hippa_id);
		$row1 = Demographics::find($pid);
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname . '<br><br><strong>Date Released:</strong>  ' . date('m/d/Y', $this->human_to_unix($row->hippa_date_release)) . '<br><br><strong>Release to:</strong>  ' . $row->hippa_provider . '<br><br><strong>Reason:</strong> ' . $row->hippa_reason; 
		echo $text;
	}
	
	public function postMtmView($mtm_id, $pid)
	{
		$row = Mtm::find($mtm_id);
		$row1 = Demographics::find($pid);
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname;
		if ($row->mtm_date_completed != '') {
			$text .= '<br><br><strong>Date Completed:</strong>  ' . date('m/d/Y', $this->human_to_unix($row->mtm_date_completed));
		}
		$text .= '<br><br><strong>Description:</strong>  ' . nl2br($row->mtm_description) . '<br><br><strong>Recommendations:</strong>  ' . nl2br($row->mtm_recommendations) . '<br><br><strong>Beneficiary Notes:</strong>  ' . nl2br($row->mtm_beneficiary_notes) . '<br><br><strong>Action:</strong>  ' . nl2br($row->mtm_action) . '<br><br><strong>Outcomes:</strong>  ' . nl2br($row->mtm_outcomes) . '<br><br><strong>Related Conditions:</strong>  ' . nl2br($row->mtm_related_conditions);
		echo $text;
	}
	
	public function postTestsView($tests_id, $pid)
	{
		$row = Tests::find($tests_id);
		$row1 = Demographics::find($pid);
		$text = '<strong>Patient:</strong>  ' . $row1->firstname . " " . $row1->lastname . '<br><br><strong>Type:</strong>  ' . $row->test_type . '<br><br><strong>Date:</strong>  ' . date('m/d/Y', $row->test_datetime) . '<br><br><strong>Test</strong>  ' . $row->test_name . '<br><br><strong>Result:</strong> ' . $row->test_result . ' ' . $row->test_units . ' (' . $row->test_reference . ')<br><br><strong>From:</strong> ' . $row->test_from; 
		echo $text;
	}
	
	public function postDocumentsView($id, $pid)
	{
		$result = Documents::find($id);
		$file_path = $result->documents_url;
		$data1 = array(
			'documents_viewed' => Session::get('displayname')
		);
		DB::table('documents')->where('documents_id', '=', $id)->update($data);
		$this->audit('Update');
		$name = time() . '_' . $pid . '.pdf';
		$data['filepath'] = '/var/www/nosh/temp' . $name;
		copy($file_path, $data['filepath']);
		while(!file_exists($data['filepath'])) {
			sleep(2);
		}
		$data['html'] = '<iframe src="' . asset('temp/' . $name) . '" width="770" height="425" style="border: none;"></iframe>';
		$data['id'] = $id;
		echo json_encode($data);
	}
	
	public function postCloseDocument()
	{
		unlink(Input::get('document_filepath'));
		echo 'OK';
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
		$file_path = "/var/www/nosh/temp/" . time() . "_demographics.txt";
		echo $csv;
	}
	
	public function postHedisAudit($type)
	{
		if ($type == 'spec') {
			$type = Input::get('time');
		}
		$html = '';
		$demographics = DB::table('demographics_relate')->where('practice_id', '=', Session::get('practice_id'))->get();
		if ($demographics) {
			$html .= '<strong>HEDIS Audit:</strong><br>';
			$html .= '<table id="hedis_grid" class="pure-table pure-table-horizontal">';
			$html .= '<thead><tr><th>Measure</th><th>Description</th><th>Result</th><th style="width:250px">Rectify</th></tr></thead><tbody>';
			$arr = array();
			$total_count = 0;
			foreach ($demographics as $demographic) {
				$arr[$demographic->pid] = $this->hedis_audit($type, 'office', $demographic->pid);
				$total_count++;
			}
			$measures = array('aba','wcc','cis','ima','hpv','lsc','bcs','ccs','col','chl','gso','cwp','uri','aab','spr','pce','asm','amr','cmc','pbh','cbp','cdc','art','omw','lbp','amm','add');
			$counter = array();
			foreach ($measures as $measure) {
				$counter[$measure]['count'] = 0;
				$counter[$measure]['rectify'] = '';
				if ($measure != 'cwp' && $measure != 'uri' && $measure != 'aab' && $measure != 'pce' && $measure != 'lbp') {
					$counter[$measure]['goal'] = 0;
				} else {
					if ($measure == 'cwp') {
						$counter[$measure]['test'] = 0;
						$counter[$measure]['abx'] = 0;
						$counter[$measure]['abx_no_test'] = 0;
					}
					if ($measure == 'uri' || $measure == 'aab') {
						$counter[$measure]['abx'] = 0;
					}
					if ($measure == 'pce') {
						$counter[$measure]['tx'] = 0;
					}
					if ($measure == 'lbp') {
						$counter[$measure]['no_rad'] = 0;
					}
				}
			}
			foreach ($arr as $pid => $audit) {
				$patient = DB::table('demographics')->where('pid', '=', $pid)->first();
				$dob = date('m/d/Y', strtotime($patient->DOB));
				$name = $patient->lastname . ', ' . $patient->firstname . ' (DOB: ' . $dob . ') (ID: ' . $patient->pid . ')';
				$rectify = '<a href="#" id="hedis_' . $pid . '" class="hedis_patient">' . $name . '</a><br>';
				foreach ($audit as $item => $row) {
					$counter[$item]['count']++;
					if ($item != 'cwp' && $item != 'uri' && $item != 'aab' && $item != 'pce' && $item != 'lbp') {
						if($row['goal'] == 'y') {
							$counter[$item]['goal']++;
						} else {
							$counter[$item]['rectify'] .= $rectify;
						}
					} else {
						if ($item == 'cwp') {
							$counter[$item]['test'] += $row['test'];
							$counter[$item]['abx'] += $row['abx'];
							$counter[$item]['abx_no_test'] += $row['abx_no_test'];
						}
						if ($item == 'uri' || $item == 'aab') {
							$counter[$item]['abx'] += $row['abx'];
						}
						if ($item == 'pce') {
							$counter[$item]['tx'] += $row['tx'];
						}
						if ($item == 'lbp') {
							$counter[$item]['no_rad'] += $row['no_rad'];
						}
					}
				}
			}
			foreach ($measures as $measure1) {
				if ($measure1 != 'cwp' && $measure1 != 'uri' && $measure1 != 'aab' && $measure1 != 'pce' && $measure1 != 'lbp') {
					if ($counter[$measure1]['count'] != 0) {
						$counter[$measure1]['percent_goal'] = round($counter[$measure1]['goal']/$counter[$measure1]['count']*100);
					} else {
						$counter[$measure1]['percent_goal'] = 0;
					}
				} else {
					if ($measure1 == 'cwp') {
						if ($counter[$measure1]['count'] != 0) {
							$counter[$measure1]['percent_test'] = round($counter[$measure1]['test']/$counter[$measure1]['count']*100);
							$counter[$measure1]['percent_abx'] = round($counter[$measure1]['abx']/$counter[$measure1]['count']*100);
							$counter[$measure1]['percent_abx_no_test'] = round($counter[$measure1]['abx_no_test']/$counter[$measure1]['count']*100);
						} else {
							$counter[$measure1]['percent_test'] = 0;
							$counter[$measure1]['percent_abx'] = 0;
							$counter[$measure1]['percent_abx_no_test'] = 0;
						}
					}
					if ($measure1 == 'uri' || $measure1 == 'aab') {
						if ($counter[$measure1]['count'] != 0) {
							$counter[$measure1]['percent_abx'] = round($counter[$measure1]['abx']/$counter[$measure1]['count']*100);
						} else {
							$counter[$measure1]['percent_abx'] = 0;
						}
					}
					if ($measure1 == 'pce') {
						if ($counter[$measure1]['count'] != 0) {
							$counter[$measure1]['percent_tx'] = round($counter[$measure1]['tx']/$counter[$measure1]['count']*100);
						} else {
							$counter[$measure1]['percent_tx'] = 0;
						}
					}
					if ($measure1 == 'lbp') {
						if ($counter[$measure1]['count'] != 0) {
							$counter[$measure1]['percent_no_rad'] = round($counter[$measure1]['no_rad']/$counter[$measure1]['count']*100);
						} else {
							$counter[$measure1]['percent_no_rad'] = 0;
						}
					}
				}
			}
			// ABA
			$html .= '<tr><td>Adult BMI Assessment</td><td>Percentage of members 18-74 who had their BMI and weight documented at an outpatient visit</td><td>' . $counter['aba']['percent_goal'] .'%</td><td>' . $counter['aba']['rectify'] .'</td></tr>';
			// WCC
			$html .= '<tr><td>Weight Assessment and Counseling for Nutrition and Physical Activity for Children and Adolescents</td><td>Percentage of members 3-17 who had an outpatient visit with a PCP or OB/GYN which included evidence of BMI documentation with corresponding height&weight, counseling for nutrition and/or counseling for physical activity</td><td>' . $counter['wcc']['percent_goal'] .'%</td><td>' . $counter['wcc']['rectify'] .'</td></tr>';
			// CIS
			$html .= '<tr><td>Childhood Immunization Status</td><td>Percentage of children two years of age with appropriate childhood immunizations</td><td>' . $counter['cis']['percent_goal'] .'%</td><td>' . $counter['cis']['rectify'] .'</td></tr>';
			// IMA
			$html .= '<tr><td>Immunizations for Adolescents</td><td>Percentage of adolescents 13 years of age with appropriate immunizations</td><td>' . $counter['ima']['percent_goal'] .'%</td><td>' . $counter['ima']['rectify'] .'</td></tr>';
			// HPV
			$html .= '<tr><td>Human Papillomavirus Vaccine for Female Adolescents</td><td>Percentage of female adolescents 13 years of age who had three doses of HPV vaccine between 9th and 13th birthdays</td><td>' . $counter['hpv']['percent_goal'] .'%</td><td>' . $counter['hpv']['rectify'] .'</td></tr>';
			// LSC
			$html .= '<tr><td>Lead Screening in Children</td><td>Percentage of children 2 years of age screened for lead poisoning</td><td>' . $counter['lsc']['percent_goal'] .'%</td><td>' . $counter['lsc']['rectify'] .'</td></tr>';
			// BCS
			$html .= '<tr><td>Breast Cancer Screening</td><td>Percentage of women 40-69 years of age who had a mammogram</td><td>' . $counter['bcs']['percent_goal'] .'%</td><td>' . $counter['bcs']['rectify'] .'</td></tr>';
			// CCS
			$html .= '<tr><td>Cervical Cancer Screening</td><td>Percentage of women 21-64 years of age who had a Pap test</td><td>' . $counter['ccs']['percent_goal'] .'%</td><td>' . $counter['ccs']['rectify'] .'</td></tr>';
			// COL
			$html .= '<tr><td>Colorectal Cancer Screening</td><td>Percentage of members 50-75 years of age who had appropriate screening for colorectal cancer</td><td>' . $counter['col']['percent_goal'] .'%</td><td>' . $counter['col']['rectify'] .'</td></tr>';
			// CHL
			$html .= '<tr><td>Chlamydia Screening in Women</td><td>Sexually active women 16-24 with annual chlamydia screening</td><td>' . $counter['chl']['percent_goal'] .'%</td><td>' . $counter['chl']['rectify'] .'</td></tr>';
			// GSO
			$html .= '<tr><td>Glaucoma Screening Older Adults</td><td>Sexually active women 1Percentage of members 65 or older who received a glaucoma eye exam (no prior history)</td><td>' . $counter['gso']['percent_goal'] .'%</td><td>' . $counter['gso']['rectify'] .'</td></tr>';
			// CWP
			$html .= '<tr><td>Appropriate Testing for Children With Pharyngitis</td><td>Percentage of children ages 2-18 diagnosed with pharyngitis, prescribed an antibiotic and tested for strep</td><td>';
			$html .= '<ul><li>Percentage tested: ' . $counter['cwp']['percent_test'] . '%</li>';
			$html .= '<li>Percentage treated with antibiotics: ' . $counter['cwp']['percent_abx'] . '%</li>';
			$html .= '<li>Percentage treated with antibiotics without testing: ' . $counter['cwp']['percent_abx_no_test'] . '%</li></ul>';
			$html .= '</td><td>' . $counter['cwp']['rectify'] .'</td></tr>';
			// URI
			$html .= '<tr><td>Appropriate Treatment for Children With Upper Respiratory Infection</td><td>Percentage of children 3 months-18 years diagnosed with ONLY upper respiratory infection diagnosis and NOT dispensed an antibiotic</td><td>';
			$html .= '<ul><li>Percentage treated with antibiotics: ' . $counter['uri']['percent_abx'] . '%</li></ul>';
			$html .= '</td><td>' . $counter['uri']['rectify'] .'</td></tr>';
			// AAB
			$html .= '<tr><td>Avoidance of Antibiotic Treatment for Adults with Acute Bronchitis</td><td>Percentage of adults 18-64 years diagnosed with acute bronchitis who were NOT dispensed an antibiotic</td><td>';
			$html .= '<ul><li>Percentage treated with antibiotics: ' . $counter['aab']['percent_abx'] . '%</li></ul>';
			$html .= '</td><td>' . $counter['aab']['rectify'] .'</td></tr>';
			// SPR
			$html .= '<tr><td>Use of Spirometry Testing in the Assessment and Diagnosis of COPD</td><td>Percentage of members age 40 and older w/ COPD and spirometry testing</td><td>' . $counter['spr']['percent_goal'] .'%</td><td>' . $counter['spr']['rectify'] .'</td></tr>';
			// PCE
			$html .= '<tr><td>Pharmacotherapy Management of COPD Exacerbation</td><td>Members dispensed systemic corticosteroid & bronchodilator after COPD exacerbation</td><td>';
			$html .= '<ul><li>Percentage treated for COPD exacerbations: ' . $counter['pce']['percent_tx'] . '%</li></ul>';
			$html .= '</td><td>' . $counter['pce']['rectify'] .'</td></tr>';
			// ASM and AMR
			$html .= '<tr><td>Use of Appropriate Medications for People with Asthma</td><td>Percentage of members 5-56 years with asthma and appropriately prescribed medications</td><td>' . $counter['asm']['percent_goal'] .'%</td><td>' . $counter['asm']['rectify'] .'</td></tr>';
			$html .= '<tr><td>Asthma Medication Ratio</td><td>Percentage of members 5-64 years with asthma who had a ratio of controller medications to total asthma medications of .5 or greater</td><td>' . $counter['amr']['percent_goal'] .'%</td><td>' . $counter['amr']['rectify'] .'</td></tr>';
			// CMC and PBH
			$html .= '<tr><td>Cholesterol Management for Patients With Cardiovascular Conditions</td><td>Percentage of members 18-75 who were discharged alive for acute myocardial infarction, coronary artery bypass graft or percutaneous coronary interventions, or who had a diagnosis of ischemic vascular diasease who had LDL-C screenings</td><td>' . $counter['cmc']['percent_goal'] .'%</td><td>' . $counter['cmc']['rectify'] .'</td></tr>';
			$html .= '<tr><td>Persistence of Beta-Blocker Treatment After a Heart Attack</td><td>Percentage of members 18 years or older, discharged with a diagnosis of acute myocardial infarction and received a beta-blocker treatment for 6 months</td><td>' . $counter['pbh']['percent_goal'] .'%</td><td>' . $counter['pbh']['rectify'] .'</td></tr>';
			// CBP
			$html .= '<tr><td>Controlling High Blood Pressure</td><td>Percentage of members 18-85 with a diagnosis of hypertension and whose blood pressure was controlled</td><td>' . $counter['cbp']['percent_goal'] .'%</td><td>' . $counter['cbp']['rectify'] .'</td></tr>';
			// CDC
			$html .= '<tr><td>Comprehensive Diabetes Care</td><td>The percentage of members 18-75 years of age with diabetes (type 1 or type 2) who had each of the following: 1) HbA1c, 2) LDL Screening, 3) Nephropathy Screening, 4) Retinal Eye Exam, 5) Blood Pressure control.</td><td>' . $counter['cdc']['percent_goal'] .'%</td><td>' . $counter['cdc']['rectify'] .'</td></tr>';
			// ART
			$html .= '<tr><td>Disease Modifying Anti-Rheumatic Drug Therapy for Rheumatoid Arthritis</td><td>Percentage of members w/ RA dispensed a DMARD</td><td>' . $counter['art']['percent_goal'] .'%</td><td>' . $counter['art']['rectify'] .'</td></tr>';
			// OMW
			$html .= '<tr><td>Osteoporosis Management in Women Who Had Fracture</td><td>Percentage of women 67 years or older who suffered a fracture and then a DEXA scan or osteoporosis medication within 6 months of incident</td><td>' . $counter['omw']['percent_goal'] .'%</td><td>' . $counter['omw']['rectify'] .'</td></tr>';
			// LBP
			$html .= '<tr><td>Osteoporosis Management in Women Who Had Fracture</td><td>Percentage of members with a primary diagnosis of low back pain who did not have an imaging study within 28 days of diagnosis</td><td>';
			$html .= '<ul><li>Percentage of instances where no imaging study was performed for a diagnosis of low back pain: ' . $counter['lbp']['percent_no_rad'] . '%</li></ul>';
			$html .= '</td><td>' . $counter['lbp']['rectify'] .'</td></tr>';
			// AMM
			$html .= '<tr><td>Antidepressant Medication Management</td><td>Percentage of members 18 years or older diagnosed with depression and treated with antidepressant meds</td><td>' . $counter['amm']['percent_goal'] .'%</td><td>' . $counter['amm']['rectify'] .'</td></tr>';
			// ADD
			$html .= '<tr><td>Follow-Up Care for Children Prescribed ADHD Medication</td><td>Percentage of children 6-12 with newly diagnosed ADHD who received the appropriate follow-up treatment and medication</td><td>' . $counter['add']['percent_goal'] .'%</td><td>' . $counter['add']['rectify'] .'</td></tr>';
			$html .= '</tbody></table>';
		}
		echo $html;
	}
}
