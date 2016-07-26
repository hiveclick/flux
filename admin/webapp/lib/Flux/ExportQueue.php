<?php
namespace Flux;

class ExportQueue extends Base\ExportQueue {
	
	/**
	 * Queries all records from a queue
	 * @return array
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		if (trim($this->getLead()->getLeadId()) != '') {
			$criteria['lead._id'] = trim($this->getLead()->getLeadId());
		}
		return parent::queryAll($criteria, $fields, $hydrate, $timeout);
	}
	
	/**
	 * Queues a lead as a post url
	 * @param $lead \Flux\Lead
	 * @return boolean
	 */
	function queueLead($lead) {
		$params = array();
		/* @var $mapping \Flux\FulfillmentMap */
		foreach ($this->getExport()->getFulfillment()->getMapping() as $mapping) {
			$value = $mapping->getMappedValue($lead);
			if (trim($mapping->getFieldName()) == '') {
				$params[$mapping->getDataField()->getKeyName()] = $value;
			} else {
				$params[$mapping->getFieldName()] = $value;
			}
		}
				
		//$this->setUrl($url);
		$this->setQs($params);
		$this->setIsError(false);
		$this->setResponse(null);
		$this->setResponseTime(0);
		$this->setLead($lead->getId());
		return parent::insert();
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$export_queue = new self();
		$export_queue->getCollection()->ensureIndex(array('lead._id' => 1), array('background' => true));
		$export_queue->getCollection()->ensureIndex(array('export._id' => 1), array('background' => true));
		$export_queue->getCollection()->ensureIndex(array('split._id' => 1), array('background' => true));
		return true;
	}
	
	
}
