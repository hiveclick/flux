<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericPost extends Generic {
	
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
	 * @var $lead_split_attempt \Flux\SplitQueueAttempt
	 */
	function addRequest($resource, $lead_split_attempt) {
		$tmp_array = $this->getRequests();
		$tmp_array[$resource] = $lead_split_attempt;
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
	function prepareCurlRequest($lead_split_attempt) {
		$start_time = microtime(true);
		$params = $lead_split_attempt->mergeLead();
		$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();

		$curl_request = $url . '?' . http_build_query($params, null, '&');
		$lead_split_attempt->setRequest($curl_request);
		
		// Setup Curl for this request
		$ch = curl_init();
		
		$headers = array();
		$headers[] = 'Accept-Encoding: gzip,deflate';
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		// Add POST data to curl
		// Passing encoded parameters to this function causes the data
		// to be sent with application/x-www-form-urlencode mime-type.
		// Using PHP associative array results in multipart/form-data.
		// Salesforce.com will not work with multipart/form-data.
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, null, '&'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSLVERSION, 0);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
				
		return $ch;
	}
	
	/**
	 * Merges the response with the lead
	 * @param $lead_split_attempt \Flux\SplitQueueAttempt
	 * @param $response string
	 * @return boolean
	 */
	function mergeResponse($lead_split_attempt, $response) {
		/* @var $lead_split_attempt \Flux\SplitQueueAttempt */
		$lead_split_attempt->setResponse($response);
		$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
		if ($this->getFulfillment()->getFulfillment()->getSuccessMsg() != '') {
			if (strpos($response, $this->getFulfillment()->getFulfillment()->getSuccessMsg()) !== false) {
				$lead_split_attempt->setIsError(false);
				$this->recordLeadPayout($lead_split_attempt, $response);
			} else {
				$lead_split_attempt->setErrorMessage(str_replace("<", "&lt;", $response));
				$lead_split_attempt->setIsError(true);
			}
		} else {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Missing SuccessMsg on " . $this->getFulfillment()->getName() . " for " . $lead_split_attempt->getSplitQueue()->getSplitQueue()->getSplit()->getName());
			$lead_split_attempt->setIsError(false);
		}
		return $lead_split_attempt;
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $split_queue_items
	 * @return boolean
	 */
	function send($lead_split_attempts, $is_test = false) {
		if ($is_test) {
			// If this is just a test, then do basic formatting, then exit
			/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
			foreach ($lead_split_attempts as $lead_split_attempt) {
				$params = $lead_split_attempt->mergeLead();
				$url = $lead_split_attempt->getFulfillment()->getFulfillment()->getPostUrl();
				 
				$url = $url . '?' . http_build_query($params, null, '&');
				$lead_split_attempt->setRequest($url);
				$lead_split_attempt->setResponse('SUCCESSFUL TEST');
				$lead_split_attempt->setIsError(false);
				$lead_split_attempt->setAttemptTime(new \MongoDate());
				$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
				$lead_split_attempt->setIsError(false);
			}
			
			return $lead_split_attempts;
		}
		
		// If this is not a test, then do the real thing
		$ret_val = array();
		
		// Now setup multi curl
		$mh = curl_multi_init();

		/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
		foreach ($lead_split_attempts as $lead_split_attempt) {
			$lead_split_attempt->setAttemptTime(microtime(true));
			// Prepare the cURL request
			$ch = $this->prepareCurlRequest($lead_split_attempt);
			$key = (string)$ch;
			$this->addRequest($key, $lead_split_attempt);
			curl_multi_add_handle($mh, $ch);
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
					if (($lead_split_attempt = $this->getRequest($resource)) !== false) {
						if ($this->mergeResponse($lead_split_attempt, $response)) {
							$ret_val[] = $lead_split_attempt;
						}
						$this->removeRequest($resource);
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