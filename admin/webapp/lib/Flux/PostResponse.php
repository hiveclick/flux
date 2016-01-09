<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class PostResponse extends CommonForm {
	
	private $lead;
	protected $response;
	protected $received_time;
	
	/**
	 * Returns the lead
	 * @return \Flux\Link\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Link\Lead();
		}
		return $this->lead;
	}
	
	/**
	 * Sets the lead
	 * @var \Flux\Link\Lead
	 */
	function setLead($arg0) {
		$this->lead = $arg0;
		$this->addModifiedColumn("lead");
		return $this;
	}
	
	/**
	 * Returns the response
	 * @return string
	 */
	function getResponse() {
		if (is_null($this->response)) {
			$this->response = "";
		}
		return $this->response;
	}
	
	/**
	 * Sets the response
	 * @var string
	 */
	function setResponse($arg0) {
		$this->response = $arg0;
		$this->addModifiedColumn("response");
		return $this;
	}
	
	/**
	 * Returns the received_time
	 * @return string
	 */
	function getReceivedTime() {
		if (is_null($this->received_time)) {
			$this->received_time = date("m/d/Y h:i:s");
		}
		return $this->received_time;
	}
	
	/**
	 * Sets the received_time
	 * @var string
	 */
	function setReceivedTime($arg0) {
		$this->received_time = $arg0;
		$this->addModifiedColumn("received_time");
		return $this;
	}
}