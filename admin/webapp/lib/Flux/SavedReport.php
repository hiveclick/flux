<?php
namespace Flux;

use \Mojavi\Form\MongoForm;

class SavedReport extends MongoForm {

	const REPORT_STATUS_ACTIVE = 1;
	const REPORT_STATUS_INACTIVE = 2;
	const REPORT_STATUS_DELETED = 3;

	const REPORT_TYPE_ADMIN_PUBLIC = 1;
	const REPORT_TYPE_ADMIN_PERSONAL = 2;
	const REPORT_TYPE_AFFILIATE = 3;

	protected $status;
	protected $type;
	protected $report_querystring;
	protected $user_id;
	protected $name;

	private $user;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->setCollectionName('savedReport');
		$this->setDbName('admin');
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::REPORT_STATUS_ACTIVE;
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
	 * Returns the type
	 * @return integer
	 */
	function getType() {
		if (is_null($this->type)) {
			$this->type = self::REPORT_TYPE_ADMIN_PUBLIC;
		}
		return $this->type;
	}

	/**
	 * Sets the type
	 * @var integer
	 */
	function setType($arg0) {
		$this->type = (int)$arg0;
		$this->addModifiedColumn('type');
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
	 * Returns the user_id
	 * @return integer
	 */
	function getUserId() {
		if (is_null($this->user_id)) {
			$this->user_id = 0;
		}
		return $this->user_id;
	}

	/**
	 * Sets the user_id
	 * @var integer
	 */
	function setUserId($arg0) {
		$this->user_id = (int)$arg0;
		$this->addModifiedColumn('user_id');
		return $this;
	}

	/**
	 * Returns the report_querystring
	 * @return string
	 */
	function getReportQuerystring() {
		if (is_null($this->report_querystring)) {
			$this->report_querystring = "";
		}
		return $this->report_querystring;
	}

	/**
	 * Sets the report_querystring
	 * @var string
	 */
	function setReportQuerystring($arg0) {
		$this->report_querystring = $arg0;
		$this->addModifiedColumn('report_querystring');
		return $this;
	}

	/**
	 * Returns the user
	 * @return \Flux\User
	 */
	function getUser() {
		if (is_null($this->user)) {
			$this->user = new \Flux\User();
			$this->user->setId($this->getUserId());
			$this->user->query();
		}
		return $this->user;
	}

	/**
	 * Helper function to return the list reports
	 */
	public static function retrieveReadableReports() {
		$saved_report = new \Flux\SavedReport();
		$saved_report->setSort('name');
		$saved_report->setSord('ASC');
		$saved_report->setIgnorePagination(true);
		return $saved_report->queryAll();
	}

	/**
	 * Helper function to return the list reports
	 */
	public static function retrieveUserReports($user_id) {
		$saved_report = new \Flux\SavedReport();
		$saved_report->setUserId($user_id);
		$saved_report->setSort('name');
		$saved_report->setSord('ASC');
		$saved_report->setIgnorePagination(true);
		return $saved_report->queryAll();
	}

	/**
	 * Returns the statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
			self::REPORT_STATUS_ACTIVE => 'Active',
			self::REPORT_STATUS_INACTIVE => 'Inactive',
			self::REPORT_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the report types
	 * @return multitype:string
	 */
	public static function retrieveTypes() {
		return array(
			self::REPORT_TYPE_ADMIN_PUBLIC => 'Admin Public',
			self::REPORT_TYPE_ADMIN_PERSONAL => 'Admin Personal',
			self::REPORT_TYPE_AFFILIATE => 'Affiliate'
		);
	}
}