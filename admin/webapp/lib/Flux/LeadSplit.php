<?php
namespace Flux;

class LeadSplit extends Base\LeadSplit {
	
	private $disposition_array;
	private $offer_id_array;
	private $split_id_array;
	private $date_range;
	private $fulfillment;
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the disposition_array
	 * @return array
	 */
	function getDispositionArray() {
		if (is_null($this->disposition_array)) {
			$this->disposition_array = array();
		}
		return $this->disposition_array;
	}
	
	/**
	 * Sets the disposition_array
	 * @var array
	 */
	function setDispositionArray($arg0) {
		if (is_array($arg0)) {
			$this->disposition_array = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->disposition_array = explode(",", $arg0);
			} else {
				$this->disposition_array = array($arg0);
			}
		} else if (is_int($arg0)) {
			$this->disposition_array = array($arg0);
		}
		array_walk($this->disposition_array, function(&$value) { $value = (int)trim($value); });
		return $this;
	}
	
	/**
	 * Returns the offer_id_array
	 * @return array
	 */
	function getOfferIdArray() {
		if (is_null($this->offer_id_array)) {
			$this->offer_id_array = array();
		}
		return $this->offer_id_array;
	}
	
	/**
	 * Sets the offer_id_array
	 * @var array
	 */
	function setOfferIdArray($arg0) {
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
			} else {
				$this->offer_id_array = array($arg0);
			}
		} else if ($arg0 instanceof \MongoId) {
			$this->offer_id_array = array($arg0);
		}
		array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		return $this;
	}
	
	/**
	 * Returns the date_range
	 * @return integer
	 */
	function getDateRange() {
		if (is_null($this->date_range)) {
			$this->date_range = \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS;
		}
		return $this->date_range;
	}
	
	/**
	 * Sets the date_range
	 * @var integer
	 */
	function setDateRange($arg0) {
		$this->date_range = (int)$arg0;
		$this->addModifiedColumn("date_range");
		return $this;
	}
	
	/**
	 * Returns the split_id_array
	 * @return array
	 */
	function getSplitIdArray() {
		if (is_null($this->split_id_array)) {
			$this->split_id_array = array();
		}
		return $this->split_id_array;
	}
	
	/**
	 * Sets the split_id_array
	 * @var array
	 */
	function setSplitIdArray($arg0) {
		if (is_array($arg0)) {
			$this->split_id_array = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->split_id_array = explode(",", $arg0);
			} else {
				$this->split_id_array = array($arg0);
			}
		} else if ($arg0 instanceof \MongoId) {
			$this->split_id_array = array($arg0);
		}
		array_walk($this->split_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
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
			$fulfillment = $this->getFulfillment();
			$fulfillment->populate($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if (is_string($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if ($arg0 instanceof \MongoId) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		}
		return $this;
	}
	
	/**
	 * Queries all records from a queue
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (\MongoId::isValid($this->getLead()->getId())) {
			$criteria['lead._id'] = $this->getLead()->getId();
		} else {
			if (count($this->getSplitIdArray()) > 0) {
				$criteria['split._id'] = array('$in' => $this->getSplitIdArray());
			}
			if ($this->getDateRange() != \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM) {
				$date_range_form = new \Mojavi\Form\DateRangeForm();
				$date_range_form->setDateRange($this->getDateRange());
				$criteria['queue_time'] = array('$gte' => new \MongoDate($date_range_form->getStartDate()), '$lte' => new \MongoDate($date_range_form->getEndDate()));
			}
			if (count($this->getOfferIdArray()) > 0) {
				$criteria['lead.offer._id'] = array('$in' => $this->getOfferIdArray());
			}
			if (count($this->getDispositionArray()) > 0) {
				$criteria['disposition'] = array('$in' => $this->getDispositionArray());
			}
			if (trim($this->getKeywords()) != '') {
				$criteria['$or'] = array(
						array('lead._id' => new \MongoRegex('/' . trim($this->getKeywords()) . '/')),
						array('lead.name' =>  new \MongoRegex('/' . trim($this->getKeywords()) . '/i'))
				);
			}
		}		
		return parent::queryAll($criteria, $hydrate, $fields);
	}
	
}