<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericManual extends Generic {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_MANUAL);
		$this->setName('Manually Fulfilled Lead');
		$this->setDescription('Leads that are fulfilled offline and just need to be flagged as fulfilled.');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $split_queue_items
	 * @return boolean
	 */
	function send($lead_split_attempts, $is_test = false) {
		if ($is_test) {
			// If this is just a test, then do basic formatting, then exit
			foreach ($lead_split_attempts as $lead_split_attempt) {
				$params = $lead_split_attempt->mergeLead();
				$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();
				 
				$url = $url . '?' . http_build_query($params, null, '&');
				$lead_split_attempt->setRequest($url);
				$lead_split_attempt->setResponse('SUCCESSFUL TEST');
				$lead_split_attempt->setIsError(false);
				$lead_split_attempt->setAttemptTime(new \MongoDate());
				$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
			}
			
			return $lead_split_attempts;
		}
		// If this is just a test, then do basic formatting, then exit
		foreach ($lead_split_attempts as $lead_split_attempt) {
			$lead_split_attempt->setAttemptTime(microtime(true));
			$lead_split_attempt->setResponse('MANUALLY FULFILLED');
			$this->recordLeadPayout($lead_split_attempt, 'MANUALLY FULFILLED');
			$lead_split_attempt->setIsError(false);
			$lead_split_attempt->setAttemptTime(new \MongoDate());
			$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
		}
		 
		return $lead_split_attempts;
	}
}