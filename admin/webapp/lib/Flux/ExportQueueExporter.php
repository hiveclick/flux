<?php
namespace Flux;

use Mojavi\Form\CommonForm;
use Mojavi\Util\StringTools;
/**
 * This class will process an export queue and send it out
 * @author Mark Hobson
 */
class ExportQueueExporter extends CommonForm {
	
	private $export_id;
	
	private $export;
	
	/**
	 * Returns the export_id
	 * @return integer
	 */
	function getExportId() {
		if (is_null($this->export_id)) {
			$this->export_id = 0;
		}
		return $this->export_id;
	}
	
	/**
	 * Sets the export_id
	 * @var integer
	 */
	function setExportId($arg0) {
		$this->export_id = $arg0;
		return $this;
	}
	
	/**
	 * Returns the export
	 * @return \Flux\Export
	 */
	function getExport() {
		if (is_null($this->export)) {
			$this->export = new \Flux\Export();
			$this->export->setId($this->getExportId());
			$this->export->query();
		}
		return $this->export;
	}
	
	/**
	 * Sets the export
	 * @var \Flux\Export
	 */
	function setExport($arg0) {
		$this->export = $arg0;
		return $this;
	}
	
	/**
	 * Returns the export folder
	 * @return string
	 */
	function getExportFolder() {
		$export_folder = MO_WEBAPP_DIR . "/meta/exports/" . $this->getExportId() . "/";
		return $export_folder;
	}
	
	/**
	 * Processes this export
	 * @return boolean
	 */
	function processExport() {
		try {
			// Flag export as processing
			StringTools::consoleWrite('Starting export #' . $this->getExportId(), null, StringTools::CONSOLE_COLOR_GREEN, true);
			$this->getExport()->setIsRunning(true);
			$this->getExport()->setStartTime(new \MongoDate());
			$this->getExport()->update();
			
			// Create the export folder that we will use to aggregate the records
			$start_time = microtime(true);
			StringTools::consoleWrite('  Creating Directory Structure', 'Creating', StringTools::CONSOLE_COLOR_RED);
			$cmd = 'mkdir -p ' . $this->getExportFolder();
			shell_exec($cmd);
			StringTools::consoleWrite('  Creating Directory Structure', 'Created (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
			
			// Update the percentage done
			$this->getExport()->setPercentComplete(20);
			$this->getExport()->update();
			
			// Find out if we have any items to process
			$start_time = microtime(true);
			StringTools::consoleWrite('  Finding records in queue', 'Finding', StringTools::CONSOLE_COLOR_RED);
			$export_queue = new \Flux\ExportQueue($this->getExportId());
			$export_queue->setIgnorePagination(true);
			$queue_items = $export_queue->queryAll(array(), array(), false);
			$this->getExport()->setNumRecords($queue_items->count());
			$this->getExport()->setFindingRecordsTime((microtime(true) - $start_time));
			StringTools::consoleWrite('  Finding records in queue', 'Found ' . $queue_items->count() . ' items (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
						
			// Update the percentage done
			$this->getExport()->setPercentComplete(50);
			$this->getExport()->update();
			if (!$queue_items->hasNext()) {
				throw new \Exception('No records found to export, stopping');
			}
			
			// Handle realtime emails differently
			/* @var $export_class \Flux\Export\ExportAbstract */
			StringTools::consoleWrite('  Creating export handler', 'Creating', StringTools::CONSOLE_COLOR_RED);
			$export_class = $this->getExport()->getFulfillment()->getFulfillment()->getExportClass();
			$export_class->setExportId($this->getExportId());
			StringTools::consoleWrite('  Creating export handler', 'Created', StringTools::CONSOLE_COLOR_GREEN, true);
			
			$start_time = microtime(true);
			StringTools::consoleWrite('  Sending records in queue', 'Sending', StringTools::CONSOLE_COLOR_RED);
			$results = $export_class->send($queue_items);
			$this->getExport()->setSendingRecordsTime((microtime(true) - $start_time));
			StringTools::consoleWrite('  Sending records in queue', 'Sent', StringTools::CONSOLE_COLOR_GREEN, true);
			
			// Calculate how many records were successful
			StringTools::consoleWrite('  Gathering queue statistics', 'Gathering', StringTools::CONSOLE_COLOR_RED);
			$successful_count = $export_queue->count(array('is_error' => false));
			$error_count = $export_queue->count(array('is_error' => true));
			$this->getExport()->setNumRecordsSuccessful($successful_count);
			$this->getExport()->setNumRecordsError($error_count);
			$this->getExport()->update();
			StringTools::consoleWrite('  Gathering queue statistics', number_format($successful_count, 0, null, ',') . ' items successful / ' . number_format($error_count, 0, null, ',') . ' items failed', StringTools::CONSOLE_COLOR_GREEN, true);
			
			// Update any notes on leads
			StringTools::consoleWrite('  Updating leads', 'Updating', StringTools::CONSOLE_COLOR_RED);
			/* @var $export_queue_item \Flux\ExportQueue */
			foreach ($results as $export_queue_item) {
				if (!$export_queue_item->getIsError()) {
					$lead = $export_queue_item->getLead()->getLead();
					$lead->addNote('Lead Successfully fulfilled to ' . $this->getExport()->getName());
					$lead->update();
				} else {
					$lead = $export_queue_item->getLead()->getLead();
					$lead->addNote('Lead UNSUCCESSFULLY fulfilled to ' . $this->getExport()->getName() . ' (' . htmlentities($export_queue_item->getResponse()) . ')');
					$lead->update();
				}
			}
			StringTools::consoleWrite('  Updating leads', 'Updated', StringTools::CONSOLE_COLOR_GREEN, true);
			
			$this->getExport()->setIsRunning(false);
			$this->getExport()->setIsComplete(true);
			$this->getExport()->setPercentComplete(100);
			$this->getExport()->setEndTime(new \MongoDate());
			$this->getExport()->update();
			
			StringTools::consoleWrite('Export Finished', null, StringTools::CONSOLE_COLOR_GREEN, true);
		} catch (\Exception $e) {
			echo StringTools::consoleColor('Error: ' . $e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return true;
	}
	
}