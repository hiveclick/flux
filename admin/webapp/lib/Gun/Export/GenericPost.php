<?php
namespace Gun\Export;

use \Gun\Export\ExportAbstract;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericPost extends ExportAbstract {
	
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
	 * Sends the leads and returns the results
	 * @param array $leads
	 * @return boolean
	 */
	function send(array $leads) {
		return false;
	}
}