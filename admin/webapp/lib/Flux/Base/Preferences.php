<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Preferences extends MongoForm {
	
	const READ_WRITE_GLOBAL = 1;
	const READ_WRITE_ADMIN = 2;
	const READ_ONLY = 3;
	
	protected $key;
	protected $value;
	protected $control;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('preferences');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the key
	 * @return string
	 */
	function getKey() {
		if (is_null($this->key)) {
			$this->key = null;
		}
		return $this->key;
	}
	
	/**
	 * Sets the key
	 * @var string
	 */
	function setKey($arg0) {
		$this->key = $arg0;
		$this->addModifiedColumn('key');
		return $this;
	}
	
	/**
	 * Returns the value
	 * @return string
	 */
	function getValue() {
		if (is_null($this->value)) {
			$this->value = null;
		}
		return $this->value;
	}
	
	/**
	 * Sets the key
	 * @var string
	 */
	function setValue($arg0) {
		$this->value = $arg0;
		$this->addModifiedColumn('value');
		return $this;
	}
	
	/**
	 * Returns the control
	 * @return int
	 */
	function getControl() {
		if (is_null($this->control)) {
			$this->control = null;
		}
		return $this->control;
	}
	
	/**
	 * Sets the key
	 * @var int
	 */
	function setControl($arg0) {
		$this->control = (int)$arg0;
		$this->addModifiedColumn('control');
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
		$daemon = new self();
		$daemon->getCollection()->ensureIndex(array('key' => 1), array('background' => true, 'unique' => true));
		return true;
	}
}