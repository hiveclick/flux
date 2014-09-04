<?php
namespace Gun;

use Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class OfferPage extends MongoForm {

	protected $offer_id;
	protected $name;
	protected $page_name;
	protected $preview_url;
	protected $priority;
	protected $file_path;
	protected $description;
	protected $page_source;
	
    protected $click_today;
    protected $click_yesterday;

    protected $offer_page_flows;
    
    private $server_id; // Used to load a page source from a remote server
	private $offer_page_id_array;

	private $offer;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('offer_page');
		$this->setDbName('admin');
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
		$this->addModifiedColumn('offer_id');
		return $this;
	}
	
	/**
	 * Returns the server_id
	 * @return integer
	 */
	function getServerId() {
		if (is_null($this->server_id)) {
			$this->server_id = 0;
		}
		return $this->server_id;
	}
	
	/**
	 * Sets the server_id
	 * @var integer
	 */
	function setServerId($arg0) {
		$this->server_id = (int)$arg0;
		$this->addModifiedColumn("server_id");
		return $this;
	}

	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn('name');
		return $this;
	}

	/**
	 * Returns the preview_url
	 * @return string
	 */
	function getPreviewUrl() {
		if (is_null($this->preview_url)) {
			$this->preview_url = "";
		}
		return $this->preview_url;
	}

	/**
	 * Sets the preview_url
	 * @var string
	 */
	function setPreviewUrl($arg0) {
		$this->preview_url = $arg0;
		$this->addModifiedColumn('preview_url');
		return $this;
	}

	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}

	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn('description');
		return $this;
	}

	/**
	 * Returns the page_source
	 * @return string
	 */
	function getPageSource() {
		if (is_null($this->page_source)) {
			$this->page_source = "";
		}
		return $this->page_source;
	}
	
	/**
	 * Sets the page_source
	 * @var string
	 */
	function setPageSource($arg0) {
		$this->page_source = $arg0;
		$this->addModifiedColumn("page_source");
		return $this;
	}
	
	/**
	 * Returns the page_name
	 * @return string
	 */
	function getPageName() {
		if (is_null($this->page_name)) {
			$this->page_name = "";
		}
		return $this->page_name;
	}

	/**
	 * Sets the page_name
	 * @var string
	 */
	function setPageName($arg0) {
		$this->page_name = $arg0;
		$this->addModifiedColumn('page_name');
		return $this;
	}

	/**
	 * Returns the priority
	 * @return integer
	 */
	function getPriority() {
		if (is_null($this->priority)) {
			$this->priority = 0;
		}
		return $this->priority;
	}

	/**
	 * Sets the priority
	 * @var integer
	 */
	function setPriority($arg0) {
		$this->priority = (int)$arg0;
		$this->addModifiedColumn('priority');
		return $this;
	}

	/**
	 * Returns the file_path
	 * @return string
	 */
	function getFilePath() {
		if (is_null($this->file_path)) {
			$this->file_path = "";
		}
		return $this->file_path;
	}

	/**
	 * Sets the file_path
	 * @var string
	 */
	function setFilePath($arg0) {
		$this->file_path = $arg0;
		$this->addModifiedColumn('file_path');
		return $this;
	}

	/**
	 * Returns the offer
	 * @return \Gun\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Gun\Offer();
			$this->offer->setId($this->getOfferId());
			$this->offer->query();
		}
		return $this->offer;
	}

	/**
	 * Returns the offer_page_id_array
	 * @return array
	 */
	function getOfferPageIdArray() {
		if (is_null($this->offer_page_id_array)) {
			$this->offer_page_id_array = array();
		}
		return $this->offer_page_id_array;
	}

	/**
	 * Sets the offer_page_id_array
	 * @var array
	 */
	function setOfferPageIdArray($arg0) {
		$this->offer_page_id_array = $arg0;
		return $this;
	}
	
	/**
	 * Returns the offer_page_flows
	 * @return \Gun\OfferPageFlow
	 */
	function getOfferPageFlows() {
		if (is_null($this->offer_page_flows)) {
			$this->offer_page_flows = array();
		}
		return $this->offer_page_flows;
	}
	
	/**
	 * Sets the offer_page_flows
	 * @var \Gun\OfferPageFlow
	 */
	function setOfferPageFlows($arg0) {
	    $this->offer_page_flows = $arg0;
	    array_walk($this->offer_page_flows, function(&$value, $key) {
			if (is_array($value)) {
			     $item = new \Gun\OfferPageFlow();
			     $item->populate($value);
			     $value = $item;   
			}
	    });
		$this->addModifiedColumn("offer_page_flows");
		return $this;
	}
	
	/**
	 * Returns the click_today
	 * @return integer
	 */
	function getClickToday() {
		if (is_null($this->click_today)) {
			$this->click_today = 0;
		}
		return $this->click_today;
	}
	
	/**
	 * Sets the click_today
	 * @var integer
	 */
	function setClickToday($arg0) {
		$this->click_today = $arg0;
		$this->addModifiedColumn("click_today");
		return $this;
	}
	
	/**
	 * Returns the click_yesterday
	 * @return integer
	 */
	function getClickYesterday() {
		if (is_null($this->click_yesterday)) {
			$this->click_yesterday = 0;
		}
		return $this->click_yesterday;
	}
	
	/**
	 * Sets the click_yesterday
	 * @var integer
	 */
	function setClickYesterday($arg0) {
		$this->click_yesterday = $arg0;
		$this->addModifiedColumn("click_yesterday");
		return $this;
	}
	
	/**
	 * Returns the offer based on the criteria
	 * @return Gun\OfferPage
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getPageName() != '') {
			$criteria['page_name'] = $this->getPageName();
		}
		if ($this->getOfferId() > 0) {
			$criteria['offer_id'] = $this->getOfferId();
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Finds an offer by the folder name
	 * @return \Gun\OfferPage
	 */
	function queryByPageName() {
		return parent::query(array('offer_id' => $this->getOfferId(), 'page_name' => $this->getPageName()), false);
	}
	
	/**
	 * Flushes and updates the offer page flow
	 * @return integer
	 */
	function flushFlow() {
	    return true;
	    $flows = array();
	    foreach ($this->getOfferPageFlows() as $page_flow) {
	        $flow = $page_flow->toArray(true);
	        if (isset($flow['filter_conditions'])) { unset($flow['filter_conditions']); }
	        $flows[] = $flow;
	    }

	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: Updating flows: " . $this->getId());
	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($flows, true));
	    //parent::update();
	    parent::updateMultiple(array('_id' => $this->getId()), array('$set' => array('offer_page_flows' => array())));
	    return true;
	}

	/**
	 * Updates the priority of multiple events
	 * @return integer
	 */
	function updateMultiplePriority() {
		// Reset the priorities first
		parent::updateMultiple(array('offer_id' => $this->getOfferId()), array('$set' => array('priority' => 0)));
		foreach ($this->getOfferPageIdArray() as $key => $offer_page_id_item) {
			$offer_page = new \Gun\OfferPage();
			$offer_page->setId($offer_page_id_item);
			$offer_page->setPriority((((int)$key) + 1));
			$offer_page->addModifiedColumn('priority');
			$offer_page->update();
		}
		return true;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$offer_page = new self();
		$offer_page->getCollection()->ensureIndex(array('offer_id' => 1), array('background' => true));
		return true;
	}
}