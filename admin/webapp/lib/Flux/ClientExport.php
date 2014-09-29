<?php
namespace Flux;

use Mojavi\Form\MongoForm;
use Mojavi\Form\CommonForm;

class ClientExport extends MongoForm {

	const CLIENT_EXPORT_STATUS_ACTIVE = 1;
	const CLIENT_EXPORT_STATUS_INACTIVE = 2;
	const CLIENT_EXPORT_STATUS_DELETED = 3;

	const CLIENT_EXPORT_TYPE_BATCH = 1;
	const CLIENT_EXPORT_TYPE_REALTIME = 2;
	const CLIENT_EXPORT_TYPE_EMAIL = 3;
	const CLIENT_EXPORT_TYPE_EMAIL_REALTIME = 4;

	protected $name;
	protected $status;
	protected $realtime_status;
	protected $batch_status;
	protected $timezone;
	protected $notification_interval;
	protected $client_id;
	protected $success_msg;
	protected $export_type;
	protected $export_class_name;

	protected $email_address;
	
	protected $ftp_hostname;
	protected $ftp_port;
	protected $ftp_username;
	protected $ftp_password;
	protected $ftp_folder;

	protected $post_url;

	protected $mapping;
	protected $scheduling;

	protected $_status_name;
	protected $_client_name;
	protected $_export_type_name;

	private $client;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('client_export');
		$this->setDbName('admin');
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
	 * Returns the export_class_name
	 * @return string
	 */
	function getExportClassName() {
		if (is_null($this->export_class_name)) {
			$this->export_class_name = "";
		}
		return $this->export_class_name;
	}
	
	/**
	 * Sets the export_class_name
	 * @var string
	 */
	function setExportClassName($arg0) {
		$this->export_class_name = $arg0;
		$this->addModifiedColumn("export_class_name");
		return $this;
	}
	
	/**
	 * Returns the export_class_name
	 * @return string
	 */
	function getExportClass() {
		$class_name = $this->getExportClassName();
		if (trim($class_name) != '') {
			$class_name = '\\Flux\\Export\\' . $class_name;
			$ret_val = new $class_name();
			$ret_val->setClientExportId($this->getId());
		} else {
			$ret_val = new \Flux\Export\GenericFtp();
			$ret_val->setClientExportId($this->getId());
		}
		return $ret_val;
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
			$this->status = self::CLIENT_EXPORT_STATUS_ACTIVE;
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
	 * Returns the realtime_status
	 * @return integer
	 */
	function getRealtimeStatus() {
		if (is_null($this->realtime_status)) {
			$this->realtime_status = 0;
		}
		return $this->realtime_status;
	}

	/**
	 * Sets the realtime_status
	 * @var integer
	 */
	function setRealtimeStatus($arg0) {
		$this->realtime_status = (int)$arg0;
		$this->addModifiedColumn('realtime_status');
		return $this;
	}

	/**
	 * Returns the success_msg
	 * @return string
	 */
	function getSuccessMsg() {
		if (is_null($this->success_msg)) {
			$this->success_msg = "";
		}
		return $this->success_msg;
	}

	/**
	 * Sets the success_msg
	 * @var string
	 */
	function setSuccessMsg($arg0) {
		$this->success_msg = $arg0;
		$this->addModifiedColumn('success_msg');
		return $this;
	}

	/**
	 * Returns the batch_status
	 * @return integer
	 */
	function getBatchStatus() {
		if (is_null($this->batch_status)) {
			$this->batch_status = 0;
		}
		return $this->batch_status;
	}

	/**
	 * Sets the batch_status
	 * @var integer
	 */
	function setBatchStatus($arg0) {
		$this->batch_status = (int)$arg0;
		$this->addModifiedColumn('batch_status');
		return $this;
	}

	/**
	 * Returns the timezone
	 * @return string
	 */
	function getTimezone() {
		if (is_null($this->timezone)) {
			$this->timezone = \Flux\Timezone::getDefaultTimezone();
		}
		return $this->timezone;
	}

	/**
	 * Sets the timezone
	 * @var string
	 */
	function setTimezone($arg0) {
		$this->timezone = $arg0;
		$this->addModifiedColumn('timezone');
		return $this;
	}

	/**
	 * Returns the notification_interval
	 * @return integer
	 */
	function getNotificationInterval() {
		if (is_null($this->notification_interval)) {
			$this->notification_interval = 0;
		}
		return $this->notification_interval;
	}

	/**
	 * Sets the notification_interval
	 * @var integer
	 */
	function setNotificationInterval($arg0) {
		$this->notification_interval = (int)$arg0;
		$this->addModifiedColumn('notification_interval');
		return $this;
	}

	/**
	 * Returns the mapping
	 * @return array
	 */
	function getMapping() {
		if (is_null($this->mapping)) {
			$this->mapping = array();
		}
		return $this->mapping;
	}

	/**
	 * Sets the mapping
	 * @var array
	 */
	function setMapping($arg0) {
		$this->mapping = $arg0;
		array_walk($this->mapping, function(&$value, $key) {
			if (is_array($value)) {
				$item = new \Flux\ClientExportMap();
				$item->populate($value);
				$value = $item;
			}
		});
		$this->addModifiedColumn('mapping');
		return $this;
	}
	
	/**
	 * Returns the scheduling
	 * @return array
	 */
	function getScheduling() {
		if (is_null($this->scheduling)) {
			$this->scheduling = array();
		}
		return $this->scheduling;
	}
	
	/**
	 * Sets the scheduling
	 * @var array
	 */
	function setScheduling($arg0) {
		$this->scheduling = $arg0;
		$this->addModifiedColumn('scheduling');
		return $this;
	}

	/**
	 * Returns the _export_type_name
	 * @return string
	 */
	function getExportTypeName() {
		if (is_null($this->_export_type_name)) {
			$this->_export_type_name = $this->getExportClass()->getName();
		}
		return $this->_export_type_name;
	}

	/**
	 * Returns the export_type
	 * @return integer
	 */
	function getExportType() {
		if (is_null($this->export_type)) {
			$this->export_type = $this->getExportClass()->getClientExportType();
		}
		return $this->export_type;
	}
	
	/**
	 * Returns the email_address
	 * @return string
	 */
	function getEmailAddress() {
		if (is_null($this->email_address)) {
			$this->email_address = array();
		}
		return $this->email_address;
	}
	
	/**
	 * Sets the email_address
	 * @var string
	 */
	function setEmailAddress($arg0) {
		if (is_array($arg0)) {
			asort($arg0);
			$this->email_address = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->email_address = explode(",", $arg0);
			} else {
				$this->email_address = array($arg0);
			}
		}
		$this->addModifiedColumn('email_address');
		return $this;
	}

