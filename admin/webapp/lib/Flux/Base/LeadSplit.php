<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class LeadSplit extends MongoForm {
	
	const DISPOSITION_UNFULFILLED = 0;
	const DISPOSITION_FULFILLED = 1;
	const DISPOSITION_PENDING = 2;
	const DISPOSITION_UNFULFILLABLE = 3;
	const DISPOSITION_ALREADY_FULFILLED = 4;
	const DISPOSITION_PROCESSING = 5;
	const DISPOSITION_FAILOVER = 6;
	const DISPOSITION_CONFIRMED = 7;
	const DISPOSITION_RETURNED = 8;
	
	protected $disposition;
	protected $lead;
	protected $split;
	protected $created_time;
	protected $attempt_count;
	protected $debug;
	
	protected $error_message;
	protected $is_error;
	protected $is_fulfilled;
	protected $is_processing;
	protected $is_catch_all;
	protected $is_unfulfillable;
	
	protected $queue_time;
	protected $last_attempt_time;
	protected $next_attempt_time;
	protected $next_cleanup_time;
	
	protected $is_confirmed;
	protected $is_returned;
	protected $bounty;
	protected $confirmed_note;
	protected $returned_note;
	
	protected $attempts;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('lead_split');
		$this->setDbName('lead');
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
		$this->is_fulfilled = (boolean)$arg0;
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
		$this->is_processing = (boolean)$arg0;
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
		$this->is_unfulfillable = (boolean)$arg0;
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
		$this->is_error = (boolean)$arg0;
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
		$this->attempt_count = (int)$arg0;
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
		if (is_null($arg0)) {
			$this->last_attempt_time = null;
		} else if (is_string($arg0)) {
			$this->last_attempt_time = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->last_attempt_time = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->last_attempt_time = $arg0;
		}
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
		if (is_null($arg0)) {
			$this->next_attempt_time = null;
		} else if (is_string($arg0)) {
			$this->next_attempt_time = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->next_attempt_time = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->next_attempt_time = $arg0;
		}
		$this->addModifiedColumn('next_attempt_time');
		return $this;
	}
	
	/**
	 * Returns the next_cleanup_time
	 * @return \MongoDate
	 */
	function getNextCleanupTime() {
		if (is_null($this->next_cleanup_time)) {
			$this->next_cleanup_time = new \MongoDate();
		}
		return $this->next_cleanup_time;
	}
	
	/**
	 * Sets the next_cleanup_time
	 * @var \MongoDate
	 */
	function setNextCleanupTime($arg0) {
		if (is_null($arg0)) {
			$this->next_cleanup_time = null;
		} else if (is_string($arg0)) {
			$this->next_cleanup_time = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->next_cleanup_time = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->next_cleanup_time = $arg0;
		}
		$this->addModifiedColumn("next_cleanup_time");
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
		if (is_null($arg0)) {
			$this->queue_time = null;
		} else if (is_string($arg0)) {
			$this->queue_time = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->queue_time = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->queue_time = $arg0;
		}
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
	 * Returns the expire_at
	 * @return \MongoDate|null
	 */
	function getExpireAt() {
		if (is_null($this->expire_at)) {
			$this->expire_at = null;
		}
		return $this->expire_at;
	}
	
	/**
	 * Sets the expire_at
	 * @var \MongoDate|null
	 */
	function setExpireAt($arg0) {
		if (is_null($arg0)) {
			$this->expire_at = null;
		} else if (is_string($arg0)) {
			$this->expire_at = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->expire_at = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->expire_at = $arg0;
		}
		$this->addModifiedColumn("expire_at");
		return $this;
	}
	
	/**
	 * Returns the this
	 * @return \Flux\Link\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Link\Lead();
		}
		return $this->lead;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setLead($arg0) {
		if (is_array($arg0)) {
			$lead = $this->getLead();
			$lead->populate($arg0);
			$this->lead = $lead;
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$lead = $this->getLead();
			$lead->setId($arg0);
			$this->lead = $lead;
		} else if ($arg0 instanceof \MongoId) {
			$lead = $this->getLead();
			$lead->setId($arg0);
			$this->lead = $lead;
		}
		$this->addModifiedColumn('lead');
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
	 * @var \Flux\Link\Split
	 */
	function setSplit($arg0) {
		if (is_array($arg0)) {
			$split = $this->getSplit();
			$split->populate($arg0);
			$this->split = $split;
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setName($split->getSplit()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$split = $this->getSplit();
			$split->setId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setName($split->getSplit()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
			$this->split = $split;
			
		} else if ($arg0 instanceof \MongoId) {
			$split = $this->getSplit();
			$split->setId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setName($split->getSplit()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
			$this->split = $split;
		}
		$this->addModifiedColumn("split");
		return $this;
	}
	
	/**
	 * Returns the created_time
	 * @return \MongoDate
	 */
	function getCreatedTime() {
		if (is_null($this->created_time)) {
			$this->created_time = new \MongoDate();
		}
		return $this->created_time;
	}
	
	/**
	 * Sets the created_time
	 * @var \MongoDate
	 */
	function setCreatedTime($arg0) {
		$this->created_time = $arg0;
		$this->addModifiedColumn("created_time");
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
				$lead_split_attempt = new \Flux\LeadSplitAttempt();
				$lead_split_attempt->populate($attempt);
				array_push($this->attempts, $lead_split_attempt);
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
			$this->attempts = array_slice($this->attempts, 0, 10);
		} else {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Attempts are not an array!");
			
		}
		$this->addModifiedColumn('attempts');
		return $this;
	}
	
	/**
	 * Sets the attempts
	 * @var array
	 */
	function addAttempt($arg0) {
		if ($arg0 instanceof \Flux\LeadSplitAttempt) {
			// Add the attempt to the array
			$attempts = $this->getAttempts();
			array_push($attempts, $arg0);
			$this->setAttemptCount(count($attempts));
			$this->setAttempts($attempts);
		} else if (is_array($arg0)) {
			/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
			$lead_split_attempt = new \Flux\LeadSplitAttempt();
			$lead_split_attempt->populate($arg0);
	
			// Add the attempt to the array
			$attempts = $this->getAttempts();
			array_push($attempts, $lead_split_attempt);
			$this->setAttemptCount(count($attempts));
			$this->setAttempts($attempts);
		}
		$this->addModifiedColumn('attempts');
		return $this;
	}
	
	/**
	 * Returns the is_confirmed
	 * @return boolean
	 */
	function getIsConfirmed() {
		if (is_null($this->is_confirmed)) {
			$this->is_confirmed = false;
		}
		return $this->is_confirmed;
	}
	
	/**
	 * Sets the is_confirmed
	 * @var boolean
	 */
	function setIsConfirmed($arg0) {
		$this->is_confirmed = $arg0;
		$this->addModifiedColumn("is_confirmed");
		return $this;
	}
	
	/**
	 * Returns the is_returned
	 * @return boolean
	 */
	function getIsReturned() {
		if (is_null($this->is_returned)) {
			$this->is_returned = false;
		}
		return $this->is_returned;
	}
	
	/**
	 * Sets the is_returned
	 * @var boolean
	 */
	function setIsReturned($arg0) {
		$this->is_returned = (boolean)$arg0;
		$this->addModifiedColumn("is_returned");
		return $this;
	}
	
	/**
	 * Returns the returned_note
	 * @return string
	 */
	function getReturnedNote() {
		if (is_null($this->returned_note)) {
			$this->returned_note = "";
		}
		return $this->returned_note;
	}
	
	/**
	 * Sets the returned_note
	 * @var string
	 */
	function setReturnedNote($arg0) {
		$this->returned_note = $arg0;
		$this->addModifiedColumn("returned_note");
		return $this;
	}
	
	/**
	 * Returns the bounty
	 * @return float
	 */
	function getBounty() {
		if (is_null($this->bounty)) {
			$this->bounty = 0.00;
		}
		return $this->bounty;
	}
	
	/**
	 * Sets the bounty
	 * @var float
	 */
	function setBounty($arg0) {
		$this->bounty = $arg0;
		$this->addModifiedColumn("bounty");
		return $this;
	}
	
	/**
	 * Returns the confirmed_note
	 * @return string
	 */
	function getConfirmedNote() {
		if (is_null($this->confirmed_note)) {
			$this->confirmed_note = "";
		}
		return $this->confirmed_note;
	}
	
	/**
	 * Sets the confirmed_note
	 * @var string
	 */
	function setConfirmedNote($arg0) {
		$this->confirmed_note = $arg0;
		$this->addModifiedColumn("confirmed_note");
		return $this;
	}
	
	/**
	 * Creates indexes for this collection
	 * @return boolean
	 */
	static function ensureIndexes() {
		$exception = null;
		$indexes = array();
		$indexes[] = array('idx' => array('lead._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array('split._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array('disposition' => 1, 'last_attempt_time' => 1), 'options' => array('background' => true));
	
		foreach ($indexes as $index) {
			try {
				$collection = new self();
				$collection->getCollection()->createIndex($index['idx'], $index['options']);
			} catch (\Exception $e) {
				$exception = $e;
			}
		}
	
		// Enable sharding as well
		/*
		$shards[] = array('idx' => array('_id' => 1));
		foreach ($shards as $shard) {
		try {
		$collection = new self();
	
		$shardDB = \Mojavi\Controller\Controller::getInstance()->getContext()->getDatabaseManager()->getDatabase($collection->getDbName())->getParameter('database');
		$shardCollection =  $shardDB . '.' . $collection->getCollectionName();
	
		$r = $collection->getConnection('mongo_admin')->command(array('shardCollection' => $shardCollection, 'key' => $shard['idx']));
	
		if (isset($r['ok']) && $r['ok'] == 0 && isset($r['errmsg'])) {
		throw new \Exception($r['errmsg']);
		}
		} catch (\Exception $e) {
		if (strpos($e->getMessage(), 'already sharded') === false && strpos($e->getMessage(), 'no such command') === false) {
		$exception = $e;
		}
		}
		}
		*/
		if (!is_null($exception)) { throw $exception; }
	}
}