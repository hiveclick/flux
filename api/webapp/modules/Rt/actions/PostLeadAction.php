<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class PostLeadAction extends BasicRestAction
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
		return new \Flux\Lead();
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
		/* @var $ajax_form BasicAjaxForm */
		$ajax_form = new BasicAjaxForm();
		$post_response = new \Flux\PostResponse();
		
		try {
			/*
			 * First we need to populate the lead and figure out if the campaign, offer, and posting IP are 
			 * valid and support posting
			 */
			/* @var $lead \Flux\Lead */
			$lead = new \Flux\Lead();
			$lead->populate($_REQUEST);
			$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, '1');
			$lead->addNote(json_encode($_REQUEST));
			// Attempt to insert the lead
			$insert_id = $lead->insert();
			$lead->setId($insert_id);
			
			$post_response->setLead($insert_id);
			
			if (!\MongoId::isValid($lead->getTracking()->getCampaign()->getCampaignId())) {
				throw new \Exception('Invalid campaign key');
			}
			if (!($lead->getTracking()->getOffer()->getOffer()->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_POST)) {
				throw new \Exception('Campaign is not a valid post campaign');
			}
			if (!\MongoId::isValid($lead->getTracking()->getOffer()->getOffer()->getSplit()->getSplitId())) {
				throw new \Exception('Campaign has not been setup with a fulfillment yet');
			}
			
			/*
			 * Now get the split from the offer and check validation on the incoming fields.  Any exceptions will
			 * stop the process and throw an error
			 */
			/* @var $split \Flux\Split */
			$split = $lead->getTracking()->getCampaign()->getCampaign()->getOffer()->getOffer()->getSplit()->getSplit();

			if (count($split->getValidators()) > 0) {
				/* @var $validator \Flux\Link\DataField */
				$data_field = new \Flux\DataField();
				foreach ($split->getValidators() as $validator) {
					$value = $lead->getValue($validator->getDataField()->getKeyName());
					$value = $validator->getDataField()->callMappingFunc($value, $lead);
					if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS) {
						if (is_array($value) && empty(array_intersect($value, $validator->getDataFieldValue()))) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . implode(", ", $value) . '\'');
						} else if (is_string($value) && !in_array($value, $validator->getDataFieldValue())) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . $value . '\'');
						}   
					} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT) {
						if (is_array($value) && !empty(array_intersect($value, $validator->getDataFieldValue()))) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . implode(", ", $value) . '\'');
						} else if (is_string($value) && in_array($value, $validator->getDataFieldValue())) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . $value . '\'');
						}
					} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) {
						if (is_string($value) && trim($value) == '') {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . $value . '\'');
						} else if (is_array($value) && empty($value)) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . implode(", ", $value) . '\'');
						}
					} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT) {
						if (is_array($validator->getDataFieldValue())) {
							$values = $validator->getDataFieldValue();
							$check_value = intval(array_shift($values));
						} else {
							$check_value = intval($validator->getDataFieldValue());
						}
						if (is_string($value) && intval($value) < $check_value) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . $value . '\'');
						} else if (is_array($value)) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . implode(", ", $value) . '\'');
						}
					} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) {
						if (is_array($validator->getDataFieldValue())) {
							$values = $validator->getDataFieldValue();
							$check_value = intval(array_shift($values));
						} else {
							$check_value = intval($validator->getDataFieldValue());
						}
						if (is_string($value) && intval($value) > $check_value) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . $value . '\'');
						} else if (is_array($value)) {
							throw new \Exception('Validation failed on ' . $validator->getDataFieldName() . ' with value \'' . implode(", ", $value) . '\'');
						}
					}
				}
			}	
			
			/*
			 * If we have passed validation, then we can add the lead to a split queue, assign it as a conversion and 
			 * prepare to fulfill the lead
			 */			
			/* @var $lead_split \Flux\SplitQueue */
			$lead_split = new \Flux\LeadSplit();
			$lead_split->setSplit($split->getId());
			$lead_split->setLead($lead->getId());
			$lead_split->setIsFulfilled(false);
			$lead_split->setIsProcessing(true);
			$lead_split->setIsError(false);
			$lead_split->setErrorMessage('');
			$lead_split->setNextAttemptTime(new \MongoDate());
			$lead_split_insert_id = $lead_split->insert();
			$lead_split->setId($lead_split_insert_id);
			
			if ($lead_split->getSplit()->getSplit()->getFulfillImmediately()) {
				/*
				 * If we get here, then it's time for the magic.  We need to fulfill the lead to the advertiser, catch any errors
				 * and return to the client
				 */
				/* @var $fulfillment \Flux\Fulfillment */
				$fulfillment = $split->getFulfillment()->getFulfillment();
				/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
				$lead_split_attempt = new \Flux\LeadSplitAttempt();
				$lead_split_attempt->setLeadSplit($lead_split->getId());
				$lead_split_attempt->setFulfillment($fulfillment->getId());
				$lead_split_attempt->setAttemptTime(new \MongoDate());
				$lead_split_attempt_insert_id = $lead_split_attempt->insert();
				$lead_split_attempt->setId($lead_split_attempt_insert_id);
				
				$results = $fulfillment->queueLead($lead_split_attempt);
	
				/* @var $result \Flux\SplitQueueAttempt */
				foreach ($results as $key => $result) {
					// Save the split queue attempts back to the split queue item
					$lead_split->addAttempt($result);
					 
					$lead_split->setDebug($result->getRequest());
					$lead_split->setLastAttemptTime(new \MongoDate());
					$lead_split->setIsProcessing(false);
					 
					if ($result->getIsError()) {
						$lead_split->setIsError(true);
						$lead_split->setErrorMessage($result->getResponse());
						$lead_split->setIsFulfilled(false);
						$lead_split->setAttemptCount($lead_split->getAttemptCount() + 1);
						$lead_split->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
						$lead_split->update();
						throw new \Exception($result->getResponse());
					} else {
						$lead_split->setIsFulfilled(true);
						$lead_split->setIsError(false);
						$lead_split->setErrorMessage('');
						$lead_split->update();
						$post_response->setResponse('success');
						
						// Add a fulfilled event to the lead
						$lead = $lead_split->getLead()->getLead();
						$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, '1');
						$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
						$lead->update();
					}
				}
			} else {
				$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, '1');
				$lead->update();
			}
			 
			$ajax_form->setInsertId((string)$insert_id);
			$ajax_form->setRowsAffected(1);
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