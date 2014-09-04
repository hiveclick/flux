<?php
namespace Gun;

use Mojavi\Form\MongoForm;

class Client extends MongoForm {

	const CLIENT_STATUS_ACTIVE = 1;
	const CLIENT_STATUS_INACTIVE = 2;
	const CLIENT_STATUS_DELETED = 3;

	const CLIENT_TYPE_PRIMARY_ADMIN = 1;
	const CLIENT_TYPE_SECONDARY_ADMIN = 2;
	const CLIENT_TYPE_AFFILIATE = 3;

	protected $name;
	protected $email;
	protected $status;
	protected $pixel;
	protected $scrub;
	protected $reference_id;
	protected $client_type;

	protected $_status_name;

	private $users;
	private $advertiser_offers;
	private $publisher_campaigns;
	private $client_exports;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('client');
		$this->setDbName('admin');
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
	 * Returns the email
	 * @return string
	 */
	function getEmail() {
		if (is_null($this->email)) {
			$this->email = "";
		}
		return $this->email;
	}

	/**
	 * Sets the email
	 * @var string
	 */
	function setEmail($arg0) {
		$this->email = $arg0;
		$this->addModifiedColumn('email');
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
			$this->status = self::CLIENT_STATUS_ACTIVE;
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
	 * Returns the pixel
	 * @return string
	 */
	function getPixel() {
		if (is_null($this->pixel)) {
			$this->pixel = "";
		}
		return $this->pixel;
	}

	/**
	 * Sets the pixel
	 * @var string
	 */
	function setPixel($arg0) {
		$this->pixel = $arg0;
		$this->addModifiedColumn('pixel');
		return $this;
	}

	/**
	 * Returns the scrub
	 * @return float
	 */
	function getScrub() {
		if (is_null($this->scrub)) {
			$this->scrub = 0.0;
		}
		return $this->scrub;
	}

	/**
	 * Sets the scrub
	 * @var float
	 */
	function setScrub($arg0) {
		$this->scrub = (float)$arg0;
		$this->addModifiedColumn('scrub');
		return $this;
	}

	/**
	 * Returns the reference_id
	 * @return string
	 */
	function getReferenceId() {
		if (is_null($this->reference_id)) {
			$this->reference_id = "";
		}
		return $this->reference_id;
	}

	/**
	 * Sets the reference_id
	 * @var string
	 */
	function setReferenceId($arg0) {
		$this->reference_id = $arg0;
		$this->addModifiedColumn('reference_id');
		return $this;
	}

	/**
	 * Returns the client_type
	 * @return integer
	 */
	function getClientType() {
		if (is_null($this->client_type)) {
			$this->client_type = self::CLIENT_TYPE_PRIMARY_ADMIN;
		}
		return $this->client_type;
	}

	/**
	 * Sets the client_type
	 * @var integer
	 */
	function setClientType($arg0) {
		$this->client_type = (int)$arg0;
		$this->addModifiedColumn('client_type');
		return $this;
	}

	/**
	 * Returns the ref_users
	 * @return integer
	 */
	function getUsers() {
		if (is_null($this->users)) {
			$user = new \Gun\User();
			$user->setClientId($this->getId());
			$this->users = $user->queryAllByClient();
		}
		return $this->users;
	}

	/**
	 * Returns the advertiser_offers
	 * @return integer
	 */
	function getAdvertiserOffers() {
		if (is_null($this->advertiser_offers)) {
			$offer = new \Gun\Offer();
			$offer->setClientId($this->getId());
			$this->advertiser_offers = $offer->queryAllByClient();
		}
		return $this->advertiser_offers;
	}

	/**
	 * Returns the publisher_campaigns
	 * @return integer
	 */
	function getPublisherCampaigns() {
		if (is_null($this->publisher_campaigns)) {
			$campaign = new \Gun\Campaign();
			$campaign->setClientId($this->getId());
			$this->publisher_campaigns = $campaign->queryAllByClient();
		}
		return $this->publisher_campaigns;
	}

	/**
	 * Returns the client_exports
	 * @return array
	 */
	function getClientExports() {
		if (is_null($this->client_exports)) {
			$client_export = new \Gun\ClientExport();
			$client_export->setClientId($this->getId());
			$this->client_exports = $client_export->queryAllByClient();
		}
		return $this->client_exports;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	/**
	 * Returns the offer based on the criteria
	 * @return Gun\Offer
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getKeywords()) != '') {
			$criteria['$or'] = array(
					array('name' => new \MongoRegex("/" . $this->getKeywords() . "/i"))
			);
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Returns the array of statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::CLIENT_STATUS_ACTIVE => 'Active',
				self::CLIENT_STATUS_INACTIVE => 'Inactive',
				self::CLIENT_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the array of client types
	 * @return multitype:string
	 */
	public static function retrieveClientTypes() {
		return array(
				self::CLIENT_TYPE_PRIMARY_ADMIN => 'Primary Administrator',
				self::CLIENT_TYPE_SECONDARY_ADMIN => 'Secondary Administrator',
				self::CLIENT_TYPE_AFFILIATE => 'Affiliate'
		);
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$client = new self();
		$client->getCollection()->ensureIndex(array('status' => 1, 'type' => 1), array('background' => true));
		return true;
	}

}
