<?php
namespace Flux\Export;

use Mojavi\Form\MongoForm;

/**
 * Abstract export class used by all export classes
 * @author Mark Hobson
 */
abstract class ExportAbstract extends MongoForm {
	
	const FULFILLMENT_TYPE_NONE = 0;
	const FULFILLMENT_TYPE_POST = 1;
	const FULFILLMENT_TYPE_FTP = 2;
	const FULFILLMENT_TYPE_EMAIL = 3;
	const FULFILLMENT_TYPE_INFUSIONSOFT = 4;
	const FULFILLMENT_TYPE_MULTI_POST = 5;
	const FULFILLMENT_TYPE_MANUAL = 6;
	const FULFILLMENT_TYPE_UBOT = 7;
	const FULFILLMENT_TYPE_MAILCHIMP = 8;
	
	protected $name;
	protected $description;
	protected $fulfillment_type;

	protected $fulfillment;
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn("name");
		return $this;
	}
	
	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}
	
	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn("description");
		return $this;
	}
	
	/**
	 * Returns the fulfillment_type
	 * @return integer
	 */
	function getFulfillmentType() {
		if (is_null($this->fulfillment_type)) {
			$this->fulfillment_type = self::FULFILLMENT_TYPE_NONE;
		}
		return $this->fulfillment_type;
	}
	
	/**
	 * Sets the fulfillment_type
	 * @var integer
	 */
	function setFulfillmentType($arg0) {
		$this->fulfillment_type = $arg0;
		$this->addModifiedColumn("fulfillment_type");
		return $this;
	}
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Link\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Link\Fulfillment();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Sets the fulfillment
	 * @var \Flux\Link\Fulfillment
	 */
	function setFulfillment($arg0) {
	    if (is_array($arg0)) {
	        $fulfillment = new \Flux\Link\Fulfillment();
	        $fulfillment->populate($arg0);
	        if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
	            $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
	        }
	        $this->fulfillment = $fulfillment;
	    } else if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $fulfillment = new \Flux\Link\Fulfillment();
	        $fulfillment->setFulfillmentId($arg0);
	        if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
	            $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
	        }
	        $this->fulfillment = $fulfillment;
	    } else if ($arg0 instanceof \MongoId) {
	        $fulfillment = new \Flux\Link\Fulfillment();
	        $fulfillment->setFulfillmentId($arg0);
	        if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
	            $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
	        }
	        $this->fulfillment = $fulfillment;
	    }
		$this->addModifiedColumn("fulfillment");
		return $this;
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $split_queue_attempts
	 * @return boolean
	 */
	abstract function send($split_queue_attempts, $is_test = false);
	
	/**
	 * Determines if this person can accept the lead or not
	 * @param \Flux\Lead $lead
	 * @return boolean
	 */
	function canReceiveLead($lead) {
		// If we get this far, then return true
		return true;
	}
	
} 