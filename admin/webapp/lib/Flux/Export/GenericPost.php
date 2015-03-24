<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericPost extends ExportAbstract {
	
	const WINDOW_SIZE = 10;
	
	private $requests;
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_POST);
		$this->setName('Generic POST Export');
		$this->setDescription('Send leads to a POST url');
	}
	
	/**
	 * Returns the requests
	 * @return array
	 */
	function getRequests() {
		if (is_null($this->requests)) {
			$this->requests = array();
		}
		return $this->requests;
	}
	
	/**
	 * Sets the requests
	 * @var array
	 */
	function setRequests($arg0) {
		$this->requests = $arg0;
		$this->addModifiedColumn("requests");
		return $this;
	}
	
	/**
	 * Sets the requests
	 * @var array
	 */
	function getRequest($resource) {
		$tmp_array = $this->getRequests();
		if (isset($tmp_array[$resource])) {
			return $tmp_array[$resource];
		}
		return false;
	}
	
	/**
	 * Sets the requests
	 * @var string
	 * @var $split_queue_attempt \Flux\SplitQueueAttempt
	 */
	function addRequest($resource, $split_queue_attempt) {
		$tmp_array = $this->getRequests();
		$tmp_array[$resource] = $split_queue_attempt;
		$this->setRequests($tmp_array);
	}
	
	/**
	 * Removes a request
	 * @var string
	 */
	function removeRequest($resource) {
		if (isset($this->requests[$resource])) {
			unset($this->requests[$resource]);
		}
	}
	
	/**
	 * Starts up multi curl
	 * @param resource $mh
	 * @param unknown_type $still_running
	 */
	function full_curl_multi_exec($mh, &$still_running) {
		do {
			$rv = curl_multi_exec($mh, $still_running);
		} while ($rv == CURLM_CALL_MULTI_PERFORM);
		return $rv;
	}
	
	/**
	 * Prepares the curl request
	 * @param $export_queue_item \Flux\ExportQueue
	 * @return resource
	 */
	function prepareCurlRequest($split_queue_attempt) {
		$start_time = microtime(true);
		$params = $split_queue_attempt->mergeLead();
		$url = $split_queue_attempt->getFulfillment()->getFulfillment()->getPostUrl();

		// Setup Curl for this request
		$ch = curl_init();
		
		$headers = array();
		$headers[] = 'Accept-Encoding: gzip,deflate';
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSLVERSION, 0);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
				
		return $ch;
	}
	
	/**
	 * Merges the response with the lead
	 * @param $split_queue_attempt Flux\SplitQueueAttempt
	 * @param $response string
	 * @return boolean
	 */
	function mergeResponse($split_queue_attempt, $response) {
		/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
		$split_queue_attempt->setResponse($response);
		$split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
		if (strpos($response, $this->getFulfillment()->getSuccessMsg()) !== false) {
			$split_queue_attempt->setIsError(false);
		} else {
			$split_queue_attempt->setIsError(true);
		}
		return $split_queue_attempt;
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
	            $split_queue_attempt->setIsError(false);
	        }
	        
	        return $split_queue_attempts;
	    }
	    
	    // If this is not a test, then do the real thing
		$ret_val = array();
		
		// Now setup multi curl
		$mh = curl_multi_init();
		
		while ($split_queue_attempts->hasNext()) {
			$cursor_item = $split_queue_attempts->getNext();
			$split_queue_attempt = new \Flux\SplitQueueAttempt();
			$split_queue_attempt->populate($cursor_item);
			$split_queue_attempt->setStartTime(microtime(true));
			// Prepare the cURL request
			$ch = $this->prepareCurlRequest($split_queue_attempt);
			$key = (string)$ch;
			$this->addRequest($key, $split_queue_attempt);
			curl_multi_add_handle($mh, $ch);
			if ($key >= self::WINDOW_SIZE) { break; }
		}

		if (count($this->getRequests()) > 0) {

			$active = null;
			//execute the handles
			$this->full_curl_multi_exec($mh, $active); // start requests
			
			do { // "wait for completion"-loop
				while (($execrun = curl_multi_exec($mh, $active)) == CURLM_CALL_MULTI_PERFORM);
				if ($execrun != CURLM_OK) {
					break;
				}
				
				while (($info = curl_multi_info_read($mh)) !== false) {
					$resource = (string)$info['handle'];
					$response = curl_multi_getcontent($info['handle']);
					if (($split_queue_attempt = $this->getRequest($resource)) !== false) {
						if ($this->mergeResponse($split_queue_attempt, $response)) {
							$ret_val[] = $split_queue_attempt;
						}
						$this->removeRequest($resource);
					}
					
					while ($split_queue_attempts->hasNext()) {
						$cursor_item = $split_queue_attempts->getNext();
						$split_queue_attempt = new \Flux\SplitQueueAttempt();
                        $split_queue_attempt->populate($cursor_item);
                        $split_queue_attempt->setStartTime(microtime(true));
						// Prepare the cURL request
						$ch = $this->prepareCurlRequest($split_queue_attempt);
						$key = (string)$ch;
						$this->addRequest($key, $split_queue_attempt);
						curl_multi_add_handle($mh, $ch);
					}
					
					// remove the curl handle that just completed
					curl_multi_remove_handle($mh, $info['handle']);
				}
				
				if ($active) {
					curl_multi_select($mh); // non-busy (!) wait for state change
				}
			} while ($active);
		
			curl_multi_close($mh);
		} else {
			throw new \Exception('No queue items to process');
		}
		return $ret_val;
	}
}