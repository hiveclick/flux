<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ManualFulfillCustomAction extends BasicRestAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
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
     * Returns the input form to use for this rest action
     * @return \Flux\Offer
     */
    function executePost($input_form) {
        $ajax_form = new BasicAjaxForm();
        
        /* @var $split_queue \Flux\SplitQueue */
        $split_queue = new \Flux\SplitQueue();
        $split_queue->setId($input_form->getId());
        $split_queue->query();
        if (\MongoId::isValid($split_queue->getId())) {
            
        	/* @var $fulfillment \Flux\Fulfillment */
        	$fulfillment = $input_form->getFulfillment()->getFulfillment();
        	
        	/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
        	$split_queue_attempt = new \Flux\SplitQueueAttempt();
        	$split_queue_attempt->setSplitQueue($split_queue->getId());
        	$split_queue_attempt->setFulfillment($fulfillment->getId());
        	$split_queue_attempt->setAttemptTime(new \MongoDate());
        	
        	if ($input_form->getTest()) {
        	    $split_queue->setTest(true);
        	    $results = $fulfillment->queueLead($split_queue_attempt, true);
        	    /* @var $result \Flux\SplitQueueAttempt */
        	    foreach ($results as $key => $result) {
        	        // Save the split queue attempts back to the split queue item
        	        $split_queue->setDebug($result->getRequest());
            	    $split_queue->setLastAttemptTime(new \MongoDate());
            	    $split_queue->setAttemptCount($split_queue->getAttemptCount() + 1);
            	    $split_queue->setIsProcessing(false);
            	    
        	        if ($result->getIsError()) {
        	            $split_queue->setIsError(true);
        	            $split_queue->setErrorMessage($result->getResponse());
        	        } else {
        	            $split_queue->setIsError(false);
        	            $split_queue->setErrorMessage('');
        	        }
        	    }
        	} else {
        	    if ($split_queue->getIsFulfilled()) {
        	        // The lead has already been fulfilled, so don't allow it to be fulfilled again
        	        /* @var $split_queue_attempt \Flux\SplitQueueAttempt */
        	        $split_queue_attempt = new \Flux\SplitQueueAttempt();
        	        $split_queue_attempt->setSplitQueue($split_queue->getId());
        	        $split_queue_attempt->setFulfillment($fulfillment->getId());
        	        $split_queue_attempt->setAttemptTime(new \MongoDate());
        	        $split_queue_attempt->setIsError(false);
        	        $split_queue_attempt->setResponse('Already Fulfilled');
        	        $split_queue->addAttempt($split_queue_attempt);
        	        
        	        $split_queue->setErrorMessage('Already Fulfilled');
        	    } else {
                    $results = $fulfillment->queueLead($split_queue_attempt);
            	
            	
                	/* @var $result \Flux\SplitQueueAttempt */
                	foreach ($results as $key => $result) {
                	    // Save the split queue attempts back to the split queue item
                	    $split_queue->addAttempt($result);
                	    
                	    $split_queue->setDebug($result->getRequest());
                	    $split_queue->setLastAttemptTime(new \MongoDate());
                	    $split_queue->setIsProcessing(false);
                	    
                	    if ($result->getIsError()) {
                	        $split_queue->setIsError(true);
                	        $split_queue->setErrorMessage($result->getResponse());
                	        $split_queue->setIsFulfilled(false);
                	        $split_queue->setAttemptCount($split_queue->getAttemptCount() + 1);
                	        $split_queue->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
                	    } else {
                	        $split_queue->setIsFulfilled(true);            	        
                	        $split_queue->setIsError(false);
                	        $split_queue->setErrorMessage('');
                	        
                	        // Add a fulfilled event to the lead
                	        $lead = $split_queue->getLead()->getLead();
                	        $lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
                	        $lead->update();
                	    }
                	}
        	    }
        	
                $split_queue->update();
        	}
            	
            $ajax_form->setRecord($split_queue);
        } else {
        	throw new Exception('We cannot find a lead given the id (' . $input_form->getId() . ')');
        }
        return $ajax_form;
    }
}

?>