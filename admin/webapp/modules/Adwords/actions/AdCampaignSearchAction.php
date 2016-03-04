<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Campaign;
use Flux\Offer;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignSearchAction extends BasicAction
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
		/* @var $ad_campaign \Flux\AdCampaign */
		$ad_campaign = new \Flux\AdCampaign();
		$ad_campaign->populate($_REQUEST);
		
		$this->getContext()->getRequest()->setAttribute("ad_campaign", $ad_campaign);
		return View::SUCCESS;
	}
}

?>