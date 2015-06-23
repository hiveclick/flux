<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class User extends MongoForm {

	const USER_STATUS_ACTIVE = 1;
	const USER_STATUS_INACTIVE = 2;
	const USER_STATUS_DELETED = 3;

	const USER_TYPE_ADMIN = 1;
	const USER_TYPE_DATA_ENTRY = 2;

	protected $name;
	protected $email;
	protected $password; // Private so it won't show in the API JSON
	protected $status;
	protected $user_type;
	protected $timezone;
	protected $client;
	protected $image_data;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('user');
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
	 * Returns the username
	 * @return string
	 */
	function getUsername() {
		return $this->getEmail();
	}
	
	/**
	 * Sets the username
	 * @param string
	 */
	function setUsername($arg0) {
		return $this->setEmail($arg0);
	}
	
	/**
	 * Returns the password
	 * @return string
	 */
	function getPassword() {
		if (is_null($this->password)) {
			$this->password = "";
		}
		return $this->password;
	}
	
	/**
	 * Sets the password
	 * @var string
	 */
	function setPassword($arg0) {
		$this->password = $arg0;
		$this->addModifiedColumn('password');
		return $this;
	}
	
	/**
	 * Returns the image_data
	 * @return string
	 */
	function getImageData() {
		if (is_null($this->image_data)) {
			$this->image_data = "";
		}
		return $this->image_data;
	}
	
	/**
	 * Sets the image_data
	 * @var string
	 */
	function setImageData($arg0) {
		$this->image_data = $arg0;
		$this->addModifiedColumn('image_data');
		return $this;
	}
	
	/**
	 * Returns the user_type
	 * @return integer
	 */
	function getUserType() {
		if (is_null($this->user_type)) {
			$this->user_type = self::USER_TYPE_ADMIN;
		}
		return $this->user_type;
	}
	
	/**
	 * Sets the user_type
	 * @var integer
	 */
	function setUserType($arg0) {
		$this->user_type = (int)$arg0;
		$this->addModifiedColumn('user_type');
		return $this;
	}
	
	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::USER_STATUS_ACTIVE;
		}
		return $this->status;
	}
	
	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		return $this;
	}
	
	
	
	/**
	 * Returns the timezone
	 * @return string
	 */
	function getTimezone() {
		if (is_null($this->timezone)) {
			$this->timezone = "US/Pacific";
		}
		return $this->timezone;
	}
	
	/**
	 * Sets the timezone
	 * @var string
	 */
	function setTimezone($arg0) {
		$this->timezone = $arg0;
		$this->addModifiedColumn('timezone');
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
			if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if (is_string($arg0)) {
			$client = $this->getClient();
			$client->setClientId($arg0);
			if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if ($arg0 instanceof \MongoId) {
		    $client = $this->getClient();
		    $client->setClientId($arg0);
		    if (\MongoId::isValid($client->getClientId()) && $client->getClientName() == "") {
		        $client->setClientName($client->getClient()->getName());
		    }
		    $this->client = $client;
		}
		$this->addModifiedColumn('client');
		return $this;
	}
}