<?php
namespace Flux\Link;

class LeadSplit extends BasicLink {
	
	private $lead_split;
	
	/**
	 * Returns the lead_split_id
	 * @return integer
	 */
	function getLeadSplitId() {
		return parent::getId();
	}
	
	/**
	 * Sets the lead_split_id
	 * @var integer
	 */
	function setLeadSplitId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the lead_split_name
	 * @return string
	 */
	function getLeadSplitName() {
		return parent::getName();
	}
	
	/**
	 * Sets the lead_split_name
	 * @var string
	 */
	function setLeadSplitName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the lead_split
	 * @return \Flux\LeadSplit
	 */
	function getLeadSplit() {
		if (is_null($this->lead_split)) {
			$this->lead_split = new \Flux\LeadSplit();
			$this->lead_split->setId($this->getId());
			$this->lead_split->query();
		}
		return $this->lead_split;
	}
}