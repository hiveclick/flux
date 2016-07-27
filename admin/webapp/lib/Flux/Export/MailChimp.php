<?php
namespace Flux\Export;

use \Flux\Export\GenericPost;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class MailChimp extends GenericPost {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_MAILCHIMP);
		$this->setName('MailChimp Export');
		$this->setDescription('Send leads to MailChimp');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $lead_split_attempts
	 * @return boolean
	 */
	function send($lead_split_attempts, $is_test = false) {
		$api_key = $this->getFulfillment()->getFulfillment()->getMailchimpApiKey();
		$url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/';
		$url .= '/lists/' . $this->getFulfillment()->getFulfillment()->getMailchimpList() . '/members/';
		
		if ($is_test) {
			// If this is just a test, then do basic formatting, then exit
			foreach ($lead_split_attempts as $lead_split_attempt) {
				$params = $lead_split_attempt->mergeLead();
				
				$post_params = array(
					'email_address' => $params['email'],
					'status' => 'subscribed'
				);
				if (isset($params['email'])) { unset($params['email']); }
				$post_params['merge_fields'] = $params;
				
				$url = $url . '?' . http_build_query($post_params, null, '&');
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
	
	/**
	 * Prepares the curl request
	 * @param $export_queue_item \Flux\ExportQueue
	 * @return resource
	 */
	function prepareCurlRequest($lead_split_attempt) {
		$start_time = microtime(true);
		$params = $lead_split_attempt->mergeLead();
		
		$api_key = $this->getFulfillment()->getFulfillment()->getMailchimpApiKey();
		$url = 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/';
		$url .= '/lists/' . $this->getFulfillment()->getFulfillment()->getMailchimpList() . '/members/';
		
		
		$post_params = array(
			'email_address' => $params['email'],
			'status' => 'subscribed'
		);
		
		if (isset($params['email'])) { unset($params['email']); }
		$post_params['merge_fields'] = $params;
		
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSLVERSION, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "mc:" . $api_key);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	
		return $ch;
	}
	
	/**
	 * Merges the response with the lead
	 * @param $lead_split_attempt \Flux\LeadSplitAttempt
	 * @param $response string
	 * @return boolean
	 */
	function mergeResponse($lead_split_attempt, $response) {
		/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
		$lead_split_attempt->setResponse($response);
		$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
		// The response is in json format
		$response_obj = json_decode($response, true);
		if (isset($response_obj['id'])) {
			$lead_split_attempt->setIsError(false);
			$this->recordLeadPayout($lead_split_attempt, $response);
		} else if (isset($response_obj['detail'])) {
			$lead_split_attempt->setIsError(true);
			$lead_split_attempt->setErrorMessage($response_obj['detail']);
		} else {
			$lead_split_attempt->setIsError(true);
		}
		return $lead_split_attempt;
	}
	
}