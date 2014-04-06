<?php

class BackupController extends BaseController
{

	/**
	* NOSH ChartingSystem Backup and Updating System, to be run as a cron job
	*/
	
	public function backup()
	{
		$config_file = __DIR__."/../../.env.php";
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
		File::cleanDirectory(__DIR__."/../../public/temp");
	}
	
	public function update_system()
	{
		$current_version = File::get(__DIR__."/../../.version");
		$result = $this->github_all();
		if ($current_version != $result[0]['sha']) {
			$arr = array();
			foreach($result as $row) {
				$arr[] = $row['sha'];
				if ($current_version == $row['sha']) {
					break;
				}
			}
			$arr2 = array_reverse($arr);
			foreach($arr2 as $sha) {
				$result1 = $this->github_single($sha);
				if (isset($result1['files'])) {
					foreach($result1['files'] as $row1) {
						$filename = __DIR__."/../../" . $row1['filename'];
						if ($row1['status'] == 'added' || $row1['status'] == 'modified') {
							$github_url = str_replace(' ', '%20', $row1['raw_url']);
							$file = file_get_contents($github_url);
							$parts = explode('/', $row1['filename']);
							array_pop($parts);
							$dir = implode('/', $parts);
							if (!is_dir(__DIR__."/../../" . $dir)) {
								if ($parts[0] == 'public') {
									mkdir(__DIR__."/../../" . $dir, 0777, true);
								} else {
									mkdir(__DIR__."/../../" . $dir, 0755, true);
								}
							}
							file_put_contents($filename, $file);
						}
						if ($row1['status'] == 'removed') {
							if (file_exists($filename)) {
								unlink($filename);
							}
						}
					}
				}
			}
			Artisan::call('migrate');
			File::put(__DIR__."/../../.version", $result[0]['sha']);
			echo "System Updated with version " . $result[0]['sha'] . " from " . $current_version;
		} else {
			echo "No update needed";
		}
	}
}
