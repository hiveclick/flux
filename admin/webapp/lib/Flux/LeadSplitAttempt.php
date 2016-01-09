<?php
namespace Flux;

class LeadSplitAttempt extends Base\LeadSplitAttempt {
	
	private $lead;
	private $lead_split;
	private $test;
	private $override_fulfillment;
	
	/**
	 * Returns the lead_split
	 * @return \Flux\LeadSplit
	 */
	function getLeadSplit() {
		if (is_null($this->lead_split)) {
			$this->lead_split = new \Flux\Link\LeadSplit();
		}
		return $this->lead_split;
	}
	
	/**
	 * Sets the lead_split
	 * @var \Flux\LeadSplit
	 */
	function setLeadSplit($arg0) {
		if (is_array($arg0)) {
			$this->lead_split = new \Flux\Link\LeadSplit();
			$this->lead_split->populate($arg0);
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$this->lead_split = new \Flux\Link\LeadSplit();
			$this->lead_split->setId($arg0);
		} else if ($arg0 instanceof \MongoId) {
			$this->lead_split = new \Flux\Link\LeadSplit();
			$this->lead_split->setId($arg0);
		} else if ($arg0 instanceof \Flux\Link\LeadSplit) {
			$this->lead_split = $arg0;
		}
		$this->addModifiedColumn("lead_split");
		return $this;
	}
	
	/**
	 * Returns the override_fulfillment
	 * @return boolean
	 */
	function getOverrideFulfillment() {
		if (is_null($this->override_fulfillment)) {
			$this->override_fulfillment = false;
		}
		return $this->override_fulfillment;
	}
	
	/**
	 * Sets the override_fulfillment
	 * @var boolean
	 */
	function setOverrideFulfillment($arg0) {
		$this->override_fulfillment = $arg0;
		$this->addModifiedColumn("override_fulfillment");
		return $this;
	}
	
	/**
	 * Returns the test
	 * @return boolean
	 */
	function getTest() {
		if (is_null($this->test)) {
			$this->test = false;
		}
		return $this->test;
	}
	
	/**
	 * Sets the test
	 * @var boolean
	 */
	function setTest($arg0) {
		$this->test = (boolean)$arg0;
		$this->addModifiedColumn("test");
		return $this;
	}
	
	/**
	 * Returns the lead
	 * @return \Flux\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Lead();
		}
		return $this->lead;
	}
	
	/**
	 * Sets the lead
	 * @var \Flux\Lead
	 */
	function setLead($arg0) {
		if (is_array($arg0)) {
			$this->lead = new \Flux\Lead();
			$this->lead->populate($arg0);
			if (\MongoId::isValid($this->lead->getId())) {
				$this->lead->query();
			}
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$this->lead = new \Flux\Lead();
			$this->lead->setId($arg0);
			$this->lead->query();
		} else if ($arg0 instanceof \MongoId) {
			$this->lead = new \Flux\Lead();
			$this->lead->setId($arg0);
			$this->lead->query();
		} else if ($arg0 instanceof \Flux\Lead) {
			$this->lead = $arg0;
		}
		$this->addModifiedColumn("lead");
		return $this;
	}
	
	
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Merges the lead with the fulfillment mapping and generates a query string
	 * @return array
	 */
	function mergeLead() {
		$params = array();
		if (\MongoId::isValid($this->getLeadSplit()->getLeadSplit()->getLead()->getId())) {
			/* @var $mapping \Flux\FulfillmentMap */
			$lead = $this->getLeadSplit()->getLeadSplit()->getLead()->getLead();
			foreach ($this->getFulfillment()->getFulfillment()->getMapping() as $mapping) {
				$value = $mapping->getMappedValue($lead);
				if (trim($mapping->getFieldName()) == '') {
					$params[$mapping->getDataField()->getKeyName()] = $value;
				} else {
					$params[$mapping->getFieldName()] = $value;
				}
			}
		} else if (\MongoId::isValid($this->getLead()->getId())) {
			// If we don't have a lead split, then we are testing a fulfillment with a real lead, so 
			// pull from the $this->getLead() function instead
			/* @var $mapping \Flux\FulfillmentMap */
			$lead = $this->getLead();
			foreach ($this->getFulfillment()->getFulfillment()->getMapping() as $mapping) {
				$value = $mapping->getMappedValue($lead);
				if (trim($mapping->getFieldName()) == '') {
					$params[$mapping->getDataField()->getKeyName()] = $value;
				} else {
					$params[$mapping->getFieldName()] = $value;
				}
			}
		} else {
			throw new \Exception('Invalid lead when trying to merge fulfillment (' . $this->getLeadSplit()->getId() . '/' .  $this->getLeadSplit()->getLeadSplit()->getLead()->getId(). ')');
		}
		return $params;
	}
}