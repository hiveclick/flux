<?php
namespace Gun\Export;

use \Gun\Export\ExportAbstract;
use Mojavi\Util\StringTools;
use Gun\ExportQueue;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericFtp extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_FTP);
		$this->setName('Generic FTP Export');
		$this->setDescription('Send leads to the FTP server');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array $leads
	 * @return boolean
	 */
	function send(array $leads) {
		$start_time = microtime(true);
		StringTools::consoleWrite('  Building file of recipients', 'Building', StringTools::CONSOLE_COLOR_RED);
		// Create an export file that we will for the FTP or email attachment
		if (($fh = fopen($this->getExportFolder() . '/original.txt', 'w')) !== false) {
			$mapping = $this->getExport()->getClientExport()->getMapping();
		
			// Write out our headers
			$header = array();
			foreach ($mapping as $mapping_item) {
				$data_field = \Gun\DataField::retrieveDataFieldFromId($mapping_item['datafield_id']);
				$header[] = $data_field->getName();
			}
			fwrite($fh, implode("\t", $header) . "\n");
		
			foreach ($leads as $key => $queue_item) {
				$line = array();
				$qs = $queue_item->getQs();
				foreach ($mapping as $mapping_item) {
					$data_field = \Gun\DataField::retrieveDataFieldFromId($mapping_item['datafield_id']);
					if (isset($qs[$data_field->getKeyName()])) {
						$line[] = trim($qs[$data_field->getKeyName()]);
					} else {
						$line[] = '';
					}
				}
				fwrite($fh, implode("\t", $line) . "\n");
		
				// Update the percentage done
				$this->getExport()->setPercentComplete((($key / count($leads)) * 40) + 50);
				$this->getExport()->update();
			}
		
			fclose($fh);
			StringTools::consoleWrite('  Building file of recipients', 'Built ' . number_format(count($leads), 0, null, ',') . ' (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
		} else {
			throw new \Exception('Cannot open export file ' . $this->getExportFolder() . '/original.txt');
		}
		$this->getExport()->setSendingRecordsTime((microtime(true) - $start_time));
		$this->getExport()->setPercentComplete(95);
		$this->getExport()->update();
		
		/*
		 * Here we will handle batch ftp, where leads are sent to an ftp server
		 */
		try {
			$start_time = microtime(true);
			StringTools::consoleWrite('  Sending as FTP', 'Sending', StringTools::CONSOLE_COLOR_RED);
			if (trim($this->getExport()->getClientExport()->getFtpHostname()) != '') {
				StringTools::consoleWrite('  Sending as FTP', 'Connecting to ' . $this->getExport()->getClientExport()->getFtpHostname(), StringTools::CONSOLE_COLOR_RED);
				if ((@$ftp_conn = ftp_connect($this->getExport()->getClientExport()->getFtpHostname(), $this->getExport()->getClientExport()->getFtpPort())) !== false) {
					StringTools::consoleWrite('  Sending as FTP', 'Connected to ' . $this->getExport()->getClientExport()->getFtpHostname(), StringTools::CONSOLE_COLOR_GREEN, true);
					StringTools::consoleWrite('  Sending as FTP', 'Logging in as ' . $this->getExport()->getClientExport()->getFtpUsername(), StringTools::CONSOLE_COLOR_RED);
					if (@ftp_login($ftp_conn, $this->getExport()->getClientExport()->getFtpUsername(), $this->getExport()->getClientExport()->getFtpPassword())) {
						StringTools::consoleWrite('  Sending as FTP', 'Logged in as ' . $this->getExport()->getClientExport()->getFtpUsername(), StringTools::CONSOLE_COLOR_GREEN, true);
						if (trim($this->getExport()->getClientExport()->getFtpFolder()) !== '') {
							if (!@ftp_chdir($ftp_conn, $this->getExport()->getClientExport()->getFtpFolder())) {
								// Attempt to make the folder
								if (@ftp_mkdir($ftp_conn, $this->getExport()->getClientExport()->getFtpFolder()) !== false) {
									if (!@ftp_chdir($ftp_conn, $this->getExport()->getClientExport()->getFtpFolder())) {
										throw new \Exception('We can login to the ftp server at ' . $this->getExport()->getClientExport()->getFtpHostname() . ', but the folder /' . $this->getExport()->getClientExport()->getFtpFolder() . ' does not exist.');
									}
								} else {
									throw new \Exception('We can login to the ftp server at ' . $this->getExport()->getClientExport()->getFtpHostname() . ', but the folder /' . $this->getExport()->getClientExport()->getFtpFolder() . ' does not exist and we could not create it');
								}
							}
						}
						// Now push the file
						if (ftp_put($ftp_conn, 'leads_' . date('YmdHms') . '.txt', $this->getExportFolder() . '/original.txt', FTP_ASCII) === false) {
							ftp_close($ftp_conn);
							throw new \Exception('We can login to the ftp server at ' . $this->getExport()->getClientExport()->getFtpHostname() . ', but cannot write the file');
		
						}
						$export_queue = new ExportQueue($this->getExportId());
						$export_queue->updateMultiple(array('export_id' => $this->getExportId()), array('$set' => array('response' => 'Sent', 'is_error' => false)));
						ftp_close($ftp_conn);
					} else {
						ftp_close($ftp_conn);
						throw new \Exception('We can connect to the ftp server at ' . $this->getExport()->getClientExport()->getFtpHostname() . ', but cannot login to it.');
					}
				} else {
					throw new \Exception('Cannot connect to the ftp server at ' . $this->getExport()->getClientExport()->getFtpHostname());
				}
			} else {
				throw new \Exception('You did not enter a valid ftp hostname');
			}
		
			StringTools::consoleWrite('  Sending via FTP', 'Sent (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
		} catch (\Exception $e) {
			$export_queue = new ExportQueue($this->getExportId());
			$export_queue->updateMultiple(array('export_id' => $this->getExportId()), array('$set' => array('response' => $e->getMessage(), 'is_error' => true)));
		}
	}
}
?>