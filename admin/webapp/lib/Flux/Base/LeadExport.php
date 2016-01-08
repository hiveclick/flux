<?php
namespace Flux\Base;

use Mojavi\Form\MojaviForm;

class LeadExport extends MojaviForm {

	protected $lead_id;
	protected $client_export_id;
	protected $client_export_name;
	protected $revenue;
	protected $export_date;
	protected $request;
	protected $response;
	protected $error_message;
	protected $is_success;
	
	private $client_export;
	
	/**
	 * Returns the lead_id
	 * @return integer
	 */
	function getLeadId() {
		if (is_null($this->lead_id)) {
			$this->lead_id = null;
		}
		return $this->lead_id;
	}
	
	/**
	 * Sets the lead_id
	 * @var string
	 */
	function setLeadId($arg0) {
		if (is_string($arg0)) {
			$this->lead_id = new \MongoId((string)$arg0);
			$this->addModifiedColumn("lead_id");
		} else if ($arg0 instanceof \MongoId) {
			$this->lead_id = $arg0;
			$this->addModifiedColumn("lead_id");
		}
		return $this;
	}
	
	/**
	 * Returns the client_export_id
	 * @return integer
	 */
	function getFulfillmentId() {
		if (is_null($this->client_export_id)) {
			$this->client_export_id = 0;
		}
		return $this->client_export_id;
	}
	
	/**
	 * Sets the client_export_id
	 * @var integer
	 */
	function setFulfillmentId($arg0) {
		$this->client_export_id = $arg0;
		$this->addModifiedColumn("client_export_id");
		$this->addModifiedColumn("client_export_name");
		return $this;
	}
	
	/**
	 * Returns the client_export_name
	 * @return string
	 */
	function getFulfillmentName() {
		if (is_null($this->client_export_name)) {
			$this->client_export_name = $this->getFulfillment()->getName();
		}
		return $this->client_export_name;
	}
	
	/**
	 * Returns the revenue
	 * @return float
	 */
	function getRevenue() {
		if (is_null($this->revenue)) {
			$this->revenue = 0.00;
		}
		return $this->revenue;
	}
	
	/**
	 * Sets the revenue
	 * @var float
	 */
	function setRevenue($arg0) {
		$this->revenue = (float)$arg0;
		$this->addModifiedColumn("revenue");
		return $this;
	}
	
	/**
	 * Returns the export_date
	 * @return MongoDate
	 */
	function getExportDate() {
		if (is_null($this->export_date)) {
			$this->export_date = new \MongoDate();
		}
		return $this->export_date;
	}
	
	/**
	 * Sets the export_date
	 * @var MongoDate
	 */
	function setExportDate($arg0) {
		if (is_integer($arg0)) {
			$this->export_date = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->export_date = $arg0;
		}
		$this->addModifiedColumn("export_date");
		return $this;
	}	
	
	/**
	 * Returns the request
	 * @return string
	 */
	function getRequest() {
		if (is_null($this->request)) {
			$this->request = "";
		}
		return $this->request;
	}
	
	/**
	 * Sets the request
	 * @var string
	 */
	function setRequest($arg0) {
		$this->request = $arg0;
		$this->addModifiedColumn("request");
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
	 * Returns the error_message
	 * @return string
	 */
	function getErrorMessage() {
		if (is_null($this->error_message)) {
			$this->error_message = "";
		}
		return $this->error_message;
	}
	
	/**
	 * Sets the error_message
	 * @var string
	 */
	function setErrorMessage($arg0) {
		$this->error_message = $arg0;
		$this->addModifiedColumn("error_message");
		return $this;
	}
	
	/**
	 * Returns the is_success
	 * @return boolean
	 */
	function getIsSuccess() {
		if (is_null($this->is_success)) {
			$this->is_success = false;
		}
		return $this->is_success;
	}
	
	/**
	 * Sets the is_success
	 * @var boolean
	 */
	function setIsSuccess($arg0) {
		$this->is_success = $arg0;
		$this->addModifiedColumn("is_success");
		return $this;
	}
	
	/**
	 * Returns the client_export
	 * @return \Flux\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->client_export)) {
			$this->client_export = new \Flux\Fulfillment();
			$this->client_export->setId($this->getId());
			$this->client_export->query();
		}
		return $this->client_export_id;
	}
}