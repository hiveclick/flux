<?php
namespace Gun;

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
	 * @return \Gun\Export
	 */
	function getExport() {
		if (is_null($this->export)) {
			$this->export = new \Gun\Export();
			$this->export->setId($this->getExportId());
			$this->export->query();
		}
		return $this->export;
	}
	
	/**
	 * Sets the export
	 * @var \Gun\Export
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
		$export_queue = new \Gun\ExportQueue($this->getExportId());
		$export_queue->setIgnorePagination(true);
		$queue_items = $export_queue->queryAll(array(), false);
		$this->getExport()->setFindingRecordsTime((microtime(true) - $start_time));
		StringTools::consoleWrite('  Finding records in queue', 'Found ' . number_format(count($queue_items), 0, null, ',') . ' items (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
		
		// Update the percentage done
		$this->getExport()->setPercentComplete(50);
		$this->getExport()->update();
		
		if (count($queue_items) == 0) {
			throw new \Exception('No records found to export, stopping');
		}
		
		// Handle realtime emails differently
		/* @var $export_class \Gun\Export\ExportAbstract */
		StringTools::consoleWrite('  Creating export handler', 'Creating', StringTools::CONSOLE_COLOR_RED);
		$export_class = $this->getExport()->getClientExport()->getExportClass();
		$export_class->setExportId($this->getExportId());
		StringTools::consoleWrite('  Creating export handler', 'Created', StringTools::CONSOLE_COLOR_GREEN, true);
		
		$export_class->send($queue_items);
		
		$this->getExport()->setIsRunning(false);
		$this->getExport()->setIsComplete(true);
		$this->getExport()->setPercentComplete(100);
		$this->getExport()->setEndTime(new \MongoDate());
		$this->getExport()->update();
		
		StringTools::consoleWrite('Export Finished', null, StringTools::CONSOLE_COLOR_GREEN, true);
		return true;
	}
	
}