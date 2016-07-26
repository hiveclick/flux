<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ManualFulfillCustomAction extends BasicRestAction
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
	 * @return \Flux\Offer
	 */
	function getInputForm() {
		return new \Flux\Lead();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		
		/* @var $lead \Flux\Lead */
		$lead = new \Flux\Lead();
		$lead->setId($input_form->getId());
		$lead->query();
		if (!is_null($lead->getId())) {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Fulfilling lead " . $lead->getId());
			// Find the client export id
			if ($this->getContext()->getRequest()->hasParameter('fulfillment_id')) {
				/* @var $fulfillment \Flux\Fulfillment */
				$fulfillment = new \Flux\Fulfillment();
				$fulfillment->setId($this->getContext()->getRequest()->getParameter('fulfillment_id'));
				$fulfillment->query();
				
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Creating export...");
				// Create a new export to use for this test
				/* @var $export \Flux\Export */
				$export = new \Flux\Export();
				$export->setName($fulfillment->getName() . ' for Lead ' . $lead->getId());
				$export->setFulfillment($fulfillment->getId());
				$export->setSplit(0);
				$export->setExportType(\Flux\Export::EXPORT_TYPE_TEST);
				$export->setIsRunning(false);
				$export_id = $export->insert();
				$export->setId($export_id);
				// Queue the lead to the export
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Queueing lead...");
				$export->queueLead($lead);
				
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Running ExportQueueExporter...");
				
				/* @var $export_queue_exporter \Flux\ExportQueueExporter */
				$export_queue_exporter = new \Flux\ExportQueueExporter();
				$export_queue_exporter->setExportId($export->getId());
				ob_start();
				try {
					\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Running ExportQueueExporter::processExport...");
					$export_queue_exporter->processExport();
					$export_queue_log = ob_get_clean();
				} catch (\Exception $e) {
					\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
					$export_queue_log = $e->getMessage();
					ob_end_clean();
					throw $e;
				}
				@file_put_contents(MO_LOG_FOLDER . '/export_queue.sh_' . $export->getId() . '.log', $export_queue_log);
				
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Fulfilling lead " . $lead->getId() . " to export " . $fulfillment->getName());
				$log_contents = \Mojavi\Util\StringTools::consoleToHtmlColor($export_queue_log);
				$lead->setFulfillLogContents($log_contents);
				$lead->setFulfillExportId($export_id);
				$ajax_form->setRecord($lead);
			} else {
				throw new Exception('We cannot find an export given the id (' . $this->getContext()->getRequest()->getParameter('fulfillment_id') . ')');
			}
		} else {
			throw new Exception('We cannot find a lead given the id (' . $input_form->getId() . ')');
		}
		return $ajax_form;
	}
}

?>