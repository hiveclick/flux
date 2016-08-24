<?php
namespace Flux\Daemon;

class Pingback extends BaseDaemon
{
	const WINDOW_SIZE = 5;

	private static $_queue;

	public function action() {
		// Instantiate our static queue model
		self::$_queue = new \Flux\PingbackKeywordQueue();

		// If this is the primary thread, then run some cleanup/statistics
		if ($this->getPrimaryThread()) {
			$this->calculatePendingRecordCount();
			// Only return if there are other threads to handle the processes
			if ($this->getDaemon()->getThreads() > 1) { return true; }
		}

		$queue_items = $this->getNextItems();
		if (count($queue_items) > 0) {
			/* @var $queue_item \Flux\PingbackKeywordQueue */
			foreach ($queue_items as $queue_item) {
				try {
					// Process the queue item
					$pingback = $queue_item->getPingback()->getPingback();
					$this->log('Pingback for ' . $queue_item->getUrl() . ' to ' . $pingback->getUrl(), array($this->pid, $queue_item->getId()));

					$xml = array($queue_item->getUrl(), $pingback->getUrl() . '?' . urlencode($queue_item->getKeyword()));
					$request = xmlrpc_encode_request("pingback.ping", $xml);
					//$request = xmlrpc_encode_request("demo.sayHello", "");

					$xmlresponse = $pingback->sendXmlRpc($pingback->getRpcUrl(), $request);
					$response = xmlrpc_decode($xmlresponse);

					if (is_array($response) && isset($response['faultString'])) {
						$fault_code = isset($response['faultCode']) ? $response['faultCode'] : 0;
						$fault_string = isset($response['faultString']) ? $response['faultString'] : 0;
						if (strpos($fault_string, 'You are posting comments too quickly') !== false) {
							// We are posting too fast, so delay this entry for 1 minute
							throw new \Exception($fault_string);
						} else {
							throw new \Exception($fault_code . ' :: ' . $fault_string);
						}
					} else if (is_string($response)) {
						$this->log('Pingback response: ' . $response, array($this->pid, $queue_item->getId()));
					}

					// Unset the queue item and flag it as processed
					self::$_queue->updateMultiple(
						array('_id' => $queue_item->getId()),
						array(
							'$unset' => array(
								'__pid_pingback' => 1
							),
							'$set' => array(
								'expire_at' => new \MongoDate(strtotime('now + 5 minutes'))
							)
						),
						array('multiple' => false, 'upsert' => false)
					);
				} catch (\Exception $e) {
					$this->log('Error ' . $e->getMessage(), array($this->pid, $queue_item->getId()));
					// Unset the queue item and flag it as processed
					self::$_queue->updateMultiple(
						array('_id' => $queue_item->getId()),
						array(
							'$unset' => array(
								'__pid_pingback' => 1
							),
							'$set' => array(
								'error_message' => $e->getMessage(),
								'is_error' => true,
								'is_processed' => false,
								'next_pingback_time' => new \MongoDate(strtotime('now + ' . ((int)$queue_item->getAttempts()^((int)$queue_item->getAttempts() + 1)) . ' minutes')) // increment the next attempt time from 1^2 = 2 minute, 2^3 = 8 minutes, 3^4 = 27 minutes, , 4^5 = 17 hours
							)
						),
						array('multiple' => false, 'upsert' => false)
					);
				}


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
				'is_processed' => false,
				'attempts' => array('$lt' => 5),
				'next_pingback_time' => array('$lt' => new \MongoDate()),
				'__pid_pingback' => array('$exists' => false)
			);
			/* @var $_record_queue \Flux\RecordQueue */
			self::$_queue->setIgnorePagination(false);
			self::$_queue->setItemsPerPage(self::WINDOW_SIZE);
			$queue_documents = self::$_queue->queryAll($criteria, array('_id' => true), false)->sort(array('next_pingback_time' => 1));
			$id_array = array();
			foreach ($queue_documents as $queue_document) {
				$id_array[] = $queue_document['_id'];
			}
			// Flag the items with the current pid, so we can pull the out later
			self::$_queue->updateMultiple(array('_id' => array('$in' => $id_array)),
				array(
					'$set' => array(
						'__pid_pingback' => $this->pid,
						'__pid_pingback_time' => new \MongoDate(),
						'last_process_time' => new \MongoDate(),
						'expire_at' => new \MongoDate(strtotime('now + 1 hour')),
						'is_processed' => true
					),
					'$inc' => array(
						'attempts' => 1
					)
				),
				array('multiple' => true, 'upsert' => false)
			);

			// Now requery the rows that have the correct pid set
			self::$_queue->setIgnorePagination(true);
			$queue_documents = self::$_queue->queryAll(array('__pid_pingback' => $this->pid));
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
			'is_processed' => false
		);
		$pending_records = self::$_queue->count($criteria);
		return $this->updatePendingRecordCount($pending_records);
	}
}
