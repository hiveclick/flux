<?php
namespace Flux\Daemon;

class Split extends BaseDaemon
{
	public function action() {
		$split = $this->getNextSplit();
		if ($split instanceof \Flux\Split) {
			$max_event_time = $split->getLastRunTime();
			// Based on the split parameters, find leads that match and place them into the split collection
			$criteria = array();
			$this->log('Finding leads for ' . $split->getName() . ' after ' . date('m/d/Y g:i:s', $split->getLastRunTime()->sec), array($this->pid, $split->getId()));
			
			// Always add a time constraint to the offers
			$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gt' => $split->getLastRunTime());
			
			// Add the offers to the criteria
			if (count($split->getOffers()) > 0) {
				$offer_ids = $split->getOffers();
				array_walk($offer_ids, function(&$value) { $value = $value->getOfferId(); });
				$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.offer.offer_id'] = array('$in' => $offer_ids);
			}
			
			// Add the filters to the criteria
			if (count($split->getFilters()) > 0) {
				/* @var $filter \Flux\Link\DataField */
				$data_field = new \Flux\DataField();
				foreach ($split->getFilters() as $filter) {
					$filter_criteria = array();
					if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS) {
						$filter_criteria = array('$in' => $filter->getDataFieldValue());
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT) {
						$filter_criteria = array('$nin' => $filter->getDataFieldValue());
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) {
						$filter_criteria = array('$ne' => '');
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET) {
						$filter_criteria = array('$exists' => true);
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT) {
						$filter_criteria = array('$gte' => array_shift($filter->getDataFieldValue()));
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) {
						$filter_criteria = array('$lte' => array_shift($filter->getDataFieldValue()));
					}					
					
					if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
						$criteria[\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $filter->getDataFieldKeyName()] = $filter_criteria;
					} else if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
						$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . $data_field->getKeyName()] = $filter_criteria;
					} else if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
						$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['data_field.data_field_id'] = $filter->getDataFieldId();
						$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['v'] = $filter_criteria;
					}
				}
			}
			
			$this->log(json_encode($criteria), array($this->pid, $split->getId()));		
			
			/* @var $lead \Flux\Lead */
			$lead = new \Flux\Lead();
			$lead->setSort("-1");
			$lead->setIgnorePagination(true);
			$matched_leads = $lead->queryAll($criteria, false);
						
			if ($matched_leads->hasNext()) {
				$this->log('Found ' . $matched_leads->count() . ' leads, processing...', array($this->pid, $split->getId()));
				
				// Save the # of leads to the split for accounting reasons
				$split->update(array('_id' => $split->getId()), array('$set' => array('last_queue_time' => new \MongoDate()), '$inc' => array('queue_count' => $matched_leads->count())), array());
			
				/* @var $split_queue \Flux\SplitQueue */
				$split_queue = new \Flux\SplitQueue($split->getId());
				while ($matched_leads->hasNext()) {
					$lead_doc = $matched_leads->getNext();
					
					/* @var $lead \Flux\Lead */
					$lead = new \Flux\Lead();
					$lead->populate($lead_doc);
					$this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId(), array($this->pid, $split->getId()));
					
					// Find the max event time
					/* @var $lead_event \Flux\LeadEvent */
					foreach ($lead->getE() as $lead_event) {
                        if ($lead_event->getT() instanceof \MongoDate) {
                            if ($lead_event->getT()->sec > $max_event_time->sec) {
					            $max_event_time = $lead_event->getT();
                            }
					   }   	
					}
					
					$split_queue->setLead($lead->getId());
					$split_queue->setIsFulfilled(false);
					$split_queue->setIsProcessing(false);
					$split_queue->setIsError(false);
					$split_queue->setErrorMessage('');
					$split_queue->setNextAttemptTime(new \MongoDate());
					
					/*
					$lead_array = $lead->toArray();
					$lead_array['split'] = $split_queue->getSplit()->toArray();
					$lead_array['is_fulfilled'] = false;
					$lead_array['is_error'] = false;
					$lead_array['is_processing'] = false;
					*/
					
					$split_queue->insert();
					
					// Add the lead to the queue
					#$split_queue->getCollection()->save($lead_array);
				}
			}
			sleep(10);
			
			$split->update(array('_id' => $split->getId()), array('$unset' => array('__pid_split' => 1), '$set' => array('last_run_time' => $max_event_time)), array());


			//$this->log('Done Processing Split: ' . $split_record->getName(), array($this->pid, $split_record->getId()));
			return true;
		} else {
			$this->clearExpiredPids();
		}
		return false;
	}
	
	/**
	 * Clears expired pids
	 * @return \Flux\Fulfill
	 */
	protected function clearExpiredPids() {
		// If there are no exports, then let's clean up some of the older ones
		$split = new \Flux\Split();
		$criteria = array(
				'status' => \Flux\Split::SPLIT_STATUS_ACTIVE,
				'pid_split' => array('$exists' => true),
				'last_run_time' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime('now')))))
		);		
		$split_records = $split->queryAll($criteria);
		if (count($split_records) > 0) {
			foreach ($split_records as $split_record) {				
				if (!is_null($split_record->getPidSplit())) {
					$cmd = 'ps -p ' . $split_record->getPidSplit() . ' | grep -v "PID"';
					$cmd_response = trim(shell_exec($cmd));
					if ($cmd_response == '') {
						$this->log('Clearing expired PIDs: ' . $split_record->getPidSplit(), array($this->pid));
						// if there isn't a process running, then clear the PID
						$split_record->clearPid();
					}
				} else {
					$this->log('Clearing blank PIDs: ' . $split_record->getId(), array($this->pid));
					$split_record->clearPid();
				}
			}
		} else {
			$this->log('No Split to Use and no cleared PIDs', array($this->pid));
		}
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Flux\Split
	 */
	protected function getNextSplit() {
		$split = new \Flux\Split();
		// Find active splits with no pid, set the pid, and return the split
		$split_record = $split->findAndModify(
			array(
				'status' => \Flux\Split::SPLIT_STATUS_ACTIVE,
				'pid_split' => array('$exists' => false)
			),
			array('$set' => array(
				'pid_split' => $this->pid,
				'pid_time_split' => new \MongoDate()
			)),
			null,
			array(
				'new' => true,
				'sort' => array('__pid_time_split' => 1)
			)
		);
		return $split_record;
	}
}
