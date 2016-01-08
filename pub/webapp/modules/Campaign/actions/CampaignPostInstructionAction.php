<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\DataField;
use Flux\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignPostInstructionAction extends BasicAction
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
		/* @var $offer Flux\Offer */
		$campaign = new Campaign();
		$campaign->populate($_REQUEST);
		$campaign->query();
		
		$this->getContext()->getRequest()->setAttribute("campaign", $campaign);
		
		return View::SUCCESS;
	}
	
	/**
	 * Indicates that this action requires security.
	 *
	 * @return bool true, if this action requires security, otherwise false.
	 */
	public function isSecure ()
	{
	
		return false;
	
	}
}

?>