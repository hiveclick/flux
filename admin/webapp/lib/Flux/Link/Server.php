<?php
namespace Flux\Link;

class Server extends BasicLink {
	
	protected $record_count;
	
	private $server;
	
	/**
	 * Returns the server_id
	 * @return integer
	 */
	function getServerId() {
		return parent::getId();
	}
	
	/**
	 * Sets the server_id
	 * @var integer
	 */
	function setServerId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the server_name
	 * @return string
	 */
	function getServerName() {
		return parent::getName();
	}
	
	/**
	 * Sets the server_name
	 * @var string
	 */
	function setServerName($arg0) {
		return parent::setName($arg0);
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