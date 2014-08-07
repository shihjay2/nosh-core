<?php

class AjaxSetupController extends BaseController {

	/**
	* NOSH ChartingSystem Setup Ajax Functions
	*/
	
	public function postGetPractice()
	{
		$practice = DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->first();
		$data = (array) $practice;
		echo json_encode($data);
	}
	
	public function postSetup1()
	{
		$data = array(
			'practice_name' => Input::get('practice_name'),
			'street_address1' => Input::get('street_address1'),
			'street_address2' => Input::get('street_address2'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone' => Input::get('phone'),
			'fax' => Input::get('fax'),
			'email' => Input::get('email'),
			'website' => Input::get('website'),
			'additional_message' => Input::get('additional_message')
		);
		if (Session::get('practice_id') == '1') {
			$data['smtp_user'] = Input::get('smtp_user');
			$data['smtp_pass'] = Input::get('smtp_pass');
			$data['patient_portal'] = Input::get('patient_portal');
			$query = DB::table('practiceinfo')->where('practice_id', '!=', '1')->get();
			if ($query) {
				foreach ($query as $practice_row) {
					if ($practice_row->patient_portal != '') {
						$portal_array = explode("/", $practice_row->patient_portal);
						$new_portal = Input::get('patient_portal') . "/" . $portal_array[4];
						$data1 = array(
							'smtp_user' => Input::get('smtp_user'),
							'smtp_pass' => Input::get('smtp_pass'),
							'patient_portal' => $new_portal
						);
						DB::table('practiceinfo')->where('practice_id', '=', $practice_row->practice_id)->update($data1);
						$this->audit('Update');
					}
				}
			}
		}
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo 'Practice settings updated';
	}
	
	public function postCheckDir()
	{
		$dir = Input::get('documents_dir');
		if ( ! is_writable($dir)){
			$result = 'You need to set the folder to writable permissions.';
		} else {
			$result = 'OK';
		}
		echo $result;
	}
	
	public function postCheckpt($pid)
	{
		$query = DB::table('demographics_relate')->where('pid', '=', $pid)->where('practice_id', '=', Session::get('practice_id'))->first();
		if ($query) {
			echo "true";
		} else {
			echo "false";
		}
	}
	
	public function postSetup2()
	{
		$data = array(
			'primary_contact' => Input::get('primary_contact'),
			'npi' => Input::get('npi'),
			'medicare' => Input::get('medicare'),
			'tax_id' => Input::get('tax_id'),
			'weight_unit' => Input::get('weight_unit'),
			'height_unit' => Input::get('height_unit'),
			'temp_unit' => Input::get('temp_unit'),
			'hc_unit' => Input::get('hc_unit'),
			'default_pos_id' => Input::get('default_pos_id'),
			'documents_dir' => Input::get('documents_dir'),
			'icd' => Input::get('icd'),
			'supplements_menu_item' => Input::get('supplements_menu_item'),
			'immunizations_menu_item' => Input::get('immunizations_menu_item'),
			'encounter_template' => Input::get('encounter_template'),
			'opennotes' => Input::get('opennotes')
		);
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo 'Practice settings updated!';
	}
	
	public function postSetup3()
	{
		$data = array(
			'fax_type' => Input::get('fax_type'),
			'fax_email' => Input::get('fax_email'),
			'fax_email_password' => Input::get('fax_email_password'),
			'fax_email_hostname' => Input::get('fax_email_hostname'),
			'fax_email_smtp' => Input::get('fax_email_smtp'),
			'phaxio_api_key' => Input::get('phaxio_api_key'),
			'phaxio_api_secret' => Input::get('phaxio_api_secret')
		);
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo 'Fax settings updated';
	}
	
	public function postSetup4()
	{
		$data = array(
			'billing_street_address1' => Input::get('billing_street_address1'),
			'billing_street_address2' => Input::get('billing_street_address2'),
			'billing_city' => Input::get('billing_city'),
			'billing_state' => Input::get('billing_state'),
			'billing_zip' => Input::get('billing_zip')
		);
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo 'Billing settings updated';
	}
	
	public function postGetPracticeLogo()
	{
		$row = Practiceinfo::find(Session::get('practice_id'));
		if ($row->practice_logo != '') {
			$result['link'] = HTML::image($row->practice_logo, 'Practice Logo', array('border' => '0', 'id' => 'image_target'));
			$img = $this->getImageFile($row->practice_logo);
			$result['button'] = "";
			if (imagesx($img) > 350) {
				$result['message'] = "Image width is too large (less than 350px is recommended).  Use the cropping tool to get to the correct width.";
				$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
			} else {
				$result['message'] = "Image width is correct.";
			}
			if (imagesy($img) > 100) {
				$result['message'] .= "  Image height is too large (less than 100px is recommended).  Use the cropping tool to get to the correct height.";
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
	
	public function practicelogoupload()
	{
		$directory = __DIR__.'/../../public/images';
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'image/jpeg' && $file->getMimeType() != 'image/gif' && $file->getMimeType() != 'image/png') {
					echo "This is not an image file.  Try again.";
					exit (0);
				}
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.' . $file->getClientOriginalExtension();
				$file->move($directory, $new_name);
				$practice_logo = $directory . "/" . $new_name;
				$data = array(
					'practice_logo' => 'images/' . $new_name
				);
				DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
				$this->audit('Update');
				$img = $this->getImageFile($practice_logo);
				if (imagesx($img) > 350 || imagesy($img) > 100) {
					$width = imagesx($img);
					$height = imagesy($img);
					$scaledDimensions = $this->getDimensions($width,$height,350,100);
					$scaledWidth = $scaledDimensions['scaledWidth'];
					$scaledHeight = $scaledDimensions['scaledHeight'];
					$scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
					imagecopyresampled($scaledImage, $img, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $width, $height);
					$this->saveImage($scaledImage, $practice_logo);
				}
			}
		}
		echo 'Practice logo uploaded!';
	}
	
	public function postNoPracticeLogo()
	{
		$data = array(
			'practice_logo' => ''
		);
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
	}
	
	public function postCropimage()
	{
		$row = Practiceinfo::find(Session::get('practice_id'));
		$targ_w = 350;
		$targ_h = 100;
		$img_r = $this->getImageFile($row->practice_logo);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		$x = Input::get('x');
		$y = Input::get('y');
		$w = Input::get('w');
		$h = Input::get('h');
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);
		$this->saveImage($dst_r, $row->practice_logo);
		$result['link'] = HTML::image($row->practice_logo, 'Practice Logo', array('border' => '0', 'id' => 'image_target'));
		$result['growl'] = "Logo cropped and saved!";
		$img = $this->getImageFile($row->practice_logo);
		$result['button'] = "";
		if (imagesx($img) > 350) {
			$result['message'] = "Image width is too large (less than 350px is recommended).  Use the cropping tool to get to the correct width.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] = "Image width is correct.";
		}
		if (imagesy($img) > 100) {
			$result['message'] .= "  Image height is too large (less than 100px is recommended).  Use the cropping tool to get to the correct height.";
			$result['button'] = "<br><button id='image_crop'>Crop Image</button>";
		} else {
			$result['message'] .= "  Image height is correct.";
		}
		echo json_encode($result);
	}
	
