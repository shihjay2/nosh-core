<?php

class AjaxMessagingController extends BaseController {

	/**
	* NOSH ChartingSystem Messaging Ajax Functions
	*/
	
	public function postInternalInbox()
	{
		$id = Session::get('user_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('messaging')
			->where('mailbox', '=', $id)
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
		$query1 = DB::table('messaging')
			->where('mailbox', '=', $id)
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
				$row1 = User::find($row->message_from);
				$response['rows'][$i]['id']=$row->message_id; 
				$response['rows'][$i]['cell']=array($row->message_id,$row->message_to,$row->read,$row->date,$row->message_from,$row1->displayname . ' (' . $row->message_from . ')',$row->subject,$row->body,$row->cc,$row->pid,$row->patient_name,nl2br($row->body),$row->t_messages_id,$row->documents_id);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postInternalDraft()
	{
		$id = Session::get('user_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('messaging')
			->where('message_from', '=', $id)
			->where('mailbox', '=', '0')
			->where('status', '=', 'Draft')
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
		$query1 = DB::table('messaging')
			->where('message_from', '=', $id)
			->where('mailbox', '=', '0')
			->where('status', '=', 'Draft')
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
				$response['rows'][$i]['id']=$row->message_id; 
				$response['rows'][$i]['cell']=array($row->message_id,$row->date,$row->message_to,$row->cc,$row->subject,$row->body,$row->pid,$row->patient_name);
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postInternalOutbox()
	{
		$id = Session::get('user_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		$query = DB::table('messaging')
			->where('message_from', '=', $id)
			->where('mailbox', '=', '0')
			->where('status', '=', 'Sent')
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
		$query1 = DB::table('messaging')
			->where('message_from', '=', $id)
			->where('mailbox', '=', '0')
			->where('status', '=', 'Sent')
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
				$response['rows'][$i]['id']=$row->message_id; 
				$response['rows'][$i]['cell']=array($row->message_id,$row->date,$row->message_to,$row->cc,$row->subject,$row->pid,nl2br($row->body));
				$i++; 
			}
		} else {
			$response['rows'] = '';
		}
		echo json_encode($response);
	}
	
	public function postReadMessage($id, $documents_id="0")
	{
		$data = array(
			'read' => 'y'
		);
		DB::table('messaging')->where('message_id', '=', $id)->update($data);
		$this->audit('Update');
		$arr = "Message read.";
		if ($documents_id != "0") {
			$data1 = array(
				'documents_viewed' => Session::get('displayname')
			);
			DB::table('documents')->where('documents_id', '=', $documents_id)->update($data1);
			$this->audit('Update');
			$arr .= "  Test(s) marked as reviewed.";
		}
		echo $arr;
	}
	
	public function postSendMessage()
	{
		$message_id = Input::get('message_id');
		$from = Session::get('user_id');
		$t_messages_id = Input::get('t_messages_id');
		if (Input::get('patient_name') == '') {
			$subject = Input::get('subject');
		} else {
			$subject = Input::get('subject') . ' [RE: ' . Input::get('patient_name') . ']'; 
		}
		$mailbox = array();
		$messages_to = "";
		$i = 0;
		foreach (Input::get('message_to') as $key => $to_row) {
			$to_pos = strpos($to_row, "(");
			$to_pos = $to_pos + 1;
			$to_id = substr($to_row, $to_pos);
			$mailbox[] = str_replace(")", "", $to_id);
			if ($i > 0) {
				$messages_to .= ";" . $to_row;
			} else {
				$messages_to .= $to_row;
			}
			$i++;
		}
		$messages_cc = "";
		if (Input::get('cc') != '') {
			$j = 0;
			foreach (Input::get('cc') as $key1 => $cc_row) {
				$cc_pos = strpos($cc_row, "(");
				$cc_pos = $cc_pos + 1;
				$cc_id = substr($cc_row, $cc_pos);
				$mailbox[] = str_replace(")", "", $cc_id);
				if ($j > 0) {
					$messages_cc .= ";" . $cc_row;
				} else {
					$messages_cc .= $cc_row;
				}
				$j++;
			}
		}
		foreach ($mailbox as $mailbox_row) {
			if ($mailbox_row != '') {
				$data = array(
					'pid' => Input::get('pid'),
					'patient_name' => Input::get('patient_name'),
					'message_to' => $messages_to,
					'cc' => $messages_cc,
					'message_from' => $from,
					'subject' => $subject,
					'body' => Input::get('body'),
					't_messages_id' => $t_messages_id,
					'status' => 'Sent',
					'mailbox' => $mailbox_row,
					'practice_id' => Session::get('practice_id')
				);
				DB::table('messaging')->insert($data);
				$this->audit('Add');
				$user_row = User::find($mailbox_row);
				if ($user_row->group_id === '100') {
					$practice = Practiceinfo::find(Session::get('practice_id'));
					$data_message['patient_portal'] = $practice->patient_portal;
					$this->send_mail('emails.newmessage', $data_message, 'New Message in your Patient Portal', $user_row->email, Session::get('practice_id'));
				}
			}
		}
		$data1a = array(
			'pid' => Input::get('pid'),
			'patient_name' => Input::get('patient_name'),
			'message_to' => $messages_to,
			'cc' => $messages_cc,
			'message_from' => $from,
			'subject' => $subject,
			'body' => Input::get('body'),
			'status' => 'Sent',
			'mailbox' => '0',
			'practice_id' => Session::get('practice_id')
		);
		if ($message_id != '') {
			DB::table('messaging')->where('message_id', '=', $message_id)->update($data1a);
			$this->audit('Update');
		} else {
			$message_id = DB::table('messaging')->insertGetId($data1a);
			$this->audit('Add');
		}
		if ($t_messages_id != '' || $t_messages_id != '0') {
			$row = User::find($from);
			$displayname = $row->displayname . ' (' . $row->id . ')';
			$row1 = Messaging::find($message_id);
			$date = explode(" ", $row1->date);
			$message1 = Input::get('body');
			$message = 'On ' . $row1->date . ', ' . $displayname . ' wrote:' . "\n---------------------------------\n" . $message1;
			$data1 = array(
				't_messages_message' => $message,
				't_messages_to' => ''
			);
			DB::table('t_messages')->where('t_messages_id', '=', $t_messages_id)->update($data1);
			$this->audit('Update');
		}
		echo 'Message sent!';
	}
	
	public function postDraftMessage()
	{
		$message_id = Input::get('message_id');
		$data = array(
			'pid' => Input::get('pid'),
			'patient_name' => Input::get('patient_name'),
			'message_to' => Input::get('message_to'),
			'cc' => Input::get('cc'),
			'message_from' => Session::get('user_id'),
			'subject' => Input::get('subject'),
			'body' => Input::get('body'),
			'status' => 'Draft',
			'mailbox' => '0',
			'practice_id' => Session::get('practice_id')
		);
		if ($message_id != '') {
			DB::table('messaging')->where('message_id', '=', $message_id)->update($data);
			$this->audit('Update');
		} else {
			DB::table('messaging')->insert($data);
			$this->audit('Add');
		}
		echo 'Message saved!';
	}
	
	public function postDeleteMessage()
	{
		DB::table('messaging')->where('message_id', '=', Input::get('message_id'))->delete();
		$this->audit('Delete');
		echo 'Message deleted!';
	}
	
	public function postGetDisplayname()
	{
		$row = User::find(Input::get('id'));
		$records = $row->displayname . ' (' . $row->id . ')';
		echo $records;
	}
	
	public function postGetDisplayname1()
	{
		$to = explode(';', Input::get('id'));
		$records = '';
		foreach ($to as $id) {
			$row = User::find($id);
			if ($records == '') {
				$records = $row->displayname . ' (' . $row->id . ')';
			} else {
				$records .= ';' . $row->displayname . ' (' . $row->id . ')';
			}
		}
		echo $records;
	}
	
	public function postExportMessage()
	{
		$message = 'Internal messaging with patient on: ' . Input::get('t_messages_date') . "\n\r" . Input::get('t_messages_message');
		$message = str_replace("<br>", "", $message);
		$data = array(
			't_messages_subject' => 'Internal messaging with patient: ' . Input::get('t_messages_subject'),
			't_messages_message' => $message,
			't_messages_dos' => date('Y-m-d H:i:s', time()),
			't_messages_provider' => Session::get('displayname'),
			't_messages_signed' => 'No',
			't_messages_from' => Session::get('displayname') . ' (' . Session::get('user_id') . ')',
			'pid' => Input::get('pid'),
			'practice_id' => Session::get('practice_id')
		);
		DB::table('t_messages')->insert($data);
		$this->audit('Add');
		echo "Message exported to the chart as a patient Message.";
	}
	
	public function postReceiveFax()
	{
		if (Session::get('group_id') == '100') {
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
			$query = DB::table('received')
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
			$query1 = DB::table('received')
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
	}
	
	public function postViewFax($id)
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Received::find($id);
			$name = time() . '_fax.pdf';
			$data['filepath'] = __DIR__.'/../../public/temp/' . $name;
			copy($result->filePath, $data['filepath']);
			$data['html'] = '<iframe src="' . asset('temp/' . $name) . '" width="770" height="425" style="border: none;"></iframe>';
			echo json_encode($data);
		}
	}
	
	public function postViewPage($id)
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$result = Pages::find($id);
			$name = time() . '_faxpage.pdf';
			$data['filepath'] = __DIR__.'/../../public/temp/' . $name;
			copy($result->file, $data['filepath']);
			$data['html'] = '<iframe src="' . asset('temp/' . $name) . '" width="770" height="425" style="border: none;"></iframe>';
			echo json_encode($data);
		}
	}
	
