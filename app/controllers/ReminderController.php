<?php

class ReminderController extends BaseController {

	/**
	* NOSH ChartingSystem Reminder System, to be run as a cron job
	*/
	public function test()
	{
		Config::set('app.timezone' , $row->timezone);
	}
	
	public function reminder()
	{
		$start = time();
		$end = time() + (2 * 24 * 60 * 60);
		$query1 = DB::table('schedule')
			->join('demographics', 'schedule.pid', '=', 'demographics.pid')
			->select('demographics.reminder_to', 'demographics.reminder_method', 'schedule.appt_id', 'schedule.provider_id', 'schedule.start')
			->where('schedule.status', '=', 'Pending')
			->whereBetween('schedule.start', array($start, $end))
			->get();
		$j=0;
		$i=0;
		$results_scan=0;
		if ($query1) {
			foreach ($query1 as $row) {
				$to = $row->reminder_to;
				if ($to != '') {
					$row2 = Practiceinfo::where('practice_id', '=', $row0->practice_id)->first();
					Config::set('app.timezone' , $row2->timezone);
					$data_message['startdate'] = date("F j, Y, g:i a", $row->start);
					$row0 = User::where('id', '=', $row->provider_id)->first();
					$data_message['displayname'] = $row0->displayname;
					$data_message['phone'] = $row2->phone;
					$data_message['email'] = $row2->email;
					$data_message['additional_message'] = $row2->additional_message;
					if ($row->reminder_method == 'Cellular Phone') {
						$this->send_mail(array('text' => 'emails.remindertext'), $data_message, 'Appointment Reminder', $to, $row0->practice_id);
					} else {
						$this->send_mail('emails.reminder', $data_message, 'Appointment Reminder', $to, $row0->practice_id);
					}
					$data = array(
						'status' => 'Reminder Sent'
					);
					DB::table('schedule')->where('appt_id', '=', $row->appt_id)->update($data);
					$this->audit('Add');
					$i++;
				}
				$j++;
			}
		}
		$query3 = Practiceinfo::all();
		foreach ($query3 as $practice_row) {
			$updox = $this->check_extension('updox_extension', $practice_row->practice_id);
			if ($updox) {
				$this->updox_sync($practice_row->practice_id);
			}
			$rcopia = $this->check_extension('rcopia_extension', $practice_row->practice_id);
			if ($rcopia) {
				$this->rcopia_sync($practice_row->practice_id);
			}
			$results_scan = $this->get_scans($practice_row->practice_id);
		}
		$results_count = $this->get_results();
		$arr = "Number of appointments: " . $j . "<br>";
		$arr .= "Number of appointment reminders sent: " . $i . "<br>";
		$arr .= "Number of results obtained: " . $results_count . "<br>";
		$arr .= "Number of documents scanned: " . $results_scan . "<br>";
		return $arr;
	}
	
	public function check_extension($extension, $practice_id)
	{
		$result = Practiceinfo::find($practice_id);
		if ($result->$extension == 'y') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function updox_sync($practice_id)
	{
		$result = Practiceinfo::find($practice_id);
		$dir = $result->documents_dir . 'updox/';
		$files = scandir($dir);
		$count = count($files);
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$trim_line = str_replace(".pdf", "", $line);
			$line_parts = explode("_", $trim_line);
			$year = substr($line_parts[5], 0, 4);
			$month = substr($line_parts[5], 4, 2);
			$day = substr($line_parts[5], 6, 2);
			$dob = $year . "-" . $month . "-" . $day . " 00:00:00";
			$patient_result = Demographics::where('DOB', $dob)->where('lastname', $line_parts[1])->where('firstname', $line_parts[0])->first();
			if ($patient_result) {
				$original_file = $dir . $line;
				$new_file = $result->documents_dir . $patient_result->pid . "/" . $trim_line . '_' . time() . '.pdf';
				rename($original_file, $new_file);
				$from_pos = strpos($line_parts[3], " from ");
				if ($from_pos === FALSE) {
					$documents_desc = $line_parts[3];
					$documents_from = "Unknown";
				} else {
					$desc_parts = explode(" from ", $line_parts[3]);
					$documents_desc = $desc_parts[0];
					$documents_from = $desc_parts[1];
				}
				$documents_data = array(
					'documents_url' => $new_file,
					'pid' => $patient_result->pid,
					'documents_type' => $line_parts[2],
					'documents_desc' => $documents_desc,
					'documents_from' => $documents_from,
					'documents_date' => $line_parts[4]
				);
				DB::table('documents')->insert($documents_data);
				$this->audit('Add');
			} else {
				$filePath = __DIR__.'/../../public/scans/' . $line;
				$filePath1 = $dir . $line;
				$date = fileatime($filePath1);
				$fileDateTime = date('Y-m-d H:i:s', $date);
				$pdftext = file_get_contents($filePath1);
				$filePages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
				$scans_data = array(
					'fileName' => $line,
					'filePath' => $filePath,
					'fileDateTime' => $fileDateTime,
					'filePages' => $filePages
				);
				DB::table('scans')->insert($scans_data);
				$this->audit('Add');
				rename($filePath1, $filePath);
			}
		}
	}
	
