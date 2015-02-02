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
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_INFUSIONSOFT);
		$this->setName('InfusionSoft Post Export');
		$this->setDescription('Send leads to an InfusionSoft API server');
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
		
		// Include the Novak Solutions infusionsoft library
		require_once(MO_WEBAPP_DIR . '/vendor/novaksolutions/infusionsoft-php-sdk/Infusionsoft/infusionsoft.php');
		
		while ($export_queue_items->hasNext()) {
			$cursor_item = $export_queue_items->getNext();
			/* @var $export_queue_item \Flux\ExportQueue */
			$export_queue_item = new \Flux\ExportQueue();
			$export_queue_item->populate($cursor_item);
			$export_queue_item->setStartTime(microtime(true));
			
			$params = $export_queue_item->getQs();
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
			\Infusionsoft_AppPool::setDefaultApp(new \Infusionsoft_App($this->getExport()->getClientExport()->getInfusionsoftHost(), $this->getExport()->getClientExport()->getInfusionsoftApiKey(), 443));			
			$contact_id = \Infusionsoft_ContactService::addWithDupCheck($contact->toArray(), 'Email');
			
			// Now add tags to the contact
			if (count($tags) > 0) {
				foreach ($tags as $tag_id) {
					\Infusionsoft_ContactService::addToGroup($contact_id, $tag_id);
				}
			}
			
			\Infusionsoft_AppPool::clearApps();
		}

		return $ret_val;
	}
}