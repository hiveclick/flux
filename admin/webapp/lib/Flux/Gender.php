<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class Gender extends MongoForm {
	
	protected $name;
	protected $gender;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('gender');
		$this->setDbName('util');
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
	 * Returns the gender
	 * @return string
	 */
	function getGender() {
		if (is_null($this->gender)) {
			$this->gender = "";
		}
		return $this->gender;
	}
	
	/**
	 * Sets the gender
	 * @var string
	 */
	function setGender($arg0) {
		$this->gender = $arg0;
		$this->addModifiedColumn('gender');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$gender = new self();
		$gender->getCollection()->ensureIndex(array('name' => 1), array('unique' => true));
		return true;
	}
	
}
