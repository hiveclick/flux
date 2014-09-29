<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class Flow extends MongoForm {

	const FLOW_STATUS_ACTIVE = 1;
	const FLOW_STATUS_INACTIVE = 2;
	const FLOW_STATUS_DELETED = 3;

	const FLOW_NODE_TYPE_RULE_FIRST = 1;
	const FLOW_NODE_TYPE_RULE_WEIGHT = 2;
	const FLOW_NODE_TYPE_OFFER = 3;
	const FLOW_NODE_TYPE_FLOW = 4;
	const FLOW_NODE_TYPE_URL = 5;

	const FILTER_OPERATOR_IS = 1;
	const FILTER_OPERATOR_IS_NOT = 2;
	const FILTER_OPERATOR_IN = 3;
	const FILTER_OPERATOR_NOT_IN = 4;
	const FILTER_OPERATOR_STARTS_WITH = 5;
	const FILTER_OPERATOR_ENDS_WITH = 6;
	const FILTER_OPERATOR_CONTAINS = 7;
	const FILTER_OPERATOR_GREATER_THAN = 8;
	const FILTER_OPERATOR_GREATER_THAN_OR_EQUAL = 9;
	const FILTER_OPERATOR_LESS_THAN = 10;
	const FILTER_OPERATOR_LESS_THAN_OR_EQUAL = 11;
	const FILTER_OPERATOR_BETWEEN = 12;
	const FILTER_OPERATOR_EXISTS = 13;
	const FILTER_OPERATOR_NOT_EXISTS = 14;

	const FILTER_TYPE_STEP_COUNT = 1;
	const FILTER_TYPE_DEVICE = 2;
	const FILTER_TYPE_AFFID = 3;
	const FILTER_TYPE_S1 = 4;
	const FILTER_TYPE_OID = 5;

	const SETTER_OPERATOR_SET = 1;
	const SETTER_OPERATOR_UNSET = 2;
	const SETTER_OPERATOR_INC = 3;
	const SETTER_OPERATOR_DEC = 4;

	const CAP_TIME_SECOND = 1;
	const CAP_TIME_MINUTE = 2;
	const CAP_TIME_HOUR = 3;
	const CAP_TIME_DAY = 4;

	protected $name;
	protected $request_name;
	protected $status;
	protected $flow_nodes;

	protected $_status_name;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('flow');
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
	 * Returns the request_name
	 * @return string
	 */
	function getRequestName() {
		if (is_null($this->request_name)) {
			$this->request_name = "";
		}
		return $this->request_name;
	}

	/**
	 * Sets the request_name
	 * @var string
	 */
	function setRequestName($arg0) {
		$this->request_name = $arg0;
		$this->addModifiedColumn('request_name');
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
			$this->status = self::FLOW_STATUS_ACTIVE;
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
	 * Returns the flow_nodes
	 * @return array
	 */
	function getFlowNodes() {
		if (is_null($this->flow_nodes)) {
			$this->flow_nodes = array();
		}
		return $this->flow_nodes;
	}

	/**
	 * Sets the flow_nodes
	 * @var array
	 */
	function setFlowNodes($arg0) {
		$this->flow_nodes = $arg0;
		$this->addModifiedColumn('flow_nodes');
		return $this;
	}

	/**
	 * Returns an array of flow type names
	 * @return multitype:multitype:string
	 */
	public static function retrieveFlowTypeNames() {
		return array(
				self::FLOW_NODE_TYPE_RULE_FIRST => 'Rule',
				self::FLOW_NODE_TYPE_RULE_WEIGHT => 'Weighted Rule',
				self::FLOW_NODE_TYPE_OFFER => 'Offer',
				self::FLOW_NODE_TYPE_FLOW => 'Flow',
				self::FLOW_NODE_TYPE_URL => 'URL'
		);
	}

	/**
	 * Returns an array of cap times
	 * @return multitype:multitype:string
	 */
	public static function retrieveCapTimes() {
		return array(
				self::CAP_TIME_MINUTE => array(
						'name' => 'Minute',
						'seconds' => 60,
				),
				self::CAP_TIME_HOUR => array(
						'name' => 'Hour',
						'seconds' => 3600
				),
				self::CAP_TIME_DAY => array(
						'name' => 'Day',
						'seconds' => 86400
				)
		);
	}

	/**
	 * Returns an array of filter operators
	 * @return multitype:multitype:string
	 */
	public static function retrieveFilterOperators() {
		return array(
				self::FILTER_OPERATOR_IS => array(
						'name' => 'Is',
						'type' => '1'
				),
				self::FILTER_OPERATOR_IS_NOT => array(
						'name' => 'Is Not',
						'type' => '1'
				),
				self::FILTER_OPERATOR_IN => array(
						'name' => 'In',
						'type' => 'm'
				),
				self::FILTER_OPERATOR_NOT_IN => array(
						'name' => 'Not In',
						'type' => 'm'
				),
				self::FILTER_OPERATOR_STARTS_WITH => array(
						'name' => 'Starts With',
						'type' => '1'
				),
				self::FILTER_OPERATOR_ENDS_WITH => array(
						'name' => 'Ends With',
						'type' => '1'
				),
				self::FILTER_OPERATOR_CONTAINS => array(
						'name' => 'Contains',
						'type' => '1'
				),
				self::FILTER_OPERATOR_GREATER_THAN => array(
						'name' => '>',
						'type' => '1'
				),
				self::FILTER_OPERATOR_GREATER_THAN_OR_EQUAL => array(
						'name' => '>=',
						'type' => '1'
				),
				self::FILTER_OPERATOR_LESS_THAN => array(
						'name' => '<',
						'type' => '1'
				),
				self::FILTER_OPERATOR_LESS_THAN_OR_EQUAL => array(
						'name' => '<=',
						'type' => '1'
				),
				self::FILTER_OPERATOR_BETWEEN => array(
						'name' => 'Between',
						'type' => '2'
				),
				self::FILTER_OPERATOR_EXISTS => array(
						'name' => 'Exists',
						'type' => '0'
				),
				self::FILTER_OPERATOR_NOT_EXISTS => array(
						'name' => 'Does Not Exist',
						'type' => '0'
				)
		);
	}

	/**
	 * Returns an array of setter operators
	 * @return multitype:multitype:string
	 */
	public static function retrieveSetterOperators() {
		return array(
				self::SETTER_OPERATOR_SET => array(
						'name' => 'Set',
						'type' => '1'
				),
				self::SETTER_OPERATOR_UNSET => array(
						'name' => 'Unset',
						'type' => '1'
				),
				self::SETTER_OPERATOR_INC => array(
						'name' => 'Inc',
						'type' => '1'
				),
				self::SETTER_OPERATOR_DEC => array(
						'name' => 'Dec',
						'type' => '1'
				)
		);
	}

	/**
	 * Returns an array of flow statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::FLOW_STATUS_ACTIVE => 'Active',
				self::FLOW_STATUS_INACTIVE => 'Inactive',
				self::FLOW_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$flow = new self();
		$flow->getCollection()->ensureIndex(array('request_name' => 1), array('background' => true, 'unique' => true));
		$flow->getCollection()->ensureIndex(array('request_name' => 1, 'status' => 1), array('background' => true));
		return true;
	}
}
