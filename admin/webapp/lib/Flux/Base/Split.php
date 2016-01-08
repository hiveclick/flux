<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Split extends MongoForm {

	const SPLIT_TYPE_NORMAL = 1;
	const SPLIT_TYPE_CATCH_ALL = 2;
	const SPLIT_TYPE_HOST_POST = 3;
	
	const SPLIT_STATUS_ACTIVE = 1;
	const SPLIT_STATUS_INACTIVE = 2;
	const SPLIT_STATUS_DELETED = 3;

	const SPLIT_SEND_TYPE_ALL = 1;
	const SPLIT_SEND_TYPE_ROTATE = 2;

	const SPLIT_NODE_TYPE_RULE_FIRST = 1;
	const SPLIT_NODE_TYPE_RULE = 2;
	const SPLIT_NODE_TYPE_EXPORT = 3;

	protected $name;
	protected $description;
	protected $status;
	protected $split_type;
	protected $fulfill_immediately;
	protected $fulfill_delay;
	
	protected $failover_enable;
	protected $failover_split;
	protected $failover_wait_time;

	protected $send_type;
	protected $offers;
	protected $fulfillment;
	protected $filters;
	protected $validators;
	protected $email_notification;
	
	protected $scheduling;
	
	protected $queue_count;
	protected $daily_leads;
	protected $hourly_leads;
	protected $yesterday_leads;
	protected $last_queue_time;
	protected $last_run_time;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('split');
		$this->setDbName('admin');
	}

	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn('name');
		return $this;
	}
	
	/**
	 * Returns the split_type
	 * @return integer
	 */
	function getSplitType() {
		if (is_null($this->split_type)) {
			$this->split_type = 0;
		}
		return $this->split_type;
	}
	
	/**
	 * Sets the split_type
	 * @var integer
	 */
	function setSplitType($arg0) {
		$this->split_type = (int)$arg0;
		$this->addModifiedColumn("split_type");
		return $this;
	}
	
	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}

	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn('description');
		return $this;
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::SPLIT_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
		return $this;
	}
	
	/**
	 * Returns the email_notification
	 * @return string
	 */
	function getEmailNotification() {
		if (is_null($this->email_notification)) {
			$this->email_notification = "";
		}
		return $this->email_notification;
	}
	
	/**
	 * Sets the email_notification
	 * @var string
	 */
	function setEmailNotification($arg0) {
		$this->email_notification = $arg0;
		return $this;
	}

	/**
	 * Returns the send_type
	 * @return integer
	 */
	function getSendType() {
		if (is_null($this->send_type)) {
			$this->send_type = self::SPLIT_SEND_TYPE_ALL;
		}
		return $this->send_type;
	}

	/**
	 * Sets the send_type
	 * @var integer
	 */
	function setSendType($arg0) {
		$this->send_type = $arg0;
		$this->addModifiedColumn('send_type');
		return $this;
	}
	
	/**
	 * Returns the fulfill_immediately
	 * @return boolean
	 */
	function getFulfillImmediately() {
		if (is_null($this->fulfill_immediately)) {
			$this->fulfill_immediately = false;
		}
		return $this->fulfill_immediately;
	}
	
	/**
	 * Sets the fulfill_immediately
	 * @var boolean
	 */
	function setFulfillImmediately($arg0) {
		$this->fulfill_immediately = (boolean)$arg0;
		$this->addModifiedColumn("fulfill_immediately");
		return $this;
	}
	
	/**
	 * Returns the fulfill_delay
	 * @return integer
	 */
	function getFulfillDelay() {
		if (is_null($this->fulfill_delay)) {
			$this->fulfill_delay = 0;
		}
		return $this->fulfill_delay;
	}
	
	/**
	 * Sets the fulfill_delay
	 * @var integer
	 */
	function setFulfillDelay($arg0) {
		$this->fulfill_delay = (int)$arg0;
		$this->addModifiedColumn("fulfill_delay");
		return $this;
	}
	
	/**
	 * Returns the failover_enable
	 * @return boolean
	 */
	function getFailoverEnable() {
		if (is_null($this->failover_enable)) {
			$this->failover_enable = false;
		}
		return $this->failover_enable;
	}
	
	/**
	 * Sets the failover_enable
	 * @var boolean
	 */
	function setFailoverEnable($arg0) {
		$this->failover_enable = (boolean)$arg0;
		$this->addModifiedColumn("failover_enable");
		return $this;
	}
	
	/**
	 * Returns the failover_wait_time
	 * @return integer
	 */
	function getFailoverWaitTime() {
		if (is_null($this->failover_wait_time)) {
			$this->failover_wait_time = 0;
		}
		return $this->failover_wait_time;
	}
	
	/**
	 * Sets the failover_wait_time
	 * @var integer
	 */
	function setFailoverWaitTime($arg0) {
		$this->failover_wait_time = (int)$arg0;
		$this->addModifiedColumn("failover_wait_time");
		return $this;
	}
	
	/**
	 * Returns the failover_split
	 * @return \Flux\Link\Split
	 */
	function getFailoverSplit() {
		if (is_null($this->failover_split)) {
			$this->failover_split = new \Flux\Link\Split();
		}
		return $this->failover_split;
	}
	
	/**
	 * Sets the failover_split
	 * @var \Flux\Split
	 */
	function setFailoverSplit($arg0) {
		if (is_array($arg0)) {
			$split = new \Flux\Link\Split();
			$split->populate($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->failover_split = $split;
		} else if (is_string($arg0)) {
			$split = new \Flux\Link\Split();
			$split->setSplitId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->failover_split = $split;
		} else if ($arg0 instanceof \MongoId) {
			$split = new \Flux\Link\Split();
			$split->setSplitId($arg0);
			if (\MongoId::isValid($split->getId()) && $split->getName() == '') {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->failover_split = $split;
		}
		$this->addModifiedColumn('failover_split');
		return $this;
	}
	
	

	/**
	 * Returns the offers
	 * @return integer
	 */
	function getOffers() {
		if (is_null($this->offers)) {
			$this->offers = array();
		}
		return $this->offers;
	}

	/**
	 * Sets the offers
	 * @var integer
	 */
	function setOffers($arg0) {
		if (is_array($arg0)) {
			$this->offers = $arg0;
			array_walk($this->offers, function(&$val, $key) { 
				$offer = new \Flux\Link\Offer();
				$offer->populate($val);
				if (\MongoId::isValid($offer->getId()) && $offer->getName() == '') {
					$offer->setOfferName($offer->getOffer()->getName());
				}
				$val = $offer; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->offers = array();
			} else if (is_string($arg0)) {
				$this->offers = implode(',', $arg0);
				array_walk($this->offers, function(&$val, $key) { 
					$offer = new \Flux\Link\Offer();
					$offer->setOfferId($val);
					if (\MongoId::isValid($offer->getId()) && $offer->getName() == '') {
						$offer->setOfferName($offer->getOffer()->getName());
					}
					$val = $offer; 
				});
			}
		}
		$this->addModifiedColumn('offers');
		return $this;
	}

	/**
	 * Returns the fulfillment
	 * @return \Flux\Link\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Link\Fulfillment();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Sets the fulfillment
	 * @var \Flux\Link\Fulfillment
	 */
	function setFulfillment($arg0) {
		if (is_array($arg0)) {
			$fulfillment = new \Flux\Link\Fulfillment();
			$fulfillment->populate($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if (is_string($arg0)) {
			$fulfillment = new \Flux\Link\Fulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if ($arg0 instanceof \MongoId) {
			$fulfillment = new \Flux\Link\Fulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		}
		$this->addModifiedColumn('fulfillment');
		return $this;
	}

	/**
	 * Returns the filters
	 * @return array
	 */
	function getFilters() {
		if (is_null($this->filters)) {
			$this->filters = array();
		}
		return $this->filters;
	}

	/**
	 * Sets the filters
	 * @var array
	 */
	function setFilters($arg0) {
		if (is_array($arg0)) {
			$this->filters = $arg0;
			array_walk($this->filters, function(&$val, $key) { 
				$filter = new \Flux\Link\DataField();
				$filter->populate($val);
				if ($filter->getDataFieldKeyName() != '' && is_null($filter->getId())) {
					$filter->setDataFieldId($filter->getDataField()->getId());
				}
				if (\MongoId::isValid($filter->getId()) && $filter->getDataFieldKeyName() == '') {
					$filter->setDataFieldKeyName($filter->getDataField()->getKeyName());
				}
				if (\MongoId::isValid($filter->getId()) && $filter->getName() == '') {
					$filter->setDataFieldName($filter->getDataField()->getName());
				}
				$val = $filter; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->filters = array();
			} else if (is_string($arg0)) {
				$this->filters = implode(',', $arg0);
				array_walk($this->filters, function(&$val, $key) { 
					$filter = new \Flux\Link\DataField();
					$filter->setDataFieldKeyName($val);
					if ($filter->getDataFieldKeyName() != '' && is_null($filter->getId())) {
						$filter->setDataFieldId($filter->getDataField()->getId());
					}
					if ($filter->getDataFieldKeyName() != '' && $filter->getName() == '') {
						$filter->setDataFieldName($filter->getDataField()->getName());
					}
					$val = $filter; 
				});
			}
		}
		$this->addModifiedColumn('filters');
		return $this;
	}
	
	/**
	 * Returns the validators
	 * @return array
	 */
	function getValidators() {
		if (is_null($this->validators)) {
			$this->validators = array();
		}
		return $this->validators;
	}
	
	/**
	 * Sets the validators
	 * @var array
	 */
	function setValidators($arg0) {
		if (is_array($arg0)) {
			$this->validators = $arg0;
			array_walk($this->validators, function(&$val, $key) {
				$validator = new \Flux\Link\DataField();
				$validator->populate($val);
				if ($validator->getDataFieldKeyName() != '' && is_null($validator->getId())) {
					$validator->setDataFieldId($validator->getDataField()->getId());
				}
				if (\MongoId::isValid($validator->getId()) && $validator->getDataFieldKeyName() == '') {
					$validator->setDataFieldKeyName($validator->getDataField()->getKeyName());
				}
				if (\MongoId::isValid($validator->getId()) && $validator->getName() == '') {
					$validator->setDataFieldName($validator->getDataField()->getName());
				}
				$val = $validator;
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->validators = array();
			} else if (is_string($arg0)) {
				$this->validators = implode(',', $arg0);
				array_walk($this->validators, function(&$val, $key) {
					$validator = new \Flux\Link\DataField();
					$validator->setDataFieldKeyName($val);
					if ($validator->getDataFieldKeyName() != '' && is_null($validator->getId())) {
						$validator->setDataFieldId($validator->getDataField()->getId());
					}
					if ($validator->getDataFieldKeyName() != '' && $validator->getName() == '') {
						$validator->setDataFieldName($validator->getDataField()->getName());
					}
					$val = $validator;
				});
			}
		}
		$this->addModifiedColumn('validators');
		return $this;
	}
	
	/**
	 * Returns the last_run_time
	 * @return string
	 */
	function getLastRunTime() {
		if (is_null($this->last_run_time)) {
			$this->last_run_time = new \MongoDate();
		}
		return $this->last_run_time;
	}
	
	/**
	 * Sets the last_run_time
	 * @var string
	 */
	function setLastRunTime($arg0) {
		$this->last_run_time = $arg0;
		$this->addModifiedColumn('last_run_time');
		return $this;
	}
	
	/**
	 * Returns the last_queue_time
	 * @return MongoDate
	 */
	function getLastQueueTime() {
		if (is_null($this->last_queue_time)) {
			$this->last_queue_time = null;
		}
		return $this->last_queue_time;
	}
	
	/**
	 * Sets the last_queue_time
	 * @var MongoDate
	 */
	function setLastQueueTime($arg0) {
		$this->last_queue_time = $arg0;
		return $this;
	}
	
	/**
	 * Returns the queue_count
	 * @return integer
	 */
	function getYesterdayCount() {
		$split_queue = new \Flux\SplitQueue($this->getId());
		return $split_queue->count(array('split._id' => $this->getId(), 'queue_time' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime('yesterday')))), '$lt' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime('yesterday')))))));
	}
	
	/**
	 * Returns the queue_count
	 * @return integer
	 */
	function getDailyCount() {
	   $split_queue = new \Flux\SplitQueue($this->getId());
	   return $split_queue->count(array('split._id' => $this->getId(), 'queue_time' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00'))))));
	}
	
	/**
	 * Returns the queue_count
	 * @return integer
	 */
	function getHourlyCount() {
		$split_queue = new \Flux\SplitQueue($this->getId());
		return $split_queue->count(array('split._id' => $this->getId(), 'queue_time' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y h:00:00'))))));
	}
	
	/**
	 * Returns the queue_count
	 * @return integer
	 */
	function getQueueCount() {
		if (is_null($this->queue_count)) {
			$this->queue_count = 0;
		}
		return $this->queue_count;
	}
	
	/**
	 * Sets the queue_count
	 * @var integer
	 */
	function setQueueCount($arg0) {
		$this->queue_count = $arg0;
		$this->addModifiedColumn("queue_count");
		return $this;
	}
	
	/**
	 * Returns the scheduling
	 * @return \Flux\Link\Schedule
	 */
	function getScheduling() {
		if (is_null($this->scheduling)) {
			$this->scheduling = new \Flux\Link\Schedule();
		}
		return $this->scheduling;
	}
	
	/**
	 * Sets the scheduling
	 * @var \Flux\Link\Schedule
	 */
	function setScheduling($arg0) {
		if (is_array($arg0)) {
			$this->scheduling = new \Flux\Link\Schedule();
			$this->scheduling->populate($arg0);
			$this->addModifiedColumn('scheduling');
		}
		return $this;
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
