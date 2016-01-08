<?php
namespace Flux\Link;

class DataField extends BasicLink {
	
	const DATA_FIELD_CONDITION_IS = 1;
	const DATA_FIELD_CONDITION_IS_NOT = 2;
	const DATA_FIELD_CONDITION_IS_NOT_BLANK = 3;
	const DATA_FIELD_CONDITION_IS_SET = 4;
	const DATA_FIELD_CONDITION_IS_GT = 5;
	const DATA_FIELD_CONDITION_IS_LT = 6;
	
	protected $data_field_key_name;
	protected $data_field_value;
	protected $data_field_condition;
	
	private $data_field;
	
	/**
	 * Returns the data_field_key_name
	 * @return string
	 */
	function getDataFieldKeyName() {
		if (is_null($this->data_field_key_name)) {
			$this->data_field_key_name = "";
		}
		return $this->data_field_key_name;
	}
	
	/**
	 * Sets the data_field_key_name
	 * @var string
	 */
	function setDataFieldKeyName($arg0) {
		$this->data_field_key_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the data_field_id
	 * @return integer
	 */
	function getDataFieldId() {
		return parent::getId();
	}
	
	/**
	 * Sets the data_field_id
	 * @var integer
	 */
	function setDataFieldId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the data_field_name
	 * @return string
	 */
	function getDataFieldName() {
		return parent::getName();
	}
	
	/**
	 * Sets the data_field_name
	 * @var string
	 */
	function setDataFieldName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the data_field_value
	 * @return string
	 */
	function getDataFieldValue() {
		if (is_null($this->data_field_value)) {
			$this->data_field_value = array();
		}
		return $this->data_field_value;
	}
	
	/**
	 * Sets the data_field_value
	 * @var string
	 */
	function setDataFieldValue($arg0) {
		if (is_array($arg0)) {
			$this->data_field_value = $arg0;
			array_walk($this->data_field_value, function(&$value) { $value = trim($value); });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->data_field_value = explode(",", $arg0);
			} else {
				$this->data_field_value = array($arg0);
			}
			array_walk($this->data_field_value, function(&$value) { $value = trim($value); });
		}
		return $this;
	}
	
	/**
	 * Returns the data_field_condition
	 * @return integer
	 */
	function getDataFieldCondition() {
		if (is_null($this->data_field_condition)) {
			$this->data_field_condition = self::DATA_FIELD_CONDITION_IS;
		}
		return $this->data_field_condition;
	}
	
	/**
	 * Sets the data_field_condition
	 * @var integer
	 */
	function setDataFieldCondition($arg0) {
		$this->data_field_condition = $arg0;
		return $this;
	}
	
	/**
	 * Returns the data_field
	 * @return \Flux\DataField
	 */
	function getDataField() {
		if (is_null($this->data_field)) {
			if ($this->getDataFieldKeyName() != '') {
				$this->data_field = new \Flux\DataField();
				$this->data_field->setKeyName($this->getDataFieldKeyName());
				$this->data_field->queryByKeyName();
			} else {
				$this->data_field = new \Flux\DataField();
				$this->data_field->setId($this->getId());
				$this->data_field->query();
			}
		}
		return $this->data_field;
	}
}