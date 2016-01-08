<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class BasicLink extends CommonForm {
	
	protected $name;
	
	/**
	 * Sets the id field
	 */
	function setId($arg0) {
		if (is_string($arg0) && \MongoId::isValid($arg0)) {
			return parent::setId(new \MongoId($arg0));
		} else if ($arg0 instanceof \MongoId) {
			return parent::setId($arg0);
		} else if (is_null($arg0)) {
			parent::setId($arg0);
		} else if (is_string($arg0) && trim($arg0) == '') {
			parent::setId(null);
		} else if ($arg0 == '0') {
			parent::setId(null);
		}
		return $this;
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
		return $this;
	}
	
}