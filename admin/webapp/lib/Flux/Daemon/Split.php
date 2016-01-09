<?php
namespace Flux\Daemon;

class Split extends BaseDaemon
{
	const DEBUG = true;
	
	public function action() {
		if ($this->getPrimaryThread()) {
			// Update the last run time of this daemon
			$this->updateLastRunTime();
		}
		
		$split = $this->getNextSplit();
		if ($split instanceof \Flux\Split) {
			$max_event_time = $split->getLastRunTime();
			// Based on the split parameters, find leads that match and place them into the split collection
			$criteria = array();
			
			
			if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL) {
				// Normal splits should find leads created since the last time they were run
				$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gt' => $max_event_time);
			} else {
				// Catch-all splits should be delayed for 1 hour to give the other splits time to accept the leads
				$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gt' => new \MongoDate(($max_event_time->sec - (2 * 60 * 60))), '$lt' => new \MongoDate(strtotime('now - 1 hour')));
			}
			
			//$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_key_name'] = array('$nin' => array(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME));
			
			// Add the offers to the criteria
			if (count($split->getOffers()) > 0) {
				$offer_ids = $split->getOffers();
				array_walk($offer_ids, function(&$value) { $value = $value->getId(); });
				$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.offer._id'] = array('$in' => $offer_ids);
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
						$values = $filter->getDataFieldValue();
						$filter_criteria = array('$gte' => array_shift($values));
					} else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) {
						$values = $filter->getDataFieldValue();
						$filter_criteria = array('$lte' => array_shift($values));
					}					
					
