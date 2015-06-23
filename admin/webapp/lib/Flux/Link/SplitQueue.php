<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class SplitQueue extends CommonForm {
	
	protected $split_queue_id;
	protected $split_queue_name;
	
	private $split_queue;
	
	/**
	 * Returns the split_queue_id
	 * @return integer
	 */
	function getSplitQueueId() {
		if (is_null($this->split_queue_id)) {
			$this->split_queue_id = null;
		}
		return $this->split_queue_id;
	}
	
	/**
	 * Sets the split_queue_id
	 * @var integer
	 */
	function setSplitQueueId($arg0) {
	    if (\MongoId::isValid($arg0)) {
            $this->split_queue_id = $arg0;
	    } else if ($arg0 instanceof \MongoId) {
	        $this->split_queue_id = (string)$arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the split_queue_name
	 * @return string
	 */
	function getSplitQueueName() {
		if (is_null($this->split_queue_name)) {
			$this->split_queue_name = "";
		}
		return $this->split_queue_name;
	}
	
	/**
	 * Sets the split_queue_name
	 * @var string
	 */
	function setSplitQueueName($arg0) {
		$this->split_queue_name = $arg0;
		return $this;
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