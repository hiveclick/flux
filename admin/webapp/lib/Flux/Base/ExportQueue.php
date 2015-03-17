<?php
namespace Flux\Base;

use \Mojavi\Form\MongoForm;

class ExportQueue extends MongoForm {

	protected $url;
	protected $qs;
	protected $last_sent_time;
	protected $response;
	protected $response_time;
	protected $is_error;
	protected $start_time;
	protected $export;
	protected $lead;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct($export_id = 0) {
		$this->setExport($export_id);
		$this->setDbName('queue');
	}
	
	/**
	 * Returns the export
	 * @return \Flux\Link\Export
	 */
	function getExport() {
		if (is_null($this->export)) {
			$this->export = new \Flux\Link\Export();
		}
		return $this->export;
	}
	
	/**
	 * Sets the export
	 * @var integer|array
	 */
	function setExport($arg0) {
		if (is_array($arg0)) {
			$export = $this->getExport();
			$export->populate($arg0);
			if ($export->getExportId() > 0 && $export->getExportName() == "") {
				$export->setExportName($export->getExport()->getName());
			}
			$this->export = $export;
		} else if (is_string($arg0) || is_int($arg0)) {
			$export = $this->getExport();
			$export->setExportId($arg0);
			if ($export->getExportId() > 0 && $export->getExportName() == "") {
				$export->setExportName($export->getExport()->getName());
			}
			$this->export = $export;
		}
		$this->setCollectionName('export_queue_' . $this->export->getExportId());
		$this->addModifiedColumn('export');
		return $this;
	}
	
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
	 * @var integer|array
	 */
	function setLead($arg0) {
		if (is_array($arg0)) {
			$lead = $this->getLead();
			$lead->populate($arg0);
			if (\MongoId::isValid($lead->getLeadId()) && $lead->getLeadName() == "") {
				$lead->setLeadName((string)$lead->getLead()->getId());
			}
			$this->lead = $lead;
		} else if (is_string($arg0)) {
			$lead = $this->getLead();
			$lead->setLeadId($arg0);
			if (\MongoId::isValid($lead->getLeadId()) && $lead->getLeadName() == "") {
				$lead->setLeadName((string)$lead->getLead()->getId());
			}
			$this->lead = $lead;
		} else if ($arg0 instanceof \MongoId) {
			$lead = $this->getLead();
			$lead->setLeadId((string)$arg0);
			if (\MongoId::isValid($lead->getLeadId()) && $lead->getLeadName() == "") {
				$lead->setLeadName((string)$lead->getLead()->getId());
			}
			$this->lead = $lead;
		}
		$this->addModifiedColumn('lead');
		return $this;
	}
	
	/**
	 * Returns the url
	 * @return string
	 */
	function getUrl() {
		if (is_null($this->url)) {
			$this->url = "";
		}
		return $this->url;
	}
	
	/**
	 * Sets the url
	 * @var string
	 */
	function setUrl($arg0) {
		$this->url = $arg0;
		$this->addModifiedColumn('url');
		return $this;
	}
	
	/**
	 * Returns the last_sent_time
	 * @return int
	 */
	function getLastSentTime() {
		if (is_null($this->last_sent_time)) {
			$this->last_sent_time = 0;
		}
		return $this->last_sent_time;
	}
	
	/**
	 * Sets the last_sent_time
	 * @var int
	 */
	function setLastSentTime($arg0) {
		$this->last_sent_time = $arg0;
		$this->addModifiedColumn('last_sent_time');
		return $this;
	}
	
	/**
	 * Returns the qs
	 * @return string
	 */
	function getQs() {
		if (is_null($this->qs)) {
			$this->qs = array();
		}
		return $this->qs;
	}
	
	/**
	 * Sets the qs
	 * @var string
	 */
	function setQs($arg0) {
		$this->qs = $arg0;
		$this->addModifiedColumn('qs');
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
		$this->addModifiedColumn('response');
		return $this;
	}
	
	/**
	 * Returns the start_time
	 * @return integer
	 */
	function getStartTime() {
		if (is_null($this->start_time)) {
			$this->start_time = microtime(true);
		}
		return $this->start_time;
	}
	
	/**
	 * Sets the start_time
	 * @var integer
	 */
	function setStartTime($arg0) {
		$this->start_time = $arg0;
		$this->addModifiedColumn("start_time");
		return $this;
	}
	
	/**
	 * Returns the response_time
	 * @return float
	 */
	function getResponseTime() {
		if (is_null($this->response_time)) {
			$this->response_time = 0;
		}
		return $this->response_time;
	}
	
	/**
	 * Sets the response_time
	 * @var float
	 */
	function setResponseTime($arg0) {
		$this->response_time = $arg0;
		$this->addModifiedColumn('response_time');
		return $this;
	}
	
	/**
	 * Returns the is_error
	 * @return boolean
	 */
	function getIsError() {
		if (is_null($this->is_error)) {
			$this->is_error = false;
		}
		return $this->is_error;
	}
	
	/**
	 * Sets the is_error
	 * @var boolean
	 */
	function setIsError($arg0) {
		$this->is_error = (boolean)$arg0;
		$this->addModifiedColumn('is_error');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$export_queue = new self();
		$export_queue->getCollection()->ensureIndex(array('lead_id' => 1), array('background' => true));
		$export_queue->getCollection()->ensureIndex(array('export_id' => 1), array('background' => true));
		$export_queue->getCollection()->ensureIndex(array('split_id' => 1), array('background' => true));
		return true;
	}
	
	
}
