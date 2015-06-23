<?php
namespace Flux;

use Mojavi\Form\MojaviForm;

class OfferPagePath extends MojaviForm {
    
	protected $position;
    protected $path_offer_id;
    protected $offer_asset_id;
    protected $destination_url;
    
    private $offer_id;
    private $offer_page;
    
    /**
     * Returns the position
     * @return integer
     */
    function getPosition() {
    	if (is_null($this->position)) {
    		$this->position = 1;
    	}
    	return $this->position;
    }
    
    /**
     * Sets the position
     * @var integer
     */
    function setPosition($arg0) {
    	$this->position = (int)$arg0;
    	$this->addModifiedColumn("position");
    	return $this;
    }
    
    /**
     * Returns the offer_page_id
     * @return integer
     */
    function getOfferPageId() {
    	if (is_null($this->offer_page_id)) {
    		$this->offer_page_id = 0;
    	}
    	return $this->offer_page_id;
    }
    
    /**
     * Sets the offer_page_id
     * @var integer
     */
    function setOfferPageId($arg0) {
    	$this->offer_page_id = $arg0;
    	$this->addModifiedColumn("offer_page_id");
    	return $this;
    }
    
    /**
     * Returns the offer_page
     * @return \Flux\OfferPage
     */
    function getOfferPage() {
    	if (is_null($this->offer_page)) {
    		$this->offer_page = new \Flux\OfferPage();
    		$this->offer_page->setId($this->getOfferPageId());
    		$this->offer_page->query();
    	}
    	return $this->offer_page;
    }
    
    /**
     * Returns the offer_id
     * @return integer
     */
    function getOfferId() {
    	if (is_null($this->offer_id)) {
    		$this->offer_id = 0;
    	}
    	return $this->offer_id;
    }
    
    /**
     * Sets the offer_id
     * @var integer
     */
    function setOfferId($arg0) {
    	$this->offer_id = $arg0;
    	$this->addModifiedColumn("offer_id");
    	return $this;
    }
    
    /**
     * Returns the offer_asset_id
     * @return integer
     */
    function getOfferAssetId() {
    	if (is_null($this->offer_asset_id)) {
    		$this->offer_asset_id = 0;
    	}
    	return $this->offer_asset_id;
    }
    
    /**
     * Sets the offer_asset_id
     * @var integer
     */
    function setOfferAssetId($arg0) {
    	$this->offer_asset_id = $arg0;
    	$this->addModifiedColumn("offer_asset_id");
    	return $this;
    }
    
    /**
     * Returns the destination_url
     * @return string
     */
    function getDestinationUrl() {
    	if (is_null($this->destination_url)) {
    		$this->destination_url = "";
    	}
    	return $this->destination_url;
    }
    
    /**
     * Sets the destination_url
     * @var string
     */
    function setDestinationUrl($arg0) {
    	$this->destination_url = $arg0;
    	$this->addModifiedColumn("destination_url");
    	return $this;
    }    
}