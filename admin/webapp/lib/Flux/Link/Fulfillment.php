<?php
namespace Flux\Link;

class Fulfillment extends BasicLink {
	
	private $fulfillment;
	
	/**
	 * Returns the fulfillment_id
	 * @return integer
	 */
	function getFulfillmentId() {
		return parent::getId();
	}
	
	/**
	 * Sets the fulfillment_id
	 * @var integer
	 */
	function setFulfillmentId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the fulfillment_name
	 * @return string
	 */
	function getFulfillmentName() {
		return parent::getName();
	}
	
	/**
	 * Sets the fulfillment_name
	 * @var string
	 */
	function setFulfillmentName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Fulfillment();
			$this->fulfillment->setId($this->getId());
			$this->fulfillment->query();
		}
		return $this->fulfillment;
	}
}