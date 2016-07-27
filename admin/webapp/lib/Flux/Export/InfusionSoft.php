<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class InfusionSoft extends Generic {
	
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
	 * @param array|MongoCursor $lead_split_attempts
	 * @return boolean
	 */
	function send($lead_split_attempts, $is_test = false) {
		$ret_val = array();
		
		// Now setup multi curl
		$mh = curl_multi_init();
		
		// Include the Novak Solutions infusionsoft library
		require_once(MO_WEBAPP_DIR . '/vendor/novaksolutions/infusionsoft-php-sdk/Infusionsoft/infusionsoft.php');
		
		foreach ($lead_split_attempts as $cursor_item) {
			/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
			$lead_split_attempt = new \Flux\LeadSplitAttempt();
			$lead_split_attempt->populate($cursor_item);
			$lead_split_attempt->setAttemptTime(microtime(true));

			$params = $lead_split_attempt->mergeLead();
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
				
				$lead_split_attempt->setRequest('http://' . $this->getFulfillment()->getFulfillment()->getInfusionsoftHost() . '?' . http_build_query($contact->toArray(), null, '&'));
				$lead_split_attempt->setResponse('SUCCESS: ' . $contact_id);
				$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
				$lead_split_attempt->setIsError(false);
				$this->recordLeadPayout($lead_split_attempt, ('SUCCESS: ' . $contact_id));
			
				// Now add tags to the contact
				if (count($tags) > 0) {
					foreach ($tags as $tag_id) {
						\Infusionsoft_ContactService::addToGroup($contact_id, $tag_id);
					}
				}
				
				\Infusionsoft_AppPool::clearApps();
			} else {
				$lead_split_attempt->setRequest('http://' . $this->getFulfillment()->getFulfillment()->getInfusionsoftHost() . '?' . http_build_query($contact->toArray(), null, '&'));
				$lead_split_attempt->setResponse('SUCCESSFUL TEST');
				$lead_split_attempt->setResponseTime(microtime(true) - $lead_split_attempt->getAttemptTime());
				$lead_split_attempt->setIsError(false);
			}
			
			$ret_val[] = $lead_split_attempt;
		}

		return $ret_val;
	}
}