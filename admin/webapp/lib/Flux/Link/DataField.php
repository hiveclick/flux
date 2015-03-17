<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class DataField extends CommonForm {
	
	const DATA_FIELD_CONDITION_IS = 1;
	const DATA_FIELD_CONDITION_IS_NOT = 2;
	const DATA_FIELD_CONDITION_IS_NOT_BLANK = 3;
	const DATA_FIELD_CONDITION_IS_SET = 4;
	const DATA_FIELD_CONDITION_IS_GT = 5;
	const DATA_FIELD_CONDITION_IS_LT = 6;
	
	protected $data_field_key_name;
	protected $data_field_id;
	protected $data_field_name;
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
		return $this;
	}
	
	/**
	 * Returns the data_field_name
	 * @return string
	 */
	function getDataFieldName() {
		if (is_null($this->data_field_name)) {
			$this->data_field_name = "";
		}
		return $this->data_field_name;
	}
	
	/**
	 * Sets the data_field_name
	 * @var string
	 */
	function setDataFieldName($arg0) {
		$this->data_field_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the data_field_value
	 * @return string
	 */
	function getDataFieldValue() {
		if (is_null($this->data_field_value)) {
			$this->data_field_value = "";
		}
		return $this->data_field_value;
	}
	
	/**
	 * Sets the data_field_value
	 * @var string
	 */
	function setDataFieldValue($arg0) {
		$this->data_field_value = $arg0;
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
				$this->data_field->setId($this->getDataFieldId());
				$this->data_field->query();
			}
		}
		return $this->data_field;
	}
}