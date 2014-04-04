<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
	protected function audit($action)
	{
		$queries = DB::getQueryLog();
		$sql = end($queries);
		if (!empty($sql['bindings'])) {
			$pdo = DB::getPdo();
			foreach($sql['bindings'] as $binding) {
				$sql['query'] = preg_replace('/\?/', $pdo->quote($binding), $sql['query'], 1);
			}
		}
		$data = array(
			'user_id' => Session::get('user_id'),
			'displayname' => Session::get('displayname'),
			'pid' => Session::get('pid'),
			'group_id' =>  Session::get('group_id'),
			'action' => $action,
			'query' => $sql['query'],
			'practice_id' => Session::get('practice_id')
		);
		DB::table('audit')->insert($data);
	}
	
	protected function rcopia($command, $practice_id)
	{
		$practice = Practiceinfo::find($practice_id);
		$url = 'https://update.drfirst.com/servlet/rcopia.servlet.EngineServlet?';
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><RCExtRequest version = \"2.08\">";
		$xml .= "<Caller><VendorName>" . $practice->rcopia_apiVendor . "</VendorName><VendorPassword>" . $practice->rcopia_apiPass . "</VendorPassword></Caller>";
		$xml .= "<SystemName>" . $practice->rcopia_apiSystem . "</SystemName>";
		$xml .= "<RcopiaPracticeUsername>" . $practice->rcopia_apiPractice . "</RcopiaPracticeUsername>";
		$xml .= $command;
		$fields = array(
			'xml' => urlencode($xml)
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$headers = array(
			"Content-type: application/xml"
		);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	protected function rcopia_update_medication_xml($pid, $result1, $origin)
	{
		$xml = new SimpleXMLElement($result1);
		$last_update_date = $xml->Response->LastUpdateDate . "";
		$number = $xml->Response->PrescriptionList->Number . "";
		if ($number != "0") {
			if ($origin == "message") {
				$rx_rx = 'PRESCRIBED MEDICATIONS:  ';
			} else {
				$rx_rx = '';
			}
			foreach ($xml->Response->PrescriptionList->Prescription as $prescription) {
				$rxl_medication = ucfirst($prescription->Sig->Drug->BrandName) . ", " . $prescription->Sig->Drug->Form;
				if ($prescription->Sig->Drug->Strength != '') {
					if (strpos($prescription->Sig->Drug->Strength, " ")) {
						$rxl_dosage_parts = explode(" ", $prescription->Sig->Drug->Strength);
						$rxl_dosage = $rxl_dosage_parts[0];
						$rxl_dosage_unit = $rxl_dosage_parts[1];
					} else {
						$rxl_dosage = $prescription->Sig->Drug->Strength . '';
						$rxl_dosage_unit = '';
					}
				} else {
					$rxl_dosage = '';
					$rxl_dosage_unit = '';
				}
				$date_active_pre = explode(" ", $prescription->CreatedDate);
				$date_active_pre1 = explode("/", $date_active_pre[0]);
				$date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
				$old_result = DB::table('rx_list')
					->where('rxl_medication', '=', $rxl_medication)
					->where('rxl_dosage', '=', $rxl_dosage)
					->first();
				if ($old_result) {
					$data1 = array(
						'rxl_date_old' => date('Y-m-d H:i:s', time())
					);
					DB::table('rx_list')->where('rxl_id', '=', $old_result->rxl_id)->update($data1);
					$this->audit('Update');
					$old_date_active = $old_result->rxl_date_active;
				} else {
					$old_date_active = $date_active;
				}
				if ($prescription->Sig->Dose != '' || $prescription->Sig->DoseUnit != '') {
					$rxl_sig = $prescription->Sig->Dose . " " . $prescription->Sig->DoseUnit;
				} else {
					$rxl_sig = '';
				}
				$quantity_unit = $prescription->Sig->QuantityUnit;
				if ($quantity_unit == "" || $quantity_unit == "undefined") {
					$rxl_quantity = $prescription->Sig->Quantity;
				} else {
					$rxl_quantity = $prescription->Sig->Quantity . " " . $prescription->Sig->QuantityUnit;
				}
				if ($prescription->Sig->SubstitutionPermitted == 'y') {
					$rxl_daw =  '';
				} else {
					$rxl_daw = 'Dispense As Written';
				}
				$date_active_pre = explode(" ", $prescription->CreatedDate);
				$date_active_pre1 = explode("/", $date_active_pre[0]);
				$date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
				if ($prescription->StopDate != '') {
					$due_date_parts0 = explode(" ", $prescription->StopDate);
					$due_date_parts = explode("/", $due_date_parts0[0]);
					$rxl_due_date = $due_date_parts[2] . "-" . $due_date_parts[0] . "-" . $due_date_parts[1] . " 00:00:00";
				} else {
					$rxl_due_date = '00-00-00 00:00:00';
				}
				$rxl_provider = $prescription->Provider->FirstName . " " . $prescription->Provider->LastName;
				$rxl_route = $prescription->Sig->Route . "";
				$rxl_frequency = $prescription->Sig->DoseTiming . "";
				$rxl_instructions = $prescription->Sig->DoseOther . "";
				$rxl_refill = $prescription->Sig->Refills . "";
				$rxl_ndcid = $prescription->Sig->Drug->NDCID . "";
				$data2 = array(
					'rxl_medication' => $rxl_medication,
					'rxl_dosage' => $rxl_dosage,
					'rxl_dosage_unit' => $rxl_dosage_unit,
					'rxl_sig' => $rxl_sig,
					'rxl_route' => $rxl_route,
					'rxl_frequency' => $rxl_frequency,
					'rxl_instructions' => $rxl_instructions,
					'rxl_quantity' => $rxl_quantity,
					'rxl_refill' => $rxl_refill,
					'rxl_date_active' => $old_date_active,
					'rxl_date_inactive' => '',
					'rxl_date_prescribed' => $date_active,
					'rxl_date_old' => '',
					'rxl_provider' => $rxl_provider,
					'rxl_daw' => $rxl_daw,
					'rxl_due_date' => $rxl_due_date,
					'rxl_ndcid' => $rxl_ndcid,
					'rcopia_sync' => 'y',
					'pid' => $pid
				);
				DB::table('rx_list')->insert($data2);
				$this->audit('Add');
				if ($rxl_sig == '') {
					$instructions = $rxl_instructions;
				} else {
					$instructions = $rxl_sig . ' ' . $rxl_route . ' ' . $rxl_frequency;
				}
				$rx_rx .= $rxl_medication . ' ' . $rxl_dosage . ' ' . $rxl_dosage_unit . ', ' . $instructions . ', Quantity: ' . $rxl_quantity . ', Refills: ' . $rxl_refill . "\n";
			}
			if ($origin == "message") {
				$rx_rx .= "\n";
			}
		} else {
			$rx_rx = "No updated prescriptions.";
		}
		$data = array(
			'rcopia_update_prescription_date' => $last_update_date,
			'rcopia_update_prescription' => 'n'
		);
		DB::table('demographics')->where('pid', '=', $pid)->update($data);
		$this->audit('Update');
		return $rx_rx;
	}
	
	protected function rcopia_update_allergy_xml($pid, $result1)
	{
		$xml = new SimpleXMLElement($result1);
		$last_update_date = $xml->Response->LastUpdateDate . "";
		foreach ($xml->Response->AllergyList->Allergy as $allergy) {
			$allergies_id = $allergy->ExternalID . "";
			$allergies_med = $allergy->Allergen->Name . "";
			$allergies_reaction = $allergy->Reaction . "";
			$date_active_pre = explode(" ", $allergy->OnsetDate);
			$date_active_pre1 = explode("/", $date_active_pre[0]);
			$allergies_date_active = $date_active_pre1[2] . "-" . $date_active_pre1[0] . "-" . $date_active_pre1[1] . " 00:00:00";
			$date_inactive_pre = explode(" ", $allergy->LastModifiedDate);
			$date_inactive_pre1 = explode("/", $date_inactive_pre[0]);
			$allergies_date_inactive = $date_inactive_pre1[2] . "-" . $date_inactive_pre1[0] . "-" . $date_inactive_pre1[1] . " 00:00:00";
			$meds_ndcid = $allergy->Allergen->Drug->NDCID . "";
			$delete = $allergy->Deleted . "";
			if ($delete == 'n') {
				$data1 = array(
					'pid' => $pid,
					'allergies_date_active' => $allergies_date_active,
					'allergies_med' => $allergies_med,
					'allergies_reaction' => $allergies_reaction,
					'rcopia_sync' => 'y',
					'meds_ndcid' => $meds_ndcid
				);
				DB::table('allergies')->insert($data1);
				$this->audit('Add');
			} else {
				$data2['allergies_date_inactive'] = $allergies_date_inactive;
				DB::table('allergies')->where('meds_ndcid', '=', $meds_ndcid)->where('pid', '=', $pid)->update($data2);
				$this->audit('Update');
			}
		}
		$data = array(
			'rcopia_update_allergy_date' => $last_update_date,
			'rcopia_update_allergy' => 'n'
		);
		DB::table('demographics')->where('pid', '=', $pid)->update($data);
		$this->audit('Update');
		return "Allergy list updated with DrFirst RCopia";
	}
	
	protected function generate_pdf($html, $filepath, $footer='footerpdf', $header='', $type='1', $headerparam='')
	{
		$pdf = PDF::loadHTML($html);
		$footer = route($footer);
		$footer = str_replace("https", "http", $footer);
		$pdf_options = array(
			'page-size' => 'Letter',
			'margin-top' => 26,
			'margin-bottom' => 26,
			'footer-html' => $footer,
			'disable-smart-shrinking' => false
		);
		if ($header != '') {
			if ($headerparam == '') {
				$pdf_options['header-center'] = $header;
				$pdf_options['header-font-size'] = 8;
			} else {
				$header = route($header, array($headerparam));
				$header = str_replace("https", "http", $header);
				$pdf_options['header-html'] = $header;
				$pdf_options['header-spacing'] = 5;
			}
		}
		if ($type == '1') {
			$pdf_options['margin-left'] = 26;
			$pdf_options['margin-right'] = 26;
		} 
		if ($type == '2') {
			$pdf_options['margin-left'] = 16;
			$pdf_options['margin-right'] = 16;
		}
		$pdf->setOptions($pdf_options)->save($filepath);
		while(!file_exists($filepath)) {
			sleep(2);
		}
		return true;
	}
	
	/**
	 *	Print chart
	 *
	 *	@param	$pid = Patient ID
	 *	@param	$output = Options: fax, print, file
	 * 	@param	$hippa_id = Hippa ID
	 *  @param	$type = Options: all, queue, 1year
	 */
	protected function print_chart($pid, $output, $hippa_id='', $type)
	{
		ini_set('memory_limit','196M');
		$result = Practiceinfo::find(Session::get('practice_id'));
		$patient = Demographics::find($pid);
		$lastname = str_replace(' ', '_', $patient->lastname);
		$firstname = str_replace(' ', '_', $patient->firstname);
		$dob = date('Ymd', $this->human_to_unix($patient->DOB));
		if ($hippa_id == '') {
			$directory = $result->documents_dir . $pid . "/print_entire";
		} else {
			$directory = $result->documents_dir . $pid . "/print_" . $hippa_id;
		}
		$directory_links = $directory . "/links";
		if (file_exists($directory)) {
			foreach (scandir($directory) as $item) {
				if ($item == '.' || $item == '..' || $item == 'links') continue;
				unlink ($directory.DIRECTORY_SEPARATOR.$item);
			}
		} else {
			mkdir($directory, 0775);
		}
		if (file_exists($directory_links)) {
			foreach (scandir($directory_links) as $item1) {
				if ($item1 == '.' || $item1 == '..') continue;
				unlink ($directory_links.DIRECTORY_SEPARATOR.$item1);
			}
		} else {
			mkdir($directory_links, 0775);
		}
		// Generate encounters and messages
		$header = strtoupper($patient->lastname . ', ' . $patient->firstname . '(DOB: ' . date('m/d/Y', $this->human_to_unix($patient->DOB)) . ', Gender: ' . ucfirst(Session::get('gender')) . ', ID: ' . $pid . ')');
		$file_path_enc = $directory . '/printchart.pdf';
		if (file_exists($file_path_enc)) {
			unlink($file_path_enc);
		}
		$html = $this->page_intro('Medical Records', Session::get('practice_id'));
		if ($type == 'all') {
			$query1 = DB::table('encounters')
				->where('pid', '=', $pid)
				->where('encounter_signed', '=', 'Yes')
				->where('addendum', '=', 'n')
				->where('practice_id', '=', Session::get('practice_id'))
				->orderBy('encounter_DOS', 'desc')
				->get();
			$query2 = DB::table('t_messages')
				->where('pid', '=', $pid)
				->where('t_messages_signed', '=', 'Yes')
				->where('practice_id', '=', Session::get('practice_id'))
				->orderBy('t_messages_dos', 'desc')
				->get();
			$query3 = DB::table('documents')
				->where('pid', '=', $pid)
				->orderBy('documents_date', 'desc')->get();
		} elseif ($type == 'queue') {
			$query1 = DB::table('hippa')
				->join('encounters', 'hippa.eid', '=', 'encounters.eid')
				->where('hippa.other_hippa_id', '=', $hippa_id)
				->whereNotNull('hippa.eid')
				->orderBy('encounters.encounter_DOS', 'desc')
				->get();
			$query2 = DB::table('hippa')
				->join('t_messages', 'hippa.t_messages_id', '=', 't_messages.t_messages_id')
				->where('hippa.other_hippa_id', '=', $hippa_id)
				->whereNotNull('hippa.t_messages_id')
				->orderBy('t_messages.t_messages_dos', 'desc')
				->get();
			$query3 = DB::table('hippa')
				->join('documents', 'hippa.documents_id', '=', 'documents.documents_id')
				->where('hippa.other_hippa_id', '=', $hippa_id)
				->whereNotNull('hippa.documents_id')
				->orderBy('documents.documents_date', 'desc')
				->get();
		} else {
			$end = time();
			$start = $end - 31556926;
			$query1 = DB::select(DB::raw("SELECT * FROM encounters WHERE pid=:pid AND UNIX_TIMESTAMP(encounter_DOS) >= :start AND UNIX_TIMESTAMP(encounter_DOS) <= :end AND encounter_signed = 'Yes' AND addendum = 'n' AND practice_id = :practice_id ORDER BY encounter_DOS DESC"), array(
				'pid' => $pid,
				'start' => $start,
				'end' => $end,
				'practice_id' => Session::get('practice_id')
			));
			$query2 = DB::select(DB::raw("SELECT * FROM t_messages WHERE pid=:pid AND UNIX_TIMESTAMP(t_messages_dos) >= :start AND UNIX_TIMESTAMP(t_messages_dos) <= :end AND t_messages_signed = 'Yes' AND practice_id = :practice_id ORDER BY t_messages_dos DESC"), array(
				'pid' => $pid,
				'start' => $start,
				'end' => $end,
				'practice_id' => Session::get('practice_id')
			));
			$query3 = DB::select(DB::raw("SELECT * FROM documents WHERE pid=:pid AND UNIX_TIMESTAMP(documents_date) >= :start AND UNIX_TIMESTAMP(documents_date) <= :end ORDER BY documents_date DESC"), array(
				'pid' => $pid,
				'start' => $start,
				'end' => $end
			));
		}
		if ($query1) {
			$html .= '<table width="100%" style="font-size:1em"><tr><th style="background-color: gray;color: #FFFFFF;">ENCOUNTERS</th></tr></table>';
			foreach ($query1 as $row1) {
				$html .= $this->encounters_view($row1->eid, $pid, Session::get('practice_id'))->render();
			}
		}
		if ($query2) {
			$html .= '<pagebreak /><table width="100%" style="font-size:1em"><tr><th style="background-color: gray;color: #FFFFFF;">MESSAGES</th></tr></table>';
			foreach ($query2 as $row2) {
				$html .= $this->t_messages_view($row2->t_messages_id);
			}
		}
		$html .= '</body></html>';
		$this->generate_pdf($html, $file_path_enc, 'footerpdf', $header, '2');
		// Generate CCR
		$file_path_ccr = $directory . '/ccr.pdf';
		if (file_exists($file_path_ccr)) {
			unlink($file_path_ccr);
		}
		$html_ccr = $this->page_intro('Continuity of Care Record', Session::get('practice_id'));
		$html_ccr .= $this->page_ccr($pid)->render();
		$this->generate_pdf($html_ccr, $file_path_ccr, 'footerpdf', $header, '2');
		// Gather documents
		$file_path_docs = $directory . '/printchart_docs.pdf';
		if (file_exists($file_path_docs)) {
			unlink($file_path_docs);
		}
		if ($query3) {
			foreach ($query3 as $row3) {
				$search = $result->documents_dir . $pid . "/";
				$link = $directory_links . "/" . time() . "_" . str_replace($search, '', $row3->documents_url);
				if(!file_exists($link)) {
					symlink($row3->documents_url, $link);
				}
			}
			$documents_commandpdf1 = "gs -sDEVICE=pdfwrite -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . $file_path_docs . " " . $directory_links . "/*.pdf";
			exec($documents_commandpdf1);
			while(!file_exists($file_path_docs)) {
				sleep(2);
			}
		}
		// Compile and save file
		if ($output == 'file' || $output == 'fax') {
			$input = "";
			if (file_exists($file_path_ccr)) {
				$input .= $file_path_ccr;
			}
			if (file_exists($file_path_enc)) {
				$input .= " " . $file_path_enc;
			}
			if (file_exists($file_path_docs)) {
				$input .= " " . $file_path_docs;
			}
			$user_id = Session::get('user_id');
			$file_path = $directory . '/' . $pid . '_' . $lastname . '_' . $firstname . '_' . $dob . '_printchart_final.pdf';
			if (file_exists($file_path)) {
				unlink ($file_path);
			}
			$commandpdf1 = "pdftk " . $input . " cat output " . $file_path;
			$commandpdf2 = escapeshellcmd($commandpdf1);
			exec($commandpdf2);
			while(!file_exists($file_path)) {
				sleep(2);
			}
			return $file_path;
		} else {
			echo "OK";
		}
	}
	
	protected function encounters_view($eid, $pid, $practice_id, $modal=false, $addendum=false)
	{
		$encounterInfo = Encounters::find($eid);
		$data['patientInfo'] = Demographics::find($pid);
		$data['eid'] = $eid;
		$data['encounter_DOS'] = date('F jS, Y; h:i A', $this->human_to_unix($encounterInfo->encounter_DOS));
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$data['date_signed'] = date('F jS, Y; h:i A', $this->human_to_unix($encounterInfo->date_signed));
		$data['age1'] = $encounterInfo->encounter_age;
		$data['dob'] = date('F jS, Y', $this->human_to_unix($data['patientInfo']->DOB));
		$data['age'] = $this->current_age($pid);
		if ($data['patientInfo']->sex == 'm') {
			$data['gender'] = 'Male';
		} 
		if ($data['patientInfo']->sex == 'f') {
			$data['gender'] = 'Female';
		}
		$data['encounter_cc'] = nl2br($encounterInfo->encounter_cc);
		$practiceInfo = Practiceinfo::find($practice_id);
		$hpiInfo = Hpi::find($eid);
		if ($hpiInfo) {
			if ($hpiInfo->hpi != '') {
				$data['hpi'] = '<br><h4>History of Present Illness:</h4><p class="view">';
				$data['hpi'] .= nl2br($hpiInfo->hpi);
				$data['hpi'] .= '</p>';
			}
			if ($hpiInfo->situation != '') {
				$data['hpi'] = '<br><h4>Situation:</h4><p class="view">';
				$data['hpi'] .= nl2br($hpiInfo->situation);
				$data['hpi'] .= '</p>';
			}
		} else {
			$data['hpi'] = '';
		}
		$rosInfo = Ros::find($eid);
		if ($rosInfo) {
			$data['ros'] = '<br><h4>Review of Systems:</h4><p class="view">';
			if ($rosInfo->ros_gen != '') {
				$data['ros'] .= '<strong>General: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gen);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_eye != '') {
				$data['ros'] .= '<strong>Eye: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_eye);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_ent != '') {
				$data['ros'] .= '<strong>Ears, Nose, Throat: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_ent);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_resp != '') {
				$data['ros'] .= '<strong>Respiratory: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_resp);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_cv != '') {
				$data['ros'] .= '<strong>Cardiovascular: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_cv);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_gi != '') {
				$data['ros'] .= '<strong>Gastrointestinal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gi);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_gu != '') {
				$data['ros'] .= '<strong>Genitourinary: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_gu);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_mus != '') {
				$data['ros'] .= '<strong>Musculoskeletal: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_mus);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_neuro != '') {
				$data['ros'] .= '<strong>Neurological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_neuro);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych != '') {
				$data['ros'] .= '<strong>Psychological: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_heme != '') {
				$data['ros'] .= '<strong>Hematological, Lymphatic: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_heme);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_endocrine != '') {
				$data['ros'] .= '<strong>Endocrine: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_endocrine);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_skin != '') {
				$data['ros'] .= '<strong>Skin: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_skin);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_wcc != '') {
				$data['ros'] .= '<strong>Well Child Check: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_wcc);
				$data['ros'] .= '<br /><br />';
			}
			$data['ros'] .= '</p>';
		} else {
			$data['ros'] = '';
		}
		$ohInfo = Other_history::find($eid);
		if ($ohInfo) {
			$data['oh'] = '<br><h4>Other Pertinent History:</h4><p class="view">';
			if ($ohInfo->oh_pmh != '') {
				$data['oh'] .= '<strong>Past Medical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_pmh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_psh != '') {
				$data['oh'] .= '<strong>Past Surgical History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_psh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_fh != '') {
				$data['oh'] .= '<strong>Family History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_fh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_sh != '') {
				$data['oh'] .= '<strong>Social History: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_sh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_etoh != '') {
				$data['oh'] .= '<strong>Alcohol Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_etoh);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_tobacco != '') {
				$data['oh'] .= '<strong>Tobacco Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_tobacco);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_drugs != '') {
				$data['oh'] .= '<strong>Illicit Drug Use: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_drugs);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_employment != '') {
				$data['oh'] .= '<strong>Employment: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_employment);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_meds != '') {
				$data['oh'] .= '<strong>Medications: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_meds);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_supplements != '') {
				$data['oh'] .= '<strong>Supplements: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_supplements);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_allergies != '') {
				$data['oh'] .= '<strong>Allergies: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_allergies);
				$data['oh'] .= '<br /><br />';
			}
			$data['oh'] .= '</p>';
		} else {
			$data['oh'] = '';
		}
		$vitalsInfo1 = Vitals::where('eid', '=', $eid)->get();
		if (count($vitalsInfo1) > 0) {
			foreach ($vitalsInfo1 as $vitalsInfo) {
				$data['vitals'] = '<br><h4>Vital Signs:</h4><p class="view">';
				$data['vitals'] .= '<strong>Date/Time:</strong>';
				$data['vitals'] .= $vitalsInfo->vitals_date . '<br>';
				if ($vitalsInfo->weight != '') {
					$data['vitals'] .= '<strong>Weight: </strong>';
					$data['vitals'] .= $vitalsInfo->weight . ' ' . $practiceInfo->weight_unit . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->height != '') {
					$data['vitals'] .= '<strong>Height: </strong>';
					$data['vitals'] .= $vitalsInfo->height . ' ' . $practiceInfo->height_unit . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->headcircumference != '') {
					$data['vitals'] .= '<strong>Head Circumference: </strong>';
					$data['vitals'] .= $vitalsInfo->headcircumference . ' ' . $practiceInfo->hc_unit . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->BMI != '') {
					$data['vitals'] .= '<strong>Body Mass Index: </strong>';
					$data['vitals'] .= $vitalsInfo->BMI . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->temp != '') {
					$data['vitals'] .= '<strong>Temperature: </strong>';
					$data['vitals'] .= $vitalsInfo->temp . ' ' . $practiceInfo->temp_unit . ', ' . $vitalsInfo->temp_method . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->bp_systolic != '' && $vitalsInfo->bp_diastolic != '') {
					$data['vitals'] .= '<strong>Blood Pressure: </strong>';
					$data['vitals'] .= $vitalsInfo->bp_systolic . '/' . $vitalsInfo->bp_diastolic . ', ' . $vitalsInfo->bp_position . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->pulse != '') {
					$data['vitals'] .= '<strong>Pulse: </strong>';
					$data['vitals'] .= $vitalsInfo->pulse . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->respirations != '') {
					$data['vitals'] .= '<strong>Respirations: </strong>';
					$data['vitals'] .= $vitalsInfo->respirations . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->o2_sat != '') {
					$data['vitals'] .= '<strong>Oxygen Saturations: </strong>';
					$data['vitals'] .= $vitalsInfo->o2_sat . '<br>';
				} else {
					$data['vitals'] .= '';
				}
				if ($vitalsInfo->vitals_other != '') {
					$data['vitals'] .= '<strong>Notes: </strong>';
					$data['vitals'] .= nl2br($vitalsInfo->vitals_other) . '<br>';
				}
				$data['vitals'] .= '</p>';
			}
		} else {
			$data['vitals'] = '';
		}
		$peInfo = Pe::find($eid);
		if ($peInfo) {
			$data['pe'] = '<br><h4>Physical Exam:</h4><p class="view">';
			if ($peInfo->pe_gen1 != '') {
				$data['pe'] .= '<strong>Constitutional: </strong>';
				$data['pe'] .= nl2br($peInfo->pe_gen1);
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_eye1 != '' || $peInfo->pe_eye2 != '' || $peInfo->pe_eye3 != '') {
				$data['pe'] .= '<strong>Eye:</strong>';
				if($peInfo->pe_eye1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye1);
				}
				if($peInfo->pe_eye2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye2);
				}
				if($peInfo->pe_eye3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_eye3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ent1 != '' || $peInfo->pe_ent2 != '' || $peInfo->pe_ent3 != '' || $peInfo->pe_ent4 != '' || $peInfo->pe_ent5 != '' || $peInfo->pe_ent6 != '') {
				$data['pe'] .= '<strong>Ears, Nose, Throat:</strong>';
				if($peInfo->pe_ent1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent1);
				}
				if($peInfo->pe_ent2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent2);
				}
				if($peInfo->pe_ent3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent3);
				}
				if($peInfo->pe_ent4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent4);
				}
				if($peInfo->pe_ent5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent5);
				}
				if($peInfo->pe_ent6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ent6);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_neck1 != '' || $peInfo->pe_neck2 != '') {
				$data['pe'] .= '<strong>Neck:</strong>';
				if($peInfo->pe_neck1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck1);
				}
				if($peInfo->pe_neck2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neck2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_resp1 != '' || $peInfo->pe_resp2 != '' || $peInfo->pe_resp3 != '' || $peInfo->pe_resp4 != '') {
				$data['pe'] .= '<strong>Respiratory:</strong>';
				if($peInfo->pe_resp1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp1);
				}
				if($peInfo->pe_resp2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp2);
				}
				if($peInfo->pe_resp3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp3);
				}
				if($peInfo->pe_resp4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_resp4);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_cv1 != '' || $peInfo->pe_cv2 != '' || $peInfo->pe_cv3 != '' || $peInfo->pe_cv4 != '' || $peInfo->pe_cv5 != '' || $peInfo->pe_cv6 != '') {
				$data['pe'] .= '<strong>Cardiovascular:</strong>';
				if($peInfo->pe_cv1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv1);
				}
				if($peInfo->pe_cv2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv2);
				}
				if($peInfo->pe_cv3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv3);
				}
				if($peInfo->pe_cv4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv4);
				}
				if($peInfo->pe_cv5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv5);
				}
				if($peInfo->pe_cv6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_cv6);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ch1 != '' || $peInfo->pe_ch2 != '') {
				$data['pe'] .= '<strong>Chest:</strong>';
				if($peInfo->pe_ch1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch1);
				}
				if($peInfo->pe_ch2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ch2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_gi1 != '' || $peInfo->pe_gi2 != '' || $peInfo->pe_gi3 != '' || $peInfo->pe_gi4 != '') {
				$data['pe'] .= '<strong>Gastrointestinal:</strong>';
				if($peInfo->pe_gi1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi1);
				}
				if($peInfo->pe_gi2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi2);
				}
				if($peInfo->pe_gi3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi3);
				}
				if($peInfo->pe_gi4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gi4);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_gu1 != '' || $peInfo->pe_gu2 != '' || $peInfo->pe_gu3 != '' || $peInfo->pe_gu4 != '' || $peInfo->pe_gu5 != '' || $peInfo->pe_gu6 != '' || $peInfo->pe_gu7 != '' || $peInfo->pe_gu8 != '' || $peInfo->pe_gu9 != '') {
				$data['pe'] .= '<strong>Genitourinary:</strong>';
				if($peInfo->pe_gu1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu1);
				}
				if($peInfo->pe_gu2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu2);
				}
				if($peInfo->pe_gu3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu3);
				}
				if($peInfo->pe_gu4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu4);
				}
				if($peInfo->pe_gu5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu5);
				}
				if($peInfo->pe_gu6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu6);
				}
				if($peInfo->pe_gu7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu7);
				}
				if($peInfo->pe_gu8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu8);
				}
				if($peInfo->pe_gu9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_gu9);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_lymph1 != '' || $peInfo->pe_lymph2 != '' || $peInfo->pe_lymph3 != '') {
				$data['pe'] .= '<strong>Lymphatic:</strong>';
				if($peInfo->pe_lymph1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph1);
				}
				if($peInfo->pe_lymph2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph2);
				}
				if($peInfo->pe_lymph3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_lymph3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_ms1 != '' || $peInfo->pe_ms2 != '' || $peInfo->pe_ms3 != '' || $peInfo->pe_ms4 != '' || $peInfo->pe_ms5 != '' || $peInfo->pe_ms6 != '' || $peInfo->pe_ms7 != '' || $peInfo->pe_ms8 != '' || $peInfo->pe_ms9 != '' || $peInfo->pe_ms10 != '' || $peInfo->pe_ms11 != '' || $peInfo->pe_ms12 != '') {
				$data['pe'] .= '<strong>Musculoskeletal:</strong>';
				if($peInfo->pe_ms1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms1);
				}
				if($peInfo->pe_ms2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms2);
				}
				if($peInfo->pe_ms3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms3);
				}
				if($peInfo->pe_ms4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms4);
				}
				if($peInfo->pe_ms5 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms5);
				}
				if($peInfo->pe_ms6 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms6);
				}
				if($peInfo->pe_ms7 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms7);
				}
				if($peInfo->pe_ms8 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms8);
				}
				if($peInfo->pe_ms9 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms9);
				}
				if($peInfo->pe_ms10 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms10);
				}
				if($peInfo->pe_ms11 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms11);
				}
				if($peInfo->pe_ms12 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_ms12);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_skin1 != '' || $peInfo->pe_skin2 != '') {
				$data['pe'] .= '<strong>Skin:</strong>';
				if($peInfo->pe_skin1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin1);
				}
				if($peInfo->pe_skin2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_skin2);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_neuro1 != '' || $peInfo->pe_neuro2 != '' || $peInfo->pe_neuro3 != '') {
				$data['pe'] .= '<strong>Neurologic:</strong>';
				if($peInfo->pe_neuro1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro1);
				}
				if($peInfo->pe_neuro2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro2);
				}
				if($peInfo->pe_neuro3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_neuro3);
				}
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_psych1 != '' || $peInfo->pe_psych2 != '' || $peInfo->pe_psych3 != '' || $peInfo->pe_psych4 != '') {
				$data['pe'] .= '<strong>Psychiatric:</strong>';
				if($peInfo->pe_psych1 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych1);
				}
				if($peInfo->pe_psych2 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych2);
				}
				if($peInfo->pe_psych3 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych3);
				}
				if($peInfo->pe_psych4 != '') {
					$data['pe'] .= ' ' . nl2br($peInfo->pe_psych4);
				}
				$data['pe'] .= '<br /><br />';
			}
			$data['pe'] .= '</p>';
		} else {
			$data['pe'] = '';
		}
		$labsInfo = Labs::find($eid);
		if ($labsInfo) {
			$data['labs'] = '<br><h4>Laboratory Testing:</h4><p class="view">';
			if ($labsInfo->labs_ua_urobili != '' || $labsInfo->labs_ua_bilirubin != '' || $labsInfo->labs_ua_ketones != '' || $labsInfo->labs_ua_glucose != '' || $labsInfo->labs_ua_protein != '' || $labsInfo->labs_ua_nitrites != '' || $labsInfo->labs_ua_leukocytes != '' || $labsInfo->labs_ua_blood != '' || $labsInfo->labs_ua_ph != '' || $labsInfo->labs_ua_spgr != '' || $labsInfo->labs_ua_color != '' || $labsInfo->labs_ua_clarity != ''){
				$data['labs'] .= '<strong>Dipstick Urinalysis:</strong><br /><table>';
				if($labsInfo->labs_ua_urobili != '') {
					$data['labs'] .= '<tr><th align=\"left\">Urobilinogen:</th><td align=\"left\">' . $labsInfo->labs_ua_urobili . '</td></tr>';
				}
				if($labsInfo->labs_ua_bilirubin != '') {
					$data['labs'] .= '<tr><th align=\"left\">Bilirubin:</th><td align=\"left\">' . $labsInfo->labs_ua_bilirubin . '</td></tr>';
				}
				if($labsInfo->labs_ua_ketones != '') {
					$data['labs'] .= '<tr><th align=\"left\">Ketones:</th><td align=\"left\">' . $labsInfo->labs_ua_ketones . '</td></tr>';
				}
				if($labsInfo->labs_ua_glucose != '') {
					$data['labs'] .= '<tr><th align=\"left\">Glucose:</th><td align=\"left\">' . $labsInfo->labs_ua_glucose . '</td></tr>';
				}
				if($labsInfo->labs_ua_protein != '') {
					$data['labs'] .= '<tr><th align=\"left\">Protein:</th><td align=\"left\">' . $labsInfo->labs_ua_protein . '</td></tr>';
				}
				if($labsInfo['labs_ua_nitrites'] != '') {
					$data['labs'] .= '<tr><th align=\"left\">Nitrites:</th><td align=\"left\">' . $labsInfo->labs_ua_nitrites . '</td></tr>';
				}
				if($labsInfo->labs_ua_leukocytes != '') {
					$data['labs'] .= '<tr><th align=\"left\">Leukocytes:</th><td align=\"left\">' . $labsInfo->labs_ua_leukocytes . '</td></tr>';
				}
				if($labsInfo->labs_ua_blood != '') {
					$data['labs'] .= '<tr><th align=\"left\">Blood:</th><td align=\"left\">' . $labsInfo->labs_ua_blood . '</td></tr>';
				}
				if($labsInfo->labs_ua_ph != '') {
					$data['labs'] .= '<tr><th align=\"left\">pH:</th><td align=\"left\">' . $labsInfo->labs_ua_ph . '</td></tr>';
				}
				if($labsInfo->labs_ua_spgr != '') {
					$data['labs'] .= '<tr><th align=\"left\">Specific gravity:</th><td align=\"left\">' . $labsInfo->labs_ua_spgr . '</td></tr>';
				}
				if($labsInfo->labs_ua_color != '') {
					$data['labs'] .= '<tr><th align=\"left\">Color:</th><td align=\"left\">' . $labsInfo->labs_ua_color . '</td></tr>';
				}
				if($labsInfo->labs_ua_clarity != '') {
					$data['labs'] .= '<tr><th align=\"left\">Clarity:</th><td align=\"left\">' . $labsInfo->labs_ua_clarity . '</td></tr>';
				}
				$data['labs'] .= '</table>';
			}
			if ($labsInfo->labs_upt != '') {
				$data['labs'] .= '<strong>Urine HcG: </strong>';
				$data['labs'] .= $labsInfo->labs_upt;
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo->labs_strep != '') {
				$data['labs'] .= '<strong>Rapid Strep: </strong>';
				$data['labs'] .= $labsInfo->labs_strep;
				$data['labs'] .= '<br /><br />';
			} 
			if ($labsInfo->labs_mono != '') {
				$data['labs'] .= '<strong>Mono Spot: </strong>';
				$data['labs'] .= $labsInfo->labs_mono;
				$data['labs'] .= '<br>';
			}
			if ($labsInfo->labs_flu != '') {
				$data['labs'] .= '<strong>Rapid Influenza: </strong>';
				$data['labs'] .= $labsInfo->labs_flu;
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_microscope != '') {
				$data['labs'] .= '<strong>Micrscopy: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_microscope);
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_glucose != '') {
				$data['labs'] .= '<strong>Fingerstick Glucose: </strong>';
				$data['labs'] .= $labsInfo->labs_glucose;
				$data['labs'] .= '<br /><br />';
			}
			if ($labsInfo->labs_other != '') {
				$data['labs'] .= '<strong>Other: </strong>';
				$data['labs'] .= nl2br($labsInfo->labs_other);
				$data['labs'] .= '<br /><br />';
			}
			$data['labs'] .= '</p>';
		} else {
			$data['labs'] = '';
		}
		$procedureInfo = Procedure::find($eid);
		if ($procedureInfo) {
			$data['procedure'] = '<br><h4>Procedures:</h4><p class="view">';
			if ($procedureInfo->proc_type != '') {
				$data['procedure'] .= '<strong>Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_type);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_description != '') {
				$data['procedure'] .= '<strong>Description of Procedure: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_description);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_complications != '') {
				$data['procedure'] .= '<strong>Complications: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_complications);
				$data['procedure'] .= '<br /><br />';
			}
			if ($procedureInfo->proc_ebl != '') {
				$data['procedure'] .= '<strong>Estimated Blood Loss: </strong>';
				$data['procedure'] .= nl2br($procedureInfo->proc_ebl);
				$data['procedure'] .= '<br /><br />';
			}
			$data['procedure'] .= '</p>';
		} else {
			$data['procedure'] = '';
		}
		$assessmentInfo = Assessment::find($eid);
		if ($assessmentInfo) {
			$data['assessment'] = '<br><h4>Assessment:</h4><p class="view">';
			if ($assessmentInfo->assessment_1 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_1 . '</strong><br />';
				if ($assessmentInfo->assessment_2 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_2 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_2 . '</strong><br />';
				if ($assessmentInfo->assessment_3 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_3 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_3 . '</strong><br />';
				if ($assessmentInfo->assessment_4 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_4 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_4 . '</strong><br />';
				if ($assessmentInfo->assessment_5 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_5 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_5 . '</strong><br />';
				if ($assessmentInfo->assessment_6 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_6 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_6 . '</strong><br />';
				if ($assessmentInfo->assessment_7 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_7 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_7 . '</strong><br />';
				if ($assessmentInfo->assessment_8 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_8 != '') {
				$data['assessment'] .= '<strong>' . $assessmentInfo->assessment_8 . '</strong><br /><br />';
			}
			if ($assessmentInfo->assessment_other != '') {
				$data['assessment'] .= '<strong>Additional Diagnoses: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_other);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_ddx != '') {
				$data['assessment'] .= '<strong>Differential Diagnoses Considered: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_ddx);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_notes != '') {
				$data['assessment'] .= '<strong>Assessment Discussion: </strong>';
				$data['assessment'] .= nl2br($assessmentInfo->assessment_notes);
				$data['assessment'] .= '<br /><br />';
			}
			$data['assessment'] .= '</p>';
		} else {
			$data['assessment'] = '';
		}
		$ordersInfo1 = Orders::where('eid', '=', $eid)->get();
		if (count($ordersInfo1) > 0) {
			$data['orders'] = '<br><h4>Orders:</h4><p class="view">';
			$orders_lab_array = array();
			$orders_radiology_array = array();
			$orders_cp_array = array();
			$orders_referrals_array = array();
			foreach ($ordersInfo1 as $ordersInfo) {
				$address_row1 = Addressbook::find($ordersInfo->address_id);
				if ($ordersInfo->orders_labs != '') {
					$orders_lab_array[] = 'Orders sent to ' . $address_row1->displayname . ': '. nl2br($ordersInfo->orders_labs) . '<br />';
				}
				if ($ordersInfo->orders_radiology != '') {
					$orders_radiology_array[] = 'Orders sent to ' . $address_row1->displayname . ': '. nl2br($ordersInfo->orders_radiology) . '<br />';
				}
				if ($ordersInfo->orders_cp != '') {
					$orders_cp_array[] = 'Orders sent to ' . $address_row1->displayname . ': '. nl2br($ordersInfo->orders_cp) . '<br />';
				}
				if ($ordersInfo->orders_referrals != '') {
					$orders_referrals_array[] = 'Referral sent to ' . $address_row1->displayname . ': '. nl2br($ordersInfo->orders_referrals) . '<br />';
				}
			}
			if (count($orders_lab_array) > 0) {
				$data['orders'] .= '<strong>Labs: </strong>';
				foreach ($orders_lab_array as $lab_item) {
					$data['orders'] .= $lab_item;
				}
			}
			if (count($orders_radiology_array) > 0) {
				$data['orders'] .= '<strong>Imaging: </strong>';
				foreach ($orders_radiology_array as $radiology_item) {
					$data['orders'] .= $radiology_item;
				}
			}
			if (count($orders_cp_array) > 0) {
				$data['orders'] .= '<strong>Cardiopulmonary: </strong>';
				foreach ($orders_cp_array as $cp_item) {
					$data['orders'] .= $cp_item;
				}
			}
			if (count($orders_referrals_array) > 0) {
				$data['orders'] .= '<strong>Referrals: </strong>';
				foreach ($orders_referrals_array as $referrals_item) {
					$data['orders'] .= $referrals_item;
				}
			}
			$data['orders'] .= '</p>';
		} else {
			$data['orders'] = '';
		}
		
		$rxInfo = Rx::find($eid);
		if ($rxInfo) {
			$data['rx'] = '<br><h4>Prescriptions and Immunizations:</h4><p class="view">';
			if ($rxInfo->rx_rx != '') {
				$data['rx'] .= '<strong>Prescriptions Given: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_rx);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_supplements != '') {
				$data['rx'] .= '<strong>Supplements Recommended: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_supplements);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_immunizations != '') {
				$data['rx'] .= '<strong>Immunizations Given: </strong>';
				$data['rx'] .= 'CDC Vaccine Information Sheets given for each immunization and consent obtained.<br />';
				$data['rx'] .= nl2br($rxInfo->rx_immunizations);
				$data['rx'] .= '<br /><br />';
			}
			$data['rx'] .= '</p>';
		} else {
			$data['rx'] = '';
		}		
		$planInfo = Plan::find($eid);
		if ($planInfo) {
			$data['plan'] = '<br><h4>Plan:</h4><p class="view">';
			if ($planInfo->plan != '') {
				$data['plan'] .= '<strong>Recommendations: </strong>';
				$data['plan'] .= nl2br($planInfo->plan);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo->followup != '') {
				$data['plan'] .= '<strong>Followup: </strong>';
				$data['plan'] .= nl2br($planInfo->followup);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo->duration != '') {
				$data['plan'] .= 'Counseling and face-to-face time consists of more than 50 percent of the visit.  Total face-to-face time is ';
				$data['plan'] .= $planInfo->duration . ' minutes.';
				$data['plan'] .= '<br /><br />';
			}
			$data['plan'] .= '</p>';
		} else {
			$data['plan'] = '';
		}		
		$billing_query = Billing_core::where('eid', '=', $eid)->get();
		if ($billing_query) {
			$data['billing'] = '<p class="view">';
			$billing_count = 0;
			foreach ($billing_query as $billing_row) {
				if ($billing_count > 0) {
					$data['billing'] .= ',' . $billing_row->cpt;
				} else {
					$data['billing'] .= '<strong>CPT Codes: </strong>';
					$data['billing'] .= $billing_row->cpt;
				}
				$billing_count++;
			}
			if ($encounterInfo->bill_complex != '') {
				$data['billing'] .= '<br><strong>Medical Complexity: </strong>';
				$data['billing'] .= nl2br($encounterInfo->bill_complex);
				$data['billing'] .= '<br /><br />';
			}
			$data['billing'] .= '</p>';
		} else {
			$data['billing'] = '';
		}
		if ($modal == true) {
			if ($encounterInfo->encounter_signed == 'No') {
				$data['status']	= 'Draft';
			} else {
				$data['status'] = 'Signed on ' . date('F jS, Y', $this->human_to_unix($encounterInfo->date_signed)) . '.';
			}
			if ($addendum == true) {
				$data['addendum'] = true;
			} else {
				$data['addendum'] = false;
			}
			return View::make('modal_view', $data);
		} else {
			return View::make('encounter_view', $data);
		}
	}
	
	protected function t_messages_view($t_messages_id)
	{
		$row = T_messages::find($t_messages_id);
		$text = '<table cellspacing="2" style="font-size:0.9em; width:100%;"><tr><th style="background-color: gray;color: #FFFFFF; text-align: left;">MESSAGE DETAILS</th></tr><tr><td><h4>Date of Service: </h4>' . date('m/d/Y', $this->human_to_unix($row->t_messages_dos));
		$text .= '<br><h4>Subject: </h4>' . $row->t_messages_subject;
		$text .= '<br><h4>Message: </h4>' . $row->t_messages_message . '<br><hr />Electronically signed by ' . $row->t_messages_provider . '.';
		$text .= '</td></tr></table>';
		return $text;
	}
	
	protected function page_intro($title, $practice_id)
	{
		$practice = Practiceinfo::find($practice_id);
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice['street_address2'] != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$data['practiceLogo'] = $this->practice_logo($practice_id);
		$data['title'] = $title;
		return View::make('pdf.intro', $data);
	}
	
	protected function page_results($pid, $results, $patient_name)
	{
		$body = '';
		$body .= "<br>Test results for " . $patient_name . "<br><br>";
		$body .= "<table><tr><th>Date</th><th>Test</th><th>Result</th><th>Units</th><th>Normal reference range</th><th>Flags</th></tr>";
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['test_datetime'] . "</td><td>" . $results_row1['test_name'] . "</td><td>" . $results_row1['test_result'] . "</td><td>" . $results_row1['test_units'] . "</td><td>" . $results_row1['test_reference'] . "</td><td>" . $results_row1['test_flags'] . "</td></tr>";
			$from = $results_row1['test_from'];
		}
		$body .= "</table><br>" . $from;
		$body .= '</body></html>';
		return $body;
	}
	
	protected function page_ccr($pid)
	{
		$data['patientInfo'] = Demographics::find($pid);
		$data['dob'] = date('m/d/Y', $this->human_to_unix($data['patientInfo']->DOB));
		$data['insuranceInfo'] = '';
		$query_in = Insurance::where('pid', '=', $pid)->where('insurance_plan_active', '=', 'Yes')->get();
		if ($query_in) {
			foreach ($query_in as $row_in) {
				$data['insuranceInfo'] .= $row_in->insurance_plan_name . '; ID: ' . $row_in->insurance_id_num . '; Group: ' . $row_in->insurance_group . '; ' . $row_in->insurance_insu_lastname . ', ' . $row_in->insurance_insu_firstname . '<br><br>';
			}
		}
		$body = 'Active Issues:<br />';
		$query = Issues::where('pid', '=', $pid)
			->where('issue_date_inactive', '=', '0000-00-00 00:00:00')
			->get();
		if ($query) {
			$body .= '<ul>';
			foreach ($query as $row) {
				$body .= '<li>' . $row->issue . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Active Medications:<br />';
		$query1 = Rx_list::where('pid', '=', $pid)
			->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
			->where('rxl_date_old', '=', '0000-00-00 00:00:00')
			->get();
		if ($query1) {
			$body .= '<ul>';
			foreach ($query1 as $row1) {
				if ($row1->rxl_sig == '') {
					$body .= '<li>' . $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_instructions . ' for ' . $row1->rxl_reason . '</li>';
				} else {
					$body .= '<li>' . $row1->rxl_medication . ' ' . $row1->rxl_dosage . ' ' . $row1->rxl_dosage_unit . ', ' . $row1->rxl_sig . ' ' . $row1->rxl_route . ' ' . $row1->rxl_frequency . ' for ' . $row1->rxl_reason . '</li>';
				}
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Immunizations:<br />';
		$query2 = Immunizations::where('pid', '=', $pid)
			->orderBy('imm_immunization', 'asc')
			->orderBy('imm_sequence', 'asc')
			->get();
		if ($query2) {
			$body .= '<ul>';
			foreach ($query2 as $row2) {
				if ($row2->imm_sequence == '1') {
					$sequence = 'first';
				}
				if ($row2->imm_sequence == '2') {
					$sequence = 'second';
				}
				if ($row2->imm_sequence == '3') {
					$sequence = 'third';
				}
				if ($row2->imm_sequence == '4') {
					$sequence = 'fourth';
				}
				if ($row2->imm_sequence == '5') {
					$sequence = 'fifth';
				}
				$body .= '<li>' . $row2->imm_immunization . ', ' . $sequence . ', given on ' . date('F jS, Y', $this->human_to_unix($row2->imm_date)) . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'None.';
		}
		$body .= '<hr />Allergies:<br />';
		$query3 = Allergies::where('pid', '=', $pid)
			->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')
			->get();
		if ($query3) {
			$body .= '<ul>';
			foreach ($query3 as $row3) {
				$body .= '<li>' . $row3->allergies_med . ' - ' . $row3->allergies_reaction . '</li>';
			}
			$body .= '</ul>';
		} else {
			$body .= 'No known allergies.';
		}
		$body .= '<br />Printed by ' . Session::get('displayname') . '.';
		$data['letter'] = $body;
		return View::make('pdf.ccr_page',$data);
	}
	
	protected function page_coverpage($job_id, $totalpages, $faxrecipients, $date)
	{
		$row = Sendfax::find($job_id);
		$data = array(
			'user' => Session::get('displayname'),
			'faxrecipients' => $faxrecipients,
			'faxsubject' => $row->faxsubject,
			'faxmessage' => $row->faxmessage,
			'faxpages' => $totalpages,
			'faxdate' => $date
		);
		return View::make('pdf.coverpage',$data);
	}
	
	protected function page_letter_reply($body)
	{
		$pid = Session::get('pid');
		$row = Demographics::find($pid);
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['firstname'] = $row->firstname;
		$data['body'] = nl2br($body) . "<br><br>Please contact me if you have any questions.";
		$data['signature'] = $this->signature(Session::get('user_id'));
		$data['date'] = date('F jS, Y');
		return View::make('pdf.letter_page', $data);
	}
	
	protected function page_letter($letter_to, $letter_body, $address_id)
	{
		$body = '';
		if ($address_id != '') {
			$row = Addressbook::find($address_id);
			$body .= $row->displayname . '<br>' . $row->street_address1;
			if (isset($row->street_address2)) {
				$body .= '<br>' . $row->street_address2;
			}
			$body .= '<br>' . $row->city . ', ' . $row->state . ' ' . $row->zip;
			$body .= '<br><br>';
		}
		$body .= $letter_to . ':';
		$body .= '<br><br>';
		$body .= nl2br($letter_body);
		$sig = $this->signature(Session::get('user_id'));
		$body .= '<br><br>Sincerely,<br>' . $sig;
		$body .= '</body></html>';
		return $body;
	}
	
	protected function page_immunization_list()
	{
		$pid = Session::get('pid');	
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$patient = Demographics::find($pid);
		$data['patientInfo1'] = $patient->firstname . ' ' . $patient->lastname;
		$data['patientInfo2'] = $patient->address;
		$data['patientInfo3'] = $patient->city . ', ' . $patient->state . ' ' . $patient->zip;
		$data['firstname'] = $patient->firstname;
		$data['body'] = 'Immunizations for ' . $patient->firstname . ' ' . $patient->lastname . ':<br />';
		$query = DB::table('immunizations')->where('pid', '=', $pid)->orderBy('imm_immunization', 'asc')->orderBy('imm_sequence', 'asc')->get();
		if ($query) {
			$data['body'] .= '<ul>';
			foreach ($query as $row) {
				if ($row->imm_sequence == '1') {
					$sequence = 'first';
				}
				if ($row->imm_sequence == '2') {
					$sequence = 'second';
				}
				if ($row->imm_sequence == '3') {
					$sequence = 'third';
				}
				if ($row->imm_sequence == '4') {
					$sequence = 'fourth';
				}
				if ($row->imm_sequence == '5') {
					$sequence = 'fifth';
				}
				$data['body'] .= '<li>' . $row->imm_immunization . ', ' . $sequence . ', given on ' . date('F jS, Y', $this->human_to_unix($row->imm_date)) . '</li>';
			}
			$data['body'] .= '</ul>';
		} else {
			$data['body'] .= 'None.';
		}
		$data['body'] .= '<br />Printed by ' . Session::get('displayname') . '.';
		$data['date'] = date('F jS, Y');
		$data['signature'] = $this->signature(Session::get('user_id'));
		return View::make('pdf.letter_page', $data);
	}
	
	protected function page_orders($orders_id)
	{
		$pid = Session::get('pid');
		$data['orders'] = DB::table('orders')->where('orders_id', '=', $orders_id)->first();
		if ($data['orders']->orders_labs != '') {
			$data['title'] = "LABORATORY ORDER";
			$data['title1'] = "LABORATORY PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_labs_icd);
			$data['text'] = nl2br($data['orders']->orders_labs) . "<br><br>" . nl2br($data['orders']->orders_labs_obtained);
		}
		if ($data['orders']->orders_radiology != '') {
			$data['title'] = "IMAGING ORDER";
			$data['title1'] = "IMAGING PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_radiology_icd);
			$data['text'] = nl2br($data['orders']->orders_radiology);
		}
		if ($data['orders']->orders_cp != '') {
			$data['title'] = "CARDIOPULMONARY ORDER";
			$data['title1'] = "CARDIOPULMONARY PROVIDER";
			$data['title2'] = "ORDER";
			$data['dx'] = nl2br($data['orders']->orders_cp_icd);
			$data['text'] = nl2br($data['orders']->orders_cp);
		}
		if ($data['orders']->orders_referrals != '') {
			$data['title'] = "REFERRAL/GENERAL ORDERS";
			$data['title1'] = "REFERRAL PROVIDER";
			$data['title2'] = "DETAILS";
			$data['dx'] = nl2br($data['orders']->orders_referrals_icd);
			$data['text'] = nl2br($data['orders']->orders_referrals);
		}
		$data['address'] = DB::table('addressbook')->where('address_id', '=', $data['orders']->address_id)->first();
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$data['patientInfo'] = Demographics::find($pid);
		$data['dob'] = date('m/d/Y', $this->human_to_unix($data['patientInfo']->DOB));
		if ($data['patientInfo']->sex == 'm') {
			$data['sex'] = 'Male';
		} else {
			$data['sex'] = 'Female';
		}
		$data['orders_date'] = date('m/d/Y', $this->human_to_unix($data['orders']->orders_date));
		$data['insuranceInfo'] = nl2br($data['orders']->orders_insurance);
		$data['signature'] = $this->signature($data['orders']->id);
		if ($data['orders']->orders_referrals != '') {
			return View::make('pdf.referral_page', $data);
		} else {
			return View::make('pdf.order_page', $data);
		}
	}
	
	protected function page_plan($eid)
	{
		$pid = Session::get('pid');
		$ordersInfo = DB::table('orders')->where('eid', '=', $eid)->first();
		if ($ordersInfo) {
			$data['orders'] = '<br><h4>Orders:</h4><p class="view">';
			$ordersInfo_labs_query = DB::table('orders')->where('eid', '=', $eid)->where('orders_labs', '!=', '')->get();
			if ($ordersInfo_labs_query) {
				$data['orders'] .= '<strong>Labs: </strong>';
				foreach ($ordersInfo_labs_query as $ordersInfo_labs_result) {
					$text1 = nl2br($ordersInfo_labs_result->orders_labs);
					$address_row1 = Addressbook::find($ordersInfo_labs_result->address_id);
					$data['orders'] .= 'Orders sent to ' . $address_row1->displayname . ': '. $text1 . '<br />';
					$data['orders'] .= $address_row1->street_address1 . '<br />';
					if ($address_row1->street_address2 != '') {
						$data['orders'] .= $address_row1->street_address2 . '<br />';
					}
					$data['orders'] .= $address_row1->city . ', ' . $address_row1->state . ' ' . $address_row1->zip . '<br />';
					$data['orders'] .= $address_row1->phone . '<br />';
				}
			}
			$ordersInfo_rad_query = DB::table('orders')->where('eid', '=', $eid)->where('orders_radiology', '!=', '')->get();
			if ($ordersInfo_rad_query) {
				$data['orders'] .= '<strong>Imaging: </strong>';
				foreach ($ordersInfo_rad_query as $ordersInfo_rad_result) {
					$text2 = nl2br($ordersInfo_rad_result->orders_radiology);
					$address_row2 = Addressbook::find($ordersInfo_rad_result->address_id);
					$data['orders'] .= 'Orders sent to ' . $address_row2->displayname . ': '. $text2 . '<br />';
					$data['orders'] .= $address_row2->street_address1 . '<br />';
					if ($address_row2->street_address2 != '') {
						$data['orders'] .= $address_row2->street_address2 . '<br />';
					}
					$data['orders'] .= $address_row2->city . ', ' . $address_row2->state . ' ' . $address_row2->zip . '<br />';
					$data['orders'] .= $address_row2->phone . '<br />';
				}
			}
			$ordersInfo_cp_query = DB::table('orders')->where('eid', '=', $eid)->where('orders_cp', '!=', '')->get();
			if ($ordersInfo_cp_query) {
				$data['orders'] .= '<strong>Cardiopulmonary: </strong>';
				foreach ($ordersInfo_cp_query as $ordersInfo_cp_result) {
					$text3 = nl2br($ordersInfo_cp_result->orders_cp);
					$address_row3 = Addressbook::find($ordersInfo_cp_result->address_id);
					$data['orders'] .= 'Orders sent to ' . $address_row3->displayname . ': '. $text3 . '<br />';
					$data['orders'] .= $address_row3->street_address1 . '<br />';
					if ($address_row3->street_address2 != '') {
						$data['orders'] .= $address_row3->street_address2 . '<br />';
					}
					$data['orders'] .= $address_row3->city . ', ' . $address_row3->state . ' ' . $address_row3->zip . '<br />';
					$data['orders'] .= $address_row3->phone . '<br />';
				}
			}
			$ordersInfo_ref_query = DB::table('orders')->where('eid', '=', $eid)->where('orders_referrals', '!=', '')->get();
			if ($ordersInfo_ref_query) {
				$data['orders'] .= '<strong>Referrals: </strong>';
				foreach ($ordersInfo_ref_query as $ordersInfo_ref_result) {
					$address_row4 = Addressbook::find($ordersInfo_ref_result->address_id);
					$data['orders'] .= 'Orders sent to ' . $address_row4->displayname . '<br />';
					$data['orders'] .= $address_row4->street_address1 . '<br />';
					if ($address_row4->street_address2 != '') {
						$data['orders'] .= $address_row4->street_address2 . '<br />';
					}
					$data['orders'] .= $address_row4->city . ', ' . $address_row4->state . ' ' . $address_row4->zip . '<br />';
					$data['orders'] .= $address_row4->phone . '<br />';
				}
			}
			$data['orders'] .= '</p>';
		} else {
			$data['orders'] = '';
		}
		$rxInfo = DB::table('rx')->where('eid', '=', $eid)->first();
		if ($rxInfo) {
			$data['rx'] = '<br><h4>Prescriptions and Immunizations:</h4><p class="view">';
			if ($rxInfo->rx_rx!= '') {
				$data['rx'] .= '<strong>Medications: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_orders_summary);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_supplements!= '') {
				$data['rx'] .= '<strong>Supplements to take: </strong>';
				$data['rx'] .= nl2br($rxInfo->rx_supplements_orders_summary);
				$data['rx'] .= '<br /><br />';
			}
			if ($rxInfo->rx_immunizations != '') {
				$data['rx'] .= '<strong>Immunizations: </strong>';
				$data['rx'] .= 'CDC Vaccine Information Sheets given for each immunization and consent obtained.<br />';
				$data['rx'] .= nl2br($rxInfo->rx_immunizations);
				$data['rx'] .= '<br /><br />';
			}
			$data['rx'] .= '</p>';
		} else {
			$data['rx'] = '';
		}
		$planInfo = DB::table('plan')->where('eid', '=', $eid)->first();
		if ($planInfo) {
			$data['plan'] = '<br><h4>Plan:</h4><p class="view">';
			if ($planInfo->plan!= '') {
				$data['plan'] .= '<strong>Recommendations: </strong>';
				$data['plan'] .= nl2br($planInfo->plan);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo->followup != '') {
				$data['plan'] .= '<strong>Followup: </strong>';
				$data['plan'] .= nl2br($planInfo->followup);
				$data['plan'] .= '<br /><br />';
			}
			$data['plan'] .= '</p>';
		} else {
			$data['plan'] = '';
		}
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$data['patientInfo'] = Demographics::find($pid);
		$data['dob'] = date('m/d/Y', $this->human_to_unix($data['patientInfo']->DOB));
		$encounterInfo = Encounters::find($eid);
		$data['encounter_DOS'] = date('F jS, Y', $this->human_to_unix($encounterInfo->encounter_DOS));
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$query1 = DB::table('insurance')->where('pid', '=', $pid)->where('insurance_plan_active', '=', 'Yes')->get();
		$data['insuranceInfo'] = '';
		if ($query1) {
			foreach ($query1 as $row) {
				$data['insuranceInfo'] .= $row->insurance_plan_name . '; ID: ' . $row->insurance_id_num . '; Group: ' . $row->insurance_group . '; ' . $row->insurance_insu_lastname . ', ' . $row->insurance_insu_firstname . '<br><br>';
			}
		}
		return View::make('pdf.instruction_page', $data);
	}
	
	protected function getNumberAppts($id)
	{
		$start_time = strtotime("today 00:00:00");
		$end_time = $start_time + 86400;
		return Schedule::where('provider_id', '=', $id)->whereBetween('start', array($start_time, $end_time))->count();
	}
	
	protected function getSearchData()
	{
		$search_data = array();
		if (Session::get('pid')) {
			$patient = Demographics::find(Session::get('pid'));
			$search_data['pt'] = $patient->firstname . ' ' . $patient->lastname;
		} else {
			$search_data['pt'] = '';
		}
		if (Session::get('eid')) {
			$search_data['encounter'] = Session::get('eid');
		} else {
			$search_data['encounter'] = '';
		}
		return $search_data;
	}
	
	protected function getMenuData()
	{
		$menu_data = array();
		$row = Practiceinfo::find(Session::get('practice_id'));
		$menu_data['mtm'] = $row->mtm_extension;
		$menu_data['id'] = Session::get('pid');
		$row1 = Encounters::where('pid', '=', Session::get('pid'))
			->where('eid', '!=', '')
			->where('practice_id', '=', Session::get('practice_id'))
			->orderBy('eid', 'desc')
			->first();
		if ($row1) {
			$menu_data['lastvisit'] = date('F jS, Y', strtotime($row1->encounter_DOS));
		} else {
			$menu_data['lastvisit'] = "No previous visits.";
		}
		$row2 = Schedule::where('pid', '=', Session::get('pid'))->where('start', '>', time())->get();
		if (isset($row2->start)) {
			$menu_data['nextvisit'] = '<br>' . date('F jS, Y, g:i A', $row2->start);
		} else {
			$menu_data['nextvisit'] = 'None.';
		}
		return $menu_data;
	}
	
	protected function human_to_unix($datestr = '')
	{
		if ($datestr == '') {
			return FALSE;
		}
		$datestr = trim($datestr);
		$datestr = preg_replace("/\040+/", "\040", $datestr);
		if ( ! preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
			return FALSE;
		}
		$split = preg_split("/\040/", $datestr);
		$ex = explode("-", $split['0']);
		$year  = (strlen($ex['0']) == 2) ? '20'.$ex['0'] : $ex['0'];
		$month = (strlen($ex['1']) == 1) ? '0'.$ex['1']  : $ex['1'];
		$day   = (strlen($ex['2']) == 1) ? '0'.$ex['2']  : $ex['2'];
		$ex = explode(":", $split['1']);
		$hour = (strlen($ex['0']) == 1) ? '0'.$ex['0'] : $ex['0'];
		$min  = (strlen($ex['1']) == 1) ? '0'.$ex['1'] : $ex['1'];
		if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2'])) {
			$sec  = (strlen($ex['2']) == 1) ? '0'.$ex['2'] : $ex['2'];
		} else {
			$sec = '00';
		}
		if (isset($split['2'])) {
			$ampm = strtolower($split['2']);
			if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
				$hour = $hour + 12;
			if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
				$hour =  '00';
			if (strlen($hour) == 1)
				$hour = '0'.$hour;
		}
		return mktime($hour, $min, $sec, $month, $day, $year);
	}
	
	protected function timespan($seconds = 1, $time = '')
	{
		$lang['date_year'] = "Year";
		$lang['date_years'] = "Years";
		$lang['date_month'] = "Month";
		$lang['date_months'] = "Months";
		$lang['date_week'] = "Week";
		$lang['date_weeks'] = "Weeks";
		$lang['date_day'] = "Day";
		$lang['date_days'] = "Days";
		$lang['date_hour'] = "Hour";
		$lang['date_hours'] = "Hours";
		$lang['date_minute'] = "Minute";
		$lang['date_minutes'] = "Minutes";
		$lang['date_second'] = "Second";
		$lang['date_seconds'] = "Seconds";
		if ( ! is_numeric($seconds)) {
			$seconds = 1;
		}
		if ( ! is_numeric($time)) {
			$time = time();
		}
		if ($time <= $seconds) {
			$seconds = 1;
		} else {
			$seconds = $time - $seconds;
		}
		$str = '';
		$years = floor($seconds / 31536000);
		if ($years > 0) {
			$str .= $years.' '.$lang[(($years	> 1) ? 'date_years' : 'date_year')].', ';
		}
		$seconds -= $years * 31536000;
		$months = floor($seconds / 2628000);
		if ($years > 0 OR $months > 0) {
			if ($months > 0) {
				$str .= $months.' '.$lang[(($months	> 1) ? 'date_months' : 'date_month')].', ';
			}
			$seconds -= $months * 2628000;
		}
		$weeks = floor($seconds / 604800);
		if ($years > 0 OR $months > 0 OR $weeks > 0){
			if ($weeks > 0) {
				$str .= $weeks.' '.$lang[(($weeks	> 1) ? 'date_weeks' : 'date_week')].', ';
			}
			$seconds -= $weeks * 604800;
		}
		$days = floor($seconds / 86400);
		if ($months > 0 OR $weeks > 0 OR $days > 0) {
			if ($days > 0) {
				$str .= $days.' '.$lang[(($days	> 1) ? 'date_days' : 'date_day')].', ';
			}
			$seconds -= $days * 86400;
		}
		$hours = floor($seconds / 3600);
		if ($days > 0 OR $hours > 0) {
			if ($hours > 0) {
				$str .= $hours.' '.$lang[(($hours	> 1) ? 'date_hours' : 'date_hour')].', ';
			}
			$seconds -= $hours * 3600;
		}
		$minutes = floor($seconds / 60);
		if ($days > 0 OR $hours > 0 OR $minutes > 0) {
			if ($minutes > 0) {
				$str .= $minutes.' '.$lang[(($minutes	> 1) ? 'date_minutes' : 'date_minute')].', ';
			}
			$seconds -= $minutes * 60;
		}
		if ($str == '') {
			$str .= $seconds.' '.$lang[(($seconds	> 1) ? 'date_seconds' : 'date_second')].', ';
		}
		return substr(trim($str), 0, -1);
	}
	
	protected function rpHash($value) 
	{ 
		switch(PHP_INT_SIZE) {
			case 4:
				$hash = 5381; 
				$value = strtoupper($value); 
				for($i = 0; $i < strlen($value); $i++) { 
					$hash = (($hash << 5) + $hash) + ord(substr($value, $i));
				}
				return $hash;
				break;
			case 8:
				$hash = 5381; 
				$value = strtoupper($value); 
				for($i = 0; $i < strlen($value); $i++) { 
					$hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i)); 
				} 
				return $hash; 
			break;
		}
		$hash = 5381; 
		$value = strtoupper($value); 
		for($i = 0; $i < strlen($value); $i++) { 
			$hash = (($hash << 5) + $hash) + ord(substr($value, $i));
		}
		return $hash; 
	}
	
	protected function leftShift32($number, $steps)
	{
		$binary = decbin($number); 
		$binary = str_pad($binary, 32, "0", STR_PAD_LEFT); 
		$binary = $binary.str_repeat("0", $steps); 
		$binary = substr($binary, strlen($binary) - 32); 
		return ($binary{0} == "0" ? bindec($binary) : -(pow(2, 31) - bindec(substr($binary, 1)))); 
	}
	
	protected function strstrb($h, $n)
	{
		$arr = explode($n,$h,2);
		return array_shift($arr);
	}
	
	protected function recursive_array_search($needle, $haystack)
	{
		foreach ($haystack as $key=>$value) {
			$current_key = $key;
			if ($needle === $value OR (is_array($value) && $this->recursive_array_search($needle, $value) !== FALSE)) {
				return $current_key;
			}
		}
		return FALSE;
	}
	
	protected function ndc_convert($ndc)
	{
		$pos1 = strpos($ndc, '-');
		$parts = explode("-", $ndc);
		if ($pos1 === 4) {
			$parts[0] = '0' . $parts[0];
		} else {
			$pos2 = strrpos($ndc, '-');
			if ($pos2 === 10) {
				$parts[2] = '0' . $parts[2];
			} else {
				$parts[1] = '0' . $parts[1];
			}
		}
		$new = $parts[0] . $parts[1] . $parts[2];
		return $new;
	}
	
	protected function setpatient($pid)
	{
		$row = Demographics::find($pid);
		$ptname = $row->firstname . ' ' . $row->lastname;
		$dob1 = $this->human_to_unix($row->DOB);
		$age1 = $this->timespan($dob1, time());
		$pos1 = strpos($age1, ',');
		$age2 = substr($age1, 0, $pos1);
		$pos2 = $pos1 + 1;
		$pos3 = strpos($age1, ',', $pos2);
		$age3 = substr($age1, 0, $pos3);
		if ($age2 == '1 Year' OR $age3 == '2 Years' OR $age3 == '3 Years') {
			$age = $age3 . ' Old';
		} else {
			$age = $age2 . ' Old';
		}
		$agediff = (time() - $dob1)/86400;
		$pos4 = strpos($agediff, '.');
		$agealldays = substr($agediff, 0, $pos4);
		if ($row->sex == 'm') {
			$gender = 'male';
		} 
		if ($row->sex == 'f') {
			$gender = 'female';
		}
		Session::put('pid', $pid);
		Session::put('gender', $gender);
		Session::put('age', $age);
		Session::put('agealldays', $agealldays);
		Session::put('ptname', $ptname);
		return true;
	}
	
	protected function current_age($pid)
	{
		$row = Demographics::find($pid);
		$dob1 = $this->human_to_unix($row->DOB);
		$age1 = $this->timespan($dob1, time());
		$pos1 = strpos($age1, ',');
		$age2 = substr($age1, 0, $pos1);
		$pos2 = $pos1 + 1;
		$pos3 = strpos($age1, ',', $pos2);
		$age3 = substr($age1, 0, $pos3);
		if ($age2 == '1 Year' OR $age3 == '2 Years' OR $age3 == '3 Years') {
			$age = $age3 . ' Old';
		} else {
			$age = $age2 . ' Old';
		}
		return $age;
	}
	
	/**
	 *	Signature to Image: A supplemental script for Signature Pad that
	 *	generates an image of the signatureâs JSON output server-side using PHP.
	 *	
	 *	@project	ca.thomasjbradley.applications.signaturetoimage
	 *	@author		Thomas J Bradley <hey@thomasjbradley.ca>
	 *	@link		http://thomasjbradley.ca/lab/signature-to-image
	 *	@link		http://github.com/thomasjbradley/signature-to-image
	 *	@copyright	Copyright MMXIâ, Thomas J Bradley
	 *	@license	New BSD License
	 *	@version	1.0.1
	 */

	/**
	 *	Accepts a signature created by signature pad in Json format
	 *	Converts it to an image resource
	 *	The image resource can then be changed into png, jpg whatever PHP GD supports
	 *
	 *	To create a nicely anti-aliased graphic the signature is drawn 12 times it's original size then shrunken
	 *
	 *	@param	string|array	$json
	 *	@param	array	$options	OPTIONAL; the options for image creation
	 *		imageSize => array(width, height)
	 *		bgColour => array(red, green, blue)
	 *		penWidth => int
	 *		penColour => array(red, green, blue)
	 *
	 *	@return	object
	 */
 
	protected function sigJsonToImage($json, $options = array())
	{
		$defaultOptions = array(
			'imageSize' => array(198, 55)
			,'bgColour' => array(0xff, 0xff, 0xff)
			,'penWidth' => 2
			,'penColour' => array(0x14, 0x53, 0x94)
			,'drawMultiplier'=> 12
		);
		$options = array_merge($defaultOptions, $options);
		$img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][1] * $options['drawMultiplier']);
		$bg = imagecolorallocate($img, $options['bgColour'][0], $options['bgColour'][1], $options['bgColour'][2]);
		$pen = imagecolorallocate($img, $options['penColour'][0], $options['penColour'][1], $options['penColour'][2]);
		imagefill($img, 0, 0, $bg);
		if(is_string($json)) {
			$json = json_decode(stripslashes($json));
		}
		foreach($json as $v) {
			$this->drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'], $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen, $options['penWidth'] * ($options['drawMultiplier'] / 2));
		}
		$imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);
		imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0], $options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][0] * $options['drawMultiplier']);
		imagedestroy($img);
		return $imgDest;
	}

	/**
	 *	Draws a thick line
	 *	Changing the thickness of a line using imagesetthickness doesn't produce as nice of result
	 *
	 *	@param	object	$img
	 *	@param	int		$startX
	 *	@param	int		$startY
	 *	@param	int		$endX
	 *	@param	int		$endY
	 *	@param	object	$colour
	 *	@param	int		$thickness
	 *
	 *	@return	void
	 */
	protected function drawThickLine($img, $startX, $startY, $endX, $endY, $colour, $thickness) 
	{
		$angle = (atan2(($startY - $endY), ($endX - $startX))); 
		$dist_x = $thickness * (sin($angle));
		$dist_y = $thickness * (cos($angle));
		$p1x = ceil(($startX + $dist_x));
		$p1y = ceil(($startY + $dist_y));
		$p2x = ceil(($endX + $dist_x));
		$p2y = ceil(($endY + $dist_y));
		$p3x = ceil(($endX - $dist_x));
		$p3y = ceil(($endY - $dist_y));
		$p4x = ceil(($startX - $dist_x));
		$p4y = ceil(($startY - $dist_y));
		$array = array(0=>$p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);
		imagefilledpolygon($img, $array, (count($array)/2), $colour);
	}
	
	protected function string_format($str, $len)
	{
		if (strlen((string)$str) < $len) {
			$str1 = str_pad((string)$str, $len);
		} else {
			$str1 = substr((string)$str, 0, $len);
		}
		$str1 = strtoupper((string)$str1);
		return $str1;
	}
	
	protected function billing_save_common($insurance_id_1, $insurance_id_2, $eid)
	{
		DB::table('billing')->where('eid', '=', $eid)->delete();
		$this->audit('Delete');
		$pid = Session::get('pid');
		$practiceInfo = Practiceinfo::find(Session::get('practice_id'));
		$encounterInfo = Encounters::find($eid);
		$bill_complex = $encounterInfo->bill_complex;
		$row = Demographics::find($pid);
		if ($insurance_id_1 == '0' || $insurance_id_1 == '') {
			$data0 = array(
				'eid' => $eid,
				'pid' => $pid,
				'insurance_id_1' => $insurance_id_1,
				'insurance_id_2' => $insurance_id_2,
				'bill_complex' => $bill_complex
			);
			DB::table('billing')->insert($data0);
			$this->audit('Add');
			$data_encounter = array(
				'bill_submitted' => 'Done'
			);
			DB::table('encounters')->where('eid', '=', $eid)->update($data_encounter);
			$this->audit('Update');
			return 'Billing Saved!';
		 	exit ( 0 );
		}
		$data_encounter = array(
			'bill_submitted' => 'No'
		);
		DB::table('encounters')->where('eid', '=', $eid)->update($data_encounter);
		$this->audit('Update');
		$result1 = Insurance::find($insurance_id_1);
		$bill_Box11C = $result1->insurance_plan_name;
		$bill_Box11C = $this->string_format($bill_Box11C, 29);
		$bill_Box1A = $result1->insurance_id_num;
		$bill_Box1A = $this->string_format($bill_Box1A, 29);
		$bill_Box4 = $result1->insurance_insu_lastname . ', ' . $result1->insurance_insu_firstname;
		$bill_Box4 = $this->string_format($bill_Box4, 29);
		$result2 = Addressbook::find($result1->address_id);
		if ($result2->insurance_plan_type == 'Medicare') {
			$bill_Box1 = "X                                            ";
			$bill_Box1P = 'Medicare';
		}
		if ($result2->insurance_plan_type == 'Medicaid') {
			$bill_Box1 = "       X                                     ";
			$bill_Box1P = 'Medicaid';
		}
		if ($result2->insurance_plan_type == 'Tricare') {
			$bill_Box1 = "              X                              ";
			$bill_Box1P = 'Tricare';
		}
		if ($result2->insurance_plan_type == 'ChampVA') {
			$bill_Box1 = "                     X                       ";
			$bill_Box1P = 'ChampVA';
		}
		if ($result2->insurance_plan_type == 'Group Health Plan') {
			$bill_Box1 = "                            X                ";
			$bill_Box1P = 'Group Health Plan';
		}
		if ($result2->insurance_plan_type == 'FECA') {
			$bill_Box1 = "                                   X         ";
			$bill_Box1P = 'FECA';
		}
		if ($result2->insurance_plan_type == 'Other') {
			$bill_Box1 = "                                            X";
			$bill_Box1P = 'Other';
		}
		$bill_payor_id = $result2->insurance_plan_payor_id;
		$bill_payor_id = $this->string_format($bill_payor_id, 5);
		if ($result2->street_address2 == '') {
			$bill_ins_add1 = $result2->street_address1;
		} else {
			$bill_ins_add1 = $result2->street_address1 . ', ' . $result2->street_address2;
		}
		$bill_ins_add1 = $this->string_format($bill_ins_add1, 29);
		$bill_ins_add2 = $result2->city . ', ' . $result2->state . ' ' . $result2->zip;
		$bill_ins_add2 = $this->string_format($bill_ins_add2, 29);
		if ($result2->insurance_plan_assignment == 'Yes') {
			$bill_Box27 = "X     ";
			$bill_Box27P = "Yes";
		} else {
			$bill_Box27 = "     X";
			$bill_Box27P = "No";
		}
		if ($result1->insurance_relationship == 'Self') {
			$bill_Box6 = "X              ";
			$bill_Box6P = "SelfBox6";
		}
		if ($result1->insurance_relationship == 'Spouse') {
			$bill_Box6 = "     X         ";
			$bill_Box6P = "Spouse";
		}
		if ($result1->insurance_relationship == 'Child') {
			$bill_Box6 = "         X     ";
			$bill_Box6P = "Child";
		}
		if ($result1->insurance_relationship == 'Other') {
			$bill_Box6 = "              X";
			$bill_Box6P = "Other";
		}
		$bill_Box7A = $result1->insurance_insu_address;
		$bill_Box7A = $this->string_format($bill_Box7A, 29);
		$bill_Box7B = $result1->insurance_insu_city;
		$bill_Box7B = $this->string_format($bill_Box7B, 23);
		$bill_Box7C = $result1->insurance_insu_state;
		$bill_Box7C = $this->string_format($bill_Box7C, 4);
		$bill_Box7D = $result1->insurance_insu_zip;
		$bill_Box7D = $this->string_format($bill_Box7D, 12);
		$bill_Box11 = $result1->insurance_group;
		$bill_Box11 = $this->string_format($bill_Box11, 29);
		$bill_Box11A1 = $this->human_to_unix($result1->insurance_insu_dob);
		$bill_Box11A1 = date('m d Y', $bill_Box11A1);
		if ($result1->insurance_insu_gender == 'm') {
			$bill_Box11A2 = "X       ";
			$bill_Box11A2P = 'M';
		} else {
			$bill_Box11A2 = "       X";
			$bill_Box11A2P = 'F';
		}
		if ($insurance_id_2 == '' || $insurance_id_2 == '0') {
			$bill_Box9D = '';
			$bill_Box9 = '';
			$bill_Box9A = '';
			$bill_Box9B1 = '          ';
			$bill_Box9B2 = '       ';
			$bill_Box9B2P = '';
			$bill_Box9C = "";
			$bill_Box11D = '     X';
			$bill_Box11DP = 'No';
		} else {
			$result3 = Insurance::find($insurance_id_2);
			$bill_Box9D = $result3->insurance_plan_name;
			$bill_Box9 = $result3->insurance_insu_lastname . ', ' . $result3->insurance_insu_firstname;
			$bill_Box9A = $result3->insurance_group;
			$bill_Box9B1 = $this->human_to_unix($result3->insurance_insu_dob);
			$bill_Box9B1 = date('m d Y', $bill_Box9B1);
			if ($result3->insurance_insu_gender == 'm') {
				$bill_Box9B2 = "X      ";
				$bill_Box9B2P = 'M';
			} else {
				$bill_Box9B2 = "      X";
				$bill_Box9B2P = 'F';
			}
			$bill_Box11D = 'X     ';
			$bill_Box11DP = 'Yes';
			if ($row->employer != '') {
				$bill_Box9C = $row->employer;
			} else {
				$bill_Box9C = "";
			}
		}
		$bill_Box9D = $this->string_format($bill_Box9D, 28);
		$bill_Box9 = $this->string_format($bill_Box9, 28);
		$bill_Box9A = $this->string_format($bill_Box9A, 28);
		$bill_Box9C = $this->string_format($bill_Box9C, 28);
		$bill_Box2 = $row->lastname . ', ' . $row->firstname;
		$bill_Box2 = $this->string_format($bill_Box2, 28);
		$bill_Box3A = $this->human_to_unix($row->DOB);
		$bill_Box3A = date('m d Y', $bill_Box3A);
		if ($row->sex == 'm') {
			$bill_Box3B = "X     ";
			$bill_Box3BP = 'M';
		} else {
			$bill_Box3B = "     X";
			$bill_Box3BP = 'F';
		}
		if ($row->marital_status == 'Single') {
			$bill_Box8A = "X            ";
			$bill_Box8AP = 'SingleBox8';
		} else {
			if ($row->marital_status == 'Married') {
				$bill_Box8A = "      X      ";
				$bill_Box8AP = 'Married';
			} else {
				$bill_Box8A = "            X";
				$bill_Box8AP = 'Other';
			}
		}
		if ($row->employer != '') {
			$bill_Box8B = "X            ";
			$bill_Box8BP = "EmployedBox8";
			$bill_Box11B = $row->employer;
		} else {
			$bill_Box8B = "             ";
			$bill_Box8BP = "";
			$bill_Box11B = "";
		}
		$bill_Box11B = $this->string_format($bill_Box11B, 29);
		$bill_Box5A = $row->address;
		$bill_Box5A = $this->string_format($bill_Box5A, 28);
		$bill_Box5B = $row->city;
		$bill_Box5B = $this->string_format($bill_Box5B, 24);
		$bill_Box5C = $row->state;
		$bill_Box5C = $this->string_format($bill_Box5C, 3);
		$bill_Box5D = $row->zip;
		$bill_Box5D = $this->string_format($bill_Box5D, 12);
		$bill_Box5E = $row->phone_home;
		$bill_Box5E = $this->string_format($bill_Box5E, 14);
		$bill_Box10 = $encounterInfo->encounter_condition;
		$bill_Box10 = $this->string_format($bill_Box10, 19);
		$work = $encounterInfo->encounter_condition_work;
		if ($work == 'Yes') {
			$bill_Box10A = "X      ";
			$bill_Box10AP = 'Yes';
		} else {
			$bill_Box10A = "      X";
			$bill_Box10AP = 'No';
		}
		$auto = $encounterInfo->encounter_condition_auto;
		if ($auto == 'Yes') {
			$bill_Box10B1 = "X      ";
			$bill_Box10B1P = 'Yes';
			$bill_Box10B2 = $encounterInfo->encounter_condition_auto_state;	
		} else {
			$bill_Box10B1 = "      X";
			$bill_Box10B1P = 'No';
			$bill_Box10B2 = "";
		}
		$bill_Box10B2 = $this->string_format($bill_Box10B2, 3);
		$other = $encounterInfo->encounter_condition_other;
		if ($other == 'Yes') {
			$bill_Box10C = "X      ";
			$bill_Box10CP = "Yes";
		} else {
			$bill_Box10C = "      X";
			$bill_Box10CP = 'No';
		}
		$provider = $encounterInfo->encounter_provider;
		$user_row = User::where('displayname', '=', $provider)->where('group_id', '=', '2')->first();
		$result4 = Providers::find($user_row->id);
		$npi = $result4->npi;
		if ($encounterInfo->referring_provider != 'Primary Care Provider' || $encounterInfo->referring_provider != '') {
			$bill_Box17 = $this->string_format($encounterInfo->referring_provider, 26);
			$bill_Box17A = $this->string_format($encounterInfo->referring_provider_npi, 17);
		} else {
			if ($encounterInfo->referring_provider != 'Primary Care Provider') {
				$bill_Box17 = $this->string_format('', 26);
				$bill_Box17A = $this->string_format('', 17);
			} else {
				$bill_Box17 = $this->string_format($provider, 26);
				$bill_Box17A = $this->string_format($npi, 17);
			}
		}
		// Modify in the future so that it allows 0 for icd-10
		$bill_Box21A = '9';
		if ($result2->insurance_box_31 == 'n') {
			$bill_Box31 = $this->string_format($provider, 21);
		} else {
			$provider2 = User::find($encounterInfo->user_id);
			$provider2a = $provider2->lastname . ", " . $provider2->firstname;
			$bill_Box31 = $this->string_format($provider2a, 21);
		}
		$bill_Box33B = $this->string_format($provider, 29);
		$pos = $encounterInfo->encounter_location;
		$bill_Box25 = $practiceInfo->tax_id;
		$bill_Box25 = $this->string_format($bill_Box25, 15);
		$bill_Box26 = $this->string_format($pid, 14);
		$bill_Box32A = $practiceInfo->practice_name;
		$bill_Box32A = $this->string_format($bill_Box32A, 26);
		$bill_Box32B = $practiceInfo->street_address1;
		if ($practiceInfo->street_address2 != '') {
			$bill_Box32B .= ', ' . $practiceInfo->street_address2;
		}
		$bill_Box32B = $this->string_format($bill_Box32B, 26);
		$bill_Box32C = $practiceInfo->city . ', ' . $practiceInfo->state . ' ' . $practiceInfo->zip;
		$bill_Box32C = $this->string_format($bill_Box32C, 26);
		if ($result2->insurance_box_32a == 'n') {
			$bill_Box32D = $practiceInfo->npi;
		} else {
			$provider3 = Providers::find($encounterInfo->user_id);
			$bill_Box32D = $provider3->npi;
		}
		$bill_Box32D = $this->string_format($bill_Box32D, 10);
		$bill_Box33A = $practiceInfo->phone;
		$bill_Box33A = $this->string_format($bill_Box33A, 14);
		$bill_Box33C = $practiceInfo->billing_street_address1;
		if ($practiceInfo->billing_street_address2 != '') {
			$bill_Box33C .= ', ' . $practiceInfo->billing_street_address2;
		}
		$bill_Box33C = $this->string_format($bill_Box33C, 29);
		$bill_Box33D = $practiceInfo->billing_city . ', ' . $practiceInfo->billing_state . ' ' . $practiceInfo->billing_zip;
		$bill_Box33D = $this->string_format($bill_Box33D, 29);
		$result5 = DB::table('billing_core')
			->where('eid', '=', $eid)
			->where('cpt', 'NOT LIKE', "sp%")
			->orderBy('cpt_charge', 'desc')
			->get();
		$num_rows5 = count($result5);
		if ($num_rows5 > 0) {
			$result6 = Assessment::find($eid);
			$bill_Box21_1 = $this->string_format($result6->assessment_icd1, 8);
			$bill_Box21_2 = $this->string_format($result6->assessment_icd2, 8);
			$bill_Box21_3 = $this->string_format($result6->assessment_icd3, 8);
			$bill_Box21_4 = $this->string_format($result6->assessment_icd4, 8);
			$bill_Box21_5 = $this->string_format($result6->assessment_icd5, 8);
			$bill_Box21_6 = $this->string_format($result6->assessment_icd6, 8);
			$bill_Box21_7 = $this->string_format($result6->assessment_icd7, 8);
			$bill_Box21_8 = $this->string_format($result6->assessment_icd8, 8);
			$bill_Box21_9 = $this->string_format($result6->assessment_icd9, 8);
			$bill_Box21_10 = $this->string_format($result6->assessment_icd10, 8);
			$bill_Box21_11 = $this->string_format($result6->assessment_icd11, 8);
			$bill_Box21_12 = $this->string_format($result6->assessment_icd12, 8);
			$i = 0;
			foreach ($result5 as $key5 => $value5) {
				$cpt_charge5[$key5]  = $value5->cpt_charge;
			}
			array_multisort($cpt_charge5, SORT_DESC, $result5);
			while ($i < $num_rows5 ) {
				$cpt_final[$i] = (array) $result5[$i];
				$cpt_final[$i]['dos_f'] = str_replace('/', '', $cpt_final[$i]['dos_f']);
				$cpt_final[$i]['dos_f'] = $this->string_format($cpt_final[$i]['dos_f'], 8);
				$cpt_final[$i]['dos_t'] = str_replace('/', '', $cpt_final[$i]['dos_t']);
				$cpt_final[$i]['dos_t'] = $this->string_format($cpt_final[$i]['dos_t'], 8);
				$cpt_final[$i]['pos'] = $this->string_format($pos, 5);
				$cpt_final[$i]['cpt'] = $this->string_format($cpt_final[$i]['cpt'], 6);
				$cpt_final[$i]['modifier'] = $this->string_format($cpt_final[$i]['modifier'], 11);
				$cpt_final[$i]['unit1'] = $cpt_final[$i]['unit'];
				$cpt_final[$i]['unit'] = $this->string_format($cpt_final[$i]['unit'] ,5);
				$cpt_final[$i]['cpt_charge'] = number_format($cpt_final[$i]['cpt_charge'], 2, ' ', '');
				$cpt_final[$i]['cpt_charge1'] = $cpt_final[$i]['cpt_charge'];
				$cpt_final[$i]['cpt_charge'] = $this->string_format($cpt_final[$i]['cpt_charge'], 8);
				$cpt_final[$i]['npi'] = $this->string_format($npi, 11);
				$cpt_final[$i]['icd_pointer'] =  $this->string_format($cpt_final[$i]['icd_pointer'], 4);
				$i++;
			}
			if ($num_rows5 < 6) {
				$array['dos_f'] = $this->string_format('', 8);
				$array['dos_t'] = $this->string_format('', 8);
				$array['pos'] = $this->string_format('', 5);
				$array['cpt'] = $this->string_format('', 6);
				$array['modifier'] = $this->string_format('', 11);
				$array['unit1'] = '0';
				$array['unit'] = $this->string_format('', 5);
				$array['cpt_charge1'] = '0';
				$array['cpt_charge'] = $this->string_format('', 8);
				$array['npi'] = $this->string_format('', 11);
				$array['icd_pointer'] =  $this->string_format('', 4);
				$cpt_final = array_pad($cpt_final, 6, $array);
			}
			$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
			$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
			$bill_Box28 = $this->string_format($bill_Box28, 9);
			$bill_Box29 = $this->string_format('0 00', 8);
			$bill_Box30 = $this->string_format($bill_Box28, 8);
			$data1 = array(
				'eid' 						=> $eid,
				'pid' 						=> $pid,
				'insurance_id_1' 			=> $insurance_id_1,
				'insurance_id_2' 			=> $insurance_id_2,
				'bill_complex'				=> $bill_complex,
				'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
				'bill_payor_id'				=> $bill_payor_id,
				'bill_ins_add1'				=> $bill_ins_add1,
				'bill_ins_add2'				=> $bill_ins_add2,
				'bill_Box1'					=> $bill_Box1,
				'bill_Box1P'				=> $bill_Box1P,
				'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
				'bill_Box2' 				=> $bill_Box2, 		//Patient Name
				'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
				'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
				'bill_Box3BP' 				=> $bill_Box3BP,
				'bill_Box4'					=> $bill_Box4, 		//Insured Name
				'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
				'bill_Box6'					=> $bill_Box6, 		//Patient Relationship to Insured
				'bill_Box6P'				=> $bill_Box6P,
				'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
				'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
				'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
				'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
				'bill_Box8AP'				=> $bill_Box8AP,
				'bill_Box7B'				=> $bill_Box7B, 	//Insured City
				'bill_Box7C'				=> $bill_Box7C, 	//Insured State
				'bill_Box5D'				=> $bill_Box5D,		//Patient Zip
				'bill_Box5E'				=> $bill_Box5E,
				'bill_Box8B'				=> $bill_Box8B,		//Patient Employment
				'bill_Box8BP'				=> $bill_Box8BP,
				'bill_Box7D'				=> $bill_Box7D,		//Insured Zip
				'bill_Box9'					=> $bill_Box9, 		//Other Insured Name
				'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
				'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
				'bill_Box10'				=> $bill_Box10, 
				'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
				'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
				'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
				'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
				'bill_Box11A2P'				=> $bill_Box11A2P,
				'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
				'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
				'bill_Box9B2P'				=> $bill_Box9B2P,
				'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
				'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
				'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
				'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
				'bill_Box9C'				=> $bill_Box9C,		//Other Insured Employer
				'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
				'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
				'bill_Box9D'				=> $bill_Box9D,		//Other Insurance Plan Name
				'bill_Box11D'				=> $bill_Box11D,
				'bill_Box11DP'				=> $bill_Box11DP,	//Other Insurance Plan Exist
				'bill_Box17' 				=> $bill_Box17,		//Provider Use for Box 31 and 33B too
				'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
				'bill_Box21A'				=> $bill_Box21A,	//ICD9 or 10
				'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
				'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
				'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
				'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
				'bill_Box21_5' 				=> $bill_Box21_5,	//ICD5
				'bill_Box21_6' 				=> $bill_Box21_6,	//ICD6
				'bill_Box21_7' 				=> $bill_Box21_7,	//ICD7
				'bill_Box21_8' 				=> $bill_Box21_8,	//ICD8
				'bill_Box21_9' 				=> $bill_Box21_9,	//ICD9
				'bill_Box21_10' 			=> $bill_Box21_10,	//ICD10
				'bill_Box21_11' 			=> $bill_Box21_11,	//ICD11
				'bill_Box21_12' 			=> $bill_Box21_12,	//ICD12
				'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
				'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
				'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
				'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
				'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
				'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
				'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
				'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
				'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
				'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
				'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
				'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
				'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
				'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
				'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
				'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
				'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
				'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
				'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
				'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
				'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
				'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
				'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
				'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
				'bill_Modifier1'			=> $cpt_final[0]['modifier'],
				'bill_Modifier2'			=> $cpt_final[1]['modifier'],
				'bill_Modifier3'			=> $cpt_final[2]['modifier'],
				'bill_Modifier4'			=> $cpt_final[3]['modifier'],
				'bill_Modifier5'			=> $cpt_final[4]['modifier'],
				'bill_Modifier6'			=> $cpt_final[5]['modifier'],
				'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
				'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
				'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
				'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
				'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
				'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
				'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
				'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
				'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
				'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
				'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
				'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
				'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
				'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
				'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
				'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
				'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
				'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
				'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
				'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
				'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
				'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
				'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
				'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
				'bill_Box25' 				=> $bill_Box25,		//Clinic Tax ID
				'bill_Box26' 				=> $bill_Box26,		//pid
				'bill_Box27'				=> $bill_Box27,		//Accept Assignment
				'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
				'bill_Box28' 				=> $bill_Box28,		//Total Charges
				'bill_Box29'				=> $bill_Box29,
				'bill_Box30'				=> $bill_Box30,
				'bill_Box31'				=> $bill_Box31,
				'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
				'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
				'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
				'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
				'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
				'bill_Box33B'				=> $bill_Box33B,
				'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
				'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
				'bill_Box33E'				=> $bill_Box32D
			);
			DB::table('billing')->insert($data1);
			$this->audit('Add');
			unset($cpt_final[0]);
			unset($cpt_final[1]);
			unset($cpt_final[2]);
			unset($cpt_final[3]);
			unset($cpt_final[4]);
			unset($cpt_final[5]);
			if ($num_rows5 > 6 && $num_rows5 < 11) {
				$k = 6;
				foreach ($cpt_final as $k=>$v) {
					$l = $k - 6;
					$cpt_final[$l] = $cpt_final[$k];
					unset($cpt_final[$k]);
					$k++;
				}
				$num_rows6 = count($cpt_final);
				if ($num_rows6 < 6) {
					$array1['dos_f'] = $this->string_format('', 8);
					$array1['dos_t'] = $this->string_format('', 8);
					$array1['pos'] = $this->string_format('', 5);
					$array1['cpt'] = $this->string_format('', 6);
					$array1['modifier'] = $this->string_format('', 11);
					$array1['unit1'] = '0';
					$array1['unit'] = $this->string_format('', 5);
					$array1['cpt_charge1'] = '0';
					$array1['cpt_charge'] = $this->string_format('', 8);
					$array1['npi'] = $this->string_format('', 11);
					$array1['icd_pointer'] =  $this->string_format('', 4);
					$cpt_final = array_pad($cpt_final, 6, $array1);
				}
				$bill_Box28 = $cpt_final[0]['cpt_charge1'] * $cpt_final[0]['unit1'] + $cpt_final[1]['cpt_charge1'] * $cpt_final[1]['unit1'] + $cpt_final[2]['cpt_charge1'] * $cpt_final[2]['unit1'] + $cpt_final[3]['cpt_charge1'] * $cpt_final[3]['unit1'] + $cpt_final[4]['cpt_charge1'] * $cpt_final[4]['unit1'] + $cpt_final[5]['cpt_charge1'] * $cpt_final[5]['unit1'];
				$bill_Box28 = number_format($bill_Box28, 2, ' ', '');
				$bill_Box28 = $this->string_format($bill_Box28, 9);
				$bill_Box29 = $this->string_format('0 00', 8);
				$bill_Box30 = $this->string_format($bill_Box28, 8);
				$data2 = array(
					'eid' 						=> $eid,
					'pid' 						=> $pid,
					'insurance_id_1' 			=> $insurance_id_1,
					'insurance_id_2' 			=> $insurance_id_2,
					'bill_complex'				=> $bill_complex,
					'bill_Box11C' 				=> $bill_Box11C,	//Insurance Plan Name
					'bill_payor_id'				=> $bill_payor_id,
					'bill_ins_add1'				=> $bill_ins_add1,
					'bill_ins_add2'				=> $bill_ins_add2,
					'bill_Box1'					=> $bill_Box1,
					'bill_Box1P'				=> $bill_Box1P,
					'bill_Box1A' 				=> $bill_Box1A, 	//Insured ID Number
					'bill_Box2' 				=> $bill_Box2, 		//Patient Name
					'bill_Box3A' 				=> $bill_Box3A, 	//Patient Date of Birth
					'bill_Box3B' 				=> $bill_Box3B, 	//Patient Sex
					'bill_Box3BP' 				=> $bill_Box3BP,
					'bill_Box4'					=> $bill_Box4, 		//Insured Name
					'bill_Box5A' 				=> $bill_Box5A, 	//Patient Address
					'bill_Box6'					=> $bill_Box6, 		//Patient Relationship to Insured
					'bill_Box6P'				=> $bill_Box6P,
					'bill_Box7A'				=> $bill_Box7A, 	//Insured Address
					'bill_Box5B' 				=> $bill_Box5B, 	//Patient City
					'bill_Box5C' 				=> $bill_Box5C, 	//Patient State
					'bill_Box8A'				=> $bill_Box8A, 	//Patient Marital Status
					'bill_Box8AP'				=> $bill_Box8AP,
					'bill_Box7B'				=> $bill_Box7B, 	//Insured City
					'bill_Box7C'				=> $bill_Box7C, 	//Insured State
					'bill_Box5D'				=> $bill_Box5D,		//Patient Zip
					'bill_Box5E'				=> $bill_Box5E,
					'bill_Box8B'				=> $bill_Box8B,		//Patient Employment
					'bill_Box8BP'				=> $bill_Box8BP,
					'bill_Box7D'				=> $bill_Box7D,		//Insured Zip
					'bill_Box9'					=> $bill_Box9, 		//Other Insured Name
					'bill_Box11'				=> $bill_Box11, 	//Insured Group Number
					'bill_Box9A'				=> $bill_Box9A, 	//Other Insured Group Number
					'bill_Box10'				=> $bill_Box10, 
					'bill_Box10A'				=> $bill_Box10A,	//Condition Employment
					'bill_Box10AP'				=> $bill_Box10AP,	//Condition Employment
					'bill_Box11A1'				=> $bill_Box11A1,	//Insured Date of Birth
					'bill_Box11D'				=> $bill_Box11D,
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box11A2'				=> $bill_Box11A2,	//Insured Sex
					'bill_Box11A2P'				=> $bill_Box11A2P,
					'bill_Box9B1'				=> $bill_Box9B1,	//Other Insured Date of Birth
					'bill_Box9B2'				=> $bill_Box9B2,	//Other Insured Sex
					'bill_Box9B2P'				=> $bill_Box9B2P,
					'bill_Box10B1'				=> $bill_Box10B1,	//Condition Auto Accident
					'bill_Box10B1P'				=> $bill_Box10B1P,	//Condition Auto Accident
					'bill_Box10B2'				=> $bill_Box10B2,	//Condition Auto Accident State
					'bill_Box11B'				=> $bill_Box11B,	//Insured Employer
					'bill_Box9C'				=> $bill_Box9C,		//Other Insured Employer
					'bill_Box10C'				=> $bill_Box10C,	//Condition Other Accident
					'bill_Box10CP'				=> $bill_Box10CP,	//Condition Other Accident
					'bill_Box9D'				=> $bill_Box9D,		//Other Insurance Plan Name
					'bill_Box11D'				=> $bill_Box11D,	//Other Insurance Plan Exist
					'bill_Box11DP'				=> $bill_Box11DP,
					'bill_Box17' 				=> $bill_Box17,		//Provider Use for Box 31 and 33B too
					'bill_Box17A' 				=> $bill_Box17A,	//Provider NPI
					'bill_Box21A'				=> $bill_Box21A,	//ICD9 or 10
					'bill_Box21_1' 				=> $bill_Box21_1,	//ICD1
					'bill_Box21_2' 				=> $bill_Box21_2,	//ICD2
					'bill_Box21_3' 				=> $bill_Box21_3,	//ICD3
					'bill_Box21_4' 				=> $bill_Box21_4,	//ICD4
					'bill_Box21_5' 				=> $bill_Box21_5,	//ICD5
					'bill_Box21_6' 				=> $bill_Box21_6,	//ICD6
					'bill_Box21_7' 				=> $bill_Box21_7,	//ICD7
					'bill_Box21_8' 				=> $bill_Box21_8,	//ICD8
					'bill_Box21_9' 				=> $bill_Box21_9,	//ICD9
					'bill_Box21_10' 			=> $bill_Box21_10,	//ICD10
					'bill_Box21_11' 			=> $bill_Box21_11,	//ICD11
					'bill_Box21_12' 			=> $bill_Box21_12,	//ICD12
					'bill_DOS1F' 				=> $cpt_final[0]['dos_f'],
					'bill_DOS1T' 				=> $cpt_final[0]['dos_t'],
					'bill_DOS2F' 				=> $cpt_final[1]['dos_f'],
					'bill_DOS2T' 				=> $cpt_final[1]['dos_t'],
					'bill_DOS3F' 				=> $cpt_final[2]['dos_f'],
					'bill_DOS3T' 				=> $cpt_final[2]['dos_t'],
					'bill_DOS4F' 				=> $cpt_final[3]['dos_f'],
					'bill_DOS4T' 				=> $cpt_final[3]['dos_t'],
					'bill_DOS5F' 				=> $cpt_final[4]['dos_f'],
					'bill_DOS5T'				=> $cpt_final[4]['dos_t'],
					'bill_DOS6F' 				=> $cpt_final[5]['dos_f'],
					'bill_DOS6T' 				=> $cpt_final[5]['dos_t'],
					'bill_Box24B1' 				=> $cpt_final[0]['pos'],	//Place of Service 1
					'bill_Box24B2' 				=> $cpt_final[1]['pos'],	//Place of Service 2
					'bill_Box24B3'				=> $cpt_final[2]['pos'],	//Place of Service 3
					'bill_Box24B4' 				=> $cpt_final[3]['pos'],	//Place of Service 4
					'bill_Box24B5' 				=> $cpt_final[4]['pos'],	//Place of Service 5
					'bill_Box24B6' 				=> $cpt_final[5]['pos'],	//Place of Service 6
					'bill_Box24D1' 				=> $cpt_final[0]['cpt'],	//CPT1
					'bill_Box24D2'				=> $cpt_final[1]['cpt'],	//CPT2
					'bill_Box24D3' 				=> $cpt_final[2]['cpt'],	//CPT3
					'bill_Box24D4' 				=> $cpt_final[3]['cpt'],	//CPT4
					'bill_Box24D5' 				=> $cpt_final[4]['cpt'],	//CPT5
					'bill_Box24D6' 				=> $cpt_final[5]['cpt'],	//CPT6
					'bill_Modifier1'			=> $cpt_final[0]['modifier'],
					'bill_Modifier2'			=> $cpt_final[1]['modifier'],
					'bill_Modifier3'			=> $cpt_final[2]['modifier'],
					'bill_Modifier4'			=> $cpt_final[3]['modifier'],
					'bill_Modifier5'			=> $cpt_final[4]['modifier'],
					'bill_Modifier6'			=> $cpt_final[5]['modifier'],
					'bill_Box24E1'				=> $cpt_final[0]['icd_pointer'],	//Diagnosis Pointer 1
					'bill_Box24E2'				=> $cpt_final[1]['icd_pointer'],	//Diagnosis Pointer 2
					'bill_Box24E3'				=> $cpt_final[2]['icd_pointer'],	//Diagnosis Pointer 3
					'bill_Box24E4'				=> $cpt_final[3]['icd_pointer'],	//Diagnosis Pointer 4
					'bill_Box24E5'				=> $cpt_final[4]['icd_pointer'],	//Diagnosis Pointer 5
					'bill_Box24E6'				=> $cpt_final[5]['icd_pointer'],	//Diagnosis Pointer 6
					'bill_Box24F1' 				=> number_format($cpt_final[0]['cpt_charge'] * $cpt_final[0]['unit'], 2, ' ', ''),	//Charges 1
					'bill_Box24F2'				=> number_format($cpt_final[1]['cpt_charge'] * $cpt_final[1]['unit'], 2, ' ', ''),	//Charges 2
					'bill_Box24F3' 				=> number_format($cpt_final[2]['cpt_charge'] * $cpt_final[2]['unit'], 2, ' ', ''),	//Charges 3
					'bill_Box24F4' 				=> number_format($cpt_final[3]['cpt_charge'] * $cpt_final[3]['unit'], 2, ' ', ''),	//Charges 4
					'bill_Box24F5' 				=> number_format($cpt_final[4]['cpt_charge'] * $cpt_final[4]['unit'], 2, ' ', ''),	//Charges 5
					'bill_Box24F6' 				=> number_format($cpt_final[5]['cpt_charge'] * $cpt_final[5]['unit'], 2, ' ', ''),	//Charges 6
					'bill_Box24G1'				=> $cpt_final[0]['unit'],	//Units 1
					'bill_Box24G2'				=> $cpt_final[1]['unit'],	//Units 2
					'bill_Box24G3'				=> $cpt_final[2]['unit'],	//Units 3
					'bill_Box24G4'				=> $cpt_final[3]['unit'],	//Units 4
					'bill_Box24G5'				=> $cpt_final[4]['unit'],	//Units 5
					'bill_Box24G6'				=> $cpt_final[5]['unit'],	//Units 6
					'bill_Box24J1' 				=> $cpt_final[0]['npi'],	//NPI 1
					'bill_Box24J2' 				=> $cpt_final[1]['npi'],	//NPI 2
					'bill_Box24J3' 				=> $cpt_final[2]['npi'],	//NPI 3
					'bill_Box24J4' 				=> $cpt_final[3]['npi'],	//NPI 4
					'bill_Box24J5' 				=> $cpt_final[4]['npi'],	//NPI 5
					'bill_Box24J6' 				=> $cpt_final[5]['npi'],	//NPI 6
					'bill_Box25' 				=> $bill_Box25,		//Clinic Tax ID
					'bill_Box26' 				=> $bill_Box26,		//pid
					'bill_Box27'				=> $bill_Box27,		//Accept Assignment
					'bill_Box27P'				=> $bill_Box27P,	//Accept Assignment
					'bill_Box28' 				=> $bill_Box28,		//Total Charges
					'bill_Box29'				=> $bill_Box29,
					'bill_Box30'				=> $bill_Box30,
					'bill_Box31'				=> $bill_Box31,
					'bill_Box32A' 				=> $bill_Box32A,	//Clinic Name
					'bill_Box32B' 				=> $bill_Box32B,	//Clinic Address 1
					'bill_Box32C'				=> $bill_Box32C,	//Clinic Address 2
					'bill_Box32D' 				=> $bill_Box32D,	//Clinic NPI use for 33E too
					'bill_Box33A' 				=> $bill_Box33A,	//Clinic Phone
					'bill_Box33B'				=> $bill_Box33B,
					'bill_Box33C'				=> $bill_Box33C,	//Billing Address 1
					'bill_Box33D'				=> $bill_Box33D,	//Billing Address 2
					'bill_Box33E'				=> $bill_Box32D
				);
				DB::table('billing')->insert($data2);
				$this->audit('Add');
				unset($cpt_final[0]);
				unset($cpt_final[1]);
				unset($cpt_final[2]);
				unset($cpt_final[3]);
				unset($cpt_final[4]);
				unset($cpt_final[5]);
			}	
		} else {
			return "No CPT charges filed. Billing not saved.";
			exit (0);
		}
		return 'Billing saved and waiting to be submitted!';
	}
	
	protected function compile_procedure_billing($cpt, $eid, $pid, $dos2, $icd_pointer, $practice_id)
	{
		$query = Billing_core::where('cpt', '=', $cpt)->where('eid', '=', $eid)->get();
		if (!$query) {
			$result = Cpt_relate::where('cpt', '=', $cpt)->where('practice_id', '=', $practice_id)->get();
			if ($result) {
				$cpt_charge = $result->cpt_charge;
			} else {
				$cpt_charge = '0';
			}
			$data = array(
				'cpt' => $cpt,
				'cpt_charge' => $result->cpt_charge,
				'eid' => $eid,
				'pid' => $pid,
				'dos_f' => $dos2,
				'dos_t' => $dos2,
				'payment' => '0',
				'icd_pointer' => $icd_pointer,
				'unit' => '1',
				'billing_group' => '1',
				'modifier' => '',
				'practice_id' => $practice_id
			);
			DB::table('billing_core')->insert($cpt3_data);
			$this->audit('Add');
		}
	}
	
	protected function add_mtm_alert($pid, $type)
	{
		$practice_id = Session::get('practice_id');
		if ($type == 'issues') {
			$query = Issues::where('pid', '=', $pid)->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->get();
		}
		if ($type == 'medications') {
			$query = Rx_list::where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		}
		if(count($query) > 1) {
			$query1 = Alerts::where('pid', '=', $pid)
				->where('alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alert_reason_not_complete', '=', '')
				->where('alert', '=', 'Medication Therapy Management')
				->where('practice_id', '=', $practice_id)
				->get();
			if (!$query1) {
				$data = array(
					'alert' => 'Medication Therapy Management',
					'alert_description' => 'Medication therapy management is needed due to more than 2 active medications or issues.',
					'alert_date_active' => date('Y-m-d H:i:s', time()),
					'alert_date_complete' => '',
					'alert_reason_not_complete' => '',
					'pid' => $pid,
					'practice_id' => $practice_id
				);
				DB::table('alerts')->insert($data);
				$this->audit('Add');
			}
		}
	}
	
	protected function check_rcopia_delete($table, $id)
	{
		if ($table == 'rx_list') {
			$key = 'rxl_id';
		}
		if ($table == 'allergies') {
			$key = 'allergies_id';
		}
		if ($table == 'issues') {
			$key = 'issue_id';
		}
		$result = DB::table($table)->where($key, '=', $id)->first();
		if ($result->rcopia_sync == 'nd') {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	protected function check_practice_id($pid, $practice_id)
	{
		$query = DB::table('demographics_relate')->where('pid', '=', $pid)->where('practice_id', '=', $practice_id)->first();
		if ($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	protected function convert_number($number) 
	{ 
		if (($number < 0) || ($number > 999999999)) { 
			$res = "not a valid number";
			return $res;
		} 
		$Gn = floor($number / 1000000);  /* Millions (giga) */ 
		$number -= $Gn * 1000000; 
		$kn = floor($number / 1000);     /* Thousands (kilo) */ 
		$number -= $kn * 1000; 
		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 
		$number -= $Hn * 100; 
		$Dn = floor($number / 10);       /* Tens (deca) */ 
		$n = $number % 10;               /* Ones */ 
		$res = ""; 
		if ($Gn) { 
			$res .= $this->convert_number($Gn) . " Million"; 
		} 
		if ($kn) { 
			$res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand"; 
		} 
		if ($Hn) { 
			$res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred"; 
		} 
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
			"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
			"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", 
			"Nineteen"); 
		$tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", 
			"Seventy", "Eighty", "Ninety"); 

		if ($Dn || $n) { 
			if (!empty($res)) { 
				$res .= " and "; 
			} 
			if ($Dn < 2) { 
				$res .= $ones[$Dn * 10 + $n]; 
			} else { 
				$res .= $tens[$Dn]; 
				if ($n) { 
				    $res .= "-" . $ones[$n]; 
				} 
			} 
		} 
		if (empty($res)) { 
			$res = "zero"; 
		} 
		return $res; 
	}
	
	protected function practice_logo($practice_id)
	{
		$practice = Practiceinfo::find($practice_id);
		if ($practice->practice_logo != '') {
			$link = HTML::image($practice->practice_logo, 'Practice Logo', array('border' => '0'));
			$logo = str_replace('https', 'http', $link);
		} else {
			$logo = '<br><br><br><br><br>';
		}
		return $logo;
	}
	
	protected function signature($id)
	{
		$signature = Providers::find($id);
		if ($signature) {
			$link = HTML::image($signature->signature, 'Signature', array('border' => '0'));
			$link = str_replace('https', 'http', $link);
			$sig = $link . '<br>' . Session::get('displayname');
		} else {
			$sig = '<br><br><br><br><br><br><br>' . Session::get('displayname');
		}
		return $sig;
	}
	
	protected function page_medication($rxl_id)
	{
		$pid = Session::get('pid');
		$data['rx'] = DB::table('rx_list')->where('rxl_id', '=', $rxl_id)->first();
		$quantity = $data['rx']->rxl_quantity;
		$refill = $data['rx']->rxl_refill;
		$data['quantity_words'] = $this->convert_number($quantity);
		$data['refill_words'] = $this->convert_number($refill);
		$data['quantity_words'] = strtoupper($data['quantity_words']);
		$data['refill_words'] = strtoupper($data['refill_words']);
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip . '<br />';
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax . '<br />';
		$data['patientInfo'] = DB::table('demographics')->where('pid', '=', $pid)->first();
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$rxicon = HTML::image('images/rxicon.png', 'Practice Logo', array('border' => '0', 'height' => '30', 'width' => '30'));
		$data['rxicon'] = str_replace('https', 'http', $rxicon);
		$data['dob'] = date('m/d/Y', $this->human_to_unix($data['patientInfo']->DOB));
		$data['rx_date'] = date('m/d/Y', $this->human_to_unix($data['rx']->rxl_date_prescribed));
		$query1 = DB::table('insurance')->where('pid', '=', $pid)->where('insurance_plan_active', '=', 'Yes')->get();
		$data['insuranceInfo'] = '';
		if ($query1) {
			foreach ($query1 as $row) {
				$data['insuranceInfo'] .= $row->insurance_plan_name . '; ID: ' . $row->insurance_id_num . '; Group: ' . $row->insurance_group . '; ' . $row->insurance_insu_lastname . ', ' . $row->insurance_insu_firstname . '<br><br>';
			}
		}
		$query2 = DB::table('allergies')->where('pid', '=', $pid)->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$data['allergyInfo'] = '';
		if ($query2) {
			$data['allergyInfo'] .= '<ul>';
			foreach ($query2 as $row1) {
				$data['allergyInfo'] .= '<li>' . $row1->allergies_med . '</li>';
			}
			$data['allergyInfo'] .= '</ul>';
		} else {
			$data['allergyInfo'] .= 'No known allergies.';
		}
		$data['signature'] = $this->signature($data['rx']->id);
		return View::make('pdf.prescription_page', $data);
	}
	
	protected function page_medication_list()
	{
		$pid = Session::get('pid');
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['website'] = $practice->website;
		$data['practiceInfo'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo'] .= '<br />';
		$data['practiceInfo'] .= $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo'] .= 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$patient = Demographics::find($pid);
		$data['patientInfo1'] = $patient->firstname . ' ' . $patient->lastname;
		$data['patientInfo2'] = $patient->address;
		$data['patientInfo3'] = $patient->city . ', ' . $patient->state . ' ' . $patient->zip;
		$data['firstname'] = $patient->firstname;
		$data['body'] = 'Active Medications for ' . $patient->firstname . ' ' . $patient->lastname . ':<br />';
		$query = DB::table('rx_list')
			->where('pid', '=', $pid)
			->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')
			->where('rxl_date_old', '=', '0000-00-00 00:00:00')
			->get();
		if ($query) {
			$data['body'] .= '<ul>';
			foreach ($query as $row) {
				$data['body'] .= '<li>' . $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency . ' for ' . $row->rxl_reason . '</li>';
			}
			$data['body'] .= '</ul>';
		} else {
			$data['body'] .= 'None.';
		}
		$data['date'] = date('F jS, Y');
		$data['signature'] = $this->signature(Session::get('user_id'));
		return View::make('pdf.letter_page', $data);
	}
	
	protected function pagecount($filename)
	{
		$pdftext = file_get_contents($filename);
  		$pagecount = preg_match_all("/\/Page\W/", $pdftext, $dummy);
		return $pagecount;
	}
	
	protected function fax_document($pid, $type, $coverpage, $filename, $file_original, $faxnumber, $faxrecipient, $job_id, $sendnow)
	{
		$demo_row = Demographics::find($pid);
		if ($job_id == '') {
			$fax_data = array(
				'user' => Session::get('displayname'),
				'faxsubject' => $type . ' for ' . $demo_row->firstname . ' ' . $demo_row->lastname,
				'faxcoverpage' => $coverpage,
				'practice_id' => Session::get('practice_id')
			);
			$job_id = DB::table('sendfax')->insertGetId($fax_data);
			$this->audit('Add');
			$fax_directory = Session::get('documents_dir') . 'sentfax/' . $job_id;
			mkdir($fax_directory, 0777);
		}
		$filename_parts = explode("/", $filename);
		$fax_filename = $fax_directory . "/" . end($filename_parts);
		copy($filename, $fax_filename);
		$pagecount = $this->pagecount($fax_filename);
		if ($file_original == '') {
			$file_original = $type . ' for ' . $demo_row['firstname'] . ' ' . $demo_row['lastname'];
		}
		$pages_data = array(
			'file' => $fax_filename,
			'file_original' => $file_original,
			'file_size' => '',
			'pagecount' => $pagecount,
			'job_id' => $job_id
		);
		DB::table('pages')->insert($pages_data);
		$this->audit('Add');
		if ($sendnow == "yes") {
			$message = $this->send_fax($job_id, $faxnumber, $faxrecipient);
		} else {
			$message = $job_id;
		}
		return $message;
	}
	
	protected function send_fax($job_id, $faxnumber, $faxrecipient)
	{
		$fax_data = Sendfax::find($job_id);
		if ($faxnumber != '' && $faxrecipient != '') {
			$meta = array("(", ")", "-", " ");
			$fax = str_replace($meta, "", $faxnumber);
			$send_list_data = array(
				'faxrecipient' => $faxrecipient,
				'faxnumber' => str_replace($meta, "", $faxnumber),
				'job_id' => $job_id
			);
			DB::table('recipients')->insert($send_list_data);
			$this->audit('Add');
		}
		$faxrecipients = '';
		$faxnumbers = '';
		$recipientlist = DB::table('recipients')->where('job_id', '=', $job_id)->select('faxrecipient', 'faxnumber')->get();
		foreach ($recipientlist as $row) {
			$faxrecipients .= $row->faxrecipient . ', Fax: ' . $row->faxnumber . "\n";
			if ($faxnumbers != '') {
				$faxnumbers .= ',' . $row->faxnumber;
			} else {
				$faxnumbers .= $row->faxnumber;
			}
		}
		$practice_row = Practiceinfo::find(Session::get('practice_id'));
		$faxnumber_array = explode(",", $faxnumbers);
		$pagesInfo = DB::table('pages')->where('job_id', '=', $job_id)->get();
		$faxpages = '';
		$totalpages = 0;
		$senddate = date('Y-m-d H:i:s');
		foreach ($pagesInfo as $row4) {
			$faxpages .= ' ' . $row4->file;
			$totalpages = $totalpages + $row4->pagecount;
		}
		if ($fax_data->faxcoverpage == 'yes') {
			$cover_filename = Session::get('documents_dir') . 'sentfax/' . $job_id . '/coverpage.pdf';
			$cover_html = $this->page_intro('Cover Page', Session::get('practice_id'))->render();
			$cover_html .= $this->page_coverpage($job_id, $totalpages, $faxrecipients, date("M d, Y, h:i", time()))->render();
			$this->generate_pdf($cover_html, $cover_filename, 'footerpdf');
			while(!file_exists($cover_filename)) {
				sleep(2);
			}
		}
		$config = array(
			'driver' => 'smtp',
			'host' => 'smtp.gmail.com',
			'port' => 465,
			'from' => array('address' => null, 'name' => null),
			'encryption' => 'ssl',
			'username' => $practice_row->fax_email,
			'password' => $practice_row->fax_email_password,
			'sendmail' => '/usr/sbin/sendmail -bs',
			'pretend' => false
		);
		Config::set('mail',$config);
		$data_message = array();
		Mail::send('emails.blank', $data_message, function($message) use ($faxnumber_array, $practice_row, $fax_data, $cover_filename, $pagesInfo) {
			$i = 0;
			foreach ($faxnumber_array as $faxnumber_row) {
				if ($i == 0) {
					$message->to($faxnumber_row . '@' . $practice_row->fax_type);
				} else {
					$message->cc($faxnumber_row . '@' . $practice_row->fax_type);
				}
				$i++;
			}
			$message->from($practice_row->email, $practice_row->practice_name);
			$message->subject($fax_data->faxsubject);
			if ($fax_data->faxcoverpage == 'yes') {
				$message->attach($cover_filename);
			}
			foreach ($pagesInfo as $row5) {
				$message->attach($row5->file);
			}
		});
		$fax_update_data = array(
			'sentdate' => date('Y-m-d'),
			'ready_to_send' => '1',
			'senddate' => $senddate,
			'faxdraft' => '0',
			'attempts' => '0',
			'success' => '1'
		);
		DB::table('sendfax')->where('job_id', '=', $job_id)->update($fax_update_data);
		$this->audit('Update');
		Session::forget('job_id');
		return 'Fax Job ' . $job_id . ' Sent';
	}
	
	protected function send_mail($template, $data_message, $subject, $to, $practice_id)
	{
		$practice = Practiceinfo::find($practice_id);
		$config = array(
			'driver' => 'smtp',
			'host' => 'smtp.gmail.com',
			'port' => 465,
			'from' => array('address' => null, 'name' => null),
			'encryption' => 'ssl',
			'username' => $practice->smtp_user,
			'password' => $practice->smtp_pass,
			'sendmail' => '/usr/sbin/sendmail -bs',
			'pretend' => false
		);
		Config::set('mail',$config);
		Mail::send($template, $data_message, function($message) use ($to, $practice, $subject) {
			$message->to($to)
				->from($practice->email, $practice->practice_name)
				->subject($subject);
		});
		return "E-mail sent.";
	}
	
	protected function generate_ccda($hippa_id)
	{
		$ccda = file_get_contents(__DIR__.'/../../public/ccda.xml');
		$practice_info = Practiceinfo::find(Session::get('practice_id'));
		$ccda = str_replace('?practice_name?', $practice_info->practice_name, $ccda);
		$date_format = "YmdHisO";
		$ccda = str_replace('?effectiveTime?', date($date_format), $ccda);
		$ccda_name = time() . '_ccda.xml';
		$pid = Session::get('pid');
		$ccda = str_replace('?pid?', $pid, $ccda);
		$demographics = Demographics::find($pid);
		$ccda = str_replace('?ss?', $demographics->ss, $ccda);
		$ccda = str_replace('?street_address1?', $demographics->address, $ccda);
		$ccda = str_replace('?city?', $demographics->city, $ccda);
		$ccda = str_replace('?state?', $demographics->state, $ccda);
		$ccda = str_replace('?zip?', $demographics->zip, $ccda);
		$ccda = str_replace('?phone_home?', $demographics->phone_home, $ccda);
		$ccda = str_replace('?firstname?', $demographics->firstname, $ccda);
		$ccda = str_replace('?lastname?', $demographics->lastname, $ccda);
		if ($demographics->sex == 'f') {
			$gender = 'F';
			$gender_full = 'Female';
		} else {
			$gender = 'M';
			$gender_full = 'Male';
		}
		$ccda = str_replace('?gender?', $gender, $ccda);
		$ccda = str_replace('?gender_full?', $gender_full, $ccda);
		$ccda = str_replace('?dob?', date('Ymd', $this->human_to_unix($demographics->DOB)), $ccda);
		if ($demographics->marital_status == 'Annulled') {
			$marital_code = "N";
		}
		if ($demographics->marital_status == 'Common law') {
			$marital_code = "C";
		}
		if ($demographics->marital_status == 'Divorced') {
			$marital_code = "D";
		}
		if ($demographics->marital_status == 'Domestic partner') {
			$marital_code = "P";
		}
		if ($demographics->marital_status == 'Interlocutory') {
			$marital_code = "I";
		}
		if ($demographics->marital_status == 'Legally Separated') {
			$marital_code = "E";
		}
		if ($demographics->marital_status == 'Living together') {
			$marital_code = "G";
		}
		if ($demographics->marital_status == 'Married') {
			$marital_code = "M";
		}
		if ($demographics->marital_status == 'Other') {
			$marital_code = "O";
		}
		if ($demographics->marital_status == 'Registered domestic partner') {
			$marital_code = "R";
		}
		if ($demographics->marital_status == 'Separated') {
			$marital_code = "A";
		}
		if ($demographics->marital_status == 'Single') {
			$marital_code = "S";
		}
		if ($demographics->marital_status == 'Unknown') {
			$marital_code = "U";
		}
		if ($demographics->marital_status == 'Unmarried') {
			$marital_code = "B";
		}
		if ($demographics->marital_status == 'Unreported') {
			$marital_code = "T";
		}
		if ($demographics->marital_status == 'Widowed') {
			$marital_code = "O";
		}
		$ccda = str_replace('?marital_status?', $demographics->marital_status, $ccda);
		$ccda = str_replace('?marital_code?', $marital_code, $ccda);
		$ccda = str_replace('?race?', $demographics->race, $ccda);
		$ccda = str_replace('?race_code?', $demographics->race_code, $ccda);
		$ccda = str_replace('?ethnicity?', $demographics->ethnicity, $ccda);
		$ccda = str_replace('?ethnicity_code?', $demographics->ethnicity_code, $ccda);
		$ccda = str_replace('?guardian_code?', $demographics->guardian_code, $ccda);
		$ccda = str_replace('?guardian_relationship?', $demographics->guardian_relationship, $ccda);
		$ccda = str_replace('?guardian_lastname?', $demographics->guardian_lastname, $ccda);
		$ccda = str_replace('?guardian_firstname?', $demographics->guardian_firstname, $ccda);
		$ccda = str_replace('?guardian_address?', $demographics->guardian_address, $ccda);
		$ccda = str_replace('?guardian_city?', $demographics->guardian_city, $ccda);
		$ccda = str_replace('?guardian_state?', $demographics->guardian_state, $ccda);
		$ccda = str_replace('?guardian_zip?', $demographics->guardian_zip, $ccda);
		$ccda = str_replace('?guardian_phone_home?', $demographics->guardian_phone_home, $ccda);
		if ($practice_info->street_address2 != '') {
			$practice_info->street_address1 .= ', ' . $practice_info->street_address2;
		}
		$ccda = str_replace('?practiceinfo_street_address?', $practice_info->street_address1, $ccda);
		$ccda = str_replace('?practiceinfo_city?', $practice_info->city, $ccda);
		$ccda = str_replace('?practiceinfo_state?', $practice_info->state, $ccda);
		$ccda = str_replace('?practiceinfo_zip?', $practice_info->zip, $ccda);
		$ccda = str_replace('?practiceinfo_phone?', $practice_info->phone, $ccda);
		$user_id = Session::get('user_id');
		$user = User::find($user_id);
		$ccda = str_replace('?user_id?', $user->id, $ccda);
		$ccda = str_replace('?user_lastname?', $user->lastname, $ccda);
		$ccda = str_replace('?user_firstname?', $user->firstname, $ccda);
		$ccda = str_replace('?user_title?', $user->title, $ccda);
		$date_format1 = "Ymd";
		$ccda = str_replace('?effectiveTimeShort?', date($date_format1), $ccda);
		$hippa_info = Hippa::find($hippa_id);
		$ccda = str_replace('?hippa_provider?', $hippa_info->hippa_provider, $ccda);
		$ccda = str_replace('?lang_code?', $demographics->lang_code, $ccda);
		$ccda = str_replace('?encounter_role?', $hippa_info->hippa_role, $ccda);
		if ($hippa_info->hippa_role == "Primary Care Provider") {
			$hippa_role_code = "PP";
		}
		if ($hippa_info->hippa_role == "Consulting Provider") {
			$hippa_role_code = "CP";
		}
		if ($hippa_info->hippa_role == "Referring Provider") {
			$hippa_role_code = "RP";
		}
		$ccda = str_replace('?encounter_role_code?', $hippa_role_code, $ccda);
		$recent_encounter_query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('encounter_signed', '=', 'Yes')
			->orderBy('encounter_DOS', 'desc')
			->take(1)
			->first();
		$ccda = str_replace('?eid?', $recent_encounter_query->eid, $ccda);
		$encounter_info = Encounters::find($recent_encounter_query->eid);
		$provider_info = User::find($encounter_info->user_id);
		$provider_info1 = Providers::find($encounter_info->user_id);
		$ccda = str_replace('?npi?', $provider_info1->npi, $ccda);
		$ccda = str_replace('?provider_title?', $provider_info->title, $ccda);
		$ccda = str_replace('?provider_firstname?', $provider_info->firstname, $ccda);
		$ccda = str_replace('?provider_lastname?', $provider_info->lastname, $ccda);
		$ccda = str_replace('?encounter_dos?', date('Ymd', $this->human_to_unix($encounter_info->encounter_DOS)), $ccda);
		$assessment_info = Assessment::find($recent_encounter_query->eid);
		$ccda = str_replace('?icd9?', $assessment_info->assessment_icd1, $ccda);
		$assessment_info1 = DB::table('icd9')->where('icd9', '=', $assessment_info->assessment_icd1)->first();
		$ccda = str_replace('?icd9_description?', $assessment_info1->icd9_description, $ccda);
		$allergies_query = DB::table('allergies')->where('pid', '=', $pid)->get();
		$allergies_table = "";
		$allergies_file_final = "";
		if ($allergies_query) {
			$i = 1;
			foreach ($allergies_query as $allergies_row) {
				$allergies_table .= "<tr>";
				$allergies_table .= "<td>" . $allergies_row->allergies_med . "</td>";
				$allergies_table .= "<td><content ID='reaction" . $i . "'>" . $allergies_row->allergies_reaction . "</content></td>";
				$allergies_table .= "<td><content ID='severity" . $i . "'>" . $allergies_row->allergies_severity . "</content></td>";
				if ($allergies_row->allergies_date_inactive == '0000-00-00 00:00:00') {
					$allergies_table .= "<td>Active</td>";
					$allergies_status = "Active";
					$allergies_file = file_get_contents(__DIR__.'/../../public/allergies_active.xml');
					$allergies_file = str_replace('?allergies_date_active?', date('Ymd', $this->human_to_unix($allergies_row->allergies_date_active)), $allergies_file);
				} else {
					$allergies_table .= "<td>Inactive</td>";
					$allergies_status = "Inactive";
					$allergies_file = file_get_contents(__DIR__.'/../../public/allergies_inactive.xml');
					$allergies_file = str_replace('?allergies_date_active?', date('Ymd', $this->human_to_unix($allergies_row->allergies_date_active)), $allergies_file);
					$allergies_file = str_replace('?allergies_date_inactive?', date('Ymd', $this->human_to_unix($allergies_row->allergies_date_inactive)), $allergies_file);
				}
				$allergies_table .= "</tr>";
				$reaction_number = "#reaction" . $i;
				$severity_number = "#severity" . $i;
				$allergies_file = str_replace('?reaction_number?', $reaction_number, $allergies_file);
				$allergies_file = str_replace('?severity_number?', $severity_number, $allergies_file);
				$allergies_file = str_replace('?allergies_med?', $allergies_row->allergies_med, $allergies_file);
				$allergies_file = str_replace('?allergies_status?', $allergies_status, $allergies_file);
				$allergies_file = str_replace('?allergies_reaction?', $allergies_row->allergies_reaction, $allergies_file);
				$allergies_file = str_replace('?allergies_code?', '', $allergies_file);
				// Need allergies severity field
				$allergies_file = str_replace('?allergies_severity?', '', $allergies_file);
				$allergy_random_id1 = $this->gen_uuid();
				$allergy_random_id2 = $this->gen_uuid();
				$allergy_random_id3 = $this->gen_uuid();
				$allergies_file = str_replace('?allergy_random_id1?', $allergy_random_id1, $allergies_file);
				$allergies_file = str_replace('?allergy_random_id2?', $allergy_random_id2, $allergies_file);
				$allergies_file = str_replace('?allergy_random_id3?', $allergy_random_id3, $allergies_file);
				$allergies_file_final .= $allergies_file;
				$i++;
			}
		}
		$ccda = str_replace('?allergies_table?', $allergies_table, $ccda);
		$ccda = str_replace('?allergies_file?', $allergies_file_final, $ccda);
		$encounters_query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('encounter_signed', '=', 'Yes')
			->orderBy('encounter_DOS', 'desc')
			->get();
		$e = 1;
		$encounters_table = "";
		$encounters_file_final = "";
		foreach($encounters_query as $encounters_row) {
			$encounters_table .= "<tr>";
			$encounters_table .= "<td><content ID='Encounter" . $e . "'>" . $encounters_row->encounter_cc . "</content></td>";
			$encounters_table .= "<td>" . $encounters_row->encounter_provider . "</td>";
			$encounters_table .= "<td>" . $practice_info->practice_name . "</td>";
			$encounters_table .= "<td>" . date('m-d-Y', $this->human_to_unix($encounters_row->encounter_DOS)) . "</td>";
			$encounters_table .= "</tr>";
			$encounters_file = file_get_contents(__DIR__.'/../../public/encounters.xml');
			$encounters_number = "#Encounter" . $e;
			$billing = DB::table('billing_core')
				->where('eid', '=', $encounters_row->eid)
				->where('billing_group', '=', '1')
				->where('cpt', 'NOT LIKE', "sp%")
				->orderBy('cpt_charge', 'desc')
				->take(1)
				->first();
			$cpt_query = DB::table('cpt_relate')->where('cpt', '=', $billing->cpt)->first();
			if ($cpt_query) {
				$cpt_result = DB::table('cpt_relate')->where('cpt', '=', $billing->cpt)->first();
			} else {
				$cpt_result = DB::table('cpt')->where('cpt', '=', $billing->cpt)->first();
			}
			$provider_info2 = User::find($encounters_row->user_id);
			$encounters_file = str_replace('?encounter_cc?', $encounters_row->encounter_cc, $encounters_file);
			$encounters_file = str_replace('?encounter_number?', $encounters_row->eid, $encounters_file);
			$encounters_file = str_replace('?encounter_code?', $billing->cpt, $encounters_file);
			$encounters_file = str_replace('?encounter_code_desc?', $cpt_result->cpt_description, $encounters_file);
			$encounters_file = str_replace('?encounter_provider?', $encounters_row->encounter_provider, $encounters_file);
			$encounters_file = str_replace('?encounter_dos1?', date('m-d-Y', $this->human_to_unix($encounters_row->encounter_DOS)), $encounters_file);
			$encounters_file = str_replace('?provider_firstname?', $provider_info2->firstname, $encounters_file);
			$encounters_file = str_replace('?provider_lastname?', $provider_info2->lastname, $encounters_file);
			$encounters_file = str_replace('?provider_title?', $provider_info2->title, $encounters_file);
			$encounters_file = str_replace('?encounter_dos?', date('Ymd', $this->human_to_unix($encounters_row->encounter_DOS)), $encounters_file);
			$encounters_file = str_replace('?practiceinfo_street_address?', $practice_info->street_address1, $encounters_file);
			$encounters_file = str_replace('?practiceinfo_city?', $practice_info->city, $encounters_file);
			$encounters_file = str_replace('?practiceinfo_state?', $practice_info->state, $encounters_file);
			$encounters_file = str_replace('?practiceinfo_zip?', $practice_info->zip, $encounters_file);
			$encounters_file = str_replace('?practiceinfo_phone?', $practice_info->phone, $encounters_file);
			$encounters_file = str_replace('?practice_name?', $practice_info->practice_name, $encounters_file);
			$encounter_random_id1 = $this->gen_uuid();
			$encounter_random_id2 = $this->gen_uuid();
			$encounter_random_id3 = $this->gen_uuid();
			$encounters_file = str_replace('?encounter_random_id1?', $encounter_random_id1, $encounters_file);
			$encounters_file = str_replace('?encounter_random_id2?', $encounter_random_id2, $encounters_file);
			$assessment_info1 = Assessment::find($encounters_row->eid);
			$dx_array[] = $assessment_info1->assessment_icd1;
			if ($assessment_info1->assessment_icd2 != "") {
				$dx_array[] = $assessment_info1->assessment_icd2;
			}
			if ($assessment_info1->assessment_icd3 != "") {
				$dx_array[] = $assessment_info1->assessment_icd3;
			}
			if ($assessment_info1->assessment_icd4 != "") {
				$dx_array[] = $assessment_info1->assessment_icd4;
			}
			if ($assessment_info1->assessment_icd5 != "") {
				$dx_array[] = $assessment_info1->assessment_icd5;
			}
			if ($assessment_info1->assessment_icd6 != "") {
				$dx_array[] = $assessment_info1->assessment_icd6;
			}
			if ($assessment_info1->assessment_icd7 != "") {
				$dx_array[] = $assessment_info1->assessment_icd7;
			}
			if ($assessment_info1->assessment_icd8 != "") {
				$dx_array[] = $assessment_info1->assessment_icd8;
			}
			$encounter_diagnosis = '';
			foreach ($dx_array as $dx_item) {
				$dx_file = file_get_contents(__DIR__.'/../../public/encounter_diagnosis.xml');
				$dx_random_id1 = $this->gen_uuid();
				$dx_random_id2 = $this->gen_uuid();
				$dx_random_id3 = $this->gen_uuid();
				$dx_file = str_replace('?dx_random_id1?', $dx_random_id1, $dx_file);
				$dx_file = str_replace('?dx_random_id2?', $dx_random_id2, $dx_file);
				$dx_file = str_replace('?dx_random_id3?', $dx_random_id3, $dx_file);
				$dx_file = str_replace('?icd9?', $dx_item, $dx_file);
				$dx_file = str_replace('?encounter_dos?', date('Ymd', $this->human_to_unix($encounter_info->encounter_DOS)), $dx_file);
				$dx_info = DB::table('icd9')->where('icd9', '=', $dx_item)->first();
				$dx_file = str_replace('?icd9_description?', $dx_info->icd9_description, $dx_file);
				$encounter_diagnosis .= $dx_file;
			}
			$encounters_file = str_replace('?encounter_diagnosis?', $encounter_diagnosis, $encounters_file);
			$encounters_file_final .= $encounters_file;
			$e++;
		}
		$ccda = str_replace('?encounters_table?', $encounters_table, $ccda);
		$ccda = str_replace('?encounters_file?', $encounters_file_final, $ccda);
		$imm_query = DB::table('immunizations')->where('pid', '=', $pid)->orderBy('imm_immunization', 'asc')->orderBy('imm_sequence', 'asc')->get();
		$imm_table = "";
		$imm_file_final = "";
		if ($imm_query) {
			$j = 1;
			foreach ($imm_query as $imm_row) {
				$imm_table .= "<tr>";
				$imm_table .= "<td><content ID='immun" . $j . "'>" . $imm_row->imm_immunization . "</content></td>";
				$imm_table .= "<td>" . date('m-d-Y', $this->human_to_unix($imm_row->imm_date)) . "</td>";
				$imm_table .= "<td>Completed</td>";
				$imm_table .= "</tr>";
				$imm_file = file_get_contents(__DIR__.'/../../public/immunizations.xml');
				$immun_number = "#immun" . $j;
				$imm_file = str_replace('?immun_number?', $immun_number, $imm_file);
				$imm_file = str_replace('?imm_date?', date('Ymd', $this->human_to_unix($imm_row->imm_date)), $imm_file);
				if ($imm_row->imm_route == "intramuscularly") {
					$imm_code = "C28161";
					$imm_code_description = "Intramuscular Route of Administration";
				}
				if ($imm_row->imm_route == "subcutaneously") {
					$imm_code = "C38299";
					$imm_code_description = "Subcutaneous Route of Administration";
				}
				if ($imm_row->imm_route == "intravenously") {
					$imm_code = "C38273";
					$imm_code_description = "Intravascular Route of Administration";
				}
				if ($imm_row->imm_route == "by mouth") {
					$imm_code = "C38289";
					$imm_code_description = "Oropharyngeal Route of Administration";
				}
				$imm_file = str_replace('?imm_code?', $imm_code, $imm_file);
				$imm_file = str_replace('?imm_code_description?', $imm_code_description, $imm_file);
				$imm_file = str_replace('?imm_dosage?', $imm_row->imm_dosage, $imm_file);
				$imm_file = str_replace('?imm_dosage_unit?', $imm_row->imm_dosage_unit, $imm_file);
				$imm_file = str_replace('?imm_cvxcode?', $imm_row->imm_cvxcode, $imm_file);
				$imm_random_id1 = $this->gen_uuid();
				$imm_file = str_replace('?imm_random_id1?', $imm_random_id1, $imm_file);
				$cvx = DB::table('cvx')->where('cvx_code', '=', $imm_row->imm_cvxcode)->first();
				$imm_file = str_replace('?vaccine_name?', $cvx->vaccine_name, $imm_file);
				$imm_file = str_replace('?imm_manufacturer?', $imm_row->imm_manufacturer, $imm_file);
				$imm_file_final .= $imm_file;
				$j++;
			}
		}
		$ccda = str_replace('?imm_table?', $imm_table, $ccda);
		$ccda = str_replace('?imm_file?', $imm_file_final, $ccda);
		$med_query = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		$med_table = "";
		$med_file_final = "";
		if ($med_query) {
			$k = 1;
			foreach ($med_query as $med_row) {
				$med_table .= "<tr>";
				$med_table .= "<td><content ID='med" . $k . "'>" . $med_row->rxl_medication . ' ' . $med_row->rxl_dosage . ' ' . $med_row->rxl_dosage_unit . "</content></td>";
				if ($med_row->rxl_sig == '') {
					$instructions = $med_row->rxl_instructions;
					$med_dosage = '';
					$med_dosage_unit = '';
					$med_code = '';
					$med_code_description = '';
					$med_period = '';
				} else {
					$instructions = $med_row->rxl_sig . ' ' . $med_row->rxl_route . ' ' . $med_row->rxl_frequency;
					$med_dosage_parts = explode(" ", $med_row->rxl_sig);
					$med_dosage = $med_dosage_parts[0];
					$med_dosage_unit = $med_dosage_parts[1];
					if ($med_row->rxl_route == "by mouth") {
						$med_code = "C38289";
						$med_code_description = "Oropharyngeal Route of Administration";
					}
					if ($med_row->rxl_route == "per rectum") {
						$med_code = "C38295";
						$med_code_description = "Rectal Route of Administration";
					}
					if ($med_row->rxl_route == "subcutaneously") {
						$med_code = "C38299";
						$med_code_description = "Subcutaneous Route of Administration";
					}
					if ($med_row->rxl_route == "intravenously") {
						$med_code = "C38273";
						$med_code_description = "Intravascular Route of Administration";
					}
					if ($med_row->rxl_route == "intramuscularly") {
						$med_code = "C28161";
						$med_code_description = "Intramuscular Route of Administration";
					}
					$med_period = '';
					$med_freq_array_1 = array("once daily", "every 24 hours", "once a day", "1 time a day", "QD");
					$med_freq_array_2 = array("twice daily", "every 12 hours", "two times a day", "2 times a day", "BID", "q12h", "Q12h");
					$med_freq_array_3 = array("three times daily", "every 8 hours", "three times a day", "3 times daily", "3 times a day", "TID", "q8h", "Q8h");
					$med_freq_array_4 = array("every six hours", "every 6 hours", "four times daily", "4 times a day", "four times a day", "4 times daily", "QID", "q6h", "Q6h");
					$med_freq_array_5 = array("every four hours", "every 4 hours", "six times a day", "6 times a day", "six times daily", "6 times daily", "q4h", "Q4h");
					$med_freq_array_6 = array("every three hours", "every 3 hours", "eight times a day", "8 times a day", "eight times daily", "8 times daily", "q3h", "Q3h");
					$med_freq_array_7 = array("every two hours", "every 2 hours", "twelve times a day", "12 times a day", "twelve times daily", "12 times daily", "q2h", "Q2h");
					$med_freq_array_8 = array("every hour", "every 1 hour", "every one hour", "q1h", "Q1h");
					if (in_array($med_row->rxl_frequency, $med_freq_array_1)) {
						$med_period = "24";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_2)) {
						$med_period = "12";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_3)) {
						$med_period = "8";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_4)) {
						$med_period = "6";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_5)) {
						$med_period = "4";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_6)) {
						$med_period = "3";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_7)) {
						$med_period = "2";
					}
					if (in_array($med_row->rxl_frequency, $med_freq_array_8)) {
						$med_period = "1";
					}
				}
				$med_table .= "<td>" . $instructions . "</td>";
				$med_table .= "<td>" . date('m-d-Y', $this->human_to_unix($med_row->rxl_date_active)) . "</td>";
				$med_table .= "<td>Active</td>";
				$med_table .= "<td>" . $med_row->rxl_reason . "</td>";
				$med_table .= "</tr>";
				$med_file = file_get_contents(__DIR__.'/../../public/medications.xml');
				$med_number = "#med" . $k;
				$med_random_id1 = $this->gen_uuid();
				$med_random_id2 = $this->gen_uuid();
				$med_file = str_replace('?med_random_id1?', $med_random_id1, $med_file);
				$med_file = str_replace('?med_random_id2?', $med_random_id2, $med_file);
				$med_file = str_replace('?med_number?', $med_number, $med_file);
				$med_file = str_replace('?med_date_active?', date('Ymd', $this->human_to_unix($med_row->rxl_date_active)), $med_file);
				$med_file = str_replace('?med_code?', $med_code, $med_file);
				$med_file = str_replace('?med_code_description?', $med_code_description, $med_file);
				$med_file = str_replace('?med_period?', $med_period, $med_file);
				$med_file = str_replace('?med_dosage?', $med_dosage, $med_file);
				$med_file = str_replace('?med_dosage_unit?', $med_dosage_unit, $med_file);
				$rxnormapi = new RxNormApi();
				$rxnormapi->output_type = 'json';
				$rxnorm = json_decode($rxnormapi->findRxcuiById("NDC", $med_row->rxl_ndcid), true);
				if (isset($rxnorm['idGroup']['rxnormId'][0])) {
					$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($rxnorm['idGroup']['rxnormId'][0]), true);
					$med_rxnorm_code = $rxnorm['idGroup']['rxnormId'][0];
					$med_name = $rxnorm1['properties']['name'];
				} else {
					$med_rxnorm_code = '';
					$med_name = $med_row->rxl_medication . ' ' . $med_row->rxl_dosage . ' ' . $med_row->rxl_dosage_unit ;
				}
				$med_file = str_replace('?med_rxnorm_code?', $med_rxnorm_code, $med_file);
				$med_file = str_replace('?med_name?', $med_name, $med_file);
				$med_file_final .= $med_file;
				$k++;
			}
		}
		$ccda = str_replace('?med_table?', $med_table, $ccda);
		$ccda = str_replace('?med_file?', $med_file_final, $ccda);
		$orders_query = DB::table('orders')->where('eid', '=', $recent_encounter_query->eid)->get();
		$orders_table = "";
		$orders_file_final = "";
		if ($orders_query) {
			foreach ($orders_query as $orders_row) {
				if ($orders_row->orders_labs != '') {
					$orders_labs_array = explode("\n",$orders_row->orders_labs);
					$n1 = 1;
					foreach ($orders_labs_array as $orders_labs_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_labs_" . $n1 . "'>" . $orders_labs_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', $this->human_to_unix($orders_row->orders_date)) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_labs_row, $orders_row->orders_date, '#orders_lab_' . $n1);
						$n1++;
					}
				}
				if ($orders_row->orders_radiology != '') {
					$orders_rad_array = explode("\n",$orders_row->orders_radiology);
					$n2 = 1;
					foreach ($orders_rad_array as $orders_rad_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_rad_" . $n2 . "'>" . $orders_rad_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', $this->human_to_unix($orders_row->orders_date)) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_rad_row, $orders_row->orders_date, '#orders_rad_' . $n2);
						$n2++;
					}
				}
				if ($orders_row->orders_cp != '') {
					$orders_cp_array = explode("\n",$orders_row->orders_cp);
					$n3 = 1;
					foreach ($orders_cp_array as $orders_cp_row) {
						$orders_table .= "<tr>";
						$orders_table .= "<td><content ID='orders_cp_" . $n3 . "'>" . $orders_cp_row . "</td>";
						$orders_table .= "<td>" . date('m-d-Y', $this->human_to_unix($orders_row->orders_date)) . "</td>";
						$orders_table .= "</tr>";
						$orders_file_final .= $this->get_snomed_code($orders_cp_row, $orders_row->orders_date, '#orders_cp_' . $n3);
						$n3++;
					}
				}
				if ($orders_row->orders_referrals != '') {
					$referral_orders = explode("\nRequested action:\n",$orders_row->orders_referrals);
					if (count($referral_orders) > 1) {
						$orders_ref_array = explode("\n",$referral_orders[0]);
						$n4 = 1;
						foreach ($orders_ref_array as $orders_ref_row) {
							$orders_table .= "<tr>";
							$orders_table .= "<td><content ID='orders_ref_" . $n4 . "'>" . $orders_ref_row . "</td>";
							$orders_table .= "<td>" . date('m-d-Y', $this->human_to_unix($orders_row->orders_date)) . "</td>";
							$orders_table .= "</tr>";
							$orders_file_final .= $this->get_snomed_code($orders_ref_row, $orders_row->orders_date, '#orders_ref_' . $n4);
							$n4++;
						}
					}
				}
			}
		}
		$ccda = str_replace('?orders_table?', $orders_table, $ccda);
		$ccda = str_replace('?orders_file?', $orders_file_final, $ccda);
		$issues_query = DB::table('issues')->where('pid', '=', $pid)->get();
		$issues_table = "";
		$issues_file_final = "";
		if ($issues_query) {
			$l = 1;
			foreach ($issues_query as $issues_row) {
				$issues_table .= "<list listType='ordered'>";
				$issues_array = explode(' [', $issues_row->issue);
				$issue_code = str_replace("]", "", $issues_array[1]);
				$issue_code_description = $issues_array[0];
				if ($issues_row->issue_date_inactive != '0000-00-00 00:00:00') {
					$issues_table .= "<item><content ID='problem" . $l . "'>" . $issues_row->issue . ": Status - Resolved</content></item>";
					$issues_status = "Resolved";
					$issues_code = "413322009";
					$issues_file = file_get_contents(__DIR__.'/../../public/issues_inactive.xml');
					$issues_file = str_replace('?issue_date_inactive?', date('Ymd', $this->human_to_unix($issues_row->issue_date_inactive)), $issues_file);
				} else {
					$issues_table .= "<item><content ID='problem" . $l . "'>" . $issues_row->issue . ": Status - Active</content></item>";
					$issues_status = "Active";
					$issues_code = "55561003";
					$issues_file = file_get_contents(__DIR__.'/../../public/issues_active.xml');
				}
				$issues_table .= "</list>";
				$issues_file = str_replace('?issue_date_active?', date('Ymd', $this->human_to_unix($issues_row->issue_date_active)), $issues_file);
				$issues_file = str_replace('?issue_code?', $issue_code, $issues_file);
				$issues_file = str_replace('?issue_code_description?', $issue_code_description, $issues_file);
				$issues_number = "#problem" . $l;
				$issues_random_id1 = $this->gen_uuid();
				$issues_file = str_replace('?issues_random_id1?', $issues_random_id1, $issues_file);
				$issues_file = str_replace('?issues_number?', $issues_number, $issues_file);
				$issues_file = str_replace('?issues_code?', $issues_code, $issues_file);
				$issues_file = str_replace('?issues_status?', $issues_status, $issues_file);
				$issues_file_final .= $issues_file;
				$l++;
			}
		}
		$ccda = str_replace('?issues_table?', $issues_table, $ccda);
		$ccda = str_replace('?issues_file?', $issues_file_final, $ccda);
		$proc_query = DB::table('procedure')->where('eid', '=', $recent_encounter_query->eid)->get();
		$proc_table = "";
		$proc_file_final = "";
		if ($proc_query) {
			$m = 1;
			foreach ($proc_query as $proc_row) {
				$proc_table .= "<tr>";
				$proc_table .= "<td><content ID='proc" . $m . "'>" . $proc_row->proc_type . "</content></td>";
				$proc_table .= "<td>" . date('m-d-Y', $this->human_to_unix($proc_row->proc_date)) . "</td>";
				$proc_table .= "</tr>";
				$proc_file = file_get_contents(__DIR__.'/../../public/proc.xml');
				$proc_file = str_replace('?proc_date?', date('Ymd', $this->human_to_unix($proc_row->proc_date)), $proc_file);
				$proc_file = str_replace('?proc_type?', $proc_row->proc_type, $proc_file);
				$proc_file = str_replace('?proc_cpt?', $proc_row->proc_cpt, $proc_file);
				$proc_file = str_replace('?practiceinfo_street_address?', $practice_info->street_address1, $proc_file);
				$proc_file = str_replace('?practiceinfo_city?', $practice_info->city, $proc_file);
				$proc_file = str_replace('?practiceinfo_state?', $practice_info->state, $proc_file);
				$proc_file = str_replace('?practiceinfo_zip?', $practice_info->zip, $proc_file);
				$proc_file = str_replace('?practiceinfo_phone?', $practice_info->phone, $proc_file);
				$proc_file = str_replace('?practice_name?', $practice_info->practice_name, $proc_file);
				$proc_number = "#proc" . $m;
				$proc_random_id1 = $this->gen_uuid();
				$proc_file = str_replace('?proc_random_id1?', $proc_random_id1, $proc_file);
				$proc_file_final .= $proc_file;
				$m++;
			}
		}
		$ccda = str_replace('?proc_table?', $proc_table, $ccda);
		$ccda = str_replace('?proc_file?', $proc_file_final, $ccda);
		$other_history_row = DB::table('other_history')->where('eid', '=', $recent_encounter_query->eid)->first();
		$other_history_table = "";
		$other_history_file = "";
		if ($other_history_row) {
			if ($other_history_row->oh_tobacco != '') {
				$other_history_table .= "<td>Smoking Status</td>";
				$other_history_table .= "<td><content ID='other_history1'>" . $other_history_row->oh_tobacco . "</td>";
				$other_history_table .= "<td>" . date('m-d-Y', $this->human_to_unix($other_history_row->oh_date)) . "</td>";
				$other_history_table .= "</tr>";
				$other_history_table .= "<tr>";
				if ($demographics->tobacco == 'yes') {
					$other_history_code = "77176002";
					$other_history_description = "Smoker";
				} else {
					$other_history_code = "8392000";
					$other_history_description = "Non-Smoker";
				}
				$other_history_file = file_get_contents(__DIR__.'/../../public/social_history.xml');
				$other_history_file = str_replace('?other_history_code?', $other_history_code, $other_history_file);
				$other_history_file = str_replace('?other_history_description?', $other_history_description, $other_history_file);
				$other_history_file = str_replace('?other_history_date?', date('Ymd', $this->human_to_unix($other_history_row->oh_date)), $other_history_file);
			}
		}
		$ccda = str_replace('?other_history_table?', $other_history_table, $ccda);
		$ccda = str_replace('?other_history_file?', $other_history_file, $ccda);
		$vitals_row = DB::table('vitals')->where('eid', '=', $recent_encounter_query->eid)->first();
		$vitals_table = "";
		$vitals_file_final = "";
		if ($vitals_row) {
			$vitals_table .= '<thead><tr><th align="right">Date / Time: </th><th>' . date('m-d-Y h:i A', $this->human_to_unix($vitals_row->vitals_date)) . '</th></tr></thead><tbody>';
			$vitals_file_final .= '               <entry typeCode="DRIV"><organizer classCode="CLUSTER" moodCode="EVN"><templateId root="2.16.840.1.113883.10.20.22.4.26"/><id root="';
			$vitals_file_final .= $this->gen_uuid() . '"/><code code="46680005" codeSystem="2.16.840.1.113883.6.96" codeSystemName="SNOMED-CT" displayName="Vital signs"/><statusCode code="completed"/><effectiveTime value="';
			$vitals_file_final .= date('Ymd', $this->human_to_unix($vitals_row->vitals_date)) . '"/>';
			if ($vitals_row->height != '') {
				$vitals_table .= '<tr><th align="left">Height</th><td><content ID="vit_height">';
				$vitals_table .= $vitals_row->height . ' ' . $practice_info->height_unit;
				$vitals_table .= '</content></td></tr>';
				$vitals_code1 = "8302-2";
				$vitals_description1 = "Body height";
				$vitals_file = file_get_contents(__DIR__.'/../../public/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code1, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description1, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', $this->human_to_unix($vitals_row->vitals_date)), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_height', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row->height, $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', $practice_info->height_unit, $vitals_file);
				$vitals_random_id1 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id1, $vitals_file);
			}
			if ($vitals_row->weight != '') {
				$vitals_table .= '<tr><th align="left">Weight</th><td><content ID="vit_weight">';
				$vitals_table .= $vitals_row->weight . ' ' . $practice_info->weight_unit;
				$vitals_table .= '</content></td></tr>';
				$vitals_code2 = "3141-9";
				$vitals_description2 = "Body weight Measured";
				$vitals_file_ = file_get_contents(__DIR__.'/../../public/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code2, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description2, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', $this->human_to_unix($vitals_row->vitals_date)), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_weight', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row->weight, $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', $practice_info->weight_unit, $vitals_file);
				$vitals_random_id2 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id2, $vitals_file);
			}
			if ($vitals_row->bp_systolic != '' && $vitals_row->bp_diastolic) {
				$vitals_table .= '<tr><th align="left">Blood Pressure</th><td><content ID="vit_bp">';
				$vitals_table .= $vitals_row->bp_systolic . '/' . $vitals_row->bp_diastolic . ' mmHg';
				$vitals_table .= '</content></td></tr>';
				$vitals_code3 = "8480-6";
				$vitals_description3 = "Intravascular Systolic";
				$vitals_file_ = file_get_contents(__DIR__.'/../../public/vitals.xml');
				$vitals_file = str_replace('?vitals_code?', $vitals_code3, $vitals_file);
				$vitals_file = str_replace('?vitals_description?', $vitals_description3, $vitals_file);
				$vitals_file = str_replace('?vitals_date?', date('Ymd', $this->human_to_unix($vitals_row->vitals_date)), $vitals_file);
				$vitals_file = str_replace('?vitals_id?', '#vit_bp', $vitals_file);
				$vitals_file = str_replace('?vitals_value?', $vitals_row->bp_systolic, $vitals_file);
				$vitals_file = str_replace('?vitals_unit?', "mmHg", $vitals_file);
				$vitals_random_id3 = $this->gen_uuid();
				$vitals_file = str_replace('?vitals_random_id1?', $vitals_random_id3, $vitals_file);
			} 
			$vitals_table .= '</tbody>';
			$vitals_file_final .= '                  </organizer>';
			$vitals_file_final .= '               </entry>';
		}
		$ccda = str_replace('?vitals_table?', $vitals_table, $ccda);
		$ccda = str_replace('?vitals_file?', $vitals_file_final, $ccda);
		return $ccda;
	}
	
	protected function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
	
	protected function get_snomed_code($item, $date, $id)
	{
		$pos = strpos($item, ", SNOMED : ");
		$pos1 = strpos($item, ", CPT: ");
		if ($pos !== FALSE) {
			$items = explode(", SNOMED: ", $item);
			$term_row = DB::table('curr_description_f')->where('conceptid', '=', $items[1])->where('active', '=', '1')->first();
			$orders_file1 = file_get_contents(__DIR__.'/../../public/orders.xml');
			$orders_file1 = str_replace('?orders_date?', date('Ymd', $this->human_to_unix($date)), $orders_file1);
			$orders_file1 = str_replace('?orders_code?', $items[1], $orders_file1);
			$orders_file1 = str_replace('?orders_code_description?', $term_row->term, $orders_file1);
			$orders_random_id1 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id1, $orders_file1);
		} elseif ($pos1 !== FALSE) {
			$items = explode(", CPT: ", $item);
			$orders_file2 = file_get_contents(__DIR__.'/../../public/orders_cpt.xml');
			$orders_file2 = str_replace('?orders_date?', date('Ymd', $this->human_to_unix($date)), $orders_file2);
			$orders_file2 = str_replace('?orders_code?', $items[1], $orders_file2);
			$orders_file2 = str_replace('?orders_code_description?', $term_row->term, $orders_file2);
			$orders_random_id2 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id2, $orders_file2);
		} else {
			$orders_file3 = file_get_contents(__DIR__.'/../../public/orders_generic.xml');
			$orders_file3 = str_replace('?orders_date?', date('Ymd', $this->human_to_unix($date)), $orders_file3);
			$orders_file3 = str_replace('?orders_description?', $item, $orders_file3);
			$orders_file3 = str_replace('?orders_reference_id?', $id, $orders_file3);
			$orders_random_id3 = $this->gen_uuid();
			$orders_file = str_replace('?orders_random_id1?', $orders_random_id3, $orders_file3);
		}
		return $orders_file;
	}
	
	protected function page_invoice1($eid)
	{
		$pid = Session::get('pid');
		$assessmentInfo = Assessment::find($eid);
		if ($assessmentInfo) {
			$data['assessment'] = '';
			if ($assessmentInfo->assessment_1 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_1 . '<br />';
				if ($assessmentInfo->assessment_2 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_2 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_2 . '<br />';
				if ($assessmentInfo->assessment_3 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_3 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_3 . '<br />';
				if ($assessmentInfo->assessment_4 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_4 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_4 . '<br />';
				if ($assessmentInfo->assessment_5 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_5 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_5 . '<br />';
				if ($assessmentInfo->assessment_6 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_6 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_6 . '<br />';
				if ($assessmentInfo->assessment_7 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_7 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_7 . '<br />';
				if ($assessmentInfo->assessment_8 == '') {
					$data['assessment'] .= '<br />';
				}
			}
			if ($assessmentInfo->assessment_8 != '') {
				$data['assessment'] .= $assessmentInfo->assessment_8 . '<br /><br />';
			}
		} else {
			$data['assessment'] = '';
		}
		$result1 = DB::table('billing_core')->where('eid', '=', $eid)->orderBy('cpt_charge', 'desc')->get(); 
		if ($result1) {
			$charge = 0;
			$payment = 0;
			$data['text'] = '<table class="pure-table"><tr><th style="width:14%">PROCEDURE</th><th style="width:14%">UNITS</th><th style="width:50%">DESCRIPTION</th><th style="width:22%">CHARGE PER UNIT</th></tr>';
			foreach ($result1 as $key1 => $value1) {
				$cpt_charge1[$key1]  = $value1->cpt_charge;
			}
			array_multisort($cpt_charge1, SORT_DESC, $result1);
			foreach ($result1 as $result1a) {
				if ($result1a->cpt) {
					$query2 = DB::table('cpt_relate')->where('cpt', '=', $result1a->cpt)->first();
					if ($query2) {
						$result2 = DB::table('cpt_relate')->where('cpt', '=', $result1a->cpt)->first();
					} else {
						$result2 = DB::table('cpt')->where('cpt', '=', $result1a->cpt)->first();
					}
					$data['text'] .= '<tr><td>' . $result1a->cpt . '</td><td>' . $result1a->unit . '</td><td>' . $result2->cpt_description . '</td><td>$' . $result1a->cpt_charge . '</td></tr>';
					$charge += $result1a->cpt_charge * $result1a->unit;
				} else {
					$data['text'] .= '<tr><td>Date of Payment:</td><td>' . $result1a->dos_f . '</td><td>' . $result1a->payment_type . '</td><td">$(' . $result1a->payment . ')</td></tr>';
					$payment = $payment + $result1a->payment;
				}
			}
			
			$balance = $charge - $payment;
			$charge = number_format($charge, 2, '.', ',');
			$payment = number_format($payment, 2, '.', ',');
			$balance = number_format($balance, 2, '.', ',');
			$data['text'] .= '<tr><td></td><td></td><td><strong>Total Charges:</strong></td><td><strong>$' . $charge . '</strong></td></tr><tr><td></td><td></td><td><strong>Total Payments:</strong></td><td><strong>$' . $payment . '</strong></td></tr><tr><td></td><td></td><td></td><td><hr/></td></tr><tr><td></td><td></td><td><strong>Remaining Balance:</strong></td><td><strong>$' . $balance . '</strong></td></tr></table>';
		} else {
			$data['text'] = 'No procedures.';
		}
		$row = Demographics::find($pid);
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['disclaimer'] = '<br>Please send a check payable to ' . $practice->practice_name . ' and mail it to:';
		$data['disclaimer'] .= '<br>' . $practice->billing_street_address1;
		if ($practice->billing_street_address2 != '') {
			$data['text'] .= ', ' . $practice->billing_street_address2;
		}
		$data['disclaimer'] .= '<br>' . $practice->billing_city . ', ' . $practice->billing_state . ' ' . $practice->billing_zip;
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['patientInfo'] = $row;
		$data['dob'] = date('m/d/Y', $this->human_to_unix($row->DOB));
		$encounterInfo = Encounters::find($eid);
		$data['encounter_DOS'] = date('F jS, Y', $this->human_to_unix($encounterInfo->encounter_DOS));
		$data['encounter_provider'] = $encounterInfo->encounter_provider;
		$query1 = DB::table('insurance')->where('pid', '=', $pid)->where('insurance_plan_active', '=', 'Yes')->get();
		$data['insuranceInfo'] = '';
		if ($query1) {
			foreach ($query1 as $row1) {
				$data['insuranceInfo'] .= $row1->insurance_plan_name . '; ID: ' . $row1->insurance_id_num . '; Group: ' . $row1->insurance_group . '; ' . $row1->insurance_insu_lastname . ', ' . $row1->insurance_insu_firstname . '<br><br>';
			}
		}
		$data['title'] = "INVOICE";
		$data['date'] = date('F jS, Y', time());
		$result = DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		if (is_null($result->billing_notes) || $result->billing_notes == '') {
			$billing_notes = 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result->billing_notes . "\n" . 'Invoice for encounter (Date of Service: ' . $data['encounter_DOS'] . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->update($billing_notes_data);
		$this->audit('Update');
		return View::make('pdf.invoice_page', $data);
	}
	
	protected function page_invoice2($id)
	{
		$pid = Session::get('pid');
		$result1 = DB::table('billing_core')->where('other_billing_id', '=', $id)->where('payment', '=', '0')->first();
		if ($result1) {
			$data['text'] = '<table class="pure-table"><tr><th style="width:14%">DATE</th><th style="width:14%">UNITS</th><th style="width:50%">DESCRIPTION</th><th style="width:22%">CHARGE PER UNIT</th></tr>';
			$charge = 0;
			$payment = 0;
			$data['text'] .= '<tr><td>' . $result1->dos_f . '</td><td>' . $result1->unit . '</td><td>' . $result1->reason . '</td><td>$' . $result1->cpt_charge . '</td></tr>';
			$charge += $result1->cpt_charge * $result1->unit;
			$query2 = DB::table('billing_core')->where('other_billing_id', '=', $result1->billing_core_id)->where('payment', '!=', '0')->get();
			if ($query2) {
				foreach ($query2 as $row2) {
					$data['text'] .= '<tr><td>Date of Payment:</td><td>' . $row2->dos_f . '</td><td>' . $row2->payment_type . '</td><td>$(' . $row2->payment . ')</td></tr>';
					$payment += $row2->payment;
				}
			}	
			$balance = $charge - $payment;
			$charge = number_format($charge, 2, '.', ',');
			$payment = number_format($payment, 2, '.', ',');
			$balance = number_format($balance, 2, '.', ',');
			$data['text'] .= '<tr><td></td><td></td><td><strong>Total Charges:</strong></td><td><strong>$' . $charge . '</strong></td></tr><tr><td></td><td></td><td><strong>Total Payments:</strong></td><td><strong>$' . $payment . '</strong></td></tr><tr><td></td><td></td><td></td><td><hr/></td></tr><tr><td></td><td></td><td><strong>Remaining Balance:</strong></td><td><strong>$' . $balance . '</strong></td></tr></table>';
		} else {
			$data['text'] = 'No procedures.';
		}
		$row = Demographics::find($pid);
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['disclaimer'] = '<br>Please send a check payable to ' . $practice->practice_name . ' and mail it to:';
		$data['disclaimer'] .= '<br>' . $practice->billing_street_address1;
		if ($practice->billing_street_address2 != '') {
			$data['text'] .= ', ' . $practice->billing_street_address2;
		}
		$data['disclaimer'] .= '<br>' . $practice->billing_city . ', ' . $practice->billing_state . ' ' . $practice->billing_zip;
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['patientInfo'] = $row;
		$data['dob'] = date('m/d/Y', $this->human_to_unix($row->DOB));
		$data['title'] = "INVOICE";
		$data['date'] = date('F jS, Y', time());
		$result = DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		if (is_null($result->billing_notes) || $result->billing_notes == '') {
			$billing_notes = 'Invoice for ' . $result1->reason . ' (Date of Bill: ' . $result1->dos_f . ') printed on ' . $data['date'] . '.';
		} else {
			$billing_notes = $result->billing_notes . "\n" . 'Invoice for ' . $result1->reason . ' (Date of Bill: ' . $result1->dos_f . ') printed on ' . $data['date'] . '.';
		}
		$billing_notes_data = array(
			'billing_notes' => $billing_notes
		);
		DB::table('demographics_notes')->where('pid', '=', Session::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->update($billing_notes_data);
		$this->audit('Update');
		return View::make('pdf.invoice_page2', $data);
	}
	
	protected function page_financial_results($results)
	{
		$body = '<br><br><table class="pure-table"><tr><th>Date</th><th>Last Name</th><th>First Name</th><th>Amount</th><th>Type</th></tr>';
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['dos_f'] . "</td><td>" . $results_row1['lastname'] . "</td><td>" . $results_row1['firstname'] . "</td><td>" . money_format('%n', $results_row1['amount']) . "</td><td>" . $results_row1['type'] . "</td></tr>";
		}
		$body .= '</table></body></html>';
		return $body;
	}
	
	protected function page_mtm_cp($pid)
	{
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$row = Demographics::find($pid);
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['date'] = date('F jS, Y');
		$data['salutation'] = "Dear " . $row->firstname .",";
		$data['practicePhone'] = $practice->phone;
		$data['providerSignature'] = $this->signature(Session::get('user_id'));
		return View::make('pdf.mtm_cp_page', $data);
	}
	
	protected function page_mtm_map($pid)
	{
		$practice_id = Session::get('practice_id');
		$practice = Practiceinfo::find($practice_id);
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$row = Demographics::find($pid);
		$data['patientDOB'] = date('m/d/Y', $this->human_to_unix($row->DOB));
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['date'] = date('F jS, Y');
		$data['practicePhone'] = $practice->phone;
		$query = DB::table('mtm')->where('pid', '=', $pid)->where('complete', '=', 'no')->where('practice_id', '=', $practice_id)->get();
		$data['mapItems'] = '';
		if ($query) {
			foreach ($query as $query_row) {
				$data['mapItems'] .= '<div style="width:6.62in;height:0.2in"></div>';
				$data['mapItems'] .= '<table><tr><td colspan="2" style="min-height:0.7in;">';
				$data['mapItems'] .= '<b>What we talked about:</b><br>' . $query_row->mtm_description . '</td></tr><tr><td style="width: 3.31in;min-height:0.9in;">';
				$data['mapItems'] .= '<b>What I need to do:</b><br>' . $query_row->mtm_recommendations . '</td><td style="width: 3.31in;min-height:0.9in;">';
				$data['mapItems'] .= '<b>What I did and when I did it:</b></td></table>';
			}
		}
		return View::make('pdf.mtm_map_page', $data);
	}
	
	protected function page_mtm_pml($pid)
	{
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$row = Demographics::find($pid);
		$data['patientDOB'] = date('m/d/Y', $this->human_to_unix($row->DOB));
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['date'] = date('F jS, Y');
		$data['practicePhone'] = $practice->phone;
		$allergies_query = DB::table('allergies')->where('pid', '=', $pid)->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($allergies_query) {
			$data['allergies'] = '<ol>';
			foreach ($allergies_query as $allergies_row) {
				$data['allergies'] .= '<li>' . $row->allergies_med . ' - ' . $row->allergies_reaction . '</li>';
			}
			$data['allergies'] .= '</ol>';
		} else {
			$data['allergies'] = 'None.';
		}
		$rx_query = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		$data['pmlItems'] = '';
		if ($rx_query) {
			foreach ($rx_query as $rx_row) {
				$data['pmlItems'] .= '<div style="width:6.62in;height:0.2in"></div>';
				$data['pmlItems'] .= '<table><tr><td colspan="2" style="min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Medication:</b><br>' . $rx_row->rxl_medication . ', ' . $rx_row->rxl_dosage . ' ' . $rx_row->rxl_dosage_unit . '</td></tr><tr><td colspan="2" style="min-height:0.23in;">';
				if ($rx_row->rxl_sig == '') {
					$data['pmlItems'] .= '<b>How I use it:</b><br>' . $rx_row->rxl_instructions . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				} else {
					$data['pmlItems'] .= '<b>How I use it:</b><br>' . $rx_row->rxl_sig . ' ' . $rx_row->rxl_route . ' ' . $rx_row->rxl_frequency . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				}
				$data['pmlItems'] .= '<b>Why I use it:</b><br>' . ucfirst($rx_row->rxl_reason) . '</td><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Prescriber:</b><br>' . $rx_row->rxl_provider . '</td></tr><tr><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Date I started using it:</b><br>' . date('m/d/Y', $this->human_to_unix($rx_row->rxl_date_active)) . '</td><td style="width: 3.31in;min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Date I stopped using it:</b><br></td></tr><tr><td colspan="2" style="min-height:0.23in;">';
				$data['pmlItems'] .= '<b>Why I stopped using it:</b></td></tr></table>';
			}
		}
		return View::make('pdf.mtm_pml_page', $data);
	}
	
	protected function page_mtm_provider($pid)
	{
		$practice_id = Session::get('practice_id');
		$practice = Practiceinfo::find($practice_id);
		$data['practiceName'] = $practice->practice_name;
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$row = Demographics::find($pid);
		$data['patientDOB'] = date('m/d/Y', $this->human_to_unix($row->DOB));
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patient_doctor'] = $row->preferred_provider;
		$data['date'] = date('F jS, Y');
		$query = DB::table('mtm')->where('pid', '=', $pid)->where('complete', '=', 'no')->where('practice_id', '=', $practice_id)->get();
		$data['topics'] = '';
		$data['recommendations'] = '';
		if ($query) {
			$data['topics'] = "<ol>";
			$data['recommendations'] = "<ol>";
			foreach ($query as $query_row) {
				$data['topics'] .= '<li>' . $query_row->mtm_description . '</li>';
				$data['recommendations'] .= '<li>' . $query_row->mtm_recommendations . '</li>';
			}
			$data['topics'] .= "</ol>";
			$data['recommendations'] .= "</ol>";
		}
		$data['providerSignature'] = $this->signature(Session::get('user_id'));
		return View::make('pdf.mtm_provider_page', $data);
	}
	
	protected function add_closed1($day, $minTime, $day2, $events, $start, $end)
	{
		$repeat_start = strtotime('this ' . $day . ' ' . $minTime, $start); 
		$repeat_end = strtotime('this ' . $day . ' ' . $day2, $start);
		while ($repeat_start <= $end) {
			$repeat_start1 = date('c', $repeat_start);
			$repeat_end1 = date('c', $repeat_end);
			$event1 = array(
				'id' => $day,
				'title' => 'Closed',
				'start' => $repeat_start1,
				'end' => $repeat_end1,
				'className' => 'colorblack',
				'editable' => false,
				'reason' => 'Closed',
				'status' => 'Closed'
			);
			$events[] = $event1;
			$repeat_start = $repeat_start + 604800;
			$repeat_end = $repeat_end + 604800;
		}
		return $events;
	}
	
	protected function add_closed2($day, $maxTime, $day2, $events, $start, $end)
	{
		$repeat_start = strtotime('this ' . $day . ' ' . $day2, $start); 
		$repeat_end = strtotime('this ' . $day . ' ' . $maxTime, $start);
		while ($repeat_start <= $end) {
			$repeat_start1 = date('c', $repeat_start);
			$repeat_end1 = date('c', $repeat_end);
			$event1 = array(
				'id' => $day,
				'title' => 'Closed',
				'start' => $repeat_start1,
				'end' => $repeat_end1,
				'className' => 'colorblack',
				'editable' => false,
				'reason' => 'Closed',
				'status' => 'Closed'
			);
			$events[] = $event1;
			$repeat_start = $repeat_start + 604800;
			$repeat_end = $repeat_end + 604800;
		}
		return $events;
	}
	
	protected function add_closed3($day, $minTime, $maxTime, $events, $start, $end)
	{
		$repeat_start = strtotime('this ' . $day . ' ' . $minTime, $start); 
		$repeat_end = strtotime('this ' . $day . ' ' . $maxTime, $start);
		while ($repeat_start <= $end) {
			$repeat_start1 = date('c', $repeat_start);
			$repeat_end1 = date('c', $repeat_end);
			$event1 = array(
				'id' => $day,
				'title' => 'Closed',
				'start' => $repeat_start1,
				'end' => $repeat_end1,
				'className' => 'colorblack',
				'editable' => false,
				'reason' => 'Closed',
				'status' => 'Closed'
			);
			$events[] = $event1;
			$repeat_start = $repeat_start + 604800;
			$repeat_end = $repeat_end + 604800;
		}
		return $events;
	}
	
	protected function schedule_notification($appt_id)
	{
		$row1 = Schedule::find($appt_id);
		if ($row1->pid != '0') {
			$row = Demographics::find($row1->pid);
			$row2 = Practiceinfo::find(Session::get('practice_id'));
			$row0 = User::find($row1->provider_id);
			$displayname = $row0->displayname;
			$to = $row->reminder_to;
			$phone = $row2->phone;
			$startdate = date("F j, Y, g:i a", $row1->start);
			if ($row1->start < time()) {
				if ($to != '') {
					$data_message['startdate'] = date("F j, Y, g:i a", $row1->start);
					$data_message['displayname'] = $row0->displayname;
					$data_message['phone'] = $row2->phone;
					$data_message['email'] = $row2->email;
					$data_message['additional_message'] = $row2->additional_message;
					if ($row->reminder_method == 'Cellular Phone') {
						$this->send_mail(array('text' => 'emails.remindertext'), $data_message, 'Appointment Reminder', $to, Session::get('practice_id'));
					} else {
						$this->send_mail('emails.reminder', $data_message, 'Appointment Reminder', $to, Session::get('practice_id'));
					}
				}
			}
		}
	}
	
	protected function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true)
	{
		foreach($messageParts as $part) {
			$flattenedParts[$prefix.$index] = $part;
			if(isset($part->parts)) {
				if($part->type == 2) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
				}
				elseif($fullPrefix) {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
				}
				else {
					$flattenedParts = $this->flattenParts($part->parts, $flattenedParts, $prefix);
				}
				unset($flattenedParts[$prefix.$index]->parts);
			}
			$index++;
		}
		return $flattenedParts;
	}
	
	protected function getPart($connection, $messageNumber, $partNumber, $encoding)
	{
		$data = imap_fetchbody($connection, $messageNumber, $partNumber);
		switch($encoding) {
			case 0: return $data; // 7BIT
			case 1: return $data; // 8BIT
			case 2: return $data; // BINARY
			case 3: return base64_decode($data); // BASE64
			case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
			case 5: return $data; // OTHER
		}
	}
	
	protected function getFilenameFromPart($part)
	{
		$filename = '';
		if($part->ifdparameters) {
			foreach($part->dparameters as $object) {
				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;
				}
			}
		}
		if(!$filename && $part->ifparameters) {
			foreach($part->parameters as $object) {
				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}
		return $filename;
	}
	
	protected function printimage($eid)
	{
		$query = DB::table('billing')->where('eid', '=', $eid)->get();
		$new_template = '';
		foreach ($query as $result) {
			$template = file_get_contents(__DIR__.'/../../public/billing.txt');
			$search = array(
				"^Bx11c**********************^",            
				"^Pay^",                                    
				"^InsuranceAddress***********^",           
				"^InsuranceAddress2**********^",           
				"^Bx1****************************************^",
				"^Bx1a***********************^",
				"^Bx2***********************^",
				"^Bx3a****^",
				"^Bx3b^",
				"^Bx4************************^",
				"^Bx5a**********************^",
				"^Bx6**********^",
				"^Bx7a***********************^",
				"^Bx5b******************^",
				"^5^",
				"^Bx7b*****************^",
				"^7*^",  
				"^Bx5d******^",
				"^Bx5e********^",
				"^Bx8b*******^",
				"^Bx7d******^",
				"^Bx7e********^", 
				"^Bx9***********************^",
				"^Bx10*************^",
				"^Bx11***********************^",
				"^Bx9a**********************^",
				"^Bx10a^",
				"^Bx11a***^",
				"^Bx11aa^",    
				"^Bx10b^",
				"^b^",
				"^Bx11b**********************^",
				"^Bx9c**********************^",
				"^Bx10c^",
				"^Bx9d**********************^",
				"^Bx10d************^",
				"^B11d^",
				"^Bx17********************^",
				"^Bx17a**********^",
				"@",
				"^Bx21a*^",
				"^Bx21b*^",
				"^Bx21c*^",
				"^Bx21d*^",
				"^Bx21d*^",
				"^Bx21e*^",
				"^Bx21f*^",
				"^Bx21g*^",
				"^Bx21h*^",
				"^Bx21i*^",
				"^Bx21j*^",
				"^Bx21k*^",
				"^Bx21l*^",
				"^DOS1F*^",
				"^DOS1T*^",
				"^a1*^",
				"^CT1*^",
				"^d1*******^",
				"^e1^",
				"^f1****^",
				"^g1*^",
				"^j1*******^",
				"^DOS2F*^",
				"^DOS2T*^",
				"^a2*^",
				"^CT2*^",
				"^d2*******^",
				"^e2^",
				"^f2****^",
				"^g2*^",
				"^j2*******^", 
				"^DOS3F*^",
				"^DOS3T*^",
				"^a3*^",
				"^CT3*^",
				"^d3*******^",
				"^e3^",
				"^f3****^",
				"^g3*^",
				"^j3*******^",
				"^DOS4F*^",
				"^DOS4T*^",
				"^a4*^",
				"^CT4*^",
				"^d4*******^",
				"^e4^",
				"^f4****^",
				"^g4*^",
				"^j4*******^",
				"^DOS5F*^",
				"^DOS5T*^",
				"^a5*^",
				"^CT5*^",
				"^d5*******^",
				"^e5^",
				"^f5****^",
				"^g5*^",
				"^j5*******^",
				"^DOS6F*^",
				"^DOS6T*^",
				"^a6*^",
				"^CT6*^",
				"^d6*******^",
				"^e6^",
				"^f6****^",
				"^g6*^",
				"^j6*******^",
				"^Bx25*********^",
				"^Bx26********^",
				"^Bx27^",
				"^Bx28***^",
				"^Bx29**^",
				"^Bx30**^",
				"^Bx33a******^",
				"^Bx32a*******************^",
				"^Bx33b**********************^",
				"^Bx32b*******************^",
				"^Bx33c**********************^",
				"^Bx32c*******************^",
				"^Bx33d**********************^",
				"^Bx31***************^",
				"^Bx32d***^",
				"^Bx33e***^"
			);
			$replace = array(
				$result->bill_Box11C,
				$result->bill_payor_id,
				$result->bill_ins_add1,
				$result->bill_ins_add2,
				$result->bill_Box1,
				$result->bill_Box1A,
				$result->bill_Box2,
				$result->bill_Box3A,
				$result->bill_Box3B,
				$result->bill_Box4,
				$result->bill_Box5A,
				$result->bill_Box6,
				$result->bill_Box7A,
				$result->bill_Box5B,
				$result->bill_Box5C,
				$result->bill_Box7B,
				$result->bill_Box7C,
				$result->bill_Box5D,
				$result->bill_Box5E,
				$result->bill_Box8B,
				$result->bill_Box7D,
				$result->bill_Box7E,
				$result->bill_Box9,
				$result->bill_Box10,
				$result->bill_Box11,
				$result->bill_Box9A,
				$result->bill_Box10A,
				$result->bill_Box11A1,
				$result->bill_Box11A2,
				$result->bill_Box10B1,
				$result->bill_Box10B2,
				$result->bill_Box11B,
				$result->bill_Box9C,
				$result->bill_Box10C,
				$result->bill_Box9D,
				"                   ",
				$result->bill_Box11D,
				$result->bill_Box17,
				$result->bill_Box17A,
				$result->bill_Box21A,
				$result->bill_Box21_1,
				$result->bill_Box21_2,
				$result->bill_Box21_3,
				$result->bill_Box21_4,
				$result->bill_Box21_5,
				$result->bill_Box21_6,
				$result->bill_Box21_7,
				$result->bill_Box21_8,
				$result->bill_Box21_9,
				$result->bill_Box21_10,
				$result->bill_Box21_11,
				$result->bill_Box21_12,
				$result->bill_DOS1F,
				$result->bill_DOS1T,
				$result->bill_Box24B1,
				$result->bill_Box24D1,
				$result->bill_Modifier1,
				$result->bill_Box24E1,
				$result->bill_Box24F1,
				$result->bill_Box24G1,
				$result->bill_Box24J1,
				$result->bill_DOS2F,
				$result->bill_DOS2T,
				$result->bill_Box24B2,
				$result->bill_Box24D2,
				$result->bill_Modifier2,
				$result->bill_Box24E2,
				$result->bill_Box24F2,
				$result->bill_Box24G2,
				$result->bill_Box24J2,
				$result->bill_DOS3F,
				$result->bill_DOS3T,
				$result->bill_Box24B3,
				$result->bill_Box24D3,
				$result->bill_Modifier3,
				$result->bill_Box24E3,
				$result->bill_Box24F3,
				$result->bill_Box24G3,
				$result->bill_Box24J3,
				$result->bill_DOS4F,
				$result->bill_DOS4T,
				$result->bill_Box24B4,
				$result->bill_Box24D4,
				$result->bill_Modifier4,
				$result->bill_Box24E4,
				$result->bill_Box24F4,
				$result->bill_Box24G4,
				$result->bill_Box24J4,
				$result->bill_DOS5F,
				$result->bill_DOS5T,
				$result->bill_Box24B5,
				$result->bill_Box24D5,
				$result->bill_Modifier5,
				$result->bill_Box24E5,
				$result->bill_Box24F5,
				$result->bill_Box24G5,
				$result->bill_Box24J5,
				$result->bill_DOS6F,
				$result->bill_DOS6T,
				$result->bill_Box24B6,
				$result->bill_Box24D6,
				$result->bill_Modifier6,
				$result->bill_Box24E6,
				$result->bill_Box24F6,
				$result->bill_Box24G6,
				$result->bill_Box24J6,
				$result->bill_Box25,
				$result->bill_Box26,
				$result->bill_Box27,
				$result->bill_Box28,
				$result->bill_Box29,
				$result->bill_Box30,
				$result->bill_Box33A,
				$result->bill_Box32A,
				$result->bill_Box33B,
				$result->bill_Box32B,
				$result->bill_Box33C,
				$result->bill_Box32C,
				$result->bill_Box33D,
				$result->bill_Box31,
				$result->bill_Box32D,
				$result->bill_Box33E
			);
			$new_template .= str_replace($search, $replace, $template);
		}
		$data = array(
			'bill_submitted' => 'Done'
		);
		DB::table('encounters')->where('eid', '=', $eid)->update($data);
		$this->audit('Update');
		return $new_template;
	}
	
	protected function hcfa($eid, $flatten)
	{
		$query = DB::table('billing')->where('eid', '=', $eid)->get();
		$input1 = '';
		if ($query) {
			$i = 0;
			$file_root = __DIR__.'/../../public/temp/';
			$file_name = 'hcfa1500_output_' . time();
			$file_path = $file_root . $file_name . '_final.pdf';
			foreach ($query as $pdfinfo) {
				$input = __DIR__.'/../../public/hcfa1500.pdf';
				$output = $file_root . $file_name . $i . '.pdf';
				if (file_exists($output)) {
					unlink($output);
				}
				$data='<?xml version="1.0" encoding="UTF-8"?>'."\n".
					'<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
					'<fields>'."\n";
				foreach($pdfinfo as $field => $val) {
					$data.='<field name="'.$field.'">'."\n";
					if($field == 'bill_DOS1F' || $field == 'bill_DOS1T' || $field == 'bill_DOS2F' || $field == 'bill_DOS2T' || $field == 'bill_DOS3F' || $field == 'bill_DOS3T' || $field == 'bill_DOS4F' || $field == 'bill_DOS4T' || $field == 'bill_DOS5F' || $field == 'bill_DOS5T' || $field == 'bill_DOS6F' || $field == 'bill_DOS6T') {
						$val_array = str_split($val, 2);
						$val_array1 = array($val_array[0], ' ', $val_array[1], ' ', $val_array[2], $val_array[3]);
						$val = implode($val_array1);
					}
					if($field == 'bill_Box3A' || $field == 'bill_Box9B1' || $field == 'bill_Box11A1'){
						$val_array2 = str_split($val, 3);
						$val_array3 = array($val_array2[0], $val_array2[1], '', $val_array2[2], $val_array2[3]);
						$val = implode($val_array3);
					}
					if($field == 'bill_Box24F1' ||$field == 'bill_Box24F2' || $field == 'bill_Box24F3' || $field == 'bill_Box24F4' || $field == 'bill_Box24F5' || $field == 'bill_Box24F6' || $field == 'bill_Box28' || $field == 'bill_Box29' || $field == 'bill_Box30') {
						$val = rtrim($val);
					}
					if(is_array($val)) {
						foreach($val as $opt)
							$data.='<value>'.$opt.'</value>'."\n";
					} else {
						$data.='<value>'.$val.'</value>'."\n";
					}
					$data.='</field>'."\n";
				}
				$data.='<field name="Date">'."\n<value>".date('m/d/Y')."</value>\n</field>\n";
				$data.='<field name="Date2">'."\n<value>".date('m/d/y')."</value>\n</field>\n";
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
				$commandpdf = "pdftk " . $input . " fill_form " . $xfdf_fn . " output " . $output;
				if ($flatten == 'y') {
					$commandpdf .= " flatten";
				}
				$commandpdf1 = escapeshellcmd($commandpdf);
				exec($commandpdf1);
				if ($i > 0) {
					$input1 .= ' ' . $output;
				} else {
					$input1 = $output;
				}
				$i++;
			}
			$commandpdf2 = "pdftk " . $input1 . " cat output " . $file_path;
			$commandpdf3 = escapeshellcmd($commandpdf2);
			$data1 = array(
				'bill_submitted' => 'Done'
			);
			DB::table('encounters')->where('eid', '=', $eid)->update($data1);
			$this->audit('Update');
			exec($commandpdf3);
			$files = explode(" ", $input1);
			foreach ($files as $row1) {
				unlink($row1);
			}
			return $file_path;
		} else {
			return FALSE;
		}
	}
	
	protected function getWeightChart($pid)
	{
		$query = DB::table('vitals')
			->select('weight', 'pedsage')
			->where('pid', '=', $pid)
			->where('weight', '!=', '')
			->orderBy('pedsage', 'asc')
			->get();
		if ($query) {
			$vals = array();
			$i = 0;
			foreach ($query as $row) {
				$row1 = Practiceinfo::find(Session::get('practice_id'));
				if ($row1->weight_unit == 'lbs') {
					$y = $row->weight / 2.20462262185;
				} else {
					$y = $row->weight * 1;
				}
				$x = $row->pedsage * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}

	protected function getHeightChart($pid)
	{
		$query = DB::table('vitals')
			->select('height', 'pedsage')
			->where('pid', '=', $pid)
			->where('height', '!=', '')
			->orderBy('pedsage', 'asc')
			->get();
		if ($query) {
			$vals = array();
			$i = 0;
			foreach ($query as $row) {
				$row1 = Practiceinfo::find(Session::get('practice_id'));
				if ($row1->height_unit == 'in') {
					$y = $row->height * 2.54;
				} else {
					$y = $row->height * 1;
				}
				$x = $row->pedsage * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}
	
	protected function getHCChart($pid)
	{
		$query = DB::table('vitals')
			->select('headcircumference', 'pedsage')
			->where('pid', '=', $pid)
			->where('headcircumference', '!=', '')
			->orderBy('pedsage', 'asc')
			->get();
		if ($query) {
			$vals = array();
			$i = 0;
			foreach ($query as $row) {
				$row1 = Practiceinfo::find(Session::get('practice_id'));
				if ($row1->hc_unit == 'in') {
					$y = $row->headcircumference * 2.54;
				} else {
					$y = $row->headcircumference * 1;
				}
				$x = $row->pedsage * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}
	
	protected function getBMIChart($pid)
	{
		$query = DB::table('vitals')
			->select('BMI', 'pedsage')
			->where('pid', '=', $pid)
			->where('BMI', '!=', '')
			->orderBy('pedsage', 'asc')
			->get();
		if ($query) {
			$vals = array();
			$i = 0;
			foreach ($query as $row) {
				$x = $row->pedsage * 2629743 / 86400;
				$vals[$i][] = $x;
				$vals[$i][] = (float) $row->BMI;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}
	
	protected function getWeightHeightChart($pid)
	{
		$query = DB::table('vitals')
			->select('weight', 'height', 'pedsage')
			->where('pid', '=', $pid)
			->where('weight', '!=', '')
			->where('height', '!=', '')
			->orderBy('pedsage', 'asc')
			->get();
		if ($query) {
			$vals = array();
			$i = 0;
			foreach ($query as $row) {
				$row1 = Practiceinfo::find(Session::get('practice_id'));
				if ($row1->weight_unit == 'lbs') {
					$y = $row->weight / 2.20462262185;
				} else {
					$y = $row->weight * 1;
				}
				if ($row1->height_unit == 'in') {
					$x = $row->height * 2.54;
				} else {
					$x = $row->height * 1;
				}
				$vals[$i][] = $x;
				$vals[$i][] = $y;
				$i++;
			}
			return $vals;
		} else {
			return FALSE;
		}
	}
	
	protected function getSpline($style, $sex)
	{
		$query = DB::table('gc')
			->where('type', '=', $style)
			->where('sex', '=', $sex)
			->get();
		$result = array();
		foreach ($query as $row) {
			$row1 = (array) $row;
			$result[] = $row1;
		}
		return $result;
	}
	
	protected function getLMS($style, $sex, $age)
	{
		$query = DB::table('gc')
			->where('type', '=', $style)
			->where('sex', '=', $sex)
			->where('Age', '=', $age)
			->first();
		$result = (array) $query;
		return $result;
	}
	
	protected function getLMS1($style, $sex, $length)
	{
		$query = DB::table('gc')
			->where('type', '=', $style)
			->where('sex', '=', $sex)
			->where('Length', '=', $length)
			->first();
		$result = (array) $query;
		return $result;
	}
	
	protected function getLMS2($style, $sex, $height)
	{
		$query = DB::table('gc')
			->where('type', '=', $style)
			->where('sex', '=', $sex)
			->where('Height', '=', $height)
			->first();
		$result = (array) $query;
		return $result;
	}
	
	protected function erf($x)
	{
		$pi = 3.1415927; 
		$a = (8*($pi - 3))/(3*$pi*(4 - $pi));
		$x2 = $x * $x;
		$ax2 = $a * $x2;
		$num = (4/$pi) + $ax2;
		$denom = 1 + $ax2;
		$inner = (-$x2)*$num/$denom;
		$erf2 = 1 - exp($inner);
		return sqrt($erf2);
	}
	
	protected function cdf($n)
	{
		if($n < 0) {
			return (1 - $this->erf($n / sqrt(2)))/2; 
		} else {
			return (1 + $this->erf($n / sqrt(2)))/2; 
		}
	}
	
	protected function getImageFile($file)
	{
		$type =  exif_imagetype($file);
		switch($type){
			case 2:
				$img = imagecreatefromjpeg($file);
				break;
			case 1:
				$img = imagecreatefromgif($file);
				break;
			case 3:
				$img = imagecreatefrompng($file);
				break;
			default:
				$img = false;
				break;
		}
		return $img;
	}
	
	protected function getDimensions($width, $height, $frameWidth, $frameHeight)
	{
		//scale the longer side first and the shorter side as per the ratio
		if($width > $height)
		{
			$newWidth = $frameWidth;
			$newHeight = $frameWidth/$width*$height;
		}else{
			$newHeight = $frameHeight;
			$newWidth = $frameHeight/$height*$width;
		}
		return array('scaledWidth' => $newWidth , 'scaledHeight' => $newHeight);
	}
	
	protected function saveImage($img, $finalDestination)
	{
		//get the filetype of the file to be saved to determine the format of the output image
		$type = strtolower(strrchr($finalDestination, '.'));
		switch($type)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($img, $finalDestination, 100);
				}
				break;
			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($img, $finalDestination, 100);
				}
				break;
			case '.png':
				if (imagetypes() & IMG_PNG) {
					imagepng($img, $finalDestination, 0);
				}
				break;
			default:
				break;
		}
		imagedestroy($img);
	}
	protected function rxnorm_search($term, $limit)
	{
		$rxnormapi = new RxNormApi();
		$rxnormapi->output_type = 'json';
		$rxnorm = json_decode($rxnormapi->getApproximateMatch($term,20,1), true);
		$result = array();
		$i = 0;
		if (isset($rxnorm['approximateGroup']['candidate'][0])) {
			if ($limit == true) {
				$result[0]['score'] = $rxnorm['approximateGroup']['candidate'][0]['score'];
				$result[0]['rxcui'] = $rxnorm['approximateGroup']['candidate'][0]['rxcui'];
			} else {
				foreach($rxnorm['approximateGroup']['candidate'] as $item) {
					$result[$i]['score'] = $item['score'];
					$result[$i]['rxcui'] = $item['rxcui'];
					//$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($item['rxcui']), true);
					//$result[$i]['name'] = $rxnorm1['properties']['name'];
					//$rxnorm2 = json_decode($rxnormapi->getRxProperty($item['rxcui'], "AVAILABLE_STRENGTH"), true);
					//if (isset($rxnorm2['propConceptGroup']['propConcept'][0])) {
						//$result[$i]['strength'] = $rxnorm2['propConceptGroup']['propConcept'][0]['propValue'];
					//} else {
						//$result[$i]['strength'] = '';
					//}
					$i++;
				}
			}
		}
		return $result;
	}
	
	protected function rxnorm_name_search($name)
	{
		$rxnormapi = new RxNormApi();
		$rxnormapi->output_type = 'json';
		$rxnorm = json_decode($rxnormapi->getDrugs($name), true);
		$result = array();
		$i = 0;
		if (isset($rxnorm['drugGroup']['conceptGroup'][3]['conceptProperties'][0])) {
			foreach($rxnorm['drugGroup']['conceptGroup'][3]['conceptProperties'] as $item) {
				$result[$i]['rxcui'] = $item['rxcui'];
				$result[$i]['name'] = $item['name'];
				$result[$i]['category'] = 'Generic';
				$i++;
			}
		}
		if (isset($rxnorm['drugGroup']['conceptGroup'][2]['conceptProperties'][0])) {
			foreach($rxnorm['drugGroup']['conceptGroup'][2]['conceptProperties'] as $item1) {
				$result[$i]['rxcui'] = $item1['rxcui'];
				$result[$i]['name'] = $item1['name'];
				$result[$i]['category'] = 'Brand';
				$i++;
			}
		}
		return $result;
			//$rxcui = $rxnormapi->getRxConceptProperties($rxnorm['drugGroup']['conceptGroup'][2]['conceptProperties'][5]['rxcui']);
			
			//$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($rxnorm['idGroup']['rxnormId'][0]), true);
					//$med_rxnorm_code = $rxnorm['idGroup']['rxnormId'][0];
					//$med_name = $rxnorm1['properties']['name'];
			//$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($rxnorm['drugGroup']['conceptGroup'][2]['conceptProperties'][0]['rxcui']), true);
			//$ndc = json_decode($rxnormapi->getNDCs('823938'), true);
			//return $ndc['ndcGroup']['ndcList']['ndc'][0];
		//}
	}
	
	protected function github_all()
	{
		$client = new \Github\Client(
			new \Github\HttpClient\CachedHttpClient(array('cache_dir' => '/tmp/github-api-cache'))
		);
		$client = new \Github\HttpClient\CachedHttpClient();
		$client->setCache(
			new \Github\HttpClient\Cache\FilesystemCache('/tmp/github-api-cache')
		);
		$client = new \Github\Client($client);
		$result = $client->api('repo')->commits()->all('shihjay2', 'nosh-core', array('sha' => 'master'));
		return $result;
	}
	
	protected function github_single($sha)
	{
		$client = new \Github\Client(
			new \Github\HttpClient\CachedHttpClient(array('cache_dir' => '/tmp/github-api-cache'))
		);
		$client = new \Github\HttpClient\CachedHttpClient();
		$client->setCache(
			new \Github\HttpClient\Cache\FilesystemCache('/tmp/github-api-cache')
		);
		$client = new \Github\Client($client);
		$result = $commit = $client->api('repo')->commits()->show('shihjay2', 'nosh-core', $sha);
		return $result;
	}
	
	protected function clinithink($text)
	{
		$url = 'http://clinithink.api.mashery.com/v1/prd/encoding/Document?profileId=3&apiKey=gaaedrzjnyhtga7vc576xqjt';
		$text_array = explode(' ', $text);
		$fields_string = 'text=';
		$fields_string .= implode("+", $text_array);
		rtrim($fields_string, '+');
		$headers = array(
			"X-Originating-Ip: " . $_SERVER['SERVER_ADDR']
		);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 15);
		$result = curl_exec($ch);
		curl_close($ch);
		$result_array = json_decode($result, true);
		$return_array = array();
		$i = 0;
		foreach ($result_array[0]['ChunkingResult']['DetailedChunkList'] as $row) {
			$return_array[$i]['term'] = $row['Term'];
			$return_array[$i]['id'] = $row['ConceptId'];
			$i++;
		}
		return $return_array;
	}
}
