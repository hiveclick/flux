<?php
namespace Flux\Network;

/**
 * Evo contains functions used to download offers from a network and import them into the system
 * @author Mark Hobson
 */
class Evo extends BaseNetwork {
    
    /**
     * Syncs revenue with this network
     * @return boolean
     */
    function syncLeadRevenue($start_date, $end_date) {
        \Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
        try {
            $api_key = trim($this->getClient()->getClient()->getReportingApiToken());
            $api_url = $this->getClient()->getClient()->getReportingApiUrl();
            
            $params = array(
                'apk' => $api_key,
                'type' => 'clickreport',
                'format' => 'xml',
                'start' => date('m/d/Y', strtotime($start_date)),
                'end' => date('m/d/Y', strtotime($end_date))
            );
    
            $params['start'] = date('m/d/Y', strtotime($start_date));
            $params['end'] = date('m/d/Y', strtotime($end_date));
            
            $revenue_response = \Mojavi\Util\Ajax::sendXml('/', $params, \Mojavi\Request\Request::GET, $api_url);
            foreach ($revenue_response->click as $click_obj) {
                // Store any offers not found into a separate array
                $transaction_id = (string)$click_obj->SA0;
                if (trim($transaction_id) != '' && \MongoId::isValid(trim($transaction_id))) {
                    /* @var $report_lead \Flux\ReportLead */
                    $report_lead = new \Flux\ReportLead();
                    $report_lead->setLead(trim($transaction_id));
                    $report_lead->setClient($this->getClient()->getClientId());
                    $report_lead->setReportDate(new \MongoDate(strtotime($click_obj->TimeConversion)));
                    $report_lead->setRevenue(floatval($click_obj->SaleAmount));
                    $report_lead->setDisposition('Accepted');
                    $report_lead->setAccepted(true);
                    	
                    \Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $report_lead->getLead()->getLeadName() . ' Revenue on ' . date('m/d/Y', $report_lead->getReportDate()->sec), '$' . number_format($report_lead->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
                    $report_lead->updateMultiple(
                        array('lead.lead_id' => $report_lead->getLead()->getLeadId(), 'report_date' => $report_lead->getReportDate()),
                        array(
                            '$setOnInsert' => array(
                                'report_date' => $report_lead->getReportDate(),
                                'client' => $report_lead->getClienet()->getArray(true, true, true)
                            ),
                            '$set' => array(
                                'lead' => $report_lead->getLead()->toArray(true, true, true),
                                'accepted' => $report_lead->getAccepted(),
                                'disposition' => $report_lead->getDisposition(),
                                'revenue' => $report_lead->getRevenue()
                            )
                        )
                    );
                } else {
                    \Mojavi\Util\StringTools::consoleWrite(' - Missing TRX ID on ' . date('m/d/Y', strtotime($click_obj->TimeIn)) . ((string)$click_obj->TransactionID != '' ? ' (' . (string)$click_obj->TransactionID . ')' : ''), '$' . number_format((float)$click_obj->SaleAmount, 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
                }
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
            
            $params = array(
                'apk' => $api_key,
                'type' => 'clickreport',
                'format' => 'xml',
                'start' => date('m/d/Y', strtotime($start_date)),
                'end' => date('m/d/Y', strtotime($end_date))
            );
    
            $params['start'] = date('m/d/Y', strtotime($start_date));
            $params['end'] = date('m/d/Y', strtotime($end_date));
            
            $revenue_response = \Mojavi\Util\Ajax::sendXml('/', $params, \Mojavi\Request\Request::GET, $api_url);
            
            $network_revenue = array();
            foreach ($revenue_response->click as $click_obj) {
                if ((string)$click_obj->TimeConversion != '') {
                    $click_date = date('m/d/Y', strtotime($click_obj->TimeConversion));
                    if (isset($network_revenue[$click_date])) {
                        $network_revenue[$click_date]['clicks']++;
                        $network_revenue[$click_date]['actions']++;
                        $network_revenue[$click_date]['revenue'] += (float)$click_obj->SaleAmount;
                    } else {
                        $network_revenue[$click_date] = array('report_date' => $click_date, 'clicks' => 1, 'actions' => 1, 'revenue' => (float)$click_obj->SaleAmount);
                    }
                } else {
                    $click_date = date('m/d/Y', strtotime($click_obj->TimeIn));
                    if (isset($network_revenue[$click_date])) {
                        $network_revenue[$click_date]['clicks']++;
                    } else {
                        $network_revenue[$click_date] = array('report_date' => $click_date, 'clicks' => 1, 'actions' => 0, 'revenue' => 0.00);
                    }
                }
            }

            foreach ($network_revenue as $entry) {
                // We found a record, so update it's revenue for this day
                $report_client = new \Flux\ReportClient();
                $report_client->setClient($this->getClient()->getClientId());
                $report_client->setReportDate(new \MongoDate(strtotime($entry['report_date'])));
                $report_client->setClickCount($entry['clicks']);
                $report_client->setConversionCount($entry['actions']);
                $report_client->setRevenue(floatval($entry['revenue']));
                \Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $this->getClient()->getClientName() . ' Network Revenue on ' . date('m/d/Y', $report_client->getReportDate()->sec), '$' . number_format($report_client->getRevenue(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
                $report_client->updateMultiple(
                    array('client.client_id' => $report_client->getClient()->getClientId(), 'report_date' => $report_client->getReportDate()),
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