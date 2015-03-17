<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Export extends CommonForm {
	
	protected $export_id;
	protected $export_name;
	
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
		$this->export_id = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the export_name
	 * @return string
	 */
	function getExportName() {
		if (is_null($this->export_name)) {
			$this->export_name = "";
		}
		return $this->export_name;
	}
	
	/**
	 * Sets the export_name
	 * @var string
	 */
	function setExportName($arg0) {
		$this->export_name = $arg0;
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
}