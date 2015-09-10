<?php
namespace Flux;

class Vertical extends Base\Vertical {

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::VERTICAL_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::VERTICAL_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::VERTICAL_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}
	
	/**
	 * Queries a report column by it's name
	 * @return Flux\ReportColumn
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName() . "/i");
		}
		return parent::queryAll($criteria, $hydrate, $fields);
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