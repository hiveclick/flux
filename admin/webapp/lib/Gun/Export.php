<?php
namespace Gun;

use Mojavi\Form\MongoForm;

class Export extends MongoForm {

	const EXPORT_STATUS_ACTIVE = 1;
	const EXPORT_STATUS_INACTIVE = 2;
	const EXPORT_STATUS_DELETED = 3;

	const EXPORT_TYPE_BATCH = 1;
	const EXPORT_TYPE_REALTIME = 2;
	const EXPORT_TYPE_EMAIL_BATCH = 3;
	const EXPORT_TYPE_EMAIL_REALTIME = 4;

	protected $split_id;
	protected $client_export_id;
	protected $export_date;
	protected $export_type;
	protected $created;
	
	protected $name;
	protected $status;
	
	protected $num_records;
	protected $percent_complete;
	protected $is_running;
	protected $is_complete;
	protected $processing_log;
	
	protected $start_time;
	protected $end_time;
	protected $finding_records_time;
	protected $sending_records_time;
	
	protected $_status_name;
	protected $_split_name;
	protected $_client_name;
	protected $_client_export_name;
	protected $_client_id;
	protected $_export_type_name;
	
	private $client_export_id_array;
	private $split_id_array;
	private $client_export;
	private $split;

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
		if (trim($this->name) == '') {
			$this->name = $this->getSplitName() . ' on ' . date('m/d/Y', $this->getExportDate()->sec);
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
	 * Returns the _split_name
	 * @return string
	 */
	function getSplitName() {
		if (is_null($this->_split_name)) {
			$this->_split_name = $this->getSplit()->getName();
		}
		return $this->_split_name;
	}
	
	/**
	 * Returns the _client_export_name
	 * @return string
	 */
	function getClientExportName() {
		if (is_null($this->_client_export_name)) {
			$this->_client_export_name = $this->getClientExport()->getName();
		}
		return $this->_client_export_name;
	}
	
	/**
	 * Returns the _client_name
	 * @return string
	 */
	function getClientName() {
		if (is_null($this->_client_name)) {
			$this->_client_name = $this->getClientExport()->getClientName();
		}
		return $this->_client_name;
	}
	
	/**
	 * Returns the _client_id
	 * @return string
	 */
	function getClientId() {
		if (is_null($this->_client_id)) {
			$this->_client_id = $this->getClientExport()->getClientId();
		}
		return $this->_client_id;
	}
	
	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if (is_null($this->_status_name)) {
			$this->_status_name = self::retrieveStatuses()[$this->getStatus()];
		}
		return $this->_status_name;
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
	 * Returns the _export_type_name
	 * @return string
	 */
	function getExportTypeName() {
		if (is_null($this->_export_type_name)) {
			$this->_export_type_name = self::retrieveExportTypes()[$this->getExportType()];
		}
		return $this->_export_type_name;
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
	 * Returns the client_export_id
	 * @return integer
	 */
	function getClientExportId() {
		if (is_null($this->client_export_id)) {
			$this->client_export_id = 0;
		}
		return $this->client_export_id;
	}
	
	/**
	 * Sets the client_export_id
	 * @var integer
	 */
	function setClientExportId($arg0) {
		$this->client_export_id = (int)$arg0;
		$this->addModifiedColumn('client_export_id');
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
		return '/var/log/gun/export_queue.sh_' . $this->getId() . ".log";
	}
	
	/**
	 * Returns the client_export_id_array
	 * @return array
	 */
	function getClientExportIdArray() {
		if (is_null($this->client_export_id_array)) {
			$this->client_export_id_array = array();
		}
		return $this->client_export_id_array;
	}
	
	/**
	 * Sets the client_export_id_array
	 * @var array
	 */
	function setClientExportIdArray($arg0) {
		if (is_array($arg0)) {
			$this->client_export_id_array = $arg0;
			array_walk($this->client_export_id_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->client_export_id_array = explode(",", $arg0);
				array_walk($this->client_export_id_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->client_export_id_array = array((int)$arg0);
			}
		}
		return $this;
	}
	
	/**
	 * Returns the split_id_array
	 * @return array
	 */
	function getSplitIdArray() {
		if (is_null($this->split_id_array)) {
			$this->split_id_array = array();
		}
		return $this->split_id_array;
	}
	
	/**
	 * Sets the split_id_array
	 * @var array
	 */
	function setSplitIdArray($arg0) {
		if (is_array($arg0)) {
			$this->split_id_array = $arg0;
			array_walk($this->split_id_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->split_id_array = explode(",", $arg0);
				array_walk($this->split_id_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->split_id_array = array((int)$arg0);
			}
		}
		return $this;
	}
	
	/**
	 * Updates the log from the file on the file system
	 * @return boolean
	 */
	function updateLog() {
		if (file_exists($this->getLogFilename())) {
			// anything over 1MB only grab a tail of
			if (filesize($this->getLogFilename()) > 1073741824) {
				$cmd = 'tail -n100 ' . $this->getLogFilename();
				$log_file_contents = trim(shell_exec($cmd));
				$log_file_contents = \Mojavi\Util\StringTools::consoleToHtmlColor($log_file_contents);
				$log_file_contents = '<span style="color:white;background-color:black;">-- Only showing the last 100 lines --<br />&nbsp;</span>' . $log_file_contents;
				$this->setProcessingLog(nl2br($log_file_contents));
				$this->update();
				return true;
			} else {
				$log_file_contents = file_get_contents($this->getLogFilename());
				$log_file_contents = \Mojavi\Util\StringTools::consoleToHtmlColor($log_file_contents);
				$this->setProcessingLog($log_file_contents);
				$this->update();
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns the split_id
	 * @return integer
	 */
	function getSplitId() {
		if (is_null($this->split_id)) {
			$this->split_id = 0;
		}
		return $this->split_id;
	}
	
	/**
	 * Sets the split_id
	 * @var integer
	 */
	function setSplitId($arg0) {
		$this->split_id = (int)$arg0;
		$this->addModifiedColumn('split_id');
		return $this;
	}

	/**
	 * Returns the split
	 * @return \Gun\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Gun\Split();
			$this->split->setId($this->getSplitId());
			$this->split->query();
		}
		return $this->split;
	}

	/**
	 * Returns the client_export
	 * @return \Gun\ClientExport
	 */
	function getClientExport() {
		if (is_null($this->client_export)) {
			$this->client_export = new \Gun\ClientExport();
			$this->client_export->setId($this->getClientExportId());
			$this->client_export->query();
		}
		return $this->client_export;
	}

	/**
	 * Queries for a list of exports by client id
	 * @return \Gun\Export
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getClientExportId() > 0) {
			$criteria['client_export_id'] = $this->getClientExportId();
		}
		if ($this->getSplitId() > 0) {
			$criteria['split_id'] = $this->getSplitId();
		}
		if (count($this->getSplitIdArray()) > 0) {
			$criteria['split_id'] = array('$in' => $this->getSplitIdArray());
		}
		if (count($this->getClientExportIdArray()) > 0) {
			$criteria['client_export_id'] = array('$in' => $this->getClientExportIdArray());
		}
		return parent::queryAll($criteria, $hydrate);
	}
	
	/**
	 * Queries for a list of exports by client id
	 * @return \Gun\Export
	 */
	function queryBySplitAndClientExport(array $criteria = array()) {
		if ($this->getClientExportId() > 0) {
			$criteria['client_export_id'] = $this->getClientExportId();
		}
		if ($this->getSplitId() > 0) {
			$criteria['split_id'] = $this->getSplitId();
		}
		$criteria['is_complete'] = false;
		$criteria['is_running'] = false;
		return parent::query($criteria, false);
	}

	/**
	 * Returns an array of export statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::EXPORT_STATUS_ACTIVE => 'Active',
				self::EXPORT_STATUS_INACTIVE => 'Inactive',
				self::EXPORT_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns an array of export types
	 * @return multitype:string
	 */
	public static function retrieveExportTypes() {
		return array(
				self::EXPORT_TYPE_BATCH => 'Batch Export to an FTP Server',
				self::EXPORT_TYPE_REALTIME => 'Realtime Export to a POST url',
				self::EXPORT_TYPE_EMAIL_BATCH => 'Emailed as an attachment',
				self::EXPORT_TYPE_EMAIL_REALTIME => 'Emailed one at a time'
		);
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