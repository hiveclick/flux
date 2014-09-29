<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class User extends MongoForm {

	const USER_STATUS_ACTIVE = 1;
	const USER_STATUS_INACTIVE = 2;
	const USER_STATUS_DELETED = 3;

	const USER_TYPE_ADMIN = 1;
	const USER_TYPE_REPORT = 2;
	const USER_TYPE_MOBILE_ONLY = 3;

	protected $name;
	protected $email;
	protected $password; // Private so it won't show in the API JSON
	protected $status;
	protected $user_type;
	protected $timezone;
	protected $client_id;
	protected $image_data;

	protected $_user_type_name;
	protected $_status_name;
	protected $_client_name;

	private $client;

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
		$this->addModifiedColumn('status');
		return $this;
	}

	/**
	 * Returns the active
	 * @return boolean
	 */
	function isActive() {
		return ($this->getStatus() == self::USER_STATUS_ACTIVE);
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
	 * Returns the _user_type_name
	 * @return string
	 */
	function getUserTypeName() {
		if (is_null($this->_user_type_name)) {
			$this->_user_type_name = self::retrieveUserTypes()[$this->getUserType()];
		}
		return $this->_user_type_name;
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
	 * @return integer
	 */
	function getClientId() {
		if (is_null($this->client_id)) {
			$this->client_id = 0;
		}
		return $this->client_id;
	}

	/**
	 * Sets the this
	 * @var integer
	 */
	function setClientId($arg0) {
		$this->client_id = intval($arg0);
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
	 * @return integer
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Client();
			$this->client->setId($this->getClientId());
			$this->client->query();
		}
		return $this->client;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getClientId() > 0) {
			$criteria['client_id'] = $this->getClientId();
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Attempts to login the user
	 * @return Flux\User
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client_id' => $this->getClientId()));
	}

	/**
	 * Attempts to login the user
	 * @return Flux\User
	 */
	function tryLogin() {
		$user = $this->query(array('email' => $this->getEmail(), 'password' => $this->getPassword(), 'status' => self::USER_STATUS_ACTIVE), false);
		if (!is_null($user) && $user->getId() > 0) {
			$this->populate($user);
		}
		return $this;
	}

	/**
	 * Returns an array of statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::USER_STATUS_ACTIVE => 'Active',
				self::USER_STATUS_INACTIVE => 'Inactive',
				self::USER_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns an array of user types
	 * @return multitype:string
	 */
	public static function retrieveUserTypes() {
		return array(
				self::USER_TYPE_ADMIN => 'Admin',
				self::USER_TYPE_REPORT => 'Report',
				self::USER_TYPE_MOBILE_ONLY => 'Mobile'
		);
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$client = new self();
		$client->getCollection()->ensureIndex(array('status' => 1, 'type' => 1), array('background' => true));
		$client->getCollection()->ensureIndex(array('email' => 1), array('background' => true, 'unique' => true));
		return true;
	}
}
