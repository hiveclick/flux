<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class SplitQueue extends MongoForm {

	protected $split;
	protected $lead;
	
	protected $is_fulfilled;
	protected $is_processing;
	protected $error_message;
	protected $is_error;
	protected $last_attempt_time;
	protected $next_attempt_time;
	protected $attempt_count;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct($split_id = 0) {
		$this->setSplit($split_id);
		$this->setDbName('queue');
		$this->setCollectionName('split_queue');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the is_fulfilled
	 * @return boolean
	 */
	function getIsFulfilled() {
		if (is_null($this->is_fulfilled)) {
			$this->is_fulfilled = false;
		}
		return $this->is_fulfilled;
	}
	
	/**
	 * Sets the is_fulfilled
	 * @var boolean
	 */
	function setIsFulfilled($arg0) {
		$this->is_fulfilled = $arg0;
		$this->addModifiedColumn('is_fulfilled');
		return $this;
	}
	
	/**
	 * Returns the is_processing
	 * @return boolean
	 */
	function getIsProcessing() {
		if (is_null($this->is_processing)) {
			$this->is_processing = false;
		}
		return $this->is_processing;
	}
	
	/**
	 * Sets the is_processing
	 * @var boolean
	 */
	function setIsProcessing($arg0) {
		$this->is_processing = $arg0;
		$this->addModifiedColumn('is_processing');
		return $this;
	}
	
	/**
	 * Returns the is_error
	 * @return boolean
	 */
	function getIsError() {
		if (is_null($this->is_error)) {
			$this->is_error = false;
		}
		return $this->is_error;
	}
	
	/**
	 * Sets the is_error
	 * @var boolean
	 */
	function setIsError($arg0) {
		$this->is_error = $arg0;
		$this->addModifiedColumn('is_error');
		return $this;
	}
	
	/**
	 * Returns the error_message
	 * @return string
	 */
	function getErrorMessage() {
		if (is_null($this->error_message)) {
			$this->error_message = "";
		}
		return $this->error_message;
	}
	
	/**
	 * Sets the error_message
	 * @var string
	 */
	function setErrorMessage($arg0) {
		$this->error_message = $arg0;
		$this->addModifiedColumn('error_message');
		return $this;
	}
	
	/**
	 * Returns the attempt_count
	 * @return integer
	 */
	function getAttemptCount() {
		if (is_null($this->attempt_count)) {
			$this->attempt_count = 1;
		}
		return $this->attempt_count;
	}
	
	/**
	 * Sets the attempt_count
	 * @var integer
	 */
	function setAttemptCount($arg0) {
		$this->attempt_count = $arg0;
		$this->addModifiedColumn('attempt_count');
		return $this;
	}
	
	/**
	 * Returns the last_attempt_time
	 * @return \MongoDate
	 */
	function getLastAttemptTime() {
		if (is_null($this->last_attempt_time)) {
			$this->last_attempt_time = null;
		}
		return $this->last_attempt_time;
	}
	
	/**
	 * Sets the last_attempt_time
	 * @var \MongoDate
	 */
	function setLastAttemptTime($arg0) {
		$this->last_attempt_time = $arg0;
		$this->addModifiedColumn('last_attempt_time');
		return $this;
	}
	
	/**
	 * Returns the next_attempt_time
	 * @return \MongoDate
	 */
	function getNextAttemptTime() {
		if (is_null($this->next_attempt_time)) {
			$this->next_attempt_time = new \MongoDate();
		}
		return $this->next_attempt_time;
	}
	
	/**
	 * Sets the next_attempt_time
	 * @var \MongoDate
	 */
	function setNextAttemptTime($arg0) {
		$this->next_attempt_time = $arg0;
		$this->addModifiedColumn('next_attempt_time');
		return $this;
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Link\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Link\Split();
		}
		return $this->split;
	}
	
	/**
	 * Sets the split
	 * @var integer|array
	 */
	function setSplit($arg0) {
		if (is_array($arg0)) {
			$split = $this->getSplit();
			$split->populate($arg0);
			if ($split->getSplitId() > 0 && $split->getSplitName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		} else if (is_string($arg0) || is_int($arg0)) {
			$split = $this->getSplit();
			$split->setSplitId($arg0);
			if ($split->getSplitId() > 0 && $split->getSplitName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		}
		$this->addModifiedColumn('split');
		return $this;
	}
	
	/**
	 * Returns the lead
	 * @return \Flux\Link\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Link\Lead();
		}
		return $this->lead;
	}
	
	/**
	 * Sets the lead
	 * @var string|array
	 */
	function setLead($arg0) {
		if (is_array($arg0)) {
			$lead = $this->getLead();
			$lead->populate($arg0);
			$this->lead = $lead;
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$lead = $this->getLead();
			$lead->setLeadId($arg0);
			$this->lead = $lead;
		} else if ($arg0 instanceof \MongoId) {
			$lead = $this->getLead();
			$lead->setLeadId((string)$arg0);
			$this->lead = $lead;
		}
		$this->addModifiedColumn('lead');
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
