<?php

class AjaxDashboardController extends BaseController {

	/**
	* NOSH ChartingSystem Dashboard Ajax Functions
	*/
	
	public function postDraftMessages()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$from = Session::get('displayname') . ' (' . Session::get('user_id') . ')';
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('t_messages')
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
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
				->join('demographics', 't_messages.pid', '=', 'demographics.pid')
				->where('t_messages.t_messages_from', '=', $from)
				->where('t_messages.t_messages_signed', '=', 'No')
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
	
	public function postDraftEncounters()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$user_id = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('encounters')
				->join('demographics', 'encounters.pid', '=', 'demographics.pid')
				->where('encounters.user_id', '=', $user_id)
				->where('encounters.encounter_signed', '=', 'No')
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
				->where('encounters.user_id', '=', $user_id)
				->where('encounters.encounter_signed', '=', 'No')
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
	
	public function postAlerts()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$provider = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $provider)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array) {
					$query_array->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Radiology results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
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
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_provider', '=', $provider)
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where(function($query_array1) {
					$query_array1->where('alerts.alert', '=', 'Laboratory results pending')
					->orWhere('alerts.alert', '=', 'Radiology results pending')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending')
					->orWhere('alerts.alert', '=', 'Referral pending')
					->orWhere('alerts.alert', '=', 'Laboratory results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Radiology results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Cardiopulmonary results pending - NEED TO OBTAIN')
					->orWhere('alerts.alert', '=', 'Reminder')
					->orWhere('alerts.alert', '=', 'REMINDER');
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
	}
	
	public function postMtmAlerts()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$practice_id = Session::get('practice_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('alerts')
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where('alerts.alert', '=', 'Medication Therapy Management')
				->where('alerts.practice_id', '=', $practice_id)
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
				->join('demographics', 'alerts.pid', '=', 'demographics.pid')
				->where('alerts.alert_date_complete', '=', '0000-00-00 00:00:00')
				->where('alerts.alert_reason_not_complete', '=', '')
				->where('alerts.alert', '=', 'Medication Therapy Management')
				->where('alerts.practice_id', '=', $practice_id)
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
	
	public function postGetDraftMessage($id)
	{
		$result = DB::table('t_messages')->where('t_messages_id', '=', $id)->first();
		if (Session::get('t_messages_id')) {
			Session::forget('t_messages_id');
		};
		echo json_encode($result);
	}
	
	public function postGetAlert($id)
	{
		$result = DB::table('alerts')->where('alert_id', '=', $id)->first();
		if (Session::get('alert_id')) {
			Session::forget('alert_id');
		};
		echo json_encode($result);
	}
	
	// Provider specific functions
	public function postProviderInfo()
	{
		$result = Providers::find(Session::get('user_id'))->toArray();
		echo json_encode($result);
	}
	
	public function postProviderInfo1()
	{
		$data = array(
			'specialty' => substr(Input::get('specialty'), 0, -13),
			'license' => Input::get('license'),
			'license_state' => Input::get('license_state'),
			'npi' => Input::get('npi'),
			'npi_taxonomy' => substr(Input::get('specialty'), -11, 10),
			'upin' => Input::get('upin'),
			'dea' => Input::get('dea'),
			'medicare' => Input::get('medicare'),
			'tax_id' => Input::get('tax_id'),
			'rcopia_username' => Input::get('rcopia_username'),
			'schedule_increment' => Input::get('schedule_increment'),
			'peacehealth_id' => Input::get('peacehealth_id')
		);
		DB::table('providers')->where('id', '=', Input::get('id'))->update($data);
		$this->audit('Update'); 
		echo "Provider information updated!";
	}
	
	public function postCheckRcopia()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Practiceinfo::find(Session::get('practice_id'));
			echo $result->rcopia_extension;
		}
	}
	
	public function postPreviewSignature()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$signature = Providers::find(Session::get('user_id'));
			if ($signature->signature != '') {
				$result = HTML::image($signature->signature, 'Provider Signature', array('border' => '0'));
			} else {
				$result = '';
			}
			echo $result;
		}
	}
	
	public function signatureupload()
	{
		$id = Session::get('user_id');
		$directory = __DIR__.'/../../public/images';
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'image/jpeg' && $file->getMimeType() != 'image/gif' && $file->getMimeType() != 'image/png') {
					echo "This is not an image file.  Try again.";
					exit (0);
				}
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.' . $file->getClientOriginalExtension();
				$file->move($directory, $new_name);
				$signature = $directory . "/" . $new_name;
				$data = array(
					'signature' => 'images/' . $new_name
				);
				DB::table('providers')->where('id', '=', $id)->update($data);
				$this->audit('Update');
				$img = $this->getImageFile($signature);
				if (imagesx($img) > 198 || imagesy($img) > 55) {
					$width = imagesx($img);
					$height = imagesy($img);
					$scaledDimensions = $this->getDimensions($width,$height,198,55);
					$scaledWidth = $scaledDimensions['scaledWidth'];
					$scaledHeight = $scaledDimensions['scaledHeight'];
					$scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
					imagecopyresampled($scaledImage, $img, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $width, $height);
					$this->saveImage($scaledImage, $signature);
				}
			}
		}
		echo 'Signature uploaded!';
	}
	
	public function postGetSignature()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$signature = Providers::find(Session::get('user_id'));
			if ($signature->signature != '') {
				$result['link'] = HTML::image($signature->signature, 'Provider Signature', array('border' => '0', 'id' => 'image_target'));
				$img = $this->getImageFile($signature->signature);
				$result['button'] = "";
				if (imagesx($img) > 198) {
					$result['message'] = "Image width is too large (less than 198px is recommended).  Use the cropping tool to get to the correct width.";
					$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
				} else {
					$result['message'] = "Image width is correct.";
				}
				if (imagesy($img) > 55) {
					$result['message'] .= "  Image height is too large (less than 55px is recommended).  Use the cropping tool to get to the correct height.";
					$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
				} else {
					$result['message'] .= "  Image height is correct.";
				}
			} else {
				$result['link'] = '';
				$result['message'] = '';
			}
			echo json_encode($result);
		}
	}
	
	public function postCropSignature()
	{
		$signature = Providers::find(Session::get('user_id'));
		$targ_w = 198;
		$targ_h = 55;
		$img_r = $this->getImageFile($signature->signature);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$x = Input::get('x');
		$y = Input::get('y');
		$w = Input::get('w');
		$h = Input::get('h');
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);
		$this->saveImage($dst_r, $signature->signature);
		$result['link'] = HTML::image($signature->signature, 'Provider Signature', array('border' => '0', 'id' => 'image_target'));
		$result['growl'] = "Signature cropped and saved!";
		$img = $this->getImageFile($signature->signature);
		$result['button'] = "";
		if (imagesx($img) > 198) {
			$result['message'] = "Image width is too large (less than 198px is recommended).  Use the cropping tool to get to the correct width.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] = "Image width is correct.";
		}
		if (imagesy($img) > 55) {
			$result['message'] .= "  Image height is too large (less than 55px is recommended).  Use the cropping tool to get to the correct height.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] .= "  Image height is correct.";
		}
		echo json_encode($result);
	}
	
	public function postChangeSignature()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$id = Session::get('user_id');
			$user = User::find($id);
			$name = $user->firstname . " " . $user->lastname;
			if ($name != Input::get('name')) {
				echo "Incorrect name!  Signature not saved.  Try again.";
			} else {
				$base_dir = __DIR__."/../../public/";
				$filename = "images/signature_" . $id . "_" . time() . ".png";
				$json = Input::get('output');
				$img = $this->sigJsonToImage($json);
				imagepng($img, $base_dir . $filename);
				imagedestroy($img);
				$data = array(
					'signature' => $filename
				);
				DB::table('providers')->where('id', '=', $id)->update($data);
				$this->audit('Update');
				echo "Signature created!";
			}
		}
	}
	
	// Demographic functions
	public function postDemographics()
	{
		$row = Demographics::find(Session::get('pid'))->toArray();
		echo json_encode($row);
	}
	
	public function postEditDemographics()
	{
		$pid = Session::get('pid');
		$dob = date('Y-m-d', strtotime(Input::get('DOB')));
		if (Input::get('reminder_method')=="Cellular Phone") {
			$meta = array("(", ")", "-", " ");
			$number = str_replace($meta, "", Input::get('phone_cell'));
			$reminder_to = $number . '@' . Input::get('cell_carrier');
		} else {
			if (Input::get('reminder_method')=="Email") {
				$reminder_to = Input::get('email');
			} else {
				$reminder_to = "";
			}
		}
		$data = array(
			'lastname' => Input::get('lastname'),
			'firstname' => Input::get('firstname'),
			'middle' => Input::get('middle'),
			'nickname' => Input::get('nickname'),
			'title' => Input::get('title'),
			'sex' => Input::get('sex'),
			'DOB'=> $dob,
			'ss' => Input::get('ss'),
			'race' => Input::get('race'),
			'ethnicity' => Input::get('ethnicity'),
			'language' => Input::get('language'),
			'address' => Input::get('address'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone_home' => Input::get('phone_home'),
			'phone_work' => Input::get('phone_work'),
			'phone_cell' => Input::get('phone_cell'),
			'email' => Input::get('email'),
			'marital_status' => Input::get('marital_status'),
			'partner_name' => Input::get('partner_name'),
			'employer' => Input::get('employer'),
			'emergency_contact' => Input::get('emergency_contact'),
			'emergency_phone' => Input::get('emergency_phone'),
			'reminder_method' => Input::get('reminder_method'),
			'reminder_to' => $reminder_to,
			'cell_carrier' => Input::get('cell_carrier'),
			'preferred_provider' => Input::get('preferred_provider'),
			'preferred_pharmacy' => Input::get('preferred_pharmacy'),
			'active' => Input::get('active'),
			'other1' => Input::get('other1'),
			'other2' => Input::get('other2'),
			'caregiver' => Input::get('caregiver'),
			'referred_by' => Input::get('referred_by'),
			'comments' => Input::get('comments'),
			'rcopia_sync' => 'n',
			'race_code' => Input::get('race_code'),
			'ethnicity_code' => Input::get('ethnicity_code'),
			'guardian_lastname' => Input::get('guardian_lastname'),
			'guardian_firstname' => Input::get('guardian_firstname'),
			'guardian_relationship' => Input::get('guardian_relationship'),
			'guardian_code' => Input::get('guardian_code'),
			'guardian_address' => Input::get('guardian_address'),
			'guardian_city' => Input::get('guardian_city'),
			'guardian_state' => Input::get('guardian_state'),
			'guardian_zip' => Input::get('guardian_zip'),
			'guardian_phone_home' => Input::get('guardian_phone_home'),
			'guardian_phone_work' => Input::get('guardian_phone_work'),
			'guardian_phone_cell' => Input::get('guardian_phone_cell'),
			'guardian_email' => Input::get('guardian_email'),
			'lang_code' => Input::get('lang_code')
		);
		DB::table('demographics')->where('pid', '=', $pid)->update($data);
		$this->audit('Update');
		$this->setpatient($pid);
		echo "OK";
	}
	
	public function postInsurance()
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'Yes')
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
		$query1 = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'Yes')
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
	
	public function postInsuranceInactive()
	{
		$pid = Session::get('pid');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'No')
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
		$query1 = DB::table('insurance')
			->where('pid', '=', $pid)
			->where('insurance_plan_active', '=', 'No')
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
	
	public function postEditInsurance()
	{
		$pid = Session::get('pid');
		$dob = date("Y-m-d", strtotime(Input::get('insurance_insu_dob')));
		$data = array(
			'insurance_plan_name' => Input::get('insurance_plan_name'),
			'address_id' => Input::get('address_id'),
			'insurance_id_num' => Input::get('insurance_id_num'),
			'insurance_group' => Input::get('insurance_group'),
			'insurance_order' => Input::get('insurance_order'),
			'insurance_relationship' => Input::get('insurance_relationship'),
			'insurance_copay' => Input::get('insurance_copay'),
			'insurance_deductible' => Input::get('insurance_deductible'),
			'insurance_comments' => Input::get('insurance_comments'),
			'insurance_insu_lastname' => Input::get('insurance_insu_lastname'),
			'insurance_insu_firstname' => Input::get('insurance_insu_firstname'),
			'insurance_insu_dob' => $dob,
			'insurance_insu_gender' => Input::get('insurance_insu_gender'),
			'insurance_insu_address' => Input::get('insurance_insu_address'),
			'insurance_insu_city' => Input::get('insurance_insu_city'),
			'insurance_insu_state' => Input::get('insurance_insu_state'),
			'insurance_insu_zip' => Input::get('insurance_insu_zip'),
			'insurance_insu_phone' => Input::get('insurance_insu_phone'),
			'insurance_plan_active' => 'Yes',
			'pid' => $pid
		);	
		if(Input::get('insurance_id') == '') {
			DB::table('insurance')->insert($data);
			echo "Insurance added!";
		} else {
			DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
			$this->audit('Update');
			echo "Insurance updated!";
		}
	}
	
	public function postInactivateInsurance()
	{
		$data = array(
			'insurance_plan_active' => 'No'
		);
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
		$this->audit('Update');
		echo "Insurance inactivated!";
	}
	
	public function postDeleteInsurance()
	{
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->delete();
		$this->audit('Delete');
		echo "Insurance deleted!";
	}

	public function postReactivateInsurance()
	{
		$data = array(
			'insurance_plan_active' => 'Yes'
		);
		DB::table('insurance')->where('insurance_id', '=', Input::get('insurance_id'))->update($data);
		$this->audit('Update');
		echo "Insurance reactivated!";
	}
	
	public function postEditInsuranceProvider()
	{
		$data = array(
			'displayname' => Input::get('facility'),
			'facility' => Input::get('facility'),
			'street_address1' => Input::get('street_address1'),
			'street_address2' => Input::get('street_address2'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone' => Input::get('phone'),
			'insurance_plan_payor_id' => Input::get('insurance_plan_payor_id'),
			'insurance_plan_type' => Input::get('insurance_plan_type'),
			'insurance_plan_assignment' => Input::get('insurance_plan_assignment'),
			'insurance_plan_ppa_phone' => Input::get('insurance_plan_ppa_phone'),
			'insurance_plan_ppa_fax' => Input::get('insurance_plan_ppa_fax'),
			'insurance_plan_ppa_url' => Input::get('insurance_plan_ppa_url'),
			'insurance_plan_mpa_phone' => Input::get('insurance_plan_mpa_phone'),
			'insurance_plan_mpa_fax' => Input::get('insurance_plan_mpa_fax'),
			'insurance_plan_mpa_url' => Input::get('insurance_plan_mpa_url'),
			'specialty' => 'Insurance',
			'insurance_box_31' => Input::get('insurance_box_31'),
			'insurance_box_32a' => Input::get('insurance_box_32a')
		);
		if(Input::get('address_id') == '') {
			$add = DB::table('addressbook')->insertGetId($data);
			$this->audit('Add');
			$result['message'] = "Insurance provider added!";
			$result['item'] = $data['displayname'];
			$result['id'] = $add;
			
		} else {
			DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->update($data);
			$this->audit('Update');
			$result['message'] = "Insurance provider updated!";
			$result['item'] = $data['displayname'];
			$result['id'] = Input::get('address_id');
		}
		echo json_encode($result);
	}
	
	public function postCopyAddress()
	{
		$row = Demographics::find(Session::get('pid'))->toArray();
		echo json_encode($row);
	}
	
	public function postCheckRegistrationCode()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Demographics::find(Session::get('pid'));
			if ($result->registration_code != '') {
				echo "Registration Code: " . $result->registration_code;
			} else {
				echo "n";
			}
		}
	}
	
	public function postRegisterPatient()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Session::get('pid');
			$length = 6;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$token = '';
			for ($i = 0; $i < $length; $i++) {
				$token .= $characters[mt_rand(0, strlen($characters)-1)];
			}
			$data = array(
				'registration_code' => $token
			);
			DB::table('demographics')->where('pid', '=', $pid)->update($data);
			$this->audit('Update');
			$result = Demographics::find($pid);
			if ($result->email != '') {
				$practice = Practiceinfo::find(Session::get('practice_id'));
				$data1 = array(
					'practicename' => $practice->practice_name,
					'url' => route('home'),
					'token' => $token
				);
				$this->send_mail('emails.loginregistrationcode', $data1, 'Patient Portal Registration Code', $result->email, Session::get('practice_id'));
			}
			echo "Registration Code: " . $token;
		}
	}
	
	// Test result functions
	public function postTests()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('tests')
				->whereNull('pid')
				->where('practice_id', '=', Session::get('practice_id'))
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
			$query1 = DB::table('tests')
				->whereNull('pid')
				->where('practice_id', '=', Session::get('practice_id'))
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
			$response['rows'] = $query1;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			$response['page'] = $page;
			if ($query1) {
				$response['rows'] = $query1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postTestsImport()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$pid = Input::get('pid');
			$tests_id_array = json_decode(Input::get('tests_id_array'));
			$i = 0;
			$results = array();
			foreach ($tests_id_array as $tests_id) {
				$data = array(
					'pid' => $pid,
					'test_unassigned' => ''
				);
				DB::table('tests')->where('tests_id', '=', $tests_id)->update($data);
				$this->audit('Update');
				$results[$i] = Tests::find($tests_id)->toArray();
				$provider_id = $results[$i]['test_provider_id'];
				$from = $results[$i]['test_from'];
				$test_type = $results[$i]['test_type'];
				$i++;
			}
			$patient_row = Demographics::find($pid)->toArray();
			$dob_message = date("m/d/Y", strtotime($patient_row['DOB']));
			$patient_name =  $patient_row['lastname'] . ', ' . $patient_row['firstname'] . ' (DOB: ' . $dob_message . ') (ID: ' . $pid . ')';
			$practice_row = Practiceinfo::find(Session::get('practice_id'))->toArray();
			$directory = $practice_row['documents_dir'] . $pid;
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
			if(Session::get('group_id') == '2') {
				$pages_data['documents_viewed'] = Session::get('displayname');
			}
			$documents_id = DB::table('documents')->insertGetId($pages_data);
			$this->audit('Add');
			if(Session::get('group_id') == '3') {
				$provider_row = User::find($provider_id)->toArray();
				$provider_name = $provider_row['firstname'] . " " . $provider_row['lastname'] . ", " . $provider_row['title'] . " (" . $provider_id . ")";
				$subject = "Test results for " . $patient_name;
				$body = "Test results for " . $patient_name . "\n\n";
				foreach ($results as $results_row1) {
					$body .= $results_row1['test_name'] . ": " . $results_row1['test_result'] . ", Units: " . $results_row1['test_units'] . ", Normal reference range: " . $results_row1['test_reference'] . ", Date: " . $results_row1['test_datetime'] . "\n";
				}
				$body .= "\n" . $from;
				$data_message = array(
					'pid' => $pid,
					'message_to' => $provider_name,
					'message_from' => Session::get('user_id'),
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
			echo $i;
		}
	}
	
	public function postDeleteTests()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$this->db->where('tests_id', $this->input->post('tests_id'));
			$this->db->delete('tests');
			echo "OK";
		}
	}
	
	// Admin specific functions
	public function postFindbackups()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row2 = Practiceinfo::find(1);
			$dir = $row2->documents_dir;
			$files = glob($dir . "*.sql");
			$data['options'] = array();
			arsort($files);
			foreach ($files as $file) {
				$explode = explode("_", $file);
				$time = intval(str_replace(".sql","",$explode[1]));
				$data['options'][$file] = date("Y-m-d H:i:s", $time);
			}
			echo json_encode($data);
		}
	}
	
	public function postBackuprestore()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$file = Input::get('file');
			$config_file = __DIR__."/../.env.php";
			$config = require($config_file);
			$command = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $config['mysql_database'] . " < " . $file;
			system($command);
			echo "OK";
		}
	}
	
	public function postFindpractices()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$practices = DB::table('practiceinfo')->where('practice_id' , '!=', '1')->get();
			$data['options'] = array();
			foreach ($practices as $practice) {
				$practice_id = $practice->practice_id;
				$data['options'][$practice_id] = $practice->practice_name;
			}
			echo json_encode($data);
		}
	}
	
	public function postCancelpractice()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$practice_id = Input::get('practice_id');
			$query1 = DB::table('users')->where('practice_id', '=', $practice_id)->where('group_id', '!=', '1')->get();
			if ($query1) {
				foreach ($query1 as $row1) {
					$active = '0';
					$disable = 'disable';
					$password = Hash::make($disable);
					$data = array(
						'active' => $active,
						'password' => $password
					);
					DB::table('users')->where('id', '=', $row1->id)->update($data);
					$this->audit('Update');
					$row2 = DB::table('demographics_relate')->where('id', '=', $row1->id)->where('practice_id', '=', $practice_id)->first();
					if ($row2) {
						$data1 = array(
							'id' => NULL
						);
						DB::table('demographics_relate')->where('demographics_relate_id', '=', $row2->demographics_relate_id)->update($data1);
						$this->audit('Update');
					}
				}
			}
			$data2 = array(
				'active' => 'N'
			);
			DB::table('practiceinfo')->where('practice_id', '=', $practice_id)->update($data2);
			$this->audit('Update');
			echo "Practice #" . $practice_id . " manually canceled!";
		}
	}
	
	public function postCheckPrintEntireChart()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array();
			$query = Demographics_relate::where('practice_id', '=', Session::get('practice_id'))->first();
			if ($query) {
				$data['response'] = true;
				Session::put('print_chart_percent', '0');
			} else {
				$data['response'] = false;
				$data['message'] = "No patients in your practice!";
			}
			echo json_encode($data);
		}
	}
	
	public function postPrintEntireChart()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$track = __DIR__.'/../../public/temp/track';
			File::put($track,'0');
			ini_set('memory_limit','196M');
			ini_set('max_execution_time', 300);
			$practice_id = Session::get('practice_id');
			$query = Demographics_relate::where('practice_id', '=', $practice_id)->get();
			$total = count($query);
			$i=0;
			$data = array();
			$zip_file_name = __DIR__.'/../../public/temp/charts_' . $practice_id . '.zip';
			if (file_exists($zip_file_name)) {
				unlink($zip_file_name);
			}
			$zip = new ZipArchive();
			if ($zip->open($zip_file_name, ZipArchive::CREATE) !== TRUE) {
				exit("Cannot open <$zip_file_name>\n");
			}
			foreach ($query as $row) {
				$file = $this->print_chart($row->pid, 'file', '', 'all');
				$zip->addFile($file);
				$i++;
				$percent = round($i/$total*100);
				File::put($track,$percent);
			}
			$zip->close();
			echo link_to_asset('temp/charts_' . $practice_id . '.zip', 'Download File', $attributes = array(), $secure = null);
		}
	}
	
	public function postCheckCsvPatientDemographics()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$query = Demographics_relate::where('practice_id', '=', Session::get('practice_id'))->first();
			if ($query) {
				$data['response'] = true;
				Session::put('csv_percent', '0');
			} else {
				$data['response'] = false;
				$data['message'] = "No patients in your practice!";
			}
			echo json_encode($data);
		}
	}
	
	function postGenerateCsvPatientDemographics()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$track = __DIR__.'/../../public/temp/track';
			File::put($track,'0');
			ini_set('memory_limit','196M');
			ini_set('max_execution_time', 300);
			$practice_id = Session::get('practice_id');
			$query = Demographics_relate::where('practice_id', '=', $practice_id)->get();
			$total = count($query);
			$i=0;
			$csv = "Last Name;First Name;Gender;Date of Birth;Address;City;State;Zip;Home Phone;Work Phone;Cell Phone";
			foreach ($query as $row) {
				$row1 = DB::table('demographics')
					->select('lastname', 'firstname', 'sex', 'DOB', 'address', 'city', 'state', 'zip', 'phone_home', 'phone_work', 'phone_cell')
					->where('pid', '=', $row->pid)
					->first();
				$csv .= "\n";
				$array = (array) $row1;
				$csv .= implode(";", $array);
				$i++;
				$percent = round($i/$total*100);
				File::put($track,$percent);
			}
			$csv_file_name = __DIR__.'/../../public/temp/csv_' . $practice_id . '.csv';
			if (file_exists($csv_file_name)) {
				unlink($csv_file_name);
			}
			File::put($csv_file_name, $csv);
			echo link_to_asset('temp/csv_' . $practice_id . '.csv', 'Download File', $attributes = array(), $secure = null);
		}
	}
	
	// Configuration dialog functions
	public function postOrdersList($type, $user_type)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3' && Session::get('group_id') != '4') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if ($user_type == 'Global') {
				$user_id = '0';
			} else {
				$user_id = Session::get('user_id');
			}
			$user_id = Session::get('user_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('orderslist')
				->where('orders_category', '=', $type)
				->where('user_id', '=', $user_id)
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
			$query1 = DB::table('orderslist')
				->where('orders_category', '=', $type)
				->where('user_id', '=', $user_id)
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
	
	public function postAddOrderslist()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
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
	}
	
	public function postCheckSnomedExtension()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		echo $result->snomed_extension;
	}
	
	public function postDeleteOrdersList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('orderslist')->where('orderslist_id', '=', Input::get('orderslist_id'))->delete();
			$this->audit('Delete');
			echo "Template entry deleted.";
		}
	}
	
	public function postCptList($mask='')
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			if($mask == ''){
				$query = DB::table('cpt')->get();
			} else {
				$query = DB::table('cpt')
					->where('cpt_description', 'LIKE', "%$mask%")
					->orWhere('cpt', 'LIKE', "%$mask%")
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
				$query1 = DB::table('cpt')
					->orderBy($sidx, $sord)
					->skip($start)
					->take($limit)
					->get();
			} else {
				$query1 = DB::table('cpt')
					->where('cpt_description', 'LIKE', "%$mask%")
					->orWhere('cpt', 'LIKE', "%$mask%")
					->orderBy($sidx, $sord)
					->skip($start)
					->take($limit)
					->get();
			}
			$response['page'] = $page;
			$response['total'] = $total_pages;
			$response['records'] = $count;
			if ($query1) {
				$records1 = array();
				$i = 0;
				foreach ($query1 as $records_row) {
					$query2_row = DB::table('cpt_relate')->where('cpt', '=', $records_row->cpt)
						->where('practice_id', '=', Session::get('practice_id'))
						->first();
					if ($query2_row) {
						$records1[$i] = array(
							'cpt_id' => $records_row->cpt_id,
							'cpt_relate_id' => $query2_row->cpt_relate_id,
							'cpt' => $query2_row->cpt,
							'cpt_description' => $query2_row->cpt_description,
							'cpt_charge' => $query2_row->cpt_charge,
							'favorite' => $query2_row->favorite,
							'unit' => $query2_row->unit
						);
					} else {
						$records1[$i] = array(
							'cpt_id' => $records_row->cpt_id,
							'cpt_relate_id' => '',
							'cpt' => $records_row->cpt,
							'cpt_description' => $records_row->cpt_description,
							'cpt_charge' => '',
							'favorite' => '0',
							'unit' => '1'
						);
					}
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postEditCptList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			if (Input::get('cpt_charge') != '') {
				$charge = str_replace("$", "", Input::get('cpt_charge'));
				$pos = strpos($charge, ".");
				if ($pos === FALSE) {
					$charge .= ".00";
				}
			} else {
				$charge = '';
			}
			$data_relate = array(
				'cpt' => Input::get('cpt'),
				'cpt_description' => Input::get('cpt_description'),
				'cpt_charge' => $charge,
				'practice_id' => Session::get('practice_id'),
				'favorite' => Input::get('favorite'),
				'unit' => Input::get('unit')
			);
			if (Input::get('cpt_relate_id') != '') {
				DB::table('cpt_relate')->where('cpt_relate_id', '=', Input::get('cpt_relate_id'))->update($data_relate);
				$this->audit('Update');
				$arr['message'] = "  CPT updated!";
			} else {
				DB::table('cpt_relate')->insert($data_relate);
				$this->audit('Add');
				$query = DB::table('cpt')->where('cpt', '=', Input::get('cpt'))->first();
				if (!$query) {
					$data_cpt = array(
						'cpt' => Input::get('cpt'),
						'cpt_description' => Input::get('cpt_description')
					);
					DB::table('cpt')->insert($data_cpt);
					$this->audit('Add');
				}
				$arr['message'] = "  CPT added!";
			}
			$arr['charge'] = $charge;
			echo json_encode($arr);
		}
	}
	
	public function postDeleteCpt()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('cpt')->where('cpt_id', '=', Input::get('id'))->delete();
			$this->audit('Delete');
			echo "CPT code deleted!";
		}
	}
	
	public function postGetTemplate()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row = Templates::find(Input::get('template_id'));
			$array = unserialize($row->array);
			echo $array;
		}
	}
	
	public function postDeleteTemplate()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('templates')->where('template_id', '=', Input::get('template_id'))->delete();
			$this->audit('Delete');
			echo "Form template deleted!";
		}
	}
	
	public function postDefaultTemplate()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$row = DB::table('templates')->where('template_id', '=', Input::get('template_id'))->first();
			if ($row->default == 'n') {
				$data = array(
					'default' => 'y'
				);
				$message = "Form template marked as default!";
			} else {
				$data = array(
					'default' => 'n'
				);
				$message = "Form template unmarked as default!";
			}
			DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($data);
			$this->audit('Update');
			echo $message;
		}
	}
	
	public function templatedownload($id)
	{
		$result = DB::table('templates')->where('template_id', '=', $id)->first();
		$array_row = (array) $result;
		unset($array_row['template_id']);
		unset($array_row['practice_id']);
		$array_values = array_values($array_row);
		$array_key = array_keys($array_row);
		$csv = implode("\t", $array_key);
		$csv .= "\n" . implode("\t", $array_values);
		$file_path = __DIR__."/../../public/temp/" . time() . "_" . $result->category . "_template.txt";
		File::put($file_path, $csv);
		return Response::download($file_path);
	}
	
	public function templateupload()
	{
		$directory = __DIR__.'/../../public/temp';
		$i = 0;
		$error = '';
		foreach (Input::file('file') as $file) {
			if ($file) {
				$file->move($directory, $file->getClientOriginalName());
				$file_path = $directory . '/' . $file->getClientOriginalName();
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$csv = File::get($file_path);
				$csv_line = explode("\n", $csv);
				if (count($csv_line) >= 2) {
					$headers = explode("\t", $csv_line[0]);
					$l = 1;
					for ($k = 0; $k < count($csv_line); $k++) {
						$values = explode("\t", $csv_line[$l]);
						$row = array();
						for ($j = 0; $j < count($headers); $j++) {
							$row[$headers[$j]] = $values[$j];
						}
						$row['practice_id'] = Session::get('practice_id');
						DB::table('templates')->insert($row);
						$this->audit('Add');
						$k++;
						$l++;
					}
					$i += $k;
				} else {
					$error = "<br>Incorrect format.";
				}
				unlink($file_path);
			} else {
				$error = '<br>Invalid file!';
			}
		}
		return "Imported " . $i . " templates." . $error;
	}
	
	public function texttemplatedownload($id)
	{
		$result = DB::table('templates')->where('template_id', '=', $id)->first();
		$array_row = (array) $result;
		unset($array_row['template_id']);
		unset($array_row['practice_id']);
		$array_values = array_values($array_row);
		$array_key = array_keys($array_row);
		$csv = implode("\t", $array_key);
		$csv .= "\n" . implode("\t", $array_values);
		$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $result->template_name)->where('group', '=', $result->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->get();
		foreach ($query as $row) {
			$array_row1 = (array) $row;
			unset($array_row1['template_id']);
			unset($array_row1['practice_id']);
			$array_values1 = array_values($array_row1);
			$csv .= "\n" . implode("\t", $array_values1);
		}
		$file_path = __DIR__."/../../public/temp/" . time() . "_" . $result->group . "_template.txt";
		File::put($file_path, $csv);
		return Response::download($file_path);
	}
	
	public function textmacrodownload($id)
	{
		$query = DB::table('templates')->where('category', '=', 'specific')->where('practice_id', '=', Session::get('practice_id'))->where('template_name', '=', $id)->get();
		$i = 0;
		$csv = '';
		foreach ($query as $row) {
			$array_row1 = (array) $row;
			unset($array_row1['template_id']);
			unset($array_row1['practice_id']);
			if ($i == 0) {
				$array_key1 = array_keys($array_row1);
				$csv .= implode("\t", $array_key1);
			}
			$array_values1 = array_values($array_row1);
			$csv .= "\n" . implode("\t", $array_values1);
			$i++;
		}
		$file_path = __DIR__."/../../public/temp/" . time() . "_" . $id . "_template.txt";
		File::put($file_path, $csv);
		return Response::download($file_path);
	}
	
	public function postPatientFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'forms')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'forms')
				->where('practice_id', '=', $practice_id)
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$records1[$i]['scoring'] = $row->scoring;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSavePatientForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id'),
				'scoring' => Input::get('scoring')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id'),
				'scoring' => Input::get('scoring')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'forms',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id'),
				'scoring' => Input::get('scoring')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postHpiFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'hpi')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'hpi')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveHpiForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'hpi',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postRosFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'ros')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'ros')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$records1[$i]['default'] = $row->default;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveRosForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => 'm',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => 'f',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				$template_data1['default'] = 'n';
				$template_data2['default'] = 'n';
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('template_name', '=', $template_row->template_name)
					->where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'ros',
				'sex' => Input::get('sex'),
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postPeFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'pe')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'pe')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$records1[$i]['default'] = $row->default;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSavePeForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => 'm',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => 'f',
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				$template_data1['default'] = 'n';
				$template_data2['default'] = 'n';
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('template_name', '=', $template_row->template_name)
					->where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'n',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'pe',
				'sex' => Input::get('sex'),
				'group' => Input::get('group'),
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postSituationFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'situation')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'situation')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveSituationForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'situation',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'situation',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'situation',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postReferralFormsList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'referral')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'referral')
				->where('practice_id', '=', $practice_id)
				->where('template_name', '!=', 'Global Default')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['sex'] = $row->sex;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postSaveReferralForm($type)
	{
		if ($type == 'user') {
			$user_id = Session::get('user_id');
		} else {
			$user_id = "0";
		}
		$group = str_replace(" ", "_", strtolower(Input::get('template_name')));
		$array = serialize(Input::get('array'));
		if (Input::get('sex') == 'b') {
			$template_data1 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'referral',
				'sex' => 'm',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			$template_data2 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'referral',
				'sex' => 'f',
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data1);
				$this->audit('Add');
				DB::table('templates')->insert($template_data2);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				$template_row = Templates::find(Input::get('template_id'));
				if ($template_row->sex == 'm') {
					$template_id1 = Input::get('template_id');
				} else {
					$template_id2 = Input::get('template_id');
				}
				$template_row1 = Templates::where('group', '=', $template_row->group)
					->where('template_id', '!=', Input::get('template_id'))
					->first();
				if ($template_row1) {
					if ($template_row1->sex == 'm') {
						$template_id1 = $template_row1->template_id;
					} else {
						$template_id2 = $template_row1->template_id;
					}
					DB::table('templates')->where('template_id', '=', $template_id1)->update($template_data1);
					$this->audit('Update');
					DB::table('templates')->where('template_id', '=', $template_id2)->update($template_data2);
					$this->audit('Update');
				} else {
					if ($template_row->sex == 'm') {
						DB::table('templates')->insert($template_data2);
						$this->audit('Add');
					} else {
						DB::table('templates')->insert($template_data1);
						$this->audit('Add');
					}
				}
				$message = "Form updated as a template!";
			}
		} else {
			$template_data3 = array(
				'user_id' => $user_id,
				'default' => 'default',
				'template_name' => Input::get('template_name'),
				'age' => Input::get('age'),
				'category' => 'referral',
				'sex' => Input::get('sex'),
				'group' => $group,
				'array' => $array,
				'practice_id' => Session::get('practice_id')
			);
			if (Input::get('template_id') == '') {
				DB::table('templates')->insert($template_data3);
				$this->audit('Add');
				$message = "Form added as a template!";
			} else {
				DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($template_data3);
				$this->audit('Update');
				$message = "Form updated as a template!";
			}
		}
		echo $message;
	}
	
	public function postTextdumpList()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'text')
				->where('practice_id', '=', $practice_id)
				->where('array', '=', '')
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
			$query1 = DB::table('templates')
				->where('category', '=', 'text')
				->where('practice_id', '=', $practice_id)
				->where('array', '=', '')
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['age'] = $row->age;
					$records1[$i]['sex'] = $row->sex;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postTextdumpList1($id)
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$group = DB::table('templates')->where('template_id', '=', $id)->first();
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'text')
				->where('practice_id', '=', $practice_id)
				->where('array', '!=', '')
				->where('group', '=', $group->group)
				->where('age', '=', $group->age)
				->where('sex', '=', $group->sex)
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
			$query1 = DB::table('templates')
				->where('category', '=', 'text')
				->where('practice_id', '=', $practice_id)
				->where('array', '!=', '')
				->where('group', '=', $group->group)
				->where('age', '=', $group->age)
				->where('sex', '=', $group->sex)
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_id'] = $row->template_id;
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['array'] = $row->array;
					$records1[$i]['group'] = $row->group;
					$records1[$i]['default'] = $row->default;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postTextdumpListSpecific()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'specific')
				->where('practice_id', '=', $practice_id)
				->groupby('template_name')
				->distinct()
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
			$query1 = DB::table('templates')
				->where('category', '=', 'specific')
				->where('practice_id', '=', $practice_id)
				->groupby('template_name')
				->distinct()
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_name'] = $row->template_name;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postEncounterTemplates()
	{
		if (Session::get('group_id') != '2' && Session::get('group_id') != '3') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$practice_id = Session::get('practice_id');
			$pid = Session::get('pid');
			$query = DB::table('templates')
				->where('category', '=', 'encounter_templates')
				->where('practice_id', '=', $practice_id)
				->where('array', '=', '')
				->groupby('template_name')
				->distinct()
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
			$query1 = DB::table('templates')
				->where('category', '=', 'encounter_templates')
				->where('practice_id', '=', $practice_id)
				->where('array', '=', '')
				->groupby('template_name')
				->distinct()
				->orderBy($sidx, $sord)
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
					$records1[$i]['template_name'] = $row->template_name;
					$records1[$i]['scoring'] = $row->scoring;
					$i++;
				}
				$response['rows'] = $records1;
			} else {
				$response['rows'] = '';
			}
			echo json_encode($response);
		}
	}
	
	public function postListmacros()
	{
		$return = '';
		$query = DB::table('templates')
			->where('category', '=', 'specific')
			->where('practice_id', '=', Session::get('practice_id'))
			->groupby('template_name')
			->distinct()
			->get();
		if ($query) {
			$i = 0;
			foreach ($query as $row) {
				if ($i != 0) {
					$return .= '<br>';
				}
				$return .= '*' . $row->template_name . '* = ';
				$query1 = DB::table('templates')
					->where('category', '=', 'specific')
					->where('practice_id', '=', Session::get('practice_id'))
					->where('template_name', '=', $row->template_name)
					->limit(3)
					->get();
				if ($query1) {
					$j = 0;
					foreach ($query1 as $row1) {
						if ($j != 0) {
							$return .= ', ';
						}
						$return .= $row1->array;
						$j++;
					}
				}
				$i++;
			}
		}
		echo $return;
	}
	
	public function postSaveTextdumpgroup()
	{
		$data = array(
			'array' => '',
			'category' => 'text',
			'template_name' => Input::get('template_name'),
			'practice_id' => Session::get('practice_id'),
			'group' => Input::get('group'),
			'sex' => Input::get('sex'),
			'age' => Input::get('age')
		);
		if (Input::get('template_id') != '') {
			$group = DB::table('templates')->where('template_id', '=', Input::get('template_id'))->first();
			$query = DB::table('templates')->where('category', '=', 'text')->where('template_name', '=', $group->template_name)->where('group', '=', $group->group)->where('practice_id', '=', Session::get('practice_id'))->where('array', '!=', '')->get();
			foreach ($query as $row) {
				$data1 = array(
					'template_name' => Input::get('template_name'),
					'group' => Input::get('group'),
					'sex' => Input::get('sex'),
					'age' => Input::get('age')
				);
				DB::table('templates')->where('template_id', '=', $row->template_id)->update($data1);
				$this->audit('Update');
			}
			DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($data);
			$this->audit('Update');
			$arr = "Group updated";
		} else {
			DB::table('templates')->insert($data);
			$this->audit('Add');
			$arr = "Group added";
		}
		echo $arr;
	}
	
	public function postSaveTextdump()
	{
		$data = array(
			'array' => Input::get('array'),
			'category' => 'text',
			'template_name' => Input::get('template_name'),
			'practice_id' => Session::get('practice_id'),
			'group' => Input::get('group')
		);
		if (Input::get('template_id') != '') {
			DB::table('templates')->where('template_id', '=', Input::get('template_id'))->update($data);
			$this->audit('Update');
			$arr = "Template updated";
		} else {
			DB::table('templates')->insert($data);
			$this->audit('Add');
			$arr = "Template added";
		}
		echo $arr;
	}
	
	public function postCheckFax()
	{
		$result = Practiceinfo::find(Session::get('practice_id'));
		if ($result->fax_type != "") {
			echo "Yes";
		} else {
			echo "No";
		}
	}
	
	public function postVisitTypeList()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$practice_id = Session::get('practice_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('calendar')
				->where('active', '=', 'y')
				->where('practice_id', '=', $practice_id)
				->where('provider_id', '=', Session::get('user_id'))
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
			$query1 = DB::table('calendar')
				->where('active', '=', 'y')
				->where('practice_id', '=', $practice_id)
				->where('provider_id', '=', Session::get('user_id'))
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
	
	public function postEditVisitTypeList()
	{
		if (Session::get('group_id') != '2') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$data = array(
				'visit_type' => Input::get('visit_type'),
				'duration' => Input::get('duration'),
				'classname' => Input::get('classname'),
				'active' => 'y',
				'provider_id' => Session::get('user_id'),
				'practice_id' => Session::get('practice_id')
			);
			$action = Input::get('oper');
			if ($action == 'edit') {
				DB::table('calendar')->insert($data);
				$this->audit('Add');
				$data1 = array(
					'active' => 'n'
				);
				DB::table('calendar')->where('calendar_id', '=', Input::get('id'))->update($data1);
				$this->audit('Update');
			}
			if ($action == 'add') {
				DB::table('calendar')->insert($data);
				$this->audit('Add');
			}
			if ($action == 'del') {
				$data1 = array(
					'active' => 'n'
				);
				DB::table('calendar')->where('calendar_id', '=', Input::get('id'))->update($data1);
				$this->audit('Update');
			}
		}
	}
	
	public function postPrintEntireCcda()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$track = __DIR__.'/../../public/temp/track';
			File::put($track,'0');
			ini_set('memory_limit','196M');
			ini_set('max_execution_time', 300);
			$practice_id = Session::get('practice_id');
			$query = Demographics_relate::where('practice_id', '=', $practice_id)->get();
			$zip_file_name = 'ccda_' . $practice_id . '.zip';
			$zip_file = __DIR__.'/../../public/temp/' . $zip_file_name;
			if (file_exists($zip_file)) {
				unlink($zip_file);
			}
			$zip = new ZipArchive();
			if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
				exit("Cannot open <$zip_file>\n");
			}
			$files_array = array();
			$i = 0;
			$count = count($query);
			foreach ($query as $row) {
				$filename = 'ccda_' . $row->pid . "_" . time() . ".xml";
				$file = __DIR__.'/../../public/temp/' . $filename;
				$query1 = DB::table('demographics')->where('pid', '=', $row->pid)->first();
				if ($query1) {
					$ccda = $this->generate_ccda('',$row->pid);
					File::put($file, $ccda);
					$files_array[$i]['file'] = $file;
					$files_array[$i]['filename'] = $filename;
					$i++;
					$percent = round($i/$count*100);
					File::put($track,$percent);
				}
			}
			foreach ($files_array as $ccda1) {
				$zip->addFile($ccda1['file'], $ccda1['filename']);
			}
			$zip->close();
			while(!file_exists($zip_file)) {
				sleep(2);
			}
			echo link_to_asset('temp/' . $zip_file_name, 'Download File', $attributes = array(), $secure = null);
		}
	}
	
	public function postNoshexport()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			ini_set('max_execution_time', 300);
			$zip_file_name = 'noshexport_' . Session::get('practice_id') . '.zip';
			$zip_file = __DIR__.'/../../public/temp/' . $zip_file_name;
			if (file_exists($zip_file)) {
				unlink($zip_file);
			}
			$track = __DIR__.'/../../public/temp/track';
			File::put($track,'0');
			$zip = new ZipArchive;
			$zip->open($zip_file, ZipArchive::CREATE);
			$documents_dir = Session::get('documents_dir');
			$config_file = __DIR__."/../../.env.php";
			$config = require($config_file);
			$database = $config['mysql_database'] . "_copy";
			$connect = mysqli_connect('localhost', $config['mysql_username'], $config['mysql_password']);
			if ($connect) {
				if (mysqli_select_db($connect, $database)) {
					$sql = "DROP DATABASE " . $database;
					mysqli_query($connect,$sql);
				}
				$sql = "CREATE DATABASE " . $database;
				if (mysqli_query($connect,$sql)) {
					$command = "mysqldump --no-data -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $config['mysql_database'] . " | mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $database;
					system($command);
					Schema::connection('mysql2')->drop('audit');
					Schema::connection('mysql2')->drop('ci_sessions');
					Schema::connection('mysql2')->drop('cpt');
					Schema::connection('mysql2')->drop('curr_associationrefset_d');
					Schema::connection('mysql2')->drop('curr_attributevaluerefset_f');
					Schema::connection('mysql2')->drop('curr_complexmaprefset_f');
					Schema::connection('mysql2')->drop('curr_concept_f');
					Schema::connection('mysql2')->drop('curr_description_f');
					Schema::connection('mysql2')->drop('curr_langrefset_f');
					Schema::connection('mysql2')->drop('curr_relationship_f');
					Schema::connection('mysql2')->drop('curr_simplemaprefset_f');
					Schema::connection('mysql2')->drop('curr_simplerefset_f');
					Schema::connection('mysql2')->drop('curr_stated_relationship_f');
					Schema::connection('mysql2')->drop('curr_textdefinition_f');
					Schema::connection('mysql2')->drop('cvx');
					Schema::connection('mysql2')->drop('extensions_log');
					Schema::connection('mysql2')->drop('gc');
					Schema::connection('mysql2')->drop('groups');
					Schema::connection('mysql2')->drop('guardian_roles');
					Schema::connection('mysql2')->drop('icd9');
					Schema::connection('mysql2')->drop('icd10');
					Schema::connection('mysql2')->drop('lang');
					Schema::connection('mysql2')->drop('meds_full');
					Schema::connection('mysql2')->drop('meds_full_package');
					Schema::connection('mysql2')->drop('migrations');
					Schema::connection('mysql2')->drop('npi');
					Schema::connection('mysql2')->drop('orderslist1');
					Schema::connection('mysql2')->drop('pos');
					Schema::connection('mysql2')->drop('sessions');
					Schema::connection('mysql2')->drop('snomed_procedure_imaging');
					Schema::connection('mysql2')->drop('snomed_procedure_path');
					Schema::connection('mysql2')->drop('supplements_list');
					File::put($track,'10');
					$practiceinfo = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
					$practiceinfo_data = (array) $practiceinfo;
					$practiceinfo_data['practice_id'] = '1';
					DB::connection('mysql2')->table('practiceinfo')->insert($practiceinfo_data);
					if ($practiceinfo->practice_logo != '') {
						$practice_logo_file = __DIR__.'/../../public/' . $practiceinfo->practice_logo;
						$localPath4 = str_replace($documents_dir,'/',$practice_logo_file);
						if (file_exists($practice_logo_file)) {
							$zip->addFile($practice_logo_file,$localPath4);
						}
					}
					$addressbook = DB::table('addressbook')->get();
					if ($addressbook) {
						foreach ($addressbook as $addressbook_row) {
							DB::connection('mysql2')->table('addressbook')->insert((array) $addressbook_row);
						}
					}
					$calendar = DB::table('calendar')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($calendar) {
						foreach ($calendar as $calendar_row) {
							DB::connection('mysql2')->table('calendar')->insert((array) $calendar_row);
						}
					}
					DB::connection('mysql2')->table('calendar')->update(array('practice_id' => '1'));
					$cpt_relate = DB::table('cpt_relate')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($cpt_relate) {
						foreach ($cpt_relate as $cpt_relate_row) {
							DB::connection('mysql2')->table('cpt_relate')->insert((array) $cpt_relate_row);
						}
					}
					DB::connection('mysql2')->table('cpt_relate')->update(array('practice_id' => '1'));
					$pid_arr = array();
					$demographics_relate = DB::table('demographics_relate')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($demographics_relate) {
						foreach ($demographics_relate as $demographics_relate_row) {
							DB::connection('mysql2')->table('demographics_relate')->insert((array) $demographics_relate_row);
							$pid_arr[] = $demographics_relate_row->pid;
						}
					}
					DB::connection('mysql2')->table('demographics_relate')->update(array('practice_id' => '1'));
					$era = DB::table('era')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($era) {
						foreach ($era as $era_row) {
							DB::connection('mysql2')->table('era')->insert((array) $era_row);
						}
					}
					DB::connection('mysql2')->table('era')->update(array('practice_id' => '1'));
					$messaging = DB::table('messaging')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($messaging) {
						foreach ($messaging as $messaging_row) {
							DB::connection('mysql2')->table('messaging')->insert((array) $messaging_row);
						}
					}
					DB::connection('mysql2')->table('messaging')->update(array('practice_id' => '1'));
					$orderslist = DB::table('orderslist')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($orderslist) {
						foreach ($orderslist as $orderslist_row) {
							DB::connection('mysql2')->table('orderslist')->insert((array) $orderslist_row);
						}
					}
					DB::connection('mysql2')->table('orderslist')->update(array('practice_id' => '1'));
					$provider_id_arr = array();
					$providers = DB::table('providers')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($providers) {
						foreach ($providers as $providers_row) {
							DB::connection('mysql2')->table('providers')->insert((array) $providers_row);
							$provider_id_arr[] = $providers_row->id;
							if ($providers_row->signature != '') {
								$signature_file = __DIR__.'/../../public/' . $providers_row->signature;
								$localPath5 = str_replace($documents_dir,'/',$signature_file);
								if (file_exists($signature_file)) {
									$zip->addFile($signature_file,$localPath5);
								}
							}
						}
					}
					DB::connection('mysql2')->table('providers')->update(array('practice_id' => '1'));
					$procedurelist = DB::table('procedurelist')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($procedurelist) {
						foreach ($procedurelist as $procedurelist_row) {
							DB::connection('mysql2')->table('procedurelist')->insert((array) $procedurelist_row);
						}
					}
					DB::connection('mysql2')->table('procedurelist')->update(array('practice_id' => '1'));
					$received = DB::table('received')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($received) {
						foreach ($received as $received_row) {
							DB::connection('mysql2')->table('received')->insert((array) $received_row);
							if ($received_row->filePath != '') {
								$localPath3 = str_replace($documents_dir,'/',$scans_row->filePath);
								if (file_exists($received_row->filePath)) {
									$zip->addFile($received_row->filePath,$localPath3);
								}
							}
						}
					}
					DB::connection('mysql2')->table('received')->update(array('practice_id' => '1'));
					$scans = DB::table('scans')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($scans) {
						foreach ($scans as $scans_row) {
							DB::connection('mysql2')->table('scans')->insert((array) $scans_row);
							if ($scans_row->filePath != '') {
								$localPath2 = str_replace($documents_dir,'/',$scans_row->filePath);
								if (file_exists($scans_row->filePath)) {
									$zip->addFile($scans_row->filePath,$localPath2);
								}
							}
						}
					}
					DB::connection('mysql2')->table('scans')->update(array('practice_id' => '1'));
					$job_id_arr = array();
					$sendfax = DB::table('sendfax')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($sendfax) {
						foreach ($sendfax as $sendfax_row) {
							DB::connection('mysql2')->table('sendfax')->insert((array) $sendfax_row);
							$job_id_arr[] = $sendfax_row->job_id;
						}
					}
					DB::connection('mysql2')->table('sendfax')->update(array('practice_id' => '1'));
					$supplement_inventory = DB::table('supplement_inventory')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($supplement_inventory) {
						foreach ($supplement_inventory as $supplement_inventory_row) {
							DB::connection('mysql2')->table('supplement_inventory')->insert((array) $supplement_inventory_row);
						}
					}
					DB::connection('mysql2')->table('supplement_inventory')->update(array('practice_id' => '1'));
					$tags_id_arr = array();
					$tags_relate = DB::table('tags_relate')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($tags_relate) {
						foreach ($tags_relate as $tags_relate_row) {
							DB::connection('mysql2')->table('tags_relate')->insert((array) $tags_relate_row);
							$tags_id_arr[] = $tags_relate_row->tags_id;
						}
					}
					DB::connection('mysql2')->table('tags_relate')->update(array('practice_id' => '1'));
					$templates = DB::table('templates')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($templates) {
						foreach ($templates as $templates_row) {
							DB::connection('mysql2')->table('templates')->insert((array) $templates_row);
						}
					}
					DB::connection('mysql2')->table('templates')->update(array('practice_id' => '1'));
					$users = DB::table('users')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($users) {
						foreach ($users as $users_row) {
							DB::connection('mysql2')->table('users')->insert((array) $users_row);
						}
					}
					DB::connection('mysql2')->table('users')->update(array('practice_id' => '1'));
					$vaccine_inventory = DB::table('vaccine_inventory')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($vaccine_inventory) {
						foreach ($vaccine_inventory as $vaccine_inventory_row) {
							DB::connection('mysql2')->table('vaccine_inventory')->insert((array) $vaccine_inventory_row);
						}
					}
					DB::connection('mysql2')->table('vaccine_inventory')->update(array('practice_id' => '1'));
					$vaccine_temp = DB::table('vaccine_temp')->where('practice_id', '=', Session::get('practice_id'))->get();
					if ($vaccine_temp) {
						foreach ($vaccine_temp as $vaccine_temp_row) {
							DB::connection('mysql2')->table('vaccine_temp')->insert((array) $vaccine_temp_row);
						}
					}
					DB::connection('mysql2')->table('vaccine_temp')->update(array('practice_id' => '1'));
					File::put($track,'20');
					if (!empty($pid_arr)) {
						$i = 0;
						$pid_count = count($pid_arr);
						foreach ($pid_arr as $pid) {
							$demographics = DB::table('demographics')->where('pid', '=', $pid)->first();
							DB::connection('mysql2')->table('demographics')->insert((array) $demographics);
							$alerts = DB::table('alerts')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($alerts as $alerts_row) {
								DB::connection('mysql2')->table('alerts')->insert((array) $alerts_row);
							}
							DB::connection('mysql2')->table('alerts')->update(array('practice_id' => '1'));
							$allergies = DB::table('allergies')->where('pid', '=', $pid)->get();
							foreach ($allergies as $allergies_row) {
								DB::connection('mysql2')->table('allergies')->insert((array) $allergies_row);
							}
							$billing_core1 = DB::table('billing_core')->where('pid', '=', $pid)->where('eid', '=', '0')->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($billing_core1 as $billing_core1_row) {
								DB::connection('mysql2')->table('billing_core')->insert((array) $billing_core1_row);
							}
							DB::connection('mysql2')->table('billing_core')->update(array('practice_id' => '1'));
							$demographics_notes = DB::table('demographics_notes')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($demographics_notes as $demographics_notes_row) {
								DB::connection('mysql2')->table('demographics_notes')->insert((array) $demographics_notes_row);
							}
							DB::connection('mysql2')->table('demographics_notes')->update(array('practice_id' => '1'));
							$documents = DB::table('documents')->where('pid', '=', $pid)->get();
							foreach ($documents as $documents_row) {
								DB::connection('mysql2')->table('documents')->insert((array) $documents_row);
							}
							$eid_arr = array();
							$encounters = DB::table('encounters')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($encounters as $encounters_row) {
								DB::connection('mysql2')->table('encounters')->insert((array) $encounters_row);
								$eid_arr[] = $encounters_row->eid;
							}
							DB::connection('mysql2')->table('encounters')->update(array('practice_id' => '1'));
							$forms = DB::table('forms')->where('pid', '=', $pid)->get();
							foreach ($forms as $forms_row) {
								DB::connection('mysql2')->table('forms')->insert((array) $forms_row);
							}
							$hippa = DB::table('hippa')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($hippa as $hippa_row) {
								DB::connection('mysql2')->table('hippa')->insert((array) $hippa_row);
							}
							DB::connection('mysql2')->table('hippa')->update(array('practice_id' => '1'));
							$hippa_request = DB::table('hippa_request')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($hippa_request as $hippa_request_row) {
								DB::connection('mysql2')->table('hippa_request')->insert((array) $hippa_request_row);
							}
							DB::connection('mysql2')->table('hippa_request')->update(array('practice_id' => '1'));
							$immunizations = DB::table('immunizations')->where('pid', '=', $pid)->get();
							foreach ($immunizations as $immunizations_row) {
								DB::connection('mysql2')->table('immunizations')->insert((array) $immunizations_row);
							}
							$insurance = DB::table('insurance')->where('pid', '=', $pid)->get();
							foreach ($insurance as $insurance_row) {
								DB::connection('mysql2')->table('insurance')->insert((array) $insurance_row);
							}
							$issues = DB::table('issues')->where('pid', '=', $pid)->get();
							foreach ($issues as $issues_row) {
								DB::connection('mysql2')->table('issues')->insert((array) $issues_row);
							}
							$mtm = DB::table('mtm')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($mtm as $mtm_row) {
								DB::connection('mysql2')->table('mtm')->insert((array) $mtm_row);
							}
							DB::connection('mysql2')->table('mtm')->update(array('practice_id' => '1'));
							$orders = DB::table('orders')->where('pid', '=', $pid)->get();
							foreach ($orders as $orders_row) {
								DB::connection('mysql2')->table('orders')->insert((array) $orders_row);
							}
							$rx_list = DB::table('rx_list')->where('pid', '=', $pid)->get();
							foreach ($rx_list as $rx_list_row) {
								DB::connection('mysql2')->table('rx_list')->insert((array) $rx_list_row);
							}
							$sup_list = DB::table('sup_list')->where('pid', '=', $pid)->get();
							foreach ($sup_list as $sup_list_row) {
								DB::connection('mysql2')->table('sup_list')->insert((array) $sup_list_row);
							}
							$tests = DB::table('tests')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($tests as $tests_row) {
								DB::connection('mysql2')->table('tests')->insert((array) $tests_row);
							}
							DB::connection('mysql2')->table('tests')->update(array('practice_id' => '1'));
							$t_messages = DB::table('t_messages')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($t_messages as $t_messages_row) {
								DB::connection('mysql2')->table('t_messages')->insert((array) $t_messages_row);
							}
							DB::connection('mysql2')->table('t_messages')->update(array('practice_id' => '1'));
							$rootPath = realpath($documents_dir . $pid);
							if (file_exists($rootPath)) {
								$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::SELF_FIRST);
								foreach ($files as $name => $file) {
									if(in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) {
										continue;
									} else {
										if (is_dir($file) === true) {
											continue; 
										} else {
											$filePath = $file->getRealPath();
											$localPath = str_replace($documents_dir,'/',$filePath);
											if ($filePath != '' && file_exists($filePath)) {
												$zip->addFile($filePath,$localPath);
											}
										}
									}
								}
							}
							$i++;
							$percent = round($i/$pid_count*50) + 20;
							File::put($track,$percent);
						}
					}
					if (!empty($provider_id_arr)) {
						foreach ($provider_id_arr as $provider_id) {
							$repeat_schedule = DB::table('repeat_schedule')->where('provider_id', '=', $provider_id)->get();
							foreach ($repeat_schedule as $repeat_schedule_row) {
								DB::connection('mysql2')->table('repeat_schedule')->insert((array) $repeat_schedule_row);
							}
						}
					}
					if (!empty($job_id_arr)) {
						foreach ($job_id_arr as $job_id) {
							$pages = DB::table('pages')->where('job_id', '=', $job_id)->get();
							foreach ($pages as $pages_row) {
								DB::connection('mysql2')->table('pages')->insert((array) $pages_row);
							}
							$recipients = DB::table('recipients')->where('job_id', '=', $job_id)->get();
							foreach ($recipients as $recipients_row) {
								DB::connection('mysql2')->table('recipients')->insert((array) $recipients_row);
							}
							$rootPath1 = realpath($documents_dir . 'sentfax/' . $job_id);
							if (file_exists($rootPath1)) {
								$files1 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath1), RecursiveIteratorIterator::SELF_FIRST);
								foreach ($files1 as $name1 => $file1) {
									if(in_array(substr($file1, strrpos($file1, '/')+1), array('.', '..'))) {
										continue;
									} else {
										if (is_dir($file1) === true) {
											continue; 
										} else {
											$filePath1 = $file1->getRealPath();
											$localPath1 = str_replace($documents_dir,'/',$filePath1);
											if ($filePath1 != '' && file_exists($filePath1)) {
												$zip->addFile($filePath1,$localPath1);
											}
										}
									}
								}
							}
						}
					}
					if (!empty($tags_id_arr)) {
						foreach ($tags_id_arr as $tags_id) {
							$tags = DB::table('tags')->where('tags_id', '=', $tags_id)->get();
							foreach ($tags as $tags_row) {
								$tagstest = DB::connection('mysql2')->table('tags')->where('tags_id', '=', $tags_id)->first();
								if (!$tagstest) {
									DB::connection('mysql2')->table('tags')->insert((array) $tags_row);
								}
							}
						}
					}
					if (!empty($eid_arr)) {
						$j = 0;
						$eid_count = count($eid_arr);
						foreach ($eid_arr as $eid) {
							$assessment = DB::table('assessment')->where('eid', '=', $eid)->first();
							if ($assessment) {
								DB::connection('mysql2')->table('assessment')->insert((array) $assessment);
							}
							$billing = DB::table('billing')->where('eid', '=', $eid)->get();
							foreach ($billing as $billing_row) {
								DB::connection('mysql2')->table('billing')->insert((array) $billing_row);
							}
							$billing_core2 = DB::table('billing_core')->where('pid', '=', $pid)->where('eid', '=',  $eid)->where('practice_id', '=', Session::get('practice_id'))->get();
							foreach ($billing_core2 as $billing_core2_row) {
								DB::connection('mysql2')->table('billing_core')->insert((array) $billing_core2_row);
							}
							DB::connection('mysql2')->table('billing_core')->update(array('practice_id' => '1'));
							$hpi = DB::table('hpi')->where('eid', '=', $eid)->first();
							if ($hpi) {
								DB::connection('mysql2')->table('hpi')->insert((array) $hpi);
							}
							$image = DB::table('image')->where('eid', '=', $eid)->get();
							foreach ($image as $image_row) {
								DB::connection('mysql2')->table('image')->insert((array) $image_row);
							}
							$labs = DB::table('labs')->where('eid', '=', $eid)->first();
							if ($labs) {
								DB::connection('mysql2')->table('labs')->insert((array) $labs);
							}
							$other_history = DB::table('other_history')->where('eid', '=', $eid)->get();
							foreach ($other_history as $other_history_row) {
								DB::connection('mysql2')->table('other_history')->insert((array) $other_history_row);
							}
							$pe = DB::table('pe')->where('eid', '=', $eid)->first();
							if ($pe) {
								DB::connection('mysql2')->table('pe')->insert((array) $pe);
							}
							$plan = DB::table('plan')->where('eid', '=', $eid)->first();
							if ($plan) {
								DB::connection('mysql2')->table('plan')->insert((array) $plan);
							}
							$procedure = DB::table('procedure')->where('eid', '=', $eid)->first();
							if ($procedure) {
								DB::connection('mysql2')->table('procedure')->insert((array) $procedure);
							}
							$ros = DB::table('ros')->where('eid', '=', $eid)->first();
							if ($ros) {
								DB::connection('mysql2')->table('ros')->insert((array) $ros);
							}
							$rx = DB::table('rx')->where('eid', '=', $eid)->first();
							if ($rx) {
								DB::connection('mysql2')->table('rx')->insert((array) $rx);
							}
							$vitals = DB::table('vitals')->where('eid', '=', $eid)->first();
							if ($vitals) {
								DB::connection('mysql2')->table('vitals')->insert((array) $vitals);
							}
							$i++;
							$percent1 = round($j/$eid_count*25) + 70;
							File::put($track,$percent1);
						}
					}
					$sqlfilename = 'noshexport_' . time() . '.sql';
					$sqlfile = __DIR__.'/../../public/temp/' . $sqlfilename;
					$command = "mysqldump -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $database . " > " . $sqlfile;
					system($command);
					if (!file_exists($sqlfile)) {
						sleep(2);
					}
					$zip->addFile($sqlfile, $sqlfilename);
					$mess = "Export file created successfully!";
				} else {
					$mess = "Error creating database: " . mysqli_error($connect);
				}
			}
			mysqli_close($connect);
			$zip->close();
			File::put($track,'100');
			echo link_to_asset('temp/' . $zip_file_name, 'Download File', $attributes = array(), $secure = null);
		}
	}
	
	public function importupload()
	{
		if (Session::get('group_id') != '1') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			ini_set('max_execution_time', 300);
			$directory = __DIR__.'/../../public/temp';
			foreach (Input::file('file') as $file) {
				if ($file) {
					$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.' . $file->getClientOriginalExtension();
					$file->move($directory,$file->getClientOriginalName());
					$zip = new ZipArchive;
					$open = $zip->open($directory . '/' . $file->getClientOriginalName());
					if ($open === TRUE) {
						$sqlsearch = glob(Session::get('documents_dir') . 'noshexport_*.sql');
						if (count($sqlsearch) > 0) {
							foreach ($sqlsearch as $sqlfile) {
								$config_file = __DIR__."/../.env.php";
								$config = require($config_file);
								$command = "mysql -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $config['mysql_database'] . " < " . $sqlfile;
								system($command);
								unlink($sqlfile);
							}
							$zip->extractTo(Session::get('documents_dir'));
							$zip->close();
							unlink($directory . '/' . $file->getClientOriginalName());
							echo "Upload and importing NOSH export file successful!";
						} else {
							unlink($directory . '/' . $file->getClientOriginalName());
							echo "This is not a proper zip file.  Missing SQL file.  Try again.";
						}
					} else {
						unlink($directory . '/' . $file->getClientOriginalName());
						echo "This is not a proper zip file.  Try again.";
					}
				}
			}
		}
	}
	
	public function postProgressbarTrack()
	{
		$track = __DIR__.'/../../public/temp/track';
		if (file_exists($track)) {
			echo File::get($track);
		} else {
			echo '0';
		}
	}
	
	public function postDeleteProgress()
	{
		$track = __DIR__.'/../../public/temp/track';
		if (file_exists($track)) {
			unlink($track);
		}
		echo "OK";
	}
}
