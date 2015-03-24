<?php
namespace Flux;

class SplitQueue extends Base\SplitQueue {

    private $offer_id_array;
    private $split_id_array;
    private $fulfillment;
    
    protected $test;
    
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
        } else if (is_int($arg0)) {
            $this->offer_id_array = array($arg0);
        }
        array_walk($this->offer_id_array, function(&$value) { $value = (int)trim($value); });
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
        } else if (is_int($arg0)) {
            $this->split_id_array = array($arg0);
        }
        array_walk($this->split_id_array, function(&$value) { $value = (int)trim($value); });
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
            if ($fulfillment->getFulfillmentId() > 0 && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        } else if (is_string($arg0) || is_int($arg0)) {
            $fulfillment = $this->getFulfillment();
            $fulfillment->setId($arg0);
            if ($fulfillment->getFulfillmentId() > 0 && $fulfillment->getFulfillmentName() == '') {
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
		if (trim($this->getKeywords()) != '') {
		    $criteria['$or'] = array(
                array('lead.lead_id' => new \MongoRegex('/' . trim($this->getKeywords()) . '/')),
		        array('lead.lead_name' =>  new \MongoRegex('/' . trim($this->getKeywords()) . '/i'))
		    );
		}
		return parent::queryAll($criteria, $hydrate);
	}
}
