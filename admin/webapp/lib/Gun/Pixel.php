<?php
namespace Gun;

use Mojavi\Form\MongoForm;
use Mojavi\Util\StringTools;

class Pixel extends MongoForm {

    protected $page;
    protected $domain;
    protected $folder;
    protected $cookie;
    protected $href;
    
    private $offer;
    private $lead;
    private $offer_page;
    
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
        $tmp = StringTools::LzwDecompress($tmp);
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
     * @return \Gun\Offer
     */
    function getOffer() {
        if (is_null($this->offer)) {
            $this->offer = new \Gun\Offer();
            $offer_found = false;
            $json_cookie = array();
            // Try to match the offer from the cookie
            $cookies = http_parse_cookie($this->getCookie());            
            foreach ($cookies->cookies as $key => $cookie) {
                if (strpos($cookie, '{') !== false) {
                    $json_cookie = json_decode($cookie, true);
                }
            }
            if (!empty($json_cookie)) {
                if (isset($json_cookie['_t']) && isset($json_cookie['_t']['_o']) && isset($json_cookie['_t']['_o']['_id']) && trim($json_cookie['_t']['_o']['_id']) != '') {
                	$this->offer = new \Gun\Offer();
                	$this->offer->setId((int)$json_cookie['_t']['_o']['_id']);
                	$this->offer->query();
                	$offer_found = true;
                } else if (isset($json_cookie['_t']) && isset($json_cookie['_t']['_offer_id']) && trim($json_cookie['_t']['_offer_id']) != '') {
                	$this->offer = new \Gun\Offer();
                	$this->offer->setId((int)$json_cookie['_t']['_offer_id']);
                	$this->offer->query();
                	$offer_found = true;
                }
            }
            // Try to find the offer using the domain name and folder name
            if (!$offer_found) {
                if ($this->getDomain() != '') {
                    $this->offer->setFolderName($this->getFolder());
                    $this->offer->setDomainName($this->getDomain());
                    $offers = $this->offer->queryAll();
                    if (count($offers) == 1) {
                        // We found only one offer, so use that one
                        $this->offer = array_shift($offers);
                        $offer_found = true;
                    }
                } 
            }
        }
        return $this->offer;
    }
    
    /**
     * Returns the lead
     * @return \Gun\Lead
     */
    function getLead() {
    	if (is_null($this->lead)) {
    		$this->lead = new \Gun\Lead();
    		$lead_found = false;
    		// Try to match the offer from the cookie
    		$cookies = http_parse_cookie($this->getCookie());            
            foreach ($cookies->cookies as $key => $cookie) {
                if (strpos($cookie, '{') !== false) {
                    $json_cookie = json_decode($cookie, true);
                }
            }
            if (!empty($json_cookie)) {
        		if (isset($json_cookie['_id']) && trim($json_cookie['_id']) != '') {
        			$this->lead = new \Gun\Lead();
        			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "FINDING LEAD BY ID: " . $json_cookie['_id']);
        			$this->lead->setId($json_cookie['_id']);
        			$this->lead->query();
        			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Lead found by id: " . var_export($this->lead->getId(), true));
        			$lead_found = true;
        		} else {
        		    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Cannot find lead by cookie: " . var_export($cookies, true));
        		}
            } else {
    		    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Cannot find lead by cookie: " . var_export($cookies, true));
    		}
    	}
    	return $this->lead;
    }
    
    
    
}