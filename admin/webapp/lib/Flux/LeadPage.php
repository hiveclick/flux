<?php
namespace Flux;

class LeadPage extends Base\LeadPage {
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Returns the pages based on the criteria
	 * @return Flux\LeadPage
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (\MongoId::isValid($this->getLead()->getLeadId())) {
			$criteria['lead._id'] = $this->getLead()->getLeadId();
		}
		return parent::queryAll($criteria, $hydrate, $fields);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$lead_page = new self();
		$lead_page->getCollection()->ensureIndex(array('lead._id' => 1, 'offer_page._id' => 1), array('background' => true, 'unique' => true));
		$lead_page->getCollection()->ensureIndex(array('offer._id' => 1), array('background' => true));
		$lead_page->getCollection()->ensureIndex(array('offer_page._id' => 1), array('background' => true));
		$lead_page->getCollection()->ensureIndex(array('lead._id' => 1), array('background' => true));
		return true;
	}
	
	
}