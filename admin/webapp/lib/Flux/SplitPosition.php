<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class SplitPosition extends MongoForm {

	protected $client_export_id;
	protected $split_id;

	protected $daily_cap_count;
	protected $cap;
	protected $revenue;

	protected $domain_group_id;
	protected $data_field_id;
	protected $position;

	private $client_export;
	private $split;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('split_position');
		$this->setDbName('admin');
	}

	/**
	 * Returns the client_export_id
	 * @return integer
	 */
	function getClientExportId() {
		if (is_null($this->client_export_id)) {
			$this->client_export_id = 0;
		}
		return $this->client_export_id;
	}

	/**
	 * Sets the client_export_id
	 * @var integer
	 */
	function setClientExportId($arg0) {
		$this->client_export_id = (int)$arg0;
		$this->addModifiedColumn('client_export_id');
		return $this;
	}

	/**
	 * Returns the cap
	 * @return integer
	 */
	function getCap() {
		if (is_null($this->cap)) {
			$this->cap = 0;
		}
		return $this->cap;
	}

	/**
	 * Sets the cap
	 * @var integer
	 */
	function setCap($arg0) {
		$this->cap = (int)$arg0;
		$this->addModifiedColumn('cap');
		return $this;
	}

	/**
	 * Returns the revenue
	 * @return float
	 */
	function getRevenue() {
		if (is_null($this->revenue)) {
			$this->revenue = 0.00;
		}
		return $this->revenue;
	}

	/**
	 * Sets the revenue
	 * @var float
	 */
	function setRevenue($arg0) {
		$this->revenue = (float)$arg0;
		$this->addModifiedColumn('revenue');
		return $this;
	}

	/**
	 * Returns the domain_group_id
	 * @return array
	 */
	function getDomainGroupId() {
		if (is_null($this->domain_group_id)) {
			$this->domain_group_id = array();
		}
		return $this->domain_group_id;
	}

	/**
	 * Sets the domain_group_id
	 * @var array
	 */
	function setDomainGroupId($arg0) {
		if (is_array($arg0)) {
			$this->domain_group_id = $arg0;
			array_walk($this->domain_group_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->domain_group_id = implode(',', $arg0);
				array_walk($this->domain_group_id, function(&$val, $key) { $val = (int)$val; });
			} else {
				$this->domain_group_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('domain_group_id');
		return $this;
	}

	/**
	 * Returns the data_field_id
	 * @return array
	 */
	function getDataFieldId() {
		if (is_null($this->data_field_id)) {
			$this->data_field_id = array();
		}
		return $this->data_field_id;
	}

	/**
	 * Sets the data_field_id
	 * @var array
	 */
	function setDataFieldId($arg0) {
		if (is_array($arg0)) {
			$this->data_field_id = $arg0;
			array_walk($this->data_field_id, function(&$val, $key) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',')) {
				$this->data_field_id = implode(',', $arg0);
				array_walk($this->data_field_id, function(&$val, $key) { $val = (int)$val; });
			} else {
				$this->data_field_id = array((int)$arg0);
			}
		}
		$this->addModifiedColumn('data_field_id');
		return $this;
	}

	/**
	 * Returns the position
	 * @return integer
	 */
	function getPosition() {
		if (is_null($this->position)) {
			$this->position = 0;
		}
		return $this->position;
	}

	/**
	 * Sets the position
	 * @var integer
	 */
	function setPosition($arg0) {
		$this->position = (int)$arg0;
		$this->addModifiedColumn('position');
		return $this;
	}
	
	/**
	 * Returns the daily_cap_count
	 * @return integer
	 */
	function getDailyCapCount() {
		if (is_null($this->daily_cap_count)) {
			$this->daily_cap_count = 0;
		}
		return $this->daily_cap_count;
	}
	
	/**
	 * Sets the daily_cap_count
	 * @var integer
	 */
	function setDailyCapCount($arg0) {
		$this->daily_cap_count = $arg0;
		$this->addModifiedColumn('daily_cap_count');
		return $this;
	}
	
	/**
	 * Sets the daily_cap_count
	 * @var integer
	 */
	function incrementDailyCapCount() {
		$this->daily_cap_count++;
		$this->addModifiedColumn('daily_cap_count');
		return $this;
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
		$this->split_id = (int)$arg0;
		$this->addModifiedColumn('split_id');
		return $this;
	}

	/**
	 * Returns the split
	 * @return \Flux\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Split();
			$this->split->setId($this->getSplitId());
			$this->split->query();
		}
		return $this->split;
	}

	/**
	 * Returns the client_export
	 * @return \Flux\ClientExport
	 */
	function getClientExport() {
		if (is_null($this->client_export)) {
			$this->client_export = new \Flux\ClientExport();
			$this->client_export->setId($this->getClientExportId());
			$this->client_export->query();
		}
		return $this->client_export;
	}
	
	/**
	 * Checks if this recipient can receive a lead
	 * @return boolean
	 */
	function sendLead($lead) {
		/*
		if ($this->getClientExport()->getExportType() == \Flux\ClientExport::CLIENT_EXPORT_TYPE_REALTIME) {
			$this->update(array(), array('$inc' => array('daily_cap_count' => 1)));
			return $this->getClientExport()->sendLeadViaPostUrl($lead);
		} else {
		*/
			// Add the lead to an export queue to be sent at a later time
			
			// First, find an export linked to this split for today
			$export = new \Flux\Export();
			$export->setSplitId($this->getSplitId());
			$export->setClientExportId($this->getClientExportId());
			$export->setIsRunning(false);
			$export->setIsComplete(false);
			$export = $export->queryBySplitAndClientExport();
			if (is_null($export)) {
				// Create a new export
				$export = new \Flux\Export();
				$export->setSplitId($this->getSplitId());
				$export->setClientExportId($this->getClientExportId());
				$export->setExportDate(new \MongoDate());
				$insert_id = $export->insert();
				$export->setId($insert_id);
			}
			
			/* @var $export_queue \Flux\SplitQueue */
			$export_queue = new \Flux\ExportQueue($export->getId());
			$export_queue->queueLead($lead);
			
			$export->update(array(), array('$inc' => array('num_records' => 1), '$set' => array('percent_complete' => 0)));
			$this->update(array(), array('$inc' => array('daily_cap_count' => 1)));
			$this->incrementDailyCapCount();
			return true;
		//}
	}
	
	/**
	 * Checks if this recipient can receive a lead
	 * @return boolean
	 */
	function canReceiveLead($lead) {
		/* @var $export_class \Flux\Export\ExportAbstract */
		$export_class = $this->getClientExport()->getExportClass();
		$export_class->setSplitId($this->getSplitId());
		$export_class->setSplitPositionId($this->getId());
		return $export_class->canReceiveLead($lead);
	}

	/**
	 * Queries all records
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getSplitId() > 0) {
			$criteria['split_id'] = $this->getSplitId();
		}
		if ($this->getPosition() > 0) {
			$criteria['position'] = $this->getPosition();
		}
		return parent::queryAll($criteria, $hydrate);
	}
}
