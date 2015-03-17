<?php
namespace Flux;

class User extends Base\User {

	private $client_id_array;
	
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
		$this->client_id_array = $arg0;
		return $this;
	}
	
	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::USER_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::USER_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::USER_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}

	/**
	 * Returns the active
	 * @return boolean
	 */
	function isActive() {
		return ($this->getStatus() == self::USER_STATUS_ACTIVE);
	}

	/**
	 * Returns the _user_type_name
	 * @return string
	 */
	function getUserTypeName() {
		if ($this->getUserType() == self::USER_TYPE_ADMIN) {
			return "Administrator";
		} else if ($this->getUserType() == self::USER_TYPE_REPORT) {
			return "Report User";
		} else if ($this->getUserType() == self::USER_TYPE_MOBILE_ONLY) {
			return "Mobile Only";
		} else {
			return "Unknown Type";
		}
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getClient()->getClientId() > 0) {
			$criteria['client.client_id'] = $this->getClient()->getClientId();
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client.client_id'] = array('$in' => $this->getClientIdArray());
		}
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName() . "/i");
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Attempts to login the user
	 * @return Flux\User
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client.client_id' => $this->getClient()->getClientId()));
	}

	/**
	 * Attempts to login the user
	 * @return Flux\User
	 */
	function tryLogin() {
		$user = $this->query(array('email' => $this->getEmail(), 'password' => $this->getPassword(), 'status' => self::USER_STATUS_ACTIVE), false);
		if ($user === false) {
			throw new \Exception('Your login credentials are not correct.  Please check your username and/or password');
		}
		if (!is_null($user) && $user->getId() > 0) {
			$this->populate($user);
		}
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
		return true;
	}
}
