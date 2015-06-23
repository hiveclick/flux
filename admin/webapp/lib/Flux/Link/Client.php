<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Client extends CommonForm {
	
	protected $client_id;
	protected $client_name;
	protected $client_color;
	
	private $client;
	
	/**
	 * Returns the client_id
	 * @return integer
	 */
	function getClientId() {
		if (is_null($this->client_id)) {
			$this->client_id = null;
		}
		return $this->client_id;
	}
	
	/**
	 * Sets the client_id
	 * @var integer
	 */
	function setClientId($arg0) {
	    if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->client_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->client_id = $arg0;
	    }
		$this->setClientColor($this->getClient()->getColor());
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
			$this->client->setId($this->getClientId());
			$this->client->query();
		}
		return $this->client;
	}
}