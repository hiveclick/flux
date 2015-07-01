<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Client extends MongoForm {

	const CLIENT_STATUS_ACTIVE = 1;
	const CLIENT_STATUS_INACTIVE = 2;
	const CLIENT_STATUS_DELETED = 3;

	const CLIENT_TYPE_PRIMARY_ADMIN = 1;
	const CLIENT_TYPE_AFFILIATE = 3;

	protected $name;
	protected $color;
	protected $email;
	protected $status;
	protected $pixel;
	protected $scrub;
	protected $reference_id;
	protected $client_type;
	
	protected $network_url;
	protected $network_username;
	protected $network_password;
	
	protected $reporting_api_url;
	protected $reporting_api_token;
	protected $reporting_api_class;

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
	 * Returns the color
	 * @return string
	 */
	function getColor() {
	    if (is_null($this->color)) {
	        $this->color = "#000000";
	    }
	    return $this->color;
	}
	
	/**
	 * Sets the color
	 * @var string
	 */
	function setColor($arg0) {
	    $this->color = $arg0;
	    $this->addModifiedColumn("color");
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
	 * Returns the network_url
	 * @return string
	 */
	function getNetworkUrl() {
	    if (is_null($this->network_url)) {
	        $this->network_url = "";
	    }
	    return $this->network_url;
	}
	
	/**
	 * Sets the network_url
	 * @var string
	 */
	function setNetworkUrl($arg0) {
	    $this->network_url = $arg0;
	    $this->addModifiedColumn('network_url');
	    return $this;
	}
	
	/**
	 * Returns the network_username
	 * @return string
	 */
	function getNetworkUsername() {
	    if (is_null($this->network_username)) {
	        $this->network_username = "";
	    }
	    return $this->network_username;
	}
	
	/**
	 * Sets the network_username
	 * @var string
	 */
	function setNetworkUsername($arg0) {
	    $this->network_username = $arg0;
	    $this->addModifiedColumn('network_username');
	    return $this;
	}
	
	/**
	 * Returns the network_password
	 * @return string
	 */
	function getNetworkPassword() {
	    if (is_null($this->network_password)) {
	        $this->network_password = "";
	    }
	    return $this->network_password;
	}
	
	/**
	 * Sets the network_password
	 * @var string
	 */
	function setNetworkPassword($arg0) {
	    $this->network_password = $arg0;
	    $this->addModifiedColumn('network_password');
	    return $this;
	}
	
	/**
	 * Returns the reporting_api_url
	 * @return string
	 */
	function getReportingApiUrl() {
	    if (is_null($this->reporting_api_url)) {
	        $this->reporting_api_url = "";
	    }
	    return $this->reporting_api_url;
	}
	
	/**
	 * Sets the reporting_api_url
	 * @var string
	 */
	function setReportingApiUrl($arg0) {
	    $this->reporting_api_url = $arg0;
	    $this->addModifiedColumn('reporting_api_url');
	    return $this;
	}
	
	/**
	 * Returns the reporting_api_token
	 * @return string
	 */
	function getReportingApiToken() {
	    if (is_null($this->reporting_api_token)) {
	        $this->reporting_api_token = "";
	    }
	    return $this->reporting_api_token;
	}
	
	/**
	 * Sets the reporting_api_token
	 * @var string
	 */
	function setReportingApiToken($arg0) {
	    $this->reporting_api_token = $arg0;
	    $this->addModifiedColumn('reporting_api_token');
	    return $this;
	}
	
	/**
	 * Returns the reporting_api_class
	 * @return string
	 */
	function getReportingApiClass() {
	    if (is_null($this->reporting_api_class)) {
	        $this->reporting_api_class = "";
	    }
	    return $this->reporting_api_class;
	}
	
	/**
	 * Sets the reporting_api_class
	 * @var string
	 */
	function setReportingApiClass($arg0) {
	    $this->reporting_api_class = $arg0;
	    $this->addModifiedColumn('reporting_api_class');
	    return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
	    $client = new self();
	    $client->getCollection()->ensureIndex(array('status' => 1, 'type' => 1), array('background' => true));
	    $client->getCollection()->ensureIndex(array('email' => 1), array('background' => true, 'unique' => true));
	    $client->getCollection()->ensureIndex(array('name' => 1), array('background' => true));
	    return true;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
}
