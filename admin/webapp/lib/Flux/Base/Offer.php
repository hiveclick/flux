<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Offer extends MongoForm {

	const OFFER_STATUS_ACTIVE = 1;
	const OFFER_STATUS_INACTIVE = 2;
	const OFFER_STATUS_DELETED = 3;

	const REDIRECT_TYPE_HOSTED = 1;
	const REDIRECT_TYPE_REDIRECT = 2;
	const REDIRECT_TYPE_POST = 3;

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
	protected $flow_id;
	protected $verticals;
	protected $payout;
	protected $default_campaign_id;
	protected $client;

	protected $notes;
	
	protected $daily_clicks;
	protected $daily_conversions;

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
	 * Returns the client
	 * @return \Flux\Link\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Link\Client();
		}
		return $this->client;
	}
	
	/**
	 * Sets the client
	 * @var \Flux\Link\Client
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
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$offer = new self();
		$offer->getCollection()->ensureIndex(array('client_id' => 1), array('background' => true));
		return true;
	}
}