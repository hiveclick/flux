<?php
namespace Flux\Link;

class Vertical extends BasicLink {

	protected $record_count;
	
	private $vertical;
	
	/**
	 * Returns the vertical_id
	 * @return integer
	 */
	function getVerticalId() {
		return parent::getId();
	}
	
	/**
	 * Sets the vertical_id
	 * @var integer
	 */
	function setVerticalId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the vertical_name
	 * @return string
	 */
	function getVerticalName() {
		return parent::getName();
	}
	
	/**
	 * Sets the vertical_name
	 * @var string
	 */
	function setVerticalName($arg0) {
		return parent::setName($arg0);
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