	/**
	 * Returns the post_url
	 * @return string
	 */
	function getPostUrl() {
		if (is_null($this->post_url)) {
			$this->post_url = "";
		}
		return $this->post_url;
	}

	/**
	 * Sets the post_url
	 * @var string
	 */
	function setPostUrl($arg0) {
		$this->post_url = $arg0;
		$this->addModifiedColumn('post_url');
		return $this;
	}

	/**
	 * Returns the ftp_hostname
	 * @return string
	 */
	function getFtpHostname() {
		if (is_null($this->ftp_hostname)) {
			$this->ftp_hostname = "";
		}
		return $this->ftp_hostname;
	}

	/**
	 * Sets the ftp_hostname
	 * @var string
	 */
	function setFtpHostname($arg0) {
		$this->ftp_hostname = $arg0;
		$this->addModifiedColumn('ftp_hostname');
		return $this;
	}

	/**
	 * Returns the ftp_port
	 * @return integer
	 */
	function getFtpPort() {
		if (is_null($this->ftp_port)) {
			$this->ftp_port = 21;
		}
		return $this->ftp_port;
	}

	/**
	 * Sets the ftp_port
	 * @var integer
	 */
	function setFtpPort($arg0) {
		$this->ftp_port = $arg0;
		$this->addModifiedColumn('ftp_port');
		return $this;
	}

	/**
	 * Returns the ftp_username
	 * @return string
	 */
	function getFtpUsername() {
		if (is_null($this->ftp_username)) {
			$this->ftp_username = "";
		}
		return $this->ftp_username;
	}

	/**
	 * Sets the ftp_username
	 * @var string
	 */
	function setFtpUsername($arg0) {
		$this->ftp_username = $arg0;
		$this->addModifiedColumn('ftp_username');
		return $this;
	}

	/**
	 * Returns the ftp_password
	 * @return string
	 */
	function getFtpPassword() {
		if (is_null($this->ftp_password)) {
			$this->ftp_password = "";
		}
		return $this->ftp_password;
	}

