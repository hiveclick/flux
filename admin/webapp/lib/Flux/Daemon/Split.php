<?php
namespace Flux\Daemon;

class Split extends BaseDaemon
{
    const DEBUG = false;
    
	public function action() {
		$split = $this->getNextSplit();
		if ($split instanceof \Flux\Split) {
			$max_event_time = $split->getLastRunTime();
			// Based on the split parameters, find leads that match and place them into the split collection
			$criteria = array();
			
			
			if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL) {
			    $this->log('Finding leads for ' . $split->getName() . ' after ' . date('m/d/Y g:i:s', $split->getLastRunTime()->sec), array($this->pid, $split->getId()));
    			// Normal splits should find leads created since the last time they were run
    			$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gt' => $split->getLastRunTime());
			} else {
			    // Catch-all splits should be delayed for 1 hour to give the other splits time to accept the leads
			    $this->log('Finding leads for ' . $split->getName() . ' between ' . date('m/d/Y g:i:s', ($split->getLastRunTime()->sec - (2 * 60 * 60))) . ' and ' . date('m/d/Y g:i:s', strtotime('now - 1 hour')), array($this->pid, $split->getId()));
			    $criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gt' => new \MongoDate(($split->getLastRunTime()->sec - (2 * 60 * 60))), '$lt' => new \MongoDate(strtotime('now - 1 hour')));
			}
			
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
						$filter_criteria = array('$exists' => true, '$nin' => $filter->getDataFieldValue());
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) {
						$filter_criteria = array('$exists' => true, '$ne' => '');
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
			
			if (self::DEBUG) { 
                $this->log(json_encode($criteria), array($this->pid, $split->getId()));
			}		
			
			/* @var $lead \Flux\Lead */
			$lead = new \Flux\Lead();
			$lead->setSort("-1");
			$lead->setIgnorePagination(true);
			$matched_leads = $lead->queryAll($criteria, false);
						
			if ($matched_leads->hasNext()) {				
				// Save the # of leads to the split for accounting reasons
				$added_leads = 0;
			
				while ($matched_leads->hasNext()) {
					$lead_doc = $matched_leads->getNext();
					
					/* @var $lead \Flux\Lead */
					$lead = new \Flux\Lead();
					$lead->populate($lead_doc);
					
					try {
    					// Validate the lead before we proceed
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
					} catch (\Exception $e) {
					    $this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId() . ', FAILED VALIDATION (' . $e->getMessage() . ')', array($this->pid, $split->getId()));
					    continue;
					}				
					
					// Find the max event time
					/* @var $lead_event \Flux\LeadEvent */
					foreach ($lead->getE() as $lead_event) {
                        if ($lead_event->getT() instanceof \MongoDate) {
                            if ($lead_event->getT()->sec > $max_event_time->sec) {
					            $max_event_time = $lead_event->getT();
                            }
					   }   	
					}
					
					/* @var $split_queue \Flux\SplitQueue */
					$split_queue = new \Flux\SplitQueue($split->getId());
					$split_queue->setLead($lead->getId());
					$split_queue->setIsFulfilled(false);
					$split_queue->setIsProcessing(false);
					$split_queue->setIsError(false);
					$split_queue->setErrorMessage('');
					$split_queue->setNextAttemptTime(new \MongoDate());
					$split_queue->setDisposition(\Flux\SplitQueue::DISPOSITION_UNFULFILLED);
					
					// If this split is normal, then we can add a lead to multiple splits
					if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL) {
    					$existing_lead = $split_queue->getCollection()->findOne(array('lead.lead_id' => $split_queue->getLead()->getLeadId(), 'split.split_id' => $split_queue->getSplit()->getSplitId()));
    					if (is_null($existing_lead)) {
    					    $this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId() . ', ADDED TO SPLIT', array($this->pid, $split->getId()));
    					    $split_queue->insert();
    					    $added_leads++;
    					} else {
    					    $this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId() . ', ALREADY EXISTS', array($this->pid, $split->getId()));
    					}
					} else {
					    // If this is a catch-all split, then we can only add this lead if it doesn't exist anywhere else
					    $existing_lead = $split_queue->getCollection()->findOne(array('lead.lead_id' => $split_queue->getLead()->getLeadId()));
					    if (is_null($existing_lead)) {
					        $this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId() . ', ADDED TO SPLIT', array($this->pid, $split->getId()));
					        $split_queue->setIsCatchAll(true);
					        $split_queue->insert();
					        $added_leads++;
					    } else {
					        $this->log('Lead found [' . $split->getId() . ']: ' . $lead->getId() . ', ALREADY EXISTS', array($this->pid, $split->getId()));
					    }  
					}
				}
				$split->update(array('_id' => $split->getId()), array('$set' => array('last_queue_time' => new \MongoDate()), '$inc' => array('queue_count' => $added_leads)), array());
			}
			sleep(10);
			
			$split->update(array('_id' => $split->getId()), array('$unset' => array('pid_split' => 1), '$set' => array('last_run_time' => $max_event_time)), array());

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
				'sort' => array('pid_time_split' => 1)
			)
		);
		return $split_record;
	}
}
