<?php
namespace Flux\Link;

class Client extends BasicLink {
	
	protected $client_color;
	
	private $client;
	
	/**
	 * Returns the client_id
	 * @return integer
	 */
	function getClientId() {
		return parent::getId();
	}
	
	/**
	 * Sets the client_id
	 * @var integer
	 */
	function setClientId($arg0) {
		parent::setId($arg0);
		$this->setClientColor($this->getClient()->getColor());
		return $this;
	}
	
	/**
	 * Returns the client_name
	 * @return string
	 */
	function getClientName() {
		return parent::getName();
	}
	
	/**
	 * Sets the client_name
	 * @var string
	 */
	function setClientName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the client_color
	 * @return string
	 */
	function getClientColor() {
		if (is_null($this->client_color)) {
			$this->client_color = "#000000";
		}
		return $this->client_color;
	}
	
	/**
	 * Sets the client_color
	 * @var string
	 */
	function setClientColor($arg0) {
		$this->client_color = $arg0;
		$this->addModifiedColumn("client_color");
		return $this;
	}
	
	/**
	 * Returns the client
	 * @return \Flux\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Client();
			$this->client->setId($this->getId());
			$this->client->query();
		}
		return $this->client;
	}
}