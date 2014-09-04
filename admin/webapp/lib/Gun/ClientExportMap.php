<?php
namespace Gun;

use Mojavi\Form\CommonForm;
/**
 * Mapping class used to handle column mappings
 * @author hobby
 */
class ClientExportMap extends CommonForm {
    
    protected $data_field_id;
    protected $column_id;
    protected $field_name;
    protected $default_value;
    protected $mapping_func;
    
    private $data_field;
    
    /**
     * Returns the data_field_id
     * @return integer
     */
    function getDataFieldId() {
    	if (is_null($this->data_field_id)) {
    		$this->data_field_id = 0;
    	}
    	return $this->data_field_id;
    }
    
    /**
     * Sets the data_field_id
     * @var integer
     */
    function setDataFieldId($arg0) {
    	$this->data_field_id = (int)$arg0;
    	$this->addModifiedColumn("data_field_id");
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
     * Calls the custom mapping function
     * @return string
     */
    function callMappingFunc($value, $lead) {
        try {
            // Define a default mapping function
            $mapping_func = function($value, $lead) { return $value; };
            // Now overwrite the default mapping function
            eval('$mapping_func = function ($value, $lead) {' . $this->getMappingFunc() . '}');
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
    
    /**
     * Returns the mapping_func
     * @return string
     */
    function getDataField() {
    	if (is_null($this->data_field)) {
    		$this->data_field = new \Gun\DataField();
    		$this->data_field->setId($this->getDataFieldId());
    		$this->data_field->query();
    	}
    	return $this->data_field;
    }
}
?>