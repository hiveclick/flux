<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Fulfillment extends CommonForm {
	
	protected $fulfillment_id;
	protected $fulfillment_name;
	
	private $fulfillment;
	
	/**
	 * Returns the fulfillment_id
	 * @return integer
	 */
	function getFulfillmentId() {
		if (is_null($this->fulfillment_id)) {
			$this->fulfillment_id = null;
		}
		return $this->fulfillment_id;
	}
	
	/**
	 * Sets the fulfillment_id
	 * @var integer
	 */
	function setFulfillmentId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->fulfillment_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->fulfillment_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the fulfillment_name
	 * @return string
	 */
	function getFulfillmentName() {
		if (is_null($this->fulfillment_name)) {
			$this->fulfillment_name = "";
		}
		return $this->fulfillment_name;
	}
	
	/**
	 * Sets the fulfillment_name
	 * @var string
	 */
	function setFulfillmentName($arg0) {
		$this->fulfillment_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Fulfillment();
			$this->fulfillment->setId($this->getFulfillmentId());
			$this->fulfillment->query();
		}
		return $this->fulfillment;
	}
}