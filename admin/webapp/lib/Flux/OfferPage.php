<?php
namespace Flux;

class OfferPage extends Base\OfferPage {
	    
    private $server_id; // Used to load a page source from a remote server
	private $offer_page_id_array; // Used to save multiple pages
	private $offer_id_array; // Used to search
	
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
		$this->server_id = $arg0;
		$this->addModifiedColumn("server_id");
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
		$this->offer_id_array = $arg0;
		array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		return $this;
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
		array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		return $this;
	}
	
	/**
	 * Returns the offer based on the criteria
	 * @return Flux\OfferPage
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if ($this->getPageName() != '') {
			$criteria['page_name'] = $this->getPageName();
		}
		if (count($this->getOfferIdArray()) > 0) {
			$criteria['offer.offer_id'] = array('$in' => $this->getOfferIdArray());
		}
		return parent::queryAll($criteria, $hydrate, $fields);
	}

	/**
	 * Finds an offer by the folder name
	 * @return \Flux\OfferPage
	 */
	function queryByPageName() {
		return parent::query(array('offer.offer_id' => $this->getOffer()->getOfferId(), 'page_name' => $this->getPageName()), false);
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
		parent::updateMultiple(array('offer.offer_id' => $this->getOffer()->getOfferId()), array('$set' => array('priority' => 0)));
		foreach ($this->getOfferPageIdArray() as $key => $offer_page_id_item) {
			$offer_page = new \Flux\OfferPage();
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
		$offer_page->getCollection()->ensureIndex(array('offer.offer_id' => 1), array('background' => true));
		return true;
	}
}