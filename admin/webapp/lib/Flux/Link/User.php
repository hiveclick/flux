<?php
namespace Flux\Link;

class User extends CommonForm {
	
	protected $user_id;
	protected $user_name;
	
	private $user;
	
	/**
	 * Returns the user_id
	 * @return integer
	 */
	function getUserId() {
		if (is_null($this->user_id)) {
			$this->user_id = 0;
		}
		return $this->user_id;
	}
	
	/**
	 * Sets the user_id
	 * @var integer
	 */
	function setUserId($arg0) {
		$this->user_id = $arg0;
		return $this;
	}
	
	/**
	 * Returns the user_name
	 * @return string
	 */
	function getUserName() {
		if (is_null($this->user_name)) {
			$this->user_name = "";
		}
		return $this->user_name;
	}
	
	/**
	 * Sets the user_name
	 * @var string
	 */
	function setUserName($arg0) {
		$this->user_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the user
	 * @return \Flux\User
	 */
	function getUser() {
		if (is_null($this->user)) {
			$this->user = new \Flux\User();
			$this->user->setId($this->getUserId());
			$this->user->query();
		}
		return $this->user;
	}	
}