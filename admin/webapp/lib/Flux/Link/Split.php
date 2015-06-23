<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Split extends CommonForm {
	
	protected $split_id;
	protected $split_name;
	
	private $split;
	
	/**
	 * Returns the split_id
	 * @return integer
	 */
	function getSplitId() {
		if (is_null($this->split_id)) {
			$this->split_id = null;
		}
		return $this->split_id;
	}
	
	/**
	 * Sets the split_id
	 * @var integer
	 */
	function setSplitId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->split_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->split_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the split_name
	 * @return string
	 */
	function getSplitName() {
		if (is_null($this->split_name)) {
			$this->split_name = "";
		}
		return $this->split_name;
	}
	
	/**
	 * Sets the split_name
	 * @var string
	 */
	function setSplitName($arg0) {
		$this->split_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Split();
			$this->split->setId($this->getSplitId());
			$this->split->query();
		}
		return $this->split;
	}
}