<?php
namespace Gun;

class SplitQueue extends Lead {

	protected $split_id;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct($split_id = 0) {
		$this->setSplitId($split_id);
		$this->setDbName('queue');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the split_id
	 * @return integer
	 */
	function getSplitId() {
		if (is_null($this->split_id)) {
			$this->split_id = 0;
		}
		return $this->split_id;
	}
	
	/**
	 * Sets the split_id
	 * @var integer
	 */
	function setSplitId($arg0) {
		$this->split_id = $arg0;
		$this->setCollectionName('split_queue_' . $this->split_id);
		$this->addModifiedColumn('split_id');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$split_queue = new self();
		$split_queue->getCollection()->ensureIndex(array('split_id' => 1), array('background' => true));
		return true;
	}
}
