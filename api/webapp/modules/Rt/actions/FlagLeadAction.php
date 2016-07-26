<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FlagLeadAction extends BasicRestAction
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
	 * @return \Flux\Lead
	 */
	function getInputForm() {
		return new \Flux\ReportLead();
	}
	
	/**
	 * Executes a GET request
	 * @var $input_form \Flux\Lead
	 */
	function executeGet($input_form) {
		return $this->executePost($input_form);
	}
	
	/**
	 * Executes a GET request
	 * @var $input_form \Flux\Lead
	 */
	function executePost($input_form) {
		/* @var $ajax_form \Mojavi\Form\AjaxForm */
		$ajax_form = new \Mojavi\Form\AjaxForm();
		$post_response = new \Flux\PostResponse();
		
		try {
			/*
			 * First we need to populate the lead and figure out if the campaign, offer, and posting IP are 
			 * valid and support posting
			 */
			/* @var $lead \Flux\ReportLead */
			$report_lead = new \Flux\ReportLead();
			$report_lead->populate($_REQUEST);
			$report_lead->queryByLead();
			
			if (\MongoId::isValid($report_lead->getId())) {
				// Set the new disposition and notes
				$report_lead->setDisposition($input_form->getDisposition());
				$report_lead->setDispositionMessage($input_form->getDispositionMessage());
				
				if ($report_lead->getDisposition() != \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED) {
					$report_lead->setAccepted(false);
				} else {
					$report_lead->setAccepted(true);
				}
				
				// Update the disposition on the lead
				$report_lead->update();
				
				$post_response->setLead($report_lead->getLead()->getLeadId());
				$post_response->setResponse('Lead disposition updated');
				
				$ajax_form->setInsertId((string)$report_lead->getLead()->getLeadId());
				$ajax_form->setRowsAffected(1);
			} else {
				throw new \Exception('Invalid lead id');
			}
		} catch (\Exception $e) {
			// Add the exception as a note to the lead so we can track it
			$lead->addNote($e->getMessage());
			$lead->updateNotes();
			// Save the exception as an error so the ajax picks it up
			$this->getErrors()->addError('error', $e->getMessage());
			$post_response->setResponse($e->getMessage());
		}
		$ajax_form->setRecord($post_response);
		
		return $ajax_form;
	}
}

?>