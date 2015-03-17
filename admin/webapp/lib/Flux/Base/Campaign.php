<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Campaign extends MongoForm {

	const CAMPAIGN_STATUS_ACTIVE = 1;
	const CAMPAIGN_STATUS_INACTIVE = 2;
	const CAMPAIGN_STATUS_DELETED = 3;

	protected $description;
	protected $status;
	protected $offer;
	protected $client;
	protected $redirect_link;
	
	protected $daily_clicks;
	protected $daily_conversions;
	
	private $offer_id_array;
	private $client_id_array;

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
		if (parent::getId() instanceof \MongoId) {
			return parent::getId()->__toString();
		}
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
	 * Returns the redirect_link
	 * @return string
	 */
	function getRedirectUrl() {
		return $this->getRedirectLink();
	}
	
	/**
	 * Sets the redirect_link
	 * @var string
	 */
	function setRedirectUrl($arg0) {
		return $this->setRedirectLink($arg0);
	}
	
	/**
	 * Returns the redirect_link
	 * @return string
	 */
	function getRedirectLink() {
		if (is_null($this->redirect_link)) {
			$this->redirect_link = "";
		}
		return $this->redirect_link;
	}
	
	/**
	 * Sets the redirect_link
	 * @var string
	 */
	function setRedirectLink($arg0) {
		$this->redirect_link = $arg0;
		$this->addModifiedColumn("redirect_link");
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
		$this->addModifiedColumn("description");
		return $this;
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
	
	/**
	 * Returns the this
	 * @return \Flux\Link\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Link\Client();
		}
		return $this->client;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setClient($arg0) {
		if (is_array($arg0)) {
			$client = $this->getClient();
			$client->populate($arg0);
			if ($client->getClientId() > 0 && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if (is_string($arg0)) {
			$client = $this->getClient();
			$client->setClientId($arg0);
			if ($client->getClientId() > 0 && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		}
		$this->addModifiedColumn('client');
		return $this;
	}
	
	/**
	 * Returns the offer
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
			if ($offer->getOfferId() > 0 && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if (is_string($arg0)) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if ($offer->getOfferId() > 0 && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		}
		$this->addModifiedColumn('offer');
		return $this;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

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
