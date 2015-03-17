<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Client extends CommonForm {
	
	protected $client_id;
	protected $client_name;
	
	private $client;
	
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
		return $this;
	}
	
	/**
	 * Returns the client_name
	 * @return string
	 */
	function getClientName() {
		if (is_null($this->client_name)) {
			$this->client_name = "";
		}
		return $this->client_name;
	}
	
	/**
	 * Sets the client_name
	 * @var string
	 */
	function setClientName($arg0) {
		$this->client_name = $arg0;
		return $this;
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
}