	public function postEditExtensions()
	{
		if (Input::get('mtm_alert_users')) {
			$mtm_alert_users = implode(",", Input::get('mtm_alert_users'));
		} else {
			$mtm_alert_users = '';
		}
		$data = array(
			'rcopia_extension' => Input::get('rcopia_extension'),
			'rcopia_apiVendor' => Input::get('rcopia_apiVendor'),
			'rcopia_apiPass' => Input::get('rcopia_apiPass'),
			'rcopia_apiPractice' => Input::get('rcopia_apiPractice'),
			'rcopia_apiSystem' => Input::get('rcopia_apiSystem'),
			'updox_extension' => Input::get('updox_extension'),
			'mtm_extension' => Input::get('mtm_extension'),
			'mtm_alert_users' => $mtm_alert_users,
			'snomed_extension' => Input::get('snomed_extension'),
			'vivacare' => Input::get('vivacare'),
			'peacehealth_id' => Input::get('peacehealth_id'),
			'birthday_extension' => Input::get('birthday_extension'),
			'birthday_message' => Input::get('birthday_message')
		);
		if (Input::get('rcopia_extension') == 'y') {
			$data['rcopia_update_notification_lastupdate'] = date("m/d/y H:i:s", time());
		}
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		$query = DB::table('practiceinfo')->get();
		if (count($query) > 1) {
			$data1 = array(
				'snomed_extension' => Input::get('snomed_extension')
			);
			DB::table('practiceinfo')->update($data1);
			$this->audit('Update');
		}
		echo 'Extensions Updated';
	}
	
	public function postGetProviders()
	{
		$query = DB::table('users')->where('group_id', '=', '2')->where('practice_id', '=', Session::get('practice_id'))->get();
		if ($query) {
			$data1['message'] = "OK";
			foreach ($query as $data) {
				$key = $data->id;
				$value = $data->displayname;
				$data1[$key] = $value;
			}
		} else {
			$data1['message'] = "No providers available.";
		}
		echo json_encode($data1);
	}
	
