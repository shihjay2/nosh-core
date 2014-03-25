<?php

class InstallController extends BaseController {

	/**
	* NOSH ChartingSystem Installation
	*/
	
	protected $layout = 'layouts.layout1';
	
	public function view()
	{
		$this->layout->title = "NOSH ChartingSystem Installation";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/install.js');
		$this->layout->content = View::make('install');
	}
	
	public function install_fix()
	{
		$this->layout->title = "NOSH ChartingSystem Database Connection Fixer";
		$this->layout->style = '';
		$this->layout->script = HTML::script('/js/installfix.js');
		$this->layout->content = View::make('install_fix_db_conn');
	}
	
	public function codeigniter_migrate()
	{
		$codeigniter = __DIR__."/../../.codeigniter.php";
		if (file_exists($codeigniter)) {
			include($codeigniter);
			$db_name = 'nosh';
			$db_username = $db['default']['username'];
			$db_password = $db['default']['password'];
			$connect = mysqli_connect('localhost', $db_username, $db_password);
			if ($connect) {
				$database_filename = __DIR__."/../../.env.php";
				$database_config['mysql_database'] = $db_name;
				$database_config['mysql_username'] = $db_username;
				$database_config['mysql_password'] = $db_password;
				file_put_contents($database_filename, '<?php return ' . var_export($database_config, true) . ";\n");
				mysqli_close($connect);
			} else {
				echo "Incorrect username/password for your MySQL database.  Try again.";
				exit (0);
			}
		}
		return Redirect::to('/');
	}
	
	public function update()
	{
		if (!Schema::hasTable('migrations')) {
			Artisan::call('migrate:install');
		}
		Artisan::call('migrate');
		$practice = Practiceinfo::find(1);
		if ($practice->version < "1.8.0") {
			$this->update180();
		}
		return Redirect::to('/');
	}
	
