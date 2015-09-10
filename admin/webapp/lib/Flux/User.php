<?php
namespace Flux;

class User extends Base\User {

	private $client_id_array;
	
	private $new_password;
	private $new_password_confirm;
	
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
	    } else if (is_string($arg0)) {
	        $this->client_id_array = array($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->client_id_array = array($arg0);
	    }
		array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
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
		} else if ($this->getUserType() == self::USER_TYPE_DATA_ENTRY) {
			return "Data Entry Only";
		} else {
			return "Unknown Type";
		}
	}
	
	/**
	 * Returns the new_password
	 * @return string
	 */
	function getNewPassword() {
	    if (is_null($this->new_password)) {
	        $this->new_password = "";
	    }
	    return $this->new_password;
	}
	
	/**
	 * Sets the new_password
	 * @var string
	 */
	function setNewPassword($arg0) {
	    $this->new_password = $arg0;
	    return $this;
	}
	
	/**
	 * Returns the new_password_confirm
	 * @return string
	 */
	function getNewPasswordConfirm() {
	    if (is_null($this->new_password_confirm)) {
	        $this->new_password_confirm = "";
	    }
	    return $this->new_password_confirm;
	}
	
	/**
	 * Sets the new_password_confirm
	 * @var string
	 */
	function setNewPasswordConfirm($arg0) {
	    $this->new_password_confirm = $arg0;
	    return $this;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (\MongoId::isValid($this->getClient()->getClientId())) {
			$criteria['client.client_id'] = $this->getClient()->getClientId();
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client.client_id'] = array('$in' => $this->getClientIdArray());
		}
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName() . "/i");
		}
		return parent::queryAll($criteria, $hydrate, $fields);
	}

	/**
	 * Attempts to login the user
	 * @return Flux\User
	 */
	function queryAllByClient() {
		return $this->queryAll(array('client.client_id' => $this->getClient()->getClientId()));
	}
	
	/**
	 * Updates the password for this user
	 * @return integer
	 */
	function updatePassword() {
	    if ($this->getNewPassword() == $this->getNewPasswordConfirm()) {
	        $this->setPassword($this->getNewPassword());
	        return $this->update();
	    } else {
	        throw new \Exception('The passwords do not match. Please check them.');
	    }
	}
	
	/**
	 * Updates the password for this user
	 * @return integer
	 */
	function updateToken() {
	    $new_token = md5(strtotime('now'));
        $this->setToken($new_token);
        return $this->update();
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
		if (!is_null($user) && \MongoId::isValid($user->getId())) {
			$this->populate($user);
		}
		return $this;
	}
	
	/**
	 * Attempts to login the user with a token
	 * @return Flux\User
	 */
	function tryTokenLogin() {
	    $user = $this->query(array('token' => $this->getToken(), 'status' => self::USER_STATUS_ACTIVE), false);
	    if ($user === false) {
	        throw new \Exception('Your login credentials are not correct.  Please check your username and/or password');
	    }
	    if (!is_null($user) && \MongoId::isValid($user->getId())) {
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
