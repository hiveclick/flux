<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;
use Mojavi\Form\CommonForm;

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
	const DATA_FIELD_EVENT_FULFILLED_NAME = 'fulfilled';
	const DATA_FIELD_EVENT_PIXEL_NAME = 'pxl';

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
			array_walk($this->request_name, function(&$val) { $val = trim(strtolower($val)); });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->request_name = explode(",", $arg0);
			} else {
				$this->request_name = array($arg0);
			}
			array_walk($this->request_name, function(&$val) { $val = trim(strtolower($val)); });
		}
		$this->addModifiedColumn('request_name');
		return $this;
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
			array_walk($this->tags, function(&$val) { $val = trim(strtolower($val)); });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->tags = explode(",", $arg0);
			} else {
				$this->tags = array($arg0);
			}
			array_walk($this->tags, function(&$val) { $val = trim(strtolower($val)); });
		}
		$this->addModifiedColumn('tags');
		return $this;
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
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$data_field = new self();
		$data_field->getCollection()->ensureIndex(array('key_name' => 1), array('background' => true, 'unique' => true));
		$data_field->getCollection()->ensureIndex(array('status' => 1, 'access_type' => 1), array('background' => true));
		return true;
	}
}

/**
 * DataFieldSet stores possible values used for autocompletion of data fields
 * @author Mark Hobson
 */
class DataFieldSet extends CommonForm {
	
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