	public function rcopia_sync($practice_id)
	{
		// Update Notification
		$row0 = Practiceinfo::find($practice_id);
		Config::set('app.timezone' , $row0->timezone);
		if ($row0->rcopia_update_notification_lastupdate == "") {
			$date0 = date('m/d/Y H:i:s', time());
		} else {
			$date0 = $row0->rcopia_update_notification_lastupdate;
		}
		$xml0 = "<Request><Command>update_notification</Command>";
		$xml0 .= "<LastUpdateDate>" . $date0 . "</LastUpdateDate>";
		$xml0 .= "</Request></RCExtRequest>";
		$result0 = $this->rcopia($xml0, $practice_id);
		$response0 = new SimpleXMLElement($result0);
		if ($response0->Response->Status == "error") {
			$description0 = $response0->Response->Error->Text . "";
			$data0a = array(
				'action' => 'update_notification',
				'pid' => '0',
				'extensions_name' => 'rcopia',
				'description' => $description0,
				'practice_id' => $practice_id
			);
			DB::table('extensions_log')->insert($data0a);
		} else {
			$last_update_date = $response0->Response->LastUpdateDate . "";
			$number = $response0->Response->NotificationList->Number . "";
			if ($number != "0") {
				foreach ($response0->Response->NotificationList->Notification as $notification) {
					$type = $notification->Type . "";
					$status = $notification->Status . "";
					$rcopia_username = $notification->Provider->Username . "";
					$medication_message = $notification->Sig->Drug->BrandName . "";
					$form_message = $notification->Sig->Drug->Form . "";
					$dose_message = $notification->Sig->Drug->Strength . "";
					$sig_message = $notification->Sig->Dose . "";
					$sig1_message = $notification->Sig->DoseUnit . "";
					$route_message = $notification->Sig->Route . "";
					$frequency_message = $notification->Sig->DoseTiming . "";
					$instructions_message = $notification->Sig->DoseOther . "";
					$quantity_message = $notification->Sig->Quantity . "";
					$quantity_message1 = $notification->Sig->QuantityUnit . "";
					$refill_message = $notification->Sig->Refills . "";
					$pharmacy_message = $notification->Pharmacy->Name . "";
					$medication_message = "Medication: " . $medication_message . ", " . $form_message . ", " . $dose_message;
					$medication_message .= "\nInstructions: " . $sig_message . " " . $sig1_message . " " . $route_message . ", " . $frequency_message;
					$medication_message .= "\nOther Instructions: " . $instructions_message;
					$medication_message .= "\nQuantity: " . $quantity_message . " " . $quantity_message1;
					$medication_message .= "\nRefills: " . $refill_message;
					$medication_message .= "\nPharmacy: " . $pharmacy_message;
					$messages_pid = $notification->Patient->ExternalID . "";
					$sender = $notification->Sender . "";
					$title = $notification->Title . "";
					$text = $notification->Text . "";
					$full_text = "From: " . $sender . "\nMessage: " . $text;
					$patient_row = Demographics::where('pid', '=', $messages_pid)->first();
					$dob_message = date("m/d/Y", strtotime($patient_row->DOB));
					$patient_name =  $patient_row->lastname . ', ' . $patient_row->firstname . ' (DOB: ' . $dob_message . ') (ID: ' . $messages_pid . ')';
					$provider_row = DB::table('users')
						->join('providers', 'providers.id', '=', 'users.id')
						->select('users.lastname', 'users.firstname', 'users.title', 'users.id')
						->where('providers.rcopia_username', '=', $rcopia_username)
						->first();
					if ($provider_row) {
						$provider_name = $provider_row->firstname . " " . $provider_row->lastname . ", " . $provider_row->title . " (" . $provider_row->id . ")";
						if ($type == "refill") {
							$subject = "Refill Request for " . $patient_name;
							$body = $medication_message;
						}
						if ($type == "message") {
							$subject = $title;
							$body = $full_text;
						}
						$data_message = array(
							'pid' => $messages_pid,
							'message_to' => $provider_name,
							'message_from' => $provider_row->id,
							'subject' => $subject,
							'body' => $body,
							'patient_name' => $patient_name,
							'status' => 'Sent',
							'mailbox' => $provider_row->id,
							'practice_id' => $practice_id
						);
						DB::table('messaging')->insert($data_message);
						$this->audit('Add');
					}
				}
			}
			$data_update = array(
				'rcopia_update_notification_lastupdate' => $last_update_date
			);
			DB::table('practiceinfo')->where('practice_id', '=', $practice_id)->update($data_update);
		}

		// Send Patient
		$query1 = Demographics::where('rcopia_sync', '=', 'n')->get();
		if ($query1) {
			foreach ($query1 as $row1) {
				if ($this->check_practice_id($row1->pid, $practice_id)) {
					$dob = explode(" ", $row1->DOB);
					$dob1 = explode("-", $dob[0]);
					$dob_final = $dob1[1] . "/" . $dob1[2] . "/" . $dob1[0];
					$xml1 = "<Request><Command>send_patient</Command><Synchronous>y</Synchronous><CheckEligibility>y</CheckEligibility>";
					$xml1 .= "<PatientList><Patient>";
					$xml1 .= "<FirstName>" . $row1->firstname . "</FirstName>";
					$xml1 .= "<LastName>" . $row1->lastname . "</LastName>";
					$xml1 .= "<MiddleName>" . $row1->middle . "</MiddleName>";
					$xml1 .= "<DOB>" . $dob_final . "</DOB>";
					$xml1 .= "<Sex>". $row1->sex . "</Sex>";
					$xml1 .= "<ExternalID>" . $row1->pid . "</ExternalID>";
					$xml1 .= "<HomePhone>" . $row1->phone_home . "</HomePhone>";
					$xml1 .= "<WorkPhone>" . $row1->phone_work . "</WorkPhone>";
					$xml1 .= "<Address1>" . $row1->address . "</Address1>";
					$xml1 .= "<Address2></Address2>";
					$xml1 .= "<City>" . $row1->city . "</City>";
					$xml1 .= "<State>" . $row1->state . "</State>";
					$xml1 .= "<Zip>" . $row1->zip . "</Zip>";
					$xml1 .= "</Patient></PatientList></Request></RCExtRequest>";
					$result1 = $this->rcopia($xml1, $practice_id);
					$response1 = new SimpleXMLElement($result1);
					$status1 = $response1->Response->PatientList->Patient->Status . "";
					if ($status1 == "error") {
						$description1 = $response1->Response->PatientList->Patient->Error->Text . "";
						$data1a = array(
							'action' => 'send_patient',
							'pid' => $row1->pid,
							'extensions_name' => 'rcopia',
							'description' => $description1,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data1a);
					} else {
						$data1b = array('rcopia_sync' => 'y');
						DB::table('demographics')->where('pid', '=', $row1->pid)->update($data1b);
						$this->audit('Update');
					}
				}
			}
		}
		
		// Send Allergy
		$query2 = Allergies::where('rcopia_sync', '=', 'n')->where('allergies_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($query2) {
			foreach ($query2 as $row2) {
				if ($this->check_practice_id($row2->pid, $practice_id)) {
					$da = explode(" ", $row2->allergies_date_active);
					$da1 = explode("-", $da[0]);
					$da_final = $da1[1] . "/" . $da1[2] . "/" . $da1[0];
					$xml2 = "<Request><Command>send_allergy</Command><Synchronous>y</Synchronous>";
					$xml2 .= "<AllergyList><Allergy>";
					$xml2 .= "<ExternalID>" . $row2->allergies_id . "</ExternalID>";
					$xml2 .= "<Patient><ExternalID>" . $row2->pid . "</ExternalID></Patient>";
					$xml2 .= "<Allergen><Name>" . $row2->allergies_med . "</Name>";
					$xml2 .= "<Drug><NDCID>" . $row2->meds_ndcid . "</NDCID></Drug></Allergen>";
					$xml2 .= "<Reaction>" . $row2->allergies_reaction . "</Reaction>";
					$xml2 .= "<OnsetDate>" . $da_final . "</OnsetDate>";
					$xml2 .= "</Allergy></AllergyList></Request></RCExtRequest>";
					$result2 = $this->rcopia($xml2, $practice_id);
					$response2 = new SimpleXMLElement($result2);
					$status2 = $response2->Response->AllergyList->Allergy->Status . "";
					if ($status2 == "error") {
						$description2 = $response2->Response->AllergyList->Allergy->Error->Text . "";
						$data2a = array(
							'action' => 'send_allergy',
							'pid' => $row2->pid,
							'extensions_name' => 'rcopia',
							'description' => $description2,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data2a);
						if ($description2 == "Can find neither name, Rcopia ID, or NDC ID for drug.") {
							$data2c = array('rcopia_sync' => 'ye');
							DB::table('allergies')->where('allergies_id', '=', $row2->allergies_id)->update($data2c);
							$this->audit('Update');
						}
					} else {
						$data2b = array('rcopia_sync' => 'y');
						DB::table('allergies')->where('allergies_id', '=', $row2->allergies_id)->update($data2b);
						$this->audit('Update');
					}
				}
			}
		}
		
		//Send Medication
		$query3 = Rx_list::where('rcopia_sync', '=', 'n')->where('rxl_date_inactive', '=', '0000-00-00 00:00:00')->where('rxl_date_old', '=', '0000-00-00 00:00:00')->get();
		if ($query3) {
			foreach ($query3 as $row3) {
				if ($this->check_practice_id($row1->pid, $practice_id)) {
					$dm = explode(" ", $row3->rxl_date_active);
					$dm1 = explode("-", $dm[0]);
					$dm_final = $dm1[1] . "/" . $dm1[2] . "/" . $dm1[0];
					if ($row3->rxl_due_date != '') {
						$dn = explode(" ", $row3->rxl_due_date);
						$dn1 = explode("-", $dn[0]);
						$dn_final = $dn1[1] . "/" . $dn1[2] . "/" . $dn1[0];
					} else {
						$dn_final = "";
					}
					if ($row3->rxl_ndcid != '') {
						$ndcid = $row3->rxl_ndcid;
						$generic_name = '';
						$form = '';
						$strength = '';
					} else {
						$ndcid = '';
						$medication_parts1 = explode(", ", $row3->rxl_medication);
						$generic_name = $medication_parts1[0];
						$form = $medication_parts1[1];
						$strength = $row3->rxl_dosage . " " . $row3->rxl_dosage_unit;
					}
					if ($row3->rxl_sig != '') {
						if(strpos($row3->rxl_sig, ' ') !== false) {
							$sig_parts1 = explode(" ", $row3->rxl_sig);
							$dose = $sig_parts1[0];
							$dose_unit = $sig_parts1[1];
						} else {
							$dose = $row3->rxl_sig;
							$dose_unit = '';
						}
					} else {
						$dose = '';
						$dose_unit = '';
					}
					if ($row3->rxl_quantity != '') {
						if(strpos($row3->rxl_quantity, ' ') !== false) {
							$quantity_parts1 = explode(" ", $row3->rxl_quantity);
							$quantity = $quantity_parts1[0];
							$quantity_unit = $quantity_parts1[1];
						} else {
							$quantity = $row3->rxl_quantity;
							$quantity_unit = '';
						}
					} else {
						$quantity = '';
						$quantity_unit = '';
					}
					if ($row3->rxl_daw != '') {
						$daw = 'n';
					} else {
						$daw = 'y';
					}
					$xml3 = "<Request><Command>send_medication</Command><Synchronous>y</Synchronous>";
					$xml3 .= "<MedicationList><Medication>";
					$xml3 .= "<ExternalID>" . $row3->rxl_id . "</ExternalID>";
					$xml3 .= "<Patient><ExternalID>" . $row3->pid . "</ExternalID></Patient>";
					$xml3 .= "<Sig>";
					$xml3 .= "<Drug><NDCID>" . $ndcid . "</NDCID>";
					$xml3 .= "<GenericName>" . $generic_name . "</GenericName>";
					$xml3 .= "<Form>" . $form . "</Form>";
					$xml3 .= "<Strength>" . $strength . "</Strength></Drug>";
					$xml3 .= "<Dose>" . $dose . "</Dose>";
					$xml3 .= "<DoseUnit>" . $dose_unit . "</DoseUnit>";
					$xml3 .= "<Route>" . $row3->rxl_route . "</Route>";
					$xml3 .= "<DoseTiming>" . $row3->rxl_frequency . "</DoseTiming>";
					$xml3 .= "<DoseOther>" . $row3->rxl_instructions . "</DoseOther>";
					$xml3 .= "<Quantity>" . $quantity . "</Quantity>";
					$xml3 .= "<QuantityUnit>" . $quantity_unit . "</QuantityUnit>";
					$xml3 .= "<Refills>" . $row3->rxl_refill . "</Refills>";
					$xml3 .= "<SubstitutionPermitted>" . $daw . "</SubstitutionPermitted>";
					$xml3 .= "</Sig>";
					$xml3 .= "<StartDate>" . $dm_final . "</StartDate>";
					$xml3 .= "<StopDate>" . $dn_final . "</StopDate>";
					$xml3 .= "</Medication></MedicationList></Request></RCExtRequest>";
					$result3 = $this->rcopia($xml3, $practice_id);
					$response3 = new SimpleXMLElement($result3);
					$status3 = $response3->Response->MedicationList->Medication->Status . "";
					if ($status3 == "error") {
						$description3 = $response3->Response->MedicationList->Medication->Error->Text . "";
						$data3a = array(
							'action' => 'send_medication',
							'pid' => $row3->pid,
							'extensions_name' => 'rcopia',
							'description' => $description3,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data3a);
					} else {
						$data3b = array('rcopia_sync' => 'y');
						DB::table('rx_list')->where('rxl_id', '=',$row3->rxl_id)->update($data3b);
						$this->audit('Update');
					}
				}
			}
		}
		
		//Send Problem List
		$query4 = Issues::where('rcopia_sync', '=', 'n')->where('issue_date_inactive', '=', '0000-00-00 00:00:00')->get();
		if ($query4) {
			foreach ($query4 as $row4) {
				if ($this->check_practice_id($row4->pid, $practice_id)) {
					$di = explode(" [", $row4->issue);
					$code = str_replace("]", "", $di[1]);
					$xml4 = "<Request><Command>send_problem</Command><Synchronous>y</Synchronous>";
					$xml4 .= "<ProblemList><Problem>";
					$xml4 .= "<ExternalID>" . $row4->issue_id . "</ExternalID>";
					$xml4 .= "<Patient><ExternalID>" . $row4->pid . "</ExternalID></Patient>";
					$xml4 .= "<Code>" . $code . "</Code>";
					$xml4 .= "<Description>" . $di[0] . "</Description>";
					$xml4 .= "</Problem></ProblemList></Request></RCExtRequest>";
					$result4 = $this->rcopia($xml4, $practice_id);
					$response4 = new SimpleXMLElement($result4);
					$status4 = $response4->Response->ProblemList->Problem->Status . "";
					if ($status4 == "error") {
						$description4 = $response4->Response->ProblemList->Problem->Error->Text . "";
						$data4a = array(
							'action' => 'send_problem',
							'pid' => $row4->pid,
							'extensions_name' => 'rcopia',
							'description' => $description4,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data4a);
					} else {
						$data4b = array('rcopia_sync' => 'y');
						DB::table('issues')->where('issue_id', '=',$row4->issue_id)->update($data4b);
						$this->audit('Update');
					}
				}
			}
		}
		
		//Delete Allergy
		$query5 = Allergies::where('rcopia_sync', '=', 'nd')->orWhere('rcopia_sync', '=', 'nd1')->get();
		if ($query5) {
			foreach ($query5 as $row5) {
				if ($this->check_practice_id($row5->pid, $practice_id)) {
					$dda = explode(" ", $row5->allergies_date_active);
					$daa1 = explode("-", $dda[0]);
					$dda_final = $dda1[1] . "/" . $dda1[2] . "/" . $dda1[0];
					$xml5 = "<Request><Command>send_allergy</Command><Synchronous>y</Synchronous>";
					$xml5 .= "<AllergyList><Allergy><Deleted>y</Deleted>";
					$xml5 .= "<ExternalID>" . $row5->allergies_id . "</ExternalID>";
					$xml5 .= "<Patient><ExternalID>" . $row5->pid . "</ExternalID></Patient>";
					$xml5 .= "<Allergen><Name>" . $row5->allergies_med . "</Name>";
					$xml5 .= "<Drug><NDCID>" . $row5->meds_ndcid . "</NDCID></Drug></Allergen>";
					$xml5 .= "<Reaction>" . $row5->allergies_reaction . "</Reaction>";
					$xml5 .= "<OnsetDate>" . $dda_final . "</OnsetDate>";
					$xml5 .= "</Allergy></AllergyList></Request></RCExtRequest>";
					$result5 = $this->rcopia($xml5, $practice_id);
					$response5 = new SimpleXMLElement($result5);
					$status5 = $response5->Response->AllergyList->Allergy->Status . "";
					if ($status5 == "error") {
						$description5 = $response5->Response->AllergyList->Allergy->Error->Text . "";
						$data5a = array(
							'action' => 'delete_allergy',
							'pid' => $row5->pid,
							'extensions_name' => 'rcopia',
							'description' => $description5,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data5a);
						$data5b = array('rcopia_sync' => 'y');
						DB::table('allergies')->where('pid',$row5->pid)->update($data5b);
						$this->audit('Update');
					} else {
						$data5b = array('rcopia_sync' => 'y');
						DB::table('allergies')->where('allergies_id',$row5->allergies_id)->update($data5b);
						$this->audit('Update');
					}
				}
			}
		}
		
		//Delete Medication
		$query6 = Rx_list::where('rcopia_sync', '=', 'nd')->orWhere('rcopia_sync', '=', 'nd1')->get();
		if ($query6) {
			foreach ($query6 as $row6) {
				if ($this->check_practice_id($row6->pid, $practice_id)) {
					$ddm = explode(" ", $row6->rxl_date_active);
					$ddm1 = explode("-", $ddm[0]);
					$ddm_final = $ddm1[1] . "/" . $ddm1[2] . "/" . $ddm1[0];
					if ($row3->rxl_due_date != '') {
						$ddn = explode(" ", $row6->rxl_due_date);
						$ddn1 = explode("-", $ddn[0]);
						$ddn_final = $ddn1[1] . "/" . $ddn1[2] . "/" . $ddn1[0];
					} else {
						$ddn_final = "";
					}
					if ($row6->rxl_ndcid != '') {
						$ndcid1 = $row6->rxl_ndcid;
						$generic_name1 = '';
						$form1 = '';
						$strength1 = '';
					} else {
						$ndcid1 = '';
						$medication_parts2 = explode(", ", $row6->rxl_medication);
						$generic_name1 = $medication_parts2[0];
						$form1 = $medication_parts2[1];
						$strength1 = $row6->rxl_dosage . " " . $row6->rxl_dosage_unit;
					}
					$sig_parts2 = explode(" ", $row6->rxl_sig);
					if ($row6->rxl_quantity != '') {
						$quantity_parts2 = explode(" ", $row6->rxl_quantity);
						$quantity1 = $quantity_parts2[0];
						$quantity_unit1 = $quantity_parts2[1];
					} else {
						$quantity1 = '';
						$quantity_unit1 = '';
					}
					if ($row6->rxl_daw != '') {
						$daw1 = 'n';
					} else {
						$daw1 = 'y';
					}
					$xml6 = "<Request><Command>send_medication</Command><Synchronous>y</Synchronous>";
					$xml6 .= "<MedicationList><Medication><Deleted>y</Deleted>";
					$xml6 .= "<ExternalID>" . $row6->rxl_id . "</ExternalID>";
					$xml6 .= "<Patient><ExternalID>" . $row6->pid . "</ExternalID></Patient>";
					$xml6 .= "<Sig>";
					$xml6 .= "<Drug><NDCID>" . $ndcid1 . "</NDCID>";
					$xml6 .= "<GenericName>" . $generic_name1 . "</GenericName>";
					$xml6 .= "<Form>" . $form1 . "</Form>";
					$xml6 .= "<Strength>" . $strength1 . "</Strength></Drug>";
					$xml6 .= "<Dose>" . $sig_parts2[0] . "</Dose>";
					$xml6 .= "<DoseUnit>" . $sig_parts2[1] . "</DoseUnit>";
					$xml6 .= "<Route>" . $row6->rxl_route . "</Route>";
					$xml6 .= "<DoseTiming>" . $row6->rxl_frequency . "</DoseTiming>";
					$xml6 .= "<DoseOther>" . $row6->rxl_instructions . "</DoseOther>";
					$xml6 .= "<Quantity>" . $quantity1 . "</Quantity>";
					$xml6 .= "<QuantityUnit>" . $quantity_unit1 . "</QuantityUnit>";
					$xml6 .= "<Refills>" . $row6->rxl_refill . "</Refills>";
					$xml6 .= "<SubstitutionPermitted>" . $daw2 . "</SubstitutionPermitted>";
					$xml6 .= "</Sig>";
					$xml6 .= "<StartDate>" . $ddm_final . "</StartDate>";
					$xml6 .= "<StopDate>" . $ddn_final . "</StopDate>";
					$xml6 .= "</Medication></MedicationList></Request></RCExtRequest>";
					$result6 = $this->rcopia($xml6, $practice_id);
					$response6 = new SimpleXMLElement($result6);
					$status6 = $response6->Response->MedicationList->Medication->Status . "";
					if ($status6 == "error") {
						$description6 = $response3->Response->MedicationList->Medication->Error->Text . "";
						$data6a = array(
							'action' => 'delete_medication',
							'pid' => $row6->pid,
							'extensions_name' => 'rcopia',
							'description' => $description6,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data6a);
						$data6b = array('rcopia_sync' => 'y');
						DB::table('rx_list')->where('pid', '=', $row6->pid)->update($data6b);
						$this->audit('Update');
					} else {
						$data6b = array('rcopia_sync' => 'y');
						DB::table('rx_list')->where('rxl_id', '=', $row6->rxl_id)->update($data6b);
						$this->audit('Update');
					}
				}
			}
		}
		
		//Delete Problem List
		$query7 = Issues::where('rcopia_sync', '=', 'nd')->orWhere('rcopia_sync', '=', 'nd1')->get();
		if ($query7) {
			foreach ($query7 as $row7) {
				if ($this->check_practice_id($row7->pid, $practice_id)) {
					$ddi = explode(" [", $row7->issue);
					$code1 = str_replace("]", "", $ddi[1]);
					$xml7 = "<Request><Command>send_problem</Command><Synchronous>y</Synchronous>";
					$xml7 .= "<ProblemList><Problem><Deleted>y</Deleted>";
					$xml7 .= "<ExternalID>" . $row7->issue_id . "</ExternalID>";
					$xml7 .= "<Patient><ExternalID>" . $row7->pid . "</ExternalID></Patient>";
					$xml7 .= "<Code>" . $code1 . "</Code>";
					$xml7 .= "<Description>" . $ddi[0] . "</Description>";
					$xml7 .= "</Problem></ProblemList></Request></RCExtRequest>";
					$result7 = $this->rcopia($xml7, $practice_id);
					$response7 = new SimpleXMLElement($result7);
					$status7 = $response7->Response->ProblemList->Problem->Status . "";
					if ($status7 == "error") {
						$description7 = $response7->Response->ProblemList->Problem->Error->Text . "";
						$data7a = array(
							'action' => 'delete_problem',
							'pid' => $row7->pid,
							'extensions_name' => 'rcopia',
							'description' => $description7,
							'practice_id' => $practice_id
						);
						DB::table('extensions_log')->insert($data7a);
						$data7b = array('rcopia_sync' => 'y');
						DB::table('issues')->where('pid', '=', $row7->pid)->update($data7b);
						$this->audit('Update');
					} else {
						$data7b = array('rcopia_sync' => 'y');
						DB::table('issues')->where('issue_id', '=', $row7->issue_id)->update($data7b);
						$this->audit('Update');
					}
				}
			}
		}
	}
	
