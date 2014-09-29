<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;

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
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_POST);
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
	 * @var string
	 */
	function addRequest($resource, $lead) {
		$tmp_array = $this->getRequests();
		$tmp_array[$resource] = $lead;
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
	function prepareCurlRequest($export_queue_item) {
		$start_time = microtime(true);
		$params = $export_queue_item->getQs();
		$url = $export_queue_item->getUrl();

		// Setup Curl for this request
		$ch = curl_init();
		
		$headers = array();
		$headers[] = 'Accept-Encoding: gzip,deflate';
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
				
		return $ch;
	}
	
	/**
	 * Merges the response with the lead
	 * @param $export_queue_item Flux\ExportQueue
	 * @param $response string
	 * @return boolean
	 */
	function mergeResponse($export_queue_item, $response) {
		/* @var $export_queue_item \Flux\ExportQueue */
		$export_queue_item->setResponse($response);
		$export_queue_item->setLastSentTime(new \MongoDate());
		$export_queue_item->setResponseTime(microtime(true) - $export_queue_item->getStartTime());
		if (strpos($response, $this->getClientExport()->getSuccessMsg()) !== false) {
			\Mojavi\Util\StringTools::consoleWrite('  Queue lead [ ' . $export_queue_item->getLeadId() . ' ]', 'Sent', \Mojavi\Util\StringTools::CONSOLE_COLOR_GREEN, true);
			$export_queue_item->setIsError(false);
		} else {
			\Mojavi\Util\StringTools::consoleWrite('  Queue lead [ ' . $export_queue_item->getLeadId() . ' ]', 'Error', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
			$export_queue_item->setIsError(true);
		}
		return $export_queue_item;
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $export_queue_items
	 * @return boolean
	 */
	function send($export_queue_items) {
		$ret_val = array();
		
		// Now setup multi curl
		$mh = curl_multi_init();
		
		while ($export_queue_items->hasNext()) {
			$cursor_item = $export_queue_items->getNext();
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Iterating cursor");
			$export_queue_item = new \Flux\ExportQueue();
			$export_queue_item->populate($cursor_item);
			$export_queue_item->setStartTime(microtime(true));
			// Prepare the cURL request
			$ch = $this->prepareCurlRequest($export_queue_item);
			$key = (string)$ch;
			$this->addRequest($key, $export_queue_item);
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
				
				while (($info = curl_multi_info_read($mh))) {
					$resource = (string)$info['handle'];
					$response = curl_multi_getcontent($info['handle']);
					if (($export_queue_item = $this->getRequest($resource)) !== false) {
						if ($this->mergeResponse($export_queue_item, $response)) {
							$export_queue_item->update();
							$ret_val[] = $export_queue_item;
						}
						$this->removeRequest($resource);
					}
					
					while ($export_queue_items->hasNext()) {
						$cursor_item = $export_queue_items->getNext();
						\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Iterating cursor");
						$export_queue_item = new \Flux\ExportQueue();
						$export_queue_item->populate($cursor_item);
						// Prepare the cURL request
						$ch = $this->prepareCurlRequest($export_queue_item);
						$key = (string)$ch;
						$this->addRequest($key, $export_queue_item);
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