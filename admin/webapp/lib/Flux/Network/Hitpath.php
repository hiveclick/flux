<?php
namespace Flux\Network;

/**
 * DaoOffer_Network_Diablo contains functions used to download offers from a network and import them into the system
 * @author Mark Hobson
 */
class Hitpath extends BaseNetwork {

    private $revenue_report_data;
    
	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncLeadRevenue($start_date, $end_date) {		
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing lead revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
		    if (count($this->getRevenueReportData()) == 0) {
		        if ($this->compileReportData($start_date, $end_date) === false) {
		            \Mojavi\Util\StringTools::consoleWrite("Error compiling report data", 'Error Compiling Data', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		        }
		    }
		
		    $network_report_data = array();
		    foreach ($this->getRevenueReportData() as $click) {
		        $sub_id = (string)$click->c1[0];
		        #$campaign_id = (string)$click->id[0];
		        $start_date = date('m/d/Y', strtotime((string)$click->time[0]));
		        if (trim($start_date) == '') {
		            $start_date = date('m/d/Y');
		        }
		        // Save the network data
		        if (!isset($network_report_data[$start_date])) {
		            $network_report_data[$start_date] = array();
		        }
		        if (!isset($network_report_data[$start_date][$sub_id])) {
		            $network_report_data[$start_date][$sub_id] = array('clicks' => 0, 'sales' => 0, 'revenue' => 0);
		        }
		        $network_report_data[$start_date][$sub_id]['clicks'] += 1;
		        if ((float)$click->amount[0] > 0) {
		            $network_report_data[$start_date][$sub_id]['sales'] += 1;
		        }
		        $network_report_data[$start_date][$sub_id]['revenue'] += (float)$click->amount[0];
		    }
		    // Sync network data also since hitpath only allows us to pull data once every hour and we can update both offer, network, and ttd at the same time
		    foreach ($network_report_data as $start_date => $sub_id_data) {
		        foreach ($sub_id_data as $transaction_id => $revenue_data) {
		            if (\MongoId::isValid(trim($transaction_id))) {
        		        // We found a record, so update it's revenue for this day
        		        $report_lead = new \Flux\ReportLead();
        		        $report_lead->setClient($this->getClient()->getClientId());
        		        $report_lead->setLead(trim($transaction_id));
        		        $report_lead->setReportDate(new \MongoDate(strtotime($start_date)));
                        $report_lead->setRevenue(floatval($revenue_data["revenue"]));
                        if (floatval($revenue_data["revenue"]) > 0) {
                            $report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
                            $report_lead->setAccepted(true);
                        } else {
                            $report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
                            $report_lead->setAccepted(true);
                        }
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
		} catch (\Exception $e) {
		    \Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
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
		    if (count($this->getRevenueReportData()) == 0) {
		        if ($this->compileReportData($start_date, $end_date) === false) {
		            \Mojavi\Util\StringTools::consoleWrite("Error compiling report data", 'Error Compiling Data', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		        }
		    }
		
		    $network_report_data = array();
		    foreach ($this->getRevenueReportData() as $click) {
		        $sub_id = (string)$click->c2[0];
		        #$campaign_id = (string)$click->id[0];
		        $start_date = date('m/d/Y', strtotime((string)$click->time[0]));
		        if (trim($start_date) == '') {
		            $start_date = date('m/d/Y');
		        }
		        // Save the network data
		        if (!isset($network_report_data[$start_date])) {
		            $network_report_data[$start_date] = array('clicks' => 0, 'sales' => 0, 'revenue' => 0);
		        }
		        $network_report_data[$start_date]['clicks'] += 1;
		        if ((float)$click->amount[0] > 0) {
		            $network_report_data[$start_date]['sales'] += 1;
		        }
		        $network_report_data[$start_date]['revenue'] += (float)$click->amount[0];
		    }
		    // Sync network data also since hitpath only allows us to pull data once every hour and we can update both offer, network, and ttd at the same time
		    foreach ($network_report_data as $start_date => $revenue_data) {
		        // We found a record, so update it's revenue for this day
		        $report_client = new \Flux\ReportClient();
		        $report_client->setClient($this->getClient()->getClientId());
		        $report_client->setReportDate(new \MongoDate(strtotime($start_date)));
		        $report_client->setClickCount($revenue_data['clicks']);
		        $report_client->setConversionCount($revenue_data['sales']);
		        $report_client->setRevenue(floatval($revenue_data['revenue']));
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
	
	/**
	 * Returns the revenue_report_data
	 * @return array
	 */
	function getRevenueReportData() {
	    if (is_null($this->revenue_report_data)) {
	        $this->revenue_report_data = array();
	    }
	    return $this->revenue_report_data;
	}
	
	/**
	 * Sets the revenue_report_data
	 * @param $arg0 array
	 */
	function setRevenueReportData($arg0) {
	    $this->revenue_report_data = $arg0;
	    return $this;
	}
	
	/**
	 * Compiles the report data
	 * @param $start_date
	 * @param $end_date
	 * @return boolean
	 */
	function compileReportData($start_date, $end_date) {
	    // Reformat the start and end date since HitPath requires a specific format
	    $start_date = date('m/d/Y', strtotime($start_date));
	    $end_date = date('m/d/Y', strtotime($end_date));
	
	    $api_key = $this->getClient()->getClient()->getReportingApiToken();
	
	    $hitpath_cache_file = '/tmp/hitpath_report_' . $this->getClient()->getClientId() . '.xml';
	
	    if (file_exists($hitpath_cache_file)) {
	        if (time() - filemtime($hitpath_cache_file) > 3600) {
	            unlink($hitpath_cache_file);
	        } else {
	            \Mojavi\Util\StringTools::consoleWrite(' - Using cached file', (3600 - (time() - filemtime($hitpath_cache_file))) . ' seconds remaining', \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
	        }
	    }
	
	    if (!file_exists($hitpath_cache_file)) {
	        $params = array('key' => $api_key,
	            'type' => 'clicks', // clicks, sales, test
	            'start' => $start_date,
	            'end' => $end_date,
	            'format' => 'xml',
	            'nozip' => '1');
	        $response = \Mojavi\Util\Ajax::sendRaw('/api.php', $params, \Mojavi\Request\Request::POST, $this->getClient()->getClient()->getReportingApiUrl());
	        $retry_count = 0;
	        while (strpos($response, 'Reports are available every') !== false) {
	            // Wait for 5 minutes before trying again
	            for ($i=5;$i>0;$i--) {
	                \Mojavi\Util\StringTools::consoleWrite(' - Too many IP requests', 'Waiting for ' . $i . ' minutes', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
	                sleep(60);
	            }
	            $retry_count++;
	            if ($retry_count >= 3) {
	                throw new \Exception('We tried 3 times, but got "Too many IP requests", please try again later');
	            }
	            $response = \Mojavi\Util\Ajax::sendRaw('/api.php', $params, \Mojavi\Request\Request::POST, $this->getClient()->getClient()->getReportingApiUrl());
	        }
	        file_put_contents($hitpath_cache_file, $response);
	    }
	
	    if (filesize($hitpath_cache_file) > 0) {
    	    $response_obj = simplexml_load_file($hitpath_cache_file);
    	    $this->setRevenueReportData($response_obj);
	    }
	    return true;
	}
}
?>
