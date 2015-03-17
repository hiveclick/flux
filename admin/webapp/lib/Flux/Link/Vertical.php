<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Vertical extends CommonForm {
	
	protected $vertical_id;
	protected $vertical_name;
	protected $record_count;
	
	private $vertical;
	
	/**
	 * Returns the vertical_id
	 * @return integer
	 */
	function getVerticalId() {
		if (is_null($this->vertical_id)) {
			$this->vertical_id = 0;
		}
		return $this->vertical_id;
	}
	
	/**
	 * Sets the vertical_id
	 * @var integer
	 */
	function setVerticalId($arg0) {
		$this->vertical_id = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the vertical_name
	 * @return string
	 */
	function getVerticalName() {
		if (is_null($this->vertical_name)) {
			$this->vertical_name = "";
		}
		return $this->vertical_name;
	}
	
	/**
	 * Sets the vertical_name
	 * @var string
	 */
	function setVerticalName($arg0) {
		$this->vertical_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the record_count
	 * @return integer
	 */
	function getRecordCount() {
		if (is_null($this->record_count)) {
			$this->record_count = 0;
		}
		return $this->record_count;
	}
	
	/**
	 * Sets the record_count
	 * @var integer
	 */
	function setRecordCount($arg0) {
		$this->record_count = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the vertical
	 * @return \Flux\Vertical
	 */
	function getVertical() {
		if (is_null($this->vertical)) {
			$this->vertical = new \Flux\Vertical();
			$this->vertical->setId($this->getVerticalId());
			$this->vertical->query();
		}
		return $this->vertical;
	}
}