<?php
namespace Flux;

class SplitPosition extends Base\SplitPosition {
	
	private $split_id_array;
	
	/**
	 * Returns the split_id_array
	 * @return array
	 */
	function getSplitIdArray() {
		if (is_null($this->split_id_array)) {
			$this->split_id_array = array();
		}
		return $this->split_id_array;
	}
	
	/**
	 * Sets the split_id_array
	 * @var array
	 */
	function setSplitIdArray($arg0) {
		$this->split_id_array = $arg0;
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
	 * Checks if this recipient can receive a lead
	 * @param $lead \Flux\Lead
	 * @return boolean
	 */
	function sendLead($lead) {
		/*
		if ($this->getFulfillment()->getExportType() == \Flux\Fulfillment::CLIENT_EXPORT_TYPE_REALTIME) {
			$this->update(array(), array('$inc' => array('daily_cap_count' => 1)));
			return $this->getFulfillment()->sendLeadViaPostUrl($lead);
		} else {
		*/
			// Add the lead to an export queue to be sent at a later time
			
			// First, find an export linked to this split for today
			$export = new \Flux\Export();
			$export->setSplit($this->getSplit()->getSplitId());
			$export->setFulfillment($this->getFulfillment()->getFulfillmentId());
			$export->setIsRunning(false);
			$export->setIsComplete(false);
			$export = $export->queryBySplitAndFulfillment();
			
			if ($export === false) {
				// Create a new export
				$export = new \Flux\Export();
				$export->setName('Export for ' . $this->getSplit()->getSplitName() . ' on ' . date('m/d/Y'));
				$export->setSplit($this->getSplit()->getSplitId());
				$export->setFulfillment($this->getFulfillment()->getFulfillmentId());
				$export->setExportDate(new \MongoDate());
				$insert_id = $export->insert();
				$export->setId($insert_id);
			} else if (($export->getName()) == '') {
				$export->setName('Export for ' . $this->getSplit()->getSplitName() . ' on ' . date('m/d/Y'));
				$export->update();
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
		$export_class = $this->getFulfillment()->getFulfillment()->getExportClass();
		$export_class->setSplit($this->getSplit()->getSplitId());
		$export_class->setSplitPosition($this->getId());
		return $export_class->canReceiveLead($lead);
	}

	/**
	 * Queries all records
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (count($this->getSplitIdArray()) > 0) {
			$criteria['split.split_id'] = array('$in' => $this->getSplitIdArray());
		}
		if ($this->getPosition() > 0) {
			$criteria['position'] = $this->getPosition();
		}
		return parent::queryAll($criteria, $hydrate);
	}
}
