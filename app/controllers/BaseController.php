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

	protected function css_assets()
	{
		$current_version = File::get(__DIR__."/../../.version");
		$basefiles = array(
			'css/main.css',
			'css/jquery.jgrowl.css',
			'css/ui.jqgrid.css',
			'css/fullcalendar.css',
			'css/main.css',
			'css/jquery.timepicker.css',
			'css/jquery.signaturepad.css',
			'css/searchFilter.css',
			'css/ui.multiselect.css',
			'css/chosen.css',
			'css/jquery.Jcrop.css',
			'css/jquery.realperson.css',
			'css/tagit.css',
			'css/wColorPicker.min.css',
			'css/wPaint.min.css',
			'css/jqueryui-editable.css',
			'css/timecube.jquery.css',
			'css/toastr.min.css'
		);
		$image_files = array(
			'chosen-sprite.png',
			'chosen-sprite@2x.png',
			'Jcrop.gif',
			'pen.png'
		);
		$response = '';
		if (App::isLocal()) {
			foreach ($basefiles as $basefile) {
				$response .= HTML::style($basefile);
			}
		} else {
			$cssfilename =  '/temp/' . $current_version . '.css';
			$cssfile = __DIR__.'/../../public' . $cssfilename;
			$str = '';
			foreach ($basefiles as $basefile) {
				$basefile1 = __DIR__.'/../../public/' . $basefile;
				$str .= File::get($basefile1);
			}
			foreach ($image_files as $image) {
				$new_image = '../css/' . $image;
				$str = str_replace($image, $new_image, $str);
			}
			File::put($cssfile,$str);
			$response .= HTML::style($cssfilename);
		}
		return $response;
	}

	protected function js_assets($type,$mobile=false)
	{
		$current_version = File::get(__DIR__."/../../.version");
		if ($mobile == false) {
			$basejsfiles = array(
				'/js/jquery.maskedinput.min.js',
				'/js/jquery.jgrowl.js',
				'/js/jquery.selectboxes.js',
				'/js/jquery-migrate-1.2.1.js',
				'/js/jquery.ajaxQueue.js',
				'/js/i18n/grid.locale-en.js',
				'/js/jquery.jqGrid.min.js',
				'/js/jquery.timepicker.min.js',
				'/js/fullcalendar.js',
				'/js/jquery-idleTimeout.js',
				'/js/jquery.iframer.js',
				'/js/jquery.serializeObject.js',
				'/js/jquery.signaturepad.min.js',
				'/js/json2.min.js',
				'/js/highcharts.js',
				'/js/exporting.js',
				'/js/jquery.dform-1.1.0.js',
				'/js/grid.addons.js',
				'/js/grid.postext.js',
				'/js/grid.setcolumns.js',
				'/js/jquery.contextmenu.js',
				'/js/jquery.searchFilter.js',
				'/js/jquery.tablednd.js',
				'/js/jquery.chosen.min.js',
				'/js/ui.multiselect.js',
				'/js/jquery.themeswitcher.js',
				'/js/jquery.color.js',
				'/js/jquery.Jcrop.min.js',
				'/js/jquery.realperson.js',
				'/js/tagit-themeroller.js',
				'/js/jquery.jstree.js',
				'/js/jquery.populate.js',
				'/js/jquery.ocupload.js',
				'/js/jstz-1.0.4.min.js',
				'/js/jquery.cookie.js',
				'/js/bluebutton.js',
				'/js/wColorPicker.min.js',
				'/js/wPaint.min.js',
				'/js/plugins/main/wPaint.menu.main.min.js',
				'/js/plugins/text/wPaint.menu.text.min.js',
				'/js/plugins/shapes/wPaint.menu.main.shapes.min.js',
				'/js/plugins/file/wPaint.menu.main.file.min.js',
				'/js/jqueryui-editable.min.js',
				'/js/jquery.touchswipe.min.js',
				'/js/jquery.ui.touch-punch.min.js',
				'/js/jquery-textrange.js',
				'/js/jquery.autosize.min.js',
				'/js/timecube.jquery.js',
				'/js/toastr.min.js',
				'/js/main.js',
			);
		} else {
			$basejsfiles = array(
				'/js/jquery.maskedinput.min.js',
				'/js/jquery-idleTimeout.js',
				'/js/jstz-1.0.4.min.js',
				'/js/jquery.cookie.js',
				'/js/bluebutton.js',
				'/js/jquery-textrange.js',
				'/js/toastr.min.js',
				'/js/nativedroid.js',
				'/js/jquery.timepicker.min.js',
				'/js/jquery.selectboxes.js',
				'/js/jquery-textrange.js',
				'/js/mobile.js'
			);
		}
		if(Session::get('group_id') == '1') {
			$homejsfiles = array(
				'/js/searchbar.js',
				'/js/dashboard.js',
				'/js/setup.js',
				'/js/users.js',
				'/js/extensions.js',
				'/js/schedule_admin.js',
				'/js/update.js',
				'/js/logs.js',
				'/js/schedule.js'
			);
		}
		if(Session::get('group_id') == '2' || Session::get('group_id') == '3' || Session::get('group_id') == '4') {
			$homejsfiles = array(
				'/js/searchbar.js',
				'/js/dashboard.js',
				'/js/demographics.js',
				'/js/options.js',
				'/js/messaging.js',
				'/js/schedule.js',
				'/js/billing.js',
				'/js/financial.js',
				'/js/office.js'
			);
			if (Session::get('patient_centric') == 'yp' && Session::get('group_id') == '2') {
				$homejsfiles[] = '/js/setup.js';
			}
		}
		if(Session::get('group_id') == '100') {
			$homejsfiles = array(
				'/js/dashboard.js',
				'/js/demographics.js',
				'/js/messaging.js',
				'/js/schedule.js',
				'/js/issues.js',
				'/js/encounters.js',
				'/js/t_messages.js',
				'/js/medications.js',
				'/js/supplements.js',
				'/js/allergies.js',
				'/js/immunizations.js',
				'/js/documents.js',
				'/js/forms.js',
				'/js/graph.js'
			);
		}
		$chartjsfiles = array(
			'/js/chart.js',
			'/js/demographics.js',
			'/js/searchbar.js',
			'/js/options.js',
			'/js/menu.js',
			'/js/issues.js',
			'/js/encounters.js',
			'/js/medications.js',
			'/js/supplements.js',
			'/js/allergies.js',
			'/js/alerts.js',
			'/js/immunizations.js',
			'/js/print.js',
			'/js/billing.js',
			'/js/documents.js',
			'/js/t_messages.js',
			'/js/lab.js',
			'/js/rad.js',
			'/js/cp.js',
			'/js/ref.js',
			'/js/messaging.js',
			'/js/schedule.js',
			'/js/financial.js',
			'/js/office.js',
			'/js/graph.js',
			'/js/image.js'
		);
		$response = '';
		if (App::isLocal()) {
			foreach ($basejsfiles as $basejsfile) {
				$response .= HTML::script($basejsfile);
			}
			if ($type == 'home') {
				foreach ($homejsfiles as $homejsfile) {
					$response .= HTML::script($homejsfile);
				}
			}
			if ($type == 'chart') {
				foreach ($chartjsfiles as $chartjsfile) {
					$response .= HTML::script($chartjsfile);
				}
			}
		} else {
			$jsfilename =  '/temp/' . $current_version . '_' . $type . '_' . time() . '.js';
			$jsfile = __DIR__.'/../../public' . $jsfilename;
			$str = '';
			foreach ($basejsfiles as $basejsfile) {
				$basejsfile1 = __DIR__.'/../../public/' . $basejsfile;
				$str .= File::get($basejsfile1);
			}
			if ($type == 'home') {
				foreach ($homejsfiles as $homejsfile) {
					$homejsfile1 = __DIR__.'/../../public/' . $homejsfile;
					$str .= File::get($homejsfile1);
				}
			}
			if ($type == 'chart') {
				foreach ($chartjsfiles as $chartjsfile) {
					$chartjsfile1 = __DIR__.'/../../public/' . $chartjsfile;
					$str .= File::get($chartjsfile1);
				}
			}
			File::put($jsfile,$str);
			$response .= HTML::script($jsfilename);
		}
		return $response;
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

	protected function encounters_view($eid, $pid, $practice_id, $modal=false, $addendum=false, $mobile=false)
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
		if ($data['patientInfo']->sex == 'u') {
			$data['gender'] = 'Undifferentiated';
		}
		$data['encounter_cc'] = nl2br($encounterInfo->encounter_cc);
		$practiceInfo = Practiceinfo::find($practice_id);
		$hpiInfo = Hpi::find($eid);
		if ($hpiInfo) {
			if (!is_null($hpiInfo->hpi) && $hpiInfo->hpi != '') {
				$data['hpi'] = '<br><h4>History of Present Illness:</h4><p class="view">';
				$data['hpi'] .= nl2br($hpiInfo->hpi);
				$data['hpi'] .= '</p>';
			}
			if (!is_null($hpiInfo->situation) && $hpiInfo->situation != '') {
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
			if ($rosInfo->ros_psych1 != '') {
				$data['ros'] .= '<strong>Depression: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych1);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych2 != '') {
				$data['ros'] .= '<strong>Anxiety: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych2);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych3 != '') {
				$data['ros'] .= '<strong>Bipolar: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych3);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych4 != '') {
				$data['ros'] .= '<strong>Mood Disorders: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych4);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych5 != '') {
				$data['ros'] .= '<strong>ADHD: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych5);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych6 != '') {
				$data['ros'] .= '<strong>PTSD: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych6);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych7 != '') {
				$data['ros'] .= '<strong>Substance Related Disorder: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych7);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych8 != '') {
				$data['ros'] .= '<strong>Obsessive Compulsive Disorder: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych8);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych9 != '') {
				$data['ros'] .= '<strong>Social Anxiety Disorder: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych9);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych10 != '') {
				$data['ros'] .= '<strong>Autistic Disorder: </strong>';
				$data['ros'] .= nl2br($rosInfo->ros_psych10);
				$data['ros'] .= '<br /><br />';
			}
			if ($rosInfo->ros_psych11 != '') {
				$data['ros'] .= "<strong>Asperger's Disorder: </strong>";
				$data['ros'] .= nl2br($rosInfo->ros_psych11);
				$data['ros'] .= '<br /><br />';
			}
			$data['ros'] .= '</p>';
		} else {
			$data['ros'] = '';
		}
		$ohInfo = DB::table('other_history')->where('eid', '=', $eid)->first();
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
			if ($ohInfo->oh_psychosocial != '') {
				$data['oh'] .= '<strong>Psychosocial: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_psychosocial);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_developmental != '') {
				$data['oh'] .= '<strong>Developmental: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_developmental);
				$data['oh'] .= '<br /><br />';
			}
			if ($ohInfo->oh_medtrials != '') {
				$data['oh'] .= '<strong>Past Medication Trials: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_medtrials);
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
			if ($ohInfo->oh_results != '') {
				$data['oh'] .= '<strong>Reviewed Results: </strong>';
				$data['oh'] .= nl2br($ohInfo->oh_results);
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
				$data['pe'] .= '<strong>General: </strong>';
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
			if ($peInfo->pe_constitutional1 != '') {
				$data['pe'] .= '<strong>Constitutional: </strong>';
				$data['pe'] .= nl2br($peInfo->pe_constitutional1);
				$data['pe'] .= '<br /><br />';
			}
			if ($peInfo->pe_mental1 != '') {
				$data['pe'] .= '<strong>Mental Status Examination: </strong>';
				$data['pe'] .= nl2br($peInfo->pe_mental1);
				$data['pe'] .= '<br /><br />';
			}
			$data['pe'] .= '</p>';
		} else {
			$data['pe'] = '';
		}
		$imagesInfo = DB::table('image')->where('eid', '=', $eid)->get();
		$html = '';
		if ($imagesInfo) {
			$data['images'] = '<br><h4>Images:</h4><p class="view">';
			$k = 0;
			foreach ($imagesInfo as $imagesInfo_row) {
				$directory = $practiceInfo->documents_dir . $pid . "/";
				$new_directory = __DIR__.'/../../public/temp/';
				$new_directory1 = '/temp/';
				$file_path = str_replace($directory, $new_directory ,$imagesInfo_row->image_location);
				$file_path1 = str_replace($directory, $new_directory1 ,$imagesInfo_row->image_location);
				copy($imagesInfo_row->image_location, $file_path);
				if ($k != 0) {
					$data['images'] .= '<br><br>';
				}
				$data['images'] .= HTML::image($file_path1, 'Image', array('border' => '0'));
				if ($imagesInfo_row->image_description != '') {
					$data['images'] .= '<br>' . $imagesInfo_row->image_description . '<br>';
				}
				$k++;
			}
		} else {
			$data['images'] = '';
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
				if ($encounterInfo->encounter_template == 'standardmtm') {
					$data['assessment'] .= '<strong>SOAP Note: </strong>';
				} else {
					$data['assessment'] .= '<strong>Additional Diagnoses: </strong>';
				}
				$data['assessment'] .= nl2br($assessmentInfo->assessment_other);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_ddx != '') {
				if ($encounterInfo->encounter_template == 'standardmtm') {
					$data['assessment'] .= '<strong>MAP2: </strong>';
				} else {
					$data['assessment'] .= '<strong>Differential Diagnoses Considered: </strong>';
				}
				$data['assessment'] .= nl2br($assessmentInfo->assessment_ddx);
				$data['assessment'] .= '<br /><br />';
			}
			if ($assessmentInfo->assessment_notes != '') {
				if ($encounterInfo->encounter_template == 'standardmtm') {
					$data['assessment'] .= '<strong>Pharmacist Note: </strong>';
				} else {
					$data['assessment'] .= '<strong>Assessment Discussion: </strong>';
				}
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
				if ($address_row1) {
					$orders_displayname = $address_row1->displayname;
				} else {
					$orders_displayname = 'Unknown';
				}
				if ($ordersInfo->orders_labs != '') {
					$orders_lab_array[] = 'Orders sent to ' . $orders_displayname . ': '. nl2br($ordersInfo->orders_labs) . '<br />';
				}
				if ($ordersInfo->orders_radiology != '') {
					$orders_radiology_array[] = 'Orders sent to ' . $orders_displayname . ': '. nl2br($ordersInfo->orders_radiology) . '<br />';
				}
				if ($ordersInfo->orders_cp != '') {
					$orders_cp_array[] = 'Orders sent to ' . $orders_displayname . ': '. nl2br($ordersInfo->orders_cp) . '<br />';
				}
				if ($ordersInfo->orders_referrals != '') {
					$orders_referrals_array[] = 'Referral sent to ' . $orders_displayname . ': '. nl2br($ordersInfo->orders_referrals) . '<br />';
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
			if ($planInfo->goals != '') {
				$data['plan'] .= '<strong>Goals/Measures: </strong>';
				$data['plan'] .=nl2br($planInfo->goals);
				$data['plan'] .= '<br /><br />';
			}
			if ($planInfo->tp != '') {
				$data['plan'] .= '<strong>Treatment Plan Notes: </strong>';
				$data['plan'] .= nl2br($planInfo->tp);
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
			if ($mobile == true) {
				$data['mobile'] = true;
			} else {
				$data['mobile'] = false;
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
		$body .= "<table style='table-layout:fixed;width:800px'><tr><th style='width:100px'>Date</th><th style='width:200px'>Test</th><th style='width:300px'>Result</th><th style='width:50px'>Units</th><th style='width:100px'>Range</th><th style='width:50px'>Flags</th></tr>";
		foreach ($results as $results_row1) {
			$body .= "<tr><td>" . $results_row1['test_datetime'] . "</td><td>" . $results_row1['test_name'] . "</td><td>" . $results_row1['test_result'] . "</td><td>" . $results_row1['test_units'] . "</td><td>" . $results_row1['test_reference'] . "</td><td>" . $results_row1['test_flags'] . "</td></tr>";
			if (isset($results_row1['test_from'])) {
				$from = $results_row1['test_from'];
			} else {
				$from = '';
			}
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
				$sequence = '';
				if ($row2->imm_sequence == '1') {
					$sequence = ', first,';
				}
				if ($row2->imm_sequence == '2') {
					$sequence = ', second,';
				}
				if ($row2->imm_sequence == '3') {
					$sequence = ', third,';
				}
				if ($row2->imm_sequence == '4') {
					$sequence = ', fourth,';
				}
				if ($row2->imm_sequence == '5') {
					$sequence = ', fifth,';
				}
				$body .= '<li>' . $row2->imm_immunization . $sequence . ' given on ' . date('F jS, Y', $this->human_to_unix($row2->imm_date)) . '</li>';
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
		} elseif ($data['patientInfo']->sex == 'f') {
			$data['sex'] = 'Female';
		} else {
			$data['sex'] = 'Undifferentiated';
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
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$search_data['patient_centric'] = $practice->patient_centric;
		if (Session::get('pid')) {
			$patient = Demographics::find(Session::get('pid'));
			$search_data['pt'] = $patient->firstname . ' ' . $patient->lastname;
		} else {
			if (Session::get('group_id') == '2' && Session::get('patient_centric') == 'yp') {
				$this->setpatient('1');
				$patient = Demographics::find('1');
				$search_data['pt'] = $patient->firstname . ' ' . $patient->lastname;
			} else {
				$search_data['pt'] = '';
			}
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
		$row2 = Schedule::where('pid', '=', Session::get('pid'))->where('start', '>', time())->first();
		if (isset($row2->start)) {
			$menu_data['nextvisit'] = '<br>' . date('F jS, Y, g:i A', $row2->start);
		} else {
			$menu_data['nextvisit'] = 'None.';
		}
		$row3 = Encounters::where('pid', '=', Session::get('pid'))
			->where('eid', '!=', '')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('encounter_template', '=', 'standardpsych')
			->where('addendum', '=', 'n')
			->orderBy('eid', 'desc')
			->first();
		if ($row3) {
			$psych_date = strtotime($row3->encounter_DOS);
			$new_psych_date = $psych_date + 31556926;
			$psych_query = Encounters::where('pid', '=', Session::get('pid'))
				->where('eid', '!=', '')
				->where('practice_id', '=', Session::get('practice_id'))
				->where('addendum', '=', 'n')
				->where(function($query_array1) {
					$query_array1->where('encounter_template', '=', 'standardpsych')
					->orWhere('encounter_template', '=', 'standardpsych1');
				})
				->orderBy('eid', 'desc')
				->get();
			$tp_date = '';
			if ($psych_query) {
				$i = 0;
				$psych_comp = '';
				$psych_comp1 = '';
				$tp_eid = '';
				foreach($psych_query as $psych_row) {
					$planInfo = Plan::find($psych_row->eid);
					if ($planInfo) {
						if ($i == 0) {
							$psych_comp = $planInfo->goals;
							$psych_comp .= $planInfo->tp;
							$tp_eid = $psych_row->eid;
							$tp_eid1 = $tp_eid;
						} else {
							$psych_comp1 = $planInfo->goals;
							$psych_comp1 .= $planInfo->tp;
							$tp_eid1 = $psych_row->eid;
						}
						if ($psych_comp1 != $psych_comp && $i != 0) {
							$tp = DB::table('encounters')->where('eid', '=', $tp_eid)->first();
							$tp_date = '<strong>Most recent TP adjustment:</strong> ' .  date('F jS, Y', strtotime($tp->encounter_DOS)) . '<br>';
							break;
						} else {
							$i++;
							$tp_eid = $tp_eid1;
						}
					}
				}
			}
			$menu_data['psych'] = '<strong>Last Annual Psychiatric Eval:</strong> ' .  date('F jS, Y', $psych_date) . '<br><strong>Next Annual Psychiatric Eval Due:</strong> ' . date('F jS, Y', $new_psych_date) . '<br>' . $tp_date;
		} else {
			$menu_data['psych'] = '';
		}
		$menu_data['supplements'] = $row->supplements_menu_item;
		$menu_data['immunizations'] = $row->immunizations_menu_item;
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
				break;
			case 8:
				$hash = 5381;
				$value = strtoupper($value);
				for($i = 0; $i < strlen($value); $i++) {
					$hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
				}
				break;
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
		if ($row->sex == 'u') {
			$gender = 'individual';
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
		} elseif ($result1->insurance_insu_gender == 'f') {
			$bill_Box11A2 = "       X";
			$bill_Box11A2P = 'F';
		} else {
			$bill_Box11A2 = "        ";
			$bill_Box11A2P = 'U';
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
			} elseif ($result3->insurance_insu_gender == 'f') {
				$bill_Box9B2 = "      X";
				$bill_Box9B2P = 'F';
			} else {
				$bill_Box9B2 = "       ";
				$bill_Box9B2P = 'U';
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
		} elseif ($row->sex == 'f') {
			$bill_Box3B = "     X";
			$bill_Box3BP = 'F';
		} else {
			$bill_Box3B = "      ";
			$bill_Box3BP = 'U';
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
		$bill_Box21A = $practiceInfo->icd;
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
		$bill_Box26 = $this->string_format($pid . '_' . $eid, 14);
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
				'bill_Box26' 				=> $bill_Box26,		//pid_eid
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
		$query = DB::table('billing_core')->where('cpt', '=', $cpt)->where('eid', '=', $eid)->first();
		if (!$query) {
			$result = DB::table('cpt_relate')->where('cpt', '=', $cpt)->where('practice_id', '=', $practice_id)->first();
			if ($result) {
				if ($result->cpt_charge != '') {
					$cpt_charge = $result->cpt_charge;
					$unit = $result->unit;
				} else {
					$cpt_charge = '0';
					$unit = '1';
				}
				$data = array(
					'cpt' => $cpt,
					'cpt_charge' => $cpt_charge,
					'eid' => $eid,
					'pid' => $pid,
					'dos_f' => $dos2,
					'dos_t' => $dos2,
					'payment' => '0',
					'icd_pointer' => $icd_pointer,
					'unit' => $unit,
					'billing_group' => '1',
					'modifier' => '',
					'practice_id' => $practice_id
				);
				DB::table('billing_core')->insert($data);
				$this->audit('Add');
			}
		}
	}

	protected function add_mtm_alert($pid, $type)
	{
		$practice_id = Session::get('practice_id');
		if ($type == 'issues') {
			$query = DB::table('issues')->where('pid', '=', $pid)->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->first();
		}
		if ($type == 'medications') {
			$query = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		}
		if($query) {
			$query1 = DB::table('alerts')
				->where('pid', '=', $pid)
				->where('alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alert_reason_not_complete', '=', '')
				->where('alert', '=', 'Medication Therapy Management')
				->where('practice_id', '=', $practice_id)
				->first();
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
			if(file_exists($cover_filename)) {
				unlink($cover_filename);
			}
			$cover_html = $this->page_intro('Cover Page', Session::get('practice_id'))->render();
			$cover_html .= $this->page_coverpage($job_id, $totalpages, $faxrecipients, date("M d, Y, h:i", time()))->render();
			$this->generate_pdf($cover_html, $cover_filename, 'footerpdf');
			while(!file_exists($cover_filename)) {
				sleep(2);
			}
		}
		if ($practice_row->fax_type != 'phaxio') {
			$config = array(
				'driver' => 'smtp',
				'host' => $practice_row->fax_email_smtp,
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
		} else {
			$phaxio_files_array = array();
			if ($fax_data->faxcoverpage == 'yes') {
				$phaxio_files_array[] = $cover_filename;
			}
			foreach ($pagesInfo as $phaxio_file) {
				$phaxio_files_array[] = $phaxio_file->file;
			}
			$phaxio = new Phaxio($practice_row->phaxio_api_key, $practice_row->phaxio_api_secret);
			$phaxio_result = $phaxio->sendFax($faxnumber_array, $phaxio_files_array);
			$phaxio_result_array = json_decode($phaxio_result, true);
			$fax_update_data = array(
				'sentdate' => date('Y-m-d'),
				'ready_to_send' => '1',
				'senddate' => $senddate,
				'faxdraft' => '0',
				'attempts' => '0',
				'success' => '0'
			);
			if ($phaxio_result_array['success'] == true) {
				$fax_update_data['success'] = '2';
				$fax_update_data['command'] = $phaxio_result_array['faxId'];
			}
		}
		DB::table('sendfax')->where('job_id', '=', $job_id)->update($fax_update_data);
		$this->audit('Update');
		Session::forget('job_id');
		return 'Fax Job ' . $job_id . ' Sent';
	}

	protected function send_mail_old($template, $data_message, $subject, $to, $practice_id)
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

	protected function send_mail($template, $data_message, $subject, $to, $practice_id)
	{
		if ($this->googleoauth_refresh($practice_id)) {
			$practice = Practiceinfo::find($practice_id);
			$config = array(
				'driver' => 'smtp',
				'host' => 'smtp.gmail.com',
				'port' => 465,
				'from' => array('address' => null, 'name' => null),
				'encryption' => 'ssl',
				'username' => $practice->smtp_user,
				'password' => $practice->smtp_pass, //access token now
				'sendmail' => '/usr/sbin/sendmail -bs',
				'pretend' => false
			);
			Config::set('mail',$config);
			extract(Config::get('mail'));
			$transport = Swift_SmtpTransport::newInstance($host, $port, 'ssl');
			$transport->setAuthMode('XOAUTH2');
			if (isset($encryption)) $transport->setEncryption($encryption);
			if (isset($username)) {
				$transport->setUsername($username);
				$transport->setPassword($password);
			}
			Mail::setSwiftMailer(new Swift_Mailer($transport));
			Mail::send($template, $data_message, function($message) use ($to, $practice, $subject) {
				$message->to($to)
					->from($practice->email, $practice->practice_name)
					->subject($subject);
			});
			return "E-mail sent.";
		} else {
			return "No refresh token available to create access token!";
		}
	}

	protected function googleoauth_refresh($practice_id)
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', $practice_id)->first();
		if ($practice->google_refresh_token != '') {
			$file = File::get(__DIR__."/../../.google");
			$file_arr = json_decode($file, true);
			$client_id = $file_arr['web']['client_id'];
			$client_secret = $file_arr['web']['client_secret'];
			$google = new Google_Client();
			$google->setClientID($client_id);
			$google->setClientSecret($client_secret);
			$google->refreshToken($practice->google_refresh_token);
			$credentials = $google->getAccessToken();
			$result = json_decode($credentials, true);
			$data['smtp_pass'] = $result['access_token'];
			DB::table('practiceinfo')->where('practice_id', '=', $practice_id)->update($data);
			return true;
		} else {
			return false;
		}
	}

	protected function generate_ccda($hippa_id='',$pid='')
	{
		$ccda = file_get_contents(__DIR__.'/../../public/ccda.xml');
		$practice_info = Practiceinfo::find(Session::get('practice_id'));
		$ccda = str_replace('?practice_name?', $practice_info->practice_name, $ccda);
		$date_format = "YmdHisO";
		$ccda = str_replace('?effectiveTime?', date($date_format), $ccda);
		$ccda_name = time() . '_ccda.xml';
		if ($pid == '') {
			$pid = Session::get('pid');
		}
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
		} elseif ($demographics->sex == 'm') {
			$gender = 'M';
			$gender_full = 'Male';
		} else {
			$gender = 'U';
			$gender_full = 'Undifferentiated Gender';
		}
		$ccda = str_replace('?gender?', $gender, $ccda);
		$ccda = str_replace('?gender_full?', $gender_full, $ccda);
		$ccda = str_replace('?dob?', date('Ymd', $this->human_to_unix($demographics->DOB)), $ccda);
		$marital_code = "U";
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
		$ccda = str_replace('?lang_code?', $demographics->lang_code, $ccda);
		if ($hippa_id != '') {
			$hippa_info = Hippa::find($hippa_id);
			$ccda = str_replace('?hippa_provider?', $hippa_info->hippa_provider, $ccda);
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
		} else {
			$ccda = str_replace('?hippa_provider?', '', $ccda);
			$ccda = str_replace('?encounter_role?', '', $ccda);
			$hippa_role_code = "";
		}
		$ccda = str_replace('?encounter_role_code?', $hippa_role_code, $ccda);
		$recent_encounter_query = DB::table('encounters')->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('encounter_signed', '=', 'Yes')
			->orderBy('encounter_DOS', 'desc')
			->take(1)
			->first();
		if ($recent_encounter_query) {
			$ccda = str_replace('?eid?', $recent_encounter_query->eid, $ccda);
			$encounter_info = Encounters::find($recent_encounter_query->eid);
			$provider_info = User::find($encounter_info->user_id);
			$provider_info1 = Providers::find($encounter_info->user_id);
			if ($provider_info1) {
				$npi = $provider_info1->npi;
			} else {
				$npi = '';
			}
			$ccda = str_replace('?npi?', $npi, $ccda);
			$ccda = str_replace('?provider_title?', $provider_info->title, $ccda);
			$ccda = str_replace('?provider_firstname?', $provider_info->firstname, $ccda);
			$ccda = str_replace('?provider_lastname?', $provider_info->lastname, $ccda);
			$ccda = str_replace('?encounter_dos?', date('Ymd', $this->human_to_unix($encounter_info->encounter_DOS)), $ccda);
			$assessment_info = Assessment::find($recent_encounter_query->eid);
			if ($assessment_info) {
				$recent_icd = $assessment_info->assessment_icd1;
				$assessment_info1 = DB::table('icd9')->where('icd9', '=', $recent_icd)->first();
				if ($assessment_info1) {
					$recent_icd_description = $assessment_info1->icd9_description;
				} else {
					$recent_icd_description = '';
				}
			} else {
				$recent_icd = '';
				$recent_icd_description = '';
			}
			$ccda = str_replace('?icd9?', $recent_icd, $ccda);
			$ccda = str_replace('?icd9_description?', $recent_icd_description, $ccda);
		} else {
			$ccda = str_replace('?eid?', '', $ccda);
			$ccda = str_replace('?npi?', '', $ccda);
			$ccda = str_replace('?provider_title?', '', $ccda);
			$ccda = str_replace('?provider_firstname?', '', $ccda);
			$ccda = str_replace('?provider_lastname?', '', $ccda);
			$ccda = str_replace('?encounter_dos?', '', $ccda);
			$ccda = str_replace('?icd9?', '', $ccda);
			$ccda = str_replace('?icd9_description?', '', $ccda);
		}
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
		if ($encounters_query) {
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
				if ($billing) {
					$cpt_query = DB::table('cpt_relate')->where('cpt', '=', $billing->cpt)->first();
					if ($cpt_query) {
						$cpt_result = DB::table('cpt_relate')->where('cpt', '=', $billing->cpt)->first();
					} else {
						$cpt_result = DB::table('cpt')->where('cpt', '=', $billing->cpt)->first();
					}
					$encounter_code = $billing->cpt;
					if ($cpt_result) {
						$cpt_description = $cpt_result->cpt_description;
					} else {
						$cpt_description = '';
					}
				} else {
					$encounter_code = '';
					$cpt_description = '';
				}
				$provider_info2 = User::find($encounters_row->user_id);
				if ($provider_info2) {
					$provider_firstname = $provider_info2->firstname;
					$provider_lastname = $provider_info2->lastname;
					$provider_title = $provider_info2->title;
				} else {
					$provider_firstname = '';
					$provider_lastname = '';
					$provider_title = '';
				}
				$encounters_file = str_replace('?encounter_cc?', $encounters_row->encounter_cc, $encounters_file);
				$encounters_file = str_replace('?encounter_number?', $encounters_row->eid, $encounters_file);
				$encounters_file = str_replace('?encounter_code?', $encounter_code, $encounters_file);
				$encounters_file = str_replace('?encounter_code_desc?', $cpt_description, $encounters_file);
				$encounters_file = str_replace('?encounter_provider?', $encounters_row->encounter_provider, $encounters_file);
				$encounters_file = str_replace('?encounter_dos1?', date('m-d-Y', $this->human_to_unix($encounters_row->encounter_DOS)), $encounters_file);
				$encounters_file = str_replace('?provider_firstname?', $provider_firstname, $encounters_file);
				$encounters_file = str_replace('?provider_lastname?', $provider_lastname, $encounters_file);
				$encounters_file = str_replace('?provider_title?', $provider_title, $encounters_file);
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
				$encounter_diagnosis = '';
				if ($assessment_info1) {
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
						if ($dx_info) {
							$icd_description = $dx_info->icd9_description;
						} else {
							$icd_description = '';
						}
						$dx_file = str_replace('?icd9_description?', $icd_description, $dx_file);
						$encounter_diagnosis .= $dx_file;
					}
				}
				$encounters_file = str_replace('?encounter_diagnosis?', $encounter_diagnosis, $encounters_file);
				$encounters_file_final .= $encounters_file;
				$e++;
			}
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
				$imm_code = '';
				$imm_code_description = '';
				if ($imm_row->imm_route == "intramuscularly") {
					$imm_code = "C1556154";
					$imm_code_description = "Intramuscular Route of Administration";
				}
				if ($imm_row->imm_route == "subcutaneously") {
					$imm_code = "C1522438";
					$imm_code_description = "Subcutaneous Route of Administration";
				}
				if ($imm_row->imm_route == "intravenously") {
					$imm_code = "C2960476";
					$imm_code_description = "Intravascular Route of Administration";
				}
				if ($imm_row->imm_route == "by mouth") {
					$imm_code = "C1522409";
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
				if ($cvx) {
					$vaccine_name = $cvx->vaccine_name;
				} else {
					$vaccine_name = '';
				}
				$imm_file = str_replace('?vaccine_name?', $vaccine_name, $imm_file);
				$imm_file = str_replace('?imm_manufacturer?', $imm_row->imm_manufacturer, $imm_file);
				$imm_file_final .= $imm_file;
				$j++;
			}
		}
		$ccda = str_replace('?imm_table?', $imm_table, $ccda);
		$ccda = str_replace('?imm_file?', $imm_file_final, $ccda);
		$med_query = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		$sup_query = DB::table('sup_list')->where('pid', '=', $pid)->where('sup_date_inactive', '=', '0000-00-00 00:00:00')->get();
		$med_table = "";
		$med_file_final = "";
		$k = 1;
		if ($med_query) {
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
					if (count($med_dosage_parts) > 1) {
						$med_dosage_unit = $med_dosage_parts[1];
					} else {
						$med_dosage_unit = '';
					}
					$med_code = '';
					$med_code_description = '';
					if ($med_row->rxl_route == "by mouth") {
						$med_code = "C1522409";
						$med_code_description = "Oropharyngeal Route of Administration";
					}
					if ($med_row->rxl_route == "per rectum") {
						$med_code = "C1527425";
						$med_code_description = "Rectal Route of Administration";
					}
					if ($med_row->rxl_route == "transdermal") {
						$med_code = "C0040652";
						$med_code_description = "Transdermal Route of Administration";
					}
					if ($med_row->rxl_route == "subcutaneously") {
						$med_code = "C1522438";
						$med_code_description = "Subcutaneous Route of Administration";
					}
					if ($med_row->rxl_route == "intravenously") {
						$med_code = "C2960476";
						$med_code_description = "Intravascular Route of Administration";
					}
					if ($med_row->rxl_route == "intramuscularly") {
						$med_code = "C1556154";
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
		if ($sup_query) {
			foreach ($sup_query as $sup_row) {
				$med_table .= "<tr>";
				$med_table .= "<td><content ID='med" . $k . "'>" . $sup_row->sup_supplement . ' ' . $sup_row->sup_dosage . ' ' . $sup_row->sup_dosage_unit . "</content></td>";
				if ($sup_row->sup_sig == '') {
					$instructions = $sup_row->sup_instructions;
					$med_dosage = '';
					$med_dosage_unit = '';
					$med_code = '';
					$med_code_description = '';
					$med_period = '';
				} else {
					$instructions = $sup_row->sup_sig . ' ' . $sup_row->sup_route . ' ' . $sup_row->sup_frequency;
					$med_dosage_parts = explode(" ", $sup_row->sup_sig);
					$med_dosage = $med_dosage_parts[0];
					$med_dosage_unit = '';
					if (isset($med_dosage_parts[1])) {
						$med_dosage_unit = $med_dosage_parts[1];
					}
					$med_code = '';
					$med_code_description = '';
					if ($sup_row->sup_route == "by mouth") {
						$med_code = "C1522409";
						$med_code_description = "Oropharyngeal Route of Administration";
					}
					if ($sup_row->sup_route == "per rectum") {
						$med_code = "C1527425";
						$med_code_description = "Rectal Route of Administration";
					}
					if ($sup_row->sup_route == "transdermal") {
						$med_code = "C0040652";
						$med_code_description = "Transdermal Route of Administration";
					}
					if ($sup_row->sup_route == "subcutaneously") {
						$med_code = "C1522438";
						$med_code_description = "Subcutaneous Route of Administration";
					}
					if ($sup_row->sup_route == "intravenously") {
						$med_code = "C2960476";
						$med_code_description = "Intravascular Route of Administration";
					}
					if ($sup_row->sup_route == "intramuscularly") {
						$med_code = "C1556154";
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
					if (in_array($sup_row->sup_frequency, $med_freq_array_1)) {
						$med_period = "24";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_2)) {
						$med_period = "12";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_3)) {
						$med_period = "8";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_4)) {
						$med_period = "6";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_5)) {
						$med_period = "4";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_6)) {
						$med_period = "3";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_7)) {
						$med_period = "2";
					}
					if (in_array($sup_row->sup_frequency, $med_freq_array_8)) {
						$med_period = "1";
					}
				}
				$med_table .= "<td>" . $instructions . "</td>";
				$med_table .= "<td>" . date('m-d-Y', $this->human_to_unix($sup_row->sup_date_active)) . "</td>";
				$med_table .= "<td>Active</td>";
				$med_table .= "<td>" . $sup_row->sup_reason . "</td>";
				$med_table .= "</tr>";
				$med_file = file_get_contents(__DIR__.'/../../public/medications.xml');
				$med_number = "#med" . $k;
				$med_random_id1 = $this->gen_uuid();
				$med_random_id2 = $this->gen_uuid();
				$med_file = str_replace('?med_random_id1?', $med_random_id1, $med_file);
				$med_file = str_replace('?med_random_id2?', $med_random_id2, $med_file);
				$med_file = str_replace('?med_number?', $med_number, $med_file);
				$med_file = str_replace('?med_date_active?', date('Ymd', $this->human_to_unix($sup_row->sup_date_active)), $med_file);
				$med_file = str_replace('?med_code?', $med_code, $med_file);
				$med_file = str_replace('?med_code_description?', $med_code_description, $med_file);
				$med_file = str_replace('?med_period?', $med_period, $med_file);
				$med_file = str_replace('?med_dosage?', $med_dosage, $med_file);
				$med_file = str_replace('?med_dosage_unit?', $med_dosage_unit, $med_file);
				$med_rxnorm_code = '';
				$med_name = $sup_row->sup_supplement . ' ' . $sup_row->sup_dosage . ' ' . $sup_row->sup_dosage_unit ;
				$med_file = str_replace('?med_rxnorm_code?', $med_rxnorm_code, $med_file);
				$med_file = str_replace('?med_name?', $med_name, $med_file);
				$med_file_final .= $med_file;
				$k++;
			}
		}
		$ccda = str_replace('?med_table?', $med_table, $ccda);
		$ccda = str_replace('?med_file?', $med_file_final, $ccda);
		$orders_table = "";
		$orders_file_final = "";
		if ($recent_encounter_query) {
			$orders_query = DB::table('orders')->where('eid', '=', $recent_encounter_query->eid)->get();
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
		$proc_table = "";
		$proc_file_final = "";
		if ($recent_encounter_query) {
			$proc_query = DB::table('procedure')->where('eid', '=', $recent_encounter_query->eid)->get();
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
		}
		$ccda = str_replace('?proc_table?', $proc_table, $ccda);
		$ccda = str_replace('?proc_file?', $proc_file_final, $ccda);
		$other_history_table = "";
		$other_history_file = "";
		if ($recent_encounter_query) {
			$other_history_row = DB::table('other_history')->where('eid', '=', $recent_encounter_query->eid)->first();
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
		}
		$ccda = str_replace('?other_history_table?', $other_history_table, $ccda);
		$ccda = str_replace('?other_history_file?', $other_history_file, $ccda);
		$vitals_table = "";
		$vitals_file_final = "";
		if ($recent_encounter_query) {
			$vitals_row = DB::table('vitals')->where('eid', '=', $recent_encounter_query->eid)->first();
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
					$vitals_file = file_get_contents(__DIR__.'/../../public/vitals.xml');
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
					$vitals_file = file_get_contents(__DIR__.'/../../public/vitals.xml');
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
		}
		$ccda = str_replace('?vitals_table?', $vitals_table, $ccda);
		$ccda = str_replace('?vitals_file?', $vitals_file_final, $ccda);
		return $ccda;
	}

	protected function gen_uuid()
	{
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	protected function gen_secret()
	{
		$length = 512;
		$val = '';
		for ($i = 0; $i < $length; $i++) {
			$val .= rand(0,9);
		}
		$fp = fopen('/dev/urandom','rb');
		$val = fread($fp, 32);
		fclose($fp);
		$val .= uniqid(mt_rand(), true);
		$hash = hash('sha512', $val, true);
		$result = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
		return $result;
	}

	protected function deltree($dir, $emptyonly=false)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->deltree("$dir/$file") : unlink("$dir/$file");
		}
		if ($emptyonly == true) {
			return 'OK';
		} else {
			return rmdir($dir);
		}
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
			$term_row = DB::table('cpt')->where('cpt', '=', $items[1])->first();
			if ($term_row) {
				$orders_code_description = $term_row->cpt_description;
			} else {
				$orders_code_description = '';
			}
			$orders_file2 = file_get_contents(__DIR__.'/../../public/orders_cpt.xml');
			$orders_file2 = str_replace('?orders_date?', date('Ymd', $this->human_to_unix($date)), $orders_file2);
			$orders_file2 = str_replace('?orders_code?', $items[1], $orders_file2);
			$orders_file2 = str_replace('?orders_code_description?', $orders_code_description, $orders_file2);
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
			$data['disclaimer'] .= ', ' . $practice->billing_street_address2;
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

	protected function page_hippa_request($id)
	{
		$result = DB::table('hippa_request')->where('hippa_request_id', '=', $id)->first();
		$row = Demographics::find($result->pid);
		$practice = Practiceinfo::find(Session::get('practice_id'));
		$data['practiceName'] = $practice->practice_name;
		$data['practiceLogo'] = $this->practice_logo(Session::get('practice_id'));
		$data['practiceInfo1'] = $practice->street_address1;
		if ($practice->street_address2 != '') {
			$data['practiceInfo1'] .= ', ' . $practice->street_address2;
		}
		$data['practiceInfo2'] = $practice->city . ', ' . $practice->state . ' ' . $practice->zip;
		$data['practiceInfo3'] = 'Phone: ' . $practice->phone . ', Fax: ' . $practice->fax;
		$data['patientInfo1'] = $row->firstname . ' ' . $row->lastname;
		$data['patientInfo2'] = $row->address;
		$data['patientInfo3'] = $row->city . ', ' . $row->state . ' ' . $row->zip;
		$data['patientInfo'] = $row;
		$dob = $this->human_to_unix($row->DOB);
		$data['dob'] = date('m/d/Y', $dob);
		$data['signature_title'] = 'PATIENT SIGNATURE';
		$data['signature_text'] = '';
		if ($dob >= $this->age_calc(18,'year')) {
			$data['signature_title'] = 'SIGNATURE OF PATIENT REPRESENTATIVE';
			$data['signature_text'] = '<br>Relationship of Representative:<br><br><br>';
		}
		if ($row->ss != '') {
			$data['ss'] = 'Social Security Number: ' . $row->ss . '<br>';
		} else {
			$data['ss'] = '';
		}
		if ($row->phone_home != '') {
			$data['phone'] = 'Phone Number: ' . $row->phone_home;
		} elseif ($row->phone_cell != '') {
			$data['phone'] = 'Phone Number: ' . $row->phone_cell;
		} else {
			$data['phone'] = '';
		}
		$data['title'] = "AUTHORIZATION TO RELEASE MEDICAL RECORDS";
		$data['date'] = date('F jS, Y', time());
		$data['reason'] = $result->request_reason;
		$data['type'] = $result->request_type;
		if ($result->request_reason == 'General Medical Records') {
			$data['type'] .= ' - excluding protected records: (Copies of medical records will be limited to two years of information including lab, x-ray unless otherwise requested.)<br>';
		}
		if ($result->history_physical != '') {
			$data['type'] .= ' dated ' . $result->history_physical . '.<br>';
		}
		if ($result->lab_type != '') {
			$data['type'] .= '<br>' . $result->lab_type . ', Dated ' . $result->lab_date . '.<br>';
		}
		if ($result->op != '') {
			$data['type'] .= ' for ' . $result->op . '.<br>';
		}
		if ($result->accident_f != '') {
			$data['type'] .= ' dated from ' . $result->accident_f . ' to ' . $result->accdient_t . '.<br>';
		}
		if ($result->other != '') {
			$data['type'] .= '<br>' . $result->other . '<br>';
		}
		$data['from'] = $result->request_to;
		if ($result->address_id != '') {
			$address = DB::table('addressbook')->where('address_id', '=', $result->address_id)->first();
			if ($address) {
				$data['from'] = $address->displayname . '<br>' . $address->street_address1 . '<br>';
				if ($address->street_address2 != '') {
					$data['from'] .= $address->street_address2 . '<br>';
				}
				$data['from'] .= $address->city . ', ' . $address->state . ' ' . $address->zip . '<br>';
				$data['from'] .= $address->phone .'<br>';
			}
		}
		return View::make('pdf.hippa_request', $data);
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
				$data['pmlItems'] .= '<div style="width:6.62in;height:0.2in;float:left"></div>';
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
				'status' => 'Closed',
				'notes' => ''
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
				'status' => 'Closed',
				'notes' => ''
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
				'status' => 'Closed',
				'notes' => ''
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
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,0);
		$result = curl_exec($ch);
		$result_array = json_decode($result, true);
		$return_array = array();
		$i = 0;
		if (!empty($result_array)) {
			foreach ($result_array[0]['ChunkingResult']['DetailedChunkList'] as $row) {
				$return_array[$i]['term'] = $row['Term'];
				$return_array[$i]['id'] = $row['ConceptId'];
				$i++;
			}
		} else {
			if(curl_errno($ch)){
				$return_array[] = 'Error:' . curl_error($ch);
			}
		}
		curl_close($ch);
		return $return_array;
	}

	protected function clinithink_crossmap($id, $type)
	{
		$url = 'http://clinithink.api.mashery.com/v1/prd/search/CrossMaps?apiKey=gaaedrzjnyhtga7vc576xqjt';
		if ($type == 'icd9') {
			$type_id = '100046';
		}
		$fields_string = 'crossMapSetId=' . $type_id . '&conceptId=' . $id;
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
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,0);
		$result = curl_exec($ch);
		$result_array = json_decode($result, true);
		if (!empty($result_array)) {
			$ret = $result_array['MappingResults'][0]['MappingCodes'][0]['Code'];
		} else {
			if(curl_errno($ch)){
				$ret = 'Error:' . curl_error($ch);
			}
		}
		curl_close($ch);
		return $ret;
	}

	protected function getCSVDelimiter($fileName)
	{
		//detect these delimeters
		$delA = array(";", ",", "|", "\t");
		$linesA = array();
		$resultA = array();
		$maxLines = 20; //maximum lines to parse for detection, this can be higher for more precision
		$lines = count(file($fileName));
		if ($lines < $maxLines) {//if lines are less than the given maximum
			$maxLines = $lines;
		}
		//load lines
		foreach ($delA as $key => $del) {
			$rowNum = 0;
			if (($handle = fopen($fileName, "r")) !== false) {
				$linesA[$key] = array();
				while ((($data = fgetcsv($handle, 1000, $del)) !== false) && ($rowNum < $maxLines)) {
					$linesA[$key][] = count($data);
					$rowNum++;
				}
				fclose($handle);
			}
		}
		foreach ($delA as $key => $del) {
			$discr = 0;
			foreach ($linesA[$key] as $actNum) {
				if ($actNum == 1) {
					$resultA[$key] = 65535; //there is only one column with this delimeter in this line, so this is not our delimiter, set this discrepancy to high
					break;
				}
				foreach ($linesA[$key] as $actNum2) {
					$discr += abs($actNum - $actNum2);
				}
				$resultA[$key] = $discr;
			}
		}
		$delRes = 65535;
		foreach ($resultA as $key => $res) {
			if ($res < $delRes) {
				$delRes = $res;
				$delKey = $key;
			}
		}
		$delimiter = $delA[$delKey];
		return $delimiter;
	}

	protected function age_calc($num, $type)
	{
		if ($type == 'year') {
			$a = 31556926*$num;
		}
		if ($type == 'month') {
			$a = 2629743*$num;
		}
		$b = time() - $a;
		return $b;
	}

	protected function hedis_assessment_query($pid, $type, $assessment_item_array)
	{
		$query = DB::table('assessment')
			->join('encounters', 'encounters.eid', '=', 'assessment.eid')
			->where('encounters.addendum', '=', 'n')
			->where('encounters.pid', '=', $pid)
			->where('encounters.encounter_signed', '=', 'Yes');
		if ($type != 'all') {
			if ($type == 'year') {
				$date_param = date('Y-m-d H:i:s', time() - 31556926);
				$query->where('encounters.encounter_DOS', '>=', $date_param);
			} else {
				$date_param = date('Y-m-d H:i:s', strtotime($type));
				$query->where('encounters.encounter_DOS', '>=', $date_param);
			}
		}
		$query->where(function($query_array) use ($assessment_item_array) {
				$count = 0;
				foreach ($assessment_item_array as $assessment_item) {
					if ($count == 0) {
						$query_array->where('assessment.assessment_icd1', '=', $assessment_item)->orWhere('assessment.assessment_icd2', '=', $assessment_item)->orWhere('assessment.assessment_icd3', '=', $assessment_item)->orWhere('assessment.assessment_icd4', '=', $assessment_item)->orWhere('assessment.assessment_icd5', '=', $assessment_item)->orWhere('assessment.assessment_icd6', '=', $assessment_item)->orWhere('assessment.assessment_icd7', '=', $assessment_item)->orWhere('assessment.assessment_icd8', '=', $assessment_item)->orWhere('assessment.assessment_icd9', '=', $assessment_item)->orWhere('assessment.assessment_icd10', '=', $assessment_item)->orWhere('assessment.assessment_icd11', '=', $assessment_item)->orWhere('assessment.assessment_icd12', '=', $assessment_item);
					} else {
						$query_array->orWhere('assessment.assessment_icd1', '=', $assessment_item)->orWhere('assessment.assessment_icd2', '=', $assessment_item)->orWhere('assessment.assessment_icd3', '=', $assessment_item)->orWhere('assessment.assessment_icd4', '=', $assessment_item)->orWhere('assessment.assessment_icd5', '=', $assessment_item)->orWhere('assessment.assessment_icd6', '=', $assessment_item)->orWhere('assessment.assessment_icd7', '=', $assessment_item)->orWhere('assessment.assessment_icd8', '=', $assessment_item)->orWhere('assessment.assessment_icd9', '=', $assessment_item)->orWhere('assessment.assessment_icd10', '=', $assessment_item)->orWhere('assessment.assessment_icd11', '=', $assessment_item)->orWhere('assessment.assessment_icd12', '=', $assessment_item);
					}
					$count++;
				}
			})
			->select('encounters.eid','encounters.pid');
		$result = $query->get();
		return $result;
	}

	protected function hedis_issue_query($pid, $issues_item_array)
	{
		$query = DB::table('issues')
			->where('pid','=', $pid)
			->where('issue_date_inactive', '=', '0000-00-00 00:00:00')
			->where(function($query_array) {
				$issues_item_array = array('496','J44.9');
				$count = 0;
				foreach ($issues_item_array as $issues_item) {
					if ($count == 0) {
						$query_array->where('issue', 'LIKE', "%$issues_item%");
					} else {
						$query_array->orWhere('issue', 'LIKE', "%$issues_item%");
					}
					$count++;
				}
			})
			->first();
		return $query;
	}

	protected function hedis_aba($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Adult BMI Assessment not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('BMI', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('assessment')
				->where('pid', '=', $pid)
				->where(function($query_array1) {
					$assessment_item_array = array('V85.0','V85.1','V85.21','V85.22','V85.23','V85.24','V85.25','V85.30','V85.31','V85.32','V85.33','V85.34','V85.35','V85.36','V85.37','V85.38','V85.39','V85.41','V85.42','V85.43','V85.44','V85.45','V85.51','V85.52','V85.53','V85.54','Z68.1','Z68.20','Z68.21','Z68.22','Z68.23','Z68.24','Z68.25','Z68.26','Z68.27','Z68.28','Z68.29','Z68.30','Z68.31','Z68.32','Z68.33','Z68.34','Z68.35','Z68.36','Z68.37','Z68.38','Z68.39','Z68.41','Z68.42','Z68.43','Z68.44','Z68.45');
					$i = 0;
					foreach ($assessment_item_array as $assessment_item) {
						if ($i == 0) {
							$query_array1->where('assessment_icd1', '=', $assessment_item)->orWhere('assessment_icd2', '=', $assessment_item)->orWhere('assessment_icd3', '=', $assessment_item)->orWhere('assessment_icd4', '=', $assessment_item)->orWhere('assessment_icd5', '=', $assessment_item)->orWhere('assessment_icd6', '=', $assessment_item)->orWhere('assessment_icd7', '=', $assessment_item)->orWhere('assessment_icd8', '=', $assessment_item)->orWhere('assessment_icd9', '=', $assessment_item)->orWhere('assessment_icd10', '=', $assessment_item)->orWhere('assessment_icd11', '=', $assessment_item)->orWhere('assessment_icd12', '=', $assessment_item);
						} else {
							$query_array1->orWhere('assessment_icd1', '=', $assessment_item)->orWhere('assessment_icd2', '=', $assessment_item)->orWhere('assessment_icd3', '=', $assessment_item)->orWhere('assessment_icd4', '=', $assessment_item)->orWhere('assessment_icd5', '=', $assessment_item)->orWhere('assessment_icd6', '=', $assessment_item)->orWhere('assessment_icd7', '=', $assessment_item)->orWhere('assessment_icd8', '=', $assessment_item)->orWhere('assessment_icd9', '=', $assessment_item)->orWhere('assessment_icd10', '=', $assessment_item)->orWhere('assessment_icd11', '=', $assessment_item)->orWhere('assessment_icd12', '=', $assessment_item);
						}
						$i++;
					}
				})
				->orderBy('eid', 'desc')
				->first();
			if ($query1) {
				$score++;
			} else {
				$data['fix'][] = 'BMI needs to measured';
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Adult BMI Assessment performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_wcc($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Weight Assessment and Counseling for Nutrition and Physical Activity for Children and Adolescents not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('BMI', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$data['fix'][] = 'BMI, height, and weight needs to be measured';
		}
		$query1 = DB::table('billing_core')
			->where('pid', '=', $pid)
			->where(function($query_array1) {
				$wcc_item_array = array('97802','97803','97804');
				$i = 0;
				foreach ($wcc_item_array as $wcc_item) {
					if ($i == 0) {
						$query_array1->where('cpt', '=', $wcc_item);
					} else {
						$query_array1->orWhere('cpt', '=', $wcc_item);
					}
					$i++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query1) {
			$score++;
		} else {
			$data['fix'][] = 'Nutritional counseling needs to be performed';
		}
		$query2 = DB::table('assessment')
			->where('pid', '=', $pid)
			->where(function($query_array2) {
				$assessment_item_array2 = array('V85.51','V85.52','V85.53','V85.54','Z68.51','Z68.52','Z68.53','Z68.54');
				$count2 = 0;
				foreach ($assessment_item_array2 as $assessment_item2) {
					if ($count2 == 0) {
						$query_array2->where('assessment_icd1', '=', $assessment_item2)->orWhere('assessment_icd2', '=', $assessment_item2)->orWhere('assessment_icd3', '=', $assessment_item2)->orWhere('assessment_icd4', '=', $assessment_item2)->orWhere('assessment_icd5', '=', $assessment_item2)->orWhere('assessment_icd6', '=', $assessment_item2)->orWhere('assessment_icd7', '=', $assessment_item2)->orWhere('assessment_icd8', '=', $assessment_item2)->orWhere('assessment_icd9', '=', $assessment_item2)->orWhere('assessment_icd10', '=', $assessment_item2)->orWhere('assessment_icd11', '=', $assessment_item2)->orWhere('assessment_icd12', '=', $assessment_item2);
					} else {
						$query_array2->orWhere('assessment_icd1', '=', $assessment_item2)->orWhere('assessment_icd2', '=', $assessment_item2)->orWhere('assessment_icd3', '=', $assessment_item2)->orWhere('assessment_icd4', '=', $assessment_item2)->orWhere('assessment_icd5', '=', $assessment_item2)->orWhere('assessment_icd6', '=', $assessment_item2)->orWhere('assessment_icd7', '=', $assessment_item2)->orWhere('assessment_icd8', '=', $assessment_item2)->orWhere('assessment_icd9', '=', $assessment_item2)->orWhere('assessment_icd10', '=', $assessment_item2)->orWhere('assessment_icd11', '=', $assessment_item2)->orWhere('assessment_icd12', '=', $assessment_item2);
					}
					$count2++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query2) {
			$score++;
		} else {
			$data['fix'][] = 'BMI, height, and weight needs to be measured';
		}
		$query3 = DB::table('assessment')
			->where('pid', '=', $pid)
			->where(function($query_array3) {
				$assessment_item_array3 = array('V65.3','Z71.3');
				$count3 = 0;
				foreach ($assessment_item_array3 as $assessment_item3) {
					if ($count3 == 0) {
						$query_array3->where('assessment_icd1', '=', $assessment_item3)->orWhere('assessment_icd2', '=', $assessment_item3)->orWhere('assessment_icd3', '=', $assessment_item3)->orWhere('assessment_icd4', '=', $assessment_item3)->orWhere('assessment_icd5', '=', $assessment_item3)->orWhere('assessment_icd6', '=', $assessment_item3)->orWhere('assessment_icd7', '=', $assessment_item3)->orWhere('assessment_icd8', '=', $assessment_item3)->orWhere('assessment_icd9', '=', $assessment_item3)->orWhere('assessment_icd10', '=', $assessment_item3)->orWhere('assessment_icd11', '=', $assessment_item3)->orWhere('assessment_icd12', '=', $assessment_item3);
					} else {
						$query_array3->orWhere('assessment_icd1', '=', $assessment_item3)->orWhere('assessment_icd2', '=', $assessment_item3)->orWhere('assessment_icd3', '=', $assessment_item3)->orWhere('assessment_icd4', '=', $assessment_item3)->orWhere('assessment_icd5', '=', $assessment_item3)->orWhere('assessment_icd6', '=', $assessment_item3)->orWhere('assessment_icd7', '=', $assessment_item3)->orWhere('assessment_icd8', '=', $assessment_item3)->orWhere('assessment_icd9', '=', $assessment_item3)->orWhere('assessment_icd10', '=', $assessment_item3)->orWhere('assessment_icd11', '=', $assessment_item3)->orWhere('assessment_icd12', '=', $assessment_item3);
					}
					$count3++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query3) {
			$score++;
		} else {
			$data['fix'][] = 'Nutritional counseling needs to be performed';
		}
		$query4 = DB::table('assessment')
			->where('pid', '=', $pid)
			->where(function($query_array4) {
				$assessment_item_array4 = array('V65.41','Z71.89');
				$count4 = 0;
				foreach ($assessment_item_array4 as $assessment_item4) {
					if ($count4 == 0) {
						$query_array4->where('assessment_icd1', '=', $assessment_item4)->orWhere('assessment_icd2', '=', $assessment_item4)->orWhere('assessment_icd3', '=', $assessment_item4)->orWhere('assessment_icd4', '=', $assessment_item4)->orWhere('assessment_icd5', '=', $assessment_item4)->orWhere('assessment_icd6', '=', $assessment_item4)->orWhere('assessment_icd7', '=', $assessment_item4)->orWhere('assessment_icd8', '=', $assessment_item4)->orWhere('assessment_icd9', '=', $assessment_item4)->orWhere('assessment_icd10', '=', $assessment_item4)->orWhere('assessment_icd11', '=', $assessment_item4)->orWhere('assessment_icd12', '=', $assessment_item4);
					} else {
						$query_array4->orWhere('assessment_icd1', '=', $assessment_item4)->orWhere('assessment_icd2', '=', $assessment_item4)->orWhere('assessment_icd3', '=', $assessment_item4)->orWhere('assessment_icd4', '=', $assessment_item4)->orWhere('assessment_icd5', '=', $assessment_item4)->orWhere('assessment_icd6', '=', $assessment_item4)->orWhere('assessment_icd7', '=', $assessment_item4)->orWhere('assessment_icd8', '=', $assessment_item4)->orWhere('assessment_icd9', '=', $assessment_item4)->orWhere('assessment_icd10', '=', $assessment_item4)->orWhere('assessment_icd11', '=', $assessment_item4)->orWhere('assessment_icd12', '=', $assessment_item4);
					}
					$count4++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query4) {
			$score++;
		} else {
			$data['fix'][] = 'Physical activity counseling needs to be performed.';
		}
		if ($score >= 2) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Weight Assessment and Counseling for Nutrition and Physical Activity for Children and Adolescents performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_cis($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Childhood Immunization Status not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('BMI', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			$score++;
		}
		// DTaP
		$query_1 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_1) {
				$imm_array_1 = array('20', '106', '107', '146', '110', '50', '120', '130', '132', '1', '22', '102');
				$count_1 = 0;
				foreach ($imm_array_1 as $imm_1) {
					if ($count_1 == 0) {
						$query_array_1->where('imm_cvxcode', '=', $imm_1);
					} else {
						$query_array_1->orWhere('imm_cvxcode', '=', $imm_1);
					}
					$count_1++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_1) {
			$score++;
		} else {
			$data['fix'][] = 'Needs DTaP immunization';
		}
		// IPV
		$query_2 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_2) {
				$imm_array_2 = array('146', '110', '120', '130', '132', '10');
				$count_2 = 0;
				foreach ($imm_array_2 as $imm_2) {
					if ($count_2 == 0) {
						$query_array_2->where('imm_cvxcode', '=', $imm_2);
					} else {
						$query_array_2->orWhere('imm_cvxcode', '=', $imm_2);
					}
					$count_2++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_2) {
			$score++;
		} else {
			$data['fix'][] = 'Needs IPV immunization';
		}
		// MMR
		$query_3 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_3) {
				$imm_array_3 = array('3', '94', '5', '6', '7', '38');
				$count_3 = 0;
				foreach ($imm_array_3 as $imm_3) {
					if ($count_3 == 0) {
						$query_array_3->where('imm_cvxcode', '=', $imm_3);
					} else {
						$query_array_3->orWhere('imm_cvxcode', '=', $imm_3);
					}
					$count_3++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_3) {
			$score++;
		} else {
			$data['fix'][] = 'Needs MMR immunization';
		}
		// Hib
		$query_4 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_4) {
				$imm_array_4 = array('146','50','120','132', '22', '102', '46', '47', '48', '49', '17', '51', '148');
				$count_4 = 0;
				foreach ($imm_array_4 as $imm_4) {
					if ($count_4 == 0) {
						$query_array_4->where('imm_cvxcode', '=', $imm_4);
					} else {
						$query_array_4->orWhere('imm_cvxcode', '=', $imm_4);
					}
					$count_4++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_4) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Hib immunization';
		}
		// HepB
		$query_5 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_5) {
				$imm_array_5 = array('146','110','132','102','104','8','42','43','44','45','51');
				$count_5 = 0;
				foreach ($imm_array_5 as $imm_5) {
					if ($count_5 == 0) {
						$query_array_5->where('imm_cvxcode', '=', $imm_5);
					} else {
						$query_array_5->orWhere('imm_cvxcode', '=', $imm_5);
					}
					$count_5++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_5) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Hepatitis B immunization';
		}
		// Varicella
		$query_6 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_6) {
				$imm_array_6 = array('21');
				$count_6 = 0;
				foreach ($imm_array_6 as $imm_6) {
					if ($count_6 == 0) {
						$query_array_6->where('imm_cvxcode', '=', $imm_6);
					} else {
						$query_array_6->orWhere('imm_cvxcode', '=', $imm_6);
					}
					$count_6++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_6) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Varicella immunization';
		}
		// Pneumococcal
		$query_7 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_7) {
				$imm_array_7 = array('133','100','109');
				$count_7 = 0;
				foreach ($imm_array_7 as $imm_7) {
					if ($count_7 == 0) {
						$query_array_7->where('imm_cvxcode', '=', $imm_7);
					} else {
						$query_array_7->orWhere('imm_cvxcode', '=', $imm_7);
					}
					$count_7++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_7) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Pneumoccocal immunization';
		}
		// HepA
		$query_8 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_8) {
				$imm_array_8 = array('52','83','84','31','85','104');
				$count_8 = 0;
				foreach ($imm_array_8 as $imm_8) {
					if ($count_8 == 0) {
						$query_array_8->where('imm_cvxcode', '=', $imm_8);
					} else {
						$query_array_8->orWhere('imm_cvxcode', '=', $imm_8);
					}
					$count_8++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_8) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Hepatitis A immunization';
		}
		// Rotavirus
		$query_9 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_9) {
				$imm_array_9 = array('119','116','74','122');
				$count_9 = 0;
				foreach ($imm_array_9 as $imm_9) {
					if ($count_9 == 0) {
						$query_array_9->where('imm_cvxcode', '=', $imm_9);
					} else {
						$query_array_9->orWhere('imm_cvxcode', '=', $imm_9);
					}
					$count_9++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_9) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Rotavirus immunization';
		}
		// Influenza
		$query_10 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_10) {
				$imm_array_10 = array('123','135','111','149','141','140','144','15','88','16','127','128','125','126');
				$count_10 = 0;
				foreach ($imm_array_10 as $imm_10) {
					if ($count_10 == 0) {
						$query_array_10->where('imm_cvxcode', '=', $imm_10);
					} else {
						$query_array_10->orWhere('imm_cvxcode', '=', $imm_10);
					}
					$count_10++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_10) {
			$score++;
		} else {
			$data['fix'][] = 'Needs influenza immunization';
		}
		if ($score >= 11) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Childhood Immunization Status performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_ima($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Immunizations for Adolescents not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('BMI', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			$score++;
		}
		// Meningococcal
		$query_1 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_1) {
				$imm_array_1 = array('103', '148', '147', '136', '114', '32', '108');
				$count_1 = 0;
				foreach ($imm_array_1 as $imm_1) {
					if ($i == 0) {
						$query_array_1->where('imm_cvxcode', '=', $imm_1);
					} else {
						$query_array_1->orWhere('imm_cvxcode', '=', $imm_1);
					}
					$count_1++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_1) {
			$score++;
		} else {
			$data['fix'][] = 'Needs meningococcal immunization';
		}
		// Tdap
		$query_2 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_2) {
				$imm_array_2 = array('138', '113', '9', '139', '115');
				$count_2 = 0;
				foreach ($imm_array_2 as $imm_2) {
					if ($i == 0) {
						$query_array_2->where('imm_cvxcode', '=', $imm_2);
					} else {
						$query_array_2->orWhere('imm_cvxcode', '=', $imm_2);
					}
					$count_2++;
				}
			})
			->orderBy('eid', 'desc')
			->first();
		if ($query_2) {
			$score++;
		} else {
			$data['fix'][] = 'Needs Tdap immunization';
		}
		if ($score >= 2) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Immunizations for Adolescents performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_hpv($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Human Papillomavirus Vaccine for Female Adolescents not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('BMI', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			$score++;
		}
		// HPV
		$query_1 = DB::table('immunizations')
			->where('pid', '=', $pid)
			->where(function($query_array_1) {
				$imm_array_1 = array('118', '62', '137');
				$count_1 = 0;
				foreach ($imm_array_1 as $imm_1) {
					if ($i == 0) {
						$query_array_1->where('imm_cvxcode', '=', $imm_1);
					} else {
						$query_array_1->orWhere('imm_cvxcode', '=', $imm_1);
					}
					$count_1++;
				}
			})
			->orderBy('eid', 'desc')
			->get();
		if ($query_1) {
			$count = count($query1);
			if ($dob >= $e) {
				if ($count == 3) {
					$score++;
				}
			}
			if ($dob >= $a && $dob < $b) {
				if ($count > 0) {
					$score++;
				}
			}
		} else {
			$data['fix'][] = 'Needs HPV immunization';
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Human Papillomavirus Vaccine for Female Adolescents performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_lsc($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Lead Screening in Children not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%lead%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%lead%")
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%lead%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Lead level needs to be measured';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Lead Screening in Children performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_bcs($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Breast Cancer Screening not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%mammogram%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%mammogram%")
				->where('documents_type', '=', 'Imaging')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%mammogram%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Mammogram needs to be performed';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Breast Cancer Screening performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_ccs($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Cervical Cancer Screening not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%pap%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%pap%")
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%pap%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Pap test needs to be performed';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Cervical Cancer Screening performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_col($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Colorectal Cancer Screening not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)
			->where(function($query_array) {
				$query_array->where('test_name', 'LIKE', "%colonoscopy%")
					->orWhere('test_name', 'LIKE', "%sigmoidoscopy%");
			})
			->orderBy('test_datetime', 'desc')
			->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where(function($query_array1) {
					$query_array1->where('documents_desc', 'LIKE', "%colonoscopy%")
						->orWhere('documents_desc', 'LIKE', "%sigmoidoscopy%");
				})
				->where('documents_type', '=', 'Endoscopy')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where(function($query_array2) {
						$query_array2->where('tags.tag', 'LIKE', "%colonoscopy%")
							->orWhere('tags.tag', 'LIKE', "%sigmoidoscopy%");
					})
					->first();
				if ($query2) {
					$score++;
				} else {
					$query3 = DB::table('documents')
						->where('pid', '=', $pid)
						->where(function($query_array3) {
							$query_array3->where('documents_desc', 'LIKE', "%guaiac%")
								->orWhere('documents_desc', 'LIKE', "%fobt%");
						})
						->where('documents_type', '=', 'Laboratory')
						->first();
					if ($query3) {
						$score++;
					} else {
						$query4 = DB::table('billing_core')
							->where('pid', '=', $pid)
							->where(function($query_array4) {
								$fobt_item_array = array('82270','82274');
								$fobt_count = 0;
								foreach ($fobt_item_array as $fobt_item) {
									if ($fobt_count == 0) {
										$query_array4->where('cpt', '=', $fobt_item);
									} else {
										$query_array4->orWhere('cpt', '=', $fobt_item);
									}
									$fobt_count++;
								}
							})
							->orderBy('eid', 'desc')
							->first();
						if ($query4) {
							$score++;
						} else {
							$data['fix'][] = 'Colon cancer screening needs to be performed';
						}
					}
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Colorectal Cancer Screening performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_chl($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Chlamydia Screening not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%chlamydia%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%chlamydia%")
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%chlamydia%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Chlamydia test needs to be performed';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Chlamydia Screening performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_gso($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Glaucoma Screening Older Adults not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%glaucoma%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%glaucoma%")
				->where('documents_type', '=', 'Referrals')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%glaucoma%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Glaucoma screening needs to be performed';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Glaucoma Screening Older Adults performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_cwp($cwp_result)
	{
		$data = array();
		$data['count'] = count($cwp_result);
		$data['test'] = 0;
		$data['abx'] = 0;
		$data['abx_no_test'] = 0;
		$data['percent_test'] = 0;
		$data['percent_abx'] = 0;
		$data['percent_abx_no_test'] = 0;
		foreach ($cwp_result as $row) {
			$test = 0;
			$query1 = DB::table('billing_core')
				->where('eid', '=', $row->eid)
				->where(function($query_array2) {
					$item2_array = array('87880','87070','87071','87081','87430','87650','87651','87652');
					$j = 0;
					foreach ($item2_array as $item2) {
						if ($j == 0) {
							$query_array2->where('cpt', '=', $item2);
						} else {
							$query_array2->orWhere('cpt', '=', $item2);
						}
						$j++;
					}
				})
				->first();
			if ($query1) {
				$data['test']++;
				$test++;
			}
			$query2 = DB::table('rx')->where('eid', '=', $row->eid)->first();
			if ($query2) {
				if ($query2->rx_rx != '') {
					$abx_count = 0;
					$search = array('cillin','amox','zith','cef','kef','mycin','eryth','pen','bac','sulf');
					foreach ($search as $needle) {
						$pos = stripos($query2->rx_rx, $needle);
						if ($pos !== false) {
							$abx_count++;
						}
					}
					if ($abx_count > 0) {
						$data['abx']++;
						if ($test == 0) {
							$data['abx_no_test']++;
						}
					}
				}
			}
		}
		$data['percent_test'] = round($data['test']/$data['count']*100);
		$data['percent_abx'] = round($data['abx']/$data['count']*100);
		$data['percent_abx_no_test'] = round($data['abx_no_test']/$data['count']*100);
		return $data;
	}

	protected function hedis_uri($uri_result)
	{
		$data = array();
		$data['count'] = count($uri_result);
		$data['abx'] = 0;
		$data['percent_abx'] = 0;
		foreach ($uri_result as $row) {
			$query1 = DB::table('rx')->where('eid', '=', $row->eid)->first();
			if ($query1) {
				if ($query1->rx_rx != '') {
					$abx_count = 0;
					$search = array('cillin','amox','zith','cef','kef','mycin','eryth','pen','bac','sulf');
					foreach ($search as $needle) {
						$pos = stripos($query2->rx_rx, $needle);
						if ($pos !== false) {
							$abx_count++;
						}
					}
					if ($abx_count > 0) {
						$data['abx']++;
					}
				}
			}
		}
		$data['percent_abx'] = round($data['abx']/$data['count']*100);
		return $data;
	}

	protected function hedis_aab($aab_result)
	{
		$data = array();
		$data['count'] = count($aab_result);
		$data['abx'] = 0;
		$data['percent_abx'] = 0;
		foreach ($query as $row) {
			$query1 = DB::table('rx')->where('eid', '=', $row->eid)->first();
			if ($query1) {
				if ($query1->rx_rx != '') {
					$abx_count = 0;
					$search = array('cillin','amox','zith','cef','kef','mycin','eryth','pen','bac','sulf','cycl','lox');
					foreach ($search as $needle) {
						$pos = stripos($query1->rx_rx, $needle);
						if ($pos !== false) {
							$abx_count++;
						}
					}
					if ($abx_count > 0) {
						$data['abx']++;
					}
				}
			}
		}
		$data['percent_abx'] = round($data['abx']/$data['count']*100);
		return $data;
	}

	protected function hedis_spr($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Use of Spirometry Testing in the Assessment and Diagnosis of COPD not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)->where('test_name', 'LIKE', "%spirometry%")->orderBy('test_datetime', 'desc')->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%spirometry%")
				->where('documents_type', '=', 'Cardiopulmonary')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%spirometry%")
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'Glaucoma screening needs to be performed';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Use of Spirometry Testing in the Assessment and Diagnosis of COPD performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_pce($pce_result)
	{
		$data = array();
		$data['count'] = count($pce_result);
		$data['tx'] = 0;
		$data['percent_tx'] = 0;
		foreach ($pce_result as $row) {
			$query1 = DB::table('rx')->where('eid', '=', $row->eid)->first();
			if ($query1) {
				if ($query1->rx_rx != '') {
					$steroid_count = 0;
					$inhaler_count = 0;
					$search = array('sone','medrol','pred','celestone','cortef','decadron','rayos');
					foreach ($search as $needle) {
						$pos = stripos($query1->rx_rx, $needle);
						if ($pos !== false) {
							$steroid_count++;
						}
					}
					$search1 = array('terol','hfa','xopenex','maxair','combivent','ipratro','duoneb');
					foreach ($search1 as $needle1) {
						$pos1 = stripos($query1->rx_rx, $needle1);
						if ($pos1 !== false) {
							$inhaler_count++;
						}
					}
					if ($steroid_count > 0 && $inhaler_count > 0) {
						$data['tx']++;
					}
				}
			}
		}
		$data['percent_tx'] = round($data['tx']/$data['count']*100);
		return $data;
	}

	protected function hedis_asm($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Use of Appropriate Medications for People with Asthma not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$med_count = 0;
			$search = array('budesonide','flovent','pulmicort','qvar','advair','aerobid','alvesco','asmanex','dulera','pulmicort','symbicort','breo','fluticasone','beclomethasone','flunisolide','ciclesonide','mometasone','cromolyn','phylline','lukast','singulair','accolate','theo');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$med_count++;
				}
			}
			if ($med_count > 0) {
				$score++;
			} else {
				$data['fix'][] = 'If the patient does not have mild, intermittent asthma, a controller medication is recommended.';
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Use of Appropriate Medications for People with Asthma performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_amr($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Medication Management for People with Asthma not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$controller_count = 0;
			$rescue_count = 0;
			$search = array('budesonide','flovent','pulmicort','qvar','advair','aerobid','alvesco','asmanex','dulera','pulmicort','symbicort','breo','fluticasone','beclomethasone','flunisolide','ciclesonide','mometasone','cromolyn','phylline','lukast','singulair','accolate','theo');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$controller_count++;
				}
			}
			$search1 = array('albuterol','ventolin','alupent','metproterenol');
			foreach ($search1 as $needle1) {
				$pos1 = stripos($query1->rxl_medication, $needle1);
				if ($pos1 !== false) {
					$rescue_count++;
				}
			}
			$total = $controller_count + $rescue_count;
			$ratio = round($controller_count/$total*100);
			if ($ratio > 50) {
				$score++;
			} else {
				$data['fix'][] = 'If the patient does not have mild, intermittent asthma, a ratio of controller medications to the total number of asthma medications of greater than 0.5 is recommended.';
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Medication Management for People with Asthma performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_cmc($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Cholesterol Management for Patients With Cardiovascular Conditions not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('tests')->where('pid', '=', $pid)
			->where(function($query_array) {
					$query_array->where('test_name', 'LIKE', "%ldl%")
						->orWhere('test_name', 'LIKE', "%cholesterol%")
						->orWhere('test_name', 'LIKE', "%lipid%");
				})
			->orderBy('test_datetime', 'desc')
			->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where(function($query_array1) {
					$query_array1->where('documents_desc', 'LIKE', "%ldl%")
						->orWhere('documents_desc', 'LIKE', "%cholesterol%")
						->orWhere('documents_desc', 'LIKE', "%lipid%");
				})
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where(function($query_array2) {
						$query_array2->where('tags.tag', 'LIKE', "%ldl%")
							->orWhere('tags.tag', 'LIKE', "%cholesterol%")
							->orWhere('tags.tag', 'LIKE', "%lipid%");
					})
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'LDL needs to be measured';
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Cholesterol Management for Patients With Cardiovascular Conditions performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_cbp($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Controlling High Blood Pressure not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$systolic = 0;
		$diastolic = 0;
		$query = DB::table('vitals')->where('pid', '=', $pid)->where('bp_systolic', '!=', '')->where('bp_diastolic', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query) {
			if ($query->bp_systolic < 140) {
				$systolic++;
			}
			if ($query->bp_diastolic < 90) {
				$diastolic++;
			}
			$score = $systolic + $diastolic;
			if ($score == 2) {
				$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Controlling High Blood Pressure performed';
				$data['goal'] = 'y';
			} else {
				$data['fix'][] = 'Blood pressure needs to be under better control.';
			}
		} else {
			$data['fix'][] = 'Blood pressures need to be measured.';
		}
		return $data;
	}

	protected function hedis_pbh($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Persistence of Beta-Blocker Treatment After a Heart Attack not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$search = array('olol','ilol','alol','betapace','brevibloc','bystolic','coreg','corgard','inderal','innopran','kerlone','levatol','lopressor','sectral','tenormin','oprol','trandate','zebeta','sorine','corzide','tenoretic','ziac');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$score++;
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Persistence of Beta-Blocker Treatment After a Heart Attack performed';
			$data['goal'] = 'y';
		} else {
			$data['fix'][] = 'Beta blocker is recommended.';
		}
		return $data;
	}

	protected function hedis_cdc($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Comprehensive Diabetes Care not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		// HgbA1c
		$query = DB::table('tests')->where('pid', '=', $pid)
			->where(function($query_array) {
					$query_array->where('test_name', 'LIKE', "%hgba1c%")
						->orWhere('test_name', 'LIKE', "%a1c%");
				})
			->orderBy('test_datetime', 'desc')
			->first();
		if ($query) {
			$score++;
		} else {
			$query1 = DB::table('documents')
				->where('pid', '=', $pid)
				->where(function($query_array1) {
					$query_array1->where('documents_desc', 'LIKE', "%hgba1c%")
						->orWhere('documents_desc', 'LIKE', "%a1c%");
				})
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query1) {
				$score++;
			} else {
				$query2 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where(function($query_array2) {
						$query_array2->where('tags.tag', 'LIKE', "%hgba1c%")
							->orWhere('tags.tag', 'LIKE', "%a1c%");
					})
					->first();
				if ($query2) {
					$score++;
				} else {
					$data['fix'][] = 'HgbA1c needs to be measured';
				}
			}
		}
		// LDL
		$query3 = DB::table('tests')->where('pid', '=', $pid)
			->where(function($query_array3) {
					$query_array3->where('test_name', 'LIKE', "%ldl%")
						->orWhere('test_name', 'LIKE', "%cholesterol%")
						->orWhere('test_name', 'LIKE', "%lipid%");
				})
			->orderBy('test_datetime', 'desc')
			->first();
		if ($query3) {
			$score++;
		} else {
			$query4 = DB::table('documents')
				->where('pid', '=', $pid)
				->where(function($query_array4) {
					$query_array4->where('documents_desc', 'LIKE', "%ldl%")
						->orWhere('documents_desc', 'LIKE', "%cholesterol%")
						->orWhere('documents_desc', 'LIKE', "%lipid%");
				})
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query4) {
				$score++;
			} else {
				$query5 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where(function($query_array5) {
						$query_array5->where('tags.tag', 'LIKE', "%ldl%")
							->orWhere('tags.tag', 'LIKE', "%cholesterol%")
							->orWhere('tags.tag', 'LIKE', "%lipid%");
					})
					->first();
				if ($query5) {
					$score++;
				} else {
					$data['fix'][] = 'LDL needs to be measured';
				}
			}
		}
		// Nephropathy screening
		$query6 = DB::table('tests')->where('pid', '=', $pid)
			->where('test_name', 'LIKE', "%microalbumin%")
			->orderBy('test_datetime', 'desc')
			->first();
		if ($query6) {
			$score++;
		} else {
			$query7 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%microalbumin%")
				->where('documents_type', '=', 'Laboratory')
				->first();
			if ($query7) {
				$score++;
			} else {
				$query8 = DB::table('tags_relate')
					->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
					->where('tags_relate.pid', '=', $pid)
					->where('tags.tag', 'LIKE', "%microalbumin%")
					->first();
				if ($query8) {
					$score++;
				} else {
					$data['fix'][] = 'Urine microalbumin needs to be measured';
				}
			}
		}
		// Eye exam
		$query9 = DB::table('documents')
			->where('pid', '=', $pid)
			->where(function($query_array9) {
				$query_array9->where('documents_desc', 'LIKE', "%ophthal%")
					->orWhere('documents_desc', 'LIKE', "%dilated eye%")
					->orWhere('documents_desc', 'LIKE', "%diabetic eye%");
			})
			->where('documents_type', '=', 'Referrals')
			->first();
		if ($query9) {
			$score++;
		} else {
			$query10 = DB::table('tags_relate')
				->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
				->where('tags_relate.pid', '=', $pid)
				->where(function($query_array10) {
					$query_array10->where('tags.tag', 'LIKE', "%ophthal%")
						->orWhere('tags.tag', 'LIKE', "%dilated eye%")
						->orWhere('tags.tag', 'LIKE', "%diabetic eye%");
				})
				->first();
			if ($query10) {
				$score++;
			} else {
				$data['fix'][] = 'Diabetic eye exam needs to be performed';
			}
		}
		// BP
		$systolic = 0;
		$diastolic = 0;
		$query11 = DB::table('vitals')->where('pid', '=', $pid)->where('bp_systolic', '!=', '')->where('bp_diastolic', '!=', '')->orderBy('eid', 'desc')->first();
		if ($query11) {
			if ($query11->bp_systolic < 140) {
				$systolic++;
			}
			if ($query11->bp_diastolic < 90) {
				$diastolic++;
			}
			$bp_score = $systolic + $diastolic;
			if ($bp_score == 2) {
				$score++;
			} else {
				$data['fix'][] = 'Blood pressure needs to be under better control.';
			}
		} else {
			$data['fix'][] = 'Blood pressures need to be measured.';
		}
		if ($score == 5) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Comprehensive Diabetes Care performed';
			$data['goal'] = 'y';
		}
		return $data;
	}

	protected function hedis_art($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Disease Modifying Anti-Rheumatic Drug Therapy for Rheumatoid Arthritis not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$search = array('methotrexate','azathioprine','cyclophosphamide','cyclosporine','azasan','cytoxan','gengraf','imuran','neoral','rheumatrex','trexall','embrel','remicade','cimzia','humira','simponi','cuprimine','hydroxychloroquine','sulfasalazine','actemra','arava','azulfidine','kineret','leflunomide','myochrysine','orencia','plaquenil','ridaura');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$score++;
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Disease Modifying Anti-Rheumatic Drug Therapy for Rheumatoid Arthritis performed';
			$data['goal'] = 'y';
		} else {
			$data['fix'][] = 'DMARD is recommended.';
		}
		return $data;
	}

	protected function hedis_omw($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Disease Modifying Anti-Rheumatic Drug Therapy for Rheumatoid Arthritis not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$search = array('actonel','dronate','atelvia','boniva','fosamax','reclast','binosto','zoledronic','estraderm','estradiol','estropipate','femhrt','jinteli','menest','premarin','premphase','vivelle','activella','alora','cenestin','climara','estrace','gynodiol','menostar','mimvey','ogen','prefest','minivelle','evista','forteo','prolia');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$score++;
				}
			}
		}
		$query2 = DB::table('documents')
			->where('pid', '=', $pid)
			->where(function($query_array2) {
				$query_array2->where('documents_desc', 'LIKE', "%dexa%")
					->orWhere('documents_desc', 'LIKE', "%osteoporosis%")
					->orWhere('documents_desc', 'LIKE', "%bone density%");
			})
			->where('documents_type', '=', 'Imaging')
			->first();
		if ($query2) {
			$score++;
		} else {
			$query3 = DB::table('tags_relate')
				->join('tags', 'tags.tags_id', '=', 'tags_relate.tags_id')
				->where('tags_relate.pid', '=', $pid)
				->where(function($query_array3) {
					$query_array3->where('tags.tag', 'LIKE', "%dexa%")
						->orWhere('tags.tag', 'LIKE', "%osteoporosis%")
						->orWhere('tags.tag', 'LIKE', "%bone density%");
				})
				->first();
			if ($query3) {
				$score++;
			} else {
				$query4 = DB::table('tests')->where('pid', '=', $pid)
					->where(function($query_array4) {
							$query_array4->where('test_name', 'LIKE', "%dexa%")
								->orWhere('test_name', 'LIKE', "%osteoporosis%")
								->orWhere('test_name', 'LIKE', "%bone density%");
						})
					->first();
				if ($query4) {
					$score++;
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Disease Modifying Anti-Rheumatic Drug Therapy for Rheumatoid Arthritis performed';
			$data['goal'] = 'y';
		} else {
			$data['fix'][] = 'Bone density screening needs to be performed or osteoporosis prevention medication is recommended';
		}
		return $data;
	}

	protected function hedis_lbp($lbp_result)
	{
		$data = array();
		$data['count'] = count($lbp_result);
		$data['no_rad'] = 0;
		$data['percent_no_rad'] = 0;
		$rad = 0;
		foreach ($lbp_result as $row) {
			$encounter = DB::table('encounters')->where('eid', '=', $row->eid)->first();
			$date_a = date('Y-m-d', $this->human_to_unix($encounter->encounter_DOS));
			$date_b = date('Y-m-d', $this->human_to_unix($encounter->encounter_DOS) + 2419200); //28 days from DOS
			$date_c = date('Y-m-d H:i:s', $this->human_to_unix($encounter->encounter_DOS));
			$date_d = date('Y-m-d H:i:s', $this->human_to_unix($encounter->encounter_DOS) + 2419200);
			$pid = $encounter->pid;
			$query2 = DB::table('documents')
				->where('pid', '=', $pid)
				->where('documents_desc', 'LIKE', "%ray%")
				->where('documents_date', '>=', $date_a)
				->where('documents_date', '<=', $date_b)
				->where(function($query_array2) {
					$query_array2->where('documents_desc', 'LIKE', "%lumbar%")
						->orWhere('documents_desc', 'LIKE', "%low back%");
				})
				->where('documents_type', '=', 'Imaging')
				->first();
			if ($query2) {
				$rad++;
			} else {
				$query3 = DB::table('tests')->where('pid', '=', $pid)
					->where('test_name', 'LIKE', "%ray%")
					->where('test_datetime', '>=', $date_c)
					->where('test_datetime', '<=', $date_d)
					->where(function($query_array) {
							$query_array->where('test_name', 'LIKE', "%lumbar%")
								->orWhere('test_name', 'LIKE', "%low back%");
						})
					->first();
				if ($query3) {
					$rad++;
				}
			}
		}
		$data['no_rad'] = $data['count'] - $rad;
		$data['percent_no_rad'] = round($data['no_rad']/$data['count']*100);
		return $data;
	}

	protected function hedis_amm($pid)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Antidepressant Medication Management not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query1 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->first();
		if ($query1) {
			$search = array('celexa','opram','prozac','fluoxetine','lexapro','luvox','paroxetine','paxil','pexeva','sarafem','sertraline','symbyax','viibryd','zoloft','cymbalta','effexor','pristiq','venlafaxine','khedezla','ptyline','amoxapine','anafranil','pramine','doxepin','elavil','limbitrol','norpramin','pamelor','surmontil','tofranil','vivactil','emsam','marplan','nardil','parnate','tranylcypromine','bupropion','aplenzin','budeprion','maprotiline','mirtazapine','nefazodone','oleptro','remeron','serzone','trazodone','wellbutrin','forfivo');
			foreach ($search as $needle) {
				$pos = stripos($query1->rxl_medication, $needle);
				if ($pos !== false) {
					$score++;
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Antidepressant Medication Management performed';
			$data['goal'] = 'y';
		} else {
			$data['fix'][] = 'Antidepressant medication is recommended.';
		}
		return $data;
	}

	protected function hedis_add($pid, $date)
	{
		$data = array();
		$data['html'] = HTML::image('images/cancel.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Follow-Up Care for Children Prescribed ADHD Medication not performed';
		$data['goal'] = 'n';
		$data['fix'] = array();
		$score = 0;
		$query = DB::table('encounters')
			->where('pid', '=', $pid)
			->where('addendum', '=', 'n')
			->where('practice_id', '=', Session::get('practice_id'))
			->where('encounter_signed', '=', 'Yes')
			->where('encounter_DOS', '>=', $date)
			->get();
		if ($query) {
			foreach ($query as $row) {
				$query1 = DB::table('billing_core')
					->where('eid', '=', $row->eid)
					->where(function($query_array1) {
						$add_item_array = array('90791','90792','90804','90805','90806','90807','90808','90809','90810','90811','90812','90813','90814','90815','90832','90833','90834','90836','90837','90838','90839','90840','96150','96151','96152','96153','96154','98960','98961','98962','98966','98967','98968','99078','99201','99202','99203','99204','99205','99211','99212','99213','99214','99215','99217','99218','99219','99220','99241','99242','99243','99244','99245','99341','99342','99343','99344','99345','99347','99348','99349','99350','99383','99384','99393','99394','99401','99402','99403','99404','99411','99412','99441','99442','99443','99510');
						$i = 0;
						foreach ($add_item_array as $add_item) {
							if ($i == 0) {
								$query_array1->where('cpt', '=', $add_item);
							} else {
								$query_array1->orWhere('cpt', '=', $add_item);
							}
							$i++;
						}
					})
					->first();
				if ($query1) {
					$score++;
				}
			}
		}
		if ($score >= 1) {
			$data['html'] = HTML::image('images/button_accept.png', 'Status', array('border' => '0', 'height' => '20', 'width' => '20', 'style' => 'vertical-align:middle;')) . ' Follow-Up Care for Children Prescribed ADHD Medication performed';
			$data['goal'] = 'y';
		} else {
			$data['fix'][] = 'Encounters monitoring ADD needs to be performed';
		}
		return $data;
	}

	protected function hedis_audit($type, $function, $pid)
	{
		$html = '';
		$return = array();
		$demographics = DB::table('demographics')->where('pid', '=', $pid)->first();
		$dob = $this->human_to_unix($demographics->DOB);
		// ABA
		if ($dob <= $this->age_calc(18,'year') && $dob >= $this->age_calc(74,'year')) {
			$return['aba'] = $this->hedis_aba($pid);
		}
		// WCC
		if ($dob <= $this->age_calc(3,'year') && $dob >= $this->age_calc(18,'year')) {
			$return['wcc'] = $this->hedis_wcc($pid);
		}
		// CIS
		if ($dob >= $this->age_calc(3,'year')) {
			$return['cis'] = $this->hedis_cis($pid);
		}
		// IMA
		if ($dob <= $this->age_calc(13,'year') && $dob >= $this->age_calc(18,'year')) {
			$return['ima'] = $this->hedis_ima($pid);
		}
		// HPV
		if ($dob <= $this->age_calc(9,'year') && $dob >= $this->age_calc(13,'year') && $demographics->sex == 'f') {
			$return['hpv'] = $this->hedis_hpv($pid);
		}
		// LSC
		if ($dob >= $this->age_calc(2,'year')) {
			$return['lsc'] = $this->hedis_lsc($pid);
		}
		// BCS
		if ($dob <= $this->age_calc(40,'year') && $dob >= $this->age_calc(69,'year') && $demographics->sex == 'f') {
			$return['bcs'] = $this->hedis_bcs($pid);
		}
		// CCS
		if ($dob <= $this->age_calc(21,'year') && $dob >= $this->age_calc(64,'year') && $demographics->sex == 'f') {
			$return['ccs'] = $this->hedis_ccs($pid);
		}
		// COL
		if ($dob <= $this->age_calc(50,'year') && $dob >= $this->age_calc(75,'year')) {
			$return['col'] = $this->hedis_col($pid);
		}
		// CHL
		if ($dob <= $this->age_calc(16,'year') && $dob >= $this->age_calc(24,'year') && $demographics->sex == 'f') {
			$return['chl'] = $this->hedis_chl($pid);
		}
		// GSO
		if ($dob <= $this->age_calc(65,'year')) {
			$return['gso'] = $this->hedis_gso($pid);
		}
		// CWP
		$cwp_assessment_item_array = array('462','J02.9','034.0','J02.0','J03.00','074.0','B08.5','474.00','J35.01','099.51','A56.4','032.0','A36.0','472.1','J31.2','098.6','A54.5');
		$cwp_result = $this->hedis_assessment_query($pid, $type, $cwp_assessment_item_array);
		if ($cwp_result && $dob <= $this->age_calc(2,'year') && $dob >= $this->age_calc(18,'year')) {
			$return['cwp'] = $this->hedis_cwp($cwp_result);
		}
		// URI
		$uri_assessment_item_array = array('465.9','J06.9','487.1','J10.1','J11.1');
		$uri_result = $this->hedis_assessment_query($pid, $type, $uri_assessment_item_array);
		if ($uri_result && $dob <= $this->age_calc(3,'month') && $dob >= $this->age_calc(18,'year')) {
			$return['uri'] = $this->hedis_uri($uri_result);
		}
		// AAB
		$aab_assessment_item_array = array('466.0','J20.9');
		$aab_result = $this->hedis_assessment_query($pid, $type, $aab_assessment_item_array);
		if ($aab_result && $dob <= $this->age_calc(3,'month') && $dob >= $this->age_calc(18,'year')) {
			$return['aab'] = $this->hedis_uri($aab_result);
		}
		// SPR
		$spr_issues_item_array = array('496','J44.9');
		$spr_query = $this->hedis_issue_query($pid, $spr_issues_item_array);
		if ($spr_query && $dob <= $this->age_calc(40,'year')) {
			$return['spr'] = $this->hedis_spr($pid);
		}
		// PCE
		$pce_assessment_item_array = array('491.21','J44.1');
		$pce_result = $this->hedis_assessment_query($pid, $type, $pce_assessment_item_array);
		if ($pce_result && $dob <= $this->age_calc(40,'year')) {
			$return['pce'] = $this->hedis_pce($pce_result);
		}
		// ASM and AMR
		$asm_issues_item_array = array('493.90','J45.909','J45.998','493.00','J45.20','493.01','J45.22','493.02','J45.21','493.10','493.11','493.12','493.20','J44.9','493.21','J44.0','493.22','J44.1','493.81','J45.990','493.82','J45.991','493.91','J45.902','493.92','J45.901');
		$asm_query = $this->hedis_issue_query($pid, $asm_issues_item_array);
		if ($asm_query && $dob <= $this->age_calc(5,'year') && $dob >= $this->age_calc(56,'year')) {
			$return['asm'] = $this->hedis_asm($pid);
			$return['amr'] = $this->hedis_amr($pid);
		}
		// CMC and PBH
		$cmc_issues_item_array = array('410','I20','I21','I22','I23','I24','I25','414.8');
		$cmc_query = $this->hedis_issue_query($pid, $cmc_issues_item_array);
		if ($cmc_query && $dob <= $this->age_calc(18,'year') && $dob >= $this->age_calc(75,'year')) {
			$return['cmc'] = $this->hedis_cmc($pid);
		}
		if ($cmc_query && $dob <= $this->age_calc(18,'year')) {
			$return['pbh'] = $this->hedis_pbh($pid);
		}
		// CBP
		$cbp_issues_item_array = array('401','402','403','404','405','I10','I11','I12','I13','I15');
		$cbp_query = $this->hedis_issue_query($pid, $cbp_issues_item_array);
		if ($cbp_query && $dob <= $this->age_calc(18,'year') && $dob >= $this->age_calc(85,'year')) {
			$return['cbp'] = $this->hedis_cbp($pid);
		}
		// CDC
		$cdc_issues_item_array = array('250','E08','E09','E10','E11','E13');
		$cdc_query = $this->hedis_issue_query($pid, $cdc_issues_item_array);
		if ($cdc_query && $dob <= $this->age_calc(18,'year') && $dob >= $this->age_calc(75,'year')) {
			$return['cdc'] = $this->hedis_cdc($pid);
		}
		// ART
		$art_issues_item_array = array('714.0','M05','M06');
		$art_query = $this->hedis_issue_query($pid, $art_issues_item_array);
		if ($art_query) {
			$return['art'] = $this->hedis_art($pid);
		}
		// OMW
		$omw_assessment_item_array = array('800','801','802','803','804','805','806','807','808','809','810','811','812','813','814','815','816','817','818','819','820','821','822','823','824','825','826','827','828','829','S02','S12','S22','S32','S42','S52','S62','S72','S82','S92');
		$omw_result = $this->hedis_assessment_query($pid, $type, $omw_assessment_item_array);
		if ($omw_result && $dob <= $this->age_calc(67,'year') && $demographics->sex == 'f') {
			$return['omw'] = $this->hedis_omw($pid);
		}
		// LBP
		$lbp_assessment_item_array = array('724.2','M54.5');
		$lbp_result = $this->hedis_assessment_query($pid, $type, $lbp_assessment_item_array);
		if ($lbp_result) {
			$return['lbp'] = $this->hedis_lbp($lbp_result);
		}
		// AMM
		$amm_issues_item_array = array('311','296.2','296.3','F32','F33');
		$amm_query = $this->hedis_issue_query($pid, $amm_issues_item_array);
		if ($amm_query && $dob <= $this->age_calc(18,'year')) {
			$return['amm'] = $this->hedis_amm($pid);
		}
		// ADD
		$add_issues_item_array = array('314.0','F90');
		$add_query = $this->hedis_issue_query($pid, $add_issues_item_array);
		if ($add_query && $dob <= $this->age_calc(6,'year') && $dob >= $this->age_calc(12,'year')) {
			$return['add'] = $this->hedis_add($pid, $add_query->issue_date_active);
		}
		if (!empty($return)) {
			foreach ($return as $item => $row) {
				if ($item != 'cwp' && $item != 'uri' && $item != 'aab' && $item != 'pce' && $item != 'lbp') {
					$html .= $row['html'] . '<br>';
					if (!empty($row['fix'])) {
						$html .= '<strong>Fixes:</strong><ul>';
						foreach ($row['fix'] as $row1) {
							$html .= '<li>' . $row1 . '</li>';
						}
						$html .= '</ul>';
					}
				} else {
					if ($item == 'cwp') {
						$html .= '<strong>Appropriate Testing for Children With Pharyngitis:</strong>';
						$html .= '<ul><li>Percentage tested: ' . $row['percent_test'] . '</li>';
						$html .= '<li>Percentage treated with antibiotics: ' . $row['percent_abx'] . '</li>';
						$html .= '<li>Percentage treated with antibiotics without testing: ' . $row['percent_abx_no_test'] . '</li></ul>';
					}
					if ($item == 'uri') {
						$html .= '<strong>Appropriate Treatment for Children With Upper Respiratory Infection:</strong>';
						$html .= '<ul><li>Percentage treated with antibiotics: ' . $row['percent_abx'] . '</li></ul>';
					}
					if ($item == 'aab') {
						$html .= '<strong>Avoidance of Antibiotic Treatment for Adults with Acute Bronchitis:</strong>';
						$html .= '<ul><li>Percentage treated with antibiotics: ' . $row['percent_abx'] . '</li></ul>';
					}
					if ($item == 'pce') {
						$html .= '<strong>Pharmacotherapy Management of COPD Exacerbation:</strong>';
						$html .= '<ul><li>Percentage treated for COPD exacerbations: ' . $row['percent_tx'] . '</li></ul>';
					}
					if ($item == 'lbp') {
						$html .= '<strong>Use of Imaging Studies for Low Back Pain:</strong>';
						$html .= '<ul><li>Percentage of instances where no imaging study was performed for a diagnosis of low back pain: ' . $row['percent_no_rad'] . '</li></ul>';
					}
				}
				$html .= '<hr class="ui-state-default"/>';
			}
		}
		if ($function == 'chart') {
			return $html;
		} else {
			return $return;
		}
	}

	protected function parse_era_2100(&$return, $cb)
	{
		if ($return['loopid'] == '2110' || $return['loopid'] == '2100') {
			// Production date is posted with adjustments, so make sure it exists.
			if (!$return['production_date']) $return['production_date'] = $return['check_date'];
			// Force the sum of service payments to equal the claim payment
			// amount, and the sum of service adjustments to equal the CLP's
			// (charged amount - paid amount - patient responsibility amount).
			// This may result from claim-level adjustments, and in this case the
			// first SVC item that we stored was a 'Claim' type.  It also may result
			// from poorly reported payment reversals, in which case we may need to
			// create the 'Claim' service type here.
			$paytotal = $return['amount_approved'];
			$adjtotal = $return['amount_charged'] - $return['amount_approved'] - $return['amount_patient'];
			foreach ($return['svc'] as $svc) {
				$paytotal -= $svc['paid'];
				foreach ($svc['adj'] as $adj) {
					if ($adj['group_code'] != 'PR') $adjtotal -= $adj['amount'];
				}
			}
			$paytotal = round($paytotal, 2);
			$adjtotal = round($adjtotal, 2);
			if ($paytotal != 0 || $adjtotal != 0) {
				if ($return['svc'][0]['code'] != 'Claim') {
					array_unshift($return['svc'], array());
					$return['svc'][0]['code'] = 'Claim';
					$return['svc'][0]['mod']  = '';
					$return['svc'][0]['chg']  = '0';
					$return['svc'][0]['paid'] = '0';
					$return['svc'][0]['adj']  = array();
					$return['warnings'] .= "Procedure 'Claim' is inserted artificially to force claim balancing.\n";
				}
				$return['svc'][0]['paid'] += $paytotal;
				if ($adjtotal) {
					$j = count($return['svc'][0]['adj']);
					$return['svc'][0]['adj'][$j] = array();
					$return['svc'][0]['adj'][$j]['group_code']  = 'CR'; // presuming a correction or reversal
					$return['svc'][0]['adj'][$j]['reason_code'] = 'Balancing';
					$return['svc'][0]['adj'][$j]['amount'] = $adjtotal;
				}
				// if ($return['svc'][0]['code'] != 'Claim') {
				//   $return['warnings'] .= "First service item payment amount " .
				//   "adjusted by $paytotal due to payment imbalance. " .
				//   "This should not happen!\n";
				// }
			}
			$cb($return);
		}
	}

	function parse_era($era_string)
	{
		$return = array();
		$lines = explode('~', $era_string);
		$pos1 = strpos($era_string, '~');
		if ($pos1 !== false) {
			if (substr($lines[0], 0, 3) === 'ISA') {
				$element_delimiter = substr($lines[0], 3, 1);
				$sub_element_delimiter = substr($lines[0], -1);
				$return['loop_id'] = '';
				$return['st_segment_count'] = 0;
				$return['clp_segment_count'] = 0;
				foreach ($lines as $line) {
					$pos2 = strpos($line, $element_delimiter);
					if ($pos2 !== false) {
						$elements = explode($element_delimiter, $line);
						if ($elements[0] == 'ISA') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected ISA segment for ' . $return['loop_id'];
							} else {
								$return['isa_sender_id'] = trim($elements[6]);
								$return['isa_receiver_id'] = trim($elements[8]);
								$return['isa_control_number'] = trim($elements[13]);
							}
						} elseif ($elements[0] == 'GS') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected GS segment for ' . $return['loop_id'];
							} else {
								$return['gs_date'] = trim($elements[4]);
								$return['gs_time'] = trim($elements[5]);
								$return['gs_control_number'] = trim($elements[6]);
							}
						} elseif ($elements[0] == 'ST') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected ST segment for ' . $return['loop_id'];
							} else {
								//$this->parse_era_2100($return, $cb);
								$return['st_control_number'] = trim($elements[2]);
								$return['st_segment_count'] = 0;
							}
						} elseif ($elements[0] == 'BPR') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected BPR segment for ' . $return['loop_id'];
							} else {
								$return['check_amount'] = trim($elements[2]);
								$return['check_date'] = strtotime(trim($elements[16])); // converted to unix time
							}
						} elseif ($elements[0] == 'TRN') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected TRN segment for ' . $return['loop_id'];
							} else {
								$return['check_number'] = trim($elements[2]);
								$return['payer_tax_id'] = substr($elements[3], 1); // converted to 9 digits
								if (isset($elements[4])) {
									$return['payer_id'] = trim($elements[4]);
								}
							}
						} elseif ($elements[0] == 'DTM' && $elements[1] == '405') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected DTM/405 segment for ' . $return['loop_id'];
							} else {
								$return['production_date'] = strtotime(trim($elements[2])); // converted to unix time
							}
						} elseif ($elements[0] == 'N1' && $elements[1] == 'PR') {
							if ($return['loop_id'] != '') {
								$return['error'][] = 'Unexpected N1|PR segment for ' . $return['loop_id'];
							} else {
								$return['loop_id'] = '1000A';
								$return['payer_name'] = trim($elements[2]);
							}
						} elseif ($elements[0] == 'N3' && $return['loop_id'] == '1000A') {
							$return['payer_street'] = trim($elements[1]);
						} elseif ($elements[0] == 'N4' && $return['loop_id'] == '1000A') {
							$return['payer_city'] = trim($elements[1]);
							$return['payer_state'] = trim($elements[2]);
							$return['payer_zip'] = trim($elements[3]);
						} elseif ($elements[0] == 'N1' && $elements[1] == 'PE') {
							if ($return['loop_id'] != '1000A') {
								$return['error'][] = 'Unexpected N1|PE segment for ' . $return['loop_id'];
							} else {
								$return['loop_id'] = '1000B';
								$return['payee_name'] = trim($elements[2]);
								$return['payee_tax_id'] = trim($elements[4]);
							}
						} elseif ($elements[0] == 'N3' && $return['loop_id'] == '1000B') {
							$return['payee_street'] = trim($elements[1]);
						} elseif ($elements[0] == 'N4' && $return['loop_id'] == '1000B') {
							$return['payee_city']  = trim($elements[1]);
							$return['payee_state'] = trim($elements[2]);
							$return['payee_zip']   = trim($elements[3]);
						} elseif ($elements[0] == 'LX') {
							if (!$return['loop_id']) {
								$return['error'][] = 'Unexpected LX segment for ' . $return['loop_id'];
							} else {
								//$this->parse_era_2100($return, $cb);
								$return['loop_id'] = '2000';
							}
						} elseif ($elements[0] == 'CLP') {
							if (!$return['loop_id']) {
								$return['error'][] = 'Unexpected CLP segment for ' . $return['loop_id'];
							} else {
								//$this->parse_era_2100($return, $cb);
								$return['loop_id'] = '2100';
								// Clear some stuff to start the new claim:
								$claim_num = $return['clp_segment_count'];
								$return['clp_segment_count']++;
								$return['claim'][$claim_num]['subscriber_lastname']     = '';
								$return['claim'][$claim_num]['subscriber_firstname']     = '';
								$return['claim'][$claim_num]['subscriber_middle']     = '';
								$return['claim'][$claim_num]['subscriber_member_id'] = '';
								$return['claim'][$claim_num]['claim_forward'] = 0;
								$return['claim'][$claim_num]['item'] = array();
								$return['claim'][$claim_num]['bill_Box26'] = trim($elements[1]); // HCFA Box 26 pid_eid
								$return['claim'][$claim_num]['claim_status_code'] = trim($elements[2]);
								$return['claim'][$claim_num]['amount_charged'] = trim($elements[3]);
								$return['claim'][$claim_num]['amount_approved'] = trim($elements[4]);
								$return['claim'][$claim_num]['amount_patient'] = trim($elements[5]); // pt responsibility, copay + deductible
								$return['claim'][$claim_num]['payer_claim_id'] = trim($elements[7]); // payer's claim number
							}
						} elseif ($elements[0] == 'CAS' && $return['loop_id'] == '2100') {
							$return['adjustment'] = array();
							$i = 0;
							for ($k = 2; $k < 20; $k += 3) {
								if (!$elements[$k]) break;
								$return['claim'][$claim_num]['adjustment'][$i]['group_code'] = $elements[1];
								$return['claim'][$claim_num]['adjustment'][$i]['reason_code'] = $elements[$k];
								$return['claim'][$claim_num]['adjustment'][$i]['amount'] = $elements[$k+1];
								$i++;
							}
						} elseif ($elements[0] == 'NM1' && $elements[1] == 'QC' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['patient_lastname'] = trim($elements[3]);
							$return['claim'][$claim_num]['patient_firstname'] = trim($elements[4]);
							$return['claim'][$claim_num]['patient_middle'] = trim($elements[5]);
							$return['claim'][$claim_num]['patient_member_id'] = trim($elements[9]);
						} elseif ($elements[0] == 'NM1' && $elements[1] == 'IL' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['subscriber_lastname'] = trim($elements[3]);
							$return['claim'][$claim_num]['subscriber_firstname'] = trim($elements[4]);
							$return['claim'][$claim_num]['subscriber_middle'] = trim($elements[5]);
							$return['claim'][$claim_num]['subscriber_member_id'] = trim($elements[9]);
						} elseif ($elements[0] == 'NM1' && $elements[1] == '82' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['provider_lastname'] = trim($elements[3]);
							$return['claim'][$claim_num]['provider_firstname'] = trim($elements[4]);
							$return['claim'][$claim_num]['provider_middle'] = trim($elements[5]);
							$return['claim'][$claim_num]['provider_member_id'] = trim($elements[9]);
						} elseif ($elements[0] == 'NM1' && $elements[1] == 'TT' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['claim_forward'] = 1; // claim automatic forward case to another payer.
						} elseif ($elements[0] == 'REF' && $elements[1] == '1W' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['claim_comment'] = trim($elements[2]);
						} elseif ($elements[0] == 'DTM' && $elements[1] == '050' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['claim_date'] = strtotime(trim($elements[2])); // converted to unix time
						} else if ($elements[0] == 'PER' && $return['loop_id'] == '2100') {
							$return['claim'][$claim_num]['payer_insurance'] = trim($elements[2]);
						} else if ($elements[0] == 'SVC') {
							if (!$return['loop_id']) {
								$return['error'][] = 'Unexpected SVC segment for ' . $return['loop_id'];
							} else {
								$return['loop_id'] = '2110';
								if (isset($elements[6])) {
									$item = explode($sub_element_delimiter, $elements[6]);
								} else {
									$item = explode($sub_element_delimiter, $elements[1]);
								}
								if ($item[0] != 'HC') {
									$return['error'][] = 'item segment has unexpected qualifier';
								}
								if (isset($return['claim'][$claim_num]['item'])) {
									$l = count($return['claim'][$claim_num]['item']);
								} else {
									$l = 0;
								}
								$return['claim'][$claim_num]['item'][$l] = array();
								if (strlen($item[1]) == 7 && empty($item[2])) {
									$return['claim'][$claim_num]['item'][$l]['cpt'] = substr($item[1], 0, 5);
									$return['claim'][$claim_num]['item'][$l]['modifier'] = substr($item[1], 5);
								} else {
									$return['claim'][$claim_num]['item'][$l]['cpt'] = $item[1];
									$return['claim'][$claim_num]['item'][$l]['modifier'] = isset($item[2]) ? $item[2] . ':' : '';
									$return['claim'][$claim_num]['item'][$l]['modifier'] .= isset($item[3]) ? $item[3] . ':' : '';
									$return['claim'][$claim_num]['item'][$l]['modifier'] .= isset($item[4]) ? $item[4] . ':' : '';
									$return['claim'][$claim_num]['item'][$l]['modifier'] .= isset($item[5]) ? $item[5] . ':' : '';
									$return['claim'][$claim_num]['item'][$l]['modifier'] = preg_replace('/:$/','',$return['claim'][$claim_num]['item'][$l]['modifier']);
								}
								$return['claim'][$claim_num]['item'][$l]['charge'] = $elements[2];
								$return['claim'][$claim_num]['item'][$l]['paid'] = $elements[3];
								$return['claim'][$claim_num]['item'][$l]['adjustment'] = array();
							}
						} elseif ($elements[0] == 'DTM' && $return['loop_id'] == '2110') {
							$return['claim'][$claim_num]['dos'] = strtotime(trim($elements[2])); // converted to unix time
						} elseif ($elements[0] == 'CAS' && $return['loop_id'] == '2110') {
							$m = count($return['claim'][$claim_num]['item']) - 1;
							for ($n = 2; $n < 20; $n += 3) {
								if (!isset($elements[$n])) break;
								if ($elements[1] == 'CO' && $elements[$n+1] < 0) {
									$elements[$n+1] = 0 - $elements[$n+1];
								}
								$o = count($return['claim'][$claim_num]['item'][$m]['adjustment']);
								$return['claim'][$claim_num]['item'][$m]['adjustment'][$o] = array();
								$return['claim'][$claim_num]['item'][$m]['adjustment'][$o]['group_cpt']  = $elements[1];
								$return['claim'][$claim_num]['item'][$m]['adjustment'][$o]['reason_cpt'] = $elements[$n];
								$return['claim'][$claim_num]['item'][$m]['adjustment'][$o]['amount'] = $elements[$n+1];
							}
						} elseif ($elements[0] == 'AMT' && $elements[1] == 'B6' && $return['loop_id'] == '2110') {
							$p = count($return['claim'][$claim_num]['item']) - 1;
							$return['claim'][$claim_num]['item'][$p]['allowed'] = $elements[2];
						} elseif ($elements[0] == 'LQ' && $elements[1] == 'HE' && $return['loop_id'] == '2110') {
							$q = count($return['claim'][$claim_num]['item']) - 1;
							$return['claim'][$claim_num]['item'][$q]['remark'] = $elements[2];
						} elseif ($elements[0] == 'PLB') {
							for ($r = 3; $r < 15; $r += 2) {
								if (!$elements[$r]) break;
								$return['plb'] .= 'PROVIDER LEVEL ADJUSTMENT (not claim-specific): $' .
									sprintf('%.2f', $elements[$r+1]) . " with reason cpt " . $elements[$r] . "\n";
							}
						} elseif ($elements[0] == 'SE') {
							//$this->parse_era_2100($return, $cb);
							$return['loop_id'] = '';
							if ($return['st_control_number'] != trim($elements[2])) {
								return 'Ending transaction set control number mismatch';
							}
							if (($return['st_segment_count'] + 1) != trim($elements[1])) {
								return 'Ending transaction set segment count mismatch';
							}
						} elseif ($elements[0] == 'GE') {
							if ($return['loop_id']) {
								$return['error'][] = 'Unexpected GE segment';
							}
							if ($return['gs_control_number'] != trim($elements[2])) {
								$return['error'][] = 'Ending functional group control number mismatch';
							}
						} elseif ($elements[0] == 'IEA') {
							if ($return['loop_id']) {
								$return['error'][] = 'Unexpected IEA segment';
							}
							if ($return['isa_control_number'] != trim($elements[2])) {
								$return['error'][] = 'Ending interchange control number mismatch';
							}
						} else {
							$return['error'][] = 'Unknown or unexpected segment ID ' . $elements[0];
						}
					} else {
						$return['error'][] = 'Error reading line ' . $return['st_segment_count'] + 1;
					}
					$return['st_segment_count']++;
				}
				if ($elements[0] != 'IEA') {
					$return['error'][] = 'Premature end of ERA file';
				}
			} else {
				$return['invalid'] = 'First line is not an ISA segment, unable to read the file.';
			}
		} else {
			$return['invalid'] = 'This is not a valid EDI 835 file!';
		}
		return $return;
	}

	protected function claim_reason_code($code)
	{
		$url = 'http://www.wpc-edi.com/reference/codelists/healthcare/claim-adjustment-reason-codes/';
		$html = new Htmldom($url);
		$table = $html->find('table[id=codelist]',0);
		$description = '';
		foreach ($table->find('tr[class=current]') as $row) {
			$code_row = $row->find('td[class=code]',0);
			$description_row = $row->find('td[class=description]',0);
			$date_row = $row->find('span[class=dates]',0);
			if ($code == $code_row->innertext) {
				$description = $description_row->plaintext;
				$date = $date_row->plaintext;
				$description = trim(str_replace($date, '', $description));
				break;
			}
		}
		if ($description == '') {
			return $code . ', Code unknown';
		} else {
			return $description;
		}
	}

	public function send_api_data($url, $data, $username, $password)
	{
		if (is_array($data)) {
			$data_string = json_encode($data);
		} else {
			$data_string = $data;
		}
		$ch = curl_init($url);
		if ($username != '') {
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		}
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		$result_arr = json_decode($result, true);
		if(curl_errno($ch)){
			$result_arr['url_error'] = 'Error:' . curl_error($ch);
		} else {
			$result_arr['url_error'] = '';
		}
		curl_close($ch);
		return $result_arr;
	}

	public function api_sync_data()
	{
		$check = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->whereNotNull('api_key')->first();
		if ($check) {

		}
	}

	public function api_data($action, $table, $primary, $id)
	{
		$check = DB::table('demographics_relate')->where('pid', '=', Session::get('pid'))->whereNotNull('api_key')->first();
		if ($check) {
			$row = DB::table($table)->where($primary, '=', $id)->first();
			$row_data = (array) $row;
			unset($row_data[$primary]);
			$remote_id = '0';
			$proceed = true;
			if ($action == 'update' || $action == 'delete') {
				$check1 = DB::table('api_queue')
					->where('table', '=', $table)
					->where('local_id', '=', $id)
					->where('remote_id', '!=', '0')
					->where('action', '!=', 'delete')
					->first();
				if ($check1) {
					$remote_id = $check1->remote_id;
				} else {
					if ($action == 'delete') {
						$action = 'add';
					} else {
						$proceed = false;
					}
				}
			}
			$json_data = array(
				'api_key' => $check->api_key,
				'table' => $table,
				'primary' => $primary,
				'remote_id' => $remote_id,
				'action' => $action,
				'data' => $row_data
			);
			$json = serialize(json_encode($json_data));
			$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
			$login_data = array(
				'api_key' => $check->api_key,
				'npi' => $practice->npi
			);
			$login = serialize(json_encode($login_data));
			$data = array(
				'table' => $table,
				'primary' => $primary,
				'local_id' => $id,
				'remote_id' => $remote_id,
				'action' => $action,
				'json' => $json,
				'login' => $login,
				'url' => $check->url,
				'api_key' => $check->api_key
			);
			if ($proceed == true) {
				DB::table('api_queue')->insert($data);
				$this->audit('Add');
			}
		}
	}

	// FHIR base controller functions
	protected function composite_array($value)
	{
		$return_value = array(
			'value' => $value,
			'parameter' => ''
		);
		$value_composite = explode("\$", $value);
		if (count($value_composite) == 2) {
			if (substr($value_composite[0], -1) != "\\") {
				$return_value['value'] = $value_composite[1];
				$return_value['parameter'] = $value_composite[0];
			}
		}
		return $return_value;
	}

	protected function query_build($query, $table_key, $key1, $comparison, $value1, $or, $resource, $table)
	{
		$proceed = false;
		// check if resource is a condition and clean up identifier values if present
		if ($resource == 'Condition') {
			if ($key1 == 'identifier') {
				if (strpos($value1, 'issue_id_') >= 0 && $table == 'issues') {
					$value1 = str_replace('issue_id_', '', $value1);
					$proceed = true;
				}
				if (strpos($value1, 'eid_') >= 0 && $table == 'assessment') {
					$value1 = str_replace('eid_', '', $value1);
					$proceed = true;
				}
			}
		} else {
			$proceed = true;
		}
		// check if resource is medication statement and clean up references
		if ($resource == 'MedicationStatement') {
			if ($key1 == 'status') {
				if ($value1 == 'active') {
					$query->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00');
				}
				if ($value1 == 'completed') {
					$query->where('rxl_date_inactive', '!=', '0000-00-00 00:00:00');
				}
				if ($value1 == 'entered-in-error') {
					//not functional
				}
				if ($value1 == 'intended') {
					//not functional
				}
			}
		} else {
			$proceed = true;
		}
		if ($proceed == true) {
			// check if value is a date
			$unixdate = strtotime($value1);
			if ($unixdate) {
				if ($key1 == 'birthDate') {
					$value1 = date('Y-m-d', $unixdate);
				}
			}
			// check if value is boolean
			if ($value1 == 'true') {
				$value1 = '1';
			}
			if ($value1 == 'false') {
				$value1 = '0';
			}
			if (isset($table_key[$key1]) && is_array($table_key[$key1])) {
				if ($or == false) {
					$query->where(function($query_array1) use ($table_key, $key1, $value1) {
						$a = 0;
						foreach ($table_key[$key1] as $key_name) {
							if ($a == 0) {
								$query_array1->where($key_name, 'LIKE', "%$value1%");
							} else {
								$query_array1->orWhere($key_name, 'LIKE', "%$value1%");
							}
							$a++;
						}
					});
				} else {
					$query->orWhere(function($query_array1) use ($table_key, $key1, $value1) {
						$a = 0;
						foreach ($table_key[$key1] as $key_name) {
							if ($a == 0) {
								$query_array1->where($key_name, 'LIKE', "%$value1%");
							} else {
								$query_array1->orWhere($key_name, 'LIKE', "%$value1%");
							}
							$a++;
						}
					});
				}
			} else {
				if ($key1 == 'subject') {
					if ($resource == 'Patient') {
						$key_name = 'pid';
					} else {
						$key_name = $table_key[$key1];
					}
				} else {
					$key_name = $table_key[$key1];
				}
				if ($or == false) {
					if ($comparison == '=') {
						$query->where($key_name, 'LIKE', "%$value1%");
					} else {
						$query->where($key_name, $comparison, $value1);
					}
				} else {
					if ($comparison == '=') {
						$query->orWhere($key_name, 'LIKE', "%$value1%");
					} else {
						$query->orWhere($key_name, $comparison, $value1);
					}
				}
			}
		}
		return $query;
	}

	// $data is Input array, $table_key is key translation for associated table, $is_date is boolean
	protected function resource_translation($data, $table, $table_primary_key, $table_key)
	{
		$i = 0;
		$parameters = array();
		foreach ($data as $key => $value) {
			if ($key!='page' && $key !='sort') {
				$exact = false;
				$missing = false;
				$text = false;
				$resource = '';
				$comparison = '=';
				$namespace = '';
				$unit = '';
				$either = false;
				$key_modifier = explode(':', $key);
				if (count($key_modifier) == 2) {
					$key1 = $key_modifier[0];
					if ($key_modifier[1] == 'exact' || $key_modifier[1] == 'missing' || $key_modifier[1] == 'text') {
						if ($key_modifier[1] == 'exact') {
							$exact = true;
						}
						if ($key_modifier[1] == 'missing') {
							$missing = true;
						}
						if ($key_modifier[1] == 'text') {
							$text = true;
						}
					} else {
						$resource = $key_modifier[1];
					}
				} else {
					$key1 = $key;
				}
				$parameters[$i]['parameter'] = $key1;
				$parameters[$i]['value'] = $value;
				$char1 = substr($value, 0, 1);
				$char2 = substr($value, 0, 2);
				if ($char1 == '<' || $char1 == '>' || $char1 == '~') {
					$comparison = $char1;
					$value = substr($value, 1);
				}
				if ($char1 == '~') {
					$comparison = 'LIKE';
					$like_value = substr($value, 1);
					$value = "%$like_value%";
				}
				if ($char2 == '<=' || $char2 == '>=') {
					$comparison = $char2;
					$value = substr($value, 2);
				}
				$value_token = explode('|', $value);
				if (count($value_token) == 2) {
					if (substr($value_token[0], -1) == "\\") {
						$value1 = $value;
					} else {
						$value1 = $value_token[1];
						$namespace = $value_token[0];
					}
				} elseif (count($value_token) == 3) {
					if (substr($value_token[0], -1) == "\\" || substr($value_token[1], -1) == "\\") {
						if (substr($value_token[0], -1) == "\\" && substr($value_token[1], -1) == "\\") {
							$value1 = str_replace("\\|", "|", $value);
						} else {
							if (substr($value_token[0], -1) == "\\") {
								$value1 = $value_token[2];
								$namespace = $value_token[0] . "|" . $value_token[1];
							} else {
								$value1 = $value_token[1] . "|" . $value_token[2];
								$namespace = $value_token[0];
							}
						}
					} else {
						$value1 = $value_token[0];
						$namespace = $value_token[1];
						$unit = $value_token[2];
					}
				} else {
					$value1 = $value;
				}
				if ($i == 0) {
					$query = DB::table($table);
				} else {
					$this->query_build($query, $table_key, $key1, $comparison, $value1, false, $resource, $table);
				}
				$value_composite1 = explode(",", $value1);
				if (count($value_composite1) > 1) {
					$code = array();
					$temp_value = '';
					$j = 0;
					foreach ($value_composite1 as $value2) {
						if (substr($value2, -1) == "\\") {
							if ($temp_value != '') {
								$value3 = $this->composite_array($value2);
								if ($value3['parameter'] == '') {
									$temp_value .= ',' . $value2;
								} else {
									$temp_value .= ',' . $value3['value'];
								}
							} else {
								$value3 = $this->composite_array($value2);
								if ($value3['parameter'] == '') {
									$temp_value = $value2;
								} else {
									$temp_value = $value3['value'];
								}
							}
						} else {
							if ($temp_value == '') {
								$value3 = $this->composite_array($value2);
								if ($value3['parameter'] == '') {
									$code[$j] = [
										'key' => $key1,
										'comparison' => $comparison,
										'value' => $value2
									];

								} else {
									$code[$j] = [
										'key' => $key1,
										'comparison' => $comparison,
										'value' => $value3['value']
									];
								}
							} else {
								$value3 = $this->composite_array($value2);
								if ($value3['parameter'] == '') {
									$temp_value .= ',' . $value2;
								} else {
									$temp_value .= ',' . $value3['value'];
								}
								$code[$j] = [
									'key' => $key1,
									'comparison' => $comparison,
									'value' => $temp_value
								];
								$temp_value = '';
							}
							$j++;
						}
					}
					if (isset($code[0])) {
						$query->where(function($query_array1) use ($code, $table_key, $resource, $table) {
							$k = 0;
							foreach ($code as $line) {
								if ($k == 0) {
									$this->query_build($query_array1, $table_key, $line['key'], $line['comparison'], $line['value'], false, $resource, $table);
								} else {
									$this->query_build($query_array1, $table_key, $line['key'], $line['comparison'], $line['value'], true, $resource, $table);
								}
								$k++;
							}
						});
					} else {
						$this->query_build($query, $table_key, $key1, $comparison, $temp_value, false, $resource, $table);
					}
				} else {
					$this->query_build($query, $table_key, $key1, $comparison, $value1, false, $resource, $table);
				}
				$i++;
			}
		}
		$query->select($table_primary_key);
		$result = $query->get();
		if ($result) {
			$return['response'] = true;
			$url_array = explode('/', Request::url());
			$return['parameters'][] = [
				'url' => array_splice($url_array, -1, 1, 'query#_type'),
				'valueString' => strtolower(end($url_array))
			];
			$return['total'] = count($result);
			foreach ($parameters as $parameter) {
				$new_url = array_splice($url_array, -1, 1, 'query#' . $parameter['parameter']);
				$return['parameters'][] = [
					'url' => $new_url,
					'valueString' => $parameter['value']
				];
			}
			$return['data'] = array();
			foreach ($result as $result_row) {
				$result_row_array = (array) $result_row;
				$return['data'][] = reset($result_row_array);
			}
		} else {
			$return['response'] = false;
		}
		return $return;
	}

	protected function resource_detail($row, $resource_type)
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$response['resourceType'] = $resource_type;
		$response['text']['status'] = 'generated';
		// Patient
		if ($resource_type == 'Patient') {
			$response['text']['div'] = '<div><table><tbody>';
			$response['identifier'][] = [
				'use' => 'usual',
				'label' => 'MRN',
				'system' => 'urn:oid:1.2.36.146.595.217.0.1',
				'value' => $row->pid,
				'period' => [
					'start' => date('Y-m-d')
				],
				'assigner' => [
					'display' => $practice->practice_name
				]
			];
			$response['name'][] = [
				'use' => 'official',
				'family' => [$row->lastname],
				'given' => [$row->firstname],
			];
			$response['text']['div'] .= '<tr><td><b>Name</b></td><td>' . $row->firstname . ' ' . $row->lastname .'</td></tr>';
			if ($row->nickname != '') {
				$response['name'][] = [
					'use' => 'usual',
					'given' => [$row->nickname]
				];
			}
			if ($row->phone_home != '') {
				$response['telecom'][] = [
					'use' => 'home',
					'value' => $row->phone_home,
					'system' => 'phone'
				];
				$response['text']['div'] .= '<tr><td><b>Telcom, Home</b></td><td>' . $row->phone_home .'</td></tr>';
			}
			if ($row->phone_work != '') {
				$response['telecom'][] = [
					'use' => 'work',
					'value' => $row->phone_work,
					'system' => 'phone'
				];
				$response['text']['div'] .= '<tr><td><b>Telcom, Work</b></td><td>' . $row->phone_work .'</td></tr>';
			}
			if ($row->sex == 'f') {
				$gender = 'F';
				$gender_full = 'Female';
			} elseif ($row->sex == 'm') {
				$gender = 'M';
				$gender_full = 'Male';
			} else {
				$gender = 'UN';
				$gender_full = 'Undifferentiated';
			}
			$response['gender']['coding'][] = [
				'system' => "http://hl7.org/fhir/v3/AdministrativeGender",
				'code' => $gender,
				'display' => $gender_full
			];
			$response['text']['div'] .= '<tr><td><b>Gender</b></td><td>' . $gender_full .'</td></tr>';
			$birthdate = date('Y-m-d', $this->human_to_unix($row->DOB));
			$response['birthDate'] = $birthdate;
			$response['text']['div'] .= '<tr><td><b>Birthdate</b></td><td>' . $birthdate .'</td></tr>';
			$response['deceasedBoolean'] = false;
			$response['address'][] = [
				'use' => 'home',
				'line' => [$row->address],
				'city' => $row->city,
				'state' => $row->state,
				'zip' => $row->zip
			];
			$response['text']['div'] .= '<tr><td><b>Address</b></td><td>' . $row->address . ', ' . $row->city . ', ' . $row->state . ', ' . $row->zip . '</td></tr>';
			$response['contact'][0]['relationship'][0]['coding'][0] = [
				'system' => "http://hl7.org/fhir/patient-contact-relationship",
				'code' => $row->guardian_relationship
			];
			$response['contact'][0]['name'] = [
				'family' => [$row->guardian_lastname],
				'given' => [$row->guardian_firstname]
			];
			$response['contact'][0]['telecom'][] = [
				'system' => 'phone',
				'value' => $row->guardian_phone_home
			];
			$response['text']['div'] .= '<tr><td><b>Contacts</b></td><td>' . $row->guardian_firstname . ' ' . $row->guardian_lastname . ', Phone: ' . $row->guardian_phone_home . ', Relationship: ' . $row->guardian_relationship . '</td></tr>';
			$response['managingOrganization'] = [
				'reference' => 'Organization/1'
			];
			if ($row->active == '0') {
				$response['active'] = false;
			} else {
				$response['active'] = true;
			}
			$response['text']['div'] .= '</tbody></table></div>';
		}

		// Condition
		if ($resource_type == 'Condition') {
			$patient = DB::table('demographics')->where('pid', '=', $row->pid)->first();
			$response['patient'] = [
				'reference' => 'Patient/' . $row->pid,
				'display' => $patient->firstname . ' ' . $patient->lastname
			];
			if ($practice->icd == '9') {
				$condition_system = 'http://hl7.org/fhir/sid/icd-9';
			} else {
				$condition_system = 'http://hl7.org/fhir/sid/icd-10';
			}
			if (isset($row->eid)) {
				$response['encounter'] = [
					'reference' => 'Encounter/' . $row->eid
				];
				$response['id'] = 'eid_' . $row->eid;
				$provider = DB::table('users')->where('displayname', '=', $row->encounter_provider)->first();
				$response['dateRecorded'] = date('Y-m-d', $this->human_to_unix($row->assessment_date));
				$i = 1;
				while ($i <= 12) {
					$condition_row_array = (array) $row;
					if ($condition_row_array['assessment_' . $i] != '') {
						$code_array = explode(' [', $condition_row_array['assessment_' . $i]);
						$response['code']['coding'][] = [
							'system' => $condition_system,
							'code' => str_replace(']', '', $code_array[1]),
							'display' => $code_array[0]
						];
						$response['text']['div'] = '<div>' . $condition_row_array['assessment_' . $i] . ', <a href="' . route('home') . '/fhir/Encounter/' . $row->eid .'">Encounter Assessment</a>, Date Active: ' . date('Y-m-d', $this->human_to_unix($row->assessment_date)) . '</div>';
					}
					$i++;
				}
				$response['category']['coding'][] = [
					'system' => 'http://hl7.org/fhir/condition-category',
					'code' => 'diagnosis',
					'display' => 'Diagnosis'
				];
			} else {
				$response['id'] = 'issue_id_' . $row->issue_id;
				$provider = DB::table('users')->where('displayname', '=', $row->issue_provider)->first();
				$response['dateRecorded'] = date('Y-m-d', $this->human_to_unix($row->issue_date_active));
				$response['onsetDateTime'] = date('Y-m-d', $this->human_to_unix($row->issue_date_active));
				$code_array = explode(' [', $row->issue);
				$response['code']['coding'][] = [
					'system' => $condition_system,
					'code' => str_replace(']', '', $code_array[1]),
					'display' => $code_array[0]
				];
				$response['text']['div'] = '<div>' . $row->issue . ', Problem, Date Active: ' . date('Y-m-d', $this->human_to_unix($row->issue_date_active)) . '</div>';
				$response['category']['coding'][] = [
					'system' => 'http://snomed.info/sct',
					'code' => '55607006',
					'display' => 'Problem'
				];
			}
			$response['asserter'] = [
				'reference' => 'Practitioner/' . $provider->id,
				'display' => $provider->displayname
			];
			$response['status'] = 'confirmed';
			// missing severity
			// missing evidence
			// missing location
			// missing relatedItem
		}

		// MedicationStatement
		if ($resource_type == 'MedicationStatement') {
			$response['id'] = $row->rxl_id;
			$patient = DB::table('demographics')->where('pid', '=', $row->pid)->first();
			$response['patient'] = [
				'reference' => 'Patient/' . $row->pid,
				'display' => $patient->firstname . ' ' . $patient->lastname
			];
			$provider = DB::table('users')->where('displayname', '=', $row->rxl_provider)->first();
			if ($provider) {
				$response['recorder'] = [
					'reference' => 'Practitioner/' . $provider->id,
					'display' => $row->rxl_provider
				];
			}
			$response['dateAsserted'] = date('Y-m-d');
			$response['effectiveDateTime'] = date('Y-m-d', $this->human_to_unix($row->rxl_date_active));
			if ($row->rxl_ndcid != '') {
				$rxnormapi = new RxNormApi();
				$rxnormapi->output_type = 'json';
				$rxnorm = json_decode($rxnormapi->findRxcuiById("NDC", $row->rxl_ndcid), true);
				if (isset($rxnorm['idGroup']['rxnormId'][0])) {
					$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($rxnorm['idGroup']['rxnormId'][0]), true);
					$response['medicationReference'] = [
						'reference' => 'Medication/' . $row->rxl_ndcid,
						'display' => $rxnorm1['properties']['name']
					];
				}
			}
			$med_prn_array = array("as needed", "PRN");
			if ($row->rxl_sig == '') {
				$response['text']['div'] = '<div>' . $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_instructions . ' for ' . $row->rxl_reason . '</div>';
				$dosage_text = $row->rxl_instructions . ' for ' . $row->rxl_reason;
				$asNeededBoolean = false;
				if (in_array($med_row->rxl_instructions, $med_prn_array)) {
					$asNeededBoolean = true;
				}
				$dosage_array = [
					'text' => $dosage_text,
					'asNeededBoolean' => $asNeededBoolean,
					'quantityQuantity' => [
						'value' => $row->rxl_quantity
					]
				];
			} else {
				$response['text']['div'] = '<div>' . $row->rxl_medication . ' ' . $row->rxl_dosage . ' ' . $row->rxl_dosage_unit . ', ' . $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency . ' for ' . $row->rxl_reason . '</div>';
				$dosage_text = $row->rxl_sig . ' ' . $row->rxl_route . ' ' . $row->rxl_frequency . ' for ' . $row->rxl_reason;
				$med_dosage_parts = explode(" ", $row->rxl_sig);
				$med_dosage = $med_dosage_parts[0];
				if (count($med_dosage_parts) > 1) {
					$med_dosage_unit = $med_dosage_parts[1];
				} else {
					$med_dosage_unit = '';
				}
				$med_code = '';
				$med_code_description = '';
				if ($row->rxl_route == "by mouth") {
					$med_code = "C1522409";
					$med_code_description = "Oropharyngeal Route of Administration";
				}
				if ($row->rxl_route == "per rectum") {
					$med_code = "C1527425";
					$med_code_description = "Rectal Route of Administration";
				}
				if ($row->rxl_route == "transdermal") {
					$med_code = "C0040652";
					$med_code_description = "Transdermal Route of Administration";
				}
				if ($row->rxl_route == "subcutaneously") {
					$med_code = "C1522438";
					$med_code_description = "Subcutaneous Route of Administration";
				}
				if ($row->rxl_route == "intravenously") {
					$med_code = "C2960476";
					$med_code_description = "Intravascular Route of Administration";
				}
				if ($row->rxl_route == "intramuscularly") {
					$med_code = "C1556154";
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
				if (in_array($row->rxl_frequency, $med_freq_array_1)) {
					$med_period = "24";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_2)) {
					$med_period = "12";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_3)) {
					$med_period = "8";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_4)) {
					$med_period = "6";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_5)) {
					$med_period = "4";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_6)) {
					$med_period = "3";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_7)) {
					$med_period = "2";
				}
				if (in_array($row->rxl_frequency, $med_freq_array_8)) {
					$med_period = "1";
				}
				$asNeededBoolean = false;
				if (in_array($row->rxl_frequency, $med_prn_array)) {
					$asNeededBoolean = true;
				}
				$dosage_array = [
					'text' => $dosage_text,
					'asNeededBoolean' => $asNeededBoolean,
					'quantityQuantity' => [
						'value' => $row->rxl_quantity
					]
				];
				if ($med_period != '') {
					$dosage_array['timing'] = [
						'repeat' => [
							'frequency' => $med_period,
							'period' => '1',
							'periodUnits' => 'd'
						]
					];
				}
				if ($med_code != '' && $med_code_description != '') {
					$dosage_array['route'] = [
						'coding' => [
							'0' => [
								'system' => 'http://ncimeta.nci.nih.gov',
								'code' => $med_code,
								'display' => $med_code_description
							]
						]
					];
				}
			}
			if ($row->rxl_date_inactive == '0000-00-00 00:00:00' && $row->rxl_date_old == '0000-00-00 00:00:00') {
				$response['status'] = 'active';
				$response['wasNotTaken'] = false;
			}
			if ($row->rxl_date_inactive != '0000-00-00 00:00:00') {
				$response['status'] = 'completed';
				$response['wasNotTaken'] = true;
			}
			$response['dosage'][] = $dosage_array;
		}
		// AllergyIntolerance
		if ($resource_type == 'AllergyIntolerance') {
			$response['id'] = $row->allergies_id;
			$patient = DB::table('demographics')->where('pid', '=', $row->pid)->first();
			$response['patient'] = [
				'reference' => 'Patient/' . $row->pid,
				'display' => $patient->firstname . ' ' . $patient->lastname
			];
			$provider = DB::table('users')->where('displayname', '=', $row->allergies_provider)->first();
			if ($provider) {
				$response['recorder'] = [
					'reference' => 'Practitioner/' . $provider->id,
					'display' => $row->allergies_provider
				];
			}
			$response['recordedDate'] = date('Y-m-d', $this->human_to_unix($row->allergies_date_active));
			$rxnormapi = new RxNormApi();
			$rxnormapi->output_type = 'json';
			$rxnorm = json_decode($rxnormapi->findRxcuiByString($row->allergies_med), true);
			$rxnorm1 = array();
			if (isset($rxnorm['idGroup']['rxnormId'][0])) {
				$rxnorm1 = json_decode($rxnormapi->getRxConceptProperties($rxnorm['idGroup']['rxnormId'][0]), true);
				$response['substance']['coding'][] = [
					'system' => 'http://www.nlm.nih.gov/research/umls/rxnorm',
					'code' => $rxnorm['idGroup']['rxnormId'][0],
					'display' => $rxnorm1['properties']['name']
				];
			} else {
				$response['substance']['text'] = $row->allergies_med;
			}
			$response['text']['div'] = '<div>' . $row->allergies_med . ', Reaction: ' . $row->allergies_reaction . ', Severeity ' . $row->allergies_severity . '</div>';
			$response['reaction'][] = [
				'manifestation' => [
					'0' => [
						'coding' => [
							'0' => [
								'system' => 'http://snomed.info/sct',
								'code' => '', //need code
								'display' => '' //need definition
							]
						]
					]
				]
			];
		}
		// Immunization
		if ($resource_type == 'Immunization') {
			$response['id'] = $row->imm_id;
			$patient = DB::table('demographics')->where('pid', '=', $row->pid)->first();
			$response['patient'] = [
				'reference' => 'Patient/' . $row->pid,
				'display' => $patient->firstname . ' ' . $patient->lastname
			];
			$provider = DB::table('users')->where('displayname', '=', $row->imm_provider)->first();
			if ($provider) {
				$response['requester'] = [
					'reference' => 'Practitioner/' . $provider->id,
					'display' => $row->imm_provider
				];
			}
			if ($row->imm_cvxcode != '') {
				$response['vaccineCode']['coding'][] = [
					'system' => 'http://hl7.org/fhir/sid/cvx',
					'code' => $row->imm_cvxcode,
					'display' => $row->imm_immunization
				];
			} else {
				$response['vaccineCode']['text'] = $row->imm_immunization;
			}
			$response['date'] = date('Y-m-d', $this->human_to_unix($row->imm_date));
			$response['status'] = 'completed';
			$response['wasNotGiven'] = false;
			if ($row->imm_lot != '') {
				$response['lotNumber'] = $row->imm_lot;
			}
			if ($row->imm_expiration != '') {
				$response['expirationDate'] = date('Y-m-d', $this->human_to_unix($row->imm_expiration));
			}
			if ($row->imm_sequence != '') {
				$response['vaccinationProtocol'][] = [
					'doseSequence' => $row->imm_sequence
				];
			}
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
			$response['text']['div'] = '<div>' . $row->imm_immunization . $sequence . ', Given: ' . date('Y-m-d', $this->human_to_unix($row->imm_date)) . '</div>';
		}
		return $response;
	}

	protected function encounter_template_names_array()
	{
		$array = array(
			'standardmedical' => 'Standard Medical Visit V1',
			'standardmedical1' => 'Standard Medical Visit V2',
			'clinicalsupport' => 'Clinical Support Visit',
			'standardpsych' => 'Annual Psychiatric Evaluation',
			'standardpsych1' => 'Psychiatric Encounter',
			'standardmtm' => 'MTM Encounter'
		);
		return $array;
	}

	protected function full_fields_array()
	{
		$array = array(
			"hpi" => "History of Present Illness",
			"situation" => "Situation",
			"ros_gen" => "ROS - General",
			"ros_eye" => "ROS - Eye",
			"ros_ent" => "ROS - Ears, Nose, Throat",
			"ros_resp" => "ROS - Respiratory",
			"ros_cv" => "ROS - Cardiovascular",
			"ros_gi" => "ROS - Gastrointestinal",
			"ros_gu" => "ROS - Genitourinary",
			"ros_mus" => "ROS - Musculoskeletal",
			"ros_neuro" => "ROS - Neurological",
			"ros_psych" => "ROS - Psychological",
			"ros_heme" => "ROS - Hematological",
			"ros_endocrine" => "ROS - Endocrine",
			"ros_skin" => "ROS - Skin",
			"ros_wcc" => "ROS - Well Child Check",
			"ros_psych1" => "ROS - Depression",
			"ros_psych2" => "ROS - Anxiety",
			"ros_psych3" => "ROS - Bipolar",
			"ros_psych4" => "ROS - Mood Disorders",
			"ros_psych5" => "ROS - ADHD",
			"ros_psych6" => "ROS - PTSD",
			"ros_psych7" => "ROS - Substance Related Disorder",
			"ros_psych8" => "ROS - Obsessive Compulsive Disorder",
			"ros_psych9" => "ROS - Social Anxiety Disorder",
			"ros_psych10" => "ROS - Autistic Disorder",
			"ros_psych11" => "ROS - Asperger's Disorder",
			"oh_pmh" => "Past Medical History",
			"oh_psh" => "Past Surgical History",
			"oh_fh" => "Family History",
			"oh_sh" => "Social History",
			"oh_etoh" => "Alcohol Use",
			"oh_tobacco" => "Tobacco Use",
			"oh_drugs" => "Illicit Drug Use",
			"oh_employment" => "History - Employment",
			"oh_psychosocial" => "History - Psychosocial",
			"oh_developmental" => "History - Developmental",
			"oh_medtrials" => "History - Medication Trials",
			"oh_results" => "Reviewed Results",
			"pe_gen1" => "PE - General",
			"pe_eye1" => "PE - Eye - Conjunctiva and Lids",
			"pe_eye2" => "PE - Eye - Pupil and Iris",
			"pe_eye3" => "PE - Eye - Fundoscopic",
			"pe_ent1" => "PE - ENT - External Ear and Nose",
			"pe_ent2" => "PE - ENT - Canals and Tympanic Membranes",
			"pe_ent3" => "PE - ENT - Hearing Assessment",
			"pe_ent4" => "PE - ENT - Sinuses, Mucosa, Septum, and Turbinates",
			"pe_ent5" => "PE - ENT - Lips, Teeth, and Gums",
			"pe_ent6" => "PE - ENT - Oropharynx",
			"pe_neck1" => "PE - Neck - General",
			"pe_neck2" => "PE - Neck - Thryoid",
			"pe_resp1" => "PE - Respiratory - Effort",
			"pe_resp2" => "PE - Respiratory - Percussion",
			"pe_resp3" => "PE - Respiratory - Palpation",
			"pe_resp4" => "PE - Respiratory - Auscultation",
			"pe_cv1" => "PE - Cardiovascular - Palpation",
			"pe_cv2" => "PE - Cardiovascular - Auscultation",
			"pe_cv3" => "PE - Cardiovascular - Carotid Arteries",
			"pe_cv4" => "PE - Cardiovascular - Abdominal Aorta",
			"pe_cv5" => "PE - Cardiovascular - Femoral Arteries",
			"pe_cv6" => "PE - Cardiovascular - Extremities",
			"pe_ch1" => "PE - Chest - Inspection",
			"pe_ch2" => "PE - Chest - Palpation",
			"pe_gi1" => "PE - Gastrointestinal - Masses and Tenderness",
			"pe_gi2" => "PE - Gastrointestinal - Liver and Spleen",
			"pe_gi3" => "PE - Gastrointestinal - Hernia",
			"pe_gi4" => "PE - Gastrointestinal - Anus, Perineum, and Rectum",
			"pe_gu1" => "PE - Genitourinary - Genitalia",
			"pe_gu2" => "PE - Genitourinary - Urethra",
			"pe_gu3" => "PE - Genitourinary - Bladder",
			"pe_gu4" => "PE - Genitourinary - Cervix",
			"pe_gu5" => "PE - Genitourinary - Uterus",
			"pe_gu6" => "PE - Genitourinary - Adnexa",
			"pe_gu7" => "PE - Genitourinary - Scrotum",
			"pe_gu8" => "PE - Genitourinary - Penis",
			"pe_gu9" => "PE - Genitourinary - Prostate",
			"pe_lymph1" => "PE - Lymphatic - Neck",
			"pe_lymph2" => "PE - Lymphatic - Axillae",
			"pe_lymph3" => "PE - Lymphatic - Groin",
			"pe_ms1" => "PE - Musculoskeletal - Gait and Station",
			"pe_ms2" => "PE - Musculoskeletal - Digit and Nails",
			"pe_ms3" => "PE - Musculoskeletal - Shoulder",
			"pe_ms4" => "PE - Musculoskeletal - Elbow",
			"pe_ms5" => "PE - Musculoskeletal - Wrist",
			"pe_ms6" => "PE - Musculoskeletal - Hand",
			"pe_ms7" => "PE - Musculoskeletal - Hip",
			"pe_ms8" => "PE - Musculoskeletal - Knee",
			"pe_ms9" => "PE - Musculoskeletal - Ankle",
			"pe_ms10" => "PE - Musculoskeletal - Foot",
			"pe_ms11" => "PE - Musculoskeletal - Cervical Spine",
			"pe_ms12" => "PE - Musculoskeletal - Thoracic and Lumbar Spine",
			"pe_neuro1" => "PE - Neurological - Cranial Nerves",
			"pe_neuro2" => "PE - Neurological - Deep Tendon Reflexes",
			"pe_neuro3" => "PE - Neurological - Sensation and Motor",
			"pe_psych1" => "PE - Psychiatric - Judgement",
			"pe_psych2" => "PE - Psychiatric - Orientation",
			"pe_psych3" => "PE - Psychiatric - Memory",
			"pe_psych4" => "PE - Psychiatric - Mood and Affect",
			"pe_skin1" => "PE - Skin - Inspection",
			"pe_skin2" => "PE - Skin - Palpation",
			"pe_constitutional1" => "PE - Constitutional",
			"pe_mental1" => "PE - Mental Status Examination",
			"proc_description" => "Procedure - Description",
			"assessment_notes" => "Assessement Discussion",
			"messages_ref_orders" => "Referral Reason",
			"orders_plan" => "Orders - Recommendations",
			"followup" => "Followup",
			"orders_goals" => "Treatment Plan - Goals/Measure",
			"orders_tp" => "Treatment Plan - Treatment Plan Notes",
			"notes" => "Appointment Notes/Tasks"
		);
		return $array;
	}

	protected function encounter_template_array()
	{
		$standardmedical = array(
			"hpi" => "History of Present Illness",
			"ros_gen" => "ROS - General",
			"ros_eye" => "ROS - Eye",
			"ros_ent" => "ROS - Ears, Nose, Throat",
			"ros_resp" => "ROS - Respiratory",
			"ros_cv" => "ROS - Cardiovascular",
			"ros_gi" => "ROS - Gastrointestinal",
			"ros_gu" => "ROS - Genitourinary",
			"ros_mus" => "ROS - Musculoskeletal",
			"ros_neuro" => "ROS - Neurological",
			"ros_psych" => "ROS - Psychological",
			"ros_heme" => "ROS - Hematological",
			"ros_endocrine" => "ROS - Endocrine",
			"ros_skin" => "ROS - Skin",
			"ros_wcc" => "ROS - Well Child Check",
			"oh_pmh" => "Past Medical History",
			"oh_psh" => "Past Surgical History",
			"oh_fh" => "Family History",
			"oh_sh" => "Social History",
			"oh_etoh" => "Alcohol Use",
			"oh_tobacco" => "Tobacco Use",
			"oh_drugs" => "Illicit Drug Use",
			"oh_employment" => "History - Employment",
			"oh_psychosocial" => "History - Psychosocial",
			"oh_developmental" => "History - Developmental",
			"oh_medtrials" => "History - Medication Trials",
			"oh_results" => "Reviewed Results",
			"pe_gen1" => "PE - General",
			"pe_eye1" => "PE - Eye - Conjunctiva and Lids",
			"pe_eye2" => "PE - Eye - Pupil and Iris",
			"pe_eye3" => "PE - Eye - Fundoscopic",
			"pe_ent1" => "PE - ENT - External Ear and Nose",
			"pe_ent2" => "PE - ENT - Canals and Tympanic Membranes",
			"pe_ent3" => "PE - ENT - Hearing Assessment",
			"pe_ent4" => "PE - ENT - Sinuses, Mucosa, Septum, and Turbinates",
			"pe_ent5" => "PE - ENT - Lips, Teeth, and Gums",
			"pe_ent6" => "PE - ENT - Oropharynx",
			"pe_neck1" => "PE - Neck - General",
			"pe_neck2" => "PE - Neck - Thryoid",
			"pe_resp1" => "PE - Respiratory - Effort",
			"pe_resp2" => "PE - Respiratory - Percussion",
			"pe_resp3" => "PE - Respiratory - Palpation",
			"pe_resp4" => "PE - Respiratory - Auscultation",
			"pe_cv1" => "PE - Cardiovascular - Palpation",
			"pe_cv2" => "PE - Cardiovascular - Auscultation",
			"pe_cv3" => "PE - Cardiovascular - Carotid Arteries",
			"pe_cv4" => "PE - Cardiovascular - Abdominal Aorta",
			"pe_cv5" => "PE - Cardiovascular - Femoral Arteries",
			"pe_cv6" => "PE - Cardiovascular - Extremities",
			"pe_ch1" => "PE - Chest - Inspection",
			"pe_ch2" => "PE - Chest - Palpation",
			"pe_gi1" => "PE - Gastrointestinal - Masses and Tenderness",
			"pe_gi2" => "PE - Gastrointestinal - Liver and Spleen",
			"pe_gi3" => "PE - Gastrointestinal - Hernia",
			"pe_gi4" => "PE - Gastrointestinal - Anus, Perineum, and Rectum",
			"pe_gu1" => "PE - Genitourinary - Genitalia",
			"pe_gu2" => "PE - Genitourinary - Urethra",
			"pe_gu3" => "PE - Genitourinary - Bladder",
			"pe_gu4" => "PE - Genitourinary - Cervix",
			"pe_gu5" => "PE - Genitourinary - Uterus",
			"pe_gu6" => "PE - Genitourinary - Adnexa",
			"pe_gu7" => "PE - Genitourinary - Scrotum",
			"pe_gu8" => "PE - Genitourinary - Penis",
			"pe_gu9" => "PE - Genitourinary - Prostate",
			"pe_lymph1" => "PE - Lymphatic - Neck",
			"pe_lymph2" => "PE - Lymphatic - Axillae",
			"pe_lymph3" => "PE - Lymphatic - Groin",
			"pe_ms1" => "PE - Musculoskeletal - Gait and Station",
			"pe_ms2" => "PE - Musculoskeletal - Digit and Nails",
			"pe_ms3" => "PE - Musculoskeletal - Shoulder",
			"pe_ms4" => "PE - Musculoskeletal - Elbow",
			"pe_ms5" => "PE - Musculoskeletal - Wrist",
			"pe_ms6" => "PE - Musculoskeletal - Hand",
			"pe_ms7" => "PE - Musculoskeletal - Hip",
			"pe_ms8" => "PE - Musculoskeletal - Knee",
			"pe_ms9" => "PE - Musculoskeletal - Ankle",
			"pe_ms10" => "PE - Musculoskeletal - Foot",
			"pe_ms11" => "PE - Musculoskeletal - Cervical Spine",
			"pe_ms12" => "PE - Musculoskeletal - Thoracic and Lumbar Spine",
			"pe_neuro1" => "PE - Neurological - Cranial Nerves",
			"pe_neuro2" => "PE - Neurological - Deep Tendon Reflexes",
			"pe_neuro3" => "PE - Neurological - Sensation and Motor",
			"pe_psych1" => "PE - Psychiatric - Judgement",
			"pe_psych2" => "PE - Psychiatric - Orientation",
			"pe_psych3" => "PE - Psychiatric - Memory",
			"pe_psych4" => "PE - Psychiatric - Mood and Affect",
			"pe_skin1" => "PE - Skin - Inspection",
			"pe_skin2" => "PE - Skin - Palpation",
			"proc_description" => "Procedure - Description",
			"assessment_notes" => "Assessement Discussion",
			"messages_ref_orders" => "Referral Reason",
			"orders_plan" => "Orders - Recommendations",
			"followup" => "Followup"
		);
		$clinicalsupport = array(
			"situation" => "Situation",
			"oh_pmh" => "Past Medical History",
			"oh_psh" => "Past Surgical History",
			"oh_fh" => "Family History",
			"oh_sh" => "Social History",
			"oh_etoh" => "Alcohol Use",
			"oh_tobacco" => "Tobacco Use",
			"oh_drugs" => "Illicit Drug Use",
			"oh_employment" => "History - Employment",
			"oh_psychosocial" => "History - Psychosocial",
			"oh_developmental" => "History - Developmental",
			"oh_medtrials" => "History - Medication Trials",
			"oh_results" => "Reviewed Results",
			"proc_description" => "Procedure - Description",
			"assessment_notes" => "Assessement Discussion",
			"messages_ref_orders" => "Referral Reason",
			"orders_plan" => "Orders - Recommendations",
			"followup" => "Followup"
		);
		$standardpsych = array(
			"hpi" => "History of Present Illness",
			"ros_gen" => "ROS - General",
			"ros_eye" => "ROS - Eye",
			"ros_ent" => "ROS - Ears, Nose, Throat",
			"ros_resp" => "ROS - Respiratory",
			"ros_cv" => "ROS - Cardiovascular",
			"ros_gi" => "ROS - Gastrointestinal",
			"ros_gu" => "ROS - Genitourinary",
			"ros_mus" => "ROS - Musculoskeletal",
			"ros_neuro" => "ROS - Neurological",
			"ros_heme" => "ROS - Hematological",
			"ros_endocrine" => "ROS - Endocrine",
			"ros_skin" => "ROS - Skin",
			"ros_wcc" => "ROS - Well Child Check",
			"ros_psych1" => "ROS - Depression",
			"ros_psych2" => "ROS - Anxiety",
			"ros_psych3" => "ROS - Bipolar",
			"ros_psych4" => "ROS - Mood Disorders",
			"ros_psych5" => "ROS - ADHD",
			"ros_psych6" => "ROS - PTSD",
			"ros_psych7" => "ROS - Substance Related Disorder",
			"ros_psych8" => "ROS - Obsessive Compulsive Disorder",
			"ros_psych9" => "ROS - Social Anxiety Disorder",
			"ros_psych10" => "ROS - Autistic Disorder",
			"ros_psych11" => "ROS - Asperger's Disorder",
			"oh_pmh" => "Past Medical History",
			"oh_psh" => "Past Surgical History",
			"oh_fh" => "Family History",
			"oh_sh" => "Social History",
			"oh_etoh" => "Alcohol Use",
			"oh_tobacco" => "Tobacco Use",
			"oh_drugs" => "Illicit Drug Use",
			"oh_employment" => "History - Employment",
			"oh_psychosocial" => "History - Psychosocial",
			"oh_developmental" => "History - Developmental",
			"oh_medtrials" => "History - Medication Trials",
			"oh_results" => "Reviewed Results",
			"pe_constitutional1" => "PE - Constitutional",
			"pe_mental1" => "PE - Mental Status Examination",
			"proc_description" => "Procedure - Description",
			"assessment_notes" => "Assessement Discussion",
			"messages_ref_orders" => "Referral Reason",
			"orders_goals" => "Treatment Plan - Goals/Measure",
			"orders_tp" => "Treatment Plan - Treatment Plan Notes"
		);
		$standardmtm = array(
			"hpi" => "History of Present Illness",
			"oh_pmh" => "Past Medical History",
			"oh_psh" => "Past Surgical History",
			"oh_fh" => "Family History",
			"oh_sh" => "Social History",
			"oh_etoh" => "Alcohol Use",
			"oh_tobacco" => "Tobacco Use",
			"oh_drugs" => "Illicit Drug Use",
			"oh_employment" => "History - Employment",
			"oh_psychosocial" => "History - Psychosocial",
			"oh_developmental" => "History - Developmental",
			"oh_medtrials" => "History - Medication Trials",
			"oh_results" => "Reviewed Results",
			"assessment_notes" => "Assessement Discussion",
			"messages_ref_orders" => "Referral Reason",
			"orders_plan" => "Orders - Recommendations",
			"followup" => "Followup"
		);
		$template = array(
			'standardmedical' => $standardmedical,
			'standardmedical1' => $standardmedical,
			'clinicalsupport' => $clinicalsupport,
			'standardpsych' => $standardpsych,
			'standardpsych1' => $standardpsych,
			'standardmtm' => $standardmtm
		);
		return $template;
	}

	protected function install_template($csv, $practice_id)
	{
		$csv_line = explode("\n", $csv);
		if (count($csv_line) >= 2) {
			$headers = explode("\t", $csv_line[0]);
			for ($k = 1; $k < count($csv_line); $k++) {
				if (isset($csv_line[$k])) {
					$values = explode("\t", $csv_line[$k]);
					if (count($values) > 1) {
						$row = array();
						for ($j = 0; $j < count($headers); $j++) {
							$row[$headers[$j]] = $values[$j];
						}
						$row_check = DB::table('templates')->where('template_core_id', '=', $row['template_core_id'])->where('practice_id', '=', $practice_id)->first();
						if ($row_check) {
							DB::table('templates')->where('template_id', '=', $row_check->template_id)->update($row);
							$this->audit('Update');
						} else {
							$row['practice_id'] = $practice_id;
							DB::table('templates')->insert($row);
							$this->audit('Add');
						}
					}
				}
			}
			$ret['count'] = $k;
		} else {
			$ret['error'] = "<br>Incorrect format.";
		}
		return $ret;
	}

	protected function vaccine_supplement_alert($practice_id)
	{
		$time_exp = date('Y-m-d H:i:s', time() + (28 * 24 * 60 * 60));
		$return = '';
		$vaccine_alert_query = DB::table('vaccine_inventory')->where('quantity', '<=', '2')->where('practice_id', '=', $practice_id)->get();
		if ($vaccine_alert_query) {
			if ($return != '') {
				$return .= '<br>';
			}
			$return .= '<strong>Vaccines in your inventory that need to be reordered soon:</strong><ul>';
			foreach ($vaccine_alert_query as $vaccine_alert_row) {
				$return .= '<li>' . $vaccine_alert_row->imm_brand . ', Quantity left: ' . $vaccine_alert_row->quantity . '</li>';
			}
			$return .= '</ul>';
		}
		$vaccine_alert_query1 = DB::table('vaccine_inventory')->where('quantity', '!=', '0')->where('imm_expiration', '<', $time_exp)->where('practice_id', '=', $practice_id)->get();
		if ($vaccine_alert_query1) {
			if ($return != '') {
				$return .= '<br>';
			}
			$return .= '<strong>Vaccines in your inventory that will expire soon:</strong><ul>';
			foreach ($vaccine_alert_query1 as $vaccine_alert_row1) {
				$return .= '<li>' . $vaccine_alert_row1->imm_brand . ', Expiration date: ' . date('m/d/Y', $this->human_to_unix($vaccine_alert_row1->imm_expiration)) . '</li>';
			}
			$return .= '</ul>';
		}
		$supplement_alert_query = DB::table('supplement_inventory')->where('quantity1', '<=', '2')->where('practice_id', '=', $practice_id)->get();
		if ($supplement_alert_query) {
			if ($return != '') {
				$return .= '<br>';
			}
			$return .= '<strong>Supplements/Herbs in your inventory that need to be reordered soon:</strong><ul>';
			foreach ($supplement_alert_query as $supplement_alert_row) {
				$return .= '<li>' . $supplement_alert_row->sup_description . ', Quantity left: ' . $supplement_alert_row->quantity1 . '</li>';
			}
			$return .= '</ul>';
		}
		$data['supplement_expire_alert'] = '';
		$supplement_alert_query1 = DB::table('supplement_inventory')->where('quantity1', '!=', '0')->where('sup_expiration', '<', $time_exp)->where('practice_id', '=', $practice_id)->get();
		if ($supplement_alert_query1) {
			if ($return != '') {
				$return .= '<br>';
			}
			$return .= '<strong>Supplements/Herbs in your inventory that will expire soon:</strong><ul>';
			foreach ($supplement_alert_query1 as $supplement_alert_row1) {
				$return .= '<li>' . $supplement_alert_row1->sup_description . ', Expiration date: ' . date('m/d/Y', $this->human_to_unix($supplement_alert_row1->sup_expiration)) . '</li>';
			}
			$return .= '</ul>';
		}
		return $return;
	}

	protected function mobile_menu_build($list_array, $id, $type)
	{
		$return = '<ul data-role="listview" id="' . $id . '">';
		//if ($type == 'mobile_click_chart') {
			$return .= '<li data-icon="home"><a href="' . route('mobile') . '">Your Dashboard</a></li>';
		//}
		//s$return .= '<li data-icon="delete" class="close_menu"><a href="#" data-rel="close">Close menu</a></li>';
		if (is_array($list_array)) {
			foreach ($list_array as $item) {
				$return .= '<li><a href="#" class="' . $type . ' ' . $item[1] . '">' . $item[0] . '</a></li>';
			}
		}
		$return .= '</ul>';
		return $return;
	}

	protected function mobile_header_build($title)
	{
		$return = '<a href="#left_panel" class="ui-btn-left ui-btn" data-role="button" role="button"><i class="zmdi zmdi-menu headericon"></i></a>';
		$return .= '<a href="#right_panel" class="ui-btn-right ui-btn" data-role="button" role="button"><i class="zmdi zmdi-settings headericon"></i></a>';
		$return .= '<h1 role="heading">' . $title . '</h1>';
		return $return;
	}

	protected function mobile_navigation_header_build($title)
	{
		$return = '<a href="#" id="navigation_header_back" class="ui-btn ui-btn-left waves-effect waves-button" data-role="button" role="button" data-nosh-origin="" data-nosh-scroll="" title="Back"><i class="zmdi zmdi-arrow-left headericon1"></i></a>';
		$return .= '<a href="#" id="navigation_header_fav" class="ui-btn ui-btn-right waves-effect waves-button" data-role="button" role="button" data-nosh-form="" data-nosh-origin="" title="Text Favorites" style="display:none"><i class="zmdi zmdi-favorite headericon"></i></a>';
		$return .= '<h1 role="heading">' . $title . '</h1>';
		return $return;
	}

	protected function mobile_result_build($list_array, $id)
	{
		//$list_array[] = [
			//'label' => '',
			//'pid' => $pid,
			//'href' => ''
		//];
		$return = '<ul data-role="listview" id="' . $id . '" data-inset="true" data-shadow="false">';
		if (is_array($list_array)) {
			foreach ($list_array as $item) {
				$return .= '<li><a data-nosh-url="' . $item['href'] . '" class="mobile_link" data-nosh-origin="' . $item['origin'] . '" data-nosh-pid="' . $item['pid'] . '">' . $item['label'] . '</a></li>';
			}
		}
		$return .= '</ul>';
		return $return;
	}

	protected function mobile_content_build()
	{
		$return = '<section id="cd-timeline" class="cd-container">';
		$arr = $this->timeline_new();
		foreach ($arr['json'] as $item) {
			$return .= $item['div'];
		}
		$return .= '</section>';
		return $return;
	}

	protected function mobile_form_build($form)
	{
		$return = '';
		if (is_array($form)) {
			$i = 0;
			foreach ($form as $row) {
				if ($i == 0) {
					// Form definitions (form = true or false) - first row in array
					// If form is true (form_action, refresh_url, form_id, row_id, table, row_index)
					// If form is false (details, pid, shortcut)
					if ($row['form'] == true) {
						$return .= '<form id="' . $row['form_id'] . '">';
						$form_id = $row['form_id'];
						$table = $row['table'];
						$row_id = $row['row_id'];
						$refresh = $row['refresh_url'];
						$row_index = $row['row_index'];
						$form_status = $row['form'];
					} else {
						$return .= $row['details'];
						$pid = $row['pid'];
						$shortcut = $row['shortcut'];
					}
					// Form actions = save, cancel, inactivate, reactivate, delete
					$form_actions = $row['form_action'];
				} else {
					// Form types = textarea, select, search, text, radio, checkbox, hidden, date, tel
					// Form row definitions (type, id, name, label, option_array (value, label), value, search_array (class, paste_to, placeholder))
					//$form[$i][] = [
						//'form' => true,
						//'form_id' => '' + $i,
						//'table' => '',
						//'row_id' => '',
						//'refresh_url' => action('AjaxChartController@postIssuesList', array('true')),
						//'row_index' => '',
						//'form_action' => [
							//'save',
							//'cancel',
							//'inactivate',
							//'delete'
						//]
					//];
					//$form[$i][] = [
						//'type' => '',
						//'id' => '' . $i,
						//'name' => '',
						//'label' => '',
						//'option_array'[] => [
						//	'0' => [
						//		'value' => '',
						//		'label' => ''
						//	],
						//],
						//'value' => '',
						//'search_array' => [
							//'class' => '',
							//'paste_to' => '',
							//'placeholder' => ''
						//]
					//];
					$return .= '<label for="' . $row['id'] . '">' . $row['label'] . '</label>';
					if ($row['type'] == 'textarea') {
						$return .= '<textarea cols="40" rows="8" id="' . $row['id'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '">' . $row['value'] . '</textarea>';
					} elseif ($row['type'] == 'select') {
						$return .= '<select id="' . $row['id'] . '" name="' . $row['name'] . '">';
						foreach ($row['option_array'] as $option) {
							if ($row['value'] ==  $option['value']) {
								$return .= '<option value="' . $option['value'] . '" selected>' . $option['label'] . '</option>';
							} else {
								$return .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
							}
						}
						$return .= '</select>';
					} elseif ($row['type'] == 'search') {
						$return .= '<ul class="' . $row['search_array']['class'] . '" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="' . $row['search_array']['placeholder'] .'" data-filter-theme="a" data-nosh-paste-to="' . $row['search_array']['paste_to'] . '"></ul>';
					} else {
						$return .= '<input type="' . $row['type'] . '" id="' . $row['id'] . '" name="' . $row['name'] . '" value="' . $row['value'] . '"/>';
					}
				}
				$i++;
			}
			if ($form_status == true) {
				$return .= '</form>';
				// Add buttons
				foreach ($form_actions as $action) {
					if ($action == 'save') {
						$class = "mobile_form_action ui-btn ui-btn-inline ui-icon-check ui-btn-icon-left";
						$label = 'Save';
					}
					if ($action == 'cancel') {
						$class = "mobile_form_action ui-btn ui-btn-inline ui-icon-minus ui-btn-icon-left";
						$label = 'Cancel';
					}
					if ($action == 'inactivate') {
						$class = "mobile_form_action ui-btn ui-btn-inline ui-icon-back ui-btn-icon-left";
						$label = 'Inactivate';
					}
					if ($action == 'reactivate') {
						$class = "mobile_form_action ui-btn ui-btn-inline ui-icon-forward ui-btn-icon-left";
						$label = 'Reactivate';
					}
					if ($action == 'delete') {
						$class = "mobile_form_action ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-left";
						$label = 'Delete';
					}
					$return .= '<a href="#" class="' . $class . '" data-nosh-form="' . $form_id . '" data-nosh-table="' . $table . '" data-nosh-row-id="' . $row_id . '" data-nosh-row-index="' . $row_index . '" data-nosh-action="' . $action . '" data-nosh-refresh="' . $refresh . '">' . $label .'</a>';
				}
			} else {
				$class = "mobile_shortcut ui-btn ui-btn-inline ui-icon-forward ui-btn-icon-left";
				$label = 'Go to Chart';
				$return .= '<a href="#" class="' . $class . '" data-nosh-pid="' . $pid . '" data-nosh-shortcut="' . $shortcut . '">' . $label .'</a>';
			}
		}
		return $return;
	}

	protected function uma_api_build($command, $url, $send_object = null, $put_delete = null)
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$api_endpoint = str_replace('/nosh', '/uma-server-webapp/api/' . $command, URL::to('/'));
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		$oidc->setAccessToken(Session::get('uma_auth_access_token'));
		$response = $oidc->api($command, $api_endpoint, $send_object, $put_delete);
		return $response;
	}

	protected function uma_resource_set($url, $name = null, $icon = null, $scopes = null)
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->setRedirectURL($url);
		if (Session::has('uma_auth_pat')) {
			$oidc->setAccessToken(Session::get('uma_auth_pat'));
		} else {
			$oidc->authenticate(true,'pat');
			Session::put('uma_auth_pat', $oidc->getAccessToken());
		}
		$response = $oidc->resource_set($name, $icon, $scopes);
		return $response;
	}

	protected function uma_permission_request($resource_set_id = null, $scopes = null)
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->refresh($practice->uma_refresh_token,true);
		$response = $oidc->permission_request($resource_set_id, $scopes);
		return $response;
	}

	protected function uma_introspect($token)
	{
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$oidc = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc->refresh($practice->uma_refresh_token,true);
		$response = $oidc->introspect($token);
		return $response;
	}

	protected function syncuser($token)
	{
		$url = 'https://noshchartingsystem.com/nosh-sso/syncuser?token=' . $token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$output = curl_exec($ch);
		$result = json_decode($output, true);
		if (isset($result['error'])) {
			$return = false;
		} else {
			$query = DB::connection('oic')->table('users')->where('username', '=', $result['users']['username'])->first();
			if ($query) {
				DB::connection('oic')->table('users')->where('username', '=', $result['users']['username'])->update($result['users']);
			} else {
				DB::connection('oic')->table('users')->insert($result['users']);
				$data = array(
					'username' => $result['users']['username'],
					'authority' => 'ROLE_USER'
				);
				DB::connection('oic')->table('authorities')->insert($data);
			}
			$query1 = DB::connection('oic')->table('user_info')->where('preferred_username', '=', $result['user_info']['preferred_username'])->first();
			if ($query1) {
				unset($result['user_info']['id']);
				DB::connection('oic')->table('user_info')->where('id', '=', $query1->id)->update($result['user_info']);
			} else {
				DB::connection('oic')->table('user_info')->insert($result['user_info']);
			}
			$return = true;
		}
		return $return;
	}

	protected function uma_users()
	{
		$query = DB::table('users')->where('id', '=', Session::get('user_id'))->first();
		$query1 = DB::connection('oic')->table('user_info')->where('sub', '!=', $query->uid)->get();
		$return = array();
		$return[''] = 'Select User to Add...';
		if ($query1) {
			foreach ($query1 as $row1) {
				$return[$row1->email] = $row1->given_name . " " . $row1->family_name;
			}
		}
		return $return;
	}

	protected function uma_scopes($resource_set_id)
	{
		$query = DB::table('uma')->where('resource_set_id', '=', $resource_set_id)->get();
		$html = '';
		$i=0;
		if ($query) {
			foreach ($query as $row) {
				$html .= '<label for="uma_scope_' . $i . '" class="pure-checkbox">';
				$html .= Form::checkbox('scopes[]', $row->scope, true, ['id' => 'uma_scope_' . $i]);
				$html .= ' ' . $row->scope;
				$html .= '</label>';
				$i++;
			}
		}
		return $html;
	}

	protected function get_uma_policy($resource_set_id)
	{
		$query = DB::connection('oic')->table('policy')->where('resource_set_id', '=', $resource_set_id)->get();
		$user_data = $this->uma_users();
		$html = '<form id="uma_form" class="pure-form pure-form-stacked"><input name="resource_set_id" id="uma_resource_set_id" type="hidden" value="' . $resource_set_id . '"/><label for="uma_email">Add a user with the following scopes for this resource:</label>';
		$html .= Form::select('email', $user_data, null, array('id'=>'uma_email','style'=>'width:300px','class'=>'text'));
		$html .= $this->uma_scopes($resource_set_id);
		$html .= '</form><i id="add_uma_policy_user" class="fa fa-plus fa-fw fa-2x add_uma_user nosh_tooltip" style="vertical-align:middle;padding:2px" title="Add permitted user to this resource" nosh-id="' . $resource_set_id . '"></i>Add User<br>';
		if ($query) {
			$html .= '<table class="pure-table pure-table-horizontal"><thead><tr><th>User</th><th>Action</th></tr></thead>';
			foreach ($query as $row) {
				$query1 = DB::connection('oic')->table('claim_to_policy')->where('policy_id', '=', $row->id)->get();
				if ($query1) {
					foreach ($query1 as $row1) {
						$query2 = DB::connection('oic')->table('claim')->where('name', '=', 'sub')->where('id', '=', $row1->claim_id)->first();
						if ($query2) {
							$sub = trim($query2->claim_value, '"');
							$query3 = DB::connection('oic')->table('user_info')->where('sub', '=', $sub)->first();
							$html .= '<tr><td>' . $query3->given_name . ' ' . $query3->family_name . ' (' . $query3->email . ')</td><td><i class="fa fa-times fa-fw fa-2x remove_uma_user nosh_tooltip" style="vertical-align:middle;padding:2px" title="Remove permission for this user" nosh-sub="' . $query3->sub . '" nosh-resource-set-id="' . $resource_set_id . '" nosh-policy-id="' . $row->id . '" nosh-name="' . $query3->given_name . ' ' . $query3->family_name . ' (' . $query3->email . ')"></i></td></tr>';
						}
					}
				}
			}
			$html .= '</table>';
		}
		return $html;
	}

	protected function uma_policy($resource_set_id, $email, $scopes, $policy_id='')
	{
		$query = DB::connection('oic')->table('user_info')->where('email', '=', $email)->first();
		if ($query) {
			$claim_ids = array();
			$data1a = array(
				'name' => 'email',
				'claim_value' => $email
			);
			$claim_ids[] = DB::connection('oic')->table('claim')->insertGetId($data1a);
			if ($query->email_verified == '1') {
				$email_verified = 'true';
			} else {
				$email_verified = 'false';
			}
			$data1b = array(
				'name' => 'email_verified',
				'claim_value' => $email_verified
			);
			$claim_ids[] = DB::connection('oic')->table('claim')->insertGetId($data1b);
			$data1c = array(
				'name' => 'sub',
				'claim_value' => $query->sub
			);
			$claim_ids[] = DB::connection('oic')->table('claim')->insertGetId($data1c);
			$query1 = DB::connection('oic')->table('policy')->where('id', '=', $policy_id)->first();
			$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
			if ($policy_id != '' && $query1) {
				DB::connection('oic')->table('policy_scope')->where('owner_id', '=', $policy_id)->delete();
				DB::connection('oic')->table('claim_to_policy')->where('policy_id', '=', $policy_id)->delete();
				$return = 'Updated policy';
			} else {
				$data2['resource_set_id'] = $resource_set_id;
				$policy_id = DB::connection('oic')->table('policy')->insertGetId($data2);
				$return = 'Added policy';
			}
			foreach($scopes as $scope) {
				$data3 = array(
					'owner_id' => $policy_id,
					'scope' => $scope
				);
				DB::connection('oic')->table('policy_scope')->insert($data3);
			}
			foreach($claim_ids as $claim_id) {
				$data4 = array(
					'policy_id' => $policy_id,
					'claim_id' => $claim_id
				);
				DB::connection('oic')->table('claim_to_policy')->insert($data4);
				$data5 = array(
					'owner_id' => $claim_id,
					'issuer' => $open_id_url
				);
				DB::connection('oic')->table('claim_issuer')->insert($data5);
			}
		} else {
			$return = 'Email address or user is not registered!';
		}
		return $return;
	}

	protected function register_scope($id, $type, $table)
	{
		if ($type == 'Condition') {
			if ($table == 'encounters') {
				$table_key = 'eid_';
			}
			if ($table == 'issues') {
				$table_key = 'issue_id';
			}
 			$resource_set_array = array(
				'name' => 'Condition',
				'icon' => 'https://noshchartingsystem.com/i-condition.png',
				'scopes' => array(
					URL::to('/') . '/fhir/Condition/' . $table_key . $id,
					URL::to('/') . '/fhir/Condition?identifier=' . $table_key . $id,
				)
			);
		}
		if ($type == 'MedicationStatement') {
			$resource_set_array = array(
				'name' => 'Medication List',
				'icon' => 'https://noshchartingsystem.com/i-pharmacy.png',
				'scopes' => array(
					URL::to('/') . '/fhir/MedicationStatement/' . $id,
					URL::to('/') . '/fhir/MedicationStatement?identifier=' . $id
				)
			);
		}
		if ($type == 'Allergy') {
			$resource_set_array = array(
				'name' => 'Allergy',
				'icon' => 'https://noshchartingsystem.com/i-allergy.png',
				'scopes' => array(
					URL::to('/') . '/fhir/AllergyIntolerance/' . $id,
					URL::to('/') . '/fhir/AllergyIntolerance?identifier=' . $id,
				)
			);
		}
		if ($type == 'Immunization') {
			$resource_set_array = array(
				'name' => 'Immunization',
				'icon' => 'https://noshchartingsystem.com/i-immunization.png',
				'scopes' => array(
					URL::to('/') . '/fhir/Immunization/' . $id,
					URL::to('/') . '/fhir/Immunization?identifier=' . $id,
				)
			);
		}
		if ($type == 'Encounter') {
			$resource_set_array = array(
				'name' => 'Encounter',
				'icon' => 'https://noshchartingsystem.com/i-medical-records.png',
				'scopes' => array(
					URL::to('/') . '/fhir/Encounter/' . $id,
					URL::to('/') . '/fhir/Encounter?identifier=' . $id,
				)
			);
		}
		if ($type == 'FamilyHistory') {
			$resource_set_array = array(
				'name' => 'Family History',
				'icon' => 'https://noshchartingsystem.com/i-family-practice.png',
				'scopes' => array(
					URL::to('/') . '/fhir/FamilyHistory/' . $id,
					URL::to('/') . '/fhir/FamilyHistory?identifier=' . $id,
				)
			);
		}
		if ($type == 'Binary') {
			$resource_set_array[] = array(
				'name' => 'Binary Files',
				'icon' => 'https://noshchartingsystem.com/i-file.png',
				'scopes' => array(
					URL::to('/') . '/fhir/Binary/' . $id,
					URL::to('/') . '/fhir/Binary?identifier=' . $id
				)
			);
		}
		if ($type == 'Observation') {
			$resource_set_array = array(
				'name' => 'Observation',
				'icon' => 'https://noshchartingsystem.com/i-cardiology.png',
				'scopes' => array(
					URL::to('/') . '/fhir/Observation/' . $id,
					URL::to('/') . '/fhir/Observation?identifier=' . $id
				)
			);
		}
		$open_id_url = str_replace('/nosh', '/uma-server-webapp/', URL::to('/'));
		$practice = DB::table('practiceinfo')->where('practice_id', '=', '1')->first();
		$client_id = $practice->uma_client_id;
		$client_secret = $practice->uma_client_secret;
		$refresh_token = $practice->uma_refresh_token;
		$oidc1 = new OpenIDConnectClient($open_id_url, $client_id, $client_secret);
		$oidc1->refresh($refresh_token,true);
		$response = $oidc1->resource_set($resource_set_array['name'], $resource_set_array['icon'], $resource_set_array['scopes']);
		if (isset($response['resource_set_id'])) {
			foreach ($resource_set_array['scopes'] as $scope_item) {
				$response_data1 = array(
					'resource_set_id' => $response['resource_set_id'],
					'scope' => $scope_item,
					'user_access_policy_uri' => $response['user_access_policy_uri'],
					'table_id' => $id,
					'table' => $table
				);
				DB::table('uma')->insert($response_data1);
				$this->audit('Add');
			}
		}
		return true;
	}

	protected function timeline()
	{
		// Will need to remove soon
		$pid = Session::get('pid');
		$json = array();
		$date_arr = array();
		$query0 = DB::table('encounters')->where('pid', '=', $pid)->where('addendum', '=', 'n')->get();
		if ($query0) {
			foreach ($query0 as $row0) {
				$description = '';
				$procedureInfo = Procedure::find($row0->eid);
				if ($procedureInfo) {
					$description .= '<br><h4>Procedures:</h4><p class="view">';
					if ($procedureInfo->proc_type != '') {
						$description .= '<strong>Procedure: </strong>';
						$description .= nl2br($procedureInfo->proc_type);
						$description .= '<br /><br />';
					}
					$description .= '</p>';
				}
				$assessmentInfo = Assessment::find($row0->eid);
				if ($assessmentInfo) {
					$description .= '<br><h4>Assessment:</h4><p class="view">';
					if ($assessmentInfo->assessment_1 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_1 . '</strong><br />';
						if ($assessmentInfo->assessment_2 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_2 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_2 . '</strong><br />';
						if ($assessmentInfo->assessment_3 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_3 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_3 . '</strong><br />';
						if ($assessmentInfo->assessment_4 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_4 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_4 . '</strong><br />';
						if ($assessmentInfo->assessment_5 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_5 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_5 . '</strong><br />';
						if ($assessmentInfo->assessment_6 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_6 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_6 . '</strong><br />';
						if ($assessmentInfo->assessment_7 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_7 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_7 . '</strong><br />';
						if ($assessmentInfo->assessment_8 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_8 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_8 . '</strong><br /><br />';
					}
					if ($assessmentInfo->assessment_other != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<strong>SOAP Note: </strong>';
						} else {
							$description .= '<strong>Additional Diagnoses: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_other);
						$description .= '<br /><br />';
					}
					if ($assessmentInfo->assessment_ddx != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<strong>MAP2: </strong>';
						} else {
							$description .= '<strong>Differential Diagnoses Considered: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_ddx);
						$description .= '<br /><br />';
					}
					if ($assessmentInfo->assessment_notes != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<strong>Pharmacist Note: </strong>';
						} else {
							$description .= '<strong>Assessment Discussion: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_notes);
						$description .= '<br /><br />';
					}
					$description .= '</p>';
				}
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row0->eid . "' type='eid' status='" . $row0->encounter_signed ."'>Encounter: " . $row0->encounter_cc . "</span>",
					'description' => $description,
					'startDate' => $this->human_to_unix($row0->encounter_DOS)
				);
				$date_arr[] = $this->human_to_unix($row0->encounter_DOS);
			}
		}
		$query1 = DB::table('t_messages')->where('pid', '=', $pid)->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row1->t_messages_id . "' type='t_messages_id' status='" . $row1->t_messages_signed . "'>Message: " . $row1->t_messages_subject . "</span>",
					'description' => substr($row1->t_messages_message, 0, 500) . '...',
					'startDate' => $this->human_to_unix($row1->t_messages_dos)
				);
				$date_arr[] = $this->human_to_unix($row1->t_messages_dos);
			}
		}
		$query2 = DB::table('rx_list')->where('pid', '=', $pid)->orderBy('rxl_date_active','asc')->groupBy('rxl_medication')->get();
		if ($query2) {
			foreach ($query2 as $row2) {
				$row2a = DB::table('rx_list')->where('rxl_id', '=', $row2->rxl_id)->first();
				if ($row2->rxl_sig == '') {
					$instructions = $row2->rxl_instructions;
				} else {
					$instructions = $row2->rxl_sig . ' ' . $row2->rxl_route . ' ' . $row2->rxl_frequency;
				}
				$description2 = $row2->rxl_medication . ' ' . $row2->rxl_dosage . ' ' . $row2->rxl_dosage_unit . ', ' . $instructions . ' for ' . $row2->rxl_reason;
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row2->rxl_id . "' type='rxl_id'>New Medication Started</span>",
					'description' => $description2,
					'startDate' => $this->human_to_unix($row2->rxl_date_active)
				);
				$date_arr[] = $this->human_to_unix($row2->rxl_date_active);
			}
		}
		$query3 = DB::table('issues')->where('pid', '=', $pid)->get();
		if ($query3) {
			foreach ($query3 as $row3) {
				if ($row3->type == 'Problem List') {
					$title = 'New Problem';
				}
				if ($row3->type == 'Medical History') {
					$title = 'New Medical Event';
				}
				if ($row3->type == 'Problem List') {
					$title = 'New Surgical Event';
				}
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row3->issue_id . "' type='issue_id'>" . $title . "</span>",
					'description' => $row3->issue,
					'startDate' => $this->human_to_unix($row3->issue_date_active)
				);
				$date_arr[] = $this->human_to_unix($row3->issue_date_active);
			}
		}
		$query4 = DB::table('immunizations')->where('pid', '=', $pid)->get();
		if ($query4) {
			foreach ($query4 as $row4) {
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row4->imm_id . "' type='imm_id'>Immunization Given</span>",
					'description' => $row4->imm_immunization,
					'startDate' => $this->human_to_unix($row4->imm_date)
				);
				$date_arr[] = $this->human_to_unix($row4->imm_date);
			}
		}
		$query5 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '!=', '0000-00-00 00:00:00')->get();
		if ($query5) {
			foreach ($query5 as $row5) {
				$row5a = DB::table('rx_list')->where('rxl_id', '=', $row5->rxl_id)->first();
				if ($row5->rxl_sig == '') {
					$instructions5 = $row5->rxl_instructions;
				} else {
					$instructions5 = $row5->rxl_sig . ' ' . $row5->rxl_route . ' ' . $row5->rxl_frequency;
				}
				$description5 = $row5->rxl_medication . ' ' . $row5->rxl_dosage . ' ' . $row5->rxl_dosage_unit . ', ' . $instructions5 . ' for ' . $row5->rxl_reason;
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row5->rxl_id . "' type='rxl_id'>Medication Stopped</span>",
					'description' => $description5,
					'startDate' => $this->human_to_unix($row5->rxl_date_inactive)
				);
				$date_arr[] = $this->human_to_unix($row5->rxl_date_inactive);
			}
		}
		$query6 = DB::table('allergies')->where('pid', '=', $pid)->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($query6) {
			foreach ($query6 as $row6) {
				$json[] = array(
					'title' => "<span class='timeline_event' value='" . $row6->allergies_id . "' type='allergies_id'>New Allergy</span>",
					'description' => $row6->allergies_med,
					'startDate' => $this->human_to_unix($row6->allergies_date_active)
				);
				$date_arr[] = $this->human_to_unix($row6->allergies_date_active);
			}
		}
		foreach ($json as $key => $value) {
			$item[$key]  = $value['startDate'];
		}
		array_multisort($item, SORT_ASC, $json);
		asort($date_arr);
		$arr['start'] = reset($date_arr);
		$arr['end'] = end($date_arr);
		if ($arr['end'] - $arr['start'] >= 315569260) {
			$arr['granular'] = 'decade';
		}
		if ($arr['end'] - $arr['start'] > 31556926 && $arr['end'] - $arr['start'] < 315569260) {
			$arr['granular'] = 'year';
		}
		if ($arr['end'] - $arr['start'] <= 31556926) {
			$arr['granular'] = 'month';
		}
		$arr['json'] = $json;
		return $arr;
	}

	protected function timeline_item($value, $type, $category, $date, $title, $p, $status='')
	{
		$div = '<div class="cd-timeline-block" data-nosh-category="' . $category . '">';
		if ($category == 'Encounter') {
			$div .= '<div class="cd-timeline-img cd-encounter"><i class="fa fa-stethoscope fa-fw fa-lg"></i>';
		}
		if ($category == 'Telephone Message') {
			$div .= '<div class="cd-timeline-img cd-encounter"><i class="fa fa-phone fa-fw fa-lg"></i>';
		}
		if ($category == 'New Medication') {
			$div .= '<div class="cd-timeline-img cd-medication"><i class="fa fa-eyedropper fa-fw fa-lg"></i>';
		}
		if ($category == 'New Problem' || $category == 'New Medical Event' || $category == 'New Surgical Event') {
			$div .= '<div class="cd-timeline-img cd-issue"><i class="fa fa-bars fa-fw fa-lg"></i>';
		}
		if ($category == 'Immunization Given') {
			$div .= '<div class="cd-timeline-img cd-imm"><i class="fa fa-magic fa-fw fa-lg"></i>';
		}
		if ($category == 'Medication Stopped') {
			$div .= '<div class="cd-timeline-img cd-medication"><i class="fa fa-ban fa-fw fa-lg"></i>';
		}
		if ($category == 'New Allergy') {
			$div .= '<div class="cd-timeline-img cd-allergy"><i class="fa fa-exclamation-triangle fa-fw fa-lg"></i>';
		}
		$div .= '</div><div class="cd-timeline-content">';
		$div .= '<h3>' . $title . '</h3>';
		$div .= '<p>' . $p . '</p>';
		$div .= '<a href="#" class="cd-read-more" data-nosh-value="' . $value . '" data-nosh-type="' . $type . '" data-nosh-status="' . $status . '">Read more</a>';
		$div .= '<span class="cd-date">' . date('Y-m-d', $date) . '</span>';
		$div .= '</div></div>';
		return $div;
	}

	protected function timeline_new()
	{
		$pid = Session::get('pid');
		$json = array();
		$date_arr = array();
		$query0 = DB::table('encounters')->where('pid', '=', $pid)->where('addendum', '=', 'n')->get();
		if ($query0) {
			foreach ($query0 as $row0) {
				$description = '';
				$procedureInfo = Procedure::find($row0->eid);
				if ($procedureInfo) {
					$description .= '<span class="nosh_bold">Procedures:</span>';
					if ($procedureInfo->proc_type != '') {
						$description .= '<strong>Procedure: </strong>';
						$description .= nl2br($procedureInfo->proc_type);
					}
				}
				$assessmentInfo = Assessment::find($row0->eid);
				if ($assessmentInfo) {
					if ($assessmentInfo->assessment_1 != '') {
						$description .= '<span class="nosh_bold">Assessment:</span>';
						$description .= '<br><strong>' . $assessmentInfo->assessment_1 . '</strong><br />';
						if ($assessmentInfo->assessment_2 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_2 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_2 . '</strong><br />';
						if ($assessmentInfo->assessment_3 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_3 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_3 . '</strong><br />';
						if ($assessmentInfo->assessment_4 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_4 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_4 . '</strong><br />';
						if ($assessmentInfo->assessment_5 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_5 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_5 . '</strong><br />';
						if ($assessmentInfo->assessment_6 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_6 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_6 . '</strong><br />';
						if ($assessmentInfo->assessment_7 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_7 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_7 . '</strong><br />';
						if ($assessmentInfo->assessment_8 == '') {
							$description .= '<br />';
						}
					}
					if ($assessmentInfo->assessment_8 != '') {
						$description .= '<strong>' . $assessmentInfo->assessment_8 . '</strong><br /><br />';
					}
					if ($assessmentInfo->assessment_other != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<br /><strong>SOAP Note: </strong>';
						} else {
							$description .= '<br /><strong>Additional Diagnoses: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_other);
						$description .= '<br /><br />';
					}
					if ($assessmentInfo->assessment_ddx != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<br /><strong>MAP2: </strong>';
						} else {
							$description .= '<br /><strong>Differential Diagnoses Considered: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_ddx);
						$description .= '<br /><br />';
					}
					if ($assessmentInfo->assessment_notes != '') {
						if ($row0->encounter_template == 'standardmtm') {
							$description .= '<br /><strong>Pharmacist Note: </strong>';
						} else {
							$description .= '<br /><strong>Assessment Discussion: </strong>';
						}
						$description .= nl2br($assessmentInfo->assessment_notes);
						$description .= '<br /><br />';
					}
				}
				$div0 = $this->timeline_item($row0->eid, 'eid', 'Encounter', $this->human_to_unix($row0->encounter_DOS), 'Encounter: ' . $row0->encounter_cc, $description, $row0->encounter_signed);
				$json[] = array(
					'div' => $div0,
					'startDate' => $this->human_to_unix($row0->encounter_DOS)
				);
				$date_arr[] = $this->human_to_unix($row0->encounter_DOS);
			}
		}
		$query1 = DB::table('t_messages')->where('pid', '=', $pid)->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				$div1 = $this->timeline_item($row1->t_messages_id, 't_messages_id', 'Telephone Message', $this->human_to_unix($row1->t_messages_dos), 'Telephone Message', substr($row1->t_messages_message, 0, 500) . '...', $row1->t_messages_signed);
				$json[] = array(
					'div' => $div1,
					'startDate' => $this->human_to_unix($row1->t_messages_dos)
				);
				$date_arr[] = $this->human_to_unix($row1->t_messages_dos);
			}
		}
		$query2 = DB::table('rx_list')->where('pid', '=', $pid)->orderBy('rxl_date_active','asc')->groupBy('rxl_medication')->get();
		if ($query2) {
			foreach ($query2 as $row2) {
				$row2a = DB::table('rx_list')->where('rxl_id', '=', $row2->rxl_id)->first();
				if ($row2->rxl_sig == '') {
					$instructions = $row2->rxl_instructions;
				} else {
					$instructions = $row2->rxl_sig . ' ' . $row2->rxl_route . ' ' . $row2->rxl_frequency;
				}
				$description2 = $row2->rxl_medication . ' ' . $row2->rxl_dosage . ' ' . $row2->rxl_dosage_unit . ', ' . $instructions . ' for ' . $row2->rxl_reason;
				$div2 = $this->timeline_item($row2->rxl_id, 'rxl_id', 'New Medication', $this->human_to_unix($row2->rxl_date_active), 'New Medication', $description2);
				$json[] = array(
					'div' => $div2,
					'startDate' => $this->human_to_unix($row2->rxl_date_active)
				);
				$date_arr[] = $this->human_to_unix($row2->rxl_date_active);
			}
		}
		$query3 = DB::table('issues')->where('pid', '=', $pid)->get();
		if ($query3) {
			foreach ($query3 as $row3) {
				if ($row3->type == 'Problem List') {
					$title = 'New Problem';
				}
				if ($row3->type == 'Medical History') {
					$title = 'New Medical Event';
				}
				if ($row3->type == 'Problem List') {
					$title = 'New Surgical Event';
				}
				$div3 = $this->timeline_item($row3->issue_id, 'issue_id', $title, $this->human_to_unix($row3->issue_date_active), $title, $row3->issue);
				$json[] = array(
					'div' => $div3,
					'startDate' => $this->human_to_unix($row3->issue_date_active)
				);
				$date_arr[] = $this->human_to_unix($row3->issue_date_active);
			}
		}
		$query4 = DB::table('immunizations')->where('pid', '=', $pid)->get();
		if ($query4) {
			foreach ($query4 as $row4) {
				$div4 = $this->timeline_item($row4->imm_id, 'imm_id', 'Immunization Given', $this->human_to_unix($row4->imm_date), 'Immunization Given', $row4->imm_immunization);
				$json[] = array(
					'div' => $div4,
					'startDate' => $this->human_to_unix($row4->imm_date)
				);
				$date_arr[] = $this->human_to_unix($row4->imm_date);
			}
		}
		$query5 = DB::table('rx_list')->where('pid', '=', $pid)->where('rxl_date_inactive', '!=', '0000-00-00 00:00:00')->get();
		if ($query5) {
			foreach ($query5 as $row5) {
				$row5a = DB::table('rx_list')->where('rxl_id', '=', $row5->rxl_id)->first();
				if ($row5->rxl_sig == '') {
					$instructions5 = $row5->rxl_instructions;
				} else {
					$instructions5 = $row5->rxl_sig . ' ' . $row5->rxl_route . ' ' . $row5->rxl_frequency;
				}
				$description5 = $row5->rxl_medication . ' ' . $row5->rxl_dosage . ' ' . $row5->rxl_dosage_unit . ', ' . $instructions5 . ' for ' . $row5->rxl_reason;
				$div5 = $this->timeline_item($row5->rxl_id, 'rxl_id', 'Medication Stopped', $this->human_to_unix($row5->rxl_date_inactive), 'Medication Stopped', $description5);
				$json[] = array(
					'div' => $div5,
					'startDate' => $this->human_to_unix($row5->rxl_date_inactive)
				);
				$date_arr[] = $this->human_to_unix($row5->rxl_date_inactive);
			}
		}
		$query6 = DB::table('allergies')->where('pid', '=', $pid)->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($query6) {
			foreach ($query6 as $row6) {
				$div6 = $this->timeline_item($row6->allergies_id, 'allergies_id', 'New Allergy', $this->human_to_unix($row6->allergies_date_active), 'New Allergy', $row6->allergies_med);
				$json[] = array(
					'div' => $div6,
					'startDate' => $this->human_to_unix($row6->allergies_date_active)
				);
				$date_arr[] = $this->human_to_unix($row6->allergies_date_active);
			}
		}
		foreach ($json as $key => $value) {
			$item[$key]  = $value['startDate'];
		}
		array_multisort($item, SORT_DESC, $json);
		asort($date_arr);
		$arr['start'] = reset($date_arr);
		$arr['end'] = end($date_arr);
		if ($arr['end'] - $arr['start'] >= 315569260) {
			$arr['granular'] = 'decade';
		}
		if ($arr['end'] - $arr['start'] > 31556926 && $arr['end'] - $arr['start'] < 315569260) {
			$arr['granular'] = 'year';
		}
		if ($arr['end'] - $arr['start'] <= 31556926) {
			$arr['granular'] = 'month';
		}
		$arr['json'] = $json;
		return $arr;
	}

	protected function goodrx($rx, $command, $api_key='46e983ffba', $secret_key='3QmFl8W7Y2Mb655bn++NNA==')
	{
		$url = 'https://api.goodrx.com/' . $command;
		$query_string = 'name=' . $rx . '&api_key=' . $api_key;
		$hash = hash_hmac('sha256', $query_string, $secret_key, true);
		$encoded = base64_encode($hash);
		$search = array('+','/');
		$sig = str_replace($search, '_', $encoded);
		$url .= '?' . $query_string . '&sig=' . $sig;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_FAILONERROR,1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,0);
		$result = curl_exec($ch);
		$result_array = json_decode($result, true);
		curl_close($ch);
		return $result_array;
	}

	protected function goodrx_notification($rx, $dose)
	{
		$row = Demographics::find(Session::get('pid'));
		$row2 = Practiceinfo::find(Session::get('practice_id'));
		$to = $row->reminder_to;
		$rx1 = explode(',', $rx);
		$rx_array = explode(' ', $rx1[0]);
		$dose_array = explode('/', $dose);
		if ($to != '') {
			$result = $this->goodrx($rx_array[0], 'drug-info');
			if ($result['success'] == true) {
				if (isset($result['data']['drugs']['tablet'][$dose_array[0]])) {
					$link = $result['data']['drugs']['tablet'][$dose_array[0]];
				} else {
					$link = reset($result['data']['drugs']['tablet']);
				}
				if ($row->reminder_method == 'Cellular Phone') {
					$data_message['item'] = 'New Medication: ' . $rx . '; ' . $link;
					$this->send_mail(array('text' => 'emails.blank'), $data_message, 'New Medication', $to, Session::get('practice_id'));
				} else {
					$data_message['item'] = 'You have a new medication prescribed to you: ' . $rx . '; For more details, click here: ' . $link;
					$this->send_mail('emails.blank', $data_message, 'New Medication', $to, Session::get('practice_id'));
				}
			}
		}
	}
}