	public function postCheckExtension($extension)
	{
		if ($extension == 'snomed') {
			$result = "Extension was not installed correctly.  Run snomed_install.sh from /var/www/nosh/extensions/snomed again.";
			if (Schema::hasTable('curr_description_f')) {
				$query = DB::table('curr_description_f')->first();
				if ($query) {
					$result = "Extension status: OK!";
				}
			}
		}
		if ($extension == 'rcopia') {
			$practice_id = Session::get('practice_id');
			$row0 = Practiceinfo::find($practice_id);
			if ($row0->rcopia_update_notification_lastupdate == "") {
				$date0 = date('m/d/Y H:i:s', time());
			} else {
				$date0 = $row0->rcopia_update_notification_lastupdate;
			}
			$xml0 = "<Request><Command>update_notification</Command>";
			$xml0 .= "<LastUpdateDate>" . $date0 . "</LastUpdateDate>";
			$xml0 .= "</Request></RCExtRequest>";
			$result0 = $this->rcopia($xml0, $practice_id);
			if ($result0 == '') {
				$result = "Your rCopia settings are incorrect.  Check the extension log for details.  The extension will not be enabled until this is corrected!";
			} else {
				$result = "Extension status: OK!";
			}
		}
		echo $result;
	}
	
	public function postUsersList($type, $active)
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		if ($type == '2') {
			$query = DB::table('users')
				->join('providers', 'providers.id', '=', 'users.id')
				->where('users.group_id', '=', $type)
				->where('users.active', '=', $active)
				->where('users.practice_id', '=', Session::get('practice_id'));
		} elseif ($type == '100') {
			$query = DB::table('users')
				->leftJoin('demographics_relate', 'users.id', '=', 'demographics_relate.id')
				->select('users.*', 'demographics_relate.pid')
				->where('users.group_id', '=', $type)
				->where('users.active', '=', $active)
				->where('users.practice_id', '=', Session::get('practice_id'));
		} else {
			$query = DB::table('users')
				->where('group_id', '=', $type)
				->where('active', '=', $active)
				->where('practice_id', '=', Session::get('practice_id'));
		}
		$query_result = $query->get();
		if($query_result) { 
			$count = count($query_result);
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
	
	public function postEditUsers($group_id)
	{
		if(Input::get('title') != ''){
			$displayname = Input::get('firstname') . " " . Input::get('lastname') . ", " . Input::get('title');
		} else {
			$displayname = Input::get('firstname') . " " . Input::get('lastname');
		}
		$data1 = array(
			'username' => Input::get('username'),
			'firstname' => Input::get('firstname'),
			'middle' => Input::get('middle'),
			'lastname' => Input::get('lastname'),
			'title' => Input::get('title'),
			'displayname' => $displayname,
			'email' => Input::get('email'),
			'group_id' => $group_id,
			'active'=> '1',
			'practice_id' => Session::get('practice_id')
		);
		if ($group_id == '2') {
			$specialty = substr(Input::get('specialty'), 0, -13);
			$npi_taxonomy = substr(Input::get('specialty'), -11, 10);
			$data2 = array(
				'specialty' => $specialty,
				'license' => Input::get('license'),
				'license_state' => Input::get('license_state'),
				'npi' => Input::get('npi'),
				'npi_taxonomy' => $npi_taxonomy,
				'upin' => Input::get('upin'),
				'dea' => Input::get('dea'),
				'medicare' => Input::get('medicare'),
				'tax_id' => Input::get('tax_id'),
				'rcopia_username' => Input::get('rcopia_username'),
				'practice_id' => Session::get('practice_id')
			);
		}
		if ($group_id == '100') {
			$demographics_relate_row = DB::table('demographics_relate')->where('pid', '=', Input::get('pid'))->where('practice_id', '=', Session::get('practice_id'))->first();
		}
		$action = Input::get('oper');
		if ($action == 'edit') {
			DB::table('users')->where('id', '=', Input::get('id'))->update($data1);
			$this->audit('Update');
			if ($group_id == '2') {
				DB::table('providers')->where('id', '=', Input::get('id'))->update($data2);
				$this->audit('Update');
			}
			if ($group_id == '100') {
				$data3 = array(
					'id' => Input::get('id')
				);
				DB::table('demographics_relate')->where('demographics_relate_id', '=', $demographics_relate_row->demographics_relate_id)->update($data3);
				$this->audit('Update');
			}
		}
		if ($action == 'add') {
			$arr['id'] = DB::table('users')->insertGetId($data1);
			$this->audit('Add');
			if ($group_id == '2') {
				$data2['id'] = $arr['id'];
				DB::table('providers')->insert($data2);
				$this->audit('Add');
			}
			if ($group_id == '100') {
				$data3 = array(
					'id' => Input::get('id')
				);
				DB::table('demographics_relate')->where('demographics_relate_id', '=', $demographics_relate_row->demographics_relate_id)->update($data3);
				$this->audit('Update');
			}
			echo json_encode($arr);
		}
	}
	
	public function postEnable()
	{
		$data = array(
			'active' => '1',
			'password' => Hash::make(Input::get('password'))
		);
		DB::table('users')->where('id', '=', Input::get('id'))->update($data);
		$this->audit('Update');
	}
	
	public function postDisable()
	{
		$data = array(
			'active' => '0',
			'password' => Hash::make('disable')
		);
		DB::table('users')->where('id', '=', Input::get('id'))->update($data);
		$this->audit('Update');
		$row = DB::table('demographics_relate')->where('id', '=', Input::get('id'))->where('practice_id', '=', Session::get('practice_id'))->first();
		if ($row) {
			$data1 = array(
				'id' => NULL
			);
			DB::table('demographics_relate')->where('demographics_relate_id', '=', $row->demographics_relate_id)->update($data1);
			$this->audit('Update');
		}
	}
	
	public function postResetPassword()
	{
		$data['password'] = Hash::make(Input::get('password'));
		DB::table('users')->where('id', '=', Input::get('id'))->update($data);
		$this->audit('Update');
		echo "Password changed!";
	}
	
	public function postCheckAdmin()
	{
		$practice_id = Session::get('practice_id');
		if ($practice_id == '1') {
			$arr = "OK";
		} else {
			$row = Practiceinfo::find($practice_id);
			$query = DB::table('users')
				->join('providers', 'users.id', '=', 'providers.id')
				->where('users.group_id', '=', '2')
				->where('users.active', '=', '1')
				->where('users.practice_id', '=', $practice_id)
				->get();
			$count = count($query); 
			if ($row->provider_limit <= $count) {
				$arr = "No more providers can be added based on your provider limit for your practice account.  Please upgrade your subscription to enable additional providers!";
			} else {
				$arr = "OK";
			}
		}
		echo $arr;
	}
	
	public function postScheduleSetup1()
	{
		$data = array(
			'weekends' => Input::get('weekends'),
			'minTime' => Input::get('minTime'),
			'maxTime' => Input::get('maxTime'),
			'sun_o' => Input::get('sun_o'),
			'sun_c' => Input::get('sun_c'),
			'mon_o' => Input::get('mon_o'),
			'mon_c' => Input::get('mon_c'),
			'tue_o' => Input::get('tue_o'),
			'tue_c' => Input::get('tue_c'),
			'wed_o' => Input::get('wed_o'),
			'wed_c' => Input::get('wed_c'),
			'thu_o' => Input::get('thu_o'),
			'thu_c' => Input::get('thu_c'),
			'fri_o' => Input::get('fri_o'),
			'fri_c' => Input::get('fri_c'),
			'sat_o' => Input::get('sat_o'),
			'sat_c' => Input::get('sat_c'),
			'timezone' => Input::get('timezone')
		);
		DB::table('practiceinfo')->where('practice_id', '=', Session::get('practice_id'))->update($data);
		$this->audit('Update');
		echo 'Practice Schedule Updated';
	}
	
	public function postVisitTypeList()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('calendar')
			->where('active', '=', 'y')
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
		$query1 = DB::table('calendar')
			->where('active', '=', 'y')
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
	
	public function postEditVisitTypeList()
	{
		$data = array(
			'visit_type' => Input::get('visit_type'),
			'duration' => Input::get('duration'),
			'classname' => Input::get('classname'),
			'active' => 'y',
			'provider_id' => Input::get('provider_id'),
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
	
	public function postSetProvider()
	{
		if (Session::get('provider_id') != '') {
			Session::forget('provider_id');
		}
		Session::put('provider_id', Input::get('id'));
		echo 'Set';
	}
	
	public function postExceptionList()
	{
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$provider_id = Session::get('provider_id');
		$query = DB::table('repeat_schedule')
			->where('provider_id', '=', $provider_id)
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
		$query1 = DB::table('repeat_schedule')
			->where('provider_id', '=', $provider_id)
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
	
	public function postEditExceptionList()
	{
		$data = array(
			'repeat_day' => Input::get('repeat_day'),
			'repeat_start_time' => Input::get('repeat_start_time'),
			'repeat_end_time' => Input::get('repeat_end_time'),
			'title' => Input::get('title'),
			'reason' => Input::get('reason'),
			'repeat' => '604800',
			'until' => '0',
			'provider_id' => Session::get('provider_id')
		);
		$action = Input::get('oper');
		if ($action == 'edit') {
			DB::table('repeat_schedule')->where('repeat_id', '=', Input::get('id'))->update($data);
			$this->audit('Update');
		}
		if ($action == 'add') {
			DB::table('repeat_schedule')->insert($data);
			$this->audit('Add');
		}
		if ($action == 'del') {
			DB::table('repeat_schedule')->where('repeat_id', '=', Input::get('id'))->delete();
			$this->audit('Delete');
		}
	}
	
	public function postIcdUpdate($icd)
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		set_time_limit(0);
		ini_set('memory_limit','384M');
		$table = 'icd' . $icd;
		DB::table($table)->truncate();
		$year = date('Y');
		if ($icd == '9') {
			$baseurl = "http://www.icd9data.com/";
			$baseurl_start = $baseurl . $year . "/Volume1/default.htm";
			$html = new Htmldom($baseurl_start);
			$link1 = array();
			if (isset($html)) {
				if ($icd == '9') {
					$div = $html->find('div[class=definitionList]',0);
				} else {
					$div = $html->find('[class=innerWrapper]',0);
				}
				if (isset($div)) {
					foreach ($div->find('li') as $li) {
						$a = $li->find('a',0);
						$link1[] = $a->href;
					}
				}
			}
			File::put($tracknote,'Retreived first nodes.');
			$link2 = array();
			foreach ($link1 as $page1) {
				$cr1 = curl_init($baseurl . $page1);
				curl_setopt($cr1, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr1, CURLOPT_CONNECTTIMEOUT, 0);
				$data1 = curl_exec($cr1);
				curl_close($cr1);
				$dom1 = new Htmldom($data1);
				$div1 = $dom1->find('div[class=definitionList]',0);
				if (isset($div1)) {
					foreach ($div1->find('li') as $li1) {
						$a1 = $li1->find('a',0);
						$link2[] = $a1->href;
					}
				}
			}
			File::put($tracknote,'Retreived second nodes.');
			$link3 = array();
			$link4 = array();
			$i = 0;
			foreach ($link2 as $page2) {
				$cr2 = curl_init($baseurl . $page2);
				curl_setopt($cr2, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr2, CURLOPT_CONNECTTIMEOUT, 0);
				$data2 = curl_exec($cr2);
				curl_close($cr2);
				$dom2 = new Htmldom($data2);
				$div2 = $dom2->find('ul[class=definitionList]',0);
				if (isset($div2)) {
					foreach ($div2->find('li') as $li2) {
						$a2 = $li2->find('a',0);
						$link3[] = $a2->href;
					}
				}
				$linecheck2 = $dom2->find('[class=localLine]',0);
				if (isset($linecheck2)) {
					$main_description0 = $linecheck2->find('[class=threeDigitCodeListDescription]',0);
					$main_description0_text = $main_description0->innertext;
					foreach ($dom2->find('[class=localLine]') as $line2) {
						$greencheck2 = $line2->find('img[src*=bullet_triangle_green.png]',0);
						if (isset($greencheck2)) {
							$icd9_0 = $line2->find('[class=identifier]',0);
							$link4[$icd9_0->innertext] = $icd9_0->href;
							$i++;
							Session::put('icd_update_progress', $i);
						}
					}
				}
			}
			File::put($tracknote,'Retreived third nodes.');
			foreach ($link3 as $page3) {
				$cr3 = curl_init($baseurl . $page3);
				curl_setopt($cr3, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr3, CURLOPT_CONNECTTIMEOUT, 0);
				$data3 = curl_exec($cr3);
				curl_close($cr3);
				$dom3 = new Htmldom($data3);
				$linecheck3 = $dom3->find('[class=localLine]',0);
				if (isset($linecheck3)) {
					$main_description = $linecheck3->find('[class=threeDigitCodeListDescription]',0);
					$main_description_text = $main_description->innertext;
					foreach ($dom3->find('[class=localLine]') as $line3) {
						$greencheck3 = $line3->find('img[src*=bullet_triangle_green.png]',0);
						if (isset($greencheck3)) {
							$icd9 = $line3->find('[class=identifier]',0);
							$link4[$icd9->innertext] = $icd9->href;
							$i++;
							Session::put('icd_update_progress', $i);
						}
					}
				}
			}
			File::put($tracknote,'Retreived fourth nodes.  Inserting nodes into database...');
			$count = 0;
			$total = count($link4);
			foreach ($link4 as $icd_val => $page4) {
				$cr4 = curl_init($baseurl . $page4);
				curl_setopt($cr4, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($cr4, CURLOPT_CONNECTTIMEOUT, 0);
				$data4 = curl_exec($cr4);
				curl_close($cr4);
				$dom4 = new Htmldom($data4);
				$linecheck4 = $dom4->find('div[class=contentBlurb]',0);
				$line_desc = $dom4->find('div[class=description]',0);
				$data_desc['icd' . $icd] = $icd_val;
				$data_desc['icd' . $icd . '_description'] = $line_desc->innertext;
				DB::table($table)->insert($data_desc);
				$count++;
				if (isset($linecheck4)) {
					if (strpos($linecheck4->innertext, "Disease Synonyms") !== FALSE) {
						foreach ($linecheck4->find('li') as $line4) {
							$data_syn['icd' . $icd] = $icd_val;
							$data_syn['icd' . $icd . '_description'] = $line4->innertext;
							DB::table($table)->insert($data_syn);
							$count++;
						}
					}
				}
				File::put($track,$count);
			}
			File::put($trackcomplete,'true');
			echo 'ICD' . $icd . ' database updated with ' . $count . 'records!';
		}
	}
	
	public function postSetupProgress()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		$data['progress'] = File::get($track);
		$data['note'] = File::get($tracknote);
		$data['complete'] = File::get($trackcomplete);
		echo json_encode($data);
	}
	
	public function postSetupReset()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		File::put($track,'0');
		File::put($tracknote,'');
		File::put($trackcomplete,'false');
		echo 'OK';
	}
	
	public function postMedUpdate()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		$product = __DIR__.'/../../public/import/drugs/Product.txt';
		$product_link = __DIR__.'/../../public/import/drugs/meds_full.txt';
		$package = __DIR__.'/../../public/import/drugs/package.txt';
		$package_link = __DIR__.'/../../public/import/drugs/meds_full_package.txt';
		if (file_exists($product)) {
			unlink ($product);
		}
		if (file_exists($package)) {
			unlink ($package);
		}
		$html = new Htmldom('http://www.fda.gov/Drugs/InformationOnDrugs/ucm142438.htm#download');
		$e = $html->find('a[href*=zip]', 0);
		$link = $e->href;
		$wget = "wget http://www.fda.gov" . $link . " --directory-prefix='".__DIR__. "/../../public/import/'";
		$last_line = system($wget, $return_val);
		File::put($tracknote,'Downloaded .zip file from the FDA website.');
		$zip = strrchr($link, '/');
		$unzip = "unzip ".__DIR__."/../../import" . $zip . " -d ".__DIR__."/../../import/drugs/";
		exec($unzip);
		unlink(__DIR__.'/../../public/import/' . $zip);
		if (!file_exists($product_link)) {
			symlink($product, $product_link);
		}
		if (!file_exists($package_link)) {
			symlink($package, $package_link);
		}
		File::put($tracknote,'Unzipped file from the FDA website.');
		ini_set('memory_limit','196M');
		$product_command = "mysqlimport -u " . $_ENV['mysql_username']. " -p". $_ENV['mysql_password'] . " --columns=@x,PRODUCTNDC,PRODUCTTYPENAME,PROPRIETARYNAME,PROPRIETARYNAMESUFFIX,NONPROPRIETARYNAME,DOSAGEFORMNAME,ROUTENAME,STARTMARKETINGDATE,ENDMARKETINGDATE,MARKETINGCATEGORYNAME,APPLICATIONNUMBER,LABELERNAME,SUBSTANCENAME,ACTIVE_NUMERATOR_STRENGTH,ACTIVE_INGRED_UNIT,PHARM_CLASSES,DEASCHEDULE --local --delete nosh " . $product_link;
		$package_command = "mysqlimport -u " . $_ENV['mysql_username']. " -p". $_ENV['mysql_password'] . " --columns=@x,PRODUCTNDC,NDCPACKAGECODE,PACKAGEDESCRIPTION --local --delete nosh " . $package_link;
		shell_exec($product_command);
		File::put($tracknote,'Imported Product.txt file into NOSH database.');
		$i = DB::table('meds_full')->count();
		File::put($track,$i);
		shell_exec($package_command);
		File::put($tracknote,'Imported package.txt file into NOSH database.');
		$i += DB::table('meds_full_package')->count();
		File::put($track,$i);
		File::put($trackcomplete,'true');
		echo 'Medication database updated!';
	}
	
	public function postSupplementsUpdate()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		DB::table('supplements_list')->truncate();
		$html = new Htmldom("http://www.nlm.nih.gov/medlineplus/druginfo/herb_All.html");
		if (isset($html)) {
			File::put($tracknote,'Retrieved data from the NIH website.');
			$i = 0;
			foreach ($html->find('[class=herbul]') as $div) {
				foreach ($div->find('li') as $li) {
					$a = $li->find('a',0);
					$data['supplement_name'] = $a->innertext;
					$count = DB::table('supplements_list')->where('supplement_name', '=', $data['supplement_name'])->first();
					if (!$count) {
						DB::table('supplements_list')->insert($data);
						$i++;
					}
					
				}
				File::put($track,$i);
			}
		}
		File::put($trackcomplete,'true');
		echo 'Supplements database updated!';
	}
	
