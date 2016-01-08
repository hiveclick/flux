<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadSplitFulfillAction extends BasicAction
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
		/* @var $lead_split Flux\LeadSplit */
		$lead_split = new \Flux\LeadSplit();
		$lead_split->populate($_REQUEST);
		$lead_split->query();
		
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('asc');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();

		$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		return View::SUCCESS;
	}
}

?>