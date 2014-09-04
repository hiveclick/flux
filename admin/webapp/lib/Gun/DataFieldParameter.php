<?php
namespace Gun;

use Mojavi\Form\MongoForm;

//@todo: this subdocument maybe should extend something else besides baseConnection? maybe if we clean up baseConnection and separate connection and default getters and setters?
class DataFieldParameter extends MongoForm {
	const PARAMETER_TYPE_DATA_FIELD = 1;

	protected $name;
	protected $field_name;
	protected $type;
	protected $field_value;
	protected $required;

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
		$this->addModifiedColumn('field_name');
		return $this;
	}

	/**
	 * Returns the type
	 * @return integer
	 */
	function getType() {
		if (is_null($this->type)) {
			$this->type = self::PARAMETER_TYPE_DATA_FIELD;
		}
		return $this->type;
	}

	/**
	 * Sets the type
	 * @var integer
	 */
	function setType($arg0) {
		$this->type = (int)$arg0;
		$this->addModifiedColumn('type');
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
	 * Returns the required
	 * @return boolean
	 */
	function getRequired() {
		if (is_null($this->required)) {
			$this->required = false;
		}
		return $this->required;
	}

	/**
	 * Sets the required
	 * @var boolean
	 */
	function setRequired($arg0) {
		$this->required = (bool)$arg0;
		$this->addModifiedColumn('required');
		return $this;
	}
}
