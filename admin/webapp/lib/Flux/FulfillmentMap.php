<?php
namespace Flux;

use Mojavi\Form\CommonForm;
/**
 * Mapping class used to handle column mappings
 * @author hobby
 */
class FulfillmentMap extends CommonForm {
	
	protected $data_field;
	protected $column_id;
	protected $field_name;
	protected $default_value;
	protected $mapping_func;
		
	/**
	 * Returns the this
	 * @return \Flux\Link\DataField
	 */
	function getDataField() {
		if (is_null($this->data_field)) {
			$this->data_field = new \Flux\Link\DataField();
		}
		return $this->data_field;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setDataField($arg0) {
		if (is_array($arg0)) {
			$data_field = $this->getDataField();
			$data_field->populate($arg0);
			if (\MongoId::isValid($data_field->getId()) && $data_field->getName() == "") {
				$data_field->setDataFieldName($data_field->getDataField()->getName());
			}
			$this->data_field = $data_field;
		} else if (is_string($arg0)) {
			$data_field = $this->getDataField();
			$data_field->setDataFieldId($arg0);
			if (\MongoId::isValid($data_field->getId()) && $data_field->getName() == "") {
				$data_field->setDataFieldName($data_field->getDataField()->getName());
			}
			$this->data_field = $data_field;
		} else if ($arg0 instanceof \MongoId) {
			$data_field = $this->getDataField();
			$data_field->setDataFieldId($arg0);
			if (\MongoId::isValid($data_field->getId()) && $data_field->getName() == "") {
				$data_field->setDataFieldName($data_field->getDataField()->getName());
			}
			$this->data_field = $data_field;
		}
		$this->addModifiedColumn('data_field');
		return $this;
	}
	
	/**
	 * Returns the column_id
	 * @return integer
	 */
	function getColumnId() {
		if (is_null($this->column_id)) {
			$this->column_id = 0;
		}
		return $this->column_id;
	}
	
	/**
	 * Sets the column_id
	 * @var integer
	 */
	function setColumnId($arg0) {
		$this->column_id = (int)$arg0;
		$this->addModifiedColumn("column_id");
		return $this;
	}
	
	/**
	 * Returns the field_name
	 * @return string
	 */
	function getFieldName() {
		if (is_null($this->field_name)) {
			$this->field_name = "";
		}
		return $this->field_name;
	}
	
	/**
	 * Sets the field_name
	 * @var string
	 */
	function setFieldName($arg0) {
		$this->field_name = $arg0;
		$this->addModifiedColumn("field_name");
		return $this;
	}
	
	/**
	 * Returns the default_value
	 * @return string
	 */
	function getDefaultValue() {
		if (is_null($this->default_value)) {
			$this->default_value = "";
		}
		return $this->default_value;
	}
	
	/**
	 * Sets the default_value
	 * @var string
	 */
	function setDefaultValue($arg0) {
		$this->default_value = $arg0;
		$this->addModifiedColumn("default_value");
		return $this;
	}
	
	/**
	 * Returns the custom mapped value
	 * @param $lead Flux\Lead
	 * @return string
	 */
	function getMappedValue($lead) {
		if (\MongoId::isValid($this->getDataField()->getId())) {
			$ret_val = $lead->getValue($this->getDataField()->getDataField()->getKeyName());
			if (is_string($ret_val)) {
				if (trim($ret_val) == '') {
					$ret_val = $this->getDefaultValue();
				}	
			} else if (is_array($ret_val)) {
				if (count($ret_val) == 0) {
					$ret_val = $this->getDefaultValue();
				}
			}			
		} else {
			$ret_val = $this->getDefaultValue();
		}
		if ($this->getDataField()->getDataField()->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && !is_array($ret_val)) {
			$ret_val = array($ret_val);
		} else if ($this->getDataField()->getDataField()->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_STRING && !is_string($ret_val)) {
			if (is_array($ret_val)) {
				$ret_val = implode(",", $ret_val);
			} else {
				$ret_val = (string)$ret_val;
			}
		}
		
		return $this->callMappingFunc($ret_val, $lead);
	}
	
	/**
	 * Calls the custom mapping function
	 * @return string
	 */
	function callMappingFunc($value, $lead) {
		try {
			// Define a default mapping function
			$mapping_func = function($value, $lead) { return $value; };
			$datafield_mapping_func = function($value, $lead) { return $value; };
			if (trim($this->getDataField()->getDataField()->getCustomCode()) != '' && $this->getDataField()->getDataField()->getCustomCode() != self::getDefaultMappingFunc()) {
				$errors = '';
				// Now overwrite the default mapping function with the one from the data field
				@ob_start();
				
				eval('$datafield_mapping_func = function ($value, $lead) {' . $this->getDataField()->getDataField()->getCustomCode() . '};');
				
				if (ob_get_length() > 0) {
					$errors = ob_get_contents();
				}
				@ob_end_clean();
				if (trim($errors) != '') {
					throw new \Exception("Error evaluating datafield mapping " . $this->getFieldName() . ": ". $errors);
				}
			}
			
			if ($this->getMappingFunc() != self::getDefaultMappingFunc()) {
				$errors = '';
				// Now overwrite the default mapping function with the one from the export mapping
				@ob_start();
				
				eval('$mapping_func = function ($value, $lead) {' . $this->getMappingFunc() . '};');
				
				
				if (ob_get_length() > 0) {
					$errors = ob_get_contents();
				}
				@ob_end_clean();
				if (trim($errors) != '') {
					throw new \Exception("Error evaluating mapping " . $this->getFieldName() . ": ". $errors);
				}
			}
			
			// setup custom error handling
			set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
				throw new \Exception("Error evaluating mapping " . $this->getFieldName() . ": ". $errstr);
			});
			
			// Finally call the mapping function and return the result
			$value = $datafield_mapping_func($value, $lead);
			
			$value = $mapping_func($value, $lead);
			// Restore the original error handling to mojavi
			restore_error_handler();
			
			return $value;
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
	 * Returns the mapping_func
	 * @return string
	 */
	function getMappingFunc() {
		if (is_null($this->mapping_func)) {
			$this->mapping_func = self::getDefaultMappingFunc();
		}
		return $this->mapping_func;
	}
	
	/**
	 * Sets the mapping_func
	 * @var string
	 */
	function setMappingFunc($arg0) {
		if (trim($arg0) != '') {
			$this->mapping_func = $arg0;
			$this->addModifiedColumn("mapping_func");
		} else {
			// if we pass in a blank value, default to just returning the value
			$this->mapping_func = self::getDefaultMappingFunc();
			$this->addModifiedColumn("mapping_func");
		}
		return $this;
	}
}
?>