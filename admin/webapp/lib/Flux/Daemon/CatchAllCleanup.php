<?php
namespace Flux\Daemon;

class CatchAllCleanup extends BaseDaemon
{
	public function action() {
		/* @var $queue_item \Flux\SplitQueue */
		$queue_item = $this->getNextQueueItem();
		if ($queue_item instanceof \Flux\SplitQueue) {
		    /* @var $event \Flux\LeadEvent */
		    foreach ($queue_item->getLead()->getLead()->getE() as $event) {
		        if ($event->getDataField()->getDataFieldKeyName() == \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME) {
                    // This lead has already been fulfilled, so remove it from the queue
		            $this->log('Lead found [' . $queue_item->getId() . ']: ' . $queue_item->getLead()->getLeadId() . '...MARKING AS FULFILLED', array($this->pid, $queue_item->getId()));
                    $queue_item->setExpireAt(new \MongoDate());
		        }		        
		    }
			
		    $queue_item->update();
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
	 * @return \Flux\SplitQueue
	 */
	protected function getNextQueueItem() {
		$split_queue = new \Flux\SplitQueue();
		// Find active splits with no pid, set the pid, and return the split
		$split_queue_item = $split_queue->findAndModify(
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
		return $split_queue_item;
	}
}
