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
			/* @var $contact Infusionsoft_Contact */
			$contact = new \Infusionsoft_Contact();
			foreach ($params as $key => $value) {
				if ($key == 'apiKey') { continue; }
				if ($key == 'infusionsoftHost') { continue; }
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
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending infusionsoft request using host: " . $this->getExport()->getClientExport()->getInfusionsoftHost());
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending infusionsoft request using key: " . $this->getExport()->getClientExport()->getInfusionsoftApiKey());
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending infusionsoft request using contact: " . var_export($contact->toArray(), true));
			\Infusionsoft_AppPool::setDefaultApp(new \Infusionsoft_App($this->getExport()->getClientExport()->getInfusionsoftHost(), $this->getExport()->getClientExport()->getInfusionsoftApiKey(), 443));			
			\Infusionsoft_ContactService::addWithDupCheck($contact->toArray(), 'Email');
			\Infusionsoft_AppPool::clearApps();
		}

		return $ret_val;
	}
}