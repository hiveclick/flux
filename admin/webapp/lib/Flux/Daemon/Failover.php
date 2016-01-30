<?php
namespace Flux\Daemon;

class Failover extends BaseDaemon
{
	public function action() {
		$lead_split_item = $this->getNextQueueItem();
		try {
			/* @var $lead_split_item \Flux\LeadSplit */
			
			if ($lead_split_item instanceof \Flux\LeadSplit) {
				// Make sure enough time has elapsed
				if (($lead_split_item->getLastAttemptTime()->sec + ($lead_split_item->getSplit()->getSplit()->getFailoverWaitTime() * 60)) > strtotime('now')) {
					$this->log('Cannot Failover Queue item ' . $lead_split_item->getId() . ' because the split schedule is not valid (' . date('m/d/Y h:i', $lead_split_item->getLastAttemptTime()->sec + ($lead_split_item->getSplit()->getSplit()->getFailoverWaitTime() * 60)) . ' > ' . date('m/d/Y h:i', strtotime('now')), array($this->pid, $lead_split_item->getId()));
					$lead_split_item->setErrorMessage('Fulfillment Schedule is closed');
					$next_attempt_time = new \MongoDate(($lead_split_item->getLastAttemptTime()->sec + ($lead_split_item->getSplit()->getSplit()->getFailoverWaitTime() * 60)));
					$lead_split_item->setNextAttemptTime($next_attempt_time);
					$lead_split_item->setIsProcessing(false);
					$lead_split_item->update();
					return false;
				}
				
				// This item is fulfilled, but not confirmed so enable the failover split
				$this->log('Assigning Lead as Failover', array($this->pid, $lead_split_item->getId()));
				$lead_split_item->setErrorMessage('Attempt set to Failover because it wasn\'t confirmed');
				$lead_split_item->setDisposition(\Flux\LeadSplit::DISPOSITION_FAILOVER);
				$lead_split_item->setIsProcessing(false);
				$lead_split_item->update();
				
				
				// Verify that we don't already have a lead split setup
				/* @var $lead_split \Flux\LeadSplit */
				$lead_split = new \Flux\LeadSplit();
				$existing_lead = $lead_split->getCollection()->findOne(array('lead._id' => $lead_split_item->getLead()->getId(), 'split._id' => $lead_split_item->getSplit()->getSplit()->getFailoverSplit()->getId()));
				if (!is_null($existing_lead)) {
					throw new \Exception('Validation failed on ALREADY FULFILLED check');
				}
				
				// Now create a new split for this lead based on the failover
				$this->log('Creating new split based on failover settings ' . $lead_split_item->getSplit()->getSplit()->getFailoverSplit()->getName(), array($this->pid, $lead_split_item->getId()));
				$lead_split = new \Flux\LeadSplit();
				$lead_split->setLead($lead_split_item->getLead()->getId());
				$lead_split->setSplit($lead_split_item->getSplit()->getSplit()->getFailoverSplit()->getId());
				$lead_split->setIsFulfilled(false);
				$lead_split->setIsProcessing(false);
				$lead_split->setIsError(false);
				$lead_split->setErrorMessage('');
				$lead_split->setNextAttemptTime(new \MongoDate());
				$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_UNFULFILLED);
				$lead_split->insert();
				
				return true;
			} else {
				throw new \Exception('Item is not an instance of \Flux\LeadSplit (' . var_export($lead_split_item, true) . ')');
				
			}
		} catch (\Exception $e) {
			$this->log('FAILED - ' . $e->getMessage(), array($this->pid));
		}
		return false;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Flux\LeadSplit
	 */
	protected function getNextQueueItem() {
		$failover_split_id_array = array();
		$split = new \Flux\Split();
		$failover_splits = $split->queryAll(array('failover_enable' => true));
		foreach ($failover_splits as $failover_split) {
			$failover_split_id_array[] = $failover_split->getId();
		}
		
		
		$lead_split = new \Flux\LeadSplit();
		// Find active splits with no pid, set the pid, and return the split
		$criteria = array('last_attempt_time' => array('$gte' => new \MongoDate(strtotime('now - 2 days')), '$lt' => new \MongoDate()),
				'is_processing' => false,
				'is_catch_all' => false,
				'is_confirmed' => false,
				'is_returned' => false,
				'disposition' => \Flux\LeadSplit::DISPOSITION_FULFILLED,
				'split._id' => array('$in' => $failover_split_id_array),
				'attempt_count' => array('$lte' => 5));
				
		$lead_split_item = $lead_split->findAndModify(
			$criteria,
			array('$set' => array(
				'is_processing' => true
			)),
			null,
			array(
				'new' => true,
				'sort' => array('_id' => 1)
			)
		);
		
		// If we don't find anybody, then attempt to find pending leads that can't be fulfilled
		if (is_null($lead_split_item)) {
			$criteria = array(
					'last_attempt_time' => array('$gte' => new \MongoDate(strtotime('now - 8 days')), '$lt' => new \MongoDate()),
					'is_processing' => false,
					'is_catch_all' => false,
					'is_confirmed' => false,
					'is_returned' => false,
					'disposition' => \Flux\LeadSplit::DISPOSITION_PENDING,
					'split._id' => array('$in' => $failover_split_id_array),
					'attempt_count' => array('$gte' => 5));
			
			$lead_split_item = $lead_split->findAndModify(
					$criteria,
					array('$set' => array(
						'is_processing' => true
					)),
					null,
					array(
						'new' => true,
						'sort' => array('_id' => 1)
					)
			);
		}
		
		return $lead_split_item;
	}
}
