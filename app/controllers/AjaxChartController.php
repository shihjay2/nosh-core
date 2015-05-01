<?php

class AjaxChartController extends BaseController {

	/**
	* NOSH ChartingSystem Chart Ajax Functions
	*/
	
	public function postDemographicsLoad()
	{
		$data['id'] = Session::get('pid');
		$data['ptname'] = Session::get('ptname');
		$data['agealldays'] = Session::get('agealldays');
		$data['age'] = Session::get('age');
		$pid = Session::get('pid');
		$row = Demographics::find($pid);
		$dob = $this->human_to_unix($row->DOB);
		$data['dob'] = date('F jS, Y', $dob);
		if ($row->sex == 'm') {
			$data['gender'] = 'Male';
		} 
		if ($row->sex == 'f') {
			$data['gender'] = 'Female';
		}
		if ($row->sex == 'u') {
			$data['gender'] = 'Undifferentiated';
		}
		$data['nickname'] = $row->nickname;
		if ($row->address == '') {
			$data['new'] = 'Y';
		} else {
			$data['new'] = 'N';
		}
		echo json_encode($data);
	}
	
	public function postDocumentsUpload()
	{
		$data = array(
			'documents_type' => Input::get('documents_type'),
			'documents_desc' => Input::get('documents_desc'),
			'documents_from' => Input::get('documents_from'),
			'documents_viewed' => Input::get('documents_viewed'),
			'documents_date' => date("Y-m-d", strtotime(Input::get('documents_date')))
		);
		DB::table('documents')->where('documents_id', '=', Input::get('documents_id'))->update($data);
		$this->audit('Update');
		echo 'Document updated!';
	}
	
	public function postDeleteUpload()
	{
		$result = Documents::find(Input::get('documents_id'));
		$delete = unlink($result->documents_url);
		if ($delete == TRUE) {
			DB::table('documents')->where('documents_id', '=', Input::get('documents_id'))->delete();
			$this->audit('Delete');
			$arr = 'Document deleted!';
		} else {
			$arr = 'Error deleting document!';
		}	
		echo $arr;
	}
	
	public function postNewMessage()
	{
		$data = array(
			't_messages_signed' => 'No',
			't_messages_dos' => date('Y-m-d H:i:s', time()),
			'pid' => Session::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		$id = DB::table('t_messages')->insertGetId($data);
		echo $id;
	}
	
	public function postGetPrenatal()
	{
		$row = Demographics::find(Session::get('pid'));
		if (isset($row->pregnant)) {
			echo $row->pregnant;
		} else {
			echo 'n';
		}
	}
	
	public function postEditPregnancy()
	{
		$data = array(
			'pregnant' => Input::get('pregnant')
		);
		DB::table('demographics')->where('pid', '=', Session::get('pid'))->update($data);
		$this->audit('Update');
		echo "Patient infomration updated.";
	}
	
	public function getModalView($eid)
	{
		return $this->encounters_view($eid, Session::get('pid'), Session::get('practice_id'), true, true);
	}
	
	// Menu Lists
	public function postDemographicsList()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$row = Demographics::find($pid);
		$row_relate = Demographics_relate::where('pid', '=', $pid)->where('practice_id', '=', $practice_id)->first();
		$result = '';
		if ($row) {
			$result .= '<p class="tips"><strong>Address:</strong><br>' . $row->address . '<br>' . $row->city . ', ' . $row->state . ' ' . $row->zip . '</p>';
			$result .= '<p class="tips"><strong>Phone Numbers:</strong><br>Home: ' . $row->phone_home . '<br>Work: ' . $row->phone_work . '<br>Cell: ' . $row->phone_cell . '<br>Email: ' . $row->email . '</p>';
			$result .= '<p class="tips"><strong>Emergency Contact:</strong><br>Contact: ' . $row->emergency_contact . ', ' . $row->emergency_phone . '</p>';
			$gender = Session::get('gender');
			if ($gender == 'female') {
				if ($row->pregnant != 'no') {
					$pregnant = 'Yes';
				} else {
					$pregnant = 'No';
				}
				$result .= '<p class="tips"><strong>Other:</strong><br>Sexually Active: ' . ucfirst($row->sexuallyactive) . '<br>Tobacco Use: ' . ucfirst($row->tobacco) . '<br>Pregnant: ' . $pregnant . '</p>';
			} else {
				$result .= '<p class="tips"><strong>Other:</strong><br>Sexually Active: ' . ucfirst($row->sexuallyactive) . '<br>Tobacco Use: ' . ucfirst($row->tobacco) . '</p>';
			}
			$result .= '<p class="tips">Active since ' . $row->date . '</p>';
			if ($row_relate->id != '') {
				$result .= '<p class="tips">Online account is active.</p>';
			} else {
				$result .= '<p class="tips">No online account.</p>';
			}
			$schedule1 = Schedule::where('pid', '=', $pid)->where('status', '=', 'LMC')->get();
			$schedule2 = Schedule::where('pid', '=', $pid)->where('status', '=', 'DNKA')->get();
			$result .= '<p class="tips"><strong># Last minute cancellations: ' . count($schedule1) . '</strong></p>';
			$result .= '<p class="tips"><strong># Did not keep appointments: ' . count($schedule2) . '</strong></p>';
			$result .= '<p class="tips"><strong>Billing Notes:</strong><br>' . nl2br($row->billing_notes) . '</p>';
			$query1 = Insurance::where('pid', '=', $pid)->where('insurance_plan_active', '=', 'Yes')->get();
			if ($query1) {
				$result .= '<p class="tips"><strong>Active Insurance:</strong>';
				foreach ($query1 as $row1) {
					$result .= '<br>' . $row1->insurance_plan_name . '; ID: ' . $row1->insurance_id_num . '; Group: ' . $row1->insurance_group;
					if ($row1->insurance_copay != '') {
						$result .= '; Copay: ' . $row1->insurance_copay; 
					}
					if ($row1->insurance_deductible != '') {
						$result .= '; Deductible: ' . $row1->insurance_deductible; 
					}
					if ($row1->insurance_comments != '') {
						$result .= '; Comments: ' . $row1->insurance_comments; 
					}
				}
				$result .= '</p>';
			}
			$result .= '<p class="tips"><strong>Credit Card on File: ';
			if ($row->creditcard_number != '') {
				$result .= 'Yes';
			} else {
				$result .= 'No';
			}
			$result .= '</strong></p>';
		} else {
			$result .= 'None available.';
		}
		echo $result;
	}
	
	public function postIssuesList()
	{
		$query = Issues::where('pid', '=', Session::get('pid'))->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				$result .= '<li>' . $row->issue . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
	}
	
	public function postMedicationsList()
	{
		$query = Rx_list::where('pid', '=', Session::get('pid'))
			->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
			->where('rxl_date_old', '=', '0000-00-00 00:00:00')
			->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				if ($row->rxl_sig == '') {
					$result .= '<li>' . $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_instructions . ' for ' . $row->rxl_reason . '</li>';
				} else {
					$result .= '<li>' . $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency . ' for ' . $row->rxl_reason . '</li>';
				}
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
	}
	
	public function postSupplementsList()
	{
		$query = Sup_list::where('pid', '=', Session::get('pid'))->where('sup_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				$result .= '<li>' . $row->sup_supplement . ' ' . $row->sup_dosage . ' ' . $row->sup_dosage_unit . ', ' . $row->sup_sig . ' ' . $row->sup_route . ' ' . $row->sup_frequency . ' for ' . $row->sup_reason . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
	}
	
	public function postImmunizationsList()
	{
		$query = Immunizations::where('pid', '=', Session::get('pid'))
			->orderBy('imm_immunization', 'asc')
			->orderBy('imm_sequence', 'asc')
			->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				$sequence = '';
				if ($row->imm_sequence == '1') {
					$sequence = ', first';
				}
				if ($row->imm_sequence == '2') {
					$sequence = ', second';
				}
				if ($row->imm_sequence == '3') {
					$sequence = ', third';
				}
				if ($row->imm_sequence == '4') {
					$sequence = ', fourth';
				}
				if ($row->imm_sequence == '5') {
					$sequence = ', fifth';
				}
				$result .= '<li>' . $row->imm_immunization . $sequence . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
	}
	
	public function postAllergiesList()
	{
		$query = Allergies::where('pid', '=', Session::get('pid'))->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				$result .= '<li>' . $row->allergies_med . ' - ' . $row->allergies_reaction . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' No known allergies.';
		}
		echo $result;
	}
	
	public function postAlertsList()
	{
		$query = Alerts::where('pid', '=', Session::get('pid'))
			->where('alert_date_active', '<=', date('Y-m-d H:i:s', time() + 1209600))
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', Session::get('practice_id'))
			->get();
		$result = '';
		if ($query) {
			$result .= '<ul>';
			foreach ($query as $row) {
				$result .= '<li>' . $row->alert . ' (Due ' . date('m/d/Y', $this->human_to_unix($row->alert_date_active)) . ') - ' . $row->alert_description . '</li>';
			}
			$result .= '</ul>';
		} else {
			$result .= ' None.';
		}
		echo $result;
	}
	