	public function get_results()
	{
		$dir = '/srv/ftp/shared/import/';
		$files = scandir($dir);
		$count = count($files);
		$full_count=0;
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$file = $dir . $line;
			$hl7 = file_get_contents($file);
			$hl7_lines = explode("\r", $hl7);
			$results = array();
			$j = 0;
			foreach ($hl7_lines as $line) {
				$line_section = explode("|", $line);
				if ($line_section[0] == "MSH") {
					if (strpos($line_section[3], "LAB") !== FALSE) {
						$test_type = "Laboratory";
					} else {
						$test_type = "Imaging";
					}
				}
				if ($line_section[0] == "PID") {
					$name_section = explode("^", $line_section[5]);
					$lastname = $name_section[0];
					$firstname = $name_section[1];
					$year = substr($line_section[7], 0, 4);
					$month = substr($line_section[7], 4, 2);
					$day = substr($line_section[7], 6, 2);
					$dob = $year . "-" . $month . "-" . $day . " 00:00:00";
					$sex = strtolower($line_section[8]);
				}
				if ($line_section[0] == "ORC") {
					$provider_section = explode("^", $line_section[12]);
					$provider_lastname = $provider_section[1];
					$provider_firstname = $provider_section[2];
					$provider_id = $provider_section[0];
					$practice_section = explode("^", $line_section[17]);
					$practice_lab_id = $practice_section[0];
				}
				if ($line_section[0] == "OBX") {
					$test_name_section = explode("^", $line_section[3]);
					$results[$j]['test_name'] = $test_name_section[1];
					$results[$j]['test_result'] = $line_section[5];
					$results[$j]['test_units'] = $line_section[6];
					$results[$j]['test_reference'] = $line_section[7];
					$results[$j]['test_flags'] = $line_section[8];
					$year1 = substr($line_section[14], 0, 4);
					$month1 = substr($line_section[14], 4, 2);
					$day1 = substr($line_section[14], 6, 2);
					$hour1 = substr($line_section[14], 8, 2);
					$minute1 = substr($line_section[14], 10, 2);
					$results[$j]['test_datetime'] = $year1 . "-" . $month1 . "-" . $day1 . " " . $hour1 . ":" . $minute1 .":00";
					$j++;
				}
				if ($line_section[0] == "NTE") {
					$from = $line_section[3];
					$keys = array_keys($results);
					foreach ($keys as $key) {
						$results[$key]['test_from'] = $line_section[3];
					}
				}
			}
			$practice_row = Practiceinfo::where('peacehealth_id', '=', $practice_lab_id)->first();
			if ($practice_row) {
				$practice_id = $practice_row->practice_id;
				Config::set('app.timezone' , $practice_row->timezone);
			} else {
				$cmd = 'rm ' . $file;
				exec($cmd);
				exit (0);
			}
			$provider_row = DB::table('users')
				->join('providers', 'providers.id', '=', 'users.id')
				->select('users.lastname', 'users.firstname', 'users.title', 'users.id')
				->where('providers.peacehealth_id', '=', $provider_id)
				->first();
			if ($provider_row) {
				$provider_id = $provider_row->id;
			} else {
				$provider_id = '';
			}
			$patient_row = Demographics::where('lastname', '=', $lastname)->where('firstname', '=', $firstname)->where('DOB', '=', $dob)->where('sex', '=', $sex)->get();
			if ($patient_row) {
				$pid = $patient_row->pid;
				$dob_message = date("m/d/Y", strtotime($patient_row->DOB));
				$patient_name =  $patient_row->lastname . ', ' . $patient_row->firstname . ' (DOB: ' . $dob_message . ') (ID: ' . $pid . ')';
				$tests = 'y';
				$test_desc = "";
				$k = 0;
				foreach ($results as $results_row) {
					$test_data = array(
						'pid' => $pid,
						'test_name' => $results_row['test_name'],
						'test_result' => $results_row['test_result'],
						'test_units' => $results_row['test_units'],
						'test_reference' => $results_row['test_reference'],
						'test_flags' => $results_row['test_flags'],
						'test_from' => $from,
						'test_datetime' => $results_row['test_datetime'],
						'test_type' => $test_type,
						'test_provider_id' => $provider_id,
						'practice_id' => $practice_id
					);
					DB::table('tests')->insert($test_data);
					$this->audit('Add');
					if ($k == 0) {
						$test_desc .= $results_row['test_name'];
					} else {
						$test_desc .= ", " . $results_row['test_name'];
					}
					$k++;
				}
				$practice_row = Practiceinfo::find($practice_id);
				$directory = $practice_row->documents_dir . $pid;
				$file_path = $directory . '/tests_' . time() . '.pdf';
				$html = $this->page_intro('Test Results', $practice_id);
				$html .= $this->page_results($pid, $results, $patient_name);
				$this->generate_pdf($html, $file_path);
				$documents_date = date("Y-m-d H:i:s", time());
				$test_desc = 'Test results for ' . $patient_name;
				$pages_data = array(
					'documents_url' => $file_path,
					'pid' => $pid,
					'documents_type' => $test_type,
					'documents_desc' => $test_desc,
					'documents_from' => $from,
					'documents_date' => $documents_date
				);
				$documents_id = DB::table('documents')->insertGetId($pages_data);
				$this->audit('Add');
			} else {
				$messages_pid = '';
				$patient_name = "Unknown patient: " . $lastname . ", " . $firstname . ", DOB: " . $month . "/" . $day . "/" . $year;
				$tests = 'unk';
				foreach ($results as $results_row) {
					$test_data = array(
						'test_name' => $results_row['test_name'],
						'test_result' => $results_row['test_result'],
						'test_units' => $results_row['test_units'],
						'test_reference' => $results_row['test_reference'],
						'test_flags' => $results_row['test_flags'],
						'test_unassigned' => $patient_name,
						'test_from' => $from,
						'test_datetime' => $results_row['test_datetime'],
						'test_type' => $test_type,
						'test_provider_id' => $provider_id,
						'practice_id' => $practice_id
					);
					DB::table('tests')->insert($test_data);
					$this->audit('Add');
				}
				$documents_id = '';
			}
			$subject = "Test results for " . $patient_name;
			$body = "Test results for " . $patient_name . "\n\n";
			foreach ($results as $results_row1) {
				$body .= $results_row1['test_name'] . ": " . $results_row1['test_result'] . ", Units: " . $results_row1['test_units'] . ", Normal reference range: " . $results_row1['test_reference'] . ", Date: " . $results_row1['test_datetime'] . "\n";
			}
			$body .= "\n" . $from;
			if ($tests="unk") {
				$body .= "\n" . "Patient is unknown to the system.  Please reconcile this test result in your dashboard.";
			}
			if ($provider_id != '') {
				$provider_name = $provider_row->firstname . " " . $provider_row->lastname . ", " . $provider_row->title . " (" . $provider_id . ")";
				$data_message = array(
					'pid' => $pid,
					'message_to' => $provider_name,
					'message_from' => $provider_row['id'],
					'subject' => $subject,
					'body' => $body,
					'patient_name' => $patient_name,
					'status' => 'Sent',
					'mailbox' => $provider_id,
					'practice_id' => $practice_id,
					'documents_id' => $documents_id
				);
				DB::table('messaging')->insert($data_message);
				$this->audit('Add');
			}
			$cmd = 'rm ' . $file;
			exec($cmd);
			$full_count++;
		}
		return $full_count;
	}
	
	public function get_scans($practice_id)
	{
		$result = Practiceinfo::find($practice_id);
		Config::set('app.timezone' , $result->timezone);
		$dir = $result->documents_dir . 'scans/' . $practice_id;
		$files = scandir($dir);
		$count = count($files);
		$j=0;
		for ($i = 2; $i < $count; $i++) {
			$line = $files[$i];
			$filePath = $dir . "/" . $line;
			$check = DB::table('scans')->where('fileName', '=', $line)->first();
			if (!$check) {
				$date = fileatime($filePath);
				$fileDateTime = date('Y-m-d H:i:s', $date);
				$pdftext = file_get_contents($filePath);
				$filePages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
				$data = array(
					'fileName' => $line,
					'filePath' => $filePath,
					'fileDateTime' => $fileDateTime,
					'filePages' => $filePages,
					'practice_id' => $practice_id
				);
				DB::table('scans')->insert($data);
				$this->audit('Add');
				$j++;
			}
		}
		return $j;
	}
	
}
