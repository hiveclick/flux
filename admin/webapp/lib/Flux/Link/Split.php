<?php
namespace Flux\Link;

class Split extends BasicLink {
	
	private $split;
	
	/**
	 * Returns the split_id
	 * @return integer
	 */
	function getSplitId() {
		return parent::getId();
	}
	
	/**
	 * Sets the split_id
	 * @var integer
	 */
	function setSplitId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the split_name
	 * @return string
	 */
	function getSplitName() {
		return parent::getName();
	}
	
	/**
	 * Sets the split_name
	 * @var string
	 */
	function setSplitName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Split();
			$this->split->setId($this->getId());
			$this->split->query();
		}
		return $this->split;
	}
}