	public function postCvxUpdate()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		DB::table('cvx')->truncate();
		$xml = simplexml_load_file('http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx');
		$i = 0;
		File::put($tracknote,'Retrieved data from the CDC website.');
		foreach ($xml->CVXInfo as $cvx) {
			$data = array(
				'cvx_code' => (string) $cvx->Value[2],
				'description' => ucfirst((string) $cvx->Value[0]),
				'vaccine_name' => ucfirst((string) $cvx->Value[1])
			);
			DB::table('cvx')->insert($data);
			$i++;
			File::put($track,$i);
		}
		File::put($trackcomplete,'true');
		echo 'CVX immunization database updated!';
	}
	
	public function cpt_update()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		$directory = __DIR__.'/../../public/import';
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'text/plain' && $file->getClientOriginalName() != 'LONGULT.txt') {
					File::put($trackcomplete,'true');
					echo "This is not the correct CPT code file.  Try again.";
					exit (0);
				}
				$new_name = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.' . $file->getClientOriginalExtension();
				$file->move($directory, $file->getClientOriginalName());
				$file_path = $directory . "/" . $file->getClientOriginalName();
			}
		}
		File::put($tracknote,'Uploaded CPT file successfully.');
		Schema::rename('cpt', 'cpt_copy');
		Schema::create('cpt', function($table) {
			$table->increments('cpt_id');
			$table->string('cpt', 255)->nullable();
			$table->longtext('cpt_description')->nullable();
			$table->string('cpt_charge', 255)->nullable();
			$table->boolean('cpt_common')->nullable();
		});
		File::put($tracknote,'Created copy of existing CPT table.');
		$file_array = file($file_path);
		$i = 0;
		foreach ($file_array as $key => $value) {
			if ($key > 32) {
				$pos = strpos($value, " ");
				$cpt_description = substr($value, $pos);
				$cpt = substr($value, 0, $pos);
				$data = array (
					'cpt' => $cpt,
					'cpt_description' => $cpt_description
				);
				DB::table('cpt')->insert($data);
				$i++;
				File::put($track,$i);
			}
		}
		File::put($tracknote,'Reconciling previous CPT code entries into new database.');
		$cpt_arr = DB::table('cpt_copy')->whereNotNull('cpt_charge')->get();
		$j = 0;
		foreach ($cpt_arr as $cpt_row) {
			$cpt_arr1 = DB::table('cpt')->where('cpt', '=', $cpt_row->cpt)->first();
			if ($cpt_arr1) {
				$cpt_arr2 = array (
					'cpt_charge' => $cpt_row->cpt_charge
				);
				DB::table('cpt')->where('cpt_id', '=', $cpt_arr1->cpt_id)->update($cpt_arr2);
			} else {
				DB::table('cpt')->insert($cpt_row);
			}
			$j++;
		}
		File::put($tracknote,'Reconcilied ' . $j . ' records.');
		Schema::drop('cpt_copy');
		File::put($trackcomplete,'true');
		echo 'CPT database updated!';
	}
	
	public function postNpiUpdate()
	{
		$track = __DIR__.'/../../public/temp/track';
		$tracknote = __DIR__.'/../../public/temp/tracknote';
		$trackcomplete = __DIR__.'/../../public/temp/trackcomplete';
		ini_set('memory_limit','96M');
		$html = new Htmldom("http://www.nucc.org/index.php?option=com_content&view=article&id=107&Itemid=132");
		if (isset($html)) {
			$e = $html->find('a[href*=csv]', 0);
			$link = $e->href;
			$wget = "wget http://www.nucc.org/" . $link . " --directory-prefix='".__DIR__."/../../import/' --output-document='npi_taxonomy.csv'";
			$last_line = system($wget, $return_val);
			File::put($tracknote,'Retrieved data from the NUCC website.');
			DB::table('npi')->truncate();
			$i = 0;
			if (($npi_handle = fopen(__DIR__.'/../../import/npi_taxonomy.csv', "r")) !== FALSE) {
				while (($npi1 = fgetcsv($npi_handle, 0, ",", '"')) !== FALSE) {
					if ($npi1[0] != '' && $npi1[0] != 'Code') {
						$npi_data = array (
							'code' => $npi1[0],
							'type' => $npi1[1],
							'classification' => $npi1[2],
							'specialization' => $npi1[3]
						);
						DB::table('npi')->insert($npi_data);
						$i++;
					}
					File::put($track,$i);
				}
				fclose($npi_handle);
			}
			File::put($trackcomplete,'true');
			echo 'NPI database updated!';
		} else {
			File::put($trackcomplete,'true');
			echo 'Unable to contact NUCC website.  Please try again later.';
		}
	}
	
	public function postAudit()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('audit')
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
		$query1 = DB::table('audit')
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
	
	public function postExtensions()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('extensions_log')
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
		$query1 = DB::table('extensions_log')
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
	
	public function spare()
	{
		$role_csv = __DIR__."/../../import/familyrole.csv";
		if (($role_handle = fopen($role_csv, "r")) !== FALSE) {
			while (($role1 = fgetcsv($role_handle, 0, ",")) !== FALSE) {
				if ($role1[0] != '') {
					$role_description = ucfirst($role1[1]);
					$role_data = array (
						'code' => $role1[0],
						'description' => $role_description
					);
					DB::table('guardian_roles')->insert($role_data);
				}
			}
			fclose($role_handle);
		}
		$lang_csv = __DIR__."/../../import/lang.csv";
		if (($lang_handle = fopen($lang_csv, "r")) !== FALSE) {
			while (($lang1 = fgetcsv($lang_handle, 0, "\t")) !== FALSE) {
				if ($lang1[0] != '') {
					$lang_data = array (
						'code' => $lang1[0],
						'description' => $lang1[6]
					);
					DB::table('lang')->insert($lang_data);
				}
			}
			fclose($lang_handle);
		}
		$npi_csv = __DIR__."/../../import/npi_taxonomy.csv";
		if (($npi_handle = fopen($npi_csv, "r")) !== FALSE) {
			while (($npi1 = fgetcsv($npi_handle, 0, ",", '"')) !== FALSE) {
				if ($npi1[0] != '' || $npi1[0] != 'Code') {
					$npi_data = array (
						'code' => $npi1[0],
						'type' => $npi1[1],
						'classification' => $npi1[2],
						'specialization' => $npi1[3]
					);
					DB::table('npi')->insert($npi_data);
				}
			}
			fclose($npi_handle);
		}
		$pos_csv = __DIR__."/../../import/pos.csv";
		if (($pos_handle = fopen($pos_csv, "r")) !== FALSE) {
			while (($pos1 = fgetcsv($pos_handle, 0, ",")) !== FALSE) {
				if ($pos1[0] != '') {
					$pos_data = array (
						'pos_id' => $pos1[0],
						'pos_description' => $pos1[1]
					);
					DB::table('pos')->insert($pos_data);
				}
			}
			fclose($pos_handle);
		}
	}
	
	public function postUpdateIcd10()
	{
		DB::table('icd10')->truncate();
		$wget = "wget http://www.cms.gov/Medicare/Coding/ICD10/Downloads/2014-ICD10-Code-Tables-and-Index.zip --directory-prefix='".__DIR__. "/../../public/import/'";
		$last_line = system($wget, $return_val);
		$zip = '2014-ICD10-Code-Tables-and-Index.zip';
		$unzip = "unzip ".__DIR__."/../../import" . $zip . " Tabular.xml -d ".__DIR__."/../../import/";
		exec($unzip);
		$xml = simplexml_load_file(__DIR__.'/../../import/Tabular.xml');
		$i = 0;
		$result = $xml->xpath('//diag');
		foreach ($result as $row) {
			if (!$row->diag) {
				$data = array(
					'icd10' => $row->name,
					'icd10_description' => $row->desc
				);
				DB::table('icd10')->insert($data);
				$i++;
			}
		}
		unlink(__DIR__.'/../../import/Tabular.xml');
		return "Records added: " . $i;
	}
}
