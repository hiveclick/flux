<?php
namespace Flux;

use \Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class ExportQueue extends MongoForm {

	protected $export_id;
	protected $lead_id;
	protected $url;
	protected $qs;
	protected $last_sent_time;
	protected $response;
	protected $response_time;
	protected $is_error;
	
	private $start_time;
	private $export;
	private $lead;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct($export_id = 0) {
		$this->setExportId($export_id);
		$this->setDbName('queue');
	}
	
	/**
	 * Returns the export_id
	 * @return integer
	 */
	function getExportId() {
		if (is_null($this->export_id)) {
			$this->export_id = 0;
		}
		return $this->export_id;
	}
	
	/**
	 * Sets the export_id
	 * @var integer
	 */
	function setExportId($arg0) {
		$this->export_id = (int)$arg0;
		$this->setCollectionName('export_queue_' . $this->export_id);
		$this->addModifiedColumn('export_id');
		return $this;
	}
	
	/**
	 * Returns the lead_id
	 * @return Object
	 */
	function getLeadId() {
		if (is_null($this->lead_id)) {
			$this->lead_id = null;
		}
		return $this->lead_id;
	}
	
	/**
	 * Sets the lead_id
	 * @var Object
	 */
	function setLeadId($arg0) {
		if (is_string($arg0)) {
			if (\MongoId::isValid($arg0)) {
				$this->lead_id = new \MongoId($arg0);
				$this->addModifiedColumn("lead_id");
			}
		} else if ($arg0 instanceof \MongoId) {
			$this->lead_id = $arg0;
			$this->addModifiedColumn("lead_id");
		}
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
	 * Returns the export
	 * @return \Flux\Export
	 */
	function getExport() {
		if (is_null($this->export)) {
			$this->export = new \Flux\Export();
			$this->export->setId($this->getExportId());
			$this->export->query();
		}
		return $this->export;
	}
	
	/**
	 * Returns the lead
	 * @return \Flux\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Lead();
			$this->lead->setId($this->getLeadId());
			$this->lead->query();
		}
		return $this->lead;
	}
	
	/**
	 * Queries all records from a queue
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getLeadId()) != '') {
			$criteria['lead_id'] = trim($this->getLeadId());
		}
		return parent::queryAll($criteria, $hydrate);
	}
	
	/**
	 * Queues a lead as a post url
	 * @return boolean
	 */
	function queueLead($lead) {
		$params = array();
		/* @var $mapping \Flux\ClientExportMap */
		foreach ($this->getClientExport()->getMapping() as $mapping) {
			$value = $mapping->getMappedValue($lead);
			if (trim($mapping->getFieldName()) == '') {
				$params[$mapping->getDataField()->getKeyName()] = $value;
			} else {
				$params[$mapping->getFieldName()] = $value;
			}
		}
				
		//$this->setUrl($url);
		$this->setQs($params);
		$this->setIsError(false);
		$this->setResponse(null);
		$this->setResponseTime(0);
		
		return parent::insert();
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
