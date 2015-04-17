<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class Generic extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_NONE);
		$this->setName('Generic Export');
		$this->setDescription('An export that doesn\'t do anything at all');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $split_queue_attempts
	 * @return boolean
	 */
	function send($split_queue_attempts, $is_test = false) {
	    foreach ($split_queue_attempts as $split_queue_attempt) {
	        $params = $split_queue_attempt->mergeLead();
	        $url = $split_queue_attempt->getFulfillment()->getFulfillment()->getPostUrl();
	        
	        $url = $url . '?' . http_build_query($params, null, '&');
	        $split_queue_attempt->setRequest($url);
	        $split_queue_attempt->setResponse('SUCCESSFUL TEST');
	        $split_queue_attempt->setIsError(false);
	    }
		return $split_queue_attempts;
	}
}
?>