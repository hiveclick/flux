<?php

namespace Flux\Daemon;

use Flux\Export as ExportDocument;

class Export extends BaseDaemon
{
	
	public function action() {
		$export_record = $this->getNextExport();
		if ($export_record instanceof ExportDocument) {
			$this->log('==============================================', array($this->pid, $export_record->getId()));
			$this->log('Processing Export: ' . $export_record->getName(), array($this->pid, $export_record->getId()));

			$exporter = new \Flux\ExportQueueExporter();
			$exporter->setExportId($export_record->getId());
			$exporter->processExport();
						
			$export_record->update(array('_id' => $export_record->retrieveValue('_id')), array('$unset' => array('__pid_split' => 1), '$set' => array('last_run_time' => new \MongoDate())), array());


			$this->log('Done Processing Split: ' . $export_record->getName(), array($this->pid, $export_record->getId()));
			return true;
		}
		$this->log('No Export to Use', array($this->pid));
		return false;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Flux\Split
	 */
	protected function getNextExport() {
		$exportDocument = new \Flux\Export();
		// Find active exports with no pid, set the pid, and return the export
		$export_record = $exportDocument->findAndModify(
			array(
				'status' => ExportDocument::EXPORT_STATUS_ACTIVE,
				'__pid_export' => array('$exists' => false)
			),
			array('$set' => array(
				'__pid_export' => $this->pid,
				'__pid_time_export' => new \MongoDate()
			)),
			null,
			array(
				'new' => true,
				'sort' => array('__pid_time_export' => -1)
			)
		);
		return $export_record;
	}
}
