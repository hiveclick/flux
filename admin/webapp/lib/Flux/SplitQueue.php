<?php
namespace Flux;

class SplitQueue extends Base\SplitQueue {

    private $disposition_array;
    private $offer_id_array;
    private $split_id_array;
    private $fulfillment;
    private $hide_unfulfillable;
    private $hide_fulfilled;
    private $hide_catch_all;
    
    protected $test;
    
    /**
     * Returns the hide_catch_all
     * @return boolean
     */
    function getHideCatchAll() {
        if (is_null($this->hide_catch_all)) {
            $this->hide_catch_all = false;
        }
        return $this->hide_catch_all;
    }
    
    /**
     * Sets the hide_catch_all
     * @var boolean
     */
    function setHideCatchAll($arg0) {
        $this->hide_catch_all = (boolean)$arg0;
        return $this;
    }
    
    /**
     * Returns the hide_unfulfillable
     * @return boolean
     */
    function getHideUnfulfillable() {
        if (is_null($this->hide_unfulfillable)) {
            $this->hide_unfulfillable = false;
        }
        return $this->hide_unfulfillable;
    }
    
    /**
     * Sets the hide_unfulfillable
     * @var boolean
     */
    function setHideUnfulfillable($arg0) {
        $this->hide_unfulfillable = (boolean)$arg0;
        return $this;
    }
    
    /**
     * Returns the hide_fulfilled
     * @return boolean
     */
    function getHideFulfilled() {
        if (is_null($this->hide_fulfilled)) {
            $this->hide_fulfilled = false;
        }
        return $this->hide_fulfilled;
    }
    
    /**
     * Sets the hide_fulfilled
     * @var boolean
     */
    function setHideFulfilled($arg0) {
        $this->hide_fulfilled = (boolean)$arg0;
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
        return $this;
    }
    
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
        array_walk($this->split_id_array, function(&$value) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
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
            if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        } else if (is_string($arg0)) {
            $fulfillment = $this->getFulfillment();
            $fulfillment->setFulfillmentId($arg0);
            if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        } else if ($arg0 instanceof \MongoId) {
            $fulfillment = $this->getFulfillment();
            $fulfillment->setFulfillmentId($arg0);
            if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        }
        return $this;
    }
    
	/**
	 * Queries all records from a queue
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (count($this->getSplitIdArray()) > 0) {
			$criteria['split.split_id'] = array('$in' => $this->getSplitIdArray());
		}
		if (count($this->getOfferIdArray()) > 0) {
		    $criteria['lead.offer.offer_id'] = array('$in' => $this->getOfferIdArray());
		}
		if (count($this->getDispositionArray()) > 0) {
		    $criteria['disposition'] = array('$in' => $this->getDispositionArray());
		}
		if ($this->getHideCatchAll()) {
		    $criteria['is_catch_all'] = false;
		} else {
		    $criteria['is_catch_all'] = true;
		}
		if (trim($this->getKeywords()) != '') {
		    $criteria['$or'] = array(
                array('lead.lead_id' => new \MongoRegex('/' . trim($this->getKeywords()) . '/')),
		        array('lead.lead_name' =>  new \MongoRegex('/' . trim($this->getKeywords()) . '/i'))
		    );
		}
		return parent::queryAll($criteria, $hydrate);
	}
}
