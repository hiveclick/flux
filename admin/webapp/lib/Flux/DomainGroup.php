<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class DomainGroup extends MongoForm {

	const DOMAIN_GROUP_STATUS_ACTIVE = 1;
	const DOMAIN_GROUP_STATUS_INACTIVE = 2;
	const DOMAIN_GROUP_STATUS_DELETED = 3;

	protected $name;
	protected $description;
	protected $domains;
	protected $status;

	private $status_name;

	private static $_domain_groups;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('domain_group');
		$this->setDbName('admin');
	}

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if (is_null($this->_status_name)) {
			$this->_status_name = self::retrieveStatuses()[$this->getStatus()];
		}
		return $this->_status_name;
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
			array_walk($arg0, function($val) { $val = trim($val); });
			$this->domains = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ",")) {
				$this->domains = explode(",", $arg0);
			} else {
				$this->domains = array($arg0);
			}
		}
		$this->addModifiedColumn('domains');
		return $this;
	}



	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	/**
	 * Returns the campaign based on the campaign key
	 * @return Flux\Campaign
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (count($this->getDomains()) > 0) {
			$criteria['domains'] = array('$in' => $this->getDomains());
		}
		if (trim($this->getName()) != '') {
			$criteria['name'] = \MongoRegex("/" . $this->getName()  . "/");
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Returns the array of domain group statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::DOMAIN_GROUP_STATUS_ACTIVE => 'Active',
				self::DOMAIN_GROUP_STATUS_INACTIVE => 'Inactive',
				self::DOMAIN_GROUP_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns an object from the id using a cache
	 * @param integer $id
	 * @return \Flux\DomainGroup
	 */
	public static function retrieveById($id) {
		if (is_null(self::$_domain_groups)) {
			$domain_group = new \Flux\DomainGroup();
			$domain_group->setIgnorePagination(true);
			self::$_domain_groups = $domain_group->queryAll();
		}
		foreach (self::$_domain_groups as $domain_group) {
			if ($domain_group->getId() == $id) {
				return $domain_group;
			}
		}
		return new \Flux\DomainGroup();
	}

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
