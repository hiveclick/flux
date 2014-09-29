<?php
namespace Flux;

use Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class Offer extends MongoForm {

	const OFFER_STATUS_ACTIVE = 1;
	const OFFER_STATUS_INACTIVE = 2;
	const OFFER_STATUS_DELETED = 3;

	const REDIRECT_TYPE_HOSTED = 1;
	const REDIRECT_TYPE_REDIRECT = 2;

	protected $name;
	protected $status;
	protected $redirect_type;
	protected $redirect_url;
	protected $folder_name;
    protected $domain_name;
    protected $docroot_dir;
	protected $preview_url;
	protected $realtime_status;
	protected $batch_status;
	protected $timezone;
	protected $notification_interval;
	protected $events;
	protected $client_id;
	protected $flow_id;
	protected $verticals;
	protected $payout;
	protected $default_campaign_id;

	protected $notes;

	protected $_client_name;
	protected $_status_name;
	protected $_redirect_type_name;
	
	protected $daily_clicks;
	protected $daily_conversions;

	private $offer_pages;
	private $client;
	private $flow;
	private $offer_map;
	private $publisher_campaigns;
	
	/* these fields are used when searching */
	private $client_id_array;
	private $status_array;
	
	private static $_offers;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('offer');
		$this->setDbName('admin');
	}
	
	/**
	 * Returns the id
	 * @return integer
	 */
	function getOfferId() {
		return parent::getId();
	}
	
	/**
	 * Sets the id
	 * @var integer
	 */
	function setOfferId($arg0) {
		return parent::setId($arg0);
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
			$this->status = self::OFFER_STATUS_ACTIVE;
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
	 * Returns the payout
	 * @return float
	 */
	function getPayout() {
		if (is_null($this->payout)) {
			$this->payout = 0.00;
		}
		return $this->payout;
	}

	/**
	 * Sets the payout
	 * @var float
	 */
	function setPayout($arg0) {
		$this->payout = (float)$arg0;
		$this->addModifiedColumn('payout');
		return $this;
	}
	
	/**
	 * Returns the default_campaign_id
	 * @return string
	 */
	function getDefaultCampaignId() {
		if (is_null($this->default_campaign_id)) {
			$this->default_campaign_id = "";
		}
		return $this->default_campaign_id;
	}
	
	/**
	 * Sets the default_campaign_id
	 * @var string
	 */
	function setDefaultCampaignId($arg0) {
		$this->default_campaign_id = $arg0;
		$this->addModifiedColumn("default_campaign_id");
		return $this;
	}

	/**
	 * Returns the _redirect_type_name
	 * @return string
	 */
	function getRedirectTypeName() {
		if (is_null($this->_redirect_type_name)) {
			$this->_redirect_type_name = self::retrieveRedirectTypes()[$this->getRedirectType()];
		}
		return $this->_redirect_type_name;
	}

	/**
	 * Returns the redirect_type
	 * @return integer
	 */
	function getRedirectType() {
		if (is_null($this->redirect_type)) {
			$this->redirect_type = self::REDIRECT_TYPE_HOSTED;
		}
		return $this->redirect_type;
	}

	/**
	 * Sets the redirect_type
	 * @var integer
	 */
	function setRedirectType($arg0) {
		$this->redirect_type = (int)$arg0;
		$this->addModifiedColumn('redirect_type');
		return $this;
	}

	/**
	 * Returns the redirect_url
	 * @return string
	 */
	function getRedirectUrl() {
		if (is_null($this->redirect_url)) {
			$this->redirect_url = "";
		}
		return $this->redirect_url;
	}

	/**
	 * Sets the redirect_url
	 * @var string
	 */
	function setRedirectUrl($arg0) {
		$this->redirect_url = $arg0;
		$this->addModifiedColumn('redirect_url');
		return $this;
	}

	/**
	 * Returns the folder_name
	 * @return string
	 */
	function getFolderName() {
		if (is_null($this->folder_name)) {
			$this->folder_name = "";
		}
		return $this->folder_name;
	}

	/**
	 * Sets the folder_name
	 * @var string
	 */
	function setFolderName($arg0) {
		$this->folder_name = $arg0;
		$this->addModifiedColumn('folder_name');
		return $this;
	}
	
	/**
	 * Returns the domain_name
	 * @return string
	 */
	function getDomainName() {
		if (is_null($this->domain_name)) {
			$this->domain_name = "";
		}
		return $this->domain_name;
	}
	
	/**
	 * Sets the domain_name
	 * @var string
	 */
	function setDomainName($arg0) {
		$this->domain_name = $arg0;
		$this->addModifiedColumn("domain_name");
		return $this;
	}
	
	/**
	 * Returns the docroot_dir
	 * @return string
	 */
	function getDocrootDir() {
		if (is_null($this->docroot_dir)) {
			$this->docroot_dir = "/var/www/sites/";
		}
		return $this->docroot_dir;
	}
	
	/**
	 * Sets the docroot_dir
	 * @var string
	 */
	function setDocrootDir($arg0) {
		$this->docroot_dir = $arg0;
		$this->addModifiedColumn("docroot_dir");
		return $this;
	}

	/**
	 * Returns the flow_id
	 * @return integer
	 */
	function getFlowId() {
		if (is_null($this->flow_id)) {
			$this->flow_id = 0;
		}
		return $this->flow_id;
	}

	/**
	 * Sets the flow_id
	 * @var integer
	 */
	function setFlowId($arg0) {
		$this->flow_id = (int)$arg0;
		$this->addModifiedColumn('flow_id');
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
	 * Returns the verticals
	 * @return array
	 */
	function getVerticals() {
		if (is_null($this->verticals)) {
			$this->verticals = array();
		}
		return $this->verticals;
	}

	/**
	 * Sets the verticals
	 * @var array
	 */
	function setVerticals($arg0) {
		if (is_array($arg0)) {
			asort($arg0);
			$this->verticals = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->verticals = explode(",", $arg0);
			} else {
				$this->verticals = array($arg0);
			}
		}
		$this->addModifiedColumn('verticals');
		return $this;
	}

	/**
	 * Returns the realtime_status
	 * @return integer
	 */
	function getRealtimeStatus() {
		if (is_null($this->realtime_status)) {
			$this->realtime_status = 0;
		}
		return $this->realtime_status;
	}

	/**
	 * Sets the realtime_status
	 * @var integer
	 */
	function setRealtimeStatus($arg0) {
		$this->realtime_status = (int)$arg0;
		$this->addModifiedColumn('realtime_status');
		return $this;
	}

	/**
	 * Returns the batch_status
	 * @return integer
	 */
	function getBatchStatus() {
		if (is_null($this->batch_status)) {
			$this->batch_status = 0;
		}
		return $this->batch_status;
	}

	/**
	 * Sets the batch_status
	 * @var integer
	 */
	function setBatchStatus($arg0) {
		$this->batch_status = (int)$arg0;
		$this->addModifiedColumn('batch_status');
		return $this;
	}

	/**
	 * Returns the timezone
	 * @return integer
	 */
	function getTimezone() {
		if (is_null($this->timezone)) {
			$this->timezone = "America/Los Angeles";
		}
		return $this->timezone;
	}

	/**
	 * Sets the timezone
	 * @var integer
	 */
	function setTimezone($arg0) {
		$this->timezone = $arg0;
		$this->addModifiedColumn('timezone');
		return $this;
	}

	/**
	 * Returns the notification_interval
	 * @return integer
	 */
	function getNotificationInterval() {
		if (is_null($this->notification_interval)) {
			$this->notification_interval = 10;
		}
		return $this->notification_interval;
	}

	/**
	 * Sets the notification_interval
	 * @var integer
	 */
	function setNotificationInterval($arg0) {
		$this->notification_interval = (int)$arg0;
		$this->addModifiedColumn('notification_interval');
		return $this;
	}

	/**
	 * Returns the notes
	 * @return string
	 */
	function getNotes() {
		if (is_null($this->notes)) {
			$this->notes = "";
		}
		return $this->notes;
	}

	/**
	 * Sets the notes
	 * @var string
	 */
	function setNotes($arg0) {
		$this->notes = $arg0;
		$this->addModifiedColumn('notes');
		return $this;
	}

	/**
	 * Returns the events
	 * @return array
	 */
	function getEvents() {
		if (is_null($this->events)) {
			$this->events = array();
		}
		return $this->events;
	}

	/**
	 * Sets the events
	 * @var array
	 */
	function setEvents($arg0) {
		$this->events = $arg0;
		array_walk($this->events, function(&$val) { $val['event_id'] = (int)$val['event_id']; });
		$this->addModifiedColumn('events');
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
	 * Returns the status_array
	 * @return array
	 */
	function getStatusArray() {
		if (is_null($this->status_array)) {
			$this->status_array = array();
		}
		return $this->status_array;
	}
	
	/**
	 * Sets the status_array
	 * @var array
	 */
	function setStatusArray($arg0) {
		if (is_array($arg0)) {
			$this->status_array = $arg0;
			array_walk($this->status_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->status_array = explode(",", $arg0);
				array_walk($this->status_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->status_array = array((int)$arg0);
			}
		}
		return $this;
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
	 * Returns the flow
	 * @return \Flux\Flow
	 */
	function getFlow() {
		if (is_null($this->flow)) {
			$this->flow = new \Flux\Flow();
			$this->flow->setId($this->getFlowId());
			$this->flow->query();
		}
		return $this->flow;
	}
	
	/**
	 * Returns the offer_pages
	 * @return array
	 */
	function getOfferPages() {
		if (is_null($this->offer_pages)) {
			$offer_page = new \Flux\OfferPage();
			$offer_page->setOfferId($this->getId());
			$this->offer_pages = $offer_page->queryAll();
		}
		return $this->offer_pages;
	}

	/**
	 * Returns the publisher_campaigns
	 * @return array
	 */
	function getPublisherCampaigns() {
		if (is_null($this->publisher_campaigns)) {
			$campaign = new \Flux\Campaign();
			$campaign->setOfferId($this->getId());
			$this->publisher_campaigns = $campaign->queryAllByOffer();
		}
		return $this->publisher_campaigns;
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
	 * Returns the offer based on the criteria
	 * @return Flux\Offer
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getFolderName() != '') {
			$criteria['folder_name'] = $this->getFolderName();
		}
		if ($this->getDomainName() != '') {
			$criteria['domain_name'] = $this->getDomainName();
		}
		if ($this->getClientId() > 0) {
			$criteria['client_id'] = $this->getClientId();
		}
		if (count($this->getVerticals()) > 0) {
			$criteria['verticals'] = array('$in' => $this->getVerticals());
		}
		if (trim($this->getKeywords()) != '') {
			$criteria['$or'] = array(
				array('name' => new \MongoRegex("/" . $this->getKeywords() . "/i")),
				array('verticals' =>  new \MongoRegex("/" . $this->getKeywords() . "/i"))
			);
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client_id'] = array('$in' => $this->getClientIdArray());
		}
		if (count($this->getStatusArray()) > 0) {
			$criteria['status'] = array('$in' => $this->getStatusArray());
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Finds an offer by the folder name
	 * @return \Flux\Offer
	 */
	function queryByFolderName() {
		return parent::query(array('folder_name' => $this->getFolderName()), false);
	}

	/**
	 * Finds all offers by client
	 * @return Flux\Offer
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client_id' => $this->getClientId()));
	}

	/**
	 * Finds all offers by client
	 * @return Flux\Offer
	 */
	function queryAllByVerticals() {
		return $this->queryAll(array('verticals' => array('$in' => $this->getVerticals())));
	}

	/**
	 * Flushes the events
	 * @return Flux\Offer
	 */
	function flushEvents() {
		$this->setEvents(array());
		parent::addModifiedColumn('events');
		return $this->update();
	}

	/**
	 * Returns the array of offer statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::OFFER_STATUS_ACTIVE => 'Active',
				self::OFFER_STATUS_INACTIVE => 'Inactive',
				self::OFFER_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the array of offer redirect types
	 * @return multitype:string
	 */
	public static function retrieveRedirectTypes() {
		return array(
				self::REDIRECT_TYPE_HOSTED => 'Hosted',
				self::REDIRECT_TYPE_REDIRECT => 'Redirect'
		);
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$offer = new self();
		$offer->getCollection()->ensureIndex(array('client_id' => 1), array('background' => true));
		return true;
	}
}
