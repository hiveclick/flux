<?php
namespace Flux;

use Mojavi\Form\MojaviForm;

class LeadEvent extends MojaviForm {
    
    protected $t; // timestamp
    protected $v; // value
    protected $n; // event_id
    protected $o; // offer_id
    protected $c; // client_id
    protected $p; // payout
    protected $r; // revenue
    
    private $h; // Datafield name
    private $m; // Event insertion action
    
    private $data_field;
    private $client;
    private $offer;
    
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
    	if (is_null($this->n)) {
    		$this->n = 0;
    	}
    	return $this->n;
    }
    
    /**
     * Sets the n
     * @var integer
     */
    function setN($arg0) {
    	$this->n = $arg0;
    	$this->addModifiedColumn("n");
    	return $this;
    }
    
    /**
     * Returns the o
     * @return integer
     */
    function getO() {
    	if (is_null($this->o)) {
    		$this->o = 0;
    	}
    	return $this->o;
    }
    
    /**
     * Sets the o
     * @var integer
     */
    function setO($arg0) {
    	$this->o = (int)$arg0;
    	$this->addModifiedColumn("o");
    	return $this;
    }
    
    /**
     * Returns the c
     * @return integer
     */
    function getC() {
    	if (is_null($this->c)) {
    		$this->c = 0;
    	}
    	return $this->c;
    }
    
    /**
     * Sets the c
     * @var integer
     */
    function setC($arg0) {
    	$this->c = (int)$arg0;
    	$this->addModifiedColumn("c");
    	return $this;
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
     * Returns the offer_id
     * @return integer
     */
    function getOfferId() {
    	return $this->getO();
    }
    
    /**
     * Sets the offer_id
     * @var integer
     */
    function setOfferId($arg0) {
    	return $this->setO($arg0);
    }
    
    /**
     * Returns the client_id
     * @return integer
     */
    function getClientId() {
    	return $this->getC();
    }
    
    /**
     * Sets the client_id
     * @var integer
     */
    function setClientId($arg0) {
    	return $this->setC($arg0);
    }
    
    /**
     * Returns the event_id
     * @return string
     */
    function getEventId() {
    	return $this->getN();
    }
    
    /**
     * Sets the event_id
     * @var string
     */
    function setEventId($arg0) {
    	return $this->setN($arg0);
    }
    
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
     * Returns the data_field
     * @return \Flux\DataField
     */
    function getDataField() {
    	if (is_null($this->data_field)) {
    		$this->data_field = new \Flux\DataField();
    		$this->data_field->setKeyName($this->getN());
    		$this->data_field->queryByKeyName();
    	}
    	return $this->data_field;
    }
    
    /**
     * Returns the offer
     * @return \Flux\Offer
     */
    function getOffer() {
    	if (is_null($this->offer)) {
    		$this->offer = new \Flux\Offer();
    		$this->offer->setId($this->getOfferId());
    		$this->offer->query();
    	}
    	return $this->offer;
    }
    
    /**
     * Returns the client
     * @return \Flux\Client
     */
    function getClient() {
    	if (is_null($this->client)) {
    		$this->client = new \Flux\Client();
    		$this->client->setId($this->getClientId());
    		$this->client->query();
    	}
    	return $this->client;
    }
}