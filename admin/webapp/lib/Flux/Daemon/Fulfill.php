<?php
namespace Flux\Daemon;

class Fulfill extends BaseDaemon
{
	public function action() {
		/* @var $queue_item \Flux\SplitQueue */
		$queue_item = $this->getNextQueueItem();
		if ($queue_item instanceof \Flux\SplitQueue) {
			if ($queue_item->getSplit()->getSplit()->getScheduling()->isValid()) {
			
				$this->log('Fulfilling Queue item ' . $queue_item->getId(), array($this->pid, $queue_item->getId()));
				
				// Create the fulfillment handler
				/* @var $fulfillment \Flux\Fulfillment */
				$fulfillment = $queue_item->getSplit()->getSplit()->getFulfillment()->getFulfillment();
				$results = $fulfillment->sendLeads(array($split_queue->getLead()->getLead()));
				
				/* @var $result \Flux\ExportQueue */
				foreach ($results as $result) {
					if ($result->getIsError()) {
						$queue_item->setIsError(true);
						$queue_item->setErrorMessage($result->getResponse());
						$queue_item->setIsFulfilled(false);
						$queue_item->setIsProcessing(false);
						$queue_item->setLastAttemptTime(new \MongoDate());
						$queue_item->setAttemptCount($queue_item->getAttemptCount() + 1);
						$queue_item->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
						// If this item failed, then send an email according to the split
						
						
					} else {
						$queue_item->setIsFulfilled(true);
						$queue_item->setLastAttemptTime(new \MongoDate());
						$queue_item->setIsProcessing(false);
					}
				}
				
				$queue_item->update();
	
				return true;
			} else {
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
			}
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
				'is_fulfilled' => false,
				'next_attempt_time' => array('$lt' => new \MongoDate()),
				'is_processing' => false,
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
