<?php
namespace Flux;

class Campaign extends Base\Campaign {

	private $offer_id_array;
	private $client_id_array;
	private $traffic_source_id_array;
	
	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::CAMPAIGN_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::CAMPAIGN_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::CAMPAIGN_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}
	
	/**
	 * Returns the traffic_source_id_array
	 * @return array
	 */
	function getTrafficSourceIdArray() {
		if (is_null($this->traffic_source_id_array)) {
			$this->traffic_source_id_array = array();
		}
		return $this->traffic_source_id_array;
	}
	
	/**
	 * Sets the traffic_source_id_array
	 * @var array
	 */
	function setTrafficSourceIdArray($arg0) {
		if (is_array($arg0)) {
			$this->traffic_source_id_array = $arg0;
			array_walk($this->traffic_source_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->traffic_source_id_array = explode(",", $arg0);
				array_walk($this->traffic_source_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			} else {
				$this->traffic_source_id_array = array($arg0);
			}
		}
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
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
			array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
				array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			} else {
				$this->offer_id_array = array($arg0);
				array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			}
		}
		return $this;
	}
	
	/**
	 * Returns the client_id_array
	 * @return array
	 */
	function getClientIdArray() {
		if (is_null($this->client_id_array)) {
			$this->client_id_array = array();
		}
		return $this->client_id_array;
	}
	
	/**
	 * Sets the client_id_array
	 * @var array
	 */
	function setClientIdArray($arg0) {
		if (is_array($arg0)) {
			$this->client_id_array = $arg0;
			array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->client_id_array = explode(",", $arg0);
				array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			} else {
				$this->client_id_array = array($arg0);
				array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			}
		}
		return $this;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	/**
	 * Returns the campaign based on the campaign key
	 * @return Flux\Campaign
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		if (trim($this->getKeywords()) != '') {
			if (\MongoId::isValid(trim($this->getKeywords()))) {
				$criteria['_id'] = new \MongoId($this->getKeywords());
			} else {
				$criteria['description'] = new \MongoRegex("/" . $this->getKeywords() . "/i");
			}
		}
		if (\MongoId::isValid($this->getClient()->getId())) {
			$criteria['client._id'] = $this->getId();
		}
		if (\MongoId::isValid($this->getOffer()->getId())) {
			$criteria['offer._id'] = $this->getId();
		}
		if (count($this->getOfferIdArray()) > 0) {
			$criteria['offer._id'] = array('$in' => $this->getOfferIdArray());
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client._id'] = array('$in' => $this->getClientIdArray());
		}
		if (count($this->getTrafficSourceIdArray()) > 0) {
			$criteria['traffic_source._id'] = array('$in' => $this->getTrafficSourceIdArray());
		}
		return parent::queryAll($criteria, $fields, $hydrate, $timeout);
	}

	/**
	 * Finds all offers by client
	 * @return Flux\Campaign
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client._id' => $this->getClient()->getId()));
	}

	/**
	 * Finds all offers by offer
	 * @return Flux\Campaign
	 */
	function queryAllByOffer() {
		return $this->queryAll(array('offer._id' => $this->getOffer()->getId()));
	}
	
	/**
	 * Updates the campaign and possibly clears the caches
	 * @return integer
	 */
	function update($criteria_array = array(), $update_array = array(), $options_array = array(), $use_set_notation = false) {
		$ret_val = parent::update($criteria_array, $update_array, $options_array, $use_set_notation);
		
		// find any servers and attempte to clear the caches
		/* @todo do this in a script, so it doesn't choke the update */
// 		$server = new Server();
// 		$servers = $server->queryAll();
// 		/* @var $server \Flux\Server */
// 		foreach ($servers as $server) {
// 			$server->clearCampaignCache($this);
// 		}
		
		return $ret_val;
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$campaign = new self();
		$campaign->getCollection()->ensureIndex(array('offer._id' => 1, 'client._id' => 1), array('background' => true));
		$campaign->getCollection()->ensureIndex(array('client._id' => 1, 'offer._id' => 1), array('background' => true));
		return true;
	}
}
