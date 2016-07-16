<?php
/**
 * ReportLead.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   7/18/16 2:34 PM
 */

namespace Flux\Network;


class ReportLead extends BaseNetwork
{

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getName() . ' Network Revenue', date('m/d/Y', strtotime($start_date)) . ' - ' . date('m/d/Y', strtotime($end_date)), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
		try {
			$report_lead = new \Flux\ReportLead();

			$start_date = date('m/d/Y 00:00:00', strtotime($start_date));
			$end_date = date('m/d/Y 23:59:59', strtotime($end_date));

			$criteria = array(
				'client._id' => array('$in' => array($this->getClient()->getId())),
				'disposition' => \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED,
				'report_date' => array('$gte' => new \MongoDate(strtotime($start_date)), '$lte' => new \MongoDate(strtotime($end_date)))
			);
			$results = $report_lead->getCollection()->aggregate(array(
				array('$match' => $criteria),
				array('$group' => array(
									'_id' => array('month' => array('$month' => '$report_date'), 'day' => array('$dayOfMonth' => '$report_date'), 'year' => array('$year' => '$report_date')),
									'count' => array('$sum' => 1),
									'revenue' => array('$sum' => '$revenue')
				))
			));

			if (isset($results['result'])) {
				if (count($results['result']) > 0) {
					foreach ($results['result'] as $result) {
						// We found a record, so update it's revenue for this day
						$report_client = new \Flux\ReportClient();
						$report_client->setClient($this->getClient()->getId());
						$report_client->setReportDate(new \MongoDate(strtotime($result['_id']['month'] . '/' . $result['_id']['day'] . '/' . $result['_id']['year'])));
						$report_client->setClickCount($result['count']);
						$report_client->setConversionCount($result['count']);
						$report_client->setRevenue(floatval($result['revenue']));
						\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getName() . ' Network Revenue on ' . date('m/d/Y', $report_client->getReportDate()->sec), '$' . number_format($report_client->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
						$report_client->updateMultiple(
							array('client._id' => $report_client->getClient()->getId(), 'report_date' => $report_client->getReportDate()),
							array(
								'$setOnInsert' => array(
									'report_date' => $report_client->getReportDate()
								),
								'$set' => array(
									'client' => $report_client->getClient()->toArray(true, true, true),
									'click_count' => $report_client->getClickCount(),
									'conversion_count' => $report_client->getConversionCount(),
									'revenue' => $report_client->getRevenue()
								)
							)
						);
					}
				} else {
					throw new \Exception('No results found for date range');
				}
			} else {
				throw new \Exception('No result element found for date range');
			}

		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncLeadRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getName() . ' Lead Revenue', 'No Need to Sync', \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
		return true;
	}
}