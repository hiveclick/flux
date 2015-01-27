<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class Split extends MongoForm {

	const SPLIT_STATUS_ACTIVE = 1;
	const SPLIT_STATUS_INACTIVE = 2;
	const SPLIT_STATUS_DELETED = 3;

	const SPLIT_SEND_TYPE_ALL = 1;
	const SPLIT_SEND_TYPE_ROTATE = 2;

	const SPLIT_NODE_TYPE_RULE_FIRST = 1;
	const SPLIT_NODE_TYPE_RULE = 2;
	const SPLIT_NODE_TYPE_EXPORT = 3;

	protected $name;
	protected $description;
	protected $status;

	protected $position1;
	protected $position2;
	protected $position3;
	protected $position4;

	protected $position;

	protected $send_type;
	protected $vertical_id;
	protected $offer_id;
	protected $domain_group_id;
	protected $data_field_id;
	
	protected $queue_count;
	protected $last_run_time;
	
	protected $__pid_split;
	protected $__pid_split_last;

	protected $_status_name;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('split');
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
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}

	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn('description');
		return $this;
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
			$this->status = self::SPLIT_STATUS_ACTIVE;
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
	 * Returns the send_type
	 * @return integer
	 */
	function getSendType() {
		if (is_null($this->send_type)) {
			$this->send_type = self::SPLIT_SEND_TYPE_ALL;
		}
		return $this->send_type;
	}

	/**
	 * Sets the send_type
	 * @var integer
	 */
	function setSendType($arg0) {
		$this->send_type = $arg0;
		$this->addModifiedColumn('send_type');
		return $this;
	}

	/**
	 * Returns the offer_id
	 * @return integer
	 */
	function getOfferId() {
		if (is_null($this->offer_id)) {
			$this->offer_id = array();
		}
		return $this->offer_id;
	}

	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		if (is_array($arg0)) {
			$this->offer_id = $arg0;
			array_walk($this->offer_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->offer_id = implode(',', $arg0);
				array_walk($this->offer_id, function(&$val, $key) { $val = (int)$val; });
			} else if (trim($arg0) == '') {
				$this->offer_id = array();
			} else {
				$this->offer_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('offer_id');
		return $this;
	}

	/**
	 * Returns the domain_group_id
	 * @return array
	 */
	function getDomainGroupId() {
		if (is_null($this->domain_group_id)) {
			$this->domain_group_id = array();
		}
		return $this->domain_group_id;
	}

	/**
	 * Sets the domain_group_id
	 * @var array
	 */
	function setDomainGroupId($arg0) {
		if (is_array($arg0)) {
			$this->domain_group_id = $arg0;
			array_walk($this->domain_group_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->domain_group_id = implode(',', $arg0);
				array_walk($this->domain_group_id, function(&$val, $key) { $val = (int)$val; });
			} else if (trim($arg0) == '') {
				$this->domain_group_id = array();
			} else {
				$this->domain_group_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('domain_group_id');
		return $this;
	}

	/**
	 * Returns the vertical_id
	 * @return array
	 */
	function getVerticalId() {
		if (is_null($this->vertical_id)) {
			$this->vertical_id = array();
		}
		return $this->vertical_id;
	}

	/**
	 * Sets the vertical_id
	 * @var array
	 */
	function setVerticalId($arg0) {
		if (is_array($arg0)) {
			$this->vertical_id = $arg0;
			array_walk($this->vertical_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->vertical_id = implode(',', $arg0);
				array_walk($this->vertical_id, function(&$val, $key) { $val = (int)$val; });
			} else {
				$this->vertical_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('vertical_id');
		return $this;
	}

	/**
	 * Returns the data_field_id
	 * @return array
	 */
	function getDataFieldId() {
		if (is_null($this->data_field_id)) {
			$this->data_field_id = array();
		}
		return $this->data_field_id;
	}

	/**
	 * Sets the data_field_id
	 * @var array
	 */
	function setDataFieldId($arg0) {
		if (is_array($arg0)) {
			$this->data_field_id = $arg0;
			array_walk($this->data_field_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->data_field_id = implode(',', $arg0);
				array_walk($this->data_field_id, function(&$val, $key) { $val = (int)$val; });
			} else if (trim($arg0) == '') {
				$this->data_field_id = array();
			} else {
				$this->data_field_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('data_field_id');
		return $this;
	}
	
	/**
	 * Returns the last_run_time
	 * @return string
	 */
	function getLastRunTime() {
		if (is_null($this->last_run_time)) {
			$this->last_run_time = new \MongoDate();
		}
		return $this->last_run_time;
	}
	
	/**
	 * Sets the last_run_time
	 * @var string
	 */
	function setLastRunTime($arg0) {
		$this->last_run_time = $arg0;
		$this->addModifiedColumn('last_run_time');
		return $this;
	}
	
	/**
	 * Returns the queue_count
	 * @return integer
	 */
	function getQueueCount() {
		if (is_null($this->queue_count)) {
			$this->queue_count = 0;
		}
		return $this->queue_count;
	}
	
	/**
	 * Sets the queue_count
	 * @var integer
	 */
	function setQueueCount($arg0) {
		$this->queue_count = $arg0;
		$this->addModifiedColumn("queue_count");
		return $this;
	}

	/**
	 * Returns the position
	 * @return integer
	 */
	function getPosition() {
		if (is_null($this->position)) {
			$this->position = 1;
		}
		return $this->position;
	}

	/**
	 * Sets the position
	 * @var integer
	 */
	function setPosition($arg0) {
		$this->position = $arg0;
		$this->addModifiedColumn('position');
		return $this;
	}

	/**
	 * Returns the position1
	 * @return array
	 */
	function getPosition1() {
		if (is_null($this->position1)) {
			/* @var $split_position \Flux\SplitPosition */
			$split_position = new \Flux\SplitPosition();
			$split_position->setSplitId($this->getId());
			$split_position->setPosition(1);
			$this->position1 = $split_position->queryAll();
		}
		return $this->position1;
	}

	/**
	 * Returns the position2
	 * @return array
	 */
	function getPosition2() {
		if (is_null($this->position2)) {
			/* @var $split_position \Flux\SplitPosition */
			$split_position = new \Flux\SplitPosition();
			$split_position->setSplitId($this->getId());
			$split_position->setPosition(2);
			$this->position2 = $split_position->queryAll();
		}
		return $this->position2;
	}

	/**
	 * Returns the position3
	 * @return array
	 */
	function getPosition3() {
		if (is_null($this->position3)) {
			/* @var $split_position \Flux\SplitPosition */
			$split_position = new \Flux\SplitPosition();
			$split_position->setSplitId($this->getId());
			$split_position->setPosition(3);
			$this->position3 = $split_position->queryAll();
		}
		return $this->position3;
	}

	/**
	 * Returns the position4
	 * @return array
	 */
	function getPosition4() {
		if (is_null($this->position4)) {
			/* @var $split_position \Flux\SplitPosition */
			$split_position = new \Flux\SplitPosition();
			$split_position->setSplitId($this->getId());
			$split_position->setPosition(4);
			$this->position4 = $split_position->queryAll();
		}
		return $this->position4;
	}

	/**
	 * Returns the array of campaign statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
			self::SPLIT_STATUS_ACTIVE => 'Active',
			self::SPLIT_STATUS_INACTIVE => 'Inactive',
			self::SPLIT_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the array of send types
	 * @return multitype:string
	 */
	public static function retrieveSendTypes() {
		return array(
			self::SPLIT_SEND_TYPE_ALL => 'All',
			self::SPLIT_SEND_TYPE_ROTATE => 'Rotate'
		);
	}
	
	/**
	 * Clears the pid status on the split to kickstart it
	 * @return boolean
	 */
	function clearPid() {
		return parent::update(array(), array('$unset' => array(
				'__pid_split' => 1,
				'__pid_time_split' => 1
		)));
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$split = new self();
		$split->getCollection()->ensureIndex(array('__pid_split' => 1), array('background' => true));
		$split->getCollection()->ensureIndex(array('name' => 1), array('background' => true));
		return true;
	}
}
