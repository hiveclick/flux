<?php
namespace Flux\Daemon;

class Fulfill extends BaseDaemon
{
	const WINDOW_SIZE = 100;

	private static $_queue;

	public function action() {
		// Instantiate our static queue model
		self::$_queue = new \Flux\LeadSplit();

		// If this is the primary thread, then run some cleanup/statistics
		if ($this->getPrimaryThread()) {
			$this->calculatePendingRecordCount();
			// Only return if there are other threads to handle the processes
			if ($this->getDaemon()->getThreads() > 1) { return true; }
		}

		$queue_items = $this->getNextItems();
		if (count($queue_items) > 0) {
			try {
				/* @var $lead_split_item \Flux\LeadSplit */
				foreach ($queue_items as $lead_split_item) {
					try {
						try {
							// Process the queue item
							if (!$lead_split_item->getSplit()->getSplit()->getScheduling()->isValid()) {
								$this->log('Cannot Fulfill Queue item ' . $lead_split_item->getId() . ' because the split schedule is not valid', array($this->pid, $lead_split_item->getId()));
								$lead_split_item->setErrorMessage('Fulfillment Schedule is closed');
								for ($i = 0; $i < 168; $i++) {
									if ($lead_split_item->getSplit()->getSplit()->getScheduling()->isValid(strtotime('now + ' . $i . ' hours'))) {
										$next_attempt_time = new \MongoDate(strtotime('now + ' . $i . ' hours'));
										$lead_split_item->setNextAttemptTime($next_attempt_time);
										break;
									}
								}

								$lead_split_item->setLastAttemptTime(new \MongoDate());
								$lead_split_item->setIsProcessing(false);
								$lead_split_item->update();
								throw new \Exception('Cannot Fulfill Queue item ' . $lead_split_item->getId() . ' because the split schedule is not valid');
							}
							if (!$lead_split_item->getSplit()->getSplit()->getFulfillImmediately()) {
								throw new \Exception('Cannot Fulfill Queue item ' . $lead_split_item->getId() . ' because the split is not setup for immediate fulfillment');
							}

							if ($lead_split_item->getSplit()->getSplit()->getFulfillImmediately() && \MongoId::isValid($lead_split_item->getSplit()->getSplit()->getFulfillDelay())) {
								if ((strtotime('now') - $lead_split_item->getLead()->getLead()->getModified->sec) > ($lead_split_item->getSplit()->getSplit()->getFulfillDelay() * 60)) {
									throw new \Exception('Cannot Fulfill Queue item ' . $lead_split_item->getId() . ' because the delay has not been reached yet');
								}
							}

							if ($lead_split_item->getIsFulfilled()) {
								// Create the fulfillment handler
								/* @var $fulfillment \Flux\Fulfillment */
								$fulfillment = $lead_split_item->getSplit()->getSplit()->getFulfillment()->getFulfillment();

								/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
								$lead_split_attempt = new \Flux\LeadSplitAttempt();
								$lead_split_attempt->setLeadSplit($lead_split_item->getId());
								$lead_split_attempt->setFulfillment($fulfillment->getId());
								$lead_split_attempt->setAttemptTime(new \MongoDate());

								// The lead has already been fulfilled, so don't allow it to be fulfilled again
								/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
								$lead_split_attempt = new \Flux\LeadSplitAttempt();
								$lead_split_attempt->setLeadSplit($lead_split_item->getId());
								$lead_split_attempt->setFulfillment($fulfillment->getId());
								$lead_split_attempt->setAttemptTime(new \MongoDate());
								$lead_split_attempt->setIsError(false);
								$lead_split_attempt->setResponse('Already Fulfilled');
								$lead_split_item->addAttempt($lead_split_attempt);
								$lead_split_item->setDisposition(\Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED);
								$lead_split_item->setErrorMessage('Already Fulfilled');
								$lead_split_item->update();
								throw new \Exception('Cannot Fulfill Queue item ' . $lead_split_item->getId() . ' because it is already fulfilled');
							}
						} catch (\Exception $e) {
							$this->log($e->getMessage(), array($this->pid, $lead_split_item->getId()));
							// we failed basic validation, so continue to the next entry
							continue;
						}

						//
						// We made it past the validations, so attempt to fulfill the lead
						//

						$this->log('Fulfilling [' . $lead_split_item->getSplit()->getName() . ']: ' . $lead_split_item->getId(), array($this->pid, $lead_split_item->getId()));

						// Create the fulfillment handler
						/* @var $fulfillment \Flux\Fulfillment */
						$fulfillment = $lead_split_item->getSplit()->getSplit()->getFulfillment()->getFulfillment();

						/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
						$lead_split_attempt = new \Flux\LeadSplitAttempt();
						$lead_split_attempt->setLeadSplit($lead_split_item->getId());
						$lead_split_attempt->setFulfillment($fulfillment->getId());
						$lead_split_attempt->setAttemptTime(new \MongoDate());

						$results = $fulfillment->queueLead($lead_split_attempt);

						/* @var $result \Flux\LeadSplitAttempt */
						if (count($results) > 0) {
							foreach ($results as $key => $result) {
								// Save the split queue attempts back to the split queue item
								$lead_split_item->addAttempt($result);

								$lead_split_item->setDebug($result->getRequest());
								$lead_split_item->setLastAttemptTime(new \MongoDate());
								$lead_split_item->setIsProcessing(false);

								if ($result->getIsDuplicate()) {
									$lead_split_item->setIsError(true);
									$lead_split_item->setErrorMessage($result->getResponse());
									$lead_split_item->setIsFulfilled(true);
									$lead_split_item->setDisposition(\Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED);
									$lead_split_item->update();

									/* @var $report_lead \Flux\ReportLead */
									$report_lead = new \Flux\ReportLead();
									$lead = $lead_split_item->getLead()->getLead();
									$report_lead->setLead($lead->getId());
									$report_lead->setClient($lead->getTracking()->getClient()->getId());
									$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE);
									$report_lead->setRevenue(0.00);
									$report_lead->setPayout(0.00);
									$report_lead->setReportDate(new \MongoDate());
									$report_lead->insert();
									$this->log('DUPLICATE [' . $lead_split_item->getSplit()->getName() . ']: ' . $lead_split_item->getId(), array($this->pid, $lead_split_item->getId()));
								} else if ($result->getIsError()) {
									$lead_split_item->setIsError(true);
									$lead_split_item->setErrorMessage($result->getResponse());
									$lead_split_item->setIsFulfilled(false);
									$lead_split_item->setAttemptCount($lead_split_item->getAttemptCount() + 1);
									$lead_split_item->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
									$lead_split_item->setDisposition(\Flux\LeadSplit::DISPOSITION_PENDING);
									$lead_split_item->update();

									/* @var $report_lead \Flux\ReportLead */
									$report_lead = new \Flux\ReportLead();
									$lead = $lead_split_item->getLead()->getLead();
									$report_lead->setLead($lead->getId());
									$report_lead->setClient($lead->getTracking()->getClient()->getId());
									$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
									$report_lead->setRevenue(0.00);
									$report_lead->setPayout(0.00);
									$report_lead->setReportDate(new \MongoDate());
									$report_lead->insert();
									$this->log('ERROR     [' . $lead_split_item->getSplit()->getName() . ']: ' . $lead_split_item->getId() . ', ERROR: ' . $result->getResponse(), array($this->pid, $lead_split_item->getId()));
								} else {
									$lead_split_item->setIsFulfilled(true);
									$lead_split_item->setIsError(false);
									$lead_split_item->setErrorMessage('');
									$lead_split_item->setDisposition(\Flux\LeadSplit::DISPOSITION_FULFILLED);
									$lead_split_item->update();

									/* @var $lead \Flux\Lead */
									$lead = $lead_split_item->getLead()->getLead();

									// Add a fulfilled event to the lead
									if ($fulfillment->getTriggerFulfillmentFlag()) {
										$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
										$lead->update();
									}

									// Add/Update the lead reporting
									/* @var $report_lead \Flux\ReportLead */
									$report_lead = new \Flux\ReportLead();
									$report_lead->setLead($lead->getId());
									$report_lead->setClient($lead->getTracking()->getClient()->getId());
									$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
									$report_lead->setRevenue($lead_split_attempt->getBounty());
									if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
										$report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
									} else {
										$report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
									}
									$report_lead->setReportDate(new \MongoDate());
									$report_lead->setAccepted(true);
									$report_lead->insert();
									$this->log('FULFILLED [' . $lead_split_item->getSplit()->getName() . ']: ' . $lead_split_item->getId(), array($this->pid, $lead_split_item->getId()));
								}
							}
						} else {
							throw new \Exception('No results returned from fulfillment, check for fulfillment errors or network errors');
						}
					} catch (\Exception $e) {
						$this->log($e->getMessage(), array($this->pid));
						$lead_split_item->setIsError(true);
						$lead_split_item->setErrorMessage($e->getMessage());
						$lead_split_item->setIsFulfilled(false);
						$lead_split_item->setAttemptCount($lead_split_item->getAttemptCount() + 1);
						$lead_split_item->setNextAttemptTime(new \MongoDate(strtotime('now + 1 hour')));
						$lead_split_item->update();
					}

					// Unset the queue item and flag it as processed
					self::$_queue->updateMultiple(
						array('_id' => $lead_split_attempt->getId()),
						array(
							'$unset' => array(
								'__pid_fulfill' => 1
							)
						),
						array('multiple' => false, 'upsert' => false)
					);
				}
			} catch (\Exception $e) {
				$this->log('Unhandled exception threw us out of the fulfillment loop');
				$this->log($e->getMessage(), array($this->pid));
				$this->log($e->getTraceAsString(), array($this->pid));
			}
		} else {
			$this->log('No more items to process', array($this->pid));
			sleep(5);
		}
		return true;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return array
	 */
	protected function getNextItems() {
		try {
			$criteria = array(
				'next_attempt_time' => array('$lt' => new \MongoDate()),
				'is_processing' => false,
				'is_catch_all' => false,
				'disposition' => \Flux\LeadSplit::DISPOSITION_UNFULFILLED,
				'attempt_count' => array('$lte' => 5),
				'__pid_fulfill' => array('$exists' => false)
			);
			/* @var $_queue \Flux\LeadSplit */
			self::$_queue->setIgnorePagination(false);
			self::$_queue->setItemsPerPage(self::WINDOW_SIZE);
			$queue_documents = self::$_queue->queryAll($criteria, array('_id' => true), false);
			$id_array = array();
			foreach ($queue_documents as $queue_document) {
				$id_array[] = $queue_document['_id'];
			}
			// Flag the items with the current pid, so we can pull the out later
			self::$_queue->updateMultiple(array('_id' => array('$in' => $id_array)),
				array('$set' => array(
					'__pid_fulfill' => $this->pid,
					'__pid_fulfill_time' => new \MongoDate(),
					'last_process_time' => new \MongoDate(),
					'expire_at' => new \MongoDate(strtotime('now + 1 hour')),
					'is_processed' => true
				)),
				array('multiple' => true, 'upsert' => false)
			);

			// Now requery the rows that have the correct pid set
			self::$_queue->setIgnorePagination(true);
			$queue_documents = self::$_queue->queryAll(array('__pid_fulfill' => $this->pid));
			return $queue_documents;
		} catch (\Exception $e) {
			$this->log($e->getMessage(), array($this->pid));
			return array();
		}
		return array();
	}

	/**
	 * Finds the number of pending records
	 * @return integer
	 */
	protected function calculatePendingRecordCount() {
		// Count the number of pending records
		$criteria = array(
			'next_attempt_time' => array('$lt' => new \MongoDate()),
			'is_processing' => false,
			'is_catch_all' => false,
			'disposition' => \Flux\LeadSplit::DISPOSITION_UNFULFILLED,
			'attempt_count' => array('$lte' => 5),
			'__pid_fulfill' => array('$exists' => false)
		);
		$pending_records = self::$_queue->count($criteria);
		return $this->updatePendingRecordCount($pending_records);
	}
}
