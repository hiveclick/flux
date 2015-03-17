<?php
namespace Flux;

class Daemon extends Base\Daemon {
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Returns the campaign based on the campaign key
	 * @return Flux\DomainGroup
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName()  . "/i");
		}
		return parent::queryAll($criteria, $hydrate);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$daemon = new self();
		$daemon->getCollection()->ensureIndex(array('type' => 1), array('background' => true, 'unique' => true));
		return true;
	}
}
