<?php
namespace Flux;

class Daemon extends Base\Daemon {
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
    /**
     * Returns the is_running
     * @return boolean
     */
    function getIsRunning() {
        if (is_null($this->is_running)) {
            if (intval($this->getPid()) > 0) {
                $out = shell_exec('ps ' . $this->getPid() . ' | wc -l');
                $this->is_running = ($out >= 2);
            } else {
                $this->is_running = false;
            }
        }
        return $this->is_running;
    }
    
    /**
     * Queries for a data field by the name
     * @return Daemon
     */
    function queryByName() {
        $criteria = array('name' => $this->getName());
        return parent::query($criteria, false);
    }
    
    /**
     * Queries for a data field by the name
     * @return Daemon
     */
    function queryByType() {
        $criteria = array('type' => $this->getType());
        return parent::query($criteria, false);
    }
    
    /**
     * Returns the campaign based on the campaign key
     * @return Flux\DomainGroup
     */
    function queryByClass() {
        $criteria = array('class_name' => $this->getClassName());
		return parent::query($criteria, false);
    }
    
	/**
	 * Returns the campaign based on the campaign key
	 * @return Flux\DomainGroup
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName()  . "/i");
		}
		return parent::queryAll($criteria, $hydrate, $fields);
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
