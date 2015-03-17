<?php
namespace Flux;

class DataField extends Base\DataField {

	private static $__source_data_fields;
	private static $__active_data_fields;
	private static $__active_data_fields_by_request_name;
	private static $__active_data_fields_by_key_name;
	
	/* These fields are used for search */
	private $storage_type_array;

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::DATA_FIELD_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::DATA_FIELD_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::DATA_FIELD_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}

	/**
	 * Returns the _field_type_name
	 * @return string
	 */
	function getFieldTypeName() {
		if ($this->getFieldType() == self::DATA_FIELD_TYPE_ARRAY) {
			return "Array";
		} else if ($this->getFieldType() == self::DATA_FIELD_TYPE_STRING) {
			return "String";
		} else if ($this->getFieldType() == self::DATA_FIELD_TYPE_OBJECT) {
			return "Object";
		} else {
			return "Unknown Type";
		}
	}

	/**
	 * Returns the _access_type_name
	 * @return string
	 */
	function getAccessTypeName() {
		if ($this->getAccessType() == self::DATA_FIELD_ACCESS_TYPE_SYSTEM) {
			return "System";
		} else if ($this->getFieldType() == self::DATA_FIELD_ACCESS_TYPE_RESERVED) {
			return "Reserved";
		} else if ($this->getFieldType() == self::DATA_FIELD_ACCESS_TYPE_PUBLIC) {
			return "Public";
		} else {
			return "Unknown Type";
		}
	}

	/**
	 * Returns the _storage_type_name
	 * @return string
	 */
	function getStorageTypeName() {
		if ($this->getStorageType() == self::DATA_FIELD_STORAGE_TYPE_MAIN) {
			return "Main";
		} else if ($this->getStorageType() == self::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
			return "Default";
		} else if ($this->getStorageType() == self::DATA_FIELD_STORAGE_TYPE_EVENT) {
			return "Event";
		} else if ($this->getStorageType() == self::DATA_FIELD_STORAGE_TYPE_TRACKING) {
			return "Tracking";
		} else if ($this->getStorageType() == self::DATA_FIELD_STORAGE_TYPE_DERIVED) {
			return "Derived";
		} else {
			return "Unknown Type";
		}
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
	 * Calls the custom mapping function
	 * @return string
	 */
	function callMappingFunc($value, $lead) {
		try {
			// Define a default mapping function
			$mapping_func = function($value, $lead) { return $value; };
	
			if ($this->getCustomCode() != self::getDefaultMappingFunc()) {
				$errors = '';
				// Now overwrite the default mapping function with the one from the export mapping
				@ob_start();
				eval('$mapping_func = function ($value, $lead) {' . $this->getCustomCode() . '};');
				if (ob_get_length() > 0) {
					$errors = ob_get_contents();
				}
				@ob_end_clean();
				if (trim($errors) != '') {
					throw new \Exception("Error evaluating mapping " . $this->getName() . ": ". $errors);
				}
			}
			// Finally call the mapping function and return the result
			return $mapping_func($value, $lead);
		} catch (\Exception $e) {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
			return $value;
		}
	}
	
	/**
	 * Returns the default function to use for mapping
	 * @return string
	 */
	static function getDefaultMappingFunc() {
		$ret_val = 'return $value;';
		return $ret_val;
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
		if (count($this->getTags()) > 0) {
			$criteria['tags'] = array('$in' => $this->getTags());
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
	 * Queries a list of the unique tag names
	 * @return array
	 */
	static function queryUniqueTagNames() {
		$ret_val = array();
		$data_field = new self();
		$results = $data_field->getCollection()->aggregate(array(array('$unwind' => '$tags'), array('$group' => array('_id' => array('tags' => '$tags'), 'tag_name' => array('$max' => '$tags')))), array('allowDiskUse' => true, 'maxTimeMS' => 1200000));
		if (isset($results['result'])) {
			foreach ($results['result'] as $result) {
				$ret_val[] = $result['tag_name'];
			}
			asort($ret_val);
			return $ret_val;
		} else {
			return $ret_val;
		}
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
	 * Returns a list of active data fields keyed by request name
	 * @param boolean $requery
	 * @return array
	 */
	public static function retrieveActiveDataFieldsByRequestName($requery = false) {
		if(is_null(self::$__active_data_fields_by_request_name) || ($requery === true)) {
			self::$__active_data_fields_by_request_name = array();
			foreach (self::retrieveDataFields($requery) AS $dataFieldId => $dataField) {
				if ($dataField->getStatus() === self::DATA_FIELD_STATUS_ACTIVE) {
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
				if ($dataField->getStatus() === self::DATA_FIELD_STATUS_ACTIVE) {
					// Request names are comma delimited such as firstname, first_name, fname
					self::$__active_data_fields_by_key_name[$dataField->getKeyName()] = $dataField;
				}
			}
		}
		return self::$__active_data_fields_by_key_name;
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