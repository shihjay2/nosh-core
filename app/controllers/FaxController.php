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
			if ($fax_type != "") {
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
								} else {
									if($part->subtype === "PLAIN") {
										$message = $this->getPart($connection, $messageNumber, $partNumber, $part->encoding);
										$from_pos_s = strpos($message, "From:");
										$from_substr = substr($message, $from_pos_s);
										if ($fax_type === "rcfax.com") {
											$from_substr1 = strstr($from_substr, 'Received:', true);
										} else {
											$from_substr1 = strstr($from_substr, '=', true);
										}
										$from = strstr($from_substr1, ':');
										$from = str_replace(": ", "", $from);
										if ($fax_type === "rcfax.com") {
											$pages_pos_s = strpos($message, "Pages:");
											$pages_substr = substr($message, $pages_pos_s);
											$pages_substr1 = strstr($pages_substr, 'To:', true);
										} else {
											$pages_pos_s = strpos($message, "Page");
											$pages_substr = substr($message, $pages_pos_s);
											$pages_substr1 = strstr($pages_substr, '=', true);
										}
										$pages = strstr($pages_substr1, ':');
										$pages = str_replace(": ", "", $pages);
									}
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
		}
		return $ret;
	}
}
