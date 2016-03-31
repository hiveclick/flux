<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
use Mojavi\Action\BasicConsoleAction;
use Mojavi\Util\StringTools;
use Mojavi\View\View;
use Flux\Offer;
use Flux\Campaign;

class SyncAdwordsCampaignsAction extends BasicConsoleAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		try {
			// Compile the number of clicks per offer
			StringTools::consoleWrite('Syncing Adwords Campaigns from Google', null, StringTools::CONSOLE_COLOR_GREEN, true);
			require_once(MO_WEBAPP_DIR . "/vendor/autoload.php");
			
			$adsapi_ini_file = MO_WEBAPP_DIR . '/config/adsapi_php.ini';
			
			$oAuth2Credential = new \Google\AdsApi\Common\Util\SimpleGoogleCredential();
			$oAuth2Credential->fromFile($adsapi_ini_file);
			StringTools::consoleWrite('  Syncing Adwords Campaigns from Google', 'Authenticated', StringTools::CONSOLE_COLOR_GREEN, true);
			$builder = new \Google\AdsApi\AdWords\AdWordsSessionBuilder();
			$session = $builder->fromFile($adsapi_ini_file)
				->withOAuth2Credential($oAuth2Credential)
				->build();
			
			$adWordsServices = new \Google\AdsApi\AdWords\AdWordsServices();
			$campaignService = $adWordsServices->get($session, 'CampaignService', 'v201601', 'cm');
			StringTools::consoleWrite('  Syncing Adwords Campaigns from Google', 'Campaign Service Setup', StringTools::CONSOLE_COLOR_GREEN, true);
			// Create selector.
			$selector = new \Google\AdsApi\AdWords\v201601\cm\Selector();
			$selector->setFields(array('Id', 'Name'));
			$selector->setOrdering(array(new \Google\AdsApi\AdWords\v201601\cm\OrderBy('Name', 'ASCENDING')));
			
			// Create paging controls.
			$selector->setPaging(new \Google\AdsApi\AdWords\v201601\cm\Paging(0, 100));
			
			// Make the get request.
			$page = $campaignService->get($selector);
			if (count($page->getEntries()) > 0) {
				foreach ($page->getEntries() as $entry) {
					// See if we have an existing ad campaign
					$ad_campaign = new \Flux\AdCampaign();
					$ad_campaign->query(array('campaign_id' => $entry->getId()), false);
					if (!\MongoId::isValid($ad_campaign->getId())) {
						$ad_campaign = new \Flux\AdCampaign();
						$ad_campaign->setCampaignId($entry->getId());
						$ad_campaign->setName($entry->getName());
						$ad_campaign->setStatus($entry->getStatus());
						$ad_campaign->setStartDate($entry->getStartDate());
						$ad_campaign->setEndDate($entry->getEndDate());
						StringTools::consoleWrite('  - Adding Adwords Campaign', $ad_campaign->getName(), StringTools::CONSOLE_COLOR_GREEN, true);
							
						$ad_campaign->insert();
					} else {
						$ad_campaign->setName($entry->getName());
						$ad_campaign->setStatus($entry->getStatus());
						$ad_campaign->setStartDate($entry->getStartDate());
						$ad_campaign->setEndDate($entry->getEndDate());
						StringTools::consoleWrite('  - Updating Adwords Campaign', $ad_campaign->getName(), StringTools::CONSOLE_COLOR_GREEN, true);
							
						$ad_campaign->update();
					}
				}
			} else {
				throw new \Exception('No campaigns found ont the account');
			}
			
			
			$xml = '<reportDefinition xmlns="https://adwords.google.com/api/adwords/cm/v201601">
					  <selector>
						<fields>Date</fields>    
						<fields>CampaignName</fields>
						<fields>CampaignId</fields>
					    <fields>Impressions</fields>
					    <fields>Clicks</fields>
					    <fields>Cost</fields>
						<fields>AverageCost</fields>
					    <predicates>
					      <field>CampaignStatus</field>
					      <operator>IN</operator>
					      <values>ENABLED</values>
					      <values>PAUSED</values>
					    </predicates>
					  </selector>
					  <reportName>Month To Date Campaign Performance Report</reportName>
					  <reportType>CAMPAIGN_PERFORMANCE_REPORT</reportType>
					  <dateRangeType>THIS_MONTH</dateRangeType>
					  <downloadFormat>CSV</downloadFormat>
					</reportDefinition>';
						
