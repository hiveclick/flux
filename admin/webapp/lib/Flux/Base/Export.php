<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Export extends MongoForm {

	const EXPORT_STATUS_ACTIVE = 1;
	const EXPORT_STATUS_INACTIVE = 2;
	const EXPORT_STATUS_DELETED = 3;

	const EXPORT_TYPE_BATCH = 1;
	const EXPORT_TYPE_REALTIME = 2;
	const EXPORT_TYPE_EMAIL_BATCH = 3;
	const EXPORT_TYPE_EMAIL_REALTIME = 4;
	const EXPORT_TYPE_TEST = 5;

	protected $split;
	protected $fulfillment;
	protected $export_date;
	protected $export_type;
	protected $created;
	
	protected $name;
	protected $status;
	
	protected $num_records;
	protected $num_records_successful;
	protected $num_records_error;
	protected $percent_complete;
	protected $is_running;
	protected $is_complete;
	protected $processing_log;
	
	protected $start_time;
	protected $end_time;
	protected $finding_records_time;
	protected $sending_records_time;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('export');
		$this->setDbName('admin');
	}
	
	/**
	 * Returns the created
	 * @return int
	 */
	function getCreated() {
		if (is_null($this->created)) {
			$this->created = new \MongoDate();
		}
		return $this->created;
	}
	
	/**
	 * Sets the created
	 * @var int
	 */
	function setCreated($arg0) {
		$this->created = $arg0;
		$this->addModifiedColumn('created');
		return $this;
	}
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn('name');
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
		$this->export_date = $arg0;
		$this->addModifiedColumn('export_date');
		return $this;
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::EXPORT_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
		return $this;
	}
	
	/**
	 * Returns the start_time
	 * @return integer
	 */
	function getStartTime() {
		if (is_null($this->start_time)) {
			$this->start_time = 0;
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
	 * Returns the end_time
	 * @return integer
	 */
	function getEndTime() {
		if (is_null($this->end_time)) {
			$this->end_time = 0;
		}
		return $this->end_time;
	}
	
	/**
	 * Sets the end_time
	 * @var integer
	 */
	function setEndTime($arg0) {
		$this->end_time = $arg0;
		$this->addModifiedColumn("end_time");
		return $this;
	}
	
	/**
	 * Returns the finding_records_time
	 * @return integer
	 */
	function getFindingRecordsTime() {
		if (is_null($this->finding_records_time)) {
			$this->finding_records_time = 0;
		}
		return $this->finding_records_time;
	}
	
	/**
	 * Sets the finding_records_time
	 * @var integer
	 */
	function setFindingRecordsTime($arg0) {
		$this->finding_records_time = $arg0;
		$this->addModifiedColumn("finding_records_time");
		return $this;
	}
	
	/**
	 * Returns the sending_records_time
	 * @return integer
	 */
	function getSendingRecordsTime() {
		if (is_null($this->sending_records_time)) {
			$this->sending_records_time = 0;
		}
		return $this->sending_records_time;
	}
	
	/**
	 * Sets the sending_records_time
	 * @var integer
	 */
	function setSendingRecordsTime($arg0) {
		$this->sending_records_time = $arg0;
		$this->addModifiedColumn("sending_records_time");
		return $this;
	}

	/**
	 * Returns the export_type
	 * @return integer
	 */
	function getExportType() {
		if (is_null($this->export_type)) {
			$this->export_type = self::EXPORT_TYPE_REALTIME;
		}
		return $this->export_type;
	}

	/**
	 * Sets the export_type
	 * @var integer
	 */
	function setExportType($arg0) {
		$this->export_type = $arg0;
		$this->addModifiedColumn('export_type');
		return $this;
	}
	
	/**
	 * Returns the is_running
	 * @return boolean
	 */
	function getIsRunning() {
		if (is_null($this->is_running)) {
			$this->is_running = false;
		}
		return $this->is_running;
	}
	
	/**
	 * Sets the is_running
	 * @var boolean
	 */
	function setIsRunning($arg0) {
		$this->is_running = (boolean)$arg0;
		$this->addModifiedColumn('is_running');
		return $this;
	}
	
	/**
	 * Returns the percent_complete
	 * @return float
	 */
	function getPercentComplete() {
		if (is_null($this->percent_complete)) {
			$this->percent_complete = 0;
		}
		return $this->percent_complete;
	}
	
	/**
	 * Sets the percent_complete
	 * @var float
	 */
	function setPercentComplete($arg0) {
		$this->percent_complete = (float)$arg0;
		$this->addModifiedColumn('percent_complete');
		return $this;
	}
	
	/**
	 * Returns the num_records
	 * @return integer
	 */
	function getNumRecords() {
		if (is_null($this->num_records)) {
			$this->num_records = 0;
		}
		return $this->num_records;
	}
	
	/**
	 * Sets the num_records
	 * @var integer
	 */
	function setNumRecords($arg0) {
		$this->num_records = (int)$arg0;
		$this->addModifiedColumn('num_records');
		return $this;
	}
	
	/**
	 * Returns the num_records_successful
	 * @return integer
	 */
	function getNumRecordsSuccessful() {
		if (is_null($this->num_records_successful)) {
			$this->num_records_successful = 0;
		}
		return $this->num_records_successful;
	}
	
	/**
	 * Sets the num_records_successful
	 * @var integer
	 */
	function setNumRecordsSuccessful($arg0) {
		$this->num_records_successful = $arg0;
		$this->addModifiedColumn("num_records_successful");
		return $this;
	}
	
	/**
	 * Returns the num_records_error
	 * @return integer
	 */
	function getNumRecordsError() {
		if (is_null($this->num_records_error)) {
			$this->num_records_error = 0;
		}
		return $this->num_records_error;
	}
	
	/**
	 * Sets the num_records_error
	 * @var integer
	 */
	function setNumRecordsError($arg0) {
		$this->num_records_error = $arg0;
		$this->addModifiedColumn("num_records_error");
		return $this;
	}
	
	/**
	 * Returns the processing_log
	 * @return string
	 */
	function getProcessingLog() {
		if (is_null($this->processing_log)) {
			$this->processing_log = "";
		}
		return $this->processing_log;
	}
	
	/**
	 * Sets the processing_log
	 * @var string
	 */
	function setProcessingLog($arg0) {
		$this->processing_log = $arg0;
		$this->addModifiedColumn('processing_log');
		return $this;
	}
	
	/**
	 * Returns the is_complete
	 * @return boolean
	 */
	function getIsComplete() {
		if (is_null($this->is_complete)) {
			$this->is_complete = false;
		}
		return $this->is_complete;
	}
	
	/**
	 * Sets the is_complete
	 * @var boolean
	 */
	function setIsComplete($arg0) {
		$this->is_complete = (boolean)$arg0;
		$this->addModifiedColumn('is_complete');
		return $this;
	}
	
	/**
	 * Returns the log filename that this export would use
	 * @return string
	 */
	function getLogFilename() {
		if (defined('MO_LOG_FOLDER')) {
			return MO_LOG_FOLDER . "/export_queue.sh_" . $this->getId() . ".log";
		}
		return '/var/log/flux/export_queue.sh_' . $this->getId() . ".log";
	}
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Link\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Link\Fulfillment();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Sets the fulfillment
	 * @var integer|array
	 */
	function setFulfillment($arg0) {
		if (is_array($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->populate($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == "") {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if (is_string($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == "") {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if ($arg0 instanceof \MongoId) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == "") {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		}
		$this->addModifiedColumn('fulfillment');
		return $this;
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Link\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Link\Split();
		}
		return $this->split;
	}
	
	/**
	 * Sets the split
	 * @var integer|array
	 */
	function setSplit($arg0) {
		if (is_array($arg0)) {
			$split = $this->getSplit();
			$split->populate($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		} else if (is_string($arg0)) {
			$split = $this->getSplit();
			$split->setSplitId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		} else if ($arg0 instanceof \MongoId) {
			$split = $this->getSplit();
			$split->setSplitId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		}
		$this->addModifiedColumn('split');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$export = new self();
		$export->getCollection()->ensureIndex(array('client_export_id' => 1), array('background' => true));
		$export->getCollection()->ensureIndex(array('split_id' => 1), array('background' => true));
		$export->getCollection()->ensureIndex(array('is_complete' => 1), array('background' => true));
		return true;
	}
}