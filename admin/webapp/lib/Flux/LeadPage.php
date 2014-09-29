<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class LeadPage extends MongoForm {

    protected $lead_id;
    protected $offer_id;
    protected $offer_page_id;
    protected $page;
    protected $domain;
    protected $folder;
    protected $cookie;
    protected $href;
    protected $entrance_time;
    protected $time_on_page;
    protected $exit_time;
    protected $load_count;
    
    private $offer;
    private $offer_page;
    private $lead;
    
    /**
     * Constructs new user
     * @return void
     */
    function __construct() {
    	$this->setCollectionName('lead_page');
    	$this->setDbName('lead');
    	$this->setIdType(self::ID_TYPE_MONGO);
    }
    
    /**
     * Returns the lead_id
     * @return integer
     */
    function getLeadId() {
    	if (is_null($this->lead_id)) {
    		$this->lead_id = "";
    	}
    	return $this->lead_id;
    }
    
    /**
     * Sets the lead_id
     * @var integer
     */
    function setLeadId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
    	   $this->lead_id = new \MongoId($arg0);
    	   $this->addModifiedColumn("lead_id");
        } else if ($arg0 instanceof \MongoId) {
            $this->lead_id = $arg0;
            $this->addModifiedColumn("lead_id");
        }
    	return $this;
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
    	$this->offer_id = (int)$arg0;
    	$this->addModifiedColumn("offer_id");
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
    	$this->offer_page_id = (int)$arg0;
    	$this->addModifiedColumn("offer_page_id");
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
     * @return array
     */
    function getCookie() {
    	if (is_null($this->cookie)) {
    		$this->cookie = array();
    	}
    	return $this->cookie;
    }
    
    /**
     * Sets the cookie
     * @var array
     */
    function setCookie($arg0) {
        $this->cookie = $arg0;
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
     * Returns the entrance_time
     * @return MongoDate
     */
    function getEntranceTime() {
    	if (is_null($this->entrance_time)) {
    		$this->entrance_time = new \MongoDate();
    	}
    	return $this->entrance_time;
    }
    
    /**
     * Sets the entrance_time
     * @var MongoDate
     */
    function setEntranceTime($arg0) {
    	$this->entrance_time = $arg0;
    	$this->addModifiedColumn("entrance_time");
    	return $this;
    }
    
    /**
     * Returns the time_on_page
     * @return float
     */
    function getTimeOnPage() {
    	if (is_null($this->time_on_page)) {
    		$this->time_on_page = 0;
    	}
    	return $this->time_on_page;
    }
    
    /**
     * Sets the time_on_page
     * @var float
     */
    function setTimeOnPage($arg0) {
    	$this->time_on_page = $arg0;
    	$this->addModifiedColumn("time_on_page");
    	return $this;
    }
    
    /**
     * Returns the exit_time
     * @return MongoDate
     */
    function getExitTime() {
    	if (is_null($this->exit_time)) {
    		$this->exit_time = new \MongoDate();
    	}
    	return $this->exit_time;
    }
    
    /**
     * Sets the exit_time
     * @var MongoDate
     */
    function setExitTime($arg0) {
    	$this->exit_time = $arg0;
    	$this->addModifiedColumn("exit_time");
    	return $this;
    }
    
    /**
     * Returns the load_count
     * @return integer
     */
    function getLoadCount() {
    	if (is_null($this->load_count)) {
    		$this->load_count = 0;
    	}
    	return $this->load_count;
    }
    
    /**
     * Sets the load_count
     * @var integer
     */
    function setLoadCount($arg0) {
    	$this->load_count = $arg0;
    	$this->addModifiedColumn("load_count");
    	return $this;
    }
    
    /**
     * Returns the offer
     * @return \Flux\Offer
     */
    function getOffer() {
    	if (is_null($this->offer)) {
    		$this->offer = new Offer();
    		$this->offer->setId($this->getOfferId());
    		$this->offer->query();
    	}
    	return $this->offer;
    }
    
    /**
     * Returns the lead
     * @return \Flux\Lead
     */
    function getLead() {
    	if (is_null($this->lead)) {
    		$this->lead = new Lead();
    		$this->lead->setId($this->getLeadId());
    		$this->lead->query();
    	}
    	return $this->lead;
    }
    
    /**
     * Returns the offer_page
     * @return \Flux\OfferPage
     */
    function getOfferPage() {
    	if (is_null($this->offer_page)) {
    		$this->offer_page = new OfferPage();
    		$this->offer_page->setId($this->getOfferPageId());
    		$this->offer_page->query();
    	}
    	return $this->offer_page;
    }
    
    /**
     * Returns the pages based on the criteria
     * @return Flux\LeadPage
     */
    function queryAll(array $criteria = array(), $hydrate = true) {
    	$criteria['lead_id'] = (string)$this->getLeadId();
    	return parent::queryAll($criteria, $hydrate);
    }
    
    /**
     * Ensures that the mongo indexes are set (should be called once)
     * @return boolean
     */
    public static function ensureIndexes() {
        $lead_page = new self();
    	$lead_page->getCollection()->ensureIndex(array('lead_id' => 1, 'offer_page_id' => 1), array('background' => true, 'unique' => true));
    	$lead_page->getCollection()->ensureIndex(array('offer_id' => 1), array('background' => true));
    	$lead_page->getCollection()->ensureIndex(array('offer_page_id' => 1), array('background' => true));
    	$lead_page->getCollection()->ensureIndex(array('lead_id' => 1), array('background' => true));
    	return true;
    }
    
    
}