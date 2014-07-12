<?php

class FaxController extends BaseController {

	/**
	* NOSH ChartingSystem Reminder System, to be run as a cron job
	*/
	
	public function fax()
	{
		$ret = 'Fax module inactive';
		$query1 = DB::table('practiceinfo')->get();
		foreach ($query1 as $row1) {
			$fax_type = $row1->fax_type;
			$smtp_user = $row1->fax_email;
			$smtp_pass = $row1->fax_email_password;
			$smtp_host = $row1->fax_email_hostname;
			if ($fax_type != "" && $fax_type != "phaxio") {
				$ret = 'Fax module active<br>';
				date_default_timezone_set($row1->timezone);
				if ($fax_type === "efaxsend.com") {
					$email_sender = "message@inbound.efax.com";
					$email_subject = 'eFax from';
				}
				if ($fax_type === "metrofax.com") {
					$email_sender = "noreply@metrofax.com";
					$email_subject = 'MetroFax message from';
				}
				if ($fax_type === "rcfax.com") {
					$email_sender = "notify@ringcentral.com";
					$email_subject = 'New Fax Message from';
				}
				$hostname = "{" . $smtp_host . "/imap/ssl/novalidate-cert}INBOX";
				$search_query = 'UNSEEN FROM "' . $email_sender . '" SUBJECT "'. $email_subject . '"';
				$connection = imap_open($hostname,$smtp_user,$smtp_pass) or die('Cannot connect to Gmail: ' . imap_last_error());
				$emails = imap_search($connection,$search_query);
				if($emails) {
					$ret .= 'Connection made and found mail that matched query<br>';
					rsort($emails);
					$i = 0;
					foreach($emails as $messageNumber) {
						$structure = imap_fetchstructure($connection, $messageNumber);
						$flattenedParts = $this->flattenParts($structure->parts);
						$info = imap_headerinfo($connection, $messageNumber);
						$date = strtotime($info->date);
						$data['fileDateTime'] = date('Y-m-d H:i:s', $date);
						$data['practice_id'] = $row1->practice_id;
						foreach($flattenedParts as $partNumber => $part) {
							if ($part->type === 0) {
								if ($fax_type === "efaxsend.com") {
									$subject = explode(" - ", $info->subject);
									$from = str_replace("eFax from ", "", $subject[0]);
									$pages = strstr($subject[1], ' ', true);
								} elseif ($fax_type === "metrofax.com") {
									$subject = explode(" ", $info->subject);
									$from = str_replace('"', '', $subject[4]);
									$pages = $subject[6];
								} elseif ($fax_type === "rcfax.com") {
									$subject = str_replace("New Fax Message from ", "", $info->subject);
									$subject_arr = explode(" on ", $subject);
									$from = $subject_arr[0];
									$message = $this->getPart($connection, $messageNumber, $partNumber, $part->encoding);
									$message_part = explode("<strong>Pages:</strong></td>", $message);
									$message_part1 = explode("</td>", $message_part[1]);
									$message_part2 = explode(">", $message_part1[0]);
									$pages = $message_part2[1];
								}
								$data['fileFrom'] = $from;
								$data['filePages'] = $pages;
							}
							$filename = $this->getFilenameFromPart($part);
							if($filename) {
								// it's an attachment
								$attachment = $this->getPart($connection, $messageNumber, $partNumber, $part->encoding);
								// save attachment
								$rp = '_' . time() . '.pdf';
								$file1 = str_replace('.pdf', $rp, $filename);
								$file2 = str_replace('.PDF', '', $filename);
								$path = $row1->documents_dir . 'received/' . $row1->practice_id . '/' . $file1;
								$xfp = fopen($path, 'w');
								if( $xfp ) {
									fwrite($xfp, $attachment);
									fclose($xfp);
								} else {
									die('Error saving attachment!');
								}
								$data['fileName'] = $file1;
								$data['filePath'] = $path;
							}
						}
						DB::table('received')->insert($data);
						$this->audit('Add');
						$i++;
					}
					$ret .= 'Number of messages: ' . $i;
				} else {
					$ret .= 'No connection made.';
				}
			}
			if ($fax_type == 'phaxio') {
				$phaxio_pending = DB::table('sendfax')->where('practice_id', '=', $row1->practice_id)->where('success', '=', '2')->get();
				if ($phaxio_pending) {
					foreach ($phaxio_pending as $phaxio_row) {
						$phaxio = new Phaxio($row1->phaxio_api_key, $row1->phaxio_api_secret);
						$phaxio_result = $phaxio->faxStatus($phaxio_row->command);
						$phaxio_result_array = json_decode($phaxio_result, true);
						if ($phaxio_result_array['data'][0]['status'] == 'success') {
							$fax_update_data['success'] = '1';
							DB::table('sendfax')->where('job_id', '=', $phaxio_row->job_id)->update($fax_update_data);
							$this->audit('Update');
						}
						if ($phaxio_result_array['data'][0]['status'] == 'failure') {
							$fax_update_data['success'] = '0';
							DB::table('sendfax')->where('job_id', '=', $phaxio_row->job_id)->update($fax_update_data);
							$this->audit('Update');
						}
					}
				}
			}
		}
		return $ret;
	}
	
	public function phaxio($practice_id)
	{
		$row = DB::table('practiceinfo')->where('practice_id', '=', $practice_id)->first();
		if ($row->fax_type == 'phaxio') {
			$result = json_decode(Input::get('fax'), true);
			$data['fileDateTime'] = date('Y-m-d H:i:s', $result['completed_at']);
			$data['practice_id'] = $practice_id;
			$data['fileFrom'] = $result['from_number'];
			$data['filePages'] = $result['num_pages'];
			$file1 = $result['id'] . '_' . time() . '.pdf';
			$path = $row->documents_dir . 'received/' . $practice_id . '/' . $file1;
			$phaxio = new Phaxio($row->phaxio_api_key, $row->phaxio_api_secret);
			$file_result = $phaxio->faxFile($result['id']);
			File::put($path, $file_result);
			$data['fileName'] = $file1;
			$data['filePath'] = $path;
			DB::table('received')->insert($data);
			$this->audit('Add');
		}
	}
}