	public function postPrevention()
	{
		$row = Demographics::find(Session::get('pid'));
		if ($row->sex == 'm') {
			$gender = 'Male';
		} 
		if ($row->sex == 'f' || $row->sex == 'u') {
			$gender = 'Female';
		}
		$age = (time() - $this->human_to_unix($row->DOB))/31556926;
		$age = round($age, 0, PHP_ROUND_HALF_DOWN);
		$sexuallyactive = $row->sexuallyactive;
		$tobacco = $row->tobacco;
		$pregnant = $row->pregnant;
		if ($pregnant != 'no') {
			$pregnant1 = '&pregnant=Yes';
		} else {
			$pregnant1 = '';
		}
		$result = '';
		$cr = curl_init('http://epss.ahrq.gov/ePSS/ePSSwidget.jsp');
		curl_setopt($cr, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cr, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($cr, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$data1 = curl_exec($cr);
		curl_close($cr);
		$fields_string = 'age=' . $age . '&sex=' . $gender . $pregnant1 . '&sexuallyActive=' . $sexuallyactive . '&tobacco=' . $tobacco . '&x=22&y=12';
		$cr1 = curl_init('http://epss.ahrq.gov/ePSS/GetResults.do?' . $fields_string);
		curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cr1, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($cr1, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$data = curl_exec($cr1);
		curl_close($cr1);
		$html = new Htmldom($data);
		if (isset($html)) {
			$div = $html->find('div[id=maincontent]',0);
			if (isset($div)) {
				$intro = $html->find('p',0);
				$result .= $intro->outertext;
				$table = $div->find('table',0);
				if (isset($table)) {
					$result .= '<ul>';
					foreach ($html->find('div[id=maincontent]',0)->find('table',0)->find('tr') as $tr) {
						$td = $tr->find('td[bgcolor=#FFFFFF]',0);
						if (isset($td)) {
							$text = $td->innertext;
							$result .= '<li>' . $text . '</li>';
						}	
					}
					$result .= '</ul>';
				}
			} else {
				$result .= 'Unable to contact Electronic Preventative Services Selector!';
			}
			if ($age <= 18) {
				$result .= '<iframe src="http://www.cdc.gov/vaccines/schedules/hcp/imz/child-adolescent-shell.html"   width="100%" height="1200px" frameborder="0" scrolling="auto" id="Iframe" title="Child Immunization Schedule">Child Immunization Schedule</iframe>';
			}
			if ($age > 18) {
				$result .= '<iframe src="http://www.cdc.gov/vaccines/schedules/hcp/imz/adult-shell.html" width="100%" height="1200px" frameborder="0" scrolling="auto" id="Iframe" title="Adult Schedule">Adult Immunization Schedule</iframe>';
			}
		} else {
			$result .= 'Unable to contact Electronic Preventative Services Selector!';
		}
		echo $result;
	}
	
	// Encounters functions
	public function postEncounterIdSet()
	{
		if (Session::get('eid') != FALSE) {
			Session::forget('eid');
			Session::forget('encounter_template');
			Session::forget('encounter_DOS');
		}
		$eid = Input::get('eid');
		Session::put('eid', $eid);
		$encounter = DB::table('encounters')->where('eid', '=', $eid)->first();
		Session::put('encounter_template', $encounter->encounter_template);
		Session::put('encounter_DOS', $encounter->encounter_DOS);
	}
	
	// Issues functions
	public function postEditIssue()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$data = array(
				'issue' => Input::get('issue'),
				'issue_date_active' => date('Y-m-d H:i:s', strtotime(Input::get('issue_date_active'))),
				'issue_date_inactive' => '',
				'type' => Input::get('type'),
				'issue_provider' => Session::get('displayname'),
				'pid' => $pid,
				'rcopia_sync' => 'n'
			);
			if(Input::get('issue_id') == '') {
				$id = DB::table('issues')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'issues', 'issue_id', $id);
				$result = Practiceinfo::find(Session::get('practice_id'));
				if ($result->mtm_extension == 'y') {
					$this->add_mtm_alert($pid, 'issues');
				}
				$arr = "Issue added!";
			} else {
				DB::table('issues')->where('issue_id', '=', Input::get('issue_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'issues', 'issue_id', Input::get('issue_id'));
				$arr = "Issue updated!";
			}
			echo $arr;
		}
	}
	
	public function postInactivateIssue()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'issue_date_inactive' => date('Y-m-d H:i:s', time()),
				'rcopia_sync' => 'nd1'
			);
			DB::table('issues')->where('issue_id', '=', Input::get('issue_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'issues', 'issue_id', Input::get('issue_id'));
			echo "Issue inactivated!";
		}
	}
	
	public function postDeleteIssue()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$issue_id = Input::get('issue_id');
			$practice = Practiceinfo::find(Session::get('practice_id'));
			if($practice->rcopia_extension == 'y') {
				$data = array(
					'rcopia_sync' => 'nd'
				);
				DB::table('issues')->where('issue_id', '=', $issue_id)->update($data);
				$this->audit('Update');
				while(!$this->check_rcopia_delete('issues', $issue_id)) {
					sleep(2);
				}
			}
			DB::table('issues')->where('issue_id', '=', $issue_id)->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'issues', 'issue_id', $issue_id);
			echo "Issue deleted!";
		}
	}
	
	public function postReactivateIssue()
	{
		$data = array(
			'issue_date_inactive' => '0000-00-00 00:00:00',
			'rcopia_sync' => 'n'
		);
		DB::table('issues')->where('issue_id', '=', Input::get('issue_id'))->update($data);
		$this->audit('Update');
		$this->api_data('update', 'issues', 'issue_id', Input::get('issue_id'));
		echo "Issue reactivated!";
	}
	
	// Medications functions
	public function postEditMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$data = array(
				'rxl_medication' => Input::get('rxl_medication'),
				'rxl_dosage' => Input::get('rxl_dosage'),
				'rxl_dosage_unit' => Input::get('rxl_dosage_unit'),
				'rxl_sig' => Input::get('rxl_sig'),
				'rxl_route' => Input::get('rxl_route'),
				'rxl_frequency' => Input::get('rxl_frequency'),
				'rxl_instructions' => Input::get('rxl_instructions'),
				'rxl_reason' => Input::get('rxl_reason'),
				'rxl_date_active' => date('Y-m-d H:i:s', strtotime(Input::get('rxl_date_active'))),
				'rxl_date_prescribed' => '',
				'rxl_date_inactive' => '',
				'rxl_date_old' => '',
				'rxl_provider' => Session::get('displayname'),
				'id' => Session::get('user_id'),
				'pid' => $pid,
				'rcopia_sync' => 'n',
				'rxl_ndcid' => Input::get('rxl_ndcid')
			);	
			if(Input::get('rxl_id') == '') {
				$id = DB::table('rx_list')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'rx_list', 'rxl_id', $id);
				$result = Practiceinfo::find(Session::get('practice_id'));
				if ($result->mtm_extension == 'y') {
					$this->add_mtm_alert($pid, 'medications');
				}
				$arr = "Medication added!";
			} else {
				DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'rx_list', 'rxl_id', Input::get('rxl_id'));
				$arr = "Medication updated!";
			}
			echo $arr;
		}
	}
	
	public function postPastMedication()
	{
		$rxl_medication = Input::get('rxl_medication');
		$query = DB::table('rx_list')
			->where('pid', '=', Session::get('pid'))
			->where('rxl_medication', '=', $rxl_medication)
			->select('rxl_date_prescribed')
			->distinct()
			->get();
		$result['header'] = 'Dates prescribed for ' . $rxl_medication . ': ';
		$result['item'] = '';
		if ($query) {
			foreach ($query as $row) {
				$result['item'] .= date('m/d/Y', strtotime($row->rxl_date_prescribed)) . '<br>';
			}
		} else {
			$result['item'] .= 'None.';
		}
		echo json_encode($result);
	}
	
	public function postInactivateMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row = Rx_list::find(Input::get('rxl_id'));
			if ($row->rxl_sig == '') {
				$instructions = $row->rxl_instructions;
			} else {
				$instructions = $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency;
			}
			$result['medtext'] =  $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $instructions . ' for ' . $row->rxl_reason;
			$data = array(
				'rxl_date_inactive' => date('Y-m-d H:i:s', time()),
				'rcopia_sync' => 'nd1'
			);
			DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'rx_list', 'rxl_id', Input::get('rxl_id'));
			$result['message'] = "Medication inactivated!";
			echo json_encode($result);
		}
	}
	
	public function postDeleteMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$rxl_id = Input::get('rxl_id');
			$practice = Practiceinfo::find(Session::get('practice_id'));
			if($practice->rcopia_extension == 'y') {
				$data = array(
					'rcopia_sync' => 'nd'
				);
				DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->update($data);
				$this->audit('Update');
				while(!$this->check_rcopia_delete('rx_list', $rxl_id)) {
					sleep(2);
				}
			}
			DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'rx_list', 'rxl_id', Input::get('rxl_id'));
			echo "Medication deleted!";
		}
	}

	public function postReactivateMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row = Rx_list::find(Input::get('rxl_id'));
			if ($row->rxl_sig == '') {
				$instructions = $row->rxl_instructions;
			} else {
				$instructions = $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency;
			}
			$result['medtext'] =  $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $instructions . ' for ' . $row->rxl_reason;
			$data = array(
				'rxl_date_inactive' => '0000-00-00 00:00:00',
				'rcopia_sync' => 'n'
			);
			DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'rx_list', 'rxl_id', Input::get('rxl_id'));
			$result['message'] = "Medication reactivated!";
			echo json_encode($result);
		}
	}
	
	public function postPrescribeMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$med = Input::get('rxl_medication');
			$user_id = Input::get('id');
			$user = User::find($user_id);
			$result1 = Providers::find($user_id);
			$license = $result1->license . ' -' . $result1->license_state;
			if(Input::get('dea') == 'Yes') {
				$dea = $result1->dea;
			} else {
				$dea = '';
			}
			if(Input::get('daw') == 'Yes') {
				$daw = 'Dispense As Written';
			} else {
				$daw = '';
			}	
			$date = strtotime(Input::get('rxl_date_prescribed'));
			$date_active = date('Y-m-d H:i:s', $date);
			$duedate1 = Input::get('rxl_days') * 86400;
			$duedate2 = $date + $duedate1;
			$duedate = date('Y-m-d H:i:s', $duedate2);
			if(Input::get('rxl_id') == '') {
				$data = array(
					'rxl_medication' => Input::get('rxl_medication'),
					'rxl_dosage' => Input::get('rxl_dosage'),
					'rxl_dosage_unit' => Input::get('rxl_dosage_unit'),
					'rxl_sig' => Input::get('rxl_sig'),
					'rxl_route' => Input::get('rxl_route'),
					'rxl_frequency' => Input::get('rxl_frequency'),
					'rxl_instructions' => Input::get('rxl_instructions'),
					'rxl_reason' => Input::get('rxl_reason'),
					'rxl_quantity' => Input::get('rxl_quantity'),
					'rxl_refill' => Input::get('rxl_refill'),
					'rxl_days' => Input::get('rxl_days'),
					'rxl_date_active' => $date_active,
					'rxl_date_inactive' => '',
					'rxl_date_prescribed' => $date_active,
					'rxl_date_old' => '',
					'rxl_provider' => $user->displayname,
					'id' => $user_id,
					'rxl_dea' => $dea,
					'rxl_daw' => $daw,
					'rxl_license' => $license,
					'rxl_due_date' => $duedate,
					'pid' => $pid,
					'rxl_ndcid' => Input::get('rxl_ndcid')
				);
				$add = DB::table('rx_list')->insertGetId($data);
				$this->audit('Add');
				$practice_result = Practiceinfo::find(Session::get('practice_id'));
				if ($practice_result->mtm_extension == 'y') {
					$this->add_mtm_alert($pid, 'medications');
				}
				if (Input::get('rxl_sig') == '') {
					$instructions = Input::get('rxl_instructions');
				} else {
					$instructions = Input::get('rxl_sig') . ' ' . Input::get('rxl_route') . ' ' . Input::get('rxl_frequency');
				}
				$result = array(
					'message' => 'Medication prescribed!',
					'id' => $add,
					'med' => 'Choose an action for ' . Input::get('rxl_medication') . ' ' . Input::get('rxl_dosage') . ' ' . Input::get('rxl_dosage_unit') . '.',
					'medtext' => Input::get('rxl_medication') . ' ' . Input::get('rxl_dosage') . ' ' . Input::get('rxl_dosage_unit') . ', ' . $instructions . ' for ' . Input::get('rxl_reason') . ', Quantity: ' . Input::get('rxl_quantity') . ', Refills: ' . Input::get('rxl_refill')
				);
			} else {
				$date_old = date('Y-m-d H:i:s', time());
				$data1 = array(
					'rxl_date_old' => $date_old
				);
				DB::table('rx_list')->where('rxl_id', '=', Input::get('rxl_id'))->update($data1);
				$this->audit('Update');
				$this->api_data('update', 'rx_list', 'rxl_id', Input::get('rxl_id'));
				$result2 = Rx_list::find(Input::get('rxl_id'));
				$old_date_active = $result2->rxl_date_active;
				$data2 = array(
					'rxl_medication' => Input::get('rxl_medication'),
					'rxl_dosage' => Input::get('rxl_dosage'),
					'rxl_dosage_unit' => Input::get('rxl_dosage_unit'),
					'rxl_sig' => Input::get('rxl_sig'),
					'rxl_route' => Input::get('rxl_route'),
					'rxl_frequency' => Input::get('rxl_frequency'),
					'rxl_instructions' => Input::get('rxl_instructions'),
					'rxl_reason' => Input::get('rxl_reason'),
					'rxl_quantity' => Input::get('rxl_quantity'),
					'rxl_refill' => Input::get('rxl_refill'),
					'rxl_days' => Input::get('rxl_days'),
					'rxl_date_active' => $old_date_active,
					'rxl_date_inactive' => '',
					'rxl_date_prescribed' => $date_active,
					'rxl_date_old' => '',
					'rxl_provider' => $user->displayname,
					'id' => $user_id,
					'rxl_dea' => $dea,
					'rxl_daw' => $daw,
					'rxl_license' => $license,
					'rxl_due_date' => $duedate,
					'pid' => $pid,
					'rxl_ndcid' => Input::get('rxl_ndcid')
				);
				$add1 = DB::table('rx_list')->insertGetId($data2);
				$this->audit('Add');
				$this->api_data('add', 'rx_list', 'rxl_id', $add1);
				if (Input::get('rxl_sig') == '') {
					$instructions = Input::get('rxl_instructions');
				} else {
					$instructions = Input::get('rxl_sig') . ' ' . Input::get('rxl_route') . ' ' . Input::get('rxl_frequency');
				}
				$result = array(
					'message' => 'Medication prescribed!',
					'id' => $add1,
					'med' => 'Choose an action for ' . Input::get('rxl_medication') . ' ' . Input::get('rxl_dosage') . ' ' . Input::get('rxl_dosage_unit') . '.',
					'medtext' => Input::get('rxl_medication') . ' ' . Input::get('rxl_dosage') . ' ' . Input::get('rxl_dosage_unit') . ', ' . $instructions . ' for ' . Input::get('rxl_reason') . ', Quantity: ' . Input::get('rxl_quantity') . ', Refills: ' . Input::get('rxl_refill')
				);
			}
			echo json_encode($result);
		}
	}

	public function postInteractionsMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$rxl_medication = Input::get('rxl_medication');
			$rx = explode(" ", $rxl_medication);
			$query_allergies = DB::table('allergies')
				->where('pid', '=', $pid)
				->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')
				->where('allergies_med', '=', $rxl_medication)
				->first();
			if ($query_allergies) {
				$result['message'] = 'Allergies';
				$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
				$result['info'] .= 'ALERT: Medication prescribed is in the patient allergy list!';
				$result['info'] .= '</div>';
				echo json_encode($result);
				exit (0);
			}
			$query = DB::table('rx_list')
				->where('pid', '=', $pid)
				->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
				->where('rxl_date_old', '=', '0000-00-00 00:00:00')
				->get();
			if ($query) {
				$comp_array = array();
				$query1 = "http://www.medscape.com/api/quickreflookup/LookupService.ashx?q=" . $rx[0] . "&all=false&sz=500&limit=500&type=10417&metadata=has-interactions&format=json";
				$cr1 = curl_init($query1);
				curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr1, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($cr1, CURLOPT_ENCODING, "");
				curl_setopt($cr1, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($cr1, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				$data1 = curl_exec($cr1);
				curl_close($cr1);
				$data1_array = json_decode($data1, true);
				if (isset($data1_array['types'][0]['totalCount'])) {
					if ($data1_array['types'][0]['totalCount'] != 0) {
						$med1 = $data1_array['types'][0]["references"][0]["id"];
					} else {
						$result['message'] = 'Multiple';
						$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
						$result['info'] .= 'Medication being prescribed is not found in drug database.  Use at your own risk!';
						$result['info'] .= '</div>';
						echo json_encode($result);
						exit (0);
					}	
				} else {
					$result['message'] = 'Multiple';
					$result['info'] = '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
					$result['info'] .= 'Medication being prescribed is not found in drug database.  Use at your own risk!';
					$result['info'] .= '</div>';
					echo json_encode($result);
					exit (0);
				}
				foreach ($query as $row) {
					$rx1 = explode(" ", $row->rxl_medication);
					$query2 = "http://www.medscape.com/api/quickreflookup/LookupService.ashx?q=" . $rx1[0] . "&all=false&sz=500&limit=500&type=10417&metadata=has-interactions&format=json";
					$cr2 = curl_init($query2);
					$cookie = __DIR__.'/../../public/cookie.txt';
					curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
					curl_setopt($cr2, CURLOPT_COOKIEJAR, $cookie);
					curl_setopt($cr2, CURLOPT_ENCODING, "");
					curl_setopt($cr2, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($cr2, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					$data2 = curl_exec($cr2);
					curl_close($cr2);
					$data2_array = json_decode($data2, true);
					if (isset($data2_array['types'][0]['totalCount'])) {
						if ($data2_array['types'][0]['totalCount'] != 0) {
							$med2 = $data2_array['types'][0]["references"][0]["id"];
						} else {
							$med2 = '0';
						}	
					} else {
						$med2 = '0';
					}
					$query3 = "http://www.medscape.com/druginteraction.do?action=getMultiInteraction&ids=" . $med1 . "," . $med2;
					$cr3 = curl_init($query3);
					curl_setopt($cr3, CURLOPT_RETURNTRANSFER, true); 
					curl_setopt($cr3, CURLOPT_COOKIEJAR, $cookie);
					curl_setopt($cr3, CURLOPT_ENCODING, "");
					curl_setopt($cr3, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($cr3, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					$data3 = curl_exec($cr3);
					curl_close($cr3);
					$data3a = ltrim($data3);
					$data3_array = json_decode($data3, true);
					if ($data3_array["errorCode"] == '1') {
						foreach ($data3_array["multiInteractions"] as $row3) {
							$comp_array[] = $row3;
						}
					}
				}
				if (count($comp_array) > 0) {
					$result['message'] = 'Multiple';
					$result['info'] = '';
					foreach ($comp_array as $key => $row4) {
						$severity[$key] = $row4['severityId'];
					}
					array_multisort($severity, SORT_DESC, $comp_array);
					$s1 = '';
					$s2 = '';
					$s3 = '';
					$s4 = '';
					foreach ($comp_array as $row5) {
						if ($row5['severity'] == 'Contraindicated') {
							$s1 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
						}
						if ($row5['severity'] == 'Serious - Use Alternative') {
							$s2 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
						}
						if ($row5['severity'] == 'Significant - Monitor Closely') {
							$s3 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
						}
						if ($row5['severity'] == 'Minor') {
							$s4 .= '<h4>' . $row5['subject'] . " + " . $row5['object'] . "</h4>" . $row5['text'] . "<br>";
						}
					}
					if ($s1 != '') {
						$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>CONTRAINDICATED</h3>' . $s1;
						$result['info'] .= '</div><br>';
					}
					if ($s2 != '') {
						$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>SERIOUS - USE ALTERNATIVE</h3>' . $s2;
						$result['info'] .= '</div><br>';
					}
					if ($s3 != '') {
						$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>SIGNIFICANT - MONITOR CLOSELY</h3>' . $s3;
						$result['info'] .= '</div><br>';
					}
					if ($s4 != '') {
						$result['info'] .= '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><br><h3>MINOR</h3>' . $s4;
						$result['info'] .= '</div><br>';
					}
				} else {
					$result['message'] = 'None';
				}
			} else {
				$result['message'] = 'None';
			}
			echo json_encode($result);
		}
	}
	
	public function postEieMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$old_rxl_id = Input::get('rxl_id');
			$row = Rx_list::find($old_rxl_id);
			if ($row->rxl_sig == '') {
				$instructions = $row->rxl_instructions;
			} else {
				$instructions = $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency;
			}
			$result['medtext'] =  $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $instructions . ' for ' . $row->rxl_reason;
			$row1 = DB::table('rx_list')
				->where('rxl_medication', '=', $row->rxl_medication)
				->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
				->where('rxl_date_old', '!=', '0000-00-00 00:00:00')
				->orderBy('rxl_date_old', 'desc')
				->first();
			if ($row1) {
				$rxl_id = $row1->rxl_id;
				$data = array(
					'rxl_date_old' => '0000-00-00 00:00:00',
					'rcopia_sync' => 'nd1'
				);
				DB::table('rx_list')->where('rxl_id', '=', $row1->rxl_id)->update($data);
				$this->audit('Update');
				$this->api_data('update', 'rx_list', 'rxl_id', $row1->rxl_id);
			}
			$practice = Practiceinfo::find(Session::get('practice_id'));
			if($practice->rcopia_extension == 'y') {
				$data1 = array(
					'rcopia_sync' => 'nd'
				);
				DB::table('rx_list')->where('rxl_id', '=', $old_rxl_id)->update($data1);
				$this->audit('Update');
				while(!$this->check_rcopia_delete('rx_list', $old_rxl_id)) {
					sleep(2);
				}
			}
			DB::table('rx_list')->where('rxl_id', '=', $old_rxl_id)->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'rx_list', 'rxl_id', $old_rxl_id);
			$result['message'] = "Entered medication in error process complete!";
			echo json_encode($result);
		}
	}
	
	public function postRxFaxList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('job_id');
			if ($job_id == FALSE) {
				$job_id = '0';
			}
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('pages')
				->where('job_id', '=', $job_id)
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
			$query1 = DB::table('pages')
				->where('job_id', '=', $job_id)
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
	}
	
	public function postStartFaxMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			$pid = Session::get('pid');
			if (Session::get('job_id') == FALSE) {
				$job_id = '';
			} else {
				$job_id = Session::get('job_id');
			}
			$rxl_id = Input::get('fax_prescribe_id');
			$html = $this->page_medication($rxl_id)->render();
			$user_id = Session::get('user_id');
			$file_path = __DIR__."/../../public/temp/rx_" . time() . "_" . $user_id . ".pdf";
			$this->generate_pdf($html, $file_path);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$row1 = Rx_list::find($rxl_id);
			$file_original = $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_sig . ' ' . $row1->rxl_route . ' ' . $row1->rxl_frequency . ' for ' . $row1->rxl_reason;
			$result['id'] = $this->fax_document($pid, 'Prescription/Refill Authorization', 'yes', $file_path, $file_original , '', '', $job_id, 'no');
			$result['message'] = 'Prescription added to fax queue!';
			Session::put('job_id', $result['id']);
			unlink($file_path);
			echo json_encode($result);
		}
	}
	
	public function postSendFaxMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('job_id');
			$result_message = $this->send_fax($job_id, Input::get('messages_pharmacy_fax_number'), Input::get('messages_pharmacy_name'));
			echo $result_message;
		}
	}
	
	public function postCancelFaxMedication()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('job_id');
			if ($job_id != '') {
				$directory = Session::get('documents_dir') . 'sentfax/' . $job_id;
				$command = "rm -R " . $directory;
				$command1 = escapeshellcmd($command);
				exec($command1);
				DB::table('sendfax')->where('job_id', '=', $job_id)->delete();
				$this->audit('Delete');
				Session::forget('job_id');
				echo "Fax queue deleted!";
			} else {
				echo "Error deleting fax queue!";
			}
		}
	}
	
	public function postAddPharmacy()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'displayname' => Input::get('messages_pharmacy_name'),
				'fax' => Input::get('messages_pharmacy_fax_number'),
				'specialty' => 'Pharmacy'
			);
			DB::table('addressbook')->insert($data);
			$this->audit('Add');
			echo "Pharmacy added!";
		}
	}
	
	public function postRcopiaUpdateMedication($origin)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$eid = Session::get('eid');
			$pid = Session::get('pid');
			$encounter_provider = Session::get('displayname');
			$old = Demographics::find($pid);
			$rcopia_data = array(
				'rcopia_update_prescription' => 'y'
			);
			DB::table('demographics')->where('pid', '=', $pid)->update($rcopia_data);
			$this->audit('Update');
			$xml1 = "<Request><Command>update_prescription</Command>";
			$xml1 .= "<LastUpdateDate>" . $old->rcopia_update_prescription_date . "</LastUpdateDate>";
			$xml1 .= "<Patient><ExternalID>" . $pid . "</ExternalID></Patient>";
			$xml1 .= "</Request></RCExtRequest>";
			$result1 = $this->rcopia($xml1, Session::get('practice_id'));
			$response1 = new SimpleXMLElement($result1);
			$status1 = $response1->Response->Status . "";
			if ($status1 == "error") {
				$description1 = $response1->Response->Error->Text . "";
				$data1a = array(
					'action' => 'update_prescription',
					'pid' => $pid,
					'extensions_name' => 'rcopia',
					'description' => $description1,
					'practice_id' => Session::get('practice_id')
				);
				DB::table('extensions_log')->insert($data1a);
				$arr['response'] = "Error connecting to DrFirst RCopia.  Try again later.";
			} else {
				$response = $this->rcopia_update_medication_xml($pid, $result1, $origin);
				if ($response == "No updated prescriptions.") {
					$arr['response'] = $response;
				} else {
					$arr['medtext'] = $response;
					$arr['response'] = "Updated medications from DrFirst Rcopia.";
				}
			}
			echo json_encode($arr);
		}
	}
	
	// Supplements functions
	public function postCheckSupplementInventory()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$inventory_result = DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->first();
			$result = "Supplement does not exist in the inventory!";
			if ($inventory_result) {
				if ($inventory_result->quantity1 > 0) {
					$result = "OK";
				}
			}
			echo $result;
		}
	}
	
	public function postEditSupplement($origin_orders)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$user_id = Input::get('id');
			if ($user_id != '') {
				$user = User::find($user_id);
				$sup_provider = $user->displayname;
			} else {
				$sup_provider = '';
			}
			$data = array(
				'sup_supplement' => Input::get('sup_supplement'),
				'sup_dosage' => Input::get('sup_dosage'),
				'sup_dosage_unit' => Input::get('sup_dosage_unit'),
				'sup_sig' => Input::get('sup_sig'),
				'sup_route' => Input::get('sup_route'),
				'sup_frequency' => Input::get('sup_frequency'),
				'sup_instructions' => Input::get('sup_instructions'),
				'sup_reason' => Input::get('sup_reason'),
				'sup_date_active' => date('Y-m-d H:i:s', strtotime(Input::get('sup_date_active'))),
				'sup_date_inactive' => '',
				'sup_provider' => $sup_provider,
				'id' => $user_id,
				'pid' => $pid
			);
			if(Input::get('amount') != '') {
				$amount = Input::get('amount');
				if(Input::get('supplement_id') != '') {
					$data['supplement_id'] = Input::get('supplement_id');
					$inventory_result = DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->first();
					$quantity = $inventory_result->quantity1 - Input::get('amount');
					$inventory_data = array(
						'quantity1' => $quantity
					);
					DB::table('supplement_inventory')->where('supplement_id', '=', Input::get('supplement_id'))->update($inventory_data);
					$this->audit('Update');
					$sales_tax_check = Practiceinfo::find(Session::get('practice_id'));
					if ($origin_orders == "Y") {
						$eid = Session::get('eid');
						$encounterInfo = Encounters::find($eid);
						$dos1 = $this->human_to_unix($encounterInfo->encounter_DOS);
						$dos = date('mdY', $dos1);
						$dos2 = date('m/d/Y', $dos1);
						$pos = $encounterInfo->encounter_location;
						$icd_pointer = '';
						$assessment_data = Assessment::find($eid);
						if ($assessment_data) {
							if ($assessment_data->assessment_1 != '') {
								$icd_pointer .= "1";
							}
							if ($assessment_data->assessment_2 != '') {
								$icd_pointer .= "2";
							}
							if ($assessment_data->assessment_3 != '') {
								$icd_pointer .= "3";
							}
							if ($assessment_data->assessment_4 != '') {
								$icd_pointer .= "4";
							}
						}
						$cpt = array(
							'cpt' => $inventory_result->cpt,
							'cpt_charge' => $inventory_result->charge,
							'eid' => $eid,
							'pid' => $pid,
							'dos_f' => $dos2,
							'dos_t' => $dos2,
							'payment' => '0',
							'icd_pointer' => $icd_pointer,
							'unit' => $amount,
							'billing_group' => '1',
							'modifier' => '',
							'practice_id' => Session::get('practice_id')
						);
						DB::table('billing_core')->insert($cpt);
						$this->audit('Add');
						if ($sales_tax_check->sales_tax != '') {
							$sales_tax_add_query1 = DB::table('billing_core')
								->where('eid', '=', $eid)
								->where('cpt', 'LIKE', "sp%")
								->get();
							if (count($sales_tax_add_query1) > 1) {
								$sales_tax_total1 = $inventory_result->charge * $amount;
								foreach ($sales_tax_add_query1 as $sales_tax_add_row1) {
									$sales_tax_total1 += $sales_tax_add_row1->cpt_charge * $sales_tax_add_row1->unit;
								}
							} else {
								$sales_tax_total1 = $inventory_result->charge * $amount;
							}
							$sales_tax1 = array(
								'cpt' => 'sptax',
								'cpt_charge' => number_format($sales_tax_total1 * $sales_tax_check->sales_tax / 100, 2),
								'eid' => $eid,
								'pid' => $pid,
								'dos_f' => $dos2,
								'dos_t' => $dos2,
								'payment' => '0',
								'icd_pointer' => $icd_pointer,
								'unit' => '1',
								'billing_group' => '1',
								'modifier' => '',
								'practice_id' => Session::get('practice_id')
							);
							$sales_tax_row1 = DB::table('billing_core')
								->where('cpt', '=', 'sptax')
								->where('eid', '=', $eid)
								->first();
							if ($sales_tax_row1) {
								DB::table('billing_core')->where('billing_core_id', '=', $sales_tax_row1->billing_core_id)->update($sales_tax1);
								$this->audit('Update');
							} else {
								DB::table('billing_core')->insert($sales_tax1);
								$this->audit('Add');
							}
						}
					} else {
						if ($sales_tax_check->sales_tax != '') {
							$sales_tax_total2 = $inventory_result->charge * $amount;
							$tax = number_format($sales_tax_total2 * $sales_tax_check->sales_tax / 100, 2);
							$cpt_charge = $sales_tax_total2 + $tax;
							$reason = Input::get('sup_supplement') . ", Quantity: " . $amount . ", Tax: $" . $tax;
							$unit = '1';
						} else {
							$cpt_charge = $inventory_result->charge;
							$reason = Input::get('sup_supplement');
							$unit = $amount;
						}
						$other_data = array(
							'eid' => '0',
							'pid' => $pid,
							'dos_f' => date('m/d/Y'),
							'cpt_charge' => $cpt_charge,
							'reason' => $reason,
							'payment' => '0',
							'unit' => $unit,
							'practice_id' => Session::get('practice_id')
						);
						$id1 = DB::table('billing_core')->insertGetId($other_data);
						$this->audit('Add');
						$data1 = array(
							'other_billing_id' => $id1
						);
						DB::table('billing_core')->where('billing_core_id', '=', $id1)->update($data1);
						$this->audit('Update');
					}
				}
			}
			if(Input::get('sup_id') == '') {
				$sup_id = DB::table('sup_list')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('update', 'sup_list', 'sup_id', $sup_id);
				$result = array(
					'message' => 'Supplement added!',
					'medtext' => Input::get('sup_supplement') . ' ' . Input::get('sup_dosage')
				);
				if (Input::get('sup_dosage_unit') != "") {
					$result['medtext'] .= ' ' . Input::get('sup_dosage_unit');
				}
				if (Input::get('sup_sig') != "") {
					if (Input::get('sup_instructions') != "") {
						$result['medtext'] .= ', ' . Input::get('sup_sig') . ' ' . Input::get('sup_route') . ' ' . Input::get('sup_frequency') . ', ' . Input::get('sup_instructions') . ' for ' . Input::get('sup_reason');
					} else {
						$result['medtext'] .= ', ' . Input::get('sup_sig') . ' ' . Input::get('sup_route') . ' ' . Input::get('sup_frequency') . ' for ' . Input::get('sup_reason');
					}
				} else {
					$result['medtext'] .= ', ' . Input::get('sup_instructions') . ' for ' . Input::get('sup_reason');
				}
			} else {
				DB::table('sup_list')->where('sup_id', '=', Input::get('sup_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'sup_list', 'sup_id', Input::get('sup_id'));
				$result = array(
					'message' => 'Supplement updated!',
					'medtext' => Input::get('sup_supplement') . ' ' . Input::get('sup_dosage')
				);
				if (Input::get('sup_dosage_unit') != "") {
					$result['medtext'] .= ' ' . Input::get('sup_dosage_unit');
				}
				if (Input::get('sup_sig') != "") {
					if (Input::get('sup_instructions') != "") {
						$result['medtext'] .= ', ' . Input::get('sup_sig') . ' ' . Input::get('sup_route') . ' ' . Input::get('sup_frequency') . ', ' . Input::get('sup_instructions') . ' for ' . Input::get('sup_reason');
					} else {
						$result['medtext'] .= ', ' . Input::get('sup_sig') . ' ' . Input::get('sup_route') . ' ' . Input::get('sup_frequency') . ' for ' . Input::get('sup_reason');
					}
				} else {
					$result['medtext'] .= ', ' . Input::get('sup_instructions') . ' for ' . Input::get('sup_reason');
				}
			}
			if(Input::get('amount') != "") {
				$result['medtext'] .= ", Quantity: " . Input::get('amount');
			}
			echo json_encode($result);
		}
	}
	
	public function postInactivateSupplement()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$sup_id = Input::get('sup_id');
			$row = DB::table('sup_list')->where('sup_id', '=', $sup_id)->first();
			$result['medtext'] =  $row->sup_supplement . ' ' . $row->sup_dosage . ' ' . $row->sup_dosage_unit . ', ' . $row->sup_sig . ' ' . $row->sup_route . ' ' . $row->sup_frequency . ' for ' . $row->sup_reason;
			$data = array(
				'sup_date_inactive' => date('Y-m-d H:i:s', time())
			);
			DB::table('sup_list')->where('sup_id', '=', $sup_id)->update($data);
			$this->audit('Update');
			$this->api_data('update', 'sup_list', 'sup_id', $sup_id);
			$result['message'] = "Supplement inactivated!";
			echo json_encode($result);
		}
	}
	
	public function postDeleteSupplement()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('sup_list')->where('sup_id', '=', Input::get('sup_id'))->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'sup_list', 'sup_id', Input::get('sup_id'));
			echo "Supplement deleted!";
		}
	}

	public function postReactivateSupplement()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$sup_id = Input::get('sup_id');
			$row = DB::table('sup_list')->where('sup_id', '=', $sup_id)->first();
			$result['medtext'] =  $row->sup_supplement . ' ' . $row->sup_dosage . ' ' . $row->sup_dosage_unit . ', ' . $row->sup_sig . ' ' . $row->sup_route . ' ' . $row->sup_frequency . ' for ' . $row->sup_reason;
			$data = array(
				'sup_date_inactive' => '0000-00-00 00:00:00'
			);
			DB::table('sup_list')->where('sup_id', '=', $sup_id)->update($data);
			$this->audit('Update');
			$this->api_data('update', 'sup_list', 'sup_id', $sup_id);
			$result['message'] = "Supplement reactivated!";
			echo json_encode($result);
		}
	}
	
	public function postOrdersSupSave()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$eid = Session::get('eid');
			$pid = Session::get('pid');
			$encounter_provider = Session::get('displayname');
			$query = DB::table('sup_list')->where('pid', '=', $pid)->where('sup_date_inactive', '=', '0000-00-00 00:00:00')->get();
			if ($query) {
				$rx_orders_summary_text = "";
				foreach ($query as $query_row) {
					$rx_orders_summary_text .= $query_row->sup_supplement . ' ' . $query_row->sup_dosage . ' ' . $query_row->sup_dosage_unit;
					if ($query_row->sup_sig != "") {
						$rx_orders_summary_text .= ", " . $query_row->sup_sig . ' ' . $query_row->sup_route . ' ' . $query_row->sup_frequency;
					}
					if ($query_row->sup_instructions != "") {
						$rx_orders_summary_text .= ", " . $query_row->sup_instructions;
					}
					$rx_orders_summary_text .= ' for ' . $query_row->sup_reason . "\n";
				}
			} else {
				$rx_orders_summary_text = "";
			}
			$rx_rx = "";
			$row = Rx::find($eid);
			if ($row) {
				$row_parts = explode("\n\n", $row->rx_supplements);
				$rx_text = "";
				$rx_purchase_text = "";
				$rx_inactivate_text = "";
				$rx_reactivate_text = "";
				foreach($row_parts as $row_part) {
					if (strpos($row_part, "SUPPLEMENTS ADVISED:")!==FALSE) {
						$rx_text .= str_replace("SUPPLEMENTS ADVISED:  ","",$row_part);
					}
					if (strpos($row_part, "SUPPLEMENTS PURCHASED BY PATIENT:")!==FALSE) {
						$rx_purchase_text .= str_replace("SUPPLEMENTS PURCHASED BY PATIENT:  ","",$row_part);
					}
					if (strpos($row_part, "DISCONTINUED SUPPLEMENTS:")!==FALSE) {
						$rx_inactivate_text .= str_replace("DISCONTINUED SUPPLEMENTS:  ","",$row_part);
					}
					if (strpos($row_part, "REINSTATED SUPPLEMENTS:")!==FALSE) {
						$rx_reactivate_text .= str_replace("REINSTATED SUPPLEMENTS:  ","",$row_part);
					}
				}
				if($rx_text != "" || Input::get('advised')) {
					$rx_rx .= "SUPPLEMENTS ADVISED:  ";
					if ($rx_text) {
						$rx_rx .= $rx_text;
					}
					if (Input::get('advised')) {
						$rx_rx .= Input::get('advised');
					}
				}
				if($rx_purchase_text != "" || Input::get('purchased')) {
					$rx_rx .= "\n\nSUPPLEMENTS PURCHASED BY PATIENT:  ";
					if ($rx_purchase_text) {
						$rx_rx .= $rx_purchase_text;
					}
					if (Input::get('purchased')) {
						$rx_rx .= Input::get('purchased');
					}
				}
				if($rx_inactivate_text != "" || Input::get('inactivate')) {
					$rx_rx .= "\n\nDISCONTINUED SUPPLEMENTS:  ";
					if ($rx_inactivate_text) {
						$rx_rx .= $rx_inactivate_text;
					}
					if (Input::get('inactivate')) {
						$rx_rx .= Input::get('inactivate');
					}
				}
				if($rx_reactivate_text != "" || Input::get('reactivate')) {
					$rx_rx .= "\n\nREINSTATED SUPPLEMENTS:  ";
					if ($rx_reactivate_text) {
						$rx_rx .= $rx_inactivate_text;
					}
					if (Input::get('reactivate')) {
						$rx_rx .= Input::get('reactivate');
					}
				}
				$data = array(
					'eid' => $eid,
					'pid' => $pid,
					'encounter_provider' => $encounter_provider,
					'rx_supplements' => $rx_rx,
					'rx_supplements_orders_summary' => $rx_orders_summary_text
				);
				DB::table('rx')->where('eid', '=', $eid)->update($data);
				$this->audit('Update');
				$result = 'Supplement Orders Updated';
			} else {
				if (Input::get('advised')) {
					$rx_text = "SUPPLEMENTS ADVISED:  " . Input::get('advised');
					$rx_rx .= $rx_text . "\n\n";
				}
				if (Input::get('purchased')) {
					$rx_purchase_text = "SUPPLEMENTS PURCHASED BY PATIENT:  " . Input::get('purchased');
					$rx_rx .= $rx_purchase_text . "\n\n";
				}
				if (Input::get('inactivate')) {
					$rx_inactivate_text = "DISCONTINUED SUPPLEMENTS:  " . Input::get('inactivate');
					$rx_rx .= $rx_inactivate_text . "\n\n";
				}
				if (Input::get('reactivate')) {
					$rx_reactivate_text = "REINSTATED SUPPLEMENTS:  " . Input::get('reactivate');
					$rx_rx .= $rx_reactivate_text . "\n\n";
				}
				$data = array(
					'eid' => $eid,
					'pid' => $pid,
					'encounter_provider' => $encounter_provider,
					'rx_supplements' => $rx_rx,
					'rx_supplements_orders_summary' => $rx_orders_summary_text
				);
				DB::table('rx')->insert($data);
				$this->audit('Add');
				$result = 'Supplement Orders Added';
			}
			echo $result;
		}
	}
	
	// Messages functions
	public function postMessages()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('t_messages')
			->where('pid', '=', $pid);
		if (Session::get('patient_centric') == 'n') {
			$query->where('practice_id', '=', $practice_id);
		}
		$result = $query->get();
		if($result) { 
			$count = count($result);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query->orderBy($sidx, $sord)
			->skip($start)
			->take($limit);
		$query1 = $query->get();
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
	
	public function postAlerts1()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', $practice_id)
			->where(function($query_array1) {
				$query_array1->where('alert', '=', 'Laboratory results pending')
				->orWhere('alert', '=', 'Radiology results pending')
				->orWhere('alert', '=', 'Cardiopulmonary results pending');
			})
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', $practice_id)
			->where(function($query_array1) {
				$query_array1->where('alert', '=', 'Laboratory results pending')
				->orWhere('alert', '=', 'Radiology results pending')
				->orWhere('alert', '=', 'Cardiopulmonary results pending');
			})
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
	
	public function postAlerts2()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', $practice_id)
			->where(function($query_array1) {
				$query_array1->where('alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
				->orWhere('alert', '=', 'Radiology results pending - NEED TO OBTAIN')
				->orWhere('alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN');
			})
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', $practice_id)
			->where(function($query_array1) {
				$query_array1->where('alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
				->orWhere('alert', '=', 'Radiology results pending - NEED TO OBTAIN')
				->orWhere('alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN');
			})
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
	
	public function postInternalMessageReply()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$row = Demographics::find($pid);
			$row1 = Practiceinfo::find(Session::get('practice_id'));
			$row_relate = DB::table('demographics_relate')
				->where('pid', '=', $pid)
				->where('practice_id', '=', Session::get('practice_id'))
				->first();
			$data_message = array(
				'displayname' => Session::get('displayname'),
				'email' => $row1->email,
				'patient_portal' => $row1->patient_portal 
			);
			if ($row_relate->id == '') {
				if ($row->email == '') {
					echo 'No message sent!';
					exit (0);
				} else {
					$data_message['portal'] = false;
					$this->send_mail('emails.newresult', $data_message, 'Test Results Available', $row->email, Session::get('practice_id'));
					echo 'E-mail notification sent!';
					exit (0);
				}
			} else {
				$from = Session::get('user_id');
				$patient_name = $row->lastname . ', ' . $row->firstname . ' (DOB: ' . date('m/d/Y', strtotime($row->DOB)) . ') (ID: ' . $row->pid . ')';
				$patient_name1 = $row->lastname . ', ' . $row->firstname . ' (ID: ' . $row->pid . ')';
				$body = Input::get('body') . "\nPlease contact me if you have any questions." . "\n\nSincerely,\n" . Session::get('displayname');
				$data = array(
					'pid' => $pid,
					'patient_name' => $patient_name,
					'message_to' => $patient_name1,
					'cc' => '',
					'message_from' => $from,
					'subject' => 'Your Test Results',
					'body' => $body,
					'status' => 'Sent',
					'mailbox' => $row_relate->id,
					'practice_id' => Session::get('practice_id')
				);
				DB::table('messaging')->insert($data);
				$this->audit('Add');
				$data1a = array(
					'pid' => $pid,
					'patient_name' => $patient_name,
					'message_to' => $patient_name1,
					'cc' => '',
					'message_from' => $from,
					'subject' => 'Your Test Results',
					'body' => $body,
					'status' => 'Sent',
					'mailbox' => '0',
					'practice_id' => Session::get('practice_id')
				);
				DB::table('messaging')->insert($data1a);
				$this->audit('Add');
				if ($row->email == '') {
					echo 'Internal message sent!';
					exit (0);
				} else {
					$data_message['portal'] = true;
					$this->send_mail('emails.newresult', $data_message, 'Test Results Available', $row->email, Session::get('practice_id'));
					echo 'Internal message sent with e-mail notification!';
					exit (0);
				}
			}
		}
	}
	
	public function postLetterReply()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			$pid = Session::get('pid');
			$result = Practiceinfo::find(Session::get('practice_id'));
			$file_path = $result->documents_dir . $pid . '/letter_' . time() . '.pdf';
			$body = Input::get('body');
			$html = $this->page_letter_reply($body)->render();
			$this->generate_pdf($html, $file_path, 'footerpdf', '', '2');
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$desc = 'Test Results Letter for ' . Session::get('ptname');
			$pages_data = array(
				'documents_url' => $file_path,
				'pid' => $pid,
				'documents_type' => 'Letters',
				'documents_desc' => $desc,
				'documents_from' => Session::get('displayname'),
				'documents_viewed' => Session::get('displayname'),
				'documents_date' => date('Y-m-d H:i:s', time())
			);
			$arr['id'] = DB::table('documents')->insertGetId($pages_data);
			$this->audit('Add');
			$arr['message'] = 'OK';
			echo json_encode($arr);
		}
	}
	
	public function postEditMessage()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$from = Session::get('displayname') . ' (' . Session::get('user_id') . ')';
			$to = Input::get('t_messages_to');
			$data = array(
				't_messages_subject' => Input::get('t_messages_subject'),
				't_messages_message' => Input::get('t_messages_message'),
				't_messages_dos' => date('Y-m-d H:i:s', strtotime(Input::get('t_messages_dos'))),
				't_messages_provider' => Session::get('displayname'),
				't_messages_signed' => 'No',
				't_messages_to' => Input::get('t_messages_to'),
				't_messages_from' => $from,
				'pid' => $pid,
				'practice_id' => Session::get('practice_id')
			);
			DB::table('t_messages')->where('t_messages_id', '=', Input::get('t_messages_id'))->update($data);
			$this->audit('Update');
			if ($to != '') {
				$to_array = explode('(', $to);
				$demo_result = Demographics::find($pid);
				$patient_name =  $demo_result->lastname . ', ' . $demo_result->firstname . ' (DOB: ' . date("m/d/Y", strtotime($demo_result->DOB)) . ') (ID: ' . $pid . ')';
				if (Session::get('user_id') != $this->strstrb($to_array[1], ')')) {
					$data1 = array(
						'pid' => $pid,
						'patient_name' => $patient_name,
						'message_to' => Input::get('t_messages_to'),
						'message_from' => Session::get('user_id'),
						'subject' => 'Telephone Message - ' . Input::get('t_messages_subject'),
						'body' => Input::get('t_messages_message'),
						'status' => 'Sent',
						't_messages_id' => Input::get('t_messages_id'),
						'mailbox' => $this->strstrb($to_array[1], ')'),
						'practice_id' => Session::get('practice_id')
					);
					DB::table('messaging')->insert($data1);
					$this->audit('Add');
				}
			}
			echo "Telephone message updated!";
		}
	}
	
	public function postSignMessage()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				't_messages_subject' => Input::get('t_messages_subject'),
				't_messages_message' => nl2br(Input::get('t_messages_message')),
				't_messages_dos' => date('Y-m-d H:i:s', strtotime(Input::get('t_messages_dos'))),
				't_messages_provider' => Session::get('displayname'),
				't_messages_signed' => 'Yes',
				't_messages_to' => '',
				't_messages_from' => '',
				'pid' => Session::get('pid'),
				'practice_id' => Session::get('practice_id')
			);
			if(Input::get('t_messages_id') == '') {
				DB::table('t_messages')->insert($data);
				$this->audit('Add');
				echo "Telephone message signed!";
			} else {
				DB::table('t_messages')->where('t_messages_id', '=', Input::get('t_messages_id'))->update($data);
				$this->audit('Update');
				echo "Telephone message signed!";
			}
		}
	}
	
	public function postDeleteMessage()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('t_messages')->where('t_messages_id', '=', Input::get('t_messages_id'))->delete();
			$this->audit('Delete');
			echo "Telephone message deleted!";
		}
	}
	
	// Allergies functions
	public function postEditAllergy()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			if (strpos(Input::get('allergies_med'), ', ') === false) {
				$ndcid = '';
			} else {
				$med_name = explode(", ", Input::get('allergies_med'), -1);
				$ndcid = "";
				if ($med_name[0]) {
					$med_result = DB::table('meds_full_package')
						->join('meds_full', 'meds_full.PRODUCTNDC', '=', 'meds_full_package.PRODUCTNDC')
						->select('meds_full_package.NDCPACKAGECODE')
						->where('meds_full.PROPRIETARYNAME', '=', $med_name[0])
						->first();
					if ($med_result) {
						$ndcid = $this->ndc_convert($med_result->NDCPACKAGECODE);
					}
				}
			}
			$data = array(
				'allergies_med' => Input::get('allergies_med'),
				'allergies_reaction' => Input::get('allergies_reaction'),
				'allergies_date_active' => date('Y-m-d H:i:s', strtotime(Input::get('allergies_date_active'))),
				'allergies_date_inactive' => '',
				'allergies_provider' => Session::get('displayname'),
				'pid' => $pid,
				'rcopia_sync' => 'n',
				'meds_ndcid' => $ndcid
			);	
			if(Input::get('allergies_id') == '') {
				$id = DB::table('allergies')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'allergies', 'allergies_id', $id);
				$result['message'] = "Allergy added!";
			} else {
				DB::table('allergies')->where('allergies_id', '=', Input::get('allergies_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'allergies', 'allergies_id', Input::get('allergies_id'));
				$result['message'] = "Allergy updated!";
			}
			echo json_encode($result);
		}
	}
	
	public function postInactivateAllergy()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'allergies_date_inactive' => date('Y-m-d H:i:s', time()),
				'rcopia_sync' => 'nd1'
			);
			DB::table('allergies')->where('allergies_id', '=', Input::get('allergies_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'allergies', 'allergies_id', Input::get('allergies_id'));
			echo "Allergy inactivated!";
		}
	}
	
	public function postDeleteAllergy()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$allergies_id = Input::get('allergies_id');
			$practice = Practiceinfo::find(Session::get('practice_id'));
			if($practice->rcopia_extension == 'y') {
				$data = array(
					'rcopia_sync' => 'nd'
				);
				DB::table('allergies')->where('allergies_id', '=', $allergies_id)->update($data);
				$this->audit('Update');
				while(!$this->check_rcopia_delete('allergies', $allergies_id)) {
					sleep(2);
				}
			}
			DB::table('allergies')->where('allergies_id', '=', $allergies_id)->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'allergies', 'allergies_id', $allergies_id);
			echo "Allergy deleted!";
		}
	}

	public function postReactivateAllergy()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'allergies_date_inactive' => '0000-00-00 00:00:00',
				'rcopia_sync' => 'n'
			);
			DB::table('allergies')->where('allergies_id', '=', Input::get('allergies_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'allergies', 'allergies_id', Input::get('allergies_id'));
			echo "Allergy reactivated!";
		}
	}
	
	public function postRcopiaUpdateAllergy()
	{
		$pid = Session::get('pid');
		$encounter_provider = Session::get('displayname');
		$old = Demographics::find($pid);
		$rcopia_data = array(
			'rcopia_update_allergy' => 'y'
		);
		DB::table('demographics')->where('pid', '=', $pid)->update($rcopia_data);
		$this->audit('Update');
		$xml1 = "<Request><Command>update_allergy</Command>";
		$xml1 .= "<LastUpdateDate>" . $old->rcopia_update_allergy_date . "</LastUpdateDate>";
		$xml1 .= "<Patient><ExternalID>" . $pid . "</ExternalID></Patient>";
		$xml1 .= "</Request></RCExtRequest>";
		$result1 = $this->rcopia($xml1, Session::get('practice_id'));
		$response1 = new SimpleXMLElement($result1);
		$status1 = $response1->Response->Status . "";
		if ($status1 == "error") {
			$description1 = $response1->Response->Error->Text . "";
			$data1a = array(
				'action' => 'update_allergy',
				'pid' => $pid,
				'extensions_name' => 'rcopia',
				'description' => $description1,
				'practice_id' => Session::get('practice_id')
			);
			DB::table('extensions_log')->insert($data1a);
			$response = "Error connecting to DrFirst RCopia.  Try again later.";
		} else {
			$response = $this->rcopia_update_allergy_xml($pid, $result1);
		}
		echo $response;
	}
	
	// Alerts functions
	public function postAlerts()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
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
	
	public function postAlertsComplete()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '!=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '!=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
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
	
	public function postAlertsNotComplete()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '!=', '')
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '!=', '')
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
	
	public function postEditAlert()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			if (Input::get('alert_send_message') != '') {
				$alert_send_message = Input::get('alert_send_message');
			} else {
				$alert_send_message = 'n';
			}
			$data = array(
				'alert' => Input::get('alert'),
				'alert_description' => Input::get('alert_description'),
				'alert_date_active' => date('Y-m-d H:i:s', strtotime(Input::get('alert_date_active'))),
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => Input::get('id'),
				'orders_id' => '',
				'pid' => $pid,
				'practice_id' => Session::get('practice_id'),
				'alert_send_message' => $alert_send_message
			);
			if(Input::get('alert_id') == '') {
				$id = DB::table('alerts')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'alerts', 'alert_id', $id);
				echo "Alert/Task added!";
			} else {
				DB::table('alerts')->where('alert_id', '=', Input::get('alert_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'alerts', 'alert_id', Input::get('alert_id'));
				echo "Alert/Task updated!";
			}
		}
	}
	
	public function postDeleteAlert()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('alerts')->where('alert_id', '=', Input::get('alert_id'))->delete();
			$this->audit('Delete');
			$this->api_data('delete', 'alerts', 'alert_id', Input::get('alert_id'));
			echo "Alert/Task deleted!";
		}
	}
	
	public function postCompleteAlert()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'alert_date_complete' => date('Y-m-d H:i:s')
			);
			DB::table('alerts')->where('alert_id', '=', Input::get('alert_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'alerts', 'alert_id', Input::get('alert_id'));
			$row = DB::table('alerts')->where('alert_id', '=', Input::get('alert_id'))->first();
			if($row->orders_id != '') {
				$data1 = array(
					'orders_completed' => 'Yes'
				);
				DB::table('orders')->where('orders_id', '=', $row->orders_id)->update($data1);
				$this->audit('Update');
			}
			echo "Alert/Task marked completed!";
		}
	}
	
	public function postIncompleteAlert()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'alert_reason_not_complete' => Input::get('alert_reason_not_complete')
			);
			DB::table('alerts')->where('alert_id', '=', Input::get('alert_id'))->update($data);
			$this->audit('Update');
			$this->api_data('update', 'alerts', 'alert_id', Input::get('alert_id'));
			echo "Alert/Task marked incomplete!";
		}
	}
	
	public function postCompleteAlertOrder($orders_id)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$query = DB::table('alerts')->where('orders_id', '=', $orders_id)->where('alert', 'LIKE', "%NEED TO OBTAIN%")->first();
			if ($query) {
				$data = array(
					'alert_date_complete' => date('Y-m-d H:i:s')
				);
				DB::table('alerts')->where('alert_id', '=', $query->alert_id)->update($data);
				$this->audit('Update');
				$this->api_data('update', 'alerts', 'alert_id', $query->alert_id);
				echo "Alert/Task marked completed!";
			} else {
				echo "No alert associated with this order.";
			}
		}
	}
	
	public function postAlertsPending()
	{
		$pid = Session::get('pid');
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('alert', 'LIKE', "%NEED TO OBTAIN%")
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
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('alert', 'LIKE', "%NEED TO OBTAIN%")
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
	
	// Documents functions
	public function postDeleteDocument()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Documents::find(Input::get('documents_id'));
			$exists = file_exists($result->documents_url);
			$delete = FALSE;
			if ($exists == TRUE) {
				$delete = unlink($result->documents_url);
			} else {
				$delete = TRUE;
			}
			if ($delete == TRUE) {
				DB::table('documents')->where('documents_id', '=', Input::get('documents_id'))->delete();
				$this->audit('Delete');
				$arr = 'Document deleted!';
			} else {
				$arr = 'Error deleting document!';
			}
			echo $arr;
		}
	}
	
	public function postEditDocument()
	{
		$data = array(
			'documents_type' => Input::get('documents_type'),
			'documents_desc' => Input::get('documents_desc'),
			'documents_from' => Input::get('documents_from'),
			'documents_viewed' => Session::get('displayname'),
			'documents_date' => date('Y-m-d', strtotime(Input::get('documents_date')))
		);
		DB::table('documents')->where('documents_id', '=', Input::get('documents_id'))->update($data);
		$this->audit('Update');
		echo 'Document updated!';
	}
	
	public function postLetterTemplateSelectList()
	{
		$user_id = Session::get('user_id');
		if (Session::get('gender') == 'male') {
			$sex = 'm';
		} elseif (Session::get('gender') == 'female') {
			$sex = 'f';
		} else {
			$sex = 'u';
		}
		$result = DB::table('templates')
			->where('user_id', '=', $user_id)
			->orWhere('user_id', '=', '0')
			->where('sex', $sex)
			->where('category', '=', 'letter')
			->get();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			if ($row->template_name == 'Global Default') {
				if ($row->group == 'school_absence') {
					$name = 'School - Absence';
				}
				if ($row->group == 'school_return') {
					$name = 'School - Return';
				}
				if ($row->group == 'work_absence') {
					$name = 'Work - Absence';
				}
				if ($row->group == 'work_return') {
					$name = 'Work - Return';
				}
				if ($row->group == 'work_modified') {
					$name = 'Work - Modified Duties';
				}
			} else {
				$name = $row->template_name;
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postGetLetterTemplate($id)
	{
		$row = Templates::find($id);
		$data = unserialize($row->array);
		echo json_encode($data);
	}
	
	public function postLetterTemplateConstruct()
	{
		$pid = Session::get('pid');
		$row1 = Encounters::where('pid', '=', Session::get('pid'))
			->where('eid', '!=', '')
			->where('practice_id', '=', Session::get('practice_id'))
			->orderBy('eid', 'desc')
			->first();
		if ($row1) {
			$lastvisit = date('F jS, Y', strtotime($row1->encounter_DOS));
		} else {
			$lastvisit = "No previous visits.";
		}
		$ptname = Session::get('ptname');
		$row = Demographics::find($pid);
		$arr['start'] = 'This letter is in regards to ' . $row->firstname . ' ' . $row->lastname . ' (Date of Birth: ' . date('F jS, Y', $this->human_to_unix($row->DOB)) . '), who is a patient of mine.  ' . $row->firstname . ' was last seen by me on ' . $lastvisit . '.  ';
		$arr['firstname'] = $row->firstname;
		echo json_encode($arr);
	}
	
	public function postPrintLetter()
	{
		ini_set('memory_limit','196M');
		$pid = Session::get('pid');
		$result = Practiceinfo::find(Session::get('practice_id'));
		$file_path = $result->documents_dir . $pid . '/letter_' . time() . '.pdf';
		$letter_to = Input::get('letter_to');
		$letter_body = Input::get('letter_body');
		$address_id = Input::get('address_id');
		$html = $this->page_intro('Letter', Session::get('practice_id'))->render();
		$html .= $this->page_letter($letter_to, $letter_body, $address_id);
		$this->generate_pdf($html, $file_path, 'footerpdf', '', '1');
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$desc = 'Letter for ' . Session::get('ptname');
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => $desc,
			'documents_from' => Session::get('displayname'),
			'documents_viewed' => Session::get('displayname'),
			'documents_date' => date('Y-m-d H:i:s', time())
		);
		$arr['id'] = DB::table('documents')->insertGetId($pages_data);
		$this->audit('Add');
		$arr['message'] = 'OK';
		echo json_encode($arr);
	}
	
	public function postTests($mask='')
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord'); 
		if($mask == ''){
			$query = DB::table('tests')->where('pid', '=', $pid)->get();
		} else {
			$query = DB::table('tests')
				->where('pid', '=', $pid)
				->where('test_name', 'LIKE', "%$mask%")
				->get();
		}
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
		if($mask == ''){
			$query1 = DB::table('tests')
				->where('pid', '=', $pid)
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
		} else {
			$query1 = DB::table('tests')
				->where('pid', '=', $pid)
				->where('test_name', 'LIKE', "%$mask%")
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
		}
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		if ($query1) {
			$response['rows'] = $query1;
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
		exit( 0 );
	}
	
	public function postChartTest($tests_id)
	{
		$pid = Session::get('pid');
		$demographics = Demographics::find($pid);
		$datenow = date("D, d M y H:i:s O", time());
		$row0 = Tests::find($tests_id);
		$data['patient'] = array();
		$data['yaxis'] = $row0->test_units;
		$data['xaxis'] = 'Date';
		$data['name'] = $row0->test_name;
		$data['title'] = 'Chart of ' . $row0->test_name . ' over time for ' . $demographics->firstname . ' ' . $demographics->lastname . ' as of ' . $datenow;
		$query1 = DB::table('tests')
			->where('test_name', '=', $row0->test_name)
			->where('pid', '=', $pid)
			->orderBy('test_datetime', 'ASC')
			->get();
		if ($query1) {
			$i = 0;
			foreach ($query1 as $row1) {
				$x = $row1->test_datetime;
				$y = $row1->test_result;
				$data['patient'][$i][] = $x;
				$data['patient'][$i][] = $y;
				$i++;
			}
		}
		echo json_encode($data);
	}
	
	// Immunizations functions
	public function postEditImmunization()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$date_active = date('Y-m-d H:i:s', strtotime(Input::get('imm_date')));
			if (Input::get('imm_expiration')=='') {
				$date_expiration = '';
			} else {
				$date_expiration = date('Y-m-d H:i:s', strtotime(Input::get('imm_expiration')));
			}
			if (Input::get('imm_elsewhere')=='Yes') {
				$imm_elsewhere = 'Yes';
			} else {
				$imm_elsewhere = 'No';
			}
			$data = array(
				'imm_immunization' => Input::get('imm_immunization'),
				'imm_sequence' => Input::get('imm_sequence'),
				'imm_body_site' => Input::get('imm_body_site'),
				'imm_route' => Input::get('imm_route'),
				'imm_dosage' => Input::get('imm_dosage'),
				'imm_dosage_unit' => Input::get('imm_dosage_unit'),
				'imm_lot' => Input::get('imm_lot'),
				'imm_expiration' => $date_expiration,
				'imm_date' => $date_active,
				'imm_elsewhere' => $imm_elsewhere,
				'imm_vis' => '',
				'imm_manufacturer' => Input::get('imm_manufacturer'),
				'imm_provider' => Session::get('displayname'),
				'imm_cvxcode' => Input::get('imm_cvxcode'),
				'pid' => $pid,
				'eid' => ''
			);
			if(Input::get('imm_id') == '') {
				$id = DB::table('immunizations')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'immunizations', 'imm_id', $id);
				$result['message'] = "Immunization added!";
			} else {
				DB::table('immunizations')->where('imm_id', '=', Input::get('imm_id'))->update($data);
				$this->audit('Update');
				$this->api_data('update', 'immunizations', 'imm_id', Input::get('imm_id'));
				$result['message'] = "Immunization updated!";
			}
			echo json_encode($result);
		}
	}
	
	public function postEditImmunization1()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$medtext = '';
			if (Input::get('imm_vis')=='Yes') {
				$imm_vis = 'Yes';
				$medtext .= 'Consent obtained and CDC Vaccine Information Sheet (VIS) given to patient/caregiver.';
			} else {
				$imm_vis = '';
			}
			$eid = Session::get('eid');
			$data = array(
				'imm_immunization' => Input::get('imm_immunization'),
				'imm_sequence' => Input::get('imm_sequence'),
				'imm_body_site' => Input::get('imm_body_site'),
				'imm_route' => Input::get('imm_route'),
				'imm_dosage' => Input::get('imm_dosage'),
				'imm_dosage_unit' => Input::get('imm_dosage_unit'),
				'imm_lot' => Input::get('imm_lot'),
				'imm_expiration' => date('Y-m-d H:i:s', strtotime(Input::get('imm_expiration'))),
				'imm_date' => date('Y-m-d H:i:s', strtotime(Input::get('imm_date'))),
				'imm_elsewhere' => 'No',
				'imm_vis' => $imm_vis,
				'imm_manufacturer' => Input::get('imm_manufacturer'),
				'imm_provider' => Session::get('displayname'),
				'imm_cvxcode' => Input::get('imm_cvxcode'),
				'pid' => $pid,
				'eid' => $eid,
				'cpt' => Input::get('cpt')
			);	
			$medtext .= '<br>' . Input::get('imm_immunization') . '; Sequence: ' . Input::get('imm_sequence') . '; Dosage: ' . Input::get('imm_dosage') . ' ' . Input::get('imm_dosage_unit') . ' ' . Input::get('imm_route') . ' administered to the ' . Input::get('imm_body_site');
			$medtext .= '<br>Manufacturer: ' . Input::get('imm_manufacturer') . '; Lot number: ' . Input::get('imm_lot') . '; Expiration date: ' . Input::get('imm_expiration');
			if(Input::get('imm_id') == '') {
				$id = DB::table('immunizations')->insertGetId($data);
				$this->audit('Add');
				$this->api_data('add', 'immunizations', 'imm_id', $id);
				$vaccine_id = Input::get('vaccine_id');
				$inventory_result = DB::table('vaccine_inventory')
					->where('vaccine_id', '=', $vaccine_id)
					->first();
				$quantity = $inventory_result->quantity;
				$quantity = $quantity - 1;
				$inventory_data = array(
					'quantity' => $quantity
				);
				DB::table('vaccine_inventory')->where('vaccine_id', '=', $vaccine_id)->update($inventory_data);
				$this->audit('Update');
				$result =  array(
					'message' => "Immunization added!",
					'medtext' => $medtext
				);
			} else {
				DB::table('immunizations')->where('imm_id', '=', Input::get('imm_id'))->update($data);
				$this->audit('Update');
				$result =  array(
					'message' => "Immunization updated!",
					'medtext' => $medtext
				);
			}
			echo json_encode($result);
		}
	}
	
	public function postDeleteImmunization()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$this->chart_model->deleteImmunization(Input::get('imm_id'));
			$this->audit('Delete');
			$this->api_data('delete', 'immunizations', 'imm_id', Input::get('imm_id'));
			echo "Immunization deleted!";
		}
	}
	
	public function postGetImmNotes()
	{
		$pid = Session::get('pid');
		$result = DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->first();
		if (is_null($result->imm_notes) || $result->imm_notes == '') {
			echo "";
		} else {
			echo nl2br($result->imm_notes) . '<br><br>';
		}
	}
	
	public function postGetImmNotes1()
	{
		$pid = Session::get('pid');
		$result = DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->first();
		if (is_null($result->imm_notes) || $result->imm_notes == '') {
			echo "";
		} else {
			echo $result->imm_notes;
		}
	}
	
	public function postEditImmNotes()
	{
		$pid = Session::get('pid');
		$data = array(
			'imm_notes' => Input::get('imm_notes')
		);
		DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo "Immunization notes updated!";
	}
	
	public function postConsentImmunizations()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$row = Demographics::find($pid);
			$practice = Practiceinfo::find(Session::get('practice_id'));
			$pdfinfo = array(
				'name' => $row->firstname . ' ' . $row->lastname,
				'vaccine_list' => Input::get('vaccine_list'),
				'date' => date("M d, Y", time()),
				'practiceinfo' => $practice->practice_name
			);
			$pdfinfo['practiceinfo'] .= "\r" . $practice->street_address1;
			if ($practice['street_address2'] != '') {
				$pdfinfo['practiceinfo'] .= ', ' . $practice->street_address2;
			}
			$pdfinfo['practiceinfo'] .= "\r";
			$pdfinfo['practiceinfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . "\r";
			$pdfinfo['practiceinfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
			$input = __DIR__.'/../../public/vaccine_consent.pdf';
			$output = __DIR__."/../../public/temp/vaccine_consent_output_" . Session::get('user_id') . ".pdf";
			$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
				'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
				'<fields>'."\n";
			foreach($pdfinfo as $field => $val) {
				$data.='<field name="'.$field.'">'."\n";
				if(is_array($val)) {
					foreach($val as $opt)
						$data.='<value>'.$opt.'</value>'."\n";
				} else {
					$data.='<value>'.$val.'</value>'."\n";
				}
				$data.='</field>'."\n";
			}
			$data.='</fields>'."\n".
				'<ids original="'.md5($input).'" modified="'.time().'" />'."\n".
				'<f href="'.$input.'" />'."\n".
				'</xfdf>'."\n";
			$xfdf_fn= __DIR__.'/../../public/temp.xfdf';
			$xfp= fopen( $xfdf_fn, 'w' );
			if( $xfp ) {
			   fwrite( $xfp, $data );
			   fclose( $xfp );
			} else {
				$result_message = 'Error making xfdf!';
				echo $result_message;
				exit (0);
			}
			if (file_exists($output)) {
				unlink($output);
			}
			$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
			$commandpdf1 = escapeshellcmd($commandpdf);
			exec($commandpdf1);
			if (file_exists($output)) {
				echo "OK";
			} else {
				echo "Error generating consent!";
			}
		}
	}
	
	// HIPPA functions
	public function postRecordsRelease()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('hippa')
			->where('pid', '=', $pid)
			->where('other_hippa_id', '=', '0')
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
		$query1 = DB::table('hippa')
			->where('pid', '=', $pid)
			->where('other_hippa_id', '=', '0')
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
	
	public function postPrintChartSave()
	{
		$pid = Session::get('pid');
		$data = array(
			'hippa_date_release' => date('Y-m-d H:i:s', strtotime(Input::get('hippa_date_release'))),
			'pid' => $pid,
			'hippa_reason' => Input::get('hippa_reason'),
			'hippa_provider' => Input::get('hippa_provider'),
			'hippa_role' => Input::get('hippa_role'),
			'other_hippa_id' => 0,
			'practice_id' => Session::get('practice_id'),
			'address_id' => Input::get('address_id')
		);
		if (Input::get('hippa_id') != '') {
			$id = Input::get('hippa_id');
			DB::table('hippa')->where('hippa_id', '=', $id)->update($data);
			$this->audit('Update');
		} else {
			$id = DB::table('hippa')->insertGetId($data);
			$this->audit('Add');
		}
		echo $id;
	}
	
	public function postGetRelease($hippa_id)
	{
		$result = DB::table('hippa')->where('hippa_id', '=', $hippa_id)->where('other_hippa_id', '=', '0')->first();
		echo json_encode($result);
	}
	
	public function postGetReleaseStats()
	{
		$hippa_id = Input::get('hippa_id');
		$result = DB::table('hippa')->where('hippa_id', '=', $hippa_id)->where('other_hippa_id', '=', '0')->first();
		$text = "<strong>Date of Release: </strong>" . date('F jS, Y', $this->human_to_unix($result->hippa_date_release)) . "<br><strong>Reason: </strong>" . $result->hippa_reason . "<br><strong>To Whom: </strong>" . $result->hippa_provider;
		echo $text;
	}
	
	public function postPrintQueue($id)
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('hippa')
			->where('other_hippa_id', '=', $id)
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
		$query1 = DB::table('hippa')
			->where('other_hippa_id', '=', $id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$i = 0;
		foreach ($query1 as $row1) {
			$row = (array) $row1;
			if (isset($row['eid'])) {
				$result1 = Encounters::find($row['eid']);
				$row['description'] = $result1->encounter_cc;
				$row['date'] = $result1->encounter_DOS;
				$row['type'] = 'Encounter';
			}
			if (isset($row['t_messages_id'])) {
				$result2 = T_messages::find($row['t_messages_id']);
				$row['description'] = $result2->t_messages_subject;
				$row['date'] = $result2->t_messages_dos;
				$row['type'] = 'Telephone Messages';
			}
			if (isset($row['documents_id'])) {
				$result3 = Documents::find($row['documents_id']);
				$row['description'] = $result3->documents_desc . ' from ' . $result3->documents_from;
				$row['date'] = $result3->documents_date;
				$row['type'] = $result3->documents_type;
			}
			$response['rows'][$i]['id']=$row['hippa_id']; 
			$response['rows'][$i]['cell']=array($row['hippa_id'],$row['date'],$row['type'],$row['description']);
			$i++; 
		}
		echo json_encode($response);
	}
	
	public function postAddAllContact()
	{
		$data = array(
			'displayname' => Input::get('faxrecipient'),
			'fax' => Input::get('faxnumber')
		);
		DB::table('addressbook')->insert($data);
		$this->audit('Add');
		echo "Contact added!";
	}
	
	public function postFaxChart($hippa_id, $type)
	{
		$pid = Session::get('pid');
		$filename = $this->print_chart($pid, 'fax', $hippa_id, $type);
		$result['message'] = $this->fax_document($pid, 'Medical Record', 'yes', $filename, '', Input::get('faxnumber'), Input::get('faxrecipient'), '', 'yes');
		echo json_encode($result);
	}
	
	public function postDeleteChartItem()
	{
		DB::table('hippa')->where('hippa_id', '=', Input::get('hippa_id'))->delete();
		$this->audit('Delete');
		echo 'Item removed from queue!';
	}
	
	public function postClearQueue()
	{
		DB::table('hippa')->where('other_hippa_id', '=', Input::get('other_hippa_id'))->delete();
		$this->audit('Delete');
		echo 'Queue cleared!';
	}
	
	public function postPrintEncounters()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->where('encounter_signed', '=', 'Yes')
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
		$query1 = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->where('encounter_signed', '=', 'Yes')
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
	
	public function postPrintMessages()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('t_messages')
			->where('pid', '=', $pid)
			->where('practice_id', '=', $practice_id)
			->where('t_messages_signed', '=', 'Yes')
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
		$query1 = DB::table('t_messages')
			->where('pid', '=', $pid)
			->where('practice_id', '=', $practice_id)
			->where('t_messages_signed', '=', 'Yes')
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
	
	public function postAddPrintQueue()
	{
		$data = array(
			'documents_id' => Input::get('documents_id'),
			'other_hippa_id' => Input::get('hippa_id'),
			'pid' => Session::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		DB::table('hippa')->insert($data);
		$this->audit('Add');
		echo "Item added to queue!";
	}
	
	public function postAddPrintQueue1()
	{
		$data = array(
			'eid' => Input::get('eid'),
			'other_hippa_id' => Input::get('hippa_id'),
			'pid' => Session::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		DB::table('hippa')->insert($data);
		$this->audit('Add');
		echo "Item added to queue!";
	}
	
	public function postAddPrintQueue2()
	{
		$data = array(
			't_messages_id' => Input::get('t_messages_id'),
			'other_hippa_id' => Input::get('hippa_id'),
			'pid' => Session::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		DB::table('hippa')->insert($data);
		$this->audit('Add');
		echo "Item added to queue!";
	}
	
	public function postRecordsRequest()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('hippa_request')
			->where('pid', '=', $pid)
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
		$query1 = DB::table('hippa_request')
			->where('pid', '=', $pid)
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
	
	public function postRecordsRequestSave()
	{
		$pid = Session::get('pid');
		$arr = array();
		$data = array(
			'hippa_date_request' => date('Y-m-d H:i:s', strtotime(Input::get('hippa_date_request'))),
			'pid' => $pid,
			'request_reason' => Input::get('request_reason'),
			'request_type' => Input::get('request_type'),
			'request_to' => Input::get('request_to'),
			'practice_id' => Session::get('practice_id'),
			'address_id' => Input::get('address_id'),
			'history_physical' => Input::get('history_physical'),
			'lab_type' => Input::get('lab_type'),
			'lab_date' => Input::get('lab_date'),
			'op' => Input::get('op'),
			'accident_f' => Input::get('accident_f'),
			'accident_t' => Input::get('accident_t'),
			'other' => Input::get('other')
		);
		if (Input::get('hippa_request_id') != '') {
			$id = Input::get('hippa_request_id');
			DB::table('hippa_request')->where('hippa_request_id', '=', $id)->update($data);
			$this->audit('Update');
		} else {
			$id = DB::table('hippa_request')->insertGetId($data);
			$this->audit('Add');
		}
		$url = time() . "_" . Session::get('user_id');
		$file_path = __DIR__."/../../public/temp/hippa_request_" . $url . ".pdf";
		$html = $this->page_hippa_request($id);
		$this->generate_pdf($html, $file_path);
		echo $url;
	}
	
	public function postRequestReceived($id)
	{
		$data = array(
			'received' => 'Yes'
		);
		DB::table('hippa_request')->where('hippa_request_id', '=', $id)->update($data);
		$this->audit('Update');
		echo 'Marked as recieved!';
	}
	
	// Billing functions
	public function postBillingEncounters()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('encounters')
			->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
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
		$query1 = DB::table('encounters')
			->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$i = 0;
		foreach ($query1 as $row_object) {
			$row = (array) $row_object;
			$query2 = DB::table('billing_core')->where('eid', '=', $row['eid'])->get();
			if ($query2) {
				$charge = 0;
				$payment = 0;
				foreach ($query2 as $row1) {
					$charge += $row1->cpt_charge * $row1->unit;
					$payment += $row1->payment;
				}
				$row['balance'] = $charge - $payment;
				$row['charges'] = $charge;
			} else {
				$row['balance'] = 0;
				$row['charges'] = 0;
			}
			$response['rows'][$i]['id'] = $row['eid']; 
			$response['rows'][$i]['cell'] = array($row['eid'],$row['encounter_DOS'],$row['encounter_cc'],$row['charges'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
	}
	
	public function postBillingOther()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('billing_core')
			->where('pid', '=', $pid)
			->where('eid', '=', '0')
			->where('payment', '=', '0')
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
		$query1 = DB::table('billing_core')
			->where('pid', '=', $pid)
			->where('eid', '=', '0')
			->where('payment', '=', '0')
			->where('practice_id', '=', $practice_id)
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$i = 0;
		foreach ($query1 as $row_object) {
			$row = (array) $row_object;
			$query2 = DB::table('billing_core')->where('other_billing_id', '=', $row['other_billing_id'])->get();
			if ($query2) {
				$charge = $row['cpt_charge'] * $row['unit'];
				$payment = 0;
				foreach ($query2 as $row1) {
					$payment += $row1->payment;
				}
				$row['balance'] = $charge - $payment;
			} else {
				$row['balance'] = 0;
			}
			$response['rows'][$i]['id'] = $row['other_billing_id']; 
			$response['rows'][$i]['cell'] = array($row['other_billing_id'],$row['dos_f'],$row['reason'],$row['cpt_charge'] * $row['unit'],$row['balance']);
			$i++; 
		}
		echo json_encode($response);
	}
	
	public function postGetPayment()
	{
		$result = DB::table('billing_core')->where('billing_core_id', '=', Input::get('id'))->first();
		echo json_encode($result);
	}
	
	public function postBillingPaymentHistory1($eid)
	{
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('billing_core')
			->where('eid', '=', $eid)
			->where('payment', '!=', '0')
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
		$query1 = DB::table('billing_core')
			->where('eid', '=', $eid)
			->where('payment', '!=', '0')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$total = 0;
		if ($query1) {
			$response['rows'] = $query1;
			foreach ($query1 as $add) {
				$total += $add->payment;
			}
		} else {
			$response['rows'] = '';
		}
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
	}
	
	public function postBillingPaymentHistory2($id)
	{
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('billing_core')
			->where('other_billing_id', '=', $id)
			->where('payment', '!=', '0')
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
		$query1 = DB::table('billing_core')
			->where('other_billing_id', '=', $id)
			->where('payment', '!=', '0')
			->orderBy($sidx, $sord)
			->skip($start)
			->take($limit)
			->get();
		$response['page'] = $page;
		$response['total'] = $total_pages;
		$response['records'] = $count;
		$total = 0;
		$total = 0;
		if ($query1) {
			$response['rows'] = $query1;
			foreach ($query1 as $add) {
				$total += $add->payment;
			}
		} else {
			$response['rows'] = '';
		}
		$response['userdata']['dos_f'] = 'Total Payments:';
		$response['userdata']['payment'] = $total;
		echo json_encode($response);
	}
	
	public function postPaymentSave()
	{
		$encounter = Encounters::find(Input::get('eid'));
		$id = Input::get('billing_core_id');
		$query = Billing_core::find($id);
		if ($encounter) {
			$pid = $encounter->pid;
		} else {
			$query1 = DB::table('billing_core')->where('other_billing_id', '=', Input::get('other_billing_id'))->first();
			$pid = $query1->pid;
		}
		$data = array(
			'eid' => Input::get('eid'),
			'other_billing_id' => Input::get('other_billing_id'),
			'pid' => $pid,
			'dos_f' => Input::get('dos_f'),
			'payment' => Input::get('payment'),
			'payment_type' => Input::get('payment_type'),
			'practice_id' => Session::get('practice_id')
		);
		if ($query) {
			DB::table('billing_core')->where('billing_core_id', '=', $id)->update($data);
			$this->audit('Update');
			$result['message'] = 'Payment Updated';
		} else {
			DB::table('billing_core')->insert($data);
			$this->audit('Add');
			$result['message'] = 'Payment Added';
		}
		$result['eid'] = Input::get('eid');
		$result['other_billing_id'] = Input::get('other_billing_id');
		echo json_encode($result);
	}
	
	public function postDeletePayment1()
	{
		$pid = Session::get('pid');
		$id = Input::get('id');
		$result = Billing_core::find($id);
		$arr['id'] = $result->eid;
		DB::table('billing_core')->where('billing_core_id', '=', $id)->delete();
		$this->audit('Delete');
		$query2 = DB::table('billing_core')->where('eid', '=', $result->eid)->get();
		if ($query2) {
			$charge = 0;
			$payment = 0;
			foreach ($query2 as $row1) {
				$charge += $row1->cpt_charge * $row1->unit;
				$payment += $row1->payment;
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
	}
	
	public function postDeletePayment2()
	{
		$pid = Session::get('pid');
		$id = Input::get('id');
		$result = Billing_core::find($id);
		$arr['id'] = $result->other_billing_id;
		DB::table('billing_core')->where('billing_core_id', '=', $id)->delete();
		$this->audit('Delete');
		$query2 = DB::table('billing_core')->where('other_billing_id', '=', $result->other_billing_id)->get();
		$result2 = Billing_core::find($result->other_billing_id);
		if ($query2) {
			$charge = $result2->cpt_charge * $result2->unit;
			$payment = 0;
			foreach ($query2 as $row1) {
				$payment += $row1->payment;
			}
			$arr['balance'] = $charge - $payment;
		} else {
			$arr['balance'] = 0;
		}
		$arr['message'] = "Payment deleted!";
		echo json_encode($arr);
	}
	
	public function postTotalBalance()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$query1 = DB::table('encounters')->where('pid', '=', $pid)->where('addendum', '=', 'n')->where('practice_id', '=', $practice_id)->get();
		$i = 0;
		if ($query1) {
			$balance1 = 0;
			foreach ($query1 as $row1) {
				$query1a = DB::table('billing_core')->where('eid', '=', $row1->eid)->get();
				if ($query1a) {
					$charge1 = 0;
					$payment1 = 0;
					foreach ($query1a as $row1a) {
						$charge1 += $row1a->cpt_charge * $row1a->unit;
						$payment1 += $row1a->payment;
					}
					$balance1 += $charge1 - $payment1;
				} else {
					$balance1 += 0;
				}
				$i++; 
			}
		} else {
			$balance1 = 0;
		}
		$query2 = DB::table('billing_core')->where('pid', '=', $pid)->where('eid', '=', '0')->where('payment', '=', '0')->where('practice_id', '=', $practice_id)->get();
		$j = 0;
		$charge2 = 0;
		$payment2 = 0;
		if ($query2) {
			foreach ($query2 as $row2) {
				$charge2 += $row2->cpt_charge * $row2->unit;
				$query2a = DB::table('billing_core')->where('other_billing_id', '=', $row2->billing_core_id)->where('payment', '!=', '0')->get();
				if ($query2a) {
					foreach ($query2a as $row2a) {
						$payment2 += $row2a->payment;
					}
				}
				$balance2 = $charge2 - $payment2;
				$j++; 
			}
		} else {
			$balance2 = 0;
		}
		$total_balance = $balance1 + $balance2;
		$result = DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', $practice_id)->first();
		if (is_null($result->billing_notes) || $result->billing_notes == '') {
			$billing_notes = "None.";
		} else {
			$billing_notes = nl2br($result->billing_notes);
		}
		$note = "<strong>Total Balance: $" .  number_format($total_balance, 2, '.', ',') . "</strong><br><br><strong>Billing Notes: </strong>" . $billing_notes . "<br>";
		$cc = DB::table('demographics')->where('pid', '=', $pid)->first();
		if ($cc->creditcard_number != '') {
			Crypt::setKey($cc->creditcard_key);
			$note .= "<br><strong>Credit Card on File: </strong><br>Number: " . Crypt::decrypt($cc->creditcard_number) . "<br>Type: " . $cc->creditcard_type . "<br>Expiration Date: " . $cc->creditcard_expiration . "<br>";
			Crypt::setKey('YourSecretKey!!!');
		}
		echo $note;
	}
	
	public function postBillingSave($eid)
	{
		$pid = Session::get('pid');
		$id = Input::get('billing_core_id');
		$query = Billing_core::find($id);
		$icd_array = Input::get('icd_pointer');
		$icd_pointer = implode("", $icd_array);
		$data = array(
			'eid' => $eid,
			'pid' => $pid,
			'cpt' => Input::get('cpt'),
			'cpt_charge' => Input::get('cpt_charge'),
			'icd_pointer' => $icd_pointer,
			'unit' => Input::get('unit'),
			'modifier' => Input::get('modifier'),
			'dos_f' => Input::get('dos_f'),
			'dos_t' => Input::get('dos_t'),
			'payment' => '0',
			'billing_group' => '1',
			'practice_id' => Session::get('practice_id')
		);
		if ($query) {
			DB::table('billing_core')->where('billing_core_id', '=', $id)->update($data);
			$this->audit('Update');
			$result = 'Billing Updated';
		} else {
			DB::table('billing_core')->insert($data);
			$this->audit('Add');
			$result = 'Billing Added';
		}
		echo $result;
	}
	
	public function postBillingSave1()
	{
		$result = $this->billing_save_common(Input::get('insurance_id_1'), Input::get('insurance_id_2'), Input::get('eid'));
		echo $result;
	}
	
	public function postRemoveCpt()
	{
		DB::table('billing_core')->where('billing_core_id', '=', Input::get('billing_core_id'))->delete();
		$this->audit('Delete');
		echo "Row deleted.";
	}
	
	public function postGetCptCharge()
	{
		$result = DB::table('cpt')->where('cpt', '=', Input::get('cpt'))->first();
		if ($result) {
			$arr = $result->cpt_charge;
		} else {
			$arr = '';
		}
		echo $arr;
	}
	
	public function postBillingOtherSave()
	{
		$id = Input::get('other_billing_id');
		$query = Billing_core::find($id);
		$data = array(
			'eid' => '0',
			'pid' => Session::get('pid'),
			'dos_f' => Input::get('dos_f'),
			'cpt_charge' => Input::get('cpt_charge'),
			'reason' => Input::get('reason'),
			'unit' => '1',
			'payment' => '0',
			'practice_id' => Session::get('practice_id')
		);
		if ($query) {
			DB::table('billing_core')->where('billing_core_id', '=', $id)->update($data);
			$this->audit('Update');
			$result['message'] = 'Miscellaneous Bill Updated';
		} else {
			$id1 = DB::table('billing_core')->insertGetId($data);
			$this->audit('Add');
			$data1 = array(
				'other_billing_id' => $id1
			);
			DB::table('billing_core')->where('billing_core_id', '=', $id1)->update($data1);
			$this->audit('Update');
			$result['message'] = 'Miscellaneous Bill Added';
		}
		echo json_encode($result);
	}
	
	public function postProcedureCodes($eid='')
	{
		if ($eid == '') {
			$eid = Session::get('eid');
		}
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = 'billing_core.' . Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('billing_core')
			->join('cpt_relate', 'billing_core.cpt', '=', 'cpt_relate.cpt')
			->where('billing_core.eid' , '=', $eid)
			->where('cpt_relate.practice_id', '=', Session::get('practice_id'))
			->select('billing_core.*', 'cpt_relate.cpt_description')
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
		$query1 = DB::table('billing_core')
			->join('cpt_relate', 'billing_core.cpt', '=', 'cpt_relate.cpt')
			->where('billing_core.eid' , '=', $eid)
			->where('cpt_relate.practice_id', '=', Session::get('practice_id'))
			->select('billing_core.*', 'cpt_relate.cpt_description')
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
	
	public function postDefineIcd($id='')
	{
		if ($id == '') {
			$eid = Session::get('eid');
		} else {
			$eid = $id;
		}
		$icd = Input::get('icd');
		$icd_array = str_split($icd);
		$arr['item'] = '';
		$assessment_array = array(
			'A' => '1',
			'B' => '2',
			'C' => '3',
			'D' => '4',
			'E' => '5',
			'F' => '6',
			'G' => '7',
			'H' => '8',
			'I' => '9',
			'J' => '10',
			'K' => '11',
			'L' => '12'
		);
		foreach ($icd_array as $icd1) {
			if ($icd1) {
				$name = 'assessment_' . $assessment_array[$icd1];
				$result = DB::table('assessment')->where('eid', '=', $eid)->first();
				$result1 = (array) $result;
				$arr['item'] .= 'Diagnosis ' . $icd1 . ': ' . $result1[$name] . '<br>';
			}
		}
		echo json_encode($arr);
	}
	
	public function postGetBilling($eid='')
	{
		if ($eid == '') {
			$eid = Session::get('eid');
		}
		$data = Assessment::find($eid);
		if ($data) {
			$data1['message'] = "OK";
			if ($data->assessment_1 != '') {
				$data1['A'] = "A - " . $data->assessment_1;
			} else {
				$data1['message'] = "No diagnoses available.";
			}
			if ($data->assessment_2 != '') {
				$data1['B'] = "B - " . $data->assessment_2;
			}
			if ($data->assessment_3 != '') {
				$data1['C'] = "C - " . $data->assessment_3;
			}
			if ($data->assessment_4 != '') {
				$data1['D'] = "D - " . $data->assessment_4;
			}
			if ($data->assessment_5 != '') {
				$data1['E'] = "E - " . $data->assessment_5;
			}
			if ($data->assessment_6 != '') {
				$data1['F'] = "F - " . $data->assessment_6;
			}
			if ($data->assessment_7 != '') {
				$data1['G'] = "G - " . $data->assessment_7;
			}
			if ($data->assessment_8 != '') {
				$data1['H'] = "H - " . $data->assessment_8;
			}
			if ($data->assessment_9 != '') {
				$data1['I'] = "I - " . $data->assessment_9;
			}
			if ($data->assessment_10 != '') {
				$data1['J'] = "J - " . $data->assessment_10;
			}
			if ($data->assessment_11 != '') {
				$data1['K'] = "K - " . $data->assessment_11;
			}
			if ($data->assessment_12 != '') {
				$data1['L'] = "L - " . $data->assessment_12;
			}
		} else {
			$data1['message'] = "No diagnoses available.";
		}
		echo json_encode($data1);
	}
	
	public function postGetPrevention($eid)
	{
		$pid = Session::get('pid');
		$row = Demographics::find($pid);
		$dob1 = $this->human_to_unix($row->DOB);
		$encounterInfo = Encounters::find($eid);
		$dos1 = $this->human_to_unix($encounterInfo->encounter_DOS);
		$agediff = $dos1- $dob1;
		if ($agediff < 31556926) {
			$data['prevent_established1'] = '99391';
			$data['prevent_new1'] = '99381';
		}
		if ($agediff >= 31556926 && $agediff < 157784630) {
			$data['prevent_established1'] = '99392';
			$data['prevent_new1'] = '99382';
		}
		if ($agediff >= 157784630 && $agediff < 378683112) {
			$data['prevent_established1'] = '99393';
			$data['prevent_new1'] = '99383';
		}
		if ($agediff >= 378683112 && $agediff < 568024668) {
			$data['prevent_established1'] = '99394';
			$data['prevent_new1'] = '99384';
		}
		if ($agediff >= 568024668 && $agediff < 1262277040) {
			$data['prevent_established1'] = '99395';
			$data['prevent_new1'] = '99385';
		}
		if ($agediff >= 1262277040 && $agediff < 2051200190) {
			$data['prevent_established1'] = '99396';
			$data['prevent_new1'] = '99386';
		}
		if ($agediff >= 2051200190) {
			$data['prevent_established1'] = '99397';
			$data['prevent_new1'] = '99387';
		}
		echo json_encode($data);
	}
	
	public function postGetInsuranceId($eid='')
	{
		if ($eid == '') {
			$eid = Session::get('eid');
		}
		$data = DB::table('billing')->where('eid', '=', $eid)->first();
		if (!$data) {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postGetInsuranceInfo()
	{
		$insurance_id_1 = Input::get('insurance_id_1');
		$insurance_id_2 = Input::get('insurance_id_2');
		if ($insurance_id_1 != '') {
			if ($insurance_id_1 == '0') {
				$arr['result1'] = 'Self pay; no insurance.';
				$arr['result2'] = 'None chosen.';
			} else {
				$result1 = Insurance::find($insurance_id_1);
				if ($result1) {
					$arr['result1'] = $result1->insurance_plan_name;
				} else {
					$arr['result1'] = 'None chosen.';
				}
			}
		} else {
			$arr['result1'] = 'None chosen.';
		}
		if ($insurance_id_2 != '') {
			$result2 = Insurance::find($insurance_id_2);
			if ($result2) {
				$arr['result2'] = $result2->insurance_plan_name;
			} else {
				$arr['result2'] = 'None chosen.';
			}	
		} else {
			$arr['result2'] = 'None chosen.';
		}
		echo json_encode($arr);
	}
	
	public function postGetAssessment($eid)
	{
		$data = DB::table('assessment')->where('eid', '=', $eid)->first();
		if (!$data) {
			$data = '';
		}
		echo json_encode($data);
	}
	
	public function postGetEncounterDate($eid)
	{
		$data = DB::table('encounters')->where('eid', '=', $eid)->first();
		echo $data->encounter_DOS;
	}
	
	public function postGetBillingNotes()
	{
		$result = DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		if (is_null($result->billing_notes) || $result->billing_notes == '') {
			echo "";
		} else {
			echo $result->billing_notes;
		}
	}
	
	public function postEditBillingNotes()
	{
		$data = array(
			'billing_notes' => Input::get('billing_notes')
		);
		DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo "Billing notes updated!";
	}
	
	public function postDeleteOtherBill()
	{
		if (Input::get('billing_core_id') == '0') {
			echo "Incorrect Payment ID, try again!";
			exit (0);
		} else {
			DB::table('billing_core')->where('billing_core_id', '=', Input::get('billing_core_id'))->delete();
			$this->audit('Delete');
			DB::table('billing_core')->where('other_billing_id', '=', Input::get('billing_core_id'))->delete();
			$this->audit('Delete');
			echo "Miscellaneous bill deleted!";
			exit (0);
		}
	}
	
	public function postUpdateCptCharge()
	{
		$data = array(
			'cpt_charge' => Input::get('cpt_charge')
		);
		$cpt = Input::get('cpt');
		$row = DB::table('cpt_relate')->where('cpt', '=', $cpt)->where('practice_id', '=', Session::get('practice_id'))->first();
		if ($row) {
			DB::table('cpt_relate')->where('cpt_relate_id', '=', $row->cpt_relate_id)->update($data);
			$this->audit('Update');
		} else {
			$row1 = DB::table('cpt')->where('cpt', '=', $cpt)->first();
			$data['cpt_description'] = $row1->cpt_description;
			$data['cpt'] = $row1->cpt;
			$data['practice_id'] = Session::get('practice_id');
			DB::table('cpt_relate')->insert($data);
			$this->audit('Add');
		}
		echo 'CPT charge updated!';
	}
	
	// MTM functions
	public function postMtm()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('mtm')
			->where('pid', '=', $pid)
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
		$query1 = DB::table('mtm')
			->where('pid', '=', $pid)
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
	
	public function postEditMtm()
	{
		$data = array(
			'mtm_description' => Input::get('mtm_description'),
			'mtm_recommendations' => Input::get('mtm_recommendations'),
			'mtm_beneficiary_notes' => Input::get('mtm_beneficiary_notes'),
			'pid' => Session::get('pid'),
			'mtm_action' => Input::get('mtm_action'),
			'mtm_outcome' => Input::get('mtm_outcome'),
			'mtm_related_conditions' => Input::get('mtm_related_conditions'),
			'mtm_duration' => Input::get('mtm_duration'),
			'practice_id' => Session::get('practice_id')
		);
		if (Input::get('mtm_date_completed') != '') {
			$data['mtm_date_completed'] = date('Y-m-d H:i:s', strtotime(Input::get('mtm_date_completed')));
			$data['complete'] = 'yes';
		} else {
			$data['mtm_date_completed'] = '';
			$data['complete'] = 'no';
		}
		if (Input::get('oper') == 'edit') {
			DB::table('mtm')->where('mtm_id', '=', Input::get('id'))->update($data);
			$this->audit('Update');
		}
		if (Input::get('oper') == 'add') {
			DB::table('mtm')->insert($data);
			$this->audit('Add');
		}
		if (Input::get('oper') == 'del') {
			$id_del = explode(",",Input::get('id'));
			foreach ($id_del as $id_del1) {
				DB::table('mtm')->where('mtm_id', '=', $id_del1)->delete();
				$this->audit('Delete');
			}
		}
	}
	
	public function postPrintMtm()
	{
		ini_set('memory_limit','196M');
		$pid = Session::get('pid');
		$result = Practiceinfo::find(Session::get('practice_id'));
		$directory = $result->documents_dir . $pid . "/mtm";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		$input = "";
		$file_path_cp = $directory . '/cp.pdf';
		$html_cp = $this->page_mtm_cp($pid)->render();
		if (file_exists($file_path_cp)) {
			unlink($file_path_cp);
		}
		$this->generate_pdf($html_cp, $file_path_cp, 'mtmfooterpdf', '', '1');
		while(!file_exists($file_path_cp)) {
			sleep(2);
		}
		$input = $file_path_cp;
		$file_path_map = $directory . '/map.pdf';
		$html_map = $this->page_mtm_map($pid)->render();
		if (file_exists($file_path_map)) {
			unlink($file_path_map);
		}
		$this->generate_pdf($html_map, $file_path_map, 'mtmfooterpdf', '', '1');
		while(!file_exists($file_path_map)) {
			sleep(2);
		}
		$input .= " " . $file_path_map;
		$file_path_pml = $directory . '/pml.pdf';
		$html_pml = $this->page_mtm_pml($pid)->render();
		if (file_exists($file_path_pml)) {
			unlink($file_path_pml);
		}
		$this->generate_pdf($html_pml, $file_path_pml, 'mtmfooterpdf', 'mtmheaderpdf', '1', Session::get('pid'));
		while(!file_exists($file_path_pml)) {
			sleep(2);
		}
		$input .= " " . $file_path_pml;
		$file_path = $result->documents_dir . $pid . "/mtm_" . time() . ".pdf";
		$commandpdf1 = "pdftk " . $input . " cat output " . $file_path;
		$commandpdf2 = escapeshellcmd($commandpdf1);
		exec($commandpdf2);
		while(!file_exists($file_path)) {
			sleep(2);
		}
		$pages_data = array(
			'documents_url' => $file_path,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => 'Medication Therapy Management Letter for ' . Session::get('ptname'),
			'documents_from' => Session::get('displayname'),
			'documents_viewed' => Session::get('displayname'),
			'documents_date' => date('Y-m-d H:i:s', time())
		);
		$arr['id'] = DB::table('documents')->insertGetId($pages_data);
		$this->audit('Add');
		$arr['message'] = 'OK';
		echo json_encode($arr);
	}
	
	public function postPrintMtmProvider($type)
	{
		ini_set('memory_limit','196M');
		$pid = Session::get('pid');
		$result = Practiceinfo::find(Session::get('practice_id'));
		$directory = $result->documents_dir . $pid;
		$file_path_provider = $directory . '/mtm_' . time() . '_provider.pdf';
		$html_provider = $this->page_mtm_provider($pid)->render();
		if (file_exists($file_path_provider)) {
			unlink($file_path_provider);
		}
		$this->generate_pdf($html_provider, $file_path_provider, 'footerpdf', '', '1');
		while(!file_exists($file_path_provider)) {
			sleep(2);
		}
		$pages_data = array(
			'documents_url' => $file_path_provider,
			'pid' => $pid,
			'documents_type' => 'Letters',
			'documents_desc' => 'Medication Therapy Management Provider Letter for ' . Session::get('ptname'),
			'documents_from' => Session::get('displayname'),
			'documents_viewed' => Session::get('displayname'),
			'documents_date' => date('Y-m-d H:i:s', time())
		);
		$arr['id'] = DB::table('documents')->insertGetId($pages_data);
		$this->audit('Add');
		if ($type == "print") {
			$arr['message'] = 'OK';
		}
		if ($type == "fax") {
			$arr['message'] = $this->fax_document($pid, 'MTM Provider Letter', 'yes', $file_path_provider, '', Input::get('faxnumber'), Input::get('faxrecipient'), '', 'yes');
		}
		echo json_encode($arr);
	}
	
	public function postEncounterMtm()
	{
		$practice_id = Session::get('practice_id');
		$pid = Session::get('pid');
		$query = DB::table('mtm')->where('pid', '=', $pid)->where('complete', '=', 'no')->where('practice_id', '=', $practice_id)->get();
		$data['value'] = "";
		$data['duration'] = "";
		if ($query) {
			$data['value'] = "Medication Therapy Management Topics and Recommendations:\n";
			foreach ($query as $row) {
				$data['value'] .= "Topic: " . $row->mtm_description . "\n";
				$data['value'] .= "Recommendations: " . $row->mtm_recommendations . "\n";
				if ($row->mtm_beneficiary_notes != '') {
					$data['value'] .= "Patient Notes: " . $row->mtm_beneficiary_notes . "\n";
				}
				if ($row->mtm_action != '') {
					$data['value'] .= "Actions Taken: " . $row->mtm_action . "\n";
				}
				if ($row->mtm_outcome != '') {
					$data['value'] .= "Outcome: " . $row->mtm_outcome . "\n";
				}
				if ($row->mtm_duration != '') {
					$data['duration'] += str_replace(" minutes", "", $row->mtm_duration);
				}
				$data['value'] .= "\n";
			}
		}
		echo json_encode($data);
	}
	
	// Labs, Radiology, Cardiopulmonary, and Referral Orders functions
	public function postOrdersList($type)
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$t_messages_id = Input::get('t_messages_id');
		if ($t_messages_id == '') {
			$t_messages_id = '0';
		}
		$query = DB::table('orders')
			->join('addressbook', 'orders.address_id', '=', 'addressbook.address_id')
			->where('pid', '=', $pid)
			->where('orders_' . $type, '!=', '');
		if ($t_messages_id != '0' || $t_messages_id != 'all') {
			$query->where('t_messages_id', '=', $t_messages_id);
		} else {
			$eid = Session::get('eid');
			if ($eid == FALSE) {
				$eid = '0';
			}
			$query->where('eid', '=', $eid);
		}
		$result = $query->get();
		if($result) { 
			$count = count($result);
			$total_pages = ceil($count/$limit); 
		} else { 
			$count = 0;
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start < 0) $start = 0;
		$query1 = $query->orderBy($sidx, $sord)
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
	
	public function postOrderType($id)
	{
		$data = Orders::find($id)->toArray();
		if ($data['orders_labs'] != '') {
			$data['label'] = 'messages_lab';
		}
		if ($data['orders_radiology'] != '') {
			$data['label'] = 'messages_rad';
		}
		if ($data['orders_cp'] != '') {
			$data['label'] = 'messages_cp';
		}
		echo json_encode($data);
	}
	
	public function postAddressdefine()
	{
		$row = Addressbook::find(Input::get('address_id'));
		$result['item'] = $row->displayname . ' (' . Input::get('address_id') . ')';
		echo json_encode($result);
	}
	
	public function postImportOrders($type)
	{
		$pid = Session::get('pid');
		$t_messages_id = Input::get('t_messages_id');
		$field = 'orders_' . $type;
		if ($type == 'referrals') {
			$text = 'Referral';
		} else {
			$text = 'Orders';
		}
		$query = DB::table('orders')
			->where('pid', '=', $pid)
			->where($field, '!=', '')
			->where('t_messages_id', '=', $t_messages_id)
			->get();
		$result = "";
		if ($query) {
			foreach ($query as $row) {
				$row1 = Addressbook::find($row->address_id);
				$result .= $text . ' sent to ' . $row1->displayname . ': '. $row->$field . "\n\n"; 
			}
		} 
		echo $result;
	}
	
	public function postDeleteOrders($type)
	{
		DB::table('orders')->where('orders_id', '=', Input::get('orders_id'))->delete();
		$this->audit('Delete');
		$result = $type . " order deleted.";
		echo $result;
	}
	
	// $type can be labs, radiology, cp, referrals
	public function postAddOrders($type)
	{
		$pid = Session::get('pid');
		$t_messages_id = Input::get('t_messages_id');
		$eid = Input::get('eid');
		if ($type == 'labs') {
			$type1 = 'Laboratory';
			$type2 = 'Laboratory results pending';
		}
		if ($type == 'radiology') {
			$type1 = 'Radiology';
			$type2 = 'Radiology results pending';
		}
		if ($type == 'cp') {
			$type1 = 'Cardiopulmonary';
			$type2 = 'Cardiopulmonary results pending';
		}
		if ($type == 'referrals') {
			$type1 = 'Referral';
			$type2 = 'Referral pending';
		}
		$provider_query = User::find(Input::get('id'));
		$data = array(
			'orders_labs' => '',
			'orders_labs_obtained' => '',
			'orders_labs_icd' => '',
			'address_id' => Input::get('address_id'),
			'orders_completed' => 'No',
			'orders_radiology' => '',
			'orders_radiology_icd' => '',
			'orders_referrals' => '',
			'orders_referrals_icd' => '',
			'orders_cp' => '',
			'orders_cp_icd' => '',
			'encounter_provider' => $provider_query->displayname,
			'pid' => $pid,
			'eid' => $eid,
			'orders_insurance' => Input::get('orders_insurance'),
			't_messages_id' => $t_messages_id,
			'id' => Input::get('id'),
			'orders_pending_date' => date('Y-m-d H:i:s', strtotime(Input::get('orders_pending_date')))
		);
		$data['orders_' . $type] = Input::get('orders_' . $type);
		$data['orders_' . $type . "_icd"] = Input::get('orders_' . $type . '_icd');
		if ($type == 'labs') {
			$data['orders_labs_obtained'] = Input::get('orders_labs_obtained');
		}
		$text = "Orders";
		if ($type == 'referrals') {
			$text = "Referrals";
		}
		$orders_id = Input::get('orders_id');
		$row = Addressbook::find(Input::get('address_id'));
		$description = $text . ' sent to ' . $row->displayname . ': '. Input::get('orders_' . $type);
		if (strtotime(Input::get('orders_pending_date')) > time() && $type != 'referrals') {
			$type2 .= " - NEED TO OBTAIN";
		}
		if ($orders_id == '') {
			$add = DB::table('orders')->insertGetId($data);
			$this->audit('Add');
			$data1 = array(
				'alert' => $type2,
				'alert_description' => $description,
				'alert_date_active' => date('Y-m-d H:i:s', time()),
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => Session::get('user_id'),
				'orders_id' => $add,
				'pid' => $pid,
				'practice_id' => Session::get('practice_id'),
				'alert_send_message' => 'n'
			);
			DB::table('alerts')->insert($data1);
			$this->audit('Add');
			$result['message'] = $type1 . " orders saved!";
			$result['id'] = $add;
			$result['choice'] = 'Choose an action for the order, reference number ' . $add;
			$result['pending'] = $description;
		} else {
			DB::table('orders')->where('orders_id', '=', $orders_id)->update($data);
			$this->audit('Update');
			$row1 = DB::table('alerts')->where('orders_id', '=', $orders_id)->first();
			$data1 = array(
				'alert' => $type2,
				'alert_description' => $description,
				'alert_date_active' => date('Y-m-d H:i:s', time()),
				'alert_date_complete' => '',
				'alert_reason_not_complete' => '',
				'alert_provider' => Session::get('user_id'),
				'pid' => $pid,
				'practice_id' => Session::get('practice_id')
			);
			DB::table('alerts')->where('alert_id', '=', $row1->alert_id)->update($data1);
			$this->audit('Update');
			$result['message'] = $type1 . " orders updated!";
			$result['id'] = $orders_id;
			$result['choice'] = 'Choose an action for the order, reference number ' . $orders_id;
			$result['pending'] = $description;
		}
		echo json_encode($result);
	}
	
	public function postGetRefTemplatesList()
	{
		$user_id = Session::get('user_id');
		$gender = Session::get('gender');
		if ($gender == 'male') {
			$sex = 'm';
		} elseif (Session::get('gender') == 'female') {
			$sex = 'f';
		} else {
			$sex = 'u';
		}
		$result = DB::table('templates')
			->where('user_id', '=', $user_id)
			->orWhere('user_id', '=', '0')
			->where('sex', '=', $sex)
			->where('category', '=', 'referral')
			->get();
		$data['options'] = array();
		foreach ($result as $row) {
			$id = $row->template_id;
			if ($row->template_name == 'Global Default') {
				if ($row->group == 'referral') {
					$name = 'Referral';
				}
				if ($row->group == 'consultation') {
					$name = 'Consultation';
				}
				if ($row->group == 'pt') {
					$name = 'Physical Therapy';
				}
				if ($row->group == 'massage') {
					$name = 'Massage Therapy';
				}
				if ($row->group == 'sleep_study') {
					$name = 'Sleep Study';
				}
			} else {
				$name = $row->template_name;
			}
			$data['options'][$id] = $name;
		}
		echo json_encode($data);
	}
	
	public function postGetRefTemplate($id)
	{
		$row = DB::table('templates')->where('template_id', '=', $id)->first();
		$data = unserialize($row->array);
		echo json_encode($data);
	}
	
	public function postMakeReferral()
	{
		$pid = Session::get('pid');
		$query = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		$result['meds'] = array();
		if ($query) {
			foreach ($query as $row) {
				$result['meds'][] = $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency . ' for ' . $row->rxl_reason;
			}
		} else {
			$result['meds'][] = 'None.';
		}
		$query1 = DB::table('issues')->where('pid', '=', $pid)->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$result['issues'] = array();
		if ($query1) {
			foreach ($query1 as $row1) {
				$result['issues'][] = $row1->issue;
			}
		} else {
			$result['issues'][] = 'None.';
		}
		$query2 = DB::table('allergies')->where('pid', '=', $pid)->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$result['allergies'] = array();
		if ($query2) {
			foreach ($query2 as $row2) {
				$result['allergies'][] = $row2->allergies_med . ' - ' . $row2->allergies_reaction;
			}
		} else {
			$result['allergies'][] = 'No known allergies.';
		}
		$result['displayname'] = Session::get('displayname');
		echo json_encode($result);
	}
	
	public function postElectronicOrders()
	{
		$pid = Session::get('pid');
		$orders_id = Input::get('orders_id');
		$row = Orders::find($orders_id);
		$row1 = Addressbook::find($row->address_id);
		if ($row1->electronic_order == '') {
			echo "Laboratory provider is not configured for electronic order entry.  Please use an alternate method for delivery.";
			exit (0);
		} else {
			$row2 = Demographics::find($pid);
			$row3 = User::find($row->id);
			$row4 = Providers::find($row->id);
			if ($row1->electronic_order == 'PeaceHealth') {
				$date = date('YmdHi');
				$dob = date('Ymd', $this->human_to_unix($row2->DOB));
				$order_date = date('YmdHi', $this->human_to_unix($row->orders_pending_date));
				$middle = substr($row3->middle, 0, 1);
				$pname = substr($row3->lastname, 0, 5) . substr($row3->firstname, 0, 1) . substr($row3->middle, 0, 1);
				$hl7 = "MSH|^~\&|QDX|" . strtoupper($pname) . "|||" . $date . "00||ORM^O01|R10063131003.1|P|2.3||^" . strtoupper($pname);
				$hl7 .= "\r";
				$hl7 .= "PID|1|" . $pid . "|||" . strtoupper($row2->lastname) . "^" . strtoupper($row2->firstname) . "||" . $dob ."|" . strtoupper($row2->sex) . "|||||||||||";
				$hl7 .= "\r";
				$hl7 .= "ORC|NW|" . $orders_id . "|||A||^^^" . $order_date . "00||" . $date . "00|||" . strtoupper($row4->peacehealth_id) . "^" . strtoupper($row3->lastname) ."^" . strtoupper($row3->firstname) . "^" . strtoupper($middle) . "^^^" . strtoupper($row3->title) . "||||^|" . strtoupper($row4->peacehealth_id) . "^" . strtoupper($row3->lastname) ."^" . strtoupper($row3->firstname) . "^" . strtoupper($middle) . "^^^" . strtoupper($row3->title) . "||||100^QA-Central Laboratory|QA-Central Laboratory|QA-Central Laboratory^123 International Way^Springfield^OR^97477|1-800-826-3616";
				$orders_array = explode("\n", $row->orders_labs);
				$j = 1;
				foreach ($orders_array as $orders_row) {
					if ($orders_row != "") {
						$orders_row_array = explode(";", $orders_row);
						$testname = $orders_row_array[0];
						$i = 0;
						foreach ($orders_row_array as $orders_row1) {
							if (strpos($orders_row1, " Code: ") !== FALSE) {
								$testcode = str_replace(" Code: ", "", $orders_row1);
								$i++;
							}
							if (strpos($orders_row1, " AOEAnswer: ") !== FALSE) {
								$aoe_answer = str_replace(" AOEAnswer: ", "", $orders_row1);
								if (strpos($aoe_answer, "|") !== FALSE) {
									$aoe_answer_array = explode("|", $aoe_answer);
								} else {
									$aoe_answer_array[] = $aoe_answer;
								}
							}
							if (strpos($orders_row1, " AOECode: ") !== FALSE) {
								$aoe_code = str_replace(" AOECode: ", "", $orders_row1);
								$aoe_code = str_replace("\r", "", $aoe_code);
								if (strpos($aoe_code, "|") !== FALSE) {
									$aoe_code_array = explode("|", $aoe_code);
								} else {
									$aoe_code_array[] = $aoe_code;
								}
							}
						}
						if ($i == 0) {
							echo "Laboratory order code is missing for the electronic order entry.  Be sure you are choosing an order from an Electronic Order Entry list";
							exit (0);
						}
						$hl7 .= "\r";
						$orders_cc = '';
						$hl7 .= "OBR|" . $j . "|" . $orders_id . "||" . strtoupper($testcode) . "^" . strtoupper($testname) . "^^|R|" . $order_date . "00|" .$date . "|||||||" . $date . "00|SST^BLD|96666|||||PHL^PeaceHealth Laboratories^123 International Way^Springfield^OR^97477|||||||" . $orders_cc . "|";
						$j++;
					}
				}
				if ($row->orders_insurance != 'Bill Client') {
					$in1_array = explode("\n", $row->orders_insurance);
					$k = 1;
					foreach ($in1_array as $in1_row) {
						$in1_array1 = explode(";", $in1_row);
						$payor_id = str_replace(" Payor ID: ", "", $in1_array1[1]);
						if ($payor_id == "Unknown") {
							$payor_id = 'UNK.';
						}
						$plan_id = str_replace(" ID: ", "", $in1_array1[2]);
						if (strpos($in1_array1[3], " Group: ") !== FALSE) {
							$group_id = str_replace(" Group: ", "", $in1_array1[3]);
							$name_array = explode(", ", $in1_array1[4]);
						} else {
							$group_id = "";
							$name_array = explode(", ", $in1_array1[3]);
						}
						$hl7 .= "\r";
						$hl7 .= "IN1|" . $k . "|UNK.|" . strtoupper($payor_id) . "|" . strtoupper($in1_array1[0]) . "||||" . strtoupper($group_id) . "||||||||" . strtoupper($name_array[0]) . "^" . strtoupper($name_array[1]) . "^^^||||||||||||||||||||" . strtoupper($plan_id) . "|||||||||";
						$k++;
					}
				}
				if (isset($aoe_answer_array)) {
					for ($l=0; $l<count($aoe_answer_array); $l++) {
						$hl7 .= "\r";
						$m = $l+1;
						$hl7 .= "OBX|" . $m ."||" . strtoupper($aoe_code_array[$l]) . "||" . strtoupper($aoe_answer_array[$l]) . "||||||P|||" . $date ."00|";
					}
				}
				$file = "/srv/ftp/shared/export/PHLE_" . time();
				file_put_contents($file, $hl7);
				echo "Electronic order entry sent!";
				exit (0);
			}
		}
	}
	
	public function postFaxOrders()
	{
		$pid = Session::get('pid');
		if (Session::get('job_id') == FALSE) {
			$job_id = '';
		} else {
			$job_id = Session::get('job_id');
		}	
		$orders_id = Input::get('orders_id');
		$html = $this->page_orders($orders_id)->render();
		$filename = __DIR__."/../../public/temp/orders_" . time() . "_" . Session::get('user_id') . ".pdf";
		$this->generate_pdf($html, $filename);
		while(!file_exists($filename)) {
			sleep(2);
		}
		$row1 = Orders::find($orders_id);
		if ($row1->orders_labs != '') {
			$file_original = "Laboratory Order";
		}
		if ($row1->orders_radiology != '') {
			$file_original = "Imaging Order";
		}
		if ($row1->orders_cp != '') {
			$file_original = "Cardiopulmonary Order";
		}
		if ($row1->orders_referrals != '') {
			$file_original = "Referral Order";
		}
		$row2 = Addressbook::find($row1->address_id);
		$result_message = $this->fax_document($pid, $file_original, 'yes', $filename, $file_original, $row2->fax, $row2->displayname, $job_id, 'yes');
		Session::forget('job_id');
		unlink($filename);
		echo $result_message;
	}
	
	// $type can be Laboratory, Radiology, Cardiopulmonary, Referrral
	public function postEditOrdersProvider($type)
	{
		$data = array(
			'displayname' => '',
			'lastname' => '',
			'firstname' => '',
			'prefix' => '',
			'suffix' => '',
			'facility' => Input::get('facility'),
			'street_address1' => Input::get('street_address1'),
			'street_address2' => Input::get('street_address2'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone' => Input::get('phone'),
			'fax' => Input::get('fax'),
			'comments' => Input::get('comments'),
			'ordering_id' => Input::get('ordering_id'),
			'specialty' => '',
			'electronic_order' => '',
			'npi' => ''
		);
		if ($type == 'Referral') {
			if(Input::get('firstname') == '' || Input::get('lastname') == '') {
				$data['displayname'] = Input::get('facility');
			} else {
				if(Input::get('suffix') == '') {
					$data['displayname'] = Input::get('firstname') . ' ' . Input::get('lastname');
				} else {
					$data['displayname'] = Input::get('firstname') . ' ' . Input::get('lastname') . ', ' . Input::get('suffix');
				}
			}
			$data['specialty'] = Input::get('specialty');
			$data['lastname'] = Input::get('lastname');
			$data['firstname'] = Input::get('firstname');
			$data['prefix'] = Input::get('prefix');
			$data['suffix'] = Input::get('suffix');
			$data['npi'] = Input::get('npi');
		} elseif ($type == 'Laboratory') {
			$data['displayname'] = Input::get('facility');
			$data['specialty'] = $type;
			$data['electronic_order'] = Input::get('electronic_order');
		} else {
			$data['displayname'] = Input::get('facility');
			$data['specialty'] = $type;
		}
		if(Input::get('address_id') == '') {
			$add = DB::table('addressbook')->insertGetId($data);
			$this->audit('Add');
			$result['message'] = $type. " provider added!";
			$result['item'] = Input::get('facility') . ' (' . $add . ')';
			$result['id'] = $add;
		} else {
			DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->update($data);
			$this->audit('Update');
			$result['message'] = $type . " provider updated!";
			$result['item'] = Input::get('facility') . ' (' . Input::get('address_id') . ')';
			$result['id'] = Input::get('address_id');
		}
		echo json_encode($result);
	}
	
	public function postAddOrderslist()
	{
		$data = array(
			'orders_category' => Input::get('orders_category'),
			'orders_description' => Input::get('orders_description'),
			'cpt' => Input::get('cpt'),
			'snomed'=> Input::get('snomed'),
			'user_id' => Input::get('user_id')
		);
		if (Input::get('orderslist_id') == '') {
			DB::table('orderslist')->insert($data);
			$this->audit('Add');
			$message = "Entry added as a template!";
		} else {
			DB::table('orderslist')->where('orderslist_id', '=', Input::get('orderslist_id'))->update($data);
			$this->audit('Update');
			$message = "Entry updated as a template!";
		}
		echo $message;
	}
	
	public function documentsupload()
	{
		$pid = Session::get('pid');
		$directory = Session::get('documents_dir') . $pid;
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'application/pdf') {
					echo "This is not a PDF file.  Try again.";
					exit (0);
				}
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.pdf';
				$file->move($directory, $new_name);
				$data = array(
					'documents_url' => $directory . '/' . $new_name,
					'pid' => $pid
				);
				$documents_id = DB::table('documents')->insertGetId($data);
				$this->audit('Add');
				if ($documents_id) {
					$arr['result'] = 'Document added!';
					$arr['result1'] = 'Enter specific information about uploaded document:' . $file->getClientOriginalName();
					$arr['id'] = $documents_id;
				} else {
					$arr['result'] = 'Error adding document!';
				}
			}
		}
		echo json_encode($arr);
	}
	
	public function ccrupload()
	{
		$pid = Session::get('pid');
		$directory = Session::get('documents_dir') . $pid;
		$i = 0;
		foreach (Input::file('file') as $file) {
			if ($file) {
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.xml';
				$file->move($directory, $new_name);
				$ccr = $directory . '/' . $new_name;
				$xml = simplexml_load_file($ccr);
				$phone_home = '';
				$phone_work = '';
				$phone_cell = '';
				foreach ($xml->Actors->Actor[0]->Telephone as $phone) {
					if ((string) $phone->Type->Text == 'Home') {
						$phone_home = (string) $phone->Value;
					}
					if ((string) $phone->Type->Text == 'Mobile') {
						$phone_cell = (string) $phone->Value;
					}
					if ((string) $phone->Type->Text == 'Alternate') {
						$phone_work = (string) $phone->Value;
					}
				}
				$address = (string) $xml->Actors->Actor[0]->Address->Line1;
				$address = ucwords(strtolower($address));
				$city = (string) $xml->Actors->Actor[0]->Address->City;
				$city = ucwords(strtolower($city));
				$data1 = array(
					'address' => $address,
					'city' => $city,
					'state' => (string) $xml->Actors->Actor[0]->Address->State,
					'zip' => (string) $xml->Actors->Actor[0]->Address->PostalCode,
					'phone_home' => $phone_home,
					'phone_work' => $phone_work,
					'phone_cell' => $phone_cell,
				);
				DB::table('demographics')->where('pid', '=', $pid)->update($data1);
				$this->audit('Update');
				if (isset($xml->Body->Problems)) {
					foreach ($xml->Body->Problems->Problem as $issue) {
						if ((string) $issue->Status->Text == 'Active') {
							$icd9 = (string) $issue->Description->Code->Value;
							$row1 = Icd9::where('icd9', '=', $icd9)->select('icd9', 'icd9_description')->first();
							if ($row1) {
								$issue_post = $row1->icd9_description . ' [' . $row1->icd9 . ']';
							} else {
								$issue_post = (string) $issue->Description->Text . ' [' . (string) $issue->Description->Code->Value . ']';
							}
							$data2 = array(
								'issue' => $issue_post,
								'issue_date_active' => (string) $issue->DateTime->ExactDateTime,
								'issue_date_inactive' => '',
								'issue_provider' => $this->session->userdata('displayname'),
								'pid' => $pid
							);
							DB::table('issues')->insert($data2);
							$this->audit('Add');
						}
					}
				}
				if (isset($xml->Body->Medications)) {
					foreach ($xml->Body->Medications->Medication as $rx) {
						if ((string) $rx->Status->Text == 'Active') {
							$data3 = array(
								'rxl_medication' => (string) $rx->Product->ProductName->Text,
								'rxl_instructions' => (string) $rx->Directions->Direction->Dose->Value,
								'rxl_date_active' => (string) $rx->DateTime->ExactDateTime,
								'rxl_date_prescribed' => '',
								'rxl_date_inactive' => '',
								'rxl_date_old' => '',
								'rxl_provider' => $this->session->userdata('displayname'),
								'pid' => $pid
							);
							DB::table('rx_list')->insert($data3);
							$this->audit('Add');
						}
					}
				}
				if (isset($xml->Body->Immunizations)) {
					foreach ($xml->Body->Immunizations->Immunization as $imm) {
						if (strpos((string) $imm->Product->ProductName->Text, '#')) {
							$items = explode('#',(string) $imm->Product->ProductName->Text);
							$imm_immunization = rtrim($items[0]);
							$imm_sequence = $items[1];
						} else {
							$imm_immunization = (string) $imm->Product->ProductName->Text;
							$imm_sequence = '';
						}
						$data4 = array(
							'imm_immunization' => $imm_immunization,
							'imm_date' => (string) $imm->DateTime->ExactDateTime,
							'imm_sequence' => $imm_sequence,
							'imm_elsewhere' => 'Yes',
							'imm_vis' => '',
							'pid' => $pid,
							'eid' => ''
						);
						DB::table('immunizations')->insert($data4);
						$this->audit('Add');
					}
				}
				if (isset($xml->Body->Alerts)) {
					foreach ($xml->Body->Alerts->Alert as $alert) {
						if ((string) $alert->Status->Text == 'Current') {
							$data5 = array(
								'alert' => (string) $alert->Type->Text,
								'alert_description' => (string) $alert->Description->Text,
								'alert_date_active' => (string) $alert->DateTime->ExactDateTime,
								'alert_date_complete' => '',
								'alert_reason_not_complete' => '',
								'alert_provider' => $this->session->userdata('displayname'),
								'orders_id' => '',
								'pid' => $pid,
								'alert_send_message' => 'n'
							);
							DB::table('alerts')->insert($data5);
							$this->audit('Add');
						}
					}
				}
				$i++;
			}
		}
		echo $i . ' Continuity of Care Record(s) Imported!';
	}
	
	public function ccdaupload()
	{
		$pid = Session::get('pid');
		$directory = Session::get('documents_dir') . $pid;
		$i = 0;
		$arr['result'] = true;
		foreach (Input::file('file') as $file) {
			if ($file) {
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.xml';
				$file->move($directory, $new_name);
				$file_path = $directory . '/' . $new_name;
				$ccda = simplexml_load_file($file_path);
				if ($ccda) {
					$data = array(
						'documents_url' => $directory . '/' . $new_name,
						'documents_type' => 'ccda',
						'documents_desc' => $ccda->title,
						'documents_from' => $ccda->recordTarget->patientRole->providerOrganization->name,
						'documents_date' => date("Y-m-d", strtotime($ccda->effectiveTime['value'])),
						'pid' => $pid
					);
					$documents_id = DB::table('documents')->insertGetId($data);
					$this->audit('Add');
					$i++;
					$arr['ccda'] = $documents_id;
				} else {
					$arr['message'] = "This file is not in the correct format.  Try again.";
					$arr['result'] = false;
					unlink($file_path);
					break;
				}
			}
		}
		if ($arr['result'] !== false) {
			$arr['message'] = $i . 'C-CDA(s) Imported!';
		}
		echo json_encode($arr);
	}
	
	public function postGetCcda($id)
	{
		$row = DB::table('documents')->where('documents_id', '=', $id)->first();
		$file = File::get($row->documents_url);
		echo $file;
	}
	
	public function postPrintVivacare1($link)
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$html = new Htmldom("http://fromyourdoctor.com/topic.do?t=" . $link);
		if (isset($html)) {
			$final_html = $this->page_intro('Patient Instructions', Session::get('practice_id'))->render();
			$title = $html->find('h4',0);
			$final_html .= '<div style="width:700px">';
			$final_html .= '<h2 style="text-align: center;">';
			$final_html .= $title->innertext;
			$final_html .= '</h2>';
			$div = $html->find('[id=usercontent]',0);
			$final_html .= $div->outertext;
			$p1 = $div->nextSibling();
			$final_html .= $p1->outertext;
			$p2 = $p1->nextSibling();
			$final_html .= $p2->outertext;
			$p3 = $p2->nextSibling();
			$final_html .= $p3->outertext;
			$p4 = $p3->nextSibling();
			$final_html .= $p4->outertext;
			$p5 = $p4->nextSibling();
			$final_html .= $p5->outertext;
			$div2 = $html->find('[id=additional-resources]',0);
			$final_html .= $div2->outertext;
			$final_html .= '</div></body></html>';
			$result = Practiceinfo::find(Session::get('practice_id'));
			$directory = $result->documents_dir . Session::get('pid');
			$file_path = $directory . '/instructions_' . time() . '.pdf';
			$this->generate_pdf($html, $file_path);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$pages_data = array(
				'documents_url' => $file_path,
				'pid' => Session::get('pid'),
				'documents_type' => 'Letters',
				'documents_desc' => 'Instructions for ' . Session::get('ptname'),
				'documents_from' => Session::get('displayname'),
				'documents_viewed' => Session::get('displayname'),
				'documents_date' => date("Y-m-d H:i:s", time())
			);
			$arr['message'] = "OK";
			$arr['id'] = DB::table('documents')->insertGetId($pages_data);
			$this->audit('Add');
		} else {
			$arr['message'] = "Unable to download instructions from Vivacare.  Try again later.";
		}
		echo json_encode($arr);
	}
	
	public function postPrintVivacare($link)
	{
		set_time_limit(0);
		ini_set('memory_limit','196M');
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$html = new Htmldom("http://informationrx.com/" . $practice->vivacare . "/HealthTopic/" . $link);
		if (isset($html)) {
			$final_html = $this->page_intro('Patient Instructions', Session::get('practice_id'))->render();
			$title = $html->find('h1',0);
			$final_html .= '<div style="width:100%">';
			$final_html .= '<h2 style="text-align: center;">';
			$final_html .= trim(str_replace("Print Page", "", $title->plaintext));
			$final_html .= '</h2>';
			$div = $html->find('[class=col-main]',0);
			$final_html .= $div->outertext;
			$div1 = $html->find('[class=col-right]',0);
			$final_html .= $div1->outertext;
			$div2 = $html->find('[id=nav-topic]',0);
			$final_html .= '<h2>Related Topics</h2>';
			$final_html .= $div2->outertext;
			$final_html .= '</div></body></html>';
			$directory = $practice->documents_dir . Session::get('pid');
			$file_path = $directory . '/instructions_' . time() . '.pdf';
			$this->generate_pdf($final_html, $file_path);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			$pages_data = array(
				'documents_url' => $file_path,
				'pid' => Session::get('pid'),
				'documents_type' => 'Letters',
				'documents_desc' => 'Instructions for ' . Session::get('ptname'),
				'documents_from' => Session::get('displayname'),
				'documents_viewed' => Session::get('displayname'),
				'documents_date' => date("Y-m-d H:i:s", time())
			);
			$arr['message'] = "OK";
			$arr['id'] = DB::table('documents')->insertGetId($pages_data);
			$this->audit('Add');
		} else {
			$arr['message'] = "Unable to download instructions from Vivacare.  Try again later.";
		}
		echo json_encode($arr);
	}
	
	public function postImageLoad()
	{
		$query = DB::table('image')->where('eid', '=', Session::get('eid'))->get();
		$html = '';
		if ($query) {
			foreach ($query as $row) {
				$pid = Session::get('pid');
				$result = Practiceinfo::find(Session::get('practice_id'));
				$directory = $result->documents_dir . Session::get('pid') . "/";
				$new_directory = __DIR__.'/../../public/temp/';
				$new_directory1 = '/temp/';
				$file_path = str_replace($directory, $new_directory ,$row->image_location);
				$file_path1 = str_replace($directory, $new_directory1 ,$row->image_location);
				copy($row->image_location, $file_path);
				$html .= HTML::image($file_path1, 'Image', array('border' => '0', 'id' => $row->image_id . "_image", 'style' => 'display:none;', 'title' => $row->image_description));
			}
		}
		echo $html;
	}
	
	public function postImageGet($image_id)
	{
		$query = DB::table('image')->where('image_id', '=', $image_id)->first();
		$row = (array) $query;
		echo json_encode($row);
	}
	
	public function postImageSave()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Practiceinfo::find(Session::get('practice_id'));
			$directory = $result->documents_dir . Session::get('pid');
			$file_path = $directory . '/image_' . time() . '.png';
			$image = imagecreatefrompng(Input::get('image'));
			imagealphablending($image, false);
			imagesavealpha($image, true);
			imagepng($image, $file_path);
			$data = array(
				'image_location' => $file_path,
				'pid' => Input::get('pid'),
				'eid' => Input::get('eid'),
				'image_description' => Input::get('image_description'),
				'id' => Session::get('user_id'),
				'encounter_provider' => Session::get('displayname')
			);
			if (Input::get('image_id') == '') {
				DB::table('image')->insert($data);
				$this->audit('Add');
				$result = 'Image added!';
			} else {
				DB::table('image')->where("image_id", '=', Input::get('image_id'))->update($data);
				$this->audit('Update');
				$result = 'Image update!';
			}
			echo $result;
		}
	}
	
	public function postDeleteImage()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = DB::table('image')->where('image_id', '=', Input::get('image_id'))->first();
			$exists = file_exists($result->image_location);
			$delete = FALSE;
			if ($exists == TRUE) {
				$delete = unlink($result->image_location);
			} else {
				$delete = TRUE;
			}
			if ($delete == TRUE) {
				DB::table('image')->where('image_id', '=', Input::get('image_id'))->delete();
				$this->audit('Delete');
				$arr = 'Image deleted!';
			} else {
				$arr = 'Error deleting image!';
			}
			echo $arr;
		}
	}
	
	public function photoupload()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		$directory = $result->documents_dir . Session::get('pid');
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'image/jpeg' && $file->getMimeType() != 'image/gif' && $file->getMimeType() != 'image/png') {
					echo "This is not an image file.  Try again.";
					exit (0);
				}
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.' . $file->getClientOriginalExtension();
				$file->move($directory, $new_name);
				$file_path = $directory . "/" . $new_name;
				$data = array(
					'image_location' => $file_path,
					'pid' => Session::get('pid'),
					'eid' => Session::get('eid'),
					'image_description' => 'Photo uploaded ' . date('F jS, Y'),
					'id' => Session::get('user_id'),
					'encounter_provider' => Session::get('displayname')
				);
				DB::table('image')->insert($data);
				$this->audit('Add');
			}
		}
		echo 'Photos uploaded!';
	}
	
	public function import_csv()
	{
		$pid = Session::get('pid');
		$directory = Session::get('documents_dir') . $pid;
		$arr = array();
		foreach (Input::file('file') as $file) {
			if ($file) {
				$file->move($directory, $file->getClientOriginalName());
				$file_path = $directory . '/' . $file->getClientOriginalName();
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$csv = File::get($file_path);
				$delimiter = $this->getCSVDelimiter($file_path);
				Config::set('formatter::formatter.csv.delimiter', $delimiter);
				Config::set('formatter::formatter.csv.enclosure', '');
				Config::set('formatter::formatter.csv.newline', "\n");
				Config::set('formatter::formatter.csv.regex_newline', '\n');
				Config::set('formatter::formatter.csv.escape', '\\');
				$result = Formatter::make($csv, 'csv')->to_array();
				if (empty(Formatter::$errors)) {
					$keys = array_keys($result[0]);
					$i = 0;
					$arr['html'] = '<input type="hidden" name="file_path" value="' . $file_path . '"/>';
					$arr['message'] = 'CSV file analyzed!';
					foreach ($keys as $key) {
						$id = 'csv_' . $key;
						$arr['html'] .= '<div class="pure-control-group">';
						$arr['html'] .= '<label for="' . $id . '">' . $key . '</label>';
						$arr['html'] .= Form::select($key, array(
							''=>'Select NOSH database field.',
							'null'=>'Do not import this field.',
							'Allergies' => array('allergies_date_active'=>'Date Active','allergies_med'=>'Medication','allergies_reaction'=>'Reaction'),
							'Billing' => array('dos_f'=>'Date of Service - Start','dos_t'=>'Date of Service - End','payment_date'=>'Payment Date','payment'=>'Payment Amount','payment_type'=>'Payment Type'),
							'Demographics' => array('lastname'=>'Last Name','firstname'=>'First Name','middle'=>'Middle Name','nickname'=>'Nickname','title'=>'Title','sex'=>'Gender','DOB'=>'Date of Birth','ss'=>'Social Security Number','race'=>'Race','ethnicity'=>'Ethnicity','language'=>'Language','address'=>'Address','city'=>'City','state'=>'State','zip'=>'Zip','phone_home'=>'Home Phone','phone_work'=>'Work Phone','phone_cell'=>'Cell Phone','email'=>'Email','marital_status'=>'Marital Status','partner_name'=>'Partner Name','employer'=>'Employer','emergency_contact'=>'Emergency Contact Name','emergency_phone'=>'Emergency Contact Phone','caregiver'=>'Caregiver','guardian_lastname'=>'Guardian Last Name','guardian_firstname'=>'Guardian First Name','guardian_address'=>'Guardian Address','guardian_city'=>'Guardian City','guardian_state'=>'Guardian State','guardian_zip'=>'Guardian Zip','guardian_phone_home'=>'Guardian Home Phone','guardian_phone_work'=>'Guardian Work Phone','guardian_phone_cell'=>'Guardian Cell Phone','guardian_email'=>'Guardian E-mail','guardian_relationship'=>'Guardian Relationship'),
							'Immunizations' => array('imm_date'=>'Date','imm_immunization'=>'Immunization','imm_sequence'=>'Sequence','imm_body_site'=>'Body Site','imm_dosage'=>'Dosage','imm_dosage_unit'=>'Dosage Unit','imm_route'=>'Route','imm_lot'=>'LOT number','imm_manufacturer'=>'Manufacturer','imm_expiration'=>'Expiration Date','imm_brand'=>'Brand'),
							'Insurance' => array('insurance_plan_name'=>'Plan Name','insurance_id_num'=>'Policy Number','insurance_group'=>'Group Number','insurance_relationship'=>'Relationship to Insured','insurance_copay'=>'Copay Amount','insurance_deductible'=>'Deductible Amount','insurance_insu_lastname'=>'Insured Last Name','insurance_insu_firstname'=>'Insured First Name','insurance_insu_address'=>'Insured Address','insurance_insu_city'=>'Insured City','insurance_insu_state'=>'Insured State','insurance_insu_zip'=>'Insured Zip','insurance_insu_phone'=>'Insured Phone','insurance_insu_dob'=>'Insured Date of Birth','insurance_insu_gender'=>'Insured Gender','street_address1'=>'Insurance Plan Address 1','street_address2'=>'Insurance Plan Address 2','insurance_city'=>'Insurance Plan City','insurance_state'=>'Insurance Plan State','insurance_zip'=>'Insurance Plan Zip'),
							'Issues' => array('issue'=>'Issue','issue_date_active'=>'Date Active'),
							'Medications' => array('rxl_date_active'=>'Date Active','rxl_date_prescribed'=>'Date Prescribed','rxl_medication'=>'Medication','rxl_dosage'=>'Dosage','rxl_dosage_unit'=>'Dosage Unit','rxl_sig'=>'SIG','rxl_route'=>'Route','rxl_frequency'=>'Frequency','rxl_instructions'=>'Instructions','rxl_quantity'=>'Quantity','rxl_refill'=>'Refills','rxl_reason'=>'Reason'),
							'Supplements' => array('sup_date_active'=>'Date Active','sup_date_prescribed'=>'Date Prescribed','sup_supplement'=>'Supplement','sup_dosage'=>'Dosage','sup_dosage_unit'=>'Dosage Unit','sup_sig'=>'SIG','sup_route'=>'Route','sup_frequency'=>'Frequency','sup_instructions'=>'Instructions','sup_quantity'=>'Quantity','sup_reason'=>'Reason'),
							'Tests' => array('test_name'=>'Test Name','test_datetime'=>'Date of Test','test_result'=>'Result','test_units'=>'Result Units','test_reference'=>'Reference Range','test_flags'=>'Flags','test_type'=>'Test Type (Laboratory or Imaging)')
						), null, array('id'=>$id,'required','style'=>'width:300px','class'=>'text'));
						$arr['html'] .= '</div>';
						$arr['file'] = $file_path;
					}
				} else {
					unlink($file_path);
					$arr['message'] = 'Error with CSV file!';
				}
			}
		}
		return $arr;
	}
	
	public function postImportCsv()
	{
		$pid = Session::get('pid');
		$data = Input::all();
		$csv = File::get($data['file_path']);
		$delimiter = $this->getCSVDelimiter($data['file_path']);
		$csv_line = explode("\n", $csv);
		$csv_headers = explode($delimiter, $csv_line[0]);
		$new_header_array = array();
		foreach ($csv_headers as $header) {
			$new_header_array[] = $data[$header];
		}
		$csv_line[0] = implode($delimiter, $new_header_array);
		$csv_final = implode("\n", $csv_line);
		Config::set('formatter::formatter.csv.delimiter', $delimiter);
		Config::set('formatter::formatter.csv.enclosure', '');
		Config::set('formatter::formatter.csv.newline', "\n");
		Config::set('formatter::formatter.csv.regex_newline', '\n');
		Config::set('formatter::formatter.csv.escape', '\\');
		$result = Formatter::make($csv_final, 'csv')->to_array();
		$i = 0;
		$message = '';
		$allergies_array = array('allergies_date_active','allergies_med','allergies_reaction');
		$billing_array = array('dos_f','dos_t','payment_date','payment','payment_type');
		$demographics_array = array('lastname','firstname','middle','nickname','title','sex','DOB','ss','race','ethnicity','language','address','city','state','zip','phone_home','phone_work','phone_cell','email','marital_status','partner_name','employer','emergency_contact','emergency_phone','caregiver','guardian_firstname','guardian_lastname','guardian_code','guardian_address','guardian_city','guardian_state','guardian_zip','guardian_phone_home','guardian_phone_work','guardian_phone_cell','guardian_email','guardian_relationship');
		$immunizations_array = array('imm_date','imm_immunization','imm_sequence','imm_body_site','imm_dosage','imm_dosage_unit','imm_route','imm_lot','imm_manufacturer','imm_expiration','imm_brand');
		$insurance_array = array('insurance_plan_name','insurance_id_num','insurance_group','insurance_relationship','insurance_copay','insurance_deductible','insurance_insu_lastname','insurance_insu_firstname','insurance_insu_address','insurance_insu_city','insurance_insu_state','insurance_insu_zip','insurance_insu_phone','insurance_insu_dob','insurance_insu_gender','street_address1','street_address2','insurance_city','insurance_state','insurance_zip');
		$issues_array = array('issue','issue_date_active');
		$medications_array = array('rxl_date_active','rxl_date_prescribed','rxl_medication','rxl_dosage','rxl_dosage_unit','rxl_sig','rxl_route','rxl_frequency','rxl_instructions','rxl_quantity','rxl_refill','rxl_reason');
		$supplements_array = array('sup_date_active','sup_date_prescribed','sup_supplement','sup_dosage','sup_dosage_unit','sup_sig','sup_route','sup_frequency','sup_instructions','sup_quantity','sup_reason');
		$tests_array = array('test_name','test_datetime','test_result','test_units','test_reference','test_flags','test_type');
		foreach ($result as $field) {
			$allergies = array();
			$billing = array();
			$demographics = array();
			$immunizations = array();
			$insurance = array();
			$issues = array();
			$medications = array();
			$supplements = array();
			$tests = array();
			while ($value = current($field)) {
				$key = key($field);
				if (in_array($key, $allergies_array)) {
					$allergies[$key] = $value;
				}
				if (in_array($key, $billing_array)) {
					$billing[$key] = $value;
				}
				if (in_array($key, $demographics_array)) {
					$demographics[$key] = $value;
				}
				if (in_array($key, $immunizations_array)) {
					$immunizations[$key] = $value;
				}
				if (in_array($key, $insurance_array)) {
					$insurance[$key] = $value;
				}
				if (in_array($key, $issues_array)) {
					$issues[$key] = $value;
				}
				if (in_array($key, $medications_array)) {
					$medications[$key] = $value;
				}
				if (in_array($key, $supplements_array)) {
					$supplements[$key] = $value;
				}
				if (in_array($key, $tests_array)) {
					$tests[$key] = $value;
				}
				next($field);
			}
			if (!empty($allergies)) {
				if (!empty($allergies['allergies_date_active'])) {
					$allergies['allergies_date_active'] = date('Y-m-d H:i:s', strtotime($allergies['allergies_date_active']));
				} else {
					$allergies['allergies_date_active'] = date('Y-m-d H:i:s', time());
				}
				$allergies['pid'] = $pid;
				$allergies['allergies_date_inactive'] = '';
				$allergies['rcopia_sync'] = 'n';
				DB::table('allergies')->insert($allergies);
				$this->audit('Add');
				$message .= 'Added ' . $allergies['allergies_med'] . 'to allergies list.<br>';
			}
			if (!empty($billing)) {
				if (!empty($billing['dos_f'])) {
					$billing['dos_f'] = date('m/d/Y', strtotime($billing['dos_f']));
					$billing_query_enc = DB::table('billing_core')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->where('eid', '!=', '0')->where('dos_f', '=', $billing['dos_f'])->first(); 
					$billing_query_oth = DB::table('billing_core')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->where('eid', '=', '0')->where('dos_f', '=', $billing['dos_f'])->first();
					$billing['practice_id'] = Session::get('practice_id');
					$billing['pid'] = $pid;
					if ($billing_query_enc) {
						$billing['eid'] = $billing_query_enc->eid;
						$dos = $billing['dos_f'];
						$billing['dos_f'] = date('m/d/Y', strtotime($billing['payment_date']));
						unset($billing['payment_date']);
						DB::table('billing_core')->insert($billing);
						$this->audit('Add');
						$message .= 'Added payment information to encounter with date of service of ' . $dos . '.</br>';
					} elseif ($billing_query_oth) {
						$billing['other_billing_id'] = $billing_query_oth->other_billing_id;
						$dos = $billing['dos_f'];
						$billing['dos_f'] = date('m/d/Y', strtotime($billing['payment_date']));
						unset($billing['payment_date']);
						DB::table('billing_core')->insert($billing);
						$this->audit('Add');
						$message .= 'Added payment information to miscellaneous bill with date of service of ' . $dos . '.</br>';
					} else {
						$message .= 'Unable to import bill since Date of Service did not match any previous encounters or miscellaneous bills for this patient.<br>';
					}
					
				} else {
					$message .= 'Unable to import bill since Date of Service was not provided.<br>';
				}
			}
			if (!empty($demographics)) {
				if (!empty($demographics['DOB'])) {
					$demographics['DOB'] = date('Y-m-d', strtotime($demographics['DOB']));
				}
				if (!empty($demographics['sex'])) {
					$demographics['sex'] = strtolower(substr($demographics['sex'],0,1));
				}
				DB::table('demographics')->where('pid', '=', $pid)->update($demographics);
				$message .= 'Updated demographics.<br>';
			}
			if (!empty($immunizations)) {
				if (!empty($immunizations['imm_date'])) {
					$immunizations['imm_date'] = date('Y-m-d H:i:s', strtotime($immunizations['imm_date']));
					$immunizations['pid'] = $pid;
					$immunizations['imm_elsewhere'] = 'Yes';
					if (!empty($immunizations['imm_expiration'])) {
						$immunizations['imm_expiration'] = date('Y-m-d H:i:s', strtotime($immunizations['imm_expiration']));
					}
					DB::table('immunizations')->insert($immunizations);
					$this->audit('Add');
					$message .= 'Added ' . $immunizations['imm_immunization'] . ' to the immunization list.<br>';
				} else {
					$message .= 'No immunization added due to missing date of immunization.<br>';
				}
			}
			if (!empty($insurance)) {
				if (!empty($insurance['insurance_plan_name'])) {
					$address_query = DB::table('addressbook')->where('facility', '=', $insurance['insurance_plan_name'])->where('specialty', '=', 'Insurance')->first();
					if ($address_query) {
						$insurance['address_id'] = $address_query->address_id;
					} else {
						$address_data = array(
							'displayname' => $insurance['insurance_plan_name'],
							'facility' => $insurance['insurance_plan_name'],
							'street_address1' => $insurance['street_address1'],
							'street_address2' => $insurance['street_address2'],
							'city' => $insurance['insurance_city'],
							'state' => $insurance['insurance_state'],
							'zip' => $insurance['insurance_zip'],
							'specialty' => 'Insurance',
							'insurance_box_31' => 'n',
							'insurance_box_32a' => 'n'
						);
						$insurance['address_id'] = DB::table('addressbook')->insertGetId($address_data);
						$message .= 'Added ' . $insurance['insurance_plan_name'] . ' to the addressbook.<br>';
					}
					unset($insurance['street_address1']);
					unset($insurance['street_address2']);
					unset($insurance['insurance_city']);
					unset($insurance['insurance_state']);
					unset($insurance['insurance_zip']);
					if (!empty($insurance['insurance_insu_dob'])) {
						$insurance['insurance_insu_dob'] = date('Y-m-d', strtotime($insurance['insurance_insu_dob']));
					}
					if (!empty($insurance['insurance_insu_gender'])) {
						$insurance['insurance_insu_gender'] = strtolower(substr($insurance['insurance_insu_gender'],0,1));
					}
					$insurance['insurance_plan_active'] = 'Yes';
					$insurance['pid'] = $pid;
					DB::table('insurance')->insert($insurance);
					$this->audit('Add');
					$message .= 'Added ' . $insurance['insurance_plan_name'] . ' to the insurance list.<br>';
				} else {
					$message .= 'No insurance information added due to missing plan name.<br>';
				}
			}
			if (!empty($issues)) {
				if (!empty($issues['issue_date_active'])) {
					$issues['issue_date_active'] = date('Y-m-d H:i:s', strtotime($issues['issue_date_active']));
				} else {
					$issues['issue_date_active'] = date('Y-m-d H:i:s', time());
				}
				$issues['pid'] = $pid;
				$issues['issue_date_inactive'] = '';
				$issues['rcopia_sync'] = 'n';
				DB::table('issues')->insert($issues);
				$this->audit('Add');
				$message .= 'Added ' . $issues['issue'] . 'to issues list.<br>';
			}
			if (!empty($medications)) {
				if (!empty($medications['rxl_date_active'])) {
					$medications['rxl_date_active'] = date('Y-m-d H:i:s', strtotime($medications['rxl_date_active']));
				} else {
					$medications['rxl_date_active'] = date('Y-m-d H:i:s', time());
				}
				$medications['pid'] = $pid;
				$medications['rcopia_sync'] = 'n';
				$medications['id'] = Session::get('user_id');
				$medications['rxl_provider'] = Session::get('displayname');
				$medications['rxl_date_old'] = '';
				$medications['rxl_date_inactive'] = '';
				DB::table('rx_list')->insert($medications);
				$this->audit('Add');
				$message .= 'Added ' . $medications['rxl_medication'] . 'to medications list.<br>';
			}
			if (!empty($supplements)) {
				if (!empty($supplements['sup_date_active'])) {
					$supplements['sup_date_active'] = date('Y-m-d H:i:s', strtotime($supplements['sup_date_active']));
				} else {
					$supplements['sup_date_active'] = date('Y-m-d H:i:s', time());
				}
				$supplements['pid'] = $pid;
				$supplements['id'] = '';
				$supplements['sup_provider'] = '';
				$supplements['sup_date_inactive'] = '';
				DB::table('sup_list')->insert($supplements);
				$this->audit('Add');
				$message .= 'Added ' . $supplements['sup_supplement'] . 'to supplements list.<br>';
			}
			if (!empty($tests)) {
				if (!empty($tests['test_datetime'])) {
					$tests['test_datetime'] = date('Y-m-d H:i:s', strtotime($tests['test_datetime']));
					$tests['pid'] = $pid;
					$tests['practice_id'] = Session::get('practice_id');
					DB::table('tests')->insert($tests);
					$this->audit('Add');
					$message .= 'Added ' . $tests['test_name'] . 'to tests list.<br>';
				} else {
					$message .= 'No test results added due to missing date of test.<br>';
				}
			}
			$i++;
		}
		$message .= 'Read ' . $i . ' lines in the CSV file.';
		return $message;
	}
	
	public function postGetCreditcard()
	{
		$query = DB::table('demographics')->select('creditcard_number', 'creditcard_expiration', 'creditcard_type', 'creditcard_key')->where('pid', '=', Session::get('pid'))->first();
		$data = array();
		if ($query) {
			if ($query->creditcard_key == '') {
				$data['creditcard_number'] = '';
			} else {
				Crypt::setKey($query->creditcard_key);
				$data['creditcard_number'] = Crypt::decrypt($query->creditcard_number);
				Crypt::setKey('YourSecretKey!!!');
			}
			$data['message'] = 'y';
			$data['creditcard_expiration'] = $query->creditcard_expiration;
			$data['creditcard_type'] = $query->creditcard_type;
		} else {
			$data['message'] = 'n';
		}
		echo json_encode($data);
	}
	
	public function postSaveCreditcard()
	{
		$data = array();
		$query = DB::table('demographics')->where('pid', '=', Session::get('pid'))->first();
		if ($query->creditcard_key == '') {
			$key = MD5(microtime());
			$data['creditcard_key'] = $key;
		} else {
			$key = $query->creditcard_key;
		}
		Crypt::setKey($key);
		$data['creditcard_number'] = Crypt::encrypt(Input::get('creditcard_number'));
		Crypt::setKey('YourSecretKey!!!');
		$data['creditcard_expiration'] = Input::get('creditcard_expiration');
		$data['creditcard_type'] = Input::get('creditcard_type');
		DB::table('demographics')->where('pid', '=', Session::get('pid'))->update($data);
		$this->audit('Update');
		echo "Credit Card Information Updated!";
	}
	
	public function postCloseencounter()
	{
		Session::forget('eid');
		Session::forget('encounter_active');
		echo 'OK';
	}
	
	public function postGetTestForm($tests_id) {
		$result = DB::table('tests')->where('tests_id', '=', $tests_id)->first();
		$result_arr = (array) $result;
		echo json_encode($result_arr);
	}
	
	public function postSaveTestForm()
	{
		$test_data = array(
			'pid' => Session::get('pid'),
			'test_name' => Input::get('test_name'),
			'test_result' => Input::get('test_result'),
			'test_units' => Input::get('test_units'),
			'test_reference' => Input::get('test_reference'),
			'test_flags' => Input::get('test_flags'),
			'test_from' => Input::get('test_from'),
			'test_datetime' => date('Y-m-d H:i:s', strtotime(Input::get('test_datetime'))),
			'test_type' => Input::get('test_type'),
			'test_provider_id' => Input::get('test_provider_id'),
			'practice_id' => Session::get('practice_id')
		);
		if (Input::get('tests_id') != '') {
			DB::table('tests')->where('tests_id', '=', Input::get('tests_id'))->update($test_data);
			$this->audit('Update');
			$result = 'Result updated!';
		} else {
			DB::table('tests')->insert($test_data);
			$this->audit('Add');
			$result = 'Result added!';
		}
		echo $result;
	}
	
	public function postDeleteTest($tests_id)
	{
		DB::table('tests')->where('tests_id', '=', $tests_id)->delete();
		$this->audit('Delete');
		echo 'Result deleted!';
	}
	
	public function postHedisChartAudit($type)
	{
		if ($type == 'spec') {
			$type = Input::get('time');
		}
		$html = $this->hedis_audit($type, 'chart', Session::get('pid'));
		echo $html;
	}
	
	public function postNotification()
	{
		$pid = Session::get('pid');
		$user_id = Session::get('user_id');
		$practice_id = Session::get('practice_id');
		$start_time = time() - 86400;
		$end_time = time() + 86400;
		$query = DB::table('schedule')->where('pid', '=', Session::get('pid'))
			->whereBetween('start', array($start_time, $end_time))
			->first();
		$data = array(
			'appt' => '',
			'appt_header' => 'Appointment Alert',
			'alert' => '',
			'alert_header' => 'Alert Notification'
		);
		if ($query) {
			if ($query->notes != '') {
				$data['appt'] = $query->notes;
			}
		}
		$query1 = DB::table('alerts')
			->where('pid', '=', $pid)
			->where('alert_date_complete', '=', '0000-00-00 00:00:00')
			->where('alert_reason_not_complete', '=', '')
			->where('practice_id', '=', $practice_id)
			->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$alert_date = $this->human_to_unix($row1->alert_date_active);
				if ($alert_date >= $start_time && $alert_date <= $end_time) {
					$data['alert'] .= $row1->alert . ': ' . $row1->alert_description . '<br>';
				}
			}
		}
		echo json_encode($data);
	}
	
	public function postConnectPatientNosh()
	{
		$data = array();
		$practiceinfo = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$practiceinfo_data = (array) $practiceinfo;
		unset($practiceinfo_data['practice_id']);
		unset($practiceinfo_data['documents_dir']);
		unset($practiceinfo_data['updox_extension']);
		unset($practiceinfo_data['mtm_extension']);
		unset($practiceinfo_data['mtm_alert_users']);
		unset($practiceinfo_data['fax_type']);
		unset($practiceinfo_data['fax_email']);
		unset($practiceinfo_data['fax_email_password']);
		unset($practiceinfo_data['fax_email_hostname']);
		unset($practiceinfo_data['fax_email_smtp']);
		unset($practiceinfo_data['phaxio_api_key']);
		unset($practiceinfo_data['phaxio_api_secret']);
		unset($practiceinfo_data['practice_api_key']);
		unset($practiceinfo_data['practice_registration_key']);
		unset($practiceinfo_data['practice_registration_timeout']);
		$data['practice'] = $practiceinfo_data;
		$result = $this->send_api_data(Input::get('url'), $data, '', '');
		$message['status'] = 'n';
		if ($result['url_error'] == '') {
			if (isset($result['error'])) {
				if ($result['error'] == true) {
					$message['message'] = 'Error: ' . $result['message'];
				} else {
					$patient_data = array(
						'api_key' => $result['api_key'],
						'url' => Input::get('url')
					);
					DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->update($patient_data);
					$this->audit('Update');
					$message['status'] = 'y';
					$message['message'] = $result['message'];
				}
			} else {
				$message['message'] = 'Error: not a valid connection, check your URL';
			}
		} else {
			$message['message'] = $result['url_error'];
		}
		echo json_encode($message);
	}
	
	public function postGetPatientCentric()
	{
		$row = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		echo $row->url;
	}
}
