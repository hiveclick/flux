<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillmentTestAction extends BasicRestAction
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
		return new \Flux\SplitQueue();
	}
	
	/**
	 * Executes a POST request
	 */
	function executePost($input_form) {
		/* @var $ajax_form \Mojavi\Form\BasicAjaxForm */
		$ajax_form = new \Mojavi\Form\BasicAjaxForm();
		
		/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
		$split_queue_attempt = new \Flux\SplitQueueAttempt();
		$split_queue_attempt->setLead($input_form->getLead()->getLeadId());
		$split_queue_attempt->setFulfillment($input_form->getFulfillment()->getFulfillmentId());
		$split_queue_attempt->setAttemptTime(new \MongoDate());
		
		$results = $split_queue_attempt->getFulfillment()->getFulfillment()->queueLead($split_queue_attempt, true);
		/* @var $result \Flux\SplitQueueAttempt */
		foreach ($results as $key => $result) {
			// Save the split queue attempts back to the split queue item
			$input_form->setDebug($result->getRequest());
			$input_form->setLastAttemptTime(new \MongoDate());
			$input_form->setAttemptCount(1);
			$input_form->setIsProcessing(false);
			 
			if ($result->getIsError()) {
				$input_form->setIsError(true);
				$input_form->setErrorMessage($result->getResponse());
			} else {
				$input_form->setIsError(false);
				$input_form->setErrorMessage('');
			}
		}
		
		$input_form->setId(new \MongoId());
		$ajax_form->setInsertId(1);
		$ajax_form->setRowsAffected(1);
		$ajax_form->setRecord($input_form);
		
		return $ajax_form;
	}
}

?>