<?php
namespace Flux\Daemon;

class Fulfill extends BaseDaemon
{
	public function action() {
		/* @var $queue_item \Flux\SplitQueue */
		$queue_item = $this->getNextQueueItem();
		if ($queue_item instanceof \Flux\SplitQueue) {
			if (!$queue_item->getSplit()->getSplit()->getScheduling()->isValid()) {
				$this->log('Cannot Fulfill Queue item ' . $queue_item->getId() . ' because the split schedule is not valid', array($this->pid, $queue_item->getId()));
				$queue_item->setErrorMessage('Fulfillment Schedule is closed');
				for ($i=0;$i<168;$i++) {
					if ($queue_item->getSplit()->getSplit()->getScheduling()->isValid(strtotime('now + ' . $i . ' hours'))) {
						$next_attempt_time = new \MongoDate(strtotime('now + ' . $i . ' hours'));
						$queue_item->setNextAttemptTime($next_attempt_time);
						break;
					}
				}
				
				$queue_item->setLastAttemptTime(new \MongoDate());
				$queue_item->setIsProcessing(false);
				$queue_item->update();
				return false;
			}
			if (!$queue_item->getSplit()->getSplit()->getFulfillImmediately()) {
				$this->log('Cannot Fulfill Queue item ' . $queue_item->getId() . ' because the split is not setup for immediate fulfillment', array($this->pid, $queue_item->getId()));
				return false;
			}
			
			if ($queue_item->getSplit()->getSplit()->getFulfillImmediately() && \MongoId::isValid($queue_item->getSplit()->getSplit()->getFulfillDelay())) {
				if ((strtotime('now') - $queue_item->getLead()->getLead()->getModified->sec) > ($queue_item->getSplit()->getSplit()->getFulfillDelay() * 60)) {
					$this->log('Cannot Fulfill Queue item ' . $queue_item->getId() . ' because the delay has not been reached yet', array($this->pid, $queue_item->getId()));
					return false;
				}
			}
			
			if ($queue_item->getIsFulfilled()) {
				// Create the fulfillment handler
				/* @var $fulfillment \Flux\Fulfillment */
				$fulfillment = $queue_item->getSplit()->getSplit()->getFulfillment()->getFulfillment();
					
				/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
				$split_queue_attempt = new \Flux\SplitQueueAttempt();
				$split_queue_attempt->setSplitQueue($queue_item->getId());
				$split_queue_attempt->setFulfillment($fulfillment->getId());
				$split_queue_attempt->setAttemptTime(new \MongoDate());
				
				// The lead has already been fulfilled, so don't allow it to be fulfilled again
				/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
				$split_queue_attempt = new \Flux\SplitQueueAttempt();
				$split_queue_attempt->setSplitQueue($queue_item->getId());
				$split_queue_attempt->setFulfillment($fulfillment->getId());
				$split_queue_attempt->setAttemptTime(new \MongoDate());
				$split_queue_attempt->setIsError(false);
				$split_queue_attempt->setResponse('Already Fulfilled');
				$queue_item->addAttempt($split_queue_attempt);
				$queue_item->setDisposition(\Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED);
				$queue_item->setErrorMessage('Already Fulfilled');
				
				return false;
			}
					
			//
			// We made it past the validations, so attempt to fulfill the lead
			//	
					
			$this->log('Fulfilling Queue item ' . $queue_item->getId(), array($this->pid, $queue_item->getId()));
					
			// Create the fulfillment handler
			/* @var $fulfillment \Flux\Fulfillment */
			$fulfillment = $queue_item->getSplit()->getSplit()->getFulfillment()->getFulfillment();
			 
			/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
			$split_queue_attempt = new \Flux\SplitQueueAttempt();
			$split_queue_attempt->setSplitQueue($queue_item->getId());
			$split_queue_attempt->setFulfillment($fulfillment->getId());
			$split_queue_attempt->setAttemptTime(new \MongoDate());
			
			$results = $fulfillment->queueLead($split_queue_attempt);
					 
			/* @var $result \Flux\SplitQueueAttempt */
			foreach ($results as $key => $result) {
				// Save the split queue attempts back to the split queue item
				$queue_item->addAttempt($result);
				 
				$queue_item->setDebug($result->getRequest());
				$queue_item->setLastAttemptTime(new \MongoDate());
				$queue_item->setIsProcessing(false);
				 
				if ($result->getIsError()) {
					$queue_item->setIsError(true);
					$queue_item->setErrorMessage($result->getResponse());
					$queue_item->setIsFulfilled(false);
					$queue_item->setAttemptCount($queue_item->getAttemptCount() + 1);
					$queue_item->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
					$queue_item->setDisposition(\Flux\SplitQueue::DISPOSITION_PENDING);
					 
					/* @var $report_lead \Flux\ReportLead */
					$report_lead = new \Flux\ReportLead();
					$lead = $queue_item->getLead()->getLead();
					$report_lead->setLead($lead->getId());
					$report_lead->setClient($lead->getTracking()->getClient()->getId());
					$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
					$report_lead->setRevenue(0.00);
					$report_lead->setPayout(0.00);
					$report_lead->setReportDate(new \MongoDate());
					$report_lead->insert();
					$this->log('Lead found [' . $queue_item->getSplit()->getName() . ']: ' . $queue_item->getId() . ', ALREADY FULFILLED', array($this->pid, $queue_item->getId()));
				} else {
					$queue_item->setIsFulfilled(true);
					$queue_item->setIsError(false);
					$queue_item->setErrorMessage('');
					$queue_item->setDisposition(\Flux\SplitQueue::DISPOSITION_FULFILLED);
					 
					// Add a fulfilled event to the lead
					/* @var $lead \Flux\Lead */
					$lead = $queue_item->getLead()->getLead();
					$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
					$lead->update();
					 
					// Add/Update the lead reporting
					/* @var $report_lead \Flux\ReportLead */
					$report_lead = new \Flux\ReportLead();
					$report_lead->setLead($lead->getId());
					$report_lead->setClient($lead->getTracking()->getClient()->getId());
					$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
					$report_lead->setRevenue($fulfillment->getBounty());
					if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
						$report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
					} else {
						$report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
					}
					$report_lead->setReportDate(new \MongoDate());
					$report_lead->setAccepted(true);
					$report_lead->insert();
					$this->log('Lead found [' . $queue_item->getSplit()->getName() . ']: ' . $queue_item->getId() . ', FULFILLED', array($this->pid, $queue_item->getId()));
				}
			}
			 
			$queue_item->update();
			
			return true;
		}
		return false;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Flux\Split
	 */
	protected function getNextQueueItem() {
		$split_queue = new \Flux\SplitQueue();
		// Find active splits with no pid, set the pid, and return the split
		$split_queue_item = $split_queue->findAndModify(
			array(
				'next_attempt_time' => array('$lt' => new \MongoDate()),
				'is_processing' => false,
				'is_catch_all' => false,
				'disposition' => 0,
				'attempt_count' => array('$lte' => 5)
			),
			array('$set' => array(
				'is_processing' => true
			)),
			null,
			array(
				'new' => true,
				'sort' => array('_id' => 1)
			)
		);
		return $split_queue_item;
	}
}
