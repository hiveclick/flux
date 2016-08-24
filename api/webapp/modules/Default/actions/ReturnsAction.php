<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\CommonForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ReturnsAction extends BasicRestAction
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
		return new \Flux\LeadSplit();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executeGet($input_form) {
		return $this->executePost($input_form);
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();	
		$post_response = new \Flux\PostResponse();
		try {
			$fulfillment_id = $this->getContext()->getRequest()->getParameter('token', null);
			
			if (is_null($fulfillment_id)) {
				throw new \Exception('You must pass in a valid token');
			}
			if (!\MongoId::isValid($fulfillment_id)) {
				throw new \Exception('You must pass in a valid token');
			}
			
			// Find the lead from the input_form
			/* @var $lead \Flux\Lead */
			$lead = $input_form->getLead();
			if (\MongoId::isValid($lead->getId())) {
				$lead_split_found = false;
				/* @var $lead_split \Flux\LeadSplit */
				foreach ($lead->getLead()->getLeadSplits() as $lead_split) {
					/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
					foreach ($lead_split->getAttempts() as $lead_split_attempt) {
						if ($lead_split_attempt->getFulfillment()->getId() == $fulfillment_id) {
							// This is the lead split we want to flag
							$lead_split_found = true;
							$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_RETURNED);
							$returned_note = 'This lead was returned by the buyer on ' . date('m/d/Y g:i a') . '<br />';
							if ($input_form->getReturnedNote() != '') {
								$returned_note .= 'REASON: ' . $input_form->getReturnedNote();
							}
							$lead_split->setErrorMessage($input_form->getReturnedNote());
							$lead_split->setReturnedNote($returned_note);
							$lead_split->setBounty(0.00);
							$lead_split->setIsReturned(true);
							$lead_split->setIsConfirmed(false);
							$lead_split->update();

							// Remove the reporting data
							$report_lead = new \Flux\ReportLead();
							$report_lead = $report_lead->query(array('lead_split._id' => $lead_split->getId()), false);
							if (\MongoId::isValid($report_lead->setId())) {
								$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
								$report_lead->setBounty(0);
								$report_lead->update();
							}

							$post_response->setResponse('Lead updated successfully');
							$post_response->setId($lead->getId());
							break;
						}
					}
				}
				if (!$lead_split_found) {
					throw new \Exception('No lead was updated because a valid fulfillment was not matched');
				}
			} else {
				throw new \Exception('An invalid lead was passed in');
			}
		} catch (\Exception $e) {
			$post_response->setResponse('Lead update failed');
			$this->getErrors()->addError('error', $e->getMessage());
		}
		$ajax_form->setHidePagination(true);
		$ajax_form->setHideMeta(true);
		$ajax_form->setRecord($post_response);
		return $ajax_form;
	}
}

?>