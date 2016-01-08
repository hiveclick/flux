<?php
namespace Flux\Link;

class Export extends BasicLink {
	
	private $export;
	
	/**
	 * Returns the export_id
	 * @return integer
	 */
	function getExportId() {
		return parent::getId();
	}
	
	/**
	 * Sets the export_id
	 * @var integer
	 */
	function setExportId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the export_name
	 * @return string
	 */
	function getExportName() {
		return parent::getName();
	}
	
	/**
	 * Sets the export_name
	 * @var string
	 */
	function setExportName($arg0) {
		return parent::setName($arg0);
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
}