					if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
						$criteria[\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $filter->getDataFieldKeyName()] = $filter_criteria;
					} else if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
						$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . $filter->getDataFieldKeyName()] = $filter_criteria;
					} else if ($filter->getDataField()->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
						$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['data_field._id'] = $filter->getId();
						$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['v'] = $filter_criteria;
					}
				}
			}
			
			if (self::DEBUG) {
				$op_query = json_encode($criteria);
				$op_query = str_replace('"$group"', '$group', $op_query);
				$op_query = str_replace('"$max"', '$max', $op_query);
				$op_query = str_replace('"$sum"', '$sum', $op_query);
				$op_query = str_replace('"$unwind"', '$unwind', $op_query);
				$op_query = str_replace('"$match"', '$match', $op_query);
				$op_query = str_replace('"$gte"', '$gte', $op_query);
				$op_query = str_replace('"$lt"', '$lt', $op_query);
				$op_query = str_replace('"$substr"', '$substr', $op_query);
				$op_query = str_replace('"$exists"', '$exists', $op_query);
				$op_query = str_replace('"$elemMatch"', '$elemMatch', $op_query);
				$op_query = str_replace('"$project"', '$project', $op_query);
				
				$op_query = preg_replace('/{"\$id":"([a-z0-9]*)"}/', 'ObjectId("$1")', $op_query);
				$op_query = str_replace(json_encode($max_event_time), 'ISODate(\'' . $max_event_time->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
				
				$start_date = new \MongoDate(($max_event_time->sec - (2 * 60 * 60)));
				$end_date = new \MongoDate(strtotime('now - 1 hour'));
				
				$op_query = str_replace(json_encode($start_date), 'ISODate(\'' . $start_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
				$op_query = str_replace(json_encode($end_date), 'ISODate(\'' . $end_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
				$op_query = str_replace(json_encode($max_event_time), 'ISODate(\'' . $max_event_time->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
				$this->log('Finding leads for ' . $split->getName() . ' after ' . date('m/d/Y g:i:s', $max_event_time->sec) . ': ' . $op_query, array($this->pid, $split->getId()));
			} else {
				$this->log('Finding leads for ' . $split->getName() . ' after ' . date('m/d/Y g:i:s', $max_event_time->sec), array($this->pid, $split->getId()));
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
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . implode(", ", $value) . '\'');
									} else if (is_string($value) && !in_array($value, $validator->getDataFieldValue())) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . $value . '\'');
									}   
								} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT) {
									if (is_array($value) && !empty(array_intersect($value, $validator->getDataFieldValue()))) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . implode(", ", $value) . '\'');
									} else if (is_string($value) && in_array($value, $validator->getDataFieldValue())) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . $value . '\'');
									}
								} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) {
									if (is_string($value) && trim($value) == '') {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . $value . '\'');
									} else if (is_array($value) && empty($value)) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . implode(", ", $value) . '\'');
									}
								} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT) {
									if (is_array($validator->getDataFieldValue())) {
										$values = $validator->getDataFieldValue();
										$check_value = intval(array_shift($values));
									} else {
										$check_value = intval($validator->getDataFieldValue());
									}
									if (is_string($value) && intval($value) < $check_value) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . $value . '\'');
									} else if (is_array($value)) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . implode(", ", $value) . '\'');
									}
								} else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) {
									if (is_array($validator->getDataFieldValue())) {
										$values = $validator->getDataFieldValue();
										$check_value = intval(array_shift($values));
									} else {
										$check_value = intval($validator->getDataFieldValue());
									}
									if (is_string($value) && intval($value) > $check_value) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . $value . '\'');
									} else if (is_array($value)) {
										throw new \Exception('Validation failed on ' . $validator->getName() . ' with value \'' . implode(", ", $value) . '\'');
									}
								}
							}
						}
						// Verify that this lead has not already been fulfilled
						/* @var $lead_event \Flux\LeadEvent */
						/* @var $lead_split \Flux\LeadSplit */
						$lead_split = new \Flux\LeadSplit();
						$existing_lead = $lead_split->getCollection()->findOne(array('lead._id' => $lead->getId(), 'split._id' => $split->getId()));
						if (!is_null($existing_lead)) {
							throw new \Exception('Validation failed on ALREADY FULFILLED check');
						}
						
					} catch (\Exception $e) {
						$this->log('Lead found [' . $lead->getId() . ']: FAILED VALIDATION (' . $e->getMessage() . ')', array($this->pid, $split->getId()));
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
					
					/* @var $lead_split \Flux\LeadSplit */
					$lead_split = new \Flux\LeadSplit();
					$lead_split->setLead($lead->getId());
					$lead_split->setSplit($split->getId());
					$lead_split->setIsFulfilled(false);
					$lead_split->setIsProcessing(false);
					$lead_split->setIsError(false);
					$lead_split->setErrorMessage('');
					$lead_split->setNextAttemptTime(new \MongoDate());
					$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_UNFULFILLED);
					
					// If this split is normal, then we can add a lead to multiple splits
					if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL) {
						$existing_lead = $lead_split->getCollection()->findOne(array('lead._id' => $lead_split->getLead()->getId(), 'split._id' => $lead_split->getSplit()->getId()));
						if (is_null($existing_lead)) {
							$this->log('Lead found [' . $lead->getId() . ']: ADDED TO SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
							$lead_split->insert();
							$added_leads++;
						} else {
							$this->log('Lead found [' . $lead->getId() . ']: ALREADY EXISTS ON SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
						}
					} else if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_HOST_POST) {
						// If this is a catch-all split, then we can only add this lead if it doesn't exist anywhere else
						$existing_lead = $lead_split->getCollection()->findOne(array('lead._id' => $lead_split->getLead()->getId(), 'split._id' => $lead_split->getSplit()->getId()));
						if (is_null($existing_lead)) {
							$this->log('Lead found [' . $lead->getId() . ']: ADDED TO HOST/POST SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
							$lead_split->insert();
							$added_leads++;
						} else {
							$this->log('Lead found [' . $lead->getId() . ']: ALREADY EXISTS ON HOST/POST SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
						}  
					} else if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_CATCH_ALL) {
						// If this is a catch-all split, then we can only add this lead if it doesn't exist anywhere else
						$existing_lead = $lead_split->getCollection()->findOne(array('lead._id' => $lead_split->getLead()->getId()));
						if (is_null($existing_lead)) {
							$this->log('Lead found [' . $lead->getId() . ']: ADDED TO CATCH-ALL SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
							$lead_split->setIsCatchAll(true);
							$lead_split->insert();
							$added_leads++;
						} else {
							$this->log('Lead found [' . $lead->getId() . ']: ALREADY EXISTS ON CATCH-ALL SPLIT ' . $split->getName(), array($this->pid, $split->getId()));
						}  
					}
				}
				$split->update(array('_id' => $split->getId()), array('$set' => array('last_queue_time' => new \MongoDate()), '$inc' => array('queue_count' => $added_leads)), array());
			} else {
				$this->log('0 Leads Found for ' . $split->getName() . ' after ' . date('m/d/Y g:i:s', $split->getLastRunTime()->sec), array($this->pid, $split->getId()));
			}
			sleep(10);
			
			$split->update(array('_id' => $split->getId()), array('$unset' => array('pid_split' => 1), '$set' => array('last_run_time' => $max_event_time, 'pid_time_split' => new \MongoDate())), array());

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
						// if there isn't a process running, then clear the PID
						$split_record->clearPid();
					}
				} else {
					$split_record->clearPid();
				}
			}
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
