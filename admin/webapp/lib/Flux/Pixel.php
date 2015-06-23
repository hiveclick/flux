<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class Pixel extends CommonForm {

    protected $page;
    protected $domain;
    protected $folder;
    protected $cookie;
    protected $href;
    
    private $offer;
    private $lead;
    private $offer_page;
    
    /**
     * Returns the lead
     * @return \Flux\Link\Lead
     */
    function getLead() {
        if (is_null($this->lead)) {
            $this->lead = new \Flux\Link\Lead();
        }
        return $this->lead;
    }
    
    /**
     * Sets the lead
     * @var \Flux\Link\Lead
     */
    function setLead($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
            $this->lead = new \Flux\Link\Lead();
            $this->lead->setLeadId($arg0);
        }
        return $this;
    }
    
    /**
     * Returns the page
     * @return string
     */
    function getPage() {
    	if (is_null($this->page)) {
    		$this->page = "";
    	}
    	return $this->page;
    }
    
    /**
     * Sets the page
     * @var string
     */
    function setPage($arg0) {
    	$this->page = $arg0;
    	$this->addModifiedColumn("page");
    	return $this;
    }
    
    /**
     * Returns the domain
     * @return string
     */
    function getDomain() {
    	if (is_null($this->domain)) {
    		$this->domain = "";
    	}
    	return $this->domain;
    }
    
    /**
     * Sets the domain
     * @var string
     */
    function setDomain($arg0) {
    	$this->domain = $arg0;
    	$this->addModifiedColumn("domain");
    	return $this;
    }
    
    /**
     * Returns the folder
     * @return string
     */
    function getFolder() {
    	if (is_null($this->folder)) {
    		$this->folder = "";
    	}
    	return $this->folder;
    }
    
    /**
     * Sets the folder
     * @var string
     */
    function setFolder($arg0) {
    	$this->folder = $arg0;
    	$this->addModifiedColumn("folder");
    	return $this;
    }
    
    /**
     * Returns the cookie
     * @return string
     */
    function getCookie() {
    	if (is_null($this->cookie)) {
    		$this->cookie = "";
    	}
    	return $this->cookie;
    }
    
    /**
     * Sets the cookie
     * @var string
     */
    function setCookie($arg0) {
        $tmp = base64_decode($arg0);
        $tmp = \Mojavi\Util\StringTools::LzwDecompress($tmp);
        $tmp = urldecode($tmp);
        $this->cookie = $tmp;
    	$this->addModifiedColumn("cookie");
    	return $this;
    }
    
    /**
     * Returns the href
     * @return string
     */
    function getHref() {
    	if (is_null($this->href)) {
    		$this->href = "";
    	}
    	return $this->href;
    }
    
    /**
     * Sets the href
     * @var string
     */
    function setHref($arg0) {
    	$this->href = $arg0;
    	$this->addModifiedColumn("href");
    	return $this;
    }
    
    /**
     * Returns the offer
     * @return \Flux\Link\Offer
     */
    function getOffer() {
        if (is_null($this->offer)) {
            $this->offer = new \Flux\Link\Offer();
            $offer_found = false;
            
            // Try finding the offer from the lead
            if (\MongoId::isValid($this->getLead()->getLeadId()) && \MongoId::isValid($this->getLead()->getLead()->getTracking()->getOffer()->getOfferId())) {
                $this->offer->setOfferId($this->getLead()->getLead()->getTracking()->getOffer()->getOfferId());
                if (\MongoId::isValid($this->offer->getOfferId()) && $this->offer->getOfferName() == '') {
                    $this->offer->setOfferName($this->offer->getOffer()->getName());
                }
                $offer_found = true;
            }
            
            // Try to find the offer using the domain name and folder name
            if (!$offer_found) {
                if ($this->getDomain() != '') {
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from domain: " . $this->getDomain() . '/' . $this->getFolder());
                	$offer = new \Flux\Offer();
                    $offer->setFolderName($this->getFolder());
                    $offer->setDomainName($this->getDomain());
                    $offers = $offer->queryAll();
                    if (count($offers) == 0) {
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from domain: " . $this->getDomain() . '/*');
                    	$offer->setFolderName('');
                    	$offer->setDomainName($this->getDomain());
                    	$offers = $offer->queryAll();
                    }
                    if (count($offers) == 0) {
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Cannot find offer from " . $this->getDomain() . '/' . $this->getFolder() . ' or ' . $this->getDomain() . '/*');
                    	$offer_found = false;
                    } else if (count($offers) == 1) {
                        // We found only one offer, so use that one
                        $offer = array_shift($offers);
                        $this->offer->setOfferId($offer->getId());
                        if (\MongoId::isValid($this->offer->getOfferId()) && $this->offer->getOfferName() == '') {
                            $this->offer->setOfferName($this->offer->getOffer()->getName());
                        }
                        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Found 1 offer (" . $this->offer->getOfferName() . "), using it");
                        
                        $offer_found = true;
                    } else {
                    	// We found more than one offer, so use the first one
                        $offer = array_shift($offers);
                        $this->offer->setOfferId($offer->getId());
                        if (\MongoId::isValid($this->offer->getOfferId()) && $this->offer->getOfferName() == '') {
                            $this->offer->setOfferName($this->offer->getOffer()->getName());
                        }
                        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Found " . count($offers) . ", using the first one (" . $this->offer->getOfferName() . ")");
                    	$offer_found = true;
                    }
                } else {
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Domain (" . $this->getDomain() . ") is blank, so we can't find an offer using domain");
                }
            }
        }
        return $this->offer;
    }
}