	public function postCloseFax()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			unlink(Input::get('fax_filepath'));
			echo 'OK';
		}
	}
	
	public function postDeletefax()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			DB::table('received')->where('fileName', '=', Input::get('fileName'))->delete();
			$this->audit('Delete');
			unlink(Input::get('filePath'));
			echo 'Fax Deleted';
		}
	}
	
	public function postNewFax()
	{
		$fax_data = array(
			'user' => Session::get('displayname'),
			'practice_id' => Session::get('practice_id')
		);
		$job_id = DB::table('sendfax')->insertGetId($fax_data);
		$this->audit('Add');
		Session::put('fax_job_id', $job_id);
		$directory = Session::get('documents_dir') . 'sentfax/' . $job_id;
		mkdir($directory, 0777);
		echo "New fax job " . $job_id . " created!";
	}
	
	public function postAddFaxRecipient()
	{
		$meta = array("(", ")", "-", " ");
		$data = array(
			'faxrecipient' => Input::get('displayname'),
			'faxnumber' => str_replace($meta, "", Input::get('fax')),
			'job_id' => Session::get('fax_job_id')
		);
		DB::table('recipients')->insert($data);
		$this->audit('Add');
		echo 'Contact added to recipient list';
	}
	
	public function postFaxImport()
	{
		$pid = Input::get('pid');
		$pt = Demographics::find($pid);
		if ($pt) {
			$directory = Session::get('documents_dir') . $pid;
			$result = Received::find(Input::get('received_id'));
			if (Input::get('fax_import_pages') == '') {
				$file1 = $result->filePath;
				$file2 = $directory . "/" . $result->fileName . '_' . time() . '.pdf';
				if (!copy($file1, $file2)) {
					echo "Fax import failed!";
					exit (0);
				}
			} else {
				$page_array = explode(",", Input::get('fax_import_pages'));
				$page = " ";
				foreach ($page_array as $page_item) {
					$page .= "A" . $page_item . " ";
				}
				$filename = str_replace(".pdf", "", $result->fileName);
				$file2 = $directory . "/" . $filename . "_" . time() . "_excerpt.pdf";
				$commandpdf2 = 'pdftk A="' . $result->filePath . '" cat' . $page . 'output "' . $file2 . '"';
				$commandpdf3 = escapeshellcmd($commandpdf2);
				exec($commandpdf3);
			}
			$pages_data2 = array(
				'documents_url' => $file2,
				'pid' => $pid,
				'documents_type' => Input::get('documents_type'),
				'documents_desc' => Input::get('documents_desc'),
				'documents_from' => Input::get('documents_from'),
				'documents_viewed' => Input::get('documents_viewed'),
				'documents_date' => date('Y-m-d', strtotime(Input::get('documents_date')))
			);
			$documents_id = DB::table('documents')->insertGetId($pages_data2);
			$this->audit('Add');
			echo 'Fax imported!';
			exit (0);
		} else {
			echo 'No patient for fax to be imported!';
			exit (0);
		}
	}
	
	public function postDraftsList()
	{
		if (Session::get('group_id') == '100') {
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
			$query = DB::table('sendfax')
				->where('faxdraft', '=', 'yes')
				->orWhereNull('faxdraft')
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
			$query1 = DB::table('sendfax')
				->where('faxdraft', '=', 'yes')
				->orWhereNull('faxdraft')
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
	}
	
	public function postSentList()
	{
		if (Session::get('group_id') == '100') {
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
			$query = DB::table('sendfax')
				->whereNotNull('senddate')
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
			$query1 = DB::table('sendfax')
				->whereNotNull('senddate')
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
	}
	
	public function postSetId()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			Session::forget('fax_job_id');
			Session::put('fax_job_id', Input::get('job_id'));
			echo 'OK';
		}
	}
	
	public function postSendList()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('fax_job_id');
			$page = Input::get('page');
			$limit = Input::get('rows');
			$sidx = Input::get('sidx');
			$sord = Input::get('sord');
			$query = DB::table('recipients')
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
			$query1 = DB::table('recipients')
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
	
	public function postEditSendList()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$meta = array("(", ")", "-", " ");
			$data = array(
				'faxrecipient' => Input::get('faxrecipient'),
				'faxnumber' => str_replace($meta, "", Input::get('faxnumber')),
				'job_id' => Session::get('fax_job_id')
			);
			$action = Input::get('oper');
			if ($action == 'edit') {
				DB::table('recipients')->where('sendlist_id', '=', Input::get('id'))->update($data);
				$this->audit('Update');
			}
			if ($action == 'add') {
				DB::table('recipients')->insert($data);
				$this->audit('Add');
			}
			if ($action == 'del') {
				DB::table('recipients')->where('sendlist_id', '=', Input::get('id'))->delete();
				$this->audit('Delete');
			}
		}
	}
	
	public function postPagesList()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('fax_job_id');
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
	
	public function postDeletepage()
	{
		DB::table('pages')->where('pages_id', '=', Input::get('pages_id'))->delete();
		$this->audit('Delete');
		unlink(Input::get('file'));
		echo 'Document removed!';
	}
	
	public function postSendfinal()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('fax_job_id');
			$query = DB::table('sendfax')->where('job_id', '=', $job_id)->first();
			if ($query) {
				$data = (array) $query;
				$data['task'] = "Draft";
			} else {
				$data['task'] = 'New';
			}
			$data['message'] = 'Fax job ' . $job_id . ' loaded!';
			echo json_encode($data);
		}
	}
	
	public function postSendFax($draft='')
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			ini_set('memory_limit','196M');
			$job_id = Session::get('fax_job_id');
			$fax_data = array(
				'faxdraft' => $draft,
				'faxschedule' => Input::get('faxschedule'),
				'faxsubject' => Input::get('faxsubject'),
				'faxcoverpage' => Input::get('faxcoverpage'),
				'faxmessage' => Input::get('faxmessage'),
				'senddate' => date('Y-m-d H:i:s'),
				'success' => '0',
				'attempts' => '0'
			);
			DB::table('sendfax')->where('job_id', '=', $job_id)->update($fax_data);
			$this->audit('Update');
			if ($draft == 'yes') {
				$arr = 'Fax Job ' . $job_id . ' Updated';
			} else {
				$arr = $this->send_fax($job_id, '', '');
			}
			Session::forget('fax_job_id');
			echo $arr;
		}
	}
	
	public function postCancelFax()
	{
		if (Session::get('group_id') == '100') {
			Auth::logout();
			Session::flush();
			header("HTTP/1.1 404 Page Not Found", true, 404);
			exit("You cannot do this.");
		} else {
			$job_id = Session::get('fax_job_id');
			if ($job_id != '') {
				$directory = Session::get('documents_dir') . 'sentfax/' . $job_id;
				$command = "rm -R " . $directory;
				$command1 = escapeshellcmd($command);
				exec($command1);
				DB::table('sendfax')->where('job_id', '=', $job_id)->delete();
				$this->audit('Delete');
				Session::forget('fax_job_id');
				echo 'Fax job ' .  $job_id . ' deleted and canceled!';
			} else {
				echo "Error deleting fax queue!";
			}
		}
	}
	
	public function postScans()
	{
		$practice_id = Session::get('practice_id');
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord'); 
		$query = DB::table('scans')
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
		$query1 = DB::table('scans')
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
	
	public function postViewScan($id)
	{
		$result = Scans::find($id);
		$file_path = $result->filePath;
		$name = time() . '_scan.pdf';
		$data['filepath'] = __DIR__.'/../../public/temp/' . $name;
		copy($file_path, $data['filepath']);
		$data['html'] = '<iframe src="' . asset('temp/' . $name) . '" width="770" height="425" style="border: none;"></iframe>';
		echo json_encode($data);
	}
	
	public function postCloseScan()
	{
		unlink(Input::get('scan_filepath'));
		echo 'OK';
	}
	
	public function postScanImport()
	{
		$pid = Input::get('pid');
		$pt = Demographics::find($pid);
		if ($pt) {
			$row = Scans::find(Input::get('scans_id'));
			$directory = Session::get('documents_dir') . $pid;
			if (Input::get('scan_import_pages') == '') {
				$filePath = $directory . "/" . $row->fileName . '_' . time() . '.pdf';
				if (!copy($row->filePath, $filePath)) {
					echo "Scan import failed!";
					exit (0);
				}
			} else {
				$page_array = explode(",", Input::get('scan_import_pages'));
				$page = " ";
				foreach ($page_array as $page_item) {
					$page .= "A" . $page_item . " ";
				}
				$filename = str_replace(".pdf", "", $row['fileName']);
				$filePath = $directory . "/" . $filename . "_" . time() . "_excerpt.pdf";
				$commandpdf2 = 'pdftk A="' . $row['filePath'] . '" cat' . $page . 'output "' . $filePath . '"';
				$commandpdf3 = escapeshellcmd($commandpdf2);
				exec($commandpdf3);
			}
			$data = array(
				'documents_url' => $filePath,
				'pid' => $pid,
				'documents_type' => Input::get('documents_type'),
				'documents_desc' => Input::get('documents_desc'),
				'documents_from' => Input::get('documents_from'),
				'documents_viewed' => Input::get('documents_viewed'),
				'documents_date' => date('Y-m-d', strtotime(Input::get('documents_date')))
			);
			DB::table('documents')->insert($data);
			$this->audit('Add');
			echo 'Document added!';
		} else {
			echo 'No patient for document to be imported!';
			exit (0);
		}
	}
	
	public function postDeletescan()
	{
		$row = Scans::find(Input::get('scans_id'));
		unlink($row->filePath);
		DB::table('scans')->where('scans_id', '=', Input::get('scans_id'))->delete();
		$this->audit('Delete');
		echo 'Document deleted!';
	}
	
	public function postAllContacts($mask='')
	{
		$page = Input::get('page');
		$limit = Input::get('rows');
		$sidx = Input::get('sidx');
		$sord = Input::get('sord');
		if($mask == ''){
			$query = DB::table('addressbook')->get();
		} else {
			$query = DB::table('addressbook')
				->where('displayname', 'LIKE', "%$mask%")
				->orWhere('specialty', 'LIKE', "%$mask%")
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
			$query1 = DB::table('addressbook')
				->orderBy($sidx, $sord)
				->skip($start)
				->take($limit)
				->get();
		} else {
			$query1 = DB::table('addressbook')
				->where('displayname', 'LIKE', "%$mask%")
				->orWhere('specialty', 'LIKE', "%$mask%")
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
	}
	
	public function postEditContact()
	{
		if(Input::get('firstname') == '' OR Input::get('lastname') == '') {
			$displayname = Input::get('facility');
		} else {
			if(Input::get('suffix') == '') {
				$displayname = Input::get('firstname') . ' ' . Input::get('lastname');
			} else {
				$displayname = Input::get('firstname') . ' ' . Input::get('lastname') . ', ' . Input::get('suffix');
			}
		}	
		$data = array(
			'displayname' => $displayname,
			'lastname' => Input::get('lastname'),
			'firstname' => Input::get('firstname'),
			'prefix' => Input::get('prefix'),
			'suffix' => Input::get('suffix'),
			'facility' => Input::get('facility'),
			'street_address1' => Input::get('street_address1'),
			'street_address2' => Input::get('street_address2'),
			'city' => Input::get('city'),
			'state' => Input::get('state'),
			'zip' => Input::get('zip'),
			'phone' => Input::get('phone'),
			'fax' => Input::get('fax'),
			'email' => Input::get('email'),
			'comments' => Input::get('comments'),
			'specialty' => Input::get('specialty'),
			'npi' => Input::get('npi')
		);	
		if(Input::get('address_id') == '') {
			DB::table('addressbook')->insert($data);
			$this->audit('Add');
			echo "Contact added!";
		} else {
			DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->update($data);
			$this->audit('Update');
			echo "Contact updated!";
		}
	}
	
	public function postDeleteContact()
	{
		DB::table('addressbook')->where('address_id', '=', Input::get('address_id'))->delete();
		$this->audit('Delete');
		echo "Contact deleted!";
	}
	
	public function pages_upload()
	{
		$job_id = Session::get('fax_job_id');
		$i = 0;
		foreach (Input::file('file') as $file) {
			if ($file) {
				if ($file->getMimeType() != 'application/pdf') {
					echo "This is not a PDF file.  Try again.";
					exit (0);
				}
				$directory = Session::get('documents_dir') . 'sentfax/';
				$file_size = $file->getSize();
				$file_original = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName()) . '_' . time() . '.pdf';
				$file->move($directory, $file_original);
				$file_path = $directory . $file_original;
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$pdftext = file_get_contents($file_path);
				$pagecount = preg_match_all("/\/Page\W/", $pdftext, $dummy);
				$data = array(
					'file' => $file_path,
					'file_original' => $file_original,
					'file_size' => $file_size,
					'pagecount' => $pagecount,
					'job_id' => $job_id
				);
				DB::table('pages')->insert($data);
				$this->audit('Add');
				$i++;
			}
		}
		echo $i . ' Document(s) added!';
	}
	
	public function import_contact()
	{
		$directory = __DIR__.'/../../public/import/';
		foreach (Input::file('file') as $file) {
			if ($file) {
				$file->move($directory, $file->getClientOriginalName());
				$file_path = $directory . $file->getClientOriginalName();
				while(!file_exists($file_path)) {
					sleep(2);
				}
				$csv = File::get($file_path);
				$result = Formatter::make($csv, 'csv')->to_array();
				if (empty(Formatter::$errors)) {
					$i = 0;
					foreach ($result as $field) {
						if($field['firstname'] == '' OR $field['lastname'] == '') {
							$field['displayname'] = $field['facility'];
						} else {
							if($this->input->post('suffix') == '') {
								$field['displayname'] = $field['firstname'] . ' ' . $field['lastname'];
							} else {
								$field['displayname'] = $field['firstname'] . ' ' . $field['lastname'] . ', ' . $field['suffix'];
							}
						}
						DB::table('addressbook')->insert($field);
						$this->audit('Add');
						$i++;
					}
					echo "Imported " . $i . " records!";
				} else {
					echo print_r(Formatter::$errors);
				}
			}
		}
	}
}
