<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Lead;
use Flux\LeadPage;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignLeadPanePageAction extends BasicAction
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
		/* @var $lead_page \Flux\LeadPage */
		$lead_page = new \Flux\LeadPage();
		$lead_page->populate($_REQUEST);
		$lead_page->query();
		
		$this->getContext()->getRequest()->setAttribute("lead_page", $lead_page);
		
		return View::SUCCESS;
	}
}

?>