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
			var_dump($page);
			
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