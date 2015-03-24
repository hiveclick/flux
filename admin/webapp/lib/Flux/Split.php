<?php
namespace Flux;

class Split extends Base\Split {

	private $position;
	private $pid_split;
	private $pid_split_last;

	/**
	 * Returns true if the offer id is selected
	 * @return boolean
	 */
	function isOfferSelected($offer_id) {
		/* @var $offer \Flux\Link\Offer */
		foreach ($this->getOffers() as $offer) {
			if ($offer->getOfferId() == $offer_id) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::SPLIT_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::SPLIT_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::SPLIT_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}
	
	/**
	 * Returns the position
	 * @return integer
	 */
	function getPosition() {
		if (is_null($this->position)) {
			$this->position = 1;
		}
		return $this->position;
	}

	/**
	 * Sets the position
	 * @var integer
	 */
	function setPosition($arg0) {
		$this->position = $arg0;
		$this->addModifiedColumn('position');
		return $this;
	}
	
	/**
	 * Returns the pid_split
	 * @return integer
	 */
	function getPidSplit() {
		if (is_null($this->pid_split)) {
			$this->pid_split = null;
		}
		return $this->pid_split;
	}
	
	/**
	 * Sets the pid_split
	 * @var integer
	 */
	function setPidSplit($arg0) {
		$this->pid_split = $arg0;
		return $this;
	}
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
	    if (trim($this->getName()) != '') {
	        $criteria['name'] = new \MongoRegex("/" . $this->getName() . "/i");
	    }
	    return parent::queryAll($criteria, $hydrate);
	}
	
	/**
	 * Clears the pid status on the split to kickstart it
	 * @return boolean
	 */
	function clearPid() {
		return parent::update(array(), array('$unset' => array(
				'pid_split' => 1,
				'pid_time_split' => 1
		)));
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$split = new self();
		$split->getCollection()->ensureIndex(array('__pid_split' => 1), array('background' => true));
		$split->getCollection()->ensureIndex(array('name' => 1), array('background' => true));
		return true;
	}
}
