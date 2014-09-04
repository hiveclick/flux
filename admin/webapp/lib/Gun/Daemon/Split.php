<?php

namespace Gun\Daemon;

use Gun\Split as SplitDocument;
use Gun\Lead;

class Split extends BaseDaemon
{
	public function action() {
		$split_record = $this->getNextSplit();
		if ($split_record instanceof SplitDocument) {
			//$this->log('==============================================', array($this->pid, $split_record->getId()));
			//$this->log('Processing Split: ' . $split_record->getName(), array($this->pid, $split_record->getId()));

			// Based on the split parameters, find leads that match and place them into the split collection
			$criteria = array();
			$this->log('Finding leads for ' . $split_record->getName() . ' after ' . date('m/d/Y g:i:s', $split_record->getLastRunTime()->sec));
			
			$criteria[\Gun\DataField::DATA_FIELD_EVENT_CONTAINER . '.t'] = array('$gte' => $split_record->getLastRunTime());
			
			if (count($split_record->getOfferId()) > 0) {
				$criteria[\Gun\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . \Gun\DataField::DATA_FIELD_REF_OFFER_ID . '._id'] = array('$in' => $split_record->getOfferId());
			} else if (count($split_record->getVerticalId()) > 0) {
				/* @var $offer \Gun\Offer */
				$offer = new \Gun\Offer();
				$offer->setVerticals($split_record->getVerticalId());
				$offer->setIgnorePagination(true);
				$offers = $offer->queryAll();
				$offer_ids = array();
				foreach ($offers as $offer) {
					$offer_ids[] = (int)$offer->getId();				
				}
				$criteria[\Gun\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . \Gun\DataField::DATA_FIELD_REF_OFFER_ID . '._id'] = array('$in' => $offer_ids);
			}
			
			if (count($split_record->getDomainGroupId()) > 0) {
				/* @var $domain_group \Gun\DomainGroup */
				$domains = array();
				$domain_group = new \Gun\DomainGroup();
				foreach ($split_record->getDomainGroupId() as $domain_group_id) {
					$domain_group->setId($domain_group_id);
					
					$domain_group->query();
					foreach ($domain_group->getDomains() as $domain_name) {
						$domains[] = '@' . $domain_name;
					}
				}
				if (count($domains) > 0) {
					$criteria[\Gun\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . \Gun\DataField::retrieveDataFieldFromName('email')->getKeyName()] = array('$regex' => new \MongoRegex('/(' . implode("|", $domains) . '/i)'));
				}
			}
			
			if (count($split_record->getDataFieldId()) > 0) {
				/* @var $data_field \Gun\DataField */
				$data_field = new \Gun\DataField();
				foreach ($split_record->getDataFieldId() as $data_field_id) {
					$data_field->setId($data_field_id);
					$data_field->query();
					if ($data_field->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
						$criteria[\Gun\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => true);
					} else if ($data_field->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
						$criteria[\Gun\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => true);
					} else if ($data_field->getStorageType() == \Gun\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
						$criteria[\Gun\DataField::DATA_FIELD_EVENT_CONTAINER . '.n'] = $data_field->getKeyName();
					}					
				}
			}
						
			
			
			$lead = new \Gun\Lead();
			$lead->setIgnorePagination(true);
			$matched_leads = $lead->queryAll($criteria, false);
			
			//$this->log("Criteria: " . var_export($criteria, true), array($this->pid, $split_record->getId()));
			//$this->log("Criteria: " . var_export($matched_leads, true), array($this->pid, $split_record->getId()));
						
			if ($matched_leads->hasNext()) {
				$this->log('Found ' . $matched_leads->count() . ' leads, processing...', array($this->pid, $split_record->getId()));
				
				// Save the # of leads to the split for accounting reasons
				$split_record->update(array('_id' => $split_record->retrieveValue('_id')), array('$inc' => array('queue_count' => $matched_leads->count())), array());
			}
			
			/* @var $split_queue \Gun\SplitQueue */
			$split_queue = new \Gun\SplitQueue($split_record->getId());
			foreach ($matched_leads as $key => $lead_doc) {
				$lead = new Lead();
				$lead->populate($lead_doc);
				$this->log('Lead found [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
				
				// Add the lead to the queue
				$split_queue->getCollection()->save($lead->toArray());
				
				/* @TODO optimize this by removing position 1-4 and make them dynamic */
				$lead_accepted = false;
				// Now also process rules for the real-time processing
				foreach ($split_record->getPosition1() as $split_recipient) {
					if ($split_recipient->canReceiveLead($lead)) {
						$lead_accepted = $split_recipient->sendLead($lead);
						// Lead was received, so mark it
						$this->log('Lead Accepted by Position 1 [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
						if ($lead_accepted) { break; }
					}
				}
				
				if (!$lead_accepted) {
					// Now also process rules for the real-time processing
					foreach ($split_record->getPosition2() as $split_recipient) {
						if ($split_recipient->canReceiveLead($lead)) {
							$lead_accepted = $split_recipient->sendLead($lead);
							$this->log('Lead Accepted by Position 2 [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
							// Lead was received, so mark it
							if ($lead_accepted) { break; }
						}
					}
				}
				
				if (!$lead_accepted) {
					// Now also process rules for the real-time processing
					foreach ($split_record->getPosition3() as $split_recipient) {
						if ($split_recipient->canReceiveLead($lead)) {
							$lead_accepted = $split_recipient->sendLead($lead);
							$this->log('Lead Accepted by Position 3 [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
							// Lead was received, so mark it
							if ($lead_accepted) { break; }
						}
					}
				}
				
				if (!$lead_accepted) {
					// Now also process rules for the real-time processing
					foreach ($split_record->getPosition4() as $split_recipient) {
						if ($split_recipient->canReceiveLead($lead)) {
							$lead_accepted = $split_recipient->sendLead($lead);
							$this->log('Lead Accepted by Position 4 [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
							// Lead was received, so mark it
							if ($lead_accepted) { break; }
						}
					}
				}
				
				if (!$lead_accepted) {
					// Lead was not accepted anywhere
					$this->log('Lead NOT Accepted by Any Position [' . $key . ']: ' . $lead->getId(), array($this->pid, $split_record->getId()));
					
				}
			}
			
			
			$split_record->update(array('_id' => $split_record->retrieveValue('_id')), array('$unset' => array('__pid_split' => 1), '$set' => array('last_run_time' => new \MongoDate())), array());


			//$this->log('Done Processing Split: ' . $split_record->getName(), array($this->pid, $split_record->getId()));
			return true;
		}
		$this->log('No Split to Use', array($this->pid));
		return false;
	}

	/**
	 * Finds the next split to process and returns it
	 * @return \Gun\Split
	 */
	protected function getNextSplit() {
		$splitDocument = new \Gun\Split();
		// Find active splits with no pid, set the pid, and return the split
		$split_record = $splitDocument->findAndModify(
			array(
				'status' => SplitDocument::SPLIT_STATUS_ACTIVE,
				'__pid_split' => array('$exists' => false)
			),
			array('$set' => array(
				'__pid_split' => $this->pid,
				'__pid_time_split' => new \MongoDate()
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
