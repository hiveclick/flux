<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class InfusionSoft extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_INFUSIONSOFT);
		$this->setName('InfusionSoft Post Export');
		$this->setDescription('Send leads to an InfusionSoft API server');
	}
		
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $split_queue_attempts
	 * @return boolean
	 */
	function send($split_queue_attempts, $is_test = false) {
		$ret_val = array();
		
		// Now setup multi curl
		$mh = curl_multi_init();
		
		// Include the Novak Solutions infusionsoft library
		require_once(MO_WEBAPP_DIR . '/vendor/novaksolutions/infusionsoft-php-sdk/Infusionsoft/infusionsoft.php');
		
		while ($split_queue_attempts->hasNext()) {
			$cursor_item = $split_queue_attempts->getNext();
			/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
			$split_queue_attempt = new \Flux\SplitQueueAttempt();
			$split_queue_attempt->populate($cursor_item);
			$split_queue_attempt->setStartTime(microtime(true));

			$params = $split_queue_attempt->mergeLead();
			$api_key = '';
			$infusion_host = '';
			$tags = array();
			/* @var $contact Infusionsoft_Contact */
			$contact = new \Infusionsoft_Contact();
			foreach ($params as $key => $value) {
				if ($key == 'apiKey') { continue; }
				if ($key == 'infusionsoftHost') { continue; }
				if (strtolower($key) == 'tag') { 
					$tags[] = $value;
				} else {
					try {
						$contact->{$key} = $value;
					} catch (\Exception $e) {
						try {
							$contact->addCustomField($key);
							$contact->{$key} = $value;
						} catch (\Exception $e) {
							echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
						}
					}
				}
			}
			
			if (!$is_test) {
    			\Infusionsoft_AppPool::setDefaultApp(new \Infusionsoft_App($this->getFulfillment()->getFulfillment()->getInfusionsoftHost(), $this->getFulfillment()->getFulfillment()->getInfusionsoftApiKey(), 443));			
    			$contact_id = \Infusionsoft_ContactService::addWithDupCheck($contact->toArray(), 'Email');
    			
    			$split_queue_attempt->setRequest(http_build_query($contact->toArray()));
    			$split_queue_attempt->setResponse('SUCCESS: ' . $contact_id);
        		$split_queue_attempt->setLastSentTime(new \MongoDate());
        		$split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
        		$split_queue_attempt->setIsError(false);
			
    			// Now add tags to the contact
    			if (count($tags) > 0) {
    				foreach ($tags as $tag_id) {
    					\Infusionsoft_ContactService::addToGroup($contact_id, $tag_id);
    				}
    			}
    			
    			\Infusionsoft_AppPool::clearApps();
			} else {
			    $split_queue_attempt->setRequest(http_build_query($contact->toArray(), null, '&'));
			    $split_queue_attempt->setResponse('SUCCESSFUL TEST');
			    $split_queue_attempt->setLastSentTime(new \MongoDate());
			    $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
			    $split_queue_attempt->setIsError(false);
			}
			
			$ret_val[] = $split_queue_attempt;
		}

		return $ret_val;
	}
}