<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class Vertical extends MongoForm {

	const VERTICAL_STATUS_ACTIVE = 1;
	const VERTICAL_STATUS_INACTIVE = 2;
	const VERTICAL_STATUS_DELETED = 3;

	protected $name;
	protected $description;
	protected $status;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('vertical');
		$this->setDbName('admin');
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
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::VERTICAL_STATUS_ACTIVE;
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
	 * Queries a report column by it's name
	 * @return Flux\ReportColumn
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getKeywords()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getKeywords() . "/i");
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Returns the array of campaign statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::VERTICAL_STATUS_ACTIVE => 'Active',
				self::VERTICAL_STATUS_INACTIVE => 'Inactive',
				self::VERTICAL_STATUS_DELETED => 'Deleted'
		);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$vertical = new self();
		$vertical->getCollection()->ensureIndex(array('status' => 1, 'type' => 1), array('background' => true));
		$vertical->getCollection()->ensureIndex(array('name' => 1), array('background' => true));
		return true;
	}
}