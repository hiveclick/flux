<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FlagNextLeadAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::GET) {
			/* @var $lead_split Flux\LeadSplit */
			$lead_split = new \Flux\LeadSplit();
			$lead_split->populate($_GET);
			$lead_split->query();
			
			$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
			return View::INPUT;
		} else if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::POST) {
			/* @var $lead_split Flux\LeadSplit */
			$lead_split = new \Flux\LeadSplit();
			$lead_split->populate($_POST);
			$lead_split->query();
			
			/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
			$lead_split_attempt = new \Flux\LeadSplitAttempt();
			$lead_split_attempt->setAttemptTime(new \MongoDate());
			$lead_split_attempt->setFulfillment(array('fulfillment_id' => 0, 'fulfillment_name' => 'uBot Script'));
			$lead_split_attempt->setResponse($_POST['response']);
			if (isset($_POST['error_message']) && trim($_POST['error_message']) != '') {
				$lead_split_attempt->setErrorMessage($_POST['error_message']);
				$lead_split_attempt->setIsError(true);
				$lead_split->setErrorMessage($_POST['error_message']);
			}
			if (isset($_POST['disposition']) && trim($_POST['disposition']) != '') {
				$lead_split->setDisposition($_POST['disposition']);
			}
			if (isset($_FILES['screenshot'])) {
				if (file_exists($_FILES['screenshot']['tmp_name'])) {
					$lead_split_attempt->setScreenshot(base64_encode(file_get_contents($_FILES['screenshot']['tmp_name'])));
				}
			}
			if (isset($_FILES['debug_screenshots']['tmp_name'])) {
				foreach ($_FILES['debug_screenshots']['tmp_name'] as $debug_screenshot) {
					if (trim($debug_screenshot) != '') {
						if (file_exists($debug_screenshot)) {
							$lead_split_attempt->addDebugScreenshot(base64_encode(file_get_contents($debug_screenshot)));
						}
					}
				}
			}
			$lead_split->addAttempt($lead_split_attempt);
			$lead_split->setLastAttemptTime(new \MongoDate());
			$lead_split->update();	   

			if ($lead_split->getDisposition() == \Flux\LeadSplit::DISPOSITION_FULFILLED) {
				// Add a fulfilled event to the lead
				/* @var $lead \Flux\Lead */
				$lead = $lead_split->getLead()->getLead();
				if ($lead_split->getSplit()->getSplit()->getFulfillment()->getFulfillment()->getTriggerFulfillmentFlag()) {
					$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
					$lead->update();
				}
				
				// Add/Update the lead reporting
				/* @var $report_lead \Flux\ReportLead */
				$report_lead = new \Flux\ReportLead();
				$report_lead->setLead($lead->getId());
				$report_lead->setClient($lead->getTracking()->getClient()->getClientId());
				$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
				$report_lead->setRevenue($lead_split->getSplit()->getSplit()->getFulfillment()->getFulfillment()->getBounty());
				if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
					$report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
				} else {
					$report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
				}
				$report_lead->setReportDate(new \MongoDate());
				$report_lead->setAccepted(true);
				$report_lead->insert();
			} else {
				/* @var $report_lead \Flux\ReportLead */
				$report_lead = new \Flux\ReportLead();
				$lead = $lead_split->getLead()->getLead();
				$report_lead->setLead($lead->getId());
				$report_lead->setClient($lead->getTracking()->getClient()->getClientId());
				$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
				$report_lead->setRevenue(0.00);
				$report_lead->setPayout(0.00);
				$report_lead->setReportDate(new \MongoDate());
				$report_lead->insert();
			}
			
			$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
			return View::SUCCESS;
		}
	}
}

?>