<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class SplitQueue extends MongoForm {

    const DISPOSITION_UNFULFILLED = 0;
    const DISPOSITION_FULFILLED = 1;
    const DISPOSITION_PENDING = 2;
    const DISPOSITION_UNFULFILLABLE = 3;
    const DISPOSITION_ALREADY_FULFILLED = 4;
    
	protected $split;
	protected $lead;
	
	protected $disposition;
	protected $is_fulfilled;
	protected $is_processing;
	protected $error_message;
	protected $is_error;
	protected $last_attempt_time;
	protected $next_attempt_time;
	protected $attempt_count;
	protected $debug;
	protected $queue_time;
	protected $is_catch_all;
	protected $is_unfulfillable;
	
	protected $attempts;
	
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
	 * Returns the disposition
	 * @return integer
	 */
	function getDisposition() {
	    if (is_null($this->disposition)) {
	        $this->disposition = self::DISPOSITION_UNFULFILLED;
	    }
	    return $this->disposition;
	}
	
	/**
	 * Sets the disposition
	 * @var integer
	 */
	function setDisposition($arg0) {
	    $this->disposition = (int)$arg0;
	    $this->addModifiedColumn("disposition");
	    return $this;
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
	 * Returns the is_catch_all
	 * @return boolean
	 */
	function getIsCatchAll() {
	    if (is_null($this->is_catch_all)) {
	        $this->is_catch_all = false;
	    }
	    return $this->is_catch_all;
	}
	
	/**
	 * Sets the is_catch_all
	 * @var boolean
	 */
	function setIsCatchAll($arg0) {
	    $this->is_catch_all = (boolean)$arg0;
	    $this->addModifiedColumn("is_catch_all");
	    return $this;
	}
	
	/**
	 * Returns the is_unfulfillable
	 * @return boolean
	 */
	function getIsUnfulfillable() {
	    if (is_null($this->is_unfulfillable)) {
	        $this->is_unfulfillable = false;
	    }
	    return $this->is_unfulfillable;
	}
	
	/**
	 * Sets the is_unfulfillable
	 * @var boolean
	 */
	function setIsUnfulfillable($arg0) {
	    $this->is_unfulfillable = $arg0;
	    $this->addModifiedColumn("is_unfulfillable");
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
	 * Returns the queue_time
	 * @return \MongoDate
	 */
	function getQueueTime() {
	    if (is_null($this->queue_time)) {
	        $this->queue_time = new \MongoDate();
	    }
	    return $this->queue_time;
	}
	
	/**
	 * Sets the queue_time
	 * @var \MongoDate
	 */
	function setQueueTime($arg0) {
	    $this->queue_time = $arg0;
	    $this->addModifiedColumn('queue_time');
	    return $this;
	}
	
	/**
	 * Returns the debug
	 * @return string
	 */
	function getDebug() {
	    if (is_null($this->debug)) {
	        $this->debug = new \Flux\Link\SplitQueueDebug();
	    }
	    return $this->debug;
	}
	
	/**
	 * Sets the debug
	 * @var string
	 */
	function setDebug($arg0) {
	    if (is_string($arg0)) {
	        $this->debug = new \Flux\Link\SplitQueueDebug();
	        $this->debug->setRequest($arg0);
	    }
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
	 * Returns the attempts
	 * @return array
	 */
	function getAttempts() {
	    if (is_null($this->attempts)) {
	        $this->attempts = array();
	    }
	    return $this->attempts;
	}
	
	/**
	 * Sets the attempts
	 * @var array
	 */
	function setAttempts($arg0) {
	    if (is_array($arg0)) {
	        $this->attempts = array();
	        foreach ($arg0 as $attempt) {
    			$split_queue_attempt = new \Flux\SplitQueueAttempt();
    			$split_queue_attempt->populate($attempt);
    			array_push($this->attempts, $split_queue_attempt);
	        }
	        
	        // Sort the attempts from latest to earliest
	        uasort($this->attempts, function($a, $b) {
	            if ($a->getAttemptTime()->sec > $b->getAttemptTime()->sec) {
	                return -1;
	            } else {
	                return 1;
	            }
	        });
	        
	        // Only allow the last 10 attempts
	        $this->attempts = array_slice($this->attempts, 0, 5);
		}
		$this->addModifiedColumn('attempts');
	    return $this;
	}
	
	/**
	 * Sets the attempts
	 * @var array
	 */
	function addAttempt($arg0) {
	    if (is_array($arg0)) {
	        /* @var $split_queue_attempt \Flux\SplitQueueAttempt */
            $split_queue_attempt = new \Flux\SplitQueueAttempt();
            $split_queue_attempt->populate($arg0);
            
            // Add the attempt to the array
            $attempts = $this->getAttempts();
            array_push($attempts, $split_queue_attempt);
            $this->setAttempts($attempts);
	    } else if ($arg0 instanceof \Flux\SplitQueueAttempt) {
	        // Add the attempt to the array
	        $attempts = $this->getAttempts();
	        array_push($attempts, $arg0);
	        $this->setAttempts($attempts);
	    }
	    $this->addModifiedColumn('attempts');
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
