<?php
namespace Flux\Link;

class SplitQueue extends BasicLink {
	
	private $split_queue;
	
	/**
	 * Returns the split_queue_id
	 * @return integer
	 */
	function getSplitQueueId() {
		return parent::getId();
	}
	
	/**
	 * Sets the split_queue_id
	 * @var integer
	 */
	function setSplitQueueId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the split_queue_name
	 * @return string
	 */
	function getSplitQueueName() {
		return parent::getName();
	}
	
	/**
	 * Sets the split_queue_name
	 * @var string
	 */
	function setSplitQueueName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the split_queue
	 * @return \Flux\SplitQueue
	 */
	function getSplitQueue() {
		if (is_null($this->split_queue)) {
			$this->split_queue = new \Flux\SplitQueue();
			$this->split_queue->setId($this->getSplitQueueId());
			$this->split_queue->query();
		}
		return $this->split_queue;
	}
}