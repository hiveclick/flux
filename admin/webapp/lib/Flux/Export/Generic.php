<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;
use Flux\ExportQueue;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class Generic extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_NONE);
		$this->setName('Generic Export');
		$this->setDescription('An export that doesn\'t do anything at all');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $export_queue_items
	 * @return boolean
	 */
	function send($export_queue_items) {
		return false;
	}
}
?>