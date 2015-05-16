<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericManual extends ExportAbstract {
	
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
	function send($split_queue_attempts, $is_test = false) {
	    if ($is_test) {
	        // If this is just a test, then do basic formatting, then exit
	        foreach ($split_queue_attempts as $split_queue_attempt) {
	            $params = $split_queue_attempt->mergeLead();
	            $url = $split_queue_attempt->getFulfillment()->getFulfillment()->getPostUrl();
	             
	            $url = $url . '?' . http_build_query($params, null, '&');
	            $split_queue_attempt->setRequest($url);
	            $split_queue_attempt->setResponse('SUCCESSFUL TEST');
	            $split_queue_attempt->setIsError(false);
	            $split_queue_attempt->setAttemptTime(new \MongoDate());
	            $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
	        }
	        
	        return $split_queue_attempts;
	    }
	    // If this is just a test, then do basic formatting, then exit
	    foreach ($split_queue_attempts as $split_queue_attempt) {
	        $split_queue_attempt->setStartTime(microtime(true));
	        $split_queue_attempt->setResponse('MANUALLY FULFILLED');
	        $split_queue_attempt->setIsError(false);
	        $split_queue_attempt->setAttemptTime(new \MongoDate());
	        $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
	    }
	     
	    return $split_queue_attempts;
	}
}