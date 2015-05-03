<?php
namespace Flux\Network;

/**
 * DaoOffer_Network_Diablo contains functions used to download offers from a network and import them into the system
 * @author Mark Hobson
 */
class Avid extends BaseNetwork {

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncLeadRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite('Syncing lead revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		return true;
	}

	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
		\Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Logging In', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		try {
			// Setup Curl for this request
		    $ch = curl_init();
		    
		    $headers = array();
		    $headers[] = 'Accept-Encoding: gzip,deflate';
		    
		    $cookie_jar = tempnam('/tmp','cookie');
		    $params = array('username' => $this->getClient()->getClient()->getNetworkUsername(), 'password' => $this->getClient()->getClient()->getNetworkPassword());
		    curl_setopt($ch, CURLOPT_URL, $this->getClient()->getClient()->getNetworkUrl());
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		    curl_setopt($ch, CURLOPT_SSLVERSION, 0);
		    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		    
		    $login_response = curl_exec($ch);

			$current_date = $start_date;
			while (strtotime($current_date) <= strtotime($end_date)) {
			    $params = array('start_date' => date('Y-m-d', strtotime($current_date)), 'end_date' => date('Y-m-d', strtotime($current_date)), 'campaign_id' => 0);
			    curl_setopt($ch, CURLOPT_URL, $this->getClient()->getClient()->getReportingApiUrl());
			    curl_setopt($ch, CURLOPT_POST, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			    $report_response = curl_exec($ch);
			    
			    if (strpos($report_response, '<table class="table_main">') !== false) {
    			    $report_table = \Mojavi\Util\StringTools::getStringBetween($report_response, '<table class="table_main">', '</table>');
    			    $report_table = substr($report_table, strpos($report_table, '<td colspan="5">Total</td>'));
    			    // Replace the total and <td> fields
    			    $report_table = str_replace('<td colspan="5">Total</td>', '', $report_table);
    			    $report_table = str_replace('<td align="right">', '', $report_table);
    			    $report_table = str_replace("\n", '', $report_table);
    			    $report_table = str_replace("\r", '', $report_table);
    			    $report_table = str_replace("\t", '', $report_table);
    			    $report_table = str_replace(' ', '', $report_table);
    			    $report_table = str_replace('$', '', $report_table);
    			    $report_table = str_replace('</td>', ',', $report_table);
    			    $report_data = explode(",", $report_table);            
    				
    				// We found a record, so update it's revenue for this day
    				$report_client = new \Flux\ReportClient();
    				$report_client->setClient($this->getClient()->getClientId());
    				$report_client->setReportDate(new \MongoDate(strtotime($current_date)));
    				$report_client->setClickCount($report_data[0]);
    				$report_client->setConversionCount($report_data[1]);
    				$report_client->setRevenue(floatval($report_data[2]));
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
			    } else {
			        throw new \Exception('Missing required html element "table_main", check that this is an Avid account');
			    }
				

				$current_date = date('m/d/Y', strtotime($current_date . ' + 1 days'));
			}
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite(' - Error syncing network revenue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		return true;
	}
}
?>
