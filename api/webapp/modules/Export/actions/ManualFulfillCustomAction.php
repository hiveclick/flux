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
		return new \Flux\LeadSplitAttempt();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
				
		/* @var $lead_split \Flux\LeadSplit */
		$lead_split = new \Flux\LeadSplit();
		$lead_split->setId($input_form->getLeadSplit()->getId());
		$lead_split->query();
		if (\MongoId::isValid($lead_split->getId())) {
			
			/* @var $fulfillment \Flux\Fulfillment */
			$fulfillment = $input_form->getFulfillment()->getFulfillment();
			
			/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
			$lead_split_attempt = new \Flux\LeadSplitAttempt();
			$lead_split_attempt->setLeadSplit($lead_split->getId());
			$lead_split_attempt->setFulfillment($fulfillment->getId());
			$lead_split_attempt->setAttemptTime(new \MongoDate());
			
			if ($input_form->getTest()) {
				$lead_split_attempt->setTest(true);
				$results = $fulfillment->queueLead($lead_split_attempt, true);
				/* @var $result \Flux\LeadSplitAttempt */
				foreach ($results as $key => $result) {
					// Save the split queue attempts back to the split queue item
					$lead_split->setDebug($result->getRequest());
					$lead_split->setLastAttemptTime(new \MongoDate());
					$lead_split->setAttemptCount($lead_split->getAttemptCount() + 1);
					$lead_split->setIsProcessing(false);
					
					if ($result->getIsError()) {
						$lead_split->setIsError(true);
						$lead_split->setErrorMessage($result->getResponse());
					} else {
						$lead_split->setIsError(false);
						$lead_split->setErrorMessage('');
					}
				}
			} else {
				if ($lead_split->getIsFulfilled() && !$input_form->getOverrideFulfillment()) {
					// The lead has already been fulfilled, so don't allow it to be fulfilled again
					$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED);
					$lead_split->setLastAttemptTime(new \MongoDate());
					$lead_split->setErrorMessage('Already Fulfilled');
					$lead_split->setIsError(true);
				} else {
					$results = $fulfillment->queueLead($lead_split_attempt);
				
					/* @var $result \Flux\LeadSplitAttempt */
					foreach ($results as $key => $result) {

						// Save the split queue attempts back to the split queue item
						$lead_split->addAttempt($result);
						
						$lead_split->setDebug($result->getRequest());
						$lead_split->setLastAttemptTime(new \MongoDate());
						$lead_split->setIsProcessing(false);

						if ($result->getIsDuplicate()) {
							$lead_split->setIsError(true);
							$lead_split->setErrorMessage($result->getResponse());
							$lead_split->setIsFulfilled(true);
							$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED);

							/* @var $report_lead \Flux\ReportLead */
							$report_lead = new \Flux\ReportLead();
							$report_lead->setLead($lead_split->getLead()->getLead()->getId());
							$report_lead->setClient($fulfillment->getClient()->getId());
							$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE);
							$report_lead->setRevenue(0.00);
							$report_lead->setPayout(0.00);
							$report_lead->setReportDate(new \MongoDate());
							$report_lead->insert();
						} else if ($result->getIsError()) {
							$lead_split->setIsError(true);
							$lead_split->setErrorMessage($result->getResponse());
							$lead_split->setIsFulfilled(false);
							$lead_split->setAttemptCount($lead_split->getAttemptCount() + 1);
							$lead_split->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
							$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_PENDING);
							
							/* @var $report_lead \Flux\ReportLead */
							$report_lead = new \Flux\ReportLead();
							$report_lead->setLead($lead_split->getLead()->getLead()->getId());
							$report_lead->setClient($fulfillment->getClient()->getId());
							$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
							$report_lead->setRevenue(0.00);
							$report_lead->setPayout(0.00);
							$report_lead->setReportDate(new \MongoDate());
							$report_lead->insert();
						} else {
							$lead_split->setIsFulfilled(true);						
							$lead_split->setIsError(false);
							$lead_split->setErrorMessage('');
							$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_FULFILLED);
							
							/* @var $lead \Flux\Lead */
							$lead = $lead_split->getLead()->getLead();
							if ($fulfillment->getTriggerFulfillmentFlag()) {
								// Add a fulfilled event to the lead
								$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
								$lead->update();
							}
							
							// Add/Update the lead reporting
							/* @var $report_lead \Flux\ReportLead */
							$report_lead = new \Flux\ReportLead();
							$report_lead->setLeadSplit($lead_split->getId());
							$report_lead->setLead($lead->getId());
							$report_lead->setClient($fulfillment->getClient()->getId());
							$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
							$report_lead->setRevenue($lead_split_attempt->getBounty());
							if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
								$report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
							} else {
								$report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
							}
							$report_lead->setReportDate(new \MongoDate());
							$report_lead->setAccepted(true);
							$report_lead->insert();
						}
					}
				}
			
				$lead_split->update();
			}
				
			$ajax_form->setRecord($lead_split);
		} else {
			throw new Exception('We cannot find a lead given the id (' . $input_form->getId() . ')');
		}
		return $ajax_form;
	}
}

?>