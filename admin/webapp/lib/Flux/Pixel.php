<?php
namespace Flux;

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
     * @return \Flux\Offer
     */
    function getOffer() {
        if (is_null($this->offer)) {
            $this->offer = new \Flux\Offer();
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
                if (isset($json_cookie['_t']) && isset($json_cookie['_t']['_o']) && isset($json_cookie['_t']['_o']['_id']) && trim($json_cookie['_t']['_o']['_id']) != '' && intval(trim($json_cookie['_t']['_o']['_id'])) > 0) {
                	$this->offer = new \Flux\Offer();
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from _t._o._id: " . $json_cookie['_t']['_o']['_id']);
                	$this->offer->setId((int)$json_cookie['_t']['_o']['_id']);
                	$this->offer->query();
                	if ($this->offer->getId() > 0) {
                		$offer_found = true;
                	}
                }
                if (!$offer_found && isset($json_cookie['_t']) && isset($json_cookie['_t']['_offer_id']) && trim($json_cookie['_t']['_offer_id']) != '' && intval(trim($json_cookie['_t']['_offer_id'])) > 0) {
                	$this->offer = new \Flux\Offer();
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from _t._offer_id: " . $json_cookie['_t']['_offer_id']);
                	$this->offer->setId((int)$json_cookie['_t']['_offer_id']);
                	$this->offer->query();
                	if ($this->offer->getId() > 0) {
                		$offer_found = true;
                	}
                }
            }
            // Try to find the offer using the domain name and folder name
            if (!$offer_found) {
                if ($this->getDomain() != '') {
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from domain: " . $this->getDomain() . '/' . $this->getFolder());
                    $this->offer->setFolderName($this->getFolder());
                    $this->offer->setDomainName($this->getDomain());
                    $offers = $this->offer->queryAll();
                    if (count($offers) == 0) {
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching for offer from domain: " . $this->getDomain() . '/*');
                    	$this->offer->setFolderName('');
                    	$this->offer->setDomainName($this->getDomain());
                    	$offers = $this->offer->queryAll();
                    }
                    if (count($offers) == 0) {
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Cannot find offer from " . $this->getDomain() . '/' . $this->getFolder() . ' or ' . $this->getDomain() . '/*');
                    	$offer_found = false;
                    } else if (count($offers) == 1) {
                        // We found only one offer, so use that one
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Found 1 offer, using it");
                        $this->offer = array_shift($offers);
                        $offer_found = true;
                    } else {
                    	// We found more than one offer, so use the first one
                    	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Found " . count($offers) . ", using the first one");
                    	$this->offer = array_shift($offers);
                    	$offer_found = true;
                    }
                } else {
                	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Domain (" . $this->getDomain() . ") is blank, so we can't find an offer using domain");
                }
            }
        }
        return $this->offer;
    }
    
    /**
     * Returns the lead
     * @return \Flux\Lead
     */
    function getLead() {
    	if (is_null($this->lead)) {
    		$this->lead = new \Flux\Lead();
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
        			$this->lead = new \Flux\Lead();
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