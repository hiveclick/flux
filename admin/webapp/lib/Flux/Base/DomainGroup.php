<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class DomainGroup extends MongoForm {

	const DOMAIN_GROUP_STATUS_ACTIVE = 1;
	const DOMAIN_GROUP_STATUS_INACTIVE = 2;
	const DOMAIN_GROUP_STATUS_DELETED = 3;

	protected $name;
	protected $description;
	protected $domains;
	protected $status;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('domain_group');
		$this->setDbName('admin');
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::DOMAIN_GROUP_STATUS_ACTIVE;
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
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
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
	 * Returns the domains
	 * @return array
	 */
	function getDomains() {
		if (is_null($this->domains)) {
			$this->domains = array();
		}
		return $this->domains;
	}

	/**
	 * Sets the domains
	 * @var string
	 */
	function setDomains($arg0) {
		if (is_array($arg0)) {
			$this->domains = $arg0;
			array_walk($this->domains, function(&$val) { $val = trim($val); });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ",")) {
				$this->domains = explode(",", $arg0);
			} else {
				$this->domains = array($arg0);
			}
			array_walk($this->domains, function(&$val) { $val = trim($val); });
		}
		$this->addModifiedColumn('domains');
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
		$domain_group = new self();
		$domain_group->getCollection()->ensureIndex(array('name' => 1), array('background' => true, 'unique' => true));
		return true;
	}
}
