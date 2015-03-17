<?php
namespace Flux;

class DomainGroup extends Base\DomainGroup {

	private static $_domain_groups;

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::DOMAIN_GROUP_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::DOMAIN_GROUP_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::DOMAIN_GROUP_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
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
			$criteria['name'] = new \MongoRegex("/" . $this->getName()  . "/i");
		}
		return parent::queryAll($criteria, $hydrate);
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
