<?php
namespace Flux\Network;

/**
 * DaoOffer_Network_Diablo contains functions used to download offers from a network and import them into the system
 * @author Mark Hobson
 */
class Diablo extends BaseNetwork {

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncLeadRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite('Syncing lead revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			$api_key = trim($this->getClient()->getClient()->getReportingApiToken());
			$api_url = $this->getClient()->getClient()->getReportingApiUrl();
			if (strpos($api_url, '/reports/') !== false) {
				$api_url = substr($api_url, 0, strpos($api_url, '/reports/'));
				$api_url .= '/reports/transaction/start/#start_date#/end/#end_date#/';
			}

			$params = array(
				'key' => $api_key,
				'output' => 'json'
			);

			/* @var $lead \Flux\Lead */
			$offer_model = new \Flux\Lead();

			$current_date = $start_date;
			while (strtotime($current_date) <= strtotime($end_date)) {
				$new_api_url = str_replace("#start_date#", date('Y-m-d', strtotime($current_date)), $api_url);
				$new_api_url = str_replace("#end_date#", date('Y-m-d', strtotime($current_date)), $new_api_url);

				$revenue_response = \Mojavi\Util\Ajax::sendAjax('/', $params, \Mojavi\Request\Request::GET, $new_api_url);

				$offer_revenue = array();
				if (isset($revenue_response['data']) && is_array($revenue_response['data'])) {
					// Store any offers not found into a separate array
					$not_found_data = array('revenue' => 0, 'clicks' => 0, 'sales' => 0);
					foreach ($revenue_response['data'] as $revenue_entry) {
						$transaction_id = $revenue_entry["trxid"];
						if (trim($transaction_id) != '') {
    						/* @var $report_lead \Flux\ReportLead */
    						$report_lead = new \Flux\ReportLead();
    						$report_lead->setLead(trim($transaction_id));
    						$report_lead->setReportDate(new \MongoDate(strtotime($revenue_entry["date"])));
    						$report_lead->setRevenue(floatval($revenue_entry["revenue"]));
    						$report_lead->setDisposition('Accepted');
    						$report_lead->setAccepted(true);
    					    
    						\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getLead()->getLeadName() . ' Revenue on ' . date('m/d/Y', $report_lead->getReportDate()->sec), '$' . number_format($report_lead->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
    						$report_lead->updateMultiple(
    						    array('lead.lead_id' => $report_lead->getLead()->getLeadId(), 'report_date' => $report_lead->getReportDate()),
    						    array(
    						        '$setOnInsert' => array(
    						            'report_date' => $report_lead->getReportDate()
    						        ),
    						        '$set' => array(
    						            'lead' => $report_lead->getLead()->toArray(),
    						            'accepted' => $report_lead->getAccepted(),
    						            'disposition' => $report_lead->getDisposition(),
    						            'revenue' => $report_lead->getRevenue()
    						        )
    						    )
    						);
						} else {
						    \Mojavi\Util\StringTools::consoleWrite(' - Missing TRX ID on ' . date('m/d/Y', strtotime($revenue_entry["date"])), '$' . number_format($revenue_entry["revenue"], 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
						}
					}
				}
				$current_date = date('m/d/Y', strtotime($current_date . ' + 1 days'));
			}
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('Error syncing lead revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			$api_key = trim($this->getClient()->getClient()->getReportingApiToken());
			$api_url = $this->getClient()->getClient()->getReportingApiUrl();
			if (strpos($api_url, '/reports/') !== false) {
				$api_url = substr($api_url, 0, strpos($api_url, '/reports/'));
				$api_url .= '/reports/subid/start/#start_date#/end/#end_date#/';
			}

			$params = array(
				'key' => $api_key,
				'output' => 'json'
			);

			$current_date = $start_date;
			while (strtotime($current_date) <= strtotime($end_date)) {
				$new_api_url = str_replace("#start_date#", date('Y-m-d', strtotime($current_date)), $api_url);
				$new_api_url = str_replace("#end_date#", date('Y-m-d', strtotime($current_date)), $new_api_url);

				$revenue_response = \Mojavi\Util\Ajax::sendAjax('/', $params, \Mojavi\Request\Request::GET, $new_api_url);

				$network_revenue = array('clicks' => 0, 'actions' => 0, 'revenue' => 0);
				if (isset($revenue_response['data']) && is_array($revenue_response['data'])) {
					foreach ($revenue_response['data'] as $revenue_entry) {
						$network_revenue['clicks'] += $revenue_entry['clicks'];
						$network_revenue['actions'] += $revenue_entry['leads'];
						$network_revenue['revenue'] += $revenue_entry['revenue'];
					}
				}
				// We found a record, so update it's revenue for this day
				$report_client = new \Flux\ReportClient();
				$report_client->setClient($this->getClient()->getClientId());
				$report_client->setReportDate(new \MongoDate(strtotime($current_date)));
				$report_client->setClickCount($network_revenue['clicks']);
				$report_client->setConversionCount($network_revenue['actions']);
				$report_client->setRevenue(floatval($network_revenue['revenue']));
				\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getClientName() . ' Network Revenue on ' . date('m/d/Y', $report_client->getReportDate()->sec), '$' . number_format($report_client->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
				$report_client->updateMultiple(
				    array('client.client_id' => $report_client->getClient()->getClientId(), 'report_date' => $report_client->getReportDate()),
				    array(
				        '$setOnInsert' => array(
				            'report_date' => $report_client->getReportDate()
				        ),
				        '$set' => array(
				            'client' => $report_client->getClient()->toArray(),
				            'click_count' => $report_client->getClickCount(),
				            'conversion_count' => $report_client->getConversionCount(),
				            'revenue' => $report_client->getRevenue()
				        )
				    )  
				);

				$current_date = date('m/d/Y', strtotime($current_date . ' + 1 days'));
			}
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}
}
?>
