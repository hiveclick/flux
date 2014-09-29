<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class Campaign extends MongoForm {

	const CAMPAIGN_STATUS_ACTIVE = 1;
	const CAMPAIGN_STATUS_INACTIVE = 2;
	const CAMPAIGN_STATUS_DELETED = 3;

	protected $description;
	protected $status;
	protected $offer_id;
	protected $client_id;
	
	protected $_offer_name;
	protected $_client_name;
	protected $_status_name;
	
	protected $daily_clicks;
	protected $daily_conversions;
	
	private $offer_id_array;
	private $client_id_array;

	private $offer;
	private $client;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('campaign');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the key
	 * @return string
	 */
	function getKey() {
		return parent::getId();
	}
	
	/**
	 * Sets the key
	 * @var string
	 */
	function setKey($arg0) {
		return parent::setId($arg0);
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
		$this->addModifiedColumn("description");
		return $this;
	}

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if (is_null($this->_status_name)) {
			$this->_status_name = self::retrieveStatuses()[$this->getStatus()];
		}
		return $this->_status_name;
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::CAMPAIGN_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
		return $this;
	}

	/**
	 * Returns the _offer_name
	 * @return string
	 */
	function getOfferName() {
		if (is_null($this->_offer_name)) {
			$this->_offer_name = $this->getOffer()->getName();
		}
		return $this->_offer_name;
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
			array_walk($this->offer_id_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
				array_walk($this->offer_id_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->offer_id_array = array((int)$arg0);
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
			array_walk($this->client_id_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->client_id_array = explode(",", $arg0);
				array_walk($this->client_id_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->client_id_array = array((int)$arg0);
			}
		}
		return $this;
	}

	/**
	 * Returns the _client_name
	 * @return string
	 */
	function getClientName() {
		if (is_null($this->_client_name)) {
			$this->_client_name = $this->getClient()->getName();
		}
		return $this->_client_name;
	}

	/**
	 * Returns the client_id
	 * @return integer
	 */
	function getClientId() {
		if (is_null($this->client_id)) {
			$this->client_id = 0;
		}
		return $this->client_id;
	}

	/**
	 * Sets the client_id
	 * @var integer
	 */
	function setClientId($arg0) {
		$this->client_id = (int)$arg0;
		$this->addModifiedColumn('client_id');
		return $this;
	}
	
	/**
	 * Returns the daily_clicks
	 * @return integer
	 */
	function getDailyClicks() {
		if (is_null($this->daily_clicks)) {
			$this->daily_clicks = 0;
		}
		return $this->daily_clicks;
	}
	
	/**
	 * Sets the daily_clicks
	 * @var integer
	 */
	function setDailyClicks($arg0) {
		$this->daily_clicks = $arg0;
		$this->addModifiedColumn("daily_clicks");
		return $this;
	}
	
	/**
	 * Returns the daily_conversions
	 * @return integer
	 */
	function getDailyConversions() {
		if (is_null($this->daily_conversions)) {
			$this->daily_conversions = 0;
		}
		return $this->daily_conversions;
	}
	
	/**
	 * Sets the daily_conversions
	 * @var integer
	 */
	function setDailyConversions($arg0) {
		$this->daily_conversions = $arg0;
		$this->addModifiedColumn("daily_conversions");
		return $this;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	/**
	 * Returns the campaign based on the campaign key
	 * @return Flux\Campaign
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
	    if (trim($this->getKeywords()) != '') {
	        if (\MongoId::isValid(trim($this->getKeywords()))) {
	    	  $criteria['_id'] = new \MongoId($this->getKeywords());
	        }
	    }
		if ($this->getClientId() > 0) {
			$criteria['client_id'] = $this->getClientId();
		}
		if ($this->getOfferId() > 0) {
			$criteria['offer_id'] = $this->getOfferId();
		}
		if (count($this->getOfferIdArray()) > 0) {
			$criteria['offer_id'] = array('$in' => $this->getOfferIdArray());
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client_id'] = array('$in' => $this->getClientIdArray());
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Finds all offers by client
	 * @return Flux\Campaign
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client_id' => $this->getClientId()));
	}

	/**
	 * Finds all offers by offer
	 * @return Flux\Campaign
	 */
	function queryAllByOffer() {
		return $this->queryAll(array('offer_id' => $this->getOfferId()));
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
	 * Returns the array of campaign statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::CAMPAIGN_STATUS_ACTIVE => 'Active',
				self::CAMPAIGN_STATUS_INACTIVE => 'Inactive',
				self::CAMPAIGN_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$campaign = new self();
		$campaign->getCollection()->ensureIndex(array('offer_id' => 1, 'client_id' => 1), array('background' => true));
		$campaign->getCollection()->ensureIndex(array('client_id' => 1, 'offer_id' => 1), array('background' => true));
		return true;
	}
}
