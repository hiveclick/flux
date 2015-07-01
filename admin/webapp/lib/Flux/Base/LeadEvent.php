<?php
namespace Flux\Base;

use Mojavi\Form\MojaviForm;

class LeadEvent extends MojaviForm {
    
    protected $t; // timestamp
    protected $v; // value
    protected $p; // payout
    protected $r; // revenue
    
    private $h; // Datafield name
    private $m; // Event insertion action
    
    protected $data_field;
    protected $client;
    protected $offer;
    
    /**
     * Constructs a new event
     */
    function __construct() {
        $this->setT(new \MongoDate());
    }
    
    /**
     * Returns the t
     * @return integer
     */
    function getT() {
    	if (is_null($this->t)) {
    		$this->t = 0;
    	}
    	return $this->t;
    }
    
    /**
     * Sets the t
     * @var integer
     */
    function setT($arg0) {
    	$this->t = $arg0;
    	$this->addModifiedColumn("t");
    	return $this;
    }
    
    /**
     * Returns the v
     * @return string
     */
    function getV() {
    	if (is_null($this->v)) {
    		$this->v = "";
    	}
    	return $this->v;
    }
    
    /**
     * Sets the v
     * @var string
     */
    function setV($arg0) {
    	$this->v = $arg0;
    	$this->addModifiedColumn("v");
    	return $this;
    }
    
    /**
     * Returns the n
     * @return integer
     */
    function getN() {
    	return $this->getDataField()->getDataFieldId();
    }
    
    /**
     * Sets the n
     * @var integer
     */
    function setN($arg0) {
    	return $this->getDataField($arg0);
    }
    
    /**
     * Returns the o
     * @return integer
     */
    function getO() {
    	return $this->getOffer()->getOfferId();
    }
    
    /**
     * Sets the o
     * @var integer
     */
    function setO($arg0) {
    	return $this->setOffer($arg0);
    }
    
    /**
     * Returns the c
     * @return integer
     */
    function getC() {
    	return $this->getClient()->getClientId();
    }
    
    /**
     * Sets the c
     * @var integer
     */
    function setC($arg0) {
    	return $this->setClient($arg0);
    }
    
    /**
     * Returns the p
     * @return float
     */
    function getP() {
    	if (is_null($this->p)) {
    		$this->p = 0;
    	}
    	return $this->p;
    }
    
    /**
     * Sets the p
     * @var float
     */
    function setP($arg0) {
    	$this->p = (float)$arg0;
    	$this->addModifiedColumn("p");
    	return $this;
    }
    
    /**
     * Returns the r
     * @return float
     */
    function getR() {
    	if (is_null($this->r)) {
    		$this->r = 0;
    	}
    	return $this->r;
    }
    
    /**
     * Sets the r
     * @var float
     */
    function setR($arg0) {
    	$this->r = (float)$arg0;
    	$this->addModifiedColumn("r");
    	return $this;
    }
    
    // +------------------------------------------------------------------------+
    // | EXTRA FUNCTIONS USED FOR TRACKING  									|
    // +------------------------------------------------------------------------+
    /**
     * Returns the h
     * @return integer
     */
    function getH() {
    	if (is_null($this->h)) {
    		$this->h = 0;
    	}
    	return $this->h;
    }
    
    /**
     * Sets the h
     * @var intger
     */
    function setH($arg0) {
    	$this->h = (int)$arg0;
    	$this->addModifiedColumn("h");
    	return $this;
    }
    
    /**
     * Returns the m
     * @return integer
     */
    function getM() {
    	if (is_null($this->m)) {
    		$this->m = 0;
    	}
    	return $this->m;
    }
    
    /**
     * Sets the m
     * @var integer
     */
    function setM($arg0) {
    	$this->m = $arg0;
    	$this->addModifiedColumn("m");
    	return $this;
    }    
    
    // +------------------------------------------------------------------------+
    // | HELPER METHODS															|
    // +------------------------------------------------------------------------+
    
    /**
     * Returns the value
     * @return string
     */
    function getValue() {
    	return $this->getV();
    }
    
    /**
     * Sets the value
     * @var string
     */
    function setValue($arg0) {
    	return $this->setV($arg0);
    }
    
    /**
     * Returns the created_time
     * @return integer
     */
    function getCreatedTime() {
    	return $this->getT();
    }
    