	/**
	 * Sets the ftp_password
	 * @var string
	 */
	function setFtpPassword($arg0) {
		$this->ftp_password = $arg0;
		$this->addModifiedColumn('ftp_password');
		return $this;
	}

	/**
	 * Returns the ftp_folder
	 * @return string
	 */
	function getFtpFolder() {
		if (is_null($this->ftp_folder)) {
			$this->ftp_folder = "";
		}
		return $this->ftp_folder;
	}

	/**
	 * Sets the ftp_folder
	 * @var string
	 */
	function setFtpFolder($arg0) {
		$this->ftp_folder = $arg0;
		$this->addModifiedColumn('ftp_folder');
		return $this;
	}

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
		$this->addModifiedColumn('client_id');
		return $this;
	}

	/**
	 * Returns the _client_name
	 * @return string
	 */
	function getClientName() {
		if (is_null($this->_client_name)) {
			$this->_client_name = $this->getClient()->getName();
		}
		return $this->_client_name;
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

	/**
	 * Queries for a list of exports by client id
	 * @return \Flux\Export
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getClientId() > 0) {
			$criteria['client_id'] = $this->getClientId();
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Queries for a list of exports by client id
	 * @return \Flux\Export
	 */
	function queryAllByClient() {
		return parent::queryAll(array('client_id' => $this->getClientId()));
	}

	/**
	 * Returns an array of export statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::CLIENT_EXPORT_STATUS_ACTIVE => 'Active',
				self::CLIENT_EXPORT_STATUS_INACTIVE => 'Inactive',
				self::CLIENT_EXPORT_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns an array of export types
	 * @return multitype:string
	 */
	public static function retrieveExportTypes() {
		return array(
				self::CLIENT_EXPORT_TYPE_BATCH => 'Batch Export to an FTP Server',
				self::CLIENT_EXPORT_TYPE_REALTIME => 'Realtime Export to a POST url',
				self::CLIENT_EXPORT_TYPE_EMAIL => 'Emailed as an attachment',
				self::CLIENT_EXPORT_TYPE_EMAIL_REALTIME => 'Emailed one at a time'
		);
	}

	/**
	 * Flushes the mapping
	 * @return Flux\ClientExport
	 */
	function flushMapping() {
		$this->setMapping(array());
		parent::addModifiedColumn('mapping');
		return $this->update();
	}
	
	/**
	 * Flushes the mapping
	 * @return Flux\ClientExport
	 */
	function flushSchedule() {
		$this->setScheduling(array());
		parent::addModifiedColumn('scheduling');
		return $this->update();
	}

	/**
	 * Tests an ftp connection
	 * @return boolean
	 */
	function testFtp() {
		if (trim($this->getFtpHostname()) != '') {
			if ((@$ftp_conn = ftp_connect($this->getFtpHostname(), $this->getFtpPort())) !== false) {
				if (@ftp_login($ftp_conn, $this->getFtpUsername(), $this->getFtpPassword())) {
					if (trim($this->getFtpFolder()) !== '') {
						if (!@ftp_chdir($ftp_conn, $this->getFtpFolder())) {
							throw new \Exception('We can login to the ftp server at ' . $this->getFtpHostname() . ', but the folder /' . $this->getFtpFolder() . ' does not exist.');
						}
					}
				} else {
					throw new \Exception('We can connect to the ftp server at ' . $this->getFtpHostname() . ', but cannot login to it.');
				}
			} else {
				throw new \Exception('Cannot connect to the ftp server at ' . $this->getFtpHostname());
			}
		} else {
			throw new \Exception('You did not enter a valid ftp hostname');
		}
	}
	
	/**
	 * Sends a lead (either to ftp or post url)
	 * @param $lead \Flux\Lead
	 * @return boolean
	 */
	function queueLead(\Flux\Lead $lead) {
		ob_start();
		\Mojavi\Util\StringTools::consoleWrite('Fulfilling Lead (' . $lead->getId() . ')', 'Finding', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		$this->sendLeads(array($lead));
		$ob_contents = ob_get_clean();
		return $ob_contents;
	}
	
	/**
	 * Sends a lead (either to ftp or post url)
	 * @param $lead array
	 * @return boolean
	 */
	function sendLeads(array $leads) {
		return $this->getExportClass()->send($leads);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$client_export = new self();
		$client_export->getCollection()->ensureIndex(array('client_id' => 1), array('background' => true));
		return true;
	}

}
