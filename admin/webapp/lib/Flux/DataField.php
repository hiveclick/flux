<?php
namespace Flux;

use Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class DataField extends MongoForm {

	const DATA_FIELD_STATUS_ACTIVE = 1;
	const DATA_FIELD_STATUS_INACTIVE = 2;
	const DATA_FIELD_STATUS_DELETED = 3;

	/* SYSTEM fields cannot be edited nor populate from realtime */
	const DATA_FIELD_ACCESS_TYPE_SYSTEM = 1;
	/* RESERVED fields can be edited but not populated from realtime */
	const DATA_FIELD_ACCESS_TYPE_RESERVED = 2;
	/* PUBLIC are accessible to everything */
	const DATA_FIELD_ACCESS_TYPE_PUBLIC = 3;

	/* These types are for any dataField or event */
	const DATA_FIELD_TYPE_OFFER_ID = 1;
	const DATA_FIELD_TYPE_CLIENT_ID = 2;
	const DATA_FIELD_TYPE_STRING = 3;
	const DATA_FIELD_TYPE_INTEGER = 4;
	const DATA_FIELD_TYPE_FLOAT = 5;
	const DATA_FIELD_TYPE_CURRENCY = 6;
	const DATA_FIELD_TYPE_DATETIME = 7;
	const DATA_FIELD_TYPE_BOOLEAN = 8;
	const DATA_FIELD_TYPE_BIRTHDATE = 9;
	const DATA_FIELD_TYPE_STATE = 10;
	const DATA_FIELD_TYPE_ZIP = 11;
	const DATA_FIELD_TYPE_COUNTRY = 12;
	const DATA_FIELD_TYPE_URL = 13;
	const DATA_FIELD_TYPE_EMAIL = 14;
	const DATA_FIELD_TYPE_PHONE = 15;
	const DATA_FIELD_TYPE_GENDER = 16;
	const DATA_FIELD_TYPE_SOURCE_URL = 17;
	const DATA_FIELD_TYPE_MONGO_ID = 18;
	const DATA_FIELD_TYPE_IP = 19;
	const DATA_FIELD_TYPE_BROWSER = 20;
	const DATA_FIELD_TYPE_OBJECT = 21;
	const DATA_FIELD_TYPE_ARRAY = 22;

	/* Storage Types */
	const DATA_FIELD_STORAGE_TYPE_MAIN = 1;
	const DATA_FIELD_STORAGE_TYPE_DEFAULT = 2;
	const DATA_FIELD_STORAGE_TYPE_EVENT = 3;
	const DATA_FIELD_STORAGE_TYPE_TRACKING = 4;
	const DATA_FIELD_STORAGE_TYPE_DERIVED = 5;

	const DATA_FIELD_MODIFIER_SET = 1;
	const DATA_FIELD_MODIFIER_UNSET = 2;
	const DATA_FIELD_MODIFIER_INC = 3;
	const DATA_FIELD_MODIFIER_DEC = 4;
	const DATA_FIELD_MODIFIER_APPEND = 5;

	const DATA_FIELD_REF_USER_AGENT_BROWSER = 'uab';
	const DATA_FIELD_REF_USER_AGENT_VERSION = 'uav';
	const DATA_FIELD_REF_USER_AGENT_PLATFORM = 'uap';

	const DATA_FIELD_REF_IP = 'ip';
	const DATA_FIELD_REF_QUERY_STRING = 'qs';
	const DATA_FIELD_REF_REQUEST_URI = 'uri';
	const DATA_FIELD_REF_REFERER = 'referer';

	const DATA_FIELD_REF_OFFER_ID = '_o';
	const DATA_FIELD_REF_OFFER_NAME = '_on';
	const DATA_FIELD_REF_CAMPAIGN_ID = '_ca';
	const DATA_FIELD_REF_CAMPAIGN_KEY = '_ck';
	const DATA_FIELD_ID_NAME = '_id';
	const DATA_FIELD_REF_CLIENT_ID = '_c';
	const DATA_FIELD_REF_CLIENT_NAME = '_cn';
	const DATA_FIELD_AGG_CKID = '_ckid';
	const DATA_FIELD_EVENT_CREATED_NAME = '_cr';
	const DATA_FIELD_EVENT_UPDATED_NAME = '_up';

	const DATA_FIELD_EVENT_CONVERSION_NAME = 'conv';

	const DATA_FIELD_DEFAULT_CONTAINER = '_d';
	const DATA_FIELD_EVENT_CONTAINER = '_e';
	const DATA_FIELD_TRACKING_CONTAINER = '_t';

	protected $name;
	protected $description;
	protected $key_name;
	protected $request_name;
	protected $status;
	protected $access_type;
	protected $field_type;
	protected $storage_type;
	protected $report_group;
	protected $pixel_allowed;
	protected $parameters;
	protected $field_value;
	protected $tags;
	protected $data_field_set;
	protected $custom_code;

	protected $_storage_type_name;
	protected $_access_type_name;
	protected $_status_name;
	protected $_field_type_name;

	private static $__source_data_fields;
	private static $__active_data_fields;
	private static $__active_data_fields_by_request_name;
	private static $__active_data_fields_by_key_name;
	private static $__grouping_data_fields;
	private static $__external_data_fields;
	private static $__pixel_data_fields;
	private static $__active_mains;
	private static $__active_defaults;
	private static $__active_events;

	/* These fields are used for search */
	private $storage_type_array;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('data_field');
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
	 * Returns the custom_code
	 * @return string
	 */
	function getCustomCode() {
		if (is_null($this->custom_code)) {
			$this->custom_code = "";
		}
		return $this->custom_code;
	}
	
	/**
	 * Sets the custom_code
	 * @var string
	 */
	function setCustomCode($arg0) {
		$this->custom_code = $arg0;
		$this->addModifiedColumn("custom_code");
		return $this;
	}
	
	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = '';
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
	 * Returns the key_name
	 * @return string
	 */
	function getKeyName() {
		if (is_null($this->key_name)) {
			$this->key_name = "";
		}
		return $this->key_name;
	}

	/**
	 * Sets the key_name
	 * @var string
	 */
	function setKeyName($arg0) {
		$this->key_name = $arg0;
		$this->addModifiedColumn('key_name');
		return $this;
	}

	/**
	 * Returns the request_name
	 * @return string
	 */
	function getRequestName() {
		if (is_null($this->request_name)) {
			$this->request_name = array();
		}
		return $this->request_name;
	}

	/**
	 * Sets the request_name
	 * @var string
	 */
	function setRequestName($arg0) {
		if (is_array($arg0)) {
			$this->request_name = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->request_name = explode(",", $arg0);
			} else {
				$this->request_name = array($arg0);
			}
		}
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
			$this->status = self::DATA_FIELD_STATUS_ACTIVE;
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
		return $this->getFieldType();
	}

	/**
	 * Sets the type
	 * @var integer
	 */
	function setType($arg0) {
		return $this->setFieldType($arg0);
	}

	/**
	 * Returns the _field_type_name
	 * @return string
	 */
	function getFieldTypeName() {
		if (is_null($this->_field_type_name)) {
			$this->_field_type_name = self::retrieveFieldTypes()[$this->getFieldType()];
		}
		return $this->_field_type_name;
	}

	/**
	 * Returns the field_type
	 * @return integer
	 */
	function getFieldType() {
		if (is_null($this->field_type)) {
			$this->field_type = self::DATA_FIELD_TYPE_STRING;
		}
		return $this->field_type;
	}

	/**
	 * Sets the field_type
	 * @var integer
	 */
	function setFieldType($arg0) {
		$this->field_type = (int)$arg0;
		$this->addModifiedColumn('field_type');
		return $this;
	}

	/**
	 * Returns the tags
	 * @return array
	 */
	function getTags() {
		if (is_null($this->tags)) {
			$this->tags = array();
		}
		return $this->tags;
	}

	/**
	 * Sets the tags
	 * @var string|array
	 */
	function setTags($arg0) {
		if (is_array($arg0)) {
			$this->tags = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->tags = explode(",", $arg0);
			} else {
				$this->tags = array($arg0);
			}
		}
		array_walk($this->tags, function(&$value) { $value = strtolower($value); });
		$this->addModifiedColumn('tags');
		return $this;
	}

	/**
	 * Returns the _access_type_name
	 * @return string
	 */
	function getAccessTypeName() {
		if (is_null($this->_access_type_name)) {
			$this->_access_type_name = self::retrieveAccessTypes()[$this->getAccessType()];
		}
		return $this->_access_type_name;
	}

	/**
	 * Returns the access_type
	 * @return integer
	 */
	function getAccessType() {
		if (is_null($this->access_type)) {
			$this->access_type = self::DATA_FIELD_ACCESS_TYPE_PUBLIC;
		}
		return $this->access_type;
	}

	/**
	 * Sets the access_type
	 * @var integer
	 */
	function setAccessType($arg0) {
		$this->access_type = (int)$arg0;
		$this->addModifiedColumn('access_type');
		return $this;
	}

	/**
	 * Returns the _storage_type_name
	 * @return string
	 */
	function getStorageTypeName() {
		if (is_null($this->_storage_type_name)) {
			$this->_storage_type_name = self::retrieveStorageTypes()[$this->getStorageType()];
		}
		return $this->_storage_type_name;
	}

	/**
	 * Returns the storage_type
	 * @return integer
	 */
	function getStorageType() {
		if (is_null($this->storage_type)) {
			$this->storage_type = self::DATA_FIELD_STORAGE_TYPE_DEFAULT;
		}
		return $this->storage_type;
	}

	/**
	 * Sets the storage_type
	 * @var integer
	 */
	function setStorageType($arg0) {
		$this->storage_type = (int)$arg0;
		$this->addModifiedColumn('storage_type');
		return $this;
	}

	/**
	 * Returns the storage_type_array
	 * @return array
	 */
	function getStorageTypeArray() {
		if (is_null($this->storage_type_array)) {
			$this->storage_type_array = array();
		}
		return $this->storage_type_array;
	}

	/**
	 * Sets the storage_type_array
	 * @var array
	 */
	function setStorageTypeArray($arg0) {
		if (is_array($arg0)) {
			$this->storage_type_array = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->storage_type_array = explode(",", $arg0);
			} else {
				$this->storage_type_array = array($arg0);
			}
		}
		array_walk($this->storage_type_array, function(&$val) { $val = (int)$val; });
		return $this;
	}
	
	/**
	 * Returns the data_field_set
	 * @return array
	 */
	function getDataFieldSet() {
		if (is_null($this->data_field_set)) {
			$this->data_field_set = array();
		}
		return $this->data_field_set;
	}
	
	/**
	 * Sets the data_field_set
	 * @var array
	 */
	function setDataFieldSet($arg0) {
	    if (is_array($arg0)) {
	        array_walk($arg0, function(&$value) { 
	            $ret_val = new DataFieldSet(); 
	            $ret_val->populate($value);
	            $value = $ret_val;
	        });
            $this->data_field_set = $arg0;
            $this->addModifiedColumn("data_field_set");
	    } else if (is_string($arg0)) {
	        $tmp_obj = new DataFieldSet();
	        $tmp_obj->setValue($arg0);
	        $this->data_field_set = array($tmp_obj);
	        $this->addModifiedColumn("data_field_set");
	    }
		return $this;
	}

	/**
	 * Returns the report_group
	 * @return integer
	 */
	function getReportGroup() {
		if (is_null($this->report_group)) {
			$this->report_group = false;
		}
		return $this->report_group;
	}

	/**
	 * Sets the report_group
	 * @var integer
	 */
	function setReportGroup($arg0) {
		$this->report_group = (int)$arg0;
		$this->addModifiedColumn('report_group');
		return $this;
	}

	/**
	 * Returns the pixel_allowed
	 * @return boolean
	 */
	function getPixelAllowed() {
		if (is_null($this->pixel_allowed)) {
			$this->pixel_allowed = false;
		}
		return $this->pixel_allowed;
	}

	/**
	 * Sets the pixel_allowed
	 * @var boolean
	 */
	function setPixelAllowed($arg0) {
		$this->pixel_allowed = (int)$arg0;
		$this->addModifiedColumn('pixel_allowed');
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
		$this->addModifiedColumn('parameters');
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
	 * Returns the field_value
	 * @return string
	 */
	function getFieldValue() {
		if (is_null($this->field_value)) {
			$this->field_value = "";
		}
		return $this->field_value;
	}

	/**
	 * Sets the field_value
	 * @var string
	 */
	function setFieldValue($arg0) {
		$this->field_value = $arg0;
		$this->addModifiedColumn('field_value');
		return $this;
	}

	/**
	 * Returns whether the data field is a system field or not
	 * @return boolean
	 */
	public function isAccessTypeSystem() {
		if ($this->getAccessType() === self::DATA_FIELD_ACCESS_TYPE_SYSTEM) {
			return true;
		}
		return false;
	}

	/**
	 * Returns the storage type name
	 * @return string
	 */
	static function retrieveStorageTypes() {
		return array(
			self::DATA_FIELD_STORAGE_TYPE_MAIN => 'Base',
			self::DATA_FIELD_STORAGE_TYPE_DEFAULT => 'Default',
			self::DATA_FIELD_STORAGE_TYPE_EVENT => 'Event',
			self::DATA_FIELD_STORAGE_TYPE_TRACKING => 'Tracking',
			self::DATA_FIELD_STORAGE_TYPE_DERIVED => 'Derived'
		);
	}

	/**
	 * Returns the settable storage types
	 * @return multitype:string
	 */
	public static function retrieveSettableStorageTypes() {
		return array(
			self::DATA_FIELD_STORAGE_TYPE_DEFAULT => 'Default',
			self::DATA_FIELD_STORAGE_TYPE_EVENT => 'Event',
			self::DATA_FIELD_STORAGE_TYPE_TRACKING => 'Tracking',
			self::DATA_FIELD_STORAGE_TYPE_DERIVED => 'Derived'
		);
	}

	/**
	 * Returns the field type names
	 * @return string
	 */
	public static function retrieveFieldTypes() {
		return self::retrieveSettableTypes();
	}

	/**
	 * Alias for retrieveFieldTypes
	 * @return multitype:string
	 */
	public static function retrieveSettableTypes() {
		return array(
			self::DATA_FIELD_TYPE_STRING => 'String',
			self::DATA_FIELD_TYPE_INTEGER => 'Integer',
			self::DATA_FIELD_TYPE_FLOAT => 'Float',
			self::DATA_FIELD_TYPE_CURRENCY => 'Currency',
			self::DATA_FIELD_TYPE_DATETIME => 'Datetime',
			self::DATA_FIELD_TYPE_BOOLEAN => 'Boolean',
			self::DATA_FIELD_TYPE_BIRTHDATE => 'Birthdate',
			self::DATA_FIELD_TYPE_STATE => 'State',
			self::DATA_FIELD_TYPE_ZIP => 'Zip',
			self::DATA_FIELD_TYPE_COUNTRY => 'Country',
			self::DATA_FIELD_TYPE_URL => 'URL',
			self::DATA_FIELD_TYPE_EMAIL => 'Email',
			self::DATA_FIELD_TYPE_PHONE => 'Phone',
			self::DATA_FIELD_TYPE_GENDER => 'Gender',
			self::DATA_FIELD_TYPE_SOURCE_URL => 'Source URL',
			self::DATA_FIELD_TYPE_MONGO_ID => 'Mongo ID',
			self::DATA_FIELD_TYPE_IP => 'IP',
			self::DATA_FIELD_TYPE_BROWSER => 'Browser',
			self::DATA_FIELD_TYPE_OBJECT => 'Object',
			self::DATA_FIELD_TYPE_ARRAY => 'Array'
		);
	}

	/**
	 * Returns a list of statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
			self::DATA_FIELD_STATUS_ACTIVE => 'Active',
			self::DATA_FIELD_STATUS_INACTIVE => 'Inactive',
			self::DATA_FIELD_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the full list of access types
	 * @return multitype:string
	 */
	public static function retrieveAccessTypes() {
		return array(
			self::DATA_FIELD_ACCESS_TYPE_SYSTEM => 'System',
			self::DATA_FIELD_ACCESS_TYPE_RESERVED => 'Reserved',
			self::DATA_FIELD_ACCESS_TYPE_PUBLIC => 'Public',
		);
	}

	/**
	 * Returns the settable list of access types
	 * @return multitype:string
	 */
	public static function retrieveSettableAccessTypes() {
		return array(
			self::DATA_FIELD_ACCESS_TYPE_SYSTEM => 'System',
			self::DATA_FIELD_ACCESS_TYPE_RESERVED => 'Reserved',
			self::DATA_FIELD_ACCESS_TYPE_PUBLIC => 'Public',
		);
	}

	/**
	 * Returns the list of modifiers
	 * @return multitype:string
	 */
	public static function retrieveModifiers() {
		return array(
			self::DATA_FIELD_MODIFIER_SET => 'Set',
			self::DATA_FIELD_MODIFIER_UNSET => 'Unset',
			self::DATA_FIELD_MODIFIER_INC => 'Add',
			self::DATA_FIELD_MODIFIER_DEC => 'Subtract',
			self::DATA_FIELD_MODIFIER_APPEND => 'Append'
		);
	}

	/**
	 * Returns the value for a parameter on this DataField
	 * @param string $parameter_name
	 * @return string
	 */
	public function retrieveParameterValue($parameter_name) {
		if (array_key_exists($parameter_name, $this->getParameters())) {
			if (isset($this->parameters[$parameter_name]['field_value'])) {
				return $this->parameters[$parameter_name]['field_value'];
			}
		}
		return null;
	}

	/**
	 * Retrieves the additional parameters
	 * @return multitype:\Flux\dataFieldParameter
	 */
	public function retrieveAdditionalParameters() {
		$additional_parameter = array();
		switch($this->getFieldType()) {
			case self::DATA_FIELD_TYPE_BROWSER:
				$dataFieldParameter = new \Flux\DataFieldParameter();
				$dataFieldParameter->setName('Browser Data Field');
				$dataFieldParameter->setFieldName('browser');
				$dataFieldParameter->setType(\Flux\DataFieldParameter::PARAMETER_TYPE_DATA_FIELD);
				$dataFieldParameter->setRequired(false);
				$dataFieldParameter->setFieldValue($this->retrieveParameterValue('browser'));
				$additional_parameter[] = $dataFieldParameter;

				$dataFieldParameter = new \Flux\DataFieldParameter();
				$dataFieldParameter->setName('Platform Data Field');
				$dataFieldParameter->setFieldName('platform');
				$dataFieldParameter->setType(\Flux\DataFieldParameter::PARAMETER_TYPE_DATA_FIELD);
				$dataFieldParameter->setRequired(false);
				$dataFieldParameter->setFieldValue($this->retrieveParameterValue('platform'));
				$additional_parameter[] = $dataFieldParameter;

				$dataFieldParameter = new \Flux\DataFieldParameter();
				$dataFieldParameter->setName('Version Data Field');
				$dataFieldParameter->setFieldName('version');
				$dataFieldParameter->setType(\Flux\DataFieldParameter::PARAMETER_TYPE_DATA_FIELD);
				$dataFieldParameter->setRequired(false);
				$dataFieldParameter->setFieldValue($this->retrieveParameterValue('version'));
				$additional_parameter[] = $dataFieldParameter;

				break;
		}
		return $additional_parameter;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	/**
	 * Queries a report column by it's name
	 * @return Flux\ReportColumn
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (count($this->getStorageTypeArray()) > 0) {
			$criteria['storage_type'] = array('$in' => $this->getStorageTypeArray());
		}

		if (trim($this->getKeywords()) != '') {
			$criteria['$or'] = array(
					array('name' => new \MongoRegex("/" . $this->getKeywords() . "/i")),
					array('tags' =>  new \MongoRegex("/" . $this->getKeywords() . "/i")),
					array('request_name' => new \MongoRegex("/" . $this->getKeywords() . "/i")),
			        array('key_name' => new \MongoRegex("/" . $this->getKeywords() . "/i"))
			);
		}
		if (!empty($this->getTags())) {
			$criteria['tags'] = array('$in' => $this->getTags());
		}
		return parent::queryAll($criteria, $hydrate);
	}


	/**
	 * Queries a data field by it's name
	 * @return Flux\DataField
	 */
	function queryByName() {
		return $this->query(array('name' => $this->getName()), false);
	}
	
	/**
	 * Queries a data field by it's name
	 * @return Flux\DataField
	 */
	function queryByKeyName() {
		return $this->query(array('key_name' => $this->getKeyName()), false);
	}

	/**
	 * Returns the full array of DataFields
	 * @param string $requery
	 * @return array
	 */
	public static function retrieveDataFields($requery = false) {
		if (is_null(self::$__source_data_fields) || ($requery === true)) {
			$data_field = new DataField();
			$data_field->setIgnorePagination(true);
			self::$__source_data_fields = $data_field->queryAll();
		}
		return self::$__source_data_fields;
	}

	/**
	 * Returns an array of the active events
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveEvents($requery = false) {
		if (is_null(self::$__active_events) || ($requery === true)) {
			self::$__active_events = array();
			foreach(self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if (
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('storage_type') === self::DATA_FIELD_STORAGE_TYPE_EVENT)
				) {
					self::$__active_events[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__active_events;
	}

	/**
	 * Returns a list of active data fields
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDataFields($requery = false) {
		if (is_null(self::$__active_data_fields) || ($requery === true)) {
			self::$__active_data_fields = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if ($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE) {
					self::$__active_data_fields[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__active_data_fields;
	}

	/**
	 * Returns a list of active data fields
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDataFieldsById($requery = false) {
		if(is_null(self::$__active_data_fields) || ($requery === true)) {
			self::$__active_data_fields = array();
			foreach (self::retrieveDataFields($requery) AS $dataField) {
				if ($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE) {
					self::$__active_data_fields[$dataField->getId()] = $dataField;
				}
			}
		}
		return self::$__active_data_fields;
	}

	/**
	 * Returns a list of active public data fields
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActivePublicDataFields($requery = false) {
		if(is_null(self::$__external_data_fields) || ($requery === true)) {
			self::$__external_data_fields = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if (
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('access_type') === self::DATA_FIELD_ACCESS_TYPE_PUBLIC)
				) {
					self::$__external_data_fields[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__external_data_fields;
	}

	/**
	 * Returns a list of active data fields keyed by request name
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDataFieldsByRequestName($requery = false) {
		if(is_null(self::$__active_data_fields_by_request_name) || ($requery === true)) {
			self::$__active_data_fields_by_request_name = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if ($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE) {
					// Request names are comma delimited such as firstname, first_name, fname
					self::$__active_data_fields_by_request_name[$dataField->getKeyName()] = $dataField;
					foreach ($dataField->getRequestName() as $request_name) {
						self::$__active_data_fields_by_request_name[$request_name] = $dataField;
					}
				}
			}
		}
		return self::$__active_data_fields_by_request_name;
	}

	/**
	 * Returns a list of active data fields keyed by request name
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDataFieldsByKeyName($requery = false) {
		if(is_null(self::$__active_data_fields_by_key_name) || ($requery === true)) {
			self::$__active_data_fields_by_key_name = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if ($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE) {
					// Request names are comma delimited such as firstname, first_name, fname
					self::$__active_data_fields_by_key_name[$dataField->getKeyName()] = $dataField;
				}
			}
		}
		return self::$__active_data_fields_by_key_name;
	}

	/**
	 * Returns a list of active grouping data fields
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveGroupingDataFields($requery = false) {
		if(is_null(self::$__grouping_data_fields) || ($requery === true)) {
			self::$__grouping_data_fields = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if (
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('report_group') === true)
				) {
					self::$__grouping_data_fields[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__grouping_data_fields;
	}

	/**
	 * Returns a list of active main storage types
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveMains($requery = false) {
		if(is_null(self::$__active_mains) || ($requery === true)) {
			self::$__active_mains = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if(
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('storage_type') === self::DATA_FIELD_STORAGE_TYPE_MAIN)
				) {
					self::$__active_mains[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__active_mains;
	}

	/**
	 * Returns a list of active default storage types
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDefaults($requery = false) {
		if(is_null(self::$__active_defaults) || ($requery === true)) {
			self::$__active_defaults = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if(
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('storage_type') === self::DATA_FIELD_STORAGE_TYPE_DEFAULT)
				) {
					self::$__active_defaults[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__active_defaults;
	}

	/**
	 * Returns a list of active pixel placeholder data fields
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActivePixelDataFields($requery = false) {
		if(is_null(self::$__pixel_data_fields) || ($requery === true)) {
			self::$__pixel_data_fields = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if(
				($dataField->retrieveValue('status') === self::DATA_FIELD_STATUS_ACTIVE)
				&& ($dataField->retrieveValue('pixel_allowed') === true)
				) {
					self::$__pixel_data_fields[$dataFieldId] = $dataField;
				}
			}
		}
		return self::$__pixel_data_fields;
	}

	/**
	 * Returns a data field from it's request name
	 * @param string $dataFieldRequestName
	 * @return \Flux\DataField
	 */
	public static function retrieveDataFieldFromName($dataFieldRequestName) {
		if (array_key_exists($dataFieldRequestName, self::retrieveActiveDataFieldsByRequestName())) {
			return self::retrieveActiveDataFieldsByRequestName()[$dataFieldRequestName];
		}
		return null;
	}

	/**
	 * Returns a data field from it's id
	 * @param string $dataFieldRequestName
	 * @return \Flux\DataField
	 * @deprecated
	 */
	public static function retrieveDataFieldFromKeyName($dataFieldKeyName) {
		$dataFields = self::retrieveActiveDataFieldsByKeyName();
		if (array_key_exists($dataFieldKeyName, $dataFields)) {
			return $dataFields[$dataFieldKeyName];
		}
		return null;
	}

	/**
	 * Returns a data field from it's id
	 * @param string $dataFieldRequestName
	 * @return \Flux\DataField
	 * @deprecated
	 */
	public static function retrieveDataFieldFromId($dataFieldId) {
		$dataFields = self::retrieveActiveDataFieldsById();
		if (array_key_exists($dataFieldId, $dataFields)) {
			return $dataFields[$dataFieldId];
		}
		return null;
	}

	/**
	 * Returns an object from the id using a cache
	 * @param integer $id
	 * @return \Flux\DomainGroup
	 */
	public static function retrieveById($id) {
		foreach (self::retrieveActiveDataFieldsByKeyName() as $data_field) {
			if ($data_field->getId() == $id) {
				return $data_field;
			}
		}
		return new \Flux\DataField();
	}

	/**
	 * Returns the pixel placeholders
	 * @param string $requery
	 * @return multitype:multitype:NULL
	 */
	public static function getPixelPlaceholders($requery = false) {
		$pixel_placeholders = array();
		foreach(self::retrieveActivePixelDataFields() AS $dataField) {
			foreach ($dataField->getRequestName() as $request_name) {
				$pixel_placeholders[] = array(
					'request_name' => $request_name
				);
			}
		}
		return $pixel_placeholders;
	}

	/**
	 * Returns an array of data fields that support url placeholder replacement
	 * @param string $requery
	 * @return multitype:multitype:NULL
	 */
	public static function getUrlPlaceholders($requery = false) {
		$url_placeholders = array();
		$url_placeholders[] = array('request_name' => self::DATA_FIELD_AGG_CKID);
		$url_placeholders[] = array('request_name' => self::DATA_FIELD_REF_CLIENT_ID);
		$url_placeholders[] = array('request_name' => self::DATA_FIELD_REF_CAMPAIGN_ID);
		$url_placeholders[] = array('request_name' => self::DATA_FIELD_REF_OFFER_ID);
		$url_placeholders[] = array('request_name' => self::DATA_FIELD_ID_NAME);

		/*
		 const DATA_FIELD_REF_CAMPAIGN_KEY = '__c';
		*/

		foreach(self::retrieveActiveDataFields() AS $dataField) {
			foreach ($dataField->getRequestName() as $request_name) {
				$url_placeholders[] = array(
					'request_name' => $request_name
				);
			}
		}
		return $url_placeholders;
	}

	/**
	 * Returns an array of types that can increment and decrement
	 * @return multitype:string
	 */
	public static function typesCanIncDec() {
		return array(
			self::DATA_FIELD_TYPE_INTEGER,
			self::DATA_FIELD_TYPE_FLOAT,
			self::DATA_FIELD_TYPE_CURRENCY,
			self::DATA_FIELD_TYPE_DATETIME,
			self::DATA_FIELD_TYPE_BOOLEAN,
			self::DATA_FIELD_TYPE_OBJECT
		);
	}

	/**
	 * Returns an array of types that support the min/max feature
	 * @return multitype:string
	 */
	public static function typesCanMaxMin() {
		return array(
			self::DATA_FIELD_TYPE_INTEGER,
			self::DATA_FIELD_TYPE_FLOAT,
			self::DATA_FIELD_TYPE_CURRENCY,
			self::DATA_FIELD_TYPE_DATETIME,
			self::DATA_FIELD_TYPE_BOOLEAN,
			self::DATA_FIELD_TYPE_OBJECT
		);
	}

	/**
	 * Returns an arry of US states
	 * @return multitype:string
	 */
	public static function getStates() {
		return array(
			'AL' => "Alabama",
			'AK' => "Alaska",
			'AZ' => "Arizona",
			'AR' => "Arkansas",
			'CA' => "California",
			'CO' => "Colorado",
			'CT' => "Connecticut",
			'DE' => "Delaware",
			'DC' => "District Of Columbia",
			'FL' => "Florida",
			'GA' => "Georgia",
			'GU' => "Guam",
			'HI' => "Hawaii",
			'ID' => "Idaho",
			'IL' => "Illinois",
			'IN' => "Indiana",
			'IA' => "Iowa",
			'KS' => "Kansas",
			'KY' => "Kentucky",
			'LA' => "Louisiana",
			'ME' => "Maine",
			'MD' => "Maryland",
			'MA' => "Massachusetts",
			'MI' => "Michigan",
			'MN' => "Minnesota",
			'MS' => "Mississippi",
			'MO' => "Missouri",
			'MT' => "Montana",
			'NE' => "Nebraska",
			'NV' => "Nevada",
			'NH' => "New Hampshire",
			'NJ' => "New Jersey",
			'NM' => "New Mexico",
			'NY' => "New York",
			'NC' => "North Carolina",
			'ND' => "North Dakota",
			'OH' => "Ohio",
			'OK' => "Oklahoma",
			'OR' => "Oregon",
			'PA' => "Pennsylvania",
			'PR' => "Puerto Rico",
			'RI' => "Rhode Island",
			'SC' => "South Carolina",
			'SD' => "South Dakota",
			'TN' => "Tennessee",
			'TX' => "Texas",
			'UT' => "Utah",
			'VT' => "Vermont",
			'VA' => "Virginia",
			'VI' => "Virgin Islands",
			'WA' => "Washington",
			'WV' => "West Virginia",
			'WI' => "Wisconsin",
			'WY' => "Wyoming"
		);
	}

	/* VALIDATION AND CLEANING CLASSES */
	/**
	 * Validates a value and changes it, if necessary
	 * @param string $dataValue
	 * @param \FluxFE\Lead $lead
	 * @return boolean
	 */
	public function doValidationAndFormat(&$dataValue, $lead) {
		$result = true;

		switch($this->getFieldType()) {
			case self::DATA_FIELD_TYPE_MONGO_ID:
				$result = $this->doValidateMongoId($dataValue);
				break;
			case self::DATA_FIELD_TYPE_OFFER_ID:
			case self::DATA_FIELD_TYPE_CLIENT_ID:
			case self::DATA_FIELD_TYPE_INTEGER:
				$result = $this->doValidateInteger($dataValue);
				break;
			case self::DATA_FIELD_TYPE_STRING:
				$result = $this->doValidateString($dataValue);
				break;
			case self::DATA_FIELD_TYPE_FLOAT:
				$result = $this->doValidateFloat($dataValue);
				break;
			case self::DATA_FIELD_TYPE_CURRENCY:
				$result = $this->doValidateCurrency($dataValue);
				break;
			case self::DATA_FIELD_TYPE_DATETIME:
				$result = $this->doValidateDatetime($dataValue);
				break;
			case self::DATA_FIELD_TYPE_BOOLEAN:
				$result = $this->doValidateBoolean($dataValue);
				break;
			case self::DATA_FIELD_TYPE_BIRTHDATE:
				$result = $this->doValidateBirthdate($dataValue);
				break;
			case self::DATA_FIELD_TYPE_URL:
				$result = $this->doValidateUrl($dataValue);
				break;
			case self::DATA_FIELD_TYPE_SOURCE_URL:
				$result = $this->doValidateSourceUrl($dataValue);
				break;
			case self::DATA_FIELD_TYPE_STATE:
				$result = $this->doValidateState($dataValue);
				break;
			case self::DATA_FIELD_TYPE_ZIP:
				$result = $this->doValidateZip($dataValue);
				break;
			case self::DATA_FIELD_TYPE_COUNTRY:
				$result = $this->doValidateCountry($dataValue);
				break;
			case self::DATA_FIELD_TYPE_EMAIL:
				$result = $this->doValidateEmail($dataValue);
				break;
			case self::DATA_FIELD_TYPE_GENDER:
				$result = $this->doValidateGender($dataValue);
				break;
			case self::DATA_FIELD_TYPE_PHONE:
				$result = $this->doValidatePhone($dataValue);
				break;
			case self::DATA_FIELD_TYPE_IP:
				$result = $this->doValidateIp($dataValue);
				break;
			case self::DATA_FIELD_TYPE_BROWSER:
				$result = $this->doValidateBrowser($dataValue, $lead);
				break;
			case self::DATA_FIELD_TYPE_OBJECT:
				$result = $this->doValidateObject($dataValue);
				break;
			case self::DATA_FIELD_TYPE_ARRAY:
				$result = $this->doValidateArray($dataValue);
				break;
			default:
				$result = true;
				break;
		}
		return $result;
	}

	/**
	 * Validates and formats the value as a string
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateArray(&$dataValue) {
		if (is_array($dataValue)) {
			return true;
		} else if (is_string($dataValue)) {
			if (strpos($dataValue, ',') !== false) {
				$dataValue = explode(",", $dataValue);
			} else {
				$dataValue = array($dataValue);
			}
		}
		return true;
	}
	
	/**
	 * Validates and formats the value as a string
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateString(&$dataValue) {
		if (is_array($dataValue)) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Array passed to string validation: " . var_export($dataValue, true)); }
		$dataValue = trim((string) $dataValue);
		return true;
	}

	/**
	 * Validates and formats the value as an integer
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateInteger(&$dataValue) {
		$dataValue = (int) $dataValue;
		return true;
	}

	/**
	 * Validates and formats the value as a float
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateFloat(&$dataValue) {
		$dataValue = (int) $dataValue;
		return true;
	}

	/**
	 * Validates and formats the value as currency
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateCurrency(&$dataValue) {
		$dataValue = number_format($dataValue, 2);
		return true;
	}

	/**
	 * Validates and formats the value as a datetime
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateDatetime(&$dataValue) {
		if ($dataValue instanceof \MongoDate) {
			return true;
		}

		$timeValue = strtotime($dataValue);
		if ($timeValue === false) {
			return false;
		}
		$dataValue = new \MongoDate($timeValue);
		return true;
	}

	/**
	 * Validates and formats the value as a boolean
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateBoolean(&$dataValue) {
		if(is_string($dataValue)) {
			$dataValue = strtolower(trim($dataValue));
		}
		if(in_array($dataValue, array('t', 'true', 1, '1', 'y', 'yes', true))) {
			$dataValue = true;
			return true;
		} elseif(in_array($dataValue, array('f', 'false', 0, '0', 'n', 'no', false))) {
			$dataValue = false;
			return true;
		}
		return false;
	}

	/**
	 * Validates and formats the value as a birthdate
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateBirthdate(&$dataValue) {
		$dateReturn = $this->doValidateDatetime($dataValue);

		if (($dateReturn === true) && ($dataValue instanceof \MongoDate)) {
			//I put a maxTime in of 1 year just in case a time machine is invented that allows for minimal time travel of one year
			//Okay, really it's because I figured perhaps "expecting mother" data may use this field for baby.
			$minTime = strtotime("-150 YEAR");
			$maxTime = strtotime("+1 YEAR");
			$birthdateTime = $dataValue->sec;

			if(($birthdateTime > $minTime) && ($birthdateTime < $maxTime)) {
				return true;
		}
	}
	return false;
	}

	/**
	 * Validates and formats the value as a url
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateUrl(&$dataValue) {
		$dataValue = strtolower(trim((string) $dataValue));
		return true;
	}

	/**
	 * Validates and formats the value as a source url
	 * @param string $dataValue
	 * @return boolean
	 */
	 public function doValidateSourceUrl(&$dataValue) {
		$result = $this->doValidateUrl($dataValue);
		if ($result === true) {
			// @todo: trim off extra stuff after first slash, and possibly remove http/s
			return true;
		}
		return false;
	}

	/**
	 * Validates and formats the value as an email
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateEmail(&$dataValue) {
		$dataValue = strtolower(trim((string) $dataValue));
		return (filter_var($dataValue, FILTER_VALIDATE_EMAIL)) ? true : false;
	}

	/**
	 * Validates and formats the value as a phone
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidatePhone(&$dataValue) {
		$dataValue = preg_replace("/[^0-9]/", "", (string) $dataValue);
		/* @TODO: Implement country checks so we can get CA/UK phones, etc. */

		if (strlen($dataValue) == 11 && substr($dataValue, 0, 1) == 1) {
			$dataValue = substr($dataValue, 1, 10);
		}

		//check for crap 800 number area codes
		$areaCode = (int)substr($dataValue, 0, 3);
		$badAreaCodes = array(800, 866, 877, 888);
		if (strlen($dataValue) != 10 || in_array($areaCode, $badAreaCodes) ) {
			return false;
		}

		//check for valid US phone format
		$match_count = preg_match("/^[2-9][0-8][0-9][2-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", $dataValue);
		if ($match_count <= 0) {
			return false;
		}

		return true;
	}

	/**
	 * Validates and formats the value as a state
	 * @param string $dataValue
	 * @return boolean
	 */
	 public function doValidateState(&$dataValue) {
		$dataValue = strtoupper(trim((string) $dataValue));
		//@todo: make this more robust
		//probably won't change this method until we actually get DataField "sets" working
		if (array_key_exists($dataValue, self::getStates())) {
		   return true;
		}
		return false;
	}

	/**
	 * Validates and formats the value as a zipcode
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateZip(&$dataValue) {
		$dataValue = (string) $dataValue;
		/* @TODO: Implement country checks so we can get CA zips, etc. */
		/*
		if ((strlen($dataValue) >= 3) && (strlen($dataValue) <= 4)) {
		$dataValue = str_pad($dataValue, 5, "0", STR_PAD_LEFT);
		}
		 */
		/*
		//this would screw up CA zips, so don't do it until we have country check
		if (strlen($dataValue) > 5) {
		$dataValue = substr($dataValue, 0, 5);
		}
		return preg_match('/^\d{5}(-\d{4})?$/', $dataValue);
		*/

		//all other countries just return true
		return true;
	}

	/**
	* Validates and formats the value as a country
	* @param string $dataValue
	* @return boolean
	*/
	public function doValidateCountry(&$dataValue) {
		$dataValue = strtoupper(trim((string) $dataValue));
		return true;
	}

	/**
	 * Validates and formats the value as gender
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateGender(&$dataValue) {
		$dataValue = strtolower(trim((string) $dataValue));
		if (in_array($dataValue, array('m', 'male', 1))) {
			$dataValue = 'm';
			return true;
		} elseif(in_array($dataValue, array('f', 'female', 0))) {
			$dataValue = 'f';
			return true;
		}
		return false;
	}

	/**
	* Validates and formats the value as a Mongo Id
	* @param string $dataValue
	* @return boolean
	*/
	public function doValidateMongoId(&$dataValue) {
		//@todo: force this to be a mongoid without throwing exception
		return true;
	}

	/**
	 * Validates and formats the value as an IP
	 * @param string $dataValue
	 * @return boolean
	 */
		 public function doValidateIp(&$dataValue) {
		 $dataValue = (string) $dataValue;
		 if (strlen($dataValue) > 0) {
		  return true;
		 }
		 return false;
}

	/**
	 * Validates and formats the value as a browser
	 * @param string $dataValue
	 * @return boolean
	 */
	 public function doValidateBrowser(&$dataValue, $lead) {
		 $dataValue = (string) $dataValue;

		 //this validation stores information into 3 other fields as defined by retrieveAdditionalParameters
		 /* @todo We need to move the BrowsCap into this project first
		 /*
		 $browser_info = common::returnUserAgentInfo($dataValue);
		 foreach($this->retrieveAdditionalParameters() AS $dataFieldParameter) {
		 if ($dataFieldParameter->retrieveValue('field_name') === 'browser') {
		 $dataFieldId = $dataFieldParameter->retrieveValue('value');
		 $dataFieldName = self::retrieveDataFieldFromId($dataFieldId)->retrieveValue('request_name');
		 $lead->setValue($dataFieldName, $browser_info->Browser, lead::LEAD_REQUEST_CODE_DEFAULT);
		 }
		 if ($dataFieldParameter->retrieveValue('field_name') === 'platform') {
		 $dataFieldId = $dataFieldParameter->retrieveValue('value');
		 $dataFieldName = self::retrieveDataFieldFromId($dataFieldId)->retrieveValue('request_name');
		 $lead->setValue($dataFieldName, $browser_info->Platform, lead::LEAD_REQUEST_CODE_DEFAULT);
		 }
		 if($dataFieldParameter->retrieveValue('field_name') === 'version') {
		 $dataFieldId = $dataFieldParameter->retrieveValue('value');
		 $dataFieldName = self::retrieveDataFieldFromId($dataFieldId)->retrieveValue('request_name');
		$lead->setValue($dataFieldName, $browser_info->MajorVer, lead::LEAD_REQUEST_CODE_DEFAULT);
			}
			//$lead_array['brows_m'] = $browser_info->isMobileDevice;
		}
		*/
		return true;
	}

	/**
	 * Validates and formats the value as a object
	 * @param string $dataValue
	 * @return boolean
	 */
	public function doValidateObject(&$dataValue) {
		return true;
	}

	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$DataField = new self();
		$DataField->getCollection()->ensureIndex(array('key_name' => 1), array('background' => true, 'unique' => true));
		$DataField->getCollection()->ensureIndex(array('status' => 1, 'access_type' => 1), array('background' => true));
		return true;
	}
}

