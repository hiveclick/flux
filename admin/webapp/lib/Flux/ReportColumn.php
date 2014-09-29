<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class ReportColumn extends MongoForm {

	const REPORT_COLUMN_STATUS_ACTIVE = 1;
	const REPORT_COLUMN_STATUS_INACTIVE = 2;
	const REPORT_COLUMN_STATUS_DELETED = 3;

	const REPORT_COLUMN_TYPE_GROUP_DATAFIELD = 1;
	const REPORT_COLUMN_TYPE_CALCULATION = 2;
	const REPORT_COLUMN_TYPE_GROUP_EVENT_SUM = 3;

	const COLUMN_SUM_VALUE = 1;
	const COLUMN_MIN_VALUE = 2;
	const COLUMN_MAX_VALUE = 3;
	const COLUMN_COUNT_TOTAL = 4;

	const COLUMN_ADD_VALUE = 1;
	const COLUMN_SUBTRACT_VALUE = 2;
	const COLUMN_MULTIPLY_VALUE = 3;
	const COLUMN_DIVIDE_TOTAL = 4;

	const COLUMN_FORMAT_DEFAULT = 1;
	const COLUMN_FORMAT_PERCENTAGE = 2;
	const COLUMN_FORMAT_CURRENCY = 3;

	protected $name;
	protected $status;
	protected $column_type;
	protected $sum_type;
	protected $operator_type;
	protected $format_type;
	protected $parameters;

	protected $_status_name;
	protected $_column_type_name;
	protected $_sum_type_name;
	protected $_format_type_name;
	protected $_operator_type_name;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('report_column');
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
			$this->status = self::REPORT_COLUMN_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = $arg0;
		return $this;
	}

	/**
	 * Returns the _column_type_name
	 * @return string
	 */
	function getColumnTypeName() {
		if (is_null($this->_column_type_name)) {
			$this->_column_type_name = self::retrieveColumnTypes()[$this->getColumnType()];
		}
		return $this->_column_type_name;
	}

	/**
	 * Returns the column_type
	 * @return integer
	 */
	function getColumnType() {
		if (is_null($this->column_type)) {
			$this->column_type = self::REPORT_COLUMN_TYPE_CALCULATION;
		}
		return $this->column_type;
	}

	/**
	 * Sets the column_type
	 * @var integer
	 */
	function setColumnType($arg0) {
		$this->column_type = $arg0;
		return $this;
	}

	/**
	 * Returns the _sum_type_name
	 * @return string
	 */
	function getSumTypeName() {
		if (is_null($this->_sum_type_name)) {
			$this->_sum_type_name = self::retrieveSumTypes()[$this->getSumType()];
		}
		return $this->_sum_type_name;
	}

	/**
	 * Returns the sum_type
	 * @return integer
	 */
	function getSumType() {
		if (is_null($this->sum_type)) {
			$this->sum_type = self::COLUMN_SUM_VALUE;
		}
		return $this->sum_type;
	}

	/**
	 * Sets the sum_type
	 * @var integer
	 */
	function setSumType($arg0) {
		$this->sum_type = $arg0;
		return $this;
	}

	/**
	 * Returns the _operator_type_name
	 * @return string
	 */
	function getOperatorTypeName() {
		if (is_null($this->_operator_type_name)) {
			$this->_operator_type_name = self::retrieveOperatorTypes()[$this->getOperatorType()];
		}
		return $this->_operator_type_name;
	}

	/**
	 * Returns the operator_type
	 * @return integer
	 */
	function getOperatorType() {
		if (is_null($this->operator_type)) {
			$this->operator_type = self::COLUMN_ADD_VALUE;
		}
		return $this->operator_type;
	}

	/**
	 * Sets the operator_type
	 * @var integer
	 */
	function setOperatorType($arg0) {
		$this->operator_type = $arg0;
		return $this;
	}

	/**
	 * Returns the _format_type_name
	 * @return string
	 */
	function getFormatTypeName() {
		if (is_null($this->_format_type_name)) {
			$this->_format_type_name = self::retrieveFormatTypes()[$this->getFormatType()];
		}
		return $this->_format_type_name;
	}

	/**
	 * Returns the format_type
	 * @return integer
	 */
	function getFormatType() {
		if (is_null($this->format_type)) {
			$this->format_type = self::COLUMN_FORMAT_DEFAULT;
		}
		return $this->format_type;
	}

	/**
	 * Sets the format_type
	 * @var integer
	 */
	function setFormatType($arg0) {
		$this->format_type = $arg0;
		return $this;
	}

	/**
	 * Returns the parameters
	 * @return array
	 */
	function getParameters() {
		if (is_null($this->parameters)) {
			$this->parameters = array();
		}
		return $this->parameters;
	}

	/**
	 * Sets the parameters
	 * @var array
	 */
	function setParameters($arg0) {
		$this->parameters = $arg0;
		return $this;
	}

	/**
	 * Adds an item to the parameters array
	 * @return array
	 */
	function addParameter($name, $value) {
		$tmp_array = $this->getParameters();
		$tmp_array[$name] = $value;
		$this->setParameters($tmp_array);
		return $this;
	}

	/**
	 * Converts the operator to a mongo operation
	 * @param integer $operator_type
	 * @return string|NULL
	 */
	public static function convertOperatorTypeToMongo($operator_type) {
		switch($operator_type) {
			case self::COLUMN_ADD_VALUE:
				return '$add';
				break;
			case self::COLUMN_SUBTRACT_VALUE:
				return '$subtract';
				break;
			case self::COLUMN_MULTIPLY_VALUE:
				return '$multiply';
				break;
			case self::COLUMN_DIVIDE_TOTAL:
				return '$divide';
				break;
		}
		return null;
	}

	/**
	 * Returns the array of statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
			self::REPORT_COLUMN_STATUS_ACTIVE => 'Active',
			self::REPORT_COLUMN_STATUS_INACTIVE => 'Inactive',
			self::REPORT_COLUMN_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the array of column types
	 * @return multitype:string
	 */
	public static function retrieveColumnTypes() {
		return array(
			self::REPORT_COLUMN_TYPE_GROUP_DATAFIELD => 'Data Field',
			self::REPORT_COLUMN_TYPE_CALCULATION => 'Calculation',
			self::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM => 'Event Sum'
		);
	}

	/**
	 * Returns the array of sum types
	 * @return multitype:string
	 */
	public static function retrieveSumTypes() {
		return array(
			self::COLUMN_SUM_VALUE => 'Sum Value',
			self::COLUMN_MIN_VALUE => 'Min Value',
			self::COLUMN_MAX_VALUE => 'Max Value',
			self::COLUMN_COUNT_TOTAL => 'Count'
		);
	}

	/**
	 * Returns the array of operator types
	 * @return multitype:string
	 */
	public static function retrieveOperatorTypes() {
		return array(
			self::COLUMN_ADD_VALUE => 'Add',
			self::COLUMN_SUBTRACT_VALUE => 'Subtract',
			self::COLUMN_MULTIPLY_VALUE => 'Multiply',
			self::COLUMN_DIVIDE_TOTAL => 'Divide'
		);
	}

	/**
	 * Returns the array of format types
	 * @return multitype:string
	 */
	public static function retrieveFormatTypes() {
		return array(
			self::COLUMN_FORMAT_DEFAULT => 'Default',
			self::COLUMN_FORMAT_PERCENTAGE => 'Percentage',
			self::COLUMN_FORMAT_CURRENCY => 'Currency'
		);
	}

	/**
	 * Returns the offer based on the criteria
	 * @return Flux\Offer
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getName()) != '') {
			$criteria['name'] = $this->getName();
		}
		if (trim($this->getKeywords()) != '') {
			$criteria['$or'] = array(
					array('name' => new \MongoRegex("/" . $this->getKeywords() . "/i"))
			);
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Queries a report column by it's name
	 * @return Flux\ReportColumn
	 */
	function queryByName() {
		return $this->query(array('name' => $this->getName()), false);
	}
}