			$headers = array(
				'Authorization: ' . $session->getOAuth2Credential()->formatCredentialsForHeader(),
				'developerToken: ' . $session->getDeveloperToken(),
				'clientCustomerId: ' . $session->getClientCustomerId()				
			);
			
			// gots to do this so that transfer-encoding: chunked comes through properly
			$url = 'https://adwords.google.com/api/adwords/reportdownload/v201601';
			$curl_adapter = new \Zend\Http\Client\Adapter\Curl();
			$curl_adapter->setCurlOption(CURLOPT_ENCODING , "gzip");
			$curl_adapter->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
			$curl_adapter->setCurlOption(CURLOPT_USERAGENT, 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10');
			/* @var $http_client \Zend\Http\Client */
			$http_client = new \Zend\Http\Client();
			$http_client->setAdapter($curl_adapter);
			$http_client->setUri($url);
			$http_client->setArgSeparator('&');
			$http_client->setEncType(\Zend\Http\Client::ENC_FORMDATA, '------------------------12d01fae60c7b559');
			
			/* @var $request \Zend\Http\Request */
			$request = new \Zend\Http\Request();
			$request->setUri($url);
			$request->getHeaders()->addHeaders($headers);
			$request->setMethod(\Zend\Http\Request::METHOD_POST);
			$request->getPost()->fromArray(array('__rdxml' => $xml));

			$response = $http_client->send($request);
			
			$lines = explode("\n", $response->getContent());
			foreach ($lines as $key => $line) {
				if ($key == 0) { continue; } // The first line is the report title
				if ($key == 1) { continue; } // The second line is the report headers
				if ($key == (count($lines) - 2)) { continue; } // The last line is the report footers
				if (trim($line) == '') { continue; } // Skip empty lines
				$line_parts = explode(",", $line);
				
				$ad_campaign = new \Flux\AdCampaign();
				$ad_campaign->query(array('campaign_id' => (int)trim($line_parts[2])), false);
				
				if (\MongoId::isValid($ad_campaign->getId())) {
					// We found a record, so update it's revenue for this day
					$report_ad_campaign = new \Flux\ReportAdCampaign();
					$report_ad_campaign->setAdCampaign($ad_campaign->getId());
					$report_ad_campaign->setReportDate(new \MongoDate(strtotime($line_parts['0'])));
					$report_ad_campaign->setImpressionCount($line_parts['3']);
					$report_ad_campaign->setClickCount($line_parts['4']);
					$report_ad_campaign->setCost($line_parts['5'] / 1000000);
					$report_ad_campaign->setAverageCost($line_parts['6'] / 1000000);
					\Mojavi\Util\StringTools::consoleWrite(' - Syncing ' . $ad_campaign->getName() . ' Network Revenue on ' . date('m/d/Y', $report_ad_campaign->getReportDate()->sec), '$' . number_format($report_ad_campaign->getCost(), 2, null, ','), \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
					$report_ad_campaign->updateMultiple(
						array('ad_campaign._id' => $ad_campaign->getId(), 'report_date' => $report_ad_campaign->getReportDate()),
						array(
							'$setOnInsert' => array(
								'report_date' => $report_ad_campaign->getReportDate()
							),
							'$set' => array(
								'ad_campaign' => $report_ad_campaign->getAdCampaign()->toArray(true, true, true),
								'click_count' => $report_ad_campaign->getClickCount(),
								'impression_count' => $report_ad_campaign->getImpressionCount(),
								'cost' => $report_ad_campaign->getCost(),
								'average_cost' => $report_ad_campaign->getAverageCost()
							)
						)
					);
					
					if (date('m/d/Y') == date('m/d/Y', strtotime($line_parts['0']))) {
						$ad_campaign->setDailyCost($line_parts['5'] / 1000000);
						$ad_campaign->setDailyClicks($line_parts['4']);
						$ad_campaign->setDailyAverageCost($line_parts['6'] / 1000000);
						$ad_campaign->update();
					}
				} else {
					\Mojavi\Util\StringTools::consoleWrite(' - Cannot find ad campaign for ' . $line_parts[1] . ' (' . $line_parts[2] . ')', '$0.00', \Mojavi\Util\StringTools::CONSOLE_COLOR_CYAN, true);
				}
			}		

			
			$xml = '<reportDefinition xmlns="https://adwords.google.com/api/adwords/cm/v201601">
					  <selector>
						<fields>CampaignName</fields>
						<fields>CampaignId</fields>
					    <fields>Impressions</fields>
					    <fields>Clicks</fields>
					    <fields>Cost</fields>
						<fields>AverageCost</fields>
					    <predicates>
					      <field>CampaignStatus</field>
					      <operator>IN</operator>
					      <values>ENABLED</values>
					      <values>PAUSED</values>
					    </predicates>
					  </selector>
					  <reportName>Month To Date Campaign Performance Report</reportName>
					  <reportType>CAMPAIGN_PERFORMANCE_REPORT</reportType>
					  <dateRangeType>THIS_MONTH</dateRangeType>
					  <downloadFormat>CSV</downloadFormat>
					</reportDefinition>';
			
			$headers = array(
					'Authorization: ' . $session->getOAuth2Credential()->formatCredentialsForHeader(),
					'developerToken: ' . $session->getDeveloperToken(),
					'clientCustomerId: ' . $session->getClientCustomerId()
			);
				
			// gots to do this so that transfer-encoding: chunked comes through properly
			$url = 'https://adwords.google.com/api/adwords/reportdownload/v201601';
			$curl_adapter = new \Zend\Http\Client\Adapter\Curl();
			$curl_adapter->setCurlOption(CURLOPT_ENCODING , "gzip");
			$curl_adapter->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
			$curl_adapter->setCurlOption(CURLOPT_USERAGENT, 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10');
			/* @var $http_client \Zend\Http\Client */
			$http_client = new \Zend\Http\Client();
			$http_client->setAdapter($curl_adapter);
			$http_client->setUri($url);
			$http_client->setArgSeparator('&');
			$http_client->setEncType(\Zend\Http\Client::ENC_FORMDATA, '------------------------12d01fae60c7b559');
				
			/* @var $request \Zend\Http\Request */
			$request = new \Zend\Http\Request();
			$request->setUri($url);
			$request->getHeaders()->addHeaders($headers);
			$request->setMethod(\Zend\Http\Request::METHOD_POST);
			$request->getPost()->fromArray(array('__rdxml' => $xml));
			
			$response = $http_client->send($request);
				
			$lines = explode("\n", $response->getContent());
			foreach ($lines as $key => $line) {
				if ($key == 0) { continue; } // The first line is the report title
				if ($key == 1) { continue; } // The second line is the report headers
				if ($key == (count($lines) - 2)) { continue; } // The last line is the report footers
				if (trim($line) == '') { continue; } // Skip empty lines
				$line_parts = explode(",", $line);
			
				$ad_campaign = new \Flux\AdCampaign();
				$ad_campaign->query(array('campaign_id' => (int)trim($line_parts[1])), false);
			
				if (\MongoId::isValid($ad_campaign->getId())) {
					$ad_campaign->setMtdCost($line_parts['4'] / 1000000);
					$ad_campaign->setMtdClicks($line_parts['3']);
					$ad_campaign->update();					
				}
			}
			
// 			$user = new AdWordsUser($adsapi_ini_file);
// 			StringTools::consoleWrite('  Syncing Adwords Campaigns from Google', 'Authenticated', StringTools::CONSOLE_COLOR_GREEN, true);
// 			$campaignService = $user->GetService('CampaignService', 'v201601');
// 			StringTools::consoleWrite('  Syncing Adwords Campaigns from Google', 'Campaign Service Setup', StringTools::CONSOLE_COLOR_GREEN, true);
// 			// Create selector.
// 			$selector = new Selector();
// 			$selector->fields = array('Id', 'Name');
// 			$selector->ordering[] = new OrderBy('Name', 'ASCENDING');

//   			// Create paging controls.
// 			$selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
			
// 			do {
// 				// Make the get request.
// 				$page = $campaignService->get($selector);
// 				StringTools::consoleWrite('  Syncing Adwords Campaigns from Google', 'Found Campaigns', StringTools::CONSOLE_COLOR_GREEN, true);
// 				// Display results.
// 				if (isset($page->entries)) {
// 					foreach ($page->entries as $campaign) {
// 						printf("Campaign with name '%s' and ID '%s' was found.\n", $campaign->name, $campaign->id);
// 					}
// 				} else {
// 					print "No campaigns were found.\n";
// 				}
// 				// Advance the paging index.
// 				$selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
// 			} while ($page->totalNumEntries > $selector->paging->startIndex);
			
			// Also clear out old numbers
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}
	
}