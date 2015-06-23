<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Server extends CommonForm {
	
	protected $server_id;
	protected $server_name;
	protected $record_count;
	
	private $server;
	
	/**
	 * Returns the server_id
	 * @return integer
	 */
	function getServerId() {
		if (is_null($this->server_id)) {
			$this->server_id = null;
		}
		return $this->server_id;
	}
	
	/**
	 * Sets the server_id
	 * @var integer
	 */
	function setServerId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->server_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->server_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the server_name
	 * @return string
	 */
	function getServerName() {
		if (is_null($this->server_name)) {
			$this->server_name = "";
		}
		return $this->server_name;
	}
	
	/**
	 * Sets the server_name
	 * @var string
	 */
	function setServerName($arg0) {
		$this->server_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the record_count
	 * @return integer
	 */
	function getRecordCount() {
		if (is_null($this->record_count)) {
			$this->record_count = 0;
		}
		return $this->record_count;
	}
	
	/**
	 * Sets the record_count
	 * @var integer
	 */
	function setRecordCount($arg0) {
		$this->record_count = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the server
	 * @return \Flux\Server
	 */
	function getServer() {
		if (is_null($this->server)) {
			$this->server = new \Flux\Server();
			$this->server->setId($this->getServerId());
			$this->server->query();
		}
		return $this->server;
	}
}