/**
 * DataFieldSet stores possible values used for autocompletion of data fields
 * @author Mark Hobson
 */
class DataFieldSet extends MongoForm {
    
    protected $name;
    protected $value;
    protected $lead_total;
    protected $daily_total;
    
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
    	$this->addModifiedColumn("name");
    	return $this;
    }
    
    /**
     * Returns the value
     * @return string
     */
    function getValue() {
    	if (is_null($this->value)) {
    		$this->value = "";
    	}
    	return $this->value;
    }
    
    /**
     * Sets the value
     * @var string
     */
    function setValue($arg0) {
    	$this->value = $arg0;
    	$this->addModifiedColumn("value");
    	return $this;
    }
    
    /**
     * Returns the lead_total
     * @return integer
     */
    function getLeadTotal() {
    	if (is_null($this->lead_total)) {
    		$this->lead_total = 0;
    	}
    	return $this->lead_total;
    }
    
    /**
     * Sets the lead_total
     * @var integer
     */
    function setLeadTotal($arg0) {
    	$this->lead_total = (int)$arg0;
    	$this->addModifiedColumn("lead_total");
    	return $this;
    }
    
    /**
     * Returns the daily_total
     * @return integer
     */
    function getDailyTotal() {
    	if (is_null($this->daily_total)) {
    		$this->daily_total = 0;
    	}
    	return $this->daily_total;
    }
    
    /**
     * Sets the daily_total
     * @var integer
     */
    function setDailyTotal($arg0) {
    	$this->daily_total = (int)$arg0;
    	$this->addModifiedColumn("daily_total");
    	return $this;
    }
}
