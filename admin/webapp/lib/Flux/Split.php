<?php
namespace Flux;

class Split extends Base\Split {

	private $position;
	private $pid_split;
	private $offer_id_array;
	protected $pid_time_split;

	/**
	 * Returns the offer_id_array
	 * @return array
	 */
	function getOfferIdArray() {
		if (is_null($this->offer_id_array)) {
			$this->offer_id_array = array();
		}
		return $this->offer_id_array;
	}
	
	/**
	 * Sets the offer_id_array
	 * @var array
	 */
	function setOfferIdArray($arg0) {
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$this->offer_id_array = array($arg0);
		} else if ($arg0 instanceof \MongoId) {
			$this->offer_id_array = array($arg0);
		}
		array_walk($this->offer_id_array, function(&$value) {
			if (is_string($value) && \MongoId::isValid($value)) {
				$value = new \MongoId($value);
			} 
		});
		
		$this->addModifiedColumn("offer_id_array");
		return $this;
	}
	
	
	
	/**
	 * Returns true if the offer id is selected
	 * @return boolean
	 */
	function isOfferSelected($offer_id) {
		/* @var $offer \Flux\Link\Offer */
		foreach ($this->getOffers() as $offer) {
			if ($offer->getId() == $offer_id) {
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
	
	/**
	 * Returns the pid_time_split
	 * @return \MongoDate
	 */
	function getPidTimeSplit() {
		if (is_null($this->pid_time_split)) {
			$this->pid_time_split = new \MongoDate();
		}
		return $this->pid_time_split;
	}
	
	/**
	 * Sets the pid_time_split
	 * @var \MongoDate
	 */
	function setPidTimeSplit($arg0) {
		$this->pid_time_split = $arg0;
		$this->addModifiedColumn("pid_time_split");
		return $this;
	}
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if ($this->getSplitType() > 0) {
			$criteria['split_type'] = $this->getSplitType();
		}
		if (trim($this->getName()) != '') {
			$criteria['name'] = new \MongoRegex("/" . $this->getName() . "/i");
		}
		if (count($this->getOfferIdArray()) > 0) {
			$criteria['offers._id'] = array('$in' => $this->getOfferIdArray());
		}
		return parent::queryAll($criteria, $hydrate, $fields);
	}
	
	/**
	 * Clears the pid status on the split to kickstart it
	 * @return boolean
	 */
	function clearPid() {
		return parent::update(array(), array('$unset' => array(
				'pid_split' => 1
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
