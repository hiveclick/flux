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
	function send($split_queue_attempts, $is_test = false) {
	    if ($is_test) {
	        // If this is just a test, then do basic formatting, then exit
	        foreach ($split_queue_attempts as $split_queue_attempt) {
	            $params = $split_queue_attempt->mergeLead();
	            $tracking_url = $split_queue_attempt->getFulfillment()->getFulfillment()->getTrackingUrl();
	            $url = $split_queue_attempt->getFulfillment()->getFulfillment()->getPostUrl();
	             
	            $url = $url . '?' . http_build_query($params, null, '&');
	            $split_queue_attempt->setRequest($tracking_url . "\n" . $url);
	            $split_queue_attempt->setResponse('SUCCESSFUL TEST');
	            $split_queue_attempt->setIsError(false);
	            $split_queue_attempt->setAttemptTime(new \MongoDate());
	            $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
	            $split_queue_attempt->setIsError(false);
	        }
	        
	        return $split_queue_attempts;
	    }
	    
	    // If this is not a test, then do the real thing
		$ret_val = array();
		
		// We use a single curl here because we have to handle a double submit and track the cookies
		// If this is just a test, then do basic formatting, then exit
		foreach ($split_queue_attempts as $cursor_item) {
			$split_queue_attempt = new \Flux\SplitQueueAttempt();
			$split_queue_attempt->populate($cursor_item);
			$split_queue_attempt->setStartTime(microtime(true));
		    
		    $params = $split_queue_attempt->mergeLead();
		    $url = $split_queue_attempt->getFulfillment()->getFulfillment()->getPostUrl();
		    $tracking_url = $split_queue_attempt->getFulfillment()->getFulfillment()->getTrackingUrl();
		
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
    		
    		/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
    		$split_queue_attempt->setResponse($response);
    		$split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
    		if (strpos($response, $this->getFulfillment()->getFulfillment()->getSuccessMsg()) !== false) {
    		    $split_queue_attempt->setIsError(false);
    		} else {
    		    $split_queue_attempt->setErrorMessage(str_replace("<", "&lt;", $response));
    		    $split_queue_attempt->setIsError(true);
    		}
    		$ret_val[] = $split_queue_attempt;
		}
		return $ret_val;
	}
}