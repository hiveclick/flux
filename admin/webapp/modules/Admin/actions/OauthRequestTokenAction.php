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
class OauthRequestTokenAction extends BasicAction
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
		require_once(MO_WEBAPP_DIR . "/vendor/autoload.php");
		// See AdWordsUser constructor
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Id: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'));
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Client Secret: " . \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET'));
		
		$simple_google_creds = new \Google\AdsApi\Common\Util\SimpleGoogleCredential();
		$simple_google_creds->setCredentials(array(
			'client_id' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_ID'),
			'client_secret' => \Flux\Preferences::getPreference('GOOGLE_OAUTH_CLIENT_SECRET')
		));
		$simple_google_creds->setScope('https://www.googleapis.com/auth/adwords');
		$redirectUri = null;
		$redirectUri = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/oauth-complete';
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWordsUser with Redirect Uri: " . $redirectUri);
		
		$offline = true;
		$authorizationUrl = $simple_google_creds->getAuthorizationUrl($redirectUri, $offline);
		
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Getting new AdWords Token with Authorization Url: " . $authorizationUrl);
		
		$this->getContext()->getController()->redirect($authorizationUrl);
		return View::NONE;
	}
}

?>