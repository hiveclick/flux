<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericFormfill extends ExportAbstract {
	
	const WINDOW_SIZE = 10;
	
	private $requests;
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_MULTI_POST);
		$this->setName('Generic FORM Fill Export through a tracking url');
		$this->setDescription('Send leads to a POST url via a tracking url');
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
				$tracking_url = $lead_split_attempt->getFulfillment()->getFulfillment()->getTrackingUrl();
				$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();
				 
				$url = $url . '?' . http_build_query($params, null, '&');
				$lead_split_attempt->setRequest($tracking_url . "\n" . $url);
				$lead_split_attempt->setResponse('SUCCESSFUL TEST');
				$lead_split_attempt->setIsError(false);
				$lead_split_attempt->setAttemptTime(new \MongoDate());
				$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getStartTime());
				$lead_split_attempt->setIsError(false);
			}
			
			return $lead_split_attempts;
		}
		
		// If this is not a test, then do the real thing
		$ret_val = array();
		
		// We use a single curl here because we have to handle a double submit and track the cookies
		// If this is just a test, then do basic formatting, then exit
		foreach ($lead_split_attempts as $cursor_item) {
			$lead_split_attempt = new \Flux\LeadSplitAttempt();
			$lead_split_attempt->populate($cursor_item);
			$lead_split_attempt->setStartTime(microtime(true));
			
			$params = $lead_split_attempt->mergeLead();
			$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();
			$tracking_url = $lead_split_attempt->getFulfillment()->getFulfillment()->getTrackingUrl();
		
			// Setup Curl for this request
			$ch = curl_init();
			
			$headers = array();
			$headers[] = 'Accept-Encoding: gzip,deflate';
			
			$cookie_jar = tempnam('/tmp','cookie');
						
			curl_setopt($ch, CURLOPT_URL, $tracking_url);
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSLVERSION, 0);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		
			$tracking_response = curl_exec($ch);
			
			// Now that we have tracked the offer, send the post url
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSLVERSION, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
			
			$response = curl_exec($ch);			
			
			curl_close($ch);
			
			/* @var $lead_split_attempt \Flux\SplitQueueAttempt */
			$lead_split_attempt->setResponse($response);
			$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getStartTime());
			if (strpos($response, $this->getFulfillment()->getFulfillment()->getSuccessMsg()) !== false) {
				$lead_split_attempt->setIsError(false);
			} else {
				$lead_split_attempt->setErrorMessage(str_replace("<", "&lt;", $response));
				$lead_split_attempt->setIsError(true);
			}
			$ret_val[] = $lead_split_attempt;
		}
		return $ret_val;
	}
}