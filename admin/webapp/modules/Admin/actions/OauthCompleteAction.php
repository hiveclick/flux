<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OauthCompleteAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		if (isset($_REQUEST['code'])) {
			// Save the code into the preferences
			try {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $_REQUEST['code']);
				require_once(MO_WEBAPP_DIR . "/vendor/autoload.php");
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Id: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'));
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Secret: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET'));
				
				
				$simple_google_creds = new \Google\AdsApi\Common\Util\SimpleGoogleCredential();
				$simple_google_creds->setCredentials(array(
						'client_id' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'),
						'client_secret' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET'),
				));
				$redirectUri = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/oauth-complete';
				$token = $simple_google_creds->getAccessToken($_REQUEST['code'], null);
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: getAccessToken: " . var_export($token, true));
				
				\Flux\Preferences::savePreference('GOOGLE_OAUTH_TOKEN', $_REQUEST['code']);
			} catch (\Exception $e) {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getTraceAsString());
			}
			
			try {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $_REQUEST['code']);
				require_once(MO_WEBAPP_DIR . "/vendor/autoload.php");
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Id: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'));
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Secret: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET'));
			
			
				$simple_google_creds = new \Google\AdsApi\Common\Util\SimpleGoogleCredential();
				$simple_google_creds->setCredentials(array(
						'client_id' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'),
						'client_secret' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET'),
						'refresh_token' => $_REQUEST['code']
				));
				$token = $simple_google_creds->getAccessToken($_REQUEST['code'], null);
			
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: getAccessToken: " . var_export($token, true));
			
				\Flux\Preferences::savePreference('GOOGLE_OAUTH_TOKEN', $_REQUEST['code']);
			} catch (\Exception $e) {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
			}
		}		
		
		return View::SUCCESS;
	}
}

?>