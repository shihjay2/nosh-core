<?php

class AjaxFinancialController extends BaseController {

	/**
	* NOSH ChartingSystem Financial Ajax Functions
	*/
	
	public function postSubmitList()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')
			->join('demographics', 'encounters.pid', '=', 'demographics.pid')
			->where('encounters.bill_submitted', '!=', 'Done')
			->where('encounters.addendum', '=', 'n')
			->where('encounters.practice_id', '=', $practice_id)
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
		$query1 = DB::table('encounters')
			->join('demographics', 'encounters.pid', '=', 'demographics.pid')
			->where('encounters.bill_submitted', '!=', 'Done')
			->where('encounters.addendum', '=', 'n')
			->where('encounters.practice_id', '=', $practice_id)
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
	
	public function postBillsDone()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')
			->join('demographics', 'encounters.pid', '=', 'demographics.pid')
			->where('encounters.bill_submitted', '=', 'Done')
			->where('encounters.addendum', '=', 'n')
			->where('encounters.practice_id', '=', $practice_id)
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
		$query1 = DB::table('encounters')
			->join('demographics', 'encounters.pid', '=', 'demographics.pid')
			->where('encounters.bill_submitted', '=', 'Done')
			->where('encounters.addendum', '=', 'n')
			->where('encounters.practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row) {
				$query2 = DB::table('billing_core')->where('eid', '=', $row->eid)->get();
				if ($query2) {
					$charge = 0;
					$payment = 0;
					foreach ($query2 as $row1) {
						$charge += $row1->cpt_charge * $row1->unit;
						$payment += $row1->payment;
					}
					$row->balance = $charge - $payment;
					$row->charges = $charge;
				} else {
					$row->balance = 0;
					$row->charges = 0;
				}
				$response['rows'][$i]['id']=$row->eid; 
				$response['rows'][$i]['cell']=array($row->eid,$row->encounter_DOS,$row->lastname,$row->firstname,$row->encounter_cc,$row->charges,$row->balance);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postOutstandingBalance()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('demographics')
			->join('demographics_relate', 'demographics.pid', '=', 'demographics_relate.pid')
			->where('demographics_relate.practice_id', '=', $practice_id)
			->get();
		$count = 0;
		$full_array = array();
		foreach ($query as $row) {
			$pid = $row->pid;
			$notes = DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->first();
			$query_a = DB::table('encounters')->where('pid', '=', $pid)->where('addendum', '=', 'n')->get();
			$g = 0;
			if ($query_a > 0) {
				$balance = 0;
				foreach ($query_a as $row_a) {
					$query_b = DB::table('billing_core')->where('eid', '=', $row_a->eid)->get();
					if ($query_b) {
						$charge = 0;
						$payment = 0;
						foreach ($query_b as $row_b) {
							$charge += $row_b->cpt_charge * $row_b->unit;
							$payment += $row_b->payment;
						}
						$balance += $charge - $payment;
					} else {
						$balance += 0;
					}
					$g++; 
				}
			} else {
				$balance = 0;
			}
			$query_c = DB::table('billing_core')->where('pid', '=', $pid)->where('eid', '=', '0')->where('payment', '=', '0')->get();
			$h = 0;
			if ($query_c) {
				$balance1 = 0;
				foreach ($query_c as $row_c) {
					$query_d = DB::table('billing_core')->where('other_billing_id', '=', $row_c->other_billing_id)->get();
					if ($query_d) {
						$charge1 = $row_c->cpt_charge * $row_c->unit;
						$payment1 = 0;
						foreach ($query_d as $row_d) {
							$payment1 += $row_d->payment;
						}
						$balance1 += $charge1 - $payment1;
					} else {
						$balance1 += 0;
					}
					$h++; 
				}
			} else {
				$balance1 = 0;
			}
			$totalbalance = $balance + $balance1;
			if ($totalbalance >= 0.01 || $notes->billing_notes != '') {
				$count++;
				$full_array[] = array(
					'pid' => $row->pid,
					'lastname' => $row->lastname,
					'firstname' => $row->firstname,
					'balance' => $totalbalance,
					'billing_notes' => $notes->billing_notes
				);
			}
		}
		if($count > 0) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		if (count($full_array) > 0) {
			foreach ($full_array as $key => $value) {
				$index[$key]  = $value[$sidx];
			}
			if ($sord == 'desc') {
				array_multisort($index, SORT_DESC, $full_array);
			} else {
				array_multisort($index, SORT_ASC, $full_array);
			}
			$records = array_slice($full_array, $start , $limit);
		} else {
			$records = $full_array;
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$response['rows'] = $records;
		echo json_encode($response);
	}
	
	public function postBillingSet()
	{
		if (Session::get('financial') != FALSE) {
			Session::forget('financial');
		}
		Session::put('financial', 'y');
		$data['message'] = 'OK';
		$data['url'] = route('chart');
		echo json_encode($data);
	}
	
	public function postAddQueue($type)
	{
		$data = array(
			'bill_submitted' => $type
		);
		DB::table('encounters')->where('eid', '=', Input::get('eid'))->update($data);
		$this->audit('Update');
		if ($type == 'Pend') {
			echo "Billed encounter added to the print image queue!";
		} else {
			echo "Billed encounter added to the print HCFA-1500 queue!";
		}
	}
	
	public function postCheckBatch($type, $flatten)
	{
		$query = DB::table('encounters')
			->where('bill_submitted', '=', $type)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->get();
		if ($query) {
			$arr['response'] = 'OK';
			$arr['filename'] = date('Ymd', time());
			if ($type == 'Pend') {
				$printimage = '';
				foreach ($query as $row) {
					$printimage .= $this->printimage($row->eid);
				}
				$arr['type'] = 'batchprintimage';
				$filename = __DIR__.'/../../public/temp/' . $arr['filename'] . "_" . $arr['type'] . ".txt";
				File::put($filename, $printimage);
			} else {
				$entire = '';
				foreach ($query as $row) {
					if ($entire === '') {
						$entire .= $this->hcfa($row->eid, $flatten);
					} else {
						$entire .= ' ' . $this->hcfa($row->eid, $flatten);
					}
				}
				$arr['type'] = "batchhcfa1500";
				$filename = __DIR__.'/../../public/temp/' . $arr['filename'] . "_" . $arr['type'] . ".pdf";
				$commandpdf2 = "pdftk " . $entire . " cat output " . $filename;
				$commandpdf3 = escapeshellcmd($commandpdf2);
				exec($commandpdf3);
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$files = explode(" ", $entire);
				foreach ($files as $row1) {
					unlink($row1);
				}
			}
		} else {
			$arr['response'] = 'None';
		}
		echo json_encode($arr);
	}
	
	public function postBillResubmit()
	{
		$eid = Input::get('eid');
		$row = DB::table('billing')->where('eid', '=', $eid)->first();
		$arr = "No bill for this encounter!";
		if ($row) {
			if ($row->insurance_id_1 == '0' || $row->insurance_id_1 == '') {
				$arr = "No insurance was assigned.  Cannot be resubmitted.";
			} else {
				$data = array(
					'bill_submitted' => 'No'
				);
				DB::table('encounters')->where('eid', '=', $eid)->update($data);
				$this->audit('Update');
				$arr = "Billed changed to unsent status!";
			}
		}
		echo $arr;
	}
	
	public function postMonthlyStats()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')
			->select(DB::raw("DATE_FORMAT(encounter_DOS, '%Y-%m') as month, COUNT(*) as patients_seen"))
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->groupBy('month')
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
		$query1 = DB::table('encounters')
			->select(DB::raw("DATE_FORMAT(encounter_DOS, '%Y-%m') as month, COUNT(*) as patients_seen"))
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->groupBy('month')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row_obj) {
				$row = (array) $row_obj;
				$month_piece = explode("-", $row['month']);
				$year = $month_piece[0];
				$month = $month_piece[1];
				$row['total_billed'] = 0;
				$row['total_payments'] = 0;
				$row['dnka'] = 0;
				$row['lmc'] = 0;
				$query1a = DB::table('encounters')
					->select('eid')
					->where(DB::raw('YEAR(encounter_DOS)'), '=', $year)
					->where(DB::raw('MONTH(encounter_DOS)'), '=', $month)
					->where('addendum', '=', 'n')
					->where('practice_id', '=', $practice_id)
					->get();
				foreach ($query1a as $row1) {
					$query2 = DB::table('billing_core')->where('eid', '=', $row1->eid)->get();
					if ($query2) {
						$charge = 0;
						$payment = 0;
						foreach ($query2 as $row2) {
							if ($row2->payment_type != "Write-Off") {
								$charge += $row2->cpt_charge * $row2->unit;
								$payment += $row2->payment;
							}	
						}
						$row['total_billed'] += $charge;
						$row['total_payments'] += $payment;
					}
				}
				$query1b = DB::table('schedule')
					->join('providers', 'providers.id', '=', 'schedule.provider_id')
					->where(DB::raw("FROM_UNIXTIME(schedule.end, '%Y')"), '=', $year)
					->where(DB::raw("FROM_UNIXTIME(schedule.end, '%m')"), '=', $month)
					->where('providers.practice_id', '=', $practice_id)
					->get();
				foreach ($query1b as $row3) {
					if ($row3->status == "DNKA") { 
						$row['dnka'] += 1;
					}
					if ($row3->status == "LMC") {
						$row['lmc'] += 1;
					}
				}
				$response['rows'][$i]['id']=$row['month']; 
				$response['rows'][$i]['cell']=array($row['month'],$row['patients_seen'],$row['total_billed'],$row['total_payments'],$row['dnka'],$row['lmc']);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postMonthlyStatsInsurance($id)
	{
		$practice_id = Session::get('practice_id');
		$month_piece = explode("-", $id);
		$year = $month_piece[0];
		$month = $month_piece[1];
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table(DB::raw('billing as t1'))
			->leftJoin(DB::raw('insurance as t2'), 't1.insurance_id_1', '=', 't2.insurance_id')
			->leftJoin(DB::raw('encounters as t3'), 't1.eid', '=', 't3.eid')
			->select(DB::raw("t2.insurance_plan_name as insuranceplan, COUNT(*) as ins_patients_seen"))
			->where(DB::raw("YEAR(t3.encounter_DOS)"), '=', $year)
			->where(DB::raw("MONTH(t3.encounter_DOS)"), '=', $month)
			->where('t3.addendum', '=', 'n')
			->where('t3.practice_id', '=', $practice_id)
			->groupBy('insuranceplan')
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
		$query1 = DB::table(DB::raw('billing as t1'))
			->leftJoin(DB::raw('insurance as t2'), 't1.insurance_id_1', '=', 't2.insurance_id')
			->leftJoin(DB::raw('encounters as t3'), 't1.eid', '=', 't3.eid')
			->select(DB::raw("t2.insurance_plan_name as insuranceplan, COUNT(*) as ins_patients_seen"))
			->where(DB::raw("YEAR(t3.encounter_DOS)"), '=', $year)
			->where(DB::raw("MONTH(t3.encounter_DOS)"), '=', $month)
			->where('t3.addendum', '=', 'n')
			->where('t3.practice_id', '=', $practice_id)
			->groupBy('insuranceplan')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get(); 
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row) {
				if (is_null($row->insuranceplan)) {
					$row->insuranceplan = 'Cash Only';
				}
				$response['rows'][$i]['id']=$row->insuranceplan; 
				$response['rows'][$i]['cell']=array($row->insuranceplan,$row->ins_patients_seen);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postYearlyStats()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')
			->select(DB::raw("DATE_FORMAT(encounter_DOS, '%Y') as year, COUNT(*) as patients_seen"))
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->groupBy('year')
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
		$query1 = DB::table('encounters')
			->select(DB::raw("DATE_FORMAT(encounter_DOS, '%Y') as year, COUNT(*) as patients_seen"))
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->groupBy('year')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row_obj) {
				$row = (array) $row_obj;
				$year = $row['year'];
				$row['total_billed'] = 0;
				$row['total_payments'] = 0;
				$row['dnka'] = 0;
				$row['lmc'] = 0;
				$query1a = DB::table('encounters')
					->select('eid')
					->where(DB::raw('YEAR(encounter_DOS)'), '=', $year)
					->where('addendum', '=', 'n')
					->where('practice_id', '=', $practice_id)
					->get();
				foreach ($query1a as $row1) {
					$query2 = DB::table('billing_core')->where('eid', '=', $row1->eid)->get();
					if ($query2) {
						$charge = 0;
						$payment = 0;
						foreach ($query2 as $row2) {
							if ($row2->payment_type != "Write-Off") {
								$charge += $row2->cpt_charge * $row2->unit;
								$payment += $row2->payment;
							}
						}
						$row['total_billed'] += $charge;
						$row['total_payments'] += $payment;
					}
				}
				$query1b = DB::table('schedule')
					->join('providers', 'providers.id', '=', 'schedule.provider_id')
					->where(DB::raw("FROM_UNIXTIME(schedule.end, '%Y')"), '=', $year)
					->where('providers.practice_id', '=', $practice_id)
					->get();
				foreach ($query1b as $row3) {
					if ($row3->status == "DNKA") { 
						$row['dnka'] += 1;
					}
					if ($row3->status == "LMC") {
						$row['lmc'] += 1;
					}
				}
				$response['rows'][$i]['id']=$row['year']; 
				$response['rows'][$i]['cell']=array($row['year'],$row['patients_seen'],$row['total_billed'],$row['total_payments'],$row['dnka'],$row['lmc']);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postYearlyStatsInsurance($id)
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table(DB::raw('billing as t1'))
			->leftJoin(DB::raw('insurance as t2'), 't1.insurance_id_1', '=', 't2.insurance_id')
			->leftJoin(DB::raw('encounters as t3'), 't1.eid', '=', 't3.eid')
			->select(DB::raw("t2.insurance_plan_name as insuranceplan, COUNT(*) as ins_patients_seen"))
			->where(DB::raw("YEAR(t3.encounter_DOS)"), '=', $year)
			->where('t3.addendum', '=', 'n')
			->where('t3.practice_id', '=', $practice_id)
			->groupBy('insuranceplan')
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
		$query1 = DB::table(DB::raw('billing as t1'))
			->leftJoin(DB::raw('insurance as t2'), 't1.insurance_id_1', '=', 't2.insurance_id')
			->leftJoin(DB::raw('encounters as t3'), 't1.eid', '=', 't3.eid')
			->select(DB::raw("t2.insurance_plan_name as insuranceplan, COUNT(*) as ins_patients_seen"))
			->where(DB::raw("YEAR(t3.encounter_DOS)"), '=', $year)
			->where('t3.addendum', '=', 'n')
			->where('t3.practice_id', '=', $practice_id)
			->groupBy('insuranceplan')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get(); 
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row) {
				if (is_null($row->insuranceplan)) {
					$row->insuranceplan = 'Cash Only';
				}
				$response['rows'][$i]['id']=$row['insuranceplan']; 
				$response['rows'][$i]['cell']=array($row->insuranceplan,$row->ins_patients_seen);
				$i++; 
			}
		}
		echo json_encode($response);
	}
	
	public function postQueryPaymentTypeList()
	{
		$query = DB::table('billing_core')
			->where('practice_id', '=', Session::get('practice_id'))
			->whereNotNull('payment_type')
			->select('payment_type')
			->distinct()
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$key = $row->payment_type;
				$data[$key] = $key;
			}
		}
		echo json_encode($data);
	}
	
	public function postQueryCptList()
	{
		$query = DB::table('billing_core')
			->where('practice_id', '=', Session::get('practice_id'))
			->select('cpt')
			->distinct()
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$key = $row->cpt;
				$data[$key] = $key;
			}
		}
		echo json_encode($data);
	}
	
	public function postQueryYearList()
	{
		$query = DB::table('billing_core')
			->where('practice_id', '=', Session::get('practice_id'))
			->select('dos_f')
			->distinct()
			->get();
		$data = array();
		if ($query) {
			foreach ($query as $row) {
				$date_array = explode("/", $row->dos_f);
				if (isset($date_array[2])) {
					if (array_search($date_array[2], $data) === FALSE) {
						$key = $date_array[2];
						$data[$key] = $key;
					}
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postFinancialQuery()
	{
		$practice_id = Session::get('practice_id');
		$query_text1 = DB::table('billing_core')->where('practice_id', '=', $practice_id);
		$variables_array = Input::get('variables');
		$type = Input::get('type');
		$i = 0;
		foreach ($variables_array[0] as $variable) {
			if ($i == 0) {
				$query_text1->where($type, '=', $variable);
			} else {
				$query_text1->orWhere($type, '=', $variable);
			}
			$i++;
		}
		$year_array = Input::get('year');
		$query_text1->where(function($query_array1) use ($year_array) {
			$j = 0;
			foreach ($year_array[0] as $year) {
				if ($j == 0) {
					$query_array1->where('dos_f', 'LIKE', "%$year%");
				} else {
					$query_array1->orWhere('dos_f', 'LIKE', "%$year%");
				}
				$j++;
			}
		});
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
		$query_text2 = $query_text1->orderBy($sidx, $sord)->skip($start)->take($limit);
		$query1 = $query_text2->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$records1 = array();
		$k = 0;
		if ($query1) {
			foreach ($query1 as $records_row) {
				$query2_row = Demographics::find($records_row->pid);
				if ($type == 'payment_type') {
					$type1 = $records_row->payment_type;
					$amount = $records_row->payment;
				} else {
					$type1 = "CPT code: " . $records_row->cpt;
					$amount = $records_row->cpt_charge;
				}
				$records1[$k] = array(
					'billing_core_id' => $records_row->billing_core_id,
					'dos_f' => $records_row->dos_f,
					'lastname' => $query2_row->lastname,
					'firstname' => $query2_row->firstname,
					'amount' => $amount,
					'type' => $type1
				);
				$k++;
			}
			$response['rows'] = $records1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postFinancialQueryPrint()
	{
		$practice_id = Session::get('practice_id');
		$query_text1 = DB::table('billing_core')->where('practice_id', '=', $practice_id);
		$variables_array = Input::get('variables');
		$type = Input::get('type');
		$i = 0;
		foreach ($variables_array[0] as $variable) {
			if ($i == 0) {
				$query_text1->where($type, '=', $variable);
			} else {
				$query_text1->orWhere($type, '=', $variable);
			}
			$i++;
		}
		$year_array = Input::get('year');
		$query_text1->where(function($query_array1) use ($year_array) {
			$j = 0;
			foreach ($year_array[0] as $year) {
				if ($j == 0) {
					$query_array1->where('dos_f', 'LIKE', "%$year%");
				} else {
					$query_array1->orWhere('dos_f', 'LIKE', "%$year%");
				}
				$j++;
			}
		});
		$query_text1->orderBy('dos_f', 'desc');
		$query = $query_text1->get();
		if ($query) {
			$records1 = array();
			$k = 0;
			foreach ($query as $records_row) {
				$query2_row = Demographics::find($records_row->pid);
				if ($type == 'payment_type') {
					$type1 = $records_row->payment_type;
					$amount = $records_row->payment;
				} else {
					$type1 = "CPT code: " . $records_row->cpt;
					$amount = $records_row->cpt_charge;
				}
				$records1[$k] = array(
					'billing_core_id' => $records_row->billing_core_id,
					'dos_f' => $records_row->dos_f,
					'lastname' => $query2_row->lastname,
					'firstname' => $query2_row->firstname,
					'amount' => $amount,
					'type' => $type1
				);
				$k++;
			}
			$response['id_doc'] = time() . "_" . Session::get('user_id');
			$file_path = __DIR__."/../../public/temp/financial_query_" . $response['id_doc'] . ".pdf";
			$html = $this->page_intro('Financial Query Results', Session::get('practice_id'))->render();
			$html .= $this->page_financial_results($records1);
			$this->generate_pdf($html, $file_path);
			$response['message'] = "OK";
		} else {
			$response['message'] = "No result.";
		}
		echo json_encode($response);
	}
	
	public function postResetSession()
	{
		if (Session::get('financial') != FALSE) {
			Session::forget('financial');
		}
	}
}
