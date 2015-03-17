<?php
namespace Flux;

class SplitQueue extends Base\SplitQueue {

	/**
	 * Queries all records from a queue
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if ($this->getSplit()->getSplitId() > 0) {
			$criteria['split.split_id'] = (int)$this->getSplit()->getSplitId();
		}
		if (trim($this->getLead()->getLeadName()) != '') {
			$criteria['lead.lead_name'] = new \MongoRegex('/' . trim($this->getLead()->getLeadName()) . '/');
		}
		return parent::queryAll($criteria, $hydrate);
	}
}
