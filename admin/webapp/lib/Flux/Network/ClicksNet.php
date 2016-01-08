<?php
namespace Flux\Network;

/**
 * DaoOffer_Network_Diablo contains functions used to download offers from a network and import them into the system
 * @author Mark Hobson
 */
class ClicksNet extends BaseNetwork {

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			$api_key = trim($this->getClient()->getClient()->getReportingApiToken());
			$account_id = 0;
			if (strpos($api_key, "|") !== false) {
				$affiliate_id = substr($api_key, strpos($api_key, "|") + 1);
				$api_key = substr($api_key, 0, strpos($api_key, "|"));
			}
			
			$api_url = $this->getClient()->getClient()->getReportingApiUrl();

			$params = array(
				'api_key' => $api_key,
				'affiliate_id' => $affiliate_id,
				'date_from' => $start_date,
				'date_to' => $end_date
			);
			
			\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Retreiving Stats (This may take up to 60s)', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW);
			$revenue_response = \Mojavi\Util\Ajax::sendXml('/GetSubIdStat', $params, \Mojavi\Request\Request::GET, 'https://www.clicks.net/Data/publisher/reports.asmx');

			\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Compiling Stats', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW);
			$network_revenue = array();

			foreach ($revenue_response->DataItem as $revenue_entry) {
				$revenue_date = date('Y-m-d', strtotime($revenue_entry->Date));
				if (isset($network_revenue[$revenue_date])) {
					$network_revenue[$revenue_date]['report_date'] = $revenue_date;
					$network_revenue[$revenue_date]['clicks'] += intval($revenue_entry->Impressions);
					$network_revenue[$revenue_date]['actions'] += intval($revenue_entry->Clicks);
					$network_revenue[$revenue_date]['revenue'] += (intval($revenue_entry->Clicks) * floatval(str_replace("$", "", $revenue_entry->EPC)));
				} else {
					$network_revenue[$revenue_date] = array(
						'report_date' => $revenue_date,
						'clicks' => intval($revenue_entry->Impressions),
						'actions' => intval($revenue_entry->Clicks),
						'revenue' => (intval($revenue_entry->Clicks) * floatval(str_replace("$", "", $revenue_entry->EPC)))
					);
				}
			}
						
			// We found a record, so update it's revenue for this day
			foreach ($network_revenue as $date_key => $entry) {
				$report_client = new \Flux\ReportClient();
				$report_client->setClient($this->getClient()->getId());
				$report_client->setReportDate(new \MongoDate(strtotime($date_key)));
				$report_client->setClickCount($entry['clicks']);
				$report_client->setConversionCount($entry['actions']);
				$report_client->setRevenue(floatval($entry['revenue']));
				\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getName() . ' Network Revenue on ' . date('m/d/Y', $report_client->getReportDate()->sec), '$' . number_format($report_client->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
				$report_client->updateMultiple(
					array('client.client_id' => $report_client->getClient()->getId(), 'report_date' => $report_client->getReportDate()),
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
			
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}
}
?>