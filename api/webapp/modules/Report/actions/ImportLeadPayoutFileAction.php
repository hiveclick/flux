<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ImportLeadPayoutFileAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\SpyReport
	 */
	function getInputForm() {
		return new \Flux\Report\LeadPayout();
	}
	
	/**
	 * Executes a GET request
	 */
	function executePost($input_form) {
		// Handle GET Requests
		/* @var $ajax_form \Mojavi\Form\AjaxForm */
		$ajax_form = new \Mojavi\Form\AjaxForm();
		// Open the file and parse it
		$entries = array();
		if (isset($_FILES['filename']['tmp_name']) && file_exists($_FILES['filename']['tmp_name'])) {
			if (($fh = fopen($_FILES['filename']['tmp_name'], 'r')) !== false) {
				while (($buffer = fgets($fh, 4096)) !== false) {
					if (strpos($buffer, "\t")) {
						$line_parts = explode("\t", $buffer);
					} else if (strpos($buffer, ',')) {
						$line_parts = explode(",", $buffer);
					} else {
						$line_parts = array($buffer);
					}
					$lead_id = $line_parts[0];
					if (\MongoId::isValid($lead_id)) {
						$payout = 0;
						if (isset($line_parts[1])) {
							$payout = floatval($line_parts[1]);
						}
						$entries[] = array('lead_id' => $lead_id, 'payout' => $payout);
					}
				}
			}
		}
		
		$ajax_form->setEntries($entries);
		$ajax_form->setTotal(count($entries));
		$ajax_form->setPage(1);
		$ajax_form->setItemsPerPage(count($entries));
		return $ajax_form;
	}
}

?>