	public function update180()
	{
		$orderslist1_array = array();
		$orderslist1_array[] = array(
			'orders_code' => '11550',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '12500',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '24080',
			'aoe_code' => 'MIC1^SOURCE:',
			'aoe_field' => 'aoe_source_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '30000',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '30740',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '30820',
			'aoe_code' => 'GLUFAST^HOURS FASTING:',
			'aoe_field' => 'aoe_fasting_hours_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '31300',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '33320',
			'aoe_code' => 'TDM1^LAST DOSE DATE:;TDM2^LAST DOSE TIME:',
			'aoe_field' => 'aoe_dose_date_code;aoe_dose_time_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '43540',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '43542',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '43546',
			'aoe_code' => 'CHM1^FASTING STATE:',
			'aoe_field' => 'aoe_fasting_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '60109',
			'aoe_code' => 'BFL1^SOURCE:',
			'aoe_field' => 'aoe_source1_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '61500',
			'aoe_code' => 'MIC1^SOURCE:;MIC2^ADD. INFORMATION:',
			'aoe_field' => 'aoe_source_code;aoe_additional_code'
		);
		$orderslist1_array[] = array(
			'orders_code' => '68329',
			'aoe_code' => 'MIC1^SOURCE:',
			'aoe_field' => 'aoe_source_code'
		);
		foreach ($orderslist1_array as $row1) {
			$order_query = DB::table('orderslist1')->where('orders_code', '=', $row1['orders_code'])->get();
			foreach ($orders_query as $row2) {
				$orders_data = array(
					'aoe_code' => $row1['aoe_code'],
					'aoe_field' => $row1['aoe_field']
				);
				DB::table('orderslist1')->where('orderslist1_id', '=', $row2->orderslist1_id)->update($orders_data);
			}
		}
		// Update referral templates
		$template_query = DB::table('templates')->where('category', '=', 'referral')->first();
		if (!$template_query) {
			$template_array = array();
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Referral - Please provide primary physician with summaries of subsequent visits.","id":"ref_referral_hidden"},{"type":"checkbox","id":"ref_referral_1","class":"ref_other ref_intro","value":"Assume management for this particular problem and return patient after conclusion of care.","name":"ref_referral_1","caption":"Return patient after managing particular problem"},{"type":"br"},{"type":"checkbox","id":"ref_referral_2","class":"ref_other ref_intro","value":"Assume future management of patient within your area of expertise.","name":"ref_referral_2","caption":"Future ongoing management"},{"type":"br"},{"type":"checkbox","id":"ref_referral_3","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_referral_3","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_referral_4","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_referral_4","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_referral_5","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_referral_5","caption":"Receive thorough written report"}]}',
				'group' => 'referral',
				'sex' => 'm'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Referral - Please provide primary physician with summaries of subsequent visits.","id":"ref_referral_hidden"},{"type":"checkbox","id":"ref_referral_1","class":"ref_other ref_intro","value":"Assume management for this particular problem and return patient after conclusion of care.","name":"ref_referral_1","caption":"Return patient after managing particular problem"},{"type":"br"},{"type":"checkbox","id":"ref_referral_2","class":"ref_other ref_intro","value":"Assume future management of patient within your area of expertise.","name":"ref_referral_2","caption":"Future ongoing management"},{"type":"br"},{"type":"checkbox","id":"ref_referral_3","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_referral_3","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_referral_4","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_referral_4","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_referral_5","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_referral_5","caption":"Receive thorough written report"}]}',
				'group' => 'referral',
				'sex' => 'f'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Consultation - Please send the patient back for follow-up and treatment.","id":"ref_consultation_hidden"},{"type":"checkbox","id":"ref_consultation_1","class":"ref_other ref_intro","value":"Confirm the diagnosis.","name":"ref_consultation_1","caption":"Confirm the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_2","class":"ref_other ref_intro","value":"Advise as to the diagnosis.","name":"ref_consultation_2","caption":"Advise as to the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_3","class":"ref_other ref_intro","value":"Suggest medication or treatment for the diagnosis.","name":"ref_consultation_3","caption":"Suggest medication or treatment"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_4","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_consultation_4","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_5","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_consultation_5","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_6","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_consultation_6","caption":"Receive thorough written report"}]}',
				'group' => 'consultation',
				'sex' => 'm'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Consultation - Please send the patient back for follow-up and treatment.","id":"ref_consultation_hidden"},{"type":"checkbox","id":"ref_consultation_1","class":"ref_other ref_intro","value":"Confirm the diagnosis.","name":"ref_consultation_1","caption":"Confirm the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_2","class":"ref_other ref_intro","value":"Advise as to the diagnosis.","name":"ref_consultation_2","caption":"Advise as to the diagnosis"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_3","class":"ref_other ref_intro","value":"Suggest medication or treatment for the diagnosis.","name":"ref_consultation_3","caption":"Suggest medication or treatment"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_4","class":"ref_other ref_after","value":"Please call me when you have seen the patient.","name":"ref_consultation_4","caption":"Call back"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_5","class":"ref_other ref_after","value":"I would like to receive periodic status reports on this patient.","name":"ref_consultation_5","caption":"Receive periodic status reports"},{"type":"br"},{"type":"checkbox","id":"ref_consultation_6","class":"ref_other ref_after","value":"Please send a thorough written report when the consultation is complete.","name":"ref_consultation_6","caption":"Receive thorough written report"}]}',
				'group' => 'consultation',
				'sex' => 'f'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Physical therapy referral details:","id":"ref_pt_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_pt_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_pt_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_pt_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_pt_1b","class":"ref_other ref_intro","value":"Increase strength.","name":"ref_pt_1","caption":"Increase strength"},{"type":"checkbox","id":"ref_pt_1c","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_pt_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_pt_2_div","html":[{"type":"span","html":"Modalities:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_pt_2","class":"ref_select ref_intro","css":{"width":"200px"},"name":"ref_pt_2","caption":"","options":{"Hot or cold packs. ":"Hot or cold packs.","TENS unit. ":"TENS unit.","Back program. ":"Back program.","Joint mobilization. ":"Joint mobilization.","Home program. ":"Home program.","Pool therapy. ":"Pool therapy.","Feldenkrais method. ":"Feldenkrais method.","Therapeutic exercise. ":"Therapeutic exercise.","Myofascial release. ":"Myofascial release.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"text","id":"ref_pt_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_3","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_pt_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_4","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_pt_5","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_5","placeholder":"Duration"}]}',
				'group' => 'pt',
				'sex' => 'm'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Physical therapy referral details:","id":"ref_pt_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_pt_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_pt_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_pt_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_pt_1b","class":"ref_other ref_intro","value":"Increase strength.","name":"ref_pt_1","caption":"Increase strength"},{"type":"checkbox","id":"ref_pt_1c","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_pt_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_pt_2_div","html":[{"type":"span","html":"Modalities:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_pt_2","class":"ref_select ref_intro","css":{"width":"200px"},"name":"ref_pt_2","caption":"","options":{"Hot or cold packs. ":"Hot or cold packs.","TENS unit. ":"TENS unit.","Back program. ":"Back program.","Joint mobilization. ":"Joint mobilization.","Home program. ":"Home program.","Pool therapy. ":"Pool therapy.","Feldenkrais method. ":"Feldenkrais method.","Therapeutic exercise. ":"Therapeutic exercise.","Myofascial release. ":"Myofascial release.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"text","id":"ref_pt_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_3","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_pt_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_4","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_pt_5","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_pt_5","placeholder":"Duration"}]}',
				'group' => 'pt',
				'sex' => 'f'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Massage therapy referral details:","id":"ref_massage_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_massage_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_massage_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_massage_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_massage_1b","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_massage_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"text","id":"ref_massage_2","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_2","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_massage_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_3","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_massage_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_4","placeholder":"Duration"}]}',
				'group' => 'massage',
				'sex' => 'm'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Massage therapy referral details:","id":"ref_massage_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_massage_1_div","html":[{"type":"span","html":"Objectives:"},{"type":"br"},{"type":"checkbox","id":"ref_massage_1a","class":"ref_other ref_intro","value":"Decrease pain.","name":"ref_massage_1","caption":"Decrease pain"},{"type":"checkbox","id":"ref_massage_1b","class":"ref_other ref_intro","value":"Increase mobility.","name":"ref_massage_1","caption":"Increase mobility"}]},{"type":"br"},{"type":"text","id":"ref_massage_2","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_2","placeholder":"Precautions"},{"type":"br"},{"type":"text","id":"ref_massage_3","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_3","placeholder":"Frequency"},{"type":"br"},{"type":"text","id":"ref_massage_4","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_massage_4","placeholder":"Duration"}]}',
				'group' => 'massage',
				'sex' => 'f'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Sleep study referral details:","id":"ref_sleep_study_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_1_div","html":[{"type":"span","html":"Type:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_sleep_study_1","class":"ref_select ref_other ref_intro","css":{"width":"200px"},"name":"ref_sleep_study_1","caption":"","options":{"Diagnostic Sleep Study Only.\n":"Diagnostic Sleep Study Only.","Diagnostic testing with Continuous Positive Airway Pressure.\n":"Diagnostic testing with Continuous Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with Oxygen.\n":"Diagnostic testing with Oxygen.","Diagnostic testing with Oral Device.\n":"Diagnostic testing with Oral Device.","MSLT (Multiple Sleep Latency Test).\n":"MSLT (Multiple Sleep Latency Test).","MWT (Maintenance of Wakefulness Test).\n":"MWT (Maintenance of Wakefulness Test).","Titrate BiPAP settings.\n":"Titrate BiPAP settings.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_2_div","html":[{"type":"span","html":"BiPAP pressures:"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2a","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2a","placeholder":"Inspiratory Pressure (IPAP), cm H20"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2b","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2b","placeholder":"Expiratory Pressure (EPAP), cm H20"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_3_div","html":[{"type":"span","html":"BiPAP Mode:"},{"type":"br"},{"type":"checkbox","id":"ref_sleep_study_3a","class":"ref_other ref_intro","value":"Spontaneous mode.","name":"ref_sleep_study_3","caption":"Spontaneous"},{"type":"checkbox","id":"ref_sleep_study_3b","class":"ref_other ref_intro","value":"Spontaneous/Timed mode","name":"ref_sleep_study_3","caption":"Spontaneous/Timed"},{"type":"br"},{"type":"text","id":"ref_sleep_study_3c","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_3","placeholder":"Breaths per minute"}]}]}',
				'group' => 'sleep_study',
				'sex' => 'm'
			);
			$template_array[] = array(
				'category' => 'referral',
				'json' => '{"html":[{"type":"hidden","class":"ref_hidden","value":"Sleep study referral details:","id":"ref_sleep_study_hidden"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_1_div","html":[{"type":"span","html":"Type:"},{"type":"br"},{"type":"select","multiple":"multiple","id":"ref_sleep_study_1","class":"ref_select ref_other ref_intro","css":{"width":"200px"},"name":"ref_sleep_study_1","caption":"","options":{"Diagnostic Sleep Study Only.\n":"Diagnostic Sleep Study Only.","Diagnostic testing with Continuous Positive Airway Pressure.\n":"Diagnostic testing with Continuous Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with BiLevel Positive Airway Pressure.\n":"Diagnostic testing with BiLevel Positive Airway Pressure.","Diagnostic testing with Oxygen.\n":"Diagnostic testing with Oxygen.","Diagnostic testing with Oral Device.\n":"Diagnostic testing with Oral Device.","MSLT (Multiple Sleep Latency Test).\n":"MSLT (Multiple Sleep Latency Test).","MWT (Maintenance of Wakefulness Test).\n":"MWT (Maintenance of Wakefulness Test).","Titrate BiPAP settings.\n":"Titrate BiPAP settings.","Patient education. ":"Patient education.","Work hardening. ":"Work hardening."}}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_2_div","html":[{"type":"span","html":"BiPAP pressures:"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2a","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2a","placeholder":"Inspiratory Pressure (IPAP), cm H20"},{"type":"br"},{"type":"text","id":"ref_sleep_study_2b","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_2b","placeholder":"Expiratory Pressure (EPAP), cm H20"}]},{"type":"br"},{"type":"div","class":"ref_buttonset","id":"ref_sleep_study_3_div","html":[{"type":"span","html":"BiPAP Mode:"},{"type":"br"},{"type":"checkbox","id":"ref_sleep_study_3a","class":"ref_other ref_intro","value":"Spontaneous mode.","name":"ref_sleep_study_3","caption":"Spontaneous"},{"type":"checkbox","id":"ref_sleep_study_3b","class":"ref_other ref_intro","value":"Spontaneous/Timed mode","name":"ref_sleep_study_3","caption":"Spontaneous/Timed"},{"type":"br"},{"type":"text","id":"ref_sleep_study_3c","css":{"width":"200px"},"class":"ref_other ref_detail_text ref_intro","name":"ref_sleep_study_3","placeholder":"Breaths per minute"}]}]}',
				'group' => 'sleep_study',
				'sex' => 'f'
			);
			foreach ($template_array as $template_ind) {
				$template_array = serialize(json_decode($template_ind['json']));
				$template_data = array(
					'user_id' => '0',
					'template_name' => 'Global Default',
					'default' => 'default',
					'category' => $template_ind['category'],
					'sex' => $template_ind['sex'],
					'group' => $template_ind['group'],
					'array' => $template_array
				);
				DB::table('templates')->insert($template_data);
			}
		}
		// Update image links and create scans and received faxes directories if needed
		$practices = Practiceinfo::all();
		foreach ($practices as $practice) {
			$practice->practice_logo = str_replace("/var/www/nosh/","", $practice->practice_logo);
			$practice->save();
			$scans_dir = $practice->documents_dir . 'scans/' . $practice->practice_id;
			if (! file_exists($scans_dir)) {
				mkdir($scans_dir, 0777);
			}
			$received_dir = $practice->documents_dir . 'received/' . $practice->practice_id;
			if (! file_exists($received_dir)) {
				mkdir($received_dir, 0777);
			}
		}
		$providers = Providers::all();
		foreach ($providers as $provider) {
			$provider->signature = str_replace("/var/www/nosh/","", $provider->signature);
			$provider->save();
		}
		// Assign standard encounter templates
		DB::table('encounters')->update(array('encounter_template' => 'standardmedical'));
		// Move scans and received faxes
		$scans = DB::table('scans')->get();
		if ($scans) {
			foreach ($scans as $scan) {
				$practice1 = Practiceinfo::find($scan->practice_id);
				$new_scans_dir = $practice1->documents_dir . 'scans/' . $scan->practice_id;
				$scans_data['filePath'] = str_replace('/var/www/nosh/scans', $new_scans_dir, $scan->filePath);
				rename($scan->filePath, $scans_data['filePath']);
				DB::table('scans')->where('scans_id', '=', $scan->scans_id)->update($scans_data);
			}
		}
		$received = DB::table('received')->get();
		if ($received) {
			foreach ($received as $fax) {
				$practice2 = Practiceinfo::find($fax->practice_id);
				$new_received_dir = $practice2->documents_dir . 'received/' . $fax->practice_id;
				$received_data['filePath'] = str_replace('/var/www/nosh/received', $new_received_dir, $fax->filePath);
				rename($fax->filePath, $received_data['filePath']);
				DB::table('received')->where('received_id', '=', $fax->received_id)->update($received_data);
			}
		}
		// Migrate bill_complex field to encounters
		$encounters = DB::table('encounters')->get();
		if ($encounters) {
			foreach ($encounters as $encounter) {
				$billing = DB::table('billing')
					->where('eid', '=', $encounter->eid)
					->where(function($query_array1){
						$query_array1->where('bill_complex', '!=', "")
						->orWhereNotNull('bill_complex');
					})
					->first();
				$data['bill_complex'] = '';
				if ($billing) {
					$data['bill_complex'] = $billing->bill_complex;
				}
				DB::table('encounters')->where('eid', '=', $encounter->eid)->update($data);
			}
		}
		// Update version
		DB::table('practiceinfo')->update(array('version' => '1.8.0'));
	}
	
	public function test2()
	{
		$this->layout->title = "NOSH ChartingSystem Database Connection Fixer";
		$this->layout->style = '';
		$this->layout->script = '';
		$this->layout->content = View::make('encounters.pe');
	}
}
