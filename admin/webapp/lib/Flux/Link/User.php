<?php
namespace Flux\Link;

class User extends BasicLink {
	
	private $user;
	
	/**
	 * Returns the user_id
	 * @return integer
	 */
	function getUserId() {
		return parent::getId();
	}
	
	/**
	 * Sets the user_id
	 * @var integer
	 */
	function setUserId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the user_name
	 * @return string
	 */
	function getUserName() {
		return parent::getName();
	}
	
	/**
	 * Sets the user_name
	 * @var string
	 */
	function setUserName($arg0) {
		return parent::setName($arg0);
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