    /**
     * Sets the created_time
     * @var integer
     */
    function setCreatedTime($arg0) {
    	return $this->setT($arg0);
    }
    
    /**
     * Returns the payout
     * @return float
     */
    function getPayout() {
    	return $this->getP();
    }
    
    /**
     * Sets the payout
     * @var float
     */
    function setPayout($arg0) {
    	return $this->setP($arg0);
    }
    
    /**
     * Returns the revenue
     * @return float
     */
    function getRevenue() {
    	return $this->getR();
    }
    
    /**
     * Sets the revenue
     * @var float
     */
    function setRevenue($arg0) {
    	return $this->setR($arg0);
    }
    
	/**
	 * Returns the this
	 * @return \Flux\Link\DataField
	 */
	function getDataField() {
		if (is_null($this->data_field)) {
			$this->data_field = new \Flux\Link\DataField();
		}
		return $this->data_field;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setDataField($arg0) {
		if (is_array($arg0)) {
			$data_field = $this->getDataField();
			$data_field->populate($arg0);
			if ($data_field->getDataFieldKeyName() == '' && \MongoId::isValid($data_field->getDataFieldId())) {
				$data_field->setDataFieldKeyName($data_field->getDataField()->getKeyName());
			}
			if ($data_field->getDataFieldKeyName() != '' && is_null($data_field->getDataFieldId())) {
				$data_field->setDataFieldId($data_field->getDataField()->getId());
			}
			if ($data_field->getDataFieldKeyName() != '' && $data_field->getDataFieldName() == "") {
				$data_field->setDataFieldName($data_field->getDataField()->getName());
			}
			$this->data_field = $data_field;
		} else if (is_string($arg0)) {
			$data_field = $this->getDataField();
			$data_field->setDataFieldKeyName($arg0);
			if ($data_field->getDataFieldKeyName() != '' && is_null($data_field->getDataFieldId())) {
				$data_field->setDataFieldId($data_field->getDataField()->getId());
			}
			if ($data_field->getDataFieldKeyName() != '' && $data_field->getDataFieldName() == "") {
				$data_field->setDataFieldName($data_field->getDataField()->getName());
			}
			$this->data_field = $data_field;
		} else if ($arg0 instanceof \MongoId) {
		    $data_field = $this->getDataField();
		    $data_field->setDataFieldId($arg0);
		    if ($data_field->getDataFieldKeyName() == '' && \MongoId::isValid($data_field->getDataFieldId())) {
		        $data_field->setDataFieldKeyName($data_field->getDataField()->getKeyName());
		    }
		    if ($data_field->getDataFieldKeyName() != '' && is_null($data_field->getDataFieldId())) {
		        $data_field->setDataFieldId($data_field->getDataField()->getId());
		    }
		    if ($data_field->getDataFieldKeyName() != '' && $data_field->getDataFieldName() == "") {
		        $data_field->setDataFieldName($data_field->getDataField()->getName());
		    }
		    $this->data_field = $data_field;
		}
		$this->addModifiedColumn('data_field');
		return $this;
	}	
    
	/**
	 * Returns the this
	 * @return \Flux\Link\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Flux\Link\Offer();
		}
		return $this->offer;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setOffer($arg0) {
		if (is_array($arg0)) {
			$offer = $this->getOffer();
			$offer->populate($arg0);
			if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if (is_string($arg0)) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if ($arg0 instanceof \MongoId) {
		    $offer = $this->getOffer();
		    $offer->setOfferId($arg0);
		    if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
		        $offer->setOfferName($offer->getOffer()->getName());
		    }
		    $this->offer = $offer;
		}
		$this->addModifiedColumn('offer');
		return $this;
	}	
    
	/**
	 * Returns the this
	 * @return \Flux\Link\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Link\Client();
		}
		return $this->client;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setClient($arg0) {
		if (is_array($arg0)) {
			$client = $this->getClient();
			$client->populate($arg0);
			if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if (is_string($arg0)) {
			$client = $this->getClient();
			$client->setClientId($arg0);
			if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if ($arg0 instanceof \MongoId) {
		    $client = $this->getClient();
		    $client->setClientId($arg0);
		    if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
		        $client->setClientName($client->getClient()->getName());
		    }
		    $this->client = $client;
		}
		$this->addModifiedColumn('client');
		return $this;
	}
}