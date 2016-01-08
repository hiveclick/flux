<?php
namespace Flux\Daemon;

class CatchAllCleanup extends BaseDaemon
{
	public function action() {
		/* @var $queue_item \Flux\LeadSplit */
		$lead_split_item = $this->getNextQueueItem();
		if ($lead_split_item instanceof \Flux\LeadSplit) {
			/* @var $event \Flux\LeadEvent */
			foreach ($lead_split_item->getLead()->getLead()->getE() as $event) {
				if ($event->getDataField()->getDataFieldKeyName() == \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME) {
					// This lead has already been fulfilled, so remove it from the queue
					$this->log('Lead found [' . $lead_split_item->getId() . ']: ' . $lead_split_item->getLead()->getId() . '...MARKING AS FULFILLED', array($this->pid, $lead_split_item->getId()));
					$lead_split_item->setExpireAt(new \MongoDate());
				}				
			}
			
			$lead_split_item->update();
			// Give other processes time to work
			sleep(1);
			return true;
		}
		// If we don't find anything, then sleep
		if ($this->getPrimaryThread()) {
			$this->log('No more leads, sleeping for 1 minute...', array($this->pid));
		}
		sleep(60);
		return false;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Flux\LeadSplit
	 */
	protected function getNextQueueItem() {
		$lead_split = new \Flux\LeadSplit();
		// Find active splits with no pid, set the pid, and return the split
		$lead_split_item = $lead_split->findAndModify(
			array(
				'next_cleanup_time' => array('$lt' => new \MongoDate()),
				'is_catch_all' => true,
				'disposition' => 0
			),
			array('$set' => array(
				'next_cleanup_time' => new \MongoDate(strtotime('now + 1 day'))
			)),
			null,
			array(
				'new' => true,
				'sort' => array('_id' => 1)
			)
		);
		return $lead_split_item;
	}
}
