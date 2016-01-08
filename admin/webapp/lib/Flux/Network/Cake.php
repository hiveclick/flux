<?php
namespace Flux\Network;

class Cake extends BaseNetwork {
	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			$start_date = date('Y-m-d', strtotime($start_date));
			$end_date = date('Y-m-d', strtotime($end_date));
			
			$api_token_array = explode('|', trim($this->getClient()->getClient()->getReportingApiToken()));
			$api_key = $api_token_array[0];
			$affiliate_id = $api_token_array[1];
			$params = array('api_key' => $api_key,
				'affiliate_id' => $affiliate_id,
				'output' => 'json',
				'start_date' => $start_date,
				'end_date' => date('Y-m-d', strtotime($end_date . ' + 1 day')),
				'offer_id' => 0,
				'start_at_row' => 1,
				'row_limit' => 0
			);
						
			$api_url = $this->getClient()->getClient()->getReportingApiUrl();
			
			$revenue_response = \Mojavi\Util\Ajax::sendXml('/affiliates/api/2/reports.asmx/DailySummary', $params, \Mojavi\Request\Request::GET, $api_url);
			$revenue_response = json_decode(json_encode((array)$revenue_response), true);
			if ($revenue_response['success'] == 'true') {
				if (isset($revenue_response['days']['day'])) {
					if (is_array($revenue_response['days']['day'])) {
						foreach ($revenue_response['days']['day'] as $revenue_entry) {
							// We found a record, so update it's revenue for this day
							$report_client = new \Flux\ReportClient();
							$report_client->setClient($this->getClient()->getId());
							$report_client->setReportDate(new \MongoDate(strtotime($revenue_entry['date'])));
							$report_client->setClickCount($revenue_entry['clicks']);
							$report_client->setConversionCount($revenue_entry['conversions']);
							$report_client->setRevenue(floatval($revenue_entry['revenue']));
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
					} else {
						throw new \Exception('Day element in response is not an array: ' . var_export($revenue_response, true));
					}
				} else {
					throw new \Exception('Cannot find days element in response: ' . var_export($revenue_response, true));
				}
			} else {
				throw new \Exception('Cannot find success element in response: ' . var_export($revenue_response, true));
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
		\Mojavi\Util\StringTools::consoleWrite('Syncing lead revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			$current_date = $start_date;
			while (strtotime($current_date) <= strtotime($end_date)) {
			
				$api_token_array = explode('|', trim($this->getClient()->getClient()->getReportingApiToken()));
				$api_key = $api_token_array[0];
				$affiliate_id = $api_token_array[1];
				$params = array('api_key' => $api_key,
					'affiliate_id' => $affiliate_id,
					'output' => 'json',
					'start_date' => $current_date,
					'end_date' => date('Y-m-d', strtotime($current_date . ' + 1 day')),
					'offer_id' => 0,
					'start_at_row' => 1,
					'row_limit' => 0
				);
										
				$api_url = $this->getClient()->getClient()->getReportingApiUrl();
								
				$revenue_response = \Mojavi\Util\Ajax::sendXml('/affiliates/api/2/reports.asmx/SubAffiliateSummary', $params, \Mojavi\Request\Request::GET, $api_url);
				
				$revenue_response = json_decode(json_encode((array)$revenue_response), true);
				
				if ($revenue_response['success'] == 'true') {
					if (isset($revenue_response['sub_affiliates']['sub_affiliate']) && is_array($revenue_response['sub_affiliates']['sub_affiliate'])) {
						if (!isset($revenue_response['sub_affiliates']['sub_affiliate'][0])) {
							$revenue_entry_array = array(0 => $revenue_response['sub_affiliates']['sub_affiliate']);
						} else {
							$revenue_entry_array = $revenue_response['sub_affiliates']['sub_affiliate'];
						}
						if (is_array($revenue_entry_array)) {
							foreach ($revenue_entry_array as $revenue_entry) {
								if (is_array($revenue_entry)) {
									if (is_array($revenue_entry['sub_id'])) {
										$revenue_entry['sub_id'] = '';
									}
									if (strpos((string)$revenue_entry['sub_id'], "|") !== false) {
										$transaction_id = substr((string)$revenue_entry['sub_id'], 0, strpos((string)$revenue_entry['sub_id'], "|") - 1);
									} else {
										$transaction_id = (string)$revenue_entry['sub_id'];
									}
									
									if (\MongoId::isValid(trim($transaction_id))) {
										// We found a record, so update it's revenue for this day
										$lead_split = new \Flux\LeadSplit();
										$lead_split->setId($transaction_id);
										$lead_split->query();
										if (\MongoId::isValid($lead_split->getId())) {
											$lead_split->setIsConfirmed(true);
											$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_CONFIRMED);
											$lead_split->setBounty(floatval($revenue_entry["revenue"]));
											$lead_split->setConfirmedNote('Split confirmed by Cake sync on ' . date('m/d/Y'));
											$lead_split->update();
											
											/* @var $report_lead \Flux\ReportLead */
											$report_lead = new \Flux\ReportLead();
											$report_lead->setLead($lead_split->getLead()->getLeadId());
											$report_lead->setClient($this->getClient()->getId());
											$report_lead->setReportDate(new \MongoDate(strtotime($current_date)));
											$report_lead->setRevenue(floatval($revenue_entry["revenue"]));
											$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
											$report_lead->setAccepted(true);
											\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $report_lead->getLead()->getLeadName() . ' Revenue on ' . date('m/d/Y', $report_lead->getReportDate()->sec), '$' . number_format($report_lead->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
											$report_lead->updateMultiple(
												array('lead.lead_id' => $report_lead->getLead()->getLeadId(), 'report_date' => $report_lead->getReportDate()),
												array(
													'$setOnInsert' => array(
														'report_date' => $report_lead->getReportDate(),
														'client' => $report_lead->getClient()->toArray(true, true, true)
													),
													'$set' => array(
														'lead' => $report_lead->getLead()->toArray(true, true, true),
														'accepted' => $report_lead->getAccepted(),
														'disposition' => $report_lead->getDisposition(),
														'revenue' => $report_lead->getRevenue()
													)
												)
											);
										}
									}
								}
							}
						}
					}
				} else {
					throw new \Exception('Cannot find success element in response: ' . var_export($revenue_response, true));
				}
				$current_date = date('m/d/Y', strtotime($current_date . ' + 1 days'));
			}
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}
}