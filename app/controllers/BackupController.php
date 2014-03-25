<?php

class BackupController extends BaseController
{

	/**
	* NOSH ChartingSystem Backup System, to be run as a cron job
	*/
	
	function backup()
	{
		$config_file = __DIR__."/../.env.php";
		$config = require($config_file);
		$row2 = Practiceinfo::find(1);
		$dir = $row2->documents_dir;
		$file = $dir . "noshbackup_" . time() . ".sql";
		$command = "mysqldump -u " . $config['mysql_username'] . " -p". $config['mysql_password'] . " " . $config['mysql_database'] . " > " . $file;
		system($command);
		$files = glob($dir . "*.sql");
		foreach ($files as $file_row) {
			$explode = explode("_", $file_row);
			$time = intval(str_replace(".sql","",$explode[1]));
			$month = time() - 604800;
			if ($time < $month) {
				unlink($file_row);
			}
		}
		DB::delete('delete from extensions_log where DATE_SUB(CURDATE(), INTERVAL 30 DAY) >= timestamp');
		File::cleanDirectory('/var/www/nosh/public/temp');
		//$extensions_prune = $this->db->query("DELETE FROM extensions_log WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) >= timestamp");
		exit (0);
	}
}
