<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class LeadPage extends MongoForm {

	protected $lead;
	protected $offer;
	protected $offer_page;
	protected $page;
	protected $domain;
	protected $folder;
	protected $cookies;
	protected $href;
	protected $entrance_time;
	protected $time_on_page;
	protected $exit_time;
	protected $load_count;
	
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
	 * Returns the cookies
	 * @return array
	 */
	function getCookies() {
		if (is_null($this->cookies)) {
			$this->cookies = array();
		}
		return $this->cookies;
	}
	
	/**
	 * Sets the cookies
	 * @var array
	 */
	function setCookies($arg0) {
		$this->cookies = $arg0;
		$this->addModifiedColumn("cookies");
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
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if (is_string($arg0)) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if ($arg0 instanceof \MongoId) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		}
		$this->addModifiedColumn('offer');
		return $this;
	}
	
	/**
	 * Returns the this
	 * @return \Flux\Link\OfferPage
	 */
	function getOfferPage() {
		if (is_null($this->offer_page)) {
			$this->offer_page = new \Flux\Link\OfferPage();
		}
		return $this->offer_page;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setOfferPage($arg0) {
		if (is_array($arg0)) {
			$offer_page = $this->getOfferPage();
			$offer_page->populate($arg0);
			if (\MongoId::isValid($offer_page->getOfferPageId()) && $offer_page->getOfferPageName() == "") {
				$offer_page->setOfferPageName($offer_page->getOfferPage()->getName());
			}
			$this->offer_page = $offer_page;
		} else if (is_string($arg0)) {
			$offer_page = $this->getOfferPage();
			$offer_page->setOfferPageId($arg0);
			if (\MongoId::isValid($offer_page->getOfferPageId()) && $offer_page->getOfferPageName() == "") {
				$offer_page->setOfferPageName($offer_page->getOfferPage()->getName());
			}
			$this->offer_page = $offer_page;
		} else if ($arg0 instanceof \MongoId) {
			$offer_page = $this->getOfferPage();
			$offer_page->setOfferPageId($arg0);
			if (\MongoId::isValid($offer_page->getOfferPageId()) && $offer_page->getOfferPageName() == "") {
				$offer_page->setOfferPageName($offer_page->getOfferPage()->getName());
			}
			$this->offer_page = $offer_page;
		}
		$this->addModifiedColumn('offer_page');
		return $this;
	}
	
	/**
	 * Returns the this
	 * @return \Flux\Link\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Link\Lead();
		}
		return $this->lead;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setLead($arg0) {
		if (is_array($arg0)) {
			$lead = $this->getLead();
			$lead->populate($arg0);
			$this->lead = $lead;
		} else if ($arg0 instanceof \MongoId) {
			$lead = $this->getLead();
			$lead->setLeadId($arg0);
			$this->lead = $lead;
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$lead = $this->getLead();
			$lead->setLeadId($arg0);
			$this->lead = $lead;
		}
		$this->addModifiedColumn('lead');
		return $this;
	}
	
	/**
	 * Creates indexes for this collection
	 * @return boolean
	 */
	static function ensureIndexes() {
		$exception = null;
		$indexes = array();
		$indexes[] = array('idx' => array('lead._id' => 1, 'offer_page._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array('offer._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array('offer_page._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array('lead._id' => 1), 'options' => array('background' => true));
	
		foreach ($indexes as $index) {
			try {
				$collection = new self();
				$collection->getCollection()->createIndex($index['idx'], $index['options']);
			} catch (\Exception $e) {
				$exception = $e;
			}
		}
	
		// Enable sharding as well
		/*
		$shards[] = array('idx' => array('_id' => 1));
		foreach ($shards as $shard) {
		try {
		$collection = new self();
	
		$shardDB = \Mojavi\Controller\Controller::getInstance()->getContext()->getDatabaseManager()->getDatabase($collection->getDbName())->getParameter('database');
		$shardCollection =  $shardDB . '.' . $collection->getCollectionName();
	
		$r = $collection->getConnection('mongo_admin')->command(array('shardCollection' => $shardCollection, 'key' => $shard['idx']));
	
		if (isset($r['ok']) && $r['ok'] == 0 && isset($r['errmsg'])) {
		throw new \Exception($r['errmsg']);
		}
		} catch (\Exception $e) {
		if (strpos($e->getMessage(), 'already sharded') === false && strpos($e->getMessage(), 'no such command') === false) {
		$exception = $e;
		}
		}
		}
		*/
		if (!is_null($exception)) { throw $exception; }
	}	
}