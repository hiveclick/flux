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
	 * @param array|MongoCursor $lead_split_attempts
	 * @return boolean
	 */
	function send($lead_split_attempts, $is_test = false) {
		foreach ($lead_split_attempts as $lead_split_attempt) {
			$params = $lead_split_attempt->mergeLead();
			$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();
			
			$url = $url . '?' . http_build_query($params, null, '&');
			$lead_split_attempt->setRequest($url);
			$lead_split_attempt->setResponse('SUCCESSFUL TEST');
			$lead_split_attempt->setIsError(false);
		}
		return $lead_split_attempts;
	}
}
?>